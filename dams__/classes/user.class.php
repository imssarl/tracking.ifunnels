<?php
class User
{
	function getUserInfo()
	{

		global $damp_db;
		
		$sql = "select * from ".TABLE_PREFIX."user where email  LIKE ('".$_POST['email']."') and password LIKE ('".$_POST['password']."')";

		$result = $damp_db->getDataSingleRow($sql);

		if($result)
		{
			return $result;
		}
		else
		{
			return false;
		}	
	}
	
	function getUserEmail()
	{

		global $damp_db;
		
		$sql = "select * from ".TABLE_PREFIX."user where email ='".$_POST['email']."'";

		$result = $damp_db->getDataSingleRow($sql);

		if($result)
		{
			return $result;
		}
		else
		{
			return false;
		}	
	}
	
	function editUser()
	{

		global $damp_db;
		
		$sql = "update `".TABLE_PREFIX."user` set
		password=".$damp_db->GetSQLValueString($_POST['password'],"text").",
		fname=".$damp_db->GetSQLValueString($contents['fname'],"text").",
		lname=".$damp_db->GetSQLValueString($contents['lname'],"text");
	
		$result = $damp_db->modify($sql);
	}
	
	function updateUser($contents)
	{

		global $damp_db;
		
		$sql = "update `".TABLE_PREFIX."user` set
		referer_id=".$damp_db->GetSQLValueString($contents['referer_id'],"text").",
		fname=".$damp_db->GetSQLValueString($contents['fname'],"text").",
		lname=".$damp_db->GetSQLValueString($contents['lname'],"text");
	
		$result = $damp_db->modify($sql);
	}
	
	function insertUser()
	{

		global $damp_db;
		
		$sql="Insert into `".TABLE_PREFIX."user`(`email`,`password`,`user_id`) values (
			".$damp_db->GetSQLValueString($_POST['email'],"text").",
			".$damp_db->GetSQLValueString($_POST['password'],"text").",
			".$damp_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int").")";
		$damp_db->insert($sql);
	}
}
?>