<?php
/**
 * Vendor List Template
 *
 * This template can be overridden by copying it to yourtheme/wc-vendors/front/vendors-list.php
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    buddyxpro
 * @subpackage WCVendors/Templates/Emails/HTML
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="vendors-list col-xs-12 col-sm-6 col-md-4">
	<div class="member-inner-list-wrap">
		<div class="vendor-list-like"><?php // echo getShopLikeButton($vendor_id); ?></div>
		<a href="<?php echo esc_url( $shop_link ); ?>">
			<span class="cover_logo"<?php echo buddyx_wc_vendors_banner_image( $vendor_id ); ?>></span>
		</a>
		<div class="member-details">
			<div class="item-avatar">
				<a href="<?php echo esc_url( $shop_link ); ?>">
					<img src="<?php echo buddyx_wc_vendors_stor_icon( $vendor_id, 80, 80 ); ?>" class="vendor_store_image_single" width=80 height=80 />
				</a>
			</div>
			<a href="<?php echo esc_url( $shop_link ); ?>" class="wcv-grid-shop-name"><?php echo esc_html( $shop_name ); ?></a>
			<div class="store-rating">
				<?php echo buddyx_wc_vendors_shop_rating( $vendor_id ); ?>
			</div>
			<div class="store-desc">
				<?php echo buddyx_wc_vendors_shop_description( $vendor_id ); ?>
			</div>
		</div>
		<div class="vendor-products">
			<?php echo buddyx_wc_vendors_vendor_products( $vendor_id ); ?>
		</div>
	</div>
</li>
