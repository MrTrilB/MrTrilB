<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Vendor Profile Widget.
 *
 * @package    rwcvendors_Pro
 * @subpackage rwcvendors_Pro/public/widgets
 * @author     Lindeni Mahlalela
 * @version    1.5.4
 * @extends    WC_Widget
 */
class BuddyxPro_WCV_Widget_Vendor_Profile extends WC_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'rwcv widget_store_vendor_profile';
		$this->widget_description = __( 'Shows the vendor image and other shop related details.', 'buddyxpro' );
		$this->widget_id          = 'rwcv_vendor_profile';
		$this->widget_name        = __( 'BUDDYX Vendor\'s Profile', 'buddyxpro' );
		$this->settings           = array(
			'title'        => array(
				'type'  => 'text',
				'std'   => __( 'Shop Owner', 'buddyxpro' ),
				'label' => __( 'Title', 'buddyxpro' ),
			),
			'show_address' => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => __( 'Show Store Address', 'buddyxpro' ),
			),
		);

		if ( class_exists( 'WCVendors_Pro' ) ) {
			$pro_settings   = array(
				'vendor_avatar'       => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => __( 'Show Shope Icon', 'buddyxpro' ),
				),
				'vendor_registration' => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => __( 'Show vendor registration', 'buddyxpro' ),
				),
				'shop_opning_hourse'  => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => __( 'Show Opening Hours', 'buddyxpro' ),
				),
				'shop_phone_no'       => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => __( 'Show Phone Number', 'buddyxpro' ),
				),
				'shop_total_sell'     => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => __( 'Show Store Total Sales', 'buddyxpro' ),
				),
			);
			$this->settings = array_merge( $this->settings, $pro_settings );
		}
		parent::__construct();
	}

	/**
	 * Output the address and map widget.
	 *
	 * @see   WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @since 1.5.4
	 */
	public function widget( $args, $instance ) {
		global $post;

		if ( ! is_woocommerce() ) {
			return;
		}

		if ( ! $post ) {
			return;
		}

		if ( ! WCV_Vendors::is_vendor_page() ) {
			return;
		}

		if ( WCV_Vendors::is_vendor_page() ) {
			$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
			$vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );
		} else {
			if ( isset( $_GET['wcv_vendor_id'] ) ) {
				$vendor_id = $_GET['wcv_vendor_id'];
			}
		}

		if ( ! isset( $vendor_id ) ) {
			return;
		}

		$vendor = get_userdata( $vendor_id );

		$vendor_settings = array_map(
			function ( $a ) {
				return $a[0];
			},
			get_user_meta( $vendor_id )
		);

		// print_r( $vendor_settings );

		$show_avatar        = isset( $instance['vendor_avatar'] ) ? $instance['vendor_avatar'] : $this->settings['vendor_avatar']['std'];
		$show_registration  = isset( $instance['vendor_registration'] ) ? $instance['vendor_registration'] : $this->settings['vendor_registration']['std'];
		$show_opning_hourse = isset( $instance['shop_opning_hourse'] ) ? $instance['shop_opning_hourse'] : $this->settings['shop_opning_hourse']['std'];
		$show_phone_no      = isset( $instance['shop_phone_no'] ) ? $instance['shop_phone_no'] : $this->settings['shop_phone_no']['std'];
		$show_total_sell    = isset( $instance['shop_total_sell'] ) ? $instance['shop_total_sell'] : $this->settings['shop_total_sell']['std'];
		$show_address       = isset( $instance['show_address'] ) ? $instance['show_address'] : $this->settings['show_address']['std'];

		$store_icon_id = isset( $vendor_settings['_wcv_store_icon_id'] ) ? $vendor_settings['_wcv_store_icon_id'] : '';
		$address_line1 = isset( $vendor_settings['_wcv_store_address1'] ) ? $vendor_settings['_wcv_store_address1'] : '';
		$city          = isset( $vendor_settings['_wcv_store_city'] ) ? $vendor_settings['_wcv_store_city'] : '';
		$state         = isset( $vendor_settings['_wcv_store_state'] ) ? $vendor_settings['_wcv_store_state'] : '';
		$post_code     = isset( $vendor_settings['_wcv_store_postcode'] ) ? $vendor_settings['_wcv_store_postcode'] : '';

		if ( class_exists( 'WCVendors_Pro' ) ) {
			// Total Seals
			$total_sales = apply_filters( 'rwcv_store_total_sales_count', WCVendors_Pro_Vendor_Controller::get_vendor_sales_count( $vendor_id ), $vendor_id );

			// Store Icon
			if ( ! empty( $store_icon_id ) ) {
				$store_icon_src = wp_get_attachment_image_src( $store_icon_id, array( 150, 150 ) );
				$store_icon     = $store_icon_src[0];
			} else {
				$store_icon = get_avatar_url( $vendor_id, array( 'size' => 150 ) );
			}
			// Opening Hourse
			if ( ! empty( $vendor_settings['wcv_store_opening_hours'] ) ) {

				$hours       = unserialize( $vendor_settings['wcv_store_opening_hours'] );
				$time_format = apply_filters( 'wcv_opening_hours_time_format', wc_time_format() );

				ob_start();
				if ( ! empty( $hours ) ) {
					?>
					<p><?php _e( 'Opening Hours', 'buddyxpro' ); ?></p>
					<table class="store-opening-hours-table">
						<thead>
						<th><?php _e( 'Day', 'buddyxpro' ); ?></th>
						<th><?php _e( 'Opening Hours', 'buddyxpro' ); ?></th>
					</thead>
					<tbody>
						<?php
						foreach ( $hours as $opening ) :
							$opening_times = sprintf( __( '%1$s to %2$s', 'buddyxpro' ), esc_attr( date( $time_format, strtotime( $opening['open'] ) ) ), esc_attr( date( $time_format, strtotime( $opening['close'] ) ) ) );
							?>
							<tr>
								<td><?php echo ucfirst( esc_attr( $opening['day'] ) ); ?></td>
								<td><?php echo $opening_times; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					</table>
					<?php
				} else {
					?>
					<p><?php _e( 'We are open.', 'buddyxpro' ); ?></p>
					<?php
				}
				$opening_hours = ob_get_clean();
			}
		}

		if ( $vendor ) {
			$vendor_registration = date_i18n( get_option( 'date_format' ), strtotime( $vendor->user_registered ) );
		}

		$this->widget_start( $args, $instance );

		echo '<ul class="contact-card">';
		echo isset( $vendor_settings['pv_shop_name'] ) ? '<li class="rwcv-widget-shop-name">' . $vendor_settings['pv_shop_name'] . '</li>' : '';

		if ( class_exists( 'WCVendors_Pro' ) ) {

			if ( $show_avatar ) {
				echo ! empty( $store_icon ) ? '<li class="rwcv-widget-shop-icon"><img src="' . $store_icon . '" alt="' . $vendor_settings['pv_shop_name'] . '" /></li>' : '';
			}

			if ( $show_opning_hourse ) {
				echo ! empty( $opening_hours ) ? '<li class="rwcv-widget-shop-opning-hourse">' . $opening_hours . '</li>' : '';
			}

			if ( $show_phone_no ) {
				echo isset( $vendor_settings['_wcv_store_phone'] ) ? '<li class="rwcv-widget-shop-phone">' . esc_html__( 'Phone Number:', 'buddyxpro' ) . ' ' . $vendor_settings['_wcv_store_phone'] . '</li>' : '';
			}

			if ( $show_total_sell ) {
				echo '<li class="rrwcv-widget-shop-total-sale">' . esc_html__( 'Total sales:', 'buddyxpro' ) . ' ' . $total_sales . '</li>';
			}
		}

		if ( $show_registration ) {
			echo ! empty( $vendor_registration ) ? '<li class="rwcv-widget-shop-registration">' . esc_html__( 'Registraion:', 'buddyxpro' ) . ' ' . $vendor_registration . '</li>' : '';
		}

		if ( $show_address ) {

			echo '<li class="rwcv-widget-store-address-lable">' . esc_html__( 'Store Address:', 'buddyxpro' ) . '</li>';

			echo '<li class="rwcv-widget-store-address">';
			echo ! empty( $address_line1 ) ? $address_line1 . ', ' : '';
			echo ! empty( $city ) ? esc_attr( $city ) . ', ' : '';
			echo ! empty( $state ) ? esc_attr( $state ) . ', ' : '';
			echo ! empty( $post_code ) ? esc_attr( $post_code ) : '';
			echo '</li>';
		}

		echo '</ul>';
		$this->widget_end( $args );
	}

	/**
	 * Widget settings form
	 *
	 * @return void
	 * @since 1.5.4
	 */
	public function form( $instance ) {
		parent::form( $instance );
	}

}

add_action(
	'widgets_init',
	function () {
		if ( class_exists( 'WC_Vendors' ) ) {
			register_widget( 'BuddyxPro_WCV_Widget_Vendor_Profile' );
		}
	}
);