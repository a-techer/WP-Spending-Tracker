<?php
/**
 * Manage filter for transaction
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package Spending_tracker
 * @subpackage Transactions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Manage filter for transaction
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 */
class Spending_Tracker_Transactions_Filters {

	/**
	 * Instanciate spending tracker filters
	 */
	public function __construct() {
		/** Filter spending transaction form in order to add fields to display */
		add_filter( 'atst_filter_transaction_form_fields', array( $this, 'add_field_to_transaction_form' ) );

		/** Filter spending transaction save for adding extra datas */
		add_filter( 'atst_insert_transaction_args', array( $this, 'transaction_args_filter' ) );
	}

	/**
	 * Filter function allowing to extend the transaction form by adding field
	 *
	 * @param array $default_content The default list of field to add to the transaction form into an array.
	 */
	public function add_field_to_transaction_form( $default_content ) {
		/** Add field for provider selection in transaction form */
		$element_custom_display = array(
			'class' => '',
			'html' => Spending_Tracker_Providers::instance()->display_element_selector(),
		);
		$default_content[] = $element_custom_display;

		/** Add field for project selection in transaction form */
		$element_custom_display = array(
			'class' => '',
			'html' => Spending_Tracker_Projects::instance()->display_element_selector(),
		);
		$default_content[] = $element_custom_display;

		/** Return all fields to add */
		return $default_content;
	}

	/**
	 * Filter the transaction args in order to add projects and provider settings
	 *
	 * @param array $transaction_args The current arguments and definition of the transaction to save into database.
	 *
	 * @return return array The filtered transaction argument
	 */
	public function transaction_args_filter( $transaction_args ) {
		/** Manage provider datas */
		$provider = null;
		if ( ! empty( $transaction_args[ Spending_Tracker_Providers::instance()->get_type() . '-new' ] ) ) {
			$provider_args['name'] = $transaction_args[ Spending_Tracker_Providers::instance()->get_type() . '-new' ];
			$provider_save_result = Spending_Tracker_Providers::instance()->post( $provider_args );
			if ( ! empty( $provider_save_result ) && ! empty( $provider_save_result['status'] ) && ! empty( $provider_save_result['object'] ) ) {
				$provider = Spending_Tracker_Providers::instance()->get( array( 'p' => $provider_save_result['object'] ), true );
			}
		} elseif ( ! empty( $transaction_args[ Spending_Tracker_Providers::instance()->get_type() ] ) ) {
			$provider_args['p'] = $transaction_args[ Spending_Tracker_Providers::instance()->get_type() ];
			$provider = Spending_Tracker_Providers::instance()->get( $provider_args, true );
		}
		unset( $transaction_args[ Spending_Tracker_Providers::instance()->get_type() . '-new' ] );
		unset( $transaction_args[ Spending_Tracker_Providers::instance()->get_type() ] );

		if ( null !== $provider ) {
			$transaction_args['user_name'] = $provider->name;
			$transaction_args['user_url'] = $provider->slug;
			$transaction_args['user_email'] = $provider->slug;
		}

		/** Manage project datas */
		$project_id = null;
		if ( ! empty( $transaction_args[ Spending_Tracker_Projects::instance()->get_type() . '-new' ] ) ) {
			$provider_args['name'] = $transaction_args[ Spending_Tracker_Projects::instance()->get_type() . '-new' ];
			$project_save_result = Spending_Tracker_Projects::instance()->post( $provider_args );
			if ( ! empty( $project_save_result ) && ! empty( $project_save_result['status'] ) && ! empty( $project_save_result['object'] ) ) {
				$project_id = $project_save_result['object'];
			}
		} elseif ( ! empty( $transaction_args[ Spending_Tracker_Projects::instance()->get_type() ] ) ) {
			$project_id = $transaction_args[ Spending_Tracker_Projects::instance()->get_type() ];
		}
		unset( $transaction_args[ Spending_Tracker_Projects::instance()->get_type() . '-new' ] );
		unset( $transaction_args[ Spending_Tracker_Projects::instance()->get_type() ] );

		if ( null !== $project_id ) {
			$transaction_args['post_id'] = $project_id;
		}

		/** Return transaction args after having filter necessary element */
		return $transaction_args;
	}

}
