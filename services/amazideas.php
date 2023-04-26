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
if( $_object->ip== '188.166.24.199' || $_object->ip== '127.0.0.1' ){
	if( isset( $_GET['action'] ) && $_GET['action']=='seturl' ){
		echo Core_Files::setContent( $_GET['url'],'./services/amazideas/url.txt' );
		exit;
	}
	if( isset( $_GET['action'] ) && $_GET['action']=='geturl' ){
		Core_Files::getContent($_url,'./services/amazideas/url.txt' );
		echo $_url;
		exit;
	}
}
Core_Files::getContent($_url,'./services/amazideas/url.txt' );
header( "Location: ".$_url );
exit;
?>