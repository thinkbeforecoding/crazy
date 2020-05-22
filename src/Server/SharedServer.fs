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


let privateEvents playerId events =
    events
    |> List.map (function
    | Board.PlayerDrewCards p as e -> 
        if playerId = p.Player then
            e
        else
            Board.PlayerDrewCards { Player = p.Player; Cards = Hand.toPrivate p.Cards }
            
    | Board.DiscardPileShuffled _ -> Board.DiscardPileShuffled []
    | e -> e )

let bgaUpdateState events board state changeState =
    for e in events do
        match e with
        | Board.Next -> changeState "next"
        | Board.GameWon _ -> changeState "endGame"
        | Board.Played(_,Player.FirstCrossroadSelected _) ->
            changeState "selectFirstCrossroad"
        | Board.Played(p, Player.MovedInField _)
        | Board.Played(p, Player.MovedPowerless _)
        | Board.Played(p, Player.FenceDrawn _) ->
            match board with
            | Board.Board b ->
                match b.Players.[p] with
                | Playing player ->
                    if Moves.canMove player.Moves then
                        changeState "move"
                    elif Hand.canPlay player.Hand then
                        if state = "endTurn" then
                            changeState "next"
                        else
                            changeState "endTurn"
                | _ -> ()
            | _ -> ()
        | Board.Played(p, Player.CardPlayed _) ->
            match board with
            | Board.Board b ->
                match b.Players.[p] with
                | Playing player ->
                    if Moves.canMove player.Moves then
                        if state = "endTurn" then
                            changeState "moreMoves"
                        else
                            changeState "playCard"
                    elif Hand.canPlay player.Hand then
                        changeState "playCard"
                | _ -> ()
            | _ -> ()
        | _ -> ()


let bgaNextPlayer board =
    match board with
    | Board b ->
        match Board.currentPlayer b with
        | Starting _ -> "nextStarting"
        | _ -> "nextPlayer"
    | InitialState _ 
    | Won _ -> ""

let bgaProgression board =
    match board with
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
                

let bgaScore events board updateScore =
    match board with
    | Board b ->
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


let textAction b es = 
    match b with
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

        let notifs =
            [ for e in es do 
                match e with
                | Board.Played (p, Player.Annexed(e)) ->
                    Php.clienttranslate "${player} takes over ${parcels} parcel(s)", Map.ofList  [ "player" ==> playerName p; "parcels" ==> List.length e.NewField ]
                | Board.Played(p, Player.Event.CutFence e) ->
                    Php.clienttranslate "${player} cut ${cut}'s fence",  Map.ofList [ "player" ==> playerName p; "cut" ==> e.Player ]
                | Board.PlayerDrewCards e ->
                    Php.clienttranslate "and draws ${cardcount} card(s)", Map.ofList [ "cardcount" ==> Hand.count e.Cards]
                | Board.Played(p, Player.Event.Bribed e) ->
                    Php.clienttranslate "Bribe: ${player} takes one of ${bribed}'s parcel", Map.ofList ["player" ==> playerName p; "bribed" ==> playerName e.Victim]
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
                | Board.Played(p, Player.CardPlayed(PlayHayBale hb  as pc) ) ->
                    Php.clienttranslate "${icon} ${player} blocks ${haybales} paths", Map.ofList ["player" ==> playerName p; "haybales" ==> List.length hb; "icon" ==> cardIcon (Card.ofPlayCard pc) ]
                | Board.Played(p, Player.CardPlayed(PlayDynamite _)) ->
                    Php.clienttranslate "${icon} ${player} dynamites 1 hay bale", Map.ofList ["player" ==> playerName p; "icon" ==> cardIcon Dynamite ]
                | Board.Played(p, Player.CardPlayed(PlayNitro power)) ->
                    Php.clienttranslate "${icon} ${player} get ${nitro} extra move(s)", Map.ofList ["player" ==> playerName p; "nitro" ==> (match power with One -> 1 | Two -> 2); "icon" ==> cardIcon (Nitro power) ]
                | _ -> ()
                ]
        let text = notifs |> List.map (fun (t,_) -> t) |> List.toArray |> String.concat ", "
        let map = notifs |> List.fold (fun acc (_,m) -> m |> Map.fold (fun m2 k v -> Map.add k v m2) acc) Map.empty
        text, map
    | _ ->
        "", Map.empty


module Stats =
    let turns_number = "turns_number"
    let fences_drawn = "fences_drawn"
    let fences_cut = "fences_cut"
    let cut_number = "cut_number"

let updateStats es (incStat: int -> string -> string option -> unit) =
    for e in es do
        match e with
        | Board.Next ->
            incStat 1 Stats.turns_number None
        | Board.Played(p, Player.CutFence e) ->
            incStat 1 Stats.fences_cut None
            incStat 1 Stats.fences_cut (Some p)
            incStat 1 Stats.cut_number (Some e.Player)
        | Board.Played(p, Player.FenceDrawn _) ->
            incStat 1 Stats.fences_drawn None
            incStat 1 Stats.fences_drawn (Some p)

        | _ -> ()

