<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wbcomdesigns.com/
 * @since             1.0.0
 * @package           Buddypress_Reactions
 *
 * @wordpress-plugin
 * Plugin Name:       Wbcom Designs - BuddyPress Reactions
 * Plugin URI:        https://wbcomdesigns.com/download/buddypress-reactions
 * Description:       This plugin helps you to have Facebook-like emotions for reacting on any activity update .
 * Version:           1.2.0
 * Author:            Wbcom Designs
 * Author URI:        https://wbcomdesigns.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       buddypress-reactions
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BUDDYPRESS_REACTIONS_VERSION', '1.2.0' );

define( 'BUDDYPRESS_REACTIONS_DIR', dirname( __FILE__ ) );
define( 'BUDDYPRESS_REACTIONS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BUDDYPRESS_REACTIONS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BUDDYPRESS_REACTIONS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined( 'BUDDYPRESS_REACTIONS_PLUGIN_FILE' ) ) {
	define( 'BUDDYPRESS_REACTIONS_PLUGIN_FILE', __FILE__ );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-buddypress-reactions-activator.php
 */
function activate_buddypress_reactions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buddypress-reactions-activator.php';
	Buddypress_Reactions_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-buddypress-reactions-deactivator.php
 */
function deactivate_buddypress_reactions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buddypress-reactions-deactivator.php';
	Buddypress_Reactions_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_buddypress_reactions' );
register_deactivation_hook( __FILE__, 'deactivate_buddypress_reactions' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-buddypress-reactions.php';
require plugin_dir_path( __FILE__ ) . 'buddypress-reactions-widget.php';
require plugin_dir_path( __FILE__ ) . 'buddypress-reactions-stats-widget.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_buddypress_reactions() {

	$plugin = new Buddypress_Reactions();
	$plugin->run();

}
run_buddypress_reactions();




/**
 *  Check if buddypress activate.
 */
function buddypress_reactions_requires_buddypress() {

	if ( ! class_exists( 'Buddypress' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'buddypress_reactions_required_plugin_admin_notice' );
		unset( $_GET['activate'] );
	}
}
add_action( 'admin_init', 'buddypress_reactions_requires_buddypress' );

/**
 * Throw an Alert to tell the Admin why it didn't activate.
 *
 * @author wbcomdesigns
 * @since  1.0.0
 */

function buddypress_reactions_required_plugin_admin_notice() {

	$bpquotes_plugin = esc_html__( ' BuddyPress Reactions', 'buddypress-reactions' );
	$bp_plugin       = esc_html__( 'BuddyPress', 'buddypress-reactions' );
	echo '<div class="error"><p>';
	echo sprintf( esc_html__( '%1$s is ineffective now as it requires %2$s to be installed and active.', 'buddypress-reactions' ), '<strong>' . esc_html( $bpquotes_plugin ) . '</strong>', '<strong>' . esc_html( $bp_plugin ) . '</strong>' );
	echo '</p></div>';
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}

/**
 *  Add notice with youzify plugin.
 *
 * @author wbcomdesigns
 * @since  1.1.0
 */
function buddypress_reactions_youzify() {
	if ( class_exists( 'Youzify' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'buddypress_reactions_youzify_plugin_admin_notice' );
		unset( $_GET['activate'] );
	}
}
add_action( 'admin_init', 'buddypress_reactions_youzify' );

/**
 * Throw an Alert to tell the Admin why it didn't activate.
 *
 * @author wbcomdesigns
 * @since  1.1.0
 */
function buddypress_reactions_youzify_plugin_admin_notice() {
	$bpreaction_plugin = esc_html__( ' BuddyPress Reactions', 'buddypress-reactions' );
	$youzify_plugin    = esc_html__( 'Youzify', 'buddypress-reactions' );
	echo '<div class="error"><p>';
	echo sprintf( esc_html__( '%1$s plugin can not be use with %2$s plugin.', 'buddypress-reactions' ), '<strong>' . esc_html( $bpreaction_plugin ) . '</strong>', '<strong>' . esc_html( $youzify_plugin ) . '</strong>' );
	echo '</p></div>';
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}

/**
 * redirect to plugin settings page after activated
 */
add_action( 'activated_plugin', 'buddypress_reactions_activation_redirect_settings' );
function buddypress_reactions_activation_redirect_settings( $plugin ) {
	if ( class_exists( 'Youzify' ) ) {
		return;
	}
	if( $plugin == plugin_basename( __FILE__ ) && class_exists( 'Buddypress' ) &&  !isset($_GET['page']) ) {
		wp_redirect( admin_url( 'admin.php?page=buddypress-reactions' ) ) ;
		exit;
	}
}

require plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://demos.wbcomdesigns.com/exporter/free-plugins/buddypress-reactions.json',
	__FILE__, // Full path to the main plugin file or functions.php.
	'buddypress-reactions'
);
