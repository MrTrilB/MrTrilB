<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Reactions
 * @subpackage Buddypress_Reactions/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Buddypress_Reactions
 * @subpackage Buddypress_Reactions/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Reactions_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Reactions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Reactions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'mscrollbar', plugin_dir_url( __FILE__ ) . 'css/mscrollbar.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/buddypress-reactions-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Reactions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Reactions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'mscrollbar', plugin_dir_url( __FILE__ ) . 'js/mscrollbar.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'lottie', plugin_dir_url( __FILE__ ) . 'js/lottie.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-reactions-admin.js', array( 'jquery','jquery-ui-sortable' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name,
			'bpreactions',
			array(
				'ajaxUrl'     					=> admin_url( 'admin-ajax.php' ),
				'emojis_path' 					=> BUDDYPRESS_REACTIONS_PLUGIN_URL . 'emojis/',
				'version'     					=> BUDDYPRESS_REACTIONS_VERSION,
				'max_emojis'     				=> apply_filters( 'buddypress_reactions_max_emojis', 6),
				'bp_reactions_shortcode_delete'	=> __( 'Are you sure you want to permanently delete this Shortcode?', 'buddypress-reactions' ),
			)
		);

	}

	/**
	 * Register admin menu for plugin.
	 *
	 * @since    1.0.0
	 */
	public function buddypress_reactions_add_admin_menu() {
		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {

			add_menu_page( esc_html__( 'WB Plugins', 'buddypress-reactions' ), esc_html__( 'WB Plugins', 'buddypress-reactions' ), 'manage_options', 'wbcomplugins', array( $this, 'buddypress_reactions_settings_page' ), 'dashicons-lightbulb', 59 );

			add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'buddypress-reactions' ), esc_html__( 'General', 'buddypress-reactions' ), 'manage_options', 'wbcomplugins' );
		}
		add_submenu_page( 'wbcomplugins', esc_html__( 'Buddypress Reactions', 'buddypress-reactions' ), esc_html__( 'Buddypress Reactions', 'buddypress-reactions' ), 'manage_options', 'buddypress-reactions', array( $this, 'buddypress_reactions_settings_page' ) );
	}


	/**
	 * BuddyPress Quote Admin Setting.
	 */
	public function buddypress_reactions_settings_page() {

		$current = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'welcome';
		
		if ( $current == 'shortcode-generator' ) {
			$current = 'my-shortcodes';
		}

		$bp_reaction_tabs = array(
			'welcome'             => __( 'Welcome', 'buddypress-reactions' ),
			'bpractions-emoji'    => __( 'Emojis', 'buddypress-reactions' ),
			//'shortcode-generator' => __( 'Shortcode Generator', 'buddypress-reactions' ),
			'my-shortcodes'       => __( 'My Shortcodes', 'buddypress-reactions' ),
			'bp-integration'      => __( 'BuddyPress Integration', 'buddypress-reactions' ),
		);
		?>

		<div class="wrap">
			<hr class="wp-header-end">
			<div class="wbcom-wrap">
				<div class="blpro-header">
					<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
					<h1 class="wbcom-plugin-heading">
						<?php esc_html_e( 'Buddypress Reactions', 'buddypress-reactions' ); ?>
					</h1>
				</div>
				<div class="wbcom-admin-settings-page">
					<div class="wbcom-tabs-section">
						<div class="nav-tab-wrapper">
							<div class="wb-responsive-menu">
								<span><?php echo esc_html( 'Menu' ); ?></span>
								<input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn">
								<label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label>
							</div>
							<ul>
							<?php
							foreach ( $bp_reaction_tabs as $bp_reaction_tab => $bp_reaction_name ) :
								$class = ( $bp_reaction_tab === $current ) ? 'nav-tab-active' : '';
								?>
								<li>
									<a class="nav-tab <?php echo esc_attr( $class ); ?>" href="admin.php?page=buddypress-reactions&tab=<?php echo $bp_reaction_tab; ?>"><?php echo $bp_reaction_name; ?></a>
								</li>
							<?php endforeach; ?>
							</ul>
						</div>
					</div>
					<?php include 'inc/buddypress-reactions-tabs-options.php'; ?>

				</div>
			</div>
		</div>
		<?php
	}

	public function buddypress_reactions_admin_init() {
		global $wpdb;

		if ( isset( $_POST['bp_reactions'] ) && ! defined( 'DOING_AJAX' )  ) {

			$_wp_http_referer =  $_POST['_wp_http_referer'] ;

			$data = [
				'options'      => json_encode( $_POST['bp_reactions'] ),
				'name'         => $_POST['bp_reactions']['shortcode_name'],
				'post_type'    => $_POST['bp_reactions']['post_type'],
				'front_render' => (isset($_POST['bp_reactions']['auto_append'])) ? 1: 0,
			];

			if ( !isset($_POST['bp_reactions']['id'])  ) {

				$format = array('%s','%s','%d','%s');
				$wpdb->insert( $wpdb->prefix . "bp_reactions_shortcodes", array(
					'name' 			=> $_POST['bp_reactions']['shortcode_name'],
					'post_type' 	=> $_POST['bp_reactions']['post_type'],
					'front_render' 	=> (isset($_POST['bp_reactions']['auto_append'])) ? 1: 0,
					'options' 		=> json_encode( $_POST['bp_reactions'] )
				), $format);
				$id = $wpdb->insert_id;
				$_wp_http_referer = $_wp_http_referer . "&bpr_id=" . $id;
			} else {

				$where = [ 'id' => $_POST['bp_reactions']['id'] ]; // NULL value in WHERE clause.
				$wpdb->update( $wpdb->prefix . 'bp_reactions_shortcodes', $data, $where ); // Also works in this case.
				$id = $_POST['bp_reactions']['id'];

			}

			$bpr_clone_id = '';
			if ( isset($_POST['bp_reactions']['bpr_clone_id']) && $_POST['bp_reactions']['bpr_clone_id'] != '' ) {
				$bpr_clone_id = $_POST['bp_reactions']['bpr_clone_id'];
			}
			$_wp_http_referer = str_replace( array('bpr_clone_id', $bpr_clone_id), array('bpr_id', $id), $_wp_http_referer );
			wp_redirect(  $_wp_http_referer );
			exit();
		}

		if ( isset( $_POST['bpr_bp_integration_settings'] ) && ! defined( 'DOING_AJAX' )  ) {
			update_option( 'bpr_bp_integration_settings', $_POST['bpr_bp_integration_settings']);
			wp_redirect(  $_POST['_wp_http_referer'] );
			exit();
		}
	}


	public function buddypress_reactions_delete_shortcode(){

		global $wpdb;
		$res = $wpdb->delete(
					$wpdb->prefix . "bp_reactions_shortcodes",
					[ 'id' => $_POST['bpr_id'] ]
				);

		$response = [ 'status' => 'success', 'message' => __( 'Shortcode deleted successfully', 'buddypress-reactions' ) ];
		if ( $res === false ) {
			$response['status']  = 'error';
			$response['message'] = __( 'Could not delete shortcode', 'buddypress-reactions' );
		} else {
			$res = $wpdb->delete(
				$wpdb->prefix . "bp_reactions_reacted_emoji",
				[ 'bprs_id' => $_POST['bpr_id'] ]
			);

		}
		echo wp_json_encode($response);
		wp_die();
	}

	public function buddypress_reactions_update_emoji_name(){
		global $wpdb;
		$data = [
				'name'         => $_POST['emoji_name']
			];
		$where = [ 'id' => $_POST['emoji_id'] ]; // NULL value in WHERE clause.

		$wpdb->update( $wpdb->prefix . 'bp_reactions_emojis', $data, $where ); // Also works in this case.

		$response = [ 'status' => 'success', 'message' => __( 'Emoji name updated successfully', 'buddypress-reactions' ) ];
		echo wp_json_encode($response);
		wp_die();
	}

}
