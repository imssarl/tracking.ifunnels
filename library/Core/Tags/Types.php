<?php
class Core_Tags_Types {

	private static $_instance=NULL;

	public static function getInstance() {
		if ( self::$_instance==NULL ) {
			self::$_instance=new Core_Tags_Types();
		}
		return self::$_instance;
	}

	private $_types=array();

	public function __construct() {
		$this->initTypes();
	}

	private function initTypes() {
		$this->_types=Core_Sql::getKeyVal( 'SELECT * FROM tag_types' );
	}

	public function getTypeByTitle( $_str='' ) {
		if ( empty( $_str ) ) {
			throw new Exception( Core_Errors::DEV.'|Tags type is empty' );
		}
		if ( in_array( $_str, $this->_types ) ) {
			return array_search( $_str, $this->_types );
		}
		$this->set( $_str );
		return $this->getTypeByTitle( $_str );
	}

	private function set( $_str='' ) {
		Core_Sql::setInsert( 'tag_types', array( 'title'=>$_str ) );
		$this->initTypes();
	}
}
?>