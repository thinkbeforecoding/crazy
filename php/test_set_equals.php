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


        $l = Set::ofSeq([5,2,4]);
        $r = Set::ofSeq([4,5,3]);
        $l2 = Set::ofSeq([4,5,2]);



        // var_dump(Util::equals($l, Set::empty([ 'Compare' => 'Util::comparePrimitives'])));
        // echo "<br/>";

        // var_dump(Util::equals($l,$l));
        // echo "<br/>";

        var_dump(Util::equals($l,$r));
        echo "<br/>";
        var_dump(Util::equals($l,$l2));
        echo "<br/>";