<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Modules\Insights;

use FernleafSystems\Wordpress\Plugin\Shield\Controller\Assets\Enqueue;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\BaseShield;
use FernleafSystems\Wordpress\Services\Services;

class ModCon extends BaseShield\ModCon {

	protected function setupCustomHooks() {
		add_action( 'admin_footer', function () {
			/** @var UI $UI */
			$UI = $this->getUIHandler();
			$UI->printAdminFooterItems();
		}, 100, 0 );
	}

	protected function onModulesLoaded() {
		$this->handleCustomRedirection();
	}

	private function handleCustomRedirection() {
		$con = $this->getCon();
		if ( !Services::WpGeneral()->isAjax() && is_admin() ) {
			if ( !$con->isModulePage() && $con->getModule_Plugin()->getActivateLength() < 5 ) {
				Services::Response()->redirect( $con->getModule_Plugin()->getUrl_Wizard( 'welcome' ) );
			}
			elseif ( $this->getAdminPage()->isCurrentPage() && empty( $this->getCurrentInsightsPage() ) ) {
				Services::Response()->redirect( $con->getPluginUrl_DashboardHome() );
			}
		}
	}

	public function getUrl_IpAnalysis( string $ip ) :string {
		return add_query_arg( [ 'analyse_ip' => $ip ], $this->getUrl_IPs() );
	}

	public function getUrl_IPs() :string {
		return $this->getUrl_SubInsightsPage( 'ips' );
	}

	public function getUrl_ScansResults() :string {
		return $this->getUrl_SubInsightsPage( 'scans_results' );
	}

	public function getUrl_ScansRun() :string {
		return $this->getUrl_SubInsightsPage( 'scans_run' );
	}

	public function getUrl_Sessions() :string {
		return $this->getUrl_SubInsightsPage( 'users' );
	}

	public function getUrl_SubInsightsPage( string $inavPage, string $subNav = '' ) :string {
		return add_query_arg(
			array_filter( [
				'inav'   => sanitize_key( $inavPage ),
				'subnav' => sanitize_key( $subNav ),
			] ),
			$this->getUrl_AdminPage()
		);
	}

	public function getCurrentInsightsPage() :string {
		return (string)Services::Request()->query( 'inav' );
	}

	public function getScriptLocalisations() :array {
		$locals = parent::getScriptLocalisations();

		$insightsData = [
			'strings' => [
				'select_action'   => __( 'Please select an action to perform.', 'wp-simple-firewall' ),
				'are_you_sure'    => __( 'Are you sure?', 'wp-simple-firewall' ),
				'absolutely_sure' => __( 'Are you absolutely sure?', 'wp-simple-firewall' ),
			],
			'vars'    => [
			],
		];

		if ( $this->getCurrentInsightsPage() === 'overview' ) {
			// Supply data for the progress meters
			$insightsData[ 'vars' ][ 'meters' ] = [
				'ajax' => [
					'render_meter_analysis' => $this->getAjaxActionData( 'render_meter_analysis' ),
				]
			];
		}

		$locals[] = [ 'plugin', 'icwp_wpsf_vars_insights', $insightsData ];

		$locals[] = [
			'shield/navigation',
			'shield_vars_navigation',
			[
				'ajax' => [
					'dynamic_load' => $this->getAjaxActionData( 'dynamic_load' )
				]
			]
		];

		return $locals;
	}

	public function getCustomScriptEnqueues() :array {
		$enq = [
			Enqueue::CSS => [],
			Enqueue::JS  => [],
		];

		$con = $this->getCon();
		$inav = $this->getCurrentInsightsPage();
		if ( empty( $inav ) ) {
			$inav = 'overview';
		}

		if ( $con->getIsPage_PluginAdmin() ) {
			switch ( $inav ) {

				case 'importexport':
					$enq[ Enqueue::JS ][] = 'shield/import';
					break;

				case 'overview':
					$enq[ Enqueue::JS ] = [
						'ip_detect'
					];
					break;

				case 'reports':
					$enq[ Enqueue::JS ] = [
						'chartist',
						'chartist-plugin-legend',
						'shield/charts',
					];
					$enq[ Enqueue::CSS ] = [
						'chartist',
						'chartist-plugin-legend',
						'shield/charts'
					];
					break;

				case 'wizard':
					$enq[ Enqueue::JS ][] = 'shield/wizard';
					$enq[ Enqueue::CSS ][] = 'shield/wizard';
					break;

				case 'notes':
				case 'scans_results':
				case 'scans_run':
				case 'audit':
				case 'audit_trail':
				case 'traffic':
				case 'ips':
				case 'debug':
				case 'users':
				case 'stats':

					$enq[ Enqueue::JS ][] = 'shield/tables';
					if ( in_array( $inav, [ 'scans_results', 'scans_run' ] ) ) {
						$enq[ Enqueue::JS ][] = 'shield/scans';
					}
					break;
			}
		}

		return $enq;
	}
}