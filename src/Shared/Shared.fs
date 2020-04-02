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
    with
    static member (+) (Field x,Field y) =
        Field (x + y)
    static member (-) (Field x,Field y) =
        Field (x - y)


[<Struct>]
type Fence = Fence of (Path*Direction) list

module Direction =
    let rev =
        function
        | Up -> Down
        | Down -> Up
        | Horizontal -> Horizontal

type Power =
    | PowerUp
    | PowerDown


type Player =
    | Starting of Parcel
    | Playing of Playing
and Playing =
    { Tractor: Crossroad
      Fence: Fence
      Field: Field
      Power: Power }

type Board = Map<Color, Player>

type PlayerState =
    | SStarting of Parcel
    | SPlaying of PlayingState
and PlayingState =
    { STractor: Crossroad
      SFence: Fence
      SField: Parcel list
      SPower: Power}

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

    let tile (Crossroad(tile,_)) = tile
    let side (Crossroad(_,side)) = side

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

    let tile (Path(tile,_)) = tile

    let neighborTiles (Path(tile, side)) =
        match side with
        | BNW -> tile + Axe.NW
        | BNE -> tile + Axe.NE
        | BN -> tile + Axe.N

    let ofMoves moves start =
        List.mapFold (fun pos move -> (neighbor move pos, move), Crossroad.neighbor move pos) start moves

type LMax =  
    { Max: Axe
      Left: Direction list
      Right: Direction list }
    
type OrientedPath =
    | DNE
    | DNW
    | DW
    | DSW
    | DSE
    | DE


module Fence =
    let empty = Fence []

    let isEmpty (Fence paths) = List.isEmpty paths 


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

    let fenceCrossroads tractor (Fence paths) =
        let rec loop pos paths =
            seq {
                match paths with
                | [] -> ()
                | (_,dir) :: tail ->
                    let next = Crossroad.neighbor (Direction.rev dir) pos
                    yield next
                    yield! loop next tail
            }
        loop tractor paths 

    let protection tractor fence =
        fenceCrossroads tractor fence
        |> Seq.truncate 2

    let start tractor (Fence paths) =
        let rec loop pos paths =
                match paths with
                | [] -> pos
                | (_,dir) :: tail ->
                    let next = Crossroad.neighbor (Direction.rev dir) pos
                    loop next tail
        loop tractor paths 


        

    let length (Fence paths) = List.length paths

    let remove toRemove (Fence paths) = Fence (List.skip (length toRemove) paths)

    let toOriented tractor (Fence paths) =
        let o,end' =
            List.mapFold (fun pos (_,dir) ->
                let o =
                    match dir, Crossroad.side pos with
                    | Horizontal,CRight -> DW
                    | Horizontal,CLeft -> DE
                    | Up, CRight -> DNE
                    | Up,CLeft -> DNW
                    | Down, CRight -> DSE
                    | Down, CLeft -> DSW
                o, Crossroad.neighbor dir pos
                )
                tractor paths
        List.rev o, end'

[<AutoOpen>]
module FenceOps =
    let (|Rwd|_|) nextPath (Fence paths) =
        match paths with
        | (last,_) :: _ when last = nextPath ->
            Some()
        | _ -> None


module Field =
    let empty = Field Set.empty
    let isEmpty (Field x) = Set.isEmpty x

    let create parcel = Field (set [parcel])

    let ofParcels parcels  = Field(set parcels)
    let parcels (Field parcels) = Set.toList parcels 
    
    let contains parcel (Field parcels) =
        Set.contains (Parcel parcel) parcels

    let interesect (Field x) (Field y) =
        Field(Set.intersect x y)
   
    let fill (paths: (Path * Direction) list) =
        let sortedPaths = 
            paths
            |> List.choose (function (Path(t,_),Horizontal) -> Some t | _ -> None)
            |> List.sortBy (fun t -> t.Q, t.R)
            |> List.groupBy(fun tile -> tile.Q)


        [ for q, ps in sortedPaths  do
            for l in List.chunkBySize 2 ps do 
                match l with
                | [s;e] -> yield! [ for r in s.R .. e.R-1 -> Parcel (Axe(q,r)) ]
                | _ ->()
        ]
        |> set |> Field

 


    let counterclock field (Crossroad(tile,side)) =
        match side with
        | CRight ->
            if contains tile field then
                Up, DNW
            elif contains (tile + Axe.NE) field then
                Horizontal , DE
            else
                Down,DSW
        | CLeft ->
            if contains tile field then
                Down,DSE
            elif contains (tile + Axe.NW) field then
                Up,DNE
            else
                Horizontal, DW


           
    let borderBetween start end' field =
        let rec loop orientedPath pos path =
            if pos = start then
                []
            elif pos = end' then
                List.rev path
            else
                match orientedPath with
                | DE ->
                    let tile = Crossroad.tile pos
                    if contains tile field then
                        
                        let next = Crossroad.neighbor Down pos
                        loop DSE next ((Path.neighbor Down pos, Down) :: path)
                    else
                        let next = Crossroad.neighbor Up pos
                        loop DNE next ((Path.neighbor Up pos, Up) :: path)
                | DNE ->
                    let tile = Crossroad.tile pos + Axe.NE
                    if contains tile field then
                        let next = Crossroad.neighbor Horizontal pos
                        loop DE next ((Path.neighbor Horizontal pos, Horizontal) :: path)
                    else
                        let next = Crossroad.neighbor Up pos
                        loop DNW next ((Path.neighbor Up pos, Up) :: path)
                | DNW ->
                    let tile = Crossroad.tile pos + Axe.NW
                    if contains tile field then
                        let next = Crossroad.neighbor Up pos
                        loop DNE next ((Path.neighbor Up pos, Up) :: path)
                    else
                        let next = Crossroad.neighbor Horizontal pos
                        loop DW next ((Path.neighbor Horizontal pos, Horizontal) :: path)
                | DW ->
                    let tile = Crossroad.tile pos 
                    if contains tile field then
                        let next = Crossroad.neighbor Up pos
                        loop DNW next ((Path.neighbor Up pos, Up) :: path)
                    else
                        let next = Crossroad.neighbor Down pos
                        loop DSW next ((Path.neighbor Down pos, Down) :: path)
                | DSW ->
                    let tile = Crossroad.tile pos + Axe.SW 
                    if contains tile field then
                        let next = Crossroad.neighbor Horizontal pos
                        loop DW next ((Path.neighbor Horizontal pos, Horizontal) :: path)
                    else
                        let next = Crossroad.neighbor Down pos
                        loop DSE next ((Path.neighbor Down pos, Down) :: path)
                | DSE ->
                    let tile = Crossroad.tile pos + Axe.SE 
                    if contains tile field then
                        let next = Crossroad.neighbor Down pos
                        loop DSW next ((Path.neighbor Down pos, Down) :: path)
                    else
                        let next = Crossroad.neighbor Horizontal pos
                        loop DE next ((Path.neighbor Horizontal pos, Horizontal) :: path)

        let firstDir,orientedPath = counterclock field start
        let pos = Crossroad.neighbor firstDir start
        loop orientedPath pos [ Path.neighbor firstDir start, firstDir ]


    let isInSameField start end' field =
        borderBetween start end' field |> List.isEmpty |> not


    let pathInFieldOrBorder path field =
         contains (Path.tile path) field || contains (Path.neighborTiles path) field

        
        
        









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
        | FenceDrawn of Moved
        | FenceRemoved of Moved
        | FenceLooped of FenceLooped
        | MovedInField of Moved
        | MovedPowerless of Moved
        | Annexed of Annexed
        | CutFence of CutFence
        | PoweredUp
    and Started =
        { Parcel : Parcel }
    and FirstCrossroadSelected =
        { Crossroad : Crossroad }
    and Moved =
        { Move: Direction
          Path: Path
          Crossroad: Crossroad}
    and FenceLooped =
        { Move: Direction
          Loop: Fence
          Crossroad: Crossroad }
    and Annexed =
        {
           NewField: Parcel list
           LostFields: (Color * Parcel list) list
        }
    and CutFence = 
        { Color: Color }

    let isCut tractor player =
        match player with
        | Playing player ->
            Fence.fenceCrossroads player.Tractor player.Fence
            |> Seq.contains tractor
        | _ -> false

    let decideCut otherPlayers tractor =
        otherPlayers
        |> List.filter (snd >> isCut tractor)
        |> List.map (fun (color,_) -> CutFence { Color= color} )

        
    let annexation field fence tractor =
        let border = Field.borderBetween (Fence.start tractor fence) tractor field
        let fullBorder = 
            let (Fence paths) = fence
            paths @ border 
        Field.fill fullBorder - field
                    
                    
    let decide otherPlayers command player =
        match player, command with
        | _, Start cmd ->
            [ Started { Parcel = cmd.Parcel } ]
        | Starting _, SelectFirstCrossroad cmd ->
            [ FirstCrossroadSelected { Crossroad = cmd.Crossroad } ]
        | Playing player, Move cmd ->
            let dir = cmd.Direction
            let nextPos = Crossroad.neighbor dir player.Tractor
            let nextPath = Path.neighbor dir player.Tractor
            match player.Power with
            | PowerUp ->
                match player.Fence with
                | Rwd nextPath ->
                    [ FenceRemoved { Move = dir; Path = nextPath; Crossroad = nextPos }  ]
                | _ ->
                    match Fence.findLoop dir player.Tractor player.Fence with
                    | Fence [] -> 
                        let endInField = Crossroad.isInField player.Field nextPos

                        let pathInField = Field.pathInFieldOrBorder nextPath player.Field
                        let inFallow =
                            if endInField then
                                if not (Fence.isEmpty player.Fence) then
                                    let fenceStart = Fence.start player.Tractor player.Fence 
                                    not (Field.isInSameField fenceStart nextPos player.Field)
                                else
                                    not pathInField
                            else
                                false

                        [ 
                          if pathInField && not inFallow then
                              MovedInField { Move = dir; Path = nextPath; Crossroad = nextPos }  
                          else
                              FenceDrawn { Move = dir; Path = nextPath; Crossroad = nextPos }  
                          yield! decideCut otherPlayers nextPos
                          if endInField && not pathInField && not inFallow then
                            let nextFence = Fence.add (nextPath, dir) player.Fence
                            let annexed = annexation player.Field nextFence nextPos

                            let lostFields = 
                                [ for color,p in otherPlayers do
                                    match p with
                                    | Playing p ->
                                        let lost = Field.interesect annexed p.Field
                                        if not (Field.isEmpty lost) then
                                            color, Field.parcels lost
                                    |_ -> ()
                                ] 
                            Annexed {
                                NewField = Field.parcels annexed
                                LostFields = lostFields
                                    }

                            for color, p in otherPlayers do
                                match p with
                                | Playing p ->
                                    if not (Fence.isEmpty p.Fence) then
                                        let start = Fence.start p.Tractor p.Fence
                                        if Crossroad.isInField annexed start then
                                            CutFence { Color = color }
                                    else
                                        let remainingField = p.Field - annexed
                                        if Crossroad.isInField annexed p.Tractor && not (Crossroad.isInField remainingField p.Tractor) then
                                            CutFence { Color = color }
                                | _ -> ()

                        ]
                    | loop -> [  FenceLooped { Move = dir; Loop = loop; Crossroad = nextPos } ]
            | PowerDown ->
                [
                    // when power is down, you don't draw fences
                    MovedPowerless { Move = dir; Path = nextPath; Crossroad = nextPos }

                    if Crossroad.isInField player.Field nextPos then
                        // when back in field, power is up
                        PoweredUp
                        // an maybe you cut someone just there
                        yield! decideCut otherPlayers nextPos
                ]
                
        | _ -> failwith "Invalid operation"      


    let evolve player event =
        match player, event with
        | Starting p, FirstCrossroadSelected e ->
            Playing { Tractor = e.Crossroad
                      Fence = Fence.empty
                      Field = Field.create p
                      Power = PowerUp
                      }
        | Playing player, FenceDrawn e -> Playing { player with Tractor = e.Crossroad; Fence = Fence.add (e.Path, e.Move) player.Fence }
        | Playing player, FenceRemoved e -> Playing { player with Tractor = e.Crossroad; Fence = Fence.tail player.Fence }
        | Playing player, FenceLooped e -> Playing { player with Tractor = e.Crossroad; Fence = Fence.remove e.Loop player.Fence }
        | Playing player, MovedInField e -> Playing { player with Tractor = e.Crossroad; Fence = Fence.empty }
        | Playing player, MovedPowerless e -> Playing { player with Tractor = e.Crossroad }
        | Playing player, PoweredUp -> Playing { player with Power = PowerUp }
        | Playing player, Annexed e -> Playing { player with Fence = Fence.empty; Field = player.Field + Field.ofParcels e.NewField}
        | _ -> player 



    let exec otherPlayers cmd state =
        state
        |> decide otherPlayers cmd
        |> List.fold evolve state

    let move dir fence =
        fence
        |> exec [] (Move {Direction = dir })

    let start parcel pos =
        Starting parcel
        |> exec [] (SelectFirstCrossroad { Crossroad = pos})


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
                    f |> Set.toList
                  SPower = p.Power }

    let ofState (p: PlayerState) =
        match p with
        | SStarting p -> Starting p
        | SPlaying p ->
            Playing {
                Tractor = p.STractor
                Fence = p.SFence
                Field = Field (set p.SField)
                Power = p.SPower
            }

module Board =
    type Command =
        | Play of Color * Player.Command

    type Event =
        | Played of Color * Player.Event

        
    let otherPlayers color (board: Board) =
        board
        |> Map.toSeq
        |> Seq.filter (fun (c,_) -> c <> color)
        |> Seq.toList
        

    let decide cmd (state: Board) =
        match cmd with
        | Play ( color, cmd) ->
            let player = state.[color]
            let others = otherPlayers color state

            

            Player.decide others cmd player
            |> List.map (fun event -> Played(color, event))

    let evolve (state: Board) event =
        match event with
        | Played (_, Player.CutFence { Color = color }) ->
            match state.[color] with
            | Playing player -> 
                let cutPlayer =
                    Playing { player with
                                Fence = Fence.empty
                                Power = PowerDown }
                Map.add color cutPlayer state
            | _ -> state
        | Played (color, Player.Annexed e ) ->
            let annexedPlayer = Player.evolve state.[color] (Player.Annexed e)
            let newMap = Map.add color annexedPlayer state
            e.LostFields
            |> List.fold (fun map (color, parcels) ->
                match state.[color] with
                | Playing p ->
                    let newP = Playing { p with Field = p.Field - Field.ofParcels parcels }
                    Map.add color newP map
                | _ -> map
            
            ) newMap
        | Played (color,e) ->
            let player = Player.evolve state.[color] e
            Map.add color player state

    let toState (board: Board) =
        board |> Map.toList |> List.map (fun (c,p) -> c, Player.toState p)

    let ofState (board: BoardState) =
        board |> List.map (fun (c,p) -> c, Player.ofState p) |> Map.ofList
        


    let possibleMoves color (board: Board) =
        match color with
        | Some color ->
            match Map.tryFind color board with
            | Some p ->
                [ for dir,m in Player.possibleMoves p do
                  dir, Seq.fold (fun c p -> Player.bindMove (Player.checkMove p) c) m (List.map snd (otherPlayers color board))
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

/// A type that specifies the messages sent to the server from the client on Elmish.Bridge
/// to learn more, read about at https://github.com/Nhowka/Elmish.Bridge#shared
type ServerMsg =
    | SyncState 
    | SetColor of Color
    | Command of Player.Command

/// A type that specifies the messages sent to the client from the server on Elmish.Bridge
type ClientMsg =
    | Events of Board.Event list
    | Message of string
    | Sync of BoardState
    | SyncColor of Color


