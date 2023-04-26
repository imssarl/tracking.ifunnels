<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Sites
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 18.03.2010
 * @version 0.1
 */

 /**
 * система сайтов
 *
 * @category Project
 * @package Project_Sites
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
abstract class Project_Sites_Type_Abstract {

	protected $_userId=0;

	protected $_errors=array();

	protected $_table='';

	protected $_fields=array();

	protected $_withOrder='';

	protected $_siteCode=''; // код сайта (см. Project_Sites::$code) нужен для опшинсов Project_Options

	public $data;

	public function setUser( $_int ) {
		$this->_userId=$_int;
		return $this;
	}

	public function setSiteCode( $_str='' ) {
		$this->_siteCode=$_str;
		return $this;
	}

	public function getTable() {
		return $this->_table;
	}

	public function getFields() {
		return $this->_fields;
	}

	public function getWithOrder() {
		return $this->_withOrder;
	}

	public function getErrors( &$arrError ) {
		$arrError = $this->_errors;
		return true;
	}

	abstract public function set( Project_Sites $object );

	abstract public function get( &$arrRes, $_arrSite=array() );

	abstract public function del( $_arrIds );

	// ниже то что касается загрузки кода на сервер

	protected $_dir; // папка в которую набиваем содержимое сайта

	abstract public function prepareSource();
	
	abstract public function import( Project_Sites $object );

	/**
	 * отдельно смена категории
	 *
	 * @param integer $_intSiteId
	 * @param integer $_intCatId id новой категории
	 * @return bool
	 */
	public function changeCategory( $_intSiteId=0, $_intCatId=0 ) {
		if ( empty( $_intSiteId )||empty( $_intCatId ) ) {
			return false;
		}
		Core_Sql::setExec( '
			UPDATE '.$this->_table.' SET category_id='.Core_Sql::fixInjection( $_intCatId ).' 
			WHERE user_id="'.$this->_userId.'" AND id='.Core_Sql::fixInjection( $_intSiteId ).' 
			LIMIT 1
		' );
		return true;
	}
}
?>