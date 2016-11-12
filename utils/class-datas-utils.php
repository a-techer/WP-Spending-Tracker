<?php
/**
 * File of main components for spending tracker
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package AT_Utils
 * @subpackage AT_Utils_Datas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AT_Datas_Utils' ) ) {

	/**
	 * Class of main components for spending tracker
	 *
	 * @author Alexandre Techer <me@alexandretecher.fr>
	 * @since 1.0
	 */
	class AT_Datas_Utils extends AT_Utils {

		/**
		 * Datas utils instanciation
		 */
		public function __construct() { }

		/**
		 * Check datas sended by the final user in order to know if they exists for current element and if the format is correct
		 *
		 * @param string $element_type Define the current element type.
		 * @param array  $model Field defining the model of current element in order to check and validate datas.
		 * @param array  $datas Datas sended by the final user to save.
		 *
		 * @return array Available and sanitized datas for current element
		 */
		public function validate_datas( $element_type, $model, $datas ) {
			/** First start by defining the array used to return datas validate and formated for saving */
			$valid_datas = array(
				'datas_are_valid'	=> true,
				'errors'					=> array(),
				'datas'						=> array(),
			);

			/** Allows to anybody to alter datas before checking the validity regarding the model definition */
			$datas = apply_filters( "atst_filter_validate_data_{$element_type}", $datas );

			/** If there are datas sended by final user start validation and formatting */
			foreach ( $model as $field_key => $field_definition ) {
				/** Check if there is a default value setted into the model definition */
				if ( array_key_exists( 'default', $field_definition ) ) {
					$valid_datas['datas'][ $field_definition['db_field'] ] = $field_definition['default'];
				}
				/** Otherwise check if there is a callback function setted into model in order to set the required value */
				if ( array_key_exists( 'callback', $field_definition ) && method_exists( $this, $field_definition['callback'] ) ) {
					$valid_datas['datas'][ $field_definition['db_field'] ] = call_user_func( array( $this, $field_definition['callback'] ) );
				}

				/** Finally check if there is a value sended by the final user for the current field */
				if ( array_key_exists( $field_key, $datas ) ) {
					$valid_datas['datas'][ $field_definition['db_field'] ] = $datas[ $field_key ];
				}

				/** Check if the current field is required */
				if ( array_key_exists( 'required', $field_definition ) && empty( $valid_datas['datas'][ $field_definition['db_field'] ] ) ) {
					$valid_datas['datas_are_valid'] = false;
					$valid_datas['errors'][] = array(
						'field'		=> $field_definition['db_field'],
						'message'	=> sprintf( __( 'Field %s is missing. Please fill it and renew your request', 'spending_tracker' ), $field_definition['db_field'] ),
					);
				}
			}

			/** Return datas validated and formated for saving */
			return $valid_datas;
		}

		/**
		 * Return an element builded with its model definition
		 *
		 * @method build_model
		 * @param  array  $model Element model to fit datas with.
		 * @param  object $datas Datas to fit to model.
		 *
		 * @return object            The element builded with the defined model
		 */
		public function build_model( $model, $datas ) {
			$builded_datas = new stdClass();

			if ( ! empty( $datas ) && ! empty( $model ) ) {
				foreach ( $model as $output_field => $field_definition ) {
					if ( isset( $datas->$field_definition['db_field'] ) ) {
						$builded_datas->$output_field = $datas->$field_definition['db_field'];
					} elseif ( ( true === $field_definition['required'] ) && ( isset( $field_definition['default'] ) ) ) {
						$builded_datas->$output_field = $datas->$field_definition['db_field'];
					}
				}
			}

			return $builded_datas;
		}

	}
}
