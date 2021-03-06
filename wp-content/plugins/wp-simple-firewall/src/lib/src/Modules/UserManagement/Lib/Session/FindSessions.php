<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Modules\UserManagement\Lib\Session;

use FernleafSystems\Wordpress\Plugin\Shield\Modules\ModConsumer;
use FernleafSystems\Wordpress\Services\Services;

class FindSessions {

	use ModConsumer;

	public function mostRecent() :array {
		return $this->lookupFromUserMeta();
	}

	public function byIP( string $ip ) :array {
		$sessions = [];
		foreach ( $this->lookupFromUserMeta( $ip ) as $userID => $userAtIP ) {
			$sessions[ $userID ] = array_map(
				function ( $sess ) use ( $userAtIP ) {
					$sess[ 'user_login' ] = $userAtIP[ 'user_login' ];
					return $sess;
				},
				\WP_Session_Tokens::get_instance( $userAtIP[ 'user_id' ] )->get_all()
			);
		}
		return $sessions;
	}

	public function lookupFromUserMeta( string $ip = '', int $limit = 10, string $orderBy = '`user_meta`.last_login_at' ) :array {
		$modData = $this->getCon()->getModule_Data();

		$wheres = [];
		if ( !empty( $ip ) ) {
			$wheres[] = sprintf( "`ips`.ip=INET6_ATON('%s')", $ip );
		}

		$DB = Services::WpDb();
		$results = $DB->selectCustom(
			sprintf( 'SELECT `user_meta`.user_id as user_id,
       					`user_meta`.last_login_at as last_login_at,
       					INET6_NTOA(`ips`.ip) as ip,
       					`wp_users`.user_login as user_login

						FROM `%s` as `user_meta`
						INNER JOIN `%s` as `ips`
						    ON `user_meta`.ip_ref = `ips`.id
						INNER JOIN `%s` as `wp_users` 
						    ON `user_meta`.user_id = `wp_users`.id
						%s
						ORDER BY %s DESC
						%s;',
				$modData->getDbH_UserMeta()->getTableSchema()->table,
				$modData->getDbH_IPs()->getTableSchema()->table,
				$DB->getTable_Users(),
				empty( $wheres ) ? '' : 'WHERE '.implode( ' AND ', $wheres ),
				$orderBy,
				empty( $limit ) ? '' : 'LIMIT '.$limit
			)
		);

		$byUserIDs = [];
		foreach ( is_array( $results ) ? $results : [] as $result ) {
			$byUserIDs[ $result[ 'user_id' ] ] = $result;
		}

		return $byUserIDs;
	}
}