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
?><form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" id="atst-transaction-form" method="post" class="">
	<input type="hidden" name="action" value="atst_save_transaction" />
	<?php wp_nonce_field( 'atst_save_transaction' ); ?>
	<fieldset>
		<legend><?php esc_html_e( 'New transaction', 'spending_tracker' ); ?></legend>
		<ul class="atst-new-transaction-fields">
			<li>
				<i class="atst-field-icon atst-date-field-icon dashicons dashicons-calendar-alt"></i>
				<input type="text" name="atst-transaction[date]" value="<?php echo esc_attr( current_time( 'd/m/Y', 1 ) ); ?>" class="atst-date-field" />
			</li>
			<li>
				<input type="text" name="atst-transaction[amount]" value="" required="true" class="atst-amount-field" />
				<i class="atst-amount-field-icon">&euro;</i>
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
		</ul>

		<button>
			<i class="dashicons dashicons-welcome-add-page"></i><?php esc_html_e( 'Save', 'spending_tracker' ); ?></button>
	</fieldset>
</form>
