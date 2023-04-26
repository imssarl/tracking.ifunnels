<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

Core_Files::getContent($_str,'./services/lottery/data-'.date('Y-m-d').'.txt');

Core_Files::getContent($_req,'./services/lottery/requests-'.date('Y-m-d').'.txt');
$_req.="\n".date('d.m.Y H:i:s').' - $_REQUEST '.serialize( $_REQUEST );
Core_Files::setContent($_req,'./services/lottery/requests-'.date('Y-m-d').'.txt');

function returnFalse( $_str ){
	Core_Files::setContent($_str,'./services/lottery/data-'.date('Y-m-d').'.txt');
	header("HTTP/1.1 404 Not Found");
	exit;
}

function returnOK( $_str ){
	Core_Files::setContent($_str,'./services/lottery/data-'.date('Y-m-d').'.txt');
	echo 'OK';
	header("HTTP/1.1 200 OK");
	exit;
}

function checkUserNowResponse( $_u2m=array(), $_intM=0, $_uId=0, $_wD=1 ){
	if( !isset( $_u2m[ $_uId ][ 'MT'.$_intM ][ $_wD ] ) ){ // сегодня пользователю не отправляли сообщения
		switch ( $_wD ){
			case 1: //Mo
				if( $_intM == 1 ){
					return true;
				}
				return false;
			break;
			case 2: //Tu
				if( $_intM == 1 
					&& ( !isset( $_u2m[ $_uId ][ 'MT'.$_intM ][ 1 ] ) || empty( $_u2m[ $_uId ][ 'MT'.$_intM ][ 1 ]['billing_delivered'] ) )
				){
					return true;
				}
				return false;
			break;
			case 3: //We
				if( $_intM == 1 
					&& ( !isset( $_u2m[ $_uId ][ 'MT'.$_intM ][ 1 ] ) || empty( $_u2m[ $_uId ][ 'MT'.$_intM ][ 1 ]['billing_delivered'] ) )
					&& ( !isset( $_u2m[ $_uId ][ 'MT'.$_intM ][ 2 ] ) || empty( $_u2m[ $_uId ][ 'MT'.$_intM ][ 2 ]['billing_delivered'] ) )
				){
					return true;
				}
				if( $_intM == 2
					&& ( ( isset( $_u2m[ $_uId ][ 'MT1' ][ 1 ] ) && !empty( $_u2m[ $_uId ][ 'MT1' ][ 1 ]['billing_delivered'] ) )
						|| ( isset( $_u2m[ $_uId ][ 'MT1' ][ 2 ] ) && !empty( $_u2m[ $_uId ][ 'MT1' ][ 2 ]['billing_delivered'] ) )
						|| ( isset( $_u2m[ $_uId ][ 'MT1' ][ 3 ] ) && !empty( $_u2m[ $_uId ][ 'MT1' ][ 3 ]['billing_delivered'] ) )
					)
				){
					return true;
				}
				return false;
			break;
			case 4: //Th
				if( $_intM == 2
					&& ( ( isset( $_u2m[ $_uId ][ 'MT1' ][ 1 ] ) && !empty( $_u2m[ $_uId ][ 'MT1' ][ 1 ]['billing_delivered'] ) )
						|| ( isset( $_u2m[ $_uId ][ 'MT1' ][ 2 ] ) && !empty( $_u2m[ $_uId ][ 'MT1' ][ 2 ]['billing_delivered'] ) )
						|| ( isset( $_u2m[ $_uId ][ 'MT1' ][ 3 ] ) && !empty( $_u2m[ $_uId ][ 'MT1' ][ 3 ]['billing_delivered'] ) )
					)
					&& ( !isset( $_u2m[ $_uId ][ 'MT'.$_intM ][ 3 ] ) || empty( $_u2m[ $_uId ][ 'MT'.$_intM ][ 3 ]['billing_delivered'] ) )
				){
					return true;
				}
				return false;
			break;
			case 5: //Fr
				if( $_intM == 3
					&& ( ( isset( $_u2m[ $_uId ][ 'MT2' ][ 3 ] ) && !empty( $_u2m[ $_uId ][ 'MT2' ][ 3 ]['billing_delivered'] ) )
						|| ( isset( $_u2m[ $_uId ][ 'MT2' ][ 4 ] ) && !empty( $_u2m[ $_uId ][ 'MT2' ][ 4 ]['billing_delivered'] ) )
					)
				){
					return true;
				}
				return false;
			break;
			case 6: //Sa
				if( $_intM == 3
					&& ( ( isset( $_u2m[ $_uId ][ 'MT2' ][ 3 ] ) && !empty( $_u2m[ $_uId ][ 'MT2' ][ 3 ]['billing_delivered'] ) )
						|| ( isset( $_u2m[ $_uId ][ 'MT2' ][ 4 ] ) && !empty( $_u2m[ $_uId ][ 'MT2' ][ 4 ]['billing_delivered'] ) )
					)
					&& ( !isset( $_u2m[ $_uId ][ 'MT'.$_intM ][ 5 ] ) || empty( $_u2m[ $_uId ][ 'MT'.$_intM ][ 5 ]['billing_delivered'] ) )
				){
					return true;
				}
				return false;
			break;
			case 7: //Su
				return false;
			break;
		}
		return true;
	}
	return false;
}

$_txtnation=new Project_Billing_Txtnation();

if( !empty( $_POST ) || !empty( $_GET ) ){
	$allowedServers=array(
		'5.39.71.100',
		'149.202.136.48',
		'149.202.136.49',
		'149.202.136.50',
		'149.202.136.51',
		'149.202.136.52',
		'149.202.136.53',
		'149.202.136.54',
		'149.202.136.55',
		'162.13.59.148',
		'162.13.52.70',
		'162.13.104.239',
		'162.13.56.28',
		'127.0.0.1'
	);
	if ( !in_array( $_SERVER['REMOTE_ADDR'], $allowedServers ) ){
		$_str.="\n".date('d.m.Y H:i:s').' - PIRATE REQUEST '.serialize( $_REQUEST )."\n".' - SERVER '.serialize( $_SERVER );
		returnFalse( $_str );
	}
	$_str.="\n".date('d.m.Y H:i:s').' - REQUEST '.serialize( $_REQUEST );
	
	if( isset( $_REQUEST['report'] ) && strtoupper( $_REQUEST['report'] )=='ACKNOWLEDGED' ){
		returnOK( $_str );
	}
	
	// первый ответ от сервиса a:6:{s:9:"reason_id";s:0:"";s:6:"report";s:9:"DELIVERED";s:6:"number";s:12:"447799906908";s:10:"message_id";s:3:"244";s:2:"id";s:3:"244";s:6:"action";s:9:"mp_report";}
	if( isset( $_REQUEST['action'] ) && $_REQUEST['action']=='mp_report'
		&& isset( $_REQUEST['report'] ) 
		&& in_array( strtoupper( $_REQUEST['report'] ), array( 'DELIVERED', 'ACCEPTED' ) )
	){
		if( $_txtnation->withUserId( $_REQUEST['number'] )->withSendId( $_REQUEST['id'] )->onlyOne()->getList( $_getData )->checkEmpty() // !empty - true empty - false
			&& empty( $_getData['send_delivered'] )
		){
			$_getData['send_delivered']=serialize( $_REQUEST );
			if( $_txtnation->setEntered( $_getData )->set() ){
				$_str.="\n".date('d.m.Y H:i:s').' - User '.$_REQUEST['number'].' get delivered for first message';
				returnOK( $_str );
			}
			returnFalse( $_str );
		}
		if( $_txtnation->withUserId( $_REQUEST['number'] )->withResponseId( $_REQUEST['id'] )->onlyOne()->getList( $_getData )->checkEmpty() // !empty - true empty - false
			&& empty( $_getData['billing_delivered'] ) 
		){
			$_getData['billing_delivered']=serialize( $_REQUEST );
			if( $_txtnation->setEntered( $_getData )->set() ){
				$_str.="\n".date('d.m.Y H:i:s').' - User '.$_REQUEST['number'].' get delivered for second message';
				returnOK( $_str );
			}
			returnFalse( $_str );
		}
	}
	// второй ответ от сервиса a:8:{s:6:"action";s:16:"mpush_ir_message";s:2:"id";s:9:"546366996";s:7:"billing";s:2:"MT";s:7:"country";s:2:"UK";s:6:"number";s:12:"447799906908";s:7:"network";s:12:"VODAFONE14UK";s:9:"shortcode";s:5:"68899";s:7:"message";s:3:"ims";}
	if( isset( $_REQUEST['action'] ) && $_REQUEST['action']=='mpush_ir_message'
		&& isset( $_REQUEST['message'] ) && $_REQUEST['message'] == 'ims'
	){
		$_getData=array();
		$_txtnation->withUserId( $_REQUEST['number'] )->getList( $_arrUserMessages );
		if( count( $_arrUserMessages ) > 0 ){
			// check is_access message_id for this user
			foreach( $_arrUserMessages as $_arrData ){
				if( empty( $_arrData['response'] ) ){
					$_getData=$_arrData;
					break;
				}
			}
		}
		if( $_txtnation->checkEmpty() // !empty - true empty - false
			&& empty( $_getData['response'] )
		){
			$_getData['response']=serialize( $_REQUEST );
			$_getData['response_id']=$_REQUEST['id'];
			$_arrSendData=unserialize( str_replace( 'в‚¤', '£ ', $_getData['send'] ) ); // преобразуем сжатый фунт в нормальный для распаковки
			$_network=@$_REQUEST['network'];
			if( isset( $_arrSendData['network'] ) ){
				$_network=$_arrSendData['network'];
			}elseif( empty( $_network ) ){
				$_network='international';
			}
			if( $_txtnation->setEntered( $_getData )->set() ){
				sleep( 2 );
				$_amount=5;
				$_txtnation->sendMessage( $_getData['userid'], $_getData['message_id'], $_network, $_amount );
				$_str.="\n".date('d.m.Y H:i:s').' - SEND message '.$_getData['message_id'].' to user '.$_getData['userid'].' with amount '.$_amount;
				returnOK( $_str );
			}
			returnFalse( $_str );
		}
	}
	// третий ответ от сервиса ?? ещё ни разу не получали

	if( isset( $_REQUEST['report'] ) 
		&& in_array( strtoupper( $_REQUEST['report'] ), array( 'DELIVERED', 'ACCEPTED' ) )
	){
		$_getData=array();
		$_txtnation->withUserId( $_REQUEST['number'] )->getList( $_arrUserMessages );
		if( count( $_arrUserMessages ) > 0 ){
			// check is_access message_id for this user
			foreach( $_arrUserMessages as $_arrData ){
				if( empty( $_arrData['billing_delivered'] ) ){
					$_getData=$_arrData;
					break;
				}
			}
		}
		if( $_txtnation->checkEmpty() // !empty - true empty - false
			&& empty( $_getData['billing_delivered'] )
		){
			$_getData['billing_delivered']=serialize( $_REQUEST );
			if ( $_txtnation->setEntered( $_getData )->set() ){
				$_str.="\n".date('d.m.Y H:i:s').' - User '.$_REQUEST['number'].' ACCEPT message '.$_REQUEST['id'];
				returnOK( $_str );
			}
			returnFalse( $_str );
		}
	}
	returnFalse( $_str );
}

$_str.="\n".date('d.m.Y H:i:s').' - run lottery action';
$_billing=new Project_Billing();
$_billing
	->withOrder( 'added--dn' )
	->withAggregator( 'txtnations' )
	->getList( $_arrList );
$_userPhones=$_usersRebills=$_userPhonesClosers=array();
foreach( $_arrList as $_bills ){
	if( empty( $_bills['userid'] ) || strlen( $_bills['userid'] ) != 12 ){
		continue;
	}
	if(  $_bills['event_type'] == 'opt_in' && $_bills['status'] == 'success' ){
		if( isset( $_userPhones[$_bills['userid']] ) ){
			if( isset( $_usersRebills[$_bills['userid']] ) ){
				$_usersRebills[$_bills['userid']]++;
			}else{
				$_usersRebills[$_bills['userid']]=1;
			}
		}else{
			$_userPhones[$_bills['userid']]=true;
			switch( $_bills['mnocode'] ){
				case 'O2UK': $_userPhonesClosers[$_bills['userid']] = 'O214UK'; break;
				case 'THREEUK': $_userPhonesClosers[$_bills['userid']] = 'THREE14UK'; break;
				case 'TMOBILEUK': $_userPhonesClosers[$_bills['userid']] = 'TMOBILE14UK'; break;
				case 'ORANGEUK': $_userPhonesClosers[$_bills['userid']] = 'ORANGE14UK'; break;
				case 'VODAFONEUK': $_userPhonesClosers[$_bills['userid']] = 'VODAFONE14UK'; break;
				default: $_userPhonesClosers[$_bills['userid']] = 'international'; break;
			}
		}
	}
	if(  $_bills['event_type'] == 'opt_out' && $_bills['status'] != 'failed' ){
		if( isset( $_userPhones[$_bills['userid']] ) ){
			unset( $_userPhones[$_bills['userid']] );
			if( isset( $_userPhonesClosers[$_bills['userid']] ) ){
				unset( $_userPhonesClosers[$_bills['userid']] );
			}
		}
	}
}
$_lotteryList=array();
foreach( $_usersRebills as $_phone=>$_rebills ){
	if( $_rebills >= 2 ){
		$_lotteryList[]=$_phone;
	}
}
sort( $_lotteryList );
/*
$_lotteryList=array(
	//'447445566731','447506548877','447772256212','447808242645',
	'447799906908'
);
$_userPhonesClosers=array(
	'447445566731'=>'THREE14UK',
	'447506548877'=>'TMOBILE14UK',
	'447772256212'=>'ORANGE14UK',
	'447808242645'=>'O214UK',
	'447799906908'=>'VODAFONE14UK'
);
*/
$_str.="\n lottery users list count = ".count( $_lotteryList );

$_txtnation->getList( $_getUsersData );

$_u2m=array();
foreach( $_getUsersData as $_user ){
	if( !isset( $_u2m[ $_user['userid'] ] ) ){
		$_u2m[ $_user['userid'] ]=array();
	}
	if( !isset( $_u2m[ $_user['userid'] ][ 'MT'.$_user['message_id'] ] ) ){
		$_u2m[ $_user['userid'] ][ 'MT'.$_user['message_id'] ]=array();
	}
	if( !isset( $_u2m[ $_user['userid'] ][ 'MT'.$_user['message_id'] ][ date( 'N', $_user['added'] ) ] ) ){
		$_u2m[ $_user['userid'] ][ 'MT'.$_user['message_id'] ][ date( 'N', $_user['added'] ) ]=$_user;
	}
}

$_time=time();

$_timeHour=date( 'G', $_time );
$_timeWeekDay=date( 'N', $_time );

switch ( $_timeWeekDay ){
	case 1: //Mo
		if( $_timeHour < 7 ){
			Project_Billing_Txtnation::install();
		}
	case 2: //Tu
	case 3: //We
		if( $_timeHour >= 7 && $_timeHour < 23 ){
			$_str.="\n".'week day '.$_timeWeekDay.' time hour '.$_timeHour;
			foreach( $_lotteryList as $_userId ){
				$_intMessage=1;
				if( checkUserNowResponse( $_u2m, $_intMessage, $_userId, $_timeWeekDay ) ){
					$_txtnation->sendMessage( $_userId, $_intMessage, $_userPhonesClosers[$_userId] );
					$_str.="\n".date('d.m.Y H:i:s').' - SEND message '.$_intMessage.' to user '.$_userId;
				}
				if( $_timeWeekDay == 3 ){
					$_intMessage=2;
					if( checkUserNowResponse( $_u2m, $_intMessage, $_userId, $_timeWeekDay ) ){
						$_txtnation->sendMessage( $_userId, $_intMessage, $_userPhonesClosers[$_userId] );
						$_str.="\n".date('d.m.Y H:i:s').' - SEND message '.$_intMessage.' to user '.$_userId;
					}
				}
			}
		}
	break;
	case 4: //Th
		if( $_timeHour >= 7 && $_timeHour < 23 ){
			foreach( $_lotteryList as $_userId ){
				$_intMessage=2;
				if( checkUserNowResponse( $_u2m, $_intMessage, $_userId, $_timeWeekDay ) ){
					$_txtnation->sendMessage( $_userId, $_intMessage, $_userPhonesClosers[$_userId] );
					$_str.="\n".date('d.m.Y H:i:s').' - SEND message '.$_intMessage.' to user '.$_userId;
				}
			}
		}
	break;
	case 5: //Fr
	case 6: //Sa
		if( $_timeHour >= 7 && $_timeHour < 17 ){
			foreach( $_lotteryList as $_userId ){
				$_intMessage=3;
				if( checkUserNowResponse( $_u2m, $_intMessage, $_userId, $_timeWeekDay ) ){
					$_txtnation->sendMessage( $_userId, $_intMessage, $_userPhonesClosers[$_userId] );
					$_str.="\n".date('d.m.Y H:i:s').' - SEND message '.$_intMessage.' to user '.$_userId;
				}
			}
		}
		$_winnerFile='./services/lottery/winner-'.date('W').'.txt';
		if( $_timeHour >= 17 && $_timeHour < 23 && !is_file($_winnerFile) ){
			$_txtnation->withDelivered()->getList( $_getWinnersList );
			$_winner=array_rand( $_getWinnersList, 1 );
			$_txtMessage="WINNER id (phone): ".$_getWinnersList[$_winner]['userid']." deliver on message ".$_getWinnersList[$_winner]['message_id']."\n";
			$_txtMessage.="\nFrom users list!\n";
			foreach( $_getWinnersList as $_user ){
				$_txtMessage.="\nUser id (phone): ".$_user['userid']." deliver on message ".$_user['message_id']." at ".date('d.m.Y H:i:s', $_user['added'] );
			}
			Core_Files::setContent( $_txtMessage, $_winnerFile);
			// save all data to file
			$_txtnation->getList( $_listAll );
			$_dataAll='';
			foreach( $_listAll as $_user ){
				$_dataAll.="\n".serialize( $_user );
			}
			Core_Files::setContent( $_dataAll,'./services/lottery/alldata-'.date('W').'.txt' );
			// send email to admin
			$_txtnation->sendMail( $_txtMessage );
		}
	break;
	case 7: //Su
	break;
	
}
echo $_str;
Core_Files::setContent($_str,'./services/lottery/data-'.date('Y-m-d').'.txt');
?>