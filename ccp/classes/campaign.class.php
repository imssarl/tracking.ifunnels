<?php

class campaign

{

	function getAdById($aid)

	{

		global $ms_db;

		$sql = "SELECT * from  `".TABLE_PREFIX."ad` where id = ".$ms_db->GetSQLValueString($aid, "int");



		$data = $ms_db->getDataSingleRow($sql);

		return $data;	

	}

	function getAdsByCampaignId($cid)

	{

		global $ms_db;

		

		$sql = "Select a.*, n.affiliate_name, count(p.id) as pages from `".TABLE_PREFIX."ad` a

		LEFT JOIN  `".TABLE_PREFIX."affiliatenetwork` n ON n.id = a.affiliate_network 

		LEFT JOIN  `".TABLE_PREFIX."track` t ON t.ad_id = a.id 

		LEFT JOIN  `".TABLE_PREFIX."trackingpages` p ON p.ad_id = a.id 		 

		where campaign_id = $cid 

		GROUP BY a.id";

		$ad_rs = $ms_db->getRS($sql);

		//echo $sql;die();

		return $ad_rs;

	}

	function getCampaignList($sel=0, $flag='')

	{

		global $ms_db;

		if ($flag =='only') $flagsql = " WHERE id = ".$ms_db->GetSQLValueString($sel, "int");

		else $flagsql = "Where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

		$sql = "Select id,campaign_name from `".TABLE_PREFIX."campaign` $flagsql order by campaign_name";

		$an = $ms_db->getRS($sql);

		$listbox = "<select name='campaign_id'  id='campaign_id' onChange='copt(this.value)'>";

		if ($flag!='only')

		$listbox .= "<option value='-1' ><-- Select Campaign --></option>";
		$listbox .= "<option value='0' >ADD NEW CAMPAIGN</option>";		
		if ($an)

		{

			while($cam = $ms_db->getNextRow($an))

			{

				if ($cam["id"]==$sel) $selected = "selected"; else $selected = "";

					$listbox .= "<option value='".$cam["id"]."' $selected>".$cam["campaign_name"]."</option>";



			}

		}

		if ($flag!='only')		

		

		$listbox .= "</select>";

		return $listbox;

	}

	

	function insertCampaign()

	{

		global $ms_db, $track;

		$sql = "INSERT INTO `".TABLE_PREFIX."campaign` (   `campaign_name` ,  `created_date`,`user_id` )

		VALUES ("

		."'".$ms_db->GetSQLValueString($_POST["campaign_name"],"text")."',"

		."'".$ms_db->GetSQLValueString(date("Y-m-d H:i:s"),"date")."',"

		."'".$ms_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int")."')";

		$id = $ms_db->insert($sql);

		return $id;

		

	}



	function insertAd($cid)

	{

		global $ms_db, $track;

		$sql = "INSERT INTO `".TABLE_PREFIX."ad` ( `affiliate_network` , `merchant_link` ,  `campaign_id` , `ad_name` , ad_env, `created_date`,`user_id` )

		VALUES ("

		."'".$ms_db->GetSQLValueString($_POST["affiliate_network"],"text")."',"

		."'".$ms_db->GetSQLValueString($_POST["merchant_link"],"text")."',"

		."'".$ms_db->GetSQLValueString($cid,"text")."',"

		."'".$ms_db->GetSQLValueString($_POST["ad_name"],"text")."',"

		."'".$ms_db->GetSQLValueString(strtoupper($_POST["ad_env"]),"text")."',"		

		."'".$ms_db->GetSQLValueString(date("Y-m-d H:i:s"), "date")."',"

		."'".$ms_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'], "date")."')";

		$id = $ms_db->insert($sql);

		

		if ($_POST["ad_env"]=="C" || $_POST["ad_env"]=="c")

		$track->inserttrackdata("Contextual", "No-keyword", 0, $id);

		return $id;	

	}

	



	function updateCampaign($cid)

	{

		global $ms_db;

		

		$sql = "UPDATE `".TABLE_PREFIX."campaign` SET 

		`campaign_name` = '".$ms_db->GetSQLValueString($_POST["campaign_name"],"text")."'

		WHERE `id` = ".$ms_db->GetSQLValueString($cid,'int');

		$id = $ms_db->modify($sql);

	

		return $id;	

	}

	

	function updateAd($aid, $cid=0)

	{

		global $ms_db;

		

		$sql = "UPDATE `".TABLE_PREFIX."ad` SET 

		`affiliate_network` = '".$ms_db->GetSQLValueString($_POST["affiliate_network"],"text")."',

		`merchant_link` = '".$ms_db->GetSQLValueString($_POST["merchant_link"],"text")."',

		`campaign_id` = '".$ms_db->GetSQLValueString($cid ,"int")."',				

		`ad_env` = '".$ms_db->GetSQLValueString($_POST["ad_env"],"text")."',		

		`ad_name` = '".$ms_db->GetSQLValueString($_POST["ad_name"],"text")."'



		WHERE `id` = ".$ms_db->GetSQLValueString($aid,'int');

		$id = $ms_db->modify($sql);

	

		return $id;	

	}

	function deleteAd($id)

	{

		global $ms_db;



		$sql = "DELETE a, t, p FROM 

		".TABLE_PREFIX."ad a 

		LEFT JOIN ".TABLE_PREFIX."track t ON  a.id = t.ad_id 

		LEFT JOIN ".TABLE_PREFIX."trackingpages p ON a.id = p.ad_id 

		WHERE a.id = ".$ms_db->GetSQLValueString($id, "int");

		

		$id = $ms_db->modify($sql);

		return $id;			

	}

	

	function deleteCampaign($id)

	{

		global $ms_db;

	

		$sql = "DELETE c, a, t, p FROM 

		".TABLE_PREFIX."campaign c 

		LEFT JOIN ".TABLE_PREFIX."ad a ON c.id = a.campaign_id 

		LEFT JOIN ".TABLE_PREFIX."track t ON  a.id = t.ad_id 

		LEFT JOIN ".TABLE_PREFIX."trackingpages p ON a.id = p.ad_id 

		WHERE c.id = ".$ms_db->GetSQLValueString($id, "int");

		

		$id = $ms_db->modify($sql);

		return $id;			

	}

	

	function deleteCapaignMassLinking($id)

	{

		global $ms_db;

	

		$sql = "DELETE  FROM ".TABLE_PREFIX."campaign WHERE id = ".$ms_db->GetSQLValueString($id, "int");

		

		$id = $ms_db->modify($sql);

		return $id;			

	}

	

	function getCampaignById($id)

	{

		global $ms_db;

		$sql = "SELECT * from  `".TABLE_PREFIX."campaign` where id = ".$ms_db->GetSQLValueString($id, "int");

		$rs = $ms_db->getDataSingleRow($sql);

		return $rs;	

	}



	function affiliateselectbox($sel = 0)

	{

		global $ms_db;	



		$sql="select * from `".TABLE_PREFIX."affiliatenetwork` where user_id=-1 or user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

		

		$an = $ms_db->getRS($sql);

		$listbox = "<select name='affiliate_network'  id='affiliate_network' onChange='sopt(this.value)'>";

		$listbox .= "<option value='-1' ><-- Select Network --></option>";

		if ($an)

		{

			while($afn = $ms_db->getNextRow($an))

			{

				if ($afn["id"]==$sel) $selected = "selected"; else $selected = "";

				$listbox .= "<option value='".$afn["id"]."' $selected>".$afn["affiliate_name"]."</option>";

			}

		}

		

		$listbox .= "</select>";

		return $listbox;

	} 

	function affiliateselectbox_manualy($sel = 0)

    {

        global $ms_db;   



        $sql="select * from `".TABLE_PREFIX."affiliatenetwork` where user_id=-1 or user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];



        $an = $ms_db->getRS($sql);

        $listbox = "<select name='affiliate_network[]'  id='affiliate_network'>";

        $listbox .= "<option value='-1' ><-- Select Network --></option>";

        if ($an)

        {    echo '<script language="JavaScript"> var k=0; var seltext=new Array(); </script>';

            while($afn = $ms_db->getNextRow($an))

            {
				//chnages for display selected network for task106 13_nov
				// if ($afn["id"]==$sel) $selected = "selected"; else $selected = "";
				
                if ($afn["affiliate_name"]==$sel) $selected = "selected"; else $selected = "";

                $listbox .= "<option value='".$afn["affiliate_name"]."' $selected>".$afn["affiliate_name"]."</option>";



            echo '<script language="JavaScript"> seltext[k]="'.$afn["affiliate_name"].'"; k++;</script>';



            }

        }

        $listbox .= "</select>";

        return $listbox;

    }

	function affiliateselectbox_massImport($sel = 0)

	{

		global $ms_db;	



		$sql="select * from `".TABLE_PREFIX."affiliatenetwork` where user_id=-1 or user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

		

		$an = $ms_db->getRS($sql);

		$listbox = "<select name='affiliate_network'  id='affiliate_network' onChange='coptaff(this.value)'>";

		$listbox .= "<option value='-1' ><-- Select Network --></option>";

		if ($an)

		{

			while($afn = $ms_db->getNextRow($an))

			{

				if ($afn["id"]==$sel) $selected = "selected"; else $selected = "";

				$listbox .= "<option value='".$afn["id"]."' $selected>".$afn["affiliate_name"]."</option>";

			}

		}

		$listbox .= "<option value='0' >ADD NEW AFFILIATE</option>";		

		$listbox .= "</select>";

		return $listbox;

	} 

	function getCampaignIdByName($campaign_name)

	{

		global $ms_db;	



		$sql="select id from `".TABLE_PREFIX."campaign` where campaign_name = '$campaign_name' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

		

		$campaign_name = $ms_db->getDataSingleRecord($sql);

		

		return $campaign_name;

	} 

    

	

    



}





?>