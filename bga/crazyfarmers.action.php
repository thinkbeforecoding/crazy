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
    

    public function endTurn()
    {
        self::setAjaxMode();


        $this->game->endTurn();

        self::ajaxResponse();
    }
    
  }
  

