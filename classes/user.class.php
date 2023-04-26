<?php
class User
{
	function getUserInfo()
	{

		global $cnm_db;
		
		$sql = "select * from ".TABLE_PREFIX."user where email  LIKE ('".$_POST['email']."') and password LIKE ('".$_POST['password']."')";

		$result = $cnm_db->getDataSingleRow($sql);

		if($result)
		{
			return $result;
		}
		else
		{
			return false;
		}	
	}
	
	function getUserById($id)
	{

		global $cnm_db;
		
		$sql = "select * from ".TABLE_PREFIX."user where id=".$id;

		$result = $cnm_db->getDataSingleRow($sql);

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

		global $cnm_db;
		
		$sql = "select * from ".TABLE_PREFIX."user where email ='".$_POST['email']."'";

		$result = $cnm_db->getDataSingleRow($sql);

		if($result)
		{
			return $result;
		}
		else
		{
			return false;
		}	
	}
	
	function editUser($contents)
	{

		global $cnm_db;
		
		$sql = "update `".TABLE_PREFIX."user` set
		password=".$cnm_db->GetSQLValueString($_POST['password'],"text").",
		fname=".$cnm_db->GetSQLValueString($contents['fname'],"text").",
		referer_id=".$cnm_db->GetSQLValueString($contents['referer_id'],"text").",
		lname=".$cnm_db->GetSQLValueString($contents['lname'],"text")." where email=".$cnm_db->GetSQLValueString($contents['email'],"text");
	
		$result = $cnm_db->modify($sql);
	}
	
// 	function updateUser($contents)
// 	{
// 
// 		global $cnm_db;
// 		
// 		$sql = "update `".TABLE_PREFIX."user` set
// 		fname=".$cnm_db->GetSQLValueString($contents['fname'],"text").",
// 		lname=".$cnm_db->GetSQLValueString($contents['lname'],"text");
// 	
// 		$result = $cnm_db->modify($sql);
// 	}
	
	function insertUser($contents)
	{

		global $cnm_db;
		
		$sql="Insert into `".TABLE_PREFIX."user` (`email`,`password`,`referer_id`,`fname`,`lname`,`isAdmin`) values (
			".$cnm_db->GetSQLValueString($contents['email'],"text").",
			".$cnm_db->GetSQLValueString($_POST['password'],"text").",
			".$cnm_db->GetSQLValueString($contents['referer_id'],"text").",
			".$cnm_db->GetSQLValueString($contents['fname'],"text").",
			".$cnm_db->GetSQLValueString($contents['lname'],"text").",'Y')";
		$cnm_db->insert($sql);
	}
	function insert($contents)
	{

		global $cnm_db;
		
		$sql="Insert into `".TABLE_PREFIX."user` (`email`,`password`,`referer_id`,`fname`,`lname`,`isAdmin`) values (
			".$cnm_db->GetSQLValueString($contents['email'],"text").",
			".$cnm_db->GetSQLValueString($_POST['password'],"text").",
			".$cnm_db->GetSQLValueString($contents['referer_id'],"text").",
			".$cnm_db->GetSQLValueString($contents['fname'],"text").",
			".$cnm_db->GetSQLValueString($contents['lname'],"text").",'N')";
		$id = $cnm_db->insert($sql);
		return $id;
	}
	function insertAdmin($contents)
	{
		global $cnm_db;
		
		$sql="Insert into `".TABLE_PREFIX."user` (`email`,`password`,`referer_id`,`fname`,`lname`,`isAdmin`) values (
			".$cnm_db->GetSQLValueString($contents['email'],"text").",
			".$cnm_db->GetSQLValueString($_POST['password'],"text").",
			".$cnm_db->GetSQLValueString($contents['referer_id'],"text").",
			".$cnm_db->GetSQLValueString($contents['fname'],"text").",
			".$cnm_db->GetSQLValueString($contents['lname'],"text").",'Y')";
		
		$id = $cnm_db->insert($sql);
		
		return $id;
	}
	
	function UpdatePaypalId($email)
	{
		global $cnm_db;
		
		$sql = "update `".TABLE_PREFIX."user` set
		paypal_id=".$cnm_db->GetSQLValueString($_POST['paypal_id'],"text").",
		currency_code=".$cnm_db->GetSQLValueString($_POST['curr_code'],"text")." where email=".$cnm_db->GetSQLValueString($email,"text")." and isAdmin='Y'";
		//echo $sql;
		$result = $cnm_db->modify($sql);
	}
	
	function getadminEmail($email)
	{
		global $cnm_db;
		
		$sql = "select * from ".TABLE_PREFIX."user where email ='".$email."'";
		$result = $cnm_db->getDataSingleRow($sql);
		
		if($result)
		{
			return $result;
		}
		else
		{
			return false;
		}
	}


}
?>