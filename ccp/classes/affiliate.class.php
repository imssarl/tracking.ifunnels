<?php
class affiliate
{
	// making it compatible with other's coding we are using field name affiliate_network 
	// for id of affiliate network.
	function insertaffiliate()
	{
		global $ms_db;
		$sql = "INSERT INTO `".TABLE_PREFIX."affiliatenetwork` ( `affiliate_name` , `affiliate_link`, type,`user_id`)
		VALUES ("
		."'".$ms_db->GetSQLValueString($_POST["affiliate_name"],"text")."',"
		."'".$ms_db->GetSQLValueString($_POST["affiliate_link"],"text")."',"		
		."'".$ms_db->GetSQLValueString("U","text")."',"
		."'".$ms_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int")."')";
		$id = $ms_db->insert($sql);
		return $id;	
	}
	function insertAffiliateByValue($affiliate_name)
	{
		global $ms_db;
		$sql = "INSERT INTO `".TABLE_PREFIX."affiliatenetwork` ( `affiliate_name` , `affiliate_link`, type,`user_id`)
		VALUES ("
		."'".$ms_db->GetSQLValueString($affiliate_name,"text")."',"
		."'".$ms_db->GetSQLValueString("No-Link","text")."',"		
		."'".$ms_db->GetSQLValueString("U","text")."',"	
		."'".$ms_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int")."')";
		$id = $ms_db->insert($sql);
		return $id;	
	}
	function getMerchantLink($affiliate_network,$merchant_link)
	{
		global $ms_db;
		
		$sql = "SELECT affiliate_link from `".TABLE_PREFIX."affiliatenetwork` 
		where id = ".$ms_db->getSqlValueString($affiliate_network, "int");
		
		$affiliate_link = $ms_db->getDataSingleRecord($sql);
		
		if ($affiliate_link)
			$merchant_link .= $affiliate_link;

			return html_entity_decode($merchant_link);
	}
	
	function updateaffiliate($id)
	{
		global $ms_db;
		$sql = "UPDATE `".TABLE_PREFIX."affiliatenetwork` SET 
		`affiliate_name` = '".$ms_db->GetSQLValueString($_POST["affiliate_name"],"text")."',
		`affiliate_link` = '".$ms_db->GetSQLValueString($_POST["affiliate_link"],"text")."'
		WHERE `id` = ".$id;
		$id = $ms_db->modify($sql);
	
		return $id;	
	}
	function deleteaffiliate($id)
	{
		global $ms_db;
		$sql = "Delete from  `".TABLE_PREFIX."affiliatenetwork` WHERE `id` = ".$id;
		$id = $ms_db->modify($sql);
		return $id;			
	}
	
	function getaffiliateById($id)
	{
		global $ms_db;
		$sql = "SELECT * from  `".TABLE_PREFIX."affiliatenetwork` where id = ".$id;
		$rs = $ms_db->getDataSingleRow($sql);
		return $rs;	
	}
	function setAfnUserDetails($anid)
	{
		global $ms_db;
		$sql = "Select id from ".TABLE_PREFIX."affn_user_details 
		where affiliatenetwork_id = ".$ms_db->GetSQLValueString($anid,"int");
		$found = $ms_db->getDataSingleRecord($sql);
		if (!$found)
		{
			if ($anid==2)
			{
				$sql = "INSERT INTO `".TABLE_PREFIX."affn_user_details` 
				( `affiliatenetwork_id` ,`valt` ,`user_id`)
				VALUES ("
				."".$ms_db->GetSQLValueString($anid,"int").","
				."'".$ms_db->GetSQLValueString($_POST["devkey"],"text")."',"
				."'".$ms_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int")."')";		
				$id = $ms_db->insert($sql);
				return $anid;
			}
			else if ($anid==3)
			{
				$sql = "INSERT INTO `".TABLE_PREFIX."affn_user_details` 
				( `affiliatenetwork_id` , `val1` , `val2` , `val3` ,`val4`,`user_id` )
				VALUES ("
				."".$ms_db->GetSQLValueString($anid,"int").","
				."'".$ms_db->GetSQLValueString($_POST["userid"],"text")."',"
				."'".$ms_db->GetSQLValueString($_POST["passwd"],"text")."',"
				."'".$ms_db->GetSQLValueString($_POST["affid"],"text")."',"
				."'".$ms_db->GetSQLValueString($_POST["netwid"],"text")."',"
				."'".$ms_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int")."')";		
				$id = $ms_db->insert($sql);
				return $anid;
			}
		}
		else
		{
			if ($anid==2)
			{
				$sql = "UPDATE `".TABLE_PREFIX."affn_user_details`  SET 
				valt = '".$ms_db->GetSQLValueString($_POST["devkey"],"text")."'
				WHERE affiliatenetwork_id = ".$ms_db->GetSQLValueString($anid,"int");
				$id = $ms_db->modify($sql);
				return $anid;
			
			}
			else if ($anid==3)
			{
				$sql = "UPDATE `".TABLE_PREFIX."affn_user_details`  SET 
				val1 = '".$ms_db->GetSQLValueString($_POST["userid"],"text")."',
				val2 = '".$ms_db->GetSQLValueString($_POST["passwd"],"text")."',
				val3 = '".$ms_db->GetSQLValueString($_POST["affid"],"text")."',
				val4 = '".$ms_db->GetSQLValueString($_POST["netwid"],"text")."'
				WHERE affiliatenetwork_id = ".$ms_db->GetSQLValueString($anid,"int");		
				$id = $ms_db->modify($sql);
				return $anid;
			}
		}
	}
	function getAfnUserDetails($anid)
	{
		global $ms_db;
		$sql = "Select * from ".TABLE_PREFIX."affn_user_details 
		where affiliatenetwork_id = ".$ms_db->GetSQLValueString($anid,"int");
		$found = $ms_db->getDataSingleRow($sql);
		return $found;
	}
	function getAffilateIdByName($affiliate_name)
	{
		global $ms_db;	

		$sql="select id from `".TABLE_PREFIX."affiliatenetwork` where affiliate_name like('%$affiliate_name%')";
		
		$affiliate_id = $ms_db->getDataSingleRecord($sql);
		
		return $affiliate_id;
	} 
// cmsnx_psf_test1_cmp_

}
?>