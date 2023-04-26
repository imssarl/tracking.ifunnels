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

if($_POST){
	$title=$_POST['txtTitle'];
	$id=$_POST['hid'];
	
	$sql="INSERT INTO `".TABLE_PREFIX."kwd_savedlist` (`list_title`,`user_id`) VALUES ('".$title."','".$_SESSION[SESSION_PREFIX.'sessionuserid']."')";
	$resid=$database->insert($sql);
	
	$sql="SELECT * from `".TABLE_PREFIX."kwd_savedkwds` where list_id='".$id."'";
	$res=$database->getRS($sql);
	
	while($data=$database->getNextRow($res))
	{
		$sqlKwd="INSERT INTO `".TABLE_PREFIX."kwd_savedkwds` (`keyword`,`list_id`) VALUES ('".$data['keyword']."','".$resid."')";
		$result=$database->insert($sqlKwd);
	}
?>
	<script language="javascript">
		<!--
			window.opener.location="savedkwds.php?resp=The list has been duplicated successfully";
			window.close();
		//-->
	</script>	
<?php	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<form name="frmduplicate" action="duplist.php" method="post">
	List Title: <input type="text" id="txtTitle" name="txtTitle"  /> &nbsp;&nbsp; <input type="hidden" id="hid" name="hid"  value="<?php echo $_GET['xid'];?>" /> <input type="submit" value="Submit" name="cmdsub" />
</form>
</body>
</html>
