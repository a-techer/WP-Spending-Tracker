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
	 * Class for comment model main definition
	 *
	 * @author Alexandre Techer <me@alexandretecher.fr>
	 * @since 1.0
	 */
	class AT_Comments_Model extends AT_Models_Utils {

		/**
		 * Define a comment basic model
		 *
		 * @var array
		 */
		protected $basic_model = array(
			'id'	=> array(
				'type'		=> 'integer',
				'db_field'	=> 'comment_ID',
				'default'	=> 0,
			),
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
				'required'	=> false,
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
		 * Comment model main definition instanciation
		 */
		public function __construct() {}

	}

}
