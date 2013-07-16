<?php

Class Helpers {

	/**
	* Calculates Points earned during the last 24 hour period.
	* Used in shortcode-user.php
	*/

	public function calc_daily_points( $user_id, $time ) {

		$one_day_ago = $time - 86400;

		$daily_points_earned = 0;

		$events = get_user_meta( $user_id, 'cpjr3_events', true );

			if ( $events ) {

				foreach ( $events as $timestamp => $value ) {

					//if it occurred less than 24 hours ago

					if ( $timestamp >= $one_day_ago ) {

						//extract points values from Action Array

						$points = $value['points'];

						//if reward

						if ( isset( $value['reward'] ) ) {

							//make points negative

							$points = -1 * abs($points);

						}

						//Add to $daily_points_earned

						$daily_points_earned = $daily_points_earned + $points;

					} //endif

				} //end foreach

			} //end if $events

		return $daily_points_earned;

	} // End calc_daily_points




	/**
	* Randomizes array.
	* Used to get random Event Actions and Rewards Items
	*/

	public function random_silliness( $input_array) {

		$output_array = $input_array[array_rand( $input_array )];

	   	return $output_array;

	} //End random_silliness



	/**
	* Reorders array by key value
	* Used to order Rewards Items by price
	*/

	private function reorder_array( &$array, $key ) {

		//Re-sort $rewards_array so rewards appear in order of price
   		//From http://stackoverflow.com/questions/2699086/sort-multidimensional-array-by-value-2

		$sorter = array();

	    $ret = array();

	    reset ( $array );

	    foreach ( $array as $ii => $va ) {

	        $sorter[$ii] = $va[$key];

	    }

	    asort ( $sorter );

	    foreach ( $sorter as $ii => $va ) {

	        $ret[$ii] = $array[$ii];

	    }

	    $array = $ret;

	}



	/**
	* Creates array of Reward and Points pairs with a specific length.
	* Used to generate array of Rewards/Points in Rewards Shortcode.
	*/

	private function create_rewards_listing( $rewards_array, $length ) {

		//create an array, 9 long...

		$length = $length - 1;

	   	$i = 0;

	   	while ( $i >= 0 && $i <= $length ) {

	   		$rewards_listing[$i]['reward'] = $this->random_silliness( $rewards_array );

	   		$rewards_listing[$i]['amount'] = rand( 0, 1000 );

	   		$i++;

	   	} //wndwhile

	   	return $rewards_listing;

	} // End create_rewards_listing



	/**
	* Wraps up several methods to create Rewards
	*/

	public function generate_rewards( $rewards_array, $length, $key ) {

	   	// Create List of Reward/Point pairs

		$rewards_listing = $this->create_rewards_listing( $rewards_array, 9 );

		//Reorder List so lower Points Values show first

		$this->reorder_array( $rewards_listing, $key );

		return $rewards_listing;

	} //end generate_rewards



	/**
	* Saves Event data to User Meta
	* Used save events that occur when points are earned/redeemed
	*/

	private function save_event( $user_id, $event, $action, $points, $is_reward ) {

	   //Add Events to User's event meta

	   $events_array = get_user_meta( $user_id, 'cpjr3_events', true );

	   $time = current_time( 'timestamp', 1 );

	   $events_array[$time]['action'] = $event . " " . $action;

	   $latest_event = $events_array[$time]['action'];

	   $events_array[$time]['points'] = $points;

	   if ( $is_reward === true ) {

		   //designate this event as a reward
	       $events_array[$time]['reward'] = 'reward';

       }

	   $updated_events = update_user_meta( $user_id, 'cpjr3_events', $events_array );

	   if( $updated_events === false ) {

	      $event_result['events']['type'] = 'error';

	   }

	   else {

	      $event_result['events']['type'] = 'success';

	      $event_result['events']['event'] = $latest_event; //most recent event

	    }

	    return $event_result;

	} // save_events



	/**
	* Saves new total Score to User Meta
	* Used when points are earned/redeemed
	*/

	private function save_score( $user_id, $new_total_score, $points )  {

		$updated_score = update_user_meta( $user_id, 'cpjr3_score', $new_total_score );

		   if( $updated_score === false ) {

		      $score_result['total_score']['type'] = 'error';

		   } else {

		      $score_result['total_score']['type'] = 'success';

		      $score_result['total_score']['value'] = $points;

		   }

		return $score_result;

	}




	/**
	* Saves new Score and Event data
	* Used when points are earned/redeemed
	*/

	public function save_process_results( $user_id, $event, $action, $points, $is_reward, $new_score) {

		//save new score to user meta

		$score_result = $this->save_score( $user_id, $new_score, $points );

		///save events to user meta

		$events_result = $this->save_event( $user_id, $event, $action, $points, $is_reward );

		$result = array_merge($score_result, $events_result);

		return $result;

	}// end save_process_results



	/**
	* Encodes results of data processing
	*/

	public function json_encode_result( $result ) {

		if( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {

		      $json_result = json_encode( $result );

		      return $json_result;

		} else {

	      header( "Location: " . $_SERVER["HTTP_REFERER"] );

	   }

	} //end json_encode_result

} //End Class Helpers