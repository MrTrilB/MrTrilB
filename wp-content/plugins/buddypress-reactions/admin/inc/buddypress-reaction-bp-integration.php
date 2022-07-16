<?php
global $wpdb;
$bpr_bp_integration_settings           = get_option( 'bpr_bp_integration_settings' );
$table_name                            = $wpdb->prefix . 'bp_reactions_shortcodes ';
$query                                 = 'SELECT id, name FROM ' . $table_name . ' ORDER BY ID DESC';
$result                                = $wpdb->get_results( $query );
$bpr_bp_integration_settings['enable'] = ( isset( $bpr_bp_integration_settings['enable'] ) ) ? $bpr_bp_integration_settings['enable'] : '';
$bpr_bp_integration_settings['bp_shortcode_id'] = ( isset( $bpr_bp_integration_settings['bp_shortcode_id'] ) ) ? $bpr_bp_integration_settings['bp_shortcode_id'] : '';

$bpr_bp_integration_settings['enable_comment']          = ( isset( $bpr_bp_integration_settings['enable_comment'] ) ) ? $bpr_bp_integration_settings['enable_comment'] : '';
$bpr_bp_integration_settings['bp_comment_shortcode_id'] = ( isset( $bpr_bp_integration_settings['bp_comment_shortcode_id'] ) ) ? $bpr_bp_integration_settings['bp_comment_shortcode_id'] : '';
?>

<div class="wbcom-tab-content">
	<div class="bpr-settings-container">		
		<h3><?php esc_html_e( 'BuddyPress Integration', 'buddypress-reactions' ); ?></h3>
		<form method="post" action="options.php">
			<?php
				settings_fields( 'bpr_bp_integration_settings' );
				do_settings_sections( 'bpr_bp_integration_settings' );
			?>
			<table class="form-table">
				<tbody>
					<!-- Checkin Tab Visibility  -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Activate BP reactions on activity feed', 'buddypress-reactions' ); ?></label>
						</th>
						<td>
							<input type="checkbox" name="bpr_bp_integration_settings[enable]" value="yes" <?php checked( 'yes', $bpr_bp_integration_settings['enable'] ); ?>/>
						</td>
					</tr>
					<tr>
						<th>
							<label><?php esc_html_e( 'Choose your shortcode', 'buddypress-reactions' ); ?></label>
						</th>
						<td>
							<select name="bpr_bp_integration_settings[bp_shortcode_id]" >
								<?php if ( ! empty( $result ) ) : ?>
									<?php foreach ( $result as $bp_short ) : ?>
										<option value="<?php echo esc_attr( $bp_short->id ); ?>"  <?php selected( $bp_short->id, $bpr_bp_integration_settings['bp_shortcode_id'] ); ?>><?php echo esc_html( $bp_short->name ); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<label><?php esc_html_e( 'Activate BP reactions on activity comment', 'buddypress-reactions' ); ?></label>
						</th>
						<td>
							<input type="checkbox" name="bpr_bp_integration_settings[enable_comment]" value="yes" <?php checked( 'yes', $bpr_bp_integration_settings['enable_comment'] ); ?>/>
						</td>
					</tr>
					<tr>
						<th>
							<label><?php esc_html_e( 'Choose your shortcode', 'buddypress-reactions' ); ?></label>
						</th>
						<td>
							<select name="bpr_bp_integration_settings[bp_comment_shortcode_id]" >
								<?php if ( ! empty( $result ) ) : ?>
									<?php foreach ( $result as $bp_short ) : ?>
										<option value="<?php echo esc_attr( $bp_short->id ); ?>"  <?php selected( $bp_short->id, $bpr_bp_integration_settings['bp_comment_shortcode_id'] ); ?>><?php echo esc_html( $bp_short->name ); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
</div>
