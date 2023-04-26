<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Wpress
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.03.2010
 * @version 1.0
 */


/**
 * Data management for module (user interface)
 *
 * @category Project
 * @package Project_Wpress
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Wpress {

	private $_errors=array();
	private $_data;
	private $_userId=0;
	private $_permalink=array(
		'Default - www.yourdomain.com/?p=123',
		'Numeric Archive - www.yourdomain.com/archives/123',
		'Day and Name:- www.yourdomain.com/2010/09/01/sample-post/',
		'Month and name:- www.yourdomain.com/2010/09/sample-post/',
		'Recommended for SEO:- www.yourdomain.com/category/post-name',
	);

	// статик нужен для Project_Wpress_Connector_Create::getInstallerCode и Project_Wpress_Connector_Import::putDatas
	public static $permalinkTypes=array(
		'',
		'/archives/%post_id%',
		'/%year%/%monthnum%/%day%/%postname%/',
		'/%year%/%monthnum%/%postname%/',
		'/%category%/%postname%/'
	);

	// статик нужен для Project_Wpress_Connector_Import::putDatas
	public static $fields=array( 
		'id', 'user_id', 'category_id', 'flg_type', 'flg_status', 'flg_settings', 'title', 'url', 
		'ftp_host', 'ftp_username', 'ftp_password', 'ftp_directory', 
		'db_host', 'db_name', 'db_username', 'db_password', 'db_tableprefix', 
		'dashboad_username', 'dashboad_password', 'flg_blogroll_links', 'flg_summary', 'flg_comment_status', 'flg_comment_moderated', 'flg_comment_notification', 
		'flg_ping_status', 'flg_ping_newpost', 'flg_permalink', 'post_perpage', 'post_per_rss', 'version', 'admin_email', 'blogtag_line', 'pingsite_list', 
		'catedit', 'edited', 'added'
	);

	// статик нужен для Project_Wpress_Connector_Import::prepareObject
	public static $table='bf_blogs';

	function __construct() {
		// чтобы использовать с произвольным пользователем надо зарегистрить свой объект вместо стандартного
		if ( !Zend_Registry::get( 'objUser' )->getId( $_int ) ) { // надо посмотреть небудет ли вызова данного класса системой (т.е.без конкретного пользователя)
			trigger_error( ERR_PHP.'|no _userId set' );
			return;
		}
		$this->_userId=$_int;
	}

	public static function wpVersion( &$_strLog ){
		set_time_limit(0);
		ignore_user_abort(true);
		$upgrade = new Project_Wpress_Connector_Upgrade();
		Project_Users_Fake::setCashe();
		$upgrade->runAsService();
		$upgrade->getLatest();
		$_strLog=ob_get_contents();
		Project_Users_Fake::retrieveFromCashe();
	}
	
	public function getPermalink() {
		return $this->_permalink;
	}

	public function getData() {
		return $this->_data->getFiltered();
	}

	public function getErrors() {
		return $this->_errors;
	}

	public function setData( $_arrData=array() ) {
		$this->_data=new Core_Data( $_arrData );
		return $this;
	}

	public function copyBlog( $_arrDest ) {
		// проверка обязательных полей формы
		if ( !$this->_data->setFilter( array( 'trim', 'clear' ) )->setChecker( array(
			'title'=>empty( $this->_data->filtered['title'] ),
			'url'=>empty( $this->_data->filtered['url'] ),
			'ftp_host'=>empty( $this->_data->filtered['ftp_host'] ),
			'ftp_username'=>empty( $this->_data->filtered['ftp_username'] ),
			'ftp_password'=>empty( $this->_data->filtered['ftp_password'] ),
			'ftp_directory'=>empty( $this->_data->filtered['ftp_directory'] ),
			'db_host'=>empty( $this->_data->filtered['db_host'] ),
			'db_name'=>empty( $this->_data->filtered['db_name'] ),
			'db_user'=>empty( $this->_data->filtered['db_username'] ),
			'db_password'=>empty( $this->_data->filtered['db_password'] ),
			'dashboad_username'=>empty( $this->_data->filtered['dashboad_username'] ),
			'dashboad_password'=>empty( $this->_data->filtered['dashboad_password'] ),
		) )->check() ) {
			$this->_data->getErrors( $this->_errors['filtered'] );
			return false; 
		}
		$_data=$this->_data;
		set_time_limit(0);
		ignore_user_abort(true);
		$_clone=new Project_Wpress_Connector_Clone( $this->_data );
		if (!$_clone->setDestination( $_arrDest )->setConfigCloner() ){ 
			$_clone->getErrors( $this->_errors['create'] );
			return false;
		}
		if (!$_clone->prepareServer() ){
			$_clone->getErrors( $this->_errors['create'] );
			return false;
		}
		if ( !$_clone->uploadMutator() ){
			$_clone->getErrors( $this->_errors['create'] );
			return false;
		}
		if ( !$_clone->startCloner() ){
			$_clone->getErrors( $this->_errors['create'] );
			return false;
		}
		$_arrBlog=array_merge($_data->setMask( self::$fields )->getValid(),$_arrDest);
		unset($_arrBlog['id']);
		$_data = new Core_Data( $_arrBlog );
		$_data->setFilter();
		Core_Sql::getInstance( true ); // восстанавливаем коннект с mysql - возможно поэтому в id потом нет данных - проверить TODO!!!
		$_data->setElement( 'id', Core_Sql::setInsertUpdate( self::$table, $_data->setMask( self::$fields )->getValid() ));
		// импортируем контент
		$_import=new Project_Wpress_Connector_Import( $_data );
		if ( !$_import->setParts( array( 'pages', 'posts', 'cats', 'opt' ) )->putImporter() ) {
			$_import->getErrors( $this->_errors['import'] );
			return false;
		}
		if ( !$_import->start() ) {
			$_import->getErrors( $this->_errors['import'] );
			return false;
		}
		return true;
	}
	
	public function setBlog() {
		// проверка обязательных полей формы
		if ( !$this->_data->setFilter( array( 'trim', 'clear' ) )->setChecker( array(
			'title'=>empty( $this->_data->filtered['title'] ),
			'url'=>empty( $this->_data->filtered['url'] ),
			'ftp_host'=>empty( $this->_data->filtered['ftp_host'] ),
			'ftp_username'=>empty( $this->_data->filtered['ftp_username'] ),
			'ftp_password'=>empty( $this->_data->filtered['ftp_password'] ),
			'ftp_directory'=>empty( $this->_data->filtered['ftp_directory'] ),
			'db_host'=>empty( $this->_data->filtered['db_host'] ),
			'db_name'=>empty( $this->_data->filtered['db_name'] ),
			'db_user'=>empty( $this->_data->filtered['db_username'] ),
			'db_password'=>empty( $this->_data->filtered['db_password'] ),
			'dashboad_username'=>empty( $this->_data->filtered['dashboad_username'] ),
			'dashboad_password'=>empty( $this->_data->filtered['dashboad_password'] ),
		) )->check() ) {
			$this->_data->getErrors( $this->_errors['filtered'] );
			return false; 
		}
		$_boolImport=false;
		if ( empty( $this->_data->filtered['id'] ) ) {
			$this->_data->setElements( array(
				'user_id'=>$this->_userId,
				'added'=>time(),
			) );
			$_boolImport=true;
		}
		$this->_data->setElements( array(
			'flg_settings'=>( empty( $this->_data->filtered['flg_settings'] )? 0:1 ),
			'edited'=>time(),
		) );
		if ( !$this->set() ) {
			return false;
		}
		// если создание прошло успешно сохраняем данные на локальном сервере
		Core_Sql::getInstance( true ); // восстанавливаем коннект с mysql - возможно поэтому в id потом нет данных - проверить TODO!!!
		$this->_data->setElement( 'id', Core_Sql::setInsertUpdate( self::$table, $this->_data->setMask( self::$fields )->getValid() ) );
		// проверить отлинкованы ли старые TODO!!!
		// линкуем тему
		$themes=new Project_Wpress_Theme();
		if ( !$themes->blogLink( $this->_data->filtered['id'], $this->_data->filtered['theme_id'] ) ) {
			return false;
		}
		// линкуем плагины
		$plugins=new Project_Wpress_Plugins();
		if ( !$plugins->blogLink( $this->_data->filtered['id'], $this->_data->filtered['plugins'] ) ) {
			return false;
		}
		// импортируем контент только при создании блога
		if ( $_boolImport ) {
			$_import=new Project_Wpress_Connector_Import( $this->_data );
			if ( !$_import->setParts( array( 'pages', 'posts', 'cats', 'opt' ) )->putImporter() ) {
				$_import->getErrors( $this->_errors['import'] );
				return false;
			}
			if ( !$_import->start() ) {
				$_import->getErrors( $this->_errors['import'] );
				return false;
			}
			Project_Wpress_Notification::createWP( $this->_data );// Письмо пользователю при создании блога.
		}
		Project_Syndication_Sites::setOutside( $this->_data->filtered['id'], Project_Sites::BF, empty( $this->_data->filtered['syndication'] ) ); // Syndication
		return true;
	}

	private function set() {
		set_time_limit(0);
		ignore_user_abort(true);
		$_create=new Project_Wpress_Connector_Create( $this->_data );
		if (!$_create->prepareServer() ){
			$_create->getErrors( $this->_errors['create'] );
			return false;
		}
		if (!$_create->generateMutator() ){ 
			$_create->getErrors( $this->_errors['create'] );
			return false;
		}
		if ( empty( $this->_data->filtered['id'] ) ) {
			if ( !$_create->uploadWordpress() ){
				$_create->getErrors( $this->_errors['create'] );
				return false;
			}
		}
		if ( !$_create->uploadMutator() ){
			$_create->getErrors( $this->_errors['create'] );
			return false;
		}
		if( !$_create->installBlog() ){
			$_create->getErrors( $this->_errors['create'] );
			return false;
		}
		return true;
	}

	public function import() {
		// проверка обязательных полей формы
		if ( !$this->_data->setFilter( array( 'strip_tags', 'trim', 'clear' ) )->setChecker( array(
			'title'=>empty( $this->_data->filtered['title'] ),
			'url'=>empty( $this->_data->filtered['url'] ),
			'ftp_host'=>empty( $this->_data->filtered['ftp_host'] ),
			'ftp_username'=>empty( $this->_data->filtered['ftp_username'] ),
			'ftp_password'=>empty( $this->_data->filtered['ftp_password'] ),
			'ftp_directory'=>empty( $this->_data->filtered['ftp_directory'] ),
			'db_host'=>empty( $this->_data->filtered['db_host'] ),
			'db_name'=>empty( $this->_data->filtered['db_name'] ),
			'db_user'=>empty( $this->_data->filtered['db_username'] ),
			'db_password'=>empty( $this->_data->filtered['db_password'] ),
			'dashboad_username'=>empty( $this->_data->filtered['dashboad_username'] ),
			'dashboad_password'=>empty( $this->_data->filtered['dashboad_password'] ),
		) )->check() ) {
			$this->_data->getErrors( $this->_errors['filtered'] );
			return false; 
		}
		$this->_data->setElements( array(
			'user_id'=>$this->_userId,
			'flg_type'=>1,
			'flg_status'=>1,
			'added'=>time(),
		) );
		$this->_data->setElement( 'id', Core_Sql::setInsertUpdate( self::$table, $this->_data->setMask( self::$fields )->getValid() ) );
		// запускаем получение данных (позже это будет в отдельном скрипте через system(script.php?id=x)) TODO!!! 24.03.2010
		$_import=Project_Wpress_Connector_Import::prepareObject( $this->_data->filtered['id'] );
		if ( $_import===false ) {
			return false;
		}
		if ( !$_import->setParts()->putImporter() ) {
			$_import->getErrors( $this->_errors['import'] );
			return false;
		}
		if ( !$_import->start() ) {
			$_import->getErrors( $this->_errors['import'] );
			return false;
		}
		Project_Syndication_Sites::setOutside( $this->_data->filtered['id'], Project_Sites::BF, empty( $this->_data->filtered['syndication'] ) ); // Syndication
		return true;
	}
	
	public function setTheme() {
		set_time_limit(0);
		ignore_user_abort(true);
		$this->_data->setFilter();
		$_create=new Project_Wpress_Connector_Create( $this->_data );
	 	if ( !$_create->prepareServer() ) {
			$_create->getErrors( $this->_errors['changeTheme'] );
			return false;
		}
		$this->_data->filtered['theme_id']=$this->_data->filtered['theme'][0];
		if (!$_create->generateMutator()) {
			$_create->setError($this->_errors['changeTheme']);
			return false;
		}
		if (!$_create->uploadMutator()){
			$_create->setError($this->_errors['changeTheme']);
			return false;
		}
		if (!$_create->installBlog()){
			$_create->setError($this->_errors['changeTheme']);
			return false;
		}
		return true;
	}

	public  function getBlog( &$arrRes, $_intId=0 ) {
		if ( empty( $_intId ) ) {
			return false;
		}
		if ( !$this->onlyOne()->withIds( $_intId )->getList( $arrRes ) ) {
			return false;
		}
		$arrRes['syndication']=Project_Syndication_Sites::isSyndicated( $arrRes['id'], Project_Sites::BF ); // syndication
		$_plugin=new Project_Wpress_Plugins();
		$_plugin->onlyIds()->onlySiteId( $_intId )->getList( $arrRes['plugins'] );
		$_themes=new Project_Wpress_Theme();
		$_themes->onlyIds()->onlySiteId( $_intId )->getList( $arrRes['theme'] );
		return true;
	}

	/**
	 * отдельно смена категории блога
	 *
	 * @param integer $_intBlogId
	 * @param integer $_intCatId id новой категории
	 * @return bool
	 */
	public function changeCategory( $_intBlogId=0, $_intCatId=0 ) {
		if ( empty( $_intBlogId )||empty( $_intCatId ) ) {
			return false;
		}
		Core_Sql::setExec( '
			UPDATE bf_blogs SET category_id='.Core_Sql::fixInjection( $_intCatId ).' 
			WHERE user_id="'.$this->_userId.'" AND id='.Core_Sql::fixInjection( $_intBlogId ).' 
			LIMIT 1
		' );
		return true;
	}

	/**
	 * список блогов для подстановки настроек в форму создания блога
	 *
	 * @param array $arrRes
	 * @return bool
	 */
	public function getSettingsBlog( &$arrRes ) {
		if ( !$this->onlySettings()->getList( $arrRes ) ) {
			return false;
		}
		$_plugin=new Project_Wpress_Plugins();
		$_themes=new Project_Wpress_Theme();
		foreach( $arrRes as $k=>$v ) {
			$_plugin->onlyIds()->onlySiteId( $v['id'] )->getList( $arrRes[$k]['plugins'] );
			$_themes->onlyIds()->onlySiteId( $v['id'] )->getList( $arrRes[$k]['theme'] );
		}
		return true;
	}

	/**
	 * обновить флаг flg_settings
	 *
	 * @param mixed $_mixIdsBlogs int или array блогов среди которых производим операцию
	 * @param mixed $_mixIdsStore int или array блогов которым нужен flg_settings=1
	 * @return bool
	 */
	public function setSettingsBlog( $_mixIdsBlogs=0, $_mixIdsStore=0 ) {
		if ( empty( $_mixIdsBlogs ) ) {
			return false;
		}
		Core_Sql::setExec( 'UPDATE bf_blogs SET flg_settings=0 WHERE id IN ('.Core_Sql::fixInjection( $_mixIdsBlogs ).')' );
		if ( !empty( $_mixIdsStore ) ) {
			Core_Sql::setExec( 'UPDATE bf_blogs SET flg_settings=1 WHERE id IN ('.Core_Sql::fixInjection( $_mixIdsStore ).')' );
		}
		return true;
	}

	/**
	 * удаление блогов, пока только из БД
	 *
	 * @param mixed $_mixIds int или array блогов которые требуется удалить
	 * @return bool
	 */
	public function deleteBlog( $_mixIds=0 ){
		if ( empty( $_mixIds ) ) {
			return false;
		}
		if ( !$this->onlyIds()->withIds( $_mixIds )->getList( $_mixIds ) ) { // прверяем владельца блогов
			return false;
		}
		Project_Syndication_Sites::setOutside( $_mixIds, Project_Sites::BF ); // syndication
		$_plugins=new Project_Wpress_Plugins();
		$_plugins->blogLink( $_mixIds );
		$_plugins=new Project_Wpress_Theme();
		$_plugins->blogLink( $_mixIds );
		Core_Sql::setExec( '
			DELETE b, ecat, epg, ep, epc, ec
			FROM bf_blogs b
			LEFT JOIN bf_ext_category ecat ON ecat.blog_id=b.id
			LEFT JOIN bf_ext_pages epg ON epg.blog_id=b.id
			LEFT JOIN bf_ext_posts ep ON ep.blog_id=b.id
			LEFT JOIN bf_ext_post2cat epc ON epc.blog_id=b.id
			LEFT JOIN bf_ext_comments ec ON ec.blog_id=b.id
			WHERE b.id IN('.Core_Sql::fixInjection( $_mixIds ).')
		' );
		return true;
	}

	// настройки для getList
	private $_onlyIds=false; // массив с ids
	private $_onlyCount=false; // только количество
	private $_onlyOne=false; // только одна запись
	private $_onlySettings=false; // только блоги с образцами настроек
	private $_toVersion=''; // блоги версия которых меньше данной версии или версия не указана
	private $_toSelect=false; // для вывода данных в селект
	private $_toJs=false; // для вывода данных в селект
	private $_withIds=0; // c данными id
	private $_withCategories=0; // c данными категориями
	private $_withoutCategories=false; // без категорий
	private $_withTitle=false; // поиск по title
	private $_withPagging=array(); // постранично
	private $_withOrder='b.id--up'; // c сортировкой
	private $_paging=array(); // инфа по навигации
	private $_cashe=array(); // закэшированный фильтр

	// сброс настроек после выполнения getArticles
	private function init() {
		$this->_onlyIds=false;
		$this->_onlyCount=false;
		$this->_onlyOne=false;
		$this->_onlySettings=false;
		$this->_toVersion='';
		$this->_toSelect=false;
		$this->_toJs=false;
		$this->_withIds=0;
		$this->_withCategories=0;
		$this->_withTitle=false;
		$this->_withoutCategories=false;
		$this->_withPagging=array();
		$this->_withOrder='b.id--up';
	}

	public function toVersion( $version ) {
		$this->_toVersion=$version;
		return $this;
	}	
	
	public function onlyIds() {
		$this->_onlyIds=true;
		return $this;
	}

	public function onlyCount() {
		$this->_onlyCount=true;
		return $this;
	}

	public function onlyOne() {
		$this->_onlyOne=true;
		return $this;
	}

	public function onlySettings() {
		$this->_onlySettings=true;
		return $this;
	}

	// array or int
	public function withCategories( $_mixId=0 ) {
		$this->_withCategories=$_mixId;
		return $this;
	}	
	public function withTitle( $_strTitle='' ) {
		$this->_withTitle=$_strTitle;
		return $this;
	}

	public function withoutCategories() {
		$this->_withoutCategories=true;
		return $this;
	}

	public function toSelect() {
		$this->_toSelect=true;
		return $this;
	}
	
	public function toJs() {
		$this->_toJs=true;
		return $this;
	}
	
	// array or int
	public function withIds( $_mixId=0 ) {
		$this->_withIds=$_mixId;
		return $this;
	}

	public function withPagging( $_arr=array() ) {
		$this->_withPagging=$_arr;
		return $this;
	}

	public function withOrder( $_str='' ) {
		if ( !empty( $_str ) ) {
			$this->_withOrder=$_str;
		}
		$this->_cashe['order']=$this->_withOrder;
		return $this;
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
	
	private function toJson( &$arrList ){
		if ( empty( $arrList ) ) {
			return false;
		}
		$_category=new Project_Wpress_Content_Category();
		$_category->setBlogById( $blog['id'] );
		$_category->getAll()->getList( $arrCategories );
		foreach( $arrList as &$blog ) {
			$blog['title']=addslashes( $blog['title'] );
			foreach ( $arrCategories as &$item ) {
				if ( $item['blog_id']==$blog['id'] ) {
					$item['title']=addslashes( $item['title'] );
					$blog['categories'][]=$item;
				}
			}
		}
		return true;
	}
	
	public function getList( &$mixRes ) {
		$_crawler=new Core_Sql_Qcrawler();
		if ( $this->_onlyIds ) {
			$_crawler->set_select( 'b.id' );
		} elseif ( $this->_toSelect ) {
			$_crawler->set_select( 'b.id, b.title' );
		} elseif ( $this->_toJs ) {
			$_crawler->set_select( 'b.id, b.title, b.category_id' );
		} else {
			$_crawler->set_select( 'b.*' );
			$_crawler->set_select( '(SELECT title FROM category_blogfusion_tree WHERE id=b.category_id) category' );
		}
		$_crawler->set_from( self::$table.' b' );
		if ( !empty( $this->_userId ) ) {
			$_crawler->set_where( 'b.user_id='.Core_Sql::fixInjection( $this->_userId ) );
		}
		if ( $this->_onlySettings ) {
			$_crawler->set_where( 'b.flg_settings=1' );
		}
		if ( !empty( $this->_toVersion ) ) {
			$_crawler->set_where( 'b.version="" OR b.version<'.Core_Sql::fixInjection( $this->_toVersion ) );
		}
		if ( !empty( $this->_withTitle ) ) {
			$_crawler->set_where( 'b.title LIKE' .Core_Sql::fixInjection( '%'.$this->_withTitle.'%' )  );
		}
		if ( !empty( $this->_withIds ) ) {
			$_crawler->set_where( 'b.id IN ('.Core_Sql::fixInjection( $this->_withIds ).')' );
		}
		// т.к.у нас только 2 уровня категорий поэтом ищем в указанной категории и во всех ниже лежащих (для которых она является родительской)
		if ( !empty( $this->_withCategories ) ) {
			$_crawler->set_where( 'b.category_id IN ('.Core_Sql::fixInjection( $this->_withCategories ).') OR b.category_id IN (SELECT id FROM category_blogfusion_tree WHERE pid IN ('.Core_Sql::fixInjection( $this->_withCategories ).'))' );
		}
		if ( !empty( $this->_withoutCategories ) ) {
			$_crawler->set_where( 'b.category_id=0' );
		}
		$_crawler->set_order_sort( $this->_withOrder );
		if ( !empty( $this->_withPagging ) ) {
			$this->_withPagging['rowtotal']=Core_Sql::getCell( $_crawler->get_result_counter( $_strTmp ) );
			$_crawler->set_paging( $this->_withPagging )->get_sql( $_strSql, $this->_paging );
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
		if ( $this->_toJs ) {
			$this->toJson( $mixRes );
		}
		$this->init();
		return !empty( $mixRes );
	}
}
?>