<html>
    <body>
    <?php
        include 'prelude.php';
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

        $es =  Shared_002EBoardModule___decide($cmd, $s);

        echo var_dump($es), "<br/>";
    ?>
    </body>
</html>