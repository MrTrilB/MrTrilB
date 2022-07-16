<?php
/**
 * Header aside template.
 *
 * @link       https://wbcomdesigns.com/plugins
 * @since      1.0.0
 *
 * @package    Wbcom_Essential
 * @subpackage Wbcom_Essential/plugins/elementor/widget/buddypress/header-bar/templates
 */

$profile_dropdown   = isset( $settings['profile_dropdown'] ) ? true : false;
$profile_dropdown_menu   = isset( $settings['profile_dropdown_menu'] ) ? $settings['profile_dropdown_menu'] : '';
$show_search        = isset( $settings['search_icon_switch'] ) ? true : false;
$show_messages      = isset( $settings['messages_icon_switch'] ) ? true : false;
$show_notifications = isset( $settings['notifications_icon_switch'] ) ? true : false;
$show_shopping_cart = isset( $settings['cart_icon_switch'] ) ? true : false;

$search_icon = ( isset( $settings['search_icon']['value'] ) && '' !== $settings['search_icon']['value'] ) ? $settings['search_icon']['value'] : 'wb-icon-search';
$template = get_option( 'template' );
?>

<div id="header-aside" class="header-aside">
	<div class="header-aside-inner">
		<?php if ( is_user_logged_in() ) : ?>
			<?php if ( $profile_dropdown ) : ?>
			<div class="user-wrap user-wrap-container menu-item-has-children">
				<?php
				global $current_user;
				$current_user = wp_get_current_user();
				$user_link    = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( $current_user->ID ) : get_author_posts_url( $current_user->ID );
				$display_name = function_exists( 'bp_core_get_user_displayname' ) ? bp_core_get_user_displayname( $current_user->ID ) : $current_user->display_name;
				?>

				<a class="user-link" href="<?php echo esc_url( $user_link ); ?>">
					<span class="user-name"><?php echo esc_html( $display_name ); ?></span><i class="wb-icon-angle-down"></i>
					<?php echo get_avatar( get_current_user_id(), 100 ); ?>
				</a>

				<div class="sub-menu">
					<div class="wrapper">						
						<?php
						if ( function_exists( 'bp_is_active' ) && $profile_dropdown_menu != '' ) {
							$menu = wp_nav_menu(
								array(
									'menu' => $profile_dropdown_menu,
									'echo'           => false,
									'fallback_cb'    => '__return_false',
								)
							);
							if ( ! empty( $menu ) ) {
								wp_nav_menu(
									array(
										'menu' => $profile_dropdown_menu,
										'menu_id'     => 'header-my-account-menu',
										'container'   => false,
										'fallback_cb' => '',
										'walker'      => '', // add walker.
										'menu_class'  => 'wbcom-essential-my-account-menu',
									)
								);
							} else {
								do_action( 'wbcom_essential_header_user_menu_items' );
							}
						} else {
							do_action( 'wbcom_essential_header_user_menu_items' );
						}
						?>						
					</div>
				</div>
			</div>
			<?php endif; ?>
			<!-- Separator -->
			<?php if ( $show_search || $show_messages || $show_notifications || $show_shopping_cart ) : ?>
					<span class="wbcom-essential-separator"></span>
			<?php endif; ?>
			<?php if ( $show_search ) : ?>
				<a href="#" class="header-search-link" data-balloon-pos="down" data-balloon="<?php esc_html_e( 'Search', 'wbcom-essential' ); ?>"><i class="<?php echo esc_attr( $search_icon ); ?>"></i></a>
				<?php
			endif;

			if ( $show_messages && function_exists( 'bp_is_active' ) && bp_is_active( 'messages' ) ) :

				$messages_dropdown_template_path = WBCOM_ESSENTIAL_ELEMENTOR_WIDGET_PATH . '/Buddypress/header-bar/templates/messages-dropdown.php';

				if ( file_exists( $messages_dropdown_template_path ) ) {
					require $messages_dropdown_template_path;
				}

			endif;

			if ( $show_notifications && function_exists( 'bp_is_active' ) && bp_is_active( 'notifications' ) ) :

				$notification_dropdown_template_path = WBCOM_ESSENTIAL_ELEMENTOR_WIDGET_PATH . '/Buddypress/header-bar/templates/notification-dropdown.php';

				if ( file_exists( $notification_dropdown_template_path ) ) {
					require $notification_dropdown_template_path;
				}

			endif;
			if ( $show_shopping_cart && class_exists( 'WooCommerce' ) ) :


				$cart_dropdown_template_path = WBCOM_ESSENTIAL_ELEMENTOR_WIDGET_PATH . '/Buddypress/header-bar/templates/cart-dropdown.php';

				if ( file_exists( $cart_dropdown_template_path ) ) {
					require $cart_dropdown_template_path;
				}

			endif;
			?>
		<?php else : ?>

			<?php if ( $show_search ) : ?>
				<a href="#" class="header-search-link" data-balloon-pos="down" data-balloon="<?php esc_attr_e( 'Search', 'wbcom-essential' ); ?>"><i class="<?php echo esc_attr( $search_icon ); ?>"></i></a>
			<?php endif; ?>

			<?php
			if ( $show_shopping_cart && class_exists( 'WooCommerce' ) ) :

				$cart_dropdown_template_path = WBCOM_ESSENTIAL_ELEMENTOR_WIDGET_PATH . '/Buddypress/header-bar/templates/cart-dropdown.php';

				if ( file_exists( $cart_dropdown_template_path ) ) {
					require $cart_dropdown_template_path;
				}

			endif;
			?>
			<span class="search-separator wbcom-essential-separator"></span>
			<div class="wbcom-essential-header-buttons buddypress-icons-wrapper">
				<?php 
				
				if ( $template == 'reign-theme' ) {
					get_template_part( 'template-parts/header-icons/login','' );
					
					if ( get_option( 'users_can_register' ) ) : 
						get_template_part( 'template-parts/header-icons/register-menu','' );
					endif;
				} elseif ( $template == 'buddyx' || $template == 'buddyx-pro') {
					
					get_template_part( 'template-parts/header/buddypress-profile','' );
				
				} else {
				?>
			
					<a href="<?php echo esc_url( wp_login_url() ); ?>" class="button small outline signin-button link btn-login"><?php esc_html_e( 'Sign in', 'wbcom-essential' ); ?></a>

					<?php if ( get_option( 'users_can_register' ) ) : ?>
						<a href="<?php echo esc_url( wp_registration_url() ); ?>" class="button small singup btn-register"><?php esc_html_e( 'Sign up', 'wbcom-essential' ); ?></a>
					<?php endif;
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</div>
