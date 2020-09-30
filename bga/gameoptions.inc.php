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
 * gameoptions.inc.php
 *
 * CrazyFarmers game options description
 * 
 * In this file, you can define your game options (= game variants).
 *   
 * Note: If your game has no variant, you don't have to modify this file.
 *
 * Note²: All options defined in this file should have a corresponding "game state labels"
 *        with the same ID (see "initGameStateLabels" in crazyfarmers.game.php)
 *
 * !! It is not a good idea to modify this file when a game is running !!
 *
 */

$game_options = array(

    /*
    
    // note: game variant ID should start at 100 (ie: 100, 101, 102, ...). The maximum is 199.
    100 => array(
                'name' => totranslate('my game option'),    
                'values' => array(

                            // A simple value for this option:
                            1 => array( 'name' => totranslate('option 1') )

                            // A simple value for this option.
                            // If this value is chosen, the value of "tmdisplay" is displayed in the game lobby
                            2 => array( 'name' => totranslate('option 2'), 'tmdisplay' => totranslate('option 2') ),

                            // Another value, with other options:
                            //  description => this text will be displayed underneath the option when this value is selected to explain what it does
                            //  beta=true => this option is in beta version right now.
                            //  nobeginner=true  =>  this option is not recommended for beginners
                            3 => array( 'name' => totranslate('option 3'), 'description' => totranslate('this option does X'), 'beta' => true, 'nobeginner' => true )
                        )
            )

    */

    100 => array(
        'name' => totranslate('Game mode'),    
        'values' => array(
                    2 => array( 'name' => totranslate('Regular (27/11/9)'), 'description' => totranslate('25-50min (2P/3P/4P)'), 'tmdisplay' => totranslate ('Regular (27/11/9)')),

                    1 => array( 'name' => totranslate('Fast (23/9/8)'), 'description' => totranslate('15-30min (2P/3P/4P)'), 'tmdisplay' => totranslate('Fast (23/9/8)') ),

                    3 => array( 'name' => totranslate('Expert (31/13/11)'), 'description' => totranslate('40-90min (2P/3P/4P)'), 'nobeginner' => true, 'tmdisplay' => totranslate('Expert (31/13/11)') )
                )
        ),
    101 => array(
            'name' => totranslate('Undo mode'),    
            'values' => array(
                        1 => array( 'name' => totranslate('Full Undo (beginners)'), 'description' => totranslate('Undo to the start of the turn, even drawn cards')),
    
                        2 => array( 'name' => totranslate('Limited Undo'), 'description' => totranslate('Full Undo, except when bonus cards have just been drawn'), 'tmdisplay' => totranslate('Limited Undo') ),
    
                        3 => array( 'name' => totranslate('No Undo'), 'description' => totranslate('No Undo at all'), 'nobeginner' => true, 'tmdisplay' => totranslate('No Undo') )
                    )
            ),
    102 => array(
                'name' => totranslate('Game Over card'),    
                'values' => array(
                            1 => array( 'name' => totranslate('No'), 'description' => totranslate('Do not use the Game Over card ')),
        
                            2 => array( 'name' => totranslate('Yes'), 'description' => totranslate('Use the Game Over card'), 'tmdisplay' => totranslate('Game Over card') ),
                        )
            )
);


