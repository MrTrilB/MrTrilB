<?php
/**
 * BuddyxPro\BuddyxPro\Typography_Options\Component class
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro\Kirki_Option;

use function add_action;
use function add_filter;
use BuddyxPro\BuddyxPro\Component_Interface;

/**
 * Class for adding custom background support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string component slug
	 */
	public function get_slug(): string {
		return 'kirki_option';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
		add_filter( 'kirki/fields', array( $this, 'add_fields' ) );
		add_filter( 'body_class', array( $this, 'site_width_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_header_layout_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_menu_effect_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_header_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_sidebar_body_classes' ) );
		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'body_class', array( $this, 'site_woo_product_body_classes' ) );
		}
		if ( class_exists( 'SFWD_LMS' ) ) {
			add_filter( 'body_class', array( $this, 'site_learndash_body_classes' ) );
		}
	}

	/**
	 * Site layout body class.
	 */
	public function site_width_body_classes( array $classes ): array {
		$classes[] = 'layout-' . get_theme_mod( 'site_layout', buddyx_defaults( 'site-layout' ) );

		return $classes;
	}

	/**
	 * Site header layout body class.
	 */
	public function site_header_layout_body_classes( array $classes ): array {
		$header_layout = get_theme_mod( 'site_header_layout', buddyx_defaults( 'site-header-layout' ) );
		if ( $header_layout == 'style_2' ) {
			$classes[] = 'header-layout-2';
		}
		if ( $header_layout == 'style_3' ) {
			$classes[] = 'header-layout-3';
		}

		return $classes;
	}

	/**
	 * Site menu effect body class.
	 */
	public function site_menu_effect_body_classes( array $classes ): array {
		$header_layout = get_theme_mod( 'site_header_menu_effect', buddyx_defaults( 'site-header-menu-effect' ) );
		if ( $header_layout == 'style_1' ) {
			$classes[] = 'menu-effect-1';
		}
		if ( $header_layout == 'style_2' ) {
			$classes[] = 'menu-effect-2';
		}
		if ( $header_layout == 'style_3' ) {
			$classes[] = 'menu-effect-3';
		}

		return $classes;
	}

	/**
	 * Site sticky header body class.
	 */
	public function site_sticky_header_classes( array $classes ): array {
		$sticky_header = get_theme_mod( 'site_sticky_header', buddyx_defaults( 'site-sticky-header' ) );
		if ( $sticky_header ) {
			$classes[] = 'sticky-header';
		}

		return $classes;
	}

	/**
	 * Site sticky sidebar body class.
	 */
	public function site_sticky_sidebar_body_classes( array $classes ): array {
		$sticky_sidebar = get_theme_mod( 'sticky_sidebar_option', buddyx_defaults( 'sticky-sidebar' ) );
		if ( $sticky_sidebar ) {
			$classes[] = 'sticky-sidebar-enable';
		}

		return $classes;
	}

	/**
	 * Site woocommerce product style body class.
	 */
	public function site_woo_product_body_classes( array $classes ): array {
		$woo_product_style = get_theme_mod( 'woocommerce_product_style', buddyx_defaults( 'woo-product-style' ) );
		if ( $woo_product_style == 'style_1' ) {
			$classes[] = 'woo-product-style-1';
		}
		if ( $woo_product_style == 'style_2' ) {
			$classes[] = 'woo-product-style-2';
		}
		if ( $woo_product_style == 'style_3' ) {
			$classes[] = 'woo-product-style-3';
		}

		return $classes;
	}

	/**
	 * Learndash dark mode body class.
	 */
	public function site_learndash_body_classes( array $classes ): array {
		if ( isset( $_COOKIE['bxtheme'] ) && 'dark' == $_COOKIE['bxtheme'] && is_user_logged_in() ) {
			$classes[] = 'buddyx-dark-theme';
		}

		return $classes;
	}

	/**
	 * Add Customizer Section.
	 */
	public function add_panels_and_sections( $wp_customize ) {
		// Site Layout.
		$wp_customize->add_panel(
			'site_layout_panel',
			array(
				'title'       => esc_html__( 'General', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_layout',
			array(
				'title'       => esc_html__( 'Site Layout', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Site Loader.
		$wp_customize->add_section(
			'site_loader',
			array(
				'title'       => esc_html__( 'Site Loader', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Page Mapping.
		$wp_customize->add_section(
			'page_mapping',
			array(
				'title'       => esc_html__( 'Page Mapping', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Sign-in / Register Popup.
		$wp_customize->add_section(
			'buddyx_signin_popup_options',
			array(
				'title'       => esc_html__( 'Sign-in Popup | Register Form Fields', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Typography.
		$wp_customize->add_panel(
			'typography_panel',
			array(
				'title'       => esc_html__( 'Typography', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_title_typography_section',
			array(
				'title'       => esc_html__( 'Site Title', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'headings_typography_section',
			array(
				'title'       => esc_html__( 'Headings', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'menu_typography_section',
			array(
				'title'       => esc_html__( 'Menu', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'body_typography_section',
			array(
				'title'       => esc_html__( 'Body', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		// Site Header.
		$wp_customize->add_section(
			'site_topbar_section',
			array(
				'title'       => esc_html__( 'Site Top Bar', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Header.
		$wp_customize->add_section(
			'site_header_section',
			array(
				'title'       => esc_html__( 'Site Header', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Sub Header.
		$wp_customize->add_section(
			'site_sub_header_section',
			array(
				'title'       => esc_html__( 'Site Sub Header', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Skin.
		$wp_customize->add_section(
			'site_skin_section',
			array(
				'title'       => esc_html__( 'Site Skin', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Blog Layout.
		$wp_customize->add_section(
			'site_blog_section',
			array(
				'title'       => esc_html__( 'Site Blog', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Sidebar Layout.
		$wp_customize->add_section(
			'site_sidebar_layout',
			array(
				'title'       => esc_html__( 'Site Sidebar', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// BuddyPress Option.
		if ( class_exists( 'BuddyPress' ) ) {
			if ( ! class_exists( 'Youzify' ) ) {
				$wp_customize->add_panel(
					'site_buddypress_panel',
					array(
						'title'       => esc_html__( 'BuddyPress', 'buddyxpro' ),
						'priority'    => 11,
						'description' => '',
					)
				);

				$wp_customize->add_section(
					'site_buddypress_activity_section',
					array(
						'title'       => esc_html__( 'Activity Control', 'buddyxpro' ),
						'priority'    => 10,
						'description' => '',
						'panel'       => 'site_buddypress_panel',
					)
				);

				$wp_customize->add_section(
					'site_buddypress_members_section',
					array(
						'title'       => esc_html__( 'BuddyPress Members', 'buddyxpro' ),
						'priority'    => 10,
						'description' => '',
						'panel'       => 'site_buddypress_panel',
					)
				);

				$wp_customize->add_section(
					'site_buddypress_groups_section',
					array(
						'title'       => esc_html__( 'BuddyPress Groups', 'buddyxpro' ),
						'priority'    => 10,
						'description' => '',
						'panel'       => 'site_buddypress_panel',
					)
				);

				$wp_customize->add_section(
					'site_buddypress_single_member_section',
					array(
						'title'       => esc_html__( 'BuddyPress Single Member', 'buddyxpro' ),
						'priority'    => 10,
						'description' => '',
						'panel'       => 'site_buddypress_panel',
					)
				);

				$wp_customize->add_section(
					'site_buddypress_single_group_section',
					array(
						'title'       => esc_html__( 'BuddyPress Single Group', 'buddyxpro' ),
						'priority'    => 10,
						'description' => '',
						'panel'       => 'site_buddypress_panel',
					)
				);
			}
		}

		// LearnDash.
		if ( class_exists( 'SFWD_LMS' ) ) {
			$wp_customize->add_section(
				'site_learndash_section',
				array(
					'title'       => esc_html__( 'LearnDash', 'buddyxpro' ),
					'priority'    => 10,
					'description' => '',
				)
			);
		}

		// WooCommerce.
		if ( class_exists( 'WooCommerce' ) ) {
			$wp_customize->add_section(
				'site_woocommerce_general_section',
				array(
					'title'       => esc_html__( 'General', 'buddyxpro' ),
					'priority'    => 10,
					'description' => '',
					'panel'       => 'woocommerce',
				)
			);
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$wp_customize->add_section(
				'site_woocommerce_shop_section',
				array(
					'title'       => esc_html__( 'Shop Page', 'buddyxpro' ),
					'priority'    => 11,
					'description' => '',
					'panel'       => 'woocommerce',
				)
			);
		}

		// Site Footer.
		$wp_customize->add_panel(
			'site_footer_panel',
			array(
				'title'       => esc_html__( 'Site Footer', 'buddyxpro' ),
				'priority'    => 11,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_footer_section',
			array(
				'title'       => esc_html__( 'Footer Section', 'buddyxpro' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Copyright.
		$wp_customize->add_section(
			'site_copyright_section',
			array(
				'title'       => esc_html__( 'Copyright Section', 'buddyxpro' ),
				'priority'    => 11,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);
	}

	public function add_fields( $fields ) {
		/*
		 *  Site Layout
		 */
		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'site_layout',
			'label'    => esc_html__( 'Site Layout', 'buddyxpro' ),
			'section'  => 'site_layout',
			'priority' => 10,
			'default'  => 'wide',
			'choices'  => array(
				'boxed' => get_template_directory_uri() . '/assets/images/boxed.png',
				'wide'  => get_template_directory_uri() . '/assets/images/wide.png',
			),
		);

		/*
		 *  Site Container Width
		 */
		$fields[] = array(
			'type'        => 'dimension',
			'settings'    => 'site_container_width',
			'label'       => esc_html__( 'Max Content Layout Width', 'buddyxpro' ),
			'description' => esc_html__( 'Select the maximum content width for your website (px)', 'buddyxpro' ),
			'section'     => 'site_layout',
			'default'     => '1300px',
			'priority'    => 10,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'element'  => '.container, .layout-boxed .site, .layout-boxed .site-header-wrapper, .layout-boxed .top-bar, .dokan-single-store.dokan-single-store-top .profile-frame .profile-info-box .profile-info-summery-wrapper, .dokan-single-store.dokan-single-store-top .dokan-store-tabs.dokan-store-tabs ul, .dokan-single-store.dokan-single-store-top .profile-frame .profile-info-box.profile-layout-layout1 .profile-info-summery-wrapper, #youzify-profile-navmenu .youzify-inner-content, .youzify .wild-content, .youzify .youzify-boxed-navbar, .youzify-cover-content, .youzify-header-content, .youzify-page-main-content, .youzify-vertical-layout .youzify-content, .lp-content-area',
					'function' => 'css',
					'property' => 'max-width',
				),
			),
		);

		/*
		 *  Site Loader
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_loader',
			'label'    => esc_html__( 'Site Loader ?', 'buddyxpro' ),
			'section'  => 'site_loader',
			'default'  => '2',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		$fields[] = array(
			'type'            => 'text',
			'settings'        => 'site_loader_text',
			'label'           => esc_html__( 'Site Loader Text', 'buddyxpro' ),
			'section'         => 'site_loader',
			'default'         => esc_html__( 'Loading', 'buddyxpro' ),
			'active_callback' => array(
				array(
					'setting'  => 'site_loader',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'            => 'color',
			'settings'        => 'site_loader_bg',
			'label'           => esc_html__( 'Site Loader Background', 'buddyxpro' ),
			'section'         => 'site_loader',
			'default'         => '#ee4036',
			'priority'        => 10,
			'output'          => array(
				array(
					'element'  => '.site-loader',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_loader',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		/*
		 *  Page Mapping
		 */
		$fields[] = array(
			'type'        => 'dropdown-pages',
			'settings'    => 'buddyx_login_page',
			'label'       => esc_attr__( 'Login Page', 'buddyxpro' ),
			'description' => esc_attr__( 'You can redirect user to custom login page.', 'buddyxpro' ),
			'section'     => 'page_mapping',
			'priority'    => 10,
			'default'     => 0,
			'placeholder' => '--- Select a Page ---',
		);

		$fields[] = array(
			'type'        => 'dropdown-pages',
			'settings'    => 'buddyx_registration_page',
			'label'       => esc_attr__( 'Registration Page', 'buddyxpro' ),
			'description' => esc_attr__( 'You can redirect user to custom registration page.', 'buddyxpro' ),
			'section'     => 'page_mapping',
			'priority'    => 10,
			'default'     => 0,
			'placeholder' => '--- Select a Page ---',
		);

		$fields[] = array(
			'type'        => 'dropdown-pages',
			'settings'    => 'buddyx_404_page',
			'label'       => esc_attr__( '404', 'buddyxpro' ),
			'description' => esc_attr__( 'You can redirect user to custom 404 page.', 'buddyxpro' ),
			'section'     => 'page_mapping',
			'priority'    => 10,
			'default'     => 0,
			'placeholder' => '--- Select a Page ---',
		);

		/*
		 *  Sign-in Popup
		 */
		$fields[] = array(
			'type'        => 'switch',
			'settings'    => 'buddyx_signin_popup',
			'label'       => esc_attr__( 'Sign-in Popup', 'buddyxpro' ),
			'description' => esc_attr__( '', 'buddyxpro' ),
			'section'     => 'buddyx_signin_popup_options',
			'priority'    => 10,
			'default'     => '',
			'choices'     => array(
				'on'  => esc_attr__( 'Yes', 'buddyxpro' ),
				'off' => esc_attr__( 'No', 'buddyxpro' ),
			),
		);

		$fields[] = array(
			'type'            => 'radio',
			'settings'        => 'buddyx_sign_form_popup',
			'label'           => esc_attr__( 'Form Popup', 'buddyxpro' ),
			'description'     => esc_attr__( '', 'buddyxpro' ),
			'section'         => 'buddyx_signin_popup_options',
			'priority'        => 10,
			'default'         => 'default',
			'choices'         => array(
				'default' => esc_attr__( 'BuddyX Login Form', 'buddyxpro' ),
				'custom'  => esc_attr__( 'Custom shortcode', 'buddyxpro' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'buddyx_signin_popup',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		$fields[] = array(
			'type'            => 'select',
			'settings'        => 'buddyx_sign_form_display',
			'label'           => esc_attr__( 'Form Display', 'buddyxpro' ),
			'description'     => esc_attr__( '', 'buddyxpro' ),
			'section'         => 'buddyx_signin_popup_options',
			'priority'        => 10,
			'default'         => 'login',
			'choices'         => array(
				'both'     => esc_attr__( 'Both', 'buddyxpro' ),
				'login'    => esc_attr__( 'Login', 'buddyxpro' ),
				'register' => esc_attr__( 'Register', 'buddyxpro' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'buddyx_sign_form_popup',
					'operator' => '!=',
					'value'    => 'custom',
				),
				array(
					'setting'  => 'buddyx_signin_popup',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		if ( class_exists( 'BuddyPress' ) ) {

			$fields[] = array(
				'type'            => 'select',
				'settings'        => 'buddyx_login_redirect',
				'label'           => esc_attr__( 'Login Redirect', 'buddyxpro' ),
				'description'     => esc_attr__( '', 'buddyxpro' ),
				'section'         => 'buddyx_signin_popup_options',
				'priority'        => 10,
				'default'         => 'current',
				'choices'         => array(
					'current'  => esc_html__( 'Current page', 'buddyxpro' ),
					'profile'  => esc_html__( 'Profile page', 'buddyxpro' ),
					'activity' => esc_html__( 'Activity page', 'buddyxpro' ),
					'custom'   => esc_html__( 'Custom page', 'buddyxpro' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'buddyx_sign_form_popup',
						'operator' => '!=',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'buddyx_signin_popup',
						'operator' => '==',
						'value'    => true,
					),
				),
			);

		} else {

			$fields[] = array(
				'type'            => 'select',
				'settings'        => 'buddyx_login_redirect',
				'label'           => esc_attr__( 'Login Redirect', 'buddyxpro' ),
				'description'     => esc_attr__( '', 'buddyxpro' ),
				'section'         => 'buddyx_signin_popup_options',
				'priority'        => 10,
				'default'         => 'current',
				'choices'         => array(
					'current' => esc_html__( 'Current page', 'buddyxpro' ),
					'custom'  => esc_html__( 'Custom page', 'buddyxpro' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'buddyx_sign_form_popup',
						'operator' => '!=',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'buddyx_signin_popup',
						'operator' => '==',
						'value'    => true,
					),
				),
			);

		}

		$fields[] = array(
			'type'            => 'link',
			'settings'        => 'buddyx_login_redirect_url',
			'label'           => esc_attr__( 'Login Custom URL', 'buddyxpro' ),
			'description'     => esc_attr__( '', 'buddyxpro' ),
			'section'         => 'buddyx_signin_popup_options',
			'priority'        => 10,
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'buddyx_signin_popup',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'buddyx_sign_form_popup',
					'operator' => '!=',
					'value'    => 'custom',
				),
				array(
					'setting'  => 'buddyx_login_redirect',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		);

		if ( ! class_exists( 'PeepSo' ) ) {

			if ( class_exists( 'BuddyPress' ) ) {

				$fields[] = array(
					'type'            => 'select',
					'settings'        => 'buddyx_register_redirect',
					'label'           => esc_attr__( 'Register Redirect', 'buddyxpro' ),
					'description'     => esc_attr__( '', 'buddyxpro' ),
					'section'         => 'buddyx_signin_popup_options',
					'priority'        => 10,
					'default'         => 'current',
					'choices'         => array(
						'current'  => esc_html__( 'Current page', 'buddyxpro' ),
						'profile'  => esc_html__( 'Profile page', 'buddyxpro' ),
						'activity' => esc_html__( 'Activity page', 'buddyxpro' ),
						'custom'   => esc_html__( 'Custom page', 'buddyxpro' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'buddyx_sign_form_popup',
							'operator' => '!=',
							'value'    => 'custom',
						),
						array(
							'setting'  => 'buddyx_signin_popup',
							'operator' => '==',
							'value'    => true,
						),
					),
				);

			} else {

				$fields[] = array(
					'type'            => 'select',
					'settings'        => 'buddyx_register_redirect',
					'label'           => esc_attr__( 'Register Redirect', 'buddyxpro' ),
					'description'     => esc_attr__( '', 'buddyxpro' ),
					'section'         => 'buddyx_signin_popup_options',
					'priority'        => 10,
					'default'         => 'current',
					'choices'         => array(
						'current' => esc_html__( 'Current page', 'buddyxpro' ),
						'custom'  => esc_html__( 'Custom page', 'buddyxpro' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'buddyx_sign_form_popup',
							'operator' => '!=',
							'value'    => 'custom',
						),
						array(
							'setting'  => 'buddyx_signin_popup',
							'operator' => '==',
							'value'    => true,
						),
					),
				);

			}

			$fields[] = array(
				'type'            => 'link',
				'settings'        => 'buddyx_register_redirect_url',
				'label'           => esc_attr__( 'Register Custom URL', 'buddyxpro' ),
				'description'     => esc_attr__( '', 'buddyxpro' ),
				'section'         => 'buddyx_signin_popup_options',
				'priority'        => 10,
				'default'         => '',
				'active_callback' => array(
					array(
						'setting'  => 'buddyx_signin_popup',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'buddyx_sign_form_popup',
						'operator' => '!=',
						'value'    => 'custom',
					),
					array(
						'setting'  => 'buddyx_register_redirect',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			);

		}

		$fields[] = array(
			'type'            => 'textarea',
			'settings'        => 'buddyx_login_description',
			'label'           => esc_attr__( 'Login Form Description', 'buddyxpro' ),
			'description'     => esc_attr__( 'Note: Use only for BuddyX Login Form.', 'buddyxpro' ),
			'section'         => 'buddyx_signin_popup_options',
			'default'         => '',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'buddyx_sign_form_popup',
					'operator' => '==',
					'value'    => 'default',
				),
				array(
					'setting'  => 'buddyx_signin_popup',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		$fields[] = array(
			'type'            => 'textarea',
			'settings'        => 'buddyx_sign_form_shortcode',
			'label'           => esc_attr__( 'Popup Content', 'buddyxpro' ),
			'description'     => esc_attr__( 'You can use own custom HTML or shortcodes that will appear in popup box', 'buddyxpro' ),
			'section'         => 'buddyx_signin_popup_options',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'buddyx_sign_form_popup',
					'operator' => '==',
					'value'    => 'custom',
				),
				array(
					'setting'  => 'buddyx_signin_popup',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		if ( class_exists( 'BuddyPress' ) ) {

			$fields[] = array(
				'type'            => 'custom',
				'settings'        => 'custom-signin-divider',
				'section'         => 'buddyx_signin_popup_options',
				'default'         => '<hr>',
				'active_callback' => array(
					array(
						'setting'  => 'buddyx_signin_popup',
						'operator' => '==',
						'value'    => true,
					),
				),
			);

			$fields[] = array(
				'type'            => 'custom',
				'settings'        => 'buddyx_regiter_form',
				'label'           => esc_attr__( 'BuddyX Register Form', 'buddyxpro' ),
				'section'         => 'buddyx_signin_popup_options',
				'active_callback' => array(
					array(
						'setting'  => 'buddyx_signin_popup',
						'operator' => '==',
						'value'    => true,
					),
				),
			);

			$fields[] = array(
				'type'            => 'custom',
				'settings'        => 'buddyx_regiter_basic',
				'label'           => '<h5 style="font-style: italic; margin: 0;">' . __( '- Basic', 'buddyxpro' ) . '</h5>',
				'description'     => esc_attr__( 'Form is non-editable and consists of basic WP fields \'First name\', \'Last name\', \'Username\', \'Email\', \'Password\' fields.', 'buddyxpro' ),
				'section'         => 'buddyx_signin_popup_options',
				'active_callback' => array(
					array(
						'setting'  => 'buddyx_signin_popup',
						'operator' => '==',
						'value'    => true,
					),
				),
			);

			$fields[] = array(
				'type'            => 'custom',
				'settings'        => 'buddyx_regiter_standard',
				'label'           => '<h5 style="font-style: italic; margin: 0;">' . __( '- Standard', 'buddyxpro' ) . '</h5>',
				'description'     => esc_attr__( 'Form is editable via Users > Profile fields. Only the required field will be displayed in the form. \'Username\', \'Email\', \'Password\' are non-editable fields.', 'buddyxpro' ),
				'section'         => 'buddyx_signin_popup_options',
				'active_callback' => array(
					array(
						'setting'  => 'buddyx_signin_popup',
						'operator' => '==',
						'value'    => true,
					),
				),
			);

			if ( function_exists( 'bp_is_active' ) && bp_is_active( 'xprofile' ) ) {

				$fields[] = array(
					'type'            => 'custom',
					'settings'        => 'buddyx_regiter_extended',
					'label'           => '<h5 style="font-style: italic; margin: 0;">' . __( '- Extended', 'buddyxpro' ) . '</h5>',
					'description'     => esc_attr__( 'Form is editable via Users > Profile fields. Only the required field will be displayed in the form. After filling in form fields user will be redirected to the Register page, where can complete other Profile Info Fields \'Username\', \'Email\' are non-editable fields.', 'buddyxpro' ),
					'section'         => 'buddyx_signin_popup_options',
					'active_callback' => array(
						array(
							'setting'  => 'buddyx_signin_popup',
							'operator' => '==',
							'value'    => true,
						),
					),
				);

				$fields[] = array(
					'type'            => 'radio',
					'settings'        => 'buddyx_sign_in_register_fields_type',
					'label'           => esc_attr__( '', 'buddyxpro' ),
					'description'     => esc_attr__( '', 'buddyxpro' ),
					'section'         => 'buddyx_signin_popup_options',
					'priority'        => 10,
					'default'         => 'simple',
					'choices'         => array(
						'simple'      => esc_attr__( 'Basic', 'buddyxpro' ),
						'buddypress'  => esc_attr__( 'Standard', 'buddyxpro' ),
						'extensional' => esc_attr__( 'Extended', 'buddyxpro' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'buddyx_signin_popup',
							'operator' => '==',
							'value'    => true,
						),
					),
				);

			} else {

				$fields[] = array(
					'type'            => 'radio',
					'settings'        => 'buddyx_sign_in_register_fields_type',
					'label'           => esc_attr__( '', 'buddyxpro' ),
					'description'     => esc_attr__( '', 'buddyxpro' ),
					'section'         => 'buddyx_signin_popup_options',
					'priority'        => 10,
					'default'         => 'simple',
					'choices'         => array(
						'simple'     => esc_attr__( 'Basic', 'buddyxpro' ),
						'buddypress' => esc_attr__( 'Standard', 'buddyxpro' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'buddyx_signin_popup',
							'operator' => '==',
							'value'    => true,
						),
					),
				);

			}

			$fields[] = array(
				'type'            => 'switch',
				'settings'        => 'sign_in_register_activation_email',
				'label'           => esc_attr__( 'Activation Email', 'buddyxpro' ),
				'description'     => esc_attr__( '', 'buddyxpro' ),
				'section'         => 'buddyx_signin_popup_options',
				'priority'        => 10,
				'default'         => 1,
				'choices'         => array(
					'on'  => esc_attr__( 'Yes', 'buddyxpro' ),
					'off' => esc_attr__( 'No', 'buddyxpro' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'buddyx_signin_popup',
						'operator' => '==',
						'value'    => true,
					),
				),
			);

		}

		/*
		 *  Site Title Typography
		 */
		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'site_title_typography_option',
			'label'    => esc_html__( 'Site Title Settings', 'buddyxpro' ),
			'section'  => 'site_title_typography_section',
			'default'  => array(
				'font-family'     => 'Nunito Sans',
				'variant'         => '600',
				'font-size'       => '38px',
				'line-height'     => '1.2',
				'letter-spacing'  => '0',
				'color'           => '#003049',
				'text-transform'  => 'none',
				'text-align'      => 'left',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.site-title a',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_title_hover_color',
			'label'    => esc_html__( 'Site Title Hover Color', 'buddyxpro' ),
			'section'  => 'site_title_typography_section',
			'default'  => '#ee4036',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.site-title a:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'site_tagline_typography_option',
			'label'    => esc_html__( 'Site Tagline Settings', 'buddyxpro' ),
			'section'  => 'site_title_typography_section',
			'default'  => array(
				'font-family'     => 'Nunito Sans',
				'variant'         => 'regular',
				'font-size'       => '14px',
				'line-height'     => '1.4',
				'letter-spacing'  => '0',
				'color'           => '#757575',
				'text-transform'  => 'none',
				'text-align'      => 'left',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.site-description',
				),
			),
		);

		/*
		 *  Headings Typography
		 */
		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h1_typography_option',
			'label'    => esc_html__( 'H1 Tag Settings', 'buddyxpro' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'     => 'Nunito Sans',
				'variant'         => '',
				'font-size'       => '30px',
				'line-height'     => '1.4',
				'letter-spacing'  => '0',
				'color'           => '#003049',
				'text-transform'  => 'none',
				'text-align'      => '',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h1, body.buddypress article.page>.entry-header .entry-title',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h2_typography_option',
			'label'    => esc_html__( 'H2 Tag Settings', 'buddyxpro' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'     => 'Nunito Sans',
				'variant'         => '',
				'font-size'       => '24px',
				'line-height'     => '1.4',
				'letter-spacing'  => '0',
				'color'           => '#003049',
				'text-transform'  => 'none',
				'text-align'      => '',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h2',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h3_typography_option',
			'label'    => esc_html__( 'H3 Tag Settings', 'buddyxpro' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'     => 'Nunito Sans',
				'variant'         => '',
				'font-size'       => '22px',
				'line-height'     => '1.4',
				'letter-spacing'  => '0',
				'color'           => '#003049',
				'text-transform'  => 'none',
				'text-align'      => '',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h3',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h4_typography_option',
			'label'    => esc_html__( 'H4 Tag Settings', 'buddyxpro' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'     => 'Nunito Sans',
				'variant'         => '',
				'font-size'       => '20px',
				'line-height'     => '1.4',
				'letter-spacing'  => '0',
				'color'           => '#003049',
				'text-transform'  => 'none',
				'text-align'      => '',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h4',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h5_typography_option',
			'label'    => esc_html__( 'H5 Tag Settings', 'buddyxpro' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'     => 'Nunito Sans',
				'variant'         => '',
				'font-size'       => '18px',
				'line-height'     => '1.4',
				'letter-spacing'  => '0',
				'color'           => '#003049',
				'text-transform'  => 'none',
				'text-align'      => '',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h5',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h6_typography_option',
			'label'    => esc_html__( 'H6 Tag Settings', 'buddyxpro' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'     => 'Nunito Sans',
				'variant'         => '',
				'font-size'       => '16px',
				'line-height'     => '1.4',
				'letter-spacing'  => '0',
				'color'           => '#003049',
				'text-transform'  => 'none',
				'text-align'      => '',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h6',
				),
			),
		);

		/*
		 *  Menu Typography
		 */
		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'menu_typography_option',
			'label'    => esc_html__( 'Menu Settings', 'buddyxpro' ),
			'section'  => 'menu_typography_section',
			'default'  => array(
				'font-family'     => 'Nunito Sans',
				'variant'         => '500',
				'font-size'       => '16px',
				'line-height'     => '1.6',
				'letter-spacing'  => '0.02em',
				'color'           => '#003049',
				'text-transform'  => 'none',
				// 'text-align' => 'left',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.main-navigation a, .main-navigation ul li a, .nav--toggle-sub li.menu-item-has-children, .nav--toggle-small .menu-toggle',
				),
				array(
					'element'  => '.nav--toggle-small .menu-toggle',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'menu_hover_color',
			'label'    => esc_html__( 'Menu Hover Color', 'buddyxpro' ),
			'section'  => 'menu_typography_section',
			'default'  => '#ee4036',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.main-navigation a:hover, .main-navigation ul li a:hover, .nav--toggle-sub li.menu-item-has-children:hover, .nav--toggle-small .menu-toggle:hover',
					'property' => 'color',
				),
				array(
					'element'  => '.nav--toggle-small .menu-toggle:hover',
					'property' => 'border-color',
				),
				array(
					'element'  => '.menu-effect-1 .main-navigation ul#primary-menu>li>a:after, .menu-effect-2 .main-navigation ul#primary-menu>li>a::before, .menu-effect-2 .main-navigation ul#primary-menu>li>a::after',
					'property' => 'background',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'menu_active_color',
			'label'    => esc_html__( 'Menu Active Color', 'buddyxpro' ),
			'section'  => 'menu_typography_section',
			'default'  => '#ee4036',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.main-navigation ul li.current-menu-item>a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'sub_menu_typography_option',
			'label'    => esc_html__( 'Sub Menu Settings', 'buddyxpro' ),
			'section'  => 'menu_typography_section',
			'default'  => array(
				'font-family'     => 'Nunito Sans',
				'variant'         => '500',
				'font-size'       => '16px',
				'line-height'     => '1.6',
				'letter-spacing'  => '0.02em',
				'text-transform'  => 'none',
				'text-align'      => 'left',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.main-navigation ul#primary-menu>li .sub-menu a',
				),
			),
		);

		/*
		 * Body Typography
		 */
		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'typography_option',
			'label'    => esc_html__( 'Settings', 'buddyxpro' ),
			'section'  => 'body_typography_section',
			'default'  => array(
				'font-family'     => 'Nunito Sans',
				'variant'         => 'regular',
				'font-size'       => '16px',
				'line-height'     => '1.6',
				'letter-spacing'  => '0',
				'color'           => '#505050',
				'text-transform'  => 'none',
				'text-align'      => 'left',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'body:not(.block-editor-page):not(.wp-core-ui), body:not(.block-editor-page):not(.wp-core-ui) pre, input, optgroup, select, textarea',
				),
			),
		);

		/*
		* Site Top Bar
		*/
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_topbar_enable',
			'label'    => esc_html__( 'Enable Top Bar ?', 'buddyxpro' ),
			'section'  => 'site_topbar_section',
			'default'  => '1',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		$fields[] = array(
			'type'            => 'switch',
			'settings'        => 'site_topbar_left_edit',
			'label'           => esc_html__( 'Edit Left Content ?', 'buddyxpro' ),
			'section'         => 'site_topbar_section',
			'default'         => '2',
			'choices'         => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_topbar_enable',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'            => 'textarea',
			'settings'        => 'site_topbar_left_area',
			'label'           => esc_html__( 'Left Content Area', 'buddyxpro' ),
			'section'         => 'site_topbar_section',
			'default'         => '<i class="fas fa-phone-alt" aria-hidden="true"></i> 011 322 44 56 | <i class="fas fa-envelope" aria-hidden="true"></i> <a href="mailto:mail@example.com">mail@example.com</a>',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'site_topbar_enable',
					'operator' => '==',
					'value'    => '1',
				),
				array(
					'setting'  => 'site_topbar_left_edit',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'            => 'color',
			'settings'        => 'topbar_background_color',
			'label'           => esc_html__( 'Top Bar Background Color', 'buddyxpro' ),
			'section'         => 'site_topbar_section',
			'default'         => '#ee4036',
			'priority'        => 10,
			'choices'         => array( 'alpha' => true ),
			'output'          => array(
				array(
					'element'  => '.top-bar',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_topbar_enable',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'            => 'color',
			'settings'        => 'topbar_content_color',
			'label'           => esc_html__( 'Top Bar Content Color', 'buddyxpro' ),
			'section'         => 'site_topbar_section',
			'default'         => '#ffffff',
			'priority'        => 10,
			'choices'         => array( 'alpha' => true ),
			'output'          => array(
				array(
					'element'  => '.top-bar',
					'property' => 'color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_topbar_enable',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'            => 'color',
			'settings'        => 'topbar_links_color',
			'label'           => esc_html__( 'Top Bar Link Color', 'buddyxpro' ),
			'section'         => 'site_topbar_section',
			'default'         => '#ffffff',
			'priority'        => 10,
			'choices'         => array( 'alpha' => true ),
			'output'          => array(
				array(
					'element'  => '.top-bar a',
					'property' => 'color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_topbar_enable',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'            => 'color',
			'settings'        => 'topbar_links_hover_color',
			'label'           => esc_html__( 'Top Bar Link Hover', 'buddyxpro' ),
			'section'         => 'site_topbar_section',
			'default'         => '#ee4036',
			'priority'        => 10,
			'choices'         => array( 'alpha' => true ),
			'output'          => array(
				array(
					'element'  => '.top-bar a:hover',
					'property' => 'color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_topbar_enable',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'            => 'repeater',
			'settings'        => 'topbar_social_links',
			'label'           => esc_html__( 'Social Links', 'buddyxpro' ),
			'description'     => __( 'Fontawesome classes are used to set icons. Check <a href="https://fontawesome.com/icons" target="_blank">https://fontawesome.com/</a> for further assistance.', 'buddyxpro' ),
			'section'         => 'site_topbar_section',
			'priority'        => 10,
			'row_label'       => array(
				'type'  => 'field',
				'value' => esc_html__( 'Social Link', 'buddyxpro' ),
				'field' => 'link_text',
			),
			'button_label'    => esc_html__( 'Add', 'buddyxpro' ),
			'transport'       => 'postMessage',
			'default'         => buddyx_topbar_default_social_links(),
			'fields'          => array(
				'link_text' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Title', 'buddyxpro' ),
					'description' => '',
					'default'     => '',
				),
				'link_icon' => array(
					'type'        => 'textarea',
					'label'       => esc_html__( 'Icon', 'buddyxpro' ),
					'description' => '',
					'default'     => '<i class="fab fa-facebook-f"></i>',
				),
				'link_url'  => array(
					'type'        => 'url',
					'label'       => esc_html__( 'Link', 'buddyxpro' ),
					'description' => '',
					'default'     => '',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_topbar_enable',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		/*
		 * Site Header
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_sticky_header',
			'label'    => esc_html__( 'Enable Sticky Header ?', 'buddyxpro' ),
			'section'  => 'site_header_section',
			'default'  => '1',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'site_header_layout',
			'label'    => esc_html__( 'Header Layout', 'buddyxpro' ),
			'section'  => 'site_header_section',
			'default'  => 'default',
			'choices'  => array(
				'default' => get_template_directory_uri() . '/assets/images/nav-model1.png',
				'style_2' => get_template_directory_uri() . '/assets/images/nav-model2.png',
				'style_3' => get_template_directory_uri() . '/assets/images/nav-model3.png',
				// 'style_3' => get_template_directory_uri() . '/assets/images/nav-model4.png',
			),
		);

		$fields[] = array(
			'type'     => 'select',
			'settings' => 'site_header_menu_effect',
			'label'    => esc_html__( 'Header Menu Effects', 'buddyxpro' ),
			'section'  => 'site_header_section',
			'default'  => 'default',
			'choices'  => array(
				'default' => esc_html__( 'No Effect', 'buddyxpro' ),
				'style_1' => esc_html__( 'Effect 1', 'buddyxpro' ),
				'style_2' => esc_html__( 'Effect 2', 'buddyxpro' ),
				'style_3' => esc_html__( 'Effect 3', 'buddyxpro' ),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_header_bg_color',
			'label'    => esc_html__( 'Header Background Color', 'buddyxpro' ),
			'section'  => 'site_header_section',
			'default'  => '#ffffff',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.site-header-wrapper, .layout-boxed .site-header-wrapper, .nav--toggle-sub ul ul, #user-profile-menu, .bp-header-submenu, .main-navigation .primary-menu-container.buddyx-mobile-menu, .main-navigation #user-profile-menu, .main-navigation .bp-header-submenu',
					'property' => 'background-color',
				),
				array(
					'element'  => '.site-header-wrapper',
					'property' => 'border-color',
				),
				array(
					'element'  => '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before',
					'property' => 'border-top-color',
				),
				array(
					'element'  => '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before',
					'property' => 'border-right-color',
				),
			),
		);

		/*
		 *  Site More Menu
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_more_menu',
			'label'    => esc_html__( 'More Menu ?', 'buddyxpro' ),
			'section'  => 'site_header_section',
			'default'  => '1',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		/*
		 *  Site Search
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_search',
			'label'    => esc_html__( 'Site Search ?', 'buddyxpro' ),
			'section'  => 'site_header_section',
			'default'  => '1',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		/*
		 *  Site Cart
		 */
		if ( function_exists( 'is_woocommerce' ) ) :
			$fields[] = array(
				'type'     => 'switch',
				'settings' => 'site_cart',
				'label'    => esc_html__( 'Site Cart ?', 'buddyxpro' ),
				'section'  => 'site_header_section',
				'default'  => '1',
				'choices'  => array(
					'on'  => esc_html__( 'Yes', 'buddyxpro' ),
					'off' => esc_html__( 'No', 'buddyxpro' ),
				),
			);
		endif;

		/**
		 *  Site Login
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_login_link',
			'label'    => esc_html__( 'Site Login Link ?', 'buddyxpro' ),
			'section'  => 'site_header_section',
			'default'  => '1',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		/**
		 *  Site Register
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_register_link',
			'label'    => esc_html__( 'Site Register Link ?', 'buddyxpro' ),
			'section'  => 'site_header_section',
			'default'  => '1',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		/**
		 *  Login/Register Buttons Style
		 */
		$fields[] = array(
			'type'            => 'radio',
			'settings'        => 'site_header_button_style',
			'label'           => esc_html__( 'Login/Register Button Style', 'buddyxpro' ),
			'section'         => 'site_header_section',
			'priority'        => 10,
			'default'         => 'icon-text',
			'choices'         => array(
				'icon-text'        => esc_html__( 'Icon+Text', 'buddyxpro' ),
				'only-icon'        => esc_html__( 'Only Icon', 'buddyxpro' ),
				'only-text'        => esc_html__( 'Only Text', 'buddyxpro' ),
				'icon-text-button' => esc_html__( 'Icon+Text Button', 'buddyxpro' ),
				'icon-button'      => esc_html__( 'Icon Button', 'buddyxpro' ),
				'text-button'      => esc_html__( 'Text Button', 'buddyxpro' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_login_link',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		/*
		 *  Site Sub Header
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_sub_header_bg',
			'label'    => esc_html__( 'Customize Background ?', 'buddyxpro' ),
			'section'  => 'site_sub_header_section',
			'default'  => 'off',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		$fields[] = array(
			'type'            => 'background',
			'settings'        => 'sub_header_background_setting',
			'label'           => esc_html__( 'Background Control', 'buddyxpro' ),
			'section'         => 'site_sub_header_section',
			'default'         => array(
				'background-color'      => 'rgba(255, 255, 255, 0.5)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element' => '.site-sub-header',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header_bg',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'site_sub_header_typography',
			'label'    => esc_html__( 'Content Typography', 'buddyxpro' ),
			'section'  => 'site_sub_header_section',
			'default'  => array(
				'font-family'     => '',
				'variant'         => '',
				'font-size'       => '',
				'line-height'     => '',
				'letter-spacing'  => '',
				'color'           => '#003049',
				'text-transform'  => 'none',
				'text-decoration' => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.site-sub-header, .site-sub-header .entry-header .entry-title, .site-sub-header .page-header .page-title, .site-sub-header .entry-header, .site-sub-header .page-header, .site-sub-header .entry-title, .site-sub-header .page-title',
				),
			),
		);

		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_breadcrumbs',
			'label'    => esc_html__( 'Site Breadcrumbs?', 'buddyxpro' ),
			'section'  => 'site_sub_header_section',
			'default'  => 'on',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		/*
		 * Site Skin
		 */
		$fields[] = array(
			'type'     => 'color',
			'settings' => 'body_background_color',
			'label'    => esc_html__( 'Body Background Color', 'buddyxpro' ),
			'section'  => 'site_skin_section',
			'default'  => '#f7f7f9',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => 'body, body.layout-boxed',
					'property' => 'background-color',
				),
			),
		);

		$fields[] = array(
			'type'            => 'color',
			'settings'        => 'content_background_color',
			'label'           => esc_html__( 'Content Background Color', 'buddyxpro' ),
			'description'     => esc_html__( 'Note: This setting will only be used if the box layout is selected.', 'buddyxpro' ),
			'section'         => 'site_skin_section',
			'default'         => '#f7f7f9',
			'choices'         => array( 'alpha' => true ),
			'priority'        => 10,
			'output'          => array(
				array(
					'element'  => 'body.layout-boxed .site',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting' => 'site_layout',
					'value'   => 'boxed',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_primary_color',
			'label'    => esc_html__( 'Theme Color', 'buddyxpro' ),
			'section'  => 'site_skin_section',
			'default'  => '#ee4036',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.registration-login-form .nav-link.active, .user-welcomeback .author-content .author-name, .post-meta-category.post-meta-category a, .buddyx-breadcrumbs a, #breadcrumbs a, .pagination .current, .buddypress-wrap .bp-navs li.current a, .buddypress-wrap .bp-navs li.selected a, .buddypress-wrap .bp-navs li:not(.current) a:focus, .buddypress-wrap .bp-navs li:not(.selected) a:focus, nav#object-nav.vertical .selected>a, .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li.current a, .buddypress-wrap .main-navs:not(.dir-navs) li.current a, .buddypress-wrap .main-navs:not(.dir-navs) li.selected a, .buddypress-wrap .bp-navs li.selected a:focus, .buddypress-wrap .bp-navs li.current a:focus,
					.woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link.is-active a, .media .rtm-tabs li.active a, .buddypress.widget .item-options a.selected,
                    .woocommerce.woo-product-style-1 li.product a.added_to_cart.wc-forward, .woocommerce.woo-product-style-1 li.product a.button.add_to_cart_button, .woocommerce.woo-product-style-1 li.product a.button.product_type_external, .woocommerce.woo-product-style-1 li.product a.button.product_type_grouped, .woocommerce.woo-product-style-1 li.product a.button.product_type_simple, .woocommerce.woo-product-style-1 li.product a.button.product_type_variable, .woocommerce ul.products li.product .price,

					.learndash-wrapper .ld-expand-button.ld-button-alternate,
					.learndash-wrapper .ld-item-list .ld-item-list-item a.ld-item-name:hover,
					.learndash-wrapper .ld-table-list .ld-table-list-item .ld-table-list-title a:hover,
					.learndash-wrapper .ld-table-list .ld-table-list-item .ld-table-list-title a:active,
					.learndash-wrapper .ld-table-list .ld-table-list-item .ld-table-list-title a:focus,
					.learndash-wrapper .ld-table-list a.ld-table-list-item-preview:hover,
					.learndash-wrapper .ld-table-list a.ld-table-list-item-preview:active,
					.learndash-wrapper .ld-table-list a.ld-table-list-item-preview:focus,
					.learndash-wrapper .ld-expand-button.ld-button-alternate:hover,
					.learndash-wrapper .ld-expand-button.ld-button-alternate:active,
					.learndash-wrapper .ld-expand-button.ld-button-alternate:focus,
					.learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item .ld-table-list-title a:hover, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item .ld-table-list-title a:active, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item .ld-table-list-title a:focus, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item a.ld-table-list-item-preview:hover, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item a.ld-table-list-item-preview:active, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item a.ld-table-list-item-preview:focus,
					
					.learndash-wrapper .ld-status-unlocked,
					#learndash_lesson_topics_list span a, #learndash_lessons a, #learndash_profile a, #learndash_profile a span, #learndash_quizzes a, .expand_collapse a, .learndash_topic_dots a, .learndash_topic_dots a>span,
					#learndash_lessons h4>a, #learndash_quizzes h4>a, #learndash_lesson_topics_list ul>li>span a, #learndash_course_content .learndash_topic_dots ul>li a, #learndash_profile .learndash-course-link a, #learndash_profile .quiz_title a, #learndash_profile .profile_edit_profile a,
					.learndash-wrapper .ld-course-navigation .ld-lesson-item.ld-is-current-lesson .ld-lesson-item-preview-heading, .learndash-wrapper .ld-course-navigation .ld-lesson-item.ld-is-current-lesson .ld-lesson-title,
					.learndash-wrapper .ld-course-navigation .ld-lesson-item-preview a.ld-lesson-item-preview-heading:hover,
					.learndash-wrapper .ld-button.ld-button-transparent,
					.learndash-wrapper .ld-focus .ld-focus-header .ld-button:hover,
					.learndash-wrapper .ld-breadcrumbs a:hover, .learndash-wrapper .ld-breadcrumbs a:active, .learndash-wrapper .ld-breadcrumbs a:focus,
					.learndash-wrapper .ld-content-actions>a:hover, .learndash-wrapper .ld-content-actions>a:active, .learndash-wrapper .ld-content-actions>a:focus,
					.learndash-wrapper .ld-tabs .ld-tabs-navigation .ld-tab.ld-active,
					.learndash-wrapper .ld-profile-summary .ld-profile-card .ld-profile-edit-link:hover,
					.learndash-wrapper .ld-item-list .ld-section-heading .ld-search-prompt:hover,
					#ld-profile .ld-item-list .ld-item-list-item a.ld-item-name:hover,
					.learndash-wrapper .ld-item-list .ld-item-search .ld-closer:hover, .learndash-wrapper .ld-item-list .ld-item-search .ld-closer:active, .learndash-wrapper .ld-item-list .ld-item-search .ld-closer:focus, .learndash-wrapper .ld-home-link:hover, .learndash-wrapper .ld-home-link:active, .learndash-wrapper .ld-home-link:focus,
					
					#learndash_lessons h4>a:hover, #learndash_lessons h4>a:active, #learndash_lessons h4>a:focus, #learndash_quizzes h4>a:hover, #learndash_quizzes h4>a:active, #learndash_quizzes h4>a:focus, #learndash_lesson_topics_list ul>li>span a:hover, #learndash_lesson_topics_list ul>li>span a:active, #learndash_lesson_topics_list ul>li>span a:focus, #learndash_course_content .learndash_topic_dots ul>li a:hover, #learndash_course_content .learndash_topic_dots ul>li a:active, #learndash_course_content .learndash_topic_dots ul>li a:focus, #learndash_profile .learndash-course-link a:hover, #learndash_profile .learndash-course-link a:active, #learndash_profile .learndash-course-link a:focus, #learndash_profile .quiz_title a:hover, #learndash_profile .quiz_title a:active, #learndash_profile .quiz_title a:focus, #learndash_profile .profile_edit_profile a:hover, #learndash_profile .profile_edit_profile a:active, #learndash_profile .profile_edit_profile a:focus,
                    ul.learn-press-courses .course .course-info .course-price .price, .widget .course-meta-field, .lp-single-course .course-price .price,
                    
                    .llms-student-dashboard .llms-sd-item.current>a, .llms-loop-item-content .llms-loop-title:hover, .llms-pagination ul li .page-numbers.current,
                    .dokan-pagination-container .dokan-pagination li.active a, .dokan-pagination-container .dokan-pagination li a:hover,
                    .tribe-common--breakpoint-medium.tribe-events-pro .tribe-events-pro-map__event-datetime-featured-text, .tribe-common--breakpoint-medium.tribe-events .tribe-events-calendar-list__event-datetime-featured-text, .tribe-common .tribe-common-c-svgicon, .tribe-common .tribe-common-cta--thin-alt:active, .tribe-common .tribe-common-cta--thin-alt:focus, .tribe-common .tribe-common-cta--thin-alt:hover, .tribe-common a:active, .tribe-common a:focus, .tribe-common a:hover, .tribe-events-cal-links .tribe-events-gcal, .tribe-events-cal-links .tribe-events-ical, .tribe-events-event-meta a, .tribe-events-event-meta a:visited, .tribe-events-pro .tribe-events-pro-organizer__meta-email-link, .tribe-events-pro .tribe-events-pro-organizer__meta-website-link, .tribe-events-pro .tribe-events-pro-photo__event-datetime-featured-text, .tribe-events-schedule .recurringinfo a, .tribe-events-single ul.tribe-related-events li .tribe-related-events-title a, .tribe-events-widget.tribe-events-widget .tribe-events-widget-events-list__view-more-link, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:active, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:focus, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:hover, .tribe-events .tribe-events-c-ical__link, .tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date, .tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link, .tribe-related-event-info .recurringinfo a, .tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum, .tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum-link',
					'property' => 'color',
				),
				array(
					'element'  => '#scrollUp:hover, .buddypress-icons-wrapper .bp-msg sup, .buddypress-icons-wrapper .user-notifications sup, .menu-icons-wrapper .cart sup, .buddypress-wrap .bp-navs li.current a .count, .buddypress-wrap .bp-navs li.dynamic.current a .count, .buddypress-wrap .bp-navs li.selected a .count, .buddypress_object_nav .bp-navs li.current a .count, .buddypress_object_nav .bp-navs li.selected a .count, .buddypress-wrap .bp-navs li.dynamic.selected a .count, .buddypress_object_nav .bp-navs li.dynamic a .count, .buddypress_object_nav .bp-navs li.dynamic.current a .count, .buddypress_object_nav .bp-navs li.dynamic.selected a .count, .bp-navs ul li .count, .buddypress-wrap .bp-navs li.dynamic a .count, .bp-single-vert-nav .bp-navs.vertical li span, .buddypress-wrap .bp-navs li.dynamic a:hover .count, .buddypress_object_nav .bp-navs li.dynamic a:hover .count, .buddypress-wrap .rtm-bp-navs ul li.selected a:hover>span, .buddypress-wrap .rtm-bp-navs ul li.selected a>span, .bp-pagination-links span.page-numbers:not(.dots),
					.entry .post-categories a, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
					nav.wcv-navigation ul.menu.vertical li.active, nav.wcv-navigation ul.menu.vertical li a:hover, nav.wcv-dashboard-navigation ul li a:hover, nav.wcv-navigation ul.menu.horizontal li.active a, nav.wcv-navigation ul.menu.horizontal li a:hover, .wcv-grid .wcv-media-uploader-gallery>a, .wcv-grid a.wcv-media-uploader-featured-add, .wcvendors-pro-dashboard-wrapper a.button, a.wcv-file-uploader-add_wcv_store_banner_id, a.wcv-file-uploader-add_wcv_store_icon_id, a.wcv-file-uploader-delete_wcv_store_banner_id, a.wcv-file-uploader-delete_wcv_store_icon_id,
                    body.course-item-popup.wpadminbar #course-item-content-header, .learn-press-progress .progress-bg .progress-active, .profile-list-table thead tr th, ul.learn-press-nav-tabs .course-nav.active:after, ul.learn-press-nav-tabs .course-nav:hover:after,
                    .woocommerce.woo-product-style-1 li.product a.added_to_cart.wc-forward:hover, .woocommerce.woo-product-style-1 li.product a.button.add_to_cart_button:hover, .woocommerce.woo-product-style-1 li.product a.button.product_type_external:hover, .woocommerce.woo-product-style-1 li.product a.button.product_type_grouped:hover, .woocommerce.woo-product-style-1 li.product a.button.product_type_simple:hover, .woocommerce.woo-product-style-1 li.product a.button.product_type_variable:hover, .woocommerce span.onsale, .woocommerce div.product ol.flex-control-nav.flex-control-thumbs button.slick-arrow,
                    
                    .llms-progress .progress-bar-complete, body .llms-syllabus-wrapper .llms-section-title,
                    .tribe-events-pro .tribe-events-pro-week-day-selector__events-icon, .tribe-events .tribe-events-c-ical__link:active, .tribe-events .tribe-events-c-ical__link:focus, .tribe-events .tribe-events-c-ical__link:hover, .tribe-events .tribe-events-calendar-list__event-row--featured .tribe-events-calendar-list__event-date-tag-datetime:after, .widget .tribe-events-widget .tribe-events-widget-events-list__event-row--featured .tribe-events-widget-events-list__event-date-tag-datetime:after',
					'property' => 'background-color',
				),
				array(
					'element'  => '.tribe-events .datepicker .day.active, .tribe-events .datepicker .day.active.focused, .tribe-events .datepicker .day.active:focus, .tribe-events .datepicker .day.active:hover, .tribe-events .datepicker .month.active, .tribe-events .datepicker .month.active.focused, .tribe-events .datepicker .month.active:focus, .tribe-events .datepicker .month.active:hover, .tribe-events .datepicker .year.active, .tribe-events .datepicker .year.active.focused, .tribe-events .datepicker .year.active:focus, .tribe-events .datepicker .year.active:hover, .tribe-events .tribe-events-c-events-bar__filter-button:before, .tribe-events .tribe-events-c-events-bar__search-button:before, .tribe-events .tribe-events-c-view-selector__button:before, .tribe-events .tribe-events-calendar-month__day-cell--selected, .tribe-events .tribe-events-calendar-month__day-cell--selected:focus, .tribe-events .tribe-events-calendar-month__day-cell--selected:hover, .tribe-events .tribe-events-calendar-list__event-date-tag-datetime, .tribe-events-pro .tribe-events-pro-photo__event-date-tag-datetime, .single-tribe_events .buddyx-event-heading .tribe-event-schedule-short .buddyx-schedule-short-date, .tribe-events-pro.tribe-events-view--week .datepicker .day.current:before',
					'property' => 'background',
				),
				array(
					'element'  => '.buddypress-wrap .bp-navs li.current a, .buddypress-wrap .bp-navs li.selected a, .woocommerce div.product .woocommerce-tabs ul.tabs li.active,
                    .lp-tab-sections .section-tab.active span, .course-curriculum ul.curriculum-sections .section-header,
                    
                    .llms-student-dashboard .llms-sd-item.current>a, .llms-student-dashboard .llms-sd-item>a:hover,
                    .tribe-common .tribe-common-cta--thin-alt, .tribe-common .tribe-common-cta--thin-alt:active, .tribe-common .tribe-common-cta--thin-alt:focus, .tribe-common .tribe-common-cta--thin-alt:hover, .tribe-events-pro .tribe-events-pro-map__event-card-wrapper--active .tribe-events-pro-map__event-card-button, .tribe-events-pro .tribe-events-pro-week-day-selector__day--active, .tribe-events .tribe-events-c-ical__link',
					'property' => 'border-color',
				),
				array(
					'element'  => '.tribe-common .tribe-common-anchor-thin:active, .tribe-common .tribe-common-anchor-thin:focus, .tribe-common .tribe-common-anchor-thin:hover, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:active, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:focus, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:hover',
					'property' => 'border-bottom-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_links_color',
			'label'    => esc_html__( 'Link Color', 'buddyxpro' ),
			'section'  => 'site_skin_section',
			'default'  => '#003049',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => 'a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_links_focus_hover_color',
			'label'    => esc_html__( 'Link Hover', 'buddyxpro' ),
			'section'  => 'site_skin_section',
			'default'  => '#ee4036',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => 'a:hover, a:active, a:focus, .buddypress-wrap .bp-navs li:not(.current) a:hover, .buddypress-wrap .bp-navs li:not(.selected) a:hover, .rtmedia-actions-before-comments .rtmedia-comment-link:hover, .rtmedia-actions-before-comments .rtmedia-view-conversation:hover, #buddypress .rtmedia-actions-before-comments .rtmedia-like:hover, .buddypress-wrap .bp-navs li:not(.current) a:focus, .buddypress-wrap .bp-navs li:not(.current) a:hover, .buddypress-wrap .bp-navs li:not(.selected) a:focus, .buddypress-wrap .bp-navs li:not(.selected) a:hover, nav#object-nav.vertical a:hover, #buddypress .activity-list .bb-activity-more-options-wrap .bb-activity-more-options .generic-button a:hover, .buddyx-post-options .buddyx-post-option-wrap.active, .buddyx-post-options .buddyx-post-option-wrap:hover, #buddypress .activity-list .bp-activity-more-options-wrap.selected .bp-activity-more-options-action, #buddypress .activity-list .bp-activity-more-options-wrap .bp-activity-more-options-action:hover,
					.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce ul.products li.product .woocommerce-loop-category__title:hover, .woocommerce ul.products li.product .woocommerce-loop-product__title:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'custom',
			'settings' => 'custom-skin-divider',
			'section'  => 'site_skin_section',
			'default'  => '<hr>',
		);

		// Site Buttons.
		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_background_color',
			'label'    => esc_html__( 'Button Background Color', 'buddyxpro' ),
			'section'  => 'site_skin_section',
			'default'  => '#ee4036',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.user-menu-dropdown .dropdown-footer a.button, .registration-login-submit, .registration-login-form-popup .icon-close, a.read-more.button, input[type="button"], input[type="reset"], button[type=submit], input[type="submit"], .buddypress-icons-wrapper .bp-icon-wrap.icon-button > a, .buddypress-icons-wrapper .bp-icon-wrap.icon-text-button > a, .buddypress-icons-wrapper .bp-icon-wrap.text-button > a,
					#buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content .generic-button a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default, .moderation-popup .modal-container .bb-model-footer .button.report-submit, button#bbp_topic_submit, button#bbp_reply_submit, .buddypress .buddypress-wrap button.mpp-button-primary, button#mpp-edit-media-submit, .ges-change, .buddypress .buddypress-wrap button.ges-change, .group-email-tooltip__close, .buddypress .buddypress-wrap button.group-email-tooltip__close, #bplock-login-btn, #bplock-register-btn, .bgr-submit-review, #bupr_save_review, button.friendship-button, button.group-button, .avatar-history-actions button.avatar-history-action.recycle, .avatar-history-actions button.avatar-history-action.delete, .avatar-history-actions button.recycle.disabled, .avatar-history-actions button.delete.disabled, #buddypress #header-cover-image .header-cover-reposition-wrap>.button,
					.woocommerce-product-search button[type=submit], .woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button,
					
					.ld-course-list-items .ld_course_grid .btn-primary,
					.learndash-wrapper .ld-expand-button,
					.learndash-wrapper .ld-expand-button.ld-button-alternate .ld-icon,
					.learndash-wrapper .ld-table-list .ld-table-list-header,
					.learndash-wrapper .ld-focus .ld-focus-sidebar .ld-course-navigation-heading,
					.learndash-wrapper .ld-focus .ld-focus-sidebar .ld-focus-sidebar-trigger,
					.learndash-wrapper .ld-button,
					.learndash-wrapper .ld-focus .ld-focus-header .ld-user-menu .ld-user-menu-items a,
					.learndash-wrapper .ld-button, .learndash-wrapper .ld-content-actions .ld-button, .learndash-wrapper .ld-expand-button, .learndash-wrapper .ld-alert .ld-button,
					.learndash-wrapper .ld-tabs .ld-tabs-navigation .ld-tab.ld-active:after,
                    .learndash-wrapper .btn-join, .learndash-wrapper #btn-join, .learndash-wrapper .learndash_mark_complete_button, .learndash-wrapper #learndash_mark_complete_button, .ld-course-status-action .ld-button, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary, .ldx-plugin .uo-toolkit-grid__course-action input, .learndash-resume-button input[type=submit], .learndash-reset-form .learndash-reset-button[type=submit], .learndash-wrapper .ld-login-modal input[type=submit], .learndash-wrapper .ld-login-button, .learndash-course-widget-wrap .ld-course-status-action a,
                    
                    .llms-button-secondary, .llms-button-primary, .llms-button-action, .llms-button-primary:focus, .llms-button-primary:active, .llms-button-action:focus, .llms-button-action:active,
                    .tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn',
					'property' => 'background',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_background_hover_color',
			'label'    => esc_html__( 'Button Background Hover Color', 'buddyxpro' ),
			'section'  => 'site_skin_section',
			'default'  => '#f83939',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.user-menu-dropdown .dropdown-footer a.button:hover, .registration-login-submit:hover, .registration-login-form-popup .icon-close:hover, a.read-more.button:hover, input[type="button"]:hover, input[type="reset"]:hover, button[type=submit]:hover, input[type="submit"]:hover, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus, .buddypress-icons-wrapper .bp-icon-wrap.icon-button > a:hover, .buddypress-icons-wrapper .bp-icon-wrap.icon-text-button > a:hover, .buddypress-icons-wrapper .bp-icon-wrap.text-button > a:hover,
					#buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content .generic-button a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover, .moderation-popup .modal-container .bb-model-footer .button.report-submit:hover, button#bbp_topic_submit:hover, button#bbp_reply_submit:hover, .buddypress .buddypress-wrap button.mpp-button-primary:hover, button#mpp-edit-media-submit:hover, .ges-change:hover, .buddypress .buddypress-wrap button.ges-change:hover, .group-email-tooltip__close:hover, .buddypress .buddypress-wrap button.group-email-tooltip__close:hover, #bplock-login-btn:hover, #bplock-register-btn:hover, .bgr-submit-review:hover, #bupr_save_review:hover, button.friendship-button:hover, button.group-button:hover, .avatar-history-actions button.avatar-history-action.recycle:hover, .avatar-history-actions button.avatar-history-action.delete:hover, .avatar-history-actions button.recycle.disabled:hover, .avatar-history-actions button.delete.disabled:hover, #buddypress #header-cover-image .header-cover-reposition-wrap>.button:hover, #buddypress #header-cover-image .header-cover-reposition-wrap>.button:focus,
					.woocommerce-product-search button[type=submit]:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover,
					
					.ld-course-list-items .ld_course_grid .btn-primary:hover,
					.learndash-wrapper .ld-expand-button:hover,
					.learndash-wrapper .ld-button:hover,
					.learndash-wrapper .ld-focus .ld-focus-header .ld-user-menu .ld-user-menu-items a:hover,
					.learndash-wrapper .ld-button:hover, .learndash-wrapper .ld-button:active, .learndash-wrapper .ld-button:focus, .learndash-wrapper .ld-content-actions .ld-button:hover, .learndash-wrapper .ld-content-actions .ld-button:active, .learndash-wrapper .ld-content-actions .ld-button:focus, .learndash-wrapper .ld-expand-button:hover, .learndash-wrapper .ld-expand-button:active, .learndash-wrapper .ld-expand-button:focus, .learndash-wrapper .ld-alert .ld-button:hover, .learndash-wrapper .ld-alert .ld-button:active, .learndash-wrapper .ld-alert .ld-button:focus,
                    .learndash-wrapper .btn-join:hover, .learndash-wrapper .btn-join:active, .learndash-wrapper .btn-join:focus, .learndash-wrapper #btn-join:hover, .learndash-wrapper #btn-join:active, .learndash-wrapper #btn-join:focus, .learndash-wrapper .learndash_mark_complete_button:hover, .learndash-wrapper .learndash_mark_complete_button:active, .learndash-wrapper .learndash_mark_complete_button:focus, .learndash-wrapper #learndash_mark_complete_button:hover, .learndash-wrapper #learndash_mark_complete_button:active, .learndash-wrapper #learndash_mark_complete_button:focus, .ld-course-status-action .ld-button:hover, .ld-course-status-action .ld-button:active, .ld-course-status-action .ld-button:focus, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button:hover, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button:active, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button:focus, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button:hover, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button:active, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button:focus, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary:hover, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary:active, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary:focus, .ldx-plugin .uo-toolkit-grid__course-action input:hover, .ldx-plugin .uo-toolkit-grid__course-action input:active, .ldx-plugin .uo-toolkit-grid__course-action input:focus, .learndash-resume-button input[type=submit]:hover, .learndash-resume-button input[type=submit]:active, .learndash-resume-button input[type=submit]:focus, .learndash-reset-form .learndash-reset-button[type=submit]:hover, .learndash-reset-form .learndash-reset-button[type=submit]:active, .learndash-reset-form .learndash-reset-button[type=submit]:focus, .learndash-wrapper .ld-login-modal input[type=submit]:hover, .learndash-wrapper .ld-login-modal input[type=submit]:active, .learndash-wrapper .ld-login-modal input[type=submit]:focus, .learndash-wrapper .ld-login-button:hover, .learndash-wrapper .ld-login-button:active, .learndash-wrapper .ld-login-button:focus, .learndash-course-widget-wrap .ld-course-status-action a:hover,
                    
                    .llms-button-secondary:hover, .llms-button-primary:hover, .llms-button-action:hover, .llms-button-action.clicked,
                    .tribe-common .tribe-common-c-btn:focus, .tribe-common .tribe-common-c-btn:hover, .tribe-common a.tribe-common-c-btn:focus, .tribe-common a.tribe-common-c-btn:hover',
					'property' => 'background',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_text_color',
			'label'    => esc_html__( 'Button Text Color', 'buddyxpro' ),
			'section'  => 'site_skin_section',
			'default'  => '#ffffff',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.user-menu-dropdown .dropdown-footer a.button, .registration-login-submit, .registration-login-form-popup .icon-close, a.read-more.button, input[type="button"], input[type="reset"], button[type=submit], input[type="submit"], .buddypress-icons-wrapper .bp-icon-wrap.icon-button > a, .buddypress-icons-wrapper .bp-icon-wrap.icon-text-button > a, .buddypress-icons-wrapper .bp-icon-wrap.text-button > a,
					#buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content .generic-button a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default, .moderation-popup .modal-container .bb-model-footer .button.report-submit, button#bbp_topic_submit, button#bbp_reply_submit, .buddypress .buddypress-wrap button.mpp-button-primary, button#mpp-edit-media-submit, .ges-change, .buddypress .buddypress-wrap button.ges-change, .group-email-tooltip__close, .buddypress .buddypress-wrap button.group-email-tooltip__close, #bplock-login-btn, #bplock-register-btn, .bgr-submit-review, #bupr_save_review, button.friendship-button, button.group-button, .avatar-history-actions button.avatar-history-action.recycle, .avatar-history-actions button.avatar-history-action.delete, .avatar-history-actions button.recycle.disabled, .avatar-history-actions button.delete.disabled, #buddypress #header-cover-image .header-cover-reposition-wrap>.button,
                    .woocommerce-product-search button[type=submit], .woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button,
                    .learndash-course-widget-wrap .ld-course-status-action a,
                    
                    .llms-button-secondary, .llms-button-primary, .llms-button-action, .llms-button-primary:focus, .llms-button-primary:active, .llms-button-action:focus, .llms-button-action:active,
                    .tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_text_hover_color',
			'label'    => esc_html__( 'Button Text Hover Color', 'buddyxpro' ),
			'section'  => 'site_skin_section',
			'default'  => '#ffffff',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.user-menu-dropdown .dropdown-footer a.button:hover, .registration-login-submit:hover, .registration-login-form-popup .icon-close:hover, a.read-more.button:hover, input[type="button"]:hover, input[type="reset"]:hover, button[type=submit]:hover, input[type="submit"]:hover, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus, .buddypress-icons-wrapper .bp-icon-wrap.icon-button > a:hover, .buddypress-icons-wrapper .bp-icon-wrap.icon-text-button > a:hover, .buddypress-icons-wrapper .bp-icon-wrap.text-button > a:hover,
					#buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content .generic-button a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover, .moderation-popup .modal-container .bb-model-footer .button.report-submit:hover, button#bbp_topic_submit:hover, button#bbp_reply_submit:hover, .buddypress .buddypress-wrap button.mpp-button-primary:hover, button#mpp-edit-media-submit:hover, .ges-change:hover, .buddypress .buddypress-wrap button.ges-change:hover, .group-email-tooltip__close:hover, .buddypress .buddypress-wrap button.group-email-tooltip__close:hover, #bplock-login-btn:hover, #bplock-register-btn:hover, .bgr-submit-review:hover, #bupr_save_review:hover, button.friendship-button:hover, button.group-button:hover, .avatar-history-actions button.avatar-history-action.recycle:hover, .avatar-history-actions button.avatar-history-action.delete:hover, .avatar-history-actions button.recycle.disabled:hover, .avatar-history-actions button.delete.disabled:hover, #buddypress #header-cover-image .header-cover-reposition-wrap>.button:hover, #buddypress #header-cover-image .header-cover-reposition-wrap>.button:focus,
					.woocommerce-product-search button[type=submit]:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover,
                    .learnpress #learn-press-profile-nav .tabs > li:hover:not(.active) > a, .learnpress #learn-press-profile-nav .tabs > li ul li:hover a,
                    .learndash-course-widget-wrap .ld-course-status-action a:hover,
                    
                    .llms-button-secondary:hover, .llms-button-primary:hover, .llms-button-action:hover, .llms-button-action.clicked,
                    .tribe-common .tribe-common-c-btn:focus, .tribe-common .tribe-common-c-btn:hover, .tribe-common a.tribe-common-c-btn:focus, .tribe-common a.tribe-common-c-btn:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_border_color',
			'label'    => esc_html__( 'Button Border Color', 'buddyxpro' ),
			'section'  => 'site_skin_section',
			'default'  => '#ee4036',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.user-menu-dropdown .dropdown-footer a.button, .registration-login-submit, .registration-login-form-popup .icon-close, a.read-more.button, input[type="button"], input[type="reset"], button[type=submit], input[type="submit"], .buddypress-icons-wrapper .bp-icon-wrap.icon-button > a, .buddypress-icons-wrapper .bp-icon-wrap.icon-text-button > a, .buddypress-icons-wrapper .bp-icon-wrap.text-button > a,
					#buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content .generic-button a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default, .moderation-popup .modal-container .bb-model-footer .button.report-submit, button#bbp_topic_submit, button#bbp_reply_submit, .buddypress .buddypress-wrap button.mpp-button-primary, button#mpp-edit-media-submit, .ges-change, .buddypress .buddypress-wrap button.ges-change, .group-email-tooltip__close, .buddypress .buddypress-wrap button.group-email-tooltip__close, #bplock-login-btn, #bplock-register-btn, .bgr-submit-review, #bupr_save_review, button.friendship-button, button.group-button, .avatar-history-actions button.avatar-history-action.recycle, .avatar-history-actions button.avatar-history-action.delete, .avatar-history-actions button.recycle.disabled, .avatar-history-actions button.delete.disabled, #buddypress #header-cover-image .header-cover-reposition-wrap>.button,
                    .woocommerce-product-search button[type=submit], .woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button,
                    
                    .llms-button-secondary, .llms-button-primary, .llms-button-action, .llms-button-primary:focus, .llms-button-primary:active, .llms-button-action:focus, .llms-button-action:active,
                    .tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_border_hover_color',
			'label'    => esc_html__( 'Button Border Hover Color', 'buddyxpro' ),
			'section'  => 'site_skin_section',
			'default'  => '#f83939',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.user-menu-dropdown .dropdown-footer a.button:hover, .registration-login-submit:hover, .registration-login-form-popup .icon-close:hover, a.read-more.button:hover, input[type="button"]:hover, input[type="reset"]:hover, button[type=submit]:hover, input[type="submit"]:hover, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus, .buddypress-icons-wrapper .bp-icon-wrap.icon-button > a:hover, .buddypress-icons-wrapper .bp-icon-wrap.icon-text-button > a:hover, .buddypress-icons-wrapper .bp-icon-wrap.text-button > a:hover,
					#buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content .generic-button a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover, .moderation-popup .modal-container .bb-model-footer .button.report-submit:hover, button#bbp_topic_submit:hover, button#bbp_reply_submit:hover, .buddypress .buddypress-wrap button.mpp-button-primary:hover, button#mpp-edit-media-submit:hover, .ges-change:hover, .buddypress .buddypress-wrap button.ges-change:hover, .group-email-tooltip__close:hover, .buddypress .buddypress-wrap button.group-email-tooltip__close:hover, #bplock-login-btn:hover, #bplock-register-btn:hover, .bgr-submit-review:hover, #bupr_save_review:hover, button.friendship-button:hover, button.group-button:hover, .avatar-history-actions button.avatar-history-action.recycle:hover, .avatar-history-actions button.avatar-history-action.delete:hover, .avatar-history-actions button.recycle.disabled:hover, .avatar-history-actions button.delete.disabled:hover, #buddypress #header-cover-image .header-cover-reposition-wrap>.button:hover, #buddypress #header-cover-image .header-cover-reposition-wrap>.button:focus,
                    .woocommerce-product-search button[type=submit]:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover,
                    
                    .llms-button-secondary:hover, .llms-button-primary:hover, .llms-button-action:hover, .llms-button-action.clicked,
                    .tribe-common .tribe-common-c-btn:focus, .tribe-common .tribe-common-c-btn:hover, .tribe-common a.tribe-common-c-btn:focus, .tribe-common a.tribe-common-c-btn:hover',
					'property' => 'border-color',
				),
			),
		);

		/*
		 *  Site Blog Layout
		 */
		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'blog_layout_option',
			'label'    => esc_html__( 'Blog Layout', 'buddyxpro' ),
			'section'  => 'site_blog_section',
			'priority' => 10,
			'default'  => 'default-layout',
			'choices'  => array(
				'default-layout' => get_template_directory_uri() . '/assets/images/default-layout.png',
				'list-layout'    => get_template_directory_uri() . '/assets/images/list-layout.png',
				'grid-layout'    => get_template_directory_uri() . '/assets/images/grid-layout.png',
				'masonry-layout' => get_template_directory_uri() . '/assets/images/masonry-layout.png',
			),
		);

		$fields[] = array(
			'type'            => 'radio',
			'settings'        => 'blog_image_position',
			'label'           => esc_html__( 'Image position', 'buddyxpro' ),
			'section'         => 'site_blog_section',
			'priority'        => 10,
			'default'         => 'thumb-left',
			'choices'         => array(
				'thumb-left'  => esc_html__( 'Left', 'buddyxpro' ),
				'thumb-right' => esc_html__( 'Right', 'buddyxpro' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_layout_option',
					'operator' => '==',
					'value'    => 'list-layout',
				),
			),
		);

		$fields[] = array(
			'type'            => 'radio',
			'settings'        => 'blog_grid_columns',
			'label'           => esc_html__( 'Grid Columns', 'buddyxpro' ),
			'section'         => 'site_blog_section',
			'priority'        => 10,
			'default'         => 'one-column',
			'choices'         => array(
				'one-column' => esc_html__( 'One', 'buddyxpro' ),
				'two-column' => esc_html__( 'Two', 'buddyxpro' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_layout_option',
					'operator' => '==',
					'value'    => 'grid-layout',
				),
			),
		);

		$fields[] = array(
			'type'            => 'radio',
			'settings'        => 'blog_masonry_view',
			'label'           => esc_html__( 'View', 'buddyxpro' ),
			'section'         => 'site_blog_section',
			'priority'        => 10,
			'default'         => 'without-masonry',
			'choices'         => array(
				'without-masonry' => esc_html__( 'Without Masonry', 'buddyxpro' ),
				'with-masonry'    => esc_html__( 'With Masonry', 'buddyxpro' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_layout_option',
					'operator' => '==',
					'value'    => 'masonry-layout',
				),
			),
		);

		$fields[] = array(
			'type'            => 'select',
			'settings'        => 'post_per_row',
			'label'           => esc_html__( 'Post Per Row', 'buddyxpro' ),
			'section'         => 'site_blog_section',
			'default'         => 'buddyx-masonry-2',
			'priority'        => 10,
			'choices'         => array(
				'buddyx-masonry-2' => esc_html__( 'Two', 'buddyxpro' ),
				'buddyx-masonry-3' => esc_html__( 'Three', 'buddyxpro' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_layout_option',
					'operator' => '==',
					'value'    => 'masonry-layout',
				),
			),
		);

		$fields[] = array(
			'type'     => 'custom',
			'settings' => 'custom-skin-divider1',
			'section'  => 'site_blog_section',
			'default'  => '<hr>',
		);

		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'single_post_content_width',
			'label'    => esc_html__( 'Single Post Content Width', 'buddyxpro' ),
			'section'  => 'site_blog_section',
			'priority' => 10,
			'default'  => 'small',
			'choices'  => array(
				'small' => get_template_directory_uri() . '/assets/images/small.png',
				'large' => get_template_directory_uri() . '/assets/images/large.png',
			),
		);

		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'single_post_title_layout',
			'label'    => esc_html__( 'Single Post Title Layout', 'buddyxpro' ),
			'section'  => 'site_blog_section',
			'priority' => 10,
			'default'  => 'buddyx-section-title-above',
			'choices'  => array(
				'buddyx-section-title-over'  => get_template_directory_uri() . '/assets/images/single-blog-layout-1.png',
				'buddyx-section-half'        => get_template_directory_uri() . '/assets/images/single-blog-layout-2.png',
				'buddyx-section-title-above' => get_template_directory_uri() . '/assets/images/single-blog-layout-3.png',
				'buddyx-section-title-below' => get_template_directory_uri() . '/assets/images/single-blog-layout-4.png',
			),
		);

		$fields[] = array(
			'type'            => 'color',
			'settings'        => 'buddyx_section_title_over_overlay',
			'label'           => esc_attr__( 'Image Overlay Color', 'buddyxpro' ),
			'description'     => esc_attr__( 'Allow to add image overlay color on single post title layout one.', 'buddyxpro' ),
			'section'         => 'site_blog_section',
			'default'         => 'rgba(0, 0, 0, 0.1)',
			'priority'        => 10,
			'choices'         => array( 'alpha' => true ),
			'output'          => array(
				array(
					'function' => 'css',
					'element'  => '.buddyx-section-title-over.has-featured-image.has-featured-image .post-thumbnail:after',
					'property' => 'background',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_post_title_layout',
					'operator' => '==',
					'value'    => 'buddyx-section-title-over',
				),
			),
		);

		$fields[] = array(
			'type'     => 'custom',
			'settings' => 'custom-skin-divider2',
			'section'  => 'site_blog_section',
			'default'  => '<hr>',
		);

		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'single_post_social_box',
			'label'    => esc_html__( 'Show Social Box?', 'buddyxpro' ),
			'section'  => 'site_blog_section',
			'default'  => 'on',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		$fields[] = array(
			'type'            => 'sortable',
			'settings'        => 'single_post_social_link',
			'label'           => esc_html__( 'Show Social Links', 'buddyxpro' ),
			'section'         => 'site_blog_section',
			'default'         => array(
				'facebook',
				'twitter',
				'pinterest',
				'linkedin',
				'whatsapp',
			),
			'choices'         => array(
				'facebook'  => esc_html__( 'Facebook', 'buddyxpro' ),
				'twitter'   => esc_html__( 'Twitter', 'buddyxpro' ),
				'pinterest' => esc_html__( 'Pinterest', 'buddyxpro' ),
				'linkedin'  => esc_html__( 'LinkedIn', 'buddyxpro' ),
				'whatsapp'  => esc_html__( 'WhatsApp', 'buddyxpro' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_post_social_box',
					'operator' => '==',
					'value'    => true,
				),
			),
		);

		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'single_post_progressbar',
			'label'    => esc_html__( 'Single Blog ProgressBar?', 'buddyxpro' ),
			'section'  => 'site_blog_section',
			'default'  => 'on',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		/*
		 *  Site Sidebar Layout
		 */
		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'sidebar_option',
			'label'    => esc_html__( 'Default Sidebar Layout', 'buddyxpro' ),
			'section'  => 'site_sidebar_layout',
			'priority' => 10,
			'default'  => 'right',
			'choices'  => array(
				'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
				'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
				'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
				'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
			),
		);

		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'single_post_sidebar_option',
			'label'    => esc_html__( 'Single Post Sidebar Layout', 'buddyxpro' ),
			'section'  => 'site_sidebar_layout',
			'priority' => 10,
			'default'  => 'none',
			'choices'  => array(
				'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
				'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
				'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
				'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
			),
		);

		if ( function_exists( 'bp_is_active' ) ) {
			if ( ! class_exists( 'Youzify' ) ) {
				$fields[] = array(
					'type'     => 'radio-image',
					'settings' => 'buddypress_sidebar_option',
					'label'    => esc_html__( 'Activity Directory Sidebar Layout', 'buddyxpro' ),
					'section'  => 'site_sidebar_layout',
					'priority' => 10,
					'default'  => 'both',
					'choices'  => array(
						'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
						'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
						'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
						'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
					),
				);

				$fields[] = array(
					'type'     => 'radio-image',
					'settings' => 'buddypress_members_sidebar_option',
					'label'    => esc_html__( 'Members Directory Sidebar Layout', 'buddyxpro' ),
					'section'  => 'site_sidebar_layout',
					'priority' => 10,
					'default'  => 'right',
					'choices'  => array(
						'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
						'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
						'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
						'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
					),
				);

				$fields[] = array(
					'type'     => 'radio-image',
					'settings' => 'buddypress_groups_sidebar_option',
					'label'    => esc_html__( 'Groups Directory Sidebar Layout', 'buddyxpro' ),
					'section'  => 'site_sidebar_layout',
					'priority' => 10,
					'default'  => 'right',
					'choices'  => array(
						'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
						'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
						'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
						'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
					),
				);
			}
		}

		if ( function_exists( 'is_bbpress' ) ) {
			$fields[] = array(
				'type'     => 'radio-image',
				'settings' => 'bbpress_sidebar_option',
				'label'    => esc_html__( 'bbPress Sidebar Layout', 'buddyxpro' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => array(
					'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				),
			);
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$fields[] = array(
				'type'     => 'radio-image',
				'settings' => 'woocommerce_sidebar_option',
				'label'    => esc_html__( 'WooCommerce Sidebar Layout', 'buddyxpro' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => array(
					'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				),
			);
		}

		if ( class_exists( 'SFWD_LMS' ) ) {
			$fields[] = array(
				'type'     => 'radio-image',
				'settings' => 'ld_sidebar_option',
				'label'    => esc_html__( 'LearnDash Sidebar Layout', 'buddyxpro' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => array(
					'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				),
			);
		}

		if ( class_exists( 'LearnPress' ) ) {
			$fields[] = array(
				'type'     => 'radio-image',
				'settings' => 'lp_sidebar_option',
				'label'    => esc_html__( 'LearnPress Sidebar Layout', 'buddyxpro' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => array(
					'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				),
			);
		}

		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'sticky_sidebar_option',
			'label'    => esc_html__( 'Sticky Sidebar ?', 'buddyxpro' ),
			'section'  => 'site_sidebar_layout',
			'default'  => '1',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		/*
		 *  BuddyPress
		 */
		if ( class_exists( 'BuddyPress' ) ) {

			$fields[] = array(
				'type'     => 'switch',
				'settings' => 'buddypress_member_cover_image_activity',
				'label'    => esc_html__( 'Member Cover Image Activity', 'buddyxpro' ),
				'section'  => 'site_buddypress_activity_section',
				'default'  => 'on',
				'choices'  => array(
					'on'  => esc_html__( 'Yes', 'buddyxpro' ),
					'off' => esc_html__( 'No', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'switch',
				'settings' => 'buddypress_group_image_activity',
				'label'    => esc_html__( 'Group Image Activity', 'buddyxpro' ),
				'section'  => 'site_buddypress_activity_section',
				'default'  => 'on',
				'choices'  => array(
					'on'  => esc_html__( 'Yes', 'buddyxpro' ),
					'off' => esc_html__( 'No', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'switch',
				'settings' => 'buddypress_group_cover_image_activity',
				'label'    => esc_html__( 'Group Cover Image Activity', 'buddyxpro' ),
				'section'  => 'site_buddypress_activity_section',
				'default'  => 'on',
				'choices'  => array(
					'on'  => esc_html__( 'Yes', 'buddyxpro' ),
					'off' => esc_html__( 'No', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'select',
				'settings' => 'buddypress_memberes_directory_view',
				'label'    => esc_html__( 'Members Directory View', 'buddyxpro' ),
				'section'  => 'site_buddypress_members_section',
				'default'  => 'card',
				'choices'  => array(
					'default' => esc_html__( 'Default', 'buddyxpro' ),
					'card'    => esc_html__( 'Card', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'            => 'switch',
				'settings'        => 'buddypress_memberes_directory_customize',
				'label'           => esc_html__( 'Customize Members Cover Background ?', 'buddyxpro' ),
				'section'         => 'site_buddypress_members_section',
				'default'         => 'off',
				'choices'         => array(
					'on'  => esc_html__( 'Yes', 'buddyxpro' ),
					'off' => esc_html__( 'No', 'buddyxpro' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'buddypress_memberes_directory_view',
						'operator' => '==',
						'value'    => 'card',
					),
				),
			);

			$fields[] = array(
				'type'            => 'image',
				'settings'        => 'buddypress_memberes_directory_cover',
				'label'           => esc_html__( 'Set Default Cover Image', 'buddyxpro' ),
				'description'     => esc_html__( 'Set members directory custom cover image', 'buddyxpro' ),
				'section'         => 'site_buddypress_members_section',
				'active_callback' => array(
					array(
						'setting'  => 'buddypress_memberes_directory_view',
						'operator' => '==',
						'value'    => 'card',
					),
					array(
						'setting'  => 'buddypress_memberes_directory_customize',
						'operator' => '==',
						'value'    => '1',
					),
				),
			);

			$fields[] = array(
				'type'     => 'select',
				'settings' => 'buddypress_groups_directory_view',
				'label'    => esc_html__( 'Groups Directory View', 'buddyxpro' ),
				'section'  => 'site_buddypress_groups_section',
				'default'  => 'card',
				'choices'  => array(
					'default' => esc_html__( 'Default', 'buddyxpro' ),
					'card'    => esc_html__( 'Card', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'            => 'switch',
				'settings'        => 'buddypress_groups_directory_customize',
				'label'           => esc_html__( 'Customize Groups Cover Background ?', 'buddyxpro' ),
				'section'         => 'site_buddypress_groups_section',
				'default'         => 'off',
				'choices'         => array(
					'on'  => esc_html__( 'Yes', 'buddyxpro' ),
					'off' => esc_html__( 'No', 'buddyxpro' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'buddypress_groups_directory_view',
						'operator' => '==',
						'value'    => 'card',
					),
				),
			);

			$fields[] = array(
				'type'            => 'image',
				'settings'        => 'buddypress_groups_directory_cover',
				'label'           => esc_html__( 'Set Default Cover Image', 'buddyxpro' ),
				'description'     => esc_html__( 'Set groups directory custom cover image', 'buddyxpro' ),
				'section'         => 'site_buddypress_groups_section',
				'active_callback' => array(
					array(
						'setting'  => 'buddypress_groups_directory_view',
						'operator' => '==',
						'value'    => 'card',
					),
					array(
						'setting'  => 'buddypress_groups_directory_customize',
						'operator' => '==',
						'value'    => '1',
					),
				),
			);

			$fields[] = array(
				'type'     => 'select',
				'settings' => 'buddypress_single_member_view',
				'label'    => esc_html__( 'Single Member Cover Image Layout', 'buddyxpro' ),
				'section'  => 'site_buddypress_single_member_section',
				'default'  => 'full',
				'choices'  => array(
					'default' => esc_html__( 'Default View', 'buddyxpro' ),
					'center'  => esc_html__( 'Center View', 'buddyxpro' ),
					'compact' => esc_html__( 'Compact View', 'buddyxpro' ),
					'both'    => esc_html__( 'Center + Compact View', 'buddyxpro' ),
					'full'    => esc_html__( 'Full Width View', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'select',
				'settings' => 'buddypress_single_member_nav_style',
				'label'    => esc_html__( 'Single Member Navigation Style', 'buddyxpro' ),
				'section'  => 'site_buddypress_single_member_section',
				'default'  => 'iconic',
				'choices'  => array(
					'default' => esc_html__( 'Default', 'buddyxpro' ),
					'iconic'  => esc_html__( 'Icon + Label', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'select',
				'settings' => 'buddypress_single_member_nav_view',
				'label'    => esc_html__( 'Single Member Navigation View', 'buddyxpro' ),
				'section'  => 'site_buddypress_single_member_section',
				'default'  => 'swipe',
				'choices'  => array(
					'more'  => esc_html__( 'More', 'buddyxpro' ),
					'swipe' => esc_html__( 'Swipe', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'select',
				'settings' => 'buddypress_single_group_view',
				'label'    => esc_html__( 'Single Group Cover Image Layout', 'buddyxpro' ),
				'section'  => 'site_buddypress_single_group_section',
				'default'  => 'full',
				'choices'  => array(
					'default' => esc_html__( 'Default View', 'buddyxpro' ),
					'center'  => esc_html__( 'Center View', 'buddyxpro' ),
					'compact' => esc_html__( 'Compact View', 'buddyxpro' ),
					'both'    => esc_html__( 'Center + Compact View', 'buddyxpro' ),
					'full'    => esc_html__( 'Full Width View', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'select',
				'settings' => 'buddypress_single_group_nav_style',
				'label'    => esc_html__( 'Single Group Navigation Style', 'buddyxpro' ),
				'section'  => 'site_buddypress_single_group_section',
				'default'  => 'iconic',
				'choices'  => array(
					'default' => esc_html__( 'Default', 'buddyxpro' ),
					'iconic'  => esc_html__( 'Icon + Label', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'select',
				'settings' => 'buddypress_single_group_nav_view',
				'label'    => esc_html__( 'Single Group Navigation View', 'buddyxpro' ),
				'section'  => 'site_buddypress_single_group_section',
				'default'  => 'swipe',
				'choices'  => array(
					'more'  => esc_html__( 'More', 'buddyxpro' ),
					'swipe' => esc_html__( 'Swipe', 'buddyxpro' ),
				),
			);
		}

		// LearnDash.
		if ( class_exists( 'SFWD_LMS' ) ) {
			$fields[] = array(
				'type'     => 'switch',
				'settings' => 'ld_category_filter',
				'label'    => esc_html__( 'Categories Filter', 'buddyxpro' ),
				'section'  => 'site_learndash_section',
				'default'  => 'on',
				'choices'  => array(
					'on'  => esc_html__( 'Yes', 'buddyxpro' ),
					'off' => esc_html__( 'No', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'switch',
				'settings' => 'ld_instructors_filter',
				'label'    => esc_html__( 'Instructors Filter', 'buddyxpro' ),
				'section'  => 'site_learndash_section',
				'default'  => 'off',
				'choices'  => array(
					'on'  => esc_html__( 'Yes', 'buddyxpro' ),
					'off' => esc_html__( 'No', 'buddyxpro' ),
				),
			);
		}

		/*
		 *  WooCommerce
		 */
		if ( class_exists( 'WooCommerce' ) ) {
			$fields[] = array(
				'type'     => 'radio',
				'settings' => 'buddyx_woo_sale_badge_style',
				'label'    => esc_html__( 'Sale Badge Style', 'buddyxpro' ),
				'section'  => 'site_woocommerce_general_section',
				'default'  => 'default',
				'choices'  => array(
					'default' => esc_html__( 'Default', 'buddyxpro' ),
					'square'  => esc_html__( 'Square', 'buddyxpro' ),
					'circle'  => esc_html__( 'Circle', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'radio',
				'settings' => 'buddyx_woo_sale_badge_position',
				'label'    => esc_html__( 'Sale Badge Position', 'buddyxpro' ),
				'section'  => 'site_woocommerce_general_section',
				'default'  => 'right',
				'choices'  => array(
					'left'  => esc_html__( 'Left', 'buddyxpro' ),
					'right' => esc_html__( 'Right', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'radio',
				'settings' => 'buddyx_woo_sale_badge_content',
				'label'    => esc_html__( 'Sale Badge Content', 'buddyxpro' ),
				'section'  => 'site_woocommerce_general_section',
				'default'  => 'text',
				'choices'  => array(
					'text'    => esc_html__( 'Sale Text', 'buddyxpro' ),
					'percent' => esc_html__( 'Percentage', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'     => 'radio',
				'settings' => 'woocommerce_product_style',
				'label'    => esc_html__( 'Product Style', 'buddyxpro' ),
				'section'  => 'site_woocommerce_shop_section',
				'default'  => 'default',
				'choices'  => array(
					'default' => esc_html__( 'Default', 'buddyxpro' ),
					'style_1' => esc_html__( 'Style 1', 'buddyxpro' ),
					'style_2' => esc_html__( 'Style 2', 'buddyxpro' ),
					'style_3' => esc_html__( 'Style 3', 'buddyxpro' ),
				),
			);

			$fields[] = array(
				'type'              => 'checkbox',
				'settings'          => 'buddyx_woo_shop_sort',
				'label'             => esc_html__( 'Product Sort', 'buddyxpro' ),
				'section'           => 'site_woocommerce_shop_section',
				'sanitize_callback' => 'buddyx_sanitize_checkbox',
				'default'           => true,
			);

			$fields[] = array(
				'type'              => 'checkbox',
				'settings'          => 'buddyx_woo_shop_result_count',
				'label'             => esc_html__( 'Product Result Count', 'buddyxpro' ),
				'section'           => 'site_woocommerce_shop_section',
				'sanitize_callback' => 'buddyx_sanitize_checkbox',
				'default'           => true,
			);

			$fields[] = array(
				'type'              => 'slider',
				'settings'          => 'buddyx_woo_product_per_page',
				'label'             => esc_html__( 'Products Per Page', 'buddyxpro' ),
				'description'       => esc_html__( 'Set archive product listing per page.', 'buddyxpro' ),
				'section'           => 'site_woocommerce_shop_section',
				'default'           => '16',
				'sanitize_callback' => 'buddyx_sanitize_number',
				'choices'           => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			);

			$fields[] = array(
				'type'              => 'checkbox',
				'settings'          => 'buddyx_woo_off_canvas_filter',
				'label'             => esc_html__( 'Display Filter Button', 'buddyxpro' ),
				'description'       => esc_html__( 'Set filters for archive products. (Go to the Appearance > Widgets > Off Canvas Sidebar)', 'buddyxpro' ),
				'section'           => 'site_woocommerce_shop_section',
				'sanitize_callback' => 'buddyx_sanitize_checkbox',
				'default'           => false,
			);

			$fields[] = array(
				'type'            => 'text',
				'settings'        => 'buddyx_woo_off_canvas_filter_text',
				'label'           => esc_html__( 'Filter Button Text', 'buddyxpro' ),
				'section'         => 'site_woocommerce_shop_section',
				'default'         => esc_html__( 'Filter', 'buddyxpro' ),
				'active_callback' => 'buddyx_cac_has_woo_filter_button',
			);
		}

		/*
		 *  Dokan
		 */
		if ( class_exists( 'WeDevs_Dokan' ) ) {
			$fields[] = array(
				'type'     => 'radio',
				'settings' => 'store_header_position',
				'label'    => esc_html__( 'Store Header Position', 'buddyxpro' ),
				'section'  => 'dokan_store',
				'default'  => 'top',
				'priority' => 0,
				'choices'  => array(
					'top'   => esc_html__( 'Top', 'buddyxpro' ),
					'inner' => esc_html__( 'Inner', 'buddyxpro' ),
				),
			);
		}

		/*
		 *  Site Footer
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_footer_bg',
			'label'    => esc_html__( 'Customize Background ?', 'buddyxpro' ),
			'section'  => 'site_footer_section',
			'default'  => 'off',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'buddyxpro' ),
				'off' => esc_html__( 'No', 'buddyxpro' ),
			),
		);

		$fields[] = array(
			'type'            => 'background',
			'settings'        => 'background_setting',
			'label'           => esc_html__( 'Background Control', 'buddyxpro' ),
			'section'         => 'site_footer_section',
			'default'         => array(
				'background-color'      => 'rgba(255,255,255,0.8)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element' => '.site-footer-wrapper',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_footer_bg',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_footer_title_color',
			'label'    => esc_html__( 'Title Color', 'buddyxpro' ),
			'section'  => 'site_footer_section',
			'default'  => '#003049',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.site-footer .widget-title',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_footer_content_color',
			'label'    => esc_html__( 'Content Color', 'buddyxpro' ),
			'section'  => 'site_footer_section',
			'default'  => '#505050',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.site-footer',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_footer_links_color',
			'label'    => esc_html__( 'Link Color', 'buddyxpro' ),
			'section'  => 'site_footer_section',
			'default'  => '#003049',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.site-footer a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_footer_links_hover_color',
			'label'    => esc_html__( 'Link Hover', 'buddyxpro' ),
			'section'  => 'site_footer_section',
			'default'  => '#ee4036',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.site-footer a:hover, .site-footer a:active',
					'property' => 'color',
				),
			),
		);

		/*
		 *  Site Copyright
		 */
		$fields[] = array(
			'type'        => 'textarea',
			'settings'    => 'site_copyright_text',
			'label'       => esc_html__( 'Add Content', 'buddyxpro' ),
			'description' => esc_html__( 'You can use [current_year], [site_title], [theme_author] shortcode if you want.', 'buddyxpro' ),
			'section'     => 'site_copyright_section',
			'default'     => esc_html__( 'Copyright © [current_year]. All rights reserved by [site_title] | Powered by [theme_author]', 'buddyxpro' ),
			'priority'    => 10,
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_copyright_background_color',
			'label'    => esc_html__( 'Background Color', 'buddyxpro' ),
			'section'  => 'site_copyright_section',
			'default'  => '#ffffff',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.site-info',
					'property' => 'background-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_copyright_border_color',
			'label'    => esc_html__( 'Border Color', 'buddyxpro' ),
			'section'  => 'site_copyright_section',
			'default'  => '#e8e8e8',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.site-info',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_copyright_content_color',
			'label'    => esc_html__( 'Content Color', 'buddyxpro' ),
			'section'  => 'site_copyright_section',
			'default'  => '#505050',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.site-info',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_copyright_links_color',
			'label'    => esc_html__( 'Link Color', 'buddyxpro' ),
			'section'  => 'site_copyright_section',
			'default'  => '#003049',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.site-info a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_copyright_links_hover_color',
			'label'    => esc_html__( 'Link Hover Color', 'buddyxpro' ),
			'section'  => 'site_copyright_section',
			'default'  => '#ee4036',
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => '.site-info a:hover',
					'property' => 'color',
				),
			),
		);

		return $fields;
	}
}
