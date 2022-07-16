<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Sample Theme
 */

// Includes the files needed for the theme updater
if ( !class_exists( 'EDD_Buddyx_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}
$theme	 = wp_get_theme( get_template() );
$theme_version = $theme->get( 'Version' );
// Loads the updater classes
$updater = new EDD_Buddyx_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => 'https://wbcomdesigns.com', // Site where EDD is hosted
		'item_name'      => 'BuddyX Pro Theme', // Name of theme
		'theme_slug'     => 'buddyx-pro-theme', // Theme slug
		'version'        => $theme_version, // The current version of this theme
		'author'         => 'Wbcom Designs', // The author of this theme
		'download_id'    => '', // Optional, used for generating a license renewal link
		'renew_url'      => '', // Optional, allows for a custom license renewal link
		'beta'           => false, // Optional, set to true to opt into beta versions
	),

	// Strings
	$strings = array(
		'theme-license'             => __( 'BuddyX Pro Theme License', 'buddyxpro' ),
		'enter-key'                 => __( 'Enter your theme license key.', 'buddyxpro' ),
		'license-key'               => __( 'License Key', 'buddyxpro' ),
		'license-action'            => __( 'License Action', 'buddyxpro' ),
		'deactivate-license'        => __( 'Deactivate License', 'buddyxpro' ),
		'activate-license'          => __( 'Activate License', 'buddyxpro' ),
		'status-unknown'            => __( 'License status is unknown.', 'buddyxpro' ),
		'renew'                     => __( 'Renew?', 'buddyxpro' ),
		'unlimited'                 => __( 'unlimited', 'buddyxpro' ),
		'license-key-is-active'     => __( 'License key is active.', 'buddyxpro' ),
		'expires%s'                 => __( 'Expires %s.', 'buddyxpro' ),
		'expires-never'             => __( 'Lifetime License.', 'buddyxpro' ),
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'buddyxpro' ),
		'license-key-expired-%s'    => __( 'License key expired %s.', 'buddyxpro' ),
		'license-key-expired'       => __( 'License key has expired.', 'buddyxpro' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'buddyxpro' ),
		'license-is-inactive'       => __( 'License is inactive.', 'buddyxpro' ),
		'license-key-is-disabled'   => __( 'License key is disabled.', 'buddyxpro' ),
		'site-is-inactive'          => __( 'Site is inactive.', 'buddyxpro' ),
		'license-status-unknown'    => __( 'License status is unknown.', 'buddyxpro' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'buddyxpro' ),
		'update-available'          => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'buddyxpro' ),
	)

);
