<?php
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

chdir( '../' );
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
$obj=new Project_Amazon();
if(!empty($_REQUEST['link'])){
	if( isset( $_REQUEST['priority'] ) ){
		$obj->setPriority( $_REQUEST['priority'] );
	}
	echo $obj->setLink( $_REQUEST['link'] )->getAmazon();
}
?>