<?php
/**
 * Template part for displaying the header navigation menu.
 */

namespace BuddyxPro\BuddyxPro;

if (!buddyxpro()->is_primary_nav_menu_active()) {
    return;
}

?>

<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small" aria-label="<?php esc_attr_e('Main menu', 'buddyxpro'); ?>"
	<?php
    if (buddyxpro()->is_amp()) {
        ?>
		[class]=" siteNavigationMenu.expanded ? 'main-navigation nav--toggle-sub nav--toggle-small nav--toggled-on' : 'main-navigation nav--toggle-sub nav--toggle-small' "
		<?php
    }
    ?>
>
	<?php
    if (buddyxpro()->is_amp()) {
        ?>
		<amp-state id="siteNavigationMenu">
			<script type="application/json">
				{
					"expanded": false
				}
			</script>
		</amp-state>
		<?php
    }
    ?>

	<button class="menu-toggle" aria-label="<?php esc_attr_e('Open menu', 'buddyxpro'); ?>" aria-controls="primary-menu" aria-expanded="false"
		<?php
        if (buddyxpro()->is_amp()) {
            ?>
			on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
			[aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'"
			<?php
        }
        ?>
	>
	<i class="fa fa-bars" aria-hidden="true"></i>
	</button>

	<div class="primary-menu-container buddyx-mobile-menu">
		<div class="mobile-menu-heading">
			<h3 class="menu-title"><?php esc_html_e('Menu', 'buddyxpro'); ?></h3>
			<a href="<?php echo esc_url('#'); ?>" class="menu-close" <?php if (buddyxpro()->is_amp()) { ?>
			on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
			[aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'"
			<?php } ?>><?php esc_html_e('Close', 'buddyxpro'); ?></a>
		</div>
		<div class="buddyx-mobile-user">
			<?php if ( is_user_logged_in() ) { ?>
				<?php
				$user_link		 = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( get_current_user_id() ) : get_author_posts_url( get_current_user_id() );
				$current_user	 = wp_get_current_user();
				?>
				<div class="user-wrap">
					<a href="<?php echo $user_link; ?>"><?php echo get_avatar( get_current_user_id(), 100 ); ?></a>
					<div>
						<a href="<?php echo $user_link; ?>"><span class="user-name"><?php echo $current_user->display_name; ?></span></a>
						<?php
						if ( function_exists( 'bp_is_active' ) && bp_is_active( 'settings' ) ) {
							$settings_link = trailingslashit( bp_loggedin_user_domain() . bp_get_settings_slug() ); ?>
							<div class="my-account-link"><a class="ab-item" aria-haspopup="true" href="<?php echo $settings_link; ?>"><?php _e( 'My Account', 'buddyxpro' ); ?></a></div><?php
						} ?>
					</div>
				</div>
			<?php } ?>
			<hr />
		</div>
		<?php buddyxpro()->display_primary_nav_menu(['menu_id' => 'primary-menu']); ?>
		<?php 
		if ( is_user_logged_in() ) {
			if ( has_nav_menu( 'user_menu' ) ) {
				echo '<hr />';
				wp_nav_menu( array( 'theme_location' => 'user_menu', 'menu_id' => 'mobile-user-profile-menu', 'fallback_cb' => '', 'container' => false, 'menu_class' => 'mobile-user-profile-menu menu', ) );
			}
		}
		?>
	</div>

	<div class="primary-menu-container buddyx-desktop-menu">
		<?php buddyxpro()->display_primary_nav_menu(['menu_id' => 'primary-menu']); ?>
	</div>
	<div class="buddypress-icons-wrapper">
		<div class="moible-icons">
			<?php buddyx_site_menu_icon(); ?>
		</div>
		<div class="desktop-icons">
			<?php buddyx_site_menu_icon(); ?>
		</div>
		<?php if ( class_exists( 'BuddyPress' ) ) {?>
			<?php get_template_part('template-parts/header/buddypress-profile'); ?>
		<?php }?>
	</div>

</nav><!-- #site-navigation -->
