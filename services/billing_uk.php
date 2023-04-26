<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

$_billing=new Project_Billing();
$_billing
	->withOrder( 'added--dn' )
//	->withAggregator( 'txtnations' )
	->getList( $_arrList );
$_checkUsers=array();
header('Content-Disposition: attachment; filename="bills.txt"');
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Description: all mobile numbers from UK");
foreach( $_arrList as $_bills ){
	if( empty( $_bills['userid'] ) || strlen( $_bills['userid'] ) != 12 ){
		continue;
	}
	if( !in_array( $_bills['userid'], $_checkUsers ) ){
		$_checkUsers[]=$_bills['userid'];
	}
}
$_str=implode( "\n", $_checkUsers );
header('Content-Length: ' . strlen( $_str ) );
echo $_str;


?>