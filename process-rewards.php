<?php

/*
 *
 * Process Widget Points Form
 *
 */

add_action( 'wp_ajax_cpjr3_rewards_process', 'cpjr3_rewards_process' );

add_action( 'wp_ajax_nopriv_cpjr3_rewards_process', 'cpjr3_rewards_must_login' );

function cpjr3_rewards_process() {

   $help = New Helpers;

   if ( !wp_verify_nonce( $_REQUEST['nonce_rw'], 'cpjr3_rewards_nonce') ) {

      exit( "Failed nonce verification." );

   }

   // get vars

   $user_id = $_REQUEST['user_id_rw'];

   $event = $_REQUEST['events_rw'];

   $action = $_REQUEST['reward_rw'];

   $points = $_REQUEST['points_rw'];

   //Check to see if user can afford requested item

   $total_score = get_user_meta( $user_id, 'cpjr3_score', true );

   $total_score = ( $total_score == '' ) ? 0 : $total_score;

   $negative_points = -1 * abs($points);

   if ( ($total_score + $negative_points ) < 0 ) {

      //NSF = "Insufficient Funds"

      $result['nsf'] = 'NSF';

      //Gotta throw these in there to prevent errors on the front end

      $result['total_score']['type'] = '';

      $result['events']['type'] = '';

   } else {

      $new_score = $total_score + $negative_points;

      //save new score and events to user meta

      $result = $help->save_process_results( $user_id, $event, $action, $points, true, $new_score );

   }// end check to see if user has enough points for reward

   //json encode

   $json_result = $help->json_encode_result( $result );

   echo $json_result;

   die();

}

function cpjr3_rewards_must_login() {

   echo "Want to earn points?  Log in or Sign up!";

   die();

}

/* End Process Widget Points Form */
