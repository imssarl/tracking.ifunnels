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
 * Template management
 *
 * @category Project
 * @package Project_Sites
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Sites_Templates {

	private $_spots;

	private $_siteType=0; // тип сайта
	private $_siteCode=''; // код сайта (см. Project_Sites::$code)
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
	private $_commonDir =''; // общие шаблоны
	private $_userDir=''; // шаблоны пользовтеля
	private $_commonDirPreview =''; // html путь до скриншотов общих шаблонов
	private $_userDirPreview=''; // html путь до скриншотов пользовательских шаблонов
	private $_userTmpDir=''; // временная папка пользовтеля
	public $_extractDir='';

	// таблицы
	private $_tableTemplates='es_templates';
	private $_tableLinkToUser='es_template2user';
	private $_tableLinkToSite='es_template2site';
	private $_fields=array( 'id', 'flg_type', 'flg_belong', 'flg_header', 'priority', 'filename', 'title', 'url', 'description', 'added' );

	public function __construct( $_type='' ) {
		if ( !Zend_Registry::get( 'objUser' )->getId( $_int ) ) {
			throw new Exception( Core_Errors::DEV.'|Zend_Registry::get( \'objUser\' )->getId( $_int ) is not return an User Id' );
			return;
		}
		if ( empty( Project_Sites::$code[$_type] ) ) {
			throw new Exception( Core_Errors::DEV.'|Site Type not set' );
			return;
		}
		$this->_userId=$_int;
		$this->_siteType=$_type;
		$this->_siteCode=Project_Sites::$code[$_type];
		$this->_spots=new Project_Sites_Spots( $this->_siteType );
		$this->initPaths();
	}

	private function initPaths() {
		$_strDir='sites'.DIRECTORY_SEPARATOR.$this->_siteCode;
		$this->_commonDir=Zend_Registry::get( 'config' )->path->absolute->user_files.$_strDir.DIRECTORY_SEPARATOR;
		if ( !Zend_Registry::get( 'objUser' )->prepareDtaDir( $_strDir ) ) {
			throw new Exception( Core_Errors::DEV.'|Zend_Registry::get( \'objUser\' )->prepareDtaDir( $_strDir ) no dir set' );
			return;
		}
		$this->_userDir=$_strDir;
		$this->_userDirPreview=Zend_Registry::get( 'config' )->path->html->user_data.$this->_userId.'/sites/'.$this->_siteCode.'/';
		$this->_commonDirPreview=Zend_Registry::get( 'config' )->path->html->user_files.'sites/'.$this->_siteCode.'/';
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
		$this->onlyCommon()->onlyIds()->getList( $_arrIds );
		Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToUser.' WHERE template_id IN('.Core_Sql::fixInjection( $_arrIds ).') AND user_id="'.$this->_userId.'"' );
		return $this->linkToUser( $this->_userId, $_arrIds );
	}

	/**
	 * Добавление ссылок на стандартные шаблоны для нового пользователся
	 * проблема может возникнуть только в случае если пользователь удалил все стандартные - будем решать по факту
	 *
	 * @return boolean
	 */
	public function addCommonTemplatesToNewUser() {
		if ( !$this->onlyCommon()->onlyIds()->getList( $_arrIds ) ) {
			return false;
		}
		$_arrTest=Core_Sql::getField( 'SELECT template_id FROM '.$this->_tableLinkToUser.' WHERE template_id IN('.Core_Sql::fixInjection( $_arrIds ).') AND user_id="'.$this->_userId.'"' );
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
	public function deleteCommonTemplate( $_intId=0 ) {
		if ( empty( $_intId ) ) {
			return false;
		}
		Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToUser.' WHERE template_id="'.$_intId.'"' );
		$this->unlinkTemplates( $_intId );
		return true;
	}

	/**
	 * Удаление пользовательского шаблона из списка+попытка удалить физически
	 * пропадает из списков но при наличии связанных сайтов физически не удаляется
	 *
	 * @param int $_intId
	 * @return boolean
	 */
	public function deleteUserTemplate( $_intId=0 ) {
		if ( empty( $_intId ) ) {
			return false;
		}
		return $this->unlinkFromUser( $this->_userId, $_intId );
	}

	// физическое удаление шаблонов, при условии что на шаблоны нет ссылок в $this->_tableLinkToUser и $this->_tableLinkToSite
	// при добавлении нового шаблона ссылки появляются в любом случае
	private function unlinkTemplates( $_arrTemplatesToDel=array() ) {
		if ( empty( $_arrTemplatesToDel ) ) {
			return false;
		}
		$_arrTemplatesWithNoLink=Core_Sql::getField( '
			SELECT p.id FROM '.$this->_tableTemplates.' p WHERE 
				p.id IN('.Core_Sql::fixInjection( $_arrTemplatesToDel ).') AND NOT (
					p.id IN(SELECT template_id FROM '.$this->_tableLinkToUser.' WHERE template_id=p.id) OR
					p.id IN(SELECT template_id FROM '.$this->_tableLinkToSite.' WHERE template_id=p.id)
				)
			GROUP BY p.id
		' );
		if ( empty( $_arrTemplatesWithNoLink ) ) {
			return false;
		}
		// если пользователи удалят у себя один из стандартных шаблонов и на него не будет завязан не один сайт то он может удалится - это корректно? 19.04.2010
		$_arrTemplates=Core_Sql::getAssoc( 'SELECT * FROM '.$this->_tableTemplates.' WHERE id IN('.Core_Sql::fixInjection( $_arrTemplatesWithNoLink ).')' );
		if ( empty( $_arrTemplates ) ) {
			return false;
		}
		foreach( $_arrTemplates as $v ) {
			// предполагается что пользовательские шаблоны удаляет только пользователь, а если так то мы будем знать $this->_userDir
			@unlink( (empty( $v['flg_belong'] )? $this->_commonDir:$this->_userDir).$v['filename'] ); // тема
			@unlink( (empty( $v['flg_belong'] )? $this->_commonDir:$this->_userDir).Core_Files::getFileName( $v['filename'] ).'.jpg' ); // первьюха
		}
		Core_Sql::setExec( 'DELETE FROM '.$this->_tableTemplates.' WHERE id IN('.Core_Sql::fixInjection( $_arrTemplatesWithNoLink ).')' );
		$this->_spots->del( $_arrTemplatesWithNoLink );
		return true;
	}

	/**
	 * Добавление ссылок на пользователя
	 *
	 * @param mix  $_arrUserIds - один или несколько id пользователей
	 * @param mix  $_arrTemplatesIds - один или несколько темплэйтов
	 * @return boolean
	 */
	public  function linkToUser( $_arrUserIds=array(), $_arrTemplatesIds=array() ) {
		if ( empty( $_arrUserIds )||empty( $_arrTemplatesIds ) ) {
			return false;
		}
		if ( !is_array( $_arrUserIds ) ) {
			$_arrUserIds=array( $_arrUserIds );
		}
		if ( !is_array( $_arrTemplatesIds ) ) {
			$_arrTemplatesIds=array( $_arrTemplatesIds );
		}
		$_arrIns=array();
		foreach( $_arrUserIds as $u ) {
			foreach( $_arrTemplatesIds as $p ) {
				$arrIns[]=array( 'user_id'=>$u, 'template_id'=>$p );
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
	private function unlinkFromUser( $_arrUserIds=array(), $_arrTemplatesIds=array() ) {
		if ( empty( $_arrUserIds ) ) {
			return false;
		}
		if ( !is_array( $_arrTemplatesIds ) ) {
			$_arrTemplatesIds=array( $_arrTemplatesIds );
		}
		if ( empty( $_arrTemplatesIds ) ) {
			$_arrTemplatesIds=Core_Sql::getField( 
				'SELECT template_id FROM '.$this->_tableLinkToUser.' WHERE user_id IN('.Core_Sql::fixInjection( $_arrUserIds ).') GROUP BY template_id' );
		}
		$_bool=Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToUser.' WHERE user_id IN('.Core_Sql::fixInjection( $_arrUserIds ).')'.
			(empty( $_arrTemplatesIds )? '':' AND template_id IN('.Core_Sql::fixInjection( $_arrTemplatesIds ).')') ); // чистим таблицу линков
		if ( !empty( $_arrTemplatesIds ) ) {
			$this->unlinkTemplates( $_arrTemplatesIds );
		}
		return true;
	}

	/**
	 * Добавление ссылок на сайты + удаление ссылок (если указать только $_arrSiteIds)
	 * тут как бы linkToUser+unlinkFromUser только для сайтов
	 *
	 * @param mix  $_arrSiteIds - один или несколько id сайтов
	 * @param mix  $_arrTemplatesIds - один или несколько темплэйтов (осталось от Project_Wpress_Plugins обычно тут один шаблон или ниодного, при удалении)
	 * @return boolean
	 */
	public function siteLink( $_arrSiteIds=array(), $_arrTemplatesIds=array() ) {
		if ( empty( $_arrSiteIds ) ) {
			return false;
		}
		if ( !is_array( $_arrSiteIds ) ) {
			$_arrSiteIds=array( $_arrSiteIds );
		}
		if ( !is_array( $_arrTemplatesIds ) ) {
			$_arrTemplatesIds=array( $_arrTemplatesIds );
		}
		$_arrOldTemplatesIds=Core_Sql::getField( '
			SELECT template_id 
			FROM '.$this->_tableLinkToSite.' 
			WHERE site_id IN('.Core_Sql::fixInjection( $_arrSiteIds ).') AND flg_type="'.$this->_siteType.'" 
			GROUP BY template_id
		' );
		// чистим таблицу линков
		Core_Sql::setExec( 'DELETE FROM '.$this->_tableLinkToSite.' WHERE site_id IN('.Core_Sql::fixInjection( $_arrSiteIds ).') AND flg_type="'.$this->_siteType.'"' );
		if ( empty( $_arrTemplatesIds ) ) {
			$this->unlinkTemplates( $_arrOldTemplatesIds ); // тут удаляем все
			return true;
		}
		$this->unlinkTemplates( array_diff( $_arrOldTemplatesIds, $_arrTemplatesIds ) ); // тут удаляем только те которые были отлинкованы от блога
		$_arrIns=array();
		foreach( $_arrSiteIds as $b ) {
			foreach( $_arrTemplatesIds as $p ) {
				$arrIns[]=array( 'site_id'=>$b, 'template_id'=>$p, 'flg_type'=>$this->_siteType );
			}
		}
		return Core_Sql::setMassInsert( $this->_tableLinkToSite, $arrIns ); // добавляем новый список линков
	}

	public function addCommonTemplate( $_arrDta=array(), $_arrZip=array() ) {
		if ( $this->onlyCommon()->withFilename( $_arrZip['name'] )->getList( $_arrTmp ) ) {
			$this->_error=array('002');
			return false; // такой шаблон уже есть
		}
		$_arrZip['name_only']=Core_Files::getFileName( $_arrZip['name'] );
		if ( !$this->checkFile( $_arrZip ) ) {
			return false; // некорректный файл
		}
		// если всё нормально то записываем перепакованную тему + картинку в папку общих шаблонов
		$_bool1=copy( $this->_extractDir.$_arrZip['name'], $this->_commonDir.$_arrZip['name'] );
		$_bool2=copy( $this->_extractDir.$_arrZip['name_only'].'.jpg', $this->_commonDir.$_arrZip['name_only'].'.jpg' );
		if ( !$_bool1||!$_bool2 ) {
			return false;
		}
		// в базу данных
		$_data=new Core_Data( $_arrDta );
		$_data->setFilter();
		$_intId=Core_Sql::setInsert( $this->_tableTemplates, $_data->setMask( $this->_fields )->getValidCurrent( $_arrZip+array(
			'flg_type'=>$this->_siteType,
			'flg_belong'=>0,
			'flg_header'=>empty( $_data->filtered['flg_header'] )? 0:1,
			'priority'=>empty( $_data->filtered['priority'] )? 0:$_data->filtered['priority'],
			'filename'=>$_arrZip['name'],
			'added'=>time()
		) ) );
		if ( empty( $_intId ) ) {
			return false;
		}
		$this->_spots->set( $_arrZip['spots'], $_intId );
		// и линки всем текущим пользователям
		Zend_Registry::get( 'objUser' )->onlyParentIds()->withoutGroups( array( 'Super Admin', 'System Users', 'Content Admin', 'Visitor' ) )->getList( $_arrUsersIds );
		return $this->linkToUser( $_arrUsersIds, $_intId );
	}

	// добавление пользовтаельского шаблона - не проработано TODO!!! 06.04.2010
	public function addUserTemplate( $_arrZip=array() ) {
		$_arrZip['name_only']=Core_Files::getFileName( $_arrZip['name'] );
		if ( !$this->checkFile( $_arrZip ) ) {
			return false; // некорректный файл
		}
		if ( $this->withFilename( $_arrZip['name'] )->getList( $_arrTmp ) ) {
			$this->_error=array('002');
			return false; // такой шаблон уже есть
		}
		// если всё нормально то записываем перепакованную тему + картинку в папку общих шаблонов
		$_bool1=copy( $this->_extractDir.$_arrZip['name'], $this->_userDir.$_arrZip['name'] );
		if( is_file($this->_extractDir.Core_Files::getFileName( $_arrZip['name_only'] ).'.jpg') ) {
			$_bool2=copy( $this->_extractDir.Core_Files::getFileName( $_arrZip['name_only'] ).'.jpg', $this->_userDir.Core_Files::getFileName( $_arrZip['name_only'] ).'.jpg' );
		} else {
			$_bool2=true;
		}
		if ( !$_bool1||!$_bool2 ) {
			return false;
		}
		// в базу данных
		$_data=new Core_Data();
		$_intId=Core_Sql::setInsert( $this->_tableTemplates, $_data->setMask( $this->_fields )->getValidCurrent( $_arrZip+array(
			'filename'=>$_arrZip['name'],
			'added'=>time(),
			'flg_type'=>$this->_siteType
		) ) );
		return $this->linkToUser( $this->_userId, $_intId ); // и линки текущему пользователю
	}
	
	/**
	 * Копирование шаблонов.
	 *
	 * @param array $_arrData
	 * @return bool
	 */
	public function copyTemplate( $_arrData=array() ){
		if( empty( $_arrData ) ){
			return false;
		}
		if ( !$this->onlyOne()->withIds( $_arrData['id'] )->getList( $_arr ) ){
			return false;
		}
		if ( $this->withTitle( $_arrData['name'] )->getList( $_arrTmp ) ) {
			$this->_error=array('002');
			return false; // такой шаблон уже есть
		}			
		
		// от куда будем копировать
		$_fromDir = ( $_arr['flg_belong'] == 0 )? $this->_commonDir : $this->_userDir;
		$_arrData['filename'] = str_replace(' ','_',$_arrData['name']).'.zip';
		
		// Копируем архив с шаблоном
		if ( !copy( $_fromDir . $_arr['filename'], $this->_userDir . $_arrData['filename'] ) ){
			return false;
		}
		// Копируем превьюху
		if ( !copy( $_fromDir . Core_Files::getFileName( $_arr['filename'] ) . '.jpg', $this->_userDir . Core_Files::getFileName($_arrData['filename']) . '.jpg' ) ){
			return false;
		}
		// пишем в базу
		unset($_arr['id']);
		$_arr['filename']=$_arrData['filename'];
		$_arr['title']=$_arrData['name'];
		$_arr['added']=time();
		$_arr['flg_belong']=1;
		$_data=new Core_Data();
		$_intId=Core_Sql::setInsert( $this->_tableTemplates, $_data->setMask( $this->_fields )->getValidCurrent( $_arr ) );
		return $this->linkToUser( $this->_userId, $_intId ); // и линки текущему пользователю
	}

	// парисинг template.xml
	public static function parseConfig( &$arrRes, $_strPathToFile='' ) {
		if ( !is_file( $_strPathToFile ) ) {
			return false;
		}
		$_xml=simplexml_load_file( $_strPathToFile );
		if ( !$_xml instanceof SimpleXMLElement ) {
			return false;
		}
		$arrRes['title']=(string)$_xml->name;
		$arrRes['description']=(string)$_xml->description;
		$arrRes['screenshot']=(string)$_xml->screenshot;
		$arrRes['spots']=array();
		foreach( $_xml->customization->spot as $_spot ) {
			$_arr=(array)$_spot->children();
			$arrRes['spots'][]=array(
				'name'=>$_arr['label'],
				'filename'=>$_arr['file'],
				'width'=>$_arr['recommended.width'],
				'height'=>$_arr['recommended.height'],
			);
		}
		return true;
	}

	// парисинг description.css
	public  function parseDesc( &$arrRes, $_strPathToFile='' ) {
		if ( !is_file( $_strPathToFile ) ) {
			return false;
		}
		Core_Files::getContent( $arrRes['description'], $_strPathToFile );
		$arrRes['title']=Core_Files::getFileName( $arrRes['name'] );
		$arrRes['screenshot']='datas/desc/screenshot.jpg';
		return true;
	}	
	
	public function checkFile( &$arrZip ) {
		if ( empty( $arrZip ) ) {
			$this->_error=array('010');
			return false;
		}
		if( $arrZip['size']>$this->_maxArchiveSize ){
			$this->_error=array('011');
			return false;
		}
		if( Core_Files::getExtension( $arrZip['name'] )!='zip' ){
			$this->_error=array('012');
			return false;
		}
		$this->_extractDir='Project_Sites_Templates@checkFile';
		if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $this->_extractDir ) ) {
			$this->_error=array('0121');
			return false;
		}
		$zip=new Core_Zip();
		if ( !$zip->setDir( $this->_extractDir )->extractZip( $arrZip['tmp_name'] ) ) {
			$this->_error=array('013');
			return false; // проверка что это корректный zip и распаковываем во временную папку
		}
		if ( !Core_Files::dirScan( $_arr, $this->_extractDir ) ) {
			$this->_error=array('014');
			return false; // пусто
		}
		foreach( $_arr as $_strDir=>$_arrFiles ) {
			// для PSB
			if ( Project_Sites::PSB == $this->_siteType && !in_array( 'template.xml', $_arrFiles ) ) { // такой файл должен лежать в корне шаблона
				continue;
			}
			if ( Project_Sites::PSB == $this->_siteType && !$this->parseConfig( $arrZip, $_strDir.DIRECTORY_SEPARATOR.'template.xml' ) ) {
				$this->_error=array( '015' );
				return false;
			}
			// для NCSB, CNB и NVSB 
			if( in_array( $this->_siteType , array( Project_Sites::NCSB, Project_Sites::CNB, Project_Sites::NVSB ) ) && (!in_array('config.php', $_arrFiles ) && !in_array('feed.xml', $_arrFiles )) ){
				continue;
			}
			if( in_array( $this->_siteType , array( Project_Sites::NCSB, Project_Sites::NVSB, Project_Sites::CNB ) ) && !$this->parseDesc( $arrZip, $_strDir.DIRECTORY_SEPARATOR.'datas'.DIRECTORY_SEPARATOR.'desc'.DIRECTORY_SEPARATOR.'description.txt') ){
				$this->_error=array( '015' );
				return false;
			}
			// превьюха шаблона
			if ( is_file( $_strDir.DIRECTORY_SEPARATOR.$arrZip['screenshot'] ) ) {
				if ( !copy( $_strDir.DIRECTORY_SEPARATOR.$arrZip['screenshot'], $this->_extractDir.$arrZip['name_only'].'.jpg' ) ) {
					return false;
				}
			}
			// перепаковываем тему (файлы сразу в корне шаблона)
			if ( true!==$zip->open( $this->_extractDir.$arrZip['name'], ZipArchive::CREATE ) ) {
				$this->_error = array('016');
				return false;
			}
			if ( !$zip->addDirAndClose( $_strDir ) ) {
				$this->_error = array('017');
				return false;
			}
			return true;
		}
		$this->_error=array('014');
		return false;
	}
	
	public function template2edit( &$arrRes, $_intId ){
		
		$this->_extractDir='Project_Sites_Templates@template2edit';
		
		$this->onlyOne()->withIds( $_intId )->getList( $_arrTheme );
		
		if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $this->_extractDir ) ) {
			return false;
		}
		$zip=new Core_Zip();
		if ( !$zip->setDir( $this->_extractDir )->extractZip( $this->_userDir.$_arrTheme['filename'] ) ) {
			return false;
		}
		if ( !Core_Files::dirScan( $arrRes, $this->_extractDir ) ) {
			return false;
		}
		foreach ( $arrRes as $k1=>$v ){
			foreach ( $v as $k2=>$_file)
			if ( in_array( Core_Files::getExtension( $_file ), array( 'jpg', 'svn', 'png', 'gif' ) ) ){
				unset( $arrRes[$k1][$k2] );
			}
		}
		return true;
	}

	public function saveTemplate( $_intId, $_arrImg=array() ){
		$_extractDir=Zend_Registry::get( 'objUser' )->getTmpDirName();
		$_extractDir.='Project_Sites_Templates@template2edit'.DIRECTORY_SEPARATOR;
		
		if ( !$this->onlyOne()->withIds( $_intId )->getList( $_arrTheme ) ){
			return false;
		}
		// меняем header у шаблона. заточено под ncsb, nvsb, psb
		if( !empty( $_arrImg ) && in_array( Core_Files::getExtension( $_arrImg['name'] ), array('png','jpg','gif') ) ) {
			if ( !copy( $_arrImg['tmp_name'], $_extractDir . 'images'.DIRECTORY_SEPARATOR.'header.'.Core_Files::getExtension( $_arrImg['name'] ) ) ) {
				return false;
			}
		}
		// перепаковываем тему (файлы сразу в корне шаблона)
		$zip=new Core_Zip();
		if ( true!==$zip->open( $_extractDir.$_arrTheme['filename'], ZipArchive::CREATE ) ) {
			return false;
		}
		if ( !$zip->addDirAndClose( $_extractDir ) ) {
			return false;
		}
		if ( !copy($_extractDir.$_arrTheme['filename'],$this->_userDir.$_arrTheme['filename']) ){
			return false;
		}
		return true;
	}

	// настройки для getList
	private $_toSelect=false;
	private $_onlySiteId=0;
	private $_onlyIds=false; // массив с ids
	private $_onlyCount=false; // только количество
	private $_onlyCommon=false; // только общие
	private $_onlyOne=false; // только одна запись
	private $_withPreview=false; // с путями до картинки
	private $_withIds=0; // c данными id (array or int)
	private $_withPagging=array(); // постранично
	private $_withFilename=''; // c сортировкой
	private $_withTitle=''; // c именем шаблона
	private $_withOrder=array('p.priority--up','p.title--dn'); // c сортировкой
	private $_withSpots=false; // c саб массивом спотов для кождого шаблона
	private $_paging=array(); // инфа по навигации
	private $_cashe=array(); // закэшированный фильтр

	// сброс настроек после выполнения getArticles
	private function init() {
		$this->_toSelect=false;
		$this->_onlyIds=false;
		$this->_onlyCount=false;
		$this->_onlyCommon=false;
		$this->_onlyOne=false;
		$this->_withPreview=false;
		$this->_withIds=0;
		$this->_withPagging=array();
		$this->_withFilename='';
		$this->_withTitle='';
		$this->_withOrder=array('p.priority--up','p.title--dn');
		$this->_withSpots=false;
		$this->_onlySiteId=0;
	}

	public function toSetect() {
		$this->_toSelect=true;
		return $this;
	}

	public function onlySiteId( $_intId ){
		$this->_onlySiteId=intval( $_intId );
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

	// только общие шаблоны
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
	
	public function withTitle( $_str='' ) {
		$this->_withTitle=$_str;
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

	// возможно тут надо проверку на текущий тип сайта т.к. споты в бд хранятся только для PSB
	public function withSpots() {
		$this->_withSpots=true;
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
		if ( $this->_toSelect ) {
			$_crawler->set_select( 'p.id, p.title' );
		} else {
			$_crawler->set_select( 'p.*' );
		}
		$_crawler->set_from( $this->_tableTemplates.' p' );
		$_crawler->set_where( 'p.flg_type='.$this->_siteType ); // обязательное условие
		// в этом случае надо отображать только общие шаблоны на которые есть ссылка в $this->_tableLinkToUser, 
		// т.к. если сслки нет это означает что шаблон удалён, даже если есть в $this->_tablePlugins
		if ( $this->_onlyCommon ) {
			$_crawler->set_from( 'INNER JOIN '.$this->_tableLinkToUser.' lu ON lu.template_id=p.id' );
			$_crawler->set_where( 'p.flg_belong=0' );
		} elseif ( !empty( $this->_userId ) ) {
			$_crawler->set_from( 'INNER JOIN '.$this->_tableLinkToUser.' lu ON lu.template_id=p.id AND lu.user_id='.$this->_userId );
		}
		if ( !empty( $this->_onlySiteId ) ) {
			$_crawler->set_from( 'INNER JOIN '.$this->_tableLinkToSite.' lb ON lb.template_id=p.id AND lb.site_id='.$this->_onlySiteId.' AND lb.flg_type='.$this->_siteType );
		}
		if ( !empty( $this->_withFilename ) ) {
			$_crawler->set_where( 'p.filename='.Core_Sql::fixInjection( $this->_withFilename ) );
		}
		if ( !empty( $this->_withTitle ) ) {
			$_crawler->set_where( 'p.title='.Core_Sql::fixInjection( $this->_withTitle ) );
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
		if ( $this->_toSelect ){
			$mixRes=Core_Sql::getKeyVal( $_strSql );
		}elseif ( $this->_onlyIds ) {
			$mixRes=Core_Sql::getField( $_strSql );
		} elseif ( $this->_onlyCount ) {
			$mixRes=Core_Sql::getCell( $_crawler->get_result_counter() );
		} elseif ( $this->_onlyOne ) {
			$mixRes=Core_Sql::getRecord( $_strSql );
			$this->addPaths( $mixRes );
		} else {
			$mixRes=Core_Sql::getAssoc( $_strSql );
			$this->addPaths( $mixRes );
			$this->addSpots( $mixRes );
		}
		$this->init();
		return !empty( $mixRes );
	}

	private function addSpots( &$mixRes ) {
		if ( empty( $mixRes )||!$this->_withSpots ) {
			return;
		}
		foreach( $mixRes as $v ) {
			$_arrIds[]=$v['id'];
		}
		$this->_spots->getList( $_arrSpots, $_arrIds );
		foreach( $mixRes as $k=>$v ) {
			foreach( $_arrSpots as $ks=>$s ) {
				if ( $v['id']==$s['template_id'] ) {
					$mixRes[$k]['spots'][]=$s;
					unSet( $_arrSpots[$ks] ); // возможно это как-то ускорит процесс
				}
			}
		}
	}

	// html путь до картинки (preview) и системный путь до архива(path)
	private function addPaths( &$mixRes ) {
		if ( empty( $mixRes ) ) {
			return;
		}
		if ( $this->_onlyOne ) {
			if ( $this->_withPreview ) {
				if ( is_file( ( empty( $mixRes['flg_belong'] )? $this->_commonDir:$this->_userDir ) . Core_Files::getFileName( $mixRes['filename'] ).'.jpg' ) ) {
					$mixRes['preview']=( empty( $mixRes['flg_belong'] )? $this->_commonDirPreview:$this->_userDirPreview ).Core_Files::getFileName( $mixRes['filename'] ).'.jpg';
				}
			}
			$mixRes['path']=empty( $mixRes['flg_belong'] )? $this->_commonDir:$this->_userDir;
		} else {
			foreach( $mixRes as $k=>$v ) {
				if ( $this->_withPreview ) {
					if ( is_file( ( empty( $v['flg_belong'] )? $this->_commonDir:$this->_userDir ) . Core_Files::getFileName( $v['filename'] ).'.jpg' ) ) {
						$mixRes[$k]['preview']=( empty( $v['flg_belong'] )? $this->_commonDirPreview:$this->_userDirPreview ).Core_Files::getFileName( $v['filename'] ).'.jpg';
					}
				}
				$mixRes[$k]['path']=empty( $v['flg_belong'] )? $this->_commonDir:$this->_userDir;
			}
		}
	}
}
?>