"use strict";

jQuery( document ).ready( function(){

	// wp.api.loadPromise.done( function() {

		// Load an existing post
		// var post = new wp.api.models.Post( { id: 1 } );
		// post.fetch({
	  //   success: function (result) {
	  //     var page = result.toJSON();
		// 		console.log( page );
	  //   }
	  // });
		//
		// console.log(wp.api.models);
		// var STProvider = wp.api.models.Post.extend({
    //   urlRoot: wpApiSettings.root + 'wp/v2/stproviders',
    //   defaults: {
    //     type: 'stproviders'
    //   }
    // });
		//
		// var post = new wp.api.models.Stproviders( { id: 1 } );
		// post.fetch({
	  //   success: function (result) {
	  //     var page = result.toJSON();
		// 		console.log( page );
	  //   }
	  // });
		//
		// // Create a new post
		// var provider = new STProvider( { title: 'This is a test provider' } );
		// provider.save();
		//
		// var provider = new STProvider( { id: 1 } );
		// provider.fetch({
	  //   success: function (result) {
	  //     var page = result.toJSON();
		// 		console.log( page );
	  //   }
	  // });
		//
    // var STProviders = wp.api.collections.Posts.extend({
    //     url: wpApiSettings.root + 'wp/v2/stProviders',
    //     model: STProvider
    // });
		//
    // self.providers = new STProviders();
    // self.providers.fetch({
    //     filter: {
    //       nopaging: true
    //     }
    // }).done( function() {
    //     self.providers.each( function( provider ) {
    //         console.log( provider.attributes );
    //     });
    // });

		// Create a new post
		// var comment = new wp.api.models.Comment( {
		// 	content: 'pfiou de commentaire',
		// 	post: 16,
		// 	comment_type: 'atst-transaction',
		// } );
		// comment.save();
	// });

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

		/** When clicking on transaction type, change some display  */
		jQuery( ".atst-transaction-type-line div" ).click( function( event ){ spending_tracker_scripts.change_transaction_type( event, jQuery(this) ) } );

		/** When clicking on "more options" for transaction */
		jQuery( ".atst-transaction-more-options-opener" ).click( function( event ){ spending_tracker_scripts.open_transaction_options( event, jQuery(this) ) } );
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
	},

	open_transaction_options: function( event, element ) {
		event.preventDefault();


	}

};
