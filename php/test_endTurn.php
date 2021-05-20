<html>
    <body>
    <?php
        function bga_rand($min,$max) { return rand($min,$max); }
        require_once 'lib.php';
        require_once 'Serialization.php';

        use \SharedGame\BoardCommand_Play, \SharedGame\Command_Move, \SharedGame\PlayerMove, \SharedGame\Direction_Up, \SharedGame\Direction_Down, \SharedGame\Direction_Horizontal, \SharedGame\Crossroad, \SharedGame\Axe, \SharedGame\CrossroadSide_CLeft, \SharedGame\CrossroadSide_CRight;

        function echo_list ($name, $l)
        {
            echo "<p>",$name,"</p><ul>";
            // $enumerator = \Util\getEnumerator($l);
            // while ($enumerator->MoveNext())
            // {
            //     $e = $enumerator->get_Current();

            //     echo "<li>", var_dump($e), "</li>";
            // }
            foreach ($l as $e)
            {
                echo "<li>", var_dump($e), "</li>";
            }
            echo "</ul>";
            
        }

        $Events = array(
  array('id' => '1','type' => 'BoardEvent_Started','body' => '[{"_type":"BoardStarted","_ns":"SharedGame","Players":{"_list":[[{"_case":"Color_Blue","fields":[],"_ns":"Shared"},"2323672","thinkbeforecoding0",{"_case":"Parcel","fields":[{"_case":"Axe","fields":[0,2],"_ns":"SharedGame"}],"_ns":"SharedGame"}],[{"_case":"Color_Yellow","fields":[],"_ns":"Shared"},"2323673","thinkbeforecoding1",{"_case":"Parcel","fields":[{"_case":"Axe","fields":[0,-2],"_ns":"SharedGame"}],"_ns":"SharedGame"}]]},"DrawPile":{"_list":[{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_Two","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_Dynamite","fields":[],"_ns":"SharedGame"},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_Rut","fields":[],"_ns":"SharedGame"},{"_case":"Card_Watchdog","fields":[],"_ns":"SharedGame"},{"_case":"Card_Helicopter","fields":[],"_ns":"SharedGame"},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_Watchdog","fields":[],"_ns":"SharedGame"},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_HighVoltage","fields":[],"_ns":"SharedGame"},{"_case":"Card_HighVoltage","fields":[],"_ns":"SharedGame"},{"_case":"Card_Dynamite","fields":[],"_ns":"SharedGame"},{"_case":"Card_Helicopter","fields":[],"_ns":"SharedGame"},{"_case":"Card_Bribe","fields":[],"_ns":"SharedGame"},{"_case":"Card_Dynamite","fields":[],"_ns":"SharedGame"},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_Bribe","fields":[],"_ns":"SharedGame"},{"_case":"Card_Rut","fields":[],"_ns":"SharedGame"},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_Two","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_HighVoltage","fields":[],"_ns":"SharedGame"},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_Helicopter","fields":[],"_ns":"SharedGame"},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_Two","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_Bribe","fields":[],"_ns":"SharedGame"},{"_case":"Card_Dynamite","fields":[],"_ns":"SharedGame"},{"_case":"Card_Helicopter","fields":[],"_ns":"SharedGame"},{"_case":"Card_Helicopter","fields":[],"_ns":"SharedGame"},{"_case":"Card_Helicopter","fields":[],"_ns":"SharedGame"},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_Two","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Card_Nitro","fields":[{"_case":"CardPower_One","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}]},"Barns":{"_list":[{"_case":"Parcel","fields":[{"_case":"Axe","fields":[0,0],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[0,-3],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[0,3],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[3,-3],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[-3,0],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[3,0],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[-3,3],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[-2,1],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[2,-1],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[1,-2],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[-1,-1],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[1,1],"_ns":"SharedGame"}],"_ns":"SharedGame"},{"_case":"Parcel","fields":[{"_case":"Axe","fields":[-1,2],"_ns":"SharedGame"}],"_ns":"SharedGame"}]},"Goal":{"_case":"Goal_Common","fields":[27],"_ns":"Shared"},"Undo":{"_case":"UndoType_FullUndo","fields":[],"_ns":"Shared"},"UseGameOver":false}]'),
  array('id' => '2','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FirstCrossroadSelected","fields":[{"_type":"FirstCrossroadSelected","_ns":"SharedGame","Crossroad":{"_case":"Crossroad","fields":[{"_case":"Axe","fields":["0","2"],"_ns":"SharedGame"},{"_case":"CrossroadSide_CRight","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}}],"_ns":"SharedGame"}]'),
  array('id' => '3','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","_ns":"SharedGame","Move":{"_case":"Direction_Horizontal","fields":[],"_ns":"SharedGame"},"Path":{"_case":"Path","fields":[{"_case":"Axe","fields":[1,2],"_ns":"SharedGame"},{"_case":"BorderSide_BN","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},"Crossroad":{"_case":"Crossroad","fields":[{"_case":"Axe","fields":[2,1],"_ns":"SharedGame"},{"_case":"CrossroadSide_CLeft","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}}],"_ns":"SharedGame"}]'),
  array('id' => '4','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","_ns":"SharedGame","Move":{"_case":"Direction_Up","fields":[],"_ns":"SharedGame"},"Path":{"_case":"Path","fields":[{"_case":"Axe","fields":[2,1],"_ns":"SharedGame"},{"_case":"BorderSide_BNW","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},"Crossroad":{"_case":"Crossroad","fields":[{"_case":"Axe","fields":[1,1],"_ns":"SharedGame"},{"_case":"CrossroadSide_CRight","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}}],"_ns":"SharedGame"}]'),
  array('id' => '5','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","_ns":"SharedGame","Move":{"_case":"Direction_Up","fields":[],"_ns":"SharedGame"},"Path":{"_case":"Path","fields":[{"_case":"Axe","fields":[1,1],"_ns":"SharedGame"},{"_case":"BorderSide_BNE","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},"Crossroad":{"_case":"Crossroad","fields":[{"_case":"Axe","fields":[2,0],"_ns":"SharedGame"},{"_case":"CrossroadSide_CLeft","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}}],"_ns":"SharedGame"}]'),
  array('id' => '6','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '7','type' => 'BoardEvent_UndoCheckPointed','body' => '[]'),
  array('id' => '8','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FirstCrossroadSelected","fields":[{"_type":"FirstCrossroadSelected","_ns":"SharedGame","Crossroad":{"_case":"Crossroad","fields":[{"_case":"Axe","fields":["0","-2"],"_ns":"SharedGame"},{"_case":"CrossroadSide_CRight","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}}],"_ns":"SharedGame"}]'),
  array('id' => '9','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","_ns":"SharedGame","Move":{"_case":"Direction_Horizontal","fields":[],"_ns":"SharedGame"},"Path":{"_case":"Path","fields":[{"_case":"Axe","fields":[1,-2],"_ns":"SharedGame"},{"_case":"BorderSide_BN","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},"Crossroad":{"_case":"Crossroad","fields":[{"_case":"Axe","fields":[2,-3],"_ns":"SharedGame"},{"_case":"CrossroadSide_CLeft","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}}],"_ns":"SharedGame"}]'),
  array('id' => '10','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","_ns":"SharedGame","Move":{"_case":"Direction_Down","fields":[],"_ns":"SharedGame"},"Path":{"_case":"Path","fields":[{"_case":"Axe","fields":[1,-2],"_ns":"SharedGame"},{"_case":"BorderSide_BNE","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},"Crossroad":{"_case":"Crossroad","fields":[{"_case":"Axe","fields":[1,-2],"_ns":"SharedGame"},{"_case":"CrossroadSide_CRight","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}}],"_ns":"SharedGame"}]'),
  array('id' => '11','type' => 'BoardEvent_Played','body' => '["2323673",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","_ns":"SharedGame","Move":{"_case":"Direction_Down","fields":[],"_ns":"SharedGame"},"Path":{"_case":"Path","fields":[{"_case":"Axe","fields":[2,-2],"_ns":"SharedGame"},{"_case":"BorderSide_BNW","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},"Crossroad":{"_case":"Crossroad","fields":[{"_case":"Axe","fields":[2,-2],"_ns":"SharedGame"},{"_case":"CrossroadSide_CLeft","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}}],"_ns":"SharedGame"}]'),
  array('id' => '12','type' => 'BoardEvent_Next','body' => '[]'),
  array('id' => '13','type' => 'BoardEvent_UndoCheckPointed','body' => '[]'),
  array('id' => '14','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","_ns":"SharedGame","Move":{"_case":"Direction_Horizontal","fields":[],"_ns":"SharedGame"},"Path":{"_case":"Path","fields":[{"_case":"Axe","fields":[1,1],"_ns":"SharedGame"},{"_case":"BorderSide_BN","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},"Crossroad":{"_case":"Crossroad","fields":[{"_case":"Axe","fields":[0,1],"_ns":"SharedGame"},{"_case":"CrossroadSide_CRight","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}}],"_ns":"SharedGame"}]'),
  array('id' => '15','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_FenceDrawn","fields":[{"_type":"Moved","_ns":"SharedGame","Move":{"_case":"Direction_Down","fields":[],"_ns":"SharedGame"},"Path":{"_case":"Path","fields":[{"_case":"Axe","fields":[1,1],"_ns":"SharedGame"},{"_case":"BorderSide_BNW","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},"Crossroad":{"_case":"Crossroad","fields":[{"_case":"Axe","fields":[1,1],"_ns":"SharedGame"},{"_case":"CrossroadSide_CLeft","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}}],"_ns":"SharedGame"}]'),
  array('id' => '16','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_Annexed","fields":[{"_type":"Annexed","_ns":"SharedGame","NewField":{"_list":[{"_case":"Parcel","fields":[{"_case":"Axe","fields":[1,1],"_ns":"SharedGame"}],"_ns":"SharedGame"}]},"LostFields":{"_list":[]},"FreeBarns":{"_list":[{"_case":"Parcel","fields":[{"_case":"Axe","fields":[1,1],"_ns":"SharedGame"}],"_ns":"SharedGame"}]},"OccupiedBarns":{"_list":[]},"FenceLength":0}],"_ns":"SharedGame"}]'),
  array('id' => '17','type' => 'BoardEvent_PlayerDrewCards','body' => '[{"_type":"PlayerDrewCards","_ns":"SharedGame","Player":"2323672","Cards":{"_case":"Hand_PublicHand","fields":[{"_list":[{"_case":"Card_HayBale","fields":[{"_case":"CardPower_One","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}]}],"_ns":"SharedGame"}}]'),
  array('id' => '18','type' => 'BoardEvent_Played','body' => '["2323672",{"_case":"Event_MovedInField","fields":[{"_type":"Moved","_ns":"SharedGame","Move":{"_case":"Direction_Up","fields":[],"_ns":"SharedGame"},"Path":{"_case":"Path","fields":[{"_case":"Axe","fields":[1,1],"_ns":"SharedGame"},{"_case":"BorderSide_BNW","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"},"Crossroad":{"_case":"Crossroad","fields":[{"_case":"Axe","fields":[0,1],"_ns":"SharedGame"},{"_case":"CrossroadSide_CRight","fields":[],"_ns":"SharedGame"}],"_ns":"SharedGame"}}],"_ns":"SharedGame"}]'),
          );
          

          
        $es = \Seq\map(function ($row) { $js = (object)[ '_case' => $row['type'],
            '_ns' => 'SharedGame',
            'fields' => json_decode($row['body'])];
            return convertFromJson($js);
            }, $Events);
        
        // echo_list("past events", $es);

        $board = $GLOBALS['BoardModule_initialState'];

        $newBoard = \Seq\fold('\\SharedGame\\BoardModule_evolve', $board, $es);

        $cmd = new BoardCommand_Play("2323672", new \SharedGame\Command_EndTurn());
        
        $es = \SharedGame\BoardModule_decide($cmd, $newBoard);
    
        echo_list("events", $es);

        $board = \Seq\fold('\\SharedGame\\BoardModule_evolve', $newBoard, $es);

        echo "<br/><br/>";
        echo "Update state<br/><br/>";

        \SharedServer\bgaUpdateState($es,$board,false,
            "endTurn",
            function($state) { echo "New state: $state"; },
            function($player) {  echo "Eliminate $player"; }
        );