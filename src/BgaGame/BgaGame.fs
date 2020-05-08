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
open Fable.SimpleJson
open Fable.SimpleJson.TypeCheck 


console.log("Startgin BGAGame loading")
//module BGA =
//    [<Emit "g_gamethemeurl + $0" >]
//    let relativeUrl url = jsNative

    //[<Emit "ebg.core.gamegui" >]
    //let gameGui: obj = jsNative



type gamegui =
    abstract constructor: unit -> unit
    abstract setup: string *BoardState * int -> unit
    abstract onEnteringState: string ->  obj[] -> unit
    abstract onLeavingState: string -> obj[] -> unit
    abstract onUpdateActionButtons: string -> obj[] -> unit
    abstract notifyEvents: Board.Event list * int -> unit

    //[<Emit "$0.isCurrentPlayerActive()">]
//    abstract isCurrentPlayerActive: unit -> bool

//module Dojo =

//    [<Emit("define($0,$1)")>]
//    let define (modules: string[], f: obj -> ((string* obj* obj) -> unit) -> unit) = jsNative

module Serialization =
    let rec  parseNative (x: obj) =
        match x with
        | TypeCheck.NativeString str -> JString str
        | TypeCheck.NativeNumber number -> JNumber number
        | TypeCheck.NativeBool value -> JBool value
        | TypeCheck.Null _ -> JNull
        | TypeCheck.NativeArray arr -> JArray (List.ofArray (Array.map parseNative arr))
        | TypeCheck.NativeObject object ->
            [ for key in JS.Constructors.Object.keys object -> key, parseNative (get<obj> key object)  ]
            |> Map.ofList
            |> JObject
        | _ -> JNull


    let inline ofObjectLiteral (tree: obj) : 'a =
        let json = parseNative tree 
        Convert.fromJsonAs json (TypeInfo.createFrom<'a> ())
        |> unbox<'a>




        


type CrazyFarmers(dispatch: Msg -> unit) =
    

    interface gamegui with
        member _.constructor() =
            console.log("crazyfarmers constructor in F#")
            dispatch (Remote (Message "constructor"))
        member this.setup(playerId, board, version) =
            console.log("Starting game setup in F#")
            let fsBoard = Serialization.ofObjectLiteral board
            dispatch (Remote (SyncPlayer playerId))
            dispatch (Remote (Sync (fsBoard, version)))
        member _.onEnteringState stateName args =
            console.log("Entering state: " + stateName)
            dispatch (Remote (Message ("Entering state" + stateName)))
        member _.onLeavingState stateName args =
            console.log("Leaving state: " + stateName)
        member this.onUpdateActionButtons stateName args =
            console.log("onUpdateActionButtons state: " + stateName)

        member _.notifyEvents(events, eventNumber) =
            dispatch (Remote (Events(events, eventNumber)))



    


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
    | Remote (SyncPlayer playerId) ->
        { model with PlayerId = Some playerId}, Cmd.none
    | Remote (Sync (s, v)) ->
        let state = Board.ofState s
        let newState =
            { model with
                  Board = state
                  LocalVersion = v
                  Synched = state
                  Version = v
                  Moves =  Board.possibleMoves model.PlayerId state
                  }
        newState, Cmd.none// cmd

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




        
