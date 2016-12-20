<?php
/**
 * The Template for displaying
 *
 * This template can be overridden by copying it to yourtheme/yourplugindirname/posts/tpl-post-selector.php
 * This template can be overridden by copying it to yourtheme/yourplugindirname/posts/tpl-{post_type}-selector.php
 *
 * @author 		Alexandre Techer
 * @package 	Spending_Tracker/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$hide_creation_input = false;
if ( ! empty( $element_query ) && $element_query->have_posts() ) :
	$hide_creation_input = true;
?>
<select class="atst-post-list" name="atst-transaction[<?php echo esc_attr( $this->get_type() ); ?>]" >
<?php foreach ( $element_query->posts as $element ) : ?>
	<option value="<?php echo esc_attr( $element->id ); ?>"><?php echo esc_html( $element->name ); ?></option>
<?php endforeach; ?>
</select>
<i class="atst-new-post-item-button dashicons dashicons-plus"></i>
<?php endif; ?>
<input type="text" class="atst-new-post-item-input<?php echo esc_attr( true === $hide_creation_input ? ' hidden' : '' ); ?>"
	name="atst-transaction[<?php echo esc_attr( $this->get_type() ); ?>-new]" value=""
	placeholder="<?php printf( esc_html( 'New %s name', 'spending_tracker' ), esc_html( strtolower( $element_post_type_definition->labels->singular_name ) ) ); ?>" />
