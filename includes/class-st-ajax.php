<?php
/**
 * File managing ajax actions for spending tracker
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package Spending_Tracker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class managing ajax actions for spending tracker
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 */
class Spending_Tracker_Ajax {

	/**
	 * Spending tracker ajax main initialisation
	 */
	public function __construct() {
		/** Trigger ajax action for saving a new transaction	*/
		add_action( 'wp_ajax_atst_save_transaction', array( $this, 'save_transaction' ) );
	}

	/**
	 * Do treatment on data sended through transaction form
	 */
	public function save_transaction() {
		/** Check if the current request is allowed */
		check_ajax_referer( 'atst_save_transaction' );

		if ( ! empty( $_POST ) && ! empty( $_POST['atst-transaction'] ) ) {
			$new_transaction = st_insert_transaction( wp_unslash( $_POST['atst-transaction'] ) );
		}

		wp_die( wp_json_encode( $new_transaction ) ); // Wordpress recommendation when using ajax request!
	}

}
