<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if( isset( $_GET['t'] ) && !empty( $_GET['t'] ) ){
	$_billing=new Project_Billing();
	$_billing
		->withTransactionId( $_GET['t'] )
		->getList( $_arrBillings );
	$_listLastBillings=Project_Ccs_Twilio_Billing::lastBillings($_arrBillings);
	foreach( $_listLastBillings as $_service ){
		if( isset( $_service[$_GET['t']]['phone'] ) && $_service[$_GET['t']]['event_type'] != 'opt_out' ){
			die($_service[$_GET['t']]['phone']);
		}
	}
}

if( isset( $_GET['phone'] ) && !empty( $_GET['phone'] ) ){
	$_billing=new Project_Billing();
	$_billing
		->withPhone( $_GET['phone'] )
		->getList( $_arrBillings );
	$_listLastBillings=Project_Ccs_Twilio_Billing::lastBillings( $_arrBillings );
	foreach( $_listLastBillings as $_service ){
		foreach( $_service as $_transactions ){
			if( $_transactions['event_type'] == 'opt_in' ){
				die('YES');
			}
		}

	}
	die('NO');
}
die();