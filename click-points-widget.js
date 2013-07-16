jQuery(document).ready( function() {


   //Dynamically generate popup div

   var $div = jQuery('<div />').appendTo('body');

      $div.hide();

      $div.attr( 'id', 'overlay_box' );

   //Popup Code

   function pointsAlert( msg ){

      jQuery("#overlay_box").html( msg );

      jQuery("#overlay_box").fadeIn(500).delay(7000).fadeOut(500);

      positionPopup();

   }

   //position the popup at the center of the page
   function positionPopup(){

      if(!jQuery("#overlay_box").is(':visible')){

         return;

      }

         jQuery("#overlay_box").css( {

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
   jQuery(window).bind('resize',positionPopup);


   // End Popup Code




   //Generate Spinner 

      //Dynamically generate spinner div

      var $spindiv = jQuery('<div />').appendTo('body');

      $spindiv.hide();

      $spindiv.attr( 'id', 'spinner' );

      var spinner = jQuery( '#spinner' );

      spinner.prepend('<div id="spinner-image"></div>');

      function spinnerIn() {

         spinner.fadeIn(50);

         positionSpinner();

      }

      function spinnerOut() {

         spinner.fadeOut(50);

      }

      function positionSpinner(){

      if( !spinner.is( ':visible' ) ){

         return;

      }

         spinner.css( {

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
   jQuery(window).bind('resize',positionSpinner);

   //End Spinner




   //Click Points Ajax


   jQuery(".cpjr3-button").click( function() {

      user_id = jQuery(this).attr("data-user");

      nonce = jQuery(this).attr("data-nonce");

      points = jQuery(this).attr("data-points");

      events= jQuery(this).attr("data-events");


      jQuery.ajax({

         type : "post",

         dataType : "json",

         url : cpjr3_AJAX.ajaxurl,

         data : {action: "cpjr3_process", user_id : user_id, nonce: nonce, points: points, events: events },

         beforeSend: function() {

                spinnerIn();

         },

         complete: function() {

                spinnerOut();

         },

         success: function(response) {

            if(response.events.type == "success" && response.total_score.type == "success") {

               message = "You just earned " + response.total_score.value + " points for " + response.events.event + "!" 

               pointsAlert( message );
               
            } else {

               message = "Error processing the event.  Points not saved." 

               pointsAlert( message );

               console.log(message);

            }

         }

      })   

   })

})