<?php
/**
 * BuddyxPro\BuddyxPro\Kirki\Component class
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro\Kirki;

use BuddyxPro\BuddyxPro\Component_Interface;
use Kirki;
use function add_filter;


/**
 * Class Component
 *
 * @package BuddyxPro\BuddyxPro\Kirki
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'kirki';
		// TODO: Implement get_slug() method.
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'kirki/config', array( $this, 'configure_kirki' ) );		
		// TODO: Implement initialize() method.
	}

	/**
	 *  Kirki Configuration
	 */
	public function configure_kirki( $config ) {
		if ( ! class_exists( 'Kirki' ) ) {
			Kirki::add_config(
				'buddyx_kirki',
				array(
					'capability'    => 'edit_theme_options',
					'option_type'   => 'theme_mod',
				)
			);
		}
	}
}