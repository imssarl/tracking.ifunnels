<?php
session_start();
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/settings.class.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");
require_once("classes/common.class.php");
$common = new Common();
$settings = new Settings();
$settings->checkSession();
$pg = new PSF_Pagination();
$sc = new psf_Search();
$ms_db = new Database();
$ms_db->openDB();


/*$sql = "Select count(p.id) from ".TABLE_PREFIX."ad p where p.user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
$total_ad = $ms_db->getDataSingleRecord($sql);

$sql = "Select count(p.id) from ".TABLE_PREFIX."campaign p where p.user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
$total_campaign = $ms_db->getDataSingleRecord($sql);*/
$sql = "Select count(p.id) from ".TABLE_PREFIX."ad p ";
$total_ad = $ms_db->getDataSingleRecord($sql);

$sql = "Select count(p.id) from ".TABLE_PREFIX."campaign p ";
$total_campaign = $ms_db->getDataSingleRecord($sql);
?>

<?php require_once("header.php"); ?>

<title>
<?php echo SITE_TITLE; ?>
</title>

<script language="javascript">
	// Javascript code will come here
</script>

<?php require_once("top.php"); ?>
<?php require_once("left.php"); ?>
<br>
	  <table width="25%" cellpadding="1" cellspacing="1" border="0"  align="center" class="summary2">
	  <tr><td colspan="2" class="heading">&nbsp;</td></tr>
	  
		  <tr>
				<td width="70%" align="left">Number of Campaigns :</td>
				<td align="center"><?php echo $total_campaign ; ?>
				</td>
		  </tr>
		  <tr>
				<td width="70%" align="left">Number of Ads :</td>
				<td align="center"><?php echo $total_ad ; ?>
				</td>
		  </tr>
		<tr><td colspan="2" class="heading">&nbsp;</td></tr>  
	  </table>
<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>
