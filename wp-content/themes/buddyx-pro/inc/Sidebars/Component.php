<?php
/**
 * BuddyxPro\BuddyxPro\Sidebars\Component class
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro\Sidebars;

use BuddyxPro\BuddyxPro\Component_Interface;
use BuddyxPro\BuddyxPro\Templating_Component_Interface;
use function add_action;
use function add_filter;
use function register_sidebar;
//use function esc_html__;
use function is_active_sidebar;
use function dynamic_sidebar;

/**
 * Class for managing sidebars.
 *
 * Exposes template tags:
 * * `buddyxpro()->is_right_sidebar_active()`
 * * `buddyxpro()->display_primary_sidebar()`
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	const LEFT_SIDEBAR_SLUG  = 'sidebar-left';
	const RIGHT_SIDEBAR_SLUG = 'sidebar-right';
	const BUDDYPRESS_LEFT_SIDEBAR_SLUG = 'buddypress-sidebar-left';
	const BUDDYPRESS_RIGHT_SIDEBAR_SLUG = 'buddypress-sidebar-right';

	const BUDDYPRESS_MEMBERS_RIGHT_SIDEBAR_SLUG = 'buddypress-members-sidebar-right';
	const BUDDYPRESS_GROUPS_RIGHT_SIDEBAR_SLUG = 'buddypress-groups-sidebar-right';

	const BBPRESS_LEFT_SIDEBAR_SLUG = 'bbpress-sidebar-left';
	const BBPRESS_RIGHT_SIDEBAR_SLUG = 'bbpress-sidebar-right';
	const WOOCOMMERCE_LEFT_SIDEBAR_SLUG  = 'woocommerce-sidebar-left';
	const WOOCOMMERCE_RIGHT_SIDEBAR_SLUG = 'woocommerce-sidebar-right';
	const LD_LEFT_SIDEBAR_SLUG  = 'ld-sidebar-left';
	const LD_RIGHT_SIDEBAR_SLUG = 'ld-sidebar-right';
	const LP_LEFT_SIDEBAR_SLUG  = 'lp-sidebar-left';
	const LP_RIGHT_SIDEBAR_SLUG = 'lp-sidebar-right';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'sidebars';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'widgets_init', [ $this, 'action_register_sidebars' ] );
		add_filter( 'body_class', [ $this, 'filter_body_classes' ] );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `buddyxpro()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() : array {
		return [
			'is_left_sidebar_active'  => [ $this, 'is_left_sidebar_active' ],
			'display_left_sidebar'    => [ $this, 'display_left_sidebar' ],
			'is_right_sidebar_active' => [ $this, 'is_right_sidebar_active' ],
			'display_right_sidebar'   => [ $this, 'display_right_sidebar' ],
			
			'display_buddypress_left_sidebar'    => [ $this, 'display_buddypress_left_sidebar' ],
			'is_buddypress_left_sidebar_active'  => [ $this, 'is_buddypress_left_sidebar_active' ],
			'display_buddypress_right_sidebar'    => [ $this, 'display_buddypress_right_sidebar' ],
			'is_buddypress_right_sidebar_active'  => [ $this, 'is_buddypress_right_sidebar_active' ],

			'display_buddypress_members_right_sidebar'    => [ $this, 'display_buddypress_members_right_sidebar' ],
			'is_buddypress_members_right_sidebar_active'  => [ $this, 'is_buddypress_members_right_sidebar_active' ],

			'display_buddypress_groups_right_sidebar'    => [ $this, 'display_buddypress_groups_right_sidebar' ],
			'is_buddypress_groups_right_sidebar_active'  => [ $this, 'is_buddypress_groups_right_sidebar_active' ],

			'display_bbpress_left_sidebar'    => [ $this, 'display_bbpress_left_sidebar' ],
			'is_bbpress_left_sidebar_active'  => [ $this, 'is_bbpress_left_sidebar_active' ],
			'display_bbpress_right_sidebar'    => [ $this, 'display_bbpress_right_sidebar' ],
			'is_bbpress_right_sidebar_active'  => [ $this, 'is_bbpress_right_sidebar_active' ],

			'display_woocommerce_left_sidebar'    => [ $this, 'display_woocommerce_left_sidebar' ],
			'is_woocommerce_left_sidebar_active'  => [ $this, 'is_woocommerce_left_sidebar_active' ],
			'display_woocommerce_right_sidebar'    => [ $this, 'display_woocommerce_right_sidebar' ],
			'is_woocommerce_right_sidebar_active'  => [ $this, 'is_woocommerce_right_sidebar_active' ],

			'display_ld_left_sidebar'    => [ $this, 'display_ld_left_sidebar' ],
			'is_ld_left_sidebar_active'  => [ $this, 'is_ld_left_sidebar_active' ],
			'display_ld_right_sidebar'    => [ $this, 'display_ld_right_sidebar' ],
			'is_ld_right_sidebar_active'  => [ $this, 'is_ld_right_sidebar_active' ],

			'display_lp_left_sidebar'    => [ $this, 'display_lp_left_sidebar' ],
			'is_lp_left_sidebar_active'  => [ $this, 'is_lp_left_sidebar_active' ],
			'display_lp_right_sidebar'    => [ $this, 'display_lp_right_sidebar' ],
			'is_lp_right_sidebar_active'  => [ $this, 'is_lp_right_sidebar_active' ],
		];
	}

	/**
	 * Registers the sidebars.
	 */
	public function action_register_sidebars() {
		register_sidebar(
			[
				'name'          => esc_html__( 'Right Sidebar', 'buddyxpro' ),
				'id'            => static::RIGHT_SIDEBAR_SLUG,
				'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Left Sidebar', 'buddyxpro' ),
				'id'            => static::LEFT_SIDEBAR_SLUG,
				'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);

		if ( function_exists('bp_is_active') ) {
			if ( ! class_exists( 'Youzify' ) ) {
				register_sidebar(
					[
						'name'          => esc_html__( 'Community Left Sidebar', 'buddyxpro' ),
						'id'            => static::BUDDYPRESS_LEFT_SIDEBAR_SLUG,
						'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					]
				);

				register_sidebar(
					[
						'name'          => esc_html__( 'Activity Directory Right Sidebar', 'buddyxpro' ),
						'id'            => static::BUDDYPRESS_RIGHT_SIDEBAR_SLUG,
						'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					]
				);

				register_sidebar(
					[
						'name'          => esc_html__( 'Members Directory Right Sidebar', 'buddyxpro' ),
						'id'            => static::BUDDYPRESS_MEMBERS_RIGHT_SIDEBAR_SLUG,
						'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					]
				);

				register_sidebar(
					[
						'name'          => esc_html__( 'Groups Directory Right Sidebar', 'buddyxpro' ),
						'id'            => static::BUDDYPRESS_GROUPS_RIGHT_SIDEBAR_SLUG,
						'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					]
				);

				register_sidebar(
					[
						'name'          => esc_html__( 'Members Single Profile Sidebar', 'buddyxpro' ),
						'id'            => 'single_member',
						'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					]
				);

				register_sidebar(
					[
						'name'          => esc_html__( 'Groups Single Group Sidebar', 'buddyxpro' ),
						'id'            => 'single_group',
						'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					]
				);
			}
		}

		if ( function_exists('is_bbpress') ) {
    		register_sidebar(
				[
					'name'          => esc_html__( 'bbPress Left Sidebar', 'buddyxpro' ),
					'id'            => static::BBPRESS_LEFT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);

			register_sidebar(
				[
					'name'          => esc_html__( 'bbPress Right Sidebar', 'buddyxpro' ),
					'id'            => static::BBPRESS_RIGHT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);
        }

		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar(
				[
					'name'          => esc_html__( 'WooCommerce Left Sidebar', 'buddyxpro' ),
					'id'            => static::WOOCOMMERCE_LEFT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);

			register_sidebar(
				[
					'name'          => esc_html__( 'WooCommerce Right Sidebar', 'buddyxpro' ),
					'id'            => static::WOOCOMMERCE_RIGHT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);

			// Off Canvas Sidebar
			if ( true == get_theme_mod( 'buddyx_woo_off_canvas_filter', false ) ) {
				register_sidebar(
					[
						'name'          => esc_html__( 'Off Canvas Sidebar', 'buddyxpro' ),
						'id'            => 'buddyx_off_canvas_sidebar',
						'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					]
				);
			}
		}

		if ( class_exists( 'SFWD_LMS' ) ) {
			register_sidebar(
				[
					'name'          => esc_html__( 'LearnDash Left Sidebar', 'buddyxpro' ),
					'id'            => static::LD_LEFT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);

			register_sidebar(
				[
					'name'          => esc_html__( 'LearnDash Right Sidebar', 'buddyxpro' ),
					'id'            => static::LD_RIGHT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);
		}

		if ( class_exists( 'LearnPress' ) ) {
			register_sidebar(
				[
					'name'          => esc_html__( 'LearnPress Left Sidebar', 'buddyxpro' ),
					'id'            => static::LP_LEFT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);

			register_sidebar(
				[
					'name'          => esc_html__( 'LearnPress Right Sidebar', 'buddyxpro' ),
					'id'            => static::LP_RIGHT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);
		}

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 1', 'buddyxpro' ),
				'id'            => 'footer-1',
				'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 2', 'buddyxpro' ),
				'id'            => 'footer-2',
				'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 3', 'buddyxpro' ),
				'id'            => 'footer-3',
				'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 4', 'buddyxpro' ),
				'id'            => 'footer-4',
				'description'   => esc_html__( 'Add widgets here.', 'buddyxpro' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);
	}

	/**
	 * Adds custom classes to indicate whether a sidebar is present to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes( array $classes ) : array {
		$default_sidebar = get_theme_mod( 'sidebar_option', buddyx_defaults( 'sidebar-option' ) );

		if ( $this->is_left_sidebar_active() && $default_sidebar == 'left' ) {
			global $template;

			if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
				$classes[] = 'has-sidebar-left';
			}
		} elseif ( $this->is_right_sidebar_active() && $default_sidebar == 'right' ) {
			global $template;

			if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
				$classes[] = 'has-sidebar-right';
			}
		} elseif ( $this->is_right_sidebar_active() && $this->is_right_sidebar_active() && $default_sidebar == 'both' ) {
			global $template;

			if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
				$classes[] = 'has-sidebar-both';
			}
		}

		//BuddyPress
		if ( class_exists( 'BuddyPress' ) ) {
			if ( bp_current_component() ) {
				global $bp;
				$buddypress_sidebar = get_theme_mod( 'buddypress_sidebar_option', buddyx_defaults( 'buddypress-sidebar-option' ) );
				$buddypress_members_sidebar = get_theme_mod( 'buddypress_members_sidebar_option', buddyx_defaults( 'buddypress-members-sidebar-option' ) );
				$buddypress_groups_sidebar = get_theme_mod( 'buddypress_groups_sidebar_option', buddyx_defaults( 'buddypress-groups-sidebar-option' ) );

				if ( $this->is_buddypress_left_sidebar_active() && $buddypress_sidebar == 'left' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'activity' ) && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-left';
						}
					}
				} elseif ( $this->is_buddypress_right_sidebar_active() && $buddypress_sidebar == 'right' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'activity' ) && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-right';
						}
					}
				} elseif ( $this->is_buddypress_right_sidebar_active() && $this->is_buddypress_right_sidebar_active() && $buddypress_sidebar == 'both' && ! $this->is_buddypress_left_sidebar_active() ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'activity' ) && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-left';
						}
					}
				} elseif ( $this->is_buddypress_left_sidebar_active() && $this->is_buddypress_left_sidebar_active() && $buddypress_sidebar == 'both' && ! $this->is_buddypress_right_sidebar_active() ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'activity' ) && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-right';
						}
					}
				} elseif ( $this->is_buddypress_right_sidebar_active() && $this->is_buddypress_right_sidebar_active() && $buddypress_sidebar == 'both' || $this->is_buddypress_left_sidebar_active() && $this->is_buddypress_left_sidebar_active() && $buddypress_sidebar == 'both' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'activity' ) && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-both';
						}
					}
				}

				if ( $this->is_buddypress_left_sidebar_active() && $buddypress_members_sidebar == 'left' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'members' ) && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-left';
						}
					}
				} elseif ( $this->is_buddypress_members_right_sidebar_active() && $buddypress_members_sidebar == 'right' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'members' ) && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-right';
						}
					}
				} elseif ( $this->is_buddypress_members_right_sidebar_active() && $this->is_buddypress_members_right_sidebar_active() && $buddypress_members_sidebar == 'both' && ! $this->is_buddypress_left_sidebar_active() ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'members' ) && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-left';
						}
					}
				} elseif ( $this->is_buddypress_left_sidebar_active() && $this->is_buddypress_left_sidebar_active() && $buddypress_members_sidebar == 'both' && ! $this->is_buddypress_members_right_sidebar_active() ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'members' ) && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-right';
						}
					}
				} elseif ( $this->is_buddypress_members_right_sidebar_active() && $this->is_buddypress_members_right_sidebar_active() && $buddypress_members_sidebar == 'both' || $this->is_buddypress_left_sidebar_active() && $this->is_buddypress_left_sidebar_active() && $buddypress_members_sidebar == 'both' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'members' ) && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-both';
						}
					}
				}

				if ( $this->is_buddypress_left_sidebar_active() && $buddypress_groups_sidebar == 'left' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'groups' ) && !bp_is_group() && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-left';
						}
					}
				} elseif ( $this->is_buddypress_groups_right_sidebar_active() && $buddypress_groups_sidebar == 'right' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'groups' ) && !bp_is_group() && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-right';
						}
					}
				} elseif ( $this->is_buddypress_groups_right_sidebar_active() && $this->is_buddypress_groups_right_sidebar_active() && $buddypress_groups_sidebar == 'both' && ! $this->is_buddypress_left_sidebar_active() ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'groups' ) && !bp_is_group() && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-left';
						}
					}
				} elseif ( $this->is_buddypress_left_sidebar_active() && $this->is_buddypress_left_sidebar_active() && $buddypress_groups_sidebar == 'both' && ! $this->is_buddypress_groups_right_sidebar_active() ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'groups' ) && !bp_is_group() && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-both has-no-buddypress-sidebar-right';
						}
					}
				} elseif ( $this->is_buddypress_groups_right_sidebar_active() && $this->is_buddypress_groups_right_sidebar_active() && $buddypress_groups_sidebar == 'both' || $this->is_buddypress_left_sidebar_active() && $this->is_buddypress_left_sidebar_active() && $buddypress_groups_sidebar == 'both' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_is_current_component( 'groups' ) && !bp_is_group() && !bp_is_user() ) {
							$classes[] = 'has-buddypress-sidebar-both';
						}
					}
				}
			}
		}

		// Sidebar classes docs component.
		if ( class_exists( 'BuddyPress' ) ) {
			if ( function_exists( 'bp_docs_is_docs_component' ) && bp_docs_is_docs_component() ) {
				global $bp;
				$buddypress_sidebar = get_theme_mod( 'buddypress_sidebar_option', buddyx_defaults( 'buddypress-sidebar-option' ) );

				if ( $this->is_buddypress_right_sidebar_active() && $buddypress_sidebar == 'right' || $this->is_buddypress_right_sidebar_active() && $buddypress_sidebar == 'both' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						if ( bp_docs_is_docs_component() && !bp_is_user() ) {
							$classes[] = 'has-docs-sidebar-right';
						}
					}
				}
			}
		}

		//bbPress
		if ( function_exists('is_bbpress') ) {
			if ( is_bbpress() ) {
				$bbpress_sidebar = get_theme_mod( 'bbpress_sidebar_option', buddyx_defaults( 'bbpress-sidebar-option' ) );

				if ( $this->is_bbpress_left_sidebar_active() && $bbpress_sidebar == 'left' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-bbpress-sidebar-left';
					}
				} elseif ( $this->is_bbpress_right_sidebar_active() && $bbpress_sidebar == 'right' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-bbpress-sidebar-right';
					}
				} elseif ( $this->is_bbpress_right_sidebar_active() && $this->is_bbpress_right_sidebar_active() && $bbpress_sidebar == 'both' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-bbpress-sidebar-both';
					}
				}
			}
		}

		//WooCommerce
		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_woocommerce() ) {
				$woocommerce_sidebar = get_theme_mod( 'woocommerce_sidebar_option', buddyx_defaults( 'woocommerce-sidebar-option' ) );
				
				if ( $this->is_woocommerce_left_sidebar_active() && $woocommerce_sidebar == 'left' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-woocommerce-sidebar-left';
					}
				} elseif ( $this->is_woocommerce_right_sidebar_active() && $woocommerce_sidebar == 'right' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-woocommerce-sidebar-right';
					}
				} elseif ( $this->is_woocommerce_right_sidebar_active() && $this->is_woocommerce_right_sidebar_active() && $woocommerce_sidebar == 'both' ) {
					global $template;

					if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
						$classes[] = 'has-woocommerce-sidebar-both';
					}
				}
			}
		}

		//LearnDash
		if ( class_exists( 'SFWD_LMS' ) ) {
			$ld_sidebar = get_theme_mod( 'ld_sidebar_option', buddyx_defaults( 'ld-sidebar-option' ) );
			
			if ( $this->is_ld_left_sidebar_active() && $ld_sidebar == 'left' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-ld-sidebar-left';
				}
			} elseif ( $this->is_ld_right_sidebar_active() && $ld_sidebar == 'right' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-ld-sidebar-right';
				}
			} elseif ( $this->is_ld_right_sidebar_active() && $this->is_ld_right_sidebar_active() && $ld_sidebar == 'both' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-ld-sidebar-both';
				}
			}
		}

		if ( is_plugin_active( 'ld-dashboard/ld-dashboard.php' ) ) {
			$classes[] = 'ld-dashboard';
		}

		//LearnPress
		if ( class_exists( 'LearnPress' ) && in_array('learnpress',$classes) ) {
			$lp_sidebar = get_theme_mod( 'lp_sidebar_option', buddyx_defaults( 'lp-sidebar-option' ) );
			
			if ( $this->is_lp_left_sidebar_active() && $lp_sidebar == 'left' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-lp-sidebar-left';
				}
			} elseif ( $this->is_lp_right_sidebar_active() && $lp_sidebar == 'right' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-lp-sidebar-right';
				}
			} elseif ( $this->is_lp_right_sidebar_active() && $this->is_lp_right_sidebar_active() && $lp_sidebar == 'both' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-lp-sidebar-both';
				}
			}
		}

		$enable_topbar = get_theme_mod( 'site_topbar_enable', buddyx_defaults( 'site-topbar-enable' ) );
		if( $enable_topbar == '1' ) {
			$classes[] = 'topbar-enable';
		}

		if ( class_exists( 'GroovyMenuUtils' ) && \GroovyMenuUtils::getAutoIntegration()) {
			$classes[] = 'groovy-menu-enable';
		}

		if ( class_exists( 'Youzify' ) ) {
			if ( bp_current_component() ) {
				$classes[] = 'youzify-active';
			}
		}

		if ( is_plugin_active( 'buddypress-global-search/buddypress-global-search.php' ) ) {
			$classes[] = 'buddypress-global-search';
		}

		// Dokan Class
		if ( class_exists( 'WeDevs_Dokan' ) ) {
			$classes[] = 'buddyx-dokan';
		}

		// Single Member Sidebar
		if ( function_exists( 'buddypress' ) && buddypress()->buddyboss ) {
			if ( is_active_sidebar( 'single_member' ) && bp_is_user() && ! bp_is_user_settings() && ! bp_is_user_messages() && ! bp_is_user_notifications() && ! bp_is_user_profile_edit() && ! bp_is_user_change_avatar() && ! bp_is_user_change_cover_image() && ! bp_is_user_front() ) {
				$classes[] = 'has-single-member-sidebar';
			}
		} elseif ( class_exists( 'BuddyPress' ) ) {
			if ( is_active_sidebar( 'single_member' ) && bp_is_user() && ! bp_is_user_settings() && ! bp_is_user_messages() && ! bp_is_user_notifications() && ! bp_is_user_profile_edit() && ! bp_is_user_change_avatar() && ! bp_is_user_change_cover_image() && ! bp_is_user_front() && function_exists( 'bp_is_members_invitations_screen' ) && ! bp_is_members_invitations_screen() ) {
				$classes[] = 'has-single-member-sidebar';
			}
		}

		if ( class_exists( 'BuddyPress' ) ) {
			// Single Group Sidebar
			if ( is_active_sidebar( 'single_group' ) && bp_is_group() ) {
				$classes[] = 'has-single-group-sidebar';
			}
		}

		// MediaPress Class
		if ( class_exists( 'MediaPress' ) ) {
			$classes[] = 'buddyx-mediapress';
		}

		// BPGES Class
		if ( class_exists( 'BPGES_Subscription' ) ) {
			$classes[] = 'buddyx-bpges';
		}

		// More Menu
		$more_menu_enable = get_theme_mod( 'site_more_menu', true );
		if ( ! empty( $more_menu_enable ) ) {
			$classes[] = 'more-menu-enable';
		}

		return $classes;
	}

	/**
	 * Checks whether the left sidebar is active.
	 *
	 * @return bool True if the left sidebar is active, false otherwise.
	 */
	public function is_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the left sidebar.
	 */
	public function display_left_sidebar() {
		dynamic_sidebar( static::LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the right sidebar is active.
	 *
	 * @return bool True if the right sidebar is active, false otherwise.
	 */
	public function is_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the right sidebar.
	 */
	public function display_right_sidebar() {
		dynamic_sidebar( static::RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the buddypress left sidebar is active.
	 *
	 * @return bool True if the buddypress left sidebar is active, false otherwise.
	 */
	public function is_buddypress_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BUDDYPRESS_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the buddypress left sidebar.
	 */
	public function display_buddypress_left_sidebar() {
		dynamic_sidebar( static::BUDDYPRESS_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the buddypress right sidebar is active.
	 *
	 * @return bool True if the buddypress right sidebar is active, false otherwise.
	 */
	public function is_buddypress_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BUDDYPRESS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the buddypress right sidebar.
	 */
	public function display_buddypress_right_sidebar() {
		dynamic_sidebar( static::BUDDYPRESS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the buddypress members right sidebar is active.
	 *
	 * @return bool True if the buddypress members right sidebar is active, false otherwise.
	 */
	public function is_buddypress_members_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BUDDYPRESS_MEMBERS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the buddypress members right sidebar.
	 */
	public function display_buddypress_members_right_sidebar() {
		dynamic_sidebar( static::BUDDYPRESS_MEMBERS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the buddypress groups right sidebar is active.
	 *
	 * @return bool True if the buddypress groups right sidebar is active, false otherwise.
	 */
	public function is_buddypress_groups_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BUDDYPRESS_GROUPS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the buddypress groups right sidebar.
	 */
	public function display_buddypress_groups_right_sidebar() {
		dynamic_sidebar( static::BUDDYPRESS_GROUPS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the bbpress left sidebar is active.
	 *
	 * @return bool True if the bbpress left sidebar is active, false otherwise.
	 */
	public function is_bbpress_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BBPRESS_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the bbpress left sidebar.
	 */
	public function display_bbpress_left_sidebar() {
		dynamic_sidebar( static::BBPRESS_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the bbpress right sidebar is active.
	 *
	 * @return bool True if the bbpress right sidebar is active, false otherwise.
	 */
	public function is_bbpress_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BBPRESS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the bbpress right sidebar.
	 */
	public function display_bbpress_right_sidebar() {
		dynamic_sidebar( static::BBPRESS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the woocommerce left sidebar is active.
	 *
	 * @return bool True if the woocommerce left sidebar is active, false otherwise.
	 */
	public function is_woocommerce_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::WOOCOMMERCE_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the woocommerce left sidebar.
	 */
	public function display_woocommerce_left_sidebar() {
		dynamic_sidebar( static::WOOCOMMERCE_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the woocommerce right sidebar is active.
	 *
	 * @return bool True if the woocommerce right sidebar is active, false otherwise.
	 */
	public function is_woocommerce_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::WOOCOMMERCE_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the woocommerce right sidebar.
	 */
	public function display_woocommerce_right_sidebar() {
		dynamic_sidebar( static::WOOCOMMERCE_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the learndash left sidebar is active.
	 *
	 * @return bool True if the learndash left sidebar is active, false otherwise.
	 */
	public function is_ld_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::LD_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the learndash left sidebar.
	 */
	public function display_ld_left_sidebar() {
		dynamic_sidebar( static::LD_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the learndash right sidebar is active.
	 *
	 * @return bool True if the learndash right sidebar is active, false otherwise.
	 */
	public function is_ld_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::LD_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the learndash right sidebar.
	 */
	public function display_ld_right_sidebar() {
		dynamic_sidebar( static::LD_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the learnpress left sidebar is active.
	 *
	 * @return bool True if the learnpress left sidebar is active, false otherwise.
	 */
	public function is_lp_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::LP_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the learnpress left sidebar.
	 */
	public function display_lp_left_sidebar() {
		dynamic_sidebar( static::LP_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the learnpress right sidebar is active.
	 *
	 * @return bool True if the learnpress right sidebar is active, false otherwise.
	 */
	public function is_lp_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::LP_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the learnpress right sidebar.
	 */
	public function display_lp_right_sidebar() {
		dynamic_sidebar( static::LP_RIGHT_SIDEBAR_SLUG );
	}
}