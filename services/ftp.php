<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');
error_reporting(E_ALL);
require_once 'inc_config.php'; // set defined params - depercated!!!
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

Project_Users_Fake::zero();
$_strDir='services@ftp';
if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $_strDir ) ) {
	return false;
}
$_strContent = 'TEST';
Core_Files::setContent($_strContent,$_strDir.'test.php');



$ftp = ftp_connect('qjmp.com','21');

if (!$ftp){
	p('error ftp_connect()');
}

if ( !ftp_login( $ftp, "qjmp", "T&^*Hsjxj" ) ){
	p('error ftp_login()');
}

if ( !ftp_chdir( $ftp, '/public_html/') ){
	p("error can't change dir ftp_chdir()");
}

if ( !ftp_put( $ftp, 'test.php', $_strDir.'test.php', FTP_BINARY ) ){
	p("error. can't upload file  {$_strDir}test.php ");
}

p('file has been uploaded');

?>