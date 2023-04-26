<?php
chdir( dirname(__FILE__) );
chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
error_reporting(E_ALL);
if( !empty( $_POST ) || !empty( $_GET ) ){
	if(is_file('./ukunsub-'.date('Y-m-d').'.txt')){
		Core_Files::getContent($_str,'./ukunsub-'.date('Y-m-d').'.txt');
	}
	$_str.="\n".date('d.m.Y H:i:s').' - POST '.serialize($_POST).' - GET '.serialize($_GET);
	Core_Files::setContent($_str,'./ukunsub-'.date('Y-m-d').'.txt');
	$_billings=new Project_Billing();
	$_billings->withPhone( $_REQUEST['phone'] )->getList( $arrUserBillings );
	if( count( $arrUserBillings )==0 ){
		echo "Mobile number registered as this one does not exist.";
		exit;
	}
	echo Project_Ccs_Twilio_Billing::unsubscribe( $arrUserBillings );
}else{
?>
<form action="http://qjmpz.com/services/ukunsub.php" method="post" >
	<label>Subscriber phone</label>
	<input type="phone" value="" name="phone" required>
    <div class="clear"><input type="submit" value="Unsubscribe" name="unsubscribe" ></div>
</form>
<?php } ?>