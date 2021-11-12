module BgaClient

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


let parseGame (s:string) =
    let idx = s.LastIndexOf("/")
    s.Substring(idx+1)

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
      Chat = { Entries = []; Show = false; Pop = false  }
      ShowVictory = true
    }, Cmd.none

// The update function computes the next state of the application based on the current state and the incoming events/messages
// It can also run side-effects (encoded as commands) like calling the server via Http.
// these commands in turn, can dispatch messages to which the update function will react.
let handleCommand (model : Model) command =
    match model.PlayerId with
    | Some playerid ->
        let events = Board.decide (Board.Play(playerid, command)) model.Board 
                    // fitler out PlayerDrewCard since randomness cannot be computed locally
                    // the actual card will be sent by the server
                    |> List.filter (function Board.PlayerDrewCards e when e.Player = playerid -> false | _ -> true )

        if List.isEmpty events then
            model, Cmd.none
        else
            let newState = List.fold Board.evolve model.Board events
            { model with 
                Board = newState
                LocalVersion = model.LocalVersion + 1
                Moves = Player.possibleMoves model.PlayerId newState.Board
                CardAction = None
            } , Cmd.bridgeSend(Command command)
    | None -> model,  Cmd.none




let update (msg : Msg) (currentModel : Model) : Model * Cmd<Msg> =
    match msg with
    | SelectFirstCrossroad c ->
        Player.SelectFirstCrossroad { Crossroad = c }
        |> handleCommand  currentModel

    | Undo ->
        Player.Undo
        |> handleCommand  currentModel
    | Quit ->
        Player.Quit
        |> handleCommand  currentModel
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
                        Hidden = true }) 
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
        let loc = Browser.Dom.document.location
        { currentModel with PlayerId = Some playerid }, Cmd.bridgeSend (JoinGame (parseGame loc.pathname ))
        
    | Remote (Sync (s, v, chat)) ->
        let state = Board.ofUndoState s
        let newState =
            { currentModel with
                  Board = state
                  LocalVersion = v
                  Synched = state
                  Version = v
                  Moves =  Player.possibleMoves currentModel.PlayerId state.Board
                  Chat = { Entries = chat; Show = false; Pop = not (List.isEmpty chat) }
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
                  Moves = Player.possibleMoves currentModel.PlayerId newState.Board
                  Message = "Event"
                  PlayedCard = newCard
            }, Cmd.none
        else
          { currentModel with Error = currentModel.Error + sprintf "\nskipped %d (<%d)" version currentModel.Version }, Cmd.none
    | Remote (Message m) ->
        { currentModel with
              Message = m
        }, Cmd.none
    | Remote (ReceiveMessage entry) ->
        { currentModel with
            Chat = { currentModel.Chat with Entries = currentModel.Chat.Entries @ [ entry ]
                                            Pop = not currentModel.Chat.Show }
        }, Cmd.none
        
    | SendMessage msg ->
        currentModel ,Cmd.bridgeSend(ServerMsg.SendMessage msg)
    | ToggleChat ->
        { currentModel with
            Chat = { currentModel.Chat with Show = not currentModel.Chat.Show; Pop = false }}, Cmd.none
    | HidePop ->
        { currentModel with
            Chat = { currentModel.Chat with Pop = false }}, Cmd.none
    | HideVictory ->
        currentModel, Cmd.none
    | CommandNotSent _ ->
        currentModel, Cmd.none
       
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
        
let chatView dispatch playerId (board: PlayingBoard) chat =
    div [ classBaseList "chat" ["show", chat.Show]  ]
       [ div [ Id "chat-header"; ClassName "chat-header" ] [ div[ OnClick(fun _ -> dispatch ToggleChat)] [ bars]; div []  [ str "table chat" ] ]
         if chat.Show then 
             div [ ClassName "chat-content"; Ref(fun e ->
                if not (isNull e) then
                    e.scrollTop <- e.scrollHeight ) ]
               [ div [ ClassName "chat-lines"] [
                 
                   for entry in chat.Entries do
                       if entry.Player = playerId then
                           div [ ClassName "entry you"] [ p [] [str entry.Text] ; div [ ClassName "time" ] [ str (entry.Date.ToShortTimeString())] ]
                       else
                            match Map.tryFind entry.Player  board.Table.Names with
                            | None -> 
                               div [ ClassName "entry other"] [ 
                                       div [ ClassName "author"] [
                                           div [ ClassName "name"] [ str "Visitor" ]
                                       ]
                                       p [] [str entry.Text] ; div [ ClassName "time" ] [ str (entry.Date.ToShortTimeString())] ]
                            | Some name ->
                               let color = board.Players.[entry.Player] |> Player.color
                               div [ ClassName "entry other"] [ 
                                       div [ ClassName "author"] [
                                           div [ ClassName (colorName color)] [ div [ ClassName "player" ] []] 
                                           div [ ClassName "name"] [ str name ]
                                       ]
                                       p [] [str entry.Text] ; div [ ClassName "time" ] [ str (entry.Date.ToShortTimeString())] ]
                    ]
               ]
             input [ Type "text"; Id "chat-input"; OnKeyUp (fun e -> 
                           let p = Browser.Dom.document.getElementById("chat-input") :?> Browser.Types.HTMLInputElement
                           if e.keyCode = 13. then
                               dispatch (SendMessage p.value)
                               p.value <- ""
                               ) 
                               ]
         else 
            match chat.Entries with
            | [] -> ()
            | _ -> 
                let entry = List.last chat.Entries
                if entry.Player <> playerId && chat.Pop then 
                    div [ ClassName "chat-pop"; OnClick(fun _ -> dispatch ToggleChat); OnAnimationEnd( fun e -> dispatch HidePop) ] [
                           let name = board.Table.Names.[entry.Player]
                           let color = board.Players.[entry.Player] |> Player.color
                           div [ ClassName "author"] [
                               div [ ClassName (colorName color)] [ div [ ClassName "player" ] []] 
                               div [ ClassName "name"] [ str name ]
                           ]
                           p [] [str entry.Text]  
                 
                        
                    
                    ] 
        
       ]

//let showCrossroad pos  =
//    let x,y = Pix.ofPlayer pos |> Pix.rotate
//    let key = key pos

//    div [ classBaseList "crossroad" [key, true  ]
//          Key key
//          Style [ Left (sprintf "%f%%" x)
//                  Top (sprintf "%f%%" y)
//                  BackgroundColor "#ff000080"
//                  BorderRadius "50%"
//                  ]
//          ]
//        []

//let showFence path =
//    let x,y = Pix.ofFence path |> Pix.rotate
//    let rot =
//        match path with
//        | Path(_,BN) -> "rotate(4deg)"
//        | Path(_,BNW) -> "rotate(-56deg)"
//        | Path(_,BNE) -> "rotate(64deg)"

    
//    div [ ClassName ("fence " + fenceClass path)
//          Style [ Transform rot
//                  Left (sprintf "%f%%" x)
//                  Top (sprintf "%f%%" y) ]
                  
//                  ] 
//        []

//let showTile (Parcel pos) = 
//    let x,y = Pix.ofTile pos |> Pix.rotate
//    div ([ ClassName "tile"
//           Style [ Left (sprintf "%f%%" x)
//                   Top (sprintf "%f%%" y)
//                   Opacity "0.5"
//                   ]
//           ]   )
          
//        []



let view (model : Model) (dispatch : Msg -> unit) =
    match model.Board.Board with
    | InitialState -> 
        div [ClassName "board" ] [
            div [] [ str model.Message ]
        
        ]
    | Board board ->
        div [] 
            [ playersDashboard model dispatch
              div [ ClassName "board" ]
                [ yield! boardView model.CardAction board
                  //div [ ClassName ("yellow") ]
                  //     [
                  //        for q in -4..4 do
                  //        for r in -4..4-q do
                  //            let a = Axe(q,r)
                  //            showCrossroad (Crossroad(a, CLeft))
                  //            showCrossroad (Crossroad(a, CRight))
                  //            showFence (Path (a, BNW))
                  //            showFence (Path (a, BN))
                  //            showFence (Path (a, BNE))
                  //            //showTile (Parcel a)
                              
                  //      ]


                  for m in model.Moves do
                    moveView dispatch m

                  yield! endTurnView dispatch model.PlayerId board


                  yield! boardCardActionView dispatch board model.CardAction 


                ]
              lazyViewWith (fun x y -> x = y) (playedCard dispatch) model.PlayedCard

              match model.PlayerId with
              | Some playerId ->
                  chatView dispatch playerId board model.Chat
              | None -> () 

              // button [ OnClick (fun _ -> dispatch Quit) ] [ str "Quit"] 

                         ]
    | Won(winners, board) ->
        div []
            [ playersDashboard model dispatch
              div [ ClassName "board" ]
                  [ yield! boardView model.CardAction board
        

                    div [ ClassName "victory-box" ]
                        [ div [ ClassName "victory" ]
                            [ 
                              match winners with
                              | [winner] ->
                                  let player = board.Players.[winner]
                                  p [] [ str "And the winner is"]
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


                              p [ ClassName "back"] [ a [ Href "/" ] [ str "back to home" ]]

                              ]  
                        ]
                    match model.PlayerId with
                    | Some playerId ->
                        chatView dispatch playerId board model.Chat
                    | None -> () 

                  ]
              lazyViewWith (fun x y -> x = y) (playedCard dispatch) model.PlayedCard
            ]



#if DEBUG
open Elmish.Debug
open Elmish.HMR
#endif

Program.mkProgram init update view
|> Program.withBridgeConfig
    (
        Bridge.endpoint "/socket/init"
        |> Bridge.withMapping Remote
        |> Bridge.withRetryTime 1
        |> Bridge.withWhenDown ConnectionLost
    )
#if DEBUG
|> Program.withConsoleTrace
#endif
|> Program.withReactBatched "elmish-app"
#if DEBUG
|> Program.withDebugger
#endif
|> Program.run

        


