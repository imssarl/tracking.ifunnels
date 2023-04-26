<meta content="text/html; charset=utf-8" http-equiv="content-type">
<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();

$_txtnation=new Project_Billing_Txtnation();
$_txtnation->getList( $_getData );
echo "Last sended message!<br/><code>".$_getData[0]['send'];
$_mix=unserialize( str_replace( 'в‚¤', '£ ', $_getData[2]['send'] ) );
$_post='';
foreach ($_mix as $_k=>$_v){
	if ( is_array($_v)||is_object($_v) ){
		$_post .= ( (empty($_post) ) ? '' : '&' ).$_k.'='.urlencode(serialize($_v));
	} else {
		$_post .= ( (empty($_post) ) ? '' : '&' ).$_k.'='.urlencode($_v);
	}
}
	var_dump( str_replace( '%E2%82%A4', '%C2%A3', http_build_query( $_mix ) ) );
echo "</code><br/>";
	var_dump( str_replace( '%E2%82%A4', '%C2%A3', $_post ) );
echo "<br/>".urlencode( 'IN text £1000' );



?>