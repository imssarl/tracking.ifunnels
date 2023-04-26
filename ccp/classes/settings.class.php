<?php
class Settings
{
	function createConstants()
	{	
		global $ms_db;
		$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$cfg = $ms_db->getDataSingleRow($sql);
		
		
		define("PAGE_LINKS", $cfg["page_links"]);
		define("ROWS_PER_PAGE", $cfg["rows_per_page"]);
		define("RECORD_PER_PAGE", $cfg["rows_per_page"]);	// same varable for PSF	
	
	}
	function chwckLogin()
	{	
		
		global $ms_db;
		//$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where username = '".addslashes($_POST['username'])."' AND password = '".addslashes($_POST['password'])."'";
		$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$userinfo = $ms_db->getDataSingleRow($sql);
		return $userinfo;
	}
	function getSettings()
	{	
		global $ms_db;
		$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$userinfo = $ms_db->getDataSingleRow($sql);
		return $userinfo;
	}
	function update()
	{	
		global $ms_db;
		$sql = "update  ".TABLE_PREFIX."admin_settings_tb set username = '".$_POST['username']."', password = '".$_POST['password']."', email_address = '".$_POST['email_address']."' , snippet_part_1 = '".$_POST['snippet_part_1']."', snippet_part_2 = '".$_POST['snippet_part_2']."', snippet_part_3 = '".$_POST['snippet_part_3']."', 
		page_links = '".$_POST['page_links']."', 
		rows_per_page = '".$_POST['rows_per_page']."' where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$ms_db->modify($sql);
	}	
	function checkSession()
	{
		session_start();

		if (!(isset($_SESSION[MSESSION_PREFIX.'sessionusername']) && $_SESSION[MSESSION_PREFIX.'sessionusername'] !== ""))
		{
			header("location: http://members.creativenichemanager.info/login.php");
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

function update_psf_setting()
{
global $ms_db;
		$sql = "update ".TABLE_PREFIX."config_tb set username = '".$_POST["username"]."', password = '".$_POST["password"]."', ad_email = '".$_POST["email_address"]."'  ";
		$ms_db->modify($sql);


}

function update_cp_settings()
{
	global $ms_db;
	$sql = "update  ".TABLE_PREFIX."admin_settings_tb set username = '".$_POST["username"]."', password = '".$_POST["password"]."', email_address = '".$_POST["ad_email"]."' where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
	$ms_db->modify($sql);
}


function update_cp_setting_install()
{
	global $ms_db;
	$sql = "update  ".TABLE_PREFIX."admin_settings_tb set username = '".$_POST['admin_name']."', password = '".$_POST["password"]."', email_address = '".$_POST['email']."'  where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
	$ms_db->modify($sql);


}	

}
?>