<?php
switch( $_SERVER['HTTP_HOST'] ) {
	case 'cnm.local': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'db_cnm_utf',
		'srv_path'=>'/',
		'srv_root'=>'E:\web\home\cnm.local\trunk/',
	); break;

	case 'cnm.dev': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'db_cnm',
		'srv_path'=>'/',
		'srv_root'=>'D:\www\dev\cnm\site/',
	); break;
	case 'cnm.cnmbeta.info': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'dev_cnm',
		'password'=>'BMtfFYGj1O',
		'dbname'=>'dev_cnm',
		'srv_path'=>'/',
		'srv_root'=>'/data/www/cnm.cnmbeta.info/html/',
	); break;
	case 'members.creativenichemanager.info':
	default: $_arrPrm=array(
		'host'=>'10.206.73.226',
		'username'=>'prod_cnm',
		'password'=>'h5obIs6M9F',
		'dbname'=>'prod_cnm',
		'srv_path'=>'/',
		'srv_root'=>'/data/www/members.creativenichemanager.info/html/',
	); break;
}

if ( empty( $GLOBALS['test'] ) ) {
	$GLOBALS['test']=1;
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	chdir( dirname(__FILE__) );
	chdir( '../' );
	require_once 'inc_config.php'; // set defined params - depercated!!!
	require_once './library/WorkHorse.php'; // starter
	WorkHorse::shell();
	Project_Users_Fake::byUserId( $_SESSION['USER']['parent_id']); // пользователи и права
	Core_Errors::off();
	ob_start();
}

define("DB_SERVER_NAME",$_arrPrm['host']);
define("DB_USERNAME",$_arrPrm['username']);
define("DB_PASSWORD",$_arrPrm['password']);
define("DB_NAME",$_arrPrm['dbname']);
define("SERVER_PATH", 'http://'.$_SERVER['HTTP_HOST'].'/');
define("ROOT_PATH",$_arrPrm['srv_root']);
define("OUTER_CSS_PATH",$_arrPrm['srv_path']);

define("FREE_SALEAPI","http://sales.ethiccash.com/2/action/Jin/APIUser/authorize.txt?gate_id=2206&secret=giveitaway");
define("PAID_SALEAPI","http://sales.ethiccash.com/2/action/Jin/APIUser/authorize.txt?gate_id=44&secret=letsbesmart");
define("TABLE_PREFIX","hct_");					
define("SESSION_PREFIX","CP_SESS_");							

define("SITE_TITLE","Control Tower");
define("ROWS_PER_PAGE", 50);
define("ARTICLE_SEPARATOR","###NEW###");	
define("MAX_FEED_ITEMS", 40);
define("MAX_LENGTH_FEED_DESC", 400);	
define("MAX_BLOG_MANAGE","5");
define("MAX_THEME_MANAGE","3");

$KEYWORD_MAIN_TAG["GOOGLE"] = array("<div class=g>","</div>");
$KEYWORD_TITLE_TAG["GOOGLE"] = array("<h2 class=r>","</h2>");
$KEYWORD_SUMMARY_TAG["GOOGLE"] = array("<table border=0 cellpadding=0 cellspacing=0><tr><td class=j>","</td></tr></table>");	
$KEYWORD_SUMMARY_SEPARATOR["GOOGLE"] = array("<nobr>");
$KEYWORD_SOURCE_SITES["GOOGLE"] = array("GOOGLE","http://www.google.com/search?q=");
$KEYWORD_START_VARS["GOOGLE"] = array("start",0,10);
$KEYWORD_DATAS = 20;	
$KEYWORD_SEARCH_BY = "GOOGLE";

//require_once(ROOT_PATH."configvars.php");	
//error_reporting(~E_NOTICE & ~E_STRICT);
?>