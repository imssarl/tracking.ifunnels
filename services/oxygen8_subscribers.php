<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

if( !isset( $_GET['restore_data'] ) ){
	Core_Files::getContent($_str,'./billing-'.date('Y-m-d').'.txt');
	$_str.="\n".date('d.m.Y H:i:s').' - OXIGEN8 - POST '.serialize($_POST).' - GET '.serialize($_GET);
	Core_Files::setContent($_str,'./billing-'.date('Y-m-d').'.txt');
}else{
	$_REQUEST=@unserialize( $_GET['restore_data'] );
	if( $_REQUEST === false ){
		exit;
	}
}
if( isset( $_GET['clientId'] ) && !empty( $_GET['clientId'] ) ){
	if( $_GET['test'] === 'true' ){
		echo( "Save client datas<br/>" );
	}
	$_oxigen8=new Project_Billing_Oxigen8();
	if( $_oxigen8
		->setEntered(array(
			'transactionid'=>$_GET['transactionID'],
			'service'=>$_GET['serviceID'],
			'clientid'=>$_GET['clientId'],
			'added'=>time()
		))
		->set() ){
		echo 'success';
	}
	exit;
}
if( isset( $_REQUEST['transactionID'] ) ){
	$arrBill=array(
		'added'=>time(),
		'aggregator'=>'oxigen8',
		'transactionid'=>$_REQUEST['transactionID']
	);
	if( $_REQUEST['status'] == 'successful' ){
		$arrBill['status']='success';
	}else{
		$arrBill['status']='failed';
	}
}else{
	header("HTTP/1.1 404 Not Found");
	exit;
}
if( isset( $_REQUEST['transactionID'] ) && !empty( $_REQUEST['transactionID'] ) ){
	$_oxigen8=new Project_Billing_Oxigen8();
	$_oxigen8
		->withTransactionId( $_REQUEST['transactionID'] )
		->onlyOne()
		->getList( $_arrBillings );
	if( isset( $_arrBillings['clientid'] ) ){
		$arrBill['clientid']=$_arrBillings['clientid'];
	}
}
if( isset( $_REQUEST['event'] ) ){
	if( $_REQUEST['event'] == 'subscribe' ){
		$arrBill['event_type']='opt_in';
		$arrBill['revenue']='7.00';
	}else{
		$arrBill['event_type']='opt_out';
	}
}
if( !isset( $arrBill['event_type'] ) && isset( $_REQUEST['reason'] ) && $_REQUEST['reason'] == 'SUCCEEDED' ){
		$arrBill['event_type']='opt_in';
		$arrBill['revenue']='7.00';
}
if( isset( $_REQUEST['msisdn'] ) ){
	$arrBill['phone']=$arrBill['userid']=$_REQUEST['msisdn'];
}
if( isset( $_REQUEST['serviceID'] ) ){
	$arrBill['service']=$_REQUEST['serviceID'];
}
if( isset( $_REQUEST['serviceID'] ) ){
	$arrBill['service']=$_REQUEST['serviceID'];
}

if( $_REQUEST['test'] === 'true' ){
	echo( "Save datas<br/>" );
}
$_billing=new Project_Billing();
$_billing
	->setEntered( $arrBill )
	->set();
if( isset( $arrBill['status'] )
	&& isset( $arrBill['event_type'] )
	&& $arrBill['status'] == 'success'
	&& $arrBill['event_type'] == 'opt_in'
){
	if( isset( $arrBill['clientid'] )
		&& !empty( $arrBill['clientid'] )
	){
		$_link='http://www.igo.pe/aff_lsr?transaction_id='.@urlencode( $arrBill['clientid'] );
		if( $_GET['test'] === 'true' ){
			echo( $_link. "<br/>" );
		}else{
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $_link);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			/*$output = */curl_exec($ch);
			curl_close($ch);
		}
	}
	if( isset( $arrBill['phone'] ) 
		&& !empty( $arrBill['phone'] ) 
	){
		$_link='https://zapier.com/hooks/catch/ocdoqb/?phone='.@urlencode( $arrBill['phone'] );
		if( $_GET['test'] === 'true' ){
			echo( $_link. "<br/>" );
		}else{
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $_link);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			/*$output = */curl_exec($ch);
			curl_close($ch);
		}
	}
	header("HTTP/1.1 200 OK");
	exit;
}
header("HTTP/1.1 404 Not Found");
exit;
?>