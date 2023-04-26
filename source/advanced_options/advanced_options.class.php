<?php
class advanced_options extends Core_Module
{
	private $_model;

	public function set_cfg() {
		$this->inst_script=array(
		'module'=>array( 'title'=>'Advanced Customization Options'),
		'actions'=>array(
					array( 'action'=>'ad', 'title'=>'Campaing Split ad', 'flg_tpl'=>1, 'flg_tree'=>1 ),
					array( 'action'=>'spots', 'title'=>'Spots', 'flg_tpl'=>1, 'flg_tree'=>1 ),
					),
		);

	}

	public function optinos(){ 
		if ( $this->params['site_type']==Project_Sites::PSB && !empty($_POST) ){
			Project_Options::setTemplate2spots($_POST['arrPsb']['template_id']);
		}		
		$_model = new Project_Options( $this->params['site_type'], $this->params['site_data']['id'] );
		if (!empty($this->params['site_data']['id'])){
			$_model->get($this->out['arrOpt']);
		}
		if (!empty($_POST)){
			$this->out['arrOpt']=$_POST['arrOpt'];
		}
		$this->out['arrSpots']=Project_Options::$arrSpotsStruct;
		$this->out['jsonOpt']=json_encode($this->out['arrOpt']);
	}

	/**
	 * Ajax Compaigns or Split
	 *
	 */
	public function ad(){
		$_model = new Project_Options( $_POST['site_type'] );
		$this->out['ids'] = $_POST['ids'];
		$this->out['flg_content']=$_POST['flg_content'];
		$this->out['arrList']=$_model->getDams( $_POST['flg_content'] );
	}

	/**
	 *  Ajax spots
	 *
	 */
	public function spots(){
		$_model = new Project_Options( $_POST['site_type'] );
		$this->out['ids'] = $_POST['ids'];
		$this->out['type'] = $_POST['type'];
		$this->out['spot_index'] = $_POST['spot_index'];
		if ( $_POST['type'] == Project_Options::ARTICLE ){
			$this->out['arrList']=$_model->getSavedSelection( $_POST['spot_id'] );
		}
		if ( $_POST['type'] == Project_Options::SNIPPET ){
			$this->out['arrList']=$_model->getSnippets( $_POST['spot_id'] );
		}
		if ( $_POST['type'] == Project_Options::VIDEO ){
			$this->out['arr']=$_model->getVideo($_POST['spot_id'] );
		}
	}

}