<?php
/**
 * File of main components for spending tracker
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package AT_Utils
 * @subpackage AT_Utils_Comments
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AT_Comments_Utils' ) ) {

	/**
	 * Class of main components for spending tracker
	 *
	 * @author Alexandre Techer <me@alexandretecher.fr>
	 * @since 1.0
	 */
	class AT_Comments_Utils extends AT_Datas_Utils {

		/**
		 * Define the default type of comments in WordPress
		 *
		 * @var string
		 */
		protected $type = 'comment';

		/**
		 * Define the default key for comments' meta
		 *
		 * @var string
		 */
		protected $meta = '_at_comment_meta';

		/**
		 * Spending tracker main initialisation
		 */
		public function __construct() { }

		/**
		 * Get and return the current element type
		 *
		 * @method get_type
		 * @return string   The defined type
		 */
		public function get_type() {
			return $this->type;
		}

		/**
		 * Create a transaction into database from data given in parameter
		 *
		 * @param array $args An array with data to save.
		 */
		public function post( $args ) {
			/** Define the creation result output */
			$comment_creation_result = array(
				'status'	=> true,
				'object'	=> null,
				'output'	=> '',
			);

			/** Check and sanitize datas */
			$final_args = $this->validate_datas( $this->get_type(), $this->model, $args );

			/** In case datas are validated send to the update function */
			if ( ( null !== $final_args ) && ( true === $final_args['datas_are_valid'] ) ) {
				$new_comment = wp_new_comment( $final_args['datas'], true );
				if ( ( is_wp_error( $new_comment ) ) || ( false === $new_comment ) ) {
					$comment_creation_result['status'] = false;
					$comment_creation_result['output'] = $new_comment;
				} else {
					/** Save comment metas in case there are metas to save */
					foreach ( $final_args['metas'] as $meta_key => $meta_value ) {
						update_comment_meta( $new_comment, $meta_key, $meta_value );
					}

					$comment_creation_result['object'] = $new_comment;
				}
			} elseif ( false === $final_args['datas_are_valid'] ) {
				$comment_creation_result['status'] = false;
				$comment_creation_result['output'] = $final_args['errors'];
			}

			return $comment_creation_result;
		}

		/**
		 * Get the list of existing comments
		 *
		 * @param array   $args 	Allows to set specific arguments for getting providers.
		 * @param boolean $single Define if the return is only the requested value or all the request.
		 */
		public function get( $args = array(), $single = false ) {
			/** Get comments from database using WordPress WP_Query */
			$default_args = array(
				'type'	=> $this->get_type(),
			);
			$comment_query_final_args = wp_parse_args( $args, $default_args );
			$comment_query_args = $this->build_query_from_model( $this->model, $comment_query_final_args );
			$list_query = new WP_Comment_Query( $comment_query_args );

			/** If $single is set to true and only one result have been found return directly the only result */
			if ( ( true === $single ) && ( 1 === count( $list_query->comments ) ) ) {
				$list_query = $this->build_model( $this->model, $list_query->post );
			} elseif ( ! empty( $list_query->comments ) ) {
				foreach ( $list_query->comments as $key => $comment ) {
					$list_query->comments[ $key ] = $this->build_model( $this->model, $comment );
				}

				/** Only return the element list if single parameter is set to true */
				if ( true === $single ) {
					$list_query = $list_query->comments;
				}
			}

			return $list_query;
		}

	}

}
