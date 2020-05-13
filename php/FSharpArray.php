
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

}