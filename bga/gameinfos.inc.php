<?php

/*
    From this file, you can edit the various meta-information of your game.

    Once you modified the file, don't forget to click on "Reload game informations" from the Control Panel in order in can be taken into account.

    See documentation about this file here:
    http://en.doc.boardgamearena.com/Game_meta-information:_gameinfos.inc.php

*/

$gameinfos = array( 

// Name of the game in English (will serve as the basis for translation) 
'game_name' => "Crazy Farmers",

// Game designer (or game designers, separated by commas)
'designer' => 'Jérémie Chassaing / Quentin de Cagny',       

// Game artist (or game artists, separated by commas)
'artist' => 'Cha',         

// Year of FIRST publication of this game. Can be negative.
'year' => 2020,                 

// Game publisher
'publisher' => 'The Freaky 42',                     

// Url of game publisher website
'publisher_website' => 'https://www.facebook.com/TheFreaky42/',   

// Board Game Geek ID of the publisher
'publisher_bgg_id' => 40187,

// Board game geek ID of the game
'bgg_id' => 302238,


// Players configuration that can be played (ex: 2 to 4 players)
'players' => array( 2,3,4 ),    

// Suggest players to play with this number of players. Must be null if there is no such advice, or if there is only one possible player configuration.
'suggest_player_number' => 2,

// Discourage players to play with these numbers of players. Must be null if there is no such advice.
'not_recommend_player_number' => null,
// 'not_recommend_player_number' => array( 2, 3 ),      // <= example: this is not recommended to play this game with 2 or 3 players

// Estimated game duration, in minutes (used only for the launch, afterward the real duration is computed)
'estimated_duration' => 30,           

// Time in second add to a player when "giveExtraTime" is called (speed profile = fast)
'fast_additional_time' => 30,           

// Time in second add to a player when "giveExtraTime" is called (speed profile = medium)
'medium_additional_time' => 40,           

// Time in second add to a player when "giveExtraTime" is called (speed profile = slow)
'slow_additional_time' => 50,           

// If you are using a tie breaker in your game (using "player_score_aux"), you must describe here
// the formula used to compute "player_score_aux". This description will be used as a tooltip to explain
// the tie breaker to the players.
// Note: if you are NOT using any tie breaker, leave the empty string.
//
// Example: 'tie_breaker_description' => totranslate( "Number of remaining cards in hand" ),
'tie_breaker_description' => totranslate("Total size of the field including fallow lands"),

// If in the game, all losers are equal (no score to rank them or explicit in the rules that losers are not ranked between them), set this to true 
// The game end result will display "Winner" for the 1st player and "Loser" for all other players
'losers_not_ranked' => false,

// Game is "beta". A game MUST set is_beta=1 when published on BGA for the first time, and must remains like this until all bugs are fixed.
'is_beta' => 0,                     

// Is this game cooperative (all players wins together or loose together)
'is_coop' => 0, 


// Complexity of the game, from 0 (extremely simple) to 5 (extremely complex)
'complexity' => 2,    

// Luck of the game, from 0 (absolutely no luck in this game) to 5 (totally luck driven)
'luck' => 1,    

// Strategy of the game, from 0 (no strategy can be setup) to 5 (totally based on strategy)
'strategy' => 4,    

// Diplomacy of the game, from 0 (no interaction in this game) to 5 (totally based on interaction and discussion between players)
'diplomacy' => 3,    

// Colors attributed to players
'player_colors' => array( "AEDBDE", "EFC54C", "A87BBE", "EA222F"),

// Favorite colors support : if set to "true", support attribution of favorite colors based on player's preferences (see reattributeColorsBasedOnPreferences PHP method)
'favorite_colors_support' => true,

// When doing a rematch, the player order is swapped using a "rotation" so the starting player is not the same
// If you want to disable this, set this to false
'disable_player_order_swap_on_rematch' => false,

// Game interface width range (pixels)
// Note: game interface = space on the left side, without the column on the right
'game_interface_width' => array(

    // Minimum width
    //  default: 740
    //  maximum possible value: 740 (ie: your game interface should fit with a 740px width (correspond to a 1024px screen)
    //  minimum possible value: 320 (the lowest value you specify, the better the display is on mobile)
    'min' => 740,

    // Maximum width
    //  default: null (ie: no limit, the game interface is as big as the player's screen allows it).
    //  maximum possible value: unlimited
    //  minimum possible value: 740
    'max' => null
),

// Game presentation
// Short game presentation text that will appear on the game description page, structured as an array of paragraphs.
// Each paragraph must be wrapped with totranslate() for translation and should not contain html (plain text without formatting).
// A good length for this text is between 100 and 150 words (about 6 to 9 lines on a standard display)
'presentation' => array(

    totranslate("Crazy Farmers And The Clôtures Electriques is a strong emerging strategy game (2p)
which tends to be more lightweight and chaotic in 3-4p (better around a table with friends and a good drink in this mode).
In any case it’s a game with a pinch of « take that » and cutthroat maneuvers 
where you’ll get to experience the best UFC games ever. As you may wonder, UFC stands
for Ultimate Farming Championship. In 2042, the European Union has long since cut off
financial aid to farmers. In order to get out of this situation, farmers created a competition for
the installation of electric fences, which very quickly attracted the attention of the public and
sponsors."),
    totranslate("So now is the time for you to enter the arena and become the best electric fence installer.
During your turn, move your tractor along the paths on the board. Place electric fences
behind you. As soon as you surround a plot with your fences, take control of it. As soon as
you control a certain number of plots (depending on the number of players), you win the
game. However, it will not be that simple. You will need to be on the lookout, as your
opponents will try to stop you by cutting your electric fences or playing event cards...")
),

// Games categories
//  You can attribute a maximum of FIVE "tags" for your game.
//  Each tag has a specific ID (ex: 22 for the category "Prototype", 101 for the tag "Science-fiction theme game")
//  Please see the "Game meta information" entry in the BGA Studio documentation for a full list of available tags:
//  http://en.doc.boardgamearena.com/Game_meta-information:_gameinfos.inc.php
//  IMPORTANT: this list should be ORDERED, with the most important tag first.
//  IMPORTANT: it is mandatory that the FIRST tag is 1, 2, 3 and 4 (= game category)
'tags' => array( 3, 11, 30, 105, 206),


//////// BGA SANDBOX ONLY PARAMETERS (DO NOT MODIFY)

// simple : A plays, B plays, C plays, A plays, B plays, ...
// circuit : A plays and choose the next player C, C plays and choose the next player D, ...
// complex : A+B+C plays and says that the next player is A+B
'is_sandbox' => false,
'turnControl' => 'simple',

////////

// 'custom_buy_button' => array(
//     'url' => 'https://www.thylgames.fr/fr/99-the-freaky42-games',
//     'label' => totranslate('Buy Online')
//  )

'custom_buy_button' => array(
    'url' => 'https://www.philibertnet.com/fr/the-freaky-42/92577-crazy-farmers-and-the-clotures-electriques-3760250841910.html',
    'label' => totranslate('Philibert')
 )
);
