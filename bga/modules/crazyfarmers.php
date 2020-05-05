<?php
#0
abstract class Color {
}

#0
class Color_Blue extends Color {
    function __construct() {
    }
}

#0
class Color_Yellow extends Color {
    function __construct() {
    }
}

#0
class Color_Purple extends Color {
    function __construct() {
    }
}

#0
class Color_Red extends Color {
    function __construct() {
    }
}

#1
abstract class Goal {
}

#1
class Goal_Common extends Goal {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#1
class Goal_Individual extends Goal {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#0
class Axe {
    public $q;
    public $r;
    function __construct($q, $r) {
        $this->q = $q;
        $this->r = $r;
    }
}

#1
function Shared_002EAxe___op_Addition__2BE35040($_arg1, $_arg2) {
    return new Axe($_arg1->q + $_arg2->q, $_arg1->r + $_arg2->r);
}

#2
function Shared_002EAxe___op_Multiply__Z425F7B5E($a, $_arg3) {
    return new Axe($_arg3->q * $a, $_arg3->r * $a);
}

#3
function Shared_002EAxe__get_Q($this_, $unitVar1) {
    return $this_->q;
}

#4
function Shared_002EAxe__get_R($this___1, $unitVar1__1) {
    return $this___1->r;
}

#5
$GLOBALS['Shared_002EAxeModule___N'] = new Axe(0, -1);

#6
$GLOBALS['Shared_002EAxeModule___S'] = new Axe(0, 1);

#7
$GLOBALS['Shared_002EAxeModule___NW'] = new Axe(-1, 0);

#8
$GLOBALS['Shared_002EAxeModule___NE'] = new Axe(1, -1);

#9
$GLOBALS['Shared_002EAxeModule___SW'] = new Axe(-1, 1);

#10
$GLOBALS['Shared_002EAxeModule___SE'] = new Axe(1, 0);

#11
$GLOBALS['Shared_002EAxeModule___W2'] = Shared_002EAxe___op_Addition__2BE35040($GLOBALS['Shared_002EAxeModule___NW'], $GLOBALS['Shared_002EAxeModule___SW']);

#12
$GLOBALS['Shared_002EAxeModule___E2'] = Shared_002EAxe___op_Addition__2BE35040($GLOBALS['Shared_002EAxeModule___NE'], $GLOBALS['Shared_002EAxeModule___SE']);

#13
$GLOBALS['Shared_002EAxeModule___center'] = new Axe(0, 0);

#14
function Shared_002EAxeModule___cube($_arg1__1) {
    return [ $_arg1__1->q, $_arg1__1->r, -$_arg1__1->q - $_arg1__1->r];
}

#15
abstract class CrossroadSide {
}

#15
class CrossroadSide_CLeft extends CrossroadSide {
    function __construct() {
    }
}

#15
class CrossroadSide_CRight extends CrossroadSide {
    function __construct() {
    }
}

#16
class Crossroad {
    public $tile;
    public $side;
    function __construct($tile, $side) {
        $this->tile = $tile;
        $this->side = $side;
    }
}

#17
abstract class BorderSide {
}

#17
class BorderSide_BNW extends BorderSide {
    function __construct() {
    }
}

#17
class BorderSide_BN extends BorderSide {
    function __construct() {
    }
}

#17
class BorderSide_BNE extends BorderSide {
    function __construct() {
    }
}

#18
class Path {
    public $tile;
    public $border;
    function __construct($tile, $border) {
        $this->tile = $tile;
        $this->border = $border;
    }
}

#19
abstract class Direction {
}

#19
class Direction_Up extends Direction {
    function __construct() {
    }
}

#19
class Direction_Down extends Direction {
    function __construct() {
    }
}

#19
class Direction_Horizontal extends Direction {
    function __construct() {
    }
}

#20
class Parcel {
    public $tile;
    function __construct($tile) {
        $this->tile = $tile;
    }
}

#21
function Shared_002EParcel___op_Addition__ZF6EFE4B($_arg1__2, $v) {
    return new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__2->tile, $v));
}

#22
class Field {
    public $parcels;
    function __construct($parcels) {
        $this->parcels = $parcels;
    }
}

#23
function Shared_002EField___op_Addition__Z24735800($_arg1__3, $_arg2__1) {
    return new Field(Set::FSharpSet___op_Addition($_arg1__3->parcels, $_arg2__1->parcels));
}

#24
function Shared_002EField___op_Subtraction__Z24735800($_arg3__1, $_arg4) {
    return new Field(Set::FSharpSet___op_Subtraction($_arg3__1->parcels, $_arg4->parcels));
}

#25
class Fence {
    public $paths;
    function __construct($paths) {
        $this->paths = $paths;
    }
}

#26
class Barns {
    public $Free;
    public $Occupied;
    function __construct($Free, $Occupied) {
        $this->Free = $Free;
        $this->Occupied = $Occupied;
    }
}

#27
function Shared_002EDirectionModule___rev($_arg1__4) {
    switch (get_class($_arg1__4))
    {
        case 'Direction_Down':
            return new Direction_Up();
        case 'Direction_Horizontal':
            return new Direction_Horizontal();
        default:
            return new Direction_Down();
    }
}

#28
abstract class Power {
}

#28
class Power_PowerUp extends Power {
    function __construct() {
    }
}

#28
class Power_PowerDown extends Power {
    function __construct() {
    }
}

#29
abstract class Card {
}

#29
class Card_Nitro extends Card {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
}

#29
class Card_Rut extends Card {
    function __construct() {
    }
}

#29
class Card_HayBale extends Card {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
}

#29
class Card_Dynamite extends Card {
    function __construct() {
    }
}

#29
class Card_HighVoltage extends Card {
    function __construct() {
    }
}

#29
class Card_Watchdog extends Card {
    function __construct() {
    }
}

#29
class Card_Helicopter extends Card {
    function __construct() {
    }
}

#29
class Card_Bribe extends Card {
    function __construct() {
    }
}

#30
abstract class CardPower {
}

#30
class CardPower_One extends CardPower {
    function __construct() {
    }
}

#30
class CardPower_Two extends CardPower {
    function __construct() {
    }
}

#31
abstract class PlayCard {
}

#31
class PlayCard_PlayNitro extends PlayCard {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
}

#31
class PlayCard_PlayRut extends PlayCard {
    public $victim;
    function __construct($victim) {
        $this->victim = $victim;
    }
}

#31
class PlayCard_PlayHayBale extends PlayCard {
    public $path;
    function __construct($path) {
        $this->path = $path;
    }
}

#31
class PlayCard_PlayDynamite extends PlayCard {
    public $path;
    function __construct($path) {
        $this->path = $path;
    }
}

#31
class PlayCard_PlayHighVoltage extends PlayCard {
    function __construct() {
    }
}

#31
class PlayCard_PlayWatchdog extends PlayCard {
    function __construct() {
    }
}

#31
class PlayCard_PlayHelicopter extends PlayCard {
    public $destination;
    function __construct($destination) {
        $this->destination = $destination;
    }
}

#31
class PlayCard_PlayBribe extends PlayCard {
    public $parcel;
    function __construct($parcel) {
        $this->parcel = $parcel;
    }
}

#32
function Shared_002ECardModule___ofPlayCard($_arg1__5) {
    switch (get_class($_arg1__5))
    {
        case 'PlayCard_PlayRut':
            return new Card_Rut();
        case 'PlayCard_PlayHayBale':
            if (FSharpList::length($_arg1__5->path) < 2) {
                return new Card_HayBale(new CardPower_One());
            } else {
                return new Card_HayBale(new CardPower_Two());
            }
        case 'PlayCard_PlayDynamite':
            return new Card_Dynamite();
        case 'PlayCard_PlayHighVoltage':
            return new Card_HighVoltage();
        case 'PlayCard_PlayWatchdog':
            return new Card_Watchdog();
        case 'PlayCard_PlayHelicopter':
            return new Card_Helicopter();
        case 'PlayCard_PlayBribe':
            return new Card_Bribe();
        default:
            return new Card_Nitro($_arg1__5->power);
    }
}

#33
abstract class Hand {
}

#33
class Hand_PrivateHand extends Hand {
    public $cards;
    function __construct($cards) {
        $this->cards = $cards;
    }
}

#33
class Hand_PublicHand extends Hand {
    public $cards;
    function __construct($cards) {
        $this->cards = $cards;
    }
}

#34
$GLOBALS['Shared_002EHandModule___empty'] = new Hand_PrivateHand(0);

#35
function Shared_002EHandModule___isEmpty($_arg1__6) {
    switch (get_class($_arg1__6))
    {
        case 'Hand_PrivateHand':
            return $_arg1__6->cards === 0;
        default:
            return $_arg1__6->cards instanceof Nil;
    }
}

#36
function Shared_002EHandModule___toPrivate($_arg1__7) {
    if ($_arg1__7 instanceof Hand_PublicHand) {
        return new Hand_PrivateHand(FSharpList::length($_arg1__7->cards));
    } else {
        return $_arg1__7;
    }
}

#37
function Shared_002EHandModule___count($_arg1__8) {
    switch (get_class($_arg1__8))
    {
        case 'Hand_PrivateHand':
            return $_arg1__8->cards;
        default:
            return FSharpList::length($_arg1__8->cards);
    }
}

#38
function Shared_002EHandModule___contains($card, $_arg1__9) {
    switch (get_class($_arg1__9))
    {
        case 'Hand_PrivateHand':
            return false;
        default:
            return FSharpList::contains($card, $_arg1__9->cards, [ 'Equals' => 'Util::equals', 'GetHashCode' => 'Util::structuralHash']);
    }
}

#39
function Shared_002EHandModule___remove($card__1, $hand) {
    switch (get_class($hand))
    {
        case 'Hand_PrivateHand':
            return new Hand_PrivateHand($hand->cards - 1);
        default:
            $matchValue__2 = FSharpList::tryFindIndex(function ($c__1) use ($card__1) {             return Util::equals($c__1, $card__1);
 }, $hand->cards);
            if (is_null($matchValue__2)) {
                return $hand;
            } else {
                $i = $matchValue__2;
                $patternInput = FSharpList::splitAt($i, $hand->cards);
                return new Hand_PublicHand(FSharpList::append($patternInput[0], FSharpList::tail($patternInput[1])));
            }
    }
}

#40
function Shared_002EHandModule___canPlay($_arg1__10) {
    switch (get_class($_arg1__10))
    {
        case 'Hand_PrivateHand':
            return $_arg1__10->cards > 0;
        default:
            $value = $_arg1__10->cards instanceof Nil;
            return !$value;
    }
}

#41
abstract class CrazyPlayer {
}

#41
class CrazyPlayer_Starting extends CrazyPlayer {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#41
class CrazyPlayer_Playing extends CrazyPlayer {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#41
class CrazyPlayer_Ko extends CrazyPlayer {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#42
class Starting {
    public $Color;
    public $Parcel;
    public $Hand;
    public $Bonus;
    function __construct($Color, $Parcel, $Hand, $Bonus) {
        $this->Color = $Color;
        $this->Parcel = $Parcel;
        $this->Hand = $Hand;
        $this->Bonus = $Bonus;
    }
}

#43
class Playing {
    public $Color;
    public $Tractor;
    public $Fence;
    public $Field;
    public $Power;
    public $Moves;
    public $Hand;
    public $Bonus;
    function __construct($Color, $Tractor, $Fence, $Field, $Power, $Moves, $Hand, $Bonus) {
        $this->Color = $Color;
        $this->Tractor = $Tractor;
        $this->Fence = $Fence;
        $this->Field = $Field;
        $this->Power = $Power;
        $this->Moves = $Moves;
        $this->Hand = $Hand;
        $this->Bonus = $Bonus;
    }
}

#44
class Moves {
    public $Capacity;
    public $Done;
    public $Acceleration;
    function __construct($Capacity, $Done, $Acceleration) {
        $this->Capacity = $Capacity;
        $this->Done = $Done;
        $this->Acceleration = $Acceleration;
    }
}

#45
class Bonus {
    public $NitroOne;
    public $NitroTwo;
    public $Watched;
    public $HighVoltage;
    public $Rutted;
    public $Heliported;
    function __construct($NitroOne, $NitroTwo, $Watched, $HighVoltage, $Rutted, $Heliported) {
        $this->NitroOne = $NitroOne;
        $this->NitroTwo = $NitroTwo;
        $this->Watched = $Watched;
        $this->HighVoltage = $HighVoltage;
        $this->Rutted = $Rutted;
        $this->Heliported = $Heliported;
    }
}

#46
class GameTable {
    public $Players;
    public $AllPlayers;
    public $Names;
    public $Current;
    function __construct($Players, $AllPlayers, $Names, $Current) {
        $this->Players = $Players;
        $this->AllPlayers = $AllPlayers;
        $this->Names = $Names;
        $this->Current = $Current;
    }
}

#47
function Shared_002EGameTable__get_Player($this___2, $unitVar1__2) {
    return $this___2->Players[$this___2->Current];
}

#48
function Shared_002EGameTable__get_Next($this___3, $unitVar1__3) {
    $Current = ($this___3->Current + 1) % $this___3->Players['length'];
    return new GameTable($this___3->Players, $this___3->AllPlayers, $this___3->Names, $Current);
}

#49
function Shared_002ETable___start($players) {
    $allplayers = FSharpArray::ofSeq(Seq::delay(function ($unitVar) use ($players) {     return Seq::collect(function ($matchValue__3) {     return Seq::singleton($matchValue__3[0]);
 }, $players);
 }), $Array);
    return new GameTable($allplayers, $allplayers, Map::ofList($players, [ 'Compare' => 'Util::comparePrimitives']), 0);
}

#50
function Shared_002ETable___eliminate($player, $table) {
    return new GameTable($table->Players->filter(function ($p__11) use ($player) {     return $p__11 !== $player;
 }), $table->AllPlayers, $table->Names, $table->Current);
}

#51
function Shared_002ETable___isCurrent($playerid, $table__1) {
    return Shared_002EGameTable__get_Player($table__1, NULL) === $playerid;
}

#52
$GLOBALS['Shared_002EBonusModule___empty'] = new Bonus(0, 0, false, false, 0, 0);

#53
function Shared_002EBonusModule___startTurn($bonus) {
    return FSharpList::ofSeq(Seq::delay(function ($unitVar__1) use ($bonus) {     return Seq::append($bonus->HighVoltage ? Seq::singleton(new Card_HighVoltage()) : Seq::empty(), Seq::delay(function ($unitVar__2) use ($bonus) {     if ($bonus->Watched) {
        return Seq::singleton(new Card_Watchdog());
    } else {
        return Seq::empty();
    }
 }));
 }));
}

#54
function Shared_002EBonusModule___endTurn($bonus__1) {
    return FSharpList::ofSeq(Seq::delay(function ($unitVar__3) use ($bonus__1) {     return Seq::append(Seq::collect(function ($matchValue__4) {     return Seq::singleton(new Card_Nitro(new CardPower_One()));
 }, Seq::rangeNumber(1, 1, $bonus__1->NitroOne)), Seq::delay(function ($unitVar__4) use ($bonus__1) {     return Seq::append(Seq::collect(function ($matchValue__5) {     return Seq::singleton(new Card_Nitro(new CardPower_Two()));
 }, Seq::rangeNumber(1, 1, $bonus__1->NitroTwo)), Seq::delay(function ($unitVar__5) use ($bonus__1) {     return Seq::append(Seq::collect(function ($matchValue__6) {     return Seq::singleton(new Card_Rut());
 }, Seq::rangeNumber(1, 1, $bonus__1->Rutted)), Seq::delay(function ($unitVar__6) use ($bonus__1) {     return Seq::collect(function ($matchValue__7) {     return Seq::singleton(new Card_Helicopter());
 }, Seq::rangeNumber(1, 1, $bonus__1->Heliported));
 }));
 }));
 }));
 }));
}

#55
function Shared_002EBonusModule___moveCapacityChange($bonus__2) {
    return $bonus__2->Rutted * -2;
}

#56
function Shared_002EBonusModule___discard($card__2, $bonus__3) {
    if ($card__2 instanceof Card_Nitro) {
        switch (get_class($card__2->power))
        {
            case 'CardPower_Two':
                $NitroTwo = $bonus__3->NitroTwo - 1;
                return new Bonus($bonus__3->NitroOne, $NitroTwo, $bonus__3->Watched, $bonus__3->HighVoltage, $bonus__3->Rutted, $bonus__3->Heliported);
            default:
                return new Bonus($bonus__3->NitroOne - 1, $bonus__3->NitroTwo, $bonus__3->Watched, $bonus__3->HighVoltage, $bonus__3->Rutted, $bonus__3->Heliported);
        }
    } else {
        switch (get_class($card__2))
        {
            case 'Card_Watchdog':
                return new Bonus($bonus__3->NitroOne, $bonus__3->NitroTwo, false, $bonus__3->HighVoltage, $bonus__3->Rutted, $bonus__3->Heliported);
            case 'Card_HighVoltage':
                return new Bonus($bonus__3->NitroOne, $bonus__3->NitroTwo, $bonus__3->Watched, false, $bonus__3->Rutted, $bonus__3->Heliported);
            case 'Card_Rut':
                $Rutted = $bonus__3->Rutted - 1;
                return new Bonus($bonus__3->NitroOne, $bonus__3->NitroTwo, $bonus__3->Watched, $bonus__3->HighVoltage, $Rutted, $bonus__3->Heliported);
            case 'Card_Helicopter':
                $Heliported = $bonus__3->Heliported - 1;
                return new Bonus($bonus__3->NitroOne, $bonus__3->NitroTwo, $bonus__3->Watched, $bonus__3->HighVoltage, $bonus__3->Rutted, $Heliported);
            default:
                return $bonus__3;
        }
    }
}

#57
abstract class Board {
}

#57
class Board_InitialState extends Board {
    function __construct() {
    }
}

#57
class Board_Board extends Board {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#57
class Board_Won extends Board {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
}

#58
class PlayingBoard {
    public $Players;
    public $Table;
    public $DrawPile;
    public $DiscardPile;
    public $Barns;
    public $HayBales;
    public $Goal;
    function __construct($Players, $Table, $DrawPile, $DiscardPile, $Barns, $HayBales, $Goal) {
        $this->Players = $Players;
        $this->Table = $Table;
        $this->DrawPile = $DrawPile;
        $this->DiscardPile = $DiscardPile;
        $this->Barns = $Barns;
        $this->HayBales = $HayBales;
        $this->Goal = $Goal;
    }
}

#59
abstract class PlayerState {
}

#59
class PlayerState_SStarting extends PlayerState {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#59
class PlayerState_SPlaying extends PlayerState {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#59
class PlayerState_SKo extends PlayerState {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#60
class StartingState {
    public $SColor;
    public $SParcel;
    public $SHand;
    public $SBonus;
    function __construct($SColor, $SParcel, $SHand, $SBonus) {
        $this->SColor = $SColor;
        $this->SParcel = $SParcel;
        $this->SHand = $SHand;
        $this->SBonus = $SBonus;
    }
}

#61
class PlayingState {
    public $SColor;
    public $STractor;
    public $SFence;
    public $SField;
    public $SPower;
    public $SMoves;
    public $SHand;
    public $SBonus;
    function __construct($SColor, $STractor, $SFence, $SField, $SPower, $SMoves, $SHand, $SBonus) {
        $this->SColor = $SColor;
        $this->STractor = $STractor;
        $this->SFence = $SFence;
        $this->SField = $SField;
        $this->SPower = $SPower;
        $this->SMoves = $SMoves;
        $this->SHand = $SHand;
        $this->SBonus = $SBonus;
    }
}

#62
class BoardState {
    public $SPlayers;
    public $STable;
    public $SDiscardPile;
    public $SFreeBarns;
    public $SOccupiedBarns;
    public $SHayBales;
    public $SGoal;
    public $SWinner;
    function __construct($SPlayers, $STable, $SDiscardPile, $SFreeBarns, $SOccupiedBarns, $SHayBales, $SGoal, $SWinner) {
        $this->SPlayers = $SPlayers;
        $this->STable = $STable;
        $this->SDiscardPile = $SDiscardPile;
        $this->SFreeBarns = $SFreeBarns;
        $this->SOccupiedBarns = $SOccupiedBarns;
        $this->SHayBales = $SHayBales;
        $this->SGoal = $SGoal;
        $this->SWinner = $SWinner;
    }
}

#63
class STable {
    public $SPlayers;
    public $SAllPlayers;
    public $SNames;
    public $SCurrent;
    function __construct($SPlayers, $SAllPlayers, $SNames, $SCurrent) {
        $this->SPlayers = $SPlayers;
        $this->SAllPlayers = $SAllPlayers;
        $this->SNames = $SNames;
        $this->SCurrent = $SCurrent;
    }
}

#64
function Shared_002ECrossroadModule___neighbor($dir, $_arg1__11) {
    if ($_arg1__11->side instanceof CrossroadSide_CRight) {
        switch (get_class($dir))
        {
            case 'Direction_Down':
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___SE']), new CrossroadSide_CLeft()];
            case 'Direction_Horizontal':
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___E2']), new CrossroadSide_CLeft()];
            default:
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___NE']), new CrossroadSide_CLeft()];
        }
    } else {
        switch (get_class($dir))
        {
            case 'Direction_Down':
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___SW']), new CrossroadSide_CRight()];
            case 'Direction_Horizontal':
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___W2']), new CrossroadSide_CRight()];
            default:
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___NW']), new CrossroadSide_CRight()];
        }
    }
    return new Crossroad($tupledArg[0], $tupledArg[1]);
}

#65
function Shared_002ECrossroadModule___neighborTiles($_arg1__12) {
    $p__12 = new Parcel($_arg1__12->tile);
    switch (get_class($_arg1__12->side))
    {
        case 'CrossroadSide_CRight':
            return new Cons($p__12, new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($p__12, $GLOBALS['Shared_002EAxeModule___NE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($p__12, $GLOBALS['Shared_002EAxeModule___SE']), FSharpList::get_Nil())));
        default:
            return new Cons($p__12, new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($p__12, $GLOBALS['Shared_002EAxeModule___NW']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($p__12, $GLOBALS['Shared_002EAxeModule___SW']), FSharpList::get_Nil())));
    }
}

#66
function Shared_002ECrossroadModule___tile($_arg1__13) {
    return $_arg1__13->tile;
}

#67
function Shared_002ECrossroadModule___side($_arg1__14) {
    return $_arg1__14->side;
}

#68
function Shared_002ECrossroadModule___isInField($_arg2__2, $_arg1__15) {
    $p__13 = new Parcel($_arg1__15->tile);
    switch (get_class($_arg1__15->side))
    {
        case 'CrossroadSide_CRight':
            if (Set::contains($p__13, $_arg2__2->parcels) ? true : Set::contains(Shared_002EParcel___op_Addition__ZF6EFE4B($p__13, $GLOBALS['Shared_002EAxeModule___NE']), $_arg2__2->parcels)) {
                return true;
            } else {
                return Set::contains(Shared_002EParcel___op_Addition__ZF6EFE4B($p__13, $GLOBALS['Shared_002EAxeModule___SE']), $_arg2__2->parcels);
            }
        default:
            if (Set::contains($p__13, $_arg2__2->parcels) ? true : Set::contains(Shared_002EParcel___op_Addition__ZF6EFE4B($p__13, $GLOBALS['Shared_002EAxeModule___NW']), $_arg2__2->parcels)) {
                return true;
            } else {
                return Set::contains(Shared_002EParcel___op_Addition__ZF6EFE4B($p__13, $GLOBALS['Shared_002EAxeModule___SW']), $_arg2__2->parcels);
            }
    }
}

#69
function Shared_002ECrossroadModule___isOnBoard($_arg1__16) {
    $patternInput__1 = Shared_002EAxeModule___cube($_arg1__16->tile);
    switch (get_class($_arg1__16->side))
    {
        case 'CrossroadSide_CRight':
            if ($patternInput__1[0] >= -4 ? $patternInput__1[0] < 4 : false ? $patternInput__1[1] > -4 : false ? $patternInput__1[1] <= 4 : false ? $patternInput__1[2] > -4 : false) {
                return $patternInput__1[2] <= 4;
            } else {
                return false;
            }
        default:
            if ($patternInput__1[0] > -4 ? $patternInput__1[0] <= 4 : false ? $patternInput__1[1] >= -4 : false ? $patternInput__1[1] < 4 : false ? $patternInput__1[2] >= -4 : false) {
                return $patternInput__1[2] < 4;
            } else {
                return false;
            }
    }
}

#70
$GLOBALS['Shared_002EParcelModule___center'] = new Parcel($GLOBALS['Shared_002EAxeModule___center']);

#71
function Shared_002EParcelModule___crossroads($_arg1__17) {
    return new Cons(new Crossroad($_arg1__17->tile, new CrossroadSide_CLeft()), new Cons(new Crossroad($_arg1__17->tile, new CrossroadSide_CRight()), new Cons(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($_arg1__17->tile, $GLOBALS['Shared_002EAxeModule___NW']), new CrossroadSide_CRight()), new Cons(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($_arg1__17->tile, $GLOBALS['Shared_002EAxeModule___NE']), new CrossroadSide_CLeft()), new Cons(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($_arg1__17->tile, $GLOBALS['Shared_002EAxeModule___SW']), new CrossroadSide_CRight()), new Cons(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($_arg1__17->tile, $GLOBALS['Shared_002EAxeModule___SE']), new CrossroadSide_CLeft()), FSharpList::get_Nil()))))));
}

#72
function Shared_002EParcelModule___isOnBoard($_arg1__18) {
    $patternInput__2 = Shared_002EAxeModule___cube($_arg1__18->tile);
    if (abs($patternInput__2[0]) <= 3 ? abs($patternInput__2[1]) <= 3 : false) {
        return abs($patternInput__2[2]) <= 3;
    } else {
        return false;
    }
}

#73
function Shared_002EParcelModule___neighbors($_arg1__19) {
    $list__1 = new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__19->tile, $GLOBALS['Shared_002EAxeModule___N'])), new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__19->tile, $GLOBALS['Shared_002EAxeModule___NE'])), new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__19->tile, $GLOBALS['Shared_002EAxeModule___SE'])), new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__19->tile, $GLOBALS['Shared_002EAxeModule___S'])), new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__19->tile, $GLOBALS['Shared_002EAxeModule___SW'])), new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__19->tile, $GLOBALS['Shared_002EAxeModule___NW'])), FSharpList::get_Nil()))))));
    return FSharpList::filter('Shared_002EParcelModule___isOnBoard', $list__1);
}

#74
function Shared_002EPathModule___neighbor($dir__1, $_arg1__20) {
    if ($_arg1__20->side instanceof CrossroadSide_CRight) {
        switch (get_class($dir__1))
        {
            case 'Direction_Down':
                return new Path(Shared_002EAxe___op_Addition__2BE35040($_arg1__20->tile, $GLOBALS['Shared_002EAxeModule___SE']), new BorderSide_BNW());
            case 'Direction_Horizontal':
                return new Path(Shared_002EAxe___op_Addition__2BE35040($_arg1__20->tile, $GLOBALS['Shared_002EAxeModule___SE']), new BorderSide_BN());
            default:
                return new Path($_arg1__20->tile, new BorderSide_BNE());
        }
    } else {
        switch (get_class($dir__1))
        {
            case 'Direction_Down':
                return new Path(Shared_002EAxe___op_Addition__2BE35040($_arg1__20->tile, $GLOBALS['Shared_002EAxeModule___SW']), new BorderSide_BNE());
            case 'Direction_Horizontal':
                return new Path(Shared_002EAxe___op_Addition__2BE35040($_arg1__20->tile, $GLOBALS['Shared_002EAxeModule___SW']), new BorderSide_BN());
            default:
                return new Path($_arg1__20->tile, new BorderSide_BNW());
        }
    }
}

#75
function Shared_002EPathModule___tile($_arg1__21) {
    return $_arg1__21->tile;
}

#76
function Shared_002EPathModule___neighborTiles($_arg1__22) {
    switch (get_class($_arg1__22->border))
    {
        case 'BorderSide_BNE':
            return Shared_002EAxe___op_Addition__2BE35040($_arg1__22->tile, $GLOBALS['Shared_002EAxeModule___NE']);
        case 'BorderSide_BN':
            return Shared_002EAxe___op_Addition__2BE35040($_arg1__22->tile, $GLOBALS['Shared_002EAxeModule___N']);
        default:
            return Shared_002EAxe___op_Addition__2BE35040($_arg1__22->tile, $GLOBALS['Shared_002EAxeModule___NW']);
    }
}

#77
function Shared_002EPathModule___ofMoves($moves, $start) {
    return FSharpList::mapFold(function ($pos, $move) {     return [ [ Shared_002EPathModule___neighbor($move, $pos), $move], Shared_002ECrossroadModule___neighbor($move, $pos)];
 }, $start, $moves);
}

#78
$GLOBALS['Shared_002EPathModule___allInnerPaths'] = Set::ofSeq(FSharpList::ofSeq(Seq::delay(function ($unitVar__7) { return Seq::append(Seq::collect(function ($q__3) { return Seq::map(function ($r__3) use ($q__3) { return new Path(new Axe($q__3, $r__3), new BorderSide_BN());
 }, Seq::rangeNumber(Util::max('Util::comparePrimitives', -2, -2 - $q__3), 1, Util::min('Util::comparePrimitives', 3, 3 - $q__3)));
 }, Seq::rangeNumber(-3, 1, 3)), Seq::delay(function ($unitVar__8) { return Seq::append(Seq::collect(function ($q__4) { return Seq::map(function ($r__4) use ($q__4) { return new Path(new Axe($q__4, $r__4), new BorderSide_BNE());
 }, Seq::rangeNumber(Util::max('Util::comparePrimitives', -2, -3 - $q__4), 1, Util::min('Util::comparePrimitives', 3, 3 - $q__4)));
 }, Seq::rangeNumber(-3, 1, 2)), Seq::delay(function ($unitVar__9) { return Seq::collect(function ($q__5) { return Seq::map(function ($r__5) use ($q__5) { return new Path(new Axe($q__5, $r__5), new BorderSide_BNW());
 }, Seq::rangeNumber(Util::max('Util::comparePrimitives', -3, -2 - $q__5), 1, Util::min('Util::comparePrimitives', 3, 3 - $q__5)));
 }, Seq::rangeNumber(-2, 1, 3));
 }));
 }));
 })), [ 'Compare' => function ($_x__17, $_y__18) { return $_x__17->CompareTo($_y__18);
 }]);

#79
class LMax {
    public $Max;
    public $Left;
    public $Right;
    function __construct($Max, $Left, $Right) {
        $this->Max = $Max;
        $this->Left = $Left;
        $this->Right = $Right;
    }
}

#80
abstract class OrientedPath {
}

#80
class OrientedPath_DNE extends OrientedPath {
    function __construct() {
    }
}

#80
class OrientedPath_DNW extends OrientedPath {
    function __construct() {
    }
}

#80
class OrientedPath_DW extends OrientedPath {
    function __construct() {
    }
}

#80
class OrientedPath_DSW extends OrientedPath {
    function __construct() {
    }
}

#80
class OrientedPath_DSE extends OrientedPath {
    function __construct() {
    }
}

#80
class OrientedPath_DE extends OrientedPath {
    function __construct() {
    }
}

#81
$GLOBALS['Shared_002EFenceModule___empty'] = new Fence(FSharpList::get_Nil());

#82
function Shared_002EFenceModule___isEmpty($_arg1__23) {
    return $_arg1__23->paths instanceof Nil;
}

#83
function Shared_002EFenceModule___findLoop($dir__2, $pos__1, $_arg1__24) {
    $nextPos = Shared_002ECrossroadModule___neighbor($dir__2, $pos__1);
    $iter = function ($pos__2, $loop, $paths__2) {     if ($paths__2 instanceof Cons) {
        $nextEnd = Shared_002ECrossroadModule___neighbor(Shared_002EDirectionModule___rev($paths__2->value[1]), $pos__2);
        if (Util::equals($nextEnd, $nextPos)) {
            return new Fence(new Cons([ $paths__2->value[0], $paths__2->value[1]], $loop));
        } else {
            return iter($nextEnd, new Cons([ $paths__2->value[0], $paths__2->value[1]], $loop), $paths__2->next);
        }
    } else {
        return $GLOBALS['Shared_002EFenceModule___empty'];
    }
 };
    return iter($pos__1, FSharpList::get_Nil(), $_arg1__24->paths);
}

#84
function Shared_002EFenceModule___add($path_0, $path_1, $_arg1__25) {
    return new Fence(new Cons([ $path_0, $path_1], $_arg1__25->paths));
}

#85
function Shared_002EFenceModule___tail($_arg1__26) {
    return new Fence(FSharpList::tail($_arg1__26->paths));
}

#86
function Shared_002EFenceModule___fenceCrossroads($tractor, $_arg1__27) {
    $loop__1 = function ($pos__3, $paths__6) {     return Seq::delay(function ($unitVar__10) {     if ($paths__6 instanceof Cons) {
        $next = Shared_002ECrossroadModule___neighbor(Shared_002EDirectionModule___rev($paths__6->value[1]), $pos__3);
        return Seq::append(Seq::singleton($next), Seq::delay(function ($unitVar__11) use ($next, $paths__6) {         return loop__1($next, $paths__6->next);
 }));
    } else {
        return Seq::empty();
    }
 });
 };
    return loop__1($tractor, $_arg1__27->paths);
}

#87
function Shared_002EFenceModule___fencePaths($_arg1__28) {
    return FSharpList::map(function ($tuple) {     return $tuple[0];
 }, $_arg1__28->paths);
}

#88
function Shared_002EFenceModule___start($tractor__1, $_arg1__29) {
    $loop__2 = function ($pos__4, $paths__9) {     if ($paths__9 instanceof Cons) {
        $next__1 = Shared_002ECrossroadModule___neighbor(Shared_002EDirectionModule___rev($paths__9->value[1]), $pos__4);
        return loop__2($next__1, $paths__9->next);
    } else {
        return $pos__4;
    }
 };
    return loop__2($tractor__1, $_arg1__29->paths);
}

#89
function Shared_002EFenceModule___length($_arg1__30) {
    return FSharpList::length($_arg1__30->paths);
}

#90
function Shared_002EFenceModule___remove($toRemove, $_arg1__31) {
    return new Fence(FSharpList::skip(Shared_002EFenceModule___length($toRemove), $_arg1__31->paths));
}

#91
function Shared_002EFenceModule___toOriented($tractor__2, $_arg1__32) {
    $patternInput__3 = FSharpList::mapFold(function ($pos__5, $tupledArg__1) use ($o) { 
        $matchValue__10 = [ $tupledArg__1[1], Shared_002ECrossroadModule___side($pos__5)];
        if ($matchValue__10[0] instanceof Direction_Up) {
            switch (get_class($matchValue__10[1]))
            {
                case 'CrossroadSide_CLeft':
                    $o = new OrientedPath_DNW();
                default:
                    $o = new OrientedPath_DNE();
            }
        } else {
            if ($matchValue__10[0] instanceof Direction_Down) {
                switch (get_class($matchValue__10[1]))
                {
                    case 'CrossroadSide_CLeft':
                        $o = new OrientedPath_DSW();
                    default:
                        $o = new OrientedPath_DSE();
                }
            } else {
                switch (get_class($matchValue__10[1]))
                {
                    case 'CrossroadSide_CLeft':
                        $o = new OrientedPath_DE();
                    default:
                        $o = new OrientedPath_DW();
                }
            }
        }
        return [ $o, Shared_002ECrossroadModule___neighbor($tupledArg__1[1], $pos__5)];
    }, $tractor__2, $_arg1__32->paths);
    return [ FSharpList::reverse($patternInput__3[0]), $patternInput__3[1]];
}

#92
function Shared_002EFenceModule___givesAcceleration($fence) {
    return Shared_002EFenceModule___length($fence) >= 4;
}

#93
function Shared_002EFenceOps____007CRwd_007C__007C($nextPath, $_arg1__34) {
    if ($_arg1__34->paths instanceof Cons) {
        if (Util::equals($_arg1__34->paths->value[0], $nextPath)) {
            $last__1 = $_arg1__34->paths->value[0];
            return NULL;
        } else {
            return NULL;
        }
    } else {
        return NULL;
    }
}

#94
$GLOBALS['Shared_002EFieldModule___empty'] = new Field(Set::empty([ 'Compare' => function ($_x__19, $_y__20) { return $_x__19->CompareTo($_y__20);
 }]));

#95
function Shared_002EFieldModule___isEmpty($_arg1__35) {
    return Set::isEmpty($_arg1__35->parcels);
}

#96
function Shared_002EFieldModule___size($_arg1__36) {
    return Set::count($_arg1__36->parcels);
}

#97
function Shared_002EFieldModule___create($parcel) {
    return new Field(Set::ofSeq(new Cons($parcel, FSharpList::get_Nil()), [ 'Compare' => function ($_x__21, $_y__22) {     return $_x__21->CompareTo($_y__22);
 }]));
}

#98
function Shared_002EFieldModule___ofParcels($parcels__1) {
    return new Field(Set::ofSeq($parcels__1, [ 'Compare' => function ($_x__23, $_y__24) {     return $_x__23->CompareTo($_y__24);
 }]));
}

#99
function Shared_002EFieldModule___parcels($_arg1__37) {
    return Set::toList($_arg1__37->parcels);
}

#100
function Shared_002EFieldModule___contains($parcel__1, $_arg1__38) {
    return Set::contains(new Parcel($parcel__1), $_arg1__38->parcels);
}

#101
function Shared_002EFieldModule___containsParcel($parcel__2, $_arg1__39) {
    return Set::contains($parcel__2, $_arg1__39->parcels);
}

#102
function Shared_002EFieldModule___interesect($_arg2__3, $_arg1__40) {
    return new Field(Set::intersect($_arg2__3->parcels, $_arg1__40->parcels));
}

#103
function Shared_002EFieldModule___unionMany($fields) {
    $elements = FSharpList::collect('Shared_002EFieldModule___parcels', $fields);
    $arg0__1 = Set::ofSeq($elements, [ 'Compare' => function ($_x__25, $_y__26) {     return $_x__25->CompareTo($_y__26);
 }]);
    return new Field($arg0__1);
}

#104
function Shared_002EFieldModule___crossroads($_arg1__41) {
    $elements__1 = Seq::collect('Shared_002EParcelModule___crossroads', $_arg1__41->parcels);
    return Set::ofSeq($elements__1, [ 'Compare' => function ($_x__27, $_y__28) {     return $_x__27->CompareTo($_y__28);
 }]);
}

#105
function Shared_002EFieldModule___fill($paths__14) {
    $list__5 = FSharpList::choose(function ($_arg1__42) {     switch (get_class($_arg1__42[1]))
    {
        case 'Direction_Horizontal':
            $t = $_arg1__42[0]->tile;
            return $t;
        default:
            return NULL;
    }
 }, $paths__14);
    $list__6 = FSharpList::sortBy(function ($t__1) {     return [ Shared_002EAxe__get_Q($t__1, NULL), Shared_002EAxe__get_R($t__1, NULL)];
 }, $list__5, [ 'Compare' => 'Util::compareArrays']);
    $sortedPaths = FSharpList::groupBy(function ($tile__8) {     return Shared_002EAxe__get_Q($tile__8, NULL);
 }, $list__6, [ 'Equals' => function ($_x__31, $_y__32) {     return $_x__31 === $_y__32;
 }, 'GetHashCode' => 'Util::structuralHash']);
    $elements__2 = FSharpList::ofSeq(Seq::delay(function ($unitVar__12) use ($sortedPaths) {     return Seq::collect(function ($matchValue__11) {     return Seq::collect(function ($l) {     if ($l instanceof Cons) {
        if ($l->next instanceof Cons) {
            if ($l->next->next instanceof Nil) {
                $e = $l->next->value;
                $s = $l->value;
                return FSharpList::ofSeq(Seq::delay(function ($unitVar__13) use ($e, $s) {                 return Seq::map(function ($r__6) use ($matchValue__11) {                 return new Parcel(new Axe($matchValue__11[0], $r__6));
 }, Seq::rangeNumber(Shared_002EAxe__get_R($s, NULL), 1, Shared_002EAxe__get_R($e, NULL) - 1));
 }));
            } else {
                return Seq::empty();
            }
        } else {
            return Seq::empty();
        }
    } else {
        return Seq::empty();
    }
 }, FSharpList::chunkBySize(2, $matchValue__11[1]));
 }, $sortedPaths);
 }));
    $arg0__2 = Set::ofSeq($elements__2, [ 'Compare' => function ($_x__33, $_y__34) {     return $_x__33->CompareTo($_y__34);
 }]);
    return new Field($arg0__2);
}

#106
function Shared_002EFieldModule___borderTiles($_arg1__43) {
    $elements__3 = Seq::collect('Shared_002EParcelModule___neighbors', $_arg1__43->parcels);
    $allNeighbors = Set::ofSeq($elements__3, [ 'Compare' => function ($_x__35, $_y__36) {     return $_x__35->CompareTo($_y__36);
 }]);
    $arg0__3 = Set::FSharpSet___op_Subtraction($allNeighbors, $_arg1__43->parcels);
    return new Field($arg0__3);
}

#107
function Shared_002EFieldModule___counterclock($field, $_arg1__44) {
    switch (get_class($_arg1__44->side))
    {
        case 'CrossroadSide_CLeft':
            if (Shared_002EFieldModule___contains($_arg1__44->tile, $field)) {
                if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__44->tile, $GLOBALS['Shared_002EAxeModule___SW']), $field)) {
                    return [ new Direction_Horizontal(), new OrientedPath_DW()];
                } else {
                    return [ new Direction_Down(), new OrientedPath_DSE()];
                }
            } else {
                if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__44->tile, $GLOBALS['Shared_002EAxeModule___NW']), $field)) {
                    return [ new Direction_Up(), new OrientedPath_DNE()];
                } else {
                    if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__44->tile, $GLOBALS['Shared_002EAxeModule___NW']), $field)) {
                        return [ new Direction_Up(), new OrientedPath_DNE()];
                    } else {
                        return [ new Direction_Horizontal(), new OrientedPath_DW()];
                    }
                }
            }
        default:
            if (Shared_002EFieldModule___contains($_arg1__44->tile, $field)) {
                if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__44->tile, $GLOBALS['Shared_002EAxeModule___NE']), $field)) {
                    return [ new Direction_Horizontal(), new OrientedPath_DE()];
                } else {
                    return [ new Direction_Up(), new OrientedPath_DNW()];
                }
            } else {
                if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__44->tile, $GLOBALS['Shared_002EAxeModule___NE']), $field)) {
                    if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__44->tile, $GLOBALS['Shared_002EAxeModule___SE']), $field)) {
                        return [ new Direction_Down(), new OrientedPath_DSW()];
                    } else {
                        return [ new Direction_Horizontal(), new OrientedPath_DE()];
                    }
                } else {
                    return [ new Direction_Down(), new OrientedPath_DSW()];
                }
            }
    }
}

#108
function Shared_002EFieldModule___borderBetween($start__1, $end_0027__1, $field__1) {
    $loop__3 = function ($orientedPath, $pos__6, $path__2) use ($end_0027__1, $start__1) {     if (Util::equals($pos__6, $end_0027__1)) {
        return FSharpList::reverse($path__2);
    } else {
        if (Util::equals($pos__6, $start__1)) {
            return FSharpList::get_Nil();
        } else {
            switch (get_class($orientedPath))
            {
                case 'OrientedPath_DNE':
                    $tile__11 = Shared_002EAxe___op_Addition__2BE35040(Shared_002ECrossroadModule___tile($pos__6), $GLOBALS['Shared_002EAxeModule___NE']);
                    if (Shared_002EFieldModule___contains($tile__11, $field__1)) {
                        $next__4 = Shared_002ECrossroadModule___neighbor(new Direction_Horizontal(), $pos__6);
                        return loop__3(new OrientedPath_DE(), $next__4, new Cons([ Shared_002EPathModule___neighbor(new Direction_Horizontal(), $pos__6), new Direction_Horizontal()], $path__2));
                    } else {
                        $next__5 = Shared_002ECrossroadModule___neighbor(new Direction_Up(), $pos__6);
                        return loop__3(new OrientedPath_DNW(), $next__5, new Cons([ Shared_002EPathModule___neighbor(new Direction_Up(), $pos__6), new Direction_Up()], $path__2));
                    }
                case 'OrientedPath_DNW':
                    $tile__12 = Shared_002EAxe___op_Addition__2BE35040(Shared_002ECrossroadModule___tile($pos__6), $GLOBALS['Shared_002EAxeModule___NW']);
                    if (Shared_002EFieldModule___contains($tile__12, $field__1)) {
                        $next__6 = Shared_002ECrossroadModule___neighbor(new Direction_Up(), $pos__6);
                        return loop__3(new OrientedPath_DNE(), $next__6, new Cons([ Shared_002EPathModule___neighbor(new Direction_Up(), $pos__6), new Direction_Up()], $path__2));
                    } else {
                        $next__7 = Shared_002ECrossroadModule___neighbor(new Direction_Horizontal(), $pos__6);
                        return loop__3(new OrientedPath_DW(), $next__7, new Cons([ Shared_002EPathModule___neighbor(new Direction_Horizontal(), $pos__6), new Direction_Horizontal()], $path__2));
                    }
                case 'OrientedPath_DW':
                    $tile__13 = Shared_002ECrossroadModule___tile($pos__6);
                    if (Shared_002EFieldModule___contains($tile__13, $field__1)) {
                        $next__8 = Shared_002ECrossroadModule___neighbor(new Direction_Up(), $pos__6);
                        return loop__3(new OrientedPath_DNW(), $next__8, new Cons([ Shared_002EPathModule___neighbor(new Direction_Up(), $pos__6), new Direction_Up()], $path__2));
                    } else {
                        $next__9 = Shared_002ECrossroadModule___neighbor(new Direction_Down(), $pos__6);
                        return loop__3(new OrientedPath_DSW(), $next__9, new Cons([ Shared_002EPathModule___neighbor(new Direction_Down(), $pos__6), new Direction_Down()], $path__2));
                    }
                case 'OrientedPath_DSW':
                    $tile__14 = Shared_002EAxe___op_Addition__2BE35040(Shared_002ECrossroadModule___tile($pos__6), $GLOBALS['Shared_002EAxeModule___SW']);
                    if (Shared_002EFieldModule___contains($tile__14, $field__1)) {
                        $next__10 = Shared_002ECrossroadModule___neighbor(new Direction_Horizontal(), $pos__6);
                        return loop__3(new OrientedPath_DW(), $next__10, new Cons([ Shared_002EPathModule___neighbor(new Direction_Horizontal(), $pos__6), new Direction_Horizontal()], $path__2));
                    } else {
                        $next__11 = Shared_002ECrossroadModule___neighbor(new Direction_Down(), $pos__6);
                        return loop__3(new OrientedPath_DSE(), $next__11, new Cons([ Shared_002EPathModule___neighbor(new Direction_Down(), $pos__6), new Direction_Down()], $path__2));
                    }
                case 'OrientedPath_DSE':
                    $tile__15 = Shared_002EAxe___op_Addition__2BE35040(Shared_002ECrossroadModule___tile($pos__6), $GLOBALS['Shared_002EAxeModule___SE']);
                    if (Shared_002EFieldModule___contains($tile__15, $field__1)) {
                        $next__12 = Shared_002ECrossroadModule___neighbor(new Direction_Down(), $pos__6);
                        return loop__3(new OrientedPath_DSW(), $next__12, new Cons([ Shared_002EPathModule___neighbor(new Direction_Down(), $pos__6), new Direction_Down()], $path__2));
                    } else {
                        $next__13 = Shared_002ECrossroadModule___neighbor(new Direction_Horizontal(), $pos__6);
                        return loop__3(new OrientedPath_DE(), $next__13, new Cons([ Shared_002EPathModule___neighbor(new Direction_Horizontal(), $pos__6), new Direction_Horizontal()], $path__2));
                    }
                default:
                    $tile__10 = Shared_002ECrossroadModule___tile($pos__6);
                    if (Shared_002EFieldModule___contains($tile__10, $field__1)) {
                        $next__2 = Shared_002ECrossroadModule___neighbor(new Direction_Down(), $pos__6);
                        return loop__3(new OrientedPath_DSE(), $next__2, new Cons([ Shared_002EPathModule___neighbor(new Direction_Down(), $pos__6), new Direction_Down()], $path__2));
                    } else {
                        $next__3 = Shared_002ECrossroadModule___neighbor(new Direction_Up(), $pos__6);
                        return loop__3(new OrientedPath_DNE(), $next__3, new Cons([ Shared_002EPathModule___neighbor(new Direction_Up(), $pos__6), new Direction_Up()], $path__2));
                    }
            }
        }
    }
 };
    $patternInput__4 = Shared_002EFieldModule___counterclock($field__1, $start__1);
    $pos__7 = Shared_002ECrossroadModule___neighbor($patternInput__4[0], $start__1);
    return loop__3($patternInput__4[1], $pos__7, new Cons([ Shared_002EPathModule___neighbor($patternInput__4[0], $start__1), $patternInput__4[0]], FSharpList::get_Nil()));
}

#109
function Shared_002EFieldModule___isInSameField($start__2, $end_0027__2, $field__2) {
    $list__7 = Shared_002EFieldModule___borderBetween($start__2, $end_0027__2, $field__2);
    $value__1 = $list__7 instanceof Nil;
    return !$value__1;
}

#110
function Shared_002EFieldModule___pathInFieldOrBorder($path__3, $field__3) {
    if (Shared_002EFieldModule___contains(Shared_002EPathModule___tile($path__3), $field__3)) {
        return true;
    } else {
        return Shared_002EFieldModule___contains(Shared_002EPathModule___neighborTiles($path__3), $field__3);
    }
}

#111
function Shared_002EFieldModule___findBorder($field__4, $crossroad) {
    $list__8 = Shared_002ECrossroadModule___neighborTiles($crossroad);
    $neighborTilesInField = FSharpList::sumBy(function ($p__17) use ($field__4) {     if (Shared_002EFieldModule___containsParcel($p__17, $field__4)) {
        return 1;
    } else {
        return 0;
    }
 }, $list__8, [ 'GetZero' => function () {     return 0;
 }, 'Add' => function ($_x__37, $_y__38) {     return $_x__37 + $_y__38;
 }]);
    if ($neighborTilesInField < 3) {
        return $crossroad;
    } else {
        return Shared_002EFieldModule___findBorder($field__4, Shared_002ECrossroadModule___neighbor(new Direction_Up(), $crossroad));
    }
}

#112
function Shared_002EFieldModule___principalField($field__5, $fence__1, $crossroad__1) {
    $start__3 = Shared_002EFenceModule___start($crossroad__1, $fence__1);
    if (Shared_002ECrossroadModule___isInField($field__5, $start__3)) {
        $onBorder = Shared_002EFieldModule___findBorder($field__5, $start__3);
        $border = Shared_002EFieldModule___borderBetween($onBorder, $onBorder, $field__5);
        return Shared_002EFieldModule___fill($border);
    } else {
        return $GLOBALS['Shared_002EFieldModule___empty'];
    }
}

#113
$GLOBALS['Shared_002EBarnsModule___empty'] = new Barns($GLOBALS['Shared_002EFieldModule___empty'], $GLOBALS['Shared_002EFieldModule___empty']);

#114
function Shared_002EBarnsModule___intersectWith($field__6, $barns) {
    return new Barns(Shared_002EFieldModule___interesect($field__6, $barns->Free), Shared_002EFieldModule___interesect($field__6, $barns->Occupied));
}

#115
function Shared_002EBarnsModule___init($barns__1) {
    return new Barns(Shared_002EFieldModule___ofParcels($barns__1), $GLOBALS['Shared_002EFieldModule___empty']);
}

#116
function Shared_002EBarnsModule___annex($annexed, $barns__2) {
    return new Barns(Shared_002EField___op_Subtraction__Z24735800($barns__2->Free, $annexed->Free), Shared_002EField___op_Addition__Z24735800($barns__2->Occupied, Shared_002EFieldModule___interesect($barns__2->Free, $annexed->Free)));
}

#117
abstract class MoveBlocker {
}

#117
class MoveBlocker_Tractor extends MoveBlocker {
    function __construct() {
    }
}

#117
class MoveBlocker_Protection extends MoveBlocker {
    function __construct() {
    }
}

#117
class MoveBlocker_PhytosanitaryProducts extends MoveBlocker {
    function __construct() {
    }
}

#117
class MoveBlocker_HayBaleOnPath extends MoveBlocker {
    function __construct() {
    }
}

#117
class MoveBlocker_HighVoltageProtection extends MoveBlocker {
    function __construct() {
    }
}

#118
abstract class Move {
}

#118
class Move_Move extends Move {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
}

#118
class Move_ImpossibleMove extends Move {
    public $Item1;
    public $Item2;
    public $Item3;
    function __construct($Item1, $Item2, $Item3) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
        $this->Item3 = $Item3;
    }
}

#118
class Move_SelectCrossroad extends Move {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#119
$GLOBALS['Shared_002EMovesModule___empty'] = new Moves(0, 0, false);

#120
function Shared_002EMovesModule___startTurn($fence__2, $bonus__4) {
    $acceleration = Shared_002EFenceModule___givesAcceleration($fence__2);
    return new Moves((function () use ($acceleration, $bonus__4) { 
        if ($acceleration) {
            $baseMoves = 4;
        } else {
            $baseMoves = 3;
        }
        return $baseMoves + Shared_002EBonusModule___moveCapacityChange($bonus__4);
    })(), 0, $acceleration);
}

#121
function Shared_002EMovesModule___canMove($m) {
    return $m->Done < $m->Capacity;
}

#122
function Shared_002EMovesModule___addCapacity($n, $m__1) {
    return new Moves(Util::min('Util::comparePrimitives', $m__1->Capacity + $n, 5), $m__1->Done, $m__1->Acceleration);
}

#123
function Shared_002EMovesModule___doMove($m__2) {
    $Done = $m__2->Done + 1;
    return new Moves($m__2->Capacity, $Done, $m__2->Acceleration);
}

#124
abstract class Command {
}

#124
class Command_Start extends Command {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#124
class Command_SelectFirstCrossroad extends Command {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#124
class Command_Move extends Command {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#124
class Command_PlayCard extends Command {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#124
class Command_EndTurn extends Command {
    function __construct() {
    }
}

#125
class Start {
    public $Parcel;
    function __construct($Parcel) {
        $this->Parcel = $Parcel;
    }
}

#126
class SelectFirstCrossroad {
    public $Crossroad;
    function __construct($Crossroad) {
        $this->Crossroad = $Crossroad;
    }
}

#127
class PlayerMove {
    public $Direction;
    public $Destination;
    function __construct($Direction, $Destination) {
        $this->Direction = $Direction;
        $this->Destination = $Destination;
    }
}

#128
abstract class Event {
}

#128
class Event_FirstCrossroadSelected extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_FenceDrawn extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_FenceRemoved extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_FenceLooped extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_MovedInField extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_MovedPowerless extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_Annexed extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_CutFence extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_PoweredUp extends Event {
    function __construct() {
    }
}

#128
class Event_CardPlayed extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_SpedUp extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_Rutted extends Event {
    function __construct() {
    }
}

#128
class Event_HighVoltaged extends Event {
    function __construct() {
    }
}

#128
class Event_BonusDiscarded extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_Watched extends Event {
    function __construct() {
    }
}

#128
class Event_Heliported extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_Bribed extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#128
class Event_Eliminated extends Event {
    function __construct() {
    }
}

#129
class PlayerStarted {
    public $Parcel;
    function __construct($Parcel) {
        $this->Parcel = $Parcel;
    }
}

#130
class FirstCrossroadSelected {
    public $Crossroad;
    function __construct($Crossroad) {
        $this->Crossroad = $Crossroad;
    }
}

#131
class Moved {
    public $Move;
    public $Path;
    public $Crossroad;
    function __construct($Move, $Path, $Crossroad) {
        $this->Move = $Move;
        $this->Path = $Path;
        $this->Crossroad = $Crossroad;
    }
}

#132
class FenceLooped {
    public $Move;
    public $Loop;
    public $Crossroad;
    function __construct($Move, $Loop, $Crossroad) {
        $this->Move = $Move;
        $this->Loop = $Loop;
        $this->Crossroad = $Crossroad;
    }
}

#133
class Annexed {
    public $NewField;
    public $LostFields;
    public $FreeBarns;
    public $OccupiedBarns;
    function __construct($NewField, $LostFields, $FreeBarns, $OccupiedBarns) {
        $this->NewField = $NewField;
        $this->LostFields = $LostFields;
        $this->FreeBarns = $FreeBarns;
        $this->OccupiedBarns = $OccupiedBarns;
    }
}

#134
class CutFence {
    public $Player;
    function __construct($Player) {
        $this->Player = $Player;
    }
}

#135
class SpedUp {
    public $Speed;
    function __construct($Speed) {
        $this->Speed = $Speed;
    }
}

#136
class Bribed {
    public $Parcel;
    public $Victim;
    function __construct($Parcel, $Victim) {
        $this->Parcel = $Parcel;
        $this->Victim = $Victim;
    }
}

#137
function Shared_002EPlayer___isCut($tractor__3, $player__1) {
    if ($player__1 instanceof CrazyPlayer_Playing) {
        if (!$player__1->Item->Bonus->HighVoltage) {
            $source__2 = Shared_002EFenceModule___fenceCrossroads($player__1->Item->Tractor, $player__1->Item->Fence);
            return Seq::contains($tractor__3, $source__2);
        } else {
            return false;
        }
    } else {
        return false;
    }
}

#138
function Shared_002EPlayer___decideCut($otherPlayers, $tractor__4) {
    $list__10 = FSharpList::filter(function ($_arg__41) use ($tractor__4) { 
        $player__3 = $_arg__41[1];
        return Shared_002EPlayer___isCut($tractor__4, $player__3);
    }, $otherPlayers);
    return FSharpList::map(function ($tupledArg__2) {     return new Event_CutFence(new CutFence($tupledArg__2[0]));
 }, $list__10);
}

#139
function Shared_002EPlayer___annexation($field__7, $fence__3, $tractor__5) {
    $border__1 = Shared_002EFieldModule___borderBetween(Shared_002EFenceModule___start($tractor__5, $fence__3), $tractor__5, $field__7);
    $fullBorder = FSharpList::append($fence__3->paths, $border__1);
    return Shared_002EField___op_Subtraction__Z24735800(Shared_002EFieldModule___fill($fullBorder), $field__7);
}

#140
function Shared_002EPlayer___startTurn($player__4) {
    switch (get_class($player__4))
    {
        case 'CrazyPlayer_Starting':
            return $player__4;
        case 'CrazyPlayer_Ko':
            return $player__4;
        default:
            return new CrazyPlayer_Playing((function () use ($player__4) { 
                $Moves = Shared_002EMovesModule___startTurn($player__4->Item->Fence, $player__4->Item->Bonus);
                return new Playing($player__4->Item->Color, $player__4->Item->Tractor, $player__4->Item->Fence, $player__4->Item->Field, $player__4->Item->Power, $Moves, $player__4->Item->Hand, $player__4->Item->Bonus);
            })());
    }
}

#141
function Shared_002EPlayer___color($player__5) {
    switch (get_class($player__5))
    {
        case 'CrazyPlayer_Starting':
            return $player__5->Item->Color;
        case 'CrazyPlayer_Ko':
            return $player__5->Item;
        default:
            return $player__5->Item->Color;
    }
}

#142
function Shared_002EPlayer___hand($player__6) {
    switch (get_class($player__6))
    {
        case 'CrazyPlayer_Starting':
            return $player__6->Item->Hand;
        case 'CrazyPlayer_Ko':
            return $GLOBALS['Shared_002EHandModule___empty'];
        default:
            return $player__6->Item->Hand;
    }
}

#143
function Shared_002EPlayer___bonus($player__7) {
    switch (get_class($player__7))
    {
        case 'CrazyPlayer_Starting':
            return $player__7->Item->Bonus;
        case 'CrazyPlayer_Ko':
            return $GLOBALS['Shared_002EBonusModule___empty'];
        default:
            return $player__7->Item->Bonus;
    }
}

#144
function Shared_002EPlayer___fence($player__8) {
    switch (get_class($player__8))
    {
        case 'CrazyPlayer_Starting':
            $_target__1 = 1;
        case 'CrazyPlayer_Ko':
            $_target__1 = 1;
        default:
            $_target__1 = 0;
    }
    switch ($_target__1)
    {
        case 0:
            return $player__8->Item->Fence;
        case 1:
            return $GLOBALS['Shared_002EFenceModule___empty'];
    }
}

#145
function Shared_002EPlayer___field($player__9) {
    switch (get_class($player__9))
    {
        case 'CrazyPlayer_Starting':
            return Shared_002EFieldModule___ofParcels(new Cons($player__9->Item->Parcel, FSharpList::get_Nil()));
        case 'CrazyPlayer_Ko':
            return $GLOBALS['Shared_002EFieldModule___empty'];
        default:
            return $player__9->Item->Field;
    }
}

#146
function Shared_002EPlayer___isKo($player__10) {
    if ($player__10 instanceof CrazyPlayer_Ko) {
        return true;
    } else {
        return false;
    }
}

#147
function Shared_002EPlayer___toPrivate($player__11) {
    switch (get_class($player__11))
    {
        case 'CrazyPlayer_Starting':
            return new CrazyPlayer_Starting((function () use ($player__11) { 
                $Hand__1 = Shared_002EHandModule___toPrivate($player__11->Item->Hand);
                return new Starting($player__11->Item->Color, $player__11->Item->Parcel, $Hand__1, $player__11->Item->Bonus);
            })());
        case 'CrazyPlayer_Ko':
            return $player__11;
        default:
            return new CrazyPlayer_Playing((function () use ($player__11) { 
                $Hand = Shared_002EHandModule___toPrivate($player__11->Item->Hand);
                return new Playing($player__11->Item->Color, $player__11->Item->Tractor, $player__11->Item->Fence, $player__11->Item->Field, $player__11->Item->Power, $player__11->Item->Moves, $Hand, $player__11->Item->Bonus);
            })());
    }
}

#148
function Shared_002EPlayer___fieldTotalSize($player__12) {
    switch (get_class($player__12))
    {
        case 'CrazyPlayer_Starting':
            return 1;
        case 'CrazyPlayer_Ko':
            return 0;
        default:
            return Shared_002EFieldModule___size($player__12->Item->Field);
    }
}

#149
function Shared_002EPlayer___principalFieldSize($player__13) {
    switch (get_class($player__13))
    {
        case 'CrazyPlayer_Starting':
            return 1;
        case 'CrazyPlayer_Ko':
            return 0;
        default:
            $arg00_0040__4 = Shared_002EFieldModule___principalField($player__13->Item->Field, $player__13->Item->Fence, $player__13->Item->Tractor);
            return Shared_002EFieldModule___size($arg00_0040__4);
    }
}

#150
function Shared_002EPlayer___watchedField($player__14) {
    if ($player__14 instanceof CrazyPlayer_Playing) {
        if ($player__14->Item->Bonus->Watched) {
            $field__8 = $player__14->Item->Field;
            return $field__8;
        } else {
            return $GLOBALS['Shared_002EFieldModule___empty'];
        }
    } else {
        return $GLOBALS['Shared_002EFieldModule___empty'];
    }
}

#151
function Shared_002EPlayer___canUseHelicopter($player__15) {
    if ($player__15 instanceof CrazyPlayer_Playing) {
        return Shared_002EFenceModule___isEmpty($player__15->Item->Fence);
    } else {
        return false;
    }
}

#152
function Shared_002EPlayer___decide($otherPlayers__1, $barns__3, $command, $player__16) {
    if ($player__16 instanceof CrazyPlayer_Starting) {
        switch (get_class($command))
        {
            case 'Command_SelectFirstCrossroad':
                $cmd = $command->Item;
                return new Cons(new Event_FirstCrossroadSelected(new FirstCrossroadSelected($cmd->Crossroad)), FSharpList::get_Nil());
            default:
                throw new Exception('Invalid operation');
        }
    } else {
        if ($player__16 instanceof CrazyPlayer_Playing) {
            switch (get_class($command))
            {
                case 'Command_Move':
                    $cmd__1 = $command->Item;
                    $player__17 = $player__16->Item;
                    $nextPos__1 = Shared_002ECrossroadModule___neighbor($cmd__1->Direction, $player__17->Tractor);
                    $nextPath__1 = Shared_002EPathModule___neighbor($cmd__1->Direction, $player__17->Tractor);
                    if (!Util::equals($nextPos__1, $cmd__1->Destination) ? true : !Shared_002EMovesModule___canMove($player__17->Moves)) {
                        return FSharpList::get_Nil();
                    } else {
                        switch (get_class($player__17->Power))
                        {
                            case 'Power_PowerDown':
                                return FSharpList::ofSeq(Seq::delay(function ($unitVar__19) use ($cmd__1, $nextPath__1, $nextPos__1) {                                 return Seq::append(Seq::singleton(new Event_MovedPowerless(new Moved($cmd__1->Direction, $nextPath__1, $nextPos__1))), Seq::delay(function ($unitVar__20) use ($nextPos__1, $player__17) {                                 if (Shared_002ECrossroadModule___isInField($player__17->Field, $nextPos__1)) {
                                    return Seq::append(Seq::singleton(new Event_PoweredUp()), Seq::delay(function ($unitVar__21) use ($nextPos__1, $otherPlayers__1) {                                     return Shared_002EPlayer___decideCut($otherPlayers__1, $nextPos__1);
 }));
                                } else {
                                    return Seq::empty();
                                }
 }));
 }));
                            default:
                                $activePatternResult41410 = Shared_002EFenceOps____007CRwd_007C__007C($nextPath__1, $player__17->Fence);
                                if (!is_null($activePatternResult41410)) {
                                    return new Cons(new Event_FenceRemoved(new Moved($cmd__1->Direction, $nextPath__1, $nextPos__1)), FSharpList::get_Nil());
                                } else {
                                    $matchValue__15 = Shared_002EFenceModule___findLoop($cmd__1->Direction, $player__17->Tractor, $player__17->Fence);
                                    if ($matchValue__15->paths instanceof Nil) {
                                        $endInField = Shared_002ECrossroadModule___isInField($player__17->Field, $nextPos__1);
                                        $pathInField = Shared_002EFieldModule___pathInFieldOrBorder($nextPath__1, $player__17->Field);
                                        if ($endInField) {
                                            $nextFence = Shared_002EFenceModule___add($nextPath__1, $cmd__1->Direction, $player__17->Fence);
                                            if ($pathInField ? Shared_002EFenceModule___length($nextFence) === 1 : false) {
                                                $inFallow = false;
                                            } else {
                                                $fenceStart = Shared_002EFenceModule___start($nextPos__1, $nextFence);
                                                $inFallow = !Shared_002EFieldModule___isInSameField($fenceStart, $nextPos__1, $player__17->Field);
                                            }
                                        } else {
                                            $inFallow = false;
                                        }
                                        return FSharpList::ofSeq(Seq::delay(function ($unitVar__14) use ($cmd__1, $inFallow, $nextPath__1, $nextPos__1, $pathInField) {                                         return Seq::append($pathInField ? !$inFallow : false ? Seq::singleton(new Event_MovedInField(new Moved($cmd__1->Direction, $nextPath__1, $nextPos__1))) : Seq::singleton(new Event_FenceDrawn(new Moved($cmd__1->Direction, $nextPath__1, $nextPos__1))), Seq::delay(function ($unitVar__15) use ($nextPos__1, $otherPlayers__1) {                                         return Seq::append(Shared_002EPlayer___decideCut($otherPlayers__1, $nextPos__1), Seq::delay(function ($unitVar__16) use ($barns__3, $cmd__1, $endInField, $inFallow, $nextPath__1, $nextPos__1, $otherPlayers__1, $pathInField, $player__17) {                                         if ($endInField ? !$pathInField : false ? !$inFallow : false) {
                                            $nextFence__1 = Shared_002EFenceModule___add($nextPath__1, $cmd__1->Direction, $player__17->Fence);
                                            $baseAnnexed = Shared_002EPlayer___annexation($player__17->Field, $nextFence__1, $nextPos__1);
                                            $annexed__1 = FSharpList::fold(function ($anx, $tupledArg__3) {                                             return Shared_002EField___op_Subtraction__Z24735800($anx, Shared_002EPlayer___watchedField($tupledArg__3[1]));
 }, $baseAnnexed, $otherPlayers__1);
                                            $lostFields = FSharpList::ofSeq(Seq::delay(function ($unitVar__17) use ($otherPlayers__1) {                                             return Seq::collect(function ($matchValue__16) use ($annexed__1) {                                             if ($matchValue__16[1] instanceof CrazyPlayer_Playing) {
                                                $lost = Shared_002EFieldModule___interesect($annexed__1, $matchValue__16[1]->Item->Field);
                                                if (!Shared_002EFieldModule___isEmpty($lost)) {
                                                    return Seq::singleton([ $matchValue__16[0], Shared_002EFieldModule___parcels($lost)]);
                                                } else {
                                                    return Seq::empty();
                                                }
                                            } else {
                                                return Seq::empty();
                                            }
 }, $otherPlayers__1);
 }));
                                            $annexedBarns = Shared_002EBarnsModule___intersectWith($annexed__1, $barns__3);
                                            return Seq::append(Seq::singleton(new Event_Annexed(new Annexed(Shared_002EFieldModule___parcels($annexed__1), $lostFields, Shared_002EFieldModule___parcels($annexedBarns->Free), Shared_002EFieldModule___parcels($annexedBarns->Occupied)))), Seq::delay(function ($unitVar__18) use ($otherPlayers__1) {                                             return Seq::collect(function ($matchValue__17) use ($annexed__1) {                                             if ($matchValue__17[1] instanceof CrazyPlayer_Playing) {
                                                if (!Shared_002EFenceModule___isEmpty($matchValue__17[1]->Item->Fence)) {
                                                    $start__4 = Shared_002EFenceModule___start($matchValue__17[1]->Item->Tractor, $matchValue__17[1]->Item->Fence);
                                                    if (Shared_002ECrossroadModule___isInField($annexed__1, $start__4)) {
                                                        return Seq::singleton(new Event_CutFence(new CutFence($matchValue__17[0])));
                                                    } else {
                                                        return Seq::empty();
                                                    }
                                                } else {
                                                    $remainingField = Shared_002EField___op_Subtraction__Z24735800($matchValue__17[1]->Item->Field, $annexed__1);
                                                    if (Shared_002ECrossroadModule___isInField($annexed__1, $matchValue__17[1]->Item->Tractor) ? !Shared_002ECrossroadModule___isInField($remainingField, $matchValue__17[1]->Item->Tractor) : false) {
                                                        return Seq::singleton(new Event_CutFence(new CutFence($matchValue__17[0])));
                                                    } else {
                                                        return Seq::empty();
                                                    }
                                                }
                                            } else {
                                                return Seq::empty();
                                            }
 }, $otherPlayers__1);
 }));
                                        } else {
                                            return Seq::empty();
                                        }
 }));
 }));
 }));
                                    } else {
                                        return new Cons(new Event_FenceLooped(new FenceLooped($cmd__1->Direction, $matchValue__15, $nextPos__1)), FSharpList::get_Nil());
                                    }
                                }
                        }
                    }
                case 'Command_PlayCard':
                    $card__3 = $command->Item;
                    $p__39 = $player__16->Item;
                    $c__2 = Shared_002ECardModule___ofPlayCard($card__3);
                    if (Shared_002EHandModule___contains($c__2, $p__39->Hand)) {
                        switch (get_class($card__3))
                        {
                            case 'PlayCard_PlayHighVoltage':
                                $_target__1 = 1;
                            case 'PlayCard_PlayWatchdog':
                                $_target__1 = 2;
                            case 'PlayCard_PlayRut':
                                $_target__1 = 3;
                            case 'PlayCard_PlayHelicopter':
                                $_target__1 = 4;
                            case 'PlayCard_PlayHayBale':
                                $_target__1 = 5;
                            case 'PlayCard_PlayDynamite':
                                $_target__1 = 5;
                            case 'PlayCard_PlayBribe':
                                $_target__1 = 6;
                            default:
                                $_target__1 = 0;
                        }
                        switch ($_target__1)
                        {
                            case 0:
                                return new Cons(new Event_CardPlayed($card__3), new Cons(new Event_SpedUp(new SpedUp($card__3->power instanceof CardPower_Two ? 2 : 1)), FSharpList::get_Nil()));
                            case 1:
                                return new Cons(new Event_CardPlayed($card__3), new Cons(new Event_HighVoltaged(), FSharpList::get_Nil()));
                            case 2:
                                return new Cons(new Event_CardPlayed($card__3), new Cons(new Event_Watched(), FSharpList::get_Nil()));
                            case 3:
                                return new Cons(new Event_CardPlayed($card__3), FSharpList::get_Nil());
                            case 4:
                                if (Shared_002EPlayer___canUseHelicopter($player__16)) {
                                    return FSharpList::ofSeq(Seq::delay(function ($unitVar__22) use ($card__3) {                                     return Seq::append(Seq::singleton(new Event_CardPlayed($card__3)), Seq::delay(function ($unitVar__23) use ($card__3) {                                     return Seq::append(Seq::singleton(new Event_Heliported($card__3->destination)), Seq::delay(function ($unitVar__24) use ($card__3, $p__39) {                                     if (Util::equals($p__39->Power, new Power_PowerDown()) ? Shared_002ECrossroadModule___isInField($p__39->Field, $card__3->destination) : false) {
                                        return Seq::singleton(new Event_PoweredUp());
                                    } else {
                                        return Seq::empty();
                                    }
 }));
 }));
 }));
                                } else {
                                    return FSharpList::get_Nil();
                                }
                            case 5:
                                return new Cons(new Event_CardPlayed($card__3), new Cons(new Event_BonusDiscarded(Shared_002ECardModule___ofPlayCard($card__3)), FSharpList::get_Nil()));
                            case 6:
                                return FSharpList::ofSeq(Seq::delay(function ($unitVar__25) use ($card__3) {                                 return Seq::append(Seq::singleton(new Event_CardPlayed($card__3)), Seq::delay(function ($unitVar__26) use ($otherPlayers__1) {                                 return Seq::append(Seq::collect(function ($matchValue__18) use ($card__3) {                                 if ((function () use ($card__3, $matchValue__18) { 
                                    $arg10_0040 = Shared_002EPlayer___field($matchValue__18[1]);
                                    return Shared_002EFieldModule___containsParcel($card__3->parcel, $arg10_0040);
                                })()) {
                                    return Seq::singleton(new Event_Bribed(new Bribed($card__3->parcel, $matchValue__18[0])));
                                } else {
                                    return Seq::empty();
                                }
 }, $otherPlayers__1), Seq::delay(function ($unitVar__27) use ($card__3) {                                 return Seq::singleton(new Event_BonusDiscarded(Shared_002ECardModule___ofPlayCard($card__3)));
 }));
 }));
 }));
                        }
                    } else {
                        return FSharpList::get_Nil();
                    }
                default:
                    throw new Exception('Invalid operation');
            }
        } else {
            throw new Exception('Invalid operation');
        }
    }
}

#153
function Shared_002EPlayer___evolve($player__19, $event) {
    if ($player__19 instanceof CrazyPlayer_Starting) {
        switch (get_class($event))
        {
            case 'Event_FirstCrossroadSelected':
                $e__1 = $event->Item;
                $p__40 = $player__19->Item;
                return new CrazyPlayer_Playing(new Playing($p__40->Color, $e__1->Crossroad, $GLOBALS['Shared_002EFenceModule___empty'], Shared_002EFieldModule___create($p__40->Parcel), new Power_PowerUp(), Shared_002EMovesModule___startTurn($GLOBALS['Shared_002EFenceModule___empty'], $p__40->Bonus), $p__40->Hand, $p__40->Bonus));
            default:
                return $player__19;
        }
    } else {
        if ($player__19 instanceof CrazyPlayer_Playing) {
            switch (get_class($event))
            {
                case 'Event_FenceDrawn':
                    $e__2 = $event->Item;
                    $player__20 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($e__2, $player__20) { 
                        $Fence = Shared_002EFenceModule___add($e__2->Path, $e__2->Move, $player__20->Fence);
                        $Moves__1 = Shared_002EMovesModule___doMove($player__20->Moves);
                        return new Playing($player__20->Color, $e__2->Crossroad, $Fence, $player__20->Field, $player__20->Power, $Moves__1, $player__20->Hand, $player__20->Bonus);
                    })());
                case 'Event_FenceRemoved':
                    $e__3 = $event->Item;
                    $player__21 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($e__3, $player__21) { 
                        $Fence__1 = Shared_002EFenceModule___tail($player__21->Fence);
                        $Moves__2 = Shared_002EMovesModule___doMove($player__21->Moves);
                        return new Playing($player__21->Color, $e__3->Crossroad, $Fence__1, $player__21->Field, $player__21->Power, $Moves__2, $player__21->Hand, $player__21->Bonus);
                    })());
                case 'Event_FenceLooped':
                    $e__4 = $event->Item;
                    $player__22 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($e__4, $player__22) { 
                        $Fence__2 = Shared_002EFenceModule___remove($e__4->Loop, $player__22->Fence);
                        $Moves__3 = Shared_002EMovesModule___doMove($player__22->Moves);
                        return new Playing($player__22->Color, $e__4->Crossroad, $Fence__2, $player__22->Field, $player__22->Power, $Moves__3, $player__22->Hand, $player__22->Bonus);
                    })());
                case 'Event_MovedInField':
                    $e__5 = $event->Item;
                    $player__23 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($e__5, $player__23) { 
                        $Moves__4 = Shared_002EMovesModule___doMove($player__23->Moves);
                        return new Playing($player__23->Color, $e__5->Crossroad, $GLOBALS['Shared_002EFenceModule___empty'], $player__23->Field, $player__23->Power, $Moves__4, $player__23->Hand, $player__23->Bonus);
                    })());
                case 'Event_MovedPowerless':
                    $e__6 = $event->Item;
                    $player__24 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($e__6, $player__24) { 
                        $Moves__5 = Shared_002EMovesModule___doMove($player__24->Moves);
                        return new Playing($player__24->Color, $e__6->Crossroad, $player__24->Fence, $player__24->Field, $player__24->Power, $Moves__5, $player__24->Hand, $player__24->Bonus);
                    })());
                case 'Event_PoweredUp':
                    $player__25 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($player__25) { 
                        $Power = new Power_PowerUp();
                        return new Playing($player__25->Color, $player__25->Tractor, $player__25->Fence, $player__25->Field, $Power, $player__25->Moves, $player__25->Hand, $player__25->Bonus);
                    })());
                case 'Event_Annexed':
                    $e__7 = $event->Item;
                    $player__26 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($e__7, $player__26) { 
                        $Field = Shared_002EField___op_Addition__Z24735800($player__26->Field, Shared_002EFieldModule___ofParcels($e__7->NewField));
                        return new Playing($player__26->Color, $player__26->Tractor, $GLOBALS['Shared_002EFenceModule___empty'], $Field, $player__26->Power, $player__26->Moves, $player__26->Hand, $player__26->Bonus);
                    })());
                case 'Event_HighVoltaged':
                    $player__27 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($player__27) { 
                        $Bonus = new Bonus($player__27->Bonus->NitroOne, $player__27->Bonus->NitroTwo, $player__27->Bonus->Watched, true, $player__27->Bonus->Rutted, $player__27->Bonus->Heliported);
                        return new Playing($player__27->Color, $player__27->Tractor, $player__27->Fence, $player__27->Field, $player__27->Power, $player__27->Moves, $player__27->Hand, $Bonus);
                    })());
                case 'Event_Watched':
                    $player__28 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($player__28) { 
                        $Bonus__1 = new Bonus($player__28->Bonus->NitroOne, $player__28->Bonus->NitroTwo, true, $player__28->Bonus->HighVoltage, $player__28->Bonus->Rutted, $player__28->Bonus->Heliported);
                        return new Playing($player__28->Color, $player__28->Tractor, $player__28->Fence, $player__28->Field, $player__28->Power, $player__28->Moves, $player__28->Hand, $Bonus__1);
                    })());
                case 'Event_Rutted':
                    $player__29 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($player__29) { 
                        $Rutted__1 = $player__29->Bonus->Rutted + 1;
                        $Bonus__2 = new Bonus($player__29->Bonus->NitroOne, $player__29->Bonus->NitroTwo, $player__29->Bonus->Watched, $player__29->Bonus->HighVoltage, $Rutted__1, $player__29->Bonus->Heliported);
                        return new Playing($player__29->Color, $player__29->Tractor, $player__29->Fence, $player__29->Field, $player__29->Power, $player__29->Moves, $player__29->Hand, $Bonus__2);
                    })());
                case 'Event_SpedUp':
                    $e__8 = $event->Item;
                    $player__30 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($e__8, $player__30) { 
                        $Moves__6 = Shared_002EMovesModule___addCapacity($e__8->Speed, $player__30->Moves);
                        return new Playing($player__30->Color, $player__30->Tractor, $player__30->Fence, $player__30->Field, $player__30->Power, $Moves__6, $player__30->Hand, $player__30->Bonus);
                    })());
                case 'Event_Heliported':
                    $e__9 = $event->Item;
                    $player__31 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($e__9, $player__31) { 
                        $Heliported__1 = $player__31->Bonus->Heliported + 1;
                        $Bonus__3 = new Bonus($player__31->Bonus->NitroOne, $player__31->Bonus->NitroTwo, $player__31->Bonus->Watched, $player__31->Bonus->HighVoltage, $player__31->Bonus->Rutted, $Heliported__1);
                        return new Playing($player__31->Color, $e__9, $GLOBALS['Shared_002EFenceModule___empty'], $player__31->Field, $player__31->Power, $player__31->Moves, $player__31->Hand, $Bonus__3);
                    })());
                case 'Event_Bribed':
                    $p__41 = $event->Item;
                    $player__32 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($p__41, $player__32) { 
                        $Field__1 = Shared_002EField___op_Addition__Z24735800($player__32->Field, Shared_002EFieldModule___ofParcels(new Cons($p__41->Parcel, FSharpList::get_Nil())));
                        return new Playing($player__32->Color, $player__32->Tractor, $player__32->Fence, $Field__1, $player__32->Power, $player__32->Moves, $player__32->Hand, $player__32->Bonus);
                    })());
                case 'Event_CardPlayed':
                    $card__4 = $event->Item;
                    $player__33 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($Bonus__4, $card__4, $player__33) { 
                        $card__5 = Shared_002ECardModule___ofPlayCard($card__4);
                        $Hand__2 = Shared_002EHandModule___remove($card__5, $player__33->Hand);
                        if ($card__4 instanceof PlayCard_PlayNitro) {
                            switch (get_class($card__4->power))
                            {
                                case 'CardPower_Two':
                                    $NitroTwo__1 = $player__33->Bonus->NitroTwo + 1;
                                    $Bonus__4 = new Bonus($player__33->Bonus->NitroOne, $NitroTwo__1, $player__33->Bonus->Watched, $player__33->Bonus->HighVoltage, $player__33->Bonus->Rutted, $player__33->Bonus->Heliported);
                                default:
                                    $Bonus__4 = new Bonus($player__33->Bonus->NitroOne + 1, $player__33->Bonus->NitroTwo, $player__33->Bonus->Watched, $player__33->Bonus->HighVoltage, $player__33->Bonus->Rutted, $player__33->Bonus->Heliported);
                            }
                        } else {
                            $Bonus__4 = $player__33->Bonus;
                        }
                        return new Playing($player__33->Color, $player__33->Tractor, $player__33->Fence, $player__33->Field, $player__33->Power, $player__33->Moves, $Hand__2, $Bonus__4);
                    })());
                case 'Event_BonusDiscarded':
                    $e__10 = $event->Item;
                    $player__34 = $player__19->Item;
                    return new CrazyPlayer_Playing((function () use ($e__10, $player__34) { 
                        $Bonus__5 = Shared_002EBonusModule___discard($e__10, $player__34->Bonus);
                        return new Playing($player__34->Color, $player__34->Tractor, $player__34->Fence, $player__34->Field, $player__34->Power, $player__34->Moves, $player__34->Hand, $Bonus__5);
                    })());
                case 'Event_Eliminated':
                    $player__35 = $player__19->Item;
                    return new CrazyPlayer_Ko($player__35->Color);
                default:
                    return $player__19;
            }
        } else {
            return $player__19;
        }
    }
}

#154
function Shared_002EPlayer___exec($otherPlayers__2, $barns__5, $cmd__2, $state) {
    $list__12 = Shared_002EPlayer___decide($otherPlayers__2, $barns__5, $cmd__2, $state);
    return FSharpList::fold('Shared_002EPlayer___evolve', $state, $list__12);
}

#155
function Shared_002EPlayer___move($dir__8, $player__38) {
    if ($player__38 instanceof CrazyPlayer_Playing) {
        $otherPlayers__3 = FSharpList::get_Nil();
        $cmd__3 = new Command_Move(new PlayerMove($dir__8, $player__38->Item->Tractor));
        return Shared_002EPlayer___exec($otherPlayers__3, $GLOBALS['Shared_002EBarnsModule___empty'], $cmd__3, $player__38);
    } else {
        throw new Exception('Not playing');
    }
}

#156
function Shared_002EPlayer___start($color__2, $parcel__4, $pos__8) {
    $state__2 = new CrazyPlayer_Starting(new Starting($color__2, $parcel__4, new Hand_PublicHand(FSharpList::get_Nil()), $GLOBALS['Shared_002EBonusModule___empty']));
    $otherPlayers__4 = FSharpList::get_Nil();
    $cmd__4 = new Command_SelectFirstCrossroad(new SelectFirstCrossroad($pos__8));
    return Shared_002EPlayer___exec($otherPlayers__4, $GLOBALS['Shared_002EBarnsModule___empty'], $cmd__4, $state__2);
}

#157
function Shared_002EPlayer___possibleMove($player__39, $dir__9) {
    $pos__9 = Shared_002ECrossroadModule___neighbor($dir__9, $player__39->Tractor);
    if (Shared_002ECrossroadModule___isOnBoard($pos__9)) {
        return new Cons([ $dir__9, new Ok($pos__9)], FSharpList::get_Nil());
    } else {
        return FSharpList::get_Nil();
    }
}

#158
function Shared_002EPlayer___possibleMoves($player__40) {
    if ($player__40 instanceof CrazyPlayer_Playing) {
        if (Shared_002EMovesModule___canMove($player__40->Item->Moves)) {
            $player__42 = $player__40->Item;
            $list__13 = new Cons(new Direction_Up(), new Cons(new Direction_Down(), new Cons(new Direction_Horizontal(), FSharpList::get_Nil())));
            return FSharpList::collect(function ($dir__10) use ($player__42) {             return Shared_002EPlayer___possibleMove($player__42, $dir__10);
 }, $list__13);
        } else {
            return FSharpList::get_Nil();
        }
    } else {
        return FSharpList::get_Nil();
    }
}

#159
function Shared_002EPlayer___bindMove($f, $cr) {
    switch (get_class($cr))
    {
        case 'Error':
            return new Error($cr->ErrorValue);
        default:
            return $f($cr->ResultValue);
    }
}

#160
function Shared_002EPlayer___op_GreaterGreaterEquals($c__4, $f__1) {
    return Shared_002EPlayer___bindMove($f__1, $c__4);
}

#161
function Shared_002EPlayer___checkTractor($player__43, $c__5) {
    if (Util::equals($c__5, $player__43->Tractor)) {
        return new Error([ $c__5, new MoveBlocker_Tractor()]);
    } else {
        return new Ok($c__5);
    }
}

#162
function Shared_002EPlayer___checkProtection($player__44, $c__6) {
    $fence__4 = Shared_002EFenceModule___fenceCrossroads($player__44->Tractor, $player__44->Fence);
    $matchValue__20 = Seq::tryFindIndex(function ($p__43) use ($c__6) {     return Util::equals($p__43, $c__6);
 }, $fence__4);
    if (!is_null($matchValue__20)) {
        $i__1 = $matchValue__20;
        if ($player__44->Bonus->HighVoltage) {
            return new Error([ $c__6, new MoveBlocker_HighVoltageProtection()]);
        } else {
            if ($i__1 < 2) {
                return new Error([ $c__6, new MoveBlocker_Protection()]);
            } else {
                return new Ok($c__6);
            }
        }
    } else {
        return new Ok($c__6);
    }
}

#163
function Shared_002EPlayer___checkHeliported($moverBonus, $player__45, $c__7) {
    if ($moverBonus->Heliported > 0) {
        $source__3 = Shared_002EFenceModule___fenceCrossroads($player__45->Tractor, $player__45->Fence);
        $isOnFence = Seq::exists(function ($p__44) use ($c__7) {         return Util::equals($p__44, $c__7);
 }, $source__3);
        if ($isOnFence) {
            return new Error([ $c__7, new MoveBlocker_PhytosanitaryProducts()]);
        } else {
            return new Ok($c__7);
        }
    } else {
        return new Ok($c__7);
    }
}

#164
function Shared_002EPlayer___checkMove($moverbonus, $player__46, $c__8) {
    if ($player__46 instanceof CrazyPlayer_Playing) {
        return Shared_002EPlayer___op_GreaterGreaterEquals(Shared_002EPlayer___op_GreaterGreaterEquals(Shared_002EPlayer___checkTractor($player__46->Item, $c__8), function ($c__9) use ($player__46) {         return Shared_002EPlayer___checkProtection($player__46->Item, $c__9);
 }), function ($c__10) use ($moverbonus, $player__46) {         return Shared_002EPlayer___checkHeliported($moverbonus, $player__46->Item, $c__10);
 });
    } else {
        return new Ok($c__8);
    }
}

#165
function Shared_002EPlayer___takeCards($cards, $player__48) {
    switch (get_class($player__48))
    {
        case 'CrazyPlayer_Starting':
            return new CrazyPlayer_Starting((function () use ($Hand__4, $player__48) { 
                if ($player__48->Item->Hand instanceof Hand_PrivateHand) {
                    switch (get_class($cards))
                    {
                        case 'Hand_PrivateHand':
                            $c__14 = $cards->cards;
                            $h__3 = $player__48->Item->Hand->cards;
                            $Hand__4 = new Hand_PrivateHand($h__3 + $c__14);
                        default:
                            throw new Exception('Unexpected mix');
                    }
                } else {
                    switch (get_class($cards))
                    {
                        case 'Hand_PublicHand':
                            $c__13 = $cards->cards;
                            $h__2 = $player__48->Item->Hand->cards;
                            $Hand__4 = new Hand_PublicHand(FSharpList::append($h__2, $c__13));
                        default:
                            throw new Exception('Unexpected mix');
                    }
                }
                return new Starting($player__48->Item->Color, $player__48->Item->Parcel, $Hand__4, $player__48->Item->Bonus);
            })());
        case 'CrazyPlayer_Ko':
            return $player__48;
        default:
            return new CrazyPlayer_Playing((function () use ($Hand__3, $player__48) { 
                if ($player__48->Item->Hand instanceof Hand_PrivateHand) {
                    switch (get_class($cards))
                    {
                        case 'Hand_PrivateHand':
                            $c__12 = $cards->cards;
                            $h__1 = $player__48->Item->Hand->cards;
                            $Hand__3 = new Hand_PrivateHand($h__1 + $c__12);
                        default:
                            throw new Exception('Unexpected mix');
                    }
                } else {
                    switch (get_class($cards))
                    {
                        case 'Hand_PublicHand':
                            $c__11 = $cards->cards;
                            $h = $player__48->Item->Hand->cards;
                            $Hand__3 = new Hand_PublicHand(FSharpList::append($h, $c__11));
                        default:
                            throw new Exception('Unexpected mix');
                    }
                }
                return new Playing($player__48->Item->Color, $player__48->Item->Tractor, $player__48->Item->Fence, $player__48->Item->Field, $player__48->Item->Power, $player__48->Item->Moves, $Hand__3, $player__48->Item->Bonus);
            })());
    }
}

#166
function Shared_002EPlayer___toState($p__47) {
    switch (get_class($p__47))
    {
        case 'CrazyPlayer_Playing':
            return new PlayerState_SPlaying(new PlayingState($p__47->Item->Color, $p__47->Item->Tractor, $p__47->Item->Fence, Set::toList($p__47->Item->Field->parcels), $p__47->Item->Power, $p__47->Item->Moves, $p__47->Item->Hand, $p__47->Item->Bonus));
        case 'CrazyPlayer_Ko':
            return new PlayerState_SKo($p__47->Item);
        default:
            return new PlayerState_SStarting(new StartingState($p__47->Item->Color, $p__47->Item->Parcel, $p__47->Item->Hand, $p__47->Item->Bonus));
    }
}

#167
function Shared_002EPlayer___ofState($p__50) {
    switch (get_class($p__50))
    {
        case 'PlayerState_SPlaying':
            return new CrazyPlayer_Playing(new Playing($p__50->Item->SColor, $p__50->Item->STractor, $p__50->Item->SFence, new Field(Set::ofSeq($p__50->Item->SField, [ 'Compare' => function ($_x__42, $_y__43) {             return $_x__42->CompareTo($_y__43);
 }])), $p__50->Item->SPower, $p__50->Item->SMoves, $p__50->Item->SHand, $p__50->Item->SBonus));
        case 'PlayerState_SKo':
            return new CrazyPlayer_Ko($p__50->Item);
        default:
            return new CrazyPlayer_Starting(new Starting($p__50->Item->SColor, $p__50->Item->SParcel, $p__50->Item->SHand, $p__50->Item->SBonus));
    }
}

#168
$GLOBALS['Shared_002EDrawPile___cards'] = (function () { 
    $list__14 = new Cons([ new Card_Nitro(new CardPower_One()), 6], new Cons([ new Card_Nitro(new CardPower_Two()), 3], new Cons([ new Card_Rut(), 2], new Cons([ new Card_HayBale(new CardPower_One()), 4], new Cons([ new Card_HayBale(new CardPower_Two()), 3], new Cons([ new Card_Dynamite(), 4], new Cons([ new Card_HighVoltage(), 3], new Cons([ new Card_Watchdog(), 2], new Cons([ new Card_Helicopter(), 6], new Cons([ new Card_Bribe(), 3], FSharpList::get_Nil()))))))))));
    return FSharpList::collect(function ($tupledArg__4) {     return FSharpList::ofSeq(Seq::delay(function ($unitVar__28) use ($tupledArg__4) {     return Seq::collect(function ($matchValue__23) use ($tupledArg__4) {     return Seq::singleton($tupledArg__4[0]);
 }, Seq::rangeNumber(1, 1, $tupledArg__4[1]));
 }));
 }, $list__14);
})();

#169
function Shared_002EDrawPile___shuffle($cards__1) {
    $rand = [ ];
    return FSharpList::sortBy(function ($_arg1__47) {     return Util::randomNext(0, 2147483647);
 }, $cards__1, [ 'Compare' => 'Util::comparePrimitives']);
}

#170
function Shared_002EDrawPile___remove($cards__2, $pile) {
    $count = Shared_002EHandModule___count($cards__2);
    $count__1 = Util::min('Util::comparePrimitives', FSharpList::length($pile), $count);
    return FSharpList::skip($count__1, $pile);
}

#171
function Shared_002EDrawPile___take($count__2, $pile__1) {
    return FSharpList::truncate($count__2, $pile__1);
}

#172
$GLOBALS['Shared_002EBoardModule___initialState'] = new Board_InitialState();

#173
abstract class BoardCommand {
}

#173
class BoardCommand_Play extends BoardCommand {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
}

#173
class BoardCommand_Start extends BoardCommand {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#174
class BoardStart {
    public $Players;
    public $Goal;
    function __construct($Players, $Goal) {
        $this->Players = $Players;
        $this->Goal = $Goal;
    }
}

#175
abstract class BoardEvent {
}

#175
class BoardEvent_Played extends BoardEvent {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
}

#175
class BoardEvent_Started extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#175
class BoardEvent_Next extends BoardEvent {
    function __construct() {
    }
}

#175
class BoardEvent_PlayerDrewCards extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#175
class BoardEvent_GameWon extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#175
class BoardEvent_HayBalesPlaced extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#175
class BoardEvent_HayBaleDynamited extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#175
class BoardEvent_DiscardPileShuffled extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#176
class BoardStarted {
    public $Players;
    public $DrawPile;
    public $Barns;
    public $Goal;
    function __construct($Players, $DrawPile, $Barns, $Goal) {
        $this->Players = $Players;
        $this->DrawPile = $DrawPile;
        $this->Barns = $Barns;
        $this->Goal = $Goal;
    }
}

#177
class PlayerDrewCards {
    public $Player;
    public $Cards;
    function __construct($Player, $Cards) {
        $this->Player = $Player;
        $this->Cards = $Cards;
    }
}

#178
function Shared_002EBoardModule___currentPlayer($board) {
    return Map::FSharpMap__get_Item__2B595($board->Players, Shared_002EGameTable__get_Player($board->Table, NULL));
}

#179
function Shared_002EBoardModule___otherPlayers($playerid__3, $board__1) {
    $source__4 = Map::toSeq($board__1->Players);
    $source__5 = Seq::filter(function ($tupledArg__5) use ($playerid__3) {     return $tupledArg__5[0] !== $playerid__3;
 }, $source__4);
    return FSharpList::ofSeq($source__5);
}

#180
function Shared_002EBoardModule___currentOtherPlayers($board__2) {
    return Shared_002EBoardModule___otherPlayers(Shared_002EGameTable__get_Player($board__2->Table, NULL), $board__2);
}

#181
function Shared_002EBoardModule___totalSize($board__3) {
    return Map::fold(function ($count__3, $_arg1__49, $p__53) {     return $count__3 + Shared_002EPlayer___fieldTotalSize($p__53);
 }, 0, $board__3->Players);
}

#182
function Shared_002EBoardModule___endGameWithBribe($board__4) {
    switch (get_class($board__4->Goal))
    {
        case 'Goal_Individual':
            $player__49 = Shared_002EBoardModule___currentPlayer($board__4);
            return (Shared_002EPlayer___fieldTotalSize($player__49) + 1) >= $board__4->Goal->Item;
        default:
            return (Shared_002EBoardModule___totalSize($board__4) + 1) >= $board__4->Goal->Item;
    }
}

#183
function Shared_002EBoardModule___tryFindWinner($board__5) {
    switch (get_class($board__5->Goal))
    {
        case 'Goal_Individual':
            $won = Map::exists(function ($_arg2__4, $p__55) use ($board__5) {             return Shared_002EPlayer___fieldTotalSize($p__55) >= $board__5->Goal->Item;
 }, $board__5->Players);
            if ($won) {
                $source__6 = Map::toSeq($board__5->Players);
                $arg0__5 = Seq::maxBy(function ($tupledArg__7) {                 return Shared_002EPlayer___principalFieldSize($tupledArg__7[1]);
 }, $source__6, [ 'Compare' => 'Util::comparePrimitives']);
                return $arg0__5;
            } else {
                return NULL;
            }
        default:
            if (Shared_002EBoardModule___totalSize($board__5) >= $board__5->Goal->Item) {
                $list__17 = Map::toList($board__5->Players);
                $arg0__4 = FSharpList::maxBy(function ($tupledArg__6) {                 return Shared_002EPlayer___principalFieldSize($tupledArg__6[1]);
 }, $list__17, [ 'Compare' => 'Util::comparePrimitives']);
                return $arg0__4;
            } else {
                return NULL;
            }
    }
}

#184
function Shared_002EBoardModule___next($state__4) {
    $playerId__1 = Shared_002EGameTable__get_Player($state__4->Table, NULL);
    $player__50 = Map::FSharpMap__get_Item__2B595($state__4->Players, $playerId__1);
    $nextPlayerId = Shared_002EGameTable__get_Player(Shared_002EGameTable__get_Next($state__4->Table, NULL), NULL);
    $nextPlayer = Map::FSharpMap__get_Item__2B595($state__4->Players, $nextPlayerId);
    return FSharpList::ofSeq(Seq::delay(function ($unitVar__29) {     return Seq::append((function () use ($player__50) { 
        $list__18 = Shared_002EBonusModule___endTurn(Shared_002EPlayer___bonus($player__50));
        return FSharpList::map(function ($c__16) use ($playerId__1) {         return new BoardEvent_Played($playerId__1, new Event_BonusDiscarded($c__16));
 }, $list__18);
    })(), Seq::delay(function ($unitVar__30) {     return Seq::append(Seq::singleton(new BoardEvent_Next()), Seq::delay(function ($unitVar__31) {     return (function () use ($nextPlayer) { 
        $list__19 = Shared_002EBonusModule___startTurn(Shared_002EPlayer___bonus($nextPlayer));
        return FSharpList::map(function ($c__17) use ($nextPlayerId) {         return new BoardEvent_Played($nextPlayerId, new Event_BonusDiscarded($c__17));
 }, $list__19);
    })();
 }));
 }));
 }));
}

#185
abstract class BribeBocker {
}

#185
class BribeBocker_InstantVictory extends BribeBocker {
    function __construct() {
    }
}

#185
class BribeBocker_NoParcelsToBribe extends BribeBocker {
    function __construct() {
    }
}

#186
function Shared_002EBoardModule___bribeParcels($board__6) {
    if (Shared_002EBoardModule___endGameWithBribe($board__6)) {
        return new Error(new BribeBocker_InstantVictory());
    } else {
        $player__51 = Map::FSharpMap__get_Item__2B595($board__6->Players, Shared_002EGameTable__get_Player($board__6->Table, NULL));
        $border__2 = Shared_002EFieldModule___borderTiles(Shared_002EPlayer___field($player__51));
        $barns__6 = Shared_002EField___op_Addition__Z24735800($board__6->Barns->Free, $board__6->Barns->Occupied);
        $list__20 = Shared_002EBoardModule___currentOtherPlayers($board__6);
        $fields__1 = FSharpList::map(function ($tupledArg__8) { 
            $field__9 = Shared_002EPlayer___field($tupledArg__8[1]);
            $bonus__6 = Shared_002EPlayer___bonus($tupledArg__8[1]);
            if (Shared_002EFieldModule___size($field__9) === 1 ? true : $bonus__6->Watched) {
                return $GLOBALS['Shared_002EFieldModule___empty'];
            } else {
                switch (get_class($tupledArg__8[1]))
                {
                    case 'CrazyPlayer_Starting':
                        $_target__1 = 1;
                    case 'CrazyPlayer_Ko':
                        $_target__1 = 1;
                    default:
                        $_target__1 = 0;
                }
                switch ($_target__1)
                {
                    case 0:
                        $startCrossRoad = Shared_002EFenceModule___start($tupledArg__8[1]->Item->Tractor, $tupledArg__8[1]->Item->Fence);
                        $parcels__7 = Shared_002ECrossroadModule___neighborTiles($startCrossRoad);
                        $startTiles = Shared_002EFieldModule___ofParcels($parcels__7);
                        return Shared_002EField___op_Subtraction__Z24735800($field__9, $startTiles);
                    case 1:
                        return $field__9;
                }
            }
        }, $list__20);
        $otherPlayersFields = Shared_002EFieldModule___unionMany($fields__1);
        $parcelsToBribe = Shared_002EField___op_Subtraction__Z24735800(Shared_002EFieldModule___interesect($border__2, $otherPlayersFields), $barns__6);
        if (Shared_002EFieldModule___isEmpty($parcelsToBribe)) {
            return new Error(new BribeBocker_NoParcelsToBribe());
        } else {
            return new Ok($parcelsToBribe);
        }
    }
}

#187
function Shared_002EBoardModule___annexed($playerid__4, $e__12, $board__7) {
    $annexedPlayer = Shared_002EPlayer___evolve(Map::FSharpMap__get_Item__2B595($board__7->Players, $playerid__4), new Event_Annexed($e__12));
    $newMap = Map::add($playerid__4, $annexedPlayer, $board__7->Players);
    $annexedBarns__1 = new Barns(Shared_002EFieldModule___ofParcels($e__12->FreeBarns), Shared_002EFieldModule___ofParcels($e__12->OccupiedBarns));
    $Barns = Shared_002EBarnsModule___annex($annexedBarns__1, $board__7->Barns);
    $state__5 = new PlayingBoard($newMap, $board__7->Table, $board__7->DrawPile, $board__7->DiscardPile, $Barns, $board__7->HayBales, $board__7->Goal);
    return FSharpList::fold(function ($map, $tupledArg__9) use ($board__7) { 
        $matchValue__26 = Map::FSharpMap__get_Item__2B595($board__7->Players, $tupledArg__9[0]);
        if ($matchValue__26 instanceof CrazyPlayer_Playing) {
            $newP = new CrazyPlayer_Playing((function () use ($matchValue__26, $tupledArg__9) { 
                $Field__2 = Shared_002EField___op_Subtraction__Z24735800($matchValue__26->Item->Field, Shared_002EFieldModule___ofParcels($tupledArg__9[1]));
                return new Playing($matchValue__26->Item->Color, $matchValue__26->Item->Tractor, $matchValue__26->Item->Fence, $Field__2, $matchValue__26->Item->Power, $matchValue__26->Item->Moves, $matchValue__26->Item->Hand, $matchValue__26->Item->Bonus);
            })());
            return new PlayingBoard(Map::add($tupledArg__9[0], $newP, $map->Players), $board__7->Table, $board__7->DrawPile, $board__7->DiscardPile, $board__7->Barns, $board__7->HayBales, $board__7->Goal);
        } else {
            return $map;
        }
    }, $state__5, $e__12->LostFields);
}

#188
function Shared_002EBoardModule___evolve($state__6, $event__2) {
    if ($state__6 instanceof Board_Board) {
        if ($event__2 instanceof BoardEvent_GameWon) {
            $board__8 = $state__6->Item;
            $player__53 = $event__2->Item;
            return new Board_Won($player__53, $board__8);
        } else {
            if ($event__2 instanceof BoardEvent_Played) {
                switch (get_class($event__2->Item2))
                {
                    case 'Event_CutFence':
                        $board__9 = $state__6->Item;
                        $playerid__6 = $event__2->Item2->Item->Player;
                        $matchValue__30 = Map::FSharpMap__get_Item__2B595($board__9->Players, $playerid__6);
                        if ($matchValue__30 instanceof CrazyPlayer_Playing) {
                            $cutPlayer = new CrazyPlayer_Playing((function () use ($matchValue__30) { 
                                $Power__1 = new Power_PowerDown();
                                return new Playing($matchValue__30->Item->Color, $matchValue__30->Item->Tractor, $GLOBALS['Shared_002EFenceModule___empty'], $matchValue__30->Item->Field, $Power__1, $matchValue__30->Item->Moves, $matchValue__30->Item->Hand, $matchValue__30->Item->Bonus);
                            })());
                            return new Board_Board(new PlayingBoard(Map::add($playerid__6, $cutPlayer, $board__9->Players), $board__9->Table, $board__9->DrawPile, $board__9->DiscardPile, $board__9->Barns, $board__9->HayBales, $board__9->Goal));
                        } else {
                            return $state__6;
                        }
                    case 'Event_Annexed':
                        $board__10 = $state__6->Item;
                        $e__13 = $event__2->Item2->Item;
                        $playerid__7 = $event__2->Item1;
                        $arg0__6 = Shared_002EBoardModule___annexed($playerid__7, $e__13, $board__10);
                        return new Board_Board($arg0__6);
                    case 'Event_Bribed':
                        $board__15 = $state__6->Item;
                        $e__15 = $event__2->Item2;
                        $p__63 = $event__2->Item2->Item;
                        $playerid__8 = $event__2->Item1;
                        $newPlayer = Shared_002EPlayer___evolve(Map::FSharpMap__get_Item__2B595($board__15->Players, $playerid__8), $e__15);
                        $matchValue__31 = Map::FSharpMap__get_Item__2B595($board__15->Players, $p__63->Victim);
                        switch (get_class($matchValue__31))
                        {
                            case 'CrazyPlayer_Playing':
                                $newVictim = new CrazyPlayer_Playing((function () use ($matchValue__31, $p__63) { 
                                    $Field__3 = Shared_002EField___op_Subtraction__Z24735800($matchValue__31->Item->Field, Shared_002EFieldModule___ofParcels(new Cons($p__63->Parcel, FSharpList::get_Nil())));
                                    return new Playing($matchValue__31->Item->Color, $matchValue__31->Item->Tractor, $matchValue__31->Item->Fence, $Field__3, $matchValue__31->Item->Power, $matchValue__31->Item->Moves, $matchValue__31->Item->Hand, $matchValue__31->Item->Bonus);
                                })());
                            case 'CrazyPlayer_Ko':
                                $newVictim = $matchValue__31;
                            default:
                                $newVictim = new CrazyPlayer_Starting($matchValue__31->Item);
                        }
                        return new Board_Board(new PlayingBoard((function () use ($board__15, $newPlayer, $newVictim, $p__63, $playerid__8) { 
                            $table__8 = Map::add($playerid__8, $newPlayer, $board__15->Players);
                            return Map::add($p__63->Victim, $newVictim, $table__8);
                        })(), $board__15->Table, $board__15->DrawPile, $board__15->DiscardPile, $board__15->Barns, $board__15->HayBales, $board__15->Goal));
                    case 'Event_Eliminated':
                        $board__16 = $state__6->Item;
                        $e__16 = $event__2->Item2;
                        $playerid__9 = $event__2->Item1;
                        $newPlayer__1 = Shared_002EPlayer___evolve(Map::FSharpMap__get_Item__2B595($board__16->Players, $playerid__9), $e__16);
                        $newTable = Shared_002ETable___eliminate($playerid__9, $board__16->Table);
                        return new Board_Board(new PlayingBoard(Map::add($playerid__9, $newPlayer__1, $board__16->Players), $newTable, $board__16->DrawPile, $board__16->DiscardPile, $board__16->Barns, $board__16->HayBales, $board__16->Goal));
                    default:
                        $board__17 = $state__6->Item;
                        $e__17 = $event__2->Item2;
                        $playerid__10 = $event__2->Item1;
                        $player__57 = Shared_002EPlayer___evolve(Map::FSharpMap__get_Item__2B595($board__17->Players, $playerid__10), $e__17);
                        if ($e__17 instanceof Event_BonusDiscarded) {
                            $newDiscardPile = new Cons($e__17->Item, $board__17->DiscardPile);
                        } else {
                            $newDiscardPile = $board__17->DiscardPile;
                        }
                        return new Board_Board((function () use ($board__17, $newDiscardPile, $player__57, $playerid__10) { 
                            $Players__1 = Map::add($playerid__10, $player__57, $board__17->Players);
                            return new PlayingBoard($Players__1, $board__17->Table, $board__17->DrawPile, $newDiscardPile, $board__17->Barns, $board__17->HayBales, $board__17->Goal);
                        })());
                }
            } else {
                switch (get_class($event__2))
                {
                    case 'BoardEvent_PlayerDrewCards':
                        $board__11 = $state__6->Item;
                        $e__14 = $event__2->Item;
                        $newDrawPile = Shared_002EDrawPile___remove($e__14->Cards, $board__11->DrawPile);
                        $player__55 = Map::FSharpMap__get_Item__2B595($board__11->Players, $e__14->Player);
                        $player__56 = Shared_002EPlayer___takeCards($e__14->Cards, $player__55);
                        return new Board_Board((function () use ($board__11, $e__14, $newDrawPile, $player__56) { 
                            $Players = Map::add($e__14->Player, $player__56, $board__11->Players);
                            return new PlayingBoard($Players, $board__11->Table, $newDrawPile, $board__11->DiscardPile, $board__11->Barns, $board__11->HayBales, $board__11->Goal);
                        })());
                    case 'BoardEvent_HayBalesPlaced':
                        $board__12 = $state__6->Item;
                        $p__61 = $event__2->Item;
                        return new Board_Board((function () use ($board__12, $p__61) { 
                            $HayBales = Set::FSharpSet___op_Addition($board__12->HayBales, Set::ofSeq($p__61, [ 'Compare' => function ($_x__56, $_y__57) {                             return $_x__56->CompareTo($_y__57);
 }]));
                            return new PlayingBoard($board__12->Players, $board__12->Table, $board__12->DrawPile, $board__12->DiscardPile, $board__12->Barns, $HayBales, $board__12->Goal);
                        })());
                    case 'BoardEvent_HayBaleDynamited':
                        $board__13 = $state__6->Item;
                        $p__62 = $event__2->Item;
                        return new Board_Board((function () use ($board__13, $p__62) { 
                            $HayBales__1 = Set::remove($p__62, $board__13->HayBales);
                            return new PlayingBoard($board__13->Players, $board__13->Table, $board__13->DrawPile, $board__13->DiscardPile, $board__13->Barns, $HayBales__1, $board__13->Goal);
                        })());
                    case 'BoardEvent_DiscardPileShuffled':
                        $board__14 = $state__6->Item;
                        $cards__5 = $event__2->Item;
                        return new Board_Board((function () use ($board__14, $cards__5) { 
                            $DrawPile = FSharpList::append($board__14->DrawPile, $cards__5);
                            $DiscardPile = FSharpList::get_Nil();
                            return new PlayingBoard($board__14->Players, $board__14->Table, $DrawPile, $DiscardPile, $board__14->Barns, $board__14->HayBales, $board__14->Goal);
                        })());
                    case 'BoardEvent_Next':
                        $board__18 = $state__6->Item;
                        $nextTable = Shared_002EGameTable__get_Next($board__18->Table, NULL);
                        $player__58 = Shared_002EPlayer___startTurn(Map::FSharpMap__get_Item__2B595($board__18->Players, Shared_002EGameTable__get_Player($nextTable, NULL)));
                        return new Board_Board(new PlayingBoard(Map::add(Shared_002EGameTable__get_Player($nextTable, NULL), $player__58, $board__18->Players), $nextTable, $board__18->DrawPile, $board__18->DiscardPile, $board__18->Barns, $board__18->HayBales, $board__18->Goal));
                    default:
                        return $state__6;
                }
            }
        }
    } else {
        if ($state__6 instanceof Board_Won) {
            return $state__6;
        } else {
            switch (get_class($event__2))
            {
                case 'BoardEvent_Started':
                    $s__1 = $event__2->Item;
                    return new Board_Board(new PlayingBoard(Map::ofList(FSharpList::ofSeq(Seq::delay(function ($unitVar__32) use ($s__1) {                     return Seq::collect(function ($matchValue__28) {                     return Seq::singleton([ $matchValue__28[1], new CrazyPlayer_Starting(new Starting($matchValue__28[0], $matchValue__28[3], new Hand_PublicHand(FSharpList::get_Nil()), $GLOBALS['Shared_002EBonusModule___empty']))]);
 }, $s__1->Players);
 })), [ 'Compare' => 'Util::comparePrimitives']), Shared_002ETable___start(FSharpList::ofSeq(Seq::delay(function ($unitVar__33) use ($s__1) {                     return Seq::collect(function ($matchValue__29) {                     return Seq::singleton([ $matchValue__29[1], $matchValue__29[2]]);
 }, $s__1->Players);
 }))), $s__1->DrawPile, FSharpList::get_Nil(), Shared_002EBarnsModule___init($s__1->Barns), Set::empty([ 'Compare' => function ($_x__54, $_y__55) {                     return $_x__54->CompareTo($_y__55);
 }]), $s__1->Goal));
                default:
                    return $state__6;
            }
        }
    }
}

#189
function Shared_002EBoardModule___decide($cmd__5, $state__7) {
    if ($state__7 instanceof Board_InitialState) {
        switch (get_class($cmd__5))
        {
            case 'BoardCommand_Start':
                $cmd__6 = $cmd__5->Item;
                if ($cmd__6->Players instanceof Cons) {
                    if ($cmd__6->Players->next instanceof Cons) {
                        if ($cmd__6->Players->next->next instanceof Cons) {
                            if ($cmd__6->Players->next->next->next instanceof Cons) {
                                if ($cmd__6->Players->next->next->next->next instanceof Nil) {
                                    $c1__2 = $cmd__6->Players->value[0];
                                    $c2__2 = $cmd__6->Players->next->value[0];
                                    $c3__1 = $cmd__6->Players->next->next->value[0];
                                    $c4 = $cmd__6->Players->next->next->next->value[0];
                                    $n1__2 = $cmd__6->Players->value[2];
                                    $n2__2 = $cmd__6->Players->next->value[2];
                                    $n3__1 = $cmd__6->Players->next->next->value[2];
                                    $n4 = $cmd__6->Players->next->next->next->value[2];
                                    $u1__2 = $cmd__6->Players->value[1];
                                    $u2__2 = $cmd__6->Players->next->value[1];
                                    $u3__1 = $cmd__6->Players->next->next->value[1];
                                    $u4 = $cmd__6->Players->next->next->next->value[1];
                                    $patternInput__6 = [ new Cons([ $c1__2, $u1__2, $n1__2, Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NE'])], new Cons([ $c2__2, $u2__2, $n2__2, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___NW']))], new Cons([ $c3__1, $u3__1, $n3__1, Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___SW']), $GLOBALS['Shared_002EAxeModule___S'])], new Cons([ $c4, $u4, $n4, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___SE']))], FSharpList::get_Nil())))), new Cons($GLOBALS['Shared_002EParcelModule___center'], new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NW']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___S']), $GLOBALS['Shared_002EAxeModule___SE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___NE'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___SW'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___N'])), $GLOBALS['Shared_002EAxeModule___NE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___S'])), $GLOBALS['Shared_002EAxeModule___SW']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___E2']), $GLOBALS['Shared_002EAxeModule___SE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___W2']), $GLOBALS['Shared_002EAxeModule___NW']), FSharpList::get_Nil()))))))))), $cmd__6->Goal];
                                } else {
                                    $playerCount = FSharpList::length($cmd__6->Players);
                                    if ($playerCount < 2) {
                                        throw new Exception('To few players');
                                    } else {
                                        throw new Exception('To many players');
                                    }
                                }
                            } else {
                                $c1__1 = $cmd__6->Players->value[0];
                                $c2__1 = $cmd__6->Players->next->value[0];
                                $c3 = $cmd__6->Players->next->next->value[0];
                                $n1__1 = $cmd__6->Players->value[2];
                                $n2__1 = $cmd__6->Players->next->value[2];
                                $n3 = $cmd__6->Players->next->next->value[2];
                                $u1__1 = $cmd__6->Players->value[1];
                                $u2__1 = $cmd__6->Players->next->value[1];
                                $u3 = $cmd__6->Players->next->next->value[1];
                                $patternInput__6 = [ new Cons([ $c1__1, $u1__1, $n1__1, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___N']))], new Cons([ $c2__1, $u2__1, $n2__1, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___SW']))], new Cons([ $c3, $u3, $n3, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___SE']))], FSharpList::get_Nil()))), new Cons($GLOBALS['Shared_002EParcelModule___center'], new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___N'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___S'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___NE'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___NW'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___SE'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___SW'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___W2']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___E2']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NW']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___S']), $GLOBALS['Shared_002EAxeModule___SE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___S']), $GLOBALS['Shared_002EAxeModule___SW']), FSharpList::get_Nil()))))))))))))), $cmd__6->Goal];
                            }
                        } else {
                            $c1 = $cmd__6->Players->value[0];
                            $c2 = $cmd__6->Players->next->value[0];
                            $n1 = $cmd__6->Players->value[2];
                            $n2 = $cmd__6->Players->next->value[2];
                            $u1 = $cmd__6->Players->value[1];
                            $u2 = $cmd__6->Players->next->value[1];
                            $patternInput__6 = [ new Cons([ $c1, $u1, $n1, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___N']))], new Cons([ $c2, $u2, $n2, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___S']))], FSharpList::get_Nil())), new Cons($GLOBALS['Shared_002EParcelModule___center'], new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___N'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___S'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___NE'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___NW'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___SE'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___SW'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___W2']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___E2']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NW']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___S']), $GLOBALS['Shared_002EAxeModule___SE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___S']), $GLOBALS['Shared_002EAxeModule___SW']), FSharpList::get_Nil()))))))))))))), $cmd__6->Goal];
                        }
                    } else {
                        $playerCount = FSharpList::length($cmd__6->Players);
                        if ($playerCount < 2) {
                            throw new Exception('To few players');
                        } else {
                            throw new Exception('To many players');
                        }
                    }
                } else {
                    $playerCount = FSharpList::length($cmd__6->Players);
                    if ($playerCount < 2) {
                        throw new Exception('To few players');
                    } else {
                        throw new Exception('To many players');
                    }
                }
                return new Cons(new BoardEvent_Started(new BoardStarted($patternInput__6[0], Shared_002EDrawPile___shuffle($GLOBALS['Shared_002EDrawPile___cards']), $patternInput__6[1], $patternInput__6[2])), FSharpList::get_Nil());
            default:
                return FSharpList::get_Nil();
        }
    } else {
        if ($state__7 instanceof Board_Board) {
            if ($cmd__5 instanceof BoardCommand_Play) {
                switch (get_class($cmd__5->Item2))
                {
                    case 'Command_EndTurn':
                        $playerId__2 = $cmd__5->Item1;
                        $state__8 = $state__7->Item;
                        if (Shared_002EGameTable__get_Player($state__8->Table, NULL) === $playerId__2) {
                            $player__59 = Map::FSharpMap__get_Item__2B595($state__8->Players, $playerId__2);
                            if ($player__59 instanceof CrazyPlayer_Playing) {
                                if (!Shared_002EMovesModule___canMove($player__59->Item->Moves)) {
                                    $p__67 = $player__59->Item;
                                    return Shared_002EBoardModule___next($state__8);
                                } else {
                                    return FSharpList::get_Nil();
                                }
                            } else {
                                return FSharpList::get_Nil();
                            }
                        } else {
                            return FSharpList::get_Nil();
                        }
                    default:
                        $cmd__7 = $cmd__5->Item2;
                        $playerid__11 = $cmd__5->Item1;
                        $state__9 = $state__7->Item;
                        $player__60 = Map::FSharpMap__get_Item__2B595($state__9->Players, $playerid__11);
                        $others = Shared_002EBoardModule___otherPlayers($playerid__11, $state__9);
                        if ($playerid__11 === Shared_002EGameTable__get_Player($state__9->Table, NULL)) {
                            $events = Shared_002EPlayer___decide($others, $state__9->Barns, $cmd__7, $player__60);
                            return FSharpList::ofSeq(Seq::delay(function ($unitVar__34) use ($events) {                             return Seq::append(Seq::map(function ($e__18) use ($playerid__11) {                             return new BoardEvent_Played($playerid__11, $e__18);
 }, $events), Seq::delay(function ($unitVar__35) use ($events) {                             return Seq::append(Seq::collect(function ($e__19) {                             if ($e__19 instanceof Event_CardPlayed) {
                                switch (get_class($e__19->Item))
                                {
                                    case 'PlayCard_PlayRut':
                                        $victim__1 = $e__19->Item->victim;
                                        return Seq::singleton(new BoardEvent_Played($victim__1, new Event_Rutted()));
                                    case 'PlayCard_PlayHayBale':
                                        $bales = $e__19->Item->path;
                                        return Seq::singleton(new BoardEvent_HayBalesPlaced($bales));
                                    case 'PlayCard_PlayDynamite':
                                        $bale = $e__19->Item->path;
                                        return Seq::singleton(new BoardEvent_HayBaleDynamited($bale));
                                    default:
                                        return Seq::empty();
                                }
                            } else {
                                return Seq::empty();
                            }
 }, $events), Seq::delay(function ($unitVar__36) use ($events, $player__60) { 
                                $nextState = FSharpList::fold('Shared_002EPlayer___evolve', $player__60, $events);
                                $matchValue__34 = FSharpList::tryFind(function ($_arg1__52) {                                 if ($_arg1__52 instanceof Event_Annexed) {
                                    return true;
                                } else {
                                    return false;
                                }
 }, $events);
                                if (!is_null($matchValue__34)) {
                                    switch (get_class($matchValue__34))
                                    {
                                        case 'Event_Annexed':
                                            $e__20 = $matchValue__34->Item;
                                            $board__19 = new PlayingBoard(Map::add($playerid__11, $nextState, $state__9->Players), $state__9->Table, $state__9->DrawPile, $state__9->DiscardPile, $state__9->Barns, $state__9->HayBales, $state__9->Goal);
                                            $nextBoard = Shared_002EBoardModule___annexed($playerid__11, $e__20, $board__19);
                                            $eliminated = 0;
                                            return Seq::append(Seq::collect(function ($matchValue__35) use ($eliminated) { 
                                                $activePatternResult41611 = $matchValue__35;
                                                if (Shared_002EPlayer___isKo($activePatternResult41611[1])) {
                                                    $eliminated = $eliminated + 1;
                                                    return Seq::empty();
                                                } else {
                                                    if (Shared_002EFieldModule___isEmpty(Shared_002EPlayer___field($activePatternResult41611[1]))) {
                                                        $eliminated = $eliminated + 1;
                                                        return Seq::singleton(new BoardEvent_Played($activePatternResult41611[0], new Event_Eliminated()));
                                                    } else {
                                                        return Seq::empty();
                                                    }
                                                }
                                            }, $nextBoard->Players), Seq::delay(function ($unitVar__37) use ($e__20, $eliminated, $nextBoard, $playerid__11, $state__9) {                                             if ($eliminated >= (Map::count($nextBoard->Players) - 1)) {
                                                return Seq::singleton(new BoardEvent_GameWon($playerid__11));
                                            } else {
                                                $matchValue__36 = Shared_002EBoardModule___tryFindWinner($nextBoard);
                                                if (!is_null($matchValue__36)) {
                                                    $winner = $matchValue__36[0];
                                                    return Seq::singleton(new BoardEvent_GameWon($winner));
                                                } else {
                                                    $cardsToTake = FSharpList::length($e__20->FreeBarns) + 2 * FSharpList::length($e__20->OccupiedBarns);
                                                    if ($cardsToTake > 0) {
                                                        if ($cardsToTake > FSharpList::length($state__9->DrawPile)) {
                                                            $shuffledDiscardPile = Shared_002EDrawPile___shuffle($state__9->DiscardPile);
                                                            $patternInput__7 = [ FSharpList::append($state__9->DrawPile, $shuffledDiscardPile), new Cons(new BoardEvent_DiscardPileShuffled($shuffledDiscardPile), FSharpList::get_Nil())];
                                                        } else {
                                                            $patternInput__7 = [ $state__9->DrawPile, FSharpList::get_Nil()];
                                                        }
                                                        return Seq::append($patternInput__7[1], Seq::delay(function ($unitVar__38) use ($cardsToTake, $patternInput__7, $playerid__11) {                                                         return Seq::singleton(new BoardEvent_PlayerDrewCards(new PlayerDrewCards($playerid__11, new Hand_PublicHand(Shared_002EDrawPile___take($cardsToTake, $patternInput__7[0])))));
 }));
                                                    } else {
                                                        if ($nextState instanceof CrazyPlayer_Playing) {
                                                            if (!Shared_002EMovesModule___canMove($nextState->Item->Moves) ? true : Shared_002EHandModule___canPlay($nextState->Item->Hand)) {
                                                                $p__70 = $nextState->Item;
                                                                return Shared_002EBoardModule___next($state__9);
                                                            } else {
                                                                return Seq::empty();
                                                            }
                                                        } else {
                                                            return Seq::empty();
                                                        }
                                                    }
                                                }
                                            }
 }));
                                        default:
                                            if ($nextState instanceof CrazyPlayer_Playing) {
                                                if (!Shared_002EMovesModule___canMove($nextState->Item->Moves) ? true : Shared_002EHandModule___canPlay($nextState->Item->Hand)) {
                                                    $p__72 = $nextState->Item;
                                                    return Shared_002EBoardModule___next($state__9);
                                                } else {
                                                    return Seq::empty();
                                                }
                                            } else {
                                                return Seq::empty();
                                            }
                                    }
                                } else {
                                    if ($nextState instanceof CrazyPlayer_Playing) {
                                        if (!Shared_002EMovesModule___canMove($nextState->Item->Moves) ? true : Shared_002EHandModule___canPlay($nextState->Item->Hand)) {
                                            $p__72 = $nextState->Item;
                                            return Shared_002EBoardModule___next($state__9);
                                        } else {
                                            return Seq::empty();
                                        }
                                    } else {
                                        return Seq::empty();
                                    }
                                }
                            }));
 }));
 }));
                        } else {
                            return FSharpList::get_Nil();
                        }
                }
            } else {
                return FSharpList::get_Nil();
            }
        } else {
            return FSharpList::get_Nil();
        }
    }
}

#190
function Shared_002EBoardModule___toState($board__20) {
    switch (get_class($board__20))
    {
        case 'Board_InitialState':
            return new BoardState([ ], new STable(NULL, NULL, NULL, 0), [ ], NULL, NULL, NULL, new Goal_Common(0), NULL);
        case 'Board_Won':
            return new BoardState((function () use ($Array, $board__20) { 
                $source__9 = Map::toSeq($board__20->Item2->Players);
                $source__10 = Seq::map(function ($tupledArg__11) {                 return [ $tupledArg__11[0], Shared_002EPlayer___toState($tupledArg__11[1])];
 }, $source__9);
                return FSharpArray::ofSeq($source__10, $Array);
            })(), new STable($board__20->Item2->Table->Players, $board__20->Item2->Table->AllPlayers, FSharpArray::ofSeq(Seq::delay(function ($unitVar__40) use ($board__20) {             return Seq::collect(function ($matchValue__38) { 
                $activePatternResult41625 = $matchValue__38;
                return Seq::singleton([ $activePatternResult41625[0], $activePatternResult41625[1]]);
            }, $board__20->Item2->Table->Names);
 }), $Array), $board__20->Item2->Table->Current), FSharpArray::ofList($board__20->Item2->DiscardPile, $Array), (function () use ($Array, $board__20) { 
                $list__24 = Shared_002EFieldModule___parcels($board__20->Item2->Barns->Free);
                return FSharpArray::ofList($list__24, $Array);
            })(), (function () use ($Array, $board__20) { 
                $list__25 = Shared_002EFieldModule___parcels($board__20->Item2->Barns->Occupied);
                return FSharpArray::ofList($list__25, $Array);
            })(), Set::toArray($board__20->Item2->HayBales, $Array), $board__20->Item2->Goal, $board__20->Item1);
        default:
            return new BoardState((function () use ($Array, $board__20) { 
                $source__7 = Map::toSeq($board__20->Item->Players);
                $source__8 = Seq::map(function ($tupledArg__10) {                 return [ $tupledArg__10[0], Shared_002EPlayer___toState($tupledArg__10[1])];
 }, $source__7);
                return FSharpArray::ofSeq($source__8, $Array);
            })(), new STable($board__20->Item->Table->Players, $board__20->Item->Table->AllPlayers, FSharpArray::ofSeq(Seq::delay(function ($unitVar__39) use ($board__20) {             return Seq::collect(function ($matchValue__37) { 
                $activePatternResult41621 = $matchValue__37;
                return Seq::singleton([ $activePatternResult41621[0], $activePatternResult41621[1]]);
            }, $board__20->Item->Table->Names);
 }), $Array), $board__20->Item->Table->Current), FSharpArray::ofList($board__20->Item->DiscardPile, $Array), (function () use ($Array, $board__20) { 
                $list__22 = Shared_002EFieldModule___parcels($board__20->Item->Barns->Free);
                return FSharpArray::ofList($list__22, $Array);
            })(), (function () use ($Array, $board__20) { 
                $list__23 = Shared_002EFieldModule___parcels($board__20->Item->Barns->Occupied);
                return FSharpArray::ofList($list__23, $Array);
            })(), Set::toArray($board__20->Item->HayBales, $Array), $board__20->Item->Goal, NULL);
    }
}

#191
function Shared_002EBoardModule___ofState($board__23) {
    if (!FSharpArray::equalsWith('Util::compareArrays', $board__23->SPlayers, NULL) ? $board__23->SPlayers['length'] === 0 : false) {
        return new Board_InitialState();
    } else {
        $state__10 = new PlayingBoard((function () use ($board__23) { 
            $elements__4 = Seq::map(function ($tupledArg__12) {             return [ $tupledArg__12[0], Shared_002EPlayer___ofState($tupledArg__12[1])];
 }, $board__23->SPlayers);
            return Map::ofSeq($elements__4, [ 'Compare' => 'Util::comparePrimitives']);
        })(), new GameTable($board__23->STable->SPlayers, $board__23->STable->SAllPlayers, Map::ofArray($board__23->STable->SNames, [ 'Compare' => 'Util::comparePrimitives']), $board__23->STable->SCurrent), FSharpList::get_Nil(), FSharpArray::toList($board__23->SDiscardPile), new Barns(Shared_002EFieldModule___ofParcels($board__23->SFreeBarns), Shared_002EFieldModule___ofParcels($board__23->SOccupiedBarns)), Set::ofSeq($board__23->SHayBales, [ 'Compare' => function ($_x__64, $_y__65) {         return $_x__64->CompareTo($_y__65);
 }]), $board__23->SGoal);
        if ($board__23->SWinner === NULL) {
            return new Board_Board($state__10);
        } else {
            return new Board_Won($board__23->SWinner, $state__10);
        }
    }
}

#192
function Shared_002EBoardModule___possibleMoves($playerid__14, $board__24) {
    if ($board__24 instanceof Board_Board) {
        if (!is_null($playerid__14)) {
            $board__25 = $board__24->Item;
            $playerid__15 = $playerid__14;
            $matchValue__42 = Map::tryFind($playerid__15, $board__25->Players);
            if (is_null($matchValue__42)) {
                return FSharpList::get_Nil();
            } else {
                switch (get_class($matchValue__42))
                {
                    case 'CrazyPlayer_Starting':
                        $p__80 = $matchValue__42->Item->Parcel->tile;
                        return new Cons(new Move_SelectCrossroad(new Crossroad($p__80, new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad($p__80, new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__80, $GLOBALS['Shared_002EAxeModule___NW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__80, $GLOBALS['Shared_002EAxeModule___NE']), new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__80, $GLOBALS['Shared_002EAxeModule___SW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__80, $GLOBALS['Shared_002EAxeModule___SE']), new CrossroadSide_CLeft())), FSharpList::get_Nil()))))));
                    case 'CrazyPlayer_Ko':
                        return FSharpList::get_Nil();
                    default:
                        $p__78 = $matchValue__42->Item;
                        $player__63 = $matchValue__42;
                        $list__26 = Shared_002EBoardModule___otherPlayers($playerid__15, $board__25);
                        $otherPlayers__5 = FSharpList::map(function ($tuple__2) {                         return $tuple__2[1];
 }, $list__26);
                        $moverbonus__1 = Shared_002EPlayer___bonus($player__63);
                        $check = function ($player__64) {                         return function ($c__20) use ($moverbonus__1, $player__64) {                         return Shared_002EPlayer___checkMove($moverbonus__1, $player__64, $c__20);
 };
 };
                        return FSharpList::ofSeq(Seq::delay(function ($unitVar__41) use ($player__63) {                         return Seq::collect(function ($matchValue__43) use ($board__25, $otherPlayers__5, $p__78) { 
                            $path__4 = Shared_002EPathModule___neighbor($matchValue__43[0], $p__78->Tractor);
                            if (Set::contains($path__4, $board__25->HayBales)) {
                                $c__21 = Shared_002ECrossroadModule___neighbor($matchValue__43[0], $p__78->Tractor);
                                return Seq::singleton(new Move_ImpossibleMove($matchValue__43[0], $c__21, new MoveBlocker_HayBaleOnPath()));
                            } else {
                                $matchValue__44 = Seq::fold(function ($c__22, $p__79) use ($check) {                                 return Shared_002EPlayer___bindMove($check($p__79), $c__22);
 }, $matchValue__43[1], $otherPlayers__5);
                                switch (get_class($matchValue__44))
                                {
                                    case 'Error':
                                        return Seq::singleton(new Move_ImpossibleMove($matchValue__43[0], $matchValue__44->ErrorValue[0], $matchValue__44->ErrorValue[1]));
                                    default:
                                        return Seq::singleton(new Move_Move($matchValue__43[0], $matchValue__44->ResultValue));
                                }
                            }
                        }, Shared_002EPlayer___possibleMoves($player__63));
 }));
                }
            }
        } else {
            return FSharpList::get_Nil();
        }
    } else {
        return FSharpList::get_Nil();
    }
}

#193
abstract class ServerMsg {
}

#193
class ServerMsg_JoinGame extends ServerMsg {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#193
class ServerMsg_Command extends ServerMsg {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#194
abstract class ClientMsg {
}

#194
class ClientMsg_Events extends ClientMsg {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
}

#194
class ClientMsg_Message extends ClientMsg {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

#194
class ClientMsg_Sync extends ClientMsg {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
}

#194
class ClientMsg_SyncPlayer extends ClientMsg {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

