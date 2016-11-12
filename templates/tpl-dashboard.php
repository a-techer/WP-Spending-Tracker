<?php
/**
 * The Template for displaying spending tracker dashboard
 *
 * This template can be overridden by copying it to yourtheme/spending-tracker/tpl-dashboard.php.
 *
 * @author 		Alexandre Techer
 * @package 	Spending_Tracker/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?><div class="wrap">
	<div class="atst-block atst-dashboad-header">
		<h1 class="alignleft">
			<i class="atst-dashboard-icon dashicons dashicons-money"></i>
			<?php esc_html_e( 'Spending tracker', 'spending_tracker' ); ?>
			<!-- <a class="page-title-action atst-operations-button" href="#" ><?php esc_html_e( 'New transaction', 'spending_tracker' ); ?></a> -->
		</h1>

		<?php
		/**
		 * Call the transaction form display
		 */
		Spending_Tracker_Transactions::instance()->display_transaction_form();
		?>
	</div>
	<!-- atst-dashboard-header -->

	<div class="atst-main-panel">
		<div id="atst-summary-panel" class="atst-summary-panel">Summary panel
		</div>
	</div>
	<!-- atst-main-panel -->
</div>
<!-- wrap -->
