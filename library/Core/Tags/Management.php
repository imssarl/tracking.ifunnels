<?php
class Core_Tags_Management {



}

/**
 * Tags Manage
 * @internal управление тэгами разных типов
 * @category framework
 * @package Auxiliary
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2008, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 30.07.2008
 * @version 1.0
 */


class generic_tags extends tags {

	public function __construct( $_arrSet=array() ) {}

	public function get_tags( &$arrRes, &$arrPg, &$_arrSet ) {
		$obj=new Core_Sql_Qcrawler();
		$obj->set_select( 't.*' );
		$obj->set_select( 'IF(INSTR(t.tag, "_"),REPLACE(t.tag,"_"," "),t.tag) decoded, COUNT(l.item_id) items_num' );
		$obj->set_from( 't_tags t' );
		$obj->set_from( 'LEFT JOIN t_link l ON l.tags_id=t.id' );
		if ( is_numeric( $_arrSet['flg_type'] ) ) {
			$obj->set_where( 't.flg_type="'.$_arrSet['flg_type'].'"' );
		}
		if ( !empty( $_arrSet['item_ids'] ) ) {
			$_arrSet['item_ids']=is_array( $_arrSet['item_ids'] )?$_arrSet['item_ids']:array( $_arrSet['item_ids'] );
			$obj->set_where( 'l.item_id IN("'.join( '", "', $_arrSet['item_ids'] ).'")' );
		}
		if ( !empty( $_arrSet['search_num'] ) ) {
			$obj->set_where( 't.search_num<"'.$_arrSet['search_num'].'"' );
		}
		if ( !empty( $_arrSet['tagnames'] )&&$this->t_encode( $_arrTag, $_arrSet['tagnames'] ) ) {
			$obj->set_where( 't.tag IN("'.join( '", "', $_arrTag ).'")' );
		}
		if ( !empty( $_arrSet['interval'] ) ) {
			$obj->set_where( 't.added>UNIX_TIMESTAMP()-'.$_arrSet['interval'] );
		}
		$_arrSet['order']=empty( $_arrSet['order'] ) ? 't.search_num--up':$_arrSet['order'];
		if ( !empty( $_arrSet['order'] ) ) {
			$obj->set_order_sort( $_arrSet['order'] );
		}
		$obj->set_group( 't.id' );
		if ( !empty( $_arrSet['limit'] ) ) {
			$obj->set_limit( $_arrSet['limit'] );
		}
		$obj->get_sql( $_strQ, $arrPg, $_arrSet );
		//Core_A::p( $_strQ );
		$arrRes=Core_Sql::getAssoc( $_strQ );
		return !empty( $arrRes );
	}

	public function tags_edit( $_arrDta=array() ) {
		if ( empty( $_arrDta ) ) {
			return false;
		}
		if ( !is_array( $_arrDta[0] ) ) {
			$_arrDta=array( $_arrDta );
		}
		foreach( $_arrDta as $v ) {
			if ( empty( $v['id'] ) ) {
				continue;
			}
			Core_Sql::setUpdate( 't_tags', $v );
		}
		return true;
	}

	public function tags_delete( $_mix=array() ) {
		if ( empty( $_mix ) ) {
			return false;
		}
		if ( !is_array( $_mix ) ) {
			$_mix=array( $_mix );
		}
		Core_Sql::setExec( '
			DELETE t, l
			FROM t_tags t
			LEFT JOIN t_link l ON l.tags_id=t.id
			WHERE t.id IN("'.join( '", "', $_mix ).'")
		' );
		return true;
	}
}
?>