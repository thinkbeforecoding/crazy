<?php
class Seq {
    public static function singleton($v) {
        return [$v];
    } 

    static public function empty() {
        static $empty = NULL;
        if (is_null($empty)) {
            $empty = [];
        }
        return $empty;
    }
    public static function filter($filter, $seq)
    {
        foreach($seq as $item)
        { 
            if ($filter($item))
                yield $item;
        }
    }
    public static function map($projection, $seq)
    {
        foreach($seq as $item)
        { 
            yield $projection($item);
        }
    }

    public static function choose($projection, $seq)
    {
        foreach($seq as $item)
        { 
            $value = $projection($item);
            if (!is_null($value))
            {
                yield $value;
            }
        }
    }


    public static function collect($projection, $seq)
    {
        foreach($seq as $item)
        {
            foreach($projection($item) as $i)
            {
                yield $i;
            }
        }
    }

    public static function contains($value, $seq)
    {
        foreach($seq as $item)
        {
            if ($item == $value)
                return true;
        }

        return false;

    }

    public static function tryFindIndex($test, $seq)
    {
        $index = 0;
        foreach($seq as $item)
        {
            if ($test($item))
                return $index;

            $index++;
        }
        return NULL;
    }

    public static function exists($test, $seq)
    {
        foreach($seq as $item)
        {
            if ($test($item))
                return true;

        }
        return false; 
    }

    public static function delay($f) 
    {
        return new DelayedSeq($f);
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

    public static function rangeNumber($start, $inc, $end)
    {
        while ($start <= $end)
        {
            yield $start;
            $start += $inc;
        }
    }

    static function maxBy($property, $seq, $comparerArray)
    {
        $max = NULL;
        $maxVal = NULL;
        $comparer = $comparerArray['Compare'];
        foreach($seq as $item)
        {
            $prop = $property($item);

            if (is_null($max) || $comparer($prop,$max) > 0)
            {
                $max = $prop;
                $maxVal = $item;
            }
        }

        return $maxVal;
    }

    static function max($seq, $comparerArray)
    {
        $max = NULL;
        $comparer = $comparerArray['Compare'];
        foreach($seq as $item)
        {
            if (is_null($max) || $comparer($item,$max) > 0)
            {
                $max = $item;
            }
        }

        return $max;
    }

     

    static function fold($aggregator, $state, $seq)
    {
        foreach ($seq as $item)
        {
            $state = $aggregator($state,$item);
        }
        return $state;
    }

    static function iterate($f, $seq)
    {
        foreach($seq as $item)
        {
            $f($item);
        }
    }

}


class DelayedSeq implements IteratorAggregate {
    public $f;
    function __construct($f) {
        $this->f = $f;
    }

    public function getIterator() {
        $f = $this->f;
        $seq = $f(NULL);
        foreach($seq as $item)
            yield $item;
    } 
    
}