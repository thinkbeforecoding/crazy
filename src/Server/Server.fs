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
      Color: Color option}

let connections =
  ServerHub<PlayerState,ServerMsg,ClientMsg>()


type GameRunnerCmd =
    | GetState of (Board option -> unit)
    | Exec of Board.Command * (Board.Event list -> unit)

let gameRunner() =
    MailboxProcessor.Start(fun mailbox ->
        let rec loop state =
            async {
                let! msg = mailbox.Receive()
                match msg with
                | GetState reply ->
                    reply (Some state)
                    return! loop state
                | Exec (cmd, reply) ->
                    let events = Board.decide cmd state 
                    let newState = List.fold Board.evolve state events
                    reply events
                    return! loop newState
            }

        let board : Board = { Players = Map.ofList [] }
        loop board
    )

     

let runner =
    MailboxProcessor.Start(fun mailbox ->
        let rec loop games =
            async {
                let! (gameid, msg) = mailbox.Receive()

                match msg with
                | GetState reply ->
                    match Map.tryFind gameid games with
                    | Some (game: MailboxProcessor<GameRunnerCmd>) ->
                        game.Post(msg)
                    | None -> reply None
                    return! loop games
                | Exec (cmd, reply) ->
                    let newGames =
                        match Map.tryFind gameid games with
                        | Some (game: MailboxProcessor<GameRunnerCmd>) ->
                            game.Post(msg)
                            games
                        | None ->
                            match cmd with
                            | Board.Start _ ->
                                let game = gameRunner()
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
    //| SyncState ->
    //    let state = runner.PostAndReply(fun c -> GetState c.Reply)
    //    clientDispatch (Sync (Board.toState state))
    //    model, Cmd.none 
    | JoinGame gameid ->
        
        let state = runner.PostAndReply(fun c -> gameid, GetState c.Reply)
        match state with 
        | Some game ->
            let color = 
                Map.tryFind claim.sub game.Players
                |> Option.bind (function 
                    | Starting p -> Some p.Color
                    | Playing p -> Some p.Color
                    | _ -> None)
                
            clientDispatch (Sync (Board.toState game))
            Connected {GameId = gameid; Color = color } ,  Cmd.none
        | None ->
            model, Cmd.none

    | Command cmd ->
        match model with
        | Connected g -> 
            let events = runner.PostAndReply(fun c -> g.GameId, Exec(Board.Play(claim.sub, cmd), c.Reply))
            connections.SendClientIf (function | Connected c when c.GameId = g.GameId -> true  | _ -> false) (Events events)
            model, Cmd.none
        | NotConnected -> model, Cmd.none


module Template =
    open Fable.React
    open Fable.React.Props
    open Fable.ReactServer
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
            | Some user -> return Some user.userid
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
                    return Some userid 
                | None -> return None }


        match userid with
        | Some userid ->
            let code = createId 16


            let challenge = 
                { id = userid + "-" + code 
                  userid = userid
                  challenge = code
                  expiry = DateTime.UtcNow.AddMinutes(5.)
                }

            do! Storage.saveChallenge config.Cosmos challenge

            Email.sendCode config.BaseUri email userid code

            return Some userid

        | None -> return None
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
        | Some userid ->
            return! redirectTo false ("/auth/check/" + userid ) next ctx
        | None -> 
            return! redirectTo false "/auth/register" next ctx
    }

let postRegister (form: RegisterForm)  : HttpHandler = fun next ctx ->
    task {
        match! sendChallenge serverConfig form.email (Some form.name) with
        | Some userid ->
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
            if challenge.expiry >= DateTime.UtcNow then
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
            else
                return! redirectTo false ("/auth/" + form.Userid ) next ctx

                

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

let authApi =
    choose [
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
        | GetState of  string * (Game option-> unit)
        | Exec of string * Command * (Event list -> unit)
    
    let setupRunner =
        MailboxProcessor.Start(fun mailbox ->
            let rec loop games =
                async {
                    let! msg = mailbox.Receive()
                    match msg with
                    | GetState (id , reply) ->
                        Map.tryFind id games
                        |> reply 
                        return! loop games
                    | Exec (id, cmd, reply) ->
                        let state = 
                            Map.tryFind id games
                            |> Option.defaultValue InitialState

                        let events = decide cmd state 
                        let newState = List.fold evolve state events
                        reply events
                        return! loop (Map.add id newState games)
                }
    
            loop Map.empty
        )
    
    
    
    let connections =
      ServerHub<Model,ServerMsg,ClientMsg>()
    
    

    let init claim clientDispatch () =
        NoGame, Cmd.none

    let update claim clientDispatch msg (model: Model) =
        match model, msg with
        | NoGame , CreateGame ->
            let gameid = createId 10
            let events = setupRunner.PostAndReply(fun c -> Exec(gameid, Create { GameId = gameid; Initiator = claim.sub}, c.Reply))
            clientDispatch(Events events)
            SetupGame gameid, Cmd.none
        | NoGame, JoinGame gameid ->
            match setupRunner.PostAndReply(fun c -> GetState(gameid, c.Reply)) with
            | Some (Setup s) -> 
                if s.Initiator = claim.sub then
                   clientDispatch(SyncCreate (gameid, Setup s))
                   SetupGame gameid, Cmd.none
                else
                    clientDispatch(SyncJoin (gameid, Setup s))
                    JoiningGame gameid, Cmd.none
            | Some (Game.Started s) ->
                clientDispatch(SyncStarted (gameid, Game.Started s))
                Started gameid, Cmd.none
            | _ ->
                model, Cmd.none

        |SetupGame gameid, SelectColor color
        |JoiningGame gameid, SelectColor color ->
            let events = setupRunner.PostAndReply(fun c -> Exec(gameid, SetPlayer(color, claim.sub, claim.nickname), c.Reply))
            connections.SendClientIf(function SetupGame id | JoiningGame id when id = gameid -> true | _ -> false ) (Events events)

            model, Cmd.none
        | SetupGame gameid, Start ->
            let events = setupRunner.PostAndReply(fun c -> Exec(gameid, Command.Start, c.Reply))
            for event in events do
                match event with
                | SharedJoin.Event.Started e ->
                    runner.PostAndReply(fun c -> gameid, GameRunnerCmd.Exec(Board.Start { Players = [ for c,(u,n) in e -> c,u] }, c.Reply))
                    |> ignore
                | _ -> ()
            connections.SendClientIf(function SetupGame id | JoiningGame id when id = gameid -> true | _ -> false ) (Events events)
            Started gameid, Cmd.none

        | _ -> model, Cmd.none


let joinGame claim  =
    Bridge.mkServer "/socket/join" (Join.init claim) (Join.update claim)
    |> Bridge.withServerHub Join.connections
    |> Bridge.run Giraffe.server


let webApp =
    choose [
        subRoute "/auth" authApi
        requireLogin playGame
        requireLogin joinGame

    ]


let configureApp (app : IApplicationBuilder) =
    app.UseDefaultFiles()
       .UseStaticFiles()
       .UseWebSockets()
       .UseGiraffe webApp 

let configureServices (services : IServiceCollection) =
    services.AddGiraffe() |> ignore
    services.AddSingleton<Giraffe.Serialization.Json.IJsonSerializer>(Thoth.Json.Giraffe.ThothSerializer()) |> ignore

do printfn "%s" Email.smtpConfig.Host

WebHost
    .CreateDefaultBuilder()
    .UseWebRoot(publicPath)
    .UseContentRoot(publicPath)
    .Configure(Action<IApplicationBuilder> configureApp)
    .ConfigureServices(configureServices)
    .UseUrls("http://0.0.0.0:" + port.ToString() + "/")
    .Build()
    .Run()
