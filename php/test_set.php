<html>
    <body>
    <?php
        function bga_rand($min,$max) { return rand($min,$max); }
        define('__FABLE_LIBRARY__',dirname(__FILE__).'/Fable');
        define('__ROOT__',dirname(__FILE__));
        require_once(__FABLE_LIBRARY__.'/Seq.php');
        require_once(__ROOT__.'/Serialization.php');
        require_once(__ROOT__.'/Shared/SharedGame.fs.php');

        function echo_list ($name, $l)
        {
            echo "<p>",$name,"</p><ul>";
            foreach($l as $e)
            {
                echo "<li>", var_dump($e), "</li>";
            }
            echo "</ul>";
            
        }

        function test($f, $text, $value, $expected) {
            if ($f($value,$expected))
            {
                echo "✔️ ";
                echo $text;
                echo "<br/>";
            }
            else
            {
                echo "❌ ";
                echo "$text";
                echo "<br/>value:<br/>";
                var_dump($value);
                echo "<br/>expected:<br/>";
                echo "<br/><br/>";
                
            }
        }

        function shouldEqual($text,$value,$expected) { test(function($x,$y) { return \Util\equals($x,$y);},$text,$value,$expected); }
        function shouldNotEqual($text,$value,$expected) { test(function($x,$y) { return !\Util\equals($x,$y);},$text,$value,$expected); }

        function isTrue($text,$value) { test(function($x,$y) { return $x;},$text,$value, true);};
        function isFalse($text,$value) { test(function($x,$y) { return !$x;},$text,$value, true);};

        $l = \Set\ofSeq([5,2,4]);
        $r = \Set\ofSeq([4,5,3]);
        $l2 = \Set\ofSeq([4,5,2]);



        // var_dump(\Util\equals($l, \Set\empty([ 'Compare' => '\Util\comparePrimitives'])));
        // echo "<br/>";

        // var_dump(\Util\equals($l,$l));
        // echo "<br/>";


        shouldNotEqual("equals: different sets", $l,$r);
        shouldEqual("equals: equal sets", $l,$l2 );

        $ls = [];
        for ($i=0 ;$i<100;$i++)
            $ls[] = random_int(0,1000000);
        $ls = \FSharpList\ofArray($ls);
        $s = \Set\ofSeq($ls);
        $r = \FSharpList\forAll(function ($x) use ($s) { echo $x . "<br/>"; \Set\contains($x,$s); }, $l);
        isTrue("contains: a set contains all its elements",$r);

        
        $sortedLs = \FSharpList\sortBy(function ($x) { return $x;}, $ls);
        $setLs = \Set\toList($s);
        shouldEqual("toList: set is ordered", $setLs, $sortedLs);

        $ls2 = [];
        for ($i=0 ;$i<100;$i++)
            $ls2[] = random_int(0,1000000);
        $ls2 = \FSharpList\ofArray($ls2);
        $s2 = \Set\ofSeq($ls2);

        $sall = \Set\FSharpSet_op_Addition($s,$s2);
        shouldEqual("op_Addition: addition of set is set of concat", $sall, \Set\ofSeq( \FSharpList\append($ls,$ls2) )); 
