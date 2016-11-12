<?php
/**
 * File containing utils for the spending tracker plugin.
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package AT_Utils
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AT_Utils' ) ) {

	/**
	 * Class defining utils for the spending tracker plugin.
	 *
	 * @author Alexandre Techer <me@alexandretecher.fr>
	 * @since 1.0
	 */
	class AT_Utils {

		/**
		 *	Instanciate Utils
		 */
		public function __construct() {
			$utils_path = str_replace( '\\', '/', plugin_dir_path( __FILE__ ) );

			require_once( $utils_path . 'class-datas-utils.php' );
			require_once( $utils_path . 'class-posts-model.php' );
			require_once( $utils_path . 'class-posts.php' );
			require_once( $utils_path . 'class-comments-model.php' );
			require_once( $utils_path . 'class-comments.php' );
		}

		/**
		 * Check if a template element exists and return the path if founded
		 *
		 * @param string  $plugin_dir_name The module name that will be used into custom theme.
		 * @param string  $main_template_dir The directory where default templates are defined.
		 * @param string  $side Define in which sub directory the template have to be search.
		 * @param string  $slug The main name of template filename to search.
		 * @param string  $name A value for specifying the template to get.
		 * @param boolean $debug Allow to display debug content when problem occured while getting template file.
		 *
		 * @return string The template file path to use for requested element
		 */
		static function get_template_part( $plugin_dir_name, $main_template_dir, $side, $slug, $name = null, $debug = null ) {
			$path = '';

			/**
			 * Allow debugging
			 */
			if ( ! empty( $debug ) ) {
				echo '--- Display debug - ' . __LINE__ . ' - ' . __FUNCTION__ . ' - Start ---<br/>';
			}

			$templates = array();
			$name = (string) $name;
			if ( ! is_array( $slug ) ) {
				$slug = array( $slug );
			}
			foreach ( $slug as $slug_key ) {
				if ( '' !== $name ) {
					$templates [] = ('' !== $side ? $side : '') . "/tpl-{$slug_key}-{$name}.php";
				}
				$templates [] = ('' !== $side ? $side : '') . "/tpl-{$slug_key}.php";
			}

			/**
			 * Check if required template exists into current theme
			 */
			$check_theme_template = array();
			foreach ( $templates as $template ) {
				$check_theme_template [] = $plugin_dir_name . '/' . $template;
			}
			/**
			 * Debug mode
			 */
			if ( ! empty( $debug ) ) {
				echo '<pre>';
				print_r( $check_theme_template );
				echo '</pre>';
			}
			$path = locate_template( $check_theme_template, false );

			if ( empty( $path ) ) {
				foreach ( (array) $templates as $template_name ) {
					if ( ! $template_name ) {
						continue;
					}

					/**
					 * Allow debugging
					 */
					if ( ! empty( $debug ) ) {
						echo __LINE__ . esc_attr( " - $main_template_dir / $template_name <hr/>" );
					}

					if ( file_exists( $main_template_dir . '/' . $template_name ) ) {
						$path = $main_template_dir . '/' . $template_name;
						break;
					}
				}
			} elseif ( ! empty( $debug ) ) {
				echo __LINE__ . '- Template override by theme<hr/>';
			}

			/**
			 * Allow debugging
			 */
			if ( ! empty( $debug ) ) {
				echo '--- Display debug - END ---<br/><br/>';
			}

			return $path;
		}

		/**
		 * Support recursive arrays for WordPress wp_parse_args function
		 *
		 * @method recursive_wp_parse_args
		 *
		 * @param  array $a An array to merge with an other.
		 * @param  array $b The defautl array to merge.
		 *
		 * @return array The merged array
		 */
		public static function recursive_wp_parse_args( &$a, $b ) {
			$a = (array) $a;
			$b = (array) $b;
			$result = $b;
			foreach ( $a as $k => &$v ) {
				if ( is_array( $v ) && isset( $result[ $k ] ) ) {
					$result[ $k ] = self::recursive_wp_parse_args( $v, $result[ $k ] );
				} else {
					$result[ $k ] = $v;
				}
			}
			return $result;
		}

		/**
		 * Get the current connected user id for filling default value into model
		 */
		public function get_connected_user_id() {
			$current_user_id = 0;

			if ( function_exists( 'wp_get_current_user' ) ) {
				return wp_get_current_user()->ID;
			}

			return $current_user_id;
		}
	}

	new AT_Utils();
}
