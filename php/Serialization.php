<?php

// converts an object tree to another that
// can be converted to json
function convertToJson($obj) {
    if (is_null($obj)) {
        return NULL;
    }
    else if ($obj instanceof FSharpList)
    {
        $array = [];
        foreach($obj as $value)
        {
            $array[] = convertToJson($value);
        }

        return [ '_list' => $array];

    }
    else if ($obj instanceof Union)
    {
        $props = [];
        foreach(get_object_vars($obj) as $prop => $value)
        {
            $props[] = convertToJson($value);
        }
        return ['_case' => $obj->get_Case(), 'fields' => $props ];
    }
    else if (is_array($obj))
    {
        $array = [];
        foreach($obj as $key => $value)
        {
            $array[$key] = convertToJson($value);
        }
        return $array;
    }
    else if (is_object($obj))
    {
        $props = [];
        $props['_type'] = get_class($obj);
        foreach(get_object_vars($obj) as $prop => $value)
        {
            $props[$prop] = convertToJson($value);
        }
        return $props;
    }
    else
        return $obj;
} 


// converts a json parsed object tree
// to an actual F# object tree
function convertFromJson($json) {
    if (is_null($json))
    {
        return NULL;
    }
    if (is_object($json))
    {
        if (property_exists($json, '_case'))
        {
            $case = $json->_case;

            $args=[];
            foreach($json->fields as $value)
            {
                $args[] = convertFromJson($value);
            }


            return new $case(...$args);
        }
        if (property_exists($json, '_type'))
        {
            $type = $json->_type;
            $args=[];
            foreach(get_object_vars($json) as $prop => $value)
            {
                if ($prop != '_type')
                {
                    $args[] = convertFromJson($value);
                }
            }
            return new $type(...$args);
        }
        if (property_exists($json, '_list'))
        {
            $array = [];
            foreach($json->_list as $value)
            {
                $array[] = convertFromJson($value);
            }
            return FSharpList::ofArray($array);
        }
        
        $props = [];
        foreach(get_object_vars($json) as $prop => $value)
        {
            $props[$prop] = convertFromJson($value);
        }

        return (object)$props;
    }
    if (is_array($json))
    {
            $array = [];
            foreach($json as $value)
            {
                $array[] = convertFromJson($value);
            }
            return $array;
    }
    return $json;

}



// converts an object tree to another that
// can be converted to json
function convertToSimpleJson($obj) {
    if (is_null($obj)) {
        return NULL;
    }
    else if ($obj instanceof FSharpList)
    {
        $array = [];
        foreach($obj as $value)
        {
            $array[] = convertToSimpleJson($value);
        }

        return $array;

    }
    else if ($obj instanceof FSharpUnion)
    {
        $vars = get_object_vars($obj);
        if (empty($vars))
            return $obj->get_FSharpCase();

        $props = [$obj->get_FSharpCase()];
        foreach($vars as $prop => $value)
        {
            $props[] = convertToSimpleJson($value);
        }
        return $props;
    }
    else if (is_array($obj))
    {
        $array = [];
        foreach($obj as $key => $value)
        {
            $array[$key] = convertToSimpleJson($value);
        }
        return $array;
    }
    else if (is_object($obj))
    {
        $props = [];
        foreach(get_object_vars($obj) as $prop => $value)
        {
            $props[$prop] = convertToSimpleJson($value);
        }
        return $props;
    }
    else
        return $obj;
} 

