<?php
session_start();
class Profile
{
	function insert($penid="")
	{
		global $asm_db;
		
		$today = date("Y-m-d");
		
		
		
		$sql = "INSERT INTO `".TABLE_PREFIX."profile` (`profile_name`,`author`,`author_lname`, `biography`,`biography_html`,`date_created`, `comments`,`user_id`) VALUES (
		".$asm_db->GetSQLValueString($_POST['profile_name'],"text").",
		".$asm_db->GetSQLValueString($_POST['author'],"text").",
		".$asm_db->GetSQLValueString($_POST['author_lname'],"text").",
		".$asm_db->GetSQLValueString($_POST['biography'],"text").",
		".$asm_db->GetSQLValueString($_POST['biography_html'],"text").",
		".$asm_db->GetSQLValueString($today,"date").",
		".$asm_db->GetSQLValueString($_POST['comments'],"text").",
		".$asm_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int").")";
		
			
		$id = $asm_db->insert($sql);
		
		return $id;
	}
	
	function inserturl()
	{
		global $asm_db;
		
		//$sql = "select * from `".TABLE_PREFIX."url` where url='".$_POST['url']."' and directory_id='".$_POST['directory']."' and dir_label!='' and user_id=-1 or user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
			
		//$login_rs = $asm_db->getRS($sql); COmmented SDEI
		//$login_rs = $asm_db->getDataSingleRow($sql);
		//print_r($login_rs);
		//die;
		//echo $login_rs;
		//if($login_rs)
		//{
		
			$sql = "INSERT INTO `".TABLE_PREFIX."url` (`directory_id`,`dir_label`,`url`, `username`,`password`,`user_id`) VALUES (
			".$asm_db->GetSQLValueString($_POST['directory'],"int").",
			".$asm_db->GetSQLValueString($_POST['dir_label'],"text").",
			".$asm_db->GetSQLValueString($_POST['url'],"text").",
			".$asm_db->GetSQLValueString($_POST['login'],"text").",
			".$asm_db->GetSQLValueString($_POST['password'],"text").",
			".$asm_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int").
			")";
			//die;	
			$id = $asm_db->insert($sql);
		//}
		//else
		//{
			 
			
			//$sql = "update `".TABLE_PREFIX."url` set dir_label='".$_POST['dir_label']."',url='".$_POST['url']."',username='".$_POST['login']."',password='".$_POST['password']."' where directory_id='".$_POST['directory']."' and url='".$_POST['url']."' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
			
			//$id= $asm_db->modify($sql);
		//}
				
			return $id;
	}
	
	function editurl($id)
	{
		global $asm_db;
		
		$sql = "update `".TABLE_PREFIX."url` set dir_label='".$_POST['dir_label']."', username='".$_POST['login']."',password='".$_POST['password']."' where id=".$id;
		
		$update = $asm_db->modify($sql);
		
		return $update;
	}
	
	function edit($id)
	{
		global $asm_db;
		
		$sql = "update `".TABLE_PREFIX."profile` set profile_name='".$_POST['profile_name']."', author='".$_POST['author']."',author_lname='".$_POST['author_lname']."',biography='".$_POST['biography']."',biography_html='".$_POST['biography_html']."',comments='".$_POST['comments']."' where id=".$id;
		
		$update = $asm_db->modify($sql);
		
		return $update;
	}
	
	function update($penid="")
	{
		global $asm_db;
		
		if($_POST['type']=="EA")
		{
			$name = $_POST['author']." ".$_POST['author_lname'];
		}
		else
		{
			$name = $_POST['author'];
		}
		
		$sql = "update `".TABLE_PREFIX."profile` set profile_id='".$penid."' where dir_type='AD' and author='".$name."'";
		
		$update = $asm_db->modify($sql);
		
		return $update;
	}
	
	function delete($id)
	{
		global $asm_db;
		
		$sql = "delete from `".TABLE_PREFIX."profile` where id=".$id;
		
		$asm_db->modify($sql);
	}
	
	function deleteurl($id)
	{
		global $asm_db;
		
		$sql = "delete from `".TABLE_PREFIX."url` where id=".$id;
		
		$asm_db->modify($sql);
	}
	
	function getProfile()
	{
		global $asm_db,$order_sql,$pg;
		
		$sql = "select * from `".TABLE_PREFIX."profile` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid']." ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;
		$result = $asm_db->getRS($sql);
		return $result;
	}
	
	function getSingleProfileById($id)
	{
		global $asm_db;
		
		$sql = "select * from `".TABLE_PREFIX."profile` where id=".$id;
		$result = $asm_db->getDataSingleRow($sql);
		return $result; 
	}
	
	function getProfileCount()
	{
		global $asm_db;
		
		$sql = "select count(*) from `".TABLE_PREFIX."profile` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$profile = $asm_db->getDataSingleRecord($sql);
		
		return $profile;
	}
	
	function getArticleCount()
	{
		global $asm_db;
		
		$sql = "select count(*) from `".TABLE_PREFIX."article` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$profile = $asm_db->getDataSingleRecord($sql);
		
		return $profile;
	
	}
	
	function getSubmmitedArticleCount()
	{
		global $asm_db;
		
		$sql = "select count(article_id) from `".TABLE_PREFIX."submission` where isSubmit='Y' and error='N' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$profile = $asm_db->getDataSingleRecord($sql);
		
		return $profile;
	
	}
	
	function getPendingArticleCount()
	{
		global $asm_db;
		
		$sql = "select count(article_id) from `".TABLE_PREFIX."submission` where isScheduled='Y' and isSubmit='N' and error='N' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$profile = $asm_db->getDataSingleRecord($sql);
		
		return $profile;
	
	}
	
	function getRejectedArticleCount()
	{
		global $asm_db;
		
		$sql = "select count(article_id) from `".TABLE_PREFIX."submission` where isScheduled='Y' and isSubmit='N' and error='Y' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$profile = $asm_db->getDataSingleRecord($sql);
		
		return $profile;
	
	}
	
	function getProfileByDir()
	{
		global $asm_db;
		
		if($_POST['type']=="EA")
		{
			$name = $_POST['author']." ".$_POST['author_lname'];
		}
		else
		{
			$name = $_POST['author'];
		}
		
		$sql = "select * from `".TABLE_PREFIX."profile` where dir_type='AD' and author='".$name."' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$result = $asm_db->getDataSingleRow($sql);
		return $result; 
	}
	
	function getArticleByProfileId($id="")
	{
		global $asm_db;
		
		$sql = "select a.id,a.title,b.directory,c.schedule,c.isSubmit from `".TABLE_PREFIX."article` as a,`".TABLE_PREFIX."directory` as b, `".TABLE_PREFIX."submission` as c where  c.flag!='F' and c.profile_id =".$id." and a.id=c.article_id and b.id=c.directory_id and a.user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		
		$result = $asm_db->getRS($sql);
		return $result; 
	}
	
	function getArticleCountByProfileId($id="")
	{
		global $asm_db;
		
		$sql = "select count(*) from `".TABLE_PREFIX."submission` where flag!='F' and (profile_id in(".$id.")) and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		
		$result = $asm_db->getDataSingleRecord($sql);
		return $result; 
	}
        function duplicate_profile($id)
        {
           global $asm_db;
	   $sql = "select * from `".TABLE_PREFIX."profile` where id=".$id;
	   $res= $asm_db->getDataSingleRow($sql);
	   $today = date("Y-m-d");
	   //$tit=$res['keyword'];
           $sql = "INSERT INTO `".TABLE_PREFIX."profile` (`profile_name`,`author`,`author_lname`, `biography`,`biography_html`,`date_created`, `comments`,`user_id`) VALUES (
		".$asm_db->GetSQLValueString($res['profile_name'],"text").",
		".$asm_db->GetSQLValueString($res['author'],"text").",
		".$asm_db->GetSQLValueString($res['author_lname'],"text").",
		".$asm_db->GetSQLValueString($res['biography'],"text").",
		".$asm_db->GetSQLValueString($res['biography_html'],"text").",
		".$asm_db->GetSQLValueString($today,"date").",
		".$asm_db->GetSQLValueString($res['comments'],"text").",
		".$asm_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int").")";
		
		$id = $asm_db->insert($sql);

         }
}
?>