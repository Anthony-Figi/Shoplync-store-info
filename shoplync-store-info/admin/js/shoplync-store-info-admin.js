(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
	 
	 function getFilename(){
		var e = $(this);
		var inputLink = e.val();
		var filename = "";
		if(inputLink != null || inputLink.length > 0){
			filename = inputLink.split("/").pop();//generates array of elements get last element
			console.log("not null:"+filename);
			if(filename.length < 1){
				if(e.attr("id") == "shoplync-store-info-link-1"){
					filename = "SMS-Latest";
				}else {
					filename = "SMS-Previous-V"+e.attr("id").split("-").pop()+".0";
				}
				console.log("length small");
			}else {
				
				var idx = filename.lastIndexOf("."); //get index of . before file extension
				filename = filename.substring(0,idx);
				console.log("length okay:"+filename);
			}
			if( $("#"+e.attr("id")+"-name").length ){
				$("#"+e.attr("id")+"-name").val(filename);
			}
			
		}
	 }
	 
	 jQuery(document).ready(function(){
		 $("#shoplync-store-info-link-1").on("change paste keyup", getFilename);
		 $("#shoplync-store-info-link-2").on("change paste keyup", getFilename);
		 $("#shoplync-store-info-link-3").on("change paste keyup", getFilename);
	 });

})( jQuery );
