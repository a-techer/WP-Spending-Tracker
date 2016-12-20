<?php
/**
 * Manage posts utilities with models and common functions definition
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package AT_Utils
 * @subpackage AT_Utils_Posts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AT_Posts_Utils' ) ) {

	/**
	 * Manage posts utilities with models and common functions definition
	 *
	 * @author Alexandre Techer <me@alexandretecher.fr>
	 * @since 1.0
	 */
	class AT_Posts_Utils extends AT_Datas_Utils {

		/**
		 * Declare the main type for the current element
		 *
		 * @var string
		 */
		protected $type = 'post';

		/**
		 * Define the default key for posts' meta
		 *
		 * @var string
		 */
		protected $meta = '_at_post_meta';

		/**
		 * Define arguments for custom post type
		 *
		 * @var array
		 */
		protected $post_type_args = array();

		/**
		 * Allows to define custom post type labels separatly from main definition
		 *
		 * @var array
		 */
		protected $post_type_labels_args = array();

		/**
		 * Spending tracker main initialisation
		 */
		public function __construct() {
			/** Call custom post type declaration function */
			add_action( 'init', array( $this, 'declare_type' ) );
		}

		/**
		 * Get the provider custom post Type
		 *
		 * @return string The main type defining providers in spending tracker
		 */
		public function get_type() {
			return $this->type;
		}

		/**
		 * Declare the custom post type for wordpress using default args and specific args defined for each type
		 *
		 * @method declare_type
		 * @return void
		 */
		public function declare_type() {

			/** Define default labels */
			$labels = wp_parse_args( $this->post_type_labels_args, array(
				'name'								=> $this->post_type_args['plural'],
				'singular_name'				=> $this->post_type_args['singular'],
				'menu_name'						=> $this->post_type_args['plural'],
				'name_admin_bar'			=> sprintf( __( 'New %s', 'spending_tracker' ), strtolower( $this->post_type_args['singular'] ) ),
				'add_new'							=> sprintf( __( 'New %s', 'spending_tracker' ), strtolower( $this->post_type_args['singular'] ) ),
				'add_new_item'				=> sprintf( __( 'Add New %s', 'spending_tracker' ), strtolower( $this->post_type_args['singular'] ) ),
				'new_item'						=> sprintf( __( 'New %s', 'spending_tracker' ), strtolower( $this->post_type_args['singular'] ) ),
				'edit_item'						=> sprintf( __( 'Edit %s', 'spending_tracker' ), strtolower( $this->post_type_args['singular'] ) ),
				'view_item'						=> sprintf( __( 'View %s', 'spending_tracker' ), strtolower( $this->post_type_args['singular'] ) ),
				'all_items'						=> sprintf( __( 'All %s', 'spending_tracker' ), strtolower( $this->post_type_args['plural'] ) ),
				'search_items'				=> sprintf( __( 'Search %s', 'spending_tracker' ), strtolower( $this->post_type_args['plural'] ) ),
				'parent_item_colon'		=> sprintf( __( 'Parent %s:', 'spending_tracker' ), strtolower( $this->post_type_args['plural'] ) ),
				'not_found'						=> sprintf( __( 'No %s found.', 'spending_tracker' ), strtolower( $this->post_type_args['plural'] ) ),
				'not_found_in_trash'	=> sprintf( __( 'No %s found in Trash.', 'spending_tracker' ), strtolower( $this->post_type_args['plural'] ) ),
			) );

		 	/** Define default args for post type */
			$post_type_default_args = array(
				'description'					=> sprintf( __( 'Manage %s custom post type', 'spending_tracker' ), $this->get_type() ),
				'labels'							=> $labels,
				'supports'						=> array( 'title' ),
				'public'							=> true,
				'publicly_queryable'	=> false,
				'exclude_from_search' => true,
				'show_in_menu'				=> true,
			);

			/** Build the final post type args */
			$this->post_type_args = wp_parse_args( $this->post_type_args, $post_type_default_args );

			/** Register the post type into wordpress */
			register_post_type( $this->get_type(), $this->post_type_args );
		}

		/**
		 * Get the list of existing posts
		 *
		 * @param array   $args 	Allows to set specific arguments for getting providers.
		 * @param boolean $single Define if the return is only the requested value or all the request.
		 */
		public function get( $args = array(), $single = false ) {
			/** Get posts from database using WordPress WP_Query */
			$default_args = array(
				'post_type' => $this->get_type(),
				'posts_per_page' => -1,
			);
			$list_query = new WP_Query( wp_parse_args( $args, $default_args ) );

			/** If $single is set to true and only one result have been found return directly the only result */
			if ( ( true === $single ) && ( 1 === $list_query->post_count ) ) {
				$list_query = $this->build_model( $this->model, $list_query->post );
			} elseif ( $list_query->have_posts() ) {
				foreach ( $list_query->posts as $key => $post ) {
					$list_query->posts[ $key ] = $this->build_model( $this->model, $post );
				}

				/** Only return the element list if single parameter is set to true */
				if ( true === $single ) {
					$list_query = $list_query->posts;
				}
			}

			return $list_query;
		}

		/**
		 * Update a post into database using model definition and wordpress built in function
		 *
		 * @param  array $args Different datas to update for the post.
		 *
		 * @return object      The full object definition under defined model shape
		 */
		public function post( $args ) {
			/** Define the creation result output */
			$post_creation_result = array(
				'status'	=> true,
				'object'	=> null,
				'output'	=> '',
			);

			/** Check and sanitize datas */
			$final_args = $this->validate_datas( $this->get_type(), $this->model, $args );

			/** In case datas are validated send to the update function */
			if ( ( null !== $final_args ) && ( true === $final_args['datas_are_valid'] ) ) {
				$new_post = wp_insert_post( $final_args['datas'], true );
				if ( false === $new_post ) {
					$post_creation_result['status'] = false;
					$post_creation_result['output'] = $new_post;
				} else {
					/** Save comment metas in case there are metas to save */
					foreach ( $final_args['metas'] as $meta_key => $meta_value ) {
						update_comment_meta( $new_post, $meta_key, $meta_value );
					}

					$post_creation_result['object'] = $new_post;
				}
			} elseif ( false === $final_args['datas_are_valid'] ) {
				$post_creation_result['status'] = false;
				$post_creation_result['output'] = $final_args['errors'];
			}

			return $post_creation_result;
		}

		/**
		 * Create a post into database using model definition and wordpress built in function
		 *
		 * @param  array $args The different datas to update for the post.
		 *
		 * @return object      The full object definition under defined model shape
		 */
		public function put( $args ) {
			return $this->post( $args );
		}

		/**
		 * Display a list of existing element or an input in order to create the first item of current element
		 *
		 * @method display_element_selector
		 */
		public function display_element_selector() {
			/** Get existing providers list */
			$element_query = $this->get();

			/** Get current element complete definition */
			$element_post_type_definition = get_post_type_object( $this->get_type() );

			/** Call template for display management */
			$selector_template_path = AT_Utils::get_template_part( AT_Utils::get_current_plugin_dir(), dirname( __FILE__ ) . '/views', 'posts', array( $this->get_type(), 'post' ), 'selector' );
			ob_start();
			require( ! empty( $custom_selector_template_path ) ? $custom_selector_template_path : $selector_template_path );
			$element_selector = ob_get_clean();

			return $element_selector;
		}

	}

}
