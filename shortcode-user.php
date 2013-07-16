<?php

/*
 *
 * User Page Shortcode
 *
 */

function cpjr3_user_page_shortcode() {

	$help = new CPJR3_Helpers();

	//Start by gathering Vars

	$time = current_time( 'timestamp', 1 );

	//Get current user
	$current_user = wp_get_current_user();

		$username = $current_user->user_login;

		$user_id =  $current_user->ID;

	$score = get_user_meta( $user_id, 'cpjr3_score', true );

	$events = get_user_meta( $user_id, 'cpjr3_events', true );

	//Print Header

	echo sprintf( __( '<h3>Points Totals for %s</h3>', 'cpjr3' ), $username );

	//Total Points Earned

	echo sprintf( _n( '<p><strong>Total Points:</strong> %s</p>', $score, 'cpjr3' ), $score );

	//Calculate Daily Points Total

	$todays_points = $help->calc_daily_points( $user_id, $time );

	echo sprintf( _n( '<p><strong>Points Earned in Last 24 Hours:</strong> %s', $todays_points, 'cpjr3' ), $todays_points );

	//Recent Points Earned

	_e( '<p><strong>Recent Activity</strong></p>', 'cpjr3' );

	if ( $events) {

		$recent_events = array_reverse( $events, true );

		$recent_events = array_slice( $recent_events, 0, 10, true );

		echo '<ul>';

		foreach ( $recent_events as $value => $key ) {

			$offset = human_time_diff( $value, $time );

			// @todo i18n the strings here using _n()
			if( isset( $key['reward'] ) ) {

				echo '<li>' . $key['action'] . ' for ' . $key['points'] . ' points (' . $offset . ' ago)</li>';

			} else {

				echo '<li>' . $key['points'] . ' points for ' . $key['action'] . ' (' . $offset . ' ago)</li>';

			}

		}

		echo '</ul>';

	}// if $events

} //end cpjr3_user_page_shortcode

add_shortcode( 'click-points', 'cpjr3_user_page_shortcode' );
