
<?php
class FSharpArray {
    public static function ofList($list)
    {
        return FSharpList::toArray($list);
    } 

    public static function toList($array)
    {
        return FSharpList::ofArray($array);
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
            foreach($seq as $item)
            {
                $array[] = $item;
            }
            return $array;
        }
    }

    public static function filter($filter,$array)
    {
        $result = [];
        foreach($array as $item)
        {
            if ($filter($item))
            {
                $result[] = $item;
            }
        }
        return $result;
    }

    public static function findIndex($predicate, $array)
    {
        $index = 0;
        foreach ($array as $item)
        {
            if ($predicate($item))
            {
                return $index;
            }
            $index++;
        }

        return -1;
    }
}