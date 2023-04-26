<?php 
require_once("config/config.php"); 
require_once("classes/campaign.class.php");
require_once("classes/tracking.class.php");
require_once("classes/database.class.php");
require_once("classes/affiliate.class.php");
require_once("classes/en_decode.class.php");	
	
$endec=new encode_decode();
$campaign= new campaign();
$track= new track();
$affiliate = new affiliate();
$ms_db = new Database();
$ms_db->openDB();


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
</head>
<script language="JavaScript">
function closeme()
{
window.close();
}
</script>
<link href="stylesheets/style1.css" rel="stylesheet" type="text/css">
<body>

<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" class="summary2" align="center">
<tr><TD align="center" class="heading" valign="top">Include Code</TD></tr>

<TR><TD align="center">
<?php
$_GET["aid"]=$endec->decode($_GET["aid"]);
if(isset($_GET["aid"]) && $_GET["aid"]>0)
{

	$tid = 0;
	$aid = $_GET["aid"];

	$addata=$campaign->getAdById($aid);
	
	$merchantlink=$affiliate->getMerchantLink($addata["affiliate_network"],$addata["merchant_link"]);
	if($addata["ad_env"]=='C')
	$tid = $track->getTrackingIdByAdId($aid);
	
	$code = $track->getTrackingCode($aid, $addata["ad_env"], $tid);
	if($merchantlink=="none")
	{
		$final_merchant_link="";
	}
	else{
		$final_merchant_link = $merchantlink."=".'<?php echo $tid; ?>';
	}
?>
<TEXTAREA  rows="16" cols="90"><?php echo ($code); ?></TEXTAREA>
<div class="message">
The code has to be copied and then paste into the page where you wants it to appear.<br>Page needs to have a php extension
</div>
</TD>
</TR>
<TR><TD align="center" height="100%">
<?php

	if($addata["affiliate_network"]==1)
	{
		$thankscode = $track->getTrackingCode($aid, 'T');
		
?>
		<TEXTAREA rows="16" cols="90"><?php echo $thankscode; ?></TEXTAREA>
		<div class="message">
			Put this code on the thanks page or your return page.
		</div>
		<?php
	}
	else if($final_merchant_link!="")
	{
		?>
		<TEXTAREA rows="4" cols="90">
			<?php echo $final_merchant_link; ?>
		</TEXTAREA>
		<div class="message">
			Replace this link by your previous merchant link ( <?php echo $addata["merchant_link"]; ?> ).
		</div>
		<?php
	}

}
else
	{
		echo "invalid request";
	}
?>
</TD></TR>
<tr><TD  class="heading"><input type="button" value="Close" onclick="closeme()" ></TD></tr>
</table>

</body>
</html>