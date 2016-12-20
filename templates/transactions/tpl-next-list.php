<?php
/**
 * Template file for displaying coming transactions
 *
 * This template can be overridden by copying it to yourtheme/spending_tracker/transactions/tpl-next-list.php
 *
 * @author 		Alexandre Techer
 * @package 	Spending_Tracker/Templates
 * @version   1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/** Check if there are transactions  */
if ( ! empty( $transactions ) ) : ?>
<ul>
<?php foreach ( $transactions as $transaction ) : ?>
	<li>
		<span><?php echo esc_html( mysql2date( get_option( 'date_format' ), $transaction->date, true ) ); ?></span>
		<span><?php echo esc_html( $transaction->user_name ); ?></span>
		<span><?php echo esc_html( $transaction->amount ); ?></span>
	</li>
<?php endforeach; ?>
</ul>
<?php else : ?>
<div class="atst-alert atst-alert-warning" ><?php esc_html_e( 'There are no transactions to display here', 'spending_tracker' ); ?></div>
<?php endif; ?>
