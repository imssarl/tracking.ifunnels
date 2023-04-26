<?php
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

chdir( dirname(__FILE__) );
chdir( '../' );
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

$link = new Project_Squeeze_Split_Link();
$url = ''; $splittest = 0;
$_idSplitTest = Project_Squeeze_Split::decode( $_GET['id'] );
$splittest = new Project_Squeeze_Split ();
$splittest->withIds( $_idSplitTest )->getList( $_arrSplit );
if($_arrSplit[0]['flg_pause'] == 1) {
	$url = $_arrSplit['url'];
}else{
	$_view_all = 0;

	foreach ($_arrSplit[0]['arrCom'] as $key => $value) {
		$_view_all += (int)$value['shown'];
	}
	$_koef = array();
	foreach ($_arrSplit[0]['arrCom'] as $key => $value) {
		$_koef[] = (int)(($_view_all - (int)$value['shown']) * 100 / $_view_all);
	}
	$_koef_sum = array_sum ($_koef);
	$r = rand(0, $_koef_sum);
	
	if($r <= $_koef[0]) {
		$link->withSplitIds(array($_arrSplit[0]['arrCom'][0]['split_id']))->withIds(array($_arrSplit[0]['arrCom'][0]['campaign_id']))->updateLink();
		$url = $_arrSplit[0]['arrCom'][0]['url'];
		$splittest = $_arrSplit[0]['arrCom'][0]['split_id'];
		
		header('Location: '.$url .'?splittest='.$splittest);
		exit;
		//$this->out['campaign_id'] = $_arrSplit['arrCom'][0]['campaign_id'];
	} else {
		$_tmp = $_koef[0];
		for($i = 1; $i < count($_koef); $i++) {
			$_tmp += $_koef[$i];
			if($r <= $_tmp) {
				$link->withSplitIds(array($_arrSplit[0]['arrCom'][$i]['split_id']))->withIds(array($_arrSplit[0]['arrCom'][$i]['campaign_id']))->updateLink();
				$url = $_arrSplit[0]['arrCom'][$i]['url'];
				$splittest = $_arrSplit[0]['arrCom'][$i]['split_id'];

				header('Location: '.$url .'?splittest='.$splittest);
				exit;
			}
		}
	}
}


?>