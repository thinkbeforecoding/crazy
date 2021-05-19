<?php
require_once('Fable/List.php');


// converts an object tree to another that
// can be converted to json
function convertToJson($obj) {
    if (is_null($obj)) {
        return NULL;
    }
    else if ($obj instanceof \FSharpList\FSharpList)
    {
        $array = [];
        foreach($obj as $value)
        {
            $array[] = convertToJson($value);
        }

        return [ '_list' => $array];

    }
    else if ($obj instanceof \FSharpUnion)
    {
        $props = [];
        $r = new \ReflectionClass($obj);
        foreach(get_object_vars($obj) as $prop => $value)
        {
            $props[] = convertToJson($value);
        }
        $array = ['_case' => $r->getShortName(), 'fields' => $props ];
        $ns = $r->getNamespaceName();
        if ($ns) {
            $array['_ns'] = $ns;
        }

        return $array;
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
        $r = new \ReflectionClass($obj);
        $props['_type'] = $r->getShortName();
        $ns = $r->getNamespaceName();
        if ($ns) {
            $props['_ns'] = $ns;
        }

        foreach(get_object_vars($obj) as $prop => $value)
        {
            $props[$prop] = convertToJson($value);
        }
        return $props;
    }
    else
        return $obj;
} 

function fixNamespace($name)
{
    switch($name)
    {
        case 'Color_Purple': 
        case 'Color_Yellow':
        case 'Color_Red': 
        case 'Color_Blue': 
        case 'Goal_Common': 
        case 'Goal_Individual': 
        case 'UndoType_NoUndo': 
        case 'UndoType_FullUndo':
        case 'UndoType_DontUndoCards':
        case 'GoalType_Fast':
        case 'GoalType_Regular':
        case 'GoalType_Expert':
             return '\\Shared\\'.$name;
        default:
             if (strpos($name, '\\'))
             {
                return $name;
             }
            else 
                return '\\SharedGame\\'.$name;
    }
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
            if (property_exists($json, '_ns'))
            {
                $case = $json->_ns . "\\" . $json->_case;
            }
            else
            {
                $case = fixNamespace($json->_case);
            }

            $args=[];
            foreach($json->fields as $value)
            {
                $args[] = convertFromJson($value);
            }


            return new $case(...$args);
        }
        if (property_exists($json, '_type'))
        {
            if (property_exists($json, '_ns'))
            {
                $type = $json->_ns . "\\" . $json->_type;
            }
            else
            {
                $type = fixNamespace($json->_type);
            }
            $args=[];
            foreach(get_object_vars($json) as $prop => $value)
            {
                if ($prop != '_type' && $prop != '_ns')
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
            return \FSharpList\ofArray($array);
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
    else if ($obj instanceof \FSharpList\FSharpList)
    {
        $array = [];
        foreach($obj as $value)
        {
            $array[] = convertToSimpleJson($value);
        }

        return $array;

    }
    else if ($obj instanceof \FSharpUnion)
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

