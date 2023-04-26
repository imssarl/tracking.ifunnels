<?php
session_start();

require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/settings.class.php");

require_once("classes/common.class.php");

require_once("classes/pagination.class.php");
require_once("classes/search.class.php");

$settings = new Settings();
$pg = new PSF_Pagination();
$sc = new psf_Search();
$common = new Common();

$ms_db = new Database();
$ms_db->openDB();

if (isset($_GET["tid"]) && $_GET["tid"]>0)
{
	$_SESSION["stid"] = $_GET["tid"];
	$_SESSION["refu"] = $_SERVER['HTTP_REFERER'];
	
}
	
if (isset($_SESSION["stid"]) && $_SESSION["stid"]>0)
	$tid = $_SESSION["stid"];
else 
	$tid = 0;
	
	$countsql = "Select count(*)
	from `".TABLE_PREFIX."clicks`  where track_id = $tid";

	$totalrecords = $ms_db->getDataSingleRecord($countsql);
	$pg->setPagination($totalrecords);
$order_sql = $sc->getOrderSql(array("id", "ref_url","ip_add","date"),"id");	

	$sql = "Select c.* , t.keyword, n.ad_name 
	from `".TABLE_PREFIX."clicks` c 
	LEFT JOIN  `".TABLE_PREFIX."track` t On t.id = c.track_id
	LEFT JOIN  `".TABLE_PREFIX."ad` n On n.id = t.ad_id 
	where  c.track_id = $tid $order_sql LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;


if($totalrecords !== false && $totalrecords > 0)
{
	$dtl_rs = $ms_db->getRS($sql);
}
else
{
	$totalrecords = 0;
	$dtl_rs = false;
}		
if ($dtl_rs)
$dtl = $ms_db->getNextRow($dtl_rs);
	
?>

<?php require_once("header.php"); ?>

<title>
<?php echo SITE_TITLE; ?>
</title>
<?php require_once("top.php"); ?>
<?php require_once("left.php"); ?>
 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left">
<?php
$bcrumbhome = '<a class="general" href="../index.php">Home</a>';
$breadmanage = ' >> <a class="general" href="campaign.php?page='.$_SESSION["cmppage"].'">Manage Campaign</a>';
$breadmanage2 = ' >> <a class="general" href="'.$_SESSION["refu"].'">Ad Report</a>';
$breadprocess = " >> Clicks Detail";
echo $bcrumbhome.$breadmanage.$breadmanage2.$breadprocess;
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
<td align="left" colspan="4">Ad : <?php echo $dtl["ad_name"]; ?> | Keyword : <?php echo $dtl["keyword"]; ?></td>
	</tr>
<tr>
<th width="20px">Srno</th>
<th><a class="menu" href="?sort=id_add">IP address</a></th>
<th><a class="menu" href="?sort=ref_url">Referers URL</a></th>
<th><a class="menu" href="?sort=date">Date/Time</a></th>
</tr>
	<?php if ($dtl_rs)
	{
	$tblmat=$pg->startpos+1;
	do
	{
	?>
	<tr class='<?php echo ($tblmat%2) ? "tablematter1" : "tablematter2" ?>'>
	<td align="center"><?php echo $tblmat++ ?></td>
	<td align="center"><?php echo $dtl["ip_add"] ?></td>
	<td align="left"><?php echo $dtl["ref_url"] ?></td>
	<td align="center"><?php echo $dtl["date"] ?></td>
	</tr>
<?php 
	}
	while($dtl = $ms_db->getNextRow($dtl_rs));
	} else { ?>
	<tr><td colspan="4" align="center">No Detail Available</td></tr>
<?php } ?>
<tr><td colspan="4" class="heading">&nbsp;</td></tr>
</table>
<br>
</td>
</tr>
</table>
<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>	


