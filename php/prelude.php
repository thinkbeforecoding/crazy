<?php

include "FSharpArray.php";
include "FSharpList.php";
include "Seq.php";
include "Set.php";
include "Map.php";

$equals = function ($x,$y) { return $x == $y; };





class Util {

    static function equals($x,$y) { return $x == $y; }

    static function max($comparer, $x, $y) {
        return $comparer($x,$y) >= 0 ? $x : $y;
    }
    static function min($comparer, $x, $y) {
        return $comparer($x,$y) <= 0 ? $x : $y;
    }


    static function randomNext($min,$max)
    {
        return rand ($min , $max );
    }
}


$comparePrimitives = function($x,$y) { return $x == $y ? 0 : ($x > $y ? 1 : -1); };
$compareArrays = function($x,$y) { 
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

        if ($x[$i] > $y[$i])
            return 1;
        if ($x[$i] < $y[$i])
            return -1;
        $i++;
    }
 };


