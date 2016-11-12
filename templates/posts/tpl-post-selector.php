<?php
/**
 * The Template for displaying
 *
 * This template can be overridden by copying it to yourtheme/spending-tracker/posts/tpl-post-selector.php
 * This template can be overridden by copying it to yourtheme/spending-tracker/posts/tpl-{post_type}-selector.php
 *
 * @author 		Alexandre Techer
 * @package 	Spending_Tracker/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! empty( $element_query ) && $element_query->have_posts() ) : ?>
<select name="atst-transaction[<?php echo esc_attr( $this->get_type() ); ?>]">
<?php foreach ( $element_query->posts as $element ) : ?>
	<option value="<?php echo esc_attr( $element->id ); ?>"><?php echo esc_html( $element->name ); ?></option>
<?php endforeach; ?>
</select>
<i class="atst-new-item-button dashicons dashicons-plus"></i>
<?php else : ?>
<input type="text" name="atst-transaction[<?php echo esc_attr( $this->get_type() ); ?>]" value="" placeholder="<?php printf( esc_html( 'New %s name', 'spending_tracker' ), esc_html( strtolower( $element_post_type_definition->labels->singular_name ) ) ); ?>" />
<?php endif; ?>
