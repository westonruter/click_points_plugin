<?php

/*
 *
 * Process Widget Points Form
 *
 */

add_action( 'wp_ajax_cpjr3_process', 'cpjr3_process' );

add_action( 'wp_ajax_nopriv_cpjr3_process', 'cpjr3_must_login' );

function cpjr3_process() {

   $help = New Helpers;

   if ( !wp_verify_nonce( $_REQUEST['nonce'], 'cpjr3_widget_nonce') ) {

      exit( "Failed nonce verification." );

   }

   //get variables

   $user_id = $_REQUEST['user_id'];

   $points = $_REQUEST['points'];

   $event = $_REQUEST['events'];

   //generate random noun

      $nouns_array = array(
         "Unicorns", "the 2013 Buggati", "Cockapoos", "Pimento Loaf", "Clean Socks",
         "Hans Liszt", "Fresh Linen", "Asian Delicacies", "Raquel Welch", "Jan Brady", "Wayne Brady",
         "Commemorative Coins", "Drakkar Noir", "Convection Oven Safety", "Cranky Babies", "Selena Gomez Tickets", "Cream of Chicken Soup",
         "Exhaust Manifolds", "Art Garfunkel", "Kate Upton's Insoles", "Narcotics", "'Get Lucky' by Daft Punk", "Oodles of Noodles", "Pogs",
         "Chubby Checker", "Interrobangs", "O.P.P.", "Moist Towelettes", "Two Turtle Doves", "Sex Panther by Odeon", "The Holy Hand Grenade", "Friday the 13th",
         "Vampire Weekend", "Chicken Lips", "Weston Ruter", "Frankie Jarrett", "Fortune Cookies", "Russian Novelists", "Marxist Culture", "The Commonwealth of Virgina", "The Dude",
         "Courtney P. Vance", "Old Spice Commercials", "Turducken", "Insomnia", "Malaise", "Rusty Screwdrivers", "Simple Machines", "Waif Memes",
         "The Truffle Shuffle", "Flux Capacitors", "TARDIS", "Magnetic Bumper Stickers", "Grumpy Cat", "European Football", "Pepper Gravy", "Deep-Fried Confections"
      );

      // insert filter in case someone wants to edit the list of rewards

      $nouns_array = apply_filters( 'nouns_list', $nouns_array );

      //call this "action" for clarity later on...

      $action = $help->random_silliness( $nouns_array );

   //Add Points to User's Total Score

   $total_score = get_user_meta( $user_id, 'cpjr3_score', true );

   $total_score = ( $total_score == '' ) ? 0 : $total_score;

   $new_score = $total_score + $points;

   //save new score and events to user meta

   $result = $help->save_process_results( $user_id, $event, $action, $points, false, $new_score );

   //json encode

   $json_result = $help->json_encode_result( $result );

   echo $json_result;

   die();

}

function cpjr3_must_login() {

   echo "Want to earn points?  Log in or Sign up!";

   die();

}

/* End Process Widget Points Form */