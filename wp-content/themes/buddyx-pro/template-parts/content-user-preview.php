<?php
/**
 * Template part for displaying a buddypress user preview content
 *
 * @package buddyxpro
 */

extract( $args );
$url                   = $cover_url = $avatar_url = $itme_name = $item_slug = '';
$display_action_button = false;
$action_button_text    = '';

if ( 'groups' === $component ) {
	$group = groups_get_group( array( 'group_id' => $item_id ) );
	$url   = bp_get_group_permalink( $group );

	if ( $type == 'new_group_cover_photo' ) {
		$cover_url = bp_activity_get_meta( $activity_id, 'group_cover_image', true );
	} else {
		$cover_url             = bp_get_group_cover_url( $group );
		$display_action_button = true;
		$action_button_text    = esc_html__( 'Visit Group', 'buddyxpro' );
	}
	$itme_name = $group->name;
	$itme_slug = $group->slug;

	if ( empty( $cover_url ) ) {
		$cover_url = get_template_directory_uri() . '/assets/images/placeholder.svg';
	}

	$avatar_url = bp_activity_get_meta( $activity_id, 'group_avatar_image', true );
	if ( $type == 'new_group_avatar' && $avatar_url != '' ) {
		$avatar_url = '<img loading="lazy" src="' .  esc_url( $avatar_url, array( 'data', 'http', 'https' ) ) . '" class="avatar group-' . esc_attr($item_id) . '-avatar avatar-150 photo" width="150" height="150">';
	} else {
		$avatar_url = bp_core_fetch_avatar(
			array(
				'item_id'    => $item_id,
				'type'       => 'full',
				'avatar_dir' => 'group-avatars',
				'object'     => 'group',
				'width'      => 150,
				'height'     => 150,
			)
		);
	}
} elseif ( 'friends' === $component ) {
	$url                   = bp_core_get_user_domain( $secondary_item_id );
	$itme_name             = bp_core_get_user_displayname( $secondary_item_id );
	$itme_slug             = bp_activity_get_user_mentionname( $secondary_item_id );
	$display_action_button = true;
	$action_button_text    = esc_html__( 'View Profile', 'buddyxpro' );

	$cover_url = bp_attachments_get_attachment(
		'url',
		array(
			'object_dir' => 'members',
			'item_id'    => $secondary_item_id,
		)
	);

	if ( empty( $cover_url ) ) {
		$cover_url = get_template_directory_uri() . '/assets/images/placeholder.svg';
	}

	$avatar_url = bp_core_fetch_avatar(
		array(
			'item_id' => $secondary_item_id,
			'type'    => 'full',
			'width'   => 150,
			'height'  => 150,
			'class'   => 'avatar',
			'id'      => false,
			'alt'     => sprintf( __( 'Profile picture of %s', 'buddyxpro' ), $itme_name ),
		)
	);
} elseif ( 'members' === $component ) {
	if ( $type == 'new_avatar' ) {
		$secondary_item_id     = $user_id;
		$display_action_button = true;
		$action_button_text    = esc_html__( 'View Profile', 'buddyxpro' );
	}

	$url       = bp_core_get_user_domain( $secondary_item_id );
	$itme_name = bp_core_get_user_displayname( $secondary_item_id );
	$itme_slug = bp_activity_get_user_mentionname( $secondary_item_id );

	if ( $type == 'new_cover_photo' ) {
		$cover_url = bp_activity_get_meta( $activity_id, 'cover_image', true );
	} else {
		$cover_url = bp_attachments_get_attachment(
			'url',
			array(
				'object_dir' => 'members',
				'item_id'    => $secondary_item_id,
			)
		);
	}

	if ( empty( $cover_url ) ) {
		$cover_url = get_template_directory_uri() . '/assets/images/placeholder.svg';
	}

	$avatar_url = bp_activity_get_meta( $activity_id, 'member_avatar_image', true );
	if ( $type == 'new_avatar' && $avatar_url != '' ) {
		$avatar_url = '<img loading="lazy" src="' . esc_url( $avatar_url, array( 'data', 'http', 'https' ) )  . '" class="avatar user-' . esc_attr($item_id) . '-avatar avatar-150 photo" width="150" height="150">';
	} elseif ( $type == 'new_avatar' &&  !$avatar_url ) {
		return;
	} else {
		$avatar_url = bp_core_fetch_avatar(
			array(
				'item_id' => $secondary_item_id,
				'type'    => 'full',
				'width'   => 150,
				'height'  => 150,
				'class'   => 'avatar',
				'id'      => false,
				'alt'     => sprintf( __( 'Profile picture of %s', 'buddyxpro' ), $itme_name ),
			)
		);
	}
}
?>
<div class="buddyx-user-preview">
	<a href="<?php echo esc_url( $url ); ?>">
		<div class="buddyx-user-preview-cover">
			<img src="<?php echo esc_url( $cover_url, array( 'data', 'http', 'https' ) ); ?>" alt="<?php echo esc_attr( 'cover-image' ); ?>"/>
		</div>
	</a>
	<?php if ( $type != 'new_cover_photo' && $type != 'new_group_cover_photo' ) : ?>
		<div class="buddyx-user-short-description">
			<a href="<?php echo esc_url( $url ); ?>" class="item-avatar-group buddyx-user-avatar buddyx-user-short-description-avatar">
				<div class="buddyx-user-avatar-content">
					<?php echo $avatar_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</a>
			<p class="buddyx-user-short-description-title">
				<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $itme_name ); ?></a>
			</p>
			<?php if ( $type == 'new_avatar') : ?>
			<p>
				<a href="<?php echo esc_url( $url ); ?>">@<?php echo esc_html( $itme_slug ); ?></a>
			</p>
			<?php endif;
			if ( $display_action_button ) {
				echo $action_button = sprintf(
					'<div class="bp-profile-button">
						<a href="%1$s" class="button large primary button-primary" role="button">%2$s</a>
					</div>',
					esc_url( $url ),
					$action_button_text
				);
			}
			?>
		</div>
		<?php
	endif;

	if ( 'groups' === $component && $type != 'new_group_cover_photo' && $type != 'new_group_avatar' ) {
		?>
		<div class="buddyx-user-preview-footer">
			<div class="buddyx-user-stats">
				<div class="buddyx-user-stat">
					<p class="buddyx-user-stat-title"><?php echo groups_get_total_member_count( $item_id ); ?></p>
					<p class="buddyx-user-stat-text"><?php esc_html_e( 'Members', 'buddyxpro' ); ?></p>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
