<?php 

class keyword_generator extends Core_Module {

	public function set_cfg(){

		$this->inst_script = array(
			'module'  => array('title' => 'Keywords Generation'),
			'actions' => array(
								array('action' => 'combine_keywords', 'title' => 'Combine Keywords', 'flg_tree' => 1),
								array('action' => 'combine_url', 'title' => 'Combine URL', 'flg_tree' => 1),
								array('action' => 'multiboxlist', 'title' => 'Multibox list', 'flg_tree' => 1, 'flg_tpl' => 1 ),
								array('action' => 'typo_generator', 'title' => 'Typo Generator', 'flg_tree' => 1)
							  )
			);

	}
	public function multiboxlist(){
		$_model = new Project_Keywords_Generator();
		$_model->getSavedList($this->out['arrList']);
		if ( !empty( $_GET['keyword'] ) ){
			$_arr = $_model->getKeywords( json_decode( $_GET['jsonIds'] , true) );
			header('Content-type: application/json;');
			echo Zend_Registry::get( 'CachedCoreString' )->php2json($_arr);
			exit;
		}
		
	}
	
	public function combine_keywords() {
		$this->out['post'] = $_POST;
		$model = new Project_Keywords_Generator();

		if (isset($_POST['type']) && 'export' == $_POST['type'])
		{
			if ( !empty( $_POST['result'] ) ) 
			{
				ob_end_clean();
				$model->get_file();
			}
		}

		$model->set_data($_POST['arrData']);

		if (isset($_POST['type']) && 'save' == $_POST['type'])
		{
			if (!empty($_POST['result']))
			{
				$model->insertKeywords($this->objUser->u_info['parent_id']);
			}
		}
		$this->out['arrData'] = $model->get_data();
		
		$left = '';
		$right = '';	
		
		if( $_POST['regular'] ) { $left = ''; $right = '';  $this->out['result'] .= $model->get_result($left,$right) . "";}
		if( $_POST['quotes'] ) { $left = '"'; $right = '"';  $this->out['result'] .= $model->get_result($left,$right) . "";}
		if( $_POST['brackets'] ) { $left = '['; $right = ']';  $this->out['result'] .= $model->get_result($left,$right) . "";}
		
		
	}

	public function combine_url() {
		$model = new Project_Keywords_Generator();
		if (!empty($_POST['export'])) {
			$_POST['name'] = (!empty($_POST['name'])) ? $_POST['name'] : 'urls.txt';
			ob_end_clean();
			$model->get_file();
		}
	}
	
	public function typo_generator() {
		$model = new Project_Keywords_Generator();
		$this->out['output'] = $_POST['output'];
		if ( !empty( $_POST['word'] ) ) {
			if (!empty($_POST['export'])) {
				$_POST['name'] = (!empty($_POST['name'])) ? $_POST['name'] : 'typo.txt';
				ob_end_clean();
				$model->get_file();
			}
			$left = '';
			$right = '';
			$this->out['arrRes'] = array();
			$words = explode("\n",$_POST['word']);
			if (empty($_POST['output'])) {
				$_POST['output']['regular'] = 1;
			}
			foreach ($_POST['output'] as $typeView => $value) {
				if ( $value ) {
					if ($typeView == 'quotes') {$left = '"'; $right = '"';}
					if ($typeView == 'brackets') {$left = '['; $right = ']';}
					foreach ( $words as $word ) {
						$arr = Project_Keywords_Generator_Typo::getAllTypos( str_replace(array("\n","\r"),array('',''), $word) );
						foreach ($arr as &$i) {
							$i = $left . $i . $right . "\n";
						}			
						$this->out['arrRes'][] = $arr;	
					}
				}
			}
		}

	}

}

?>