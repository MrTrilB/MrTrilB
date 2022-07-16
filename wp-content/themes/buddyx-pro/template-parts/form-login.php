<?php
/**
 * Template part for displaying a login form content
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

?>

<?php
/**
 * @var int $rand
 * @var string $redirect_to
 * @var string $redirect
 * @var string $forms
 */
extract( $args );
$can_register = get_option( 'users_can_register' );

global $wbtm_buddyx_settings;
$registration_page_url = wp_registration_url();
if ( isset( $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'] ) && ( $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'] != '-1' && $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'] != '' ) ) {
	$registration_page_id  = $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'];
	$registration_page_url = get_permalink( $registration_page_id );
}

?>
<div class="title h6"><?php echo ( $login_title != '' ) ? esc_html($login_title) : esc_html__( 'Login to your Account', 'buddyxpro' ); ?></div>

<form data-handler="buddyx-signin-form" class="content buddyx-sign-form-login buddyx-sign-form" method="POST" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>">
	
	<input class="simple-input" type="hidden" value="<?php echo wp_create_nonce( 'buddyx-sign-form' ); ?>" name="_ajax_nonce" />

	<input class="simple-input" type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>"/>
	<input class="simple-input" type="hidden" name="redirect" value="<?php echo esc_attr( $redirect ); ?>"/>

	<?php do_action( 'buddyx-login_form_top' ); ?>
	
	<ul class="buddyx-sign-form-messages"></ul>

	<div class="form-group label-floating">
		<label class="control-label"><?php esc_html_e( 'Username', 'buddyxpro' ); ?></label>
		<input class="form-control simple-input" name="log" type="text">
	</div>
	<div class="form-group label-floating password-eye-wrap">
		<label class="control-label"><?php esc_html_e( 'Your Password', 'buddyxpro' ); ?></label>
		<a href="#" class="fa fa-fw fa-eye password-eye" tabindex="-1"></a>
		<input class="form-control simple-input" name="pwd"  type="password">
	</div>

	<div class="remember">

		<div class="checkbox">
			<label>
				<input name="rememberme" value="forever" type="checkbox">
				<?php esc_html_e( 'Remember Me', 'buddyxpro' ); ?>
			</label>
		</div>
		
		<a href="<?php echo esc_url( $registration_page_url ); ?>" class="register"><?php esc_html_e( 'Register', 'buddyxpro' ); ?></a>
		
		<?php $lostpswd = apply_filters( 'buddyx_lostpassword_url', wp_lostpassword_url() ); ?>

		<a href="<?php echo esc_url( $lostpswd ); ?>" class="forgot"><?php esc_html_e( 'Forgot my Password', 'buddyxpro' ); ?></a>
		
	</div>

	<?php do_action( 'buddyxpro_recaptcha_after_login_form' ); ?>

	<button type="submit" class="btn full-width registration-login-submit">
		<span><?php esc_html_e( 'Login', 'buddyxpro' ); ?></span>
		<span class="icon-loader"></span>
	</button>

	<?php do_action( 'buddyx_login_form_bottom' ); ?>

	<?php
	if ( $can_register ) {
		if ( $login_description != '' ) {
			echo wp_kses_post( wpautop( do_shortcode( $login_description ) ) );
		} else {
			echo sprintf(
				'<p>%s %s %s</p>',
				esc_html__( 'Don\'t you have an account?', 'buddyxpro' ),
				esc_html__( 'Register Now!', 'buddyxpro' ),
				esc_html__( 'it\'s really simple and you can start enjoying all the benefits!', 'buddyxpro' )
			);
		}
	}
	?>
</form>
