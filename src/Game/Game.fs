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
    }, Cmd.none

// The update function computes the next state of the application based on the current state and the incoming events/messages
// It can also run side-effects (encoded as commands) like calling the server via Http.
// these commands in turn, can dispatch messages to which the update function will react.
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
            } , Cmd.bridgeSend(Command command)
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
        let loc = Browser.Dom.document.location
        { currentModel with PlayerId = Some playerid }, Cmd.bridgeSend (JoinGame (parseGame loc.pathname ))
        
    | Remote (Sync (s, v, chat)) ->
        let state = Board.ofState s
        let newState =
            { currentModel with
                  Board = state
                  LocalVersion = v
                  Synched = state
                  Version = v
                  Moves =  Board.possibleMoves currentModel.PlayerId state
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
                           let name = board.Table.Names.[entry.Player]
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



let view (model : Model) (dispatch : Msg -> unit) =
    match model.Board with
    | InitialState -> 
        div [ClassName "board" ] [
            div [] [ str model.Message ]
        
        ]
    | Board board ->
        div [] 
            [ playersDashboard model dispatch
              div [ ClassName "board" ]
                [ yield! boardView board

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

                         ]
    | Won(winner, board) ->
        div []
            [ playersDashboard model dispatch
              div [ ClassName "board" ]
                  [ yield! boardView board
        
                    let player = board.Players.[winner]

                    div [ ClassName "victory" ]
                        [ p [] [ str "And the winner is"]
                          div [ ClassName ("winner " + colorName (Player.color player)) ]
                              [ div [ ClassName "player"] [] ]
                          p [] [ str board.Table.Names.[winner] ] 

                          p [ ClassName "back"] [ a [ Href "/" ] [ str "back to home" ]]

                          ]  
                    match model.PlayerId with
                    | Some playerId ->
                        chatView dispatch playerId board model.Chat
                    | None -> () 

                  ]
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

        


