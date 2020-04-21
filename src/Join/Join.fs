module Join

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
open SharedJoin

//open Shared

// The model holds data that you want to keep track of while the application is running
// in this case, we are keeping track of a counter
// we mark it as optional, because initially it will not be available from the client
// the initial value will be requested from server
type Model =
    { Player: Player option
      Game: PageModel
      LocalVersion: int
      Synched: PageModel
      Version: int }
and Player =
    { PlayerId: string
      Name: string}
and PageModel = 
    | Home
    | NewGame of NewGame
    | SelectGame
    | JoinGame of NewGame
    | Started of string
    | LoginPage
    | RegisterPage
    | CheckPage of string


and NewGame =
    { GameId: string
      Players: Map<Color, string* string>
      Goal: GoalType
      }
    with
    member this.CanStart = Map.count this.Players >= 2
    



// The Msg type defines what events/actions can occur while the application is running
// the state of the application changes *only* in reaction to these events
type Msg = 
    | CreateNewGame
    | SelectJoin
    | Join of string
    | Cancel
    | Start 
    | SelectColor of Color
    | SelectGoal of GoalType
    | Remote of ClientMsg
    | ConnectionLost
    | OpenLogin
    | OpenRegister
    | Login of string
    | Register of string * string


let localDecide command state =
    match state.Game, command with
    | NewGame g, SetPlayer (c,p,n) 
    | JoinGame g, SetPlayer (c,p,n) ->
        match Map.tryFind c g.Players with
        | Some _   -> []
        | None -> [ PlayerSet { Color = c; PlayerId = p; Name = n} ]
    | _, Create e ->
        if Option.isSome state.Player then
            [ Created { GameId = e.GameId; Initiator = e.Initiator }]
        else
            []
    | NewGame _, Command.SetGoal goal ->
        [ GoalSet goal]
    | NewGame g, Command.Start ->
        if g.CanStart then
            match goalFromType g.Players.Count g.Goal with
            | Some goal ->
                [ Event.Started { 
                    Players = [ for c,(p,n) in Map.toSeq g.Players -> { Color = c; PlayerId = p; Name = n}]
                    Goal = goal } ]
            | None -> []
        else 
            []
    |_ -> []

let localEvolve state event =
    match state, event with
    | _, Created e ->
        NewGame { GameId = e.GameId
                  Players = Map.empty
                  Goal = Regular
                  }
    | NewGame g, PlayerSet p ->
        NewGame { g with 
                    Players = 
                        g.Players 
                        |> Map.filter (fun _ (pid,_) -> pid <> p.PlayerId)
                        |> Map.add p.Color (p.PlayerId,p.Name) }
    | JoinGame g, PlayerSet p ->
        JoinGame { g with 
                    Players = 
                        g.Players 
                        |> Map.filter (fun _ (pid,_) -> pid <> p.PlayerId)
                        |> Map.add p.Color (p.PlayerId,p.Name) }
    | NewGame g , GoalSet goal ->
        NewGame {g with Goal = goal }
    | JoinGame g , GoalSet goal ->
        JoinGame {g with Goal = goal }
    | NewGame g,  Event.Started _ 
    | JoinGame g, Event.Started _ ->
        Started g.GameId
        
    | _ -> state



// defines the initial state and initial command (= side-effect) of the application
let init () : Model * Cmd<Msg> =
    { Player = None; 
      Game = Home
      LocalVersion = -1
      Synched = Home
      Version = -1
      }, Cmd.none
     

let handleCommand state (command, serverCmd : ServerMsg) =
    let events =
        localDecide command state
    if List.isEmpty events then
        state, Cmd.none
    else
        let newState = List.fold localEvolve state.Game events
        Browser.Dom.console.log (sprintf "Events %A" events)
        { state
            with
                Game = newState
                LocalVersion = state.LocalVersion + 1
        } , Cmd.bridgeSend(serverCmd)

// The update function computes the next state of the application based on the current state and the incoming events/messages
// It can also run side-effects (encoded as commands) like calling the server via Http.
// these commands in turn, can dispatch messages to which the update function will react.
let update (msg : Msg) (currentModel : Model) : Model * Cmd<Msg> =
    match msg with
    | CreateNewGame ->
        currentModel, Cmd.bridgeSend(CreateGame)
    | SelectJoin ->
        { currentModel with Game = SelectGame}, Cmd.none
    | Join gameid ->
        Browser.Dom.document.location.hash <- gameid
        currentModel, Cmd.bridgeSend(ServerMsg.JoinGame gameid)
    | SelectColor color ->
        match currentModel.Player with
        | Some p ->
            (SetPlayer (color, p.PlayerId, p.Name),ServerMsg.SelectColor color)
            |> handleCommand currentModel
        | None -> currentModel, Cmd.none
    | SelectGoal goal ->
        (SetGoal goal, ServerMsg.SelectGoal goal)
        |> handleCommand currentModel

    | Cancel ->
        { currentModel with Game = Home}, Cmd.none
    | Start ->
        (Command.Start,ServerMsg.Start)
        |> handleCommand currentModel
    | OpenLogin ->
        { currentModel with Game = LoginPage }, Cmd.none
    | OpenRegister ->
        { currentModel with Game = RegisterPage }, Cmd.none
    | Login email ->
        currentModel, Cmd.bridgeSend(ServerMsg.Login email)
    | Register (email,name) ->
        currentModel, Cmd.bridgeSend(ServerMsg.Register (email,name))

    | Remote(LoggedIn(playerid,name)) ->
        let cmd = 
            let hash = Browser.Dom.window.location.hash.TrimStart('#')
            if System.String.IsNullOrWhiteSpace hash then
                Cmd.none
            else
                Cmd.bridgeSend(ServerMsg.JoinGame hash)

        { currentModel with
            Player = Some { PlayerId = playerid
                            Name = name}}, cmd
    | Remote(StartCheck playerid) ->
        { currentModel with
            Game = CheckPage playerid }, Cmd.none
    | Remote(ShouldLogin) ->
        { currentModel with
            Game = LoginPage }, Cmd.none


    | Remote (Events (events, version) ) ->
        for e in events do
            match e with
            |  Event.Started _ ->
                match currentModel.Game with
                | PageModel.NewGame { GameId = gameid }
                | PageModel.JoinGame { GameId = gameid }
                | PageModel.Started gameid ->
                    Browser.Dom.console.log("starting " + gameid)
                    Browser.Dom.document.location.replace("/game/" + gameid)
                | _ -> 
                    Browser.Dom.console.log(sprintf "Started but other state: %A" currentModel.Game)
            | Event.Created e ->
                Browser.Dom.document.location.hash <- e.GameId

            | _ -> ()

        if version >= currentModel.Version then
            let newModel =
                events
                |> List.fold localEvolve currentModel.Synched

            match newModel with
            | Started gameid ->
                Browser.Dom.document.location.replace("/game/" + gameid)
            | _ -> ()

            
            { currentModel with
                    Game = 
                        if version >= currentModel.LocalVersion then
                            newModel
                        else
                            currentModel.Game
                    LocalVersion = max currentModel.LocalVersion version
                    Synched = newModel
                    Version = version
                    }, Cmd.none
        else
            currentModel, Cmd.none
    | Remote (SyncJoin(gameid, game, version)) ->
        Browser.Dom.document.location.hash <- gameid
        match game with
        | Game.Setup s -> 
            let newGame = 
                JoinGame { GameId = gameid
                           Players = s.Players
                           Goal = s.Goal
                           }
            { currentModel with
                Game = newGame
                LocalVersion = version
                Synched = newGame
                Version = version
            }, Cmd.none
        | _ -> currentModel, Cmd.none
    | Remote (SyncCreate(gameid, game, version)) ->
        Browser.Dom.document.location.hash <- gameid
        
        match game with
        | Game.Setup s -> 
            let newGame = 
                NewGame { GameId = gameid
                          Players = s.Players
                          Goal = s.Goal }
            { currentModel with
                Game = newGame
                LocalVersion = version
                Synched = newGame
                Version = version
             }, Cmd.none
        | _ -> currentModel, Cmd.none
    | Remote (SyncStarted(gameid, game,_)) ->
        Browser.Dom.document.location.replace("/game/" + gameid)
        
        currentModel, Cmd.none
    | _ -> currentModel, Cmd.none

        
let colorName =
    function
    | Blue -> "blue"
    | Yellow -> "yellow"
    | Red -> "red"
    | Purple -> "purple"

let selectPlayer dispatch color players login =
    let player = Map.tryFind color players
    let selected = player.IsSome
    let isLocalPlayer = 
        match login, player with
        | Some pl, Some(p,_) -> pl.PlayerId = p
        | _ -> false
    div [ classList ["select-player", true; "selected", selected; "local", isLocalPlayer ]
          if player.IsNone then
              OnClick (fun _ -> dispatch (SelectColor color))
          ]
        [ img [ Src (sprintf "/img/select-%s.jpg" (colorName color))]
          div [ Style [ TextAlign TextAlignOptions.Center; Height "1.5em"] ]
              [ ( match player with
                      | Some (_,name) -> str name
                      | None ->  str "" ) ]
        ]


module Colors =
    let lightGreen = "#ECF1A2" 
    let darkGreen = "#D8EFAB"
    let blue = "#A9DAF2"
  

let header dispatch player =
    div [ ClassName "header"]
        [ match player with
          | Some player -> 
                span [] [ str player.Name ]
                span [] [ a [ Href "/auth/logout" ] [str "Logout"] ]
          | None -> 
                span []
                    [ a [ Href "#"
                          OnClick (fun _ -> dispatch OpenLogin) ] [ str "Login" ] 
                       ]
                span []
                    [ a [ Href "#"
                          OnClick (fun _ -> dispatch OpenRegister) ] [ str "Register" ] ]
        ]


let mainTitle text = h1 [ ClassName "main"] [str text] 
let cancel dispatch = a [ Href "#"; OnClick (fun _ -> dispatch Cancel) ] [ str "Cancel"]
let footer =
    div [ ClassName "footer" ]
        [ span [] [ str ("v"+ Version.app)] ]

let goalName = function
    | Fast -> "Fast"
    | Regular -> "Regular"
    | Expert -> "Expert"

let goalTime = function
    | Fast -> "15-30 min"
    | Regular -> "25-50 min"
    | Expert -> "40-90 min"
    

let goalDetails goal =
    match goal with
    | Some (Individual n) -> sprintf "%d parcels/player" n
    | Some (Common n) -> sprintf "%d shared parcels" n
    | None -> ""


let goalView dispatch goal players newGame =
    let item value =
        li [ classBaseList "goal" [ "selected", goal = value]
             if newGame then 
                 OnClick (fun _ -> dispatch (SelectGoal value))
           ] [ str (goalName value)
               div [] [ str (goalTime value)]
               div [] [ str (goalDetails (goalFromType players value)) ]
           ]
        
    ul [ classBaseList "goals" [ "new-game", newGame ] ]
        [ item Fast
          item Regular
          item Expert
        ]


let view (model : Model) (dispatch : Msg -> unit) =
    div [  ]
      [ 
        match model.Game with
        | Home ->
            div [ ClassName "title" ] [
                header dispatch model.Player

                div [ ClassName "content" ]
                    [ mainTitle "Play Online"
                      button [ OnClick (fun _ -> dispatch CreateNewGame) ] [ str "Open new Arena" ]
                      button [ OnClick (fun _ -> dispatch SelectJoin)] [ str "Join an Arena"]
                    ]
                footer
            ]
        | NewGame game ->
            div [ ClassName "content" ] [
                header dispatch model.Player
                div [ ClassName "content" ]
                    [ mainTitle "Open new Arena"
                      p [] [ str ("Game id: " + game.GameId) ]
                      p [ ClassName "info"] [ str "Send this game id to your friends, and tell them to join the game using this code."]
                      p [ ClassName "info"] [ str "Select your team, and start the game once your friends joined."]

                      div [ ClassName "players" ]
                          [ for color in [ Blue; Yellow; Purple; Red ] do
                            selectPlayer dispatch color game.Players model.Player
                          ]

                      goalView dispatch game.Goal game.Players.Count true


                      div [ Style [ Clear ClearOptions.Both ]]
                          [ button [ OnClick (fun _ -> dispatch Start) 
                                     Disabled (not game.CanStart)
                                   ] [ str "Start"] 
                    
                            cancel dispatch
                           ]
                    ]
                footer
            ]
        | JoinGame game ->
            div [ ClassName "content" ] [
                header dispatch model.Player

                div [ ClassName "content" ]
                    [ mainTitle "Join an Arena"
                      str ("Game id: " + game.GameId)

                      div [ ClassName "info"] 
                          [ str "Select your team ! If you don't select any, you can still watch the game. "  ]

                      div [ ClassName "players" ]
                          [ for color in [ Blue; Yellow; Purple; Red ] do
                                selectPlayer dispatch color game.Players model.Player
                          ]
                      goalView dispatch game.Goal game.Players.Count false

                      div [ Style [ Clear ClearOptions.Both ]]
                          [ cancel dispatch]
                    ]
                ]

        | SelectGame ->
            div [ ClassName "title" ] [
                header dispatch model.Player

                div [ ClassName "content" ]
                    [ mainTitle "Join game"

                      div [ ClassName "info"] 
                          [ str "Get a game id from an friend, and paste it here to join the game."  ]

                      input [ Type "text"; Id "gameid" ]
                
                      div [ Style [ Clear ClearOptions.Both ]]
                          [ button [ OnClick (fun _ -> 
                                     let elt = Browser.Dom.document.getElementById("gameid") :?> Browser.Types.HTMLInputElement

                                     dispatch (Join elt.value))] [ str "Join" ]

                            cancel dispatch
                      ]
                    ]
                footer
            ]
        | LoginPage ->
            div [ ClassName "title" ] [
                div [ ClassName "content" ] [

                    mainTitle "Login"
                    div []
                        [ label [] [ str "Email" ]
                          input [ Type "email"; Id "email" ] ]
                    button [ OnClick (fun _ ->
                        let email = Browser.Dom.document.getElementById("email") :?> Browser.Types.HTMLInputElement
                        dispatch (Login email.value)
                    )
                    
                        ] [ str "Login" ]
                    cancel dispatch
                    div [ ClassName "switch" ]
                        [ str "Don't have an accout yet ? "
                          a [ Href "#"; OnClick (fun _ -> dispatch OpenRegister) ] [ str "Register"]]
                ]
                footer
            ]
        | RegisterPage ->
            div [ ClassName "title" ] [
                div [ ClassName "content" ] [

                    mainTitle "Register"

                    div []
                        [ label [] [ str "Email" ]
                          input [ Type "email" ; Id "email" ] ]
                    div []
                        [ label [] [ str "Name" ]
                          input [ Type "text"; Id "name" ] ]
                    button [ OnClick (fun _ ->
                        let email = Browser.Dom.document.getElementById("email") :?> Browser.Types.HTMLInputElement
                        let name = Browser.Dom.document.getElementById("name") :?> Browser.Types.HTMLInputElement
                        dispatch (Register(email.value,name.value))
                    )] [ str "Register" ]
                    cancel dispatch
                    div [ ClassName "switch"  ]
                        [ str "Already have an accout ? "
                          a [ Href "#"; OnClick (fun _ -> dispatch OpenLogin) ] [ str "Login"]]
                ]
                footer
            ]
        | CheckPage playerid ->
            div [ ClassName "title" ] [
                div [ ClassName "content" ] [

                    mainTitle "Check"

                    form [ Action "/auth/check"; HTMLAttr.Method "POST" ]
                        [
                        div []
                            [ label [] [ str "Code" ]
                              input [ Type "text"; Name "code"  ]
                              input [ Type "hidden"; Name "userid"; Value playerid]
                              ]

                        button [ ] [ str "Check" ]
                        ]
                    cancel dispatch
                ]
                footer
            ]
        | Started _ -> ()

            
      ]





#if DEBUG
open Elmish.Debug
open Elmish.HMR
#endif

Program.mkProgram init update view
|> Program.withBridgeConfig
    (
        Bridge.endpoint "/socket/join"
        |> Bridge.withMapping Remote
        |> Bridge.withRetryTime 1
        |> Bridge.withWhenDown ConnectionLost
    )
#if DEBUG
|> Program.withConsoleTrace
#endif
|> Program.withReactBatched "elmish-join"
#if DEBUG
|> Program.withDebugger
#endif
|> Program.run
