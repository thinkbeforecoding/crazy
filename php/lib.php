<?php
class Axe {
    public $q;
    public $r;
    function __construct($q, $r) {
        $this->q = $q;
        $this->r = $r;
    }
}

function Shared_002EAxe___op_Addition__2BE35040($_arg1, $_arg2) {
    return new Axe($_arg1->q + $_arg2->q, $_arg1->r + $_arg2->r);
}

function Shared_002EAxe___op_Multiply__Z425F7B5E($a, $_arg3) {
    return new Axe($_arg3->q * $a, $_arg3->r * $a);
}

function Shared_002EAxe__get_Q($this_, $unitVar1) {
    return $this_->q;
}

function Shared_002EAxe__get_R($this___1, $unitVar1__1) {
    return $this___1->r;
}

$Shared_002EAxeModule___N = new Axe(0, -1);

$Shared_002EAxeModule___S = new Axe(0, 1);

$Shared_002EAxeModule___NW = new Axe(-1, 0);

$Shared_002EAxeModule___NE = new Axe(1, -1);

$Shared_002EAxeModule___SW = new Axe(-1, 1);

$Shared_002EAxeModule___SE = new Axe(1, 0);

$Shared_002EAxeModule___W2 = Shared_002EAxe___op_Addition__2BE35040($GLOBALS['Shared_002EAxeModule___NW'], $GLOBALS['Shared_002EAxeModule___SW']);

$Shared_002EAxeModule___E2 = Shared_002EAxe___op_Addition__2BE35040($GLOBALS['Shared_002EAxeModule___NE'], $GLOBALS['Shared_002EAxeModule___SE']);

$Shared_002EAxeModule___center = new Axe(0, 0);

function Shared_002EAxeModule___cube($_arg1__1) {
    return [ $_arg1__1->q, $_arg1__1->r, -$_arg1__1->q - $_arg1__1->r ];
}

abstract class CrossroadSide {
}

class CrossroadSide_CLeft extends CrossroadSide {
    function __construct() {
    }
}

class CrossroadSide_CRight extends CrossroadSide {
    function __construct() {
    }
}

class Crossroad {
    public $tile;
    public $side;
    function __construct($tile, $side) {
        $this->tile = $tile;
        $this->side = $side;
    }
}

abstract class BorderSide {
}

class BorderSide_BNW extends BorderSide {
    function __construct() {
    }
}

class BorderSide_BN extends BorderSide {
    function __construct() {
    }
}

class BorderSide_BNE extends BorderSide {
    function __construct() {
    }
}

class Path {
    public $tile;
    public $border;
    function __construct($tile, $border) {
        $this->tile = $tile;
        $this->border = $border;
    }
}

abstract class Direction {
}

class Direction_Up extends Direction {
    function __construct() {
    }
}

class Direction_Down extends Direction {
    function __construct() {
    }
}

class Direction_Horizontal extends Direction {
    function __construct() {
    }
}

class Parcel {
    public $tile;
    function __construct($tile) {
        $this->tile = $tile;
    }
}

function Shared_002EParcel___op_Addition__ZF6EFE4B($_arg1__2, $v) {
    return new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__2->tile, $v));
}

class Field {
    public $parcels;
    function __construct($parcels) {
        $this->parcels = $parcels;
    }
}

function Shared_002EField___op_Addition__Z24735800($_arg1__3, $_arg2__1) {
    return new Field(Set::FSharpSet___op_Addition($_arg1__3->parcels, $_arg2__1->parcels));
}

function Shared_002EField___op_Subtraction__Z24735800($_arg3__1, $_arg4) {
    return new Field(Set::FSharpSet___op_Subtraction($_arg3__1->parcels, $_arg4->parcels));
}

class Fence {
    public $paths;
    function __construct($paths) {
        $this->paths = $paths;
    }
}

class Barns {
    public $Free;
    public $Occupied;
    function __construct($Free, $Occupied) {
        $this->Free = $Free;
        $this->Occupied = $Occupied;
    }
}

function Shared_002EDirectionModule___rev($_arg1__4) {
    switch (get_class($_arg1__4))
    {
        case 'Down':
            return new Direction_Up();
        case 'Horizontal':
            return new Direction_Horizontal();
        default:
            return new Direction_Down();
    }
}

abstract class Power {
}

class Power_PowerUp extends Power {
    function __construct() {
    }
}

class Power_PowerDown extends Power {
    function __construct() {
    }
}

abstract class Card {
}

class Card_Nitro extends Card {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
}

class Card_Rut extends Card {
    function __construct() {
    }
}

class Card_HayBale extends Card {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
}

class Card_Dynamite extends Card {
    function __construct() {
    }
}

class Card_HighVoltage extends Card {
    function __construct() {
    }
}

class Card_Watchdog extends Card {
    function __construct() {
    }
}

class Card_Helicopter extends Card {
    function __construct() {
    }
}

class Card_Bribe extends Card {
    function __construct() {
    }
}

abstract class CardPower {
}

class CardPower_One extends CardPower {
    function __construct() {
    }
}

class CardPower_Two extends CardPower {
    function __construct() {
    }
}

abstract class PlayCard {
}

class PlayCard_PlayNitro extends PlayCard {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
}

class PlayCard_PlayRut extends PlayCard {
    public $victim;
    function __construct($victim) {
        $this->victim = $victim;
    }
}

class PlayCard_PlayHayBale extends PlayCard {
    public $path;
    function __construct($path) {
        $this->path = $path;
    }
}

class PlayCard_PlayDynamite extends PlayCard {
    public $path;
    function __construct($path) {
        $this->path = $path;
    }
}

class PlayCard_PlayHighVoltage extends PlayCard {
    function __construct() {
    }
}

class PlayCard_PlayWatchdog extends PlayCard {
    function __construct() {
    }
}

class PlayCard_PlayHelicopter extends PlayCard {
    public $destination;
    function __construct($destination) {
        $this->destination = $destination;
    }
}

class PlayCard_PlayBribe extends PlayCard {
    public $parcel;
    function __construct($parcel) {
        $this->parcel = $parcel;
    }
}

function Shared_002ECardModule___ofPlayCard($_arg1__5) {
    switch (get_class($_arg1__5))
    {
        case 'PlayRut':
            return new Card_Rut();
        case 'PlayHayBale':
            return FSharpList::length($_arg1__5->path) < 2 ? new Card_HayBale(new CardPower_One()) : new Card_HayBale(new CardPower_Two());
        case 'PlayDynamite':
            return new Card_Dynamite();
        case 'PlayHighVoltage':
            return new Card_HighVoltage();
        case 'PlayWatchdog':
            return new Card_Watchdog();
        case 'PlayHelicopter':
            return new Card_Helicopter();
        case 'PlayBribe':
            return new Card_Bribe();
        default:
            return new Card_Nitro($_arg1__5->power);
    }
}

abstract class Hand {
}

class Hand_PrivateHand extends Hand {
    public $cards;
    function __construct($cards) {
        $this->cards = $cards;
    }
}

class Hand_PublicHand extends Hand {
    public $cards;
    function __construct($cards) {
        $this->cards = $cards;
    }
}

$Shared_002EHandModule___empty = new Hand_PrivateHand(0);

function Shared_002EHandModule___isEmpty($_arg1__6) {
    switch (get_class($_arg1__6))
    {
        case 'PrivateHand':
            return $_arg1__6->cards === 0;
        default:
            return $_arg1__6->cards instanceof Nil;
    }
}

function Shared_002EHandModule___toPrivate($_arg1__7) {
    if ($_arg1__7 instanceof Hand_PublicHand) {
        return new Hand_PrivateHand(FSharpList::length($_arg1__7->cards));
    } else {
        return $_arg1__7;
    }
}

function Shared_002EHandModule___count($_arg1__8) {
    switch (get_class($_arg1__8))
    {
        case 'PrivateHand':
            return $_arg1__8->cards;
        default:
            return FSharpList::length($_arg1__8->cards);
    }
}

function Shared_002EHandModule___contains($card, $_arg1__9) {
    switch (get_class($_arg1__9))
    {
        case 'PrivateHand':
            return false;
        default:
            return FSharpList::contains($card, $_arg1__9->cards, [ 'Equals' => $GLOBALS['equals'], 'GetHashCode' => $GLOBALS['structuralHash']]);
    }
}

function Shared_002EHandModule___remove($card__1, $hand) {
    switch (get_class($hand))
    {
        case 'PrivateHand':
            return new Hand_PrivateHand($hand->cards - 1);
        default:
            $matchValue__2 = FSharpList::tryFindIndex(function ($c__1) use ($card__1) { return Util::equals($c__1, $card__1); }, $hand->cards);
            if (is_null($matchValue__2)) {
                return $hand;
            } else {
                $i = $matchValue__2;
                $patternInput = FSharpList::splitAt($i, $hand->cards);
                return new Hand_PublicHand(FSharpList::append($patternInput[0], FSharpList::tail($patternInput[1])));
            }
    }
}

function Shared_002EHandModule___canPlay($_arg1__10) {
    switch (get_class($_arg1__10))
    {
        case 'PrivateHand':
            return $_arg1__10->cards > 0;
        default:
            $value = $_arg1__10->cards instanceof Nil;
            return !$value;
    }
}

abstract class Player {
}

class Player_Starting extends Player {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

class Player_Playing extends Player {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

class Player_Ko extends Player {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

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

class Table {
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

function Shared_002ETable__get_Player($this___2, $unitVar1__2) {
    return $this___2->Players[$this___2->Current];
}

function Shared_002ETable__get_Next($this___3, $unitVar1__3) {
    $Current = ($this___3->Current + 1) % $this___3->Players[length];
    return new Table($this___3->Players, $this___3->AllPlayers, $this___3->Names, $Current);
}

function Shared_002ETableModule___start($players) {
    $allplayers = Array::ofSeq(Seq::delay(function ($unitVar) use ($players) { return Seq::collect(function ($matchValue__3) { return Seq::singleton($matchValue__3[0]); }, $players); }), $Array);
    return new Table($allplayers, $allplayers, Map::ofList($players, [ 'Compare' => $GLOBALS['comparePrimitives']]), 0);
}

function Shared_002ETableModule___eliminate($player, $table) {
    return new Table($table->Players->filter(function ($p__11) use ($player) { return $p__11 !== $player; }), $table->AllPlayers, $table->Names, $table->Current);
}

function Shared_002ETableModule___isCurrent($playerid, $table__1) {
    return Shared_002ETable__get_Player() === $playerid;
}

$Shared_002EBonusModule___empty = new Bonus(0, 0, false, false, 0, 0);

function Shared_002EBonusModule___startTurn($bonus) {
    return FSharpList::ofSeq(Seq::delay(function ($unitVar__1) use ($bonus) { return Seq::append($bonus->HighVoltage ? Seq::singleton(new Card_HighVoltage()) : Seq::empty(), Seq::delay(function ($unitVar__2) use ($bonus) { return $bonus->Watched ? Seq::singleton(new Card_Watchdog()) : Seq::empty(); })); }));
}

function Shared_002EBonusModule___endTurn($bonus__1) {
    return FSharpList::ofSeq(Seq::delay(function ($unitVar__3) use ($bonus__1) { return Seq::append(Seq::collect(function ($matchValue__4) { return Seq::singleton(new Card_Nitro(new CardPower_One())); }, Seq::rangeNumber(1, 1, $bonus__1->NitroOne)), Seq::delay(function ($unitVar__4) use ($bonus__1) { return Seq::append(Seq::collect(function ($matchValue__5) { return Seq::singleton(new Card_Nitro(new CardPower_Two())); }, Seq::rangeNumber(1, 1, $bonus__1->NitroTwo)), Seq::delay(function ($unitVar__5) use ($bonus__1) { return Seq::append(Seq::collect(function ($matchValue__6) { return Seq::singleton(new Card_Rut()); }, Seq::rangeNumber(1, 1, $bonus__1->Rutted)), Seq::delay(function ($unitVar__6) use ($bonus__1) { return Seq::collect(function ($matchValue__7) { return Seq::singleton(new Card_Helicopter()); }, Seq::rangeNumber(1, 1, $bonus__1->Heliported)); })); })); })); }));
}

function Shared_002EBonusModule___moveCapacityChange($bonus__2) {
    return $bonus__2->Rutted * -2;
}

function Shared_002EBonusModule___discard($card__2, $bonus__3) {
    if ($card__2 instanceof Card_Nitro) {
        switch (get_class($card__2->power))
        {
            case 'Two':
                $NitroTwo = $bonus__3->NitroTwo - 1;
                return new Bonus($bonus__3->NitroOne, $NitroTwo, $bonus__3->Watched, $bonus__3->HighVoltage, $bonus__3->Rutted, $bonus__3->Heliported);
            default:
                return new Bonus($bonus__3->NitroOne - 1, $bonus__3->NitroTwo, $bonus__3->Watched, $bonus__3->HighVoltage, $bonus__3->Rutted, $bonus__3->Heliported);
        }
    } else {
        switch (get_class($card__2))
        {
            case 'Watchdog':
                return new Bonus($bonus__3->NitroOne, $bonus__3->NitroTwo, false, $bonus__3->HighVoltage, $bonus__3->Rutted, $bonus__3->Heliported);
            case 'HighVoltage':
                return new Bonus($bonus__3->NitroOne, $bonus__3->NitroTwo, $bonus__3->Watched, false, $bonus__3->Rutted, $bonus__3->Heliported);
            case 'Rut':
                $Rutted = $bonus__3->Rutted - 1;
                return new Bonus($bonus__3->NitroOne, $bonus__3->NitroTwo, $bonus__3->Watched, $bonus__3->HighVoltage, $Rutted, $bonus__3->Heliported);
            case 'Helicopter':
                $Heliported = $bonus__3->Heliported - 1;
                return new Bonus($bonus__3->NitroOne, $bonus__3->NitroTwo, $bonus__3->Watched, $bonus__3->HighVoltage, $bonus__3->Rutted, $Heliported);
            default:
                return $bonus__3;
        }
    }
}

abstract class Board {
}

class Board_InitialState extends Board {
    function __construct() {
    }
}

class Board_Board extends Board {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

class Board_Won extends Board {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
}

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

abstract class PlayerState {
}

class PlayerState_SStarting extends PlayerState {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

class PlayerState_SPlaying extends PlayerState {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

class PlayerState_SKo extends PlayerState {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
}

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

function Shared_002ECrossroadModule___neighbor($dir, $_arg1__11) {
    $tupledArg = $_arg1__11->side instanceof CrossroadSide_CRight ? $dir instanceof Direction_Down ? [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___SE']), new CrossroadSide_CLeft() ] : $dir instanceof Direction_Horizontal ? [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___E2']), new CrossroadSide_CLeft() ] : [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___NE']), new CrossroadSide_CLeft() ] : $dir instanceof Direction_Down ? [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___SW']), new CrossroadSide_CRight() ] : $dir instanceof Direction_Horizontal ? [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___W2']), new CrossroadSide_CRight() ] : [ Shared_002EAxe___op_Addition__2BE35040($_arg1__11->tile, $GLOBALS['Shared_002EAxeModule___NW']), new CrossroadSide_CRight() ];
    return new Crossroad($tupledArg[0], $tupledArg[1]);
}

