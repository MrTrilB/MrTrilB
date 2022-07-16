<?php
/**
 * Template part for displaying the header branding
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

?>

<div class="site-branding">
	<div class="site-logo-wrapper">
		<?php the_custom_logo(); ?>
	</div>
	<div class="site-branding-inner">
		<?php
		if ( is_front_page() && is_home() ) {
			?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php
		} else {
			?>
			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
		}
		?>

		<?php
		$buddyxpro_description = get_bloginfo( 'description', 'display' );
		if ( $buddyxpro_description || is_customize_preview() ) {
			?>
			<p class="site-description">
				<?php echo $buddyxpro_description; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
			</p>
			<?php
		}
		?>
	</div>
</div><!-- .site-branding -->
