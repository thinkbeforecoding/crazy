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
 * stats.inc.php
 *
 * CrazyFarmers game statistics description
 *
 */

/*
    In this file, you are describing game statistics, that will be displayed at the end of the
    game.
    
    !! After modifying this file, you must use "Reload  statistics configuration" in BGA Studio backoffice
    ("Control Panel" / "Manage Game" / "Your Game")
    
    There are 2 types of statistics:
    _ table statistics, that are not associated to a specific player (ie: 1 value for each game).
    _ player statistics, that are associated to each players (ie: 1 value for each player in the game).

    Statistics types can be "int" for integer, "float" for floating point values, and "bool" for boolean
    
    Once you defined your statistics there, you can start using "initStat", "setStat" and "incStat" method
    in your game logic, using statistics names defined below.
    
    !! It is not a good idea to modify this file when a game is running !!

    If your game is already public on BGA, please read the following before any change:
    http://en.doc.boardgamearena.com/Post-release_phase#Changes_that_breaks_the_games_in_progress
    
    Notes:
    * Statistic index is the reference used in setStat/incStat/initStat PHP method
    * Statistic index must contains alphanumerical characters and no space. Example: 'turn_played'
    * Statistics IDs must be >=10
    * Two table statistics can't share the same ID, two player statistics can't share the same ID
    * A table statistic can have the same ID than a player statistics
    * Statistics ID is the reference used by BGA website. If you change the ID, you lost all historical statistic data. Do NOT re-use an ID of a deleted statistic
    * Statistic name is the English description of the statistic as shown to players
    
*/

$stats_type = array(

    // Statistics global to table
    "table" => array(

        "turns_number" => array("id"=> 10,
                    "name" => totranslate("Number of turns"),
                    "type" => "int" ),

        "fences_drawn" => ["id" => 11,
                    "name" => totranslate("Fences drawn"),
                    "type" => "int"],
    
        "fences_cut" => ["id" => 12,
                    "name" => totranslate("Fences cut"),
                    "type" => "int"],
        "takeovers_number" => ["id" => 13,
                    "name" => totranslate("Number of takeovers"),
                    "type" => "int"],
        "biggest_takeover" => ["id" => 14,
                    "name" => totranslate("Biggest takeover"),
                    "type" => "int"],
        "freebarns_number" => ["id" => 15,
                    "name" => totranslate("Number of free barns taken"),
                    "type" => "int"],
        "occupiedbarns_number" => ["id" => 16,
                    "name" => totranslate("Number of occupied barns taken"),
                    "type" => "int"],
        "cardsplayed_number" => ["id" => 17,
                    "name" => totranslate("Number of cards played"),
                    "type" => "int"],
        "haybales_number" => ["id" => 18,
                    "name" => totranslate("Number of Hay bales played"),
                    "type" => "int"],
        "dynamites_number" => ["id" => 19,
                    "name" => totranslate("Number Dynamites played"),
                    "type" => "int"],
        "haybales_max_number" => ["id" => 20,
                    "name" => totranslate("Maximum number of hay bales"),
                    "type" => "int"],
        "haybales_moved_number" => ["id" => 21,
                    "name" => totranslate("Number of hay bales moved"),
                    "type" => "int"],
        "helicopters_number" => ["id" => 22,
                    "name" => totranslate("Number of Helicopters played"),
                    "type" => "int"],
        "highvoltages_number" => ["id" => 23,
                    "name" => totranslate("Number of High Voltages played"),
                    "type" => "int"],
        "watchdogs_number" => ["id" => 24,
                    "name" => totranslate("Number of Watchdogs played"),
                    "type" => "int"],
        "bribes_number" => ["id" => 25,
                    "name" => totranslate("Number of Bribes played"),
                    "type" => "int"],
        "nitro1_number" => ["id" => 26,
                    "name" => totranslate("Number of Nitro +1 played"),
                    "type" => "int"],
        "nitro2_number" => ["id" => 27,
                    "name" => totranslate("Number of Nitro +2 played"),
                    "type" => "int"],
        "ruts_number" => ["id" => 28,
                    "name" => totranslate("Number of Ruts played"),
                    "type" => "int"],
/*
        Examples:


        "table_teststat1" => array(   "id"=> 10,
                                "name" => totranslate("table test stat 1"), 
                                "type" => "int" ),
                                
        "table_teststat2" => array(   "id"=> 11,
                                "name" => totranslate("table test stat 2"), 
                                "type" => "float" )
*/  
    ),
    
    // Statistics existing for each player
    "player" => array(

    
        "fences_drawn" => [ "id" => 10,
                    "name" => totranslate("Fences drawn"),
                    "type" => "int" ],

        "fences_cut" => ["id" => 11,
                    "name" => totranslate("Number of fences cut"),
                    "type" => "int"],
        "cut_number" => ["id" => 12,
                    "name" => totranslate("Number of time you've been cut"),
                    "type" => "int"],
        "takeovers_number" => ["id" => 13,
                    "name" => totranslate("Number of takeovers"),
                    "type" => "int"],
        "biggest_takeover" => ["id" => 14,
                    "name" => totranslate("Biggest takeover"),
                    "type" => "int"],
        "freebarns_number" => ["id" => 15,
                    "name" => totranslate("Number of free barns taken"),
                    "type" => "int"],
        "occupiedbarns_number" => ["id" => 16,
                    "name" => totranslate("Number of occupied barns taken"),
                    "type" => "int"],
        "cardsplayed_number" => ["id" => 17,
                    "name" => totranslate("Number of cards played"),
                    "type" => "int"],
        "haybales_number" => ["id" => 18,
                    "name" => totranslate("Number of Hay bales played"),
                    "type" => "int"],
        "dynamites_number" => ["id" => 19,
                    "name" => totranslate("Number Dynamites played"),
                    "type" => "int"],
        "helicopters_number" => ["id" => 22,
                    "name" => totranslate("Number of Helicopters played"),
                    "type" => "int"],
        "highvoltages_number" => ["id" => 23,
                    "name" => totranslate("Number of High Voltages played"),
                    "type" => "int"],
        "watchdogs_number" => ["id" => 24,
                    "name" => totranslate("Number of Watchdogs played"),
                    "type" => "int"],
        "bribes_number" => ["id" => 25,
                    "name" => totranslate("Number of Bribes played"),
                    "type" => "int"],
        "nitro1_number" => ["id" => 26,
                    "name" => totranslate("Number of Nitro +1 played"),
                    "type" => "int"],
        "nitro2_number" => ["id" => 27,
                    "name" => totranslate("Number of Nitro +2 played"),
                    "type" => "int"],
        "ruts_number" => ["id" => 28,
                    "name" => totranslate("Number of Ruts played"),
                    "type" => "int"],
        "rutted_number" => ["id" => 29,
                    "name" => totranslate("Number of Ruts played against you"),
                    "type" => "int"],

                
/*
        Examples:    
        
        
        "player_teststat1" => array(   "id"=> 10,
                                "name" => totranslate("player test stat 1"), 
                                "type" => "int" ),
                                
        "player_teststat2" => array(   "id"=> 11,
                                "name" => totranslate("player test stat 2"), 
                                "type" => "float" )

*/    
    )

);
