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
 * states.inc.php
 *
 * CrazyFarmers game states description
 *
 */

/*
   Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
   in a very easy way from this configuration file.

   Please check the BGA Studio presentation about game state to understand this, and associated documentation.

   Summary:

   States types:
   _ activeplayer: in this type of state, we expect some action from the active player.
   _ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
   _ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
   _ manager: special type for initial and final state

   Arguments of game states:
   _ name: the name of the GameState, in order you can recognize it on your own code.
   _ description: the description of the current game state is always displayed in the action status bar on
                  the top of the game. Most of the time this is useless for game state with "game" type.
   _ descriptionmyturn: the description of the current game state when it's your turn.
   _ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
   _ action: name of the method to call when this game state become the current game state. Usually, the
             action method is prefixed by "st" (ex: "stMyGameStateName").
   _ possibleactions: array that specify possible player actions on this step. It allows you to use "checkAction"
                      method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
   _ transitions: the transitions are the possible paths to go from a game state to another. You must name
                  transitions in order to use transition names in "nextState" PHP method, and use IDs to
                  specify the next game state for each transition.
   _ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
           client side to be used on "onEnteringState" or to set arguments in the gamestate description.
   _ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
                            method).
*/

//    !! It is not a good idea to modify this file when a game is running !!

 
$machinestates = array(

    // The initial state. Please do not modify.
    1 => array(
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => array( "" => 2 )
    ),
    
    // Note: ID=2 => your first state
    2 => [
        "name" => "selectFirstCrossroad",
        "description" => clienttranslate('${actplayer} must select a crossroad around his field from where to start' ),
        "descriptionmyturn" => clienttranslate('${you} must select a crossroad around your field from where to start' ),
        "type" => "activeplayer",
        "possibleactions" => array("selectFirstCrossroad", "undo"),
        "transitions" => ["selectFirstCrossroad" => 3, "zombiepass" => 11, "endGame" => 99 ]
 

    ],

    3 => [
    		"name" => "playerTurn",
    		"description" => clienttranslate('${actplayer} must move or play a card'),
    		"descriptionmyturn" => clienttranslate('${you} must move or play a card'),
    		"type" => "activeplayer",
    		"possibleactions" => [ "move", "playCard", "next",  "undo", "discard", "endGame" ],
            "updateGameProgression" => true,
    		"transitions" => [ "canMove" => 3, "next" => 10 , "endTurn" => 4, "restart" => 2, "shouldDiscard" => 5, "zombiepass" => 11, "endGame" => 99 ]
    ], 

    4 => [
        "name" => "endTurn",
    	"description" => clienttranslate('${actplayer} must play a card or end turn'),
    	"descriptionmyturn" => clienttranslate('${you} must play a card or end turn'),
    	"type" => "activeplayer",
        "possibleactions" => [ "playCard", "next", "move", "discard", "undo" ],
        "updateGameProgression" => true,
    	"transitions" => [ "canMove" => 3, "shouldDiscard" => 5, "next" => 10, "restart" => 2, "endTurn" => 4, "zombiepass" => 11,  "endGame" => 99 ]
    ],
    5 => [
        "name" => "discard",
    	"description" => clienttranslate('${actplayer} must play or discard a card to have max 6 cards'),
    	"descriptionmyturn" => clienttranslate('${you} must play or discard a card to have max 6 cards'),
    	"type" => "activeplayer",
        "possibleactions" => [ "playCard", "next", "move", "discard", "undo", ],
        "updateGameProgression" => true,
    	"transitions" => [ "canMove" => 3, "shouldDiscard" => 5, "next" => 10,  "endTurn" => 4, "zombiepass" => 11, "endGame" => 99 ]
    ],
    10 => [
        "name" => "next",
        "description" => '',
        "type" => "game",
        "action" => "stNextPlayer",
        "updateGameProgression" => true,   
        "transitions" => ["nextPlayer" => 3, "nextStarting" => 2, "nextEndTurn" => 4 ]
    ],
    11 => [
        "name" => "zombie",
        "description" => '',
        "type" => "game",
        "action" => "stZombie",
        "updateGameProgression" => true,   
        "transitions" => ["nextPlayer" => 3, "nextStarting" => 2, "nextEndTurn" => 4, "endGame" => 99 ]

    ],
    
/*
    Examples:
    
    2 => array(
        "name" => "nextPlayer",
        "description" => '',
        "type" => "game",
        "action" => "stNextPlayer",
        "updateGameProgression" => true,   
        "transitions" => array( "endGame" => 99, "nextPlayer" => 10 )
    ),
    
    10 => array(
        "name" => "playerTurn",
        "description" => clienttranslate('${actplayer} must play a card or pass'),
        "descriptionmyturn" => clienttranslate('${you} must play a card or pass'),
        "type" => "activeplayer",
        "possibleactions" => array( "playCard", "pass" ),
        "transitions" => array( "playCard" => 2, "pass" => 2 )
    ), 

*/    
   

    // Final state.
    // Please do not modify (and do not overload action/args methods).
    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )

);



