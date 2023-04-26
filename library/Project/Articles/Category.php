<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Articles
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 18.11.2010
 * @version 2.0
 */


/**
 * Категории статей
 *
 * @category Project
 * @package Project_Articles
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Articles_Category extends Core_Category {

	public function __construct() {
		parent::__construct( 'Article Manager' );
	}

	public function set( &$arrRes, &$arrErr, $_arrData=array() ) {
		$_arrIds=array();
		foreach( $_arrData as $v ) {
			if ( !empty( $v['del'] ) ) {
				$_arrIds[]=$v['id'];
			}
		}
		if ( !empty( $_arrIds ) ) {
			// удаляем стати из категорий
			$_articles=new Project_Articles();
			if ( $_articles->onlyIds()->withCategory( $_arrIds )->getList( $_arrArticlesIds ) ) {
				$_articles->del( $_arrArticlesIds );
			}
		}
		return parent::set( $arrRes, $arrErr, $_arrData );
	}

	public function withPagging( $_arr=array() ) {
		parent::withPagging( $_arr );
		return $this;
	}

	// + подсчёт статей в каждой категории
	public function management( &$arrRes, &$arrPg ) {
		if ( !$this->get( $arrRes, $arrPg ) ) {
			return false;
		}
		$_articles=new Project_Articles();
		foreach( $arrRes as $k=>$v ) {
			$arrRes[$k]['count']=$_articles->withCategory( $v['id'] )->getList( $arrRes[$k]['items'] )? count( $arrRes[$k]['items'] ):0;
		}
		return true;
	}
}
?>