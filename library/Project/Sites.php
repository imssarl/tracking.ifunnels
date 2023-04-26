<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Sites
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 18.03.2010
 * @version 0.1
 */


 /**
 * система сайтов
 * сделать extands Core_Storage TODO!!! 17.09.2010
 *
 * @category Project
 * @package Project_Sites
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Sites {

	/**
	* типы сайтов, значения используются также в таблицах для указания типа
	* BF - это Project_Wpress хоть это и отдельный пакет но так как все типы 
	* сайтов часто используются вместе то константы должны быть одинаковыми и 
	* ссылатся на один источник. Возможно в будущем интегрируем Project_Wpress в Project_Sites
	* хотя это и под вопросом TODO!!! 21.05.2010
	* @var const
	*/
	const PSB=1, NCSB=2, NVSB=3, CNB=4, BF=5;

	/**
	* название таблицы где хранятся категории
	* @var const
	*/
	public static $category='category_blogfusion_tree';

	/**
	* типы сайтов, используются также как часть пути до шаблонов в Project_Sites_Templates
	* @var const
	*/
	public static $code=array(
		Project_Sites::PSB=>'psb',
		Project_Sites::NCSB=>'ncsb',
		Project_Sites::NVSB=>'nvsb',
		Project_Sites::CNB=>'cnb',
		Project_Sites::BF=>'bf',
	);

	/**
	* таблицы хранящие основные данные для каждого типа
	* @var const
	*/
	public static $tables=array(
		Project_Sites::PSB=>'es_psb',
		Project_Sites::NCSB=>'es_ncsb',
		Project_Sites::NVSB=>'es_nvsb',
		Project_Sites::CNB=>'es_cnb',
		Project_Sites::BF=>'bf_blogs',
	);

	public static $arrUrls=array();
	
	/**
	* объект Core_Data
	* @var object
	*/
	public $data;

	/**
	* объект с абстрактом Project_Sites_Type_Abstract
	* @var object
	*/
	protected $_driver;

	private $_userId=0;
	
	protected $_type=0;

	/**
	* конструктор
	* @return void
	*/
	public function __construct( $_type=0 ) {
		if ( !self::getUserId( $this->_userId ) ) {
			return;
		}
		$this->_type=$_type;
		$this->setDriver();
		$this->_driver->setUser( $this->_userId );
		$this->_withOrder=$this->_driver->getWithOrder();
	}

	protected function setDriver() {
		switch( $this->_type ) {
			case Project_Sites::PSB: $this->_driver=new Project_Sites_Type_Psb(); break;
			case Project_Sites::NCSB: $this->_driver=new Project_Sites_Type_Ncsb(); break;
			case Project_Sites::NVSB: $this->_driver=new Project_Sites_Type_Nvsb(); break;
			case Project_Sites::CNB: $this->_driver=new Project_Sites_Type_Cnb(); break;
			case Project_Sites::BF: $this->_driver=new Project_Sites_Type_Bf(); break;
			default: throw new Exception( Core_Errors::DEV.'|Project_Sites driver not found' ); return; break;
		}
		$this->_driver->setSiteCode( Project_Sites::$code[$this->_type] );
	}

	protected static function getUserId( &$_int ) {
		if ( !Zend_Registry::get( 'objUser' )->getId( $_int ) ) {
			throw new Exception( Core_Errors::DEV.'|Zend_Registry::get( \'objUser\' )->getId( $_int ) is not return an User Id' );
			return false;
		}
		return true;
	}

	public static function getLastUrls(){
		self::$arrUrls=array_merge(
			Project_Syndication_Sites_Blogfusion::getLastUrls(),
			Project_Sites_Type_Ncsb::getLastUrls(),
			Project_Sites_Type_Nvsb::getLastUrls(),
			Project_Sites_Type_Psb::getLastUrls(),
			Project_Sites_Type_Cnb::getLastUrls()
		);
		return self::$arrUrls;
	}

	public function urlLog($_arrSite){
		return $this->_driver->urlLog( $_arrSite );	
	}
	
	public function set() {
		return $this->_driver->set( $this );
	}	
	
	public function setFiles( $_arrFiles=array() ){
		return $this->_driver->setFiles( $_arrFiles );
	}
	
	public function import() {
		return $this->_driver->import( $this );
	}

	public function changeCategory( $_intSiteId=0, $_intCatId=0 ) {
		return $this->_driver->changeCategory( $_intSiteId, $_intCatId );
	}

	public function setData( $_arrData=array() ) {
		$this->data=new Core_Data( $_arrData );
		return $this;
	}

	public function getEntered( &$arrRes ) {
		$arrRes=$this->data->getFiltered();
		return $this;
	}

	public function getErrors( &$arrRes ) {
		$this->_driver->getErrors( $arrRes );
		return $this;
	}

	public function getSite( &$arrRes, $_intId=0 ) {
		if ( !$this->onlyOne()->withId( $_intId )->getList( $arrSite ) ) {
			return false;
		}
		return $this->_driver->get( $arrRes, $arrSite );
	}

	public function delSites( $mixId=array() ) {
		if ( empty( $mixId ) ) {
			return false;
		}
		$mixId=is_array( $mixId )? $mixId:array( $mixId );
		return $this->_driver->del( $mixId );
	}

	private $_onlyCount=false; // только количество
	private $_onlyOne=false; // только одна запись
	private $_onlyPortals=false; // только порталы, для CNB сайтов!
	private $_withId=array(); // c данными id
	protected $_withOrder; // c сортировкой, значение из драйвера
	private $_withPagging=array(); // постранично
	protected $_paging=array(); // инфа по навигации
	protected $_cashe=array(); // закэшированный фильтр
	protected $_withoutCategories=false; // без категорий

	// сброс настроек после выполнения getList
	protected function init() {
		$this->_onlyCount=false;
		$this->_withPagging=array();
		$this->_onlyOne=false;
		$this->_onlyPortals=false;
		$this->_withId=array();
		$this->_withOrder=$this->_driver->getWithOrder();
		$this->_withoutCategories=false;
	}

	public function onlyCount() {
		$this->_onlyCount=true;
		return $this;
	}
	
	public function onlyPortals() {
		$this->_onlyPortals=true;
		return $this;
	}

	public function withPaging( $_arr=array() ) {
		$this->_withPaging=$_arr;
		return $this;
	}

	public function onlyOne() {
		$this->_onlyOne=true;
		return $this;
	}

	public function withId( $_arrIds=array() ) {
		$this->_withId=$_arrIds;
		return $this;
	}

	public function withOrder( $_str='' ) {
		if ( !empty( $_str ) ) {
			$this->_withOrder=$_str;
		}
		$this->_cashe['order']=$this->_withOrder;
		return $this;
	}

	public function withoutCategories() {
		$this->_withoutCategories=true;
		return $this;
	}

	public function getList( &$mixRes ) {
		$_crawler=new Core_Sql_Qcrawler();
		$_crawler->set_select( 's.*, ls.template_id' );
		$_crawler->set_select( '(SELECT title FROM category_blogfusion_tree WHERE id=s.category_id) category' );
		$_crawler->set_from( $this->_driver->getTable(). ' s' );
		if ( !empty( $this->_userId ) ) {
			$_crawler->set_where( 's.user_id='.Core_Sql::fixInjection( $this->_userId ) );
		}
		if ( !empty( $this->_withId ) ) {
			$_crawler->set_where( 's.id IN('.Core_Sql::fixInjection( $this->_withId ).')' );
		}
		if ( !empty( $this->_withoutCategories ) ) {
			$_crawler->set_where( 's.category_id=0' );
		}
		if ( $this->_onlyPortals ){
			$_crawler->set_where( 's.flg_portal=1' );
		}
		$_crawler->set_from( 'LEFT JOIN es_template2site ls ON ls.site_id =s.id AND flg_type = '. $this->_type );
		$_crawler->set_order_sort( $this->_withOrder );
		if ( !empty( $this->_withPaging ) ) {
			$_crawler->set_paging( $this->_withPaging )->get_sql( $_strSql, $this->_paging );
		} elseif ( !$this->_onlyCount ) {
			$_crawler->get_result_full( $_strSql );
		}
		if ( $this->_onlyIds ) {
			$mixRes=Core_Sql::getField( $_strSql );
		} elseif ( $this->_onlyCount ) {
			$mixRes=Core_Sql::getCell( $_crawler->get_result_counter() );
		} elseif ( $this->_onlyOne ) {
			$mixRes=Core_Sql::getRecord( $_strSql );
		} else {
			$mixRes=Core_Sql::getAssoc( $_strSql );
		}
		$this->init();
		return !empty( $mixRes );
	}

	public function getFilter( &$arrRes ) {
		$arrRes=$this->_cashe;
		return $this;
	}

	public function getPaging( &$arrRes ) {
		$arrRes=$this->_paging;
		$this->_paging=array();
		return $this;
	}
}
?>