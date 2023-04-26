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
 * Right control
 * @category framework
 * @package AccsessControlSystem
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2008, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 09.04.2008
 * @version 1.2
 */


class Core_Acs_Rights extends Core_Services {
	public $r_tbl=array( 
		'u_groups'=>array( 'id', 'sys_name', 'title', 'description' ),
		'u_rights'=>array( 'id', 'sys_name', 'title', 'description' ),
	);
	public $r_tocreate_groups=array( 'Super Admin', 'System Users', 'Content Admin', 'Visitor' );
	public $r_mandatory_groups=array( 'Super Admin' );
	public $r_system_groups=array( 'System Users' );
	public $r_minimal_groups=array( 'Visitor' );
	public $r_mandatory_groups_ids=array();
	public $r_system_groups_ids=array();
	public $r_minimal_groups_ids=array();

	function __construct() {}
	// права по одному
	function r_setrightone( $_arrDat=array() ) {
		$_arrDat=Core_A::array_check( $_arrDat, $this->post_filter );
		if ( !$this->error_check( $arrTmp, $arrRes['arrErr'], $_arrDat, array( 
			'sys_name'=>empty( $_arrDat['sys_name'] ), 
			'title'=>empty( $_arrDat['title'] ), 
		) ) ) {
			return false;
		}
		return $this->r_setrightmass( $_arrDat );
	}

	// права пачкой
	function r_setrightmass( $_arrSet=array() ) {
		if ( empty( $_arrSet ) ) {
			return false;
		}
		if ( !is_array( $_arrSet[0] ) ) {
			$_arrSet=array( $_arrSet );
		}
		foreach( $_arrSet as $k=>$v ) {
			$_arrSet[$k]=$this->get_valid_array( $v, $this->r_tbl['u_rights'] );
		}
		Core_Sql::setMassInsert( 'u_rights', $_arrSet );
		return true;
	}

	// удаление прав и ссылок на них
	function r_delright( $_arrIds=array(), $_arrSet=array() ) {
		if ( !empty( $_arrSet['module_name'] ) ) {
			$this->r_getrights_ids_bylike( $_arrIds, $_arrSet['module_name'] );
			$_arrIds=array_keys( $_arrIds );
		}
		if ( empty( $_arrIds ) ) {
			return false;
		}
		Core_Sql::setExec( '
			DELETE u_rights, u_rights2group
			FROM u_rights
			LEFT JOIN u_rights2group ON u_rights2group.rights_id=u_rights.id
			WHERE u_rights.id IN("'.join( '", "', $_arrIds ).'")
		' );
		return true;
	}

	function r_delright_one( $_arrSet=array() ) {
		if ( empty( $_arrSet['name'] )||empty( $_arrSet['action'] ) ) {
			return false;
		}
		$_arrIds=Core_Sql::getKeyVal( 'SELECT id, sys_name FROM u_rights WHERE sys_name LIKE "'.$_arrSet['name'].'_@_'.$_arrSet['action'].'"' );
		$_arrIds=array_keys( $_arrIds );
		$this->r_delright( $_arrIds );
		return true;
	}

	function r_set_rights2groups( $_intGid=0, $_arrRids=array(), $_flgDelete=true ) {
		if ( empty( $_intGid )||empty( $_arrRids ) ) {
			return false;
		}
		if ( $_flgDelete ) {
			Core_Sql::setExec( 'DELETE FROM u_rights2group WHERE group_id="'.$_intGid.'"' );
		}
		foreach( $_arrRids as $k=>$v ) {
			$arrIns[]=array( 'group_id'=>$_intGid, 'rights_id'=>$k );
		}
		Core_Sql::setMassInsert( 'u_rights2group', $arrIns );
		return true;
	}

	function r_set_groups2rights( $_intRid=0, $_arrGids=array(), $_flgDelete=true ) {
		if ( empty( $_intRid )||empty( $_arrGids ) ) {
			return false;
		}
		if ( $_flgDelete ) {
			Core_Sql::setExec( 'DELETE FROM u_rights2group WHERE rights_id="'.$_intRid.'"' );
		}
		foreach( $_arrGids as $k=>$v ) {
			$arrIns[]=array( 'rights_id'=>$_intRid, 'group_id'=>$k );
		}
		Core_Sql::setMassInsert( 'u_rights2group', $arrIns );
		return true;
	}

	// set this module rights 2 superadmin group
	function r_setmoduleright( $_arrSet=array() ) {
		if ( empty( $_arrSet['module_name'] ) ) {
			return false;
		}
		$this->r_getrights_ids_bylike( $_arrRids, $_arrSet['module_name'] );
		$this->r_get_ids_by_title( $_arrGids, $this->r_mandatory_groups );
		foreach( $_arrGids as $k=>$v ) {
			$this->r_set_rights2groups( $k, $_arrRids, false );
		}
		return true;
	}

	// выбранные права для суперадминских групп
	function r_setmoduleright_one( $_arrSet=array() ) {
		if ( empty( $_arrSet['name'] )||empty( $_arrSet['action'] ) ) {
			return false;
		}
		$_arrRids=Core_Sql::getKeyVal( 'SELECT id, sys_name FROM u_rights WHERE sys_name LIKE "'.$_arrSet['name'].'_@_'.$_arrSet['action'].'"' );
		foreach( $this->r_mandatory_groups_ids as $k=>$v ) {
			$this->r_set_rights2groups( $k, $_arrRids, false );
		}
		return true;
	}

	function r_getrights_to_list( &$arrRes ) {
		$_arrS=Core_Sql::getAssoc( '
			SELECT r.*, a.flg_tree, m.id mid, m.title mtitle
			FROM u_rights r, sys_module m, sys_action a
			WHERE r.sys_name=CONCAT(m.name,"_@_",a.action) AND a.module_id=m.id
			ORDER BY a.flg_tree, a.module_id, r.title
		' );
		if ( empty( $_arrS ) ) {
			return false;
		}
		foreach( $_arrS as $v ) {
			$arrRes[(!isSet( $v['flg_tree'] )? 3:$v['flg_tree'])][(!isSet( $v['mid'] )? 0:$v['mid'])][]=$v;
		}
		return !empty( $arrRes );
	}

	function r_getrights_to_list_plain( &$arrRes, &$arrPg, $_arrSet=array() ) {
		$obj=new Core_Sql_Qcrawler();
		$arrRes=Core_Sql::getAssoc( $obj->getPaged( $_strQ, $arrPg, 'SELECT * FROM u_rights ORDER BY title', $_arrSet ) );
		return !empty( $arrRes );
	}

	// после инсталла модуля берём права данного модуля чтобы привязать их к Супер-пользователю
	function r_getrights_ids_bylike( &$arrRes, $_strLike='' ) {
		if ( empty( $_strLike ) ) {
			return false;
		}
		$arrRes=Core_Sql::getKeyVal( 'SELECT id, sys_name FROM u_rights WHERE sys_name LIKE "'.$_strLike.'_@_%"' );
		return !empty( $arrRes );
	}

	function r_getrights( &$arrRes, $_arrSet=array() ) {
		switch( $_arrSet['mode'] ) {
			case 'to_select': $arrRes=Core_Sql::getKeyVal( 'SELECT id, title FROM u_rights ORDER BY title' ); break;
			case 'to_list_plain': $arrRes=Core_Sql::getAssoc( 'SELECT * FROM u_rights ORDER BY title' ); break;
			case 'to_list': $this->r_getrights_to_list( $arrRes ); break;
			case 'id2sys': $arrRes=Core_Sql::getKeyVal( 'SELECT id, sys_name FROM u_rights' ); break;
			case 'by_ids': $arrRes=Core_Sql::getKeyVal( 'SELECT id, sys_name FROM u_rights WHERE id IN("'.join( '", "', $_arrSet['ids'] ).'")' ); break;
		}
		return !empty( $arrRes );
	}
}
?>