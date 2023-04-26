<?php
	session_start();

	require_once("config/config.php");
	require_once("classes/database.class.php");
	require_once("classes/common.class.php");
	require_once("classes/campaign.class.php");
	require_once("classes/sound.class.php");	
	require_once("classes/pagination.class.php");
	require_once("classes/search.class.php");	


	$damp_db = new Database();
	$common_obj = new Common();
	$campaign_obj = new Campaign();
	$sound_obj = new Sound();	
	$pg = new Pagination();
	$sc = new Search();

	$damp_db->openDB();

	if (isset($_GET["cid"]) && $_GET["cid"]>0)
	{
		$_SESSION["scid"] = $_GET["cid"];
	}
		
	if (isset($_SESSION["scid"]) && $_SESSION["scid"]>0)
		$cid = $_SESSION["scid"];
	else 
		$cid = 0;
		
		$countsql = "Select count(*) from `".TABLE_PREFIX."clicks`  where ad_id = $cid";
	
		$totalrecords = $damp_db->getDataSingleRecord($countsql);
		$pg->setPagination($totalrecords);
		$order_sql = $sc->getOrderSql(array("id", "refurl","siteurl","ipaddress","datetime"),"id");	
	
// 		$sql = "Select a.*,b.campaign_name,c.url,c.anchortext from `".TABLE_PREFIX."clicks` a, `".TABLE_PREFIX."adcampaigns` b  , `".TABLE_PREFIX."campaign_trackurls` c  where a.ad_id=b.id and b.id=c.campaign_id and a.ad_id = $cid group by a.id
// 		$order_sql LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;
// 	echo $sql;
		$campaign_data=$campaign_obj->getCampaignById($cid);
		$sql = "Select d.* , u.url, u.anchortext from `".TABLE_PREFIX."clicks` d LEFT JOIN `".TABLE_PREFIX."campaign_trackurls` u On u.id = d.siteurl where d.ad_id = $cid $order_sql LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;
	//echo $sql; die();
	if($totalrecords !== false && $totalrecords > 0)
	{
		$dtl_rs = $damp_db->getRS($sql);
	//	print_r($dtl_rs);
	}
	else
	{
		$totalrecords = 0;
		$dtl_rs = false;
	}		
	if ($dtl_rs)
	$dtl = $damp_db->getNextRow($dtl_rs);
		
	?>

<?php require_once("incheader.php"); ?>

<title>
<?php echo SITE_TITLE; ?>
</title>
<?php require_once("inctop.php"); ?>
<?php require_once("incleft.php"); ?>
 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left">
<?php
$bcrumbhome = '<a class="general" href="index.php">Home</a>';
$breadmanage = ' >> <a class="general" href="campaign.php?page='.$_SESSION["cmppage"].'">Manage Campaign</a>';
$breadprocess = " >> Clicks Detail";
echo $bcrumbhome.$breadmanage.$breadprocess;
?>
<br><br>
	  </td>
	</tr>
<?php if($totalrecords > 0) { ?>
				<tr>
					<td colspan="7">
						<?php $pg->showPagination(); ?>
					</td>
				</tr>
			
			<?php } ?>
<tr>
<td> 
<br>
<table align="center" width="90%" cellpadding="1" cellspacing="2" class="summary">
	<tr>
<td align="left" colspan="4">Campaign Name : <?php echo $campaign_data["campaign_name"]; ?>
 
<!-- | Keyword : <?php echo $dtl["keyword"]; ?> -->
 
 </td>
	</tr>
<tr>
<th width="20px">Srno</th>
<th><a class="menu" href="?sort=idaddress">IP address</a></th>
<th><a class="menu" href="?sort=refurl">Site URL</a></th>
<th><a class="menu" href="?sort=siteurl">Link Clicked</a></th>
<th><a class="menu" href="?sort=siteurl">Link Text</a></th>
<th><a class="menu" href="?sort=datetime">Date/Time</a></th>
</tr>
	<?php if ($dtl_rs)
	{
	$tblmat=$pg->startpos+1;
	do
	{
	?>
	<tr class='<?php echo ($tblmat%2) ? "tablematter1" : "tablematter2" ?>'>
	<td align="center"><?php echo $tblmat++ ?></td>
	<td align="center"><?php if($dtl["ipaddress"]!="") { echo $dtl["ipaddress"];} else { echo "No Detail Available";} ?></td>
	<td align="left"><?php if($dtl["refurl"]!="") echo $dtl["refurl"]; else echo "No Detail Available"; ?></td>
	<td align="left"><?php if($dtl["url"]!="") echo $dtl["url"]; else echo $campaign_data['url']; ?></td>
	<td align="left"><?php if($dtl["anchortext"]!="") echo $dtl["anchortext"]; else echo "Global Redirect With No Anchor Text"; ?></td>
	<td align="center"><?php if($dtl["datetime"]!="") echo $dtl["datetime"]; else echo "No Detail Available"; ?></td>
	</tr>
<?php 
	}
	while($dtl = $damp_db->getNextRow($dtl_rs));
	} else { ?>
	<tr><td colspan="6" align="center">No Detail Available</td></tr>
<?php } ?>
<tr><td colspan="6" class="heading">&nbsp;</td></tr>
</table>
<br>
</td>
</tr>
</table>
<?php require_once("incright.php"); ?>
<?php require_once("incbottom.php"); ?>


