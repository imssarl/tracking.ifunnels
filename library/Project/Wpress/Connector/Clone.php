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
 * Blog clone transport
 *
 * @category Project
 * @package Project_Wpress
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Wpress_Connector_Clone extends Project_Wpress_Connector {

	private $_arrDest = array();
	
	// в $obj site_id (для получения фтп настроек) а также настройки для фтп куда клонировать и site_url
	public function __construct( Core_Data $obj ) {
		parent::__construct( $obj );
	}

	public function setDestination( $_arrData ){
		if (empty($_arrData)){
			return false;
		}
		$this->_arrDest=$_arrData;
		return $this;
	}
	public function prepareServer() {
		if ( !$this->prepare() ) {
			return false;
		}
		// заливаем анпакер
		$this->setPathFrom($this->_cloneSrcDir);
		$this->setPathTo($this->_data->filtered['ftp_directory'] .'clone/' );
		if ( !$this->dirUpload() ) {
			return $this->setError( 'unable upload '.$this->_data->filtered['ftp_directory'].'clone/' );
		}
		return true;
	}

	// на этом этапе к фтп не подключаемся
	public function setConfigCloner() {
		// подготовить диру, $this->_mutatorDir определяется выше потому что она ещё понадобится в других методах (шагах)
		$_strTmp='Project_Wpress_Connector_Clone@generateMutator';
		if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $_strTmp ) ) {
			return false;
		}
		$this->_mutatorDir=$_strTmp;
		Core_Files::getContent( $_strFile, $this->_cloneSrcDir . 'clone.php');
		if ( empty($_strFile) ){
			return false;
		}
		$_serach = array(
			'#DB_NAME#',
			'#DB_USER#',
			'#DB_PASSWORD#',
			'#DB_HOST#',
			'#new_table_prefix#',
			'#siteurl#',
			'#home#',
			'#blogname#',
			'#without_post#',
			'#without_page#'
		);
		if ( substr( $this->_arrDest['sub_dir'], -1 )!='/' ) {
			$this->_arrDest['sub_dir'].='/';
		}
		if ( substr( $this->_arrDest['sub_dir'], 0,1 )!='/' ) {
			$this->_arrDest['sub_dir']='/'.$this->_arrDest['sub_dir'];
		}		
		$_replace = array(
			$this->_arrDest['db_name'],
			$this->_arrDest['db_username'],
			$this->_arrDest['db_password'],
			$this->_arrDest['db_host'],
			$this->_arrDest['db_tableprefix'],
			$this->_arrDest['url'],
			(empty($this->_arrDest['sub_dir']))? '/':$this->_arrDest['sub_dir'],
			$this->_arrDest['title'],
			$this->_arrDest['without_post'],
			$this->_arrDest['without_page'],
			
		);
		$_strFile=str_replace($_serach,$_replace,$_strFile);
		if ( !Core_Files::setContent($_strFile,$_strTmp.'clone.php') ){
			return false;
		}
		return true;
	}

	public function uploadMutator() {
		if ( !$this->fileUpload( $this->_data->filtered['ftp_directory'].'clone/clone.php', $this->_mutatorDir.'clone.php' ) ) {
			return $this->setError( 'unable upload '.$this->_mutatorDir.'clone.php' );
		}
		return true;
	}

	public function startCloner(){
		// упаковываем блог
		if ( !$this->getResponce( $_strRes, $this->_data->filtered['url'].'clone/pack.php' ) ) {
			return $this->setError( 'no respond '.$this->_data->filtered['url'].'clone/pack.php' );
		}
		// заливаем блог на новый сервак;
		if (!$this->uploadDestination() ) {
			return false;
		}
		return true;		
	}
	
	private function uploadDestination(){
		$_strTmp='Project_Wpress_Connector_Clone@uploadDestination';
		if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $_strTmp ) ) {
			return false;
		}
		if ( !$this->fileDownload($this->_data->filtered['ftp_directory'].'clone/blog.zip',$_strTmp.'blog.zip') ) {
			return $this->setError('unbale download file ' . $this->_data->filtered['url'].'clone/blog.zip' );
		}
		$_data = new Core_Data( $this->_arrDest );
		$_data->setFilter();
		parent::__destruct();
		$this->closeConnection();
		$this->_permChecked=false;
		parent::__construct( $_data );

		if (!$this->setConfigCloner() ){ 
			$this->getErrors( $this->_errors['create'] );
			return false;
		}
		if (!$this->prepareServer() ){
			$this->getErrors( $this->_errors['create'] );
			return false;
		}
		if ( !$this->uploadMutator() ){
			$this->getErrors( $this->_errors['create'] );
			return false;
		}
		if (!$this->fileUpload($this->_data->filtered['ftp_directory'].'clone/blog.zip',$_strTmp.'blog.zip')){
			return $this->setError('unbale upload file ' . $this->_data->filtered['url'].'clone/blog.zip' );
		}
		if ( !$this->endCloner() ){
			$this->getErrors( $this->_errors['create'] );
			return false;
		}	
		return true;
	}
	
	private function endCloner(){
		// распаковываем блог
		if ( !$this->getResponce( $_strRes, $this->_data->filtered['url'].'clone/unpack.php' ) ) {
			return $this->setError( 'no respond '.$this->_data->filtered['url'].'clone/unpack.php' );
		}		
		return true;
	}
}
?>