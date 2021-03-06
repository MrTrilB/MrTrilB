<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Modules\IPs\Lib\Bots;

use FernleafSystems\Wordpress\Plugin\Shield\Modules\Data\DB\IPs;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\Data\DB\UserMeta\Ops as UserMetaDB;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\IPs\{
	DB\BotSignal,
	DB\BotSignal\BotSignalRecord,
	DB\BotSignal\LoadBotSignalRecords,
	ModCon
};
use FernleafSystems\Wordpress\Plugin\Shield\Modules\IPs\Components\IpAddressConsumer;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\IPs\Lib\Ops\LookupIpOnList;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\ModConsumer;
use FernleafSystems\Wordpress\Services\Services;

class BotSignalsRecord {

	use ModConsumer;
	use IpAddressConsumer;

	public function delete() :bool {
		/** @var ModCon $mod */
		$mod = $this->getMod();
		$thisReq = $this->getCon()->this_req;
		/** @var BotSignal\Ops\Select $select */
		$select = $mod->getDbH_BotSignal()->getQueryDeleter();

		if ( $thisReq->ip === $this->getIP() ) {
			unset( $thisReq->botsignal_record );
		}

		return $select->filterByIP( $this->getIPRecord()->id )->query();
	}

	public function retrieveNotBotAt() :int {
		/** @var ModCon $mod */
		$mod = $this->getMod();
		return (int)Services::WpDb()->getVar(
			sprintf( "SELECT bs.notbot_at
						FROM `%s` as bs
						INNER JOIN `%s` as ips
							ON `ips`.id = `bs`.ip_ref 
							AND `ips`.`ip`=INET6_ATON('%s')
						ORDER BY `bs`.updated_at DESC
						LIMIT 1;",
				$mod->getDbH_BotSignal()->getTableSchema()->table,
				$this->getCon()->getModule_Data()->getDbH_IPs()->getTableSchema()->table,
				$this->getIP()
			)
		);
	}

	public function retrieve( bool $storeOnLoad = true ) :BotSignalRecord {
		/** @var ModCon $mod */
		$mod = $this->getMod();
		$thisReq = $this->getCon()->this_req;

		if ( $thisReq->ip === $this->getIP() && !empty( $thisReq->botsignal_record ) ) {
			return $thisReq->botsignal_record;
		}

		$r = $this->dbLoad();
		if ( empty( $r ) ) {
			$r = new BotSignalRecord();
			$r->ip_ref = $this->getIPRecord()->id;
		}

		$ipOnList = ( new LookupIpOnList() )
			->setDbHandler( $mod->getDbHandler_IPs() )
			->setIP( $this->getIP() )
			->lookupIp();

		if ( !empty( $ipOnList ) ) {
			if ( empty( $r->bypass_at ) && $ipOnList->list === $mod::LIST_MANUAL_WHITE ) {
				$r->bypass_at = $ipOnList->created_at;
			}
			if ( empty( $r->offense_at ) && $ipOnList->list === $mod::LIST_AUTO_BLACK ) {
				$r->offense_at = $ipOnList->last_access_at;
			}
			$r->blocked_at = $ipOnList->blocked_at;
		}

		if ( empty( $r->notbot_at ) && Services::IP()->getRequestIp() === $this->getIP() ) {
			$r->notbot_at = $mod->getBotSignalsController()
								->getHandlerNotBot()
								->hasCookie() ? Services::Request()->ts() : 0;
		}

		if ( empty( $r->auth_at ) ) {
			/** @var UserMetaDB\Select $userMetaSelect */
			$userMetaSelect = $this->getCon()->getModule_Data()->getDbH_UserMeta()->getQuerySelector();
			$lastUserMetaLogin = $userMetaSelect->filterByIPRef( $r->ip_ref )
												->setColumnsToSelect( [ 'last_login_at' ] )
												->setOrderBy( 'last_login_at' )
												->first();
			if ( !empty( $lastUserMetaLogin ) ) {
				$r->auth_at = $lastUserMetaLogin->last_login_at;
			}
		}

		if ( $storeOnLoad ) {
			$this->store( $r );
		}

		return $r;
	}

	/**
	 * @return BotSignal\BotSignalRecord|null
	 */
	private function dbLoad() {
		try {
			$record = ( new LoadBotSignalRecords() )
				->setMod( $this->getMod() )
				->setIP( $this->getIP() )
				->loadRecord();
		}
		catch ( \Exception $e ) {
			$record = null;
		}

		return $record;
	}

	public function store( BotSignalRecord $record ) :bool {
		/** @var ModCon $mod */
		$mod = $this->getMod();

		if ( empty( $record->id ) ) {
			$success = $mod->getDbH_BotSignal()
						   ->getQueryInserter()
						   ->insert( $record );
		}
		else {
			$data = $record->getRawData();
			$data[ 'updated_at' ] = Services::Request()->ts();
			$success = $mod->getDbH_BotSignal()
						   ->getQueryUpdater()
						   ->updateById( $record->id, $data );
		}

		$thisReq = $this->getCon()->this_req;
		if ( $thisReq->ip === $record->ip ) {
			$thisReq->botsignal_record = $record;
		}

		return $success;
	}

	/**
	 * @param int|null $ts
	 * @throws \LogicException
	 */
	public function updateSignalField( string $field, $ts = null ) :BotSignalRecord {
		/** @var ModCon $mod */
		$mod = $this->getMod();

		if ( !$mod->getDbH_BotSignal()->getTableSchema()->hasColumn( $field ) ) {
			throw new \LogicException( sprintf( '"%s" is not a valid column on Bot Signals', $field ) );
		}

		$record = $this->retrieve( false ); // false as we're going to store it anyway
		$record->{$field} = is_null( $ts ) ? Services::Request()->ts() : $ts;

		$this->store( $record );

		return $record;
	}

	private function getIPRecord() :IPs\Ops\Record {
		return ( new IPs\IPRecords() )
			->setMod( $this->getCon()->getModule_Data() )
			->loadIP( $this->getIP(), true );
	}
}