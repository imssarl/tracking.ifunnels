<?php
//chdir( dirname(__FILE__) );
//chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
require_once 'inc_config.php'; // set defined params - depercated!!!
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
Core_Errors::off();
// а тут запуск нужного класса - метода
error_reporting(E_ALL);
$view = new Project_Options_HtmlGenerator();
$_GET['type_view'] = 'snippetstrack';
$view->init($_GET);
/*
$url = $_SERVER['REQUEST_URI'];
$url = explode("?",$url);
if ($url[1]){
	header("Location: /cronjobs/getcontent.php?type_view=snippetstrack&{$url[1]}");
}

if (isset($_GET["id"]) && $_GET["id"]>0)

{

$para = explode("-",$_GET["id"]);

$pid = $para[0];

$redirect = $para[1];

require_once("config/config.php");

require_once("classes/snippets.functions.class.php");

require_once("classes/database.class.php");

require_once("classes/common.class.php");

require_once("classes/snippets.class.php");



$common = new Common();

$ms_db = new Database();

$ms_db->openDB();

$sf  = new SnippetFunctions();

$snippets = new Snippet();



$snippet = $sf->updatePartClicked($pid, $redirect);

$redirectto = $snippets->getTrackUrlToRedirect($redirect);



header("location: $redirectto");



}

else

{

echo "<div align='center'>Invalid Request</div>";

}
*/
?>