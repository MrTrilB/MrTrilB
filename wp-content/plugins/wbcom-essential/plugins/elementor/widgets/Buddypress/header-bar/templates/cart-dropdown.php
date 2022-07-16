<?php
/**
 * WC cart Dropdown.
 *
 * @link       https://wbcomdesigns.com/plugins
 * @since      1.0.0
 *
 * @package    Wbcom_Essential
 * @subpackage Wbcom_Essential/plugins/elementor/widget/buddypress/header-bar/templates
 */

$cart_icon = ( isset( $settings['cart_icon']['value'] ) && '' !== $settings['cart_icon']['value'] ) ? $settings['cart_icon']['value'] : 'wb-icon-shopping-cart';
?>

<div class="notification-wrap header-cart-link-wrap cart-wrap menu-item-has-children">
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-cart-link notification-link">
		<span data-balloon-pos="down" data-balloon="<?php esc_html_e( 'Cart', 'wbcom-essential' ); ?>">
			<i class="<?php echo esc_attr( $cart_icon ); ?>"></i>
			<?php
			if ( is_object( WC()->cart ) ) {
				$wc_cart_count = wc()->cart->get_cart_contents_count();
				if ( 0 !== $wc_cart_count ) {
					?>
				<span class="count header-cart-count	"><?php echo esc_html__( wc()->cart->get_cart_contents_count() ); ?></span>
					<?php
				}
			}
			?>
		</span>
	</a>
	<section class="notification-dropdown">
		<header class="notification-header">
			<h2 class="title"><?php esc_html_e( 'Cart', 'wbcom-essential' ); ?></h2>
		</header>
		<div class="header-mini-cart">
			<div class="widget_shopping_cart_content">
				<?php
				if ( is_object( WC()->cart ) ) {
						woocommerce_mini_cart();
				}
				?>
			</div>
		</div>
	</section>
</div>
