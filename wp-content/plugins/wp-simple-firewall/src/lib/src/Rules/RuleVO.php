<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Rules;

use FernleafSystems\Utilities\Data\Adapter\DynPropertiesClass;

/**
 * @property string   $slug
 * @property string   $name
 * @property string   $description
 * @property string   $wp_hook
 * @property int      $wp_hook_level
 * @property bool     $result
 * @property bool     $immediate_exec_response
 * @property string[] $flags
 * @property string[] $prerequisites
 * @property array[]  $conditions
 * @property array[]  $responses
 * @property string[] $all_actions
 */
class RuleVO extends DynPropertiesClass {

	public function __get( string $key ) {
		$value = parent::__get( $key );
		switch ( $key ) {
			case 'wp_hook':
				if ( empty( $value ) ) {
					$value = $this->determineWpHook();
					$this->wp_hook = $value;
				}
				break;

			case 'immediate_exec_response':
				$value = (bool)$value;
				break;

			case 'flags':
			case 'prerequisites':
			case 'conditions':
			case 'responses':
				if ( !is_array( $value ) ) {
					$value = [];
				}
				$this->{$key} = $value;
				break;

			default:
				break;
		}
		return $value;
	}

	private function determineWpHook() :string {
		$hook = '';
		if ( isset( $this->prerequisites[ 'is_logged_in' ] ) ) {
			$hook = 'init';
		}
		return $hook;
	}
}