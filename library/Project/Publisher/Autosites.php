<?php
class Project_Publisher_Autosites extends Core_Storage {

	public $fields=array( 'project_id', 'site_id', 'ext_category_id' );
	public $table='pub_autosites';

	private $_projectId=0;

	public function __construct( $_intProjectId=0 ) {
		if ( empty( $_intProjectId ) ) {
			throw new Exception( Core_Errors::DEV.'|Project_Publisher_Autosites->__construct( $_intProjectId=0 ) - empty project id set' );
		}
		$this->_projectId=$_intProjectId;
	}

	public function setData( $_data=array() ) {
		if ( empty( $_data ) ) {
			throw new Exception( Core_Errors::DEV.'|Project_Publisher_Cache->setData( $_data=array() ) - empty list of sites' );
		}
		$this->_data=$_data;
		return $this;
	}

	public function store() {
		$this->del();
		foreach( $this->_data as $v ) {
			$arrIns[]=array(
				'project_id'=>$this->_projectId, 
				'site_id'=>$v['site_id'],
				'ext_category_id'=>(empty( $v['ext_category_id'] )? 0:$v['ext_category_id']), 
			);
		}
		if ( !empty( $arrIns ) ) {
			Core_Sql::setMassInsert( $this->table, $arrIns );
		}
	}

	public function del() {
		Core_Sql::setExec( 'DELETE FROM '.$this->table.' WHERE project_id='.$this->_projectId );
	}

	protected function assemblyQuery() {
		$this->_crawler->set_select( 'd.*' );
		$this->_crawler->set_from( $this->table.' d' );
		$this->_crawler->set_where( 'd.project_id='.$this->_projectId );
		if ( !empty( $this->_withIds ) ) {
			$this->_crawler->set_where( 'd.id IN ('.Core_Sql::fixInjection( $this->_withIds ).')' );
		}
	}
}
?>