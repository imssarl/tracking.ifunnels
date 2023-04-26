<?php
session_start();
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/settings.class.php");
require_once("classes/common.class.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");

require_once("classes/campaign.class.php");
require_once("classes/tracking.class.php");
require_once("classes/en_decode.class.php");	
	
$endec=new encode_decode();

$campaign= new campaign();
$track=new track();

$settings = new Settings();
$common = new Common();
$settings->checkSession();
$ms_db = new Database();
$ms_db->openDB();

$pg = new PSF_Pagination();
$sc = new psf_Search();
$pagefor = "campaign";
//////////////////////////////////
if (isset($_POST['process']))
{
	$process = $_POST['process'];
}
else if (isset($_GET['process']))
{
	$process = $_GET['process'];
}
else
{
	$process = "manage";
}
if (isset($_GET["page"]))
{
	$page = $_GET["page"];
}
else if  (isset($_POST["page"]))
{
	$page = $_POST["page"];
}
else
{
	$page = 1;
}
if (isset($_POST['msg']))
{
	$msg = $_POST['msg'];
}
else if (isset($_GET['msg']))
{
	$msg = $_GET['msg'];
}
else
{
	$msg = "";
}
// if (isset($_POST['msg1']))
// {
// 	$msg1 = $_POST['msg1'];
// }
// else if (isset($_GET['msg1']))
// {
// 	$msg1 = $_GET['msg1'];
// }
// else
// {
// 	$msg1 = "";
// }

	$flag = '';		
if (isset($_POST["campaignform"]) && $_POST["campaignform"] == "yes")
{
//print_r($_POST);die();
	if (isset($_POST["affiliate_network"]) && $_POST["affiliate_network"] != 1)
	{

		$_POST['merchant_link'] = trim(str_replace(array("\\\\","\\"),"/",$_POST['merchant_link']));
		if (substr(trim($_POST['merchant_link']),0,7)!="http://") $_POST['merchant_link']="http://".$_POST['merchant_link'];
/*		if (substr($_POST['merchant_link'],strlen($_POST['merchant_link'])-1,1)!="/") $_POST['merchant_link'] .= "/";*/

		$var = parse_url($_POST['merchant_link']);
				
		if(!isset($var['path']) && !isset($var['query']))
		{ 
			$_POST['merchant_link'].="/";
		}
		elseif(isset($var['path']) && !isset($var['query']))
		{
			$temp = explode("/",$var['path']);
	
			if(isset($temp[count($temp)-1]) && $temp[count($temp)-1]!="")
			{
				$pos = strpos($temp[count($temp)-1],".");
				
				if($pos===false)
				{
					$_POST['merchant_link'].="/";
				}
			}
		}
	}

	if ($process=="new")
	{
		if (isset($_POST["campaign_id"]) && $_POST["campaign_id"] > 0)
			$cid = $_POST["campaign_id"];
		else
			$cid = $campaign->insertCampaign();
		$id = $campaign->insertAd($cid);
		
		
		if ($id)
		{
			header("location: campaign.php?process=manage&msg=Ad $id has been added");
			exit;
		}
		else
		{
			$data = $common->getPostData();
			$msg = "Error in saving data";
		}
	}
	else if ($process=="editad")
	{
		if (isset($_POST["campaign_id"]) && $_POST["campaign_id"] > 0)
			$cid = $_POST["campaign_id"];
		else
			$cid = $campaign->insertCampaign();

		$ok = $campaign->updateAd($_POST["aid"], $cid);
		header("location: campaign.php?process=manage&msg=Ad has been modified");
		exit;
	}
	else if ($process=="edit")
	{
		$ok = $campaign->updateCampaign($_POST["cid"]);
		header("location: campaign.php?process=manage&msg=Campaign has been modified");
		exit;
	}
	
}
else if ($process=="editad")
{
	$flag = '';
	$data = $campaign->getAdById($_GET["aid"]);
}
else if ($process=="edit")
{
	$data = $campaign->getCampaignById($_GET["cid"]);
}
else if ($process=="new")
{
	if(isset($_GET["cid"]) && $_GET["cid"]>0)
	{
		$data["campaign_id"] = $_GET["cid"];
		$flag = 'only';
	}
}

else if ($process=="deletead")
{
	$ok = $campaign->deleteAd($_GET["id"]);
	header("location: campaign.php?process=manage&page=".$_GET["page"]."&msg=Ad ".$_GET["id"]." has been deleted");
	exit;
}
else if ($process=="deletecp")
{
	$ok = $campaign->deleteCampaign($_GET["id"]);
	header("location: campaign.php?process=manage&page=".$_GET["page"]."&msg=Campaign ".$_GET["id"]." has been deleted");
	exit;
}

if ($process == "manage")
{
	$sql = "select count(*) from `".TABLE_PREFIX."campaign` c where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

	$totalrecords = $ms_db->getDataSingleRecord($sql);
	if ($totalrecords>0)
	{
		$pg->setPagination($totalrecords);
		
		$order_sql = $sc->getOrderSql(array("id","campaign_name","noofads","created_date"),"id");
		
		$sql= "SELECT c.*, count(a.id) as noofads  from `".TABLE_PREFIX."campaign` c  LEFT JOIN `".TABLE_PREFIX."ad` a ON a.campaign_id = c.id where c.user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid']." Group BY c.id 
		".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;
		$campaign_rs = $ms_db->getRS($sql);
	}
	else
	{
		$totalrecords = 0;
		$campaign_rs = false;	
	}
}
	
?>

<?php require_once("header.php"); ?>

<title>
<?php echo SITE_TITLE; ?>
</title>
<script language="javascript">
function show_getlinkcode()
{
document.getElementById("getlinkcode").style.display="inline";
}
function hide_getlinkcode()
{
document.getElementById("getlinkcode").style.display="none";
}

function showmerchantlink()
{
	if(document.newcampaign.affiliate_network.value==1)
	{
		document.getElementById("showmerchantlink").style.display="none";
		document.newcampaign.merchant_link.value="Own network";
	}
	else{
		document.getElementById("showmerchantlink").style.display="inline";
	}
}



function showcode(id)
{
	openwindow= window.open ("campaigngetcode.php?aid="+id, "GETCODE",
		"'status=0,scrollbars=1',width=650,height=500,resizable=1");
	
	openwindow.moveTo(50,50);
}

function chkMainForm(frm)
{
	var mss = "";
	
	if (frm.affiliate_network.value=="")
	{
		mss += "Please enter affiliate network.\n";
	}
	
	if (frm.campaign_id==0 && frm.campaign_name.value=="")
	{
		mss += "Enter your Campaign name.\n";
	}
	else if(frm.campaign_id==-1)
	{
		mss += "Enter select Campaign name.\n";
	}
	
	if (frm.ad_name.value=="")
	{
		mss += "Enter your Ad name.\n";
	}
	if (frm.affiliate_network.value!="Own")
	{
		if (frm.merchant_link.value=="")
		{
			mss += "Enter the link for the merchant you wish to track.\n";
		}
	}
	if (mss.length>0)
	{
		alert(mss);
		return false;
	}
	else
	{
		return true;
	}
}

function chkuploadForm(frm)
{
	var mss = "";
	
	if (frm.affiliate_network.value=="")
	{
		mss += "Please enter affiliate network.\n";
	}
	
	if (frm.csvfile.value=="")
	{
		mss += "Please enter any file to upload.\n";
	}
	if (mss.length>0)
	{
		alert(mss);
		return false;
	}
	else
	{
		return true;
	}
}

function disableMyButtons(form)
{
form.nobutton.disabled = true;
form.yesbutton.disabled = true;
}
var hch = '';
function sopt(nw)
{
	var ml = document.getElementById("merchant_link");
	if(nw==1)
	{
		hch = ml.value;
		ml.value='No-link';
		ml.readOnly = true;
	}
	else
	{
		if(hch!='')
			ml.value=hch;
		ml.readOnly = false;
	}
}
function copt(cam)
{
	if(cam==0)
		document.getElementById("campaign_name").className = 'showil';
	else
		document.getElementById("campaign_name").className = 'noshow';
}
function hndlsr(tid)
{
	var ad = document.getElementById('ad'+tid);
	var cp = document.getElementById('row'+tid);	
	if (ad.className == 'show')
	{
		ad.className = 'noshow';
		cp.className = 'backcolor1';
	}
	else 
	{
		ad.className = 'show';
		cp.className = 'backcolor3';
	}
		
}
</script>

<?php require_once("top.php"); ?>

<?php require_once("left.php"); ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
   
    <tr>
<tr>
	<td align="left">
<?php
$bcrumbhome = '&nbsp;<a class="general" href="../index.php">Home</a>';

if ($process=="manage")
{
	$breadmanage = " >> Manage Campaign";
}
else
{
	$breadmanage = ' >> <a class="general" href="campaign.php">Manage Campaign</a>';
}

if ($process=="new")
{
	$breadprocess = ' >> New Campaign';
}
else if ($process=="edit")
{
	$breadprocess = ' >> Edit Campaign';
}
else if ($process == "confirmdelete")
{
	$breadprocess = ' >> Delete Campaign';
}
else if ($process == "upload")
{
	$breadprocess = ' >> Upload Sales Data';
}
 
 
	
	
	
echo 	$bcrumbhome.$breadmanage.$breadprocess;
?>
	<br>
	</td>
</tr>
<tr>
<td  align="center"> 
<?php
// 	if(isset($msg1) && $msg1!="")
// 	{
// echo $msg1."<br>";
// 		$temp2 = explode("####",$msg1);
// print_r($temp2);
// 		echo "<span class='legcol3'>".$temp2[0]."</span>";
// 		echo $temp2[1];
// 	}
// 	else
	//{
	 	echo $msg;
	//}
?>
<br><br>
</td>
</tr>

<tr>
	<td align="center"> <!-- mail block starts -->
<?php  if ($process=="new" || $process == "editad") { ?>
<br>
	    
<form name="newcampaign" method="post" action="campaign.php" onSubmit="return chkMainForm(this)">
<table width="90%"  border="0" cellspacing="0" cellpadding="0" class="inputform">
          <tr>
            <td align="left" width="40%" class="heading" colspan="2"><?php echo ($process=="new") ? "New" : "Edit";?> Campaign</td>
			</tr>
			<tr>
				<TD align="right">Affiliate Network:</TD>
				<TD align="left">
					<?php echo $campaign->affiliateselectbox($data["affiliate_network"]); ?>
				</TD>
			</tr>

			
			<tr>
				<TD align="right" width="50%">Campaign Name:</TD>
				<TD width="50%" align="left">
				<?php echo $campaign->getCampaignList($data["campaign_id"], $flag); ?>
				
				<input class='noshow' type="text" name="campaign_name" id="campaign_name" value="<?php echo $data["campaign_name"]; ?>" size="20"></TD>
			</tr>
<tr><td colspan="2" height="8px"></td></tr>
			<tr>
				<TD align="right">Ad Name:</TD>
				<TD align="left"><input type="text" name="ad_name" id="ad_name" value="<?php echo $data["ad_name"]; ?>" size="20"></TD>
			</tr>
			<tr>
				<TD align="right" width="50%">Merchant Link:</TD>
				<TD width="50%" align="left"><input type="text" name="merchant_link" id="merchant_link" value="<?php echo $data["merchant_link"]; ?>" size="30"></TD>
			</tr>
			<tr>
				<TD align="right" width="50%">Environment:</TD>
				<TD width="50%" align="left">
				<input type="radio" name="ad_env" value="K" <?php if($data["ad_env"]=="K" || !isset($data["ad_name"])) echo 'checked="checked"'; ?>> Keyword related (search engine, PPC)<br>
				<input type="radio" name="ad_env" value="C" <?php if($data["ad_env"]=="C") echo 'checked="checked"'; ?>>	Contextual (email, solo ads, newsletter)
				</TD>
			</tr>

           <tr>
		   <td colspan="2" align="center" class="heading">
              <div align="center">
                <input type="submit" name="Submit" value="Save">
              </div>
                <input type="hidden" name="process" value="<?php echo $process ?>">
                <input type="hidden" name="aid" value="<?php echo $data["id"] ?>">
                <input type="hidden" name="campaignform" value="yes">				
			</td>
            </tr>
             
        </table> </form>	
<?php }
		else if (substr($process,0,7)=="edit")
		{	?>		

<form name="newcampaign" method="post" action="campaign.php" onSubmit="return chkMainForm(this)">
<table width="90%"  border="0" cellspacing="0" cellpadding="0" class="inputform">
          <tr>
            <td align="left" width="40%" class="heading" colspan="2">Edit Campaign</td>
			</tr>

			<tr>
				<TD align="right" width="50%">Campaign Name:</TD>
				<TD width="50%" align="left">
				<input type="text" name="campaign_name" id="campaign_name" value="<?php echo $data["campaign_name"]; ?>" size="20"></TD>
			</tr>


           <tr>
		   <td colspan="2" align="center" class="heading">
              <div align="center">
                <input type="submit" name="Submit" value="Save">
              </div>
                <input type="hidden" name="process" value="<?php echo $process ?>">
                <input type="hidden" name="cid" value="<?php echo $data["id"] ?>">
                <input type="hidden" name="campaignform" value="yes">				
			</td>
            </tr>
             
        </table> </form>	

<?php }
		else if (substr($process,0,7)=="confirm")
		{	?>		
			<table class="messagebox" height="300" align="center" border="0">
			<tr align="center">
			<td align="center" valign="middle">
			<form method="get" action="" onSubmit="disableMyButtons(this)">
			<?php if ($process == "confirmdeletead") { ?>
			Are you sure to delete this  Ad?<br>
			<input type="hidden" name="process" value="deletead">
			<?php }  ?>
			<?php if ($process == "confirmdelete") { ?>
			The process will also delete all the ads and tracking information under this campaign<br>
			Are you sure to delete this campaign?<br>
			<input type="hidden" name="process" value="deletecp">
			<?php }  ?>
			
			<input type="hidden" name="confirm" value="yes">
			<input type="hidden" name="campaign_id" value="<?php echo $_GET["id"] ?>">
			<input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
			<input type="hidden" name="block_id" value="<?php echo $_GET["block_id"] ?>">			
			<input type="hidden" name="page" value="<?php echo $page ?>">
			<input name="yesbutton" type="submit" value="Yes">&nbsp;
			<input name="nobutton" type="button" value="No" onClick="javascript: location='campaign.php?msg=Operation cencelled&page=<?php echo $page ?>&block_id=<?php echo $_GET["block_id"] ?>'">
			</form>
			</td>
			</tr>
			</table>		
<?php }
		else if ($process=="manage")
		{ ?>
<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
	<tr>
		<TD valign = "top" align="center" class="heading"> <a  class="menu" href = "?process=new">Create new campaign</a>  |  <a  class="menu" href = "affiliatenetworks.php">Manage Affiliate Networks</a>  |  <a class="menu" href = "ctmsalesdata.php">Sales Data</a>  |  <a class="menu" href = "remotefileeditor.php?process=selrmtfil">Remote File Editor</a></TD>
	</tr>

	<tr>
					<td colspan="12">
						<?php $pg->showPagination(); ?>
					</td>
				</tr>
</table>
<br>
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
<tr  class="tableheading">
<th width="16">&nbsp;<a title = "Sort" class = "menu" href="?sort=id">ID</a>&nbsp;</th>
<th><a title = "Sort" class = "menu" href="?sort=campaign_name">Campaign Name</a></th>
<th><a title = "Sort" class = "menu" href="?sort=noofads">No of ads</a></th>
<th><a title = "Sort" class = "menu" href="?sort=created_date">Date Created</a></th>
<th colspan="3"></th>
</tr>
<?php
if ($campaign_rs)
{
 
	$tblmat=0;
	while($camp_data = $ms_db->getNextRow($campaign_rs))
	{
		$cid = $camp_data["id"];
?>
<tr  id="row<?php echo $cid ?>"  class='backcolor1'  >
		<td align="center" width="20px"><?php echo $cid ?></td>
		<td align="left"><?php echo $camp_data["campaign_name"] ?></td>
		<td align="center" >
		<a href="#" onClick="hndlsr(<?php echo $cid ?>); return false;">
		<?php echo $camp_data["noofads"] ?>
		</a>
		</td>
		<td align="center"><?php echo $camp_data["created_date"] ?></td>
		<td width="16">
		<a href="?process=new&cid=<?php echo $cid?>&page=<?php echo $page?>">
		<img src="images/add.png" border="0" title="Add" style="cursor:pointer">
		</a>
		</td>
		<td width="16">
		<a href="?process=edit&cid=<?php echo $cid?>&page=<?php echo $page?>">
		<img src="images/edit.png" border="0" title="Edit" style="cursor:pointer">
		</a>
		</td>
		<td width="16">
		<a href="?process=confirmdelete&id=<?php echo $cid?>&page=<?php echo $page?>">
		<img src="images/delete.png" border="0" title="Delete" style="cursor:pointer">
		</a>
		</td>
</tr>
<tr>
<td colspan="7">
<div class="noshow" id="ad<?php echo $cid ?>">
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
<tr  class="tableheading">
<th width="16">&nbsp;ID&nbsp;</th>
<th>Ad Name</th>
<th>Affiliate Network</th>
<th>Ad Enviroment</th>
<th>Merchant link</th>
<th>Date</th>
<th colspan="6"></th>
</tr>
<?php
	$ad_rs = $campaign->getAdsByCampaignId($cid);
if ($ad_rs)
{
 
	$tblmat=0;
	while($ad_data = $ms_db->getNextRow($ad_rs))
	{
		$id = $ad_data["id"];
?>	
<tr  id="row<?php echo $id ?>"  class='<?php echo ($tblmat++%2) ? "backcolor1" : "backcolor2" ?>' >
		<td align="center"><?php echo $id ?></td>
		<td align="left"><?php echo $ad_data["ad_name"] ?></td>
		<td align="left"><?php echo $ad_data["affiliate_name"] ?></td>
		<td align="left"><?php echo ($ad_data["ad_env"]=='K' || $ad_data["ad_env"]=='k')?'Keyword':'Contextual'; ?></td>		
		<td align="left"><?php echo $ad_data["merchant_link"] ?></td>
		<td align="center"><?php echo $ad_data["created_date"] ?></td>
		<td width="16">
		<a href="?process=editad&aid=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/edit.png" border="0" title="Edit" style="cursor:pointer">
		</a>
		</td>
		<td width="16">
		<a href="?process=confirmdeletead&id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/delete.png" border="0" title="Delete" style="cursor:pointer">
		</a>
		</td>
		<td width="16">
		<img src="images/getcode.gif" border="0" title="Get code" style="cursor:pointer" onclick="showcode('<?php echo $endec->encode($id)?>')">
		</td>
		<td width="16">
		<?php if ($ad_data["affiliate_network"]!=1) { ?>
		<a href="tracking.php?process=new&aid=<?php echo $id?>">
		<img src="images/link.png" border="0" title="Create tracking page" style="cursor:pointer">
		</a>
		<?php }	else { ?>
			<img src="images/denied.png" border="0" title="Can not create tracking page" style="cursor:pointer">
		<?php } ?>				
		</td>
		
		<td width="16">
		<?php if ($ad_data["affiliate_network"]!=1  && $ad_data["pages"]>0) { ?>
		<a href="tracking.php?process=manage&apid=<?php echo $id?>">
		<img src="images/link.png" border="0" title="Edit tracking page" style="cursor:pointer">
		</a>
		<?php }	else { ?>
			<img src="images/denied.png" border="0" title="No tracking page is created" style="cursor:pointer">
		<?php } ?>				
		</td>
		<td width="16">
		<a href="report.php?aid=<?php echo $id?>&cpage=<?php echo $page ?>">
		<img src="images/report.png" border="0" title="Get Campaign Report" style="cursor:pointer">
		</a>
		</td>
	</tr>
<?php	}
}	  
else
{
echo "<tr><td align='center' colspan='12'>No Ad Found</td></tr>";
}
?>	  
</table>
</div>
</td>
</tr>	
<?php	}

}
else
{
echo "<tr><td align='center' colspan='7'>No Campaign Found</td></tr>";
}
?>		
<tr ><td align='center' colspan='7'  class="heading">&nbsp;</td></tr>	  
</table>	

	
<?php }  // end manage 	?>		
	</td> <!-- main block ends -->
</tr>
<tr><TD><br></TD></tr>
	
	</table>	
		
		
		
		
<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>