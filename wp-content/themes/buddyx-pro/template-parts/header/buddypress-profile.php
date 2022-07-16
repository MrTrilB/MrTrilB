<?php
/**
 * BuddyX notification nav
 *
 * Displays in the notification navbar
 *
 * @package buddyxpro
 * @since 1.0.0
 */

/** Do not allow directly accessing this file. */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<?php
// User Messages.
if ( class_exists( 'BuddyPress' ) && is_user_logged_in() && bp_is_active( 'messages' ) ) {
	?>
	<div class="bp-msg user-menu-dropdown">
		<div id="private-message-list" class="nav-item private-message-list">
			<a class="user-menu-dropdown-toggle" href="#" id="nav_private_messages" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa fa-envelope"></i>
				<?php if ( messages_get_unread_count( bp_loggedin_user_id() ) > 0 ) : ?>
					<?php if ( messages_get_unread_count( bp_loggedin_user_id() ) > 9 ) : ?>
						<sup class="count"><?php esc_html_e( '9+', 'buddyxpro' ); ?></sup>
					<?php else : ?>
						<sup class="count"><?php echo esc_html( messages_get_unread_count() ); ?></sup>
					<?php endif; ?>
				<?php endif; ?>
			</a>
			<div class="user-menu-dropdown-menu" aria-labelledby="nav_private_messages">
				<div class="dropdown-title"><?php esc_html_e( 'Unread messages', 'buddyxpro' ); ?></div>
					<?php
					if ( bp_has_message_threads(
						array(
							'user_id'  => bp_loggedin_user_id(),
							'type'     => 'unread',
							'per_page' => 10,
							'max'      => 10,
						)
					) ) :
						?>
					<div class="user-menu-dropdown-item-wrapper">
						<?php
						while ( bp_message_threads() ) :
							bp_message_thread();
							?>
							<div class="dropdown-item">
								<div class="item-avatar">
									<?php bp_message_thread_avatar( 'type=thumb&width=40&height=40' ); ?>
								</div>
								<div class="item-info">
									<div class="dropdown-item-title message-subject ellipsis">
										<a href="<?php bp_message_thread_view_link( bp_get_message_thread_id(), bp_loggedin_user_id() ); ?>" class="color-primary"><?php bp_message_thread_subject(); ?></a>
									</div>
									<p class="mute"><?php bp_message_thread_last_post_date(); ?></p>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				<?php else : ?>
					<div class="alert-message">
						<div class="alert alert-warning" role="alert"><?php esc_html_e( 'No messages to read.', 'buddyxpro' ); ?></div>
					</div>
				<?php endif; ?>
				<div class="dropdown-footer">
					<a href="<?php echo esc_url( trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() . '/inbox' ) ); ?>" class="button"><?php esc_html_e( 'All Messages', 'buddyxpro' ); ?></a>
				</div>
			</div>
		</div>
</div>
	<?php
}
// User notifications.
if ( class_exists( 'BuddyPress' ) && is_user_logged_in() && bp_is_active( 'notifications' ) ) {
	?>
	<div class="user-notifications user-menu-dropdown">
		<div id="notification-list" class="nav-item notification-list">
			<a class="nav-link user-menu-dropdown-toggle" href="#" id="nav_notification" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa fa-bell"></i>
				<?php if ( bp_notifications_get_unread_notification_count( bp_loggedin_user_id() ) > 0 ) : ?>
					<?php if ( bp_notifications_get_unread_notification_count( bp_loggedin_user_id() ) > 9 ) : ?>
						<sup class="count"><?php esc_html_e( '9+', 'buddyxpro' ); ?></sup>
					<?php else : ?>
						<sup class="count"><?php echo esc_html( bp_notifications_get_unread_notification_count( bp_loggedin_user_id() ) ); ?></sup>
					<?php endif; ?>
				<?php endif; ?>
			</a>
			<div class="user-menu-dropdown-menu" aria-labelledby="nav_notification">
				<div class="dropdown-title"><?php esc_html_e( 'Notifications', 'buddyxpro' ); ?></div>
				<?php
				if ( bp_has_notifications(
					array(
						'user_id'  => bp_loggedin_user_id(),
						'per_page' => 10,
						'max'      => 10,
					)
				) ) :
					?>
				<div class="user-menu-dropdown-item-wrapper">
					<?php
					while ( bp_the_notifications() ) :
						bp_the_notification();
						?>
						<div class="dropdown-item">
							<div class="dropdown-item-title notification ellipsis"><?php bp_the_notification_description(); ?></div>
							<p class="mute"><?php bp_the_notification_time_since(); ?></p>
						</div>
					<?php endwhile; ?>
				</div>
				<?php else : ?>
					<div class="alert-message">
						<div class="alert alert-warning" role="alert"><?php esc_html_e( 'No notifications found.', 'buddyxpro' ); ?></div>
					</div>
				<?php endif; ?>
				<div class="dropdown-footer">
					<a href="<?php echo esc_url( trailingslashit( bp_loggedin_user_domain() . bp_get_notifications_slug() . '/unread' ) ); ?>" class="button"><?php esc_html_e( 'All Notifications', 'buddyxpro' ); ?></a>
				</div>
			</div>
		</div>
	</div>
	<?php
}
// User Account Details.
if ( is_user_logged_in() ) {
	$loggedin_user = wp_get_current_user();
	if ( ( $loggedin_user instanceof WP_User ) ) {
		$user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( get_current_user_id() ) : '#';
		echo '<div class="user-link-wrap">';
		echo '<a class="user-link" href="' . esc_url( $user_link ) . '">';
		?>
		<span class="bp-user"><?php echo $loggedin_user->display_name; ?></span>
		<?php
		echo get_avatar( $loggedin_user->user_email, 100 );
		echo '</a>';
		wp_nav_menu(
			array(
				'theme_location' => 'user_menu',
				'menu_id'        => 'user-profile-menu',
				'fallback_cb'    => '',
				'container'      => false,
				'menu_class'     => 'user-profile-menu',
			)
		);
		echo '</div>';
	}
} else {
	global $wbtm_buddyx_settings;

	$login_page_url = wp_login_url();
	if ( isset( $settings['buddyx_pages']['buddyx_login_page'] ) && ( $wbtm_buddyx_settings['buddyx_pages']['buddyx_login_page'] != '-1' ) ) {
		$login_page_id  = $wbtm_buddyx_settings['buddyx_pages']['buddyx_login_page'];
		$login_page_url = get_permalink( $login_page_id );
	}

	$buddyx_signin_popup = get_theme_mod( 'buddyx_signin_popup', buddyx_defaults( 'buddyx-signin-popup' ) );
	$form_type_login     = get_theme_mod( 'buddyx_sign_form_popup', 'default' );
	$forms               = get_theme_mod( 'buddyx_sign_form_display', 'login' );

	if ( ( $form_type_login == 'custom' || $forms == 'login' || $forms == 'both' ) && $buddyx_signin_popup == true ) {
		$login_page_url = '#';
	}

	$registration_page_url = wp_registration_url();
	if ( isset( $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'] ) && ( $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'] != '-1' ) ) {
		$registration_page_id  = $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'];
		$registration_page_url = get_permalink( $registration_page_id );
	}

	if ( ( $form_type_login != 'custom' && ( $forms == 'register' || $forms == 'both' ) ) && $buddyx_signin_popup == true ) {
		$registration_page_url = '#';
	}
	?>

	<?php
	$site_header_button_style = get_theme_mod( 'site_header_button_style' );
	if ( $site_header_button_style == 'only-icon' ) {
		$button_class = 'only-icon';
	} elseif ( $site_header_button_style == 'only-text' ) {
		$button_class = 'only-text';
	} elseif ( $site_header_button_style == 'icon-text-button' ) {
		$button_class = 'icon-text-button';
	} elseif ( $site_header_button_style == 'icon-button' ) {
		$button_class = 'icon-button';
	} elseif ( $site_header_button_style == 'text-button' ) {
		$button_class = 'text-button';
	} else {
		$button_class = 'icon-text';
	}
	?>

	<?php if ( true == get_theme_mod( 'site_login_link', true ) ) : ?>
		<div class="bp-icon-wrap <?php echo esc_attr( $button_class ); ?>">
			<a href="<?php echo esc_url( $login_page_url ); ?>" class="btn-login" title="<?php esc_attr_e( 'Login', 'buddyxpro' ); ?>"> <span class="fa fa-user"></span><?php esc_html_e( 'Log in', 'buddyxpro' ); ?></a>
		</div>
	<?php endif; ?>
	<?php
	if ( get_option( 'users_can_register' ) && true == get_theme_mod( 'site_register_link', true ) ) {
		?>
		<div class="bp-icon-wrap <?php echo esc_attr( $button_class ); ?>">
			<a href="<?php echo $registration_page_url; ?>" class="btn-register" title="<?php esc_attr_e( 'Register', 'buddyxpro' ); ?>"><span class="fas fa-address-book"></span><?php esc_html_e( 'Register', 'buddyxpro' ); ?></a>
		</div>
		<?php
	}
}
