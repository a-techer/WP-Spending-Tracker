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
				'metas'						=> array(),
			);

			/** Allows to anybody to alter datas before checking the validity regarding the model definition */
			$datas = apply_filters( "atst_filter_validate_data_{$element_type}", $datas );

			/** Do treatment on element metas before treating datas of main model */
			if ( ! empty( $datas['options'] ) ) {
				$valid_datas = wp_parse_args( $this->read_model_and_build_datas( $model['options'], $datas['options'], 'metas' ), $valid_datas );
				unset( $model['options'] );
			}

			/** If there are datas sended by final user start validation and formatting */
			$valid_datas = wp_parse_args( $this->read_model_and_build_datas( $model, $datas ), $valid_datas );

			/** Return datas validated and formated for saving */
			return $valid_datas;
		}

		/**
		 * Read a given model structure, build the final datas or return error due to missing field or bad given type
		 *
		 * @param array  $model_definition Datas model defining the current element.
		 * @param array  $datas Datas sended by the final user.
		 * @param string $data_key Optinnal The key to use to store returned data. datas|metas.
		 *
		 * @return array Datas stored correctly or an error message if there is a missing field or if datas sended by final users are not correctly formed
		 */
		function read_model_and_build_datas( $model_definition, $datas, $data_key = 'datas' ) {

			/** Read datas model to build datas to save */
			foreach ( $model_definition as $field_key => $field_definition ) {
				/** Check if there is a default value setted into the model definition */
				if ( array_key_exists( 'default', $field_definition ) ) {
					$valid_datas[ $data_key ][ $field_definition['db_field'] ] = $field_definition['default'];
				}

				/** Otherwise check if there is a callback function setted into model in order to set the required value */
				if ( array_key_exists( 'callback', $field_definition ) && method_exists( $this, $field_definition['callback'] ) ) {
					$valid_datas[ $data_key ][ $field_definition['db_field'] ] = call_user_func( array( $this, $field_definition['callback'] ), array( $datas[ $field_key ] ) );
					unset( $datas[ $field_key ] );
				}

				/** Finally check if there is a value sended by the final user for the current field */
				if ( array_key_exists( $field_key, $datas ) ) {
					$valid_datas[ $data_key ][ $field_definition['db_field'] ] = $datas[ $field_key ];
				}

				/** Check if the current field is required */
				if ( array_key_exists( 'required', $field_definition ) && ( true === $field_definition['required'] ) && empty( $valid_datas[ $data_key ][ $field_definition['db_field'] ] ) ) {
					$valid_datas['datas_are_valid'] = false;
					$valid_datas['errors'][] = array(
						'field'		=> $field_definition['db_field'],
						'message'	=> sprintf( __( 'Field %s is missing. Please fill it and renew your request', 'spending_tracker' ), $field_definition['db_field'] ),
					);
				}
			}

			return $valid_datas;
		}

		/**
		 * Return an element builded with its model definition
		 *
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

		/**
		 * Return an element builded with its model definition
		 *
		 * @param  array  $model Element model to fit datas with.
		 * @param  object $datas Datas to fit to model.
		 *
		 * @return array Arguments for the element query
		 */
		public function build_query_from_model( $model, $datas ) {
			$final_args = array();

			if ( ! empty( $datas ) && ! empty( $model ) ) {
				foreach ( $datas as $field => $value ) {
					if ( array_key_exists( $field, $model ) && array_key_exists( 'query_field', $model[ $field ] ) ) {
						$final_args[ $model[ $field ]['query_field'] ] = $value;
					} elseif ( array_key_exists( $field, $model ) && ( 'options' !== $field ) ) {
						$final_args[ $field ] = $value;
					} elseif ( ( 'options' === $field ) && is_array( $value ) ) {
						foreach ( $value as $meta_key => $meta_value ) {
							$final_args['meta_query'][] = array( 'key' => $model['options'][ $meta_key ]['db_field'], 'value' => $meta_value );
						}
					}
				}
			}

			return $final_args;
		}

		/**
		 * Format the date before saving into database
		 *
		 * @param  array $args An array with the date to format.
		 *
		 * @return string The formatted date
		 */
		public function date_format( $args ) {
			$date_to_timestamp = strtotime( str_replace( '/', '-', $args[0] ) );

			return date( 'Y-m-d', $date_to_timestamp );
		}

	}
}
