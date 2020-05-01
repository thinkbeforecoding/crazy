<html>
    <body>
    <?php
        include 'prelude.php';

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


        echo "maxby", var_dump(FSharpList::maxBy(function($s) { return strlen($s);}, FSharpList::ofArray(["hello", "every", "body", "wonderfull", "yo"]), [ 'Compare' => $GLOBALS['comparePrimitives']] )), "<br/>";

        echo "compareArray", var_dump($compareArrays([1], [])), "<br/>";


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
        
        ?>
    </body>
</html>