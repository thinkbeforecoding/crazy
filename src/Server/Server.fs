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
    |> Option.defaultValue (Path.GetFullPath "../Client/public")
let port =
    "SERVER_PORT"
    |> tryGetEnv |> Option.map uint16 |> Option.defaultValue 8085us

let connections =
  ServerHub<Color option,ServerMsg,ClientMsg>()


type RunnerCmd =
    | GetState of (Board -> unit)
    | Exec of Board.Command * (Board.Event list -> unit)

let runner =
    MailboxProcessor.Start(fun mailbox ->
        let rec loop state =
            async {
                let! msg = mailbox.Receive()
                match msg with
                | GetState reply ->
                    reply state
                    return! loop state
                | Exec (cmd, reply) ->
                    let events = Board.decide cmd state 
                    let newState = List.fold Board.evolve state events
                    reply events
                    return! loop newState
            }

        let board =
            [ Blue, Starting (Parcel.center + 2 * Axe.N) 
              Yellow, Starting (Parcel.center + 2 * Axe.S) ]
            |> Map.ofList



        loop board
        
    )

/// Elmish init function with a channel for sending client messages
/// Returns a new state and commands
let init clientDispatch () =

    let model = runner.PostAndReply(fun c -> GetState c.Reply)
    clientDispatch (Sync (Board.toState model))
    None, Cmd.none

/// Elmish update function with a channel for sending client messages
/// Returns a new state and commands
let update clientDispatch msg (model: Color option) =
    match msg with
    | SyncState ->
        let state = runner.PostAndReply(fun c -> GetState c.Reply)
        clientDispatch (Sync (Board.toState state))
        model, Cmd.none 
    | SetColor color ->
        clientDispatch (SyncColor color)
        Some color, Cmd.none

    | Command cmd ->
        match model with
        | Some color -> 
            let events = runner.PostAndReply(fun c -> Exec(Board.Play(color, cmd), c.Reply))
            connections.BroadcastClient (Events events)
            model, Cmd.none
        | None -> model, Cmd.none

let server = 
    Bridge.mkServer "/socket/init" init update
    |> Bridge.withServerHub connections
    |> Bridge.run Giraffe.server

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
                ctx.Response.Cookies.Append("crazyfarmers", jwt, Http.CookieOptions(Secure = serverConfig.Secure, Domain = serverConfig.Domain, Expires = Nullable (DateTimeOffset.UtcNow.AddYears(1))))
                return! (redirectTo false "/"  ) next ctx
            else
                return! redirectTo false ("/auth/" + form.Userid ) next ctx

                

        | None ->
            return! redirectTo false ("/auth/" + form.Userid ) next ctx
    }
[<CLIMutable>]
type JwtClaim = { sub: string
                  nickname: string }

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

let requireLogin (handler: HttpHandler) : HttpHandler = fun next ctx ->
    task {
        match claim ctx.Request.Cookies.["crazyfarmers"] with
        | Error _ ->
            return! redirectTo false "/auth/login" next ctx
        | Ok _ ->
            return! handler next ctx
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

let webApp =
    choose [
        subRoute "/auth" authApi
        requireLogin server

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
