<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Rules\Conditions;

use FernleafSystems\Wordpress\Plugin\Shield\Modules\IPs\Lib\Ops\LookupIpOnList;
use FernleafSystems\Wordpress\Plugin\Shield\Rules\Conditions\Traits\RequestIP;

class IsIpBlacklisted extends Base {

	use RequestIP;

	const SLUG = 'is_ip_blacklisted';

	protected function execConditionCheck() :bool {
		$thisReq = $this->getCon()->this_req;
		if ( !isset( $thisReq->is_ip_blacklisted ) ) {
			$thisReq->is_ip_blacklisted = !empty( ( new LookupIpOnList() )
				->setDbHandler( $this->getCon()->getModule_IPs()->getDbHandler_IPs() )
				->setIP( $this->getRequestIP() )
				->setListTypeBlock()
				->lookup() );
		}
		return $thisReq->is_ip_blacklisted;
	}
}