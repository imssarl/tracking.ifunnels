<?php
	if (isset($_GET["id"]) && $_GET["id"]>0)
	{
		$para = explode("-",$_GET["id"]);
		$cid = $para[0];
		$redirect = $para[1];
	
		require_once("config/config.php");
		require_once("classes/database.class.php");
		require_once("classes/campaign.class.php");
	
		$damp_db = new Database();
		$campaign_obj = new Campaign();
		
		$damp_db->openDB();
		if(!(isset($_COOKIE[$cid.'Xxxx']) && $_COOKIE[$cid.'Xxxx']==$cid))
		{
			$campaign_obj->insertClicks($_SERVER["HTTP_REFERER"],$redirect,$cid); //Insert clicks for that ad in clicks tables.
		
			$campaign_obj->updateClicks($cid); //Update clicks detail an Campaign table.

			setcookie($cid."Xxxx",$cid,time()+COOKIE_TIME);
		}

		$redirectto = $campaign_obj->getTrackUrlToRedirect($redirect);

		header("location: $redirectto");
	}
?>