<?php
session_start();
	class Campaign
	{
		function insert()
		{
			//extract($_POST);
			//print_r($_POST);
			$num = "";
			//exit;
			global $damp_db,$sound_obj,$uploadflipped,$uploadbackground,$uploadsound,$msg,$common_obj;
			
			$sql="select campaign_name from  `".TABLE_PREFIX."adcampaigns` where campaign_name='".$_POST["campaign_name"]."' and user_id='".$_SESSION[MSESSION_PREFIX.'sessionuserid']."'";
			
			$num=$damp_db->getData($sql);
			
			if($num!="")
			{
			
			return false;
			}
		else
		{	$position='';
			
			$position.=$_POST['positionC']."+";
			
			
			$position.=$_POST['positionS']."+";
			
			
			$position.=$_POST['positionF'];
			//echo $position;

			if(isset($_POST["sound_id_hid"]) && $_POST["sound_id_hid"]!="")
			{
				$sound_id = $_POST["sound_id_hid"];
			}
					
			else
			{
				$sound_id="";
			}
			
			$subsrt = strpos($_POST["url"],"http");
			
			if($subsrt!==false)
			{
				$url=$_POST["url"];
			}
			else
			{
				$url="http://".$_POST["url"];
			}

			if(isset($_POST["contents"]) && trim($_POST["contents"])!="")
			{
				$content=$_POST["contents"];
				if($_POST['open_url']=="n")
 				{
					$myRegExp = "<a";
					$myRegExpreplace = "<a target='_blank'";
					$content = str_replace($myRegExp, $myRegExpreplace,$content);
					
					$myRegExp = "<A";
					$myRegExpreplace = "<A target='_blank'";
					$content = str_replace($myRegExp, $myRegExpreplace,$content);
					//echo $content; die();
					//$content = html_entity_decode($content);
				}
				//print_r($content);
			}

			if(isset($_POST["txt_contents"]) && trim($_POST["txt_contents"])!="")
			{
				$content=$_POST["txt_contents"];
 				
 				if($_POST['open_url']=="n")
 				{
					$myRegExp = "<a";
					$myRegExpreplace = "<a target='_blank'";
					$content = str_replace($myRegExp, $myRegExpreplace,$content);
					
					$myRegExp = "<A";
					$myRegExpreplace = "<A target='_blank'";
					$content = str_replace($myRegExp, $myRegExpreplace,$content);
					//$content = html_entity_decode($content);
				}
			}
			
			if(isset($_POST["fix_txt_contents"]) && trim($_POST["fix_txt_contents"])!="")
			{
				$fix_content=$_POST["fix_txt_contents"];
				//$fix_content = html_entity_decode($fix_content);
				//echo $fix_content.'12';exit;
			}

			 if(isset($_POST["fix_html_contents"]) && trim($_POST["fix_html_contents"])!="")
			{
				$fix_content=$_POST["fix_html_contents"];
				//$fix_content = html_entity_decode($fix_content);
				//echo $fix_content.'13';exit;
			}
			
			//echo $fix_content.'14';exit;
			
			$zero =0;
			
			$sql="Insert into `".TABLE_PREFIX."adcampaigns`(`campaign_name`,`start_date`,`end_date`,`position`,`on_action`,`corner_position`,`fix_position`,`floating`,`sdiv_pos_type`,`sdiv_pos`,`fdiv_height_type`,`fdiv_height`,`fdiv_width_type`,`fdiv_width`,`fdiv_background_color`,`fdiv_border_style`,`fdiv_border_width`,`fdiv_border_color`,`url`,`open_url`,`content_type`,`contents`,`fix_cont_type`,`fix_contents`,`play_sound`,`sound_id`,`track_ad`,`clicks`,`impression`,`effectiveness`,`user_id`) values (
			".$damp_db->GetSQLValueString($_POST["campaign_name"],"text").",
			".$damp_db->GetSQLValueString($_POST["start_date"],"text").",
			".$damp_db->GetSQLValueString($_POST["end_date"],"text").",
			".$damp_db->GetSQLValueString($position,"text").",
			".$damp_db->GetSQLValueString($_POST["on_action"],"text").",
			".$damp_db->GetSQLValueString($_POST["corner_position"],"text").",
			".$damp_db->GetSQLValueString($_POST["corner_position1"],"text").",
			".$damp_db->GetSQLValueString($_POST["floating_eff"],"text").",
			".$damp_db->GetSQLValueString($_POST["sheight"],"text").",
			".$damp_db->GetSQLValueString($_POST["user_shgt"],"text").",
			".$damp_db->GetSQLValueString($_POST["height"],"text").",
			".$damp_db->GetSQLValueString($_POST["user_hgt"],"text").",
			".$damp_db->GetSQLValueString($_POST["width"],"text").",
			".$damp_db->GetSQLValueString($_POST["user_width"],"text").",
			".$damp_db->GetSQLValueString($_POST["header_caption_color"],"text").",
			".$damp_db->GetSQLValueString($_POST["border_style"],"text").",
			".$damp_db->GetSQLValueString($_POST["border_width"],"text").",
			".$damp_db->GetSQLValueString($_POST["border_caption_color"],"text").",
			".$damp_db->GetSQLValueString($url,"text").",
			".$damp_db->GetSQLValueString($_POST['open_url'],"text").",
			".$damp_db->GetSQLValueString($_POST["content_type"],"text").",
			".$damp_db->GetSQLValueString($content,"text").",
			".$damp_db->GetSQLValueString($_POST["fix_content_type"],"text").",
			".$damp_db->GetSQLValueString($fix_content,"text").",
			".$damp_db->GetSQLValueString($_POST["play_sound"],"text").",
			'".$sound_id."',
			".$damp_db->GetSQLValueString($_POST["track_ad"],"text").",
			".$damp_db->GetSQLValueString($zero,"int").",
			".$damp_db->GetSQLValueString($zero,"int").",
			".$damp_db->GetSQLValueString($zero,"int").",
			".$damp_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int")."
			)";
		
			$campaign_id = $damp_db->insert($sql);
			//echo $content; die();
			if($content!="")
			{
			$content = html_entity_decode($content);
			$this->changeLinksWithTrackURLs($content, $campaign_id,"N");
			}
			if($fix_content!="")
			{
			$fix_content = html_entity_decode($fix_content);
			$this->changeLinksWithTrackURLs($fix_content, $campaign_id,"N");
			}
			$this->changeLinksWithTrackURLs($content, $campaign_id,"N");
			
			if($_POST["position"]=="S")
			{
				$small_corner_img=null;
			}
			elseif(isset($_POST["small_corner_img"]) && $_POST["small_corner_img"]!="")
			{
				$small_corner_img = $_POST["small_corner_img"];
			}
			elseif($_FILES["small_corner_img"]["error"]=="0")
			{
				$small_corner_img = $this->upload_flipped_img($campaign_id);
				
				if($small_corner_img===false)
				{
					$flipped_upload = false;
					$msg = "Erro with flipped_upload";
				}
			}
			
			$sql="Update `".TABLE_PREFIX."adcampaigns` set `small_corner_img`='".$small_corner_img."' where id=".$campaign_id;
			$damp_db->modify($sql);

			
			if(isset($_POST["background"]) && $_POST["background"]!="")
			{ 
				$background = $_POST["background"];
			}
			else if($_FILES["background"]["error"]=="0")
			{
				$background = $this->upload_background_img($campaign_id);
				//echo $background;exit;
				if($background===false)
				{
					$uploadbackground = false;
					$msg = "Erro with uploadbackground";					
				}
			}
			
			$sql="Update `".TABLE_PREFIX."adcampaigns` set `background`='".$background."' where id=".$campaign_id;
			$damp_db->modify($sql);

			if($_FILES["original_name"]["error"]=="0")
			{
				$sound_name = $this->upload_sound();
				
				if($sound_name===false)
				{
					$uploadsound=false;
					$msg = "Some error while uploading sound";
				}
				else
				{
					$sound_id = $sound_obj->insert();
					
					rename("sound_files/temp","sound_files/sound_".$sound_id);
					
					$sql="Update `".TABLE_PREFIX."adcampaigns` set `sound_id`='".$sound_id."' where id=".$campaign_id;
					
					$damp_db->modify($sql);
				}
			}
			
			if($uploadbackground && $uploadsound && $uploadflipped)
			{
				return true;
			}
			else
			{
				//echo $uploadflipped;
				$this->delete($campaign_id);
				return false;
			}


		}
		}
	
	
		function update($id)
		{
			global $damp_db,$sound_obj,$uploadflipped,$uploadbackground,$uploadsound;
			
			$position='';
			
			$position.=$_POST['positionC']."+";
			
			
			$position.=$_POST['positionS']."+";
			
			
			$position.=$_POST['positionF'];
			
			
			
			
			$small_corner_img=$_POST["small_corner_img"];
			//print_r($_POST);exit;
			if(isset($_POST["contents"]) && $_POST["contents"]!="")
			{
				if($_POST["content_type"]=="H")
				$content=$_POST["contents"];
				if($_POST['open_url']=="n")
 				{
					$myRegExp = "<a";
					$myRegExpreplace = "<a target='_blank'";
					$content = str_replace($myRegExp, $myRegExpreplace,$content);
					
					$myRegExp = "<A";
					$myRegExpreplace = "<A target='_blank'";
					$content = str_replace($myRegExp, $myRegExpreplace,$content);
					//$content = html_entity_decode($content);
				}
				
			}

			if(isset($_POST["txt_contents"]) && $_POST["txt_contents"]!="")
			{
				if($_POST["content_type"]=="T")
				$content=$_POST["txt_contents"];
				if($_POST['open_url']=="n")
 				{
					$myRegExp = "<a";
					$myRegExpreplace = "<a target='_blank'";
					$content = str_replace($myRegExp, $myRegExpreplace,$content);
					
					$myRegExp = "<A";
					$myRegExpreplace = "<A target='_blank'";
					$content = str_replace($myRegExp, $myRegExpreplace,$content);
					//$content = html_entity_decode($content);
				}
			}
			
			if(isset($_POST["fix_txt_contents"]) && trim($_POST["fix_txt_contents"])!="")
			{
				if($_POST["fix_content_type"]=="T")
				$fix_content=$_POST["fix_txt_contents"];
				//$fix_content = html_entity_decode($fix_content);
				
			}
			
			 if(isset($_POST["fix_html_contents"]) &&  trim($_POST["fix_html_contents"])!="")
			{	
				
				if($_POST["fix_content_type"]=="H")
					$fix_content=$_POST["fix_html_contents"];
					//$fix_content = html_entity_decode($fix_content);
				//var_dump($campaign_Data["fix_cont_type"]);
				//die();
			}

			//echo $fix_content."rahul";exit;
			if($_POST['height']=="a")
			{
				$_POST['user_hgt']="";
			}
			if($_POST['width']=="d")
			{
				$_POST['user_width']="";
			}
			if($_POST['sheight']=="d")
			{
				$_POST['user_shgt']="";
			}
			$sql="Update `".TABLE_PREFIX."adcampaigns` set 
			`campaign_name`=".$damp_db->GetSQLValueString($_POST["campaign_name"],"text").",
			`start_date`=".$damp_db->GetSQLValueString($_POST["start_date"],"text").",
			`end_date`=".$damp_db->GetSQLValueString($_POST["end_date"],"text").",
			`position`=".$damp_db->GetSQLValueString($position,"text").",
			`on_action`=".$damp_db->GetSQLValueString($_POST["on_action"],"text").",
			`corner_position`=".$damp_db->GetSQLValueString($_POST["corner_position"],"text").",
			`small_corner_img`='".$small_corner_img."',
			fix_position=".$damp_db->GetSQLValueString($_POST["corner_position1"],"text").",
			floating=".$damp_db->GetSQLValueString($_POST["floating_eff"],"text").",
			sdiv_pos_type=".$damp_db->GetSQLValueString($_POST["sheight"],"text").",
			sdiv_pos=".$damp_db->GetSQLValueString($_POST["user_shgt"],"text").",
			fdiv_height_type=".$damp_db->GetSQLValueString($_POST["height"],"text").",
			fdiv_height=".$damp_db->GetSQLValueString($_POST["user_hgt"],"text").",
			fdiv_width_type=".$damp_db->GetSQLValueString($_POST["width"],"text").",
			fdiv_width=".$damp_db->GetSQLValueString($_POST["user_width"],"text").",
			fdiv_background_color=".$damp_db->GetSQLValueString($_POST["header_caption_color"],"text").",
			fdiv_border_style=".$damp_db->GetSQLValueString($_POST["border_style"],"text").",
			fdiv_border_width=".$damp_db->GetSQLValueString($_POST["border_width"],"text").",
			fdiv_border_color=".$damp_db->GetSQLValueString($_POST["border_caption_color"],"text").",
			`url`=".$damp_db->GetSQLValueString($_POST["url"],"text").",
			`open_url`=".$damp_db->GetSQLValueString($_POST["open_url"],"text").",
			`content_type`=".$damp_db->GetSQLValueString($_POST["content_type"],"text").",
			`contents`=".$damp_db->GetSQLValueString($content,"text").",
			`fix_cont_type`=".$damp_db->GetSQLValueString($_POST["fix_content_type"],"text").",
			`fix_contents`=".$damp_db->GetSQLValueString($fix_content,"text").",
			`play_sound`=".$damp_db->GetSQLValueString($_POST["play_sound"],"text").",
			`sound_id`=".$damp_db->GetSQLValueString($_POST["sound_id_hid"],"text").",
			`track_ad`=".$damp_db->GetSQLValueString($_POST["track_ad"],"text")."
			 where id=".$id;
			
// 			echo $sql;
// 			die();
			
			$damp_db->modify($sql);
			
			 if($content!="")
			 {
			 	$content = html_entity_decode($content);
				$link = $this->changeLinksWithTrackURLs($content, $id, "N");
			 }
			if($fix_content!="")
			{
			$fix_content = html_entity_decode($fix_content);
			$this->changeLinksWithTrackURLs($fix_content, $id,"N");
			}
			$link = $this->changeLinksWithTrackURLs($content, $id, "N");
			
//////////////////////////////Updating Background Images///////////////////////////////////////	
			//print_r($_POST);
			//print_r($_FILES);
			//die();	
			//exit;
			if(isset($_POST["background"]) && $_POST["background"]!="" && $_POST["background_default"]=="D")
			{ //echo "dfsd";exit;
				$background = $_POST["background"];
			}
			
			elseif($_FILES["background"]["error"]=="0")
			  { //echo "go";exit;
				$background = $this->upload_background_img($_POST["campaign_id_hid"]);
				//echo $background;exit;
				if($background===false)
				{ 
					$uploadbackground = false;
				}
			 
			}
			//echo $background;exit;
			$sql="Update `".TABLE_PREFIX."adcampaigns` set `background`='".$background."' where id=".$id;
			//echo $sql; exit;
			$damp_db->modify($sql);

			
			
//////////////////////////////////Updating small corner images //////////////////////
			
			
			if($_FILES["small_corner_img"]["error"]=="0")
			{
				$small_corner_img = $this->upload_flipped_img($_POST["campaign_id_hid"]);
				
				if($small_corner_img===false)
				{
					$flipped_upload = false;
 				}
 				else
 				{
 					$sql="Update `".TABLE_PREFIX."adcampaigns` set `small_corner_img`='".$small_corner_img."'";
					$damp_db->modify($sql);
 				}
			}
			
//////////////////////////////Updating Sonud///////////////////////////////////////
			
/*			print_r($_POST);
			print_r($_FILES);
			die();*/
			
			if($_FILES["original_name"]["error"]=="0")
			{
				$sound_name = $this->upload_sound();
				
				if($sound_name===false)
				{
					$uploadsound=false;
					$msg = "Some error while uploading sound";
				}
				else
				{
					$sound_id = $sound_obj->insert();
					
					rename("sound_files/temp","sound_files/sound_".$sound_id);
					
					$sql="Update `".TABLE_PREFIX."adcampaigns` set `sound_id`='".$sound_id."' where id=".$_POST["campaign_id_hid"];
					
					$damp_db->modify($sql);
				}
			}
		}
		
		function insertDuplicate($id)
		{
			global $damp_db;
			$sql = "Select * from `".TABLE_PREFIX."adcampaigns` where id=".$id;
			$campaign_rs = $damp_db->getDataSingleRow($sql);
			//print_r($campaign_rs);die();
			$zero =0;
			$content = html_entity_decode($campaign_rs["contents"]);
			$fix_contents = html_entity_decode($campaign_rs["fix_contents"]);
			//echo $content;die();
			$sql1="Insert into `".TABLE_PREFIX."adcampaigns`(`campaign_name`,`start_date`,`end_date`,`position`,`on_action`,`corner_position`,`small_corner_img`,`fix_position`,`floating`,`sdiv_pos_type`,`sdiv_pos`,`fdiv_height_type`,`fdiv_height`,`fdiv_width_type`,`fdiv_width`,`fdiv_background_color`,`fdiv_border_style`,`fdiv_border_width`,`fdiv_border_color`,`url`,`open_url`,`content_type`,`contents`,`fix_cont_type`,`fix_contents`,`background`,`play_sound`,`sound_id`,`track_ad`,`clicks`,`impression`,`effectiveness`,`user_id`) values (
			".$damp_db->GetSQLValueString($campaign_rs["campaign_name"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["start_date"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["end_date"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["position"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["on_action"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["corner_position"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["small_corner_img"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["fix_position"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["floating"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["sdiv_pos_type"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["sdiv_pos"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["fdiv_height_type"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["fdiv_height"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["fdiv_width_type"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["fdiv_width"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["fdiv_background_color"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["fdiv_border_style"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["fdiv_border_width"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["fdiv_border_color"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["url"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs['open_url'],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["content_type"],"text").",
			".$damp_db->GetSQLValueString($content,"text").",
			".$damp_db->GetSQLValueString($campaign_rs["fix_cont_type"],"text").",
			".$damp_db->GetSQLValueString($fix_contents,"text").",
			".$damp_db->GetSQLValueString($campaign_rs["background"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["play_sound"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["sound_id"],"text").",
			".$damp_db->GetSQLValueString($campaign_rs["track_ad"],"text").",
			".$damp_db->GetSQLValueString($zero,"int").",
			".$damp_db->GetSQLValueString($zero,"int").",
			".$damp_db->GetSQLValueString($zero,"int").",
			".$damp_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int")."
			)";
			
			$campaign = $damp_db->insert($sql1);
			return $campaign;
		}
		
		
		function getCampaignById($id)
		{
			global $damp_db;
			$sql = "Select * from `".TABLE_PREFIX."adcampaigns` where id=".$id;
			
			$campaign_rs = $damp_db->getDataSingleRow($sql);
			
			if($campaign_rs)
			{
				return $campaign_rs;
			}
			else
			{
				return false;
			}
		}
		function getUrlbyId($id)
		{
			global $damp_db;
			$sql = "Select url from `".TABLE_PREFIX."adcampaigns` where id=".$id;
			
			$campaign_rs = $damp_db->getDataSingleRecord($sql);
			
			if($campaign_rs)
			{
				return $campaign_rs;
			}
			else
			{
				return false;
			}
		}
		
		function delete($id)
		{
			global $damp_db;
			
			$sql="delete from `".TABLE_PREFIX."adcampaigns` where id=".$id;
			$damp_db->modify($sql);
			
			$sql="delete from `".TABLE_PREFIX."split_campaign` where campaign_id=".$id;
			$damp_db->modify($sql);
		}
		
		function insertClicks($ref_url,$php_self,$cid=0)
		{ 
		global $damp_db;
		
		$datetime = Date("Y-m-d H:i:s");
		
		if($cid>0)
		$id = $cid;
		else
		$id = $_GET["id"];
		
		$sql = "Insert into `".TABLE_PREFIX."clicks`(ad_id,datetime,refurl,siteurl,ipaddress) values (
		".$damp_db->GetSQLValueString($id,"int").",
		".$damp_db->GetSQLValueString($datetime,"text").",
		".$damp_db->GetSQLValueString($ref_url,"text").",
		".$damp_db->GetSQLValueString($php_self,"text").",
		".$damp_db->GetSQLValueString($_SERVER["REMOTE_ADDR"],"text").")";
		//echo $sql; die();
		$clicks = $damp_db->insert($sql);
		
		}
		function insertClicksUrl($php_self="",$cid=0)
		{ 
		global $damp_db;
		
		$datetime = Date("Y-m-d H:i:s");
		
		if($cid>0)
		$id = $cid;
		else
		$id = $_GET["id"];
		
		$sql = "Insert into `".TABLE_PREFIX."clicks`(ad_id,datetime,refurl,ipaddress) values (
		".$damp_db->GetSQLValueString($id,"int").",
		".$damp_db->GetSQLValueString($datetime,"text").",
		".$damp_db->GetSQLValueString($php_self,"text").",
		".$damp_db->GetSQLValueString($_SERVER["REMOTE_ADDR"],"text").")";
		//echo $sql; die();
		$clicks = $damp_db->insert($sql);
		
		}
		function insertImpression($campaign_id,$ref_url,$php_self)
		{
			global $damp_db;
		
			$datetime = Date("Y-m-d H:i:s");

			$sql = "Insert into `".TABLE_PREFIX."impressions`(ad_id,datetime,refurl,siteurl,ipaddress) values (
			".$damp_db->GetSQLValueString($campaign_id,"int").",
			".$damp_db->GetSQLValueString($datetime,"text").",		
			".$damp_db->GetSQLValueString($ref_url,"text").",
			".$damp_db->GetSQLValueString($php_self,"text").",
			".$damp_db->GetSQLValueString($_SERVER["REMOTE_ADDR"],"text").")";
	//echo $sql;die();
			$clicks = $damp_db->insert($sql);
			
		}
		function insertEffectiveness($ref_url="",$php_self="")
		{
			global $damp_db;
		
			$datetime = Date("Y-m-d H:i:s");

			$sql = "Insert into `".TABLE_PREFIX."effectiveness`(ad_id,datetime,refurl,siteurl,ipaddress) values (
			".$damp_db->GetSQLValueString($_GET["id"],"int").",
			".$damp_db->GetSQLValueString($datetime,"text").",		
			".$damp_db->GetSQLValueString($ref_url,"text").",
			".$damp_db->GetSQLValueString($php_self,"text").",
			".$damp_db->GetSQLValueString($_SERVER["REMOTE_ADDR"],"text").")";
	
			$effectiveness = $damp_db->insert($sql);
			
		}
		
		function updateImpression($id)
		{
			global $damp_db;
			
			$sql ="update `".TABLE_PREFIX."adcampaigns` set impression=impression+1 where id=".$id;
			$damp_db->modify($sql);
		}
		
		function updateClicks($id)
		{
			global $damp_db;
			
			$sql ="update `".TABLE_PREFIX."adcampaigns` set clicks=clicks+1 where id=".$id;
			$damp_db->modify($sql);
		}
		
		function updateEffectiveness($id)
		{
			global $damp_db;
			
			$sql ="update `".TABLE_PREFIX."adcampaigns` set effectiveness=effectiveness+1 where id=".$id;
			$damp_db->modify($sql);
		}
		
		function upload_flipped_img($campaign_id)
		{
			$ext = strtolower(strrev(substr(strrev($_FILES['small_corner_img']['name']),0,strpos(strrev($_FILES['small_corner_img']['name']),"."))));
			
			$uploaddir = 'flipped_images/';
			$filemame =  "flippedimg_".$campaign_id.".".$ext;
			
			$uploadfile = $uploaddir.$filemame;
			
			if (move_uploaded_file($_FILES['small_corner_img']['tmp_name'], $uploadfile))
			{
				return $filemame;
			}
			else
			{
				return false;
			}
		}
		function upload_background_img($campaign_id)
		{
		
			$uploaddir = 'background_images/';
			
			$filename = "background_".$campaign_id;
				
			$uploadfile = $uploaddir.$filename;
				//echo $filename;exit;
			if (move_uploaded_file($_FILES['background']['tmp_name'], $uploadfile))
			{
				return $filename;
			}
			else
			{
				return false;
			}
		}
		function upload_sound()
		{
			$uploaddir = 'sound_files/';
			$filename = "temp";
			
			$uploadfile = $uploaddir.$filename;
			
			if (move_uploaded_file($_FILES['original_name']['tmp_name'], $uploadfile))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		function getAllCampaignName()
		{
			global $damp_db;
		
			$sql = "select  id,campaign_name from `".TABLE_PREFIX."adcampaigns` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
			$result = $damp_db->getRS($sql);
		
			return $result;
		}
		function getNoOfCornerAds()
		{
		
		global $damp_db;
	
		$sql = "select  count(*) from `".TABLE_PREFIX."adcampaigns` where position like '%C%' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$result = $damp_db->getDataSingleRecord($sql);
	
		return $result;
		}
		function getNoOfSlideInAds()
		{
		
		global $damp_db;
	
		$sql = "select  count(*) from `".TABLE_PREFIX."adcampaigns` where position like '%S%' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$result = $damp_db->getDataSingleRecord($sql);
	
		return $result;
		}
		function getNoOfFixAds()
		{
		
		global $damp_db;
	
		$sql = "select  count(*) from `".TABLE_PREFIX."adcampaigns` where position like '%F%' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$result = $damp_db->getDataSingleRecord($sql);
	
		return $result;
		}
		function getTotalNoOfAds()
		{
			
			global $damp_db;
	
			$sql = "select  count(*) from `".TABLE_PREFIX."adcampaigns`  where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
			$result = $damp_db->getDataSingleRecord($sql);
			return $result;
 		}
		function getTotalNoOfRunningAds()
		{
			
			global $damp_db;
			$today = date('Y-m-d');
		
		$sql= "select count(*) from `".TABLE_PREFIX."adcampaigns` where end_date >= '".$today."' or end_date is NULL  and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

//echo $sql;die();
			$result = $damp_db->getDataSingleRecord($sql);
	
			return $result;
 		}
		
		function getTotalNoOfClosedAds()
		{
			
			global $damp_db;

			$today = date('Y-m-d');
	
			$sql= "select count(*) from `".TABLE_PREFIX."adcampaigns` where end_date < '".$today."' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

			$result = $damp_db->getDataSingleRecord($sql);
	
			return $result;
 		}
 		function getSplitTestById($id)
		{
			global $damp_db;
		
			$sql = "select  * from `".TABLE_PREFIX."split_test` where id=".$damp_db->GetSQLValueString($id,"int")."";
			
			$result = $damp_db->getDataSingleRow($sql);
		
			return $result;
		}

 		function changeLinksWithTrackURLs($in, $partid,$isShowCode)
		{
		$posstart = 0;
		$out = $in = html_entity_decode($in);
		
		while(($posofA = strpos(strtolower($in),"<a",$posstart)) !== false)
		{
		$posofAend = strpos($in, ">",$posofA+1);
		$posofAtagend = strpos(strtolower($in), "</a>",$posofA+1);
		if ($posofAend === false)
		{
		if ($posofAtagend === false)
		{
		return $in;
		}
		else
		{
		$posstart = $posofAtagend;
		continue;
		}
		}
		else
		{
		if ($posofAtagend === false)
		{
		return $in;
		}
		else
		{
		if ($posofAend > $posofAtagend)
		{
		$posstart = $posofAtagend;
		continue;
		}
		}
		}
		if ($posofA>=0)
		{
		$posofhref = strpos(strtolower($in),"href",$posofA+1);
		if ($posofhref>=0)
		{
		$posoflinkstarta = strpos($in,"'",$posofhref+1);
		$posoflinkstartb = strpos($in,'"',$posofhref+1);
		
		//if ($posoflinkstarta===false)$posoflinkstarta=-1;
		//if ($posoflinkstartb===false)$posoflinkstartb=-1;
		$linkstartposfound = false;
		
		if ($posoflinkstarta !== false && $posoflinkstartb !== false)
		{
		if ($posoflinkstarta < $posoflinkstartb)
		{
		$posoflinkstart = $posoflinkstarta+1;
		$linkquot = "'";
		$linkstartposfound = true;
		}
		else
		{
		$posoflinkstart = $posoflinkstartb+1;
		$linkquot = '"';
		$linkstartposfound = true;
		}
		}
		else if ($posoflinkstarta !== false)
		{
		$posoflinkstart = $posoflinkstarta+1;
		$linkquot = "'";
		$linkstartposfound = true;
		}
		else if ($posoflinkstartb !== false)
		{
		$posoflinkstart = $posoflinkstartb+1;
		$linkquot = '"';
		$linkstartposfound = true;
		}
		else
		{
		$linkstartposfound = false;
		}
		if ($linkstartposfound)
		{
		$posoflinkend = strpos($in,$linkquot,$posoflinkstart);
		if ($posoflinkend>0)
		{
		$link = substr($in,$posoflinkstart,$posoflinkend-$posoflinkstart);
		$posstart = $posoflinkend+1;
		//echo "==> $posstart <==$link<br>";
		//echo htmlentities(substr($in,$posstart,1000));
		$tracked = strpos($link,"campaigntrack.php?");
		if ($tracked>0)
		{
		
		}
		else
		{
		$posofAclose = strpos(strtolower($in),">",$posoflinkend);
		$posofAtagclose = strpos(strtolower($in),"</a>",$posofAclose);
		if ($posofAclose !== false && $posofAtagclose!== false)
		$atext = substr($in,$posofAclose+1,$posofAtagclose-$posofAclose-1);
		else
		$atext = "X";
		
		//$posstart = $posofAclose+1;
		$posstart = $posoflinkstart;
		
		$trackURL = $this->getTrackURLForLink($link,$atext, $partid,$isShowCode);
		//$in = str_replace($link,$trackURL,$in);
		$in = substr_replace($in,$trackURL,$posoflinkstart,$posoflinkend-$posoflinkstart);
		
		}
		}
		else
		{
		$posstart = $posoflinkstart;
		}
		}
		else
		{
		$posstart = $posofhref;
		}
		}
		else
		{
		$posstart = $posofA;
		}
		}
		}
		return $in;
		}
		
		function getTrackURLForLink($link,$atext, $partid,$isShowCode)
		{
			global $damp_db;
			
			$sql = "Select * from `".TABLE_PREFIX."campaign_trackurls` where `campaign_id` = ".$partid;
			
			$trk_rs = $damp_db->getRS($sql);
			
			if ($trk_rs)
			{
				while($turl = $damp_db->getNextRow($trk_rs))
				{
					if ($link == $turl["url"])
					{
					$trackURL = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/campaigntrack.php?id=$partid-".$turl["id"];
					return $trackURL;
					}
				}
			}
			if($isShowCode=="N")
			{
			$sql = "INSERT INTO `".TABLE_PREFIX."campaign_trackurls` (`campaign_id` , `url`, anchortext )
			VALUES (".$damp_db->GetSQLValueString($partid,"int").",
			".$damp_db->GetSQLValueString($link,"text").",
			".$damp_db->GetSQLValueString($atext,"text").")";
			$id = $damp_db->insert($sql);
			
			$trackURL = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/campaigntrack.php?id=$partid-$id";
			}
			return $trackURL;
		}
		function getTrackUrlToRedirect($redirect)
		{
			global $damp_db;
			
			$sql = "Select url from `".TABLE_PREFIX."campaign_trackurls` where id = ".$redirect;
			$track = $damp_db->getDataSingleRecord($sql);
			return $track;
		}
		
		function insertSplitTest()
		{
			global $damp_db;
			$today = date("Y-m-d");
		//	print_r($_POST);die();
			
			if(isset($_POST["split_test_duration_checkbox"]) && $_POST["split_test_duration_checkbox"]=="Y")
			{
				$isDuration = "Y";
				$durationType = $_POST["duration_days"];
				if($durationType=="D")
					$duration = $_POST["spilt_duration_days_inputbox"];
				else
					$duration = $_POST["spilt_duration_hits_inputbox"];
			}
			else
			{
				$isDuration = "N";
				$durationType = null;
				$duration = null;
			}
			$sql = "insert into `".TABLE_PREFIX."split_test`(`test_name`,`isDuration`,`duration_type`,`duration`,`date_created`,`user_id`)
					 values (".$damp_db->GetSQLValueString($_POST["test_name"],"text").",
					 		".$damp_db->GetSQLValueString($isDuration,"text").",
					 		".$damp_db->GetSQLValueString($durationType,"text").",	".$damp_db->GetSQLValueString($duration,"int").",
					 		".$damp_db->GetSQLValueString($today,"date").",
							".$damp_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"date").")";
			
			$id = $damp_db->insert($sql);
			
			$campaign_id = $_POST["S_campaign_list"];
			
			//print_r($campaign_id);die();
			if(is_array($campaign_id))
			{
				foreach($campaign_id as $new_campaign_id)
				{
					$sql = "insert into `".TABLE_PREFIX."split_campaign`(`split_test_id`,`campaign_id`)
						 values (".$damp_db->GetSQLValueString($id,"int").",
						".$damp_db->GetSQLValueString($new_campaign_id,"int").")";
					
					$damp_db->insert($sql);
				}
			}
			else
			{
				$sql = "insert into `".TABLE_PREFIX."split_campaign`(`split_test_id`,`campaign_id`)
					 values (".$damp_db->GetSQLValueString($id,"int").",
					".$damp_db->GetSQLValueString($campaign_id,"int").")";
				
				$damp_db->insert($sql);
			}
			return $id;
		}
		function updateTest($id)
		{
			global $damp_db;
			
			if(isset($_POST["split_test_duration_checkbox"]) && $_POST["split_test_duration_checkbox"]=="Y")
			{
				$isDuration = "Y";
				$durationType = $_POST["duration_days"];
				if($durationType=="D")
					$duration = $_POST["spilt_duration_days_inputbox"];
				else
					$duration = $_POST["spilt_duration_hits_inputbox"];
			}
			else
			{
				$isDuration = "N";
				$durationType = null;
				$duration = null;
			}
			
			$sql = "update `".TABLE_PREFIX."split_test` set `test_name`= ".$damp_db->GetSQLValueString($_POST["test_name"],"text")." , `isDuration`= ".$damp_db->GetSQLValueString($isDuration,"text")." , `duration_type`= ".$damp_db->GetSQLValueString($durationType,"text")." , `duration`=".$damp_db->GetSQLValueString($duration,"int")." where id=".$id;
			
			$damp_db->Modify($sql);
			
			$campaign_id = $_POST["S_campaign_list"];
			
			$sql="delete from `".TABLE_PREFIX."split_campaign` where `split_test_id`=".$damp_db->GetSQLValueString($id,"int")."";
			$damp_db->modify($sql);
			
			//print_r($campaign_id);die();
			if(is_array($campaign_id))
			{
				foreach($campaign_id as $new_campaign_id)
				{
					$sql = "insert into `".TABLE_PREFIX."split_campaign`(`split_test_id`,`campaign_id`)
						 values (".$damp_db->GetSQLValueString($id,"int").",
						".$damp_db->GetSQLValueString($new_campaign_id,"int").")";
					
					$damp_db->insert($sql);
				}
			}
			else
			{
				$sql = "insert into `".TABLE_PREFIX."split_campaign`(`split_test_id`,`campaign_id`)
					 values (".$damp_db->GetSQLValueString($id,"int").",
					".$damp_db->GetSQLValueString($campaign_id,"int").")";
				
				$damp_db->insert($sql);
			}
		}
		function deleteSplitTest($id)
		{
			global $damp_db;
			
			$sql="delete from `".TABLE_PREFIX."split_test` where id=".$id;
			$damp_db->modify($sql);
		}
		function endSplitTest($id)
		{
			global $damp_db;
			
			// Setting it to be comlpeted.
			$sql="UPDATE `".TABLE_PREFIX."split_test` set isRunning='N' where id=".$id;
			$damp_db->modify($sql);
			
			$campaignIdOfHieghtestCTR = $this->getCampaignOfHightestCTR($id);
			
			//Making this campaign winner in the split_campaign table for this perticlular split test
			
			$sql="UPDATE `".TABLE_PREFIX."split_campaign` SET `isWinner` = 'Y' WHERE `campaign_id` =".$campaignIdOfHieghtestCTR." AND `split_test_id` =".$_GET['id'];
			$damp_db->modify($sql);			
		}
		// lostarchives - 6:35 PM 1/21/2009
		function getCampaignOfHightestCTR($splitTestId)
		{
			global $damp_db;
					
			// Getting campaigns in this split test with its information like impression and clicks
			$sql = "SELECT * FROM `".TABLE_PREFIX."adcampaigns` WHERE id=".$splitTestId;
			$campaign_rs = $damp_db->getRS($sql);
			echo '<pre>'; print_r($campaign_rs); echo '</pre>';
			//a.id,a.clicks,a.impression//a,`".TABLE_PREFIX."split_campaign` b
/*
			
			
			
			//////// Calculating hiegest CTR ////////////
			$ctrstr="";
			while($campaign_Data = $damp_db->getNextRow($campaign_rs))
			{
				if($campaign_Data['impression']!=0)
					$ctrstr.=round($campaign_Data['clicks']/$campaign_Data['impression']*100,2)." ";
				else
					$ctrarr[]=0;
			}
			$ctrarr = explode(" ",$ctrstr);
			rsort($ctrarr);
			$hieghtest_CTR = $ctrarr[0];
			
			$damp_db->moveFirst($campaign_rs);
			//////// Code Ends Calculating hiegest CTR ////////////
			
			//////// Calculating hiegest CTR campaign ////////////
			while($campaign_Data = $damp_db->getNextRow($campaign_rs))
			{
				if($campaign_Data['impression']!=0)
				{
					$ctrstr=round($campaign_Data['clicks']/$campaign_Data['impression']*100,2)." ";
					
					if(trim($ctrstr)==trim($hieghtest_CTR))
					{ 
						$campaignIdOfHieghtestCTR = $campaign_Data['id'];
					}
				}
			}
			//////// Code Ends Calculating hiegest CTR campaign ////////////
			return $campaignIdOfHieghtestCTR;
*/			
		}
		function insertDuplicateSplitTest($id)
		{
			global $damp_db;
			
			$sql="select * from `".TABLE_PREFIX."split_test` where id=".$id;
			$splitTestData = $damp_db->getDataSingleRow($sql);
			
			$sql = "insert into `".TABLE_PREFIX."split_test`(`test_name`,`isDuration`,`duration_type`,`duration`,`date_created`,`user_id`) values (".$damp_db->GetSQLValueString($splitTestData["test_name"],"text").", ".$damp_db->GetSQLValueString($splitTestData["isDuration"],"text").",
			".$damp_db->GetSQLValueString($splitTestData["duration_type"],"text").",	".$damp_db->GetSQLValueString($splitTestData["duration"],"int").",
			".$damp_db->GetSQLValueString($splitTestData["date_created"],"date").",
			".$damp_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int").")";

			$newid = $damp_db->insert($sql);
			
			$sql = "select campaign_id from ".TABLE_PREFIX."split_campaign where split_test_id=".$damp_db->GetSQLValueString($id,"int")."";
			
			//echo $sql;die();
			$campaign_id = $damp_db->getData($sql);
			//print_r( $campaign_id);die();
			if(is_array($campaign_id))
			{
				foreach($campaign_id as $new_campaign_id)
				{
					$sql = "insert into `".TABLE_PREFIX."split_campaign`(`split_test_id`,`campaign_id`)
						 values (".$damp_db->GetSQLValueString($newid,"int").",
						".$damp_db->GetSQLValueString($new_campaign_id['campaign_id'],"int").")";
					$damp_db->insert($sql);
				}
			}
		}
		function getCampaignbySplitId($id)
		{
			global $damp_db;
			
			$sql = "Select a.isWinner,b.id,b.campaign_name,b.impression,b.clicks,b.effectiveness from ".TABLE_PREFIX."split_campaign a, ".TABLE_PREFIX."adcampaigns b where a.campaign_id=b.id and a.split_test_id=".$id;
			
			//echo $sql;die();
			$rs = $damp_db->getRS($sql);
			
			if($rs)
				return $rs;
			else
				return false;
		}
}
?>
