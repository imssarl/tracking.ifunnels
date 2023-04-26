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
 * Theme management
 *
 * @category Project
 * @package Project_Wpress
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Wpress_Theme {

	private $_userId=0;

	// Errors code:
	// 011 - Uploaded file size is more than 3MB.Please upload below 3MB.
	// 012 - Invalid file.Please upload only zip file.
	// 013 - Invalid zip file.
	// 014 - Invalid Plugin.
	// 002 - This plugin is already exist
	private $_error=0;

	// папки и файлы
	private $_maxArchiveSize=5242880; // максимально разрешённый размер архива
	private $_commonDir =''; // общие шаблоны
	private $_userDir=''; // плагины пользовтеля
	private $_commonDirPreview =''; // html путь до скриншотов общих шаблонов
	private $_userDirPreview=''; // html путь до скриншотов пользовательских шаблонов
	private $_userTmpDir=''; // временная папка пользовтеля
	public $_extractDir='';

	// таблицы
	private $_tableThemes='bf_themes';
	private $_tableLinkToUser='bf_theme2user_link';
	private $_tableLinkToBlog='bf_theme2blog_link';
	private $_fields=array( 'id', 'flg_type', 'flg_prop', 'priority', 'filename', 'title', 'url', 'version', 'author', 'author_url', 'description', 'added' );

	public function __construct() {
		if ( !Zend_Registry::get( 'objUser' )->getId( $_int ) ) {
			throw new Exception( Core_Errors::DEV.'|Zend_Registry::get( \'objUser\' )->getId( $_int ) is not return an User Id' );
			return;
		}
		$this->_userId=$_int;
		$this->initPaths();
	}
	
	public function search( $_arrData ){
		$curl=Core_Curl::getInstance();
		$arrParams=array(
			'action'=> 'query_themes',
			'request'=> (object) array( 
				$_arrData['type']=>($_arrData['type']=='tag')?explode(' ',$_arrData['search']):$_arrData['search'],
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
		if (!$curl->setPost( $arrParams )->getContent('http://api.wordpress.org/themes/info/1.0/')){
			return false;
		}
		$arr=$curl->getResponce();
		return (array) unserialize($arr);
	}

	private function initPaths() {
		$_strDir='blogfusion'.DIRECTORY_SEPARATOR.'themes';
		$this->_commonDir=Zend_Registry::get( 'config' )->path->absolute->user_files.$_strDir.DIRECTORY_SEPARATOR;
		if ( !Zend_Registry::get( 'objUser' )->prepareDtaDir( $_strDir ) ) {
			throw new Exception( Core_Errors::DEV.'|Zend_Registry::get( \'objUser\' )->prepareDtaDir( $_strDir ) no dir set' );
			return;
		}
		$this->_userDir=$_strDir;
		$this->_userDirPreview=Zend_Registry::get( 'config' )->path->html->user_data.$this->_userId.'/blogfusion/themes/';
		$this->_commonDirPreview=Zend_Registry::get( 'config' )->path->html->user_files.'blogfusion/themes/';
	}

	public function getErrors( &$arrRes ) {
		$arrRes=$this->_error;
		return empty( $this->_error );
	}

	/**
	 * Восстановление ссылок на стандартные шаблоны для пользователся
	 * перед этим сначала удалим, чтобы небыло дубликатов
	 *
	 * @return boolean
	 */
	public function reassignCommonToUser() {
		if ( !$this->toRestore()->onlyIds()->getList( $_arrIds ) ) {
			return false;
		}
		Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToUser.' WHERE theme_id IN('.Core_Sql::fixInjection( $_arrIds ).') AND user_id="'.$this->_userId.'"' );
		return $this->linkToUser( $this->_userId, $_arrIds );
	}

	/**
	 * Добавление ссылок на стандартные шаблоны для нового пользователся
	 * проблема может возникнуть только в случае если пользователь удалил все стандартные - будем решать по факту
	 *
	 * @return boolean
	 */
	public function addCommonThemesToNewUser() {
		if ( !$this->toRestore()->onlyIds()->getList( $_arrIds ) ) {
			return false;
		}
		$_arrTest=Core_Sql::getField( 'SELECT theme_id FROM '.$this->_tableLinkToUser.' WHERE theme_id IN('.Core_Sql::fixInjection( $_arrIds ).') AND user_id="'.$this->_userId.'"' );
		if ( !empty( $_arrTest ) ) {
			return true;
		}
		return $this->linkToUser( $this->_userId, $_arrIds );
	}

	// если удаляется общий плагин то он пропадает у всех пользователей, но при залинкованных блогах физически и из таблицы плагинов не удаляем
	public function deleteCommonTheme( $_intId=0 ) {
		if ( empty( $_intId ) ) {
			return false;
		}
		Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToUser.' WHERE theme_id="'.$_intId.'"' );
		$this->unlinkThemes( $_intId );
		return true;
	}

	// если удаляется пользовательский плагин то он пропадает у пользователя, но при залинкованных блогах физически и из таблицы плагинов не удаляем
	public function deleteUserTheme( $_intId=0 ) {
		if ( empty( $_intId ) ) {
			return false;
		}
		return $this->unlinkFromUser( $this->_userId, $_intId );
	}

	// физическое удаление плагинов, при условии что на плагины нет ссылок в $this->_tableLinkToUser и $this->_tableLinkToBlog
	// при добавлении нового темы ссылки появляются в любом случае
	private function unlinkThemes( $_arrThemesToDel=array() ) {
		if ( empty( $_arrThemesToDel ) ) {
			return false;
		}
		$_arrThemesWithNoLink=Core_Sql::getField( '
			SELECT p.id FROM '.$this->_tableThemes.' p WHERE 
				p.id IN('.Core_Sql::fixInjection( $_arrThemesToDel ).') AND NOT (
					p.id IN(SELECT theme_id FROM '.$this->_tableLinkToUser.' WHERE theme_id=p.id) OR
					p.id IN(SELECT theme_id FROM '.$this->_tableLinkToBlog.' WHERE theme_id=p.id)
				)
			GROUP BY p.id
		' );
		if ( empty( $_arrThemesWithNoLink ) ) {
			return false;
		}
		$_arrThemes=Core_Sql::getAssoc( 'SELECT * FROM '.$this->_tableThemes.' WHERE id IN('.Core_Sql::fixInjection( $_arrThemesWithNoLink ).')' );
		if ( empty( $_arrThemes ) ) {
			return false;
		}
		foreach( $_arrThemes as $v ) {
			// предполагается что пользовательские плагины удаляет только пользователь, а если так то мы будем знать $this->_userDir
			@unlink( (empty( $v['flg_type'] )? $this->_commonDir:$this->_userDir).$v['filename'] ); // тема
			@unlink( (empty( $v['flg_type'] )? $this->_commonDir:$this->_userDir).Core_Files::getFileName( $v['filename'] ).'.png' ); // первьюха
		}
		Core_Sql::setExec( 'DELETE FROM '.$this->_tableThemes.' WHERE id IN('.Core_Sql::fixInjection( $_arrThemesWithNoLink ).')' );
		return true;
	}

	// добавление темы пользователям userLink( $_arrUserIds, $_themeId );
	// добавление темы пользоватлем userLink( $_userId, $_themeId );
	private function linkToUser( $_arrUserIds=array(), $_arrThemesIds=array() ) {
		if ( empty( $_arrUserIds )||empty( $_arrThemesIds ) ) {
			return false;
		}
		if ( !is_array( $_arrUserIds ) ) {
			$_arrUserIds=array( $_arrUserIds );
		}
		if ( !is_array( $_arrThemesIds ) ) {
			$_arrThemesIds=array( $_arrThemesIds );
		}
		$_arrIns=array();
		foreach( $_arrUserIds as $u ) {
			foreach( $_arrThemesIds as $p ) {
				$arrIns[]=array( 'user_id'=>$u, 'theme_id'=>$p );
			}
		}
		return Core_Sql::setMassInsert( $this->_tableLinkToUser, $arrIns );
	}

	// удаление темы у пользователей unlinkFromUser( $_arrUserIds, $_themeId );
	// удаление темы пользователем unlinkFromUser( $_userId, $_themeId );
	// удаление пользователя unlinkFromUser( $_userId );
	private function unlinkFromUser( $_arrUserIds=array(), $_arrThemesIds=array() ) {
		if ( empty( $_arrUserIds ) ) {
			return false;
		}
		if ( !is_array( $_arrThemesIds ) ) {
			$_arrThemesIds=array( $_arrThemesIds );
		}
		if ( empty( $_arrThemesIds ) ) {
			$_arrThemesIds=Core_Sql::getField( 'SELECT theme_id FROM '.$this->_tableLinkToUser.' WHERE user_id IN('.Core_Sql::fixInjection( $_arrUserIds ).') GROUP BY theme_id' );
		}
		$_bool=Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToUser.' WHERE user_id IN('.Core_Sql::fixInjection( $_arrUserIds ).')'.
			(empty( $_arrThemesIds )? '':' AND theme_id IN('.Core_Sql::fixInjection( $_arrThemesIds ).')') ); // чистим таблицу линков
		if ( !empty( $_arrThemesIds ) ) {
			$this->unlinkThemes( $_arrThemesIds );
		}
		return true;
	}

	// добавление в новый блог плагинов blogLink( $_arrBlogIds, $_arrThemesIds );
	// обновление списка плагинов блога blogLink( $_arrBlogIds, $_arrThemesIds );
	// удаление блога blogLink( $_arrBlogIds );
	public function blogLink( $_arrBlogIds=array(), $_arrThemesIds=array() ) {
		if ( empty( $_arrBlogIds ) ) {
			return false;
		}
		if ( !is_array( $_arrBlogIds ) ) {
			$_arrBlogIds=array( $_arrBlogIds );
		}
		if ( !is_array( $_arrThemesIds ) ) {
			$_arrThemesIds=array( $_arrThemesIds );
		}
		$_arrOldThemesIds=Core_Sql::getField( 'SELECT theme_id FROM '.$this->_tableLinkToBlog.' WHERE blog_id IN('.Core_Sql::fixInjection( $_arrBlogIds ).') GROUP BY theme_id' );
		Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToBlog.' WHERE blog_id IN('.Core_Sql::fixInjection( $_arrBlogIds ).')' ); // чистим таблицу линков
		if ( empty( $_arrThemesIds ) ) {
			$this->unlinkThemes( $_arrOldThemesIds ); // тут удаляем темы физически если они помечены как удалённые в БД
			return true;
		}
		$this->unlinkThemes( array_diff( $_arrOldThemesIds, $_arrThemesIds ) ); // тут удаляем только те которые были отлинкованы от блога
		$_arrIns=array();
		foreach( $_arrBlogIds as $b ) {
			foreach( $_arrThemesIds as $p ) {
				$arrIns[]=array( 'blog_id'=>$b, 'theme_id'=>$p );
			}
		}
		return Core_Sql::setMassInsert( $this->_tableLinkToBlog, $arrIns ); // добавляем новый список линков
	}

	public function addCommonTheme( $_arrDta=array(), $_arrZip=array() ) {
		if ( !$this->checkFile( $_arrZip ) ) {
			return false; // некорректный файл
		}
		if ( $this->onlyCommon()->withFilename( $_arrZip['name'] )->getList( $_arrTmp ) ) {
			$this->_error = array('002');
			return false; // такой плагин уже есть
		}
		// если всё нормально то записываем перепакованную тему + картинку в папку общих плагинов
		$_bool1=copy( $this->_extractDir.$_arrZip['name'], $this->_commonDir.$_arrZip['name'] );
		$_bool2=copy( $this->_extractDir.Core_Files::getFileName( $_arrZip['name'] ).'.png', $this->_commonDir.Core_Files::getFileName( $_arrZip['name'] ).'.png' );
		if ( !$_bool1||!$_bool2 ) {
			return false;
		}
		// в базу данных
		$_data=new Core_Data( $_arrDta );
		$_data->setFilter();
		$_intId=Core_Sql::setInsert( $this->_tableThemes, $_data->setMask( $this->_fields )->getValidCurrent( $_arrZip+array(
			'flg_type'=>0,
			'flg_prop'=>empty( $_data->filtered['flg_prop'] )? 0:1,
			'priority'=>empty( $_data->filtered['priority'] )? 0:$_data->filtered['priority'],
			'filename'=>$_arrZip['name'],
			'added'=>time()
		) ) );
		// и линки всем текущим пользователям
		Zend_Registry::get( 'objUser' )->onlyParentIds()->withoutGroups( array( 'Super Admin', 'System Users', 'Content Admin', 'Visitor' ) )->getList( $_arrUsersIds );
		return $this->linkToUser( $_arrUsersIds, $_intId );
	}

	public function downloadTheme($_strLink){
		if (empty($_strLink)){
			return false;
		}
		$_strTmp='Project_Wpress_Theme@downloadTheme';
		if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $_strTmp ) ) {
			return false;
		}		
		$_curl=Core_Curl::getInstance();
		if (!$_curl->getContent($_strLink)){
			return false;
		}
		$_strContent=$_curl->getResponce();
		if (!Core_Files::setContent($_strContent,$_strTmp.'theme.zip')){
			return false;
		}
		$_arrData=array(
			'name'=>Core_Files::getBaseName($_strLink),
			'tmp_name'=> $_strTmp.'theme.zip',
		);
		if ( !$this->addUserTheme($_arrData) ){
			return false;
		}
		return true;
	}
	// добавление пользовтаельского темы
	public function addUserTheme( $_arrZip=array() ) {
		if ( !$this->checkFile( $_arrZip ) ) {
			return false; // некорректный файл
		}
		if ( $this->withFilename( $_arrZip['name'] )->getList( $_arrTmp ) ) {
			$this->_error=array('002');
			return false; // такой плагин уже есть
		}
		// если всё нормально то записываем перепакованную тему + картинку в папку общих плагинов
		$_bool1=copy( $this->_extractDir.$_arrZip['name'], $this->_userDir.$_arrZip['name'] );
		if( is_file($this->_extractDir.Core_Files::getFileName( $_arrZip['name'] ).'.png') ) {
			$_bool2=copy( $this->_extractDir.Core_Files::getFileName( $_arrZip['name'] ).'.png', $this->_userDir.Core_Files::getFileName( $_arrZip['name'] ).'.png' );
		} else {
			$_bool2=true;
		}
		if ( !$_bool1||!$_bool2 ) {
			return false;
		}
		// в базу данных
		$_data=new Core_Data();
		$_intId=Core_Sql::setInsert( $this->_tableThemes, $_data->setMask( $this->_fields )->getValidCurrent( $_arrZip+array(
			'filename'=>$_arrZip['name'],
			'added'=>time()
		) ) );
		return $this->linkToUser( $this->_userId, $_intId ); // и линки текущему пользователю
	}

	/**
	 * Парсинг файла для получения информеции о теме
	 * в нормальной теме должна быть шапка например такого вида:
	 * Theme Name: Lifestyle WordPress Theme
	 * Theme URL: http://www.revolutiontwo.com/themes/lifestyle
	 * Description: Lifestyle is a 3-column Widget-ready theme created for WordPress.
	 * Author: Brian Gardner
	 * Author URI: http://www.briangardner.com
	 * Version: 3.0
	 * Tags: three columns, fixed width, white, tan, teal, purple, sidebar widgets
	 *
	 * @param array $_arrZip - массив $_FILES[name]
	 * @param array $_strFileContent - содержимое очередного файла из темы
	 * @return boolean
	 */
	private function parseFile( &$_arrZip, &$_strFileContent ) {
		if ( !preg_match( '/Theme Name ?: ?(.*)$/mi', $_strFileContent, $_arrMatch ) ) {
			return false;
		}
		$_arrZip['title']=trim( $_arrMatch[1] );
		if ( preg_match( '/Theme URL ?: ?(.*)$/mi', $_strFileContent, $_arrMatch ) ) {
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
	
	private function checkChild(&$_arrZip, &$_strFileContent){
		if ( !preg_match( '/Template ?: ?(.*)$/mi', $_strFileContent, $_arrMatch ) ) {
			return false;
		}		
		$_arrZip['template']=trim( $_arrMatch[1] );
		return true;
	}

	public function checkFile( &$_arrZip ) {
		if ( empty( $_arrZip ) ) {
			$this->_error=array('010');
			return false;
		}
		if( $_arrZip['size']>$this->_maxArchiveSize ){
			$this->_error=array('011');
			return false;
		}
		if( Core_Files::getExtension( $_arrZip['name'] )!='zip' ){
			$this->_error=array('012');
			return false;
		}
		$this->_extractDir='Project_Wpress_Theme@checkFile';
		if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $this->_extractDir ) ) {
			$this->_error=array('0121');
			return false;
		}
		$zip=new Core_Zip();
		if ( !$zip->setDir( $this->_extractDir )->extractZip( $_arrZip['tmp_name'] ) ) {
			$this->_error=array('013');
			return false; // проверка что это корректный zip и распаковываем во временную папку
		}
		if ( !Core_Files::dirScan( $_arr, $this->_extractDir ) ) {
			$this->_error=array('014');
			return false; // пусто
		}//p($_arr);
		foreach ( $_arr as $_strDir=>$_arrFiles ) {
			if (in_array( 'style.css', $_arrFiles )){
				$_arrStyle[] = $_strDir;
			}	
		}
		// возможно есть Template и Child template, т.к. имеется 2-а файла style.css. Проверяем на наличеие родителя и ребенка.
		if( count($_arrStyle) > 1) {
			foreach ( $_arrStyle as $_dir ) {
				if ( Core_Files::getContent( $_strFileContent, $_dir . DIRECTORY_SEPARATOR . 'style.css') && $this->checkChild( $_arrZip, $_strFileContent ) ){
					$_arrZip['child']=$_dir;
					break; // Нашли ребёнка.
				}
			}
		}
		foreach( $_arr as $_strDir=>$_arrFiles ) {
			// если тема с Child, ищем корень child  
			if ( !empty( $_arrZip['template']) && !empty( $_arrZip['child'] ) ) {
				if ( $_strDir != $_arrZip['child'] ) {
					continue;
				}
			}
			// в минимальной теме, в корне должны лежать эти 2 файла
			if ( !in_array( 'style.css', $_arrFiles ) || ( empty( $_arrZip['child'] ) && !in_array( 'index.php', $_arrFiles ) ) ) {
				continue;
			}
			// выдираем информацию о теме - она хранится в style.css
			if ( !( $_strFileContent=@file_get_contents( $_strDir.DIRECTORY_SEPARATOR.'style.css' ) ) ) {
				continue;
			}
			if ( !$this->parseFile( $_arrZip, $_strFileContent ) ) {
				$this->_error = array('015 ');
				return false;
			}
			// меняем имя файла (имя архива может не совпадать с названием темы - берём название папки с темой)
			$_arrDirs=Core_Files::getDirsOfPath( $_strDir.DIRECTORY_SEPARATOR.'style.css' );
			$_arrZip['name']=$_arrDirs[0].'.zip';
			// пакуем текущую диру в zip в корень $this->_extractDir + в туже папку переносим картинку и меняем название на <имя архива>.png
			if ( true!==$zip->open( $this->_extractDir.$_arrZip['name'], ZipArchive::CREATE ) ) {
				$this->_error = array('016');
				return false;
			}
			if ( !empty( $_arrZip['template']) ) {
				if ( !$zip->addDirAndClose( $this->_extractDir ) ) {
					$this->_error = array('017');
					return false;	
				}
			} elseif ( !$zip->setRoot( Core_Files::getFileName( $_arrZip['name'] ) )->addDirAndClose( $_strDir ) ) {
				$this->_error = array('017');
				return false;
			}
			foreach( $_arrFiles as $_strFile ) {
				if ( Core_Files::getFileName( $_strFile )!='screenshot'||!in_array( Core_Files::getExtension( $_strFile ), array( 'png', 'gif', 'jpg', 'jpeg' ) ) ) {
					continue;
				}
				if ( !copy( $_strDir.DIRECTORY_SEPARATOR.$_strFile, $this->_extractDir.Core_Files::getFileName( $_arrZip['name'] ).'.png' ) ) {
					return false;
				}
			}
			return true; // в архиве должна быть одна подпапка где лежит плагин
		}
		$this->_error=array('014');
		return false;
	}

	// настройки для getList
	private $_onlySiteId=0;
	private $_onlyIds=false; // массив с ids
	private $_onlyCount=false; // только количество
	private $_onlyCommon=false; // только общие
	private $_onlyOne=false; // только одна запись
	private $_withPreview=false; // с путями до картинки
	private $_toRestore=false; // только общие для восстоновления
	private $_withIds=0; // c данными id (array or int)
	private $_withPagging=array(); // постранично
	private $_withFilename=''; // c сортировкой
	private $_withOrder='p.priority--up'; // c сортировкой
	private $_paging=array(); // инфа по навигации
	private $_cashe=array(); // закэшированный фильтр

	// сброс настроек после выполнения getArticles
	private function init() {
		$this->_onlyIds=false;
		$this->_onlyCount=false;
		$this->_onlyCommon=false;
		$this->_onlyOne=false;
		$this->_withPreview=false;
		$this->_toRestore=false;
		$this->_withIds=0;
		$this->_withPagging=array();
		$this->_withFilename='';
		$this->_withOrder='p.priority--up';
		$this->_onlySiteId=0;

	}

	public function onlySiteId( $_intId){
		$this->_onlySiteId=intval($_intId);
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

	// c html путём - для отображения превьюшек на веб странице
	public function withPreview() {
		$this->_withPreview=true;
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
		$_crawler->set_from( $this->_tableThemes.' p' );
		// в этом случае надо отображать только общие плагины на которые есть ссылка в $this->_tableLinkToUser, 
		// т.к. если сслки нет это означает что плагин удалён, даже если есть в $this->_tablePlugins
		if ( $this->_onlyCommon ) {
			$_crawler->set_from( 'INNER JOIN '.$this->_tableLinkToUser.' lu ON lu.theme_id=p.id' );
			$_crawler->set_where( 'p.flg_type=0' );
		} elseif ( $this->_toRestore ) { // только стандартные темы
			$_crawler->set_where( 'p.flg_type=0' );
		} elseif ( !empty( $this->_userId ) ) {
			$_crawler->set_from( 'INNER JOIN '.$this->_tableLinkToUser.' lu ON lu.theme_id=p.id AND lu.user_id='.$this->_userId );
		}
		if ( !empty( $this->_onlySiteId ) ) {
			$_crawler->set_from( 'INNER JOIN '.$this->_tableLinkToBlog.' lb ON lb.theme_id=p.id AND lb.blog_id='.$this->_onlySiteId );
		}
		if ( !empty( $this->_withFilename ) ) {
			$_crawler->set_where( 'p.filename='.Core_Sql::fixInjection( $this->_withFilename ) );
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
			$this->addPaths( $mixRes );
		} else {
			$mixRes=Core_Sql::getAssoc( $_strSql );
			$this->addPaths( $mixRes );
		}
		$this->init();
		return !empty( $mixRes );
	}

	// html путь до картинки (preview) и системный путь до архива(path)
	private function addPaths( &$mixRes ) {
		if ( empty( $mixRes ) ) {
			return;
		}
		if ( $this->_onlyOne ) {
			if ( $this->_withPreview ) {
				if ( is_file( ( empty( $mixRes['flg_type'] )? $this->_commonDir:$this->_userDir ) . Core_Files::getFileName( $mixRes['filename'] ).'.png' ) ) {
					$mixRes['preview']=( empty( $mixRes['flg_type'] )? $this->_commonDirPreview:$this->_userDirPreview ).Core_Files::getFileName( $mixRes['filename'] ).'.png';
				}
			}
			$mixRes['path']=empty( $mixRes['flg_type'] )? $this->_commonDir:$this->_userDir;
		} else {
			foreach( $mixRes as $k=>$v ) {
				if ( $this->_withPreview ) {
					if ( is_file( ( empty( $v['flg_type'] )? $this->_commonDir:$this->_userDir ) . Core_Files::getFileName( $v['filename'] ).'.png' ) ) {
						$mixRes[$k]['preview']=( empty( $v['flg_type'] )? $this->_commonDirPreview:$this->_userDirPreview ).Core_Files::getFileName( $v['filename'] ).'.png';
					}
				}
				$mixRes[$k]['path']=empty( $v['flg_type'] )? $this->_commonDir:$this->_userDir;
			}
		}
	}
}
?>