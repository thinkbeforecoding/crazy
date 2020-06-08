module SharedServer

open Shared
open Fable.Core
open System

let privateGame id (game: PlayingBoard) =
    { game with
        Players = 
            game.Players
            |> Map.map (fun playerid player ->
                if playerid = id then
                    player
                else
                    Player.toPrivate player
            )
    }

let privateBoard playerId board =
    match board with
    | Board b -> Board (privateGame playerId b)
    | Won(p,b) -> Won(p, (privateGame playerId b))
    | InitialState -> InitialState

let privateUndoableBoard playerId undoboard =
    { undoboard with
        Board = privateBoard playerId undoboard.Board
        UndoPoint = privateBoard playerId undoboard.UndoPoint
    }


let privateEvents playerId events =
    events
    |> List.map (function
    | Board.PlayerDrewCards p as e -> 
        if playerId = p.Player then
            e
        else
            Board.PlayerDrewCards { Player = p.Player; Cards = Hand.toPrivate p.Cards }
            
    | Board.DiscardPileShuffled _ -> Board.DiscardPileShuffled []
    | Board.DrawPileShuffled _ -> Board.DrawPileShuffled []
    | e -> e )

let bgaUpdateState events (board: UndoableBoard) (state: string) changeState eliminatePlayer =
    for e in events do
        match e with
        | Board.Next -> changeState "next"
        | Board.GameWon _ -> changeState "endGame"
        | Board.Played(_,Player.FirstCrossroadSelected _) ->
            changeState "selectFirstCrossroad"
        | Board.Played(p, Player.Eliminated) ->
             eliminatePlayer p;

        |Board.Played(p, Player.Undone) ->
            match board.Board with
            | Board.Board b ->
                match b.Players.[p] with
                | Starting _ -> changeState "restart"
                | Playing player ->
                    if Player.canMove (Some p) board.Board then
                        changeState "canMove"
                    elif Hand.canPlay player.Hand then
                        if Hand.shouldDiscard player.Hand then
                            changeState "shouldDiscard"
                        else
                            changeState "endTurn"
                | _ -> ()
            | _ -> ()
        | Board.Played(p, _) ->
            match board.Board with
            | Board.Board b ->
                match b.Players.[p] with
                | Playing player ->
                    if Player.canMove (Some p) board.Board then
                        changeState "canMove"
                    elif Hand.canPlay player.Hand then
                        if Hand.shouldDiscard player.Hand then
                            changeState "shouldDiscard"
                        else
                            changeState "endTurn"
                    elif board.UndoType <> NoUndo then
                         changeState "endTurn"
                | _ -> ()
            | _ -> ()
        | _ -> ()


let bgaNextPlayer (board: UndoableBoard) =
    match board.Board with
    | Board b ->
        match Board.currentPlayer b with
        | Starting _ -> "nextStarting"
        | _ -> 
            if Player.canMove (Some b.Table.Player) board.Board then
                "nextPlayer"
            else
                "nextEndTurn"
    | InitialState _ 
    | Won _ -> ""

let bgaProgression (board: UndoableBoard) =
    match board.Board with
    | InitialState _ -> 0.
    | Won _ -> 100.
    | Board b ->
        match b.Goal with
        | Common g ->
            let totalSize = Board.totalSize b
            float (min g totalSize) * 100. / float g
        | Individual g ->
            let maxSize = 
                b.Players 
                |> Map.toSeq
                |> Seq.map(fun (_,p) -> Player.principalFieldSize p)
                |> Seq.max
            float (min g maxSize) * 100. / float g
                

let bgaScore events (board: UndoableBoard) updateScore =
    match board.Board with
    | Board b
    | Won(_,b)->
        for e in events do
            match e with
            | Board.Played(_, Player.Annexed(_))
            | Board.Played(_, Player.CardPlayed(PlayBribe _)) ->
                for pid,player in Map.toSeq b.Players do
                    let score = Player.principalFieldSize player
                    let scoreAux = Player.fieldTotalSize player
                    updateScore(pid, score, scoreAux);
            | _ -> ()
    | _ -> ()

type Php =
    [<Emit("_($0)")>]
    static member translate s = s

    [<Emit("sprintf(_($0),$1...)")>]
    static member translatef(fmt : string, [<ParamArray>]args : obj[]) = fmt

    [<Emit("clienttranslate($0)")>]
    static member clienttranslate s = s

let inline (==>) k v = k, box v

let cardIcon card =
    let cardName = Client.cardName card
    "<div class=\"cardicon\"><div class=\"" + cardName + "\"></div></div>"


let textAction (previous: UndoableBoard) (b: UndoableBoard)  e = 
    match b.Board with
    | Board board
    | Won (_,board) ->
        let playerName p =
            let name = board.Table.Names.[p]
            let color =
                match board.Players.[p] |> Player.color with
                | Blue -> "AEDBDE"
                | Yellow -> "EFC54C"
                | Purple -> "A87BBE"
                | Red -> "EA222F"
            "<span style=\"font-weight:bold;color:#" + color + "\">" + name + "</span>"

        match e with
        | Board.Played (p, Player.Annexed(e)) ->
            Php.clienttranslate "${player} takes over ${parcels} parcel(s)", Map.ofList  [ "player" ==> playerName p; "parcels" ==> List.length e.NewField ]
        | Board.Played(p, Player.Event.CutFence e) ->
            Php.clienttranslate "${player} cut ${cut}'s fence",  Map.ofList [ "player" ==> playerName p; "cut" ==> playerName e.Player ]
        | Board.PlayerDrewCards e ->
            Php.clienttranslate "${player} draws ${cardcount} card(s)", Map.ofList [  "player" ==> playerName e.Player; "cardcount" ==> Hand.count e.Cards]
        | Board.Played(p, Player.Event.Bribed e) ->
            Php.clienttranslate "${icon} ${player} takes one of ${bribed}'s parcel", Map.ofList ["player" ==> playerName p; "bribed" ==> playerName e.Victim;  "icon" ==> cardIcon Bribe  ]
        | Board.Played(p, Player.Event.Eliminated) ->
            Php.clienttranslate "${player} is eliminated !", Map.ofList ["player" ==> playerName p ]
        | Board.Played(p, Player.Event.CardPlayed(PlayHelicopter _)) ->
            Php.clienttranslate "${icon} ${player} is heliported to new crossroad", Map.ofList ["player" ==> playerName p; "icon" ==> cardIcon Helicopter ]
        | Board.Played(p, Player.Event.CardPlayed(PlayHighVoltage)) ->
            Php.clienttranslate "${icon} ${player}'s fence cannot be cut until next turn", Map.ofList ["player" ==> playerName p; "icon" ==> cardIcon HighVoltage ]
        | Board.Played(p, Player.Event.CardPlayed(PlayWatchdog)) ->
            Php.clienttranslate "${icon} ${player} field is protected until next turn", Map.ofList ["player" ==> playerName p; "icon" ==> cardIcon Watchdog ]
        | Board.Played(p, Player.CardPlayed(PlayRut victim )) ->
            Php.clienttranslate "${icon} ${player} makes ${rutted} loose 2 moves during next turn", Map.ofList ["player" ==> playerName p; "rutted" ==> playerName victim; "icon" ==> cardIcon Rut ]
        | Board.Played(p, Player.CardPlayed(PlayHayBale(hb,_)  as pc) ) ->
            Php.clienttranslate "${icon} ${player} blocks ${haybales} paths", Map.ofList ["player" ==> playerName p; "haybales" ==> List.length hb; "icon" ==> cardIcon (Card.ofPlayCard pc) ]
        | Board.Played(p, Player.CardPlayed(PlayDynamite _)) ->
            Php.clienttranslate "${icon} ${player} dynamites 1 hay bale", Map.ofList ["player" ==> playerName p; "icon" ==> cardIcon Dynamite ]
        | Board.Played(p, Player.CardPlayed(PlayNitro power)) ->
            Php.clienttranslate "${icon} ${player} get ${nitro} extra move(s)", Map.ofList ["player" ==> playerName p; "nitro" ==> (match power with One -> 1 | Two -> 2); "icon" ==> cardIcon (Nitro power) ]
        | Board.Played(p, Player.Undone) ->
            Php.clienttranslate "${player} undo last moves", Map.ofList ["player" ==> playerName p ]
        | Board.Next ->
            let player = 
                match previous.Board with
                | Board b -> b.Table.Player
                | _ -> ""
            match Map.tryFind player board.Players with
            | Some (Playing p) -> Php.clienttranslate "${player} ends turn after ${moves} moves", Map.ofList ["player" ==> playerName player; "moves" ==> p.Moves.Done ] 
            | _ -> null, Map.empty

        | _ -> null, Map.empty
    | _ ->
        null, Map.empty

type Stats =
    {
        TableStats: Map<string, int>
        PlayerStats: Map<string, Map<string,int>> }

type UndoableStats =
    { Stats: Stats
      UndoPoint: Stats }




module Stats =
    let turns_number = "turns_number"
    let fences_drawn = "fences_drawn"
    let fences_cut = "fences_cut"
    let cut_number = "cut_number"
    let takeovers_number = "takeovers_number"
    let biggest_takeover = "biggest_takeover"
    let freebarns_number = "freebarns_number"
    let occupiedbarns_number = "occupiedbarns_number"
    let cardsplayed_number = "cardsplayed_number"
    let haybales_max_number = "haybales_max_number"
    let haybales_number = "haybales_number"
    let dynamites_number = "dynamites_number"
    let haybales_moved_number = "haybales_moved_number"
    let helicopters_number = "helicopters_number"
    let highvoltages_number = "highvoltages_number"
    let watchdogs_number = "watchdogs_number"
    let bribes_number = "bribes_number"
    let nitro1_number = "nitro1_number"
    let nitro2_number = "nitro2_number"
    let ruts_number = "ruts_number"
    let rutted_number = "rutted_number"

    let empty =
        let stats =
            { TableStats = Map.ofList [ 
                turns_number, 1
                fences_drawn, 0
                fences_cut, 0
                takeovers_number, 0
                biggest_takeover, 0
                freebarns_number, 0
                occupiedbarns_number, 0
                cardsplayed_number, 0
                haybales_number, 0
                dynamites_number, 0
                haybales_max_number, 0
                haybales_moved_number, 0
                helicopters_number, 0
                highvoltages_number, 0
                watchdogs_number, 0
                bribes_number, 0
                nitro1_number, 0
                nitro2_number, 0
                ruts_number, 0
                ]
              PlayerStats = Map.empty }
        { Stats = stats
          UndoPoint = stats
           }



    let diff x y =
        let sx = x |> Map.toSeq |> set
        let sy = y |> Map.toSeq |> set

        sy - sx

    let inc n name stats =
        match Map.tryFind name stats with
        | Some v -> stats |> Map.add name (v+n)
        | None -> stats |> Map.add name n

    let up f name stats =
        match Map.tryFind name stats with
        | Some v -> stats |> Map.add name (f v stats)
        | None -> stats |> Map.add name (f 0 stats)

    let getStat name stats =
        match Map.tryFind name stats with
        | Some v -> v
        | None -> 0

    let incStat n name player stats =
        match player with
        | None -> { stats with TableStats = stats.TableStats |> inc n name  }
        | Some p ->
            let ps = 
                match Map.tryFind p stats.PlayerStats with
                | Some ps -> ps
                | None -> Map.ofList 
                            [
                                fences_drawn, 0
                                fences_cut, 0
                                cut_number, 0
                                takeovers_number, 0
                                biggest_takeover, 0
                                freebarns_number, 0
                                occupiedbarns_number, 0
                                cardsplayed_number, 0
                                haybales_number, 0
                                dynamites_number, 0
                                helicopters_number, 0
                                highvoltages_number, 0
                                watchdogs_number, 0
                                bribes_number, 0
                                nitro1_number, 0
                                nitro2_number, 0
                                ruts_number, 0
                                rutted_number, 0
                            ]
            let newStats = ps |> inc n name
            { stats with
                PlayerStats = stats.PlayerStats |> Map.add p newStats }

    let updateStat f name player stats =
        match player with
        | None -> { stats with TableStats = stats.TableStats |> up f name  }
        | Some p ->
            let ps =
                match Map.tryFind p stats.PlayerStats with
                | Some ps -> ps
                | None -> Map.empty
            let newStats = ps |> up f name
            { stats with
                PlayerStats = stats.PlayerStats |> Map.add p newStats }

    let update stats e =
        match e with
        | Board.Next ->
            let newStats =
                stats.Stats 
                |> incStat 1 turns_number None
            { Stats = newStats; UndoPoint = newStats }
        | Board.Played(_, Player.Undone) ->
            { stats with Stats = stats.UndoPoint }
            
        | Board.Played(p, Player.CutFence e) ->
            let newStats = 
                stats.Stats
                |> incStat 1 fences_cut None
                |> incStat 1 fences_cut (Some p)
                |> incStat 1 cut_number (Some e.Player)
            { stats with Stats = newStats}
        | Board.Played(p, Player.FenceDrawn _) ->
            let newStats =
                stats.Stats
                |> incStat 1 fences_drawn None
                |> incStat 1 fences_drawn (Some p)
            { stats with Stats = newStats}
        | Board.Played(p, Player.Annexed e) ->
            let size = List.length e.NewField
            let freeBarns = List.length e.FreeBarns
            let occupiedBarns = List.length e.OccupiedBarns
            let newStats =
                stats.Stats
                |> incStat 1 takeovers_number None
                |> incStat 1 takeovers_number (Some p)
                |> updateStat (fun current _ -> max current size) biggest_takeover (Some p)
                |> updateStat (fun current _ -> max current size) biggest_takeover None
                |> incStat freeBarns freebarns_number None
                |> incStat freeBarns freebarns_number (Some p)
                |> incStat occupiedBarns occupiedbarns_number None
                |> incStat occupiedBarns occupiedbarns_number (Some p)
            { stats with Stats = newStats}
        | Board.Played(p, Player.CardPlayed cp )->
            let statsNew = 
                stats.Stats
                |> incStat 1 cardsplayed_number None
                |> incStat 1 cardsplayed_number (Some p)
            match cp with
            | PlayHayBale(hb,rm) ->
                let hayBales = List.length hb
                let moved = List.length rm
                let newStats = 
                    statsNew
                    |> incStat hayBales haybales_number None
                    |> incStat hayBales haybales_number (Some p)
                    |> incStat moved haybales_moved_number None
                    |> updateStat (fun current stats ->
                        let totalHayBales = getStat haybales_number stats - getStat dynamites_number stats - getStat haybales_moved_number stats
                        max current totalHayBales
                        ) haybales_max_number None
                { stats with Stats = newStats}
            | PlayDynamite _ ->
                let newStats =
                    statsNew
                    |> incStat 1 dynamites_number None
                    |> incStat 1 dynamites_number (Some p)
                    |> updateStat (fun current stats ->
                                      let totalHayBales = getStat haybales_number stats - getStat dynamites_number stats  - getStat haybales_moved_number stats
                                      max current totalHayBales
                                  ) haybales_max_number None
                { stats with Stats = newStats}
            | PlayHelicopter _ ->
                let newStats = 
                    stats.Stats
                    |> incStat 1 helicopters_number None
                    |> incStat 1 helicopters_number (Some p)
                { stats with Stats = newStats}
            | PlayHighVoltage ->
                let newStats =
                    stats.Stats
                    |> incStat 1 highvoltages_number None
                    |> incStat 1 highvoltages_number (Some p)
                { stats with Stats = newStats}
            | PlayWatchdog ->
                let newStats =
                    stats.Stats
                    |> incStat 1 watchdogs_number None
                    |> incStat 1 watchdogs_number (Some p)
                { stats with Stats = newStats}
            | PlayBribe _ ->
                let newStats =
                    stats.Stats
                    |> incStat 1 bribes_number None
                    |> incStat 1 bribes_number (Some p)
                { stats with Stats = newStats}
            | PlayNitro One ->
                let newStats =
                    stats.Stats
                    |> incStat 1 nitro1_number None
                    |> incStat 1 nitro1_number (Some p)
                { stats with Stats = newStats}
            | PlayNitro Two ->
                let newStats =
                    stats.Stats
                    |> incStat 1 nitro2_number None
                    |> incStat 1 nitro2_number (Some p)
                { stats with Stats = newStats}
            | PlayRut victim ->
                let newStats =
                    stats.Stats
                    |> incStat 1 ruts_number None
                    |> incStat 1 ruts_number (Some p)
                    |> incStat 1 rutted_number (Some victim)
                { stats with Stats = newStats}
        | Board.UndoCheckPointed ->
            { stats with UndoPoint = stats.Stats }
        | _ -> stats




let updateStats es stats (setStat: int -> string -> string option -> unit) =
    let newStats = 
        es
        |> List.fold Stats.update stats

    let tableDiffs = Stats.diff stats.Stats.TableStats newStats.Stats.TableStats
    for n,v in tableDiffs do
        setStat v n None

    for player, pstats in Map.toSeq newStats.Stats.PlayerStats do
        let existing =
            match Map.tryFind player stats.Stats.PlayerStats with
            | Some s -> s
            | None -> Map.empty

        let playerDiff = Stats.diff existing pstats 
        for n,v in playerDiff do
           setStat v n (Some player)
