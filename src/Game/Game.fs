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
    CardAction: (int* Card) option
    Moves: Move list 
    Message: string
    Error : string
    }

// The Msg type defines what events/actions can occur while the application is running
// the state of the application changes *only* in reaction to these events
type Msg =
    | Move of Direction * Crossroad
    | PlayCard of PlayCard
    | SelectFirstCrossroad of Crossroad
    | SelectCard of Card * int
    | CancelCard
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
            CardAction = Some (i,card) }, Cmd.none
    | CancelCard ->
        { currentModel with
            CardAction = None }, Cmd.none
    | PlayCard(card) ->
        Player.PlayCard card
        |> handleCommand { currentModel with CardAction = None}
    | EndTurn ->
        Player.EndTurn
        |> handleCommand { currentModel with CardAction = None}
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

let barn (Parcel pos) occupied = 
    let x,y = Pix.ofTile pos |> Pix.rotate
    div [ classBaseList "barn" [ "occupied", occupied ]
          Style [ Left (sprintf "%fvw" x)
                  Top (sprintf "%fvw" y)
                   ]] 
        []

let drawplayer (x,y) =
    div [ ClassName "player"
          Style [ Left (sprintf "%fvw" x)
                  Top (sprintf "%fvw" y)
                   ]] 
        []

let player pos =
    Pix.ofPlayer pos
    |> Pix.rotate
    |> drawplayer

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
let playerFences  p =
    match p with
    | Playing p ->
        div [ ClassName (colorName p.Color) ]
            [ let (Fence paths) = p.Fence
              for p,_ in paths do
                singleFence p ]
    | Starting _ ->
        null

let playerTractor p =
    match p with
    | Playing p ->
        div [ ClassName (colorName p.Color)]
            [ player p.Tractor ]
    | Starting { Parcel = Parcel p; Color = color } ->
        div [ ClassName (colorName color)]
            [ Pix.ofTile p
              |> Pix.rotate
              |> Pix.translate (3.3,3.3)
              |> drawplayer  ]



let moveView dispatch move =
    match move with
    | Move.Move (dir,c) -> crossroad c (fun _ -> dispatch (Move (dir, c)))
    | Move.ImpossibleMove (_,c,e) -> blockedCrossroad   c e
    | Move.SelectCrossroad(c) -> crossroad c (fun _ -> dispatch (SelectFirstCrossroad c))

       
let cardName =
    function
    | Nitro One -> "card nitro-1"
    | Nitro Two -> "card nitro-1"
    | Rut -> "card rut"
    | HayBale One -> "card hay-bale-1"
    | HayBale Two -> "card hay-bale-2"
    | Dynamite -> "card dynamite"
    | HighVoltage -> "card high-voltage"
    | Watchdog -> "card watchdog"
    | Helicopter -> "card helicopter"
    | Bribe -> "card bribe"

let handView dispatch player otherPlayers cardAction =
    function 
    | Public cards -> 
       div [ ClassName "cards" ]
           [ for i,c in List.indexed cards do
               div [ ClassName (cardName c)
                     match cardAction with
                     | Some(index, _) when index = i -> ()
                     | _ ->
                         OnClick (fun _ -> dispatch (SelectCard(c,i)))
               ] [
                match cardAction with
                | Some(index, Nitro power) when index = i ->
                    button [ OnClick (fun _ -> dispatch (PlayCard (PlayNitro power))) ] [ str "Play" ]
                | Some(index, Rut) when index = i ->
                    for playerId, player in otherPlayers do
                          div [ OnClick (fun _ -> dispatch (PlayCard (PlayRut playerId)))
                                ClassName (colorName (Player.color player)) ] [
                                    div [ ClassName "player"] []
                                ] 
                | Some(index, HighVoltage ) when index = i ->
                    button [ OnClick (fun _ -> dispatch (PlayCard (PlayHighVoltage))) ] [ str "Play" ]
                | Some(index, Watchdog ) when index = i ->
                    button [ OnClick (fun _ -> dispatch (PlayCard (PlayWatchdog))) ] [ str "Play" ]
                | Some(index, Helicopter) when index = i ->
                    if Fence.isEmpty (Player.fence player) then
                        div [] [ str "Select a destination in your field" ]
                    else
                        div [] [ str "Cannot be played with a fence" ]


                | _ -> ()




               
               ]
           ]
    | Private cards ->
        div [ ClassName "cards"]
            [ for c in 1..cards do
                div [ ClassName (sprintf "back z%d" c) ] []
            ]

let barnsView barns =
    div [] 
        [ for parcel in Field.parcels barns.Free do
            barn parcel false
          for parcel in Field.parcels barns.Occupied do
            barn parcel true ]

let boardView board =
   [ for _,p in Map.toSeq board.Players do
         lazyViewWith sameField playerField p

     lazyView barnsView board.Barns

     for _,p in Map.toSeq board.Players do
         lazyViewWith sameFence playerFences  p

     for _,p in Map.toSeq board.Players do
         playerTractor  p ]

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


let boardCardActionView dispatch player board  cardAction =
    match cardAction with
    | Some (_, Helicopter) ->
        [ for c in helicopterDestinations player board do
            crossroad c (fun _ -> dispatch (PlayCard (PlayHelicopter c))) ]
    | _ ->
        []

let view (model : Model) (dispatch : Msg -> unit) =
    div [ ClassName "board" ]
        [
            match model.Board with
            | InitialState -> ()
            | Board board ->
                yield! boardView board

                for m in model.Moves do
                    moveView dispatch m

                yield! endTurnView dispatch model.PlayerId board

                let player =  board.Players.[board.Table.Player]

                yield! boardCardActionView dispatch player board model.CardAction

                div [ Style [Position PositionOptions.Fixed
                             Left 0
                             Top "0px"
                             Padding "10px"
                             BackgroundColor "white"
                            ] ]
                    [  
                        div [ ClassName (colorName (Player.color player))                             ]
                            [ div [ClassName "player" ] []]
                        
                       
                        div [Style [ MarginLeft "3em" ]] [ str board.Table.Names.[board.Table.Player] ]

                        let otherPlayers =
                            Board.otherPlayers board.Table.Player board
                        
                        handView dispatch player otherPlayers  model.CardAction (Player.hand player)

                        goalView board
                    ]
            | Won(winner, board) ->
                yield! boardView board
                

                div [ Style [ Position PositionOptions.Absolute
                              Top "10em"
                              Left "10em"
                              BackgroundColor "white"
                              ] ]
                    [ p [] [ str "And the winner is !"]
                      p [] [ str board.Table.Names.[winner] ] ]

                


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
            //        [ str (sprintf "%d,%d,%d" x y z) ]
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

        


