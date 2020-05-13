module Client

open Fable.Core
open Elmish
open Elmish.React
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

console.log("Startgin BGAGame loading")
//module BGA =
//    [<Emit "g_gamethemeurl + $0" >]
//    let relativeUrl url = jsNative

    //[<Emit "ebg.core.gamegui" >]
    //let gameGui: obj = jsNative

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
        | TypeCheck.NativeArray arr -> 
            JArray (List.ofArray (Array.map parseNative arr))
        | TypeCheck.NativeObject object ->
            [ for key in JS.Constructors.Object.keys object -> key, parseNative (get<obj> key object)  ]
            |> Map.ofList
            |> JObject
        | _ -> JNull


    let inline ofObjectLiteral (tree: obj) : 'a =
        let json = parseNative tree 
        Convert.fromJsonAs json (TypeInfo.createFrom<'a> ())
        |> unbox<'a>


[<AllowNullLiteral>]
type gamegui =
    abstract constructor: unit -> unit
    abstract setup: string * obj * int * (string * obj -> unit) -> unit
    abstract onEnteringState: string ->  obj[] -> unit
    abstract onLeavingState: string -> obj[] -> unit
    abstract onUpdateActionButtons: string -> obj[] -> unit
    abstract notifyEvents: obj * int -> unit

[<AllowNullLiteral>]
type Bridge(dispatch: ClientMsg -> unit) =
    let mutable send : string * obj -> unit = fun _ -> ()

    member _.Send(cmd) =
        
        match cmd with
        | Player.SelectFirstCrossroad( { Crossroad = Crossroad(Axe(q,r), side) } ) ->
            send ("selectFirstCrossroad", createObj [ "q" ==> q
                                                      "r" ==> r
                                                      "side" ==> string side
                                                      "lock" ==> true ])

        | Player.Move({ Direction = dir; Destination = Crossroad(Axe(q,r), side)  }) ->
            send ("move", createObj [ "direction" ==> string dir
                                      "q" ==> q
                                      "r" ==> r
                                      "side" ==> string side
                                      "lock" ==> true ])

        | Player.EndTurn ->
            send ("endTurn", createObj [ "value" ==> true; "lock" ==> true ])

        | Player.PlayCard _ -> ()
        | Player.Start _ -> ()
        //send json

        


    interface gamegui with
        member _.constructor() =
            console.log("crazyfarmers constructor in F#")
            dispatch (Message "constructor")
        member this.setup(playerId, board, version, sendCallback) =
            

            //let empty = SStarting  
            //                { SHand =  PublicHand [] 
            //                  SColor = Blue
            //                  SBonus = Bonus.empty
            //                  SParcel = Parcel.center }

            //console.log(SimpleJson.stringify empty)
            //let players = unbox<obj[][]> board
            ////let hand = players.[1].[1]?SStarting //.[1]?SHand
            //console.log(JS.JSON.stringify(board))

            let fsBoard = Serialization.ofObjectLiteral board


            send <- sendCallback
            dispatch (SyncPlayer playerId)
            dispatch (Sync (fsBoard, version))
        member _.onEnteringState stateName args =
            console.log("Entering state: " + stateName)
            dispatch (Message ("Entering state" + stateName))
        member _.onLeavingState stateName args =
            console.log("Leaving state: " + stateName)
        member this.onUpdateActionButtons stateName args =
            console.log("onUpdateActionButtons state: " + stateName)

        member _.notifyEvents(events, eventNumber) =
            console.log(events)
            let fsevents : Board.Event list = Serialization.ofObjectLiteral events
            dispatch (Events(fsevents, eventNumber))


let mutable bridge : Bridge = null

module Program =
    let withBrige f (program: Program<'arg,'model, 'msg, 'view>) =
        program
        |> Program.withSubscription(fun _ -> [ fun dispatch ->
            bridge <- Bridge(f >> dispatch )
            window?crazyfarmers <- bridge ])

module Cmd =
    let bridgeSend msg : Cmd<'msg> =
        [ fun _ -> bridge.Send(msg) ]



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


let handleCommand (model : Model) command =
    match model.PlayerId with
    | Some playerid ->
        let events = Board.decide (Board.Play(playerid, command)) model.Board 
        if List.isEmpty events then
            model, Cmd.none
        else
            let newState = List.fold Board.evolve model.Board events
            { model with 
                Board = newState
                LocalVersion = model.LocalVersion + 1
                Moves = Board.possibleMoves model.PlayerId newState
                CardAction = None
            } , Cmd.bridgeSend(command)
    | None -> model,  Cmd.none



let update (msg : Msg) (currentModel : Model) : Model * Cmd<Msg> =
    match msg with
    | SelectFirstCrossroad c ->
        Player.SelectFirstCrossroad { Crossroad = c }
        |> handleCommand  currentModel
    | Move(dir,crossroad) ->
        Player.Move { Direction = dir; Destination = crossroad }
        |> handleCommand currentModel
    | SelectCard(card,i) ->
        { currentModel with
            CardAction = Some { Index = i; Card = card; Ext = NoExt} }, Cmd.none
    | CancelCard ->
        { currentModel with
            CardAction = None }, Cmd.none
    | PlayCard(card) ->
        Player.PlayCard card
        |> handleCommand { currentModel with CardAction = None}
    | SelectHayBale bale ->
        { currentModel with
            CardAction = 
                currentModel.CardAction
                |> Option.map (fun c -> { c with Ext = FirstHayBale (false,bale) })}, Cmd.none
    | Go ->
        { currentModel with
            CardAction =
                currentModel.CardAction
                |> Option.map (fun c ->
                    { c with
                        Ext = 
                            match c.Ext with
                            | FirstHayBale(_,bale) -> FirstHayBale(true,bale)
                            | _ -> Hidden }) 
        }, Cmd.none
    | HidePlayedCard ->
        { currentModel with
            PlayedCard = None }, Cmd.none
    | EndTurn ->
        Player.EndTurn
        |> handleCommand { currentModel with CardAction = None}
    | SwitchDashboard ->
        { currentModel with DashboardOpen = not currentModel.DashboardOpen }, Cmd.none
    | ConnectionLost ->
        { currentModel with Message = "Connection lost"}, Cmd.none
    | Remote (SyncPlayer playerid) ->
        { currentModel with PlayerId = Some playerid }, Cmd.none
        
    | Remote (Sync (s, v)) ->
        let state = Board.ofState s
        let newState =
            { currentModel with
                  Board = state
                  LocalVersion = v
                  Synched = state
                  Version = v
                  Moves =  Board.possibleMoves currentModel.PlayerId state
                  }
        newState, Cmd.none// cmd

    | Remote (Events (e, version)) ->
        if version >= currentModel.Version then
            let newState = List.fold Board.evolve currentModel.Synched e
            let newVersion = version + 1
            let newCard = 
                e |> List.fold (fun card e ->
                    match e with
                    | Board.Played(_,Player.CardPlayed c ) -> Some (Card.ofPlayCard c)
                    | _ -> card
                    ) currentModel.PlayedCard
            { currentModel with
                  Board = 
                    if newVersion >= currentModel.LocalVersion then
                        newState
                    else
                        currentModel.Board
                  LocalVersion = max newVersion currentModel.LocalVersion
                  Synched = newState
                  Version = version + 1
                  Error = currentModel.Error + "\n" + string version
                  Moves = Board.possibleMoves currentModel.PlayerId newState
                  Message = "Event"
                  PlayedCard = newCard
            }, Cmd.none
        else
          { currentModel with Error = currentModel.Error + sprintf "\nskipped %d (<%d)" version currentModel.Version }, Cmd.none
    | Remote (Message m) ->
        { currentModel with
              Message = m
        }, Cmd.none


#if DEBUG
open Elmish.Debug
open Elmish.HMR
#endif
console.log("Startgin React loop")
Program.mkProgram init update view
|>  Program.withBrige Remote
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




        
