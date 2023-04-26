<?php
class Projects


{


	function getNoofItems($id, $type)


	{


		global  $ms_db;





		if ($type == "K")


		{


			 $sql2 = "select count(distinct d.id) from `".TABLE_PREFIX."keyword_details_tb` d


			  JOIN `".TABLE_PREFIX."keyword_source_tb` s ON s.id = d.keyword_source_id


			  JOIN `".TABLE_PREFIX."keywords_projects_tb` k ON k.id = s.keyword_project_id


			AND k.project_id = $id";


		}


		else


		{


			 $sql2 = "select count(distinct d.id) from `".TABLE_PREFIX."article_source_tb` d


			 JOIN `".TABLE_PREFIX."articles_projects_tb` k ON k.id = d.article_project_id


			AND k.project_id = $id AND k.user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];			


		}


		$rec = $ms_db->getDataSingleRecord($sql2);


		if (!$rec)  $rec = 0;


		return $rec;	


	}


	function updateProjectConfig($id, $type)


	{


		global  $ms_db;





		if ($type == "K")


		{


			$sql = "update `".TABLE_PREFIX."keywords_projects_tb` SET 


			 generate_keywords = ".$_POST["genkeywordsp"]." ,


			 generate_period = ".$_POST["period"]."


			 where project_id = $id";


			 $sql2 = "select count(distinct d.id) from `".TABLE_PREFIX."keyword_details_tb` d


			 LEFT JOIN `".TABLE_PREFIX."keyword_source_tb` s ON s.id = d.keyword_source_id


			 LEFT JOIN `".TABLE_PREFIX."keywords_projects_tb` k ON k.id = s.keyword_project_id


			 where d.generated = 'N'


			AND k.project_id = $id AND k.user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];


		}


		else


		{


			$sql = "update  `".TABLE_PREFIX."articles_projects_tb` SET 


			generate_articles = ".$_POST["genkeywordsp"]." ,


			generate_period = ".$_POST["period"]."


			where project_id = $id";		


			 $sql2 = "select count(distinct d.id) from `".TABLE_PREFIX."article_source_tb` d


			 LEFT JOIN `".TABLE_PREFIX."articles_projects_tb` k ON k.id = d.article_project_id


			 where d.generated = 'N'


			AND k.project_id = $id AND k.user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];			


		}


		$data = $ms_db->modify($sql);





		$rec = $ms_db->getDataSingleRecord($sql2);


		return $rec;


	}


	function getProjectConfig($id, $type)


	{


		global  $ms_db;





		if ($type == "K")


		{


			$sql = "select id, generate_keywords, generate_period from `".TABLE_PREFIX."keywords_projects_tb` where project_id = $id and user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];


		}


		else


		{


			$sql = "select id, generate_articles, generate_period from `".TABLE_PREFIX."articles_projects_tb` where project_id = $id and user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];		


		}


		


		$data = $ms_db->getData($sql);


		return $data;


	}


	function insertProject()


	{


	global $ms_db;


	if (isset($_POST["site_id1"]) && $_POST["site_id1"] != 0)


	{


	$site_id =  $_POST["site_id1"];


	}


	else


	{


	$site_id =  $_POST["site_id2"];


	}


	


	$sql = "INSERT INTO `".TABLE_PREFIX."projects_tb` (  `site_id` , `created_date` , `status` , `type` , `status_comments`,`user_id` )


	VALUES ("


			."'".$ms_db->GetSQLValueString($site_id,"text")."',"


			."'".$ms_db->GetSQLValueString(date("Y-m-d"),"date")."',"


			."'".$ms_db->GetSQLValueString('I',"text")."',"


			."'".$ms_db->GetSQLValueString($_POST["projtype"],"text")."',"


			."'".$ms_db->GetSQLValueString('In Progress',"text")."',"
			."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX.'sessionuserid'],"int").
			"')" ;						


	


			$id = $ms_db->insert($sql);


			return $id;


	}


	function setProjectStatus($projectid,$status="",$why="-NA-")


	{


		global $ms_db;


		$sql = "Update ".TABLE_PREFIX."projects_tb set status = '".$status."' , status_comments = '".$why."' where id = ".$ms_db->GetSQLValueString($projectid,"int");


		$id = $ms_db->modify($sql);


	


		return $id;


	}


	function changeStatus($id,$status,$why)


	{


		global $ms_db;


		$sql = "Update  `".TABLE_PREFIX."projects_tb` set status = '".$ms_db->GetSQLValueString($status,"text")."' , status_comments = '".$why."' where id = ".$ms_db->GetSQLValueString($id,"int");


		$id = $ms_db->modify($sql);


		return $id;


	}


	function deleteProject($id)


	{


		global  $ms_db;





		$sql = "Select type from ".TABLE_PREFIX."projects_tb where id = ".$id;





		$type = $ms_db->getDataSingleRecord($sql);





		if ($type == "K")


		{





			$sql = "select k.id kid ,s.id sid from `".TABLE_PREFIX."keywords_projects_tb` k, `".TABLE_PREFIX."keyword_source_tb` s where k.user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."' and s.keyword_project_id = k.id and k.project_id = ".$id;





			$del = $ms_db->getDataSingleRow($sql);





			$sqlm = "delete from `".TABLE_PREFIX."mail_send_tb` where project_id = ".$ms_db->GetSQLValueString($id,"int");





			$idm = $ms_db->modify($sqlm);





			$sql1 = "delete from `".TABLE_PREFIX."projects_tb` where id = ".$ms_db->GetSQLValueString($id,"int");





			$id = $ms_db->modify($sql1);


			$sql2 = "delete from `".TABLE_PREFIX."keywords_projects_tb` where id = ".$del["kid"];





			$id = $ms_db->modify($sql2);


	


			$sqls = "select id from `".TABLE_PREFIX."keyword_source_tb` where keyword_project_id = ".$del["kid"];





			$sour = $ms_db->getRS($sqls);


	


			$sql3 = "delete from `".TABLE_PREFIX."keyword_source_tb` where keyword_project_id = ".$del["kid"];





			$id = $ms_db->modify($sql3);


	


			while($src = $ms_db->getNextRow($sour))


			{


				$sql4 = "delete from `".TABLE_PREFIX."keyword_details_tb` where keyword_source_id = ".$src["id"];


				$id = $ms_db->modify($sql4);


			}


		}


		else if ($type == "A")


		{


			$sql = "Select a.id from ".TABLE_PREFIX."articles_projects_tb a , ".TABLE_PREFIX."projects_tb p where p.user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."' and a.project_id = p.id  and p.id = ".$id;





			$apid = $ms_db->getDataSingleRecord($sql);





			$sqlm = "delete from `".TABLE_PREFIX."mail_send_tb` where project_id = ".$ms_db->GetSQLValueString($id,"int");





			$idm = $ms_db->modify($sqlm);


			


			$sql = "Delete from ".TABLE_PREFIX."article_source_tb where article_project_id = ".$apid;		





			$id1 = $ms_db->modify($sql);


			$sql = "Delete from ".TABLE_PREFIX."articles_projects_tb where project_id = ".$id;





			$id2 = $ms_db->modify($sql);


			$sql = "Delete from ".TABLE_PREFIX."projects_tb where id = ".$id;





			$id3 = $ms_db->modify($sql);





		}


}


	function duplicateProject($pid)


	{


		global  $ms_db;


//		$sql = "select k.id kid ,s.id sid from `".TABLE_PREFIX."keywords_projects_tb` k, `".TABLE_PREFIX."keyword_source_tb` s where s.keyword_project_id = k.id and k.project_id = ".$id;


//		$dup = $ms_db->getDataSingleRow($sql);





	$sql = "Select type from ".TABLE_PREFIX."projects_tb where id = ".$pid;


	$type = $ms_db->getDataSingleRecord($sql);





		$sqlp = "select * from `".TABLE_PREFIX."projects_tb` where id = ".$pid;


		$proj = $ms_db->getDataSingleRow($sqlp);





		$sql = "INSERT INTO `".TABLE_PREFIX."projects_tb` (  `site_id` , `created_date` , `status` , `type` , `status_comments`,`user_id` )


VALUES ("


		."'".$ms_db->GetSQLValueString($proj["site_id"],"int")."',"


		."'".$ms_db->GetSQLValueString($proj["created_date"],"date")."',"


		."'".$ms_db->GetSQLValueString($proj["status"],"text")."',"


		."'".$ms_db->GetSQLValueString($proj["type"],"text")."',"


		."'".$ms_db->GetSQLValueString($proj["status_comments"],"text")."',"
		."'".$ms_db->GetSQLValueString($proj["user_id"],"int").
		"')" ;						





		$npid = $ms_db->insert($sql);


		


	if ($type == "K")


	{


		$sqlk = "select * from `".TABLE_PREFIX."keywords_projects_tb` where project_id = ".$pid;


		$kewd = $ms_db->getDataSingleRow($sqlk);





		$sql = "INSERT INTO `".TABLE_PREFIX."keywords_projects_tb` (`project_id` , `mode` , `generate_all_keywords` , `generate_keywords` , `generate_random` , `generate_period` , `last_generated_date`,`user_id` )


VALUES ("





		."'".$ms_db->GetSQLValueString($npid,"int")."',"


		."'".$ms_db->GetSQLValueString($kewd["mode"],"text")."',"


		."'".$ms_db->GetSQLValueString($kewd["generate_all_keywords"],"text")."',"


		."'".$ms_db->GetSQLValueString($kewd["generate_keywords"],"int")."',"


		."'".$ms_db->GetSQLValueString($kewd["generate_random"],"text")."',"


		."'".$ms_db->GetSQLValueString($kewd["generate_period"],"text")."',"


		."'".$ms_db->GetSQLValueString($kewd["last_generated_date"],"date")."',"
		
		."'".$ms_db->GetSQLValueString($kewd["user_id"],"text").
		
		"')";





		$nkwdid = $ms_db->insert($sql);


		


				


		$sqls = "select * from `".TABLE_PREFIX."keyword_source_tb` where keyword_project_id = ".$kewd["id"];


		$sour = $ms_db->getRS($sqls);


		while($src = $ms_db->getNextRow($sour))


		{


			$sql = "INSERT INTO `".TABLE_PREFIX."keyword_source_tb` (  `keyword_project_id` , `source_type` , `source_file_name` , `source_date` )


VALUES ("


		."'".$ms_db->GetSQLValueString($nkwdid,"int")."',"


		."'".$ms_db->GetSQLValueString($src["source_type"],"text")."',"


		."'".$ms_db->GetSQLValueString($src["source_file_name"],"text")."',"


		."'".$ms_db->GetSQLValueString($src["source_date"],"date")."')";





		$nsrcid = $ms_db->insert($sql);


	


			$sqld = "select * from `".TABLE_PREFIX."keyword_details_tb` where keyword_source_id = ".$src["id"];


			$detl = $ms_db->getRS($sqld);


			while($dtl = $ms_db->getNextRow($detl))


			{


	


			$sql = "INSERT INTO `".TABLE_PREFIX."keyword_details_tb` ( `keyword_source_id` , `keyword_name` , `generated` , `generated_date` , `generated_file_name` ) VALUES ("


		."'".$ms_db->GetSQLValueString($nsrcid,"int")."',"


		."'".$ms_db->GetSQLValueString($dtl["keyword_name"],"text")."',"


		."'".$ms_db->GetSQLValueString($dtl["generated"],"text")."',"


		."'".$ms_db->GetSQLValueString($dtl["generated_date"],"date")."',"


		."'".$ms_db->GetSQLValueString($dtl["generated_file_name"],"text")."')";


	


				$id = $ms_db->insert($sql);


			}


		}


	}


	else if ($type == "A")


	{


		$sqla = "select * from `".TABLE_PREFIX."articles_projects_tb` where project_id = ".$pid;


		$atcl = $ms_db->getDataSingleRow($sqla);








		$sql = "INSERT INTO `".TABLE_PREFIX."articles_projects_tb` ( `project_id` , `mode` , `generate_articles` , `generate_period` , `will_keyword_generate` , `last_generated_date`,`user_id` )


VALUES ("





		."'".$ms_db->GetSQLValueString($npid,"int")."',"


		."'".$ms_db->GetSQLValueString($atcl["mode"],"text")."',"


		."'".$ms_db->GetSQLValueString($atcl["generate_articles"],"int")."',"


		."'".$ms_db->GetSQLValueString($atcl["generate_period"],"text")."',"


		."'".$ms_db->GetSQLValueString($atcl["will_keyword_generate"],"text")."',"		


		."'".$ms_db->GetSQLValueString($atcl["last_generated_date"],"date")."',"	
		
		."'".$ms_db->GetSQLValueString($atcl["user_id"],"int").
		
		"')";





		$nartid = $ms_db->insert($sql);





		$sqls = "select * from `".TABLE_PREFIX."article_source_tb` where article_project_id = ".$atcl["id"];


		$sour = $ms_db->getRS($sqls);


		while($src = $ms_db->getNextRow($sour))


		{


		$sql = "INSERT INTO `".TABLE_PREFIX."article_source_tb` ( `article_project_id`  , `source_file_name` , `source_date` , `generated` )


		VALUES ("


			."'".$ms_db->GetSQLValueString($nartid,"int")."',"


//			."'".$ms_db->GetSQLValueString($src["source_type"],"text")."',"


			."'".$ms_db->GetSQLValueString($src["source_file_name"],"text")."',"


			."'".$ms_db->GetSQLValueString($src["source_date"],"date")."',"


			."'".$ms_db->GetSQLValueString($src["generated"],"text")."')";			


			$nartsrcid = $ms_db->insert($sql);


		}


	}


		return $npid;


	}


	function showSiteList($selected, $posttype="")
	{
		global $ms_db;
		$sql = "select id,url from `".TABLE_PREFIX."portals_sites_tb` where is_under_portal != 'Y' and type = 'S' and user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."' order by url";
		$title_rs = $ms_db->getRS($sql);
		if ($_POST["type"]=="2" || $posttype == "2") $style = ""; else $style = 'style="display:none"';
				echo '<select name="site_id1" id="sitelist" '.$style.' onChange="getKwd(\'kwd\')">';
				echo '<option selected value="0"><-- Select Site --></option>';
		if ($title_rs)
		{
			while ($site = $ms_db->getNextRow($title_rs))
			{
				if($site['id']==$selected) $select = "selected"; else $select = "";		
				echo '<option value="'.$site['id'].'"'.$select.'>'.$site['url'].'</option>';
			}
		}
				echo '</select>';
	}


	


	function showPortalList($selected, $posttype="")


	{


		global $ms_db;


		$sql = "select id,url from `".TABLE_PREFIX."portals_sites_tb` where type = 'P' and user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."' order by url";


		$title_rs = $ms_db->getRS($sql);


	if ($_POST["type"]=="1" || $posttype == "1") $style = ""; else $style = 'style="display:none"';	


	echo '     <select name="packagelist" id="packagelist" onChange="JavaScript:PopulateCategory(document.forms[0].packagelist,document.getElementById(\'packagesitelist\'),-999)"  '.$style.'>  ';


	echo '        <option selected value="0"><-- Select Package --></option>';


		if ($title_rs)


		{


			while ($site = $ms_db->getNextRow($title_rs))


			{


				if($site['id']==$selected) $select = "selected"; else $select = "";


				echo '<option value="'.$site['id'].'"'.$select.'>'.$site['url'].'</option>';


			}


		}


	echo '		</select>';





	}


	


function isMailSend($pid,$lastdate)


{


	global $ms_db;


	$sql = "Select id from ".TABLE_PREFIX."mail_send_tb where project_id = ".$pid." and mail_sent = 'Y' and date = '".$ms_db->GetSQLValueString($lastdate,"date")."'";


	$found = $ms_db->getDataSingleRecord($sql);


	


	if ($found)


	return true;


	else 


	return false;


}


function sendMail($from_email, $from_name, $to_email, $to_name, $subject, $message )


{





	global $mail;


	$mail->From = $from_email;


	$mail->FromName = $from_name;


	$mail->ContentType = "text/plain";


	$mail->Priority = 1;


	$mail->Mailer = "mail";


	$mail->Subject = $subject;


	$mail->Body = $message;


	$mail->AddAddress($to_email,$to_name);


	$mail->Send();


	$mail->ClearAddresses();


}





function updateMailSend($projectid,$lastdate)


{


	global $ms_db;


	$sql = "INSERT INTO `".TABLE_PREFIX."mail_send_tb` (`project_id` , `date` , `mail_sent` )


	VALUES ("


		."'".$ms_db->GetSQLValueString($projectid,"int")."',"


		."'".$ms_db->GetSQLValueString($lastdate,"date")."',"


		."'".$ms_db->GetSQLValueString("Y","text")."')";





		$id = $ms_db->insert($sql);


		return $id;


}


function showTopOfPage()


{


	require_once("header.php");


	echo "<title>";


	echo SITE_TITLE;


	echo "</title>";


//	echo '<link href="stylesheets/style.css" rel="stylesheet" type="text/css">';


	$donotshowmwnu="yes";


	require_once("top.php");


	require_once("left.php");


	echo '


	<br>


	<table width = "100%" align = "center" class = "messwindow">


	<tr>


	<td width = "100%" align = "left">';


}


function showBottomOfPage()


{


	echo '


	</td>


	</tr>


	</table>


	<br>';


	require_once("right.php");


	require_once("bottom.php");


}





}


?>