<?php

function goodgame_version_migrate()
{
    $prev_version = get_option('goodgame_previous_' . GOODGAME_THEME_DOMAIN .'_version', '1.0');   //for now use 1.0.4 as prev version
    $migrated_version = get_option('goodgame_' . GOODGAME_THEME_DOMAIN .'_migrated_version', $prev_version);
    $theme = wp_get_theme();
    $version = $theme->get('Version');

   //Only run in admin
    if( is_admin())
    {

    	//Product attribute fix for woocommerce 3.0
		if(!get_option('plsh_wc300_attributes_fixes')) {

			global $wpdb;

			$querydetails =
            "SELECT wposts.ID, wpostmeta.meta_key, wpostmeta.meta_value
            FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
            WHERE wposts.ID = wpostmeta.post_id
            AND wpostmeta.meta_key = '_product_attributes'
            AND wposts.post_status = 'publish'
            AND wposts.post_type = 'product'";

			$results = $wpdb->get_results($querydetails, OBJECT);

			if(!empty($results)) {

				foreach($results as $item) {

					$value = maybe_unserialize($item->meta_value);
					if(is_serialized($value)) {
						$value = maybe_unserialize($value);
					}

					update_post_meta($item->ID, '_product_attributes', $value);
				}
			}

			update_option('plsh_wc300_attributes_fixes', 1);
		}
    }

}
