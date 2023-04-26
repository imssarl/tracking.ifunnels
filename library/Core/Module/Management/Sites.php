<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package Core_Module
 * @subpackage Management
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.04.2009
 * @version 6.0
 */


/**
 * Class for control sites in project
 *
 * @category   WorkHorse
 * @package    Core_Module
 * @subpackage Management
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Core_Module_Management_Sites extends Core_Module_Management_Pages {

	private $_tableFields=array( 'id', 'root_id', 'flg_type', 'flg_active', 'domain', 'sys_name', 'title', 'added', );
	private $_tableName='sys_site';
	private $_checked=false;
	public $config=array();

	public function __construct() {
		parent::__construct();
		$this->config=Zend_Registry::get( 'config' );
		if ( $this->config->engine->check_site_installed ) { // если надо проверять
			if ( !$this->_checked ) { // если непроверено
				$this->checkInstalled( $this->config->project->toArray() );
				$this->_checked=true;
			}
		}
	}

	private function checkInstalled( $_arr ) {
		foreach( $_arr as $k=>$v ) {
			if ( $k=='frontends' ) {
				$this->checkInstalled( $v );
			} else {
				if ( $this->getSite( $_arrTmp, $v ) ) {
					continue;
				}
				$v['flg_type']=$k=='backend'? 1:0;
				if ( $this->setSite( $_arrTmp, $_arrErr, $v ) ) {
					continue;
				}
				trigger_error( 'Site isnt installed' );
			}
		}
	}

	public function getSites( &$arrRes, $_arrSet=array() ) {
		if ( !empty( $_arrSet['to_select'] ) ) {
			$arrRes=Core_Sql::getKeyVal( 'SELECT root_id, title FROM '.$this->_tableName.' ORDER BY added DESC' );
		} else {
			$arrRes=Core_Sql::getAssoc( 'SELECT * FROM '.$this->_tableName.' ORDER BY added DESC' );
		}
		return !empty( $arrRes );
	}

	public function getSitesToSelect( &$arrRes ) {
		return $this->getSites( $arrRes, array( 'to_select'=>true ) );
	}

	public function setSite( &$arrRes, &$arrErr, $_arrDta=array() ) {
		$_arrDat=Core_A::array_check( $_arrDta, $this->post_filter );
		// записываем в таблицу сайтов
		// проверить на sys_name exists
		if ( !$this->error_check( $arrRes, $arrErr, $_arrDat, array(
			'sys_name'=>empty( $_arrDat['sys_name'] ),
		) ) ) {
			return false;
		}
		$arrRes['added']=time();
		$arrRes['flg_active']=empty( $_arrDat['flg_active'] ) ? 0:1;
		$arrRes['flg_type']=empty( $_arrDat['flg_type'] ) ? 0:1;
		$arrRes['id']=Core_Sql::setInsertUpdate( $this->_tableName, $this->get_valid_array( $arrRes, $this->_tableFields ) );
		if ( empty( $arrRes['root_id'] ) ) {
			// создаём корневую страницу
			$arrPage=$this->get_valid_array( $arrRes, array( 'sys_name', 'title' ) );
			$arrPage['pid']=$this->root_id; // это из Core_Adjacency
			if ( !$this->setPage( $_arrRes, $_arrErr, $arrPage ) ) {
				return false;
			}
			// апдэйтим root_id
			$arrRes['root_id']=$_arrRes['id'];
			Core_Sql::setInsertUpdate( $this->_tableName, $this->get_valid_array( $arrRes, $this->_tableFields ) );
			// апдэйтим страницу root_id
			$arrPage=$_arrRes;
			$arrPage['root_id']=$arrPage['id'];
			if ( !$this->setPage( $_arrRes, $_arrErr, $arrPage ) ) {
				return false;
			}
		}
		return true;
	}

	public function delSite() {}

	public function getSite( &$arrRes, $_arrSet=array() ) {
		if ( !empty( $_arrSet['id'] ) ) {
			$_arrW[]='id='.Core_Sql::fixInjection( $_arrSet['id'] );
		}
		if ( !empty( $_arrSet['root_id'] ) ) {
			$_arrW[]='root_id='.Core_Sql::fixInjection( $_arrSet['root_id'] );
		}
		if ( !empty( $_arrSet['sys_name'] ) ) {
			$_arrW[]='sys_name='.Core_Sql::fixInjection( $_arrSet['sys_name'] );
		}
		if ( empty( $_arrW ) ) {
			return false;
		}
		$arrRes=Core_Sql::getRecord( 'SELECT * FROM '.$this->_tableName.' WHERE '.join( ' AND ', $_arrW ).' LIMIT 1' );
		return !empty( $arrRes );
	}
}
?>