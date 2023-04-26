<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Content_Monetized_Clickbank
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2009-2011, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 10.03.2011
 * @version 1.0
 */


/**
 * Clickbank контент функционал
 *
 * @category Project
 * @package Project_Content_Monetized_Clickbank
 * @copyright Copyright (c) 2009-2011, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Content_Adapter_Clickbank extends Core_Storage implements Project_Content_Interface {
	
	public $fields=array( 'id', 'category_id', 'flg_network', 'flg_language', 'vendor_name', 'vendor_id', 'title', 'long_desctiption', 'short_description','video_url','url','smallthumb','largethumb',
	'file0','file1','file2','file3','file4','file5','file6','file7','file8','file9','type0','type1','type2','type3','type4','type5','type6','type7','type8','type9','added','edited' );
	public $table='content_clickbank';
	/**
	 * Banners type
	 * @var array
	 */
	public static $bannerType=array(
			array('width'=>'300','height'=>'250','title'=>'300x250 IMU - (Medium Rectangle)'),
			array('width'=>'250','height'=>'250','title'=>'250x250 IMU - (Square Pop-Up)'),
			array('width'=>'240','height'=>'400','title'=>'240x400 IMU - (Vertical Rectangle)'),
			array('width'=>'336','height'=>'280','title'=>'336x280 IMU - (Large Rectangle)'),
			array('width'=>'180','height'=>'150','title'=>'180x150 IMU - (Rectangle) '),
			array('width'=>'300','height'=>'100','title'=>'300x100 IMU - (3:1 Rectangle)'),
			array('width'=>'720','height'=>'300','title'=>'720x300 IMU – (Pop-Under)'),
			array('width'=>'468','height'=>'60','title'=>'468x60 IMU - (Full Banner)'),
			array('width'=>'234','height'=>'60','title'=>'234x60 IMU - (Half Banner)'),
			array('width'=>'88', 'height'=>'31','title'=>'88x31 IMU - (Micro Bar)'),
			array('width'=>'120','height'=>'90','title'=>'120x90 IMU - (Button 1)'),
			array('width'=>'120','height'=>'60','title'=>'120x60 IMU - (Button 2)'),
			array('width'=>'120','height'=>'240','title'=>'120x240 IMU - (Vertical Banner)'),
			array('width'=>'125','height'=>'125','title'=>'125x125 IMU - (Square Button)'),
			array('width'=>'728','height'=>'90','title'=>'728x90 IMU - (Leaderboard)'),
			array('width'=>'160','height'=>'600','title'=>'160x600 IMU - (Wide Skyscraper)'),
			array('width'=>'120','height'=>'600','title'=>'120x600 IMU - (Skyscraper)'),
			array('width'=>'300','height'=>'600','title'=>'300x600 IMU - (Half Page Ad)')
	);
	/**
	 * Banners from user
	 * @var bool
	 */
	private $_files=false;
	
	/**
	 * Content settings
	 * @var array
	 */
	protected $_settings=array();
	protected $_counter=false;
	protected $_limit=false;
	protected $_withTags=false; // поиск по тегам
	protected $_withLanguage=false; // только один язык
	private $_tags=array('body'=>'{body}');

	public function __construct() {}	
	
	public static function getInstance() {}
	
	/**
	 * Set files
	 *
	 * @param array $_files
	 * @return object
	 */
	public function setFile( $_files=array() ){
		if (empty($_files)){
			return $this;
		}
		$this->_files=$_files;
		return $this;
	}
	/**
	 * Create and save item
	 *
	 * @return bool
	 */
	public function set(){
		if ( !$this->_data->setFilter( array( 'trim', 'clear' ) )->setChecker( array(
			'short_description'=>empty( $this->_data->filtered['short_description'] ),
			'long_desctiption'=>empty( $this->_data->filtered['long_desctiption'] ),
			'url'=>empty( $this->_data->filtered['url'] ),
			'flg_network'=>empty( $this->_data->filtered['flg_network'] ),
			'category_id'=>empty( $this->_data->filtered['category_id'] ),
			'title'=>empty( $this->_data->filtered['title'] ),
		) )->check() ) {
			$this->_data->getErrors( $this->_errors );
			return false;
		}
		$this->uploadFiles();
		if ( empty($this->_data->filtered['id']) ){
			$this->_data->setElement( 'added', time() );
			// сохраняем тэги только если нет id
			$tags=new Core_Tags('clickbank');
		}
		$this->_data->setElement( 'edited', time() );
		$this->_data->setElement( 'id', Core_Sql::setInsertUpdate( $this->table, $this->_data->setMask( $this->fields )->getValid() ) );
		if( is_object($tags) && !empty($this->_data->filtered['tags']) ){
			if( !$tags->setItem( $this->_data->filtered['id'] )->setTags( $this->_data->filtered['tags'] )->set() ){
				return $this->setError('can\'t add tags');
			}
		}
		return true;
	}
	
	public function getOwnerId(){}
	
	/**
	 * Delete item
	 *
	 * @param int $intId
	 * @return abool
	 */
	public function del( $_arr=array() ){
		if (empty($_arr)){
			return false;
		}
		$this->_link=false;
		$this->withIds($_arr)->onlyOne()->getContent( $arrItem );
		if ( !parent::del($_arr) ){
			return false;
		}
		$_strDir=Zend_Registry::get( 'config' )->path->absolute->user_files.'clickbank/';
		for ($i=0; $i<10; $i++){
			if ( is_file( $_strDir.$arrItem['file'.$i] ) ){
				unlink( $_strDir.$arrItem['file'.$i] );
			}
		}
		if ( is_file($_strDir.$arrItem['smallthumb']) ){
			unlink($_strDir.$arrItem['smallthumb']);
		}
		if ( is_file($_strDir.$arrItem['largethumb']) ){
			unlink($_strDir.$arrItem['largethumb']);
		}
		return true;
	}
	
	/**
	 * Upload and resize banners and thumb
	 *
	 * @return bool
	 */
	private function uploadFiles(){
		$media=new Core_Media_Driver();
		$_strDir=Zend_Registry::get( 'config' )->path->absolute->user_files.'clickbank/';
		if (!empty($this->_data->filtered['smallthumb_delete'])){
			unlink($_strDir.$this->_data->filtered['smallthumb']);
			$this->_data->filtered['smallthumb']='';
		}
		if (!empty($this->_data->filtered['largethumb_delete'])){
			unlink($_strDir.$this->_data->filtered['largethumb']);
			$this->_data->filtered['largethumb']='';
		}
		if ( !empty( $this->_files['smallthumb']['tmp_name'] ) ){
			if (is_file($_strDir.$this->_data->filtered['smallthumb'])){
				unlink($_strDir.$this->_data->filtered['smallthumb']);
			}			
			$media->setSize( array( 'width'=>100, 'height'=>100 ), $this->_files['smallthumb']['tmp_name'] );
			$filename=uniqid('smallthumb_').'.'.Core_Files::getExtension( $this->_files['smallthumb']['name'] );
			if ( $media->d_gd_morf( $this->_files['smallthumb']['tmp_name'], $_strDir.$filename ) ){
				$this->_data->filtered['smallthumb']=$filename;
			}
		}
		if ( !empty( $this->_files['largethumb']['tmp_name'] ) ){
			if (is_file($_strDir.$this->_data->filtered['smallthumb'])){
				unlink($_strDir.$this->_data->filtered['largethumb']);
			}
			$media->setSize( array( 'width'=>350, 'height'=>350 ), $this->_files['largethumb']['tmp_name'] );
			$filename=uniqid('largethumb_').'.'.Core_Files::getExtension( $this->_files['largethumb']['name'] );
			if ( $media->d_gd_morf( $this->_files['largethumb']['tmp_name'], $_strDir.$filename ) ){
				$this->_data->filtered['largethumb']=$filename;
			}
		}
		foreach( $this->_data->filtered['banners'] as $key=>$index_banner ){
			if (!empty($this->_data->filtered['banner_delete'][$key])){
				unlink($_strDir.$this->_data->filtered['banner_file'][$key]);
				$this->_data->filtered['type'.$key]='';
				$this->_data->filtered['file'.$key]='';
				continue;
			}			
			if (empty($this->_files['banners']['tmp_name'][$key])){
				continue;
			}
			if( is_file($_strDir.$this->_data->filtered['banner_file'][$key])  ){
				unlink($_strDir.$this->_data->filtered['banner_file'][$key]);
			}
			$media->setSize( self::$bannerType[$index_banner], $this->_files['banners']['tmp_name'][$key] );
			$filename=uniqid( 'banner_'. $key .'_' ).
			'_'.self::$bannerType[$index_banner]['width'].'x'.self::$bannerType[$index_banner]['height'].'.'.Core_Files::getExtension($this->_files['banners']['name'][$key]);
			$media->d_gd_morf($this->_files['banners']['tmp_name'][$key],$_strDir.$filename);
			$this->_data->filtered['type'.$key]=$index_banner;
			$this->_data->filtered['file'.$key]=$filename;
		}
		return true;
	}
	
	protected $_withCategories=false;
	
	public function withCategories( $_arr=array() ) {
		$this->_withCategories=$_arr;
		return $this;
	}	

	public function setFilter( $_arrFilter=array() ){
		$this->_settings=$_arrFilter;
		$this
			->withLanguage( $_arrFilter['flg_language'])
			->withTags( $_arrFilter['tags'] )
			->withCategories($_arrFilter['category_id']);
		return $this;
	}

	public function withLanguage( $_intLang ){
		$this->_withLanguage=$_intLang;
		return $this;
	}

	public function setCounter( $_intCounter ){
		$this->_counter=$_intCounter;
		return $this;
	}

	public function setLimited( $_intLimited ){
		$this->_limit=$_intLimited;
		return $this;
	}

	public function getFilter( &$arrRes ){
		$arrRes=$this->_settings;
		return !empty($arrRes);
	}

	public function withTags( $_str ){
		if( empty($_str) ){
			return $this;
		}
		$this->_withTags=$_str;
		return $this;
	}

	protected function init(){
		parent::init();
		$this->_withTags=false;
		$this->_withCategories=false;
	}
	
	protected function assemblyQuery() {
		if ( $this->_onlyIds ) {
			$this->_crawler->set_select( 'd.id' );
		} elseif ( $this->_toSelect ) {
			$this->_crawler->set_select( 'd.id, d.title' );
		} else {
			$this->_crawler->set_select( 'd.*' );
		}
		$this->_crawler->set_from( $this->table.' d' );
		if ( !empty( $this->_withIds ) ) {
			$this->_crawler->set_where( 'd.id IN ('.Core_Sql::fixInjection( $this->_withIds ).')' );
		}
		if ( !empty( $this->_withTags ) ) {
			$tags=new Core_Tags('clickbank');
			$tags->setTags( $this->_withTags )->getSearchQuery( $_strSql );
			$this->_crawler->set_where( 'd.id IN ('.$_strSql.')' );
		}
		if( $this->_withAffiliate ){
			$this->_crawler->set_where( 'd.id IN ('.$_strSql.')' );
		}
		if ( $this->_onlyOwner ) {
			$this->_crawler->set_where( 'd.user_id='.$this->getOwnerId() );
		}
		if ( $this->_withLanguage ) {
			$this->_crawler->set_where( 'd.flg_language='.Core_Sql::fixInjection( $this->_withLanguage ) );
		}
		if ( !( $this->_onlyOne||$this->_onlyCell ) ) {
			$this->_crawler->set_order_sort( $this->_withOrder );
		}
		if ( !empty( $this->_withCategories ) ) {
			$this->_crawler->set_where( 'd.category_id IN ('.Core_Sql::fixInjection( $this->_withCategories ).')' );
		}
	}

	/**
	 * Set settings to content
	 *
	 * @param  $arrSettings
	 * @return bool|Project_Content_Adapter_Clickbank
	 */
	public function setSettings( $arrSettings ){
		if( !empty($arrSettings) ){
			return false;
		}
		$this->_settings=$arrSettings;
		return $this;
	}

	/**
	 * Get content from DataBase
	 *
	 * @param array $mixRes
	 */
	public function getList( &$mixRes ){
		$_onlyOne=$this->_onlyOne;
		parent::getList( $mixRes );
		if ( $_onlyOne ){
			$this->addPath($mixRes);
		}
		if( !empty( $this->_settings['template'] ) ){
			$mixRes['body']=$mixRes['short_description'];
			$this->prepareBody( $mixRes );
		}
		return $this;
	}

	private function prepareBody( &$mixRes ){
		foreach( $mixRes as &$_item ){
			if( !is_array($_item) ){
				return;
			}
			$_tmpTemplate=$this->_settings['template'];
			$_replace=array_intersect_key( $_item, $this->_tags );
			str_replace( $this->_tags, $_replace, $_tmpTemplate );
			$_item['body']=$_tmpTemplate;
		}
	}	

	/**
	 * Add path for preview to banners and thumb 
	 *
	 * @param array $mixRes
	 */
	private function addPath( &$mixRes ){
		$_strDir=Zend_Registry::get( 'config' )->path->html->user_files.'clickbank/';
		for ($i=0;$i<=count(self::$bannerType); $i++){
			if ( !empty($mixRes['file'.$i]) ){
				$mixRes['preview'.$i]=$_strDir.$mixRes['file'.$i];
			}
		}
		if( !empty($mixRes['smallthumb']) ){
			$mixRes['smallthumb_preview']=$_strDir.$mixRes['smallthumb'];
		}
		if( !empty($mixRes['largethumb']) ){
			$mixRes['largethumb_preview']=$_strDir.$mixRes['largethumb'];
		}		
	}
}
?>