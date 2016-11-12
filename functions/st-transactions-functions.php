<?php
/**
 * Define accessible function to manage transactions
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package Spending_Tracker
 */

/**
 * Create a transaction just by calling a function with hooks
 *
 * @method st_insert_transaction
 * @param  array $transaction_args Argument defining the transaction to create.
 * @return array A response after transaction creation
 */
function st_insert_transaction( $transaction_args ) {
	/** Define the default value for function return */
	$status = true;
	$message = __( 'Transaction have been saved successfully.', 'spending_tracker' );
	$element = null;

	/** Perform some action before saving transaction into database */
	do_action( 'atst_before_transaction_save' );

	/** Apply a filter on transaction arguments allowing anybody to add/change transaction data before saving */
	$transaction_args = apply_filters( 'atst_insert_transaction_args', $transaction_args );

	/** Save the transaction into database and return the transaction save result */
	$new_transaction = Spending_Tracker_Transactions::instance()->post( $transaction_args );
	if ( false === $new_transaction['status'] ) {
		$status = $new_transaction['status'];
		$message = $new_transaction['errors'];
	}

	/** Perform some action after saving transaction into database */
	do_action( 'atst_after_transaction_save' );

	/** Return the transaction creation result */
	return array(
		'status'	=> $status,
		'message'	=> $message,
		'element'	=> $new_transaction,
	);
}
