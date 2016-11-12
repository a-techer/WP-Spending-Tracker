"use strict";

jQuery( document ).ready( function(){
	spending_tracker_scripts.init();
	
	jQuery( "#atst-transaction-form" ).ajaxForm();
});

var spending_tracker_scripts = {
	init: function() {
		/**	Listen event on click on the create button on dashboard*/
		jQuery( ".atst-operations-button" ).click( function( event ){ spending_tracker_scripts.display_transaction_form( event, jQuery( this ) ); } );
		
		/**	Turn all input having a specific class into a datepicker input	*/
		jQuery( ".atst-date-field" ).datepicker({
			dateFormat: "dd/mm/yy",
		});
		/**	When clicking on date icon focus the date input in order to display the calendar widget	*/
		jQuery( ".atst-date-field-icon" ).click( function( event ){ jQuery( ".atst-date-field" ).focus(); } );
	},
	
	/**
	 * Display the hidden form by clicking the creation button
	 * 
	 * @param event Object containing the event
	 * @param element The element that have been clicked
	 */
	display_transaction_form: function( event, element ) {
		event.preventDefault();
		
		jQuery( element ).slideUp( 'fast', function(){
			jQuery( "#atst-transaction-form" ).slideDown( 'slow' );
		} );
	},
	
	/**
	 * Open a dialog box allowing to create an advanced transaction
	 * 
	 * @param event Object containing the event
	 * @param element The element that have been clicked
	 */
	open_dialog_incoming: function( event, element ) {
		event.preventDefault();
		
		jQuery( "#atst-new-transaction" ).dialog({
			"title"			: spending_tracker.dialog_title,
			"width"			: 300,
			"height"		: 450,
	        "dialogClass"   : "atst-dialog",           
	        "modal"         : true,
	        "autoOpen"      : true, 
	        "closeOnEscape" : true,
	        "buttons"		: [
				{
					text: spending_tracker.cancel_call_button_text,
					class: "button-secondary",
				    click: function() {
				    	jQuery( this ).dialog( "close" );
				    }
				},
				{
					text: spending_tracker.save_call_button_text,
		            class: "button-primary",
		            click: function() {
		            	jQuery( this ).dialog( "close" );
		            }
		        }
	        ],
	        "close"			: function( event, ui ) {
	        	
	        },
			"open"			: function( event, ui ) {
//				var data = {
//					action: "ipc_load_incoming_call_form",
//					line_id: current_line_id,
//				}
//				jQuery( this ).load( ajaxurl, data );
			}
	    });
	}
};
