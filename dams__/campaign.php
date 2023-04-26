<?php
session_start();
// echo $_SESSION['admin'];
require_once( "config/config.php" );
require_once( "classes/database.class.php" );
require_once( "classes/common.class.php" );
require_once( "classes/campaign.class.php" );
require_once( "classes/sound.class.php" );
require_once( "classes/pagination.class.php" );
require_once( "classes/search.class.php" );
require_once( "classes/en_decode.class.php" );

$endec = new encode_decode();
$damp_db = new Database();
$common_obj = new Common();
$campaign_obj = new Campaign();
$sound_obj = new Sound();
$pg = new Pagination();
$sc = new Search();
$damp_db->openDB();

$common_obj->checkSession();

if ( isset( $_POST['process'] ) ) {
	$process = $_POST['process'];
} else if ( isset( $_GET['process'] ) ) {
	$process = $_GET['process'];
} else {
	$process = "manage";
} 
if ( isset( $_GET["page"] ) ) {
	$page = $_GET["page"];
	$_SESSION["cmppage"] = $_GET["page"];
} else if ( isset( $_POST["page"] ) ) {
	$page = $_POST["page"];
	$_SESSION["cmppage"] = $_POST["page"];
} else {
	$page = 1;
} 
if ( isset( $_POST['msg'] ) ) {
	$msg = $_POST['msg'];
} else if ( isset( $_GET['msg'] ) ) {
	$msg = $_GET['msg'];
} else {
	$msg = "";
} 
if ( isset( $_GET['search'] ) && $_GET['search'] != "" && $_GET['search'] == "split" ) {
	$process = $_GET['search'];
} 

if ( isset( $_POST['insert_campaign'] ) && $_POST['insert_campaign'] == "Yes" ) {
	include_once( "incvalidatefileupload.php" );
	if ( $uploadsound && $uploadflipped && $uploadbackground ) {
		$numb = $campaign_obj->insert(); 
		// echo $numb;
		if ( $numb === false ) { // echo $numb;
			$campaign_Data = $common_obj->getPostData();
			if ( $campaign_Data['txt_contents'] != "" ) {
				$campaign_Data['contents'] = $campaign_Data['txt_contents'];
			} 
			// print_r($campaign_Data );exit;
			$process = "new";
			$camp_name_error = "error";
		} else {
			$isInserted = $campaign_obj->insert();
			header( "location: campaign.php?process=manage&page=" . $page . "&msg=Campaign has been created" );
			exit;
		} 
	} else {
		$campaign_Data = $common_obj->getPostData();
	} 
} 
if ( isset( $_POST['update_campaign'] ) && $_POST['update_campaign'] == "Yes" ) {
	include_once( "incvalidatefileupload.php" );
	if ( $uploadsound && $uploadflipped && $uploadbackground ) {
		$campaign_obj->update( $_POST["campaign_id_hid"] );
		header( "location: campaign.php?process=manage&page=" . $page . "&msg=Campaign has been updated" );
		exit;
	} 
} 
if ( isset( $_POST["create_test"] ) && $_POST["create_test"] == "Yes" ) {
	$id = $campaign_obj->insertSplitTest();

	header( "location: campaign.php?process=split&page=" . $page . "&msg=New split test has been created" );
	exit;
} 
if ( isset( $_POST['update_test'] ) && $_POST['update_test'] == "Yes" ) {
	$campaign_obj->updateTest( $_POST["tid"] );
	header( "location: campaign.php?process=split&page=" . $page . "&msg=Split test has been updated" );
	exit;
} 
if ( $_GET['process'] == "confirmWinner" ) {
	// print_r($_GET);die();
	$sql = "UPDATE `" . TABLE_PREFIX . "split_test` set isRunning='N' where id =" . $_GET['sid'] . "";
	$damp_db->modify( $sql );

	$sql = "UPDATE `" . TABLE_PREFIX . "split_campaign` SET `isWinner` = 'N' WHERE `split_test_id` =" . $_GET['sid'];
	$damp_db->modify( $sql );

	$sql = "UPDATE `" . TABLE_PREFIX . "split_campaign` SET `isWinner` = 'Y' WHERE `campaign_id` =" . $_GET['cid'] . " AND `split_test_id` =" . $_GET['sid'];
	$damp_db->modify( $sql );

	if ( $_GET['which'] == "highest" )
		$msg = "Campaign with highest CTR is now running!!!";
	else
		$msg = "New Campaign has been made as a winner";

	header( "location: campaign.php?process=split&page=" . $page . "&msg=" . $msg . "" );
	exit;
} 

if ( $process == "manage" ) {
	if ( $_GET['search'] && $_GET['search'] == 'cornerads' ) {
		$sql = "select count(*) from " . TABLE_PREFIX . "adcampaigns where position like '%C%' and user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'];
		$totalrecords = $damp_db->getDataSingleRecord( $sql );

		if ( $totalrecords > 0 ) {
			$pg->setPagination( $totalrecords );
		} else {
			$pg->startpos = 0;
		} 

		$order_sql = $sc->getOrderSql( array( "id", "campaign_name", "start_date", "end_date", "position", "on_action", "play_sound", "track_ad", "clicks", "impression", "effectiveness" ), "id" );

		$sql = "select * from `" . TABLE_PREFIX . "adcampaigns` where position like '%C%' and user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;
	} elseif ( $_GET['search'] && $_GET['search'] == 'slideads' ) {
		$sql = "select count(*) from " . TABLE_PREFIX . "adcampaigns where position like '%S%' and user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'];
		$totalrecords = $damp_db->getDataSingleRecord( $sql );
		if ( $totalrecords > 0 ) {
			$pg->setPagination( $totalrecords );
		} else {
			$pg->startpos = 0;
		} 

		$order_sql = $sc->getOrderSql( array( "id", "campaign_name", "start_date", "end_date", "position", "on_action", "play_sound", "track_ad", "clicks", "impression", "effectiveness" ), "id" );

		$sql = "select * from `" . TABLE_PREFIX . "adcampaigns` where position like '%S%' and user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;
	} elseif ( $_GET['search'] && $_GET['search'] == 'fixads' ) {
		$sql = "select count(*) from " . TABLE_PREFIX . "adcampaigns where position like '%F%' and user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'];
		$totalrecords = $damp_db->getDataSingleRecord( $sql );
		if ( $totalrecords > 0 ) {
			$pg->setPagination( $totalrecords );
		} else {
			$pg->startpos = 0;
		} 

		$order_sql = $sc->getOrderSql( array( "id", "campaign_name", "start_date", "end_date", "position", "on_action", "play_sound", "track_ad", "clicks", "impression", "effectiveness" ), "id" );

		$sql = "select * from `" . TABLE_PREFIX . "adcampaigns` where position like '%F%' and user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;
	} elseif ( $_GET['search'] && $_GET['search'] == 'runads' ) {
		$today = date( 'Y-m-d' );
		$sql = "select count(*) from `" . TABLE_PREFIX . "adcampaigns` where (end_date >= '" . $today . "' or end_date is NULL) and user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'];
		$totalrecords = $damp_db->getDataSingleRecord( $sql );
		if ( $totalrecords > 0 ) {
			$pg->setPagination( $totalrecords );
		} else {
			$pg->startpos = 0;
		} 

		$order_sql = $sc->getOrderSql( array( "id", "campaign_name", "start_date", "end_date", "position", "on_action", "play_sound", "track_ad", "clicks", "impression", "effectiveness" ), "id" );

		$sql = "select * from `" . TABLE_PREFIX . "adcampaigns` where (end_date >= '" . $today . "' or end_date is NULL) and user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;
	} elseif ( $_GET['search'] && $_GET['search'] == 'cloads' ) {
		$today = date( 'Y-m-d' );
		$sql = "select count(*) from " . TABLE_PREFIX . "adcampaigns where end_date < '" . $today . "' and user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'];
		$totalrecords = $damp_db->getDataSingleRecord( $sql );
		if ( $totalrecords > 0 ) {
			$pg->setPagination( $totalrecords );
		} else {
			$pg->startpos = 0;
		} 

		$order_sql = $sc->getOrderSql( array( "id", "campaign_name", "start_date", "end_date", "position", "on_action", "play_sound", "track_ad", "clicks", "impression", "effectiveness" ), "id" );

		$sql = "select * from `" . TABLE_PREFIX . "adcampaigns` where end_date < '" . $today . "' and user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;
	} else {
		$sql = "select count(*) from " . TABLE_PREFIX . "adcampaigns where user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'];
		$totalrecords = $damp_db->getDataSingleRecord( $sql );
		if ( $totalrecords > 0 ) {
			$pg->setPagination( $totalrecords );
		} else {
			$pg->startpos = 0;
		} 

		$order_sql = $sc->getOrderSql( array( "id", "campaign_name", "start_date", "end_date", "position", "on_action", "play_sound", "track_ad", "clicks", "impression", "effectiveness" ), "id" );

		$sql = "select * from `" . TABLE_PREFIX . "adcampaigns` where user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;
	} 

	$campaign_rs = $damp_db->getRS( $sql );
} else if ( $process == "edit" ) {
	if ( isset( $_GET["id"] ) && $_GET["id"] != "" ) {
		$campaign_id = $_GET["id"];
	} elseif ( isset( $_POST['campaign_id_hid'] ) && $_POST['campaign_id_hid'] != "" ) {
		$campaign_id = $_POST['campaign_id_hid'];
	} 

	$campaign_Data = $campaign_obj->getCampaignById( $campaign_id );
	$sound_Data = $sound_obj->getSoundById( $campaign_Data["sound_id"] );
} else if ( $process == "confirmdelete" ) {
	$ok = $campaign_obj->delete( $_GET["id"] );
	header( "location: campaign.php?process=manage&page=" . $_GET["page"] . "&msg=Campaign has been deleted" );
	exit;
} else if ( $process == "confirmduplicate" ) {
	$ok = $campaign_obj->insertDuplicate( $_GET["id"] );
	header( "location: campaign.php?process=manage&page=" . $_GET["page"] . "&msg=Campaign has been duplicated" );
	exit;
} else if ( $process == "new_split" ) {
	$sql = "select count(*) from " . TABLE_PREFIX . "split_test where user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'];
	$totalrecords = $damp_db->getDataSingleRecord( $sql );
	if ( $totalrecords > 0 ) {
		$pg->setPagination( $totalrecords );
	} else {
		$pg->startpos = 0;
	} 

	$order_sql = $sc->getOrderSql( array( "id", "test_name", "isDuration", "duration_type", "duration" ), "id" );

	$sql = "select * from `" . TABLE_PREFIX . "split_test` where user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;

	$split_test_rs = $damp_db->getRS( $sql );
} else if ( $process == "confirmdeletesplittest" ) {
	$ok = $campaign_obj->deleteSplitTest( $_GET["id"] );
	header( "location: campaign.php?process=split&page=" . $_GET["page"] . "&msg=Split test has been deleted" );
	exit;
} else if ( $process == "confirmduplicatesplittest" ) {
	$ok = $campaign_obj->insertDuplicateSplitTest( $_GET["id"] );
	header( "location: campaign.php?process=split&page=" . $_GET["page"] . "&msg=Split test has been duplicated" );
	exit;
} else if ( $process == "confirmendsplittest" ) {
	$ok = $campaign_obj->endSplitTest( $_GET["id"] );
	header( "location: campaign.php?process=split&page=" . $_GET["page"] . "&msg=Split test has been Ended, now campaign with highest CTR is running!!!" );
	exit;
} else if ( $process == "split" ) {
	$sql = "select count(*) from " . TABLE_PREFIX . "split_test where user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'];
	$totalrecords = $damp_db->getDataSingleRecord( $sql );
	if ( $totalrecords > 0 ) {
		$pg->setPagination( $totalrecords );
	} else {
		$pg->startpos = 0;
	} 
	$order_sql = $sc->getOrderSql( array( "id", "test_name", "isDuration", "date_created", "duration_type", "duration", "isRunning" ), "id" );
	$sql = "select * from `" . TABLE_PREFIX . "split_test` where user_id=" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;
	$split_test_rs = $damp_db->getRS( $sql );
	$_GET['search'] = "split";
} 

require_once( "incheader.php" );
?>
<title><?php echo SITE_TITLE;?></title>
<?php 
require_once( "campaign_js.php" );
require_once( "inctop.php" );
require_once( "incleft.php" );
?>
<?php if ( isset( $_SESSION[SESSION_PREFIX . 'sessionuser'] ) ) {?>
<table align="right">
	<TR>
		<TD align="right" style="font-weight:bold;">Welcome <?php echo $_SESSION[SESSION_PREFIX . 'sessionuser'];?></TD>
	</TR>
</table><br><br>
<?php } ?>
<table align="left" border="0">
	<tr>
		<TD align="left">
			<?php
$home = '<a class="general" href="index.php">Home</a>';

if ( $process == "manage" ) {
	$manage = " >> Manage Campaign";
} elseif ( $process == "new" || $process == "edit" || $process == "upload" || $process == "confirmdelete" ) {
	$manage = ' >> <a class="general" href="campaign.php">Manage Campaign</a> ';
} 
if ( $process == "new" ) {
	$editprocess = ' >> New Campaign';
} else if ( $process == "edit" ) {
	$editprocess = ' >> Edit Campaign';
} 
if ( $process == "split" ) {
	$manage = " >> Manage Split Test";
} elseif ( $process == "new_split" || $process == "edit_split" ) {
	$manage = ' >> <a class="general" href="campaign.php?process=split">Manage Split Test</a> ';
} 
if ( $process == "new_split" ) {
	$editprocess = ' >> New Split Test';
} else if ( $process == "edit_split" ) {
	$editprocess = ' >> Edit Split Test';
} 
echo $home . $manage; 
?>
		</TD>
	</tr>		
</table><br><br>
<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
	<tr>
		<td valign = "top" align="center" class="optional_field"><?php echo $msg ?></td>
	</tr>
</table>
<?php if ( $process == "new" || $process == "edit" ) {
	if ( $campaign_Data['position'] != 'C' && $campaign_Data['position'] != 'S' && $campaign_Data['position'] != 'F' ) {
		$data = $campaign_Data['position'];
		$campaign = explode( "+", $data );
		$campaign_Data['positionC'] = $campaign[0];
		$campaign_Data['positionS'] = $campaign[1];
		$campaign_Data['positionF'] = $campaign[2]; 
		// print_r($campaign);
	} else { // echo "rahul";
		if ( $campaign_Data['position'] == 'C' )
			$campaign_Data['positionC'] = $campaign_Data['position'];
		if ( $campaign_Data['position'] == 'S' )
			$campaign_Data['positionS'] = $campaign_Data['position'];
		if ( $campaign_Data['position'] == 'F' )
			$campaign_Data['positionF'] = $campaign_Data['position']; 
		// echo $campaign_Data['positionC'].$campaign_Data['positionS'].$campaign_Data['positionF'];exit;
	} 

	if ( $camp_name_error == "error" ) {
		?>
			<div align="center" class="error">This Campaign Name Already Exist Please Select Another Name</div>
<?php
	} 
	// echo $process;exit;
	?>
	
	<form name="campaign" action="campaign.php" method="post" onsubmit="return validate_form(this)"  enctype="multipart/form-data">
		<table width="80%" align="center" class="summary2" border='0'>
			<tr><TD colspan="2"><strong>Note: All fields on this page are required, unless otherwise indicated as (optional). Optional fields are marked as</strong> <span class="optional_field">* </span></TD></tr><tr><TD colspan="2">&nbsp;</TD></tr>
			<TR>
				<TD align="right" width="30%">
					Campaign Name:
				</TD>
				<Td align="left">
					<input type="text" name="campaign_name" id="campaign_name" value="<?php echo $campaign_Data["campaign_name"];
	?>">
				</Td>
			</TR>
			<tr>
				<TD align="right">
					<span class="optional_field">* </span>Start Date:
				</TD>
				<td align="left">
					<input type="text" value="<?php echo $campaign_Data["start_date"];
	?>" readonly name="start_date" id="start_date">
					<input type="button" value="Pick" onclick="displayCalendar(document.forms[0].start_date,'yyyy/mm/dd',this)">
				</td>
			</tr>
			<tr>
				<TD align="right">
					<span class="optional_field">* </span>End Date:
				</TD>
				<td align="left">
					<input type="text" value="<?php echo $campaign_Data["end_date"];
	?>" readonly name="end_date" id="end_date">
					<input type="button" value="Pick" onclick="displayCalendar(document.forms[0].end_date,'yyyy/mm/dd',this)">					
				</td>
			</tr>
			<tr>
				<TD align="right">
					Ad Type:
				</TD>
				<td align="left" valign="middle">
					<input type="checkbox" name="positionC" id="positionC" value="C"  <?php if ( $campaign_Data['positionC'] == "C" || $process == "new" ) echo "checked";
	?>  onclick="checking();">Corner Ads&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="positionS" id="positionS" value="S" <?php if ( $campaign_Data["positionS"] == "S" ) echo "checked";
	?> onclick="checking();" >Slide In&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="positionF" id="positionF" value="F" <?php if ( $campaign_Data["positionF"] == "F" ) echo "checked";
	?> onclick="checking();" >Fix Position Ads
				</td>
			</tr>
			
			<tr>
				<TD colspan="2">
					<div id="slide" style="display:none">
						<table width="100%" align="center" border="0">
							<tr>
								<TD align="right" width="30%">Slide In Content Type:</TD>
								<td align="left">
									<input type="radio" name="content_type" value="T" <?php if ( $campaign_Data["content_type"] == "T" ) echo "checked";
	?> onclick="show_textarea();">Text&nbsp;
									<input type="radio" name="content_type" <?php if ( $campaign_Data["content_type"] == "H" ) echo "checked";
	?> value="H" onclick="show_html();" >HTML
								</td>
							</tr>
							<TR>
								
								<TD align="right" width="30%">
								</TD>
								<td align="left" colspan="0">
								<div id=1 style="display:none">
								Contents :
									<textarea name = "contents" id="contents" rows="13" style="width:99%"><?php if ( $campaign_Data["content_type"] == "H" ) echo $campaign_Data["contents"];
	?>
									</textarea>
								</div>

							<div id=2 style="display:none">
								Contents :
								<textarea name="txt_contents" id="txt_contents" rows="13" style="width:99%" ><?php if ( $campaign_Data["content_type"] == "T" )echo $campaign_Data["contents"];
	?></textarea>
							</div>
								</TD>
							</TR>
					
			
							

					
											</table>
									</div>
								</td>
							</tr>

							
			<tr>
				<TD colspan="2">
					<div id="fix_content" style="display:none">
						<table width="100%" align="center" border="0">
							<tr>
								<TD align="right" width="30%">Fix Position Content Type:</TD>
								<td align="left">
									<input type="radio" name="fix_content_type" value="T" <?php if ( $campaign_Data["fix_cont_type"] == "T" ) echo "checked";
	?> onclick="show_textarea_fix();">Text&nbsp;
									<input type="radio" name="fix_content_type" <?php if ( $campaign_Data["fix_cont_type"] == "H" ) echo "checked";
	?> value="H" onclick="show_html_fix();" >HTML
								</td>
							</tr>
							<TR>
								
								<TD align="right" width="30%">
								</TD>
								<td align="left" colspan="0">
							<div id="fix_textarea" style="display:none">
								Text Contents :
								<textarea name="fix_txt_contents" id="fix_txt_contents" rows="13" style="width:99%" ><?php if ( $campaign_Data["fix_cont_type"] == 'T' )echo $campaign_Data["fix_contents"];
	?></textarea>
							</div>
							<div id="html_fix" style="display:none">
								HTML Contents:
									<textarea name = "fix_html_contents" id="fix_html_contents" rows="13" style="width:99%"><?php if ( $campaign_Data["fix_cont_type"] == 'H' ) echo $campaign_Data["fix_contents"];
	?>
									</textarea>
								</div>
								</TD>
							</TR></table></div></TD></tr>
				
							<tr>
								<TD colspan="2">
								<div id="fix_pos" style="display:none">
								<table width="100%">
								<tr>
								<TD align="right" width="30%">
									Select Fixed Position:
								</TD>
								<td>
									<input type="radio" name="corner_position1" id="corner_position1" value="T" checked="true">Top &nbsp;
									<input type="radio" name="corner_position1" id="corner_position1" value="B" <?php if ( $campaign_Data["fix_position"] == "B" ) echo "checked";
	?> > Bottom
									
								</td>
							</tr>
							

								</table>
									</div>
								</td>
							</tr>
							<tr>
								<TD colspan="2">
								<div id="floating" style="display:none">
								<table width="100%">
								<tr>
								<TD align="right" width="30%">
									Floating Effect:
								</TD>
								<td>
									<input type="radio" name="floating_eff" id="floating_eff" value="Y" checked="true">Yes &nbsp;
									<input type="radio" name="floating_eff" id="floating_eff" value="N" <?php if ( $campaign_Data["floating"] == "N" ) echo "checked";
	?> > No
									
								</td>
							</tr></table></div></TD></tr>
							<tr>
								<TD colspan="3">
								<div id="sheight" style="display:none">
								<table width="100%">
								<tr>
								<TD align="right" width="30%">
									Slide in Position:
								</TD>
								<td>
									<input type="radio" name="sheight" id="sheight" value="d" checked="true" onclick="show_sheight1();">Default &nbsp;
									<input type="radio" name="sheight" id="sheight" value="u" onclick="show_sheight();" <?php if ( $campaign_Data["sdiv_pos_type"] == "u" ) echo "checked";
	?> > User Defined&nbsp;
									<div id="user_shgt1" style="display:none;"><input type="text" name="user_shgt" id="user_shgt" size="4" maxlength="4" value="<?php echo $campaign_Data["sdiv_pos"];
	?>"  >Pixels</div>
								</td>
								</div>
							</tr></table></div></TD></tr>
							<tr>
								<TD colspan="3">
								<div id="height" style="display:none">
								<table width="100%">
								<tr>
								<TD align="right" width="30%">
									Height:
								</TD>
								<td>
									<input type="radio" name="height" id="height" value="a" checked="true" onclick="show_height1();">Auto &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="height" id="height" value="u" onclick="show_height();" <?php if ( $campaign_Data["fdiv_height_type"] == "u" ) echo "checked";
	?> > User Defined&nbsp;
									<div id="user_hgt1" style="display:none;"><input type="text" name="user_hgt" id="user_hgt" size="4" maxlength="4" value="<?php echo $campaign_Data["fdiv_height"];
	?>"  >Pixels</div>
								</td>
								</div>
							</tr></table></div></TD></tr>
							
							<tr>
								<TD colspan="3">
								<div id="width" style="display:none">
								<table width="100%">
								<tr>
								<TD align="right" width="30%">
									Width:
								</TD>
								<td>
									<input type="radio" name="width" id="width" value="d" checked="checked" onclick="show_width1();"><div id="fixed" style="display:none;">100%</div><div id="slidein" style="display:none;">Auto</div> &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="width" id="width" value="u" onclick="show_width();" <?php if ( $campaign_Data["fdiv_width_type"] == "u" ) echo "checked";
	?> > User Defined&nbsp;
									<div id="user_width1" style="display:none;"><input type="text" name="user_width" id="user_width" size="4" maxlength="4" value="<?php echo $campaign_Data["fdiv_width"];
	?>"  >Pixels</div>
								</td>
								</div>
							</tr></table></div></TD></tr>
							<tr>
								<TD colspan="3">
								<div id="background_color" style="display:none">
								<table width="100%">
								<tr>
								<TD align="right" width="30%">
									Background Color:
								</TD>
								<td>
									<input type="text" name="header_caption_color" size="35" class="inputtype" value="<?php echo $campaign_Data["fdiv_background_color"];?>">
									<span id="header_caption_color_span" style="background-color:<?php echo $campaign_Data['fdiv_background_color'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
									<a href="javascript:TCP.popup(document.campaign.elements['header_caption_color'],'header_caption_color_span')">
									<img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="colorpicker/img/sel.gif">
									</a>
								</td>
							</tr></table></div></TD></tr>
							<tr>
								<TD colspan="3">
								<div id="border" style="display:none">
								<table width="100%">
								<tr>
								<TD align="right" width="30%">
									Border Style:
								</TD>
								<td>
									<select name="border_style">
										<option value="none" <?php if ( $campaign_Data['fdiv_border_style'] == "none" ) echo "selected";?>>None</option>
										<option value="dotted" <?php if ( $campaign_Data['fdiv_border_style'] == "dotted" ) echo "selected";?>>Dotted</option>
										<option value="dashed" <?php if ( $campaign_Data['fdiv_border_style'] == "dashed" ) echo "selected";?>>Dashed</option>
										<option value="solid"  <?php if ( $campaign_Data['fdiv_border_style'] == "solid" ) echo "selected";?>>Solid</option>
										<option value="double" <?php if ( $campaign_Data['fdiv_border_style'] == "double" ) echo "selected";?>>Double</option>
										<option value="groove" <?php if ( $campaign_Data['fdiv_border_style'] == "groove" ) echo "selected";?>>Groove</option>
										<option value="ridge" <?php if ( $campaign_Data['fdiv_border_style'] == "ridge" ) echo "selected";?>>Ridge</option>
										<option value="inset" <?php if ( $campaign_Data['fdiv_border_style'] == "inset" ) echo "selected";?>>Inset</option>
										<option value="outset" <?php if ( $campaign_Data['fdiv_border_style'] == "outset" ) echo "selected";?>>Outset</option>
									</select>
								</td>
								</tr><tr><TD></TD></tr>
								
								<tr>
								<TD colspan="3">
								<div id="border_width" style="display:none">
								<table width="100%">
								<tr>
								<TD align="right" width="30%">
									Border Width:
								</TD>
								<td>
									<input type="radio" name="border_width" id="border_width" value="thin" checked="true" >Thin &nbsp;&nbsp;&nbsp;
									<input type="radio" name="border_width" id="border_width" value="medium"  <?php if ( $campaign_Data["fdiv_border_width"] == "medium" ) echo "checked";
	?> >Medium&nbsp;&nbsp;
									<input type="radio" name="border_width" id="border_width" value="thick"  <?php if ( $campaign_Data["fdiv_border_width"] == "thick" ) echo "checked";
	?> >Thick&nbsp;&nbsp;
								</td>
								</tr></table></div></TD></tr>
								
								<tr>
								<TD align="right" width="30%">
									Border Color:
								</TD>
								<td>
									
									<input type="text" name="border_caption_color" size="35" class="inputtype" value="<?php echo $campaign_Data["fdiv_border_color"];
	?>">
									
									<span id="border_caption_color_span" style="background-color:<?php echo $campaign_Data['fdiv_border_color'];
	?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
									
									<a href="javascript:TCP.popup(document.campaign.elements['border_caption_color'],'border_caption_color_span')">
									<img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="colorpicker/img/sel.gif">
									</a>
								</td>
								
							</tr></table></div></TD></tr>
							
							<tr>
								<TD colspan="2">
									<div id="background_upload_option" style="display:none;">
										<table width="100%">
											<tr>
												<TD align="right" width="30%">Set Background Image:</TD>
												<td>
													<input type="radio" name="background_default" id="background_default" value="D"  <?php if ( $campaign_Data["background"] != "" || $process == "edit" ) echo "checked";
	?> onclick="show_default_background_upload()">Default Image
													<input type="radio" name="background_default" id="background_default" value="N" <?php if ( $campaign_Data["background"] == "N" ) echo "checked";
	?> onclick="show_background_upload();">Upload New Image
												</td>
											</tr>
								
							<tr>
							<TD colspan="2">
						<div id="background_default_upload" style="display:none;">
				<table width="100%">
				<tr>
												<TD align="right" width="30%">From Gallery:</TD>
		<td>										<div>
													<input type="text" name="background" id="background" value="<?php echo $campaign_Data['background'];
	?>">
													<input type="button" name="show_default_background" value="Pick" onclick="show_default_backgroundphp(background);">
												</td>
											</tr>
										</table>
									</div>
									<div id="background_new_upload" style="display:none;">
					<table width="100%">
											<tr>
												<TD align="right" width="30%">Upload New:</TD>
												<td>
													<input type="file" name="background" id="background1" value="<?php echo $campaign_Data['background'];
	?>">
													<?php
	if ( isset( $background_error ) && $background_error != "" ) {
		echo "<span class='error'>" . $background_error . "</span>";
	} 

	?>
												</td>
											</tr>
										</table>
									</div>
								</TD>
							</tr>
							
							
					

						</table>
					</div>
				</TD>
			</tr>
							
							
							
			<tr>
				<TD colspan="2">
					<div id="corder">
						<table width="100%" align="center" border="0">
							<?php if ( $process == "edit" ) {
		?>
							<tr><TD colspan="2"><div id="show_image_div">
								<table width="100%" align="center" border="0">
									<TR>
										<TD align="right" width="30%">Small corner image</TD>
										<td>
											<?php echo $campaign_Data["small_corner_img"];
		?>&nbsp;
											<a  href="javascript:opens('flipped_images/<?php echo $campaign_Data["small_corner_img"];
		?>')" >Click to View</a>&nbsp;
											<a href="#" onclick="flipped()">Change</a>
										</td>
									</TR>
								</table>
							</div></TD></tr>
							<?php } 
	?>
							<tr>
								<TD colspan="2">
									<div id="flipped_upload_option">
										<table width="100%">
											<tr>
												<TD align="right" width="30%">Upload Corner Ads Image:</TD>
												<td>
													<input type="radio" name="flipped_default" id="flipped_default" value="D"  <?php if ( $campaign_Data["flipped_default"] == "D" ) echo "checked";
	?> onclick="show_default_flipped_upload()">Default Image
													<input type="radio" name="flipped_default" id="flipped_new" value="N" <?php if ( $campaign_Data["flipped_default"] == "N" ) echo "checked";
	?> onclick="show_flipped_upload();">Upload Flipped Image
												</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<TD colspan="2">
									<div id="flipped_default_upload" style="display:none;">
										<table width="100%">
											<tr>
												<TD align="right" width="30%">Upload Default:</TD>
												<td>
													<input type="text" name="small_corner_img" id="small_corner_img" value="<?php echo $campaign_Data['small_corner_img'];
	?>">
													<input type="button" name="show_default_flippedimg" value="Pick" onclick="show_default_flippedphp(small_corner_img);">
												</td>
											</tr>
										</table>
									</div>
									<div id="flipped_new_upload" style="display:none;">
										<table width="100%">
											<tr>
												<TD align="right" width="30%">Upload New:</TD>
												<td>
													<input type="file" name="small_corner_img" id="small_corner_img2" value="<?php echo $campaign_Data['small_corner_img'];
	?>">
													<br>Note : Please upload image with minimum of 800 hiegth 600 width.
													<?php
	if ( isset( $flipped_error ) && $flipped_error != "" ) {
		echo "<span class='error'>" . $flipped_error . "</span>";
	} 

	?>
												</td>
											</tr>
										</table>
									</div>
								</TD>
							</tr>
							<tr>
								<TD align="right" width="30%">
									Select Corner Position
								</TD>
								<td>
									<input type="radio" name="corner_position" id="corner_position_tl" value="tl" checked="true">Top Left<br>
									<input type="radio" name="corner_position" id="corner_position_tr" value="tr" <?php if ( $campaign_Data["corner_position"] == "tr" ) echo "checked";
	?>>Top Right<br>
									<input type="radio" name="corner_position" id="corner_position_bl" value="bl" <?php if ( $campaign_Data["corner_position"] == "bl" ) echo "checked";
	?>>Bottom Left<br>
									<input type="radio" name="corner_position" id="corner_position_br" value="br" <?php if ( $campaign_Data["corner_position"] == "br" ) echo "checked";
	?>>Bottom Right
								</td>
							</tr>
							<tr>
								<TD align="right">Play Sound?
								</TD>
								<Td>
									<input type="radio" name="play_sound" id="sound_no" value="N" onclick="playsound_no();" checked="true">No
									<input type="radio" name="play_sound" id="sound_yes" value="Y" <?php if ( $campaign_Data["play_sound"] == "Y" ) echo "checked";
	?> onclick="playsound_yes();">Yes
								</Td>
							</tr>
							<tr><TD colspan="2"><div id="show_sound_edit_div" style="display:none;">
								<table width="100%" align="center" border="0">
									<TR>
										<TD align="right" width="30%">Uploaded sound</TD>
										<td>
											<?php echo $sound_Data["original_name"];
	?>&nbsp;
											<a href="#" onclick="playsound_yes()">Change</a>
										</td>
									</TR>
								</table>
							</div></TD></tr>
							
							<tr>
								<Td colspan="2">
									<div id="sound_upload" style="display:none;">
										<table width="100%">
											<tr>
												<TD align="right" width="30%">Upload Sound File:</TD>
												<td>
													<input type="radio" name="sound_option" id="sound_default_option" value="D" <?php if ( $campaign_Data["sound_option"] == "D" ) echo "checked";
	?> onclick="show_default_sound_upload()">Default Sound
													<input type="radio" name="sound_option" id="sound_new_option" value="N" <?php if ( $campaign_Data["sound_option"] == "N" ) echo "checked";
	?> onclick="show_new_sound_upload();">Upload New Sound
												</td>
											</tr>
										</table>
									</div>
								</Td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="sound_default_upload" style="display:none;">
										<table width="100%">
											<tr>
												<TD align="right" width="30%">Upload Default:</TD>
												<td>
													<input type="input" name="original_name" id="default_sound_file" value="<?php echo $campaign_Data['original_name'];
	?>">
													<input type="button" name="soundbtn" onclick="show_sound_file(original_name);" value="Pick">
												</td>
											</tr>
										</table>
									</div>
									<div id="sound_new_upload" style="display:none;">
										<table width="100%" border="0">
											<tr>
												<TD align="right" width="30%">
													Upload Sound:(mp3 format)<br>
												</TD>
												<td>
													<input type="file" name="original_name" id="new_sound_file" size="40" value="<?php echo $campaign_Data['original_name'];
	?>">
													<?php
	if ( isset( $sound_error ) && $sound_error != "" ) {
		echo "<span class='error'>" . $sound_error . "</span>";
	} 

	?>
												</td>
											</tr>
											<tr>
												<TD align="right"><span class="optional_field">* </span>Title:</TD>
												<td><input type="text" name="title" value="<?php echo $campaign_Data['title'];
	?>"></td>
											</tr>
											<tr>
												<TD align="right"><span class="optional_field">* </span>Description:</TD>
												<td><textarea name="description" cols="30" rows="5"><?php echo $campaign_Data['description'];
	?></textarea></td>
											</tr>
										</table>
									</div>
								</TD>
							</tr>
							

						</table>
					</TR>
				</TD>
			</tr>

			<TR>
				<td colspan="2">
					<div id="action1">
						<table width="100%">
							<tr>
								<TD align="right" width="30%">Action:</TD>
									<td align="left" valign="middle">
										
										<input type="radio" name="on_action" id="action_f" value="F"  checked="true" >On Load
										<input type="radio" name="on_action" id="action_l" value="L" <?php if ( $campaign_Data["on_action"] == "L" ) {
		echo "checked";
	} 
	?>> When User Leaves the Page (traffic regeneration)	
									</td>
							</TR>
						</table>
					</div>
				</td>
			</tr>

			<tr>
				<TD align="right">
					<div id="url_unmandatory" style="display:none">
						<span class="optional_field">*</span>Url:
					</div>
					<div id="url_mandatory" style="display:block">Url:</div>
				</TD>
				<td><input type="text" name="url" value="<?php echo $campaign_Data['url'];?>" ></td>
			</tr>
			<tr>
				<TD align="right">
					Open Url in:	
				</TD>
				<td>
					<input type="radio" name="open_url" value="n" checked="true">New Window&nbsp;&nbsp;
					<input type="radio" name="open_url" value="s" <?php if ( $campaign_Data["open_url"] == "s" ) {
		echo "checked";
	} 
	?>>Same Window&nbsp;&nbsp;
				</td>
			</tr>

			<tr>
				<TD align="right">Display Mode?</TD>
				<td>
					<input type="radio" name="track_ad" value="N" checked="checked" <?php if ( $campaign_Data["track_ad"] == "N" ) echo "checked";?>>Always
					<input type="radio" name="track_ad" value="Y" <?php if ( $campaign_Data["track_ad"] == "Y" ) echo "checked";?>>Once Per Session
				</td>
			</tr>
			<input type="hidden" name="reset_css" value="0" />
			<tr>
				<TD align="right">Reset CSS styles</TD>
				<td>
					<input type="checkbox" name="reset_css" <?php if ($campaign_Data['reset_css'] == 1):?>checked='1'<?php endif;?> value="1">
				</td>
			</tr>			
			<input type="hidden" name="old_css" value="<?php echo $campaign_Data['reset_css'];?>">
			<?php
	if ( $process == "new" ) {

		?>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="submit" value="Submit"></TD>
					<input name="insert_campaign" type="hidden" value="Yes">
					<input  name="sound_id_hid" type="hidden" value="<?php echo $campaign_Data['sound_id_hid'];
		?>" id="sound_id_hid">
				</tr>
			 
			 <?php
	} elseif ( $process == "edit" ) {

		?>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="submit" value="Update"></TD>
					<input name="update_campaign" type="hidden" value="Yes">
					<input name="process" type="hidden" value="edit">
					<input name="campaign_id_hid" type="hidden" value="<?php echo $campaign_Data['id'];
		?>">
					<input  name="sound_id_hid" type="hidden" value="<?php echo $campaign_Data['sound_id'];
		?>" id="sound_id_hid">
					<input name="page" type="hidden" value="<?php echo $_GET['page'];
		?>">
				</tr>			 
			<?php
	} 

	?>
			 <TR>
				<td colspan="2">
					<div id="warn" style="display:none;">
						<table width="100%">
							<tr>
								<TD  align="left" width="30%" class='warn' colspan="2">
									<?php
	echo "*Warning: If you enter an URL in the field below, all ad types will be linked
											to this URL and will overwrite the links you use inside your ad
											content.To avoid this, create multiple campaigns.";
	echo "<br><br>";
	echo "*Warning: If you select On Load, or When User Leaves the Page, this setting
											will apply to all ad types running within this campaign.To avoid
											this, please create multiple campaigns.";

	?>
								</TD>
							</TR>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<TD  align="left" width="30%" class='error' colspan="2">
					<?php
	echo "File upload size should be less than " . $UPLOAD_LIMIT . " kb";
	echo "<br>";
	echo "Post form size should be less than " . $POST_MAX_LIMIT . " kb";

	?>
				</TD>
			</tr>
		</table>
	</form>
<?php
} //$process=="new" || $process == "edit"
if ( $process == "manage" && ( !isset( $campaign_Data ) ) ) {
	?>
<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
	<tr>
		<td valign = "top" align="center" class="heading"> <a  class="menu" href = "?process=new">Create New Campaign</a> &nbsp;|&nbsp; <a  class="menu" href = "?process=split">Manage Split Tests</a></td>
		
	</tr>
	<br>
	<tr>
					<td colspan="12">
						<?php $pg->showPagination();
	?>
					</td>
				</tr>
</table>
<br>
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
			<?php if ( $totalrecords > 0 ) {
		?>
				
			
			<?php } 
	?>

<tr  class="tableheading">
<th><a title = "Sort" class = "menu" href="?sort=id">Campaign #</a></th>
<th><a title = "Sort" class = "menu" href="?sort=campaign_name">Campaign name</a></th>
<th><a title = "Sort" class = "menu" href="?sort=start_date">Start date</a></th>
<th><a title = "Sort" class = "menu" href="?sort=end_date">End date</a></th>
<th><a title = "Sort" class = "menu" href="?sort=position">Ad type</a></th>
<th><a title = "Sort" class = "menu" href="?sort=on_action">On action</a></th>
<th><a title = "Sort" class = "menu" href="?sort=play_sound">Play sound</a></th>
<th><a title = "Sort" class = "menu" href="?sort=track_ad">Display mode</a></th>
<th><a title = "Sort" class = "menu" href="?sort=impression"># of impressions</a></th>
<th><a title = "Sort" class = "menu" href="?sort=clicks"># of clicks</a></th>
<th><a title = "Sort" class = "menu" href="?sort=effectiveness"># of effectiveness</a></th>
<th></th>
<th></th>
<th></th>
<th></th>
</tr>
<?php
	if ( $campaign_rs ) {
		$tblmat = 0;
		while( $campaign = $damp_db->getNextRow( $campaign_rs ) ) {
			if ( $campaign['position'] != 'C' && $campaign['position'] != 'S' && $campaign['position'] != 'F' ) {
				$data = $campaign['position'];
				$campaign_data = explode( "+", $data );
				$campaign_data['positionC'] = $campaign_data[0];
				$campaign_data['positionS'] = $campaign_data[1];
				$campaign_data['positionF'] = $campaign_data[2]; 
				// print_r($campaign['position']);
				$position = '';
				if ( $campaign_data["positionC"] == "C" ) {
					$position .= "Corner,";
				} 
				if ( $campaign_data["positionS"] == "S" ) {
					$position .= "Slide In,";
				} 
				if ( $campaign_data["positionF"] == "F" ) {
					$position .= "Fix Position";
				} 
			} else {
				$position = '';
				if ( $campaign['position'] == 'C' ) {
					$position = 'Corner';
				} elseif ( $campaign['position'] == 'S' )
					$position = "Slide In";

				elseif ( $campaign['position'] == 'F' )
					$position = "Fix Position"; 
				// echo $campaign_Data['positionC'].$campaign_Data['positionS'].$campaign_Data['positionF'];//exit;
			} 

			if ( $campaign["on_action"] == "L" ) $on_action = "Leaving the page";
			else if ( $campaign["on_action"] == "F" ) $on_action = "On load";

			if ( $campaign["play_sound"] == "Y" ) $play_sound = "Yes";
			else if ( $campaign["play_sound"] == "N" ) $play_sound = "No";
			if ( $campaign["track_ad"] == "Y" ) $track_ad = "Once";
			else if ( $campaign["track_ad"] == "N" ) $track_ad = "Always";
			$id = $campaign["id"];

			?>	
<tr  id="row<?php echo $id ?>"  class='<?php echo ( $tblmat++ % 2 ) ? "tablematter1" : "tablematter2" ?>' >
		<td align="center"><?php echo $id ?></td>
		<td align="left"><?php echo $campaign["campaign_name"];
			?></td>
		<td align="left" title="" ><?php if ( $campaign["start_date"] != "" ) {
				echo $campaign["start_date"];
			} else {
				echo "-";
			} 
			?></td>
		<td align="center" class="general"><?php if ( $campaign["end_date"] != "" ) {
				echo $campaign["end_date"];
			} else {
				echo "-";
			} 
			?></td>
		<td align="center" nowrap="true"><?php echo $position;
			?></td>
		<td align="center"><?php echo $on_action;
			?></td>
		<td align="center"><?php echo $play_sound;
			?></td>		
		<td align="center"><?php echo $track_ad;
			?></td>
        <td align="center">
		<?php if ( $campaign["impression"] > 0 ) {
				?>
		<a target="_blank" title="Click here for details" href="impressionreport.php?cid=<?php echo $id;
				?>">
		<?php } 
			?>
		
		<?php echo $campaign["impression"];
			?>
		<?php if ( $campaign["impression"] > 0 ) {
				?> </a> <?php } 
			?>		
		</td>
        <td align="center">
		<?php if ( $campaign["clicks"] > 0 ) {
				?>
		<a target="_blank" title="Click here for details" href="clicksreport.php?cid=<?php echo $id;
				?>">
		<?php } 
			?>
		
		<?php echo $campaign["clicks"];
			?>
		<?php if ( $campaign["clicks"] > 0 ) {
				?> </a> <?php } 
			?>		
		</td>
        <td align="center">
		<?php if ( $campaign["effectiveness"] > 0 ) {
				?>
		<a target="_blank" title="Click here for details" href="effectivenessreport.php?cid=<?php echo $id;
				?>">
		<?php } 
			?>
		
		<?php echo $campaign["effectiveness"];
			?>
		<?php if ( $campaign["effectiveness"] > 0 ) {
				?> </a> <?php } 
			?>		
		</td>
		
		<td>
		<a href="?process=edit&id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/edit.png" border="0" title="Edit" style="cursor:pointer">
		</a>
		</td>
		<td>
		<a onclick="javascript:return confirm('Do you want to duplicate this Campaign?');"  href="?process=confirmduplicate&id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/duplicate.png" border="0" title="Duplicate" style="cursor:pointer">
		</a>
		</td>
		<td>
		<a onclick="javascript:return confirm('Do you want to delete this Campaign?');"  href="?process=confirmdelete&id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/delete.png" border="0" title="Delete" style="cursor:pointer">
		</a>
		</td>
		<td>
		<img src="images/getcode.gif" border="0" title="Get code" style="cursor:pointer" alt="Get code" onclick="showcode('<?php echo $endec->encode( $id );
			?>','single')">
		</td>
	</tr>
	<tr>
	<TD colspan="15">
	
	</TD>
	</tr>
<?php } 
	} else {
		echo "<tr><td align='center' colspan='15'>No Campaign Found</td></tr>";
	} 

	?>	  
<tr ><td align='center' colspan='15'  class="heading">&nbsp;</td></tr>	  
</table>	
	
	
	

<?php
	if ( isset( $_GET["block_id"] ) && $_GET["block_id"] > 0 ) {
		echo '<script language="javascript">showdiv("' . $_GET["block_id"] . '")</script>';
	} 
} // end manage 	
if ( $process == "split" ) {

	?>
<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
	<tr>
		<td valign = "top" align="center" class="heading"><a  class="menu" href = "?process=new_split">Create New Split Tests</a></td>
		
	</tr>
	<br>
	<tr>
					<td colspan="12">
						<?php $pg->showPagination();
	?>
					</td>
				</tr>
</table>
<br>
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
			<?php if ( $totalrecords > 0 ) {
		?>
				
			
			<?php } 
	?>

<tr  class="tableheading">
<th><a title = "Sort" class = "menu" href="?sort=id&process=split">S.No.</a></th>
<th><a title = "Sort" class = "menu" href="?sort=test_name&process=split">Test Name</a></th>
<th><a title = "Sort" class = "menu" href="?sort=start_date&process=split">Campaigns Included</a></th>
<th><a title = "Sort" class = "menu" href="?sort=date_created&process=split">Date Created</a></th>
<th><a title = "Sort" class = "menu" href="?sort=isDuration&process=split">Split Mode</a></th>
<th><a title = "Sort" class = "menu" href="?sort=isRunning&process=split">Status</a></th>
<th></th>
<th></th>
<th></th>
<th></th>
<th></th>
</tr>
<?php
	if ( $split_test_rs ) {
		$tblmat = 0;
		while( $split_test_Data = $damp_db->getNextRow( $split_test_rs ) ) {
			$id = $split_test_Data["id"];

			if ( $split_test_Data['isDuration'] == 'Y' )
				$durationStatus = "Restricted";
			else
				$durationStatus = "Not Restricted";

			if ( $split_test_Data['isRunning'] == 'Y' )
				$runningStatus = "Running";
			else
				$runningStatus = "Completed"; 
			// /////////////////////////////////////////////////////////////////////////
			$campaign_rs = $campaign_obj->getCampaignbySplitId( $id ); 
			// /////////////////////////////////////////////////////////////////////////
			?>
		<tr  id="row<?php echo $id ?>"  class='<?php echo ( $tblmat++ % 2 ) ? "backcolor1" : "backcolor2" ?>' >
			<td align="center"><?php echo $split_test_Data["id"];
			?></td>
			<td align="left">
				<a href="#" onClick="hndlsr(<?php echo $id;
			?>); return false;">
					<?php echo $split_test_Data["test_name"] ?>
				</a>
			</td>
			<td align="left" title="" >
				
				<?php
			$count = 0;
			if ( $campaign_rs ) {
				while( $campaign_Data = $damp_db->getNextRow( $campaign_rs ) ) {
					$count++;
					if ( $count > 1 )
						echo ", ";
					echo $campaign_Data['campaign_name'];
				} 

				$damp_db->moveFirst( $campaign_rs );
			} else {
				echo "No Campaign in this split test";
			} 

			?>
			</td>
			<td align="left">
					<?php echo $split_test_Data['date_created'] ?>
			</td>
			<td align="left" nowrap="true">
					<?php echo $durationStatus ?>
			</td>
			<td align="left">
					<?php echo $runningStatus ?>
			</td>
			<td>
			<a href="?process=edit_split&id=<?php echo $id?>&page=<?php echo $page?>">
			<img src="images/edit.png" border="0" title="Edit" style="cursor:pointer">
			</a>
			</td>
			<td>
			<a onclick="javascript:return confirm('Do you want to duplicate this Campaign?');"  href="?process=confirmduplicatesplittest&id=<?php echo $id?>&page=<?php echo $page?>">
			<img src="images/duplicate.png" border="0" title="Duplicate" style="cursor:pointer">
			</a>
			</td>
			<td>
			<a onclick="javascript:return confirm('Do you want to delete this Campaign?');"  href="?process=confirmdeletesplittest&id=<?php echo $id?>&page=<?php echo $page?>">
			<img src="images/delete.png" border="0" title="Delete" style="cursor:pointer">
			</a>
			</td>
			<td>
			<?php if ( $split_test_Data['isRunning'] == 'Y' ) {
				?>
				<a onclick="javascript:return confirm('***********************************************************************\n\nDo you want to end this split test?\nIf yes then Campaign with highest CTR will be made as a winner \nalthough you can change it later by clicking on the winner image icon\n\n************************************************************************');"  href="?process=confirmendsplittest&id=<?php echo $id?>&page=<?php echo $page?>">
				<img src="images/end.png" border="0" title="End Split Test" style="cursor:pointer">
			</a>
			<?php } else {
				?>
							<a href="#" onClick="hndlsr(<?php echo $id;
				?>); return false;">
								<img src="images/denied.png" border="0" title="Split test is over and a winning campaign is now running.Click to view it!" style="cursor:pointer">
							</a>
				<?php } 
			?>
			</td>
			<td>
			<img src="images/getcode.gif" border="0" title="Get code" style="cursor:pointer" alt="Get code" onclick="showcode('<?php echo $endec->encode( $id );
			?>','split')">
			</td>
		</tr>
	<!-- 	/////////////////// Code for Inner Table starts here////////////////////////// -->
		<tr>
			<td colspan="7">
				<div class="noshow" id="ad<?php echo $id ?>">
					<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
						<tr  class="tableheading">
							<th width="16">&nbsp;ID&nbsp;</th>
							<th>Campaign Name</th>
							<th># of impressions</th>
							<th># of clicks</th>
							<th># of effectiveness [ CTR ]</th>
							<th colspan="6"></th>
						</tr>
					<?php 
			// ////// Calculating hiegest CTR campaign ////////////
			$ctrstr = "";
			while( $campaign_Data = $damp_db->getNextRow( $campaign_rs ) ) {
				if ( $campaign_Data['impression'] != 0 )
					$ctrstr .= round( $campaign_Data['clicks'] / $campaign_Data['impression'] * 100, 2 ) . " ";
				else
					$ctrarr[] = 0;
			} 
			$ctrarr = explode( " ", $ctrstr );
			rsort( $ctrarr );
			$hieghtest_CTR = $ctrarr[0];

			$damp_db->moveFirst( $campaign_rs ); 
			// ////// Code Ends Calculating hiegest CTR campaign ////////////
			$tblmatinner = 0;
			while( $campaign_Data = $damp_db->getNextRow( $campaign_rs ) ) {
				if ( $campaign_Data['impression'] != 0 )
					$ctr = round( $campaign_Data['clicks'] / $campaign_Data['impression'] * 100, 2 );
				else
					$ctr = 0; 
				// print_r($campaign_Data);
				?>
						<tr  id="row1<?php echo $id ?>"  class='<?php echo ( $tblmatinner++ % 2 ) ? "tablematter1" : "tablematter2" ?>' >
							<td align="center"><?php echo $campaign_Data['id']?></td>
							<td align="left"><?php echo $campaign_Data['campaign_name'];
				?></td>
							<td align="center"><?php echo $campaign_Data['impression']?></td>
							<td align="center"><?php echo $campaign_Data['clicks']?></td>		
							<td align="center"><?php echo $ctr . "%";
				?></td>
							<td width="16">
							<?php
				if ( $campaign_Data['isWinner'] == "Y" ) {

					?>
									<img src="images/winner.jpg" border="0" title="Winning Campaign" style="cursor:pointer">
							<?php
				} elseif ( $split_test_Data['isRunning'] == 'Y' && $ctr == $hieghtest_CTR ) {

					?>
									<a onclick="javascript:return confirm('Do you want to make this Campaign as a winner?');"  href="?process=confirmWinner&sid=<?php echo $split_test_Data["id"]?>&cid=<?php echo $campaign_Data["id"]?>&which=highest&page=<?php echo $page?>">
										<img src="images/winner1.gif" border="0" title="Click here to make this campaign as a winner">
									</a>
							<?php
				} else {

					?>
								<a onclick="javascript:return confirm('Do you want to make this Campaign as a winner?');"  href="?process=confirmWinner&sid=<?php echo $split_test_Data["id"]?>&cid=<?php echo $campaign_Data["id"]?>&which=other&page=<?php echo $page?>">						<img src="images/winner_bnw.jpg" border="0" title="Click here
									 to make this campaign as a winner" style="cursor:pointer">
								</a>
							<?php
				} 

				?>
							</td>
					
						</tr>
						<?php
			} //End of While Loop Campaign Data of click,impression of perticluler Split Test

			?>
					</table>
				</div>
			</td>
		</tr>	
	<!-- 	/////////////////// Code for Inner Table Ends here ////////////////////////// -->
			<?php
		} // End of While Loop of Split Test 
	} //End of If $split_test_rs

	?>

<!--	<tr>
	<TD colspan="14">
	
	</TD>
	</tr>-->

<tr ><td align='center' colspan='14'  class="heading">&nbsp;</td></tr>	  
</table>
<?php
} 
if ( $process == "new_split" || $process == "edit_split" ) {
	$campaign_Data = $campaign_obj->getAllCampaignName();

	if ( $process == "edit_split" ) {
		$split_test_Data = $campaign_obj->getSplitTestById( $_GET['id'] );

		$sql = "select campaign_id from " . TABLE_PREFIX . "split_campaign where split_test_id=" . $_GET['id'];
		$selectedCampaign_Data = $damp_db->getData( $sql ); 
		// print_r( $selectedCampaign_Data);
	} 
	// print_r($selectedCampaign_Data);
	// print_r($campaign_Data);
	?>
	<form name="campaign" action="campaign.php" method="POST" onsubmit="return validate_split_test_form(this)">
		<table width="80%" align="center" class="summary2" border='0'>
			<TR>
				<TD align="right" width="30%">
					Test Name:
				</TD>
				<Td align="left">
					<input type="text" name="test_name" id="test_name" value="<?php echo $split_test_Data["test_name"];
	?>">
				</Td>
			</TR>
			<TR>
				<TD align="right" width="30%">
					Campaigns:
				</TD>
				<Td align="left">
					<select name="S_campaign_list[]" id="S_campaign_list" multiple="true" size="4"> 
						<?php

	?>
						<option <?php if ( $process != "edit_split" ) {
		echo "selected";
	} 
	?> value="-1">Please Select One or More</option>
					<?php
	while( $campaign = $damp_db->getNextRow( $campaign_Data ) ) {
		echo "<option value=" . $campaign['id'];
		if ( $selectedCampaign_Data ) {
			foreach( $selectedCampaign_Data as $selectedCampaign ) {
				// print_r($selectedCampaign);
				if ( in_array( $campaign['id'], $selectedCampaign ) )
					echo " selected";
			} 
		} 
		echo ">" . $campaign['campaign_name'] . "</option>";
	} 

	?>
					</select>
				</Td>
			</TR>
			<tr>
				<TD align="right" width="30%">
					Do you want to limit split test duration?
				</TD>
				<td>
					<input type="checkbox" name="split_test_duration_checkbox" onclick="javascript:show_split_test_duration_option_div(this.checked)" value="Y"
						<?php if ( $split_test_Data['isDuration'] == "Y" ) {
		echo "checked ";
	} 

	?>
					>Yes
				</td>
			</tr>
			<tr>
				<TD colspan="2">
					<div id="split_test_duration_option_div" style="display:none">
						<table width="100%" align="center" border='0'>
							<TR>
								<TD align="right" width="30%">Select duration type:</TD>
								<td>
									<input type="radio" name="duration_days" id="duration_in_days" value="D" onclick="show_split_test_duration_div(this);" <?php if ( $split_test_Data['duration_type'] == "D" ) echo "checked";
	?>>In Days
									<input type="radio" name="duration_days" id="duration_in_hits" value="H" onclick="show_split_test_duration_div(this);" <?php if ( $split_test_Data['duration_type'] == "H" ) echo "checked";
	?>>In Hits
								</td>
							</TR>
						</table>
					</div>
				</TD>
			</tr>
			<tr>
				<TD colspan="2">
					<div id="split_test_duration_div_for_days" style="display:none">
						<table width="100%" align="center" border='0'>
							<TR>
								<TD align="right" width="30%">Duration:</TD>
								<td>
									<input type="input" name="spilt_duration_days_inputbox" id="spilt_duration" value="<?php if ( $split_test_Data['duration_type'] == "D" ) echo $split_test_Data['duration'];
	?>"> Days
								</td>
							</TR>
						</table>
					</div>
					<div id="split_test_duration_div_for_hits" style="display:none">
						<table width="100%" align="center" border='0'>
							<TR>
								<TD align="right" width="30%">No. of Hits:</TD>
								<td>
									<input type="input" name="spilt_duration_hits_inputbox" id="spilt_duration" value="<?php if ( $split_test_Data['duration_type'] == "H" ) echo $split_test_Data['duration'];
	?>"> Each Campaign
								</td>
							</TR>
						</table>
					</div>
				</TD>
			</tr>
			<tr><TD colspan="2">&nbsp;</TD></tr>
			
			<?php
	if ( $process == "edit_split" ) {

		?>
			<tr>
				<TD align="right" width="30%">&nbsp;</TD>
				<TD align="left">
					<input type="submit" name="edit" value="Update">
					<input type="hidden" name="update_test" value="Yes">
					<input type="hidden" name="tid" value="<?php echo $_GET['id'];
		?>">
				</TD>
				
			</tr>
			<?php
	} else {

		?>
			<tr>
				<TD align="right" width="30%">&nbsp;</TD>
				<TD align="left"><input type="submit" name="submit" value="Create"></TD>
				<input type="hidden" name="create_test" value="Yes">
			</tr>
			<?php
	} 

	?>
			
		</table>
	</form>
<?php
} 

?>		
<?php require_once( "incright.php" );
?>

<?php require_once( "incbottom.php" );
?>
<?php
if ( isset( $background_error ) && $background_error != "" ) {

	?>
<script language="JavaScript">
	hide_corner();
	{
	document.getElementById("positionS").checked=true;
	document.getElementById(id="url_mandatory").style.display='block';
	}
	
</script>
<?php
} 

?>
<?php
if ( isset( $flipped_error ) && $flipped_error != "" ) {

	?>
<script language="JavaScript">
	flipped();
	show_flipped_upload();
 	document.getElementById("action_f").checked=true;
 	document.getElementById("flipped_new").checked=true;
</script>
<?php
} 

?>

<?php
if ( isset( $sound_error ) && $sound_error != "" ) {

	?>
<script language="JavaScript">
	playsound_yes();
 	show_new_sound_upload();
  	document.getElementById("sound_yes").checked=true;
  	document.getElementById("sound_new_option").checked=true;
</script>
<?php
} 

?>
<?php 
// echo $_GET["process"];exit;
if ( $campaign_Data["on_action"] == "F" ) {
	echo "<script language='JavaScript'> flipped()</script>";
} 
if ( $campaign_Data["flipped_default"] == "D" ) {
	echo "<script language='JavaScript'> show_default_flipped_upload()</script>";
} elseif ( $campaign_Data["flipped_default"] == "N" ) {
	echo "<script language='JavaScript'> show_flipped_upload()</script>";
} 
if ( $campaign_Data["sound_option"] == "D" ) {
	echo "<script language='JavaScript'> show_default_sound_upload()</script>";
} elseif ( $campaign_Data["sound_option"] == "N" ) {
	echo "<script language='JavaScript'> show_new_sound_upload()</script>";
} 
if ( $campaign_Data["play_sound"] == "Y" ) {
	echo "<script language='JavaScript'> playsound_yes()</script>";
} 

if ( $_GET["process"] == "edit" || $process == "new" ) {
	echo "<script language='JavaScript'>";
	echo "var edit='Yes';";
	if ( $_GET["process"] == "edit" ) {
		echo "document.getElementById('show_image_div').style.display='block';";
		echo "document.getElementById('flipped_upload_option').style.display='none';";
	} 
	echo "document.getElementById('background_default_upload').style.display = 'block';"; 
	// echo "document.getElementById('background_new_upload').style.display = 'none';";
	echo "</script>";

	if ( $campaign_Data["play_sound"] == "Y" ) {
		echo "<script language='JavaScript'>";
		echo "document.getElementById('show_sound_edit_div').style.display='block';";
		echo "document.getElementById('sound_upload').style.display = 'none';";
		echo "document.getElementById('sound_new_upload').style.display = 'none';";
		echo "document.getElementById('sound_default_upload').style.display = 'none';";
		echo "</script>";
	} 

	if ( isset( $campaign_Data["position"] ) ) {
		echo "<script language='JavaScript'>";
		echo "checking();";
		echo "</script>";
	} 
	/*if($campaign_Data["positionF"]=="F")
	{
		echo "<script language='JavaScript'>";
		echo "hide_corner1();";
		echo "</script>";
	}*/

	if ( $campaign_Data["content_type"] == "T" ) {
		echo "<script language = 'JavaScript'>";
		echo "show_textarea();";
		echo "</script>";
	} 

	if ( $campaign_Data["content_type"] == "H" ) {
		echo "<script language = 'JavaScript'>";
		echo "show_html();";
		echo "</script>";
	} 
	if ( $campaign_Data["fix_cont_type"] == "T" ) {
		echo "<script language = 'JavaScript'>";
		echo "show_textarea_fix();";
		echo "</script>";
	} 

	if ( $campaign_Data["fix_cont_type"] == "H" ) {
		echo "<script language = 'JavaScript'>";
		echo "show_html_fix();";
		echo "</script>";
	} 

	if ( $campaign_Data["fdiv_height_type"] == "u" ) {
		echo "<script language = 'JavaScript'>";
		echo "show_height();";
		echo "</script>";
	} 

	if ( $campaign_Data["fdiv_width_type"] == "u" ) {
		echo "<script language = 'JavaScript'>";
		echo "show_width();";
		echo "</script>";
	} 

	if ( $campaign_Data["sdiv_pos_type"] == "u" ) {
		echo "<script language = 'JavaScript'>";
		echo "show_sheight();";
		echo "</script>";
	} 
} 
if ( $process == "new" ) { // echo"gdfgdfg";exit;
	echo "<script language='JavaScript'>";
	echo "var edit='No';";
	echo "</script>";
} 
if ( $process == "edit_split" ) {
	if ( $split_test_Data['isDuration'] == 'Y' ) {
		if ( $split_test_Data['duration_type'] == "D" ) {
			$assign = 'D';
		} else {
			$assign = 'H';
		} 
		echo "<script language='JavaScript'>";
		echo "show_split_test_duration_option_div(true);";
		echo "show_split_test_duration_div_for_value('$assign');";
		echo "</script>";
	} 
} 

?>