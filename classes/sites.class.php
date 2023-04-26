<?php
session_start();
//require_once("config/config.php");

require_once('advancedOptionsClass.php');
require_once("database.class.php");
require_once("en_decode.class.php");

class Sites
{

	function insertSite()
	{
		global $ms_db;

		$aDataArray = $_SESSION['cnb_site_from_post'];
		
	   	$dams_type= $aDataArray["headlines_spot1"];
	   	$dams_ids = $aDataArray["damsids"];
	   	
		if($aDataArray["headlines_spot1"]=="manage"){
			$dams_type="single";
		}
		
		if (!$aDataArray['damscode_spot1']){
			$dams_type = '';
			$dams_ids = '';
		}
		
		$sql = "INSERT INTO `".TABLE_PREFIX."portals_sites_tb` (  `type` ,`source_type`,`damas_type`,`damas_ids`, `prim_keyword`, `created_date` ,`updatedate`, `title` , `description` , `url` , `ftp_address` , `ftp_username` , `ftp_password` , `ftp_homepage` , `is_under_portal` , `portal_id`, `feed_writable`, `cache_writable`, `images_data_writable`, `temp_article_writable`,`profile_id`,`user_id` )
		VALUES (
		'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."type"],"text") . "',"
		."'".$aDataArray["source_type"]."',"
		."'".$dams_type."',"
		."'".$dams_ids
		."','".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."prim_keyword"],"text")
		."','".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."created_date"],"date")."',NOW(),"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."title"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."description"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."url"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_address"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_username"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_password"],"text")."',"		
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_homepage"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."is_under_portal"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."portal_id"],"text")."'," 
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."feed_writable"],"text")."'," 
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."cache_writable"],"text")."'," 
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."image_data_writable"],"text")."'," 
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."temp_article_writable"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX.'profile_id'],"int")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."sessionuserid"],"int").
		"')" ;
		
		$id = $ms_db->insert($sql);

		//insert spots after site creation
		
		$oAdvancedOptions = new advancedOptionsClass();
		
		$oAdvancedOptions->setSiteId($id);
		$oAdvancedOptions->setSiteType('cnb');
		$oAdvancedOptions->setUserId($_SESSION[SESSION_PREFIX.'sessionuserid']);
		$oAdvancedOptions->insertSpotsIntoDb($aDataArray);
		
		$_SESSION['cnb_site_from_post'] = array();
		
		return $id;
	}
	
	

	function insertPortal()
	{
		global $ms_db;

		
		$aDataArray = $_SESSION['cnb_site_from_post'];
		
	   	$dams_type= $aDataArray["headlines_spot1"];
	   	$dams_ids = $aDataArray["damsids"];
	   	
		if($aDataArray["headlines_spot1"]=="manage"){
			$dams_type="single";
		}
		
		if (!$aDataArray['damscode_spot1']){
			$dams_type = '';
			$dams_ids = '';
		}

		
		$sql = "INSERT INTO `".TABLE_PREFIX."portals_sites_tb` (  `type` , `source_type`,`damas_type`,`damas_ids`, `created_date` , `updatedate`,`title` , `description` , `url` , `ftp_address` , `ftp_username` , `ftp_password` , `ftp_homepage`,`user_id` )
		VALUES (
		'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."type"],"text") . "',"
		."'".$aDataArray["source_type"]."',"
		."'".$dams_type."',"
		."'".$dams_ids."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."created_date"],"date")."',NOW(),"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."title"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."description"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."url"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_address"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_username"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_password"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_homepage"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."sessionuserid"],"int").
		"')";

		$id = $ms_db->insert($sql);
	
		
		//insert spots after site creation
		$oAdvancedOptions = new advancedOptionsClass();
		
		$oAdvancedOptions->setSiteId($id);
		$oAdvancedOptions->setSiteType('cnb');
		$oAdvancedOptions->setUserId($_SESSION[SESSION_PREFIX.'sessionuserid']);
		$oAdvancedOptions->insertSpotsIntoDb($aDataArray);

		$_SESSION['cnb_site_from_post'] = array();
		
		return $id;
	}



	function updatePortal($id)
	{	
		global $ms_db;

	   	$dams_type=$_POST["headlines_spot1"];
	   	$dams_ids = $_POST["damsids"];
	   	
		if($_POST["headlines_spot1"]=="manage"){
			$dams_type="single";
		}
		
		if (!$_POST['damscode_spot1']){
			$dams_type = '';
			$dams_ids = '';
		}
		
		$oAdvancedOptions = new advancedOptionsClass();
		
		$oAdvancedOptions->setSiteId($id);
		$oAdvancedOptions->setSiteType('cnb');
		$oAdvancedOptions->setUserId($_SESSION[SESSION_PREFIX.'sessionuserid']);
		$oAdvancedOptions->insertSpotsIntoDb($_POST);
		
		$sql = "update `".TABLE_PREFIX."portals_sites_tb`  set `updatedate`=NOW(),
	   `source_type`='".$_POST["source_type"]."',
	   `damas_type`='".$dams_type."',
	   `damas_ids`='".$dams_ids."',
		title ='".$ms_db->GetSQLValueString($_POST["title"],"text")."',
		profile_id='".$ms_db->GetSQLValueString($_POST["profile_id"],"text")."',
		prim_keyword='".$ms_db->GetSQLValueString($_POST["prim_keyword"],"text")."',
		description='".$ms_db->GetSQLValueString($_POST["description"],"text")."',
		url='".$ms_db->GetSQLValueString($_POST["url"],"text")."',
		ftp_address='".$ms_db->GetSQLValueString($_POST["ftp_address"],"text")."',
		ftp_username='".$ms_db->GetSQLValueString($_POST["ftp_username"],"text")."',
		ftp_password='".$ms_db->GetSQLValueString($_POST["ftp_password"],"text")."',
		ftp_homepage='".$ms_db->GetSQLValueString($_POST["ftp_homepage"],"text")."' 
		where id = '".$id."';";

		$id = $ms_db->modify($sql);

		return $id;
	}



	function simpleUpdateForSite($id)	{
		global $ms_db;	
		global $conn; // Conn added 280209 Raj for SDEI	
		
		/*
		if ('91.149.132.93' == $_SERVER['REMOTE_ADDR'])
		{
			var_dump($_POST);
			die();
		}
		*/
		
		if(!$conn)
		{
			$conn=ftp_connect($_POST["ftp_address"]);
			$login=ftp_login($conn,$_POST["ftp_username"],$_POST["ftp_password"]);
			@ftp_pasv( $conn, true );
		}
		
		$profile=new CNBProfile(); //profile added 270209 Raj for SDEI		
		
		if ( $_SESSION[SESSION_PREFIX.'process'] == "edit")	
		{			
			$headers  = " title ='".$ms_db->GetSQLValueString($_POST["title"],"text")."',profile_id ='".$ms_db->GetSQLValueString($_POST["profile_id"],"int")."',prim_keyword ='".$ms_db->GetSQLValueString($_POST["prim_keyword"],"text")."',description='".$ms_db->GetSQLValueString($_POST["description"],"text")."', ";
		}
		else if ($_SESSION[SESSION_PREFIX.'process'] == "editpsite")
		{			
			$headers  = "";		
		}
		
	   	$dams_type=$_POST["headlines_spot1"];
	   	$dams_ids = $_POST["damsids"];
		
		if($_POST["headlines_spot1"]=="manage"){
			$dams_type="single";
		}
		
		if (!$_POST['damscode_spot1']){
			$dams_type = '';
			$dams_ids = '';
		}
		
		$oAdvancedOptions = new advancedOptionsClass();
		
		$oAdvancedOptions->setSiteId($id);
		$oAdvancedOptions->setSiteType('cnb');
		$oAdvancedOptions->setUserId($_SESSION[SESSION_PREFIX.'sessionuserid']);
		$oAdvancedOptions->insertSpotsIntoDb($_POST);
		
		$sql = "update `".TABLE_PREFIX."portals_sites_tb`  set 
		`updatedate`= NOW(),
		$headers 
		url='".$ms_db->GetSQLValueString($_POST["url"],"text")."',
	   `source_type`='".$_POST["source_type"]."',
	   `damas_type`='".$dams_type."',
	   `damas_ids`='".$dams_ids."',
		prim_keyword ='".$ms_db->GetSQLValueString($_POST["prim_keyword"],"text")."',
		profile_id ='".$ms_db->GetSQLValueString($_POST["profile_id"],"int")."',
		ftp_address='".$ms_db->GetSQLValueString($_POST["ftp_address"],"text")."',
		ftp_username='".$ms_db->GetSQLValueString($_POST["ftp_username"],"text")."',
		ftp_password='".$ms_db->GetSQLValueString($_POST["ftp_password"],"text")."',
		ftp_homepage='".$ms_db->GetSQLValueString($_POST["ftp_homepage"],"text")."' 
		where id = '".$id."'";
		
		$idx = $ms_db->modify($sql);
		$selTemp="SELECT temp_name from `".TABLE_PREFIX."templates` where id=(SELECT template_id from `".TABLE_PREFIX."portals_sites_tb` where id='".$id."')";
		
		$res = $ms_db->getDataSingleRow($selTemp);	
		$profile->uploadProfile($_POST["profile_id"], $_POST["ftp_homepage"],$res["temp_name"],$_SESSION[SESSION_PREFIX.'process']);
		
		return $idx;	
	}



	function updateSite($id)
	{	
		global $ms_db;

		$aDataArray = $_SESSION['cnb_site_from_post'];
		
	   	$dams_type= $aDataArray["headlines_spot1"];
	   	$dams_ids = $aDataArray["damsids"];
	   	
		if($aDataArray["headlines_spot1"]=="manage"){
			$dams_type="single";
		}
		
		if (!$aDataArray['damscode_spot1']){
			$dams_type = '';
			$dams_ids = '';
		}
		
		$oAdvancedOptions = new advancedOptionsClass();
		
		$oAdvancedOptions->setSiteId($id);
		$oAdvancedOptions->setSiteType('cnb');
		$oAdvancedOptions->setUserId($_SESSION[SESSION_PREFIX.'sessionuserid']);
		$oAdvancedOptions->insertSpotsIntoDb($aDataArray);
		
		$sql = "update `".TABLE_PREFIX."portals_sites_tb`  set `updatedate`= NOW(),
		title ='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."title"],"text")."',
	   `source_type`='".$aDataArray["source_type"]."',
	   `damas_type`='".$dams_type."',
	   `damas_ids`='".$dams_ids."',
		prim_keyword ='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."prim_keyword"],"text")."',
		profile_id ='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."profile_id"],"int")."',
		description='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."description"],"text")."',
		url='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."url"],"text")."',
		ftp_address='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_address"],"text")."',
		ftp_username='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_username"],"text")."',
		ftp_password='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_password"],"text")."',
		ftp_homepage='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_homepage"],"text")."',
		feed_writable='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."feed_writable"],"text")."',
		cache_writable='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."cache_writable"],"text")."',
		images_data_writable='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."image_data_writable"],"text")."',
		temp_article_writable='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."temp_article_writable"],"text")."'
		where id = '".$id."'";

		$id = $ms_db->modify($sql);

		$_SESSION['cnb_site_from_post'] = array();
		
		return $id;
	}



	function updatePortalSite($id)
	{
		global $ms_db;
		

		$aDataArray = $_SESSION['cnb_site_from_post'];
		
	   	$dams_type= $aDataArray["headlines_spot1"];
	   	$dams_ids = $aDataArray["damsids"];
	   	
		if($aDataArray["headlines_spot1"]=="manage"){
			$dams_type="single";
		}
		
		if (!$aDataArray['damscode_spot1']){
			$dams_type = '';
			$dams_ids = '';
		}
		
		$oAdvancedOptions = new advancedOptionsClass();
		
		$oAdvancedOptions->setSiteId($id);
		$oAdvancedOptions->setSiteType('cnb');
		$oAdvancedOptions->setUserId($_SESSION[SESSION_PREFIX.'sessionuserid']);
		$oAdvancedOptions->insertSpotsIntoDb($aDataArray);
		
		$sql = "update `".TABLE_PREFIX."portals_sites_tb`  set `updatedate`= NOW(),
	   `source_type`='".$aDataArray["source_type"]."',
	   `damas_type`='".$dams_type."',
	   `damas_ids`='".$dams_ids."',
		url='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."url"],"text")."',
		prim_keyword ='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."prim_keyword"],"text")."',		
		profile_id ='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."profile_id"],"int")."',
		ftp_address='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_address"],"text")."',		
		ftp_username='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_username"],"text")."',		
		ftp_password='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_password"],"text")."',		
		ftp_homepage='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."ftp_homepage"],"text")."',		
		feed_writable='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."feed_writable"],"text")."',
		cache_writable='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."cache_writable"],"text")."',
		images_data_writable='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."image_data_writable"],"text")."',
		temp_article_writable='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."temp_article_writable"],"text")."'
		where id = '".$id."'";

		$id = $ms_db->modify($sql);

		$_SESSION['cnb_site_from_post'] = array();
		
		return $id;
	}


	function getAllSites()
	{
		global $ms_db;
		
		$sql = "select * from `".TABLE_PREFIX."portals_sites_tb` where is_under_portal != 'Y' and user_id='".$_SESSION[SESSION_PREFIX."sessionuserid"]."' order by updatedate DESC";

		$rs = $ms_db->getRS($sql);

		return $rs;
	}


	function getSites($id)
	{
		global $ms_db;
		
		$sql = "select * from `".TABLE_PREFIX."portals_sites_tb` 
		where type = 'S' AND (id ='".$id."' or portal_id ='".$id."') AND user_id='".$_SESSION[SESSION_PREFIX."sessionuserid"]."' order by updatedate DESC";

		$rs = $ms_db->getRS($sql);

		return $rs;
	}



	function getPortalSites($pid, $orderby)
	{
		global $ms_db;
//		$sql = "select * from `".TABLE_PREFIX."portals_sites_tb` where is_under_portal = 'Y' AND portal_id = ".$pid." ".$orderby;
		 $sql = "SELECT s.*, count(p.id) AS 'proj'
		  FROM ".TABLE_PREFIX."portals_sites_tb s left JOIN ".TABLE_PREFIX."projects_tb p ON s.id = p.site_id 
		  where is_under_portal = 'Y' AND portal_id = ".$pid." AND s.user_id=".$_SESSION[SESSION_PREFIX."sessionuserid"]."
		  group by s.id ".$orderby;

		$rs = $ms_db->getRS($sql);
		
		return $rs;
	}


	function getSiteByID($id)
	{
		global $ms_db;

		$sql = "select * from `".TABLE_PREFIX."portals_sites_tb` where id ='".$ms_db->GetSQLValueString($id,"int")."'";

		$rs = $ms_db->getDataSingleRow($sql);
		
		return $rs;
	}



	function delete($id)
	{

		global $ms_db, $project;

		$sql = "select id from `".TABLE_PREFIX."portals_sites_tb` where id =".$id." or portal_id = ".$id ;

		$srs = $ms_db->getRS($sql);

		if ($srs)
		{
			while($siteid = $ms_db->getNextRow($srs))
			{
				$sql = "select id  from `".TABLE_PREFIX."projects_tb` where site_id = ".$siteid["id"];
				$prs = $ms_db->getRS($sql);

				if ($prs)
				{
					while($prj = $ms_db->getNextRow($prs))
					{
						$project->deleteProject($prj["id"]);
					}
				}
			}
		}

		$sql = "delete from `".TABLE_PREFIX."portals_sites_tb` where id =".$id." or portal_id = ".$id ;
		$rs = $ms_db->modify($sql);

		return $rs;
	}



	function duplicate($id)
	{
		global $ms_db;
		
		$source = $this->getSiteByID($id);

		$sql = "INSERT INTO `".TABLE_PREFIX."portals_sites_tb` (  `type` , `updatedate`,`created_date` , `title` , `description` , `url` , `ftp_address` , `ftp_username` , `ftp_password` , `ftp_homepage` , `is_under_portal` , `portal_id`, `feed_writable`, `cache_writable`, `images_data_writable`, `temp_article_writable`,`user_id` )

VALUES ('".$ms_db->GetSQLValueString($source["type"],"text")."',NOW(),'".$ms_db->GetSQLValueString($source["created_date"],"date")."','".$ms_db->GetSQLValueString($source["title"],"text")."','".$ms_db->GetSQLValueString($source["description"],"text")."','".$ms_db->GetSQLValueString($source["url"],"text")."',"

		."'".$ms_db->GetSQLValueString($source["ftp_address"],"text")."',"
		."'".$ms_db->GetSQLValueString($source["ftp_username"],"text")."',"
		."'".$ms_db->GetSQLValueString($source["ftp_password"],"text")."',"
		."'".$ms_db->GetSQLValueString($source["ftp_homepage"],"text")."',"
		."'".$ms_db->GetSQLValueString($source["is_under_portal"],"text")."',"
		."'".$ms_db->GetSQLValueString($source["portal_id"],"text")."',"
		."'".$ms_db->GetSQLValueString($source["feed_writable"],"text")."',"
		."'".$ms_db->GetSQLValueString($source["cache_writable"],"text")."',"
		."'".$ms_db->GetSQLValueString($source["image_data_writable"],"text")."',"
		."'".$ms_db->GetSQLValueString($source["temp_article_writable"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX."sessionuserid"],"int").
		"')" ;

		$id = $ms_db->insert($sql);
		
		return $id;
	}


function post_to_session_for_site()
{

		$_SESSION[SESSION_PREFIX."type"] = $_POST["type"];
		$_SESSION[SESSION_PREFIX."created_date"] = $_POST["created_date"];
		$_SESSION[SESSION_PREFIX."title"] = $_POST["title"];
		$_SESSION[SESSION_PREFIX."description"] = $_POST["description"];
		$_SESSION[SESSION_PREFIX."url"] = $_POST["url"];
		$_SESSION[SESSION_PREFIX."ftp_address"] = $_POST["ftp_address"];
		$_SESSION[SESSION_PREFIX."ftp_username"] = $_POST["ftp_username"];
		$_SESSION[SESSION_PREFIX."ftp_password"] = $_POST["ftp_password"];		
		$_SESSION[SESSION_PREFIX."ftp_homepage"] = $_POST["ftp_homepage"];
		$_SESSION[SESSION_PREFIX."is_under_portal"] = $_POST["is_under_portal"];
		$_SESSION[SESSION_PREFIX."portal_id"] = $_POST["portal_id"]; 
		$_SESSION[SESSION_PREFIX.'newftp']=$_POST['ftpserveroption'];
}	



function post_to_session_for_portal()
{
		$_SESSION[SESSION_PREFIX."pftp_address"] = $_POST["ftp_address"];
		$_SESSION[SESSION_PREFIX."pftp_username"] = $_POST["ftp_username"];
		$_SESSION[SESSION_PREFIX."pftp_password"] = $_POST["ftp_password"];		
		$_SESSION[SESSION_PREFIX."pftp_homepage"] = $_POST["ftp_homepage"];
		$_SESSION[SESSION_PREFIX.'pnewftp']=$_POST['ftpserveroption'];
}	


function post_to_session_for_underportal()
{
		if ($_SESSION[SESSION_PREFIX.'process'] != "editpsite")
		{

			$portal = $this->getSiteByID($_SESSION[SESSION_PREFIX.'portal_id']);
		}
		if (isset($_POST['ftpsame']) && $_POST['ftpsame'] == "yes")
		{
			if ($_SESSION[SESSION_PREFIX.'process'] == "addXnewsite")
			{

//				$portal = $this->getSiteByID($_SESSION[SESSION_PREFIX.'portal_id']);
				if (isset($_POST["process"]) && $_POST["process"] == "cnbnewsite") 
					$_SESSION[SESSION_PREFIX."title"] = $_POST["title"];
				else
					$_SESSION[SESSION_PREFIX."title"] = $portal["title"];
					$_SESSION[SESSION_PREFIX."description"] = $portal["description"];
					$_SESSION[SESSION_PREFIX."created_date"] = date("Y-m-d");
					$_SESSION[SESSION_PREFIX."ftp_address"] = $portal["ftp_address"];
					$_SESSION[SESSION_PREFIX."ftp_username"] = $portal["ftp_username"];
					$_SESSION[SESSION_PREFIX."ftp_password"] = $portal["ftp_password"];
	//				$_SESSION[SESSION_PREFIX."ftp_homepage"] = $portal["ftp_homepage"];

			}
			else
			{
					$_SESSION[SESSION_PREFIX."ftp_address"] = $_SESSION[SESSION_PREFIX."pftp_address"];
					$_SESSION[SESSION_PREFIX."ftp_username"] = $_SESSION[SESSION_PREFIX."pftp_username"];
					$_SESSION[SESSION_PREFIX."ftp_password"] = $_SESSION[SESSION_PREFIX."pftp_password"];
					$_SESSION[SESSION_PREFIX.'newftp']=$_SESSION[SESSION_PREFIX.'pnewftp'];
//				$_SESSION[SESSION_PREFIX."ftp_homepage"] = $_SESSION[SESSION_PREFIX."pftp_homepage"];
			}
		}
		else 
		{
			if ($_SESSION[SESSION_PREFIX.'process'] != "editpsite")
			{
				$_SESSION[SESSION_PREFIX."title"] = $portal["title"];
				$_SESSION[SESSION_PREFIX."description"] = $portal["description"];
				$_SESSION[SESSION_PREFIX."created_date"] = date("Y-m-d");
				$_SESSION[SESSION_PREFIX.'newftp']=$_POST['ftpserveroption'];
			}
			$_SESSION[SESSION_PREFIX."ftp_address"] = $_POST["ftp_address"];
			$_SESSION[SESSION_PREFIX."ftp_username"] = $_POST["ftp_username"];
			$_SESSION[SESSION_PREFIX."ftp_password"] = $_POST["ftp_password"];		
			$_SESSION[SESSION_PREFIX.'newftp']=$_POST['ftpserveroption'];
	//		$_SESSION[SESSION_PREFIX."ftp_homepage"] = $_POST["ftp_homepage"];
		}
		$_SESSION[SESSION_PREFIX."url"] = $_POST["url"];
		$_SESSION[SESSION_PREFIX."ftp_homepage"] = $_POST["ftp_homepage"];
}



	function post_to_session_clear()
	{
		$_SESSION[SESSION_PREFIX."type"] = "";
		$_SESSION[SESSION_PREFIX."created_date"] = "";
		$_SESSION[SESSION_PREFIX."title"] = "";
		$_SESSION[SESSION_PREFIX."description"] = "";
		$_SESSION[SESSION_PREFIX."url"] = "";
		$_SESSION[SESSION_PREFIX."ftp_address"] = "";
		$_SESSION[SESSION_PREFIX."ftp_username"] = "";
		$_SESSION[SESSION_PREFIX."ftp_password"] = "";		
		$_SESSION[SESSION_PREFIX."ftp_homepage"] = "";
		$_SESSION[SESSION_PREFIX."is_under_portal"] = "";
		$_SESSION[SESSION_PREFIX."portal_id"] = ""; 
		$_SESSION[SESSION_PREFIX.'newftp']="";
	}



	function clearSessions()
	{
		global $process;

		$_SESSION[SESSION_PREFIX."page"] = "";
		$_SESSION[SESSION_PREFIX."portal_id"] = ""; 
		$_SESSION[SESSION_PREFIX."type"] = "";
		$_SESSION[SESSION_PREFIX."is_under_portal"] = "";
		$_SESSION[SESSION_PREFIX."site_id"] = "";
		$_SESSION[SESSION_PREFIX."process"] = "";
		$_SESSION[SESSION_PREFIX."theportalid"] = "";		
		$_SESSION[SESSION_PREFIX."sitecreationsteps"] = "";
		$_SESSION[SESSION_PREFIX."cnbnewsite"] = "";
		$_SESSION[SESSION_PREFIX.'newftp']="";
	}


	function checkSamePostDataForEditing()
	{
			global $ms_db;
			
			$sql = "Select count(id) from `".TABLE_PREFIX."portals_sites_tb` where 
			url = '".$ms_db->GetSQLValueString($_POST["url"],"text")."' and 
			ftp_address = '".$ms_db->GetSQLValueString($_POST['ftp_address'],"text")."' and 
			ftp_homepage = '".$ms_db->GetSQLValueString($_POST['ftp_homepage'],"text")."' and
			id ='".$_POST["site_id"]."'";

			$exist = $ms_db->getDataSingleRecord($sql);

			if ($exist > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
	}	



	function isURLexist($url, $exsite = 0)
	{
		global $ms_db;

		$sql = "Select count(*) from ".TABLE_PREFIX."addon_tbl where keyword='nvsb' and status='A'";
		$nvsb_data = $ms_db->getDataSingleRecord($sql);

		$sql = "Select count(*) from ".TABLE_PREFIX."addon_tbl where keyword='ncsb' and status='A'";
		$ncsb_data = $ms_db->getDataSingleRecord($sql);

		$sql = "Select count(*) from `".TABLE_PREFIX."portals_sites_tb` where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."' and url = '".$ms_db->GetSQLValueString($url,"text")."'";

		if ($exsite!=0)
			$sql.= " AND id != ".$exsite;		

		$exist = $ms_db->getDataSingleRecord($sql);

		if ($exist > 0)
		{
			return true;
		}

		if($nvsb_data)
		{
			$sql = "Select count(*) from `".TABLE_PREFIX."nvsbsites` where url = '".$ms_db->GetSQLValueString($url,"text")."'";
			$exist = $ms_db->getDataSingleRecord($sql);

			if ($exist > 0)
			{
				return true;
			}
		}
		
		if($ncsb_data)
		{
			$sql = "Select count(*) from `".TABLE_PREFIX."ncsbsites` where url = '".$ms_db->GetSQLValueString($url,"text")."'";
			$exist = $ms_db->getDataSingleRecord($sql);
			
			if ($exist > 0)
			{
				return true;
			}
		}
		
		return false;
	}



	function updateWPInstallPath($address,$id)
	{
		global $ms_db;

		$sql = "update `".TABLE_PREFIX."portals_sites_tb`  set 	wp_install_path ='$address',wp_installed ='Y'where id = '".$id."'";

		$id = $ms_db->modify($sql);

		return $id;
	}


	function getAllurl()
	{
		global $ms_db;

		$sql = "select * from `".TABLE_PREFIX."portals_sites_tb` where user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];
		$rs = $ms_db->getRS($sql);

		return $rs;
	}
}

?>