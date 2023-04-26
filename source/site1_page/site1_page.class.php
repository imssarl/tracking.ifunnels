<?php

class site1_page extends Core_Module {

	public function set_cfg(){
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM page', ),
			'actions'=>array(
				array( 'action'=>'page', 'title'=>'CNM Page', 'flg_tpl'=>1, 'flg_tree'=>1 )
			),
		);
	}
	
	public function page(){
		$link = new Project_Squeeze_Split_Link();
		$url = ''; $splittest = 0;
		$_idSplitTest = Project_Squeeze_Split::decode( $_GET['id'] );
		unset( $_GET['id'] );
		$getRedirect='&'.http_build_query( $_GET );
		$splittest = new Project_Squeeze_Split ();
		$splittest->withIds( $_idSplitTest )->onlyOne()->getList( $_arrSplit );
		$_hash = md5( $_idSplitTest . Project_Conversionpixel::getUserIp() );		
		if($_arrSplit['flg_pause'] == 1){
			$url = $_arrSplit['url'];
			if(!empty($url)){
				header('Location: ' . $url.'?st='.$getRedirect);
			}
		}else{
			$_view_all = 0;
			foreach ($_arrSplit['arrCom'] as $key => $value){
				$_view_all += (int)$value['shown'];
			}
			$_koef = array();
			foreach ($_arrSplit['arrCom'] as $key => $value){
				$_koef[] = (int)(($_view_all - (int)$value['shown']) * 100 / $_view_all);
			}
			$_koef_sum = array_sum ($_koef);
			$r = rand(0, $_koef_sum);
			if( $r <= $_koef[0] ){
				$link->withSplitIds(array($_arrSplit['arrCom'][0]['split_id']))->withIds(array($_arrSplit['arrCom'][0]['campaign_id']))->updateLink();
				$url = $_arrSplit['arrCom'][0]['url'];
				$splittest = $_arrSplit['arrCom'][0]['split_id'];
				//=====================
				$_lpbId=Core_Sql::getCell('SELECT lpb_id as count FROM lpb_cookies WHERE hash=\'' . $_hash . '\';');
				if(intval($_lpbId) > 0){
					Core_Sql::setExec('UPDATE lpb_cookies SET `added`='.time().', `lpb_id`=' . $_arrSplit['arrCom'][0]['campaign_id'] . ' WHERE `hash`=\''.$_hash.'\';');
				}else{
					Core_Sql::setExec('INSERT INTO lpb_cookies VALUES (\'' . $_hash . '\',' . $_arrSplit['arrCom'][0]['campaign_id'] . ', ' . time() . ');');
				}
				//=====================
				header('Location: '.$url .'?splt='.$splittest.$getRedirect);
				exit;
			}else{
				$_tmp = $_koef[0];
				for($i = 1; $i < count($_koef); $i++){
					$_tmp += $_koef[$i];
					if($r <= $_tmp){
						$link->withSplitIds(array($_arrSplit['arrCom'][$i]['split_id']))->withIds(array($_arrSplit['arrCom'][$i]['campaign_id']))->updateLink();
						$url = $_arrSplit['arrCom'][$i]['url'];
						$splittest = $_arrSplit['arrCom'][$i]['split_id'];
						//=====================
						$_lpbId=Core_Sql::getCell('SELECT lpb_id as count FROM lpb_cookies WHERE hash=\'' . $_hash . '\';');
						if(intval($_lpbId) > 0){
							Core_Sql::setExec('UPDATE lpb_cookies SET `added`='.time().', `lpb_id`=' . $_arrSplit['arrCom'][$i]['campaign_id'] . ' WHERE `hash`=\''.$_hash.'\';');
						}else{
							Core_Sql::setExec('INSERT INTO lpb_cookies VALUES (\'' . $_hash . '\',' . $_arrSplit['arrCom'][$i]['campaign_id'] . ', ' . time() . ');');
						}
						//=====================
						header('Location: '.$url .'?splt='.$splittest.$getRedirect);
						exit;
					}
				}
			}
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