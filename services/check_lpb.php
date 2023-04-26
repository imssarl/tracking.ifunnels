<?php
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

chdir( dirname(__FILE__) );
chdir( '../' );
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

if( !isset( $_GET['id'] ) || empty( $_GET['id'] ) ){
	exit;
}

Core_Sql::getInstance();
try {
	Core_Sql::setConnectToServer( 'lpb.tracker' );
	//========
	$dataView = Core_Sql::getAssoc( 'SELECT * FROM `lpb_view_'.(int)$_GET['id'].'`' );
	$dataClick = Core_Sql::getAssoc( 'SELECT * FROM `lpb_click_'.(int)$_GET['id'].'`' );
	//========
	Core_Sql::renewalConnectFromCashe();
} catch(Exception $e) {
	Core_Sql::renewalConnectFromCashe();
	p( $e );
}

p( array($dataView, $dataClick) );

?>