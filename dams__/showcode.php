<?php
	require_once("config/config.php");
	require_once("classes/database.class.php");
	require_once("classes/common.class.php");
	require_once("classes/campaign.class.php");
	require_once("classes/sound.class.php");
	//require_once("classes/admin.class.php");	
	
	$damp_db = new Database();
	$common_obj = new Common();
	$campaign_obj = new Campaign();
	$sound_obj = new Sound();
	//$admin_obj= new admin();
	

	$damp_db->openDB();
	//$admin_info = $admin_obj->ViewAdminSetting();

$today = date('Y-m-d');
$Ccheck=0;
$Scheck=0;
$Fcheck=0;
echo "<script language='JavaScript'>";
echo "var Scheck=false; var Fcheck=false; var Ccheck=false;";
echo "</script>";

require_once("classes/en_decode.class.php");	
$endec=new encode_decode();
$_GET["id"]=$endec->decode($_GET["id"]);

	if(isset($_GET['id']) && $_GET['id']!="")
	{
	
		//**************************** Code Start for getting campaign_id($id)*******************************
		//***************************************************************************************************
		
		// Coding by:- Mayank 
		// Date :- 11-12-2007Select * from `hct_dams_adcampaigns` where id= 
		
	 	//Here we are checking, are we using this code for split test or a single ad. If $_GET['process']=="single" then $_GET['id'] will be campaign_id and if $_GET['process']=="split" then $_GET['id'] will be split test id
	 
		if(isset($_GET['process']) && $_GET['process']=="single")
		{
			$id=$_GET['id'];
		}
		elseif(isset($_GET['process']) && $_GET['process']=="split")
		{
//			$id=$_GET['id'];
			
			$sql = "SELECT * FROM `".TABLE_PREFIX."split_test` WHERE id ='".$_GET['id']."'";

			$splitData = $damp_db->getDataSingleRow($sql);
			
			if($splitData['isRunning']=='Y') // If it is running then we will check all the other aspect otherwise will just show winning campaign.
			{
		
				if($splitData['isDuration']=='Y')
				{
					// There are two type of duration are defined 1)In days limit[D] 2)No. of hits[H]

					if($splitData['duration_type']=="D") // In Days, We will check is it running otherwise Winning campaign will be shown.
					{
					
						/*from old code*/
						if((strtotime($splitData['date_created'])+$splitData['duration']*60*60*24)>time())
						{
							//geting minimum shown campaign in this perticular split test.	
							
							$sql = "SELECT campaign_id FROM `".TABLE_PREFIX."split_campaign` WHERE split_test_id =".$_GET['id']." ORDER BY (round) LIMIT 0 , 1";
							
							$CampaignId = $damp_db->getDataSingleRow($sql);
							
							// Updating its round by 1. round means how many times it has been shown.
						
							$sql="UPDATE `".TABLE_PREFIX."split_campaign` SET `round` = `round`+1 WHERE `campaign_id` =".$CampaignId['campaign_id']." AND `split_test_id` =".$_GET['id'];
							$damp_db->modify($sql);
						
							// Assigning its value to $id for futher reference.
						
							$id=$CampaignId['campaign_id'];
						}
						else // We will make and show Winner campaign here
						{
							// This is the case where Split test has been expaired by date but this split test is not closed yet nor winning campaign has been declared. So we will do both the things here.
						
							$sql="UPDATE `".TABLE_PREFIX."split_test` set isRunning='N' where id =".$_GET['id']."";
							$damp_db->modify($sql);
							
							$campaignIdOfHieghtestCTR = $campaign_obj->getCampaignOfHightestCTR($_GET['id']);
							//Making this campaign winner in the split_campaign table for this perticlular split test
							
							$sql="UPDATE `".TABLE_PREFIX."split_campaign` SET `isWinner` = 'Y' WHERE `campaign_id` =".$campaignIdOfHieghtestCTR." AND `split_test_id` =".$_GET['id'];
							$damp_db->modify($sql);
							
							$id=$campaignIdOfHieghtestCTR;
						}
						/*from old code*/
					}
					elseif($splitData['duration_type']=="H") // In hits
					{

						$sql = "SELECT * FROM `".TABLE_PREFIX."split_campaign` WHERE split_test_id =".$_GET['id']." ORDER BY (campaign_id)";
						$CampaignIds = $damp_db->getData($sql);
						//print_r($CampaignIds);

						// get how many campaigns are less than $splitData['duration']
						$cam_array = array();
						foreach ($CampaignIds as $item)
						{
							if ($item['round'] < $splitData['duration'])
								array_push($cam_array, array($item['campaign_id'], $item['round']));
						}

						//print_r($cam_array);

						// no eligible campaigns
						if (count($cam_array) == 0)
						{
							//no ads
						}
						else
						{
							$selected = $cam_array[0][0];
					
							// Updating its round by 1. round means how many times it has been shown.
				
							$sql="UPDATE `".TABLE_PREFIX."split_campaign` SET `round` = `round`+1 WHERE `campaign_id` =".$selected." AND `split_test_id` =".$_GET['id'];
							$damp_db->modify($sql);
				
							// Assigning its value to $id for futher reference.
							$id=$selected;	
						}
					}
				}// End of isDuration 
				else
				{
					//geting minimum shown campaign in this perticular split test.	
					
					$sql = "SELECT campaign_id FROM `".TABLE_PREFIX."split_campaign` WHERE split_test_id =".$_GET['id']." ORDER BY (round) LIMIT 0 , 1";
					
					$CampaignId = $damp_db->getDataSingleRow($sql);
					
					// Updating its round by 1. round means how many times it has been shown.
				
					$sql="UPDATE `".TABLE_PREFIX."split_campaign` SET `round` = `round`+1 WHERE `campaign_id` =".$CampaignId['campaign_id']." AND `split_test_id` =".$_GET['id'];
					$damp_db->modify($sql);
				
					// Assigning its value to $id for futher reference.
				
					$id=$CampaignId['campaign_id'];				
				}
			}
			// End of isRunning
		
			elseif($splitData['isRunning']=='N')
			{ 
				$sql = "SELECT campaign_id FROM `".TABLE_PREFIX."split_campaign` WHERE split_test_id =".$_GET['id']." and isWinner='Y' LIMIT 0 , 1";
	
				$CampaignId = $damp_db->getDataSingleRow($sql);

				$id=$CampaignId['campaign_id'];
				
			}
		}
		//*************************** Code Ends for getting campaign_id ($id )*******************************
		//***************************************************************************************************

		// lostarchives - 7:03 PM 1/20/2009
		if (isset($id) && ($id !=""))
		{
			$campaign_Data = $campaign_obj->getCampaignById($id);
			//print_r($campaign_Data);die();

			if($campaign_Data['position']!='C' && $campaign_Data['position']!='S' && $campaign_Data['position']!='F')
			{
					$data=$campaign_Data['position'];
				$campaign=explode("+",$data);
				$campaign_Data['positionC']=$campaign[0];
				$campaign_Data['positionS']=$campaign[1];
				$campaign_Data['positionF']=$campaign[2];
				//print_r($campaign);
			}
			else
			{ //echo "rahul";
				if($campaign_Data['position']=='C')
				$campaign_Data['positionC']=$campaign_Data['position'];
				if($campaign_Data['position']=='S')
				$campaign_Data['positionS']=$campaign_Data['position'];
				if($campaign_Data['position']=='F')
				$campaign_Data['positionF']=$campaign_Data['position'];
				//echo $campaign_Data['positionC'].$campaign_Data['positionS'].$campaign_Data['positionF'];exit;
			}
		}
	}
	
	if($campaign_Data)
	{
			$today = date("Y-m-d");

			if(($campaign_Data["start_date"]<=$today &&  $campaign_Data["end_date"]>=$today))
			{
					
						$backgroundimg=BASEPATH."background_images/".$campaign_Data["background"];
						if($campaign_Data["url"]!="")
						{
							$redirectUrl = $campaign_Data["url"];
						}
						else
						{
							$redirectUrl="";
						}
				

							
							//START positionS
							if($campaign_Data["positionS"]=="S")
							{ 
								echo "<script language='JavaScript'>";
								echo "var Scheck=true;";
								echo "</script>";
								$Scheck=1; 
								$link = $campaign_obj->changeLinksWithTrackURLs($campaign_Data["contents"],$campaign_Data["id"],"Y"); 
								
								$link=html_entity_decode($link);
								
								

								  $str = preg_replace("/(\r\n|\n|\r)/", "", $link);
								  $link= preg_replace("=<br */?>=i", "\n", $str);
								
	  
								 

								$link1= strtolower($link);
								$link2 = str_replace("'",'"',$link1);
								
								$spartstart = '<form';//for original
								$spartend = '>';
								
								$start='action="';
								$end='"';
								
								
								
								preg_match_all("|(".$spartstart."(.*)".$spartend.")|U",$link2, $out1);
								
								$out=str_replace(" ","",$out1[2][0]);
//								echo $out;  die();
								preg_match_all("|(".$start."(.*)".$end.")|U",$out, $out2);
								
								
								if(isset($out2[2][0]) && $out2[2][0]!="")
								{
									
									$action=$out2[2][0];
									
									
									$setaction=1;//echo html_entity_decode($content) ;
								}
								else
								{//echo "is ke andar aaya"; die();
								//echo html_entity_decode($link);
									$tracking_url = BASEPATH."tracking.php?id=".$campaign_Data["id"]."&ref_url=".$_GET['ref_url']."&php_self=".$_GET['php_self']."&redirectUrl=".$redirectUrl;
							
								}
								
							?>
								
								<SCRIPT src="<?php echo BASEPATH;?>jscripts/hoverpop.js" type=text/javascript></SCRIPT>
								<script language="javascript" type="text/javascript">
								function redirect_s(tar)
								{
									if(tar=='n')
										window.open("<?php echo $tracking_url; ?>","_blank");
									else
										window.open("<?php echo $tracking_url; ?>","_self");
									
								}
								</script>
								<?php
								if($setaction==1)
								{
									$campaign_Data["url"]="";
								}
								?>
								
								<DIV id="dropin" style="VISIBILITY: hidden; MARGIN: auto; WIDTH: auto; HEIGHT: AUTO; POSITION: absolute; TOP: 100px">
								
									<DIV id=tbl style="WIDTH: auto; height:auto; background-repeat:repeat-x;  background-image: url(<?php echo $backgroundimg;?>); BACKGROUND-COLOR: <?php echo $campaign_Data["fdiv_background_color"];?>;">
										<DIV id=dragtext style="<?php
												if($campaign_Data["fdiv_width_type"]=='d')
												{	
												?>
												WIDTH: auto;
												<?php
												}
												if($campaign_Data["fdiv_width_type"]=='u') {
												?>
												<??>width: <?php echo $campaign_Data["fdiv_width"];?>px;
												
												/*overflow:scroll;*/
												
												<?php
												}
												?> 
												<?php
												if($campaign_Data["fdiv_height_type"]=='a')
												{	
												?>
												HEIGHT:auto;
												<?php
												}
												if($campaign_Data["fdiv_height_type"]=='u') {
												?>
												HEIGHT: <?php echo $campaign_Data["fdiv_height"];?>px;
												
												/*overflow:scroll;*/
												
												<?php
												}
												?>
													BORDER-RIGHT: <?php echo $campaign_Data["fdiv_border_color"];?> <?php echo $campaign_Data["fdiv_border_width"];?> <?php echo $campaign_Data["fdiv_border_style"];?>; 
													PADDING-RIGHT: 10px; 
													BORDER-TOP: <?php echo $campaign_Data["fdiv_border_color"];?> <?php echo $campaign_Data["fdiv_border_width"];?> <?php echo $campaign_Data["fdiv_border_style"];?>; 
													PADDING-LEFT: 10px; FONT-SIZE: 10pt; 
													PADDING-BOTTOM: 10px; 
													BORDER-LEFT: <?php echo $campaign_Data["fdiv_border_color"];?> <?php echo $campaign_Data["fdiv_border_width"];?> <?php echo $campaign_Data["fdiv_border_style"];?>; 
													COLOR: #000000; 
													PADDING-TOP: 10px; 
													BORDER-BOTTOM: <?php echo $campaign_Data["fdiv_border_color"];?> <?php echo $campaign_Data["fdiv_border_width"];?> <?php echo $campaign_Data["fdiv_border_style"];?>; 
													BACKGROUND-COLOR: <?php echo $campaign_Data["fdiv_background_color"];?>;
													FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;" valign="top">
															<!--This content gets replaced with the content in the div 
															below that has the ID "hover_content"-->
										</DIV>
									</DIV>
								</DIV>
								
								
								
								<DIV id="hover_content" style="DISPLAY: none; VISIBILITY: hidden; BACKGROUND-COLOR: #ffffff">
								
									<DIV align=right>
										<A style="FONT-SIZE: 10px; FONT-FAMILY: verdana;  cursor:pointer" onclick="dismissbox();return false" title="Close X">Close X</A>
									</DIV>
									
									<div <?if($campaign_Data["url"]!="http://" && $campaign_Data["url"]!=NULL):?>onclick="redirect_s('<?php echo $campaign_Data['open_url'];?>');" <?endif;?>
									style="<?php
												if($campaign_Data["fdiv_width_type"]=='d')
												{	
												?>
												WIDTH: auto;
												<?php
												}
												if($campaign_Data["fdiv_width_type"]=='u') {
												?>
												<??>width: <?php echo $campaign_Data["fdiv_width"];?>px;
												
												<?php
												}
												?> 
												<?php
												if($campaign_Data["fdiv_height_type"]=='a')
												{	
												?>
												HEIGHT:auto;
												<?php
												}
												if($campaign_Data["fdiv_height_type"]=='u') {
												?>
												HEIGHT: <?php echo $campaign_Data["fdiv_height"];?>px;
												
												<?php
												}
												?>
												"
									>	
										<BR><BR>
										<?php if($setaction!=1)
										{ 
											if($campaign_Data["url"]!="http://" && $campaign_Data["url"]!=NULL)
												{ 
											?>
												 <a style="FONT-SIZE:15px;color: #000000;text-decoration: none; FONT-FAMILY: verdana;" <?php if($campaign_Data['open_url']=="n") echo "target='_blank'"; ?> href="<?php echo $tracking_url; ?>">
												 <?php
													$cont=html_entity_decode($campaign_Data["contents"]);
												
														$cont1=eregi_replace("<a[^>]*>","",$cont);
														$cont1=eregi_replace("</a[^>]*>","",$cont1);
														echo html_entity_decode($cont1);
														
												}
												else
												{	//echo $link;
											
													echo html_entity_decode($link);
												}
												?></a>
   								  <?php }
   								  		else
   								  		{
											//echo $link; die();
   								  			echo html_entity_decode($link);
   								  		}
   								  	?>
									</div>	
								</DIV><!-- End Hover Ad Content -->
								<DIV style="Z-INDEX: 100; WIDTH: 99%; POSITION: absolute; TOP: 0px" align=right>
								</DIV>
								<?php 
									if($campaign_Data["on_action"]=="F")
									{ 
										if($campaign_Data['sdiv_pos_type']=="d")
										{
											$lpos="75";
										}
										elseif($campaign_Data['sdiv_pos_type']=="u")
										{
											$lpos=$campaign_Data['sdiv_pos'];
										}
										
									?>
								<script language="javascript" type="text/javascript" src="<?php echo BASEPATH;?>jscripts/cookie.js"></script>
								<SCRIPT language=JavaScript type=text/javascript>
								
										//alert(readCookie('tracking<?php echo $campaign_Data['id']; ?>'));
										if(<?php echo ($campaign_Data['track_ad']=="Y") ? "false":"true";?> || readCookie('tracking<?php echo $campaign_Data['id']; ?>')!= <?php echo $campaign_Data["id"] ?>)
										{
											var div_elem = document.getElementById('hover_content')
											
											DropIn('<?php echo $lpos;?>','','Microsoft Sans Serif',14,'',div_elem.innerHTML,'75','300','#0000FF','solid',0,0,true);
											
											<?php
												$campaign_obj->updateImpression($id); //Impression++ for this Ad in campaign table.
												$campaign_obj->insertImpression($id,$_GET['ref_url'],$_GET['php_self']);

											?>
										}
										</SCRIPT>
									<NOSCRIPT></NOSCRIPT>
									<?php
									} 						  	

							}//End $campaign_Data["positionS"]=="S"
						  	
						  	
						  	//START PIOSITION FIX
						  	if($campaign_Data["positionF"]=="F")
						  	{ 
						  		echo "<script language='JavaScript'>";
								echo " var Fcheck=true;";
								echo "</script>"; $Fcheck=1;
								$link = $campaign_obj->changeLinksWithTrackURLs($campaign_Data["fix_contents"],$campaign_Data["id"],"Y");
								
								$fix_content = html_entity_decode($link);
								
								$str = preg_replace("/(\r\n|\n|\r)/", "", $fix_content);
								$fix_content= preg_replace("=<br */?>=i", "\n", $str);
								
								$fix_content=html_entity_decode($fix_content);
								//echo $fix_content; die();
								$fix_content1= strtolower($fix_content);
								$fix_content2 = str_replace("'",'"',$fix_content1);
								//echo $link2; die();
								$spartstart1 = '<form';//for original
								$spartend1 = '>';
								
								$start1='action="';
								$end1='"';
								
															
								preg_match_all("|(".$spartstart1."(.*)".$spartend1.")|U",$fix_content2, $out_1);
								
								$out=str_replace(" ","",$out_1[2][0]);
//								echo $out;  die();
								preg_match_all("|(".$start1."(.*)".$end1.")|U",$out, $out_2);
								
								
								if(isset($out_2[2][0]) && $out_2[2][0]!="")
								{

									$fix_action=$out_2[2][0];
									

									$fix_setaction=1;//echo html_entity_decode($content) ;
								}
								else
								{
									$fix_tracking_url = BASEPATH."tracking.php?id=".$campaign_Data["id"]."&ref_url=".$_GET['ref_url']."&php_self=".$_GET['php_self']."&redirectUrl=".$redirectUrl;
							
								}
								//echo $link;
								?>
								
								
								
								<script language="javascript" type="text/javascript" src="<?php echo BASEPATH;?>jscripts/cookie.js"></script>
								
								<script>
									function pid_hide()
									{
										document.getElementById("mini-nav").style.display="none";
									}
								</script>
									
									<?php 
									
									if($campaign_Data["on_action"]=="F" || $campaign_Data["on_action"]=="L" )
									{
										if($campaign_Data["fix_position"]=='T')
										{
											if($campaign_Data["floating"]=='N')
											{
									?>
												<style>
												#mini-nav{
												float: top;
												position:fixed;
												<?php
												if($campaign_Data["fdiv_width_type"]=='d')
												{	
												?>
												WIDTH: 100%;
												<?php
												}
												if($campaign_Data["fdiv_width_type"]=='u') {
												?>
												width: <?php echo $campaign_Data["fdiv_width"];?>px;
												/*overflow:scroll;*/
												
												<?php
												}
												?>
												<?php
												if($campaign_Data["fdiv_height_type"]=='a')
												{	
												?>
												HEIGHT:auto;
												<?php
												}
												if($campaign_Data["fdiv_height_type"]=='u') {
												?>
												HEIGHT: <?php echo $campaign_Data["fdiv_height"];?>px;
												/*overflow:scroll;*/
												
												<?php
												}
												?>
												margin-left : 0px;
												margin-top : 0px;
												top:0px;
												background-image: url(<?php echo $backgroundimg;?>);
												padding-TOP:0%;
												padding-bottom:1% ;
												PADDING-RIGHT:0%;
												PADDING-LEFT:0%;
												background-color:<?php echo $campaign_Data["fdiv_background_color"];?>;
												
												BORDER-bottom: <?php echo $campaign_Data["fdiv_border_color"];?> <?php echo $campaign_Data["fdiv_border_width"];?> <?php echo $campaign_Data["fdiv_border_style"];?>;
												text-align:left;
												vertical-align: text-top;
												z-index: 100;
												display: inline-table; 
												letter-spacing: normal; 
												cursor: default;
												}
												</style>
												<script language="javascript" type="text/javascript">
												function redirect(tar)
												{
													if(tar=='n')
														window.open("<?php echo $fix_tracking_url; ?>","_blank");
													else
														window.open("<?php echo $fix_tracking_url; ?>","_self");
												}
												</script>
												
												
													<?php
													if($fix_setaction==1)
													{
														$campaign_Data["url"]="";
													}
													if($campaign_Data["url"]!="http://" && $campaign_Data["url"]!=NULL)
													{
													?>
													<div style="display:none;" id="mini-nav">
													<?php
													}else
													{
													?>
													<div id="mini-nav" style="display:none;">
													<?php
													}
													?>
													<div   align="right">
														<a style="FONT-SIZE: 12px;font-weight:bold; FONT-FAMILY: verdana;color:#FF0522; cursor:pointer" title="Close Window" onclick="javascript:pid_hide();">Close X</a>
													</div>
													<div onclick="redirect('<?php echo $campaign_Data['open_url'];?>');" style="FONT-SIZE:15px;color: #000000;text-decoration: none; FONT-FAMILY: verdana;">
														<?php if($fix_setaction!=1)
															  { 
																if($campaign_Data["url"]!="http://" && $campaign_Data["url"]!=NULL)
																{
																	?> <a style="FONT-SIZE:15px;color: #000000;text-decoration: none; FONT-FAMILY: verdana;" <?php if($campaign_Data['open_url']=="n"){?> target="_blank"<?php }?> href="<?php echo $fix_tracking_url; ?>">
																	<?php
																			//print_r($campaign_Data);
																			$cont=html_entity_decode($campaign_Data["fix_contents"]);//

																			$cont1=eregi_replace("<a[^>]*>","",$cont);
																			$cont1=eregi_replace("</a[^>]*>","",$cont1);
																			echo html_entity_decode($cont1);
																}
																else
																{
																	echo html_entity_decode($link);
																}
																?></a>
															<?php
															  }
															  else
															  {
															  	echo html_entity_decode($fix_content);
															  }
															  ?>
													</div></div>
													<script language="JavaScript">
														function showfix_ad()
														{
															document.getElementById("mini-nav").style.display="block";
														}
													</script>
													<script language="javascript" type="text/javascript" src="<?php echo BASEPATH;?>jscripts/cookie.js"></script>
													<SCRIPT language=JavaScript type=text/javascript>
													if(<?php echo ($campaign_Data['track_ad']=="Y") ? "false":"true";?> || readCookie('tracking<?php echo $campaign_Data['id']; ?>')!= <?php echo $campaign_Data["id"] ?>)
													{
														<?if ($campaign_Data["on_action"] == "F"):?>
														showfix_ad();
														<?endif;?>
														<?php
															$campaign_obj->updateImpression($id); //Impression++ for this Ad in campaign table.
															$campaign_obj->insertImpression($id,$_GET['ref_url'],$_GET['php_self']);
														?>
													}
													</SCRIPT>
													<NOSCRIPT></NOSCRIPT>
														
													
										<?php										
											}
											elseif($campaign_Data["floating"]=='Y')
											{ 
										?>
											<script language='JavaScript'>
												var verticalpos="fromtop";
											</script>
											<script language='JavaScript' src='<?php echo BASEPATH;?>jscripts/floatingbar.js'>
												
											</script>
												<script language="JavaScript">
												if (window.addEventListener)
												window.addEventListener("load", staticbar, false)
												else if (window.attachEvent)
												window.attachEvent("onload", staticbar)
												else if (document.getElementById)
												window.onload=staticbar
												</script>
												
												<style>
												#topbar
												{
													position:absolute;
													border: 1px solid rgb(0, 0, 0);
													color:#CCCCCC;
													background-color: #FFFFFF;
													visibility: hidden;
													z-index: 1;
													
													<?php
													if($campaign_Data["fdiv_width_type"]=='d')
													{	
													?>
													WIDTH: 100%;
													<?php
													}
													if($campaign_Data["fdiv_width_type"]=='u') {
													?>
													width: <?php echo $campaign_Data["fdiv_width"];?>px;
													/*overflow:scroll;*/
													
													<?php
													}
													?>
													<?php
													if($campaign_Data["fdiv_height_type"]=='a')
													{	
													?>
													HEIGHT:auto;
													<?php
													}
													if($campaign_Data["fdiv_height_type"]=='u') {
													?>
													HEIGHT: <?php echo $campaign_Data["fdiv_height"];?>px;
													/*overflow:scroll;*/
													
													<?php
													}
	// 												?>
													margin-left : 0px;
													margin-top:0px;
													BORDER-bottom: <?php echo $campaign_Data["fdiv_border_color"];?> <?php echo $campaign_Data["fdiv_border_width"];?> <?php echo $campaign_Data["fdiv_border_style"];?>;
													background-color:<?php echo $campaign_Data["fdiv_background_color"];?>;
													background-image: url(<?php echo $backgroundimg;?>);
													padding-TOP:0%;
													padding-bottom:0% ;
													PADDING-RIGHT:0%;
													PADDING-LEFT:0%;
													text-align:left;
													vertical-align: text-top;
													
													display: inline-table; 
													letter-spacing: normal; 
													cursor: default;
												}</style>	
												<script language="javascript" type="text/javascript">
												function redirect(tar)
												{
													if(tar=='n')
														window.open("<?php echo $fix_tracking_url; ?>","_blank");
													else
														window.open("<?php echo $fix_tracking_url; ?>","_self");
												}
												</script>
												
												
													<?php
													if($fix_setaction==1)
													{
														$campaign_Data["url"]="";
													}
													if($campaign_Data["url"]!="http://" && $campaign_Data["url"]!=NULL)
													{
													?>
													<div style="display:none;" id="topbar">
													<?php
													}else
													{
													?>
													<div style="display: none; " id="topbar">
													<?php
													}
													?>
													<div   align="right">
														<a style="FONT-SIZE: 12px;font-weight:bold; FONT-FAMILY: verdana;color:#FF0522; cursor:pointer" title="Close Window" onclick="javascript:closebar();">Close X</a>
													</div>
													<div  style="FONT-SIZE:15px;color: #000000;text-decoration: none; FONT-FAMILY: verdana;">
														<?php if($fix_setaction!=1)
															  { 
																if($campaign_Data["url"]!="http://" && $campaign_Data["url"]!=NULL)
																{ 
																	?> <a style="FONT-SIZE:15px;color: #000000;text-decoration: none; FONT-FAMILY: verdana;" <?php if($campaign_Data['open_url']=="n"){?> target="_blank"<?php }?> href="<?php echo $fix_tracking_url; ?>">																	<?php
																			$cont=html_entity_decode($campaign_Data["fix_contents"]);
										
																			$cont1=eregi_replace("<a[^>]*>","",$cont);
																			$cont1=eregi_replace("</a[^>]*>","",$cont1);
																			echo html_entity_decode($cont1);
																	?>
																	</a><?php
																}
																else echo html_entity_decode($link);
															  }
															  else echo html_entity_decode($fix_content);
															?>
													</div></div>
													<script language="JavaScript">
														function showfix_ad()
														{
															document.getElementById("topbar").style.display="block";
														}
													</script>
													<script language="javascript" type="text/javascript" src="<?php echo BASEPATH;?>jscripts/cookie.js"></script>
													<SCRIPT language=JavaScript type=text/javascript>
													if(<?php echo ($campaign_Data['track_ad']=="Y") ? "false":"true";?> || readCookie('tracking<?php echo $campaign_Data['id']; ?>')!= <?php echo $campaign_Data["id"] ?>)
													{
														<?if ($campaign_Data["on_action"] == "F"):?>
														showfix_ad();
														<?endif;?>
														<?php
															$campaign_obj->updateImpression($id); //Impression++ for this Ad in campaign table.
															$campaign_obj->insertImpression($id,$_GET['ref_url'],$_GET['php_self']);
														?>
													}
													</SCRIPT>
													<NOSCRIPT></NOSCRIPT>
										<?php	
											}
										
										?>
							
														
											
													
										<?php
										
										}//ENDIF Fix_position"]=='T'
							
										if($campaign_Data["fix_position"]=="B")
										{ 
											if($campaign_Data["floating"]=='N')
											{
										?>
											<style>
												#mini-nav{
												float: bottom;
												position:fixed;
												<?php
												if($campaign_Data["fdiv_width_type"]=='d')
												{	
												?>
												WIDTH: 100%;
												<?php
												}
												if($campaign_Data["fdiv_width_type"]=='u') {
												?>
												width: <?php echo $campaign_Data["fdiv_width"];?>px;
												/*overflow:scroll;*/
												
												<?php
												}
												?>
												<?php
												if($campaign_Data["fdiv_height_type"]=='a')
												{	
												?>
												HEIGHT:auto;
												<?php
												}
												if($campaign_Data["fdiv_height_type"]=='u') {
												?>
												HEIGHT: <?php echo $campaign_Data["fdiv_height"];?>px;
												/*overflow:scroll;*/
												
												<?php
												}
// 												?>
												margin-left : 0px;
												margin-top : 0px;
												bottom:0px;
												background-image: url(<?php echo $backgroundimg;?>);
												padding-TOP:0%;
												padding-bottom:1% ;
												PADDING-RIGHT:0%;
												PADDING-LEFT:0%;
												background-color:<?php echo $campaign_Data["fdiv_background_color"];?>;
												
												BORDER-top: <?php echo $campaign_Data["fdiv_border_color"];?> <?php echo $campaign_Data["fdiv_border_width"];?> <?php echo $campaign_Data["fdiv_border_style"];?>;
												text-align:left;
												vertical-align: text-top;
												z-index: 100;
												display: inline-table; 
												letter-spacing: normal; 
												cursor: default; 
												}
												</style>

													<script language="javascript" type="text/javascript">
												function redirect(tar)
												{
													if(tar=='n')
														window.open("<?php echo $fix_tracking_url; ?>","_blank");
													else
														window.open("<?php echo $fix_tracking_url; ?>","_self");
												}
												</script>
												
												
													<?php
													if($fix_setaction==1)
													{
														$campaign_Data["url"]="";
													}
													if($campaign_Data["url"]!="http://" && $campaign_Data["url"]!=NULL)
													{
													?>
													<div style="display:none;" id="mini-nav">
													<?php
													}else
													{
													?>
													<div id="mini-nav" style="display:none;">
													<?php
													}
													?>
													<div   align="right">
														<a style="FONT-SIZE: 12px;font-weight:bold; FONT-FAMILY: verdana;color:#FF0522; cursor:pointer" title="Close Window"  onclick="javascript:pid_hide();">Close X</a>
													</div>
													<div onclick="redirect('<?php echo $campaign_Data['open_url'];?>');" style="FONT-SIZE:15px;color: #000000;text-decoration: none; FONT-FAMILY: verdana;">
														<?php if($fix_setaction!=1)
															  {
																if($campaign_Data["url"]!="http://"  && $campaign_Data["url"]!=NULL)
																{
																	?> <a style="FONT-SIZE:15px;color: #000000;text-decoration: none; FONT-FAMILY: verdana;" <?php if($campaign_Data['open_url']=="n"){?> target="_blank"<?php }?> href="<?php echo $fix_tracking_url; ?>">																	<?php
																		if($campaign_Data["url"]!="http://" && $campaign_Data["url"]!=NULL)
																		{ 
																			$cont=html_entity_decode($campaign_Data["fix_contents"]);
								
																			$cont1=eregi_replace("<a[^>]*>","",$cont);
																			$cont1=eregi_replace("</a[^>]*>","",$cont1);
																			echo html_entity_decode($cont1);
																		}
																		else
																		{	
																			 echo html_entity_decode($link);;
																		}
																	?>
																	</a><?php
																}
																else echo html_entity_decode($link);
															  }
															  else echo html_entity_decode($fix_content);?>
													</div>
													</div>
													<script language="JavaScript">
														function showfix_ad()
														{
															document.getElementById("mini-nav").style.display="block";
														}
													</script>
													<script language="javascript" type="text/javascript" src="<?php echo BASEPATH;?>jscripts/cookie.js"></script>
													<SCRIPT language=JavaScript type=text/javascript>
													if(<?php echo ($campaign_Data['track_ad']=="Y") ? "false":"true";?> || readCookie('tracking<?php echo $campaign_Data['id']; ?>')!= <?php echo $campaign_Data["id"] ?>)
													{
														<?if ($campaign_Data["on_action"] == "F"):?>
														showfix_ad();
														<?endif;?>
														<?php
															$campaign_obj->updateImpression($id); //Impression++ for this Ad in campaign table.
															$campaign_obj->insertImpression($id,$_GET['ref_url'],$_GET['php_self']);
														?>
													}
													</SCRIPT>
													<NOSCRIPT></NOSCRIPT>
										<?php	
											}
											elseif($campaign_Data["floating"]=='Y')
											{
										?>
												<script language='JavaScript'>
												var verticalpos="frombottom";
												</script>
												<script language='JavaScript' src='<?php echo BASEPATH;?>jscripts/floatingbar.js'></script>
												<script language="JavaScript">
												 
												<?/*if($campaign_Data["fdiv_height_type"]=='u'):?> var startY = <?php echo $campaign_Data["fdiv_height"];?>;<?else :?> var startY = 90; <?endif;*/?>
												if (window.addEventListener){
													window.addEventListener("load", staticbar, false); 
													window.addEventListener("load", closebar, false); 
												}
												else if (window.attachEvent) {
													window.attachEvent("onload", staticbar); 
													window.attachEvent("onload", closebar); 
												}
												else if (document.getElementById){
													window.onload=staticbar; 
													window.onload=closebar; 
												}
													 
												</script>
												
												<style>
												#topbar
												{
													float: bottom;
													position:absolute;
													border: 1px;
													color:#CCCCCC;
													background-color: #FFFFFF;
													visibility: hidden;
													z-index: 1;
													
													<?php
													if($campaign_Data["fdiv_width_type"]=='d')
													{	
													?>
													WIDTH: 100%;
													<?php
													}
													if($campaign_Data["fdiv_width_type"]=='u') {
													?>
													width: <?php echo $campaign_Data["fdiv_width"];?>px;
													/*overflow:scroll;*/
													
													<?php
													}
													?>
													<?php
													if($campaign_Data["fdiv_height_type"]=='a')
													{	
													?>
													HEIGHT:auto;
													<?php
													}
													if($campaign_Data["fdiv_height_type"]=='u') {
													?>
													HEIGHT: <?php echo $campaign_Data["fdiv_height"];?>px;
													/*overflow:scroll;*/
													
													<?php
													}
	// 												?>
													margin-left : 0px;
													margin-top:0px;
													BORDER-top: <?php echo $campaign_Data["fdiv_border_color"];?> <?php echo $campaign_Data["fdiv_border_width"];?> <?php echo $campaign_Data["fdiv_border_style"];?>;
													background-color:<?php echo $campaign_Data["fdiv_background_color"];?>;
													background-image: url(<?php echo $backgroundimg;?>);
													padding-TOP:0%;
													padding-bottom:0% ;
													PADDING-RIGHT:0%;
													PADDING-LEFT:0%;
													text-align:left;
													vertical-align: text-top;
													
													display: inline-table; 
													letter-spacing: normal; 
													cursor: default;
												}</style>	
												<script language="javascript" type="text/javascript">
												function redirect(tar)
												{
													if(tar=='n')
														window.open("<?php echo $fix_tracking_url; ?>","_blank");
													else
														window.open("<?php echo $fix_tracking_url; ?>","_self");
												}
												</script>
												
												
													<?php
													if($fix_setaction==1)
													{
														$campaign_Data["url"]="";
													}
													if($campaign_Data["url"]!="http://" && $campaign_Data["url"]!=NULL)
													{
													?>
													<div style="display:none;" id="topbar">
													<?php
													}else
													{
													?>
													<div style="display: none; " id="topbar">
													<?php
													}
													?>
													<div   align="right">
														<a style="FONT-SIZE: 12px;font-weight:bold; FONT-FAMILY: verdana;color:#FF0522; cursor:pointer" title="Close Window" onclick="javascript:closebar();">Close X</a>
													</div>
													<div style="FONT-SIZE:15px;color: #000000;text-decoration: none; FONT-FAMILY: verdana;">
														<?php if($fix_setaction!=1)
															  {
																if($campaign_Data["url"]!="http://" && $campaign_Data["url"]!=NULL)
																{
																	?> <a onclick="redirect('<?php echo $campaign_Data['open_url'];?>');" style="FONT-SIZE:15px;color: #000000;text-decoration: none; FONT-FAMILY: verdana;" <?php if($campaign_Data['open_url']=="n"){?> target="_blank"<?php }?> href="<?php echo $fix_tracking_url; ?>">																	<?php
																		if($campaign_Data["url"]!="http://" && $campaign_Data["url"]!=NULL)
																		{ 
																			$cont=html_entity_decode($campaign_Data["fix_contents"]);
		
																			$cont1=eregi_replace("<a[^>]*>","",$cont);
																			$cont1=eregi_replace("</a[^>]*>","",$cont1);
																			echo html_entity_decode($cont1);
																		}
																		else
																		{	
																			echo html_entity_decode($link);
																		}
																	?>
																	</a><?php
																}
																else echo html_entity_decode($link);
															  }
															  else echo html_entity_decode($fix_content);?>
													</div>
												</div>
													<script language="JavaScript">
														function showfix_ad()
														{
															document.getElementById("topbar").style.display="block";
															document.getElementById("topbar").style.visibility="hidden";
														}
														function vshowfix_ad()
														{
															document.getElementById("topbar").style.visibility="visible";
														}														
													</script>
													<script language="javascript" type="text/javascript" src="<?php echo BASEPATH;?>jscripts/cookie.js"></script>
													<SCRIPT language=JavaScript type=text/javascript>
													if(<?php echo ($campaign_Data['track_ad']=="Y") ? "false":"true";?> || readCookie('tracking<?php echo $campaign_Data['id']; ?>')!= <?php echo $campaign_Data["id"] ?>)
													{ showfix_ad();
													
													
														<?if ($campaign_Data["on_action"] == "F"):?>
														showfix_ad();
														vshowfix_ad();
														
														<?endif;?>
														<?php
															$campaign_obj->updateImpression($id); //Impression++ for this Ad in campaign table.
															$campaign_obj->insertImpression($id,$_GET['ref_url'],$_GET['php_self']);
														?>
													}
													</SCRIPT>
													<NOSCRIPT></NOSCRIPT>
										<?php	
											}
										?>	
													
											<?php
										}//END fix_position"]=="B"
									}//END [on_action"]=="F"
							}//END POSTIONF==F
				if($campaign_Data["positionC"]=="C")
				{
					echo "<script language='JavaScript'>";
					echo "var Ccheck=true;";
					echo "</script>";
					$Ccheck=1;
					
					if($campaign_Data["corner_position"]=="tl") 
					{
						$float = "left";
						$clear = "none";
						$position = "absolute";
						$top = "0%";
						$left = "0%";
						$swfBig = TOPLEFTBIGSWF;
						$swfSmall = TOPLEFTSMALLSWF;
						$dividBig = "bigDivTopLeft";
						$dividSmall = "smallDivTopLeft";
						$divHeight = "400";
						$divWidth = "500";
					}
					elseif($campaign_Data["corner_position"]=="bl")
					{
						$float = "left";
						$clear = "none";
						$position = "absolute";
						$left = "0%";
						$bottom = "0%";
						$swfBig = BOTTOMLEFTBIGSWF;
						$swfSmall = BOTTOMLEFTSMALLSWF;	
						$dividBig = "bigDivBottomLeft";
						$dividSmall = "smallDivBottomLeft";
						$divHeight = "500";
						$divWidth = "400";
					}
					elseif($campaign_Data["corner_position"]=="tr")
					{
						$float = "right";
						$clear = "none";
						$position = "absolute";
						$top = "0%";
						$right = "0%";
						$swfBig = TOPRIGHTBIGSWF;
						$swfSmall = TOPRIGHTSMALLSWF;
						$dividBig = "bigDivTopRight";
						$dividSmall = "smallDivTopRight";
						$divHeight = "400";
						$divWidth = "500";
					}
					elseif($campaign_Data["corner_position"]=="br")
					{
						$float = "right";
						$clear = "none";
						$position = "absolute";
						$bottom = "0%";
						$right = "0%";
						$swfBig = BOTTOMRIGHTBIGSWF;
						$swfSmall = BOTTOMRIGHTSMALLSWF;					
						$dividBig = "bigDivBottomRight";
						$dividSmall = "smallDivBottomRight";
						$divHeight = "500";
						$divWidth = "400";
					}
						echo "<script>var dividBig='".$dividBig."';var dividSmall='".$dividSmall."';</script>";
						?>
						<SCRIPT language=VBScript src="<?php echo BASEPATH;?>jscripts/ckvrs.vbs"></SCRIPT>
						<SCRIPT language="javascript" src="<?php echo BASEPATH;?>jscripts/ckvrs.js"></SCRIPT>
						<SCRIPT language="javascript" src="<?php echo BASEPATH;?>jscripts/activate.js"></SCRIPT>
					
					
					<?php
					if($campaign_Data["play_sound"]=="Y" && $campaign_Data["sound_id"]!="0")
					{
						$dataURL1=BASEPATH."xml.php?songid=".$campaign_Data["sound_id"];
						echo "<SCRIPT language=JavaScript type=text/javascript>";
						echo "</SCRIPT>";
					}
					if($campaign_Data["url"]!="")
					{
						$redirectUrl = $campaign_Data["url"];
					}
					else
					{
						$redirectUrl="";
					}
					
						
						if($campaign_Data["open_url"]=="s")
						{
							$dataURL2 = "S";
						}
						elseif($campaign_Data["open_url"]=="n")
						{
							$dataURL2 = "N";
						}
						
						$dataURL="http://{$_SERVER['SERVER_NAME']}/dams/images.php?imageid=".$campaign_Data["small_corner_img"]."::::http://{$_SERVER['SERVER_NAME']}/dams/tracking.php?id=".$campaign_Data["id"]."::::".$_GET['php_self']."::::".$_GET['php_self'];
						if($campaign_Data["on_action"]=="F")
						{
						?>	
							<script language="javascript" type="text/javascript" src="<?php echo BASEPATH;?>jscripts/cookie.js"></script>
							<SCRIPT language=JavaScript type=text/javascript>
								if(<?php echo ($campaign_Data['track_ad']=="Y") ? "false":"true";?> || readCookie('tracking<?php echo $campaign_Data['id']; ?>')!= <?php echo $campaign_Data["id"] ?>)
								{
									activateFlash('<?php echo BASEPATH."swf_files/".$swfBig;?>','<?php echo BASEPATH."swf_files/".$swfSmall;?>','<?php echo $dataURL; ?>','<?php echo $dataURL1; ?>','<?php echo $dataURL2; ?>','<?php echo $dividBig; ?>','<?php echo $dividSmall; ?>','none','block','<?php echo $divHeight; ?>','<?php echo $divWidth; ?>');
									<?php
										$campaign_obj->updateImpression($id); //Impression++ for this Ad in campaign table.
										$campaign_obj->insertImpression($id,$_GET['ref_url'],$_GET['php_self']);
									?>
								}
									</SCRIPT>
									<NOSCRIPT></NOSCRIPT>
								<?php
						}
				}//positionC==C							
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

				if($campaign_Data["on_action"]=="L")
				{ 
				
				?>	
					<script src="<?php echo BASEPATH;?>lib/prototype.js" type="text/javascript"></script>
					<script src="<?php echo BASEPATH;?>src/unittest.js" type="text/javascript"></script>
					<script src="<?php echo BASEPATH;?>src/scriptaculous.js" type="text/javascript"></script>
					<script language="javascript" type="text/javascript" src="<?php echo BASEPATH;?>jscripts/cookie.js"></script>
					<SCRIPT language="JavaScript" type="text/javascript">
					if(<?php echo ($campaign_Data['track_ad']=="Y") ? "false":"true";?> || readCookie('trackingL<?php echo $campaign_Data['id']; ?>')!= <?php echo $campaign_Data["id"] ?>)
					{
					
						
						if(<?php echo $Ccheck; ?>)
						{//alert("Maaynk");
							activateFlash('<?php echo BASEPATH."swf_files/".$swfBig;?>','<?php echo BASEPATH."swf_files/".$swfSmall;?>','<?php echo $dataURL; ?>','<?php echo $dataURL1; ?>','<?php echo $dataURL2; ?>','<?php echo $dividBig; ?>','<?php echo $dividSmall; ?>','none','none','<?php echo $divHeight; ?>','<?php echo $divWidth; ?>');
							
						}
						if(<?php echo $Scheck; ?>)
						{
						var Scheck=true;
						//alert("Scheck");
						}
						if(<?php echo($Fcheck);?>)
						{ //alert("df");
						 var Fcheck=true;
						}
					
					
					}
					</SCRIPT>
					<NOSCRIPT></NOSCRIPT>
				
					
					<SCRIPT language=JavaScript type=text/javascript>
					
					
						var opened=false;
						var eg_width=450;
						var eg_height=300;
						var nrp=-1;
								
								
							
						function mmove(e)
						{
									
							
							if(opened)
							
							return true;
							var posx=0;
							var posy=0;
							if(!e)var e=window.event;
							//alert(e);
// 							if(e.pageX||e.pageY)
// 							{
// 								posx=e.pageX;
// 								posy=e.pageY;
// 								//alert(posy);
// 							}
// 							else
// 							{
// 								posx=e.screenX;
// 								posy=e.screenY;
// 								
// 							}
							
								if (e.pageX || e.pageY)
								{ // this doesn't work on IE6!! (works on FF,Moz,Opera7)
								posx = e.pageX;
								posy = e.pageY;
								algor = '[e.pageX]';
								if (e.clientX || e.clientY) algor += ' [e.clientX] '
								}
								else if (e.clientX || e.clientY)
								{ // works on IE6,FF,Moz,Opera7
								posx = e.clientX + document.body.scrollLeft;
								posy = e.clientY + document.body.scrollTop;
								algor = '[e.clientX]';
								if (e.pageX || e.pageY) algor += ' [e.pageX] '
								}  
						
							//alert(posy);
							//alert(posy);
							//alert(posy +" "+ window.scrollTop);
							if(posy<20+document.body.scrollTop)
							{
								
								//alert("ADFASDFSD"); 
								if(<?php echo ($campaign_Data['track_ad']=="Y") ? "false":"true";?> || readCookie('tracking<?php echo $campaign_Data['id']; ?>')!= <?php echo $campaign_Data["id"] ?>)
								{
									if(<?php echo ($campaign_Data['track_ad']=="Y") ? "true":"false";?>)
										{
											var isCookieSet = GetCookie('trackingL<?php echo $campaign_Data['id']; ?>');
											if(!isCookieSet)
											{
												SetCooKie('trackingL<?php echo $campaign_Data['id']; ?>','<?php echo $campaign_Data['id']; ?>');
											}
										}
									if(Ccheck===true)
									{
										if(Scheck===true)
										{
											if(Fcheck===true)
											{ //alert("S+C+F");
											
												/*if(<?php echo ($campaign_Data["fix_position"]=="T")? "true":"false"; ?>)
												{ 
													//Effect.Appear('float_div_top',{ duration: 2.0 });
													document.getElementById("pid").style.display="block";
												}
												if(<?php echo ($campaign_Data["fix_position"]=="B")? "true":"false"; ?>)
												{
													document.getElementById("pid").style.display="block";
												}*/
												
												<?php
													if($campaign_Data['sdiv_pos_type']=="d")
													{
														$lpos="75";
													}
													elseif($campaign_Data['sdiv_pos_type']=="u")
													{
														$lpos=$campaign_Data['sdiv_pos'];
													}
													//ECHO $lpos;
												?>
												
												var div_elem =document.getElementById('hover_content')
												//DropIn(main_bgcolor,main_texttype,main_textsize,main_textcolor,textbar,height,width,top,left,bordercolor,borderstyle,borderwidth,sec,showeverytime)
												DropIn('<?php echo $lpos;?>','','Microsoft Sans Serif',14,'',div_elem.innerHTML,'75','400','#0000FF','solid',0,0,true);
												
												document.getElementById(dividBig).style.display='block';
											}
											else
											{ //alert("S+C");
												document.getElementById(dividBig).style.display='block';
												<?php
													if($campaign_Data['sdiv_pos_type']=="d")
													{
														$lpos="75";
													}
													elseif($campaign_Data['sdiv_pos_type']=="u")
													{
														$lpos=$campaign_Data['sdiv_pos'];
													}
												?>
												var div_elem =document.getElementById('hover_content')
									
												DropIn('<?php echo $lpos;?>','','Microsoft Sans Serif',14,'',div_elem.innerHTML,'75','300','#0000FF','solid',0,0,true);
											}
										}
										else if(Fcheck===true)
										{ //alert("C+F");
										
											document.getElementById(dividBig).style.display='block';
											
											if(<?php echo ($campaign_Data["fix_position"]=="T")? "true":"false"; ?>)
											{
													document.getElementById("pid").style.display="block";
											}
											if(<?php echo ($campaign_Data["fix_position"]=="B")? "true":"false"; ?>)
											{
													document.getElementById("pid").style.display="block";
											}
										}
										else
										{ //alert("C");
											document.getElementById(dividBig).style.display='block';
										}
									}
									else if(Scheck===true && Fcheck===true)
									{//alert("S+F");
											<?php
													if($campaign_Data['sdiv_pos_type']=="d")
													{
														$lpos="75";
													}
													elseif($campaign_Data['sdiv_pos_type']=="u")
													{
														$lpos=$campaign_Data['sdiv_pos'];
													}
												?>
											var div_elem =document.getElementById('hover_content')
									
											DropIn('<?php echo $lpos;?>','','Microsoft Sans Serif',14,'',div_elem.innerHTML,'75','300','#0000FF','solid',0,0,true);
											if(<?php echo ($campaign_Data["fix_position"]=="T")? "true":"false"; ?>)
												{ 
													document.getElementById("pid").style.display="block";
												}
												if(<?php echo ($campaign_Data["fix_position"]=="B")? "true":"false"; ?>)
												{
													document.getElementById("pid").style.display="block";
												}
									}
									else if(Scheck===true && Fcheck===false)	
									{
										//alert("S");
										<?php
													if($campaign_Data['sdiv_pos_type']=="d")
													{
														$lpos="75";
													}
													elseif($campaign_Data['sdiv_pos_type']=="u")
													{
														$lpos=$campaign_Data['sdiv_pos'];
													}
												?>
													var div_elem =document.getElementById('hover_content')
									
											DropIn('<?php echo $lpos;?>','','Microsoft Sans Serif',14,'',div_elem.innerHTML,'75','300','#0000FF','solid',0,0,true);
									
									} else if (Fcheck == true) {
										<?if($campaign_Data["floating"]=='Y'):?>
											showfix_ad();
											vshowfix_ad();
											staticbar();
										<?else :?> 
											showfix_ad();
											vshowfix_ad();
											staticbar();
										<?endif;?>
									}	
																						
								
								}
										
									opened = true;
									return true;
							}
							
							else
							{
								is_in=false
							}
							return true
						};
						
						function addLoadEvent(func)
						{//alert("xczx");
							var oldonload=window.onload;
							if(typeof window.onload!='function')
							{
								window.onload=func
							}
							else
							{
								window.onload=function(){if(oldonload){oldonload()}func()}
							}
						};
								
						function eg_init()
						{
							document.onmousemove=mmove;
						};
						addLoadEvent(eg_init);
					
					</SCRIPT>
					<NOSCRIPT></NOSCRIPT>
					
				
				<?php
					$campaign_obj->updateImpression($id); //Impression++ for this Ad in campaign table.
					$campaign_obj->insertImpression($id,$_GET['ref_url'],$_GET['php_self']);
				}//end onleave		
					
		}//start date
	}//if(campaign_data)

?>
<style>

				#bigDivTopLeft
				{
					height:400px;
					width:500px;
					top:0%;
					left:0%;
					float:left;
					clear:none;
					position:absolute;
				}
				#smallDivTopLeft
				{
					height:75px;
					width:75px;
					top: 0%;
					left:0%;
					float:left;
					clear:none;
					position:absolute;
				}
				#bigDivTopRight
				{
					position:absolute;
					height:400px;
					width:500px;
					right:0%;
					top:0%;
					float:right;
					clear:none;
				}
				#smallDivTopRight
				{
					position:absolute;
					height:75px;
					width:75px;
					right:0%;
					top:0%;
					float:right;
					clear:none;
				}
				#bigDivBottomRight
				{
					position:absolute;
					height:500px;
					width:400px;
					right:0%;
					bottom:0%;
					float:right;
					clear:none;
				}
				#smallDivBottomRight
				{
					position:absolute;
					height:75px;
					width:75px;
					right:0%;
					bottom:0%;
					float:right;
					clear:none;
				}
				#bigDivBottomLeft
				{
					position:absolute;
					height:500px;
					width:400px;
					left:0%;
					bottom:0%;
					float:left;
					clear:none;
					padding-bottom:0%;
				}
				#smallDivBottomLeft
				{
					position:absolute;
					height:75px;
					width:75px;
					left:0%;
					bottom:0%;
					float:left;
					clear:none;
				}

	
					#right_top {
						position:absolute;
						width:400px;
						float:right;
						clear:none;
						height:90px;
						z-index:1;
						right: 0%;
						top: 0%;
						}
					#left_top {
					
						position:absolute;
						width:400px;
						float:left;
						clear:none;
						height:90px;
						z-index:1;
						left: 0%;
						top: 0%;
						overflow: hidden;
						}
					#left_bottom {
						position:absolute;
						width:90px;
						float:left;
						clear:none;
						height:400px;
						z-index:1;
						left: 0%;
						bottom:0%;
						}
					#right_bottom {
						position:absolute;
						width:400px;
						float:right;
						clear:none;
						height:400px;
						z-index:1;
						right: 0%;
						bottom:0%;
						}		
	
	</style>
	<?php
			
			if($campaign_Data['track_ad']=="Y")
			{
			
				echo "<script type='text/javascript'>";
					echo "var isCookieSet = GetCookie('tracking".$campaign_Data['id']."');";
					echo "if(!isCookieSet){";
						echo "SetCooKie('tracking".$campaign_Data['id']."',".$campaign_Data['id'].");}";
				echo "</script>";
				
			}	
 ?>	
