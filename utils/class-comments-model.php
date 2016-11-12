<?php
/**
 * File for comment model main definition
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package AT_Utils
 * @subpackage AT_Utils_Comments
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AT_Comments_Model' ) ) {

	/**
	 * Class of main components for spending tracker
	 *
	 * @author Alexandre Techer <me@alexandretecher.fr>
	 * @since 1.0
	 */
	class AT_Comments_Model {

		/**
		 * Define a comment basic model
		 *
		 * @var array
		 */
		protected $basic_model = array(
			'status'	=> array(
				'type'		=> 'integer',
				'db_field'	=> 'comment_approved',
				'default'	=> 0,
			),
			'date'		=> array(
				'type'		=> 'string',
				'db_field'	=> 'comment_date',
				'callback'	=> 'date_format',
			),
			'user_id'	=> array(
				'type'		=> 'integer',
				'db_field'	=> 'user_id',
				'required'	=> true,
				'default'	=> 0,
				'callback'	=> 'get_connected_user_id',
			),
			'type'		=> array(
				'type'		=> 'string',
				'db_field'	=> 'comment_type',
				'default'	=> 'comment',
			),
			'content'	=> array(
				'type'		=> 'string',
				'db_field'	=> 'comment_content',
				'required'	=> true,
			),
			'parent_id'	=> array(
				'type'		=> 'integer',
				'db_field'	=> 'comment_parent',
				'required'	=> true,
				'default'	=> 0,
			),
			'post_id'	=> array(
				'type'		=> 'integer',
				'db_field'	=> 'comment_post_ID',
				'required'	=> true,
				'default'	=> 0,
			),
			'user_name'	=> array(
				'type'		=> 'string',
				'db_field'	=> 'comment_author',
				'required'	=> true,
			),
			'user_email'	=> array(
				'type'		=> 'string',
				'db_field'	=> 'comment_author_email',
				'required'	=> true,
			),
			'user_url'	=> array(
				'type'		=> 'string',
				'db_field'	=> 'comment_author_url',
				'required'	=> true,
			),
		);

		/**
		 * Spending tracker main initialisation
		 */
		public function __construct() {}

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
