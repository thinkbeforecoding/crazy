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

    
// defines the initial state and initial command (= side-effect) of the application
let init () : Model * Cmd<Msg> =
    { Player = None; Game = Home }, Cmd.none
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
        currentModel, Cmd.bridgeSend(ServerMsg.SelectColor color)
    | Cancel ->
        { currentModel with Game = Home}, Cmd.none
    | Start ->
        currentModel, Cmd.bridgeSend(ServerMsg.Start)
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


    | Remote (Events events ) ->
        let newModel =
            events
            |> List.fold (fun state event ->
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
            ) currentModel.Game

        match newModel with
        | Started gameid ->
            Browser.Dom.document.location.replace("/game/" + gameid)
        | _ -> ()
        { currentModel with Game = newModel}, Cmd.none
    | Remote (SyncJoin(gameid, game)) ->
        match game with
        | Game.Setup s -> 
            { currentModel with 
                Game = JoinGame { GameId = gameid
                                  Players = s.Players}}, Cmd.none
        | _ -> currentModel, Cmd.none
    | Remote (SyncCreate(gameid, game)) ->
        match game with
        | Game.Setup s -> 
            { currentModel with
                Game = NewGame { GameId = gameid
                                 Players = s.Players}}, Cmd.none
        | _ -> currentModel, Cmd.none
    | Remote (SyncStarted(gameid, game)) ->
        Browser.Dom.document.location.replace("/game/" + gameid)
        
        currentModel, Cmd.none
    | _ -> currentModel, Cmd.none

        
let colorName =
    function
    | Blue -> "blue"
    | Yellow -> "yellow"
    | Red -> "red"
    | Purple -> "purple"

let selectPlayer dispatch color players =
    let player = Map.tryFind color players
    div [ classList ["select-player", true; "selected", player.IsSome ]
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
    
let view (model : Model) (dispatch : Msg -> unit) =
    div [  ]
      [ 
        match model.Game with
        | Home ->
            div [Style [BackgroundImage "url(/img/crazyfarmers.png)"
                        BackgroundRepeat "no-repeat"
                        BackgroundPosition "top right"
                        BackgroundSize "60%"
                        Height "100vh"
                        ]]
                [
            div [ Style [ Height "2em"
                          TextAlign TextAlignOptions.Right ]]
                [ match model.Player with
                  | Some player -> 
                       str player.Name
                       a [ Href "/auth/logout" ] [str "Logout"]
                  | None -> 
                       a [ Href "#"
                           OnClick (fun _ -> dispatch OpenLogin) ] [ str "Login" ] 
                       a [ Href "#"
                           OnClick (fun _ -> dispatch OpenRegister) ] [ str "Register" ] ]
            
            h1 [ Style [PaddingTop "10vh"] ] [ str "Join" ] 
            button [ OnClick (fun _ -> dispatch CreateNewGame) ] [ str "Nouvelle Partie" ]
            button [ OnClick (fun _ -> dispatch SelectJoin)] [ str "Rejoindre une Partie"]
            ]
        | NewGame game ->
            h1 [] [ str "New game"]
            str ("GameId: " + game.GameId)
            div []
                [ for color in [ Blue; Yellow; Purple; Red ] do
                    selectPlayer dispatch color game.Players
                ]
            div [ Style [ Clear ClearOptions.Both ]]
                [ button [ OnClick (fun _ -> dispatch Start) 
                           Disabled (not game.CanStart)
                           ] [ str "Start"]
                
                  button [ OnClick (fun _ -> dispatch Cancel) ] [ str "Cancel"]]
        | JoinGame game ->
            h1 [] [ str "Join game"]
            str ("GameId: " + game.GameId)
            div []
                [ for color in [ Blue; Yellow; Purple; Red ] do
                    selectPlayer dispatch color game.Players
                ]
            div [ Style [ Clear ClearOptions.Both ]]
                [ button [ OnClick (fun _ -> dispatch Cancel) ] [ str "Cancel"]]

        | SelectGame ->
            h1 [] [ str "Join game"]

            input [ Type "text"; Id "gameid" ]
            
            button [ OnClick (fun _ -> 
                    let elt = Browser.Dom.document.getElementById("gameid") :?> Browser.Types.HTMLInputElement

                    dispatch (Join elt.value))] [ str "Join" ]

            button [ OnClick (fun _ -> dispatch Cancel) ] [ str "Cancel"]
        | LoginPage ->
            h1 [] [ str "Login" ]
            div []
                [ label [] [ str "Email" ]
                  input [ Type "text"; Id "email" ] ]
            button [ OnClick (fun _ ->
                let email = Browser.Dom.document.getElementById("email") :?> Browser.Types.HTMLInputElement
                dispatch (Login email.value)
            )
            
                ] [ str "Login" ]
            button [ OnClick (fun _ -> dispatch Cancel)] [ str "Cancel" ]
            div []
                [ str "Don't have an accout yet ? "
                  a [ Href "#"; OnClick (fun _ -> dispatch OpenRegister) ] [ str "Register"]]
        | RegisterPage ->
            h1 [] [ str "Register" ]
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
            button [ OnClick (fun _ -> dispatch Cancel)] [ str "Cancel" ]
            div []
                [ str "Already have an accout ? "
                  a [ Href "#"; OnClick (fun _ -> dispatch OpenLogin) ] [ str "Login"]]
        | CheckPage playerid ->
            h1 [] [ str "Check" ]
            form [ Action "/auth/check"; HTMLAttr.Method "POST" ]
                [
                div []
                    [ label [] [ str "Code" ]
                      input [ Type "text"; Name "code"  ]
                      input [ Type "hidden"; Name "userid"; Value playerid]
                      ]

                button [ ] [ str "Check" ]
                ]
            button [ OnClick (fun _ -> dispatch Cancel)] [ str "Cancel" ]
            
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
