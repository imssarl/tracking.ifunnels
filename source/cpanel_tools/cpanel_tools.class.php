<?php

class cpanel_tools extends Core_Module  {

	private $_model = false;
	
	public function before_run_parent(){
		$this->_model = new Core_Cpanel();
		if (!empty($_POST['arrCpanel']) && !$this->_model->setAccess($_POST['arrCpanel']) ) {
			$this->objStore->set( array('error'=>'Process Aborted. Not correct data' ) );
			$this->location();
		}
	}
	
	public function set_cfg() {
		$this->inst_script=array(
			'module'=>array( 'title'=>'Cpanel tools', ),
			'actions'=>array(
				array( 'action'=>'subdomain', 'title'=>'Subdomain creator', 'flg_tree'=>2, 'flg_tpl'=>1 ),
				array( 'action'=>'database', 'title'=>'Database creator', 'flg_tree'=>2, 'flg_tpl'=>1 ),
				array( 'action'=>'addondomain', 'title'=>'Addon domains creator', 'flg_tree'=>2, 'flg_tpl'=>1 ),
			),
		);
	}

	public function addondomain(){
		$this->objStore->getAndClear( $this->out );
		if ( !empty( $_POST ) ) {
			if ( !$this->_model->createAddonDomain( $arrErr, $_POST['arrAction'] ) ) {
				if ( $arrErr['connect'] ) {
					$this->objStore->set( array('error'=>'001' ) );
				} 
				if ( $arrErr['notadded'] ) {
					$this->objStore->set( array('error'=>'002' ) );
				}
				$this->location();
			}
			$result = $this->_model->getResult();
			$this->objStore->set( array('jsonResult'=> Zend_Registry::get( 'CachedCoreString' )->php2json($result), 'result'=> $result, 'host' => $_POST['arrCpanel']['host'] ) );
			$this->location(array( 'wg'=>true ));
		}
	}

	public function subdomain(){
		$this->objStore->getAndClear( $this->out );
		if ( !empty( $_POST ) ) {
			if(!$this->_model->setDomain($_POST['arrAction']['root'])) {
				$this->objStore->set( array('error'=>'001' ) );
				$this->location();
			}
			
			if (!$this->_model->createSubDomains($_POST['arrAction']['subdomain'])) {
				$this->objStore->set( array('error'=>'002' ) );
				$this->location();				
			}
			$this->objStore->set( array('result'=>$this->_model->getResult(), 'jsonResult' => Zend_Registry::get( 'CachedCoreString' )->php2json($_POST['arrCpanel']+$_POST['arrAction']), 'root' => $_POST['arrAction']['root'] ) );
			$this->location(array( 'wg'=>true ));							
		}		
	}

	public function database(){
		$this->objStore->getAndClear( $this->out );
		if ( !empty( $_POST ) ) {
			if ( !$this->_model->createDb( $_POST['arrAction'] ) ) {
				$this->objStore->set( array('error'=>'001' ) );
				$this->location();
			}
			$result = $this->_model->getResult();
			if ( $result['bind'] ) {
				$this->objStore->set( array('jsonResult'=> Zend_Registry::get( 'CachedCoreString' )->php2json($result), 'result'=> $result ) );
			} else {
				$this->objStore->set( array('error'=>"002" ) );
			}
			$this->location(array( 'wg'=>true ));
		}
	}
	
	public function set(){
			
	}

}
?>