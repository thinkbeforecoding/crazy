<?php

include "FSharpArray.php";
include "FSharpList.php";
include "Seq.php";
include "Set.php";
include "Map.php";

$equals = function ($x,$y) { return $x == $y; };

interface iComparable {
    public function CompareTo($Other);
}



class Util {
    static function equals($x,$y) { 
        if ( $x instanceof iComparable)
            return $x->CompareTo($y) == 0;
        
        return $x == $y; }

    static function max($comparer, $x, $y) {
        return $comparer($x,$y) >= 0 ? $x : $y;
    }
    static function min($comparer, $x, $y) {
        return $comparer($x,$y) <= 0 ? $x : $y;
    }


    static function randomNext($min,$max)
    {
        return bga_rand ($min , $max );
    }


    static function comparePrimitives($x,$y) 
    { return $x == $y ? 0 : ($x > $y ? 1 : -1); }

    static function compareArrays ($x,$y) { 
        $i = 0;
        $xl = count($x);
        $yl = count($y);
        while(true)
        {
            if ($i == $xl && $i == $yl)
                return 0;
            if ($i == $xl)
                return -1;
            if ($i == $yl)
                return 1;

            $c = Util::compare($x[$i],$y[$i]);
            if ($c !== 0)
                return $c;
            $i++;
        }
    }

    static function compare($x,$y)
    {
        if (is_array($x))
        {
            if (is_array($y))
                return Util::compareArrays($x,$y);
            else
                return 1;
        }
        elseif ($x instanceof iComparable )
        {
            return $x->CompareTo($y);
        }
        {
            if (is_array($y))
                return -1;
            else
                return Util::comparePrimitives($x,$y);
        }

        
    }
}
interface Union {
    public function get_Case();
}
interface FSharpUnion {
    public function get_FSharpCase();
}


function void($x) {}

class Result {

}

class Ok extends Result {
    public $ResultValue;
    function __construct($value)
    {
        $this->ResultValue = $value;
    }

}

class ResultError extends Result {
    public $ErrorValue;
    function __construct($value)
    {
        $this->ErrorValue = $value;
    }
}

class Option {
    static function  defaultArg($opt, $val)
    {
        return is_null($opt) ? $val : $opt;
    }
}