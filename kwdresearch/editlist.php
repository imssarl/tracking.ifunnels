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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="stylesheets/style1.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="stylesheets/login.css" />
<link type="text/css" rel="stylesheet" href="<?php echo OUTER_CSS_PATH;?>stylesheets/style_new.css" />
<title>Creative Niche Manager</title>
</head>
<script>
<!--
	function doUpdate(x,y,z){
		
		if(confirm("Are you sure to update the keyword?")){
			
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
			var txt="txt"+z;
			var text=document.getElementById(txt).value;
			var url = "updatekwd.php";
			var params = "id="+x+"&text="+text;
			//alert(params);
			//return false;
			xmlHttp.open("POST", url, true);
			//Send the proper header information along with the request
			xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8"); 
			//xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//xmlHttp.setRequestHeader("Content-length", params.length);
			//xmlHttp.setRequestHeader("Connection", "close");
			xmlHttp.send(params);
			xmlHttp.onreadystatechange = function() {//Call a function when the state changes.
				if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
					document.getElementById("messagesave").style.display="block";
					document.getElementById("messagesave").innerHTML=xmlHttp.responseText;
					document.getElementById("xid").value=y;
					//document.frmDummy.action="<?php  //echo SERVER_PATH.substr($_SERVER['PHP_SELF'],1);?>?xid="+y;
					document.frmDummy.submit();	
					//window.close();				
				}
			}
			////////////////////////////////////
		}
		return false;
	}
	
	function reloadWindow(){
		
	}
//-->
</script>
<body>
<br/><br/>
<div id="messagesave" align="center"></div>
<br/><br/>
<form name="frmDummy" action="viewlist.php" >
<input type="hidden" name="xid" id="xid" value="<?php echo $_GET['xid'];?>" />
</form>
<p style="color:#FF0000; padding-left:10px">
	Edit the keyword by directly typing in the keyword box, and click 'Update Keyword' tool to save the updated keyword.
</p>
<div id="listing">

<form name="frmedit">
<?php
$xid=isset($_GET['xid'])?$_GET['xid']:(isset($_POST['xid'])?$_POST['xid']:'');
$sql="SELECT * from `".TABLE_PREFIX."kwd_savedkwds` where list_id='".$xid."'";
	$res=$database->getRS($sql);
	$xRow=$database->getRowsOfRS($res);
	if($xRow>0)
	{
?>
<table style='border:solid; border-width:1px; border-color:#666666' cellpadding=0 cellspacing=2 width = '90%' align='center'>
	<tr style='background:#646464; color:#FFFFFF'>
		<th width='40'><b>S.No</b></th>
		<th ><b>Keyword</b></th>
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
		<td ><input type="text" style="width:250px" id="txt<?php echo $sno;?>" name="txt[]" value="<?php echo $data['keyword'];?>"  /></td>
		<td width='20'><img src="../images/go-up.gif" onClick="doUpdate('<?php echo $data['kwdid'];?>','<?php echo $_GET['xid'];?>','<?php echo $sno;?>');" title="Update Keyword" style="cursor:pointer"></td>
		</tr>
	<?php
		$sno++;
		}
	?>	
</table>
</form>
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

</body>
</html>
