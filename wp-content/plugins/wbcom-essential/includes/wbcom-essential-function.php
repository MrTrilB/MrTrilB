<?php
/**
 * Wbcom essential plugin general functions.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wbcomdesigns.com/plugins
 * @since      1.0.0
 *
 * @package    Wbcom_Essential
 * @subpackage Wbcom_Essential/includes
 */

/**
 * Gets and includes template files.
 *
 * @since 3.0.0
 * @param string|array $template_name Get template name.
 * @param array        $args Additional arguments passed to the template.
 * @param string       $template_path Template path.
 * @param string       $default_path Default path.
 */
function wbcom_essential_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- Please, forgive us.
		extract( $args );
	}

	include wbcom_essential_locate_template( $template_name, $template_path, $default_path );
}

/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 *
 * @since 3.0.0
 *
 * @param string|array $template_name Get template name.
 * @param string       $template_path Template path.
 * @param string       $default_path Default path.
 */
function wbcom_essential_locate_template( $template_name, $template_path, $default_path = '' ) {
	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template.
	if ( ! $template && false !== $default_path ) {
		$default_path = $default_path ? $default_path : WBCOM_ESSENTIAL_PATH . 'templates/';
		if ( file_exists( trailingslashit( $default_path . $template_path ) . $template_name ) ) {
			$template = trailingslashit( $default_path . $template_path ) . $template_name;
		}
	}
	return apply_filters( 'wbcom_essential_locate_template', $template, $template_name, $template_path, $default_path );
}

/**
 * Function checks the theme is active or not.
 *
 * @param string $theme Required Theme Name.
 */
function _is_theme_active( $theme ) {
	$current_theme = wp_get_theme(); // gets the current theme.
	if ( $theme == $current_theme->name || $theme == $current_theme->parent_theme ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Get column class
 *
 * @param string $type Type.
 * @param string $viewport Viewport.
 *
 * @return mixed|string
 */
function _get_column_class( $type, $viewport = '' ) {

	$classes = array(
		'one'   => 'one',
		'two'   => 'two',
		'three' => 'three',
		'four'  => 'four',
	);

	if ( 'tablet' === $viewport ) {
		return 'md-' . $classes[ $type ];
	}

	if ( 'mobile' === $viewport ) {
		return 'sm-' . $classes[ $type ];
	}

	return $classes[ $type ];
}



if ( ! function_exists( 'wbcom_essential_notification_avatar' ) ) {
	/**
	 * BuddyPress notification for avatar.
	 *
	 * @return void
	 */
	function wbcom_essential_notification_avatar() {
		$notification = buddypress()->notifications->query_loop->notification;
		$component    = $notification->component_name;

		switch ( $component ) {
			case 'groups':
				if ( ! empty( $notification->item_id ) ) {
					$item_id = $notification->item_id;
					$object  = 'group';
				}
				break;
			case 'follow':
			case 'friends':
				if ( ! empty( $notification->item_id ) ) {
					$item_id = $notification->item_id;
					$object  = 'user';
				}
				break;
			case has_action( 'bb_notification_avatar_' . $component ):
				do_action( 'bb_notification_avatar_' . $component );
				break;
			default:
				if ( ! empty( $notification->secondary_item_id ) ) {
					$item_id = $notification->secondary_item_id;
					$object  = 'user';
				} else {
					$item_id = $notification->item_id;
					$object  = 'user';
				}
				break;
		}

		if ( isset( $item_id, $object ) ) {

			if ( 'group' === $object ) {
				$group = new BP_Groups_Group( $item_id );
				$link  = bp_get_group_permalink( $group );
			} else {
				$user = new WP_User( $item_id );
				$link = bp_core_get_user_domain( $user->ID, $user->user_nicename, $user->user_login );
			}

			?>
			<a href="<?php echo esc_url( $link ); ?>">
				<?php
				echo bp_core_fetch_avatar(
					array(
						'item_id' => $item_id,
						'object'  => $object,
					)
				);
				?>
				<?php ( isset( $user ) ? wbcom_essential_user_status( $user->ID ) : '' ); ?>
			</a>
			<?php
		}

	}
}



if ( ! function_exists( 'wbcom_essential_is_user_online' ) ) {
	/**
	 * Is the current user online
	 *
	 * @param int $user_id User ID.
	 *
	 * @return bool
	 */
	function wbcom_essential_is_user_online( $user_id ) {

		if ( ! function_exists( 'bp_get_user_last_activity' ) ) {
			return;
		}

		$last_activity = strtotime( bp_get_user_last_activity( $user_id ) );

		if ( empty( $last_activity ) ) {
			return false;
		}

		// the activity timeframe is 5 minutes.
		$activity_timeframe = 5 * MINUTE_IN_SECONDS;
		return ( time() - $last_activity <= $activity_timeframe );
	}
}


if ( ! function_exists( 'wbcom_essential_user_status' ) ) {
	/**
	 * BuddyPress user status
	 *
	 * @param int $user_id User ID.
	 */
	function wbcom_essential_user_status( $user_id ) {
		if ( wbcom_essential_is_user_online( $user_id ) ) {
			echo '<span class="member-status online"></span>';
		}
	}
}



if ( ! function_exists( 'wbcom_essential_theme_elementor_topic_link_attribute_change' ) ) {

	/**
	 * Changed elementor topic link attribute.
	 *
	 * @param  mixed $retval Link.
	 * @param  Array $r R.
	 * @param  mixed $args Arguments.
	 * @return void
	 */
	function wbcom_essential_theme_elementor_topic_link_attribute_change( $retval, $r, $args ) {

		if ( ! function_exists( 'buddypress' ) && ! bp_is_active( 'forums' ) ) {
			return;
		}

		$url    = bbp_get_topic_last_reply_url( $r['id'] ) . '?bbp_reply_to=0#new-post';
		$retval = $r['link_before'] . '<a data-balloon=" ' . esc_html__( 'Reply', 'wbcom-essential' ) . ' " data-balloon-pos="up" href="' . esc_url( $url ) . '" class="bbp-reply-to-link"><i class="wb-icon-reply"></i><span class="bb-forum-reply-text">' . esc_html( $r['reply_text'] ) . '</span></a>' . $r['link_after'];
		return apply_filters( 'bb_theme_topic_link_attribute_change', $retval, $r, $args );
	}
}

if ( ! function_exists( 'wbcom_essential_theme_elementor_reply_link_attribute_change' ) ) {
	/**
	 * Changed elementor reply link attribute.
	 *
	 * @param  mixed $retval Link.
	 * @param  Array $r R.
	 * @param  mixed $args Arguments.
	 * @return void
	 */
	function wbcom_essential_theme_elementor_reply_link_attribute_change( $retval, $r, $args ) {

		if ( ! function_exists( 'buddypress' ) && ! bp_is_active( 'forums' ) ) {
			return;
		}

		// Get the reply to use it's ID and post_parent.
		$reply = bbp_get_reply( bbp_get_reply_id( (int) $r['id'] ) );

		// Bail if no reply or user cannot reply.
		if ( empty( $reply ) || ! bbp_current_user_can_access_create_reply_form() ) {
			return;
		}

		// If single user replies page then no need to open a modal for reply to.
		if ( bbp_is_single_user_replies() ) {
			return $retval;
		}

		// Build the URI and return value.
		$uri = remove_query_arg( array( 'bbp_reply_to' ) );
		$uri = add_query_arg( array( 'bbp_reply_to' => $reply->ID ), bbp_get_topic_permalink( bbp_get_reply_topic_id( $reply->ID ) ) );
		$uri = wp_nonce_url( $uri, 'respond_id_' . $reply->ID );
		$uri = $uri . '#new-post';

		// Only add onclick if replies are threaded.
		if ( bbp_thread_replies() ) {

			// Array of classes to pass to moveForm.
			$move_form = array(
				$r['add_below'] . '-' . $reply->ID,
				$reply->ID,
				$r['respond_id'],
				$reply->post_parent,
			);

			// Build the onclick.
			$onclick = ' onclick="return addReply.moveForm(\'' . implode( "','", $move_form ) . '\');"';

			// No onclick if replies are not threaded.
		} else {
			$onclick = '';
		}

		$modal = 'data-modal-id-inline="new-reply-' . $reply->post_parent . '"';

		// Add $uri to the array, to be passed through the filter.
		$r['uri'] = $uri;
		$retval   = $r['link_before'] . '<a data-balloon=" ' . esc_html__( 'Reply', 'wbcom-essential' ) . ' " data-balloon-pos="up" href="' . esc_url( $r['uri'] ) . '" class="bbp-reply-to-link ' . $reply->ID . ' "><i class="wb-icon-reply"></i><span class="bb-forum-reply-text">' . esc_html( $r['reply_text'] ) . '</span></a>' . $r['link_after'];

		return $retval;
	}
}


/**
 * Get a refreshed cart fragment, including the mini cart HTML.
 *
 * @param Array $fragments WC Cart fragments.
 */
function wbcom_essential_header_cart_fragment( $fragments ) {

	$fragments['span.header-cart-count'] = '<span class="count header-cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'wbcom_essential_header_cart_fragment' );

/**
 * Filters the scripts to enqueue for BuddyPress Nouveau.
 *
 * @param  Array $scripts_args Array of scripts to register.
 */
function wbcom_essential_bp_nouveau_register_scripts( $scripts_args ) {
	if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
		return $scripts_args;
	}

	if ( isset( $scripts_args['bp-nouveau'] ) ) {
		$scripts_args['bp-nouveau']['file'] = WBCOM_ESSENTIAL_URL . 'assets/js/buddypress-nouveau%s.js';
	}
	return $scripts_args;
}
add_filter( 'bp_nouveau_register_scripts', 'wbcom_essential_bp_nouveau_register_scripts', 20 );
