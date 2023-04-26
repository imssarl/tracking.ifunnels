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
 * Group control
 * @category framework
 * @package AccsessControlSystem
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2008, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 17.03.2008
 * @version 1.3
 */


class Core_Acs_Groups extends Core_Acs_Rights {
	private $all_new=false;

	function __construct() {}

	public function getGroupBySysName( $_str='' ) {
		if ( empty( $_str ) ) {
			return array();
		}
		return Core_Sql::getRecord( 'SELECT * FROM u_groups WHERE sys_name="'.$_str.'"' );
	}

	public function getGroupsBySys( $_arr=array() ) {
		if ( empty( $_arr ) ) {
			return array();
		}
		return Core_Sql::getAssoc( 'SELECT * FROM u_groups WHERE sys_name IN ('.Core_Sql::fixInjection( $_arr ).')' );
	}

	function r_setsystemsgroup( $_arrGrp=array() ) {
		if ( empty( $_arrGrp ) ) {
			return false;
		}
		foreach( $_arrGrp as $v ) {
			$arrDta[]=array( 'sys_name'=>$v, 'title'=>$v );
		}
		$this->all_new=true;
		$_bool=$this->r_setgroups( $_arrTmp1, $_arrTmp2, $arrDta );
		$this->all_new=false;
		return $_bool;
	}

	function r_setgroups( &$arrRes, &$arrErr, $arrDta=array() ) {
		if ( empty( $arrDta ) ) {
			return false;
		}
		$arrDel=array();
		foreach( $arrDta as $k=>$v ) {
			if ( !empty( $v['del'] ) ) {
				$arrDel[]=$k;
			} elseif ( !empty( $v['title'] ) ) {
				$_arrFld=array();
				if ( !empty( $k )&!$this->all_new ) {
					$_arrFld['id']=$k;
				}
				$_arrFld=$v+$_arrFld;
				Core_Sql::setInsertUpdate( 'u_groups', $this->get_valid_array( $_arrFld, $this->r_tbl['u_groups'] ) );
			}
		}
		$this->r_delgroup( $arrDel );
		return true;
	}

	function r_get_to_editlist( &$arrRes ) {
		$arrRes=Core_Sql::getAssoc( '
			SELECT g.*, COUNT(r2g.rights_id) rnum
			FROM u_groups g
			LEFT JOIN u_rights2group r2g ON r2g.group_id=g.id
			GROUP BY g.id
			ORDER BY g.id
		' );
		return !empty( $arrRes );
	}

	function r_get_to_list( &$arrRes ) {
		$arrRes=Core_Sql::getAssoc( 'SELECT * FROM u_groups ORDER BY id' );
		return !empty( $arrRes );
	}

	function r_get_to_select( &$arrRes ) {
		$arrRes=Core_Sql::getKeyVal( 'SELECT id, title FROM u_groups ORDER BY id' );
		return !empty( $arrRes );
	}

	function r_get_ids_by_title( &$arrRes, $_arrTitles=array() ) {
		if ( empty( $_arrTitles ) ) {
			return false;
		}
		$_strKey=join( '_', $_arrTitles );
		if ( isSet( $GLOBALS['BACKUP_QUERY']['r_get_ids_by_title'][$_strKey] ) ) {
			$arrRes=$GLOBALS['BACKUP_QUERY']['r_get_ids_by_title'][$_strKey];
		}
		if ( empty( $arrRes ) ) {
			$arrRes=Core_Sql::getKeyVal( 'SELECT id, title FROM u_groups WHERE sys_name IN("'.join( '", "', $_arrTitles ).'") ORDER BY id' );
		}
		if ( empty( $arrRes ) ) {
			return false;
		}
		$GLOBALS['BACKUP_QUERY']['r_get_ids_by_title'][$_strKey]=$arrRes;
		return true;
	}

	public static function getIdsBySysName( &$arrRes, $_arrSys=array() ) {
		if ( empty( $_arrSys ) ) {
			return false;
		}
		$arrRes=Core_Sql::getField( 'SELECT id FROM u_groups WHERE sys_name IN('.Core_Sql::fixInjection( $_arrSys ).')' );
		return !empty( $arrRes );
	}

	public function r_get_ids_by_sys_name( &$arrRes, $_arrSys=array() ) {
		if ( empty( $_arrSys ) ) {
			return false;
		}
		$arrRes=Core_Sql::getKeyVal( 'SELECT id, sys_name FROM u_groups WHERE sys_name IN("'.join( '", "', $_arrSys ).'") ORDER BY id' );
		return !empty( $arrRes );
	}

	function r_get_title_by_ids( &$arrRes, $_arrIds=array() ) {
		if ( empty( $_arrIds ) ) {
			return false;
		}
		$arrRes=Core_Sql::getKeyVal( 'SELECT id, title FROM u_groups WHERE id IN("'.join( '", "', $_arrIds ).'") ORDER BY id' );
		return !empty( $arrRes );
	}

	function r_get_sys_name_by_ids( &$arrRes, $_arrIds=array() ) {
		if ( empty( $_arrIds ) ) {
			return false;
		}
		$arrRes=Core_Sql::getKeyVal( 'SELECT id, sys_name FROM u_groups WHERE id IN("'.join( '", "', $_arrIds ).'") ORDER BY id' );
		return !empty( $arrRes );
	}

	function r_check_group( $_mixDta=0 ) {
		if ( empty( $_mixDta ) ) {
			return false;
		}
		if ( is_array( $_mixDta ) ) {
			foreach( $_mixDta as $k=>$v ) {
				$_arrW[]=$k.'='.$v;
			}
		} else {
			$_arrW[]='id='.$_mixDta;
		}
		$_flg=Core_Sql::getCell( 'SELECT 1 FROM u_groups WHERE '.join( ' AND ', $_arrW ) );
		return !empty( $_flg );
	}

	function r_get_groups_without_visitor_full( &$arrRes ) {
		$arrRes=Core_Sql::getAssoc( '
			SELECT g.*, COUNT(r2g.rights_id) rnum
			FROM u_groups g
			LEFT JOIN u_rights2group r2g ON r2g.group_id=g.id
			WHERE g.title!="Visitor"
			GROUP BY g.id
			ORDER BY g.id
		' );
		return !empty( $arrRes );
	}

	function getGroupsWithoutVisitorList( &$arrRes ) {
		$arrRes=Core_Sql::getKeyVal( 'SELECT sys_name, title FROM u_groups WHERE sys_name!="Visitor" ORDER BY title' );
	}

	function r_get_groups_without_visitor_list( &$arrRes ) {
		$arrRes=Core_Sql::getKeyVal( 'SELECT id, title FROM u_groups WHERE title!="Visitor" ORDER BY id' );
		return !empty( $arrRes );
	}

	function r_get_groups_without_visitor( &$arrRes ) {
		$arrRes=Core_Sql::getAssoc( 'SELECT id, title FROM u_groups WHERE title!="Visitor" ORDER BY id' );
		return !empty( $arrRes );
	}

	function r_delgroup( $_arrIds=array() ) {
		if ( empty( $_arrIds ) ) {
			return false;
		}
		Core_Sql::setExec( '
			DELETE u_groups, u_rights2group
			FROM u_groups
			LEFT JOIN u_rights2group ON u_rights2group.group_id=u_groups.id
			WHERE u_groups.id IN("'.join( '", "', $_arrIds ).'")
		' );
		return true;
	}
}
?>