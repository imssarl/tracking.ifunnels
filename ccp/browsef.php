<?php
session_start();
function decodePassword($password)
{
$find=array("%2B", "%2C" ,"%60" ,"%3D" ,"%40" ,"%23" ,"%24" ,"%25" ,"%5E" ,"%26" ,"%7B" ,"%7D" ,"%7C","%3A","%3B" ,"%3F","%22","%2F","%5B","%5D");
// ,"%5C" ,"%2F" ,"%22" ,"%3A","%3B" ,"%3F");
$replace=array("+", "," ,"`" ,"=" ,"@" ,"#" ,"$" ,"%" ,"^" ,"&" ,"{" ,"}" ,"|",":" ,";" ,"?",'"','/','[',']');
//,"\" ,'/' ,""" ,":" ,";" ,"?");
$resultPassword=str_replace($find,$replace,$password);
return $resultPassword;
}
$_GET["password"]=decodePassword($_GET["password"]);
if (isset($_GET["address"]) && $_GET["address"] != "")
{

	$_SESSION["add"] = $_GET["address"];
	$_SESSION["unm"] = $_GET["username"];
	$_SESSION["pas"] = $_GET["password"];
	if (isset($_GET["onlyf"]) && $_GET["onlyf"]=="yes")
		$_SESSION["of"] = "yes";
	else
		$_SESSION["of"] = "no";

	if (isset($_GET["oldv"]) && $_GET["oldv"]=="yes")
		$_SESSION["ov"] = "yes";
	else
		$_SESSION["ov"] = "no";
		
	$_SESSION["rootpath"] = "/".trim($_GET["username"]); // old version of browse feature			
		
}


	if(isset($_GET["dir"]) && $_GET["dir"] != "")
	{
		$base = stripcslashes($_GET["dir"]);
		if (substr($base,strlen($base)-1,1)!="/") $base .= "/";	
	}
	else
	{
		$base = "/";
		$first_time = true;
	}


$cid = @ftp_connect($_SESSION["add"]);
if ($cid)
{
	$login_result = @ftp_login($cid, $_SESSION["unm"], $_SESSION["pas"]);
	@ftp_pasv( $cid, true );
	if (!$login_result)
		die("Please check FTP user details");
}
else
{
	die("Please check FTP address");
}
$buff = ftp_rawlist($cid,$base);
//print_r($buff);
@ftp_close($cid);
//echo $base;
//print_r($buff);

// $buff = file("buff.txt");
foreach ($buff as $file)
{

       if(ereg("([-dl][rwxst-]+).* ([0-9]).* ([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9])[ ]+(([0-9]{2}:[0-9]{2})|[0-9]{4}) (.+)", $file, $regs)) {
           $type = (int) strpos("-dl", $regs[1]{0});
           $tmp_array['line'] = $regs[0];
           $tmp_array['isdir'] = $type;
           $tmp_array['rights'] = $regs[1];
           $tmp_array['number'] = $regs[2];
           $tmp_array['user'] = $regs[3];
           $tmp_array['group'] = $regs[4];
           $tmp_array['size'] = $regs[5];
           $tmp_array['date'] = date("m-d",strtotime($regs[6]));
           $tmp_array['time'] = $regs[7];
           $tmp_array['name'] = $regs[9];
       }
    $dir_list[]=$tmp_array;
}


?>


<html>
<head>
<link href="stylesheets/style.css" rel="stylesheet" type="text/css">
</head>
<script>
function setFolder(folder)
{
<?php if ($_SESSION["ov"]=="yes") { ?>
window.opener.document.getElementById('ftp_homepage'+'<?php echo trim($_GET['homebox']) ?>').value='<?php echo $_SESSION["rootpath"] ?>'+folder;
<?php } else  { ?>
window.opener.document.getElementById('remote_file'+'<?php echo trim($_GET['homebox']) ?>').value=folder;
<?php } ?>
window.close();
}
</script>
<body>

<?php

	$first_time = false;
	echo "<br>";		
	if($first_time === true)
	{
		echo "<img align='bottom' src='images/folder.png' title='Click here to select the folder' onClick='setFolder(\"".$base."\")'><a class = 'general' href='browsef.php?dir=".($base)."&homebox=".$_GET['homebox']."'>".$base."</a><br>";
	}
	else
	{
		//echo urldecode($_GET["dir"]);
	echo "".$base."</div><br><hr/><br>";
		echo "<a class = 'general' href='javascript:history.go(-1);' title='Click here to go back'><img border = '0' align='bottom' src='images/folder.png' >&nbsp;..</a><br>";

$dirlist2 = $dir_list;

	if (count($dirlist2)>0)
	{
		foreach($dirlist2 as $dl)
		{
			if($dl['name'] == "." || $dl['name'] == "..") continue;
			if ($_SESSION["of"]=="yes" && $dl['isdir']!=1)	continue;
			if ($dl['isdir']==1)
			{
				$image = 'folder'; 
				$fltitle = "'Click here to view subfolder(s)'";
				if ($_SESSION["of"]!="yes")	$ltitle = $fltitle;
				else $ltitle = "'Click here to select' onClick='setFolder(\"".$base.$dl['name'].'/'."\")'";
//				if ($_SESSION["of"]=="yes") $ltitle = $ltitle+"onClick='setFolder(\"".$base.$dl['name']."\")'";
				$inlinkst = "<a href='browsef.php?dir=".($base.$dl['name'])."&homebox=".$_GET['homebox']."' class = 'general' title=$fltitle>";
				$inlinked = "</a>";
			}
			else 
			{
				$image = 'file';
				$ltitle = "'Click here to select' onClick='setFolder(\"".$base.$dl['name']."\")'";
				$inlinked = "";
				$inlinkst = "";
			}
			if ($_SESSION["of"]!="yes")
			echo $inlinkst;
			echo "<img  border='0'align='bottom' src='images/$image.png' title=$ltitle";
			echo ">";
			if ($_SESSION["of"]=="yes")
			echo $inlinkst;
			echo "&nbsp;".$dl['name']."$inlinked<br>";			
		
		}
	}
	}


?>
</body>
</html>