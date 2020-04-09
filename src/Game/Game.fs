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
    Moves: Move list 
    Message: string
    Error : string
    }

// The Msg type defines what events/actions can occur while the application is running
// the state of the application changes *only* in reaction to these events
type Msg =
    | Move of Direction * Crossroad
    | SelectFirstCrossroad of Crossroad
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
    

let parcel (Parcel pos) = 
    let x,y = Pix.ofTile pos |> Pix.rotate
    div [ ClassName "tile"
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
                        parcel p ]
        | Starting { Parcel = p; Color = color } ->
            div [ ClassName (colorName color) ]
                [ parcel p ] 
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
    
    
let view (model : Model) (dispatch : Msg -> unit) =
    div [ ClassName "board" ]
        [
            match model.Board with
            | Board board ->
                for _,p in Map.toSeq board.Players do
                    lazyViewWith sameField playerField p

                for _,p in Map.toSeq board.Players do
                    lazyViewWith sameFence playerFences  p
                for _,p in Map.toSeq board.Players do
                    playerTractor  p

                for m in model.Moves do
                    moveView dispatch m



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

        


