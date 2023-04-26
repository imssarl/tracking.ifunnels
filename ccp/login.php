<?php
session_start();

require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/settings.class.php");
require_once("classes/common.class.php");
$common = new Common();
$settings = new Settings();
$settings->checkForInstallationFiles();

$ms_db = new Database();
$ms_db->openDB();
$settings->checkSession();
$rightuser = $settings->chwckLogin();

	$_SESSION[SESSION_PREFIX.'sessionuserid'] = $rightuser['id'];
	$_SESSION[SESSION_PREFIX.'sessionusername'] = $rightuser['username'];
	$_SESSION[SESSION_PREFIX.'sessionuseremail'] = $rightuser['email_address'];
	$_SESSION['user_type']="admin";
	$_SESSION['sessionGen'] = $_POST["username"];
	header("location: index.php");
	exit;

// if (isset($_POST['login']) && $_POST['login'] == "yes")
// {
// $ms_db = new Database();
// $ms_db->openDB();
// $settings = new Settings();
// $rightuser = $settings->chwckLogin();
// if ($rightuser)
// {
// $_SESSION[SESSION_PREFIX.'sessionuserid'] = $rightuser['id'];
// $_SESSION[SESSION_PREFIX.'sessionusername'] = $rightuser['username'];
// $_SESSION[SESSION_PREFIX.'sessionuseremail'] = $rightuser['email_address'];
// $_SESSION['user_type']="admin";
// $_SESSION['sessionGen'] = $_POST["username"];
// header("location: index.php");
// exit;
// } else {
// $msg = "Invalid username or password";
// }
// }




?>

<?php require_once("header.php"); ?>

<title>
<?php echo SITE_TITLE; ?>

</title>

<script language="javascript">
	function chkform(form)
	{
	mess  = "";

	if (form.username.value == "")
		mess += "- Username can't be blank\n";
	if (form.password.value == "")
		mess += "- Password can't be blank\n";
		
			if (mess.length>0) {
				mess = "The following errors occured\n"+mess;
				alert (mess);
				return false;
			} else {
				return true;
			}
	}
</script>

<!-- <link href="stylesheets/style1.css" rel="stylesheet" type="text/css"> -->
<?php require_once("top.php"); ?>



<?php require_once("left.php"); ?>
  <table width="100%"   border="0" cellspacing="0" cellpadding="0">
  <form name="login" id="login" method="post" action="login.php" onSubmit="return chkform(this)">
    <tr>
      <td width="100%" valign="middle" align="center">
	  <table width="300" border="0" cellpadding="0" cellspacing="0" class="summary2">
<tr>
<td colspan="2"><div align="center"><?php echo (isset($msg)) ? $msg : ""; ?></div></td>
</tr>
<tr><td colspan="2" align="left" class = "heading">Login Form</td>
</tr>
        <tr>
          <td align="right">User name: </td>
          <td align="left">
            <input name="username" type="text" id="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : "" ?>" size="30" maxlength="255">
          </td>
        </tr>
        <tr>
          <td align="right">Password : </td>
          <td align="left">
            <input name="password" type="password" id="password" value="" size="30" maxlength="255">
		</td>
        </tr>
        <tr>
          <td colspan="2">
            <div align="center">
              <input type="submit" name="Submit" value="Login">
              <input type="hidden" name="login" value="yes">			  
            </div>
			</td>
          </tr>
      </table></td>
    </tr>
</form>	
  </table>

	<!-- html code will come here -->
	
<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>
