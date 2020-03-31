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

let tryGetEnv = System.Environment.GetEnvironmentVariable >> function null | "" -> None | x -> Some x

let publicPath = Path.GetFullPath "../Client/public"
let port =
    "SERVER_PORT"
    |> tryGetEnv |> Option.map uint16 |> Option.defaultValue 8085us

let connections =
  ServerHub<Color option,ServerMsg,ClientMsg>()

type RunnerCmd =
    | GetState of (Board -> unit)
    | Exec of Board.Command * (Board.Event -> unit)

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
                    let event = Board.decide cmd state 
                    let newState = Board.evolve state event
                    reply event
                    return! loop newState
            }

        let board =
            [ Blue, Player.start (Parcel.center + 2 * Axe.N) (Crossroad (Axe.N + Axe.NE, CLeft))
              Yellow, Player.start (Parcel.center + 2 * Axe.S) (Crossroad (Axe.S + Axe.SW, CRight))]
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
            let event = runner.PostAndReply(fun c -> Exec(Board.Play(color, cmd), c.Reply))
            connections.BroadcastClient (Event event)
            model, Cmd.none
        | None -> model, Cmd.none



/// Connect the Elmish functions to an endpoint for websocket connections
let webApp =
    Bridge.mkServer "/socket/init" init update
    |> Bridge.withServerHub connections
    |> Bridge.run Giraffe.server


let configureApp (app : IApplicationBuilder) =
    app.UseDefaultFiles()
       .UseStaticFiles()
       .UseWebSockets()
       .UseGiraffe webApp

let configureServices (services : IServiceCollection) =
    services.AddGiraffe() |> ignore
    services.AddSingleton<Giraffe.Serialization.Json.IJsonSerializer>(Thoth.Json.Giraffe.ThothSerializer()) |> ignore

WebHost
    .CreateDefaultBuilder()
    .UseWebRoot(publicPath)
    .UseContentRoot(publicPath)
    .Configure(Action<IApplicationBuilder> configureApp)
    .ConfigureServices(configureServices)
    .UseUrls("http://0.0.0.0:" + port.ToString() + "/")
    .Build()
    .Run()
