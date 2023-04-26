<?php
session_start();
//error_reporting(0);
//include("config.php");
chdir( '../' );
include("config/config.php");
chdir( dirname(__FILE__) );
require_once("../classes/database.class.php");
$database = new Database();
$database->openDB();
$mode=$_POST['mode'];
$id=$_POST['id'];

switch($mode){
	case "delete":
		$sql="DELETE from `".TABLE_PREFIX."kwd_savedkwds` where list_id='".$id."'";
		$res=$database->modify($sql);
		$sql="DELETE from `".TABLE_PREFIX."kwd_savedlist` where list_id='".$id."'";
		$res=$database->modify($sql);
		if($res){
			$content="The list has been removed successfully";
		}else{
			$content="The list could not be removed";
		}
	break;
}

echo $content;
?>