<?php
 /**
  *------
  * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
  * CrazyFarmers implementation : © <Your name here> <Your email address here>
  * 
  * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
  * See http://en.boardgamearena.com/#!doc/Studio for more information.
  * -----
  * 
  * crazyfarmers.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );
require_once('modules/FSharp.Core.php');
require_once('modules/crazyfarmers.php');
require_once('modules/Serialization.php');

class CrazyFarmers extends Table
{
	function __construct( )
	{
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();
        
        self::initGameStateLabels( array( 
            "board" => 10,
            "game_mode" => 100
            //    "my_second_global_variable" => 11,
            //      ...
            //    "my_first_game_variant" => 100,
            //    "my_second_game_variant" => 101,
            //      ...
        ) );        
	}
	
    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "crazyfarmers";
    }	

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    static function get_Color($color)
    {
        static $colors = NULL;
        if (is_null($colors)) {
            $colors =
                [ "AEDBDE" => new Color_Blue(),
                "EFC54C" => new Color_Yellow(),
                "A87BBE" => new Color_Purple(),
                "EA222F" => new Color_Red()
        ]; };
        return $colors[$color];
    }

    protected function setupNewGame( $players, $options = array() )
    {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar, player_score, player_score_aux) VALUES ";
        $values = array();
        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."',1,1)";
        }
        $sql .= implode( $values, ',' );
        self::DbQuery( $sql );
        self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();

        $crazyPlayers = array();
        foreach(self::loadPlayersBasicInfos() as $player_id => $player) {
            $crazyPlayers[$player['player_no']] = [ CrazyFarmers::get_Color($player['player_color']), strval($player_id), $player['player_name']  ];
        }
        


        /************ Start the game initialization *****/

        // Init global values with their initial values
        //self::setGameStateInitialValue( 'my_first_global_variable', 0 );
        
        // Init game statistics
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        //self::initStat( 'table', 'table_teststat1', 0 );    // Init a table statistics
        //self::initStat( 'player', 'player_teststat1', 0 );  // Init a player statistics (for all players)

        // TODO: setup the initial game situation here

        self::initStat( "table", "turns_number", 1);
        self::initStat( "table", "fences_drawn", 0);
        self::initStat( "table", "fences_cut", 0);
        self::initStat( "table", "takeovers_number", 0);
        self::initStat( "table", "biggest_takeover", 0);
        self::initStat( "table", "freebarns_number", 0);
        self::initStat( "table", "occupiedbarns_number", 0);
        self::initStat( "table", "cardsplayed_number", 0);
        self::initStat( "table", "haybales_number", 0);
        self::initStat( "table", "dynamites_number", 0);
        self::initStat( "table", "haybales_max_number", 0);
        self::initStat( "table", "haybales_moved_number", 0);
        self::initStat( "table", "helicopters_number", 0);
        self::initStat( "table", "highvoltages_number", 0);
        self::initStat( "table", "watchdogs_number", 0);
        self::initStat( "table", "bribes_number", 0);
        self::initStat( "table", "nitro1_number", 0);
        self::initStat( "table", "nitro2_number", 0);
        self::initStat( "table", "ruts_number", 0);

        self::initStat( "player", "fences_drawn", 0);
        self::initStat( "player", "fences_cut", 0);
        self::initStat( "player", "cut_number", 0);
        self::initStat( "player", "takeovers_number", 0);
        self::initStat( "player", "biggest_takeover", 0);
        self::initStat( "player", "freebarns_number", 0);
        self::initStat( "player", "occupiedbarns_number", 0);
        self::initStat( "player", "cardsplayed_number", 0);
        self::initStat( "player", "haybales_number", 0);
        self::initStat( "player", "dynamites_number", 0);
        self::initStat( "player", "helicopters_number", 0);
        self::initStat( "player", "highvoltages_number", 0);
        self::initStat( "player", "watchdogs_number", 0);
        self::initStat( "player", "bribes_number", 0);
        self::initStat( "player", "nitro1_number", 0);
        self::initStat( "player", "nitro2_number", 0);
        self::initStat( "player", "ruts_number", 0);
        self::initStat( "player", "rutted_number", 0);

        switch ($this->gamestate->table_globals[100])
        {
            case 1:
                $goalType = new GoalType_Fast();
            break;
            case 3:
                $goalType = new GoalType_Expert();
            break;
            default:
                $goalType = new GoalType_Regular();
        }




        $goal = Shared_002EGoalModule___fromType(count($players), $goalType);

        $cmd = new BoardCommand_Start(
                new BoardStart(
                        FSharpList::ofArray($crazyPlayers),
                        $goal));
        $board = new Board_InitialState();
        $es = Shared_002EBoardModule___decide($cmd, $board);
        
        self::saveEvents($es);
        
        
        $board = self::fold($board, $es);


        self::setGameStateInitialValue( 'board', json_encode(convertToJson($board)) );

        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas()
    {
        $result = array();
    
        $current_player_id = strval(self::getCurrentPlayerId());    // !! We must only return informations visible by this player !!
    
        
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_score score FROM player ";
        $result['players'] = self::getCollectionFromDb( $sql );
  
        $r = self::loadState();
        $board = $r[1];
        $pboard = SharedServer___privateBoard($current_player_id, $board);



        $result['board'] = convertToSimpleJson(Shared_002EBoardModule___toState($pboard));
        $result['version'] = $r[0];

        // TODO: Gather all information about current game situation (visible by player $current_player_id).
  
        return $result;
    }

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression()
    {
        $r = self::loadState();

        return SharedServer___bgaProgression($r[1]);
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    function fold($board, $es)
    {
        return FSharpList::fold('Shared_002EBoardModule___evolve', $board, $es);
    }

    function saveEvent($e)
    {
        $je = convertToJson($e);

        $type = $je['_case'];
        $body = json_encode($je['fields']);

        $sql = "INSERT INTO `Events` (`type`, `body`) VALUES ('".$type."','".addslashes($body)."')";
        self::DbQuery( $sql );
    }


    function saveEvents($es)
    {
        foreach($es as $e)
            self::saveEvent($e);
        return self::DbGetLastId();
    }

    function loadEvents()
    {
        $r = self::getCollectionFromDb("SELECT `id`, `type`,`body` FROM `Events` ORDER BY `id`");

        foreach($r as $row) {
            
            $js = (object)[ '_case' => $row['type'],
                            'fields' => json_decode($row['body'])];

            $e = convertFromJson($js);

            yield [$row['id'], $e];
        }
    }

    function loadState()
    {
        $s = new Board_InitialState();
        $v = 0;
        foreach(self::loadEvents() as $r)
        {
            $v = $r[0];
            $e = $r[1];
            $s = Shared_002EBoardModule___evolve($s,$e);
        }

        return [$v,$s];
    }



//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
//////////// 

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in crazyfarmers.action.php)
    */

    /*
    
    Example:

    function playCard( $card_id )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'playCard' ); 
        
        $player_id = self::getActivePlayerId();
        
        // Add your game logic to play a card there 
        ...
        
        // Notify all players about the card played
        self::notifyAllPlayers( "cardPlayed", clienttranslate( '${player_name} plays ${card_name}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'card_name' => $card_name,
            'card_id' => $card_id
        ) );
          
    }
    
    */
    function playCommand($playerCmd)
    {
        $player_id = self::getActivePlayerId();
        $state = self::loadState();
        $version = $state[0];
        $board = $state[1];
     
        $cmd = new BoardCommand_Play($player_id, $playerCmd);
        $es = Shared_002EBoardModule___decide($cmd, $board);


        $newVersion = self::saveEvents($es);
        $board = self::fold($board, $es);

        self::updateScore($es,$board);
        SharedServer___updateStats($es, function($d,$n,$p) { self::incStat($d,$n,$p); }, 
            function($f,$n,$p) {
                $current = self::getStat($n,$p);
                $newValue = $f($current);
                self::setStat($newValue,$n,$p); },
            function($n,$p) {
                return self::getStat($n,$p);
            }
    );

        if (!empty($es))
        {


            $args = [];
            $args['version'] = $newVersion;
            foreach(self::loadPlayersBasicInfos() as $id => $p)
            {
                $privateEs = SharedServer___privateEvents((string)$id, $es);
                $args['events'] = convertToSimpleJson($privateEs);
                self::notifyPlayer($id, "events", "", $args );
            }

            $privateEs = SharedServer___privateEvents("", $es);
            $args['events'] = convertToSimpleJson($privateEs);
            self::notifyAllPlayers( "specatorEvents", "", $args);

            foreach ($es as $e)
            {
                $result = SharedServer___textAction($board, $e);
                $text = $result[0];
                if (!is_null($text))
                {
                    $args = [];
                    foreach(Map::toSeq($result[1]) as $kv)
                    {
                        $args[$kv[0]] = $kv[1];
                    }
                    self::notifyAllPlayers("messages", $text, $args );
                }
            }
        }
        self::updateState($es,$board);

    }

    function updateScore($es, $board)

    {
        SharedServer___bgaScore($es, $board, function ($args) 
        {
            $pid = $args[0];
            $score = $args[1];
            $scoreAux = $args[2];
            self::DbQuery( "UPDATE player SET player_score=$score, player_score_aux=$scoreAux WHERE player_id='$pid'" );
        });
    }

    function selectFirstCrossroad($crossroad)
    {
        self::checkAction('selectFirstCrossroad');
        $player_id = self::getActivePlayerId();
        $cmd =  new Command_SelectFirstCrossroad(new SelectFirstCrossroad($crossroad));

        self::playCommand($cmd);
    }

    
    function move($dir, $destination)
    {
        self::checkAction('move');
        $player_id = self::getActivePlayerId();
        $cmd = new Command_Move(new PlayerMove($dir, $destination));

        self::playCommand($cmd);

    }
    function playCard($card)
    {
        self::checkAction('playCard');
        $player_id = self::getActivePlayerId();
        $cmd = new Command_PlayCard($card);

        self::playCommand($cmd);
    }
    function discard($card)
    {
        self::checkAction('discard');
        $player_id = self::getActivePlayerId();
        $cmd = new Command_Discard($card);

        self::playCommand($cmd);
    }
    function endTurn()
    {
        self::checkAction('next');
        $player_id = self::getActivePlayerId();
        $cmd = new Command_EndTurn();

        self::playCommand($cmd);
    }

    function updateState($es,$board)
    {
        SharedServer___bgaUpdateState($es,$board, $this->gamestate->state()['name'], function($state) {$this->gamestate->nextState($state);});
    }
    
    function stNextPlayer()
    {
        $player_id = self::activeNextPlayer();
        $state = self::loadState();
        $version = $state[0];
        $board = $state[1];



        $s = SharedServer___bgaNextPlayer($board);

        $this->gamestate->nextState($s);
    }
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    /*
    
    Example for game state "MyGameState":
    
    function argMyGameState()
    {
        // Get some values from the current game situation in database...
    
        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }    
    */

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */
    
    /*
    
    Example for game state "MyGameState":

    function stMyGameState()
    {
        // Do some stuff ...
        
        // (very often) go to another gamestate
        $this->gamestate->nextState( 'some_gamestate_transition' );
    }    
    */

//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
    */

    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
    
///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */
    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }    
}
