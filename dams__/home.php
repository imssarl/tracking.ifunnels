<?php
	session_start();

	require_once("config/config.php");
	require_once("classes/database.class.php");
	//require_once("classes/admin.class.php");
	require_once("classes/user.class.php");

	$damp_db = new Database();
	$damp_db->openDB();

	//$admin_obj = new admin();
	$user_obj = new User();
	$msg="";
	
	
	if(isset($_POST['login']) && $_POST['login']="Login")
	{
// 		$rs = $admin_obj->getAdminInfo();
// 		if($rs)
// 		{				
// 			$_SESSION[SESSION_PREFIX.'sessionadmin'] = $rs['email_address'];
// 			header("location: index.php");
// 		}
// 		else
// 		{
			$url="http://sales.ethiccash.com/2/action/Jin/APIUser/authorize.txt?gate_id=5&secret=testapi&email=".$_POST['email']."&password=".$_POST['password']."";
		
			$handle = fopen($url, "r");
			$contents = '';
			while (!feof($handle)) {
			$contents .= fread($handle, 8192);
			}
			$action=preg_split ("/\s+/",$contents);
			if($action[0]=="ERROR")
			{
				$msg=$contents;
			}
			else
			{   
				$contents="";
				
				for($i=0;$i<count($action);$i++)
				{
					if($action[$i]!="")
					{
					$temp=explode("=",$action[$i]);
					$contents[$temp[0]]=$temp[1];
					}
				}
				
				$rs = $user_obj->getUserInfo();
				if($rs)
				{	$user_obj->updateUser($contents);
					$rs = $user_obj->getUserInfo();
					if($rs)
					{
					$_SESSION[SESSION_PREFIX.'sessionuser'] = $rs['fname']." ".$rs['lname'];	
					header("location: index.php");
					}
				}
				else
				{
					$rs = $user_obj->getUserEmail();
					if($rs)
					{
						$user_obj->editUser();
						$user_obj->updateUser($contents);
						$rs = $user_obj->getUserInfo();
						if($rs)
						{
						$_SESSION[SESSION_PREFIX.'sessionuser'] = $rs['fname']." ".$rs['lname'];	
						header("location: index.php");
						}
					}
					else
					{	//echo $contents['fname']; die();
						$msg= "! Please Enter Correct Email Id and Password";
					}
				}
				
			}
	
		//}
		
		
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
		<td colspan="2" align="center" style="color:red;"><?php echo $msg;?></td>	
		</TR><tr><TD><br></TD></tr>

		<TR><TD>Email Address:</TD>
		    <td><input type="text" name="email" size="30"></td>	
		</TR>


		<TR><TD>Password:</TD>
		   <td><input type="password" name="password" size="30" ></td>	
		</TR>

		<TR><TD></TD>
		   <td></td>	
		</TR>

		<TR>
		    <td colspan="2" align="right">
		    	<input type="submit" name="login" value="Login">&nbsp;
		    	<input type="reset" name="reset" value="Reset">	
		</TR>
		<TR>
		    <td align="right" colspan="2"><a href="http://sales.ethiccash.com/2" target="_blank">Retrieve Password?</a></td>
		    </td>
		</TR>

	</table>
	</form> 
	
<?php require_once("incright.php"); ?>
<?php require_once("incbottom.php"); ?>
