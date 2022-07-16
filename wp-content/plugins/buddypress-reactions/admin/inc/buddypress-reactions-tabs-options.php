<?php
/**
 * This template file is used for fetching desired options page file at admin settings end.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Quotes
 * @subpackage Buddypress_Quotes/admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( isset( $_GET['tab'] ) ) {
	$bpsts_tab = sanitize_text_field( $_GET['tab'] );
} else {
	$bpsts_tab = 'welcome';
}


switch ( $bpsts_tab ) {
	case 'welcome':
		include 'buddypress-reaction-welcome-page.php';
		break;
	case 'bpractions-emoji':
		include 'buddypress-reaction-emoji-page.php';
		break;
	case 'shortcode-generator':
		include 'buddypress-reaction-create-shortcode.php';
		break;
	case 'my-shortcodes':
		include 'buddypress-reaction-my-shortcodes.php';
		break;
	case 'bp-integration':
		include 'buddypress-reaction-bp-integration.php';
		break;
	default:
		include 'buddypress-reaction-welcome-page.php';
		break;
}

