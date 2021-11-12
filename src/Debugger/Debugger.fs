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
open SharedClient

console.log("Startgin BGAGame loading")
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


type DebuggerModel =
    { Events: Board.Event list
      Position: int
      Model: Model }

type DebuggerMsg =
    | Msg of Msg
    | Load of string
    | Back
    | Fwd


// defines the initial state and initial command (= side-effect) of the application
let init () : DebuggerModel * Cmd<DebuggerMsg> =
    let board= Board.initialState

    { Events = []
      Position = 0
      Model = 
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
          Chat = { Show = false; Entries = []; Pop = false}
          ShowVictory = true
        } }, Cmd.none


let handleCommand (model : Model) command =
    match model.PlayerId with
    | Some playerid ->
        let events =
            Board.decide (Board.Play(playerid, command)) model.Board 
            // fitler out PlayerDrewCard since randomness cannot be computed locally
            // the actual card will be sent by the server
            
        let filteredEvent =
            events |> List.filter (function Board.PlayerDrewCards e when e.Player = playerid -> false | _ -> true )

        if List.isEmpty events then
            model, Cmd.none
        else
            let newState = List.fold Board.evolve model.Board filteredEvent
            console.log(sprintf "handleCommand version %d" (model.LocalVersion + 1))
            { model with 
                Board = newState
                LocalVersion = model.LocalVersion + 1
                Moves = Player.possibleMoves model.PlayerId newState.Board
                CardAction = None
            } , Cmd.none
    | None -> model,  Cmd.none



let update (msg : Msg) (currentModel : Model) : Model * Cmd<Msg> =
    match msg with
    | SelectFirstCrossroad c ->
        Player.SelectFirstCrossroad { Crossroad = c }
        |> handleCommand  currentModel
    | Undo ->
        Player.Undo
        |> handleCommand currentModel
    | Quit -> currentModel, Cmd.none
    | Move(dir,crossroad) ->
        Player.Move { Direction = dir; Destination = crossroad }
        |> handleCommand currentModel
    | DiscardCard(card) ->
        Player.Discard card
        |> handleCommand currentModel
    | SelectCard(card,i) ->
        { currentModel with
            CardAction = Some { Index = i; Card = card; Hidden = false
                                Ext = match card with
                                      | HayBale _ -> 
                                            if HayBales.maxReached (Board.hayBales currentModel.Board.Board) then
                                                HayBales(Remove,[],[])
                                            else
                                                HayBales(Place, [], [])
                                      | _ -> NoExt
                } }, Cmd.none
    | CancelCard ->
        { currentModel with
            CardAction = None }, Cmd.none
    | PlayCard(card) ->
        Player.PlayCard card
        |> handleCommand { currentModel with CardAction = None}
    | RemoveHayBale bale ->
        { currentModel with
            CardAction = 
                currentModel.CardAction
                |> Option.map (fun c -> 
                    { c with Ext = 
                                match c.Ext with
                                | NoExt -> HayBales(Place, [], [bale] )
                                | HayBales(_,added,removed) -> HayBales (Place, added, bale :: removed) })}, Cmd.none


    | SelectHayBale bale ->
        { currentModel with
            CardAction = 
                currentModel.CardAction
                |> Option.map (fun c ->
                    { c with Ext = 
                                
                                let action =
                                    let hb = Board.hayBales currentModel.Board.Board |> Set.add bale
                                    let currentHb =
                                        match c.Ext with
                                        | NoExt -> hb
                                        | HayBales(_,added,removed) -> hb + set added + set removed
                                    if HayBales.maxReached(currentHb) then
                                        Remove
                                    else
                                        Place

                                match c.Ext with
                                | NoExt -> HayBales(action,[bale], [])
                                | HayBales(_,added,removed) -> HayBales (action, bale :: added, removed) })}, Cmd.none
    | Go ->
        { currentModel with
            CardAction =
                currentModel.CardAction
                |> Option.map (fun c ->
                    { c with
                        Hidden = true } )
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
        
    | Remote (Sync (s, v, _)) ->
        let state = Board.ofUndoState s
        let newState =
            { currentModel with
                  Board = state
                  LocalVersion = v
                  Synched = state
                  Version = v
                  Moves =  Player.possibleMoves currentModel.PlayerId state.Board
                  }
        newState, Cmd.none// cmd

    | Remote (Events (e, version)) ->
        if version >= currentModel.Version then
            console.log (sprintf "version: %d localVersion %d currentVersion %d" version currentModel.LocalVersion currentModel.Version)
            let newState = List.fold Board.evolve currentModel.Synched e
            let newVersion = version 
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
                  Version = newVersion
                  Moves = Player.possibleMoves currentModel.PlayerId newState.Board
                  PlayedCard = newCard
            }, Cmd.none
        else
          currentModel, Cmd.none
    | Remote (Message m) ->
        { currentModel with
              Message = m
        }, Cmd.none

    | SendMessage _
    | HidePop
    | ToggleChat 
    | Remote(ReceiveMessage _) ->
        currentModel, Cmd.none
    | HideVictory ->
         {currentModel with ShowVictory = false}, Cmd.none
    | CommandNotSent(version, board) ->
        console.log(sprintf "Command not sent")
        { currentModel with
            LocalVersion = version
            Board = board
            Version = version
            Synched = board
        }, Cmd.none

let debuggerUpdate (msg : DebuggerMsg) (currentModel : DebuggerModel) : DebuggerModel * Cmd<DebuggerMsg>  =
    match msg with
    | Msg msg ->
        let newModel,cmd = update msg currentModel.Model
        { currentModel with Model = newModel }, Cmd.map Msg cmd
    | Load text ->
        let es : Board.Event list = Json.parseNativeAs text
        let board = List.fold Board.evolve Board.initialState es
        { currentModel with
            Events = es
            Position = es.Length
            Model = { currentModel.Model with Board = board }
        }, Cmd.none
    | Back ->
        let position = currentModel.Position - 1
        let board = List.fold Board.evolve Board.initialState currentModel.Events.[0..position]
        { currentModel with
            Position = position
            Model = { currentModel.Model with Board = board }
        } , Cmd.none
    | Fwd ->
        let position = (currentModel.Position + 1) % (currentModel.Events.Length + 1)
        let board = List.fold Board.evolve Board.initialState currentModel.Events.[0..position]
        { currentModel with
            Position = position
            Model = { currentModel.Model with Board = board }
        } , Cmd.none





let playerBoard board playerid (player: CrazyPlayer) dispatch =
    div [] [
        let hand = Player.hand player
        let cardCount = Hand.count hand
        let cards =
            div [ClassName "card-info"]
              [ div [ ClassName "card-count" ]
                    [ 
                      div [] [span [][str (string (cardCount))]] 
                       ]
                tooltip (String.format (Globalization.translate "{cards} cards in hand") (Map.ofList [ "cards" ==> cardCount])) ]


        playerInfo { Name = None
                     Player = player
                     IsActive = playerid = board.Table.Player
                     Goal = Some board.Goal
                     PlayingBoard = board
                     Id = playerid}
                   cards
                   dispatch

    ]


let score  player =
    str (string (Player.principalFieldSize player))

let undoView (model: Model) dispatch =
    if model.Board.UndoType <> NoUndo then
        match model.Board.Board with
        | Board b when model.PlayerId = Some b.Table.Player && not model.Board.AtUndoPoint  ->
            a [ Id "crazy-undo"; ClassName "action-button bgabutton bgabutton_blue"; Href "#"; OnClick(fun e -> dispatch Undo; e.preventDefault()) ] [ str (Globalization.translate "Undo")]
        | _ -> null
    else
        null


let playersboard model dispatch =
    match model.Board.Board with
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


            ReactDom.render( score player,  document.getElementById("player_score_" + pid))

        let undobtn = document.getElementById("crazy_undo")
        if undobtn = null then
            let parent = document.getElementById("generalactions")
            let bdiv = document.createElement "span" :?> Browser.Types.HTMLSpanElement
            bdiv.id <- "crazy_undo"
            parent.appendChild(bdiv) |> ignore
            ReactDom.render(undoView model dispatch, document.getElementById ("crazy_undo"))

    | _ -> ()


let playersHand model dispatch =
    match model.PlayerId with
    | Some playerId ->
        match model.Board.Board with
        | Board board 
        | Won(_,board) ->
            
            handView dispatch true model.PlayerId board model.CardAction (Player.hand board.Players.[playerId]) true
        | _ -> null
    | None -> 
        match model.Board.Board with
        | Board board 
        | Won(_,board) ->
            handView dispatch true model.PlayerId board None Hand.empty true
        | _ -> null
        

let playedCard dispatch card =
    match card with
    | Some c ->
      let boardDiv = document.getElementById("board") :?> Browser.Types.HTMLDivElement

      let top = 
        if isNull boardDiv then
            0.
        else
            max 0. (boardDiv.offsetTop - window.scrollY)
      div [ClassName "played-card"
           Style [Top (sprintf "calc (%fpx + 10em)" top)]
           OnAnimationEnd (fun _ -> dispatch HidePlayedCard) ]
          [ div [ClassName ("card " + Client.cardName c)] [] ]
    | None -> null
let playersDashboard model dispatch =
   match model.Board.Board with
   | InitialState -> null
   | Board board
   | Won(_,board) ->

       div [ ClassName "header"] [
           div [ ClassName "dash"] [
               span [ OnClick (fun _ -> dispatch SwitchDashboard)]
                   [ bars ]
               div [ classBaseList "dashboard" [ "closed",not model.DashboardOpen  ] ]
                   [  


                       let currentPlayer = board.Table.Player
                       for playerid in board.Table.AllPlayers do
                           let player = board.Players.[playerid]
                           div[ classBaseList "player-dashboard" [ "local", Some playerid = model.PlayerId ]] [
                               playerInfo { Name = Some(board.Table.Names.[playerid])
                                            Player = player 
                                            IsActive = currentPlayer = playerid
                                            Goal = match board.Goal with | Individual _ as g -> Some g | _ -> None 
                                            PlayingBoard = board
                                            Id = playerid } null dispatch

                               if model.DashboardOpen then
                                   handView dispatch false model.PlayerId board model.CardAction (Player.hand player) false

                               //goalView board
       
                           ]

                       match board.Goal with
                       | Common goal ->
                           commonGoal "common" board goal
                       | _ -> () ]
               ]
           if model.DashboardOpen then
               div [ ClassName "help"] [ 
                   let currentPlayer = board.Table.Player
                   let isActive = model.PlayerId.IsSome && currentPlayer = model.PlayerId.Value
                   let player = board.Players.[board.Table.Player]
                   if isActive then
                       match player with
                       | Starting p ->
                           span [] [
                               str (sprintf "Let's go ! Select a crossroad around your %s field to start." (translatedColorName p.Color) )
                           ]
                       | Playing p ->
                           span [] [
                               if Moves.canMove p.Moves then
                                   if p.Power = PowerDown then
                                       str "You're fence has been cut. Go back to your field to draw a new one. "
                                       match p.Bonus.Rutted with
                                       | 0 -> ()
                                       | 1 ->
                                           str "You're victime of a rut, you lost 2 moves. "
                                       | n ->
                                           str (sprintf "You're victime of %d ruts, you lost %d moves. " n (n*2))
                                   elif p.Moves.Done = 0 then
                                       match p.Bonus.Rutted with
                                       | 0 ->
                                           if p.Moves.Acceleration then
                                               str "You started the turn with at least 4 fences, you get an extra move. "
                                           else
                                               str "You have 3 moves this turn. "
                                       | 1 ->
                                           str "You're victime of a rut, you lost 2 moves. "
                                       | n ->
                                           str (sprintf "You're victime of %d ruts, you lost %d moves. " n (n*2))

                                        
                                   str "Select a crossroad around you to move. "
                                   if not (Hand.isEmpty p.Hand) then
                                       str "You can also play a card."
                               else
                                   let boardPos = History.createPos board
                                   match History.repetitions board.Table.Player boardPos board.History with
                                   | 1 -> str "Danger ! The game has already been exactly in this same position once at the end of your turn. On the 3rd time, the game ends in a draw. "
                                   | 2 -> str "Danger ! The game has already been exactly in this same position twice at the end of your turn. If you end now, the game ends in a draw. "
                                   | _ -> null
                                   str "Play a card, or click on your character to end your turn."


                               ]

                           if model.Board.UndoType <> NoUndo then
                               button [ ClassName "undo"; Disabled model.Board.AtUndoPoint; OnClick (fun e -> dispatch Undo; e.preventDefault()) ] [ str "Undo" ]
                       | Ko _ ->
                           span [] [
                               str (sprintf "You're eliminated. Take your revenge in the next game !")
                           ]
                   else
                       span [] [ str "Wait for your turn" ]
               
               ]
          
       ]
        

let boardView (model : Model) (dispatch : Msg -> unit) =
    match model.Board.Board with
    | InitialState -> 
        div [ ClassName "crazy-box" ]
            [ div [ClassName "board-box"]
                [ div [ClassName "board" ] [ div [] [ ] ] ]
            ]
    | Board board ->
        div [ ClassName "crazy-box" ] 
            [ 
              playersDashboard model dispatch
              playersHand model dispatch

              div [ClassName "board-box"] 
                [
                  div [ Id "board"; ClassName "board" ]
                    [ yield! boardView model.CardAction board

                      for m in model.Moves do
                        moveView dispatch m

                      yield! endTurnView dispatch model.PlayerId board

                      yield! boardCardActionView dispatch board model.CardAction 

                    ]
                  lazyViewWith (fun x y -> x = y) (playedCard dispatch) model.PlayedCard
                  ]
            ]
    | Won(winners, board) ->
        
        div [ ClassName "crazy-box" ] 
            [ playersDashboard model dispatch
              div [ClassName "board-box"]
                [ 
                  div [ ClassName "board" ]
                      [ yield! boardView model.CardAction board
            
                        if model.ShowVictory then

                            div [ ClassName "victory-box"]
                                [ div [ ClassName "victory" ] [
                                    match winners with
                                    | [ winner ] ->
                                         let player = board.Players.[winner]
                                         p [] [ str (Globalization.translate "And the winner is")]
                                         div [ ClassName ("winner " + colorName (Player.color player)) ]
                                              [ div [ ClassName "player"] [] ]
                                         p [] [ str board.Table.Names.[winner] ] 
                                    | _ -> 
                                        let players = List.map (fun p -> board.Players.[p]) winners
                                        p [] [ str (Globalization.translate "Draw game between")]
                                        div [] [
                                            for player in players ->
                                                div [ ClassName ("winner " + colorName (Player.color player)) ]
                                                    [ div [ ClassName "player"] [] ]
                                            ]
                                        p [] [ str (winners |> List.map (fun winner -> board.Table.Names.[winner]) |> String.concat " / " ) ] 

                                    p [ ClassName "back"] [ a [ Href "#"; OnClick(fun e -> dispatch HideVictory; e.preventDefault()) ] [ str (Globalization.translate "Hide") ]]

                                    ] 
                                ]
                      ]
                  lazyViewWith (fun x y -> x = y) (playedCard dispatch) model.PlayedCard

                ]
            ]

let view (model : DebuggerModel) (dispatch : DebuggerMsg -> unit) =
    div []
        [
            boardView model.Model (dispatch << Msg)
            textarea [ Id "events" ] []
            div [] [
                button [ OnClick (fun e -> 
                    let area = document.getElementById("events") :?> Browser.Types.HTMLTextAreaElement
                    dispatch (Load area.value) ) ] [ str "Load" ]

                button [ OnClick (fun _ -> dispatch Back)] [ str "<<" ] 
                button [ OnClick (fun _ -> dispatch Fwd)] [ str ">>" ]
            ]
            div [] [
                ul [] [
                    for i in (max 0 (model.Position - 3)) .. (min (model.Position + 3) (model.Events.Length - 1) ) do
                        li [ Style  (if i = model.Position then [ CSSProp.Color "red" ] else []) ] [str (sprintf "%A" model.Events.[i])]
                ]
            ]
        ]



#if DEBUG
open Elmish.Debug
open Elmish.HMR
#endif
console.log("Startgin React loop")
Program.mkProgram init debuggerUpdate view
#if DEBUG
|> Program.withConsoleTrace
#endif
|> Program.withReactBatched "elmish-app"
#if DEBUG
|> Program.withDebugger
#endif
|> Program.run




        
