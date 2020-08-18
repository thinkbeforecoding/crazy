<?php
#0
abstract class Color implements Union, FSharpUnion {
}

#0
class Color_Blue extends Color {
    function __construct() {
    }
    function get_Case() {
        return 'Color_Blue';
    }
    function get_FSharpCase() {
        return 'Blue';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__1 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__1;
    }
}

#0
class Color_Yellow extends Color {
    function __construct() {
    }
    function get_Case() {
        return 'Color_Yellow';
    }
    function get_FSharpCase() {
        return 'Yellow';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__2 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__2;
    }
}

#0
class Color_Purple extends Color {
    function __construct() {
    }
    function get_Case() {
        return 'Color_Purple';
    }
    function get_FSharpCase() {
        return 'Purple';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__3 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__3;
    }
}

#0
class Color_Red extends Color {
    function __construct() {
    }
    function get_Case() {
        return 'Color_Red';
    }
    function get_FSharpCase() {
        return 'Red';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__4 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__4;
    }
}

#1
abstract class Goal implements Union, FSharpUnion {
}

#1
class Goal_Common extends Goal {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Goal_Common';
    }
    function get_FSharpCase() {
        return 'Common';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__5 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__5 != 0) {
            return $_cmp__5;
        }        
        $_cmp__6 = $this->Item > $other->Item ? 1 : ($this->Item < $other->Item ? -1 : 0);
        if ($_cmp__6 != 0) {
            return $_cmp__6;
        }        
        return 0;
    }
}

#1
class Goal_Individual extends Goal {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Goal_Individual';
    }
    function get_FSharpCase() {
        return 'Individual';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__7 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__7 != 0) {
            return $_cmp__7;
        }        
        $_cmp__8 = $this->Item > $other->Item ? 1 : ($this->Item < $other->Item ? -1 : 0);
        if ($_cmp__8 != 0) {
            return $_cmp__8;
        }        
        return 0;
    }
}

#2
abstract class GoalType implements Union, FSharpUnion {
}

#2
class GoalType_Fast extends GoalType {
    function __construct() {
    }
    function get_Case() {
        return 'GoalType_Fast';
    }
    function get_FSharpCase() {
        return 'Fast';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__9 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__9;
    }
}

#2
class GoalType_Regular extends GoalType {
    function __construct() {
    }
    function get_Case() {
        return 'GoalType_Regular';
    }
    function get_FSharpCase() {
        return 'Regular';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__10 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__10;
    }
}

#2
class GoalType_Expert extends GoalType {
    function __construct() {
    }
    function get_Case() {
        return 'GoalType_Expert';
    }
    function get_FSharpCase() {
        return 'Expert';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__11 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__11;
    }
}

#3
abstract class UndoType implements Union, FSharpUnion {
}

#3
class UndoType_FullUndo extends UndoType {
    function __construct() {
    }
    function get_Case() {
        return 'UndoType_FullUndo';
    }
    function get_FSharpCase() {
        return 'FullUndo';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__12 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__12;
    }
}

#3
class UndoType_DontUndoCards extends UndoType {
    function __construct() {
    }
    function get_Case() {
        return 'UndoType_DontUndoCards';
    }
    function get_FSharpCase() {
        return 'DontUndoCards';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__13 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__13;
    }
}

#3
class UndoType_NoUndo extends UndoType {
    function __construct() {
    }
    function get_Case() {
        return 'UndoType_NoUndo';
    }
    function get_FSharpCase() {
        return 'NoUndo';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__14 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__14;
    }
}

#4
function Shared_002EGoalModule___fromType($playerCount, $goal) {
    if ($playerCount === 2) {
        switch (get_class($goal))
        {
            case 'GoalType_Regular':
                $arg0__3 = new Goal_Common(27);
                return $arg0__3;
            case 'GoalType_Expert':
                $arg0__6 = new Goal_Common(31);
                return $arg0__6;
            default:
                $arg0 = new Goal_Common(23);
                return $arg0;
        }
    }     else {
        if ($playerCount === 3) {
            switch (get_class($goal))
            {
                case 'GoalType_Regular':
                    $arg0__4 = new Goal_Individual(11);
                    return $arg0__4;
                case 'GoalType_Expert':
                    $arg0__7 = new Goal_Individual(13);
                    return $arg0__7;
                default:
                    $arg0__1 = new Goal_Individual(9);
                    return $arg0__1;
            }
        }         else {
            if ($playerCount === 4) {
                switch (get_class($goal))
                {
                    case 'GoalType_Regular':
                        $arg0__5 = new Goal_Individual(9);
                        return $arg0__5;
                    case 'GoalType_Expert':
                        $arg0__8 = new Goal_Individual(11);
                        return $arg0__8;
                    default:
                        $arg0__2 = new Goal_Individual(8);
                        return $arg0__2;
                }
            }             else {
                return NULL;
            }
        }
    }
}

#0
class Axe implements FSharpUnion {
    public $q;
    public $r;
    function __construct($q, $r) {
        $this->q = $q;
        $this->r = $r;
    }
    function get_FSharpCase() {
        return 'Axe';
    }
    function CompareTo($other) {
        $_cmp__15 = $this->q > $other->q ? 1 : ($this->q < $other->q ? -1 : 0);
        if ($_cmp__15 != 0) {
            return $_cmp__15;
        }        
        $_cmp__16 = $this->r > $other->r ? 1 : ($this->r < $other->r ? -1 : 0);
        if ($_cmp__16 != 0) {
            return $_cmp__16;
        }        
        return 0;
    }
}

#1
function Shared_002EAxe___op_Addition__2BE35040($_arg1, $_arg2) {
    return new Axe(($_arg1->q + $_arg2->q), ($_arg1->r + $_arg2->r));
}

#2
function Shared_002EAxe___op_Multiply__Z425F7B5E($a, $_arg3) {
    return new Axe(($_arg3->q * $a), ($_arg3->r * $a));
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
abstract class CrossroadSide implements Union, FSharpUnion {
}

#15
class CrossroadSide_CLeft extends CrossroadSide {
    function __construct() {
    }
    function get_Case() {
        return 'CrossroadSide_CLeft';
    }
    function get_FSharpCase() {
        return 'CLeft';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__17 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__17;
    }
}

#15
class CrossroadSide_CRight extends CrossroadSide {
    function __construct() {
    }
    function get_Case() {
        return 'CrossroadSide_CRight';
    }
    function get_FSharpCase() {
        return 'CRight';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__18 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__18;
    }
}

#16
class Crossroad implements FSharpUnion {
    public $tile;
    public $side;
    function __construct($tile, $side) {
        $this->tile = $tile;
        $this->side = $side;
    }
    function get_FSharpCase() {
        return 'Crossroad';
    }
    function CompareTo($other) {
        $_cmp__19 = $this->tile->CompareTo($other->tile);
        if ($_cmp__19 != 0) {
            return $_cmp__19;
        }        
        $_cmp__20 = $this->side->CompareTo($other->side);
        if ($_cmp__20 != 0) {
            return $_cmp__20;
        }        
        return 0;
    }
}

#17
abstract class BorderSide implements Union, FSharpUnion {
}

#17
class BorderSide_BNW extends BorderSide {
    function __construct() {
    }
    function get_Case() {
        return 'BorderSide_BNW';
    }
    function get_FSharpCase() {
        return 'BNW';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__21 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__21;
    }
}

#17
class BorderSide_BN extends BorderSide {
    function __construct() {
    }
    function get_Case() {
        return 'BorderSide_BN';
    }
    function get_FSharpCase() {
        return 'BN';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__22 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__22;
    }
}

#17
class BorderSide_BNE extends BorderSide {
    function __construct() {
    }
    function get_Case() {
        return 'BorderSide_BNE';
    }
    function get_FSharpCase() {
        return 'BNE';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__23 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__23;
    }
}

#18
class Path implements FSharpUnion {
    public $tile;
    public $border;
    function __construct($tile, $border) {
        $this->tile = $tile;
        $this->border = $border;
    }
    function get_FSharpCase() {
        return 'Path';
    }
    function CompareTo($other) {
        $_cmp__24 = $this->tile->CompareTo($other->tile);
        if ($_cmp__24 != 0) {
            return $_cmp__24;
        }        
        $_cmp__25 = $this->border->CompareTo($other->border);
        if ($_cmp__25 != 0) {
            return $_cmp__25;
        }        
        return 0;
    }
}

#19
abstract class Direction implements Union, FSharpUnion {
}

#19
class Direction_Up extends Direction {
    function __construct() {
    }
    function get_Case() {
        return 'Direction_Up';
    }
    function get_FSharpCase() {
        return 'Up';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__26 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__26;
    }
}

#19
class Direction_Down extends Direction {
    function __construct() {
    }
    function get_Case() {
        return 'Direction_Down';
    }
    function get_FSharpCase() {
        return 'Down';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__27 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__27;
    }
}

#19
class Direction_Horizontal extends Direction {
    function __construct() {
    }
    function get_Case() {
        return 'Direction_Horizontal';
    }
    function get_FSharpCase() {
        return 'Horizontal';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__28 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__28;
    }
}

#20
class Parcel implements FSharpUnion {
    public $tile;
    function __construct($tile) {
        $this->tile = $tile;
    }
    function get_FSharpCase() {
        return 'Parcel';
    }
    function CompareTo($other) {
        $_cmp__29 = $this->tile->CompareTo($other->tile);
        if ($_cmp__29 != 0) {
            return $_cmp__29;
        }        
        return 0;
    }
}

#21
function Shared_002EParcel___op_Addition__ZF6EFE4B($_arg1__2, $v) {
    return new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__2->tile, $v));
}

#22
class Field implements FSharpUnion {
    public $parcels;
    function __construct($parcels) {
        $this->parcels = $parcels;
    }
    function get_FSharpCase() {
        return 'Field';
    }
    function CompareTo($other) {
        $_cmp__30 = $this->parcels->CompareTo($other->parcels);
        if ($_cmp__30 != 0) {
            return $_cmp__30;
        }        
        return 0;
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
class Fence implements FSharpUnion {
    public $paths;
    function __construct($paths) {
        $this->paths = $paths;
    }
    function get_FSharpCase() {
        return 'Fence';
    }
    function CompareTo($other) {
        $_cmp__31 = $this->paths->CompareTo($other->paths);
        if ($_cmp__31 != 0) {
            return $_cmp__31;
        }        
        return 0;
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
abstract class Power implements Union, FSharpUnion {
}

#28
class Power_PowerUp extends Power {
    function __construct() {
    }
    function get_Case() {
        return 'Power_PowerUp';
    }
    function get_FSharpCase() {
        return 'PowerUp';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__32 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__32;
    }
}

#28
class Power_PowerDown extends Power {
    function __construct() {
    }
    function get_Case() {
        return 'Power_PowerDown';
    }
    function get_FSharpCase() {
        return 'PowerDown';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__33 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__33;
    }
}

#29
abstract class Card implements Union, FSharpUnion {
}

#29
class Card_Nitro extends Card {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
    function get_Case() {
        return 'Card_Nitro';
    }
    function get_FSharpCase() {
        return 'Nitro';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__34 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__34 != 0) {
            return $_cmp__34;
        }        
        $_cmp__35 = $this->power->CompareTo($other->power);
        if ($_cmp__35 != 0) {
            return $_cmp__35;
        }        
        return 0;
    }
}

#29
class Card_Rut extends Card {
    function __construct() {
    }
    function get_Case() {
        return 'Card_Rut';
    }
    function get_FSharpCase() {
        return 'Rut';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__36 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__36;
    }
}

#29
class Card_HayBale extends Card {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
    function get_Case() {
        return 'Card_HayBale';
    }
    function get_FSharpCase() {
        return 'HayBale';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__37 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__37 != 0) {
            return $_cmp__37;
        }        
        $_cmp__38 = $this->power->CompareTo($other->power);
        if ($_cmp__38 != 0) {
            return $_cmp__38;
        }        
        return 0;
    }
}

#29
class Card_Dynamite extends Card {
    function __construct() {
    }
    function get_Case() {
        return 'Card_Dynamite';
    }
    function get_FSharpCase() {
        return 'Dynamite';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__39 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__39;
    }
}

#29
class Card_HighVoltage extends Card {
    function __construct() {
    }
    function get_Case() {
        return 'Card_HighVoltage';
    }
    function get_FSharpCase() {
        return 'HighVoltage';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__40 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__40;
    }
}

#29
class Card_Watchdog extends Card {
    function __construct() {
    }
    function get_Case() {
        return 'Card_Watchdog';
    }
    function get_FSharpCase() {
        return 'Watchdog';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__41 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__41;
    }
}

#29
class Card_Helicopter extends Card {
    function __construct() {
    }
    function get_Case() {
        return 'Card_Helicopter';
    }
    function get_FSharpCase() {
        return 'Helicopter';
    }
    function get_Tag() {
        return 6;
    }
    function CompareTo($other) {
        $_cmp__42 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__42;
    }
}

#29
class Card_Bribe extends Card {
    function __construct() {
    }
    function get_Case() {
        return 'Card_Bribe';
    }
    function get_FSharpCase() {
        return 'Bribe';
    }
    function get_Tag() {
        return 7;
    }
    function CompareTo($other) {
        $_cmp__43 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__43;
    }
}

#29
class Card_GameOver extends Card {
    function __construct() {
    }
    function get_Case() {
        return 'Card_GameOver';
    }
    function get_FSharpCase() {
        return 'GameOver';
    }
    function get_Tag() {
        return 8;
    }
    function CompareTo($other) {
        $_cmp__44 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__44;
    }
}

#30
abstract class CardPower implements Union, FSharpUnion {
}

#30
class CardPower_One extends CardPower {
    function __construct() {
    }
    function get_Case() {
        return 'CardPower_One';
    }
    function get_FSharpCase() {
        return 'One';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__45 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__45;
    }
}

#30
class CardPower_Two extends CardPower {
    function __construct() {
    }
    function get_Case() {
        return 'CardPower_Two';
    }
    function get_FSharpCase() {
        return 'Two';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__46 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__46;
    }
}

#31
abstract class PlayCard implements Union, FSharpUnion {
}

#31
class PlayCard_PlayNitro extends PlayCard {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
    function get_Case() {
        return 'PlayCard_PlayNitro';
    }
    function get_FSharpCase() {
        return 'PlayNitro';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__47 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__47 != 0) {
            return $_cmp__47;
        }        
        $_cmp__48 = $this->power->CompareTo($other->power);
        if ($_cmp__48 != 0) {
            return $_cmp__48;
        }        
        return 0;
    }
}

#31
class PlayCard_PlayRut extends PlayCard {
    public $victim;
    function __construct($victim) {
        $this->victim = $victim;
    }
    function get_Case() {
        return 'PlayCard_PlayRut';
    }
    function get_FSharpCase() {
        return 'PlayRut';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__49 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__49 != 0) {
            return $_cmp__49;
        }        
        $_cmp__50 = $this->victim->CompareTo($other->victim);
        if ($_cmp__50 != 0) {
            return $_cmp__50;
        }        
        return 0;
    }
}

#31
class PlayCard_PlayHayBale extends PlayCard {
    public $path;
    public $moved;
    function __construct($path, $moved) {
        $this->path = $path;
        $this->moved = $moved;
    }
    function get_Case() {
        return 'PlayCard_PlayHayBale';
    }
    function get_FSharpCase() {
        return 'PlayHayBale';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__51 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__51 != 0) {
            return $_cmp__51;
        }        
        $_cmp__52 = $this->path->CompareTo($other->path);
        if ($_cmp__52 != 0) {
            return $_cmp__52;
        }        
        $_cmp__53 = $this->moved->CompareTo($other->moved);
        if ($_cmp__53 != 0) {
            return $_cmp__53;
        }        
        return 0;
    }
}

#31
class PlayCard_PlayDynamite extends PlayCard {
    public $path;
    function __construct($path) {
        $this->path = $path;
    }
    function get_Case() {
        return 'PlayCard_PlayDynamite';
    }
    function get_FSharpCase() {
        return 'PlayDynamite';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__54 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__54 != 0) {
            return $_cmp__54;
        }        
        $_cmp__55 = $this->path->CompareTo($other->path);
        if ($_cmp__55 != 0) {
            return $_cmp__55;
        }        
        return 0;
    }
}

#31
class PlayCard_PlayHighVoltage extends PlayCard {
    function __construct() {
    }
    function get_Case() {
        return 'PlayCard_PlayHighVoltage';
    }
    function get_FSharpCase() {
        return 'PlayHighVoltage';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__56 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__56;
    }
}

#31
class PlayCard_PlayWatchdog extends PlayCard {
    function __construct() {
    }
    function get_Case() {
        return 'PlayCard_PlayWatchdog';
    }
    function get_FSharpCase() {
        return 'PlayWatchdog';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__57 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__57;
    }
}

#31
class PlayCard_PlayHelicopter extends PlayCard {
    public $destination;
    function __construct($destination) {
        $this->destination = $destination;
    }
    function get_Case() {
        return 'PlayCard_PlayHelicopter';
    }
    function get_FSharpCase() {
        return 'PlayHelicopter';
    }
    function get_Tag() {
        return 6;
    }
    function CompareTo($other) {
        $_cmp__58 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__58 != 0) {
            return $_cmp__58;
        }        
        $_cmp__59 = $this->destination->CompareTo($other->destination);
        if ($_cmp__59 != 0) {
            return $_cmp__59;
        }        
        return 0;
    }
}

#31
class PlayCard_PlayBribe extends PlayCard {
    public $parcel;
    function __construct($parcel) {
        $this->parcel = $parcel;
    }
    function get_Case() {
        return 'PlayCard_PlayBribe';
    }
    function get_FSharpCase() {
        return 'PlayBribe';
    }
    function get_Tag() {
        return 7;
    }
    function CompareTo($other) {
        $_cmp__60 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__60 != 0) {
            return $_cmp__60;
        }        
        $_cmp__61 = $this->parcel->CompareTo($other->parcel);
        if ($_cmp__61 != 0) {
            return $_cmp__61;
        }        
        return 0;
    }
}

#31
class PlayCard_PlayGameOver extends PlayCard {
    function __construct() {
    }
    function get_Case() {
        return 'PlayCard_PlayGameOver';
    }
    function get_FSharpCase() {
        return 'PlayGameOver';
    }
    function get_Tag() {
        return 8;
    }
    function CompareTo($other) {
        $_cmp__62 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__62;
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
            }             else {
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
        case 'PlayCard_PlayGameOver':
            return new Card_GameOver();
        default:
            return new Card_Nitro($_arg1__5->power);
    }
}

#33
abstract class Hand implements Union, FSharpUnion {
}

#33
class Hand_PrivateHand extends Hand {
    public $cards;
    function __construct($cards) {
        $this->cards = $cards;
    }
    function get_Case() {
        return 'Hand_PrivateHand';
    }
    function get_FSharpCase() {
        return 'PrivateHand';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__63 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__63 != 0) {
            return $_cmp__63;
        }        
        $_cmp__64 = $this->cards > $other->cards ? 1 : ($this->cards < $other->cards ? -1 : 0);
        if ($_cmp__64 != 0) {
            return $_cmp__64;
        }        
        return 0;
    }
}

#33
class Hand_PublicHand extends Hand {
    public $cards;
    function __construct($cards) {
        $this->cards = $cards;
    }
    function get_Case() {
        return 'Hand_PublicHand';
    }
    function get_FSharpCase() {
        return 'PublicHand';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__65 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__65 != 0) {
            return $_cmp__65;
        }        
        $_cmp__66 = $this->cards->CompareTo($other->cards);
        if ($_cmp__66 != 0) {
            return $_cmp__66;
        }        
        return 0;
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
function Shared_002EHandModule___isPublic($_arg1__7) {
    switch (get_class($_arg1__7))
    {
        case 'Hand_PrivateHand':
            return false;
        default:
            return true;
    }
}

#37
function Shared_002EHandModule___toPrivate($_arg1__8) {
    if ($_arg1__8 instanceof Hand_PublicHand) {
        return new Hand_PrivateHand(FSharpList::length($_arg1__8->cards));
    }     else {
        return $_arg1__8;
    }
}

#38
function Shared_002EHandModule___count($_arg1__9) {
    switch (get_class($_arg1__9))
    {
        case 'Hand_PrivateHand':
            return $_arg1__9->cards;
        default:
            return FSharpList::length($_arg1__9->cards);
    }
}

#39
function Shared_002EHandModule___contains($card, $_arg1__10) {
    switch (get_class($_arg1__10))
    {
        case 'Hand_PrivateHand':
            return false;
        default:
            return FSharpList::contains($card, $_arg1__10->cards, [ 'Equals' => 'Util::equals', 'GetHashCode' => 'Util::structuralHash']);
    }
}

#40
function Shared_002EHandModule___remove($card__1, $hand) {
    switch (get_class($hand))
    {
        case 'Hand_PrivateHand':
            return new Hand_PrivateHand(($hand->cards - 1));
        default:
            $matchValue__2 = FSharpList::tryFindIndex(function ($c__1) use ($card__1) {             return Util::equals($c__1, $card__1);
 }, $hand->cards);
            if (is_null($matchValue__2)) {
                return $hand;
            }             else {
                $i = $matchValue__2;
                $patternInput = FSharpList::splitAt($i, $hand->cards);
                return new Hand_PublicHand(FSharpList::append($patternInput[0], FSharpList::tail($patternInput[1])));
            }
    }
}

#41
function Shared_002EHandModule___canPlay($_arg1__11) {
    switch (get_class($_arg1__11))
    {
        case 'Hand_PrivateHand':
            return $_arg1__11->cards > 0;
        default:
            $value = $_arg1__11->cards instanceof Nil;
            return !$value;
    }
}

#42
function Shared_002EHandModule___shouldDiscard($_arg1__12) {
    switch (get_class($_arg1__12))
    {
        case 'Hand_PrivateHand':
            return $_arg1__12->cards > 6;
        default:
            return FSharpList::length($_arg1__12->cards) > 6;
    }
}

#43
abstract class CrazyPlayer implements Union, FSharpUnion {
}

#43
class CrazyPlayer_Starting extends CrazyPlayer {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'CrazyPlayer_Starting';
    }
    function get_FSharpCase() {
        return 'Starting';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__67 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__67 != 0) {
            return $_cmp__67;
        }        
        $_cmp__68 = $this->Item->CompareTo($other->Item);
        if ($_cmp__68 != 0) {
            return $_cmp__68;
        }        
        return 0;
    }
}

#43
class CrazyPlayer_Playing extends CrazyPlayer {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'CrazyPlayer_Playing';
    }
    function get_FSharpCase() {
        return 'Playing';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__69 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__69 != 0) {
            return $_cmp__69;
        }        
        $_cmp__70 = $this->Item->CompareTo($other->Item);
        if ($_cmp__70 != 0) {
            return $_cmp__70;
        }        
        return 0;
    }
}

#43
class CrazyPlayer_Ko extends CrazyPlayer {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'CrazyPlayer_Ko';
    }
    function get_FSharpCase() {
        return 'Ko';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__71 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__71 != 0) {
            return $_cmp__71;
        }        
        $_cmp__72 = $this->Item->CompareTo($other->Item);
        if ($_cmp__72 != 0) {
            return $_cmp__72;
        }        
        return 0;
    }
}

#44
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

#45
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

#46
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

#47
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

#48
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

#49
function Shared_002EGameTable__get_Player($this___2, $unitVar1__2) {
    return $this___2->Players[$this___2->Current];
}

#50
function Shared_002EGameTable__get_Next($this___3, $unitVar1__3) {
    $Current = ($this___3->Current + 1) % count($this___3->Players);
    return new GameTable($this___3->Players, $this___3->AllPlayers, $this___3->Names, $Current);
}

#51
function Shared_002ETable___start($players) {
    $allplayers = FSharpArray::ofSeq(Seq::delay(function ($unitVar) use ($players) {     return Seq::collect(function ($matchValue__3) {     return Seq::singleton($matchValue__3[0]);
 }, $players);
 }));
    return new GameTable($allplayers, $allplayers, Map::ofList($players, [ 'Compare' => 'Util::comparePrimitives']), 0);
}

#52
function Shared_002ETable___eliminate($player, $table) {
    $index = FSharpArray::findIndex(function ($p__13) use ($player) {     return $p__13 === $player;
 }, $table->Players);
    $newPlayers = FSharpArray::filter(function ($p__14) use ($player) {     return $p__14 !== $player;
 }, $table->Players);
    if ($table->Current <= $index) {
        $Current__2 = $table->Current % count($newPlayers);
    }     else {
        $Current__2 = $table->Current - 1;
    }
    return new GameTable($newPlayers, $table->AllPlayers, $table->Names, $Current__2);
}

#53
function Shared_002ETable___isCurrent($playerid, $table__1) {
    return Shared_002EGameTable__get_Player($table__1, NULL) === $playerid;
}

#54
$GLOBALS['Shared_002EBonusModule___empty'] = new Bonus(0, 0, false, false, 0, 0);

#55
function Shared_002EBonusModule___startTurn($bonus) {
    return FSharpList::ofSeq(Seq::delay(function ($unitVar__1) use ($bonus) {     return Seq::append($bonus->HighVoltage ? Seq::singleton(new Card_HighVoltage()) : Seq::empty(), Seq::delay(function ($unitVar__2) use ($bonus) {     if ($bonus->Watched) {
        return Seq::singleton(new Card_Watchdog());
    }     else {
        return Seq::empty();
    }
 }));
 }));
}

#56
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

#57
function Shared_002EBonusModule___moveCapacityChange($bonus__2) {
    return $bonus__2->Rutted * -2;
}

#58
function Shared_002EBonusModule___discard($card__2, $bonus__3) {
    if ($card__2 instanceof Card_Nitro) {
        switch (get_class($card__2->power))
        {
            case 'CardPower_Two':
                $NitroTwo = $bonus__3->NitroTwo - 1;
                return new Bonus($bonus__3->NitroOne, $NitroTwo, $bonus__3->Watched, $bonus__3->HighVoltage, $bonus__3->Rutted, $bonus__3->Heliported);
            default:
                return new Bonus(($bonus__3->NitroOne - 1), $bonus__3->NitroTwo, $bonus__3->Watched, $bonus__3->HighVoltage, $bonus__3->Rutted, $bonus__3->Heliported);
        }
    }     else {
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

#59
abstract class Board implements Union, FSharpUnion {
}

#59
class Board_InitialState extends Board {
    function __construct() {
    }
    function get_Case() {
        return 'Board_InitialState';
    }
    function get_FSharpCase() {
        return 'InitialState';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__73 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__73;
    }
}

#59
class Board_Board extends Board {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Board_Board';
    }
    function get_FSharpCase() {
        return 'Board';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__74 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__74 != 0) {
            return $_cmp__74;
        }        
        $_cmp__75 = $this->Item->CompareTo($other->Item);
        if ($_cmp__75 != 0) {
            return $_cmp__75;
        }        
        return 0;
    }
}

#59
class Board_Won extends Board {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
    function get_Case() {
        return 'Board_Won';
    }
    function get_FSharpCase() {
        return 'Won';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__76 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__76 != 0) {
            return $_cmp__76;
        }        
        $_cmp__77 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__77 != 0) {
            return $_cmp__77;
        }        
        $_cmp__78 = $this->Item2->CompareTo($other->Item2);
        if ($_cmp__78 != 0) {
            return $_cmp__78;
        }        
        return 0;
    }
}

#60
class PlayingBoard {
    public $Players;
    public $Table;
    public $DrawPile;
    public $DiscardPile;
    public $Barns;
    public $HayBales;
    public $Goal;
    public $UseGameOver;
    function __construct($Players, $Table, $DrawPile, $DiscardPile, $Barns, $HayBales, $Goal, $UseGameOver) {
        $this->Players = $Players;
        $this->Table = $Table;
        $this->DrawPile = $DrawPile;
        $this->DiscardPile = $DiscardPile;
        $this->Barns = $Barns;
        $this->HayBales = $HayBales;
        $this->Goal = $Goal;
        $this->UseGameOver = $UseGameOver;
    }
}

#61
class UndoableBoard {
    public $Board;
    public $UndoPoint;
    public $UndoType;
    public $ShouldShuffle;
    public $AtUndoPoint;
    function __construct($Board, $UndoPoint, $UndoType, $ShouldShuffle, $AtUndoPoint) {
        $this->Board = $Board;
        $this->UndoPoint = $UndoPoint;
        $this->UndoType = $UndoType;
        $this->ShouldShuffle = $ShouldShuffle;
        $this->AtUndoPoint = $AtUndoPoint;
    }
}

#62
abstract class PlayerState implements Union, FSharpUnion {
}

#62
class PlayerState_SStarting extends PlayerState {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'PlayerState_SStarting';
    }
    function get_FSharpCase() {
        return 'SStarting';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__79 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__79 != 0) {
            return $_cmp__79;
        }        
        $_cmp__80 = $this->Item->CompareTo($other->Item);
        if ($_cmp__80 != 0) {
            return $_cmp__80;
        }        
        return 0;
    }
}

#62
class PlayerState_SPlaying extends PlayerState {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'PlayerState_SPlaying';
    }
    function get_FSharpCase() {
        return 'SPlaying';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__81 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__81 != 0) {
            return $_cmp__81;
        }        
        $_cmp__82 = $this->Item->CompareTo($other->Item);
        if ($_cmp__82 != 0) {
            return $_cmp__82;
        }        
        return 0;
    }
}

#62
class PlayerState_SKo extends PlayerState {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'PlayerState_SKo';
    }
    function get_FSharpCase() {
        return 'SKo';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__83 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__83 != 0) {
            return $_cmp__83;
        }        
        $_cmp__84 = $this->Item->CompareTo($other->Item);
        if ($_cmp__84 != 0) {
            return $_cmp__84;
        }        
        return 0;
    }
}

#63
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

#64
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

#65
class BoardState {
    public $SPlayers;
    public $STable;
    public $SDiscardPile;
    public $SDrawPile;
    public $SFreeBarns;
    public $SOccupiedBarns;
    public $SHayBales;
    public $SGoal;
    public $SWinner;
    public $SWinners;
    public $SUseGameOver;
    function __construct($SPlayers, $STable, $SDiscardPile, $SDrawPile, $SFreeBarns, $SOccupiedBarns, $SHayBales, $SGoal, $SWinner, $SWinners, $SUseGameOver) {
        $this->SPlayers = $SPlayers;
        $this->STable = $STable;
        $this->SDiscardPile = $SDiscardPile;
        $this->SDrawPile = $SDrawPile;
        $this->SFreeBarns = $SFreeBarns;
        $this->SOccupiedBarns = $SOccupiedBarns;
        $this->SHayBales = $SHayBales;
        $this->SGoal = $SGoal;
        $this->SWinner = $SWinner;
        $this->SWinners = $SWinners;
        $this->SUseGameOver = $SUseGameOver;
    }
}

#66
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

#67
class UndoBoardState {
    public $SBoard;
    public $SUndoPoint;
    public $SUndoType;
    public $SShouldShuffle;
    public $SAtUndoPoint;
    function __construct($SBoard, $SUndoPoint, $SUndoType, $SShouldShuffle, $SAtUndoPoint) {
        $this->SBoard = $SBoard;
        $this->SUndoPoint = $SUndoPoint;
        $this->SUndoType = $SUndoType;
        $this->SShouldShuffle = $SShouldShuffle;
        $this->SAtUndoPoint = $SAtUndoPoint;
    }
}

#68
function Shared_002ECrossroadModule___neighbor($dir, $_arg1__13) {
    if ($_arg1__13->side instanceof CrossroadSide_CRight) {
        switch (get_class($dir))
        {
            case 'Direction_Down':
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__13->tile, $GLOBALS['Shared_002EAxeModule___SE']), new CrossroadSide_CLeft()];
                break;
            case 'Direction_Horizontal':
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__13->tile, $GLOBALS['Shared_002EAxeModule___E2']), new CrossroadSide_CLeft()];
                break;
            default:
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__13->tile, $GLOBALS['Shared_002EAxeModule___NE']), new CrossroadSide_CLeft()];
                break;
        }
    }     else {
        switch (get_class($dir))
        {
            case 'Direction_Down':
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__13->tile, $GLOBALS['Shared_002EAxeModule___SW']), new CrossroadSide_CRight()];
                break;
            case 'Direction_Horizontal':
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__13->tile, $GLOBALS['Shared_002EAxeModule___W2']), new CrossroadSide_CRight()];
                break;
            default:
                $tupledArg = [ Shared_002EAxe___op_Addition__2BE35040($_arg1__13->tile, $GLOBALS['Shared_002EAxeModule___NW']), new CrossroadSide_CRight()];
                break;
        }
    }
    return new Crossroad($tupledArg[0], $tupledArg[1]);
}

#69
function Shared_002ECrossroadModule___neighborTiles($_arg1__14) {
    $p__15 = new Parcel($_arg1__14->tile);
    switch (get_class($_arg1__14->side))
    {
        case 'CrossroadSide_CRight':
            return new Cons($p__15, new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($p__15, $GLOBALS['Shared_002EAxeModule___NE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($p__15, $GLOBALS['Shared_002EAxeModule___SE']), FSharpList::get_Nil())));
        default:
            return new Cons($p__15, new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($p__15, $GLOBALS['Shared_002EAxeModule___NW']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($p__15, $GLOBALS['Shared_002EAxeModule___SW']), FSharpList::get_Nil())));
    }
}

#70
function Shared_002ECrossroadModule___tile($_arg1__15) {
    return $_arg1__15->tile;
}

#71
function Shared_002ECrossroadModule___side($_arg1__16) {
    return $_arg1__16->side;
}

#72
function Shared_002ECrossroadModule___isInField($_arg2__2, $_arg1__17) {
    $p__16 = new Parcel($_arg1__17->tile);
    switch (get_class($_arg1__17->side))
    {
        case 'CrossroadSide_CRight':
            if (Set::contains($p__16, $_arg2__2->parcels) ? true : Set::contains(Shared_002EParcel___op_Addition__ZF6EFE4B($p__16, $GLOBALS['Shared_002EAxeModule___NE']), $_arg2__2->parcels)) {
                return true;
            }             else {
                return Set::contains(Shared_002EParcel___op_Addition__ZF6EFE4B($p__16, $GLOBALS['Shared_002EAxeModule___SE']), $_arg2__2->parcels);
            }
        default:
            if (Set::contains($p__16, $_arg2__2->parcels) ? true : Set::contains(Shared_002EParcel___op_Addition__ZF6EFE4B($p__16, $GLOBALS['Shared_002EAxeModule___NW']), $_arg2__2->parcels)) {
                return true;
            }             else {
                return Set::contains(Shared_002EParcel___op_Addition__ZF6EFE4B($p__16, $GLOBALS['Shared_002EAxeModule___SW']), $_arg2__2->parcels);
            }
    }
}

#73
function Shared_002ECrossroadModule___isOnBoard($_arg1__18) {
    $patternInput__1 = Shared_002EAxeModule___cube($_arg1__18->tile);
    switch (get_class($_arg1__18->side))
    {
        case 'CrossroadSide_CRight':
            if (((($patternInput__1[0] >= -4 ? $patternInput__1[0] < 4 : false) ? $patternInput__1[1] > -4 : false) ? $patternInput__1[1] <= 4 : false) ? $patternInput__1[2] > -4 : false) {
                return $patternInput__1[2] <= 4;
            }             else {
                return false;
            }
        default:
            if (((($patternInput__1[0] > -4 ? $patternInput__1[0] <= 4 : false) ? $patternInput__1[1] >= -4 : false) ? $patternInput__1[1] < 4 : false) ? $patternInput__1[2] >= -4 : false) {
                return $patternInput__1[2] < 4;
            }             else {
                return false;
            }
    }
}

#74
$GLOBALS['Shared_002EParcelModule___center'] = new Parcel($GLOBALS['Shared_002EAxeModule___center']);

#75
function Shared_002EParcelModule___crossroads($_arg1__19) {
    return new Cons(new Crossroad($_arg1__19->tile, new CrossroadSide_CLeft()), new Cons(new Crossroad($_arg1__19->tile, new CrossroadSide_CRight()), new Cons(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($_arg1__19->tile, $GLOBALS['Shared_002EAxeModule___NW']), new CrossroadSide_CRight()), new Cons(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($_arg1__19->tile, $GLOBALS['Shared_002EAxeModule___NE']), new CrossroadSide_CLeft()), new Cons(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($_arg1__19->tile, $GLOBALS['Shared_002EAxeModule___SW']), new CrossroadSide_CRight()), new Cons(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($_arg1__19->tile, $GLOBALS['Shared_002EAxeModule___SE']), new CrossroadSide_CLeft()), FSharpList::get_Nil()))))));
}

#76
function Shared_002EParcelModule___contains($_arg2__3, $_arg1__20) {
    if (Util::equals($_arg2__3->tile, $_arg1__20->tile) ? true : (Util::equals($_arg2__3->side, new CrossroadSide_CRight()) ? (Util::equals($_arg2__3->tile, Shared_002EAxe___op_Addition__2BE35040($_arg1__20->tile, $GLOBALS['Shared_002EAxeModule___NW'])) ? true : Util::equals($_arg2__3->tile, Shared_002EAxe___op_Addition__2BE35040($_arg1__20->tile, $GLOBALS['Shared_002EAxeModule___SW']))) : false)) {
        return true;
    }     else {
        if (Util::equals($_arg2__3->side, new CrossroadSide_CLeft())) {
            if (Util::equals($_arg2__3->tile, Shared_002EAxe___op_Addition__2BE35040($_arg1__20->tile, $GLOBALS['Shared_002EAxeModule___NE']))) {
                return true;
            }             else {
                return Util::equals($_arg2__3->tile, Shared_002EAxe___op_Addition__2BE35040($_arg1__20->tile, $GLOBALS['Shared_002EAxeModule___SE']));
            }
        }         else {
            return false;
        }
    }
}

#77
function Shared_002EParcelModule___isOnBoard($_arg1__21) {
    $patternInput__2 = Shared_002EAxeModule___cube($_arg1__21->tile);
    if (abs($patternInput__2[0]) <= 3 ? abs($patternInput__2[1]) <= 3 : false) {
        return abs($patternInput__2[2]) <= 3;
    }     else {
        return false;
    }
}

#78
function Shared_002EParcelModule___unrestrictedNeighbors($_arg1__22) {
    return new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__22->tile, $GLOBALS['Shared_002EAxeModule___N'])), new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__22->tile, $GLOBALS['Shared_002EAxeModule___NE'])), new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__22->tile, $GLOBALS['Shared_002EAxeModule___SE'])), new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__22->tile, $GLOBALS['Shared_002EAxeModule___S'])), new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__22->tile, $GLOBALS['Shared_002EAxeModule___SW'])), new Cons(new Parcel(Shared_002EAxe___op_Addition__2BE35040($_arg1__22->tile, $GLOBALS['Shared_002EAxeModule___NW'])), FSharpList::get_Nil()))))));
}

#79
function Shared_002EParcelModule___neighbors($p__21) {
    $list__1 = Shared_002EParcelModule___unrestrictedNeighbors($p__21);
    return FSharpList::filter('Shared_002EParcelModule___isOnBoard', $list__1);
}

#80
function Shared_002EParcelModule___areNeighbors($_arg2__4, $_arg1__23) {
    if ((((Util::equals($_arg2__4->tile, Shared_002EAxe___op_Addition__2BE35040($_arg1__23->tile, $GLOBALS['Shared_002EAxeModule___N'])) ? true : Util::equals($_arg2__4->tile, Shared_002EAxe___op_Addition__2BE35040($_arg1__23->tile, $GLOBALS['Shared_002EAxeModule___NE']))) ? true : Util::equals($_arg2__4->tile, Shared_002EAxe___op_Addition__2BE35040($_arg1__23->tile, $GLOBALS['Shared_002EAxeModule___SE']))) ? true : Util::equals($_arg2__4->tile, Shared_002EAxe___op_Addition__2BE35040($_arg1__23->tile, $GLOBALS['Shared_002EAxeModule___S']))) ? true : Util::equals($_arg2__4->tile, Shared_002EAxe___op_Addition__2BE35040($_arg1__23->tile, $GLOBALS['Shared_002EAxeModule___SW']))) {
        return true;
    }     else {
        return Util::equals($_arg2__4->tile, Shared_002EAxe___op_Addition__2BE35040($_arg1__23->tile, $GLOBALS['Shared_002EAxeModule___NW']));
    }
}

#81
abstract class ParcelDir implements Union, FSharpUnion {
}

#81
class ParcelDir_PN extends ParcelDir {
    function __construct() {
    }
    function get_Case() {
        return 'ParcelDir_PN';
    }
    function get_FSharpCase() {
        return 'PN';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__85 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__85;
    }
}

#81
class ParcelDir_PNE extends ParcelDir {
    function __construct() {
    }
    function get_Case() {
        return 'ParcelDir_PNE';
    }
    function get_FSharpCase() {
        return 'PNE';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__86 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__86;
    }
}

#81
class ParcelDir_PSE extends ParcelDir {
    function __construct() {
    }
    function get_Case() {
        return 'ParcelDir_PSE';
    }
    function get_FSharpCase() {
        return 'PSE';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__87 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__87;
    }
}

#81
class ParcelDir_PS extends ParcelDir {
    function __construct() {
    }
    function get_Case() {
        return 'ParcelDir_PS';
    }
    function get_FSharpCase() {
        return 'PS';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__88 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__88;
    }
}

#81
class ParcelDir_PSW extends ParcelDir {
    function __construct() {
    }
    function get_Case() {
        return 'ParcelDir_PSW';
    }
    function get_FSharpCase() {
        return 'PSW';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__89 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__89;
    }
}

#81
class ParcelDir_PNW extends ParcelDir {
    function __construct() {
    }
    function get_Case() {
        return 'ParcelDir_PNW';
    }
    function get_FSharpCase() {
        return 'PNW';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__90 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__90;
    }
}

#82
function Shared_002EParcelModule___getDir($_arg2__5, $_arg1__24) {
    if (Util::equals(Shared_002EAxe___op_Addition__2BE35040($_arg2__5->tile, $GLOBALS['Shared_002EAxeModule___N']), $_arg1__24->tile)) {
        return new ParcelDir_PN();
    }     else {
        if (Util::equals(Shared_002EAxe___op_Addition__2BE35040($_arg2__5->tile, $GLOBALS['Shared_002EAxeModule___NE']), $_arg1__24->tile)) {
            return new ParcelDir_PNE();
        }         else {
            if (Util::equals(Shared_002EAxe___op_Addition__2BE35040($_arg2__5->tile, $GLOBALS['Shared_002EAxeModule___SE']), $_arg1__24->tile)) {
                return new ParcelDir_PSE();
            }             else {
                if (Util::equals(Shared_002EAxe___op_Addition__2BE35040($_arg2__5->tile, $GLOBALS['Shared_002EAxeModule___S']), $_arg1__24->tile)) {
                    return new ParcelDir_PS();
                }                 else {
                    if (Util::equals(Shared_002EAxe___op_Addition__2BE35040($_arg2__5->tile, $GLOBALS['Shared_002EAxeModule___SW']), $_arg1__24->tile)) {
                        return new ParcelDir_PSW();
                    }                     else {
                        return new ParcelDir_PNW();
                    }
                }
            }
        }
    }
}

#83
function Shared_002EParcelModule___dir($n) {
    $matchValue__9 = $n % 6;
    if ($matchValue__9 === 0) {
        return new ParcelDir_PN();
    }     else {
        if ($matchValue__9 === 1) {
            return new ParcelDir_PNE();
        }         else {
            if ($matchValue__9 === 2) {
                return new ParcelDir_PSE();
            }             else {
                if ($matchValue__9 === 3) {
                    return new ParcelDir_PS();
                }                 else {
                    if ($matchValue__9 === 4) {
                        return new ParcelDir_PSW();
                    }                     else {
                        return new ParcelDir_PNW();
                    }
                }
            }
        }
    }
}

#84
function Shared_002EParcelModule___dirs($s__1, $n__1) {
    return Set::ofSeq(FSharpList::ofSeq(Seq::delay(function ($unitVar__7) use ($n__1, $s__1) {     return Seq::map(function ($i__1) use ($s__1) {     return Shared_002EParcelModule___dir($s__1 + $i__1);
 }, Seq::rangeNumber(0, 1, $n__1));
 })), [ 'Compare' => function ($_x__5, $_y__6) {     return $_x__5->CompareTo($_y__6);
 }]);
}

#85
function Shared_002EPathModule___neighbor($dir__1, $_arg1__25) {
    if ($_arg1__25->side instanceof CrossroadSide_CRight) {
        switch (get_class($dir__1))
        {
            case 'Direction_Down':
                return new Path(Shared_002EAxe___op_Addition__2BE35040($_arg1__25->tile, $GLOBALS['Shared_002EAxeModule___SE']), new BorderSide_BNW());
            case 'Direction_Horizontal':
                return new Path(Shared_002EAxe___op_Addition__2BE35040($_arg1__25->tile, $GLOBALS['Shared_002EAxeModule___SE']), new BorderSide_BN());
            default:
                return new Path($_arg1__25->tile, new BorderSide_BNE());
        }
    }     else {
        switch (get_class($dir__1))
        {
            case 'Direction_Down':
                return new Path(Shared_002EAxe___op_Addition__2BE35040($_arg1__25->tile, $GLOBALS['Shared_002EAxeModule___SW']), new BorderSide_BNE());
            case 'Direction_Horizontal':
                return new Path(Shared_002EAxe___op_Addition__2BE35040($_arg1__25->tile, $GLOBALS['Shared_002EAxeModule___SW']), new BorderSide_BN());
            default:
                return new Path($_arg1__25->tile, new BorderSide_BNW());
        }
    }
}

#86
function Shared_002EPathModule___tile($_arg1__26) {
    return $_arg1__26->tile;
}

#87
function Shared_002EPathModule___neighborTiles($_arg1__27) {
    switch (get_class($_arg1__27->border))
    {
        case 'BorderSide_BNE':
            return Shared_002EAxe___op_Addition__2BE35040($_arg1__27->tile, $GLOBALS['Shared_002EAxeModule___NE']);
        case 'BorderSide_BN':
            return Shared_002EAxe___op_Addition__2BE35040($_arg1__27->tile, $GLOBALS['Shared_002EAxeModule___N']);
        default:
            return Shared_002EAxe___op_Addition__2BE35040($_arg1__27->tile, $GLOBALS['Shared_002EAxeModule___NW']);
    }
}

#88
function Shared_002EPathModule___ofMoves($moves, $start) {
    return FSharpList::mapFold(function ($pos, $move) {     return [ [ Shared_002EPathModule___neighbor($move, $pos), $move], Shared_002ECrossroadModule___neighbor($move, $pos)];
 }, $start, $moves);
}

#89
$GLOBALS['Shared_002EPathModule___allInnerPaths'] = Set::ofSeq(FSharpList::ofSeq(Seq::delay(function ($unitVar__8) { return Seq::append(Seq::collect(function ($q__3) { return Seq::map(function ($r__3) use ($q__3) { return new Path(new Axe($q__3, $r__3), new BorderSide_BN());
 }, Seq::rangeNumber(Util::max('Util::comparePrimitives', -2, -2 - $q__3), 1, Util::min('Util::comparePrimitives', 3, 3 - $q__3)));
 }, Seq::rangeNumber(-3, 1, 3)), Seq::delay(function ($unitVar__9) { return Seq::append(Seq::collect(function ($q__4) { return Seq::map(function ($r__4) use ($q__4) { return new Path(new Axe($q__4, $r__4), new BorderSide_BNE());
 }, Seq::rangeNumber(Util::max('Util::comparePrimitives', -2, -3 - $q__4), 1, Util::min('Util::comparePrimitives', 3, 3 - $q__4)));
 }, Seq::rangeNumber(-3, 1, 2)), Seq::delay(function ($unitVar__10) { return Seq::collect(function ($q__5) { return Seq::map(function ($r__5) use ($q__5) { return new Path(new Axe($q__5, $r__5), new BorderSide_BNW());
 }, Seq::rangeNumber(Util::max('Util::comparePrimitives', -3, -2 - $q__5), 1, Util::min('Util::comparePrimitives', 3, 3 - $q__5)));
 }, Seq::rangeNumber(-2, 1, 3));
 }));
 }));
 })), [ 'Compare' => function ($_x__19, $_y__20) { return $_x__19->CompareTo($_y__20);
 }]);

#90
$GLOBALS['Shared_002EPathModule___boderPaths'] = Set::ofSeq(FSharpList::ofSeq(Seq::delay(function ($unitVar__11) { return Seq::append(Seq::collect(function ($r__6) { return Seq::append(Seq::singleton(new Path(new Axe(-3, $r__6), new BorderSide_BNW())), Seq::delay(function ($unitVar__12) use ($r__6) { return Seq::append(Seq::singleton(new Path(Shared_002EAxe___op_Addition__2BE35040(new Axe(-3, $r__6), $GLOBALS['Shared_002EAxeModule___SW']), new BorderSide_BNE())), Seq::delay(function ($unitVar__13) use ($r__6) { return Seq::append(Seq::singleton(new Path(new Axe(3, (-$r__6)), new BorderSide_BNE())), Seq::delay(function ($unitVar__14) use ($r__6) { return Seq::singleton(new Path(Shared_002EAxe___op_Addition__2BE35040(new Axe(3, (-$r__6)), $GLOBALS['Shared_002EAxeModule___SE']), new BorderSide_BNW()));
 }));
 }));
 }));
 }, Seq::rangeNumber(0, 1, 3)), Seq::delay(function ($unitVar__15) { return Seq::append(Seq::collect(function ($q__6) { return Seq::append(Seq::singleton(new Path(new Axe($q__6, (-$q__6 - 3)), new BorderSide_BNW())), Seq::delay(function ($unitVar__16) use ($q__6) { return Seq::append(Seq::singleton(new Path(new Axe($q__6, (-$q__6 - 3)), new BorderSide_BN())), Seq::delay(function ($unitVar__17) use ($q__6) { return Seq::append(Seq::singleton(new Path(Shared_002EAxe___op_Addition__2BE35040(new Axe($q__6, 3), $GLOBALS['Shared_002EAxeModule___SW']), new BorderSide_BNE())), Seq::delay(function ($unitVar__18) use ($q__6) { return Seq::singleton(new Path(Shared_002EAxe___op_Addition__2BE35040(new Axe($q__6, 3), $GLOBALS['Shared_002EAxeModule___S']), new BorderSide_BN()));
 }));
 }));
 }));
 }, Seq::rangeNumber(-3, 1, 0)), Seq::delay(function ($unitVar__19) { return Seq::collect(function ($q__7) { return Seq::append(Seq::singleton(new Path(new Axe($q__7, -3), new BorderSide_BN())), Seq::delay(function ($unitVar__20) use ($q__7) { return Seq::append(Seq::singleton(new Path(new Axe($q__7, -3), new BorderSide_BNE())), Seq::delay(function ($unitVar__21) use ($q__7) { return Seq::append(Seq::singleton(new Path(Shared_002EAxe___op_Addition__2BE35040(new Axe($q__7, (3 - $q__7)), $GLOBALS['Shared_002EAxeModule___S']), new BorderSide_BN())), Seq::delay(function ($unitVar__22) use ($q__7) { return Seq::singleton(new Path(Shared_002EAxe___op_Addition__2BE35040(new Axe($q__7, (3 - $q__7)), $GLOBALS['Shared_002EAxeModule___SE']), new BorderSide_BNW()));
 }));
 }));
 }));
 }, Seq::rangeNumber(0, 1, 3));
 }));
 }));
 })), [ 'Compare' => function ($_x__21, $_y__22) { return $_x__21->CompareTo($_y__22);
 }]);

#91
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

#92
abstract class OrientedPath implements Union, FSharpUnion {
}

#92
class OrientedPath_DNE extends OrientedPath {
    function __construct() {
    }
    function get_Case() {
        return 'OrientedPath_DNE';
    }
    function get_FSharpCase() {
        return 'DNE';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__91 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__91;
    }
}

#92
class OrientedPath_DNW extends OrientedPath {
    function __construct() {
    }
    function get_Case() {
        return 'OrientedPath_DNW';
    }
    function get_FSharpCase() {
        return 'DNW';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__92 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__92;
    }
}

#92
class OrientedPath_DW extends OrientedPath {
    function __construct() {
    }
    function get_Case() {
        return 'OrientedPath_DW';
    }
    function get_FSharpCase() {
        return 'DW';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__93 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__93;
    }
}

#92
class OrientedPath_DSW extends OrientedPath {
    function __construct() {
    }
    function get_Case() {
        return 'OrientedPath_DSW';
    }
    function get_FSharpCase() {
        return 'DSW';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__94 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__94;
    }
}

#92
class OrientedPath_DSE extends OrientedPath {
    function __construct() {
    }
    function get_Case() {
        return 'OrientedPath_DSE';
    }
    function get_FSharpCase() {
        return 'DSE';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__95 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__95;
    }
}

#92
class OrientedPath_DE extends OrientedPath {
    function __construct() {
    }
    function get_Case() {
        return 'OrientedPath_DE';
    }
    function get_FSharpCase() {
        return 'DE';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__96 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__96;
    }
}

#93
$GLOBALS['Shared_002EFenceModule___empty'] = new Fence(FSharpList::get_Nil());

#94
function Shared_002EFenceModule___isEmpty($_arg1__28) {
    return $_arg1__28->paths instanceof Nil;
}

#95
function Shared_002EFenceModule___findLoop($dir__2, $pos__1, $_arg1__29) {
    $nextPos = Shared_002ECrossroadModule___neighbor($dir__2, $pos__1);
    $iter = function ($pos__2, $loop, $paths__2) use ($nextPos, &$iter) {     if ($paths__2 instanceof Cons) {
        $nextEnd = Shared_002ECrossroadModule___neighbor(Shared_002EDirectionModule___rev($paths__2->value[1]), $pos__2);
        if (Util::equals($nextEnd, $nextPos)) {
            return new Fence(new Cons([ $paths__2->value[0], $paths__2->value[1]], $loop));
        }         else {
            return $iter($nextEnd, new Cons([ $paths__2->value[0], $paths__2->value[1]], $loop), $paths__2->next);
        }
    }     else {
        return $GLOBALS['Shared_002EFenceModule___empty'];
    }
 };
    return $iter($pos__1, FSharpList::get_Nil(), $_arg1__29->paths);
}

#96
function Shared_002EFenceModule___add($path_0, $path_1, $_arg1__30) {
    return new Fence(new Cons([ $path_0, $path_1], $_arg1__30->paths));
}

#97
function Shared_002EFenceModule___tail($_arg1__31) {
    return new Fence(FSharpList::tail($_arg1__31->paths));
}

#98
function Shared_002EFenceModule___fenceCrossroads($tractor, $_arg1__32) {
    $loop__1 = function ($pos__3, $paths__6) use (&$loop__1) {     return Seq::delay(function ($unitVar__23) use ($paths__6, $pos__3, &$loop__1) {     if ($paths__6 instanceof Cons) {
        $next = Shared_002ECrossroadModule___neighbor(Shared_002EDirectionModule___rev($paths__6->value[1]), $pos__3);
        return Seq::append(Seq::singleton($next), Seq::delay(function ($unitVar__24) use ($next, $paths__6, &$loop__1) {         return $loop__1($next, $paths__6->next);
 }));
    }     else {
        return Seq::empty();
    }
 });
 };
    return $loop__1($tractor, $_arg1__32->paths);
}

#99
function Shared_002EFenceModule___fencePaths($_arg1__33) {
    return FSharpList::map(function ($tuple) {     return $tuple[0];
 }, $_arg1__33->paths);
}

#100
function Shared_002EFenceModule___bribeAnnexation($p__22, $tractor__1, $_arg1__34) {
    $findExit = function ($remainingLength, $pos__4, $paths__9) use ($p__22, &$findExit) {     if ($paths__9 instanceof Cons) {
        $next__1 = Shared_002ECrossroadModule___neighbor(Shared_002EDirectionModule___rev($paths__9->value[1]), $pos__4);
        if (Shared_002EParcelModule___contains($next__1, $p__22)) {
            return $findExit($remainingLength, $next__1, $paths__9->next);
        }         else {
            return [ $remainingLength, $pos__4, $paths__9];
        }
    }     else {
        return [ $remainingLength, $pos__4, FSharpList::get_Nil()];
    }
 };
    $findContact = function ($remainingLength__1, $pos__5, $paths__10) use ($p__22, &$findContact, &$findExit) {     if ($paths__10 instanceof Cons) {
        $next__2 = Shared_002ECrossroadModule___neighbor(Shared_002EDirectionModule___rev($paths__10->value[1]), $pos__5);
        if (Shared_002EParcelModule___contains($next__2, $p__22)) {
            return $findExit($remainingLength__1 + 1, $next__2, $paths__10->next);
        }         else {
            return $findContact($remainingLength__1 + 1, $next__2, $paths__10->next);
        }
    }     else {
        return NULL;
    }
 };
    if (Shared_002EParcelModule___contains($tractor__1, $p__22)) {
        return $findExit(0, $tractor__1, $_arg1__34->paths);
    }     else {
        return $findContact(0, $tractor__1, $_arg1__34->paths);
    }
}

#101
function Shared_002EFenceModule___start($tractor__2, $_arg1__35) {
    $loop__2 = function ($pos__6, $paths__12) use (&$loop__2) {     if ($paths__12 instanceof Cons) {
        $next__3 = Shared_002ECrossroadModule___neighbor(Shared_002EDirectionModule___rev($paths__12->value[1]), $pos__6);
        return $loop__2($next__3, $paths__12->next);
    }     else {
        return $pos__6;
    }
 };
    return $loop__2($tractor__2, $_arg1__35->paths);
}

#102
function Shared_002EFenceModule___length($_arg1__36) {
    return FSharpList::length($_arg1__36->paths);
}

#103
function Shared_002EFenceModule___remove($toRemove, $_arg1__37) {
    return new Fence(FSharpList::skip(Shared_002EFenceModule___length($toRemove), $_arg1__37->paths));
}

#104
function Shared_002EFenceModule___truncate($count, $_arg1__38) {
    return new Fence(FSharpList::truncate($count, $_arg1__38->paths));
}

#105
function Shared_002EFenceModule___toOriented($tractor__3, $_arg1__39) {
    $patternInput__3 = FSharpList::mapFold(function ($pos__7, $tupledArg__1) { 
        $matchValue__11 = [ $tupledArg__1[1], Shared_002ECrossroadModule___side($pos__7)];
        if ($matchValue__11[0] instanceof Direction_Up) {
            switch (get_class($matchValue__11[1]))
            {
                case 'CrossroadSide_CLeft':
                    $o = new OrientedPath_DNW();
                    break;
                default:
                    $o = new OrientedPath_DNE();
                    break;
            }
        }         else {
            if ($matchValue__11[0] instanceof Direction_Down) {
                switch (get_class($matchValue__11[1]))
                {
                    case 'CrossroadSide_CLeft':
                        $o = new OrientedPath_DSW();
                        break;
                    default:
                        $o = new OrientedPath_DSE();
                        break;
                }
            }             else {
                switch (get_class($matchValue__11[1]))
                {
                    case 'CrossroadSide_CLeft':
                        $o = new OrientedPath_DE();
                        break;
                    default:
                        $o = new OrientedPath_DW();
                        break;
                }
            }
        }
        return [ $o, Shared_002ECrossroadModule___neighbor($tupledArg__1[1], $pos__7)];
    }, $tractor__3, $_arg1__39->paths);
    return [ FSharpList::reverse($patternInput__3[0]), $patternInput__3[1]];
}

#106
function Shared_002EFenceModule___givesAcceleration($fence) {
    return Shared_002EFenceModule___length($fence) >= 4;
}

#107
function Shared_002EFenceOps____007CRwd_007C__007C($nextPath, $_arg1__41) {
    if ($_arg1__41->paths instanceof Cons) {
        if (Util::equals($_arg1__41->paths->value[0], $nextPath)) {
            $last__1 = $_arg1__41->paths->value[0];
            return NULL;
        }         else {
            return NULL;
        }
    }     else {
        return NULL;
    }
}

#108
$GLOBALS['Shared_002EFieldModule___empty'] = new Field(Set::empty([ 'Compare' => function ($_x__23, $_y__24) { return $_x__23->CompareTo($_y__24);
 }]));

#109
function Shared_002EFieldModule___isEmpty($_arg1__42) {
    return Set::isEmpty($_arg1__42->parcels);
}

#110
function Shared_002EFieldModule___size($_arg1__43) {
    return Set::count($_arg1__43->parcels);
}

#111
function Shared_002EFieldModule___create($parcel) {
    return new Field(Set::ofSeq(new Cons($parcel, FSharpList::get_Nil()), [ 'Compare' => function ($_x__25, $_y__26) {     return $_x__25->CompareTo($_y__26);
 }]));
}

#112
function Shared_002EFieldModule___ofParcels($parcels__1) {
    return new Field(Set::ofSeq($parcels__1, [ 'Compare' => function ($_x__27, $_y__28) {     return $_x__27->CompareTo($_y__28);
 }]));
}

#113
function Shared_002EFieldModule___parcels($_arg1__44) {
    return Set::toList($_arg1__44->parcels);
}

#114
function Shared_002EFieldModule___contains($parcel__1, $_arg1__45) {
    return Set::contains(new Parcel($parcel__1), $_arg1__45->parcels);
}

#115
function Shared_002EFieldModule___containsParcel($parcel__2, $_arg1__46) {
    return Set::contains($parcel__2, $_arg1__46->parcels);
}

#116
function Shared_002EFieldModule___intersect($_arg2__6, $_arg1__47) {
    return new Field(Set::intersect($_arg2__6->parcels, $_arg1__47->parcels));
}

#117
function Shared_002EFieldModule___unionMany($fields) {
    $elements = FSharpList::collect('Shared_002EFieldModule___parcels', $fields);
    $arg0__1 = Set::ofSeq($elements, [ 'Compare' => function ($_x__29, $_y__30) {     return $_x__29->CompareTo($_y__30);
 }]);
    return new Field($arg0__1);
}

#118
function Shared_002EFieldModule___crossroads($_arg1__48) {
    $elements__1 = Seq::collect('Shared_002EParcelModule___crossroads', $_arg1__48->parcels);
    return Set::ofSeq($elements__1, [ 'Compare' => function ($_x__31, $_y__32) {     return $_x__31->CompareTo($_y__32);
 }]);
}

#119
function Shared_002EFieldModule___fill($paths__18) {
    $list__5 = FSharpList::choose(function ($_arg1__49) {     switch (get_class($_arg1__49[1]))
    {
        case 'Direction_Horizontal':
            $t__1 = $_arg1__49[0]->tile;
            return $t__1;
        default:
            return NULL;
    }
 }, $paths__18);
    $list__6 = FSharpList::sortBy(function ($t__2) {     return [ Shared_002EAxe__get_Q($t__2, NULL), Shared_002EAxe__get_R($t__2, NULL)];
 }, $list__5, [ 'Compare' => 'Util::compareArrays']);
    $sortedPaths = FSharpList::groupBy(function ($tile__8) {     return Shared_002EAxe__get_Q($tile__8, NULL);
 }, $list__6, [ 'Equals' => function ($_x__35, $_y__36) {     return $_x__35 === $_y__36;
 }, 'GetHashCode' => 'Util::structuralHash']);
    $elements__2 = FSharpList::ofSeq(Seq::delay(function ($unitVar__25) use ($sortedPaths) {     return Seq::collect(function ($matchValue__12) {     return Seq::collect(function ($l) use ($matchValue__12) {     if ($l instanceof Cons) {
        if ($l->next instanceof Cons) {
            if ($l->next->next instanceof Nil) {
                $e = $l->next->value;
                $s__2 = $l->value;
                return FSharpList::ofSeq(Seq::delay(function ($unitVar__26) use ($e, $matchValue__12, $s__2) {                 return Seq::map(function ($r__7) use ($matchValue__12) {                 return new Parcel(new Axe($matchValue__12[0], $r__7));
 }, Seq::rangeNumber(Shared_002EAxe__get_R($s__2, NULL), 1, Shared_002EAxe__get_R($e, NULL) - 1));
 }));
            }             else {
                return Seq::empty();
            }
        }         else {
            return Seq::empty();
        }
    }     else {
        return Seq::empty();
    }
 }, FSharpList::chunkBySize(2, $matchValue__12[1]));
 }, $sortedPaths);
 }));
    $arg0__2 = Set::ofSeq($elements__2, [ 'Compare' => function ($_x__37, $_y__38) {     return $_x__37->CompareTo($_y__38);
 }]);
    return new Field($arg0__2);
}

#120
function Shared_002EFieldModule___border($neighbors, $_arg1__50) {
    $elements__3 = Seq::collect($neighbors, $_arg1__50->parcels);
    $allNeighbors = Set::ofSeq($elements__3, [ 'Compare' => function ($_x__39, $_y__40) {     return $_x__39->CompareTo($_y__40);
 }]);
    $arg0__3 = Set::FSharpSet___op_Subtraction($allNeighbors, $_arg1__50->parcels);
    return new Field($arg0__3);
}

#121
function Shared_002EFieldModule___borderTiles($parcels__7) {
    return Shared_002EFieldModule___border('Shared_002EParcelModule___neighbors', $parcels__7);
}

#122
function Shared_002EFieldModule___unrestrictedborderTiles($parcels__8) {
    return Shared_002EFieldModule___border('Shared_002EParcelModule___unrestrictedNeighbors', $parcels__8);
}

#123
function Shared_002EFieldModule___counterclock($field, $_arg1__51) {
    switch (get_class($_arg1__51->side))
    {
        case 'CrossroadSide_CLeft':
            if (Shared_002EFieldModule___contains($_arg1__51->tile, $field)) {
                if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__51->tile, $GLOBALS['Shared_002EAxeModule___SW']), $field)) {
                    return [ new Direction_Horizontal(), new OrientedPath_DW()];
                }                 else {
                    return [ new Direction_Down(), new OrientedPath_DSE()];
                }
            }             else {
                if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__51->tile, $GLOBALS['Shared_002EAxeModule___NW']), $field)) {
                    return [ new Direction_Up(), new OrientedPath_DNE()];
                }                 else {
                    if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__51->tile, $GLOBALS['Shared_002EAxeModule___NW']), $field)) {
                        return [ new Direction_Up(), new OrientedPath_DNE()];
                    }                     else {
                        return [ new Direction_Horizontal(), new OrientedPath_DW()];
                    }
                }
            }
        default:
            if (Shared_002EFieldModule___contains($_arg1__51->tile, $field)) {
                if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__51->tile, $GLOBALS['Shared_002EAxeModule___NE']), $field)) {
                    return [ new Direction_Horizontal(), new OrientedPath_DE()];
                }                 else {
                    return [ new Direction_Up(), new OrientedPath_DNW()];
                }
            }             else {
                if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__51->tile, $GLOBALS['Shared_002EAxeModule___NE']), $field)) {
                    if (Shared_002EFieldModule___contains(Shared_002EAxe___op_Addition__2BE35040($_arg1__51->tile, $GLOBALS['Shared_002EAxeModule___SE']), $field)) {
                        return [ new Direction_Down(), new OrientedPath_DSW()];
                    }                     else {
                        return [ new Direction_Horizontal(), new OrientedPath_DE()];
                    }
                }                 else {
                    return [ new Direction_Down(), new OrientedPath_DSW()];
                }
            }
    }
}

#124
function Shared_002EFieldModule___borderBetween($start__1, $end_0027__1, $field__1) {
    $loop__3 = function ($orientedPath, $pos__8, $path__2) use ($end_0027__1, $field__1, $start__1, &$loop__3) {     if (Util::equals($pos__8, $end_0027__1)) {
        return FSharpList::reverse($path__2);
    }     else {
        if (Util::equals($pos__8, $start__1)) {
            return FSharpList::get_Nil();
        }         else {
            switch (get_class($orientedPath))
            {
                case 'OrientedPath_DNE':
                    $tile__11 = Shared_002EAxe___op_Addition__2BE35040(Shared_002ECrossroadModule___tile($pos__8), $GLOBALS['Shared_002EAxeModule___NE']);
                    if (Shared_002EFieldModule___contains($tile__11, $field__1)) {
                        $next__6 = Shared_002ECrossroadModule___neighbor(new Direction_Horizontal(), $pos__8);
                        return $loop__3(new OrientedPath_DE(), $next__6, new Cons([ Shared_002EPathModule___neighbor(new Direction_Horizontal(), $pos__8), new Direction_Horizontal()], $path__2));
                    }                     else {
                        $next__7 = Shared_002ECrossroadModule___neighbor(new Direction_Up(), $pos__8);
                        return $loop__3(new OrientedPath_DNW(), $next__7, new Cons([ Shared_002EPathModule___neighbor(new Direction_Up(), $pos__8), new Direction_Up()], $path__2));
                    }
                case 'OrientedPath_DNW':
                    $tile__12 = Shared_002EAxe___op_Addition__2BE35040(Shared_002ECrossroadModule___tile($pos__8), $GLOBALS['Shared_002EAxeModule___NW']);
                    if (Shared_002EFieldModule___contains($tile__12, $field__1)) {
                        $next__8 = Shared_002ECrossroadModule___neighbor(new Direction_Up(), $pos__8);
                        return $loop__3(new OrientedPath_DNE(), $next__8, new Cons([ Shared_002EPathModule___neighbor(new Direction_Up(), $pos__8), new Direction_Up()], $path__2));
                    }                     else {
                        $next__9 = Shared_002ECrossroadModule___neighbor(new Direction_Horizontal(), $pos__8);
                        return $loop__3(new OrientedPath_DW(), $next__9, new Cons([ Shared_002EPathModule___neighbor(new Direction_Horizontal(), $pos__8), new Direction_Horizontal()], $path__2));
                    }
                case 'OrientedPath_DW':
                    $tile__13 = Shared_002ECrossroadModule___tile($pos__8);
                    if (Shared_002EFieldModule___contains($tile__13, $field__1)) {
                        $next__10 = Shared_002ECrossroadModule___neighbor(new Direction_Up(), $pos__8);
                        return $loop__3(new OrientedPath_DNW(), $next__10, new Cons([ Shared_002EPathModule___neighbor(new Direction_Up(), $pos__8), new Direction_Up()], $path__2));
                    }                     else {
                        $next__11 = Shared_002ECrossroadModule___neighbor(new Direction_Down(), $pos__8);
                        return $loop__3(new OrientedPath_DSW(), $next__11, new Cons([ Shared_002EPathModule___neighbor(new Direction_Down(), $pos__8), new Direction_Down()], $path__2));
                    }
                case 'OrientedPath_DSW':
                    $tile__14 = Shared_002EAxe___op_Addition__2BE35040(Shared_002ECrossroadModule___tile($pos__8), $GLOBALS['Shared_002EAxeModule___SW']);
                    if (Shared_002EFieldModule___contains($tile__14, $field__1)) {
                        $next__12 = Shared_002ECrossroadModule___neighbor(new Direction_Horizontal(), $pos__8);
                        return $loop__3(new OrientedPath_DW(), $next__12, new Cons([ Shared_002EPathModule___neighbor(new Direction_Horizontal(), $pos__8), new Direction_Horizontal()], $path__2));
                    }                     else {
                        $next__13 = Shared_002ECrossroadModule___neighbor(new Direction_Down(), $pos__8);
                        return $loop__3(new OrientedPath_DSE(), $next__13, new Cons([ Shared_002EPathModule___neighbor(new Direction_Down(), $pos__8), new Direction_Down()], $path__2));
                    }
                case 'OrientedPath_DSE':
                    $tile__15 = Shared_002EAxe___op_Addition__2BE35040(Shared_002ECrossroadModule___tile($pos__8), $GLOBALS['Shared_002EAxeModule___SE']);
                    if (Shared_002EFieldModule___contains($tile__15, $field__1)) {
                        $next__14 = Shared_002ECrossroadModule___neighbor(new Direction_Down(), $pos__8);
                        return $loop__3(new OrientedPath_DSW(), $next__14, new Cons([ Shared_002EPathModule___neighbor(new Direction_Down(), $pos__8), new Direction_Down()], $path__2));
                    }                     else {
                        $next__15 = Shared_002ECrossroadModule___neighbor(new Direction_Horizontal(), $pos__8);
                        return $loop__3(new OrientedPath_DE(), $next__15, new Cons([ Shared_002EPathModule___neighbor(new Direction_Horizontal(), $pos__8), new Direction_Horizontal()], $path__2));
                    }
                default:
                    $tile__10 = Shared_002ECrossroadModule___tile($pos__8);
                    if (Shared_002EFieldModule___contains($tile__10, $field__1)) {
                        $next__4 = Shared_002ECrossroadModule___neighbor(new Direction_Down(), $pos__8);
                        return $loop__3(new OrientedPath_DSE(), $next__4, new Cons([ Shared_002EPathModule___neighbor(new Direction_Down(), $pos__8), new Direction_Down()], $path__2));
                    }                     else {
                        $next__5 = Shared_002ECrossroadModule___neighbor(new Direction_Up(), $pos__8);
                        return $loop__3(new OrientedPath_DNE(), $next__5, new Cons([ Shared_002EPathModule___neighbor(new Direction_Up(), $pos__8), new Direction_Up()], $path__2));
                    }
            }
        }
    }
 };
    $patternInput__4 = Shared_002EFieldModule___counterclock($field__1, $start__1);
    $pos__9 = Shared_002ECrossroadModule___neighbor($patternInput__4[0], $start__1);
    return $loop__3($patternInput__4[1], $pos__9, new Cons([ Shared_002EPathModule___neighbor($patternInput__4[0], $start__1), $patternInput__4[0]], FSharpList::get_Nil()));
}

#125
function Shared_002EFieldModule___isInSameField($start__2, $end_0027__2, $field__2) {
    $list__7 = Shared_002EFieldModule___borderBetween($start__2, $end_0027__2, $field__2);
    $value__1 = $list__7 instanceof Nil;
    return !$value__1;
}

#126
function Shared_002EFieldModule___pathInFieldOrBorder($path__3, $field__3) {
    if (Shared_002EFieldModule___contains(Shared_002EPathModule___tile($path__3), $field__3)) {
        return true;
    }     else {
        return Shared_002EFieldModule___contains(Shared_002EPathModule___neighborTiles($path__3), $field__3);
    }
}

#127
function Shared_002EFieldModule___findBorder($field__4, $crossroad) {
    $list__8 = Shared_002ECrossroadModule___neighborTiles($crossroad);
    $neighborTilesInField = FSharpList::sumBy(function ($p__24) use ($field__4) {     if (Shared_002EFieldModule___containsParcel($p__24, $field__4)) {
        return 1;
    }     else {
        return 0;
    }
 }, $list__8, [ 'GetZero' => function () {     return 0;
 }, 'Add' => function ($_x__41, $_y__42) {     return $_x__41 + $_y__42;
 }]);
    if ($neighborTilesInField < 3) {
        return $crossroad;
    }     else {
        return Shared_002EFieldModule___findBorder($field__4, Shared_002ECrossroadModule___neighbor(new Direction_Up(), $crossroad));
    }
}

#128
function Shared_002EFieldModule___principalField($field__5, $fence__1, $crossroad__1) {
    $start__3 = Shared_002EFenceModule___start($crossroad__1, $fence__1);
    if (Shared_002ECrossroadModule___isInField($field__5, $start__3)) {
        $onBorder = Shared_002EFieldModule___findBorder($field__5, $start__3);
        $border = Shared_002EFieldModule___borderBetween($onBorder, $onBorder, $field__5);
        return Shared_002EFieldModule___fill($border);
    }     else {
        return $GLOBALS['Shared_002EFieldModule___empty'];
    }
}

#129
$GLOBALS['Shared_002EBarnsModule___empty'] = new Barns($GLOBALS['Shared_002EFieldModule___empty'], $GLOBALS['Shared_002EFieldModule___empty']);

#130
function Shared_002EBarnsModule___intersectWith($field__6, $barns) {
    return new Barns(Shared_002EFieldModule___intersect($field__6, $barns->Free), Shared_002EFieldModule___intersect($field__6, $barns->Occupied));
}

#131
function Shared_002EBarnsModule___init($barns__1) {
    return new Barns(Shared_002EFieldModule___ofParcels($barns__1), $GLOBALS['Shared_002EFieldModule___empty']);
}

#132
function Shared_002EBarnsModule___annex($annexed, $barns__2) {
    return new Barns(Shared_002EField___op_Subtraction__Z24735800($barns__2->Free, $annexed->Free), Shared_002EField___op_Addition__Z24735800($barns__2->Occupied, Shared_002EFieldModule___intersect($barns__2->Free, $annexed->Free)));
}

#133
function Shared_002EHayBales___findCutPaths($hayBales) {
    $neighbor__1 = function ($dir__9, $crossroad__2) use ($hayBales) { 
        $neighbor = Shared_002ECrossroadModule___neighbor($dir__9, $crossroad__2);
        if (Shared_002ECrossroadModule___isOnBoard($neighbor)) {
            $path__4 = Shared_002EPathModule___neighbor($dir__9, $crossroad__2);
            if (Set::contains($path__4, $hayBales)) {
                return NULL;
            }             else {
                return [ $path__4, $neighbor];
            }
        }         else {
            return NULL;
        }
    };
    $cut = FSharpList::get_Nil();
    $visited = Map::empty([ 'Compare' => function ($_x__43, $_y__44) {     return $_x__43->CompareTo($_y__44);
 }]);
    $time = 0;
    $loop__4 = function ($parent, $crossroad__3) use (&$cut, &$loop__4, &$neighbor__1, &$time, &$visited) { 
        $visited = Map::add($crossroad__3, $time, $visited);
        $d0 = $time;
        $time = $time + 1;
        $matchValue__13 = $neighbor__1(new Direction_Up(), $crossroad__3);
        if (!is_null($matchValue__13)) {
            if ((function () use ($matchValue__13, $parent) { 
                $p__25 = $matchValue__13[0];
                $nxt = $matchValue__13[1];
                return !Util::equals($nxt, $parent);
            })()) {
                $nxt__1 = $matchValue__13[1];
                $p__26 = $matchValue__13[0];
                $matchValue__14 = Map::tryFind($nxt__1, $visited);
                if (is_null($matchValue__14)) {
                    $n__2 = $loop__4($crossroad__3, $nxt__1);
                }                 else {
                    $d = $matchValue__14;
                    $n__2 = $d;
                }
                if ($n__2 > $d0) {
                    $cut = new Cons($p__26, $cut);
                }                 else {
                }
                $upDepth = $n__2;
            }             else {
                $upDepth = $d0 + 1;
            }
        }         else {
            $upDepth = $d0 + 1;
        }
        $matchValue__15 = $neighbor__1(new Direction_Down(), $crossroad__3);
        if (!is_null($matchValue__15)) {
            if ((function () use ($matchValue__15, $parent) { 
                $p__27 = $matchValue__15[0];
                $nxt__2 = $matchValue__15[1];
                return !Util::equals($nxt__2, $parent);
            })()) {
                $nxt__3 = $matchValue__15[1];
                $p__28 = $matchValue__15[0];
                $matchValue__16 = Map::tryFind($nxt__3, $visited);
                if (is_null($matchValue__16)) {
                    $n__3 = $loop__4($crossroad__3, $nxt__3);
                }                 else {
                    $d__1 = $matchValue__16;
                    $n__3 = $d__1;
                }
                if ($n__3 > $d0) {
                    $cut = new Cons($p__28, $cut);
                }                 else {
                }
                $downDepth = $n__3;
            }             else {
                $downDepth = $d0 + 1;
            }
        }         else {
            $downDepth = $d0 + 1;
        }
        $matchValue__17 = $neighbor__1(new Direction_Horizontal(), $crossroad__3);
        if (!is_null($matchValue__17)) {
            if ((function () use ($matchValue__17, $parent) { 
                $p__29 = $matchValue__17[0];
                $nxt__4 = $matchValue__17[1];
                return !Util::equals($nxt__4, $parent);
            })()) {
                $nxt__5 = $matchValue__17[1];
                $p__30 = $matchValue__17[0];
                $matchValue__18 = Map::tryFind($nxt__5, $visited);
                if (is_null($matchValue__18)) {
                    $n__4 = $loop__4($crossroad__3, $nxt__5);
                }                 else {
                    $d__2 = $matchValue__18;
                    $n__4 = $d__2;
                }
                if ($n__4 > $d0) {
                    $cut = new Cons($p__30, $cut);
                }                 else {
                }
                $horizontalDepth = $n__4;
            }             else {
                $horizontalDepth = $d0 + 1;
            }
        }         else {
            $horizontalDepth = $d0 + 1;
        }
        $e2 = Util::min('Util::comparePrimitives', $upDepth, $downDepth);
        $d__3 = Util::min('Util::comparePrimitives', $horizontalDepth, $e2);
        $visited = Map::add($crossroad__3, $d__3, $visited);
        return $d__3;
    };
    $start__4 = new Crossroad($GLOBALS['Shared_002EAxeModule___center'], new CrossroadSide_CLeft());
    $value__2 = $loop__4($start__4, $start__4);
    void ($value__2);
    return Set::ofSeq($cut, [ 'Compare' => function ($_x__49, $_y__50) {     return $_x__49->CompareTo($_y__50);
 }]);
}

#134
function Shared_002EHayBales___hayBaleDestinations($players__1, $hayBales__1) {
    return Set::FSharpSet___op_Subtraction(Set::FSharpSet___op_Subtraction(Set::FSharpSet___op_Subtraction($GLOBALS['Shared_002EPathModule___allInnerPaths'], Set::unionMany(FSharpList::ofSeq(Seq::delay(function ($unitVar__27) use ($players__1) {     return Seq::collect(function ($matchValue__19) {     if ($matchValue__19[1] instanceof CrazyPlayer_Playing) {
        return Seq::singleton((function () use ($matchValue__19) { 
            $elements__4 = Shared_002EFenceModule___fencePaths($matchValue__19[1]->Item->Fence);
            return Set::ofSeq($elements__4, [ 'Compare' => function ($_x__51, $_y__52) {             return $_x__51->CompareTo($_y__52);
 }]);
        })());
    }     else {
        return Seq::singleton(Set::empty([ 'Compare' => function ($_x__53, $_y__54) {         return $_x__53->CompareTo($_y__54);
 }]));
    }
 }, $players__1);
 })), [ 'Compare' => function ($_x__55, $_y__56) {     return $_x__55->CompareTo($_y__56);
 }])), $hayBales__1), Shared_002EHayBales___findCutPaths($hayBales__1));
}

#135
abstract class Blocker implements Union, FSharpUnion {
}

#135
class Blocker_BorderBlocker extends Blocker {
    function __construct() {
    }
    function get_Case() {
        return 'Blocker_BorderBlocker';
    }
    function get_FSharpCase() {
        return 'BorderBlocker';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__97 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__97;
    }
}

#135
class Blocker_FenceBlocker extends Blocker {
    function __construct() {
    }
    function get_Case() {
        return 'Blocker_FenceBlocker';
    }
    function get_FSharpCase() {
        return 'FenceBlocker';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__98 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__98;
    }
}

#135
class Blocker_CutPathBlocker extends Blocker {
    function __construct() {
    }
    function get_Case() {
        return 'Blocker_CutPathBlocker';
    }
    function get_FSharpCase() {
        return 'CutPathBlocker';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__99 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__99;
    }
}

#136
function Shared_002EHayBales___hayBaleDestinationsWithComment($players__2, $hayBales__2) {
    $players__3 = Set::unionMany(FSharpList::ofSeq(Seq::delay(function ($unitVar__28) use ($players__2) {     return Seq::collect(function ($matchValue__20) {     if ($matchValue__20[1] instanceof CrazyPlayer_Playing) {
        return Seq::singleton((function () use ($matchValue__20) { 
            $elements__5 = Shared_002EFenceModule___fencePaths($matchValue__20[1]->Item->Fence);
            return Set::ofSeq($elements__5, [ 'Compare' => function ($_x__57, $_y__58) {             return $_x__57->CompareTo($_y__58);
 }]);
        })());
    }     else {
        return Seq::singleton(Set::empty([ 'Compare' => function ($_x__59, $_y__60) {         return $_x__59->CompareTo($_y__60);
 }]));
    }
 }, $players__2);
 })), [ 'Compare' => function ($_x__61, $_y__62) {     return $_x__61->CompareTo($_y__62);
 }]);
    $cutPaths = Shared_002EHayBales___findCutPaths($hayBales__2);
    return FSharpList::ofSeq(Seq::delay(function ($unitVar__29) use ($cutPaths, $hayBales__2, $players__3) {     return Seq::append(Seq::map(function ($p__35) {     return [ $p__35, new Ok(NULL)];
 }, Set::FSharpSet___op_Subtraction(Set::FSharpSet___op_Subtraction(Set::FSharpSet___op_Subtraction($GLOBALS['Shared_002EPathModule___allInnerPaths'], $players__3), $hayBales__2), $cutPaths)), Seq::delay(function ($unitVar__30) use ($cutPaths, $players__3) {     return Seq::append(Seq::map(function ($p__36) {     return [ $p__36, new ResultError(new Blocker_FenceBlocker())];
 }, $players__3), Seq::delay(function ($unitVar__31) use ($cutPaths) {     return Seq::append(Seq::map(function ($p__37) {     return [ $p__37, new ResultError(new Blocker_CutPathBlocker())];
 }, $cutPaths), Seq::delay(function ($unitVar__32) {     return Seq::map(function ($p__38) {     return [ $p__38, new ResultError(new Blocker_BorderBlocker())];
 }, $GLOBALS['Shared_002EPathModule___boderPaths']);
 }));
 }));
 }));
 }));
}

#137
function Shared_002EHayBales___maxReached($hayBales__3) {
    return Set::count($hayBales__3) >= 8;
}

#138
abstract class MoveBlocker implements Union, FSharpUnion {
}

#138
class MoveBlocker_Tractor extends MoveBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'MoveBlocker_Tractor';
    }
    function get_FSharpCase() {
        return 'Tractor';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__100 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__100;
    }
}

#138
class MoveBlocker_Protection extends MoveBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'MoveBlocker_Protection';
    }
    function get_FSharpCase() {
        return 'Protection';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__101 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__101;
    }
}

#138
class MoveBlocker_PhytosanitaryProducts extends MoveBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'MoveBlocker_PhytosanitaryProducts';
    }
    function get_FSharpCase() {
        return 'PhytosanitaryProducts';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__102 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__102;
    }
}

#138
class MoveBlocker_HayBaleOnPath extends MoveBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'MoveBlocker_HayBaleOnPath';
    }
    function get_FSharpCase() {
        return 'HayBaleOnPath';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__103 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__103;
    }
}

#138
class MoveBlocker_HighVoltageProtection extends MoveBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'MoveBlocker_HighVoltageProtection';
    }
    function get_FSharpCase() {
        return 'HighVoltageProtection';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__104 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__104;
    }
}

#139
abstract class Move implements Union, FSharpUnion {
}

#139
class Move_Move extends Move {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
    function get_Case() {
        return 'Move_Move';
    }
    function get_FSharpCase() {
        return 'Move';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__105 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__105 != 0) {
            return $_cmp__105;
        }        
        $_cmp__106 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__106 != 0) {
            return $_cmp__106;
        }        
        $_cmp__107 = $this->Item2->CompareTo($other->Item2);
        if ($_cmp__107 != 0) {
            return $_cmp__107;
        }        
        return 0;
    }
}

#139
class Move_ImpossibleMove extends Move {
    public $Item1;
    public $Item2;
    public $Item3;
    function __construct($Item1, $Item2, $Item3) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
        $this->Item3 = $Item3;
    }
    function get_Case() {
        return 'Move_ImpossibleMove';
    }
    function get_FSharpCase() {
        return 'ImpossibleMove';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__108 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__108 != 0) {
            return $_cmp__108;
        }        
        $_cmp__109 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__109 != 0) {
            return $_cmp__109;
        }        
        $_cmp__110 = $this->Item2->CompareTo($other->Item2);
        if ($_cmp__110 != 0) {
            return $_cmp__110;
        }        
        $_cmp__111 = $this->Item3->CompareTo($other->Item3);
        if ($_cmp__111 != 0) {
            return $_cmp__111;
        }        
        return 0;
    }
}

#139
class Move_SelectCrossroad extends Move {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Move_SelectCrossroad';
    }
    function get_FSharpCase() {
        return 'SelectCrossroad';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__112 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__112 != 0) {
            return $_cmp__112;
        }        
        $_cmp__113 = $this->Item->CompareTo($other->Item);
        if ($_cmp__113 != 0) {
            return $_cmp__113;
        }        
        return 0;
    }
}

#140
$GLOBALS['Shared_002EMovesModule___empty'] = new Moves(0, 0, false);

#141
function Shared_002EMovesModule___startTurn($fence__2, $bonus__4) {
    $acceleration = Shared_002EFenceModule___givesAcceleration($fence__2);
    return new Moves((function () use ($acceleration, $bonus__4) { 
        if ($acceleration) {
            $baseMoves = 4;
        }         else {
            $baseMoves = 3;
        }
        return $baseMoves + Shared_002EBonusModule___moveCapacityChange($bonus__4);
    })(), 0, $acceleration);
}

#142
function Shared_002EMovesModule___canMove($m) {
    return $m->Done < $m->Capacity;
}

#143
function Shared_002EMovesModule___addCapacity($n__5, $m__1) {
    return new Moves(Util::min('Util::comparePrimitives', ($m__1->Capacity + $n__5), 5), $m__1->Done, $m__1->Acceleration);
}

#144
function Shared_002EMovesModule___doMove($m__2) {
    $Done = $m__2->Done + 1;
    return new Moves($m__2->Capacity, $Done, $m__2->Acceleration);
}

#145
$GLOBALS['Shared_002EDrawPile___cards'] = (function () { 
    $list__9 = new Cons([ new Card_Nitro(new CardPower_One()), 6], new Cons([ new Card_Nitro(new CardPower_Two()), 3], new Cons([ new Card_Rut(), 2], new Cons([ new Card_HayBale(new CardPower_One()), 4], new Cons([ new Card_HayBale(new CardPower_Two()), 3], new Cons([ new Card_Dynamite(), 4], new Cons([ new Card_HighVoltage(), 3], new Cons([ new Card_Watchdog(), 2], new Cons([ new Card_Helicopter(), 6], new Cons([ new Card_Bribe(), 3], FSharpList::get_Nil()))))))))));
    return FSharpList::collect(function ($tupledArg__2) {     return FSharpList::ofSeq(Seq::delay(function ($unitVar__33) use ($tupledArg__2) {     return Seq::collect(function ($matchValue__21) use ($tupledArg__2) {     return Seq::singleton($tupledArg__2[0]);
 }, Seq::rangeNumber(1, 1, $tupledArg__2[1]));
 }));
 }, $list__9);
})();

#146
function Shared_002EDrawPile___shuffle($useGameOver, $cards) {
    $rand = [ ];
    $cardsWithoutGameOver = FSharpList::filter(function ($_arg1__52) {     if ($_arg1__52 instanceof Card_GameOver) {
        return false;
    }     else {
        return true;
    }
 }, $cards);
    $remainingCards = FSharpList::length($cardsWithoutGameOver);
    if ($remainingCards <= 8 ? true : !$useGameOver) {
        return FSharpList::sortBy(function ($_arg1__53) {         return Util::randomNext(0, 2147483647);
 }, $cards, [ 'Compare' => 'Util::comparePrimitives']);
    }     else {
        $gameOverPos = Util::randomNext(1, 8) - 1;
        $list__13 = FSharpList::sortBy(function ($_arg2__7) {         return Util::randomNext(0, 2147483647);
 }, $cardsWithoutGameOver, [ 'Compare' => 'Util::comparePrimitives']);
        $index__1 = $remainingCards - $gameOverPos;
        $patternInput__5 = FSharpList::splitAt($index__1, $list__13);
        return FSharpList::append($patternInput__5[0], FSharpList::append(new Cons(new Card_GameOver(), FSharpList::get_Nil()), $patternInput__5[1]));
    }
}

#147
function Shared_002EDrawPile___remove($cards__1, $pile) {
    $count__1 = Shared_002EHandModule___count($cards__1);
    $count__2 = Util::min('Util::comparePrimitives', FSharpList::length($pile), $count__1);
    return FSharpList::skip($count__2, $pile);
}

#148
function Shared_002EDrawPile___take($count__3, $pile__1) {
    return FSharpList::truncate($count__3, $pile__1);
}

#149
abstract class Command implements Union, FSharpUnion {
}

#149
class Command_Start extends Command {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Command_Start';
    }
    function get_FSharpCase() {
        return 'Start';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__114 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__114 != 0) {
            return $_cmp__114;
        }        
        $_cmp__115 = $this->Item->CompareTo($other->Item);
        if ($_cmp__115 != 0) {
            return $_cmp__115;
        }        
        return 0;
    }
}

#149
class Command_SelectFirstCrossroad extends Command {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Command_SelectFirstCrossroad';
    }
    function get_FSharpCase() {
        return 'SelectFirstCrossroad';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__116 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__116 != 0) {
            return $_cmp__116;
        }        
        $_cmp__117 = $this->Item->CompareTo($other->Item);
        if ($_cmp__117 != 0) {
            return $_cmp__117;
        }        
        return 0;
    }
}

#149
class Command_Move extends Command {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Command_Move';
    }
    function get_FSharpCase() {
        return 'Move';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__118 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__118 != 0) {
            return $_cmp__118;
        }        
        $_cmp__119 = $this->Item->CompareTo($other->Item);
        if ($_cmp__119 != 0) {
            return $_cmp__119;
        }        
        return 0;
    }
}

#149
class Command_PlayCard extends Command {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Command_PlayCard';
    }
    function get_FSharpCase() {
        return 'PlayCard';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__120 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__120 != 0) {
            return $_cmp__120;
        }        
        $_cmp__121 = $this->Item->CompareTo($other->Item);
        if ($_cmp__121 != 0) {
            return $_cmp__121;
        }        
        return 0;
    }
}

#149
class Command_Discard extends Command {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Command_Discard';
    }
    function get_FSharpCase() {
        return 'Discard';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__122 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__122 != 0) {
            return $_cmp__122;
        }        
        $_cmp__123 = $this->Item->CompareTo($other->Item);
        if ($_cmp__123 != 0) {
            return $_cmp__123;
        }        
        return 0;
    }
}

#149
class Command_EndTurn extends Command {
    function __construct() {
    }
    function get_Case() {
        return 'Command_EndTurn';
    }
    function get_FSharpCase() {
        return 'EndTurn';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__124 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__124;
    }
}

#149
class Command_Undo extends Command {
    function __construct() {
    }
    function get_Case() {
        return 'Command_Undo';
    }
    function get_FSharpCase() {
        return 'Undo';
    }
    function get_Tag() {
        return 6;
    }
    function CompareTo($other) {
        $_cmp__125 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__125;
    }
}

#149
class Command_Quit extends Command {
    function __construct() {
    }
    function get_Case() {
        return 'Command_Quit';
    }
    function get_FSharpCase() {
        return 'Quit';
    }
    function get_Tag() {
        return 7;
    }
    function CompareTo($other) {
        $_cmp__126 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__126;
    }
}

#150
class Start {
    public $Parcel;
    function __construct($Parcel) {
        $this->Parcel = $Parcel;
    }
}

#151
class SelectFirstCrossroad {
    public $Crossroad;
    function __construct($Crossroad) {
        $this->Crossroad = $Crossroad;
    }
}

#152
class PlayerMove {
    public $Direction;
    public $Destination;
    function __construct($Direction, $Destination) {
        $this->Direction = $Direction;
        $this->Destination = $Destination;
    }
}

#153
abstract class Event implements Union, FSharpUnion {
}

#153
class Event_FirstCrossroadSelected extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_FirstCrossroadSelected';
    }
    function get_FSharpCase() {
        return 'FirstCrossroadSelected';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__127 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__127 != 0) {
            return $_cmp__127;
        }        
        $_cmp__128 = $this->Item->CompareTo($other->Item);
        if ($_cmp__128 != 0) {
            return $_cmp__128;
        }        
        return 0;
    }
}

#153
class Event_FenceDrawn extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_FenceDrawn';
    }
    function get_FSharpCase() {
        return 'FenceDrawn';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__129 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__129 != 0) {
            return $_cmp__129;
        }        
        $_cmp__130 = $this->Item->CompareTo($other->Item);
        if ($_cmp__130 != 0) {
            return $_cmp__130;
        }        
        return 0;
    }
}

#153
class Event_FenceRemoved extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_FenceRemoved';
    }
    function get_FSharpCase() {
        return 'FenceRemoved';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__131 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__131 != 0) {
            return $_cmp__131;
        }        
        $_cmp__132 = $this->Item->CompareTo($other->Item);
        if ($_cmp__132 != 0) {
            return $_cmp__132;
        }        
        return 0;
    }
}

#153
class Event_FenceLooped extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_FenceLooped';
    }
    function get_FSharpCase() {
        return 'FenceLooped';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__133 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__133 != 0) {
            return $_cmp__133;
        }        
        $_cmp__134 = $this->Item->CompareTo($other->Item);
        if ($_cmp__134 != 0) {
            return $_cmp__134;
        }        
        return 0;
    }
}

#153
class Event_MovedInField extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_MovedInField';
    }
    function get_FSharpCase() {
        return 'MovedInField';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__135 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__135 != 0) {
            return $_cmp__135;
        }        
        $_cmp__136 = $this->Item->CompareTo($other->Item);
        if ($_cmp__136 != 0) {
            return $_cmp__136;
        }        
        return 0;
    }
}

#153
class Event_MovedPowerless extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_MovedPowerless';
    }
    function get_FSharpCase() {
        return 'MovedPowerless';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__137 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__137 != 0) {
            return $_cmp__137;
        }        
        $_cmp__138 = $this->Item->CompareTo($other->Item);
        if ($_cmp__138 != 0) {
            return $_cmp__138;
        }        
        return 0;
    }
}

#153
class Event_FenceReduced extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_FenceReduced';
    }
    function get_FSharpCase() {
        return 'FenceReduced';
    }
    function get_Tag() {
        return 6;
    }
    function CompareTo($other) {
        $_cmp__139 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__139 != 0) {
            return $_cmp__139;
        }        
        $_cmp__140 = $this->Item->CompareTo($other->Item);
        if ($_cmp__140 != 0) {
            return $_cmp__140;
        }        
        return 0;
    }
}

#153
class Event_Annexed extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_Annexed';
    }
    function get_FSharpCase() {
        return 'Annexed';
    }
    function get_Tag() {
        return 7;
    }
    function CompareTo($other) {
        $_cmp__141 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__141 != 0) {
            return $_cmp__141;
        }        
        $_cmp__142 = $this->Item->CompareTo($other->Item);
        if ($_cmp__142 != 0) {
            return $_cmp__142;
        }        
        return 0;
    }
}

#153
class Event_CutFence extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_CutFence';
    }
    function get_FSharpCase() {
        return 'CutFence';
    }
    function get_Tag() {
        return 8;
    }
    function CompareTo($other) {
        $_cmp__143 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__143 != 0) {
            return $_cmp__143;
        }        
        $_cmp__144 = $this->Item->CompareTo($other->Item);
        if ($_cmp__144 != 0) {
            return $_cmp__144;
        }        
        return 0;
    }
}

#153
class Event_PoweredUp extends Event {
    function __construct() {
    }
    function get_Case() {
        return 'Event_PoweredUp';
    }
    function get_FSharpCase() {
        return 'PoweredUp';
    }
    function get_Tag() {
        return 9;
    }
    function CompareTo($other) {
        $_cmp__145 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__145;
    }
}

#153
class Event_CardPlayed extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_CardPlayed';
    }
    function get_FSharpCase() {
        return 'CardPlayed';
    }
    function get_Tag() {
        return 10;
    }
    function CompareTo($other) {
        $_cmp__146 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__146 != 0) {
            return $_cmp__146;
        }        
        $_cmp__147 = $this->Item->CompareTo($other->Item);
        if ($_cmp__147 != 0) {
            return $_cmp__147;
        }        
        return 0;
    }
}

#153
class Event_SpedUp extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_SpedUp';
    }
    function get_FSharpCase() {
        return 'SpedUp';
    }
    function get_Tag() {
        return 11;
    }
    function CompareTo($other) {
        $_cmp__148 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__148 != 0) {
            return $_cmp__148;
        }        
        $_cmp__149 = $this->Item->CompareTo($other->Item);
        if ($_cmp__149 != 0) {
            return $_cmp__149;
        }        
        return 0;
    }
}

#153
class Event_Rutted extends Event {
    function __construct() {
    }
    function get_Case() {
        return 'Event_Rutted';
    }
    function get_FSharpCase() {
        return 'Rutted';
    }
    function get_Tag() {
        return 12;
    }
    function CompareTo($other) {
        $_cmp__150 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__150;
    }
}

#153
class Event_HighVoltaged extends Event {
    function __construct() {
    }
    function get_Case() {
        return 'Event_HighVoltaged';
    }
    function get_FSharpCase() {
        return 'HighVoltaged';
    }
    function get_Tag() {
        return 13;
    }
    function CompareTo($other) {
        $_cmp__151 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__151;
    }
}

#153
class Event_BonusDiscarded extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_BonusDiscarded';
    }
    function get_FSharpCase() {
        return 'BonusDiscarded';
    }
    function get_Tag() {
        return 14;
    }
    function CompareTo($other) {
        $_cmp__152 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__152 != 0) {
            return $_cmp__152;
        }        
        $_cmp__153 = $this->Item->CompareTo($other->Item);
        if ($_cmp__153 != 0) {
            return $_cmp__153;
        }        
        return 0;
    }
}

#153
class Event_CardDiscarded extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_CardDiscarded';
    }
    function get_FSharpCase() {
        return 'CardDiscarded';
    }
    function get_Tag() {
        return 15;
    }
    function CompareTo($other) {
        $_cmp__154 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__154 != 0) {
            return $_cmp__154;
        }        
        $_cmp__155 = $this->Item->CompareTo($other->Item);
        if ($_cmp__155 != 0) {
            return $_cmp__155;
        }        
        return 0;
    }
}

#153
class Event_Watched extends Event {
    function __construct() {
    }
    function get_Case() {
        return 'Event_Watched';
    }
    function get_FSharpCase() {
        return 'Watched';
    }
    function get_Tag() {
        return 16;
    }
    function CompareTo($other) {
        $_cmp__156 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__156;
    }
}

#153
class Event_Heliported extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_Heliported';
    }
    function get_FSharpCase() {
        return 'Heliported';
    }
    function get_Tag() {
        return 17;
    }
    function CompareTo($other) {
        $_cmp__157 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__157 != 0) {
            return $_cmp__157;
        }        
        $_cmp__158 = $this->Item->CompareTo($other->Item);
        if ($_cmp__158 != 0) {
            return $_cmp__158;
        }        
        return 0;
    }
}

#153
class Event_Bribed extends Event {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'Event_Bribed';
    }
    function get_FSharpCase() {
        return 'Bribed';
    }
    function get_Tag() {
        return 18;
    }
    function CompareTo($other) {
        $_cmp__159 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__159 != 0) {
            return $_cmp__159;
        }        
        $_cmp__160 = $this->Item->CompareTo($other->Item);
        if ($_cmp__160 != 0) {
            return $_cmp__160;
        }        
        return 0;
    }
}

#153
class Event_Eliminated extends Event {
    function __construct() {
    }
    function get_Case() {
        return 'Event_Eliminated';
    }
    function get_FSharpCase() {
        return 'Eliminated';
    }
    function get_Tag() {
        return 19;
    }
    function CompareTo($other) {
        $_cmp__161 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__161;
    }
}

#153
class Event_Undone extends Event {
    function __construct() {
    }
    function get_Case() {
        return 'Event_Undone';
    }
    function get_FSharpCase() {
        return 'Undone';
    }
    function get_Tag() {
        return 20;
    }
    function CompareTo($other) {
        $_cmp__162 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__162;
    }
}

#153
class Event_PlayerQuit extends Event {
    function __construct() {
    }
    function get_Case() {
        return 'Event_PlayerQuit';
    }
    function get_FSharpCase() {
        return 'PlayerQuit';
    }
    function get_Tag() {
        return 21;
    }
    function CompareTo($other) {
        $_cmp__163 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__163;
    }
}

#154
class PlayerStarted {
    public $Parcel;
    function __construct($Parcel) {
        $this->Parcel = $Parcel;
    }
}

#155
class FirstCrossroadSelected {
    public $Crossroad;
    function __construct($Crossroad) {
        $this->Crossroad = $Crossroad;
    }
}

#156
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

#157
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

#158
class Annexed {
    public $NewField;
    public $LostFields;
    public $FreeBarns;
    public $OccupiedBarns;
    public $FenceLength;
    function __construct($NewField, $LostFields, $FreeBarns, $OccupiedBarns, $FenceLength) {
        $this->NewField = $NewField;
        $this->LostFields = $LostFields;
        $this->FreeBarns = $FreeBarns;
        $this->OccupiedBarns = $OccupiedBarns;
        $this->FenceLength = $FenceLength;
    }
}

#159
class CutFence {
    public $Player;
    function __construct($Player) {
        $this->Player = $Player;
    }
}

#160
class FenceReduced {
    public $NewLength;
    function __construct($NewLength) {
        $this->NewLength = $NewLength;
    }
}

#161
class SpedUp {
    public $Speed;
    function __construct($Speed) {
        $this->Speed = $Speed;
    }
}

#162
class Bribed {
    public $Parcel;
    public $Victim;
    function __construct($Parcel, $Victim) {
        $this->Parcel = $Parcel;
        $this->Victim = $Victim;
    }
}

#163
function Shared_002EPlayer___isCut($tractor__4, $player__1) {
    if ($player__1 instanceof CrazyPlayer_Playing) {
        if (!$player__1->Item->Bonus->HighVoltage) {
            $source__2 = Shared_002EFenceModule___fenceCrossroads($player__1->Item->Tractor, $player__1->Item->Fence);
            return Seq::contains($tractor__4, $source__2);
        }         else {
            return false;
        }
    }     else {
        return false;
    }
}

#164
function Shared_002EPlayer___decideCut($otherPlayers, $tractor__5) {
    $list__17 = FSharpList::filter(function ($_arg__71) use ($tractor__5) { 
        $player__3 = $_arg__71[1];
        return Shared_002EPlayer___isCut($tractor__5, $player__3);
    }, $otherPlayers);
    return FSharpList::map(function ($tupledArg__3) {     return new Event_CutFence(new CutFence($tupledArg__3[0]));
 }, $list__17);
}

#165
function Shared_002EPlayer___nearestContact($field__7, $_arg1__55, $pos__10) {
    $loop__5 = function ($pos__11, $fence__4, $len) use ($field__7, &$loop__5) {     if (Shared_002ECrossroadModule___isInField($field__7, $pos__11)) {
        return [ $pos__11, $fence__4, $len];
    }     else {
        if ($fence__4 instanceof Cons) {
            return $loop__5(Shared_002ECrossroadModule___neighbor(Shared_002EDirectionModule___rev($fence__4->value[1]), $pos__11), $fence__4->next, $len + 1);
        }         else {
            return NULL;
        }
    }
 };
    return $loop__5($pos__10, $_arg1__55->paths, 0);
}

#166
function Shared_002EPlayer___fullAnnexation($field__8, $fence__5, $tractor__6) {
    $mainField = Shared_002EFieldModule___principalField($field__8, $fence__5, $tractor__6);
    $start__5 = Shared_002EFenceModule___start($tractor__6, $fence__5);
    if (Shared_002ECrossroadModule___isInField($mainField, $start__5)) {
        $matchValue__22 = Shared_002EPlayer___nearestContact($mainField, $fence__5, $tractor__6);
        if (!is_null($matchValue__22)) {
            if ((function () use ($matchValue__22, $start__5) { 
                $pos__12 = $matchValue__22[0];
                $paths__19 = $matchValue__22[1];
                $len__1 = $matchValue__22[2];
                return !Util::equals($pos__12, $start__5);
            })()) {
                $len__2 = $matchValue__22[2];
                $paths__20 = $matchValue__22[1];
                $pos__13 = $matchValue__22[0];
                $border__1 = Shared_002EFieldModule___borderBetween($start__5, $pos__13, $mainField);
                $fullBorder = FSharpList::append($paths__20, $border__1);
                return [ Shared_002EField___op_Subtraction__Z24735800(Shared_002EFieldModule___fill($fullBorder), $mainField), $len__2];
            }             else {
                return NULL;
            }
        }         else {
            return NULL;
        }
    }     else {
        return NULL;
    }
}

#167
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

#168
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

#169
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

#170
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

#171
function Shared_002EPlayer___fence($player__8) {
    switch (get_class($player__8))
    {
        case 'CrazyPlayer_Starting':
            $_target__164 = 1;
            break;
        case 'CrazyPlayer_Ko':
            $_target__164 = 1;
            break;
        default:
            $_target__164 = 0;
            break;
    }
    switch ($_target__164)
    {
        case 0:
            return $player__8->Item->Fence;
        case 1:
            return $GLOBALS['Shared_002EFenceModule___empty'];
    }
}

#172
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

#173
function Shared_002EPlayer___isKo($player__10) {
    if ($player__10 instanceof CrazyPlayer_Ko) {
        return true;
    }     else {
        return false;
    }
}

#174
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

#175
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

#176
function Shared_002EPlayer___principalFieldSize($player__13) {
    switch (get_class($player__13))
    {
        case 'CrazyPlayer_Starting':
            return 1;
        case 'CrazyPlayer_Ko':
            return 0;
        default:
            $arg00_0040__6 = Shared_002EFieldModule___principalField($player__13->Item->Field, $player__13->Item->Fence, $player__13->Item->Tractor);
            return Shared_002EFieldModule___size($arg00_0040__6);
    }
}

#177
function Shared_002EPlayer___watchedField($player__14) {
    if ($player__14 instanceof CrazyPlayer_Playing) {
        if ($player__14->Item->Bonus->Watched) {
            $field__9 = $player__14->Item->Field;
            return $field__9;
        }         else {
            return $GLOBALS['Shared_002EFieldModule___empty'];
        }
    }     else {
        return $GLOBALS['Shared_002EFieldModule___empty'];
    }
}

#178
function Shared_002EPlayer___canUseHelicopter($player__15) {
    if ($player__15 instanceof CrazyPlayer_Playing) {
        return Shared_002EFenceModule___isEmpty($player__15->Item->Fence);
    }     else {
        return false;
    }
}

#179
function Shared_002EPlayer___decide($otherPlayers__1, $barns__3, $hayBales__4, $bribeParcels, $command, $player__16) {
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
                }                 else {
                    switch (get_class($player__17->Power))
                    {
                        case 'Power_PowerDown':
                            return FSharpList::ofSeq(Seq::delay(function ($unitVar__36) use ($cmd__1, $nextPath__1, $nextPos__1, $otherPlayers__1, $player__17) {                             return Seq::append(Seq::singleton(new Event_MovedPowerless(new Moved($cmd__1->Direction, $nextPath__1, $nextPos__1))), Seq::delay(function ($unitVar__37) use ($nextPos__1, $otherPlayers__1, $player__17) {                             if (Shared_002ECrossroadModule___isInField($player__17->Field, $nextPos__1)) {
                                return Seq::append(Seq::singleton(new Event_PoweredUp()), Seq::delay(function ($unitVar__38) use ($nextPos__1, $otherPlayers__1) {                                 return Shared_002EPlayer___decideCut($otherPlayers__1, $nextPos__1);
 }));
                            }                             else {
                                return Seq::empty();
                            }
 }));
 }));
                        default:
                            $activePatternResult1216 = Shared_002EFenceOps____007CRwd_007C__007C($nextPath__1, $player__17->Fence);
                            if (!is_null($activePatternResult1216)) {
                                return new Cons(new Event_FenceRemoved(new Moved($cmd__1->Direction, $nextPath__1, $nextPos__1)), FSharpList::get_Nil());
                            }                             else {
                                $matchValue__26 = Shared_002EFenceModule___findLoop($cmd__1->Direction, $player__17->Tractor, $player__17->Fence);
                                if ($matchValue__26->paths instanceof Nil) {
                                    $endInField = Shared_002ECrossroadModule___isInField($player__17->Field, $nextPos__1);
                                    $pathInField = Shared_002EFieldModule___pathInFieldOrBorder($nextPath__1, $player__17->Field);
                                    if ($endInField) {
                                        $nextFence = Shared_002EFenceModule___add($nextPath__1, $cmd__1->Direction, $player__17->Fence);
                                        if ($pathInField ? Shared_002EFenceModule___length($nextFence) === 1 : false) {
                                            $inFallow = false;
                                        }                                         else {
                                            $fenceStart = Shared_002EFenceModule___start($nextPos__1, $nextFence);
                                            $inFallow = !Shared_002EFieldModule___isInSameField($fenceStart, $nextPos__1, $player__17->Field);
                                        }
                                    }                                     else {
                                        $inFallow = false;
                                    }
                                    return FSharpList::ofSeq(Seq::delay(function ($unitVar__34) use ($cmd__1, $inFallow, $nextPath__1, $nextPos__1, $otherPlayers__1, $pathInField) {                                     return Seq::append(($pathInField ? !$inFallow : false) ? Seq::singleton(new Event_MovedInField(new Moved($cmd__1->Direction, $nextPath__1, $nextPos__1))) : Seq::singleton(new Event_FenceDrawn(new Moved($cmd__1->Direction, $nextPath__1, $nextPos__1))), Seq::delay(function ($unitVar__35) use ($nextPos__1, $otherPlayers__1) {                                     return Shared_002EPlayer___decideCut($otherPlayers__1, $nextPos__1);
 }));
 }));
                                }                                 else {
                                    return new Cons(new Event_FenceLooped(new FenceLooped($cmd__1->Direction, $matchValue__26, $nextPos__1)), FSharpList::get_Nil());
                                }
                            }
                    }
                }
            case 'Command_PlayCard':
                $card__3 = $command->Item;
                $p__55 = $player__16->Item;
                $c__3 = Shared_002ECardModule___ofPlayCard($card__3);
                if (Shared_002EHandModule___contains($c__3, $p__55->Hand)) {
                    switch (get_class($card__3))
                    {
                        case 'PlayCard_PlayHighVoltage':
                            return new Cons(new Event_CardPlayed($card__3), new Cons(new Event_HighVoltaged(), FSharpList::get_Nil()));
                        case 'PlayCard_PlayWatchdog':
                            return new Cons(new Event_CardPlayed($card__3), new Cons(new Event_Watched(), FSharpList::get_Nil()));
                        case 'PlayCard_PlayRut':
                            return new Cons(new Event_CardPlayed($card__3), FSharpList::get_Nil());
                        case 'PlayCard_PlayHelicopter':
                            $othersCrossroads = Set::ofSeq(FSharpList::ofSeq(Seq::delay(function ($unitVar__39) use ($otherPlayers__1) {                             return Seq::collect(function ($matchValue__27) {                             if ($matchValue__27[1] instanceof CrazyPlayer_Playing) {
                                return Shared_002EFenceModule___fenceCrossroads($matchValue__27[1]->Item->Tractor, $matchValue__27[1]->Item->Fence);
                            }                             else {
                                return Seq::empty();
                            }
 }, $otherPlayers__1);
 })), [ 'Compare' => function ($_x__72, $_y__73) {                             return $_x__72->CompareTo($_y__73);
 }]);
                            if ((Shared_002EPlayer___canUseHelicopter($player__16) ? Shared_002ECrossroadModule___isInField(Shared_002EPlayer___field($player__16), $card__3->destination) : false) ? !Set::contains($card__3->destination, $othersCrossroads) : false) {
                                return FSharpList::ofSeq(Seq::delay(function ($unitVar__40) use ($card__3, $p__55) {                                 return Seq::append(Seq::singleton(new Event_CardPlayed($card__3)), Seq::delay(function ($unitVar__41) use ($card__3, $p__55) {                                 return Seq::append(Seq::singleton(new Event_Heliported($card__3->destination)), Seq::delay(function ($unitVar__42) use ($card__3, $p__55) {                                 if (Util::equals($p__55->Power, new Power_PowerDown()) ? Shared_002ECrossroadModule___isInField($p__55->Field, $card__3->destination) : false) {
                                    return Seq::singleton(new Event_PoweredUp());
                                }                                 else {
                                    return Seq::empty();
                                }
 }));
 }));
 }));
                            }                             else {
                                return FSharpList::get_Nil();
                            }
                        case 'PlayCard_PlayHayBale':
                            if (Util::max('Util::comparePrimitives', Set::count($hayBales__4) + FSharpList::length($card__3->path) - 8, 0) === FSharpList::length($card__3->moved)) {
                                if (Set::isSubset(Set::ofSeq($card__3->moved, [ 'Compare' => function ($_x__76, $_y__77) {                                 return $_x__76->CompareTo($_y__77);
 }]), $hayBales__4)) {
                                    $dests = Shared_002EHayBales___hayBaleDestinations(new Cons([ '', $player__16], $otherPlayers__1), $hayBales__4);
                                    if (FSharpList::forAll(function ($b) use ($dests) {                                     return Set::contains($b, $dests);
 }, $card__3->path)) {
                                        return new Cons(new Event_CardPlayed($card__3), new Cons(new Event_BonusDiscarded(Shared_002ECardModule___ofPlayCard($card__3)), FSharpList::get_Nil()));
                                    }                                     else {
                                        return FSharpList::get_Nil();
                                    }
                                }                                 else {
                                    return FSharpList::get_Nil();
                                }
                            }                             else {
                                return FSharpList::get_Nil();
                            }
                        case 'PlayCard_PlayDynamite':
                            if (Set::contains($card__3->path, $hayBales__4)) {
                                return new Cons(new Event_CardPlayed($card__3), new Cons(new Event_BonusDiscarded(Shared_002ECardModule___ofPlayCard($card__3)), FSharpList::get_Nil()));
                            }                             else {
                                return FSharpList::get_Nil();
                            }
                        case 'PlayCard_PlayBribe':
                            $matchValue__28 = $bribeParcels(NULL);
                            if ($matchValue__28 instanceof Ok) {
                                if (Shared_002EFieldModule___containsParcel($card__3->parcel, $matchValue__28->ResultValue)) {
                                    return FSharpList::ofSeq(Seq::delay(function ($unitVar__43) use ($card__3, $otherPlayers__1) {                                     return Seq::append(Seq::singleton(new Event_CardPlayed($card__3)), Seq::delay(function ($unitVar__44) use ($card__3, $otherPlayers__1) {                                     return Seq::append(Seq::collect(function ($matchValue__29) use ($card__3) {                                     if ((function () use ($card__3, $matchValue__29) { 
                                        $arg10_0040 = Shared_002EPlayer___field($matchValue__29[1]);
                                        return Shared_002EFieldModule___containsParcel($card__3->parcel, $arg10_0040);
                                    })()) {
                                        return Seq::singleton(new Event_Bribed(new Bribed($card__3->parcel, $matchValue__29[0])));
                                    }                                     else {
                                        return Seq::empty();
                                    }
 }, $otherPlayers__1), Seq::delay(function ($unitVar__45) use ($card__3) {                                     return Seq::singleton(new Event_BonusDiscarded(Shared_002ECardModule___ofPlayCard($card__3)));
 }));
 }));
 }));
                                }                                 else {
                                    return FSharpList::get_Nil();
                                }
                            }                             else {
                                return FSharpList::get_Nil();
                            }
                        case 'PlayCard_PlayGameOver':
                            return FSharpList::get_Nil();
                        default:
                            return new Cons(new Event_CardPlayed($card__3), new Cons(new Event_SpedUp(new SpedUp(($card__3->power instanceof CardPower_Two ? 2 : 1))), FSharpList::get_Nil()));
                    }
                }                 else {
                    return FSharpList::get_Nil();
                }
            case 'Command_Discard':
                $card__4 = $command->Item;
                $p__58 = $player__16->Item;
                if (Shared_002EHandModule___contains($card__4, $p__58->Hand)) {
                    return new Cons(new Event_CardDiscarded($card__4), FSharpList::get_Nil());
                }                 else {
                    return FSharpList::get_Nil();
                }
            case 'Command_Quit':
                return new Cons(new Event_PlayerQuit(), FSharpList::get_Nil());
            default:
                throw new Exception('Invalid operation');
        }
    }     else {
        if ($player__16 instanceof CrazyPlayer_Ko) {
            switch (get_class($command))
            {
                case 'Command_Quit':
                    return FSharpList::get_Nil();
                default:
                    throw new Exception('Invalid operation');
            }
        }         else {
            switch (get_class($command))
            {
                case 'Command_SelectFirstCrossroad':
                    $cmd = $command->Item;
                    return new Cons(new Event_FirstCrossroadSelected(new FirstCrossroadSelected($cmd->Crossroad)), FSharpList::get_Nil());
                case 'Command_Quit':
                    return new Cons(new Event_PlayerQuit(), FSharpList::get_Nil());
                default:
                    throw new Exception('Invalid operation');
            }
        }
    }
}

#180
function Shared_002EPlayer___evolve($player__19, $event) {
    if ($player__19 instanceof CrazyPlayer_Starting) {
        switch (get_class($event))
        {
            case 'Event_FirstCrossroadSelected':
                $e__1 = $event->Item;
                $p__59 = $player__19->Item;
                return new CrazyPlayer_Playing(new Playing($p__59->Color, $e__1->Crossroad, $GLOBALS['Shared_002EFenceModule___empty'], Shared_002EFieldModule___create($p__59->Parcel), new Power_PowerUp(), Shared_002EMovesModule___startTurn($GLOBALS['Shared_002EFenceModule___empty'], $p__59->Bonus), $p__59->Hand, $p__59->Bonus));
            case 'Event_PlayerQuit':
                $player__38 = $player__19->Item;
                return new CrazyPlayer_Ko($player__38->Color);
            default:
                return $player__19;
        }
    }     else {
        if ($player__19 instanceof CrazyPlayer_Playing) {
            switch (get_class($event))
            {
                case 'Event_FenceDrawn':
                    $e__2 = $event->Item;
                    $player__20 = $player__19->Item;
                    $_target__165 = 1;
                    break;
                case 'Event_FenceRemoved':
                    $e__3 = $event->Item;
                    $player__21 = $player__19->Item;
                    $_target__165 = 2;
                    break;
                case 'Event_FenceLooped':
                    $e__4 = $event->Item;
                    $player__22 = $player__19->Item;
                    $_target__165 = 3;
                    break;
                case 'Event_MovedInField':
                    $e__5 = $event->Item;
                    $player__23 = $player__19->Item;
                    $_target__165 = 4;
                    break;
                case 'Event_MovedPowerless':
                    $e__6 = $event->Item;
                    $player__24 = $player__19->Item;
                    $_target__165 = 5;
                    break;
                case 'Event_FenceReduced':
                    $e__7 = $event->Item;
                    $player__25 = $player__19->Item;
                    $_target__165 = 6;
                    break;
                case 'Event_PoweredUp':
                    $player__26 = $player__19->Item;
                    $_target__165 = 7;
                    break;
                case 'Event_Annexed':
                    $e__8 = $event->Item;
                    $player__27 = $player__19->Item;
                    $_target__165 = 8;
                    break;
                case 'Event_HighVoltaged':
                    $player__28 = $player__19->Item;
                    $_target__165 = 9;
                    break;
                case 'Event_Watched':
                    $player__29 = $player__19->Item;
                    $_target__165 = 10;
                    break;
                case 'Event_Rutted':
                    $player__30 = $player__19->Item;
                    $_target__165 = 11;
                    break;
                case 'Event_SpedUp':
                    $e__9 = $event->Item;
                    $player__31 = $player__19->Item;
                    $_target__165 = 12;
                    break;
                case 'Event_Heliported':
                    $e__10 = $event->Item;
                    $player__32 = $player__19->Item;
                    $_target__165 = 13;
                    break;
                case 'Event_Bribed':
                    $p__60 = $event->Item;
                    $player__33 = $player__19->Item;
                    $_target__165 = 14;
                    break;
                case 'Event_CardPlayed':
                    $card__5 = $event->Item;
                    $player__34 = $player__19->Item;
                    $_target__165 = 15;
                    break;
                case 'Event_BonusDiscarded':
                    $e__11 = $event->Item;
                    $player__35 = $player__19->Item;
                    $_target__165 = 16;
                    break;
                case 'Event_CardDiscarded':
                    $card__7 = $event->Item;
                    $player__36 = $player__19->Item;
                    $_target__165 = 17;
                    break;
                case 'Event_Eliminated':
                    $player__37 = $player__19->Item;
                    $_target__165 = 18;
                    break;
                case 'Event_PlayerQuit':
                    $player__37 = $player__19->Item;
                    $_target__165 = 18;
                    break;
                default:
                    $_target__165 = 20;
                    break;
            }
            switch ($_target__165)
            {
                case 0:
                case 1:
                    return new CrazyPlayer_Playing((function () use ($e__2, $player__20) { 
                        $Fence = Shared_002EFenceModule___add($e__2->Path, $e__2->Move, $player__20->Fence);
                        $Moves__1 = Shared_002EMovesModule___doMove($player__20->Moves);
                        return new Playing($player__20->Color, $e__2->Crossroad, $Fence, $player__20->Field, $player__20->Power, $Moves__1, $player__20->Hand, $player__20->Bonus);
                    })());
                case 2:
                    return new CrazyPlayer_Playing((function () use ($e__3, $player__21) { 
                        $Fence__1 = Shared_002EFenceModule___tail($player__21->Fence);
                        $Moves__2 = Shared_002EMovesModule___doMove($player__21->Moves);
                        return new Playing($player__21->Color, $e__3->Crossroad, $Fence__1, $player__21->Field, $player__21->Power, $Moves__2, $player__21->Hand, $player__21->Bonus);
                    })());
                case 3:
                    return new CrazyPlayer_Playing((function () use ($e__4, $player__22) { 
                        $Fence__2 = Shared_002EFenceModule___remove($e__4->Loop, $player__22->Fence);
                        $Moves__3 = Shared_002EMovesModule___doMove($player__22->Moves);
                        return new Playing($player__22->Color, $e__4->Crossroad, $Fence__2, $player__22->Field, $player__22->Power, $Moves__3, $player__22->Hand, $player__22->Bonus);
                    })());
                case 4:
                    return new CrazyPlayer_Playing((function () use ($e__5, $player__23) { 
                        $Moves__4 = Shared_002EMovesModule___doMove($player__23->Moves);
                        return new Playing($player__23->Color, $e__5->Crossroad, $GLOBALS['Shared_002EFenceModule___empty'], $player__23->Field, $player__23->Power, $Moves__4, $player__23->Hand, $player__23->Bonus);
                    })());
                case 5:
                    return new CrazyPlayer_Playing((function () use ($e__6, $player__24) { 
                        $Moves__5 = Shared_002EMovesModule___doMove($player__24->Moves);
                        return new Playing($player__24->Color, $e__6->Crossroad, $player__24->Fence, $player__24->Field, $player__24->Power, $Moves__5, $player__24->Hand, $player__24->Bonus);
                    })());
                case 6:
                    return new CrazyPlayer_Playing((function () use ($e__7, $player__25) { 
                        $Fence__3 = Shared_002EFenceModule___truncate($e__7->NewLength, $player__25->Fence);
                        return new Playing($player__25->Color, $player__25->Tractor, $Fence__3, $player__25->Field, $player__25->Power, $player__25->Moves, $player__25->Hand, $player__25->Bonus);
                    })());
                case 7:
                    return new CrazyPlayer_Playing((function () use ($player__26) { 
                        $Power = new Power_PowerUp();
                        return new Playing($player__26->Color, $player__26->Tractor, $player__26->Fence, $player__26->Field, $Power, $player__26->Moves, $player__26->Hand, $player__26->Bonus);
                    })());
                case 8:
                    return new CrazyPlayer_Playing((function () use ($e__8, $player__27) { 
                        $Fence__4 = Shared_002EFenceModule___truncate($e__8->FenceLength, $player__27->Fence);
                        $Field = Shared_002EField___op_Addition__Z24735800($player__27->Field, Shared_002EFieldModule___ofParcels($e__8->NewField));
                        return new Playing($player__27->Color, $player__27->Tractor, $Fence__4, $Field, $player__27->Power, $player__27->Moves, $player__27->Hand, $player__27->Bonus);
                    })());
                case 9:
                    return new CrazyPlayer_Playing((function () use ($player__28) { 
                        $Bonus = new Bonus($player__28->Bonus->NitroOne, $player__28->Bonus->NitroTwo, $player__28->Bonus->Watched, true, $player__28->Bonus->Rutted, $player__28->Bonus->Heliported);
                        return new Playing($player__28->Color, $player__28->Tractor, $player__28->Fence, $player__28->Field, $player__28->Power, $player__28->Moves, $player__28->Hand, $Bonus);
                    })());
                case 10:
                    return new CrazyPlayer_Playing((function () use ($player__29) { 
                        $Bonus__1 = new Bonus($player__29->Bonus->NitroOne, $player__29->Bonus->NitroTwo, true, $player__29->Bonus->HighVoltage, $player__29->Bonus->Rutted, $player__29->Bonus->Heliported);
                        return new Playing($player__29->Color, $player__29->Tractor, $player__29->Fence, $player__29->Field, $player__29->Power, $player__29->Moves, $player__29->Hand, $Bonus__1);
                    })());
                case 11:
                    return new CrazyPlayer_Playing((function () use ($player__30) { 
                        $Rutted__1 = $player__30->Bonus->Rutted + 1;
                        $Bonus__2 = new Bonus($player__30->Bonus->NitroOne, $player__30->Bonus->NitroTwo, $player__30->Bonus->Watched, $player__30->Bonus->HighVoltage, $Rutted__1, $player__30->Bonus->Heliported);
                        return new Playing($player__30->Color, $player__30->Tractor, $player__30->Fence, $player__30->Field, $player__30->Power, $player__30->Moves, $player__30->Hand, $Bonus__2);
                    })());
                case 12:
                    return new CrazyPlayer_Playing((function () use ($e__9, $player__31) { 
                        $Moves__6 = Shared_002EMovesModule___addCapacity($e__9->Speed, $player__31->Moves);
                        return new Playing($player__31->Color, $player__31->Tractor, $player__31->Fence, $player__31->Field, $player__31->Power, $Moves__6, $player__31->Hand, $player__31->Bonus);
                    })());
                case 13:
                    return new CrazyPlayer_Playing((function () use ($e__10, $player__32) { 
                        $Heliported__1 = $player__32->Bonus->Heliported + 1;
                        $Bonus__3 = new Bonus($player__32->Bonus->NitroOne, $player__32->Bonus->NitroTwo, $player__32->Bonus->Watched, $player__32->Bonus->HighVoltage, $player__32->Bonus->Rutted, $Heliported__1);
                        return new Playing($player__32->Color, $e__10, $GLOBALS['Shared_002EFenceModule___empty'], $player__32->Field, $player__32->Power, $player__32->Moves, $player__32->Hand, $Bonus__3);
                    })());
                case 14:
                    return new CrazyPlayer_Playing((function () use ($p__60, $player__33) { 
                        $Field__1 = Shared_002EField___op_Addition__Z24735800($player__33->Field, Shared_002EFieldModule___ofParcels(new Cons($p__60->Parcel, FSharpList::get_Nil())));
                        return new Playing($player__33->Color, $player__33->Tractor, $player__33->Fence, $Field__1, $player__33->Power, $player__33->Moves, $player__33->Hand, $player__33->Bonus);
                    })());
                case 15:
                    return new CrazyPlayer_Playing((function () use ($card__5, $player__34) { 
                        $card__6 = Shared_002ECardModule___ofPlayCard($card__5);
                        $Hand__2 = Shared_002EHandModule___remove($card__6, $player__34->Hand);
                        if ($card__5 instanceof PlayCard_PlayNitro) {
                            switch (get_class($card__5->power))
                            {
                                case 'CardPower_Two':
                                    $NitroTwo__1 = $player__34->Bonus->NitroTwo + 1;
                                    $Bonus__4 = new Bonus($player__34->Bonus->NitroOne, $NitroTwo__1, $player__34->Bonus->Watched, $player__34->Bonus->HighVoltage, $player__34->Bonus->Rutted, $player__34->Bonus->Heliported);
                                    break;
                                default:
                                    $Bonus__4 = new Bonus(($player__34->Bonus->NitroOne + 1), $player__34->Bonus->NitroTwo, $player__34->Bonus->Watched, $player__34->Bonus->HighVoltage, $player__34->Bonus->Rutted, $player__34->Bonus->Heliported);
                                    break;
                            }
                        }                         else {
                            $Bonus__4 = $player__34->Bonus;
                        }
                        return new Playing($player__34->Color, $player__34->Tractor, $player__34->Fence, $player__34->Field, $player__34->Power, $player__34->Moves, $Hand__2, $Bonus__4);
                    })());
                case 16:
                    return new CrazyPlayer_Playing((function () use ($e__11, $player__35) { 
                        $Bonus__5 = Shared_002EBonusModule___discard($e__11, $player__35->Bonus);
                        return new Playing($player__35->Color, $player__35->Tractor, $player__35->Fence, $player__35->Field, $player__35->Power, $player__35->Moves, $player__35->Hand, $Bonus__5);
                    })());
                case 17:
                    return new CrazyPlayer_Playing((function () use ($card__7, $player__36) { 
                        $Hand__3 = Shared_002EHandModule___remove($card__7, $player__36->Hand);
                        return new Playing($player__36->Color, $player__36->Tractor, $player__36->Fence, $player__36->Field, $player__36->Power, $player__36->Moves, $Hand__3, $player__36->Bonus);
                    })());
                case 18:
                    return new CrazyPlayer_Ko($player__37->Color);
                case 19:
                case 20:
                    return $player__19;
            }
        }         else {
            return $player__19;
        }
    }
}

#181
function Shared_002EPlayer___exec($otherPlayers__2, $barns__4, $haybales, $cmd__2, $state) {
    $list__19 = Shared_002EPlayer___decide($otherPlayers__2, $barns__4, $haybales, function ($unitVar0) {     return new Ok($GLOBALS['Shared_002EFieldModule___empty']);
 }, $cmd__2, $state);
    return FSharpList::fold('Shared_002EPlayer___evolve', $state, $list__19);
}

#182
function Shared_002EPlayer___move($dir__12, $player__41) {
    if ($player__41 instanceof CrazyPlayer_Playing) {
        $otherPlayers__3 = FSharpList::get_Nil();
        $haybales__1 = Set::empty([ 'Compare' => function ($_x__78, $_y__79) {         return $_x__78->CompareTo($_y__79);
 }]);
        $cmd__3 = new Command_Move(new PlayerMove($dir__12, $player__41->Item->Tractor));
        return Shared_002EPlayer___exec($otherPlayers__3, $GLOBALS['Shared_002EBarnsModule___empty'], $haybales__1, $cmd__3, $player__41);
    }     else {
        throw new Exception('Not playing');
    }
}

#183
function Shared_002EPlayer___start($color__1, $parcel__4, $pos__14) {
    $state__2 = new CrazyPlayer_Starting(new Starting($color__1, $parcel__4, new Hand_PublicHand(FSharpList::get_Nil()), $GLOBALS['Shared_002EBonusModule___empty']));
    $otherPlayers__4 = FSharpList::get_Nil();
    $haybales__2 = Set::empty([ 'Compare' => function ($_x__80, $_y__81) {     return $_x__80->CompareTo($_y__81);
 }]);
    $cmd__4 = new Command_SelectFirstCrossroad(new SelectFirstCrossroad($pos__14));
    return Shared_002EPlayer___exec($otherPlayers__4, $GLOBALS['Shared_002EBarnsModule___empty'], $haybales__2, $cmd__4, $state__2);
}

#184
function Shared_002EPlayer___possibleMove($player__42, $dir__13) {
    $pos__15 = Shared_002ECrossroadModule___neighbor($dir__13, $player__42->Tractor);
    if (Shared_002ECrossroadModule___isOnBoard($pos__15)) {
        return new Cons([ $dir__13, new Ok($pos__15)], FSharpList::get_Nil());
    }     else {
        return FSharpList::get_Nil();
    }
}

#185
function Shared_002EPlayer___basicMoves($player__43) {
    if ($player__43 instanceof CrazyPlayer_Playing) {
        if (Shared_002EMovesModule___canMove($player__43->Item->Moves)) {
            $player__45 = $player__43->Item;
            $list__20 = new Cons(new Direction_Up(), new Cons(new Direction_Down(), new Cons(new Direction_Horizontal(), FSharpList::get_Nil())));
            return FSharpList::collect(function ($dir__14) use ($player__45) {             return Shared_002EPlayer___possibleMove($player__45, $dir__14);
 }, $list__20);
        }         else {
            return FSharpList::get_Nil();
        }
    }     else {
        return FSharpList::get_Nil();
    }
}

#186
function Shared_002EPlayer___bindMove($f, $cr) {
    switch (get_class($cr))
    {
        case 'ResultError':
            return new ResultError($cr->ErrorValue);
        default:
            return $f($cr->ResultValue);
    }
}

#187
function Shared_002EPlayer___op_GreaterGreaterEquals($c__5, $f__1) {
    return Shared_002EPlayer___bindMove($f__1, $c__5);
}

#188
function Shared_002EPlayer___checkTractor($player__46, $c__6) {
    if (Util::equals($c__6, $player__46->Tractor)) {
        return new ResultError([ $c__6, new MoveBlocker_Tractor()]);
    }     else {
        return new Ok($c__6);
    }
}

#189
function Shared_002EPlayer___checkProtection($player__47, $c__7) {
    $fence__6 = Shared_002EFenceModule___fenceCrossroads($player__47->Tractor, $player__47->Fence);
    $matchValue__31 = Seq::tryFindIndex(function ($p__62) use ($c__7) {     return Util::equals($p__62, $c__7);
 }, $fence__6);
    if (!is_null($matchValue__31)) {
        $i__2 = $matchValue__31;
        if ($player__47->Bonus->HighVoltage) {
            return new ResultError([ $c__7, new MoveBlocker_HighVoltageProtection()]);
        }         else {
            if ($i__2 < 2) {
                return new ResultError([ $c__7, new MoveBlocker_Protection()]);
            }             else {
                return new Ok($c__7);
            }
        }
    }     else {
        return new Ok($c__7);
    }
}

#190
function Shared_002EPlayer___checkHeliported($moverBonus, $player__48, $c__8) {
    if ($moverBonus->Heliported > 0) {
        $source__3 = Shared_002EFenceModule___fenceCrossroads($player__48->Tractor, $player__48->Fence);
        $isOnFence = Seq::exists(function ($p__63) use ($c__8) {         return Util::equals($p__63, $c__8);
 }, $source__3);
        if ($isOnFence) {
            return new ResultError([ $c__8, new MoveBlocker_PhytosanitaryProducts()]);
        }         else {
            return new Ok($c__8);
        }
    }     else {
        return new Ok($c__8);
    }
}

#191
function Shared_002EPlayer___checkMove($moverbonus, $player__49, $c__9) {
    if ($player__49 instanceof CrazyPlayer_Playing) {
        return Shared_002EPlayer___op_GreaterGreaterEquals(Shared_002EPlayer___op_GreaterGreaterEquals(Shared_002EPlayer___checkTractor($player__49->Item, $c__9), function ($c__10) use ($player__49) {         return Shared_002EPlayer___checkProtection($player__49->Item, $c__10);
 }), function ($c__11) use ($moverbonus, $player__49) {         return Shared_002EPlayer___checkHeliported($moverbonus, $player__49->Item, $c__11);
 });
    }     else {
        return new Ok($c__9);
    }
}

#192
function Shared_002EPlayer___otherPlayers($playerid__2, $board) {
    $source__4 = Map::toSeq($board->Players);
    $source__5 = Seq::filter(function ($tupledArg__4) use ($playerid__2) {     return $tupledArg__4[0] !== $playerid__2;
 }, $source__4);
    return FSharpList::ofSeq($source__5);
}

#193
function Shared_002EPlayer___possibleMoves($playerid__3, $board__1) {
    if ($board__1 instanceof Board_Board) {
        if (!is_null($playerid__3)) {
            $board__2 = $board__1->Item;
            $playerid__4 = $playerid__3;
            $matchValue__33 = Map::tryFind($playerid__4, $board__2->Players);
            if (!is_null($matchValue__33)) {
                if ($matchValue__33 instanceof CrazyPlayer_Playing) {
                    if ((function () use ($matchValue__33) { 
                        $player__51 = $matchValue__33;
                        $p__64 = $matchValue__33->Item;
                        return Shared_002EMovesModule___canMove($p__64->Moves);
                    })()) {
                        $p__65 = $matchValue__33->Item;
                        $player__52 = $matchValue__33;
                        $list__21 = Shared_002EPlayer___otherPlayers($playerid__4, $board__2);
                        $otherPlayers__5 = FSharpList::map(function ($tuple__2) {                         return $tuple__2[1];
 }, $list__21);
                        $moverbonus__1 = Shared_002EPlayer___bonus($player__52);
                        $check = function ($player__53) use ($moverbonus__1) {                         return function ($c__12) use ($moverbonus__1, $player__53) {                         return Shared_002EPlayer___checkMove($moverbonus__1, $player__53, $c__12);
 };
 };
                        return FSharpList::ofSeq(Seq::delay(function ($unitVar__46) use ($board__2, $check, $otherPlayers__5, $p__65, $player__52) {                         return Seq::collect(function ($matchValue__34) use ($board__2, $check, $otherPlayers__5, $p__65) { 
                            $path__5 = Shared_002EPathModule___neighbor($matchValue__34[0], $p__65->Tractor);
                            if (Set::contains($path__5, $board__2->HayBales)) {
                                $c__13 = Shared_002ECrossroadModule___neighbor($matchValue__34[0], $p__65->Tractor);
                                return Seq::singleton(new Move_ImpossibleMove($matchValue__34[0], $c__13, new MoveBlocker_HayBaleOnPath()));
                            }                             else {
                                $matchValue__35 = Seq::fold(function ($c__14, $p__66) use ($check) {                                 return Shared_002EPlayer___bindMove($check($p__66), $c__14);
 }, $matchValue__34[1], $otherPlayers__5);
                                switch (get_class($matchValue__35))
                                {
                                    case 'ResultError':
                                        return Seq::singleton(new Move_ImpossibleMove($matchValue__34[0], $matchValue__35->ErrorValue[0], $matchValue__35->ErrorValue[1]));
                                    default:
                                        return Seq::singleton(new Move_Move($matchValue__34[0], $matchValue__35->ResultValue));
                                }
                            }
                        }, Shared_002EPlayer___basicMoves($player__52));
 }));
                    }                     else {
                        if (is_null($matchValue__33)) {
                            return FSharpList::get_Nil();
                        }                         else {
                            switch (get_class($matchValue__33))
                            {
                                case 'CrazyPlayer_Ko':
                                    $_target__166 = 1;
                                    break;
                                case 'CrazyPlayer_Playing':
                                    $_target__166 = 1;
                                    break;
                                default:
                                    $p__67 = $matchValue__33->Item->Parcel->tile;
                                    $_target__166 = 0;
                                    break;
                            }
                            switch ($_target__166)
                            {
                                case 0:
                                    return new Cons(new Move_SelectCrossroad(new Crossroad($p__67, new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad($p__67, new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___NW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___NE']), new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___SW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___SE']), new CrossroadSide_CLeft())), FSharpList::get_Nil()))))));
                                case 1:
                                    return FSharpList::get_Nil();
                            }
                        }
                    }
                }                 else {
                    if (is_null($matchValue__33)) {
                        return FSharpList::get_Nil();
                    }                     else {
                        switch (get_class($matchValue__33))
                        {
                            case 'CrazyPlayer_Ko':
                                $_target__167 = 1;
                                break;
                            case 'CrazyPlayer_Playing':
                                $_target__167 = 1;
                                break;
                            default:
                                $p__67 = $matchValue__33->Item->Parcel->tile;
                                $_target__167 = 0;
                                break;
                        }
                        switch ($_target__167)
                        {
                            case 0:
                                return new Cons(new Move_SelectCrossroad(new Crossroad($p__67, new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad($p__67, new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___NW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___NE']), new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___SW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___SE']), new CrossroadSide_CLeft())), FSharpList::get_Nil()))))));
                            case 1:
                                return FSharpList::get_Nil();
                        }
                    }
                }
            }             else {
                if (is_null($matchValue__33)) {
                    return FSharpList::get_Nil();
                }                 else {
                    switch (get_class($matchValue__33))
                    {
                        case 'CrazyPlayer_Ko':
                            $_target__168 = 1;
                            break;
                        case 'CrazyPlayer_Playing':
                            $_target__168 = 1;
                            break;
                        default:
                            $p__67 = $matchValue__33->Item->Parcel->tile;
                            $_target__168 = 0;
                            break;
                    }
                    switch ($_target__168)
                    {
                        case 0:
                            return new Cons(new Move_SelectCrossroad(new Crossroad($p__67, new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad($p__67, new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___NW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___NE']), new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___SW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Shared_002EAxe___op_Addition__2BE35040($p__67, $GLOBALS['Shared_002EAxeModule___SE']), new CrossroadSide_CLeft())), FSharpList::get_Nil()))))));
                        case 1:
                            return FSharpList::get_Nil();
                    }
                }
            }
        }         else {
            return FSharpList::get_Nil();
        }
    }     else {
        return FSharpList::get_Nil();
    }
}

#194
function Shared_002EPlayer___canMove($playerid__5, $board__3) {
    $list__22 = Shared_002EPlayer___possibleMoves($playerid__5, $board__3);
    return FSharpList::exists(function ($_arg1__57) {     if ($_arg1__57 instanceof Move_ImpossibleMove) {
        return false;
    }     else {
        return true;
    }
 }, $list__22);
}

#195
function Shared_002EPlayer___takeCards($cards__2, $player__54) {
    switch (get_class($player__54))
    {
        case 'CrazyPlayer_Starting':
            return new CrazyPlayer_Starting((function () use ($cards__2, $player__54) { 
                if ($player__54->Item->Hand instanceof Hand_PrivateHand) {
                    switch (get_class($cards__2))
                    {
                        case 'Hand_PrivateHand':
                            $c__20 = $cards__2->cards;
                            $h__3 = $player__54->Item->Hand->cards;
                            $Hand__5 = new Hand_PrivateHand(($h__3 + $c__20));
                            break;
                        default:
                            throw new Exception('Unexpected mix');
                            break;
                    }
                }                 else {
                    switch (get_class($cards__2))
                    {
                        case 'Hand_PublicHand':
                            $c__19 = $cards__2->cards;
                            $h__2 = $player__54->Item->Hand->cards;
                            $Hand__5 = new Hand_PublicHand(FSharpList::append($h__2, $c__19));
                            break;
                        default:
                            throw new Exception('Unexpected mix');
                            break;
                    }
                }
                return new Starting($player__54->Item->Color, $player__54->Item->Parcel, $Hand__5, $player__54->Item->Bonus);
            })());
        case 'CrazyPlayer_Ko':
            return $player__54;
        default:
            return new CrazyPlayer_Playing((function () use ($cards__2, $player__54) { 
                if ($player__54->Item->Hand instanceof Hand_PrivateHand) {
                    switch (get_class($cards__2))
                    {
                        case 'Hand_PrivateHand':
                            $c__18 = $cards__2->cards;
                            $h__1 = $player__54->Item->Hand->cards;
                            $Hand__4 = new Hand_PrivateHand(($h__1 + $c__18));
                            break;
                        default:
                            throw new Exception('Unexpected mix');
                            break;
                    }
                }                 else {
                    switch (get_class($cards__2))
                    {
                        case 'Hand_PublicHand':
                            $c__17 = $cards__2->cards;
                            $h = $player__54->Item->Hand->cards;
                            $Hand__4 = new Hand_PublicHand(FSharpList::append($h, $c__17));
                            break;
                        default:
                            throw new Exception('Unexpected mix');
                            break;
                    }
                }
                return new Playing($player__54->Item->Color, $player__54->Item->Tractor, $player__54->Item->Fence, $player__54->Item->Field, $player__54->Item->Power, $player__54->Item->Moves, $Hand__4, $player__54->Item->Bonus);
            })());
    }
}

#196
function Shared_002EPlayer___toState($p__70) {
    switch (get_class($p__70))
    {
        case 'CrazyPlayer_Playing':
            return new PlayerState_SPlaying(new PlayingState($p__70->Item->Color, $p__70->Item->Tractor, $p__70->Item->Fence, Set::toList($p__70->Item->Field->parcels), $p__70->Item->Power, $p__70->Item->Moves, $p__70->Item->Hand, $p__70->Item->Bonus));
        case 'CrazyPlayer_Ko':
            return new PlayerState_SKo($p__70->Item);
        default:
            return new PlayerState_SStarting(new StartingState($p__70->Item->Color, $p__70->Item->Parcel, $p__70->Item->Hand, $p__70->Item->Bonus));
    }
}

#197
function Shared_002EPlayer___ofState($p__73) {
    switch (get_class($p__73))
    {
        case 'PlayerState_SPlaying':
            return new CrazyPlayer_Playing(new Playing($p__73->Item->SColor, $p__73->Item->STractor, $p__73->Item->SFence, new Field(Set::ofSeq($p__73->Item->SField, [ 'Compare' => function ($_x__82, $_y__83) {             return $_x__82->CompareTo($_y__83);
 }])), $p__73->Item->SPower, $p__73->Item->SMoves, $p__73->Item->SHand, $p__73->Item->SBonus));
        case 'PlayerState_SKo':
            return new CrazyPlayer_Ko($p__73->Item);
        default:
            return new CrazyPlayer_Starting(new Starting($p__73->Item->SColor, $p__73->Item->SParcel, $p__73->Item->SHand, $p__73->Item->SBonus));
    }
}

#198
$GLOBALS['Shared_002EBoardModule___initialState'] = new UndoableBoard(new Board_InitialState(), new Board_InitialState(), new UndoType_NoUndo(), false, true);

#199
abstract class BoardCommand implements Union, FSharpUnion {
}

#199
class BoardCommand_Play extends BoardCommand {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
    function get_Case() {
        return 'BoardCommand_Play';
    }
    function get_FSharpCase() {
        return 'Play';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__169 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__169 != 0) {
            return $_cmp__169;
        }        
        $_cmp__170 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__170 != 0) {
            return $_cmp__170;
        }        
        $_cmp__171 = $this->Item2->CompareTo($other->Item2);
        if ($_cmp__171 != 0) {
            return $_cmp__171;
        }        
        return 0;
    }
}

#199
class BoardCommand_Start extends BoardCommand {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'BoardCommand_Start';
    }
    function get_FSharpCase() {
        return 'Start';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__172 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__172 != 0) {
            return $_cmp__172;
        }        
        $_cmp__173 = $this->Item->CompareTo($other->Item);
        if ($_cmp__173 != 0) {
            return $_cmp__173;
        }        
        return 0;
    }
}

#200
class BoardStart {
    public $Players;
    public $Goal;
    public $Undo;
    public $UseGameOver;
    function __construct($Players, $Goal, $Undo, $UseGameOver) {
        $this->Players = $Players;
        $this->Goal = $Goal;
        $this->Undo = $Undo ?? new UndoType_FullUndo();
        $this->UseGameOver = $UseGameOver;
    }
}

#201
abstract class BoardEvent implements Union, FSharpUnion {
}

#201
class BoardEvent_Played extends BoardEvent {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
    function get_Case() {
        return 'BoardEvent_Played';
    }
    function get_FSharpCase() {
        return 'Played';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__174 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__174 != 0) {
            return $_cmp__174;
        }        
        $_cmp__175 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__175 != 0) {
            return $_cmp__175;
        }        
        $_cmp__176 = $this->Item2->CompareTo($other->Item2);
        if ($_cmp__176 != 0) {
            return $_cmp__176;
        }        
        return 0;
    }
}

#201
class BoardEvent_Started extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'BoardEvent_Started';
    }
    function get_FSharpCase() {
        return 'Started';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__177 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__177 != 0) {
            return $_cmp__177;
        }        
        $_cmp__178 = $this->Item->CompareTo($other->Item);
        if ($_cmp__178 != 0) {
            return $_cmp__178;
        }        
        return 0;
    }
}

#201
class BoardEvent_Next extends BoardEvent {
    function __construct() {
    }
    function get_Case() {
        return 'BoardEvent_Next';
    }
    function get_FSharpCase() {
        return 'Next';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__179 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__179;
    }
}

#201
class BoardEvent_PlayerDrewCards extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'BoardEvent_PlayerDrewCards';
    }
    function get_FSharpCase() {
        return 'PlayerDrewCards';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__180 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__180 != 0) {
            return $_cmp__180;
        }        
        $_cmp__181 = $this->Item->CompareTo($other->Item);
        if ($_cmp__181 != 0) {
            return $_cmp__181;
        }        
        return 0;
    }
}

#201
class BoardEvent_GameWon extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'BoardEvent_GameWon';
    }
    function get_FSharpCase() {
        return 'GameWon';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__182 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__182 != 0) {
            return $_cmp__182;
        }        
        $_cmp__183 = $this->Item->CompareTo($other->Item);
        if ($_cmp__183 != 0) {
            return $_cmp__183;
        }        
        return 0;
    }
}

#201
class BoardEvent_GameEnded extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'BoardEvent_GameEnded';
    }
    function get_FSharpCase() {
        return 'GameEnded';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__184 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__184 != 0) {
            return $_cmp__184;
        }        
        $_cmp__185 = $this->Item->CompareTo($other->Item);
        if ($_cmp__185 != 0) {
            return $_cmp__185;
        }        
        return 0;
    }
}

#201
class BoardEvent_HayBalesPlaced extends BoardEvent {
    public $added;
    public $removed;
    function __construct($added, $removed) {
        $this->added = $added;
        $this->removed = $removed;
    }
    function get_Case() {
        return 'BoardEvent_HayBalesPlaced';
    }
    function get_FSharpCase() {
        return 'HayBalesPlaced';
    }
    function get_Tag() {
        return 6;
    }
    function CompareTo($other) {
        $_cmp__186 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__186 != 0) {
            return $_cmp__186;
        }        
        $_cmp__187 = $this->added->CompareTo($other->added);
        if ($_cmp__187 != 0) {
            return $_cmp__187;
        }        
        $_cmp__188 = $this->removed->CompareTo($other->removed);
        if ($_cmp__188 != 0) {
            return $_cmp__188;
        }        
        return 0;
    }
}

#201
class BoardEvent_HayBaleDynamited extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'BoardEvent_HayBaleDynamited';
    }
    function get_FSharpCase() {
        return 'HayBaleDynamited';
    }
    function get_Tag() {
        return 7;
    }
    function CompareTo($other) {
        $_cmp__189 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__189 != 0) {
            return $_cmp__189;
        }        
        $_cmp__190 = $this->Item->CompareTo($other->Item);
        if ($_cmp__190 != 0) {
            return $_cmp__190;
        }        
        return 0;
    }
}

#201
class BoardEvent_DiscardPileShuffled extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'BoardEvent_DiscardPileShuffled';
    }
    function get_FSharpCase() {
        return 'DiscardPileShuffled';
    }
    function get_Tag() {
        return 8;
    }
    function CompareTo($other) {
        $_cmp__191 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__191 != 0) {
            return $_cmp__191;
        }        
        $_cmp__192 = $this->Item->CompareTo($other->Item);
        if ($_cmp__192 != 0) {
            return $_cmp__192;
        }        
        return 0;
    }
}

#201
class BoardEvent_DrawPileShuffled extends BoardEvent {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'BoardEvent_DrawPileShuffled';
    }
    function get_FSharpCase() {
        return 'DrawPileShuffled';
    }
    function get_Tag() {
        return 9;
    }
    function CompareTo($other) {
        $_cmp__193 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__193 != 0) {
            return $_cmp__193;
        }        
        $_cmp__194 = $this->Item->CompareTo($other->Item);
        if ($_cmp__194 != 0) {
            return $_cmp__194;
        }        
        return 0;
    }
}

#201
class BoardEvent_UndoCheckPointed extends BoardEvent {
    function __construct() {
    }
    function get_Case() {
        return 'BoardEvent_UndoCheckPointed';
    }
    function get_FSharpCase() {
        return 'UndoCheckPointed';
    }
    function get_Tag() {
        return 10;
    }
    function CompareTo($other) {
        $_cmp__195 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__195;
    }
}

#202
class BoardStarted {
    public $Players;
    public $DrawPile;
    public $Barns;
    public $Goal;
    public $Undo;
    public $UseGameOver;
    function __construct($Players, $DrawPile, $Barns, $Goal, $Undo=NULL, $UseGameOver=false) {
        $this->Players = $Players;
        $this->DrawPile = $DrawPile;
        $this->Barns = $Barns;
        $this->Goal = $Goal;
        $this->Undo = $Undo ?? new UndoType_FullUndo();
        $this->UseGameOver = $UseGameOver;
    }
}

#203
class PlayerDrewCards {
    public $Player;
    public $Cards;
    function __construct($Player, $Cards) {
        $this->Player = $Player;
        $this->Cards = $Cards;
    }
}

#204
function Shared_002EBoardModule___currentPlayer($board__4) {
    return Map::FSharpMap__get_Item__2B595($board__4->Players, Shared_002EGameTable__get_Player($board__4->Table, NULL));
}

#205
function Shared_002EBoardModule___currentOtherPlayers($board__5) {
    return Shared_002EPlayer___otherPlayers(Shared_002EGameTable__get_Player($board__5->Table, NULL), $board__5);
}

#206
function Shared_002EBoardModule___totalSize($board__6) {
    return Map::fold(function ($count__4, $_arg1__58, $p__76) {     return $count__4 + Shared_002EPlayer___fieldTotalSize($p__76);
 }, 0, $board__6->Players);
}

#207
function Shared_002EBoardModule___hayBales($board__7) {
    switch (get_class($board__7))
    {
        case 'Board_Board':
            return $board__7->Item->HayBales;
        case 'Board_Won':
            return $board__7->Item2->HayBales;
        default:
            return Set::empty([ 'Compare' => function ($_x__84, $_y__85) {             return $_x__84->CompareTo($_y__85);
 }]);
    }
}

#208
function Shared_002EBoardModule___endGameWithBribe($board__8) {
    switch (get_class($board__8->Goal))
    {
        case 'Goal_Individual':
            $player__55 = Shared_002EBoardModule___currentPlayer($board__8);
            return Shared_002EPlayer___fieldTotalSize($player__55) + 1 >= $board__8->Goal->Item;
        default:
            return false;
    }
}

#209
abstract class FindWinner implements Union, FSharpUnion {
}

#209
class FindWinner_Winner extends FindWinner {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'FindWinner_Winner';
    }
    function get_FSharpCase() {
        return 'Winner';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__196 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__196 != 0) {
            return $_cmp__196;
        }        
        $_cmp__197 = $this->Item->CompareTo($other->Item);
        if ($_cmp__197 != 0) {
            return $_cmp__197;
        }        
        return 0;
    }
}

#209
class FindWinner_Lead extends FindWinner {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'FindWinner_Lead';
    }
    function get_FSharpCase() {
        return 'Lead';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__198 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__198 != 0) {
            return $_cmp__198;
        }        
        $_cmp__199 = $this->Item->CompareTo($other->Item);
        if ($_cmp__199 != 0) {
            return $_cmp__199;
        }        
        return 0;
    }
}

#210
function Shared_002EBoardModule___tryFindWinner($board__9) {
    $list__23 = Map::toList($board__9->Players);
    $list__24 = FSharpList::groupBy(function ($tupledArg__5) {     return [ Shared_002EPlayer___principalFieldSize($tupledArg__5[1]), Shared_002EPlayer___fieldTotalSize($tupledArg__5[1])];
 }, $list__23, [ 'Equals' => 'Util::equalArrays', 'GetHashCode' => 'Util::structuralHash']);
    $patternInput__7 = FSharpList::maxBy(function ($tupledArg__6) {     return $tupledArg__6[0];
 }, $list__24, [ 'Compare' => 'Util::compareArrays']);
    $leadsids = FSharpList::map(function ($tupledArg__7) {     return $tupledArg__7[0];
 }, $patternInput__7[1]);
    switch (get_class($board__9->Goal))
    {
        case 'Goal_Individual':
            $won = Map::exists(function ($_arg4__1, $p__78) use ($board__9) {             return Shared_002EPlayer___fieldTotalSize($p__78) >= $board__9->Goal->Item;
 }, $board__9->Players);
            if ($won) {
                return new FindWinner_Winner($leadsids);
            }             else {
                return new FindWinner_Lead($leadsids);
            }
        default:
            if (Shared_002EBoardModule___totalSize($board__9) >= $board__9->Goal->Item) {
                return new FindWinner_Winner($leadsids);
            }             else {
                return new FindWinner_Lead($leadsids);
            }
    }
}

#211
function Shared_002EBoardModule___next($shouldShuffle, $state__4) {
    $playerId__1 = Shared_002EGameTable__get_Player($state__4->Table, NULL);
    $player__56 = Map::tryFind($playerId__1, $state__4->Players);
    $nextPlayerId = Shared_002EGameTable__get_Player(Shared_002EGameTable__get_Next($state__4->Table, NULL), NULL);
    $nextPlayer = Map::FSharpMap__get_Item__2B595($state__4->Players, $nextPlayerId);
    return FSharpList::ofSeq(Seq::delay(function ($unitVar__47) use ($nextPlayer, $nextPlayerId, $playerId__1, $player__56, $shouldShuffle, $state__4) { 
        if (is_null($player__56)) {
            $bonus__6 = $GLOBALS['Shared_002EBonusModule___empty'];
        }         else {
            $player__57 = $player__56;
            $bonus__6 = Shared_002EPlayer___bonus($player__57);
        }
        return Seq::append((function () use ($bonus__6, $playerId__1) { 
            $list__25 = Shared_002EBonusModule___endTurn($bonus__6);
            return FSharpList::map(function ($c__22) use ($playerId__1) {             return new BoardEvent_Played($playerId__1, new Event_BonusDiscarded($c__22));
 }, $list__25);
        })(), Seq::delay(function ($unitVar__48) use ($nextPlayer, $nextPlayerId, $shouldShuffle, $state__4) {         return Seq::append($shouldShuffle ? ($state__4->DrawPile instanceof Hand_PrivateHand ? Seq::empty() : Seq::singleton(new BoardEvent_DrawPileShuffled(Shared_002EDrawPile___shuffle($state__4->UseGameOver, $state__4->DrawPile->cards)))) : Seq::empty(), Seq::delay(function ($unitVar__49) use ($nextPlayer, $nextPlayerId) {         return Seq::append(Seq::singleton(new BoardEvent_Next()), Seq::delay(function ($unitVar__50) use ($nextPlayer, $nextPlayerId) {         return Seq::append((function () use ($nextPlayer, $nextPlayerId) { 
            $list__26 = Shared_002EBonusModule___startTurn(Shared_002EPlayer___bonus($nextPlayer));
            return FSharpList::map(function ($c__23) use ($nextPlayerId) {             return new BoardEvent_Played($nextPlayerId, new Event_BonusDiscarded($c__23));
 }, $list__26);
        })(), Seq::delay(function ($unitVar__51) {         return Seq::singleton(new BoardEvent_UndoCheckPointed());
 }));
 }));
 }));
 }));
    }));
}

#212
abstract class BribeBlocker implements Union, FSharpUnion {
}

#212
class BribeBlocker_InstantVictory extends BribeBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'BribeBlocker_InstantVictory';
    }
    function get_FSharpCase() {
        return 'InstantVictory';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__200 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__200;
    }
}

#212
class BribeBlocker_NoParcelsToBribe extends BribeBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'BribeBlocker_NoParcelsToBribe';
    }
    function get_FSharpCase() {
        return 'NoParcelsToBribe';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__201 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__201;
    }
}

#213
abstract class BribeParcelBlocker implements Union, FSharpUnion {
}

#213
class BribeParcelBlocker_BarnBlocker extends BribeParcelBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'BribeParcelBlocker_BarnBlocker';
    }
    function get_FSharpCase() {
        return 'BarnBlocker';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__202 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__202;
    }
}

#213
class BribeParcelBlocker_LastParcelBlocker extends BribeParcelBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'BribeParcelBlocker_LastParcelBlocker';
    }
    function get_FSharpCase() {
        return 'LastParcelBlocker';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__203 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__203;
    }
}

#213
class BribeParcelBlocker_WatchedBlocker extends BribeParcelBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'BribeParcelBlocker_WatchedBlocker';
    }
    function get_FSharpCase() {
        return 'WatchedBlocker';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__204 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__204;
    }
}

#213
class BribeParcelBlocker_FenceBlocker extends BribeParcelBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'BribeParcelBlocker_FenceBlocker';
    }
    function get_FSharpCase() {
        return 'FenceBlocker';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__205 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__205;
    }
}

#213
class BribeParcelBlocker_FallowBlocker extends BribeParcelBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'BribeParcelBlocker_FallowBlocker';
    }
    function get_FSharpCase() {
        return 'FallowBlocker';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__206 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__206;
    }
}

#213
class BribeParcelBlocker_BridgeBlocker extends BribeParcelBlocker {
    function __construct() {
    }
    function get_Case() {
        return 'BribeParcelBlocker_BridgeBlocker';
    }
    function get_FSharpCase() {
        return 'BridgeBlocker';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__207 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__207;
    }
}

#214
function Shared_002EBoardModule___isCutParcel($field__10, $parcel__5) {
    $neighbors__1 = new Cons($GLOBALS['Shared_002EAxeModule___N'], new Cons($GLOBALS['Shared_002EAxeModule___NE'], new Cons($GLOBALS['Shared_002EAxeModule___SE'], new Cons($GLOBALS['Shared_002EAxeModule___S'], new Cons($GLOBALS['Shared_002EAxeModule___SW'], new Cons($GLOBALS['Shared_002EAxeModule___NW'], FSharpList::get_Nil()))))));
    $find = function ($neighbors__2, $result) use ($field__10, $parcel__5, &$find) {     if ($neighbors__2 instanceof Cons) {
        $neighbor__2 = Shared_002EParcel___op_Addition__ZF6EFE4B($parcel__5, $neighbors__2->value);
        $infield = Shared_002EFieldModule___containsParcel($neighbor__2, $field__10);
        if ($result instanceof Cons) {
            if ($result->value === $infield) {
                $prev__1 = $result->value;
                return $find($neighbors__2->next, $result);
            }             else {
                return $find($neighbors__2->next, new Cons($infield, $result));
            }
        }         else {
            return $find($neighbors__2->next, new Cons($infield, FSharpList::get_Nil()));
        }
    }     else {
        return $result;
    }
 };
    $changes = $find($neighbors__1, FSharpList::get_Nil());
    if (FSharpList::head($changes) === FSharpList::last($changes)) {
        $changes__1 = FSharpList::tail($changes);
    }     else {
        $changes__1 = $changes;
    }
    return FSharpList::length($changes__1) > 2;
}

#215
function Shared_002EBoardModule___cutParcels($field__11, $_arg1__60) {
    $parcels__10 = Seq::filter(function ($p__79) use ($field__11) {     return Shared_002EBoardModule___isCutParcel($field__11, $p__79);
 }, $_arg1__60->parcels);
    return Shared_002EFieldModule___ofParcels($parcels__10);
}

#216
function Shared_002EBoardModule___findBridgeParcels($field__12) {
    $patternInput__8 = Shared_002EFieldModule___unrestrictedborderTiles($field__12);
    $cut__1 = FSharpList::get_Nil();
    $visited__1 = Map::empty([ 'Compare' => function ($_x__90, $_y__91) {     return $_x__90->CompareTo($_y__91);
 }]);
    $time__1 = 0;
    $loop__7 = function ($parent__1, $parcel__7) use ($patternInput__8, &$cut__1, &$loop__7, &$time__1, &$visited__1) { 
        $visited__1 = Map::add($parcel__7, $time__1, $visited__1);
        $d0__1 = $time__1;
        $time__1 = $time__1 + 1;
        $isRoot = Util::equals($parent__1, $parcel__7);
        $step = function ($dir__17, $tupledArg__8) use ($d0__1, $isRoot, $parcel__7, $parent__1, $patternInput__8, $visited__1, &$cut__1, &$loop__7) { 
            $neighbor__3 = Shared_002EParcel___op_Addition__ZF6EFE4B($parcel__7, $dir__17);
            if (Set::contains($neighbor__3, $patternInput__8->parcels)) {
                $matchValue__41 = $neighbor__3;
            }             else {
                $matchValue__41 = NULL;
            }
            if (!is_null($matchValue__41)) {
                if ((function () use ($matchValue__41, $parent__1) { 
                    $nxt__6 = $matchValue__41;
                    return !Util::equals($nxt__6, $parent__1);
                })()) {
                    $nxt__7 = $matchValue__41;
                    $matchValue__42 = Map::tryFind($nxt__7, $visited__1);
                    if (is_null($matchValue__42)) {
                        $patternInput__9 = [ $loop__7($parcel__7, $nxt__7), $tupledArg__8[1] + 1];
                    }                     else {
                        $d__4 = $matchValue__42;
                        $patternInput__9 = [ Util::min('Util::comparePrimitives', $d__4, $tupledArg__8[0]), $tupledArg__8[1]];
                    }
                    if ($patternInput__9[0] >= $d0__1 ? !$isRoot : false) {
                        $cut__1 = new Cons($parcel__7, $cut__1);
                    }                     else {
                    }
                    return [ Util::min('Util::comparePrimitives', $patternInput__9[0], $tupledArg__8[0]), $patternInput__9[1]];
                }                 else {
                    return [ $tupledArg__8[0], $tupledArg__8[1]];
                }
            }             else {
                return [ $tupledArg__8[0], $tupledArg__8[1]];
            }
        };
        $patternInput__10 = $step($GLOBALS['Shared_002EAxeModule___NW'], $step($GLOBALS['Shared_002EAxeModule___SW'], $step($GLOBALS['Shared_002EAxeModule___S'], $step($GLOBALS['Shared_002EAxeModule___SE'], $step($GLOBALS['Shared_002EAxeModule___NE'], $step($GLOBALS['Shared_002EAxeModule___N'], [ $d0__1, 0]))))));
        if ($isRoot ? $patternInput__10[1] > 1 : false) {
            $cut__1 = new Cons($parcel__7, $cut__1);
        }         else {
        }
        $visited__1 = Map::add($parcel__7, $patternInput__10[0], $visited__1);
        return $patternInput__10[0];
    };
    $start__6 = Set::minElement($patternInput__8->parcels);
    $value__3 = $loop__7($start__6, $start__6);
    void ($value__3);
    $list__27 = $cut__1;
    $parcels__11 = FSharpList::filter('Shared_002EParcelModule___isOnBoard', $list__27);
    return Shared_002EFieldModule___ofParcels($parcels__11);
}

#217
function Shared_002EBoardModule___bribeParcels($board__10) {
    if (Shared_002EBoardModule___endGameWithBribe($board__10)) {
        return new ResultError(new BribeBlocker_InstantVictory());
    }     else {
        $player__58 = Map::FSharpMap__get_Item__2B595($board__10->Players, Shared_002EGameTable__get_Player($board__10->Table, NULL));
        $playerField = Shared_002EPlayer___field($player__58);
        $border__3 = Shared_002EFieldModule___borderTiles($playerField);
        $barns__5 = Shared_002EField___op_Addition__Z24735800($board__10->Barns->Free, $board__10->Barns->Occupied);
        $bridgeParcels = Shared_002EBoardModule___findBridgeParcels($playerField);
        $list__28 = Shared_002EBoardModule___currentOtherPlayers($board__10);
        $fields__1 = FSharpList::map(function ($tupledArg__9) use ($border__3, $bridgeParcels) { 
            $field__13 = Shared_002EPlayer___field($tupledArg__9[1]);
            $bonus__7 = Shared_002EPlayer___bonus($tupledArg__9[1]);
            if (Shared_002EFieldModule___size($field__13) === 1 ? true : $bonus__7->Watched) {
                return $GLOBALS['Shared_002EFieldModule___empty'];
            }             else {
                $cutParcels = Shared_002EBoardModule___cutParcels($field__13, Shared_002EFieldModule___intersect($field__13, $border__3));
                switch (get_class($tupledArg__9[1]))
                {
                    case 'CrazyPlayer_Starting':
                        $_target__208 = 1;
                        break;
                    case 'CrazyPlayer_Ko':
                        $_target__208 = 1;
                        break;
                    default:
                        $_target__208 = 0;
                        break;
                }
                switch ($_target__208)
                {
                    case 0:
                        $startCrossRoad = Shared_002EFenceModule___start($tupledArg__9[1]->Item->Tractor, $tupledArg__9[1]->Item->Fence);
                        $parcels__12 = Shared_002ECrossroadModule___neighborTiles($startCrossRoad);
                        $arg10_0040__2 = Shared_002EFieldModule___ofParcels($parcels__12);
                        $startTiles = Shared_002EFieldModule___intersect($field__13, $arg10_0040__2);
                        return Shared_002EField___op_Subtraction__Z24735800(Shared_002EField___op_Subtraction__Z24735800(Shared_002EField___op_Subtraction__Z24735800($field__13, $startTiles), $cutParcels), $bridgeParcels);
                    case 1:
                        return $field__13;
                }
            }
        }, $list__28);
        $otherPlayersFields = Shared_002EFieldModule___unionMany($fields__1);
        $parcelsToBribe = Shared_002EField___op_Subtraction__Z24735800(Shared_002EFieldModule___intersect($border__3, $otherPlayersFields), $barns__5);
        if (Shared_002EFieldModule___isEmpty($parcelsToBribe)) {
            return new ResultError(new BribeBlocker_NoParcelsToBribe());
        }         else {
            return new Ok($parcelsToBribe);
        }
    }
}

#218
function Shared_002EBoardModule___bribeParcelsBlockers($board__11) {
    if (Shared_002EBoardModule___endGameWithBribe($board__11)) {
        return FSharpList::get_Nil();
    }     else {
        $player__59 = Map::FSharpMap__get_Item__2B595($board__11->Players, Shared_002EGameTable__get_Player($board__11->Table, NULL));
        $playerField__1 = Shared_002EPlayer___field($player__59);
        $border__4 = Shared_002EFieldModule___borderTiles($playerField__1);
        $othersFields = Shared_002EFieldModule___unionMany(FSharpList::ofSeq(Seq::delay(function ($unitVar__52) use ($board__11) {         return Seq::collect(function ($matchValue__43) {         return Seq::singleton(Shared_002EPlayer___field($matchValue__43[1]));
 }, Shared_002EBoardModule___currentOtherPlayers($board__11));
 })));
        $arg10_0040__3 = Shared_002EFieldModule___intersect($border__4, Shared_002EField___op_Addition__Z24735800($board__11->Barns->Free, $board__11->Barns->Occupied));
        $barns__6 = Shared_002EFieldModule___intersect($othersFields, $arg10_0040__3);
        $border__5 = Shared_002EField___op_Subtraction__Z24735800($border__4, $barns__6);
        $bridgeParcels__1 = Shared_002EBoardModule___findBridgeParcels($playerField__1);
        return FSharpList::ofSeq(Seq::delay(function ($unitVar__53) use ($barns__6, $board__11, $border__5, $bridgeParcels__1) {         return Seq::append(Seq::map(function ($barn) {         return [ $barn, new BribeParcelBlocker_BarnBlocker()];
 }, Shared_002EFieldModule___parcels($barns__6)), Seq::delay(function ($unitVar__54) use ($board__11, $border__5, $bridgeParcels__1) {         return Seq::collect(function ($matchValue__44) use ($border__5, $bridgeParcels__1) { 
            $field__14 = Shared_002EPlayer___field($matchValue__44[1]);
            $bonus__8 = Shared_002EPlayer___bonus($matchValue__44[1]);
            $fieldBorder = Shared_002EFieldModule___intersect($border__5, $field__14);
            if (Shared_002EFieldModule___size($field__14) === 1) {
                return Seq::map(function ($p__82) {                 return [ $p__82, new BribeParcelBlocker_LastParcelBlocker()];
 }, Shared_002EFieldModule___parcels($fieldBorder));
            }             else {
                if ($bonus__8->Watched) {
                    return Seq::map(function ($p__83) {                     return [ $p__83, new BribeParcelBlocker_WatchedBlocker()];
 }, Shared_002EFieldModule___parcels($fieldBorder));
                }                 else {
                    switch (get_class($matchValue__44[1]))
                    {
                        case 'CrazyPlayer_Starting':
                            $_target__208 = 1;
                            break;
                        case 'CrazyPlayer_Ko':
                            $_target__208 = 1;
                            break;
                        default:
                            $_target__208 = 0;
                            break;
                    }
                    switch ($_target__208)
                    {
                        case 0:
                            $startCrossRoad__1 = Shared_002EFenceModule___start($matchValue__44[1]->Item->Tractor, $matchValue__44[1]->Item->Fence);
                            $parcels__13 = Shared_002ECrossroadModule___neighborTiles($startCrossRoad__1);
                            $arg10_0040__4 = Shared_002EFieldModule___ofParcels($parcels__13);
                            $startTiles__1 = Shared_002EFieldModule___intersect($field__14, $arg10_0040__4);
                            $borderStarts = Shared_002EFieldModule___intersect($startTiles__1, $border__5);
                            return Seq::append(Seq::map(function ($p__85) {                             return [ $p__85, new BribeParcelBlocker_FenceBlocker()];
 }, Shared_002EFieldModule___parcels($borderStarts)), Seq::delay(function ($unitVar__55) use ($border__5, $bridgeParcels__1, $field__14) { 
                                $cutParcels__1 = Shared_002EBoardModule___cutParcels($field__14, Shared_002EFieldModule___intersect($field__14, $border__5));
                                return Seq::append(Seq::map(function ($p__86) {                                 return [ $p__86, new BribeParcelBlocker_FallowBlocker()];
 }, Shared_002EFieldModule___parcels($cutParcels__1)), Seq::delay(function ($unitVar__56) use ($bridgeParcels__1, $field__14) { 
                                    $bridges = Shared_002EFieldModule___intersect($bridgeParcels__1, $field__14);
                                    return Seq::map(function ($p__87) {                                     return [ $p__87, new BribeParcelBlocker_BridgeBlocker()];
 }, Shared_002EFieldModule___parcels($bridges));
                                }));
                            }));
                        case 1:
                            return Seq::empty();
                    }
                }
            }
        }, Shared_002EBoardModule___currentOtherPlayers($board__11));
 }));
 }));
    }
}

#219
function Shared_002EBoardModule___annexed($playerid__6, $e__14, $board__12) {
    $annexedPlayer = Shared_002EPlayer___evolve(Map::FSharpMap__get_Item__2B595($board__12->Players, $playerid__6), new Event_Annexed($e__14));
    $newMap = Map::add($playerid__6, $annexedPlayer, $board__12->Players);
    $annexedBarns = new Barns(Shared_002EFieldModule___ofParcels($e__14->FreeBarns), Shared_002EFieldModule___ofParcels($e__14->OccupiedBarns));
    $Barns = Shared_002EBarnsModule___annex($annexedBarns, $board__12->Barns);
    $state__5 = new PlayingBoard($newMap, $board__12->Table, $board__12->DrawPile, $board__12->DiscardPile, $Barns, $board__12->HayBales, $board__12->Goal, $board__12->UseGameOver);
    return FSharpList::fold(function ($board__13, $tupledArg__10) { 
        $matchValue__45 = Map::FSharpMap__get_Item__2B595($board__13->Players, $tupledArg__10[0]);
        if ($matchValue__45 instanceof CrazyPlayer_Playing) {
            $newP = new CrazyPlayer_Playing((function () use ($matchValue__45, $tupledArg__10) { 
                $Field__2 = Shared_002EField___op_Subtraction__Z24735800($matchValue__45->Item->Field, Shared_002EFieldModule___ofParcels($tupledArg__10[1]));
                return new Playing($matchValue__45->Item->Color, $matchValue__45->Item->Tractor, $matchValue__45->Item->Fence, $Field__2, $matchValue__45->Item->Power, $matchValue__45->Item->Moves, $matchValue__45->Item->Hand, $matchValue__45->Item->Bonus);
            })());
            return new PlayingBoard(Map::add($tupledArg__10[0], $newP, $board__13->Players), $board__13->Table, $board__13->DrawPile, $board__13->DiscardPile, $board__13->Barns, $board__13->HayBales, $board__13->Goal, $board__13->UseGameOver);
        }         else {
            return $board__13;
        }
    }, $state__5, $e__14->LostFields);
}

#220
function Shared_002EBoardModule___bribed($playerid__8, $p__89, $board__14) {
    $newPlayer = Shared_002EPlayer___evolve(Map::FSharpMap__get_Item__2B595($board__14->Players, $playerid__8), new Event_Bribed($p__89));
    $matchValue__46 = Map::FSharpMap__get_Item__2B595($board__14->Players, $p__89->Victim);
    switch (get_class($matchValue__46))
    {
        case 'CrazyPlayer_Playing':
            $newVictim = new CrazyPlayer_Playing((function () use ($matchValue__46, $p__89) { 
                $Field__3 = Shared_002EField___op_Subtraction__Z24735800($matchValue__46->Item->Field, Shared_002EFieldModule___ofParcels(new Cons($p__89->Parcel, FSharpList::get_Nil())));
                return new Playing($matchValue__46->Item->Color, $matchValue__46->Item->Tractor, $matchValue__46->Item->Fence, $Field__3, $matchValue__46->Item->Power, $matchValue__46->Item->Moves, $matchValue__46->Item->Hand, $matchValue__46->Item->Bonus);
            })());
            break;
        case 'CrazyPlayer_Ko':
            $newVictim = $matchValue__46;
            break;
        default:
            $newVictim = new CrazyPlayer_Starting($matchValue__46->Item);
            break;
    }
    return new PlayingBoard((function () use ($board__14, $newPlayer, $newVictim, $p__89, $playerid__8) { 
        $table__7 = Map::add($playerid__8, $newPlayer, $board__14->Players);
        return Map::add($p__89->Victim, $newVictim, $table__7);
    })(), $board__14->Table, $board__14->DrawPile, $board__14->DiscardPile, $board__14->Barns, $board__14->HayBales, $board__14->Goal, $board__14->UseGameOver);
}

#221
function Shared_002EBoardModule___evolve($state__6, $event__2) {
    if ($state__6->Board instanceof Board_Board) {
        if ($event__2 instanceof BoardEvent_GameWon) {
            $board__16 = $state__6->Board->Item;
            $player__60 = $event__2->Item;
            $won__1 = new Board_Won(new Cons($player__60, FSharpList::get_Nil()), $board__16);
            return new UndoableBoard($won__1, $won__1, $state__6->UndoType, $state__6->ShouldShuffle, true);
        }         else {
            if ($event__2 instanceof BoardEvent_GameEnded) {
                $board__17 = $state__6->Board->Item;
                $players__6 = $event__2->Item;
                $won__2 = new Board_Won($players__6, $board__17);
                return new UndoableBoard($won__2, $won__2, $state__6->UndoType, $state__6->ShouldShuffle, true);
            }             else {
                if ($event__2 instanceof BoardEvent_Played) {
                    switch (get_class($event__2->Item2))
                    {
                        case 'Event_CutFence':
                            $board__18 = $state__6->Board->Item;
                            $playerid__9 = $event__2->Item2->Item->Player;
                            $matchValue__50 = Map::FSharpMap__get_Item__2B595($board__18->Players, $playerid__9);
                            if ($matchValue__50 instanceof CrazyPlayer_Playing) {
                                $cutPlayer = new CrazyPlayer_Playing((function () use ($matchValue__50) { 
                                    $Power__1 = new Power_PowerDown();
                                    return new Playing($matchValue__50->Item->Color, $matchValue__50->Item->Tractor, $GLOBALS['Shared_002EFenceModule___empty'], $matchValue__50->Item->Field, $Power__1, $matchValue__50->Item->Moves, $matchValue__50->Item->Hand, $matchValue__50->Item->Bonus);
                                })());
                                $Board = new Board_Board(new PlayingBoard(Map::add($playerid__9, $cutPlayer, $board__18->Players), $board__18->Table, $board__18->DrawPile, $board__18->DiscardPile, $board__18->Barns, $board__18->HayBales, $board__18->Goal, $board__18->UseGameOver));
                                return new UndoableBoard($Board, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, false);
                            }                             else {
                                return $state__6;
                            }
                        case 'Event_Annexed':
                            $board__19 = $state__6->Board->Item;
                            $e__15 = $event__2->Item2->Item;
                            $playerid__10 = $event__2->Item1;
                            $arg0__4 = Shared_002EBoardModule___annexed($playerid__10, $e__15, $board__19);
                            $newBoard = new Board_Board($arg0__4);
                            return new UndoableBoard($newBoard, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, false);
                        case 'Event_Bribed':
                            $board__25 = $state__6->Board->Item;
                            $p__95 = $event__2->Item2->Item;
                            $playerid__11 = $event__2->Item1;
                            $newBoard__6 = Shared_002EBoardModule___bribed($playerid__11, $p__95, $board__25);
                            $Board__1 = new Board_Board($newBoard__6);
                            return new UndoableBoard($Board__1, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, false);
                        case 'Event_Eliminated':
                            $board__26 = $state__6->Board->Item;
                            $e__17 = $event__2->Item2;
                            $playerid__12 = $event__2->Item1;
                            $newPlayer__1 = Shared_002EPlayer___evolve(Map::FSharpMap__get_Item__2B595($board__26->Players, $playerid__12), $e__17);
                            $newTable = Shared_002ETable___eliminate($playerid__12, $board__26->Table);
                            $newBoard__7 = new Board_Board(new PlayingBoard(Map::add($playerid__12, $newPlayer__1, $board__26->Players), $newTable, $board__26->DrawPile, $board__26->DiscardPile, $board__26->Barns, $board__26->HayBales, $board__26->Goal, $board__26->UseGameOver));
                            return new UndoableBoard($newBoard__7, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, false);
                        case 'Event_PlayerQuit':
                            $board__27 = $state__6->Board->Item;
                            $e__18 = $event__2->Item2;
                            $playerid__13 = $event__2->Item1;
                            $currentPlayer = Shared_002EGameTable__get_Player($board__27->Table, NULL);
                            $newPlayer__2 = Shared_002EPlayer___evolve(Map::FSharpMap__get_Item__2B595($board__27->Players, $playerid__13), $e__18);
                            $newTable__1 = Shared_002ETable___eliminate($playerid__13, $board__27->Table);
                            $players__7 = Map::add($playerid__13, $newPlayer__2, $board__27->Players);
                            if ($playerid__13 === $currentPlayer) {
                                $nextPlayer__1 = Shared_002EPlayer___startTurn(Map::FSharpMap__get_Item__2B595($board__27->Players, Shared_002EGameTable__get_Player($newTable__1, NULL)));
                                $key__1 = Shared_002EGameTable__get_Player($newTable__1, NULL);
                                $players__8 = Map::add($key__1, $nextPlayer__1, $players__7);
                            }                             else {
                                $players__8 = $players__7;
                            }
                            $newBoard__8 = new Board_Board(new PlayingBoard($players__8, $newTable__1, $board__27->DrawPile, $board__27->DiscardPile, $board__27->Barns, $board__27->HayBales, $board__27->Goal, $board__27->UseGameOver));
                            return new UndoableBoard($newBoard__8, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, false);
                        case 'Event_Undone':
                            return new UndoableBoard($state__6->UndoPoint, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, true);
                        default:
                            $board__28 = $state__6->Board->Item;
                            $e__19 = $event__2->Item2;
                            $playerid__14 = $event__2->Item1;
                            $player__64 = Shared_002EPlayer___evolve(Map::FSharpMap__get_Item__2B595($board__28->Players, $playerid__14), $e__19);
                            switch (get_class($e__19))
                            {
                                case 'Event_BonusDiscarded':
                                    $card__8 = $e__19->Item;
                                    $_target__208 = 0;
                                    break;
                                case 'Event_CardDiscarded':
                                    $card__8 = $e__19->Item;
                                    $_target__208 = 0;
                                    break;
                                default:
                                    $_target__208 = 1;
                                    break;
                            }
                            switch ($_target__208)
                            {
                                case 0:
                                    $newDiscardPile = new Cons($card__8, $board__28->DiscardPile);
                                    break;
                                case 1:
                                    $newDiscardPile = $board__28->DiscardPile;
                                    break;
                            }
                            $newBoard__9 = new Board_Board((function () use ($board__28, $newDiscardPile, $player__64, $playerid__14) { 
                                $Players__1 = Map::add($playerid__14, $player__64, $board__28->Players);
                                return new PlayingBoard($Players__1, $board__28->Table, $board__28->DrawPile, $newDiscardPile, $board__28->Barns, $board__28->HayBales, $board__28->Goal, $board__28->UseGameOver);
                            })());
                            return new UndoableBoard($newBoard__9, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, false);
                    }
                }                 else {
                    switch (get_class($event__2))
                    {
                        case 'BoardEvent_PlayerDrewCards':
                            $board__20 = $state__6->Board->Item;
                            $e__16 = $event__2->Item;
                            switch (get_class($board__20->DrawPile))
                            {
                                case 'Hand_PrivateHand':
                                    $newDrawPile = new Hand_PrivateHand(($board__20->DrawPile->cards - Shared_002EHandModule___count($e__16->Cards)));
                                    break;
                                default:
                                    $arg0__5 = Shared_002EDrawPile___remove($e__16->Cards, $board__20->DrawPile->cards);
                                    $newDrawPile = new Hand_PublicHand($arg0__5);
                                    break;
                            }
                            $player__62 = Map::FSharpMap__get_Item__2B595($board__20->Players, $e__16->Player);
                            $player__63 = Shared_002EPlayer___takeCards($e__16->Cards, $player__62);
                            $newBoard__1 = new Board_Board((function () use ($board__20, $e__16, $newDrawPile, $player__63) { 
                                $Players = Map::add($e__16->Player, $player__63, $board__20->Players);
                                return new PlayingBoard($Players, $board__20->Table, $newDrawPile, $board__20->DiscardPile, $board__20->Barns, $board__20->HayBales, $board__20->Goal, $board__20->UseGameOver);
                            })());
                            if ($state__6->UndoType instanceof UndoType_FullUndo) {
                                return new UndoableBoard($newBoard__1, $state__6->UndoPoint, $state__6->UndoType, true, false);
                            }                             else {
                                return new UndoableBoard($newBoard__1, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, $state__6->AtUndoPoint);
                            }
                        case 'BoardEvent_UndoCheckPointed':
                            return new UndoableBoard($state__6->Board, $state__6->Board, $state__6->UndoType, false, true);
                        case 'BoardEvent_HayBalesPlaced':
                            $added = $event__2->added;
                            $board__21 = $state__6->Board->Item;
                            $removed = $event__2->removed;
                            $newBoard__2 = new Board_Board((function () use ($added, $board__21, $removed) { 
                                $HayBales = Set::FSharpSet___op_Addition(Set::FSharpSet___op_Subtraction($board__21->HayBales, Set::ofSeq($removed, [ 'Compare' => function ($_x__100, $_y__101) {                                 return $_x__100->CompareTo($_y__101);
 }])), Set::ofSeq($added, [ 'Compare' => function ($_x__102, $_y__103) {                                 return $_x__102->CompareTo($_y__103);
 }]));
                                return new PlayingBoard($board__21->Players, $board__21->Table, $board__21->DrawPile, $board__21->DiscardPile, $board__21->Barns, $HayBales, $board__21->Goal, $board__21->UseGameOver);
                            })());
                            return new UndoableBoard($newBoard__2, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, false);
                        case 'BoardEvent_HayBaleDynamited':
                            $board__22 = $state__6->Board->Item;
                            $p__94 = $event__2->Item;
                            $newBoard__3 = new Board_Board((function () use ($board__22, $p__94) { 
                                $HayBales__1 = Set::remove($p__94, $board__22->HayBales);
                                return new PlayingBoard($board__22->Players, $board__22->Table, $board__22->DrawPile, $board__22->DiscardPile, $board__22->Barns, $HayBales__1, $board__22->Goal, $board__22->UseGameOver);
                            })());
                            return new UndoableBoard($newBoard__3, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, false);
                        case 'BoardEvent_DiscardPileShuffled':
                            $board__23 = $state__6->Board->Item;
                            $cards__6 = $event__2->Item;
                            $newBoard__4 = new Board_Board((function () use ($board__23, $cards__6) { 
                                switch (get_class($board__23->DrawPile))
                                {
                                    case 'Hand_PrivateHand':
                                        $DrawPile = new Hand_PrivateHand(($board__23->DrawPile->cards + FSharpList::length($cards__6)));
                                        break;
                                    default:
                                        $DrawPile = new Hand_PublicHand(FSharpList::append($board__23->DrawPile->cards, $cards__6));
                                        break;
                                }
                                $DiscardPile = FSharpList::get_Nil();
                                return new PlayingBoard($board__23->Players, $board__23->Table, $DrawPile, $DiscardPile, $board__23->Barns, $board__23->HayBales, $board__23->Goal, $board__23->UseGameOver);
                            })());
                            return new UndoableBoard($newBoard__4, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, false);
                        case 'BoardEvent_DrawPileShuffled':
                            $board__24 = $state__6->Board->Item;
                            $cards__7 = $event__2->Item;
                            $newBoard__5 = new Board_Board((function () use ($board__24, $cards__7) { 
                                switch (get_class($board__24->DrawPile))
                                {
                                    case 'Hand_PrivateHand':
                                        $DrawPile__1 = $board__24->DrawPile;
                                        break;
                                    default:
                                        $DrawPile__1 = new Hand_PublicHand($cards__7);
                                        break;
                                }
                                return new PlayingBoard($board__24->Players, $board__24->Table, $DrawPile__1, $board__24->DiscardPile, $board__24->Barns, $board__24->HayBales, $board__24->Goal, $board__24->UseGameOver);
                            })());
                            return new UndoableBoard($newBoard__5, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, false);
                        case 'BoardEvent_Next':
                            $board__29 = $state__6->Board->Item;
                            $nextTable = Shared_002EGameTable__get_Next($board__29->Table, NULL);
                            $player__65 = Shared_002EPlayer___startTurn(Map::FSharpMap__get_Item__2B595($board__29->Players, Shared_002EGameTable__get_Player($nextTable, NULL)));
                            $newBoard__10 = new Board_Board(new PlayingBoard(Map::add(Shared_002EGameTable__get_Player($nextTable, NULL), $player__65, $board__29->Players), $nextTable, $board__29->DrawPile, $board__29->DiscardPile, $board__29->Barns, $board__29->HayBales, $board__29->Goal, $board__29->UseGameOver));
                            return new UndoableBoard($newBoard__10, $newBoard__10, $state__6->UndoType, false, true);
                        default:
                            return $state__6;
                    }
                }
            }
        }
    }     else {
        if ($state__6->Board instanceof Board_Won) {
            if ($event__2 instanceof BoardEvent_Played) {
                switch (get_class($event__2->Item2))
                {
                    case 'Event_Undone':
                        return new UndoableBoard($state__6->UndoPoint, $state__6->UndoPoint, $state__6->UndoType, $state__6->ShouldShuffle, true);
                    default:
                        return $state__6;
                }
            }             else {
                return $state__6;
            }
        }         else {
            switch (get_class($event__2))
            {
                case 'BoardEvent_Started':
                    $s__3 = $event__2->Item;
                    $_target__209 = 0;
                    break;
                case 'BoardEvent_Played':
                    $_target__209 = 1;
                    break;
                default:
                    $_target__209 = 1;
                    break;
            }
            switch ($_target__209)
            {
                case 0:
                    $board__15 = new Board_Board(new PlayingBoard(Map::ofList(FSharpList::ofSeq(Seq::delay(function ($unitVar__57) use ($s__3) {                     return Seq::collect(function ($matchValue__48) {                     return Seq::singleton([ $matchValue__48[1], new CrazyPlayer_Starting(new Starting($matchValue__48[0], $matchValue__48[3], new Hand_PublicHand(FSharpList::get_Nil()), $GLOBALS['Shared_002EBonusModule___empty']))]);
 }, $s__3->Players);
 })), [ 'Compare' => 'Util::comparePrimitives']), Shared_002ETable___start(FSharpList::ofSeq(Seq::delay(function ($unitVar__58) use ($s__3) {                     return Seq::collect(function ($matchValue__49) {                     return Seq::singleton([ $matchValue__49[1], $matchValue__49[2]]);
 }, $s__3->Players);
 }))), new Hand_PublicHand($s__3->DrawPile), FSharpList::get_Nil(), Shared_002EBarnsModule___init($s__3->Barns), Set::empty([ 'Compare' => function ($_x__98, $_y__99) {                     return $_x__98->CompareTo($_y__99);
 }]), $s__3->Goal, $s__3->UseGameOver));
                    return new UndoableBoard($board__15, $board__15, $s__3->Undo, false, false);
                case 1:
                    return $state__6;
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                case 9:
                case 10:
                case 11:
                case 12:
                case 13:
                case 14:
                case 15:
                case 16:
                case 17:
                case 18:
                case 19:
            }
        }
    }
}

#222
function Shared_002EBoardModule___cont($f__3, $board__30, $events) {
    if ($board__30->Board instanceof Board_Board) {
        $newEvents = $f__3($board__30->Board->Item, $events);
        $newBoard__11 = FSharpList::fold('Shared_002EBoardModule___evolve', $board__30, $newEvents);
        return [ $newBoard__11, FSharpList::append($events, $newEvents)];
    }     else {
        return [ $board__30, $events];
    }
}

#223
function Shared_002EBoardModule___decide($cmd__5, $state__8) {
    if ($state__8->Board instanceof Board_InitialState) {
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
                                    $patternInput__11 = [ new Cons([ $c1__2, $u1__2, $n1__2, Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NE'])], new Cons([ $c2__2, $u2__2, $n2__2, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___NW']))], new Cons([ $c3__1, $u3__1, $n3__1, Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___SW']), $GLOBALS['Shared_002EAxeModule___S'])], new Cons([ $c4, $u4, $n4, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___SE']))], FSharpList::get_Nil())))), new Cons($GLOBALS['Shared_002EParcelModule___center'], new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NW']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___S']), $GLOBALS['Shared_002EAxeModule___SE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___NE'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___SW'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___N'])), $GLOBALS['Shared_002EAxeModule___NE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___S'])), $GLOBALS['Shared_002EAxeModule___SW']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___E2']), $GLOBALS['Shared_002EAxeModule___SE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___W2']), $GLOBALS['Shared_002EAxeModule___NW']), FSharpList::get_Nil()))))))))), $cmd__6->Goal];
                                }                                 else {
                                    $playerCount = FSharpList::length($cmd__6->Players);
                                    if ($playerCount < 2) {
                                        throw new Exception('To few players');
                                    }                                     else {
                                        throw new Exception('To many players');
                                    }
                                }
                            }                             else {
                                $c1__1 = $cmd__6->Players->value[0];
                                $c2__1 = $cmd__6->Players->next->value[0];
                                $c3 = $cmd__6->Players->next->next->value[0];
                                $n1__1 = $cmd__6->Players->value[2];
                                $n2__1 = $cmd__6->Players->next->value[2];
                                $n3 = $cmd__6->Players->next->next->value[2];
                                $u1__1 = $cmd__6->Players->value[1];
                                $u2__1 = $cmd__6->Players->next->value[1];
                                $u3 = $cmd__6->Players->next->next->value[1];
                                $patternInput__11 = [ new Cons([ $c1__1, $u1__1, $n1__1, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___N']))], new Cons([ $c2__1, $u2__1, $n2__1, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___SW']))], new Cons([ $c3, $u3, $n3, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___SE']))], FSharpList::get_Nil()))), new Cons($GLOBALS['Shared_002EParcelModule___center'], new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___N'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___S'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___NE'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___NW'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___SE'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___SW'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___W2']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___E2']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NW']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___S']), $GLOBALS['Shared_002EAxeModule___SE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___S']), $GLOBALS['Shared_002EAxeModule___SW']), FSharpList::get_Nil()))))))))))))), $cmd__6->Goal];
                            }
                        }                         else {
                            $c1 = $cmd__6->Players->value[0];
                            $c2 = $cmd__6->Players->next->value[0];
                            $n1 = $cmd__6->Players->value[2];
                            $n2 = $cmd__6->Players->next->value[2];
                            $u1 = $cmd__6->Players->value[1];
                            $u2 = $cmd__6->Players->next->value[1];
                            $patternInput__11 = [ new Cons([ $c1, $u1, $n1, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___N']))], new Cons([ $c2, $u2, $n2, Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(2, $GLOBALS['Shared_002EAxeModule___S']))], FSharpList::get_Nil())), new Cons($GLOBALS['Shared_002EParcelModule___center'], new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___N'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___S'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___NE'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___NW'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___SE'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], Shared_002EAxe___op_Multiply__Z425F7B5E(3, $GLOBALS['Shared_002EAxeModule___SW'])), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___W2']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___E2']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___N']), $GLOBALS['Shared_002EAxeModule___NW']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___S']), $GLOBALS['Shared_002EAxeModule___SE']), new Cons(Shared_002EParcel___op_Addition__ZF6EFE4B(Shared_002EParcel___op_Addition__ZF6EFE4B($GLOBALS['Shared_002EParcelModule___center'], $GLOBALS['Shared_002EAxeModule___S']), $GLOBALS['Shared_002EAxeModule___SW']), FSharpList::get_Nil()))))))))))))), $cmd__6->Goal];
                        }
                    }                     else {
                        $playerCount = FSharpList::length($cmd__6->Players);
                        if ($playerCount < 2) {
                            throw new Exception('To few players');
                        }                         else {
                            throw new Exception('To many players');
                        }
                    }
                }                 else {
                    $playerCount = FSharpList::length($cmd__6->Players);
                    if ($playerCount < 2) {
                        throw new Exception('To few players');
                    }                     else {
                        throw new Exception('To many players');
                    }
                }
                return new Cons(new BoardEvent_Started(new BoardStarted($patternInput__11[0], Shared_002EDrawPile___shuffle($cmd__6->UseGameOver, $GLOBALS['Shared_002EDrawPile___cards']), $patternInput__11[1], $patternInput__11[2], $cmd__6->Undo, $cmd__6->UseGameOver)), FSharpList::get_Nil());
            default:
                return FSharpList::get_Nil();
        }
    }     else {
        if ($state__8->Board instanceof Board_Board) {
            if ($cmd__5 instanceof BoardCommand_Play) {
                switch (get_class($cmd__5->Item2))
                {
                    case 'Command_EndTurn':
                        $board__31 = $state__8->Board->Item;
                        $playerId__2 = $cmd__5->Item1;
                        if (Shared_002EGameTable__get_Player($board__31->Table, NULL) === $playerId__2) {
                            $player__66 = Map::FSharpMap__get_Item__2B595($board__31->Players, $playerId__2);
                            if ($player__66 instanceof CrazyPlayer_Playing) {
                                if (!(Shared_002EPlayer___canMove($playerId__2, $state__8->Board) ? true : Shared_002EHandModule___shouldDiscard($player__66->Item->Hand))) {
                                    $p__97 = $player__66->Item;
                                    return Shared_002EBoardModule___next($state__8->ShouldShuffle, $board__31);
                                }                                 else {
                                    return FSharpList::get_Nil();
                                }
                            }                             else {
                                return FSharpList::get_Nil();
                            }
                        }                         else {
                            return FSharpList::get_Nil();
                        }
                    case 'Command_Undo':
                        $board__32 = $state__8->Board->Item;
                        $playerId__3 = $cmd__5->Item1;
                        if ((Shared_002EGameTable__get_Player($board__32->Table, NULL) === $playerId__3 ? !Util::equals($state__8->UndoType, new UndoType_NoUndo()) : false) ? !$state__8->AtUndoPoint : false) {
                            return new Cons(new BoardEvent_Played($playerId__3, new Event_Undone()), FSharpList::get_Nil());
                        }                         else {
                            return FSharpList::get_Nil();
                        }
                    case 'Command_Discard':
                        $board__33 = $state__8->Board->Item;
                        $card__9 = $cmd__5->Item2->Item;
                        $cmd__7 = $cmd__5->Item2;
                        $playerId__4 = $cmd__5->Item1;
                        if (Shared_002EGameTable__get_Player($board__33->Table, NULL) === $playerId__4) {
                            $player__67 = Map::FSharpMap__get_Item__2B595($board__33->Players, $playerId__4);
                            $others = Shared_002EPlayer___otherPlayers($playerId__4, $board__33);
                            $events__1 = Shared_002EPlayer___decide($others, $board__33->Barns, $board__33->HayBales, function ($unitVar0__1) use ($board__33) {                             return Shared_002EBoardModule___bribeParcels($board__33);
 }, $cmd__7, $player__67);
                            return FSharpList::ofSeq(Seq::delay(function ($unitVar__59) use ($board__33, $card__9, $events__1, $playerId__4, $player__67, $state__8) {                             return Seq::append(Seq::map(function ($e__20) use ($playerId__4) {                             return new BoardEvent_Played($playerId__4, $e__20);
 }, $events__1), Seq::delay(function ($unitVar__60) use ($board__33, $card__9, $events__1, $player__67, $state__8) {                             if ($player__67 instanceof CrazyPlayer_Playing) {
                                if (((FSharpList::exists(function ($_arg1__62) {                                 if ($_arg1__62 instanceof Event_CardDiscarded) {
                                    return true;
                                }                                 else {
                                    return false;
                                }
 }, $events__1) ? !Shared_002EHandModule___shouldDiscard(Shared_002EHandModule___remove($card__9, $player__67->Item->Hand)) : false) ? !Shared_002EMovesModule___canMove($player__67->Item->Moves) : false) ? Util::equals($state__8->UndoType, new UndoType_NoUndo()) : false) {
                                    $p__99 = $player__67->Item;
                                    return Shared_002EBoardModule___next($state__8->ShouldShuffle, $board__33);
                                }                                 else {
                                    return Seq::empty();
                                }
                            }                             else {
                                return Seq::empty();
                            }
 }));
 }));
                        }                         else {
                            return FSharpList::get_Nil();
                        }
                    default:
                        $board__34 = $state__8->Board->Item;
                        $cmd__8 = $cmd__5->Item2;
                        $playerid__15 = $cmd__5->Item1;
                        $player__68 = Map::FSharpMap__get_Item__2B595($board__34->Players, $playerid__15);
                        $others__1 = Shared_002EPlayer___otherPlayers($playerid__15, $board__34);
                        if ($playerid__15 === Shared_002EGameTable__get_Player($board__34->Table, NULL)) {
                            $tupledArg__11 = [ $state__8, FSharpList::get_Nil()];
                            $tupledArg__12 = Shared_002EBoardModule___cont(function ($board__35, $_arg1__63) use ($cmd__8, $others__1, $player__68, $playerid__15) { 
                                $events__2 = Shared_002EPlayer___decide($others__1, $board__35->Barns, $board__35->HayBales, function ($unitVar0__2) use ($board__35) {                                 return Shared_002EBoardModule___bribeParcels($board__35);
 }, $cmd__8, $player__68);
                                return FSharpList::ofSeq(Seq::delay(function ($unitVar__61) use ($events__2, $playerid__15) {                                 return Seq::append(Seq::map(function ($e__21) use ($playerid__15) {                                 return new BoardEvent_Played($playerid__15, $e__21);
 }, $events__2), Seq::delay(function ($unitVar__62) use ($events__2) {                                 return Seq::collect(function ($e__22) {                                 if ($e__22 instanceof Event_CardPlayed) {
                                    switch (get_class($e__22->Item))
                                    {
                                        case 'PlayCard_PlayRut':
                                            $victim__1 = $e__22->Item->victim;
                                            return Seq::singleton(new BoardEvent_Played($victim__1, new Event_Rutted()));
                                        case 'PlayCard_PlayHayBale':
                                            $added__1 = $e__22->Item->path;
                                            $removed__1 = $e__22->Item->moved;
                                            return Seq::singleton(new BoardEvent_HayBalesPlaced($added__1, $removed__1));
                                        case 'PlayCard_PlayDynamite':
                                            $bale = $e__22->Item->path;
                                            return Seq::singleton(new BoardEvent_HayBaleDynamited($bale));
                                        default:
                                            return Seq::empty();
                                    }
                                }                                 else {
                                    return Seq::empty();
                                }
 }, $events__2);
 }));
 }));
                            }, $tupledArg__11[0], $tupledArg__11[1]);
                            $tupledArg__13 = Shared_002EBoardModule___cont(function ($board__37, $_arg2__9) use ($playerid__15) { 
                                $player__69 = Map::FSharpMap__get_Item__2B595($board__37->Players, $playerid__15);
                                if ($player__69 instanceof CrazyPlayer_Playing) {
                                    $matchValue__58 = Shared_002EPlayer___fullAnnexation($player__69->Item->Field, $player__69->Item->Fence, $player__69->Item->Tractor);
                                    if (is_null($matchValue__58)) {
                                        return FSharpList::get_Nil();
                                    }                                     else {
                                        $surrounded = $matchValue__58[0];
                                        $newLength = $matchValue__58[1];
                                        if (Shared_002EFieldModule___isEmpty($surrounded)) {
                                            return new Cons(new BoardEvent_Played($playerid__15, new Event_FenceReduced(new FenceReduced($newLength))), FSharpList::get_Nil());
                                        }                                         else {
                                            $protectedField = Shared_002EFieldModule___unionMany(FSharpList::ofSeq(Seq::delay(function ($unitVar__63) use ($board__37, $playerid__15) {                                             return Seq::collect(function ($matchValue__59) use ($playerid__15) {                                             if ($matchValue__59[1] instanceof CrazyPlayer_Playing) {
                                                if ($matchValue__59[0] !== $playerid__15 ? $matchValue__59[1]->Item->Bonus->Watched : false) {
                                                    $p__102 = $matchValue__59[1]->Item;
                                                    return Seq::singleton($p__102->Field);
                                                }                                                 else {
                                                    return Seq::empty();
                                                }
                                            }                                             else {
                                                return Seq::empty();
                                            }
 }, Map::toSeq($board__37->Players));
 })));
                                            $annexed__1 = Shared_002EField___op_Subtraction__Z24735800($surrounded, $protectedField);
                                            $lostFields = FSharpList::ofSeq(Seq::delay(function ($unitVar__64) use ($annexed__1, $board__37, $playerid__15) {                                             return Seq::collect(function ($matchValue__60) use ($annexed__1, $playerid__15) {                                             if ($matchValue__60[1] instanceof CrazyPlayer_Playing) {
                                                if ($matchValue__60[0] !== $playerid__15) {
                                                    $p__105 = $matchValue__60[1]->Item;
                                                    $lost = Shared_002EFieldModule___intersect($annexed__1, $p__105->Field);
                                                    if (!Shared_002EFieldModule___isEmpty($lost)) {
                                                        return Seq::singleton([ $matchValue__60[0], Shared_002EFieldModule___parcels($lost)]);
                                                    }                                                     else {
                                                        return Seq::empty();
                                                    }
                                                }                                                 else {
                                                    return Seq::empty();
                                                }
                                            }                                             else {
                                                return Seq::empty();
                                            }
 }, Map::toSeq($board__37->Players));
 }));
                                            $annexedBarns__1 = Shared_002EBarnsModule___intersectWith($annexed__1, $board__37->Barns);
                                            return new Cons(new BoardEvent_Played($playerid__15, new Event_Annexed(new Annexed(Shared_002EFieldModule___parcels($annexed__1), $lostFields, Shared_002EFieldModule___parcels($annexedBarns__1->Free), Shared_002EFieldModule___parcels($annexedBarns__1->Occupied), $newLength))), FSharpList::get_Nil());
                                        }
                                    }
                                }                                 else {
                                    return FSharpList::get_Nil();
                                }
                            }, $tupledArg__12[0], $tupledArg__12[1]);
                            $tupledArg__14 = Shared_002EBoardModule___cont(function ($board__39, $_arg3__3) {                             return FSharpList::ofSeq(Seq::delay(function ($unitVar__65) use ($board__39) {                             return Seq::collect(function ($matchValue__61) { 
                                $activePatternResult1508 = $matchValue__61;
                                if (Shared_002EFieldModule___isEmpty(Shared_002EPlayer___field($activePatternResult1508[1])) ? !Shared_002EPlayer___isKo($activePatternResult1508[1]) : false) {
                                    return Seq::append(Seq::singleton(new BoardEvent_Played($activePatternResult1508[0], new Event_Eliminated())), Seq::delay(function ($unitVar__66) {                                     return Seq::singleton(new BoardEvent_UndoCheckPointed());
 }));
                                }                                 else {
                                    return Seq::empty();
                                }
                            }, $board__39->Players);
 }));
 }, $tupledArg__13[0], $tupledArg__13[1]);
                            $tupledArg__16 = Shared_002EBoardModule___cont(function ($board__41, $_arg4__2) use ($playerid__15) {                             return FSharpList::ofSeq(Seq::delay(function ($unitVar__67) use ($board__41, $playerid__15) {                             return Seq::collect(function ($matchValue__62) use ($playerid__15) {                             if ($matchValue__62[1] instanceof CrazyPlayer_Playing) {
                                switch (get_class($matchValue__62[1]->Item->Power))
                                {
                                    case 'Power_PowerUp':
                                        $player__72 = $matchValue__62[1]->Item;
                                        $start__7 = Shared_002EFenceModule___start($player__72->Tractor, $player__72->Fence);
                                        if (!Shared_002ECrossroadModule___isInField($player__72->Field, $start__7)) {
                                            return Seq::singleton(new BoardEvent_Played($playerid__15, new Event_CutFence(new CutFence($matchValue__62[0]))));
                                        }                                         else {
                                            return Seq::empty();
                                        }
                                    default:
                                        $player__71 = $matchValue__62[1]->Item;
                                        if (Shared_002ECrossroadModule___isInField($player__71->Field, $player__71->Tractor)) {
                                            return Seq::singleton(new BoardEvent_Played($matchValue__62[0], new Event_PoweredUp()));
                                        }                                         else {
                                            return Seq::empty();
                                        }
                                }
                            }                             else {
                                return Seq::empty();
                            }
 }, Map::toSeq($board__41->Players));
 }));
 }, $tupledArg__14[0], $tupledArg__14[1]);
                            $tupledArg__17 = Shared_002EBoardModule___cont(function ($board__43, $_arg5) { 
                                $source__7 = Map::toSeq($board__43->Players);
                                $source__8 = Seq::choose(function ($tupledArg__15) {                                 if ($tupledArg__15[1] instanceof CrazyPlayer_Ko) {
                                    return NULL;
                                }                                 else {
                                    return $tupledArg__15[0];
                                }
 }, $source__7);
                                $remainingPlayers = FSharpList::ofSeq($source__8);
                                if ($remainingPlayers instanceof Cons) {
                                    if ($remainingPlayers->next instanceof Nil) {
                                        $winner = $remainingPlayers->value;
                                        return new Cons(new BoardEvent_GameWon($winner), FSharpList::get_Nil());
                                    }                                     else {
                                        return FSharpList::get_Nil();
                                    }
                                }                                 else {
                                    return FSharpList::get_Nil();
                                }
                            }, $tupledArg__16[0], $tupledArg__16[1]);
                            $tupledArg__18 = Shared_002EBoardModule___cont(function ($board__45, $es) { 
                                $matchValue__63 = FSharpList::tryFind(function ($_arg2__10) {                                 if ($_arg2__10 instanceof BoardEvent_Played) {
                                    switch (get_class($_arg2__10->Item2))
                                    {
                                        case 'Event_Annexed':
                                            return true;
                                        default:
                                            return false;
                                    }
                                }                                 else {
                                    return false;
                                }
 }, $es);
                                if (!is_null($matchValue__63)) {
                                    if ($matchValue__63 instanceof BoardEvent_Played) {
                                        switch (get_class($matchValue__63->Item2))
                                        {
                                            case 'Event_Annexed':
                                                $e__23 = $matchValue__63->Item2->Item;
                                                $cardsToTake = FSharpList::length($e__23->FreeBarns) + 2 * FSharpList::length($e__23->OccupiedBarns);
                                                if ($cardsToTake > Shared_002EHandModule___count($board__45->DrawPile)) {
                                                    return new Cons(new BoardEvent_DiscardPileShuffled(Shared_002EDrawPile___shuffle($board__45->UseGameOver, $board__45->DiscardPile)), FSharpList::get_Nil());
                                                }                                                 else {
                                                    return FSharpList::get_Nil();
                                                }
                                            default:
                                                return FSharpList::get_Nil();
                                        }
                                    }                                     else {
                                        return FSharpList::get_Nil();
                                    }
                                }                                 else {
                                    return FSharpList::get_Nil();
                                }
                            }, $tupledArg__17[0], $tupledArg__17[1]);
                            $tupledArg__19 = Shared_002EBoardModule___cont(function ($board__47, $es__1) use ($playerid__15, $state__8) { 
                                $matchValue__64 = Shared_002EBoardModule___tryFindWinner($board__47);
                                switch (get_class($matchValue__64))
                                {
                                    case 'FindWinner_Lead':
                                        $matchValue__65 = FSharpList::tryFind(function ($_arg3__4) {                                         if ($_arg3__4 instanceof BoardEvent_Played) {
                                            switch (get_class($_arg3__4->Item2))
                                            {
                                                case 'Event_Annexed':
                                                    return true;
                                                default:
                                                    return false;
                                            }
                                        }                                         else {
                                            return false;
                                        }
 }, $es__1);
                                        if (!is_null($matchValue__65)) {
                                            if ($matchValue__65 instanceof BoardEvent_Played) {
                                                switch (get_class($matchValue__65->Item2))
                                                {
                                                    case 'Event_Annexed':
                                                        $e__24 = $matchValue__65->Item2->Item;
                                                        $player__73 = $matchValue__65->Item1;
                                                        $cardsToTake__1 = FSharpList::length($e__24->FreeBarns) + 2 * FSharpList::length($e__24->OccupiedBarns);
                                                        return FSharpList::ofSeq(Seq::delay(function ($unitVar__68) use ($board__47, $cardsToTake__1, $matchValue__64, $playerid__15, $state__8) {                                                         if ($cardsToTake__1 > 0) {
                                                            switch (get_class($board__47->DrawPile))
                                                            {
                                                                case 'Hand_PrivateHand':
                                                                    return Seq::empty();
                                                                default:
                                                                    $cardsDrawn = Shared_002EDrawPile___take($cardsToTake__1, $board__47->DrawPile->cards);
                                                                    return Seq::append(Seq::singleton(new BoardEvent_PlayerDrewCards(new PlayerDrewCards($playerid__15, new Hand_PublicHand($cardsDrawn)))), Seq::delay(function ($unitVar__69) use ($cardsDrawn, $matchValue__64, $playerid__15, $state__8) {                                                                     if (FSharpList::contains(new Card_GameOver(), $cardsDrawn, [ 'Equals' => 'Util::equals', 'GetHashCode' => 'Util::structuralHash'])) {
                                                                        return Seq::append(Seq::singleton(new BoardEvent_Played($playerid__15, new Event_CardPlayed(new PlayCard_PlayGameOver()))), Seq::delay(function ($unitVar__70) use ($matchValue__64) {                                                                         if ($matchValue__64->Item instanceof Cons) {
                                                                            if ($matchValue__64->Item->next instanceof Nil) {
                                                                                $win__1 = $matchValue__64->Item->value;
                                                                                return Seq::singleton(new BoardEvent_GameWon($win__1));
                                                                            }                                                                             else {
                                                                                return Seq::singleton(new BoardEvent_GameEnded($matchValue__64->Item));
                                                                            }
                                                                        }                                                                         else {
                                                                            return Seq::singleton(new BoardEvent_GameEnded($matchValue__64->Item));
                                                                        }
 }));
                                                                    }                                                                     else {
                                                                        if (Util::equals($state__8->UndoType, new UndoType_DontUndoCards())) {
                                                                            return Seq::singleton(new BoardEvent_UndoCheckPointed());
                                                                        }                                                                         else {
                                                                            return Seq::empty();
                                                                        }
                                                                    }
 }));
                                                            }
                                                        }                                                         else {
                                                            $player__74 = Map::FSharpMap__get_Item__2B595($board__47->Players, $playerid__15);
                                                            if ($player__74 instanceof CrazyPlayer_Playing) {
                                                                if (!(Shared_002EMovesModule___canMove($player__74->Item->Moves) ? true : Shared_002EHandModule___canPlay($player__74->Item->Hand)) ? Util::equals($state__8->UndoType, new UndoType_NoUndo()) : false) {
                                                                    $p__110 = $player__74->Item;
                                                                    return Shared_002EBoardModule___next($state__8->ShouldShuffle, $board__47);
                                                                }                                                                 else {
                                                                    return Seq::empty();
                                                                }
                                                            }                                                             else {
                                                                return Seq::empty();
                                                            }
                                                        }
 }));
                                                    default:
                                                        $player__75 = Map::FSharpMap__get_Item__2B595($board__47->Players, $playerid__15);
                                                        if ($playerid__15 !== Shared_002EGameTable__get_Player($board__47->Table, NULL)) {
                                                            $nextPlayerId__1 = Shared_002EGameTable__get_Player($board__47->Table, NULL);
                                                            $nextPlayer__2 = Map::FSharpMap__get_Item__2B595($board__47->Players, $nextPlayerId__1);
                                                            return FSharpList::ofSeq(Seq::delay(function ($unitVar__71) use ($nextPlayerId__1, $nextPlayer__2) {                                                             return Seq::append((function () use ($nextPlayerId__1, $nextPlayer__2) { 
                                                                $list__30 = Shared_002EBonusModule___startTurn(Shared_002EPlayer___bonus($nextPlayer__2));
                                                                return FSharpList::map(function ($c__26) use ($nextPlayerId__1) {                                                                 return new BoardEvent_Played($nextPlayerId__1, new Event_BonusDiscarded($c__26));
 }, $list__30);
                                                            })(), Seq::delay(function ($unitVar__72) {                                                             return Seq::singleton(new BoardEvent_UndoCheckPointed());
 }));
 }));
                                                        }                                                         else {
                                                            if ($player__75 instanceof CrazyPlayer_Playing) {
                                                                if (!(Shared_002EMovesModule___canMove($player__75->Item->Moves) ? true : Shared_002EHandModule___canPlay($player__75->Item->Hand)) ? Util::equals($state__8->UndoType, new UndoType_NoUndo()) : false) {
                                                                    $p__112 = $player__75->Item;
                                                                    return Shared_002EBoardModule___next($state__8->ShouldShuffle, $board__47);
                                                                }                                                                 else {
                                                                    return FSharpList::get_Nil();
                                                                }
                                                            }                                                             else {
                                                                return FSharpList::get_Nil();
                                                            }
                                                        }
                                                }
                                            }                                             else {
                                                $player__75 = Map::FSharpMap__get_Item__2B595($board__47->Players, $playerid__15);
                                                if ($playerid__15 !== Shared_002EGameTable__get_Player($board__47->Table, NULL)) {
                                                    $nextPlayerId__1 = Shared_002EGameTable__get_Player($board__47->Table, NULL);
                                                    $nextPlayer__2 = Map::FSharpMap__get_Item__2B595($board__47->Players, $nextPlayerId__1);
                                                    return FSharpList::ofSeq(Seq::delay(function ($unitVar__71) use ($nextPlayerId__1, $nextPlayer__2) {                                                     return Seq::append((function () use ($nextPlayerId__1, $nextPlayer__2) { 
                                                        $list__30 = Shared_002EBonusModule___startTurn(Shared_002EPlayer___bonus($nextPlayer__2));
                                                        return FSharpList::map(function ($c__26) use ($nextPlayerId__1) {                                                         return new BoardEvent_Played($nextPlayerId__1, new Event_BonusDiscarded($c__26));
 }, $list__30);
                                                    })(), Seq::delay(function ($unitVar__72) {                                                     return Seq::singleton(new BoardEvent_UndoCheckPointed());
 }));
 }));
                                                }                                                 else {
                                                    if ($player__75 instanceof CrazyPlayer_Playing) {
                                                        if (!(Shared_002EMovesModule___canMove($player__75->Item->Moves) ? true : Shared_002EHandModule___canPlay($player__75->Item->Hand)) ? Util::equals($state__8->UndoType, new UndoType_NoUndo()) : false) {
                                                            $p__112 = $player__75->Item;
                                                            return Shared_002EBoardModule___next($state__8->ShouldShuffle, $board__47);
                                                        }                                                         else {
                                                            return FSharpList::get_Nil();
                                                        }
                                                    }                                                     else {
                                                        return FSharpList::get_Nil();
                                                    }
                                                }
                                            }
                                        }                                         else {
                                            $player__75 = Map::FSharpMap__get_Item__2B595($board__47->Players, $playerid__15);
                                            if ($playerid__15 !== Shared_002EGameTable__get_Player($board__47->Table, NULL)) {
                                                $nextPlayerId__1 = Shared_002EGameTable__get_Player($board__47->Table, NULL);
                                                $nextPlayer__2 = Map::FSharpMap__get_Item__2B595($board__47->Players, $nextPlayerId__1);
                                                return FSharpList::ofSeq(Seq::delay(function ($unitVar__71) use ($nextPlayerId__1, $nextPlayer__2) {                                                 return Seq::append((function () use ($nextPlayerId__1, $nextPlayer__2) { 
                                                    $list__30 = Shared_002EBonusModule___startTurn(Shared_002EPlayer___bonus($nextPlayer__2));
                                                    return FSharpList::map(function ($c__26) use ($nextPlayerId__1) {                                                     return new BoardEvent_Played($nextPlayerId__1, new Event_BonusDiscarded($c__26));
 }, $list__30);
                                                })(), Seq::delay(function ($unitVar__72) {                                                 return Seq::singleton(new BoardEvent_UndoCheckPointed());
 }));
 }));
                                            }                                             else {
                                                if ($player__75 instanceof CrazyPlayer_Playing) {
                                                    if (!(Shared_002EMovesModule___canMove($player__75->Item->Moves) ? true : Shared_002EHandModule___canPlay($player__75->Item->Hand)) ? Util::equals($state__8->UndoType, new UndoType_NoUndo()) : false) {
                                                        $p__112 = $player__75->Item;
                                                        return Shared_002EBoardModule___next($state__8->ShouldShuffle, $board__47);
                                                    }                                                     else {
                                                        return FSharpList::get_Nil();
                                                    }
                                                }                                                 else {
                                                    return FSharpList::get_Nil();
                                                }
                                            }
                                        }
                                    default:
                                        if ($matchValue__64->Item instanceof Cons) {
                                            if ($matchValue__64->Item->next instanceof Nil) {
                                                $win = $matchValue__64->Item->value;
                                                return new Cons(new BoardEvent_GameWon($win), FSharpList::get_Nil());
                                            }                                             else {
                                                return new Cons(new BoardEvent_GameEnded($matchValue__64->Item), FSharpList::get_Nil());
                                            }
                                        }                                         else {
                                            return new Cons(new BoardEvent_GameEnded($matchValue__64->Item), FSharpList::get_Nil());
                                        }
                                }
                            }, $tupledArg__18[0], $tupledArg__18[1]);
                            return $tupledArg__19[1];
                        }                         else {
                            if ($cmd__8 instanceof Command_Quit) {
                                $tupledArg__20 = [ $state__8, FSharpList::get_Nil()];
                                $tupledArg__22 = Shared_002EBoardModule___cont(function ($board__49, $_arg7) use ($cmd__8, $others__1, $player__68, $playerid__15) { 
                                    $events__10 = Shared_002EPlayer___decide($others__1, $board__49->Barns, $board__49->HayBales, function ($unitVar0__3) use ($board__49) {                                     return Shared_002EBoardModule___bribeParcels($board__49);
 }, $cmd__8, $player__68);
                                    return FSharpList::ofSeq(Seq::delay(function ($unitVar__73) use ($events__10, $playerid__15) {                                     return Seq::map(function ($e__25) use ($playerid__15) {                                     return new BoardEvent_Played($playerid__15, $e__25);
 }, $events__10);
 }));
                                }, $tupledArg__20[0], $tupledArg__20[1]);
                                $tupledArg__23 = Shared_002EBoardModule___cont(function ($board__51, $_arg8) { 
                                    $source__9 = Map::toSeq($board__51->Players);
                                    $source__10 = Seq::choose(function ($tupledArg__21) {                                     if ($tupledArg__21[1] instanceof CrazyPlayer_Ko) {
                                        return NULL;
                                    }                                     else {
                                        return $tupledArg__21[0];
                                    }
 }, $source__9);
                                    $remainingPlayers__1 = FSharpList::ofSeq($source__10);
                                    if ($remainingPlayers__1 instanceof Cons) {
                                        if ($remainingPlayers__1->next instanceof Nil) {
                                            $winner__1 = $remainingPlayers__1->value;
                                            return new Cons(new BoardEvent_GameWon($winner__1), FSharpList::get_Nil());
                                        }                                         else {
                                            return FSharpList::get_Nil();
                                        }
                                    }                                     else {
                                        return FSharpList::get_Nil();
                                    }
                                }, $tupledArg__22[0], $tupledArg__22[1]);
                                return $tupledArg__23[1];
                            }                             else {
                                return FSharpList::get_Nil();
                            }
                        }
                }
            }             else {
                return FSharpList::get_Nil();
            }
        }         else {
            return FSharpList::get_Nil();
        }
    }
}

#224
function Shared_002EBoardModule___toState($board__53) {
    switch (get_class($board__53))
    {
        case 'Board_InitialState':
            return new BoardState([ ], new STable(NULL, NULL, NULL, 0), [ ], NULL, NULL, NULL, NULL, new Goal_Common(0), NULL, [ ], NULL);
        case 'Board_Won':
            return new BoardState((function () use ($board__53) { 
                $source__13 = Map::toSeq($board__53->Item2->Players);
                $source__14 = Seq::map(function ($tupledArg__25) {                 return [ $tupledArg__25[0], Shared_002EPlayer___toState($tupledArg__25[1])];
 }, $source__13);
                return FSharpArray::ofSeq($source__14);
            })(), new STable($board__53->Item2->Table->Players, $board__53->Item2->Table->AllPlayers, FSharpArray::ofSeq(Seq::delay(function ($unitVar__75) use ($board__53) {             return Seq::collect(function ($matchValue__68) { 
                $activePatternResult1549 = $matchValue__68;
                return Seq::singleton([ $activePatternResult1549[0], $activePatternResult1549[1]]);
            }, $board__53->Item2->Table->Names);
 })), $board__53->Item2->Table->Current), FSharpArray::ofList($board__53->Item2->DiscardPile), Shared_002EHandModule___count($board__53->Item2->DrawPile), (function () use ($board__53) { 
                $list__33 = Shared_002EFieldModule___parcels($board__53->Item2->Barns->Free);
                return FSharpArray::ofList($list__33);
            })(), (function () use ($board__53) { 
                $list__34 = Shared_002EFieldModule___parcels($board__53->Item2->Barns->Occupied);
                return FSharpArray::ofList($list__34);
            })(), Set::toArray($board__53->Item2->HayBales), $board__53->Item2->Goal, ($board__53->Item1 instanceof Cons ? ($board__53->Item1->next instanceof Nil ? (function ($winner__2) {             return $winner__2;
 })($board__53->Item1->value) : NULL) : NULL), ($board__53->Item1 instanceof Cons ? ($board__53->Item1->next instanceof Nil ? [ ] : FSharpArray::ofList($board__53->Item1)) : [ ]), $board__53->Item2->UseGameOver);
        default:
            return new BoardState((function () use ($board__53) { 
                $source__11 = Map::toSeq($board__53->Item->Players);
                $source__12 = Seq::map(function ($tupledArg__24) {                 return [ $tupledArg__24[0], Shared_002EPlayer___toState($tupledArg__24[1])];
 }, $source__11);
                return FSharpArray::ofSeq($source__12);
            })(), new STable($board__53->Item->Table->Players, $board__53->Item->Table->AllPlayers, FSharpArray::ofSeq(Seq::delay(function ($unitVar__74) use ($board__53) {             return Seq::collect(function ($matchValue__67) { 
                $activePatternResult1545 = $matchValue__67;
                return Seq::singleton([ $activePatternResult1545[0], $activePatternResult1545[1]]);
            }, $board__53->Item->Table->Names);
 })), $board__53->Item->Table->Current), FSharpArray::ofList($board__53->Item->DiscardPile), Shared_002EHandModule___count($board__53->Item->DrawPile), (function () use ($board__53) { 
                $list__31 = Shared_002EFieldModule___parcels($board__53->Item->Barns->Free);
                return FSharpArray::ofList($list__31);
            })(), (function () use ($board__53) { 
                $list__32 = Shared_002EFieldModule___parcels($board__53->Item->Barns->Occupied);
                return FSharpArray::ofList($list__32);
            })(), Set::toArray($board__53->Item->HayBales), $board__53->Item->Goal, NULL, [ ], $board__53->Item->UseGameOver);
    }
}

#225
function Shared_002EBoardModule___toUndoState($s__4) {
    return new UndoBoardState(Shared_002EBoardModule___toState($s__4->Board), Shared_002EBoardModule___toState($s__4->UndoPoint), ($s__4->UndoType instanceof UndoType_DontUndoCards ? 'DontUndoCards' : ($s__4->UndoType instanceof UndoType_NoUndo ? 'NoUndo' : 'FullUndo')), $s__4->ShouldShuffle, $s__4->AtUndoPoint);
}

#226
function Shared_002EBoardModule___ofState($board__56) {
    if (!FSharpArray::equalsWith('Util::compareArrays', $board__56->SPlayers, NULL) ? count($board__56->SPlayers) === 0 : false) {
        return new Board_InitialState();
    }     else {
        $state__9 = new PlayingBoard((function () use ($board__56) { 
            $elements__6 = Seq::map(function ($tupledArg__26) {             return [ $tupledArg__26[0], Shared_002EPlayer___ofState($tupledArg__26[1])];
 }, $board__56->SPlayers);
            return Map::ofSeq($elements__6, [ 'Compare' => 'Util::comparePrimitives']);
        })(), new GameTable($board__56->STable->SPlayers, $board__56->STable->SAllPlayers, Map::ofArray($board__56->STable->SNames, [ 'Compare' => 'Util::comparePrimitives']), $board__56->STable->SCurrent), (function () use ($board__56) { 
            $arg0__6 = Option::defaultArg($board__56->SDrawPile, 0);
            return new Hand_PrivateHand($arg0__6);
        })(), FSharpArray::toList($board__56->SDiscardPile), new Barns(Shared_002EFieldModule___ofParcels($board__56->SFreeBarns), Shared_002EFieldModule___ofParcels($board__56->SOccupiedBarns)), Set::ofSeq($board__56->SHayBales, [ 'Compare' => function ($_x__112, $_y__113) {         return $_x__112->CompareTo($_y__113);
 }]), $board__56->SGoal, Option::defaultArg($board__56->SUseGameOver, false));
        if ($board__56->SWinner === NULL) {
            if (FSharpArray::equalsWith('Util::comparePrimitives', $board__56->SWinners, NULL)) {
                return new Board_Board($state__9);
            }             else {
                if (!FSharpArray::equalsWith('Util::comparePrimitives', $board__56->SWinners, NULL) ? count($board__56->SWinners) === 0 : false) {
                    return new Board_Board($state__9);
                }                 else {
                    $winners__2 = $board__56->SWinners;
                    return new Board_Won(FSharpList::ofArray($winners__2), $state__9);
                }
            }
        }         else {
            if (!FSharpArray::equalsWith('Util::comparePrimitives', $board__56->SWinners, NULL) ? count($board__56->SWinners) === 0 : false) {
                $winner__3 = $board__56->SWinner;
                return new Board_Won(new Cons($winner__3, FSharpList::get_Nil()), $state__9);
            }             else {
                if (FSharpArray::equalsWith('Util::comparePrimitives', $board__56->SWinners, NULL)) {
                    $winner__3 = $board__56->SWinner;
                    return new Board_Won(new Cons($winner__3, FSharpList::get_Nil()), $state__9);
                }                 else {
                    $winners__2 = $board__56->SWinners;
                    return new Board_Won(FSharpList::ofArray($winners__2), $state__9);
                }
            }
        }
    }
}

#227
function Shared_002EBoardModule___ofUndoState($s__5) {
    return new UndoableBoard(Shared_002EBoardModule___ofState($s__5->SBoard), Shared_002EBoardModule___ofState($s__5->SUndoPoint), ($s__5->SUndoType === 'NoUndo' ? new UndoType_NoUndo() : ($s__5->SUndoType === 'DontUndoCards' ? new UndoType_DontUndoCards() : new UndoType_FullUndo())), $s__5->SShouldShuffle, $s__5->SAtUndoPoint);
}

#228
function Shared_002EClient___cardName($_arg1__64) {
    if ($_arg1__64 instanceof Card_Rut) {
        return 'card rut';
    }     else {
        if ($_arg1__64 instanceof Card_HayBale) {
            switch (get_class($_arg1__64->power))
            {
                case 'CardPower_Two':
                    return 'card hay-bale-2';
                default:
                    return 'card hay-bale-1';
            }
        }         else {
            if ($_arg1__64 instanceof Card_Dynamite) {
                return 'card dynamite';
            }             else {
                if ($_arg1__64 instanceof Card_HighVoltage) {
                    return 'card high-voltage';
                }                 else {
                    if ($_arg1__64 instanceof Card_Watchdog) {
                        return 'card watchdog';
                    }                     else {
                        if ($_arg1__64 instanceof Card_Helicopter) {
                            return 'card helicopter';
                        }                         else {
                            if ($_arg1__64 instanceof Card_Bribe) {
                                return 'card bribe';
                            }                             else {
                                if ($_arg1__64 instanceof Card_GameOver) {
                                    return 'card gameover';
                                }                                 else {
                                    switch (get_class($_arg1__64->power))
                                    {
                                        case 'CardPower_Two':
                                            return 'card nitro-2';
                                        default:
                                            return 'card nitro-1';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

#229
abstract class ServerMsg implements Union, FSharpUnion {
}

#229
class ServerMsg_JoinGame extends ServerMsg {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'ServerMsg_JoinGame';
    }
    function get_FSharpCase() {
        return 'JoinGame';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__210 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__210 != 0) {
            return $_cmp__210;
        }        
        $_cmp__211 = $this->Item->CompareTo($other->Item);
        if ($_cmp__211 != 0) {
            return $_cmp__211;
        }        
        return 0;
    }
}

#229
class ServerMsg_Command extends ServerMsg {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'ServerMsg_Command';
    }
    function get_FSharpCase() {
        return 'Command';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__212 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__212 != 0) {
            return $_cmp__212;
        }        
        $_cmp__213 = $this->Item->CompareTo($other->Item);
        if ($_cmp__213 != 0) {
            return $_cmp__213;
        }        
        return 0;
    }
}

#229
class ServerMsg_SendMessage extends ServerMsg {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'ServerMsg_SendMessage';
    }
    function get_FSharpCase() {
        return 'SendMessage';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__214 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__214 != 0) {
            return $_cmp__214;
        }        
        $_cmp__215 = $this->Item->CompareTo($other->Item);
        if ($_cmp__215 != 0) {
            return $_cmp__215;
        }        
        return 0;
    }
}

#230
class ChatEntry {
    public $Text;
    public $Player;
    public $Date;
    function __construct($Text, $Player, $Date) {
        $this->Text = $Text;
        $this->Player = $Player;
        $this->Date = $Date;
    }
}

#231
abstract class ClientMsg implements Union, FSharpUnion {
}

#231
class ClientMsg_Events extends ClientMsg {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
    function get_Case() {
        return 'ClientMsg_Events';
    }
    function get_FSharpCase() {
        return 'Events';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__216 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__216 != 0) {
            return $_cmp__216;
        }        
        $_cmp__217 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__217 != 0) {
            return $_cmp__217;
        }        
        $_cmp__218 = $this->Item2 > $other->Item2 ? 1 : ($this->Item2 < $other->Item2 ? -1 : 0);
        if ($_cmp__218 != 0) {
            return $_cmp__218;
        }        
        return 0;
    }
}

#231
class ClientMsg_Message extends ClientMsg {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'ClientMsg_Message';
    }
    function get_FSharpCase() {
        return 'Message';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__219 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__219 != 0) {
            return $_cmp__219;
        }        
        $_cmp__220 = $this->Item->CompareTo($other->Item);
        if ($_cmp__220 != 0) {
            return $_cmp__220;
        }        
        return 0;
    }
}

#231
class ClientMsg_Sync extends ClientMsg {
    public $Item1;
    public $Item2;
    public $Item3;
    function __construct($Item1, $Item2, $Item3) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
        $this->Item3 = $Item3;
    }
    function get_Case() {
        return 'ClientMsg_Sync';
    }
    function get_FSharpCase() {
        return 'Sync';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__221 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__221 != 0) {
            return $_cmp__221;
        }        
        $_cmp__222 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__222 != 0) {
            return $_cmp__222;
        }        
        $_cmp__223 = $this->Item2 > $other->Item2 ? 1 : ($this->Item2 < $other->Item2 ? -1 : 0);
        if ($_cmp__223 != 0) {
            return $_cmp__223;
        }        
        $_cmp__224 = $this->Item3->CompareTo($other->Item3);
        if ($_cmp__224 != 0) {
            return $_cmp__224;
        }        
        return 0;
    }
}

#231
class ClientMsg_SyncPlayer extends ClientMsg {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'ClientMsg_SyncPlayer';
    }
    function get_FSharpCase() {
        return 'SyncPlayer';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__225 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__225 != 0) {
            return $_cmp__225;
        }        
        $_cmp__226 = $this->Item->CompareTo($other->Item);
        if ($_cmp__226 != 0) {
            return $_cmp__226;
        }        
        return 0;
    }
}

#231
class ClientMsg_ReceiveMessage extends ClientMsg {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_Case() {
        return 'ClientMsg_ReceiveMessage';
    }
    function get_FSharpCase() {
        return 'ReceiveMessage';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__227 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__227 != 0) {
            return $_cmp__227;
        }        
        $_cmp__228 = $this->Item->CompareTo($other->Item);
        if ($_cmp__228 != 0) {
            return $_cmp__228;
        }        
        return 0;
    }
}

#0
function SharedServer___privateGame($id, $game) {
    return new PlayingBoard(Map::map(function ($playerid, $player) use ($id) {     if ($playerid === $id) {
        return $player;
    }     else {
        return Shared_002EPlayer___toPrivate($player);
    }
 }, $game->Players), $game->Table, $game->DrawPile, $game->DiscardPile, $game->Barns, $game->HayBales, $game->Goal, $game->UseGameOver);
}

#1
function SharedServer___privateBoard($playerId, $board) {
    switch (get_class($board))
    {
        case 'Board_Won':
            return new Board_Won($board->Item1, SharedServer___privateGame($playerId, $board->Item2));
        case 'Board_InitialState':
            return new Board_InitialState();
        default:
            return new Board_Board(SharedServer___privateGame($playerId, $board->Item));
    }
}

#2
function SharedServer___privateUndoableBoard($playerId__1, $undoboard) {
    return new UndoableBoard(SharedServer___privateBoard($playerId__1, $undoboard->Board), SharedServer___privateBoard($playerId__1, $undoboard->UndoPoint), $undoboard->UndoType, $undoboard->ShouldShuffle, $undoboard->AtUndoPoint);
}

#3
function SharedServer___privateEvents($playerId__2, $events) {
    return FSharpList::map(function ($_arg1) use ($playerId__2) {     switch (get_class($_arg1))
    {
        case 'BoardEvent_PlayerDrewCards':
            if ($playerId__2 === $_arg1->Item->Player) {
                return $_arg1;
            }             else {
                return new BoardEvent_PlayerDrewCards(new PlayerDrewCards($_arg1->Item->Player, Shared_002EHandModule___toPrivate($_arg1->Item->Cards)));
            }
        case 'BoardEvent_DiscardPileShuffled':
            return new BoardEvent_DiscardPileShuffled(FSharpList::ofSeq(Seq::delay(function ($unitVar) use ($_arg1) {             return Seq::map(function ($c) {             return new Card_Rut();
 }, $_arg1->Item);
 })));
        case 'BoardEvent_DrawPileShuffled':
            return new BoardEvent_DrawPileShuffled(FSharpList::get_Nil());
        default:
            return $_arg1;
    }
 }, $events);
}

#4
function SharedServer___bgaUpdateState($events__1, $board__1, $zombie, $state, $changeState, $eliminatePlayer) {
    if ($zombie) {
        return $changeState('zombiepass');
    }     else {
        return Seq::iterate(function ($e__2) use ($board__1, $changeState, $eliminatePlayer) {         if ($e__2 instanceof BoardEvent_Next) {
            return $changeState('next');
        }         else {
            if ($e__2 instanceof BoardEvent_GameWon) {
                return $changeState('endGame');
            }             else {
                if ($e__2 instanceof BoardEvent_GameEnded) {
                    return $changeState('endGame');
                }                 else {
                    if ($e__2 instanceof BoardEvent_Played) {
                        switch (get_class($e__2->Item2))
                        {
                            case 'Event_FirstCrossroadSelected':
                                return $changeState('selectFirstCrossroad');
                            case 'Event_Eliminated':
                                $p__2 = $e__2->Item1;
                                return $eliminatePlayer($p__2);
                            case 'Event_Undone':
                                $p__3 = $e__2->Item1;
                                if ($board__1->Board instanceof Board_Board) {
                                    $matchValue__1 = Map::FSharpMap__get_Item__2B595($board__1->Board->Item->Players, $p__3);
                                    switch (get_class($matchValue__1))
                                    {
                                        case 'CrazyPlayer_Starting':
                                            return $changeState('restart');
                                        case 'CrazyPlayer_Playing':
                                            if (Shared_002EPlayer___canMove($p__3, $board__1->Board)) {
                                                return $changeState('canMove');
                                            }                                             else {
                                                if (Shared_002EHandModule___canPlay($matchValue__1->Item->Hand)) {
                                                    if (Shared_002EHandModule___shouldDiscard($matchValue__1->Item->Hand)) {
                                                        return $changeState('shouldDiscard');
                                                    }                                                     else {
                                                        return $changeState('endTurn');
                                                    }
                                                }                                                 else {
                                                    return NULL;
                                                }
                                            }
                                        default:
                                            return NULL;
                                    }
                                }                                 else {
                                    return NULL;
                                }
                            default:
                                $p__4 = $e__2->Item1;
                                if ($board__1->Board instanceof Board_Board) {
                                    $matchValue__3 = Map::FSharpMap__get_Item__2B595($board__1->Board->Item->Players, $p__4);
                                    if ($matchValue__3 instanceof CrazyPlayer_Playing) {
                                        if (Shared_002EPlayer___canMove($p__4, $board__1->Board)) {
                                            return $changeState('canMove');
                                        }                                         else {
                                            if (Shared_002EHandModule___canPlay($matchValue__3->Item->Hand)) {
                                                if (Shared_002EHandModule___shouldDiscard($matchValue__3->Item->Hand)) {
                                                    return $changeState('shouldDiscard');
                                                }                                                 else {
                                                    return $changeState('endTurn');
                                                }
                                            }                                             else {
                                                if (!Util::equals($board__1->UndoType, new UndoType_NoUndo())) {
                                                    return $changeState('endTurn');
                                                }                                                 else {
                                                    return NULL;
                                                }
                                            }
                                        }
                                    }                                     else {
                                        return NULL;
                                    }
                                }                                 else {
                                    return NULL;
                                }
                        }
                    }                     else {
                        return NULL;
                    }
                }
            }
        }
 }, $events__1);
    }
}

#5
function SharedServer___bgaUpdateZombieState($board__2, $changeState__1) {
    switch (get_class($board__2->Board))
    {
        case 'Board_Board':
            $p__5 = Shared_002EGameTable__get_Player($board__2->Board->Item->Table, NULL);
            $matchValue__5 = Map::FSharpMap__get_Item__2B595($board__2->Board->Item->Players, $p__5);
            switch (get_class($matchValue__5))
            {
                case 'CrazyPlayer_Playing':
                    if (Shared_002EPlayer___canMove($p__5, $board__2->Board)) {
                        return $changeState__1('nextPlayer');
                    }                     else {
                        return $changeState__1('nextEndTurn');
                    }
                case 'CrazyPlayer_Ko':
                    return NULL;
                default:
                    return $changeState__1('nextStarting');
            }
        case 'Board_Won':
            return $changeState__1('nextPlayer');
        default:
            return NULL;
    }
}

#6
function SharedServer___bgaNextPlayer($board__3) {
    switch (get_class($board__3->Board))
    {
        case 'Board_InitialState':
            $_target__229 = 1;
            break;
        case 'Board_Won':
            $_target__229 = 1;
            break;
        default:
            $_target__229 = 0;
            break;
    }
    switch ($_target__229)
    {
        case 0:
            $matchValue__7 = Shared_002EBoardModule___currentPlayer($board__3->Board->Item);
            if ($matchValue__7 instanceof CrazyPlayer_Starting) {
                return 'nextStarting';
            }             else {
                if (Shared_002EPlayer___canMove(Shared_002EGameTable__get_Player($board__3->Board->Item->Table, NULL), $board__3->Board)) {
                    return 'nextPlayer';
                }                 else {
                    return 'nextEndTurn';
                }
            }
        case 1:
            return '';
    }
}

#7
function SharedServer___bgaProgression($board__4) {
    switch (get_class($board__4->Board))
    {
        case 'Board_Won':
            return 100;
        case 'Board_Board':
            switch (get_class($board__4->Board->Item->Goal))
            {
                case 'Goal_Individual':
                    $source = Map::toSeq($board__4->Board->Item->Players);
                    $source__1 = Seq::map(function ($tupledArg) {                     return Shared_002EPlayer___principalFieldSize($tupledArg[1]);
 }, $source);
                    $maxSize = Seq::max($source__1, [ 'Compare' => 'Util::comparePrimitives']);
                    return Util::min('Util::comparePrimitives', $board__4->Board->Item->Goal->Item, $maxSize) * 100 / $board__4->Board->Item->Goal->Item;
                default:
                    $totalSize = Shared_002EBoardModule___totalSize($board__4->Board->Item);
                    return Util::min('Util::comparePrimitives', $board__4->Board->Item->Goal->Item, $totalSize) * 100 / $board__4->Board->Item->Goal->Item;
            }
        default:
            return 0;
    }
}

#8
function SharedServer___bgaScore($events__2, $board__5, $updateScore) {
    switch (get_class($board__5->Board))
    {
        case 'Board_Board':
            $b__7 = $board__5->Board->Item;
            $_target__230 = 0;
            break;
        case 'Board_Won':
            $b__7 = $board__5->Board->Item2;
            $_target__230 = 0;
            break;
        default:
            $_target__230 = 1;
            break;
    }
    switch ($_target__230)
    {
        case 0:
            return Seq::iterate(function ($e__3) use ($b__7, $updateScore) {             if ($e__3 instanceof BoardEvent_Played) {
                if ($e__3->Item2 instanceof Event_Annexed) {
                    $inputSequence = Map::toSeq($b__7->Players);
                    return Seq::iterate(function ($forLoopVar) use ($updateScore) { 
                        $score = Shared_002EPlayer___principalFieldSize($forLoopVar[1]);
                        $scoreAux = Shared_002EPlayer___fieldTotalSize($forLoopVar[1]);
                        return $updateScore([ $forLoopVar[0], $score, $scoreAux]);
                    }, $inputSequence);
                }                 else {
                    if ($e__3->Item2 instanceof Event_CardPlayed) {
                        switch (get_class($e__3->Item2->Item))
                        {
                            case 'PlayCard_PlayBribe':
                                $inputSequence = Map::toSeq($b__7->Players);
                                return Seq::iterate(function ($forLoopVar) use ($updateScore) { 
                                    $score = Shared_002EPlayer___principalFieldSize($forLoopVar[1]);
                                    $scoreAux = Shared_002EPlayer___fieldTotalSize($forLoopVar[1]);
                                    return $updateScore([ $forLoopVar[0], $score, $scoreAux]);
                                }, $inputSequence);
                            default:
                                return NULL;
                        }
                    }                     else {
                        return NULL;
                    }
                }
            }             else {
                return NULL;
            }
 }, $events__2);
        case 1:
            return NULL;
    }
}

#9
class Php {
    function __construct() {
    }
}

#10
function SharedServer___cardIcon($card) {
    $cardName = Shared_002EClient___cardName($card);
    return '<div class="cardicon"><div class="' . $cardName . '"></div></div>';
}

#11
function SharedServer___textAction($previous, $b__8, $e__4) {
    switch (get_class($b__8->Board))
    {
        case 'Board_Board':
            $board__6 = $b__8->Board->Item;
            $_target__231 = 0;
            break;
        case 'Board_Won':
            $board__6 = $b__8->Board->Item2;
            $_target__231 = 0;
            break;
        default:
            $_target__231 = 1;
            break;
    }
    switch ($_target__231)
    {
        case 0:
            $playerName = function ($p__7) use ($board__6) { 
                $name = Map::FSharpMap__get_Item__2B595($board__6->Table->Names, $p__7);
                $player__5 = Map::FSharpMap__get_Item__2B595($board__6->Players, $p__7);
                $matchValue__12 = Shared_002EPlayer___color($player__5);
                switch (get_class($matchValue__12))
                {
                    case 'Color_Yellow':
                        $color = 'EFC54C';
                        break;
                    case 'Color_Purple':
                        $color = 'A87BBE';
                        break;
                    case 'Color_Red':
                        $color = 'EA222F';
                        break;
                    default:
                        $color = 'AEDBDE';
                        break;
                }
                return '<span style="font-weight:bold;color:#' . $color . '">' . $name . '</span>';
            };
            if ($e__4 instanceof BoardEvent_Played) {
                if ($e__4->Item2 instanceof Event_Annexed) {
                    $e__5 = $e__4->Item2->Item;
                    $p__8 = $e__4->Item1;
                    return [ clienttranslate('${player} takes over ${parcels} parcel(s)'), Map::ofList(new Cons((function () use ($p__8, $playerName) { 
                        $v = $playerName($p__8);
                        return [ 'player', $v];
                    })(), new Cons((function () use ($e__5) { 
                        $v__1 = FSharpList::length($e__5->NewField);
                        return [ 'parcels', $v__1];
                    })(), FSharpList::get_Nil())), [ 'Compare' => 'Util::comparePrimitives'])];
                }                 else {
                    if ($e__4->Item2 instanceof Event_CutFence) {
                        $e__6 = $e__4->Item2->Item;
                        $p__9 = $e__4->Item1;
                        return [ clienttranslate('${player} cut ${cut}\'s fence'), Map::ofList(new Cons((function () use ($p__9, $playerName) { 
                            $v__2 = $playerName($p__9);
                            return [ 'player', $v__2];
                        })(), new Cons((function () use ($e__6, $playerName) { 
                            $v__3 = $playerName($e__6->Player);
                            return [ 'cut', $v__3];
                        })(), FSharpList::get_Nil())), [ 'Compare' => 'Util::comparePrimitives'])];
                    }                     else {
                        if ($e__4->Item2 instanceof Event_Bribed) {
                            $e__8 = $e__4->Item2->Item;
                            $p__10 = $e__4->Item1;
                            return [ clienttranslate('${icon} ${player} takes one of ${bribed}\'s parcel'), Map::ofList(new Cons((function () use ($p__10, $playerName) { 
                                $v__8 = $playerName($p__10);
                                return [ 'player', $v__8];
                            })(), new Cons((function () use ($e__8, $playerName) { 
                                $v__9 = $playerName($e__8->Victim);
                                return [ 'bribed', $v__9];
                            })(), new Cons((function () { 
                                $v__10 = SharedServer___cardIcon(new Card_Bribe());
                                return [ 'icon', $v__10];
                            })(), FSharpList::get_Nil()))), [ 'Compare' => 'Util::comparePrimitives'])];
                        }                         else {
                            if ($e__4->Item2 instanceof Event_Eliminated) {
                                $p__11 = $e__4->Item1;
                                return [ clienttranslate('${player} is eliminated !'), Map::ofList(new Cons((function () use ($p__11, $playerName) { 
                                    $v__11 = $playerName($p__11);
                                    return [ 'player', $v__11];
                                })(), FSharpList::get_Nil()), [ 'Compare' => 'Util::comparePrimitives'])];
                            }                             else {
                                if ($e__4->Item2 instanceof Event_CardPlayed) {
                                    switch (get_class($e__4->Item2->Item))
                                    {
                                        case 'PlayCard_PlayHelicopter':
                                            $p__12 = $e__4->Item1;
                                            return [ clienttranslate('${icon} ${player} is heliported to new crossroad'), Map::ofList(new Cons((function () use ($p__12, $playerName) { 
                                                $v__12 = $playerName($p__12);
                                                return [ 'player', $v__12];
                                            })(), new Cons((function () { 
                                                $v__13 = SharedServer___cardIcon(new Card_Helicopter());
                                                return [ 'icon', $v__13];
                                            })(), FSharpList::get_Nil())), [ 'Compare' => 'Util::comparePrimitives'])];
                                        case 'PlayCard_PlayHighVoltage':
                                            $p__13 = $e__4->Item1;
                                            return [ clienttranslate('${icon} ${player}\'s fence cannot be cut until next turn'), Map::ofList(new Cons((function () use ($p__13, $playerName) { 
                                                $v__14 = $playerName($p__13);
                                                return [ 'player', $v__14];
                                            })(), new Cons((function () { 
                                                $v__15 = SharedServer___cardIcon(new Card_HighVoltage());
                                                return [ 'icon', $v__15];
                                            })(), FSharpList::get_Nil())), [ 'Compare' => 'Util::comparePrimitives'])];
                                        case 'PlayCard_PlayWatchdog':
                                            $p__14 = $e__4->Item1;
                                            return [ clienttranslate('${icon} ${player} field is protected until next turn'), Map::ofList(new Cons((function () use ($p__14, $playerName) { 
                                                $v__16 = $playerName($p__14);
                                                return [ 'player', $v__16];
                                            })(), new Cons((function () { 
                                                $v__17 = SharedServer___cardIcon(new Card_Watchdog());
                                                return [ 'icon', $v__17];
                                            })(), FSharpList::get_Nil())), [ 'Compare' => 'Util::comparePrimitives'])];
                                        case 'PlayCard_PlayRut':
                                            $p__15 = $e__4->Item1;
                                            $victim = $e__4->Item2->Item->victim;
                                            return [ clienttranslate('${icon} ${player} makes ${rutted} loose 2 moves during next turn'), Map::ofList(new Cons((function () use ($p__15, $playerName) { 
                                                $v__18 = $playerName($p__15);
                                                return [ 'player', $v__18];
                                            })(), new Cons((function () use ($playerName, $victim) { 
                                                $v__19 = $playerName($victim);
                                                return [ 'rutted', $v__19];
                                            })(), new Cons((function () { 
                                                $v__20 = SharedServer___cardIcon(new Card_Rut());
                                                return [ 'icon', $v__20];
                                            })(), FSharpList::get_Nil()))), [ 'Compare' => 'Util::comparePrimitives'])];
                                        case 'PlayCard_PlayHayBale':
                                            $hb = $e__4->Item2->Item->path;
                                            $p__16 = $e__4->Item1;
                                            $pc = $e__4->Item2->Item;
                                            return [ clienttranslate('${icon} ${player} blocks ${haybales} paths'), Map::ofList(new Cons((function () use ($p__16, $playerName) { 
                                                $v__21 = $playerName($p__16);
                                                return [ 'player', $v__21];
                                            })(), new Cons((function () use ($hb) { 
                                                $v__22 = FSharpList::length($hb);
                                                return [ 'haybales', $v__22];
                                            })(), new Cons((function () use ($pc) { 
                                                $v__23 = SharedServer___cardIcon(Shared_002ECardModule___ofPlayCard($pc));
                                                return [ 'icon', $v__23];
                                            })(), FSharpList::get_Nil()))), [ 'Compare' => 'Util::comparePrimitives'])];
                                        case 'PlayCard_PlayDynamite':
                                            $p__17 = $e__4->Item1;
                                            return [ clienttranslate('${icon} ${player} dynamites 1 hay bale'), Map::ofList(new Cons((function () use ($p__17, $playerName) { 
                                                $v__24 = $playerName($p__17);
                                                return [ 'player', $v__24];
                                            })(), new Cons((function () { 
                                                $v__25 = SharedServer___cardIcon(new Card_Dynamite());
                                                return [ 'icon', $v__25];
                                            })(), FSharpList::get_Nil())), [ 'Compare' => 'Util::comparePrimitives'])];
                                        case 'PlayCard_PlayNitro':
                                            $p__18 = $e__4->Item1;
                                            $power = $e__4->Item2->Item->power;
                                            return [ clienttranslate('${icon} ${player} get ${nitro} extra move(s)'), Map::ofList(new Cons((function () use ($p__18, $playerName) { 
                                                $v__26 = $playerName($p__18);
                                                return [ 'player', $v__26];
                                            })(), new Cons((function () use ($power) { 
                                                switch (get_class($power))
                                                {
                                                    case 'CardPower_Two':
                                                        $v__27 = 2;
                                                        break;
                                                    default:
                                                        $v__27 = 1;
                                                        break;
                                                }
                                                return [ 'nitro', $v__27];
                                            })(), new Cons((function () use ($power) { 
                                                $v__28 = SharedServer___cardIcon(new Card_Nitro($power));
                                                return [ 'icon', $v__28];
                                            })(), FSharpList::get_Nil()))), [ 'Compare' => 'Util::comparePrimitives'])];
                                        default:
                                            return [ NULL, Map::empty([ 'Compare' => 'Util::comparePrimitives'])];
                                    }
                                }                                 else {
                                    switch (get_class($e__4->Item2))
                                    {
                                        case 'Event_Undone':
                                            $p__19 = $e__4->Item1;
                                            return [ clienttranslate('${player} undo last moves'), Map::ofList(new Cons((function () use ($p__19, $playerName) { 
                                                $v__29 = $playerName($p__19);
                                                return [ 'player', $v__29];
                                            })(), FSharpList::get_Nil()), [ 'Compare' => 'Util::comparePrimitives'])];
                                        default:
                                            return [ NULL, Map::empty([ 'Compare' => 'Util::comparePrimitives'])];
                                    }
                                }
                            }
                        }
                    }
                }
            }             else {
                switch (get_class($e__4))
                {
                    case 'BoardEvent_PlayerDrewCards':
                        $e__7 = $e__4->Item;
                        if (Shared_002EHandModule___contains(new Card_GameOver(), $e__7->Cards)) {
                            return [ clienttranslate('${icon} ${player} draws the Game Over card. The game ends now !'), Map::ofList(new Cons((function () use ($e__7, $playerName) { 
                                $v__4 = $playerName($e__7->Player);
                                return [ 'player', $v__4];
                            })(), new Cons((function () { 
                                $v__5 = SharedServer___cardIcon(new Card_GameOver());
                                return [ 'icon', $v__5];
                            })(), FSharpList::get_Nil())), [ 'Compare' => 'Util::comparePrimitives'])];
                        }                         else {
                            return [ clienttranslate('${player} draws ${cardcount} card(s)'), Map::ofList(new Cons((function () use ($e__7, $playerName) { 
                                $v__6 = $playerName($e__7->Player);
                                return [ 'player', $v__6];
                            })(), new Cons((function () use ($e__7) { 
                                $v__7 = Shared_002EHandModule___count($e__7->Cards);
                                return [ 'cardcount', $v__7];
                            })(), FSharpList::get_Nil())), [ 'Compare' => 'Util::comparePrimitives'])];
                        }
                    case 'BoardEvent_Next':
                        if ($previous->Board instanceof Board_Board) {
                            $player__6 = Shared_002EGameTable__get_Player($previous->Board->Item->Table, NULL);
                        }                         else {
                            $player__6 = '';
                        }
                        $matchValue__14 = Map::tryFind($player__6, $board__6->Players);
                        if (!is_null($matchValue__14)) {
                            switch (get_class($matchValue__14))
                            {
                                case 'CrazyPlayer_Playing':
                                    $p__20 = $matchValue__14->Item;
                                    return [ clienttranslate('${player} ends turn after ${moves} moves'), Map::ofList(new Cons((function () use ($playerName, $player__6) { 
                                        $v__30 = $playerName($player__6);
                                        return [ 'player', $v__30];
                                    })(), new Cons([ 'moves', $p__20->Moves->Done], FSharpList::get_Nil())), [ 'Compare' => 'Util::comparePrimitives'])];
                                default:
                                    return [ NULL, Map::empty([ 'Compare' => 'Util::comparePrimitives'])];
                            }
                        }                         else {
                            return [ NULL, Map::empty([ 'Compare' => 'Util::comparePrimitives'])];
                        }
                    case 'BoardEvent_GameWon':
                        $p__21 = $e__4->Item;
                        return [ clienttranslate('${player} wins the game !'), Map::ofList(new Cons((function () use ($p__21, $playerName) { 
                            $v__32 = $playerName($p__21);
                            return [ 'player', $v__32];
                        })(), FSharpList::get_Nil()), [ 'Compare' => 'Util::comparePrimitives'])];
                    case 'BoardEvent_GameEnded':
                        $ps = $e__4->Item;
                        return [ clienttranslate('${players} end the game in a draw !'), Map::ofList(new Cons((function () use ($playerName, $ps) { 
                            $v__33 = join(' / ', FSharpArray::ofSeq(Seq::delay(function ($unitVar__1) use ($playerName, $ps) {                             return Seq::map(function ($p__22) use ($playerName) {                             return $playerName($p__22);
 }, $ps);
 })));
                            return [ 'players', $v__33];
                        })(), FSharpList::get_Nil()), [ 'Compare' => 'Util::comparePrimitives'])];
                    default:
                        return [ NULL, Map::empty([ 'Compare' => 'Util::comparePrimitives'])];
                }
            }
        case 1:
            return [ NULL, Map::empty([ 'Compare' => 'Util::comparePrimitives'])];
    }
}

#12
class Stats {
    public $TableStats;
    public $PlayerStats;
    function __construct($TableStats, $PlayerStats) {
        $this->TableStats = $TableStats;
        $this->PlayerStats = $PlayerStats;
    }
}

#13
class UndoableStats {
    public $Stats;
    public $UndoPoint;
    function __construct($Stats, $UndoPoint) {
        $this->Stats = $Stats;
        $this->UndoPoint = $UndoPoint;
    }
}

#14
$GLOBALS['SharedServer_002EStatsModule___turns_number'] = 'turns_number';

#15
$GLOBALS['SharedServer_002EStatsModule___fences_drawn'] = 'fences_drawn';

#16
$GLOBALS['SharedServer_002EStatsModule___fences_cut'] = 'fences_cut';

#17
$GLOBALS['SharedServer_002EStatsModule___cut_number'] = 'cut_number';

#18
$GLOBALS['SharedServer_002EStatsModule___takeovers_number'] = 'takeovers_number';

#19
$GLOBALS['SharedServer_002EStatsModule___biggest_takeover'] = 'biggest_takeover';

#20
$GLOBALS['SharedServer_002EStatsModule___freebarns_number'] = 'freebarns_number';

#21
$GLOBALS['SharedServer_002EStatsModule___occupiedbarns_number'] = 'occupiedbarns_number';

#22
$GLOBALS['SharedServer_002EStatsModule___cardsplayed_number'] = 'cardsplayed_number';

#23
$GLOBALS['SharedServer_002EStatsModule___haybales_max_number'] = 'haybales_max_number';

#24
$GLOBALS['SharedServer_002EStatsModule___haybales_number'] = 'haybales_number';

#25
$GLOBALS['SharedServer_002EStatsModule___dynamites_number'] = 'dynamites_number';

#26
$GLOBALS['SharedServer_002EStatsModule___haybales_moved_number'] = 'haybales_moved_number';

#27
$GLOBALS['SharedServer_002EStatsModule___helicopters_number'] = 'helicopters_number';

#28
$GLOBALS['SharedServer_002EStatsModule___highvoltages_number'] = 'highvoltages_number';

#29
$GLOBALS['SharedServer_002EStatsModule___watchdogs_number'] = 'watchdogs_number';

#30
$GLOBALS['SharedServer_002EStatsModule___bribes_number'] = 'bribes_number';

#31
$GLOBALS['SharedServer_002EStatsModule___nitro1_number'] = 'nitro1_number';

#32
$GLOBALS['SharedServer_002EStatsModule___nitro2_number'] = 'nitro2_number';

#33
$GLOBALS['SharedServer_002EStatsModule___ruts_number'] = 'ruts_number';

#34
$GLOBALS['SharedServer_002EStatsModule___rutted_number'] = 'rutted_number';

#35
$GLOBALS['SharedServer_002EStatsModule___empty'] = (function () { 
    $stats = new Stats(Map::ofList(new Cons([ $GLOBALS['SharedServer_002EStatsModule___turns_number'], 1], new Cons([ $GLOBALS['SharedServer_002EStatsModule___fences_drawn'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___fences_cut'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___takeovers_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___biggest_takeover'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___freebarns_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___occupiedbarns_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___cardsplayed_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___haybales_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___dynamites_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___haybales_max_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___haybales_moved_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___helicopters_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___highvoltages_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___watchdogs_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___bribes_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___nitro1_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___nitro2_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___ruts_number'], 0], FSharpList::get_Nil()))))))))))))))))))), [ 'Compare' => 'Util::comparePrimitives']), Map::empty([ 'Compare' => 'Util::comparePrimitives']));
    return new UndoableStats($stats, $stats);
})();

#36
function SharedServer_002EStatsModule___diff($x, $y) {
    $elements = Map::toSeq($x);
    $sx = Set::ofSeq($elements, [ 'Compare' => 'Util::compareArrays']);
    $elements__1 = Map::toSeq($y);
    $sy = Set::ofSeq($elements__1, [ 'Compare' => 'Util::compareArrays']);
    return Set::FSharpSet___op_Subtraction($sy, $sx);
}

#37
function SharedServer_002EStatsModule___inc($n, $name__1, $stats__1) {
    $matchValue__15 = Map::tryFind($name__1, $stats__1);
    if (is_null($matchValue__15)) {
        return Map::add($name__1, $n, $stats__1);
    }     else {
        $v__34 = $matchValue__15;
        $value = $v__34 + $n;
        return Map::add($name__1, $value, $stats__1);
    }
}

#38
function SharedServer_002EStatsModule___up($f, $name__2, $stats__2) {
    $matchValue__16 = Map::tryFind($name__2, $stats__2);
    if (is_null($matchValue__16)) {
        $value__2 = $f(0, $stats__2);
        return Map::add($name__2, $value__2, $stats__2);
    }     else {
        $v__35 = $matchValue__16;
        $value__1 = $f($v__35, $stats__2);
        return Map::add($name__2, $value__1, $stats__2);
    }
}

#39
function SharedServer_002EStatsModule___getStat($name__3, $stats__3) {
    $matchValue__17 = Map::tryFind($name__3, $stats__3);
    if (is_null($matchValue__17)) {
        return 0;
    }     else {
        $v__36 = $matchValue__17;
        return $v__36;
    }
}

#40
function SharedServer_002EStatsModule___incStat($n__1, $name__4, $player__7, $stats__4) {
    if (!is_null($player__7)) {
        $p__23 = $player__7;
        $matchValue__18 = Map::tryFind($p__23, $stats__4->PlayerStats);
        if (is_null($matchValue__18)) {
            $ps__2 = Map::ofList(new Cons([ $GLOBALS['SharedServer_002EStatsModule___fences_drawn'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___fences_cut'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___cut_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___takeovers_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___biggest_takeover'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___freebarns_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___occupiedbarns_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___cardsplayed_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___haybales_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___dynamites_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___helicopters_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___highvoltages_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___watchdogs_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___bribes_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___nitro1_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___nitro2_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___ruts_number'], 0], new Cons([ $GLOBALS['SharedServer_002EStatsModule___rutted_number'], 0], FSharpList::get_Nil())))))))))))))))))), [ 'Compare' => 'Util::comparePrimitives']);
        }         else {
            $ps__1 = $matchValue__18;
            $ps__2 = $ps__1;
        }
        $newStats = SharedServer_002EStatsModule___inc($n__1, $name__4, $ps__2);
        $PlayerStats = Map::add($p__23, $newStats, $stats__4->PlayerStats);
        return new Stats($stats__4->TableStats, $PlayerStats);
    }     else {
        return new Stats(SharedServer_002EStatsModule___inc($n__1, $name__4, $stats__4->TableStats), $stats__4->PlayerStats);
    }
}

#41
function SharedServer_002EStatsModule___updateStat($f__1, $name__5, $player__8, $stats__7) {
    if (!is_null($player__8)) {
        $p__24 = $player__8;
        $matchValue__19 = Map::tryFind($p__24, $stats__7->PlayerStats);
        if (is_null($matchValue__19)) {
            $ps__4 = Map::empty([ 'Compare' => 'Util::comparePrimitives']);
        }         else {
            $ps__3 = $matchValue__19;
            $ps__4 = $ps__3;
        }
        $newStats__1 = SharedServer_002EStatsModule___up($f__1, $name__5, $ps__4);
        $PlayerStats__1 = Map::add($p__24, $newStats__1, $stats__7->PlayerStats);
        return new Stats($stats__7->TableStats, $PlayerStats__1);
    }     else {
        return new Stats(SharedServer_002EStatsModule___up($f__1, $name__5, $stats__7->TableStats), $stats__7->PlayerStats);
    }
}

#42
function SharedServer_002EStatsModule___update($stats__10, $e__9) {
    if ($e__9 instanceof BoardEvent_Next) {
        $player__9 = NULL;
        $newStats__2 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___turns_number'], $player__9, $stats__10->Stats);
        return new UndoableStats($newStats__2, $newStats__2);
    }     else {
        if ($e__9 instanceof BoardEvent_UndoCheckPointed) {
            return new UndoableStats($stats__10->Stats, $stats__10->Stats);
        }         else {
            if ($e__9 instanceof BoardEvent_Played) {
                switch (get_class($e__9->Item2))
                {
                    case 'Event_Undone':
                        return new UndoableStats($stats__10->UndoPoint, $stats__10->UndoPoint);
                    case 'Event_CutFence':
                        $e__10 = $e__9->Item2->Item;
                        $p__25 = $e__9->Item1;
                        $player__10 = NULL;
                        $stats__13 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___fences_cut'], $player__10, $stats__10->Stats);
                        $player__11 = $p__25;
                        $stats__14 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___fences_cut'], $player__11, $stats__13);
                        $player__12 = $e__10->Player;
                        $newStats__3 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___cut_number'], $player__12, $stats__14);
                        return new UndoableStats($newStats__3, $stats__10->UndoPoint);
                    case 'Event_FenceDrawn':
                        $p__26 = $e__9->Item1;
                        $player__13 = NULL;
                        $stats__16 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___fences_drawn'], $player__13, $stats__10->Stats);
                        $player__14 = $p__26;
                        $newStats__4 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___fences_drawn'], $player__14, $stats__16);
                        return new UndoableStats($newStats__4, $stats__10->UndoPoint);
                    case 'Event_Annexed':
                        $e__11 = $e__9->Item2->Item;
                        $p__27 = $e__9->Item1;
                        $size = FSharpList::length($e__11->NewField);
                        $freeBarns = FSharpList::length($e__11->FreeBarns);
                        $occupiedBarns = FSharpList::length($e__11->OccupiedBarns);
                        $player__15 = NULL;
                        $stats__18 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___takeovers_number'], $player__15, $stats__10->Stats);
                        $player__16 = $p__27;
                        $stats__19 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___takeovers_number'], $player__16, $stats__18);
                        $player__17 = $p__27;
                        $stats__20 = SharedServer_002EStatsModule___updateStat(function ($current, $_arg1__2) use ($size) {                         return Util::max('Util::comparePrimitives', $current, $size);
 }, $GLOBALS['SharedServer_002EStatsModule___biggest_takeover'], $player__17, $stats__19);
                        $player__18 = NULL;
                        $stats__21 = SharedServer_002EStatsModule___updateStat(function ($current__1, $_arg2) use ($size) {                         return Util::max('Util::comparePrimitives', $current__1, $size);
 }, $GLOBALS['SharedServer_002EStatsModule___biggest_takeover'], $player__18, $stats__20);
                        $player__19 = NULL;
                        $stats__22 = SharedServer_002EStatsModule___incStat($freeBarns, $GLOBALS['SharedServer_002EStatsModule___freebarns_number'], $player__19, $stats__21);
                        $player__20 = $p__27;
                        $stats__23 = SharedServer_002EStatsModule___incStat($freeBarns, $GLOBALS['SharedServer_002EStatsModule___freebarns_number'], $player__20, $stats__22);
                        $player__21 = NULL;
                        $stats__24 = SharedServer_002EStatsModule___incStat($occupiedBarns, $GLOBALS['SharedServer_002EStatsModule___occupiedbarns_number'], $player__21, $stats__23);
                        $player__22 = $p__27;
                        $newStats__5 = SharedServer_002EStatsModule___incStat($occupiedBarns, $GLOBALS['SharedServer_002EStatsModule___occupiedbarns_number'], $player__22, $stats__24);
                        return new UndoableStats($newStats__5, $stats__10->UndoPoint);
                    case 'Event_CardPlayed':
                        $cp = $e__9->Item2->Item;
                        $p__28 = $e__9->Item1;
                        $player__23 = NULL;
                        $stats__26 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___cardsplayed_number'], $player__23, $stats__10->Stats);
                        $player__24 = $p__28;
                        $statsNew = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___cardsplayed_number'], $player__24, $stats__26);
                        if ($cp instanceof PlayCard_PlayDynamite) {
                            $player__29 = NULL;
                            $stats__33 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___dynamites_number'], $player__29, $statsNew);
                            $player__30 = $p__28;
                            $stats__35 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___dynamites_number'], $player__30, $stats__33);
                            $player__31 = NULL;
                            $newStats__7 = SharedServer_002EStatsModule___updateStat(function ($current__3, $stats__34) { 
                                $totalHayBales__1 = SharedServer_002EStatsModule___getStat($GLOBALS['SharedServer_002EStatsModule___haybales_number'], $stats__34) - SharedServer_002EStatsModule___getStat($GLOBALS['SharedServer_002EStatsModule___dynamites_number'], $stats__34) - SharedServer_002EStatsModule___getStat($GLOBALS['SharedServer_002EStatsModule___haybales_moved_number'], $stats__34);
                                return Util::max('Util::comparePrimitives', $current__3, $totalHayBales__1);
                            }, $GLOBALS['SharedServer_002EStatsModule___haybales_max_number'], $player__31, $stats__35);
                            return new UndoableStats($newStats__7, $stats__10->UndoPoint);
                        }                         else {
                            if ($cp instanceof PlayCard_PlayHelicopter) {
                                $player__32 = NULL;
                                $stats__37 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___helicopters_number'], $player__32, $statsNew);
                                $player__33 = $p__28;
                                $newStats__8 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___helicopters_number'], $player__33, $stats__37);
                                return new UndoableStats($newStats__8, $stats__10->UndoPoint);
                            }                             else {
                                if ($cp instanceof PlayCard_PlayHighVoltage) {
                                    $player__34 = NULL;
                                    $stats__39 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___highvoltages_number'], $player__34, $statsNew);
                                    $player__35 = $p__28;
                                    $newStats__9 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___highvoltages_number'], $player__35, $stats__39);
                                    return new UndoableStats($newStats__9, $stats__10->UndoPoint);
                                }                                 else {
                                    if ($cp instanceof PlayCard_PlayWatchdog) {
                                        $player__36 = NULL;
                                        $stats__41 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___watchdogs_number'], $player__36, $statsNew);
                                        $player__37 = $p__28;
                                        $newStats__10 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___watchdogs_number'], $player__37, $stats__41);
                                        return new UndoableStats($newStats__10, $stats__10->UndoPoint);
                                    }                                     else {
                                        if ($cp instanceof PlayCard_PlayBribe) {
                                            $player__38 = NULL;
                                            $stats__43 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___bribes_number'], $player__38, $statsNew);
                                            $player__39 = $p__28;
                                            $newStats__11 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___bribes_number'], $player__39, $stats__43);
                                            return new UndoableStats($newStats__11, $stats__10->UndoPoint);
                                        }                                         else {
                                            if ($cp instanceof PlayCard_PlayNitro) {
                                                switch (get_class($cp->power))
                                                {
                                                    case 'CardPower_Two':
                                                        $player__42 = NULL;
                                                        $stats__47 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___nitro2_number'], $player__42, $statsNew);
                                                        $player__43 = $p__28;
                                                        $newStats__13 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___nitro2_number'], $player__43, $stats__47);
                                                        return new UndoableStats($newStats__13, $stats__10->UndoPoint);
                                                    default:
                                                        $player__40 = NULL;
                                                        $stats__45 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___nitro1_number'], $player__40, $statsNew);
                                                        $player__41 = $p__28;
                                                        $newStats__12 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___nitro1_number'], $player__41, $stats__45);
                                                        return new UndoableStats($newStats__12, $stats__10->UndoPoint);
                                                }
                                            }                                             else {
                                                switch (get_class($cp))
                                                {
                                                    case 'PlayCard_PlayRut':
                                                        $victim__1 = $cp->victim;
                                                        $player__44 = NULL;
                                                        $stats__49 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___ruts_number'], $player__44, $statsNew);
                                                        $player__45 = $p__28;
                                                        $stats__50 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___ruts_number'], $player__45, $stats__49);
                                                        $player__46 = $victim__1;
                                                        $newStats__14 = SharedServer_002EStatsModule___incStat(1, $GLOBALS['SharedServer_002EStatsModule___rutted_number'], $player__46, $stats__50);
                                                        return new UndoableStats($newStats__14, $stats__10->UndoPoint);
                                                    case 'PlayCard_PlayGameOver':
                                                        return $stats__10;
                                                    default:
                                                        $hb__1 = $cp->path;
                                                        $rm = $cp->moved;
                                                        $hayBales = FSharpList::length($hb__1);
                                                        $moved = FSharpList::length($rm);
                                                        $player__25 = NULL;
                                                        $stats__28 = SharedServer_002EStatsModule___incStat($hayBales, $GLOBALS['SharedServer_002EStatsModule___haybales_number'], $player__25, $statsNew);
                                                        $player__26 = $p__28;
                                                        $stats__29 = SharedServer_002EStatsModule___incStat($hayBales, $GLOBALS['SharedServer_002EStatsModule___haybales_number'], $player__26, $stats__28);
                                                        $player__27 = NULL;
                                                        $stats__31 = SharedServer_002EStatsModule___incStat($moved, $GLOBALS['SharedServer_002EStatsModule___haybales_moved_number'], $player__27, $stats__29);
                                                        $player__28 = NULL;
                                                        $newStats__6 = SharedServer_002EStatsModule___updateStat(function ($current__2, $stats__30) { 
                                                            $totalHayBales = SharedServer_002EStatsModule___getStat($GLOBALS['SharedServer_002EStatsModule___haybales_number'], $stats__30) - SharedServer_002EStatsModule___getStat($GLOBALS['SharedServer_002EStatsModule___dynamites_number'], $stats__30) - SharedServer_002EStatsModule___getStat($GLOBALS['SharedServer_002EStatsModule___haybales_moved_number'], $stats__30);
                                                            return Util::max('Util::comparePrimitives', $current__2, $totalHayBales);
                                                        }, $GLOBALS['SharedServer_002EStatsModule___haybales_max_number'], $player__28, $stats__31);
                                                        return new UndoableStats($newStats__6, $stats__10->UndoPoint);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    default:
                        return $stats__10;
                }
            }             else {
                return $stats__10;
            }
        }
    }
}

#43
function SharedServer___updateStats($es, $stats__51, $setStat) {
    $newStats__15 = FSharpList::fold('SharedServer_002EStatsModule___update', $stats__51, $es);
    $tableDiffs = SharedServer_002EStatsModule___diff($stats__51->Stats->TableStats, $newStats__15->Stats->TableStats);
    Seq::iterate(function ($forLoopVar__1) use (&$setStat) {     return $setStat($forLoopVar__1[1], $forLoopVar__1[0], NULL);
 }, $tableDiffs);
    $inputSequence__1 = Map::toSeq($newStats__15->Stats->PlayerStats);
    return Seq::iterate(function ($forLoopVar__2) use ($stats__51, &$setStat) { 
        $matchValue__20 = Map::tryFind($forLoopVar__2[0], $stats__51->Stats->PlayerStats);
        if (is_null($matchValue__20)) {
            $existing = Map::empty([ 'Compare' => 'Util::comparePrimitives']);
        }         else {
            $s = $matchValue__20;
            $existing = $s;
        }
        $playerDiff = SharedServer_002EStatsModule___diff($existing, $forLoopVar__2[1]);
        return Seq::iterate(function ($forLoopVar__3) use ($forLoopVar__2, &$setStat) {         return $setStat($forLoopVar__3[1], $forLoopVar__3[0], $forLoopVar__2[0]);
 }, $playerDiff);
    }, $inputSequence__1);
}

