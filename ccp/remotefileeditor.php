<?php

session_start();

set_time_limit(0);

require_once("config/config.php");

require_once("classes/database.class.php");

require_once("classes/settings.class.php");



require_once("classes/common.class.php");

require_once("classes/pagination.class.php");

require_once("classes/search.class.php");



require_once("classes/tracking.class.php");







$settings = new Settings();

$settings->checkSession();



$common = new Common();

$ms_db = new Database();

$ms_db->openDB();



$track = new track();



$stphp = '<!--#ST#PHP#?';

$etphp = '?#ET#PHP#-->';

$dlphp = '#DL#PHP#';



$phpst = '<?';

$phpet = '?>';

$phpdl = '$';







$msg = "";

if (isset($_POST['process']))

{

	$process = $_POST['process'];

}

else if (isset($_GET['process']))

{

	$process = $_GET['process'];

}



if (isset($_GET['aid']))

{

	$aid = $_GET['aid'];

}

else if (isset($_POST['aid']))

{

	$aid = $_POST['aid'];

}



if(isset($_GET["sameserver"]) && $_GET["sameserver"]=="yes")

{

	if($process=="delfile")

	{

		$filename = ROOT_PATH."trackingpages/".$_GET['serverfile'];	

		$msg = "File ".$filename." has been deleted";

		$track->deleteTrackPageDetails($_GET["rfid"]);

		header("location: campaign.php?msg=$msg");

	}

	if($process=="editfile")

	{

		$sameserver=$_GET["sameserver"];

	

		@fopen("trackingpages",'r');

		$remote_file = ROOT_PATH."trackingpages/".$_GET["serverfile"];

	

		$handle = fopen($remote_file, "r");

		$remotecontent = fread($handle, filesize($remote_file));

		//print_r($contents);

		fclose($handle);

	}

}

if(isset($_POST["sameserver"]) && $_POST["sameserver"] != "")

{

	if (($process=="editfile" && $_POST["fromeditor"]=="yes") || $process == "updatefile")

	{

		if (get_magic_quotes_gpc()) 

			$code = stripslashes($_POST["remotecontent"]);

		else 

			$code = $_POST["remotecontent"];

		$code = $common->decodePhpTags($code, $stphp, $phpst);

		$code = $common->decodePhpTags($code, $etphp, $phpet);

		$code = $common->decodePhpTags($code, $dlphp, $phpdl);

		$remotecontent = $code;

	}

		if ($_POST["fromeditor"] != "yes")

		{ 

			if ($process=="updatefile")

			{

				$fp = @fopen($_POST['remote_file'],"w+");

				if($fp)

				{

					@fputs($fp,$code);

					fclose($fp);

					$msg = "Sucessfully updated local file";

				}

				else

				{

					$msg = "Unable to update";

				}

				header("location: campaign.php?msg=$msg");

				die();

			}

		}

}



if (isset($_GET["remotefile"]) && $_GET["remotefile"]=="yes")

{

	$_POST["site_id1"] = $_GET["site_id1"];

	$_POST["remotefile"] = $_GET["remotefile"];

	$_POST["remote_file"] = $_GET["remote_file"];

}



if (isset($_POST["remotefile"]) && $_POST["remotefile"] != "")

{

		if (isset($_POST["site_id1"]) && $_POST["site_id1"] != 0)

		$site_id =  $_POST["site_id1"];

		$load = false;

		$remote_file = $_POST["remote_file"];



		if (isset($_POST["editor"]) && $_POST["editor"] == "html")

			$editin = "html";

		else

		{

			if (substr($common->getExt($remote_file),0,3)!="htm")

				$alert = true;

			$editin = "text";

		}

		

		if (($process=="editfile" && $_POST["fromeditor"]=="yes") || $process == "updatefile")

		{

			if (get_magic_quotes_gpc()) 

				$code = stripslashes($_POST["remotecontent"]);

			else 

				$code = $_POST["remotecontent"];

			$code = $common->decodePhpTags($code, $stphp, $phpst);

			$code = $common->decodePhpTags($code, $etphp, $phpet);

			$code = $common->decodePhpTags($code, $dlphp, $phpdl);

			$remotecontent = $code;

		}

		if ($_POST["fromeditor"] != "yes")

		{

			$local_file = "temp_data/rf_".substr(md5(rand() * time()),0,4).".rmt";



			$site = array();

//  			if (isset($_POST["type"]) && $_POST["type"]==3)

			if(isset($aid) && $aid>0)

			{

				$site_info = $track->getSiteByAdId($aid);

				$site["ftp_address"] = $site_info["ftp_address"];

				$site["ftp_username"] = $site_info["ftp_username"];

				$site["ftp_password"] = $site_info["ftp_password"];

			}

			else

  			{

				$site["ftp_address"] = $_POST["ftp_address"];

				$site["ftp_username"] = $_POST["ftp_username"];

				$site["ftp_password"] = $_POST["ftp_password"];

  			}

//  			else

//  				$site = $sites->getSiteByID($site_id);

				

			$conn_id = @ftp_connect($site["ftp_address"]); 

			$login_result = @ftp_login($conn_id, $site["ftp_username"], $site["ftp_password"]); 
@ftp_pasv( $conn_id, true );
//			$str = $site['ftp_homepage'];

			$str = $remote_file;

			$cut = $site['ftp_username'];

			$ftphomepage = $common->getFTPhomePageAdv($str, $cut);

			$remote_path = $ftphomepage;



			if (($conn_id) && ($login_result)) 

			{

				if ($process=="updatefile")

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

				else if ($process=="editfile")

				{

					$load = @ftp_get($conn_id, $local_file, $remote_path , FTP_BINARY);

					if ($load) 

					{

						$remotecontent = "";

						$fp = @fopen($local_file,"r");

						if($fp)

						{		

							while(!@feof($fp))

							{

								$remotecontent .= @fgets($fp);

							}

							@fclose($fp);

						}

						else 

						{

							$remotecontent = "";

						}				

					}

					else

					{

						$msg = "There was a problem in downloading the file: $remote_file";

						$process="selrmtfil";

					}

				}

				else if ($process=="delfile")

				{

					if (@ftp_delete($conn_id, $remote_path)) 

					{

					 	$msg = "File $remote_path has been deleted";

						$track->deleteTrackPageDetails($_GET["rfid"]);

					} 

					else 

					{

					 	$msg = "Could not delete $remote_path";

					}

					header("location: campaign.php?msg=$msg");

				}

				@ftp_close($conn_id);

			}

			else

			{

				$msg = "Can not connect to FTP server, please check details";		

				$process="selrmtfil";

			}

		}

}



?>



<?php require_once("header.php"); ?>



<title>

<?php echo SITE_TITLE; ?>

</title>

<script src="jscripts/ajax.js"></script>

<?php if ($editin == "html") { ?>

<script language="javascript" type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>

<script language="javascript" type="text/javascript">

	// Notice: The simple theme does not use all options some of them are limited to the advanced theme

	tinyMCE.init({

		theme : "advanced", 

		mode : "textareas",

		plugins : "fullpage, codeprotect, php",		

		apply_source_formatting: true,

		remove_linebreaks : false,

		force_p_newlines : false,

		verify_html : false,

		valid_elements: "*[*]",

		preformatted : true,

		php_external_list_url : "./tinymce/jscripts/tiny_mce/plugins/php/examples/example_php_list.js",

		theme_advanced_buttons3_add : "fullpage"

	});

	tinyMCE.importPluginLanguagePack('codeprotect', 'en');



<?php } ?>

</script>

<script language="javascript">

// function showList(type)

// {

// 	hideNewFTPBlock(type.value);

// 	if (type.value=="2") {

// 		document.getElementById("sitelist").style.display = 'block';

// 		document.getElementById("packagelist").style.display = 'none';

// 		document.getElementById("packagesitelist").style.display = 'none';

// 	}

// 	else  if (type.value=="1") {

// 		document.getElementById("sitelist").style.display = 'none';

// 		document.getElementById("packagelist").style.display = 'block';

// 		document.getElementById("packagesitelist").style.display = 'block';

// 	} else {

// 		document.getElementById("packagesitelist").style.display = 'none';

// 		document.getElementById("sitelist").style.display = 'none';

// 		document.getElementById("packagelist").style.display = 'none';

// 	}

// }

function hideNewFTPBlock(type)

{

	var nsf = document.getElementById("newsiteftp");

	if (type=="3") 

		nsf.className = 'show';

	else

		nsf.className = 'noshow';

}

function submitForm(frm)

{



<?php if ($editin == "html") { ?>

	tinyMCE.triggerSave();

<?php } ?>	



	frm.remotecontent.value = frm.remotecontent.value.replace(/[<][?]/g, '<?php echo $stphp ?>');

	frm.remotecontent.value = frm.remotecontent.value.replace(/[?][>]/g, '<?php echo $etphp ?>');

	frm.remotecontent.value = frm.remotecontent.value.replace(/[$]/g, '<?php echo $dlphp ?>');



	return true;

}

function customSave() { }

function valsel(frm)

{

	var msg = "";
	if(frm.ftp_address.value=="")
	{
		msg+="Please enter FTP address \n";
	}
	if(frm.ftp_username.value=="")
	{
		msg+="Please enter FTP user name \n";
	}
	if(frm.ftp_password.value=="")
	{
		msg+="Please enter FTP password \n";
	}
	if(frm.remote_file.value=="")
	{
		msg+="Please enter remote file path \n";
	}
	if(msg.length > 0)
	{
		alert(msg);
		return false;
	}
	else
	{
		return true;
	}
	/*if (valdsbox(1)!= false)

		if (frm.remote_file.value!="")

			return true

		else alert("Please enter remote file path");

	return false;*/

}

function browseR()

{

		url = ftpurl(2);

		if (url != false)

		{

			openwindow= window.open ("browse2.php?dir=&homebox="+url, "Browse",

				"status=0,scrollbars=1,width=450,height=500,resizable=1");

			

			openwindow.moveTo(50,50);

		}

}

function ftpurl(from)

{

	addr = document.getElementById("address").value;

	user = document.getElementById("username").value;

	pass = document.getElementById("password").value;

	which="";

	if (addr.length==0 || user.length == 0 || pass.length == 0)

	{

		alert("Please enter all FTP details");

		return false;

	}

	if(from==1) return true;

	openwindow= window.open ("browsef.php?dir=&address="+addr+"&username="+user+"&password="+pass+"&homebox="+which, "Browse","status=0,scrollbars=1,width=450,height=500,resizable=1");

	openwindow.moveTo(50,50);

	return false;



}

// function valdsbox(from)

// {

// 	var typ = document.getElementById("type").value;

// 	var sel;

// 	if (typ==1)

// 		sel = document.getElementById("packagesitelist");

// 	else if (typ==2)

// 		sel = document.getElementById("sitelist");

// 	else if (typ==3)

// 	{

// 		return ftpurl(from);

// 	}

// 	else

// 	{

// 		alert("Please select site type"+typ);

// 		return false;

// 	}

// 

// //		alert(sel.value)

// 	if ((sel!=undefined && sel != null))

// 		if (sel.value>0)

// 		{

// 			url = sel[sel.selectedIndex].text;

// 			return url;

// 		}

// 

// 	alert("Please select a site");

// 	return false;

// 

// }

function sw2(to)

{

	var ta = document.getElementById("ta");

	if (to=='t')

	ta.className = 'textarea';

	else 

	ta.className = '';

}

function editin(frm, ein)

{



<?php if ($alert)  { ?>

	if (!confirm("This s recommeded to use simple text editor view to edit this file\nopening this file in HTML editor may cause of data curruption.\nAre you sure to open this file in HTML editor?"))

	{

		return false;

	}

<?php } ?>

	frm.process.value = 'editfile';

	frm.editor.value = ein;

	frm.fromeditor.value = 'yes';

	submitForm(frm)	

	frm.submit();

}

</script>
<script type="text/javascript">
function displayftpserver()
{

if(document.getElementById("ftpserveroption").value=="new_ftp")	{	


	document.getElementById('address').value= "";
	document.getElementById('username').value= "";
	document.getElementById('password').value= "";
	
	document.getElementById('address').readOnly=false;
	document.getElementById('username').readOnly=false;
	document.getElementById('password').readOnly=false;
}
else if(document.getElementById("ftpserveroption").value=="" || document.getElementById("ftpserveroption").value!="new_ftp")	{
	
	var temp = new Array();
	str=document.getElementById("ftpserveroption").value;
	temp=str.split(' ');
	
	document.getElementById('address').readOnly=true;
	document.getElementById('username').readOnly=true;
	document.getElementById('password').readOnly=true;
	
	document.getElementById('address').value= temp[0];
	document.getElementById('username').value= temp[1];
	document.getElementById('password').value= temp[2];
}
}
</script>


<?php require_once("top.php"); ?>



<?php require_once("left.php"); ?>

<div  align="left">

<?php

$bcrumbhome = '&nbsp;<a class="general" href="../index.php">Home</a>';

$breadmanage = ' >> <a class="general" href="campaign.php">Manage Campaign</a>';

if ($process=="selrmtfil")

{

	$breadprocess = ' >> Remote file';

}

else if ($process=="editfile")

{

	$breadremote = ' >> <a class="general" href="remotefileeditor.php?process=selrmtfil">Remote file</a>';

	$breadprocess = $breadremote.' >> Editor';

}

	

echo 	$bcrumbhome.$breadmanage.$breadprocess;



?>

</div>

<br>

<div class="message">

<?php echo (isset($msg))?$msg:""; ?>

</div>

<br>



<?php 

if ($process=="editfile")

{

?>



<form name="rfm" method="post" action="remotefileeditor.php" onSubmit="return submitForm(this)">

<table class="summary">

	<tr><td align="left">&nbsp;&nbsp;<?php echo $remote_file ?></td></tr>

	<tr><td align="right">



	</td></tr>



	<tr>

	<td align="center">

		<textarea name="remotecontent" id="ta" class="textarea" ><?php

		 echo htmlspecialchars($remotecontent);

		 ?></textarea>

	</td>

	</tr>

	<tr>

	<td class="heading">

	<?php //if(isset($_POST["type"]) && $_POST["type"]==3) {	?>

	<input type="hidden" name="type" value="<?php echo $_POST["type"] ?>">	

	<input type="hidden" name="ftp_address" value="<?php echo $_POST["ftp_address"] ?>">	

	<input type="hidden" name="ftp_username" value="<?php echo $_POST["ftp_username"] ?>">		

	<input type="hidden" name="ftp_password" value="<?php echo $_POST["ftp_password"] ?>">		

	<? //} ?>

	<input type="hidden" name="remote_file" value="<?php echo $remote_file ?>">

	<input type="hidden" name="remotefile" value="<?php echo $remotefile ?>">

	<input type="hidden" name="site_id1" value="<?php echo $site_id ?>">

	<input type="hidden" name="aid" value="<?php echo $aid ?>">	

	<input type="hidden" name="process" value="updatefile">

	<input type="hidden" name="fromeditor" value="no">

	<input type="hidden" name="remotefile" value="update">	

	<input type="hidden" name="sameserver" value="<?php echo $sameserver; ?>">				

	<input type="hidden" name="editor" value="<?php echo $editin ?>">				

	<input type="submit" value="Update">

	<input type="button" value="View in <?php echo ($editin=="html") ? "text":"html"  ?> editor" onClick="editin(this.form,'<?php echo ($editin=="html") ? "text" : "html"; ?>')" >

	

	</td>

	</tr>

</table>

</form>	

<?php

}

else if($process=="selrmtfil")

{


?>

<form name="remotepath" method="post" action="remotefileeditor.php" onSubmit="return valsel(this)">

	<table class="summary">

	<tr><td colspan="2" class="heading">Remote File Editor</td></tr>


			<tr>

            <td align="right" width="40%">Existing FTP Server:&nbsp;</td>

            <td align="left">
			<?php
			$sql="select * from ".MTABLE_PREFIX."ftp_details_tb where user_id='".$_SESSION[MSESSION_PREFIX.'sessionuserid']."'";
			
			?>
			<select id="ftpserveroption" onchange="displayftpserver()">
				<option value='new_ftp'>--New FTP--</option>
				<?php 
				
				$Ftp_rs = $ms_db->getRS($sql);
				if($Ftp_rs){
				while($Ftp=$ms_db->getNextRow($Ftp_rs))
				{
				echo "<option value='".$Ftp["ftp_address"]." ".$Ftp["ftp_username"]." ".$Ftp["ftp_password"]."'>".$Ftp["ftp_address"]."(".$Ftp["ftp_username"].")"."</option>";
				}
				}
				?>			
			</select> </td>

          </tr>

          <tr>

            <td align="right" width="40%">FTP address  :&nbsp;</td>

            <td align="left">

              <input name="ftp_address" type="text" id="address" value="<?php echo $_POST["ftp_address"] ?>" size="30" maxlength="255"  onchange="hidepermconf()">			</td>

          </tr>

          <tr>

            <td align="right">FTP username  :&nbsp;</td>

            <td align="left">

              <input name="ftp_username" type="text" id="username" value="<?php echo $_POST["ftp_username"] ?>" size="30" maxlength="255"  onchange="hidepermconf()">			</td>

          </tr>

          <tr>

            <td align="right">FTP password  :&nbsp;</td>

            <td align="left">

              <input name="ftp_password" type="password" id="password" value="<?php echo $_POST["ftp_password"] ?>" size="30" maxlength="255"  onchange="hidepermconf()">			</td>

          </tr>







	<tr>

		<td class="caption"> Remote File path:</td>

		<td align="left"><input type="text" name="remote_file" id="remote_file" size="40" value="<?php echo $_POST["remote_file"] ?>"><input type="button" value="Browse" onClick="browseR()"></td>

	</tr>

	<tr>

		<td colspan="2" class="heading">

		<input type="hidden" name="remotefile" value="yes">

		<input type="hidden" name="process" value="editfile">		

		<input type="submit" value="Open file">

		</td>

	</tr>

	</table>	

</form>

<?php /*if(isset($_POST["type"]) && $_POST["type"] >0) { 

	echo '<script language="javascript"> showList(document.getElementById("type")) </script>';

}*/ } ?>

<br>

<?php require_once("right.php"); ?>

<?php require_once("bottom.php"); ?>