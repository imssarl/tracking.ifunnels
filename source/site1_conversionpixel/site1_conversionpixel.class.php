<?php
class site1_conversionpixel extends Core_Module {

	public function set_cfg(){
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM convresionpixel', ),
			'actions'=>array(
				array( 'action'=>'conversionpixel', 'title'=>'Conversionpixel', 'flg_tpl'=>1, 'flg_tree'=>1 )
			),
		);
	}
	
	public function conversionpixel(){
		$this->out['ip'] = Project_Conversionpixel::getUserIp();
		$this->out['split_id'] = $_REQUEST["splitid"];
		$this->out['type'] = $_REQUEST["type"];
		if( !empty($_POST) ){
			header('Access-Control-Allow-Origin: *');
			$_hash = md5(Project_Squeeze_Split::decode($this->out['split_id']).$this->out['ip']); 
			if($_POST['type'] == 'view'){
				Core_Sql::setExec('DELETE FROM lpb_cookies WHERE added <= (UNIX_TIMESTAMP()-60*60)');
				$_lpbId = Core_Sql::getCell('SELECT lpb_id FROM lpb_cookies WHERE hash="' . $_hash . '";');
				if( intval($_lpbId) > 0 ){
					Core_Sql::setExec('UPDATE lpb_cookies SET `added`='.time().' WHERE `hash`="'.$_hash.'";');
					$obj = new Project_Conversionpixel();
					$obj->setEntered(array('squeeze_id' => $_lpbId, 'param' => $_POST['type'], 'ip' => $this->out['ip'] ))->set();
				}
			}
			if( $_POST['type'] == 'sale' ){
				$_lpbId = Core_Sql::getCell("SELECT lpb_id FROM lpb_cookies WHERE hash = '".$_hash."'");
				if( intval($_lpbId) > 0 ){
					$obj = new Project_Conversionpixel();
					$obj->setEntered(array('squeeze_id' => $_lpbId, 'param' => $_POST['type'], 'ip' => $this->out['ip'] ))->set();
				}
			}
			if( $_POST['type'] == 'lead' ){
				$_lpbId = Core_Sql::getCell("SELECT lpb_id FROM lpb_cookies WHERE hash = '".$_hash."'");
				if( intval($_lpbId) > 0 ){
					$obj = new Project_Conversionpixel();
					$obj->setEntered(array('squeeze_id' => $_lpbId, 'param' => $_POST['type'], 'ip' => $this->out['ip'] ))->set();
				}
			}
			echo true;
			exit;
		}
	}

	public function install(){
		Core_Sql::setExec('CREATE TABLE IF NOT EXISTS `lpb_cookies` (
			`hash` text  NOT NULL,
			`lpb_id` int(11) NOT NULL,
			`added` int(11) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;');
	}
}
?>