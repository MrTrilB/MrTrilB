<?php

if(!function_exists('goodgame_gs'))
{
    function goodgame_gs($param = NULL, $allow_cache = true)	//get setting
    {
        return GOODGAME_SETTINGS_INSTANCE()->get_single($param, $allow_cache);

        //legacy remove later
        if($param === NULL) return GOODGAME_SETTINGS_INSTANCE()->active;
        if(!empty(GOODGAME_SETTINGS_INSTANCE()->active[$param]) && $allow_cache == true) return GOODGAME_SETTINGS_INSTANCE()->active[$param];
        if(!empty(GOODGAME_SETTINGS_INSTANCE()->$param)) return GOODGAME_SETTINGS_INSTANCE()->$param;
        if(!empty(GOODGAME_SETTINGS_INSTANCE()->hidden[$param])) return GOODGAME_SETTINGS_INSTANCE()->hidden[$param];
        return false;
    }
}

if(!function_exists('goodgame_ss'))
{
    function goodgame_ss($name, $value) //save setting
    {
        GOODGAME_SETTINGS_INSTANCE()->update_single($name, $value);
    }
}

if(!function_exists('goodgame_get_settings_admin_head'))
{
    function goodgame_get_settings_admin_head()
    {
        return GOODGAME_SETTINGS_INSTANCE()->admin_head;
    }
}

if(!function_exists('goodgame_get_settings_admin_body'))
{
    function goodgame_get_settings_admin_body()
    {
        return GOODGAME_SETTINGS_INSTANCE()->admin_body;
    }
}

if(!function_exists('debug'))
{
    function debug($variable, $die=true)
    {
        if ((is_scalar($variable)) || (is_null($variable)))
        {
            if (is_null($variable))
            {
                $output = '<i>NULL</i>';
            }
            elseif (is_bool($variable))
            {
                $output = '<i>' . (($variable) ? 'TRUE' : 'FALSE') . '</i>';
            }
            else
            {
                $output = $variable;
            }
            echo '<pre>variable: ' . $output . '</pre>';
        }
        else // non-scalar
        {
            echo '<pre>';
            print_r($variable);
            echo '</pre>';
        }

        if ($die)
        {
            die();
        }
    }
}


//function perform_remote_request($url, $http_header = array())
//{
//	$curl = curl_init($url);
//
//	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//	if(!empty($http_header))
//	{
//		curl_setopt($curl, CURLOPT_HTTPHEADER, $http_header);
//	}
//
//	$response = curl_exec($curl);
//
//	curl_close($curl);
//
//	return $response;
//}

//function goodgame_get_twitch_stream($username = '')
//{
//	$client_id = trim(goodgame_gs('twitch_client_id'));
//	if(strlen($client_id) == 0 || $username == '')
//	{
//		return false;
//	}
//
//	$version = 'Accept: application/vnd.twitchtv.v3+json';
//	$user = 'https://api.twitch.tv/kraken/streams/' . sanitize_user($username);
//	$client = 'Client-ID: ' . sanitize_key($client_id);
//
//	$response = perform_remote_request($user, array($version, $client));
//
//	if(!$response)
//	{
//		return false;
//	}
//
//	$result = json_decode($response, true);
//
//	if(empty($result) || !empty($result['error']) || empty($result['stream']))
//	{
//		return false;
//	}
//
////	var_dump($result['stream']);
//	return $result['stream'];
//}

//function goodgame_get_twitch_channel($username = '')
//{
//	$client_id = trim(goodgame_gs('twitch_client_id'));
//	if(strlen($client_id) == 0 || $username == '')
//	{
//		return false;
//	}
//
//	$version = 'Accept: application/vnd.twitchtv.v3+json';
//	$user = 'https://api.twitch.tv/kraken/channels/' . sanitize_text_field($username);
//	$client = 'Client-ID: ' . sanitize_key($client_id);
//
//	$response = perform_remote_request($user, array($version, $client));
//
//	if(!$response)
//	{
//		return false;
//	}
//
//	$result = json_decode($response, true);
//
//	if(empty($result) || !empty($result['error']))
//	{
//		return false;
//	}
//
////	var_dump($result);
//	return $result;
//}

if(!function_exists('goodgame_dbSE'))
{
    function goodgame_dbSE($value)
    {
        global $wpdb;
        return $wpdb->_real_escape($value);
    }
}

if(!function_exists('goodgame_get'))
{
    function goodgame_get( $array, $key, $default = NULL )
    {
        if(is_array($array))
        {
            if( !empty( $array[$key] ) )
            {
                return $array[$key];
            }
        }
        return $default;
    }
}

if(!function_exists('goodgame_current_page_url'))
{
    function goodgame_current_page_url()
    {
        $pageURL = 'http';

        if (goodgame_get($_SERVER, "HTTPS") == "on") {$pageURL .= "s";}

        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        }
        else
        {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }


        return $pageURL;
    }
}

if(!function_exists('goodgame_assamble_url'))
{
    function goodgame_assamble_url($pageURL = false, $add_params = array(), $remove_params = array())
    {
        if(!$pageURL)
        {
            $pageURL = goodgame_current_page_url();
        }

        if(!empty($remove_params))
        {
            foreach($remove_params as $remove)
            if(strpos($pageURL, $remove) !== false)
            {
                $parts = explode('?', $pageURL);
                if(count($parts) > 1)
                {
                    $query_parts = explode('&', $parts[1]);
                    foreach($query_parts as $key => $value)
                    {
                        if(strpos($value, $remove) !== false)
                        {
                            unset($query_parts[$key]);
                        }
                    }
                    if(!empty($query_parts))
                    {
                        $parts[1] = implode('&', $query_parts);
                    }
                    else
                    {
                        unset($parts[1]);
                    }
                }

                $pageURL = implode('?', $parts);
            }
        }

        if(!empty($add_params))
        {
            foreach($add_params as $add)
            {
                if(strpos($pageURL, '?') !== false)
                {
                    $pageURL .= '&' . $add;
                }
                else
                {
                    $pageURL .= '?' . $add;
                }
            }
        }

        return $pageURL;
    }
}

if(!function_exists('goodgame_get_post_id_from_slug'))
{
    function goodgame_get_post_id_from_slug( $slug, $post_type = 'post' )
    {
        global $wpdb;

        $query = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = %s", $slug, $post_type );
        $id = $wpdb->get_var( $query );
        if ( ! empty( $id ) ) {
            return $id;
        } else {
            return 0;
        }
    }
}

if(!function_exists('goodgame_get_post_slug_from_id'))
{
    function goodgame_get_post_slug_from_id( $post_id )
    {
        $post = get_post( $post_id );
        if ( isset( $post->post_name ) ) {
            return $post->post_name;
        } else {
            return null;
        }
    }
}

if(!function_exists('goodgame_thumbnail_regenerate_notification'))
{
    function goodgame_thumbnail_regenerate_notification()
    {
        $dismissed = get_option('goodgame_page_thumb_regen_dismissed', false);

        if(!$dismissed)
        {

            $dismiss_link = get_admin_url() . 'admin.php?page=' . goodgame_gs('theme_slug') . '-admin&goodgame_action=dismiss-thumb-regen';

            ?>
            <div class="updated planetshine-auto-page-notification">
                <p>
                    <?php printf( esc_html__('If your blog already has posts with images, please <strong>Install</strong> & <strong>Run</strong> the bundled <strong>Regenerate Thumbnails</strong> plugin! This will ensure faster page load speeds.', 'planetshine-goodgame') ); ?>
                    <a class="planetshine-dismiss" href="<?php echo esc_url($dismiss_link); ?>"><?php esc_html_e('dismiss', 'planetshine-goodgame'); ?></a>
                </p>
            </div>
            <?php
        }
    }
}

if(!function_exists('goodgame_page_install_notification'))
{
    function goodgame_page_install_notification()
    {
        $dismissed = get_option('goodgame_page_install_dismissed', false);

        if(!$dismissed && class_exists('Goodgame_Extension'))
        {
            $pages = goodgame_get_auto_pages();
            $pages_installed = true;
            foreach($pages as &$page)
            {
                if(empty($page['id']) || get_post($page['id']) == NULL) //if page is not created of has been deleted
                {
                    $pages_installed = false;
                }
            }

			//look for demo import
			$demo_imported = false;
			if(class_exists('GoodGame_Demo_Export') && class_exists('GoodGame_Demo_Import'))
			{
				$demo_imported = GoodGame_Demo_Import :: getCurrentImport();
			}

            if(!$pages_installed && $demo_imported == false)
            {
//                $install_link = get_admin_url() . 'admin.php?page=' . goodgame_gs('theme_slug') . '-admin&goodgame_action=install-auto-pages';
				$import_link = get_admin_url() . 'admin.php?page=' . goodgame_gs('theme_slug') . '-admin&view=setup&section=demo_import';
                $import_pages_link = get_admin_url() . 'admin.php?page=' . goodgame_gs('theme_slug') . '-admin&view=setup&section=install_pages';
                $dismiss_link = get_admin_url() . 'admin.php?page=' . goodgame_gs('theme_slug') . '-admin&goodgame_action=dismiss-auto-pages';
                ?>
                <div class="updated planetshine-auto-page-notification">
                    <p>
                        <?php
                            esc_html_e('Click here to', 'planetshine-goodgame');
                            echo ' <a href="' . esc_url($import_link) . '">' . esc_html__('import full theme demo', 'planetshine-goodgame') . '</a>. ';
                            esc_html_e('Or click here to', 'planetshine-goodgame');
                            echo ' <a href="' . esc_url($import_pages_link) . '">' . esc_html__('import homepage and other individual pages', 'planetshine-goodgame') . '</a>';
                        ?>
						<a class="planetshine-dismiss" href="<?php echo esc_url($dismiss_link); ?>"><?php esc_html_e('dismiss', 'planetshine-goodgame'); ?></a>
                    </p>
                </div>
                <?php
            }
        }
    }
}


if(!function_exists('goodgame_db_update_notification'))
{
    function goodgame_db_update_notification()
    {
        $install_link = get_admin_url() . 'admin.php?page=' . goodgame_gs('theme_slug') . '-admin&goodgame_action=planetshine-db-migrate';
        ?>
        <div class="update-nag planetshine-auto-page-notification">
            <p>
                <?php
                    echo esc_html__('Theme needs to update your sites database to ensure full compatibility with the latest version of theme.', 'planetshine-goodgame');
                    echo ' <a href="' . esc_url($install_link) . '">' . esc_html__('Click here to update', 'planetshine-goodgame')  . '</a>';
                ?>
            </p>
        </div>
        <?php
    }
}

if(!function_exists('goodgame_page_install_success_notification'))
{
    function goodgame_page_install_success_notification()
    {
        ?>
        <div class="updated planetshine-auto-page-notification">
            <p>
                <?php esc_html_e('The pages have been installed successfully!', 'planetshine-goodgame'); ?>
            </p>
        </div>
        <?php
    }
}

if(!function_exists('goodgame_add_auto_pages'))
{
    function goodgame_add_auto_pages()
    {
        $pages = goodgame_get_auto_pages();

        foreach($pages as &$page)
        {
            if(empty($page['id']) || get_post($page['id']) == NULL) //if page is not created of has been deleted
            {
                $page['id'] = goodgame_create_page($page);

                //set up frontpage & blog page
                if($page['role'] == 'front_page')
                {
                    update_option( 'page_on_front', $page['id'] );
                    update_option( 'show_on_front', 'page' );
                }
                if($page['role'] == 'posts')
                {
                    update_option( 'page_for_posts', $page['id'] );
                }

                if(!empty($page['template']))
                {
                    update_post_meta( $page['id'], '_wp_page_template', $page['template'] );
                }
            }
        }

        update_option('goodgame_auto_pages', json_encode($pages));
    }
}

if(!function_exists('goodgame_get_auto_pages'))
{
    function goodgame_get_auto_pages()
    {
        $default_pages = goodgame_gs('auto_pages');
        $pages = get_option('goodgame_auto_pages', json_encode($default_pages));
        return json_decode($pages, true);
    }
}

if(!function_exists('goodgame_create_page'))
{
    function goodgame_create_page($page)
    {
        $page_data = array(
            'post_status' 		=> 'publish',
            'post_type' 		=> 'page',
            'post_author' 		=> 1,
            'post_name' 		=> esc_sql( $page['slug'] ),
            'post_title' 		=> $page['name'],
            'post_content' 		=> $page['content'],
            'post_parent' 		=> 0,
            'comment_status' 	=> 'closed'
        );

        $page_id = wp_insert_post( $page_data );
        if($page['role'] == 'front_page')
        {
            update_post_meta( $page_id, '_wp_page_template', 'page-home.php' );
        }
        return $page_id;
    }
}

if(!function_exists('goodgame_is_shop_installed'))
{
    function goodgame_is_shop_installed()
    {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');

        if ( class_exists( 'WooCommerce' ) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

if(!function_exists('goodgame_is_woocommerce_active'))
{
    function goodgame_is_woocommerce_active()
    {
        if ( goodgame_is_shop_installed())
        {
            return true;
        }
        return false;
    }
}

if(!function_exists('goodgame_not_woocommerce_special_content'))
{
    function goodgame_not_woocommerce_special_content()
    {
        if(goodgame_is_woocommerce_active())
        {
            if( is_cart() || is_checkout() )
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        return true;
    }
}

if(!function_exists('goodgame_dump_included_files'))
{
    function goodgame_dump_included_files()
    {
        $included_files = get_included_files();
        $stylesheet_dir = str_replace( '\\', '/', get_stylesheet_directory() );
        $template_dir   = str_replace( '\\', '/', get_template_directory() );

        foreach ( $included_files as $key => $path ) {

            $path   = str_replace( '\\', '/', $path );

            if ( false === strpos( $path, $stylesheet_dir ) && false === strpos( $path, $template_dir ) )
                unset( $included_files[$key] );
        }

        debug( $included_files );
    }
}

if(!function_exists('goodgame_get_posts_by_type'))
{
    function goodgame_get_posts_by_type($post_type = 'post', $count = 8)
    {
        global $wpdb;

        if(function_exists('icl_get_languages')) //if wpml
        {
            $querydetails = $wpdb->prepare("
                SELECT wposts.*
                FROM $wpdb->posts as wposts
                LEFT JOIN ". $wpdb->base_prefix ."icl_translations
                ON wposts.ID = ". $wpdb->base_prefix ."icl_translations.element_id
                WHERE
                wposts.post_status = 'publish'
                AND wposts.post_type = %s
                AND ". $wpdb->base_prefix ."icl_translations.language_code = %s
                ORDER BY wposts.post_date DESC
                LIMIT 0, %d
            ",
            goodgame_dbSE($post_type),
            ICL_LANGUAGE_CODE,
            $count);
        }
        else
        {
            $querydetails = $wpdb->prepare("
                SELECT wposts.*
                FROM $wpdb->posts wposts
                WHERE
                wposts.post_status = 'publish'
                AND wposts.post_type = %s
                ORDER BY wposts.post_date DESC
                LIMIT 0, %d",
                goodgame_dbSE($post_type),
                $count
                );
        }

        return $wpdb->get_results($querydetails, OBJECT);
    }
}

if(!function_exists('goodgame_get_posts_by_platform'))
{
    function goodgame_get_posts_by_platform($platform_id = 'all', $interval = 'all', $count = 5)
    {
		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $count,
			'meta_key' => 'rating_stars',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'meta_query'     => array(
				'relation'  => 'AND',
				array (
				   'key'     => 'rating_stars',
				   'value'   => 'disabled',
				   'compare' => '!=',
				)
			)
		);

		if($interval != 'all')
		{
			$interval = explode('-', $interval);
			$time_amount = intval($interval[0]);
			$time_period = (isset($interval[1])) ? $interval[1] : '';
			if($time_amount > 0 && ($time_period == 'month' || $time_period == 'week' || $time_period == 'year' || $time_period == 'day'))
			{
				$after_value = $time_amount . ' ' . $time_period . ' ago';

				$args['date_query'] = array(
					array(
						'column' => 'post_date_gmt',
						'after'    => $after_value,
					)
				);
			}
		}

		if($platform_id != 'all' && intval($platform_id) > 0)
		{
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'platform',
					'field'    => 'term_id',
					'terms'    => intval($platform_id),
				)
			);
		}
		else
		{
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'platform',
					'operator' => 'EXISTS'
				)
			);
		}

		$posts = new WP_Query($args);

		return $posts->posts;
	}
}

if(!function_exists('goodgame_get_posts_by_meta'))
{
    function goodgame_get_posts_by_meta($key, $value, $count, $page=1, $post_type = 'post')
    {
        global $wpdb;
        $limit = ($page-1) * $count;

		$q = "
            SELECT wposts.*
            FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
            WHERE wposts.ID = wpostmeta.post_id
            AND wpostmeta.meta_key = %s";

		if(is_array($value))
		{
			$q .= "AND wpostmeta.meta_value IN (" . implode(', ', array_fill(0, count($value), '%s')) . ")";
		}
		else
		{
			$q .= "AND wpostmeta.meta_value = %s";
			$value = array($value);
		}

        $q .= "
            AND wposts.post_status = 'publish'
            AND wposts.post_type = %s
            ORDER BY wposts.post_date DESC
            LIMIT %d, %d";

		$querydetails = call_user_func_array(array($wpdb, 'prepare'), array_merge(array($q), array(goodgame_dbSE($key)), $value, array(goodgame_dbSE($post_type)), array($limit), array($count)) );

        return $wpdb->get_results($querydetails, OBJECT);
    }
}

if(!function_exists('goodgame_get_post_count_by_meta'))
{
    function goodgame_get_post_count_by_meta($key, $value, $post_type = 'post')
    {
        global $wpdb;

        $querydetails = $wpdb->prepare("
            SELECT COUNT(*) as count
            FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
            WHERE wposts.ID = wpostmeta.post_id
            AND wpostmeta.meta_key = %s
            AND wpostmeta.meta_value = %s
            AND wposts.post_status = 'publish'
            AND wposts.post_type = %s",
            goodgame_dbSE($key),
            goodgame_dbSE($value),
            goodgame_dbSE($post_type)
        );

        $values = $wpdb->get_results($querydetails, ARRAY_A);
        if(!empty($values))
        {
            return $values[0]['count'];
        }
        return 0;
    }
}

if(!function_exists('goodgame_get_post_collection'))
{
    function goodgame_get_post_collection($params = array(), $count = NULL, $page=1, $platform = '', $orderby = 'date', $dir = 'DESC', $type='post')
    {
        $args = array();
        if(!empty($params))
        {
            foreach($params as $key => $value)
            {
				if($value != NULL) { $args[$key] = $value; }
            }
        }

        $args['orderby'] = $orderby;
        $args['order'] = $dir;
        $args['post_status'] = 'publish';
        $args['ignore_sticky_posts'] = 1;
        $args['paged'] = $page;
        $args['post_type'] = $type;
		if($platform != '')
		{
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'platform',
					'field'    => 'slug',
					'terms'    => $platform,
				)
			);
		}
		if($count) { $args['posts_per_page'] = $count; }
        $posts = new WP_Query($args);

        return $posts->posts;
    }
}

if(!function_exists('goodgame_get_taxonomy_hierarchy'))
{
    function goodgame_get_taxonomy_hierarchy($taxonomy, $parent_id = 0)
    {
        $args = array(
            'type'                     => 'post',
            'parent'                   => $parent_id,
            'orderby'                  => 'name',
            'order'                    => 'ASC',
            'hide_empty'               => 1,
            //'hierarchical'             => 1,
            'taxonomy'                 => $taxonomy,
            'pad_counts'               => false
        );
        $categories = get_categories( $args );

        foreach($categories as $key => $value)
        {
            $categories[$key]->children = goodgame_get_taxonomy_hierarchy($taxonomy, $value->term_id);
        }

        return $categories;
    }
}

if(!function_exists('goodgame_get_posts_with_latest_comments'))
{
    function goodgame_get_posts_with_latest_comments($count, $page=1, $type='post')
    {
        global $wpdb;
        $limit = ($page-1) * $count;

        $querydetails = $wpdb->prepare("
            select wp_posts.*,
            coalesce(
                (
                    select max(comment_date)
                    from $wpdb->comments wpc
                    where wpc.comment_post_id = wp_posts.id
                ),
                wp_posts.post_date
            ) as mcomment_date
            from $wpdb->posts wp_posts
            where post_type = %s
            and post_status = 'publish'
            and comment_count > 0
            order by mcomment_date desc
            limit %d, %d",
            $type,
            $limit,
            $count
            );

        return $wpdb->get_results($querydetails, OBJECT);
    }
}

if(!function_exists('goodgame_get_posts_with_comments_count'))
{
    function goodgame_get_posts_with_comments_count($type='post')
    {
        global $wpdb;

        $querydetails = $wpdb->prepare("
            select COUNT(*) as count
            from $wpdb->posts wp_posts
            where post_type = %s
            and post_status = 'publish'
            and comment_count > 0",
            $type
        );

        $values = $wpdb->get_results($querydetails, ARRAY_A);
        if(!empty($values))
        {
            return $values[0]['count'];
        }
        return 0;
    }
}

if(!function_exists('goodgame_generate_css'))
{
    function goodgame_generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true )
    {
        $return = '';
        $default = goodgame_gs($mod_name);
        $mod = get_theme_mod($mod_name, $default);
        if ( ! empty( $mod ) )
        {
            $mod = str_replace('#', '', $mod);
            $mod = str_replace('+', ' ', $mod);
            $return = sprintf('%s { %s:%s; }',
               $selector,
               $style,
               $prefix.$mod.$postfix
            );
            if ( $echo )
            {
               echo $return . "\n";
            }
        }
        return $return;
    }
}

if(!function_exists('goodgame_map_visual_settings_to_less'))
{
	function goodgame_map_visual_settings_to_less($vars, $handle)
	{
		$body = GOODGAME_SETTINGS_INSTANCE()->admin_body;
		$keys = array_merge(
			array_keys($body['visual_editor']['visual_colors']),
			array_keys($body['visual_editor']['visual_background']),
			array_keys($body['visual_editor']['visual_header']),
			array_keys($body['visual_editor']['visual_footer']),
			array_keys($body['visual_editor']['visual_fonts'])
		);

		foreach($keys as $key)
		{
			$default = goodgame_gs($key);
			$mod = get_theme_mod($key, $default);

			if ( ! empty( $mod ) )
			{
				$vars[$key] = str_replace('+', ' ', $mod);
			}
            else
            {
                $default = str_replace('+', ' ', goodgame_gs($key, false));
                if($default)
                {
                    $vars[$key] = $default;
                }
            }
		}

		return $vars;
	}
}

/* Add additional params to wp_get_archives thus enabling filter by year functionality */
if(!function_exists('goodgame_archive_where'))
{
    function goodgame_archive_where($where,$args){
        $year = isset($args['year']) ? $args['year'] : '';
        $month = isset($args['month']) ? $args['month'] : '';

        if($year){
        $where .= " AND YEAR(post_date) = '$year' ";
        $where .= $month ? " AND MONTH(post_date) = '$month' " : '';
        }
        if($month){
        $where .= " AND MONTH(post_date) = '$month' ";
        }

        return $where;
    }
}

if(!function_exists('goodgame_is_blog'))
{
    function goodgame_is_blog()
    {
        if ( is_front_page() && is_home() )
        {
            return false;
        }
        elseif ( is_front_page() )
        {
            return false;
        }
        elseif ( is_home() ) {
            return true;
        } else {
            return false;
        }
    }
}

if(!function_exists('goodgame_log_theme_version'))
{
    function goodgame_log_theme_version()
    {
        $theme = wp_get_theme();
        $version = $theme->get('Version');

        $curr_version = get_option('goodgame_current_' . GOODGAME_THEME_DOMAIN .'_version', '0');

		if($version != $curr_version)
		{
			update_option('goodgame_previous_' . GOODGAME_THEME_DOMAIN .'_version', $curr_version);
			update_option('goodgame_current_' . GOODGAME_THEME_DOMAIN .'_version', $version);
		}
    }
}

if(!function_exists('goodgame_redirect_to_status'))
{
    function goodgame_redirect_to_status()
    {

		if ( is_admin() && isset( $_GET['activated'] ) ) {
			$url = get_admin_url() . 'admin.php?page=' . goodgame_gs('theme_slug') . '-admin' . '&view=setup';
			wp_redirect($url);
			exit;
		}
    }
}

//replacement function to allow to use shortcodes without VC
if(!function_exists('vc_map'))
{
    function vc_build_link($url)
    {
        return array('url' => $url);
    }
}

//wp filesysytem based put contents
if(!function_exists('goodgame_wp_file_put_contents'))
{
	function goodgame_wp_file_put_contents($path, $file_contents) {

		global $wp_filesystem;
		// Initialize the WP filesystem, no more using 'file-put-contents' function
		if (empty($wp_filesystem)) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}

		if(!$wp_filesystem->put_contents( $path, $file_contents, 0644) ) {
			return esc_html__('Failed to put file', 'planetshine-goodgame');
		}

	}
}

//wp filesystem based get contents
if(!function_exists('goodgame_wp_file_get_contents'))
{
	function goodgame_wp_file_get_contents($path) {

		global $wp_filesystem;
		// Initialize the WP filesystem, no more using 'file-get-contents' function
		if (empty($wp_filesystem)) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
			WP_Filesystem();
		}

		return $wp_filesystem->get_contents($path);

	}
}

if(!function_exists('goodgame_get_bundled_plugin_version'))
{
	function goodgame_get_bundled_plugin_version($slug = '')
	{
		global $plsh_bundled_versions;

		if(!empty($plsh_bundled_versions[$slug]))
		{
			return $plsh_bundled_versions[$slug];
		}

		return false;
	}
}
?>
