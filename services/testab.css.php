<?php

header('Access-Control-Allow-Origin: *');
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

chdir(dirname(__FILE__));
chdir('../');
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

header('Content-Type: text/css');

$option = '#';

if (!empty($_REQUEST['testab'])) {
    $option = $_REQUEST['testab'];
}

echo str_replace('%s', $option, '[data-variant-current="#"] [data-vhide-default],[data-variant-name]:not([data-variant-name="%s"]):not([data-vshow~="%s"]){display: none;}');

http_response_code(200);
