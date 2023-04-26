<?php
	require_once("config/config.php");
	require_once("classes/database.class.php");
	require_once("classes/common.class.php");
	require_once("classes/campaign.class.php");

	$damp_db = new Database();
	$common_obj = new Common();
	$campaign_obj = new Campaign();

	$damp_db->openDB();

//echo "This is cookie=".$_COOKIE[$_GET["id"].'Xxxx'];
	if(isset($_GET["id"]) && $_GET["id"]>0)
	{
		if(!(isset($_COOKIE[$_GET["id"].'Xxxx']) && $_COOKIE[$_GET["id"].'Xxxx']==$_GET["id"]))
		{
		
			$campaign_obj->insertClicksUrl($_GET['php_self']); //Insert clicks for that ad in clicks tables.
			$campaign_obj->updateClicks($_GET["id"]); //Update clicks detail an Campaign table.

			setcookie($_GET["id"]."Xxxx",$_GET["id"],time()+COOKIE_TIME);
		}
		if(isset($_GET['redirectUrl']) && $_GET['redirectUrl']!="")	
		{
			$subsrt = strpos($_GET['redirectUrl'],"http");
				
			if($subsrt!==false)
			{
				$redirectUrl=$_GET['redirectUrl'];
			}
			else
			{
				$redirectUrl="http://".$_GET['redirectUrl'];
			}
		}
		else  // Case of Corner Ads
		{
			$redirectUrl = $campaign_obj->getUrlbyId($_GET["id"]);	
			$subsrt = strpos($redirectUrl,"http");	
			if($subsrt!==false)
			{
				$redirectUrl=$redirectUrl;
			}
			else
			{
				$redirectUrl="http://".$redirectUrl;
			}
			
		}
		
		header("location: ".$redirectUrl);
		
	}
?>