module Client

open Fable.Core
open Elmish
open Elmish.React
open Elmish.Bridge
open Fable.React
open Fable.React.Props
open Fetch.Types
open Thoth.Fetch
open Fulma
open Thoth.Json

open Shared
open SharedClient
open Fable.Core
open Browser.Dom
open Fable.Core.JsInterop


console.log("Startgin BGAGame loading")
//module BGA =
//    [<Emit "g_gamethemeurl + $0" >]
//    let relativeUrl url = jsNative

    //[<Emit "ebg.core.gamegui" >]
    //let gameGui: obj = jsNative



type gamegui =
    abstract constructor: unit -> unit
    abstract setup: obj -> unit
    abstract onEnteringState: string ->  obj[] -> unit
    abstract onLeavingState: string -> obj[] -> unit
    abstract onUpdateActionButtons: string -> obj[] -> unit

    //[<Emit "$0.isCurrentPlayerActive()">]
//    abstract isCurrentPlayerActive: unit -> bool

//module Dojo =

//    [<Emit("define($0,$1)")>]
//    let define (modules: string[], f: obj -> ((string* obj* obj) -> unit) -> unit) = jsNative



type CrazyFarmers(dispatch: Msg -> unit) =
    
    member _.setupNotifications() =
          console.log "notifications subscriptions setup"

    interface gamegui with
        member _.constructor() =
            console.log("crazyfarmers constructor in F#")
            dispatch (Remote (Message "constructor"))
        member this.setup gameData =
            console.log("Starting game setup in F#")
            dispatch (Remote (Message "setup"))
            this.setupNotifications() 
        member _.onEnteringState stateName args =
            console.log("Entering state: " + stateName)
            dispatch (Remote (Message ("Entering state" + stateName)))
        member _.onLeavingState stateName args =
            console.log("Leaving state: " + stateName)
        member this.onUpdateActionButtons stateName args =
            console.log("onUpdateActionButtons state: " + stateName)



    


// defines the initial state and initial command (= side-effect) of the application
let init () : Model * Cmd<Msg> =
    let board= Board.initialState

    { Board = board
      LocalVersion = -1
      Synched = board
      Version = -1
      Moves = []
      PlayerId = None
      CardAction = None
      Message = "Init from client"
      Error = ""
      DashboardOpen = true
      PlayedCard = None
    }, Cmd.none


let update command model =
    match command with
    | Remote (Message msg) ->
        { model with Message = msg }, Cmd.none
    | _ -> model, Cmd.none




#if DEBUG
open Elmish.Debug
open Elmish.HMR
#endif
console.log("Startgin React loop")
Program.mkProgram init update view
|> Program.withSubscription(fun _ -> [ fun dispatch ->
        window?crazyfarmers <- CrazyFarmers(dispatch)
    ]

    

)
//|> Program.withBridgeConfig
//    (
//        Bridge.endpoint "/socket/init"
//        |> Bridge.withMapping Remote
//        |> Bridge.withRetryTime 1
//        |> Bridge.withWhenDown ConnectionLost
//    )
#if DEBUG
|> Program.withConsoleTrace
#endif
|> Program.withReactBatched "elmish-app"
#if DEBUG
|> Program.withDebugger
#endif
|> Program.run


//Dojo.define
//    ([| "dojo"
//        "dojo/_base/declare" |],
//         fun dojo declare ->
//         declare("crazyfarmers", null,CrazyFarmers()))

console.log("Define window.crazyfarmers")


        
