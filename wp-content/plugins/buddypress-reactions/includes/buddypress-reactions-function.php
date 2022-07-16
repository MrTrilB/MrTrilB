<?php


function get_buddypress_reactions_emojis( $type ) {
	global $wpdb;
	if ( $type != '' ) {
		return $wpdb->get_results("SELECT * from " . $wpdb->prefix . "bp_reactions_emojis where type = '$type'");
	} else {
		return $wpdb->get_results("SELECT * from " . $wpdb->prefix . "bp_reactions_emojis" );
	}
}


function get_buddypress_reactions_emojisPath() {
	return BUDDYPRESS_REACTIONS_PLUGIN_URL . 'emojis/';
}

function get_buddypress_reaction_emoji( $name, $type ) {
	$v    = '?v=' . BUDDYPRESS_REACTIONS_VERSION;
	$path = get_buddypress_reactions_emojisPath();

	return $path . "{$type}/{$name}.{$type}" . $v;
}


function get_buddypress_reaction_emoji_name( $emoji_id ) {
	global $wpdb;
	
	return $wpdb->get_var("SELECT name from " . $wpdb->prefix . "bp_reactions_emojis where  id=" . $emoji_id );
	
}


function bp_ractions_emoji_template( $name, $data = [] ) {	
	
	$file = apply_filters('bp_reactions_emoji_layout', BUDDYPRESS_REACTIONS_PLUGIN_PATH . $name . '.php', $name) ;

	if ( !file_exists($file) ) {
		return;
	}
	
	require( $file );
		
}

function bp_reactions_build_linestyle( $args ) {
	$style = '';
	foreach ( $args as $key => $val ) {
		$style .= $key . ':' . $val . ';';
	}

	return $style;
}

function bp_reactions_emoji_counts( $emoji_id, $post_id, $post_type, $bp_shortcode_id ) {
	global $wpdb;
	
	$query          = $wpdb->prepare( 'SELECT count(*) as count FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE emoji_id = %s and post_id = %s and post_type = %s and bprs_id = %s', $emoji_id, $post_id, $post_type, $bp_shortcode_id );
	return $wpdb->get_var($query);
	
}

function bp_reactions_emoji_count_format( $count ) {
	
	$format = $count;
	if ( $count >= 1000000 ) {
		$format = round( ( $count / 1000000 ), 1 ) . 'M';
	} elseif ( $count >= 1000 ) {
		$format = round( ( $count / 1000 ), 1 ) . 'K';
	}

	return $format;
}


function bpr_bp_post_type_reactions_meta( $post_id, $post_type, $bprs_id) {
	global $wpdb;
	$query          = $wpdb->prepare( 'SELECT count(*) FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE post_id = %s and  post_type = %s and bprs_id = %s', $post_id, $post_type, $bprs_id );
	$reacted_counts = $wpdb->get_var( $query );
	
	$reacted_style = "";
	if ( $reacted_counts == 0) {
		$reacted_style = "style='display:none';";
	}

	$query                   = $wpdb->prepare( 'SELECT emoji_id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE post_id = %s and  post_type = %s and bprs_id = %s group by emoji_id;', $post_id, $post_type, $bprs_id );
	$reacted_post_emojis = $wpdb->get_results( $query );
	
	?>
	<div id="bp-reactions-post-<?php echo esc_attr( $post_id ); ?>" class="reacted-count content-actions" <?php echo $reacted_style; ?> >
		<div class="content-action" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-post-type="<?php echo esc_attr($post_type);?>" data-bprs-id="<?php echo esc_attr($bprs_id);?>">
			<div class="meta-line">
				<div class="reaction-item-list meta-line-list">
					<?php if ( ! empty( $reacted_post_emojis ) ) : ?>
						<?php foreach ( $reacted_post_emojis as $reacted_emoji ) : ?>	
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
											$query                = $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE post_id = %s and  post_type = %s and emoji_id = %s and bprs_id = %s', $post_id, $post_type, $reacted_emoji->emoji_id, $bprs_id );
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
				<span class="meta-line-text total-reaction-counts meta-line-text-trigger" data-emoji_id="all" data-bprs-id="<?php echo esc_attr($bprs_id);?>"><?php echo esc_html( $reacted_counts ); ?></span>
			</div>
		</div>			
	</div>
	<?php
}

