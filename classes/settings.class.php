<?php
session_start();
//require_once("config/config.php");
class Settings

{

	function createConstants()
	{	

		global $ms_db;

		$sql = "select * from ".TABLE_PREFIX."admin_settings_tb";

		if($ms_db) 
		{
			$cfg = $ms_db->getDataSingleRow($sql);
	
			define("PAGE_LINKS", $cfg["page_links"]);
			define("ROWS_PER_PAGE", $cfg["rows_per_page"]);
			define("RECORD_PER_PAGE", $cfg["rows_per_page"]);	// same varable for PSF	
		}
	}

	function chwckLogin()
	{	

		$username = str_replace(" ","abc8211",$_POST['username']);
		$password = str_replace(" ","abc8211",$_POST['password']);

		global $ms_db;

		$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where email_address = '".$username."' AND password = '".$password."'";

		$userinfo = $ms_db->getDataSingleRow($sql);

		return $userinfo;

	}

	function getSettings()
	{	

		global $ms_db;
		$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
		
		$userinfo = $ms_db->getDataSingleRow($sql);
		return $userinfo;
	}

	function update()
	{	

		global $ms_db;
		
		$sql="SELECT id from ".TABLE_PREFIX."admin_settings_tb where user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];
		$userinfo = $ms_db->getDataSingleRow($sql);
		if($userinfo['id']!='' &&  $userinfo['id']>0)
		{
			// Removed from query on 26october08 for SDEI
			//  username = '".$_POST['username']."', password = '".$_POST['password']."', email_address = '".$_POST['email_address']."' ,
			 
			//$sql = "update  ".TABLE_PREFIX."admin_settings_tb set snippet_part_1 = '".$_POST['snippet_part_1']."', snippet_part_2 = '".$_POST['snippet_part_2']."', snippet_part_3 = '".$_POST['snippet_part_3']."',page_links = '".$_POST['page_links']."', rows_per_page = '".$_POST['rows_per_page']."' where id=".$userinfo['id'];
			
			$sql = "update  ".TABLE_PREFIX."admin_settings_tb set username='".$_SESSION[SESSION_PREFIX.'sessionusername']."', password='".$_SESSION[SESSION_PREFIX.'sessionuserpassword']."', snippet_part_1 = '".$_POST['snippet_part_1']."', snippet_part_2 = '".$_POST['snippet_part_2']."', snippet_part_3 = '".$_POST['snippet_part_3']."',page_links = '".$_POST['page_links']."', rows_per_page = '".$_POST['rows_per_page']."' where id=".$userinfo['id'];
			
		}
		else
		{
			$sql="INSERT INTO ".TABLE_PREFIX."admin_settings_tb (`username`,`password`,`email_address`,`snippet_part_1`,`snippet_part_2`,`snippet_part_3`,`page_links`,`rows_per_page`,`user_id`) VALUES('".$_SESSION[SESSION_PREFIX.'sessionusername']."','".$_SESSION[SESSION_PREFIX.'sessionuserpassword']."','".$_SESSION[SESSION_PREFIX.'sessionuseremail']."','".$_POST['snippet_part_1']."','".$_POST['snippet_part_2']."','".$_POST['snippet_part_3']."','".$_POST['page_links']."','".$_POST['rows_per_page']."','".$_SESSION[SESSION_PREFIX.'sessionuserid']."')";
		}
		$ms_db->modify($sql);

	}	

	function checkSession()
	{

		session_start();

		if (!(isset($_SESSION[SESSION_PREFIX.'sessionuserid']) && $_SESSION[SESSION_PREFIX.'sessionuserid'] !== ""))
		{
			header("location: login.php");
			//exit();
		}
	}
	function checkSessionForJP()
	{

		session_start();

		if ($_SESSION[SESSION_PREFIX.'sessionuseremail']!="ethiccash@gmail.com")
		{
			header("location: login.php");
			//exit();
		}
	}
	function checkForInstallationFiles()

	{

		$file_1 = "install.step1.php";
		$file_2 = "install.step2.php";
		$file_3 = "install.step3.php";

		if(file_exists($file_1) || file_exists($file_2) || file_exists($file_3))
		{

			echo "<h1>Files found !!!!!!!!!!!!!</h1><br><hr/>Before accessing the admin area, please confirm that you have deleted or renamed the

			<br>install.step1.php 
			<br>install.step2.php
			<br>install.step3.php
			<br>files in the root folder:<hr/>";
			die();
		}
	}	

/*	function checkForInstallationFiles()

	{

		$file_1 = "install.step1.php";

		$file_2 = "install.step2.php";

		$file_3 = "install.step3.php";

		$file_4 = "install.db.php";

		$file_5 = "install.db2.php";

		$file_6 = "install.db3.php";

		$file_7 = "install.step1.apca.php";

		$file_8 = "install.step2.apca.php";

		$file_9 = "install.step3.apca.php";
		
		$file_10 = "install.step1.dams.php";

					

		if(file_exists($file_1) || file_exists($file_2) || file_exists($file_3) || file_exists($file_4) || file_exists($file_5) || file_exists($file_6) || file_exists($file_7) || file_exists($file_8) || file_exists($file_9) || file_exists($file_10))

		{

			echo "<h1>Error</h1><br><hr/>Before accessing the admin area, please confirm that you have deleted or renamed the



			<br>install.db.php 

			<br>install.db2.php

			<br>install.db3.php

			<br>install.step1.php 

			<br>install.step2.php

			<br>install.step3.php

			<br>install.step1.apca.php

			<br>install.step2.apca.php

			<br>install.step3.apca.php

			<br>install.step1.dams.php

			<br>files in the root folder:<hr/>

			";

			die();

		}

	}	
*/
	



function update_psf_setting()
{
		global $ms_db;

		$sql = "update ".TABLE_PREFIX."config_tb set username = '".$_POST["username"]."', password = '".$_POST["password"]."', ad_email = '".$_POST["email_address"]."'  ";
		$ms_db->modify($sql);
}


function update_cp_settings()
{

	global $ms_db;

	$sql = "update  ".TABLE_PREFIX."admin_settings_tb set username = '".$_POST["username"]."', password = '".$_POST["password"]."', email_address = '".$_POST["ad_email"]."'  ";

	$ms_db->modify($sql);

}





function update_cp_setting_install()

{

	global $ms_db;

	$sql = "update  ".TABLE_PREFIX."admin_settings_tb set username = '".$_POST['admin_name']."', password = '".$_POST["password"]."', email_address = '".$_POST['email']."'  ";

	$ms_db->modify($sql);





}	



}

?>