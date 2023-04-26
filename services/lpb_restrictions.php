<?php
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

chdir( dirname(__FILE__) );
chdir( '../' );
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

$_object=new Project_Traffic();
$_object->checkIP();
Core_Files::getContent($_str,'./services/lpb/restrictions-'.date('Y-m-d').'.txt');
$_str.="\n".date('d.m.Y H:i:s').' - POST '.serialize($_POST).' - GET '.serialize($_GET).' FROM '.$_SERVER['REQUEST_URI'].' IP '.$_object->ip."\n";
Core_Files::setContent($_str,'./services/lpb/restrictions-'.date('Y-m-d').'.txt');

if( isset( $_GET['action'] ) && $_GET['action']=='geturl' ){
	Core_Files::getContent($_url,'./services/lpb/remote_url.txt' );
	echo $_url;
	exit;
}

//CHECK only from CNM
if( $_object->ip== '188.166.24.199' || $_object->ip== '127.0.0.1' ){
	if( isset( $_GET['action'] ) && $_GET['action']=='get_user_restrictions' ){
		$_restrictions=new Project_Squeeze_Restrictions();
		$subscribers=new Project_Squeeze_Subscribers();
		$_restrictions->withUserId( $_GET['uid'] )->getList( $_arrRestrictions );
		$_intSqueezeRestrictions=0;
		if( !empty( $_arrRestrictions ) ){
			foreach( $_arrRestrictions as $_key=>$_rest ){
				if( $_rest['restrictions'] == -1 ){
					echo 'unlim';
					exit;
				}
				if( $_rest['flg_type'] == 0 ){
					$_intSqueezeRestrictions+=$_rest['restrictions'];
				}elseif( $_rest['flg_type'] == 1 ){
					$subscribers->afterDate( $_rest['added'] )->withUID( $_GET['uid'] )->onlyOne()->onlyCount()->getList( $_subscribersCount );
					if( $_subscribersCount >= $_rest['restrictions'] && $_rest['added'] <= time()-60*60*24*30 ){
						if( !empty( $_rest['id'] ) ){
							$_restrictions->withIds( $_rest['id'] )->del();
						}
						unset( $_arrRestrictions[$_key] );
					}else{
						$_intSqueezeRestrictions+=$_rest['restrictions'];
					}
				}
			}
		}
		$subscribers->afterDate( time()-60*60*24*30 )->onlyOne()->onlyCount()->withUID( $_GET['uid'] )->getList( $_subscribersCount );
		echo max( $_intSqueezeRestrictions-$_subscribersCount, 0 );
		exit;
	}
	if( isset( $_GET['action'] ) && $_GET['action']=='seturl' ){
		echo Core_Files::setContent( $_GET['url'],'./services/lpb/remote_url.txt' );
		exit;
	}
	if( isset( $_GET['uid'] ) && !empty( $_GET['uid'] ) 
		&& isset( $_GET['restrictions'] ) && !empty( $_GET['restrictions'] ) 
	){
		$_restriction=new Project_Squeeze_Restrictions();
		if( $_GET['restrictions'] == '-all' ){
			$_GET['restrictions']=0;
			$_restriction->withUserId( $_GET['uid'] )->getList( $_arrIds );
			$_removeIds=array();
			if( !empty( $_arrIds ) ){
				foreach( $_arrIds as $_arrData ){
					$_removeIds[]=$_arrData['id'];
				}
			}
			if( !empty( $_removeIds ) ){
				$_restriction->withIds( $_removeIds )->del();
			}
		}elseif( $_GET['restrictions'] == 'unlim' ){
			$_GET['restrictions']=-1;
			$_restriction->withUserId( $_GET['uid'] )->getList( $_arrIds );
			$_removeIds=array();
			if( !empty( $_arrIds ) ){
				foreach( $_arrIds as $_arrData ){
					$_removeIds[]=$_arrData['id'];
				}
			}
			if( !empty( $_removeIds ) ){
				$_restriction->withIds( $_removeIds )->del();
			}
		}else{
			$_restriction->withUserId( $_GET['uid'] )->withFlgType( 0 )->getList( $_unlim );
			if( !empty( $_unlim ) ){
				foreach( $_unlim as $_arrData ){
					if( $_arrData['restrictions'] == -1 ){
						$_restriction->withIds( $_arrData['id'] )->del();
					}
				}
			}
			$_restriction->withUserId( $_GET['uid'] )->withFlgType( $_GET['flg_type'] )->getList( $_arrIds );
			$_summ=0;
			$_removeIds=array();
			if( !empty( $_arrIds ) ){
				foreach( $_arrIds as $_arrData ){
					$_summ+=$_arrData['restrictions'];
					$_removeIds[]=$_arrData['id'];
				}
			}
			if( !empty( $_removeIds ) ){
				$_restriction->withIds( $_removeIds )->del();
			}
			$_GET['restrictions']=$_summ+$_GET['restrictions'];
			if( $_GET['restrictions'] < 0 ){
				$_GET['restrictions']=0;
			}
		}
		$_restriction->setEntered( $_GET )->set();
	}
}
?>