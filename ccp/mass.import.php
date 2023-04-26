<?php
	session_start();
	require_once("config/config.php");
	require_once("classes/database.class.php");
	require_once("classes/settings.class.php");
	require_once("classes/common.class.php");
	require_once("classes/campaign.class.php");
	require_once("classes/tracking.class.php");
	require_once("classes/affiliate.class.php");
	require_once("classes/ctmsalesdata.class.php");
	
	$campaign= new campaign();
	$track=new track();
	
	$settings = new Settings();
	$common = new Common();
	$settings->checkSession();
	$ms_db = new Database();
	$affn = new affiliate();
	$ctmsales = new CTMSales();
	$ms_db->openDB();
	
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
		$process = "add";
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

	if (isset($_POST["campaignform"]) && $_POST["campaignform"] == "yes")
	{
			$msg = $ctmsales->uploadMassLinking();		
			header("Location:campaign.php?process=manage&msg=".nl2br(trim($msg)));
			exit;
	}
?>

<?php require_once("header.php"); ?>

<title>
<?php echo SITE_TITLE; ?>
</title>

<script language="javascript">
	// Javascript code will come here
	
function chkMainForm(frm)
{
	var mss = "";

	if (frm.csvfile.value=="")
	{
		mss += "Please enter CSV file to upload (.csv or .txt format) \n";
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
	

</script>

<?php require_once("top.php"); ?>
<?php require_once("left.php"); ?>


<form name="newcampaign" method="post" action="mass.import.php" enctype="multipart/form-data" onSubmit="return chkMainForm(this)">
<table width="90%"  border="0" cellspacing="0" cellpadding="0" class="inputform">
<tr>
<td class="message"  align="center" colspan="2"> <?php echo $msg ?>
<br><br>
</td>
</tr>
          <tr>
            <td align="left" width="40%" class="heading" colspan="2">Deep Linking Mass Import</td>
			</tr>
			<tr><TD>&nbsp;</TD></tr>
			<tr>
				<TD align="right">Upload CSV:</TD>
				<TD align="left">
					<input type="file" name="csvfile" size="35">
				</TD>
			</tr>
			<!--<tr><TD>&nbsp;</TD></tr>-->
			<tr>
				<TD  align="right">Include Header in CSV files?</TD>
				<td><input type="radio" name="isHeaderInclude" value="N" >&nbsp;No&nbsp;
					
				<input type="radio" name="isHeaderInclude" value="Y" checked="checked">&nbsp;Yes</td>
				
			</tr>
			<!--<tr><TD>&nbsp;</TD></tr>-->
			<tr>
				<TD  align="right">Do You Want to Create Tracking Page?</TD>
				<td><input type="radio" name="page" value="N" checked="checked" >&nbsp;No&nbsp;
					
				<input type="radio" name="page" value="Y">&nbsp;Yes</td>
				
			</tr>
			<tr><TD>&nbsp;</TD></tr>
			<tr>
				<TD colspan="2">
					<font color="red">
						Note:Your CSV file should follow this order<br><br>
						[Campaign, Ad, Network, Environment, Link]
					</font>
			</TD></tr>
           <tr>
		   <td colspan="2" align="center" class="heading">
              <div align="center">
                <input type="submit" name="Submit" value="Import">
              </div>
                <input type="hidden" name="process" value="<?php echo $process ?>">
                <input type="hidden" name="campaignform" value="yes">				
			</td>
            </tr>
             
        </table> </form>	
<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>
