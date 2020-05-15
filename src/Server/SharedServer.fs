module SharedServer

open Shared

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

