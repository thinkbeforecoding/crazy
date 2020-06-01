open System
open System.IO
open System.Threading.Tasks

open Microsoft.AspNetCore
open Microsoft.AspNetCore.Builder
open Microsoft.AspNetCore.Hosting
open Microsoft.Extensions.DependencyInjection

open FSharp.Control.Tasks.V2
open Giraffe
open Shared
open SharedServer

open Elmish
open Elmish.Bridge
open Microsoft.Extensions.Configuration
open JWT.Builder
open JWT.Algorithms
open FSharp.Data.UnitSystems.SI.UnitSymbols

let tryGetEnv = System.Environment.GetEnvironmentVariable >> function null | "" -> None | x -> Some x


printfn "Starting instance: %s" Environment.MachineName

type ServerConfig =
    { Domain: string
      Secure: bool
      BaseUri: string
      Cosmos: string
      JWTSecret: string
    }

[<CLIMutable>]
type JwtClaim = { sub: string
                  nickname: string }

let config =
    ConfigurationBuilder()
        .SetBasePath(Directory.GetCurrentDirectory())
        .AddJsonFile("local.settings.json",true)
        .AddEnvironmentVariables()
        .Build();
    
let serverConfig =
    let server = config.GetSection("server")
    let domain = server.["domain"]
    let secure = Boolean.Parse server.["secure"]
    let port = server.["port"] 
    { Domain = domain
      Secure = secure
      BaseUri = 
        if secure then
            "https://" + domain + (if port <> "" then ":" + port else "")
        else
            "http://" + domain + (if port <> "" then ":" + port else "")
            
      Cosmos = config.GetConnectionString("COSMOS")
      JWTSecret = server.["JWTSecret"]
      }

let dev = config.["dev"] |> Boolean.TryParse |> function true, v -> v | _ -> false
    
   
let containerName = 
    if dev then "crazydev" else "crazy"

let publicPath =
    "SERVER_ROOT"
    |> tryGetEnv
    |> Option.orElseWith ( fun () ->
            let p =Path.GetFullPath "./public"
            if Directory.Exists p then
                Some p
            else
                None )
    |> Option.defaultValue (Path.GetFullPath "../Game/public")
let port =
    "SERVER_PORT"
    |> tryGetEnv |> Option.map uint16 |> Option.defaultValue 8085us
type PlayerState =
    | NotConnected
    | Connected of Connected
and Connected = 
    { GameId: string
      Color: Color option
      Player: string }

let connections =
  ServerHub<PlayerState,ServerMsg,ClientMsg>()


type GameRunnerCmd =
    | GetState of ((UndoableBoard * int * ChatEntry list) option -> unit)
    | Exec of Board.Command * (Board.Event list -> unit)
    | WriteChat of string*string*(unit->unit)

open Newtonsoft.Json.Linq
let (|JObj|_|) (o: obj) : 'e option =
    match o with
    | :? JObject as j -> Some (j.ToObject<'e>())
    | :? JArray as j -> Some (j.ToObject<'e>())
    | :? string as s -> Some (unbox s)
    | _ -> None


module Dto = 
    let ofCard =
        function
        | Nitro One -> "Nitro1"
        | Nitro Two -> "Nitro2"
        | Rut -> "Rut"
        | HayBale One -> "HayBale1"
        | HayBale Two -> "HayBale2"
        | Dynamite -> "Dynamite"
        | HighVoltage -> "HighVoltage"
        | Watchdog -> "Watchdog"
        | Helicopter -> "Helicopter"
        | Bribe -> "Bribe"

    let toCard =
        function
        | "Nitro1" -> Nitro One
        | "Nitro2" -> Nitro Two
        | "Rut" -> Rut
        | "HayBale1" -> HayBale One
        | "HayBale2" -> HayBale Two
        | "Dynamite" -> Dynamite
        | "HighVoltage" -> HighVoltage
        | "Watchdog" -> Watchdog
        | "Helicopter" -> Helicopter
        | "Bribe" -> Bribe
        | _ -> failwith "Unknown card"
        
    type ParcelDto = { q: int; r: int }

    let ofParcel (Parcel(Axe(q,r)))= { q = q; r = r}
    let toParcel p = Parcel(Axe(p.q, p.r))

    let ofParcels = Seq.map ofParcel >> Seq.toArray
    let toParcels = Seq.map toParcel >> Seq.toList

    type CrossroadDto = {q: int; r: int; side: string}

    let ofCrossroad (Crossroad(Axe(q,r), side)) =
        { q = q; r = r; side = match side with CLeft -> "Left" | CRight -> "Right" }

    let toCrossroad c =
        Crossroad(Axe(c.q,c.r), match c.side with "Left" -> CLeft | "Right" -> CRight | _ -> failwithf "Unknwon side" )

    type PathDto = {q: int; r: int; border: string}

    let ofPath (Path(Axe(q,r), border)) =
        { q = q; r = r; border = match border with BNW -> "NW" | BN -> "N" | BNE -> "NE" }

    let toPath c =
        Path(Axe(c.q,c.r), match c.border with "NW" -> BNW | "N" -> BN  | "NE" -> BNE | _ -> failwithf "Unknwon border" )



    let ofColor =
        function
        | Blue -> "Blue"
        | Yellow -> "Yellow"
        | Purple -> "Purple"
        | Red -> "Red"

    let toColor =
        function
        | "Blue" -> Blue
        | "Yellow" -> Yellow
        | "Purple" -> Purple
        | "Red" -> Red
        | _ -> failwith "Unknown color"

    type PlayerDto =
        { Color: string 
          Id: string
          Name: string
          Parcel: ParcelDto}

    let ofPlayer (color, id, name, p) = { Color = ofColor color; Id = id; Name = name; Parcel = ofParcel p}
    let toPlayer p = toColor p.Color, p.Id, p.Name, toParcel p.Parcel

    type StartedDto =
        { Players: PlayerDto[]
          Barns: Parcel[]
          DrawPile: string[];
          CommonGoal: bool
          Goal: int
          Undo: string
         }

    let ofUndoType =
        function
        | FullUndo -> "FullUndo"
        | DontUndoCards -> "DontUndoCards"
        | NoUndo -> "NoUndo"


    let toUndoType =
        function
        | "DontUndoCards" -> DontUndoCards
        | "NoUndo" -> NoUndo
        | _  -> FullUndo

    let ofDirection =
        function
        | Up -> "Up"
        | Down -> "Down"
        | Horizontal -> "Horizontal"

    let toDirection =
        function
        | "Up" -> Up
        | "Down" -> Down
        | "Horizontal" -> Horizontal
        | _ -> failwith "Unknown direction"

    type MovedDto =
        { Move: string
          Crossroad: CrossroadDto
          Path: PathDto }

    let ofMoved (e: Player.Moved) =
       { Move = ofDirection e.Move
         Crossroad = ofCrossroad e.Crossroad
         Path = ofPath e.Path } 

    let toMoved (e: MovedDto) =
       { Player.Moved.Move = toDirection e.Move
         Player.Moved.Crossroad = toCrossroad e.Crossroad
         Player.Moved.Path = toPath e.Path } 

    type FirstCrossroadSelectedDto =
        { Crossroad: CrossroadDto }

    type FenceReducedDto =
        { NewLength: int }

    let ofFenceReduced (e: Player.FenceReduced) =
        { NewLength = e.NewLength }

    let toFenceReduced (e: FenceReducedDto) =
        { Player.FenceReduced.NewLength = e.NewLength }

    type AnnexedDto =
        { NewField: ParcelDto[]
          LostFields: LostFieldDto[]
          FreeBarns: ParcelDto[]
          OccupiedBarns: ParcelDto[]
          FenceLength: int option }
    and LostFieldDto = 
        { Player: string 
          Field: ParcelDto[]
          }

    let ofLostField (p, field) =
        { Player = p
          Field = ofParcels field }
    let toLostField f =
        f.Player, toParcels f.Field


    type CutFenceDto =
        { Player: string }

    type FencePathDto = {
        Path: PathDto
        Direction: string
    }
    type FenceLoopedDto =
        { Move: string 
          Loop: FencePathDto[]
          Crossroad: CrossroadDto }

    let ofFence (Fence(paths)) =
        paths |> Seq.map(fun (p,d) -> { Path = ofPath p; Direction = ofDirection d }) |> Seq.toArray

    let toFence (paths: FencePathDto[]) =
        paths |> Seq.map (fun p -> toPath p.Path, toDirection p.Direction) |> Seq.toList |> Fence

    type PlayCardDto =
        { Card: string 
          Effect: obj }
    type HayBalesDto =
        { Added: PathDto[]
          Removed: PathDto[] }

    let ofPlayCard (cp: PlayCard) =
        { Card = Card.ofPlayCard cp |> ofCard
          Effect = 
            match cp with
            | PlayNitro _ -> null
            | PlayRut v -> v |> box
            | PlayHayBale(added,removed) -> { Added = added |> Seq.map ofPath |> Seq.toArray
                                              Removed = removed |> Seq.map ofPath |> Seq.toArray }  |> box
            | PlayDynamite p -> ofPath p |> box
            | PlayHighVoltage -> null
            | PlayWatchdog -> null
            | PlayHelicopter c -> ofCrossroad c |> box
            | PlayBribe p -> ofParcel p |> box
        }

    let toPlayCard (cp: PlayCardDto) =
        match toCard cp.Card with
        | Nitro p -> PlayNitro p
        | Rut -> PlayRut (unbox<string> cp.Effect)
        | HayBale _ -> 
            match cp.Effect with
            | :? JArray as a ->
                PlayHayBale ((a.ToObject<_[]>())|> Seq.map toPath |> Seq.toList, [] )
            | :? JObject as o ->
                let hb : HayBalesDto = o.ToObject()
                PlayHayBale (hb.Added |> Seq.map toPath |> Seq.toList,
                             hb.Removed |> Seq.map toPath |> Seq.toList )
            | _ -> failwith "Unknow haybale format"  

        | Dynamite -> PlayDynamite(toPath ((cp.Effect :?> JObject).ToObject()))
        | HighVoltage -> PlayHighVoltage
        | Watchdog -> PlayWatchdog
        | Helicopter -> PlayHelicopter(toCrossroad ((cp.Effect :?> JObject).ToObject()))
        | Bribe -> PlayBribe(toParcel ((cp.Effect :?> JObject).ToObject()))
        
    type SpedUpDto = { Speed: int }

    type HeliportedDto = { Destination:  CrossroadDto }

    type BribedDto = { Parcel: ParcelDto; Victim: string }

    type PlayerDrewCardsDto =
        { Player: string
          Cards: obj }

    type HandDto =
        { Public: string[]
          Private: int }

    let ofHand hand =
        match hand with
        | PublicHand cards -> cards |> Seq.map ofCard |> Seq.toArray |> box
        | PrivateHand n -> box n

    let toHand (v: obj) =
        match v with
        | :? int as n -> PrivateHand n
        | :? JArray as cards -> cards.ToObject<string[]>() |> Seq.map toCard |> Seq.toList |> PublicHand
        | _ -> failwithf "Unknown hand"




type PlayerEvent = 
    { Player: string
      Event: obj }

let serialize =
    function
    | Board.Event.Started e -> 
        "Started", box { Dto.Players = e.Players |> Seq.map Dto.ofPlayer |> Seq.toArray
                         Dto.Barns = List.toArray e.Barns
                         Dto.DrawPile = e.DrawPile |> Seq.map Dto.ofCard |> Seq.toArray
                         Dto.CommonGoal = match e.Goal with | Common _ -> true | Individual _ -> false
                         Dto.Goal = match e.Goal with | Common n -> n |Individual n -> n
                         Dto.Undo = Dto.ofUndoType e.Undo
                         }
    | Board.Event.Played (playerid, Player.Event.MovedInField e ) -> 
        "MovedInField", box { Player = playerid
                              Event = Dto.ofMoved e }
    | Board.Event.Played (playerid, Player.Event.FirstCrossroadSelected e ) -> 
        "FirstCrossroadSelected" , box { Player = playerid
                                         Event = { Dto.FirstCrossroadSelectedDto.Crossroad = Dto.ofCrossroad e.Crossroad } }
    | Board.Event.Played (playerid, Player.Event.Annexed e ) ->
        "Annexed" , box { Player = playerid
                          Event =  { Dto.NewField = Dto.ofParcels e.NewField
                                     Dto.LostFields = e.LostFields |> Seq.map Dto.ofLostField |> Seq.toArray
                                     Dto.FreeBarns = Dto.ofParcels e.FreeBarns
                                     Dto.OccupiedBarns = Dto.ofParcels e.OccupiedBarns
                                     Dto.FenceLength = Some e.FenceLength
                                     } }
    | Board.Event.Played (playerid, Player.Event.CutFence e) -> 
        "CutFence" , box { Player = playerid; Event =  { Dto.CutFenceDto.Player = e.Player }  }
    | Board.Event.Played (playerid, Player.Event.FenceReduced e) -> 
        "FenceReduced" , box { Player = playerid; Event =  Dto.ofFenceReduced e  }
    | Board.Event.Played (playerid, Player.Event.FenceDrawn e ) -> 
        "FenceDrawn" , box { Player = playerid; Event = Dto.ofMoved e }
    | Board.Event.Played (playerid, Player.Event.FenceLooped e ) -> 
        "FenceLooped" , box { Player = playerid
                              Event = { Dto.FenceLoopedDto.Move = Dto.ofDirection e.Move
                                        Dto.FenceLoopedDto.Loop = Dto.ofFence e.Loop
                                        Dto.FenceLoopedDto.Crossroad = Dto.ofCrossroad e.Crossroad } }
    | Board.Event.Played (playerid, Player.Event.FenceRemoved e ) -> 
        "FenceRemoved" , box { Player = playerid; Event = Dto.ofMoved e }
    | Board.Event.Played (playerid, Player.Event.MovedPowerless e ) -> 
        "MovedPowerless" , box { Player = playerid; Event = Dto.ofMoved e }
    | Board.Event.Played (playerid, Player.Event.PoweredUp  ) -> "PoweredUp" , box { Player = playerid; Event = null }
    | Board.Event.Played (playerid, Player.Event.CardPlayed e  ) -> "CardPlayed" , box { Player = playerid; Event = (Dto.ofPlayCard e) }
    | Board.Event.Played (playerid, Player.Event.CardDiscarded e  ) -> "CardDiscarded" , box { Player = playerid; Event = (Dto.ofCard e) }
    | Board.Event.Played (playerid, Player.Event.HighVoltaged  ) -> "HighVoltaged" , box { Player = playerid; Event = null }
    | Board.Event.Played (playerid, Player.Event.Watched  ) -> "Watched" , box { Player = playerid; Event = null }
    | Board.Event.Played (playerid, Player.Event.Rutted  ) -> "Rutted" , box { Player = playerid; Event = null }
    | Board.Event.Played (playerid, Player.Event.SpedUp e  ) -> "SpedUp" , box { Player = playerid; Event = { Dto.SpedUpDto.Speed = e.Speed } }
    | Board.Event.Played (playerid, Player.Event.Heliported e  ) -> "Heliported" , box { Player = playerid; Event = { Dto.HeliportedDto.Destination = Dto.ofCrossroad e } }
    | Board.Event.Played (playerid, Player.Event.Bribed e  ) -> "Bribed" , box { Player = playerid; Event = { Dto.BribedDto.Parcel = Dto.ofParcel e.Parcel; Dto.BribedDto.Victim = e.Victim} }
    | Board.Event.Played (playerid, Player.Event.BonusDiscarded e  ) -> "BonusDiscarded" , box { Player = playerid; Event = Dto.ofCard e }
    | Board.Event.Played (playerid, Player.Event.Eliminated  ) -> "Eliminated" , box { Player = playerid; Event = null }
    | Board.Event.Played (playerid, Player.Event.Undone  ) -> "Undone" , box { Player = playerid; Event = null }
    | Board.Event.Next  -> "Next" , null
    | Board.Event.UndoCheckPointed  -> "UndoCheckPointed" , null
    | Board.Event.PlayerDrewCards e  -> 
        "PlayerDrewCards" , box { Dto.PlayerDrewCardsDto.Player = e.Player
                                  Dto.PlayerDrewCardsDto.Cards = Dto.ofHand e.Cards }
    | Board.Event.HayBalesPlaced(added,removed)  -> 
        "HayBalesPlaced" , box ({ Dto.HayBalesDto.Added = added |> Seq.map Dto.ofPath |> Seq.toArray
                                  Dto.HayBalesDto.Removed = removed |> Seq.map Dto.ofPath |> Seq.toArray } )
    | Board.Event.HayBaleDynamited e  -> "HayBaleDynamited" , box (Dto.ofPath e)
    | Board.Event.DiscardPileShuffled e  -> "DiscardPileShuffled" , box (e |> Seq.map Dto.ofCard |> Seq.toArray) 
    | Board.Event.DrawPileShuffled e  -> "DrawPileShuffled" , box (e |> Seq.map Dto.ofCard |> Seq.toArray) 
    | Board.Event.GameWon e  -> "GameWon" , box e


let deserialize data =
    try
        match data with
        | "Started", JObj (e: Dto.StartedDto) -> 
            [Board.Started { Players = e.Players |> Seq.map Dto.toPlayer |> Seq.toList
                             Barns = List.ofArray e.Barns
                             DrawPile = e.DrawPile |> Seq.map Dto.toCard |> Seq.toList
                             Goal = if e.CommonGoal then Common e.Goal else Individual e.Goal
                             Undo = Dto.toUndoType e.Undo } ]
        | "MovedInField", JObj { Player = p; Event = JObj(e: Dto.MovedDto) } -> 
            [Board.Played(p, Player.MovedInField (Dto.toMoved e))]
        | "FirstCrossroadSelected", JObj { Player = p; Event = JObj (e: Dto.FirstCrossroadSelectedDto) } -> 
            [Board.Played(p, Player.FirstCrossroadSelected { Crossroad = Dto.toCrossroad e.Crossroad })]
        | "Annexed", JObj { Player = p; Event = JObj (e: Dto.AnnexedDto) } -> 
            [Board.Played(p, Player.Annexed { NewField = Dto.toParcels e.NewField
                                              LostFields = e.LostFields |> Seq.map Dto.toLostField |> Seq.toList 
                                              FreeBarns = Dto.toParcels e.FreeBarns
                                              OccupiedBarns = Dto.toParcels e.OccupiedBarns
                                              FenceLength = e.FenceLength |> Option.defaultValue 0
                                              })]
        | "CutFence", JObj { Player = p; Event = JObj (e: Dto.CutFenceDto) } -> 
            [Board.Played(p, Player.CutFence { Player = e.Player })]
        | "FenceReduced", JObj { Player = p; Event = JObj (e: Dto.FenceReducedDto) } -> 
            [Board.Played(p, Player.FenceReduced (Dto.toFenceReduced e)) ]
        | "FenceDrawn", JObj { Player = p; Event = JObj (e: Dto.MovedDto) } -> 
            [Board.Played(p, Player.FenceDrawn (Dto.toMoved e))]
        | "FenceLooped", JObj { Player = p; Event = JObj (e: Dto.FenceLoopedDto) } -> 
            [Board.Played(p, Player.FenceLooped { Move = Dto.toDirection e.Move
                                                  Crossroad = Dto.toCrossroad e.Crossroad
                                                  Loop = Dto.toFence e.Loop })]
        | "FenceRemoved", JObj { Player = p; Event = JObj (e: Dto.MovedDto) } ->
            [Board.Played(p, Player.FenceRemoved (Dto.toMoved e))]
        | "MovedPowerless", JObj { Player = p; Event = JObj (e: Dto.MovedDto) } -> 
            [Board.Played(p, Player.MovedPowerless (Dto.toMoved e))]
        | "PoweredUp", JObj { Player = p; Event = _ } -> [Board.Played(p, Player.PoweredUp )]
        | "CardPlayed", JObj { Player = p; Event = JObj (e: Dto.PlayCardDto) } -> [Board.Played(p, Player.CardPlayed (Dto.toPlayCard e) )]
        | "CardDiscarded", JObj { Player = p; Event = JObj (e: string) } -> [Board.Played(p, Player.CardDiscarded (Dto.toCard e) )]
        | "HighVoltaged", JObj { Player = p; Event = _ } -> [Board.Played(p, Player.HighVoltaged )]
        | "Watched", JObj { Player = p; Event = _ } -> [Board.Played(p, Player.Watched )]
        | "Rutted", JObj { Player = p; Event = _ } -> [Board.Played(p, Player.Rutted )]
        | "SpedUp", JObj { Player = p; Event = JObj (e: Dto.SpedUpDto) } -> [Board.Played(p, Player.SpedUp { Speed = e.Speed })]
        | "Heliported", JObj { Player = p; Event = JObj (e: Dto.HeliportedDto) } -> [Board.Played(p, Player.Heliported (Dto.toCrossroad e.Destination))]
        | "Bribed", JObj { Player = p; Event = JObj (e: Dto.BribedDto) } -> [Board.Played(p, Player.Bribed { Parcel = Dto.toParcel e.Parcel; Victim = e.Victim })]
        | "BonusDiscarded", JObj { Player = p; Event = JObj (e: string) } -> [Board.Played(p, Player.BonusDiscarded (Dto.toCard e) )]
        | "Eliminated", JObj { Player = p; Event = _ } -> [Board.Played(p, Player.Eliminated )]
        | "Undone", JObj { Player = p; Event = _ } -> [Board.Played(p, Player.Undone )]
        | "Next", _ -> [ Board.Next]
        | "UndoCheckPointed", _ -> [ Board.UndoCheckPointed ]
        | "PlayerDrewCards", JObj (e: Dto.PlayerDrewCardsDto) -> [ Board.PlayerDrewCards { Player = e.Player; Cards = Dto.toHand e.Cards } ]
        | "HayBalesPlaced",  (e: obj) ->
            match e with
            | :? JArray as a ->
                [ Board.HayBalesPlaced (a.ToObject<Dto.PathDto[]>() |> Seq.map Dto.toPath |> Seq.toList, []) ]
            | :? JObject as o ->
                let hb = o.ToObject<Dto.HayBalesDto>()
                [ Board.HayBalesPlaced (hb.Added |> Seq.map Dto.toPath |> Seq.toList,
                                        hb.Removed |> Seq.map Dto.toPath |> Seq.toList) ]
            | _ -> []

        | "HayBaleDynamited", JObj (e: Dto.PathDto) -> [ Board.HayBaleDynamited (Dto.toPath e) ]
        | "DiscardPileShuffled", JObj (e: string[]) -> [ Board.DiscardPileShuffled (e |> Seq.map Dto.toCard |> Seq.toList) ]
        | "DrawPileShuffled", JObj (e: string[]) -> [ Board.DrawPileShuffled (e |> Seq.map Dto.toCard |> Seq.toList) ]
        | "GameWon", JObj e -> [ Board.GameWon e ]
        | _ -> []
    with
    | _ -> []

type PlayerCommand =
    { Player: string 
      Command: obj }
let serializeCmd =
    function
    | Board.Start c -> "Start", box c
    | Board.Play (p, Player.Start c) -> "PlayerStart", box { Player = p; Command = box c }
    | Board.Play (p, Player.Move c) -> "Move", box { Player = p; Command = box c }
    | Board.Play (p, Player.EndTurn) -> "EndTurn", box { Player = p; Command = null }
    | Board.Play (p, Player.PlayCard c) -> "PlayCard", box { Player = p; Command = box c }
    | Board.Play (p, Player.Discard c) -> "Discard", box { Player = p; Command = box c }
    | Board.Play (p, Player.SelectFirstCrossroad c) -> "SelectFirstCrossroad", box { Player = p; Command = box c }
    | Board.Play (p, Player.Undo) -> "Undo", box { Player = p; Command = null }



let gameRunner container gameid =
    let stream = "game-"+gameid
    let cmdStream = "game-cmd-" + gameid
    let chatStream = "chat-" + gameid
    MailboxProcessor.Start(fun mailbox ->
        let rec loop state expectedVersion =
            async {
                let! msg = mailbox.Receive()


                match msg with
                | GetState reply ->
                    let! newState, nextExpectedVersion =
                       EventStore.fold deserialize container Board.evolve stream state expectedVersion
                       |> Async.AwaitTask
                    let! chat =
                        EventStore.loadChat container chatStream
                        |> Async.AwaitTask

                    reply (Some (newState, nextExpectedVersion, chat))
                    return! loop newState nextExpectedVersion
                | Exec (cmd, reply) ->
                    let correlationId = Guid.NewGuid().ToString("n")


                    do! EventStore.appendCmd serializeCmd container cmdStream correlationId cmd
                        |> Async.AwaitTask |> Async.Ignore

                    let rec exec board  expectedVersion =
                        async {
                            
                            let events = Board.decide cmd board 
                            let newBoard = List.fold Board.evolve board events



                            let! result =
                                EventStore.append serialize container stream correlationId expectedVersion events 
                                |> Async.AwaitTask
                            match result with
                            | Ok nextExpectedVersion ->
                                reply events
                                return (newBoard,  nextExpectedVersion)
                            | Error e ->
                                let! newState, nextExpectedVersion =
                                   EventStore.fold deserialize container Board.evolve stream state expectedVersion
                                   |> Async.AwaitTask
                                return! exec newState  nextExpectedVersion 
                        }

                    let! newBoard, nextExpectedVersion =  exec state expectedVersion
                    return! loop newBoard nextExpectedVersion
                | WriteChat(message, player, reply) ->
                    do! EventStore.appendChat container chatStream (message, player, DateTime.UtcNow)
                        |> Async.AwaitTask
                    reply()
                    return! loop state expectedVersion
            }


        async {
            try 
                let! board, expectedVersion  = 
                    EventStore.fold deserialize container Board.evolve stream Board.initialState 0
                    |> Async.AwaitTask
            


                return! loop board expectedVersion

            with
            | ex -> printfn "%O" ex
        }
    )

     
let runner =
    let client = new Microsoft.Azure.Cosmos.CosmosClient(serverConfig.Cosmos)
    let container = client.GetContainer("crazyfarmers",containerName)
    MailboxProcessor.Start(fun mailbox ->
        let rec loop games =
            async {
                let! (gameid, msg) = mailbox.Receive()

                match msg with
                | GetState _ ->
                    let newGames =
                        match Map.tryFind gameid games with
                        | Some (game: MailboxProcessor<GameRunnerCmd>) ->
                            game.Post(msg)
                            games
                        | None -> 
                            let game = gameRunner container gameid
                            game.Post(msg)
                            Map.add gameid game games
                    return! loop newGames
                | Exec (cmd, reply) ->
                    let newGames =
                        match Map.tryFind gameid games with
                        | Some (game: MailboxProcessor<GameRunnerCmd>) ->
                            game.Post(msg)
                            games
                        | None ->
                            match cmd with
                            | Board.Start _ ->
                                let game = gameRunner container gameid
                                game.Post(msg)
                                Map.add gameid game games
                            | _ ->
                                reply []
                                games
                    return! loop newGames
                | WriteChat (_,_, reply) ->
                    match Map.tryFind gameid games with
                    | Some (game: MailboxProcessor<GameRunnerCmd>) ->
                        game.Post(msg)
                    | None -> reply()
                    return! loop games

                    

            }
        loop Map.empty
    )


/// Elmish init function with a channel for sending client messages
/// Returns a new state and commands
let init (claim: JwtClaim) clientDispatch () =
    clientDispatch(SyncPlayer claim.sub)
    NotConnected, Cmd.none

/// Elmish update function with a channel for sending client messages
/// Returns a new state and commands

let update claim clientDispatch msg (model: PlayerState) =
    async {
    match msg with
    | JoinGame gameid ->
        
        let! state = runner.PostAndAsyncReply(fun c -> gameid, GetState c.Reply)
        match state with 
        | Some ({ Board = Board game} as s, version, chat)
        | Some ({ Board = Won(_, game)} as s, version, chat) ->
            let color = 
                Map.tryFind claim.sub game.Players
                |> Option.map Player.color

            let pboard = privateUndoableBoard claim.sub s

            clientDispatch (Sync (Board.toUndoState pboard, version, chat))
            return  Connected {GameId = gameid; Color = color; Player = claim.sub } ,  Cmd.none
        | _ ->
            return model, Cmd.none

    | Command cmd ->
        match model with
        | Connected g -> 
            do! runner.PostAndAsyncReply(fun c -> g.GameId, Exec(Board.Play(claim.sub, cmd), c.Reply))
                |> Async.Ignore



            return model, Cmd.none
        | NotConnected -> return model, Cmd.none
    | SendMessage msg ->
        match model with
        | Connected g ->
            do! runner.PostAndAsyncReply(fun c -> g.GameId, WriteChat(msg, claim.sub, c.Reply))
                |> Async.Ignore

            return model, Cmd.none
                
        | NotConnected -> return model, Cmd.none
    }

let handleGame gameId i events =
    task {
    match events with
    | [Board.Started e] ->
        Events ([ Board.Started { e with DrawPile = [] } ], i)
        |> connections.SendClientIf (function Connected c when c.GameId = gameId -> true  | _ -> false )
    | _ ->
        let drawingPlayers =
            events
            |> List.choose (function
                | Board.PlayerDrewCards e -> Some e.Player
                | _ -> None)
            |> set

        for drawingPlayer in drawingPlayers do
            let personalEvents =
                events
                |> List.map (
                    function
                    | Board.PlayerDrewCards e when e.Player <> drawingPlayer ->
                        Board.PlayerDrewCards { e with Cards = Hand.toPrivate e.Cards }
                    | e -> e
                )
            connections.SendClientIf (function | Connected c when c.GameId = gameId && c.Player = drawingPlayer -> true  | _ -> false) 
                (Events (personalEvents, i ))

        let privateEvents =
                events
                |> List.map (
                    function
                    | Board.PlayerDrewCards e ->
                        Board.PlayerDrewCards { e with Cards = Hand.toPrivate e.Cards }
                    | Board.DiscardPileShuffled e ->
                        Board.DiscardPileShuffled []
                    | e -> e
                )


        connections.SendClientIf (function | Connected c when c.GameId = gameId && not (Set.contains c.Player drawingPlayers ) -> true  | _ -> false)
            (Events (privateEvents, i ))
    }


module Template =
    open Fable.React
    open Fable.React.Props
    let register =
        html [ ]
            [ head [] 
               [ title [ ] [ str "⚡Crazy Farmers and the clôtures électriques⚡"] ]
              body []
                   [ h1 [] [ str "Register" ] 
                     form [ Method "POST" ]
                          [ div [] 
                                [ label [ HtmlFor "email" ] [ str "email" ] 
                                  input [ Type "text"; Name "email"] ]
                            div [] 
                                [ label [ HtmlFor "name"] [str "name"]
                                  input [ Type "test"; Name "name"] ]
                            button [] [ str "Register" ]
                            div []
                                [ str "Alreay have an account ?"
                                  a [ Href "/auth/login"] [str "Login"]
                                ]
                            ] 
                   ]
            ]
        |> Fable.ReactServer.renderToString 
    let login =
        html [ ]
            [ head [] 
               [ title [ ] [ str "⚡Crazy Farmers and the clôtures électriques⚡"] ]
              body []
                   [ h1 [] [ str "Login" ] 
                     form [ Method "POST" ]
                          [ div [] 
                                [ label [ HtmlFor "email" ] [ str "email" ] 
                                  input [ Type "text"; Name "email"] ]
                            button [] [ str "Login" ]
                            div []
                                [ str "no account yet ? "
                                  a [ Href "/auth/register" ] [str "Register"]
                                  ]

                            ] 
                   ]
            ]
        |> Fable.ReactServer.renderToString 
        
    let auth userid =
        html [ ]
            [ head [] 
               [ title [ ] [ str "⚡Crazy Farmers and the clôtures électriques⚡"] ]
              body []
                   [ h1 [] [ str "Verification" ] 
                     form [ Method "POST"; Action "/auth/check" ]
                          [ label [ HtmlFor "code" ] [ str "code" ]
                            input [ Type "text"; Name "Code"]
                            input [ Type "hidden"; Name "Userid"; Value userid ]
                            button [] [ str "Verify" ]
                            ] 
                   ]
            ]
        |> Fable.ReactServer.renderToString 
    let test userid name =
       html [ ]
           [ head [] 
              [ title [ ] [ str "⚡Crazy Farmers and the clôtures électriques⚡"] ]
             body []
                  [ h1 [] [ str "Test" ] 
                    p [] [ str userid ]
                    p [] [ str name ]
                  ]
           ]
       |> Fable.ReactServer.renderToString 


    
let createId len = 
  Array.init len (fun _ ->
    let n = System.Security.Cryptography.RandomNumberGenerator.GetInt32(10+26+26)
    let c= 
      if n < 10 then
        int '0' + n
      elif n < 10+26 then
        int 'a' + n - 10
      else 
        int 'A' + n - 36
    char c)
  |> String

open Model


let sendChallenge config email name =
    task {
        let! userid =
            task{
            match! Storage.tryGetUserByEmail config.Cosmos email with
            | Some user -> return Some (user.userid, user.name)
            | None -> 
                match name with
                | Some name -> 
                    let mutable succeeded = false
                    let mutable userid = ""
                    while not succeeded do
                        userid <- createId 16
                        let user = 
                            { id = "user"
                              userid = userid
                              email = email
                              name = name }
                        let! response = Storage.saveUser config.Cosmos user
                        succeeded <- response
                    return Some (userid , name)
                | None -> return None }


        match userid with
        | Some (userid,name) ->
            let code = createId 16


            let challenge = 
                { id = userid + "-" + code 
                  userid = userid
                  challenge = code
                  ttl = 600<s>
                }

            do! Storage.saveChallenge config.Cosmos challenge

            do! Email.sendCode config.BaseUri email userid code

            return Some (userid, name)

        | None -> return None
    }


let logout : HttpHandler = fun next ctx ->
    task {
        ctx.Response.Cookies.Delete("crazyfarmers")

        return! redirectTo false "/" next ctx

    }


let login : HttpHandler = fun _ ctx ->
    task {
        return! ctx.WriteHtmlStringAsync (Template.login)
    }
let register : HttpHandler = fun _ ctx ->
    task {
        return! ctx.WriteHtmlStringAsync (Template.register)
    }


[<CLIMutable>]
type LoginForm = 
    { email : string }
[<CLIMutable>]
type RegisterForm = 
    { email : string
      name: string}

let postLogin (form: LoginForm)  : HttpHandler = fun next ctx ->
    task {
        match! sendChallenge serverConfig form.email None with
        | Some (userid,_) ->
            return! redirectTo false ("/auth/check/" + userid ) next ctx
        | None -> 
            return! redirectTo false "/auth/register" next ctx
    }

let postRegister (form: RegisterForm)  : HttpHandler = fun next ctx ->
    task {
        match! sendChallenge serverConfig form.email (Some form.name) with
        | Some (userid,_) ->
            return! redirectTo false ("/auth/check/" + userid ) next ctx
        | None -> 
            return! redirectTo false "/auth/register" next ctx
    }

[<CLIMutable>]
type AuthForm = 
    { Userid : string
      Code: string }
[<CLIMutable>]
type AuthWaitForm = 
    { Userid : string }

let auth (form: AuthWaitForm): HttpHandler = fun _ ctx ->
    task {
        return! ctx.WriteHtmlStringAsync (Template.auth form.Userid)
    }


let postAuth (form: AuthForm) : HttpHandler = fun next ctx ->
    task {
        match! Storage.tryGetChallenge serverConfig.Cosmos  form.Userid form.Code with
        | Some challenge  ->
            let! user = Storage.getUserById serverConfig.Cosmos form.Userid
            let jwt = 
                JwtBuilder()
                    .WithAlgorithm(HMACSHA256Algorithm())
                    .WithSecret(serverConfig.JWTSecret)
                    .AddClaim(ClaimName.Subject, form.Userid)
                    .AddClaim(ClaimName.CasualName, user.name)
                    .AddClaim(ClaimName.ExpirationTime, DateTimeOffset.UtcNow.AddYears(1).ToUnixTimeSeconds())
                    .Encode()
            ctx.Response.Cookies.Append("crazyfarmers", jwt, Http.CookieOptions(Secure = serverConfig.Secure, Domain = (match serverConfig.Domain with "localhost" -> null | s -> s), Expires = Nullable (DateTimeOffset.UtcNow.AddYears(1))))
            return! (redirectTo false "/"  ) next ctx

        | None ->
            return! (redirectTo false "/") next ctx
    }

let claim jwt = 
    if String.IsNullOrEmpty jwt then
        Error "No cookie"
    else
        try
            let serializer = JWT.Serializers.JsonNetSerializer()
            JwtBuilder()
               .WithSecret(serverConfig.JWTSecret)
               .WithSerializer(serializer)
               .WithAlgorithm(HMACSHA256Algorithm())
               .WithValidator(JWT.JwtValidator(serializer, JWT.UtcDateTimeProvider()))
               .WithUrlEncoder(JWT.JwtBase64UrlEncoder())
               .MustVerifySignature()
               .Decode<JwtClaim>(jwt)
            |> Ok
        with
        | ex -> Error ex.Message

let authTest : HttpHandler = fun next ctx ->
    task {
        match claim ctx.Request.Cookies.["crazyfarmers"] with
        | Ok claim ->
            return! ctx.WriteHtmlStringAsync (Template.test claim.sub claim.nickname)
        | Error e ->
            return! ctx.WriteHtmlStringAsync (Template.test "Not authenticated" e)


    }

let requireLogin (handler: _ -> HttpHandler) : HttpHandler = fun next ctx ->
    task {
        match claim ctx.Request.Cookies.["crazyfarmers"] with
        | Error _ ->
            return! redirectTo false "/" next ctx
        | Ok claim ->
            return! handler claim next ctx
    }
let tryLogin (handler: _ -> HttpHandler) : HttpHandler = fun next ctx ->
    task {
        match claim ctx.Request.Cookies.["crazyfarmers"] with
        | Error _ ->
            return! handler None next ctx

        | Ok claim ->
            return! handler (Some claim) next ctx
    }

let authApi =
    choose [
        GET >=> route "/logout" >=> logout
        GET >=> route "/login" >=> login
        POST >=> route "/login" >=>  bindForm<LoginForm>(None)  postLogin 
        GET >=> route "/register" >=> register
        POST >=> route "/register" >=>  bindForm<RegisterForm>(None)  postRegister 
        GET >=> route "/test" >=> authTest 
        GET >=> routeBind<AuthWaitForm> "/check/{userid}" auth
        POST >=> route "/check" >=> bindForm<AuthForm>(None) postAuth 
        GET >=> routeBind<AuthForm> "/check/{userid}/{code}" postAuth
    ]

let playGame claim = 
    Bridge.mkServer "/socket/init" (init claim) (update claim)
    |> Bridge.withServerHub connections
    |> Bridge.run Giraffe.server

module Join =
    open SharedJoin
    type Model =
        | NoGame
        | SelectingGame
        | SetupGame of string 
        | JoiningGame of string
        | Started of string 

    type RunnerCmd =
        | GetState of  ((Game * int) option-> unit)
        | Exec of Command * ((Event list * int) -> unit)

    let serialize =
        function
        | Created e -> "Created", box e
        | PlayerSet e -> "PlayerSet", box e
        | GoalSet e -> "GoalSet", box e
        | MadePrivate -> "MadePrivate", null
        | MadePublic -> "MadePublic", null
        | Leaved e -> "Leaved", box e
        | Event.Started e -> "Started", box e
        | Event.Cancelled -> "Cancelled", null

    let deserialize =
        function
        | "Created", JObj e -> [Created e]
        | "PlayerSet", JObj e -> [PlayerSet e]
        | "GoalSet", JObj e -> [GoalSet e]
        | "MadePublic", _ -> [ MadePublic ]
        | "MadePrivate", _ -> [ MadePrivate ]
        | ("Leaved"| "PlayerUnset"), JObj e -> [ Leaved e]
        | "Started", JObj e -> [Event.Started e]
        | "Cancelled", _ -> [ Event.Cancelled ]
        | _ -> []

    
    let serializeCmd =
        function
        | Create e -> "Create", box e
        | SetPlayer (c,p,n) -> "SetPlayer", box {| Color = c; Player = p; Name = n |}
        | Command.SetGoal e -> "SetGoal", box e
        | Command.MakePublic -> "MakePublic", null
        | Command.MakePrivate -> "MakePrivate", null
        | Command.Leave e -> "Leave", box e
        | Command.Start -> "Start", null


    let serializeGoal =
        function
        | Fast -> "Fast"
        | Regular -> "Regular"
        | Expert -> "Expert"

    let deserializeGoal =
        function
        | "Fast" -> Fast
        | "Expert" -> Expert
        | _  -> Regular

    let gameRunner container gameid =
        let stream = "join-"+gameid
        let cmdStream = "join-cmd-"+gameid
        MailboxProcessor.Start(fun mailbox ->
            let rec loop state expectedVersion =
                async {
                    let! msg = mailbox.Receive()


                    match msg with
                    | GetState reply ->
                        let! newState, nextExpectedVersion =
                           EventStore.fold deserialize container evolve stream state expectedVersion
                           |> Async.AwaitTask
                        reply (Some (newState, nextExpectedVersion))
                        return! loop newState nextExpectedVersion
                    | Exec (cmd, reply) ->

                        let correlationId = Guid.NewGuid().ToString("n")
                        do! EventStore.appendCmd serializeCmd container cmdStream correlationId cmd
                            |> Async.AwaitTask |> Async.Ignore

                        let rec exec state expectedVersion =
                            async {
                                let events = decide cmd state 

                                let! result =
                                    EventStore.append serialize container stream correlationId expectedVersion events
                                    |> Async.AwaitTask
                                match result with
                                | Ok nextExpectedVersion ->
                                    let newState = List.fold evolve state events
                                    reply (events, nextExpectedVersion)
                                    return (newState, nextExpectedVersion)
                                | Error e ->
                                    let! newState, nextExpectedVersion =
                                       EventStore.fold deserialize container evolve stream state expectedVersion
                                       |> Async.AwaitTask
                                    return! exec newState nextExpectedVersion 
                            }

                        let! newState, nextExpectedVersion =  exec state expectedVersion


                        return! loop newState nextExpectedVersion
                }

            async {
                let! board, expectedVersion  = 
                    EventStore.fold deserialize container evolve stream InitialState 0
                    |> Async.AwaitTask
            


                return! loop board expectedVersion
            }
        )



        


    let setupRunner =
        let client = new Microsoft.Azure.Cosmos.CosmosClient(serverConfig.Cosmos)
        let container = client.GetContainer("crazyfarmers",containerName)

        MailboxProcessor.Start(fun mailbox ->
            let rec loop games =
                async {
                    let! id, msg = mailbox.Receive()
                    match Map.tryFind id games with
                    | Some (runner: _ MailboxProcessor) ->
                        runner.Post msg
                        return! loop games
                    | None ->
                        let runner = gameRunner container id
                        runner.Post msg
                        return! loop (Map.add id runner games)
                }
            loop Map.empty
        )
    
    
    
    let connections =
      ServerHub<Model,ServerMsg,ClientMsg>()
    
    
    let init claim clientDispatch () =
        match claim with
        | Some claim -> clientDispatch (LoggedIn (claim.sub, claim.nickname))
        | None -> ()
        NoGame, Cmd.none


    let notifyPublicGames cnxString =
        async {
        let! games = Storage.loadPublicGames cnxString |> Async.AwaitTask
        let g = [| for game in games -> 
                        { PublicGame.Id = game.id
                          Goal = deserializeGoal game.goal
                          Players = game.players
                          Created = game.created } |]
        connections.SendClientIf (fun s -> s = SelectingGame)  (UpdatePublicGames g)
        }

    let update cnxString claim clientDispatch msg (model: Model) =
        async {
        match model, msg with
        | _ , Login email ->
            let t =
                task {
                    let! result = sendChallenge serverConfig email None
                    match result with
                    | Some(playerid,_) -> clientDispatch (StartCheck playerid)
                    | None -> () }
            t.Wait()
            return model, Cmd.none
        | _ , Register (email, name) ->
            let t =
                task {
                    let! result = sendChallenge serverConfig email (Some name)
                    match result with
                    | Some(playerid,_) -> clientDispatch (StartCheck playerid)
                    | None -> () }
            t.Wait()
            return model, Cmd.none


        | _ , CreateGame ->
            match claim with
            | Some claim ->
                let gameid = createId 10
                let! events = setupRunner.PostAndAsyncReply(fun c -> gameid, Exec(Create { GameId = gameid; Initiator = claim.sub}, c.Reply))
                clientDispatch(Events events)
                return SetupGame gameid, Cmd.none
            | _ ->
                clientDispatch(ShouldLogin )
                return model, Cmd.none
                
        | _, JoinGame gameid ->
            match claim with
            | Some claim ->
                match! setupRunner.PostAndAsyncReply(fun c -> gameid, GetState(c.Reply)) with
                | Some (Setup s, version) -> 
                    if s.Initiator = claim.sub then
                       clientDispatch(SyncCreate (gameid, Setup s, version))
                       return SetupGame gameid, Cmd.none
                    else
                        clientDispatch(SyncJoin (gameid, Setup s, version))
                        return JoiningGame gameid, Cmd.none
                | Some (Game.Started s, version) ->
                    clientDispatch(SyncStarted (gameid, Game.Started s, version))
                    return Started gameid, Cmd.none
                | _ ->
                    return model, Cmd.none
            | None ->
                clientDispatch(ShouldLogin )
                return model, Cmd.none


        |SetupGame gameid, SelectColor color
        |JoiningGame gameid, SelectColor color ->
            match claim with
            | Some claim ->
                do! setupRunner.PostAndAsyncReply(fun c -> gameid, Exec(SetPlayer(color, claim.sub, claim.nickname), c.Reply))
                    |> Async.Ignore
            | None -> ()

            return model, Cmd.none
        | JoiningGame gameid, Leave ->
            match claim with
            | Some claim ->
                do! setupRunner.PostAndAsyncReply(fun c -> gameid, Exec(Command.Leave claim.sub, c.Reply))
                    |> Async.Ignore
            | None -> ()

            return NoGame, Cmd.none
        | SetupGame gameid, Leave ->
            match claim with
            | Some claim ->
                do! setupRunner.PostAndAsyncReply(fun c -> gameid, Exec(Command.Leave claim.sub, c.Reply))
                    |> Async.Ignore
            | None -> ()

            return NoGame, Cmd.none

        | SetupGame gameid, SelectGoal goal ->
            do! setupRunner.PostAndAsyncReply(fun c -> gameid, Exec(SetGoal goal, c.Reply))
                    |> Async.Ignore

            return  model, Cmd.none
        | SetupGame gameid, MakePublic ->
            do! setupRunner.PostAndAsyncReply(fun c -> gameid, Exec(Command.MakePublic, c.Reply))
                    |> Async.Ignore

            return  model, Cmd.none
        | SetupGame gameid, MakePrivate ->
            do! setupRunner.PostAndAsyncReply(fun c -> gameid, Exec(Command.MakePrivate, c.Reply))
                    |> Async.Ignore

            return  model, Cmd.none
        | SetupGame gameid, Start ->
            let! events, version = setupRunner.PostAndAsyncReply(fun c -> gameid, Exec(Command.Start, c.Reply))
            for event in events do
                match event with
                | SharedJoin.Event.Started e ->
                    do! runner.PostAndAsyncReply(fun c -> gameid, GameRunnerCmd.Exec(Board.Start { Players = [ for p in e.Players -> p.Color, p.PlayerId, p.Name ]; Goal = e.Goal; Undo = FullUndo }, c.Reply))
                         |> Async.Ignore
                | _ -> ()
            //connections.SendClientIf(function SetupGame id | JoiningGame id when id = gameid -> true | _ -> false ) (Events(events, version))
            return Started gameid, Cmd.none
        | _, Select ->
            let! games = Storage.loadPublicGames cnxString |> Async.AwaitTask
            let g = [| for game in games -> 
                            { Id = game.id
                              Goal = deserializeGoal game.goal
                              Players = game.players
                              Created = game.created} |]
            clientDispatch(UpdatePublicGames g)
            return  SelectingGame, Cmd.none

        | _ -> return model, Cmd.none
    }

    let handleJoin cnxString gameid i events =
        task {
            connections.SendClientIf (function SetupGame id | JoiningGame id | Started id when id = gameid -> true | _ -> false ) (Events (events, i))
            for event in events do
                match event with
                | Created _ ->
                    do! Storage.createGame cnxString gameid
                | MadePublic ->
                    let! game = Storage.loadGame cnxString gameid
                    do! Storage.updateGame cnxString { game with isPublic = true}
                    do! notifyPublicGames cnxString
                | MadePrivate ->
                    let! game = Storage.loadGame cnxString gameid
                    do! Storage.updateGame cnxString { game with isPublic = false}
                    do! notifyPublicGames cnxString
                    
                | Event.PlayerSet e ->
                    let! game = Storage.loadGame cnxString gameid
                    let players = Map.ofArray game.players |> Map.add e.PlayerId e.Name
                    do! Storage.updateGame cnxString { game with players = Map.toArray players }
                    do! notifyPublicGames cnxString
                   
                | Event.Leaved e ->
                    let! game = Storage.loadGame cnxString gameid
                    let players = Map.ofArray game.players |> Map.remove e
                    do! Storage.updateGame cnxString { game with players = Map.toArray players }
                    do! notifyPublicGames cnxString
                | Event.GoalSet e ->
                    let! game = Storage.loadGame cnxString gameid
                    if deserializeGoal game.goal <> e then
                        do! Storage.updateGame cnxString { game with goal = serializeGoal e}
                    do! notifyPublicGames cnxString
                 
                | Event.Started _ ->
                    do! Storage.deleteGame cnxString gameid 
                    do! notifyPublicGames cnxString
                | Event.Cancelled _ ->
                    do! Storage.deleteGame cnxString gameid 
                    do! notifyPublicGames cnxString
        }

            


        
let subs = 
    let client = new Microsoft.Azure.Cosmos.CosmosClient(serverConfig.Cosmos)
    let container = client.GetContainer("crazyfarmers", containerName)

    EventStore.subscription client container ("game-"+Environment.MachineName)
        [ EventStore.handler @"^game-(?<id>[\w\d]+)$" deserialize handleGame
          EventStore.handler @"^join-(?<id>[\w\d]+)$" Join.deserialize (Join.handleJoin serverConfig.Cosmos) ]
        

    EventStore.chatSubscription client container ("chat-"+Environment.MachineName)
        (fun e ->
            task {
            if e.p.StartsWith "chat-" then
                let gameid = e.p.Substring(5)
                connections.SendClientIf
                    (function Connected c when c.GameId = gameid -> true | _ -> false)
                    { Text = e.m; Player = e.pl; Date = e.d } 
            } )



        

let joinGame claim  =
    Bridge.mkServer "/socket/join" (Join.init claim) (Join.update serverConfig.Cosmos claim)
    |> Bridge.withServerHub Join.connections
    |> Bridge.run Giraffe.server


let webApp =
    choose [
        subRoute "/auth" authApi
        tryLogin joinGame
        requireLogin playGame

    ]


let configureApp (app : IApplicationBuilder) =
    Storage.initialize serverConfig.Cosmos

    app.UseDefaultFiles()
       .UseStaticFiles()
       .UseWebSockets()
       .UseGiraffe webApp 

let configureServices (services : IServiceCollection) =
    services.AddGiraffe() |> ignore
    services.AddSingleton<Giraffe.Serialization.Json.IJsonSerializer>(Thoth.Json.Giraffe.ThothSerializer()) |> ignore



WebHost
    .CreateDefaultBuilder()
    .UseWebRoot(publicPath)
    .UseContentRoot(publicPath)
    .Configure(Action<IApplicationBuilder> configureApp)
    .ConfigureServices(configureServices)
    .UseUrls("http://0.0.0.0:" + port.ToString() + "/")
    .Build()
    .Run()
