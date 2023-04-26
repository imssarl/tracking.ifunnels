<?php

session_start();

if (isset($_GET["id"]) && $_GET["id"]>0)

{

	$_SESSION["snippetsummaryid"] = $_GET["id"];;

	$_SESSION["snippetsummaryprocess"] = "summarysnippet";

}

else if (isset($_GET["pid"]) && $_GET["pid"]>0)

{

	$_SESSION["snippetsummaryid"] = $_GET["pid"];;

	$_SESSION["snippetsummaryprocess"] = "summarypart";

}

if (isset($_SESSION["snippetsummaryid"]) && $_SESSION["snippetsummaryid"]>0)

{

$process = $_SESSION["snippetsummaryprocess"];

$id = $_SESSION["snippetsummaryid"];

}

else

{

	

	echo "<div align='center'>Invalid Request</div>";

	die();

}





require_once("config/config.php");

require_once("classes/database.class.php");

require_once("classes/settings.class.php");

require_once("classes/sites.class.php");

require_once("classes/common.class.php");

require_once("classes/keywords.class.php");

require_once("classes/pagination.class.php");

require_once("classes/search.class.php");



$settings = new Settings();

$pg = new PSF_Pagination();

$sc = new psf_Search();

$common = new Common();



$ms_db = new Database();

$ms_db->openDB();



if ($process=="summarypart")

{

	$countsql = "Select count(*)

	from `".TABLE_PREFIX."snippet_click_details` d where d.snippet_part_id = $id";



}

else

{

	$countsql = "Select count(*)

	from `".TABLE_PREFIX."snippet_click_details`d 

	LEFT JOIN `".TABLE_PREFIX."snippet_parts` p ON p.id = d.snippet_part_id

	LEFT JOIN `".TABLE_PREFIX."snippets` s  ON s.id = p.snippet_id 

	where s.id = $id";

	

}

	$totalrecords = $ms_db->getDataSingleRecord($countsql);

	$pg->setPagination($totalrecords);

$order_sql = $sc->getOrderSql(array("snippet_part_id","url_shown","ip_address","date", "url", "anchortext"),"snippet_part_id");	

if ($process=="summarypart")

{

	$sql = "Select d.* , u.url, u.anchortext

	from `".TABLE_PREFIX."snippet_click_details` d

	LEFT JOIN  `".TABLE_PREFIX."snippet_trackurls` u On u.id = d.trackurl_id

	where  d.snippet_part_id = $id $order_sql LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;

}

else

{

	$sql = "Select d.* , u.url, u.anchortext

	from `".TABLE_PREFIX."snippet_click_details`d 

	LEFT JOIN  `".TABLE_PREFIX."snippet_trackurls` u On u.id = d.trackurl_id

	LEFT JOIN `".TABLE_PREFIX."snippet_parts` p ON p.id = d.snippet_part_id

	LEFT JOIN `".TABLE_PREFIX."snippets` s  ON s.id = p.snippet_id 

	where  s.id = $id  and s.user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid']." $order_sql LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;

}





if($totalrecords !== false && $totalrecords > 0)

{

	$dtl_rs = $ms_db->getRS($sql);

}

else

{

	$totalrecords = 0;

	$details = false;

}		

	

	

?>

<?php require_once("header.php"); ?>



<title>



<?php echo SITE_TITLE; ?>

</title>



<script language="javascript">

function showpart(id)

{

	openwindow= window.open ("snippetsshow.php?partidfortest="+id, "Snippet Part",

		"'status=0,scrollbars=1',width=650,height=300,resizable=1");

	

	openwindow.moveTo(50,50);

}

	

</script>



<?php require_once("top.php"); ?>



<?php require_once("left.php"); ?>



 <table width="100%"  border="0" cellspacing="0" cellpadding="0">

    <tr>

      <td><?php require_once("menu.php") ?></td>

    </tr>

    <tr>

      <td align="left">

<?php

$bcrumbhome = '<a class="general" href="index.php">Home</a>';

$breadmanage = ' >> <a class="general" href="snippets.php">Manage Snippets</a>';

$breadprocess = " >> Snippet Details";

echo $bcrumbhome.$breadmanage.$breadprocess;

?>

	  </td>

	</tr>

	<tr>

	<td align="center">

	  <br>

<?php if($totalrecords > 0) { ?>

				<tr>

					<td colspan="7">

						<?php $pg->showPagination(); ?>

					</td>

				</tr>

			

			<?php } ?>

 

<br>

<table align="center" width="90%" cellpadding="1" cellspacing="2" class="summary">

			

<tr>

<th>Srno</th>

<th><a class="menu" href="?sort=snippet_part_id">Part ID</a></td>

<th><a class="menu" href="?sort=id_address">IP address</a></td>

<th><a class="menu" href="?sort=url_shown">Referers URL</a></td>

<th><a class="menu" href="?sort=url">Link Clicked</a></td>

<th><a class="menu" href="?sort=anchortext">Link Text</a></td>

<th><a class="menu" href="?sort=date">Date/Time</a></th>



</tr>

	<?php if ($dtl_rs)

	{

	$tblmat=$pg->startpos+1;

	while($dtl = $ms_db->getNextRow($dtl_rs))

	{

	?>

	<tr class='<?php echo ($tblmat%2) ? "tablematter1" : "tablematter2" ?>'>

	<td align="center"><?php echo $tblmat++ ?></td>

	<td align="center">

	<div onclick="showpart(<?php echo $dtl["snippet_part_id"] ?>)" style="cursor:pointer" class="general">

	<?php echo $dtl["snippet_part_id"] ?>

	</div>

	</td>

	<td align="center"><?php echo $dtl["ip_address"] ?></td>

	<td align="left"><?php echo $dtl["url_shown"] ?></td>

	<td align="left"><?php echo $dtl["url"] ?></td>

	<td align="left"><?php echo $dtl["anchortext"] ?></td>	

	<td align="center"><?php echo $dtl["date"] ?></td>

	</tr>

<?php 

	}

	} else { ?>

	<tr><td colspan="7" align="center">No Detail Available</td></tr>

<?php } ?>

<tr><td colspan="7" class="heading">&nbsp</td></tr>

</table>

<br>

</td>

</tr>

</table>

<?php require_once("right.php"); ?>

<?php require_once("bottom.php"); ?>

	





