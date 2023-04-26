<?php
	require_once("config/config.php");
	require_once("classes/database.class.php");
	require_once("classes/common.class.php");
	require_once("classes/campaign.class.php");

	$damp_db = new Database();
	$common_obj = new Common();
	$campaign_obj = new Campaign();

	$damp_db->openDB();
	require_once("classes/en_decode.class.php");	
	$endec=new encode_decode();
	$_GET["id"] = $endec->decode($_GET["id"]);
	if(isset($_GET["id"]) && $_GET["id"]!="")
	{
		if(!(isset($_COOKIE[$_GET["id"]."Xxx"]) && $_COOKIE[$_GET["id"]."Xxx"]==$_GET["id"]))
		{
			
			//echo "===>".$_GET['php_self']); die();
			if(!(isset($_COOKIE[$_GET["id"].'Xxxx']) && $_COOKIE[$_GET["id"].'Xxxx']==$_GET["id"]))
			{//echo "dasdasd";
				$campaign_obj->insertClicksUrl(); //Insert clicks for that ad in clicks tables.
				$campaign_obj->updateClicks($_GET["id"]);
			}

			$campaign_obj->insertEffectiveness(); //Insert Effectiveness for that ad in Effectiveness table.
			$campaign_obj->updateEffectiveness($_GET["id"]); // effectiveness++ in Campaign table for this Ad.
			//$str=$_GET["id"]."B";
			setcookie($_GET["id"]."Xxx",$_GET["id"],time()+COOKIE_TIME);
			//$_COOKIE['Xxx']=$_GET["id"]);
		}	
	}
?>