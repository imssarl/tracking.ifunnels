<?php
session_start();

	require_once("config/config.php");
	require_once("classes/database.class.php");
	require_once("classes/admin.class.php");
	require_once("classes/common.class.php");
	require_once("classes/settings.class.php");
	$damp_db = new Database();
	$common_obj = new Common();
	$damp_db->openDB();
	$settings = new Settings();
	$settings->checkSession();
	$admin_obj= new admin();

	if(isset($_POST['submit']))
	{
		$res_edit = $admin_obj->editAdminSetting(); 
	}	

	$res_view = $admin_obj->ViewAdminSetting();
?>

<?php require_once("header.php"); ?>

<title>

<?php echo SITE_TITLE; ?>
</title>
<link href="../stylesheets/style1.php" rel="StyleSheet" type="text/css"> 
<script language="javascript">
function abc()

	{
		var col = document.frm;
		var flag = "true";
		var msg="";
		
		if(col.aname.value=="")
		{
			flag = false;
			msg+="Administrator name can not kept empty\n";
		}

		
		if(col.aemail.value=="")
		{
			flag = false;
			msg+="Administrator email can not kept empty\n";
		}

		if(col.aopass.value=="")
		{
			flag = false;
			msg+="Administrator password can not kept empty\n";
		}
				
		if(col.npass.value!=col.rnpass.value)
		{

			alert("Password did not matched");
			return false;		
		
		}

		if(flag==false)
		{
		alert(msg);
		return(flag);
		}
	
	}

</script>

<?php require_once("top.php");?>
<?php require_once("left.php");?>
<form name="frm" method="POST" action="" onsubmit= "return abc()">
<br>
<br>
<br>
<br>
<table border="0" align="center" class="summary2">

	<TR>
		<td colspan="2" align="center">
			<?php
			if(isset($_POST['submit']))
			{
				echo "Information Updated successfully";
			}		
			?>
		</td>
	</TR>
	<TR>
		<td colspan="2" align="left" class="error">
			Fields marked * are mandatory		
		</td>
	</TR>
	<TR>
		<TD >Administrator Name <span class="error">*</span></TD>
		<td><Input type="text" name="aname" size="30" value="<?php echo $res_view['username']?>"></td>
	</TR>

	<TR>
		<TD>Administrator Email <span class="error">*</span></TD>
		<td><Input type="text" name="aemail" size="30" value="<?php echo $res_view['email_address']?>"></td>
	</TR>

	<TR>
		<TD>Old Password</TD>
		<td><Input type="password" name="aopass" size="30"  value="<?php echo $res_view['password']?>" readonly="true"></td>
	</TR>

	<TR>
		<TD>New Password</TD>
		<td><Input type="password" name="npass" size="30"></td>
	</TR>	

	<TR>
		<TD>Retype New Password</TD>
		<td><Input type="password" name="rnpass" size="30"></td>
	</TR>	

	<TR>
		<TD></TD>
		<td></td>
	</TR>

	<TR>		
		<TD colspan="2" align="center"><input type="submit" name="submit" value="Update">
	</TR>

</table>	
</form>
<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>
