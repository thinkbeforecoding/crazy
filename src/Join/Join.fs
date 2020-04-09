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
      Version: int
    }
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
      Players: Map<Color, string* string> }
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
    | NewGame g, Command.Start ->
        if g.CanStart then
            [ Event.Started [ for c,(p,n) in Map.toSeq g.Players -> { Color = c; PlayerId = p; Name = n}] ]
        else 
            []
    |_ -> []

let localEvolve state event =
    match state, event with
    | _, Created e ->
        NewGame { GameId = e.GameId
                  Players = Map.empty }
    | NewGame g, PlayerSet p ->
        NewGame { g with Players = 
                g.Players 
                |> Map.filter (fun k (pid,_) -> pid <> p.PlayerId)
                |> Map.add p.Color (p.PlayerId,p.Name) }
    | JoinGame g, PlayerSet p ->
        JoinGame { g with Players = 
                g.Players 
                |> Map.filter (fun k (pid,_) -> pid <> p.PlayerId)
                |> Map.add p.Color (p.PlayerId,p.Name) }
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
    | CreateNewGame  ->
        currentModel, Cmd.bridgeSend(CreateGame)
    | SelectJoin ->
        { currentModel with Game = SelectGame}, Cmd.none
    | Join gameid ->
        currentModel, Cmd.bridgeSend(ServerMsg.JoinGame gameid)
    | SelectColor color ->
        match currentModel.Player with
        | Some p ->
            (SetPlayer (color, p.PlayerId, p.Name),ServerMsg.SelectColor color)
            |> handleCommand currentModel
        | None -> currentModel, Cmd.none
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
        { currentModel with
            Player = Some { PlayerId = playerid
                            Name = name}}, Cmd.none
    | Remote(StartCheck playerid) ->
        { currentModel with
            Game = CheckPage playerid }, Cmd.none
    | Remote(ShouldLogin) ->
        { currentModel with
            Game = LoginPage }, Cmd.none


    | Remote (Events (events, version) ) ->
        if version >= currentModel.Version then
            let newModel =
                events
                |> List.fold localEvolve currentModel.Synched
            let newVersion = version+1

            match newModel with
            | Started gameid ->
                Browser.Dom.document.location.replace("/game/" + gameid)
            | _ -> ()

            
            { currentModel with
                    Game = 
                        if newVersion >= currentModel.LocalVersion then
                            newModel
                        else
                            currentModel.Game
                    LocalVersion = max currentModel.LocalVersion newVersion
                    Synched = newModel
                    Version = newVersion
                    }, Cmd.none
        else
            currentModel, Cmd.none
    | Remote (SyncJoin(gameid, game, version)) ->

        match game with
        | Game.Setup s -> 
            let newGame = 
                JoinGame { GameId = gameid
                           Players = s.Players}
            { currentModel with
                Game = newGame
                LocalVersion = version
                Synched = newGame
                Version = version
            }, Cmd.none
        | _ -> currentModel, Cmd.none
    | Remote (SyncCreate(gameid, game, version)) ->
        match game with
        | Game.Setup s -> 
            let newGame = 
                NewGame { GameId = gameid
                          Players = s.Players}
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


let view (model : Model) (dispatch : Msg -> unit) =
    div [  ]
      [ 
        match model.Game with
        | Home ->
            div [ ClassName "title" ] [
                header dispatch model.Player

                div [ ClassName "content" ]
                    [ mainTitle "Play Online"
                      button [ OnClick (fun _ -> dispatch CreateNewGame) ] [ str "New game" ]
                      button [ OnClick (fun _ -> dispatch SelectJoin)] [ str "Join game"]
                    ]
            ]
        | NewGame game ->
            div [ ClassName "content" ] [
                header dispatch model.Player
                div [ ClassName "content" ]
                    [ mainTitle "New game"
                      p [] [ str ("Game id: " + game.GameId) ]
                      p [ ClassName "info"] [ str "Send this game id to your friends, and tell them to join the game using this code."]
                      p [ ClassName "info"] [ str "Select your team, and start the game once your friends joined."]

                      div []
                          [ for color in [ Blue; Yellow; Purple; Red ] do
                            selectPlayer dispatch color game.Players model.Player
                          ]


                      div [ Style [ Clear ClearOptions.Both ]]
                          [ button [ OnClick (fun _ -> dispatch Start) 
                                     Disabled (not game.CanStart)
                                   ] [ str "Start"] 
                    
                            cancel dispatch
                           ]
                    ]
            ]
        | JoinGame game ->
            div [ ClassName "content" ] [
                header dispatch model.Player

                div [ ClassName "content" ]
                    [ mainTitle "Join game"
                      str ("Game id: " + game.GameId)

                      div [ ClassName "info"] 
                          [ str "Select your team ! If you don't select any, you can still watch the game. "  ]

                      div []
                          [ for color in [ Blue; Yellow; Purple; Red ] do
                                selectPlayer dispatch color game.Players model.Player
                          ]
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
            ]
        | LoginPage ->
            div [ ClassName "title" ] [
                div [ ClassName "content" ] [

                    mainTitle "Login"
                    div []
                        [ label [] [ str "Email" ]
                          input [ Type "text"; Id "email" ] ]
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
            ]
        | RegisterPage ->
            div [ ClassName "title" ] [
                div [ ClassName "content" ] [

                    mainTitle "Register"

                    div []
                        [ label [] [ str "Email" ]
                          input [ Type "text" ; Id "email" ] ]
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
            ]
            
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
