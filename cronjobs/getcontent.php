<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
require_once 'inc_config.php'; // set defined params - depercated!!!
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
// а тут запуск нужного класса - метода
error_reporting(E_ALL);
header( "HTTP/1.1 200 OK" );
$view = new Project_Options_HtmlGenerator();
$view->init($_GET);

?>