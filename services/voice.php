<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
error_reporting(E_ALL);
$_str='';
if(is_file('./voice-'.date('Y-m-d').'.txt')){
	Core_Files::getContent($_str,'./voice-'.date('Y-m-d').'.txt');
}
$_str.="\n".date('d.m.Y H:i:s').' - POST '.serialize($_POST).' - GET '.serialize($_GET);
Core_Files::setContent($_str,'./voice-'.date('Y-m-d').'.txt');
$_service=new Project_Ccs_Twilio_Billing();
$_service->setSettings( $_REQUEST )->voice();
?>