namespace Shared


[<Struct>]
type Axe = Axe of q:int * r:int
    with
    static member (+) (Axe(q1,r1), Axe(q2,r2)) =
        Axe(q1+q2, r1+r2)
    static member ( * ) (a,Axe(q,r)) =
        Axe(q*a, r*a)

    member this.Q = match this with Axe(q,_) -> q 
    member this.R = match this with Axe(_,r) -> r 

module Axe =
    let N = Axe(0,-1)
    let S = Axe(0,+1)

    let NW = Axe(-1,0)
    let NE = Axe(+1,-1) 

    let SW = Axe(-1,+1)
    let SE = Axe(+1,0)

    let W2 = NW + SW
    let E2 = NE + SE

    let zero = Axe(0,0)
    let center = zero

    

    let cube (Axe(q,r)) =
        q,r,-q-r

type CrossroadSide = CLeft | CRight

[<Struct>]
type Crossroad = Crossroad of tile:Axe * side:CrossroadSide

type BorderSide = BNW | BN | BNE

[<Struct>]
type Path = Path of tile:Axe * border:BorderSide

type Direction = Up | Down | Horizontal

[<Struct>]
type Parcel = Parcel of tile:Axe
    with
    static member (+) (Parcel p, v: Axe) =
        Parcel (p+v)


[<Struct>]
type Field = Field of parcels:Parcel Set
    with
    static member (+) (Field x,Field y) =
        Field (x + y)
    static member (-) (Field x,Field y) =
        Field (x - y)


[<Struct>]
type Fence = Fence of paths:(Path*Direction) list

type Barns =
    { Free: Field
      Occupied: Field }

module Direction =
    let rev =
        function
        | Up -> Down
        | Down -> Up
        | Horizontal -> Horizontal

type Power =
    | PowerUp
    | PowerDown


type Card =
    | Nitro of power:CardPower
    | Rut
    | HayBale of power:CardPower
    | Dynamite
    | HighVoltage
    | Watchdog
    | Helicopter
    | Bribe
    | GameOver

and CardPower = One | Two

type PlayCard =
    | PlayNitro of power:CardPower
    | PlayRut of victim:string
    | PlayHayBale of path:Path list * moved: Path list
    | PlayDynamite of path:Path
    | PlayHighVoltage
    | PlayWatchdog
    | PlayHelicopter of destination:Crossroad
    | PlayBribe of parcel:Parcel
    | PlayGameOver

module Card =
    let ofPlayCard =
        function
        | PlayNitro power -> Nitro power
        | PlayRut _ -> Rut
        | PlayHayBale(hb,_) -> if List.length hb < 2 then HayBale One else HayBale Two
        | PlayDynamite _ -> Dynamite
        | PlayHighVoltage -> HighVoltage
        | PlayWatchdog -> Watchdog
        | PlayHelicopter _ -> Helicopter
        | PlayBribe _ -> Bribe
        | PlayGameOver -> GameOver

type Hand =
    | PrivateHand of cards:int
    | PublicHand of cards:Card list

module Hand =
    let empty = PrivateHand 0

    let isEmpty =
        function
        | PublicHand p -> List.isEmpty p
        | PrivateHand p -> p = 0

    let isPublic =
        function
        | PublicHand _ -> true
        | PrivateHand _ -> false

    let toPrivate =
        function
        | PublicHand p -> PrivateHand (List.length p)
        | priv -> priv

    let count =
        function
        | PublicHand p -> p.Length
        | PrivateHand c -> c


    let contains card =
        function
        | PublicHand p -> List.contains card p
        | PrivateHand _ -> false
       
    let remove card hand =
        match hand with
        | PublicHand p -> 
            match List.tryFindIndex (fun c -> c = card) p with
            | Some i -> 
                let left, right = List.splitAt i p
                PublicHand (left @ List.tail right)
            | None -> hand
        | PrivateHand p -> PrivateHand (p-1)

    let canPlay =
        function
        | PublicHand p -> 
            p |> List.isEmpty |> not
        | PrivateHand p -> p > 0

    let shouldDiscard =
        function
        | PublicHand p ->
            List.length p > 6
        | PrivateHand p -> p > 6


type CrazyPlayer =
    | Starting of Starting
    | Playing of Playing
    | Ko of Color
and Starting =
    { Color: Color
      Parcel: Parcel
      Hand: Hand
      Bonus: Bonus
      }
and Playing =
    { Color: Color
      Tractor: Crossroad
      Fence: Fence
      Field: Field
      Power: Power
      Moves: Moves
      Hand: Hand 
      Bonus: Bonus
      } 
and Moves =
    { Capacity: int
      Done: int
      Acceleration: bool }
and Bonus =
    { NitroOne: int
      NitroTwo: int
      Watched: bool
      HighVoltage: bool
      Rutted: int
      Heliported: int
      }



type GameTable =
    { Players: string[]
      AllPlayers: string[]
      Names: Map<string,string>
      Current: int }
    member this.Player = this.Players.[this.Current]

    member this.Next =
        { this with
            Current = (this.Current + 1) % Array.length this.Players }

module Table =
    let start players =
        let allplayers = [| for p,_ in players -> p |]
        { Players = allplayers
          AllPlayers = allplayers
          Current = 0
          Names = Map.ofList players }

    let eliminate player table =
        let index = Array.findIndex(fun p -> p = player) table.Players
        let newPlayers = Array.filter (fun p -> p <> player) table.Players
        { table with
            Players = newPlayers
            Current =
                if table.Current <= index then
                    table.Current % (Array.length newPlayers)
                else
                    table.Current - 1
            
            }

    let isCurrent playerid (table:GameTable) =
        table.Player = playerid

module Bonus =
    let empty =
        { NitroOne = 0
          NitroTwo = 0
          Watched = false
          HighVoltage = false
          Rutted = 0
          Heliported = 0
          }

    let startTurn bonus =
        [ if bonus.HighVoltage then
            HighVoltage
          if bonus.Watched then
             Watchdog

        ]

    let endTurn bonus =
        [ for _ in 1..bonus.NitroOne do
            Nitro One
          for _ in 1..bonus.NitroTwo do
            Nitro Two
          for _ in 1..bonus.Rutted do
            Rut
          for _ in 1..bonus.Heliported do
            Helicopter
        ]

    let moveCapacityChange bonus =
        bonus.Rutted * -2


    let discard card bonus =
        match card with
        | Nitro One ->
            { bonus with NitroOne = bonus.NitroOne - 1  }
        | Nitro Two ->
            { bonus with NitroTwo = bonus.NitroTwo - 1 }
        | Watchdog ->
            { bonus with Watched = false}
        | HighVoltage ->
            { bonus with HighVoltage = false }
        | Rut ->
            { bonus with Rutted = bonus.Rutted - 1}
        | Helicopter ->
            { bonus with Heliported = bonus.Heliported - 1 }
        | _ -> bonus

type PlayerPosition =
    { Player: string
      TractorPos: Crossroad
      FencePos: Fence
      FieldPos: Field }

type BoardPosition =
    { Positions: PlayerPosition Set }

type History =
    { PlayersHistory: Map<string, BoardPosition list> }


type Board = 
    | InitialState
    | Board of PlayingBoard
    | Won of string list * PlayingBoard
and PlayingBoard =
    { Players: Map<string, CrazyPlayer>
      Table: GameTable
      DrawPile: Hand
      DiscardPile: Card list
      Barns: Barns
      HayBales: Path Set
      Goal: Goal
      UseGameOver: bool
      History: History }

type UndoableBoard =
    { Board: Board
      UndoPoint: Board
      UndoType: UndoType
      ShouldShuffle: bool
      AtUndoPoint: bool
    }


type PlayerState =
    | SStarting of StartingState
    | SPlaying of PlayingState
    | SKo of Color
and StartingState =
    { SColor: Color
      SParcel: Parcel
      SHand: Hand
      SBonus: Bonus}
and PlayingState =
    { SColor: Color 
      STractor: Crossroad
      SFence: Fence
      SField: Parcel list
      SPower: Power
      SMoves: Moves 
      SHand: Hand
      SBonus: Bonus
      }

type BoardState = 
    { SPlayers: (string*PlayerState) []
      STable: STable
      SDiscardPile: Card []
      SDrawPile: int option
      SFreeBarns: Parcel[]
      SOccupiedBarns: Parcel[]
      SHayBales: Path[]
      SGoal: Goal
      SWinner: string
      SWinners: string[]
      SUseGameOver: bool option
      SHistory: (string * ( (string * Crossroad * Fence * Parcel list ) [])[] ) []
    }
and STable =
    { SPlayers: string[]
      SAllPlayers: string[]
      SNames: (string*string)[]
      SCurrent: int }

type UndoBoardState =
    { SBoard: BoardState
      SUndoPoint: BoardState
      SUndoType: string
      SShouldShuffle: bool
      SAtUndoPoint: bool }
    


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

    let neighborTiles (Crossroad(tile, side)) =
        let p = Parcel tile
        match side with
        | CLeft -> [p; p+Axe.NW; p+Axe.SW ]
        | CRight -> [ p; p+Axe.NE; p+Axe.SE]

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

    let crossroads (Parcel p) =
        [ Crossroad(p, CLeft)
          Crossroad(p, CRight)
          Crossroad(p+Axe.NW, CRight)
          Crossroad(p+Axe.NE, CLeft)
          Crossroad(p+Axe.SW, CRight)
          Crossroad(p+Axe.SE, CLeft) ]

    let contains (Crossroad(t,s)) (Parcel p) =
        t = p
        || (s = CRight && (t = p+Axe.NW || t = p+Axe.SW))
        || (s = CLeft && (t = p+Axe.NE || t = p+Axe.SE))
        

    let isOnBoard (Parcel p) =
        let x,y,z = Axe.cube p
        abs x <= 3 && abs y <= 3 && abs z <= 3

    let unrestrictedNeighbors (Parcel p) =
        [ Parcel (p + Axe.N)
          Parcel (p + Axe.NE)
          Parcel (p + Axe.SE)
          Parcel (p + Axe.S)
          Parcel (p + Axe.SW)
          Parcel (p + Axe.NW) ]

    let neighbors p =
        unrestrictedNeighbors p
        |> List.filter isOnBoard


    let areNeighbors (Parcel px) (Parcel py) =
       px = py+Axe.N || px = py+Axe.NE || px = py+Axe.SE || 
                   px = py+Axe.S || px = py+Axe.SW || px = py+Axe.NW 

    type ParcelDir =
        | PN
        | PNE
        | PSE
        | PS
        | PSW
        | PNW

    let getDir (Parcel px) (Parcel py) =
        if px + Axe.N = py then PN
        elif px + Axe.NE = py then PNE
        elif px + Axe.SE = py then PSE
        elif px + Axe.S = py then PS
        elif px + Axe.SW = py then PSW
        else PNW

    let dir n = 
        match n % 6 with
        | 0 -> PN
        | 1 -> PNE
        | 2 -> PSE
        | 3 -> PS
        | 4 -> PSW
        | _ -> PNW

    let dirs s n =
        set [ for i in 0 .. n -> dir (s + i) ]
        


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

    let allInnerPaths =
        set [ for q in -3 .. 3 do
                for r in max -2 (-2-q) .. min 3 (3-q)  do
                    Path(Axe(q,r), BN)

              for q in -3 .. 2 do
                for r in max -2 (-3-q) .. min 3 (3-q)  do
                    Path(Axe(q,r), BNE)

              for q in -2 .. 3 do
                for r in max -3 (-2-q) .. min 3 (3-q) do
                    Path(Axe(q,r), BNW) ]

    let boderPaths =
        set [
            for r in 0 .. 3 do
                Path(Axe(-3,r), BNW)
                Path(Axe(-3,r) + Axe.SW, BNE )
                Path(Axe(3,-r), BNE)
                Path(Axe(3,-r) + Axe.SE, BNW )

            for q in -3..0 do
                Path(Axe(q,-q-3), BNW)
                Path(Axe(q,-q-3), BN)
                Path(Axe(q,3)+Axe.SW, BNE)
                Path(Axe(q,3)+Axe.S, BN)

            for q in 0..3 do
                Path(Axe(q,-3), BN)
                Path(Axe(q,-3), BNE)
                Path(Axe(q,3-q)+Axe.S, BN)
                Path(Axe(q,3-q)+Axe.SE, BNW)
        ]
        

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

    let fencePaths (Fence paths) =
        paths |> List.map fst

    let bribeAnnexation p tractor (Fence paths) =
        let rec findExit remainingLength pos paths =
           match paths with
           | [] -> Some (remainingLength, pos, [])
           | (_,dir) :: tail ->
               let next = Crossroad.neighbor (Direction.rev dir) pos
               if Parcel.contains next p then
                   findExit remainingLength next tail
               else
                   Some (remainingLength, pos, paths)

        let rec findContact remainingLength pos paths   =
           match paths with
           | [] -> None
           | (_,dir) :: tail ->
               let next = Crossroad.neighbor (Direction.rev dir) pos
               if Parcel.contains next p then
                   findExit (remainingLength+1) next tail
               else
                   findContact (remainingLength+1) next tail 
            

        if Parcel.contains tractor p then
            findExit 0 tractor paths
        else
            findContact 0 tractor paths


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

    let truncate count (Fence paths) = Fence (List.truncate count paths)

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

    let givesAcceleration fence = length fence >= 4

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
    let size (Field x) = Set.count x

    let create parcel = Field (set [parcel])

    let ofParcels parcels  = Field(set parcels)
    let parcels (Field parcels) = Set.toList parcels 
    
    let contains parcel (Field parcels) =
        Set.contains (Parcel parcel) parcels

    let containsParcel parcel (Field parcels) =
        Set.contains parcel parcels


    let intersect (Field x) (Field y) =
        Field(Set.intersect x y)

    let unionMany (fields: Field list)=
        fields
        |> List.collect parcels
        |> set
        |> Field

    let crossroads (Field parcels) =
        parcels
        |> Seq.collect Parcel.crossroads
        |> set

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

    let border neighbors (Field parcels) =
        let allNeighbors =
            parcels
            |> Seq.collect neighbors
            |> set
        allNeighbors - parcels
        |> Field
        

    let borderTiles parcels = border Parcel.neighbors parcels
    let unrestrictedborderTiles parcels = border Parcel.unrestrictedNeighbors parcels

    let counterclock field (Crossroad(tile,side)) =
        match side with
        | CRight ->
            if contains tile field then
                if contains (tile + Axe.NE) field then
                    Horizontal, DE
                else
                    Up, DNW
            elif contains (tile + Axe.NE) field then
                if contains (tile + Axe.SE) field then
                    Down, DSW
                else
                    Horizontal , DE
            else
                Down,DSW
        | CLeft ->
            if contains tile field then
                if contains (tile + Axe.SW) field then
                    Horizontal, DW
                else
                    Down,DSE
            elif contains (tile + Axe.NW) field then
                Up,DNE
            else
                if contains (tile + Axe.NW) field then
                    Up, DNE
                else
                    Horizontal, DW


           
    let borderBetween start end' field =
        let rec loop orientedPath pos path =
            if pos = end' then
                List.rev path
            elif pos = start then
                []
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


    let rec findBorder field crossroad =
        let neighborTilesInField =
            Crossroad.neighborTiles crossroad
            |> List.sumBy (fun p -> if containsParcel p field then 1 else 0)

        if neighborTilesInField < 3 then
            crossroad
        else
            findBorder field (Crossroad.neighbor Up crossroad)



        

    let principalField field fence crossroad =
        let start = Fence.start crossroad fence
        if Crossroad.isInField field start then
            let onBorder = findBorder field start
            
            let border = borderBetween onBorder onBorder field
            fill border
        else
            empty


        
        
module Barns =
    let empty = { Free = Field.empty; Occupied = Field.empty }

    let intersectWith (field: Field) barns =
        { Free = Field.intersect field barns.Free
          Occupied = Field.intersect field barns.Occupied }

    let init barns =
        { Free = Field.ofParcels barns
          Occupied = Field.empty }


    let create axes = axes |> List.map Parcel

    let annex annexed barns =
        { Free = barns.Free - annexed.Free
          Occupied = barns.Occupied + (Field.intersect barns.Free annexed.Free) }

module HayBales =

    let findCutPaths hayBales =
        let neighbor dir crossroad = 
            let neighbor = Crossroad.neighbor dir crossroad
            if Crossroad.isOnBoard neighbor then
                let path = Path.neighbor dir crossroad
                if Set.contains path hayBales then
                    None
                else
                    Some (path,neighbor)
            else
                None

        let mutable cut = []
        let mutable visited = Map.empty
        let mutable time = 0
        let rec loop parent crossroad : int =
            visited <- Map.add crossroad time visited
            let d0 = time
            time <- time + 1 
            let upDepth =
                match neighbor Up crossroad with
                | Some (p,nxt) when nxt <> parent ->
                    let n = 
                        match Map.tryFind nxt visited with
                        | Some d -> d
                        | None -> loop crossroad nxt 
                    if n > d0 then
                        cut <- p :: cut 
                    n
                | _ -> 
                    d0 + 1
            let downDepth = 
                match neighbor Down crossroad with
                | Some (p,nxt) when nxt <> parent ->
                    let n = 
                        match Map.tryFind nxt visited with
                        | Some d -> d
                        | None -> loop crossroad nxt 
                    if n > d0 then
                        cut <- p :: cut 
                    n
                | _ -> 
                    d0 + 1
            let horizontalDepth = 
                match neighbor Horizontal crossroad with
                | Some (p,nxt) when nxt <> parent ->
                    let n = 
                        match Map.tryFind nxt visited with
                        | Some d -> d
                        | None -> loop crossroad nxt 
                    if n > d0 then
                        cut <- p :: cut 
                    n
                | _ -> 
                    d0 + 1

            let d = min upDepth downDepth |> min horizontalDepth 
            visited <- Map.add crossroad d visited
            d

        let start = Crossroad(Axe.center, CLeft)
        loop start start |> ignore
        set cut

        


    let hayBaleDestinations players hayBales =
        Path.allInnerPaths 
            - Set.unionMany 
                [ for _,p in players do
                    match p with
                    | Playing p -> p.Fence |> Fence.fencePaths |> set
                    | _ -> Set.empty ]
            - hayBales
            - findCutPaths hayBales

    type Blocker =
        | BorderBlocker
        | FenceBlocker
        | CutPathBlocker

    let hayBaleDestinationsWithComment players hayBales =
        let players = 
            Set.unionMany 
                [ for _,p in players do
                   match p with
                   | Playing p -> p.Fence |> Fence.fencePaths |> set
                   | _ -> Set.empty ]
        let cutPaths = findCutPaths hayBales

        
        [ for p in Path.allInnerPaths - players - hayBales - cutPaths do
            p, Ok()
          for p in players do
            p, Error FenceBlocker
          for p in cutPaths do
            p, Error CutPathBlocker
          for p in Path.boderPaths do
            p, Error BorderBlocker

        ]


    let maxReached hayBales =
        Set.count hayBales >= 8



type MoveBlocker =
    | Tractor
    | Protection
    | PhytosanitaryProducts
    | HayBaleOnPath
    | HighVoltageProtection


type Move =
    | Move of Direction * Crossroad
    | ImpossibleMove of Direction * Crossroad * MoveBlocker
    | SelectCrossroad of Crossroad


module Moves =
    let empty = 
        { Capacity = 0
          Done = 0
          Acceleration = false }

    let startTurn fence bonus =
        let acceleration = Fence.givesAcceleration fence
        { Capacity = 
            let baseMoves = 
                if acceleration then
                    4
                else
                    3
            baseMoves + Bonus.moveCapacityChange bonus
          Done = 0 
          Acceleration = acceleration
          }

    let canMove m =
        m.Done < m.Capacity

    let addCapacity n m =
        { m with 
            Capacity = min (m.Capacity + n)  5 }

    let doMove m =
        { m with Done = m.Done + 1}

module DrawPile =
    let cards =
        [ Nitro One,   6
          Nitro Two,   3
          Rut,         2
          HayBale One, 4
          HayBale Two, 3
          Dynamite,    4
          HighVoltage, 3
          Watchdog,    2
          Helicopter,  6
          Bribe,       3]
        |> List.collect (fun (c,n) -> [for _ in 1..n -> c])


    let shuffle useGameOver cards =
        let rand = System.Random()
        let cardsWithoutGameOver = 
                cards
                |> List.filter (function GameOver -> false | _ -> true) 
        let remainingCards = List.length cardsWithoutGameOver
        if remainingCards <= 8 || not useGameOver then
            cards
            |> List.sortBy (fun _ -> rand.Next()) 
        else
            let gameOverPos = rand.Next(1, 8) - 1
            let before, after =
                cardsWithoutGameOver
                |> List.sortBy (fun _ -> rand.Next()) 
                |> List.splitAt (remainingCards - gameOverPos)
            before @ [ GameOver ] @ after
        


    let remove cards pile =
        let count = Hand.count cards
        pile |> List.skip (min (List.length pile) count)

    let take count pile =
        pile |> List.truncate count


module Player =
    type Command =
        | Start of Start
        | SelectFirstCrossroad of SelectFirstCrossroad
        | Move of PlayerMove
        | PlayCard of PlayCard
        | Discard of Card
        | EndTurn
        | Undo
        | Quit
    and Start =
        { Parcel: Parcel }
    and SelectFirstCrossroad =
        { Crossroad: Crossroad }
    and PlayerMove =
        { Direction: Direction
          Destination: Crossroad }

    type Event =
        | FirstCrossroadSelected of FirstCrossroadSelected
        | FenceDrawn of Moved
        | FenceRemoved of Moved
        | FenceLooped of FenceLooped
        | MovedInField of Moved
        | MovedPowerless of Moved
        | FenceReduced of FenceReduced
        | Annexed of Annexed
        | CutFence of CutFence
        | PoweredUp
        | CardPlayed of PlayCard
        | SpedUp of SpedUp
        | Rutted
        | HighVoltaged
        | BonusDiscarded of Card
        | CardDiscarded of Card
        | Watched
        | Heliported of Crossroad
        | Bribed of Bribed
        | Eliminated
        | Undone
        | PlayerQuit
    and 
        [<CompiledName("PlayerStarted")>]
        Started =
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
           LostFields: (string * Parcel list) list
           FreeBarns: Parcel list
           OccupiedBarns: Parcel list
           FenceLength: int
        }
    and CutFence = 
        { Player: string }
    and FenceReduced = 
        { NewLength: int }
    and SpedUp =
        { Speed: int }
    and Bribed = 
        { Parcel: Parcel
          Victim: string}

    let isCut tractor player =
        match player with
        | Playing player ->
            not player.Bonus.HighVoltage
            &&
            Fence.fenceCrossroads player.Tractor player.Fence
            |> Seq.contains tractor
        | _ -> false

    let decideCut otherPlayers tractor =
        otherPlayers
        |> List.filter (snd >> isCut tractor)
        |> List.map (fun (playerid,_) -> CutFence { Player = playerid } )

        
                 

    let nearestContact field (Fence fence) pos =
        let rec loop pos fence len =
            if Crossroad.isInField field pos then
                Some(pos, fence, len)
            else
                match fence with
                | [] -> None
                | (_,dir) :: tail -> 
                    loop (Crossroad.neighbor (Direction.rev dir) pos) tail (len+1)
        loop pos fence 0

                 
    let fullAnnexation field fence tractor =
        let mainField = Field.principalField field fence tractor
        let start = Fence.start tractor fence
        if Crossroad.isInField mainField start then
            match nearestContact mainField fence tractor with
            | Some(pos, paths, len) when pos <> start ->
                let border = Field.borderBetween start pos mainField
                let fullBorder = 
                    paths @ border 
                Some(Field.fill fullBorder - field, len)
            | _ -> None
        else
            None


    let startTurn player =
        match player with
        | Playing p ->
            Playing 
                { p with
                    Moves = Moves.startTurn p.Fence p.Bonus }
        | Starting _ -> player
        | Ko _ -> player

    let color player =
        match player with
        | Playing p -> p.Color
        | Starting p -> p.Color
        | Ko color -> color
        
    let hand player =
        match player with
        | Playing p -> p.Hand
        | Starting p -> p.Hand
        | Ko _ -> Hand.empty

    let bonus player =
        match player with
        | Playing p -> p.Bonus
        | Starting p -> p.Bonus
        | Ko _ -> Bonus.empty

    let fence player =
        match player with
        | Playing p -> p.Fence
        | Starting _
        | Ko _ -> Fence.empty

    let field player =
        match player with
        | Playing p -> p.Field
        | Starting p -> Field.ofParcels [p.Parcel]
        | Ko _ -> Field.empty

    let isKo player =
        match player with
        | Ko _ -> true
        | _ -> false

    let moves player =
        match player with
        | Playing p -> p.Moves
        | _ -> Moves.empty


    let toPrivate player =
        match player with
        | Playing p -> Playing { p with Hand = Hand.toPrivate p.Hand }
        | Starting p -> Starting { p with Hand = Hand.toPrivate p.Hand }
        | Ko p -> player

    let fieldTotalSize player =
        match player with
        | Playing p -> Field.size p.Field
        | Starting _ -> 1
        | Ko _ -> 0

    let principalFieldSize player =
        match player with
        | Playing p -> 
            Field.principalField p.Field p.Fence p.Tractor
            |> Field.size
        | Starting _ -> 1
        | Ko _ -> 0


    let watchedField player =
        match player with
        | Playing ({ Bonus = { Watched = true }; Field = field }) -> field
        | _ -> Field.empty

    let canUseHelicopter player =
        match player with
        | Playing p -> Fence.isEmpty p.Fence
        | _ -> false

     

    let decide (otherPlayers: (string * CrazyPlayer) list) barns hayBales bribeParcels command player =
        match player, command with
        | Starting _, SelectFirstCrossroad cmd ->
            [ FirstCrossroadSelected { Crossroad = cmd.Crossroad } ]
        | Playing player, Move cmd ->
            let dir = cmd.Direction
            let nextPos = Crossroad.neighbor dir player.Tractor
            let nextPath = Path.neighbor dir player.Tractor
            if nextPos <> cmd.Destination || not (Moves.canMove player.Moves) then
                []
            else
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
                                    let nextFence = Fence.add (nextPath,dir) player.Fence
                                    if pathInField && Fence.length nextFence = 1 then
                                        false
                                    else
                                        let fenceStart = Fence.start nextPos nextFence
                                        not (Field.isInSameField fenceStart nextPos player.Field)
                                else
                                    false

                            [ 
                              if pathInField && not inFallow then
                                  MovedInField { Move = dir; Path = nextPath; Crossroad = nextPos }  
                              else
                                  FenceDrawn { Move = dir; Path = nextPath; Crossroad = nextPos }  
                              yield! decideCut otherPlayers nextPos
                              
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
        | Playing p, PlayCard card ->
            let c = Card.ofPlayCard card
            if Hand.contains c p.Hand then
                match card with
                | PlayNitro power ->
                        [ CardPlayed card
                          SpedUp {  Speed = match power with One -> 1 | Two -> 2 }
                        ]

                | PlayHighVoltage ->
                        [ CardPlayed card
                          HighVoltaged ]
                | PlayWatchdog ->
                        [ CardPlayed card
                          Watched ]
                | PlayRut _ ->
                    [ CardPlayed card ]
                | PlayHelicopter crossroad ->
                    let othersCrossroads = 
                        set [ for _,p in otherPlayers do
                                match p with
                                | Playing p -> yield! Fence.fenceCrossroads p.Tractor p.Fence
                                | _ -> () ] 
                    if canUseHelicopter player 
                        && Crossroad.isInField (field player) crossroad
                        && not (Set.contains crossroad othersCrossroads)
                        then
                        [  CardPlayed card
                           Heliported crossroad
                           if p.Power = PowerDown && Crossroad.isInField p.Field crossroad then
                                PoweredUp ]
                    else
                        []
                | PlayHayBale(hb,rm)  ->
                    if max (Set.count hayBales + List.length hb - 8) 0 = List.length rm then
                        if Set.isSubset (set rm) hayBales then
                            let dests = HayBales.hayBaleDestinations (("",player) :: otherPlayers) hayBales
                            
                            if hb |> List.forall (fun b -> Set.contains b dests) then
                                [ CardPlayed card
                                  BonusDiscarded (Card.ofPlayCard card) ]
                            else
                                []
                        else
                            []
                    else
                        []
                | PlayDynamite hb ->
                    if Set.contains hb hayBales then
                        [ CardPlayed card
                          BonusDiscarded (Card.ofPlayCard card) ]
                    else
                        []
                | PlayBribe parcel ->
                    match bribeParcels() with
                    | Ok bribable when Field.containsParcel parcel bribable ->
                        [ CardPlayed card
                          for playerId, player in otherPlayers do
                            if field player |> Field.containsParcel parcel then
                              Bribed { Parcel = parcel; Victim = playerId }
                          BonusDiscarded (Card.ofPlayCard card)
                        ]

                    | _ -> []
                | PlayGameOver -> []


            else 
                []
        | Playing p, Discard card ->
            if Hand.contains card p.Hand then
                [ CardDiscarded card ]
            else
                []
        
        | Starting _, Quit
        | Playing _, Quit ->
            [ PlayerQuit ]
        | Ko _, Quit -> []
        | _ -> failwith "Invalid operation"

    let evolve player event =
        match player, event with
        | Starting p, FirstCrossroadSelected e ->
            Playing { Color = p.Color
                      Tractor = e.Crossroad
                      Fence = Fence.empty
                      Field = Field.create p.Parcel
                      Power = PowerUp
                      Moves = Moves.startTurn Fence.empty p.Bonus
                      Hand = p.Hand
                      Bonus = p.Bonus
                      }
        | Playing player, FenceDrawn e -> Playing { player with Tractor = e.Crossroad; Fence = Fence.add (e.Path, e.Move) player.Fence; Moves = Moves.doMove player.Moves }
        | Playing player, FenceRemoved e -> Playing { player with Tractor = e.Crossroad; Fence = Fence.tail player.Fence ; Moves = Moves.doMove player.Moves }
        | Playing player, FenceLooped e -> Playing { player with Tractor = e.Crossroad; Fence = Fence.remove e.Loop player.Fence ; Moves = Moves.doMove player.Moves }
        | Playing player, MovedInField e -> Playing { player with Tractor = e.Crossroad; Fence = Fence.empty; Moves = Moves.doMove player.Moves  }
        | Playing player, MovedPowerless e -> Playing { player with Tractor = e.Crossroad; Moves = Moves.doMove player.Moves  }
        | Playing player, FenceReduced e -> Playing { player with Fence = Fence.truncate e.NewLength player.Fence}
        | Playing player, PoweredUp -> Playing { player with Power = PowerUp }
        | Playing player, Annexed e -> Playing { player with Fence = Fence.truncate e.FenceLength player.Fence ; Field = player.Field + Field.ofParcels e.NewField}
        | Playing player, HighVoltaged -> Playing { player with Bonus = { player.Bonus with  HighVoltage = true}}
        | Playing player, Watched -> Playing { player with Bonus = { player.Bonus with  Watched = true }}
        | Playing player, Rutted -> Playing { player with Bonus = { player.Bonus with  Rutted = player.Bonus.Rutted + 1 }}
        | Playing player, SpedUp e -> Playing { player with Moves = player.Moves |> Moves.addCapacity e.Speed }
        | Playing player, Heliported e -> Playing { player with Tractor = e; Fence = Fence.empty; Bonus = { player.Bonus with Heliported = player.Bonus.Heliported + 1}  }
        | Playing player, Bribed p -> Playing { player with Field = player.Field + Field.ofParcels [p.Parcel] }

        | Playing player, CardPlayed card ->
            Playing { player with
                        Hand = 
                            player.Hand 
                            |> Hand.remove (Card.ofPlayCard card)
                        Bonus =
                            match card with
                            | PlayNitro One ->
                                { player.Bonus with NitroOne = player.Bonus.NitroOne + 1 }
                            | PlayNitro Two ->
                                { player.Bonus with NitroTwo = player.Bonus.NitroTwo + 1 }
                            | _ -> player.Bonus

                            }

        |  Playing player, BonusDiscarded e ->
            Playing { player with
                        Bonus = player.Bonus |> Bonus.discard e }
        | Playing player, CardDiscarded card ->
            Playing { player with Hand = player.Hand |> Hand.remove card }
        | Playing player, Eliminated
        | Playing player, PlayerQuit ->
            Ko player.Color
        | Starting player, PlayerQuit ->
            Ko player.Color
        | _ -> player 



    let exec otherPlayers barns haybales cmd state =
        state
        |> decide otherPlayers barns haybales (fun() -> Ok Field.empty) cmd
        |> List.fold evolve state

    let move dir player =
        match player with
        | Playing p ->
            player
            |> exec [] Barns.empty Set.empty (Move {Direction = dir; Destination = p.Tractor })
        | _ -> failwith "Not playing"

    let start color parcel pos =
        Starting  { Parcel = parcel; Color = color; Hand = PublicHand []; Bonus = Bonus.empty  }
        |> exec [] Barns.empty Set.empty (SelectFirstCrossroad { Crossroad = pos})


    let possibleMove player dir =
        let pos = Crossroad.neighbor dir player.Tractor
        if Crossroad.isOnBoard pos then
            [ dir, Ok pos]
        else
            []

    let basicMoves player =
        match player with
        | Playing player when Moves.canMove player.Moves ->
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
        let fence = Fence.fenceCrossroads player.Tractor player.Fence
        match Seq.tryFindIndex (fun p -> p = c) fence with
        | None -> Ok c
        | Some i ->
            if player.Bonus.HighVoltage then
                Error(c, HighVoltageProtection)
            elif i < 2 then
                Error(c, Protection)
            else
                Ok c

        
    let checkHeliported moverBonus player c =
        if moverBonus.Heliported > 0 then
            let isOnFence =
                player.Fence
                |> Fence.fenceCrossroads player.Tractor
                |> Seq.exists (fun p -> p = c)

            if isOnFence then
                Error(c, PhytosanitaryProducts )
            else
                Ok c
        else
            Ok c

    let checkMove moverbonus player c =
        match player with
        | Playing player ->
            checkTractor player c 
            >>= checkProtection player
            >>= checkHeliported moverbonus player
           
        | _ -> Ok c

    let otherPlayers playerid (board: PlayingBoard) =
        board.Players
        |> Map.toSeq
        |> Seq.filter (fun (id,_) -> id <> playerid)
        |> Seq.toList


    let possibleMoves playerid (board: Board) =
        match board, playerid with
        | Board board, Some playerid ->
            match Map.tryFind playerid board.Players with
            | Some ((Playing p) as player) when Moves.canMove p.Moves->
                let otherPlayers =
                    otherPlayers playerid board
                    |> List.map snd
                let check = 
                    checkMove (bonus player)
                [ 
                    for dir,m in basicMoves player do
                        let path = Path.neighbor dir p.Tractor
                        if Set.contains path board.HayBales then
                            let c = Crossroad.neighbor dir p.Tractor
                            ImpossibleMove(dir, c, MoveBlocker.HayBaleOnPath)
                        else
                            match Seq.fold (fun c p -> bindMove (check p) c) m otherPlayers with
                            | Ok c -> Move.Move(dir, c)
                            | Error (c,e) -> ImpossibleMove(dir, c, e) ]
            | Some (Starting { Parcel = Parcel p}) ->
                [ SelectCrossroad (Crossroad (p, CLeft))
                  SelectCrossroad (Crossroad (p,CRight))
                  SelectCrossroad (Crossroad (p+Axe.NW, CRight))
                  SelectCrossroad (Crossroad (p+Axe.NE, CLeft))
                  SelectCrossroad (Crossroad (p+Axe.SW, CRight))
                  SelectCrossroad (Crossroad (p+Axe.SE, CLeft)) ]
            | Some (Ko _)
            | Some (Playing _)
            | None -> []
        | _ -> []

    let canMove playerid (board: Board) =
        possibleMoves playerid board
        |> List.exists (function ImpossibleMove _ -> false | _ -> true)

    let takeCards cards player =
        match player with
        | Playing p ->
            Playing 
                { p with 
                    Hand = 
                        match p.Hand, cards with
                        | PublicHand h, PublicHand c -> PublicHand (h @ c)
                        | PrivateHand h, PrivateHand c -> PrivateHand (h + c)  
                        | _ -> failwith "Unexpected mix" }
        | Starting p ->
            Starting
                { p with
                    Hand = 
                        match p.Hand, cards with
                        | PublicHand h, PublicHand c -> PublicHand (h @ c)
                        | PrivateHand h, PrivateHand c -> PrivateHand (h + c)  
                        | _ -> failwith "Unexpected mix" }
        | Ko _ -> player



    let toState (p: CrazyPlayer) =
        match p with
        | Starting p -> 
            SStarting 
                { SColor = p.Color
                  SParcel = p.Parcel
                  SHand = p.Hand
                  SBonus = p.Bonus
                  }
        | Playing p ->
            SPlaying
                { SColor = p.Color
                  STractor = p.Tractor
                  SFence = p.Fence
                  SField =
                    let (Field f) = p.Field
                    f |> Set.toList
                  SPower = p.Power
                  SMoves = p.Moves
                  SHand = p.Hand
                  SBonus = p.Bonus
                  }
        | Ko color -> SKo color


    let ofState (p: PlayerState) =
        match p with
        | SStarting p -> 
            Starting 
                { Color = p.SColor
                  Parcel = p.SParcel
                  Hand = p.SHand
                  Bonus = p.SBonus}
        | SPlaying p ->
            Playing {
                Color = p.SColor
                Tractor = p.STractor
                Fence = p.SFence
                Field = Field (set p.SField)
                Power = p.SPower
                Moves = p.SMoves
                Hand = p.SHand
                Bonus = p.SBonus
            }
        | SKo color -> Ko color


module History =
    let empty = { PlayersHistory = Map.empty }
    let createPos (board: PlayingBoard) =
        { Positions = 
            board.Players
            |> Map.toSeq
            |> Seq.choose (fun (pid, player) ->
                match player with
                | Playing p ->
                    Some ({ Player = pid
                            TractorPos = p.Tractor
                            FencePos = p.Fence
                            FieldPos = p.Field })
                | _ -> None)

            |> set }

    let repetitions player (boardPos: BoardPosition) (history: History) =
        match Map.tryFind player history.PlayersHistory with
        | Some h -> 

            let rec count n h =
                match h with
                | [] -> n
                | pos :: rest  ->
                    if pos = boardPos then
                        count (n+1) rest
                    else
                        count n rest

            count 0 h
        | None -> 0


    let addPos player (boardPos: BoardPosition) (history: History) =
        let playerHistory =
            match Map.tryFind player history.PlayersHistory with
            | None -> []
            | Some h -> h
            
        { PlayersHistory = 
            history.PlayersHistory
            |> Map.add player (boardPos :: playerHistory) }
                               
   
    let findDangerousPositions player (boardPos: BoardPosition) (history: History) =
        [ for KeyValue(opponent, h) in history.PlayersHistory do
            if opponent <> player then
                let posOfOtherPlayers = boardPos.Positions |> Set.filter (fun p -> p.Player <> opponent)
                let rec findRep once twice positions =
                    match positions with
                    | [] -> twice
                    | pos :: rest ->
                        if Set.isSubset posOfOtherPlayers pos.Positions then
                            if Set.contains pos.Positions once then
                                findRep once (Set.add pos.Positions twice) rest
                            else
                                findRep (Set.add pos.Positions once) twice rest
                        else
                            findRep once twice rest

                yield!
                    findRep Set.empty Set.empty h
                    |> Seq.choose ( Seq.tryFind (fun p -> p.Player = opponent ) )
                    |> Seq.map (fun p -> p.Player, p.TractorPos)
                    |> Seq.toList ]


                


        


module Board =
    let initialState = 
        { Board = InitialState
          UndoPoint = InitialState
          UndoType = NoUndo
          ShouldShuffle = false 
          AtUndoPoint = true }

    [<CompiledName("BoardCommand")>]
    type Command =
        | Play of string * Player.Command
        | Start of BoardStart
    and BoardStart =
        { Players: (Color * string * string) list
          Goal: Goal
          Undo: UndoType
          UseGameOver: bool
        }

    [<CompiledName("BoardEvent")>]
    type Event =
        | Played of string * Player.Event
        | Started of Started
        | Next
        | PlayerDrewCards of PlayerDrewCards
        | GameWon of string
        | GameEnded of string list
        | GameEndedByRepetition
        | RepetitionDetected of player:string
        | HayBalesPlaced of added: Path list * removed: Path list
        | HayBaleDynamited of Path
        | DiscardPileShuffled of Card list
        | DrawPileShuffled of Card list
        | UndoCheckPointed
    and 
        [<CompiledName("BoardStarted")>]
        Started =
        { Players: (Color*string*string*Parcel) list 
          DrawPile: Card list
          Barns: Parcel list
          Goal: Goal
          Undo: UndoType
          UseGameOver: bool
        }
    and PlayerDrewCards =
        { Player: string 
          Cards: Hand }
        
    let currentPlayer (board: PlayingBoard) =
        board.Players.[board.Table.Player]

    let currentOtherPlayers board =
        Player.otherPlayers board.Table.Player board

    let totalSize (board: PlayingBoard) = 
       let players = board.Players
       players
       |> Map.fold (fun count _ p -> count + Player.fieldTotalSize p) 0



    let hayBales board =
        match board with
        | Board.InitialState -> Set.empty
        | Board.Board b -> b.HayBales
        | Board.Won(_,b) -> b.HayBales

    let endGameWithBribe (board: PlayingBoard) =
        match board.Goal with
        | Common _ -> 
            false // the parcel always belong to another player and wont change the total size
        | Individual goal ->
            let player = currentPlayer board
            Player.fieldTotalSize player + 1 >= goal

    type FindWinner =
        | Winner of string list
        | Lead of string list 

    let tryFindWinner (board: PlayingBoard) =
        let players = board.Players
        let _, leads = 
            players
            |> Map.toList
            |> List.groupBy(fun (_,p)-> Player.principalFieldSize p, Player.fieldTotalSize p)
            |> List.maxBy(fun (c,_) -> c)


        let leadsids = List.map (fun (id,_) -> id) leads
        match board.Goal with
        | Common goal ->
            if totalSize board >= goal then
                Winner leadsids
            else
                Lead leadsids

        | Individual goal ->
            let won =
                players
                |> Map.exists (fun _ p -> Player.fieldTotalSize p >= goal)
            if won then
                Winner leadsids
            else
                Lead leadsids

    let next shouldShuffle repeated state =
        let playerId = state.Table.Player
        let player = Map.tryFind playerId state.Players
        let nextPlayerId = state.Table.Next.Player 
        let nextPlayer = state.Players.[nextPlayerId]
        [ 
          let bonus = 
            match player with
            | Some player -> Player.bonus player
            | None -> Bonus.empty
          yield! 
               Bonus.endTurn bonus
               |> List.map (fun c -> Played(playerId, Player.BonusDiscarded c))

          if shouldShuffle then
            match state.DrawPile with
            | PublicHand drawPile -> yield DrawPileShuffled (DrawPile.shuffle state.UseGameOver drawPile)
            | PrivateHand _ -> ()

          yield Next
          if repeated then
            yield RepetitionDetected playerId
          yield! 
           Bonus.startTurn (Player.bonus nextPlayer)
           |> List.map (fun c -> Played(nextPlayerId, Player.BonusDiscarded c))
          yield UndoCheckPointed ]
    type BribeBlocker =
        | InstantVictory
        | NoParcelsToBribe

    type BribeParcelBlocker =
        | BarnBlocker
        | LastParcelBlocker
        | WatchedBlocker
        | FenceBlocker
        | FallowBlocker
        | BridgeBlocker



    let isCutParcel (field: Field) (parcel: Parcel) =
        let neighbors =
            [ Axe.N; Axe.NE; Axe.SE; Axe.S; Axe.SW; Axe.NW ]
            
            
        let rec find neighbors result =
            match neighbors with
            | [] -> result
            | axe :: tail ->            
                let neighbor = parcel + axe
                let infield = Field.containsParcel neighbor field 
                match result with
                | [] -> find tail [infield]
                | prev :: _ when prev = infield -> find tail result
                | _ -> find tail (infield :: result)

        let changes = find neighbors []
        let changes =
            if List.head changes = List.last changes then
                List.tail changes
            else
                changes

        List.length changes > 2
                
            


    let cutParcels (field: Field) (Field parcels) =
        parcels
        |> Seq.filter (fun p -> isCutParcel field p)
        |> Field.ofParcels
            
    let findBridgeParcels field =
        let (Field border) = Field.unrestrictedborderTiles field


        let neighbor (dir: Axe) (parcel: Parcel) = 
            let neighbor = parcel + dir
            if Set.contains neighbor border then
                Some neighbor
            else
                None

        let mutable cut = []
        let mutable visited = Map.empty
        let mutable time = 0


        let rec loop parent parcel : int =
            visited <- Map.add parcel time visited
            let d0 = time
            time <- time + 1 
            let isRoot = (parent = parcel)

            let step dir (low,children) =
                match neighbor dir parcel with
                | Some nxt when nxt <> parent ->
                    let n,c = 
                        match Map.tryFind nxt visited with
                        | Some d -> min d low, children
                        | None -> loop parcel nxt, children+1

                    if n >= d0 && not isRoot then
                        cut <- parcel :: cut
                        
                    
                    min n low,c
                | _ -> 
                    low,children


            let d, children = 
                (d0, 0)
                |> step Axe.N
                |> step Axe.NE
                |> step Axe.SE
                |> step Axe.S
                |> step Axe.SW
                |> step Axe.NW

            if isRoot && children > 1 then
                cut <- parcel :: cut

            visited <- Map.add parcel d visited
            d

        let start = Set.minElement border
        loop start start |> ignore
        cut 
        |> List.filter Parcel.isOnBoard
        |> Field.ofParcels 
            



    let bribeParcels (board: PlayingBoard) =
        if  endGameWithBribe board then
            Error InstantVictory
        else
            let player = board.Players.[board.Table.Player]
            let playerField = Player.field player
            let border = Field.borderTiles playerField
            let barns = board.Barns.Free + board.Barns.Occupied
            let bridgeParcels = findBridgeParcels playerField

            let otherPlayersFields =
                 currentOtherPlayers board
                 |> List.map (fun (_, neighborPlayer) -> 
                        let field = Player.field neighborPlayer
                        let bonus = Player.bonus neighborPlayer
                        if Field.size field = 1 || bonus.Watched  then
                            Field.empty
                        else
                            let cutParcels = cutParcels field (Field.intersect field border)
                            
                            match neighborPlayer with
                            | Playing p ->
                                let startCrossRoad = Fence.start p.Tractor p.Fence
                                let startTiles = 
                                    Crossroad.neighborTiles startCrossRoad
                                    |> Field.ofParcels
                                    |> Field.intersect field

                                
                                field - startTiles - cutParcels - bridgeParcels
                                

                            | Starting _ 
                            | Ko _ -> field
                                )
                 |> Field.unionMany
            

            let parcelsToBribe = (Field.intersect border otherPlayersFields) - barns
            if Field.isEmpty parcelsToBribe then
                Error NoParcelsToBribe
            else
                Ok parcelsToBribe

    let bribeParcelsBlockers (board: PlayingBoard) =
        if  endGameWithBribe board then
            []
        else
            let player = board.Players.[board.Table.Player]
            let playerField = Player.field player
            let border = Field.borderTiles playerField
            let othersFields = Field.unionMany [for (_,p) in currentOtherPlayers board -> Player.field p ]
            let barns = 
                Field.intersect border (board.Barns.Free + board.Barns.Occupied)
                |> Field.intersect othersFields
            let border = border - barns
            let bridgeParcels = findBridgeParcels playerField

            [ for barn in Field.parcels barns do
                barn, BarnBlocker
            
              for (_, neighborPlayer) in currentOtherPlayers board do
                let field = Player.field neighborPlayer
                let bonus = Player.bonus neighborPlayer
                let fieldBorder = Field.intersect border field

                if Field.size field = 1  then
                    for p in Field.parcels fieldBorder do
                        p, LastParcelBlocker
                elif bonus.Watched  then
                    for p in Field.parcels fieldBorder do
                        p, WatchedBlocker
                else
                    match neighborPlayer with
                    | Playing p ->
                        let startCrossRoad = Fence.start p.Tractor p.Fence
                        let startTiles = 
                            Crossroad.neighborTiles startCrossRoad
                            |> Field.ofParcels
                            |> Field.intersect field
                        let borderStarts = Field.intersect startTiles border
                        for p in Field.parcels borderStarts do
                            p, FenceBlocker
                        let cutParcels = cutParcels field (Field.intersect field border)
                        for p in Field.parcels cutParcels do
                            p, FallowBlocker
                        let bridges = Field.intersect bridgeParcels field
                        for p in Field.parcels bridges do
                            p, BridgeBlocker
                    | Starting _ 
                    | Ko _ -> () ]


    let annexed playerid e (board: PlayingBoard) =
        let annexedPlayer = Player.evolve board.Players.[playerid] (Player.Annexed e)
        let newMap = Map.add playerid annexedPlayer board.Players
        let freeFields = Field.ofParcels e.NewField - (Field.unionMany [for _, f in e.LostFields -> Field.ofParcels f])
        e.LostFields
        |> List.fold (fun (board: PlayingBoard) (playerid, parcels) ->
            match board.Players.[playerid] with
            | Playing p ->
                let newP = Playing { p with Field = p.Field - Field.ofParcels parcels }
                { board with
                            Players = Map.add playerid newP board.Players }
            | _ -> board
        ) { board with Players = newMap
                       Barns =
                           let annexedBarns =
                               { Free = e.FreeBarns |> Field.ofParcels
                                 Occupied = e.OccupiedBarns |> Field.ofParcels }
                           Barns.annex annexedBarns board.Barns
                       History = if Field.isEmpty freeFields then board.History else History.empty
                           }

    let bribed playerid p (board: PlayingBoard) =
        let newPlayer = Player.evolve board.Players.[playerid] (Player.Bribed p)

        let newVictim = 
            match board.Players.[p.Victim] with
            | Starting p -> Starting p
            | Playing victim -> Playing { victim with Field = victim.Field - Field.ofParcels [p.Parcel] }
            | Ko _ as p -> p
        { board with
            Players =
                board.Players
                |> Map.add playerid newPlayer
                |> Map.add p.Victim newVictim 
        }

    let evolve (state: UndoableBoard) event =
        match state.Board,event with
        | InitialState, Started s -> 
           let board = 
                Board 
                    { Players =
                        Map.ofList [ for c,u,n,p in s.Players -> u, Starting { Color = c; Parcel = p; Hand = PublicHand []; Bonus = Bonus.empty}]
                      Table =  Table.start [ for _,p,n,_ in s.Players -> p,n ] 
                      DrawPile = PublicHand s.DrawPile
                      DiscardPile = []
                      Barns = Barns.init s.Barns
                      HayBales = Set.empty
                      Goal = s.Goal
                      UseGameOver = s.UseGameOver
                      History = History.empty
                       }
           { Board = board
             UndoPoint = board
             UndoType = s.Undo
             ShouldShuffle = false
             AtUndoPoint = false }
        | InitialState, _ -> state
        | Board _, Started _ -> state
        | Board board, GameWon player
            -> 
                let won = Won([player], board)
                { state with
                    Board = won 
                    UndoPoint = won
                    AtUndoPoint = true }
        | Board board, GameEnded players
            -> 
                let won = Won(players, board)
                { state with
                    Board = won 
                    UndoPoint = won
                    AtUndoPoint = true }
        | Board board, GameEndedByRepetition
            -> 
                let won = Won([], board)
                { state with
                    Board = won
                    UndoPoint = won
                    AtUndoPoint = true }
        | Board _, RepetitionDetected _ ->
            state

                

        | Board board, Played (_, Player.CutFence { Player = playerid }) ->
            match board.Players.[playerid] with
            | Playing player -> 
                let cutPlayer =
                    Playing { player with
                                Fence = Fence.empty
                                Power = PowerDown }
                { state with 
                    Board = 
                        Board { board with
                                    Players = Map.add playerid cutPlayer board.Players }
                    AtUndoPoint = false }
            | _ -> state

        | Board board, Played (playerid, Player.Annexed e ) ->
            let newBoard = 
                annexed playerid e board
                |> Board


            { state with Board = newBoard
                         AtUndoPoint = false }
        |  Board board, PlayerDrewCards e ->
            let newDrawPile = 
                match board.DrawPile with
                | PublicHand cards -> DrawPile.remove e.Cards cards |> PublicHand
                | PrivateHand cards -> PrivateHand (cards - Hand.count e.Cards) 
            let player = board.Players.[e.Player] |> Player.takeCards e.Cards
            let newBoard = 
                Board {
                    board with
                        Players = Map.add e.Player player board.Players
                        DrawPile = newDrawPile
                         }
            match state.UndoType with
            | FullUndo ->
                { state with Board = newBoard; ShouldShuffle = true; AtUndoPoint = false }
            | _ -> { state with Board = newBoard }
        | Board _, UndoCheckPointed ->
                { state with UndoPoint = state.Board; ShouldShuffle = false; AtUndoPoint = true  }
            

        | Board board, HayBalesPlaced (added, removed) ->
            let newBoard = 
                Board {
                    board with
                        HayBales = board.HayBales - set removed + set added }
            { state with Board = newBoard
                         AtUndoPoint = false }
        | Board board, HayBaleDynamited p ->
            let newBoard = 
                Board {
                    board with
                        HayBales = board.HayBales |> Set.remove p }
            { state with Board = newBoard
                         AtUndoPoint = false }
        | Board board, DiscardPileShuffled cards ->
            let newBoard = 
                Board {
                    board with
                        DrawPile = 
                            match board.DrawPile with
                            | PublicHand drawPile ->
                                 PublicHand (drawPile @ cards)
                            | PrivateHand drawPile -> PrivateHand(drawPile + List.length cards)
                        DiscardPile = [] }
            { state with Board = newBoard
                         AtUndoPoint = false }
        | Board board, DrawPileShuffled cards ->
            let newBoard =
                Board {
                    board with
                        DrawPile = 
                            match board.DrawPile with
                            | PublicHand _ -> PublicHand cards
                            | PrivateHand _ ->  board.DrawPile }

            { state with Board = newBoard
                         AtUndoPoint = false }


        | Board board, Played(playerid,(Player.Bribed p)) ->
            let newBoard = bribed playerid p board
            
            { state with Board = Board newBoard 
                         AtUndoPoint = false }
        | Board board, Played(playerid,(Player.Eliminated as e)) ->
            let newPlayer = Player.evolve board.Players.[playerid] e
            let newTable = Table.eliminate playerid board.Table
            let newBoard =
                Board { board with
                            Players = Map.add playerid newPlayer board.Players
                            Table = newTable
                        }
            { state with Board = newBoard 
                         AtUndoPoint = false}
        | Board board, Played(playerid,(Player.PlayerQuit as e)) ->
            let currentPlayer = board.Table.Player
            let newPlayer = Player.evolve board.Players.[playerid] e
            let newTable = Table.eliminate playerid board.Table
            let players = Map.add playerid newPlayer board.Players

            let players = 
                if playerid = currentPlayer then
                    let nextPlayer = Player.startTurn board.Players.[newTable.Player]
                    players |> Map.add newTable.Player nextPlayer
                else
                    players
            let newBoard =
                Board { board with
                            Players = players
                            Table = newTable
                        }
            { state with Board = newBoard 
                         AtUndoPoint = false}
        | _, Played (_, Player.Undone) -> 
            { state with Board = state.UndoPoint
                         AtUndoPoint = true }

        | Board board, Played (playerid,e) ->
            let player = Player.evolve board.Players.[playerid] e
            let newDiscardPile =
                match e with
                | Player.BonusDiscarded card
                | Player.CardDiscarded card ->
                    card :: board.DiscardPile
                | _ -> board.DiscardPile

            let newBoard = 
                Board { board with
                           Players = Map.add playerid player board.Players
                           DiscardPile = newDiscardPile }
            { state with Board = newBoard
                         AtUndoPoint = false }
        
        | Board board, Next ->
            let previousPlayer = board.Table.Player
            let nextTable = board.Table.Next
            let player = Player.startTurn board.Players.[nextTable.Player]
            let newBoard = 
                Board {
                    board with
                        Players = Map.add nextTable.Player player board.Players
                        Table = nextTable
                        History = History.addPos previousPlayer (History.createPos board) board.History
                        }
            { state with
                Board = newBoard
                UndoPoint = newBoard
                ShouldShuffle = false
                AtUndoPoint = true }
        | Won _, _ -> state

    
    let cont f (board: UndoableBoard, events) =
        match board.Board with
        | Board b ->
            let newEvents = f b events 
            
            let newBoard = List.fold evolve board newEvents
            (newBoard, events @ newEvents)
        | _ -> board, events


    module Configurations =
        module P2 =
            let classicpos = 
                Parcel.center + 2 * Axe.N,
                Parcel.center + 2 * Axe.S 
            let classic  =
                classicpos,
                Barns.create
                    [ Axe.zero
                      3 * Axe.N 
                      3 * Axe.S 
                      3 * Axe.NE 
                      3 * Axe.NW 
                      3 * Axe.SE 
                      3 * Axe.SW 
                      Axe.W2
                      Axe.E2
                      Axe.N + Axe.NE
                      Axe.N + Axe.NW
                      Axe.S + Axe.SE
                      Axe.S + Axe.SW ] 

            let snake =
                classicpos,
                Barns.create
                    [ Axe.zero
                      2 * Axe.N + Axe.NW
                      Axe.N + Axe.NE
                      Axe.NE
                      3 * Axe.NE
                      2 * Axe.NW
                      3 * Axe.NW
                      Axe.SW
                      3 * Axe.SW
                      Axe.S + Axe.SW
                      2*Axe.S + Axe.SE
                      2* Axe.SE
                      3* Axe.SE ]

            let star =
                classicpos,
                Barns.create
                    [ Axe.zero
                      Axe.N
                      Axe.S
                      3 * Axe.N
                      3 * Axe.S
                      2 * Axe.NW
                      3 * Axe.NW
                      2 * Axe.NE
                      3 * Axe.NE
                      2 * Axe.SW
                      3 * Axe.SW
                      2 * Axe.SE
                      3 * Axe.SE ]


        module P3 =
            let classicpos =
                Parcel.center + 2 * Axe.N,
                Parcel.center + 2 * Axe.SW,
                Parcel.center + 2 * Axe.SE

            let classic = 
                classicpos,
                Barns.create
                    [ Axe.zero
                      3 * Axe.N 
                      3 * Axe.S 
                      3 * Axe.NE 
                      3 * Axe.NW 
                      3 * Axe.SE 
                      3 * Axe.SW 
                      Axe.W2
                      Axe.E2
                      Axe.N + Axe.NE
                      Axe.N + Axe.NW
                      Axe.S + Axe.SE
                      Axe.S + Axe.SW ]
            let galaxy = 
                classicpos,
                Barns.create
                    [ Axe.zero
                      Axe.N
                      Axe.SW
                      Axe.SE
                      2 * Axe.S
                      2 * Axe.S + Axe.SW
                      Axe.S + 2 * Axe.SE
                      Axe.W2 + Axe.SW
                      Axe.E2 + Axe.NE
                      2 * Axe.NE
                      2 * Axe.NW
                      2 * Axe.NW + Axe.N
                      2 * Axe.N + Axe.NE ]

            let famine =
                classicpos,
                Barns.create
                    [ Axe.NW
                      2 * Axe.NW + Axe.SW
                      2 * Axe.NW + Axe.N
                      Axe.NE
                      2 * Axe.NE + Axe.SE
                      2 * Axe.NE + Axe.N
                      Axe.S
                      2 * Axe.S + Axe.SE
                      2 * Axe.S + Axe.SW ]

        
        module P4 =
            let classicpos =
                Parcel.center + Axe.N + Axe.NE,
                Parcel.center + 2 * Axe.NW,
                Parcel.center + Axe.SW + Axe.S,
                Parcel.center + 2 * Axe.SE

            let classic =
                classicpos,
                Barns.create
                      [ Axe.zero
                        Axe.N + Axe.NW
                        Axe.S + Axe.SE
                        2 * Axe.NE
                        2 * Axe.SW
                        2 * Axe.N + Axe.NE
                        2 * Axe.S + Axe.SW
                        Axe.E2 + Axe.SE
                        Axe.W2 + Axe.NW ]

            let xwing =
                classicpos,
                Barns.create
                      [ Axe.zero
                        Axe.NW
                        Axe.SW
                        Axe.NE
                        Axe.SE
                        3 * Axe.S
                        3 * Axe.N
                        Axe.S + 2 * Axe.SW
                        Axe.S + 2 * Axe.SE
                        Axe.N + 2 * Axe.NW
                        Axe.N + 2 * Axe.NE
                        Axe.W2 + Axe.SW
                        Axe.E2 + Axe.NE ]

            let windmill =
                classicpos,
                Barns.create
                      [ Axe.zero
                        Axe.N; 2 * Axe.N; 3 * Axe.N
                        Axe.S; 2 * Axe.S; 3 * Axe.S
                        Axe.NW;Axe.NW+Axe.SW;Axe.NW+2*Axe.SW
                        Axe.SE;Axe.SE+Axe.NE;Axe.SE+2*Axe.NE ]

    let shufflePlayers players parcels =
        let rand = System.Random()
        parcels
        |> List.sortBy (fun _ -> rand.Next())
        |> List.map2 (fun (c,u,n) p -> c,u,n,p) players
    let decide cmd (state: UndoableBoard) =
        match state.Board, cmd with
        | InitialState, Start cmd ->
            let players, barns =


                match cmd.Players with
                | [ _; _ ] ->
                    let (p1, p2), barns = Configurations.P2.star
                    
                    shufflePlayers cmd.Players [  p1; p2  ],
                      barns

                | [ _;_;_] ->
                    let (p1,p2,p3), barns = Configurations.P3.famine
                    shufflePlayers cmd.Players [ p1; p2; p3 ],
                      barns

                | [_;_;_;_] ->
                    let (p1,p2,p3,p4), barns = Configurations.P4.windmill
                    shufflePlayers cmd.Players [ p1; p2; p3; p4 ],
                      barns
                | _ ->
                    let playerCount = List.length cmd.Players
                    if playerCount < 2 then
                        failwith "Too few players"
                    else
                        failwith "Too many players"
            [ Started {
                Players = players
                DrawPile = DrawPile.shuffle cmd.UseGameOver DrawPile.cards
                Barns = barns 
                Goal = cmd.Goal
                Undo = cmd.Undo
                UseGameOver = cmd.UseGameOver} ] 
        | Board board, Play(playerId, Player.EndTurn) ->
            if board.Table.Player = playerId then
                let player = board.Players.[playerId]
                match player with
                | Playing p when not (Player.canMove (Some playerId) state.Board || Hand.shouldDiscard p.Hand ) -> 
                    let boardPos = History.createPos board

                    match  History.repetitions playerId boardPos board.History with
                    | n when n >= 2 -> [ GameEndedByRepetition ]
                    | 1 -> next state.ShouldShuffle true board
                    | _ -> next state.ShouldShuffle false board
                | _ -> []
            else
                []
        | Board board, Play(playerId, Player.Undo) ->
            if board.Table.Player = playerId && state.UndoType <> NoUndo && not state.AtUndoPoint then
                [ Played(playerId, Player.Undone )]
            else
                []
        | Board board, Play(playerId,(Player.Discard card as cmd))  ->
            if board.Table.Player = playerId then
                let player = board.Players.[playerId]
                let others = Player.otherPlayers playerId board
                let events =
                    Player.decide others board.Barns board.HayBales (fun() -> bribeParcels board) cmd player
                [ for e in events do
                    Played(playerId, e) ]
            else
                []
        | Board board,Play (playerid, cmd) ->
            let player = board.Players.[playerid]
            let others = Player.otherPlayers playerid board

            if playerid = board.Table.Player then
                (state, [])
                |> cont (fun board _ ->

                    let events = 
                        Player.decide others board.Barns board.HayBales (fun() -> bribeParcels board) cmd player
                    // return cards action
                    // and bonus effects
                    [ for e in events do
                        Played(playerid,e)

                      for e in events do
                        match e with
                        | Player.CardPlayed (PlayRut victim) -> 
                            Played(victim, Player.Rutted)
                        | Player.CardPlayed (PlayHayBale(added,removed)) ->
                            HayBalesPlaced(added, removed)
                        | Player.CardPlayed (PlayDynamite bale) ->
                            HayBaleDynamited bale
                        | _ -> ()
                    ])
                |> cont (fun board _ ->

                        let player = board.Players.[playerid]
                        match player with
                        | Playing player ->
                            match Player.fullAnnexation player.Field player.Fence player.Tractor with
                            | Some(surrounded, newLength) ->
                                if Field.isEmpty surrounded  then
                                    [ Played(playerid, Player.FenceReduced {NewLength = newLength })]
                                else
                                    let protectedField =
                                        Field.unionMany [
                                            for id,p in Map.toSeq  board.Players do
                                                match p with
                                                | Playing p when id <> playerid && p.Bonus.Watched ->
                                                    p.Field
                                                |_ -> () 
                                        ]

                                    let annexed = surrounded - protectedField

                                    let lostFields = 
                                        [ for id,p in Map.toSeq board.Players do
                                            match p with
                                            | Playing p when id <> playerid ->
                                                let lost = Field.intersect annexed p.Field
                                                if not (Field.isEmpty lost) then
                                                    id, Field.parcels lost
                                            |_ -> () ] 
        
                                    let annexedBarns = 
                                        board.Barns |> Barns.intersectWith annexed
        
                                    [ Played(playerid,
                                        Player.Annexed {
                                          NewField = Field.parcels annexed
                                          LostFields = lostFields
                                          FreeBarns = annexedBarns.Free |> Field.parcels
                                          OccupiedBarns = annexedBarns.Occupied |> Field.parcels
                                          FenceLength = newLength }) ]
        
                                    
                                    
                            | None -> []

                        | _ -> []
                )
                |> cont (fun board _ ->
                        // check wether some players were eliminated
                        [ for KeyValue(pid, p) in board.Players do
                           if Field.isEmpty (Player.field p) && not (Player.isKo p) then
                               Played(pid, Player.Eliminated)
                               UndoCheckPointed // an elimination cannot be undone 
                        ]
                        )
                |> cont (fun board _ ->
                    [
                    for pid, p in Map.toSeq board.Players do
                        match p with
                        | Playing ({ Power = Power.PowerDown } as player) ->
                            if Crossroad.isInField player.Field player.Tractor then
                                Played(pid, Player.PoweredUp)
                        | Playing ({ Power = Power.PowerUp} as player) ->
                            let start = Fence.start player.Tractor player.Fence
                            if not (Crossroad.isInField player.Field start) then
                                Played(playerid, Player.CutFence { Player = pid })
                                // If the player is in field (fallow land) while being cut
                                // they're reconnected instantly after losing fence
                                if Crossroad.isInField player.Field player.Tractor then
                                    Played(pid, Player.PoweredUp)
                        | _ -> () ])
                |> cont (fun board  _ ->
                        let remainingPlayers =
                            board.Players
                            |> Map.toSeq
                            |> Seq.choose(fun (pid,p) -> 
                                match p with
                                | Ko _ -> None
                                | _  -> Some pid)
                            |> Seq.toList

                        match remainingPlayers with
                        | [ winner] ->
                            // winner by ko
                            [ GameWon winner ]
                        | _ -> [] )
                |> cont (fun board es ->
                    match List.tryFind (function Played(_, Player.Annexed _) -> true | _ -> false) es with
                    | Some (Played (_, Player.Annexed e)) ->
                        let cardsToTake =
                            e.FreeBarns.Length + 2 * e.OccupiedBarns.Length
                        if cardsToTake > Hand.count board.DrawPile then
                            [ DiscardPileShuffled (DrawPile.shuffle board.UseGameOver board.DiscardPile) ]
                        else
                            []
                    | _ -> []
                )
                |> cont (fun board es ->
                            match tryFindWinner board with
                            | Winner winners ->
                                // winner because goal is reached
                                match winners with
                                | [ win ] -> [GameWon win]
                                | _ -> [ GameEnded winners ]
                            | Lead leads ->
                                match List.tryFind (function Played(_, Player.Annexed _) -> true | _ -> false) es with
                                | Some (Played (player, Player.Annexed e)) ->
                                    let cardsToTake =
                                        e.FreeBarns.Length + 2 * e.OccupiedBarns.Length
                                    [
                                        if cardsToTake > 0 then
                                            match board.DrawPile with
                                            | PublicHand drawPile ->
                                                    let cardsDrawn = drawPile |> DrawPile.take cardsToTake
                                                    PlayerDrewCards 
                                                        { Player = playerid
                                                          Cards = PublicHand cardsDrawn }

                                                    if List.contains GameOver cardsDrawn then
                                                        Played(playerid, Player.CardPlayed PlayCard.PlayGameOver)

                                                        match leads with
                                                        | [ win ] -> GameWon win
                                                        |  _ -> GameEnded leads

                                                    elif state.UndoType = DontUndoCards then
                                                        UndoCheckPointed
                                            | PrivateHand _ -> ()
                                    ]
                                | _ -> 
                                    let player = board.Players.[playerid]
                                    if playerid <> board.Table.Player then
                                        let nextPlayerId = board.Table.Player
                                        let nextPlayer = board.Players.[nextPlayerId]
                                        [ yield! Bonus.startTurn (Player.bonus nextPlayer)
                                                  |> List.map (fun c -> Played(nextPlayerId, Player.BonusDiscarded c))
                                          yield UndoCheckPointed ] 
                                    else
                                        []
                )
                |> fun (_,es) -> es
            else
                match cmd with
                | Player.Quit -> 
                    (state, [])
                    |> cont (fun board _ ->
                        let events = Player.decide others board.Barns board.HayBales (fun() -> bribeParcels board) cmd player
                        [ for e in events do
                            Played(playerid,e) ] )
                    |> cont (fun board _ ->
                       let remainingPlayers =
                           board.Players
                           |> Map.toSeq
                           |> Seq.choose(fun (pid,p) -> 
                               match p with
                               | Ko _ -> None
                               | _  -> Some pid)
                           |> Seq.toList

                       match remainingPlayers with
                       | [ winner] ->
                           // winner by ko
                           [ GameWon winner ] 
                       | _ -> []
                    )
                    |> fun (_,es) -> es

                | _ -> []

        | _ -> []

    let toState (board: Board) =
        match board with
        | Board board ->
            { SPlayers =
                board.Players
                |> Map.toSeq
                |> Seq.map (fun (playerid,p) -> playerid, Player.toState p)
                |> Seq.toArray

              STable = { SPlayers = board.Table.Players
                         SAllPlayers = board.Table.AllPlayers
                         SNames = [| for KeyValue(p,n) in board.Table.Names -> p,n |]
                         SCurrent = board.Table.Current }
              SDiscardPile = List.toArray board.DiscardPile
              SDrawPile = Some (Hand.count board.DrawPile)
              SFreeBarns = Field.parcels board.Barns.Free |> List.toArray
              SOccupiedBarns = Field.parcels board.Barns.Occupied |> List.toArray
              SHayBales = Set.toArray board.HayBales
              SGoal = board.Goal
              SWinner = null
              SWinners = [||]
              SUseGameOver = Some board.UseGameOver
              SHistory = [| for KeyValue(p,h) in board.History.PlayersHistory ->
                                    p, ( [| for boardpos in h -> [| for pos in boardpos.Positions -> pos.Player, pos.TractorPos, pos.FencePos, Field.parcels pos.FieldPos  |] |])
              |]
              }
        | InitialState -> 
            { SPlayers = [||]
              STable = { SPlayers = null; SAllPlayers = null; SNames = null; SCurrent = 0}
              SDiscardPile = [||]
              SDrawPile = None
              SFreeBarns = null
              SOccupiedBarns = null
              SHayBales = null
              SGoal = Common 0
              SWinner = null
              SWinners = [||]
              SUseGameOver = None
              SHistory = [||]
              }
        | Won(winners, board) ->
            { SPlayers =
                board.Players
                |> Map.toSeq
                |> Seq.map (fun (playerid,p) -> playerid, Player.toState p)
                |> Seq.toArray

              STable = { SPlayers = board.Table.Players
                         SAllPlayers = board.Table.AllPlayers
                         SNames = [| for KeyValue(p,n) in board.Table.Names -> p,n |]
                         SCurrent = board.Table.Current }
              SDiscardPile = List.toArray board.DiscardPile
              SDrawPile = Some (Hand.count board.DrawPile)
              SFreeBarns = Field.parcels board.Barns.Free |> List.toArray
              SOccupiedBarns = Field.parcels board.Barns.Occupied |> List.toArray
              SHayBales = Set.toArray board.HayBales
              SGoal = board.Goal
              SWinner = 
                match winners with
                | [ winner ] -> winner 
                | _ -> null
              SWinners = 
                match winners with
                | []
                | [_] -> [||]
                | _ -> List.toArray winners
              SUseGameOver = Some board.UseGameOver 
              SHistory = [||]
                }

    
    let toUndoState s =
        { SBoard = toState s.Board 
          SUndoPoint = toState s.UndoPoint
          SUndoType =
            match s.UndoType with
            | FullUndo -> "FullUndo"
            | DontUndoCards -> "DontUndoCards"
            | NoUndo -> "NoUndo"
          SShouldShuffle = s.ShouldShuffle
          SAtUndoPoint = s.AtUndoPoint }


    let ofState (board: BoardState) =
        match board.SPlayers with
        | [||] -> 
            InitialState
        | _ ->
            let state =
                  { Players = board.SPlayers |> Seq.map (fun (c,p) -> c, Player.ofState p) |> Map.ofSeq 
                    Table = { Players = board.STable.SPlayers
                              AllPlayers = board.STable.SAllPlayers
                              Names = Map.ofArray board.STable.SNames
                              Current = board.STable.SCurrent }
                    DrawPile = board.SDrawPile |> Option.defaultValue 0 |> PrivateHand
                    DiscardPile = Array.toList board.SDiscardPile
                    Barns = { Free = Field.ofParcels board.SFreeBarns
                              Occupied = Field.ofParcels board.SOccupiedBarns }
                    HayBales = set board.SHayBales
                    Goal = board.SGoal
                    UseGameOver = board.SUseGameOver |> Option.defaultValue false
                    History =
                        { PlayersHistory = Map.ofList
                                                [ for p, ph in board.SHistory ->
                                                    p, [ for h in ph ->  { Positions = set [ for pl,t,fence,field in h -> { Player = pl; TractorPos = t; FencePos = fence; FieldPos = Field.ofParcels field  } ]  } ]
                                                ]}
                    }
            match board.SWinner, board.SWinners with
            | null, null
            | null, [||] -> Board state
            | winner, [||]
            | winner, null -> Won([winner], state) 
            | _, winners -> Won(List.ofArray winners, state) 
        
    let ofUndoState s =
        { Board = ofState s.SBoard
          UndoPoint = ofState s.SUndoPoint
          UndoType = 
            match s.SUndoType with
            | "NoUndo" -> NoUndo
            | "DontUndoCards" -> DontUndoCards
            |  _ -> FullUndo 
          ShouldShuffle = s.SShouldShuffle
          AtUndoPoint = s.SAtUndoPoint}




module Client =
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
        | GameOver -> "card gameover"

/// A type that specifies the messages sent to the server from the client on Elmish.Bridge
/// to learn more, read about at https://github.com/Nhowka/Elmish.Bridge#shared
type ServerMsg =
    //| SyncState 
    | JoinGame of string
    | Command of Player.Command
    | SendMessage of  string

type ChatEntry =
    { Text: string
      Player: string
      Date: System.DateTime
    }
/// A type that specifies the messages sent to the client from the server on Elmish.Bridge
type ClientMsg =
    | Events of Board.Event list * int
    | Message of string
    | Sync of UndoBoardState * int * ChatEntry list
    | SyncPlayer of  string
    | ReceiveMessage of ChatEntry
