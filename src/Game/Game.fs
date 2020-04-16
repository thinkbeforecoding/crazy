module Client

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



module Pix =
    let size = 5.75
    let offsetX = 48.5
    let offsetY = 47.8

    let ofPoint (Axe(q,r)) = 
        let x = size * (3./2. * float q ) + offsetX
        let y = size * (sqrt 3./2. * float q  +  sqrt 3. * float r) + offsetY
        x,y

    let ofTile pos =
        let x,y = ofPoint pos  
        x - size /2. , y - size /2.

    let playerSize = 0.
    let ofPlayer (Crossroad (tile,corner)) =
        let tx,ty = ofPoint tile 
        let x = 
            match corner with
            | CLeft  -> tx - size * 0.9 - playerSize/2.
            | CRight -> tx + size * 1.1 - playerSize/2.
        let y = ty - playerSize/2.
        x,y
          

    let fenceWidth = 2.
    let ofFence (Path(tile,border)) =
        let tx,ty = ofPoint tile
        
        match border with
        | BN -> tx - size * 0.1,ty-size*0.73
        | BNW -> tx - size*0.77,ty-size*0.31
        | BNE -> tx + size*0.74,ty-size*0.27



    let teta = -0.063
    let cost = cos teta 
    let sint = sin teta
    let rotate (x,y) =
        let cx = x-offsetX
        let cy = y-offsetY
        offsetX + cx * cost + cy * sint, offsetY - cx * sint + cy * cost 
    let translate (dx,dy) (x,y) =
        x+dx, y+dy
        

type CardExt =
    | NoExt
    | FirstHayBale of Path

type CardAction = 
    { Index: int
      Card: Card
      Ext: CardExt }


// The model holds data that you want to keep track of while the application is running
// in this case, we are keeping track of a counter
// we mark it as optional, because initially it will not be available from the client
// the initial value will be requested from server
type Model = {
    Board: Board
    LocalVersion: int
    Synched: Board
    Version: int
    PlayerId: string option
    CardAction: CardAction option
    Moves: Move list 
    Message: string
    Error : string
    DashboardOpen: bool
    }

// The Msg type defines what events/actions can occur while the application is running
// the state of the application changes *only* in reaction to these events
type Msg =
    | Move of Direction * Crossroad
    | PlayCard of PlayCard
    | SelectFirstCrossroad of Crossroad
    | SelectCard of Card * int
    | SelectHayBale of Path
    | CancelCard
    | SwitchDashboard
    | EndTurn
    | Remote of ClientMsg
    | ConnectionLost

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
    | None -> model, Cmd.none


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
                |> Option.map (fun c -> { c with Ext = FirstHayBale bale })}, Cmd.none
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
            }, Cmd.none
        else
          { currentModel with Error = currentModel.Error + sprintf "\nskipped %d (<%d)" version currentModel.Version }, Cmd.none
    | Remote (Message m) ->
        { currentModel with
              Message = m
        }, Cmd.none


let colorName color = 
    match color with
    | Blue -> "blue"
    | Yellow -> "yellow"
    | Purple -> "purple"
    | Red -> "red"
    

let parcel (Parcel pos) attr = 
    let x,y = Pix.ofTile pos |> Pix.rotate
    div ([ ClassName "tile"
           Style [ Left (sprintf "%fvw" x)
                   Top (sprintf "%fvw" y)
                   ]]  @ attr)
          
        []
let tile (Parcel pos) f = 
    let x,y = Pix.ofTile pos |> Pix.rotate
    div ([ ClassName "tile"
           Style [ Left (sprintf "%fvw" x)
                   Top (sprintf "%fvw" y)
                   ]
           OnClick f
                   ]   )
          
        []

let barn (Parcel pos) occupied = 
    let x,y = Pix.ofTile pos |> Pix.rotate
    div [ classBaseList "barn" [ "occupied", occupied ]
          Style [ Left (sprintf "%fvw" x)
                  Top (sprintf "%fvw" y)
                   ]] 
        []

let drawplayer selected (x,y) =
    div [ classBaseList "player" ["selected", selected]
          Style [ Left (sprintf "%fvw" x)
                  Top (sprintf "%fvw" y)
                   ]] 
        []

let player active pos =
    Pix.ofPlayer pos
    |> Pix.rotate
    |> drawplayer active

let drawcrossroad pos f =
    let x,y = Pix.ofPlayer pos |> Pix.rotate
    div [ ClassName "crossroad"
          Style [ Left (sprintf "%fvw" x)
                  Top (sprintf "%fvw" y) ]
          match f with
          | Ok f -> OnClick f
          | _ -> () ] 
        []

let crossroad pos f = drawcrossroad pos (Ok f)
let blockedCrossroad pos e = drawcrossroad pos (Error e)

let singleFence path =
    let x,y = Pix.ofFence path |> Pix.rotate
    let rot =
        match path with
        | Path(_,BN) -> "rotate(4deg)"
        | Path(_,BNW) -> "rotate(-56deg)"
        | Path(_,BNE) -> "rotate(64deg)"

    
    div [ ClassName "fence"
          Style [ Transform rot
                  Left (sprintf "%fvw" x)
                  Top (sprintf "%fvw" y) ]] 
        []

let haybale p =
    let x,y = Pix.ofFence p |> Pix.translate (0.2,-0.8) |> Pix.rotate
    div [ ClassName "hay-bale"
          Style [ Left (sprintf "%fvw" x)
                  Top (sprintf "%fvw" y) ]
          ]
        []

let path p f =
    let x,y = Pix.ofFence p |> Pix.translate (0.2,-0.8) |> Pix.rotate
    div [ ClassName "path"
          Style [ Left (sprintf "%fvw" x)
                  Top (sprintf "%fvw" y) ]
          OnClick f

          ]
        []


let sameField x y =
    match x,y with
    | Playing px, Playing py -> px.Field = py.Field
    | Starting px, Starting py -> px = py 
    | _ -> false

let sameFence x y = 
    match x,y with
    | Playing px, Playing py -> px.Fence = py.Fence
    | Starting _, Starting _ -> true
    | _ -> false

let playerField  p =
        match p with
        | Playing p ->
            div [ ClassName (colorName p.Color)]
                [ let (Field parcels) = p.Field
                  for p in parcels do
                        parcel p [] ]
        | Starting { Parcel = p; Color = color } ->
            div [ ClassName (colorName color) ]
                [ parcel p [] ] 
        | Ko _ -> 
            null

let playerFences  p =
    match p with
    | Playing p ->
        div [ ClassName (colorName p.Color) ]
            [ let (Fence paths) = p.Fence
              for p,_ in paths do
                singleFence p ]
    | Starting _ 
    | Ko _ ->
        null

let playerTractor selected p =
    match p with
    | Playing p ->
        div [ ClassName (colorName p.Color) ]
            [ player selected p.Tractor ]
    | Starting { Parcel = Parcel p; Color = color } ->
        div [ ClassName (colorName color) ]
            [ Pix.ofTile p
              |> Pix.rotate
              |> Pix.translate (3.3,3.3)
              |> drawplayer selected  ]
    | Ko _ -> null



let moveView dispatch move =
    match move with
    | Move.Move (dir,c) -> crossroad c (fun _ -> dispatch (Move (dir, c)))
    | Move.ImpossibleMove (_,c,e) -> blockedCrossroad   c e
    | Move.SelectCrossroad(c) -> crossroad c (fun _ -> dispatch (SelectFirstCrossroad c))

       
let cardName =
    function
    | Nitro One -> "card nitro-1"
    | Nitro Two -> "card nitro-2"
    | Rut -> "card rut"
    | HayBale One -> "card hay-bale-1"
    | HayBale Two -> "card hay-bale-2"
    | Dynamite -> "card dynamite"
    | HighVoltage -> "card high-voltage"
    | Watchdog -> "card watchdog"
    | Helicopter -> "card helicopter"
    | Bribe -> "card bribe"

let handView dispatch board cardAction hand =
    let player = Board.currentPlayer board
    let otherPlayers = Board.currentOtherPlayers board
    let cancel = a [ ClassName "cancel"; Href "#"; OnClick (fun _ -> dispatch CancelCard)] [ str "Cancel" ]
    let action title texts buttons =
        div [ClassName "action" ]
            [ h2 [] [ str title ]
              for t in texts do
                p [] [ t ]
              div [ ClassName "buttons" ] [ yield! buttons; yield cancel ] ]

            
        
        


    match hand with 
    | Public cards -> 
       div [ ClassName "cards" ]
           [ for i,c in List.indexed cards do
               div [ClassName "card-container" ]
                   [
                        div [ ClassName ("card " + cardName c)
                              match cardAction with
                              | Some { Index = index } when index = i -> 
                                    OnClick (fun _ -> dispatch CancelCard)
                              | _ -> OnClick (fun _ -> dispatch (SelectCard(c,i))) ] []

                        match cardAction with
                        | Some { Index = index; Card = Nitro power} when index = i ->
                            action ("Nitro +" + match power with One -> "1" | Two -> "2") 
                                [ str (sprintf "Gives you %s extra moves during this turn." (match power with One -> "one" | Two -> "two"))
                                  Standard.i [] [str "(Reminder: max. 5 moves per turn)" ] ]
                                 [ button [ OnClick (fun _ -> dispatch (PlayCard (PlayNitro power))) ] [ str "Play" ] ]
                        | Some { Index = index; Card = Rut } when index = i ->
                            action "Rut"
                                [ str "Choose an opponent; he/she will have two fewer moves during his next turn" ]
                                [ for playerId, player in otherPlayers do
                                      div [ OnClick (fun _ -> dispatch (PlayCard (PlayRut playerId)))
                                            ClassName (colorName (Player.color player)) ] [
                                                div [ ClassName "player"] [] ] ]
                        | Some { Index = index; Card = HighVoltage } when index = i ->
                            action "High Voltage"
                                [ str "Protects the entire length of the fence, even the starting point until your next turn. Other tractors cannot go through or cut your fence." ]
                                [ button [ OnClick (fun _ -> dispatch (PlayCard (PlayHighVoltage))) ] [ str "Play" ] ]
                        | Some { Index = index; Card = Watchdog } when index = i ->
                            action "Watchdog"
                                [ str "Protects your plots and barns from being annexed until next turn. Annexations by opponents leave your plots and barns in place." ]
                                [ button [ OnClick (fun _ -> dispatch (PlayCard (PlayWatchdog))) ] [ str "Play" ] ] 
                        | Some { Index = index; Card = Helicopter } when index = i ->
                            action "Helicopter"
                                [ str "Moves your tractor to any point in your field. The point of arrival must be in the field or at the edge. Once moved, you cannot cut any more fences until the end of the turn: crop protection agents + electicity... I could explode!"
                                  if Fence.isEmpty (Player.fence player) then
                                        str "Select a destination in your field"
                                  else
                                        str "Cannot be played with a fence" ]
                                []
                        | Some { Index = index; Card = HayBale One } when index = i ->
                            action "1 Hay Bale"
                                [ str "Hay bales block the path for all players until blasted out with dynamite. You cannot place a Hay Bale on a fence in progress or on the edge of the board. It is forbiddent to lock in an opponent." 
                                  str "Select a free path for the hay bale" ]
                                []
                        | Some { Index = index; Card = HayBale Two; Ext = NoExt } when index = i ->
                            action "2 Hay Bales"
                                [ str "Hay bales block the path for all players until blasted out with dynamite. You cannot place a Hay Bale on a fence in progress or on the edge of the board. It is forbiddent to lock in an opponent."
                                  str "Select a  free paths for the first hay bale"]
                                []
                        | Some { Index = index; Card = HayBale Two; Ext = FirstHayBale _ } when index = i ->
                             action "2 Hay Bales"
                                [ str "Hay bales block the path for all players until blasted out with dynamite. You cannot place a Hay Bale on a fence in progress or on the edge of the board. It is forbiddent to lock in an opponent."
                                  str "Select a free paths for the second hay bales" ] 
                                []
                        | Some { Index = index; Card = Dynamite } when index = i ->
                             action "2 Hay Bales"
                                [ str "Remove 1 Hay Bale of your choice" 
                                  str "Select a hay bale to blow up" ]
                                []
                        | Some { Index = index; Card =  Bribe } when index = i ->
                             action "Bribe"
                                [ str "It wasn't clear on the plan... slipping a small bill should do the trick. The choose a plot of an opponent's field that has a common edge with yours... now it belongs to you! Careful, it needs to be discreet. You cannot take a plot of land from which a fence starts, it would cut it off, hence a bit conspicuous... You cannot take a barn either, hard to hide... You cannot place your last plot using this bonus, it would be a bit much!"
                                  match Board.bribeParcels board with
                                  | Ok _ -> str "Select a parcel on the border of your field to take over"
                                  | Error Board.InstantVictory -> str "You cannot bribe to take the last parcel ! That would be too visible !"
                                  | Error Board.NoParcelsToBribe -> str "There is no parcel to bribe."
                                ]
                                []
                        | _ -> ()
                    ]
           ]
    | Private cards ->
        div [ ClassName "cards"]
            [ for c in 1..cards do
               div [ClassName "card-container" ]
                   [ div [ ClassName (sprintf "card back z%d" c) ] [] ]
            ]

let barnsView barns =
    div [] 
        [ for parcel in Field.parcels barns.Free do
            barn parcel false
          for parcel in Field.parcels barns.Occupied do
            barn parcel true ]

let hayBalesView board =
    div []
        [ for p in board.HayBales do
            haybale p
        ]

let boardView board =
   [ for _,p in Map.toSeq board.Players do
         lazyViewWith sameField playerField p

     lazyView barnsView board.Barns

     for _,p in Map.toSeq board.Players do
         lazyViewWith sameFence playerFences  p

     for playerid,p in Map.toSeq board.Players do
         playerTractor (Table.isCurrent playerid board.Table)  p
         
     hayBalesView board ]

let goalView board =
    match board.Goal with
    | Common c ->
        div []
            [ str (sprintf "%d parcels left" (c - Board.totalSize board)) ]
    | Individual c ->
        div []
            [ for playerid, player in Map.toSeq board.Players do
                p [] [ str (sprintf "%s: %d parcels left" board.Table.Names.[playerid] (c - Player.fieldTotalSize player)) ]
            ]

let endTurnView dispatch playerId board =
    if playerId = Some board.Table.Player then
        match  board.Players.[board.Table.Player] with
        | Playing player when 
             not (Moves.canMove player.Moves) && Hand.canPlay player.Hand ->
               [ crossroad player.Tractor (fun _ -> dispatch EndTurn) ]
                
        | _ -> []
    else
        []

let helicopterDestinations player board =
    Field.crossroads (Player.field player) 
    - (set [ for _,p in Map.toSeq board.Players do
                match p with
                | Playing p ->
                      p.Tractor
                      yield! Fence.fenceCrossroads p.Tractor p.Fence
                | _ -> ()
                      ])

let hayBaleDestinations board =
    Path.allInnerPaths 
        - Set.unionMany 
            [ for KeyValue(_,p) in board.Players do
                p  |> Player.fence |> Fence.fencePaths |> set ]
        - board.HayBales


let boardCardActionView dispatch board  cardAction =

    match cardAction with
    | Some { Card = Helicopter} ->
        let player = Board.currentPlayer board
        [ for c in helicopterDestinations player board do
            crossroad c (fun _ -> dispatch (PlayCard (PlayHelicopter c))) ]
    | Some { Card =  HayBale One } ->
        [ for p in hayBaleDestinations board do
            path p (fun _ -> dispatch (PlayCard (PlayHayBale [ p ]))) ]
    | Some {Card = HayBale Two; Ext = NoExt } ->
        [ for p in hayBaleDestinations board do
            path p (fun _ -> dispatch (SelectHayBale p) ) ]
    | Some {Card = HayBale Two; Ext = FirstHayBale fp } ->
        [ haybale fp
          for p in hayBaleDestinations board do
            path p (fun _ -> dispatch (PlayCard (PlayHayBale [fp; p])) ) ]
    | Some { Card =  Dynamite}  ->
        [ for p in board.HayBales do
            path p (fun _ -> dispatch (PlayCard (PlayDynamite p))) ]
    | Some { Card =  Bribe}  ->
        match Board.bribeParcels board with
        | Ok parcels ->
            [ for p in Field.parcels parcels do
                tile p (fun _ -> dispatch (PlayCard (PlayBribe p))) ]
        | Error _ -> []
    | _ ->
        []

let flash active =
    div [ classBaseList "flash" [ "inactive", not active] ]
        []

let bars = i [ ClassName "fas fa-bars"] [
 svg [ AriaHidden true; Role "img"; ViewBox "0 0 448 512" ]
    [ Fable.React.Standard.path [ SVGAttr.Fill "currentColor"; D "M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z" ] []]
    ]

let playersDashboard model dispatch =
    match model.Board with
    | InitialState -> null
    | Board board
    | Won(_,board) ->

        div [ classBaseList "dashboard" [ "closed",not model.DashboardOpen  ] ]
            [  
                span [ OnClick (fun _ -> dispatch SwitchDashboard)]
                    [ bars ]

                let currentPlayer = board.Table.Player
                for playerid in board.Table.AllPlayers do
                    let isActive = currentPlayer = playerid
                    let player = board.Players.[playerid]
                    div[ classBaseList "player-dashboard" [ "local", Some playerid = model.PlayerId ]] [

                        div []
                          [ div [ ClassName (colorName (Player.color player))                             ]
                                [ div [ classBaseList "player" [ "selected", isActive ; "ko", Player.isKo player ] ] []]
                            
                           
                            div [Style [ MarginLeft "3em" ]] [ str board.Table.Names.[playerid] ] ]

                            
                        match player, board.Goal with
                        | Ko _, _ -> ()
                        | _, Individual goal ->
                            div [ ClassName "individual-goal" ]
                                [ div [ ClassName ("stack " + colorName (Player.color player))]
                                      [ div [ ClassName ("tile")] []  ]
                                  div [ClassName "tile-count"]
                                      [ str (sprintf "x %d" (goal - Player.fieldTotalSize player)) ] 
                                ]
                        | _ -> ()
                           


                        div [ ClassName "moves" ] 
                            [ if isActive then
                                match player with
                                | Starting _->
                                    for _ in 1 .. 3 do
                                        flash true 
                                | Playing p ->
                                    for i in 1 .. p.Moves.Capacity do
                                        flash  (i <= p.Moves.Done)
                                | Ko _ -> ()
                            ]
                        if model.DashboardOpen then
                            handView dispatch board model.CardAction (Player.hand player)

                        //goalView board
                    ]
                match board.Goal with
                | Common goal ->
                    div [ ClassName "common-goal" ]
                        [ let colors = [ for KeyValue(_,p) in board.Players -> Player.color p]
                          for c in colors do
                            div [ ClassName ("stack " + colorName c)]
                                [ div [ ClassName ("tile")] [] ]
                          div [ClassName "tile-count"]
                            [ str (sprintf "x %d" (goal - Board.totalSize board)) ]
                        ]
                | _ -> ()

 ]



let view (model : Model) (dispatch : Msg -> unit) =
    match model.Board with
    | InitialState -> 
        div [ClassName "board" ] []
    | Board board ->
        div [] 
            [ playersDashboard model dispatch
              div [ ClassName "board" ]
                [ yield! boardView board

                  for m in model.Moves do
                    moveView dispatch m

                  yield! endTurnView dispatch model.PlayerId board


                  yield! boardCardActionView dispatch board model.CardAction ] ]
    | Won(winner, board) ->
        div []
            [ div [ ClassName "board" ]
                  [ yield! boardView board
        

                    div [ Style [ Position PositionOptions.Absolute
                                  Top "10em"
                                  Left "10em"
                                  BackgroundColor "white"
                                  ] ]
                        [ p [] [ str "And the winner is :"]
                          p [] [ str board.Table.Names.[winner] ] ] ] ]

        


    //div [ Style [ Position PositionOptions.Fixed
    //              Top 0
    //              Left 0
    //              BackgroundColor "white"
    //              ]
    //]  [   p [] [str (sprintf "%d %s" model.Version model.Message)]
    //       p [] [str model.Error ] ]

    //for q in -4..4 do
    // for r in -4..4 do
    //    let px,py = Pix.ofTile (Axe(q,r))
    //    let x,y,z = Axe.cube (Axe(q,r))
    //    div [ Style [ Position PositionOptions.Absolute
    //                  Left (sprintf "%fvw" (px+2.5))
    //                  Top (sprintf "%fvw" (py+2.5))
    //                  BackgroundColor "white"
    //                   ]] 
    //        [ //str (sprintf "%d,%d,%d" x y z)
    //          str (sprintf "%d, %d" q r) ]



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

        


