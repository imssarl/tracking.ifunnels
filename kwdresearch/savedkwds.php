<?php 
session_start();
//error_reporting(0);
//include("config.php");
chdir( '../' );
include("config/config.php");
chdir( dirname(__FILE__) );
require_once("../classes/database.class.php");
$database = new Database();
$database->openDB();
?>
<?php require_once("incheader.php"); ?>
<script language="javascript">
<!--
	function doView(x){
		test=window.open("viewlist.php?xid="+x, "mywindow" ,"status=0,scrollbars=1,width=400,height=500,resizable=0");
		test.moveTo(50,50);	
	}
	
	function doDuplicate(x){
		test=window.open("duplist.php?xid="+x, "mywindow" ,"status=0,scrollbars=0,width=200,height=200,resizable=0");
		test.moveTo(50,50);		
	}
	
	function doAdd(x){
		window.location.href="index.php?xid="+x+"&proc=add";		
	}
	
	function doEdit(x){
		test=window.open("editlist.php?xid="+x, "mywindow" ,"status=0,scrollbars=1,width=400,height=500,resizable=0");
		test.moveTo(50,50);	
	}
	
	function doDelete(x){
		if(confirm("Are you sure to delete the list?")){
			document.getElementById("loading").style.display="block";
			//////////////////////////////////////////////////////////////
			// To Delete List
			var xmlHttp;
			try
			{
				// Firefox, Opera 8.0+, Safari
				xmlHttp=new XMLHttpRequest();
			}
			catch (e)
			{
				// Internet Explorer
				try
				{
					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (e)
				{
					try
					{
						xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
					} 
					catch (e)
					{
						alert("Your browser does not support AJAX!");
						return false;
					}
				}
			}
			//alert(document.formName.h_var.value);
			var url = "listaction.php";
			var params = "id="+x+"&mode=delete";
			xmlHttp.open("POST", url, true);
			//Send the proper header information along with the request
			xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8"); 
			//xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//xmlHttp.setRequestHeader("Content-length", params.length);
			//xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(params);
			xmlHttp.onreadystatechange = function() {//Call a function when the state changes.
				if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
					document.getElementById("loading").style.display="none";
					document.getElementById("messagesave").style.display="block";
					document.getElementById("messagesave").innerHTML="";
					document.getElementById("messagesave").innerHTML=xmlHttp.responseText;
					//document.getElementById("respg").value=xmlHttp.responseText;
					document.frmdummy.submit();
				}
			}
			////////////////////////////////////
		}
		return false;
	}
//-->
</script>
<?php //require_once("inctop.php"); ?>
 <?php //require_once("incleft.php"); ?>
 <form name="frmdummy" action="savedkwds.php">
  </form>
<?php if(isset($_SESSION[SESSION_PREFIX.'sessionusername'])){?>
<table align="right" style="padding-left:50px;">	
	<TR>		
		<TD align="right" style="font-weight:bold;">
		Welcome <?php echo $_SESSION[SESSION_PREFIX.'sessionusername'];?>
		</TD>
	</TR>
</table><br><br>
 <font color="#001E71"><a href="../index.php" style="text-decoration: none">Home</a>>>Keyword Research</font><br/><br/>
	<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td valign = "top" align="center" class="heading">
			<a class="menu" href = "index.php">Search Keywords</a>  |  <a  class="menu" href = "savedkwds.php">Saved Keywords Selections</a>  
			</td>
		</tr>
	</table><br/><br/>
		<div id="loading" style="display:none">
			<img src='images/ajax-loader.gif'>
		</div>	
		 <div id="messagesave" align="center">
		 	<?php
				if(isset($_REQUEST['resp']))echo $_REQUEST['resp'];
			?>
		 </div>
	<br/><br/>
<?php
}
?>
<div id="listsaved">
<?php
$sql="SELECT * from `".TABLE_PREFIX."kwd_savedlist` where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
$res=$database->getRS($sql);
$xRow=$database->getRowsOfRS($res);
if($xRow>0)
{
?>
<table style='border:solid; border-width:1px; border-color:#666666' cellpadding=0 cellspacing=2 width = '90%' align='center'>
	<tr style='background:#646464; color:#FFFFFF'>
		<th width='40'><b>S.No</b></th>
		<th ><b>Title</b></th>
		<th width='20'>&nbsp;</th>
		<th width='20'>&nbsp;</th>
		<th width='20'>&nbsp;</th>
		<th width='20'>&nbsp;</th>
		<th width='20'>&nbsp;</th>
	</tr>
	<?php
		$sno=1;
		while($data=$database->getNextRow($res)){
		$kcount++;
		if($kcount%2==0)
			$xcolor="#FFFFFF";
		else
			$xcolor="#e1e1e1";
	?>		
	<tr style='background:<?php echo $xcolor?>'>
		<td width='18'><?php echo $sno?></td>
		<td ><font color='#000000'><?php echo $data['list_title'];?></font></td>
		<td width='20'><img src="../images/getcode.gif" onClick="doView('<?php echo $data['list_id'];?>');" title="View List" style="cursor:pointer"></td>
		<td width='20'><img src="../images/edit.png" onClick="doEdit('<?php echo $data['list_id'];?>');" title="Edit List" style="cursor:pointer"></td>
		<td width='20'><img src="../images/add.png" onClick="doAdd('<?php echo $data['list_id'];?>');" title="Add List" style="cursor:pointer"></td>
		<td width='20'><img src="../images/duplicate.png" onClick="doDuplicate('<?php echo $data['list_id'];?>');" title="Duplicate List" style="cursor:pointer"></td>
		<td width='20'><img src="../images/delete.png" onClick="doDelete('<?php echo $data['list_id'];?>');" title="Delete List" style="cursor:pointer"></td>
		</tr>
	<?php
		$sno++;
		}
	?>	
</table>
</div>
<?php
}
else
{
?>
<table style='border:solid; border-width:1px; border-color:#666666' cellpadding=0 cellspacing=2 width = '90%' align='center'>
	<tr style='background:#646464; color:#FFFFFF'>
		<th width='40'><b>No Records Found</b></th>
		
	</tr>
</table>
<?php } ?>
<?php require_once("incright.php"); ?>
<?php require_once("incbottom.php"); ?>