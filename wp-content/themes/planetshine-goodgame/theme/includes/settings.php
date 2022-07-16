<?php

/******************* DEFAULT DATA *******************/
/* Override this function in child theme to change image sizes */

if(!function_exists('goodgame_images_sizes'))
{
	function goodgame_images_sizes()
	{
		return array(
			'goodgame_post_list_item_small' => array( 60, 60, true ),
			'goodgame_post_list_item_medium_small' => array( 270, 270, true ),
			'goodgame_post_list_item_medium' => array( 389, 389, true ),
			'goodgame_post_list_item_large' => array( 808, 379, true ),
			'goodgame_post_single_small' => array( 808, 454, true ),
			'goodgame_post_single_medium' => array( 1170, 658, true ),
			'goodgame_post_single_full_screen' => array( 1920, 1080, true ),
			'goodgame_slider_image' => array( 750, 750, true ),
			'goodgame_featured_list_item_large' => array( 389, 540, true ),
			'goodgame_gallery_item_small' => array( 90, 90, true ),
			'goodgame_gallery_item_large' => array( 870, 640, true ),
			'goodgame_gallery_embed_item' => array( 404, 404, true ),
		);
	}
}

/******************* DEFAULT DATA *******************/

$auto_pages = array(
	'home' => array(
		'name' => 'Home',
		'slug' => 'home',
        'content' => '',
		'id' => '',
		'role' => 'front_page'
	),
	'blog' => array(
		'name' => 'Blog',
		'slug' => 'blog',
		'content' => '',
		'id' => '',
		'role' => 'posts'
	),
);

$sidebars = array(
    array(
        'name' => esc_html__('Default', 'planetshine-goodgame'),
        'id'   => 'default_sidebar',
        'description' => esc_html__('Default Sidebar', 'planetshine-goodgame'),
        'class' => '',
        'before_widget' => '<div class="row"><div id="%1$s" class="widget-default widget-sidebar %2$s">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<div class="title-default"><div><span>',
        'after_title'   => '</span></div></div>'
    ),
    array(
        'name' => esc_html__('Footer', 'planetshine-goodgame'),
        'id'   => 'footer_sidebar',
        'description' => esc_html__('Footer Sidebar', 'planetshine-goodgame'),
        'class' => '',
        'before_widget' => '<div id="%1$s" class="col-md-3 col-xs-12 widget-default widget-footer %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="title-default"><div><span>',
        'after_title'   => '</span></div></div>'
    ),
	array(
        'name' => esc_html__('Search Lightbox', 'planetshine-goodgame'),
        'id'   => 'search_sidebar',
        'description' => esc_html__('Search Lightbox Widgets', 'planetshine-goodgame'),
        'class' => '',
        'before_widget' => '<div id="%1$s" class="col-md-4 col-xs-12 widget-default widget-footer %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="title-default"><span>',
        'after_title'   => '</span></div>'
    ),
);

$page_types = array(
    'blog' => 'Blog',
    'single_post' => 'Single post',
    'categories' => 'Categories',
    'search' => 'Search Results',
    'archives' => 'Archives',
    'page' => 'Page',
    'shop' => 'Shop Catalog',
    'product' => 'Single product',
    'forum' => 'Forum (bbPress)',
	'buddypress' => 'Buddypress',
);

$post_categories = array('' => 'None');
$cat_objects = get_categories(array('type' => 'post'));
foreach($cat_objects as $cat)
{
    $post_categories[$cat->cat_ID] = $cat->name;
}

$custom_fonts = array(
    'general-font',
    'logo_font',
    'menu_font',
    'title-font'
);

$theme = wp_get_theme();
$version = $theme->get('Version');

/******************* STATIC *******************/

$_settings_static = array(
	'theme_name' => 'GoodGame',
	'theme_slug' => 'planetshine-goodgame',
	'theme_version' => $version,
	'theme_homepage' => '',
	'sync_url' => '',
    'image_sizes' => goodgame_images_sizes(),
    'page_types' => $page_types,
    'support_url' => 'http://planetshine.net/redirect.php?theme=goodgame-wp&v=' . $version,
);

$google_fonts = goodgame_get_all_google_fonts();

/******************* HIDDEN SETTINGS *******************/

$_settings_hidden = array(
	'auto_pages' => $auto_pages,
    'static_sidebars' => $sidebars,
	'sidebars'  => array(),
    'sidebar_template' => array(
        'name' => '',
        'id'   => '',
        'description' => '',
        'class' => '',
        'before_widget' => '<div class="row"><div id="%1$s" class="widget-default widget-sidebar %2$s">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<div class="title-default"><div><span>',
        'after_title'   => '</span></div></div>'
    ),
    'fonts' => $google_fonts,
    'custom_fonts' => $custom_fonts,
    'page_sidebars' => array(),
);


/***************** ADMIN SETTINGS *****************/

$_settings_admin_head = array(
    'general' => array(
		'name'     => 'General',
		'slug'     => 'general',
        'type'     => 'goodgame_option_list',
        'icon'     => 'cog',
        'children' => array(
            'logo_fav' => array(
                'name' => 'Logo',
                'slug' => 'logo_fav'
            ),
            'header' => array(
                'name' => 'Header',
                'slug' => 'header'
            ),
            'footer' => array(
                'name' => 'Footer',
                'slug' => 'footer'
            ),
            'blog' => array(
                'name' => 'Blog',
                'slug' => 'blog'
            ),
            'post' => array(
                'name' => 'Post',
                'slug' => 'post'
            ),
			'shop' => array(
                'name' => 'Shop',
                'slug' => 'shop'
            ),
			'gallery' => array(
                'name' => 'Gallery',
                'slug' => 'gallery'
            ),
			'social' => array(
                'name' => 'Social Networks',
                'slug' => 'social'
            ),
			'newsletter' => array(
                'name' => 'Newsletter',
                'slug' => 'newsletter'
            ),
			'twitch' => array(
                'name' => 'Twitch',
                'slug' => 'twitch'
            ),
			'css_mode' => array(
                'name' => 'CSS Mode',
                'slug' => 'css_mode',
				'description' => 'If your server encounters problems with using LESS for stylesheets, you can enable CSS Mode until resolving the issues. This mode is "read only" and visual editor settings will be ignored. See Theme Status page for more info about potential causes of issues.'
            ),
            'custom_code' => array(
                'name' => 'Custom Code',
                'slug' => 'custom_code'
            ),
        ),
	),
    'visual_editor' => array(
		'name'     => 'Visual Editor',
		'slug'     => 'visual_editor',
        'type'     => 'goodgame_visual_editor',
        'icon'     => 'adjust',
        'link'     => get_admin_url() . 'customize.php?return=' . get_admin_url() . 'admin.php?page=' . $_settings_static['theme_slug'] . '-admin',
        'children' => array(
            'visual_background' => array(
                'name' => 'Backgrounds',
                'slug' => 'visual_background',
                'priority' => 5
            ),
            'visual_header' => array(
                'name' => 'Header colors',
                'slug' => 'visual_header',
                'priority' => 6
            ),
			'visual_footer' => array(
                'name' => 'Footer colors',
                'slug' => 'visual_footer',
                'priority' => 7
            ),
            'visual_fonts' => array(
                'name' => 'Fonts',
                'slug' => 'visual_fonts',
                'priority' => 9
            ),
            'visual_colors' => array(
                'name' => 'Body colors',
                'slug' => 'visual_colors',
                'priority' => 8
            ),
        ),
    ),
	'setup' => array(
        'name'     => 'Status &amp; Import',
		'slug'     => 'setup',
        'type'     => 'goodgame_setup_section',
        'icon'     => 'wrench',
        'children' => array(
            'status' => array(
                'name'     => 'Status',
                'slug'     => 'status',
                'type'     => 'goodgame_status',
                'children' => array(
					'setup_checklist' => array(
                        'name' => 'Setup Checklist',
                        'slug' => 'setup_checklist'
                    ),
					'plugin_status' => array(
                        'name' => 'Plugin Status',
                        'slug' => 'plugin_status'
                    ),
					'version' => array(
                        'name' => 'Version',
                        'slug' => 'version'
                    ),
				)
            ),
            'install_pages' => array(
                'name'     => 'Install Pages',
                'slug'     => 'install_pages',
                'type'     => 'goodgame_install_pages',
                'children' => array()
            ),
            'demo_import' => array(
                'name'     => 'Demo Import',
                'slug'     => 'demo_import',
                'type'     => 'goodgame_demo_import',
                'children' => array()
            ),
            'backup_reset' => array(
                'name'     => 'Backup & Reset',
                'slug'     => 'backup_reset',
                'type'     => 'goodgame_backup_reset',
                'children' => array(
                    'backup_settings' => array(
                        'name' => 'Backup Settings',
                        'slug' => 'backup_settings'
                    ),
                    'import_settings' => array(
                        'name' => 'Import Settings',
                        'slug' => 'import_settings'
                    ),
                    'reset_settings' => array(
                        'name' => 'Reset Settings',
                        'slug' => 'reset_settings'
                    ),
                ),
            ),
        )
    ),
    'ads_manager' => array(
        'name'     => 'Advertisement',
		'slug'     => 'ads_manager',
        'type'     => 'goodgame_ads_manager',
        'icon'     => 'picture-o',
        'children' => array(
            'ads_manager' => array(
                'name' => 'Manage',
                'slug' => 'ads_manager',
                'children' => array(
                    '728x90' => array(
                        'name' => '728x90px',
                        'slug' => '728x90',
                    ),
					'970x90' => array(
                        'name' => '970x90px',
                        'slug' => '970x90',
                    ),
                    '468x60' => array(
                        'name' => '468x60px',
                        'slug' => '468x60',
                    ),
                    '300x300' => array(
                        'name' => '300x300px',
                        'slug' => '300x300',
                    ),
                    /* '150x125' => array(
                        'name' => '150x125px (x4)',
                        'slug' => '150x125',
                    ),*/
                ),
            ),
            'ad_locations' => array(
                'name' => 'Locations',
                'slug' => 'ad_locations',
                'children' => array(
                    'header_ad' => array(
                        'name' => 'Header',
                        'slug' => 'header_ad',
                    ),
                    'blog_ad' => array(
                        'name' => 'Blog index',
                        'slug' => 'blog_ad',
                    ),
                    'post_ad' => array(
                        'name' => 'Blog Post',
                        'slug' => 'post_ad',
                    ),
                    'gallery_ad' => array(
                        'name' => 'Gallery index',
                        'slug' => 'gallery_ad',
                    ),
                    'single_gallery_ad' => array(
                        'name' => 'Gallery item',
                        'slug' => 'single_gallery_ad',
                    ),
                ),
            ),
        )
    ),
    'sidebar_manager' => array(
		'name'     => 'Sidebar Manager',
		'slug'     => 'sidebar_manager',
        'type'     => 'goodgame_sidebar_manager',
        'icon'     => 'cube',
        'children' => array(
            'all_sidebars' => array(
                'name' => 'All sidebars',
                'slug' => 'all_sidebars',
            ),
            'create_sidebar' => array(
                'name' => 'Create sidebar',
                'slug' => 'create_sidebar',
            ),
            'manage_sidebars' => array(
                'name' => 'Manage sidebars',
                'slug' => 'manage_sidebars',
            )
        )
    ),
	'google_fonts' => array(
		'name'     => 'Google Fonts',
		'slug'     => 'google_fonts',
        'type'     => 'goodgame_google_fonts',
        'icon'     => 'font',
    ),
    'help_support' => array(
		'name'     => 'Help & Support',
		'slug'     => 'help_support',
        'type'     => 'goodgame_support_iframe',
        'icon'     => 'info-circle',
        'children' => array()
    )
);


$_settings_admin_body = array(
	'general' => array(
        'logo_fav' => array(
			'use_image_logo' => array(
                'slug' => 'use_image_logo',
                'title' => 'Logo type',
                'description' => '',
                'type' => 'select',
                'data' => array('image_logo' => 'Image logo', 'text_logo' => 'Textual logo'),
                'default' => 'text_logo',
            ),
            'logo_image' => array(
                'slug' => 'logo_image',
                'title' => 'Upload logo image',
                'description' => '',
                'type' => 'fileupload',
                'default' => '',
                'dependant' => 'use_image_logo use_image_logo=[image_logo]',
            ),
            'logo_image_alt' => array(
                'slug' => 'logo_image_alt',
                'title' => 'Logo image ALT text',
                'description' => '',
                'type' => 'textbox',
                'default' => '',
                'dependant' => 'use_image_logo use_image_logo=[image_logo]',
            ),
			'logo_max_width' => array(
                'slug' => 'logo_max_width',
                'title' => 'Max logo width (in pixels)',
                'description' => 'For example, "120px". Set to "none" to disable.',
                'type' => 'textbox',
                'default' => 'none',
                'dependant' => '',
            ),
        ),
        'header' => array(
			'show_header_dock' => array(
                'slug' => 'show_header_dock',
                'title' => 'Show top header row',
                'description' => '',
                'type' => 'checkbox',
                'default' => 'on',
            ),
			'show_header_social' => array(
                'slug' => 'show_header_social',
                'title' => 'Show header social icons',
                'description' => '',
                'type' => 'checkbox',
                'default' => 'on',
				'dependant' => 'show_header_dock',
            ),
			'show_header_login' => array(
                'slug' => 'show_header_login',
                'title' => 'Show header login',
                'description' => '',
                'type' => 'checkbox',
                'default' => 'on',
				'dependant' => 'show_header_dock',
            ),
			'show_trending' => array(
                'slug' => 'show_trending',
                'title' => 'Show trending news ticker',
                'description' => '',
                'type' => 'checkbox',
                'default' => '',
				'dependant' => 'show_header_dock',
            ),
			'trending_source' => array(
                'slug' => 'trending_source',
                'title' => 'Source for trending news ticker',
                'description' => '',
                'type' => 'select',
                'data' => array('category' => 'Category', 'popular' => 'Popular posts'),
                'default' => 'popular',
                'dependant' => 'show_trending',
            ),
			'trending_category' => array(
                'slug' => 'trending_category',
                'title' => 'Post category for trending news ticker',
                'description' => '',
                'type' => 'select',
                'data' => $post_categories,
                'default' => '',
                'dependant' => 'show_trending trending_source trending_source=[category]',
            ),
            'trending_title' => array(
                'slug' => 'trending_title',
                'title' => 'News ticker title',
                'description' => '',
                'type' => 'textbox',
                'default' => 'Trending',
                'dependant' => 'show_trending',
            ),
			'header_layout' => array(
				'slug' => 'header_layout',
                'title' => 'Header layout',
                'description' => '',
                'type' => 'select',
                'data' => array(
					'logo_left_banner_right' => 'Logo on left, Banner on right',
					'logo_middle' => 'Logo in middle',
					'banner_left_logo_right' => 'Banner on left, Logo on right',
				),
                'default' => 'logo_middle',
                'dependant' => '',
			),
			/*'show_menu_videos' => array(
                'slug' => 'show_menu_videos',
                'title' => 'Show header menu video',
                'description' => '',
                'type' => 'checkbox',
                'default' => 'off',
            ),*/
			'show_menu_search' => array(
                'slug' => 'show_menu_search',
                'title' => 'Show header menu search',
                'description' => '',
                'type' => 'checkbox',
                'default' => 'on',
            ),
			'register_account_link' => array(
                'slug' => 'register_account_link',
                'title' => 'Register Account link',
                'description' => 'Provide registration page URL for bbPress or Ultimate Member registration link. Leave empty to use WordPress default or BuddyPress default registration.',
                'type' => 'textbox',
                'default' => '',
            ),
        ),
        'footer' => array(
            'copyright' => array(
                'slug' => 'copyright',
                'title' => 'Copyright text',
                'description' => '',
                'type' => 'textbox',
                'default' => '',
            ),
        ),
        'blog' => array(
            'blog_title' => array(
                'slug' => 'blog_title',
                'title' => 'Blog title',
                'description' => '',
                'type' => 'textbox',
                'default' => 'Blog',
            ),
			'blog_item_style' => array(
                'slug' => 'blog_item_style',
                'title' => 'Post item style',
                'description' => '',
                'type' => 'select',
                'data' => array('compact_single' => 'Compact, 1 item per row', 'compact_double' => 'Compact, 2 items per row', 'large' => 'Large' ),
                'default' => 'compact_single',
            ),
			'enable_sidebar_affix' =>  array(
                'slug' => 'enable_sidebar_affix',
                'title' => 'Enable affixed sidebar',
                'description' => 'The sidebar will follow you as you scroll down the page',
                'type' => 'checkbox',
                'default' => 'on',
            ),
            'force_post_excerpt' =>  array(
                'slug' => 'force_post_excerpt',
                'title' => 'Use automatic post excerpts',
                'description' => 'If manual excerpt is not defined, theme will automatically cap articles at 50 words',
                'type' => 'checkbox',
                'default' => 'on',
            ),
            'sidebar_position' => array(
                'slug' => 'sidebar_position',
                'title' => 'Sidebar position',
                'description' => 'This setting also applies to sidebar position for blog posts and pages',
                'type' => 'select',
                'data' => array('left' => 'Left', 'right' => 'Right' ),
                'default' => 'right',
            ),
        ),
        'post' => array(
            'post_style' => array(
                'slug' => 'post_style',
                'title' => 'Default Post style',
                'description' => 'You can alter this setting on post by post basis by editing settings in post editor',
                'type' => 'select',
                'data' => array('sidebar' => 'With sidebar', 'no-sidebar' => 'Full width' ),
                'default' => 'no-sidebar',
            ),
			'post_image_width' => array(
                'slug' => 'post_image_width',
                'title' => 'Post image size',
                'description' => '',
                'type' => 'select',
                'data' => array('text_width' => 'As wide as text', 'container_width' => 'As wide as site container (for posts with sidebar only)', 'full_screen' => 'Full screen width', 'no_image' => 'No image' ),
                'default' => 'text_width',
            ),
			'show_about_author' =>  array(
                'slug' => 'show_about_author',
                'title' => 'Show "about author" section',
                'description' => '',
                'type' => 'checkbox',
                'default' => 'on',
            ),
			'enable_post_viewcount' =>  array(
                'slug' => 'enable_post_viewcount',
                'title' => 'Show post viewcount',
                'description' => 'Requires WordPress Popular Posts plugin to be active',
                'type' => 'checkbox',
                'default' => 'on',
            ),
			'enable_post_like' =>  array(
                'slug' => 'enable_post_like',
                'title' => 'Enable like/dislike buttons',
                'description' => '',
                'type' => 'checkbox',
                'default' => 'on',
            ),
			'show_post_share' =>  array(
                'slug' => 'show_post_share',
                'title' => 'Show share buttons',
                'description' => '',
                'type' => 'checkbox',
                'default' => 'on',
            ),
        ),
		'shop' => array(
            'show_shop_sidebar' => array(
                'slug' => 'show_shop_sidebar',
                'title' => 'Show sidebar in shop catalog',
                'description' => '',
                'type' => 'checkbox',
                'default' => '',
            ),
            'show_product_sidebar' => array(
                'slug' => 'show_product_sidebar',
                'title' => 'Show sidebar in single product view',
                'description' => '',
                'type' => 'checkbox',
                'default' => '',
            ),
        ),
		'gallery' => array(
            'show_gallery_single_latest' => array(
                'slug' => 'show_gallery_single_latest',
                'title' => 'Show latest galleries below single gallery slider ',
                'description' => '',
                'type' => 'checkbox',
                'default' => 'on',
            ),
        ),
        'social' => array(
            'social_facebook' => array(
                'slug' => 'social_facebook',
                'title' => 'Facebook profile URL',
                'description' => '',
                'type' => 'textbox',
                'default' => '',
            ),
            'social_twitter' => array(
                'slug' => 'social_twitter',
                'title' => 'Twitter account URL',
                'description' => '',
                'type' => 'textbox',
                'default' => '',
            ),
            'social_pinterest' => array(
                'slug' => 'social_pinterest',
                'title' => 'Pinterest account URL',
                'description' => '',
                'type' => 'textbox',
                'default' => '',
            ),
            'social_youtube' => array(
                'slug' => 'social_youtube',
                'title' => 'Youtube account URL',
                'description' => '',
                'type' => 'textbox',
                'default' => '',
            ),
            'social_gplus' => array(
                'slug' => 'social_gplus',
                'title' => 'Google plus account URL',
                'description' => '',
                'type' => 'textbox',
                'default' => '',
            ),
            'social_instagram' => array(
                'slug' => 'social_instagram',
                'title' => 'Instagram account URL',
                'description' => '',
                'type' => 'textbox',
                'default' => '',
            ),
            'social_steam' => array(
	            'slug' => 'social_steam',
	            'title' => 'Steam account URL',
	            'description' => '',
	            'type' => 'textbox',
	            'default' => '',
            ),
            'social_twitch' => array(
                'slug' => 'social_twitch',
                'title' => 'Twitch account URL',
                'description' => '',
                'type' => 'textbox',
                'default' => '',
            ),
            /* 'social_rss' => array(
                'slug' => 'social_rss',
                'title' => 'RSS URL',
                'description' => '',
                'type' => 'textbox',
                'default' => '',
            ), */
        ),
		'newsletter' => array(
            'newsletter_form_action' => array(
                'slug' => 'newsletter_form_action',
                'title' => 'Newsletter form action',
                'description' => '',
                'warning' => 'For more information on where to get form action value, please reffer to documentation',
                'type' => 'textarea',
                'default' => '',
            ),
            'newsletter_form_method' => array(
                'slug' => 'newsletter_form_method',
                'title' => 'Newsletter form method',
                'description' => '',
                'type' => 'select',
                'data' => array('POST' => 'POST', 'GET' => 'GET'),
                'default' => 'POST',
            ),
            'newsletter_email_field' => array(
                'slug' => 'newsletter_email_field',
                'title' => 'Newsletter email field name',
                'description' => '',
                'type' => 'textbox',
                'default' => 'EMAIL',
            ),
        ),
		'twitch' => array(
			'twitch_client_id' => array(
				'slug' => 'twitch_client_id',
                'title' => 'Twitch Client ID',
                'description' => 'To acquire one, register your application on https://www.twitch.tv/settings/connections',
                'type' => 'textbox',
                'default' => '',
			)
		),
		'css_mode' => array(
			'enable_css_mode' => array(
				'slug' => 'enable_css_mode',
                'title' => 'Enable CSS mode',
                'description' => 'This should only be done in case of issues with using LESS stylsheets!',
                'type' => 'checkbox',
                'default' => 'off',
			),
		),
		'custom_code' => array(
            'custom_css' => array(
                'slug' => 'custom_css',
                'title' => 'Custom CSS',
                'description' => 'If you want to modify the appearance of any elements on site, you can write the CSS code here.',
                'type' => 'textarea',
                'default' => '',
            ),
            'custom_js' => array(
                'slug' => 'custom_js',
                'title' => 'Custom Javascript',
                'description' => 'If you want add any extra Javascript to your site paste it here (no script tags needed).',
                'type' => 'textarea',
                'default' => '',
            )
        ),
    ),
    'visual_editor' => array(
        'visual_background' => array(
			'background_mode' => array(
                'slug' => 'background_mode',
                'title' => 'Background mode',
                'description' => '',
                'type' => 'select',
                'data' => array('full-width-bg' => 'Full width', 'boxed' => 'Boxed'),
                'default' => 'boxed',
            ),
            'background_image' => array(
                'slug' => 'gg_background_image',
                'title' => 'background image',
                'description' => '',
                'type' => 'background',
                'default' => '',
            ),
            'background_repeat' => array(
                'slug' => 'gg_background_repeat',
                'title' => 'Background image repeat',
                'description' => '',
                'type' => 'select',
                'data' => array('repeat' => 'Repeat', 'no-repeat' => 'No-repeat'),
                'default' => 'repeat',
            ),
            'background_attachment' => array(
                'slug' => 'gg_background_attachment',
                'title' => 'Background image attachment',
                'description' => '',
                'type' => 'select',
                'data' => array('scroll' => 'Scrollable', 'fixed' => 'Fixed to top'),
                'default' => 'fixed',
            ),

            'body-background-1' => array(
                'slug' => 'body-background-1',
                'title' => 'Main background color (box content & full width)',
                'description' => '',
                'type' => 'color',
                'default' => '#181818',
            ),
			'boxed-bg-color' => array(
                'slug' => 'boxed-bg-color',
                'title' => 'Body background color (boxed mode only)',
                'description' => '',
                'type' => 'color',
                'default' => '#181818',
            ),
        ),
		'visual_header' =>array(
			'logo-color' => array( //*******
                'slug' => 'logo-color',
                'title' => 'Logo text color',
                'description' => '',
                'type' => 'color',
                'default' => '#aaaaaa',
            ),
			'dock-bg-color' => array( //*******
                'slug' => 'dock-bg-color',
                'title' => 'Top menu background',
                'description' => '',
                'type' => 'color',
                'default' => '#181818',
            ),
			'dock-text-color' => array( //*******
                'slug' => 'dock-text-color',
                'title' => 'Top menu text color',
                'description' => '',
                'type' => 'color',
                'default' => '#a1a1a1',
            ),
			'mega-menu-bg-color' => array( //*******
                'slug' => 'mega-menu-bg-color',
                'title' => 'Main menu background color',
                'description' => '',
                'type' => 'color',
                'default' => '#282828',
            ),
			'mega-menu-text-color' => array( //*******
                'slug' => 'mega-menu-text-color',
                'title' => 'Main menu text color',
                'description' => '',
                'type' => 'color',
                'default' => '#aaaaaa',
            ),
			'mega-menu-dropdown-color' => array( //*******
                'slug' => 'mega-menu-dropdown-color',
                'title' => 'Main menu dropdown color',
                'description' => '',
                'type' => 'color',
                'default' => '#0f0f0f',
            ),
			'mega-menu-accent-color' => array( //*******
                'slug' => 'mega-menu-accent-color',
                'title' => 'Main menu accent color',
                'description' => '',
                'type' => 'color',
                'default' => '#ff2851',
            ),
		),
		'visual_footer' => array(
			'footer-bg-color' => array( //*******
                'slug' => 'footer-bg-color',
                'title' => 'Footer background',
                'description' => '',
                'type' => 'color',
                'default' => '#252525',
            ),
			'footer-title-color' => array( //*******
                'slug' => 'footer-title-color',
                'title' => 'Footer title color',
                'description' => '',
                'type' => 'color',
                'default' => '#ff2851',
            ),
			'footer-text-color' => array( //*******
                'slug' => 'footer-text-color',
                'title' => 'Footer text color',
                'description' => '',
                'type' => 'color',
                'default' => '#ffffff',
            ),
			'footer-copyright-text-color' => array( //*******
                'slug' => 'footer-copyright-text-color',
                'title' => 'Footer copyright text color',
                'description' => '',
                'type' => 'color',
                'default' => '#a1a1a1',
            ),
		),
        'visual_fonts' => array(
            'general-font' => array(
                'slug' => 'general-font',
                'title' => 'General text font',
                'description' => 'Used for most texts in the theme',
                'type' => 'font_select',
                'data' => $google_fonts,
                'default' => 'Roboto',
            ),
            'logo_font' => array(
                'slug' => 'logo_font',
                'title' => 'Logo text font',
                'description' => '',
                'type' => 'font_select',
                'data' => $google_fonts,
                'default' => 'Dosis',
            ),
            'menu_font' => array(
                'slug' => 'menu_font',
                'title' => 'Menu text font',
                'description' => '',
                'type' => 'font_select',
                'data' => $google_fonts,
                'default' => 'Dosis',
            ),
			'title-font' => array(
                'slug' => 'title-font',
                'title' => 'Title font',
                'description' => '',
                'type' => 'font_select',
                'data' => $google_fonts,
                'default' => 'Dosis',
            ),
			'menu_fontsize' => array( //*******
                'slug' => 'menu_fontsize',
                'title' => 'Menu font size',
                'description' => '',
				'type' => 'select',
                'data' => array('12px' => '12px', '14px' => '14px', '16px' => '16px', '18px' => '18px', '20px' => '20px', '22px' => '22px', '24px' => '24px', '26px' => '26px',),
                'default' => '18px',
            ),
			'logo_fontsize' => array( //*******
                'slug' => 'logo_fontsize',
                'title' => 'Logo font size',
                'description' => '',
				'type' => 'select',
                'data' => array('30px' => '30px', '36px' => '36px', '42px' => '42px', '48px' => '48px', '54px' => '54px'),
                'default' => '54px',
            ),
        ),
        'visual_colors' => array(
			'regular-text-color' => array( //*******
                'slug' => 'regular-text-color',
                'title' => 'Regular text color - posts, pages etc',
                'description' => '',
                'type' => 'color',
                'default' => '#aaaaaa',
            ),
			'accent-color-1' => array( //*******
                'slug' => 'accent-color-1',
                'title' => 'Accent color - buttons, titles, hovers etc.',
                'description' => '',
                'type' => 'color',
                'default' => '#ff2851',
            ),
			'accent-text-color-1' => array( //*******
				 'slug' => 'accent-text-color-1',
				 'title' => 'Accent text color - element titles',
				 'description' => '',
				 'type' => 'color',
				 'default' => '#181818',
			),
			'post-title-color' => array( //*******
				 'slug' => 'post-title-color',
				 'title' => 'Post title color',
				 'description' => '',
				 'type' => 'color',
				 'default' => '#ffffff',
			),
			'block-background-color' => array( //*******
				 'slug' => 'block-background-color',
				 'title' => 'Element block background color',
				 'description' => '',
				 'type' => 'color',
				 'default' => '#282828',
			),
			'trending-color-1' => array( //*******
                'slug' => 'trending-color-1',
                'title' => 'Featured/trending label color',
                'description' => '',
                'type' => 'color',
                'default' => '#ff2851',
            ),
			'more-dropdown-bg-color' => array( //*******
                'slug' => 'more-dropdown-bg-color',
                'title' => 'More tags dropdown color',
                'description' => '',
                'type' => 'color',
                'default' => '#ff2851',
            ),
			'page-title-bg-color-1' => array( //*******
                'slug' => 'page-title-bg-color-1',
                'title' => 'Page title background color',
                'description' => '',
                'type' => 'color',
                'default' => '#282828',
            ),
        ),
    ),
    'ads_manager' => array(
        'ads_manager' => array(
            '728x90' => array(
                'slug' => '728x90',
                'title' => '728x90px',
                'description' => '',
                'default' => array(array('ad_enabled' => 'on', 'mobile_enabled' => 'on', 'ad_slug' => 'default', 'ad_title' => 'Default 728x90', 'ad_type' => 'banner', 'googlead_content' => '', 'shortcode' => '', 'ad_file' => GOODGAME_IMG_URL . 'banner-728x90.jpg', 'ad_link' => '', 'ad_iframe_src' => ''))
            ),
			'970x90' => array(
                'slug' => '970x90',
                'title' => '970x90px',
                'description' => '',
                'default' => array(array('ad_enabled' => 'on', 'mobile_enabled' => 'on', 'ad_slug' => 'default', 'ad_title' => 'Default 970x90', 'ad_type' => 'banner', 'googlead_content' => '', 'shortcode' => '', 'ad_file' => GOODGAME_IMG_URL . 'banner-970x90.png', 'ad_link' => '', 'ad_iframe_src' => ''))
            ),
            '468x60' => array(
                'slug' => '468x60',
                'title' => '468x60px',
                'description' => '',
                'default' => array(array('ad_enabled' => 'on', 'mobile_enabled' => 'on', 'ad_slug' => 'default', 'ad_title' => 'Default 468x60', 'ad_type' => 'banner', 'googlead_content' => '', 'shortcode' => '', 'ad_file' => GOODGAME_IMG_URL . 'banner-468x60.png', 'ad_link' => '', 'ad_iframe_src' => ''))
            ),
            '300x300' => array(
                'slug' => '300x300',
                'title' => '300x300px',
                'description' => '',
                'default' => array(array('ad_enabled' => 'on', 'mobile_enabled' => 'on', 'ad_slug' => 'default', 'ad_title' => 'Default 300x300', 'ad_type' => 'banner', 'googlead_content' => '', 'shortcode' => '', 'ad_file' => GOODGAME_IMG_URL . 'banner-300x300.jpg', 'ad_link' => '', 'ad_iframe_src' => ''))
            ),
            /* '150x125' => array(
                'slug' => '150x125',
                'title' => '150x125 (group of 4)',
                'description' => '',
                'default' => array(array('ad_enabled' => 'on', 'mobile_enabled' => 'on', 'ad_slug' => 'default', 'ad_title' => 'Default 150x125', 'ad_type' => 'banner', 'googlead_content:0' => '', 'googlead_content:1' => '', 'googlead_content:2' => '', 'googlead_content:3' => '', 'ad_file:0' => GOODGAME_IMG_URL . 'banner-150x125.png', 'ad_link:0' => '', 'ad_file:1' => GOODGAME_IMG_URL . 'banner-150x125.png', 'ad_link:1' => '', 'ad_file:2' => GOODGAME_IMG_URL . 'banner-150x125.png', 'ad_link:2' => '', 'ad_file:3' => GOODGAME_IMG_URL . 'banner-150x125.png', 'ad_link:3' => '', 'ad_iframe_src:0' => '', 'ad_iframe_src:1' => '', 'ad_iframe_src:2' => '', 'ad_iframe_src:3' => ''))
            ),*/
        ),
        'ad_locations' => array(
            'header_ad' => array(
                'slug' => 'header_ad',
                'title' => 'Header',
                'description' => 'The main site banner visible in header. Supports 728x90 or 468x60 ads',
                'supports' => array('728x90', '468x60'),
                'default' => array('ad_enabled' => 'on', 'mobile_enabled' => 'on', 'ad_slug' => 'default', 'ad_size' => '728x90')
            ),
            'blog_ad' => array(
                'slug' => 'blog_ad',
                'title' => 'Blog index',
                'description' => 'Ad that is visible below the list of posts. Supports 468x60 ads',
                'supports' => array('970x90', '728x90', '468x60'),
                'default' => array('ad_enabled' => 'on', 'mobile_enabled' => 'on', 'ad_slug' => 'default', 'ad_size' => '468x60')
            ),
            'post_ad' => array(
                'slug' => 'post_ad',
                'title' => 'Blog Post',
                'description' => 'Ad that is visible below the a post. Supports 468x60 ads',
                'supports' => array('970x90', '728x90', '468x60'),
                'default' => array('ad_enabled' => 'on', 'mobile_enabled' => 'on', 'ad_slug' => 'default', 'ad_size' => '468x60')
            ),
            'gallery_ad' => array(
                'slug' => 'gallery_ad',
                'title' => 'Gallery index',
                'description' => 'Ad that is visible below list of galleries. Supports 728x90 or 468x60 ads',
                'supports' => array('970x90', '728x90', '468x60'),
                'default' => array('ad_enabled' => 'on', 'mobile_enabled' => 'on', 'ad_slug' => 'default', 'ad_size' => '728x90')
            ),
            'single_gallery_ad' => array(
                'slug' => 'single_gallery_ad',
                'title' => 'Gallery item',
                'description' => 'Ad that is visible below open gallery content. Supports 728x90 or 468x60 ads',
                'supports' => array('970x90', '728x90', '468x60'),
                'default' => array('ad_enabled' => 'on', 'mobile_enabled' => 'on', 'ad_slug' => 'default', 'ad_size' => '728x90')
            ),
        ),
    ),
);

/*

	'general_settings' => array(
		'general_textbox' => array(
			'slug' => 'general_textbox',
			'title' => 'Textbox example',
			'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt',
			'default' => 'test',
			'type' => 'textbox'
		),
		'general_checkbox' => array(
			'slug' => 'general_checkbox',
			'title' => 'Checkbox example',
			'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt',
			'type' => 'checkbox',
			'default' => '',
		),
		'general_select' => array(
			'slug' => 'general_select',
			'title' => 'Select example',
			'description' => 'Lorem ipsum dolor sit amet',
			'type' => 'select',
			'data' => $select_example,
			'default' => '--',
			'dependant' => 'general_checkbox'
		),
		'general_textarea' => array(
			'slug' => 'general_textarea',
			'title' => 'Textarea example',
			'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing',
			'type' => 'textarea',
			'default' => '',
			'dependant' => 'general_checkbox'
		),
	)

*/

?>
