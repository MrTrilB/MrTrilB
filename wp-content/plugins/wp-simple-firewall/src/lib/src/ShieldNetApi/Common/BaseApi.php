<?php

namespace FernleafSystems\Wordpress\Plugin\Shield\ShieldNetApi\Common;

use FernleafSystems\Utilities\Data\Adapter\DynPropertiesClass;
use FernleafSystems\Wordpress\Services\Services;
use FernleafSystems\Wordpress\Services\Utilities\HttpRequest;

/**
 * @property int         $api_version
 * @property string      $lookup_url_stub
 * @property string      $request_method
 * @property int         $timeout
 * @property HttpRequest $last_http_req
 * @property array       $params_body
 * @property array       $params_query
 */
abstract class BaseApi extends DynPropertiesClass {

	const DEFAULT_URL_STUB = '';
	const API_ACTION = '';

	/**
	 * @return array|null
	 */
	protected function sendReq() {
		$httpReq = Services::HttpRequest();

		$reqParams = [
			'timeout' => $this->timeout,
		];

		switch ( $this->request_method ) {

			case 'post':
				$reqParams[ 'body' ] = $this->params_body;
				$bReqSuccess = $httpReq->post( $this->getApiRequestUrl(), $reqParams );
				break;

			case 'get':
			default:
				// Doing it in the ['body'] on some sites fails with the params not passed through to query string.
				// if they're not using the newer WP Request() class. WP 4.6+
				$bReqSuccess = $httpReq->get(
					add_query_arg( $this->params_query, $this->getApiRequestUrl() ),
					$reqParams
				);
				break;
		}

		if ( $bReqSuccess ) {
			$aResponse = empty( $httpReq->lastResponse->body ) ? [] : @json_decode( $httpReq->lastResponse->body, true );
		}
		else {
			$aResponse = null;
		}

		$this->last_http_req = $httpReq;
		return $aResponse;
	}

	protected function getApiRequestUrl() :string {
		return sprintf( '%s/v%s/%s', $this->lookup_url_stub, $this->api_version, static::API_ACTION );
	}

	/**
	 * @return string[]
	 */
	protected function getRequestParamKeys() {
		return [];
	}

	/**
	 * @return mixed
	 */
	public function __get( string $key ) {

		$value = parent::__get( $key );

		switch ( $key ) {

			case 'params_query':
			case 'params_body':
				if ( !is_array( $value ) ) {
					$value = [];
				}
				break;

			case 'request_method':
				$value = empty( $value ) ? 'get' : strtolower( $value );
				break;

			case 'api_version':
				if ( empty( $value ) ) {
					$value = 1;
				}
				break;

			case 'lookup_url_stub':
				if ( empty( $value ) ) {
					$value = static::DEFAULT_URL_STUB;
				}
				$value = rtrim( $value, '/' );
				break;

			case 'timeout':
				if ( empty( $value ) || !is_numeric( $value ) ) {
					$value = 60;
				}
				break;

			default:
				break;
		}

		return $value;
	}
}