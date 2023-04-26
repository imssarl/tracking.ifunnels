<?php

define("PATH", join(DIRECTORY_SEPARATOR, [__DIR__, '..', 'logs']));

if (!is_dir(PATH)) {
    mkdir(PATH);
} 

$time = date('Y-m-d H:i:s', time());
$date = date('Y-m-d', time());

file_put_contents(
    PATH . DIRECTORY_SEPARATOR . "registry-$date.log",
    sprintf("[%s]: api/registry %s\n", $time, json_encode($_POST)),
    FILE_APPEND
);
