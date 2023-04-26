<?php
session_start();
chdir( '../' );
include("config/config.php");
chdir( dirname(__FILE__) );
require_once("../classes/database.class.php");

$database = new Database();
$database->openDB();

$list=$_POST['txtListTitle'];
$keywords=$_POST['keywords'];

$sql="SELECT list_id from `".TABLE_PREFIX."kwd_savedlist` where list_title='".$list."' AND user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
$res=$database->getDataSingleRecord($sql);

if($res>0){
	header("Location:index.php?resp=A list with similar name already exists.");
}else{
	$sql="INSERT INTO `".TABLE_PREFIX."kwd_savedlist` (`list_title`,`user_id`) VALUES ('".$list."','".$_SESSION[SESSION_PREFIX.'sessionuserid']."')";
	$resid=$database->insert($sql);
	
	for($i=0;$i<count($keywords);$i++){
		$sqlKwd="INSERT INTO `".TABLE_PREFIX."kwd_savedkwds` (`keyword`,`list_id`) VALUES ('".$keywords[$i]."','".$resid."')";
		$result=$database->insert($sqlKwd);
	}
	
	header("Location:index.php?resp=Selected keywords have been saved successfully");
}

?>