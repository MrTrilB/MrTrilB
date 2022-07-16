<?php

/*-----------------------------------------------------------------------------------*/
/* Define Constants */
/*-----------------------------------------------------------------------------------*/

    define('GOODGAME_URL', get_template_directory_uri() . '/');
	define('GOODGAME_THEME_URL', get_template_directory_uri() . '/theme/');
    define('GOODGAME_ADMIN_ASSET_URL', get_template_directory_uri() . '/core/panel/assets/');

	define('GOODGAME_IMG_URL', GOODGAME_THEME_URL . 'assets/images/');
	define('GOODGAME_JS_URL', GOODGAME_THEME_URL . 'assets/js/');
	define('GOODGAME_CSS_URL', GOODGAME_THEME_URL . 'assets/css/');
    define('GOODGAME_LESS_URL', GOODGAME_THEME_URL . 'assets/less/');

    $upload_dir = wp_upload_dir();
    define('GOODGAME_UPLOAD_URL',  $upload_dir['baseurl'] . '/goodgame/');
    define('GOODGAME_UPLOAD_PATH',  $upload_dir['basedir'] . '/goodgame/');

    define('GOODGAME_IS_CHILD', is_child_theme());

	define('GOODGAME_TF_ITEM_ID', '18560441');

    if(GOODGAME_IS_CHILD)
    {
        define('GOODGAME_CHILD_PATH', get_stylesheet_directory());
        define('GOODGAME_CHILD_THEME_PATH', get_stylesheet_directory() . '/theme/');
        define('GOODGAME_CHILD_TEMPLATE_PATH', GOODGAME_CHILD_THEME_PATH . 'templates/');
    }

/*-----------------------------------------------------------------------------------*/
/* Load the required Framework Files */
/*-----------------------------------------------------------------------------------*/

	include_once( get_template_directory() . '/core/' . 'shared-functions.php' );
	include_once( get_template_directory() . '/core/panel/' . 'admin-functions.php' );
	include_once( get_template_directory() . '/core/panel/' . 'admin-templates.php' );
    include_once( get_template_directory() . '/core/' . 'template-functions.php' );
	include_once( get_template_directory() . '/core/lib/' . 'settings.class.php' );
    include_once( get_template_directory() . '/core/lib/' . 'class-tgm-plugin-activation.php' );
    include_once( get_template_directory() . '/core/lib/' . 'wpBootstrapNavwalker.class.php' );
    include_once( get_template_directory() . '/core/lib/' . 'wp-less.class.php' );
	include_once( get_template_directory() . '/theme/includes/' . 'google-fonts.php');
	include_once( get_template_directory() . '/theme/includes/' . 'settings.php' );
    include_once( get_template_directory() . '/theme/plugins/' . 'versions.php' );
    include_once( get_template_directory() . '/theme/' . 'migrate.php');	//theme version change migrate


/*-----------------------------------------------------------------------------------*/
/* Load settings */
/*-----------------------------------------------------------------------------------*/

	$_SETTINGS = new GoodGame_Settings();

/*-----------------------------------------------------------------------------------*/
/* Constants */
/*-----------------------------------------------------------------------------------*/

	define('GOODGAME_THEME_DOMAIN', goodgame_gs('theme_slug'));

/*-----------------------------------------------------------------------------------*/
/* Add actions */
/*-----------------------------------------------------------------------------------*/

if( is_admin())
{
	add_action('admin_menu', 'goodgame_load_admin_menus');
    add_action('admin_enqueue_scripts', 'goodgame_load_admin_styles');
    add_action('admin_enqueue_scripts', 'goodgame_load_admin_scripts');
	add_action('wp_ajax_goodgame_save_sidebar', 'goodgame_save_sidebar');
	add_action('wp_ajax_goodgame_save_settings', 'goodgame_save_settings');
    add_action('wp_ajax_goodgame_load_style_preset', 'goodgame_load_style_preset');
    add_action('wp_ajax_goodgame_save_ads', 'goodgame_save_ads');
    add_action('wp_ajax_goodgame_save_ad_locations', 'goodgame_save_ad_locations');
    add_action('wp_ajax_goodgame_import_settings', 'goodgame_import_settings');
    add_action('wp_ajax_goodgame_reset_settings', 'goodgame_reset_settings');
    add_action('wp_ajax_goodgame_upload_image', 'goodgame_upload_image');
	add_action('wp_ajax_goodgame_remove_newsletter_notification', 'goodgame_remove_newsletter_notification');
	add_action('wp_ajax_goodgame_extra_google_fonts', 'goodgame_extra_google_fonts');
    add_action('wp_loaded', 'goodgame_version_migrate');
//  add_action('admin_notices', 'goodgame_handle_admin_actions', 5);
	add_action('wp_ajax_goodgame_demo_import_launcher', 'goodgame_demo_import_launcher');
    add_action('wp_ajax_goodgame_import_page', 'goodgame_import_page');
//  add_action('admin_notices', 'goodgame_page_install_notification');
//	add_action('admin_notices', 'goodgame_thumbnail_regenerate_notification');
    add_action('after_switch_theme', 'goodgame_log_theme_version');
	//add_action('after_switch_theme', 'goodgame_redirect_to_status', 999);
}

?>
