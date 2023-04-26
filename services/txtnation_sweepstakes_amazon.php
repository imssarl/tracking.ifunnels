<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

Core_Files::getContent($_str,'./billing-'.date('Y-m-d').'.txt');
$_str.="\n".date('d.m.Y H:i:s').' - TXTNATIONS - POST '.serialize($_POST).' - GET '.serialize($_GET);
Core_Files::setContent($_str,'./billing-'.date('Y-m-d').'.txt');
/*
/services/txtnation_subscribers.php?status=OK&key=1234567890abcdef1234567890abcdef&billed=1&transactionId=1258470153&msisdn=447445566731&network=THREEUK&channel=weblite&browser=LG-A133&optin=header&test=true
*/
$arrBill=array();
if( isset( $_REQUEST['transactionId'] ) 
	&& ( (isset( $_REQUEST['billed'] ) && $_REQUEST['billed']==1 )
		|| (isset( $_REQUEST['billed'] ) && $_REQUEST['billed']==0 && isset( $_REQUEST['stop'] ) && $_REQUEST['stop'] == 1 ) 
	) 
){
	$arrBill['transactionid']=$_REQUEST['transactionId'];
	$arrBill['country']='gb';
	if( !isset( $_REQUEST['stop'] ) || $_REQUEST['stop'] != 1 ){
		$arrBill['enduserprice']=4.5;
		$arrBill['revenue']=4.5;
		$arrBill['revenuecurrency']='GBP';
	}
	$arrBill['added']=time();
	$arrBill['aggregator']='txtnations';
}else{
	header("HTTP/1.1 404 Not Found");
	exit;
}
if( isset( $_REQUEST['status'] ) ){
	$arrBill['status']=( $_REQUEST['status']=='OK' )?'success':( ( $_REQUEST['status']=='FAILED')?'failed': $_REQUEST['status'] );
}
if( isset( $_REQUEST['stop'] ) && isset( $_REQUEST['billed'] ) && $_REQUEST['stop'] == 1 && $_REQUEST['billed'] == 0 ){
	$arrBill['event_type']='opt_out';
}elseif( isset( $_REQUEST['billed'] ) && $_REQUEST['billed'] == 1 ){
	$arrBill['event_type']='opt_in';
}
if( isset( $_REQUEST['msisdn'] ) ){
	$arrBill['phone']=$arrBill['userid']=$_REQUEST['msisdn'];
}elseif( isset( $_REQUEST['transactionId'] ) && !empty( $_REQUEST['transactionId'] ) ){
	$_billing=new Project_Billing();
	$_billing
		->withTransactionId( $_REQUEST['transactionId'] )
		->getList( $_arrBillings );
	foreach( $_arrBillings as $_bill ){
		if( isset( $_bill['phone'] ) && !empty( $_bill['phone'] ) ){
			$arrBill['phone']=$arrBill['userid']=$_bill['phone'];
			break;
		}
	}
}
if( isset( $_REQUEST['network'] ) ){
	$arrBill['mnocode']=$_REQUEST['network'];
}
if( isset( $_REQUEST['channel'] ) ){
	$arrBill['opt_in_channel']=$_REQUEST['channel'];
}
if( isset( $_REQUEST['key'] ) ){
	$arrBill['clientid']=$_REQUEST['key'];
}
$_billing=new Project_Billing();
$_billing
	->withTransactionId( $_REQUEST['transactionId'] )
	->getList( $_arrBillings );
$_listLastBillings=Project_Ccs_Twilio_Billing::lastBillings($_arrBillings);
if( !empty( $arrBill ) ){
	if( @$_GET['test'] === 'true' ){
		echo( "Save datas<br/>" );
	}
	$_billing=new Project_Billing();
	$_billing
		->setEntered( $arrBill+array( 'services'=>3 ) )
		->set();
}else{
	header("HTTP/1.1 404 Not Found");
	exit;
}
if( !isset( $_listLastBillings['txtnations'][$_REQUEST['transactionId']]['flg_rebiling'] )
	&& isset( $_REQUEST['status'] )
	&& $_REQUEST['status']=='OK'
	&& isset( $_REQUEST['billed'] )
	&& $_REQUEST['billed']==1
){
	if( isset( $_REQUEST['key'] )
		&& !empty( $_REQUEST['key'] )
	){
		$_link='http://www.igo.pe/aff_lsr?transaction_id='.@urlencode( $_REQUEST['key'] );
		if( @$_GET['test'] === 'true' ){
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
	if( isset( $_REQUEST['msisdn'] ) 
		&& !empty( $_REQUEST['msisdn'] ) 
	){
		$_link='https://zapier.com/hooks/catch/ocdoqb/?phone='.@urlencode( $_REQUEST['msisdn'] );
		if( @$_GET['test'] === 'true' ){
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