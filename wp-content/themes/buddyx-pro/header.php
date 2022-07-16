<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<?php do_action( 'buddyx_head_top' ); ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<?php do_action( 'buddyx_head_bottom' ); ?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'buddyx_body_top' ); ?>

<?php buddyx_site_loader(); ?>
<?php buddyx_wp_body_open(); ?>
<div id="page" class="site">
	
	<?php do_action( 'buddyx_page_top' ); ?>

	<a class="skip-link screen-reader-text" href="<?php echo esc_url( '#primary' ); ?>"><?php esc_html_e( 'Skip to content', 'buddyxpro' ); ?></a>
	<?php if ( class_exists( 'WooCommerce' ) ) {
		buddyx_cart_widget_side();
	} ?>
	<?php
	if ( class_exists( 'WooCommerce' ) && true == get_theme_mod( 'buddyx_woo_off_canvas_filter', false ) ) {
		buddyx_filters_widget_side();
	}
	$enable_topbar = get_theme_mod( 'site_topbar_enable', buddyx_defaults( 'site-topbar-enable' ) );
	 if($enable_topbar == '1') :
		buddyx_topbar_content();
	endif; ?>

	<?php do_action( 'buddyx_header_before' ); ?>

	<div class="site-header-wrapper">
		<?php if ( is_single() && get_post_type() == 'post' && true == get_theme_mod( 'single_post_progressbar', buddyx_defaults( 'single-post-progressbar' ) ) ) : ?>
			<div class="buddyx-progress-bar">
				<progress value="0" id="progressBar"></progress>
			</div>
		<?php endif; ?>
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) { ?>
			<div class="container">
				<header id="masthead" class="site-header">
					
					<?php get_template_part( 'template-parts/header/custom_header' ); ?>

					<?php get_template_part( 'template-parts/header/branding' ); ?>

					<?php get_template_part( 'template-parts/header/navigation' ); ?>
					
				</header><!-- #masthead -->
			</div>
		<?php } ?>
    </div>

	<?php do_action( 'buddyx_header_after' ); ?>
