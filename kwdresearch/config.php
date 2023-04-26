<?php
switch( @$_SERVER['HTTP_HOST'] ) {
	case 'cnm.local': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'db_cnm_utf',
	); break;
	case 'cnm.dev': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'db_cnm_keywords',
	); break;
	case 'cnm.cnmbeta.info':
	case 'members.creativenichemanager.info':
	default: $_arrPrm=array(
		'host'=>'10.206.73.226',
		'username'=>'prod_kwdtool',
		'password'=>'VXh1Tw5Ak0',
		'dbname'=>'prod_kwdtool',
	); break;
}
$host=$_arrPrm['host'];
$database=$_arrPrm['dbname'];
$username=$_arrPrm['username'];
$password=$_arrPrm['password'];
?>