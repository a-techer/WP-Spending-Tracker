<?php
/**
 * The Template for displaying spending tracker transaction form
 *
 * This template can be overridden by copying it to yourtheme/spending_tracker/transactions/tpl-form.php
 *
 * @author 		Alexandre Techer
 * @package 	Spending_Tracker/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?><form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" id="atst-transaction-form" method="post" class="atst-block" >
	<input type="hidden" name="action" value="atst_save_transaction" />
	<?php wp_nonce_field( 'atst_save_transaction' ); ?>
	<ul class="atst-new-transaction-fields">
		<li class="atst-transaction-type-line atst-block" >
			<div data-type="expense" class="atst-transaction-current-type" ><?php esc_html_e( 'Expense', 'spending_tracker' ); ?></div>
			<div data-type="income" ><?php esc_html_e( 'Income', 'spending_tracker' ); ?></div>
		</li>
		<li class="atst-transaction-field-line atst-transaction-field-date" >
			<i class="atst-field-icon atst-date-field-icon dashicons dashicons-calendar-alt"></i>
			<input type="text" name="atst-transaction[date]" value="<?php echo esc_attr( current_time( 'd/m/Y', 0 ) ); ?>" class="atst-date-field" />
		</li>
		<li class="atst-transaction-field-line atst-transaction-field-amount atst-transaction-amount-expense" >
			<input type="text" name="atst-transaction[amount]" value="" required="true" class="atst-amount-field" />
			<i class="atst-amount-field-icon">&euro;</i>
		</li>
		<li class="atst-transaction-field-line atst-transaction-field-recurrent" >
			<label>
				<input type="checkbox" name="atst-transaction[options][is_recurrent]" value="yes" class="atst-recurrent-field" />
				<?php esc_html_e( 'Recurrent transaction', 'spending_tracker' ); ?>
			</label>
		</li>

<?php
	/**
	 * Extend transaction form using a filter.
	 * Allowing any third party to add field to the form.
	 *
	 * @var $extra_fields
	 */
	$extra_fields = apply_filters( 'atst_filter_transaction_form_fields', array() );
?>
<?php if ( ! empty( $extra_fields ) ) : ?>
	<?php foreach ( $extra_fields as $field_output ) : ?>
		<li class="atst-extra-field <?php echo sanitize_html_class( $field_output['class'] ); ?>"><?php echo $field_output['html']; ?></li>
	<?php endforeach; ?>
<?php endif; ?>
		<!-- / Extra fields -->

		<li class="atst-transaction-action-line atst-block" >
			<div>
				<a href="#" class="atst-transaction-more-options-opener" ><?php esc_html_e( 'more options', 'spending_tracker' ); ?></a>
				<div class="" ></div>
			</div>
			<div><button class="alignright" ><i class="dashicons dashicons-welcome-add-page"></i><?php esc_html_e( 'Save', 'spending_tracker' ); ?></button></div>
		</li>
	</ul>
</form>
