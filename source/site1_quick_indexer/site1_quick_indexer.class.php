<?php
class site1_quick_indexer extends Core_Module {
	

	public function set_cfg(){		
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM Quick Indexer', ),
			'actions'=>array(
				array( 'action'=>'main', 'title'=>'Quick Indexer main', 'flg_tree'=>1 ),
			),
		);
	}
	
	public function main() {

	}
	
}
?>