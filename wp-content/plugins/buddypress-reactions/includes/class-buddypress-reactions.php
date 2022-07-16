<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Reactions
 * @subpackage Buddypress_Reactions/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Buddypress_Reactions
 * @subpackage Buddypress_Reactions/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Reactions {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Buddypress_Reactions_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BUDDYPRESS_REACTIONS_VERSION' ) ) {
			$this->version = BUDDYPRESS_REACTIONS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'buddypress-reactions';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Buddypress_Reactions_Loader. Orchestrates the hooks of the plugin.
	 * - Buddypress_Reactions_i18n. Defines internationalization functionality.
	 * - Buddypress_Reactions_Admin. Defines all hooks for the admin area.
	 * - Buddypress_Reactions_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-buddypress-reactions-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-buddypress-reactions-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-buddypress-reactions-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-buddypress-reactions-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/buddypress-reactions-function.php';
		
		/* Enqueue wbcom plugin folder file. */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wbcom/wbcom-admin-settings.php';

		/* Enqueue wbcom plugin folder file. */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wbcom/wbcom-paid-plugin-settings.php';

		$this->loader = new Buddypress_Reactions_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Buddypress_Reactions_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Buddypress_Reactions_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Buddypress_Reactions_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'buddypress_reactions_add_admin_menu' );
		$this->loader->add_action( 'init', $plugin_admin, 'buddypress_reactions_admin_init' );
		
		
		$this->loader->add_action( 'wp_ajax_bpr_delete_shortcode', $plugin_admin, 'buddypress_reactions_delete_shortcode' );
		$this->loader->add_action( 'wp_ajax_bpr_update_emoji_name', $plugin_admin, 'buddypress_reactions_update_emoji_name' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		
		$theme_support = apply_filters( 'buddyPress_reactions_theme_suuport', ['reign-theme', 'buddyx-pro'] );
		$theme_name =  wp_get_theme();
		
		
		$plugin_public = new Buddypress_Reactions_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_action( 'bp_activity_entry_meta', $plugin_public, 'buddypress_reactions_activity_entry_meta' );
		$this->loader->add_action( 'bp_activity_comment_options', $plugin_public, 'buddypress_reactions_activity_comment_options' );
		
		
		$this->loader->add_action( 'wp_ajax_bpr_create_user_react_emoji_ajax', $plugin_public, 'bpr_create_user_react_emoji_ajax' );
		$this->loader->add_action( 'wp_ajax_bpr_remove_user_react_emoji_ajax', $plugin_public, 'bpr_remove_user_react_emoji_ajax' );
		$this->loader->add_action( 'wp_ajax_bpr_display_user_react_emoji_ajax', $plugin_public, 'bpr_display_user_react_emoji_ajax' );
		//$this->loader->add_action( 'wp_ajax_nopriv_bpr_display_user_react_emoji_ajax', $plugin_public, 'bpr_display_user_react_emoji_ajax' );
		
		
		
		if( in_array( $theme_name->template, $theme_support) ) {
			$this->loader->add_action( 'bp_activity_before_post_footer_content', $plugin_public, 'bpr_bp_activity_reactions_meta',1 );
		} else {
			$this->loader->add_filter( 'the_content', $plugin_public, 'bpr_post_reactions_the_content',9999 );
			$this->loader->add_action( 'bp_before_activity_entry_comments', $plugin_public, 'bpr_bp_activity_reactions_meta',1 );
			
			
		}
		
		$this->loader->add_shortcode( 'bp_reactions', $plugin_public, 'handle_reactions' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Buddypress_Reactions_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
