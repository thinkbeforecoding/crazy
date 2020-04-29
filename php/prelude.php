<?php


$equals = function ($x,$y) { return $x == $y; };



abstract class FSharpList {
    
    abstract function isEmpty();


    function __debugInfo() {
        $list = $this;
        $array = [];
        while($list instanceof Cons)
        {
            $array[] = $list->value;
            $list = $list->next;
        }
        return $array;
    }
    static function length(FSharpList $list) {
        $len = 0;
        while($list instanceof Cons)
        {
            $len++;
            $list = $list->next;
        }

        return $len;
    }

    static function ofArray($a) {
        $list = FSharpList::get_Nil();
        foreach ($a as $item) {
            $list = new Cons($item, $list);
        }
        return $list;
    }

    static function contains($item, $list, $comparer)
    {
        $eq = $comparer['Equals'];
        while($list instanceof Cons)
        {
            if ($eq($item, $list->value))
            {
                return true;
            }

            $list = $list->next;
        }
        return false;
    }

    static function tryFindIndex($filter, $list)
    {
        $i = 0;
        while ($list instanceof Cons)
        {   
            $v = $filter($list->value);
            if ($v)
            {
                return $i;
            }
            $i++;
            $list = $list->next;
        }
        return NULL;
    }

    static function splitAt($i, $list)
    {
        $left = FSharpList::get_Nil();

        while ($i-- > 0 && $list instanceof Cons)
        {
            $left = new Cons($list->value, $left);
            $list = $list->next;
        }

        return [ $left, $list];
    }

    static function append($left,$right)
    {
        $result = $right;

        while ($left instanceof Cons)
        {
            $result = new Cons($left->value, $result);
            $left = $left->next;
        }
        return $result;
    }


    static function tail($list)
    {
        return $list->next;
    }
    static private $nil;


    static public function get_Nil() {
        if ($nil === null) {
            $nil = new Nil();
        }
        return $nil;
    }
}

class Nil extends FSharpList {
    public function __construct() {}

    function isEmpty() { return true; }

    
}

class Cons extends FSharpList {
    public $value;
    public $next;

    function __construct($value, FSharpList $next) {
        $this->value = $value;
        $this->next = $next;
    }

    function isEmpty() { return false; }
}

class Util {

    static function equals($x,$y) { return $x == $y; }
}

