<?php

//session_start();

require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/settings.class.php");
require_once("classes/sites.class.php");
require_once("classes/common.class.php");
require_once("classes/sites.steps.class.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");
require_once("classes/projects.class.php");
require_once("classes/feed.class.php");
require_once("classes/cnbprofile.class.php");
require_once("classes/cnbmanage_template.class.php");
require_once("classes/nvsbmanage_template.class.php");
require_once("classes/pclzip.lib.php");

//error_reporting(E_ALL);

$settings = new Settings();

$settings->checkSession();



$profile = new CNBProfile();

$feed = new xmlParser();

$project = new Projects();

$common = new Common();

$ms_db = new Database();

$ms_db->openDB();

$sites = new Sites();

$steps = new Steps();

$pg = new PSF_Pagination();

$sc = new psf_Search();

$templ=new mng_template();
$nvsb_template = new nvsb_template();

$pagefor="template";

if (isset($_GET['process']))

$process = $_GET['process'];

else if (isset($_POST['process']))

$process = $_POST['process'];

else $process="collect_template";



if (isset($_GET['msg']))

	$msg = $_GET['msg'];

else

	$msg = "";





if($process=="getprev")
{
	if (isset($_GET["temp_name"]) && $_GET["temp_name"] != "")
	{
		$temp_name = $_GET["temp_name"];
		$desc = $nvsb_template->gettemplate_decr($temp_name);
		$prev = "nvsb_templates/".$temp_name."/datas/desc/screenshot.jpg";
		$prev = '<image border="0" src="'.$prev.'">';
		echo "true"."!%#%!".$prev."!%#%!".$desc;
		die();
	}
}



if($process=="mismatch")
{
	if((isset($_GET['temp_del'])) && $_GET['temp_del']=="delete")
	{
		if(isset($_GET['temp_delete']))
		{
			$temp_del=$_GET['temp_delete'];
			foreach($temp_del as $val)
			{
				$check=$templ->deletetemplate($val);
			}
		}
	}
	if((isset($_GET['temp_ins'])) && $_GET['temp_ins']=="insert")
	{
		if(isset($_GET['notfound']))
		{
			$temp_ins=$_GET['notfound'];
			foreach($temp_ins as $val)
			{
				$check=$templ->insert_template($val);
			}
		}	
	}
	$process="collect_template";
	header("location: cnbmanage_template.php");
	die();
}

if(isset($_POST['process']) && $_POST['process']=="upload_template")
{	
	$template_name=$_FILES['upload_temp']['name'];
	

	$pos = strpos($template_name,".");

	$chk_format=substr($template_name,$pos+1);

	$template_name=substr($template_name,0,$pos);

	$template_name=trim($template_name);



	if($chk_format=="zip" || $chk_format=="ZIP")
	{

		if($_FILES['upload_temp']['error']==0 && $_FILES['upload_temp']['size'] > 0)
		{
			$archive = new PclZip($_FILES['upload_temp']['tmp_name']);

			$upfiles = $archive->listContent();

			$template_name = "";

			foreach($upfiles as $upar)

			{

				$up = $upar["stored_filename"];

				$pos = strpos($up,"/");

				$template_name=substr($up,0,$pos);

				$template_name=trim($template_name);

				$mmsg = "";

				if ($template_name != "")

				{

					if (file_exists("template/".$template_name))
					{
						$template_name = "";
						$mmsg = "This template is already exists";
					}

					break;

				}

			}

			if ($template_name != "")
			{

				if ($archive->extract("template/") != 0)
				{
					$templ->insert_template($template_name);
				}
				else
				{	
					$msg= "Unable to extract ".$template_name."Zip please upload using FTP or try again later";	
				}

			}

			else

			{

				if ($mmsg=="")

				$msg= "The zip file you uploaded has bad template format, please check it";	

				else

				$msg = $mmsg;

			}



		}

		else

		{	

		$msg="Unable to ulpload ".$template_name."Zip please upload using FTP or try again later";	

				

		}





 	}

 	else

 	{

 		 $msg="Please select zip format file";	

 	} 



	$_SESSION['upload_template']="";

	unset($_SESSION['upload_template']);	

	if($msg!="")

	{

		$process="new_template";

	}

	else

	{

		$process="collect_template";

	}



}

else if($process=="delete")
{
	$del = $templ->deleteTemplateById($_GET["tid"]);
	if ($del)
	{
		$delfol = $templ->deleteFolder(trim($_GET["tname"]));
		if($delfol)
		{
			header("location: cnbmanage_template.php?msg=Template ".$_GET["tname"]." has been deleted");
		}
		else
		{
			header("location: cnbmanage_template.php?msg=Due to some problem, contents of template ".$_GET["tname"]." are still exist,<BR>Please remove them manually");
		}
	}
	else
	{
		header("location: cnbmanage_template.php?msg=Problem in deleting ".$_GET["tname"]." template details");
	}
}

else

{

		if(isset($_SESSION['upload_template']) && $_SESSION['upload_template']=="upload_sucess" && (!isset($_GET['process']) && $_GET['process']!="collect_template"))
		{

			$_SESSION['upload_template']="";

			unset($_SESSION['upload_template']);

			$msg="The script is unable to upload the Zip please uplaod manualy";

			$process="new_template";

		}

}


if($process=="collect_template")
{

$template=$templ->listtemplate();

if($template)
{
	$tem = array();
	foreach($template as $val)
	{
		 $tem[]=trim($val['name']);
	}
	$count=count($tem);
	if($count)
	{	
		for($i=0;$i<=$count-1;$i++)
		{	
			$check=$templ->Is_template_exist($tem[$i],0);
			if($check)
			{	
				 $temp_found[]=$tem[$i];
			}
			else
			{	
				if (is_dir("template/".$tem[$i]))
				{
					$notfound[]=$tem[$i];
				}
			}	
		}	
	}
	else
	{
		$msg = "No folder in template";
	}
	
	$all_temp = $templ->getAlltemplate();
	
	if($all_temp)
	{

	  while ($t = $ms_db->getNextRow($all_temp))
	  {

		$check=$templ->checkfordelete($t['temp_name'],$tem);
		if($check)
		{
		}
		else
		{
			$temp_delete[]=$t['temp_name'];
		}
	  }

	}		

}

else

{

	$msg =  "NO Template found";

}



	$search_sql = "";

	$sql = "select count(*) from `".TABLE_PREFIX."templates` where user_id=-1 OR user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];

	$totalrecords = $ms_db->getDataSingleRecord($sql);

	if($totalrecords !== false && $totalrecords > 0)

	{

	

		$pg->setPagination($totalrecords);						

		$order_sql = $sc->getOrderSql(array("id","temp_name"),"id");

		

		$sql = "SELECT *  FROM ".TABLE_PREFIX."templates where user_id=-1 and user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid']." 

		".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;

		$tamplate_rs = $ms_db->getRS($sql);

	}

	else

	{

		$totalrecords = 0;

		$tamplate_rs = false;

	}

}





?>



<?php

	//PHP code will come here

?>



<?php require_once("header.php"); ?>



<title>

<?php echo SITE_TITLE; ?>

</title>



<script language="javascript">

	// Javascript code will come here

function validat()

{

var error="There are following errors:\n";

var flag=true;	



if(document.new_template.upload_temp.value == "")

	{	

		error+="- Please select template ZIP file from the disk\n";

		flag = false;

	}



if(flag==false)

	{

		alert(error);

		return false;	

	}

	else

	{

	

	return true;	

	}

}

function deltempl(tid, tname)

{

	if(confirm("Are you sure to delete template "+tname+"?"))

	{

		location = 'cnbmanage_template.php?process=delete&tid='+tid+'&tname='+tname;

	}

}

</script>



<?php  $menutype="cnb"; require_once("top.php"); ?>

<?php require_once("left.php"); ?>

<div align="left">&nbsp;<a class="general" href="index.php">Home</a> >> <a class="general" href="cnbsites.php">Creative Niche Builder</a> >> <?php if($process=="collect_template"){?>Manage Template <?php }?> <?php if($process=="new_template"){?><a class="general" href="cnbmanage_template.php?process=collect_template">Manage Template</a> >> Upload New Template<?php }?></div>

<br><br>

<table width="80%"  border="0" cellspacing="0" cellpadding="0" align="center">

	<tr>

		<td colspan="2" align="center" class="heading"> 

		<a href="cnbsites.php" class="menu">Create Site</a> | <a class="menu"  href="cnbprofile.php">Profiles</a> | <a class="menu" href="cnbmanage_template.php?process=collect_template">Manage Templates</a> | <a class="menu" href="cnbhistory.php">History</a> </td>

	</tr>

</table>	





<table width="100%"  border="0" cellspacing="0" cellpadding="0">



	<tr>

      <td>

<br>    

	<div class="message">

	<?php	if (isset($_GET["msg"]) && $_GET["msg"] != "") echo $_GET["msg"];?>&nbsp;

	</div>

  <?php if($process=="collect_template"){ ?> <div align="center"> 

  <a class="general" href="cnbmanage_template.php?process=new_template"> Upload New Template</a></div> <?php }?>



<?php if($process=="collect_template")

{

?>

<div align="center">

<?php if($totalrecords > 0) { 

echo '<br>';

					 $pg->showPagination();

		 } ?>	 

</div>

<br>     





 <table width="80%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary2">

		<TR >

		<Th><a class="menu" href="?sort=id">ID</a></Th><Th><a class="menu" href="?sort=temp_name">Template Name</a></Th><Th>Description</Th><th>Screenshot</th></TD><th>Installed on URL(s)</th><th></th>

		</TR>

<?php if ($tamplate_rs)

		{

		$tblmat=0;

		$i=0;

	//	die();

			while($check = $ms_db->getNextRow($tamplate_rs))

			{

			

			//$check=$templ->Is_template_exist($temp_found[$i],1);

			

		if(in_array($check["temp_name"], $temp_found))

		{?>		

<tr class='<?php echo ($tblmat++%2) ? "tablematter1" : "tablematter2" ?>'>

<TD align="center"><?php echo $check["id"] ?></TD>

<TD align="center"><?php echo $check['temp_name']?></TD>

<?php

$desc=$templ->gettemplate_decr($check['temp_name']);

if($desc==false)

{

$desc="No description found for this template";

}

?>

<TD align="left"><?php echo wordwrap($desc,65,"<br>",1); ?></TD>

<TD align="center" height="260" width="210"> 

<?php

$imagepath= "template/".$check['temp_name']."/datas/desc/screenshot.jpg";

if(file_exists($imagepath))

{?>

<img src="template/<?php echo $check['temp_name']?>/datas/desc/screenshot.jpg"  width="200">



<?php }

 else

 {

 echo "No screenshot exists for this template";

 }

 ?>

 </TD>

<TD align="left">

	<?php

	$urls = $templ->getURLByTemplateId($check["id"]);
	
	foreach($urls as $showurls)
	{
		echo "<a target='_blank' href='".$showurls."'>".$showurls."</a><br>";
	}

	//$showurls = implode("<BR>",$urls);
//print_r($urls);
//	

	?>

</TD>

<td width="20px">

	<img src="images/delete.png" onClick="deltempl(<?php echo $check["id"]; ?>,'<?php echo trim($check['temp_name']);?>')" title="Delete Template">

</td>

</tr>		



<?php   

$i++; } } ?>

<?php }

     else

		{

			echo 	'<tr><td colspan="7" align="center">No Template Exists</td></tr>';

		}

			echo '<TR "><td colspan="7" class="heading">&nbsp;</td></tr>';

		} ?>	

</table>

	

	

	

<?php  if($temp_delete || $notfound )

{?>

<br>	

<form name="templatedel" method="GET" action="cnbmanage_template.php">

<table cellpadding="0" cellspacing="0" border="0" class="summary2" align="center" width="80%">



<tr>

<TD  height="5" class="heading" align="left" width="15px"> ID</TD>

<TD  height="5" class="heading"> Mismatch Entries</TD>

<td align="right"  height="5" class="heading" width="20px"> Select</td>

</tr>

<?php if($temp_delete)

{?>



<tr>



<TD  colspan="3"><b>  Templates not found  do you want to delete these entries from data table..</b></TD></tr>



<?php

 $i=1;

 foreach($temp_delete as $val)

	{ ?>

<tr class='<?php echo ($tblmat++%2) ? "tablematter1" : "tablematter2" ?>'>

<td align="center"> <?php echo $i; ?> </php></td>

<td align="left" valign="top"> <?php echo $val;?> </td>

<td align="center" valign="top"> <input type="checkbox" name="temp_delete[]" value="<?php echo $val;?>"></td>

</tr>



 <?php $i++;  } 

 echo '<input type="hidden" name="temp_del" value="delete">';

 }

if($notfound)

{

?>

<tr ><TD height="1" colspan="3" class="heading"></TD></tr>

<tr><TD  colspan="3"> <b> New Templates found  do you want to insert these entries in data table</b> </TD></tr>



<?php 

 $j=1;

foreach($notfound as $val)

{ ?>

<tr class='<?php echo ($tblmat++%2) ? "tablematter1" : "tablematter2" ?>'>

<td align="center"> <?php echo $j; ?> </td>

<td align="left" valign="top"> <?php echo $val;?> </td>

<td align="center" valign="top"> <input type="checkbox" name="notfound[]" value="<?php echo $val;?>"></td>

</tr>

 <?php  $j++; } 

 echo '<input type="hidden" name="temp_ins" value="insert">';

 

 } ?>



<input type="hidden" name="process" value="mismatch">

<input type="hidden" name="url" value="">

<tr><TD colspan="3" height="5" class="heading"><input type="submit" value="Update Entries" id="yes"></TD></tr>

</table>

</form>

<br>

<?php }

if($process=="new_template") 

{

?>





<form name="new_template" method="post" enctype="multipart/form-data" action="cnbmanage_template.php">

<table cellpadding="0" cellspacing="0" border="0"  height="200" align="center" width="500" class="inputform">

<?php

if($msg)

{?>

<tr class="heading">

<td class="message" nowrap="true" align="center" colspan="2" height="5"> <?php  echo $msg; ?> </td>

</tr>

<br>

<?php }?>

<tr> <TD colspan="2" height="5" class="heading" width="500">Upload Templates</TD></tr>	

<tr align="center">

<td align="right" width="200"> &nbsp;Upload Template&nbsp;</td>

<TD align="left"> 

<input type="file" name="upload_temp" size="70">

</TD></tr>

<tr><td colspan="2" align="center" class="message">

Template should have a "folder container" which includes "datas" folder and other files with "desc" folder to be found inside the "datas" folder with "description.txt" and "screenshot.jpg" inside 

</td></tr>

<?php  $_SESSION['upload_template']="upload_sucess"; ?>

<input type="hidden" name="process" value="upload_template">

<tr > <TD colspan="2" height="5" class="heading" width="500"><input type="submit" value="Upload Template" id="yes" onclick="return validat();"></TD></tr>	

</table>

</form>

<br>

<?php } ?>

<br>

<?php require_once("right.php"); ?>

<?php require_once("bottom.php"); ?>