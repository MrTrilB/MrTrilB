<div class="lightbox lightbox-login">
	<a href="#" class="btn btn-default btn-dark close"><i class="fa fa-times"></i></a>
	<div class="container">
		<div class="row">
			<form class="goodgame-login" name="loginform" id="loginform" action="<?php echo get_home_url() . '/wp-login.php' ?>" method="post">
				<p class="input-wrapper">
					<input name="log" id="user_login" type="text" placeholder="<?php esc_html_e('Username', 'planetshine-goodgame'); ?>" />
				</p>
				<p class="input-wrapper">
					<input type="password" name="pwd" id="user_pass" placeholder="<?php esc_html_e('Password', 'planetshine-goodgame'); ?>" />
				</p>
				<p class="input-wrapper">
					<input type="submit" name="wp-submit" id="wp-submit" value="<?php esc_html_e('Login', 'planetshine-goodgame'); ?>" />
				</p>
				<p class="input-wrapper login-options">
					<input type="checkbox" name="rememberme" value="forever" id="rememberme"><label><?php esc_html_e('Remember me', 'planetshine-goodgame'); ?></label>
					<a href="<?php echo get_home_url() . '/wp-login.php?action=lostpassword' ?>" class="lost-password"><?php esc_html_e('Lost your password?', 'planetshine-goodgame'); ?></a>
				</p>
				<?php if (get_option( 'users_can_register' )): ?>
					<?php
						$gg_register_url = trim(goodgame_gs('register_account_link'));
						$register_link = '';
						if (strlen($gg_register_url) > 0) {
							$register_link = esc_url($gg_register_url);
						}	
						else {
							$register_link = wp_registration_url();
						}
					?>
					<p class="input-wrapper register-now-link">
						<a href="<?php echo $register_link; ?>"><?php esc_html_e('Register now', 'planetshine-goodgame'); ?></a>
					</p>
				<?php endif; ?>
			</form>
		</div>
	</div>
</div>
