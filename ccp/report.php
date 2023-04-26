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
require_once("classes/campaign.class.php");








$affiliate= new affiliate();
$track=new track();
$campaign=new campaign();


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


/*if(isset($_GET['aid']))
{
    $aid=$_GET['aid'];
}
else if(isset($_POST['aid']))
{
    $aid=$_POST['aid'];
}

*/

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
if (isset($_GET["aid"]) && $_GET["aid"] > 0)
	$_SESSION["aid"] = $_GET["aid"];
if (isset($_GET["cpage"]) && $_GET["cpage"] > 0)
	$_SESSION["cmppage"] = $_GET["cpage"];
	
$aid = $_SESSION["aid"];	


if ($process == "manage")
{
    $sql = "select count(*) from `".TABLE_PREFIX."track` where ad_id=$aid ";
    $totalrecords = $ms_db->getDataSingleRecord($sql);
    if ($totalrecords>0)
    {
        $pg->setPagination($totalrecords);
		$order_sql=$sc->getOrderSql(array("id","keyword","url_refered","clicks","noofsales","items","amount"),"amount");
		$sql= "SELECT t.id,t.*, round(sum(c.amount),2) as amount, sum(c.item) as items, count(c.id) as noofsales 
		from ".TABLE_PREFIX."track t  
		LEFT JOIN ".TABLE_PREFIX."salesdata c  ON c.track_id = t.id 
		WHERE ad_id = $aid 
		GROUP BY t.id 
		".$order_sql." 
		LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;
	
		$report_rs = $ms_db->getRS($sql);
    }
    else
    {
		$totalrecords = 0;
		$report_rs = false;	
    }
    

}
  
?>

<?php require_once("header.php"); ?>

<title>
<?php echo SITE_TITLE; ?>
</title>
<?php require_once("top.php"); ?>
<?php require_once("left.php"); ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
   
    <tr>
<tr>
    <td align="left">
<?php
$ad = $campaign->getAdById($aid);

$bcrumbhome = '<a class="general" href="../index.php">Home</a>';
$breadmanage = ' >> <a class="general" href="campaign.php?page='.$_SESSION["cmppage"].'">Manage Campaign</a>';
$breadprocess = " >> Ad Report (".$ad["ad_name"].")";

echo     $bcrumbhome.$breadmanage.$breadprocess;
?>
    <br>
    </td>
</tr>
<tr>
<td  align="center"> <?php echo $msg ?></td>
</tr>

<tr>
    <td align="center"> <!-- mail block starts -->
<?php if ($process=="manage")
        { ?>
<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
   <tr>
                    <td>
                        <?php $pg->showPagination(); ?>
                    </td>
                </tr>
</table>
<br>
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">

<tr  class="tableheading">
<th>&nbsp;<a title = "Sort" class = "menu" href="?sort=id">ID</a>&nbsp;</th>
<th><a title = "Sort" class = "menu" href="?sort=keyword">Keyword</a></th>
<th><a title = "Sort" class = "menu" href="?sort=url_refered">Search Engine</a></th>
<th><a title = "Sort" class = "menu" href="?sort=clicks"># of clicks</a></th>
<th><a title = "Sort" class = "menu" href="?sort=noofsales"># of sales</a></th>
<th><a title = "Sort" class = "menu" href="?sort=items"># of items sold</a></th>
<th><a title = "Sort" class = "menu" href="?sort=amount">Sales amount</a></th>
</tr>
<?php
if ($report_rs)
{
 
    $tblmat=0;
    while($report_data = $ms_db->getNextRow($report_rs))
    {
        $id = $report_data["id"];
?>
<tr  id="row<?php echo $id ?>"  class='<?php echo ($tblmat++%2) ? "tablematter1" : "tablematter2" ?>' >
        <td align="center"><?php echo $id; ?></td>
        <td align="center"><?php echo $report_data["keyword"]; ?></td>
        <td align="center"><?php echo $report_data["url_refered"]; ?></td>
        <td align="center">
		<?php if ($report_data["clicks"]>0) { ?>
		<a target="_blank" title="Click here for details" href="ctmclicksreport.php?tid=<?php echo $id; ?>">
		<?php } ?>
		
		<?php echo $report_data["clicks"]; ?>
		<?php if ($report_data["clicks"]>0) { ?> </a> <?php } ?>		
		</td>
        <td align="center"><?php echo ($report_data["noofsales"]>0)?$report_data["noofsales"]:0 ; ?></td>
        <td align="center"><?php echo ($report_data["items"]>0)?$report_data["items"]:0; ?></td>
        <td align="center"><?php echo ($report_data["amount"]>0)?$report_data["amount"]:0; ?></td>
    </tr>
<?php    }
}
else
{
echo "<tr><td align='center' colspan='7'>No Keyword Records Found</td></tr>";
}
?>      
<tr ><td align='center' colspan='7'  class="heading">&nbsp;</td></tr>      
</table>
<?php }  // end manage     ?>        
    </td> <!-- main block ends -->
</tr>
<tr><TD><br></TD></tr>

    </table>

<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>