<?php
	session_start();

	require_once("config/config.php");
	require_once("classes/database.class.php");
	require_once("classes/common.class.php");
	require_once("classes/campaign.class.php");
	require_once("classes/pagination.class.php");
	require_once("classes/search.class.php");	


	$damp_db = new Database();
	$common_obj = new Common();
	$campaign_obj = new Campaign();
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
		
		$countsql = "Select count(*) from `".TABLE_PREFIX."effectiveness`  where ad_id = $cid";
	
		$totalrecords = $damp_db->getDataSingleRecord($countsql);
		$pg->setPagination($totalrecords);
		$order_sql = $sc->getOrderSql(array("id", "refurl","siteurl","ipaddress","datetime"),"id");	
	
		$sql = "Select a.*,b.campaign_name from `".TABLE_PREFIX."effectiveness` a, `".TABLE_PREFIX."adcampaigns` b  where a.ad_id=b.id and a.ad_id = $cid
		$order_sql LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;
	//echo $sql;die();
	
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
$breadprocess = " >> Effectiveness Detail";
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
<td align="left" colspan="4">Campaign Name : <?php echo $dtl["campaign_name"]; ?>
 
<!-- | Keyword : <?php echo $dtl["keyword"]; ?> -->
 
 </td>
	</tr>
<tr>
<th width="20px">Srno</th>
<th><a class="menu" href="?sort=idaddress">IP address</a></th>
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
	<td align="center"><?php echo $dtl["ipaddress"] ?></td>
	<td align="center"><?php echo $dtl["datetime"] ?></td>
	</tr>
<?php 
	}
	while($dtl = $damp_db->getNextRow($dtl_rs));
	} else { ?>
	<tr><td colspan="4" align="center">No Detail Available</td></tr>
<?php } ?>
<tr><td colspan="5" class="heading">&nbsp;</td></tr>
</table>
<br>
</td>
</tr>
</table>
<?php require_once("incright.php"); ?>
<?php require_once("incbottom.php"); ?>


