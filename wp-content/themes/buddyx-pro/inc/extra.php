<?php
/**
 * The `buddyxpro()` extra.
 *
 * @package buddyxpro
 */

// Content wrapper
if ( ! function_exists( 'buddyx_content_top' ) ) {

    function buddyx_content_top() { ?>
        <?php if ( class_exists( 'BuddyPress' ) ) { ?>
            <?php if ( ! bp_is_user() && ! bp_is_group_single() ) : ?>
                <div class="container">
                    <div class="site-wrapper">
            <?php endif; ?>
                <?php } else { ?>
                    <div class="container">
                        <div class="site-wrapper">
            <?php
        }
    }

}

add_action( 'buddyx_before_content', 'buddyx_content_top' );

                if ( ! function_exists( 'buddyx_content_bottom' ) ) {

                    function buddyx_content_bottom() {
                        ?>
                        <?php if ( class_exists( 'BuddyPress' ) ) { ?>
                            <?php if ( ! bp_is_user() && ! bp_is_group_single() ) : ?>
                            </div></div>
                    <?php endif; ?>
                <?php } else { ?>
                </div></div>
            <?php
        }
    }

}

add_action( 'buddyx_after_content', 'buddyx_content_bottom' );

// Site Sub Header
if ( ! function_exists( 'buddyx_sub_header' ) ) {
    add_action( 'buddyx_sub_header', 'buddyx_sub_header' );

    function buddyx_sub_header() {
        global $post;
        if ( is_front_page() ) {
            return;
        }
        ?>
        <div class="site-sub-header">
            <div class="container">
                <?php
                if ( get_post_type() === 'post' || is_single() || is_archive( 'post-type-archive-forum' ) && ( function_exists( 'is_shop' ) && ! is_shop() ) ) {
                    get_template_part('template-parts/content/page_header');
                    $breadcrumbs = get_theme_mod('site_breadcrumbs', buddyx_defaults('site-breadcrumbs'));
                    if ( ! empty( $breadcrumbs ) ) {
                        buddyx_the_breadcrumb();
                    }
                } elseif (get_post_type() === 'page' || is_singular()) {
                    // PAGE
                    get_template_part('template-parts/content/entry_title', get_post_type());
                    $breadcrumbs = get_theme_mod('site_breadcrumbs', buddyx_defaults('site-breadcrumbs'));
                    if (!empty($breadcrumbs)) {
                        buddyx_the_breadcrumb();
                    }
                }

                if (class_exists('WooCommerce')) {
                    if (is_shop()) {
                        ?>
                        <header class="woocommerce-products-header page-header">
                            <?php if (apply_filters('woocommerce_show_page_title', true)) { ?>
                                <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
                                <?php
                            }
                            /*
                             * Hook: woocommerce_archive_description.
                             *
                             * @hooked woocommerce_taxonomy_archive_description - 10
                             * @hooked woocommerce_product_archive_description - 10
                             */
                            do_action('woocommerce_archive_description');
                            ?>
                        </header>
                        <?php 
                            $breadcrumbs = get_theme_mod('site_breadcrumbs', buddyx_defaults('site-breadcrumbs'));
                            if (!empty($breadcrumbs)) {
                                buddyx_the_breadcrumb();
                            }
                        ?>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }

}

// Site Loader
function buddyx_site_loader() {
    $loader = get_theme_mod( 'site_loader', buddyx_defaults( 'site-loader' ) );
    $site_loader_text = get_theme_mod( 'site_loader_text', buddyx_defaults( 'site-loader-text' ) );
    if ( $loader == '1' ) {
        echo '<div class="site-loader"><div class="loader-inner"><div class="loader-text">'. esc_html( $site_loader_text ) .'</div><span class="dot"></span><span class="dot dot1"></span><span class="dot dot2"></span><span class="dot dot3"></span><span class="dot dot4"></span></div></div>';
    }
}

// Site Search and Woo icon
if (!function_exists('buddyx_site_menu_icon')) {

    function buddyx_site_menu_icon() {
        // menu icons
        $searchicon = (int) get_theme_mod('site_search', buddyx_defaults('site-search'));
        $carticon = (int) get_theme_mod('site_cart', buddyx_defaults('site-cart'));
        if (!empty($searchicon) || !empty($carticon)) : ?>
			<div class="menu-icons-wrapper"><?php
                if (!empty($searchicon)): ?>
					<div class="search search-menu-dropdown" <?php echo apply_filters( 'buddyx_search_slide_toggle_data_attrs', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<a href="#" id="overlay-search" class="search-icon search-menu-dropdown-toggle"> <span class="fa fa-search"> </span> </a>
						<div class="top-menu-search-container search-menu-dropdown-menu" <?php echo apply_filters( 'buddyx_search_field_toggle_data_attrs', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php get_search_form(); ?>
						</div>
					</div>
					<?php
                endif;
                if (!empty($carticon) && function_exists('is_woocommerce')) :
                    ?>
                    <div class="cart cart-widget-opener">
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View Shopping Cart', 'buddyxpro'); ?>">
                            <span class="fas fa-shopping-cart"> </span><?php
                            $count = WC()->cart->cart_contents_count;
                            if ( $count > 0 ) { ?>
								<sup><?php echo esc_html($count) ?></sup><?php
							}
                            ?>
                        </a>
                    </div><?php endif;
                        ?>
            </div><?php
        endif;
    }

}

// buddyx_bp_get_activity_css_first_class
if ( ! function_exists( 'buddyx_bp_get_activity_css_first_class' ) ) {
	function buddyx_bp_get_activity_css_first_class() {
		global $activities_template;
		/**
		 * Filters the available mini activity actions available as CSS classes.
		 *
		 * @since 1.2.0
		 *
		 * @param array $value Array of classes used to determine classes applied to HTML element.
		 */
		$mini_activity_actions = '';

		switch ( $activities_template->activity->component ) {
			case 'xprofile':
				$mini_activity_actions = __( 'Profile', 'buddyxpro' );
				break;
			case 'activity':
				$mini_activity_actions = __( 'Activity', 'buddyxpro' );
				break;
			case 'groups':
				$mini_activity_actions = __( 'Groups', 'buddyxpro' );
				break;
			case 'bbpress':
				$mini_activity_actions = __( 'Forums', 'buddyxpro' );
				break;
			case 'friends':
				$mini_activity_actions = __( 'Friends', 'buddyxpro' );
				break;
			case 'members':
				$mini_activity_actions = __( 'Members', 'buddyxpro' );
				break;
			case 'blogs':
				$mini_activity_actions = __( 'Blogs', 'buddyxpro' );
				break;
				
			default:			
				$mini_activity_actions = __( 'Activity', 'buddyxpro' );
				break;
		}
		
		return apply_filters( 'buddyx_bp_get_activity_css_first_class', $mini_activity_actions, $activities_template->activity->component );
	}
}

/*
 * Is the current user online
 *
 * @param $user_id
 *
 * @return bool
 */
if (!function_exists('buddyx_is_user_online')) {

    function buddyx_is_user_online($user_id) {
        if (!function_exists('bp_get_user_last_activity')) {
            return;
        }

        $last_activity = strtotime(bp_get_user_last_activity($user_id));

        if (empty($last_activity)) {
            return false;
        }

        // the activity timeframe is 5 minutes
        $activity_timeframe = 5 * MINUTE_IN_SECONDS;

        return time() - $last_activity <= $activity_timeframe;
    }

}

/*
 * BuddyPress user status
 *
 * @param $user_id
 *
 */
if (!function_exists('buddyx_user_status')) {

    function buddyx_user_status($user_id) {
        if (buddyx_is_user_online($user_id)) {
            echo '<span class="member-status online"></span>';
        }
    }

}

/*
 * woocommerce_cart_collaterals
 */
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart_form', 'woocommerce_cross_sell_display', 10);

/**
 * buddyx_disable_woo_commerce_sidebar.
 */
function buddyx_disable_woo_commerce_sidebar() {
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
}

add_action('init', 'buddyx_disable_woo_commerce_sidebar');

/*
 * BREADCRUMBS 
 */
//  to include in functions.php
if ( ! function_exists( 'buddyx_the_breadcrumb' ) ) {
    function buddyx_the_breadcrumb() {
        $wpseo_titles = get_option( 'wpseo_titles' );
        if (function_exists( 'yoast_breadcrumb' ) && isset( $wpseo_titles['breadcrumbs-enable'] ) && $wpseo_titles['breadcrumbs-enable'] == 1 ) {
            yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
        } else {
            echo '<div class="buddyx-breadcrumbs">';
                buddyx_get_breadcrumb();
            echo '</div>';
        }
    }
}

/*
 * showing member cover image on member directory page
 */
if (!function_exists('buddyx_render_member_cover_image')) {
    $buddypress_memberes_directory_view = get_theme_mod( 'buddypress_memberes_directory_view', 'card' );
    if ( $buddypress_memberes_directory_view === 'card' ) {
        add_action('buddyx_before_member_avatar_member_directory', 'buddyx_render_member_cover_image', 10);
    }

    function buddyx_render_member_cover_image() {
        $cover_img_url = bp_attachments_get_attachment('url', $args = [
            'object_dir' => 'members',
            'item_id' => $user_id = bp_get_member_user_id(),
            'type' => 'cover-image',
        ]);
        $default_members_cover = get_theme_mod('buddypress_memberes_directory_cover');
        $cover_img_url = $cover_img_url ?: $default_members_cover;
        echo '<div class="buddyx-mem-cover-wrapper"><div class="buddyx-mem-cover-img"><img src="' . $cover_img_url . '" /></div></div>';
    }

}

/*
 * showing group cover image on groups directory page
 */
if (!function_exists('buddyx_render_group_cover_image')) {
    $buddypress_groups_directory_view = get_theme_mod( 'buddypress_groups_directory_view', 'card' );
    if ( $buddypress_groups_directory_view === 'card' ) {
        add_action('buddyx_before_group_avatar_group_directory', 'buddyx_render_group_cover_image', 10);
    }

    function buddyx_render_group_cover_image() {
        $cover_img_url = bp_attachments_get_attachment('url', $args = [
            'object_dir' => 'groups',
            'item_id' => $group_id = bp_get_group_id(),
            'type' => 'cover-image',
        ]);
        $default_groups_cover = get_theme_mod('buddypress_groups_directory_cover');
        $cover_img_url = $cover_img_url ?: $default_groups_cover;
        echo '<div class="buddyx-grp-cover-wrapper"><div class="buddyx-grp-cover-img"><img src="' . $cover_img_url . '" /></div></div>';
    }

}

/*
 *
 * WooCommerce Cart Side Widget
 *
 * @since 1.0.0
 * @version 1.0.0
 */

/* Ensure cart contents update when products are added to the cart via AJAX */
add_filter('woocommerce_add_to_cart_fragments', 'buddyx_header_add_to_cart_fragment');

if (!function_exists('buddyx_header_add_to_cart_fragment')) {

    function buddyx_header_add_to_cart_fragment($fragments) {
        $count = WC()->cart->get_cart_contents_count();
        ob_start();
        ?>
        <a class="menu-icons-wrapper cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php _e('View your shopping cart', 'buddyxpro'); ?>">
            <span class="fas fa-shopping-cart"></span>
            <?php
			if ( $count > 0 ) { ?>
				<sup><?php echo esc_html($count) ?></sup><?php
			}
			?>
        </a>
        <?php
        $fragments['.menu-icons-wrapper .cart a'] = ob_get_clean();

        return $fragments;
    }

}

if (!function_exists('buddyx_cart_widget_side')) {

    function buddyx_cart_widget_side() {
        ?>
        <div class="buddyx-cart-widget-side">
            <div class="widget-heading">
                <h3 class="widget-title"><?php esc_html_e( 'Shopping cart' ); ?></h3>
                <a href="#" class="widget-close"><?php esc_html_e( 'close' ); ?></a>
            </div>
            <div class="buddyx-module-woominicart">
                <div class="woocommerce">
                    <div class="buddyx-mini-cart">
                        <?php woocommerce_mini_cart(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}

if (!function_exists('buddyx_woocommerce_top_bar_ajax')) {

    function buddyx_woocommerce_top_bar_ajax($fragments) {
        global $woocommerce;

        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_clean();

        $fragments['.buddyx-mini-cart'] = '<div class="buddyx-mini-cart">' . $mini_cart . '</div>';
        $fragments['.buddyx-cart-count'] = '<span class="buddyx-cart-count">' . $woocommerce->cart->cart_contents_count . '</span>';
        $fragments['.buddyx-cart-contents'] = '<span class="buddyx-cart-contents">' . sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'buddyxpro'), $woocommerce->cart->cart_contents_count) . ' - ' . $woocommerce->cart->get_cart_total() . '</span>';

        return $fragments;
    }

    add_filter('woocommerce_add_to_cart_fragments', 'buddyx_woocommerce_top_bar_ajax');
}

/*
 * Top Bar
 */

if (!function_exists('buddyx_topbar_content')) {

    function buddyx_topbar_content() {
        ?>
        <div id="top-bar" class="top-bar">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 topbar-content-left">
                        <?php echo get_theme_mod('site_topbar_left_area', buddyx_defaults('site-topbar-left-area')); ?>
                    </div>
                    <div class="col-sm-6 topbar-content-right">
                        <?php
                        $social_links = get_theme_mod('topbar_social_links', buddyx_topbar_default_social_links());
                        if (!empty($social_links) && is_array($social_links)) {
                            foreach ($social_links as $social_link) {
                                echo '<a href="' . $social_link['link_url'] . '" title="' . $social_link['link_text'] . '">' . $social_link['link_icon'] . '</a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}

if (!function_exists('buddyx_topbar_default_social_links')) {

    function buddyx_topbar_default_social_links() {
        $topbar_default_social_links = [
            [
                'link_text' => esc_attr__('Facebook', 'buddyxpro'),
                'link_icon' => '<i class="fab fa-facebook-f"></i>',
                'link_url' => '#',
            ],
            [
                'link_text' => esc_attr__('Twitter', 'buddyxpro'),
                'link_icon' => '<i class="fab fa-twitter"></i>',
                'link_url' => '#',
            ],
            [
                'link_text' => esc_attr__('LinkedIn', 'buddyxpro'),
                'link_icon' => '<i class="fab fa-linkedin-in"></i>',
                'link_url' => '#',
            ],
            [
                'link_text' => esc_attr__('Dribbble', 'buddyxpro'),
                'link_icon' => '<i class="fab fa-dribbble"></i>',
                'link_url' => '#',
            ],
            [
                'link_text' => esc_attr__('Github', 'buddyxpro'),
                'link_icon' => '<i class="fab fa-github"></i>',
                'link_url' => '#',
            ],
        ];
        $topbar_default_social_links = apply_filters('topbar_default_social_links', $topbar_default_social_links);

        return $topbar_default_social_links;
    }

}

/**
 * Function Footer Custom Text
 */
if ( ! function_exists( 'buddyx_footer_custom_text' ) ) {
    /**
     * Function Footer Custom Text
     *
     * @since 1.0.14
     * @param string $option Custom text option name.
     * @return mixed         Markup of custom text option.
     */
    function buddyx_footer_custom_text() {
        
        $copyright = get_theme_mod( 'site_copyright_text' );
        if ( ! empty( $copyright ) ) {
            $copyright = str_replace( '[current_year]', date_i18n( 'Y' ), $copyright );
            $copyright = str_replace( '[site_title]', '<span class="buddyx-footer-site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></span>', $copyright );
            $theme_author = apply_filters(
                    'buddyx_theme_author',
                    array(
                            'theme_name'       => esc_html( 'Wbcom Designs', 'buddyxpro' ),
                            'theme_author_url' => esc_url( 'https://wbcomdesigns.com' ),
                    )
            );
            $copyright = str_replace( '[theme_author]', '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '" target="_blank">' . esc_html( $theme_author['theme_name'] ) . '</a>', $copyright );
            return apply_filters( 'buddyx_footer_copyright_text', $copyright );
        }else {
            $output = 'Copyright © [current_year] [site_title] | Powered by [theme_author]';
            $output = str_replace( '[current_year]', date_i18n( 'Y' ), $output );
            $output = str_replace( '[site_title]', '<span class="buddyx-footer-site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></span>', $output );
            $theme_author = apply_filters(
                    'buddyx_theme_author',
                    array(
                            'theme_name'       => esc_html( 'Wbcom Designs', 'buddyxpro' ),
                            'theme_author_url' => esc_url( 'https://wbcomdesigns.com' ),
                    )
            );
            $output = str_replace( '[theme_author]', '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '" target="_blank">' . esc_html( $theme_author['theme_name'] ) . '</a>', $output );
            return apply_filters( 'buddyx_footer_copyright_text', $output );
        }
        
    }
}

/*
 * Output badges on profile
 */
if ( !function_exists( 'buddyx_profile_achievements' ) ) {
	function buddyx_profile_achievements() {
        if ( class_exists( 'BadgeOS' ) ) {
            global $blog_id, $post;
            $type = "all";
            $limit = apply_filters( 'buddyx_user_badges_limit', 10 );
            $offset = 0;
            $count = 0;
            $filter = "completed";
            $search = false;
            $orderby = "menu_order";
            $order = "ASC";
            $wpms = false;
            $include = array();
            $exclude = array();
            $meta_key = '';
            $meta_value = '';
            $old_post = $post;
            $user_id = bp_displayed_user_id();
            // Convert $type to properly support multiple achievement types
            if ( 'all' == $type ) {
                $type = badgeos_get_achievement_types_slugs();
                // Drop steps from our list of "all" achievements
                $step_key = array_search( 'step', $type );
                if  ($step_key )
                    unset($type[$step_key]);
            } else {
                $type = explode( ',', $type );
            }
            // Build $include array
            if ( ! is_array( $include ) ) {
                $include = explode( ',', $include );
            }
            // Build $exclude array
            if ( ! is_array( $exclude ) ) {
                $exclude = explode( ',', $exclude );
            }
            // Initialize our output and counters
            $achievements = '';
            $achievement_count = 0;
            $query_count = 0;
            // Grab our hidden badges (used to filter the query)
            $hidden = badgeos_get_hidden_achievement_ids( $type );
            // If we're polling all sites, grab an array of site IDs
            if ( $wpms && $wpms != 'false' ) {
                $sites = badgeos_get_network_site_ids();
            } else {
                // Otherwise, use only the current site
                $sites = array( $blog_id );
            }
            // Loop through each site (default is current site only)
            foreach ( $sites as $site_blog_id ) {
                // If we're not polling the current site, switch to the site we're polling
                if ( $blog_id != $site_blog_id ) {
                    switch_to_blog( $site_blog_id );
                }
                // Grab our earned badges (used to filter the query)
                $earned_ids = badgeos_get_user_earned_achievement_ids( $user_id, $type );
                // Query Achievements
                $args = array(
                    'post_type' => $type,
                    'orderby' => $orderby,
                    'order' => $order,
                    'posts_per_page' => $limit,
                    'offset' => $offset,
                    'post_status' => 'publish',
                    'post__not_in' => array_diff( $hidden, $earned_ids )
                );
                // Filter - query completed or non completed achievements
                if ( $filter == 'completed' ) {
                    $args['post__in'] = array_merge( array(0), $earned_ids );
                } elseif ($filter == 'not-completed') {
                    $args['post__not_in'] = array_merge( $hidden, $earned_ids );
                }
                if ( '' !== $meta_key && '' !== $meta_value ) {
                    $args['meta_key'] = $meta_key;
                    $args['meta_value'] = $meta_value;
                }
                // Include certain achievements
                if ( ! empty( $include ) ) {
                    $args['post__not_in'] = array_diff( $args['post__not_in'], $include );
                    $args['post__in'] = array_merge( array(0), array_diff( $include, $args['post__in'] ) );
                }
                // Exclude certain achievements
                if ( ! empty( $exclude ) ) {
                    $args['post__not_in'] = array_merge( $args['post__not_in'], $exclude );
                }
                // Search
                if ( $search ) {
                    $args['s'] = $search;
                }
                // Loop Achievements
                $achievement_posts = new WP_Query( $args );
                $query_count += $achievement_posts->found_posts;
                while ( $achievement_posts->have_posts() ) : $achievement_posts->the_post();
                    // If we were given an ID, get the post
                    if ( is_numeric( get_the_ID() ) ) {
                        $achievement = get_post( get_the_ID() );
                    }
                    $achievements .= '<div class="ps-badgeos__item ps-badgeos__item--focus" >';
                    $achievements .= '<a href="' . get_permalink( $achievement->ID ) . '">' . badgeos_get_achievement_post_thumbnail( $achievement->ID ) . '</a>';
                    $achievements .= '</div>';
                    $achievement_count++;
                endwhile;
                wp_reset_query();
                $post = $old_post;
            }
            echo '<div class="ps-badgeos__list-wrapper">';
            echo '<div class="ps-badgeos__list-title">' . _n( 'Recently earned badge', 'Recently earned badges', $achievement_count, 'buddyxpro' ) . '</div>';
            echo '<div class="ps-badgeos__list">' . $achievements . '</div>';
            echo '</div>';
        }
    }
}

/**
 * Enqueue a admin script in the WordPress admin.
 *
 * @param int $hook Hook suffix for the current admin page.
 */
function buddyx_enqueue_admin_script( $hook ) {
    global $post;

    if ( isset($post->post_type) && $post->post_type == 'sfwd-courses' ) {

        wp_enqueue_script( 'buddyx-admin-script', get_theme_file_uri( '/assets/js/buddyx-admin.min.js' ), array(), '1.0' );
    }
}
add_action( 'admin_enqueue_scripts', 'buddyx_enqueue_admin_script' );

/**
 * Managing Login and Register URL in Frontend
 * 
 */
add_filter( 'login_url', 'buddyx_alter_login_url_at_frontend', 10, 3 );

function buddyx_alter_login_url_at_frontend( $login_url, $redirect, $force_reauth ) {
    if ( is_admin() ) {
        return $login_url;
    }

    $buddyx_login_page_id = get_theme_mod( 'buddyx_login_page', '0' );
    if ($buddyx_login_page_id) {
        $buddyx_login_page_url = get_permalink( $buddyx_login_page_id );
        if ( $buddyx_login_page_url ) {
            $login_url = $buddyx_login_page_url;
        }
    }
    return $login_url;
}

add_filter( 'register_url', 'buddyx_alter_register_url_at_frontend', 10, 1 );

function buddyx_alter_register_url_at_frontend( $register_url ) {
    if ( is_admin() ) {
        return $register_url;
    }

    $buddyx_registration_page_id = get_theme_mod( 'buddyx_registration_page', '0' );
    if ( $buddyx_registration_page_id ) {
        $buddyx_registration_page_url = get_permalink( $buddyx_registration_page_id );
        if ( $buddyx_registration_page_url ) {
            $register_url = $buddyx_registration_page_url;
        }
    }
    return $register_url;
}

/**
 * Redirect to selected login page from options.
 */
function buddyx_redirect_login_page() {

    /* removing conflict with logout url */
    if ( isset($_GET['action']) && ( $_GET['action'] == 'logout' ) ) {
        return;
    }

    global $wbtm_buddyx_settings;
    $login_page_id = $wbtm_buddyx_settings['buddyx_pages']['buddyx_login_page'];
    $register_page_id = $wbtm_buddyx_settings['buddyx_pages']['buddyx_register_page'];

    $login_page = get_permalink($login_page_id);
    $register_page = get_permalink($register_page_id);
    $page_viewed_url = basename($_SERVER['REQUEST_URI']);
    $exploded_Url = wp_parse_url($page_viewed_url);

    if ( ! isset( $exploded_Url['path'] ) ) {
        return;
    }

    // For register page
    if ( $register_page && 'wp-login.php' == $exploded_Url['path'] && 'action=register' == $exploded_Url['query'] && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
        wp_redirect( $register_page );
        exit;
    }

    // For login page
    if ( $login_page && $exploded_Url['path'] == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
        wp_redirect( $login_page );
        exit;
    }
}

/**
 * Add 404 page redirect
 */
function buddyx_404_redirect() {

    // media popup fix
    if ( strpos( $_SERVER['REQUEST_URI'], "media" ) !== false ) {
        return;
    }

    // media upload fix
    if ( strpos( $_SERVER['REQUEST_URI'], "upload" ) !== false ) {
        return;
    }

    if ( ! is_404() ) {
        return;
    }

    $buddyx_404_page_id = get_theme_mod( 'buddyx_404_page', '0' );

    if ( $buddyx_404_page_id ) {
        $buddyx_404_page_url = get_permalink( $buddyx_404_page_id );
        wp_redirect( $buddyx_404_page_url );
        exit;
    }

}
add_action( 'template_redirect', 'buddyx_404_redirect' );


/**
 * Blog Post Meta
 */
if ( ! function_exists( 'buddyx_categorized_blog' ) ) {
    function buddyx_categorized_blog() {
        $output = '';
        $categories_list = get_the_category_list( esc_html__( ', ', 'buddyxpro' ) );
        if ( $categories_list ) {
            $categories = sprintf(
                esc_html( '%1$s' ),
                $categories_list
            );
            $output .= '<span class="entry-cat-links">' . $categories . '</span>';
        }
        echo apply_filters( 'buddyx_post_categories', $output );
    }
}

if ( ! function_exists( 'buddyx_posted_on' ) ) {

    function buddyx_posted_on() {

        global $post;

        if ( is_sticky() && is_home() && ! is_paged() ) {
            echo '<span class="entry-featured">' . esc_html__( 'Sticky', 'buddyxpro' ) . '</span>';
        }
        
        if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) ) {
            buddyx_categorized_blog();
        }
            
        if ( ! is_search() ) {

            if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
                echo '<span class="entry-comments-link">';
                comments_popup_link( esc_html__( 'Leave a comment', 'buddyxpro' ), esc_html__( '1 Comment', 'buddyxpro' ), esc_html__( '% Comments', 'buddyxpro' ) );
                echo '</span>';
            }
        }

        edit_post_link( esc_html__( 'Edit', 'buddyxpro' ), '<span class="entry-edit-link">', '</span>' );
    }

}

/**
 * Add Elementor Locations Support
 */
if ( ! function_exists( 'buddyx_register_elementor_locations' ) ) {
    function buddyx_register_elementor_locations( $elementor_theme_manager ) {

        $elementor_theme_manager->register_location( 'header' );
        $elementor_theme_manager->register_location( 'footer' );

    }
    add_action( 'elementor/theme/register_locations', 'buddyx_register_elementor_locations' );
}

/**
 * Display LifterLMS Course and Lesson sidebars
 * on courses and lessons in place of the sidebar returned by
 * this function
 * @param    string     $id    default sidebar id (an empty string)
 * @return   string
 */
if ( ! function_exists( 'buddyx_llms_sidebar_function' ) ) {
    function buddyx_llms_sidebar_function( $id ) {

        $my_sidebar_id = 'sidebar-right'; // replace this with your theme's sidebar ID

        return $my_sidebar_id;

    }
    add_filter( 'llms_get_theme_default_sidebar', 'buddyx_llms_sidebar_function' );
}

/**
 * Declare explicit theme support for LifterLMS course and lesson sidebars
 * @return   void
 */
if ( ! function_exists( 'buddyx_llms_theme_support' ) ) {
    function buddyx_llms_theme_support(){

        add_theme_support( 'lifterlms-sidebars' );

    }
    add_action( 'after_setup_theme', 'buddyx_llms_theme_support' );
}

/**
 * Remove groovy menu plugin related script and style.
 *
 * @return void
 */
if ( ! function_exists( 'buddyx_remove_groovy_menu_scripts' ) ) {
    function buddyx_remove_groovy_menu_scripts() {
        if ( class_exists( 'GroovyMenuUtils' ) && !\GroovyMenuUtils::getAutoIntegration()) {		
            remove_action( 'wp_enqueue_scripts', 'groovy_menu_toolbar' );
            remove_action( 'wp_enqueue_scripts', 'groovy_menu_scripts' );
        }
        if ( is_single() && 'post' === get_post_type() ) {
			remove_action( 'buddyx_sub_header', 'buddyx_sub_header' );
		}
    }
    add_action( 'wp', 'buddyx_remove_groovy_menu_scripts' );
}

/**
 * buddyx_bbp_get_reply_avtar.
 *
 */
function buddyx_bbp_get_reply_avtar( $topic_id = 0 ) {
	if ( class_exists( 'bbPress' ) ) {

		$topic_id = bbp_get_topic_id( $topic_id );

		$r = array(
			'post_type'      => 'reply',
			'post_parent'    => $topic_id,
			'post_status'    => 'publish',
			'posts_per_page' => 4,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		$replies = new WP_Query( $r );
		if ( isset( $replies->posts ) ) {
			$reply_author_avtar = '';
			foreach ( $replies->posts as $key => $reply ) {
				echo get_avatar( $reply->post_author );
			}
		}
	}
	wp_reset_postdata();
}


/**
 * buddyx_add_post_meta_box.
 */
 add_action( 'add_meta_boxes', 'buddyx_add_post_meta_box' );
 function buddyx_add_post_meta_box() {
     global $post;
 
     add_meta_box(
         'buddyx_post_settings',
         __( 'Post Settings', 'buddyx' ),
         'render_buddyx_add_post_settings_meta_box',
         array( 'post' ),
         'normal',
         'high'
     );
 
     add_meta_box(
         'buddyx_postformat_settings',
         __( 'Post Format Settings', 'buddyx' ),
         'render_buddyx_add_post_format_meta_box',
         array( 'post' ),
         'normal',
         'high'
     );
 
 }
 
function render_buddyx_add_post_settings_meta_box( $post ) {
    $post_id         = $post->ID;
    $title_overwrite = get_post_meta( $post_id, '_post_title_overwrite', true );
    $title_position  = get_post_meta( $post_id, '_post_title_position', true );
    ?>
    <div class="buddyx_post_settings">
        <div class="buddyx-field buddyx-checkbox">
            <label>
                <input type="checkbox" name="_post_title_overwrite" value='yes' <?php checked( $title_overwrite, 'yes' ); ?>/>
                <?php esc_html_e( 'Overwrite Title Customizer settings', 'buddyx' ); ?>
            </label>
        </div>
        <div class="buddyx-field buddyx-radio">
            <div>
                <h4><?php esc_html_e( 'Title Style', 'buddyx' ); ?></h4>
            </div>
            <ul class="buddyx_radio_list">
                <li class="buddyx_radio_list_item">
                    <label>
                        <input class="buddyx_radio_input" type="radio" name="_post_title_position" value="title-over" <?php checked( $title_position, 'title-over' ); ?>/>
                        <img src="<?php echo get_template_directory_uri() . '/assets/images/single-blog-layout-1.png'; ?>" class="post-title-image"/>
                    </label>
                </li>
                <li class="buddyx_radio_list_item">
                    <label>
                        <input class="buddyx_radio_input" type="radio" name="_post_title_position" value="half" <?php checked( $title_position, 'half' ); ?>/>
                        <img src="<?php echo get_template_directory_uri() . '/assets/images/single-blog-layout-2.png'; ?>" class="post-title-image"/>
                    </label>
                </li>
                <li class="buddyx_radio_list_item">
                    <label>
                        <input class="buddyx_radio_input" type="radio" name="_post_title_position" value="title-above" <?php checked( $title_position, 'title-above' ); ?>/>
                        <img src="<?php echo get_template_directory_uri() . '/assets/images/single-blog-layout-3.png'; ?>" class="post-title-image"/>
                    </label>
                </li>
                <li class="buddyx_radio_list_item">
                    <label>
                        <input class="buddyx_radio_input" type="radio" name="_post_title_position" value="title-below" <?php checked( $title_position, 'title-below' ); ?>/>
                        <img src="<?php echo get_template_directory_uri() . '/assets/images/single-blog-layout-4.png'; ?>" class="post-title-image"/>
                    </label>
                </li>
            </ul>
        </div>
    </div>
    <?php
}
 
 
function render_buddyx_add_post_format_meta_box( $post ) {
    $post_format       = get_post_format( $post );
    $post_id           = $post->ID;
    $post_video        = get_post_meta( $post_id, '_buddyx_post_video', true );
    $post_audio        = get_post_meta( $post_id, '_buddyx_post_audio', true );
    $post_quote        = get_post_meta( $post_id, '_buddyx_post_quote', true );
    $post_quote_author = get_post_meta( $post_id, '_buddyx_post_quote_author', true );

    $post_link_title    = get_post_meta( $post_id, '_buddyx_post_link_title', true );
    $post_link_url      = get_post_meta( $post_id, '_buddyx_post_link_url', true );
    $post_image_gallery = get_post_meta( $post_id, '_buddyx_image_gallery', true );
    ?>
    <div class="buddyx_post_format-settings">
        <input type="hidden" value="<?php echo $post_format; ?>" id="buddyx_post_format"/>
        <div class="buddyx_video_format_setting">
            <p class="description"><?php esc_html_e( 'Enter Youtube, Vimeo and etc video url.', 'buddyx' ); ?></p>
            <div class="buddyx_input_section">
                <div class="format-setting-label">
                    <label class="label"><?php esc_html_e( 'Video URL', 'buddyx' ); ?></label>
                </div>
                <input type="text" id="buddyx_post_video" name="buddyx_post_video" value="<?php echo $post_video; ?>" class="buddyx-input-text"/>
                <a href="javascript:void(0);" class="buddyx_upload_media option-tree-ui-button button button-primary light" data-id="buddyx_post_video" rel="<?php echo esc_attr( $post_id ); ?>" title="<?php esc_html_e( 'Add Media', 'buddyx' ); ?>">
                    <span class="dashicons dashicons-insert"></span>
                </a>
            </div>
        </div>

        <div class="buddyx_audio_format_setting">
            <p class="description"><?php esc_html_e( 'Enter audio url.', 'buddyx' ); ?></p>
            <div class="buddyx_input_section">
                <div class="format-setting-label">
                    <label class="label"><?php esc_html_e( 'Audio URL', 'buddyx' ); ?></label>
                </div>
                <input type="text" id="buddyx_post_audio" name="buddyx_post_audio" value="<?php echo $post_audio; ?>" class="buddyx-input-text"/>
                <a href="javascript:void(0);" class="buddyx_upload_media option-tree-ui-button button button-primary light" data-id="buddyx_post_audio" rel="<?php echo esc_attr( $post_id ); ?>" title="<?php esc_html_e( 'Add Media', 'buddyx' ); ?>">
                    <span class="dashicons dashicons-insert"></span>
                </a>
            </div>
        </div>

        <div class="buddyx_quote_format_setting">
            <p class="description"><?php esc_html_e( 'Input your quote.', 'buddyx' ); ?></p>
            <div class="buddyx_input_section">
                <div class="format-setting-label">
                    <label class="label"><?php esc_html_e( 'Quote Text', 'buddyx' ); ?></label>
                </div>
                <textarea name="buddyx_post_quote" class="buddyx-input-textare"><?php echo $post_quote; ?></textarea>
            </div>	
            <div class="buddyx_input_section">
                <div class="format-setting-label">
                    <label class="label"><?php esc_html_e( 'Quote Author', 'buddyx' ); ?></label>
                </div>
                <input type="text" name="buddyx_post_quote_author" value="<?php echo $post_quote_author; ?>" class="buddyx-input-text"/>						
            </div>
        </div>
        
        <div class="buddyx_link_format_setting">
            <p class="description"><?php esc_html_e( 'Input your link.', 'buddyx' ); ?></p>
            <div class="buddyx_input_section">
                <div class="format-setting-label">
                    <label class="label"><?php esc_html_e( 'Link Title', 'buddyx' ); ?></label>
                </div>
                <input type="text" name="buddyx_post_link_title" value="<?php echo $post_link_title; ?>" class="buddyx-input-text"/>						
            </div>
            <div class="buddyx_input_section">
                <div class="format-setting-label">
                    <label class="label"><?php esc_html_e( 'Link URL', 'buddyx' ); ?></label>
                </div>
                <input type="text" name="buddyx_post_link_url" value="<?php echo $post_link_url; ?>" class="buddyx-input-text"/>
            </div>
        </div>
        
        <div class="buddyx_gallery_format_setting">
            <p class="description"><?php esc_html_e( 'To create a gallery, upload your images and then select "Uploaded to this post" from the dropdown (in the media popup) to see images attached to this post. You can drag to re-order or delete them there.', 'buddyx' ); ?></p>
            <div id="images_gallery_container" class="buddyx_images_gallery_container">
                <ul class="buddyx_images_gallery images_gallery">
                <?php
                    $image_gallery = '';
                    if ( ! empty( $post_image_gallery ) ) {
                        $post_image_gallery = explode( ',', $post_image_gallery );

                        foreach ( $post_image_gallery as $image_id ) {
                            if ( trim( $image_id ) != '' ) {
                                // $image = wp_get_attachment_image_src($image_id, 'thumbnail');

                                echo '<li class="image" data-attachment_id="' . $image_id . '">
                                    <div class="attachment-preview type-image">
                                        <div class="thumbnail">
                                            <div class="centered">
                                            ' . wp_get_attachment_image( $image_id, 'thumbnail' ) . '
                                            </div>
                                        </div>
                                    </div>

                                    <div class="actions">
                                        <a href="#" id="' . $image_id . '" class="delete" title="' . __( 'Delete image', 'buddyx' ) . '"><i class="dashicons dashicons-no"></i></a>
                                    </div>
                                </li>';
                                $image_gallery .= $image_id . ',';
                            }
                        }
                    }

                    ?>
                </ul>
                <input type="hidden" id="buddyx_image_gallery" name="buddyx_image_gallery" value="<?php echo esc_attr( substr( @$image_gallery, 0, -1 ) ); ?>" />
            </div>
            <div class="clearfix buddyx_image_gallery_description">
                <p class="add_buddyx_images hide-if-no-js">
                    <a class="components-button is-primary" href="#"><?php echo __( 'Add images gallery', 'buddyx' ); ?></a>
                </p>
            </div>
        </div>
        

        <script>
        ( function ( $ ) {
            'use strict';
            $('#buddyx_postformat_settings').hide();
            $( document ).ready( function () {
                
                var post_format = $('input[name=post_format]:checked').val();						
                if ( typeof post_format == 'undefined' ) {
                    post_format = $('#buddyx_post_format').val();
                }						
                if ( post_format == 'video' || post_format == 'audio' || post_format == 'quote' || post_format == 'link' || post_format == 'gallery' ) {
                    $('#buddyx_postformat_settings').show();
                    $( '.buddyx_video_format_setting').hide();
                    $( '.buddyx_audio_format_setting').hide();
                    $( '.buddyx_quote_format_setting').hide();
                    $( '.buddyx_link_format_setting').hide();
                    $( '.buddyx_gallery_format_setting').hide();
                    $( '.buddyx_' + post_format + '_format_setting').show();
                }
                
                
                $(document).on( "change", 'input[name=post_format], .editor-post-format__content select.components-select-control__input' , function(e){
                    var post_format = $( this ).val();
                    $( '.buddyx_video_format_setting').hide();
                    $( '.buddyx_audio_format_setting').hide();
                    $( '.buddyx_quote_format_setting').hide();
                    $( '.buddyx_link_format_setting').hide();
                    $( '.buddyx_gallery_format_setting').hide();
                    if ( post_format == 'video' ) {
                        $('#buddyx_postformat_settings').show();
                        $( '.buddyx_video_format_setting').show();
                    } else if ( post_format == 'audio' ) {
                        $('#buddyx_postformat_settings').show();
                        $( '.buddyx_audio_format_setting').show();
                    } else if ( post_format == 'quote' ) {
                        $('#buddyx_postformat_settings').show();
                        $( '.buddyx_quote_format_setting').show();
                    } else if ( post_format == 'link' ) {
                        $('#buddyx_postformat_settings').show();
                        $( '.buddyx_link_format_setting').show();
                    }else if ( post_format == 'gallery' ) {
                        $('#buddyx_postformat_settings').show();
                        $( '.buddyx_gallery_format_setting').show();
                    } else {
                        $('#buddyx_postformat_settings').hide();
                    }
                });
                
                /* Uploading files */
                var image_gallery_frame;
                var $image_gallery_ids = $('#buddyx_image_gallery');
                var $images_gallery = $('#images_gallery_container ul.images_gallery');
                $('.add_buddyx_images').on( 'click', 'a', function( event ) {
                    var $el = $(this);
                    var attachment_ids = $image_gallery_ids.val();
                    event.preventDefault();
                    /* If the media frame already exists, reopen it. */
                    if ( image_gallery_frame ) {
                        image_gallery_frame.open();
                        return;
                    }
                    /* Create the media frame.  */
                    image_gallery_frame = wp.media.frames.downloadable_file = wp.media({
                        /* Set the title of the modal.  */
                        title: '<?php echo __( 'Add images gallery', 'buddyx' ); ?>',
                        button: {
                            text: '<?php echo __( 'Add to gallery', 'buddyx' ); ?>',
                        },
                        multiple: true
                    });
                    /* When an image is selected, run a callback.  */
                    image_gallery_frame.on( 'select', function() {
                        var selection = image_gallery_frame.state().get('selection');
                        selection.map( function( attachment ) {
                            attachment = attachment.toJSON();
                            if ( attachment.id ) {
                                attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;
                                $images_gallery.append('\
                                    <li class="image" data-attachment_id="' + attachment.id + '">\
                                        <div class="attachment-preview type-image">\
                                            <div class="thumbnail">\
                                                <div class="centered">\
                                                    <img src="' + attachment.url + '" />\
                                                </div>\
                                            </div>\
                                        </div>\
                                        <div class="actions">\
                                            <a href="#" class="delete" title="<?php echo __( 'Delete image', 'buddyx' ); ?>"><i class="dashicons dashicons-no"></i></a>\
                                        </div>\
                                    </li>');
                            }
                        } );
                        $image_gallery_ids.val( attachment_ids );
                    });
                    /* Finally, open the modal. */
                    image_gallery_frame.open();
                });
                /* Image ordering */
                $images_gallery.sortable({
                    items: 'li.image',
                    cursor: 'move',
                    scrollSensitivity:40,
                    forcePlaceholderSize: true,
                    forceHelperSize: false,
                    helper: 'clone',
                    opacity: 0.65,
                    placeholder: 'wc-metabox-sortable-placeholder',
                    start:function(event,ui){
                        ui.item.css('background-color','#f6f6f6');
                    },
                    stop:function(event,ui){
                        ui.item.removeAttr('style');
                    },
                    update: function(event, ui) {
                        var attachment_ids = '';
                        $('#images_gallery_container ul li.image').css('cursor','default').each(function() {
                            var attachment_id = $(this).attr( 'data-attachment_id' );
                            attachment_ids = attachment_ids + attachment_id + ',';
                        });
                        $image_gallery_ids.val( attachment_ids );
                    }
                });
                /* Remove images */
                $('#images_gallery_container').on( 'click', 'a.delete', function() {

                    $(this).closest('li.image').remove();
                    var attachment_ids = '';
                    $('#images_gallery_container ul li.image').css('cursor','default').each(function() {
                        var attachment_id = $(this).attr( 'data-attachment_id' );
                        attachment_ids = attachment_ids + attachment_id + ',';
                    });
                    $image_gallery_ids.val( attachment_ids );
                    return false;
                } );


                $('.buddyx_upload_media').on( 'click',  function( event ) {
                    var $el = $(this);	
                    var media_id = $(this).data( 'id' );							
                    event.preventDefault();
                    /* If the media frame already exists, reopen it. */
                    if ( image_gallery_frame ) {
                        image_gallery_frame.open();
                        return;
                    }
                    /* Create the media frame.  */
                    image_gallery_frame = wp.media.frames.downloadable_file = wp.media({
                        /* Set the title of the modal.  */
                        title: '<?php echo __( 'Add Media', 'buddyx' ); ?>',
                        button: {
                            text: '<?php echo __( 'Add to Media', 'buddyx' ); ?>',
                        },
                        multiple: true
                    });
                    /* When an image is selected, run a callback.  */
                    image_gallery_frame.on( 'select', function() {
                        var selection = image_gallery_frame.state().get('selection');
                        selection.map( function( attachment ) {
                            attachment = attachment.toJSON();
                            if ( attachment.id ) {
                                
                                $( '#' + media_id ).val(attachment.url);
                            }
                        } );								
                    });
                    /* Finally, open the modal. */
                    image_gallery_frame.open();
                });
                
            });
        } )( jQuery );
        </script>

    </div>
    <?php

}
 
add_action( 'save_post', 'buddyx_save_post_meta', 10, 1 );
function buddyx_save_post_meta( $post_id ) {
    // Bail if we're doing an auto save.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // if our current user can't edit this post, bail.
    if ( ! current_user_can( 'edit_posts' ) ) {
        return;
    }

    if ( isset( $_POST['post_type'] ) && $_POST['post_type'] == 'post' ) {
        if ( isset( $_POST['buddyx_post_video'] ) ) {
            update_post_meta( $post_id, '_buddyx_post_video', $_POST['buddyx_post_video'] );
        }
        if ( isset( $_POST['buddyx_post_audio'] ) ) {
            update_post_meta( $post_id, '_buddyx_post_audio', $_POST['buddyx_post_audio'] );
        }

        if ( isset( $_POST['buddyx_post_quote'] ) ) {
            update_post_meta( $post_id, '_buddyx_post_quote', $_POST['buddyx_post_quote'] );
            update_post_meta( $post_id, '_buddyx_post_quote_author', $_POST['buddyx_post_quote_author'] );
        }
        if ( isset( $_POST['buddyx_post_link_title'] ) ) {
            update_post_meta( $post_id, '_buddyx_post_link_title', $_POST['buddyx_post_link_title'] );
            update_post_meta( $post_id, '_buddyx_post_link_url', $_POST['buddyx_post_link_url'] );
        }
        if ( isset( $_POST['buddyx_image_gallery'] ) ) {
            update_post_meta( $post_id, '_buddyx_image_gallery', $_POST['buddyx_image_gallery'] );
        }

        update_post_meta( $post_id, '_post_title_overwrite', $_POST['_post_title_overwrite'] );
        update_post_meta( $post_id, '_post_title_position', $_POST['_post_title_position'] );
    }
}


/**
* Login / Register Popup
*/
function buddyx_login_reigister_popup() {

	$form_type_login        = get_theme_mod( 'buddyx_sign_form_popup', 'default' );
	$popup_custom_shortcode = get_theme_mod( 'buddyx_sign_form_shortcode' );

	if ( true == get_theme_mod( 'buddyx_sign_form_shortcode' ) ) {
		$buddyx_login_shortcode = 'buddyx-custom-shortcode';
	} else {
       $buddyx_login_shortcode = '';
    }
	?>
		<div class="buddyx-module buddyx-window-popup buddyx-close-popup" id="registration-login-form-popup" tabindex="-1" role="dialog"  data-id="registration-login-form-popup">
			<div class="modal-dialog window-popup registration-login-form-popup" role="document" >
				<div class="modal-content">
					<div class="close icon-close buddyx-close-popup" id="buddyx-close-popup" data-id='registration-login-form-popup'>
						<i class="fa fa-times" id="buddyx-close-popup" data-id='registration-login-form-popup'></i>
					</div>
					<div class="modal-body no-padding <?php echo esc_attr( $buddyx_login_shortcode ); ?>">
							<?php
							if ( $form_type_login != 'custom' ) {
								echo buddyx_get_login_reg_form_html();
							} else {
								echo do_shortcode( $popup_custom_shortcode );
							}
							?>
					</div>
				</div>
			</div>
		</div>
	<?php
}
add_action( 'wp_footer', 'buddyx_login_reigister_popup', 999 );


function buddyx_get_login_reg_form_html( $redirect_to_custom = '', $option_data = array() ) {
	global $wp;

	$forms             = get_theme_mod( 'buddyx_sign_form_display', 'login' );
	$redirect          = get_theme_mod( 'buddyx_login_redirect', 'current' );
	$redirect_to       = get_theme_mod( 'buddyx_login_redirect_url' );
	$login_description = get_theme_mod( 'buddyx_login_description' );

	$register_redirect    = get_theme_mod( 'buddyx_register_redirect', 'current' );
	$register_redirect_to = get_theme_mod( 'buddyx_register_redirect_url' );
	$register_fields_type = get_theme_mod( 'buddyx_sign_in_register_fields_type', 'simple' );

	$redirect_to_custom = filter_var( $redirect_to_custom, FILTER_VALIDATE_URL );

	$redirect_to = ( $redirect_to && $redirect === 'custom' ) ? $redirect_to : home_url( $wp->request );
	if ( $redirect_to_custom ) {
		$redirect_to = $redirect_to_custom;
	}

	$register_redirect_to = ( $register_redirect_to && $register_redirect === 'custom' ) ? $register_redirect_to : home_url( $wp->request );
	if ( $redirect_to_custom ) {
		$register_redirect_to = $redirect_to_custom;
	}

	$attr = array();

	if ( ! empty( $option_data ) ) {
		foreach ( $option_data as $option_data_key => $option_data_value ) {
			$attr[ $option_data_key ] = $option_data_value;
		}
	} else {
		$attr = array(
			'register_redirect_to' => $register_redirect_to,
			'redirect_to'          => $redirect_to,
			'login_description'    => $login_description,
			'forms'                => $forms,
            'login_title'          => '',
			'redirect'             => $redirect,
			'register_redirect'    => $register_redirect,
			'register_fields_type' => $register_fields_type,
		);
	}
	get_template_part( 'template-parts/form', '', $attr );
}



function buddyx_bp_fields( $register_fields_type = 'buddy_press' ) {
	while ( bp_profile_groups() ) :
		bp_the_profile_group();
		while ( bp_profile_fields() ) :
			bp_the_profile_field();
			$not_required = true;
			if ( $register_fields_type == 'extensional' && bp_get_the_profile_field_is_required() === false ) {
				$not_required = false;
			}

			if ( $not_required ) {
				$simple_fields_arr = array( 'textbox', 'textbox', 'wp-textbox' );
				if ( in_array( bp_get_the_profile_field_type(), $simple_fields_arr ) ) :
					?>
				<div class="form-group label-floating fw-ext-sign-form-bp-field-group">
					<label for="<?php bp_the_profile_field_input_name(); ?>" class="control-label"><?php bp_the_profile_field_name(); ?></label>
					<input class="form-control" type="text" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" 
						<?php
						if ( bp_get_the_profile_field_is_required() ) :
							?>
						aria-required="true"<?php endif; ?>/>
				</div>
					<?php
				endif;
				if ( 'number' == bp_get_the_profile_field_type() ) :
					?>
				<div class="form-group label-floating fw-ext-sign-form-bp-field-group">
					<label for="<?php bp_the_profile_field_input_name(); ?>" class="control-label"><?php bp_the_profile_field_name(); ?></label>
					<input class="form-control" type="number" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" 
					<?php
					if ( bp_get_the_profile_field_is_required() ) :
						?>
						aria-required="true"<?php endif; ?>/>
				</div>
					<?php
				endif;
				if ( 'telephone' == bp_get_the_profile_field_type() ) :
					?>
				<div class="form-group label-floating fw-ext-sign-form-bp-field-group">
					<label for="<?php bp_the_profile_field_input_name(); ?>" class="control-label"><?php bp_the_profile_field_name(); ?></label>
					<input class="form-control" type="tel" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" 
					<?php
					if ( bp_get_the_profile_field_is_required() ) :
						?>
					aria-required="true"<?php endif; ?>/>
				</div>
					<?php
				endif;
				if ( 'url' == bp_get_the_profile_field_type() ) :
					?>
				<div class="form-group label-floating fw-ext-sign-form-bp-field-group">
					<label for="<?php bp_the_profile_field_input_name(); ?>" class="control-label"><?php bp_the_profile_field_name(); ?></label>
					<input class="form-control" type="text" inputmode="url" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" 
					<?php
					if ( bp_get_the_profile_field_is_required() ) :
						?>
						aria-required="true"<?php endif; ?>/>
				</div>
					<?php
				endif;
				if ( 'textarea' == bp_get_the_profile_field_type() || 'wp-biography' == bp_get_the_profile_field_type() ) :
					?>
				<div class="form-group label-floating fw-ext-sign-form-bp-field-group">
					<label for="<?php bp_the_profile_field_input_name(); ?>" class="control-label"><?php bp_the_profile_field_name(); ?></label>
					<textarea class="form-control" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" 
					<?php
					if ( bp_get_the_profile_field_is_required() ) :
						?>
						aria-required="true"<?php endif; ?>><?php bp_the_profile_field_edit_value(); ?></textarea>
				</div>
					<?php
				endif;
				if ( 'datebox' == bp_get_the_profile_field_type() ) :
					?>
				<h6><?php bp_the_profile_field_name(); ?></h6>
				<div class="form-group label-floating is-select">
					<label class="control-label"><?php esc_html_e( 'Day', 'buddyxpro' ); ?></label>
					<select name="<?php bp_the_profile_field_input_name(); ?>_day" class="selectpicker form-control">
						<?php for ( $i = 1; $i < 32; ++$i ) { ?>
							<?php echo sprintf( '<option value="%1$s">%2$s</option>', (int) $i, (int) $i ); ?>
						<?php } ?>
					</select>
				</div>
				<div class="form-group label-floating is-select">
					<label class="control-label"><?php esc_html_e( 'Month', 'buddyxpro' ); ?></label>
					<select name="<?php bp_the_profile_field_input_name(); ?>_month" class="selectpicker form-control">
						<?php
							$months = array(
								__( 'January', 'buddyxpro' ),
								__( 'February', 'buddyxpro' ),
								__( 'March', 'buddyxpro' ),
								__( 'April', 'buddyxpro' ),
								__( 'May', 'buddyxpro' ),
								__( 'June', 'buddyxpro' ),
								__( 'July', 'buddyxpro' ),
								__( 'August', 'buddyxpro' ),
								__( 'September', 'buddyxpro' ),
								__( 'October', 'buddyxpro' ),
								__( 'November', 'buddyxpro' ),
								__( 'December', 'buddyxpro' ),
							);
							for ( $i = 0; $i < 12; ++$i ) {
								?>
								<?php echo sprintf( '<option value="%1$s">%2$s</option>', $months[ $i ], $months[ $i ] ); ?>
						<?php } ?>
					</select>
				</div>
				<div class="form-group label-floating is-select">
					<label class="control-label"><?php esc_html_e( 'Year', 'buddyxpro' ); ?></label>
					<select name="<?php bp_the_profile_field_input_name(); ?>_year" class="selectpicker form-control">
						<?php for ( $i = date( 'Y', time() - 60 * 60 * 24 ); $i > 1901; $i-- ) { ?>
							<?php echo sprintf( '<option value="%1$s">%2$s</option>', (int) $i, (int) $i ); ?>
						<?php } ?>
					</select>
				</div>
					<?php
				endif;
				if ( 'selectbox' == bp_get_the_profile_field_type() ) :
					?>
				<div class="form-group label-floating is-select">
					<label for="<?php bp_the_profile_field_input_name(); ?>" class="control-label"><?php bp_the_profile_field_name(); ?></label>
					<select name="<?php bp_the_profile_field_input_name(); ?>" class="selectpicker form-control">
						<?php bp_the_profile_field_options(); ?>
					</select>
				</div>
					<?php
				endif;
				if ( 'multiselectbox' == bp_get_the_profile_field_type() ) :
					?>
				<div class="form-group label-floating is-select">
					<label for="<?php bp_the_profile_field_input_name(); ?>" class="control-label"><?php bp_the_profile_field_name(); ?></label>
					<select multiple name="<?php bp_the_profile_field_input_name(); ?>" class="selectpicker form-control">
						<?php bp_the_profile_field_options(); ?>
					</select>
				</div>
					<?php
				endif;
				if ( 'checkbox' == bp_get_the_profile_field_type() ) :
					$field_ch   = new BP_XProfile_Field( bp_get_the_profile_field_id() );
					$options_ch = $field_ch->get_children( true );

					?>
				<h6><?php bp_the_profile_field_name(); ?></h6>
					<?php
					if ( ! empty( $options_ch ) ) {
						foreach ( $options_ch as $options_val ) {
							?>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="<?php bp_the_profile_field_input_name() . '[]'; ?>" value="<?php echo $options_val->name; ?>" >
							<?php echo $options_val->name; ?>
					</label>
				</div>
							<?php
						}
					}
				endif;
				if ( 'radio' == bp_get_the_profile_field_type() ) :
					$field   = new BP_XProfile_Field( bp_get_the_profile_field_id() );
					$options = $field->get_children( true );
					?>
				<h6><?php bp_the_profile_field_name(); ?></h6>
					<?php
					if ( ! empty( $options ) ) {
						foreach ( $options as $options_val ) {
							?>
				<div class="radio-button">
					<label>
						<input type="radio" name="<?php bp_the_profile_field_input_name(); ?>" value="<?php echo $options_val->name; ?>">
							<?php echo $options_val->name; ?>
					</label>
				</div>
							<?php
						}
					}
				endif;
			}
		endwhile;
	endwhile;
}


function buddyx_signin_form() {
	check_ajax_referer( 'buddyx-sign-form', '_ajax_nonce' );

	$errors = array();

	$log         = filter_input( INPUT_POST, 'log' );
	$pwd         = filter_input( INPUT_POST, 'pwd' );
	$rememberme  = filter_input( INPUT_POST, 'rememberme' );
	$redirect    = filter_input( INPUT_POST, 'redirect' );
	$redirect_to = filter_input( INPUT_POST, 'redirect_to', FILTER_VALIDATE_URL );

	if ( ! $log ) {
		$errors['log'] = esc_html__( 'Login is required', 'buddyxpro' );
	}

	if ( ! $pwd ) {
		$errors['pwd'] = esc_html__( 'Password is required', 'buddyxpro' );
	}

	if ( ! empty( $errors ) ) {
		wp_send_json_error(
			array(
				'errors' => $errors,
			)
		);
	}

	$user = wp_signon(
		array(
			'user_login'    => $log,
			'user_password' => $pwd,
			'remember'      => $rememberme,
		)
	);

	if ( is_wp_error( $user ) ) {
		wp_send_json_error(
			array(
				'message' => $user->get_error_message(),
			)
		);
	}

	if ( buddyx_BuddyPress() ) {
		if ( $redirect === 'profile' && function_exists( 'bp_core_get_user_domain' ) ) {
			$redirect_to = bp_core_get_user_domain( $user->ID );
		}

		if ( $redirect === 'activity' ) {
			$redirect_to = get_bloginfo( 'url' ) . '/members/' . $user->user_login . '/activity/';
		}
	}

	if ( class_exists( 'PeepSo' ) ) {
		if ( $redirect === 'profile' && class_exists( 'PeepSo' ) ) {
			$redirect_to = site_url( '/' ) . 'profile/?user/';
			$user        = PeepSoUser::get_instance( $user->ID );
			$redirect_to = $user->get_profileurl();
		}

		if ( $redirect === 'activity' && class_exists( 'PeepSo' ) ) {
			$redirect_to = site_url( '/' ) . PeepSo::get_option( 'page_activity' );
		}
	}

	wp_send_json_success(
		array(
			'redirect_to' => $redirect_to ? $redirect_to : '',
		)
	);
}
add_action( 'wp_ajax_nopriv_buddyx-signin-form', 'buddyx_signin_form' );



/**
 * check buddypress component active or not
 *
 * @package buddyxpro
 */
function buddyx_BuddyPress() {
	if ( function_exists( 'bp_core_get_user_domain' ) && function_exists( 'bp_activity_get_user_mentionname' ) && function_exists( 'bp_attachments_get_attachment' ) && function_exists( 'bp_loggedin_user_domain' ) && function_exists( 'bp_is_active' ) && function_exists( 'bp_get_activity_slug' ) && function_exists( 'bp_is_active' ) && function_exists( 'bp_get_notifications_unread_permalink' ) && function_exists( 'bp_loggedin_user_domain' ) && function_exists( 'bp_get_settings_slug' ) ) {
		return true;
	}

	return false;
}

function buddyx_signup_form() {
	check_ajax_referer( 'buddyx-sign-form', '_ajax_nonce' );

	$errors = array();

	$user_login  = filter_input( INPUT_POST, 'user_login' );
	$user_email  = filter_input( INPUT_POST, 'user_email', FILTER_VALIDATE_EMAIL );
	$first_name  = filter_input( INPUT_POST, 'first_name' );
	$last_name   = filter_input( INPUT_POST, 'last_name' );
	$redirect_to = filter_input( INPUT_POST, 'redirect_to', FILTER_VALIDATE_URL );
	$redirect    = filter_input( INPUT_POST, 'redirect' );
	$gdpr        = '';
	if ( isset($_POST['gdpr']) ) {
		$gdpr = filter_input( INPUT_POST, 'gdpr' );
	}
	
	if ( isset($_POST['signup-privacy-policy-accept']) ) {
		$gdpr = filter_input( INPUT_POST, 'signup-privacy-policy-accept' );
	}

	if ( isset($_POST['legal_agreement']) ) {
		$gdpr = filter_input( INPUT_POST, 'legal_agreement' );
	}

	$privacy_policy_page_link = get_privacy_policy_url();

	$user_password         = filter_input( INPUT_POST, 'user_password' );
	$user_password_confirm = filter_input( INPUT_POST, 'user_password_confirm' );

	if ( isset( $user_password ) ) {
		$user_password = trim( $user_password );
	}
	$register_fields_type = get_theme_mod( 'buddyx_sign_in_register_fields_type', 'simple' );
	$bp_fields            = buddyx_get_buddypress_fields();
	if ( $register_fields_type != 'simple' ) {
		if ( ! empty( $bp_fields ) ) {
			foreach ( $bp_fields as $bp_field_key => $bp_field_value ) {
				$post_val = ( isset( $_POST[ $bp_field_key ] ) ) ? $_POST[ $bp_field_key ] : '';
				if ( empty( $post_val ) ) {
					$errors[ $bp_field_key ] = esc_html__( $bp_field_value['label'] . ' is required', 'buddyxpro' );
				}
			}
		}
	}

	if ( $register_fields_type == 'simple' ) {
		if ( ! trim( $first_name ) && isset( $first_name ) ) {
			$errors['first_name'] = esc_html__( 'First name is required', 'buddyxpro' );
		}
		if ( ! trim( $last_name ) && isset( $last_name ) ) {
			$errors['last_name'] = esc_html__( 'Last name is required', 'buddyxpro' );
		}
	}

	if ( ! trim( $user_login ) ) {
		$errors['user_login'] = esc_html__( 'Login is required', 'buddyxpro' );
	}
	if ( ! trim( $user_email ) ) {
		$errors['user_email'] = esc_html__( 'Email is required', 'buddyxpro' );
	}

	if ( strlen( $user_password ) < 6 && isset( $user_password ) ) {
		$errors['user_password'] = esc_html__( 'Minimum password length is 6 characters', 'buddyxpro' );
	} elseif ( $user_password !== $user_password_confirm && isset( $user_password_confirm ) && isset( $user_password ) ) {
		$errors['user_password_confirm'] = esc_html__( 'Password and confirm password does not match', 'buddyxpro' );
	}

	if ( ! $gdpr && $privacy_policy_page_link ) {
		$errors['gdpr'] = esc_html__( 'Please, accept privacy policy', 'buddyxpro' );
	}

	if ( ! empty( $errors ) ) {
		wp_send_json_error(
			array(
				'errors' => $errors,
			)
		);
	}

	$send_validation_email = get_theme_mod( 'sign_in_register_activation_email', 'yes' );
	$bp_pages_option       = get_option( 'bp-pages' );
	$register_page_id      = $bp_pages_option['register'];
	$register_page_status  = get_post_status( $register_page_id );
	$register_page_url     = get_permalink( $register_page_id );

	if ( $register_page_status != 'publish' || $register_fields_type != 'extensional' ) {
		if ( ! buddyx_BuddyPress() ) {
			$user_id = buddyx_register_new_user( $user_login, $user_email, $user_password );
			// Authorize user
			wp_set_auth_cookie( $user_id, true );
			if ( is_wp_error( $user_id ) ) {
				wp_send_json_error(
					array(
						'message' => $user_id->get_error_message(),
					)
				);
			}

			wp_send_json_success(
				array(
					'redirect_to' => $redirect_to ? $redirect_to : '',
				)
			);
		} else {
			$user_meta_arr = array(
				'last_name'  => $last_name,
				'first_name' => $first_name,
				'gdpr'       => $gdpr,
			);
			if ( ! empty( $bp_fields ) ) {
				$date_val = array();
				foreach ( $bp_fields as $bp_field_key => $bp_field_value ) {
					$post_val = ( isset( $_POST[ $bp_field_key ] ) ) ? $_POST[ $bp_field_key ] : '';
					if ( $bp_field_value['type'] != 'datebox' ) {
						$user_meta_arr[ 'buddyx_sign_form_' . $bp_field_key ] = $post_val;
					} else {
						if ( ! isset( $date_text ) ) {
							$date_text = '';
						}
						$date_text .= $post_val . '-';
						array_push( $date_val, 1 );
						if ( count( $date_val ) == 3 ) {
							$date_text = substr( $date_text, 0, -1 );
							$user_meta_arr[ 'buddyx_sign_form_' . $bp_field_value['id'] ] = $date_text;
							$date_text = '';
							$date_val  = array();
						}
					}
				}
			}

			if ( $send_validation_email == '' ) {
				add_action( 'bp_core_signup_user', 'buddyx_bp_core_signup_user_disable_validation' );
			}
			$user_id = bp_core_signup_user( $user_login, $user_password, $user_email, $user_meta_arr );
			if ( is_wp_error( $user_id ) ) {
				wp_send_json_error(
					array(
						'message' => $user_id->get_error_message(),
					)
				);
			}

			if ( $send_validation_email == '' ) {
				if ( $redirect === 'profile' && function_exists( 'bp_core_get_user_domain' ) ) {
					$redirect_to = bp_core_get_user_domain( $user_id );
				}

				if ( $redirect === 'profile' && class_exists( 'PeepSo' ) ) {
					$redirect_to = site_url() . 'profile/?user/';
					$user        = PeepSoUser::get_instance( $user_id );
					$redirect_to = $user->get_profileurl();
				}

				$user_data = get_userdata( $user_id );

				if ( $redirect === 'activity' ) {
					$redirect_to = get_bloginfo( 'url' ) . '/members/' . $user_data->user_login . '/activity/';
				}

				if ( $redirect === 'activity' && class_exists( 'PeepSo' ) ) {
					$redirect_to = site_url( '/' ) . PeepSo::get_option( 'page_activity' );
				}

				wp_send_json_success(
					array(
						'redirect_to' => $redirect_to ? $redirect_to : '',
					)
				);
			} else {
				wp_send_json_success(
					array(
						'email_sent' => 1,
					)
				);
			}
		}
	} elseif ( $register_page_status == 'publish' && $register_fields_type == 'extensional' ) {
		$register_page_url = wp_registration_url();
		$add_params        = '?buddyx_sign_form_prefill=1&user_login=' . $user_login . '&user_email=' . $user_email . '&first_name=' . $first_name . '&last_name=' . $last_name;

		if ( ! empty( $bp_fields ) ) {
			foreach ( $bp_fields as $bp_field_key => $bp_field_value ) {
				$post_val_el = '';
				$post_val    = ( isset( $_POST[ $bp_field_key ] ) ) ? $_POST[ $bp_field_key ] : '';
				if ( is_array( $post_val ) ) {
					foreach ( $post_val as $post_val_v ) {
						$post_val_el .= wp_unslash( $post_val_v ) . '|';
					}
					$post_val_el = substr( $post_val_el, 0, -1 );
				} else {
					$post_val_el = wp_unslash( $post_val );
				}

				$add_params .= '&' . $bp_field_key . '=' . $post_val_el;
			}
		}

		$register_page_url .= $add_params;
		wp_send_json_success(
			array(
				'redirect_to' => $register_page_url,
			)
		);
	}
}

add_action( 'wp_ajax_nopriv_buddyx-signup-form', 'buddyx_signup_form' );


function buddyx_get_buddypress_fields() {
	$fields_arr = array();
	if ( buddyx_BuddyPress() ) {
		if ( bp_is_active( 'xprofile' ) && ! function_exists( 'bp_nouveau_has_signup_xprofile_fields' ) ) :
			if ( bp_has_profile(
				array(
					'profile_group_id' => 1,
					'fetch_field_data' => false,
				)
			) ) :
				while ( bp_profile_groups() ) :
					bp_the_profile_group();
					while ( bp_profile_fields() ) :
						bp_the_profile_field();
						if ( bp_get_the_profile_field_is_required() ) {
							if ( bp_get_the_profile_field_type() != 'datebox' ) {
								$fields_arr[ 'field_' . bp_get_the_profile_field_id() ] = array(
									'label' => bp_get_the_profile_field_name(),
									'type'  => bp_get_the_profile_field_type(),
									'id'    => bp_get_the_profile_field_id(),
								);
							} else {
								$fields_arr[ 'field_' . bp_get_the_profile_field_id() . '_day' ]   = array(
									'label' => bp_get_the_profile_field_name(),
									'type'  => bp_get_the_profile_field_type(),
									'id'    => bp_get_the_profile_field_id(),
								);
								$fields_arr[ 'field_' . bp_get_the_profile_field_id() . '_month' ] = array(
									'label' => bp_get_the_profile_field_name(),
									'type'  => bp_get_the_profile_field_type(),
									'id'    => bp_get_the_profile_field_id(),
								);
								$fields_arr[ 'field_' . bp_get_the_profile_field_id() . '_year' ]  = array(
									'label' => bp_get_the_profile_field_name(),
									'type'  => bp_get_the_profile_field_type(),
									'id'    => bp_get_the_profile_field_id(),
								);
							}
						}
				endwhile;
				endwhile;
		endif;
		elseif ( bp_is_active( 'xprofile' ) && bp_nouveau_has_signup_xprofile_fields( true ) ) :
			while ( bp_profile_groups() ) :
				bp_the_profile_group();
				while ( bp_profile_fields() ) :
					bp_the_profile_field();
					if ( bp_get_the_profile_field_is_required() ) {
						if ( bp_get_the_profile_field_type() != 'datebox' ) {
							$fields_arr[ 'field_' . bp_get_the_profile_field_id() ] = array(
								'label' => bp_get_the_profile_field_name(),
								'type'  => bp_get_the_profile_field_type(),
								'id'    => bp_get_the_profile_field_id(),
							);
						} else {
							$fields_arr[ 'field_' . bp_get_the_profile_field_id() . '_day' ]   = array(
								'label' => bp_get_the_profile_field_name(),
								'type'  => bp_get_the_profile_field_type(),
								'id'    => bp_get_the_profile_field_id(),
							);
							$fields_arr[ 'field_' . bp_get_the_profile_field_id() . '_month' ] = array(
								'label' => bp_get_the_profile_field_name(),
								'type'  => bp_get_the_profile_field_type(),
								'id'    => bp_get_the_profile_field_id(),
							);
							$fields_arr[ 'field_' . bp_get_the_profile_field_id() . '_year' ]  = array(
								'label' => bp_get_the_profile_field_name(),
								'type'  => bp_get_the_profile_field_type(),
								'id'    => bp_get_the_profile_field_id(),
							);
						}
					}
			endwhile;
			endwhile;
		endif;
	}

	return $fields_arr;
}


function buddyx_register_new_user( $user_login, $user_email, $user_pass ) {

	$errors = new WP_Error();

	$sanitized_user_login = sanitize_user( $user_login );
	/**
	 * Filters the email address of a user being registered.
	 *
	 * @since 2.1.0
	 *
	 * @param string $user_email The email address of the new user.
	 */
	$user_email = apply_filters( 'user_registration_email', $user_email );

	// Check the username
	if ( $sanitized_user_login === '' ) {
		$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.', 'buddyxpro' ) );
	} elseif ( ! validate_username( $user_login ) ) {
		$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.', 'buddyxpro' ) );
		$sanitized_user_login = '';
	} elseif ( username_exists( $sanitized_user_login ) ) {
		$errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered. Please choose another one.', 'buddyxpro' ) );
	} else {
		/** This filter is documented in wp-includes/user.php */
		$illegal_user_logins = array_map( 'strtolower', (array) apply_filters( 'illegal_user_logins', array() ) );
		if ( in_array( strtolower( $sanitized_user_login ), $illegal_user_logins ) ) {
			$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: Sorry, that username is not allowed.', 'buddyxpro' ) );
		}
	}

	// Check the email address
	if ( $user_email === '' ) {
		$errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your email address.', 'buddyxpro' ) );
	} elseif ( ! is_email( $user_email ) ) {
		$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'buddyxpro' ) );
		$user_email = '';
	} elseif ( email_exists( $user_email ) ) {
		$errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email is already registered, please choose another one.', 'buddyxpro' ) );
	}

	// Check the password
	if ( $user_pass === '' ) {
		$errors->add( 'empty_pass', __( '<strong>ERROR</strong>: Please type your password.', 'buddyxpro' ) );
	} elseif ( strlen( $user_pass ) < 6 ) {
		$errors->add( 'invalid_pass', __( '<strong>ERROR</strong>: The minimum password length is 6 characters.', 'buddyxpro' ) );
	}

	/**
	 * Fires when submitting registration form data, before the user is created.
	 *
	 * @since 2.1.0
	 *
	 * @param string   $sanitized_user_login The submitted username after being sanitized.
	 * @param string   $user_email           The submitted email.
	 * @param WP_Error $errors               Contains any errors with submitted username and email,
	 *                                       e.g., an empty field, an invalid username or email,
	 *                                       or an existing username or email.
	 */
	do_action( 'register_post', $sanitized_user_login, $user_email, $errors );

	/**
	 * Filters the errors encountered when a new user is being registered.
	 *
	 * The filtered WP_Error object may, for example, contain errors for an invalid
	 * or existing username or email address. A WP_Error object should always returned,
	 * but may or may not contain errors.
	 *
	 * If any errors are present in $errors, this will abort the user's registration.
	 *
	 * @since 2.1.0
	 *
	 * @param WP_Error $errors               A WP_Error object containing any errors encountered
	 *                                       during registration.
	 * @param string   $sanitized_user_login User's username after it has been sanitized.
	 * @param string   $user_email           User's email.
	 */
	$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

	if ( $errors->has_errors() ) {
		return $errors;
	}

	$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
	if ( ! $user_id || is_wp_error( $user_id ) ) {
		$errors->add( 'registerfail', sprintf( __( '<strong>ERROR</strong>: Couldn&#8217;t register you&hellip; please contact the <a href="mailto:%s">webmaster</a> !', 'buddyxpro' ), get_option( 'admin_email' ) ) );
		return $errors;
	}

	update_user_option( $user_id, 'default_password_nag', true, true ); // Set up the Password change nag.

	/**
	 * Fires after a new user registration has been recorded.
	 *
	 * @since 4.4.0
	 *
	 * @param int $user_id ID of the newly registered user.
	 */
	do_action( 'register_new_user', $user_id );

	return $user_id;
}

function buddyx_bp_core_signup_user_disable_validation( $user_id ) {
	global $wpdb;

	// Hook if you want to do something before the activation
	do_action( 'bp_disable_activation_before_activation' );

	$activation_key = get_user_meta( $user_id, 'activation_key', true );
	$activate       = apply_filters( 'bp_core_activate_account', bp_core_activate_signup( $activation_key ) );
	BP_Signup::validate( $activation_key );
	$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->users SET user_status = 0 WHERE ID = %d", $user_id ) );

	// Add note on Activity Stream
	if ( function_exists( 'bp_activity_add' ) ) {
		$userlink = bp_core_get_userlink( $user_id );

		bp_activity_add(
			array(
				'user_id'   => $user_id,
				'action'    => apply_filters( 'bp_core_activity_registered_member', sprintf( __( '%s became a registered member', 'buddyxpro' ), $userlink ), $user_id ),
				'component' => 'profile',
				'type'      => 'new_member',
			)
		);

	}
	// Send email to admin
	wp_new_user_notification( $user_id );
	// Remove the activation key meta
	delete_user_meta( $user_id, 'activation_key' );
	// Delete the total member cache
	wp_cache_delete( 'bp_total_member_count', 'bp' );

	// Hook if you want to do something before the login
	do_action( 'bp_disable_activation_before_login' );

	/*
		//Automatically log the user in	.
		//Thanks to Justin Klein's  wp-fb-autoconnect plugin for the basic code to login automatically
		$user_info = get_userdata($user_id);
		wp_set_auth_cookie($user_id);

		do_action('wp_signon', $user_info->user_login);
	*/

	// Hook if you want to do something after the login
	do_action( 'bp_disable_activation_after_login' );

}

add_action( 'bp_after_register_page', 'buddyx_action_buddyx_sign_form_prefill_register_form' );
function buddyx_action_buddyx_sign_form_prefill_register_form() {

	if ( class_exists( 'Youzify' ) ) {
		return;
	}
	
	$bp_fields = array();
	$bp_fields = buddyx_get_buddypress_fields();

	if ( isset( $_GET['buddyx_sign_form_prefill'] ) ) {
		$user_login = ( isset( $_GET['user_login'] ) ) ? $_GET['user_login'] : '';
		$user_email = ( isset( $_GET['user_email'] ) ) ? $_GET['user_email'] : '';
		?>
		<script>
			jQuery( document ).ready( function($) {
				$('#signup_username').val('<?php echo $user_login; ?>');
				$('#signup_email').val('<?php echo $user_email; ?>');
				<?php
				if ( ! empty( $bp_fields ) ) {
					foreach ( $bp_fields as $bp_field_key => $bp_field_value ) {
						$get_field_val = ( isset( $_GET[ $bp_field_key ] ) ) ? $_GET[ $bp_field_key ] : '';
						$input_types   = array( 'textbox', 'textarea', 'number', 'telephone', 'url', 'datebox', 'selectbox' );
						if ( in_array( $bp_field_value['type'], $input_types ) ) {
							?>
						$('*[name="<?php echo $bp_field_key; ?>"]').val('<?php echo $get_field_val; ?>');
							<?php
						} elseif ( $bp_field_value['type'] == 'radio' ) {
							?>
						$('#<?php echo $bp_field_key; ?>').find('input[value="<?php echo $get_field_val; ?>"]').prop('checked', true);
							<?php
						} elseif ( $bp_field_value['type'] == 'checkbox' ) {
							$values_arr = explode( '|', $get_field_val );
							if ( ! empty( $values_arr ) ) {
								foreach ( $values_arr as $values_arr_v ) {
									?>
								$('#<?php echo $bp_field_key; ?>').find('input[value="<?php echo $values_arr_v; ?>"]').prop('checked', true);
									<?php
								}
							}
						} elseif ( $bp_field_value['type'] == 'multiselectbox' ) {
							$values_arr = explode( '|', $get_field_val );
							if ( ! empty( $values_arr ) ) {
								foreach ( $values_arr as $values_arr_v ) {
									?>
								$('select[name="<?php echo $bp_field_key; ?>[]"]').find('option[value="<?php echo $values_arr_v; ?>"]').attr("selected", "selected");
									<?php
								}
							}
						}
					}
				}
				?>
			});
		</script>
		<?php
	}
}

/**
 *
 * CSS Compress
 *
 */
if ( ! function_exists( 'buddyx_css_compress' ) ) {
	function buddyx_css_compress( $css ) {
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		$css = str_replace( ': ', ':', $css );
		$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
		return $css;
	}
}

/**
 * Function to filter activity content to formate some types of activity
 *
 * @var void
 */
add_filter( 'bp_activity_entry_content', 'buddyx_filter_activity_content', 9 );

add_action( 'bp_activity_embed_after_media', 'buddyx_filter_activity_content' );

function buddyx_filter_activity_content() {
	if ( ! bp_activity_has_content() ) {
		global $activities_template;

		$activity_id   = $activities_template->activity->id;
		$activity_type = $activities_template->activity->type;

		if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) && $activity_type == 'new_avatar' ) {
			return;
		}

		if ( function_exists( 'buddypress' ) && version_compare( buddypress()->version, '10.0.0', '>=' ) && ! isset( buddypress()->buddyboss ) && $activity_type != 'new_group_avatar' && $activity_type != 'new_group_cover_photo' && $activity_type != 'new_cover_photo' ) {
			return;
		}

		switch ( $activity_type ) {
			case 'joined_group':
				buddyx_joined_group_activity_content( $activities_template->activity );
				break;
			case 'friendship_created':
				buddyx_friendship_created_activity_content( $activities_template->activity );
				break;
			case 'new_group_avatar':
			case 'new_group_cover_photo':
				buddyx_new_group_avatar_activity_content( $activities_template->activity );
				break;
			case 'new_avatar':
			case 'new_cover_photo':
				buddyx_new_avatar_activity_content( $activities_template->activity );
				break;
			default:
				break;
		}
	}
}

/**
 * Formate join group activity.
 *
 * @param  object $activity
 *
 * @return void
 */
function buddyx_joined_group_activity_content( $activity ) {
	$args = array(
		'activity_id' => $activity->id,
		'user_id'     => $activity->user_id,
		'item_id'     => $activity->item_id,
		'component'   => $activity->component,
		'type'        => $activity->type,
	);

	if ( ! class_exists( 'Youzify' ) ) {
		get_template_part( 'template-parts/content', 'user-preview', $args );
	}
}

/**
 * Formate friendship created activity
 *
 * @param  object $activity
 *
 * @return void
 */
function buddyx_friendship_created_activity_content( $activity ) {

	$args = array(
		'activity_id'       => $activity->id,
		'user_id'           => $activity->user_id,
		'item_id'           => $activity->item_id,
		'secondary_item_id' => $activity->secondary_item_id,
		'component'         => $activity->component,
		'type'              => $activity->type,
	);

	if ( ! class_exists( 'Youzify' ) ) {
		get_template_part( 'template-parts/content', 'user-preview', $args );
	}
}

function buddyx_new_avatar_activity_content( $activity ) {

	$args = array(
		'activity_id'       => $activity->id,
		'user_id'           => $activity->user_id,
		'item_id'           => $activity->item_id,
		'secondary_item_id' => $activity->secondary_item_id,
		'component'         => $activity->component,
		'type'              => $activity->type,
	);

	if ( ! class_exists( 'Youzify' ) ) {
		get_template_part( 'template-parts/content', 'user-preview', $args );
	}
}

function buddyx_new_group_avatar_activity_content( $activity ) {
	$args = array(
		'activity_id'       => $activity->id,
		'user_id'           => $activity->user_id,
		'item_id'           => $activity->item_id,
		'secondary_item_id' => $activity->secondary_item_id,
		'component'         => $activity->component,
		'type'              => $activity->type,
	);

	if ( ! class_exists( 'Youzify' ) ) {
		get_template_part( 'template-parts/content', 'user-preview', $args );
	}
}

function buddyx_register_activity_actions() {
	
    /* Member Cover Photo */
	if ( true == get_theme_mod( 'buddypress_member_cover_image_activity', true ) ) {
		bp_activity_set_action(
			buddypress()->members->id,
			'new_cover_photo',
			__( 'Member changed cover photo', 'buddyxpro' ),
			'bp_members_format_activity_action_new_cover_photo',
			__( 'Updated Cover Photo', 'buddyxpro' )
		);
	}

	if ( bp_is_active( 'groups' ) ) {
		/* Gropup Photo */
		if ( true == get_theme_mod( 'buddypress_group_image_activity', true ) ) {
			bp_activity_set_action(
				buddypress()->groups->id,
				'new_group_avatar',
				__( 'Member changed group picture', 'buddyxpro' ),
				'bp_groups_format_activity_action_new_group_avatar',
				__( 'Updated Group Photos', 'buddyxpro' )
			);
		}

		/* Gropup Cover Photo */
		if ( true == get_theme_mod( 'buddypress_group_cover_image_activity', true ) ) {
			bp_activity_set_action(
				buddypress()->groups->id,
				'new_group_cover_photo',
				__( 'Member changed group cover photo', 'buddyxpro' ),
				'bp_groups_format_activity_action_new_group_cover_photo',
				__( 'Updated Group Cover Photos', 'buddyxpro' )
			);
		}
	}
}

add_action( 'bp_register_activity_actions', 'buddyx_register_activity_actions' );

/**
 * Format 'new_cover_photo' activity actions.
 *
 * @since 3.4.0
 *
 * @param string $action   Static activity action.
 * @param object $activity Activity object.
 * @return string
 */
function bp_members_format_activity_action_new_cover_photo( $action, $activity ) {
	$userlink = bp_core_get_userlink( $activity->user_id );

	/* translators: %s: user link */
	$action = sprintf( esc_html__( '%s changed their cover photo', 'buddyxpro' ), $userlink );

	// Legacy filter - pass $user_id instead of $activity.
	if ( has_filter( 'bp_xprofile_new_avatar_action' ) ) {
		$action = apply_filters( 'bp_xprofile_new_avatar_action', $action, $activity->user_id );
	}

	return apply_filters( 'bp_members_format_activity_action_new_cover_photo', $action, $activity );
}

/**
 * Format 'new_group_avatar' activity actions.
 *
 * @since 3.4.0
 *
 * @param string $action   Static activity action.
 * @param object $activity Activity object.
 * @return string
 */
function bp_groups_format_activity_action_new_group_avatar( $action, $activity ) {
	$userlink = bp_core_get_userlink( $activity->user_id );

	$group      = groups_get_group( array( 'group_id' => $activity->item_id ) );
	$group_link = bp_get_group_permalink( $group );
	$grouplink  = '<a href="' . esc_url( $group_link ) . '">' . $group->name . '</a>';

	/* translators: %s: user link */
	$action = sprintf( esc_html__( '%1$s changed %2$s group photo', 'buddyxpro' ), $userlink, $grouplink );

	return apply_filters( 'bp_groups_format_activity_action_new_group_avatar', $action, $activity );
}

/**
 * Format 'new_group_cover_photo' activity actions.
 *
 * @since 3.4.0
 *
 * @param string $action   Static activity action.
 * @param object $activity Activity object.
 * @return string
 */
function bp_groups_format_activity_action_new_group_cover_photo( $action, $activity ) {
	$userlink = bp_core_get_userlink( $activity->user_id );

	$group      = groups_get_group( array( 'group_id' => $activity->item_id ) );
	$group_link = bp_get_group_permalink( $group );
	$grouplink  = '<a href="' . esc_url( $group_link ) . '">' . $group->name . '</a>';

	/* translators: %s: user link */
	$action = sprintf( esc_html__( '%1$s changed %2$s group cover photo', 'buddyxpro' ), $userlink, $grouplink );

	return apply_filters( 'bp_groups_format_activity_action_new_group_cover_photo', $action, $activity );
}

/**
 * Adds an activity stream item when a user has uploaded a new cover image.
 *
 * @since 3.4.0
 *
 * @param int $user_id The user id the avatar was set for.
 */
function buddyx_members_cover_image_uploaded( $item_id, $name, $cover_url, $feedback_code ) {
	// Bail if activity component is not active.

	if ( false == get_theme_mod( 'buddypress_member_cover_image_activity', true ) ) {
		return false;
	}

	if ( ! bp_is_active( 'activity' ) ) {
		return false;
	}

	if ( empty( $user_id ) ) {
		$user_id = bp_displayed_user_id();
	}

	// Add the activity.
	$activity_id = bp_activity_add(
		array(
			'user_id'           => $user_id,
			'component'         => buddypress()->members->id,
			'type'              => 'new_cover_photo',
			'item_id'           => $item_id,
			'secondary_item_id' => $item_id,
		)
	);

	$type = pathinfo( $cover_url, PATHINFO_EXTENSION );
	$data = wp_remote_get( $cover_url );
	$data = wp_remote_retrieve_body( $data );
	$avatar_image_base64 = 'data:image/' . $type . ';base64,' . base64_encode( $data );
	bp_activity_update_meta( $activity_id, 'cover_image', $avatar_image_base64 );
	bp_activity_update_meta( $activity_id, 'cover_image_name', $name );
}

if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {
	add_action( 'members_cover_image_uploaded', 'buddyx_members_cover_image_uploaded', 10, 4 );
}

if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
	add_action( 'xprofile_cover_image_uploaded', 'buddyx_members_cover_image_uploaded', 10, 4 );
}

/**
 * Adds an activity stream item when a user has uploaded a new group cover image.
 *
 * @since 3.4.0
 */
function buddyx_groups_cover_image_uploaded( $item_id, $name, $cover_url, $feedback_code ) {
	// Bail if activity component is not active.

	if ( false == get_theme_mod( 'buddypress_group_cover_image_activity', true ) ) {
		return false;
	}

	if ( ! bp_is_active( 'activity' ) ) {
		return false;
	}

	if ( empty( $user_id ) ) {
		$user_id = bp_displayed_user_id();
	}

	$user_id = get_current_user_id();

	// Add the activity.
	$activity_id = bp_activity_add(
		array(
			'user_id'   => $user_id,
			'component' => buddypress()->groups->id,
			'type'      => 'new_group_cover_photo',
			'item_id'   => $item_id,
		)
	);

	$type = pathinfo( $cover_url, PATHINFO_EXTENSION );
	$data = wp_remote_get( $cover_url );
	$data = wp_remote_retrieve_body( $data );
	$avatar_image_base64 = 'data:image/' . $type . ';base64,' . base64_encode( $data );
	bp_activity_update_meta( $activity_id, 'group_cover_image', $avatar_image_base64 );
	bp_activity_update_meta( $activity_id, 'group_cover_image_name', $name );
}

add_action( 'groups_cover_image_uploaded', 'buddyx_groups_cover_image_uploaded', 10, 4 );

/**
 * Adds an activity stream item when a user has uploaded a new group avatar.
 *
 * @since 3.4.0
 */
function buddyx_groups_avatar_uploaded( $item_id = 0, $type ) {

	// Bail if activity component is not active.
	if ( false == get_theme_mod( 'buddypress_group_image_activity', true ) ) {
		return false;
	}

	if ( ! bp_is_active( 'activity' ) ) {
		return false;
	}

	$user_id = get_current_user_id();

	// Add the activity.
	$activity_id = bp_activity_add(
		array(
			'user_id'   => $user_id,
			'component' => buddypress()->groups->id,
			'type'      => 'new_group_avatar',
			'item_id'   => $item_id,
		)
	);

	$avatar_url = bp_core_fetch_avatar(
		array(
			'item_id'    => $item_id,
			'type'       => 'full',
			'avatar_dir' => 'group-avatars',
			'object'     => 'group',
			'width'      => 400,
			'height'     => 400,
			'html'       => false,
		)
	);

	$title = basename( $avatar_url );
	$type = pathinfo( $avatar_url, PATHINFO_EXTENSION );
	$data = wp_remote_get( $avatar_url );
	$data = wp_remote_retrieve_body( $data );
	$avatar_image_base64 = 'data:image/' . $type . ';base64,' . base64_encode( $data );
	bp_activity_update_meta( $activity_id, 'group_avatar_image', $avatar_image_base64 );
	bp_activity_update_meta( $activity_id, 'group_avatar_image_name', $title );
}

add_action( 'groups_avatar_uploaded', 'buddyx_groups_avatar_uploaded', 20, 2 );

add_filter( 'bp_get_activity_action_pre_meta', 'buddyx_group_activity_secondary_avatars', 20, 2 );

function buddyx_group_activity_secondary_avatars( $action, $activity ) {

	if ( $activity->type == 'new_group_avatar' || $activity->type == 'new_group_cover_photo' ) {
		switch ( $activity->component ) {
			case 'groups':
				global $activities_template;
				$action = $activities_template->activity->action;
				break;
		}
	}
	return $action;
}

function buddyx_bp_avatar_activity_add( $args, $activity_id ) {
	if ( $args['type'] == 'new_avatar' && ( $args['component'] == 'members' || $args['component'] == 'profile' ) ) {
		$avatar_url = bp_core_fetch_avatar(
			array(
				'item_id' => $args['user_id'],
				'type'    => 'full',
				'width'   => 400,
				'height'  => 400,
				'html'    => false,
			)
		);

		$title = basename( $avatar_url );
		$type = pathinfo( $avatar_url, PATHINFO_EXTENSION );
		$data = wp_remote_get( $avatar_url );
		$data = wp_remote_retrieve_body( $data );
		$avatar_image_base64 = 'data:image/' . $type . ';base64,' . base64_encode( $data );
		bp_activity_update_meta( $activity_id, 'member_avatar_image', $avatar_image_base64 );
		bp_activity_update_meta( $activity_id, 'member_avatar_image_name', $title );
	}
}

if ( function_exists( 'buddypress' ) && version_compare( buddypress()->version, '10.0.0', '<' ) && ! isset( buddypress()->buddyboss ) ) {
	add_action( 'bp_activity_add', 'buddyx_bp_avatar_activity_add', 20, 2 );
}

/**
 * Function will add feature image for blog post in the activity feed content.
 *
 * @param string $content
 * @param int    $blog_post_id
 *
 * @return string $content
 *
 * @since 3.4.0
 */
function buddyx_add_feature_image_blog_post_as_activity_content_callback( $content, $blog_post_id ) {
	if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {
		if ( ! empty( $blog_post_id ) && ! empty( get_post_thumbnail_id( $blog_post_id ) ) ) {
			$content .= sprintf( ' <a class="buddyx-post-img-link" href="%s"><img src="%s" /></a>', esc_url( get_permalink( $blog_post_id ) ), esc_url( wp_get_attachment_image_url( get_post_thumbnail_id( $blog_post_id ), 'full' ) ) );
		}
	}

	return $content;
}

add_filter( 'buddyx_add_feature_image_blog_post_as_activity_content', 'buddyx_add_feature_image_blog_post_as_activity_content_callback', 10, 2 );

add_action( 'bp_before_activity_activity_content', 'buddyx_bp_blogs_activity_content_set_temp_content' );

/**
 * Function which set the temporary content on the blog post activity.
 *
 * @since 3.4.0
 */
function buddyx_bp_blogs_activity_content_set_temp_content() {

	if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {

		global $activities_template;

		$activity = $activities_template->activity;
		if ( ( 'blogs' === $activity->component ) && isset( $activity->secondary_item_id ) && 'new_blog_' . get_post_type( $activity->secondary_item_id ) === $activity->type ) {
			$content = get_post( $activity->secondary_item_id );
			// If we converted $content to an object earlier, flip it back to a string.
			if ( is_a( $content, 'WP_Post' ) ) {
				$activities_template->activity->content = '&#8203;';
			}
		} elseif ( 'blogs' === $activity->component && 'new_blog_comment' === $activity->type && $activity->secondary_item_id && $activity->secondary_item_id > 0 ) {
			$activities_template->activity->content = '&#8203;';
		}
	}
}

add_filter( 'bp_get_activity_content_body', 'buddyx_bp_blogs_activity_content_with_read_more', 9999, 2 );

/**
 * Function which set the content on activity blog post.
 *
 * @param $content
 * @param $activity
 *
 * @return string
 *
 * @since 3.4.0
 */
function buddyx_bp_blogs_activity_content_with_read_more( $content, $activity ) {

	if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {

		if ( ( 'blogs' === $activity->component ) && isset( $activity->secondary_item_id ) && 'new_blog_' . get_post_type( $activity->secondary_item_id ) === $activity->type ) {
			$blog_post = get_post( $activity->secondary_item_id );
			// If we converted $content to an object earlier, flip it back to a string.
			if ( is_a( $blog_post, 'WP_Post' ) ) {
				$content_img = apply_filters( 'buddyx_add_feature_image_blog_post_as_activity_content', '', $blog_post->ID );
				$post_title  = sprintf( '<a class="buddyx-post-title-link" href="%s"><span class="buddyx-post-title">%s</span></a>', esc_url( get_permalink( $blog_post->ID ) ), esc_html( $blog_post->post_title ) );
				$content     = bp_create_excerpt( bp_strip_script_and_style_tags( html_entity_decode( get_the_excerpt( $blog_post->ID ) ) ) );
				if ( false !== strrpos( $content, __( '&hellip;', 'buddyxpro' ) ) ) {
					$content = str_replace( ' [&hellip;]', '&hellip;', $content );
					$content = apply_filters_ref_array( 'bp_get_activity_content', array( $content, $activity ) );
					preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $content, $matches );
					if ( isset( $matches ) && array_key_exists( 0, $matches ) && ! empty( $matches[0] ) ) {
						$iframe  = $matches[0];
						$content = strip_tags( preg_replace( '/<iframe.*?\/iframe>/i', '', $content ), '<a>' );

						$content .= $iframe;
					}
					$content = sprintf( '%1$s <div class="buddyx-content-wrp">%2$s %3$s</div>', $content_img, $post_title, wpautop( $content ) );
				} else {
					$content = apply_filters_ref_array( 'bp_get_activity_content', array( $content, $activity ) );
					$content = strip_tags( $content, '<a><iframe><img><span><div>' );
					preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $content, $matches );
					if ( isset( $matches ) && array_key_exists( 0, $matches ) && ! empty( $matches[0] ) ) {
						$content = $content;
					}
					$content = sprintf( '%1$s <div class="buddyx-content-wrp">%2$s %3$s</div>', $content_img, $post_title, wpautop( $content ) );
				}
			}
		} elseif ( 'blogs' === $activity->component && 'new_blog_comment' === $activity->type && $activity->secondary_item_id && $activity->secondary_item_id > 0 ) {
			$comment = get_comment( $activity->secondary_item_id );
			$content = bp_create_excerpt( html_entity_decode( $comment->comment_content ) );
			if ( false !== strrpos( $content, __( '&hellip;', 'buddyxpro' ) ) ) {
				$content     = str_replace( ' [&hellip;]', '&hellip;', $content );
				$append_text = apply_filters( 'bp_activity_excerpt_append_text', __( ' Read more', 'buddyxpro' ) );
				$content     = wpautop( sprintf( '%1$s<span class="activity-blog-post-link"><a href="%2$s" rel="nofollow">%3$s</a></span>', $content, get_comment_link( $activity->secondary_item_id ), $append_text ) );
			}
		}
	}

	return $content;
}

/**
 * Added buddypress activity types activity embed css.
 *
 * @param $content
 * @param $activity
 *
 * @return string
 *
 * @since 6.0.0
 */
function buddyx_activity_embed_add_inline_styles() {
	?>
	<style type="text/css">
        .buddyx-user-preview {
			position: relative;
			margin-top: 0;
		}

		.buddyx-user-preview .buddyx-user-preview-cover img {
			width: 100%;
			height: 132px;
			object-fit: cover;
			background: #555;
		}

		.new_cover_photo .buddyx-user-preview .buddyx-user-preview-cover img,
		.new_group_cover_photo .buddyx-user-preview .buddyx-user-preview-cover img {
			height: 100%;
			min-height: 225px;
			background: transparent;
		}

		.profile.new_avatar .buddyx-user-preview .buddyx-user-preview-cover img {
			font-size: 0;
			height: auto;
			background: transparent;
			opacity: 0;
			visibility: hidden;
		}

		.profile.new_avatar .buddyx-user-preview .buddyx-user-avatar-content {
			margin: 0;
		}

		.buddyx-preview-widget .user-short-description {
			padding-top: 44px;
		}

		.buddyx-short-description {
			position: relative;
			padding-top: 62px;
		}

		.buddyx-user-avatar-content {
			margin-top: -75px;
			margin-bottom: 15px;
		}

		.buddyx-user-short-description {
			text-align: center;
		}

		.buddypress-wrap .activity-inner .buddyx-user-short-description-title p {
			margin-bottom: 0 !important;
		}

		.buddyx-user-avatar-content img {
			margin: auto;
		}

		@media( min-width: 544px) {
			.buddyx-user-stats {
				position: absolute;
				left: 0;
				bottom: 0;
			}
			.bp-nouveau .activity-list .activity-item .activity-content .activity-inner .buddyx-user-stats .buddyx-user-stat-title,
			.bp-nouveau .activity-list .activity-item .activity-content .activity-inner .buddyx-user-stats .buddyx-user-stat-text {
				margin-bottom: 0 !important;
			}
		}

		@media( max-width: 543px) {
			.buddyx-user-stats {
				text-align: center;
				margin-top: 10px;
			}
		}

		.bp-nouveau .activity-list .activity-item .activity-content .activity-inner .buddyx-user-stats .buddyx-user-stat-title,
		.bp-nouveau .activity-list .activity-item .activity-content .activity-inner .buddyx-user-stats .buddyx-user-stat-text {
			font-size: 14px;
			line-height: normal;
		}

		.bp-nouveau .activity-list .activity-item .activity-content .activity-inner .buddyx-user-stats .buddyx-user-stat-title {
			margin-bottom: 0 !important;
		}

		#buddypress .activity-content .activity-inner .buddyx-user-short-description .bp-profile-button a {
			margin: 0;
			padding: 0;
			color: inherit;
			background: transparent;
			border: 0;
			font-size: inherit;
			font-weight: normal;
		}

		@media(min-width: 544px) {
			.bp-profile-button {
				text-align: right;
				margin-top: -20px;
			}
		} 
	</style>
	<?php
}

add_action( 'embed_head', 'buddyx_activity_embed_add_inline_styles', 20 );

/**
 * Add buddyx signup terms privacy in register popup for bb platform
 */
function bp_nouveau_buddyx_signup_terms_privacy() {
	$page_ids = bp_core_get_directory_page_ids();
	$show_legal_agreement = bb_register_legal_agreement();

	$terms   = isset( $page_ids['terms'] ) ? $page_ids['terms'] : false;
	$privacy = isset( $page_ids['privacy'] ) ? $page_ids['privacy'] : (int) get_option( 'wp_page_for_privacy_policy' );

	// Do not show the page if page is not published.
	if ( false !== $terms && 'publish' !== get_post_status( $terms ) ) {
		$terms = false;
	}

	// Do not show the page if page is not published.
	if ( false !== $privacy && 'publish' !== get_post_status( $privacy ) ) {
		$privacy = false;
	}

	if ( ! $terms && ! $privacy ) {
		return false;
	}

	if ( ! empty( $terms )  && ! empty( $privacy ) ) {
		$terms_link   = '<a class="popup-modal-register popup-terms" href="' . esc_url( get_permalink($terms) ). '" target="_blank">' . get_the_title( $terms ) . '</a>';
		$privacy_link = '<a class="popup-modal-register popup-privacy" href="' . esc_url( get_permalink($privacy) ). '" target="_blank">' . get_the_title( $privacy ) . '</a>';
		?>
		<?php if ( $show_legal_agreement ) { ?>
			<div class="input-options checkbox-options">
				<div class="bp-checkbox-wrap">
					<input type="checkbox" name="legal_agreement" id="legal_agreement" value="1" class="bs-styled-checkbox">
					<label for="legal_agreement" class="option-label"><?php printf( __( 'I agree to the %1$s and %2$s.', 'buddyxpro' ), $terms_link, $privacy_link ); ?></label>
				</div>
			</div>
		<?php } else { ?>
			<p class="register-privacy-info">
				<?php printf( __( 'By creating an account you are agreeing to the %1$s and %2$s.', 'buddyxpro' ), $terms_link, $privacy_link ); ?>
			</p>
		<?php }
		
	} else if ( empty( $terms ) && ! empty ( $privacy ) ) {
		$privacy_link = '<a class="popup-modal-register popup-privacy" href="' . esc_url( get_permalink($privacy) ). '" target="_blank">' . get_the_title( $privacy ) . '</a>';
		?>
		<?php if ( $show_legal_agreement ) { ?>
			<div class="input-options checkbox-options">
				<div class="bp-checkbox-wrap">
					<input type="checkbox" name="legal_agreement" id="legal_agreement" value="1" class="bs-styled-checkbox">
					<label for="legal_agreement" class="option-label"><?php printf( __( 'I agree to the %s.', 'buddyxpro' ), $privacy_link ); ?></label>
				</div>
			</div>
		<?php } else { ?>
			<p class="register-privacy-info">
				<?php printf( __( 'By creating an account you are agreeing to the %s.', 'buddyxpro' ), $privacy_link ); ?>
			</p>
		<?php } 
		
	} else if ( ! empty ( $terms ) && empty ( $privacy ) ) {
		$terms_link = '<a class="popup-modal-register popup-terms" href="' . esc_url( get_permalink($terms) ). '" target="_blank">' . get_the_title( $terms ) . '</a>';
		?>
		<?php if ( $show_legal_agreement ) { ?>
			<div class="input-options checkbox-options">
				<div class="bp-checkbox-wrap">
					<input type="checkbox" name="legal_agreement" id="legal_agreement" value="1" class="bs-styled-checkbox">
					<label for="legal_agreement" class="option-label"><?php printf( __( 'I agree to the %s.', 'buddyxpro' ), $terms_link ); ?></label>
				</div>
			</div>
		<?php } else { ?>
			<p class="register-privacy-info">
				<?php printf( __( 'By creating an account you are agreeing to the %s.', 'buddyxpro' ), $terms_link ); ?>
			</p>
		<?php } 

	}

	if ( $show_legal_agreement ) {
	    do_action('bp_legal_agreement_errors' );
	}
}


add_filter( 'bp_nouveau_get_activity_entry_buttons', 'buddyx_theme_bp_nouveau_get_activity_entry_buttons', 99, 2 );
function buddyx_theme_bp_nouveau_get_activity_entry_buttons( $buttons, $activity_id ) {
	if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {		
		unset( $buttons['activity_delete'] );
	}	
	
	return $buttons;
}

/*
 * Replace Mark as Favirite to Like and Rrmove Favirite to Unlike
 */

add_filter('gettext', 'buddyx_bp_string_translate', 10, 3);
function buddyx_bp_string_translate( $translation, $text, $domain) {
	
	if ( $domain == 'buddypress' ) {
		if ( $text == 'Remove Favorite' ) {
			$translation = esc_html__( 'Unlike', 'buddyxpro');
		}
		
		if ( $text == 'Mark as Favorite' ) {
			$translation = esc_html__( 'Like', 'buddyxpro');
		}
		
		if ( $text == 'My Favorites' ) {
			$translation = esc_html__( 'Likes', 'buddyxpro');
		}
	}
	
	return $translation;
}


function bp_nouveau_activity_entry_dropdown_toggle_buttons( $args = array() ) {
	$output = join( ' ', bb_nouveau_get_activity_entry_dropdown_toggle_buttons( $args ) );

	ob_start();

	do_action( 'bp_activity_entry_dropdown_toggle_meta' );

	$output .= ob_get_clean();

	$has_content = trim( $output, ' ' );
	if ( ! $has_content ) {
		return;
	}

	if ( ! $args ) {
		$args = array( 'container_classes' => array( 'bp-activity-more-options-wrap', 'activity-meta' ) );
	}

	$output = sprintf( '<span class="bp-activity-more-options-action activity-meta action" data-balloon-pos="up" data-balloon="%s"><i class="fa fa-ellipsis-h"></i></span><div class="bp-activity-more-options">%s</div>', esc_html__( 'More Options', 'buddyxpro' ), $output );

	bp_nouveau_wrapper( array_merge( $args, array( 'output' => $output ) ) );
}

function bb_nouveau_get_activity_entry_dropdown_toggle_buttons( $args ) {
	 $buttons = array();
	if ( ! isset( $GLOBALS['activities_template'] ) ) {
		return $buttons;
	}

	$activity_id    = bp_get_activity_id();
	$activity_type  = bp_get_activity_type();
	$parent_element = '';
	$button_element = 'a';

	if ( ! $activity_id ) {
		return $buttons;
	}

	/*
	 * If the container is set to 'ul' force the $parent_element to 'li',
	 * else use parent_element args if set.
	 *
	 * This will render li elements around anchors/buttons.
	 */
	if ( isset( $args['container'] ) && 'ul' === $args['container'] ) {
		$parent_element = 'li';
	} elseif ( ! empty( $args['parent_element'] ) ) {
		$parent_element = $args['parent_element'];
	}

	$parent_attr = ( ! empty( $args['parent_attr'] ) ) ? $args['parent_attr'] : array();

	/*
	 * If we have an arg value for $button_element passed through
	 * use it to default all the $buttons['button_element'] values
	 * otherwise default to 'a' (anchor)
	 * Or override & hardcode the 'element' string on $buttons array.
	 *
	 */
	if ( ! empty( $args['button_element'] ) ) {
		$button_element = $args['button_element'];
	}

	// The delete button is always created, and removed later on if needed.
	$delete_args = array();

	/*
	 * As the delete link is filterable we need this workaround
	 * to try to intercept the edits the filter made and build
	 * a button out of it.
	 */
	if ( has_filter( 'bp_get_activity_delete_link' ) ) {
		preg_match( '/<a\s[^>]*>(.*)<\/a>/siU', bp_get_activity_delete_link(), $link );

		if ( ! empty( $link[0] ) && ! empty( $link[1] ) ) {
			$delete_args['link_text'] = $link[1];
			$subject                  = str_replace( $delete_args['link_text'], '', $link[0] );
		}

		preg_match_all( '/([\w\-]+)=([^"\'> ]+|([\'"]?)(?:[^\3]|\3+)+?\3)/', $subject, $attrs );

		if ( ! empty( $attrs[1] ) && ! empty( $attrs[2] ) ) {
			foreach ( $attrs[1] as $key_attr => $key_value ) {
				$delete_args[ 'link_' . $key_value ] = trim( $attrs[2][ $key_attr ], '"' );
			}
		}

		$delete_args = bp_parse_args(
			$delete_args,
			array(
				'link_text'   => '',
				'button_attr' => array(
					'link_id'         => '',
					'link_href'       => '',
					'link_class'      => '',
					'link_rel'        => 'nofollow',
					'data_bp_tooltip' => '',
				),
			),
			'nouveau_get_activity_entry_buttons'
		);
	}

	if ( empty( $delete_args['link_href'] ) ) {
		$delete_args = array(
			'button_element'  => $button_element,
			'link_id'         => '',
			'link_class'      => 'button item-button bp-secondary-action bp-tooltip delete-activity confirm',
			'link_rel'        => 'nofollow',
			'data_bp_tooltip' => _x( 'Delete', 'button', 'buddypress' ),
			'link_text'       => _x( 'Delete', 'button', 'buddypress' ),
			'link_href'       => bp_get_activity_delete_url(),
		);

		// If button element set add nonce link to data-attr attr
		if ( 'button' === $button_element ) {
			$delete_args['data-attr'] = bp_get_activity_delete_url();
			$delete_args['link_href'] = '';
		} else {
			$delete_args['link_href'] = bp_get_activity_delete_url();
			$delete_args['data-attr'] = '';
		}
	}

	$buttons['activity_delete'] = array(
		'id'                => 'activity_delete',
		'position'          => 35,
		'component'         => 'activity',
		'parent_element'    => $parent_element,
		'parent_attr'       => $parent_attr,
		'must_be_logged_in' => true,
		'button_element'    => $button_element,
		'button_attr'       => array(
			'id'              => $delete_args['link_id'],
			'href'            => $delete_args['link_href'],
			'class'           => $delete_args['link_class'],
			'data-bp-tooltip' => $delete_args['data_bp_tooltip'],
			'data-bp-nonce'   => $delete_args['data-attr'],
		),
		'link_text'         => sprintf( '<span class="bp-screen-reader-text">%s</span>', esc_html( $delete_args['data_bp_tooltip'] ) ),
	);

	// Add the Spam Button if supported
	if ( bp_is_akismet_active() && isset( buddypress()->activity->akismet ) && bp_activity_user_can_mark_spam() ) {
		$buttons['activity_spam'] = array(
			'id'                => 'activity_spam',
			'position'          => 45,
			'component'         => 'activity',
			'parent_element'    => $parent_element,
			'parent_attr'       => $parent_attr,
			'must_be_logged_in' => true,
			'button_element'    => $button_element,
			'button_attr'       => array(
				'class'           => 'bp-secondary-action spam-activity confirm button item-button bp-tooltip',
				'id'              => 'activity_make_spam_' . $activity_id,
				'data-bp-tooltip' => _x( 'Spam', 'button', 'buddypress' ),
			),
			'link_text'         => sprintf(
				/** @todo: use a specific css rule for this */
				'<span class="dashicons dashicons-flag" style="color:#a00;vertical-align:baseline;width:18px;height:18px" aria-hidden="true"></span><span class="bp-screen-reader-text">%s</span>',
				esc_html_x( 'Spam', 'button', 'buddypress' )
			),
		);

		// If button element, add nonce link to data attribute.
		if ( 'button' === $button_element ) {
			$data_element = 'data-bp-nonce';
		} else {
			$data_element = 'href';
		}

		$buttons['activity_spam']['button_attr'][ $data_element ] = wp_nonce_url(
			bp_get_root_domain() . '/' . bp_nouveau_get_component_slug( 'activity' ) . '/spam/' . $activity_id . '/',
			'bp_activity_akismet_spam_' . $activity_id
		);
	}
	/**
	 * Filter to add your buttons, use the position argument to choose where to insert it.
	 *
	 * @since BuddyBoss 1.7.2
	 *
	 * @param array $buttons     The list of buttons.
	 * @param int   $activity_id The current activity ID.
	 */
	$buttons_group = apply_filters( 'bb_nouveau_get_activity_entry_dropdown_toggle_buttons', $buttons, $activity_id );

	if ( ! $buttons_group ) {
		return $buttons;
	}

	// It's the first entry of the loop, so build the Group and sort it.
	if ( ! isset( bp_nouveau()->activity->entry_buttons ) || ! is_a( bp_nouveau()->activity->entry_buttons, 'BP_Buttons_Buddyx_Group' ) ) {
		$sort                                 = true;
		bp_nouveau()->activity->entry_buttons = new BP_Buttons_Buddyx_Group( $buttons_group );

		// It's not the first entry, the order is set, we simply need to update the Buttons Group.
	} else {
		$sort = false;
		bp_nouveau()->activity->entry_buttons->update( $buttons_group );
	}

	$return = bp_nouveau()->activity->entry_buttons->get( $sort );

	if ( ! $return ) {
		return array();
	}

	// Remove the Delete button if the user can't delete.
	if ( ! bp_activity_user_can_delete() ) {
		unset( $return['activity_delete'] );
	}

	do_action_ref_array( 'bb_nouveau_return_activity_entry_dropdown_toggle_buttons', array( &$return, $activity_id ) );

	return $return;
}

class BP_Buttons_Buddyx_Group {

	protected $group = array();

	public function __construct( $args = array() ) {
		foreach ( $args as $arg ) {
			$this->add( $arg );
		}
	}

	public function sort( $buttons ) {
		$sorted = array();

		foreach ( $buttons as $button ) {
			$position = 99;

			if ( isset( $button['position'] ) ) {
				$position = (int) $button['position'];
			}

			// If position is already taken, move to the first next available
			if ( isset( $sorted[ $position ] ) ) {
				$sorted_keys = array_keys( $sorted );

				do {
					$position += 1;
				} while ( in_array( $position, $sorted_keys, true ) );
			}

			$sorted[ $position ] = $button;
		}

		ksort( $sorted );
		return $sorted;
	}


	public function get( $sort = true ) {
		$buttons = array();

		if ( empty( $this->group ) ) {
			return $buttons;
		}

		if ( true === $sort ) {
			$this->group = $this->sort( $this->group );
		}

		foreach ( $this->group as $key_button => $button ) {
			// Reindex with ids.
			if ( true === $sort ) {
				$this->group[ $button['id'] ] = $button;
				unset( $this->group[ $key_button ] );
			}

			$buttons[ $button['id'] ] = bp_get_button( $button );
		}

		return $buttons;
	}


	public function update( $args = array() ) {
		$this->group = array();
		foreach ( $args as $id => $params ) {
			$this->set( $params );
		}
	}


	private function add( $args ) {
		$r = bp_parse_args(
			(array) $args,
			array(
				'id'                => '',
				'position'          => 99,
				'component'         => '',
				'must_be_logged_in' => true,
				'block_self'        => false,
				'parent_element'    => false,
				'parent_attr'       => array(),
				'button_element'    => 'a',
				'button_attr'       => array(),
				'link_text'         => '',
			),
			'buttons_group_constructor'
		);

		// Just don't set the button if a param is missing
		if ( empty( $r['id'] ) || empty( $r['component'] ) || empty( $r['link_text'] ) ) {
			return false;
		}

		$r['id'] = sanitize_key( $r['id'] );

		// If the button already exist don't add it
		if ( isset( $this->group[ $r['id'] ] ) ) {
			return false;
		}

		/*
		 * If, in bp_nouveau_get_*_buttons(), we pass through a false value for 'parent_element'
		 * but we have attributtes for it in the array, let's default to setting a div.
		 *
		 * Otherwise, the original false value will be passed through to BP buttons.
		 * @todo: this needs review, probably trying to be too clever
		 */
		if ( ( ! empty( $r['parent_attr'] ) ) && false === $r['parent_element'] ) {
			$r['parent_element'] = 'div';
		}

		$this->group[ $r['id'] ] = $r;
		return true;
	}
}




function bp_activity_get_share_count( $post_id, $is_bp = false ) {
	$share_count = 0;

	if ( empty( $post_id ) ) {
		return $share_count;
	}

	if ( ( function_exists( 'bp_is_activity_directory' ) && bp_is_activity_directory() ) || $is_bp == true  ) {
		$share_count = bp_activity_get_meta( $post_id, 'share_count', true );
	} elseif ( is_single() ) {
		$share_count = bp_activity_get_meta( $post_id, 'share_count', true );
	}

	if ( empty( $share_count ) ) {
		$share_count = 0;
	}

	return $share_count;
}



function bp_activity_get_post_comment_count( $post_id ) {
	$comment_count = get_comments_number( $post_id );
	if ( empty( $comment_count ) ) {
		$comment_count = 0;
	}
	return $comment_count;
}


/**
 *
 * Display three button, reaction, comment and reshare
 *
 * @since 6.5.0
 * @version 6.5.0
 */
add_action( 'buddyx_pro_post_comment_before', 'buddyx_pro_post_comment_box' );
function buddyx_pro_post_comment_box() {

	global $post, $wpdb;

	if ( get_post_type() != 'post' ) {
		return;
	}

	if ( ! class_exists( 'Buddypress_Reactions_Public' ) && ! class_exists( 'Buddypress_Share_Public' ) ) {
		return true;
	}

	$user_id   = get_current_user_id();
	$post_type = get_post_type();
	$post_id   = get_the_ID();

	if ( class_exists( 'Buddypress_Reactions_Public' ) ) {

		$query                = $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_reactions_shortcodes WHERE post_type = %s and front_render=%s limit 1', $post_type, 1 );
		$reactions_shortcodes = $wpdb->get_results( $query );
		$bp_reactions         = $reactions_shortcodes[0];
		$bp_shortcode_id      = $bp_reactions->id;
		$bp_reactions         = json_decode( $bp_reactions->options, true );
		$emojis               = $bp_reactions['emojis'];
		$animation            = $bp_reactions['animation'];

		$query            = $wpdb->prepare( 'SELECT emoji_id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE user_id = %s and post_id = %s and  post_type = %s and bprs_id=%s', $user_id, $post_id, $post_type, $bp_shortcode_id );
		$reacted_emoji_id = $wpdb->get_var( $query );

		$bp_reations_classes = 'bp-reactions-animation-' . $animation;
	}

	$bp_reshare_settings = get_site_option( 'bp_reshare_settings' );

	$share_count   = get_post_meta( $post_id, 'share_count', true );
	$share_count   = ( $share_count ) ? $share_count : 0;
	$comment_count = wp_count_comments( $post_id )->total_comments;

	?>
	
	<div class="buddyx-post-footer">
		<div class="buddyx-content-actions">
			<?php if ( function_exists( 'bpr_bp_post_type_reactions_meta' ) ) : ?>
				<div class="buddyx-content-action">
					<div id="bp-reactions-post-<?php echo esc_attr( $post_id ); ?>" class="reacted-count content-actions">
						<?php bpr_bp_post_type_reactions_meta( $post_id, $post_type, $bp_shortcode_id ); ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="buddyx-content-action">
				<div class="buddyx-meta-line">
					<p class="buddyx-meta-line-text">						
						<?php
						echo esc_html(
							sprintf(
								_nx(
									'%s Comment',
									'%s Comments',
									$comment_count,
									'Comment Count',
									'buddyxpro'
								),
								number_format_i18n( $comment_count )
							)
						);
						?>
					</p>
				</div>
				<div class="buddyx-meta-line">
					<p class="buddyx-meta-line-text">
						<span id="bp-activity-reshare-count-<?php echo esc_attr( get_the_ID() ); ?>" class="reshare-count bp-post-reshare-count"><?php echo esc_html( $share_count ); ?></span>
					<?php echo __( 'Shares', 'buddyxpro' ); ?></p>
				</div>				
			</div>
		</div>
		
		<?php if ( is_user_logged_in() ) : ?>
			<div class="buddyx-post-options">
				<?php if ( class_exists( 'Buddypress_Reactions_Public' ) ) : ?>
					<div class="buddyx-post-option-wrap">
						<div class="bp-activity-react-button-wrapper" id="post-reactions-<?php echo esc_attr( $post_id ); ?>">
							<div class="bp-activity-react-btn">
								<a class="button item-button bp-secondary-action bp-activity-react-button" rel="nofollow" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-type="<?php echo esc_attr( $post_type ); ?>"  data-bprs-id="<?php echo esc_attr( $bp_shortcode_id ); ?>">
									<div class="bp-post-react-icon bp-activity-react-icon">
										<?php if ( $reacted_emoji_id != '' && $reacted_emoji_id != 0 ) : ?>
											<img class="post-option-image" src="<?php echo get_buddypress_reaction_emoji( $reacted_emoji_id, 'svg' ); ?>" alt="">
										<?php else : ?>
											<div class="icon-thumbs-up">
												<i class="br-icon br-icon-smile"></i>
											</div>
										<?php endif; ?>
									</div>
									<span class="bp-react-button-text"><?php esc_html_e( 'React!', 'buddyxpro' ); ?></span>
								</a>
							</div>
							<div class="bp-activity-reactions reaction-options emoji-picker <?php echo esc_attr( $bp_reations_classes ); ?>">
								<?php if ( ! empty( $emojis ) ) : ?>
									<?php foreach ( $emojis as $emoji ) : ?>
										<div class="emoji-pick" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-type="<?php echo esc_attr( $post_type ); ?>" data-emoji-id="<?php echo $emoji; ?>" title="<?php echo $emoji; ?>" data-bprs-id="<?php echo esc_attr( $bp_shortcode_id ); ?>" >
											<div class="emoji-lottie-holder" style="display: none"></div>
											<figure itemprop="gif" class="emoji-svg-holder" style="background-image: url('<?php echo get_buddypress_reaction_emoji( $emoji, 'svg' ); ?>'"></figure>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
				
				<div class="buddyx-post-option-wrap active">
					<i class="fa fa-comment-dots"></i><p class="post-option-text"><?php esc_html_e( 'Comment', 'buddyxpro' ); ?></p>
				</div>
				
				<?php if ( class_exists( 'Buddypress_Share_Public' ) ) : ?>
					<div class="buddyx-post-option-wrap">
						
						<div class="bp-activity-post-share-btn">
							<a class="button item-button bp-secondary-action bp-activity-share-button" data-bs-toggle="modal" data-bs-target="#activity-share-modal" data-post-id="<?php echo esc_attr( $post_id ); ?>" rel="nofollow">
							<span class="bp-activity-reshare-icon">
								<i class="as-icon as-icon-share-square"></i>
							</span>
								<span class="bp-share-text"><?php esc_html_e( 'Share', 'buddyxpro' ); ?></span>							
							</a>
						</div>			
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}


add_action( 'buddyx_pro_before_comment_replay', 'buddyx_pro_post_comment_bp_reactions', 10, 2 );
function buddyx_pro_post_comment_bp_reactions( $comment_id, $comment ) {
	global $post, $wpdb;

	if ( ! class_exists( 'Buddypress_Reactions_Public' ) ) {
		return true;
	}

	if ( class_exists( 'Buddypress_Reactions_Public' ) ) {

		$user_id   = get_current_user_id();
		$post_type = get_post_type();
		$post_id   = get_the_ID();

		$query                = $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'bp_reactions_shortcodes WHERE post_type = %s and front_render=%s limit 1', $post_type, 1 );
		$reactions_shortcodes = $wpdb->get_results( $query );
		$bp_reactions         = $reactions_shortcodes[0];
		$bp_shortcode_id      = $bp_reactions->id;
		$bp_reactions         = json_decode( $bp_reactions->options, true );
		$emojis               = $bp_reactions['emojis'];
		$animation            = $bp_reactions['animation'];

		$query            = $wpdb->prepare( 'SELECT emoji_id FROM ' . $wpdb->prefix . 'bp_reactions_reacted_emoji WHERE user_id = %s and post_id = %s and  post_type = %s and bprs_id=%s', $user_id, $comment_id, 'post-comment', $bp_shortcode_id );
		$reacted_emoji_id = $wpdb->get_var( $query );

		$bp_reations_classes = 'bp-reactions-animation-' . $animation;
	}

	$bp_reshare_settings = get_site_option( 'bp_reshare_settings' );

	?>
	<div class="bp-react-post-comment">
		<?php bpr_bp_post_type_reactions_meta( $comment_id, 'post-comment', $bp_shortcode_id ); ?>
		<div id="bp-activity-comment-react-<?php echo esc_attr( $comment_id ); ?>" class="bp-activity-comment-react-button bp-activity-react-button-wrapper">
			<div class="bp-activity-react-btn">
				<a class="button item-button bp-secondary-action bp-activity-react-button" rel="nofollow" data-post-id="<?php echo esc_attr( $comment_id ); ?>" data-type="post-comment" data-bprs-id="<?php echo esc_attr( $bp_shortcode_id ); ?>">
					<?php esc_html_e( 'React!', 'buddyxpro' ); ?>
				</a>
			</div>
			<div class="bp-activity-reactions reaction-options emoji-picker <?php echo esc_attr( $bp_reations_classes ); ?>">
				<?php if ( ! empty( $emojis ) ) : ?>
					<?php foreach ( $emojis as $emoji ) : ?>
						<div class="emoji-pick" data-post-id="<?php echo esc_attr( $comment_id ); ?>" data-type="post-comment" data-emoji-id="<?php echo $emoji; ?>" title="<?php echo $emoji; ?>" data-bprs-id="<?php echo esc_attr( $bp_shortcode_id ); ?>" >
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

/**
 * Uses the $comment_type to determine which comment template should be used. Once the
 * template is located, it is loaded for use. Child themes can create custom templates based off
 * the $comment_type. The comment template hierarchy is comment-$comment_type.php,
 * comment.php.
 *
 * The templates are saved in $supreme->comment_template[$comment_type], so each comment template
 * is only located once if it is needed. Following comments will use the saved template.
 *
 * @param array   $comment              array of comment.
 * @param array   $args                 arguments of comments.
 * @param integer $depth                number of replies.
 */
function buddyx_pro_comments_callback( $comment, $args, $depth ) {

	$GLOBALS['comment']       = $comment;
	$GLOBALS['comment_depth'] = $depth;
	/* Get the comment type of the current comment. */
	$comment_type     = get_comment_type( $comment->comment_ID );
	$comment_template = array();

	/* Check if a template has been provided for the specific comment type.  If not, get the template. */
	if ( ! isset( $comment_template[ $comment_type ] ) ) {
		/* Create an array of template files to look for. */
		$templates = array( "comment-{$comment_type}.php" );
		/* If the comment type is a 'pingback' or 'trackback', allow the use of 'comment-ping.php'. */
		if ( 'pingback' == $comment_type || 'trackback' == $comment_type ) {
			$templates[] = 'comment-ping.php';
		}
		/* Add the fallback 'comment.php' template. */
		$templates[] = 'comment.php';
		/* Locate the comment template. */
		$template = locate_template( $templates );
		/* Set the template in the comment template array. */
		$comment_template[ $comment_type ] = $template;
	}
	/* If a template was found, load the template. */
	if ( ! empty( $comment_template[ $comment_type ] ) ) {
		require $comment_template[ $comment_type ];
	}
}