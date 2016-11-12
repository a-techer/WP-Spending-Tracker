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
	class AT_Posts_Model {

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
		);

		/**
		 * Define the provider model
		 *
		 * @var array
		 */
		protected $model = array();

		/**
		 * Spending tracker main initialisation
		 */
		public function __construct() { }

		/**
		 * Get and return the basic model for the current element.
		 * Which is a basic element from wordpress database
		 *
		 * @return array The basic model of comments into wordpress
		 */
		public function get_basic_model() {
			return $this->basic_model;
		}

		/**
		 * Get and return the model for the current element.
		 */
		public function get_model() {
			return $this->model;
		}

	}

}
