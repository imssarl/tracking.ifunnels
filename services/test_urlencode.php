<?php
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

chdir( dirname(__FILE__) );
chdir( '../' );
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

Core_Sql::setExec( "UPDATE `lpb_utm` SET `view`=1 WHERE `view`=0 AND `click`=0;");
?>