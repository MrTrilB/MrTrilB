<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Modules\Insights;

use FernleafSystems\Wordpress\Plugin\Shield;
use FernleafSystems\Wordpress\Services\Services;

class AjaxHandler extends Shield\Modules\BaseShield\AjaxHandler {

	protected function getAjaxActionCallbackMap( bool $isAuth ) :array {
		$map = parent::getAjaxActionCallbackMap( $isAuth );
		if ( $isAuth ) {
			$map = array_merge( $map, [
				'dynamic_load'          => [ $this, 'ajaxExec_DynamicLoad' ],
				'render_meter_analysis' => [ $this, 'ajaxExec_RenderMeterAnalysis' ],
			] );
		}
		return $map;
	}

	public function ajaxExec_RenderMeterAnalysis() :array {
		try {
			$html = ( new Lib\MeterAnalysis\Handler() )
				->setMod( $this->getMod() )
				->renderAnalysis( Services::Request()->post( 'meter' ) );
			$success = true;
		}
		catch ( \Exception $e ) {
			$html = $e->getMessage();
			$success = false;
		}
		return [
			'success' => $success,
			'html'    => $html,
		];
	}

	public function ajaxExec_DynamicLoad() :array {
		try {
			$pageData = ( new Lib\Requests\DynamicPageLoader() )
				->setMod( $this->getMod() )
				->build( Shield\Modules\Base\Lib\Request\FormParams::Retrieve() );
			$success = true;
		}
		catch ( \Exception $e ) {
			$pageData = [
				'message' => $e->getMessage(),
				'success' => false,
			];
			$success = false;
		}

		return array_merge(
			[
				'success'    => false,
				'message'    => 'no msg',
				'html'       => 'no html',
				'show_toast' => !$success,
			],
			$pageData
		);
	}
}