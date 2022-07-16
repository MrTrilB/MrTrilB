<?php
/**
 * The `buddyxpro()` woocommerce functions.
 *
 * @link    https://wbcomdesigns.com/
 * @package buddyxpro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Add WooCommerce Support
 */
if ( ! function_exists( 'buddyx_woocommerce_support' ) ) {

	function buddyx_woocommerce_support() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	add_action( 'after_setup_theme', 'buddyx_woocommerce_support' );
}

/**
 * Remove WooCommerce the breadcrumbs
 */
add_action( 'init', 'buddyx_remove_wc_breadcrumbs' );
function buddyx_remove_wc_breadcrumbs() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

/**
 * Remove WooCommerce CSS if WooCommerce not activated
 */
function buddyx_woo_dequeue_styles() {
	wp_dequeue_style( 'buddyxpro-woocommerce' );
		wp_deregister_style( 'buddyxpro-woocommerce' );
}
if ( ! class_exists( 'WooCommerce' ) ) {
	add_action( 'wp_print_styles', 'buddyx_woo_dequeue_styles' );
}

/**
 * Sale badge content
 *
 * @since 2.6.2
 */

if ( 'percent' == get_theme_mod( 'buddyx_woo_sale_badge_content' ) ) {
	add_filter( 'woocommerce_sale_flash', 'sale_flash', 10, 3 );
}

if ( ! function_exists( 'sale_flash' ) ) {
	function sale_flash() {
		global $product;

		if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {

			$r_price = $product->get_regular_price();
			$s_price = $product->get_sale_price();
			$percent = round( ( ( floatval( $r_price ) - floatval( $s_price ) ) / floatval( $r_price ) ) * 100 );

		} elseif ( $product->is_type( 'variable' ) ) {

			$available_variations = $product->get_available_variations();
			$maximumper           = 0;

			for ( $i = 0; $i < count( $available_variations ); ++ $i ) {
				$variation_id     = $available_variations[ $i ]['variation_id'];
				$variable_product = new WC_Product_Variation( $variation_id );

				if ( ! $variable_product->is_on_sale() ) {
					continue;
				}

				$r_price = $variable_product->get_regular_price();
				$s_price = $variable_product->get_sale_price();
				$percent = round( ( ( floatval( $r_price ) - floatval( $s_price ) ) / floatval( $r_price ) ) * 100 );

				if ( $percent > $maximumper ) {
					$maximumper = $percent;
				}
			}

			$percent = sprintf( __( '%s', 'buddyxpro' ), $maximumper );

		} else {

			$percent = '<span class="onsale">' . __( 'Sale!', 'buddyxpro' ) . '</span>';
			return $percent;

		}

		$value = '-' . esc_html( $percent ) . '%';

		return '<span class="onsale">' . esc_html( $value ) . '</span>';
	}
}

/**
 * Add classes to WooCommerce product entries.
 *
 * @since 2.6.2
 */
add_filter( 'post_class', 'add_product_classes', 40, 3 );

if ( ! function_exists( 'add_product_classes' ) ) {
	function add_product_classes( $classes ) {
		global $woocommerce_loop;

		// Vars.
		$product = wc_get_product( get_the_ID() );

		// Sale badge style.
		$sale_style = get_theme_mod( 'buddyx_woo_sale_badge_style', 'defualt' );
		if ( 'square' == $sale_style ) {
			$classes[] = $sale_style . '-sale';
		}
		if ( 'circle' == $sale_style ) {
			$classes[] = $sale_style . '-sale';
		}

		// Sale badge position.
		$sale_style = get_theme_mod( 'buddyx_woo_sale_badge_position', 'right' );
		if ( 'left' == $sale_style ) {
			$classes[] = $sale_style . '-position';
		}

		return $classes;
	}
}

/**
 * Number sanitization callback
 *
 * @since 2.6.2
 */
if ( ! function_exists( 'buddyx_sanitize_number' ) ) {
	function buddyx_sanitize_number( $val ) {
		return is_numeric( $val ) ? $val : 0;
	}
}

/**
 * Checkbox sanitization callback
 *
 * @since 2.6.2
 */
if ( ! function_exists( 'buddyx_sanitize_checkbox' ) ) {
	function buddyx_sanitize_checkbox( $checked ) {
		// Boolean check.
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}
}

// Remove orderby if disabled.
if ( ! get_theme_mod( 'buddyx_woo_shop_sort', true ) ) {
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
}

// Remove result count if disabled.
if ( ! get_theme_mod( 'buddyx_woo_shop_result_count', true ) ) {
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
}

/**
 * Returns correct posts per page for the shop
 *
 * @since 2.6.2
 */
add_filter( 'loop_shop_per_page', 'loop_shop_per_page', 20 );

if ( ! function_exists( 'loop_shop_per_page' ) ) {
	function loop_shop_per_page() {
		if ( get_theme_mod( 'buddyx_woo_shop_result_count', true ) ) {
			$posts_per_page = ( isset( $_GET['products-per-page'] ) ) ? sanitize_text_field( wp_unslash( $_GET['products-per-page'] ) ) : get_theme_mod( 'buddyx_woo_product_per_page', '12' );

			if ( $posts_per_page == 'all' ) {
				$posts_per_page = wp_count_posts( 'product' )->publish;
			}
		} else {
			$posts_per_page = get_theme_mod( 'buddyx_woo_product_per_page' );
			$posts_per_page = $posts_per_page ? $posts_per_page : '12';
		}
		return $posts_per_page;
	}
}


/**
 * Checks if on the WooCommerce shop page.
 *
 * @since 2.6.2
 */
if ( ! function_exists( 'buddyx_is_woo_shop' ) ) {

	function buddyx_is_woo_shop() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
			return true;
		}
	}
}

/**
 * Checks if on a WooCommerce tax.
 *
 * @since 2.6.2
 */
if ( ! function_exists( 'buddyx_is_woo_tax' ) ) {

	function buddyx_is_woo_tax() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		} elseif ( ! is_tax() ) {
			return false;
		} elseif ( function_exists( 'is_product_taxonomy' ) ) {
			if ( is_product_taxonomy() ) {
				return true;
			}
		}
	}
}

/**
 * Add off canvas filter button.
 *
 * @since 2.6.2
 */

if ( true == get_theme_mod( 'buddyx_woo_off_canvas_filter', false ) ) {
	add_action( 'woocommerce_before_shop_loop', 'off_canvas_filter_button', 10 );
}

if ( ! function_exists( 'off_canvas_filter_button' ) ) {
	function off_canvas_filter_button() {

		// Return if is not in shop page.
		if ( ! buddyx_is_woo_shop()
			&& ! buddyx_is_woo_tax() ) {
			return;
		}

		// Get filter text.
		$text = get_theme_mod( 'buddyx_woo_off_canvas_filter_text' );
		$text = $text ? $text : esc_html__( 'Filter', 'buddyxpro' );

		$output = '<a href="#" class="buddyx-woo-canvas-filter"><i class="fa fa-bars" aria-hidden="true"></i><span class="off-canvas-filter-text">' . esc_html( $text ) . '</span></a>';

		echo apply_filters( 'buddyx_off_canvas_filter_button_output', $output );
	}
}

if ( ! function_exists( 'buddyx_cac_has_woo_filter_button' ) ) {
	function buddyx_cac_has_woo_filter_button() {
		if ( true == get_theme_mod( 'buddyx_woo_off_canvas_filter', false ) ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Show WooCommerce Filter In Shop Page
 *
 * @since 2.6.2
 */
if ( ! function_exists( 'buddyx_filters_widget_side' ) ) {

	function buddyx_filters_widget_side() {
		$text = get_theme_mod( 'buddyx_woo_off_canvas_filter_text' );
		$text = $text ? $text : esc_html__( 'Filter', 'buddyxpro' );
		?>
		<div class="buddyx-filter-widget-side">
			<div class="widget-heading">
				<h3 class="widget-title"><?php esc_html_e( $text ); ?></h3>
				<a href="#" class="widget-close"><?php esc_html_e( 'close' ); ?></a>
			</div>
			<div class="buddyx-module-filter">
				<div class="woocommerce">
					<div class="buddyx-woo-filter">
					<aside id="secondary" class="woo-off-canvas-sidebar">
						<?php dynamic_sidebar( 'buddyx_off_canvas_sidebar' ); ?>
					</aside>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

/**
 * WooCommerce Quantity Fields Script
 *
 * @since 3.8.4
 */
add_action( 'wp_footer', 'buddyx_quantity_fields_script' );

function buddyx_quantity_fields_script() {
	?>
	<script type='text/javascript'>
	jQuery( function( $ ) {
		if ( ! String.prototype.getDecimals ) {
			String.prototype.getDecimals = function() {
				var num = this,
					match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
				if ( ! match ) {
					return 0;
				}
				return Math.max( 0, ( match[1] ? match[1].length : 0 ) - ( match[2] ? +match[2] : 0 ) );
			}
		}
		// Quantity "plus" and "minus" buttons
		$( document.body ).on( 'click', '.plus, .minus', function() {
			var $qty        = $( this ).closest( '.quantity' ).find( '.qty'),
				currentVal  = parseFloat( $qty.val() ),
				max         = parseFloat( $qty.attr( 'max' ) ),
				min         = parseFloat( $qty.attr( 'min' ) ),
				step        = $qty.attr( 'step' );

			// Format values
			if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
			if ( max === '' || max === 'NaN' ) max = '';
			if ( min === '' || min === 'NaN' ) min = 0;
			if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

			// Change the value
			if ( $( this ).is( '.plus' ) ) {
				if ( max && ( currentVal >= max ) ) {
					$qty.val( max );
				} else {
					$qty.val( ( currentVal + parseFloat( step )).toFixed( step.getDecimals() ) );
				}
			} else {
				if ( min && ( currentVal <= min ) ) {
					$qty.val( min );
				} else if ( currentVal > 0 ) {
					$qty.val( ( currentVal - parseFloat( step )).toFixed( step.getDecimals() ) );
				}
			}

			// Trigger change event
			$qty.trigger( 'change' );
		});
	});
	</script>
	<?php
}
