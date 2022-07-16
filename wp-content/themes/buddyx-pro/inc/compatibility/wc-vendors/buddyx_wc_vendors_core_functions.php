<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    buddyxpro
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Display the banner image  of vendor.
 *
 * @param  integer $vendor_id Venor's Id
 * @return string           Return a background image html.
 */
if ( ! function_exists( 'buddyx_wc_vendors_banner_image' ) ) {

	function buddyx_wc_vendors_banner_image( $vendor_id ) {
		$store_bg = '';
		if ( class_exists( 'WCVendors_Pro' ) ) {
			$store_icon_src = wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_banner_id', true ), 'full' );
			if ( is_array( $store_icon_src ) ) {
				$store_bg = $store_icon_src[0];
			}
			if ( empty( $store_bg ) ) {
				$store_bg = WCVendors_Pro::get_option( 'default_store_banner_src' );
			}
		} else {

		}
		$bg_styles = ( ! empty( $store_bg ) ) ? ' style="background-image: url(' . $store_bg . '); background-repeat: no-repeat;background-size: cover;"' : '';
		if ( ! empty( $bg_styles ) ) {
			return $bg_styles;
		}
	}
}

/**
 * Create vendor stor icon.
 *
 * @param  integer $vendor_id Vendor ID
 * @param  integer $width     Width of icon
 * @param  integer $height    Heignt of Icon
 * @return
 */
if ( ! function_exists( 'buddyx_wc_vendors_stor_icon' ) ) {

	function buddyx_wc_vendors_stor_icon( $vendor_id, $width = 150, $height = 150 ) {

		if ( ! $vendor_id ) {
			return;
		}
		$store_icon_url = '';

		if ( class_exists( 'WCVendors_Pro' ) ) {
			$store_icon_src = wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_icon_id', true ), array( 150, 150 ) );
			if ( is_array( $store_icon_src ) ) {
				$store_icon_url = $store_icon_src[0];
			} else {
				$store_icon_url = get_avatar_url( $vendor_id, 150 );
			}
		} else {
			$store_icon_url = get_avatar_url( $vendor_id, 150 );
		}
		return $store_icon_url;
	}
}

/**
 * Print shop rating under the shop icon.
 *
 * @param  integer $vendor_id Vendor ID
 */
if ( ! function_exists( 'buddyx_wc_vendors_shop_rating' ) ) {

	function buddyx_wc_vendors_shop_rating( $vendor_id ) {

		if ( class_exists( 'WCVendors_Pro' ) ) {
			if ( ! WCVendors_Pro::get_option( 'ratings_management_cap' ) ) {
				echo '<div class="wcv_grid_rating">';
				echo WCVendors_Pro_Ratings_Controller::ratings_link( $vendor_id, true );
				echo '</div>';
			}
		}
	}
}


/**
 * This function print the desciption under the vendor rating,
 *
 * @param  integer $vendor_id Vendor Id
 * @return string            Return trimed description
 */
if ( ! function_exists( 'buddyx_wc_vendors_shop_description' ) ) {

	function buddyx_wc_vendors_shop_description( $vendor_id ) {
		$vendor_meta = array_map(
			function( $a ) {
				return $a[0];
			},
			get_user_meta( $vendor_id )
		);

		$shop_descr = $vendor_meta['pv_shop_description'];

		$length  = apply_filters( 'buddyx_wc_vendors_shop_description_limit', 350 );
		$maxchar = ! empty( $length ) ? (int) trim( $length ) : 350;
		$text    = ! empty( $shop_descr ) ? trim( $shop_descr ) : '';

		$out = '';

		$out = $text . $out;

		$out = preg_replace( '~\[/?.*?\]~', '', $out );
		$out = strip_tags( strip_shortcodes( $out ) );

		if ( mb_strlen( $out ) > $maxchar ) {
			$out = mb_substr( $out, 0, $maxchar );
			$out = preg_replace( '@(.*)\s[^\s]*$@s', '\\1 ...', $out );
		}

		return $out;
	}
}

/**
 * List vendors products
 *
 * @param  integer $vendor_id Vendor's ID
 */
if ( ! function_exists( 'buddyx_wc_vendors_vendor_products' ) ) {

	function buddyx_wc_vendors_vendor_products( $vendor_id ) {
		$args     = array(
			'post_type'           => 'product',
			'posts_per_page'      => 3,
			'author'              => $vendor_id,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);
		$products = new WP_Query( $args );

		if ( ! empty( $products->posts ) ) {

			$i = 0;
			foreach ( $products->posts as $product ) {

				$product_id    = $product->ID;
				$product_title = get_the_title( $product_id );
				$product_url   = get_permalink( $product_id );
				$atachment_url = wp_get_attachment_url( get_post_thumbnail_id( $product_id ) );
				$store_url     = WCV_Vendors::get_vendor_shop_page( $vendor_id );
				$totaldeals    = count_user_posts( $vendor_id, $post_type      = 'product' ) - 3;
				$i++;
				?>
				<a href="<?php echo esc_url( $product_url ); ?>" class="vendor_product">
					<img src="<?php echo esc_url( $atachment_url ); ?>" width=70 height=70 alt="<?php echo esc_attr( $product_title ); ?>"/>
				</a>
				<?php
			}
			if ( $i == 3 && $totaldeals > 0 ) {
				?>
				<a href="<?php echo esc_url( $store_url ); ?>" target="_blank" class="vendor_product">
					<span class="product_count_in_member"><?php echo '+' . $totaldeals; ?></span>
				</a>
				<?php
			}
		}
		wp_reset_query();
	}
}

/**
 * Format and print store address.
 *
 * @param  integer $vendor_id [description]
 * @return string
 */
if ( ! function_exists( 'buddyx_wc_vendors_format_store_address' ) ) {

	function buddyx_wc_vendors_format_store_address( $vendor_id ) {
		$store_address_args = apply_filters(
			'buddyx_wc_vendors_format_store_address_args',
			array(
				'address1' => get_user_meta( $vendor_id, '_wcv_store_address1', true ),
				'city'     => get_user_meta( $vendor_id, '_wcv_store_city', true ),
				'state'    => get_user_meta( $vendor_id, '_wcv_store_state', true ),
				'postcode' => get_user_meta( $vendor_id, '_wcv_store_postcode', true ),
				'country'  => WC()->countries->countries[ get_user_meta( $vendor_id, '_wcv_store_country', true ) ],
			),
			$vendor_id
		);

		$store_address_args = array_filter( $store_address_args );

		return apply_filters( 'buddyx_wc_vendors_format_store_address_output', implode( ', ', $store_address_args ), $vendor_id );
	}
}

/**
 * Formate Store Url
 *
 * @var integer
 * @return string
 */
if ( 'buddyx_wc_vendors_format_store_url' ) {

	function buddyx_wc_vendors_format_store_url( $vendor_id ) {
		$store_url = get_user_meta( $vendor_id, '_wcv_company_url', true );
		if ( ! $store_url ) {
			return '';
		}

		return apply_filters(
			'buddyx_wc_vendors_format_store_url',
			sprintf( '<a href="%1$s">%1$s</a>', $store_url ),
			$vendor_id
		);
	}
}

/**
 * All social settings in one place.
 *
 * @return array
 */
function buddyx_wc_vendors_get_social_media_settings() {
	$settings = array(
		'twitter'   => array(
			'id'           => '_wcv_twitter_username',
			'label'        => __( 'Twitter Username', 'buddyxpro' ),
			'placeholder'  => __( 'YourTwitterUserHere', 'buddyxpro' ),
			'desc_tip'     => 'true',
			'description'  => __( 'Your <a href="https://twitter.com/">Twitter</a> username without the url.', 'buddyxpro' ),
			'type'         => 'text',
			'icon'         => 'twitter-square',
			'url_template' => '//twitter.com/%s',
		),
		'instagram' => array(
			'id'           => '_wcv_instagram_username',
			'label'        => __( 'Instagram Username', 'buddyxpro' ),
			'placeholder'  => __( 'YourInstagramUsername', 'buddyxpro' ),
			'desc_tip'     => 'true',
			'description'  => __( 'Your <a href="https://instagram.com/">Instagram</a> username without the url.', 'buddyxpro' ),
			'type'         => 'text',
			'icon'         => 'instagram',
			'url_template' => '//instagram.com/%s',
		),
		'facebook'  => array(
			'id'           => '_wcv_facebook_url',
			'label'        => __( 'Facebook URL', 'buddyxpro' ),
			'placeholder'  => __( 'http://yourfacebookurl/here', 'buddyxpro' ),
			'desc_tip'     => 'true',
			'description'  => __( 'Your <a href="https://facebook.com/">Facebook</a> url.', 'buddyxpro' ),
			'type'         => 'text',
			'icon'         => 'facebook-square',
			'url_template' => '%s',
		),
		'linkedin'  => array(
			'id'           => '_wcv_linkedin_url',
			'label'        => __( 'LinkedIn URL', 'buddyxpro' ),
			'placeholder'  => __( 'http://linkedinurl.com/here', 'buddyxpro' ),
			'desc_tip'     => 'true',
			'description'  => __( 'Your <a href="https://linkedin.com/">LinkedIn</a> url.', 'buddyxpro' ),
			'type'         => 'url',
			'icon'         => 'linkedin',
			'url_template' => '%s',
		),
		'youtube'   => array(
			'id'           => '_wcv_youtube_url',
			'label'        => __( 'YouTube URL', 'buddyxpro' ),
			'placeholder'  => __( 'http://youtube.com/here', 'buddyxpro' ),
			'desc_tip'     => 'true',
			'description'  => __( 'Your <a href="https://youtube.com/">Youtube</a> url.', 'buddyxpro' ),
			'type'         => 'url',
			'icon'         => 'youtube-square',
			'url_template' => '%s',
		),
		'pinterest' => array(
			'id'           => '_wcv_pinterest_url',
			'label'        => __( 'Pinterest URL', 'buddyxpro' ),
			'placeholder'  => __( 'https://www.pinterest.com/username/', 'buddyxpro' ),
			'desc_tip'     => 'true',
			'description'  => __( 'Your <a href="https://www.pinterest.com/">Pinterest</a> url.', 'buddyxpro' ),
			'type'         => 'url',
			'icon'         => 'pinterest-square',
			'url_template' => '%s',
		),
		'snapchat'  => array(
			'id'           => '_wcv_snapchat_username',
			'label'        => __( 'Snapchat Username', 'buddyxpro' ),
			'placeholder'  => __( 'snapchatUsername', 'buddyxpro' ),
			'desc_tip'     => 'true',
			'description'  => __( 'Your snapchat username.', 'buddyxpro' ),
			'type'         => 'text',
			'icon'         => 'snapchat',
			'url_template' => '//www.snapchat.com/add/%s',
		),
		'telegram'  => array(
			'id'           => '_wcv_telegram_username',
			'label'        => __( 'Telegram Username', 'buddyxpro' ),
			'placeholder'  => __( 'TelegramUsername', 'buddyxpro' ),
			'desc_tip'     => 'true',
			'description'  => __( 'Your telegram username.', 'buddyxpro' ),
			'type'         => 'text',
			'icon'         => 'telegram-square',
			'url_template' => '//telegram.me/%s',
		),
	);

	return apply_filters( 'buddyx_wc_vendors_get_social_media_settings', $settings );
}

/**
 * Format store social icons
 *
 * @param int    $vendor_id Vendor ID.
 * @param string $size      Icon size.
 * @param array  $hidden    Hidden items.
 *
 * @since 1.6.2
 * @version 1.6.3
 *
 * @return false|string
 */
if ( ! function_exists( 'buddyx_wc_vendors_format_store_social_icons' ) ) {

	function buddyx_wc_vendors_format_store_social_icons( $vendor_id, $size = 'sm', $hidden = array() ) {
		ob_start();

		foreach ( buddyx_wc_vendors_get_social_media_settings() as $key => $setting ) {
			if ( in_array( $key, $hidden ) ) {
				continue;
			}

			$value = get_user_meta( $vendor_id, $setting['id'], true );

			if ( ! $value ) {
				continue;
			}
			?>
			<li>
				<a href="<?php printf( $setting['url_template'], $value ); ?>" target="_blank">
					<svg class="wcv-icon wcv-icon-<?php echo esc_attr( $size ); ?>">
					<use xlink:href="<?php echo WCV_PRO_PUBLIC_ASSETS_URL; ?>svg/wcv-icons.svg#wcv-icon-<?php echo $setting['icon']; ?>"></use>
					</svg>
				</a>
			</li>
			<?php
		}

		$list = trim( ob_get_clean() );
		if ( ! $list ) {
			return;
		}
		return '<ul class="social-icons">' . $list . '</ul>';
	}
}

/**
* Add header on single vendor page.
*/

if ( ! function_exists( 'buddyxpro_wcvendors_header_top' ) ) {
	add_action( 'buddyx_sub_header', 'buddyxpro_wcvendors_header_top', 9 );
	function buddyxpro_wcvendors_header_top() {
		// WC Vendor
		if ( class_exists( 'WC_Vendors' ) ) {
			global $post;

			if ( WCV_Vendors::is_vendor_page() ) {
				remove_action( 'buddyx_sub_header', 'buddyx_sub_header' );
				$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
				$vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );
				$vendor_meta = array_map(
					function ( $a ) {
						return $a[0];
					},
					get_user_meta( $vendor_id )
				);

				wc_get_template(
					'store-header.php',
					array(
						'vendor_id'   => $vendor_id,
						'vendor_meta' => $vendor_meta,
					),
					'wc-vendors/store/',
					get_template_directory() . 'wc-vendors/store/'
				);

			}

			if ( WCV_Vendors::is_vendor_product_page( $post->post_author ) && ! is_shop() ) {
				// Set header on single product papge.
				remove_action( 'buddyx_sub_header', 'buddyx_sub_header' );
				$vendor_id   = $post->post_author;
				$vendor_meta = array_map(
					function ( $a ) {
						return $a[0];
					},
					get_user_meta( $vendor_id )
				);

				wc_get_template(
					'store-header.php',
					array(
						'vendor_id'   => $vendor_id,
						'vendor_meta' => $vendor_meta,
					),
					'wc-vendors/store/',
					get_template_directory() . 'wc-vendors/store/'
				);
			}
		}

	}
}

/**
* Hide vendor header from content area
*/
if ( ! function_exists( 'buddyx_hide_vendor_header' ) ) {
	if ( class_exists( 'woocommerce' ) &&  class_exists( 'WC_Vendors' ) ) {
				add_action( 'after_setup_theme', 'buddyx_hide_vendor_header' );
	}

	function buddyx_hide_vendor_header() {
		$shop_header    = wc_string_to_bool( get_option( 'wcvendors_display_shop_headers', 'no' ) );
		$product_header = wc_string_to_bool( get_option( 'wcvendors_store_single_headers', 'no' ) );
		if ( $shop_header || $product_header ) {
			update_option( 'wcvendors_display_shop_headers', 'no' );
			update_option( 'wcvendors_store_single_headers', 'no' );
		} else {
			update_option( 'wcvendors_display_shop_headers', 'no' );
			update_option( 'wcvendors_store_single_headers', 'no' );
		}

	}
}

if ( ! function_exists( 'buddyx_hide_vendor_header_option' ) ) {
	add_filter( 'wcvendors_settings_display_general', 'buddyx_hide_vendor_header_option', 20, 1 );

	function buddyx_hide_vendor_header_option( $settings ) {

		$shop_headers_id = array_search( 'wcvendors_display_shop_headers', array_column( $settings, 'id' ) );
		$product_headers_id = array_search( 'wcvendors_store_single_headers', array_column( $settings, 'id' ) );
		if ( ! empty( $shop_headers_id ) && ! empty( $product_headers_id ) ) {
			unset( $settings[ $shop_headers_id ] );
			unset( $settings[ $product_headers_id ] );
		}
			return $settings;
	}
}
