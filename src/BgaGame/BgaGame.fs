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

        | Player.PlayCard c ->
            match c with
            | PlayNitro One -> send ("playNitro", createObj ["power" ==>  "One"; "lock" ==> true ])
            | PlayNitro Two -> send ("playNitro", createObj ["power" ==>  "Two"; "lock" ==> true ])
            | PlayRut victim -> send ("playRut", createObj ["victim" ==> victim; "lock" ==> true ])
            | PlayHayBale [Path(Axe(q,r), side)] -> send ("playOneHayBale", createObj ["q" ==>  q; "r" ==> r; "side" ==> string side; "lock" ==> true ])
            | PlayHayBale [Path(Axe(q1,r1), side1); Path(Axe(q2,r2), side2)] -> 
                            send ("playTwoHayBale", createObj ["q1" ==>  q1; "r1" ==> r1; "side1" ==> string side1
                                                               "q2" ==> q2; "r2" ==> r2; "side2" ==> string side2
                                                               "lock" ==> true ]) 
            | PlayDynamite (Path(Axe(q,r),side)) -> send ("playDynamite", createObj ["q" ==>  q; "r" ==> r; "side" ==> string side; "lock" ==> true ])
            | PlayHighVoltage  -> send ("playHighVoltage", createObj ["value" ==> true; "lock" ==> true ])
            | PlayWatchdog  -> send ("playWatchdog", createObj ["value" ==> true; "lock" ==> true])
            | PlayHelicopter (Crossroad(Axe(q,r), side)) -> send ("playHelicopter", createObj ["q" ==>  q; "r" ==> r; "side" ==> string side; "lock" ==> true ])
            | PlayBribe (Parcel(Axe(q,r)))  ->  send ("playBribe", createObj ["q" ==>  q; "r" ==> r; "lock" ==> true ])
            | PlayHayBale _ -> () 
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
    let withExtraView view (program: Program<'arg,'model,'msg,'view>) =
        let mutable lastRequest = None
        let setState model dispatch =
            match lastRequest with
            | Some r -> window.cancelAnimationFrame r
            | _ -> ()

            lastRequest <- Some (window.requestAnimationFrame (fun _ -> view model dispatch ))
            Program.setState program model dispatch

        program
        |> Program.withSetState setState
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


let playerBoard board playerid (player: CrazyPlayer) dispatch =
    div [] [
        let hand = Player.hand player

        let cards =
            div [ ClassName "card-count " ]
                [ div [] [span [][str (string (Hand.count hand))]] ]


        playerInfo { Name = None
                     Player = player
                     IsActive = playerid = board.Table.Player
                     Goal = board.Goal }
                   cards
                   dispatch

    ]


let playersboard model dispatch =
    match model.Board with
    | Board b
    | Won(_,b) ->
        for pid, player in Map.toSeq b.Players do
            let cp = document.getElementById ("crazy_player_board_" + pid)
            if cp = null then
                let parent = document.getElementById("overall_player_board_" + pid)
                let pdiv = document.createElement "div" :?> Browser.Types.HTMLDivElement
                pdiv.id <- "crazy_player_board_" + pid
                pdiv.className <- "player-dashboard bga"
                parent.appendChild(pdiv) |> ignore 
            ReactDom.render( playerBoard b pid player dispatch,
                        document.getElementById ("crazy_player_board_" + pid))
    | _ -> ()


let playersHand model dispatch =
    match model.PlayerId with
    | Some playerId ->
        match model.Board with
        | Board board 
        | Won(_,board) ->
            
            handView dispatch model.PlayerId board model.CardAction (Player.hand board.Players.[playerId])
        | _ -> null
    | None -> null

let view (model : Model) (dispatch : Msg -> unit) =
    match model.Board with
    | InitialState -> 
        div [ClassName "board" ] [
            div [] [ ]
        
        ]
    | Board board ->
        div [] 
            [ 
              playersHand model dispatch

              div [ ClassName "board" ]
                [ yield! boardView board

                  for m in model.Moves do
                    moveView dispatch m

                  yield! endTurnView dispatch model.PlayerId board

                  yield! boardCardActionView dispatch board model.CardAction 

                ]
              lazyViewWith (fun x y -> x = y) (playedCard dispatch) model.PlayedCard
            ]
    | Won(winner, board) ->
        div []
            [ 
              div [ ClassName "board" ]
                  [ yield! boardView board
        
                    let player = board.Players.[winner]

                    div [ ClassName "victory" ]
                        [ p [] [ str "And the winner is"]
                          div [ ClassName ("winner " + colorName (Player.color player)) ]
                              [ div [ ClassName "player"] [] ]
                          p [] [ str board.Table.Names.[winner] ] 

                          ] ] ]



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
|> Program.withExtraView playersboard
#if DEBUG
|> Program.withDebugger
#endif
|> Program.run




        
