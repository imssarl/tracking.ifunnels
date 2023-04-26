<?php

class CTMSales

{

	function addRecords()

	{

		$added = 0;

 

		for($i=0; $i<count($_POST["track_id"]); $i++)

		{

			$track_id = trim($_POST["track_id"][$i]);

			if($track_id != "" && is_numeric($track_id) && $track_id > 0)

			{

				$product_id = $_POST["product_id"][$i];

				$affiliate_network = $_POST["affiliate_network"][$i];

				$amount = $_POST["amount"][$i];

				$item = $_POST["item"][$i];

				$commission = $_POST["commission"][$i];									

				$trdate = $_POST["date"][$i];

				$trtime = $_POST["time"][$i];



				if($amount=="") $amount = 0;

				if($item=="") $item = 0;

				if($commission=="") $commission = 0;

				

				$trn_id = $track_id.$amount.$item.$commission;

								

				$id = $this->insertSalesData($trn_id, $track_id, $amount, $item, $commission, $trdate, $trtime,$product_id,$affiliate_network);

				if($id)

				$added++;

			}

		}	

		return $added;

	}

	function updateRecord()

	{

		global $ms_db;

		

			$sql = "Update `".TABLE_PREFIX."salesdata` SET "

			."track_id = '".$ms_db->GetSQLValueString($_POST["track_id"][0],"text")."',"

			."amount = '".$ms_db->GetSQLValueString($_POST["amount"][0],"text")."',"

			."commission = '".$ms_db->GetSQLValueString($_POST["commission"][0],"text")."',"	

			."item = '".$ms_db->GetSQLValueString($_POST["item"][0],"text")."',"

			."date = '".$ms_db->GetSQLValueString($_POST["date"][0],"text")."',"

			."time = '".$ms_db->GetSQLValueString($_POST["time"][0],"text")."',"
			
			."product_id = '".$ms_db->GetSQLValueString($_POST["product_id"][0],"text")."',"
			
			."affiliate_network = '".$ms_db->GetSQLValueString($_POST["affiliate_network"][0],"text")."'

			WHERE id = '".$ms_db->GetSQLValueString($_POST["sid"],"int")."'";

			$id = $ms_db->modify($sql);

			return $id;

			// for updating affilate network name add pro_id and affilate_network field on 14 nov by sdei
	}

	function deleteRecord($sid)

	{

		global $ms_db;

	

		$sql = "Delete from `".TABLE_PREFIX."salesdata` WHERE

		id = '".$ms_db->GetSQLValueString($sid,"int")."'";

;

		$id = $ms_db->modify($sql);

		return true;	

	}

	function getRecordById($sid)

	{

		global $ms_db;

	

		$sql = "select *  from `".TABLE_PREFIX."salesdata` WHERE

		id = '".$ms_db->GetSQLValueString($sid,"int")."'";

;

		$data = $ms_db->getDataSingleRow($sql);

		return $data;	

	}

	function insertSalesData($trn_id, $track_id=0, $amount=0, $item=0, $commission=0, $trdate='0000-00-00', $trtime='00:00:00',$product_id=0, $affiliate_network=0)

	{

		global $ms_db;

		

		$ex = $this->checkExists($track_id, $amount, $commission, $item);

		if (!$ex)

		{

			$sql = "INSERT INTO `".TABLE_PREFIX."salesdata` (unq_trn_id, `track_id`, `product_id`, `affiliate_network` , `amount` , commission, `item` , date, time,`user_id`)

			VALUES ("

			."'".$ms_db->GetSQLValueString($trn_id,"text")."',"		

			."'".$ms_db->GetSQLValueString($track_id,"text")."',

			"."'".$ms_db->GetSQLValueString($product_id,"text")."',

			"."'".$ms_db->GetSQLValueString($affiliate_network,"text")."',"

			."'".$ms_db->GetSQLValueString($amount,"text")."',"

			."'".$ms_db->GetSQLValueString($commission,"text")."',"	

			."'".$ms_db->GetSQLValueString($item,"text")."',"

			."'".$ms_db->GetSQLValueString($trdate,"text")."',"

			."'".$ms_db->GetSQLValueString($trtime,"text")."',"

			."'".$ms_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int")."')";

			$id = $ms_db->insert($sql);

			

			return $id;

		}

		else

		{

			return false;

		}

	}

	function checkExists($track_id, $amount, $commission, $item)

	{

		global $ms_db;

		if ($track_id=="") $track_id = 'NULL';

		if ($item=="") $item = 0;

		

		

		$sql = "Select id from `".TABLE_PREFIX."salesdata` WHERE

		track_id = '$track_id' AND

		round(amount,2) = round($amount,2) AND 

		round(commission,2) = round($commission,2) AND 		

		item = $item";

		$id = $ms_db->getDataSingleRecord($sql);



		return $id;

	}



	function uploadUpdateSalesDataForClickBank()

	{

		// code was made by Jitendra, Mahendra :- made some logical changes to make it working.

		global $affn;

		

		if(isset($_POST['affiliate_network']) && $_POST['affiliate_network']!="")

		{

			$affn_Data = $affn->getaffiliateById($_POST['affiliate_network']);

			$affiliate_name = $affn_Data['affiliate_name'];

		}

		else

		{

			$affiliate_name ="";

		}

// 		echo $affiliate_name;

// 		die();

		$msg = "";

		$filename=$_FILES["csvfile"]["name"];

		$extention=explode(".",$filename);

		$fileextention=$extention[1];

		if($fileextention=="csv" || $fileextention=="txt")

		{

			$csv_file=$_FILES["csvfile"]["tmp_name"];

			$handle = fopen($csv_file,"r");

			if($handle)

			{

				$trackingpage = fread($handle, filesize($csv_file));

				fclose($handle);

				$seperatingbyline=explode("\n",$trackingpage);

				$noofupdates = 0;

				if(str_replace(",TID,",",TID,",$seperatingbyline[0]))

				{

					foreach($seperatingbyline as $val)

					{

						$csvdata=explode(",",$val);

						if($csvdata[5]=="Sale")

						{

							$unq_id=$csvdata[2];

							$track_id=$csvdata[3];

							$amount=str_replace("$","",$csvdata[7]);

							$product_id=$csvdata[8];

							$item=$csvdata[6];

							$trdate = $csvdata[0];

							$trtime = $csvdata[1];

							$commission = str_replace("$","",$csvdata[7]);;



							$id=$this->insertSalesData($unq_id, $track_id, $amount, $item, $commission, $trdate, $trtime,$product_id,$affiliate_name);

							

							if ($id)

							{

								$noofupdates++;

								$salesdata .= $this->collectSalesData($unq_id, $track_id, $amount, $item, $commission, $trdate, $trtime);

							}

						}

					}

					$pathsalesdata = $this->storeSalesData($salesdata);

					$msg = "Total $noofupdates record(s) updated successfully<a target=_blank href=ctmsalesdata.php>View Data</a>";



				}

				else

				{

					$msg="There is some problem in fetched data, Please try again";

				}

			}

			else

			{	

				$msg= "Problem in Opening the file.";

			}

		}

		else

		{

			$msg="Only CSV files are allowed.";

		}

		return $msg;	

	}

	

	function autoUpdateSalesDataForLinkShare()

	{

		global $affn, $common;

		$msg = "";

		$bdate = str_replace("/","",$_POST["bdate"]);

		$edate = str_replace("/","",$_POST["edate"]);

		$url = "https://ssl.linksynergy.com/php-bin/affiliate/reports/report.shtml";

		$url .= "?name=u1field&direct=1&direct_dl=1";

		$url .= "&bdate=$bdate";

		$url .= "&edate=$edate";

		$url .= "&cuserid=".$_POST["userid"];

		$url .= "&cpi=".$_POST["passwd"];

		$url .= "&eid=".$_POST["affid"];

		$url .= "&nid=".$_POST["netwid"];



		$affn->setAfnUserDetails(3);



		$data = $common->fetchDataFromUrl($url);

		if (trim($data)!="")

		{

			$records=explode("\n",$data);

			$noofupdates = 0;



			if(strpos($records[0], "SKU Number"))

			{

				$entry = true;

				foreach($records as $val)

				{

					if (trim($val) != "" && $entry==false)

					{

						$csvdata=explode(",",$val);

						$unq_id = $csvdata[3];

						$track_id=$csvdata[0];

						$amount=str_replace("$","",$csvdata[7]);

						$item=$csvdata[8];

						$trdate = $csvdata[4];

						$trtime = $csvdata[5];

						$commission = $csvdata[9];

			

						$id = $this->insertSalesData($unq_id, $track_id, $amount, $item, $commission, $trdate, $trtime);

						

						if ($id)

						{

							$noofupdates++;

							$salesdata .= $this->collectSalesData($unq_id, $track_id, $amount, $item, $commission, $trdate, $trtime);

						}

					}

					$entry = false;

				}

				$pathsalesdata = $this->storeSalesData($salesdata);				

				$msg = "Total $noofupdates record(s) updated successfully<a href=$pathsalesdata>View Data</a>";

			}

			else

			{

				$msg="There is some problem in fetched data, Please try again";

			}

		}

		else

		{

			$msg = "No data is fetched from LinkShare.com";

		}

		return $msg;

	}

	

	function autoUpdateSalesDataForCommissionJunction()

	{

		global $affn;

		require_once("soaplib/nusoap.php");		

		$msg = "";		

		$affn->setAfnUserDetails($_POST["affiliate_network"]);

		$developerkey = $_POST["devkey"];

		$trdate = $_POST["tdate"];

		$datetype = $_POST["datetype"];

		$soapclient = new soapclient("https://pubcommission.api.cj.com/wsdl/version2/publisherCommissionService.wsdl", 'wsdl');		

		

		if($soapclient)

		{

			$params = array(

			"developerKey" => $developerkey,

			"date" => $trdate,

			"dateType" => $datetype

			);



			$proxy = $soapclient->getProxy();

			if($proxy)

			{

				$result = $proxy->findPublisherCommissions($params);

				if($result)

				{

					if ($result["out"]["totalResults"]>0)

					{



						$cdata = $result["out"]["publisherCommissions"];

						$id = array();

						foreach($cdata as $data)

						{

							$id[] = $data["originalActionId"];

						}

						$oids = implode(',', $id);

						

						

						$params = array(

						"developerKey" => $developerkey,

						"originalActionIds" => $oids

						);

						$sdata = false;

						$sdata = $proxy->findPublisherCommissionDetails($params);

						if ($sdata)

						{

							$transdata = $sdata["TransactionDetail"];

							$noofupdates = 0;

							foreach($transdata as $trns)

							{

								$itemdata = $trns["ItemDetails"];

								$item = $itemdata["quantity"];

								$amount = $itemdata["amount"];

								$track_id=$itemdata["sId"];

								$unq_id = $itemdata["originalActionId"];

								$commission = $itemdata["commissionAmount"];

								$datetime = explode("T", $itemdata["postingDate"]);

								$trdate = $datetime[0];

								$trtime = $datetime[1];

								

								$amount=str_replace("$","",$amount);

				

				

								$id = $this->insertSalesData($unq_id, $track_id, $amount, $item, $commission, $trdate, $trtime);

							

								if ($id)

								{

									$noofupdates++;

									$salesdata .= $this->collectSalesData($unq_id, $track_id, $amount, $item, $commission, $trdate, $trtime);

								}

							}

							$pathsalesdata = $this->storeSalesData($salesdata);							

							$msg = "Total $noofupdates record(s) updated successfully<a href=$pathsalesdata>View Data</a>";

						}

						else

						{

							$msg = "There is some problem in fetching sales data, Please try again";						

						}

					}

					else

					{

						$msg = "Total zero records found";

					}

				}

			}

		}

		return $msg;

	}

	function uploadUpdateSalesData($for="")

	{

	

		global $affn;

		$msg = "";

		$filename=$_FILES["csvfile"]["name"];

		$extention=explode(".",$filename);

		$fileextention=$extention[1];

		if($fileextention=="csv" || $fileextention=="txt")

		{

			$csv_file=$_FILES["csvfile"]["tmp_name"];

			$handle = @fopen($csv_file,"r");

			if($handle)

			{

				$trackingpage = @fread($handle, filesize($csv_file));

				@fclose($handle);

				$seperatingbyline=explode("\n",$trackingpage);

				$noofupdates = 0;

				

				if($for=="cj")

					$columns = array("sId", "originalActionId", "saleAmount","items","commissionAmount", "eventDate", "time");

				else if ($for=="ls")

					$columns = array("Member", "Order", "Sales", "Quantity", "Commissions", "Date", "Time");

				else if ($for=="mb")

					$columns = array("TrackingID", "Order", "Sales", "Quantity", "Commissions", "Date", "Time");				

				else if ($for=="ct")

					$columns = array("TrackingID", "Order", "Sales", "Quantity", "Commissions", "Date", "Time");				

				else if ($for=="ce")								

					$columns = array("TrackingID", "Order", "Sales", "Quantity", "Commissions", "Date", "time");

				else if ($for=="ss")								

					$columns = array("TrackingID", "Order", "Sales", "Quantity", "Commissions", "Date", "time");

				else if ($for=="ma")								

					$columns = array("TrackingID", "Order", "Sales", "Quantity", "Commissions", "Date", "Time");

					

/* 

Here all are same by default but as per requirement in future these field names may be different

So change them if actual CSV files have field names different then default :-)



At this time i have not seen either of LinkShare or CT or MB or CE

*/									

// 			PRINT_R($_POST);

// 		die();



				if(isset($_POST['affiliate_network']) && $_POST['affiliate_network']!=0)

				{

					$affn_Data = $affn->getaffiliateById($_POST['affiliate_network']);

					$affiliate_name = $affn_Data['affiliate_name'];

				}

				else

				{

					$affiliate_name = $_POST['affiliate_network'];

				}

				$product_id = "";

				$position = $this->getPositionOfColumn($seperatingbyline[0], $columns);

				

				if(is_array($position) && count($position)>0)

				{

					$entry = true;

					foreach($seperatingbyline as $val)

					{

						if (trim($val) == "") continue;

						else if ($entry) { $entry = false; continue; }

		

						$csvdata=explode(",",$val);



						$track_id = $csvdata[$position[$columns[0]]];



						if (isset($csvdata[$position[$columns[2]]]) && $csvdata[$position[$columns[2]]] != "")

							$amount = str_replace("$","",$csvdata[$position[$columns[2]]]);

						else

							$amount = 0;



						if (isset($csvdata[$position[$columns[4]]]) && $csvdata[$position[$columns[4]]]!= "")

							$commission = str_replace("$","",$csvdata[$position[$columns[4]]]);

						else

							$commission = 0;





						if (isset($csvdata[$position[$columns[3]]]) && $csvdata[$position[$columns[3]]] != "")

							$item = $csvdata[$position[$columns[3]]];

						else

							$item = 0;





						if(isset($csvdata[$position[$columns[1]]]) && $csvdata[$position[$columns[1]]] != "")

						{

							$unq_id = $csvdata[$position[$columns[1]]];

							$product_id = $csvdata[$position[$columns[1]]];

						}

						else

						{

							$unq_id = $track_id.$amount.$item;

							$product_id = "";

						}





						if (isset($csvdata[$position[$columns[5]]]) && $csvdata[$position[$columns[5]]]!= "")

							$trdate = $csvdata[$position[$columns[5]]];

						else

							$trdate = '0000-00-00';



						if (isset($csvdata[$position[$columns[6]]]) && $csvdata[$position[$columns[6]]]!= "")

							$trtime = $csvdata[$position[$columns[6]]];

						else

							$trtime = '0000-00-00';



							

						if ($for=="cj")

						{

							$item = 1;

							$dt = explode("T", $trdate);

							$trdate = $dt[0];

							if ($trtime == "" || $trtime == '0000-00-00')

								$trtime = $dt[1];

						}





						$id = $this->insertSalesData($unq_id, $track_id, $amount, $item, $commission, $trdate, $trtime,$product_id,$affiliate_name);

						//echo "a".$id;die();

						if ($id)

						{ //echo "aahya";

							$noofupdates++;

							$salesdata .= $this->collectSalesData($unq_id, $track_id, $amount, $item, $commission, $trdate, $trtime);

						}



					}

						

					$pathsalesdata = $this->storeSalesData($salesdata);

					$msg = "Total $noofupdates record(s) updated successfully<a target=_blank href=ctmsalesdata.php>View Data</a>";



				}

				else

				{

					$fields = implode(",", $coulmns);

					$msg="There is some problem in data,<BR>

					Please check fields : ".$fields;

				}

			}

			else

			{	

				$msg= "Problem in Opening the file.";

			}

		}

		else

		{ 

			$msg="Only CSV files are allowed.";

		}



		return $msg;	

	}

	function uploadMassLinking()

	{

		global $affn,$campaign,$ms_db,$common,$track;

		$msg = "";

		$add_count = 0;

		$camp_count = 0;

		$aff_count = 0;

		$filename=$_FILES["csvfile"]["name"];

		$extention=explode(".",$filename);

		$fileextention=$extention[1];

		if($fileextention=="csv" || $fileextention=="txt")

		{

			$csv_file=$_FILES["csvfile"]["tmp_name"];

			$handle = @fopen($csv_file,"r");

			if($handle)

			{

				$trackingpage = @fread($handle, filesize($csv_file));

				@fclose($handle);

				$seperatingbyline=explode("\n",$trackingpage);

				$noofupdates = 0;

				

				$columns = array("Campaign","Ad","Network","Environment","Link");



				if($_POST['isHeaderInclude']=="N")

				{

					foreach($seperatingbyline as $val)

					{	

						if (trim($val) == "") continue;

						else if ($entry) { $entry = false; continue; }

		

						$csvdata=explode(",",$val);



						$_POST["campaign_name"] = trim($csvdata[0]);

						$_POST["ad_name"] = trim($csvdata[1]);

						$_POST["affiliate_network"] = trim($csvdata[2]);

						$_POST["ad_env"] = trim($csvdata[3]);

						$_POST["merchant_link"] = trim($csvdata[4]);

						

						$_POST['merchant_link'] = trim(str_replace(array("\\\\","\\"),"/",$_POST['merchant_link']));

						if (substr(trim($_POST['merchant_link']),0,7)!="http://") $_POST['merchant_link']="http://".$_POST['merchant_link'];

						

						$var = parse_url($_POST['merchant_link']);

								

						if(!isset($var['path']) && !isset($var['query']))

						{ 

							$_POST['merchant_link'].="/";

						}

						elseif(isset($var['path']) && !isset($var['query']))

						{

							$temp = explode("/",$var['path']);

					

							if(isset($temp[count($temp)-1]) && $temp[count($temp)-1]!="")

							{

								$pos = strpos($temp[count($temp)-1],".");

								

								if($pos===false)

								{

									$_POST['merchant_link'].="/";

								}

							}

						}



						

					

						$campaign_id = $campaign->getCampaignIdByName($_POST["campaign_name"]); //Check wheather this campaign already exists.

							

						if($campaign_id)

						{

							$cid = $campaign_id;

						}

						else

						{

							$cid = $campaign->insertCampaign();

							$camp_count++;

						}

						

						$sql = "select ad_name from ".TABLE_PREFIX."ad where campaign_id=".$cid;

						

						$ad_name = $ms_db->getData($sql);

						

						//echo "C=".$ad_name[0]['ad_name'];die();

						//print_r($ad_name);die();

						$ad_flag = true;

						if($ad_name)

						{

							if(is_array($ad_name))

							{

								for($i=0;$i<=count($ad_name);$i++)

								{

									if($ad_name[$i]['ad_name']==$_POST['ad_name'])

									{

										$ad_flag = false;

										break;

									}

								}

							}

							else

							{

								if($ad_name==$_POST['ad_name'])

								{

									$ad_flag = false;

								}

							}

						}

						if($ad_flag!==false)	

						{

							$affiliate_id=0;

							$affiliate_id = $affn->getAffilateIdByName($_POST["affiliate_network"]);

							

							if($affiliate_id==0)

							{

								$affiliate_id = $affn->insertAffiliateByValue($_POST["affiliate_network"]);

								//$affiliate_id = $_POST['affiliate_network'];

								$aff_count++;

								$msg.="WARNING! Please update your Affiliate Networks Details for ".$_POST['affiliate_network']."<br>";

							}

							

							$_POST["affiliate_network"] = $affiliate_id;

							

							$id = $campaign->insertAd($cid);

							

							if($id)

								$add_count++;

								if($_POST['page']=="Y")

								{

									$addata=$campaign->getAdById($id);

										

									$merchantlink=$affn->getMerchantLink($addata["affiliate_network"],$addata["merchant_link"]);

									

									

									$name=str_replace(" ","-",trim($_POST["ad_name"]));

									$filename=$name.".php";

								//echo $filename; die();

									$createdpage = $track->createTrackingPage($id, $addata["ad_env"], $merchantlink);

								

									if ($createdpage)

									{

										$dir="trackingpages";

										if (is_dir($dir))

										{

											if($dh = opendir($dir))

											{

												$file_exist=false;

												while (($file = readdir($dh)) !== false)

												{

													if(!is_dir($file) && !strcmp($file,$filename))

													{

														$file_exist=true;

														break;

													}

												}

												

												if($file_exist===true)

												{

													closedir($dh);

													if ($dh = opendir($dir))

													{

														$file_exist1=false;

														while (($file = readdir($dh)) !== false)

														{

															$filename=$name."-1.php";

															if(!is_dir($file) && !strcmp($file,$filename))

															{

																$file_exist1=true;

																sleep(1);

																$code=md5(date("Y-m-d h-i-s"));

																$code=substr($code, strlen($code)-3,3);

																$filename=$name."-".$code.".php";

																break;

															}

															

														}

														if($file_exist1===false)

														{

															$filename=$name."-1.php";

														}

													}

												}

												else

												{

													$filename=$name.".php";

												}

												closedir($dh);

											}

										}

										

										$destination = "trackingpages/".$filename;

										

// 										$handle = fopen($destination, "r");

// 										$contents = fread($handle, filesize($filename));

// 										fclose($handle);

										if(@copy($createdpage, $destination))

										{

											//@chmod( $destination, 0777 );

											$track->insertTrackPageDetails($id, 0, ROOT_PATH.$destination,"L");

															

										}

										else

										{

											$msg.="Tracking page cannot be created for ".$_POST["ad_name"]." Ad<br>";				

										}

									}

								}								

						}

					}

				}				

				else

				{

					

					$position = $this->getPositionOfColumn($seperatingbyline[0], $columns);

					

					if(is_array($position) && count($position)>0)

					{

						$entry = true;

						foreach($seperatingbyline as $val)

						{

							

							if (trim($val) == "") continue;

							else if ($entry) { $entry = false; continue; }

			

							$csvdata=explode(",",$val);

	

							$_POST["campaign_name"] = trim($csvdata[$position[$columns[0]]]);

							$_POST["ad_name"] = trim($csvdata[$position[$columns[1]]]);

							$_POST["affiliate_network"] = trim($csvdata[$position[$columns[2]]]);

							$_POST["ad_env"] = trim($csvdata[$position[$columns[3]]]);

							$_POST["merchant_link"] = trim($csvdata[$position[$columns[4]]]);

							

							$_POST['merchant_link'] = trim(str_replace(array("\\\\","\\"),"/",$_POST['merchant_link']));

							if (substr(trim($_POST['merchant_link']),0,7)!="http://") $_POST['merchant_link']="http://".$_POST['merchant_link'];

							

							$var = parse_url($_POST['merchant_link']);

									

							if(!isset($var['path']) && !isset($var['query']))

							{ 

								$_POST['merchant_link'].="/";

							}

							elseif(isset($var['path']) && !isset($var['query']))

							{

								$temp = explode("/",$var['path']);

						

								if(isset($temp[count($temp)-1]) && $temp[count($temp)-1]!="")

								{

									$pos = strpos($temp[count($temp)-1],".");

									

									if($pos===false)

									{

										$_POST['merchant_link'].="/";

									}

								}

							}

						

							$campaign_id = $campaign->getCampaignIdByName($_POST["campaign_name"]); //Check wheather this campaign already exists.

							

							

							

							if($campaign_id)

							{

								$cid = $campaign_id;

							}

							else

							{

								$cid = $campaign->insertCampaign();

								$camp_count++;		

							}

							

							$sql = "select ad_name from ".TABLE_PREFIX."ad where campaign_id=".$cid;

							

							$ad_name = $ms_db->getData($sql);

							

							//echo "C=".$ad_name[0]['ad_name'];die();

							//print_r($ad_name);die();

							$ad_flag = true;

							if($ad_name)

							{

								if(is_array($ad_name))

								{

									for($i=0;$i<=count($ad_name);$i++)

									{

										if($ad_name[$i]['ad_name']==$_POST['ad_name'])

										{

											$ad_flag = false;

											break;

										}

									}

								}

								else

								{

									if($ad_name==$_POST['ad_name'])

									{

										$ad_flag = false;

										continue;

									}

								}

							}

							if($ad_flag!==false)	

							{

								$affiliate_id=0;

								$affiliate_id = $affn->getAffilateIdByName($_POST["affiliate_network"]);

								

								if($affiliate_id==0)

								{

									$affiliate_id = $affn->insertAffiliateByValue($_POST["affiliate_network"]);

									//$affiliate_id = $_POST['affiliate_network'];

									$aff_count++;

									$msg.="WARNING! Please update your Affiliate Networks Details for ".$_POST['affiliate_network']."<br>";

								}

								

								$_POST["affiliate_network"] = $affiliate_id;

								

								$id = $campaign->insertAd($cid);

								

								if($id)

									$add_count++;

							

								if($_POST['page']=="Y")

								{

									$addata=$campaign->getAdById($id);

										

									$merchantlink=$affn->getMerchantLink($addata["affiliate_network"],$addata["merchant_link"]);

									

									

									$name=str_replace(" ","-",trim($_POST["ad_name"]));

									$filename=$name.".php";

								//echo $filename; die();

									$createdpage = $track->createTrackingPage($id, $addata["ad_env"], $merchantlink);

								

									if ($createdpage)

									{

										$dir="trackingpages";

										if (is_dir($dir))

										{

											if($dh = opendir($dir))

											{

												$file_exist=false;

												while (($file = readdir($dh)) !== false)

												{

													if(!is_dir($file) && !strcmp($file,$filename))

													{

														$file_exist=true;

														break;

													}

												}

												

												if($file_exist===true)

												{

													closedir($dh);

													if ($dh = opendir($dir))

													{

														$file_exist1=false;

														while (($file = readdir($dh)) !== false)

														{

															$filename=$name."-1.php";

															if(!is_dir($file) && !strcmp($file,$filename))

															{

																$file_exist1=true;

																sleep(1);

																$code=md5(date("Y-m-d h-i-s"));

																$code=substr($code, strlen($code)-3,3);

																$filename=$name."-".$code.".php";

																break;

															}

															

														}

														if($file_exist1===false)

														{

															$filename=$name."-1.php";

														}

													}

												}

												else

												{

													$filename=$name.".php";

												}

												closedir($dh);

											}

										}

										

										$destination = "trackingpages/".$filename;

										

// 										$handle = fopen($destination, "r");

// 										$contents = fread($handle, filesize($filename));

// 										fclose($handle);

										if(@copy($createdpage, $destination))

										{

											//@chmod( $destination, 0777 );

											$track->insertTrackPageDetails($id, 0, ROOT_PATH.$destination,"L");

															

										}

										else

										{

											$msg.="Tracking page cannot be created for ".$_POST["ad_name"]." Ad<br>";				

										}

									}

								}	

							}						

						}

					}

					else

					{

						$fields = implode(",", $coulmns);

						$msg="There is some problem in data,<BR>

						Please check fields : ".$fields;

					}

				}

				

			}

			else

			{	

				$msg= "Problem in Opening the file.";

			}

		}

		else

		{ 

			$msg="Only CSV files are allowed.";

		}

		$msg.="Total $aff_count Affiliate Network(s) has been added<br>";

		$msg.="Total $camp_count Campaign(s) has been added<br>";

		$msg.="Total $add_count Ad(s) has been added";

		return nl2br($msg);

	}	

	function getPositionOfColumn($heading, $columns)

	{

		$pos = array();



		if(strlen($heading)>0)

		{

			$hcolumns = explode(",", $heading);

			

			if(is_array($hcolumns) && count($hcolumns)>0)

				foreach($columns as $column)

				{

					for($i=0; $i<count($hcolumns); $i++)

					{

						$found = @eregi($column, trim($hcolumns[$i]));

						if($found)

						{

							$pos[$column] = $i;

							break;

						}

						else

						{

							$pos[$column] = 9999;

						}

					}

				}

		}

		return $pos;

	}

	function collectSalesData($unq_id, $track_id, $amount, $item, $commission, $date, $time)

	{

		return "$unq_id, $track_id, $amount, $item, $commission, $date, $time \n";

	}

	function storeSalesData($salesdata)

	{

		$salesdata = "Tran.ID, Tracking ID, Amount, Items, Commission, Date, Time\n".$salesdata;

		$file = "temp_data/ctmsalesdata.txt";

		$fp = @fopen($file, "w+");

		if($fp)

		{

			@fputs($fp, $salesdata, strlen($salesdata));

			@fclose($fp);

		}

		else

		{

			$file = "NO FILE GENERATED";

		}

		return $file;

	}

}

?>