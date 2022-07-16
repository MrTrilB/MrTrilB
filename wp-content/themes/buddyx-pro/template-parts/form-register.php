<?php
/**
 * Template part for displaying a register form content
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

?>

<?php
/**
 * @var int    $rand
 * @var string $redirect_to
 * @var string $redirect
 * @var string $forms
 * @var string $login_descr
 */
extract( $args );
?>
<div class="title h6"><?php esc_html_e( 'Register in', 'buddyxpro' ); ?>&nbsp;<?php echo get_bloginfo( 'name' ); ?></div>
<form data-handler="buddyx-signup-form" name="registerform" class="content buddyx-sign-form-register buddyx-sign-form" action="<?php echo esc_url( site_url( 'wp-login.php?action=register&type=internal', 'login_post' ) ); ?>" method="post">
	<div class="buddyx-sign-form-success-email-message"> 
		<img src="<?php echo get_template_directory_uri() . '/assets/images/check-circle.gif'; ?>" class="buddyx-register-sent-email"/>
		<span class="h3"><?php esc_html_e( 'Thanks for registration!', 'buddyxpro' ); ?></span>
		<p><?php echo sprintf( __( 'We just sent you an Email. %s Please Open it up to activate your account.', 'buddyxpro' ), '<br />' ); ?></p>
	</div>
	
	<input class="simple-input" type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
	<input class="simple-input" type="hidden" name="redirect" value="<?php echo esc_attr( $redirect ); ?>" />

	<input class="simple-input" type="hidden" value="<?php echo wp_create_nonce( 'buddyx-sign-form' ); ?>" name="_ajax_nonce" />
	
	<div class="buddyx-sign-form-register-fields">       

		<ul class="buddyx-sign-form-messages"></ul>
			
		<?php do_action( 'buddyx_register_form_top' ); ?>
	
		<?php if ( $register_fields_type == 'simple' ) { ?>
			<div class="form-group label-floating">
				<label class="control-label"><?php esc_html_e( 'First Name', 'buddyxpro' ); ?></label>
				<input class="form-control simple-input" name="first_name" type="text">
			</div>

			<div class="form-group label-floating">
				<label class="control-label"><?php esc_html_e( 'Last Name', 'buddyxpro' ); ?></label>
				<input class="form-control simple-input" name="last_name" type="text">
			</div>
		<?php } ?>

		<div class="form-group label-floating">
			<label class="control-label"><?php esc_html_e( 'Username', 'buddyxpro' ); ?></label>
			<input type="text" name="user_login" class="form-control simple-input" size="20" />
		</div>
		
		<div class="form-group label-floating">
			<label class="control-label"><?php esc_html_e( 'Your Email', 'buddyxpro' ); ?></label>
			<input type="email" name="user_email" class="form-control simple-input" size="25" />
		</div>

		<?php
		if ( $register_fields_type != 'simple' ) {
			if ( function_exists( 'bp_core_get_user_domain' ) && function_exists( 'bp_activity_get_user_mentionname' ) && function_exists( 'bp_attachments_get_attachment' ) && function_exists( 'bp_loggedin_user_domain' ) && function_exists( 'bp_is_active' ) && function_exists( 'bp_get_activity_slug' ) && function_exists( 'bp_is_active' ) && function_exists( 'bp_get_notifications_unread_permalink' ) && function_exists( 'bp_loggedin_user_domain' ) && function_exists( 'bp_get_settings_slug' ) ) {
				if ( bp_is_active( 'xprofile' ) && ! function_exists( 'bp_nouveau_has_signup_xprofile_fields' ) ) :
					if ( bp_has_profile(
						array(
							'profile_group_id' => 1,
							'fetch_field_data' => false,
						)
					) ) :
						buddyx_bp_fields( $register_fields_type );
				endif;
				elseif ( bp_is_active( 'xprofile' ) && bp_nouveau_has_signup_xprofile_fields( true ) ) :
					buddyx_bp_fields( $register_fields_type );
				endif;
			}
		}
		?>

		<?php if ( $register_fields_type != 'extensional' ) { ?>
			<div class="form-group label-floating password-eye-wrap">
				<label class="control-label"><?php esc_html_e( 'Your Password', 'buddyxpro' ); ?></label>
				<a href="#" class="fa fa-fw fa-eye password-eye" tabindex="-1"></a>
				<input type="password" name="user_password" class="form-control simple-input sign-form-password-verify" size="25" />
				<div class="sign-form-pass-strength-result"></div>
			</div>

			<div class="form-group label-floating password-eye-wrap">
				<label class="control-label"><?php esc_html_e( 'Confirm Password', 'buddyxpro' ); ?></label>
				<a href="#" class="fa fa-fw fa-eye password-eye" tabindex="-1"></a>
				<input type="password" name="user_password_confirm" class="form-control sign-form-password-verify-confirm" size="25" />
			</div>
		<?php } ?>
		
		<?php if ( function_exists( 'bp_signup_requires_privacy_policy_acceptance' ) && bp_signup_requires_privacy_policy_acceptance() && function_exists( 'bp_nouveau_signup_privacy_policy_acceptance_section' ) ) : ?>
			<div class="form-group">
				<?php bp_nouveau_signup_privacy_policy_acceptance_section(); ?>
			</div>
		<?php endif; ?>

		<?php if ( function_exists( 'bp_nouveau_signup_terms_privacy' ) ) : ?>
			<div class="form-group">
				<?php bp_nouveau_buddyx_signup_terms_privacy(); ?>
			</div>
		<?php endif; ?>
		
		<?php do_action( 'buddyxpro_recaptcha_after_register_form' ); ?>

		<button type="submit" class="btn full-width registration-login-submit">
			<span><?php esc_html_e( 'Complete Registration!', 'buddyxpro' ); ?></span>
			<span><?php esc_html_e( 'Login', 'buddyxpro' ); ?></span>
			<span class="icon-loader"></span>
		</button>
		
		<?php do_action( 'buddyx_register_form_bottom' ); ?>
		
	</div>
</form>
