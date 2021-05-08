<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * CrazyFarmers implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * crazyfarmers.action.php
 *
 * CrazyFarmers main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/crazyfarmers/crazyfarmers/myAction.html", ...)
 *
 */
  
  
  class action_crazyfarmers extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "crazyfarmers_crazyfarmers";
            self::trace( "Complete reinitialization of board game" );
      }
  	} 
  	
  	// TODO: defines your action entry points there


    /*
    
    Example:
  	
    public function myAction()
    {
        self::setAjaxMode();     

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = self::getArg( "myArgument1", AT_posint, true );
        $arg2 = self::getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        self::ajaxResponse( );
    }
    
    */

    function newCrossroad($q,$r,$side)
    {
        return new Crossroad(new Axe($q,$r), $side == "CRight" ? new CrossroadSide_CRight() : new CrossroadSide_CLeft());
    }

    public function selectFirstCrossroad()
    {
        self::setAjaxMode();

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $q = self::getArg( "q", AT_int, true );
        $r = self::getArg( "r", AT_int, true );
        $side = self::getArg( "side", AT_enum, true, "CLeft", ["CLeft", "CRight"] );

        $this->game->selectFirstCrossroad(self::newCrossroad($q,$r,$side));

        self::ajaxResponse();
    }



    public function move()
    {
        self::setAjaxMode();

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $dir = self::getArg( "direction", AT_enum, true, "Up", ["Up", "Down", "Horizontal"] );
        $q = self::getArg( "q", AT_int, true );
        $r = self::getArg( "r", AT_int, true );
        $side = self::getArg( "side", AT_enum, true, "CLeft", ["CLeft", "CRight"] );

        switch ($dir)
        {
            case "Up": 
                $direction = new Direction_Up();
            break;
            case "Down" : 
                $direction =  new Direction_Down();
                break;
            default: $direction = new Direction_Horizontal();
        }

        $this->game->move($direction, self::newCrossroad($q,$r,$side));

        self::ajaxResponse();
    }

    public function playNitro()
    {
        self::setAjaxMode();

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $pow = self::getArg( "power", AT_enum, true, "One", ["One", "Two"] );
        switch ($pow)
        {
            case "Two": 
                $power = new CardPower_Two();
            break;
            default:
                $power =  new CardPower_One();
        }

        $this->game->playCard(new PlayCard_PlayNitro($power));

        self::ajaxResponse(); 
    }

    public function playRut()
    {
        self::setAjaxMode();

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $victim = self::getArg( "victim", AT_alphanum, true);

        $this->game->playCard(new PlayCard_PlayRut($victim));

        self::ajaxResponse(); 
    }
     
    public function playOneHayBale()
    {
        global $NIL;
        self::setAjaxMode();

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $q = self::getArg( "q", AT_int, true );
        $r = self::getArg( "r", AT_int, true );
        $side = self::getArg( "side", AT_enum, true, "BN", ["BNW", "BN", "BNE"] );

        $rm_q = self::getArg( "rm_q", AT_int, false );
        $rm_r = self::getArg( "rm_r", AT_int, false );
        $rm_side = self::getArg( "rm_side", AT_enum, false, NULL, ["BNW", "BN", "BNE"] );
      
        if (!(is_null($rm_q) || is_null($rm_r) ||is_null($rm_side)))
            $rm_path = FSharpList::ofArray([ self::newPath($rm_q,$rm_r,$rm_side)]);
        else
            $rm_path = $NIL;

        $this->game->playCard(new PlayCard_PlayHayBale(FSharpList::ofArray([ self::newPath($q,$r,$side)  ]), $rm_path));

        self::ajaxResponse(); 
    }

    function newPath($q,$r,$side)
    {
        switch ($side)
        {
            case "BNW":
                $side = new BorderSide_BNW();
            break;
            case "BN":
                $side = new BorderSide_BN();
            break;
            default:
                $side = new BorderSide_BNE();
        }
        return new Path(new Axe($q,$r), $side);
    }

    public function playTwoHayBale()
    {
        global $NIL;
        self::setAjaxMode();

        $q1 = self::getArg( "q1", AT_int, true );
        $r1 = self::getArg( "r1", AT_int, true );
        $side1 = self::getArg( "side1", AT_enum, true, "BN", ["BNW", "BN", "BNE"] );
        $q2 = self::getArg( "q2", AT_int, true );
        $r2 = self::getArg( "r2", AT_int, true );
        $side2 = self::getArg( "side2", AT_enum, true, "BN", ["BNW", "BN", "BNE"] );

        $rm_q1 = self::getArg( "rm_q1", AT_int, false );
        $rm_r1 = self::getArg( "rm_r1", AT_int, false );
        $rm_side1 = self::getArg( "rm_side1", AT_enum, false, NULL, ["BNW", "BN", "BNE"] );
        $rm_q2 = self::getArg( "rm_q2", AT_int, false );
        $rm_r2 = self::getArg( "rm_r2", AT_int, false );
        $rm_side2 = self::getArg( "rm_side2", AT_enum, false, NULL, ["BNW", "BN", "BNE"] );

        $rm_path = $NIL; 
        if (!(is_null($rm_q1) || is_null($rm_r1) ||is_null($rm_side1)))
            $rm_path = new Cons(self::newPath($rm_q1,$rm_r1,$rm_side1), $rm_path);

        if (!(is_null($rm_q2) || is_null($rm_r2) ||is_null($rm_side2)))
            $rm_path = new Cons(self::newPath($rm_q2,$rm_r2,$rm_side2),$rm_path);

        $this->game->playCard(new PlayCard_PlayHayBale(FSharpList::ofArray([
                self::newPath($q1,$r1,$side1), 
                self::newPath($q2,$r2,$side2) ]),
                $rm_path
            ));

        self::ajaxResponse(); 
    }

    public function playDynamite()
    {
        self::setAjaxMode();

        $q = self::getArg( "q", AT_int, true );
        $r = self::getArg( "r", AT_int, true );
        $side = self::getArg( "side", AT_enum, true, "BN", ["BNW", "BN", "BNE"] );

        $this->game->playCard(new PlayCard_PlayDynamite(self::newPath($q,$r,$side)));

        self::ajaxResponse(); 
    }

    public function playHighVoltage()
    {
        self::setAjaxMode();

        $this->game->playCard(new PlayCard_PlayHighVoltage());

        self::ajaxResponse(); 
    }

    
    public function playWatchdog()
    {
        self::setAjaxMode();


        $this->game->playCard(new PlayCard_PlayWatchdog());

        self::ajaxResponse(); 
    }

    public function playHelicopter()
    {
        self::setAjaxMode();

        $q = self::getArg( "q", AT_int, true );
        $r = self::getArg( "r", AT_int, true );
        $side = self::getArg( "side", AT_enum, true, "CLeft", ["CLeft", "CRight"] );

        $this->game->playCard(new PlayCard_PlayHelicopter(self::newCrossroad($q,$r,$side)));

        self::ajaxResponse(); 
    } 
    public function playBribe()
    {
        self::setAjaxMode();

        $q = self::getArg( "q", AT_int, true );
        $r = self::getArg( "r", AT_int, true );

        $this->game->playCard(new PlayCard_PlayBribe(new Parcel(new Axe($q,$r))));

        self::ajaxResponse(); 
    } 

    public function endTurn()
    {
        self::setAjaxMode();


        $this->game->endTurn();

        self::ajaxResponse();
    }


    public function undo()
    {
        self::setAjaxMode();


        $this->game->undo();

        self::ajaxResponse();
    }
    public function discard()
    {
        self::setAjaxMode();

        $card = self::getArg( "card", AT_enum, true, "", ["Nitro1", "Nitro2", "Rut", "HayBale1", "HayBale2", "Dynamite", "HighVoltage", "Watchdog", "Helicopter", "Bribe"] );

        switch($card)
        {
            case 'Nitro1': 
                $card = new Card_Nitro(new CardPower_One());
                break;
            case 'Nitro2': 
                $card = new Card_Nitro(new CardPower_Two());
                break;
            case 'Rut': 
                $card =  new Card_Rut();
            break;
            case 'HayBale1': 
                $card = new Card_HayBale(new CardPower_One());
                break;
            case 'HayBale2': 
                $card = new Card_HayBale(new CardPower_Two());
                break;
            case 'Dynamite': 
                $card = new Card_Dynamite();
                break;
            case 'HighVoltage': 
                $card = new Card_HighVoltage();
                break;
            case 'Watchdog': 
                $card = new Card_Watchdog();
                break;
            case 'Helicopter': 
                $card = new Card_Helicopter();
                break;
            case 'Bribe': 
                $card = new Card_Bribe();
                break;
        }

        $this->game->discard($card);

        self::ajaxResponse(); 
    }  
  }
  

