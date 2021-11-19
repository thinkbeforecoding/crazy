<?php
namespace SharedGame;

require_once(__FABLE_LIBRARY__.'/Array.php');
require_once(__FABLE_LIBRARY__.'/FSharp.Core.php');
require_once(__FABLE_LIBRARY__.'/List.php');
require_once(__FABLE_LIBRARY__.'/Map.php');
require_once(__FABLE_LIBRARY__.'/Option.php');
require_once(__FABLE_LIBRARY__.'/Seq.php');
require_once(__FABLE_LIBRARY__.'/Seq2.php');
require_once(__FABLE_LIBRARY__.'/Set.php');
require_once(__FABLE_LIBRARY__.'/Util.php');
require_once(__ROOT__.'/Shared/Shared.fs.php');

use \FSharpUnion;
use \IComparable;
use \FSharpList\Cons;
use \FSharpList\Nil;

#0
class Axe implements FSharpUnion, IComparable {
    public $q;
    public $r;
    function __construct($q, $r) {
        $this->q = $q;
        $this->r = $r;
    }
    function get_FSharpCase() {
        return 'Axe';
    }
    function get_Tag() {
        return 0;
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
    function get_Q() {
        return $this->q;
    }
    function get_R() {
        return $this->r;
    }
}

#1
function Axe_op_Addition_2BE35040($_arg1, $_arg2) {
    return new Axe(($_arg1->q + $_arg2->q), ($_arg1->r + $_arg2->r));
}

#2
function Axe_op_Multiply_Z425F7B5E($a, $_arg3) {
    return new Axe(($_arg3->q * $a), ($_arg3->r * $a));
}

#5
$GLOBALS['AxeModule_N'] = new Axe(0, -1);

#6
$GLOBALS['AxeModule_S'] = new Axe(0, 1);

#7
$GLOBALS['AxeModule_NW'] = new Axe(-1, 0);

#8
$GLOBALS['AxeModule_NE'] = new Axe(1, -1);

#9
$GLOBALS['AxeModule_SW'] = new Axe(-1, 1);

#10
$GLOBALS['AxeModule_SE'] = new Axe(1, 0);

#11
$GLOBALS['AxeModule_W2'] = Axe_op_Addition_2BE35040($GLOBALS['AxeModule_NW'], $GLOBALS['AxeModule_SW']);

#12
$GLOBALS['AxeModule_E2'] = Axe_op_Addition_2BE35040($GLOBALS['AxeModule_NE'], $GLOBALS['AxeModule_SE']);

#13
$GLOBALS['AxeModule_zero'] = new Axe(0, 0);

#14
$GLOBALS['AxeModule_center'] = $GLOBALS['AxeModule_zero'];

#15
function AxeModule_cube($_arg1) {
    $r = $_arg1->r;
    $q = $_arg1->q;
    return [ $q, $r, -$q - $r];
}

#16
abstract class CrossroadSide implements FSharpUnion {
}

#16
class CrossroadSide_CLeft extends CrossroadSide implements IComparable {
    function __construct() {
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

#16
class CrossroadSide_CRight extends CrossroadSide implements IComparable {
    function __construct() {
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

#17
class Crossroad implements FSharpUnion, IComparable {
    public $tile;
    public $side;
    function __construct($tile, $side) {
        $this->tile = $tile;
        $this->side = $side;
    }
    function get_FSharpCase() {
        return 'Crossroad';
    }
    function get_Tag() {
        return 0;
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

#18
abstract class BorderSide implements FSharpUnion {
}

#18
class BorderSide_BNW extends BorderSide implements IComparable {
    function __construct() {
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

#18
class BorderSide_BN extends BorderSide implements IComparable {
    function __construct() {
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

#18
class BorderSide_BNE extends BorderSide implements IComparable {
    function __construct() {
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

#19
class Path implements FSharpUnion, IComparable {
    public $tile;
    public $border;
    function __construct($tile, $border) {
        $this->tile = $tile;
        $this->border = $border;
    }
    function get_FSharpCase() {
        return 'Path';
    }
    function get_Tag() {
        return 0;
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

#20
abstract class Direction implements FSharpUnion {
}

#20
class Direction_Up extends Direction implements IComparable {
    function __construct() {
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

#20
class Direction_Down extends Direction implements IComparable {
    function __construct() {
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

#20
class Direction_Horizontal extends Direction implements IComparable {
    function __construct() {
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

#21
class Parcel implements FSharpUnion, IComparable {
    public $tile;
    function __construct($tile) {
        $this->tile = $tile;
    }
    function get_FSharpCase() {
        return 'Parcel';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__29 = $this->tile->CompareTo($other->tile);
        if ($_cmp__29 != 0) {
            return $_cmp__29;
        }        
        return 0;
    }
}

#22
function Parcel_op_Addition_ZF6EFE4B($_arg1, $v) {
    return new Parcel(Axe_op_Addition_2BE35040($_arg1->tile, $v));
}

#23
class Field implements FSharpUnion, IComparable {
    public $parcels;
    function __construct($parcels) {
        $this->parcels = $parcels;
    }
    function get_FSharpCase() {
        return 'Field';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__30 = $this->parcels->CompareTo($other->parcels);
        if ($_cmp__30 != 0) {
            return $_cmp__30;
        }        
        return 0;
    }
}

#24
function Field_op_Addition_Z24735800($_arg1, $_arg2) {
    return new Field(\Set\FSharpSet_op_Addition($_arg1->parcels, $_arg2->parcels));
}

#25
function Field_op_Subtraction_Z24735800($_arg3, $_arg4) {
    return new Field(\Set\FSharpSet_op_Subtraction($_arg3->parcels, $_arg4->parcels));
}

#26
class Fence implements FSharpUnion, IComparable {
    public $paths;
    function __construct($paths) {
        $this->paths = $paths;
    }
    function get_FSharpCase() {
        return 'Fence';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__31 = $this->paths->CompareTo($other->paths);
        if ($_cmp__31 != 0) {
            return $_cmp__31;
        }        
        return 0;
    }
}

#27
class Barns implements IComparable {
    public $Free;
    public $Occupied;
    function __construct($Free, $Occupied) {
        $this->Free = $Free;
        $this->Occupied = $Occupied;
    }
    function CompareTo($other) {
        $_cmp__32 = $this->Free->CompareTo($other->Free);
        if ($_cmp__32 != 0) {
            return $_cmp__32;
        }        
        $_cmp__33 = $this->Occupied->CompareTo($other->Occupied);
        if ($_cmp__33 != 0) {
            return $_cmp__33;
        }        
        return 0;
    }
}

#28
function DirectionModule_rev($_arg1) {
    switch ($_arg1->get_Tag())
    {
        case 1:
            return new Direction_Up();
        case 2:
            return new Direction_Horizontal();
        default:
            return new Direction_Down();
    }
}

#29
abstract class Power implements FSharpUnion {
}

#29
class Power_PowerUp extends Power implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PowerUp';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__34 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__34;
    }
}

#29
class Power_PowerDown extends Power implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PowerDown';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__35 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__35;
    }
}

#30
abstract class Card implements FSharpUnion {
}

#30
class Card_Nitro extends Card implements IComparable {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
    function get_FSharpCase() {
        return 'Nitro';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__36 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__36 != 0) {
            return $_cmp__36;
        }        
        $_cmp__37 = $this->power->CompareTo($other->power);
        if ($_cmp__37 != 0) {
            return $_cmp__37;
        }        
        return 0;
    }
}

#30
class Card_Rut extends Card implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Rut';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__38 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__38;
    }
}

#30
class Card_HayBale extends Card implements IComparable {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
    function get_FSharpCase() {
        return 'HayBale';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__39 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__39 != 0) {
            return $_cmp__39;
        }        
        $_cmp__40 = $this->power->CompareTo($other->power);
        if ($_cmp__40 != 0) {
            return $_cmp__40;
        }        
        return 0;
    }
}

#30
class Card_Dynamite extends Card implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Dynamite';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__41 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__41;
    }
}

#30
class Card_HighVoltage extends Card implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'HighVoltage';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__42 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__42;
    }
}

#30
class Card_Watchdog extends Card implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Watchdog';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__43 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__43;
    }
}

#30
class Card_Helicopter extends Card implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Helicopter';
    }
    function get_Tag() {
        return 6;
    }
    function CompareTo($other) {
        $_cmp__44 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__44;
    }
}

#30
class Card_Bribe extends Card implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Bribe';
    }
    function get_Tag() {
        return 7;
    }
    function CompareTo($other) {
        $_cmp__45 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__45;
    }
}

#30
class Card_GameOver extends Card implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'GameOver';
    }
    function get_Tag() {
        return 8;
    }
    function CompareTo($other) {
        $_cmp__46 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__46;
    }
}

#31
abstract class CardPower implements FSharpUnion {
}

#31
class CardPower_One extends CardPower implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'One';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__47 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__47;
    }
}

#31
class CardPower_Two extends CardPower implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Two';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__48 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__48;
    }
}

#32
abstract class PlayCard implements FSharpUnion {
}

#32
class PlayCard_PlayNitro extends PlayCard implements IComparable {
    public $power;
    function __construct($power) {
        $this->power = $power;
    }
    function get_FSharpCase() {
        return 'PlayNitro';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__49 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__49 != 0) {
            return $_cmp__49;
        }        
        $_cmp__50 = $this->power->CompareTo($other->power);
        if ($_cmp__50 != 0) {
            return $_cmp__50;
        }        
        return 0;
    }
}

#32
class PlayCard_PlayRut extends PlayCard implements IComparable {
    public $victim;
    function __construct($victim) {
        $this->victim = $victim;
    }
    function get_FSharpCase() {
        return 'PlayRut';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__51 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__51 != 0) {
            return $_cmp__51;
        }        
        $_cmp__52 = $this->victim->CompareTo($other->victim);
        if ($_cmp__52 != 0) {
            return $_cmp__52;
        }        
        return 0;
    }
}

#32
class PlayCard_PlayHayBale extends PlayCard implements IComparable {
    public $path;
    public $moved;
    function __construct($path, $moved) {
        $this->path = $path;
        $this->moved = $moved;
    }
    function get_FSharpCase() {
        return 'PlayHayBale';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__53 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__53 != 0) {
            return $_cmp__53;
        }        
        $_cmp__54 = $this->path->CompareTo($other->path);
        if ($_cmp__54 != 0) {
            return $_cmp__54;
        }        
        $_cmp__55 = $this->moved->CompareTo($other->moved);
        if ($_cmp__55 != 0) {
            return $_cmp__55;
        }        
        return 0;
    }
}

#32
class PlayCard_PlayDynamite extends PlayCard implements IComparable {
    public $path;
    function __construct($path) {
        $this->path = $path;
    }
    function get_FSharpCase() {
        return 'PlayDynamite';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__56 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__56 != 0) {
            return $_cmp__56;
        }        
        $_cmp__57 = $this->path->CompareTo($other->path);
        if ($_cmp__57 != 0) {
            return $_cmp__57;
        }        
        return 0;
    }
}

#32
class PlayCard_PlayHighVoltage extends PlayCard implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PlayHighVoltage';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__58 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__58;
    }
}

#32
class PlayCard_PlayWatchdog extends PlayCard implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PlayWatchdog';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__59 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__59;
    }
}

#32
class PlayCard_PlayHelicopter extends PlayCard implements IComparable {
    public $destination;
    function __construct($destination) {
        $this->destination = $destination;
    }
    function get_FSharpCase() {
        return 'PlayHelicopter';
    }
    function get_Tag() {
        return 6;
    }
    function CompareTo($other) {
        $_cmp__60 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__60 != 0) {
            return $_cmp__60;
        }        
        $_cmp__61 = $this->destination->CompareTo($other->destination);
        if ($_cmp__61 != 0) {
            return $_cmp__61;
        }        
        return 0;
    }
}

#32
class PlayCard_PlayBribe extends PlayCard implements IComparable {
    public $parcel;
    function __construct($parcel) {
        $this->parcel = $parcel;
    }
    function get_FSharpCase() {
        return 'PlayBribe';
    }
    function get_Tag() {
        return 7;
    }
    function CompareTo($other) {
        $_cmp__62 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__62 != 0) {
            return $_cmp__62;
        }        
        $_cmp__63 = $this->parcel->CompareTo($other->parcel);
        if ($_cmp__63 != 0) {
            return $_cmp__63;
        }        
        return 0;
    }
}

#32
class PlayCard_PlayGameOver extends PlayCard implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PlayGameOver';
    }
    function get_Tag() {
        return 8;
    }
    function CompareTo($other) {
        $_cmp__64 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__64;
    }
}

#33
function CardModule_ofPlayCard($_arg1) {
    switch ($_arg1->get_Tag())
    {
        case 1:
            return new Card_Rut();
        case 2:
            if (\FSharpList\length($_arg1->path) < 2) {
                return new Card_HayBale(new CardPower_One());
            } else {
                return new Card_HayBale(new CardPower_Two());
            }
        case 3:
            return new Card_Dynamite();
        case 4:
            return new Card_HighVoltage();
        case 5:
            return new Card_Watchdog();
        case 6:
            return new Card_Helicopter();
        case 7:
            return new Card_Bribe();
        case 8:
            return new Card_GameOver();
        default:
            return new Card_Nitro($_arg1->power);
    }
}

#34
abstract class Hand implements FSharpUnion {
}

#34
class Hand_PrivateHand extends Hand implements IComparable {
    public $cards;
    function __construct($cards) {
        $this->cards = $cards;
    }
    function get_FSharpCase() {
        return 'PrivateHand';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__65 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__65 != 0) {
            return $_cmp__65;
        }        
        $_cmp__66 = $this->cards > $other->cards ? 1 : ($this->cards < $other->cards ? -1 : 0);
        if ($_cmp__66 != 0) {
            return $_cmp__66;
        }        
        return 0;
    }
}

#34
class Hand_PublicHand extends Hand implements IComparable {
    public $cards;
    function __construct($cards) {
        $this->cards = $cards;
    }
    function get_FSharpCase() {
        return 'PublicHand';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__67 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__67 != 0) {
            return $_cmp__67;
        }        
        $_cmp__68 = $this->cards->CompareTo($other->cards);
        if ($_cmp__68 != 0) {
            return $_cmp__68;
        }        
        return 0;
    }
}

#35
$GLOBALS['HandModule_empty'] = new Hand_PrivateHand(0);

#36
function HandModule_isEmpty($_arg1) {
    switch ($_arg1->get_Tag())
    {
        case 0:
            return $_arg1->cards === 0;
        default:
            return $_arg1->cards instanceof Nil;
    }
}

#37
function HandModule_isPublic($_arg1) {
    switch ($_arg1->get_Tag())
    {
        case 0:
            return false;
        default:
            return true;
    }
}

#38
function HandModule_toPrivate($_arg1) {
    if ($_arg1->get_Tag() == 1) {
        return new Hand_PrivateHand(\FSharpList\length($_arg1->cards));
    } else {
        return $_arg1;
    }
}

#39
function HandModule_count($_arg1) {
    switch ($_arg1->get_Tag())
    {
        case 0:
            return $_arg1->cards;
        default:
            return \FSharpList\length($_arg1->cards);
    }
}

#40
function HandModule_contains($card, $_arg1) {
    switch ($_arg1->get_Tag())
    {
        case 0:
            return false;
        default:
            return \FSharpList\contains($card, $_arg1->cards, [ 'Equals' => function ($x, $y) {             return \Util\equals($x, $y);
 }, 'GetHashCode' => function ($x) {             return \Util\safeHash($x);
 }]);
    }
}

#41
function HandModule_remove($card, $hand) {
    switch ($hand->get_Tag())
    {
        case 0:
            return new Hand_PrivateHand(($hand->cards - 1));
        default:
            $p = $hand->cards;
            $matchValue = \FSharpList\tryFindIndex(function ($c) use ($card) {             return \Util\equals($c, $card);
 }, $p);
            if (is_null($matchValue)) {
                return $hand;
            } else {
                $patternInput = \FSharpList\splitAt($matchValue, $p);
                return new Hand_PublicHand(\FSharpList\append($patternInput[0], \FSharpList\tail($patternInput[1])));
            }
    }
}

#42
function HandModule_canPlay($_arg1) {
    switch ($_arg1->get_Tag())
    {
        case 0:
            return $_arg1->cards > 0;
        default:
            return !$_arg1->cards instanceof Nil;
    }
}

#43
function HandModule_shouldDiscard($_arg1) {
    switch ($_arg1->get_Tag())
    {
        case 0:
            return $_arg1->cards > 6;
        default:
            return \FSharpList\length($_arg1->cards) > 6;
    }
}

#44
abstract class CrazyPlayer implements FSharpUnion {
}

#44
class CrazyPlayer_Starting extends CrazyPlayer implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Starting';
    }
    function get_Tag() {
        return 0;
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

#44
class CrazyPlayer_Playing extends CrazyPlayer implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Playing';
    }
    function get_Tag() {
        return 1;
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
class CrazyPlayer_Ko extends CrazyPlayer implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Ko';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__73 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__73 != 0) {
            return $_cmp__73;
        }        
        $_cmp__74 = $this->Item->CompareTo($other->Item);
        if ($_cmp__74 != 0) {
            return $_cmp__74;
        }        
        return 0;
    }
}

#45
class Starting implements IComparable {
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
    function CompareTo($other) {
        $_cmp__75 = $this->Color->CompareTo($other->Color);
        if ($_cmp__75 != 0) {
            return $_cmp__75;
        }        
        $_cmp__76 = $this->Parcel->CompareTo($other->Parcel);
        if ($_cmp__76 != 0) {
            return $_cmp__76;
        }        
        $_cmp__77 = $this->Hand->CompareTo($other->Hand);
        if ($_cmp__77 != 0) {
            return $_cmp__77;
        }        
        $_cmp__78 = $this->Bonus->CompareTo($other->Bonus);
        if ($_cmp__78 != 0) {
            return $_cmp__78;
        }        
        return 0;
    }
}

#46
class Playing implements IComparable {
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
    function CompareTo($other) {
        $_cmp__79 = $this->Color->CompareTo($other->Color);
        if ($_cmp__79 != 0) {
            return $_cmp__79;
        }        
        $_cmp__80 = $this->Tractor->CompareTo($other->Tractor);
        if ($_cmp__80 != 0) {
            return $_cmp__80;
        }        
        $_cmp__81 = $this->Fence->CompareTo($other->Fence);
        if ($_cmp__81 != 0) {
            return $_cmp__81;
        }        
        $_cmp__82 = $this->Field->CompareTo($other->Field);
        if ($_cmp__82 != 0) {
            return $_cmp__82;
        }        
        $_cmp__83 = $this->Power->CompareTo($other->Power);
        if ($_cmp__83 != 0) {
            return $_cmp__83;
        }        
        $_cmp__84 = $this->Moves->CompareTo($other->Moves);
        if ($_cmp__84 != 0) {
            return $_cmp__84;
        }        
        $_cmp__85 = $this->Hand->CompareTo($other->Hand);
        if ($_cmp__85 != 0) {
            return $_cmp__85;
        }        
        $_cmp__86 = $this->Bonus->CompareTo($other->Bonus);
        if ($_cmp__86 != 0) {
            return $_cmp__86;
        }        
        return 0;
    }
}

#47
class Moves implements IComparable {
    public $Capacity;
    public $Done;
    public $Acceleration;
    function __construct($Capacity, $Done, $Acceleration) {
        $this->Capacity = $Capacity;
        $this->Done = $Done;
        $this->Acceleration = $Acceleration;
    }
    function CompareTo($other) {
        $_cmp__87 = $this->Capacity > $other->Capacity ? 1 : ($this->Capacity < $other->Capacity ? -1 : 0);
        if ($_cmp__87 != 0) {
            return $_cmp__87;
        }        
        $_cmp__88 = $this->Done > $other->Done ? 1 : ($this->Done < $other->Done ? -1 : 0);
        if ($_cmp__88 != 0) {
            return $_cmp__88;
        }        
        $_cmp__89 = $this->Acceleration->CompareTo($other->Acceleration);
        if ($_cmp__89 != 0) {
            return $_cmp__89;
        }        
        return 0;
    }
}

#48
class Bonus implements IComparable {
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
    function CompareTo($other) {
        $_cmp__90 = $this->NitroOne > $other->NitroOne ? 1 : ($this->NitroOne < $other->NitroOne ? -1 : 0);
        if ($_cmp__90 != 0) {
            return $_cmp__90;
        }        
        $_cmp__91 = $this->NitroTwo > $other->NitroTwo ? 1 : ($this->NitroTwo < $other->NitroTwo ? -1 : 0);
        if ($_cmp__91 != 0) {
            return $_cmp__91;
        }        
        $_cmp__92 = $this->Watched->CompareTo($other->Watched);
        if ($_cmp__92 != 0) {
            return $_cmp__92;
        }        
        $_cmp__93 = $this->HighVoltage->CompareTo($other->HighVoltage);
        if ($_cmp__93 != 0) {
            return $_cmp__93;
        }        
        $_cmp__94 = $this->Rutted > $other->Rutted ? 1 : ($this->Rutted < $other->Rutted ? -1 : 0);
        if ($_cmp__94 != 0) {
            return $_cmp__94;
        }        
        $_cmp__95 = $this->Heliported > $other->Heliported ? 1 : ($this->Heliported < $other->Heliported ? -1 : 0);
        if ($_cmp__95 != 0) {
            return $_cmp__95;
        }        
        return 0;
    }
}

#49
class GameTable implements IComparable {
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
    function CompareTo($other) {
        $_cmp__96 = $this->Players->CompareTo($other->Players);
        if ($_cmp__96 != 0) {
            return $_cmp__96;
        }        
        $_cmp__97 = $this->AllPlayers->CompareTo($other->AllPlayers);
        if ($_cmp__97 != 0) {
            return $_cmp__97;
        }        
        $_cmp__98 = $this->Names->CompareTo($other->Names);
        if ($_cmp__98 != 0) {
            return $_cmp__98;
        }        
        $_cmp__99 = $this->Current > $other->Current ? 1 : ($this->Current < $other->Current ? -1 : 0);
        if ($_cmp__99 != 0) {
            return $_cmp__99;
        }        
        return 0;
    }
    function get_Player() {
        return $this->Players[$this->Current];
    }
    function get_Next() {
        return new GameTable($this->Players, $this->AllPlayers, $this->Names, (($this->Current + 1) % count($this->Players)));
    }
}

#52
function Table_start($players) {
    $allplayers = \Seq\toArray(\Seq\delay(function ($unitVar) use ($players) {     return \Seq\collect(function ($matchValue) {     return \Seq\singleton($matchValue[0]);
 }, $players);
 }));
    return new GameTable($allplayers, $allplayers, \Map\ofList($players), 0);
}

#53
function Table_eliminate($player, $table) {
    $index = \FSharpArray\findIndex(function ($p) use ($player) {     return $p === $player;
 }, $table->Players);
    $newPlayers = \FSharpArray\filter(function ($p_1) use ($player) {     return $p_1 !== $player;
 }, $table->Players);
    return new GameTable($newPlayers, $table->AllPlayers, $table->Names, ($table->Current <= $index ? $table->Current % count($newPlayers) : $table->Current - 1));
}

#54
function Table_isCurrent($playerid, $table) {
    return $table->get_Player() === $playerid;
}

#55
$GLOBALS['BonusModule_empty'] = new Bonus(0, 0, false, false, 0, 0);

#56
function BonusModule_startTurn($bonus) {
    return \Seq\toList(\Seq\delay(function ($unitVar) use ($bonus) {     return \Seq\append($bonus->HighVoltage ? \Seq\singleton(new Card_HighVoltage()) : \Seq\_empty(), \Seq\delay(function ($unitVar_1) use ($bonus) {     if ($bonus->Watched) {
        return \Seq\singleton(new Card_Watchdog());
    } else {
        return \Seq\_empty();
    }
 }));
 }));
}

#57
function BonusModule_endTurn($bonus) {
    return \Seq\toList(\Seq\delay(function ($unitVar) use ($bonus) {     return \Seq\append(\Seq\collect(function ($matchValue) {     return \Seq\singleton(new Card_Nitro(new CardPower_One()));
 }, \Range\rangeDouble(1, 1, $bonus->NitroOne)), \Seq\delay(function ($unitVar_1) use ($bonus) {     return \Seq\append(\Seq\collect(function ($matchValue_1) {     return \Seq\singleton(new Card_Nitro(new CardPower_Two()));
 }, \Range\rangeDouble(1, 1, $bonus->NitroTwo)), \Seq\delay(function ($unitVar_2) use ($bonus) {     return \Seq\append(\Seq\collect(function ($matchValue_2) {     return \Seq\singleton(new Card_Rut());
 }, \Range\rangeDouble(1, 1, $bonus->Rutted)), \Seq\delay(function ($unitVar_3) use ($bonus) {     return \Seq\collect(function ($matchValue_3) {     return \Seq\singleton(new Card_Helicopter());
 }, \Range\rangeDouble(1, 1, $bonus->Heliported));
 }));
 }));
 }));
 }));
}

#58
function BonusModule_moveCapacityChange($bonus) {
    return $bonus->Rutted * -2;
}

#59
function BonusModule_discard($card, $bonus) {
    if ($card->get_Tag() == 0) {
        switch ($card->power->get_Tag())
        {
            case 1:
                return new Bonus($bonus->NitroOne, ($bonus->NitroTwo - 1), $bonus->Watched, $bonus->HighVoltage, $bonus->Rutted, $bonus->Heliported);
            default:
                return new Bonus(($bonus->NitroOne - 1), $bonus->NitroTwo, $bonus->Watched, $bonus->HighVoltage, $bonus->Rutted, $bonus->Heliported);
        }
    } else {
        switch ($card->get_Tag())
        {
            case 5:
                return new Bonus($bonus->NitroOne, $bonus->NitroTwo, false, $bonus->HighVoltage, $bonus->Rutted, $bonus->Heliported);
            case 4:
                return new Bonus($bonus->NitroOne, $bonus->NitroTwo, $bonus->Watched, false, $bonus->Rutted, $bonus->Heliported);
            case 1:
                return new Bonus($bonus->NitroOne, $bonus->NitroTwo, $bonus->Watched, $bonus->HighVoltage, ($bonus->Rutted - 1), $bonus->Heliported);
            case 6:
                return new Bonus($bonus->NitroOne, $bonus->NitroTwo, $bonus->Watched, $bonus->HighVoltage, $bonus->Rutted, ($bonus->Heliported - 1));
            default:
                return $bonus;
        }
    }
}

#60
class PlayerPosition implements IComparable {
    public $Player;
    public $TractorPos;
    public $FencePos;
    public $FieldPos;
    function __construct($Player, $TractorPos, $FencePos, $FieldPos) {
        $this->Player = $Player;
        $this->TractorPos = $TractorPos;
        $this->FencePos = $FencePos;
        $this->FieldPos = $FieldPos;
    }
    function CompareTo($other) {
        $_cmp__100 = $this->Player > $other->Player ? 1 : ($this->Player < $other->Player ? -1 : 0);
        if ($_cmp__100 != 0) {
            return $_cmp__100;
        }        
        $_cmp__101 = $this->TractorPos->CompareTo($other->TractorPos);
        if ($_cmp__101 != 0) {
            return $_cmp__101;
        }        
        $_cmp__102 = $this->FencePos->CompareTo($other->FencePos);
        if ($_cmp__102 != 0) {
            return $_cmp__102;
        }        
        $_cmp__103 = $this->FieldPos->CompareTo($other->FieldPos);
        if ($_cmp__103 != 0) {
            return $_cmp__103;
        }        
        return 0;
    }
}

#61
class BoardPosition implements IComparable {
    public $Positions;
    function __construct($Positions) {
        $this->Positions = $Positions;
    }
    function CompareTo($other) {
        $_cmp__104 = $this->Positions->CompareTo($other->Positions);
        if ($_cmp__104 != 0) {
            return $_cmp__104;
        }        
        return 0;
    }
}

#62
class History implements IComparable {
    public $PlayersHistory;
    function __construct($PlayersHistory) {
        $this->PlayersHistory = $PlayersHistory;
    }
    function CompareTo($other) {
        $_cmp__105 = $this->PlayersHistory->CompareTo($other->PlayersHistory);
        if ($_cmp__105 != 0) {
            return $_cmp__105;
        }        
        return 0;
    }
}

#63
abstract class Board implements FSharpUnion {
}

#63
class Board_InitialState extends Board implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'InitialState';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__106 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__106;
    }
}

#63
class Board_Board extends Board implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Board';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__107 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__107 != 0) {
            return $_cmp__107;
        }        
        $_cmp__108 = $this->Item->CompareTo($other->Item);
        if ($_cmp__108 != 0) {
            return $_cmp__108;
        }        
        return 0;
    }
}

#63
class Board_Won extends Board implements IComparable {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
    function get_FSharpCase() {
        return 'Won';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__109 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__109 != 0) {
            return $_cmp__109;
        }        
        $_cmp__110 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__110 != 0) {
            return $_cmp__110;
        }        
        $_cmp__111 = $this->Item2->CompareTo($other->Item2);
        if ($_cmp__111 != 0) {
            return $_cmp__111;
        }        
        return 0;
    }
}

#64
class PlayingBoard implements IComparable {
    public $Players;
    public $Table;
    public $DrawPile;
    public $DiscardPile;
    public $Barns;
    public $HayBales;
    public $Goal;
    public $UseGameOver;
    public $History;
    function __construct($Players, $Table, $DrawPile, $DiscardPile, $Barns, $HayBales, $Goal, $UseGameOver, $History) {
        $this->Players = $Players;
        $this->Table = $Table;
        $this->DrawPile = $DrawPile;
        $this->DiscardPile = $DiscardPile;
        $this->Barns = $Barns;
        $this->HayBales = $HayBales;
        $this->Goal = $Goal;
        $this->UseGameOver = $UseGameOver;
        $this->History = $History;
    }
    function CompareTo($other) {
        $_cmp__112 = $this->Players->CompareTo($other->Players);
        if ($_cmp__112 != 0) {
            return $_cmp__112;
        }        
        $_cmp__113 = $this->Table->CompareTo($other->Table);
        if ($_cmp__113 != 0) {
            return $_cmp__113;
        }        
        $_cmp__114 = $this->DrawPile->CompareTo($other->DrawPile);
        if ($_cmp__114 != 0) {
            return $_cmp__114;
        }        
        $_cmp__115 = $this->DiscardPile->CompareTo($other->DiscardPile);
        if ($_cmp__115 != 0) {
            return $_cmp__115;
        }        
        $_cmp__116 = $this->Barns->CompareTo($other->Barns);
        if ($_cmp__116 != 0) {
            return $_cmp__116;
        }        
        $_cmp__117 = $this->HayBales->CompareTo($other->HayBales);
        if ($_cmp__117 != 0) {
            return $_cmp__117;
        }        
        $_cmp__118 = $this->Goal->CompareTo($other->Goal);
        if ($_cmp__118 != 0) {
            return $_cmp__118;
        }        
        $_cmp__119 = $this->UseGameOver->CompareTo($other->UseGameOver);
        if ($_cmp__119 != 0) {
            return $_cmp__119;
        }        
        $_cmp__120 = $this->History->CompareTo($other->History);
        if ($_cmp__120 != 0) {
            return $_cmp__120;
        }        
        return 0;
    }
}

#65
class UndoableBoard implements IComparable {
    public $Board;
    public $UndoPoint;
    public $UndoType;
    public $ShouldShuffle;
    public $AtUndoPoint;
    public $Undone;
    function __construct($Board, $UndoPoint, $UndoType, $ShouldShuffle, $AtUndoPoint, $Undone) {
        $this->Board = $Board;
        $this->UndoPoint = $UndoPoint;
        $this->UndoType = $UndoType;
        $this->ShouldShuffle = $ShouldShuffle;
        $this->AtUndoPoint = $AtUndoPoint;
        $this->Undone = $Undone;
    }
    function CompareTo($other) {
        $_cmp__121 = $this->Board->CompareTo($other->Board);
        if ($_cmp__121 != 0) {
            return $_cmp__121;
        }        
        $_cmp__122 = $this->UndoPoint->CompareTo($other->UndoPoint);
        if ($_cmp__122 != 0) {
            return $_cmp__122;
        }        
        $_cmp__123 = $this->UndoType->CompareTo($other->UndoType);
        if ($_cmp__123 != 0) {
            return $_cmp__123;
        }        
        $_cmp__124 = $this->ShouldShuffle->CompareTo($other->ShouldShuffle);
        if ($_cmp__124 != 0) {
            return $_cmp__124;
        }        
        $_cmp__125 = $this->AtUndoPoint->CompareTo($other->AtUndoPoint);
        if ($_cmp__125 != 0) {
            return $_cmp__125;
        }        
        $_cmp__126 = $this->Undone->CompareTo($other->Undone);
        if ($_cmp__126 != 0) {
            return $_cmp__126;
        }        
        return 0;
    }
}

#66
abstract class PlayerState implements FSharpUnion {
}

#66
class PlayerState_SStarting extends PlayerState implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'SStarting';
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

#66
class PlayerState_SPlaying extends PlayerState implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'SPlaying';
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

#66
class PlayerState_SKo extends PlayerState implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'SKo';
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

#67
class StartingState implements IComparable {
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
    function CompareTo($other) {
        $_cmp__133 = $this->SColor->CompareTo($other->SColor);
        if ($_cmp__133 != 0) {
            return $_cmp__133;
        }        
        $_cmp__134 = $this->SParcel->CompareTo($other->SParcel);
        if ($_cmp__134 != 0) {
            return $_cmp__134;
        }        
        $_cmp__135 = $this->SHand->CompareTo($other->SHand);
        if ($_cmp__135 != 0) {
            return $_cmp__135;
        }        
        $_cmp__136 = $this->SBonus->CompareTo($other->SBonus);
        if ($_cmp__136 != 0) {
            return $_cmp__136;
        }        
        return 0;
    }
}

#68
class PlayingState implements IComparable {
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
    function CompareTo($other) {
        $_cmp__137 = $this->SColor->CompareTo($other->SColor);
        if ($_cmp__137 != 0) {
            return $_cmp__137;
        }        
        $_cmp__138 = $this->STractor->CompareTo($other->STractor);
        if ($_cmp__138 != 0) {
            return $_cmp__138;
        }        
        $_cmp__139 = $this->SFence->CompareTo($other->SFence);
        if ($_cmp__139 != 0) {
            return $_cmp__139;
        }        
        $_cmp__140 = $this->SField->CompareTo($other->SField);
        if ($_cmp__140 != 0) {
            return $_cmp__140;
        }        
        $_cmp__141 = $this->SPower->CompareTo($other->SPower);
        if ($_cmp__141 != 0) {
            return $_cmp__141;
        }        
        $_cmp__142 = $this->SMoves->CompareTo($other->SMoves);
        if ($_cmp__142 != 0) {
            return $_cmp__142;
        }        
        $_cmp__143 = $this->SHand->CompareTo($other->SHand);
        if ($_cmp__143 != 0) {
            return $_cmp__143;
        }        
        $_cmp__144 = $this->SBonus->CompareTo($other->SBonus);
        if ($_cmp__144 != 0) {
            return $_cmp__144;
        }        
        return 0;
    }
}

#69
class BoardState implements IComparable {
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
    public $SHistory;
    function __construct($SPlayers, $STable, $SDiscardPile, $SDrawPile, $SFreeBarns, $SOccupiedBarns, $SHayBales, $SGoal, $SWinner, $SWinners, $SUseGameOver, $SHistory) {
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
        $this->SHistory = $SHistory;
    }
    function CompareTo($other) {
        $_cmp__145 = $this->SPlayers->CompareTo($other->SPlayers);
        if ($_cmp__145 != 0) {
            return $_cmp__145;
        }        
        $_cmp__146 = $this->STable->CompareTo($other->STable);
        if ($_cmp__146 != 0) {
            return $_cmp__146;
        }        
        $_cmp__147 = $this->SDiscardPile->CompareTo($other->SDiscardPile);
        if ($_cmp__147 != 0) {
            return $_cmp__147;
        }        
        $_cmp__148 = $this->SDrawPile->CompareTo($other->SDrawPile);
        if ($_cmp__148 != 0) {
            return $_cmp__148;
        }        
        $_cmp__149 = $this->SFreeBarns->CompareTo($other->SFreeBarns);
        if ($_cmp__149 != 0) {
            return $_cmp__149;
        }        
        $_cmp__150 = $this->SOccupiedBarns->CompareTo($other->SOccupiedBarns);
        if ($_cmp__150 != 0) {
            return $_cmp__150;
        }        
        $_cmp__151 = $this->SHayBales->CompareTo($other->SHayBales);
        if ($_cmp__151 != 0) {
            return $_cmp__151;
        }        
        $_cmp__152 = $this->SGoal->CompareTo($other->SGoal);
        if ($_cmp__152 != 0) {
            return $_cmp__152;
        }        
        $_cmp__153 = $this->SWinner > $other->SWinner ? 1 : ($this->SWinner < $other->SWinner ? -1 : 0);
        if ($_cmp__153 != 0) {
            return $_cmp__153;
        }        
        $_cmp__154 = $this->SWinners->CompareTo($other->SWinners);
        if ($_cmp__154 != 0) {
            return $_cmp__154;
        }        
        $_cmp__155 = $this->SUseGameOver->CompareTo($other->SUseGameOver);
        if ($_cmp__155 != 0) {
            return $_cmp__155;
        }        
        $_cmp__156 = $this->SHistory->CompareTo($other->SHistory);
        if ($_cmp__156 != 0) {
            return $_cmp__156;
        }        
        return 0;
    }
}

#70
class STable implements IComparable {
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
    function CompareTo($other) {
        $_cmp__157 = $this->SPlayers->CompareTo($other->SPlayers);
        if ($_cmp__157 != 0) {
            return $_cmp__157;
        }        
        $_cmp__158 = $this->SAllPlayers->CompareTo($other->SAllPlayers);
        if ($_cmp__158 != 0) {
            return $_cmp__158;
        }        
        $_cmp__159 = $this->SNames->CompareTo($other->SNames);
        if ($_cmp__159 != 0) {
            return $_cmp__159;
        }        
        $_cmp__160 = $this->SCurrent > $other->SCurrent ? 1 : ($this->SCurrent < $other->SCurrent ? -1 : 0);
        if ($_cmp__160 != 0) {
            return $_cmp__160;
        }        
        return 0;
    }
}

#71
class UndoBoardState implements IComparable {
    public $SBoard;
    public $SUndoPoint;
    public $SUndoType;
    public $SShouldShuffle;
    public $SAtUndoPoint;
    public $SUndone;
    function __construct($SBoard, $SUndoPoint, $SUndoType, $SShouldShuffle, $SAtUndoPoint, $SUndone) {
        $this->SBoard = $SBoard;
        $this->SUndoPoint = $SUndoPoint;
        $this->SUndoType = $SUndoType;
        $this->SShouldShuffle = $SShouldShuffle;
        $this->SAtUndoPoint = $SAtUndoPoint;
        $this->SUndone = $SUndone;
    }
    function CompareTo($other) {
        $_cmp__161 = $this->SBoard->CompareTo($other->SBoard);
        if ($_cmp__161 != 0) {
            return $_cmp__161;
        }        
        $_cmp__162 = $this->SUndoPoint->CompareTo($other->SUndoPoint);
        if ($_cmp__162 != 0) {
            return $_cmp__162;
        }        
        $_cmp__163 = $this->SUndoType > $other->SUndoType ? 1 : ($this->SUndoType < $other->SUndoType ? -1 : 0);
        if ($_cmp__163 != 0) {
            return $_cmp__163;
        }        
        $_cmp__164 = $this->SShouldShuffle->CompareTo($other->SShouldShuffle);
        if ($_cmp__164 != 0) {
            return $_cmp__164;
        }        
        $_cmp__165 = $this->SAtUndoPoint->CompareTo($other->SAtUndoPoint);
        if ($_cmp__165 != 0) {
            return $_cmp__165;
        }        
        $_cmp__166 = $this->SUndone->CompareTo($other->SUndone);
        if ($_cmp__166 != 0) {
            return $_cmp__166;
        }        
        return 0;
    }
}

#72
function CrossroadModule_neighbor($dir, $_arg1) {
    $tile = $_arg1->tile;
    $matchValue = [ $_arg1->side, $dir];
    if ($matchValue[0]->get_Tag() == 1) {
        switch ($matchValue[1]->get_Tag())
        {
            case 1:
                $tupledArg = [ Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_SE']), new CrossroadSide_CLeft()];
                break;
            case 2:
                $tupledArg = [ Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_E2']), new CrossroadSide_CLeft()];
                break;
            default:
                $tupledArg = [ Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_NE']), new CrossroadSide_CLeft()];
                break;
        }
    } else {
        switch ($matchValue[1]->get_Tag())
        {
            case 1:
                $tupledArg = [ Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_SW']), new CrossroadSide_CRight()];
                break;
            case 2:
                $tupledArg = [ Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_W2']), new CrossroadSide_CRight()];
                break;
            default:
                $tupledArg = [ Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_NW']), new CrossroadSide_CRight()];
                break;
        }
    }
    return new Crossroad($tupledArg[0], $tupledArg[1]);
}

#73
function CrossroadModule_neighborTiles($_arg1) {
    $p = new Parcel($_arg1->tile);
    switch ($_arg1->side->get_Tag())
    {
        case 1:
            return new Cons($p, new Cons(Parcel_op_Addition_ZF6EFE4B($p, $GLOBALS['AxeModule_NE']), new Cons(Parcel_op_Addition_ZF6EFE4B($p, $GLOBALS['AxeModule_SE']), $GLOBALS['NIL'])));
        default:
            return new Cons($p, new Cons(Parcel_op_Addition_ZF6EFE4B($p, $GLOBALS['AxeModule_NW']), new Cons(Parcel_op_Addition_ZF6EFE4B($p, $GLOBALS['AxeModule_SW']), $GLOBALS['NIL'])));
    }
}

#74
function CrossroadModule_tile($_arg1) {
    return $_arg1->tile;
}

#75
function CrossroadModule_side($_arg1) {
    return $_arg1->side;
}

#76
function CrossroadModule_isInField($_arg2, $_arg1) {
    $parcels = $_arg2->parcels;
    $p = new Parcel($_arg1->tile);
    switch ($_arg1->side->get_Tag())
    {
        case 1:
            if (\Set\contains($p, $parcels) ? true : \Set\contains(Parcel_op_Addition_ZF6EFE4B($p, $GLOBALS['AxeModule_NE']), $parcels)) {
                return true;
            } else {
                return \Set\contains(Parcel_op_Addition_ZF6EFE4B($p, $GLOBALS['AxeModule_SE']), $parcels);
            }
        default:
            if (\Set\contains($p, $parcels) ? true : \Set\contains(Parcel_op_Addition_ZF6EFE4B($p, $GLOBALS['AxeModule_NW']), $parcels)) {
                return true;
            } else {
                return \Set\contains(Parcel_op_Addition_ZF6EFE4B($p, $GLOBALS['AxeModule_SW']), $parcels);
            }
    }
}

#77
function CrossroadModule_isOnBoard($_arg1) {
    $patternInput = AxeModule_cube($_arg1->tile);
    $z = $patternInput[2];
    $y = $patternInput[1];
    $x = $patternInput[0];
    switch ($_arg1->side->get_Tag())
    {
        case 1:
            if (((($x >= -4 ? $x < 4 : false) ? $y > -4 : false) ? $y <= 4 : false) ? $z > -4 : false) {
                return $z <= 4;
            } else {
                return false;
            }
        default:
            if (((($x > -4 ? $x <= 4 : false) ? $y >= -4 : false) ? $y < 4 : false) ? $z >= -4 : false) {
                return $z < 4;
            } else {
                return false;
            }
    }
}

#78
$GLOBALS['ParcelModule_center'] = new Parcel($GLOBALS['AxeModule_center']);

#79
function ParcelModule_crossroads($_arg1) {
    $p = $_arg1->tile;
    return new Cons(new Crossroad($p, new CrossroadSide_CLeft()), new Cons(new Crossroad($p, new CrossroadSide_CRight()), new Cons(new Crossroad(Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_NW']), new CrossroadSide_CRight()), new Cons(new Crossroad(Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_NE']), new CrossroadSide_CLeft()), new Cons(new Crossroad(Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_SW']), new CrossroadSide_CRight()), new Cons(new Crossroad(Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_SE']), new CrossroadSide_CLeft()), $GLOBALS['NIL']))))));
}

#80
function ParcelModule_contains($_arg2, $_arg1) {
    $t = $_arg2->tile;
    $s = $_arg2->side;
    $p = $_arg1->tile;
    if (\Util\equals($t, $p) ? true : (\Util\equals($s, new CrossroadSide_CRight()) ? (\Util\equals($t, Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_NW'])) ? true : \Util\equals($t, Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_SW']))) : false)) {
        return true;
    } else {
        if (\Util\equals($s, new CrossroadSide_CLeft())) {
            if (\Util\equals($t, Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_NE']))) {
                return true;
            } else {
                return \Util\equals($t, Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_SE']));
            }
        } else {
            return false;
        }
    }
}

#81
function ParcelModule_isOnBoard($_arg1) {
    $patternInput = AxeModule_cube($_arg1->tile);
    if (abs($patternInput[0]) <= 3 ? abs($patternInput[1]) <= 3 : false) {
        return abs($patternInput[2]) <= 3;
    } else {
        return false;
    }
}

#82
function ParcelModule_unrestrictedNeighbors($_arg1) {
    $p = $_arg1->tile;
    return new Cons(new Parcel(Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_N'])), new Cons(new Parcel(Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_NE'])), new Cons(new Parcel(Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_SE'])), new Cons(new Parcel(Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_S'])), new Cons(new Parcel(Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_SW'])), new Cons(new Parcel(Axe_op_Addition_2BE35040($p, $GLOBALS['AxeModule_NW'])), $GLOBALS['NIL']))))));
}

#83
function ParcelModule_neighbors($p) {
    return \FSharpList\filter(function ($arg00_0040) {     return ParcelModule_isOnBoard($arg00_0040);
 }, ParcelModule_unrestrictedNeighbors($p));
}

#84
function ParcelModule_areNeighbors($_arg2, $_arg1) {
    $px = $_arg2->tile;
    $py = $_arg1->tile;
    if ((((\Util\equals($px, Axe_op_Addition_2BE35040($py, $GLOBALS['AxeModule_N'])) ? true : \Util\equals($px, Axe_op_Addition_2BE35040($py, $GLOBALS['AxeModule_NE']))) ? true : \Util\equals($px, Axe_op_Addition_2BE35040($py, $GLOBALS['AxeModule_SE']))) ? true : \Util\equals($px, Axe_op_Addition_2BE35040($py, $GLOBALS['AxeModule_S']))) ? true : \Util\equals($px, Axe_op_Addition_2BE35040($py, $GLOBALS['AxeModule_SW']))) {
        return true;
    } else {
        return \Util\equals($px, Axe_op_Addition_2BE35040($py, $GLOBALS['AxeModule_NW']));
    }
}

#85
abstract class ParcelDir implements FSharpUnion {
}

#85
class ParcelDir_PN extends ParcelDir implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PN';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__167 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__167;
    }
}

#85
class ParcelDir_PNE extends ParcelDir implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PNE';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__168 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__168;
    }
}

#85
class ParcelDir_PSE extends ParcelDir implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PSE';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__169 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__169;
    }
}

#85
class ParcelDir_PS extends ParcelDir implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PS';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__170 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__170;
    }
}

#85
class ParcelDir_PSW extends ParcelDir implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PSW';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__171 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__171;
    }
}

#85
class ParcelDir_PNW extends ParcelDir implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PNW';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__172 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__172;
    }
}

#86
function ParcelModule_getDir($_arg2, $_arg1) {
    $px = $_arg2->tile;
    $py = $_arg1->tile;
    if (\Util\equals(Axe_op_Addition_2BE35040($px, $GLOBALS['AxeModule_N']), $py)) {
        return new ParcelDir_PN();
    } else {
        if (\Util\equals(Axe_op_Addition_2BE35040($px, $GLOBALS['AxeModule_NE']), $py)) {
            return new ParcelDir_PNE();
        } else {
            if (\Util\equals(Axe_op_Addition_2BE35040($px, $GLOBALS['AxeModule_SE']), $py)) {
                return new ParcelDir_PSE();
            } else {
                if (\Util\equals(Axe_op_Addition_2BE35040($px, $GLOBALS['AxeModule_S']), $py)) {
                    return new ParcelDir_PS();
                } else {
                    if (\Util\equals(Axe_op_Addition_2BE35040($px, $GLOBALS['AxeModule_SW']), $py)) {
                        return new ParcelDir_PSW();
                    } else {
                        return new ParcelDir_PNW();
                    }
                }
            }
        }
    }
}

#87
function ParcelModule_dir($n) {
    $matchValue = $n % 6;
    if ($matchValue === 0) {
        return new ParcelDir_PN();
    } else {
        if ($matchValue === 1) {
            return new ParcelDir_PNE();
        } else {
            if ($matchValue === 2) {
                return new ParcelDir_PSE();
            } else {
                if ($matchValue === 3) {
                    return new ParcelDir_PS();
                } else {
                    if ($matchValue === 4) {
                        return new ParcelDir_PSW();
                    } else {
                        return new ParcelDir_PNW();
                    }
                }
            }
        }
    }
}

#88
function ParcelModule_dirs($s, $n) {
    return \Set\ofSeq(\Seq\toList(\Seq\delay(function ($unitVar) use ($n, $s) {     return \Seq\map(function ($i) use ($s) {     return ParcelModule_dir($s + $i);
 }, \Range\rangeDouble(0, 1, $n));
 })), [ 'Compare' => function ($x, $y) {     return \Util\compare($x, $y);
 }]);
}

#89
function PathModule_neighbor($dir, $_arg1) {
    $tile = $_arg1->tile;
    $matchValue = [ $_arg1->side, $dir];
    if ($matchValue[0]->get_Tag() == 1) {
        switch ($matchValue[1]->get_Tag())
        {
            case 1:
                return new Path(Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_SE']), new BorderSide_BNW());
            case 2:
                return new Path(Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_SE']), new BorderSide_BN());
            default:
                return new Path($tile, new BorderSide_BNE());
        }
    } else {
        switch ($matchValue[1]->get_Tag())
        {
            case 1:
                return new Path(Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_SW']), new BorderSide_BNE());
            case 2:
                return new Path(Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_SW']), new BorderSide_BN());
            default:
                return new Path($tile, new BorderSide_BNW());
        }
    }
}

#90
function PathModule_tile($_arg1) {
    return $_arg1->tile;
}

#91
function PathModule_neighborTiles($_arg1) {
    $tile = $_arg1->tile;
    $side = $_arg1->border;
    switch ($side->get_Tag())
    {
        case 2:
            return Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_NE']);
        case 1:
            return Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_N']);
        default:
            return Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_NW']);
    }
}

#92
function PathModule_ofMoves($moves, $start) {
    return \FSharpList\mapFold(function ($pos, $move) {     return [ [ PathModule_neighbor($move, $pos), $move], CrossroadModule_neighbor($move, $pos)];
 }, $start, $moves);
}

#93
$GLOBALS['PathModule_allInnerPaths'] = \Set\ofSeq(\Seq\toList(\Seq\delay(function ($unitVar) { return \Seq\append(\Seq\collect(function ($q) { return \Seq\map(function ($r) use ($q) { return new Path(new Axe($q, $r), new BorderSide_BN());
 }, \Range\rangeDouble(\Util\max(function ($x, $y) { return \Util\comparePrimitives($x, $y);
 }, -2, -2 - $q), 1, \Util\min(function ($x_1, $y_1) { return \Util\comparePrimitives($x_1, $y_1);
 }, 3, 3 - $q)));
 }, \Range\rangeDouble(-3, 1, 3)), \Seq\delay(function ($unitVar_1) { return \Seq\append(\Seq\collect(function ($q_1) { return \Seq\map(function ($r_1) use ($q_1) { return new Path(new Axe($q_1, $r_1), new BorderSide_BNE());
 }, \Range\rangeDouble(\Util\max(function ($x_2, $y_2) { return \Util\comparePrimitives($x_2, $y_2);
 }, -2, -3 - $q_1), 1, \Util\min(function ($x_3, $y_3) { return \Util\comparePrimitives($x_3, $y_3);
 }, 3, 3 - $q_1)));
 }, \Range\rangeDouble(-3, 1, 2)), \Seq\delay(function ($unitVar_2) { return \Seq\collect(function ($q_2) { return \Seq\map(function ($r_2) use ($q_2) { return new Path(new Axe($q_2, $r_2), new BorderSide_BNW());
 }, \Range\rangeDouble(\Util\max(function ($x_4, $y_4) { return \Util\comparePrimitives($x_4, $y_4);
 }, -3, -2 - $q_2), 1, \Util\min(function ($x_5, $y_5) { return \Util\comparePrimitives($x_5, $y_5);
 }, 3, 3 - $q_2)));
 }, \Range\rangeDouble(-2, 1, 3));
 }));
 }));
 })), [ 'Compare' => function ($x_6, $y_6) { return \Util\compare($x_6, $y_6);
 }]);

#94
$GLOBALS['PathModule_boderPaths'] = \Set\ofSeq(\Seq\toList(\Seq\delay(function ($unitVar) { return \Seq\append(\Seq\collect(function ($r) { return \Seq\append(\Seq\singleton(new Path(new Axe(-3, $r), new BorderSide_BNW())), \Seq\delay(function ($unitVar_1) use ($r) { return \Seq\append(\Seq\singleton(new Path(Axe_op_Addition_2BE35040(new Axe(-3, $r), $GLOBALS['AxeModule_SW']), new BorderSide_BNE())), \Seq\delay(function ($unitVar_2) use ($r) { return \Seq\append(\Seq\singleton(new Path(new Axe(3, (-$r)), new BorderSide_BNE())), \Seq\delay(function ($unitVar_3) use ($r) { return \Seq\singleton(new Path(Axe_op_Addition_2BE35040(new Axe(3, (-$r)), $GLOBALS['AxeModule_SE']), new BorderSide_BNW()));
 }));
 }));
 }));
 }, \Range\rangeDouble(0, 1, 3)), \Seq\delay(function ($unitVar_4) { return \Seq\append(\Seq\collect(function ($q) { return \Seq\append(\Seq\singleton(new Path(new Axe($q, (-$q - 3)), new BorderSide_BNW())), \Seq\delay(function ($unitVar_5) use ($q) { return \Seq\append(\Seq\singleton(new Path(new Axe($q, (-$q - 3)), new BorderSide_BN())), \Seq\delay(function ($unitVar_6) use ($q) { return \Seq\append(\Seq\singleton(new Path(Axe_op_Addition_2BE35040(new Axe($q, 3), $GLOBALS['AxeModule_SW']), new BorderSide_BNE())), \Seq\delay(function ($unitVar_7) use ($q) { return \Seq\singleton(new Path(Axe_op_Addition_2BE35040(new Axe($q, 3), $GLOBALS['AxeModule_S']), new BorderSide_BN()));
 }));
 }));
 }));
 }, \Range\rangeDouble(-3, 1, 0)), \Seq\delay(function ($unitVar_8) { return \Seq\collect(function ($q_1) { return \Seq\append(\Seq\singleton(new Path(new Axe($q_1, -3), new BorderSide_BN())), \Seq\delay(function ($unitVar_9) use ($q_1) { return \Seq\append(\Seq\singleton(new Path(new Axe($q_1, -3), new BorderSide_BNE())), \Seq\delay(function ($unitVar_10) use ($q_1) { return \Seq\append(\Seq\singleton(new Path(Axe_op_Addition_2BE35040(new Axe($q_1, (3 - $q_1)), $GLOBALS['AxeModule_S']), new BorderSide_BN())), \Seq\delay(function ($unitVar_11) use ($q_1) { return \Seq\singleton(new Path(Axe_op_Addition_2BE35040(new Axe($q_1, (3 - $q_1)), $GLOBALS['AxeModule_SE']), new BorderSide_BNW()));
 }));
 }));
 }));
 }, \Range\rangeDouble(0, 1, 3));
 }));
 }));
 })), [ 'Compare' => function ($x, $y) { return \Util\compare($x, $y);
 }]);

#95
class LMax implements IComparable {
    public $Max;
    public $Left;
    public $Right;
    function __construct($Max, $Left, $Right) {
        $this->Max = $Max;
        $this->Left = $Left;
        $this->Right = $Right;
    }
    function CompareTo($other) {
        $_cmp__173 = $this->Max->CompareTo($other->Max);
        if ($_cmp__173 != 0) {
            return $_cmp__173;
        }        
        $_cmp__174 = $this->Left->CompareTo($other->Left);
        if ($_cmp__174 != 0) {
            return $_cmp__174;
        }        
        $_cmp__175 = $this->Right->CompareTo($other->Right);
        if ($_cmp__175 != 0) {
            return $_cmp__175;
        }        
        return 0;
    }
}

#96
abstract class OrientedPath implements FSharpUnion {
}

#96
class OrientedPath_DNE extends OrientedPath implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'DNE';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__176 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__176;
    }
}

#96
class OrientedPath_DNW extends OrientedPath implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'DNW';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__177 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__177;
    }
}

#96
class OrientedPath_DW extends OrientedPath implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'DW';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__178 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__178;
    }
}

#96
class OrientedPath_DSW extends OrientedPath implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'DSW';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__179 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__179;
    }
}

#96
class OrientedPath_DSE extends OrientedPath implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'DSE';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__180 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__180;
    }
}

#96
class OrientedPath_DE extends OrientedPath implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'DE';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__181 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__181;
    }
}

#97
$GLOBALS['FenceModule_empty'] = new Fence($GLOBALS['NIL']);

#98
function FenceModule_isEmpty($_arg1) {
    return $_arg1->paths instanceof Nil;
}

#99
function FenceModule_findLoop($dir, $pos, $_arg1) {
    $nextPos = CrossroadModule_neighbor($dir, $pos);
    $iter = function ($pos_1, $loop, $paths_1) use ($nextPos, &$iter) {     if ($paths_1 instanceof Cons) {
        $path = $paths_1->value[0];
        $dir_1 = $paths_1->value[1];
        $nextEnd = CrossroadModule_neighbor(DirectionModule_rev($dir_1), $pos_1);
        if (\Util\equals($nextEnd, $nextPos)) {
            return new Fence(new Cons([ $path, $dir_1], $loop));
        } else {
            return $iter($nextEnd, new Cons([ $path, $dir_1], $loop), $paths_1->next);
        }
    } else {
        return $GLOBALS['FenceModule_empty'];
    }
 };
    return $iter($pos, $GLOBALS['NIL'], $_arg1->paths);
}

#100
function FenceModule_add($path_0, $path_1, $_arg1) {
    return new Fence(new Cons([ $path_0, $path_1], $_arg1->paths));
}

#101
function FenceModule_tail($_arg1) {
    return new Fence(\FSharpList\tail($_arg1->paths));
}

#102
function FenceModule_fenceCrossroads($tractor, $_arg1) {
    $loop = function ($pos, $paths_1) use (&$loop) {     return \Seq\delay(function ($unitVar) use ($paths_1, $pos, &$loop) {     if ($paths_1 instanceof Cons) {
        $next = CrossroadModule_neighbor(DirectionModule_rev($paths_1->value[1]), $pos);
        return \Seq\append(\Seq\singleton($next), \Seq\delay(function ($unitVar_1) use ($next, $paths_1, &$loop) {         return $loop($next, $paths_1->next);
 }));
    } else {
        return \Seq\_empty();
    }
 });
 };
    return $loop($tractor, $_arg1->paths);
}

#103
function FenceModule_fencePaths($_arg1) {
    return \FSharpList\map(function ($tuple) {     return $tuple[0];
 }, $_arg1->paths);
}

#104
function FenceModule_bribeAnnexation($p, $tractor, $_arg1) {
    $paths = $_arg1->paths;
    $findExit = function ($remainingLength, $pos, $paths_1) use ($p, &$findExit) {     if ($paths_1 instanceof Cons) {
        $next = CrossroadModule_neighbor(DirectionModule_rev($paths_1->value[1]), $pos);
        if (ParcelModule_contains($next, $p)) {
            return $findExit($remainingLength, $next, $paths_1->next);
        } else {
            return [ $remainingLength, $pos, $paths_1];
        }
    } else {
        return [ $remainingLength, $pos, $GLOBALS['NIL']];
    }
 };
    $findContact = function ($remainingLength_1, $pos_1, $paths_2) use ($p, &$findContact, &$findExit) {     if ($paths_2 instanceof Cons) {
        $tail_1 = $paths_2->next;
        $next_1 = CrossroadModule_neighbor(DirectionModule_rev($paths_2->value[1]), $pos_1);
        if (ParcelModule_contains($next_1, $p)) {
            return $findExit($remainingLength_1 + 1, $next_1, $tail_1);
        } else {
            return $findContact($remainingLength_1 + 1, $next_1, $tail_1);
        }
    } else {
        return NULL;
    }
 };
    if (ParcelModule_contains($tractor, $p)) {
        return $findExit(0, $tractor, $paths);
    } else {
        return $findContact(0, $tractor, $paths);
    }
}

#105
function FenceModule_start($tractor, $_arg1) {
    $loop = function ($pos, $paths_1) use (&$loop) {     if ($paths_1 instanceof Cons) {
        return $loop(CrossroadModule_neighbor(DirectionModule_rev($paths_1->value[1]), $pos), $paths_1->next);
    } else {
        return $pos;
    }
 };
    return $loop($tractor, $_arg1->paths);
}

#106
function FenceModule_length($_arg1) {
    return \FSharpList\length($_arg1->paths);
}

#107
function FenceModule_remove($toRemove, $_arg1) {
    return new Fence(\FSharpList\skip(FenceModule_length($toRemove), $_arg1->paths));
}

#108
function FenceModule_truncate($count, $_arg1) {
    return new Fence(\FSharpList\truncate($count, $_arg1->paths));
}

#109
function FenceModule_toOriented($tractor, $_arg1) {
    $patternInput = \FSharpList\mapFold(function ($pos, $tupledArg) { 
        $dir = $tupledArg[1];
        return [ (function ($matchValue) {         if ($matchValue[0]->get_Tag() == 0) {
            switch ($matchValue[1]->get_Tag())
            {
                case 0:
                    return new OrientedPath_DNW();
                default:
                    return new OrientedPath_DNE();
            }
        } else {
            if ($matchValue[0]->get_Tag() == 1) {
                switch ($matchValue[1]->get_Tag())
                {
                    case 0:
                        return new OrientedPath_DSW();
                    default:
                        return new OrientedPath_DSE();
                }
            } else {
                switch ($matchValue[1]->get_Tag())
                {
                    case 0:
                        return new OrientedPath_DE();
                    default:
                        return new OrientedPath_DW();
                }
            }
        }
 })([ $dir, CrossroadModule_side($pos)]), CrossroadModule_neighbor($dir, $pos)];
    }, $tractor, $_arg1->paths);
    return [ \FSharpList\reverse($patternInput[0]), $patternInput[1]];
}

#110
function FenceModule_givesAcceleration($fence) {
    return FenceModule_length($fence) >= 4;
}

#111
function FenceOps__007CRwd_007C__007C($nextPath, $_arg1) {
    $paths = $_arg1->paths;
    if ($paths instanceof Cons) {
        if (\Util\equals($paths->value[0], $nextPath)) {
            $last_1 = $paths->value[0];
            return NULL;
        } else {
            return NULL;
        }
    } else {
        return NULL;
    }
}

#112
$GLOBALS['FieldModule_empty'] = new Field(\Set\_empty([ 'Compare' => function ($x, $y) { return \Util\compare($x, $y);
 }]));

#113
function FieldModule_isEmpty($_arg1) {
    return \Set\isEmpty($_arg1->parcels);
}

#114
function FieldModule_size($_arg1) {
    return \Set\count($_arg1->parcels);
}

#115
function FieldModule_create($parcel) {
    return new Field(\Set\ofSeq(new Cons($parcel, $GLOBALS['NIL']), [ 'Compare' => function ($x, $y) {     return \Util\compare($x, $y);
 }]));
}

#116
function FieldModule_ofParcels($parcels) {
    return new Field(\Set\ofSeq($parcels, [ 'Compare' => function ($x, $y) {     return \Util\compare($x, $y);
 }]));
}

#117
function FieldModule_parcels($_arg1) {
    return \Set\toList($_arg1->parcels);
}

#118
function FieldModule_contains($parcel, $_arg1) {
    return \Set\contains(new Parcel($parcel), $_arg1->parcels);
}

#119
function FieldModule_containsParcel($parcel, $_arg1) {
    return \Set\contains($parcel, $_arg1->parcels);
}

#120
function FieldModule_intersect($_arg2, $_arg1) {
    return new Field(\Set\intersect($_arg2->parcels, $_arg1->parcels));
}

#121
function FieldModule_unionMany($fields) {
    return new Field(\Set\ofSeq(\FSharpList\collect(function ($arg00_0040) {     return FieldModule_parcels($arg00_0040);
 }, $fields), [ 'Compare' => function ($x, $y) {     return \Util\compare($x, $y);
 }]));
}

#122
function FieldModule_crossroads($_arg1) {
    return \Set\ofSeq(\Seq\collect(function ($arg00_0040) {     return ParcelModule_crossroads($arg00_0040);
 }, $_arg1->parcels), [ 'Compare' => function ($x, $y) {     return \Util\compare($x, $y);
 }]);
}

#123
function FieldModule_fill($paths) {
    $sortedPaths = \Seq2\List_groupBy(function ($tile) {     return $tile->get_Q();
 }, \FSharpList\sortBy(function ($t_1) {     return [ $t_1->get_Q(), $t_1->get_R()];
 }, \FSharpList\choose(function ($_arg1) {     switch ($_arg1[1]->get_Tag())
    {
        case 2:
            $t = $_arg1[0]->tile;
            return $t;
        default:
            return NULL;
    }
 }, $paths), [ 'Compare' => function ($x, $y) {     return \Util\compareArrays($x, $y);
 }]), [ 'Equals' => function ($x_1, $y_1) {     return $x_1 === $y_1;
 }, 'GetHashCode' => function ($x_1) {     return \Util\numberHash($x_1);
 }]);
    return new Field(\Set\ofSeq(\Seq\toList(\Seq\delay(function ($unitVar) use ($sortedPaths) {     return \Seq\collect(function ($matchValue) {     return \Seq\collect(function ($l) use ($matchValue) {     if ($l instanceof Cons) {
        if ($l->next instanceof Cons) {
            if ($l->next->next instanceof Nil) {
                $e = $l->next->value;
                $s = $l->value;
                return \Seq\toList(\Seq\delay(function ($unitVar_1) use ($e, $matchValue, $s) {                 return \Seq\map(function ($r) use ($matchValue) {                 return new Parcel(new Axe($matchValue[0], $r));
 }, \Range\rangeDouble($s->get_R(), 1, $e->get_R() - 1));
 }));
            } else {
                return \Seq\_empty();
            }
        } else {
            return \Seq\_empty();
        }
    } else {
        return \Seq\_empty();
    }
 }, \FSharpList\chunkBySize(2, $matchValue[1]));
 }, $sortedPaths);
 })), [ 'Compare' => function ($x_2, $y_2) {     return \Util\compare($x_2, $y_2);
 }]));
}

#124
function FieldModule_border($neighbors, $_arg1) {
    $parcels = $_arg1->parcels;
    return new Field(\Set\FSharpSet_op_Subtraction(\Set\ofSeq(\Seq\collect($neighbors, $parcels), [ 'Compare' => function ($x, $y) {     return \Util\compare($x, $y);
 }]), $parcels));
}

#125
function FieldModule_borderTiles($parcels) {
    return FieldModule_border(function ($p) {     return ParcelModule_neighbors($p);
 }, $parcels);
}

#126
function FieldModule_unrestrictedborderTiles($parcels) {
    return FieldModule_border(function ($arg00_0040) {     return ParcelModule_unrestrictedNeighbors($arg00_0040);
 }, $parcels);
}

#127
function FieldModule_counterclock($field, $_arg1) {
    $tile = $_arg1->tile;
    switch ($_arg1->side->get_Tag())
    {
        case 0:
            if (FieldModule_contains($tile, $field)) {
                if (FieldModule_contains(Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_SW']), $field)) {
                    return [ new Direction_Horizontal(), new OrientedPath_DW()];
                } else {
                    return [ new Direction_Down(), new OrientedPath_DSE()];
                }
            } else {
                if (FieldModule_contains(Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_NW']), $field)) {
                    return [ new Direction_Up(), new OrientedPath_DNE()];
                } else {
                    if (FieldModule_contains(Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_NW']), $field)) {
                        return [ new Direction_Up(), new OrientedPath_DNE()];
                    } else {
                        return [ new Direction_Horizontal(), new OrientedPath_DW()];
                    }
                }
            }
        default:
            if (FieldModule_contains($tile, $field)) {
                if (FieldModule_contains(Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_NE']), $field)) {
                    return [ new Direction_Horizontal(), new OrientedPath_DE()];
                } else {
                    return [ new Direction_Up(), new OrientedPath_DNW()];
                }
            } else {
                if (FieldModule_contains(Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_NE']), $field)) {
                    if (FieldModule_contains(Axe_op_Addition_2BE35040($tile, $GLOBALS['AxeModule_SE']), $field)) {
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

#128
function FieldModule_borderBetween($start, $end_0027, $field) {
    $loop = function ($orientedPath, $pos, $path) use ($end_0027, $field, $start, &$loop) {     if (\Util\equals($pos, $end_0027)) {
        return \FSharpList\reverse($path);
    } else {
        if (\Util\equals($pos, $start)) {
            return $GLOBALS['NIL'];
        } else {
            switch ($orientedPath->get_Tag())
            {
                case 0:
                    if (FieldModule_contains(Axe_op_Addition_2BE35040(CrossroadModule_tile($pos), $GLOBALS['AxeModule_NE']), $field)) {
                        return $loop(new OrientedPath_DE(), CrossroadModule_neighbor(new Direction_Horizontal(), $pos), new Cons([ PathModule_neighbor(new Direction_Horizontal(), $pos), new Direction_Horizontal()], $path));
                    } else {
                        return $loop(new OrientedPath_DNW(), CrossroadModule_neighbor(new Direction_Up(), $pos), new Cons([ PathModule_neighbor(new Direction_Up(), $pos), new Direction_Up()], $path));
                    }
                case 1:
                    if (FieldModule_contains(Axe_op_Addition_2BE35040(CrossroadModule_tile($pos), $GLOBALS['AxeModule_NW']), $field)) {
                        return $loop(new OrientedPath_DNE(), CrossroadModule_neighbor(new Direction_Up(), $pos), new Cons([ PathModule_neighbor(new Direction_Up(), $pos), new Direction_Up()], $path));
                    } else {
                        return $loop(new OrientedPath_DW(), CrossroadModule_neighbor(new Direction_Horizontal(), $pos), new Cons([ PathModule_neighbor(new Direction_Horizontal(), $pos), new Direction_Horizontal()], $path));
                    }
                case 2:
                    if (FieldModule_contains(CrossroadModule_tile($pos), $field)) {
                        return $loop(new OrientedPath_DNW(), CrossroadModule_neighbor(new Direction_Up(), $pos), new Cons([ PathModule_neighbor(new Direction_Up(), $pos), new Direction_Up()], $path));
                    } else {
                        return $loop(new OrientedPath_DSW(), CrossroadModule_neighbor(new Direction_Down(), $pos), new Cons([ PathModule_neighbor(new Direction_Down(), $pos), new Direction_Down()], $path));
                    }
                case 3:
                    if (FieldModule_contains(Axe_op_Addition_2BE35040(CrossroadModule_tile($pos), $GLOBALS['AxeModule_SW']), $field)) {
                        return $loop(new OrientedPath_DW(), CrossroadModule_neighbor(new Direction_Horizontal(), $pos), new Cons([ PathModule_neighbor(new Direction_Horizontal(), $pos), new Direction_Horizontal()], $path));
                    } else {
                        return $loop(new OrientedPath_DSE(), CrossroadModule_neighbor(new Direction_Down(), $pos), new Cons([ PathModule_neighbor(new Direction_Down(), $pos), new Direction_Down()], $path));
                    }
                case 4:
                    if (FieldModule_contains(Axe_op_Addition_2BE35040(CrossroadModule_tile($pos), $GLOBALS['AxeModule_SE']), $field)) {
                        return $loop(new OrientedPath_DSW(), CrossroadModule_neighbor(new Direction_Down(), $pos), new Cons([ PathModule_neighbor(new Direction_Down(), $pos), new Direction_Down()], $path));
                    } else {
                        return $loop(new OrientedPath_DE(), CrossroadModule_neighbor(new Direction_Horizontal(), $pos), new Cons([ PathModule_neighbor(new Direction_Horizontal(), $pos), new Direction_Horizontal()], $path));
                    }
                default:
                    if (FieldModule_contains(CrossroadModule_tile($pos), $field)) {
                        return $loop(new OrientedPath_DSE(), CrossroadModule_neighbor(new Direction_Down(), $pos), new Cons([ PathModule_neighbor(new Direction_Down(), $pos), new Direction_Down()], $path));
                    } else {
                        return $loop(new OrientedPath_DNE(), CrossroadModule_neighbor(new Direction_Up(), $pos), new Cons([ PathModule_neighbor(new Direction_Up(), $pos), new Direction_Up()], $path));
                    }
            }
        }
    }
 };
    $patternInput = FieldModule_counterclock($field, $start);
    $firstDir = $patternInput[0];
    return $loop($patternInput[1], CrossroadModule_neighbor($firstDir, $start), new Cons([ PathModule_neighbor($firstDir, $start), $firstDir], $GLOBALS['NIL']));
}

#129
function FieldModule_isInSameField($start, $end_0027, $field) {
    return !FieldModule_borderBetween($start, $end_0027, $field) instanceof Nil;
}

#130
function FieldModule_pathInFieldOrBorder($path, $field) {
    if (FieldModule_contains(PathModule_tile($path), $field)) {
        return true;
    } else {
        return FieldModule_contains(PathModule_neighborTiles($path), $field);
    }
}

#131
function FieldModule_findBorder($field, $crossroad) {
    if (\FSharpList\sumBy(function ($p) use ($field) {     if (FieldModule_containsParcel($p, $field)) {
        return 1;
    } else {
        return 0;
    }
 }, CrossroadModule_neighborTiles($crossroad), [ 'GetZero' => function () {     return 0;
 }, 'Add' => function ($x, $y) {     return $x + $y;
 }]) < 3) {
        return $crossroad;
    } else {
        return FieldModule_findBorder($field, CrossroadModule_neighbor(new Direction_Up(), $crossroad));
    }
}

#132
function FieldModule_principalField($field, $fence, $crossroad) {
    $start = FenceModule_start($crossroad, $fence);
    if (CrossroadModule_isInField($field, $start)) {
        $onBorder = FieldModule_findBorder($field, $start);
        return FieldModule_fill(FieldModule_borderBetween($onBorder, $onBorder, $field));
    } else {
        return $GLOBALS['FieldModule_empty'];
    }
}

#133
$GLOBALS['BarnsModule_empty'] = new Barns($GLOBALS['FieldModule_empty'], $GLOBALS['FieldModule_empty']);

#134
function BarnsModule_intersectWith($field, $barns) {
    return new Barns(FieldModule_intersect($field, $barns->Free), FieldModule_intersect($field, $barns->Occupied));
}

#135
function BarnsModule_init($barns) {
    return new Barns(FieldModule_ofParcels($barns), $GLOBALS['FieldModule_empty']);
}

#136
function BarnsModule_create($axes) {
    return \FSharpList\map(function ($arg0) {     return new Parcel($arg0);
 }, $axes);
}

#137
function BarnsModule_annex($annexed, $barns) {
    return new Barns(Field_op_Subtraction_Z24735800($barns->Free, $annexed->Free), Field_op_Addition_Z24735800($barns->Occupied, FieldModule_intersect($barns->Free, $annexed->Free)));
}

#138
function HayBales_findCutPaths($hayBales) {
    $neighbor_1 = function ($dir, $crossroad) use ($hayBales) { 
        $neighbor = CrossroadModule_neighbor($dir, $crossroad);
        if (CrossroadModule_isOnBoard($neighbor)) {
            $path = PathModule_neighbor($dir, $crossroad);
            if (\Set\contains($path, $hayBales)) {
                return NULL;
            } else {
                return [ $path, $neighbor];
            }
        } else {
            return NULL;
        }
    };
    $cut = $GLOBALS['NIL'];
    $visited = \Map\_empty();
    $time = 0;
    $loop = function ($parent, $crossroad_1) use (&$cut, &$loop, &$neighbor_1, &$time, &$visited) { 
        $visited = \Map\add($crossroad_1, $time, $visited);
        $d0 = $time;
        $time = $time + 1;
        $matchValue = $neighbor_1(new Direction_Up(), $crossroad_1);
        if (!is_null($matchValue)) {
            if ((function ($p) use ($matchValue, $parent) {             return !\Util\equals($matchValue[1], $parent);
 })($matchValue[0])) {
                $nxt_1 = $matchValue[1];
                $p_1 = $matchValue[0];
                $matchValue_1 = \Map\tryFind($nxt_1, $visited);
                if (is_null($matchValue_1)) {
                    $n = $loop($crossroad_1, $nxt_1);
                } else {
                    $n = $matchValue_1;
                }
                if ($n > $d0) {
                    $cut = new Cons($p_1, $cut);
                } else {
                }
                $upDepth = $n;
            } else {
                $upDepth = $d0 + 1;
            }
        } else {
            $upDepth = $d0 + 1;
        }
        $matchValue_2 = $neighbor_1(new Direction_Down(), $crossroad_1);
        if (!is_null($matchValue_2)) {
            if ((function ($p_2) use ($matchValue_2, $parent) {             return !\Util\equals($matchValue_2[1], $parent);
 })($matchValue_2[0])) {
                $nxt_3 = $matchValue_2[1];
                $p_3 = $matchValue_2[0];
                $matchValue_3 = \Map\tryFind($nxt_3, $visited);
                if (is_null($matchValue_3)) {
                    $n_1 = $loop($crossroad_1, $nxt_3);
                } else {
                    $n_1 = $matchValue_3;
                }
                if ($n_1 > $d0) {
                    $cut = new Cons($p_3, $cut);
                } else {
                }
                $downDepth = $n_1;
            } else {
                $downDepth = $d0 + 1;
            }
        } else {
            $downDepth = $d0 + 1;
        }
        $d_3 = \Util\min(function ($x_1, $y_1) {         return \Util\comparePrimitives($x_1, $y_1);
 }, (function ($matchValue_4) use ($crossroad_1, $d0, $parent, &$cut, &$loop, &$visited) {         if (!is_null($matchValue_4)) {
            if ((function ($p_4) use ($matchValue_4, $parent) {             return !\Util\equals($matchValue_4[1], $parent);
 })($matchValue_4[0])) {
                $nxt_5 = $matchValue_4[1];
                $p_5 = $matchValue_4[0];
                $matchValue_5 = \Map\tryFind($nxt_5, $visited);
                if (is_null($matchValue_5)) {
                    $n_2 = $loop($crossroad_1, $nxt_5);
                } else {
                    $n_2 = $matchValue_5;
                }
                if ($n_2 > $d0) {
                    $cut = new Cons($p_5, $cut);
                } else {
                }
                return $n_2;
            } else {
                return $d0 + 1;
            }
        } else {
            return $d0 + 1;
        }
 })($neighbor_1(new Direction_Horizontal(), $crossroad_1)), \Util\min(function ($x, $y) {         return \Util\comparePrimitives($x, $y);
 }, $upDepth, $downDepth));
        $visited = \Map\add($crossroad_1, $d_3, $visited);
        return $d_3;
    };
    $start = new Crossroad($GLOBALS['AxeModule_center'], new CrossroadSide_CLeft());
    void($loop($start, $start));
    return \Set\ofSeq($cut, [ 'Compare' => function ($x_2, $y_2) {     return \Util\compare($x_2, $y_2);
 }]);
}

#139
function HayBales_hayBaleDestinations($players, $hayBales) {
    return \Set\FSharpSet_op_Subtraction(\Set\FSharpSet_op_Subtraction(\Set\FSharpSet_op_Subtraction($GLOBALS['PathModule_allInnerPaths'], \Set\unionMany(\Seq\toList(\Seq\delay(function ($unitVar) use ($players) {     return \Seq\collect(function ($matchValue) { 
        $p = $matchValue[1];
        if ($p->get_Tag() == 1) {
            return \Seq\singleton(\Set\ofSeq(FenceModule_fencePaths($p->Item->Fence), [ 'Compare' => function ($x, $y) {             return \Util\compare($x, $y);
 }]));
        } else {
            return \Seq\singleton(\Set\_empty([ 'Compare' => function ($x_1, $y_1) {             return \Util\compare($x_1, $y_1);
 }]));
        }
    }, $players);
 })), [ 'Compare' => function ($x_2, $y_2) {     return \Util\compare($x_2, $y_2);
 }])), $hayBales), HayBales_findCutPaths($hayBales));
}

#140
abstract class Blocker implements FSharpUnion {
}

#140
class Blocker_BorderBlocker extends Blocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'BorderBlocker';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__182 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__182;
    }
}

#140
class Blocker_FenceBlocker extends Blocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'FenceBlocker';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__183 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__183;
    }
}

#140
class Blocker_CutPathBlocker extends Blocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'CutPathBlocker';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__184 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__184;
    }
}

#141
function HayBales_hayBaleDestinationsWithComment($players, $hayBales) {
    $players_1 = \Set\unionMany(\Seq\toList(\Seq\delay(function ($unitVar) use ($players) {     return \Seq\collect(function ($matchValue) { 
        $p = $matchValue[1];
        if ($p->get_Tag() == 1) {
            return \Seq\singleton(\Set\ofSeq(FenceModule_fencePaths($p->Item->Fence), [ 'Compare' => function ($x, $y) {             return \Util\compare($x, $y);
 }]));
        } else {
            return \Seq\singleton(\Set\_empty([ 'Compare' => function ($x_1, $y_1) {             return \Util\compare($x_1, $y_1);
 }]));
        }
    }, $players);
 })), [ 'Compare' => function ($x_2, $y_2) {     return \Util\compare($x_2, $y_2);
 }]);
    $cutPaths = HayBales_findCutPaths($hayBales);
    return \Seq\toList(\Seq\delay(function ($unitVar_1) use ($cutPaths, $hayBales, $players_1) {     return \Seq\append(\Seq\map(function ($p_2) {     return [ $p_2, new \Result_Ok(NULL)];
 }, \Set\FSharpSet_op_Subtraction(\Set\FSharpSet_op_Subtraction(\Set\FSharpSet_op_Subtraction($GLOBALS['PathModule_allInnerPaths'], $players_1), $hayBales), $cutPaths)), \Seq\delay(function ($unitVar_2) use ($cutPaths, $players_1) {     return \Seq\append(\Seq\map(function ($p_3) {     return [ $p_3, new \Result_Error(new Blocker_FenceBlocker())];
 }, $players_1), \Seq\delay(function ($unitVar_3) use ($cutPaths) {     return \Seq\append(\Seq\map(function ($p_4) {     return [ $p_4, new \Result_Error(new Blocker_CutPathBlocker())];
 }, $cutPaths), \Seq\delay(function ($unitVar_4) {     return \Seq\map(function ($p_5) {     return [ $p_5, new \Result_Error(new Blocker_BorderBlocker())];
 }, $GLOBALS['PathModule_boderPaths']);
 }));
 }));
 }));
 }));
}

#142
function HayBales_maxReached($hayBales) {
    return \Set\count($hayBales) >= 8;
}

#143
abstract class MoveBlocker implements FSharpUnion {
}

#143
class MoveBlocker_Tractor extends MoveBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Tractor';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__185 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__185;
    }
}

#143
class MoveBlocker_Protection extends MoveBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Protection';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__186 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__186;
    }
}

#143
class MoveBlocker_PhytosanitaryProducts extends MoveBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PhytosanitaryProducts';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__187 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__187;
    }
}

#143
class MoveBlocker_HayBaleOnPath extends MoveBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'HayBaleOnPath';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__188 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__188;
    }
}

#143
class MoveBlocker_HighVoltageProtection extends MoveBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'HighVoltageProtection';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__189 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__189;
    }
}

#144
abstract class Move implements FSharpUnion {
}

#144
class Move_Move extends Move implements IComparable {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
    function get_FSharpCase() {
        return 'Move';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__190 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__190 != 0) {
            return $_cmp__190;
        }        
        $_cmp__191 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__191 != 0) {
            return $_cmp__191;
        }        
        $_cmp__192 = $this->Item2->CompareTo($other->Item2);
        if ($_cmp__192 != 0) {
            return $_cmp__192;
        }        
        return 0;
    }
}

#144
class Move_ImpossibleMove extends Move implements IComparable {
    public $Item1;
    public $Item2;
    public $Item3;
    function __construct($Item1, $Item2, $Item3) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
        $this->Item3 = $Item3;
    }
    function get_FSharpCase() {
        return 'ImpossibleMove';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__193 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__193 != 0) {
            return $_cmp__193;
        }        
        $_cmp__194 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__194 != 0) {
            return $_cmp__194;
        }        
        $_cmp__195 = $this->Item2->CompareTo($other->Item2);
        if ($_cmp__195 != 0) {
            return $_cmp__195;
        }        
        $_cmp__196 = $this->Item3->CompareTo($other->Item3);
        if ($_cmp__196 != 0) {
            return $_cmp__196;
        }        
        return 0;
    }
}

#144
class Move_SelectCrossroad extends Move implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'SelectCrossroad';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__197 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__197 != 0) {
            return $_cmp__197;
        }        
        $_cmp__198 = $this->Item->CompareTo($other->Item);
        if ($_cmp__198 != 0) {
            return $_cmp__198;
        }        
        return 0;
    }
}

#145
$GLOBALS['MovesModule_empty'] = new Moves(0, 0, false);

#146
function MovesModule_startTurn($fence, $bonus) {
    $acceleration = FenceModule_givesAcceleration($fence);
    return new Moves((($acceleration ? 4 : 3) + BonusModule_moveCapacityChange($bonus)), 0, $acceleration);
}

#147
function MovesModule_canMove($m) {
    return $m->Done < $m->Capacity;
}

#148
function MovesModule_addCapacity($n, $m) {
    return new Moves(\Util\min(function ($x, $y) {     return \Util\comparePrimitives($x, $y);
 }, ($m->Capacity + $n), 5), $m->Done, $m->Acceleration);
}

#149
function MovesModule_doMove($m) {
    return new Moves($m->Capacity, ($m->Done + 1), $m->Acceleration);
}

#150
$GLOBALS['DrawPile_cards'] = \FSharpList\collect(function ($tupledArg) { return \Seq\toList(\Seq\delay(function ($unitVar) use ($tupledArg) { return \Seq\collect(function ($matchValue) use ($tupledArg) { return \Seq\singleton($tupledArg[0]);
 }, \Range\rangeDouble(1, 1, $tupledArg[1]));
 }));
 }, new Cons([ new Card_Nitro(new CardPower_One()), 6], new Cons([ new Card_Nitro(new CardPower_Two()), 3], new Cons([ new Card_Rut(), 2], new Cons([ new Card_HayBale(new CardPower_One()), 4], new Cons([ new Card_HayBale(new CardPower_Two()), 3], new Cons([ new Card_Dynamite(), 4], new Cons([ new Card_HighVoltage(), 3], new Cons([ new Card_Watchdog(), 2], new Cons([ new Card_Helicopter(), 6], new Cons([ new Card_Bribe(), 3], $GLOBALS['NIL'])))))))))));

#151
function DrawPile_shuffle($useGameOver, $cards) {
    $rand = [ ];
    $cardsWithoutGameOver = \FSharpList\filter(function ($_arg1) {     if ($_arg1->get_Tag() == 8) {
        return false;
    } else {
        return true;
    }
 }, $cards);
    $remainingCards = \FSharpList\length($cardsWithoutGameOver);
    if ($remainingCards <= 8 ? true : !$useGameOver) {
        return \FSharpList\sortBy(function ($_arg1_1) {         return \Util\randomNext(0, 2147483647);
 }, $cards, [ 'Compare' => function ($x, $y) {         return \Util\comparePrimitives($x, $y);
 }]);
    } else {
        $list_3 = \FSharpList\sortBy(function ($_arg2) {         return \Util\randomNext(0, 2147483647);
 }, $cardsWithoutGameOver, [ 'Compare' => function ($x_1, $y_1) {         return \Util\comparePrimitives($x_1, $y_1);
 }]);
        $patternInput = \FSharpList\splitAt($remainingCards - \Util\randomNext(1, 8) - 1, $list_3);
        return \FSharpList\append($patternInput[0], \FSharpList\append(new Cons(new Card_GameOver(), $GLOBALS['NIL']), $patternInput[1]));
    }
}

#152
function DrawPile_remove($cards, $pile) {
    $count = HandModule_count($cards);
    return \FSharpList\skip(\Util\min(function ($x, $y) {     return \Util\comparePrimitives($x, $y);
 }, \FSharpList\length($pile), $count), $pile);
}

#153
function DrawPile_take($count, $pile) {
    return \FSharpList\truncate($count, $pile);
}

#154
abstract class Command implements FSharpUnion {
}

#154
class Command_Start extends Command implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Start';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__199 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__199 != 0) {
            return $_cmp__199;
        }        
        $_cmp__200 = $this->Item->CompareTo($other->Item);
        if ($_cmp__200 != 0) {
            return $_cmp__200;
        }        
        return 0;
    }
}

#154
class Command_SelectFirstCrossroad extends Command implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'SelectFirstCrossroad';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__201 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__201 != 0) {
            return $_cmp__201;
        }        
        $_cmp__202 = $this->Item->CompareTo($other->Item);
        if ($_cmp__202 != 0) {
            return $_cmp__202;
        }        
        return 0;
    }
}

#154
class Command_Move extends Command implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Move';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__203 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__203 != 0) {
            return $_cmp__203;
        }        
        $_cmp__204 = $this->Item->CompareTo($other->Item);
        if ($_cmp__204 != 0) {
            return $_cmp__204;
        }        
        return 0;
    }
}

#154
class Command_PlayCard extends Command implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'PlayCard';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__205 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__205 != 0) {
            return $_cmp__205;
        }        
        $_cmp__206 = $this->Item->CompareTo($other->Item);
        if ($_cmp__206 != 0) {
            return $_cmp__206;
        }        
        return 0;
    }
}

#154
class Command_Discard extends Command implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Discard';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__207 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__207 != 0) {
            return $_cmp__207;
        }        
        $_cmp__208 = $this->Item->CompareTo($other->Item);
        if ($_cmp__208 != 0) {
            return $_cmp__208;
        }        
        return 0;
    }
}

#154
class Command_EndTurn extends Command implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'EndTurn';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__209 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__209;
    }
}

#154
class Command_Undo extends Command implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Undo';
    }
    function get_Tag() {
        return 6;
    }
    function CompareTo($other) {
        $_cmp__210 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__210;
    }
}

#154
class Command_Quit extends Command implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Quit';
    }
    function get_Tag() {
        return 7;
    }
    function CompareTo($other) {
        $_cmp__211 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__211;
    }
}

#155
class Start implements IComparable {
    public $Parcel;
    function __construct($Parcel) {
        $this->Parcel = $Parcel;
    }
    function CompareTo($other) {
        $_cmp__212 = $this->Parcel->CompareTo($other->Parcel);
        if ($_cmp__212 != 0) {
            return $_cmp__212;
        }        
        return 0;
    }
}

#156
class SelectFirstCrossroad implements IComparable {
    public $Crossroad;
    function __construct($Crossroad) {
        $this->Crossroad = $Crossroad;
    }
    function CompareTo($other) {
        $_cmp__213 = $this->Crossroad->CompareTo($other->Crossroad);
        if ($_cmp__213 != 0) {
            return $_cmp__213;
        }        
        return 0;
    }
}

#157
class PlayerMove implements IComparable {
    public $Direction;
    public $Destination;
    function __construct($Direction, $Destination) {
        $this->Direction = $Direction;
        $this->Destination = $Destination;
    }
    function CompareTo($other) {
        $_cmp__214 = $this->Direction->CompareTo($other->Direction);
        if ($_cmp__214 != 0) {
            return $_cmp__214;
        }        
        $_cmp__215 = $this->Destination->CompareTo($other->Destination);
        if ($_cmp__215 != 0) {
            return $_cmp__215;
        }        
        return 0;
    }
}

#158
abstract class Event implements FSharpUnion {
}

#158
class Event_FirstCrossroadSelected extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'FirstCrossroadSelected';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__216 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__216 != 0) {
            return $_cmp__216;
        }        
        $_cmp__217 = $this->Item->CompareTo($other->Item);
        if ($_cmp__217 != 0) {
            return $_cmp__217;
        }        
        return 0;
    }
}

#158
class Event_FenceDrawn extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'FenceDrawn';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__218 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__218 != 0) {
            return $_cmp__218;
        }        
        $_cmp__219 = $this->Item->CompareTo($other->Item);
        if ($_cmp__219 != 0) {
            return $_cmp__219;
        }        
        return 0;
    }
}

#158
class Event_FenceRemoved extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'FenceRemoved';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__220 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__220 != 0) {
            return $_cmp__220;
        }        
        $_cmp__221 = $this->Item->CompareTo($other->Item);
        if ($_cmp__221 != 0) {
            return $_cmp__221;
        }        
        return 0;
    }
}

#158
class Event_FenceLooped extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'FenceLooped';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__222 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__222 != 0) {
            return $_cmp__222;
        }        
        $_cmp__223 = $this->Item->CompareTo($other->Item);
        if ($_cmp__223 != 0) {
            return $_cmp__223;
        }        
        return 0;
    }
}

#158
class Event_MovedInField extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'MovedInField';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__224 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__224 != 0) {
            return $_cmp__224;
        }        
        $_cmp__225 = $this->Item->CompareTo($other->Item);
        if ($_cmp__225 != 0) {
            return $_cmp__225;
        }        
        return 0;
    }
}

#158
class Event_MovedPowerless extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'MovedPowerless';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__226 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__226 != 0) {
            return $_cmp__226;
        }        
        $_cmp__227 = $this->Item->CompareTo($other->Item);
        if ($_cmp__227 != 0) {
            return $_cmp__227;
        }        
        return 0;
    }
}

#158
class Event_FenceReduced extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'FenceReduced';
    }
    function get_Tag() {
        return 6;
    }
    function CompareTo($other) {
        $_cmp__228 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__228 != 0) {
            return $_cmp__228;
        }        
        $_cmp__229 = $this->Item->CompareTo($other->Item);
        if ($_cmp__229 != 0) {
            return $_cmp__229;
        }        
        return 0;
    }
}

#158
class Event_Annexed extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Annexed';
    }
    function get_Tag() {
        return 7;
    }
    function CompareTo($other) {
        $_cmp__230 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__230 != 0) {
            return $_cmp__230;
        }        
        $_cmp__231 = $this->Item->CompareTo($other->Item);
        if ($_cmp__231 != 0) {
            return $_cmp__231;
        }        
        return 0;
    }
}

#158
class Event_CutFence extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'CutFence';
    }
    function get_Tag() {
        return 8;
    }
    function CompareTo($other) {
        $_cmp__232 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__232 != 0) {
            return $_cmp__232;
        }        
        $_cmp__233 = $this->Item->CompareTo($other->Item);
        if ($_cmp__233 != 0) {
            return $_cmp__233;
        }        
        return 0;
    }
}

#158
class Event_PoweredUp extends Event implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PoweredUp';
    }
    function get_Tag() {
        return 9;
    }
    function CompareTo($other) {
        $_cmp__234 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__234;
    }
}

#158
class Event_CardPlayed extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'CardPlayed';
    }
    function get_Tag() {
        return 10;
    }
    function CompareTo($other) {
        $_cmp__235 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__235 != 0) {
            return $_cmp__235;
        }        
        $_cmp__236 = $this->Item->CompareTo($other->Item);
        if ($_cmp__236 != 0) {
            return $_cmp__236;
        }        
        return 0;
    }
}

#158
class Event_SpedUp extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'SpedUp';
    }
    function get_Tag() {
        return 11;
    }
    function CompareTo($other) {
        $_cmp__237 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__237 != 0) {
            return $_cmp__237;
        }        
        $_cmp__238 = $this->Item->CompareTo($other->Item);
        if ($_cmp__238 != 0) {
            return $_cmp__238;
        }        
        return 0;
    }
}

#158
class Event_Rutted extends Event implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Rutted';
    }
    function get_Tag() {
        return 12;
    }
    function CompareTo($other) {
        $_cmp__239 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__239;
    }
}

#158
class Event_HighVoltaged extends Event implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'HighVoltaged';
    }
    function get_Tag() {
        return 13;
    }
    function CompareTo($other) {
        $_cmp__240 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__240;
    }
}

#158
class Event_BonusDiscarded extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'BonusDiscarded';
    }
    function get_Tag() {
        return 14;
    }
    function CompareTo($other) {
        $_cmp__241 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__241 != 0) {
            return $_cmp__241;
        }        
        $_cmp__242 = $this->Item->CompareTo($other->Item);
        if ($_cmp__242 != 0) {
            return $_cmp__242;
        }        
        return 0;
    }
}

#158
class Event_CardDiscarded extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'CardDiscarded';
    }
    function get_Tag() {
        return 15;
    }
    function CompareTo($other) {
        $_cmp__243 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__243 != 0) {
            return $_cmp__243;
        }        
        $_cmp__244 = $this->Item->CompareTo($other->Item);
        if ($_cmp__244 != 0) {
            return $_cmp__244;
        }        
        return 0;
    }
}

#158
class Event_Watched extends Event implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Watched';
    }
    function get_Tag() {
        return 16;
    }
    function CompareTo($other) {
        $_cmp__245 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__245;
    }
}

#158
class Event_Heliported extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Heliported';
    }
    function get_Tag() {
        return 17;
    }
    function CompareTo($other) {
        $_cmp__246 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__246 != 0) {
            return $_cmp__246;
        }        
        $_cmp__247 = $this->Item->CompareTo($other->Item);
        if ($_cmp__247 != 0) {
            return $_cmp__247;
        }        
        return 0;
    }
}

#158
class Event_Bribed extends Event implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Bribed';
    }
    function get_Tag() {
        return 18;
    }
    function CompareTo($other) {
        $_cmp__248 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__248 != 0) {
            return $_cmp__248;
        }        
        $_cmp__249 = $this->Item->CompareTo($other->Item);
        if ($_cmp__249 != 0) {
            return $_cmp__249;
        }        
        return 0;
    }
}

#158
class Event_Eliminated extends Event implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Eliminated';
    }
    function get_Tag() {
        return 19;
    }
    function CompareTo($other) {
        $_cmp__250 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__250;
    }
}

#158
class Event_Undone extends Event implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Undone';
    }
    function get_Tag() {
        return 20;
    }
    function CompareTo($other) {
        $_cmp__251 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__251;
    }
}

#158
class Event_PlayerQuit extends Event implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'PlayerQuit';
    }
    function get_Tag() {
        return 21;
    }
    function CompareTo($other) {
        $_cmp__252 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__252;
    }
}

#159
class PlayerStarted implements IComparable {
    public $Parcel;
    function __construct($Parcel) {
        $this->Parcel = $Parcel;
    }
    function CompareTo($other) {
        $_cmp__253 = $this->Parcel->CompareTo($other->Parcel);
        if ($_cmp__253 != 0) {
            return $_cmp__253;
        }        
        return 0;
    }
}

#160
class FirstCrossroadSelected implements IComparable {
    public $Crossroad;
    function __construct($Crossroad) {
        $this->Crossroad = $Crossroad;
    }
    function CompareTo($other) {
        $_cmp__254 = $this->Crossroad->CompareTo($other->Crossroad);
        if ($_cmp__254 != 0) {
            return $_cmp__254;
        }        
        return 0;
    }
}

#161
class Moved implements IComparable {
    public $Move;
    public $Path;
    public $Crossroad;
    function __construct($Move, $Path, $Crossroad) {
        $this->Move = $Move;
        $this->Path = $Path;
        $this->Crossroad = $Crossroad;
    }
    function CompareTo($other) {
        $_cmp__255 = $this->Move->CompareTo($other->Move);
        if ($_cmp__255 != 0) {
            return $_cmp__255;
        }        
        $_cmp__256 = $this->Path->CompareTo($other->Path);
        if ($_cmp__256 != 0) {
            return $_cmp__256;
        }        
        $_cmp__257 = $this->Crossroad->CompareTo($other->Crossroad);
        if ($_cmp__257 != 0) {
            return $_cmp__257;
        }        
        return 0;
    }
}

#162
class FenceLooped implements IComparable {
    public $Move;
    public $Loop;
    public $Crossroad;
    function __construct($Move, $Loop, $Crossroad) {
        $this->Move = $Move;
        $this->Loop = $Loop;
        $this->Crossroad = $Crossroad;
    }
    function CompareTo($other) {
        $_cmp__258 = $this->Move->CompareTo($other->Move);
        if ($_cmp__258 != 0) {
            return $_cmp__258;
        }        
        $_cmp__259 = $this->Loop->CompareTo($other->Loop);
        if ($_cmp__259 != 0) {
            return $_cmp__259;
        }        
        $_cmp__260 = $this->Crossroad->CompareTo($other->Crossroad);
        if ($_cmp__260 != 0) {
            return $_cmp__260;
        }        
        return 0;
    }
}

#163
class Annexed implements IComparable {
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
    function CompareTo($other) {
        $_cmp__261 = $this->NewField->CompareTo($other->NewField);
        if ($_cmp__261 != 0) {
            return $_cmp__261;
        }        
        $_cmp__262 = $this->LostFields->CompareTo($other->LostFields);
        if ($_cmp__262 != 0) {
            return $_cmp__262;
        }        
        $_cmp__263 = $this->FreeBarns->CompareTo($other->FreeBarns);
        if ($_cmp__263 != 0) {
            return $_cmp__263;
        }        
        $_cmp__264 = $this->OccupiedBarns->CompareTo($other->OccupiedBarns);
        if ($_cmp__264 != 0) {
            return $_cmp__264;
        }        
        $_cmp__265 = $this->FenceLength > $other->FenceLength ? 1 : ($this->FenceLength < $other->FenceLength ? -1 : 0);
        if ($_cmp__265 != 0) {
            return $_cmp__265;
        }        
        return 0;
    }
}

#164
class CutFence implements IComparable {
    public $Player;
    function __construct($Player) {
        $this->Player = $Player;
    }
    function CompareTo($other) {
        $_cmp__266 = $this->Player > $other->Player ? 1 : ($this->Player < $other->Player ? -1 : 0);
        if ($_cmp__266 != 0) {
            return $_cmp__266;
        }        
        return 0;
    }
}

#165
class FenceReduced implements IComparable {
    public $NewLength;
    function __construct($NewLength) {
        $this->NewLength = $NewLength;
    }
    function CompareTo($other) {
        $_cmp__267 = $this->NewLength > $other->NewLength ? 1 : ($this->NewLength < $other->NewLength ? -1 : 0);
        if ($_cmp__267 != 0) {
            return $_cmp__267;
        }        
        return 0;
    }
}

#166
class SpedUp implements IComparable {
    public $Speed;
    function __construct($Speed) {
        $this->Speed = $Speed;
    }
    function CompareTo($other) {
        $_cmp__268 = $this->Speed > $other->Speed ? 1 : ($this->Speed < $other->Speed ? -1 : 0);
        if ($_cmp__268 != 0) {
            return $_cmp__268;
        }        
        return 0;
    }
}

#167
class Bribed implements IComparable {
    public $Parcel;
    public $Victim;
    function __construct($Parcel, $Victim) {
        $this->Parcel = $Parcel;
        $this->Victim = $Victim;
    }
    function CompareTo($other) {
        $_cmp__269 = $this->Parcel->CompareTo($other->Parcel);
        if ($_cmp__269 != 0) {
            return $_cmp__269;
        }        
        $_cmp__270 = $this->Victim > $other->Victim ? 1 : ($this->Victim < $other->Victim ? -1 : 0);
        if ($_cmp__270 != 0) {
            return $_cmp__270;
        }        
        return 0;
    }
}

#168
function Player_isCut($tractor, $player) {
    if ($player->get_Tag() == 1) {
        $player_1 = $player->Item;
        if (!$player_1->Bonus->HighVoltage) {
            return \Seq\contains($tractor, FenceModule_fenceCrossroads($player_1->Tractor, $player_1->Fence), [ 'Equals' => function ($x, $y) {             return \Util\equals($x, $y);
 }, 'GetHashCode' => function ($x) {             return \Util\safeHash($x);
 }]);
        } else {
            return false;
        }
    } else {
        return false;
    }
}

#169
function Player_decideCut($otherPlayers, $tractor) {
    return \FSharpList\map(function ($tupledArg) {     return new Event_CutFence(new CutFence($tupledArg[0]));
 }, \FSharpList\filter(function ($arg) use ($tractor) {     return Player_isCut($tractor, $arg[1]);
 }, $otherPlayers));
}

#170
function Player_nearestContact($field, $_arg1, $pos) {
    $loop = function ($pos_1, $fence_1, $len) use ($field, &$loop) {     if (CrossroadModule_isInField($field, $pos_1)) {
        return [ $pos_1, $fence_1, $len];
    } else {
        if ($fence_1 instanceof Cons) {
            return $loop(CrossroadModule_neighbor(DirectionModule_rev($fence_1->value[1]), $pos_1), $fence_1->next, $len + 1);
        } else {
            return NULL;
        }
    }
 };
    return $loop($pos, $_arg1->paths, 0);
}

#171
function Player_fullAnnexation($field, $fence, $tractor) {
    $mainField = FieldModule_principalField($field, $fence, $tractor);
    $start = FenceModule_start($tractor, $fence);
    if (CrossroadModule_isInField($mainField, $start)) {
        $matchValue = Player_nearestContact($mainField, $fence, $tractor);
        if (!is_null($matchValue)) {
            if ((function ($paths) use ($matchValue, $start) { 
                $len = $matchValue[2];
                return !\Util\equals($matchValue[0], $start);
            })($matchValue[1])) {
                $len_1 = $matchValue[2];
                $paths_1 = $matchValue[1];
                $pos_1 = $matchValue[0];
                return [ Field_op_Subtraction_Z24735800(FieldModule_fill(\FSharpList\append($paths_1, FieldModule_borderBetween($start, $pos_1, $mainField))), $field), $len_1];
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    } else {
        return NULL;
    }
}

#172
function Player_startTurn($player) {
    switch ($player->get_Tag())
    {
        case 0:
            return $player;
        case 2:
            return $player;
        default:
            $p = $player->Item;
            return new CrazyPlayer_Playing(new Playing($p->Color, $p->Tractor, $p->Fence, $p->Field, $p->Power, MovesModule_startTurn($p->Fence, $p->Bonus), $p->Hand, $p->Bonus));
    }
}

#173
function Player_color($player) {
    switch ($player->get_Tag())
    {
        case 0:
            return $player->Item->Color;
        case 2:
            return $player->Item;
        default:
            return $player->Item->Color;
    }
}

#174
function Player_hand($player) {
    switch ($player->get_Tag())
    {
        case 0:
            return $player->Item->Hand;
        case 2:
            return $GLOBALS['HandModule_empty'];
        default:
            return $player->Item->Hand;
    }
}

#175
function Player_bonus($player) {
    switch ($player->get_Tag())
    {
        case 0:
            return $player->Item->Bonus;
        case 2:
            return $GLOBALS['BonusModule_empty'];
        default:
            return $player->Item->Bonus;
    }
}

#176
function Player_fence($player) {
    switch ($player->get_Tag())
    {
        case 0:
            $_target__271 = 1;
            break;
        case 2:
            $_target__271 = 1;
            break;
        default:
            $_target__271 = 0;
            break;
    }
    switch ($_target__271)
    {
        case 0:
            return $player->Item->Fence;
        case 1:
            return $GLOBALS['FenceModule_empty'];
    }
}

#177
function Player_field($player) {
    switch ($player->get_Tag())
    {
        case 0:
            return FieldModule_ofParcels(new Cons($player->Item->Parcel, $GLOBALS['NIL']));
        case 2:
            return $GLOBALS['FieldModule_empty'];
        default:
            return $player->Item->Field;
    }
}

#178
function Player_isKo($player) {
    if ($player->get_Tag() == 2) {
        return true;
    } else {
        return false;
    }
}

#179
function Player_moves($player) {
    if ($player->get_Tag() == 1) {
        return $player->Item->Moves;
    } else {
        return $GLOBALS['MovesModule_empty'];
    }
}

#180
function Player_toPrivate($player) {
    switch ($player->get_Tag())
    {
        case 0:
            $p_1 = $player->Item;
            return new CrazyPlayer_Starting(new Starting($p_1->Color, $p_1->Parcel, HandModule_toPrivate($p_1->Hand), $p_1->Bonus));
        case 2:
            return $player;
        default:
            $p = $player->Item;
            return new CrazyPlayer_Playing(new Playing($p->Color, $p->Tractor, $p->Fence, $p->Field, $p->Power, $p->Moves, HandModule_toPrivate($p->Hand), $p->Bonus));
    }
}

#181
function Player_fieldTotalSize($player) {
    switch ($player->get_Tag())
    {
        case 0:
            return 1;
        case 2:
            return 0;
        default:
            return FieldModule_size($player->Item->Field);
    }
}

#182
function Player_principalFieldSize($player) {
    switch ($player->get_Tag())
    {
        case 0:
            return 1;
        case 2:
            return 0;
        default:
            $p = $player->Item;
            return FieldModule_size(FieldModule_principalField($p->Field, $p->Fence, $p->Tractor));
    }
}

#183
function Player_watchedField($player) {
    if ($player->get_Tag() == 1) {
        if ($player->Item->Bonus->Watched) {
            $field = $player->Item->Field;
            return $field;
        } else {
            return $GLOBALS['FieldModule_empty'];
        }
    } else {
        return $GLOBALS['FieldModule_empty'];
    }
}

#184
function Player_canUseHelicopter($player) {
    if ($player->get_Tag() == 1) {
        return FenceModule_isEmpty($player->Item->Fence);
    } else {
        return false;
    }
}

#185
function Player_decide($otherPlayers, $barns, $hayBales, $bribeParcels, $command, $player) {
    $matchValue = [ $player, $command];
    if ($matchValue[0]->get_Tag() == 1) {
        switch ($matchValue[1]->get_Tag())
        {
            case 2:
                $cmd_1 = $matchValue[1]->Item;
                $player_1 = $matchValue[0]->Item;
                $dir = $cmd_1->Direction;
                $nextPos = CrossroadModule_neighbor($dir, $player_1->Tractor);
                $nextPath = PathModule_neighbor($dir, $player_1->Tractor);
                if (!\Util\equals($nextPos, $cmd_1->Destination) ? true : !MovesModule_canMove($player_1->Moves)) {
                    return $GLOBALS['NIL'];
                } else {
                    switch ($player_1->Power->get_Tag())
                    {
                        case 1:
                            return \Seq\toList(\Seq\delay(function ($unitVar_2) use ($dir, $nextPath, $nextPos, $otherPlayers, $player_1) {                             return \Seq\append(\Seq\singleton(new Event_MovedPowerless(new Moved($dir, $nextPath, $nextPos))), \Seq\delay(function ($unitVar_3) use ($nextPos, $otherPlayers, $player_1) {                             if (CrossroadModule_isInField($player_1->Field, $nextPos)) {
                                return \Seq\append(\Seq\singleton(new Event_PoweredUp()), \Seq\delay(function ($unitVar_4) use ($nextPos, $otherPlayers) {                                 return Player_decideCut($otherPlayers, $nextPos);
 }));
                            } else {
                                return \Seq\_empty();
                            }
 }));
 }));
                        default:
                            if (!is_null(FenceOps__007CRwd_007C__007C($nextPath, $player_1->Fence))) {
                                return new Cons(new Event_FenceRemoved(new Moved($dir, $nextPath, $nextPos)), $GLOBALS['NIL']);
                            } else {
                                $matchValue_3 = FenceModule_findLoop($dir, $player_1->Tractor, $player_1->Fence);
                                if ($matchValue_3->paths instanceof Nil) {
                                    $endInField = CrossroadModule_isInField($player_1->Field, $nextPos);
                                    $pathInField = FieldModule_pathInFieldOrBorder($nextPath, $player_1->Field);
                                    if ($endInField) {
                                        $nextFence = FenceModule_add($nextPath, $dir, $player_1->Fence);
                                        if ($pathInField ? FenceModule_length($nextFence) === 1 : false) {
                                            $inFallow = false;
                                        } else {
                                            $inFallow = !FieldModule_isInSameField(FenceModule_start($nextPos, $nextFence), $nextPos, $player_1->Field);
                                        }
                                    } else {
                                        $inFallow = false;
                                    }
                                    return \Seq\toList(\Seq\delay(function ($unitVar) use ($dir, $inFallow, $nextPath, $nextPos, $otherPlayers, $pathInField) {                                     return \Seq\append(($pathInField ? !$inFallow : false) ? \Seq\singleton(new Event_MovedInField(new Moved($dir, $nextPath, $nextPos))) : \Seq\singleton(new Event_FenceDrawn(new Moved($dir, $nextPath, $nextPos))), \Seq\delay(function ($unitVar_1) use ($nextPos, $otherPlayers) {                                     return Player_decideCut($otherPlayers, $nextPos);
 }));
 }));
                                } else {
                                    return new Cons(new Event_FenceLooped(new FenceLooped($dir, $matchValue_3, $nextPos)), $GLOBALS['NIL']);
                                }
                            }
                    }
                }
            case 3:
                $card = $matchValue[1]->Item;
                $p = $matchValue[0]->Item;
                if (HandModule_contains(CardModule_ofPlayCard($card), $p->Hand)) {
                    switch ($card->get_Tag())
                    {
                        case 4:
                            return new Cons(new Event_CardPlayed($card), new Cons(new Event_HighVoltaged(), $GLOBALS['NIL']));
                        case 5:
                            return new Cons(new Event_CardPlayed($card), new Cons(new Event_Watched(), $GLOBALS['NIL']));
                        case 1:
                            return new Cons(new Event_CardPlayed($card), $GLOBALS['NIL']);
                        case 6:
                            $crossroad = $card->destination;
                            $othersCrossroads = \Set\ofSeq(\Seq\toList(\Seq\delay(function ($unitVar_5) use ($otherPlayers) {                             return \Seq\collect(function ($matchValue_4) { 
                                $p_1 = $matchValue_4[1];
                                if ($p_1->get_Tag() == 1) {
                                    $p_2 = $p_1->Item;
                                    return FenceModule_fenceCrossroads($p_2->Tractor, $p_2->Fence);
                                } else {
                                    return \Seq\_empty();
                                }
                            }, $otherPlayers);
 })), [ 'Compare' => function ($x, $y) {                             return \Util\compare($x, $y);
 }]);
                            if ((Player_canUseHelicopter($player) ? CrossroadModule_isInField(Player_field($player), $crossroad) : false) ? !\Set\contains($crossroad, $othersCrossroads) : false) {
                                return \Seq\toList(\Seq\delay(function ($unitVar_6) use ($card, $crossroad, $p) {                                 return \Seq\append(\Seq\singleton(new Event_CardPlayed($card)), \Seq\delay(function ($unitVar_7) use ($crossroad, $p) {                                 return \Seq\append(\Seq\singleton(new Event_Heliported($crossroad)), \Seq\delay(function ($unitVar_8) use ($crossroad, $p) {                                 if (\Util\equals($p->Power, new Power_PowerDown()) ? CrossroadModule_isInField($p->Field, $crossroad) : false) {
                                    return \Seq\singleton(new Event_PoweredUp());
                                } else {
                                    return \Seq\_empty();
                                }
 }));
 }));
 }));
                            } else {
                                return $GLOBALS['NIL'];
                            }
                        case 2:
                            $rm = $card->moved;
                            $hb = $card->path;
                            if (\Util\max(function ($x_1, $y_1) {                             return \Util\comparePrimitives($x_1, $y_1);
 }, \Set\count($hayBales) + \FSharpList\length($hb) - 8, 0) === \FSharpList\length($rm)) {
                                if (\Set\isSubset(\Set\ofSeq($rm, [ 'Compare' => function ($x_2, $y_2) {                                 return \Util\compare($x_2, $y_2);
 }]), $hayBales)) {
                                    $dests = HayBales_hayBaleDestinations(new Cons([ '', $player], $otherPlayers), $hayBales);
                                    if (\FSharpList\forAll(function ($b) use ($dests) {                                     return \Set\contains($b, $dests);
 }, $hb)) {
                                        return new Cons(new Event_CardPlayed($card), new Cons(new Event_BonusDiscarded(CardModule_ofPlayCard($card)), $GLOBALS['NIL']));
                                    } else {
                                        return $GLOBALS['NIL'];
                                    }
                                } else {
                                    return $GLOBALS['NIL'];
                                }
                            } else {
                                return $GLOBALS['NIL'];
                            }
                        case 3:
                            if (\Set\contains($card->path, $hayBales)) {
                                return new Cons(new Event_CardPlayed($card), new Cons(new Event_BonusDiscarded(CardModule_ofPlayCard($card)), $GLOBALS['NIL']));
                            } else {
                                return $GLOBALS['NIL'];
                            }
                        case 7:
                            $parcel = $card->parcel;
                            $matchValue_5 = $bribeParcels(NULL);
                            if ($matchValue_5->get_Tag() == 0) {
                                if (FieldModule_containsParcel($parcel, $matchValue_5->ResultValue)) {
                                    return \Seq\toList(\Seq\delay(function ($unitVar_9) use ($card, $otherPlayers, $parcel) {                                     return \Seq\append(\Seq\singleton(new Event_CardPlayed($card)), \Seq\delay(function ($unitVar_10) use ($card, $otherPlayers, $parcel) {                                     return \Seq\append(\Seq\collect(function ($matchValue_6) use ($parcel) {                                     if (FieldModule_containsParcel($parcel, Player_field($matchValue_6[1]))) {
                                        return \Seq\singleton(new Event_Bribed(new Bribed($parcel, $matchValue_6[0])));
                                    } else {
                                        return \Seq\_empty();
                                    }
 }, $otherPlayers), \Seq\delay(function ($unitVar_11) use ($card) {                                     return \Seq\singleton(new Event_BonusDiscarded(CardModule_ofPlayCard($card)));
 }));
 }));
 }));
                                } else {
                                    return $GLOBALS['NIL'];
                                }
                            } else {
                                return $GLOBALS['NIL'];
                            }
                        case 8:
                            return $GLOBALS['NIL'];
                        default:
                            return new Cons(new Event_CardPlayed($card), new Cons(new Event_SpedUp(new SpedUp(($card->power->get_Tag() == 1 ? 2 : 1))), $GLOBALS['NIL']));
                    }
                } else {
                    return $GLOBALS['NIL'];
                }
            case 4:
                $card_1 = $matchValue[1]->Item;
                $p_3 = $matchValue[0]->Item;
                if (HandModule_contains($card_1, $p_3->Hand)) {
                    return new Cons(new Event_CardDiscarded($card_1), $GLOBALS['NIL']);
                } else {
                    return $GLOBALS['NIL'];
                }
            case 7:
                return new Cons(new Event_PlayerQuit(), $GLOBALS['NIL']);
            default:
                throw new Error('Invalid operation');
        }
    } else {
        if ($matchValue[0]->get_Tag() == 2) {
            switch ($matchValue[1]->get_Tag())
            {
                case 7:
                    return $GLOBALS['NIL'];
                default:
                    throw new Error('Invalid operation');
            }
        } else {
            switch ($matchValue[1]->get_Tag())
            {
                case 1:
                    $cmd = $matchValue[1]->Item;
                    return new Cons(new Event_FirstCrossroadSelected(new FirstCrossroadSelected($cmd->Crossroad)), $GLOBALS['NIL']);
                case 7:
                    return new Cons(new Event_PlayerQuit(), $GLOBALS['NIL']);
                default:
                    throw new Error('Invalid operation');
            }
        }
    }
}

#186
function Player_evolve($player, $event) {
    $matchValue = [ $player, $event];
    if ($matchValue[0]->get_Tag() == 0) {
        switch ($matchValue[1]->get_Tag())
        {
            case 0:
                $e = $matchValue[1]->Item;
                $p = $matchValue[0]->Item;
                return new CrazyPlayer_Playing(new Playing($p->Color, $e->Crossroad, $GLOBALS['FenceModule_empty'], FieldModule_create($p->Parcel), new Power_PowerUp(), MovesModule_startTurn($GLOBALS['FenceModule_empty'], $p->Bonus), $p->Hand, $p->Bonus));
            case 21:
                $player_19 = $matchValue[0]->Item;
                return new CrazyPlayer_Ko($player_19->Color);
            default:
                return $player;
        }
    } else {
        if ($matchValue[0]->get_Tag() == 1) {
            switch ($matchValue[1]->get_Tag())
            {
                case 1:
                    $e_1 = $matchValue[1]->Item;
                    $player_1 = $matchValue[0]->Item;
                    $_target__272 = 1;
                    break;
                case 2:
                    $e_2 = $matchValue[1]->Item;
                    $player_2 = $matchValue[0]->Item;
                    $_target__272 = 2;
                    break;
                case 3:
                    $e_3 = $matchValue[1]->Item;
                    $player_3 = $matchValue[0]->Item;
                    $_target__272 = 3;
                    break;
                case 4:
                    $e_4 = $matchValue[1]->Item;
                    $player_4 = $matchValue[0]->Item;
                    $_target__272 = 4;
                    break;
                case 5:
                    $e_5 = $matchValue[1]->Item;
                    $player_5 = $matchValue[0]->Item;
                    $_target__272 = 5;
                    break;
                case 6:
                    $e_6 = $matchValue[1]->Item;
                    $player_6 = $matchValue[0]->Item;
                    $_target__272 = 6;
                    break;
                case 9:
                    $player_7 = $matchValue[0]->Item;
                    $_target__272 = 7;
                    break;
                case 7:
                    $e_7 = $matchValue[1]->Item;
                    $player_8 = $matchValue[0]->Item;
                    $_target__272 = 8;
                    break;
                case 13:
                    $player_9 = $matchValue[0]->Item;
                    $_target__272 = 9;
                    break;
                case 16:
                    $player_10 = $matchValue[0]->Item;
                    $_target__272 = 10;
                    break;
                case 12:
                    $player_11 = $matchValue[0]->Item;
                    $_target__272 = 11;
                    break;
                case 11:
                    $e_8 = $matchValue[1]->Item;
                    $player_12 = $matchValue[0]->Item;
                    $_target__272 = 12;
                    break;
                case 17:
                    $e_9 = $matchValue[1]->Item;
                    $player_13 = $matchValue[0]->Item;
                    $_target__272 = 13;
                    break;
                case 18:
                    $p_1 = $matchValue[1]->Item;
                    $player_14 = $matchValue[0]->Item;
                    $_target__272 = 14;
                    break;
                case 10:
                    $card = $matchValue[1]->Item;
                    $player_15 = $matchValue[0]->Item;
                    $_target__272 = 15;
                    break;
                case 14:
                    $e_10 = $matchValue[1]->Item;
                    $player_16 = $matchValue[0]->Item;
                    $_target__272 = 16;
                    break;
                case 15:
                    $card_2 = $matchValue[1]->Item;
                    $player_17 = $matchValue[0]->Item;
                    $_target__272 = 17;
                    break;
                case 19:
                    $player_18 = $matchValue[0]->Item;
                    $_target__272 = 18;
                    break;
                case 21:
                    $player_18 = $matchValue[0]->Item;
                    $_target__272 = 18;
                    break;
                default:
                    $_target__272 = 20;
                    break;
            }
            switch ($_target__272)
            {
                case 0:
                case 1:
                    return new CrazyPlayer_Playing(new Playing($player_1->Color, $e_1->Crossroad, FenceModule_add($e_1->Path, $e_1->Move, $player_1->Fence), $player_1->Field, $player_1->Power, MovesModule_doMove($player_1->Moves), $player_1->Hand, $player_1->Bonus));
                case 2:
                    return new CrazyPlayer_Playing(new Playing($player_2->Color, $e_2->Crossroad, FenceModule_tail($player_2->Fence), $player_2->Field, $player_2->Power, MovesModule_doMove($player_2->Moves), $player_2->Hand, $player_2->Bonus));
                case 3:
                    return new CrazyPlayer_Playing(new Playing($player_3->Color, $e_3->Crossroad, FenceModule_remove($e_3->Loop, $player_3->Fence), $player_3->Field, $player_3->Power, MovesModule_doMove($player_3->Moves), $player_3->Hand, $player_3->Bonus));
                case 4:
                    return new CrazyPlayer_Playing(new Playing($player_4->Color, $e_4->Crossroad, $GLOBALS['FenceModule_empty'], $player_4->Field, $player_4->Power, MovesModule_doMove($player_4->Moves), $player_4->Hand, $player_4->Bonus));
                case 5:
                    return new CrazyPlayer_Playing(new Playing($player_5->Color, $e_5->Crossroad, $player_5->Fence, $player_5->Field, $player_5->Power, MovesModule_doMove($player_5->Moves), $player_5->Hand, $player_5->Bonus));
                case 6:
                    return new CrazyPlayer_Playing(new Playing($player_6->Color, $player_6->Tractor, FenceModule_truncate($e_6->NewLength, $player_6->Fence), $player_6->Field, $player_6->Power, $player_6->Moves, $player_6->Hand, $player_6->Bonus));
                case 7:
                    return new CrazyPlayer_Playing(new Playing($player_7->Color, $player_7->Tractor, $player_7->Fence, $player_7->Field, new Power_PowerUp(), $player_7->Moves, $player_7->Hand, $player_7->Bonus));
                case 8:
                    return new CrazyPlayer_Playing(new Playing($player_8->Color, $player_8->Tractor, FenceModule_truncate($e_7->FenceLength, $player_8->Fence), Field_op_Addition_Z24735800($player_8->Field, FieldModule_ofParcels($e_7->NewField)), $player_8->Power, $player_8->Moves, $player_8->Hand, $player_8->Bonus));
                case 9:
                    return new CrazyPlayer_Playing(new Playing($player_9->Color, $player_9->Tractor, $player_9->Fence, $player_9->Field, $player_9->Power, $player_9->Moves, $player_9->Hand, (function ($inputRecord) {                     return new Bonus($inputRecord->NitroOne, $inputRecord->NitroTwo, $inputRecord->Watched, true, $inputRecord->Rutted, $inputRecord->Heliported);
 })($player_9->Bonus)));
                case 10:
                    return new CrazyPlayer_Playing(new Playing($player_10->Color, $player_10->Tractor, $player_10->Fence, $player_10->Field, $player_10->Power, $player_10->Moves, $player_10->Hand, (function ($inputRecord_1) {                     return new Bonus($inputRecord_1->NitroOne, $inputRecord_1->NitroTwo, true, $inputRecord_1->HighVoltage, $inputRecord_1->Rutted, $inputRecord_1->Heliported);
 })($player_10->Bonus)));
                case 11:
                    return new CrazyPlayer_Playing(new Playing($player_11->Color, $player_11->Tractor, $player_11->Fence, $player_11->Field, $player_11->Power, $player_11->Moves, $player_11->Hand, (function ($inputRecord_2) use ($player_11) {                     return new Bonus($inputRecord_2->NitroOne, $inputRecord_2->NitroTwo, $inputRecord_2->Watched, $inputRecord_2->HighVoltage, ($player_11->Bonus->Rutted + 1), $inputRecord_2->Heliported);
 })($player_11->Bonus)));
                case 12:
                    return new CrazyPlayer_Playing(new Playing($player_12->Color, $player_12->Tractor, $player_12->Fence, $player_12->Field, $player_12->Power, MovesModule_addCapacity($e_8->Speed, $player_12->Moves), $player_12->Hand, $player_12->Bonus));
                case 13:
                    return new CrazyPlayer_Playing(new Playing($player_13->Color, $e_9, $GLOBALS['FenceModule_empty'], $player_13->Field, $player_13->Power, $player_13->Moves, $player_13->Hand, (function ($inputRecord_3) use ($player_13) {                     return new Bonus($inputRecord_3->NitroOne, $inputRecord_3->NitroTwo, $inputRecord_3->Watched, $inputRecord_3->HighVoltage, $inputRecord_3->Rutted, ($player_13->Bonus->Heliported + 1));
 })($player_13->Bonus)));
                case 14:
                    return new CrazyPlayer_Playing(new Playing($player_14->Color, $player_14->Tractor, $player_14->Fence, Field_op_Addition_Z24735800($player_14->Field, FieldModule_ofParcels(new Cons($p_1->Parcel, $GLOBALS['NIL']))), $player_14->Power, $player_14->Moves, $player_14->Hand, $player_14->Bonus));
                case 15:
                    return new CrazyPlayer_Playing(new Playing($player_15->Color, $player_15->Tractor, $player_15->Fence, $player_15->Field, $player_15->Power, $player_15->Moves, HandModule_remove(CardModule_ofPlayCard($card), $player_15->Hand), ($card->get_Tag() == 0 ? ($card->power->get_Tag() == 1 ? (function ($inputRecord_5) use ($player_15) {                     return new Bonus($inputRecord_5->NitroOne, ($player_15->Bonus->NitroTwo + 1), $inputRecord_5->Watched, $inputRecord_5->HighVoltage, $inputRecord_5->Rutted, $inputRecord_5->Heliported);
 })($player_15->Bonus) : (function ($inputRecord_4) use ($player_15) {                     return new Bonus(($player_15->Bonus->NitroOne + 1), $inputRecord_4->NitroTwo, $inputRecord_4->Watched, $inputRecord_4->HighVoltage, $inputRecord_4->Rutted, $inputRecord_4->Heliported);
 })($player_15->Bonus)) : $player_15->Bonus)));
                case 16:
                    return new CrazyPlayer_Playing(new Playing($player_16->Color, $player_16->Tractor, $player_16->Fence, $player_16->Field, $player_16->Power, $player_16->Moves, $player_16->Hand, BonusModule_discard($e_10, $player_16->Bonus)));
                case 17:
                    return new CrazyPlayer_Playing(new Playing($player_17->Color, $player_17->Tractor, $player_17->Fence, $player_17->Field, $player_17->Power, $player_17->Moves, HandModule_remove($card_2, $player_17->Hand), $player_17->Bonus));
                case 18:
                    return new CrazyPlayer_Ko($player_18->Color);
                case 19:
                case 20:
                    return $player;
            }
        } else {
            return $player;
        }
    }
}

#187
function Player_exec($otherPlayers, $barns, $haybales, $cmd, $state) {
    return \FSharpList\fold(function ($player_1, $event) {     return Player_evolve($player_1, $event);
 }, $state, Player_decide($otherPlayers, $barns, $haybales, function ($unitVar0) {     return new \Result_Ok($GLOBALS['FieldModule_empty']);
 }, $cmd, $state));
}

#188
function Player_move($dir, $player) {
    if ($player->get_Tag() == 1) {
        return Player_exec($GLOBALS['NIL'], $GLOBALS['BarnsModule_empty'], \Set\_empty([ 'Compare' => function ($x, $y) {         return \Util\compare($x, $y);
 }]), new Command_Move(new PlayerMove($dir, $player->Item->Tractor)), $player);
    } else {
        throw new Error('Not playing');
    }
}

#189
function Player_start($color, $parcel, $pos) {
    $state = new CrazyPlayer_Starting(new Starting($color, $parcel, new Hand_PublicHand($GLOBALS['NIL']), $GLOBALS['BonusModule_empty']));
    return Player_exec($GLOBALS['NIL'], $GLOBALS['BarnsModule_empty'], \Set\_empty([ 'Compare' => function ($x, $y) {     return \Util\compare($x, $y);
 }]), new Command_SelectFirstCrossroad(new SelectFirstCrossroad($pos)), $state);
}

#190
function Player_possibleMove($player, $dir) {
    $pos = CrossroadModule_neighbor($dir, $player->Tractor);
    if (CrossroadModule_isOnBoard($pos)) {
        return new Cons([ $dir, new \Result_Ok($pos)], $GLOBALS['NIL']);
    } else {
        return $GLOBALS['NIL'];
    }
}

#191
function Player_basicMoves($player) {
    if ($player->get_Tag() == 1) {
        if (MovesModule_canMove($player->Item->Moves)) {
            $player_2 = $player->Item;
            return \FSharpList\collect(function ($dir) use ($player_2) {             return Player_possibleMove($player_2, $dir);
 }, new Cons(new Direction_Up(), new Cons(new Direction_Down(), new Cons(new Direction_Horizontal(), $GLOBALS['NIL']))));
        } else {
            return $GLOBALS['NIL'];
        }
    } else {
        return $GLOBALS['NIL'];
    }
}

#192
function Player_bindMove($f, $cr) {
    switch ($cr->get_Tag())
    {
        case 1:
            return new \Result_Error($cr->ErrorValue);
        default:
            return $f($cr->ResultValue);
    }
}

#193
function Player_op_GreaterGreaterEquals($c, $f) {
    return Player_bindMove($f, $c);
}

#194
function Player_checkTractor($player, $c) {
    if (\Util\equals($c, $player->Tractor)) {
        return new \Result_Error([ $c, new MoveBlocker_Tractor()]);
    } else {
        return new \Result_Ok($c);
    }
}

#195
function Player_checkProtection($player, $c) {
    $matchValue = \Seq\tryFindIndex(function ($p) use ($c) {     return \Util\equals($p, $c);
 }, FenceModule_fenceCrossroads($player->Tractor, $player->Fence));
    if (!is_null($matchValue)) {
        if ($player->Bonus->HighVoltage) {
            return new \Result_Error([ $c, new MoveBlocker_HighVoltageProtection()]);
        } else {
            if ($matchValue < 2) {
                return new \Result_Error([ $c, new MoveBlocker_Protection()]);
            } else {
                return new \Result_Ok($c);
            }
        }
    } else {
        return new \Result_Ok($c);
    }
}

#196
function Player_checkHeliported($moverBonus, $player, $c) {
    if ($moverBonus->Heliported > 0) {
        if (\Seq\exists(function ($p) use ($c) {         return \Util\equals($p, $c);
 }, FenceModule_fenceCrossroads($player->Tractor, $player->Fence))) {
            return new \Result_Error([ $c, new MoveBlocker_PhytosanitaryProducts()]);
        } else {
            return new \Result_Ok($c);
        }
    } else {
        return new \Result_Ok($c);
    }
}

#197
function Player_checkMove($moverbonus, $player, $c) {
    if ($player->get_Tag() == 1) {
        $player_1 = $player->Item;
        return Player_op_GreaterGreaterEquals(Player_op_GreaterGreaterEquals(Player_checkTractor($player_1, $c), function ($c_1) use ($player_1) {         return Player_checkProtection($player_1, $c_1);
 }), function ($c_2) use ($moverbonus, $player_1) {         return Player_checkHeliported($moverbonus, $player_1, $c_2);
 });
    } else {
        return new \Result_Ok($c);
    }
}

#198
function Player_otherPlayers($playerid, $board) {
    return \Seq\toList(\Seq\filter(function ($tupledArg) use ($playerid) {     return $tupledArg[0] !== $playerid;
 }, \Map\toSeq($board->Players)));
}

#199
function Player_possibleMoves($playerid, $board) {
    $matchValue = [ $board, $playerid];
    if ($matchValue[0]->get_Tag() == 1) {
        if (!is_null($matchValue[1])) {
            $board_1 = $matchValue[0]->Item;
            $playerid_1 = $matchValue[1];
            $matchValue_1 = \Map\tryFind($playerid_1, $board_1->Players);
            if (!is_null($matchValue_1)) {
                if ($matchValue_1->get_Tag() == 1) {
                    if ((function ($player) use ($matchValue_1) {                     return MovesModule_canMove($matchValue_1->Item->Moves);
 })($matchValue_1)) {
                        $p_1 = $matchValue_1->Item;
                        $player_1 = $matchValue_1;
                        $otherPlayers = \FSharpList\map(function ($tuple) {                         return $tuple[1];
 }, Player_otherPlayers($playerid_1, $board_1));
                        $moverbonus = Player_bonus($player_1);
                        $check = function ($player_2) use ($moverbonus) {                         return function ($c) use ($moverbonus, $player_2) {                         return Player_checkMove($moverbonus, $player_2, $c);
 };
 };
                        return \Seq\toList(\Seq\delay(function ($unitVar) use ($board_1, $check, $otherPlayers, $p_1, $player_1) {                         return \Seq\collect(function ($matchValue_2) use ($board_1, $check, $otherPlayers, $p_1) { 
                            $dir = $matchValue_2[0];
                            if (\Set\contains(PathModule_neighbor($dir, $p_1->Tractor), $board_1->HayBales)) {
                                return \Seq\singleton(new Move_ImpossibleMove($dir, CrossroadModule_neighbor($dir, $p_1->Tractor), new MoveBlocker_HayBaleOnPath()));
                            } else {
                                $matchValue_3 = \Seq\fold(function ($c_2, $p_2) use ($check) {                                 return Player_bindMove($check($p_2), $c_2);
 }, $matchValue_2[1], $otherPlayers);
                                switch ($matchValue_3->get_Tag())
                                {
                                    case 1:
                                        return \Seq\singleton(new Move_ImpossibleMove($dir, $matchValue_3->ErrorValue[0], $matchValue_3->ErrorValue[1]));
                                    default:
                                        return \Seq\singleton(new Move_Move($dir, $matchValue_3->ResultValue));
                                }
                            }
                        }, Player_basicMoves($player_1));
 }));
                    } else {
                        if (is_null($matchValue_1)) {
                            return $GLOBALS['NIL'];
                        } else {
                            switch ($matchValue_1->get_Tag())
                            {
                                case 2:
                                    $_target__273 = 1;
                                    break;
                                case 1:
                                    $_target__273 = 1;
                                    break;
                                default:
                                    $p_3 = $matchValue_1->Item->Parcel->tile;
                                    $_target__273 = 0;
                                    break;
                            }
                            switch ($_target__273)
                            {
                                case 0:
                                    return new Cons(new Move_SelectCrossroad(new Crossroad($p_3, new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad($p_3, new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_NW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_NE']), new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_SW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_SE']), new CrossroadSide_CLeft())), $GLOBALS['NIL']))))));
                                case 1:
                                    return $GLOBALS['NIL'];
                            }
                        }
                    }
                } else {
                    if (is_null($matchValue_1)) {
                        return $GLOBALS['NIL'];
                    } else {
                        switch ($matchValue_1->get_Tag())
                        {
                            case 2:
                                $_target__274 = 1;
                                break;
                            case 1:
                                $_target__274 = 1;
                                break;
                            default:
                                $p_3 = $matchValue_1->Item->Parcel->tile;
                                $_target__274 = 0;
                                break;
                        }
                        switch ($_target__274)
                        {
                            case 0:
                                return new Cons(new Move_SelectCrossroad(new Crossroad($p_3, new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad($p_3, new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_NW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_NE']), new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_SW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_SE']), new CrossroadSide_CLeft())), $GLOBALS['NIL']))))));
                            case 1:
                                return $GLOBALS['NIL'];
                        }
                    }
                }
            } else {
                if (is_null($matchValue_1)) {
                    return $GLOBALS['NIL'];
                } else {
                    switch ($matchValue_1->get_Tag())
                    {
                        case 2:
                            $_target__275 = 1;
                            break;
                        case 1:
                            $_target__275 = 1;
                            break;
                        default:
                            $p_3 = $matchValue_1->Item->Parcel->tile;
                            $_target__275 = 0;
                            break;
                    }
                    switch ($_target__275)
                    {
                        case 0:
                            return new Cons(new Move_SelectCrossroad(new Crossroad($p_3, new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad($p_3, new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_NW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_NE']), new CrossroadSide_CLeft())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_SW']), new CrossroadSide_CRight())), new Cons(new Move_SelectCrossroad(new Crossroad(Axe_op_Addition_2BE35040($p_3, $GLOBALS['AxeModule_SE']), new CrossroadSide_CLeft())), $GLOBALS['NIL']))))));
                        case 1:
                            return $GLOBALS['NIL'];
                    }
                }
            }
        } else {
            return $GLOBALS['NIL'];
        }
    } else {
        return $GLOBALS['NIL'];
    }
}

#200
function Player_canMove($playerid, $board) {
    return \FSharpList\exists(function ($_arg1) {     if ($_arg1->get_Tag() == 1) {
        return false;
    } else {
        return true;
    }
 }, Player_possibleMoves($playerid, $board));
}

#201
function Player_takeCards($cards, $player) {
    switch ($player->get_Tag())
    {
        case 0:
            $p_1 = $player->Item;
            return new CrazyPlayer_Starting(new Starting($p_1->Color, $p_1->Parcel, (function ($matchValue_1) {             if ($matchValue_1[0]->get_Tag() == 0) {
                switch ($matchValue_1[1]->get_Tag())
                {
                    case 0:
                        $c_3 = $matchValue_1[1]->cards;
                        $h_3 = $matchValue_1[0]->cards;
                        return new Hand_PrivateHand(($h_3 + $c_3));
                    default:
                        throw new Error('Unexpected mix');
                }
            } else {
                switch ($matchValue_1[1]->get_Tag())
                {
                    case 1:
                        $c_2 = $matchValue_1[1]->cards;
                        $h_2 = $matchValue_1[0]->cards;
                        return new Hand_PublicHand(\FSharpList\append($h_2, $c_2));
                    default:
                        throw new Error('Unexpected mix');
                }
            }
 })([ $p_1->Hand, $cards]), $p_1->Bonus));
        case 2:
            return $player;
        default:
            $p = $player->Item;
            return new CrazyPlayer_Playing(new Playing($p->Color, $p->Tractor, $p->Fence, $p->Field, $p->Power, $p->Moves, (function ($matchValue) {             if ($matchValue[0]->get_Tag() == 0) {
                switch ($matchValue[1]->get_Tag())
                {
                    case 0:
                        $c_1 = $matchValue[1]->cards;
                        $h_1 = $matchValue[0]->cards;
                        return new Hand_PrivateHand(($h_1 + $c_1));
                    default:
                        throw new Error('Unexpected mix');
                }
            } else {
                switch ($matchValue[1]->get_Tag())
                {
                    case 1:
                        $c = $matchValue[1]->cards;
                        $h = $matchValue[0]->cards;
                        return new Hand_PublicHand(\FSharpList\append($h, $c));
                    default:
                        throw new Error('Unexpected mix');
                }
            }
 })([ $p->Hand, $cards]), $p->Bonus));
    }
}

#202
function Player_toState($p) {
    switch ($p->get_Tag())
    {
        case 1:
            $p_2 = $p->Item;
            return new PlayerState_SPlaying(new PlayingState($p_2->Color, $p_2->Tractor, $p_2->Fence, \Set\toList($p_2->Field->parcels), $p_2->Power, $p_2->Moves, $p_2->Hand, $p_2->Bonus));
        case 2:
            return new PlayerState_SKo($p->Item);
        default:
            $p_1 = $p->Item;
            return new PlayerState_SStarting(new StartingState($p_1->Color, $p_1->Parcel, $p_1->Hand, $p_1->Bonus));
    }
}

#203
function Player_ofState($p) {
    switch ($p->get_Tag())
    {
        case 1:
            $p_2 = $p->Item;
            return new CrazyPlayer_Playing(new Playing($p_2->SColor, $p_2->STractor, $p_2->SFence, new Field(\Set\ofSeq($p_2->SField, [ 'Compare' => function ($x, $y) {             return \Util\compare($x, $y);
 }])), $p_2->SPower, $p_2->SMoves, $p_2->SHand, $p_2->SBonus));
        case 2:
            return new CrazyPlayer_Ko($p->Item);
        default:
            $p_1 = $p->Item;
            return new CrazyPlayer_Starting(new Starting($p_1->SColor, $p_1->SParcel, $p_1->SHand, $p_1->SBonus));
    }
}

#204
$GLOBALS['HistoryModule_empty'] = new History(\Map\_empty());

#205
function HistoryModule_createPos($board) {
    return new BoardPosition(\Set\ofSeq(\Seq\choose(function ($tupledArg) { 
        $player = $tupledArg[1];
        if ($player->get_Tag() == 1) {
            $p = $player->Item;
            return new PlayerPosition($tupledArg[0], $p->Tractor, $p->Fence, $p->Field);
        } else {
            return NULL;
        }
    }, \Map\toSeq($board->Players)), [ 'Compare' => function ($x, $y) {     return \Util\compare($x, $y);
 }]));
}

#206
function HistoryModule_repetitions($player, $boardPos, $history) {
    $matchValue = \Map\tryFind($player, $history->PlayersHistory);
    if (is_null($matchValue)) {
        return 0;
    } else {
        $count = function ($n, $h_1) use ($boardPos, &$count) {         if ($h_1 instanceof Cons) {
            $rest = $h_1->next;
            if (\Util\equals($h_1->value, $boardPos)) {
                return $count($n + 1, $rest);
            } else {
                return $count($n, $rest);
            }
        } else {
            return $n;
        }
 };
        return $count(0, $matchValue);
    }
}

#207
function HistoryModule_addPos($player, $boardPos, $history) {
    return new History(\Map\add($player, new Cons($boardPos, (function ($matchValue) {     if (!is_null($matchValue)) {
        return $matchValue;
    } else {
        return $GLOBALS['NIL'];
    }
 })(\Map\tryFind($player, $history->PlayersHistory))), $history->PlayersHistory));
}

#208
function HistoryModule_findDangerousPositions($player, $boardPos, $history) {
    return \Seq\toList(\Seq\delay(function ($unitVar) use ($boardPos, $history, $player) {     return \Seq\collect(function ($matchValue) use ($boardPos, $player) { 
        $activePatternResult1357 = $matchValue;
        $opponent = $activePatternResult1357[0];
        if ($opponent !== $player) {
            $posOfOtherPlayers = \Set\filter(function ($p) use ($opponent) {             return $p->Player !== $opponent;
 }, $boardPos->Positions);
            $findRep = function ($once, $twice, $positions) use ($posOfOtherPlayers, &$findRep) {             if ($positions instanceof Cons) {
                $rest = $positions->next;
                $pos = $positions->value;
                if (\Set\isSubset($posOfOtherPlayers, $pos->Positions)) {
                    if (\Set\contains($pos->Positions, $once)) {
                        return $findRep($once, \Set\add($pos->Positions, $twice), $rest);
                    } else {
                        return $findRep(\Set\add($pos->Positions, $once), $twice, $rest);
                    }
                } else {
                    return $findRep($once, $twice, $rest);
                }
            } else {
                return $twice;
            }
 };
            return \Seq\toList(\Seq\map(function ($p_2) {             return [ $p_2->Player, $p_2->TractorPos];
 }, \Seq\choose(function ($source) use ($opponent) {             return \Seq\tryFind(function ($p_1) use ($opponent) {             return $p_1->Player === $opponent;
 }, $source);
 }, $findRep(\Set\_empty([ 'Compare' => function ($x, $y) {             return \Util\compare($x, $y);
 }]), \Set\_empty([ 'Compare' => function ($x_1, $y_1) {             return \Util\compare($x_1, $y_1);
 }]), $activePatternResult1357[1]))));
        } else {
            return \Seq\_empty();
        }
    }, $history->PlayersHistory);
 }));
}

#209
$GLOBALS['BoardModule_initialState'] = new UndoableBoard(new Board_InitialState(), new Board_InitialState(), new \Shared\UndoType_NoUndo(), false, true, false);

#210
abstract class BoardCommand implements FSharpUnion {
}

#210
class BoardCommand_Play extends BoardCommand implements IComparable {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
    function get_FSharpCase() {
        return 'Play';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__276 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__276 != 0) {
            return $_cmp__276;
        }        
        $_cmp__277 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__277 != 0) {
            return $_cmp__277;
        }        
        $_cmp__278 = $this->Item2->CompareTo($other->Item2);
        if ($_cmp__278 != 0) {
            return $_cmp__278;
        }        
        return 0;
    }
}

#210
class BoardCommand_Start extends BoardCommand implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Start';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__279 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__279 != 0) {
            return $_cmp__279;
        }        
        $_cmp__280 = $this->Item->CompareTo($other->Item);
        if ($_cmp__280 != 0) {
            return $_cmp__280;
        }        
        return 0;
    }
}

#211
class BoardStart implements IComparable {
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
    function CompareTo($other) {
        $_cmp__281 = $this->Players->CompareTo($other->Players);
        if ($_cmp__281 != 0) {
            return $_cmp__281;
        }        
        $_cmp__282 = $this->Goal->CompareTo($other->Goal);
        if ($_cmp__282 != 0) {
            return $_cmp__282;
        }        
        $_cmp__283 = $this->Undo->CompareTo($other->Undo);
        if ($_cmp__283 != 0) {
            return $_cmp__283;
        }        
        $_cmp__284 = $this->UseGameOver->CompareTo($other->UseGameOver);
        if ($_cmp__284 != 0) {
            return $_cmp__284;
        }        
        return 0;
    }
}

#212
abstract class BoardEvent implements FSharpUnion {
}

#212
class BoardEvent_Played extends BoardEvent implements IComparable {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
    function get_FSharpCase() {
        return 'Played';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__285 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__285 != 0) {
            return $_cmp__285;
        }        
        $_cmp__286 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__286 != 0) {
            return $_cmp__286;
        }        
        $_cmp__287 = $this->Item2->CompareTo($other->Item2);
        if ($_cmp__287 != 0) {
            return $_cmp__287;
        }        
        return 0;
    }
}

#212
class BoardEvent_Started extends BoardEvent implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Started';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__288 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__288 != 0) {
            return $_cmp__288;
        }        
        $_cmp__289 = $this->Item->CompareTo($other->Item);
        if ($_cmp__289 != 0) {
            return $_cmp__289;
        }        
        return 0;
    }
}

#212
class BoardEvent_Next extends BoardEvent implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'Next';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__290 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__290;
    }
}

#212
class BoardEvent_PlayerDrewCards extends BoardEvent implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'PlayerDrewCards';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__291 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__291 != 0) {
            return $_cmp__291;
        }        
        $_cmp__292 = $this->Item->CompareTo($other->Item);
        if ($_cmp__292 != 0) {
            return $_cmp__292;
        }        
        return 0;
    }
}

#212
class BoardEvent_GameWon extends BoardEvent implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'GameWon';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__293 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__293 != 0) {
            return $_cmp__293;
        }        
        $_cmp__294 = $this->Item->CompareTo($other->Item);
        if ($_cmp__294 != 0) {
            return $_cmp__294;
        }        
        return 0;
    }
}

#212
class BoardEvent_GameEnded extends BoardEvent implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'GameEnded';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__295 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__295 != 0) {
            return $_cmp__295;
        }        
        $_cmp__296 = $this->Item->CompareTo($other->Item);
        if ($_cmp__296 != 0) {
            return $_cmp__296;
        }        
        return 0;
    }
}

#212
class BoardEvent_GameEndedByRepetition extends BoardEvent implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'GameEndedByRepetition';
    }
    function get_Tag() {
        return 6;
    }
    function CompareTo($other) {
        $_cmp__297 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__297;
    }
}

#212
class BoardEvent_RepetitionDetected extends BoardEvent implements IComparable {
    public $player;
    function __construct($player) {
        $this->player = $player;
    }
    function get_FSharpCase() {
        return 'RepetitionDetected';
    }
    function get_Tag() {
        return 7;
    }
    function CompareTo($other) {
        $_cmp__298 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__298 != 0) {
            return $_cmp__298;
        }        
        $_cmp__299 = $this->player->CompareTo($other->player);
        if ($_cmp__299 != 0) {
            return $_cmp__299;
        }        
        return 0;
    }
}

#212
class BoardEvent_HayBalesPlaced extends BoardEvent implements IComparable {
    public $added;
    public $removed;
    function __construct($added, $removed) {
        $this->added = $added;
        $this->removed = $removed;
    }
    function get_FSharpCase() {
        return 'HayBalesPlaced';
    }
    function get_Tag() {
        return 8;
    }
    function CompareTo($other) {
        $_cmp__300 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__300 != 0) {
            return $_cmp__300;
        }        
        $_cmp__301 = $this->added->CompareTo($other->added);
        if ($_cmp__301 != 0) {
            return $_cmp__301;
        }        
        $_cmp__302 = $this->removed->CompareTo($other->removed);
        if ($_cmp__302 != 0) {
            return $_cmp__302;
        }        
        return 0;
    }
}

#212
class BoardEvent_HayBaleDynamited extends BoardEvent implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'HayBaleDynamited';
    }
    function get_Tag() {
        return 9;
    }
    function CompareTo($other) {
        $_cmp__303 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__303 != 0) {
            return $_cmp__303;
        }        
        $_cmp__304 = $this->Item->CompareTo($other->Item);
        if ($_cmp__304 != 0) {
            return $_cmp__304;
        }        
        return 0;
    }
}

#212
class BoardEvent_DiscardPileShuffled extends BoardEvent implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'DiscardPileShuffled';
    }
    function get_Tag() {
        return 10;
    }
    function CompareTo($other) {
        $_cmp__305 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__305 != 0) {
            return $_cmp__305;
        }        
        $_cmp__306 = $this->Item->CompareTo($other->Item);
        if ($_cmp__306 != 0) {
            return $_cmp__306;
        }        
        return 0;
    }
}

#212
class BoardEvent_DrawPileShuffled extends BoardEvent implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'DrawPileShuffled';
    }
    function get_Tag() {
        return 11;
    }
    function CompareTo($other) {
        $_cmp__307 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__307 != 0) {
            return $_cmp__307;
        }        
        $_cmp__308 = $this->Item->CompareTo($other->Item);
        if ($_cmp__308 != 0) {
            return $_cmp__308;
        }        
        return 0;
    }
}

#212
class BoardEvent_UndoCheckPointed extends BoardEvent implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'UndoCheckPointed';
    }
    function get_Tag() {
        return 12;
    }
    function CompareTo($other) {
        $_cmp__309 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__309;
    }
}

#213
class BoardStarted implements IComparable {
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
    function CompareTo($other) {
        $_cmp__310 = $this->Players->CompareTo($other->Players);
        if ($_cmp__310 != 0) {
            return $_cmp__310;
        }        
        $_cmp__311 = $this->DrawPile->CompareTo($other->DrawPile);
        if ($_cmp__311 != 0) {
            return $_cmp__311;
        }        
        $_cmp__312 = $this->Barns->CompareTo($other->Barns);
        if ($_cmp__312 != 0) {
            return $_cmp__312;
        }        
        $_cmp__313 = $this->Goal->CompareTo($other->Goal);
        if ($_cmp__313 != 0) {
            return $_cmp__313;
        }        
        $_cmp__314 = $this->Undo->CompareTo($other->Undo);
        if ($_cmp__314 != 0) {
            return $_cmp__314;
        }        
        $_cmp__315 = $this->UseGameOver->CompareTo($other->UseGameOver);
        if ($_cmp__315 != 0) {
            return $_cmp__315;
        }        
        return 0;
    }
}

#214
class PlayerDrewCards implements IComparable {
    public $Player;
    public $Cards;
    function __construct($Player, $Cards) {
        $this->Player = $Player;
        $this->Cards = $Cards;
    }
    function CompareTo($other) {
        $_cmp__316 = $this->Player > $other->Player ? 1 : ($this->Player < $other->Player ? -1 : 0);
        if ($_cmp__316 != 0) {
            return $_cmp__316;
        }        
        $_cmp__317 = $this->Cards->CompareTo($other->Cards);
        if ($_cmp__317 != 0) {
            return $_cmp__317;
        }        
        return 0;
    }
}

#215
function BoardModule_currentPlayer($board) {
    return $board->Players->get_Item($board->Table->get_Player());
}

#216
function BoardModule_currentOtherPlayers($board) {
    return Player_otherPlayers($board->Table->get_Player(), $board);
}

#217
function BoardModule_totalSize($board) {
    return \Map\fold(function ($count, $_arg1, $p) {     return $count + Player_fieldTotalSize($p);
 }, 0, $board->Players);
}

#218
function BoardModule_hayBales($board) {
    switch ($board->get_Tag())
    {
        case 1:
            return $board->Item->HayBales;
        case 2:
            return $board->Item2->HayBales;
        default:
            return \Set\_empty([ 'Compare' => function ($x, $y) {             return \Util\compare($x, $y);
 }]);
    }
}

#219
function BoardModule_endGameWithBribe($board) {
    $matchValue = $board->Goal;
    switch ($matchValue->get_Tag())
    {
        case 1:
            return Player_fieldTotalSize(BoardModule_currentPlayer($board)) + 1 >= $matchValue->Item;
        default:
            return false;
    }
}

#220
abstract class FindWinner implements FSharpUnion {
}

#220
class FindWinner_Winner extends FindWinner implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Winner';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__318 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__318 != 0) {
            return $_cmp__318;
        }        
        $_cmp__319 = $this->Item->CompareTo($other->Item);
        if ($_cmp__319 != 0) {
            return $_cmp__319;
        }        
        return 0;
    }
}

#220
class FindWinner_Lead extends FindWinner implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Lead';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__320 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__320 != 0) {
            return $_cmp__320;
        }        
        $_cmp__321 = $this->Item->CompareTo($other->Item);
        if ($_cmp__321 != 0) {
            return $_cmp__321;
        }        
        return 0;
    }
}

#221
function BoardModule_tryFindWinner($board) {
    $players = $board->Players;
    $leadsids = \FSharpList\map(function ($tupledArg_2) {     return $tupledArg_2[0];
 }, \FSharpList\maxBy(function ($tupledArg_1) {     return $tupledArg_1[0];
 }, \Seq2\List_groupBy(function ($tupledArg) { 
        $p = $tupledArg[1];
        return [ Player_principalFieldSize($p), Player_fieldTotalSize($p)];
    }, \Map\toList($players), [ 'Equals' => function ($x, $y) {     return \Util\equalArrays($x, $y);
 }, 'GetHashCode' => function ($x) {     return \Util\arrayHash($x);
 }]), [ 'Compare' => function ($x_1, $y_1) {     return \Util\compareArrays($x_1, $y_1);
 }])[1]);
    $matchValue = $board->Goal;
    switch ($matchValue->get_Tag())
    {
        case 1:
            if (\Map\exists(function ($_arg4, $p_1) use ($matchValue) {             return Player_fieldTotalSize($p_1) >= $matchValue->Item;
 }, $players)) {
                return new FindWinner_Winner($leadsids);
            } else {
                return new FindWinner_Lead($leadsids);
            }
        default:
            if (BoardModule_totalSize($board) >= $matchValue->Item) {
                return new FindWinner_Winner($leadsids);
            } else {
                return new FindWinner_Lead($leadsids);
            }
    }
}

#222
function BoardModule_next($shouldShuffle, $undone, $repeated, $state) {
    $playerId = $state->Table->get_Player();
    $player = \Map\tryFind($playerId, $state->Players);
    $nextPlayerId = $state->Table->get_Next()->get_Player();
    $nextPlayer = $state->Players->get_Item($nextPlayerId);
    return \Seq\toList(\Seq\delay(function ($unitVar) use ($nextPlayer, $nextPlayerId, $player, $playerId, $repeated, $shouldShuffle, $state, $undone) {     return \Seq\append(\FSharpList\map(function ($c) use ($playerId) {     return new BoardEvent_Played($playerId, new Event_BonusDiscarded($c));
 }, BonusModule_endTurn(is_null($player) ? $GLOBALS['BonusModule_empty'] : Player_bonus($player))), \Seq\delay(function ($unitVar_1) use ($nextPlayer, $nextPlayerId, $playerId, $repeated, $shouldShuffle, $state, $undone) {     return \Seq\append(($shouldShuffle ? $undone : false) ? (function ($matchValue) use ($state) {     switch ($matchValue->get_Tag())
    {
        case 0:
            return \Seq\_empty();
        default:
            return \Seq\singleton(new BoardEvent_DrawPileShuffled(DrawPile_shuffle($state->UseGameOver, $matchValue->cards)));
    }
 })($state->DrawPile) : \Seq\_empty(), \Seq\delay(function ($unitVar_2) use ($nextPlayer, $nextPlayerId, $playerId, $repeated) {     return \Seq\append(\Seq\singleton(new BoardEvent_Next()), \Seq\delay(function ($unitVar_3) use ($nextPlayer, $nextPlayerId, $playerId, $repeated) {     return \Seq\append($repeated ? \Seq\singleton(new BoardEvent_RepetitionDetected($playerId)) : \Seq\_empty(), \Seq\delay(function ($unitVar_4) use ($nextPlayer, $nextPlayerId) {     return \Seq\append(\FSharpList\map(function ($c_1) use ($nextPlayerId) {     return new BoardEvent_Played($nextPlayerId, new Event_BonusDiscarded($c_1));
 }, BonusModule_startTurn(Player_bonus($nextPlayer))), \Seq\delay(function ($unitVar_5) {     return \Seq\singleton(new BoardEvent_UndoCheckPointed());
 }));
 }));
 }));
 }));
 }));
 }));
}

#223
abstract class BribeBlocker implements FSharpUnion {
}

#223
class BribeBlocker_InstantVictory extends BribeBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'InstantVictory';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__322 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__322;
    }
}

#223
class BribeBlocker_NoParcelsToBribe extends BribeBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'NoParcelsToBribe';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__323 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__323;
    }
}

#224
abstract class BribeParcelBlocker implements FSharpUnion {
}

#224
class BribeParcelBlocker_BarnBlocker extends BribeParcelBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'BarnBlocker';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__324 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__324;
    }
}

#224
class BribeParcelBlocker_LastParcelBlocker extends BribeParcelBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'LastParcelBlocker';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__325 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__325;
    }
}

#224
class BribeParcelBlocker_WatchedBlocker extends BribeParcelBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'WatchedBlocker';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__326 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__326;
    }
}

#224
class BribeParcelBlocker_FenceBlocker extends BribeParcelBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'FenceBlocker';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__327 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__327;
    }
}

#224
class BribeParcelBlocker_FallowBlocker extends BribeParcelBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'FallowBlocker';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__328 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__328;
    }
}

#224
class BribeParcelBlocker_BridgeBlocker extends BribeParcelBlocker implements IComparable {
    function __construct() {
    }
    function get_FSharpCase() {
        return 'BridgeBlocker';
    }
    function get_Tag() {
        return 5;
    }
    function CompareTo($other) {
        $_cmp__329 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        return $_cmp__329;
    }
}

#225
function BoardModule_isCutParcel($field, $parcel) {
    $find = function ($neighbors_1, $result) use ($field, $parcel, &$find) {     if ($neighbors_1 instanceof Cons) {
        $tail = $neighbors_1->next;
        $infield = FieldModule_containsParcel(Parcel_op_Addition_ZF6EFE4B($parcel, $neighbors_1->value), $field);
        if ($result instanceof Cons) {
            if ($result->value === $infield) {
                $prev_1 = $result->value;
                return $find($tail, $result);
            } else {
                return $find($tail, new Cons($infield, $result));
            }
        } else {
            return $find($tail, new Cons($infield, $GLOBALS['NIL']));
        }
    } else {
        return $result;
    }
 };
    $changes = $find(new Cons($GLOBALS['AxeModule_N'], new Cons($GLOBALS['AxeModule_NE'], new Cons($GLOBALS['AxeModule_SE'], new Cons($GLOBALS['AxeModule_S'], new Cons($GLOBALS['AxeModule_SW'], new Cons($GLOBALS['AxeModule_NW'], $GLOBALS['NIL'])))))), $GLOBALS['NIL']);
    return \FSharpList\length((\FSharpList\head($changes) === \FSharpList\last($changes) ? \FSharpList\tail($changes) : $changes)) > 2;
}

#226
function BoardModule_cutParcels($field, $_arg1) {
    return FieldModule_ofParcels(\Seq\filter(function ($p) use ($field) {     return BoardModule_isCutParcel($field, $p);
 }, $_arg1->parcels));
}

#227
function BoardModule_findBridgeParcels($field) {
    $border = FieldModule_unrestrictedborderTiles($field)->parcels;
    $cut = $GLOBALS['NIL'];
    $visited = \Map\_empty();
    $time = 0;
    $loop = function ($parent, $parcel_1) use ($border, &$cut, &$loop, &$time, &$visited) { 
        $visited = \Map\add($parcel_1, $time, $visited);
        $d0 = $time;
        $time = $time + 1;
        $isRoot = \Util\equals($parent, $parcel_1);
        $step = function ($dir_1, $tupledArg) use ($border, $d0, $isRoot, $parcel_1, $parent, &$cut, &$loop, &$visited) { 
            $low = $tupledArg[0];
            $children = $tupledArg[1];
            $neighbor = Parcel_op_Addition_ZF6EFE4B($parcel_1, $dir_1);
            if (\Set\contains($neighbor, $border)) {
                $matchValue = $neighbor;
            } else {
                $matchValue = NULL;
            }
            if (!is_null($matchValue)) {
                if (!\Util\equals($matchValue, $parent)) {
                    $nxt_1 = $matchValue;
                    $matchValue_1 = \Map\tryFind($nxt_1, $visited);
                    if (is_null($matchValue_1)) {
                        $patternInput_1 = [ $loop($parcel_1, $nxt_1), $children + 1];
                    } else {
                        $patternInput_1 = [ \Util\min(function ($x, $y) {                         return \Util\comparePrimitives($x, $y);
 }, $matchValue_1, $low), $children];
                    }
                    $n = $patternInput_1[0];
                    if ($n >= $d0 ? !$isRoot : false) {
                        $cut = new Cons($parcel_1, $cut);
                    } else {
                    }
                    return [ \Util\min(function ($x_1, $y_1) {                     return \Util\comparePrimitives($x_1, $y_1);
 }, $n, $low), $patternInput_1[1]];
                } else {
                    return [ $low, $children];
                }
            } else {
                return [ $low, $children];
            }
        };
        $patternInput_2 = $step($GLOBALS['AxeModule_NW'], $step($GLOBALS['AxeModule_SW'], $step($GLOBALS['AxeModule_S'], $step($GLOBALS['AxeModule_SE'], $step($GLOBALS['AxeModule_NE'], $step($GLOBALS['AxeModule_N'], [ $d0, 0]))))));
        $d_1 = $patternInput_2[0];
        if ($isRoot ? $patternInput_2[1] > 1 : false) {
            $cut = new Cons($parcel_1, $cut);
        } else {
        }
        $visited = \Map\add($parcel_1, $d_1, $visited);
        return $d_1;
    };
    $start = \Set\minElement($border);
    void($loop($start, $start));
    return FieldModule_ofParcels(\FSharpList\filter(function ($arg00_0040) {     return ParcelModule_isOnBoard($arg00_0040);
 }, $cut));
}

#228
function BoardModule_bribeParcels($board) {
    if (BoardModule_endGameWithBribe($board)) {
        return new \Result_Error(new BribeBlocker_InstantVictory());
    } else {
        $playerField = Player_field($board->Players->get_Item($board->Table->get_Player()));
        $border = FieldModule_borderTiles($playerField);
        $barns = Field_op_Addition_Z24735800($board->Barns->Free, $board->Barns->Occupied);
        $bridgeParcels = BoardModule_findBridgeParcels($playerField);
        $parcelsToBribe = Field_op_Subtraction_Z24735800(FieldModule_intersect($border, FieldModule_unionMany(\FSharpList\map(function ($tupledArg) use ($border, $bridgeParcels) { 
            $neighborPlayer = $tupledArg[1];
            $field = Player_field($neighborPlayer);
            $bonus = Player_bonus($neighborPlayer);
            if (FieldModule_size($field) === 1 ? true : $bonus->Watched) {
                return $GLOBALS['FieldModule_empty'];
            } else {
                $cutParcels = BoardModule_cutParcels($field, FieldModule_intersect($field, $border));
                switch ($neighborPlayer->get_Tag())
                {
                    case 0:
                        $_target__330 = 1;
                        break;
                    case 2:
                        $_target__330 = 1;
                        break;
                    default:
                        $_target__330 = 0;
                        break;
                }
                switch ($_target__330)
                {
                    case 0:
                        $p = $neighborPlayer->Item;
                        return Field_op_Subtraction_Z24735800(Field_op_Subtraction_Z24735800(Field_op_Subtraction_Z24735800($field, FieldModule_intersect($field, FieldModule_ofParcels(CrossroadModule_neighborTiles(FenceModule_start($p->Tractor, $p->Fence))))), $cutParcels), $bridgeParcels);
                    case 1:
                        return $field;
                }
            }
        }, BoardModule_currentOtherPlayers($board)))), $barns);
        if (FieldModule_isEmpty($parcelsToBribe)) {
            return new \Result_Error(new BribeBlocker_NoParcelsToBribe());
        } else {
            return new \Result_Ok($parcelsToBribe);
        }
    }
}

#229
function BoardModule_bribeParcelsBlockers($board) {
    if (BoardModule_endGameWithBribe($board)) {
        return $GLOBALS['NIL'];
    } else {
        $playerField = Player_field($board->Players->get_Item($board->Table->get_Player()));
        $border = FieldModule_borderTiles($playerField);
        $barns = FieldModule_intersect(FieldModule_unionMany(\Seq\toList(\Seq\delay(function ($unitVar) use ($board) {         return \Seq\collect(function ($matchValue) {         return \Seq\singleton(Player_field($matchValue[1]));
 }, BoardModule_currentOtherPlayers($board));
 }))), FieldModule_intersect($border, Field_op_Addition_Z24735800($board->Barns->Free, $board->Barns->Occupied)));
        $border_1 = Field_op_Subtraction_Z24735800($border, $barns);
        $bridgeParcels = BoardModule_findBridgeParcels($playerField);
        return \Seq\toList(\Seq\delay(function ($unitVar_1) use ($barns, $board, $border_1, $bridgeParcels) {         return \Seq\append(\Seq\map(function ($barn) {         return [ $barn, new BribeParcelBlocker_BarnBlocker()];
 }, FieldModule_parcels($barns)), \Seq\delay(function ($unitVar_2) use ($board, $border_1, $bridgeParcels) {         return \Seq\collect(function ($matchValue_1) use ($border_1, $bridgeParcels) { 
            $neighborPlayer = $matchValue_1[1];
            $field = Player_field($neighborPlayer);
            $bonus = Player_bonus($neighborPlayer);
            $fieldBorder = FieldModule_intersect($border_1, $field);
            if (FieldModule_size($field) === 1) {
                return \Seq\map(function ($p_1) {                 return [ $p_1, new BribeParcelBlocker_LastParcelBlocker()];
 }, FieldModule_parcels($fieldBorder));
            } else {
                if ($bonus->Watched) {
                    return \Seq\map(function ($p_2) {                     return [ $p_2, new BribeParcelBlocker_WatchedBlocker()];
 }, FieldModule_parcels($fieldBorder));
                } else {
                    switch ($neighborPlayer->get_Tag())
                    {
                        case 0:
                            $_target__330 = 1;
                            break;
                        case 2:
                            $_target__330 = 1;
                            break;
                        default:
                            $_target__330 = 0;
                            break;
                    }
                    switch ($_target__330)
                    {
                        case 0:
                            $p_3 = $neighborPlayer->Item;
                            return \Seq\append(\Seq\map(function ($p_4) {                             return [ $p_4, new BribeParcelBlocker_FenceBlocker()];
 }, FieldModule_parcels(FieldModule_intersect(FieldModule_intersect($field, FieldModule_ofParcels(CrossroadModule_neighborTiles(FenceModule_start($p_3->Tractor, $p_3->Fence)))), $border_1))), \Seq\delay(function ($unitVar_3) use ($border_1, $bridgeParcels, $field) {                             return \Seq\append(\Seq\map(function ($p_5) {                             return [ $p_5, new BribeParcelBlocker_FallowBlocker()];
 }, FieldModule_parcels(BoardModule_cutParcels($field, FieldModule_intersect($field, $border_1)))), \Seq\delay(function ($unitVar_4) use ($bridgeParcels, $field) {                             return \Seq\map(function ($p_6) {                             return [ $p_6, new BribeParcelBlocker_BridgeBlocker()];
 }, FieldModule_parcels(FieldModule_intersect($bridgeParcels, $field)));
 }));
 }));
                        case 1:
                            return \Seq\_empty();
                    }
                }
            }
        }, BoardModule_currentOtherPlayers($board));
 }));
 }));
    }
}

#230
function BoardModule_annexed($playerid, $e, $board) {
    $newMap = \Map\add($playerid, Player_evolve($board->Players->get_Item($playerid), new Event_Annexed($e)), $board->Players);
    $freeFields = Field_op_Subtraction_Z24735800(FieldModule_ofParcels($e->NewField), FieldModule_unionMany(\Seq\toList(\Seq\delay(function ($unitVar) use ($e) {     return \Seq\collect(function ($matchValue) {     return \Seq\singleton(FieldModule_ofParcels($matchValue[1]));
 }, $e->LostFields);
 }))));
    return \FSharpList\fold(function ($board_1, $tupledArg) { 
        $playerid_1 = $tupledArg[0];
        $matchValue_1 = $board_1->Players->get_Item($playerid_1);
        if ($matchValue_1->get_Tag() == 1) {
            $p = $matchValue_1->Item;
            return new PlayingBoard(\Map\add($playerid_1, new CrazyPlayer_Playing(new Playing($p->Color, $p->Tractor, $p->Fence, Field_op_Subtraction_Z24735800($p->Field, FieldModule_ofParcels($tupledArg[1])), $p->Power, $p->Moves, $p->Hand, $p->Bonus)), $board_1->Players), $board_1->Table, $board_1->DrawPile, $board_1->DiscardPile, $board_1->Barns, $board_1->HayBales, $board_1->Goal, $board_1->UseGameOver, $board_1->History);
        } else {
            return $board_1;
        }
    }, new PlayingBoard($newMap, $board->Table, $board->DrawPile, $board->DiscardPile, BarnsModule_annex(new Barns(FieldModule_ofParcels($e->FreeBarns), FieldModule_ofParcels($e->OccupiedBarns)), $board->Barns), $board->HayBales, $board->Goal, $board->UseGameOver, (FieldModule_isEmpty($freeFields) ? $board->History : $GLOBALS['HistoryModule_empty'])), $e->LostFields);
}

#231
function BoardModule_bribed($playerid, $p, $board) {
    $newPlayer = Player_evolve($board->Players->get_Item($playerid), new Event_Bribed($p));
    return new PlayingBoard(\Map\add($p->Victim, (function ($matchValue) use ($p) {     switch ($matchValue->get_Tag())
    {
        case 1:
            $victim = $matchValue->Item;
            return new CrazyPlayer_Playing(new Playing($victim->Color, $victim->Tractor, $victim->Fence, Field_op_Subtraction_Z24735800($victim->Field, FieldModule_ofParcels(new Cons($p->Parcel, $GLOBALS['NIL']))), $victim->Power, $victim->Moves, $victim->Hand, $victim->Bonus));
        case 2:
            return $matchValue;
        default:
            return new CrazyPlayer_Starting($matchValue->Item);
    }
 })($board->Players->get_Item($p->Victim)), \Map\add($playerid, $newPlayer, $board->Players)), $board->Table, $board->DrawPile, $board->DiscardPile, $board->Barns, $board->HayBales, $board->Goal, $board->UseGameOver, $board->History);
}

#232
function BoardModule_evolve($state, $event) {
    $matchValue = [ $state->Board, $event];
    if ($matchValue[0]->get_Tag() == 1) {
        if ($matchValue[1]->get_Tag() == 4) {
            $board_1 = $matchValue[0]->Item;
            $player = $matchValue[1]->Item;
            $won = new Board_Won(new Cons($player, $GLOBALS['NIL']), $board_1);
            return new UndoableBoard($won, $won, $state->UndoType, $state->ShouldShuffle, true, false);
        } else {
            if ($matchValue[1]->get_Tag() == 5) {
                $board_2 = $matchValue[0]->Item;
                $players = $matchValue[1]->Item;
                $won_1 = new Board_Won($players, $board_2);
                return new UndoableBoard($won_1, $won_1, $state->UndoType, $state->ShouldShuffle, true, false);
            } else {
                if ($matchValue[1]->get_Tag() == 6) {
                    $board_3 = $matchValue[0]->Item;
                    $won_2 = new Board_Won($GLOBALS['NIL'], $board_3);
                    return new UndoableBoard($won_2, $won_2, $state->UndoType, $state->ShouldShuffle, true, false);
                } else {
                    if ($matchValue[1]->get_Tag() == 7) {
                        return $state;
                    } else {
                        if ($matchValue[1]->get_Tag() == 0) {
                            switch ($matchValue[1]->Item2->get_Tag())
                            {
                                case 8:
                                    $board_4 = $matchValue[0]->Item;
                                    $playerid = $matchValue[1]->Item2->Item->Player;
                                    $matchValue_3 = $board_4->Players->get_Item($playerid);
                                    if ($matchValue_3->get_Tag() == 1) {
                                        $player_1 = $matchValue_3->Item;
                                        return new UndoableBoard(new Board_Board(new PlayingBoard(\Map\add($playerid, new CrazyPlayer_Playing(new Playing($player_1->Color, $player_1->Tractor, $GLOBALS['FenceModule_empty'], $player_1->Field, new Power_PowerDown(), $player_1->Moves, $player_1->Hand, $player_1->Bonus)), $board_4->Players), $board_4->Table, $board_4->DrawPile, $board_4->DiscardPile, $board_4->Barns, $board_4->HayBales, $board_4->Goal, $board_4->UseGameOver, $board_4->History)), $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, false, $state->Undone);
                                    } else {
                                        return $state;
                                    }
                                case 7:
                                    $board_5 = $matchValue[0]->Item;
                                    $e = $matchValue[1]->Item2->Item;
                                    $playerid_1 = $matchValue[1]->Item1;
                                    return new UndoableBoard(new Board_Board(BoardModule_annexed($playerid_1, $e, $board_5)), $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, false, $state->Undone);
                                case 18:
                                    $board_11 = $matchValue[0]->Item;
                                    $p_3 = $matchValue[1]->Item2->Item;
                                    $playerid_2 = $matchValue[1]->Item1;
                                    return new UndoableBoard(new Board_Board(BoardModule_bribed($playerid_2, $p_3, $board_11)), $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, false, $state->Undone);
                                case 19:
                                    $board_12 = $matchValue[0]->Item;
                                    $e_2 = $matchValue[1]->Item2;
                                    $playerid_3 = $matchValue[1]->Item1;
                                    $newPlayer = Player_evolve($board_12->Players->get_Item($playerid_3), $e_2);
                                    $newTable = Table_eliminate($playerid_3, $board_12->Table);
                                    return new UndoableBoard(new Board_Board(new PlayingBoard(\Map\add($playerid_3, $newPlayer, $board_12->Players), $newTable, $board_12->DrawPile, $board_12->DiscardPile, $board_12->Barns, $board_12->HayBales, $board_12->Goal, $board_12->UseGameOver, $board_12->History)), $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, false, $state->Undone);
                                case 21:
                                    $board_13 = $matchValue[0]->Item;
                                    $e_3 = $matchValue[1]->Item2;
                                    $playerid_4 = $matchValue[1]->Item1;
                                    $currentPlayer = $board_13->Table->get_Player();
                                    $newPlayer_1 = Player_evolve($board_13->Players->get_Item($playerid_4), $e_3);
                                    $newTable_1 = Table_eliminate($playerid_4, $board_13->Table);
                                    $players_1 = \Map\add($playerid_4, $newPlayer_1, $board_13->Players);
                                    return new UndoableBoard(new Board_Board(new PlayingBoard(($playerid_4 === $currentPlayer ? (function ($nextPlayer) use ($newTable_1, $players_1) {                                     return \Map\add($newTable_1->get_Player(), $nextPlayer, $players_1);
 })(Player_startTurn($board_13->Players->get_Item($newTable_1->get_Player()))) : $players_1), $newTable_1, $board_13->DrawPile, $board_13->DiscardPile, $board_13->Barns, $board_13->HayBales, $board_13->Goal, $board_13->UseGameOver, $board_13->History)), $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, false, $state->Undone);
                                case 20:
                                    return new UndoableBoard($state->UndoPoint, $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, true, true);
                                default:
                                    $board_14 = $matchValue[0]->Item;
                                    $e_4 = $matchValue[1]->Item2;
                                    $playerid_5 = $matchValue[1]->Item1;
                                    $player_4 = Player_evolve($board_14->Players->get_Item($playerid_5), $e_4);
                                    switch ($e_4->get_Tag())
                                    {
                                        case 14:
                                            $card = $e_4->Item;
                                            $_target__330 = 0;
                                            break;
                                        case 15:
                                            $card = $e_4->Item;
                                            $_target__330 = 0;
                                            break;
                                        default:
                                            $_target__330 = 1;
                                            break;
                                    }
                                    switch ($_target__330)
                                    {
                                        case 0:
                                            $newDiscardPile = new Cons($card, $board_14->DiscardPile);
                                            break;
                                        case 1:
                                            $newDiscardPile = $board_14->DiscardPile;
                                            break;
                                    }
                                    return new UndoableBoard(new Board_Board(new PlayingBoard(\Map\add($playerid_5, $player_4, $board_14->Players), $board_14->Table, $board_14->DrawPile, $newDiscardPile, $board_14->Barns, $board_14->HayBales, $board_14->Goal, $board_14->UseGameOver, $board_14->History)), $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, false, $state->Undone);
                            }
                        } else {
                            switch ($matchValue[1]->get_Tag())
                            {
                                case 3:
                                    $board_6 = $matchValue[0]->Item;
                                    $e_1 = $matchValue[1]->Item;
                                    $matchValue_4 = $board_6->DrawPile;
                                    switch ($matchValue_4->get_Tag())
                                    {
                                        case 0:
                                            $newDrawPile = new Hand_PrivateHand(($matchValue_4->cards - HandModule_count($e_1->Cards)));
                                            break;
                                        default:
                                            $newDrawPile = new Hand_PublicHand(DrawPile_remove($e_1->Cards, $matchValue_4->cards));
                                            break;
                                    }
                                    $newBoard_1 = new Board_Board(new PlayingBoard(\Map\add($e_1->Player, Player_takeCards($e_1->Cards, $board_6->Players->get_Item($e_1->Player)), $board_6->Players), $board_6->Table, $newDrawPile, $board_6->DiscardPile, $board_6->Barns, $board_6->HayBales, $board_6->Goal, $board_6->UseGameOver, $board_6->History));
                                    if ($state->UndoType->get_Tag() == 0) {
                                        return new UndoableBoard($newBoard_1, $state->UndoPoint, $state->UndoType, true, false, $state->Undone);
                                    } else {
                                        return new UndoableBoard($newBoard_1, $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, $state->AtUndoPoint, $state->Undone);
                                    }
                                case 12:
                                    return new UndoableBoard($state->Board, $state->Board, $state->UndoType, false, true, false);
                                case 8:
                                    $added = $matchValue[1]->added;
                                    $board_7 = $matchValue[0]->Item;
                                    $removed = $matchValue[1]->removed;
                                    return new UndoableBoard(new Board_Board(new PlayingBoard($board_7->Players, $board_7->Table, $board_7->DrawPile, $board_7->DiscardPile, $board_7->Barns, \Set\FSharpSet_op_Addition(\Set\FSharpSet_op_Subtraction($board_7->HayBales, \Set\ofSeq($removed, [ 'Compare' => function ($x_1, $y_1) {                                     return \Util\compare($x_1, $y_1);
 }])), \Set\ofSeq($added, [ 'Compare' => function ($x_2, $y_2) {                                     return \Util\compare($x_2, $y_2);
 }])), $board_7->Goal, $board_7->UseGameOver, $board_7->History)), $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, false, $state->Undone);
                                case 9:
                                    $board_8 = $matchValue[0]->Item;
                                    $p_2 = $matchValue[1]->Item;
                                    return new UndoableBoard(new Board_Board(new PlayingBoard($board_8->Players, $board_8->Table, $board_8->DrawPile, $board_8->DiscardPile, $board_8->Barns, \Set\remove($p_2, $board_8->HayBales), $board_8->Goal, $board_8->UseGameOver, $board_8->History)), $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, false, $state->Undone);
                                case 10:
                                    $board_9 = $matchValue[0]->Item;
                                    $cards_3 = $matchValue[1]->Item;
                                    return new UndoableBoard(new Board_Board(new PlayingBoard($board_9->Players, $board_9->Table, (function ($matchValue_6) use ($cards_3) {                                     switch ($matchValue_6->get_Tag())
                                    {
                                        case 0:
                                            return new Hand_PrivateHand(($matchValue_6->cards + \FSharpList\length($cards_3)));
                                        default:
                                            return new Hand_PublicHand(\FSharpList\append($matchValue_6->cards, $cards_3));
                                    }
 })($board_9->DrawPile), $GLOBALS['NIL'], $board_9->Barns, $board_9->HayBales, $board_9->Goal, $board_9->UseGameOver, $board_9->History)), $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, false, $state->Undone);
                                case 11:
                                    $board_10 = $matchValue[0]->Item;
                                    $cards_4 = $matchValue[1]->Item;
                                    return new UndoableBoard(new Board_Board(new PlayingBoard($board_10->Players, $board_10->Table, ($board_10->DrawPile->get_Tag() == 0 ? $board_10->DrawPile : new Hand_PublicHand($cards_4)), $board_10->DiscardPile, $board_10->Barns, $board_10->HayBales, $board_10->Goal, $board_10->UseGameOver, $board_10->History)), $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, false, $state->Undone);
                                case 2:
                                    $board_15 = $matchValue[0]->Item;
                                    $previousPlayer = $board_15->Table->get_Player();
                                    $nextTable = $board_15->Table->get_Next();
                                    $player_5 = Player_startTurn($board_15->Players->get_Item($nextTable->get_Player()));
                                    $newBoard_10 = new Board_Board(new PlayingBoard(\Map\add($nextTable->get_Player(), $player_5, $board_15->Players), $nextTable, $board_15->DrawPile, $board_15->DiscardPile, $board_15->Barns, $board_15->HayBales, $board_15->Goal, $board_15->UseGameOver, HistoryModule_addPos($previousPlayer, HistoryModule_createPos($board_15), $board_15->History)));
                                    return new UndoableBoard($newBoard_10, $newBoard_10, $state->UndoType, false, true, $state->Undone);
                                default:
                                    return $state;
                            }
                        }
                    }
                }
            }
        }
    } else {
        if ($matchValue[0]->get_Tag() == 2) {
            if ($matchValue[1]->get_Tag() == 0) {
                switch ($matchValue[1]->Item2->get_Tag())
                {
                    case 20:
                        return new UndoableBoard($state->UndoPoint, $state->UndoPoint, $state->UndoType, $state->ShouldShuffle, true, true);
                    default:
                        return $state;
                }
            } else {
                return $state;
            }
        } else {
            switch ($matchValue[1]->get_Tag())
            {
                case 1:
                    $s = $matchValue[1]->Item;
                    $_target__331 = 0;
                    break;
                case 0:
                    $_target__331 = 1;
                    break;
                default:
                    $_target__331 = 1;
                    break;
            }
            switch ($_target__331)
            {
                case 0:
                    $board = new Board_Board(new PlayingBoard(\Map\ofList(\Seq\toList(\Seq\delay(function ($unitVar) use ($s) {                     return \Seq\collect(function ($matchValue_1) {                     return \Seq\singleton([ $matchValue_1[1], new CrazyPlayer_Starting(new Starting($matchValue_1[0], $matchValue_1[3], new Hand_PublicHand($GLOBALS['NIL']), $GLOBALS['BonusModule_empty']))]);
 }, $s->Players);
 }))), Table_start(\Seq\toList(\Seq\delay(function ($unitVar_1) use ($s) {                     return \Seq\collect(function ($matchValue_2) {                     return \Seq\singleton([ $matchValue_2[1], $matchValue_2[2]]);
 }, $s->Players);
 }))), new Hand_PublicHand($s->DrawPile), $GLOBALS['NIL'], BarnsModule_init($s->Barns), \Set\_empty([ 'Compare' => function ($x, $y) {                     return \Util\compare($x, $y);
 }]), $s->Goal, $s->UseGameOver, $GLOBALS['HistoryModule_empty']));
                    return new UndoableBoard($board, $board, $s->Undo, false, false, false);
                case 1:
                    return $state;
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
                case 20:
                case 21:
            }
        }
    }
}

#233
function BoardModule_cont($f, $board, $events) {
    $matchValue = $board->Board;
    if ($matchValue->get_Tag() == 1) {
        $newEvents = $f($matchValue->Item, $events);
        return [ \FSharpList\fold(function ($state, $event) {         return BoardModule_evolve($state, $event);
 }, $board, $newEvents), \FSharpList\append($events, $newEvents)];
    } else {
        return [ $board, $events];
    }
}

#234
$GLOBALS['BoardModule_Configurations_P2_classicpos'] = [ Parcel_op_Addition_ZF6EFE4B($GLOBALS['ParcelModule_center'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_N'])), Parcel_op_Addition_ZF6EFE4B($GLOBALS['ParcelModule_center'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_S']))];

#235
$GLOBALS['BoardModule_Configurations_P2_classic'] = [ $GLOBALS['BoardModule_Configurations_P2_classicpos'], BarnsModule_create(new Cons($GLOBALS['AxeModule_zero'], new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_N']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_S']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_NW']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_SW']), new Cons($GLOBALS['AxeModule_W2'], new Cons($GLOBALS['AxeModule_E2'], new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_N'], $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_N'], $GLOBALS['AxeModule_NW']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_S'], $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_S'], $GLOBALS['AxeModule_SW']), $GLOBALS['NIL']))))))))))))))];

#236
$GLOBALS['BoardModule_Configurations_P2_snake'] = [ $GLOBALS['BoardModule_Configurations_P2_classicpos'], BarnsModule_create(new Cons($GLOBALS['AxeModule_zero'], new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_N']), $GLOBALS['AxeModule_NW']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_N'], $GLOBALS['AxeModule_NE']), new Cons($GLOBALS['AxeModule_NE'], new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NW']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_NW']), new Cons($GLOBALS['AxeModule_SW'], new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_S'], $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_S']), $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_SE']), $GLOBALS['NIL']))))))))))))))];

#237
$GLOBALS['BoardModule_Configurations_P2_star'] = [ $GLOBALS['BoardModule_Configurations_P2_classicpos'], BarnsModule_create(new Cons($GLOBALS['AxeModule_zero'], new Cons($GLOBALS['AxeModule_N'], new Cons($GLOBALS['AxeModule_S'], new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_N']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_S']), new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NW']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_NW']), new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_SE']), $GLOBALS['NIL']))))))))))))))];

#238
$GLOBALS['BoardModule_Configurations_P3_classicpos'] = [ Parcel_op_Addition_ZF6EFE4B($GLOBALS['ParcelModule_center'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_N'])), Parcel_op_Addition_ZF6EFE4B($GLOBALS['ParcelModule_center'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_SW'])), Parcel_op_Addition_ZF6EFE4B($GLOBALS['ParcelModule_center'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_SE']))];

#239
$GLOBALS['BoardModule_Configurations_P3_classic'] = [ $GLOBALS['BoardModule_Configurations_P3_classicpos'], BarnsModule_create(new Cons($GLOBALS['AxeModule_zero'], new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_N']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_S']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_NW']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_SW']), new Cons($GLOBALS['AxeModule_W2'], new Cons($GLOBALS['AxeModule_E2'], new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_N'], $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_N'], $GLOBALS['AxeModule_NW']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_S'], $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_S'], $GLOBALS['AxeModule_SW']), $GLOBALS['NIL']))))))))))))))];

#240
$GLOBALS['BoardModule_Configurations_P3_galaxy'] = [ $GLOBALS['BoardModule_Configurations_P3_classicpos'], BarnsModule_create(new Cons($GLOBALS['AxeModule_zero'], new Cons($GLOBALS['AxeModule_N'], new Cons($GLOBALS['AxeModule_SW'], new Cons($GLOBALS['AxeModule_SE'], new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_S']), new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_S']), $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_S'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_SE'])), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_W2'], $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_E2'], $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NW']), new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NW']), $GLOBALS['AxeModule_N']), new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_N']), $GLOBALS['AxeModule_NE']), $GLOBALS['NIL']))))))))))))))];

#241
$GLOBALS['BoardModule_Configurations_P3_famine'] = [ $GLOBALS['BoardModule_Configurations_P3_classicpos'], BarnsModule_create(new Cons($GLOBALS['AxeModule_NW'], new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NW']), $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NW']), $GLOBALS['AxeModule_N']), new Cons($GLOBALS['AxeModule_NE'], new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NE']), $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NE']), $GLOBALS['AxeModule_N']), new Cons($GLOBALS['AxeModule_S'], new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_S']), $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_S']), $GLOBALS['AxeModule_SW']), $GLOBALS['NIL']))))))))))];

#242
$GLOBALS['BoardModule_Configurations_P3_star'] = [ $GLOBALS['BoardModule_Configurations_P3_classicpos'], BarnsModule_create(new Cons($GLOBALS['AxeModule_zero'], new Cons(Axe_op_Multiply_Z425F7B5E(1, $GLOBALS['AxeModule_N']), new Cons(Axe_op_Multiply_Z425F7B5E(-1, $GLOBALS['AxeModule_N']), new Cons(Axe_op_Multiply_Z425F7B5E(-2, $GLOBALS['AxeModule_N']), new Cons(Axe_op_Multiply_Z425F7B5E(-3, $GLOBALS['AxeModule_N']), new Cons($GLOBALS['AxeModule_SE'], new Cons(Axe_op_Multiply_Z425F7B5E(-1, $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Multiply_Z425F7B5E(-2, $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Multiply_Z425F7B5E(-3, $GLOBALS['AxeModule_SE']), new Cons($GLOBALS['AxeModule_SW'], new Cons(Axe_op_Multiply_Z425F7B5E(-1, $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Multiply_Z425F7B5E(-2, $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Multiply_Z425F7B5E(-3, $GLOBALS['AxeModule_SW']), $GLOBALS['NIL']))))))))))))))];

#243
$GLOBALS['BoardModule_Configurations_P4_classicpos'] = [ Parcel_op_Addition_ZF6EFE4B(Parcel_op_Addition_ZF6EFE4B($GLOBALS['ParcelModule_center'], $GLOBALS['AxeModule_N']), $GLOBALS['AxeModule_NE']), Parcel_op_Addition_ZF6EFE4B($GLOBALS['ParcelModule_center'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NW'])), Parcel_op_Addition_ZF6EFE4B(Parcel_op_Addition_ZF6EFE4B($GLOBALS['ParcelModule_center'], $GLOBALS['AxeModule_SW']), $GLOBALS['AxeModule_S']), Parcel_op_Addition_ZF6EFE4B($GLOBALS['ParcelModule_center'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_SE']))];

#244
$GLOBALS['BoardModule_Configurations_P4_classic'] = [ $GLOBALS['BoardModule_Configurations_P4_classicpos'], BarnsModule_create(new Cons($GLOBALS['AxeModule_zero'], new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_N'], $GLOBALS['AxeModule_NW']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_S'], $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_N']), $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Addition_2BE35040(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_S']), $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_E2'], $GLOBALS['AxeModule_SE']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_W2'], $GLOBALS['AxeModule_NW']), $GLOBALS['NIL']))))))))))];

#245
$GLOBALS['BoardModule_Configurations_P4_xwing'] = [ $GLOBALS['BoardModule_Configurations_P4_classicpos'], BarnsModule_create(new Cons($GLOBALS['AxeModule_zero'], new Cons($GLOBALS['AxeModule_NW'], new Cons($GLOBALS['AxeModule_SW'], new Cons($GLOBALS['AxeModule_NE'], new Cons($GLOBALS['AxeModule_SE'], new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_S']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_N']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_S'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_SW'])), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_S'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_SE'])), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_N'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NW'])), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_N'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NE'])), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_W2'], $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_E2'], $GLOBALS['AxeModule_NE']), $GLOBALS['NIL']))))))))))))))];

#246
$GLOBALS['BoardModule_Configurations_P4_windmill'] = [ $GLOBALS['BoardModule_Configurations_P4_classicpos'], BarnsModule_create(new Cons($GLOBALS['AxeModule_zero'], new Cons($GLOBALS['AxeModule_N'], new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_N']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_N']), new Cons($GLOBALS['AxeModule_S'], new Cons(Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_S']), new Cons(Axe_op_Multiply_Z425F7B5E(3, $GLOBALS['AxeModule_S']), new Cons($GLOBALS['AxeModule_NW'], new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_NW'], $GLOBALS['AxeModule_SW']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_NW'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_SW'])), new Cons($GLOBALS['AxeModule_SE'], new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_SE'], $GLOBALS['AxeModule_NE']), new Cons(Axe_op_Addition_2BE35040($GLOBALS['AxeModule_SE'], Axe_op_Multiply_Z425F7B5E(2, $GLOBALS['AxeModule_NE'])), $GLOBALS['NIL']))))))))))))))];

#247
function BoardModule_shufflePlayers($players, $parcels) {
    $rand = [ ];
    return \FSharpList\map2(function ($tupledArg, $p) {     return [ $tupledArg[0], $tupledArg[1], $tupledArg[2], $p];
 }, $players, \FSharpList\sortBy(function ($_arg1) {     return \Util\randomNext(0, 2147483647);
 }, $parcels, [ 'Compare' => function ($x, $y) {     return \Util\comparePrimitives($x, $y);
 }]));
}

#248
function BoardModule_decide($cmd, $state) {
    $matchValue = [ $state->Board, $cmd];
    if ($matchValue[0]->get_Tag() == 0) {
        switch ($matchValue[1]->get_Tag())
        {
            case 1:
                $cmd_1 = $matchValue[1]->Item;
                $matchValue_1 = $cmd_1->Players;
                if ($matchValue_1 instanceof Cons) {
                    if ($matchValue_1->next instanceof Cons) {
                        if ($matchValue_1->next->next instanceof Cons) {
                            if ($matchValue_1->next->next->next instanceof Cons) {
                                if ($matchValue_1->next->next->next->next instanceof Nil) {
                                    $patternInput = [ BoardModule_shufflePlayers($cmd_1->Players, new Cons($GLOBALS['BoardModule_Configurations_P4_windmill'][0][0], new Cons($GLOBALS['BoardModule_Configurations_P4_windmill'][0][1], new Cons($GLOBALS['BoardModule_Configurations_P4_windmill'][0][2], new Cons($GLOBALS['BoardModule_Configurations_P4_windmill'][0][3], $GLOBALS['NIL']))))), $GLOBALS['BoardModule_Configurations_P4_windmill'][1]];
                                } else {
                                    if (\FSharpList\length($cmd_1->Players) < 2) {
                                        throw new Error('Too few players');
                                    } else {
                                        throw new Error('Too many players');
                                    }
                                }
                            } else {
                                $patternInput = [ BoardModule_shufflePlayers($cmd_1->Players, new Cons($GLOBALS['BoardModule_Configurations_P3_star'][0][0], new Cons($GLOBALS['BoardModule_Configurations_P3_star'][0][1], new Cons($GLOBALS['BoardModule_Configurations_P3_star'][0][2], $GLOBALS['NIL'])))), $GLOBALS['BoardModule_Configurations_P3_star'][1]];
                            }
                        } else {
                            $patternInput = [ BoardModule_shufflePlayers($cmd_1->Players, new Cons($GLOBALS['BoardModule_Configurations_P2_star'][0][0], new Cons($GLOBALS['BoardModule_Configurations_P2_star'][0][1], $GLOBALS['NIL']))), $GLOBALS['BoardModule_Configurations_P2_star'][1]];
                        }
                    } else {
                        if (\FSharpList\length($cmd_1->Players) < 2) {
                            throw new Error('Too few players');
                        } else {
                            throw new Error('Too many players');
                        }
                    }
                } else {
                    if (\FSharpList\length($cmd_1->Players) < 2) {
                        throw new Error('Too few players');
                    } else {
                        throw new Error('Too many players');
                    }
                }
                return new Cons(new BoardEvent_Started(new BoardStarted($patternInput[0], DrawPile_shuffle($cmd_1->UseGameOver, $GLOBALS['DrawPile_cards']), $patternInput[1], $cmd_1->Goal, $cmd_1->Undo, $cmd_1->UseGameOver)), $GLOBALS['NIL']);
            default:
                return $GLOBALS['NIL'];
        }
    } else {
        if ($matchValue[0]->get_Tag() == 1) {
            if ($matchValue[1]->get_Tag() == 0) {
                switch ($matchValue[1]->Item2->get_Tag())
                {
                    case 5:
                        $board = $matchValue[0]->Item;
                        $playerId = $matchValue[1]->Item1;
                        if ($board->Table->get_Player() === $playerId) {
                            $player = $board->Players->get_Item($playerId);
                            if ($player->get_Tag() == 1) {
                                if (!(Player_canMove($playerId, $state->Board) ? true : HandModule_shouldDiscard($player->Item->Hand))) {
                                    $p_1 = $player->Item;
                                    $matchValue_2 = HistoryModule_repetitions($playerId, HistoryModule_createPos($board), $board->History);
                                    if ($matchValue_2 >= 2) {
                                        return new Cons(new BoardEvent_GameEndedByRepetition(), $GLOBALS['NIL']);
                                    } else {
                                        if ($matchValue_2 === 1) {
                                            return BoardModule_next($state->ShouldShuffle, $state->Undone, true, $board);
                                        } else {
                                            return BoardModule_next($state->ShouldShuffle, $state->Undone, false, $board);
                                        }
                                    }
                                } else {
                                    return $GLOBALS['NIL'];
                                }
                            } else {
                                return $GLOBALS['NIL'];
                            }
                        } else {
                            return $GLOBALS['NIL'];
                        }
                    case 6:
                        $board_1 = $matchValue[0]->Item;
                        $playerId_1 = $matchValue[1]->Item1;
                        if (($board_1->Table->get_Player() === $playerId_1 ? !\Util\equals($state->UndoType, new \Shared\UndoType_NoUndo()) : false) ? !$state->AtUndoPoint : false) {
                            return new Cons(new BoardEvent_Played($playerId_1, new Event_Undone()), $GLOBALS['NIL']);
                        } else {
                            return $GLOBALS['NIL'];
                        }
                    case 4:
                        $board_2 = $matchValue[0]->Item;
                        $card = $matchValue[1]->Item2->Item;
                        $cmd_2 = $matchValue[1]->Item2;
                        $playerId_2 = $matchValue[1]->Item1;
                        if ($board_2->Table->get_Player() === $playerId_2) {
                            $player_1 = $board_2->Players->get_Item($playerId_2);
                            $events = Player_decide(Player_otherPlayers($playerId_2, $board_2), $board_2->Barns, $board_2->HayBales, function ($unitVar0) use ($board_2) {                             return BoardModule_bribeParcels($board_2);
 }, $cmd_2, $player_1);
                            return \Seq\toList(\Seq\delay(function ($unitVar) use ($events, $playerId_2) {                             return \Seq\map(function ($e) use ($playerId_2) {                             return new BoardEvent_Played($playerId_2, $e);
 }, $events);
 }));
                        } else {
                            return $GLOBALS['NIL'];
                        }
                    default:
                        $board_3 = $matchValue[0]->Item;
                        $cmd_3 = $matchValue[1]->Item2;
                        $playerid = $matchValue[1]->Item1;
                        $player_2 = $board_3->Players->get_Item($playerid);
                        $others_1 = Player_otherPlayers($playerid, $board_3);
                        if ($playerid === $board_3->Table->get_Player()) {
                            return (function ($tupledArg_7) use ($playerid, $state) {                             return BoardModule_cont(function ($board_16, $es_1) use ($playerid, $state) { 
                                $matchValue_9 = BoardModule_tryFindWinner($board_16);
                                switch ($matchValue_9->get_Tag())
                                {
                                    case 1:
                                        $leads = $matchValue_9->Item;
                                        $matchValue_10 = \FSharpList\tryFind(function ($_arg3_1) {                                         if ($_arg3_1->get_Tag() == 0) {
                                            switch ($_arg3_1->Item2->get_Tag())
                                            {
                                                case 7:
                                                    return true;
                                                default:
                                                    return false;
                                            }
                                        } else {
                                            return false;
                                        }
 }, $es_1);
                                        if (!is_null($matchValue_10)) {
                                            if ($matchValue_10->get_Tag() == 0) {
                                                switch ($matchValue_10->Item2->get_Tag())
                                                {
                                                    case 7:
                                                        $e_4 = $matchValue_10->Item2->Item;
                                                        $player_7 = $matchValue_10->Item1;
                                                        $cardsToTake_1 = \FSharpList\length($e_4->FreeBarns) + 2 * \FSharpList\length($e_4->OccupiedBarns);
                                                        return \Seq\toList(\Seq\delay(function ($unitVar_9) use ($board_16, $cardsToTake_1, $leads, $playerid, $state) {                                                         if ($cardsToTake_1 > 0) {
                                                            $matchValue_11 = $board_16->DrawPile;
                                                            switch ($matchValue_11->get_Tag())
                                                            {
                                                                case 0:
                                                                    return \Seq\_empty();
                                                                default:
                                                                    $cardsDrawn = DrawPile_take($cardsToTake_1, $matchValue_11->cards);
                                                                    return \Seq\append(\Seq\singleton(new BoardEvent_PlayerDrewCards(new PlayerDrewCards($playerid, new Hand_PublicHand($cardsDrawn)))), \Seq\delay(function ($unitVar_10) use ($cardsDrawn, $leads, $playerid, $state) {                                                                     if (\FSharpList\contains(new Card_GameOver(), $cardsDrawn, [ 'Equals' => function ($x, $y) {                                                                     return \Util\equals($x, $y);
 }, 'GetHashCode' => function ($x) {                                                                     return \Util\safeHash($x);
 }])) {
                                                                        return \Seq\append(\Seq\singleton(new BoardEvent_Played($playerid, new Event_CardPlayed(new PlayCard_PlayGameOver()))), \Seq\delay(function ($unitVar_11) use ($leads) {                                                                         if ($leads instanceof Cons) {
                                                                            if ($leads->next instanceof Nil) {
                                                                                $win_1 = $leads->value;
                                                                                return \Seq\singleton(new BoardEvent_GameWon($win_1));
                                                                            } else {
                                                                                return \Seq\singleton(new BoardEvent_GameEnded($leads));
                                                                            }
                                                                        } else {
                                                                            return \Seq\singleton(new BoardEvent_GameEnded($leads));
                                                                        }
 }));
                                                                    } else {
                                                                        if (\Util\equals($state->UndoType, new \Shared\UndoType_DontUndoCards())) {
                                                                            return \Seq\singleton(new BoardEvent_UndoCheckPointed());
                                                                        } else {
                                                                            return \Seq\_empty();
                                                                        }
                                                                    }
 }));
                                                            }
                                                        } else {
                                                            return \Seq\_empty();
                                                        }
 }));
                                                    default:
                                                        $player_8 = $board_16->Players->get_Item($playerid);
                                                        if ($playerid !== $board_16->Table->get_Player()) {
                                                            $nextPlayerId = $board_16->Table->get_Player();
                                                            $nextPlayer = $board_16->Players->get_Item($nextPlayerId);
                                                            return \Seq\toList(\Seq\delay(function ($unitVar_12) use ($nextPlayer, $nextPlayerId) {                                                             return \Seq\append(\FSharpList\map(function ($c_1) use ($nextPlayerId) {                                                             return new BoardEvent_Played($nextPlayerId, new Event_BonusDiscarded($c_1));
 }, BonusModule_startTurn(Player_bonus($nextPlayer))), \Seq\delay(function ($unitVar_13) {                                                             return \Seq\singleton(new BoardEvent_UndoCheckPointed());
 }));
 }));
                                                        } else {
                                                            return $GLOBALS['NIL'];
                                                        }
                                                }
                                            } else {
                                                $player_8 = $board_16->Players->get_Item($playerid);
                                                if ($playerid !== $board_16->Table->get_Player()) {
                                                    $nextPlayerId = $board_16->Table->get_Player();
                                                    $nextPlayer = $board_16->Players->get_Item($nextPlayerId);
                                                    return \Seq\toList(\Seq\delay(function ($unitVar_12) use ($nextPlayer, $nextPlayerId) {                                                     return \Seq\append(\FSharpList\map(function ($c_1) use ($nextPlayerId) {                                                     return new BoardEvent_Played($nextPlayerId, new Event_BonusDiscarded($c_1));
 }, BonusModule_startTurn(Player_bonus($nextPlayer))), \Seq\delay(function ($unitVar_13) {                                                     return \Seq\singleton(new BoardEvent_UndoCheckPointed());
 }));
 }));
                                                } else {
                                                    return $GLOBALS['NIL'];
                                                }
                                            }
                                        } else {
                                            $player_8 = $board_16->Players->get_Item($playerid);
                                            if ($playerid !== $board_16->Table->get_Player()) {
                                                $nextPlayerId = $board_16->Table->get_Player();
                                                $nextPlayer = $board_16->Players->get_Item($nextPlayerId);
                                                return \Seq\toList(\Seq\delay(function ($unitVar_12) use ($nextPlayer, $nextPlayerId) {                                                 return \Seq\append(\FSharpList\map(function ($c_1) use ($nextPlayerId) {                                                 return new BoardEvent_Played($nextPlayerId, new Event_BonusDiscarded($c_1));
 }, BonusModule_startTurn(Player_bonus($nextPlayer))), \Seq\delay(function ($unitVar_13) {                                                 return \Seq\singleton(new BoardEvent_UndoCheckPointed());
 }));
 }));
                                            } else {
                                                return $GLOBALS['NIL'];
                                            }
                                        }
                                    default:
                                        $winners = $matchValue_9->Item;
                                        if ($winners instanceof Cons) {
                                            if ($winners->next instanceof Nil) {
                                                $win = $winners->value;
                                                return new Cons(new BoardEvent_GameWon($win), $GLOBALS['NIL']);
                                            } else {
                                                return new Cons(new BoardEvent_GameEnded($winners), $GLOBALS['NIL']);
                                            }
                                        } else {
                                            return new Cons(new BoardEvent_GameEnded($winners), $GLOBALS['NIL']);
                                        }
                                }
                            }, $tupledArg_7[0], $tupledArg_7[1]);
 })((function ($tupledArg_6) {                             return BoardModule_cont(function ($board_14, $es) { 
                                $matchValue_8 = \FSharpList\tryFind(function ($_arg2_1) {                                 if ($_arg2_1->get_Tag() == 0) {
                                    switch ($_arg2_1->Item2->get_Tag())
                                    {
                                        case 7:
                                            return true;
                                        default:
                                            return false;
                                    }
                                } else {
                                    return false;
                                }
 }, $es);
                                if (!is_null($matchValue_8)) {
                                    if ($matchValue_8->get_Tag() == 0) {
                                        switch ($matchValue_8->Item2->get_Tag())
                                        {
                                            case 7:
                                                $e_3 = $matchValue_8->Item2->Item;
                                                if (\FSharpList\length($e_3->FreeBarns) + 2 * \FSharpList\length($e_3->OccupiedBarns) > HandModule_count($board_14->DrawPile)) {
                                                    return new Cons(new BoardEvent_DiscardPileShuffled(DrawPile_shuffle($board_14->UseGameOver, $board_14->DiscardPile)), $GLOBALS['NIL']);
                                                } else {
                                                    return $GLOBALS['NIL'];
                                                }
                                            default:
                                                return $GLOBALS['NIL'];
                                        }
                                    } else {
                                        return $GLOBALS['NIL'];
                                    }
                                } else {
                                    return $GLOBALS['NIL'];
                                }
                            }, $tupledArg_6[0], $tupledArg_6[1]);
 })((function ($tupledArg_5) {                             return BoardModule_cont(function ($board_12, $_arg5) { 
                                $remainingPlayers = \Seq\toList(\Seq\choose(function ($tupledArg_4) {                                 if ($tupledArg_4[1]->get_Tag() == 2) {
                                    return NULL;
                                } else {
                                    return $tupledArg_4[0];
                                }
 }, \Map\toSeq($board_12->Players)));
                                if ($remainingPlayers instanceof Cons) {
                                    if ($remainingPlayers->next instanceof Nil) {
                                        $winner = $remainingPlayers->value;
                                        return new Cons(new BoardEvent_GameWon($winner), $GLOBALS['NIL']);
                                    } else {
                                        return $GLOBALS['NIL'];
                                    }
                                } else {
                                    return $GLOBALS['NIL'];
                                }
                            }, $tupledArg_5[0], $tupledArg_5[1]);
 })((function ($tupledArg_3) use ($playerid) {                             return BoardModule_cont(function ($board_10, $_arg4) use ($playerid) {                             return \Seq\toList(\Seq\delay(function ($unitVar_7) use ($board_10, $playerid) {                             return \Seq\collect(function ($matchValue_7) use ($playerid) { 
                                $pid_1 = $matchValue_7[0];
                                $p_9 = $matchValue_7[1];
                                if ($p_9->get_Tag() == 1) {
                                    switch ($p_9->Item->Power->get_Tag())
                                    {
                                        case 0:
                                            $player_6 = $p_9->Item;
                                            if (!CrossroadModule_isInField($player_6->Field, FenceModule_start($player_6->Tractor, $player_6->Fence))) {
                                                return \Seq\append(\Seq\singleton(new BoardEvent_Played($playerid, new Event_CutFence(new CutFence($pid_1)))), \Seq\delay(function ($unitVar_8) use ($pid_1, $player_6) {                                                 if (CrossroadModule_isInField($player_6->Field, $player_6->Tractor)) {
                                                    return \Seq\singleton(new BoardEvent_Played($pid_1, new Event_PoweredUp()));
                                                } else {
                                                    return \Seq\_empty();
                                                }
 }));
                                            } else {
                                                return \Seq\_empty();
                                            }
                                        default:
                                            $player_5 = $p_9->Item;
                                            if (CrossroadModule_isInField($player_5->Field, $player_5->Tractor)) {
                                                return \Seq\singleton(new BoardEvent_Played($pid_1, new Event_PoweredUp()));
                                            } else {
                                                return \Seq\_empty();
                                            }
                                    }
                                } else {
                                    return \Seq\_empty();
                                }
                            }, \Map\toSeq($board_10->Players));
 }));
 }, $tupledArg_3[0], $tupledArg_3[1]);
 })((function ($tupledArg_2) {                             return BoardModule_cont(function ($board_8, $_arg3) {                             return \Seq\toList(\Seq\delay(function ($unitVar_5) use ($board_8) {                             return \Seq\collect(function ($matchValue_6) { 
                                $activePatternResult1591 = $matchValue_6;
                                $p_8 = $activePatternResult1591[1];
                                if (FieldModule_isEmpty(Player_field($p_8)) ? !Player_isKo($p_8) : false) {
                                    return \Seq\append(\Seq\singleton(new BoardEvent_Played($activePatternResult1591[0], new Event_Eliminated())), \Seq\delay(function ($unitVar_6) {                                     return \Seq\singleton(new BoardEvent_UndoCheckPointed());
 }));
                                } else {
                                    return \Seq\_empty();
                                }
                            }, $board_8->Players);
 }));
 }, $tupledArg_2[0], $tupledArg_2[1]);
 })((function ($tupledArg_1) use ($playerid) {                             return BoardModule_cont(function ($board_6, $_arg2) use ($playerid) { 
                                $player_3 = $board_6->Players->get_Item($playerid);
                                if ($player_3->get_Tag() == 1) {
                                    $player_4 = $player_3->Item;
                                    $matchValue_3 = Player_fullAnnexation($player_4->Field, $player_4->Fence, $player_4->Tractor);
                                    if (is_null($matchValue_3)) {
                                        return $GLOBALS['NIL'];
                                    } else {
                                        $surrounded = $matchValue_3[0];
                                        $newLength = $matchValue_3[1];
                                        if (FieldModule_isEmpty($surrounded)) {
                                            return new Cons(new BoardEvent_Played($playerid, new Event_FenceReduced(new FenceReduced($newLength))), $GLOBALS['NIL']);
                                        } else {
                                            $annexed = Field_op_Subtraction_Z24735800($surrounded, FieldModule_unionMany(\Seq\toList(\Seq\delay(function ($unitVar_3) use ($board_6, $playerid) {                                             return \Seq\collect(function ($matchValue_4) use ($playerid) { 
                                                $p_2 = $matchValue_4[1];
                                                if ($p_2->get_Tag() == 1) {
                                                    if ($matchValue_4[0] !== $playerid ? $p_2->Item->Bonus->Watched : false) {
                                                        $p_4 = $p_2->Item;
                                                        return \Seq\singleton($p_4->Field);
                                                    } else {
                                                        return \Seq\_empty();
                                                    }
                                                } else {
                                                    return \Seq\_empty();
                                                }
                                            }, \Map\toSeq($board_6->Players));
 }))));
                                            $lostFields = \Seq\toList(\Seq\delay(function ($unitVar_4) use ($annexed, $board_6, $playerid) {                                             return \Seq\collect(function ($matchValue_5) use ($annexed, $playerid) { 
                                                $p_5 = $matchValue_5[1];
                                                $id_1 = $matchValue_5[0];
                                                if ($p_5->get_Tag() == 1) {
                                                    if ($id_1 !== $playerid) {
                                                        $p_7 = $p_5->Item;
                                                        $lost = FieldModule_intersect($annexed, $p_7->Field);
                                                        if (!FieldModule_isEmpty($lost)) {
                                                            return \Seq\singleton([ $id_1, FieldModule_parcels($lost)]);
                                                        } else {
                                                            return \Seq\_empty();
                                                        }
                                                    } else {
                                                        return \Seq\_empty();
                                                    }
                                                } else {
                                                    return \Seq\_empty();
                                                }
                                            }, \Map\toSeq($board_6->Players));
 }));
                                            $annexedBarns = BarnsModule_intersectWith($annexed, $board_6->Barns);
                                            return new Cons(new BoardEvent_Played($playerid, new Event_Annexed(new Annexed(FieldModule_parcels($annexed), $lostFields, FieldModule_parcels($annexedBarns->Free), FieldModule_parcels($annexedBarns->Occupied), $newLength))), $GLOBALS['NIL']);
                                        }
                                    }
                                } else {
                                    return $GLOBALS['NIL'];
                                }
                            }, $tupledArg_1[0], $tupledArg_1[1]);
 })((function ($tupledArg) use ($cmd_3, $others_1, $player_2, $playerid, $state) {                             return BoardModule_cont(function ($board_4, $_arg1) use ($cmd_3, $others_1, $player_2, $playerid, $state) {                             if ($cmd_3->get_Tag() == 2 ? ((function ($m) use ($playerid, $state) {                             return !\FSharpList\exists(function ($_arg1_1) use ($m) {                             if ($_arg1_1->get_Tag() == 0) {
                                if (\Util\equals($_arg1_1->Item1, $m->Direction)) {
                                    return \Util\equals($_arg1_1->Item2, $m->Destination);
                                } else {
                                    return false;
                                }
                            } else {
                                return false;
                            }
 }, Player_possibleMoves($playerid, $state->Board));
 })($cmd_3->Item) ? (function ($m_1) {                             return false;
 })($cmd_3->Item) : true) : true) {
                                $events_1 = Player_decide($others_1, $board_4->Barns, $board_4->HayBales, function ($unitVar0_1) use ($board_4) {                                 return BoardModule_bribeParcels($board_4);
 }, $cmd_3, $player_2);
                                return \Seq\toList(\Seq\delay(function ($unitVar_1) use ($events_1, $playerid) {                                 return \Seq\append(\Seq\map(function ($e_1) use ($playerid) {                                 return new BoardEvent_Played($playerid, $e_1);
 }, $events_1), \Seq\delay(function ($unitVar_2) use ($events_1) {                                 return \Seq\collect(function ($e_2) {                                 if ($e_2->get_Tag() == 10) {
                                    switch ($e_2->Item->get_Tag())
                                    {
                                        case 1:
                                            $victim = $e_2->Item->victim;
                                            return \Seq\singleton(new BoardEvent_Played($victim, new Event_Rutted()));
                                        case 2:
                                            $added = $e_2->Item->path;
                                            $removed = $e_2->Item->moved;
                                            return \Seq\singleton(new BoardEvent_HayBalesPlaced($added, $removed));
                                        case 3:
                                            $bale = $e_2->Item->path;
                                            return \Seq\singleton(new BoardEvent_HayBaleDynamited($bale));
                                        default:
                                            return \Seq\_empty();
                                    }
                                } else {
                                    return \Seq\_empty();
                                }
 }, $events_1);
 }));
 }));
                            } else {
                                return $GLOBALS['NIL'];
                            }
 }, $tupledArg[0], $tupledArg[1]);
 })([ $state, $GLOBALS['NIL']])))))))[1];
                        } else {
                            if ($cmd_3->get_Tag() == 7) {
                                return (function ($tupledArg_11) {                                 return BoardModule_cont(function ($board_20, $_arg8) { 
                                    $remainingPlayers_1 = \Seq\toList(\Seq\choose(function ($tupledArg_10) {                                     if ($tupledArg_10[1]->get_Tag() == 2) {
                                        return NULL;
                                    } else {
                                        return $tupledArg_10[0];
                                    }
 }, \Map\toSeq($board_20->Players)));
                                    if ($remainingPlayers_1 instanceof Cons) {
                                        if ($remainingPlayers_1->next instanceof Nil) {
                                            $winner_1 = $remainingPlayers_1->value;
                                            return new Cons(new BoardEvent_GameWon($winner_1), $GLOBALS['NIL']);
                                        } else {
                                            return $GLOBALS['NIL'];
                                        }
                                    } else {
                                        return $GLOBALS['NIL'];
                                    }
                                }, $tupledArg_11[0], $tupledArg_11[1]);
 })((function ($tupledArg_9) use ($cmd_3, $others_1, $player_2, $playerid) {                                 return BoardModule_cont(function ($board_18, $_arg7) use ($cmd_3, $others_1, $player_2, $playerid) { 
                                    $events_9 = Player_decide($others_1, $board_18->Barns, $board_18->HayBales, function ($unitVar0_2) use ($board_18) {                                     return BoardModule_bribeParcels($board_18);
 }, $cmd_3, $player_2);
                                    return \Seq\toList(\Seq\delay(function ($unitVar_14) use ($events_9, $playerid) {                                     return \Seq\map(function ($e_5) use ($playerid) {                                     return new BoardEvent_Played($playerid, $e_5);
 }, $events_9);
 }));
                                }, $tupledArg_9[0], $tupledArg_9[1]);
 })([ $state, $GLOBALS['NIL']]))[1];
                            } else {
                                return $GLOBALS['NIL'];
                            }
                        }
                }
            } else {
                return $GLOBALS['NIL'];
            }
        } else {
            return $GLOBALS['NIL'];
        }
    }
}

#249
function BoardModule_toState($board) {
    switch ($board->get_Tag())
    {
        case 0:
            return new BoardState([ ], new STable(NULL, NULL, NULL, 0), [ ], NULL, NULL, NULL, NULL, new \Shared\Goal_Common(0), NULL, [ ], NULL, [ ]);
        case 2:
            $winners = $board->Item1;
            $board_2 = $board->Item2;
            return new BoardState(\Seq\toArray(\Seq\map(function ($tupledArg_1) {             return [ $tupledArg_1[0], Player_toState($tupledArg_1[1])];
 }, \Map\toSeq($board_2->Players))), new STable($board_2->Table->Players, $board_2->Table->AllPlayers, \Seq\toArray(\Seq\delay(function ($unitVar_4) use ($board_2) {             return \Seq\collect(function ($matchValue_2) { 
                $activePatternResult1640 = $matchValue_2;
                return \Seq\singleton([ $activePatternResult1640[0], $activePatternResult1640[1]]);
            }, $board_2->Table->Names);
 })), $board_2->Table->Current), \FSharpList\toArray($board_2->DiscardPile), HandModule_count($board_2->DrawPile), \FSharpList\toArray(FieldModule_parcels($board_2->Barns->Free)), \FSharpList\toArray(FieldModule_parcels($board_2->Barns->Occupied)), \Set\toArray($board_2->HayBales), $board_2->Goal, ($winners instanceof Cons ? ($winners->next instanceof Nil ? (function ($winner) {             return $winner;
 })($winners->value) : NULL) : NULL), ($winners instanceof Cons ? ($winners->next instanceof Nil ? [ ] : \FSharpList\toArray($winners)) : [ ]), $board_2->UseGameOver, [ ]);
        default:
            $board_1 = $board->Item;
            return new BoardState(\Seq\toArray(\Seq\map(function ($tupledArg) {             return [ $tupledArg[0], Player_toState($tupledArg[1])];
 }, \Map\toSeq($board_1->Players))), new STable($board_1->Table->Players, $board_1->Table->AllPlayers, \Seq\toArray(\Seq\delay(function ($unitVar) use ($board_1) {             return \Seq\collect(function ($matchValue) { 
                $activePatternResult1629 = $matchValue;
                return \Seq\singleton([ $activePatternResult1629[0], $activePatternResult1629[1]]);
            }, $board_1->Table->Names);
 })), $board_1->Table->Current), \FSharpList\toArray($board_1->DiscardPile), HandModule_count($board_1->DrawPile), \FSharpList\toArray(FieldModule_parcels($board_1->Barns->Free)), \FSharpList\toArray(FieldModule_parcels($board_1->Barns->Occupied)), \Set\toArray($board_1->HayBales), $board_1->Goal, NULL, [ ], $board_1->UseGameOver, \Seq\toArray(\Seq\delay(function ($unitVar_1) use ($board_1) {             return \Seq\collect(function ($matchValue_1) { 
                $activePatternResult1636 = $matchValue_1;
                return \Seq\singleton([ $activePatternResult1636[0], \Seq\toArray(\Seq\delay(function ($unitVar_2) use ($activePatternResult1636) {                 return \Seq\map(function ($boardpos) {                 return \Seq\toArray(\Seq\delay(function ($unitVar_3) use ($boardpos) {                 return \Seq\map(function ($pos) {                 return [ $pos->Player, $pos->TractorPos, $pos->FencePos, FieldModule_parcels($pos->FieldPos)];
 }, $boardpos->Positions);
 }));
 }, $activePatternResult1636[1]);
 }))]);
            }, $board_1->History->PlayersHistory);
 })));
    }
}

#250
function BoardModule_toUndoState($s) {
    return new UndoBoardState(BoardModule_toState($s->Board), BoardModule_toState($s->UndoPoint), (function ($matchValue) {     switch ($matchValue->get_Tag())
    {
        case 1:
            return 'DontUndoCards';
        case 2:
            return 'NoUndo';
        default:
            return 'FullUndo';
    }
 })($s->UndoType), $s->ShouldShuffle, $s->AtUndoPoint, $s->Undone);
}

#251
function BoardModule_ofState($board) {
    $matchValue = $board->SPlayers;
    if (!\FSharpArray\equalsWith(function ($x, $y) {     return \Util\compareArrays($x, $y);
 }, $matchValue, NULL) ? count($matchValue) === 0 : false) {
        return new Board_InitialState();
    } else {
        $state = new PlayingBoard(\Map\ofSeq(\Seq\map(function ($tupledArg) {         return [ $tupledArg[0], Player_ofState($tupledArg[1])];
 }, $board->SPlayers)), new GameTable($board->STable->SPlayers, $board->STable->SAllPlayers, \Map\ofArray($board->STable->SNames), $board->STable->SCurrent), new Hand_PrivateHand(\Option\defaultArg($board->SDrawPile, 0)), \FSharpList\ofArray($board->SDiscardPile), new Barns(FieldModule_ofParcels($board->SFreeBarns), FieldModule_ofParcels($board->SOccupiedBarns)), \Set\ofSeq($board->SHayBales, [ 'Compare' => function ($x_1, $y_1) {         return \Util\compare($x_1, $y_1);
 }]), $board->SGoal, \Option\defaultArg($board->SUseGameOver, false), new History(\Map\ofList(\Seq\toList(\Seq\delay(function ($unitVar) use ($board) {         return \Seq\collect(function ($matchValue_1) {         return \Seq\singleton([ $matchValue_1[0], \Seq\toList(\Seq\delay(function ($unitVar_1) use ($matchValue_1) {         return \Seq\map(function ($h) {         return new BoardPosition(\Set\ofSeq(\Seq\toList(\Seq\delay(function ($unitVar_2) use ($h) {         return \Seq\collect(function ($matchValue_2) {         return \Seq\singleton(new PlayerPosition($matchValue_2[0], $matchValue_2[1], $matchValue_2[2], FieldModule_ofParcels($matchValue_2[3])));
 }, $h);
 })), [ 'Compare' => function ($x_2, $y_2) {         return \Util\compare($x_2, $y_2);
 }]));
 }, $matchValue_1[1]);
 }))]);
 }, $board->SHistory);
 })))));
        $matchValue_3 = [ $board->SWinner, $board->SWinners];
        if ($matchValue_3[0] === NULL) {
            if (\FSharpArray\equalsWith(function ($x_3, $y_3) {             return \Util\comparePrimitives($x_3, $y_3);
 }, $matchValue_3[1], NULL)) {
                return new Board_Board($state);
            } else {
                if ((function ($testExpr) {                 if (!\FSharpArray\equalsWith(function ($x_4, $y_4) {                 return \Util\comparePrimitives($x_4, $y_4);
 }, $testExpr, NULL)) {
                    return count($testExpr) === 0;
                } else {
                    return false;
                }
 })($matchValue_3[1])) {
                    return new Board_Board($state);
                } else {
                    $winners = $matchValue_3[1];
                    return new Board_Won(\FSharpList\ofArray($winners), $state);
                }
            }
        } else {
            if ((function ($testExpr_1) {             if (!\FSharpArray\equalsWith(function ($x_5, $y_5) {             return \Util\comparePrimitives($x_5, $y_5);
 }, $testExpr_1, NULL)) {
                return count($testExpr_1) === 0;
            } else {
                return false;
            }
 })($matchValue_3[1])) {
                $winner = $matchValue_3[0];
                return new Board_Won(new Cons($winner, $GLOBALS['NIL']), $state);
            } else {
                if (\FSharpArray\equalsWith(function ($x_6, $y_6) {                 return \Util\comparePrimitives($x_6, $y_6);
 }, $matchValue_3[1], NULL)) {
                    $winner = $matchValue_3[0];
                    return new Board_Won(new Cons($winner, $GLOBALS['NIL']), $state);
                } else {
                    $winners = $matchValue_3[1];
                    return new Board_Won(\FSharpList\ofArray($winners), $state);
                }
            }
        }
    }
}

#252
function BoardModule_ofUndoState($s) {
    return new UndoableBoard(BoardModule_ofState($s->SBoard), BoardModule_ofState($s->SUndoPoint), (function ($matchValue) {     if ($matchValue === 'NoUndo') {
        return new \Shared\UndoType_NoUndo();
    } else {
        if ($matchValue === 'DontUndoCards') {
            return new \Shared\UndoType_DontUndoCards();
        } else {
            return new \Shared\UndoType_FullUndo();
        }
    }
 })($s->SUndoType), $s->SShouldShuffle, $s->SAtUndoPoint, $s->SUndone);
}

#253
function Client_cardName($_arg1) {
    if ($_arg1->get_Tag() == 1) {
        return 'rut';
    } else {
        if ($_arg1->get_Tag() == 2) {
            switch ($_arg1->power->get_Tag())
            {
                case 1:
                    return 'hay-bale-2';
                default:
                    return 'hay-bale-1';
            }
        } else {
            if ($_arg1->get_Tag() == 3) {
                return 'dynamite';
            } else {
                if ($_arg1->get_Tag() == 4) {
                    return 'high-voltage';
                } else {
                    if ($_arg1->get_Tag() == 5) {
                        return 'watchdog';
                    } else {
                        if ($_arg1->get_Tag() == 6) {
                            return 'helicopter';
                        } else {
                            if ($_arg1->get_Tag() == 7) {
                                return 'bribe';
                            } else {
                                if ($_arg1->get_Tag() == 8) {
                                    return 'gameover';
                                } else {
                                    switch ($_arg1->power->get_Tag())
                                    {
                                        case 1:
                                            return 'nitro-2';
                                        default:
                                            return 'nitro-1';
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

#254
abstract class ServerMsg implements FSharpUnion {
}

#254
class ServerMsg_JoinGame extends ServerMsg implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'JoinGame';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__332 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__332 != 0) {
            return $_cmp__332;
        }        
        $_cmp__333 = $this->Item->CompareTo($other->Item);
        if ($_cmp__333 != 0) {
            return $_cmp__333;
        }        
        return 0;
    }
}

#254
class ServerMsg_Command extends ServerMsg implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Command';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__334 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__334 != 0) {
            return $_cmp__334;
        }        
        $_cmp__335 = $this->Item->CompareTo($other->Item);
        if ($_cmp__335 != 0) {
            return $_cmp__335;
        }        
        return 0;
    }
}

#254
class ServerMsg_SendMessage extends ServerMsg implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'SendMessage';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__336 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__336 != 0) {
            return $_cmp__336;
        }        
        $_cmp__337 = $this->Item->CompareTo($other->Item);
        if ($_cmp__337 != 0) {
            return $_cmp__337;
        }        
        return 0;
    }
}

#255
class ChatEntry implements IComparable {
    public $Text;
    public $Player;
    public $Date;
    function __construct($Text, $Player, $Date) {
        $this->Text = $Text;
        $this->Player = $Player;
        $this->Date = $Date;
    }
    function CompareTo($other) {
        $_cmp__338 = $this->Text > $other->Text ? 1 : ($this->Text < $other->Text ? -1 : 0);
        if ($_cmp__338 != 0) {
            return $_cmp__338;
        }        
        $_cmp__339 = $this->Player > $other->Player ? 1 : ($this->Player < $other->Player ? -1 : 0);
        if ($_cmp__339 != 0) {
            return $_cmp__339;
        }        
        $_cmp__340 = $this->Date->CompareTo($other->Date);
        if ($_cmp__340 != 0) {
            return $_cmp__340;
        }        
        return 0;
    }
}

#256
abstract class ClientMsg implements FSharpUnion {
}

#256
class ClientMsg_Events extends ClientMsg implements IComparable {
    public $Item1;
    public $Item2;
    function __construct($Item1, $Item2) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
    }
    function get_FSharpCase() {
        return 'Events';
    }
    function get_Tag() {
        return 0;
    }
    function CompareTo($other) {
        $_cmp__341 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__341 != 0) {
            return $_cmp__341;
        }        
        $_cmp__342 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__342 != 0) {
            return $_cmp__342;
        }        
        $_cmp__343 = $this->Item2 > $other->Item2 ? 1 : ($this->Item2 < $other->Item2 ? -1 : 0);
        if ($_cmp__343 != 0) {
            return $_cmp__343;
        }        
        return 0;
    }
}

#256
class ClientMsg_Message extends ClientMsg implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'Message';
    }
    function get_Tag() {
        return 1;
    }
    function CompareTo($other) {
        $_cmp__344 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__344 != 0) {
            return $_cmp__344;
        }        
        $_cmp__345 = $this->Item->CompareTo($other->Item);
        if ($_cmp__345 != 0) {
            return $_cmp__345;
        }        
        return 0;
    }
}

#256
class ClientMsg_Sync extends ClientMsg implements IComparable {
    public $Item1;
    public $Item2;
    public $Item3;
    public $Item4;
    function __construct($Item1, $Item2, $Item3, $Item4) {
        $this->Item1 = $Item1;
        $this->Item2 = $Item2;
        $this->Item3 = $Item3;
        $this->Item4 = $Item4;
    }
    function get_FSharpCase() {
        return 'Sync';
    }
    function get_Tag() {
        return 2;
    }
    function CompareTo($other) {
        $_cmp__346 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__346 != 0) {
            return $_cmp__346;
        }        
        $_cmp__347 = $this->Item1->CompareTo($other->Item1);
        if ($_cmp__347 != 0) {
            return $_cmp__347;
        }        
        $_cmp__348 = $this->Item2->CompareTo($other->Item2);
        if ($_cmp__348 != 0) {
            return $_cmp__348;
        }        
        $_cmp__349 = $this->Item3 > $other->Item3 ? 1 : ($this->Item3 < $other->Item3 ? -1 : 0);
        if ($_cmp__349 != 0) {
            return $_cmp__349;
        }        
        $_cmp__350 = $this->Item4->CompareTo($other->Item4);
        if ($_cmp__350 != 0) {
            return $_cmp__350;
        }        
        return 0;
    }
}

#256
class ClientMsg_SyncPlayer extends ClientMsg implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'SyncPlayer';
    }
    function get_Tag() {
        return 3;
    }
    function CompareTo($other) {
        $_cmp__351 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__351 != 0) {
            return $_cmp__351;
        }        
        $_cmp__352 = $this->Item->CompareTo($other->Item);
        if ($_cmp__352 != 0) {
            return $_cmp__352;
        }        
        return 0;
    }
}

#256
class ClientMsg_ReceiveMessage extends ClientMsg implements IComparable {
    public $Item;
    function __construct($Item) {
        $this->Item = $Item;
    }
    function get_FSharpCase() {
        return 'ReceiveMessage';
    }
    function get_Tag() {
        return 4;
    }
    function CompareTo($other) {
        $_cmp__353 = $this->get_Tag() > $other->get_Tag() ? 1 : ($this->get_Tag() < $other->get_Tag() ? -1 : 0);
        if ($_cmp__353 != 0) {
            return $_cmp__353;
        }        
        $_cmp__354 = $this->Item->CompareTo($other->Item);
        if ($_cmp__354 != 0) {
            return $_cmp__354;
        }        
        return 0;
    }
}

