<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Logging\Processors;

use FernleafSystems\Wordpress\Plugin\Shield\Modules\Data\DB\ReqLogs\Ops\Handler;
use FernleafSystems\Wordpress\Services\Services;

class RequestMetaProcessor extends BaseMetaProcessor {

	/**
	 * @inheritDoc
	 */
	public function __invoke( array $record ) {
		$WP = Services::WpGeneral();
		$isWpCli = $WP->isWpCli();

		$req = Services::Request();
		$leadingPath = Services::WpGeneral()->isMultisite_SubdomainInstall() ? $req->getHost() : '';

		if ( $isWpCli ) {
			global $argv;
			$path = $argv[ 0 ];
			$query = count( $argv ) === 1 ? '' : implode( ' ', array_slice( $argv, 1 ) );
		}
		else {
			$path = $leadingPath.$req->getPath();
			$query = empty( $_GET ) ? '' : http_build_query( $_GET );
		}

		if ( $isWpCli ) {
			$type = Handler::TYPE_WPCLI;
		}
		elseif ( $WP->isAjax() ) {
			$type = Handler::TYPE_AJAX;
		}
		elseif ( Services::Rest()->isRest() ) {
			$type = Handler::TYPE_REST;
		}
		elseif ( $WP->isXmlrpc() ) {
			$type = Handler::TYPE_XMLRPC;
		}
		elseif ( $WP->isCron() ) {
			$type = Handler::TYPE_CRON;
		}
		elseif ( $WP->isLoginRequest() ) {
			$type = Handler::TYPE_LOGIN;
		}
		elseif ( $WP->isLoginUrl() && $req->isPost() && $req->query( 'shield_action' ) === 'wp_login_2fa_verify' ) {
			$type = Handler::TYPE_2FA;
		}
		elseif ( Services::WpComments()->isCommentSubmission() ) {
			$type = Handler::TYPE_COMMENT;
		}
		else {
			$type = Handler::TYPE_HTTP;
		}

		$data = [
			'ip'   => $isWpCli ? '' : (string)Services::IP()->getRequestIp(),
			'rid'  => Services::Request()->getID( true ),
			'ts'   => microtime( true ),
			'path' => $path,
			'type' => $type,
		];
		if ( !$isWpCli ) {
			$data[ 'ua' ] = $req->getUserAgent();
			$data[ 'code' ] = http_response_code();
			$data[ 'verb' ] = strtoupper( $req->getMethod() );
		}
		if ( !empty( $query ) ) {
			$data[ 'query' ] = $query;
		}

		$record[ 'extra' ][ 'meta_request' ] = $data;

		return $record;
	}
}