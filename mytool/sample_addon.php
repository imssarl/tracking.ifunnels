<script>
function callfunction(gourl)
{
	document.location.href=(gourl);
}
</script>
<?php 
if($_POST[''])
print_r($_POST);

if($_POST["cpanelversion"]=="x3")
	{
	
		$file = @fopen("testaddon_newcpanel.php","r");

	
	}
	else
	{
		
		$file = @fopen("testaddon.php","r");

	}

if($file==FALSE)
{
	return false;
}
else
{ 
	$databasefile = "";
		while(!@feof($file))
		{
			$str = @fgets($file);
			$databasefile .= $str;
		}
		
		@fclose($file);


}
	$databasefile = str_replace("$$$"."hostname$$$",$hostname,$databasefile);
	$databasefile = str_replace("$$$"."cpanelusername$$$",$username,$databasefile);
	$databasefile = str_replace("$$$"."cpanel_password$$$",$passsword,$databasefile);
	$databasefile = str_replace("$$$"."newdomain$$$",$newdomain,$databasefile);
	$databasefile = str_replace("$$$"."newusername$$$",$newusername,$databasefile);
	$databasefile = str_replace("$$$"."newpassword$$$",$newpassword,$databasefile);
	
	$newfilename = "database_".substr(md5(rand() * time()),0,4).".php";
	//$newfilename = "database_cpanel_dynamic.php";
	

		@chmod($newfilename,0755);

		$fp = @fopen($newfilename,"x+");



		if ($fp)
		{
			
			fputs($fp,$databasefile,strlen($databasefile));

			fclose($fp);

		}
		else
		{
			echo "Unable to open a file"; 
		}



 $cpanel_host_get = $hostname;
 $cpanel_user_get = $username;
 $cpanel_password_get = $password;

//echo $cpanel_host_get.":".$cpanel_user_get.":".$cpanel_password_get;

$conn = @ftp_connect($cpanel_host_get);
@ftp_login($conn,$cpanel_user_get,$cpanel_password_get);

$ftp_root = '/public_html/';

$ftpcheck =  @ftp_put($conn,$ftp_root."create_addons.php",$newfilename,FTP_BINARY);
 @ftp_put($conn,$ftp_root."curl.php","curl.php",FTP_BINARY);

 @ftp_close($conn);

// Executing file through curl

	$ch2 = curl_init();	
	
	curl_setopt($ch2, CURLOPT_URL, "http://www.$cpanel_host_get/create_addons.php");
	
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	$output=curl_exec($ch2);
	curl_close($ch2); 

	

	$s = $output;
	//print_r($output);
	//$pos=strpos($output,"FAILED");
	//$pos2=strpos($output,"Failed");
	$pos=stripos($output,"FAILED");
	$pos2=stripos($output,"SUCCESS");
	//echo "pos:".$pos."<br/>pos2:".$pos2."<br/>";

	if($pos > 0)
	{
		echo "Oops! it seems you do not have required permissions to set up an Add On Domain";
	}
	elseif($pos2 > 0)
	{
		echo "Addon Domain Additions <br/><br/><p>Bind reloading on host using rndc zone: [".$hostname."] The subdomain, ".$newdomain." has been added.<br/></p>		<p>".$newdomain." has been setup. It can be accessed via the subdomain <b>" .$newusername ."." .$hostname . "</b> FTP access has been granted with the user name <br> Username <b>". $newusername . "@"  ."$hostname</b></p>";
	}
	else{
		echo "OOPs! Unknown Error Occured";
	}
	
	echo "<input type=\"button\" name=\"back\" value=\"Back\"  onclick=\"javascript:callfunction('addon.php');\"/>";
	/*$a = $mtc = array();
	
	if (preg_match_all("/<font class=\"med\">(.*?)<\/font>/",$s,$mtc,PREG_SET_ORDER)) {
	foreach($mtc as $v){
	if($v[2] == "<i>no value</i>") continue;
	$a[$v[1]] = $v[2];
	echo $a[$v[1]];
	}
	}


	if($ftpcheck == 1)
	{
	
	
	
	echo "<b>" . $newdomain ."</b> has been setup";
	echo "<br> It can be accessed via the subdomain   <b>" .$newusername ."." .$hostname . "</b> FTP access has been granted with the user name";
	echo "<br> Username <b>". $newusername . "@"  ."$hostname</b>";
	echo " and the Password <b>". $newpassword ."</b>";
	echo "<br>";
	echo "<br>";
	
	echo "<input type=\"button\" name=\"back\" value=\"Back\"  onclick=\"javascript:callfunction('addon.php');\"/>";
	}
	else
	{
		echo "Could not Connect";
		echo "<br>";
		echo "<br>";
echo "<input type=\"button\" name=\"back\" value=\"Back\"  onclick=\"javascript:callfunction('addon.php');\"/>";
	}*/
	

?>