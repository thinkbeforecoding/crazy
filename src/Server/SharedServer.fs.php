<?php
namespace SharedServer;

require_once(__FABLE_LIBRARY__.'/FSharp.Core.php');
require_once(__FABLE_LIBRARY__.'/List.php');
require_once(__FABLE_LIBRARY__.'/Map.php');
require_once(__FABLE_LIBRARY__.'/Seq.php');
require_once(__FABLE_LIBRARY__.'/Set.php');
require_once(__FABLE_LIBRARY__.'/String.php');
require_once(__FABLE_LIBRARY__.'/Util.php');
require_once(__ROOT__.'/Shared/SharedGame.fs.php');

use \IComparable;
use \FSharpList\Cons;
use \FSharpList\Nil;

#0
function privateGame($id, $game) {
    return new \SharedGame\PlayingBoard(\Map\map(function ($playerid, $player) use ($id) {     if ($playerid === $id) {
        return $player;
    } else {
        return \SharedGame\Player_toPrivate($player);
    }
 }, $game->Players), $game->Table, $game->DrawPile, $game->DiscardPile, $game->Barns, $game->HayBales, $game->Goal, $game->UseGameOver, $game->History);
}

#1
function privateBoard($playerId, $board) {
    switch ($board->get_Tag())
    {
        case 2:
            return new \SharedGame\Board_Won($board->Item1, privateGame($playerId, $board->Item2));
        case 0:
            return new \SharedGame\Board_InitialState();
        default:
            return new \SharedGame\Board_Board(privateGame($playerId, $board->Item));
    }
}

#2
function privateUndoableBoard($playerId, $undoboard) {
    return new \SharedGame\UndoableBoard(privateBoard($playerId, $undoboard->Board), privateBoard($playerId, $undoboard->UndoPoint), $undoboard->UndoType, $undoboard->ShouldShuffle, $undoboard->AtUndoPoint, $undoboard->Undone);
}

#3
function privateEvents($playerId, $events) {
    return \FSharpList\map(function ($_arg1) use ($playerId) {     switch ($_arg1->get_Tag())
    {
        case 3:
            $p = $_arg1->Item;
            if ($playerId === $p->Player) {
                return $_arg1;
            } else {
                return new \SharedGame\BoardEvent_PlayerDrewCards(new \SharedGame\PlayerDrewCards($p->Player, \SharedGame\HandModule_toPrivate($p->Cards)));
            }
        case 10:
            return new \SharedGame\BoardEvent_DiscardPileShuffled(\Seq\toList(\Seq\delay(function ($unitVar) use ($_arg1) {             return \Seq\map(function ($c) {             return new \SharedGame\Card_Rut();
 }, $_arg1->Item);
 })));
        case 11:
            return new \SharedGame\BoardEvent_DrawPileShuffled($GLOBALS['NIL']);
        default:
            return $_arg1;
    }
 }, $events);
}

#4
function bgaUpdateState($events, $board, $zombie, $state, $changeState, $eliminatePlayer) {
    if ($zombie) {
        return $changeState('zombiepass');
    } else {
        $enumerator = \Util\getEnumerator($events);
        try {
            while ($enumerator->MoveNext()) {
                $e = $enumerator->get_Current();
                if ($e->get_Tag() == 2) {
                    $changeState('next');
                } else {
                    if ($e->get_Tag() == 4) {
                        $changeState('endGame');
                    } else {
                        if ($e->get_Tag() == 5) {
                            $changeState('endGame');
                        } else {
                            if ($e->get_Tag() == 6) {
                                $changeState('endGame');
                            } else {
                                if ($e->get_Tag() == 0) {
                                    if ($e->Item2->get_Tag() == 0) {
                                        $changeState('selectFirstCrossroad');
                                    } else {
                                        if ($e->Item2->get_Tag() == 19) {
                                            $p = $e->Item1;
                                            $eliminatePlayer($p);
                                        } else {
                                            switch ($e->Item2->get_Tag())
                                            {
                                                case 20:
                                                    $p_1 = $e->Item1;
                                                    $matchValue = $board->Board;
                                                    if ($matchValue->get_Tag() == 1) {
                                                        $matchValue_1 = $matchValue->Item->Players->get_Item($p_1);
                                                        switch ($matchValue_1->get_Tag())
                                                        {
                                                            case 0:
                                                                $changeState('restart');
                                                                break;
                                                            case 1:
                                                                $player = $matchValue_1->Item;
                                                                if (\SharedGame\Player_canMove($p_1, $board->Board)) {
                                                                    $changeState('canMove');
                                                                } else {
                                                                    if (\SharedGame\HandModule_canPlay($player->Hand)) {
                                                                        if (\SharedGame\HandModule_shouldDiscard($player->Hand)) {
                                                                            $changeState('shouldDiscard');
                                                                        } else {
                                                                            $changeState('endTurn');
                                                                        }
                                                                    } else {
                                                                    }
                                                                }
                                                                break;
                                                            default:
                                                                break;
                                                        }
                                                    } else {
                                                    }
                                                    break;
                                                default:
                                                    $p_2 = $e->Item1;
                                                    $matchValue_2 = $board->Board;
                                                    if ($matchValue_2->get_Tag() == 1) {
                                                        $matchValue_3 = $matchValue_2->Item->Players->get_Item($p_2);
                                                        if ($matchValue_3->get_Tag() == 1) {
                                                            $player_1 = $matchValue_3->Item;
                                                            if (\SharedGame\Player_canMove($p_2, $board->Board)) {
                                                                $changeState('canMove');
                                                            } else {
                                                                if (\SharedGame\HandModule_canPlay($player_1->Hand)) {
                                                                    if (\SharedGame\HandModule_shouldDiscard($player_1->Hand)) {
                                                                        $changeState('shouldDiscard');
                                                                    } else {
                                                                        $changeState('endTurn');
                                                                    }
                                                                } else {
                                                                    $changeState('endTurn');
                                                                }
                                                            }
                                                        } else {
                                                        }
                                                    } else {
                                                    }
                                                    break;
                                            }
                                        }
                                    }
                                } else {
                                }
                            }
                        }
                    }
                }
            }
        }
        finally {
            if ($enumerator instanceof IDisposable) {
                return $enumerator->Dispose();
            } else {
                return NULL;
            }
        }
    }
}

#5
function bgaUpdateZombieState($board, $changeState) {
    $matchValue = $board->Board;
    switch ($matchValue->get_Tag())
    {
        case 1:
            $b = $matchValue->Item;
            $p = $b->Table->get_Player();
            $matchValue_1 = $b->Players->get_Item($p);
            switch ($matchValue_1->get_Tag())
            {
                case 1:
                    if (\SharedGame\Player_canMove($p, $board->Board)) {
                        return $changeState('nextPlayer');
                    } else {
                        return $changeState('nextEndTurn');
                    }
                case 2:
                    return NULL;
                default:
                    return $changeState('nextStarting');
            }
        case 2:
            return $changeState('nextPlayer');
        default:
            return NULL;
    }
}

#6
function bgaNextPlayer($board) {
    $matchValue = $board->Board;
    switch ($matchValue->get_Tag())
    {
        case 0:
            $_target__354 = 1;
            break;
        case 2:
            $_target__354 = 1;
            break;
        default:
            $_target__354 = 0;
            break;
    }
    switch ($_target__354)
    {
        case 0:
            $b = $matchValue->Item;
            if (\SharedGame\BoardModule_currentPlayer($b)->get_Tag() == 0) {
                return 'nextStarting';
            } else {
                if (\SharedGame\Player_canMove($b->Table->get_Player(), $board->Board)) {
                    return 'nextPlayer';
                } else {
                    return 'nextEndTurn';
                }
            }
        case 1:
            return '';
    }
}

#7
function bgaProgression($board) {
    $matchValue = $board->Board;
    switch ($matchValue->get_Tag())
    {
        case 2:
            return 100;
        case 1:
            $b = $matchValue->Item;
            $matchValue_1 = $b->Goal;
            switch ($matchValue_1->get_Tag())
            {
                case 1:
                    $g_1 = $matchValue_1->Item;
                    return \Util\min(function ($x_2, $y_2) {                     return \Util\comparePrimitives($x_2, $y_2);
 }, $g_1, \Seq\max(\Seq\map(function ($tupledArg) {                     return \SharedGame\Player_principalFieldSize($tupledArg[1]);
 }, \Map\toSeq($b->Players)), [ 'Compare' => function ($x_1, $y_1) {                     return \Util\comparePrimitives($x_1, $y_1);
 }])) * 100 / $g_1;
                default:
                    $g = $matchValue_1->Item;
                    return \Util\min(function ($x, $y) {                     return \Util\comparePrimitives($x, $y);
 }, $g, \SharedGame\BoardModule_totalSize($b)) * 100 / $g;
            }
        default:
            return 0;
    }
}

#8
function bgaScore($events, $board, $updateScore) {
    $matchValue = $board->Board;
    if ($matchValue->get_Tag() == 1) {
        $b = $matchValue->Item;
        $enumerator = \Util\getEnumerator(\Map\toSeq($b->Players));
        try {
            while ($enumerator->MoveNext()) {
                $forLoopVar = $enumerator->get_Current();
                $pid = $forLoopVar[0];
                if ($forLoopVar[1]->get_Tag() == 2) {
                    $updateScore([ $pid, 0, 0]);
                } else {
                    $updateScore([ $pid, 1, 0]);
                }
            }
        }
        finally {
            if ($enumerator instanceof IDisposable) {
                return $enumerator->Dispose();
            } else {
                return NULL;
            }
        }
    } else {
        if ($matchValue->get_Tag() == 2) {
            if ($matchValue->Item1 instanceof Nil) {
                $b = $matchValue->Item2;
                $enumerator = \Util\getEnumerator(\Map\toSeq($b->Players));
                try {
                    while ($enumerator->MoveNext()) {
                        $forLoopVar = $enumerator->get_Current();
                        $pid = $forLoopVar[0];
                        if ($forLoopVar[1]->get_Tag() == 2) {
                            $updateScore([ $pid, 0, 0]);
                        } else {
                            $updateScore([ $pid, 1, 0]);
                        }
                    }
                }
                finally {
                    if ($enumerator instanceof IDisposable) {
                        return $enumerator->Dispose();
                    } else {
                        return NULL;
                    }
                }
            } else {
                $b_1 = $matchValue->Item2;
                $enumerator_1 = \Util\getEnumerator($events);
                try {
                    while ($enumerator_1->MoveNext()) {
                        $e = $enumerator_1->get_Current();
                        if ($e->get_Tag() == 0) {
                            if ($e->Item2->get_Tag() == 7) {
                                $enumerator_2 = \Util\getEnumerator(\Map\toSeq($b_1->Players));
                                try {
                                    while ($enumerator_2->MoveNext()) {
                                        $forLoopVar_1 = $enumerator_2->get_Current();
                                        $player_1 = $forLoopVar_1[1];
                                        $updateScore([ $forLoopVar_1[0], \SharedGame\Player_principalFieldSize($player_1), \SharedGame\Player_fieldTotalSize($player_1)]);
                                    }
                                }
                                finally {
                                    if ($enumerator_2 instanceof IDisposable) {
                                        $enumerator_2->Dispose();
                                    } else {
                                    }
                                }
                            } else {
                                if ($e->Item2->get_Tag() == 10) {
                                    switch ($e->Item2->Item->get_Tag())
                                    {
                                        case 7:
                                            $enumerator_2 = \Util\getEnumerator(\Map\toSeq($b_1->Players));
                                            try {
                                                while ($enumerator_2->MoveNext()) {
                                                    $forLoopVar_1 = $enumerator_2->get_Current();
                                                    $player_1 = $forLoopVar_1[1];
                                                    $updateScore([ $forLoopVar_1[0], \SharedGame\Player_principalFieldSize($player_1), \SharedGame\Player_fieldTotalSize($player_1)]);
                                                }
                                            }
                                            finally {
                                                if ($enumerator_2 instanceof IDisposable) {
                                                    $enumerator_2->Dispose();
                                                } else {
                                                }
                                            }
                                            break;
                                        default:
                                            break;
                                    }
                                } else {
                                }
                            }
                        } else {
                        }
                    }
                }
                finally {
                    if ($enumerator_1 instanceof IDisposable) {
                        return $enumerator_1->Dispose();
                    } else {
                        return NULL;
                    }
                }
            }
        } else {
            return NULL;
        }
    }
}

#9
class Php {
    function __construct() {
    }
}

#10
function cardIcon($card) {
    return '<div class="cardicon"><div class="' . \SharedGame\Client_cardName($card) . '"></div></div>';
}

#11
function textAction($previous, $b, $e) {
    $matchValue = $b->Board;
    switch ($matchValue->get_Tag())
    {
        case 1:
            $board = $matchValue->Item;
            $_target__355 = 0;
            break;
        case 2:
            $board = $matchValue->Item2;
            $_target__355 = 0;
            break;
        default:
            $_target__355 = 1;
            break;
    }
    switch ($_target__355)
    {
        case 0:
            $playerName = function ($p) use ($board) { 
                $name = $board->Table->Names->get_Item($p);
                return '<span style="font-weight:bold;color:#' . (function ($matchValue_1) {                 switch ($matchValue_1->get_Tag())
                {
                    case 1:
                        return 'EFC54C';
                    case 2:
                        return 'A87BBE';
                    case 3:
                        return 'EA222F';
                    default:
                        return 'AEDBDE';
                }
 })(\SharedGame\Player_color($board->Players->get_Item($p))) . '">' . $name . '</span>';
            };
            if ($e->get_Tag() == 0) {
                if ($e->Item2->get_Tag() == 7) {
                    $e_1 = $e->Item2->Item;
                    $p_1 = $e->Item1;
                    return [ clienttranslate('${player} takes over ${parcels} parcel(s)'), \Map\ofList(new Cons([ 'player', $playerName($p_1)], new Cons([ 'parcels', \FSharpList\length($e_1->NewField)], $GLOBALS['NIL'])))];
                } else {
                    if ($e->Item2->get_Tag() == 8) {
                        $e_2 = $e->Item2->Item;
                        $p_2 = $e->Item1;
                        return [ clienttranslate('${player} cut ${cut}\'s fence'), \Map\ofList(new Cons([ 'player', $playerName($p_2)], new Cons([ 'cut', $playerName($e_2->Player)], $GLOBALS['NIL'])))];
                    } else {
                        if ($e->Item2->get_Tag() == 18) {
                            $e_4 = $e->Item2->Item;
                            $p_3 = $e->Item1;
                            return [ clienttranslate('${icon} ${player} takes one of ${bribed}\'s parcel'), \Map\ofList(new Cons([ 'player', $playerName($p_3)], new Cons([ 'bribed', $playerName($e_4->Victim)], new Cons([ 'icon', cardIcon(new \SharedGame\Card_Bribe())], $GLOBALS['NIL']))))];
                        } else {
                            if ($e->Item2->get_Tag() == 19) {
                                $p_4 = $e->Item1;
                                return [ clienttranslate('${player} is eliminated!'), \Map\ofList(new Cons([ 'player', $playerName($p_4)], $GLOBALS['NIL']))];
                            } else {
                                if ($e->Item2->get_Tag() == 10) {
                                    if ($e->Item2->Item->get_Tag() == 6) {
                                        $p_5 = $e->Item1;
                                        return [ clienttranslate('${icon} ${player} is heliported to new crossroad'), \Map\ofList(new Cons([ 'player', $playerName($p_5)], new Cons([ 'icon', cardIcon(new \SharedGame\Card_Helicopter())], $GLOBALS['NIL'])))];
                                    } else {
                                        if ($e->Item2->Item->get_Tag() == 4) {
                                            $p_6 = $e->Item1;
                                            return [ clienttranslate('${icon} ${player}\'s fence cannot be cut until next turn'), \Map\ofList(new Cons([ 'player', $playerName($p_6)], new Cons([ 'icon', cardIcon(new \SharedGame\Card_HighVoltage())], $GLOBALS['NIL'])))];
                                        } else {
                                            if ($e->Item2->Item->get_Tag() == 5) {
                                                $p_7 = $e->Item1;
                                                return [ clienttranslate('${icon} ${player} field is protected until next turn'), \Map\ofList(new Cons([ 'player', $playerName($p_7)], new Cons([ 'icon', cardIcon(new \SharedGame\Card_Watchdog())], $GLOBALS['NIL'])))];
                                            } else {
                                                if ($e->Item2->Item->get_Tag() == 1) {
                                                    $p_8 = $e->Item1;
                                                    $victim = $e->Item2->Item->victim;
                                                    return [ clienttranslate('${icon} ${player} makes ${rutted} loose 2 moves during next turn'), \Map\ofList(new Cons([ 'player', $playerName($p_8)], new Cons([ 'rutted', $playerName($victim)], new Cons([ 'icon', cardIcon(new \SharedGame\Card_Rut())], $GLOBALS['NIL']))))];
                                                } else {
                                                    if ($e->Item2->Item->get_Tag() == 2) {
                                                        $hb = $e->Item2->Item->path;
                                                        $p_9 = $e->Item1;
                                                        $pc = $e->Item2->Item;
                                                        return [ clienttranslate('${icon} ${player} blocks ${haybales} paths'), \Map\ofList(new Cons([ 'player', $playerName($p_9)], new Cons([ 'haybales', \FSharpList\length($hb)], new Cons([ 'icon', cardIcon(\SharedGame\CardModule_ofPlayCard($pc))], $GLOBALS['NIL']))))];
                                                    } else {
                                                        if ($e->Item2->Item->get_Tag() == 3) {
                                                            $p_10 = $e->Item1;
                                                            return [ clienttranslate('${icon} ${player} dynamites 1 hay bale'), \Map\ofList(new Cons([ 'player', $playerName($p_10)], new Cons([ 'icon', cardIcon(new \SharedGame\Card_Dynamite())], $GLOBALS['NIL'])))];
                                                        } else {
                                                            switch ($e->Item2->Item->get_Tag())
                                                            {
                                                                case 0:
                                                                    $p_11 = $e->Item1;
                                                                    $power = $e->Item2->Item->power;
                                                                    return [ clienttranslate('${icon} ${player} get ${nitro} extra move(s)'), \Map\ofList(new Cons([ 'player', $playerName($p_11)], new Cons([ 'nitro', ($power->get_Tag() == 1 ? 2 : 1)], new Cons([ 'icon', cardIcon(new \SharedGame\Card_Nitro($power))], $GLOBALS['NIL']))))];
                                                                default:
                                                                    return [ NULL, \Map\_empty()];
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    switch ($e->Item2->get_Tag())
                                    {
                                        case 20:
                                            $p_12 = $e->Item1;
                                            return [ clienttranslate('${player} undo last moves'), \Map\ofList(new Cons([ 'player', $playerName($p_12)], $GLOBALS['NIL']))];
                                        default:
                                            return [ NULL, \Map\_empty()];
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                switch ($e->get_Tag())
                {
                    case 3:
                        $e_3 = $e->Item;
                        if (\SharedGame\HandModule_contains(new \SharedGame\Card_GameOver(), $e_3->Cards)) {
                            return [ clienttranslate('${icon} ${player} draws the Game Over card. The game ends now!'), \Map\ofList(new Cons([ 'player', $playerName($e_3->Player)], new Cons([ 'icon', cardIcon(new \SharedGame\Card_GameOver())], $GLOBALS['NIL'])))];
                        } else {
                            return [ clienttranslate('${player} draws ${cardcount} card(s)'), \Map\ofList(new Cons([ 'player', $playerName($e_3->Player)], new Cons([ 'cardcount', \SharedGame\HandModule_count($e_3->Cards)], $GLOBALS['NIL'])))];
                        }
                    case 2:
                        $matchValue_2 = $previous->Board;
                        if ($matchValue_2->get_Tag() == 1) {
                            $player_1 = $matchValue_2->Item->Table->get_Player();
                        } else {
                            $player_1 = '';
                        }
                        $matchValue_3 = \Map\tryFind($player_1, $board->Players);
                        if (!is_null($matchValue_3)) {
                            switch ($matchValue_3->get_Tag())
                            {
                                case 1:
                                    $p_13 = $matchValue_3->Item;
                                    return [ clienttranslate('${player} ends turn after ${moves} moves'), \Map\ofList(new Cons([ 'player', $playerName($player_1)], new Cons([ 'moves', $p_13->Moves->Done], $GLOBALS['NIL'])))];
                                default:
                                    return [ NULL, \Map\_empty()];
                            }
                        } else {
                            return [ NULL, \Map\_empty()];
                        }
                    case 4:
                        $p_14 = $e->Item;
                        return [ clienttranslate('${player} wins the game!'), \Map\ofList(new Cons([ 'player', $playerName($p_14)], $GLOBALS['NIL']))];
                    case 5:
                        $ps = $e->Item;
                        return [ clienttranslate('${players} end the game in a tie!'), \Map\ofList(new Cons([ 'players', \String\join(' / ', \Seq\toArray(\Seq\delay(function ($unitVar) use ($playerName, $ps) {                         return \Seq\map(function ($p_15) use ($playerName) {                         return $playerName($p_15);
 }, $ps);
 })))], $GLOBALS['NIL']))];
                    case 6:
                        return [ clienttranslate('After being 3 times in the same state, the game ended in a tie!'), \Map\_empty()];
                    case 7:
                        $player_2 = $e->player;
                        return [ clienttranslate('At the end of ${player}\'s turn, the board was in the exact same position as a previous turn.'), \Map\ofList(new Cons([ 'player', $playerName($player_2)], $GLOBALS['NIL']))];
                    default:
                        return [ NULL, \Map\_empty()];
                }
            }
        case 1:
            return [ NULL, \Map\_empty()];
    }
}

#12
class Stats implements IComparable {
    public $TableStats;
    public $PlayerStats;
    function __construct($TableStats, $PlayerStats) {
        $this->TableStats = $TableStats;
        $this->PlayerStats = $PlayerStats;
    }
    function CompareTo($other) {
        $_cmp__356 = $this->TableStats->CompareTo($other->TableStats);
        if ($_cmp__356 != 0) {
            return $_cmp__356;
        }        
        $_cmp__357 = $this->PlayerStats->CompareTo($other->PlayerStats);
        if ($_cmp__357 != 0) {
            return $_cmp__357;
        }        
        return 0;
    }
}

#13
class UndoableStats implements IComparable {
    public $Stats;
    public $UndoPoint;
    function __construct($Stats, $UndoPoint) {
        $this->Stats = $Stats;
        $this->UndoPoint = $UndoPoint;
    }
    function CompareTo($other) {
        $_cmp__358 = $this->Stats->CompareTo($other->Stats);
        if ($_cmp__358 != 0) {
            return $_cmp__358;
        }        
        $_cmp__359 = $this->UndoPoint->CompareTo($other->UndoPoint);
        if ($_cmp__359 != 0) {
            return $_cmp__359;
        }        
        return 0;
    }
}

#14
$GLOBALS['StatsModule_turns_number'] = 'turns_number';

#15
$GLOBALS['StatsModule_fences_drawn'] = 'fences_drawn';

#16
$GLOBALS['StatsModule_fences_cut'] = 'fences_cut';

#17
$GLOBALS['StatsModule_cut_number'] = 'cut_number';

#18
$GLOBALS['StatsModule_takeovers_number'] = 'takeovers_number';

#19
$GLOBALS['StatsModule_biggest_takeover'] = 'biggest_takeover';

#20
$GLOBALS['StatsModule_freebarns_number'] = 'freebarns_number';

#21
$GLOBALS['StatsModule_occupiedbarns_number'] = 'occupiedbarns_number';

#22
$GLOBALS['StatsModule_cardsplayed_number'] = 'cardsplayed_number';

#23
$GLOBALS['StatsModule_haybales_max_number'] = 'haybales_max_number';

#24
$GLOBALS['StatsModule_haybales_number'] = 'haybales_number';

#25
$GLOBALS['StatsModule_dynamites_number'] = 'dynamites_number';

#26
$GLOBALS['StatsModule_haybales_moved_number'] = 'haybales_moved_number';

#27
$GLOBALS['StatsModule_helicopters_number'] = 'helicopters_number';

#28
$GLOBALS['StatsModule_highvoltages_number'] = 'highvoltages_number';

#29
$GLOBALS['StatsModule_watchdogs_number'] = 'watchdogs_number';

#30
$GLOBALS['StatsModule_bribes_number'] = 'bribes_number';

#31
$GLOBALS['StatsModule_nitro1_number'] = 'nitro1_number';

#32
$GLOBALS['StatsModule_nitro2_number'] = 'nitro2_number';

#33
$GLOBALS['StatsModule_ruts_number'] = 'ruts_number';

#34
$GLOBALS['StatsModule_rutted_number'] = 'rutted_number';

#35
$GLOBALS['StatsModule_repetition_number'] = 'repetition_number';

#36
$GLOBALS['StatsModule_draw_number'] = 'draw_number';

#37
$GLOBALS['StatsModule_empty'] = (function ($stats) { return new UndoableStats($stats, $stats);
 })(new Stats(\Map\ofList(new Cons([ $GLOBALS['StatsModule_turns_number'], 1], new Cons([ $GLOBALS['StatsModule_fences_drawn'], 0], new Cons([ $GLOBALS['StatsModule_fences_cut'], 0], new Cons([ $GLOBALS['StatsModule_takeovers_number'], 0], new Cons([ $GLOBALS['StatsModule_biggest_takeover'], 0], new Cons([ $GLOBALS['StatsModule_freebarns_number'], 0], new Cons([ $GLOBALS['StatsModule_occupiedbarns_number'], 0], new Cons([ $GLOBALS['StatsModule_cardsplayed_number'], 0], new Cons([ $GLOBALS['StatsModule_haybales_number'], 0], new Cons([ $GLOBALS['StatsModule_dynamites_number'], 0], new Cons([ $GLOBALS['StatsModule_haybales_max_number'], 0], new Cons([ $GLOBALS['StatsModule_haybales_moved_number'], 0], new Cons([ $GLOBALS['StatsModule_helicopters_number'], 0], new Cons([ $GLOBALS['StatsModule_highvoltages_number'], 0], new Cons([ $GLOBALS['StatsModule_watchdogs_number'], 0], new Cons([ $GLOBALS['StatsModule_bribes_number'], 0], new Cons([ $GLOBALS['StatsModule_nitro1_number'], 0], new Cons([ $GLOBALS['StatsModule_nitro2_number'], 0], new Cons([ $GLOBALS['StatsModule_ruts_number'], 0], new Cons([ $GLOBALS['StatsModule_repetition_number'], 0], new Cons([ $GLOBALS['StatsModule_draw_number'], 0], $GLOBALS['NIL'])))))))))))))))))))))), \Map\_empty()));

#38
function StatsModule_diff($x, $y) {
    $sx = \Set\ofSeq(\Map\toSeq($x), [ 'Compare' => function ($x_1, $y_1) {     return \Util\compareArrays($x_1, $y_1);
 }]);
    return \Set\FSharpSet_op_Subtraction(\Set\ofSeq(\Map\toSeq($y), [ 'Compare' => function ($x_2, $y_2) {     return \Util\compareArrays($x_2, $y_2);
 }]), $sx);
}

#39
function StatsModule_inc($n, $name, $stats) {
    $matchValue = \Map\tryFind($name, $stats);
    if (is_null($matchValue)) {
        return \Map\add($name, $n, $stats);
    } else {
        return \Map\add($name, $matchValue + $n, $stats);
    }
}

#40
function StatsModule_up($f, $name, $stats) {
    $matchValue = \Map\tryFind($name, $stats);
    if (is_null($matchValue)) {
        return \Map\add($name, $f(0, $stats), $stats);
    } else {
        return \Map\add($name, $f($matchValue, $stats), $stats);
    }
}

#41
function StatsModule_getStat($name, $stats) {
    $matchValue = \Map\tryFind($name, $stats);
    if (is_null($matchValue)) {
        return 0;
    } else {
        return $matchValue;
    }
}

#42
function StatsModule_incStat($n, $name, $player, $stats) {
    if (!is_null($player)) {
        $p = $player;
        return new Stats($stats->TableStats, \Map\add($p, StatsModule_inc($n, $name, (function ($matchValue) {         if (is_null($matchValue)) {
            return \Map\ofList(new Cons([ $GLOBALS['StatsModule_fences_drawn'], 0], new Cons([ $GLOBALS['StatsModule_fences_cut'], 0], new Cons([ $GLOBALS['StatsModule_cut_number'], 0], new Cons([ $GLOBALS['StatsModule_takeovers_number'], 0], new Cons([ $GLOBALS['StatsModule_biggest_takeover'], 0], new Cons([ $GLOBALS['StatsModule_freebarns_number'], 0], new Cons([ $GLOBALS['StatsModule_occupiedbarns_number'], 0], new Cons([ $GLOBALS['StatsModule_cardsplayed_number'], 0], new Cons([ $GLOBALS['StatsModule_haybales_number'], 0], new Cons([ $GLOBALS['StatsModule_dynamites_number'], 0], new Cons([ $GLOBALS['StatsModule_helicopters_number'], 0], new Cons([ $GLOBALS['StatsModule_highvoltages_number'], 0], new Cons([ $GLOBALS['StatsModule_watchdogs_number'], 0], new Cons([ $GLOBALS['StatsModule_bribes_number'], 0], new Cons([ $GLOBALS['StatsModule_nitro1_number'], 0], new Cons([ $GLOBALS['StatsModule_nitro2_number'], 0], new Cons([ $GLOBALS['StatsModule_ruts_number'], 0], new Cons([ $GLOBALS['StatsModule_rutted_number'], 0], $GLOBALS['NIL'])))))))))))))))))));
        } else {
            return $matchValue;
        }
 })(\Map\tryFind($p, $stats->PlayerStats))), $stats->PlayerStats));
    } else {
        return new Stats(StatsModule_inc($n, $name, $stats->TableStats), $stats->PlayerStats);
    }
}

#43
function StatsModule_updateStat($f, $name, $player, $stats) {
    if (!is_null($player)) {
        $p = $player;
        return new Stats($stats->TableStats, \Map\add($p, StatsModule_up($f, $name, (function ($matchValue) {         if (is_null($matchValue)) {
            return \Map\_empty();
        } else {
            return $matchValue;
        }
 })(\Map\tryFind($p, $stats->PlayerStats))), $stats->PlayerStats));
    } else {
        return new Stats(StatsModule_up($f, $name, $stats->TableStats), $stats->PlayerStats);
    }
}

#44
function StatsModule_update($stats, $e) {
    if ($e->get_Tag() == 2) {
        $newStats = StatsModule_incStat(1, $GLOBALS['StatsModule_turns_number'], NULL, $stats->Stats);
        return new UndoableStats($newStats, $newStats);
    } else {
        if ($e->get_Tag() == 7) {
            $p = $e->player;
            return new UndoableStats(StatsModule_incStat(1, $GLOBALS['StatsModule_repetition_number'], $p, StatsModule_incStat(1, $GLOBALS['StatsModule_repetition_number'], NULL, $stats->Stats)), $stats->UndoPoint);
        } else {
            if ($e->get_Tag() == 6) {
                return new UndoableStats(StatsModule_incStat(1, $GLOBALS['StatsModule_draw_number'], NULL, $stats->Stats), $stats->UndoPoint);
            } else {
                if ($e->get_Tag() == 12) {
                    return new UndoableStats($stats->Stats, $stats->Stats);
                } else {
                    if ($e->get_Tag() == 0) {
                        if ($e->Item2->get_Tag() == 20) {
                            return new UndoableStats($stats->UndoPoint, $stats->UndoPoint);
                        } else {
                            if ($e->Item2->get_Tag() == 8) {
                                $e_1 = $e->Item2->Item;
                                $p_1 = $e->Item1;
                                return new UndoableStats(StatsModule_incStat(1, $GLOBALS['StatsModule_cut_number'], $e_1->Player, StatsModule_incStat(1, $GLOBALS['StatsModule_fences_cut'], $p_1, StatsModule_incStat(1, $GLOBALS['StatsModule_fences_cut'], NULL, $stats->Stats))), $stats->UndoPoint);
                            } else {
                                if ($e->Item2->get_Tag() == 1) {
                                    $p_2 = $e->Item1;
                                    return new UndoableStats(StatsModule_incStat(1, $GLOBALS['StatsModule_fences_drawn'], $p_2, StatsModule_incStat(1, $GLOBALS['StatsModule_fences_drawn'], NULL, $stats->Stats)), $stats->UndoPoint);
                                } else {
                                    if ($e->Item2->get_Tag() == 7) {
                                        $e_2 = $e->Item2->Item;
                                        $p_3 = $e->Item1;
                                        $size = \FSharpList\length($e_2->NewField);
                                        $freeBarns = \FSharpList\length($e_2->FreeBarns);
                                        $occupiedBarns = \FSharpList\length($e_2->OccupiedBarns);
                                        return new UndoableStats(StatsModule_incStat($occupiedBarns, $GLOBALS['StatsModule_occupiedbarns_number'], $p_3, StatsModule_incStat($occupiedBarns, $GLOBALS['StatsModule_occupiedbarns_number'], NULL, StatsModule_incStat($freeBarns, $GLOBALS['StatsModule_freebarns_number'], $p_3, StatsModule_incStat($freeBarns, $GLOBALS['StatsModule_freebarns_number'], NULL, StatsModule_updateStat(function ($current_1, $_arg2) use ($size) {                                         return \Util\max(function ($x_1, $y_1) {                                         return \Util\comparePrimitives($x_1, $y_1);
 }, $current_1, $size);
 }, $GLOBALS['StatsModule_biggest_takeover'], NULL, StatsModule_updateStat(function ($current, $_arg1) use ($size) {                                         return \Util\max(function ($x, $y) {                                         return \Util\comparePrimitives($x, $y);
 }, $current, $size);
 }, $GLOBALS['StatsModule_biggest_takeover'], $p_3, StatsModule_incStat(1, $GLOBALS['StatsModule_takeovers_number'], $p_3, StatsModule_incStat(1, $GLOBALS['StatsModule_takeovers_number'], NULL, $stats->Stats)))))))), $stats->UndoPoint);
                                    } else {
                                        switch ($e->Item2->get_Tag())
                                        {
                                            case 10:
                                                $cp = $e->Item2->Item;
                                                $p_4 = $e->Item1;
                                                $statsNew = StatsModule_incStat(1, $GLOBALS['StatsModule_cardsplayed_number'], $p_4, StatsModule_incStat(1, $GLOBALS['StatsModule_cardsplayed_number'], NULL, $stats->Stats));
                                                if ($cp->get_Tag() == 3) {
                                                    return new UndoableStats(StatsModule_updateStat(function ($current_3, $stats_27) {                                                     return \Util\max(function ($x_3, $y_3) {                                                     return \Util\comparePrimitives($x_3, $y_3);
 }, $current_3, StatsModule_getStat($GLOBALS['StatsModule_haybales_number'], $stats_27) - StatsModule_getStat($GLOBALS['StatsModule_dynamites_number'], $stats_27) - StatsModule_getStat($GLOBALS['StatsModule_haybales_moved_number'], $stats_27));
 }, $GLOBALS['StatsModule_haybales_max_number'], NULL, StatsModule_incStat(1, $GLOBALS['StatsModule_dynamites_number'], $p_4, StatsModule_incStat(1, $GLOBALS['StatsModule_dynamites_number'], NULL, $statsNew))), $stats->UndoPoint);
                                                } else {
                                                    if ($cp->get_Tag() == 6) {
                                                        return new UndoableStats(StatsModule_incStat(1, $GLOBALS['StatsModule_helicopters_number'], $p_4, StatsModule_incStat(1, $GLOBALS['StatsModule_helicopters_number'], NULL, $statsNew)), $stats->UndoPoint);
                                                    } else {
                                                        if ($cp->get_Tag() == 4) {
                                                            return new UndoableStats(StatsModule_incStat(1, $GLOBALS['StatsModule_highvoltages_number'], $p_4, StatsModule_incStat(1, $GLOBALS['StatsModule_highvoltages_number'], NULL, $statsNew)), $stats->UndoPoint);
                                                        } else {
                                                            if ($cp->get_Tag() == 5) {
                                                                return new UndoableStats(StatsModule_incStat(1, $GLOBALS['StatsModule_watchdogs_number'], $p_4, StatsModule_incStat(1, $GLOBALS['StatsModule_watchdogs_number'], NULL, $statsNew)), $stats->UndoPoint);
                                                            } else {
                                                                if ($cp->get_Tag() == 7) {
                                                                    return new UndoableStats(StatsModule_incStat(1, $GLOBALS['StatsModule_bribes_number'], $p_4, StatsModule_incStat(1, $GLOBALS['StatsModule_bribes_number'], NULL, $statsNew)), $stats->UndoPoint);
                                                                } else {
                                                                    if ($cp->get_Tag() == 0) {
                                                                        switch ($cp->power->get_Tag())
                                                                        {
                                                                            case 1:
                                                                                return new UndoableStats(StatsModule_incStat(1, $GLOBALS['StatsModule_nitro2_number'], $p_4, StatsModule_incStat(1, $GLOBALS['StatsModule_nitro2_number'], NULL, $statsNew)), $stats->UndoPoint);
                                                                            default:
                                                                                return new UndoableStats(StatsModule_incStat(1, $GLOBALS['StatsModule_nitro1_number'], $p_4, StatsModule_incStat(1, $GLOBALS['StatsModule_nitro1_number'], NULL, $statsNew)), $stats->UndoPoint);
                                                                        }
                                                                    } else {
                                                                        switch ($cp->get_Tag())
                                                                        {
                                                                            case 1:
                                                                                $victim = $cp->victim;
                                                                                return new UndoableStats(StatsModule_incStat(1, $GLOBALS['StatsModule_rutted_number'], $victim, StatsModule_incStat(1, $GLOBALS['StatsModule_ruts_number'], $p_4, StatsModule_incStat(1, $GLOBALS['StatsModule_ruts_number'], NULL, $statsNew))), $stats->UndoPoint);
                                                                            case 8:
                                                                                return $stats;
                                                                            default:
                                                                                $hb = $cp->path;
                                                                                $rm = $cp->moved;
                                                                                $hayBales = \FSharpList\length($hb);
                                                                                return new UndoableStats(StatsModule_updateStat(function ($current_2, $stats_23) {                                                                                 return \Util\max(function ($x_2, $y_2) {                                                                                 return \Util\comparePrimitives($x_2, $y_2);
 }, $current_2, StatsModule_getStat($GLOBALS['StatsModule_haybales_number'], $stats_23) - StatsModule_getStat($GLOBALS['StatsModule_dynamites_number'], $stats_23) - StatsModule_getStat($GLOBALS['StatsModule_haybales_moved_number'], $stats_23));
 }, $GLOBALS['StatsModule_haybales_max_number'], NULL, StatsModule_incStat(\FSharpList\length($rm), $GLOBALS['StatsModule_haybales_moved_number'], NULL, StatsModule_incStat($hayBales, $GLOBALS['StatsModule_haybales_number'], $p_4, StatsModule_incStat($hayBales, $GLOBALS['StatsModule_haybales_number'], NULL, $statsNew)))), $stats->UndoPoint);
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            default:
                                                return $stats;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        return $stats;
                    }
                }
            }
        }
    }
}

#45
function updateStats($es, $stats, $setStat) {
    $newStats = \FSharpList\fold(function ($stats_1, $e) {     return StatsModule_update($stats_1, $e);
 }, $stats, $es);
    $enumerator = \Util\getEnumerator(StatsModule_diff($stats->Stats->TableStats, $newStats->Stats->TableStats));
    try {
        while ($enumerator->MoveNext()) {
            $forLoopVar = $enumerator->get_Current();
            $setStat($forLoopVar[1], $forLoopVar[0], NULL);
        }
    }
    finally {
        if ($enumerator instanceof IDisposable) {
            $enumerator->Dispose();
        } else {
        }
    }
    $enumerator_1 = \Util\getEnumerator(\Map\toSeq($newStats->Stats->PlayerStats));
    try {
        while ($enumerator_1->MoveNext()) {
            $forLoopVar_1 = $enumerator_1->get_Current();
            $player = $forLoopVar_1[0];
            $enumerator_2 = \Util\getEnumerator(StatsModule_diff((function ($matchValue) {             if (is_null($matchValue)) {
                return \Map\_empty();
            } else {
                return $matchValue;
            }
 })(\Map\tryFind($player, $stats->Stats->PlayerStats)), $forLoopVar_1[1]));
            try {
                while ($enumerator_2->MoveNext()) {
                    $forLoopVar_2 = $enumerator_2->get_Current();
                    $setStat($forLoopVar_2[1], $forLoopVar_2[0], $player);
                }
            }
            finally {
                if ($enumerator_2 instanceof IDisposable) {
                    $enumerator_2->Dispose();
                } else {
                }
            }
        }
    }
    finally {
        if ($enumerator_1 instanceof IDisposable) {
            return $enumerator_1->Dispose();
        } else {
            return NULL;
        }
    }
}

