<?php
error_reporting(E_ERROR);
class site1_affiliate extends Core_Module {
	private $_model;
	public function set_cfg(){		
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM Affiliate profit booster', ),
			'actions'=>array(
				array( 'action'=>'create', 'title'=>'Create Affiliate Pages', 'flg_tree'=>1 ),
				array( 'action'=>'manage', 'title'=>'Manage Affiliate Pages', 'flg_tree'=>1 ),
				array( 'action'=>'get', 'title'=>'Get file', 'flg_tpl' =>1, 'flg_tree'=>1 ),
				array( 'action'=>'save', 'title'=>'Save file', 'flg_tpl' =>1, 'flg_tree'=>1 ),
				array( 'action'=>'edit_file', 'title'=>'Edit file', 'flg_tree'=>1 ),
				array( 'action'=>'edit_settings', 'title'=>'Edit settings', 'flg_tree'=>1 ),
			),
		);
	}

	public function  before_run_parent(){
		$this->_model = new Project_Affiliate();
	}
	
	public function create() {
		$ftp = new Project_Ftp();
		$ftp->getList( $this->out['ftp'] );
	}
	
	public function get(){
		if ( !$this->_model->init("get",$_POST) ) {
			echo 'Can not connect to server!';
			die();
		}
		$fileContent = $this->_model->getFile($_POST);
		if ( !$fileContent ) {
			echo 'No search file!';
			die();
		}
		echo $fileContent;
		die();
	}

	public function save(){
		if( !$this->_model->init("get",$_POST)) {
			echo 0; die();
		}
		if ( $_POST['edit']['type']  == 'edit' && !$_POST['convert_page']) {
			echo (!$this->_model->writeFile( $_POST )) ? 0 : 1; die();
		} else {
			echo (!$this->_model->creatPage( $_POST )) ? 0 : 1; die();
		}	
	}
	
	public function manage() {
		if ( isset($_GET['del']) ) {
			$type = (!empty($_GET['cpp'])) ? 'cpp' : 'affiliate';
			$this->_model->deleteAffiliatePage( $_GET['del'] , $type );
		}
		$this->out['arrItems'] = $this->_model->getAffiliatePages();	
	}
	
	public function edit_file() {
		if ( empty( $_GET['id'] ) ) {
			$this->location('./');
		}
		if (empty($_GET['cpp'])) {
			$this->out['arrItem'] = $this->_model->getAffiliatePageById( $_GET['id'] );
		} else {
			$this->out['arrItem'] = $this->_model->getCppTrakingPage( $_GET['id'] );
		}
		$this->out['arrItem']['arrFtp']['directory'] = $this->out['arrItem']['ftp_directory'] . $this->out['arrItem']['page_name'];
		$this->out['arrItem']['arrFtp']['address'] = $this->out['arrItem']['ftp_address'];
		$this->out['arrItem']['arrFtp']['username'] = $this->out['arrItem']['ftp_username'];
		$this->out['arrItem']['arrFtp']['password'] = $this->out['arrItem']['ftp_password'];
	}
	
	public function edit_settings() {
		if ( empty( $_GET['id'] ) ) {
			$this->location('./');
		}
		if (empty($_GET['cpp'])) {
			$this->out['arrItem'] = $this->_model->getAffiliatePageById( $_GET['id'] );
		} else {
			$this->out['arrItem'] = $this->_model->getCppTrakingPage( $_GET['id'] );
		}	}
	
}

?>