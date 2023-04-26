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
class Project_Sites_Type_Psb extends Project_Sites_Type_Abstract {

	protected $_withOrder='edited--up';

	protected $_table='es_psb';

	protected static $_lastUrls=array();

	protected $_fields=array( 
		'id', 'user_id', 'category_id', 'profile_id', 
		'result_num', 'flg_articles', 'flg_damas', 'damas_ids', 'url', 'main_keyword', 'google_analytics', 
		'ftp_host', 'ftp_username', 'ftp_password', 'ftp_directory', 'catedit', 'edited', 'added' );

	public function del( $_arrIds ) {
		// споты
		$options=new Project_Options(Project_Sites::PSB);
		foreach( $_arrIds as $intId ) {
			$options->setSiteId( $intId )->clearOptions();
		}
		// ссылки на шаблоны
		$_templates=new Project_Sites_Templates( Project_Sites::PSB );
		$_templates->siteLink( $_arrIds );
		// syndication
		Project_Syndication_Sites::setOutside( $_arrIds, Project_Sites::PSB );
		// сами сайты
		Core_Sql::setExec( 'DELETE FROM '.$this->_table.' WHERE id IN('.Core_Sql::fixInjection( $_arrIds ).')' );
		return true;
	}

	public function get( &$arrRes, $_arrSite=array() ) {
		$arrRes['arrPsb']=$arrRes['arrOpt']=$_arrSite;
		$arrRes['arrFtp']=array(
			'address'=>$_arrSite['ftp_host'],
			'username'=>$_arrSite['ftp_username'],
			'password'=> $_arrSite['ftp_password'],
			'directory'=>$_arrSite['ftp_directory']);
		if( !Project_Articles_Links::getIds( $arrRes['strJson'], $_arrSite['id'], Project_Sites::PSB ) ) {
			$arrRes['strJson']=false;
		}
		$arrRes['strJson']=Zend_Registry::get( 'CachedCoreString' )->php2json($arrRes['strJson']);
		$arrRes['arrPsb']['syndication']=Project_Syndication_Sites::isSyndicated( $_arrSite['id'], Project_Sites::PSB ); // syndication
		return true;
	}

	public function import( Project_Sites $object ) {
		$this->data=new Core_Data( $object->data->setFilter( array( 'stripslashes', 'trim', 'clear' ) )->getRaw( 'arrPsb' ) );
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
				'main_keyword'=>empty( $this->data->filtered['main_keyword'] ), ) )
			->check() ) {
			$this->data->getErrors( $this->_errors['arrPsb'] );
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
		$this->data=new Core_Data( $object->data->setFilter( array( 'stripslashes', 'trim', 'clear' ) )->getRaw( 'arrPsb' ) );
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
				'flg_articles'=>!empty( $this->data->filtered['flg_articles'] )&&empty( $this->data->filtered['arrArticleIds'] ),
				'main_keyword'=>empty( $this->data->filtered['main_keyword'] ), ) )
			->check() ) {
			$this->data->getErrors( $this->_errors['arrPsb'] );
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
		$_arrIds=array();
		if ( !empty( $this->data->filtered['flg_articles'] ) ) {
			foreach( $this->data->filtered['arrArticleIds'] as $item ) {
				$_arrIds[]=$item['id'];
			}
		}
		$this->data->setElements( array(
			'edited'=>time(),
			'result_num'=>(empty( $this->data->filtered['result_num'] )? 10:$this->data->filtered['result_num']),
			'flg_gastatus'=>(empty( $this->data->filtered['flg_gastatus'] )? 0:1),
			'flg_articles'=>(empty( $this->data->filtered['flg_articles'] )? 0:1),
			'arrArticleIds'=>$_arrIds,
			'flg_damas'=>(!empty( $object->data->filtered['headlines_spot1'] )? $object->data->filtered['headlines_spot1']:0),
			'damas_ids'=>(!empty( $object->data->filtered['dmascodetext'] )? $object->data->filtered['dmascodetext']:''),
		) );
		$this->_optData=$object->data->getRaw( 'arrOpt' );
		$_spots = new Project_Sites_Spots( Project_Sites::PSB );
		$_spots->getList( $this->_arrSpots, $this->data->filtered['template_id'] );
		if ( !$this->upload() ) {
			return false;
		}
		if ( !$this->saveRec() ) {
			return false;
		}
		// линк на шаблон
		$_templates=new Project_Sites_Templates( Project_Sites::PSB );
		if ( !$_templates->siteLink( $this->data->filtered['id'], $this->data->filtered['template_id'] ) ) {
			return false;
		}
		$this->setArticles();
		// с этим тоже надо что-то делать. хотя незнаю что
		$_opt=new Project_Options(  Project_Sites::PSB , $this->data->filtered['id'] );
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
		Project_Syndication_Sites::setOutside( $this->data->filtered['id'], Project_Sites::PSB, empty( $this->data->filtered['syndication'] ) ); // Syndication
		return true;
	}

	private function setArticles() {
		if ( empty( $this->data->filtered['flg_articles'] ) ) { // отлинковываем статьи
			Project_Articles_Links::delete( $this->data->filtered['id'], Project_Sites::PSB );
			return;
		}
		// добавить в Project_Content_Interface и функционал в классы TODO!!! 24.01.2011
		if ( !Project_Articles_Links::saveIds( $this->data->filtered['arrArticleIds'], $this->data->filtered['id'], Project_Sites::PSB ) ){
			$this->_errors['articles_wrong_insert']=true;
		}
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
		$this->_dir='Project_Sites_Type_Psb@prepareSource';
		if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $this->_dir ) ) {
			$this->_errors[] = 'Process Aborted. Can\'t create dir Project_Sites_Type_Psb@prepareSource';
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
		$_template=new Project_Sites_Templates( Project_Sites::PSB );
		if ( !$_template->onlyOne()->withIds( $this->data->filtered['template_id'] )->getList( $_arrTemplate ) ) {
			return false;
		}
		return Core_Zip::getInstance()
			->setDir( $this->_dir )
			->extractZip( $_arrTemplate['path'].$_arrTemplate['filename'] );
	}

	private function patchFiles() {
		$_arrFiles=array();
		// dams
		$_strCode=Project_Options_GetCode::getDamsPhpCode( $this->_optData );
		$_arrFiles['damscode.php']=str_replace( '<damscode>', (empty( $_strCode )?'':$_strCode), file_get_contents( $this->_dir.'damscode.php' ) );
		// spots
		$_arrCode=Project_Options_GetCode::getSpotsCode( $this->_optData );
		foreach( $this->_arrSpots as $v ) {
			if ( !empty( $_arrFiles[$v['filename']] ) ) {
				continue;
			}
			$_arrFiles[$v['filename']]=file_get_contents( $this->_dir.$v['filename'] );
		}
		foreach( $this->_arrSpots as $v ) {
			if ( !preg_match( '/\d{1,}$/i', $v['name'], $_arrMatch ) ) {
				continue;
			}
			// если спот не выбран остаётся код поумолчанию если он есть
			if ( empty( $_arrCode[$v['name']] ) ) {
				$_arrFiles[$v['filename']]=str_replace( 
					array( '<'.$v['name'].'>', '<default'.$_arrMatch[0].'>', '</default'.$_arrMatch[0].'>' ), 
					array( '', '', '' ), 
					$_arrFiles[$v['filename']] );
				continue;
			}
			$_arrFiles[$v['filename']]=str_replace( '<'.$v['name'].'>', $_arrCode[$v['name']], $_arrFiles[$v['filename']] ); // заменяем спот
			$_arrFiles[$v['filename']]=preg_replace( '~<default'.$_arrMatch[0].'>.*</default'.$_arrMatch[0].'>~ims', '', $_arrFiles[$v['filename']] ); // удаляем дефолтный код если есть
		}
		$_profile=new Project_Sites_Profiles();
		if ( !$_profile->onlyOne()->withIds( $this->data->filtered['profile_id'] )->getList( $_arrProfile ) ) {
			return false;
		}
		// global
		$_arrFiles['global.php']=str_replace( array(
			'$$$numlist$$$',
			'$$$showanalytics$$$',
			'$$$ga$$$',
			'$$$EBAYAFFID$$$',
			'$$$AMAZONID$$$',
			'$$$AMAZONNUM$$$',
			'$$$NAME$$$',
			'$$$EMAIL$$$',
		), array(
			$this->data->filtered['result_num'],
			$this->data->filtered['flg_gastatus'],
			(empty( $this->data->filtered['flg_gastatus'] )||empty( $this->data->filtered['google_analytics'] )? '':$this->data->filtered['google_analytics']),
			(empty( $_arrProfile['ebayaffid'] )? '':$_arrProfile['ebayaffid']),
			(empty( $_arrProfile['amazon_associates_id'] )? '':$_arrProfile['amazon_associates_id']),
			(empty( $_arrProfile['no_of_amazon_products'] )? '':$_arrProfile['no_of_amazon_products']),
			(empty( $_arrProfile["first_name"] )||empty( $_arrProfile["last_name"] )? '':$_arrProfile["first_name"].' '.$_arrProfile["last_name"]),
			(empty( $_arrProfile['email'] )? '':$_arrProfile['email']),
		), file_get_contents( $this->_dir.'global.php' ) );
		// config
		$_arrFiles['config.php']=str_replace( array(
			'$$$keyword$$$',
			'$$$adsense$$$',
		), array(
			(empty( $this->data->filtered['main_keyword'] )? '':$this->data->filtered['main_keyword']),
			(empty( $_arrProfile['adsense_id'] )? '':$_arrProfile['adsense_id']),
		), file_get_contents( $this->_dir.'config.php' ) );
		// сохраняем
		return Core_Files::setContentMass( $_arrFiles, $this->_dir );
	}

	protected function generateArticles() {
		if ( empty( $this->data->filtered['flg_articles'] ) ) { // сайт без статей
			return true;
		}
		if ( !Project_Articles::getInstance()->withIds( $this->data->filtered['arrArticleIds'] )->getContent( $_arrContent ) ) {
			$this->_errors['articles']='Process Aborted. Unable to collect articles';
			return false;
		}
		$_strDir=$this->_dir.'articles'.DIRECTORY_SEPARATOR;
		if ( !is_dir($_strDir) ){
			mkdir($_strDir,true);
		}
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
		self::$_lastUrls[] = array('shedule_id'=>$_sheduleId, 'url'=> $this->data->filtered['url'] . 'article/' . Core_Files::getFileName($_strFilename) .'.html' );
	}

	public static function getLastUrls(){
		return self::$_lastUrls;
	}
}
?>