<?php

if(!class_exists('GoodGame'))
{
    class GoodGame {

        protected static $_instance = null;

        /* Return instance of Class */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        function __construct() {

            //set instance variable
            $this::$_instance = $this;

            /* Theme Specific Actions */
            add_action( 'after_setup_theme', array($this, 'setup' ));
            add_action( 'after_setup_theme', array($this, 'init_less' ));
            add_action( 'init', array($this, 'add_vc_blocks' ));
            add_action( 'wp_enqueue_scripts', array($this, 'add_stylesheets' ), 11 );
            add_action( 'wp_enqueue_scripts', array($this, 'add_scripts' ));
            add_action( 'wp_enqueue_scripts', array($this, 'restrict_loading_third_party_scripts'), 999);
            add_action( 'admin_enqueue_scripts', array($this, 'widget_upload_enqueue' ));
            add_action( 'wp_default_scripts', array($this, 'print_scripts_in_footer' ));
            add_action( 'parse_query', array($this, 'wpse_71157_parse_query' ));  //fix query parse bug
            add_action( 'widgets_init', array($this, 'add_widgets' ));
            add_action( 'add_meta_boxes', array($this, 'post_meta_boxes' ));
            add_action( 'save_post', array($this, 'post_save_postdata' ));

            add_action( 'wp_ajax_post_like', array($this, 'post_like' ));
            add_action( 'wp_ajax_nopriv_post_like', array($this, 'post_like' ));
            add_action( 'wp_head', array($this, 'add_schema_meta') );

            add_action( 'tgmpa_register', array($this, 'register_required_plugins' ));

            add_action( 'show_user_profile', array($this, 'extra_user_profile_fields'));
            add_action( 'edit_user_profile', array($this, 'extra_user_profile_fields'));
            add_action( 'personal_options_update', array($this, 'save_extra_user_profile_fields'));
            add_action( 'edit_user_profile_update', array($this, 'save_extra_user_profile_fields'));

            add_action( 'login_enqueue_scripts', array($this, 'custom_login_style'));
            add_action( 'login_enqueue_scripts', array($this, 'custom_login_logo'));


            /* Framework Actions */
            add_action( 'customize_register', 'goodgame_customize_register' );
            add_action( 'wp_head', 'goodgame_output_theme_version' );
            add_filter( 'less_vars', 'goodgame_map_visual_settings_to_less', 10, 2 );

            /* Filters */
            add_filter( 'wp_title', array($this, 'wp_title_for_home') , 10, 2 );
            add_filter( 'excerpt_length', array($this, 'custom_excerpt_length'), 999 );
            add_filter( 'excerpt_more', array($this, 'custom_excerpt_more'), 999 );
            add_filter( 'img_caption_shortcode', array($this, 'fix_image_margins'), 10, 3);
            add_filter( 'constellation_sidebar_args', array($this, 'setup_constellation_sidebar'));
            //add_filter( 'widget_title', array($this, 'widget_title_force'));
            add_filter( 'mega_menu_prepend_item', array($this, 'mega_menu_prepend_item'));
            add_filter( 'mega_menu_append_item', array($this, 'mega_menu_append_item'));
            add_filter( 'dynamic_sidebar_params',array($this, 'footer_custom_params'));
            add_filter( 'comment_form_fields', array($this, 'wpb_move_comment_field_to_bottom' ));
            add_filter( 'avatar_defaults',  array($this, 'avatar' ));

            /* Global - add body tag classes for thank you and add quoute pages */
            add_filter( 'body_class', array($this, 'theme_body_classes'));

            /* WooCommerce filters & actions */
            add_filter( 'woocommerce_output_related_products_args', array($this, 'woo_related_products_args'));
            add_filter( 'woocommerce_cross_sells_total', function() { return 4; });
            add_filter( 'woocommerce_show_page_title', function() { return false; });

            remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' ); //move cross sells lower
            add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

            /* Post Category slider AJAX call */
            add_action( 'wp_ajax_load_post_slider_items', array($this, 'load_post_slider_items') );
            add_action( 'wp_ajax_nopriv_load_post_slider_items', array($this, 'load_post_slider_items') );

            /* Twitch Stream widget AJAX call */
            add_action( 'wp_ajax_load_twitch_stream_widget', array($this, 'load_twitch_stream_widget') );
            add_action( 'wp_ajax_nopriv_load_twitch_stream_widget', array($this, 'load_twitch_stream_widget') );

            /* Twitch Stream VC block AJAX call */
            add_action( 'wp_ajax_load_twitch_stream_vc_block', array($this, 'load_twitch_stream_vc_block') );
            add_action( 'wp_ajax_nopriv_load_twitch_stream_vc_block', array($this, 'load_twitch_stream_vc_block') );

            add_action( 'admin_head', array($this, 'hide_plugin_admin_notifications'));

            /* Top Reviews by Platform AJAX call */
            if(class_exists('GoodGame_Platform'))
            {
                add_action( 'wp_ajax_load_top_reviews_widget_items', array($this, 'load_top_reviews_widget_items') );
                add_action( 'wp_ajax_nopriv_load_top_reviews_widget_items', array($this, 'load_top_reviews_widget_items') );
            }


            if(function_exists('vc_map'))
            {
                add_filter( 'vc_autocomplete_home_slider_item_slide_post_callback', 'vc_include_field_search', 10, 1);
            }
        }


        /*
         * Init main theme features, declare what's supported
         */
        function setup()
        {
            /* Make theme available for translation.
             * Translations can be added to the /languages/ directory.
             */
            load_theme_textdomain( 'planetshine-goodgame', get_template_directory() . '/languages' );
            if(is_child_theme())
            {
                load_child_theme_textdomain( 'planetshine-goodgame', get_stylesheet_directory() . '/languages' );
            }

            // This theme styles the visual editor with editor-style.css to match the theme style.
            add_theme_support( 'woocommerce' );
            add_theme_support( 'automatic-feed-links' );
            add_post_type_support( 'page', 'excerpt' );
            add_theme_support( 'post-thumbnails' );
            add_theme_support( 'title-tag' );
            // commented out since WP 4.9 - an error occurs which doesn't allow to display customizer's Publish button
            //            add_theme_support( 'custom-background');

            register_nav_menu( 'primary-menu', esc_html__( 'Primary Menu', 'planetshine-goodgame' ) );

            goodgame_add_image_sizes();

            if(!goodgame_is_woocommerce_active())
            {
                $this->remove_woocommerce_settings();
            }

            $sidebars = goodgame_get_sidebars();
            if(!empty($sidebars))
            {
                foreach($sidebars as $sidebar)
                {
                    register_sidebar( $sidebar );
                }
            }

            // WP-Polls template updates
            add_option('poll_template_voteheader', '<h2>%POLL_QUESTION%</h2><div id="polls-%POLL_ID%-ans" class="wp-polls-ans"><ul class="wp-polls-ul">');
            add_option('poll_template_votebody', '<li><label for="poll-answer-%POLL_ANSWER_ID%" class="radio"><input type="%POLL_CHECKBOX_RADIO%" id="poll-answer-%POLL_ANSWER_ID%" name="poll_%POLL_ID%" value="%POLL_ANSWER_ID%" /><span>%POLL_ANSWER%</span></label></li>');
            add_option('poll_template_votefooter', '</ul><div class="buttons"><a href="#" class="btn btn-default" onclick="poll_vote(%POLL_ID%); return false;">Vote</a><a href="#ViewPollResults" class="btn btn-default" onclick="poll_result(%POLL_ID%); return false;">Results</a></div></div>');
            add_option('poll_template_resultheader', '<h3>%POLL_QUESTION%</h3><div id="polls-%POLL_ID%-ans" class="wp-polls-ans"><ul class="wp-polls-ul">');
            add_option('poll_template_resultbody', '<li class="question-wrapper"><div class="question">%POLL_ANSWER% <span>%POLL_ANSWER_PERCENTAGE%% %POLL_ANSWER_VOTES% votes</span></div><div class="pollbar" style="width: %POLL_ANSWER_IMAGEWIDTH%%;" title="%POLL_ANSWER_TEXT% (%POLL_ANSWER_PERCENTAGE%% | %POLL_ANSWER_VOTES% Votes)"></div><div class="pollbar-100"></div></li>');
            add_option('poll_template_resultbody2', '<li class="question-wrapper"><div class="question"><b>%POLL_ANSWER%</b><span>%POLL_ANSWER_PERCENTAGE%% %POLL_ANSWER_VOTES% votes</span></div><div class="pollbar" style="width: %POLL_ANSWER_IMAGEWIDTH%%;" title="You Have Voted For This Choice - %POLL_ANSWER_TEXT% (%POLL_ANSWER_PERCENTAGE%% | %POLL_ANSWER_VOTES% Votes)"></div><div class="pollbar-100"></div></li>');
            add_option('poll_template_resultfooter', '</ul><p class="total">Total voters <strong>%POLL_TOTALVOTERS%</strong></p></div>');
            add_option('poll_template_resultfooter2', '</ul><p class="total">Total voters <strong>%POLL_TOTALVOTERS%</strong><a href="#VotePoll" class="btn btn-default vote" onclick="poll_booth(%POLL_ID%); return false;" title="Vote For This Poll">Vote</a></p></div>');
            add_option('poll_template_pollarchivelink', '<a href="%POLL_ARCHIVE_URL%" class="btn btn-default archive">Archive</a>');


        }


        /*
         *  Init LESS stylsheets
         */
        function init_less()
        {
            $upload_dir = wp_upload_dir();
            if(goodgame_gs('enable_css_mode') == 'off' && (is_writable($upload_dir['basedir']) && class_exists( 'wp_less' ) && function_exists('file_get_contents') && function_exists('file_put_contents')))
            {
                add_action( 'init', array( 'wp_less', 'instance' ) );
                update_option('goodgame_use_less', 1);
            }
            else
            {
                update_option('goodgame_use_less', 0);
            }
        }

        /*
         * Include stylsheets
         */
        function add_stylesheets()
        {
            wp_enqueue_style( 'goodgame-bootstrap', GOODGAME_CSS_URL . 'bootstrap.min.css' );
            wp_enqueue_style( 'goodgame-vendor', GOODGAME_CSS_URL . 'vendor.css' );

            if(get_option('goodgame_use_less') == 1)
            {
                wp_enqueue_style( 'goodgame-main-less', GOODGAME_LESS_URL . 'goodgame.less');
            }
            else
            {
                wp_enqueue_style( 'goodgame-main-css', GOODGAME_CSS_URL . 'goodgame.css' );
            }

            wp_enqueue_style( 'goodgame-style', get_bloginfo( 'stylesheet_url' ) );
            wp_enqueue_style( 'goodgame-google-fonts', goodgame_google_fonts_url(), array(), null );

            //don't use Constellation stylesheet
            wp_dequeue_style('cm-frontend');


            /* Add inline styles */

            //customizer settings
            ob_start();
            include get_template_directory() . '/theme/includes/' . 'customizer-settings.php';
            $customizer = ob_get_contents();
            ob_end_clean();

            wp_add_inline_style('goodgame-style', $customizer); //customizer

            wp_add_inline_style('goodgame-style', stripslashes(goodgame_gs('custom_css'))); //user css

            if(class_exists('GoodGame_Platform'))
            {
                wp_add_inline_style('goodgame-style', $this->get_platform_color_css()); //platform colors
            }
        }

        /*
         * Include scripts
         */
        function add_scripts()
        {
            wp_enqueue_script( 'goodgame-modernizr', GOODGAME_JS_URL . 'vendor/modernizr.min.js');

            wp_enqueue_script( 'goodgame-bootstrap', GOODGAME_JS_URL . 'vendor/bootstrap.min.js', array( 'jquery' ), false, true);

            if(is_single() || is_page())
            {
                wp_enqueue_script( 'jquery-ui-core' );
                wp_enqueue_script( 'jquery-ui-widget' );
                wp_enqueue_script( 'jquery-effects-slide');
                wp_enqueue_script( 'jquery-ui-draggable' );
                wp_enqueue_script( 'jquery-ui-mouse' );
                wp_enqueue_script( 'goodgame-touch', GOODGAME_JS_URL . 'vendor/jquery.ui.touch-punch.min.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget' ), false, true);
            }

            wp_enqueue_script( 'goodgame-select', GOODGAME_JS_URL . 'vendor/bootstrap-select.min.js');

            wp_enqueue_script( 'goodgame-inview', GOODGAME_JS_URL . 'vendor/jquery.inview.js', array( 'jquery' ), false, true);

            wp_enqueue_script( 'goodgame-owlcarousel', GOODGAME_JS_URL . 'vendor/owl.carousel.min.js', array( 'jquery' ), false, true);

            wp_enqueue_script( 'goodgame-cycle2', GOODGAME_JS_URL . 'vendor/jquery.cycle2.min.js', array( 'jquery' ), false, true);
            wp_enqueue_script( 'goodgame-jquery-mobile', GOODGAME_JS_URL . 'vendor/jquery.mobile.custom.min.js', array( 'jquery', 'goodgame-bootstrap' ), false, true);
            wp_enqueue_script( 'goodgame-social-button', GOODGAME_JS_URL . 'vendor/share-button.min.js', array( 'jquery' ), false, true);
            wp_enqueue_script( 'goodgame-mega-menu', GOODGAME_JS_URL . 'planetshine-mega-menu.js', array( 'jquery', 'goodgame-jquery-mobile' ), false, true);
            $theme_dependencies = array( 'jquery', 'goodgame-social-button', 'goodgame-inview', 'goodgame-owlcarousel' );

            if(strlen(trim(goodgame_gs('twitch_client_id'))) > 0)
            {
                $twitch_player_script_url = ((is_ssl()) ? 'https' : 'http') . '://player.twitch.tv/js/embed/v1.js';
                wp_enqueue_script( 'goodgame-twitch', $twitch_player_script_url, array( 'jquery' ), false, true);
                $theme_dependencies[] = 'goodgame-twitch';
            }


            wp_enqueue_script( 'goodgame-theme', GOODGAME_JS_URL . 'theme.js', $theme_dependencies, false, true);

            $ajax_object = array();
            $ajax_object['ajaxurl'] = admin_url( 'admin-ajax.php' );
            $ajax_object['slider_prev'] = __('Previous', 'planetshine-goodgame');
            $ajax_object['slider_next'] = __('Next', 'planetshine-goodgame');
            $ajax_object['enable_sidebar_affix'] = goodgame_gs('enable_sidebar_affix');

            if(function_exists('icl_get_languages'))
            {
                $ajax_object['lang'] = ICL_LANGUAGE_CODE;
            }

            wp_localize_script( 'goodgame-theme', 'goodgame_js_params', $ajax_object );

            //Add inline scripts
            wp_add_inline_script('goodgame-theme', stripslashes(goodgame_gs('custom_js')));
        }

        /*
         * Add schema info
         */
        function add_schema_meta() {

            global $post;

            if (have_posts()):while(have_posts()):the_post(); endwhile; endif;

            if (is_single()) : ?>
                <?php $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'goodgame_slider_image'); ?>
                <script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "NewsArticle",
    "headline": "<?php the_title(); ?>",
    "alternativeHeadline": "<?php echo goodgame_excerpt(20); ?>",

    "image": ["<?php if($img) { echo esc_url($img[0]); } ?>"],
    "datePublished": "<?php echo esc_attr(get_the_date('Y-m-dTH:i:s')); ?>",
    "description": "<?php echo goodgame_excerpt(50); ?>",
    "articleBody": "<?php echo strip_tags(strip_shortcodes(get_the_content())); ?>"
}
</script>
            <?php endif;
        }

        /*
         * Don't load woocommerce, bbpress and buddypress scripts on pages where they are not needed
         */
        function restrict_loading_third_party_scripts()
        {

            //Only load CSS and JS on Woocommerce pages
            if(function_exists('is_woocommerce'))
            {
                if(! is_woocommerce() && ! is_cart() && ! is_checkout() )
                {
                    //Dequeue scripts
                    wp_dequeue_script('woocommerce');
                    wp_dequeue_script('wc-add-to-cart');
                    wp_dequeue_script('wc-cart-fragments');
                    wp_dequeue_script('wc-add-to-cart');
                }
            }


            //bbpress scripts
            if ( class_exists('bbPress') )
            {
                if ( ! is_bbpress() )
                {
                    //Dequeue styles
                    wp_dequeue_style('bbp-default');
                    wp_dequeue_style( 'bbp_private_replies_style');

                    //Dequeue scripts
                    wp_dequeue_script('bbpress-editor');
                }
            }


            //buddypress
            if(function_exists('is_buddypress'))
            {
                if(!is_buddypress())
                {
                    wp_dequeue_style('bp-admin-bar');
                    wp_dequeue_style('bp-legacy-css');

                    //here was code that removed buddypress code when its not needed
                }
            }


            //wordpress popular posts
            wp_dequeue_style('wordpress-popular-posts');

        }

        /*
         * Initialize widgets
         */
        function add_widgets()
        {
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-post-tabs.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-social-share.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-top-reviews-by-platforms.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-newsletter-social.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-recent-post-list.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-tag-cloud.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-banner-large.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-about.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-post-categories.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-twitch-stream.php' );

            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-dropdown-featured-post-list.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-dropdown-category-posts.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-dropdown-post-list-with-heading.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-dropdown-post-list-with-sidemenu.php' );
            require_once( get_template_directory() . '/theme/includes/widgets/' . 'goodgame-dropdown-latest-videos.php' );


            register_widget( 'GoodGamePostTabs' );
            register_widget( 'GoodGameSocialShare' );
            register_widget( 'GoodGameRecentPostList' );
            register_widget( 'GoodGameSocialNewsletter' );
            register_widget( 'GoodGameTagCloud' );
            register_widget( 'GoodGameBannerLarge' );
            register_widget( 'GoodGameAbout' );
            register_widget( 'GoodGamePostCategories' );

            if(class_exists('GoodGame_Platform'))
            {
                register_widget( 'GoodGameTopReviewsByPlatforms' );
            }
            if(function_exists('perform_remote_request'))
            {
                register_widget( 'GoodGameTwitchStream' );
            }


            register_widget( 'GoodGameDropdownFeaturedPostList' );
            register_widget( 'GoodGameDropdownCategoryPosts' );
            register_widget( 'GoodGameDropdownPostListWithHeading' );
            register_widget( 'GoodGameDropdownPostListWithSidemenu' );
            register_widget( 'GoodGameDropdownLatestVideos' );

        }

        /*
         * Initialize VC blocks
         */
        function add_vc_blocks()
        {
            //if VC exists
            if(function_exists('vc_map'))
            {
                //register vc search post type
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'home-slider.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'home-slider-item.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'post-category-slider.php');
                if(class_exists('GoodGame_Platform'))
                {
                    require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'post-platform-slider.php');
                }
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'post-list-with-heading.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'post-slider-large.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'photo-galleries.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'compact-post-columns.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'post-list-large-items.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'post-list-compact-items.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'exclusive-post.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'title-block.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'review-summary.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'gallery-embed.php');
                if(function_exists('perform_remote_request'))
                {
                    require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'twitch-stream.php');
                    require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'twitch-videos.php');
                }

                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'banner970.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'banner728.php');
                require_once( get_template_directory() . '/theme/includes/vc-blocks/' . 'banner300.php');
            }
        }


        /*
         * Add meta boxes
         */
        function post_meta_boxes()
        {
            add_meta_box(
                'post_settings',
                esc_html__('Post settings', 'planetshine-goodgame'),
                array($this, 'post_inner_meta_box'),
                'post'
            );

            global $post;
            // don't show page settings for shop
            if(goodgame_is_woocommerce_active())
            {
                $shop_id = wc_get_page_id( 'shop' );
                if(wc_get_page_id( 'shop' ) == $post->ID) return;
            }

            //don't show page settings for blog & homepage
            if(get_option('show_on_front') == 'page')
            {
                //    if(get_option( 'page_on_front') == $post->ID) return;
                if(get_option( 'page_for_posts') == $post->ID) return;
            }

            add_meta_box(
                'page_settings',
                esc_html__('Page settings', 'planetshine-goodgame'),
                array($this, 'page_inner_meta_box'),
                'page'
            );

        }

        /*
         * Handle meta box save
         */
        function post_save_postdata( $post_id )
        {
            // Check if the current user is authorised to do this action.
            if ( 'post' == goodgame_get($_POST, 'post_type') || 'post' == goodgame_get($_GET, 'post_type') || 'product' == goodgame_get($_GET, 'post_type'))
            {
                if ( ! current_user_can( 'edit_page', $post_id ) )
                    return;
            }
            else
            {
                if ( ! current_user_can( 'edit_post', $post_id ) )
                    return;
            }

            //Nonce verfy
            if ( ! isset( $_POST['page_noncename'] ) || ! wp_verify_nonce( $_POST['page_noncename'], plugin_basename( __FILE__ ) ) )
                return;


            $post_ID = $_POST['post_ID'];
            $type = get_post_type($post_ID);
            if($type == 'post')
            {
                $post_style = trim(sanitize_text_field( $_POST['post_style'] ));
                $is_featured  = ( !empty($_POST['is_featured']) ? sanitize_text_field($_POST['is_featured']) : false);
                $image_size = trim(sanitize_text_field( $_POST['image_size'] ));
                $rating_stars = trim(sanitize_text_field( $_POST['rating_stars'] ));
                $video_url = trim(sanitize_text_field( $_POST['video_url'] ));

                update_post_meta($post_ID, 'post_style', $post_style);
                update_post_meta($post_ID, 'is_featured', $is_featured);
                update_post_meta($post_ID, 'image_size', $image_size);
                update_post_meta($post_ID, 'rating_stars', $rating_stars);
                update_post_meta($post_ID, 'video_url', $video_url);
            }
            else if($type == 'page')
            {
                if(get_option( 'page_on_front') != $post_ID)
                {
                    $show_share  = ( !empty($_POST['show_share']) ? sanitize_text_field($_POST['show_share']) : false);
                    $custom_sidebar = trim(sanitize_text_field( $_POST['custom_sidebar'] ));

                    update_post_meta($post_ID, 'show_share', $show_share);
                    update_post_meta($post_ID, 'custom_sidebar', $custom_sidebar);

                }

                if(isset($_POST['page_rev_slider']))
                {
                    $page_rev_slider = trim(sanitize_text_field( $_POST['page_rev_slider'] ));
                    update_post_meta($post_ID, 'page_rev_slider', $page_rev_slider);
                }

            }

        }

        /*
         * Get platform object by platform ID or first platform post belongs to
         */
        function get_platform($platform_id = 'all')
        {
            if(!class_exists('GoodGame_Platform'))
            {
                return false;
            }

            $result = array();
            if($platform_id != 'all' && intval($platform_id) > 0)
            {
                $result = get_term( intval($platform_id), 'platform' );
            }
            else
            {
                $terms = wp_get_post_terms( get_the_ID(), 'platform', array() );
                $result = $terms[0];
            }

            return $result;
        }

        /*
         * Get all platforms which belongs to current post
         */
        function get_post_platforms()
        {
            if(!class_exists('GoodGame_Platform'))
            {
                return false;
            }
            $result = wp_get_post_terms( get_the_ID(), 'platform', array() );

            return $result;
        }

        /*
         * Get all platform terms
         */
        function get_all_platforms() {
            if(!class_exists('GoodGame_Platform'))
            {
                return false;
            }
            $terms = get_terms('platform');
            return $terms;
        }

        /*
         * Get link to platform or blog page
         */
        function get_platform_view_more_link($platform_id = 'all') {
            if(!class_exists('GoodGame_Platform'))
            {
                return false;
            }
            if($platform_id != 'all' && intval($platform_id) > 0)
            {
                return get_term_link(intval($platform_id), 'platform');
            }
            else
            {
                return ( get_option( 'show_on_front' ) == 'page' ) ? get_permalink( get_option('page_for_posts' ) ) : home_url();
            }
        }

        /*
         * Get CSS string for platform tag background override
         */
        function get_platform_color_css()
        {
            $platform_css = '';
            $platforms = $this->get_all_platforms();

            if(!empty($platforms)){
                foreach($platforms as $plat){
                    $color = GoodGame_Platform::get_platform_color($plat->term_id);
                    if($color) { $platform_css .= '.tags .tag-' . esc_attr($plat->slug) . ':before { background-color: #' . $color . '; }' . "\n"; }
                }
            }

            return $platform_css;
        }

        /*
         * Get Twitch related data using Twitch API
         */
        function get_twitch_data($index = 'channels', $twitch_id = '')
        {
            if(!function_exists('perform_remote_request'))
            {
                return false;
            }

            $client_id = trim(goodgame_gs('twitch_client_id'));
            $twitch_id = trim($twitch_id);

            if(strlen($client_id) == 0 || strlen($twitch_id) == 0)
            {
                return false;
            }

            $url = 'https://api.twitch.tv/kraken/';

            if($index == 'streams')
            {
                $url .= 'streams/' . $twitch_id;
            }
            elseif($index == 'channels' )
            {
                $url .= 'channels/?id=' . $twitch_id;
            }
            elseif($index == 'videos')
            {
                $url .= 'channels/' . $twitch_id . '/videos?limit=100&broadcast_type=archive,highlight,upload';
            }
            elseif($index == 'follows')
            {
                $url .= 'users/' . $twitch_id . '/follows/channels?limit=1';
            }
            else
            {
                return false;
            }

            $option_handle = 'goodgame_cached_' . $index . '_' . sanitize_user($twitch_id);
            $cache_interval = 60;

            $cached = get_option($option_handle, json_encode(array()));
            $cached = json_decode($cached, true);

            if(empty($cached) || (!empty($cached) && $cached['timestamp'] < time()) || 1==1)  //if cache is older one hour
            {
                $version = 'Accept: application/vnd.twitchtv.v5+json';
                $client = 'Client-ID: ' . sanitize_key($client_id);
                $response = perform_remote_request($url, array($version, $client));

                if(!$response)
                {
                    return false;
                }

                $response_arr = json_decode($response, true);

                if (empty($response_arr) || !empty($response_arr['error']))
                {
                    return false;
                }

                $data = array();
                if($index == 'channels')
                {
                    $data = $response_arr['channels'][0];
                }
                elseif($index == 'streams' && !empty($response_arr['stream']))
                {
                    $data = $response_arr['stream'];
                }
                elseif($index == 'videos' && array_key_exists( '_total', $response_arr ))
                {
                    $data = $response_arr;
                }
                elseif($index == 'follows' && array_key_exists( '_total', $response_arr ))
                {
                    $data = $array = array( '_total' => $response_arr['_total'] );
                }
                else
                {
                    return false;
                }

                $cached = array('data' => $data, 'timestamp' => time() + $cache_interval);
                update_option($option_handle, json_encode($cached), false);
            }

            if(empty($cached) || empty($cached['data']))
            {
                return false;
            }
            else
            {
                return $cached['data'];
            }
        }

        /*
         * Load Twitch stream widget content
         */
        function load_twitch_stream_widget() {
            global $post;

            $username = isset( $_POST['username'] ) ? $_POST['username'] : '';
            $unique_id = uniqid();
            $twitch_id = $this->get_twitch_user_id($username);
            $stream = $this->get_twitch_data('streams', $twitch_id);
            $channel = $this->get_twitch_data('channels', $twitch_id);

            ob_start();

            if($channel): ?>
                <?php if($stream): ?>
                    <h2><a href="<?php if(!empty($channel['url'])){ echo esc_url($channel['url']); } ?>"><?php if(!empty($stream['game'])){ echo esc_html($stream['game']) . ' '; } echo esc_html__('Stream', 'planetshine-goodgame' ); ?></a></h2>
                <?php endif; ?>

                <?php if(!empty($channel['display_name'])): ?>
                    <div class="legend">
                        <a href="<?php if(!empty($channel['url'])){ echo esc_url($channel['url']); } ?>" class="user"><?php echo esc_html($channel['display_name']) . ' '; if($stream){ echo esc_html__('now playing', 'planetshine-goodgame' ) . '<span> ' . esc_html($stream['game']) . '</span>'; } else { echo esc_html__('is currently', 'planetshine-goodgame' ) . '<span> ' . esc_html__('offline', 'planetshine-goodgame' ) . '</span>'; } ?></a>
                    </div>
                <?php endif; ?>

                <div id="<?php echo esc_attr($unique_id); ?>-twitch-video" class="twitch-video-wrapper twitch-iframe" data-channel="<?php if(!empty($channel['name'])){ echo esc_attr($channel['name']); } ?>">
                    <?php if($stream): ?><span class="btn-circle btn-play"></span><?php endif; ?>
                    <?php
                    if($stream && !empty($stream['preview']['large']))
                    {
                        echo '<img src="' . esc_url($stream['preview']['large']). '">';
                    }
                    elseif(!empty($channel['video_banner']))
                    {
                        echo '<img src="' . esc_url($channel['video_banner']). '">';
                    }
                    ?>
                </div>

                <div class="legend">
                    <a href="#" class="views"><?php echo esc_html( number_format_i18n($channel['views']) ); ?></a>
                    <a href="#" class="hearts"><?php echo esc_html( number_format_i18n($channel['followers']) ); ?></a>
                </div>
            <?php else: ?>
                <p><?php esc_html_e('Something went wrong. Make sure "Planetshine GoodGame Theme Extension" plugin is installed. Check your Twitch client ID and streamer\'s username.', 'planetshine-goodgame'); ?></p>
            <?php endif;


            $html = ob_get_contents();
            ob_end_clean();

            die($html);
        }

        public function get_twitch_user_id($username)
        {

            if (!function_exists('perform_remote_request')) {
                return false;
            }

            $client_id = trim(goodgame_gs('twitch_client_id'));
            $username = trim($username);

            if (strlen($client_id) == 0 || strlen($username) == 0) {
                return false;
            }

            $url = 'https://api.twitch.tv/kraken/users/?login=' . $username;

            $option_handle = 'goodgame_cached_twitch_id_' . sanitize_user($username);
            $cache_interval = 60;

            $cached = get_option($option_handle, json_encode(array()));
            $cached = json_decode($cached, true);

            if (empty($cached) || (!empty($cached) && $cached['timestamp'] < time()) || 1 == 1)  //if cache is older one hour
            {
                $version = 'Accept: application/vnd.twitchtv.v5+json';
                $client = 'Client-ID: ' . sanitize_key($client_id);

                $response = perform_remote_request($url, array($version, $client));

                if (!$response) {
                    return false;
                }

                $response_arr = json_decode($response, true);

                if (empty($response_arr) || empty($response_arr['users']) || !empty($response_arr['error'])) {
                    return false;
                }

                $user = $response_arr['users'][0];
                $cached = array('data' => $user, 'timestamp' => time() + $cache_interval);
                update_option($option_handle, json_encode($cached), false);
            }

            if(empty($cached) || empty($cached['data']) || empty($cached['data']['_id']))
            {
                return false;
            }
            else
            {
                return $cached['data']['_id'];
            }
        }

        /*
         * Load Twitch Stream VC block content
         */
        function load_twitch_stream_vc_block() {
            global $post;

            $username = isset( $_POST['username'] ) ? $_POST['username'] : '';

            $unique_id = uniqid();

            $twitch_id = $this->get_twitch_user_id($username);
            $channel = $this->get_twitch_data('channels', $twitch_id);
            $stream = $this->get_twitch_data('streams', $twitch_id);
            $videos = $this->get_twitch_data('videos', $twitch_id);
            $follows = $this->get_twitch_data('follows', $twitch_id);

            ob_start();

            if($channel)
            {
                ?>
                <div class="twitch-user" <?php if(!empty($channel['profile_banner'])){ echo 'style="background-image: url(' . esc_url($channel['profile_banner']) . ')"'; } ?>>
                    <div class="user">
                        <?php if(!empty($channel['logo'])): ?>
                            <div class="image">
                                <a href="<?php if(!empty($channel['url'])){ echo esc_url($channel['url']); } ?>"><img src="<?php echo esc_url($channel['logo']); ?>"></a>
                            </div>
                        <?php endif; ?>
                        <div class="text">
                            <h3><a href="<?php if(!empty($channel['url'])){ echo esc_url($channel['url']); } ?>"><?php if(!empty($channel['display_name'])){ echo esc_html($channel['display_name']); } ?></a></h3>
                            <div class="legend">
                                <?php if($stream && !empty($channel['url']) && !empty($channel['game']))
                                {
                                    echo esc_html__('Streaming', 'planetshine-goodgame' ) . ' <a href="'. esc_url($channel['url']) .'">' . esc_html($channel['game']) . '</a>';
                                }
                                elseif($stream)
                                {
                                    echo esc_html__('Streaming', 'planetshine-goodgame' );
                                }
                                else
                                {
                                    echo esc_html__('Currently', 'planetshine-goodgame' ) . '<span> ' . esc_html__('offline', 'planetshine-goodgame' ) . '</span>';
                                }
                                ?>
                            </div>
                        </div>
                        <?php if(!empty($channel['url'])): ?>
                            <div class="buttons">
                                <a href="<?php echo esc_url($channel['url']) . '/videos/all' ?>" class="btn btn-default"><?php esc_html_e('Videos', 'planetshine-goodgame' ); ?> <span><?php if(!empty($videos)){ echo esc_html(number_format_i18n($videos['_total'])); } ?></span></a>
                                <a href="<?php echo esc_url($channel['url']) . '/followers' ?>" class="btn btn-default"><?php esc_html_e('Followers', 'planetshine-goodgame' ); ?> <span><?php echo esc_html(number_format_i18n($channel['followers'])); ?></span></a>
                                <a href="<?php echo esc_url($channel['url']) . '/following' ?>" class="btn btn-default"><?php esc_html_e('Following', 'planetshine-goodgame' ); ?> <span><?php if(!empty($follows)){ echo esc_html(number_format_i18n($follows['_total'])); } ?></span></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="title-default">
                    <div><span><?php esc_html_e('Now streaming', 'planetshine-goodgame' ); ?></span></div>
                </div>

                <div id="<?php echo esc_attr($unique_id); ?>-twitch-video" class="twitch-stream twitch-iframe" data-channel="<?php if(!empty($channel['name'])){ echo esc_attr($channel['name']); } ?>">
                    <?php
                    if($stream && !empty($stream['preview']['template']))
                    {
                        $video_banner = urldecode($stream['preview']['template']);
                        $video_banner = str_replace('{width}x{height}', '1170x660', $video_banner);
                        echo '<span href="#" class="btn-circle btn-play"></span><img src="' . esc_url($video_banner) . '">';
                    }
                    elseif(!empty($channel['video_banner']))
                    {
                        echo '<img src="' . esc_url($channel['video_banner']) . '">';
                    }
                    ?>
                </div>
                <?php
            }
            else
            {
                echo '<p>' . esc_html__('Something went wrong. Make sure "Planetshine GoodGame Theme Extension" plugin is installed. Check your Twitch client ID and streamer\'s username.', 'planetshine-goodgame') . '</p>';
            }

            $html = ob_get_contents();
            ob_end_clean();


            die($html);
        }

        /*
         * Callback for Top reviews select change
         */
        function load_top_reviews_widget_items() {
            global $post;

            ob_start();

            $items = goodgame_get_posts_by_platform($_POST['platform_id'], $_POST['interval'], intval($_POST['count']));
            ?>

            <?php if(!empty($items)) : ?>
                <?php foreach($items as $post) : ?>
                    <div class="row">
                        <div>
                            <?php
                            setup_postdata($post);
                            $post->platform_id = $_POST['platform_id'];
                            get_template_part('theme/templates/post-list-platform-item');
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="row">
                    <a href="<?php echo esc_url($this->get_platform_view_more_link($post->platform_id)); ?>" class="btn-default"><?php esc_html_e('View more games', 'planetshine-goodgame'); ?></a>
                </div>
            <?php else : ?>
                <p class="empty"><?php esc_html_e('No posts were found!', 'planetshine-goodgame'); ?></p>
            <?php endif; ?>

            <?php
            $html = ob_get_contents();
            ob_end_clean();

            die($html);
        }

        /*
         * Post inner meta box
         */
        function post_inner_meta_box( $post )
        {
            // Use nonce for verification
            wp_nonce_field( plugin_basename( __FILE__ ), 'page_noncename' );

            $is_featured = get_post_meta( $post->ID, $key = 'is_featured', $single = true );
            echo '<p>';
            echo '<input type="checkbox" id="is_featured" name="is_featured" ' . ($is_featured == true ? 'checked' : '')  . ' />';
            echo '<label for="is_featured">';
            esc_html_e("This post is featured", 'planetshine-goodgame');
            echo '</label>';
            echo '</p>';

            $post_style = get_post_meta( $post->ID, $key = 'post_style', $single = true );
            echo '<p>';
            echo '<label for="post_style">';
            esc_html_e("Post style:", 'planetshine-goodgame');
            echo '</label><br/>';
            echo '<select id="post_style" name="post_style" value="' . esc_attr($post_style) . '"" style="min-width: 300px;">'
                . '<option value="global" ' . ($post_style == 'global' ? 'selected="selected"' : '')  . '>' . esc_html__('Global theme setting', 'planetshine-goodgame') . '</option>'
                . '<option value="sidebar"' . ($post_style == 'sidebar' ? 'selected="selected"' : '')  . '>' . esc_html__('With sidebar', 'planetshine-goodgame') . '</option>'
                . '<option value="no-sidebar"' . ($post_style == 'no-sidebar' ? 'selected="selected"' : '')  . '>' . esc_html__('Full width', 'planetshine-goodgame') . '</option>'
                . '</select>';
            echo '</p>';

            $image_size = get_post_meta( $post->ID, $key = 'image_size', $single = true );
            echo '<p>';
            echo '<label for="image_size">';
            esc_html_e("Image mode:", 'planetshine-goodgame');
            echo '</label><br/>';
            echo '<select id="image_size" name="image_size" value="' . esc_attr($image_size) . '"" style="min-width: 300px;">'
                . '<option value="global" ' . ($image_size == 'global' ? 'selected="selected"' : '')  . '>' . esc_html__('Global theme setting', 'planetshine-goodgame') . '</option>'
                . '<option value="text_width"' . ($image_size == 'text_width' ? 'selected="selected"' : '')  . '>' . esc_html__('As wide as text', 'planetshine-goodgame') . '</option>'
                . '<option value="container_width"' . ($image_size == 'container_width' ? 'selected="selected"' : '')  . '>' . esc_html__('Site container width (requires sidebar)', 'planetshine-goodgame') . '</option>'
                . '<option value="full_screen"' . ($image_size == 'full_screen' ? 'selected="selected"' : '')  . '>' . esc_html__('Full screen (featured) ', 'planetshine-goodgame') . '</option>'
                . '<option value="no_image"' . ($image_size == 'no_image' ? 'selected="selected"' : '')  . '>' . esc_html__('No image', 'planetshine-goodgame') . '</option>'
                . '<option value="video"' . ($image_size == 'video' ? 'selected="selected"' : '')  . '>' . esc_html__('Video', 'planetshine-goodgame') . '</option>'
                . '<option value="video_autoplay"' . ($image_size == 'video_autoplay' ? 'selected="selected"' : '')  . '>' . esc_html__('Video with autoplay', 'planetshine-goodgame') . '</option>'
                . '</select>';
            echo '</p>';


            $video_url = get_post_meta( $post->ID, $key = 'video_url', $single = true );
            echo '<p>';
            echo '<label for="video_url">';
            esc_html_e("Video url (Optional. Used when image mode - video is set):", 'planetshine-goodgame');
            echo '</label><br/>';
            echo '<input type="text" id="video_url" name="video_url" value="' . $video_url . '" style="min-width: 300px;"/>';
            echo '</p>';

            $rating_stars = get_post_meta( $post->ID, $key = 'rating_stars', $single = true );
            echo '<p>';
            echo '<label for="rating_stars">';
            esc_html_e("Rating (for reviews):", 'planetshine-goodgame');
            echo '</label><br/>';
            echo '<select id="rating_stars" name="rating_stars" value="' . esc_attr($post_style) . '"" style="min-width: 300px;">'
                . '<option value="disabled" ' . ($rating_stars == 'disabled' ? 'selected="selected"' : '')  . '>Disabled</option>'
                . '<option value="0"' . ($rating_stars == '0' ? 'selected="selected"' : '')  . '>0</option>'
                . '<option value="5"' . ($rating_stars == '5' ? 'selected="selected"' : '')  . '>0.5</option>'
                . '<option value="10"' . ($rating_stars == '10' ? 'selected="selected"' : '')  . '>1</option>'
                . '<option value="15"' . ($rating_stars == '15' ? 'selected="selected"' : '')  . '>1.5</option>'
                . '<option value="20"' . ($rating_stars == '20' ? 'selected="selected"' : '')  . '>2</option>'
                . '<option value="25"' . ($rating_stars == '25' ? 'selected="selected"' : '')  . '>2.5</option>'
                . '<option value="30"' . ($rating_stars == '30' ? 'selected="selected"' : '')  . '>3</option>'
                . '<option value="35"' . ($rating_stars == '35' ? 'selected="selected"' : '')  . '>3.5</option>'
                . '<option value="40"' . ($rating_stars == '40' ? 'selected="selected"' : '')  . '>4</option>'
                . '<option value="45"' . ($rating_stars == '45' ? 'selected="selected"' : '')  . '>4.5</option>'
                . '<option value="50"' . ($rating_stars == '50' ? 'selected="selected"' : '')  . '>5</option>'
                . '<option value="55"' . ($rating_stars == '55' ? 'selected="selected"' : '')  . '>5.5</option>'
                . '<option value="60"' . ($rating_stars == '60' ? 'selected="selected"' : '')  . '>6</option>'
                . '<option value="65"' . ($rating_stars == '65' ? 'selected="selected"' : '')  . '>6.5</option>'
                . '<option value="70"' . ($rating_stars == '70' ? 'selected="selected"' : '')  . '>7</option>'
                . '<option value="75"' . ($rating_stars == '75' ? 'selected="selected"' : '')  . '>7.5</option>'
                . '<option value="80"' . ($rating_stars == '80' ? 'selected="selected"' : '')  . '>8</option>'
                . '<option value="85"' . ($rating_stars == '85' ? 'selected="selected"' : '')  . '>8.5</option>'
                . '<option value="90"' . ($rating_stars == '90' ? 'selected="selected"' : '')  . '>9</option>'
                . '<option value="95"' . ($rating_stars == '95' ? 'selected="selected"' : '')  . '>9.5</option>'
                . '<option value="100"' . ($rating_stars == '100' ? 'selected="selected"' : '')  . '>10</option>'
                . '</select>';
            echo '</p>';

            echo '<p>' . esc_html__('Post ID', 'planetshine-goodgame') . ': <strong>' . $post->ID . '</strong></p>';
        }

        /*
         * Page inner meta box
         */
        function page_inner_meta_box( $post )
        {
            // Use nonce for verification
            wp_nonce_field( plugin_basename( __FILE__ ), 'page_noncename' );

            if(get_option( 'page_on_front') != $post->ID)
            {
                $show_share = get_post_meta( $post->ID, $key = 'show_share', $single = true );
                echo '<p>';
                echo '<input type="checkbox" id="show_share" name="show_share" ' . ($show_share == true ? 'checked' : '')  . ' />';
                echo '<label for="show_share">';
                esc_html_e("Show share icons", 'planetshine-goodgame');
                echo '</label>';
                echo '</p>';


                $page_sidebars =  goodgame_get_sidebars();
                if(!empty($page_sidebars))
                {
                    $custom_sidebar = get_post_meta( $post->ID, $key = 'custom_sidebar', $single = true );
                    echo '<p>';
                    echo '<label for="custom_sidebar">';
                    esc_html_e("Custom sidebar (for pages that have a sidebar):", 'planetshine-goodgame');
                    echo '</label><br/>';
                    echo '<select id="custom_sidebar" name="custom_sidebar" value="' . esc_attr($custom_sidebar) . '"" style="min-width: 300px;">';

                    echo '<option value="global">' . esc_html__('Global theme setting', 'planetshine-goodgame') . '</option>';
                    foreach($page_sidebars as $sidebar)
                    {
                        $selected = ($sidebar['id'] == $custom_sidebar ? 'selected="selected"' : '' );
                        echo '<option value="' . $sidebar['id'] . '"' . $selected .'>' . $sidebar['name'] . '</option>';
                    }

                    echo '</select>';
                    echo '</p>';
                }
            }

        }

        /*
         * Add extra fields to user profile in wp-admin. Mainly for socila profile urls
         */
        function extra_user_profile_fields($user)
        {
            ?>
            <h3><?php esc_html_e('Additional user information', 'planetshine-goodgame'); ?></h3>

            <table class="form-table">

                <tr>
                    <th><label for="position"><?php esc_html_e('Position', 'planetshine-goodgame'); ?></label></th>
                    <td>
                        <input type="text" name="position" id="position" value="<?php echo esc_attr( get_the_author_meta( 'position', $user->ID ) ); ?>" class="regular-text" /><br />
                        <span class="description"><?php esc_html_e('Users position in this magazine. For example "Editor in chief" or "Food critic"', 'planetshine-goodgame'); ?></span>
                    </td>
                </tr>

                <tr>
                    <th><label for="twitter"><?php esc_html_e('Twitter account', 'planetshine-goodgame'); ?></label></th>
                    <td>
                        <input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
                        <span class="description"><?php esc_html_e('Twitter account URL', 'planetshine-goodgame'); ?></span>
                    </td>
                </tr>

                <tr>
                    <th><label for="facebook"><?php esc_html_e('Facebook account', 'planetshine-goodgame'); ?></label></th>
                    <td>
                        <input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $user->ID ) ); ?>" class="regular-text" /><br />
                        <span class="description"><?php esc_html_e('Facebook account URL', 'planetshine-goodgame'); ?></span>
                    </td>
                </tr>

                <tr>
                    <th><label for="youtube"><?php esc_html_e('Youtube account', 'planetshine-goodgame'); ?></label></th>
                    <td>
                        <input type="text" name="youtube" id="youtube" value="<?php echo esc_attr( get_the_author_meta( 'youtube', $user->ID ) ); ?>" class="regular-text" /><br />
                        <span class="description"><?php esc_html_e('Youtube account URL', 'planetshine-goodgame'); ?></span>
                    </td>
                </tr>

                <tr>
                    <th><label for="gplus"><?php esc_html_e('Google+ account', 'planetshine-goodgame'); ?></label></th>
                    <td>
                        <input type="text" name="gplus" id="gplus" value="<?php echo esc_attr( get_the_author_meta( 'gplus', $user->ID ) ); ?>" class="regular-text" /><br />
                        <span class="description"><?php esc_html_e('Google+ account URL', 'planetshine-goodgame'); ?></span>
                    </td>
                </tr>

                <tr>
                    <th><label for="pinterest"><?php esc_html_e('Pinterest account', 'planetshine-goodgame'); ?></label></th>
                    <td>
                        <input type="text" name="pinterest" id="pinterest" value="<?php echo esc_attr( get_the_author_meta( 'pinterest', $user->ID ) ); ?>" class="regular-text" /><br />
                        <span class="description"><?php esc_html_e('Pinterest account URL', 'planetshine-goodgame'); ?></span>
                    </td>
                </tr>

                <tr>
                    <th><label for="instagram"><?php esc_html_e('Instagram account', 'planetshine-goodgame'); ?></label></th>
                    <td>
                        <input type="text" name="instagram" id="instagram" value="<?php echo esc_attr( get_the_author_meta( 'instagram', $user->ID ) ); ?>" class="regular-text" /><br />
                        <span class="description"><?php esc_html_e('Instagram account URL', 'planetshine-goodgame'); ?></span>
                    </td>
                </tr>

                <tr>
                    <th><label for="steam"><?php esc_html_e('Steam account', 'planetshine-goodgame'); ?></label></th>
                    <td>
                        <input type="text" name="steam" id="steam" value="<?php echo esc_attr( get_the_author_meta( 'steam', $user->ID ) ); ?>" class="regular-text" /><br />
                        <span class="description"><?php esc_html_e('Steam account URL', 'planetshine-goodgame'); ?></span>
                    </td>
                </tr>

            </table>
            <?php
        }

        /*
         * Save additional user field content
         */
        function save_extra_user_profile_fields( $user_id ) {

            if ( !current_user_can( 'edit_user', $user_id ) )
            {
                return false;
            }

            update_user_meta( $user_id, 'position', $_POST['position'] );
            update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
            update_user_meta( $user_id, 'facebook', $_POST['facebook'] );
            update_user_meta( $user_id, 'youtube', $_POST['youtube'] );
            update_user_meta( $user_id, 'gplus', $_POST['gplus'] );
            update_user_meta( $user_id, 'pinterest', $_POST['pinterest'] );
            update_user_meta( $user_id, 'instagram', $_POST['instagram'] );
            update_user_meta( $user_id, 'steam', $_POST['steam'] );
        }


        /*
         * Init Mega Menu sidebar
         */
        function setup_constellation_sidebar($args) {

            $args['before_widget'] = '<div id="%1$s" class="constellation-widget section %2$s">';
            $args['after_widget'] = '</div>';
            $args['before_title'] = '<div class="title-default"><div><span>';
            $args['after_title'] = '</span></div></div>';

            return $args;
        }

        /*
         * Custom excerpt lenght
         */
        function custom_excerpt_length( $length )
        {
            return 50;
        }

        /*
         * Custom excerpt more
         */
        function custom_excerpt_more( $more )
        {
            return '...';
        }


        /*
         * Include login stylsheets
         */
        function custom_login_style()
        {
            wp_enqueue_style( 'custom-login', GOODGAME_LESS_URL . 'wp-login.less' );
        }

        /*
         * Include custom logo in wp-login
         */
        function custom_login_logo()
        {
            if(goodgame_gs('use_image_logo') == 'image_logo') : ?>
                <style type="text/css">
                    #login h1 a, .login h1 a {
                        background-image: url(<?php echo esc_url(goodgame_get_attachment_src(goodgame_gs('logo_image'))); ?>);
                        padding-bottom: 30px;
                        background-size: contain;
                        width: auto;
                        max-width: 100%;
                        height: auto;
                    }
                </style>
            <?php endif;
        }


        /*
         * Description: removes the silly 10px margin from the new caption based images
         * Author: Justin Adie
         * Version: 0.1.0
         * Author URI: http://rathercurious.net
         */
        function fix_image_margins($x=null, $attr, $content)
        {
            extract(shortcode_atts(array(
                'id'    => '',
                'align'    => 'alignnone',
                'width'    => '',
                'caption' => ''
            ), $attr));

            if ( 1 > (int) $width || empty($caption) )
            {
                return $content;
            }

            if ( $id )
            {
                $id = 'id="' . $id . '" ';
            }

            return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . ((int) $width + 0) . 'px">'
                . $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
        }


        /*
         * Adjust the title tag output in homepage
         */
        function wp_title_for_home( $title, $sep ) {
            if ( is_feed() )
            {
                return $title;
            }

            global $page, $paged;

            // Add the blog name
            $title .= get_bloginfo( 'name', 'display' );

            // Add the blog description for the home/front page.
            $site_description = get_bloginfo( 'description', 'display' );
            if ( $site_description && ( is_home() || is_front_page() ) )
            {
                $title .= " $sep $site_description";
            }

            // Add a page number if necessary:
            if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
            {
                $title .= " $sep " . sprintf( esc_html__( 'Page %s', 'planetshine-goodgame' ), max( $paged, $page ) );
            }

            return $title;
        }

        /*
         * Remove WooCommerce from theme settings
         */
        function remove_woocommerce_settings()
        {
            global $_SETTINGS;
            if(!empty($_SETTINGS->admin_head['general']['children']['shop']))
            {
                unset($_SETTINGS->admin_head['general']['children']['shop']);
            }
            if(!empty($_SETTINGS->admin_body['general']['shop']))
            {
                unset($_SETTINGS->admin_body['general']['shop']);
            }
            if(!empty($_SETTINGS->active['page_types']['shop']))
            {
                unset($_SETTINGS->active['page_types']['shop']);
            }
            if(!empty($_SETTINGS->active['page_types']['product']))
            {
                unset($_SETTINGS->active['page_types']['product']);
            }
        }

        /*
         * Include scripts required by wordpress media upload used in widget fro special offers
         */
        function widget_upload_enqueue()
        {
            wp_enqueue_media();
            wp_enqueue_script('goodgame-widget-upload', GOODGAME_JS_URL. 'widget-upload.js', null, null, true);
        }

        /*
         * Print footer scripts
         */
        function print_scripts_in_footer( &$scripts)
        {
            if ( ! is_admin() )
            {
                $scripts->add_data( 'comment-reply', 'group', 1 );
            }
        }

        /*
         * Fix bug width wp_query parse
         */
        function wpse_71157_parse_query( $wp_query )
        {
            if ( $wp_query->is_post_type_archive && $wp_query->is_tax )
            {
                $wp_query->is_post_type_archive = false;
            }
        }

        /*
         * Force every widget to have non empty title
         */
        function widget_title_force($title)
        {
            if(empty($title))
            {
                $title = ' ';
            }

            return $title;
        }

        /*
         * Get a collection of popular posts
         */
        function get_popular_posts($range='monthly', $count=5)
        {
            //if popular post plugin class is defined
            if(function_exists('wpp_get_views'))
            {
                global $wpdb;

                if ( !$range || 'all' == $range )
                {
                    $querydetails = $wpdb->prepare("SELECT
                                               pop.postid FROM {$wpdb->prefix}popularpostsdata as pop,
                                               {$wpdb->prefix}posts as p WHERE pop.postid = p.ID
                                               AND p.post_type = \"post\"
                                               ORDER BY pop.pageviews DESC LIMIT %d", $count);
                }
                else
                {
                    $interval = "";

                    switch( $range ){
                        case "yesterday":
                            $interval = "1 DAY";
                            break;

                        case "daily":
                            $interval = "1 DAY";
                            break;

                        case "weekly":
                            $interval = "1 WEEK";
                            break;

                        case "monthly":
                            $interval = "1 MONTH";
                            break;

                        default:
                            $interval = "1 DAY";
                            break;
                    }

                    $now = current_time('mysql');
                    $querydetails = $wpdb->prepare("SELECT pop.postid FROM {$wpdb->prefix}popularpostssummary as pop,
                                                                              {$wpdb->prefix}posts as p WHERE pop.postid = p.ID
                                                                              AND pop.view_datetime > DATE_SUB('%s', INTERVAL $interval )
                                                                              AND p.post_type = \"post\"
                                                                              GROUP BY pop.postid
                                                                              ORDER BY SUM(pop.pageviews) DESC LIMIT %d",
                        $now,
                        $count
                    );
                }
                $result = $wpdb->get_results($querydetails);
                if (empty($result) )
                {
                    return false;
                }

                $double_check = array();
                // WPML support, get original post/page ID
                if ( defined('ICL_LANGUAGE_CODE') && function_exists('icl_object_id') ) {
                    global $sitepress;

                    if ( isset( $sitepress )) { // avoids a fatal error with Polylang

                        foreach($result as $key => &$item)
                        {
                            $new_id = icl_object_id( $item->postid, get_post_type( $item->postid ), false, ICL_LANGUAGE_CODE );
                            if($new_id && !isset($double_check[$new_id]))
                            {
                                $double_check[$new_id] = true;
                                $item->postid = $new_id;
                            }
                            else
                            {
                                unset($result[$key]);
                            }
                        }
                    }

                }

                return $result;
            }

            return false;
        }

        /*
         * Check if posts in the popular list this week
         */
        function is_post_hot($post_id)
        {
            //if popular post plugin class is defined
            if(function_exists('wpp_get_views'))
            {
                global $wpdb;

                $cached = get_option('goodgame_cached_popular_posts', json_encode(array()));
                $cached = json_decode($cached, true);
                $result = array();

                if(empty($cached) || (!empty($cached) && $cached['timestamp'] < time()))  //if cache is older one hour
                {
                    $table_name = $wpdb->prefix . "popularposts";
                    $interval = "1 WEEK";
                    $now = current_time('mysql');

                    $querydetails = $wpdb->prepare("SELECT pop.postid FROM {$table_name}summary as pop, {$wpdb->prefix}posts as p"
                        . " WHERE pop.postid = p.ID AND pop.view_datetime >  DATE_SUB('%s', INTERVAL $interval )"
                        . " AND p.post_type = \"post\""
                        . " GROUP BY pop.postid ORDER BY SUM(pop.pageviews)"
                        . " DESC LIMIT 5",
                        $now
                    );

                    $result = $wpdb->get_results($querydetails);

                    $data = array();
                    if(!empty($result))
                    {
                        foreach($result as $item)
                        {
                            $data[] = $item->postid;
                        }
                    }
                    $cached = array('data' => $data, 'timestamp' => time() + 60*60);
                    update_option('goodgame_cached_popular_posts', json_encode($cached));
                }

                if(empty($cached) || empty($cached['data']))
                {
                    return false;
                }

                if(in_array($post_id, $cached['data']))
                {
                    return true;
                }
            }

            return false;
        }

        /*
         * Get pageview count for a specific post
         */
        function get_post_pageviews($post_id = false) {

            global $wpdb;

            //if popular post plugin class is defined
            if(function_exists('wpp_get_views') && $post_id)
            {
                $table_name = $wpdb->prefix . "popularposts";
                $query = "SELECT SUM(pop.pageviews) as pageviews FROM {$table_name}summary as pop WHERE pop.postid = %d";
                $querydetails = $wpdb->prepare($query, $post_id);

                $result = $wpdb->get_row($querydetails);

                if(!empty($result))
                {
                    return $result->pageviews;
                }

                return 0;
            }
        }

        /*
         * Check if gallery index/item is open
         */
        function is_gallery()
        {
            if(is_post_type_archive('gallery') || is_singular('gallery'))
            {
                return true;
            }
            return false;
        }


        /*
         * Add Home item to start of mega menu
         */
        function mega_menu_prepend_item() {

            $prepend = '';

            return $prepend;
        }

        /*
         * Add Home item to end of mega menu
         */
        function mega_menu_append_item() {

            $append = '';

            /*
             if(goodgame_gs('show_menu_videos') == 'on')
             {
             $append .= '<li class="menu-item menu-item-type-post_type menu-item-object-page full-width dropdown"><a href="#" class="parent">' . '<i class="fa fa-video-camera"></i>' . '</a>' . $this->get_video_dropdown_content() . '</li>';
             }
             */

            if(goodgame_gs('show_menu_search') == 'on')
            {
                $append .= '<li class="menu-item menu-item-type-post_type menu-item-object-page search-launcher"><a href="#">' . '<i class="fa fa-search"></i>' . '</a>' . '</li>';
            }

            return $append;
        }

        /*
         * Get video dropdown content
         */
        function get_video_dropdown_content() {

            ob_start();

            the_widget('GoodGameDropdownLatestVideos', array(), array('title' => esc_html__('Latest posts with videos', 'planetshine-goodgame'), 'before_widget' => '<ul><li><div class="goodgame_dropdown_latest_videos">', 'after_widget' => '</div></li></ul>' ));

            $return = ob_get_contents();
            ob_end_clean();
            return $return;
        }

        /*
         * Check if post is checked in as featured
         */
        function post_is_featured($post_id = false) {

            global $post;
            if($post_id)
            {
                $post_item = get_post($post_id);
            }
            else
            {
                $post_item = $post;
            }

            if($post_item)
            {
                $is_featured = get_post_meta( $post_item->ID, $key = 'is_featured', $single = true );

                if($is_featured == 'on')
                {
                    return true;
                }
            }

            return false;
        }


        /*
         * Modify the count of related products in WC
         */
        function woo_related_products_args( $args )
        {
            $args['posts_per_page'] = 4; // 4 related products
            $args['columns'] = 4; // arranged in 2 columns
            return $args;
        }


        /*
         * Callback for post category/platform tab change
         */
        function load_post_slider_items()
        {
            global $post;
            ob_start();

            $count = intval($_POST['count']);
            $slug_text = $_POST['slug'];
            $unique_id = $_POST['unique_id'];

            $interval = $_POST['interval'];

            if($_POST['is_platform'] == 'true' )
            {
                $slug = get_term_by('slug', $slug_text, 'platform');
                GoodGame_Post_Platform_Slider::single_slider($unique_id, $slug, $count, $interval, true);
            }
            else
            {
                $slug = get_term_by('slug', $slug_text, 'category');
                GoodGame_Post_Category_Slider::single_slider($unique_id, $slug, $count, $interval, true);
            }

            $html = ob_get_contents();
            ob_end_clean();

            die($html);
        }


        /*
         * Callback for post like/dislike feature
         */
        static function post_like() {

            $data = -1;
            $type = (!empty($_POST['type']) ? intval($_POST['type']) : false);
            $id = (!empty($_POST['id']) ? intval($_POST['id']) : false);

            if($type && $id)
            {
                check_ajax_referer( 'post_like_' . $id );

                $post = get_post($id);

                if($post)
                {
                    $meta = get_post_meta($id);
                    $likes = (!empty($meta['post_likes']) ? abs(intval($meta['post_likes'][0])) : 0);
                    $dislikes = (!empty($meta['post_dislikes']) ? abs(intval($meta['post_dislikes'][0])) : 0);

                    if(empty($_COOKIE['goodgame_post_like_' . $id]))    //check if user has voted before
                    {
                        if($type == 1) //like
                        {
                            update_post_meta($id, 'post_likes', ++$likes);
                        }
                        elseif($type == 2) //dislike
                        {
                            update_post_meta($id, 'post_dislikes', ++$dislikes);
                        }
                    }
                    elseif(    //if this voted is different thant the original
                        !empty($_COOKIE['goodgame_post_like_' . $id])
                        &&
                        $_COOKIE['goodgame_post_like_' . $id] != $type
                    )
                    {
                        if($type == 1) //like
                        {
                            update_post_meta($id, 'post_likes', ++$likes);
                            update_post_meta($id, 'post_dislikes', --$dislikes);
                        }
                        elseif($type == 2) //dislike
                        {
                            update_post_meta($id, 'post_dislikes', ++$dislikes);
                            update_post_meta($id, 'post_likes', --$likes);
                        }
                    }

                    $data = array('likes' => $likes, 'dislikes' => $dislikes);
                }

                //set cookie to remember the vote
                setcookie("goodgame_post_like_" . $id, $type,  time() + (10 * 365 * 24 * 60 * 60), '/');

            }

            die(json_encode($data));
        }

        /*
         * Filter footer widget width to squeeze all items in bs 12 columns layout
         */
        function footer_custom_params($params) {

            $sidebar_id = $params[0]['id'];

            if ( $sidebar_id == 'footer_sidebar' ) {

                $total_widgets = wp_get_sidebars_widgets();
                $sidebar_widgets = count($total_widgets[$sidebar_id]);
                $params[0]['before_widget'] = str_replace('col-md-3', 'col-md-' . floor(12 / $sidebar_widgets), $params[0]['before_widget']);
            }

            return $params;
        }


        /*
         * Add body classes
         */
        function theme_body_classes($classes) {

            global $post;

            //only allow bg mode = boxed if image is set
            $bg_image = get_theme_mod('gg_background_image', goodgame_gs('gg_background_image'));
            if($bg_image)
            {
                $classes[] = 'boxed';
                $classes[] = 'custom-background';
            }
            else
            {
                //background mode
                $classes[] = get_theme_mod('background_mode', goodgame_gs('background_mode'));
            }

            //is the a featured post
            if(is_single())
            {
                if('full_screen' == goodgame_get_post_image_width($post->ID))
                {
                    $classes[] = 'featured-post';
                }
            }

            return $classes;
        }

        /*
         * Move comment textarea to be below other input fields
         */
        function wpb_move_comment_field_to_bottom( $fields ) {
            $comment_field = $fields['comment'];
            unset( $fields['comment'] );
            $fields['comment'] = $comment_field;
            return $fields;
        }

        /*
         * Determine if image gradients are enabled
         */
        public function get_image_gradient_class() {

            $gradient = get_theme_mod('use-image-gradients', goodgame_gs('use-image-gradients'));
            if($gradient)
            {
                echo ' image-fx';
            }
        }

        /*
         * Checks if current post is review
         */
        public static function is_review()
        {
            $stars = get_post_meta(get_the_ID(), 'rating_stars', true );
            if($stars !== '' && $stars !== 'disabled')
            {
                return true;
            }

            return false;
        }

        /*
         * Get rating star HTML
         */
        public static function get_rating_stars($label = false, $label_text = '', $loop_item = false)
        {
            if(self::is_review())
            {
                $post_id = get_the_ID();
                $stars = get_post_meta($post_id, 'rating_stars', true );
                $stars = $stars / 10;
                $stars_out = number_format($stars, 1);
                $stars_out = str_replace('.5', '<small>.5</small>', $stars_out);
                $stars_out = str_replace('.0', '<small>.0</small>', $stars_out);

                ?><div class="rating">
                <div class="radial-progress<?php if(is_single($post_id) && !($loop_item)){ echo ' big'; }?>" data-score="<?php echo $stars ?>">
                    <div class="circle">
                        <div class="mask full">
                            <div class="fill"></div>
                        </div>
                        <div class="mask half">
                            <div class="fill"></div>
                            <div class="fill fix"></div>
                        </div>
                    </div>
                    <div class="inset"<?php if(is_single($post_id)) : ?> itemprop="rating"<?php endif; ?>><?php echo wp_kses_post($stars_out); if($label) echo '<p>' . wp_kses_post($label_text) . '</p>'; ?></div>
                </div>
                </div><?php
            }
        }

        /*
         * add extra avatar
         */
        public static function avatar ($avatar_defaults) {
            $myavatar = "http://i.imgur.com/D0eZXDh.png";
            $avatar_defaults[$myavatar] = __('GoodGame Avatar', 'planetshine-goodgame');
            return $avatar_defaults;
        }

        /*
         * Register plugins for TGMPA
         */
        function register_required_plugins()
        {
            /**
             * Array of plugin arrays. Required keys are name and slug.
             * If the source is NOT from the .org repo, then source is also required.
             */
            $plugins = $this->get_bunlded_plugins();

            // Change this to your theme text domain, used for internationalising strings

            /**
             * Array of configuration settings. Amend each line as needed.
             * If you want the default strings to be available under your own theme domain,
             * leave the strings uncommented.
             * Some of the strings are added into a sprintf, so see the comments at the
             * end of each line for what each argument will be.
             */
            $config = array(
                'domain'               => 'planetshine-goodgame',             // Text domain - likely want to be the same as your theme.
                'default_path'         => '',                             // Default absolute path to pre-packaged plugins
                'menu'                 => 'install-required-plugins',     // Menu slug
                'parent_slug'       => 'themes.php',
                'has_notices'          => true,                           // Show admin notices or not
                'is_automatic'        => false,                           // Automatically activate plugins after installation or not
                'message'             => '',                            // Message to output right before the plugins table
                'strings'              => array(
                    'page_title'                                   => esc_html__( 'Install Required Plugins', 'planetshine-goodgame' ),
                    'menu_title'                                   => esc_html__( 'Install Plugins', 'planetshine-goodgame' ),
                    'installing'                                   => esc_html__( 'Installing Plugin: %s', 'planetshine-goodgame' ), // %1$s = plugin name
                    'oops'                                         => esc_html__( 'Something went wrong with the plugin API.', 'planetshine-goodgame' ),
                    'notice_can_install_required'                 => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'planetshine-goodgame' ), // %1$s = plugin name(s)
                    'notice_can_install_recommended'            => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'planetshine-goodgame' ), // %1$s = plugin name(s)
                    'notice_cannot_install'                      => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'planetshine-goodgame' ), // %1$s = plugin name(s)
                    'notice_can_activate_required'                => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'planetshine-goodgame' ), // %1$s = plugin name(s)
                    'notice_can_activate_recommended'            => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'planetshine-goodgame' ), // %1$s = plugin name(s)
                    'notice_cannot_activate'                     => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'planetshine-goodgame' ), // %1$s = plugin name(s)
                    'notice_ask_to_update'                         => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'planetshine-goodgame' ), // %1$s = plugin name(s)
                    'notice_cannot_update'                         => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'planetshine-goodgame' ), // %1$s = plugin name(s)
                    'install_link'                                   => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'planetshine-goodgame' ),
                    'activate_link'                               => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'planetshine-goodgame' ),
                    'return'                                       => esc_html__( 'Return to Required Plugins Installer', 'planetshine-goodgame' ),
                    'plugin_activated'                             => esc_html__( 'Plugin activated successfully.', 'planetshine-goodgame' ),
                    'complete'                                     => esc_html__( 'All plugins installed and activated successfully. %s', 'planetshine-goodgame' ), // %1$s = dashboard link
                    'nag_type'                                    => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
                )
            );

            tgmpa( $plugins, $config );
        }

        /*
         * Return list of bundled plugins
         */
        public function get_bunlded_plugins()
        {
            return array(

                // This is an example of how to include a plugin pre-packaged with a theme
                array(
                    'name'                     => 'Visual Composer', // The plugin name
                    'slug'                     => 'js_composer', // The plugin slug (typically the folder name)
                    'source'                   => get_template_directory() . '/theme/plugins/' . 'js_composer.zip', // The plugin source
                    'required'                 => true, // If false, the plugin is only 'recommended' instead of required
                    'version'                 => goodgame_get_bundled_plugin_version('js_composer'), // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                    'force_activation'         => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                    'force_deactivation'     => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                    'external_url'             => '', // If set, overrides default API URL and points to an external URL
                    'class'                    => 'Vc_Manager',
                ),
                array(
                    'name'                     => 'Planetshine GoodGame Theme Extension', // The plugin name
                    'slug'                     => 'planetshine-goodgame', // The plugin slug (typically the folder name)
                    'source'                   => get_template_directory() . '/theme/plugins/' . 'planetshine-goodgame.zip', // The plugin source
                    'required'                 => true, // If false, the plugin is only 'recommended' instead of required
                    'version'                 => '1.0.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                    'force_activation'         => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                    'force_deactivation'     => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                    'external_url'             => '', // If set, overrides default API URL and points to an external URL
                    'class'                    => 'GoodGame_Extension',
                ),
                array(
                    'name'                     => 'Regenerate Thumbnails', // The plugin name
                    'slug'                     => 'regenerate-thumbnails', // The plugin slug (typically the folder name)
                    'required'                 => true, // If false, the plugin is only 'recommended' instead of required
                    'version'                 => '',
                    'class'                    => 'RegenerateThumbnails',
                ),
                array(
                    'name'                     => 'Attachments', // The plugin name
                    'slug'                     => 'attachments', // The plugin slug (typically the folder name)
                    'required'                 => true, // If false, the plugin is only 'recommended' instead of required
                    'version'                 => '',
                    'class'                    => 'Attachments',
                ),
                array(
                    'name'                     => 'WordPress Popular Posts', // The plugin name
                    'slug'                     => 'wordpress-popular-posts', // The plugin slug (typically the folder name)
                    'required'                 => true, // If false, the plugin is only 'recommended' instead of required
                    'class'                    => '\WordPressPopularPosts\WordPressPopularPosts',
                ),
                array(
                    'name'                     => 'WooCommerce', // The plugin name
                    'slug'                     => 'woocommerce', // The plugin slug (typically the folder name)
                    'required'                 => false, // If false, the plugin is only 'recommended' instead of required
                    'version'                 => '',
                    'class'                    => 'WooCommerce',
                ),
                array(
                    'name'                     => 'bbPress', // The plugin name
                    'slug'                     => 'bbpress', // The plugin slug (typically the folder name)
                    'required'                 => false, // If false, the plugin is only 'recommended' instead of required
                    'version'                 => '',
                    'class'                    => 'bbPress',
                ),
                array(
                    'name'                     => 'buddyPress', // The plugin name
                    'slug'                     => 'buddypress', // The plugin slug (typically the folder name)
                    'required'                 => false, // If false, the plugin is only 'recommended' instead of required
                    'version'                 => '',
                    'class'                    => 'buddyPress',
                ),
                array(
                    'name'                     => 'Ultimate Member', // The plugin name
                    'slug'                     => 'ultimate-member', // The plugin slug (typically the folder name)
                    'required'                 => false, // If false, the plugin is only 'recommended' instead of required
                    'version'                 => '',
                    'class'                    => 'UM',
                ),
                array(
                    'name'                     => 'Contact Form 7', // The plugin name
                    'slug'                     => 'contact-form-7', // The plugin slug (typically the folder name)
                    'required'                 => false, // If false, the plugin is only 'recommended' instead of required
                    'version'                 => '',
                    'class'                    => 'WPCF7',
                ),
                array(
                    'name'                     => 'WP-Polls', // The plugin name
                    'slug'                     => 'wp-polls', // The plugin slug (typically the folder name)
                    'required'                 => false, // If false, the plugin is only 'recommended' instead of required
                    'version'                 => '',
                    'class'                    => 'WP_Widget_Polls',
                ),

                array(
                    'name'                     => 'Envato Market Plugin', // The plugin name
                    'slug'                     => 'envato-market', // The plugin slug (typically the folder name)
                    'source'                => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
                    'required'                 => true, // If false, the plugin is only 'recommended' instead of required
                    'force_activation'         => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                    'force_deactivation'     => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                    'class'                    => 'Envato_Market',
                ),
            );
        }


        /*-----------------------------------------------------------------------------------*/
        /* Admin CSS */
        /*-----------------------------------------------------------------------------------*/
        public function hide_plugin_admin_notifications()
        {
            echo '<style>';
            echo 'tr[data-slug="slider-revolution"] + .plugin-update-tr, .vc_license-activation-notice, .rs-update-notice-wrap, tr.plugin-update-tr.active#js_composer-update { display: none !important;}';
            echo '</style>';
        }

    }
}

function GoodGameInstance() {
    return GoodGame::instance();
}

/*
 * Order number for post-list-platform-item template
 */
$post_list_order = NULL;

/*
 * Visible platform & category tag count for post-dropdown-platforms-categories template
 */
$visible_platform_count = NULL;

/**
 * Force Visual Composer & Revslider to initialize as "built into the theme".
 */
if(function_exists('vc_set_as_theme')) { vc_set_as_theme(true); }

if(function_exists('set_revslider_as_theme')) { set_revslider_as_theme(); }

?>
