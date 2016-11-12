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
			'type'		=> 'integer',
			'db_field'	=> 'comment_approved',
			'default'	=> 97431,
		),
		'amount'	=> array(
			'type'		=> 'float',
			'db_field'	=> 'comment_content',
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
			'type'		=> 'string',
			'db_field'	=> 'comment_type',
			'default'	=> $this->get_type(),
		);

		/** Build the final model from current element specific and common model */
		$this->model = array_merge( $this->model, $comment_model->get_basic_model() );
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
		require_once( AT_Utils::get_template_part( SPENDTRACK_DIR, SPENDTRACK_PATH . 'templates', 'transactions', 'form' ) );
	}

}
