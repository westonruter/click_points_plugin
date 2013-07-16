<?php

/*
 *
 * Rewards Shortcode
 *
 */

function cpjr3_rewards_shortcode() {

	$help = New Helpers;

	echo "<p>";

	esc_html_e( 'Click to purchase a Reward.  Results will show up on your Click Points User Page.', '$cpjr3' );

	echo "</p><p>";

	esc_html_e( 'Refresh the page to view more Rewards', '$cpjr3' );

	echo "</p>";

	//generate random reward and assign points value

	   	$rewards_array = array(
	   		"Ham & Eggs", "Eight-Day-Old Donuts", "Big Box of Wishes", "Creepy Backrub", "An Extra Value Meal",
	   		"Handful of Quiet", "Frankincense & Myrrh", "Kentucky Fried WTF", "Trapper Keeper", "Mr. T-shirt", "Pocket Full of Sunshine",
	   		"Commemorative Coins", "CK One", "Chicken Wopper", "Springsteen Poster", "Selena Gomez Tickets", "4 Fried Chickens & a Coke",
	   		"Self-Fulfilling Prophecy", "Perpetual Hope", "Skrillex Coffee Mug", "Jolt Cola", "Unpaid Parking Ticket", "Assorted Loose Legos", "Garbage Pail Kids",
	   		"Near-Mint Washcloths", "Grawlixes", "Wish Sandwich", "Dry, Hacking Cough", "Skin Infection", "Hepatitis C", "Super-Human Strength",
	   		"Near-Anonymity", "Chronic Flatulence", "Crazy Pills", "Hyperactive Colon", "Super Smelling", "Chocolatey Goodness", "Unfortunate Birthmark",
	   		"Infamy", "Boardwalk", "Marvin Gardens", "Foie Gras", "Four Track", "Geo Prism", "Soul-Crushing Despair"
	   	);

	   	// insert filter so list of rewards can be modified

	   	$rewards_array = apply_filters( 'rewards_list', $rewards_array );

	$rewards_array = $help->generate_rewards ( $rewards_array, 9, 'amount' );

   	//Create nonce for our reward buttons

   	$nonce = wp_create_nonce( "cpjr3_rewards_nonce" );

   	//get current user

   	$user_id = get_current_user_id();

   	//Render Rewards Buttons

   	echo '<p>';

   	$i = 0;

   	foreach ( $rewards_array as $rewards ) {

   		$amount = $rewards['amount'];

   		$reward = $rewards['reward'];

   		$link = admin_url('admin-ajax.php?action=cpjr3_rewards_process&user_id_rw=' . esc_attr( $user_id ) . '&points_rw=' . esc_attr( $amount ) . '&events_rw=Purchased&reward_rw=' . esc_attr( $reward ) .'&nonce_rw=' . esc_attr( $nonce ) );

	    echo '<button class="cpjr3-rewards-button" data-user="' . esc_attr( $user_id ) . '" data-nonce="' . esc_attr( $nonce ) . '" data-events="Purchased" data-points="' . esc_attr( $amount ) . '" data-reward="' . esc_attr( $reward ) . '" href="' . esc_attr($link) . '">' . esc_attr( $reward ) . '<br />' . esc_attr( $amount ) . 'pts</button>';

	    echo ( $i == 2 || $i == 5 ) ? " </p><p> " : "";

		$i++;

   	}

	echo '</p>';

}

add_shortcode( 'click-points-rewards', 'cpjr3_rewards_shortcode' );
