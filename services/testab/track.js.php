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

header('Content-Type: text/js');
define(JS_BUNDLE_PATH, join(DIRECTORY_SEPARATOR, [Zend_Registry::get('config')->path->absolute->root . 'skin', 'test', 'dist', 'js', 'testab.bundle.js']));

$params = array_merge(array_keys($_REQUEST), ['url']);
$params = array_map(function ($key) {return "[%" . strtoupper($key) . "%]";}, $params);

$values = array_merge(array_values($_REQUEST), ["https://fasttrk.net//services/testab/goal.php"]);

if (file_exists(JS_BUNDLE_PATH)) {
    $js = file_get_contents(JS_BUNDLE_PATH);
    $js = str_replace($params, $values, $js);
    echo $js;
}

http_response_code(200);
