<?php
namespace Shared;

require_once(__FABLE_LIBRARY__.'/FSharp.Core.php');

use \FSharpUnion;
use \IComparable;
use \Union;

#0
abstract class Color implements Union, FSharpUnion {
}

#0
class Color_Blue extends Color implements IComparable {
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
class Color_Yellow extends Color implements IComparable {
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
class Color_Purple extends Color implements IComparable {
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
class Color_Red extends Color implements IComparable {
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
class Goal_Common extends Goal implements IComparable {
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
class Goal_Individual extends Goal implements IComparable {
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
class GoalType_Fast extends GoalType implements IComparable {
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
class GoalType_Regular extends GoalType implements IComparable {
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
class GoalType_Expert extends GoalType implements IComparable {
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
class UndoType_FullUndo extends UndoType implements IComparable {
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
class UndoType_DontUndoCards extends UndoType implements IComparable {
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
class UndoType_NoUndo extends UndoType implements IComparable {
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
function GoalModule_fromType($playerCount, $goal) {
    $matchValue = [ $playerCount, $goal];
    if ($matchValue[0] === 2) {
        switch ($matchValue[1]->get_Tag())
        {
            case 1:
                return new Goal_Common(27);
            case 2:
                return new Goal_Common(31);
            default:
                return new Goal_Common(23);
        }
    } else {
        if ($matchValue[0] === 3) {
            switch ($matchValue[1]->get_Tag())
            {
                case 1:
                    return new Goal_Individual(11);
                case 2:
                    return new Goal_Individual(13);
                default:
                    return new Goal_Individual(9);
            }
        } else {
            if ($matchValue[0] === 4) {
                switch ($matchValue[1]->get_Tag())
                {
                    case 1:
                        return new Goal_Individual(9);
                    case 2:
                        return new Goal_Individual(11);
                    default:
                        return new Goal_Individual(8);
                }
            } else {
                return NULL;
            }
        }
    }
}

