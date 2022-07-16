<?php
defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
if ( ! class_exists( "cmplz_datarequest" ) ) {
	class cmplz_datarequest {
		private static $_this;

		function __construct() {
			if ( isset( self::$_this ) ) {
				wp_die( sprintf( '%s is a singleton class and you cannot create a second instance.',
					get_class( $this ) ) );
			}

			self::$_this = $this;
			add_shortcode( 'cmplz-data-request', array($this, 'datarequest_form') );
			add_action( 'activated_plugin', array( $this, 'update_db_check' ), 10, 2 );
			add_action( 'admin_init', array( $this, 'update_db_check' ), 10 );
			add_filter( 'cmplz_datarequest_options', array( $this, 'datarequest_options' ), 30 );
		}

		static function this() {
			return self::$_this;
		}

		/**
		 * Extend options with generic options
		 *
		 * @param array $options
		 *
		 * @return array
		 */

		public function datarequest_options( $options = [] ){
			$options = $options + [
				"request_for_access"   => [
						'short' => __( 'Request for access', 'complianz-gdpr' ),
						'long' => __( 'Submit a request for access to the data we process about you.', 'complianz-gdpr' ),
						'slug' => 'definition/what-is-the-right-to-access/',
				],
				"right_to_be_forgotten"   => [
						'short'  => __( 'Right to be forgotten', 'complianz-gdpr' ),
						'long' => __( 'Submit a request for deletion of the data if it is no longer relevant.', 'complianz-gdpr' ),
						'slug' => 'definition/right-to-be-forgotten/',
				],
				"right_to_data_portability" => [
						'short' => __( 'Right to data portability', 'complianz-gdpr' ),
						'long' => __( 'Submit a request to receive an export file of the data we process about you.', 'complianz-gdpr' ),
						'slug' => 'definition/right-to-data-portability/',
				],
			];
			return $options;
		}
		/**
		 * Render the form in the shortcode
		 *
		 * @return string
		 */
		public function datarequest_form($atts = [], $content = null, $tag = '') {
				$atts = array_change_key_case( (array) $atts, CASE_LOWER );
				$atts = shortcode_atts( array( 'region' => 'us' ), $atts, $tag );
				$region = $atts['region'];
				ob_start();
			?>
			<div class="cmplz-datarequest cmplz-alert">
				<span class="cmplz-close">&times;</span>
				<span id="cmplz-message"></span>
			</div>
			<form id="cmplz-datarequest-form">
				<input type="hidden" required value="<?php echo esc_attr($region)?>" name="cmplz_datarequest_region" id="cmplz_datarequest_region" >

				<label for="cmplz_datarequest_firstname" class="cmplz-first-name"><?php echo __('Name','complianz-gdpr')?>
					<input type="search" class="datarequest-firstname" value="" placeholder="your first name" id="cmplz_datarequest_firstname" name="cmplz_datarequest_firstname" >
				</label>
				<div>
					<label for="cmplz_datarequest_name"><?php echo __('Name','complianz-gdpr')?></label>
					<input type="text" required value="" placeholder="<?php echo __('Your name','complianz-gdpr')?>" id="cmplz_datarequest_name" name="cmplz_datarequest_name">
				</div>
				<div>
					<label for="cmplz_datarequest_email"><?php echo __('Email','complianz-gdpr')?></label>
					<input type="email" required value="" placeholder="<?php echo __('email@email.com','complianz-gdpr')?>" id="cmplz_datarequest_email" name="cmplz_datarequest_email">
				</div>
				<?php
					$options = $this->datarequest_options();
					foreach ( $options as $id => $label ) { ?>
						<div class="cmplz_datarequest cmplz_datarequest_<?php echo $id?>">
							<label for="cmplz_datarequest_<?php echo $id?>">
								<input type="checkbox" value="1" name="cmplz_datarequest_<?php echo $id?>" id="cmplz_datarequest_<?php echo $id?>"/>
								<?php echo $label['long']?>
							</label>
						</div>
				<?php } ?>
				<input type="button" id="cmplz-datarequest-submit"  value="<?php echo __('Send','complianz-gdpr')?>">
			</form>

			<style>
				/* first-name is honeypot */
				.cmplz-first-name {
					position: absolute !important;
					left: -5000px !important;
				}
			</style>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}

		/**
		 * Extend the table to include pro data request options
		 * @return void
		 */

		public function update_db_check() {
			if ( get_option( 'cmplz_datarequests_db_version' ) != cmplz_version ) {
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				global $wpdb;
				$charset_collate = $wpdb->get_charset_collate();
				$table_name = $wpdb->prefix . 'cmplz_dnsmpd';
				$sql        = "CREATE TABLE $table_name (
				  `ID` int(11) NOT NULL AUTO_INCREMENT,
				  `request_for_access` int(11) NOT NULL,
				  `right_to_be_forgotten` int(11) NOT NULL,
				  `right_to_data_portability` int(11) NOT NULL,
				  PRIMARY KEY  (ID)
				) $charset_collate;";
				dbDelta( $sql );
				update_option( 'cmplz_datarequests_db_version', cmplz_version );
			}
		}
	} //class closure
}
$data_request = new cmplz_datarequest();
