<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Content
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2009-2011, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 11.03.2011
 * @version 1.0
 */


/**
 * Управление настройками контента
 *
 * @category Project
 * @package Project_Content_Settings
 * @copyright Copyright (c) 2009-2011, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Content_Settings extends Core_Storage {
	
	public $table='content_setting';
	public $fields=array('id','user_id','flg_source','settings');

	public function __construct() {
		if ( !Zend_Registry::get( 'objUser' )->getId( $_int ) ) {
			throw new Exception( Core_Errors::DEV.'|Zend_Registry::get( \'objUser\' )->getId( $_int ) is not return an User Id' );
			return;
		}
		$this->_userId=$_int;		
	}
	
	/**
	 * Переопределение родительского метода
	 *
	 */
	public static function getInstance() {}
	
	/**
	 * Вернуть текущего пользователя
	 *
	 */
	public function getOwnerId() {
		return $this->_userId;
	}
	
	/**
	 * Сохраняет свойства источников в базе
	 * формат данных:
	 * array(
	 * 	'label'=>array('settings'=>array(),['id'=>int] ),
	 * )
	 * 
	 * @return bool
	 */
	public function set() {
		$this->_data->setFilter( array( 'trim', 'clear' ) );
		foreach( $this->_data->filtered as $v ) {
			if ( empty( $v['settings'] ) ) {
				continue;
			}
			$_data=array(
				'user_id'=>$this->getOwnerId(),
				'flg_source'=>$v['flg_source'],
				'settings'=>serialize( $v['settings'] )
			);
			if ( !empty( $v['id'] ) ){
				$_data['id']=$v['id'];
			}
			if ( !Core_Sql::setInsertUpdate( $this->table, $_data ) ){
				return false;
			}
		}
		return true;
	}
	/**
	 * Только один источник
	 *
	 * @var int
	 */
	private $_onlySource=false;
	
	public function onlySource( $intId ){
		if (empty($intId)){
			return false;
		}
		$this->_onlySource=$intId;
		return $this;
	}
	
	protected function init(){
		parent::init();
		$this->_onlySource=false;
	}
	
	protected function assemblyQuery() {
		$this->_keyRecordForm=true;
		$this->_crawler->set_select( 'd.flg_source flg, d.*' );
		$this->_crawler->set_from( $this->table.' d' );
		if ( !empty( $this->_withIds ) ) {
			$this->_crawler->set_where( 'd.id IN ('.Core_Sql::fixInjection( $this->_withIds ).')' );
		}
		if ( $this->_onlyOwner ) {
			$this->_crawler->set_where( 'd.user_id='.$this->getOwnerId() );
		}
		if ( !( $this->_onlyOne||$this->_onlyCell ) ) {
			$this->_crawler->set_order_sort( $this->_withOrder );
		}
		if ( !empty( $this->_onlySource ) ) {
			$this->_crawler->set_where( 'd.flg_source = '.Core_Sql::fixInjection( $this->_onlySource ) );
		}		
	}	
	
	/**
	 * Список свойств
	 *
	 */
	public function getContent( &$mixRes ) {
		$this->onlyOwner();
		parent::getList( $mixRes );
		return $this->prepare( $mixRes );
	}
	
	/**
	 * Подготовить данные для шаблона
	 *
	 * @param array $mixRes
	 * @return bool
	 */
	private function prepare( &$mixRes ) {
		if ( empty( $mixRes ) ){
			return false;
		}
		if ( isSet( $mixRes['settings'] ) ) {
			if ( empty( $mixRes['settings'] ) ) {
				return false;
			}
			$mixRes['settings']=unserialize($mixRes['settings']);
			return true;
		}
		foreach( $mixRes as $k=>$v ) {
			$mixRes[$k]['settings']=unserialize( $v['settings'] );
		}
		return true;
	}
}
?>