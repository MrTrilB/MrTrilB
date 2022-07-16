<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Reactions
 * @subpackage Buddypress_Reactions/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Buddypress_Reactions
 * @subpackage Buddypress_Reactions/public
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Reactions_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		$rtl_css = is_rtl() ? '-rtl' : '';

		wp_enqueue_style( 'br-icons', plugin_dir_url( __FILE__ ) . 'css/br-icons.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css' . $rtl_css . '/buddypress-reactions-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( 'lottie', plugin_dir_url( __FILE__ ) . 'js/lottie.min.js', array( 'jquery' ), $this->version, false );		
		wp_register_script( 'chart-js', plugin_dir_url( __FILE__ ) . 'js/chart.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-reactions-public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name,
			'bpreactions',
			array(
				'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
				'emojis_path' => BUDDYPRESS_REACTIONS_PLUGIN_URL . 'emojis/',
				'version'     => BUDDYPRESS_REACTIONS_VERSION,
				'ajax_nonce'  => wp_create_nonce( 'bp-reactions' ),
				'reactions_count'  => __( 'Reactions Count', 'buddypress-reactions' ),
			)
		);

	}

	public function buddypress_reactions_activity_entry_meta() {
		global $wpdb;

		if ( ! is_user_logged_in() ) {
			return;
		}

		$bpr_bp_integration_settings = get_option( 'bpr_bp_integration_settings' );
		if ( isset( $bpr_bp_integration_settings['enable'] ) && isset( $bpr_bp_integration_settings['bp_shortcode_id'] ) && $bpr_bp_integration_settings['bp_shortcode_id'] != '' ) {
			$bp_shortcode_id = $bpr_bp_integration_settings['bp_shortcode_id'];
			$table_name      = $wpdb->prefix . 'bp_reactions_shortcodes ';
			$query           = 'SELECT options FROM ' . $table_name . ' where id=' . $bp_shortcode_id;
			$bp_reactions    = $wpdb->get_var( $query );
			$bp_reactions    = json_decode( $bp_reactions, true );
			$emojis          = $bp_reactions['emojis'];
			$animation       = $bp_reactions['animation'];

			$user_id          = get_current_user_id();
			$query            = $wpdb->prepare( 'SELECT emoji_id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE user_id = %s and post_id = %s and  post_type = %s and bprs_id= %s', $user_id, bp_get_activity_id(), 'activity', $bp_shortcode_id );
			$reacted_emoji_id = $wpdb->get_var( $query );

			$bp_reations_classes = 'bp-reactions-animation-' . $animation;

			?>
			<div class="generic-button bp-activity-react-button-wrapper">
				<div class="bp-activity-react-btn">
					<a class="button item-button bp-secondary-action bp-activity-react-button" rel="nofollow" data-post-id="<?php echo esc_attr( bp_get_activity_id() ); ?>" data-type="activity"  data-bprs-id="<?php echo esc_attr( $bp_shortcode_id ); ?>">
						<div class="bp-post-react-icon bp-activity-react-icon">
							<?php if ( $reacted_emoji_id != '' && $reacted_emoji_id != 0 ) : ?>
								<img class="post-option-image" src="<?php echo get_buddypress_reaction_emoji( $reacted_emoji_id, 'svg' ); ?>" alt="">
							<?php else : ?>
								<div class="icon-thumbs-up">
									<i class="br-icon br-icon-smile"></i>
								</div>
							<?php endif; ?>
						</div>
						<span class="bp-react-button-text"><?php esc_html_e( 'React!', 'buddypress-reactions' ); ?></span>
					</a>
				</div>
				<div class="bp-activity-reactions reaction-options emoji-picker <?php echo esc_attr( $bp_reations_classes ); ?>">
					<?php if ( ! empty( $emojis ) ) : ?>
						<?php foreach ( $emojis as $emoji ) : ?>
							<div class="emoji-pick" data-post-id="<?php echo esc_attr( bp_get_activity_id() ); ?>" data-type="activity" data-emoji-id="<?php echo $emoji; ?>" title="<?php echo $emoji; ?>" data-bprs-id="<?php echo esc_attr( $bp_shortcode_id ); ?>" >
								<div class="emoji-lottie-holder" style="display: none"></div>
								<figure itemprop="gif" class="emoji-svg-holder" style="background-image: url('<?php echo get_buddypress_reaction_emoji( $emoji, 'svg' ); ?>'"></figure>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
	}

	public function buddypress_reactions_activity_comment_options() {

		global $wpdb;

		$bpr_bp_integration_settings = get_option( 'bpr_bp_integration_settings' );
		if ( isset( $bpr_bp_integration_settings['enable_comment'] ) && isset( $bpr_bp_integration_settings['bp_comment_shortcode_id'] ) && $bpr_bp_integration_settings['bp_comment_shortcode_id'] != '' ) {

			$bp_comment_shortcode_id = $bpr_bp_integration_settings['bp_comment_shortcode_id'];
			$table_name              = $wpdb->prefix . 'bp_reactions_shortcodes ';
			$query                   = 'SELECT id, options FROM ' . $table_name . ' where id=' . $bp_comment_shortcode_id;
			$bp_reactions            = $wpdb->get_results( $query );

			$bprs_id             = $bp_reactions[0]->id;
			$bp_reactions        = json_decode( $bp_reactions[0]->options, true );
			$emojis              = $bp_reactions['emojis'];
			$animation           = $bp_reactions['animation'];
			$bp_reations_classes = 'bp-reactions-animation-' . $animation;

			$user_id             = get_current_user_id();
			$activity_comment_id = bp_get_activity_comment_id();
			$activity_id         = bp_get_activity_id();
			$query               = $wpdb->prepare( 'SELECT emoji_id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE user_id = %s and post_id = %s and  post_type = %s and bprs_id=%s', $user_id, $activity_comment_id, 'activity-comment', $bprs_id );

			$reacted_emoji_id = $wpdb->get_var( $query );
			?>
			<div class="bp-react-activity-comment">
				<?php bpr_bp_post_type_reactions_meta( $activity_comment_id, 'activity-comment', $bp_comment_shortcode_id ); ?>
				<div id="bp-activity-comment-react-<?php echo esc_attr( $activity_comment_id ); ?>" class="bp-activity-comment-react-button bp-activity-react-button-wrapper">
					<div class="bp-activity-react-btn">
						<a class="button item-button bp-secondary-action bp-activity-react-button" rel="nofollow" data-post-id="<?php echo esc_attr( $activity_comment_id ); ?>" data-type="activity-comment" data-bprs-id="<?php echo esc_attr( $bp_comment_shortcode_id ); ?>">
							<?php esc_html_e( 'React!', 'buddypress-reactions' ); ?>
						</a>
					</div>
					<div class="bp-activity-reactions reaction-options emoji-picker <?php echo esc_attr( $bp_reations_classes ); ?>">
						<?php if ( ! empty( $emojis ) ) : ?>
							<?php foreach ( $emojis as $emoji ) : ?>
								<div class="emoji-pick" data-post-id="<?php echo esc_attr( $activity_comment_id ); ?>" data-type="activity-comment" data-emoji-id="<?php echo $emoji; ?>" title="<?php echo $emoji; ?>" data-bprs-id="<?php echo esc_attr( $bp_comment_shortcode_id ); ?>" >
									<div class="emoji-lottie-holder" style="display: none"></div>
									<figure itemprop="gif" class="emoji-svg-holder" style="background-image: url('<?php echo get_buddypress_reaction_emoji( $emoji, 'svg' ); ?>'"></figure>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
		}

	}

	public function bpr_create_user_react_emoji_ajax() {

		if ( wp_verify_nonce( $_POST['ajax_nonce'], 'bp-reactions' ) ) {
			global $wpdb;

			$emoji_id         = sanitize_text_field( $_POST['emoji_id'] );
			$post_id          = sanitize_text_field( $_POST['post_id'] );
			$post_type        = sanitize_text_field( $_POST['post_type'] );
			$bprs_id          = sanitize_text_field( $_POST['bprs_id'] );
			$user_id          = get_current_user_id();
			$reacted_emoji_id = 0;
			$query            = $wpdb->prepare( 'SELECT id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE user_id = %s and post_id = %s and  post_type = %s and bprs_id = %s', $user_id, $post_id, $post_type, $bprs_id );

			$reacted_emoji_id = $wpdb->get_var( $query );

			$data = array(
				'user_id'      => $user_id,
				'post_id'      => $post_id,
				'post_type'    => $post_type,
				'reacted_to'   => 'emoji-' . $emoji_id,
				'emoji_id'     => $emoji_id,
				'bprs_id'      => $bprs_id,
				'reacted_date' => date( 'Y-m-d H:i:s' ),
			);
			if ( $reacted_emoji_id != 0 && $reacted_emoji_id != '' ) {
				$response['action'] = 'update';
				$response['status'] = $wpdb->update(
					$wpdb->prefix . 'bp_reactions_reacted_emoji',
					$data,
					array(
						'id'      => $reacted_emoji_id,
						'user_id' => $user_id,
						'post_id' => $post_id,
					)
				) > 0
					? 'success'
					: 'error';
			} else {
				$response['status'] = $wpdb->insert( $wpdb->prefix . 'bp_reactions_reacted_emoji', $data ) > 0
				? 'success'
				: 'error';
			}
			if ( $post_type == 'activity' ) {
				ob_start();
				$this->bpr_bp_activity_reactions_meta( $post_id );
				$response['container'] = ob_get_clean();
			} else {

				ob_start();
				bpr_bp_post_type_reactions_meta( $post_id, $post_type, $bprs_id );
				$response['container'] = ob_get_clean();
			}
		}

		echo wp_json_encode( $response );
		wp_die();
	}

	public function bpr_remove_user_react_emoji_ajax() {
		if ( wp_verify_nonce( $_POST['ajax_nonce'], 'bp-reactions' ) ) {
			global $wpdb;

			$post_id          = sanitize_text_field( $_POST['post_id'] );
			$post_type        = sanitize_text_field( $_POST['post_type'] );
			$bprs_id          = sanitize_text_field( $_POST['bprs_id'] );
			$user_id          = get_current_user_id();
			$reacted_emoji_id = 0;
			$query            = $wpdb->prepare( 'SELECT id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE user_id = %s and post_id = %s and  post_type = %s and bprs_id = %s', $user_id, $post_id, $post_type, $bprs_id );

			$reacted_emoji_id = $wpdb->get_var( $query );

			if ( $reacted_emoji_id != 0 && $reacted_emoji_id != '' ) {
				$response['action'] = 'delete';
				$response['status'] = $wpdb->delete(
					$wpdb->prefix . 'bp_reactions_reacted_emoji',
					array(
						'id'        => $reacted_emoji_id,
						'user_id'   => $user_id,
						'post_id'   => $post_id,
						'post_type' => $post_type,
						'bprs_id'   => $bprs_id,
					)
				) > 0
					? 'success'
					: 'error';
			}
			if ( $post_type == 'activity' ) {
				ob_start();
				$this->bpr_bp_activity_reactions_meta( $post_id );
				$response['container'] = ob_get_clean();
			} else {

				ob_start();
				bpr_bp_post_type_reactions_meta( $post_id, $post_type, $bprs_id );
				$response['container'] = ob_get_clean();
			}
		}

		echo wp_json_encode( $response );
		wp_die();
	}

	public function bpr_bp_activity_reactions_meta( $activity_id = '' ) {
		global $wpdb;

		$bpr_bp_integration_settings = get_option( 'bpr_bp_integration_settings' );
		if ( isset( $bpr_bp_integration_settings['enable'] ) && isset( $bpr_bp_integration_settings['bp_shortcode_id'] ) && $bpr_bp_integration_settings['bp_shortcode_id'] != '' ) {
			$bp_shortcode_id = $bpr_bp_integration_settings['bp_shortcode_id'];

			$activity_id = ( $activity_id != '' ) ? $activity_id : bp_get_activity_id();

			$query          = $wpdb->prepare( 'SELECT count(*) FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE post_id = %s and  post_type = %s and bprs_id= %s', $activity_id, 'activity', $bp_shortcode_id );
			$reacted_counts = $wpdb->get_var( $query );

			$reacted_style = '';
			if ( $reacted_counts == 0 ) {
				$reacted_style = "style='display:none';";
			}

			$query                   = $wpdb->prepare( 'SELECT emoji_id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE post_id = %s and  post_type = %s and bprs_id= %s group by emoji_id;', $activity_id, 'activity', $bp_shortcode_id );
			$reacted_activity_emojis = $wpdb->get_results( $query );

			?>
			<div id="bp-reactions-post-<?php echo esc_attr( $activity_id ); ?>" class="reacted-count content-actions" <?php echo $reacted_style; ?> >
				<div class="content-action" data-post-id="<?php echo esc_attr( $activity_id ); ?>" data-post-type="activity" data-bprs-id="<?php echo esc_attr( $bp_shortcode_id ); ?>">
					<div class="meta-line">
						<div class="reaction-item-list meta-line-list">
							<?php if ( ! empty( $reacted_activity_emojis ) ) : ?>
								<?php foreach ( $reacted_activity_emojis as $reacted_emoji ) : ?>
									<div class="wbreacted-emoji-container reaction-item-wrap" data-emoji_id="<?php echo esc_attr( $reacted_emoji->emoji_id ); ?>">
										<div class="reaction-item" style="position: relative;">
											<img class="reaction-image" src="<?php echo esc_url( get_buddypress_reaction_emoji( $reacted_emoji->emoji_id, 'svg' ) ); ?>"  alt="">
											<div class="simple-dropdown reaction-list-dropdown">
												<div class="reacted-emoji simple-dropdown-text">
													<img class="reaction" src="<?php echo esc_url( get_buddypress_reaction_emoji( $reacted_emoji->emoji_id, 'svg' ) ); ?>"  alt="">
													<span class="bold"><?php echo get_buddypress_reaction_emoji_name( $reacted_emoji->emoji_id ); ?></span>
												</div>
												<div class="simple-dropdown-text">
													<?php
													$query                = $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE post_id = %s and  post_type = %s and emoji_id = %s and bprs_id= %s', $activity_id, 'activity', $reacted_emoji->emoji_id, $bp_shortcode_id );
													$reacted_emojis_users = $wpdb->get_results( $query );
													?>
													<?php if ( ! empty( $reacted_emojis_users ) ) : ?>
														<ul class="reacted-users">
															<?php foreach ( $reacted_emojis_users as $user ) : ?>
																<li class="reacted-user"><?php echo esc_html( bp_core_get_username( $user->user_id ) ); ?></li>
															<?php endforeach; ?>
														</ul>
													<?php endif; ?>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
						<span class="meta-line-text total-reaction-counts meta-line-text-trigger" data-emoji_id="all" data-bprs-id="<?php echo esc_attr($bp_shortcode_id);?>"><?php echo esc_html( $reacted_counts ); ?></span>
					</div>
				</div>
			</div>
			<?php
		}
	}



	public function bpr_display_user_react_emoji_ajax() {

		if ( wp_verify_nonce( $_POST['ajax_nonce'], 'bp-reactions' ) ) {
			global $wpdb;

			$emoji_id  = sanitize_text_field( $_POST['emoji_id'] );
			$post_id   = sanitize_text_field( $_POST['post_id'] );
			$post_type = sanitize_text_field( $_POST['post_type'] );
			$bprs_id   = sanitize_text_field( $_POST['bprs_id'] );

			$query          = $wpdb->prepare( 'SELECT count(*) FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE post_id = %s and  post_type = %s and bprs_id = %s', $post_id, $post_type, $bprs_id );
			$reacted_counts = $wpdb->get_var( $query );

			$query                         = $wpdb->prepare( 'SELECT count(*) as count, emoji_id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE post_id = %s and  post_type = %s and bprs_id = %s group by emoji_id;', $post_id, $post_type, $bprs_id );
			$reacted_activity_emojis_group = $wpdb->get_results( $query );

			$query                   = $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE post_id = %s and  post_type = %s and bprs_id = %s', $post_id, $post_type, $bprs_id );
			$reacted_activity_emojis = $wpdb->get_results( $query );
			ob_start();
			?>
			<div class="reaction-box">
				<div class="reaction-box-options">
					<?php if ( $reacted_counts != 0 ) : ?>
						<ul>
							<li class="reaction-box-option <?php echo ( $emoji_id == 'all' ) ? 'active' : ''; ?>" data-id="all">
								<p class="reaction-box-option-text"><?php echo sprintf( __( 'All: %s', 'buddypress-reactions' ), $reacted_counts ); ?></p>
							</li>
							<?php
							foreach ( $reacted_activity_emojis_group as $emojo ) :
									$active_class = ( $emojo->emoji_id == $emoji_id ) ? 'active' : '';
								?>
								<li class="reaction-box-option <?php echo esc_attr( $active_class ); ?>" data-id="<?php echo esc_attr( $emojo->emoji_id ); ?>">
									<img src="<?php echo get_buddypress_reaction_emoji( $emojo->emoji_id, 'svg' ); ?>"  alt="" class="user-status-reaction-image">
									<p class="reaction-box-option-text"><?php echo $emojo->count; ?></p>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<div class="reaction-box-content">
					<div class="reaction-box-item">
					<?php if ( ! empty( $reacted_activity_emojis ) ) : ?>
						<div class="user-status-list">
						<?php
						foreach ( $reacted_activity_emojis as $reacted ) :

							$username = bp_core_get_username( $reacted->user_id );
							$url      = bp_core_get_user_domain( $reacted->user_id );

							$avatar_url = bp_core_fetch_avatar(
								array(
									'item_id' => $reacted->user_id,
									'type'    => 'full',
									'width'   => 150,
									'height'  => 150,
									'class'   => 'avatar',
									'id'      => false,
									'alt'     => sprintf( __( 'Profile picture of %s', 'buddypress-reactions' ), $username ),
								)
							);
							?>
							<div class="user-status request-small bp-reacted-emoji-<?php echo $reacted->emoji_id; ?>">
								<a href="<?php echo esc_url( $url ); ?>" class="user-avatar-circle small no-stats no-border user-status-avatar">
									<?php echo $avatar_url; ?>
								</a>
								<div class="user-status-title">
									<a href="<?php echo esc_url( $url ); ?>"><?php echo $username; ?></a>
								</div>
								<div class="action-request-list">
									<img src="<?php echo get_buddypress_reaction_emoji( $reacted->emoji_id, 'svg' ); ?>"  alt="" class="user-status-reaction-image">
								</div>
							</div>
						<?php endforeach; ?>
						</div>
					<?php endif; ?>
					</div>
				</div>
			</div>
			<?php

			$response = ob_get_clean();

		}
		echo wp_json_encode( $response );
		wp_die();
	}

	public function bpr_post_reactions_the_content( $content ) {
		global $post, $wpdb;

		// Give it to Woo integration
		if ( get_post_type() == 'product' ) {
			return $content;
		}

		// add emojis if only we are in main query and looping
		// TODO: check if this has any effect
		if ( ! ( in_the_loop() and is_main_query() ) ) {
			return $content;
		}

		$query                = $wpdb->prepare( 'SELECT id FROM ' . $wpdb->prefix . 'bp_reactions_shortcodes WHERE post_type = %s and front_render=%s limit 1', get_post_type(), 1 );
		$reactions_shortcodes = $wpdb->get_results( $query );

		if ( ! empty( $reactions_shortcodes ) ) {
			$shortcodes_options['id'] = $reactions_shortcodes[0]->id;
			$reactions                = $this->handle_reactions( $shortcodes_options );

			return $content . $reactions;

		}

		return $content;
	}

	public function handle_reactions( $atts = array() ) {
		global $wpdb;

		$layout = 'default';

		$query                = $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_reactions_shortcodes WHERE id = %s limit 1', $atts['id'] );
		$reactions_shortcodes = $wpdb->get_results( $query );
		ob_start();

		if ( ! empty( $reactions_shortcodes ) ) {

			$atts                    = json_decode( $reactions_shortcodes[0]->options, true );
			$atts['bp_shortcode_id'] = $reactions_shortcodes[0]->id;
			$user_id                 = get_current_user_id();
			$query                   = $wpdb->prepare( 'SELECT emoji_id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji  WHERE post_type = %s and bprs_id = %s and user_id = %s limit 1', get_post_type(), $reactions_shortcodes[0]->id, $user_id );
			$user_emoji_id           = $wpdb->get_var( $query );

			$atts['user_emoji_id'] = $user_emoji_id;

			$out = bp_ractions_emoji_template( "templates/layouts/$layout", array( 'params' => $atts ) );
		}

		return ob_get_clean();

	}
}
