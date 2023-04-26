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

$input = json_decode(file_get_contents('php://input'));

if ($input === false) {
    http_response_code(400);
}

$instance = new Project_Pagebuilder_Quiz($input->uid);
$instance
    ->setEntered([
        'pb_site_id'        => $input->siteid,
        'pb_page_id'        => $input->pageid,
        'quiz_id'           => $input->quiz_id,
        'quiz_answer_index' => $input->index,
    ])
    ->set();

http_response_code(200);
