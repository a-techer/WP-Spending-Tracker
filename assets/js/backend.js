"use strict";

jQuery( document ).ready( function(){
	spending_tracker_scripts.init();

	jQuery( "#atst-transaction-form" ).ajaxForm({
		dataType: 'json',
		success: function ( responseText, statusText, xhr, $form ) {
			console.log( responseText );
		}
	});
});

var spending_tracker_scripts = {
	init: function() {
		/**	Turn all input having a specific class into a datepicker input	*/
		jQuery( ".atst-date-field" ).datepicker({
			// dateFormat: "dd/mm/yy",
			dateFormat: "yy-mm-dd",
		});
		/**	When clicking on date icon focus the date input in order to display the calendar widget	*/
		jQuery( ".atst-date-field-icon" ).click( function( event ){ jQuery( ".atst-date-field" ).focus(); } );

		/** When clicking on dashicon allowing to create a new item */
		jQuery( ".atst-new-post-item-button" ).click( function( event ){ spending_tracker_scripts.display_new_post_item_input( event, jQuery(this) ) } );

		jQuery( ".atst-transaction-type-line div" ).click( function( event ){ spending_tracker_scripts.change_transaction_type( event, jQuery(this) ) } );
	},

	display_new_post_item_input: function( event, element ) {
		event.preventDefault();

		jQuery( element ).next(".atst-new-post-item-input").show();
		jQuery( element ).prev("select.atst-post-list").hide();
		jQuery( element ).hide();
	},

	change_transaction_type: function( event, element ) {
		event.preventDefault();

		var current_type = 'expense';
		jQuery( element ).closest("li").children("div").each( function(){
			if ( jQuery( this ).hasClass("atst-transaction-current-type") ) {
				current_type = jQuery( this ).attr("data-type");
				jQuery( this ).removeClass("atst-transaction-current-type");
			}
		});
		jQuery( element ).addClass("atst-transaction-current-type");
		jQuery( ".atst-transaction-field-amount" ).attr("class", jQuery( ".atst-transaction-field-amount" ).attr("class").replace( current_type, ( "expense" == current_type ? "income" : "expense" ) ) );
	}

};
