<?php
	session_start();

	require_once("config/config.php");
	require_once("classes/database.class.php");
	require_once("classes/common.class.php");
	require_once("classes/campaign.class.php");
	require_once("classes/sound.class.php");	
	require_once("classes/pagination.class.php");
	require_once("classes/search.class.php");	
	require_once("classes/en_decode.class.php");	
	
	$endec=new encode_decode();
	$damp_db = new Database();
	$common_obj = new Common();
	$campaign_obj = new Campaign();
	$sound_obj = new Sound();	
	$pg = new Pagination();
	$sc = new Search();
	$damp_db->openDB();
	
	$common_obj->checkSession();

	$process = $_POST['process'];

	if (isset($_GET["page"]))
	{
		$page = $_GET["page"];
		$_SESSION["cmppage"] = $_GET["page"];
	}
	else if  (isset($_POST["page"]))
	{
		$page = $_POST["page"];
		$_SESSION["cmppage"] = $_POST["page"];
	}
	else
	{
		$page = 1;
	}
	
	$ids = explode(',',$_POST['ids']);
	foreach ($ids as $i => $s)
	{
		$ids[$i] = $endec->decode($s);
	}
	
	if ($process == "manage")
	{
	
			$sql = "select count(*) from ".TABLE_PREFIX."adcampaigns where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
			$totalrecords = $damp_db->getDataSingleRecord($sql);
			if ($totalrecords>0)
			{
				$pg->setPagination($totalrecords);
			}
			else
			{
				$pg->startpos=0;
			}
			
			$order_sql = $sc->getOrderSql(array("id","campaign_name","start_date","end_date","position","on_action","play_sound","track_ad","clicks","impression","effectiveness"),"id");
				
			$sql = "select * from `".TABLE_PREFIX."adcampaigns` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid']." ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;
			
		
		
		$campaign_rs = $damp_db->getRS($sql);
	}	
	
	
	else if ($process=="split")
	{
 		$sql = "select count(*) from ".TABLE_PREFIX."split_test where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		
 		$totalrecords = $damp_db->getDataSingleRecord($sql);
 		if ($totalrecords>0)
 		{
 			$pg->setPagination($totalrecords);
 		}
 		else
 		{
 			$pg->startpos=0;
 		}
 		
 		$order_sql = $sc->getOrderSql(array("id","test_name","isDuration","date_created","duration_type","duration","isRunning"),"id");
 			
 		$sql = "select * from `".TABLE_PREFIX."split_test` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid']." ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;
 	
 		$split_test_rs = $damp_db->getRS($sql);
 		
	}	
?>

<script language="javascript">

function opens(path)
{
 window.open('view_image.php?imgpath='+path,'abc', 'height=1000,width=1000,menubar=no,toolbar=no,resizable=yes, scrollbars=yes');
}
function showcode(id,process)
{
//alert(process);
	openwindow= window.open("getcode.php?id="+id+"&process="+process, "GETCODE",
		"'status=0,scrollbars=1',width=700,height=325,resizable=1");
	
	openwindow.moveTo(50,50);
}

</script>

<?php
 if ($process=="manage"&&(!isset($campaign_Data)))
		{ ?>
<form action="" method="get" name="frmcampaign" >
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
			<?php if($totalrecords > 0) { ?>
				
			
			<?php } ?>

<tr  class="tableheading">
<th class = "menu">Campaign</th>
<th class = "menu">Campaign name</th>
<th class = "menu">Start date</th>
<th class = "menu">End date</th>
<th class = "menu">Ad type</th>
<th class = "menu">On action</th>
<th class = "menu">Play sound</th>
<th class = "menu">Mode</th>
<th class = "menu">Impressions</th>
<th class = "menu">Clicks</th>
<th class = "menu">Effectiveness</th>
<th><input name='chkall' type='checkbox' id='chkall' value='chkall' onClick='checkUncheckAll(this,"single");'></th>
</tr>
<?php
if ($campaign_rs)
{
	$tblmat=0;
	while($campaign = $damp_db->getNextRow($campaign_rs))
	{
		if($campaign['position']!='C' && $campaign['position']!='S' && $campaign['position']!='F')
		{
			$data=$campaign['position'];
			$campaign_data=explode("+",$data);
			$campaign_data['positionC']=$campaign_data[0];
			$campaign_data['positionS']=$campaign_data[1];
			$campaign_data['positionF']=$campaign_data[2];
			//print_r($campaign['position']);
			$position='';
			if ($campaign_data["positionC"]=="C") 
			{
			
				$position.= "Corner,"; 
			}
			if ($campaign_data["positionS"]=="S")
			{
			
				$position.= "Slide In,";  
			}
			if ($campaign_data["positionF"]=="F") 
			{
			
				$position .= "Fix Position";
			}
		}
		else
		{ 	$position='';
			if($campaign['position']=='C')
			{
				$position='Corner';
				
			}
			elseif($campaign['position']=='S')
			$position="Slide In";
			
			elseif($campaign['position']=='F')
			$position="Fix Position";
			
			//echo $campaign_Data['positionC'].$campaign_Data['positionS'].$campaign_Data['positionF'];//exit;
		}
		
		
		if ($campaign["on_action"]=="L") $on_action = "Leaving the page"; else if ($campaign["on_action"]=="F") $on_action = "On load";

		if ($campaign["play_sound"]=="Y") $play_sound = "Yes"; else if ($campaign["play_sound"]=="N") $play_sound = "No";
		if ($campaign["track_ad"]=="Y") $track_ad = "Once"; else if ($campaign["track_ad"]=="N") $track_ad = "Always";
		$id = $campaign["id"];
?>	
<tr  id="row<?php echo $id ?>"  class='<?php echo ($tblmat++%2) ? "tablematter1" : "tablematter2" ?>' >
		<td align="center"><?php echo $id ?></td>
		<td align="left"><?php echo $campaign["campaign_name"]; ?></td>
		<td align="left" title="" ><?php if($campaign["start_date"]!="") { echo $campaign["start_date"]; }
		else { echo "-";} ?></td>
		<td align="center" class="general"><?php if($campaign["end_date"]!="") { echo $campaign["end_date"]; }
		else { echo "-";} ?></td>
		<td align="center" nowrap="true"><?php echo $position; ?></td>
		<td align="center"><?php echo $on_action; ?></td>
		<td align="center"><?php echo $play_sound; ?></td>		
		<td align="center"><?php echo $track_ad; ?></td>
        <td align="center">
		<?php if ($campaign["impression"]>0) { ?>
		<a target="_blank" title="Click here for details" href="impressionreport.php?cid=<?php echo $id; ?>">
		<?php } ?>
		
		<?php echo $campaign["impression"]; ?>
		<?php if ($campaign["impression"]>0) { ?> </a> <?php } ?>		
		</td>
        <td align="center">
		<?php if ($campaign["clicks"]>0) { ?>
		<a target="_blank" title="Click here for details" href="clicksreport.php?cid=<?php echo $id; ?>">
		<?php } ?>
		
		<?php echo $campaign["clicks"]; ?>
		<?php if ($campaign["clicks"]>0) { ?> </a> <?php } ?>		
		</td>
        <td align="center">
		<?php if ($campaign["effectiveness"]>0) { ?>
		<a target="_blank" title="Click here for details" href="effectivenessreport.php?cid=<?php echo $id; ?>">
		<?php } ?>
		
		<?php echo $campaign["effectiveness"]; ?>
		<?php if ($campaign["effectiveness"]>0) { ?> </a> <?php } ?>		
		</td>		
		<td>
		<input name='chkselect[]' id='chkselect' type='checkbox' value='<?php echo $endec->encode($id); ?>' <?php if(in_array($id,$ids) && $process == 'manage'){echo "checked ";}?> onclick="get_damscode(this.form,'single');">
		</td>
	</tr>
	<tr>
	<TD colspan="15">
	
	</TD>
	</tr>
<?php	}
}	  
else
{
echo "<tr><td align='center' colspan='15'>No Campaign Found</td></tr>";
}
?>	  
<tr ><td align='center' colspan='15'  class="heading">&nbsp;</td></tr>	  
</table>	
</form>
<?php 
	if (isset($_GET["block_id"]) && $_GET["block_id"]>0)
	{
		echo '<script language="javascript">showdiv("'.$_GET["block_id"].'")</script>';
	}
} // end manage 	
if($process=="split")
{
?>
<form action="" method="get" name="frmcampaign" >
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
<tr  class="tableheading">
<th class = "menu">S.No.</th>
<th class = "menu">Test Name</th>
<th class = "menu">Campaigns Included</th>
<th class = "menu">Date Created</th>
<th class = "menu">Split Mode</th>
<th class = "menu">Status</th>
<th><input name='chkall' type='checkbox' id='chkall' value='chkall' onClick='checkUncheckAll(this,"split");'></th>
</tr>
<?php
	if($split_test_rs)
	{
		$tblmat=0;
		while($split_test_Data = $damp_db->getNextRow($split_test_rs))
		{
			$id = $split_test_Data["id"];
			
			if($split_test_Data['isDuration']=='Y')
				$durationStatus = "Restricted";
			else
				$durationStatus = "Not Restricted";
							
			if($split_test_Data['isRunning']=='Y')
				$runningStatus = "Running";
			else
				$runningStatus = "Completed";
		
		///////////////////////////////////////////////////////////////////////////
				$campaign_rs = $campaign_obj->getCampaignbySplitId($id);
		///////////////////////////////////////////////////////////////////////////
	?>
		<tr  id="row<?php echo $id ?>"  class='<?php echo ($tblmat++%2) ? "backcolor1" : "backcolor2" ?>' >
			<td align="center"><?php echo $split_test_Data["id"]; ?></td>
			<td align="left">
				<a href="#" onClick="hndlsr(<?php echo $id; ?>); return false;">
					<?php echo $split_test_Data["test_name"] ?>
				</a>
			</td>
			<td align="left" title="" >
				
				<?php
					$count=0;
					if($campaign_rs)
					{
						while($campaign_Data = $damp_db->getNextRow($campaign_rs))
						{
							$count++;
							if($count>1)
								echo ", ";
							echo $campaign_Data['campaign_name'];
						}
						
						$damp_db->moveFirst($campaign_rs);
					}
					else
					{
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
<input name='chkselect[]' id='chkselect' type='checkbox' value='<?php echo $endec->encode($id); ?>' <?php if(in_array($id,$ids) && $process == 'split'){echo "checked ";}?> onclick="get_damscode(this.form,'split');">
			
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
					
						//////// Calculating hiegest CTR campaign ////////////
						$ctrstr="";
						while($campaign_Data = $damp_db->getNextRow($campaign_rs))
						{
							if($campaign_Data['impression']!=0)
								$ctrstr.=round($campaign_Data['clicks']/$campaign_Data['impression']*100,2)." ";
							else
								$ctrarr[]=0;
						}
						$ctrarr = explode(" ",$ctrstr);
 						rsort($ctrarr);
 						$hieghtest_CTR = $ctrarr[0];
 						
						$damp_db->moveFirst($campaign_rs);
						
						//////// Code Ends Calculating hiegest CTR campaign ////////////
						$tblmatinner=0;
						while($campaign_Data = $damp_db->getNextRow($campaign_rs))
						{
							if($campaign_Data['impression']!=0)
								$ctr=round($campaign_Data['clicks']/$campaign_Data['impression']*100,2);
							else
								$ctr=0;
								
							//	print_r($campaign_Data);
					?>
						<tr  id="row1<?php echo $id ?>"  class='<?php echo ($tblmatinner++%2) ? "tablematter1" : "tablematter2" ?>' >
							<td align="center"><?php echo $campaign_Data['id']?></td>
							<td align="left"><?php echo $campaign_Data['campaign_name'];?></td>
							<td align="center"><?php echo $campaign_Data['impression']?></td>
							<td align="center"><?php echo $campaign_Data['clicks']?></td>		
							<td align="center"><?php echo $ctr."%";?></td>
							<td width="16">
							<?php
								if($campaign_Data['isWinner']=="Y")
								{
							?>
									<img src="images/winner.jpg" border="0" title="Winning Campaign" style="cursor:pointer">
							<?php
								}
								elseif($split_test_Data['isRunning']=='Y' && $ctr==$hieghtest_CTR)
								{
							?>
									<a onclick="javascript:return confirm('Do you want to make this Campaign as a winner?');"  href="?process=confirmWinner&sid=<?php echo $split_test_Data["id"]?>&cid=<?php echo $campaign_Data["id"]?>&which=highest&page=<?php echo $page?>">
										<img src="images/winner1.gif" border="0" title="Click here to make this campaign as a winner">
									</a>
							<?php
								}
								else
								{
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
						}//End of While Loop Campaign Data of click,impression of perticluler Split Test
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
</form>
<?php }?>		
