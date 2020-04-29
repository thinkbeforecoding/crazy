<html>
    <body>
    <?php
        include 'prelude.php';
        include 'lib.php';

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
    ?>
    </body>
</html>