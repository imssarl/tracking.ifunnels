<?php 
require_once("config/config.php"); 
require_once("classes/campaign.class.php");
require_once("classes/tracking.class.php");
require_once("classes/database.class.php");
require_once("classes/affiliate.class.php");

$campaign= new campaign();
$track= new track();
$affiliate = new affiliate();
$ms_db = new Database();
$ms_db->openDB();

if(isset($_POST['update']))
{
	$filename = $_POST['filename'];
	$somecontent = $_POST['contents'];


/*	if (is_writable($filename)) {

    if (!$handle = fopen($filename, 'a')) {
         echo "Cannot open file ($filename)";
         exit;
    }


    if (fwrite($handle, $somecontent) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }



    fclose($handle);

	} else {
    echo "The file $filename is not writable";
	}*/
	
	$site["ftp_address"] = "www.qjmp.com";
	$site["ftp_username"] = "qjmp";
	$site["ftp_password"] = "xtba3yhc";
	$remote_file = ROOT_PATH.$_POST['file'];
	$local_file = ROOT_PATH.$_POST['file'];
	$code = $_POST['contents'];

			$conn_id = @ftp_connect($site["ftp_address"]); 
// 			echo "aaa";die();	
			$login_result = @ftp_login($conn_id, $site["ftp_username"], $site["ftp_password"]); 
@ftp_pasv( $conn_id, true );
			$str = $remote_file;
			$cut = $site['ftp_username'];
			$ftphomepage = $common->getFTPhomePageAdv($str, $cut);
			$remote_path = $ftphomepage;

			if (($conn_id) && ($login_result)) 
			{
				$fp = @fopen($local_file,"w+");
				if($fp)
				{		
					@fputs($fp,$code);
					fclose($fp);
					$load = @ftp_put($conn_id, $remote_path, $local_file, FTP_BINARY); 
					@ftp_close($conn_id);
					if ($load)
						$msg = "File has been updated successfully";
					else
						$msg = "Error in updating remote file";				
				}
				else
				{
					$msg = "Unable to create local copy of file to upload";
				}
				header("location: campaign.php?msg=$msg");
			}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
</head>
<script language="JavaScript">
function closeme()
{
window.close();
}
</script>
<link href="stylesheets/style1.css" rel="stylesheet" type="text/css">
<body>

<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" class="summary2" align="center">
<tr><TD align="center" class="heading" valign="top">Edit Code</TD></tr>

<TR><TD align="center">
<?php

if(isset($_GET["file"]) && $_GET["file"]!="")
{

	@fopen("trackingpages",'r');
	$filename = "trackingpages/".$_GET["file"];
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	//print_r($contents);
	fclose($handle);
?>
<form name="form" action="#" method="POST">
<TEXTAREA name="contents"  rows="16" cols="90"><?php echo ($contents); ?></TEXTAREA>

</TD>
</TR>
<?php
}
else
{
		echo "invalid request";
}
?>
</TD></TR>
<tr><TD  class="heading">
<input  type="hidden" name="filename" value="<?php echo $filename; ?>">
<input type="submit" name="update" value="Update">
<input  type="hidden"  name="file" value="<?php echo $_GET['file'];?>">
</form></TD></tr>
</table>

</body>
</html>