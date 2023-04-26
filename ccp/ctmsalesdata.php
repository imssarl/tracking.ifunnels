<?php





session_start();





require_once("config/config.php");





require_once("classes/database.class.php");





require_once("classes/settings.class.php");





require_once("classes/common.class.php");





require_once("classes/pagination.class.php");





require_once("classes/search.class.php");





require_once("classes/campaign.class.php");





require_once("classes/tracking.class.php");





require_once("classes/affiliate.class.php");





require_once("classes/ctmsalesdata.class.php");











$campaign= new campaign();





$track=new track();





$settings = new Settings();





$common = new Common();





$settings->checkSession();





$ms_db = new Database();





$ms_db->openDB();





$pg = new PSF_Pagination();





$sc = new psf_Search();





$affn = new affiliate();





$ctmsales = new CTMSales();











$pagefor = "record";





//////////////////////////////////





if (isset($_POST['process']))





	$process = $_POST['process'];





else if (isset($_GET['process']))





	$process = $_GET['process'];





else





	$process = "manage";











if(isset($_GET["msg"]))





	$msg = $_GET["msg"];





else





	$msg = "";	











if (isset($_GET["afn"]) && $_GET["afn"]>0)





	$afn = $_GET["afn"];





else





	$afn = -1;





	





if (isset($_POST["uploadcsv"]) && $_POST["uploadcsv"]=="yes")





{





	if ($process=="cbsd")





	{





		$msg = $ctmsales->uploadUpdateSalesDataForClickBank();





	}





	else if ($process=="lssd")





	{





		if ($_POST["datafrom"]=="auto")





			$msg = $ctmsales->autoUpdateSalesDataForLinkShare();





		else





			$msg = $ctmsales->uploadUpdateSalesData("ls");





	}





	else if($process=="cjsd")





	{





		if ($_POST["datafrom"]=="auto")





			$msg = $ctmsales->autoUpdateSalesDataForCommissionJunction();





		else





			$msg = $ctmsales->uploadUpdateSalesData("cj");





	}





	else if ($process=="mbsd" || $process == "ctsd" || $process == "cesd" || $process == "masd")





	{





		$msg = $ctmsales->uploadUpdateSalesData(substr($process,0,2));





	}



	//$msg=trim(nl2br($msg));	

	header("Location:ctmsalesdata.php?process=manage&msg=".$msg);





	//exit;





}





else if (isset($_POST["inputform"]) && $_POST["inputform"]=="yes")





{





	if ($process=="edit")





	{





		$ctmsales->updateRecord();





		header("location: ctmsalesdata.php?msg=Record no.".$_POST["sid"]." has been updated");





	}





	else if($process=="add")





	{











		$added = $ctmsales->addRecords();











		header("location: ctmsalesdata.php?msg=Total $added Record(s) added");





	}





}





else if($process=="delete")





{





	$ok = $ctmsales->deleteRecord($_GET["sid"]);





	header("location: ctmsalesdata.php?msg=Record no.".$_GET["sid"]." has been deleted");





}





else if ($process=="edit")





{





	$sales = $ctmsales->getRecordById($_GET["sid"]);





}

















if (isset($_GET["afn"]))





{





	$data = $affn->getAfnUserDetails($_GET["afn"]);





	if($data)





	{





		foreach($data as $idx=>$val)





			if ($val=="NULL" || $val == "")





				$data[$idx] = "";





	}





}			











		





		





if($process=="manage")





{





	$search_sql = "";





	$sql = "select count(*) from `".TABLE_PREFIX."salesdata` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];











	$totalrecords = $ms_db->getDataSingleRecord($sql);











	if($totalrecords !== false && $totalrecords > 0)





	{











		$pg->setPagination($totalrecords);						





		$order_sql = $sc->getOrderSql(array("id","track_id","item","amount","commission","date","time"),"id");











		$sql = "SELECT *  FROM ".TABLE_PREFIX."salesdata where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid']."





		".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;











		$sales_rs = $ms_db->getRS($sql);





	}





	else





	{





		$totalrecords = 0;





		$sales_rs = false;





	}		





}		











	





?>





<?php require_once("header.php"); ?>











<title>





<?php echo SITE_TITLE; ?>





</title>





<link type="text/css" rel="stylesheet" href="stylesheets/calendar.css?random=20051112" media="screen"></LINK>





<SCRIPT type="text/javascript" src="jscripts/calendar.js?random=20060118"></script>





<script language="javascript">





	function sopt(afn)





	{





		if (afn==1)





		{





			alert("No need to update sales data");





			location = 'ctmsalesdata.php?afn='+afn;			





		}





		else if (afn==2)





			location = 'ctmsalesdata.php?process=cjsd&afn='+afn;





		else if (afn==3)





			location = 'ctmsalesdata.php?process=lssd&afn='+afn;





		else if (afn==4)





			location = 'ctmsalesdata.php?process=cbsd&afn='+afn;





		else if (afn==5)





			location = 'ctmsalesdata.php?process=mbsd&afn='+afn;





		else if (afn==6)





			location = 'ctmsalesdata.php?process=ctsd&afn='+afn;





		else if (afn==7)





			location = 'ctmsalesdata.php?process=cesd&afn='+afn;





		else if(afn >7)





			location = 'ctmsalesdata.php?process=masd&afn='+afn;





	}





	function eddky(img)





	{





		img.className = 'noshow';





		document.getElementById("dkey").className = 'show';





	}





	





	





	function show(obj1, obj2)





	{





		document.getElementById(obj1).className = 'show';





		document.getElementById(obj2).className = 'noshow';	





	}





	function dtsd(sid)





	{





		if(confirm("Are you sure to delete record # "+sid))





			location = 'ctmsalesdata.php?process=delete&sid='+sid;





	}











	var tid = 0;





	var rid = 2;





	function addField_2( area, oID ) 





	{





	





		if( !document.getElementById ) return ; 





	





		var field_area = document.getElementById( area ) ;





		var lastRow = field_area.rows.length ;





		var iteration = lastRow ;





		var row = field_area.insertRow( lastRow ) ;





		row.className = 'tablematter1';





		var fld = Array();





		fld[7] = 'track_id';





		fld[6] = 'product_id';





		fld[5] = 'affiliate_network';





		fld[4] = 'item';





		fld[3] = 'amount';





		fld[2] = 'commission';		





		fld[1] = 'date';





		fld[0] = 'time';		





						





	





		for(var i=0; i<9; i++)





		{





			var cellLeft = row.insertCell(0) ;





			cellLeft.align = 'center';





			if(i==1)





			{		





				cellLeft.noWrap = 'true';





			}





			if(i==8)





			{





				var span = document.createElement('SPAN') ;





				span.id = 'sid_'+tid++ ;





				span.innerHTML = rid++;





				cellLeft.appendChild( span );





			}





						





			else





			{





				if(fld[i]=='affiliate_network')





				{	var g=0;





						var txt = document.createElement('select') ;





						txt.id = oID+tid ;





						txt.name = fld[i]+'[]' ;





						





					var oOption = document.createElement("OPTION");





					txt.options.add(oOption);





					oOption.text = "<-- Select Network -->";





					oOption.value = -1;





				





					for(g=0; g<k;g++){





					oOption = document.createElement("OPTION");





					txt.options.add(oOption);





					oOption.text = seltext[g];





					oOption.value = seltext[g];





					}cellLeft.appendChild( txt ) ;





				}





				else if( document.createElement ) 





				{ 





						











						





						var txt = document.createElement('input') ;





						txt.id = oID+tid ;





						txt.name = fld[i]+'[]' ;





						txt.type = 'text' ;





						txt.size = '15';











						if (fld[i]=='date')





						{





							txt.title = '[YYYY-MM-DD]';





							var img = document.createElement('img') ;





							img.id = 'img_'+tid;





							img.src = 'images/calendar.png';





							img.align="absmiddle";





							img.onclick = function()





							{





								displayCalendar(document.forms[0].eval((this.id).replace('img', 'text')),'yyyy-mm-dd',this);





							}





						}





						else if (fld[i]=='time')





							txt.title = '[HH:MM:SS]';





						else if (fld[i]!='product_id' && fld[i]!='affiliate_network')





						{





							txt.onblur = function()





							{





								 valnum(this)





							}





						}





						





						cellLeft.appendChild( txt ) ;





						if (fld[i]=='date')





						cellLeft.appendChild( img ) ;





						





						tid++;





				}





			}





		}





	}





	function valnum(txt)





	{





			if(txt.value != "")





			{





				var rx = /^[0-9. +-]+$/;





				if (!rx.test(txt.value))





				{





					alert("Invalid value : "+txt.value);





					txt.value = 0;





				}





			}











	}





// validation for select box
	function chkuploadForm(frm)
	{
		//var frm1=frm.form;
		if(document.getElementById("affiliate_network").value=="-1")
		{
			alert("Please Select Network");
			document.getElementById("affiliate_network").focus();
			return false;
		}
	
	}
	
// end of validation 






</script>











<?php require_once("top.php"); ?>











<?php require_once("left.php"); ?>





<div align="left">





<?php





$homebc = "&nbsp;<a href='../index.php'>Home</a> >> <a href='campaign.php'>My Campaigns</a> >> ";





if ($process=="manage")





	$pagebc = "Sales Data";





else





	$pagebc = "<a href='ctmsalesdata.php'>Sales Data</a> >> ";





$procbc = "";	





if ($process=="new") $procbc = "Update Data";





else if ($process=="add") $procbc = "Add Record(s)";





else if ($process=="edit") $procbc = "Edit Data";





else if ($process=="cbsd") $procbc = "ClickBank";





else if ($process=="lssd") $procbc = "LinkShare";





else if ($process=="cjsd") $procbc = "Commission Junction";











echo $homebc.$pagebc.$procbc;





?>





</div>





<div class="message">





<?php echo $msg ?>





</div>





<br>





<?php if($process=="manage") { ?>











<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">





	<tr>





		<TD valign = "top" align="center" class="heading"> 





<a href="?process=new">Update Sales Records</a> | <a href="?process=add">Manually add Sales Records</a> 





</TD></tr></table>





<br>





<?php if($totalrecords > 0) { ?>





	<table align="center">





		<tr>





			<td colspan="12">





				<?php $pg->showPagination(); ?>





			</td>





		</tr>





	</table>





	<?php } ?>











<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">





<tr>





<th><a href="?sort=id">ID</a></th>





<th><a href="?sort=track_id">Track ID</a></th>





<th><a href="?sort=product_id">Product ID</a></th>





<th><a href="?sort=affiliate_network">Affiliate Network</a></th>





<th><a href="?sort=item">Sold Items</a></th>





<th><a href="?sort=amount">Sales</a></th>





<th><a href="?sort=commission">Commission</a></th>





<th><a href="?sort=date">Date</a></th>





<th><a href="?sort=time">Time</a></th>





<th width="16px">&nbsp;</th>





<th width="16px">&nbsp;</th>





</tr>





<?php 





	if($sales_rs)





	{





		while($sales = $ms_db->getNextRow($sales_rs))





		{





		?>





		<tr  id="row<?php echo $id ?>"  class='<?php echo ($tblmat++%2) ? "tablematter1" : "tablematter2" ?>' >





		<td align="center"><?php echo $sales["id"] ?> </td>





		<td align="center"><?php echo $sales["track_id"] ?> </td>





		<td align="center"><?php echo $sales["product_id"] ?> </td>





		<td align="center"><?php echo $sales["affiliate_network"] ?> </td>





		<td align="center"><?php echo $sales["item"] ?> </td>





		<td align="center"><?php echo $sales["amount"] ?> </td>





		<td align="center"><?php echo $sales["commission"] ?> </td>





		<td align="center"><?php echo $sales["date"] ?> </td>





		<td align="center"><?php echo $sales["time"] ?> </td>





		<td>





		<a href="?process=edit&sid=<?php echo $sales["id"]?>">





			<img src="images/edit.png" title="Edit this record">





		</a>





		</td>





		<td>





			<img src="images/delete.png" title="Delete this record" onClick="dtsd(<?php echo $sales["id"]?>)">





		</td>











		</tr>





		<?php





		}





	}





	else





	{





		echo "<tr><td colspan='9' align='center'>No sales record found</td></tr>";





	}





	





?>





</table>





<?php } else if ($process=="edit" || $process=="add") { ?>





<form name="editrecords" method="post" action="ctmsalesdata.php">





<table width="95%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary" id="inputgrid">





<tr><th colspan="9" align="center"> <?php ($process=="edit") ? "Edit" : "Add" ; ?> Records</th></tr>





<tr>





<th>ID</th>





<th>Track ID</th>





<th>Product ID</th>





<th>Affiliate Network</th>





<th>Sold Items</th>





<th>Sales</th>





<th>Commission</th>





<th>Date</th>





<th>Time</th>





</tr>





<tr  id="row<?php echo $id ?>"  class='tablematter1' >





<td align="center">





<span id="sid_a">





<?php echo (isset($sales["id"]) && $sales["id"]!="") ? $sales["id"] : 1 ; ?>





</span>





<input type="hidden" name="sid" value="<?php echo $sales["id"] ?>">





</td>





<td align="center">





<input type="text" size="15" onBlur="valnum(this)" name="track_id[]" id="track_id_a" value="<?php echo $sales["track_id"] ?>">





</td>





<td align="center">





<input type="text" size="15" name="product_id[]" id="product_id_a" value="<?php echo $sales["product_id"] ?>">





</td>





<td align="center" id="drop" >





<?php echo $campaign->affiliateselectbox_manualy($sales["affiliate_network"]);  ?>





<!--<input type="text" size="15" name="affiliate_network[]" id="affiliate_network_a" value="<?php //echo $sales["affiliate_network"] ?>">-->





</td>





<td align="center">





<input type="text" size="15" onBlur="valnum(this)" name="item[]" id="item_a" value="<?php echo $sales["item"] ?>">





 </td>





<td align="center">





<input type="text" size="15" onBlur="valnum(this)" name="amount[]" id="amount_a" value="<?php echo $sales["amount"] ?>">





</td>





<td align="center">





<input type="text" size="15" onBlur="valnum(this)" name="commission[]" id="commission_a" value="<?php echo $sales["commission"] ?>">





</td>





<td align="center" nowrap="true" >





<input type="text" size="15"  name="date[]" id="date_a" value="<?php echo $sales["date"] ?>" title="[YYYYY-MM-DD]"><img align="absmiddle" src="images/calendar.png" onclick="displayCalendar(document.forms[0].date_a,'yyyy-mm-dd',this)">





</td>





<td align="center">





<input type="text" size="15"  name="time[]" id="time_a" value="<?php echo $sales["time"] ?>" title="[HH:MM:SS]">





</td>





</tr>





</table>





<table width="90%"   border="0" cellspacing="1" cellpadding="1" align="center" class="summary">





<tr><td colspan="7">&nbsp;</td></tr>





<tr><td colspan="7" class="heading">





	<?php if ($process=="add") { ?>





	<input type="submit" value="Save">&nbsp;&nbsp;





	<input type="button" value="Add another record" onclick="addField_2('inputgrid','text_');" />





	<?php } else { ?>





	<input type="submit" value="Update">





	<?php } ?>





</td></tr>





</table>











<input type="hidden" name="inputform" value="yes">





<input type="hidden" name="process" value="<?php echo $process ?>">











</form>

















<?php } else { ?>





<form name="uploadcsv" method="POST" action="ctmsalesdata.php" onSubmit="return chkuploadForm(this)" enctype="multipart/form-data">





<table width="90%"  border="0" cellspacing="0" cellpadding="0" class="inputform">





          <tr>





            <td align="left" width="40%" class="heading" colspan="2">Update sales data</td>





			</tr>





			<tr height="10px"><td colspan="2">&nbsp;</td></tr>			





			<tr>





				<TD align="right" width="50%">Affiliate Network:</TD>





				<TD align="left" width="50%">





				<?php  echo $campaign -> affiliateselectbox($afn); ?>





				</TD>





			</tr>





<?php if($process=="cjsd" || $process=="lssd") { ?>





<tr>





<td align="center" colspan="2">











<input type="radio" name="datafrom" value="auto"  checked="checked" onclick="show('auto','upld')"> Update Automatically 





<input type="radio" name="datafrom" value="upload"  onclick="show('upld','auto')"> Upload Sales Data





<br><br>





</td>





<td>&nbsp;





</td>





</tr>











<?php } ?>











<?php if ($process=="cbsd" || $process=="mbsd" || $process== "ctsd" || $process == "cesd"  || $process == "masd") { ?>	





<tr><td colspan="2" height="10pxx"></td></tr>





		





			<tr>





				<TD align="right">Upload CSV:</TD>





				<TD align="left">





					<input type="file" name="csvfile" size="35">





				</TD>





			</tr>





			<tr><TD  align="left" colspan="2">





			<?php





				if($process=="cbsd")





				{





			?>





				<font color="red">Note:Your CSV file should follow this order</font><br><br>





				[Date('YYYY-MM-DD'), Time(hh:mm), Receipt, TID, Pmt, Txn Type, Item, Amount, Publisher, Affiliate, CC, St., Last Name, First Name, Email]





							





			<?php	





				}





				else





				{	





			?>





				<font color="red">Note:Your CSV file should follow this order</font><br><br>["TrackingID", "Order", "Sales", "Quantity", "Commissions", "Date('YYYY-MM-DD')", "Time(hh:mm:ss)"]





			<?php





				}





			?>





			</TD></tr>





			





<?php }  else if ($process=="lssd") { ?>











<tr>





<td colspan="2">











<div id="upld" align="center" class="noshow">





	<table class="inputform">





			<tr>





				<TD align="right" width="50%">Upload CSV:</TD>





				<TD align="left">





					<input type="file" name="csvfile" size="35">





				</TD>





			</tr>





			<tr><TD  align="left" colspan="2"><font color="red">Note:Your CSV file should follow this order</font><br><br>["Member", "Order", "Sales", "Quantity", "Commissions", "Date('YYYY-MM-DD')", "Time(hh:mm:ss)"]</TD></tr>





	</table>





</div>





<div id="auto">





	<table class="inputform">





		





			<tr>





				<TD align="right" width="50%">User ID:</TD>





				<TD align="left">





					<input type="text" name="userid" value="<?php echo $data["val1"] ?>">





				</TD>





			</tr>





			<tr>





				<TD align="right">Password:</TD>





				<TD align="left">





					<input type="text" name="passwd" value="<?php echo $data["val2"] ?>">





				</TD>





			</tr>





			<tr>





				<TD align="right">Affiliate ID:</TD>





				<TD align="left">





					<input type="text" name="affid" value="<?php echo $data["val3"] ?>">





				</TD>





			</tr>





	





			<tr>





				<TD align="right">Begin date:</TD>





				<TD align="left">





					<input type="text" name="bdate" id="bdate">





					<input type="button" value="Pick" onclick="displayCalendar(document.forms[0].bdate,'yyyy/mm/dd',this)">						





				</TD>





			</tr>





			<tr>





				<TD align="right">End date:</TD>





				<TD align="left">





					<input type="text" name="edate" id="edate">





					<input type="button" value="Pick" onclick="displayCalendar(document.forms[0].edate,'yyyy/mm/dd',this)">						





					





				</TD>





			</tr>





			<tr>





				<TD align="right">Network:</TD>





				<TD align="left">





				<select name="netwid">





				<option value="1" <?php if($data["val4"]=='1') echo "Selected" ?>>LinkShare Network (United States)</option>





				<option value="5" <?php if($data["val4"]=='5') echo "Selected" ?>>LinkShare Canada</option>





				<option value="4" <?php if($data["val4"]=='4') echo "Selected" ?>>LinkShare UK</option>





				<option value="54" <?php if($data["val4"]=='54') echo "Selected" ?>>LinkShare Lead Advantage</option>





				</select>





				</TD>





			</tr>





	</table>





</div>	





			





</td>





</tr>				





			





<?php }  else if ($process=="cjsd") { ?>





<tr>





<td colspan="2">





<div id="upld" align="center" class="noshow">





	<table class="inputform">





			<tr>





				<TD align="right" width="50%">Upload CSV:</TD>





				<TD align="left">





					<input type="file" name="csvfile" size="35">





				</TD>





			</tr>





			<tr><TD  align="left" colspan="2"><font color="red">Note:Your CSV file should follow this order</font><br><br>["sId", "originalActionId", "saleAmount","items", "commissionAmount", "eventDate", "time(hh:mm:ss)"]</TD></tr>











	</table>





</div>





<div id="auto">





	<table class="inputform">











			<tr>





				<TD align="right" width="50%">Transaction date:</TD>





				<TD align="left">





					<input type="text" name="tdate" id="tdate">





					<input type="button" value="Pick" onclick="displayCalendar(document.forms[0].tdate,'mm/dd/yyyy',this)">						





				</TD>





			</tr>





			<tr>





				<TD align="right">Developer Key:</TD>





				<TD align="left">&nbsp;&nbsp;





				<a href="#" onClick="eddky(this); return false;" title="Modify Developer Key">Modify Developer Key</a>





				<textarea class="noshow" cols="55" rows="2" id="dkey" name="devkey"><?php echo $data["valt"] ?></textarea>





				<input type="hidden" name="datetype" value="event">





				</TD>





			</tr>





	</table>





</div>	





			





</td>





</tr>			





<?php } ?>             





		<tr height="10px"><td colspan="2">&nbsp;</td></tr>





           <tr>





		   <td colspan="2" align="center" class="heading">





                <input type="submit" name="Submit" value="Save">





                <input type="hidden" name="process" value="<?php echo $process ?>">





                <input type="hidden" name="uploadcsv" value="yes">				





			</td>





            </tr>











        </table>





</form>





<?php } ?>





<br>





<?php require_once("right.php"); ?>





<?php require_once("bottom.php"); ?>











