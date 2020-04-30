<?php


$equals = function ($x,$y) { return $x == $y; };



abstract class FSharpList implements IteratorAggregate {
    
    abstract function isEmpty();


    function __debugInfo() {
        return FSharpList::toArray($this);
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
        $list = NULL;
        $p = &$list;
        foreach ($a as $item) {
            
            $p = new Cons($item, $list);
            $p = &$p->next;
        }
        $p = FSharpList::get_Nil();
        return $list;
    }

    static function toArray($list) {
        $array = [];
        while($list instanceof Cons)
        {
            $array[] = $list->value;
            $list = $list->next;
        }
        return $array;
    }

    static function ofSeq($seq) {
        $list = NULL;
        $p = &$list;
        foreach ($seq as $item) {
            
            $p = new Cons($item, $list);
            $p = &$p->next;
        }
        $p = FSharpList::get_Nil();
        return $list;
    }

    static function toSeq($list) {
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
        $left = NULL;
        $p = &$left;

        while ($i-- > 0 && $list instanceof Cons)
        {
            $p = new Cons($list->value, $left);
            $p = &$p->next;
            $list = $list->next;
        }
        $p = FSharpList::get_Nil();

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

    static function map($projection, $list)
    {
        $lst = NULL;
        $p = &$lst;
        while ($list instanceof Cons)
        {
            $p = new Cons($projection($list->value), $null);
            $p = &$p->next;    
            $list = $list->next;
        }
        $p = FSharpList::get_Nil();
        return $lst;

    }

    static function mapFold($aggregator, $state, $list)
    {
        $lst = NULL;
        $p = &$lst;
        while ($list instanceof Cons)
        {
            $v = $aggregator($state,$list->value);

            $p = new Cons($v[0], $null);
            $p = &$p->next;
            $state = $v[1];
            $list = $list->next;
        }
        $p = FSharpList::get_Nil();
        return [$lst,$state];
    }

    public function collect($projection, $list)
    {
        return FSharpList::ofSeq(Seq::collect($projection, $list));
    }

    public function getIterator() {
        $list = $this;
        while ($list instanceof Cons)
        {
            yield $list->value;
            $list = $list->next;
        }
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

    function __construct($value, FSharpList $next = NULL) {
        $this->value = $value;
        $this->next = $next;
    }

    function isEmpty() { return false; }
}

class FSharpArray {
    public static function ofList($list)
    {
        return FSharpList::toArray($list);
    } 

    public static function ofSeq($seq)
    {
        if (is_array($seq))
        {
            return $seq;
        }
        else
        {
            $array = [];
            foreach($array as $item)
            {
                $array[] = $item;
            }
            return $array;
        }
    }
}

class Seq {
    public static function singleton($v) {
        return [$v];
    } 

    public static function map($projection, $seq)
    {
        foreach($seq as $item)
        { 
            yield $projection($item);
        }
    }
    public static function collect($projection, $seq)
    {
        foreach($seq as $item)
        {
            foreach($projection($seq) as $i)
            {
                yield $i;
            }
        }
    }

    public static function delay($f) 
    {
        return $f(NULL);
    }

    public static function append($x,$y)
    {
        foreach($x as $i)
        {
            yield $i;
        }
        foreach($y as $i)
        {
            yield $i;
        }
    }

    public static function rangeNumber($start, $end)
    {
        while ($start <= $end)
        {
            yield $start++;
        }
    }

}

class Util {

    static function equals($x,$y) { return $x == $y; }

    static function max($comparer, $x, $y) {
        return $comparer($x,$y) >= 0 ? $x : $y;
    }
    static function min($comparer, $x, $y) {
        return $comparer($x,$y) <= 0 ? $x : $y;
    }
}

class Set {
    static function ofSeq($seq)
    {
        return NULL;
    }

    static function empty()
    {
        return NULL;
    }
}

$comparePrimitives = function($x,$y) { return $x == $y ? 0 : $x > $y ? 1 : -1; };



