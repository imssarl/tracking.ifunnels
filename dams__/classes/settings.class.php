<?php
class Settings
{
	function checkNextRound()
	{
		global $ap_db;
		
		$sql = "select id from  ".TABLE_PREFIX."admin_settings 
		WHERE last_round_time < '".date("Y-m-d H:i:s",time()-ROUND_DIFF_TIME*60)."'  and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

		$is_round_ok = $ap_db->getDataSingleRecord($sql);	

		return $is_round_ok;
	}
	function checkNresetGetAdsProcess()
	{
		global $ap_db, $proxy;
		
		$sql = "select max(last_tracked) from  ".TABLE_PREFIX."keywords";

		$last_round = $ap_db->getDataSingleRecord($sql);	

		if (strtotime($last_round) < (time()-MAX_RESET_TIME*60))
		{
			$this->setGetAdsProcessStop();
		}
	}
	

	function isGetAdsProcessRunning()
	{
		global $ap_db;
		$sql = "select prc_status from ".TABLE_PREFIX."admin_settings where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$status = $ap_db->getDataSingleRecord($sql);
		
		if ($status=="R")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function setGetAdsProcessStart()
	{
		global $ap_db;
		$sql = "UPDATE ".TABLE_PREFIX."admin_settings SET 
		prc_status = 'R' where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$ap_db->modify($sql);	
	}
	
	function setGetAdsProcessStop()
	{
		global $ap_db;

		$sql = "UPDATE ".TABLE_PREFIX."admin_settings SET 
		prc_status = 'S' , 
		last_round_time = '".date("Y-m-d H:i:s")."' where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$ap_db->modify($sql);
		return true;
	}
		
	
	
	function resetRound()
	{
		global $ap_db;
		$sql = "select count(id) from `".TABLE_PREFIX."keywords` where status = 'A' AND round < ".ROUND;
		$ex = $ap_db->getDataSingleRecord($sql);
		if (!$ex)
		{
			$sql = "UPDATE ".TABLE_PREFIX."admin_settings SET 
			round = round+1, 
			last_round_time = '".date("Y-m-d H-i-s")."' where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
			$ap_db->modify($sql);
		}
	}
	function createConstants()
	{	
		global $ap_db;
		$sql = "select * from ".TABLE_PREFIX."admin_settings where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$cfg = $ap_db->getDataSingleRow($sql);
		
		
		define("PAGE_LINKS", $cfg["page_links"]);
		define("ROWS_PER_PAGE", $cfg["rows_per_page"]);
		define("MAX_PROXY_USED",$cfg["max_proxy_used"]);
		define("PROXY_TIMEOUT_SEC",$cfg["proxy_timeout"]);
		define("PAUSE_BW_QUERIES",$cfg["pause_bw_queries"]);
		define("SCHEDULING",$cfg["scheduling"]);
		define("ROUND",$cfg["round"]);		
		define("USE_PROXY_PER_ROUND",$cfg["use_proxy_per_round"]);
		define("PROXY_PAUSE_TIME",$cfg["proxy_pause_time"]);	
		define("ROUND_DIFF_TIME",$cfg["round_diff_time"]);
		define("MAX_DEPTH",$cfg["max_depth"]);					
			
	}

	function gethelp()
	{
		$name=$_SERVER['PHP_SELF'];
		$pos=strrpos($name,"/");
		$rev=strrev($name);
		$last=strrpos($rev,".");
		$name=substr($name,$pos+1,-$last);
		return $name;
	}

	function chwckLogin()
	{	
		
		global $ap_db;
		$sql = "select * from ".TABLE_PREFIX."admin_settings where username = '".addslashes($_POST['username'])."' AND password = '".addslashes($_POST['password'])."'";
		$userinfo = $ap_db->getDataSingleRow($sql);
		return $userinfo;
	}
	function getSettings()
	{	
		global $ap_db;
		$sql = "select * from ".TABLE_PREFIX."admin_settings where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$userinfo = $ap_db->getDataSingleRow($sql);
		return $userinfo;
	}
	
	function installUpdate()
	{	
		global $ap_db;
		$sql = "update  ".TABLE_PREFIX."admin_settings set 
		username = '".$_POST['username']."', 
		password = '".$_POST['password']."', 
		email_address = '".$_POST['email_address']."', 
		page_links = '".$_POST['page_links']."', 
		rows_per_page = '".$_POST['rows_per_page']."', 
		use_proxy = '".$_POST['use_proxy']."' where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		
		$ap_db->modify($sql);
	}		
	
	function update()
	{	
		global $ap_db;
		$sql = "update  ".TABLE_PREFIX."admin_settings set 
		username = '".$_POST['username']."', 
		password = '".$_POST['password']."', 
		email_address = '".$_POST['email_address']."', 
		
		page_links = '".$_POST['page_links']."', 
		rows_per_page = '".$_POST['rows_per_page']."', 
		max_proxy_used = '".$_POST['max_proxy_used']."', 
		proxy_timeout = '".$_POST['proxy_timeout']."', 
		pause_bw_queries = '".$_POST['pause_bw_queries']."', 

		scheduling = '".$_POST['scheduling']."', 
		use_proxy_per_round = '".$_POST['use_proxy_per_round']."', 
		proxy_pause_time = '".$_POST['proxy_pause_time']."', 
		round_diff_time = '".$_POST['round_diff_time']."', 		
		max_depth = '".$_POST['max_depth']."', 
										
		use_proxy = '".$_POST['use_proxy']."' where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		
		
		
		
		$ap_db->modify($sql);
	}	
	function checkSession()
	{
	session_start();

	if (!(isset($_SESSION[MSESSION_PREFIX.'sessionuserid']) && $_SESSION[MSESSION_PREFIX.'sessionuserid'] !== ""))
	{
		header("location: login.php");
	}
	}
	function checkForInstallationFiles()
	{
		$file_1 = "install.step1.php";
		$file_2 = "install.step2.php";
		$file_3 = "install.step3.php";				
		if(file_exists($file_1) || file_exists($file_2) || file_exists($file_3))
		{
			echo "<h1>Error</h1><br><hr/>Before accessing the admin area, please confirm that you have deleted or renamed the
			<br>install.step1.php 
			<br>install.step2.php
			<br>install.step3.php
			<br>files in the root folder:<hr/>
			";
			die();
		}
	}	
	

}
?>