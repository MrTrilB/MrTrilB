<?php
/**
 * BuddyxPro functions and definitions
 *
 * This file must be parseable by PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package buddyxpro
 */

define( 'BUDDYXPRO_MINIMUM_WP_VERSION', '4.5' );
define( 'BUDDYXPRO_MINIMUM_PHP_VERSION', '7.0' );

/**
 * Checks if the BP Template pack is Nouveau when BuddyPress is active.
 *
 * @return boolean False if BuddyPress is not active or Nouveau is the active Template Pack.
 *                 True otherwise.
 */
function buddyx_template_pack_check() {
	$retval = false;

	if ( function_exists( 'buddypress' ) ) {
		$retval = 'nouveau' !== bp_get_theme_compat_id();
	}

	return $retval;
}

// Bail if requirements are not met.
if ( version_compare( $GLOBALS['wp_version'], BUDDYXPRO_MINIMUM_WP_VERSION, '<' ) || version_compare( phpversion(), BUDDYXPRO_MINIMUM_PHP_VERSION, '<' ) || buddyx_template_pack_check() ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

// Include WordPress shims.
require get_template_directory() . '/inc/wordpress-shims.php';

//Include Kirki
require get_template_directory() . '/external/require_plugins.php';
require_once get_template_directory() . '/external/include-kirki.php';
require_once get_template_directory() . '/external/kirki-utils.php';

// Setup autoloader (via Composer or custom).
if ( file_exists( get_template_directory() . '/vendor/autoload.php' ) ) {
	require get_template_directory() . '/vendor/autoload.php';
} else {
	/**
	 * Custom autoloader function for theme classes.
	 *
	 * @access private
	 *
	 * @param string $class_name Class name to load.
	 * @return bool True if the class was loaded, false otherwise.
	 */
	function _buddyxpro_autoload( $class_name ) {
		$namespace = 'BuddyxPro\BuddyxPro';

		if ( strpos( $class_name, $namespace . '\\' ) !== 0 ) {
			return false;
		}

		$parts = explode( '\\', substr( $class_name, strlen( $namespace . '\\' ) ) );

		$path = get_template_directory() . '/inc';
		foreach ( $parts as $part ) {
			$path .= '/' . $part;
		}
		$path .= '.php';

		if ( ! file_exists( $path ) ) {
			return false;
		}

		require_once $path;

		return true;
	}
	spl_autoload_register( '_buddyxpro_autoload' );
}

// Load the `buddyxpro()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Initialize the theme.
call_user_func( 'BuddyxPro\BuddyxPro\buddyxpro' );

// Require plugin.php to use is_plugin_active() below
if ( !function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

// Load theme breadcrubms function.
require get_template_directory() . '/inc/class-buddyx-breadcrumbs.php';

// Load theme extra function.
require get_template_directory() . '/inc/extra.php';

// Load theme extra function.
require get_template_directory() . '/inc/buddyx-custom-style.php';

// Load widgets.
require get_template_directory() . '/inc/widgets/bp-profile-completion-widget.php';
require get_template_directory() . '/inc/widgets/ld-featured-course-widget.php';
require get_template_directory() . '/inc/widgets/login-widget.php';

// Load Vendors Profile Widget.
if ( class_exists( 'WooCommerce' ) && class_exists( 'WC_Widget' ) ) {
	require get_template_directory() . '/inc/widgets/class-vendor-profile-widget.php';
}

// Load LearnPress functions.
if ( class_exists( 'LearnPress' ) ) {
	require get_template_directory() . '/inc/compatibility/learnpress/learnpress-functions.php';
}

// Load LearnDash functions.
if ( class_exists( 'SFWD_LMS' ) ) {
	require get_template_directory() . '/inc/compatibility/learndash/learndash-functions.php';
}

// Load WooCommerce functions.
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/compatibility/woocommerce/woocommerce-functions.php';
}

// Load Dokan functions.
if ( class_exists( 'WeDevs_Dokan' ) ) {
	require get_template_directory() . '/inc/compatibility/dokan/dokan-functions.php';
}

/**
* The the file contain plugin core functions available on both the front-end and admin.
*/
if ( class_exists( 'WooCommerce' ) && class_exists( 'WC_Vendors' ) ) {
	require_once get_template_directory() . '/inc/compatibility/wc-vendors/buddyx_wc_vendors_core_functions.php';
}

// bp_nouveau_appearance default option
$optionKey = "buddyx_theme_is_activated";
if ( !get_option( $optionKey ) ) {

	$bp_nouveau_appearance = array(
		'members_layout'		 => 3,
		'members_friends_layout' => 3,
		'groups_layout'			 => 3,
		'members_group_layout'	 => 3,
		'group_front_page'		 => 0,
		'group_front_boxes'		 => 0,
		'user_front_page'		 => 0,
		'user_nav_display'		 => 1,
		'group_nav_display'		 => 1,
	);
	update_option( 'bp_nouveau_appearance', $bp_nouveau_appearance );
	update_option( $optionKey, 1 );
}

// force add theme support for BP nouveau
if ( !function_exists( 'buddyx_buddypress_nouveau_support' ) ) {

	function buddyx_buddypress_nouveau_support() {
		add_theme_support('buddypress-use-nouveau');
	}

	add_action( 'after_setup_theme', 'buddyx_buddypress_nouveau_support' );
}

/**
 * Added function for theme updater
 */
function buddyx_theme_updater() {
	require( get_template_directory() . '/updater/theme-updater.php' );
}
add_action( 'after_setup_theme', 'buddyx_theme_updater' );

/**
* Load fontawesome on admin side.
*/
if ( !function_exists( 'buddyx_fontawesome_dashboard' ) ) {

	function buddyx_fontawesome_dashboard() {
		wp_enqueue_style('fontawesome', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css', '', '5.8.1', 'all');
	}

	add_action( 'admin_init', 'buddyx_fontawesome_dashboard' );
}

/*
 * Call amp templates for archive single post, post category/tag, search and author page when amp request get
 *
 */
add_filter( 'template_include', 'buddyx_theme_amp_templatre_include', '9999992233720368547758099' );
function buddyx_theme_amp_templatre_include( $template_file) {
	
	if ( function_exists( 'amp_is_request' ) && amp_is_request() || ( function_exists('amp_is_legacy') && amp_is_legacy() )) {		
		if ( is_single() && get_post_type() == 'post') {
			$template_file = get_template_directory() ."/amp/single.php";
		}
		
		if ( ( is_home() && get_post_type() == 'post' )  || is_category() || is_tag() || is_author()  ) {
			$template_file = get_template_directory() ."/amp/archive.php";
		}
		if ( is_search() ) {
			$template_file = get_template_directory() ."/amp/search.php";
		}
		
		if ( is_archive() && get_post_type() == 'post') {
			$template_file = get_template_directory() ."/amp/archive.php";
		}	
	}
	return $template_file;
}

/*
 * Return $bp_is_directory false when buddypress register page view.
 *
 */
add_filter( 'bp_nouveau_theme_compat_page_templates_directory_only', 'buddyx_page_templates_directory_only' );
function buddyx_page_templates_directory_only( $bp_is_directory ) {
	global $wp_query;
	$bp_pages = get_option( 'bp-pages' );

	/* Register page id and BuddyBoss saved register page equal then bp_is_directory set false */
	if ( isset( $bp_pages['register'] ) && $bp_pages['register'] != '' && get_the_ID() != 0 && $bp_pages['register'] == get_the_ID() ) {
		$bp_is_directory = false;
	}

	/* Register page id and BuddyPress saved register page equal then bp_is_directory set false */
	if ( isset( $bp_pages['register'] ) && $bp_pages['register'] != '' && $wp_query->queried_object_id != 0 && $bp_pages['register'] == $wp_query->queried_object_id ) {
		$bp_is_directory = false;
	}

	/* Activate page id and BuddyBoss saved activate page equal then bp_is_directory set false */
	if ( isset( $bp_pages['activate'] ) && $bp_pages['activate'] != '' && get_the_ID() != 0 && $bp_pages['activate'] == get_the_ID() ) {
		$bp_is_directory = false;
	}

	/* Activate page id and BuddyPress saved activate page equal then bp_is_directory set false */
	if ( isset( $bp_pages['activate'] ) && $bp_pages['activate'] != '' && $wp_query->queried_object_id != 0 && $bp_pages['activate'] == $wp_query->queried_object_id ) {
		$bp_is_directory = false;
	}

	return $bp_is_directory;
}