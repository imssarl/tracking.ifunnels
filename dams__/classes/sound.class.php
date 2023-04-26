<?php
class Sound
{

	function getAllSound()
	{
		global $damp_db;
		$sql= "Select * from ".TABLE_PREFIX."sounds where user_id=-1 or user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$rs = $damp_db->getRS($sql);
		
		if($rs)
			return $rs;
		else
			return false;
	}
	function insert()
	{
		global $damp_db;
		$today = date("Y-m-d");
			
		$sql="Insert into `".TABLE_PREFIX."sounds` (`title`,`description`,`original_name`,`date_uploaded`,`size`,`user_id`) values (
		".$damp_db->GetSQLValueString($_POST["title"],"text").",
		".$damp_db->GetSQLValueString($_POST["description"],"text").",
		".$damp_db->GetSQLValueString($_FILES["original_name"]["name"],"text").",
		'".$today."',
		".$damp_db->GetSQLValueString($_FILES["original_name"]["size"],"int").",
		".$damp_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int")."
		)";
		
		$id = $damp_db->insert($sql);
		return $id;
	}
	function getSoundById($id)
	{
		global $damp_db;
		$sql= "Select * from ".TABLE_PREFIX."sounds where id=".$id;
		$sound_rs = $damp_db->getDataSingleRow($sql);
		
		if($sound_rs)
			return $sound_rs;
		else
			return false;
	}
	

}
?>