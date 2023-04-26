<?php
class Project_Subscribers extends Core_Data_Storage{

	protected $_table='s8rs_';
	protected $_fields=array('id', 'email', 'name', 'ip', 'tags', 'settings', 'options', 'added');

	public function __construct( $_uid=false ){
		if( $_uid !== false ){
			$this->_table=$this->_table.$_uid;
		}
		$this->install( $_uid );
	}
	
	public function install(){
		try {
			Core_Sql::setConnectToServer( 'lpb.tracker' );
			//========
			Core_Sql::setExec( "CREATE TABLE IF NOT EXISTS `".$this->_table."` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`email` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
				`name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
				`ip` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
				`tags` TEXT NULL,
				`settings` TEXT NULL,
				`added` INT(11) UNSIGNED NOT NULL DEFAULT '0',
				UNIQUE INDEX `id` (`id`)
			);");
			//========
			Core_Sql::renewalConnectFromCashe();
		} catch(Exception $e) {
			Core_Sql::renewalConnectFromCashe();
			return $this;
		}
	}

	protected $_withEmails=array();

	public function withEmails( $_arrIds=array() ) {
		$this->_withEmails=$_arrIds;
		return $this;
	}

	public function withTags( $_arrIds=array() ) {
		$this->_withTags=$_arrIds;
		return $this;
	}

	protected function assemblyQuery() {
		parent::assemblyQuery();
		if ( !empty( $this->_withEmails ) ) {
			$this->_crawler->set_where( 'd.email IN ('.Core_Sql::fixInjection( $this->_withEmails ).')' );
		}
		if ( !empty( $this->_withTags ) ) {
			$_moreLikes=array();
			foreach( $this->_withTags as $_tagN ){
				if( !empty( $_tagN ) ){
					$_moreLikes[]= 'd.tags LIKE \'%,'.$_tagN.',%\'';
				}
				$this->_crawler->set_where( implode( ' OR ', $_moreLikes ) );
			}
		}
	}
	
	protected function init() {
		parent::init();
		$this->_withEmails=array();
	}
	
	protected function beforeSet(){
		$this->_data->setFilter( array( 'trim', 'clear' ) );
		$this->withEmails( $this->_data->filtered['email'] )->onlyOne()->getList( $_arrSubscriber );
		if( isset( $_arrSubscriber ) && !empty( $_arrSubscriber ) ){
			$this->_data->setElement('id', $_arrSubscriber['id']);
			$_settings=base64_encode( serialize( $this->_data->filtered['settings']+$_arrSubscriber['settings'] ) );
		}else{
			$_settings=base64_encode( serialize( $this->_data->filtered['settings'] ) );
		}
		$this->_data->setElement('settings', $_settings);
		if(	!empty( $this->_data->filtered['tags'] ) && substr( $this->_data->filtered['tags'], 0, 1 ) != ',' ){
			$_tags=Project_Tags::set( $this->_data->filtered['tags'] );
		}
		$this->_data->setElement('tags', $_tags);
		$this->_data->setFilter( array( 'trim', 'clear' ) );
		return parent::beforeSet();
	}

	public function getList( &$mixRes ){
		try {
			Core_Sql::setConnectToServer( 'lpb.tracker' );
			//========
			parent::getList( $mixRes );
			//========
			Core_Sql::renewalConnectFromCashe();
		} catch(Exception $e) {
			Core_Sql::renewalConnectFromCashe();
			return $this;
		}
		if( is_int( array_keys( $mixRes )[0] ) ){
			$_tagsIds=array();
			foreach( $mixRes as &$_item ){
				$_item['settings']=unserialize( base64_decode( $_item['settings'] ) );
				if( strpos( $_item['tags'], ',' ) !== false ){
					$_item['tags']=array_filter( explode( ',', trim( $_item['tags'], ',' ) ) );
				}
				if( !empty( $_item['tags'] ) && !in_array( $_item['tags'], $_tagsIds ) ){
					foreach( $_item['tags'] as $_tagId ){
						$_tagsIds[$_tagId]=$_tagId;
					}
				}
			}
			if( !empty( $_tagsIds ) ){
				$_tags = Project_Tags::get( implode( ',', $_tagsIds ) );
				foreach( $mixRes as &$item ){
					foreach( $item['tags'] as &$_tagGetName ){
						foreach( $_tags as $_tagId=>$_tagName ){
							if( $_tagId == $_tagGetName ) {
								$_tagGetName=$_tagName;
							}
						}
					}
				}
			}
		}else{
			$mixRes['settings']=unserialize( base64_decode( $mixRes['settings'] ) );
			if( ctype_digit( $mixRes['options']['tags'] ) ){
				$mixRes['tags'] = current( Project_Tags::get( $mixRes['tags'] ) );
			}
		}
		return !empty($mixRes);
	}

	public function set() {
		if ( !$this->beforeSet() ) {
			return false;
		}
		if ( empty( $this->_data->filtered['id'] ) && empty( $this->_data->filtered['added'] ) ){
			$this->_data->setElement( 'added', time() );
		}
		try {
			Core_Sql::setConnectToServer( 'lpb.tracker' );
			//========
			$this->_data->setElement( 'id', Core_Sql::setInsertUpdate( $this->_table, $this->_data->setMask( $this->_fields )->getValid() ) );
			//========
			Core_Sql::renewalConnectFromCashe();
		} catch(Exception $e) {
			Core_Sql::renewalConnectFromCashe();
			return $this;
		}
		return $this->afterSet();
	}
	
	public function setMass(){

	}
	
	public function del(){
		$_strWith=array();
		if ( !empty( $this->_withEmail ) ){
			$_strWith[]='email IN ('.Core_Sql::fixInjection( $this->_withEmail ).')';
		}
		if( empty( $_strWith ) ){
			$this->init();
			return false;
		}
		try {
			Core_Sql::setConnectToServer( 'lpb.tracker' );
			//========
			Core_Sql::setExec( 'DELETE FROM '.$this->_table.' WHERE '.implode( ' and ', $_strWith ) );
			//========
			Core_Sql::renewalConnectFromCashe();
		} catch(Exception $e) {
			Core_Sql::renewalConnectFromCashe();
			$this->init();
			return false;
		}
		$this->init();
		return true;
	}
}
?>