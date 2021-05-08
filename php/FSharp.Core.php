<?php

include "FSharpArray.php";
include "FSharpList.php";
include "Seq.php";
include "Seq2.php";
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

    static function stringHash($s) {
        $i = 0;
        $h = 5381;
        $len = strlen($s);
        while ($i < $len) {
            $h = ($h * 33) ^ $s[$i++];
        }
        return $h;
    }

    static function numberHash($x) {
        return $x * 2654435761;
    }
    static function arrayHash($x) {
        $len = count($x);
        $hashes = [];
        for ($i = 0; $i < $len; $i++) {
            $hashes[$i] = Util::structuralHash($x[$i]);
        }
        return Util::combineHashCodes($hashes);
    }
    static function combineHashCodes($hashes) {
        if (count($hashes) === 0) {
            return 0;
        }
        $hash = 0;
        foreach ($hashes as $value) {
            $hash = (($hash << 5) + $hash) ^ $value;
        }
        return $hash;
    }

    static function structuralHash($x) {
        if (is_null($x)) {
            return 0;
        }
        switch (gettype($x)) {
            case "boolean":
                return $x ? 1 : 0;
            case "integer":
                return numberHash($x);
            case "double":
                return numberHash($x);
            case "string":
                return stringHash($x);
            case "array":
                return arrayHash($x);
            default: {
                if ($x instanceof IEquatable) {
                    return $x->GetHashCode();
                }
                // else if ($x instanceof Date) {
                //     return dateHash($x);
                // }
                $vars = get_object_vars ($x);
                if (count($vars) != 0)
                {
                    $vals = [];
                    foreach ($vars as $value) {
                        $vals[] = structuralHash($value);
                    }
                    return Util::combineHashCodes($vals);
                }

                else {
                    // Classes don't implement GetHashCode by default, but must use identity hashing
                    return Util::stringHash(spl_object_hash ($x));
                }
            }
        }
    }
    static function safeHash($x)
    {
        if ($x === NULL)
        {
            return 0;
        }
        if ($x instanceof IEquatable)
        {
            return $x->GetHashCode();
        }
        
        return Util::stringHash(spl_object_hash ($x));
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

    public function get_Tag() {
        return 0;
    }
}

class Result_Error extends Result {
    public $ErrorValue;
    function __construct($value)
    {
        $this->ErrorValue = $value;
    }
    public function get_Tag() {
        return 1;
    }
}

class Option {
    static function  defaultArg($opt, $val)
    {
        return is_null($opt) ? $val : $opt;
    }
}
