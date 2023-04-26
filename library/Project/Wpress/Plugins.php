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
 * Plugins management
 *
 * @category Project
 * @package Project_Wpress
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Wpress_Plugins {

	private $_userId=0;

	// Errors code:
	// 011 - Uploaded file size is more than 3MB.Please upload below 3MB.
	// 012 - Invalid file.Please upload only zip file.
	// 013 - Invalid zip file.
	// 014 - Invalid Plugin.
	// 002 - This plugin is already exist
	private $_error=0;

	// папки и файлы
	private $_maxArchiveSize=3145728; // максимально разрешённый размер архива
	private $_commonDir =''; // общие плагины
	private $_userDir=''; // плагины пользовтеля
	private $_userTmpDir=''; // временная папка пользовтеля

	// таблицы
	private $_tablePlugins='bf_plugins';
	private $_tableLinkToUser='bf_plugin2user_link';
	private $_tableLinkToBlog='bf_plugin2blog_link';
	private $_fields=array( 'id', 'flg_type', 'filename', 'wp_path', 'title', 'url', 'version', 'author', 'author_url', 'description', 'added' );

	public function __construct() {
		// чтобы использовать с произвольным пользователем надо зарегистрить свой объект вместо стандартного
		if ( !Zend_Registry::get( 'objUser' )->getId( $_int ) ) { // надо посмотреть небудет ли вызова данного класса системой (т.е. без конкретного пользователя)
			trigger_error( ERR_PHP.'|no _userId set' );
			return;
		}
		$this->_userId=$_int;
		$this->initPaths();
	}

	private function initPaths() {
		$_strDir='blogfusion'.DIRECTORY_SEPARATOR.'plugins';
		$this->_commonDir=Zend_Registry::get( 'config' )->path->absolute->user_files.$_strDir.DIRECTORY_SEPARATOR;
		if ( !Zend_Registry::get( 'objUser' )->prepareDtaDir( $_strDir ) ) {
			throw new Exception( Core_Errors::DEV.'|Zend_Registry::get( \'objUser\' )->prepareDtaDir( $_strDir ) no dir set' );
			return;
		}
		$this->_userDir=$_strDir;
	}

	public function getErrors( &$arrRes ) {
		$arrRes=$this->_error;
		return empty( $this->_error );
	}
	
	public function search( $_arrData ){
		$curl=Core_Curl::getInstance();
		$arrParams=array(
			'action'=> 'query_plugins',
			'request'=> (object) array( 
				'search'=>$_arrData['search'],
    			'page' => $_arrData['page'],
    			'fields' => array(
            		'description' => 1,
            		'sections' => 0,
            		'tested' => 1,
            		'requires' => 1,
            		'rating' => 1,
            		'downloaded' => 1,
            		'downloadlink' => 1,
            		'last_updated' => 1,
            		'homepage' => 1,
            		'tags' => 1,
            		'num_ratings' => 1,
        		),    			
	    		'per_page' => $_arrData['per_page']
			)
		);
		if (!$curl->setPost( $arrParams )->getContent('http://api.wordpress.org/plugins/info/1.0/')){
			return false;
		}
		$arr=$curl->getResponce();
		return (array) unserialize($arr);
	}
	/**
	 * Восстановление ссылок на стандартные шаблоны для пользователся
	 * перед этим сначала удалим, чтобы небыло дубликатов
	 *
	 * @return boolean
	 */
	public function reassignCommonToUser() {
		$this->toRestore()->onlyIds()->getList( $_arrIds );
		Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToUser.' WHERE plugin_id IN('.Core_Sql::fixInjection( $_arrIds ).') AND user_id="'.$this->_userId.'"' );
		return $this->linkToUser( $this->_userId, $_arrIds );
	}

	/**
	 * Добавление ссылок на стандартные шаблоны для нового пользователся
	 * проблема может возникнуть только в случае если пользователь удалил все стандартные - будем решать по факту
	 *
	 * @return boolean
	 */
	public function addCommonPluginsToNewUser() {
		if ( !$this->toRestore()->onlyIds()->getList( $_arrIds ) ) {
			return false;
		}
		$_arrTest=Core_Sql::getField( 'SELECT plugin_id FROM '.$this->_tableLinkToUser.' WHERE plugin_id IN('.Core_Sql::fixInjection( $_arrIds ).') AND user_id="'.$this->_userId.'"' );
		if ( !empty( $_arrTest ) ) {
			return true;
		}
		return $this->linkToUser( $this->_userId, $_arrIds );
	}

	/**
	 * Удаление шаблона из списка+попытка удалить физически
	 * пропадает из списков но при наличии связанных сайтов физически не удаляется
	 *
	 * @param int $_intId
	 * @return boolean
	 */
	public function deleteCommonPlugin( $_intId=0 ) {
		if ( empty( $_intId ) ) {
			return false;
		}
		Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToUser.' WHERE plugin_id="'.$_intId.'"' );
		$this->unlinkPlugins( $_intId );
		return true;
	}

	/**
	 * Удаление пользовательского шаблона из списка+попытка удалить физически
	 * пропадает из списков но при наличии связанных сайтов физически не удаляется
	 *
	 * @param int $_intId
	 * @return boolean
	 */
	public function deleteUserPlugin( $_intId=0 ) {
		if ( empty( $_intId ) ) {
			return false;
		}
		return $this->unlinkFromUser( $this->_userId, $_intId );
	}

	// физическое удаление плагинов, при условии что на плагины нет ссылок в $this->_tableLinkToUser и $this->_tableLinkToBlog
	// при добавлении нового плагина ссылки появляются в любом случае
	private function unlinkPlugins( $_arrPluginsToDel=array() ) {
		if ( empty( $_arrPluginsToDel ) ) {
			return false;
		}
		$_arrPluginsWithNoLink=Core_Sql::getField( '
			SELECT p.id FROM '.$this->_tablePlugins.' p WHERE 
				p.id IN('.Core_Sql::fixInjection( $_arrPluginsToDel ).') AND NOT (
					p.id IN(SELECT plugin_id FROM '.$this->_tableLinkToUser.' WHERE plugin_id=p.id) OR
					p.id IN(SELECT plugin_id FROM '.$this->_tableLinkToBlog.' WHERE plugin_id=p.id)
				)
			GROUP BY p.id
		' );
		if ( empty( $_arrPluginsWithNoLink ) ) {
			return false;
		}
		$_arrPlugins=Core_Sql::getAssoc( 'SELECT * FROM '.$this->_tablePlugins.' WHERE id IN('.Core_Sql::fixInjection( $_arrPluginsWithNoLink ).')' );
		if ( empty( $_arrPlugins ) ) {
			return false;
		}
		foreach( $_arrPlugins as $v ) {
			// предполагается что пользовательские плагины удаляет только пользователь, а если так то мы будем знать $this->_userDir
			@unlink( (empty( $v['flg_type'] )? $this->_commonDir:$this->_userDir).$v['filename'] );
		}
		Core_Sql::setExec( 'DELETE FROM '.$this->_tablePlugins.' WHERE id IN('.Core_Sql::fixInjection( $_arrPluginsWithNoLink ).')' );
		return true;
	}

	/**
	 * Добавление ссылок на пользователя
	 *
	 * @param mix  $_arrUserIds - один или несколько id пользователей
	 * @param mix  $_arrTemplatesIds - один или несколько темплэйтов
	 * @return boolean
	 */
	private function linkToUser( $_arrUserIds=array(), $_arrPluginsIds=array() ) {
		if ( empty( $_arrUserIds )||empty( $_arrPluginsIds ) ) {
			return false;
		}
		if ( !is_array( $_arrUserIds ) ) {
			$_arrUserIds=array( $_arrUserIds );
		}
		if ( !is_array( $_arrPluginsIds ) ) {
			$_arrPluginsIds=array( $_arrPluginsIds );
		}
		$_arrIns=array();
		foreach( $_arrUserIds as $u ) {
			foreach( $_arrPluginsIds as $p ) {
				$arrIns[]=array( 'user_id'=>$u, 'plugin_id'=>$p );
			}
		}
		return Core_Sql::setMassInsert( $this->_tableLinkToUser, $arrIns );
	}

	/**
	 * Удаление ссылок на пользователя + попытка удаления шаблонов
	 * использовать при удалении пользователя в том числе
	 *
	 * @param mix  $_arrUserIds - один или несколько id пользователей
	 * @param mix  $_arrTemplatesIds - один или несколько темплэйтов
	 * @return boolean
	 */
	private function unlinkFromUser( $_arrUserIds=array(), $_arrPluginsIds=array() ) {
		if ( empty( $_arrUserIds ) ) {
			return false;
		}
		if ( !is_array( $_arrPluginsIds ) ) {
			$_arrPluginsIds=array( $_arrPluginsIds );
		}
		if ( empty( $_arrPluginsIds ) ) {
			$_arrPluginsIds=Core_Sql::getField( 'SELECT plugin_id FROM '.$this->_tableLinkToUser.' WHERE user_id IN('.Core_Sql::fixInjection( $_arrUserIds ).') GROUP BY plugin_id' );
		}
		$_bool=Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToUser.' WHERE user_id IN('.Core_Sql::fixInjection( $_arrUserIds ).')'.
			(empty( $_arrPluginsIds )? '':' AND plugin_id IN('.Core_Sql::fixInjection( $_arrPluginsIds ).')') ); // чистим таблицу линков
		if ( !empty( $_arrPluginsIds ) ) {
			$this->unlinkPlugins( $_arrPluginsIds );
		}
		return true;
	}

	// добавление в новый блог плагинов blogLink( $_blogId, $_arrPluginsIds );
	// обновление списка плагинов блога blogLink( $_blogId, $_arrPluginsIds );
	// удаление блога blogLink( $_blogId );
	public function blogLink( $_arrBlogIds=array(), $_arrPluginsIds=array() ) {
		if ( empty( $_arrBlogIds ) ) {
			return false;
		}
		if ( !is_array( $_arrBlogIds ) ) {
			$_arrBlogIds=array( $_arrBlogIds );
		}
		if ( !is_array( $_arrPluginsIds ) ) {
			$_arrPluginsIds=array( $_arrPluginsIds );
		}
		$_arrOldPluginsIds=Core_Sql::getField( 'SELECT plugin_id FROM '.$this->_tableLinkToBlog.' WHERE blog_id IN('.Core_Sql::fixInjection( $_arrBlogIds ).') GROUP BY plugin_id' );
		$_bool=Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToBlog.' WHERE blog_id IN('.Core_Sql::fixInjection( $_arrBlogIds ).')' ); // чистим таблицу линков
		if ( empty( $_arrPluginsIds ) ) {
			$this->unlinkPlugins( $_arrOldPluginsIds ); // тут удаляем все
			return true;
		}
		$this->unlinkPlugins( array_diff( $_arrOldPluginsIds, $_arrPluginsIds ) ); // тут удаляем только те которые были отлинкованы от блога
		$_arrIns=array();
		foreach( $_arrBlogIds as $b ) {
			foreach( $_arrPluginsIds as $p ) {
				$arrIns[]=array( 'blog_id'=>$b, 'plugin_id'=>$p );
			}
		}
		return Core_Sql::setMassInsert( $this->_tableLinkToBlog, $arrIns ); // добавляем новый список линков
	}

	public function addCommonPlugin( $_arrZip=array() ) {
		if ( !$this->checkFile( $_arrZip ) ) {
			return false;
		}
		if ( $this->onlyCommon()->withFilename( $_arrZip['name'] )->getList( $_arrTmp ) ) {
			$this->_error = '002';
			return false; // такой плагин уже есть
		}
		// если всё нормально то записываем залитый zip в папку общих плагинов
		if ( move_uploaded_file( $_arrZip['tmp_name'], $this->_commonDir.$_arrZip['name'] )==false ) {
			return false; // ошибка при копировании
		}
		// в базу данных
		$_data=new Core_Data();
		$_intId=Core_Sql::setInsert( $this->_tablePlugins, $_data->setMask( $this->_fields )->getValidCurrent( $_arrZip+array(
			'flg_type'=>0,
			'filename'=>$_arrZip['name'],
			'added'=>time()
		) ) );
		// и линки всем текущим пользователям
		Zend_Registry::get( 'objUser' )->onlyParentIds()->withoutGroups( array( 'Super Admin', 'System Users', 'Content Admin', 'Visitor' ) )->getList( $_arrUsersIds );
		return $this->linkToUser( $_arrUsersIds, $_intId );
	}

	public function downloadPlugin($_strLink){
		if (empty($_strLink)){
			return false;
		}
		$_strTmp='Project_Wpress_Plugin@downloadPlugin';
		if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $_strTmp ) ) {
			return false;
		}		
		$_curl=Core_Curl::getInstance();
		if (!$_curl->getContent($_strLink)){
			return false;
		}
		$_strContent=$_curl->getResponce();
		if (!Core_Files::setContent($_strContent,$_strTmp.'plugin.zip')){
			return false;
		}
		$_arrData=array(
			'name'=>Core_Files::getBaseName($_strLink),
			'tmp_name'=> $_strTmp.'plugin.zip',
		);
		if ( !$this->addUserPlugin($_arrData) ){
			return false;
		}
		return true;
	}
	// добавление пользовтаельского плагина
	public function addUserPlugin( $_arrZip=array() ) {
		if ( !$this->checkFile( $_arrZip ) ) {
			return false; // некорректный файл
		}
		if ( $this->withFilename( $_arrZip['name'] )->getList( $_arrTmp ) ) {
			$this->_error = '002';
			return false; // такой плагин уже есть
		}
		// если всё нормально то записываем залитый zip в папку пользователя
		if ( copy( $_arrZip['tmp_name'], $this->_userDir.$_arrZip['name'] )==false ) {
			return false; // ошибка при копировании
		}
		// в базу данных
		$_data=new Core_Data();
		$_intId=Core_Sql::setInsert( $this->_tablePlugins, $_data->setMask( $this->_fields )->getValidCurrent( $_arrZip+array(
			'filename'=>$_arrZip['name'],
			'added'=>time()
		) ) );
		// и линки текущему пользователю
		return $this->linkToUser( $this->_userId, $_intId );
	}

	/**
	 * Парсинг файла для получения информеции о плагине
	 * в нормальном плагине должна быть шапка например такого вида:
	 * Plugin Name: All in One SEO Pack
	 * Plugin URI: http://semperfiwebdesign.com
	 * Description: Out-of-the-box SEO for your Wordpress blog.
	 * Version: 1.5.6
	 * Author: Michael Torbert
	 * Author URI: http://michaeltorbert.com
	 *
	 * @param array $_arrZip - массив $_FILES[name]
	 * @param array $_strFileContent - содержимое очередного файла из плагина
	 * @return boolean
	 */
	private function parseFile( &$_arrZip, &$_strFileContent ) {
		if ( !preg_match( '/Plugin Name ?: ?(.*)$/mi', $_strFileContent, $_arrMatch ) ) {
			return false;
		}
		$_arrZip['title']=trim( $_arrMatch[1] );
		if ( preg_match( '/Plugin URI ?: ?(.*)$/mi', $_strFileContent, $_arrMatch ) ) {
			$_arrZip['url']=trim( $_arrMatch[1] );
		}
		if ( preg_match( '/Version ?: ?(.*)$/mi', $_strFileContent, $_arrMatch ) ) {
			$_arrZip['version']=trim( $_arrMatch[1] );
		}
		if ( preg_match( '/Author ?: ?(.*)$/mi', $_strFileContent, $_arrMatch ) ) {
			$_arrZip['author']=trim( $_arrMatch[1] );
		}
		if ( preg_match( '/Author URI ?: ?(.*)$/mi', $_strFileContent, $_arrMatch ) ) {
			$_arrZip['author_url']=trim( $_arrMatch[1] );
		}
		if ( preg_match( '/Description ?: ?(.*)$/mi', $_strFileContent, $_arrMatch ) ) {
			$_arrZip['description']=trim( $_arrMatch[1] );
		}
		return true;
	}

	public function checkFile( &$_arrZip ) {
		if ( empty( $_arrZip ) ) {
			return false;
		}
		if( $_arrZip['size']>$this->_maxArchiveSize ){
			$this->_error='011';
			return false;
		}
		if( Core_Files::getExtension( $_arrZip['name'] )!='zip' ){
			$this->_error = '012';
			return false;
		}
		$_strExtractDir='Project_Wpress_Plugins@checkFile';
		if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $_strExtractDir ) ) {
			return false;
		}
		$zip=new Core_Zip();
		// проверка что это корректный zip и распаковываем во временную папку
		if ( !$zip->setDir( $_strExtractDir )->extractZip( $_arrZip['tmp_name'] ) ) {
			$this->_errors = '013';
			return false;
		}
		if ( !Core_Files::dirScan( $_arr, $_strExtractDir ) ) {
			return false; // пусто
		}
		foreach( $_arr as $_strDir=>$_arrFiles ) {
			foreach( $_arrFiles as $_strFile ) {
				// ищем по php файлам описание плагина
				if ( Core_Files::getExtension( $_strFile )!='php' ) {
					continue;
				}
				if ( !( $_strFileContent=@file_get_contents( $_strDir.DIRECTORY_SEPARATOR.$_strFile ) ) ) {
					continue;
				}
				if ( $this->parseFile( $_arrZip, $_strFileContent ) ) {
					// путь от корня папки plugins до исполняемого файла плагина в установленном wp
					$_arrZip['wp_path']=str_replace( '\\', '/', str_replace( $_strExtractDir, '', $_strDir.DIRECTORY_SEPARATOR.$_strFile ) );
					return true;
				}
			}
			break; // в архиве должна быть одна подпапка где лежит плагин
		}
		$this->_error = '014';
		return false;
	}

	// настройки для getList
	private $_onlySiteId=0;
	private $_onlyIds=false; // массив с ids
	private $_onlyCount=false; // только количество
	private $_onlyCommon=false; // только общие
	private $_onlyOne=false; // только одна запись
	private $_withIds=0; // c данными id (array or int)
	private $_toRestore=false; // только общие для восстоновления
	private $_withPagging=array(); // постранично
	private $_withFilename=''; // c сортировкой
	private $_withOrder='p.id--up'; // c сортировкой
	private $_paging=array(); // инфа по навигации
	private $_cashe=array(); // закэшированный фильтр

	// сброс настроек после выполнения getArticles
	private function init() {
		$this->_onlySiteId=0;
		$this->_onlyIds=false;
		$this->_onlyCount=false;
		$this->_onlyCommon=false;
		$this->_onlyOne=false;
		$this->_toRestore=false;
		$this->_withIds=0;
		$this->_withPagging=array();
		$this->_withFilename='';
		$this->_withFilenames='';
		$this->_withOrder='p.id--up';
	}

	public function onlySiteId( $_intId){
		$this->_onlySiteId = intval($_intId);
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

	// только общие плагины
	public function onlyCommon() {
		$this->_onlyCommon=true;
		return $this;
	}

	public function onlyOne() {
		$this->_onlyOne=true;
		return $this;
	}

	// array, int
	public function withIds( $_mixId=0 ) {
		$this->_withIds=$_mixId;
		return $this;
	}

	public function withFilename( $_str='' ) {
		$this->_withFilename=$_str;
		return $this;
	}

	public function withFilenames( $_arrNames=array() ) {
		$this->_withFilenames= "'".join("','",$_arrNames)."'";
		return $this;
	}
	public function withPagging( $_arr=array() ) {
		$this->_withPagging=$_arr;
		return $this;
	}	
	
	public function toRestore( ) {
		$this->_toRestore=true;
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
	}

	public function getPaging( &$arrRes ) {
		$arrRes=$this->_paging;
		$this->_paging=array();
	}

	public function getList( &$mixRes ) {
		$_crawler=new Core_Sql_Qcrawler();
		$_crawler->set_select( 'p.*' );
		$_crawler->set_from( $this->_tablePlugins.' p' );
		// в этом случае надо отображать только общие плагины на которые есть ссылка в $this->_tableLinkToUser, 
		// т.к. если сслки нет это означает что плагин удалён, даже если есть в $this->_tablePlugins
		if ( $this->_onlyCommon ) {
			$_crawler->set_from( 'INNER JOIN '.$this->_tableLinkToUser.' lu ON lu.plugin_id=p.id' );
			$_crawler->set_where( 'p.flg_type=0' );
		} elseif ( $this->_toRestore ) { // только стандартные плагины
			$_crawler->set_where( 'p.flg_type=0' );
		} elseif ( !empty( $this->_userId ) ) {
			$_crawler->set_from( 'INNER JOIN '.$this->_tableLinkToUser.' lu ON lu.plugin_id=p.id AND lu.user_id='.$this->_userId );
		}
		if ( !empty( $this->_onlySiteId ) ) {
			$_crawler->set_from( 'INNER JOIN '.$this->_tableLinkToBlog.' lb ON lb.plugin_id=p.id AND lb.blog_id='.$this->_onlySiteId );
		}
		if ( !empty( $this->_withFilename ) ) {
			$_crawler->set_where( 'p.filename='.Core_Sql::fixInjection( $this->_withFilename ) );
		}
		if ( !empty( $this->_withFilenames ) ) {
			$_crawler->set_where( 'p.filename IN('. $this->_withFilenames .')' );
		}
		if ( !empty( $this->_withIds ) ) {
			$_crawler->set_where( 'p.id IN ('.Core_Sql::fixInjection( $this->_withIds ).')' );
		}
		$_crawler->set_order_sort( $this->_withOrder );
		if ( !empty( $this->_userId )||!empty( $this->_onlySiteId )||$this->_onlyCommon ) {
			$_crawler->set_group( 'p.id' );
		}
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
			if ( !empty( $mixRes ) ) {
				foreach( $mixRes as $k=>$v ) {
					$mixRes[$k]['path']=empty( $v['flg_type'] )? $this->_commonDir:$this->_userDir;
				}
			}
		}
		$this->init();
		return !empty( $mixRes );
	}
}
?>