namespace Shared


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

type CrossroadSide = CLeft | CRight

[<Struct>]
type Crossroad = Crossroad of Axe * CrossroadSide

type BorderSide = BNW | BN | BNE

[<Struct>]
type Path = Path of Axe * BorderSide

type Direction = Up | Down | Horizontal

type Goal =
    | Common of int
    | Individual of int

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
    | Nitro of CardPower
    | Rut
    | HayBale of CardPower
    | Dynamite
    | HighVoltage
    | Watchdog
    | Helicopter
    | Bribe
and CardPower = One | Two

type PlayCard =
    | PlayNitro of CardPower
    | PlayRut of string
    | PlayHayBale of Path list
    | PlayDynamite of Path
    | PlayHighVoltage
    | PlayWatchdog
    | PlayHelicopter of Crossroad
    | PlayBribe of Parcel

module Card =
    let ofPlayCard =
        function
        | PlayNitro power -> Nitro power
        | PlayRut _ -> Rut
        | PlayHayBale cs -> if List.length cs < 2 then HayBale One else HayBale Two
        | PlayDynamite _ -> Dynamite
        | PlayHighVoltage -> HighVoltage
        | PlayWatchdog -> Watchdog
        | PlayHelicopter _ -> Helicopter
        | PlayBribe _ -> Bribe

type Hand =
    | Private of int
    | Public of Card list

module Hand =
    let empty = Private 0

    let toPrivate =
        function
        | Public p -> Private p.Length
        | priv -> priv

    let count =
        function
        | Public p -> p.Length
        | Private c -> c


    let contains card =
        function
        | Public p -> List.contains card p
        | Private _ -> false
       
    let remove card =
        function
        | Public p -> 
            let i = List.findIndex (fun c -> c = card) p
            let left, right = List.splitAt i p
            Public (left @ List.tail right)

        | Private p -> Private(p-1)

    let canPlay =
        function
        | Public p -> 
            p |> List.isEmpty |> not
        | Private p -> p > 0


type Player =
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
      Done: int}
and Bonus =
    { NitroOne: int
      NitroTwo: int
      Watched: bool
      HighVoltage: bool
      Rutted: int
      Heliported: int
      }



type Table =
    { Players: string[]
      AllPlayers: string[]
      Names: Map<string,string>
      Current: int }
    member this.Player = this.Players.[this.Current]

    member this.Next =
        { this with
            Current = (this.Current + 1) % this.Players.Length }

module Table =
    let start players =
        let allplayers = [| for p,_ in players -> p |]

        { Players = allplayers
          AllPlayers = allplayers
          Current = 0
          Names = Map.ofList players }

    let eliminate player table =
        { table with
            Players = Array.filter (fun p -> p <> player) table.Players }

    let isCurrent playerid (table:Table) =
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

type Board = 
    | InitialState
    | Board of PlayingBoard
    | Won of string * PlayingBoard
and PlayingBoard =
    { Players: Map<string, Player>
      Table: Table
      DrawPile: Card list
      DiscardPile: Card list
      Barns: Barns
      HayBales: Path Set
      Goal: Goal
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
      SFreeBarns: Parcel[]
      SOccupiedBarns: Parcel[]
      SHayBales: Path[]
      SGoal: Goal
      SWinner: string
    }
and STable =
    { SPlayers: string[]
      SAllPlayers: string[]
      SNames: (string*string)[]
      SCurrent: int }
    


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

    let isOnBoard (Parcel p) =
        let x,y,z = Axe.cube p
        abs x <= 3 && abs y <= 3 && abs z <= 3

    let neighbors (Parcel p) =
        [ Parcel (p + Axe.N)
          Parcel (p + Axe.NE)
          Parcel (p + Axe.SE)
          Parcel (p + Axe.S)
          Parcel (p + Axe.SW)
          Parcel (p + Axe.NW) ]
        |> List.filter isOnBoard


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


    let interesect (Field x) (Field y) =
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

    let borderTiles (Field parcels) =
        (parcels
         |> Seq.collect Parcel.neighbors
         |> set)
        - parcels
        |> Field

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

        
        
module Barns =
    let empty = { Free = Field.empty; Occupied = Field.empty }

    let intersectWith (field: Field) barns =
        { Free = Field.interesect field barns.Free
          Occupied = Field.interesect field barns.Occupied }

    let init barns =
        { Free = Field.ofParcels barns
          Occupied = Field.empty }



    let annex annexed barns =
        { Free = barns.Free - annexed.Free
          Occupied = barns.Occupied + (Field.interesect barns.Free annexed.Free) }





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
          Done = 0}

    let startTurn fence bonus =
        { Capacity = 
            let baseMoves = 
                if Fence.givesAcceleration fence then
                    4
                else
                    3
            baseMoves + Bonus.moveCapacityChange bonus
          Done = 0 }

    let canMove m =
        m.Done < m.Capacity

    let addCapacity n m =
        { m with 
            Capacity = min (m.Capacity + n)  5 }

    let doMove m =
        { m with Done = m.Done + 1}

module Player =
    type Command =
        | Start of Start
        | SelectFirstCrossroad of SelectFirstCrossroad
        | Move of Move
        | PlayCard of PlayCard
        | EndTurn
    and Start =
        { Parcel: Parcel }
    and SelectFirstCrossroad =
        { Crossroad: Crossroad }
    and Move =
        { Direction: Direction
          Destination: Crossroad }

    type Event =
        | FirstCrossroadSelected of FirstCrossroadSelected
        | FenceDrawn of Moved
        | FenceRemoved of Moved
        | FenceLooped of FenceLooped
        | MovedInField of Moved
        | MovedPowerless of Moved
        | Annexed of Annexed
        | CutFence of CutFence
        | PoweredUp
        | CardPlayed of PlayCard
        | SpedUp of SpedUp
        | Rutted
        | HighVoltaged
        | BonusDiscarded of Card
        | Watched
        | Heliported of Crossroad
        | Bribed of Bribed
        | Eliminated
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
           LostFields: (string * Parcel list) list
           FreeBarns: Parcel list
           OccupiedBarns: Parcel list
        }
    and CutFence = 
        { Player: string }
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

        
    let annexation field fence tractor =
        let border = Field.borderBetween (Fence.start tractor fence) tractor field
        let fullBorder = 
            let (Fence paths) = fence
            paths @ border 
        Field.fill fullBorder - field
                    

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


    let watchedField player =
        match player with
        | Playing ({ Bonus = { Watched = true }; Field = field }) -> field
        | _ -> Field.empty

    let decide (otherPlayers: (string * Player) list) barns command player =
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
                              if endInField && not pathInField && not inFallow then
                                let nextFence = Fence.add (nextPath, dir) player.Fence
                                let baseAnnexed = 
                                    annexation player.Field nextFence nextPos

                                let annexed = 
                                    otherPlayers
                                    |> List.fold (fun anx (_,p) ->
                                        anx - watchedField p) baseAnnexed

                                let lostFields = 
                                    [ for color,p in otherPlayers do
                                        match p with
                                        | Playing p ->
                                            let lost = Field.interesect annexed p.Field
                                            if not (Field.isEmpty lost) then
                                                color, Field.parcels lost
                                        |_ -> ()
                                    ] 

                                let annexedBarns = 
                                    barns |> Barns.intersectWith annexed

                                Annexed {
                                    NewField = Field.parcels annexed
                                    LostFields = lostFields
                                    FreeBarns = annexedBarns.Free |> Field.parcels
                                    OccupiedBarns = annexedBarns.Occupied |> Field.parcels }

                                for playerid, p in otherPlayers do
                                    match p with
                                    | Playing p ->
                                        if not (Fence.isEmpty p.Fence) then
                                            let start = Fence.start p.Tractor p.Fence
                                            if Crossroad.isInField annexed start then
                                                CutFence { Player = playerid }
                                        else
                                            let remainingField = p.Field - annexed
                                            if Crossroad.isInField annexed p.Tractor && not (Crossroad.isInField remainingField p.Tractor) then
                                                CutFence { Player = playerid }
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
        | Playing player, PlayCard card ->
            let c = Card.ofPlayCard card
            if Hand.contains c player.Hand then
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
                    [  CardPlayed card
                       Heliported crossroad
                       if player.Power = PowerDown && Crossroad.isInField player.Field crossroad then
                            PoweredUp
                       ]
                | PlayHayBale _ 
                | PlayDynamite _ ->
                    [ CardPlayed card
                      BonusDiscarded (Card.ofPlayCard card) ]
                | PlayBribe parcel ->

                    [ CardPlayed card
                      for playerId, player in otherPlayers do
                        if field player |> Field.containsParcel parcel then
                          Bribed { Parcel = parcel; Victim = playerId }
                      BonusDiscarded (Card.ofPlayCard card) ]


            else 
                []

                    
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
        | Playing player, PoweredUp -> Playing { player with Power = PowerUp }
        | Playing player, Annexed e -> Playing { player with Fence = Fence.empty; Field = player.Field + Field.ofParcels e.NewField}
        | Playing player, HighVoltaged -> Playing { player with Bonus = { player.Bonus with  HighVoltage = true}}
        | Playing player, Watched -> Playing { player with Bonus = { player.Bonus with  Watched = true }}
        | Playing player, Rutted -> Playing { player with Bonus = { player.Bonus with  Rutted = player.Bonus.Rutted + 1 }}
        | Playing player, SpedUp e -> Playing { player with Moves = player.Moves |> Moves.addCapacity e.Speed }
        | Playing player, Heliported e -> Playing { player with Tractor = e; Bonus = { player.Bonus with Heliported = player.Bonus.Heliported + 1}  }
        | Playing player, Bribed p -> Playing { player with Field = player.Field + Field.ofParcels [p.Parcel] }

        | Playing player, CardPlayed card ->
            Playing { player with
                        Hand = 
                            player.Hand 
                            |> Hand.remove (Card.ofPlayCard card) }

        |  Playing player, BonusDiscarded e ->
            Playing { player with
                        Bonus = player.Bonus |> Bonus.discard e }
        | Playing player, Eliminated ->
            Ko player.Color
        | _ -> player 



    let exec otherPlayers barns cmd state =
        state
        |> decide otherPlayers barns cmd
        |> List.fold evolve state

    let move dir player =
        match player with
        | Playing p ->
            player
            |> exec [] Barns.empty (Move {Direction = dir; Destination = p.Tractor })
        | _ -> failwith "Not playing"

    let start color parcel pos =
        Starting  { Parcel = parcel; Color = color; Hand = Public []; Bonus = Bonus.empty  }
        |> exec [] Barns.empty (SelectFirstCrossroad { Crossroad = pos})


    let possibleMove player dir =
        let pos = Crossroad.neighbor dir player.Tractor
        if Crossroad.isOnBoard pos then
            [ dir, Ok pos]
        else
            []

    let possibleMoves player =
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

    let takeCards cards player =
        match player with
        | Playing p ->
            Playing 
                { p with 
                    Hand = 
                        match p.Hand, cards with
                        | Public h, Public c -> Public (h @ c)
                        | Private h, Private c -> Private (h + c)  
                        | _ -> failwith "Unexpected mix" }
        | Starting p ->
            Starting
                { p with
                    Hand = 
                        match p.Hand, cards with
                        | Public h, Public c -> Public (h @ c)
                        | Private h, Private c -> Private (h + c)  
                        | _ -> failwith "Unexpected mix" }
        | Ko _ -> player



    let toState (p: Player) =
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


    let shuffle cards =
        let rand = System.Random()
        List.sortBy (fun _ -> rand.Next()) cards
        //List.sortBy (function
        //    | Helicopter -> System.Int32.MinValue
        //    | _ -> rand.Next()) cards


    let remove cards pile =
        let count = Hand.count cards
        pile |> List.skip (min (List.length pile) count)

    let take count pile =
        pile |> List.truncate count

module Board =
    let initialState = InitialState

    type Command =
        | Play of string * Player.Command
        | Start of Start
    and Start =
        { Players: (Color * string * string) list }

    type Event =
        | Played of string * Player.Event
        | Started of Started
        | Next
        | PlayerDrewCards of PlayerDrewCards
        | GameWon of string
        | HayBalesPlaced of Path list
        | HayBaleDynamited of Path
    and Started =
        { Players: (Color*string*string*Parcel) list 
          DrawPile: Card list
          Barns: Parcel list
          Goal: Goal
        }
    and PlayerDrewCards =
        { Player: string 
          Cards: Hand }
        
    let currentPlayer (board: PlayingBoard) =
        board.Players.[board.Table.Player]

    let otherPlayers playerid (board: PlayingBoard) =
        board.Players
        |> Map.toSeq
        |> Seq.filter (fun (id,_) -> id <> playerid)
        |> Seq.toList

    let currentOtherPlayers board =
        otherPlayers board.Table.Player board

    let totalSize (board: PlayingBoard) = 
       let players = board.Players
       players
       |> Map.fold (fun count _ p -> count + Player.fieldTotalSize p) 0


    let endGameWithBribe (board: PlayingBoard) =
        match board.Goal with
        | Common goal ->
            totalSize board + 1>= goal
        | Individual goal ->
            let player = currentPlayer board
            Player.fieldTotalSize player + 1 >= goal

    let tryFindWinner (board: PlayingBoard) =
        let players = board.Players
        match board.Goal with
        | Common goal ->
            if totalSize board >= goal then
                players
                |> Map.toList
                |> List.maxBy(fun (_,p)-> Player.fieldTotalSize p)
                |> Some
            else
                None
        | Individual goal ->
            players
            |> Map.toList
            |> List.tryFind (fun (_, p) -> Player.fieldTotalSize p >= goal)


    let next state =
        let playerId = state.Table.Player
        let player = state.Players.[playerId]
        let nextPlayerId = state.Table.Next.Player 
        let nextPlayer = state.Players.[nextPlayerId]
        [ yield! 
           Bonus.endTurn (Player.bonus player)
           |> List.map (fun c -> Played(playerId, Player.BonusDiscarded c))
          yield Next
          yield! 
           Bonus.startTurn (Player.bonus nextPlayer)
           |> List.map (fun c -> Played(nextPlayerId, Player.BonusDiscarded c))
                       ]
    type BribeBocker =
        | InstantVictory
        | NoParcelsToBribe


    let bribeParcels (board: PlayingBoard) =
        if  endGameWithBribe board then
            Error InstantVictory
        else
            let player = board.Players.[board.Table.Player]
            let border = Field.borderTiles (Player.field player) 
            let barns = board.Barns.Free + board.Barns.Occupied

            let otherPlayersFields =
                 currentOtherPlayers board
                 |> List.map (fun (_, player) -> 
                        let field = Player.field player
                        if Field.size field = 1 then
                            Field.empty
                        else
                            match player with
                            | Playing p ->
                                let startCrossRoad = Fence.start p.Tractor p.Fence
                                let startTiles = 
                                    Crossroad.neighborTiles startCrossRoad
                                    |> Field.ofParcels
                                field - startTiles
                            | Starting _ 
                            | Ko _ -> field
                                )
                 |> Field.unionMany
            

            let parcelsToBribe = (Field.interesect border otherPlayersFields) - barns
            if Field.isEmpty parcelsToBribe then
                Error NoParcelsToBribe
            else
                Ok parcelsToBribe
                    
        


    let annexed playerid e (board: PlayingBoard) =
        let annexedPlayer = Player.evolve board.Players.[playerid] (Player.Annexed e)
        let newMap = Map.add playerid annexedPlayer board.Players
        e.LostFields
        |> List.fold (fun map (playerid, parcels) ->
            match board.Players.[playerid] with
            | Playing p ->
                let newP = Playing { p with Field = p.Field - Field.ofParcels parcels }
                { board with
                            Players = Map.add playerid newP map.Players }
            | _ -> map
        ) { board with Players = newMap
                       Barns =
                           let annexedBarns =
                               { Free = e.FreeBarns |> Field.ofParcels
                                 Occupied = e.OccupiedBarns |> Field.ofParcels }
                           Barns.annex annexedBarns board.Barns }


    let evolve (state: Board) event =
        match state,event with
        | InitialState, Started s ->
            Board 
                { Players =
                    Map.ofList [ for c,u,n,p in s.Players -> u, Starting { Color = c; Parcel = p; Hand = Public []; Bonus = Bonus.empty}]
                  Table =  Table.start [ for _,p,n,_ in s.Players -> p,n ] 
                  DrawPile = s.DrawPile
                  DiscardPile = []
                  Barns = Barns.init s.Barns
                  HayBales = Set.empty
                  Goal = s.Goal }
        | InitialState, _ -> state
        | Board _, Started _ -> state
        | Board board, GameWon player -> Won(player, board)
        | Board board, Played (_, Player.CutFence { Player = playerid }) ->
            match board.Players.[playerid] with
            | Playing player -> 
                let cutPlayer =
                    Playing { player with
                                Fence = Fence.empty
                                Power = PowerDown }
                Board { board with
                            Players = Map.add playerid cutPlayer board.Players }
            | _ -> state

        | Board board, Played (playerid, Player.Annexed e ) ->
            annexed playerid e board
            |> Board
        | Board board, PlayerDrewCards e ->
            let newDrawPile = board.DrawPile |> DrawPile.remove e.Cards
            let player = board.Players.[e.Player] |> Player.takeCards e.Cards
            Board {
                board with
                    Players = Map.add e.Player player board.Players
                    DrawPile = newDrawPile }

        | Board board, HayBalesPlaced p ->
            Board {
                board with
                    HayBales = board.HayBales + set p }
        | Board board, HayBaleDynamited p ->
            Board {
                board with
                    HayBales = board.HayBales |> Set.remove p }
        | Board board, Played(playerid,(Player.Bribed p as e)) ->
            let newPlayer = Player.evolve board.Players.[playerid] e
            let newVictim = 
                match board.Players.[p.Victim] with
                | Starting p -> Starting p
                | Playing victim -> Playing { victim with Field = victim.Field - Field.ofParcels [p.Parcel] }
                | Ko _ as p -> p
            Board {
                board with
                    Players =
                        board.Players
                        |> Map.add playerid newPlayer
                        |> Map.add p.Victim newVictim 
            }
        | Board board, Played(playerid,(Player.Eliminated as e)) ->
            let newPlayer = Player.evolve board.Players.[playerid] e
            let newTable = Table.eliminate playerid board.Table
            Board { board with
                        Players = Map.add playerid newPlayer board.Players
                        Table = newTable
                    }

        | Board board, Played (playerid,e) ->
            let player = Player.evolve board.Players.[playerid] e
            let newDiscardPile =
                match e with
                | Player.BonusDiscarded card ->
                    card :: board.DiscardPile
                | _ -> board.DiscardPile

            Board { board with
                       Players = Map.add playerid player board.Players
                       DiscardPile = newDiscardPile }
        | Board board, Next ->
            let nextTable = board.Table.Next
            let player = Player.startTurn board.Players.[nextTable.Player]
            Board {
                board with
                    Players = Map.add nextTable.Player player board.Players
                    Table = nextTable}
        | Won _, _ -> state


    let decide cmd (state: Board) =
        
        match state, cmd with
        | InitialState, Start cmd ->
            let players, barns, goal =
                match cmd.Players with
                | [ c1,u1,n1; c2,u2,n2 ] ->
                    [ c1, u1, n1, Parcel.center + 2 * Axe.N 
                      c2, u2, n2, Parcel.center + 2 * Axe.S  ],
                      [ Parcel.center
                        Parcel.center + 3 * Axe.N 
                        Parcel.center + 3 * Axe.S 
                        Parcel.center + 3 * Axe.NE 
                        Parcel.center + 3 * Axe.NW 
                        Parcel.center + 3 * Axe.SE 
                        Parcel.center + 3 * Axe.SW 
                        Parcel.center + Axe.W2
                        Parcel.center + Axe.E2
                        Parcel.center + Axe.N + Axe.NE
                        Parcel.center + Axe.N + Axe.NW
                        Parcel.center + Axe.S + Axe.SE
                        Parcel.center + Axe.S + Axe.SW
                    ], Goal.Common 27


                | [ c1,u1,n1; c2,u2,n2; c3,u3,n3 ] ->
                    [ c1, u1, n1, Parcel.center + 2 * Axe.N
                      c2, u2, n2, Parcel.center + 2 * Axe.SW
                      c3, u3, n3, Parcel.center + 2 * Axe.SE ],
                        [ Parcel.center
                          Parcel.center + 3 * Axe.N 
                          Parcel.center + 3 * Axe.S 
                          Parcel.center + 3 * Axe.NE 
                          Parcel.center + 3 * Axe.NW 
                          Parcel.center + 3 * Axe.SE 
                          Parcel.center + 3 * Axe.SW 
                          Parcel.center + Axe.W2
                          Parcel.center + Axe.E2
                          Parcel.center + Axe.N + Axe.NE
                          Parcel.center + Axe.N + Axe.NW
                          Parcel.center + Axe.S + Axe.SE
                          Parcel.center + Axe.S + Axe.SW ],
                          Goal.Individual 11

                | [ c1,u1,n1; c2,u2,n2; c3,u3,n3; c4,u4,n4 ] ->
                    [ c1, u1, n1, Parcel.center + Axe.N + Axe.NE
                      c2, u2, n2, Parcel.center + 2 * Axe.NW
                      c3, u3, n3, Parcel.center + Axe.SW + Axe.S
                      c4, u4, n4, Parcel.center + 2 * Axe.SE ],
                      [  Parcel.center
                         Parcel.center + Axe.N + Axe.NW
                         Parcel.center + Axe.S + Axe.SE
                         Parcel.center + 2 * Axe.NE
                         Parcel.center + 2 * Axe.SW
                         Parcel.center + 2 * Axe.N + Axe.NE
                         Parcel.center + 2 * Axe.S + Axe.SW
                         Parcel.center + Axe.E2 + Axe.SE
                         Parcel.center + Axe.W2 + Axe.NW ],
                         Goal.Individual 9
                | _ ->
                    let playerCount = List.length cmd.Players
                    if playerCount < 2 then
                        failwith "To few players"
                    else
                        failwith "To many players"
            [ Started {
                Players = players
                DrawPile = DrawPile.shuffle DrawPile.cards
                Barns = barns 
                Goal = goal } ] 
        | Board state, Play(playerId, Player.EndTurn) ->
            if state.Table.Player = playerId then
                let player = state.Players.[playerId]
                match player with
                | Playing p when not (Moves.canMove p.Moves) -> 
                    next state
                | _ -> []
            else
                []
            
        | Board state,Play (playerid, cmd) ->
            let player = state.Players.[playerid]
            let others = otherPlayers playerid state

            if playerid = state.Table.Player then
                let events = 
                    Player.decide others state.Barns cmd player



                [ for e in events do
                    Played(playerid,e)

                  for e in events do
                    match e with
                    | Player.CardPlayed (PlayRut victim) -> 
                        Played(victim, Player.Rutted)
                    | Player.CardPlayed (PlayHayBale bales) ->
                        HayBalesPlaced bales
                    | Player.CardPlayed (PlayDynamite bale) ->
                        HayBaleDynamited bale
                    | _ -> ()
                  
                  let nextState = List.fold Player.evolve player events

                  match List.tryFind (function Player.Annexed _ -> true | _ -> false) events with
                  | Some (Player.Annexed e) ->
                        let nextBoard = 
                            { state with Players = Map.add playerid nextState state.Players }
                            |> annexed playerid e

                        let mutable eliminated = 0
                        for KeyValue(pid, p) in nextBoard.Players do
                            
                            if Player.isKo p then   
                                eliminated <- eliminated + 1
                            elif Field.isEmpty (Player.field p) then
                                eliminated <- eliminated + 1
                                Played(pid, Player.Eliminated)

                        if eliminated >= Map.count nextBoard.Players - 1 then
                            // winner by ko
                            GameWon playerid

                        else
                            match tryFindWinner nextBoard with
                            | Some (winner,_) ->
                                GameWon winner
                            | _ ->

                                let cardsToTake =
                                    e.FreeBarns.Length + 2 * e.OccupiedBarns.Length
                                if cardsToTake > 0 then
                                    PlayerDrewCards 
                                        { Player = playerid
                                          Cards = Public (state.DrawPile |> DrawPile.take cardsToTake) }
                                else
                                    match nextState with
                                    | Playing p when not (Moves.canMove p.Moves || Hand.canPlay p.Hand) ->
                                        yield! next state 
                                    | _ -> ()
                  | _ -> 
                      match nextState with
                      | Playing p when not (Moves.canMove p.Moves || Hand.canPlay p.Hand) ->
                              yield! next state 
                      | _ -> ()
                ]
            else
                []
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
              SFreeBarns = Field.parcels board.Barns.Free |> List.toArray
              SOccupiedBarns = Field.parcels board.Barns.Occupied |> List.toArray
              SHayBales = Set.toArray board.HayBales
              SGoal = board.Goal
              SWinner = null
                         }
        | InitialState -> 
            { SPlayers = [||]
              STable = { SPlayers = null; SAllPlayers = null; SNames = null; SCurrent = 0}
              SDiscardPile = [||]
              SFreeBarns = null
              SOccupiedBarns = null
              SHayBales = null
              SGoal = Common 0
              SWinner = null }
        | Won(player, board) ->
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
              SFreeBarns = Field.parcels board.Barns.Free |> List.toArray
              SOccupiedBarns = Field.parcels board.Barns.Occupied |> List.toArray
              SHayBales = Set.toArray board.HayBales
              SGoal = board.Goal
              SWinner = player }



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
                    DrawPile = [] 
                    DiscardPile = Array.toList board.SDiscardPile
                    Barns = { Free = Field.ofParcels board.SFreeBarns
                              Occupied = Field.ofParcels board.SOccupiedBarns }
                    HayBales = set board.SHayBales
                    Goal = board.SGoal }
            match board.SWinner with
            | null -> Board state
            | winner -> Won(winner, state) 
        

    let possibleMoves playerid (board: Board) =
        match board, playerid with
        | Board board, Some playerid ->
            match Map.tryFind playerid board.Players with
            | Some ((Playing p) as player)->
                let otherPlayers =
                    otherPlayers playerid board
                    |> List.map snd
                let check = 
                    Player.checkMove (Player.bonus player)


                    
                [ 
                    for dir,m in Player.possibleMoves player do
                        let path = Path.neighbor dir p.Tractor
                        if Set.contains path board.HayBales then
                            let c = Crossroad.neighbor dir p.Tractor
                            ImpossibleMove(dir, c, MoveBlocker.HayBaleOnPath)
                        else
                            match Seq.fold (fun c p -> Player.bindMove (check p) c) m otherPlayers with
                            | Ok c -> Move(dir, c)
                            | Error (c,e) -> ImpossibleMove(dir, c, e) ]
            | Some (Starting { Parcel = Parcel p}) ->
                [ SelectCrossroad (Crossroad (p, CLeft))
                  SelectCrossroad (Crossroad (p,CRight))
                  SelectCrossroad (Crossroad (p+Axe.NW, CRight))
                  SelectCrossroad (Crossroad (p+Axe.NE, CLeft))
                  SelectCrossroad (Crossroad (p+Axe.SW, CRight))
                  SelectCrossroad (Crossroad (p+Axe.SE, CLeft)) ]
            | Some (Ko _)
            | None -> []
        | _ -> []

/// A type that specifies the messages sent to the server from the client on Elmish.Bridge
/// to learn more, read about at https://github.com/Nhowka/Elmish.Bridge#shared
type ServerMsg =
    //| SyncState 
    | JoinGame of string
    | Command of Player.Command

/// A type that specifies the messages sent to the client from the server on Elmish.Bridge
type ClientMsg =
    | Events of Board.Event list * int
    | Message of string
    | Sync of BoardState * int
    | SyncPlayer of  string



            

            

        


