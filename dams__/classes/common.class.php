<?php

class Common
{
	function getPostData()
	{
		$data = array();
		foreach($_POST as $key => $val)
		{
			$data[$key] = stripslashes($val);
		}
		$_SESSION["last_post_data"] = $data;
		return $data;
	}
	function checkSession()
	{
		session_start();
	
		if (!(isset($_SESSION[SESSION_PREFIX.'sessionuser']) && $_SESSION[SESSION_PREFIX.'sessionuser'] !== ""))
		{
		//	header("location: home.php");
		}
	}
// 	function getSettings()
// 	{	
// 		global $dams_db;
// 		$sql = "select * from ".TABLE_PREFIX."admin_settings";
// 		$userinfo = $dams_db->getDataSingleRow($sql);
// 		return $userinfo;
// 	}
// 	
// 	function installUpdate()
// 	{	
// 		global $dams_db;
// 		$sql = "update  ".TABLE_PREFIX."admin_settings set 
// 		username = '".$_POST['username']."', 
// 		password = '".$_POST['password']."', 
// 		email_address = '".$_POST['email_address']."'";
// 		
// 		$dams_db->modify($sql);
// 	}		
	
}
?>
