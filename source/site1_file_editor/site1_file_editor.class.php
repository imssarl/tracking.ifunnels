<?php

class site1_file_editor extends Core_Module  {
	
	public function set_cfg(){		
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM Remote File Editor', ),
			'actions'=>array(
				array( 'action'=>'edit', 'title'=>'Edit', 'flg_tree'=>1 ),
			),
		);
	}
	
	public function edit() {}
}

?>