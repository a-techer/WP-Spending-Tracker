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
		</h1>

		<div class="atst-alert hidden" id="atst-alert" ></div>
	</div>
	<!-- atst-dashboard-header -->

	<div class="atst-main-panel">
		<div id="atst-summary-panel" class="atst-block metabox-holder atst-summary-panel">
			<?php do_meta_boxes( $screen->id, 'atst-normal', '' ); ?>
		</div>
	</div>
	<!-- atst-main-panel -->
</div>
<!-- wrap -->
<?php
/** Add nonce field to dashboard for metaboxes state saving */
wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
