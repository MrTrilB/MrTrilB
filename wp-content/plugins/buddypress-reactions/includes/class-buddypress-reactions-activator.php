<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Reactions
 * @subpackage Buddypress_Reactions/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Buddypress_Reactions
 * @subpackage Buddypress_Reactions/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Reactions_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		
		$charset_collate = $wpdb->get_charset_collate();
		
		$sql[]  = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "bp_reactions_reacted_emoji (
                id bigint NOT NULL AUTO_INCREMENT,
                user_id BIGINT(20) NOT NULL,
                post_id BIGINT(20) NOT NULL,
                post_type varchar(50) NOT NULL,				
                reacted_to varchar(10) NOT NULL,
                emoji_id SMALLINT NOT NULL,
                bprs_id BIGINT(20) NOT NULL,
				reacted_date DATETIME NOT NULL,
                PRIMARY KEY (id)
        ) $charset_collate;";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "bp_reactions_emojis (
    		id int auto_increment primary key,
    		name varchar(500) null,
			type enum ('inbuilt', 'custom') not null,
    		format varchar(10) not null
		) $charset_collate";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "bp_reactions_shortcodes (
                id bigint NOT NULL AUTO_INCREMENT,
                name varchar(255),                
                post_type varchar(255),
                front_render boolean not null default true,
                options text,
                PRIMARY KEY (id)
        ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		foreach ($sql as $db_sql) {
			dbDelta($db_sql);
		}
		
		$emoji_count = $wpdb->get_var("SELECT count(*) from " .$wpdb->prefix . "bp_reactions_emojis where type = 'inbuilt'");
		if( $emoji_count != 200 ) {
			$json = file_get_contents( BUDDYPRESS_REACTIONS_PLUGIN_PATH . "emojis/bp-reaction-emojis.json" );

			$bp_reactions_emojis = json_decode( $json, true );
			foreach( $bp_reactions_emojis as $emojis ) {
				$wpdb->insert($wpdb->prefix . "bp_reactions_emojis", array(
					'name' 		=> $emojis,
					'type' 		=> 'inbuilt',
					'format' 	=> 'svg/json',				
				));
			}
		}
		
		if( !get_option( 'bpr_activate_first' )) {
			$format = array('%s','%s','%d','%s');
			$wpdb->insert( $wpdb->prefix . "bp_reactions_shortcodes", array(
				'name' 			=> 'Post Reaction',
				'post_type' 	=> 'post',
				'front_render' 	=> 1,
				'options' 		=> '{"emojis":["64","190","36","5","42","29"],"shortcode_name":"Post Reaction","post_type":"post","auto_append":"1","animation":"true"}'
			), $format);
			$id = $wpdb->insert_id;
			
			$wpdb->insert( $wpdb->prefix . "bp_reactions_shortcodes", array(
				'name' 			=> 'Activity Reaction',
				'post_type' 	=> '',
				'front_render' 	=> 1,
				'options' 		=> '{"emojis":["64","190","36","5","42","29"],"shortcode_name":"Activity Reaction","post_type":"","auto_append":"1","animation":"true"}'
			), $format);
			$activity_bpsr_id = $wpdb->insert_id;
			
			$wpdb->insert( $wpdb->prefix . "bp_reactions_shortcodes", array(
				'name' 			=> 'Activity Comment Reaction',
				'post_type' 	=> '',
				'front_render' 	=> 1,
				'options' 		=> '{"emojis":["64","190","36","5","42","29"],"shortcode_name":"Activity Comment Reaction","post_type":"","auto_append":"1","animation":"true"}'
			), $format);
			$activity_comment_bpsr_id = $wpdb->insert_id;
			
			$bpr_bp_integration_settings = [];
			$bpr_bp_integration_settings['enable'] = 'yes';
			$bpr_bp_integration_settings['bp_shortcode_id'] = $activity_bpsr_id;
			$bpr_bp_integration_settings['enable_comment'] = 'yes';
			$bpr_bp_integration_settings['bp_comment_shortcode_id'] = $activity_comment_bpsr_id;
			
			update_option( 'bpr_bp_integration_settings', $bpr_bp_integration_settings );
			
			update_option( 'bpr_activate_first', true );
		}
	}

}
