<html>
    <body>
    <?php
        function bga_rand($min,$max) { return rand($min,$max); }
        include 'FSharp.Core.php';
        include 'Serialization.php';
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

        $l = FSharpList::ofArray([ 1,2,3,4 ]);
        $x = Seq::collect(function($v) {return [ $v+1, $v-1 ];},  $l);
        echo_list("collect", $x);

        $x = (function () { 
            $l = new Cons([ "x", 6], new Cons([ "y", 3], FSharpList::get_Nil()));

            return FSharpList::collect(
                    function ($t) {     
                        return FSharpList::ofSeq(Seq::delay(function ($unitVar__28) use ($t) { 
                            return Seq::collect(function ($matchValue__23) use ($t) {  
                                    return Seq::singleton($t[0]);
         }, Seq::rangeNumber(1, 1, $t[1]));
        }));
         }, $l);
        })();

       echo_list("Complex", $x);

       $r = FSharpList::sortBy(function($x) { return -$x; },  $l);
        echo var_dump($r);
       echo_list("sortBy", $r);

       $a = [3 => 5, 4 => 6];
       echo var_dump($a[5]);

        $g = FSharpList::groupBy(function ($x) { return $x % 2; }, $l);
       echo_list("groupBy", $g);

      
       $c = FSharpList::chunkBySize(3,$l);
       echo_list("chunk",$c);


        echo "maxby", var_dump(FSharpList::maxBy(function($s) { return strlen($s);}, FSharpList::ofArray(["hello", "every", "body", "wonderfull", "yo"]), [ 'Compare' => 'Util::comparePrimitives'] )), "<br/>";

        echo "compareArray", var_dump(Util::compareArrays([1], [])), "<br/>";

        echo_list("reverse", FSharpList::reverse($l));

        class Player {
            public $name;
            public $age;
            function __construct($name,$age)
            {
                $this->name = $name;
                $this->age = $age;
            }
        }

        $p1 = new Player("b",40);
        $p2 = new Player("b",41);

        echo "cmp: ", var_dump($p1>$p2), "<br/>";

        $set = Set::ofSeq([2,7,4,2,5]);

        $set2 = Set::ofSeq([3,1,7,2]);

        $add = Set::FSharpSet___op_Addition($set,$set2);
        echo_list("add",$add);

        $sub = Set::FSharpSet___op_Subtraction($set,$set2);
        echo_list("sub",$sub);
        
        $int = Set::intersect($set,$set2);
        echo_list("intersect",$int);

        $m = Map::empty([ 'Compare' => 'Util::comparePrimitives' ]);
        $m = Map::add(3, "a", $m);
        $m = Map::add(5, "b", $m);
        $m = Map::add(2, "c", $m);

        echo_list("map", $m);

        $l = FSharpList::ofArray([ [ 3,2 ], [3,1], [2,3], [2,1], [4,1] ]);

        echo_list("sorby:", FSharpList::sortBy(function ($x) { return $x; }, $l));

// `ebd_crazyfarmers_141547`.`Events`
$Events = array(
  array('id' => '1','type' => 'BoardEvent_Started','body' => '[{"_type":"BoardStarted","Players":{"_list":[[{"_case":"Color_Blue","fields":[]},"2323672","thinkbeforecoding0",{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":-2}}],[{"_case":"Color_Yellow","fields":[]},"2323673","thinkbeforecoding1",{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":2}}]]},"DrawPile":{"_list":[{"_case":"Card_HighVoltage","fields":[]},{"_case":"Card_Dynamite","fields":[]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_HighVoltage","fields":[]},{"_case":"Card_Bribe","fields":[]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Bribe","fields":[]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_Rut","fields":[]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Dynamite","fields":[]},{"_case":"Card_HighVoltage","fields":[]},{"_case":"Card_Bribe","fields":[]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_Watchdog","fields":[]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_Dynamite","fields":[]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_Rut","fields":[]},{"_case":"Card_Watchdog","fields":[]},{"_case":"Card_Dynamite","fields":[]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[]}]}]},"Barns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":0}},{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":-3}},{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":3}},{"_type":"Parcel","tile":{"_type":"Axe","q":3,"r":-3}},{"_type":"Parcel","tile":{"_type":"Axe","q":-3,"r":0}},{"_type":"Parcel","tile":{"_type":"Axe","q":3,"r":0}},{"_type":"Parcel","tile":{"_type":"Axe","q":-3,"r":3}},{"_type":"Parcel","tile":{"_type":"Axe","q":-2,"r":1}},{"_type":"Parcel","tile":{"_type":"Axe","q":2,"r":-1}},{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":-2}},{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":-1}},{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":1}},{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":2}}]},"Goal":{"_case":"Goal_Common","fields":[27]}}]'),
  array('id' => '2','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FirstCrossroadSelected","fields":[{"_type":"FirstCrossroadSelected","Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":"0","r":"-2"},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '3','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-2},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-3},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '4','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-2},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-2},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '5','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":-2},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '6','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '7','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FirstCrossroadSelected","fields":[{"_type":"FirstCrossroadSelected","Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":"0","r":"2"},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '8','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":2},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '9','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '10','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '11','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '12','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '13','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":-1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '14','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":-2}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":-2}}]},"OccupiedBarns":{"_list":[]}}]}]'),
  array('id' => '15','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","Player":"2323672","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_HighVoltage","fields":[]}]}]}}]'),
  array('id' => '16','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":-1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '17','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '18','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '19','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '20','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '21','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '22','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":-1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '23','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":0},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '24','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":0},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '25','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '26','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '27','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":2},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":2},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '28','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":1}},{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":1}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":1}}]},"OccupiedBarns":{"_list":[]}}]}]'),
  array('id' => '29','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","Player":"2323673","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_Dynamite","fields":[]}]}]}}]'),
  array('id' => '30','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":2},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '31','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":2},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":2},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '32','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '33','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '34','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":-1}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[]},"OccupiedBarns":{"_list":[]}}]}]'),
  array('id' => '35','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '36','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":0},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '37','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '38','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":2},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '39','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":3},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":3},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '40','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":3},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '41','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":2}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":2}}]},"OccupiedBarns":{"_list":[]}}]}]'),
  array('id' => '42','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","Player":"2323673","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[]}]}]}]}}]'),
  array('id' => '43','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '44','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":0},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '45','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":-1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '46','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '47','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":-1}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[]},"OccupiedBarns":{"_list":[]}}]}]'),
  array('id' => '48','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '49','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":3},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":3},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '50','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":3},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":3},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '51','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":4},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-3,"r":4},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '52','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '53','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '54','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":-1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":3,"r":-2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '55','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":3,"r":-2},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-2},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '56','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '57','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-3,"r":4},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":3},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '58','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-3,"r":4},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-4,"r":4},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '59','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-4,"r":4},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-3,"r":3},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '60','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '61','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":-2},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":3,"r":-3},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '62','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":3,"r":-3},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-3},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '63','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":-3},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":3,"r":-4},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '64','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '65','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-3,"r":3},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-4,"r":3},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '66','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-3,"r":3},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '67','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":2},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-3,"r":2},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '68','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":2},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '69','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '70','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":-3},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-3},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '71','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-3},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-4},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '72','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-3},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-3},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '73','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-3},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-3},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '74','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":-3}},{"_type":"Parcel","tile":{"_type":"Axe","q":2,"r":-3}},{"_type":"Parcel","tile":{"_type":"Axe","q":2,"r":-2}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[]},"OccupiedBarns":{"_list":[]}}]}]'),
  array('id' => '75','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '76','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '77','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '78','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '79','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":-3,"r":3}},{"_type":"Parcel","tile":{"_type":"Axe","q":-2,"r":2}},{"_type":"Parcel","tile":{"_type":"Axe","q":-2,"r":3}},{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":1}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":-3,"r":3}}]},"OccupiedBarns":{"_list":[]}}]}]'),
  array('id' => '80','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","Player":"2323673","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[]}]}]}]}}]'),
  array('id' => '81','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '82','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '83','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-3},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-3},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '84','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":-3},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-4},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '85','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":-3},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":-3},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '86','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '87','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":0},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '88','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":0},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '89','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '90','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '91','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":-3},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-3},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '92','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":-2},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":-2},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '93','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":-3}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":-3}}]},"OccupiedBarns":{"_list":[]}}]}]'),
  array('id' => '94','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","Player":"2323672","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]}]}]}}]'),
  array('id' => '95','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":-2},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '96','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '97','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '98','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '99','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":-1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '100','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '101','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":-1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '102','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":-1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  array('id' => '103','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":0},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  array('id' => '104','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '105','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":0},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
  //array('id' => '106','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":0},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
  //array('id' => '107','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":0}},{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":-1}},{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":0}}]},"LostFields":{"_list":[["2323672",{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":-1}}]}]]},"FreeBarns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":0}}]},"OccupiedBarns":{"_list":[]}}]}]'),
  //array('id' => '108','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","Player":"2323673","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_Helicopter","fields":[]}]}]}}]')
);

$es = Seq::map(function ($row) { $js = (object)[ '_case' => $row['type'],
    'fields' => json_decode($row['body'])];
    return convertFromJson($js);
    }, $Events);

$board = new Board_InitialState();
$newBoard = Seq::fold('Shared_002EBoardModule___evolve', $board, $es);

function echo_paths($name,$paths)
{
    $ps = Seq::map(function($axe) { $q = $axe[0]->tile->q; $r = $axe[0]->tile->r; $b = $axe[0]->border->get_FSharpCase(); $d = $axe[1]->get_FSharpCase(); return "($q,$r,$b),$d";}, $paths);
    echo "<b>$name</b><br/>";
    echo "<ul>";
    foreach($ps as $p)
    {
        echo "<li>$p</li>";
    }
    echo "</ul>";
}

function echo_field($name,$field)
{
    $ps = Seq::map(function($p) { $q = $p->tile->q; $r = $p->tile->r;return "($q,$r)";}, $field->parcels);
    echo "<b>$name</b><br/>";
    echo "<ul>";
    foreach($ps as $p)
    {
        echo "<li>$p</li>";
    }
    echo "</ul>";
}



$player = Map::FSharpMap__get_Item__2B595($newBoard->Item->Players, "2323673")->Item;
$fence = $player->Fence;
echo_paths("Fence", $fence->paths);

$path = new Path(new Axe(2,0), new BorderSide_BNW());
$nextFence = Shared_002EFenceModule___add($path, new Direction_Down() ,$fence);

echo_paths("Fence", $nextFence->paths);
$field = $player->Field;
$tractor = Shared_002ECrossroadModule___neighbor(new Direction_Down(),$player->Tractor);
var_dump($tractor);
echo_field("Field", $field);


$anx = Shared_002EPlayer___annexation($field,$nextFence,$tractor);

echo_field("Field",$anx);
$border__1 = Shared_002EFieldModule___borderBetween(Shared_002EFenceModule___start($tractor, $nextFence), $tractor, $field);
echo_paths("border between",$border__1);
$fullBorder = FSharpList::append($nextFence->paths, $border__1);
echo_paths("full border ",$fullBorder);
$fill = Shared_002EFieldModule___fill($fullBorder);
echo_field("Fill",$fill);
$anx2 = Shared_002EField___op_Subtraction__Z24735800($fill,$field);
echo_field("subs",$anx2);

echo_list("append", FSharpList::append(FSharpList::ofArray([1,2,3,4]), FSharpList::ofArray([5,6,7,8])));


$Events = array(
    array('id' => '1','type' => 'BoardEvent_Started','body' => '[{"_type":"BoardStarted","Players":{"_list":[[{"_case":"Color_Blue","fields":[]},"2323673","thinkbeforecoding1",{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":-2}}],[{"_case":"Color_Yellow","fields":[]},"2323672","thinkbeforecoding0",{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":2}}]]},"DrawPile":{"_list":[{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Dynamite","fields":[]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_HighVoltage","fields":[]},{"_case":"Card_Dynamite","fields":[]},{"_case":"Card_Watchdog","fields":[]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_Bribe","fields":[]},{"_case":"Card_HighVoltage","fields":[]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Rut","fields":[]},{"_case":"Card_Rut","fields":[]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_Dynamite","fields":[]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_HighVoltage","fields":[]},{"_case":"Card_Watchdog","fields":[]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_Bribe","fields":[]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_Two","fields":[]}]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Bribe","fields":[]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[]}]},{"_case":"Card_Helicopter","fields":[]},{"_case":"Card_Dynamite","fields":[]},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[]}]}]},"Barns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":0}},{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":-3}},{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":3}},{"_type":"Parcel","tile":{"_type":"Axe","q":3,"r":-3}},{"_type":"Parcel","tile":{"_type":"Axe","q":-3,"r":0}},{"_type":"Parcel","tile":{"_type":"Axe","q":3,"r":0}},{"_type":"Parcel","tile":{"_type":"Axe","q":-3,"r":3}},{"_type":"Parcel","tile":{"_type":"Axe","q":-2,"r":1}},{"_type":"Parcel","tile":{"_type":"Axe","q":2,"r":-1}},{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":-2}},{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":-1}},{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":1}},{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":2}}]},"Goal":{"_case":"Goal_Common","fields":[27]}}]'),
    array('id' => '2','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FirstCrossroadSelected","fields":[{"_type":"FirstCrossroadSelected","Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":"0","r":"-2"},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '3','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-2},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-3},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '4','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-2},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-2},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '5','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":-2},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '6','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '7','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FirstCrossroadSelected","fields":[{"_type":"FirstCrossroadSelected","Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":"0","r":"2"},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '8','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":2},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '9','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '10','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '11','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '12','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '13','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":-1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '14','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":-2}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":-2}}]},"OccupiedBarns":{"_list":[]}}]}]'),
    array('id' => '15','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","Player":"2323673","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[]}]}]}]}}]'),
    array('id' => '16','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":-1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '17','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '18','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '19','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '20','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":1}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":1}}]},"OccupiedBarns":{"_list":[]}}]}]'),
    array('id' => '21','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","Player":"2323672","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]}]}]}}]'),
    array('id' => '22','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '23','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '24','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":-1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '25','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":0},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '26','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":0},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '27','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '28','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '29','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '30','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '31','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '32','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":-1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '33','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":-1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '34','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":-1}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":-1}}]},"OccupiedBarns":{"_list":[]}}]}]'),
    array('id' => '35','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","Player":"2323673","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_Dynamite","fields":[]}]}]}}]'),
    array('id' => '36','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_CardPlayed","fields":[{"_case":"PlayCard_PlayHayBale","fields":[{"_list":[{"_type":"Path","tile":{"_type":"Axe","q":"-1","r":"2"},"border":{"_case":"BorderSide_BNE","fields":[]}},{"_type":"Path","tile":{"_type":"Axe","q":"1","r":"0"},"border":{"_case":"BorderSide_BNW","fields":[]}}]}]}]}]'),
    array('id' => '37','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_BonusDiscarded","fields":[{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[]}]}]}]'),
    array('id' => '38','type' => 'BoardEvent_HayBalesPlaced','body' => '[{"_list":[{"_type":"Path","tile":{"_type":"Axe","q":"-1","r":"2"},"border":{"_case":"BorderSide_BNE","fields":[]}},{"_type":"Path","tile":{"_type":"Axe","q":"1","r":"0"},"border":{"_case":"BorderSide_BNW","fields":[]}}]}]'),
    array('id' => '39','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":-1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '40','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '41','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_CardPlayed","fields":[{"_case":"PlayCard_PlayNitro","fields":[{"_case":"CardPower_One","fields":[]}]}]}]'),
    array('id' => '42','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_SpedUp","fields":[{"_type":"SpedUp","Speed":1}]}]'),
    array('id' => '43','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":2},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":2},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '44','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":2},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '45','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":3},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":3},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '46','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":3},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '47','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":2}},{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":1}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":2}}]},"OccupiedBarns":{"_list":[]}}]}]'),
    array('id' => '48','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","Player":"2323672","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_Helicopter","fields":[]}]}]}}]'),
    array('id' => '49','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_CardPlayed","fields":[{"_case":"PlayCard_PlayHelicopter","fields":[{"_type":"Crossroad","tile":{"_type":"Axe","q":"1","r":"1"},"side":{"_case":"CrossroadSide_CRight","fields":[]}}]}]}]'),
    array('id' => '50','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_Heliported","fields":[{"_type":"Crossroad","tile":{"_type":"Axe","q":"1","r":"1"},"side":{"_case":"CrossroadSide_CRight","fields":[]}}]}]'),
    array('id' => '51','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_BonusDiscarded","fields":[{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[]}]}]}]'),
    array('id' => '52','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '53','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":-1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":-2},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '54','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":-1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-3,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '55','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":-1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '56','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '57','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":3,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '58','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":3,"r":0},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '59','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":0},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":3,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '60','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_BonusDiscarded","fields":[{"_case":"Card_Helicopter","fields":[]}]}]'),
    array('id' => '61','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '62','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-3,"r":0},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-3,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '63','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":0},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '64','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":-2,"r":-1}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[]},"OccupiedBarns":{"_list":[]}}]}]'),
    array('id' => '65','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":0},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-3,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '66','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '67','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":2,"r":0},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '68','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":0},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":2,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '69','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":0},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '70','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '71','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":0},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '72','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-3,"r":1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-3,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '73','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '74','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '75','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":0},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '76','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":0},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '77','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":0},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '78','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '79','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":0}},{"_type":"Parcel","tile":{"_type":"Axe","q":1,"r":0}},{"_type":"Parcel","tile":{"_type":"Axe","q":2,"r":0}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":0}}]},"OccupiedBarns":{"_list":[]}}]}]'),
    array('id' => '80','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","Player":"2323672","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[]}]}]}]}}]'),
    array('id' => '81','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_CardPlayed","fields":[{"_case":"PlayCard_PlayHayBale","fields":[{"_list":[{"_type":"Path","tile":{"_type":"Axe","q":"-1","r":"0"},"border":{"_case":"BorderSide_BNW","fields":[]}},{"_type":"Path","tile":{"_type":"Axe","q":"1","r":"1"},"border":{"_case":"BorderSide_BN","fields":[]}}]}]}]}]'),
    array('id' => '82','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_BonusDiscarded","fields":[{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[]}]}]}]'),
    array('id' => '83','type' => 'BoardEvent_HayBalesPlaced','body' => '[{"_list":[{"_type":"Path","tile":{"_type":"Axe","q":"-1","r":"0"},"border":{"_case":"BorderSide_BNW","fields":[]}},{"_type":"Path","tile":{"_type":"Axe","q":"1","r":"1"},"border":{"_case":"BorderSide_BN","fields":[]}}]}]'),
    array('id' => '84','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '85','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":1},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '86','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":1},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":0},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '87','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":0},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '88','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '89','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '90','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":2},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-2,"r":2},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    array('id' => '91','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-2,"r":2},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '92','type' => 'BoardEvent_Next','body' => '[]'),
    array('id' => '93','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":0},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    array('id' => '94','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":-2,"r":0}},{"_type":"Parcel","tile":{"_type":"Axe","q":-1,"r":0}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[]},"OccupiedBarns":{"_list":[]}}]}]'),
    array('id' => '101','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_CardPlayed","fields":[{"_case":"PlayCard_PlayDynamite","fields":[{"_type":"Path","tile":{"_type":"Axe","q":"-1","r":"0"},"border":{"_case":"BorderSide_BNW","fields":[]}}]}]}]'),
    array('id' => '102','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_BonusDiscarded","fields":[{"_case":"Card_Dynamite","fields":[]}]}]'),
    array('id' => '103','type' => 'BoardEvent_HayBaleDynamited','body' => '[{"_type":"Path","tile":{"_type":"Axe","q":"-1","r":"0"},"border":{"_case":"BorderSide_BNW","fields":[]}}]'),
    // array('id' => '104','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","Move":{"_case":"Direction_Down","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":-1,"r":0},"border":{"_case":"BorderSide_BNE","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":-1,"r":0},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    // array('id' => '105','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Horizontal","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":0,"r":0},"border":{"_case":"BorderSide_BN","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":1,"r":-1},"side":{"_case":"CrossroadSide_CLeft","fields":[]}}}]}]'),
    // array('id' => '106','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","Move":{"_case":"Direction_Up","fields":[]},"Path":{"_type":"Path","tile":{"_type":"Axe","q":1,"r":-1},"border":{"_case":"BorderSide_BNW","fields":[]}},"Crossroad":{"_type":"Crossroad","tile":{"_type":"Axe","q":0,"r":-1},"side":{"_case":"CrossroadSide_CRight","fields":[]}}}]}]'),
    // array('id' => '107','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","NewField":{"_list":[{"_type":"Parcel","tile":{"_type":"Axe","q":0,"r":-1}}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[]},"OccupiedBarns":{"_list":[]}}]}]'),
    // array('id' => '108','type' => 'BoardEvent_Next','body' => '[]')
);
    $es = Seq::map(function ($row) { $js = (object)[ '_case' => $row['type'],
        'fields' => json_decode($row['body'])];
        return convertFromJson($js);
        }, $Events);
    
    $board = new Board_InitialState();
    $newBoard = Seq::fold('Shared_002EBoardModule___evolve', $board, $es);
    
    var_dump($newBoard->Item->HayBales);

    $ax = new Axe(3,3);
    $ay = new Axe(3,2);
    echo "ax ay ", var_dump($ax->CompareTo($ay)), "<br/>"; 
    echo "ay ax ", var_dump($ay->CompareTo($ax)), "<br/>"; 

    $cx = new Crossroad(new Axe(3,2), new CrossroadSide_CLeft());
    $cy = new Crossroad(new Axe(3,2), new CrossroadSide_CRight());
    echo "cx cy", var_dump($cx->CompareTo($cy)),"<br/>";
    echo "cy cx", var_dump($cy->CompareTo($cx)),"<br/>";

    $cut = Shared_002EHayBales___findCutPaths(Set::empty([ 'Compare' => function ($_x__49, $_y__50) {         return $_x__49->CompareTo($_y__50);
    }]));

    echo_list("cut", $cut);
        ?>
    </body>
</html>