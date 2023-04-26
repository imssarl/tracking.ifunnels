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
Core_Files::getContent($_str,'./services/lpb/subscribers-'.date('Y-m-d').'.txt');
$_str.="\n".date('d.m.Y H:i:s').' - POST '.serialize($_POST).' - GET '.serialize($_GET).' FROM '.$_SERVER['REQUEST_URI'].' IP '.$_object->ip."\n";
Core_Files::setContent($_str,'./services/lpb/subscribers-'.date('Y-m-d').'.txt');

Project_Squeeze_Subscribers::checkSubscribers( $_REQUEST );
?>