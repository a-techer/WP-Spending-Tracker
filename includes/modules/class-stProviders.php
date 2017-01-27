<?php
/**
 * Provider manager for spending tracker main file
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package Spending_Tracker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Provider manager for spending tracker main file
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 */
class Spending_Tracker_Providers extends AT_Posts_Utils {

	/**
	 * Define the provider model
	 *
	 * @var array
	 */
	protected $model = array();

	/**
	 * The single instance of the class.
	 *
	 * @var Spending_Tracker_Providers
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Declare the main type for the current element
	 *
	 * @var string
	 */
	protected $type = 'stProviders';

	/**
	 * Initialize component
	 */
	public function __construct() {
		/** Define the specific args for providers */
		$this->post_type_args = array(
			'description' 	=> __( 'Manage providers for spending tracker', 'spending_tracker' ),
			'singular'			=> __( 'Provider', 'spending_tracker' ),
			'plural'				=> __( 'Providers', 'spending_tracker' ),
			'show_in_menu'	=> 'spending-tracker-dashboard',
		);
		$this->post_type_labels_args = array(
			'menu_name'	=> _x( 'Provider', 'admin menu', 'spending_tracker' ),
		);

		/** Call parent common constructor */
		parent::__construct();

		/** Declare and build comments model */
		$post_model = new AT_Posts_Model();
		/** Build the final model from current element specific and common model */
		$specific_model = array(
			'type'	=> array(
				'type'			=> 'string',
				'db_field'	=> 'post_type',
				'default'		=> $this->get_type(),
			),
		);
		$this->model = wp_parse_args( wp_parse_args( $specific_model, $this->model ), $post_model->get_basic_model() );
	}

	/**
	 * Main Spending Tracker Instance.
	 *
	 * Ensures only one instance of Spending Tracker is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @static
	 *
	 * @return Spending Tracker Providers - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

}
