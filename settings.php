<?php

/*
 *
 * Create User Settings Page and Options
 *
 * Portions based on "WordPress Theme Options Page" by Jeffery Way
 * https://github.com/JeffreyWay/WordPress-Theme-Options-Page
 *
 *
 */

function cpjr3_settings_page() {

   add_options_page( __( 'Click Points Settings', 'cpjr3' ), __( 'Click Points', 'cpjr3' ), 'administrator', __FILE__, 'cpjr3_settings_fn' );

}

add_action( 'admin_menu', 'cpjr3_settings_page' );



function render_fields() {

   register_setting( 'cpjr3_settings', 'cpjr3_settings', 'validate_cpjr3_settings' );

   add_settings_section('primary_section', __( 'Select Buttons', 'cpjr3' ), 'primary_section_cb', __FILE__ );

   add_settings_field( 'check_like', __( 'Display Like Button', 'cpjr3' ), 'check_like_settings', __FILE__, 'primary_section' );

   add_settings_field( 'check_share', __( 'Display Share Button', 'cpjr3' ), 'check_share_settings', __FILE__, 'primary_section' );

   add_settings_field( 'check_post', __( 'Display Post Button', 'cpjr3' ), 'check_post_settings', __FILE__, 'primary_section' );

   add_settings_field( 'check_link', __( 'Display Link Button', 'cpjr3' ), 'check_link_settings', __FILE__, 'primary_section' );

   add_settings_field( 'check_tweet', __( 'Display Tweet Button', 'cpjr3' ), 'check_tweet_settings', __FILE__, 'primary_section' );

}

add_action( 'admin_init', 'render_fields' );



function cpjr3_settings_fn() {

?>

   <div id="cpjr3-settings-wrap" class="wrap">

      <div class="icon32" id="icon-options-general">

      		<br />

      </div>

      	<h2>Click Points Plugin Settings</h2>

    	<form method="post" action="options.php" enctype="multipart/form-data">

	        <?php settings_fields( 'cpjr3_settings' ); ?>

	        <?php do_settings_sections( __FILE__ ); ?>

	        <p class="submit">

	        	<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />

	        </p>

   		</form>

	</div>

<?php

}

function primary_section_cb() {

    echo '<p> ' . __( 'Select the Buttons that will display in the Click Points Widget.', 'cpjr3' ) . '</p>' ;

}

function check_like_settings() {

  $options = get_option('cpjr3_settings');

  if ( !isset( $options['check_like'] ) ) {

    $options['check_like'] = 0;

  }

   echo "<input type='checkbox' id='check_like' name='cpjr3_settings[check_like]' value='1' " . checked( $options['check_like'], 1, false ) . "/> <label for='check_like'>" . __( 'Like', 'cpjr3' ) . "</label>";

}

function check_share_settings() {

   $options = get_option('cpjr3_settings');

   if ( !isset( $options['check_share'] ) ) {

    $options['check_share'] = 0;

  }

   echo "<input type='checkbox' id='check_share' name='cpjr3_settings[check_share]' value='1' " . checked( 1, $options['check_share'], false ) . "/> <label for='check_share'>" . __( 'Share', 'cpjr3' ) . "</label>";

}

function check_post_settings() {

   $options = get_option('cpjr3_settings');

   if ( !isset( $options['check_post'] ) ) {

    $options['check_post'] = 0;

  }

   echo "<input type='checkbox' id='check_post' name='cpjr3_settings[check_post]' value='1' " . checked( 1, $options['check_post'], false ) . "/> <label for='check_post'>" . __( 'Post', 'cpjr3' ) . "</label>";

}

function check_link_settings() {

   $options = get_option('cpjr3_settings');

   if ( !isset( $options['check_link'] ) ) {

    $options['check_link'] = 0;

  }

   echo "<input type='checkbox' id='check_link' name='cpjr3_settings[check_link]' value='1' " . checked( 1, $options['check_link'], false ) . "/> <label for='check_link'>" . __( 'Link', 'cpjr3' ) . "</label>";

}

function check_tweet_settings() {

   $options = get_option('cpjr3_settings');

   if ( !isset( $options['check_tweet'] ) ) {

    $options['check_tweet'] = 0;

  }

   echo "<input type='checkbox' id='check_tweet' name='cpjr3_settings[check_tweet]' value='1' " . checked( 1, $options['check_tweet'], false ) . "/> <label for='check_tweet'>" . __( 'Tweet', 'cpjr3' ) . "</label>";

}


function validate_cpjr3_settings( $input ) {

   /*
    * Validation script by Tom McFarlin
    * https://github.com/tommcfarlin/WordPress-Settings-Sandbox
    *
    * This isn't the best way to vaildate checkboxes, obviously.
    * Need some work here.
    */

    $output = array();

    foreach( $input as $key => $value ) {

        if( isset( $input[$key] ) ) {

            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );

        }

    }

    return apply_filters( 'validate_cpjr3_settings', $output, $input );

}



function section_cb() {}


