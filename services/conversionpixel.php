<?php
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Access-Control-Allow-Origin: *');
chdir( '../' );
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
$obj=new Project_Conversionpixel();
if(!empty($_GET)){
	$obj->setEntered($_GET)->set();
}
?>