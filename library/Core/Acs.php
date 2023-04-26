<?php
/**
 * Accsess Control System
 * @category framework
 * @package AccsessControlSystem
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2008, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 09.04.2008
 * @version 2.1
 */


/**
 * Main ACS class
 * @category framework
 * @package AccsessControlSystem
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2008, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 09.04.2008
 * @version 2.1
 */


class Core_Acs extends Core_Acs_Groups implements Core_Singleton_Interface {

	private static $_instance=NULL;

	function __construct() {
		// если нету r_mandatory_groups то создаём r_tocreate_groups
		if ( !$this->r_get_ids_by_title( $this->r_mandatory_groups_ids, $this->r_mandatory_groups ) ) {
			if ( !$this->r_setsystemsgroup( $this->r_tocreate_groups ) ) {
				trigger_error( ERR_PHP.'|Can\'t run rights constructor - broken create $this->r_tocreate_groups' );
			}
			$this->r_get_ids_by_title( $this->r_mandatory_groups_ids, $this->r_mandatory_groups );
		}
		// берём остальные системные группы
		$this->r_get_ids_by_title( $this->r_system_groups_ids, $this->r_system_groups );
		$this->r_get_ids_by_title( $this->r_minimal_groups_ids, $this->r_minimal_groups );
	}

/*RIGHT2GROUP*/
	function r_getright2group( &$arrRes, $_intId=0 ) {
		if ( empty( $_intId ) ) {
			return false;
		}
		$arrRes=Core_Sql::getKeyVal( 'SELECT rights_id, 1 FROM u_rights2group WHERE group_id="'.$_intId.'"' );
		return true;
	}

	function r_setright2group( $_arrDat=array() ) {
		$_arrDat=Core_A::array_check( $_arrDat, $this->post_filter );
		if ( !$this->error_check( $arrTmp, $arrRes['arrErr'], $_arrDat, array( 
			'group_id'=>empty( $_arrDat['group_id'] ), 
			'rights'=>empty( $_arrDat['rights'] ), 
		) ) ) {
			return false;
		}
		return $this->r_set_rights2groups( $_arrDat['group_id'], $_arrDat['rights'] );
	}

/*GROUP2RIGHT*/
	function r_getgroup2right( &$arrRes, $_intId=0 ) {
		if ( empty( $_intId ) ) {
			return false;
		}
		$arrRes=Core_Sql::getKeyVal( 'SELECT group_id, 1 FROM u_rights2group WHERE rights_id="'.$_intId.'"' );
		return true;
	}

	function r_setgroup2right( $_arrDat=array() ) {
		$_arrDat=Core_A::array_check( $_arrDat, $this->post_filter );
		if ( !$this->error_check( $arrTmp, $arrRes['arrErr'], $_arrDat, array( 
			'right_id'=>empty( $_arrDat['right_id'] ), 
			'groups'=>empty( $_arrDat['groups'] ), 
		) ) ) {
			return false;
		}
		return $this->r_set_groups2rights( $_arrDat['right_id'], $_arrDat['groups'] );
	}

	public static function getInstance() {
		if ( self::$_instance==NULL ) {
			self::$_instance=new Core_Acs();
		}
		return self::$_instance;
	}
}
?>