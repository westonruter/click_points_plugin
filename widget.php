<?php

/*
 *
 * Widget for Click Points Plugin
 *
 * Creates Buttons to Click!
 *
 * Based on "Sample WordPress Widget" by Pippin Williamson
 * http://pippinsplugins.com/simple-wordpress-widget-template/
 *
 */



class CPJR3_Widget extends WP_Widget {

	static function register() {
		register_widget( __CLASS__ );
	}

	/** constructor -- name this the same as the class above */

	function CPJR3_Widget() {

		parent::WP_Widget( false, $name = __( 'Click Points Widget', 'cpjr3' ) );

	}

	/** @see WP_Widget::widget -- do not rename this */

	function widget( $args, $instance ) {

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;

		if ( $title ) {

			echo $before_title . $title . $after_title;

		}

		echo '<p>';

		esc_html_e( 'Click these buttons to earn points!', 'cpjr3' );

		echo '</p>';

			//Get ready to generate Points Action Buttons

			//create nonce
			$nonce  = wp_create_nonce( "cpjr3_widget_nonce" );


			//fetch user_id
			$user_id = get_current_user_id();

			//Insert Actions Array
			$actions_array = array(

				"Like" => array(

					"title" => __( "Like", 'cpjr3' ),

					"link" => "like_link",

					"event" => "Liking",

					"points" => "50"

				),

				"Share" => array(

					"title" => __( "Share", 'cpjr3' ),

					"link" => "share_link",

					"event" => "Sharing",

					"points" => "100"

				),

				"Post" => array(

					"title" => __( "Post", 'cpjr3' ),

					"link" => "post_link",

					"event" => "Posting about",

					"points" => "200"

				),

				"Link" => array(

					"title" => __( "Link", 'cpjr3' ),

					"link" => "link_link",

					"event" => "Linking to",

					"points" => "500"

				),

				"Tweet" => array(

					"title" => __( "Tweet", 'cpjr3' ),

					"link" => "tweet_link",

					"event" => "Tweeting",

					"points" => "700"

				)

			);

			$options = get_option( 'cpjr3_settings' );

			$actions = array_keys( $actions_array );

			foreach ( $actions as $title ) {

				$sm_title = strtolower( $title );

				if ( isset( $options["check_" . $sm_title] ) ) {

					$actions_array[$title]["active"] = 1;

				} else {

					$actions_array[$title]["active"] = 0;

				}

			}


			foreach( $actions_array as $action ) {

				if( $action["active"] == 1 ) {

					$$action["link"] = admin_url('admin-ajax.php?action=cpjr3_process&user_id=' . esc_attr( $user_id ) . '&points=' . esc_attr( $action["points"] ) . '&events= ' . esc_attr( $action["event"] ) . '&nonce=' . esc_attr( $nonce ) );
					echo '<p><button class="cpjr3-button" data-user="' . esc_attr( $user_id ) . '" data-nonce="' . esc_attr( $nonce )  . '" data-events="' . esc_attr( $action["event"] ) . '" data-points="' . esc_attr( $action["points"] ) . '" href="' . esc_attr( $$action["link"] ) . '">' . esc_attr( $action["title"] ) . ' +' . esc_attr( $action["points"] ) . 'pts</button></p>';
				}

			}

		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	function form( $instance ) {

		$instance = array_merge(
			array(
				'title' => '',
			),
			$instance
		);

		?>

		<p>

			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>

			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />

		</p>

		<p class="description"><?php esc_html_e( 'This widget is used for the Click Points Plugin.', 'cpjr3' ) ?></p>

		<?php
	}


} // end class example_widget

add_action( 'widgets_init', array( 'CPJR3_Widget', 'register' ) );
