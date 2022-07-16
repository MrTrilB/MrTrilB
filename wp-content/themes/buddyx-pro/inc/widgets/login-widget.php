<?php
/**
 * BuddyPress Login Widget.
 *
 * @package BuddyPress
 * @subpackage Login Widget
 * @since 5.6.6
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Login widget.
 *
 * @since 5.6.6
 */

class BP_BUDDYX_BP_Login_Widget extends WP_Widget {

	/**
	 * Working as a group, we get things done better.
	 *
	 * @since 1.0.3
	 */
	public function __construct() {
		$widget_ops = array(
			'description'                 => esc_html__( 'Display BP Login widget.', 'buddyxpro' ),
			'classname'                   => 'widget_buddyx_bp_login_widget buddypress widget',
			'customize_selective_refresh' => true,
		);
		parent::__construct( false, esc_html_x( 'BuddyX - BP Login', 'widget name', 'buddyxpro' ), $widget_ops );
	}

	/**
	 * Extends our front-end output method.
	 *
	 * @since 1.0.3
	 *
	 * @param array $args     Array of arguments for the widget.
	 * @param array $instance Widget instance data.
	 */
	public function widget( $args, $instance ) {

		$defaults           = array(
			'title'              => esc_html__( 'Login', 'buddyxpro' ),
			'login_redirect'     => 'current',
			'login_redirect_url' => '',
			'login_description'  => '',
		);
		$instance           = wp_parse_args( (array) $instance, $defaults );
		$title              = isset( $instance['title'] ) ? $instance['title'] : '';
		$login_redirect     = isset( $instance['login_redirect'] ) ? $instance['login_redirect'] : 'current';
		$login_redirect_url = isset( $instance['login_redirect_url'] ) ? $instance['login_redirect_url'] : '';
		$login_description  = isset( $instance['login_description'] ) ? $instance['login_description'] : '';
		$rand               = rand( 1000, 9999 );
		echo isset( $args['before_widget'] ) ? $args['before_widget'] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $login_description == '' ) {
			global $wbtm_buddyx_settings;
			$registration_page_url = wp_registration_url();
			if ( isset( $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'] ) && ( $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'] != '-1' && $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'] != '' ) ) {
				$registration_page_id  = $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'];
				$registration_page_url = get_permalink( $registration_page_id );
			}

			$login_description = sprintf(
				'<p>%s %s %s %s %s</p>',
				esc_html__( 'Don\'t you have an account?', 'buddyxpro' ),
				'<a href="' . $registration_page_url . '" title="' . esc_attr__( 'Register', 'buddyxpro' ) . '">',
				esc_html__( 'Register Now!', 'buddyxpro' ),
				'</a>',
				esc_html__( 'it\'s really simple and you can start enjoying all the benefits!', 'buddyxpro' )
			);
		}

		$attr = array(
			'forms'                => 'login',
			'login_title'          => $title,
			'redirect'             => $login_redirect,
			'redirect_to'          => $login_redirect_url,
			'login_description'    => $login_description,
			'register_redirect'    => '',
			'register_redirect_to' => '',
			'register_fields_type' => 'simple',
		);
		get_template_part( 'template-parts/form', '', $attr );

		echo isset( $args['after_widget'] ) ? $args['after_widget'] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	/**
	 * Extends our update method.
	 *
	 * @since 1.0.3
	 *
	 * @param array $new_instance New instance data.
	 * @param array $old_instance Original instance data.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']              = strip_tags( $new_instance['title'] );
		$instance['login_redirect']     = strip_tags( $new_instance['login_redirect'] );
		$instance['login_redirect_url'] = strip_tags( $new_instance['login_redirect_url'] );
		$instance['login_description']  = strip_tags( $new_instance['login_description'] );

		return $instance;
	}

	/**
	 * Extends our form method.
	 *
	 * @since 1.0.3
	 *
	 * @param array $instance Current instance.
	 * @return mixed
	 */
	public function form( $instance ) {
		$defaults = array(
			'title'              => esc_html__( 'Login', 'buddyxpro' ),
			'login_redirect'     => 'current',
			'login_redirect_url' => '',
			'login_description'  => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title              = strip_tags( $instance['title'] );
		$login_redirect     = strip_tags( $instance['login_redirect'] );
		$login_redirect_url = strip_tags( $instance['login_redirect_url'] );
		$login_description  = strip_tags( $instance['login_description'] );
		?>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'buddyxpro' ); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 100%" /></label></p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'login_redirect' ) ); ?>"><?php esc_html_e( 'Login Redirect', 'buddyxpro' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'login_redirect' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'login_redirect' ) ); ?>">
				<option value="current" <?php selected( $login_redirect, 'current' ); ?>><?php esc_html_e( 'Current page', 'buddyxpro' ); ?></option>
				<option value="profile" <?php selected( $login_redirect, 'profile' ); ?>><?php esc_html_e( 'Profile page', 'buddyxpro' ); ?></option>
				<option value="activity"  <?php selected( $login_redirect, 'activity' ); ?>><?php esc_html_e( 'Activity page', 'buddyxpro' ); ?></option>
				<option value="custom" <?php selected( $login_redirect, 'custom' ); ?>><?php esc_html_e( 'Custom page', 'buddyxpro' ); ?></option>
			</select>
		</p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'login_redirect_url' ) ); ?>"><?php esc_html_e( 'Login Custom URL', 'buddyxpro' ); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'login_redirect_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'login_redirect_url' ) ); ?>" type="text" value="<?php echo esc_attr( $login_redirect_url ); ?>" style="width: 100%" /></label></p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'login_description' ) ); ?>"><?php esc_html_e( 'Login description', 'buddyxpro' ); ?></label>
			<textarea class="widefat text " id="<?php echo esc_attr( $this->get_field_id( 'login_description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'login_description' ) ); ?>" rows="6" cols="20"><?php echo esc_attr( $login_description ); ?></textarea>				
			
		</p>
		<?php
	}

}

/**
 * Register the widget
 */
function buddyx_register_bp_login_widget() {
	register_widget( 'BP_BUDDYX_BP_Login_Widget' );
}

add_action( 'bp_widgets_init', 'buddyx_register_bp_login_widget' );
