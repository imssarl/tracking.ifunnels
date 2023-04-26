<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
error_reporting(E_ALL);

if(is_file('./sms-'.date('Y-m-d').'.txt')){
	Core_Files::getContent($_str,'./sms-'.date('Y-m-d').'.txt');
}
$_str.="\n".date('d.m.Y H:i:s').' - POST '.serialize($_POST).' - GET '.serialize($_GET);
Core_Files::setContent($_str,'./sms-'.date('Y-m-d').'.txt');
$_sms=new Project_Ccs_Twilio_Billing();
$_sms->setSettings( $_REQUEST )->sms();
?>