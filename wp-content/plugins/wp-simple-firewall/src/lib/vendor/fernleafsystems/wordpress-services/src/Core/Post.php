<?php

namespace FernleafSystems\Wordpress\Services\Core;

use FernleafSystems\Wordpress\Services\Services;

class Post {

	/**
	 * @param $id
	 * @return false|\WP_Post
	 */
	public function getById( $id ) {
		return \WP_Post::get_instance( $id );
	}

	/**
	 * @return string
	 */
	public function getCurrentPage() {
		global $pagenow;
		return $pagenow;
	}

	/**
	 * @return \WP_Post
	 */
	public function getCurrentPost() {
		global $post;
		return $post;
	}

	/**
	 * @return int
	 */
	public function getCurrentPostId() {
		$oPost = $this->getCurrentPost();
		return empty( $oPost->ID ) ? -1 : $oPost->ID;
	}

	/**
	 * @param $sTermSlug
	 * @return bool
	 */
	public function getDoesWpPostSlugExist( $sTermSlug ) {
		$oDb = Services::WpDb();
		$sQuery = "
				SELECT ID
				FROM %s
				WHERE
					post_name = '%s'
					LIMIT 1
			";
		$sQuery = sprintf(
			$sQuery,
			$oDb->getTable_Posts(),
			$sTermSlug
		);
		$nResult = $oDb->getVar( $sQuery );
		return !is_null( $nResult ) && $nResult > 0;
	}

	/**
	 * @param string
	 * @return string
	 */
	public function isCurrentPage( $page ) :bool {
		return $page == $this->getCurrentPage();
	}

	public function isPage_Updates() :bool {
		return $this->isCurrentPage( 'update.php' );
	}
}