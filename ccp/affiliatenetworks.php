<?php
session_start();
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/settings.class.php");
require_once("classes/common.class.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");
require_once("classes/affiliate.class.php");
require_once("classes/tracking.class.php");

$affiliate= new affiliate();
$track=new track();
$settings = new Settings();
$common = new Common();
$settings->checkSession();
$ms_db = new Database();
$ms_db->openDB();

$pg = new PSF_Pagination();
$sc = new psf_Search();
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

		
if (isset($_POST["affiliatenetworkform"]) && $_POST["affiliatenetworkform"] == "yes")
{
	if ($process=="new")
	{

		$id = $affiliate->insertaffiliate();

		if ($id)
		{
			header("location: affiliatenetworks.php?process=manage&msg=Affiliate has been added");
			exit;
		}
		else
		{
			$affiliate_data = $common->getPostData();
			$msg = "Error in saving data";
		}
	}
	else if ($process=="edit")
	{
		$ok = $affiliate->updateaffiliate($_POST["id"]);
		header("location: affiliatenetworks.php?process=manage&msg=Affiliate has been modified");
		exit;
	}
}
else if ($process=="edit")
{
	$affiliate_data = $affiliate->getaffiliateById($_GET["id"]);
	
	
}
else if(isset($_GET["upload"]) && $_GET["upload"]=="error")
{
	$affiliate_data = $common->getPostData();
	$msg = $_GET["msg"];
}

else if ($process=="delete")
{
			$ok = $affiliate->deleteaffiliate($_GET["id"]);
			header("location: affiliatenetworks.php?process=manage&page=".$_GET["page"]."&msg=Affiliate has been deleted");
			exit;
}

if ($process == "manage")
{
	$sql = "select count(*) from `".TABLE_PREFIX."affiliatenetwork` where user_id=-1 or user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
	$totalrecords = $ms_db->getDataSingleRecord($sql);
	if ($totalrecords>0)
	{
		$pg->setPagination($totalrecords);
	}
	else
	{
		$pg->startpos=0;
	}
	
	$orderby="";
	if(isset($_GET["sort"]) && $_GET["sort"]!="")
	{
		$orderby = " ORDER BY ".$_GET["sort"];
	}

	$sql= "SELECT * from `".TABLE_PREFIX."affiliatenetwork` where user_id=-1 or user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid']." ".$orderby." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;

	$affiliatenetwork_rs = $ms_db->getRS($sql);
}
	
?>

<?php require_once("header.php"); ?>

<title>
<?php echo SITE_TITLE; ?>
</title>
<script language="javascript" type="text/javascript" src="../tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	// Notice: The simple theme does not use all options some of them are limited to the advanced theme
	/*tinyMCE.init({
		elements : "htmllink",
		theme : "advanced",
		mode : "exact",
		save_callback : "customSave",
		content_css : "example_advanced.css",
		extended_valid_elements : "a[href|target|name]",
		plugins : "table",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		//invalid_elements : "a",
		theme_advanced_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1", // Theme specific setting CSS classes
		//execcommand_callback : "myCustomExecCommandHandler",
		debug : false		
		
	});*/
</script>
<script language="javascript">

function chkMainForm(frm)
{
	var mss = "";
	
	if (frm.affiliate_name.value=="")
	{
		mss += "Please enter affiliate network.\n";
	}
	
	if (frm.affiliate_link.value=="")
	{
		mss += "Enter your Affiliate link name.\n";
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
</script>

<?php require_once("top.php"); ?>

<?php require_once("left.php"); ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
   
    <tr>
<tr>
	<td align="left">
<?php
$bcrumbhome = '<a class="general" href="../index.php">Home</a>';

$bcrumbcamp = ' >> <a class="general" href="campaign.php">Manage Campaign</a>';

if ($process=="manage")
{
	$breadmanage = " >> Manage Affiliate";
}
else
{
	$breadmanage = ' >> <a class="general" href="affiliatenetworks.php">Manage Affiliate</a>';
}

if ($process=="new")
{
	$breadprocess = ' >> New Affiliate';
}
else if ($process=="edit")
{
	$breadprocess = ' >> Edit Affiliate';
}
else if ($process == "confirmdelete")
{
	$breadprocess = ' >> Delete affiliate';
}
 
 
	
	
	
echo 	$bcrumbhome.$bcrumbcamp.$breadmanage.$breadprocess;
?>
	<br>
	</td>
</tr>
<tr>
<td  align="center"> <?php echo $msg ?></td>
</tr>

<tr>
	<td align="center"> <!-- mail block starts -->
<?php  if ($process=="new" || $process == "edit") { ?>
<br>
	    
<form name="newaffiliate" method="post" action="affiliatenetworks.php" onSubmit="return chkMainForm(this)">
<table width="90%"  border="0" cellspacing="0" cellpadding="0" class="inputform">
          <tr>
            <td align="left" width="40%" class="heading" colspan="2"><?php echo ($process=="new") ? "New" : "Edit";?> Affiliate</td>
			</tr>
			<br>
			
			<tr>
				<TD align="right" width="50%">Affiliate Network:</TD>
				<TD width="50%" align="left"><input type="text" name="affiliate_name" id="affiliate_name" value="<?php echo $affiliate_data["affiliate_name"]; ?>" size="20"></TD>
			</tr>

			<tr>
				<TD align="right" width="50%">Track id:</TD>
				<TD width="50%" align="left"><input type="text" name="affiliate_link" id="affiliate_link" value="<?php echo $affiliate_data["affiliate_link"]; ?>" size="15"></TD>
			</tr>

           <tr>
		   <td colspan="2" align="center" class="heading">
              <div align="center">
                <input type="submit" name="Submit" value="Save">
              </div>
                <input type="hidden" name="process" value="<?php echo $process ?>">
                <input type="hidden" name="id" value="<?php echo $affiliate_data["id"] ?>">
                <input type="hidden" name="affiliatenetworkform" value="yes">	
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
			<?php if ($process == "confirmdelete") { ?>
			Are you sure to delete this  network?<br>
			<input type="hidden" name="process" value="delete">
			<?php }  ?>
			<input type="hidden" name="confirm" value="yes">
			<input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
			<input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
			<input type="hidden" name="block_id" value="<?php echo $_GET["block_id"] ?>">			
			<input type="hidden" name="page" value="<?php echo $page ?>">
			<input name="yesbutton" type="submit" value="Yes">&nbsp;
			<input name="nobutton" type="button" value="No" onClick="javascript: location='affiliatenetworks.php?msg=Operation cencelled&page=<?php echo $page ?>&block_id=<?php echo $_GET["block_id"] ?>'">
			</form>
			</td>
			</tr>
			</table>		
<?php }
		else if ($process=="manage")
		{ 
		?>
<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
	<tr>
		<td valign = "top" align="center" class="heading"> <a  class="menu" href = "?process=new">Create new Affiliate</a> </td>
	</tr>
	<tr>
					<td colspan="12">
			<?php if($totalrecords > 0) { ?>					
						<?php $pg->showPagination(); ?>
			<?php } ?>						
					</td>
				</tr>
</table>
<br>
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
<tr  class="tableheading">
<th width="15"><a title = "Sort" class = "menu" href="?sort=id"> ID </a></th>
<th><a title = "Sort" class = "menu" href="?sort=affiliate_name">Affiliate Network</a></th>
<th><a title = "Sort" class = "menu" href="?sort=affiliate_link">Track ID</a></th>
<th colspan="2">Tools</th>
</tr>
<?php
if ($affiliatenetwork_rs)
{
 
	$tblmat=0;
	while($affiliatenetwork_data = $ms_db->getNextRow($affiliatenetwork_rs))
	{
		$id = $affiliatenetwork_data["id"];
		$sys = $affiliatenetwork_data["type"];
		if ($sys=="S") $delete="denied"; else $delete = "delete";
?>	
<tr  id="row<?php echo $id ?>"  class='<?php echo ($tblmat++%2) ? "tablematter1" : "tablematter2" ?>' >
		<td align="center"><?php echo $id ?></td>
		<td align="left"><?php echo $affiliatenetwork_data["affiliate_name"] ?></td>
		<td align="center"><?php echo $affiliatenetwork_data["affiliate_link"] ?></td>
		<td width="16">
		<?php if($affiliatenetwork_data["user_id"]=="-1"){
			echo "X";
		}else{ ?>
			<a href="?process=edit&id=<?php echo $id?>&page=<?php echo $page?>">
			<img src="images/edit.png" border="0" title="Edit" style="cursor:pointer">
			</a>
		<?php } ?>
		</td>
		<td width="16">
		<?php if ($delete=="delete") { ?>
		
		<?php if($affiliatenetwork_data["user_id"]=="-1"){
			echo "X";
		}else{?>
		<a href="?process=confirmdelete&id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/delete.png" border="0" title="Delete" style="cursor:pointer">
		</a>	
		<?php }  } ?>
		</td>
	</tr>
<?php	}
}	  
else
{
echo "<tr><td align='center' colspan='12'>No Affiliate Network Found</td></tr>";
}
?>	  
<tr ><td align='center' colspan='13'  class="heading">&nbsp;</td></tr>	  
</table>	
	
	
	
	
<?php 
	if (isset($_GET["block_id"]) && $_GET["block_id"]>0)
	{
		echo '<script language="javascript">showdiv("'.$_GET["block_id"].'")</script>';
	}
}  // end manage 	?>		
	</td> <!-- main block ends -->
</tr>
<tr><TD><br></TD></tr>
	
	</table>	
		
		
		
		
<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>