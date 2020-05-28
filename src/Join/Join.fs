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
    | PlayWithStrangers of PublicGame[] option
    | JoinGame of NewGame
    | Started of string
    | LoginPage
    | RegisterPage
    | CheckPage of string


and NewGame =
    { GameId: string
      Players: Map<Color, string* string>
      Goal: GoalType
      Public: bool 
      }
    with
    member this.CanStart = Map.count this.Players >= 2
    



// The Msg type defines what events/actions can occur while the application is running
// the state of the application changes *only* in reaction to these events
type Msg = 
    | CreateNewGame
    | SelectJoin
    | SelectPlayWithStrangers
    | Join of string
    | Cancel
    | Start 
    | SelectColor of Color
    | SelectGoal of GoalType
    | Remote of ClientMsg
    | ConnectionLost
    | OpenLogin
    | OpenRegister
    | MakePublic
    | MakePrivate
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
            match Goal.fromType g.Players.Count g.Goal with
            | Some goal ->
                [ Event.Started { 
                    Players = [ for c,(p,n) in Map.toSeq g.Players -> { Color = c; PlayerId = p; Name = n}]
                    Goal = goal } ]
            | None -> []
        else 
            []
    | NewGame _ , Command.Leave _ ->
        [ Cancelled ]
    | JoinGame _, Command.Leave p ->
        [ Leaved p]
    | NewGame _, Command.MakePublic ->
        [ MadePublic ]
    | NewGame _, Command.MakePrivate ->
        [ MadePrivate ]
    |_ -> []

let localEvolve playerId state event =
    match state, event with
    | _, Created e ->
        NewGame { GameId = e.GameId
                  Players = Map.empty
                  Goal = Regular
                  Public = false
                  }
    | NewGame g, PlayerSet p ->
        NewGame { g with 
                    Players = 
                        g.Players 
                        |> Map.filter (fun _ (pid,_) -> pid <> p.PlayerId)
                        |> Map.add p.Color (p.PlayerId,p.Name) }
    | NewGame g, Leaved p ->
        if p = playerId then
            Home
        else
            NewGame { g with 
                        Players = 
                            g.Players 
                            |> Map.filter (fun _ (pid,_) -> pid <> p) }
    | NewGame _,  Cancelled ->
        Home
    | NewGame g , GoalSet goal ->
        NewGame {g with Goal = goal }
    | NewGame g, MadePublic ->
        NewGame { g with Public = true}
    | NewGame g, MadePrivate ->
        NewGame { g with Public = false}
    | JoinGame g, PlayerSet p ->
        JoinGame { g with 
                    Players = 
                        g.Players 
                        |> Map.filter (fun _ (pid,_) -> pid <> p.PlayerId)
                        |> Map.add p.Color (p.PlayerId,p.Name) }
    | JoinGame g, Leaved p ->
        if p = playerId then
            Home
        else
            JoinGame { g with 
                        Players = 
                            g.Players 
                            |> Map.filter (fun _ (pid,_) -> pid <> p) }
    | JoinGame _, Event.Cancelled ->
        Home

    | JoinGame g , GoalSet goal ->
        JoinGame {g with Goal = goal }
    | JoinGame g, MadePublic ->
        JoinGame { g with Public = true}
    | JoinGame g, MadePrivate ->
        JoinGame { g with Public = false}

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
        let playerId = 
            match state.Player with
            | Some p -> p.PlayerId
            | None -> ""
        let newState = List.fold (localEvolve playerId) state.Game events
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
        { currentModel with
            Synched = Home
            Version = -1
            LocalVersion = -1 }
        , Cmd.bridgeSend(CreateGame)
    | SelectJoin ->
        { currentModel with Game = SelectGame }, Cmd.none
    | SelectPlayWithStrangers ->
        { currentModel with Game = PlayWithStrangers  None }, Cmd.bridgeSend(ServerMsg.Select)

    | Remote (UpdatePublicGames games) ->
        match currentModel.Game with
        | PlayWithStrangers _ -> { currentModel with Game = PlayWithStrangers (Some games) }, Cmd.none
        | _ -> currentModel, Cmd.none

    | Join gameid ->
        Browser.Dom.document.location.hash <- gameid
        { currentModel with
            Synched = Home
            Version = -1
            LocalVersion = -1 }
        , Cmd.bridgeSend(ServerMsg.JoinGame gameid)
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
        Browser.Dom.document.location.hash <- ""
 

        { currentModel with 
            Game = Home
            Version = -1
            LocalVersion = -1
            Synched = Home }, 
            match currentModel.Game with
            | JoinGame _
            | NewGame _ ->
                Cmd.bridgeSend(ServerMsg.Leave)
            | _ -> Cmd.none
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
    | MakePublic ->
        { currentModel with Game = 
                            match currentModel.Game with
                            | PageModel.NewGame g -> NewGame { g with Public = true}
                            | g -> g
        }, Cmd.bridgeSend(ServerMsg.MakePublic)
    | MakePrivate ->
        { currentModel with Game = 
                                match currentModel.Game with
                                | PageModel.NewGame g -> NewGame { g with Public = false}
                                | g -> g
        }, Cmd.bridgeSend(ServerMsg.MakePrivate)

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
            | Event.Cancelled ->
                Browser.Dom.document.location.hash <- ""
            | _ -> ()

        if version >= currentModel.Version then
            let playerId =
                match currentModel.Player with
                | Some p -> p.PlayerId
                | None -> ""
            let newModel =
                events
                |> List.fold (localEvolve playerId) currentModel.Synched

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
                    }, 
                    if List.exists (function Event.Cancelled -> true | _ -> false) events && currentModel.Game <> Home then
                        Cmd.bridgeSend Leave
                    else
                        Cmd.none
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
                           Public = s.Public
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
                          Goal = s.Goal
                          Public = s.Public
                          }
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
        [ 
          span []
                [ a [ Href "https://www.facebook.com/TheFreaky42/"] [ i [ ClassName "fab fa-facebook-square"] [] ]]
          span []
                [ a [ Href "https://twitter.com/crazy_farmers"] [ i [ ClassName "fab fa-twitter"] [] ]]
          span []
                [ a [ Href "https://www.instagram.com/crazyfarmers.lejeu/"] [ i [ ClassName "fab fa-instagram"] [] ]]
          span []
                [ a [ Href "https://www.kickstarter.com/projects/1486112993/crazy-farmers-and-the-clotures-electriques"] [ i [ ClassName "fab fa-kickstarter"] [] ]]

          match player with
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
               div [] [ str (goalDetails (Goal.fromType players value)) ]
           ]
        
    ul [ classBaseList "goals" [ "new-game", newGame ] ]
        [ item Fast
          item Regular
          item Expert
        ]

let duration (d: System.DateTime) =
    let span = System.DateTime.UtcNow - d
    if span.TotalHours > 1. then
        sprintf "more than %dh" (int span.TotalHours)
    elif span.TotalMinutes > 1. then
        sprintf "%dm" (int span.TotalMinutes)
    else
        "a few seconds"


    

let view (model : Model) (dispatch : Msg -> unit) =
    div [  ]
      [ 
        match model.Game with
        | Home ->
            div [ ClassName "title" ] [
                header dispatch model.Player

                div [ ClassName "content" ]
                    [ mainTitle "Play Online"

                      div [ ClassName "intro" ] [
                        str "2042... L'agriculture a périclité, les fermiers ont dû se reconvertir et développer
                             l’Ultimate Farming Championship (UFC pour les intimes) mélange improbable de Monster Truck,
                             catch mexicain et foire agricole, qui a rapidement fait le Buzz sur les écrans.
                             À bord de votre tracteur trafiqué, entrez à votre tour dans l’arène pour affronter
                             vos adversaires sans fois ni loi pour  devenir le meilleur poseur de Clôtures
                             Électriques du monde\u00a0!"
                      ]
                      button [ OnClick (fun _ -> dispatch CreateNewGame) ] [ str "Open new Arena" ]
                      button [ OnClick (fun _ -> dispatch SelectJoin)] [ str "Join an Arena"]
                      button [ OnClick (fun _ -> dispatch SelectPlayWithStrangers)] [ str "Play with Strangers"]

                      div [ ] [
                      
                          h2 [] [ str "Aide" ]
                          ul [] [
                              li [] [a [ Href "/rules/cf-jeu-en-ligne.pdf"] [ str "Création de votre compte" ]]
                              li [] [a [ Href "/rules/cf-jeu-en-ligne-2.pdf"] [ str "Le jeu"]]
                              li [] [a [ Href "/rules/cf-jeu-en-ligne-bonus.pdf"] [ str "Les bonus"]]
                              li [] [a [ Href "/rules/CrazyFarmers-fr.pdf"] [ str "Les règles complètes (fr)" ]]
                              li [] [a [ Href "/rules/CrazyFarmers-en.pdf"] [ str "Complete rules (en)" ]]
                              li [] [a [ Href "https://www.youtube.com/watch?v=ol4c1J6vW1c&t=1610s"] [str "Présentation Vidéo chez So Many Backers "; i [ClassName "fab fa-youtube"] []]]
                          ]
                      ]
                    ]
                footer
            ]
        | NewGame game ->
            div [ ClassName "content" ] [
                header dispatch model.Player
                div [ ClassName "content" ]
                    [ mainTitle "Open new Arena"
                      div [ ClassName "text"]
                          [ p [] [ str ("Arena id: " + game.GameId) ]

                            div [ ClassName "public-private"] [
                                input [ Type "Radio"; Name "Public"; Id "Private"; Value "Private"; Checked (not game.Public); OnClick (fun _ -> dispatch MakePrivate) ] 
                                label [ HtmlFor "Private" ] [ str "Play with friends" ]
                                div [] [
                                    if not game.Public then
                                            p [ ClassName "info"] [ str "Send this game id to your friends, and tell them to join the game using this code."]
                                            p [ ClassName "info"] [ str "You can also send them "; a [ Href (Browser.Dom.document.location.ToString())  ] [ str "the arena join link."] ]
                                ]
                                input [ Type "Radio"; Name "Public"; Id "Public"; Value "Public"; Checked game.Public; OnClick(fun _ -> dispatch MakePublic)]
                                label [ HtmlFor "Public"] [ str "Play with strangers" ]
                                div [] [
                                    if game.Public then
                                        p [ ClassName "info"] [ str "This game will appear publicly on the website so that other players can join it from the Play with Strangers section."]
                                        p [ ClassName "info" ] [ str "Players you don't know may join." ]
                                ]
                            ]

                            p [ ClassName "info"] [ str "Select your team, and start the game once other players joined."]
                          ]

                      div [ ClassName "players" ]
                          [ for color in [ Blue; Yellow; Purple; Red ] do
                            selectPlayer dispatch color game.Players model.Player
                          ]

                      goalView dispatch game.Goal game.Players.Count true


                      div [ Style [ Clear ClearOptions.Both ]]
                          [ button [ OnClick (fun _ -> dispatch Start) 
                                     Disabled (not game.CanStart)
                                   ] [ str "Start" 
                                       if not game.CanStart then
                                        str " (not enough players)"] 
                    
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
                      str ("Arena id: " + game.GameId)

                      div [ ClassName "info"] 
                          [ str "Select your team ! If you don't select any, you can still watch the game. "  ]

                      div [ ClassName "players" ]
                          [ for color in [ Blue; Yellow; Purple; Red ] do
                                selectPlayer dispatch color game.Players model.Player
                          ]
                      goalView dispatch game.Goal game.Players.Count false

                      div [ Style [ Clear ClearOptions.Both ]]
                          [ cancel dispatch ]
                    ]
                ]

        | SelectGame ->
            div [ ClassName "title" ] [
                header dispatch model.Player

                div [ ClassName "content" ]
                    [ mainTitle "Join an Arena"

                      div [ ClassName "info"] 
                          [ str "Get an Arena id from an friend, and paste it here to join the game."  ]

                      input [ Type "text"; Id "gameid"; AutoFocus true; OnKeyUp (fun e -> if e.keyCode = 13. then dispatch (Join  e.Value) ) ]
                
                      div [ Style [ Clear ClearOptions.Both ]]
                          [ button [ Type "submit"; OnClick (fun _ -> 
                                     let elt = Browser.Dom.document.getElementById("gameid") :?> Browser.Types.HTMLInputElement

                                     dispatch (Join elt.value))] [ str "Join" ]

                            cancel dispatch
                      ]


                    ]
                footer
            ]

        | PlayWithStrangers games ->
            div [ ClassName "title" ] [
                header dispatch model.Player

                div [ ClassName "content" ]
                    [ mainTitle "Play with Strangers"

                      div [ ClassName "info public-arenas" ]
                          [ 
                            match games with
                            | None -> ()
                            | Some [||] ->
                                p [] [ str "There is currently no public arena to play with strangers" ]
                                p [] [ str "But you can still "
                                       a [ Href "#"; OnClick (fun _ -> dispatch CreateNewGame) ] [ str "open new Arena" ] 
                                       str " yourself"
                                        ]
                            | Some games ->
                                p [] [ str "Join a game with strangers in a public arena !" ]
                                p [] [ str "Select an arena below:" ]

                                ul [] [
                                    for game in games do
                                        li [] [ a [ OnClick (fun _ -> dispatch (Join game.Id))] [ 
                                            match game.Goal with
                                            | Fast -> str "Fast game "
                                            | Regular -> str "Regular game"
                                            | Expert -> str "Expert game"
                                            str " with "
                                            str (String.concat " / " (Array.map snd game.Players))
                                            str " created "
                                            str (duration game.Created)
                                            str " ago ("

                                            str game.Id
                                            str ")"
                                            ] ]
                                    ]
                      ]
                      div [ Style [ Clear ClearOptions.Both ]]
                          [ cancel dispatch ]


                    ]
                footer
            ]

        | LoginPage ->
            div [ ClassName "title" ] [
                div [ ClassName "content" ] [

                    mainTitle "Login"
                    div []
                        [ div [ ClassName "text "]
                            [ p [] [ str "Please provide the email you used for your registration. You will receive a verification code by email." ] ]
                          label [] [ str "Email" ]
                          input [ Type "email"; Id "email"; AutoFocus true; OnKeyUp (fun e -> if e.keyCode = 13. then dispatch (Login  e.Value) ) ] ]

                    button [ Type "submit"; OnClick (fun _ ->
                        let email = Browser.Dom.document.getElementById("email") :?> Browser.Types.HTMLInputElement
                        dispatch (Login email.value)
                    ) ] [ str "Login" ] 
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

                    div [ ClassName "text "]
                        [ p [] [ str "Please enter your email for account verification. No password is required, you will simply receive an email containing a verification code." ]
                          p [ ClassName "info" ] [ str "Your email is used for account verification only and will not be used for any other purpose or shared with any other party." ] ]

                    div []
                        [ label [] [ str "Email" ]
                          input [ Type "email" ; Id "email"; AutoFocus true ] ]
                    div []
                        [ label [] [ str "Name" ]
                          input [ Type "text"; Id "name" ] ]
                    button [ Type "submit"; OnClick (fun _ ->
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

                    mainTitle "Verification"

                    div [ ClassName "text" ]
                        [ p [] [ str "Check your email. You should have received a message from Crazy Farmers with your verification code." ] ]

                    form [ Action "/auth/check"; HTMLAttr.Method "POST" ]
                        [
                        div []
                            [ label [] [ str "Code" ]
                              input [ Type "text"; Name "code"; AutoFocus true  ]
                              input [ Type "hidden"; Name "userid"; Value playerid]
                              ]

                        button [ Type "submit" ] [ str "Verification" ]
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
