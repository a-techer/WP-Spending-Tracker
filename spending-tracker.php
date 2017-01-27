<?php
/**
 * Plugin Name: Spending tracker
 * Description: Track your spending within your wordpress installation.
 * You will be able to know where goes your money.
 * Tags: spending, tracker, spending tracker, money,
 * Version: 1.0
 * Author: Alexandre Techer
 * Author URI: http://alexandretecher.fr
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: spending_tracker
 * Domain Path: /i18n/languages
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 * @package Spending_Tracker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Global vars for the plugin
 */
DEFINE( 'SPENDTRACK_DIR', basename( dirname( __FILE__ ) ) );
DEFINE( 'SPENDTRACK_PATH', str_replace( '\\', '/', plugin_dir_path( __FILE__ ) ) );
DEFINE( 'SPENDTRACK_URL', str_replace( str_replace( '\\', '/', ABSPATH ), site_url() . '/', SPENDTRACK_PATH ) );

/**
 * Class of main components for spending tracker
 *
 * @author Alexandre Techer <me@alexandretecher.fr>
 * @since 1.0
 */
final class Spending_Tracker {

	/**
	 * Spending tracker version
	 *
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * The single instance of the class.
	 *
	 * @var Spending_Tracker
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Spending tracker main initialisation
	 */
	public function __construct() {
		/** Load plugin translations */
		$this->load_plugin_textdomain();

		/** Initialize */
		$this->init();
	}

	/**
	 * Main Spending Tracker Instance.
	 *
	 * Ensures only one instance of Spending Tracker is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @static
	 *
	 * @return Spending Tracker - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 * - WP_LANG_DIR/spending_tracker/spending_tracker-LOCALE.mo
	 * - WP_LANG_DIR/plugins/spending_tracker-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'spending_tracker' );

		load_textdomain( 'spending_tracker', WP_LANG_DIR . '/spending_tracker/spending_tracker-' . $locale . '.mo' );
		load_plugin_textdomain( 'spending_tracker', false, SPENDTRACK_DIR . '/i18n/languages' );
	}

	/**
	 * Initialize spending tracker components
	 */
	public function init() {
		/**
		 * Utils declaration
		 */
		// require_once( SPENDTRACK_PATH . 'utils/class-utils.php' );
		if ( class_exists( 'AT_Utils' ) ) {
			AT_Utils::set_current_plugin_dir( SPENDTRACK_DIR );
		}

		/**
		 * Require and Instanciate actions for spending tracker
		 */
		require_once( SPENDTRACK_PATH . 'includes/class-st-actions.php' );
		Spending_Tracker_Actions::instance();

		/**
		 * Require transactions for spending tracker
		 */
		require_once( SPENDTRACK_PATH . 'includes/modules/transactions/class-st-transactions-filters.php' );
		new Spending_Tracker_Transactions_Filters();
	 	require_once( SPENDTRACK_PATH . 'includes/modules/transactions/class-st-transactions.php' );
		require_once( SPENDTRACK_PATH . 'includes/modules/transactions/st-transactions-functions.php' );

		/**
		 * Require ajax request listener for spending tracker
		 */
		require_once( SPENDTRACK_PATH . 'includes/class-st-ajax.php' );
		new Spending_Tracker_Ajax();

		/**
		 * Require providers module for speding tracker
		 */
		require_once( SPENDTRACK_PATH . 'includes/modules/class-stProviders.php' );
		Spending_Tracker_Providers::instance();

		/**
		 * Require projects module for speding tracker
		 */
		require_once( SPENDTRACK_PATH . 'includes/modules/class-stProjects.php' );
		Spending_Tracker_Projects::instance();

		/**
		 * Declare filters
		 */
		require_once( SPENDTRACK_PATH . 'includes/class-st-filters.php' );
		new Spending_Tracker_Filters();
	}

}

/**
 * Instanciate main spending tracker
 */
Spending_Tracker::instance();
