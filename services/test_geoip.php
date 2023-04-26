<?php
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Access-Control-Allow-Origin: *');
chdir( '../' );
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();



$_ip='194.158.200.242';

var_dump('SELECT country_id FROM getip_countries2ip WHERE ip_start <= ' . sprintf("%u\n", ip2long($_ip)) . ' AND ' . sprintf("%u\n", ip2long($_ip) ) . ' <= ip_end');
var_dump( Core_Sql::getAssoc('SELECT country_id FROM getip_countries2ip WHERE ip_start <= ' . sprintf("%u\n", ip2long($_ip)) . ' AND ' . sprintf("%u\n", ip2long($_ip)) . ' <= ip_end') );