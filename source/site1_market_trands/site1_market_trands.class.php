<?php
class site1_market_trands extends Core_Module {
	

	public function set_cfg(){		
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM Market Trends', ),
			'actions'=>array(
				array( 'action'=>'main', 'title'=>'Market Trends main', 'flg_tree'=>1 ),
				array( 'action'=>'popup', 'title'=>'Market Trends popup', 'flg_tree'=>1, 'flg_tpl' => 1 ),
			),
		);
	}
	
	public function main() {
		if ( !empty( $_POST['keywords'] ) ) {
			
			$this->out['keywords'] = str_replace(' ', '+', trim($_POST['keywords']) );  
			$this->out['scope'] = $_POST['scope'];
			$this->out['time'] = $_POST['time'];
			$this->out['google_display'] = true;
			
		}
	}
	
	public function popup(){
		if ( !empty( $_GET['keywords'] ) ) {
			$this->out['keywords'] = str_replace(' ', '+', trim($_GET['keywords']) );  
			$this->out['scope'] = 'empty';
			$this->out['time'] = '3-m';
		}		
	}
	
	
}
?>