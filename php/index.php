<html>
    <body>
    <?php
        include 'FSharp.Core.php';
        include 'lib.php';

        function echo_list ($name, $l)
        {
            echo "<p>",$name,"</p><ul>";
            foreach($l as $e)
            {
                echo "<li>", var_dump($e), "</li>";
            }
            echo "</ul>";
            
        }
#        $a = new Axe(2,3);
#        echo "Axe: ", var_dump(Shared_002EAxe___op_Multiply__Z425F7B5E(2,$a));

#        $d = new Up();
#        echo "Rev: ", get_class(Shared_002EDirectionModule___rev($d));
        $pc = new PlayCard_PlayNitro(new CardPower_Two());
        echo "Card: ", var_dump(Shared_002ECardModule___ofPlayCard($pc));
        echo "<br/>";
        $a = [ new Path($Shared_002EAxeModule___N , new BorderSide_BN() ), new Path($Shared_002EAxeModule___NE, new BorderSide_BNE() ) ];
        echo "a: ", var_dump($a), "<br/>";
        $lst = FSharpList::ofArray($a);
        echo "array:", var_dump($lst), "<br/>";
        $pc2 = new PlayCard_PlayHayBale($lst);
        echo "Play: ", var_dump($pc2);
        echo "<br/>";

        echo "Card2: ", var_dump(Shared_002ECardModule___ofPlayCard($pc2)), "<br/>";
        $hand = new Hand_PublicHand(FSharpList::ofArray([new Card_Bribe(), new Card_HighVoltage(), new Card_Watchdog()]));
        $result = Shared_002EHandModule___contains(new Card_Rut(), $hand);
        echo "contains: " , var_dump($result),"<br/>";
        $hv = new Card_HighVoltage();
        $rm = FSharpList::tryFindIndex(function($item) { global $hv; return $item == $hv; } , $hand->cards);
        echo "remove: " , var_dump($rm),"<br/>";
        $rm2 = Shared_002EHandModule___remove(new Card_HighVoltage(), $hand);
        echo "Hand.remove: " , var_dump($rm2),"<br/>"; 
        $rm3 = Shared_002EHandModule___remove(new Card_Bribe(), $rm2);
        $rm4 = Shared_002EHandModule___remove(new Card_Watchdog(), $rm3);
        echo "CanPlay: ", var_dump(Shared_002EHandModule___canPlay($rm3)), "<br/>";

        $l0 = FSharpList::ofArray([2,3,4,5]);
        $l = FSharpList::map(function ($x) { return $x + 1;}, $l0 );
        
        echo "map: ", var_dump($l), "<br/>";

        $s = FSharpList::splitAt(2,$l0);
        echo "splitAt: ", var_dump($s), "<br/>";

        echo "<ul>";
        foreach($l as $e)
        {
            echo "<li>item: ", $e,"</li>"; 
        }
        echo "</ul>";

        $mf = FSharpList::mapFold(function($acc,$i){ return [$acc, $i+$acc];}, 0, $l);
        echo "<ul>";
        foreach($mf[0] as $e)
        {
            echo "<li>item: ", $e,"</li>"; 
        }
        echo "</ul>";

        $cmd = new BoardCommand_Start(new BoardStart(FSharpList::ofArray([ [new Color_Blue(), "p1", "Player1" ], [new Color_Red(), "p2", "Player2"]]), new Goal_Common(27)));
        $s = new Board_InitialState();
        $es = Shared_002EBoardModule___decide($cmd, $s);
        $s = FSharpList::fold('Shared_002EBoardModule___evolve', $s, $es);
        $cmd = new BoardCommand_Play("p1", new Command_SelectFirstCrossroad(new SelectFirstCrossroad(new Crossroad(Shared_002EAxe___op_Multiply__Z425F7B5E(2, $Shared_002EAxeModule___N), new CrossroadSide_CLeft()) )) );

        $es2 =  Shared_002EBoardModule___decide($cmd, $s);

        echo var_dump($es2), "<br/><br/>";

        function toJson($obj) {
            if (is_null($obj)) {
                return NULL;
            }
            else if ($obj instanceof FSharpList)
            {
                $array = [];
                foreach($obj as $value)
                {
                    $array[] = toJson($value);
                }

                return [ '_list' => $array];

            }
            else if ($obj instanceof Union)
            {
                $props = [];
                foreach(get_object_vars($obj) as $prop => $value)
                {
                    $props[] = toJson($value);
                }
                return ['_case' => $obj->get_Case(), 'fields' => $props ];
            }
            else if (is_array($obj))
            {
                $array = [];
                foreach($obj as $key => $value)
                {
                    $array[$key] = toJson($value);
                }
                return $array;
            }
            else if (is_object($obj))
            {
                $props = [];
                foreach(get_object_vars($obj) as $prop => $value)
                {
                    $props[$prop] = toJson($value);
                }
                return $props;
            }
            else
                return $obj;
        } 

        function fromJson($json) {
            if (is_null($json))
            {
                return NULL;
            }
            else if (is_object($json) && property_exists($json, '_case'))
            {
                $case = $json->_case;

                $args=[];
                foreach($json->fields as $value)
                {
                    $args[] = fromJson($value);
                }


                return new $case(...$args);
            }
            else if (is_object($json) && property_exists($json, '_list'))
            {
                $array = [];
                foreach($json->_list as $value)
                {
                    $array[] = fromJson($value);
                }
                return FSharpList::ofArray($array);
            }
            else if (is_array($json))
            {
                    $array = [];
                    foreach($json as $value)
                    {
                        $array[] = fromJson($value);
                    }
                    return $array;
            }
            else if (is_object($json))
            {
                $props = [];
                foreach(get_object_vars($json) as $prop => $value)
                {
                    $props[$prop] = fromJson($value);
                }

                return (object)$props;
            }
            else
                return $json;

        }

        echo "listjson:", json_encode(toJson(FSharpList::ofArray([1,2,3])));
        
        echo "json: ", var_dump($es->value), "<br/>";
        $js = json_encode(toJson($es->value));
        echo "json: ", var_dump($js), "<br/>";

        echo "back: ", var_dump(fromJson(json_decode($js))), "<br/>";

        $db = new PDO('mysql:host=mysql;dbname=crazyfarmers', "root", "test");

        $insert = $db->prepare("INSERT INTO `Events` (`type`, `body`) VALUES (:t, :b )");

        // foreach ($es as $e) {
        //     $je = toJson($e);
        //     $insert->execute([':t' => $je['_case'], ':b' =>  json_encode($je['fields']) ]);
        // }

        // foreach ($es2 as $e) {
        //     $je = toJson($e);
        //     $insert->execute([':t' => $je['_case'], ':b' =>  json_encode($je['fields']) ]);
        // }

//        $db->query("INSERT INTO `Events` (`type`, `body`) VALUES ('Test', 'body' )");
        $r = $db->query("SELECT `type`,`body` FROM `Events` ORDER BY `id`");

        echo "<b>Loaded Events:</b><br/><br/>";



        $s2 = new Board_InitialState();

        foreach($r as $row) {
            
            $js = (object)[ '_case' => $row['type'],
                            'fields' => json_decode($row['body'])];

            $e = fromJson($js);
         echo var_dump($e), "<br/><br/>";

         $s2 = Shared_002EBoardModule___evolve($s2,$e);
        }
        

        echo "loaded state: ", var_dump($s2), "<br/><br/>";



    ?>
    </body>
</html>