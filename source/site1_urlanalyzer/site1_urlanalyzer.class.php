<?php
/**
 * CNM
 *
 * @category CNM
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 26.06.2009
 * @version 1.0
 */


/**
 * @category CNM
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 */
class site1_urlanalyzer extends Core_Module {
	
	public function set_cfg() {
		$this->inst_script=array(
			'module' =>array( 'title'=>'CNM URL Analyzer', ),
			'actions'=>array(
				array( 'action'=>'settings', 'title'=>'Settings', 'flg_tree'=>1 ),
				array( 'action'=>'analyze', 'title'=>'Analyze', 'flg_tree'=>1 ),
			),
		);
	}
	
	public function settings(){}
	
	public function analyze(){
		if (!empty($_POST['arrData'])){
			$_model=new Project_Urlanalyz();
			if ( !$_model->setSettings($_POST['arrData'])->run() ){
				$this->objStore->set( array( 'msg'=>'delete' ) );
				$this->location(array('action'=>'settings'));
			}
			$this->out['arrList']=$_model->getResponce();
			$this->out['arrParams']=$_model->getParams();
			$this->out['arrData']=$_POST['arrData'];
		}		
	}

}
?>