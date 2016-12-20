<?php
/**
 * Manage all filters for spending tracker module
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package Spending_tracker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Manage all filters for spending tracker module
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 */
class Spending_Tracker_Filters {

	/**
	 * Instanciate spending tracker filters
	 */
	public function __construct() {
		/** Manage the menu order */
		add_filter( 'custom_menu_order', array( $this, 'custom_menu' ) );
	}

	/**
	 * Reorder admin menu
	 *
	 * @param  mixed $current_menu_order The current menu order.
	 *
	 * @return mixed
	 */
	public function custom_menu( $current_menu_order ) {
		global $submenu;

		$new_order = array(
			$submenu['spending-tracker-dashboard'][2],
			$submenu['spending-tracker-dashboard'][0],
			$submenu['spending-tracker-dashboard'][1],
		);
		$submenu['spending-tracker-dashboard'] = $new_order;

		return $current_menu_order;
	}

}
