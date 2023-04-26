<?php
require_once("en_decode.class.php");	
class track
{
	
	function insertTrackPageDetails($ad_id, $site_id, $remote_path,$type)
	{
		global $ms_db;

		$exid = $this->checkExists($site_id, $remote_path);

		if ($exid)
			$this->deleteTrackPageDetails($exid);
		$clock = ($_POST['cloak']['check']) ? 1 : 0;	
		$sql = "INSERT INTO `".TABLE_PREFIX."trackingpages` ( `ad_id`, `site_id` , `remote_path` ,  date , type, cloaked, title, keywords)
		VALUES ("
		."'".$ms_db->GetSQLValueString($ad_id,"int")."',"
		."'".$ms_db->GetSQLValueString($site_id,"int")."',"
		."'".$ms_db->GetSQLValueString($remote_path,"text")."',"
		."'".$ms_db->GetSQLValueString(date("Y-m-d H:i:s"),"date")."',"
		."'".$ms_db->GetSQLValueString($type,"text")."',"
		."'".$ms_db->GetSQLValueString($_POST['cloak']['dams_on'],"int")."',"
		."'".$ms_db->GetSQLValueString($_POST['cloak']['title'],"text")."',"
		."'".$ms_db->GetSQLValueString($_POST['cloak']['keywords'],"text")."'"
		.")";
		$id = $ms_db->insert($sql);
		
		// insert cloack settings
		

		$sql = "Delete from `hct_affiliate_compaign` WHERE mod_type = 'cpp' AND page_id = ".$ms_db->GetSQLValueString($id,"int");
		$ms_db->modify($sql);

		include('../library/Project/Options/Encode.php');
		if ( $_POST['cloak']['check'] && $_POST['cloak']['dams_on'] ) {
			foreach ($_POST['chkselect'] as $compaign_id ) {
				$compaign_id = Project_Options_Encode::decode($compaign_id);
				$sql = "INSERT INTO `hct_affiliate_compaign` (`page_id`, `compaign_id`, `compaign_type`, `mod_type`) ";
				$sql .= "VALUES ( "
				. " '{$id}',"
				. " '{$compaign_id}',"
				. " '{$_POST['cloak']['dams']['type']}',"
				. " 'cpp')"; 
				$ms_db->modify($sql);
			}
		}
		
		return $id;
	}
	function checkExists($site_id, $remote_path)
	{
		global $ms_db;
		$sql = "Select id from ".TABLE_PREFIX."trackingpages Where
		site_id = '$site_id' AND
		remote_path = '$remote_path' LIMIT 1";
		
		$exid = $ms_db->getDataSingleRecord($sql);
		return $exid;
	}
	function deleteTrackPageDetails($rfid)
	{
		global $ms_db;
		$sql = "Delete from `".TABLE_PREFIX."trackingpages` WHERE id = ".$ms_db->GetSQLValueString($rfid,"int");
		$id = $ms_db->modify($sql);
		return $id;
	}

	function getTrackingCode($cid, $env='K', $tid=0)
	{
		$endec=new encode_decode();
		if ($env == 'C') $clink = "&tid=$tid"; else $clink = "";
		$code ='<?php';
		if ($env == 'K' || $env == 'C')
		$code .= '
		$href = urlencode(@$_SERVER["HTTP_REFERER"]);
		if($href=="")$href=urlencode($_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]);
		$rfip = $_SERVER["REMOTE_ADDR"];
		$url = "http://'.$_SERVER['HTTP_HOST'].'/ccp/trackid.php?href=$href&ip=$rfip&id='.$endec->encode($cid).$clink.'";';
		else if ($env == 'T')
		$code .= '
		////////////////////////////////////////////////////////////////////////////
		$amount = "AMOUNT"; // AMOUNT can be replaced with actual amount of product
		$items = "ITEMS";   // ITEMS can be replaced with no of items
		////////////////////////////////////////////////////////////////////////////
		$track_id = $_COOKIE["track_id"];
		$url = "http://'.$_SERVER['HTTP_HOST'].'/ccp/trackid.php?mytid=$track_id&items=$items&amount=$amount";';
		
		
		
		$code .= '
		if(function_exists("curl_init"))
		{
			$ch = @curl_init();
			@curl_setopt($ch, CURLOPT_URL, $url);
			@curl_setopt($ch, CURLOPT_HEADER, 0);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$resp = @curl_exec($ch); 
			
			$curl_resp = curl_errno($ch);

			if ($curl_resp == 0)
			{
				$val = $resp;
			}
			else if($curl_resp != 0 && $resp == "") 
			{
				$val = "";
			} 

			@curl_close($ch);
			unset($ch);		
		}
		else if(function_exists("fopen"))
		{
				$fp = @fopen($url,"r");
				if($fp)
				{		
					while(!@feof($fp))
					{
						$val .= @fgets($fp);
					}
					@fclose($fp);
				}
				else 
				{
					$val = "";
				}
		} ';
		
		if ($env != 'T')
		$code .= '
		$tid = trim($val);
		setcookie("track_id", $tid); ?>';
		else
		$code .= '
		setcookie ("track_id", "", time() - 3600);
		unset($_COOKIE["track_id"]); ?>';
		
		return $code;	
	}

	function insertClicksDetails($trackid, $url, $ip)
	{
		global $ms_db;
		$sql = "INSERT INTO `".TABLE_PREFIX."clicks` ( `track_id` , `ref_url` ,  `ip_add`, date)
		VALUES ("
		."'".$ms_db->GetSQLValueString($trackid,"int")."',"
		."'".$ms_db->GetSQLValueString($url,"text")."',"
		."'".$ms_db->GetSQLValueString($ip,"text")."',"
		."'".$ms_db->GetSQLValueString(date("Y-m-d H:i:s"),"date")."')";
		$id = $ms_db->insert($sql);
		return $id;
	}
	function inserttrackdata($searchengine, $keyword, $clicks="", $aid=0)
	{
		global $ms_db;
		if ($aid==0) $aid = $_GET["id"];
		$sql = "INSERT INTO `".TABLE_PREFIX."track` ( `ad_id` , `url_refered` ,  `keyword` , `clicks` , `date_of_search` )
		VALUES ("
		."'".$ms_db->GetSQLValueString($aid,"int")."',"
		."'".$ms_db->GetSQLValueString($searchengine,"text")."',"
		."'".$ms_db->GetSQLValueString($keyword,"text")."',"
		."'".$ms_db->GetSQLValueString($clicks,"text")."',"
		."'".$ms_db->GetSQLValueString(date("Y-m-d"),"date")."')";
		$id = $ms_db->insert($sql);
		return $id;
	}

	function updatetrackdata($id)
	{
		global $ms_db;
		$sql = "UPDATE `".TABLE_PREFIX."track` SET `clicks` = `clicks`+1 
		WHERE id= '".$id."'";
		$uid = $ms_db->modify($sql);	
		return $id;
	}
	function getTrackingIdByAdId($aid)
	{
		global $ms_db;
		$sql = "Select id from  `".TABLE_PREFIX."track` where ad_id = $aid LIMIT 1"; // LIMIT 1 just for precaution
		$kid = $ms_db->getDataSingleRecord($sql);
		return $kid;
	}

	function getaffiliatelink($affiliate_network)
	{
		global $ms_db;
		$sql = "SELECT affiliate_link FROM `".TABLE_PREFIX."affiliatenetwork` 
		where affiliate_name ='".$affiliate_network."' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$al = $ms_db->getDataSingleRow($sql);
		return $al["affiliate_link"];
	}


	function createTrackingPage($aid, $env, $merchantlink)
	{
		$newfilename = "temp_data/trackpage_".$aid."_".substr(md5(rand() * time()),0,4).".php";
		
		if(@file_exists($newfilename))
		{
			if(!is_writable($newfilename))
			{
				return false;
			}
		}
		
		if($env=='C')
			$tid = $this->getTrackingIdByAdId($aid);


		$trackcode = $this->getTrackingCode($aid, $env , $tid);


		if ( $_POST['cloak']['check'] == 1 ) {
$redirect = '<!doctype html><html><head>
<base href="'.$merchantlink.'=<?=$tid?>">
<title>'. htmlentities( $_POST['cloak']['title'] ) .'</title>
<meta name="keywords" content="'. htmlentities( $_POST['cloak']['keywords'] ) .'"/>
<style type="text/css">
html, body, div.iframe, iframe { margin:0; padding:0; height:100%; }
iframe { display:block; width:100%; border:none; }
html, body {overflow: hidden;}
</style>
</head>
<body>
<div class="iframe">
<iframe src="'.$merchantlink.'=<?=$tid?>" height="100%" width="100%"></iframe>
</div>';

		$code = "";
		if ($_POST['cloak']['dams_on'] == 1) {
			$serverPath = $_SERVER['SERVER_NAME'];
			foreach ($_POST['chkselect'] as $id) {
				$code .= ' <?php if(function_exists("curl_init")){ $ch = @curl_init();curl_setopt($ch, CURLOPT_URL,"http://'.$serverPath.'/dams/showcode.php?id='.$id.'&process='.$_POST['cloak']['dams']['type'].'&ref_url=".$_SERVER["HTTP_REFERER"]."&php_self=".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]);curl_setopt($ch, CURLOPT_HEADER, 0);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$resp=@curl_exec($ch);$err=curl_errno($ch);if($err === false || $resp ==""){$newsstr = "";}else{if (function_exists("curl_getinfo")){$info = curl_getinfo($ch);if ($info["http_code"]!=200)$resp="";}$newsstr = $resp;}@curl_close ($ch);echo $newsstr;} ?> ';
			}
		}
		
		$redirect  .= $code . '</body></html>';

		} else {
			$redirect = '<?php header("Location: '.$merchantlink.'=$tid"); ?>';
		}
		
		
		$trackcode = $trackcode."\n".$redirect;
		$trackcode = trim($trackcode);
		

		$fp = @fopen($newfilename,"w");
		if ($fp)
		{
			@fputs($fp,$trackcode,strlen($trackcode));
			@fclose($fp);
		}
		else
		{
			return false;
		}
		
		return $newfilename;
	}
	function insertSite($aid)
	{
		global $common, $campaign, $ms_db, $sites;
		
		$_POST['url'] = trim(str_replace(array("\\\\","\\"),"/",$_POST['url']));
		if (substr(trim($_POST['url']),0,7)!="http://") $_POST['url']="http://".$_POST['url'];
		if (substr($_POST['url'],strlen($_POST['url'])-1,1)!="/") $_POST['url'] .= "/";

		$sql = "INSERT INTO `".TABLE_PREFIX."site` ( `ad_id` , `url`, `ftp_address` ,  `ftp_username` , `ftp_password`,`user_id`)
		VALUES ("
		."'".$ms_db->GetSQLValueString($aid,"int")."',
		"."'".$ms_db->GetSQLValueString($_POST["url"],"text")."',"
		."'".$ms_db->GetSQLValueString($_POST["ftp_address"],"text")."',"
		."'".$ms_db->GetSQLValueString($_POST["ftp_username"],"text")."',"
		."'".$ms_db->GetSQLValueString($_POST["ftp_password"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int")."')";
		
		$id = $ms_db->insert($sql);
		return $id;
	}
	function getSiteByAdId($aid)
	{
		global $ms_db;
		
		$sql = "select * from `".TABLE_PREFIX."site` where ad_id=".$aid;
		
		$siteid = $ms_db->getDataSingleRow($sql);
		return $siteid;
	}

	function uploadTrackingPageOnSite($ad_id, $site_id, $trackingpage, $sourcefile, $sitetype="own")
	{
		
		global $common, $campaign, $ms_db, $sites;
		if ($sitetype=="own")
		{
			$dataofsite = $sites->getSiteByID($site_id);
		}
		else
		{
			$dataofsite["ftp_address"] = $_POST["ftp_address"];
			$dataofsite["ftp_username"] = $_POST["ftp_username"];
			$dataofsite["ftp_password"] = $_POST["ftp_password"];
			$dataofsite['ftp_homepage'] = $_POST["remote_file"];
		}
			
		$conn_id = @ftp_connect($dataofsite["ftp_address"]); 
		$login_result = @ftp_login($conn_id, $dataofsite["ftp_username"], $dataofsite["ftp_password"]); 
		@ftp_pasv( $conn_id, true );
		$str = $dataofsite['ftp_homepage'];
		$cut = $dataofsite['ftp_username'];

		if ($sitetype=="own")
			$ftphomepage = $common->getFTPhomePage($str, $cut);
		else
			$ftphomepage = $common->getFTPhomePageAdv($str, $cut);
		
		if (($conn_id) && ($login_result)) 
		{ 
			$source_file = $sourcefile;
			$destination_file = $ftphomepage.$trackingpage;
			
			if ($sitetype=="own")
				$trackingpageurl = $dataofsite["url"].$trackingpage;
			else
				$trackingpageurl = $dataofsite["ftp_address"];
			
			$upload = @ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 
			@unlink($source_file);
			if($upload)
			{
				$msg = "OK".'::::'.$trackingpageurl.'::::'.$destination_file ;
			}
			else
			{	
				$msg = "Problem in Uploading the file.";
			}
		}
		else 
		{
			$msg = "Unable to connect with server : ".$dataofsite["ftp_address"];
		}
		@ftp_close($conn_id);
		return $msg;
	}
	function uploadTrackingPageUsingFTPDetails()
	{
	
	
	}
	function deleteuploadedtrackingfile($id)
	{
		global $common, $campaign, $ms_db;
		$dataoftracking= $this->gettrackingById($id);
		$dataofsite= $this->getsitedata($dataoftracking["site_name"]);
		$conn_id = @ftp_connect($dataofsite["ftp_address"]); 
		$login_result = @ftp_login($conn_id, $dataofsite["ftp_username"], $dataofsite["ftp_password"]); 
		@ftp_pasv( $conn_id, true );
		$str = $dataofsite['ftp_homepage'];
		$cut = $dataofsite['ftp_username'];
		
		$ftphomepage = $common->getFTPhomePage($str, $cut);
		if (($conn_id) && ($login_result)) { 
			$destination_file = $ftphomepage."".$dataoftracking["creating_page"];
			
			if(unlink($destination_file))
			{
				return "yes";
			}
			else{	
				$msg= "Problem in Deleting the file.";
			}
		}
		else {
			$msg = "Sorry! there is some problem in login.";
		}
		
	header("Location: tracking.php?process=manage&camp_id=".$campaign_id."&msg=".$msg);
	}

	function gettrackingpage($track_id, $campaign_id)
	{
		global $common, $campaign, $ms_db;
		$dataoftracking= $this->gettrackingById($track_id);
		$dataofsite= $this->getsitedata($dataoftracking["site_name"]);
		$conn_id = @ftp_connect($dataofsite["ftp_address"]); 
		$login_result = @ftp_login($conn_id, $dataofsite["ftp_username"], $dataofsite["ftp_password"]); 
		@ftp_pasv( $conn_id, true );
		//$str = $dataofsite['ftp_homepage'];
		//$cut = $dataofsite['ftp_username'];
		
		//$ftphomepage = $common->getFTPhomePage($str, $cut);
		if (($conn_id) && ($login_result)) { 
			$destination_file = "/home".$dataofsite['ftp_homepage']."".$dataoftracking["creating_page"];
			if(file_exists($destination_file))
			{
				$handle = fopen($destination_file,"r");
				if($handle)
				{
					$trackingpage = fread($handle, filesize($destination_file));
					fclose($handle);
					return $trackingpage;
				}
				else{	
					$msg= "Problem in Opening the file.";
				}
			}
			else{
				$msg= "This file does not exists.";
				//print_r($_SERVER);die();
			}
		}
		else {
			$msg = "Sorry! there is some problem in login.";
		}
		
	header("Location: tracking.php?process=manage&camp_id=".$campaign_id."&msg=".$msg);
	}

	function edittrackingfileoflive($track_id, $campaign_id, $dataofedit)
	{
		global $common, $campaign, $ms_db;
		$dataoftracking= $this->gettrackingById($track_id);
		$dataofsite= $this->getsitedata($dataoftracking["site_name"]);
		$dataofedit=stripslashes($dataofedit);
		$conn_id = @ftp_connect($dataofsite["ftp_address"]); 
		$login_result = @ftp_login($conn_id, $dataofsite["ftp_username"], $dataofsite["ftp_password"]); 
		@ftp_pasv( $conn_id, true );
		//$str = $dataofsite['ftp_homepage'];
		//$cut = $dataofsite['ftp_username'];
		
		//$ftphomepage = $common->getFTPhomePage($str, $cut);
		if (($conn_id) && ($login_result)) { 
			$destination_file = "/home".$dataofsite['ftp_homepage']."".$dataoftracking["creating_page"];
			if(is_writable($destination_file))
			{
				$handle = fopen($destination_file,"w");
				if($handle)
				{
					$editedfile=fwrite($handle, $dataofedit);
					if($editedfile)
					{
						return true;
					}
					else
					{
						$msg="Problem in writing the file.";
					}
				}
				else
				{	
					$msg= "Problem in opening the file.";
				}
			}
			else
			{
				$msg= "This file does not have permissions to write.";
			}
		}
		else 
		{
			$msg = "Sorry! there is some problem in login.";
		}
		
		header("Location: tracking.php?process=manage&camp_id=".$campaign_id."&msg=".$msg);
	}
}
?>