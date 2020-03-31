namespace Shared

type Counter = { Value : int }


type Color =
    | Blue
    | Yellow
    | Purple
    | Red

[<Struct>]
type Axe = Axe of int * int
    with
    static member (+) (Axe(q1,r1), Axe(q2,r2)) =
        Axe(q1+q2, r1+r2)
    static member ( * ) (Axe(q,r), a) =
        Axe(q*a, r*a)
    static member ( * ) (a,Axe(q,r)) =
        Axe(q*a, r*a)

    member this.Q = match this with Axe(q,_) -> q 
    member this.R = match this with Axe(_,r) -> r 

type CrossroadSide = CLeft | CRight

[<Struct>]
type Crossroad = Crossroad of Axe * CrossroadSide

type BorderSide = BNW | BN | BNE

[<Struct>]
type Path = Path of Axe * BorderSide

type Direction = Up | Down | Horizontal


[<Struct>]
type Parcel = Parcel of Axe
    with
    static member (+) (Parcel p, v: Axe) =
        Parcel (p+v)


[<Struct>]
type Field = Field of Parcel Set

[<Struct>]
type Fence = Fence of (Path*Direction) list

module Direction =
    let rev =
        function
        | Up -> Down
        | Down -> Up
        | Horizontal -> Horizontal

type Player =
    | Starting of Parcel
    | Playing of Playing

and Playing =
    { Tractor: Crossroad
      Fence: Fence
      Field: Field}

type Board = Map<Color, Player>

type PlayerState =
    | SStarting of Parcel
    | SPlaying of PlayingState
and PlayingState =
    { STractor: Crossroad
      SFence: Fence
      SField: Parcel list }

type BoardState = (Color*PlayerState) list
    

module Axe =
    let N = Axe(0,-1)
    let S = Axe(0,+1)

    let NW = Axe(-1,0)
    let NE = Axe(+1,-1) 

    let SW = Axe(-1,+1)
    let SE = Axe(+1,0)

    let W2 = NW + SW
    let E2 = NE + SE

    let center = Axe(0,0)

    

    let cube (Axe(q,r)) =
        q,r,-q-r

module Crossroad =
    let neighbor dir (Crossroad(tile, side)) =
        match side, dir with
        | CLeft, Up -> tile + Axe.NW, CRight
        | CLeft, Down -> tile + Axe.SW, CRight
        | CLeft, Horizontal -> tile + Axe.W2, CRight
        | CRight, Up -> tile + Axe.NE, CLeft
        | CRight, Down -> tile + Axe.SE, CLeft
        | CRight, Horizontal -> tile + Axe.E2, CLeft
        |> Crossroad

    let isInField (Field parcels) (Crossroad(tile, side)) =
        let p = Parcel tile
        match side with
        | CLeft ->
            Set.contains p parcels
            || Set.contains (p+Axe.NW) parcels
            || Set.contains (p+Axe.SW) parcels
        | CRight ->
            Set.contains p parcels
            || Set.contains (p+Axe.NE) parcels
            || Set.contains (p+Axe.SE) parcels

    let isOnBoard (Crossroad(tile,side)) =
        let x,y,z = Axe.cube tile
        match side with
        | CLeft ->
            x > -4 && x <= 4 && y >= -4 && y < 4 && z >= -4 && z < 4
        | CRight ->
            x >= -4 && x < 4 && y > -4 && y <= 4 && z > -4 && z <= 4
            
            

module Parcel =
    let center = Parcel Axe.center

module Path =
    let neighbor dir (Crossroad(tile, side)) =
        match side, dir with
        | CLeft, Up -> Path (tile, BNW)
        | CLeft, Down -> Path (tile + Axe.SW, BNE)
        | CLeft, Horizontal -> Path (tile + Axe.SW , BN)
        | CRight, Up -> Path (tile, BNE)
        | CRight, Down -> Path (tile + Axe.SE, BNW)
        | CRight, Horizontal -> Path (tile + Axe.SE, BN)

    let ofMoves moves start =
        List.mapFold (fun pos move -> neighbor move pos, Crossroad.neighbor move pos) start moves

module Fence =
    let empty = Fence []

    let findLoop dir pos (Fence paths) =
        let nextPos = Crossroad.neighbor dir pos 
        let rec iter pos loop paths =
            match paths with
            | [] -> empty
            | (path,dir) :: tail ->
                let nextEnd = Crossroad.neighbor (Direction.rev dir) pos
                if nextEnd = nextPos then
                    Fence ((path, dir) :: loop)
                else
                    iter nextEnd ((path, dir ) :: loop) tail
        iter pos [] paths 

    let add path (Fence paths) =
        Fence (path :: paths)

    let tail (Fence paths) =
        Fence (List.tail paths)

    let protection pos (Fence paths) =
        let rec loop pos paths =
            seq {
                match paths with
                | [] -> ()
                | (_,dir) :: tail ->
                    let next = Crossroad.neighbor (Direction.rev dir) pos
                    yield next
                    yield! loop next tail
            }

        loop pos paths |> Seq.truncate 2
        

    let length (Fence paths) = List.length paths

    let remove toRemove (Fence paths) = Fence (List.skip (length toRemove) paths)

[<AutoOpen>]
module FenceOps =
    let (|Rwd|_|) nextPath (Fence paths) =
        match paths with
        | (last,_) :: _ when last = nextPath ->
            Some()
        | _ -> None

module Field =
    let empty = Field Set.empty
    let create parcel = Field (set [parcel])

type MoveBlocker =
    | Tractor
    | Protection


module Player =
    type Command =
        | Start of Start
        | SelectFirstCrossroad of SelectFirstCrossroad
        | Move of Move
    and Start =
        { Parcel: Parcel }
    and SelectFirstCrossroad =
        { Crossroad: Crossroad }
    and Move =
        { Direction: Direction }

    type Event =
        | Started of Started
        | FirstCrossroadSelected of FirstCrossroadSelected
        | FenceDrawn of FenceDrawn
        | FenceRemoved of FenceRemoved
        | FenceLooped of FenceLooped
    and Started =
        { Parcel : Parcel }
    and FirstCrossroadSelected =
        { Crossroad : Crossroad }
    and FenceDrawn =
        { Move: Direction
          Path: Path
          Crossroad: Crossroad}
    and FenceRemoved =
        { Move : Direction
          Path: Path
          Crossroad: Crossroad}
    and FenceLooped =
        { Move: Direction
          Loop: Fence
          Crossroad: Crossroad }


    let decide command player =
        match player, command with
        | InitialState, Start cmd ->
            Started { Parcel = cmd.Parcel }
        | Starting _, SelectFirstCrossroad cmd ->
            FirstCrossroadSelected { Crossroad = cmd.Crossroad }
        | Playing player, Move cmd ->
            let dir = cmd.Direction
            let nextPos = Crossroad.neighbor dir player.Tractor
            let nextPath = Path.neighbor dir player.Tractor
            match player.Fence with
            | Rwd nextPath ->
                FenceRemoved { Move = dir; Path = nextPath; Crossroad = nextPos } 
            | _ ->
                match Fence.findLoop dir player.Tractor player.Fence with
                | Fence [] -> FenceDrawn { Move = dir; Path = nextPath; Crossroad = nextPos } 
                | loop -> FenceLooped { Move = dir; Loop = loop; Crossroad = nextPos }
        | _ -> failwith "Invalid operation"      

    let evolve player event =
        match player, event with
        | Starting p, FirstCrossroadSelected e ->
            Playing { Tractor = e.Crossroad
                      Fence = Fence.empty
                      Field = Field.create p}
        | Playing player, FenceDrawn e -> Playing { player with Tractor = e.Crossroad; Fence = Fence.add (e.Path, e.Move) player.Fence }
        | Playing player, FenceRemoved e -> Playing { player with Tractor = e.Crossroad; Fence = Fence.tail player.Fence }
        | Playing player, FenceLooped e -> Playing { player with Tractor = e.Crossroad; Fence = Fence.remove e.Loop player.Fence }
        | _ -> player 


    let exec cmd state =
        state
        |> decide cmd
        |> evolve state

    let move dir fence =
        fence
        |> exec (Move {Direction = dir })

    let start parcel pos =
        Starting parcel
        |> exec (SelectFirstCrossroad { Crossroad = pos})


    let possibleMove player dir =
        let pos = Crossroad.neighbor dir player.Tractor
        if Crossroad.isOnBoard pos then
            [ dir, Ok pos]
        else
            []

    let possibleMoves player =
        match player with
        | Playing player ->
            [ Up;Down;Horizontal]
            |> List.collect (possibleMove player)

        | _ -> []

    let bindMove f cr =
            match cr with
            | Ok c -> f c
            | Error e -> Error e

    let (>>=) c f = bindMove f c

    let checkTractor player c  =
        if c = player.Tractor then
            Error (c, MoveBlocker.Tractor)
        else
            Ok c

    let checkProtection player c =
        let isOnProtection =
            player.Fence
            |> Fence.protection player.Tractor
            |> Seq.exists (fun p -> p = c)
        if isOnProtection then
            Error(c, Protection)
        else
            Ok c
        

    let checkMove player c =
        match player with
        | Playing player ->
            checkTractor player c 
            >>= checkProtection player
           
        | _ -> Ok c


    let toState (p: Player) =
        match p with
        | Starting p -> SStarting p
        | Playing p ->
            SPlaying
                { STractor = p.Tractor
                  SFence = p.Fence
                  SField =
                    let (Field f) = p.Field
                    f |> Set.toList }

    let ofState (p: PlayerState) =
        match p with
        | SStarting p -> Starting p
        | SPlaying p ->
            Playing {
                Tractor = p.STractor
                Fence = p.SFence
                Field = Field (set p.SField)
            }

module Board =
    type Command =
        | Play of Color * Player.Command

    type Event =
        | Played of Color * Player.Event

    let decide cmd (state: Board) =
        match cmd with
        | Play ( color, cmd) ->
            let player = state.[color]
            let event = Player.decide cmd player
            Played(color, event)

    let evolve (state: Board) event =
        match event with
        | Played (color,e) ->
            let player = Player.evolve state.[color] e
            Map.add color player state

    let toState (board: Board) =
        board |> Map.toList |> List.map (fun (c,p) -> c, Player.toState p)

    let ofState (board: BoardState) =
        board |> List.map (fun (c,p) -> c, Player.ofState p) |> Map.ofList
        


    let otherPlayers color (board: Board) =
        board
        |> Map.toSeq
        |> Seq.filter (fun (c,_) -> c <> color)
        |> Seq.map snd
    

    let possibleMoves color (board: Board) =
        match color with
        | Some color ->
            match Map.tryFind color board with
            | Some p ->
                [ for dir,m in Player.possibleMoves p do
                  dir, Seq.fold (fun c p -> Player.bindMove (Player.checkMove p) c) m (otherPlayers color board)
                ]
            | None -> []
        | None -> []

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
        | BN -> tx,ty-size*0.73
        | BNW -> tx - size*0.75,ty-size*0.35
        | BNE -> tx + size*0.73,ty-size*0.22



    let teta = -0.063
    let cost = cos teta 
    let sint = sin teta
    let rotate (x,y) =
        let cx = x-offsetX
        let cy = y-offsetY
        offsetX + cx * cost + cy * sint, offsetY - cx * sint + cy * cost 

/// A type that specifies the messages sent to the server from the client on Elmish.Bridge
/// to learn more, read about at https://github.com/Nhowka/Elmish.Bridge#shared
type ServerMsg =
    | SyncState 
    | SetColor of Color
    | Command of Player.Command

/// A type that specifies the messages sent to the client from the server on Elmish.Bridge
type ClientMsg =
    | Event of Board.Event
    | Message of string
    | Sync of BoardState
    | SyncColor of Color


