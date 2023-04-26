<?php
class admin
{
	function getAdminInfo()
	{

		global $damp_db;
		
		$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where email_address ='".$_POST['email']."' and password='".$_POST['password']."'";

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
	function ViewAdminSetting()
	{

		global $damp_db;
	
		$sql = "select * from `".TABLE_PREFIX."admin_settings_tb` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$result = $damp_db->getDataSingleRow($sql);
	
		return $result;
	}

	function editAdminSetting()
	{

		global $damp_db;
		if(isset($_POST['npass']) && $_POST['npass']!="")
		{
			$password = $_POST['npass'];
		}
		else
		{
			$password = $_POST['aopass'];
		}
	
		$sql = "update `".TABLE_PREFIX."admin_settings_tb` set
		username= '".$damp_db->GetSQLValueString($_POST['aname'],"text")."',
		email_address='".$damp_db->GetSQLValueString($_POST['aemail'],"text")."',
		password='".$damp_db->GetSQLValueString($password,"text")."' where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
	
		$result = $damp_db->modify($sql);
	} 	
}
?>