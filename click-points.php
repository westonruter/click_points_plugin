<?php

/*
	Plugin Name: Click Points Plugin
	Plugin URI: http://johnregan3.com
	Description: Earn points by simply by clicking, then spend your points on fabulous prizes!
	Author: John Regan
	Author URI: http://johnregan3.com
	Version: 1.0
 */

include_once( 'settings.php' ); 		//Plugin Settings Page
include_once( 'helpers.php' ); 			//Helpers Class
include_once( 'process-widget.php' ); 	//Calculates points from Widget
include_once( 'process-rewards.php' ); 	//Calculates points from Rewards
include_once( 'shortcode-user.php' ); 	//User Page shortcode
include_once( 'shortcode-rewards.php' ); //Rewards Page shortcode
include_once( 'widget.php' ); 			//Custom Widget


/*
 *
 * Add settings link on Plugins page
 *
 */

function cpjr3_settings_link( $links ) { 

	//Get link to be generated

	$settings_page = '<a href="' . admin_url('options-general.php?page=click-points/settings.php' ) .'">Settings</a>'; 

	//Add to front of $links array

	array_unshift( $links, $settings_page ); 

	return $links; 

}
 
$plugin = plugin_basename(__FILE__); 

add_filter( "plugin_action_links_$plugin", 'cpjr3_settings_link' );


/*
 *
 * Enqueue Scripts
 *
 */

function cpjr3_enqueue_scripts() {

	wp_register_script( 'cpjr3_points', WP_PLUGIN_URL . '/click-points/script-widget.js', array( 'jquery' ) );

	wp_register_script( 'cpjr3_rewards', WP_PLUGIN_URL . '/click-points/script-rewards.js', array( 'jquery' ) );

	wp_localize_script( 'cpjr3_points', 'cpjr3_AJAX', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );  

	wp_localize_script( 'cpjr3_rewards', 'cpjr3_rewards_AJAX', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );       

	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( 'cpjr3_points' );

	wp_enqueue_script( 'cpjr3_rewards' );

	wp_register_style( 'cpjr3_style', plugins_url( 'style.css', __FILE__ ), array(), '1.0', 'all' );

	wp_enqueue_style( 'cpjr3_style' );  

}

add_action( 'init', 'cpjr3_enqueue_scripts' );



/*
 *
 * Register Activation Hook
 *
 */

function cpjr3_activate() {

	$actions = array(
		"check_like",
		"check_share",
		"check_post",
		"check_link",
		"check_tweet",
	);

	foreach ($actions as $action) {

		$cpjr3_settings[$action] = 1;

	}

    add_option( 'cpjr3_settings', $cpjr3_settings );
}

register_activation_hook( __FILE__, 'cpjr3_activate' );





/*
 *
 * Register Deactivation Hook
 *
 */


function cpjr3_deactivate() {

	delete_option( 'cpjr3_settings' );

	//User Meta Deletion Code From
	//http://wordpress.stackexchange.com/questions/60769/best-practice-way-to-delete-user-meta-data-during-plugin-uninstall

	$all_user_ids = get_users ( 'fields=ID' );

	foreach ( $all_user_ids as $user_id ) {

	    delete_user_meta( $user_id, 'cpjr3_score' );

	    delete_user_meta( $user_id, 'cpjr3_events' );

	}

}

register_deactivation_hook( __FILE__, 'cpjr3_deactivate' );


function cpjr3_textdomain() {

     load_plugin_textdomain('cpjr3');
}

add_action('init', 'cpjr3_textdomain');
