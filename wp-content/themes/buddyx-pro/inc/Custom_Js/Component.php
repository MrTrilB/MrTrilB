<?php
/**
 * BuddyxPro\BuddyxPro\Custom_Js\Component class
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro\Custom_Js;

use BuddyxPro\BuddyxPro\Component_Interface;
use function BuddyxPro\BuddyxPro\buddyxpro;
use WP_Post;
use function add_action;
use function add_filter;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function get_theme_file_path;
use function wp_script_add_data;
use function wp_localize_script;

/**
 * Class for improving custom_js among various core features.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'custom_js';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'action_enqueue_custom_js_script' ] );
	}

	/**
	 * Enqueues a script that improves navigation menu accessibility.
	 */
	public function action_enqueue_custom_js_script() {

		// If the AMP plugin is active, return early.
		if ( buddyxpro()->is_amp() ) {
			return;
		}

		// Enqueue the vendors script.
		wp_enqueue_script(
			'buddyxpro-vendors',
			get_theme_file_uri( '/assets/js/vendors.min.js' ),
			['jquery'],
			buddyxpro()->get_asset_version( get_theme_file_path( '/assets/js/vendors.min.js' ) ),
			true
		);

		// Enqueue the more menu script.
		wp_enqueue_script(
			'buddyxpro-more-menu',
			get_theme_file_uri( '/assets/js/more-menu.min.js' ),
			['jquery'],
			buddyxpro()->get_asset_version( get_theme_file_path( '/assets/js/more-menu.min.js' ) ),
			true
		);

		// Enqueue the jquery cookie script.
        wp_enqueue_script(
            'buddyxpro-jquery-cookie',
            get_theme_file_uri('/assets/js/jquery-cookie.min.js'),
            ['jquery'],
            buddyxpro()->get_asset_version(get_theme_file_path('/assets/js/jquery-cookie.min.js')),
            true
        );

		// Enqueue the slick script.
        wp_enqueue_script(
            'buddyxpro-slick',
            get_theme_file_uri('/assets/js/slick.min.js'),
            ['jquery'],
            buddyxpro()->get_asset_version(get_theme_file_path('/assets/js/slick.min.js')),
            true
        );

		// Enqueue the custom script.
		wp_enqueue_script(
			'buddyxpro-custom',
			get_theme_file_uri( '/assets/js/custom.min.js' ),
			['jquery'],
			buddyxpro()->get_asset_version( get_theme_file_path( '/assets/js/custom.min.js' ) ),
			true
		);
		
	}
}
