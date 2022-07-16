<?php
/**
 * Template part for displaying a profile screen content
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

?>

<?php
$user_ID = get_current_user_id();

if ( ! $user_ID ) {
	return;
}

$use_buddypress = buddyx_BuddyPress();

$author_name = wp_get_current_user()->display_name;

if ( $use_buddypress ) {
	$author_url         = bp_core_get_user_domain( $user_ID );
	$author_cover_image = bp_attachments_get_attachment(
		'url',
		array(
			'object_dir' => 'members',
			'item_id'    => $user_ID,
		)
	);
} else {
	$author_url         = get_author_posts_url( $user_ID );
	$author_cover_image = '';
}

$author_cover_image = $author_cover_image ? "background-image: url({$author_cover_image})" : '';
?>

<div class="buddyx-module buddyx-login-form">
	<div class="user-welcomeback">
		<div class="featured-background" style="<?php echo esc_attr( $author_cover_image ); ?>"></div>
		<div class="user-active">
			<a href="<?php echo esc_url( $author_url ); ?>" class="author-thumb">
			<?php echo get_avatar( $user_ID, 90 ); ?>
			</a>
			<div class="author-content">			
				<h3><?php esc_html_e( 'Welcome Back', 'buddyxpro' ); ?></h3>
				<a href="<?php echo esc_url( $author_url ); ?>" class="author-name"><?php echo $author_name; ?></a>
			</div>
		</div>
			<?php if ( $use_buddypress ) { ?>

					<div class="links">
							<?php if ( bp_is_active( 'activity' ) ) { ?>
									<a href="<?php echo esc_url( bp_loggedin_user_domain() . bp_get_activity_slug() ); ?>" class="link-item">
											<i class="link-item-icon fas fa-comments"></i>
											<div class="title"><?php esc_html_e( 'Activity', 'buddyxpro' ); ?></div>
											<div class="sup-title"><?php esc_html_e( 'Review your activity!', 'buddyxpro' ); ?></div>
									</a>
							<?php } ?>

							<?php if ( bp_is_active( 'settings' ) ) { ?>
									<a href="<?php echo esc_url( bp_loggedin_user_domain() . bp_get_settings_slug() ); ?>" class="link-item">
											<i class="link-item-icon fas fa-user-cog"></i>
											<div class="title"><?php esc_html_e( 'Settings', 'buddyxpro' ); ?></div>
											<div class="sup-title"><?php esc_html_e( 'Manage your preferences!', 'buddyxpro' ); ?></div>
									</a>
							<?php } ?>
					</div>
			<?php } ?>
		<div class="buddyx-block-content">
			<a href="<?php echo esc_url( $author_url ); ?>" class="btn button full-width registration-login-submit">
			<?php esc_html_e( 'Visit Profile', 'buddyxpro' ); ?>
			</a>
		</div>
	</div>
</div>
