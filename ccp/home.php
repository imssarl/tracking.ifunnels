<?php
	session_start();

	require_once("config/config.php");
	require_once("classes/database.class.php");
	require_once("classes/admin.class.php");

	$damp_db = new Database();
	$damp_db->openDB();

	$admin_obj = new admin();
	$msg="";
	
	
	if(isset($_POST['login']) && $_POST['login']="Login")
	{
		$rs = $admin_obj->getAdminInfo();
		if($rs)
		{				
			$_SESSION[SESSION_PREFIX.'sessionadmin'] = $rs['email_address'];
			header("location: index.php");
		}
		else
		{
			$msg="Invalid email and/or password";
		}
	}	

?>

<?php require_once("incheader.php"); ?>

<title>

<?php echo SITE_TITLE; ?>
</title>

<script language="javascript">
	
function abc()

	{
		var col = document.frm;
		var flag = "true";
		var msg="";
		
		if(col.email.value=="")
		{
			flag = false;
			msg+="please enter your email\n";
		}

		
		if(col.password.value=="")
		{
			flag = false;
			msg+="please enter your password\n";
		}

		if(flag==false)

		{

		alert(msg);
		return(flag);

		}
	}

</script>

<?php require_once("inctop.php"); ?>
<?php require_once("incleft.php"); ?>

<br>
<br>
<br>
<br>
<br>
<br>
<form name="frm" action="" method="POST" onsubmit="return abc()">
	<table border="0" align="center">

		<tr>
		<td colspan="2" align="center"><?php echo $msg;?></td>	
		</TR>

		<TR><TD>Email Address:</TD>
		    <td><input type="text" name="email" size="30"></td>	
		</TR>


		<TR><TD>Password:</TD>
		   <td><input type="password" name="password" size="30"></td>	
		</TR>

		<TR><TD></TD>
		   <td></td>	
		</TR>

		<TR>
		    <td colspan="2" align="center">
		    	<input type="submit" name="login" value="Login">&nbsp;
		    	<input type="reset" name="reset" value="Reset"></td>
		</TR>

	</table>
	</form> 
	
<?php require_once("incright.php"); ?>
<?php require_once("incbottom.php"); ?>
