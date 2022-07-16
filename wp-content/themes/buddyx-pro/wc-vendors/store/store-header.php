<?php
/**
 * The Template for displaying the modern store header
 *
 * Override this template by copying it to yourtheme/wc-vendors/store
 *
 * @package    WCVendors_Pro
 * @version    1.6.2
 */
global $post;
$store_icon				 = '';
$store_icon_src			 = wp_get_attachment_image_src(
 get_user_meta( $vendor_id, '_wcv_store_icon_id', true ), array( 150, 150 )
);
$store_banner_src		 = wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_banner_id', true ), 'full' );
$store_banner_image_url	 = get_option( 'wcvendors_default_store_banner_src' );

// see if the array is valid
if ( is_array( $store_icon_src ) ) {
	$store_icon = '<img src="' . $store_icon_src[ 0 ] . '" alt="" class="store-icon" />';
} else {
	$store_icon = '<img src="' . get_avatar_url( $vendor_id, array( 'size' => 150 ) ) . '" alt="" class="store-icon" />';
}

if ( is_array( $store_banner_src ) ) {
	$store_banner_image_url = $store_banner_src[ 0 ];
}

// Verified vendor
$verified_vendor		 = ( array_key_exists( '_wcv_verified_vendor', $vendor_meta ) ) ? $vendor_meta[ '_wcv_verified_vendor' ] : false;
$verified_vendor_label	 = __( get_option( 'wcvendors_verified_vendor_label' ), 'buddyxpro' );

// Store title
$_store_title		 = isset( $vendor_meta[ 'pv_shop_name' ] ) ? $vendor_meta[ 'pv_shop_name' ] : '';
$store_title		 = ( is_product() ) ? '<a href="' . WCV_Vendors::get_vendor_shop_page( $post->post_author ) . '">' . $_store_title . '</a>' : $_store_title;
$store_description	 = ( array_key_exists( 'pv_shop_description', $vendor_meta ) ) ? $vendor_meta[ 'pv_shop_description' ] : '';

$phone = ( array_key_exists( '_wcv_store_phone', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_phone' ] : '';

// This is where you would load your own custom meta fields if you stored any in the settings page for the dashboard
?>

<?php do_action( 'buddyx_wc_vendors_before_vendor_store_header' ); ?>

<div class="wcv-store-header header-modern">
	<div class="upper">
		<div class="cover" style="background-image: url(<?php echo $store_banner_image_url; ?>);"></div>
		<div class="container">
			<div class="info">
				<div class="avatar">
					<?php echo $store_icon; ?>
				</div>
				<div class="about">
					<div class="name">
						<div class="txt"><?php echo $store_title; ?></div>
						<?php if ( $verified_vendor ) : ?>
							<div class="wcv-verified-vendor">
								<i class="fas fa-shield-alt"></i>
								<span><?php echo __( 'Verified', 'buddyxpro' ); ?></span>
							</div>
						<?php endif; ?>
					</div>
					<p class="desc"><?php echo $store_description; ?></p>
					<?php if ( buddyx_wc_vendors_format_store_url( $vendor_id ) ) : ?>
						<p class="url"><?php echo buddyx_wc_vendors_format_store_url( $vendor_id ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="meta">
		<?php if ( class_exists( 'WCVendors_Pro' ) ): ?>
			<?php if ( !wc_string_to_bool( get_option( 'wcvendors_ratings_management_cap', 'no' ) ) ) : ?>
				<div class="rating block">
					<div class="label">
						<?php echo __( 'Rating', 'buddyxpro' ); ?>
					</div>
					<div class="stars">
						<?php echo WCVendors_Pro_Ratings_Controller::ratings_link( $vendor_id, true ); ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( $phone ) : ?>
			<div class="phone block">
				<div class="label">
					<?php echo __( 'Phone', 'buddyxpro' ); ?>
				</div>
				<a href="tel:<?php echo $phone; ?>">
					<i class="fas fa-phone-alt"></i>
					<?php echo $phone; ?>
				</a>
			</div>
		<?php endif; ?>
		<?php if ( $address = buddyx_wc_vendors_format_store_address( $vendor_id ) ) : ?>
			<div class="address block">
				<div class="label">
					<?php echo __( 'Address', 'buddyxpro' ); ?>
				</div>
				<a href="http://maps.google.com/maps?&q=<?php echo $address; ?>">
					<address>
						<i class="fas fa-home"></i>
						<?php echo $address; ?>
					</address>
				</a>
			</div>
		<?php endif; ?>
		<?php if ( class_exists( 'WCVendors_Pro' ) ): ?>
			<?php if ( $social_icons = buddyx_wc_vendors_format_store_social_icons( $vendor_id ) ) : ?>
				<div class="social block">
					<div class="label">
						<?php echo __( 'Social', 'buddyxpro' ); ?>
					</div>
					<?php echo $social_icons; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( class_exists( 'WCVendors_Pro' ) ):; ?>
			<?php if ( wc_string_to_bool( get_option( 'wcvendors_show_store_total_sales' ) ) ) : ?>
				<div class="sales block">
					<div class="label">
						<?php echo __( 'Total Sales', 'buddyxpro' ); ?>
					</div>
					<div class="value">
						<i class="fas fa-info-circle"></i>
						<?php
						$label = WCVendors_Pro_Vendor_Controller::get_total_sales_label( $vendor_id, 'store' );
						echo do_shortcode( '[wcv_pro_vendor_totalsales vendor_id="' . $vendor_id . '" position="none"]' );
						?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>

<?php do_action( 'buddyx_wc_vendors_after_vendor_store_header' ); ?>
