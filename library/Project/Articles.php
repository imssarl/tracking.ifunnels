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
 * Управление статьями и пользовательский интерфейс
 *
 * @category Project
 * @package Project_Articles
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Articles extends Core_Storage implements Project_Content_Interface {

	private $_fields=array( 'id', 'category_id', 'source_id', 'flg_status', 'title', 'author', 'summary', 'body', 'date', 'user_id', 'edited', 'added' );
	public $table='hct_am_article';
	protected $_tableKeyword='hct_am_article_keywords';
	private $_userId=0;

	protected $_withTags=false; // поиск по тегам
	protected $_withCategory=array(); // c данными категориями
	protected $_toJs=false; // для JavaScript
	protected $_onlyLast=false; // последние созданные

	private static $_instance=NULL;
	private $_userTmpDir='';

	public function __construct(  $_withoutUser=false ){
		if ( $_withoutUser ) { // в этом случае корректная работа обеспечена только для геттеров
			return;
		}
		if ( !Zend_Registry::get( 'objUser' )->getId( $this->_userId ) ) {
			throw new Exception( Core_Errors::DEV.'|Zend_Registry::get( \'objUser\' )->getId( $_int ) is not return an User Id' );
			return;
		}
		if ( !Zend_Registry::get( 'objUser' )->checkTmpDir( $this->_userTmpDir ) ) {
			throw new Exception( Core_Errors::DEV.'|Zend_Registry::get( \'objUser\' )->checkTmpDir( $_tmpDir ) no _userTmpDir set' );
			return;
		}
	}
	public function setFilter( $_arrFilter=array() ){}

	public function setCounter( $_intCounter ){}

	public function setLimited( $_intLimited ){}

	public function getFilter( &$arrRes ){}

	public static function getInstance(){
		if ( self::$_instance==NULL ) {
			self::$_instance=new Project_Articles( true );
		}
		return self::$_instance;
	}

	public function set(){
		if ( !$this->_data->setFilter( array( 'trim', 'clear' ) )->setChecker( array(
			'category_id'=>empty( $this->_data->filtered['category_id'] ),
			'source_id'=>empty( $this->_data->filtered['source_id'] ),
			'author'=>empty( $this->_data->filtered['author'] ),
			'author'=>empty( $this->_data->filtered['author'] ),
			'body'=>empty( $this->_data->filtered['body'] ),
			'title'=>empty( $this->_data->filtered['title'] ),
		) )->check() ) {
			$this->_data->getErrors( $this->_errors );
			return false;
		}
		if ( empty( $this->_data->filtered['id']) ) {
			$this->_data->setElements( array(
				'user_id'=>$this->_userId,
				'added'=>time(),
				'edited'=>time(),
			) );
			$tags=new Core_Tags('articles');
		} else {
			$this->_data->setElement( 'edited', time() );
		}
		$this->_data->filtered['flg_status']=empty( $this->_data->filtered['flg_status'] ) ? 0:1;
		// надо это дело перенести в шаблоны? TODO 5.04.2011
		if ( empty(  $this->_data->filtered['summary'] ) ) {
			$_obj=Core_String::getInstance(  $this->_data->filtered['body'] );
			$this->_data->filtered['summary']=$_obj->setNeedLength( 200 )->fullWords()? $_obj->getResult().' ...':$_obj->getResult();
		}
		$this->_data->setElement( 'id', Core_Sql::setInsertUpdate( $this->table, $this->_data->setMask( $this->_fields )->getValid() ) );
		if( is_object($tags) && !empty($this->_data->filtered['tags']) ){
			if( !$tags->setItem( $this->_data->filtered['id'] )->setTags( $this->_data->filtered['tags'] )->set() ){
				return false;
			}
		}
		return true;
	}

	public function import( &$strJsonArticles = array() , $_arrData=array(), $_arrFile=array() ){
		$arrArticles = array();
		if ( $_arrData['article_source'] == 'text_file' ) { // if text files
			foreach ( $_arrFile['tmp_name']['file'] as $key=>$file ) {
				if( !strpos($_arrFile['name']['file'][$key],'.txt') ){
					continue;
				}
				if ( !$this->prepareArticlesFromFile( $_arrRes, $file, $_arrData['category'][$key], $_arrData ) ) {
					continue;
				}
				$arrRes[]=$_arrRes;
			}
			$arrArticles[] = $arrRes;
		}

		if ( $_arrData['article_source'] == 'zip_file' ) { // if zip files
			set_time_limit(0);
			ignore_user_abort(true);
			foreach ( $_arrFile['tmp_name']['file'] as $key=>$file ) {
				if( !strpos($_arrFile['name']['file'][$key],'.zip') ){
					continue;
				}
				$this->extractZip( $arrRes, $file, $_arrData['category'][$key], $_arrData );
				$arrArticles[] = $arrRes;
			}
		}

		if ($_arrData['article_source'] == 'manually') { // if from post
			$_obj=Core_String::getInstance( $_arrData['manually']['text'] );
			$arrArticles[] = array(
				array(
					'title' 		=> $_arrData['manually']['title'],
					'summary' 		=> ( $_obj->setNeedLength( 200 )->fullWords()? $_obj->getResult().' ...':$_obj->getResult() ),
					'body' 			=> $_arrData['manually']['text'],
					'category_id'	=> $_arrData['manually']['category'],
					'user_id' 		=> $this->_userId,
					'source_id'		=> $_arrData['source'],
					'flg_status'	=> $_arrData['status'],
					'author' 		=> $_arrData['author'],
					'date'			=> date( 'Y-m-d' ),
					'edited'		=> time(),
					'added'			=> time(),
				)
			);
		}
		$_arrJson=array();
		foreach ($arrArticles as &$item) {
			if ( empty( $item ) ) {
				continue;
			}
			foreach ($item as &$article) {
				// надо путсить через Core_String каким-то образом TODO!!! 01.06.2010
				$article['title'] = preg_replace('/[^a-z\sA-Z|`\'\"\:-_,\.\!\?\(\)\[\]0-9]/si','',iconv("",'UTF-8//IGNORE',$article['title']));
				if ( empty($article['title']) ) {
					continue;
				}
				$article['id']=Core_Sql::setInsert( $this->table, $article );
				if ( empty( $article['id'] ) ) {
					continue;
				}
				$_arrJson[]=array(
					'id'=>$article['id'],
					'category'=>$article['category_id'],
					'title'=>addslashes($article['title']),
					'source'=>$article['source_id']
				);
			}
		}
		$strJsonArticles = Zend_Registry::get( 'CachedCoreString' )->php2json( $_arrJson );
		return !empty( $_arrJson );
	}

	public function del( $_mixId=array() ) {
		if ( empty( $_mixId ) ) {
			return false;
		}
		Core_Sql::setExec( '
			DELETE a, k
			FROM '.$this->table.' a
			LEFT JOIN '.$this->_tableKeyword.' k ON a.id=article_id
			WHERE
				a.id IN('.Core_Sql::fixInjection( $_mixId ).') AND
				a.user_id='.$this->_userId
		);
		return true;
	}

	public function dupArticle( $_intId=0 ) {
		if ( !$this->onlyOne()->withIds( array($_intId) )->getList( $_arrRes ) ) {
			return false;
		}
		unSet( $_arrRes['id'] );
		if ( !$this->setData( $_arrRes )->set() ) {
			return false;
		}
		return true;
	}

	public function withCategory( $_arrIds=array() ) {
		$this->_withCategory=$_arrIds;
		return $this;
	}

	public function toJs() {
		$this->_toJs=true;
		return $this;
	}

	public function getOwnerId(){
		return $this->_userId;
	}

	public function onlyLast() {
		$this->_onlyLast=true;
		return $this;
	}

	public function withTags( $_str ){
		if( empty($_str) ){
			return $this;
		}
		$this->_withTags=$_str;
		return $this;
	}

	protected function init(){
		$this->_onlyLast=false;
		$this->_toJs=false;
		$this->_withCategory=false;
		parent::init();
	}

	protected function assemblyQuery() {
		if ( $this->_onlyIds ) {
			$this->_crawler->set_select( 'd.id' );
		} elseif ( $this->_toSelect || $this->_toJs ) {
			$this->_crawler->set_select( 'd.id, d.title' );
		} elseif( $this->_onlyOne ) {
			$this->_crawler->set_select( 'd.*' );
		} else {
			$this->_crawler->set_select( 'd.id, d.flg_status, d.title, d.body, d.author, SUBSTRING(d.summary FROM 1 FOR 100) summary, d.date' );
			$this->_crawler->set_select( 'c.title category_title, s.title source_title' );
		}
		$this->_crawler->set_from( $this->table.' d' );
		$this->_crawler->set_from( 'INNER JOIN category_category c ON c.id=d.category_id' );
		$this->_crawler->set_from( 'INNER JOIN category_cat2flag f ON f.cat_id=d.category_id AND f.flag_id=(SELECT id FROM category_flags WHERE title=\'active\' AND type_id=c.type_id)' );
		$this->_crawler->set_from( 'INNER JOIN category_category s ON s.id=d.source_id' );
		if ( !empty( $this->_userId ) ) {
			$this->_crawler->set_where( 'd.user_id='.$this->_userId );
		}
		if ( !empty( $this->_withTags ) ) {
			$tags=new Core_Tags('articles');
			$tags->setTags( $this->_withTags )->getSearchQuery( $_strSql );
			$this->_crawler->set_where( 'd.id IN ('.$_strSql.')' );
		}
		if ( !empty( $this->_withCategory ) ) {
			$this->_crawler->set_where( 'd.category_id IN('.Core_Sql::fixInjection( $this->_withCategory ).')' );
		}
		if ( !empty( $this->_withIds ) ) {
			$this->_crawler->set_where( 'd.id IN('.Core_Sql::fixInjection( $this->_withIds ).')' );
		}
		if ( !empty( $this->_onlyLast ) ) {
			$this->_crawler->set_where( 'd.added = (SELECT added FROM '.$this->table.' WHERE user_id='. $this->_userId .' ORDER BY added DESC LIMIT 1 )' );
		}
		if ( !( $this->_onlyOne||$this->_onlyCell ) ) {
			$this->_crawler->set_order_sort( $this->_withOrder );
		}
	}

	public function getAdditional( &$arrRes ) {
		$category=new Project_Articles_Category();
		$category->withFlags(array('active'))->toSelect()->get( $arrRes['category'], $_arrTmp );
		$source=new Core_Category( 'Article Manager Source' );
		$source->toSelect()->get( $arrRes['source'], $_arrTmp );
	}

	private function extractZip( &$arrRes, $strFile, $idCategory, $_arrData ){
		$driver = new Core_Media_Driver();
		$_strExtractDir = $this->_userTmpDir . 'Project_Articles@extractZip' . DIRECTORY_SEPARATOR;
		if ( !$driver->prepareTmpDir( $_strExtractDir ) ) {
			return false;
		}
		$zip = new Core_Zip();
		if ( true != $zip->open( $strFile ) ) {
			return false;
		}
		$_bool=$zip->extractTo( $_strExtractDir );
		$zip->close();
		if( !$_bool ){
			return false;
		}
		$files=$driver->dirScan( $_strExtractDir );
		if ( empty( $files ) ){
			return false;
		}
		$files=array_shift( $files );
		foreach ( $files as $file ){
			$driver->d_get_extension( $file );
			if( $driver->m_sys_ext!='txt'||!$driver->d_get_filename( $file ) ) {
				continue;
			}
			if ( !$this->prepareArticlesFromFile( $_arrRes, $_strExtractDir.$file, $idCategory, $_arrData ) ) {
				continue;
			}
			$arrRes[]=$_arrRes;
		}
		return true;
	}

	public function export( &$strZipFile, $_arrIds=array(), $_arrArticles=array() ) {
		if ( !$this->flushingToFiles( $strDir, $_arrIds, $_arrArticles) ) {
			return false;
		}
		if ( !$this->createZip( $strZipFile, $strDir ) ) {
			return false;
		}
		return true;
	}

	private function flushingToFiles( &$strDir, $_arrIds=array(), $_arrRes=array() ) {
		if ( empty( $_arrIds ) && empty( $_arrRes ) ) {
			return false;
		}
		$strDir=$this->_userTmpDir.'Project_Articles@flushingToFiles'.DIRECTORY_SEPARATOR;
		$driver = new Core_Media_Driver();
		if ( !$driver->prepareTmpDir( $strDir ) ) {
			return false;
		}
		if ( empty( $_arrRes ) ) {
			if (!$this->withId( $_arrIds )->getList( $_arrRes )){
				return false;
			}
		}
		foreach( $_arrRes as $v ) {
			$_strTitle=Core_String::getInstance( $v['title'] )->toSystem( '_' );
			if ( empty( $_strTitle ) ) {
				$_strTitle='no_valid_name_'.Core_A::rand_uniqid();
			}
			file_put_contents( $strDir.$_strTitle.'.txt', trim( $v['title'] )."\n".trim( $v['author'] )."\n\n".trim( $v['body'] ) );
		}
		return true;
	}

	private function createZip( &$strFile, $_strFilesDir='' ) {
		if ( empty( $_strFilesDir ) ) {
			return false;
		}
		$_strDir=$this->_userTmpDir.'Project_Articles@createZip'.DIRECTORY_SEPARATOR;
		$driver = new Core_Media_Driver();
		if ( !$driver->prepareTmpDir( $_strDir ) ) {
			return false;
		}
		$strFile=$_strDir.'article_export_'.date( "Y_m_d_H_i_s" ).'.zip';
		$_zip=new Core_Zip();
		if ( true!==$_zip->open( $strFile, ZipArchive::CREATE ) ) {
			return false;
		}
		return $_zip->addDirAndClose( $_strFilesDir );
	}

	private function prepareArticlesFromFile( &$arrRes, $_strFile, $idCategory, $_arrData ) {
		$_strContent=file_get_contents( $_strFile );
		if ( $_strContent===false ) {
			return false;
		}
		$arrRes=array(
			'date'=>date( 'Y-m-d' ),
			'category_id'=>$idCategory,
			'user_id'=>$this->_userId,
			'flg_status'=>$_arrData['status'],
			'source_id'=>$_arrData['source'],
			'author'=>$_arrData['author'],
		);
		$_structFile=explode( "\n", str_replace( array( "–", "“", "”", "’","'","\r" ), array( '-', '"', '"', "`",'`','' ), $_strContent ) );
		$_obj=Core_String::getInstance( array_shift( $_structFile ) );
		$_obj->setNeedLength( 100 )->fullWords(); // первую строчку в обработку как title
		$arrRes['title']=$_obj->getResult(); // получаем разультат с изменениями или без
		array_shift( $_structFile ); // убираем пустую строчку
		$_obj=Core_String::getInstance( implode("\n", $_structFile ) );
		$arrRes['summary']=$_obj->setNeedLength( 200 )->fullWords()? $_obj->getResult().' ...':$_obj->getResult();
		$arrRes['body']=$_obj->getSource(); // боди забираем перекодированным в утф8
		$arrRes['edited']=$arrRes['added']=time();
		return true;
	}
}
?>