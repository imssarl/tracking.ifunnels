<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

$_object=new Project_Traffic();
$_object->checkIP();
Core_Files::getContent($_str,'./services/traffic/data-'.date('Y-m-d').'.txt');
$_str.="\n".date('d.m.Y H:i:s').' - POST '.serialize($_POST).' - GET '.serialize($_GET).' FROM '.$_SERVER['REQUEST_URI'].' IP '.$_object->ip;
Core_Files::setContent($_str,'./services/traffic/data-'.date('Y-m-d').'.txt');
	
//CHECK only from CNM
if( $_object->ip== '188.166.24.199' || $_object->ip== '127.0.0.1' ){
	
	if( isset( $_GET['action'] )
		&& !empty( $_GET['action'] )
		&& in_array( $_GET['action'], array( 'set', 'del', 'getList', 'end' ) )
	){
		if( !empty( $_GET['toSelect'] ) ){
			$_object->toSelect();
		}
		if( !empty( $_GET['onlyIds'] ) ){
			$_object->onlyIds();
		}
		if( !empty( $_GET['onlyCell'] ) ){
			$_object->onlyCell();
		}
		if( !empty( $_GET['onlyCount'] ) ){
			$_object->onlyCount();
		}
		if( !empty( $_GET['onlyOne'] ) ){
			$_object->onlyOne();
		}
		if( !empty( $_GET['keyRecordForm'] ) ){
			$_object->keyRecordForm();
		}
		if( !empty( $_GET['onlyActive'] ) ){
			$_object->onlyActive();
		}
		if( !empty( $_GET['withIds'] ) ){
			$_object->withIds( $_GET['withIds'] );
		}
		if( !empty( $_GET['withPaging'] ) ){
			$_object->withPaging( $_GET['withPaging'] );
		}
		if( !empty( $_GET['withOrder'] ) ){
			$_object->withOrder( $_GET['withOrder'] );
		}
		if( !empty( $_GET['withUserId'] ) ){
			$_object->withUserId( $_GET['withUserId'] );
		}
		if( !empty( $_GET['withoutUserId'] ) ){
			$_object->withoutUserId( $_GET['withoutUserId'] );
		}
		if( !empty( $_GET['withCategoryId'] ) ){
			$_object->withCategoryId( $_GET['withCategoryId'] );
		}
		if( !empty( $_GET['onAction'] ) ){
			$_object->onAction();
		}
		if( !empty( $_GET['onlyPromoted'] ) ){
			$_object->onlyPromoted();
		}
		if( !empty( $_GET['onlyNotEnd'] ) ){
			$_object->onlyNotEnd();
		}
		switch ($_GET['action']){
			case 'set':
				if( !empty( $_GET['data'] ) ){
					$_object->setEntered( $_GET['data'] );
				}
				if( $_object->set() ){
					echo 'true';
				}else{
					echo 'false';
				}
			break;
			case 'del':
				$_object->del();
			break;
			case 'end':
				$_object->end();
			break;
			case 'getList':
				$_object
					->getList( $_return )
					->getPaging( $_paging )
					->getFilter( $_cashe );
				echo serialize( array( 
					'data' => $_return,
					'paging' => $_paging,
					'cashe' => $_cashe
				) );
			break;
		}
		exit;
	}
	
	if( isset( $_GET['credit'] )
		&& !empty( $_GET['credit'] )
		&& in_array( $_GET['credit'], array( 'get', 'add' ) )
	){
		$haveCredits=0;
		$_credits=new Project_Traffic_Credits();
		if( !empty( $_GET['id'] ) ){
			$_credits->onlyOne()->withIds( $_GET['id'] )->getList( $arrData );
			if( isset( $arrData['credits'] ) ){
				$haveCredits=$arrData['credits'];
			}
		}
		switch ( $_GET['credit'] ) {
			case 'get':
				echo ''.$haveCredits;
			break;
			case 'add':
				if( isset( $_GET['amount'] ) ){
					$_credits->setEntered(array( 
						'id'=>$_GET['id'],
						'credits'=>$haveCredits+$_GET['amount']
					))->set();
				}
				echo ''.($haveCredits+@$_GET['amount']);
			break;
		}
		exit;
	}
	
	if( isset( $_GET['locking'] )
		&& !empty( $_GET['locking'] )
	){
		$_subscribers=new Project_Traffic_Subscribers();
		echo $_subscribers->setLockingChange( $_GET['user_id'], $_GET['campaign_id'] );
		exit;
	}
	
	if( isset( $_GET['seturl'] )
		&& !empty( $_GET['seturl'] )
	){
		$_siteUrl=htmlspecialchars_decode( $_GET['seturl'] );
		Core_Files::setContent( $_siteUrl,'./services/traffic/remote_url.txt' );
		exit;
	}

	if( isset( $_GET['geturl'] )
		&& !empty( $_GET['geturl'] )
	){
		Core_Files::getContent($endUrl,'./services/traffic/remote_url.txt' );
		if( strpos( $endUrl, 'http' ) !== 0 ){
			$endUrl='http://'.$endUrl;
		}
		echo $endUrl;
		exit;
	}
	
	if( isset( $_GET['setads'] )
		&& !empty( $_GET['setads'] )
	){
		$_siteUrl=base64_decode( htmlspecialchars_decode( $_GET['setads'] ) );
		Core_Files::setContent( $_siteUrl,'./services/traffic/remote_ads.txt' );
		exit;
	}

	if( isset( $_GET['getads'] )
		&& !empty( $_GET['getads'] )
	){
		Core_Files::getContent($_adsScript,'./services/traffic/remote_ads.txt' );
		echo $_adsScript;
		exit;
	}

	
}

//from anywear
if( ( isset( $_GET['v'] )
	&& !empty( $_GET['v'] )
	&& isset( $_GET['u'] )
	&& !empty( $_GET['u'] ) )
	|| isset( $_GET['c'] )
){
	Core_Files::getContent($endUrl,'./services/traffic/remote_url.txt' );
	if( strpos( $endUrl, 'http' ) !== 0 ){
		$endUrl='http://'.$endUrl;
	}
	if( !empty( $_GET['c'] ) ){
		$_GET=$_object->regenerateUrl( $_GET['c'] )+(array)$_GET;
	}
	if( !( isset( $_GET['v'] )
		&& !empty( $_GET['v'] )
		&& isset( $_GET['u'] )
		&& !empty( $_GET['u'] ) )
	){
		$_object->showAds( $endUrl );
		exit;
	}
	$_subscribers=new Project_Traffic_Subscribers();
	$_object->withIds( array( $_GET['v'] ) )->onlyOne()->withoutUserId( $_GET['u'] )->onAction()->notLinkUpdate()->getList( $arrCampaign );
	if( strpos( $arrCampaign['url'], 'http' ) !== 0 ){
		$arrCampaign['url']='http://'.$arrCampaign['url'];
	}
	if( $arrCampaign['flg_locking'] > 0 ){
		$_object->showAds( $endUrl );
		exit;
	}
	$_subscribers->withIP( $_object->ip )->withCampaignId( $_GET['v'] )->onlyOne()->onlyCount()->getList( $_viewCount );
	if( $_viewCount > 0 ){
		$_object->showAds( $arrCampaign['url'] );
		exit;
	}
	$_subscribers->withCampaignId( $_GET['v'] )->onlyOne()->onlyCount()->getList( $_countAllView );
	$_credits=new Project_Traffic_Credits();
	$_credits->onlyOne()->withIds( $arrCampaign['user_id'] )->getList( $creditsFrom );
	if( $_countAllView < $arrCampaign['credits'] && $creditsFrom>0 ){
		$_object->showAds( $arrCampaign['url'], $arrCampaign['user_id'] );
	}else{
		$_object->showAds( $endUrl );
	}
	//exit;
}
?>