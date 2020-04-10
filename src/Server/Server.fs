open System
open System.IO
open System.Threading.Tasks

open Microsoft.AspNetCore
open Microsoft.AspNetCore.Builder
open Microsoft.AspNetCore.Hosting
open Microsoft.Extensions.DependencyInjection

open FSharp.Control.Tasks.V2
open Giraffe
open Shared

open Elmish
open Elmish.Bridge
open Microsoft.Extensions.Configuration
open JWT.Builder
open JWT.Algorithms
open FSharp.Data.UnitSystems.SI.UnitSymbols

let tryGetEnv = System.Environment.GetEnvironmentVariable >> function null | "" -> None | x -> Some x


type ServerConfig =
    { Domain: string
      Secure: bool
      BaseUri: string
      Cosmos: string
      JWTSecret: string
    }

[<CLIMutable>]
type JwtClaim = { sub: string
                  nickname: string }

let serverConfig =
    let config =
        ConfigurationBuilder()
            .SetBasePath(Directory.GetCurrentDirectory())
            .AddJsonFile("local.settings.json",true)
            .AddEnvironmentVariables()
            .Build();
    let server = config.GetSection("server")
    let domain = server.["domain"]
    let secure = Boolean.Parse server.["secure"]
    let port = server.["port"] 
    { Domain = domain
      Secure = secure
      BaseUri = 
        if secure then
            "https://" + domain + (if port <> "" then ":" + port else "")
        else
            "http://" + domain + (if port <> "" then ":" + port else "")
            
      Cosmos = config.GetConnectionString("COSMOS")
      JWTSecret = server.["JWTSecret"]
      }

let publicPath =
    "SERVER_ROOT"
    |> tryGetEnv
    |> Option.orElseWith ( fun () ->
            let p =Path.GetFullPath "./public"
            if Directory.Exists p then
                Some p
            else
                None )
    |> Option.defaultValue (Path.GetFullPath "../Game/public")
let port =
    "SERVER_PORT"
    |> tryGetEnv |> Option.map uint16 |> Option.defaultValue 8085us
type PlayerState =
    | NotConnected
    | Connected of Connected
and Connected = 
    { GameId: string
      Color: Color option
      Player: string }

let connections =
  ServerHub<PlayerState,ServerMsg,ClientMsg>()


type GameRunnerCmd =
    | GetState of ((Board * int) option -> unit)
    | Exec of Board.Command * (Board.Event list -> unit)

open Newtonsoft.Json.Linq
let (|JObj|_|) (o: obj) : 'e option =
    match o with
    | :? JObject as j -> Some (j.ToObject<'e>())
    | :? JArray as j -> Some (j.ToObject<'e>())
    | :? string as s -> Some (unbox s)
    | _ -> None


type PlayerEvent = 
    { Player: string
      Event: obj }
let serialize =
    function
    | Board.Event.Started e -> "Started", box e
    | Board.Event.Played (playerid, Player.Event.MovedInField e ) -> "MovedInField", box { Player = playerid; Event = e }
    | Board.Event.Played (playerid, Player.Event.FirstCrossroadSelected e ) -> "FirstCrossroadSelected" , box { Player = playerid; Event = e }
    | Board.Event.Played (playerid, Player.Event.Annexed e ) -> "Annexed" , box { Player = playerid; Event = e }
    | Board.Event.Played (playerid, Player.Event.CutFence e ) -> "CutFence" , box { Player = playerid; Event = e }
    | Board.Event.Played (playerid, Player.Event.FenceDrawn e ) -> "FenceDrawn" , box { Player = playerid; Event = e }
    | Board.Event.Played (playerid, Player.Event.FenceLooped e ) -> "FenceLooped" , box { Player = playerid; Event = e }
    | Board.Event.Played (playerid, Player.Event.FenceRemoved e ) -> "FenceRemoved" , box { Player = playerid; Event = e }
    | Board.Event.Played (playerid, Player.Event.MovedPowerless e ) -> "MovedPowerless" , box { Player = playerid; Event = e }
    | Board.Event.Played (playerid, Player.Event.PoweredUp  ) -> "PoweredUp" , box { Player = playerid; Event = null }
    | Board.Event.Next  -> "Next" , null
    | Board.Event.PlayerDrewCards e  -> "PlayerDrewCards" , box e
    | Board.Event.GameWon e  -> "GameWon" , box e

let deserialize =
    function
    | "Started", JObj e -> [Board.Started e]
    | "MovedInField", JObj { Player = p; Event = JObj e } -> [Board.Played(p, Player.MovedInField e)]
    | "FirstCrossroadSelected", JObj { Player = p; Event = JObj e } -> [Board.Played(p, Player.FirstCrossroadSelected e)]
    | "Annexed", JObj { Player = p; Event = JObj e } -> [Board.Played(p, Player.Annexed e)]
    | "CutFence", JObj { Player = p; Event = JObj e } -> [Board.Played(p, Player.CutFence e)]
    | "FenceDrawn", JObj { Player = p; Event = JObj e } -> [Board.Played(p, Player.FenceDrawn e)]
    | "FenceLooped", JObj { Player = p; Event = JObj e } -> [Board.Played(p, Player.FenceLooped e)]
    | "FenceRemoved", JObj { Player = p; Event = JObj e } -> [Board.Played(p, Player.FenceRemoved e)]
    | "MovedPowerless", JObj { Player = p; Event = JObj e } -> [Board.Played(p, Player.MovedPowerless e)]
    | "PoweredUp", JObj { Player = p; Event = _ } -> [Board.Played(p, Player.PoweredUp )]
    | "Next", _ -> [ Board.Next]
    | "PlayerDrewCards", JObj e -> [ Board.PlayerDrewCards e ]
    | "GameWon", JObj e -> [ Board.GameWon e ]
    | _ -> []



let gameRunner container gameid =
    let stream = "game-"+gameid
    MailboxProcessor.Start(fun mailbox ->
        let rec loop state expectedVersion =
            async {
                let! msg = mailbox.Receive()


                match msg with
                | GetState reply ->
                    let! newState, nextExpectedVersion =
                       EventStore.fold deserialize container Board.evolve stream state expectedVersion
                       |> Async.AwaitTask
                    reply (Some (newState, nextExpectedVersion))
                    return! loop newState nextExpectedVersion
                | Exec (cmd, reply) ->

                    let rec exec board  expectedVersion =
                        async {
                            
                            let events = Board.decide cmd board 
                            let newBoard = List.fold Board.evolve state events



                            let! result =
                                EventStore.append serialize container stream expectedVersion events
                                |> Async.AwaitTask
                            match result with
                            | Ok nextExpectedVersion ->
                                reply events
                                return (newBoard,  nextExpectedVersion)
                            | Error e ->
                                let! newState, nextExpectedVersion =
                                   EventStore.fold deserialize container Board.evolve stream state expectedVersion
                                   |> Async.AwaitTask
                                return! exec newState  nextExpectedVersion 
                        }

                    let! newBoard, nextExpectedVersion =  exec state expectedVersion
                    return! loop newBoard nextExpectedVersion
            }


        async {
            let! board, expectedVersion  = 
                EventStore.fold deserialize container Board.evolve stream Board.initialState 0
                |> Async.AwaitTask
        


            return! loop board expectedVersion
        }
    )

     

let runner =
    let client = new Microsoft.Azure.Cosmos.CosmosClient(serverConfig.Cosmos)
    let container = client.GetContainer("crazyfarmers","crazyfarmers")
    MailboxProcessor.Start(fun mailbox ->
        let rec loop games =
            async {
                let! (gameid, msg) = mailbox.Receive()

                match msg with
                | GetState _ ->
                    let newGames =
                        match Map.tryFind gameid games with
                        | Some (game: MailboxProcessor<GameRunnerCmd>) ->
                            game.Post(msg)
                            games
                        | None -> 
                            let game = gameRunner container gameid
                            game.Post(msg)
                            Map.add gameid game games
                    return! loop newGames
                | Exec (cmd, reply) ->
                    let newGames =
                        match Map.tryFind gameid games with
                        | Some (game: MailboxProcessor<GameRunnerCmd>) ->
                            game.Post(msg)
                            games
                        | None ->
                            match cmd with
                            | Board.Start _ ->
                                let game = gameRunner container gameid
                                game.Post(msg)
                                Map.add gameid game games
                            | _ ->
                                reply []
                                games
                    return! loop newGames
                    

            }
        loop Map.empty
    )


/// Elmish init function with a channel for sending client messages
/// Returns a new state and commands
let init (claim: JwtClaim) clientDispatch () =
    clientDispatch(SyncPlayer claim.sub)
    NotConnected, Cmd.none

/// Elmish update function with a channel for sending client messages
/// Returns a new state and commands

let update claim clientDispatch msg (model: PlayerState) =
    match msg with
    | JoinGame gameid ->
        
        let state = runner.PostAndReply(fun c -> gameid, GetState c.Reply)
        match state with 
        | Some (Board game as s, version)
        | Some (Won(_, game) as s, version) ->
            let color = 
                Map.tryFind claim.sub game.Players
                |> Option.bind (function 
                    | Starting p -> Some p.Color
                    | Playing p -> Some p.Color )

            let privateGame =
                { game with
                    Players = 
                        game.Players
                        |> Map.map (fun playerid player ->
                            if playerid = claim.sub then
                                player
                            else
                                Player.toPrivate player
                        )
                }
                
            clientDispatch (Sync (Board.toState s, version))
            Connected {GameId = gameid; Color = color; Player = claim.sub } ,  Cmd.none
        | _ ->
            model, Cmd.none

    | Command cmd ->
        match model with
        | Connected g -> 
            let events = runner.PostAndReply(fun c -> g.GameId, Exec(Board.Play(claim.sub, cmd), c.Reply))



            model, Cmd.none
        | NotConnected -> model, Cmd.none


module Template =
    open Fable.React
    open Fable.React.Props
    let register =
        html [ ]
            [ head [] 
               [ title [ ] [ str "⚡Crazy Farmers and the clôtures électriques⚡"] ]
              body []
                   [ h1 [] [ str "Register" ] 
                     form [ Method "POST" ]
                          [ div [] 
                                [ label [ HtmlFor "email" ] [ str "email" ] 
                                  input [ Type "text"; Name "email"] ]
                            div [] 
                                [ label [ HtmlFor "name"] [str "name"]
                                  input [ Type "test"; Name "name"] ]
                            button [] [ str "Register" ]
                            div []
                                [ str "Alreay have an account ?"
                                  a [ Href "/auth/login"] [str "Login"]
                                ]
                            ] 
                   ]
            ]
        |> Fable.ReactServer.renderToString 
    let login =
        html [ ]
            [ head [] 
               [ title [ ] [ str "⚡Crazy Farmers and the clôtures électriques⚡"] ]
              body []
                   [ h1 [] [ str "Login" ] 
                     form [ Method "POST" ]
                          [ div [] 
                                [ label [ HtmlFor "email" ] [ str "email" ] 
                                  input [ Type "text"; Name "email"] ]
                            button [] [ str "Login" ]
                            div []
                                [ str "no account yet ? "
                                  a [ Href "/auth/register" ] [str "Register"]
                                  ]

                            ] 
                   ]
            ]
        |> Fable.ReactServer.renderToString 
        
    let auth userid =
        html [ ]
            [ head [] 
               [ title [ ] [ str "⚡Crazy Farmers and the clôtures électriques⚡"] ]
              body []
                   [ h1 [] [ str "Verification" ] 
                     form [ Method "POST"; Action "/auth/check" ]
                          [ label [ HtmlFor "code" ] [ str "code" ]
                            input [ Type "text"; Name "Code"]
                            input [ Type "hidden"; Name "Userid"; Value userid ]
                            button [] [ str "Verify" ]
                            ] 
                   ]
            ]
        |> Fable.ReactServer.renderToString 
    let test userid name =
       html [ ]
           [ head [] 
              [ title [ ] [ str "⚡Crazy Farmers and the clôtures électriques⚡"] ]
             body []
                  [ h1 [] [ str "Test" ] 
                    p [] [ str userid ]
                    p [] [ str name ]
                  ]
           ]
       |> Fable.ReactServer.renderToString 


    
let createId len = 
  Array.init len (fun _ ->
    let n = System.Security.Cryptography.RandomNumberGenerator.GetInt32(10+26+26)
    let c= 
      if n < 10 then
        int '0' + n
      elif n < 10+26 then
        int 'a' + n - 10
      else 
        int 'A' + n - 36
    char c)
  |> String

open Model


let sendChallenge config email name =
    task {
        let! userid =
            task{
            match! Storage.tryGetUserByEmail config.Cosmos email with
            | Some user -> return Some (user.userid, user.name)
            | None -> 
                match name with
                | Some name -> 
                    let mutable succeeded = false
                    let mutable userid = ""
                    while not succeeded do
                        userid <- createId 16
                        let user = 
                            { id = "user"
                              userid = userid
                              email = email
                              name = name }
                        let! response = Storage.saveUser config.Cosmos user
                        succeeded <- response
                    return Some (userid , name)
                | None -> return None }


        match userid with
        | Some (userid,name) ->
            let code = createId 16


            let challenge = 
                { id = userid + "-" + code 
                  userid = userid
                  challenge = code
                  ttl = 300<s>
                }

            do! Storage.saveChallenge config.Cosmos challenge

            Email.sendCode config.BaseUri email userid code

            return Some (userid, name)

        | None -> return None
    }


let logout : HttpHandler = fun next ctx ->
    task {
        ctx.Response.Cookies.Delete("crazyfarmers")

        return! redirectTo false "/" next ctx

    }


let login : HttpHandler = fun _ ctx ->
    task {
        return! ctx.WriteHtmlStringAsync (Template.login)
    }
let register : HttpHandler = fun _ ctx ->
    task {
        return! ctx.WriteHtmlStringAsync (Template.register)
    }


[<CLIMutable>]
type LoginForm = 
    { email : string }
[<CLIMutable>]
type RegisterForm = 
    { email : string
      name: string}

let postLogin (form: LoginForm)  : HttpHandler = fun next ctx ->
    task {
        match! sendChallenge serverConfig form.email None with
        | Some (userid,_) ->
            return! redirectTo false ("/auth/check/" + userid ) next ctx
        | None -> 
            return! redirectTo false "/auth/register" next ctx
    }

let postRegister (form: RegisterForm)  : HttpHandler = fun next ctx ->
    task {
        match! sendChallenge serverConfig form.email (Some form.name) with
        | Some (userid,_) ->
            return! redirectTo false ("/auth/check/" + userid ) next ctx
        | None -> 
            return! redirectTo false "/auth/register" next ctx
    }

[<CLIMutable>]
type AuthForm = 
    { Userid : string
      Code: string }
[<CLIMutable>]
type AuthWaitForm = 
    { Userid : string }

let auth (form: AuthWaitForm): HttpHandler = fun _ ctx ->
    task {
        return! ctx.WriteHtmlStringAsync (Template.auth form.Userid)
    }


let postAuth (form: AuthForm) : HttpHandler = fun next ctx ->
    task {
        match! Storage.tryGetChallenge serverConfig.Cosmos  form.Userid form.Code with
        | Some challenge ->
            let! user = Storage.getUserById serverConfig.Cosmos form.Userid
            let jwt = 
                JwtBuilder()
                    .WithAlgorithm(HMACSHA256Algorithm())
                    .WithSecret(serverConfig.JWTSecret)
                    .AddClaim(ClaimName.Subject, form.Userid)
                    .AddClaim(ClaimName.CasualName, user.name)
                    .AddClaim(ClaimName.ExpirationTime, DateTimeOffset.UtcNow.AddYears(1).ToUnixTimeSeconds())
                    .Encode()
            ctx.Response.Cookies.Append("crazyfarmers", jwt, Http.CookieOptions(Secure = serverConfig.Secure, Domain = (match serverConfig.Domain with "localhost" -> null | s -> s), Expires = Nullable (DateTimeOffset.UtcNow.AddYears(1))))
            return! (redirectTo false "/"  ) next ctx

        | None ->
            return! redirectTo false ("/auth/" + form.Userid ) next ctx
    }

let claim jwt = 
    if String.IsNullOrEmpty jwt then
        Error "No cookie"
    else
        try
            let serializer = JWT.Serializers.JsonNetSerializer()
            JwtBuilder()
               .WithSecret(serverConfig.JWTSecret)
               .WithSerializer(serializer)
               .WithAlgorithm(HMACSHA256Algorithm())
               .WithValidator(JWT.JwtValidator(serializer, JWT.UtcDateTimeProvider()))
               .WithUrlEncoder(JWT.JwtBase64UrlEncoder())
               .MustVerifySignature()
               .Decode<JwtClaim>(jwt)
            |> Ok
        with
        | ex -> Error ex.Message

let authTest : HttpHandler = fun next ctx ->
    task {
        match claim ctx.Request.Cookies.["crazyfarmers"] with
        | Ok claim ->
            return! ctx.WriteHtmlStringAsync (Template.test claim.sub claim.nickname)
        | Error e ->
            return! ctx.WriteHtmlStringAsync (Template.test "Not authenticated" e)


    }

let requireLogin (handler: _ -> HttpHandler) : HttpHandler = fun next ctx ->
    task {
        match claim ctx.Request.Cookies.["crazyfarmers"] with
        | Error _ ->
            return! redirectTo false "/auth/login" next ctx
        | Ok claim ->
            return! handler claim next ctx
    }
let tryLogin (handler: _ -> HttpHandler) : HttpHandler = fun next ctx ->
    task {
        match claim ctx.Request.Cookies.["crazyfarmers"] with
        | Error _ ->
            return! handler None next ctx

        | Ok claim ->
            return! handler (Some claim) next ctx
    }

let authApi =
    choose [
        GET >=> route "/logout" >=> logout
        GET >=> route "/login" >=> login
        POST >=> route "/login" >=>  bindForm<LoginForm>(None)  postLogin 
        GET >=> route "/register" >=> register
        POST >=> route "/register" >=>  bindForm<RegisterForm>(None)  postRegister 
        GET >=> route "/test" >=> authTest 
        GET >=> routeBind<AuthWaitForm> "/check/{userid}" auth
        POST >=> route "/check" >=> bindForm<AuthForm>(None) postAuth 
        GET >=> routeBind<AuthForm> "/check/{userid}/{code}" postAuth
    ]

let playGame claim = 
    Bridge.mkServer "/socket/init" (init claim) (update claim)
    |> Bridge.withServerHub connections
    |> Bridge.run Giraffe.server

module Join =
    open SharedJoin
    type Model =
        | NoGame
        | SetupGame of string 
        | JoiningGame of string
        | Started of string 

    type RunnerCmd =
        | GetState of  ((Game * int) option-> unit)
        | Exec of Command * ((Event list * int) -> unit)

    let serialize =
        function
        | Created e -> "Created", box e
        | PlayerSet e -> "PlayerSet", box e
        | Event.Started e -> "Started", box (List.toArray e)

    let deserialize =
        function
        | "Created", JObj e -> [Created e]
        | "PlayerSet", JObj e -> [PlayerSet e]
        | "Started", JObj e -> [Event.Started (Array.toList e)]
        | _ -> []

    let gameRunner container gameid =
        let stream = "join-"+gameid
        MailboxProcessor.Start(fun mailbox ->
            let rec loop state expectedVersion =
                async {
                    let! msg = mailbox.Receive()


                    match msg with
                    | GetState reply ->
                        let! newState, nextExpectedVersion =
                           EventStore.fold deserialize container evolve stream state expectedVersion
                           |> Async.AwaitTask
                        reply (Some (newState, nextExpectedVersion))
                        return! loop newState nextExpectedVersion
                    | Exec (cmd, reply) ->

                        let rec exec state expectedVersion =
                            async {
                                let events = decide cmd state 

                                if List.isEmpty events then
                                    return state, expectedVersion
                                else
                                    let! result =
                                        EventStore.append serialize container stream expectedVersion events
                                        |> Async.AwaitTask
                                    match result with
                                    | Ok nextExpectedVersion ->
                                        let newState = List.fold evolve state events
                                        reply (events, nextExpectedVersion)
                                        return (newState, nextExpectedVersion)
                                    | Error e ->
                                        let! newState, nextExpectedVersion =
                                           EventStore.fold deserialize container evolve stream state expectedVersion
                                           |> Async.AwaitTask
                                        return! exec newState nextExpectedVersion 
                            }

                        let! newState, nextExpectedVersion =  exec state expectedVersion


                        return! loop newState nextExpectedVersion
                }

            async {
                let! board, expectedVersion  = 
                    EventStore.fold deserialize container evolve stream InitialState 0
                    |> Async.AwaitTask
            


                return! loop board expectedVersion
            }
        )



        


    let setupRunner =
        let client = new Microsoft.Azure.Cosmos.CosmosClient(serverConfig.Cosmos)
        let container = client.GetContainer("crazyfarmers","crazyfarmers")

        MailboxProcessor.Start(fun mailbox ->
            let rec loop games =
                async {
                    let! id, msg = mailbox.Receive()
                    match Map.tryFind id games with
                    | Some (runner: _ MailboxProcessor) ->
                        runner.Post msg
                        return! loop games
                    | None ->
                        let runner = gameRunner container id
                        runner.Post msg
                        return! loop (Map.add id runner games)
                }
            loop Map.empty
        )
    
    
    
    let connections =
      ServerHub<Model,ServerMsg,ClientMsg>()
    
    
    let init claim clientDispatch () =
        match claim with
        | Some claim -> clientDispatch (LoggedIn (claim.sub, claim.nickname))
        | None -> ()
        NoGame, Cmd.none

    let update claim clientDispatch msg (model: Model) =
        match model, msg with
        | _ , Login email ->
            let t =
                task {
                    let! result = sendChallenge serverConfig email None
                    match result with
                    | Some(playerid,_) -> clientDispatch (StartCheck playerid)
                    | None -> () }
            t.Wait()
            model, Cmd.none
        | _ , Register (email, name) ->
            let t =
                task {
                    let! result = sendChallenge serverConfig email (Some name)
                    match result with
                    | Some(playerid,_) -> clientDispatch (StartCheck playerid)
                    | None -> () }
            t.Wait()
            model, Cmd.none


        | _ , CreateGame ->
            match claim with
            | Some claim ->
                let gameid = createId 10
                let events = setupRunner.PostAndReply(fun c -> gameid, Exec(Create { GameId = gameid; Initiator = claim.sub}, c.Reply))
                clientDispatch(Events events)
                SetupGame gameid, Cmd.none
            | _ ->
                clientDispatch(ShouldLogin )
                model, Cmd.none
                
        | _, JoinGame gameid ->
            match claim with
            | Some claim ->
                match setupRunner.PostAndReply(fun c -> gameid, GetState(c.Reply)) with
                | Some (Setup s, version) -> 
                    if s.Initiator = claim.sub then
                       clientDispatch(SyncCreate (gameid, Setup s, version))
                       SetupGame gameid, Cmd.none
                    else
                        clientDispatch(SyncJoin (gameid, Setup s, version))
                        JoiningGame gameid, Cmd.none
                | Some (Game.Started s, version) ->
                    clientDispatch(SyncStarted (gameid, Game.Started s, version))
                    Started gameid, Cmd.none
                | _ ->
                    model, Cmd.none
            | None ->
                clientDispatch(ShouldLogin )
                model, Cmd.none

        |SetupGame gameid, SelectColor color
        |JoiningGame gameid, SelectColor color ->
            match claim with
            | Some claim ->
                let events = setupRunner.PostAndReply(fun c -> gameid, Exec(SetPlayer(color, claim.sub, claim.nickname), c.Reply))
                //function | Connected c when c.GameId = gameId -> true  | _ -> falsefunction | Connected c when c.GameId = gameId -> true  | _ -> falsefunction | Connected c when c.GameId = gameId -> true  | _ -> false) (Events events)
                ()
            | None -> ()

            model, Cmd.none
        | SetupGame gameid, Start ->
            let events, version = setupRunner.PostAndReply(fun c -> gameid, Exec(Command.Start, c.Reply))
            for event in events do
                match event with
                | SharedJoin.Event.Started e ->
                    runner.PostAndReply(fun c -> gameid, GameRunnerCmd.Exec(Board.Start { Players = [ for p in e -> p.Color, p.PlayerId, p.Name ] }, c.Reply))
                    |> ignore
                | _ -> ()
            //connections.SendClientIf(function SetupGame id | JoiningGame id when id = gameid -> true | _ -> false ) (Events(events, version))
            Started gameid, Cmd.none

        | _ -> model, Cmd.none

    let subsJoin =
        let client = new Microsoft.Azure.Cosmos.CosmosClient(serverConfig.Cosmos)
        
        let container = client.GetContainer("crazyfarmers", "crazyfarmers")
        let feed =
           container.GetChangeFeedProcessorBuilder<EventStore.BatchData>("join-" + Environment.MachineName,
                fun changes ct ->
                    task {
                        for c in changes do
                            if c.p.StartsWith("join-") then
                                let events = 
                                    [ for ed in c.e do
                                        yield! deserialize (ed.c, ed.d) ]
                                let gameid = c.p.Substring(5) 
                                connections.SendClientIf (function SetupGame id | JoiningGame id when id = gameid -> true | _ -> false ) (Events (events, c.i))
                            
                        
                    } :> Task
            )
            .WithLeaseContainer(client.GetContainer("crazyfarmers", "subscriptions"))
            .WithInstanceName(Environment.MachineName)
            .WithPollInterval(TimeSpan.FromMilliseconds 500.)
            .Build()
        feed.StartAsync() |> Async.AwaitTask |> Async.RunSynchronously
        
        

let joinGame claim  =
    Bridge.mkServer "/socket/join" (Join.init claim) (Join.update claim)
    |> Bridge.withServerHub Join.connections
    |> Bridge.run Giraffe.server


let webApp =
    choose [
        subRoute "/auth" authApi
        tryLogin joinGame
        requireLogin playGame

    ]


let configureApp (app : IApplicationBuilder) =
    Storage.initialize serverConfig.Cosmos

    app.UseDefaultFiles()
       .UseStaticFiles()
       .UseWebSockets()
       .UseGiraffe webApp 

let configureServices (services : IServiceCollection) =
    services.AddGiraffe() |> ignore
    services.AddSingleton<Giraffe.Serialization.Json.IJsonSerializer>(Thoth.Json.Giraffe.ThothSerializer()) |> ignore


let subsGame =
    let client = new Microsoft.Azure.Cosmos.CosmosClient(serverConfig.Cosmos)
    
    let container = client.GetContainer("crazyfarmers", "crazyfarmers")
    let feed =
       container.GetChangeFeedProcessorBuilder<EventStore.BatchData>("game-"+Environment.MachineName,
            fun changes ct ->
                task {
                    for c in changes do
                        if c.p.StartsWith("game-") then
                            let events = 
                                [ for ed in c.e do
                                    yield! deserialize (ed.c, ed.d) ]
                            let gameId = c.p.Substring(5) 

                            match events with
                            | [Board.Started e] ->

                                Events ([ Board.Started { e with DrawPile = [] } ], c.i)
                                |> connections.SendClientIf (function Connected c when c.GameId = gameId -> true  | _ -> false )
                            | _ ->
                                let drawingPlayers =
                                    events
                                    |> List.choose (function
                                        | Board.PlayerDrewCards e -> Some e.Player
                                        | _ -> None)
                                    |> set

                                for drawingPlayer in drawingPlayers do
                                    let personalEvents =
                                        events
                                        |> List.map (
                                            function
                                            | Board.PlayerDrewCards e when e.Player <> drawingPlayer ->
                                                Board.PlayerDrewCards { e with Cards = Hand.toPrivate e.Cards }
                                            | e -> e
                                        )
                                    connections.SendClientIf (function | Connected c when c.GameId = gameId && c.Player = drawingPlayer -> true  | _ -> false) 
                                        (Events (personalEvents, c.i ))

                                let privateEvents =
                                        events
                                        |> List.map (
                                            function
                                            | Board.PlayerDrewCards e ->
                                                Board.PlayerDrewCards { e with Cards = Hand.toPrivate e.Cards }
                                            | e -> e
                                        )


                                connections.SendClientIf (function | Connected c when c.GameId = gameId && not (Set.contains c.Player drawingPlayers ) -> true  | _ -> false)
                                    (Events (privateEvents, c.i ))
                } :> Task
        )
        .WithLeaseContainer(client.GetContainer("crazyfarmers", "subscriptions"))
        .WithInstanceName(Environment.MachineName)
        .WithPollInterval(TimeSpan.FromMilliseconds 500.)
        .Build()
    feed.StartAsync() |> Async.AwaitTask |> Async.RunSynchronously


WebHost
    .CreateDefaultBuilder()
    .UseWebRoot(publicPath)
    .UseContentRoot(publicPath)
    .Configure(Action<IApplicationBuilder> configureApp)
    .ConfigureServices(configureServices)
    .UseUrls("http://0.0.0.0:" + port.ToString() + "/")
    .Build()
    .Run()
