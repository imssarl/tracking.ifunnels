<?php
	session_start();
	//echo $_SESSION['admin'];

	require_once("config/config.php");

	require_once("classes/database.class.php");

	require_once("classes/campaign.class.php");
	require_once("classes/common.class.php");


	$damp_db = new Database();

	$common_obj = new Common();


	$damp_db->openDB();


	$common_obj->checkSession();
	
	$campaign_obj = new Campaign;
	$fix_count = $campaign_obj->getNoOfFixAds(); 
	$corner_count = $campaign_obj->getNoOfCornerAds();
	$slide_count = $campaign_obj->getNoOfSlideInAds();
	$ads_count = $campaign_obj->getTotalNoOfAds();
	$ranningAds_count = $campaign_obj->getTotalNoOfRunningAds();
	$closedAds_count = $campaign_obj->getTotalNoOfClosedAds();
	
	//$ranningAds_count=$ads_count-$closedAds_count;
	//$closedAds_count=$ads_count-$ranningAds_count;


?>



<?php require_once("incheader.php"); ?>



<title>



<?php echo SITE_TITLE; ?>

</title>



<script language="javascript">

	// Javascript code will come here

</script>





<?php require_once("inctop.php"); ?>

<?php require_once("incleft.php"); ?>
<?php 
if(isset($_SESSION[SESSION_PREFIX.'sessionuser']))
{
?>
<table align="right">
	<TR>
		<TD align="right" style="font-weight:bold;">Welcome <?php echo $_SESSION[SESSION_PREFIX.'sessionuser']; ?></TD>
	</TR>
	
</table><br><br>
<?php
}
?>
		<table width="30%" align="center" class="summary2">
            <TR>
                <TD>Total no. of Ads</TD>
                <TD>
                    <?php if($ads_count>0){?>
                            <a href = 'campaign.php '><?php echo $ads_count; ?></a>
                    <?php }else{echo $ads_count;}?>
                </TD>
            </TR>
            <TR>
                <TD>Total no. of Corner Ads</TD>
                <TD>
                    <?php if($corner_count>0){?>
                            <a href = 'campaign.php?search=cornerads'><?php echo $corner_count; ?></a>
                    <?php }else{echo $corner_count;}?>
                </TD>
            </TR>
            <TR>
                <TD>Total no. of Slide In Ads</TD>
                <TD>
                    <?php if($slide_count>0){?>
                        <a href = 'campaign.php?search=slideads'><?php echo $slide_count; ?></a>
                    <?php }else{echo $slide_count;}?>
                </TD>
            </TR>
            <TR>
                <TD>Total no. of Fix Ads</TD>
                <TD>
                    <?php if($fix_count>0){?>
                        <a href = 'campaign.php?search=fixads'><?php echo $fix_count; ?></a>
                    <?php }else{echo $fix_count;}?>
                </TD>
            </TR>
            <TR>
                <TD>Total no. of Running Ads</TD>
                <TD>
                    <?php if($ranningAds_count>0){?>
                        <a href = ' campaign.php?search=runads'><?php echo $ranningAds_count; ?></a>
                <?php }else{echo $ranningAds_count;}?>
                </TD>
            </TR>
            <TR>
                <TD>Total no. of Closed Ads</TD>
                <TD>
                    <?php if($closedAds_count>0){?>
                        <a href = 'campaign.php?search=cloads'><?php echo $closedAds_count; ?></a>
                <?php }else{echo $closedAds_count;}?>
                </TD>
            </TR>
        </table>
<?php require_once("incright.php"); ?>

<?php require_once("incbottom.php"); ?>
