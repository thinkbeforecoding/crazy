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


        $l = FSharpList::ofArray([1,2,3,4,5,6,7,8]);
        echo_list("chunk", FSharpList::chunkBySize(2,$l));

        $l = FSharpList::ofArray([1,2,3,4,5,6,7,8]);
        echo_list("chunk partial", FSharpList::chunkBySize(3,$l));