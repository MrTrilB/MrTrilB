<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

if ( ! buddyxpro()->is_left_sidebar_active() || ! buddyxpro()->is_right_sidebar_active() ) {
	return;
}

buddyxpro()->print_styles( 'buddyxpro-sidebar', 'buddyxpro-widgets' );

?>

<?php do_action( 'buddyx_sidebar_before' ); ?>

<aside id="secondary" class="primary-sidebar widget-area">
	<div class="sticky-sidebar">
		<?php
		$sidebar = get_theme_mod( 'sidebar_option' );
		switch ( $sidebar ) {
			case 'left':
				buddyxpro()->display_left_sidebar();
				break;
			case 'right':
				buddyxpro()->display_right_sidebar();
				break;
			default:
		}
		?>
	</div>
</aside><!-- #secondary -->

<?php do_action( 'buddyx_sidebar_after' ); ?>