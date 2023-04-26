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
class Project_Sites_Type_Ncsb extends Project_Sites_Type_Abstract {

	protected $_withOrder='edited--up';

	protected $_table='es_ncsb';

	protected static $_lastUrls=array();

	protected $_fields=array( 
		'id', 'category_id', 'flg_damas', 'google_analytics', 'main_keyword', 
		'ftp_host', 'ftp_username', 'ftp_password', 'ftp_directory', 'url', 'user_id', 'flg_snippet',
		'damas_ids', 'snippet_number', 'snippet_length', 'navigation_length', 'catedit', 'added', 'edited' );

	public function del( $_arrIds ) {
		// споты
		$options=new Project_Options(Project_Sites::NCSB);
		foreach( $_arrIds as $intId ) {
			$options->setSiteId( $intId )->clearOptions();
		}
		// ссылки на шаблоны
		$_templates=new Project_Sites_Templates( Project_Sites::NCSB );
		$_templates->siteLink( $_arrIds );
		// syndication
		Project_Syndication_Sites::setOutside( $_arrIds, Project_Sites::NCSB );
		// сами сайты
		Core_Sql::setExec( 'DELETE FROM '.$this->_table.' WHERE id IN('.Core_Sql::fixInjection( $_arrIds ).')' );
		return true;
	}

	public function get( &$arrRes, $_arrSite=array() ) {
		$arrRes['arrNcsb']=$arrRes['arrOpt']=$_arrSite;
		$arrRes['arrFtp']=array(
			'address'=>$_arrSite['ftp_host'],
			'username'=>$_arrSite['ftp_username'],
			'password'=> $_arrSite['ftp_password'],
			'directory'=>$_arrSite['ftp_directory']);
		if( !Project_Articles_Links::getIds( $arrRes['strJson'], $arrRes['arrNcsb']['id'], Project_Sites::NCSB  )) {
			$arrRes['strJson']=false;
		}
		$arrRes['strJson']=Zend_Registry::get( 'CachedCoreString' )->php2json($arrRes['strJson']);
		$arrRes['arrNcsb']['syndication']=Project_Syndication_Sites::isSyndicated( $_arrSite['id'], Project_Sites::NCSB ); // syndication
		return true;
	}

	public function import( Project_Sites $object ) {
		$this->data=new Core_Data( $object->data->setFilter( array( 'stripslashes', 'trim', 'clear' ) )->getRaw( 'arrNcsb' ) );
		if ( !$this->data
			->setElements( array(
				'ftp_host'=>$object->data->filtered['arrFtp']['address'],
				'ftp_username'=>$object->data->filtered['arrFtp']['username'],
				'ftp_password'=>$object->data->filtered['arrFtp']['password'],
				'ftp_directory'=>$object->data->filtered['arrFtp']['directory'], ) )
			->setChecker( array(
				'ftp_host'=>empty( $this->data->filtered['ftp_host'] ),
				'ftp_username'=>empty( $this->data->filtered['ftp_username'] ),
				'ftp_password'=>empty( $this->data->filtered['ftp_password'] ),
				'ftp_directory'=>empty( $this->data->filtered['ftp_directory'] ),
				'category_id'=>empty( $this->data->filtered['category_id'] ),
				'url'=>empty( $this->data->filtered['url'] ),
				'google_analytics'=>empty( $this->data->filtered['google_analytics'] ),
				'main_keyword'=>empty( $this->data->filtered['main_keyword'] ), ) )
			->check() ) {
			$this->data->getErrors( $this->_errors['arrNcsb'] );
			return false;
		}
		// исправляем ссылку если нет закрывающего слэша
		if ( substr( $this->data->filtered['url'], -1 )!='/' ) {
			$this->data->setElement( 'url', $this->data->filtered['url'].'/' );
		}
		if ( substr( $this->data->filtered['url'], 0, 7)!='http://' ) {
			$this->data->setElement( 'url', 'http://'.$this->data->filtered['url'] );
		}
		$_connector=new Project_Sites_Connector();
		if ( !$_connector
			->setHttpUrl( $this->data->filtered['url'] )
			->setHost( $this->data->filtered['ftp_host'] )
			->setUser( $this->data->filtered['ftp_username'] )
			->setPassw( $this->data->filtered['ftp_password'] )
			->setRoot( $this->data->filtered['ftp_directory'] )
			->checkFtpAccessibility() ) {
			$this->_errors['connect'] = 'can not connect to ftp server ' . $this->data->filtered['ftp_host'] ;
			return false;
		}
		$this->data->setElements( array(
			'user_id'=>$this->_userId,
			'added'=>time(),
			'edited'=>time(),
		) );
		return $this->saveRec();
	}

	public function set( Project_Sites $object ) {
		$this->data=new Core_Data( $object->data->setFilter( array( 'trim', 'clear' ) )->getRaw( 'arrNcsb' ) );
		if ( !$this->data
			->setElements( array(
				'arrArticleIds'=>$object->data->filtered['multibox_ids_content_wizard'],
				'ftp_host'=>$object->data->filtered['arrFtp']['address'],
				'ftp_username'=>$object->data->filtered['arrFtp']['username'],
				'ftp_password'=>$object->data->filtered['arrFtp']['password'],
				'ftp_directory'=>$object->data->filtered['arrFtp']['directory'], ) )
			->setChecker( array(
				'ftp_host'=>empty( $this->data->filtered['ftp_host'] ),
				'ftp_username'=>empty( $this->data->filtered['ftp_username'] ),
				'ftp_password'=>empty( $this->data->filtered['ftp_password'] ),
				'ftp_directory'=>empty( $this->data->filtered['ftp_directory'] ),
				'template_id'=>empty( $this->data->filtered['template_id'] ),
				'category_id'=>empty( $this->data->filtered['category_id'] ),
				'url'=>empty( $this->data->filtered['url'] ),
				'google_analytics'=>empty( $this->data->filtered['google_analytics'] ),
				'main_keyword'=>empty( $this->data->filtered['main_keyword'] ),
				'navigation_length'=>empty( $this->data->filtered['navigation_length'] ),
				'flg_snippet'=>empty( $this->data->filtered['flg_snippet'] ),
				'articles'=>empty( $this->data->filtered['arrArticleIds'] ), ) )
			->check() ) {
			$this->data->getErrors( $this->_errors['arrNcsb'] );
			return false;
		}
		// исправляем ссылку если нет закрывающего слэша
		if ( substr( $this->data->filtered['url'], -1 )!='/' ) {
			$this->data->setElement( 'url', $this->data->filtered['url'].'/' );
		}
		if ( substr( $this->data->filtered['url'], 0, 7)!='http://' ) {
			$this->data->setElement( 'url', 'http://'.$this->data->filtered['url'] );
		}
		if ( empty( $this->data->filtered['id'] ) ) {
			$this->data->setElements( array(
				'user_id'=>$this->_userId,
				'added'=>time(),
			) );
		}
		foreach( $this->data->filtered['arrArticleIds'] as $item ) {
			$_arrIds[]=$item['id'];
		}
		$this->data->setElements( array(
			'edited'=>time(),
			'arrArticleIds'=>$_arrIds,
			'damas_ids'=>(!empty( $object->data->filtered['dmascodetext'] )? $object->data->filtered['dmascodetext']:''),
			'flg_damas'=>(!empty( $object->data->filtered['headlines_spot1'] )? $object->data->filtered['headlines_spot1']:0),
			'snippet_number'=>(!empty( $this->data->filtered['snippet_number'] )? $this->data->filtered['snippet_number']:5),
			'snippet_length'=>(!empty( $this->data->filtered['snippet_length'] )? $this->data->filtered['snippet_length']:250),
			'flg_snippet'=>(( $this->data->filtered['flg_snippet'] == 'no')? 0:1),
		) );
		$this->_optData=$object->data->getRaw( 'arrOpt' );
		if ( !$this->upload() ) {
			return false;
		}
		if ( !$this->saveRec() ) {
			return false;
		}
		// линк на шаблон
		$_templates=new Project_Sites_Templates( Project_Sites::NCSB );
		if ( !$_templates->siteLink( $this->data->filtered['id'], $this->data->filtered['template_id'] ) ) {
			return false;
		}		
		// добавить в Project_Content_Interface и функционал в классы TODO!!! 24.01.2011
		if ( !Project_Articles_Links::saveIds( $this->data->filtered['arrArticleIds'], $this->data->filtered['id'], Project_Sites::NCSB  ) ){
			$this->_errors['articles_wrong_insert']=true;
		}
		$_opt=new Project_Options(  Project_Sites::NCSB , $this->data->filtered['id'] );
		if ( !$_opt->setData( $this->_optData )->set()){
			return false;
		}
		return true;
	}

	private function saveRec() {
		$this->data->setElement( 'id', Core_Sql::setInsertUpdate( $this->_table, $this->data->setMask( $this->_fields )->getValid() ) );
		if ( empty( $this->data->filtered['id'] ) ) {
			return false;
		}
		Project_Syndication_Sites::setOutside( $this->data->filtered['id'], Project_Sites::NCSB, empty( $this->data->filtered['syndication'] ) ); // Syndication
		return true;
	}

	protected function upload() {
		if ( !$this->prepareSource() ) {
			return false;
		}
		$_connector=new Project_Sites_Connector();
		return $_connector
			->setSourceDir( $this->_dir )
			->setHttpUrl( $this->data->filtered['url'] )
			->setHost( $this->data->filtered['ftp_host'] )
			->setUser( $this->data->filtered['ftp_username'] )
			->setPassw( $this->data->filtered['ftp_password'] )
			->setRoot( $this->data->filtered['ftp_directory'] )
			->upload();
	}

	public function prepareSource() {
		$this->_dir='Project_Sites_Type_Ncsb@prepareSource';
		if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $this->_dir ) ) {
			$this->_errors[] = 'Process Aborted. Can\'t create dir Project_Sites_Type_Ncsb@prepareSource';
			return false;
		}
		if ( !$this->getTemplate() ) {
			$this->_errors[] = 'Process Aborted. Can\'t get template';
			return false;
		}
		if ( !$this->patchFiles() ) {
			$this->_errors[] = 'Process Aborted. Can\'t patch files';
			return false;
		}
		if ( !$this->generateArticles() ) {
			$this->_errors[] = 'Process Aborted. Can\'t generate articles';
			return false;
		}
		return true;
	}

	private function getTemplate() {
		$_template=new Project_Sites_Templates( Project_Sites::NCSB );
		if ( !$_template->onlyOne()->withIds( $this->data->filtered['template_id'] )->getList( $_arrTemplate ) ) {
			return false;
		}
		return Core_Zip::getInstance()
			->setDir( $this->_dir )
			->extractZip( $_arrTemplate['path'].$_arrTemplate['filename'] );
	}

	private function patchFiles() {
		// dams
		$_strCode=Project_Options_GetCode::getDamsPhpCode( $this->_optData );
		$_strFile=file_get_contents( $this->_dir.'damscode.php' );
		$_arrFiles['damscode.php']=str_replace( '<damscode>', (empty( $_strCode )?'':$_strCode), $_strFile );
		// spots
		$_arrCode=Project_Options_GetCode::getSpotsCode( $this->_optData );
		// spot1
		$_strFile=file_get_contents( $this->_dir.'mainads.php' );
		if ( empty( $_arrCode['spot1'] ) ) { // if defult then $_arrCode['spot1']==false а также его может и не быть
			$_arrFiles['mainads.php']=str_replace( array( '<spot1>', '<default1>', '</default1>' ), array( '', '', '' ), $_strFile );
		} else {
			$_strDefaultCode=substr( $_strFile, stripos( $_strFile, '<default1>' ), stripos( $_strFile, '</default1>' ) + 3 );
			$_arrFiles['mainads.php']=str_replace( array( '<spot1>', $_strDefaultCode ), array( '', $_arrCode['spot1'] ), $_strFile );
		}
		// spot2
		$_strFile=file_get_contents( $this->_dir.'sideads.php' );
		if ( empty( $_arrCode['spot2'] ) ) {
			$_strFile=str_replace( array( '<spot2>', '<default2>', '</default2>' ), array( '', '', '' ), $_strFile );
		} else {
			$strposStart=stripos( $_strFile, '<default2>' );
			$strposEnd=stripos( $_strFile, '</default2>' );
			$_strDefaultCode=substr( $_strFile, $strposStart , $strposEnd - $strposStart + 11 );
			$_strFile=str_replace( array( '<spot2>', $_strDefaultCode ), array( '', $_arrCode['spot2'] ), $_strFile );
		}
		// spot3
		if ( empty( $_arrCode['spot3'] ) ) {
			$_strFile=str_replace( array( '<spot3>', '<default3>', '</default3>' ), array( '', '', '' ), $_strFile );
		} else {
			$strposStart=stripos( $_strFile, '<default3>' );
			$strposEnd=stripos( $_strFile, '</default3>' );			
			$_strDefaultCode=substr( $_strFile,  $strposStart , $strposEnd - $strposStart + 11 );
			$_strFile=str_replace( array( '<spot3>', $_strDefaultCode ), array( '', $_arrCode['spot3'] ), $_strFile );
		}
		$_arrFiles['sideads.php']=$_strFile;
		// config
		$_strFile=file_get_contents( $this->_dir.'config.php' );
		$_bool=(empty( $this->data->filtered['flg_snippet'] )||$this->data->filtered['flg_snippet']=='no'); // выбрали Full Article (display random article ...
		$_arrFiles['config.php']=str_replace( array(
			'$$$snippet$$$',
			'$$$nrsnippet$$$',
			'$$$lsnippet$$$',
			'$$$navigation$$$',
			'$$$adsense$$$',
			'$$$keyword$$$',
		), array(
			( $_bool? '0':'1' ),
			( $_bool? '5':$this->data->filtered['snippet_number'] ),
			( $_bool? '250':$this->data->filtered['snippet_length'] ),
			$this->data->filtered['navigation_length'],
			$this->data->filtered['google_analytics'],
			$this->data->filtered['main_keyword']
		), $_strFile );
		// сохраняем
		return Core_Files::setContentMass( $_arrFiles, $this->_dir );
	}

	protected function generateArticles() {
		if ( !Project_Articles::getInstance()->withIds( $this->data->filtered['arrArticleIds'] )->getContent( $_arrContent ) ) {
			$this->_errors['articles']='Process Aborted. Unable to collect articles';
			return false;
		}
		$_strDir=$this->_dir.'datas'.DIRECTORY_SEPARATOR.'articles'.DIRECTORY_SEPARATOR;
		foreach( $_arrContent as $v ) {
			$_strContent=$v['title']."\n".$v['author']."\n".$v['body'];
			$_strFileName=Core_String::getInstance( strtolower( strip_tags( $v['title'] ) ) )->toSystem( '-' ).'.txt';
			if ( !Core_Files::setContent( $_strContent, $_strDir.$_strFileName ) ) {
				$this->_errors['articles']='Process Aborted. Unable to save articles';
				return false;
			}
			$_arrFiels[]=$_strFileName;
		}
		$_strFiles=serialize($_arrFiels);
		Core_Files::setContent( $_strFiles, $this->_dir.'articles-list.txt');
		return true;
	}

	protected function setLinks( $_sheduleId, $_strFilename ){
		self::$_lastUrls[] = array('shedule_id'=>$_sheduleId, 'url'=> $this->data->filtered['url'] . Core_Files::getFileName($_strFilename) .'.html' );
	}

	public static function getLastUrls(){
		return self::$_lastUrls;
	}
}
?>