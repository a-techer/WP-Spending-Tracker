<?php
/**
 * File for model utilities definition
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package AT_Utils
 * @subpackage AT_Models_Utils
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AT_Models_Utils' ) ) {

	/**
	 * Class for model utilities definition
	 *
	 * @author Alexandre Techer <me@alexandretecher.fr>
	 * @since 1.0
	 */
	class AT_Models_Utils {

		/**
		 * Model utilities instanciation
		 */
		public function __construct() {}

		/**
		 * Get and return the basic model for the current element.
		 * Which is a basic element from wordpress database
		 *
		 * @param array $exclude Optionnal An array of fields of the model to exclude from basic model to return.
		 *
		 * @return array The basic model of comments into wordpress
		 */
		public function get_basic_model( $exclude = array() ) {

			/** Check if there are fields to exclude from basic model */
			if ( ! empty( $exclude ) && is_array( $exclude ) ) {
				foreach ( $exclude as $field_to_exclude ) {
					if ( array_key_exists( $field_to_exclude, $this->basic_model ) ) {
						unset( $this->basic_model[ $field_to_exclude ] );
					}
				}
			}

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
