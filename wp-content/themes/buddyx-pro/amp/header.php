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
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php buddyx_site_loader(); ?>
<?php buddyx_wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="<?php echo esc_url( '#primary' ); ?>"><?php esc_html_e( 'Skip to content', 'buddyxpro' ); ?></a>
	
	<div class="site-header-wrapper amp-site-header-wrapper">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) { ?>
			<header id="masthead" class="buddyx-amp-header">
				<!--  Add AMP header html code -->
				<?php get_template_part( 'amp/branding' ); ?>
			</header><!-- #masthead -->
		<?php } ?>
    </div>
