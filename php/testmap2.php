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


        $l1 = FSharpList::ofArray([5,2,4]);
        $l2 = FSharpList::ofArray([3,8,2]);


        $l = FSharpList::map2(function($x,$y) { return $x + $y; }, $l1, $l2);

        echo_list("map2", $l);