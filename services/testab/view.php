<?php

header('Access-Control-Allow-Origin: *');
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

chdir(dirname(__FILE__));
chdir('../../');
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

header('Content-Type: text/json');

$input = json_decode(file_get_contents('php://input'));
if ($input === false) {
    http_response_code(403);
}

$response = Project_TestAB_View::addStat($input->pageid, $input->current_option);
file_put_contents('php://output', json_encode(['status' => $response]));
http_response_code(200);