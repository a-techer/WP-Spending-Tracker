<?php
/**
 * File managing transactions for spending tracker
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package Spending_Tracker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class managing transactions for spending tracker
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 */
class Spending_Tracker_Transactions extends AT_Comments_Utils {

	/**
	 * Define the comment type to use for transactions
	 *
	 * @var string
	 */
	protected $type = 'atst-transaction';

	/**
	 * The single instance of the class.
	 *
	 * @var Spending_Tracker_Transactions
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Define a transaction model.
	 *
	 * @var array
	 */
	protected $model = array(
		'status'	=> array(
			'type'				=> 'integer',
			'db_field'		=> 'comment_approved',
			'default'			=> 97431,
		),
		'amount'	=> array(
			'type'			=> 'float',
			'db_field'	=> 'comment_content',
		),
		'options'	=> array(
			'is_recurrent' => array(
				'type'			=> 'boolean',
				'db_field'	=> '_atst_is_recurrent',
			),
		),
	);

	/**
	 * Initialize transactions management
	 *
	 * @since 1.0
	 */
	public function __construct() {
		/** Declare and build comments model */
		$comment_model = new AT_Comments_Model();

		/** Set the transaction comment type */
		$this->model['type'] = array(
			'type'			=> 'string',
			'db_field'	=> 'comment_type',
			'default'		=> $this->get_type(),
		);

		/** Build the final model from current element specific and common model */
		$this->model = wp_parse_args( $this->model, $comment_model->get_basic_model( array( 'content' ) ) );

		add_action( 'comment_post', array( $this, 'action_comment_post' ), 10, 3 );
	}

	/**
	 * Return existing instance of the class.
	 * Create a new one if not existing
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Display the form allowing to create/edit a transaction
	 */
	public function display_transaction_form() {
		AT_Utils::get_template_part( SPENDTRACK_DIR, SPENDTRACK_PATH . 'templates', 'transactions', 'form' );
	}

	/**
	 * Display coming next transaction
	 */
	public function display_next_transaction( $args ) {
		/** Get next transactions */
		$transactions_query = $this->get( array(
			'status'	=> 97431,
			'options'	=> array(
				'is_recurrent'	=> 'yes',
			),
		) );

		/** Check if there are transaction comming */
		$transactions = array();
		if ( ! empty( $transactions_query ) && ! empty( $transactions_query->comments ) ) {
			$transactions = $transactions_query->comments;
		}

		AT_Utils::get_template_part( SPENDTRACK_DIR, SPENDTRACK_PATH . 'templates', 'transactions', 'next', 'list', false );
	}

	/**
	 * Display last transaction list
	 */
	public function display_last_transaction( $args ) {
		/** Get next transactions */
		$transactions_query = $this->get( array(
			'status'	=> 97431,
		) );

		/** Check if there are transaction comming */
		$transactions = array();
		if ( ! empty( $transactions_query ) && ! empty( $transactions_query->comments ) ) {
			$transactions = $transactions_query->comments;
		}

		AT_Utils::get_template_part( SPENDTRACK_DIR, SPENDTRACK_PATH . 'templates', 'transactions', 'next', 'list', false );
	}

	/**
	 * Action to launch after a transaction save. It will be launch using the comment_post action declared by WordPress
	 *
	 * @param  integer    $comment_id       Last inserted comment to hook the save.
	 * @param  int|string $comment_approved The current status for last inserted comment.
	 * @param  array      $comment_data     Optionnal. An array with the current comment datas.
	 */
	public function action_comment_post( $comment_id, $comment_approved, $comment_data ) {
		if ( $this->get_type() === get_comment_type( $comment_id ) ) {
			wp_update_comment( array(
				'comment_ID'				=> $comment_id,
				'comment_approved'	=> 97431,
			) );
		}
	}

}
