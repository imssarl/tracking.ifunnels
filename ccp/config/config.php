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
		'dbname'=>'db_cnm_utf',
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

define("DB_SERVER_NAME",$_arrPrm['host']);
define("DB_USERNAME",$_arrPrm['username']);
define("DB_PASSWORD",$_arrPrm['password']);
define("DB_NAME",$_arrPrm['dbname']);
define("SERVER_PATH",$_arrPrm['srv_path']);
define("ROOT_PATH",$_arrPrm['srv_root']);
define("OUTER_CSS_PATH",$_arrPrm['srv_path']);



define("SITE_TITLE","Covert Conversion Pro");
define("TABLE_PREFIX","hct_ccp_");
define("SESSION_PREFIX","CAMPAIGN_SESS_");
define("MSESSION_PREFIX","CP_SESS_");
define("USE_FLASH","no");
define("RECORD_PER_PAGE","10");
define("MTABLE_PREFIX","hct_");
define("ROWS_PER_PAGE","10");
define("PSF_TOTAL_PAGES","10");
define("PAGE_LINKS","10");
define("DATE_FORMAT","d M Y");
define("DATE_TIME_FORMAT","d M Y h:i A");

$sign=" > ";

?>