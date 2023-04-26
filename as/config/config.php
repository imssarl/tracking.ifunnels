<?php
switch( $_SERVER['HTTP_HOST'] ) {
	case 'cnm.dev': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'db_cnm',
		'srv_path'=>'/',
		'srv_root'=>'D:\www\dev\cnm\site\as/',
	); break;
	case 'cnm.local': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'db_cnm_utf',
		'srv_path'=>'/',
		'srv_root'=>'E:\web\home\cnm.local\trunk/',
	); break;
	case 'members.cnmbeta.info': $_arrPrm=array(
		'host'=>'192.168.0.2',
		'username'=>'cnmbeta_develop',
		'password'=>'RwURyRpxd66A8EKD',
		'dbname'=>'cnmbeta_develop',
		'srv_path'=>'/',
		'srv_root'=>'/data/www/cnm.cnmbeta.info/html/as/',
	); break;
	case 'members.creativenichemanager.info':
	default: $_arrPrm=array(
		'host'=>'10.206.73.226',
		'username'=>'prod_cnm',
		'password'=>'h5obIs6M9F',
		'dbname'=>'prod_cnm',
		'srv_path'=>'/',
		'srv_root'=>'/data/www/members.creativenichemanager.info/html/as/',
	); break;
}

define("DB_SERVER_NAME",$_arrPrm['host']);
define("DB_USERNAME",$_arrPrm['username']);
define("DB_PASSWORD",$_arrPrm['password']);
define("DB_NAME",$_arrPrm['dbname']);
define("SERVER_PATH",$_arrPrm['srv_path']);
define("ROOT_PATH",$_arrPrm['srv_root']);
define("OUTER_CSS_PATH",$_arrPrm['srv_path']);

define("SITE_TITLE","Article Submission");
define("TABLE_PREFIX","hct_as_");
define("SESSION_PREFIX","ASM_SESS_");
define("MSESSION_PREFIX","CP_SESS_");
define("RECORD_PER_PAGE","10");
define("ROWS_PER_PAGE","10");
define("PAGE_LINKS","10");
$sign=" > ";
?>