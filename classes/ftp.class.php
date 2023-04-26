<?php 
session_start();

class Ftp
{

	function getFtpDetail()
	{
		global $cnm_db;
		//$sql="select * from ".TABLE_PREFIX."ftp_tb where userid='".$_SESSION[SESSION_PREFIX.'userid']."'";
		$sql="select * from ".TABLE_PREFIX."ftp_details_tb where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
		//echo $sql; die();	
		$result = $cnm_db->getRS($sql);

		return $sql;
	}
	function Select($id)
	{
		global $cnm_db;
		$sql = "select * from ".TABLE_PREFIX."ftp_details_tb where id=".$id;
		//echo $sql;
		$result  = $cnm_db->getDataSingleRow($sql);
		return $result;
	}
	function edit($id)
	{
		global $cnm_db;
		if($id!='')
		{
			$sql = "Update ".TABLE_PREFIX."ftp_details_tb set ftp_address='".$cnm_db->GetSQLValueString($_POST['ftp_add'],"text")."',ftp_username='".$cnm_db->GetSQLValueString($_POST['ftp_uname'],"text")."',ftp_password='".$cnm_db->GetSQLValueString($_POST['ftp_pass'],"text")."' where id=".$id;
			$cnm_db->modify($sql);
		}
		else
		{
						$sql ="INSERT INTO ".TABLE_PREFIX."ftp_details_tb (`ftp_address`, `ftp_username`, `ftp_password`, `user_id`) VALUES ('".$cnm_db->GetSQLValueString($_POST['ftp_add'],"text")."', '".$cnm_db->GetSQLValueString($_POST['ftp_uname'],"text")."', '".$cnm_db->GetSQLValueString($_POST['ftp_pass'],"text")."', '".$_SESSION[SESSION_PREFIX.'sessionuserid']."');";							
						
			$cnm_db->insert($sql);
			
		}
	}
	
	function Addftp()
	{
		global $ms_db;
		/*global $cnm_db;
		$sql="SELECT id from ".TABLE_PREFIX."ftp_details_tb where ftp_address='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX.'ftp_address'],"text")."' and ftp_usernames='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX.'ftp_username'],"text")."' and ftp_passwords='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX.'ftp_password'],"text")."' and user_id='".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX.'sessionuserid'],"text")."'";
		$result = $cnm_db->getDataSingleRow($sql);*/
		 $sql3="select count(*) from ".TABLE_PREFIX."ftp_details_tb where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."' and ftp_address='".trim($_SESSION[SESSION_PREFIX.'ftp_address'])."' and ftp_username='".trim($_SESSION[SESSION_PREFIX.'ftp_username'])."' ";
			$checkFtp=$ms_db->getDataSingleRecord($sql3);
			if($checkFtp=="0")
			{			
			$sql ="INSERT INTO ".TABLE_PREFIX."ftp_details_tb (`ftp_address`, `ftp_username`, `ftp_password`, `user_id`) VALUES ('".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX.'ftp_address'],"text")."', '".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX.'ftp_username'],"text")."', '".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX.'ftp_password'],"text")."', '".$_SESSION[SESSION_PREFIX.'sessionuserid']."');";							
						
			$ms_db->insert($sql);
			}
		
	}
	
	function delete($id)
	{
		global $cnm_db;
		$sql = "Delete from ".TABLE_PREFIX."ftp_details_tb where id=".$id;
		$cnm_db->modify($sql);
	}	
}
?>