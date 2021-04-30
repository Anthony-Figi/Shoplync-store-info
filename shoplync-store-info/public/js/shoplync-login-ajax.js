(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 jQuery(document).ready(function(){
		jQuery( 'form[name="sms-credentials"]' ).on( 'submit', function(e) {
			//e.prevenDefault();
			
			var form_data = jQuery(this).serializeArray();
			// Here we add our nonce (The one we created on our functions.php. WordPress needs this code to verify if the request comes from a valid source.
			form_data.push( { "name" : "security", "value" : ajax_nonce } );
		 
			// Here is the ajax petition.
			jQuery.ajax({
				url : ajax_url, // Here goes our WordPress AJAX endpoint.
				type : 'post',
				data : form_data,
				success : function( response ) {
					// You can craft something here to handle the message return
					location.reload();
				},
				fail : function( err ) {
					// You can craft something here to handle an error if something goes wrong when doing the AJAX request.
					alert( "There was an error: " + err );
				}
			});
			 
			// This return prevents the submit event to refresh the page.
			return false;
		});
	 });

})( jQuery );


