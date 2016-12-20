<?php
/**
 * Manage posts model defintion
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package AT_Utils
 * @subpackage AT_Utils_Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AT_Posts_Model' ) ) {

	/**
	 * Manage posts model definition
	 *
	 * @author Alexandre Techer <me@alexandretecher.fr>
	 * @since 1.0
	 */
	class AT_Posts_Model extends AT_Models_Utils {

		/**
		 * Define a post basic model
		 *
		 * @var array
		 */
		protected $basic_model = array(
			'id'	=> array(
				'type'			=> 'integer',
				'db_field'	=> 'ID',
				'default'		=> 0,
			),
			'status'	=> array(
				'type'			=> 'string',
				'db_field'	=> 'post_status',
				'default'		=> 'publish',
			),
			'date'		=> array(
				'type'			=> 'string',
				'db_field'	=> 'post_date',
				'callback'	=> 'date_format',
			),
			'name'		=> array(
				'type'			=> 'string',
				'db_field'	=> 'post_title',
			),
			'slug'		=> array(
				'type'			=> 'string',
				'db_field'	=> 'post_name',
			),
			'type'		=> array(
				'type'			=> 'string',
				'db_field'	=> 'post_type',
				'default'		=> 'post',
			),
		);

		/**
		 * Spending tracker main initialisation
		 */
		public function __construct() { }

	}

}
