jQuery(document).ready( function() {

   //Use Rw suffix to prevent conflicts with click-points.js

   //Dynamically generate popup div

   var $div_rw = jQuery('<div />').appendTo('body');

      $div_rw.hide();

      $div_rw.attr( 'id', 'overlay_box_rw' );

   //Popup Code

   function pointsAlertRw( msg ){

      jQuery("#overlay_box_rw").html( msg );

      jQuery("#overlay_box_rw").fadeIn(500).delay(7000).fadeOut(500);

      positionPopupRw();

   }

   //position the popup at the center of the page
   function positionPopupRw(){

      if(!jQuery("#overlay_box_rw").is(':visible')){

         return;

      }

         jQuery("#overlay_box_rw").css({

            right: '10px',

            bottom: '10px',

            position:'fixed',

            background: '#ffffff',

            padding: '10px',

            border: '1px solid #aaaaaa', 

            'border-radius': '3px'

         });

   }
    
   //maintain the popup position when browser resized
   jQuery(window).bind('resize',positionPopupRw);


   // End Popup Code


   //Generate Spinner 

      //Dynamically generate spinner div

      var $spindiv_rw = jQuery('<div />').appendTo('body');

      $spindiv_rw.hide();

      $spindiv_rw.attr( 'id', 'spinner-rw' );

      var spinnerRw = jQuery( '#spinner-rw' );

      spinnerRw.prepend('<div id="spinner-rw-image"></div>');

      function spinnerInRw() {

         spinnerRw.fadeIn(50);

         positionSpinnerRw();

      }

      function spinnerOutRw() {

         spinnerRw.fadeOut(50);

      }

      function positionSpinnerRw(){

      if( !spinnerRw.is( ':visible' ) ){

         return;

      }
         spinnerRw.css( {

            right: '10px',

            bottom: '10px',

            position:'fixed',

            background: '#ffffff',

            padding: '10px',

            border: '1px solid #aaaaaa', 

            'border-radius': '3px'

         } );

   }
    
   //maintain the popup position when browser resized
   jQuery(window).bind('resize',positionSpinnerRw);

   //End Spinner




   //Click Points Ajax


   jQuery(".cpjr3-rewards-button").click( function() {

      user_id = jQuery(this).attr("data-user");

      nonce = jQuery(this).attr("data-nonce");

      points = jQuery(this).attr("data-points");

      events = jQuery(this).attr("data-events");

      reward = jQuery(this).attr("data-reward");

      jQuery.ajax({

         type : "post",

         dataType : "json",

         url : cpjr3_rewards_AJAX.ajaxurl,

         data : {action: "cpjr3_rewards_process", user_id_rw : user_id, nonce_rw: nonce, points_rw: points, events_rw: events, reward_rw: reward },

         beforeSend: function() {

                spinnerInRw();

         },

         complete: function() {

                spinnerOutRw();

         },

         success: function(response) {

            if( response.events.type == "success" && response.total_score.type == "success" ) {

               message = "Congratulations!  You just " + response.events.event  + " for " + response.total_score.value + " points!" 
               
               pointsAlertRw( message );
               
            } else if ( response.nsf == "NSF" ) {

               message = "Sorry. You do not have enough Points for that Reward."

               pointsAlertRw( message );

            } else {

               message = "Error processing the event.  Points not saved." 

               pointsAlertRw( message );

            }

         }

      })   

   })

})