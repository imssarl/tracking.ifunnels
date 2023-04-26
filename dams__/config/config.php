<?php
switch( $_SERVER['HTTP_HOST'] ) {
	case 'cnm.local': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'db_cnm_utf',
		'srv_path_base'=> "http://{$_SERVER['HTTP_HOST']}/dams/",		
		'srv_path'=>'/',
		'srv_root'=>'E:\web\home\cnm.local\trunk\dams/',
	); break;
	case 'cnm.dev': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'db_cnm',
		'srv_path_base'=> "http://{$_SERVER['HTTP_HOST']}/dams/",		
		'srv_path'=>'/',
		'srv_root'=>'D:\www\dev\cnm\site\dams/',
	); break;
	case 'cnm.loc': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'db_cnm_utf',
		'srv_path_base'=> "http://{$_SERVER['HTTP_HOST']}/dams/",
		'srv_path'=> "/",
		'srv_root'=>'J:\web\home\cnm\trunk\dams/',
	); break;	
	case 'cnm.cnmbeta.info': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'dev_cnm',
		'password'=>'BMtfFYGj1O',
		'dbname'=>'dev_cnm',
		'srv_path_base'=> "http://{$_SERVER['HTTP_HOST']}/dams/",		
		'srv_path'=>'/',
		'srv_root'=>'/data/www/cnm.cnmbeta.info/html/',
	); break;
	case 'members.creativenichemanager.info':
	default: $_arrPrm=array(
		'host'=>'10.206.73.226',
		'username'=>'prod_cnm',
		'password'=>'h5obIs6M9F',
		'dbname'=>'prod_cnm',
		'srv_path_base'=> "http://{$_SERVER['HTTP_HOST']}/dams/",		
		'srv_path'=>'/',
		'srv_root'=>'/data/www/members.creativenichemanager.info/html/dams/',
	); break;
}


define("DB_SERVER_NAME",$_arrPrm['host']);
define("DB_USERNAME",$_arrPrm['username']);
define("DB_PASSWORD",$_arrPrm['password']);
define("DB_NAME",$_arrPrm['dbname']);
define("BASEPATH",$_arrPrm['srv_path_base']);
define("SERVER_PATH",$_arrPrm['srv_path']);
define("ROOTPATH",$_arrPrm['srv_root']);
define("OUTER_CSS_PATH",$_arrPrm['srv_path']);

define("SITE_TITLE","Dynamic Ads Management System");
define("TABLE_PREFIX","hct_dams_");

define("SESSION_PREFIX","DAMS_SESS_");
define("MSESSION_PREFIX","CP_SESS_");
define("USE_FLASH","no");
define("RECORD_PER_PAGE","10");
define("ROWS_PER_PAGE","10");
define("PAGE_LINKS","10");
define("DATE_FORMAT","d M Y");
define("DATE_TIME_FORMAT","d M Y h:i A");

define("TOPLEFTBIGSWF","topleft.swf");
define("TOPRIGHTBIGSWF","topright.swf");
define("BOTTOMLEFTBIGSWF","bottomleft.swf");
define("BOTTOMRIGHTBIGSWF","bottomright.swf");
define("TOPLEFTSMALLSWF","small_topleft.swf");
define("TOPRIGHTSMALLSWF","small_topright.swf");
define("BOTTOMLEFTSMALLSWF","small_bottomleft.swf");
define("BOTTOMRIGHTSMALLSWF","small_bottomright.swf");
define("MAXFILEUPLOAD",100);

$UPLOAD_LIMIT	= MAXFILEUPLOAD;
$POST_MAX_LIMIT	= MAXFILEUPLOAD;

$sign=" > ";

?>