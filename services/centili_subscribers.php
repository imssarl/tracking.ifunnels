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
$_str.="\n".date('d.m.Y H:i:s').' - CENTILI - POST '.serialize($_POST).' - GET '.serialize($_GET);
Core_Files::setContent($_str,'./billing-'.date('Y-m-d').'.txt');
//$_fields=array( 'id', 'aggregator', 'status', 'errormessage', 'event_type', 'clientid', 'revenuecurrency', 'phone', 'amount', 'service', 'transactionid', 'enduserprice', 'country', 'mno', 'mnocode', 'revenue', 'interval', 'opt_in_channel', 'sign', 'userid', 'added' );
$_billing=new Project_Billing();
$_billing
	->withTransactionId( $_GET['transactionid'] )
	->getList( $_arrBillings );
$_listLastBillings=Project_Ccs_Twilio_Billing::lastBillings($_arrBillings);
if( isset( $_GET ) && !empty( $_GET ) ){
	if( @$_GET['test'] === 'true' ){
		echo( "Save datas<br/>" );
	}
	$_GET['added']=time();
	$_GET['aggregator']='centili';
	$_billing=new Project_Billing();
	$_billing
		->setEntered( @$_GET )
		->set();
}else{
	header("HTTP/1.1 200 OK");
	exit;
}
if( !isset( $_listLastBillings['centili'][$_REQUEST['transactionid']]['flg_rebiling'] )
	&& isset( $_GET['status'] )
	&& isset( $_GET['event_type'] )
	&& $_GET['status'] == 'success'
	&& $_GET['event_type'] == 'opt_in'
){
	if( isset( $_GET['revenue'] )
		&& isset( $_GET['clientid'] )
		&& !empty( $_GET['revenue'] )
		&& !empty( $_GET['clientid'] )
		&& $_GET['revenue'] != 0
	){
		$_link='http://www.igo.pe/aff_lsr?transaction_id='.@urlencode( $_GET['clientid'] );
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
	if( isset( $_GET['phone'] ) 
		&& !empty( $_GET['phone'] ) 
	){
		$_link='https://zapier.com/hooks/catch/ocdoqb/?phone='.@urlencode( $_GET['phone'] );
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
}
header("HTTP/1.1 200 OK");
exit;
?>