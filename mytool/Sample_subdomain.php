<script>

function parentfill(domain)
{
	//opener.document.getElementById('blog_url').value="http://"+domain;
	window.close();
}
</script>
<?php 
	@session_start();
	$root =  preg_replace('/^www\./','',trim($_POST["root"]));
	$hostname = preg_replace('/^www\./','',trim($_POST["hostname"]));	
	$username = $_POST["username"];
	$password = $_POST["password"];
	$portnum = "2082";
	/*echo '<br><pre>';
	print_r($_POST["subdomains"]);
	echo '</pre>';*/
	foreach($_POST["subdomains"] as $subdomains)
	{
	if(trim($subdomains)!="")
	$subdomain[] = preg_replace('/^www\./','',trim($subdomains));
	}
	/*echo '<br><pre>';
	print_r($subdomain);
	echo '</pre>';
	die;*/
	if($_POST["cpanelversion"]=="other")
	{
		$skin=trim($_POST["othertheme"]);
	}
	else
	{
		$skin=$_POST["cpanelversion"];
	}

	$strmode = '<?php 
	$sapi_type = php_sapi_name();
	if (substr($sapi_type, 0, 3) == "cgi") {
	  $mode="0755";
	} else {
	  $mode="0777";
	}
	echo $mode;
	?>';
	$newfilename = "checkserver_".$skin.substr(md5(rand() * time()),0,4).".php";
	

	$fp = @fopen($newfilename,"w+");
	if ($fp)
	{
		fwrite($fp,$strmode,strlen($strmode));
		fclose($fp);
	}
	else
	{
		echo "Unable to open a file"; 
	}
	if(@ftp_connect($hostname))
	{
		$cid = @ftp_connect($hostname);
		if ($cid)
		{
			$conn = @ftp_login($cid,$username,$password);
		}
		$ftp_root = '/public_html/';
		$ftpcheck=@ftp_put($cid,$ftp_root."modechk.php",$newfilename,FTP_BINARY);
		sleep(2);
		$output=@file_get_contents("http://www.".$hostname."/modechk.php");
		$chmod = substr($output,0,4);
		define('CHMODE',$chmod);
		$_SESSION["chmod"]=$chmod;
		
		sleep(5);
		
		for($i=0;$i<count($subdomain);$i++){
			$str[$i] = '<?php
			include("curl.php");
			$curl->set_user_agent("Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0");
			$curl->set_error_message("error");
			$cpanel_user ="'.$cpanel_user_get.'";
			$root = "'.$root.'";
			$hostname = "'.$hostname.'";
			$username = "'.$username.'";
			$password = "'.$password.'";
			$portnum = "2082";
			$page = "http://" . $username . ":" . $password . "@" . $hostname .":" . $portnum . "/frontend/'.$skin.'/subdomain/doadddomain.html";
			$subdomain = "'.$subdomain[$i].'";
			if($subdomain!="") 
			{
				$args = "?domain=$subdomain&rootdomain=$root";
				echo "trying to add subdomain $subdomain" . "." . "$root ... ";
				$result = $curl->get($page . $args);
				if(!strcmp($result,"error")) 
				{
					echo "<b>error</b>.<br>";
					$error = 1;
				}
				else
				{
					echo "<b>added</b>.<br>";
				}
			}
			?>';
		}
		$newfilename1=array();
		for($i=0;$i<count($subdomain);$i++){
			$newfilename1[$i] = "subdomain_".substr(md5(rand() * time()),0,4).".php";
			$fp = @fopen($newfilename1[$i],"w+");
			if ($fp)
			{
				fwrite($fp,$str[$i],strlen($str[$i]));
				fclose($fp);
			}
			else
			{
				//echo "Unable to open a file";		
			}
		}


		$cpanel_host_get = $hostname;
		$cpanel_user_get = $username;
		$cpanel_password_get = $password;
		$chmod =  $_SESSION["chmod"];
		
		if(ftp_connect($cpanel_host_get))
		{
			$conn = @ftp_connect($cpanel_host_get);
			//echo $conn.$cpanel_user_get.$cpanel_password_get;
			if(@ftp_login($conn,$cpanel_user_get,$cpanel_password_get))
			{
				$ftp_root = '/public_html/';
				
				for($i=0;$i<count($subdomain);$i++){
					$ftpcheck = ftp_put($conn,$ftp_root."create_subdomain.php",$newfilename1[$i],FTP_BINARY);
					sleep(2);
					@ftp_put($conn,$ftp_root."curl.php","curl.php",FTP_BINARY);
					//@ftp_close($conn);
					// Executing file through curl
					$ch2 = curl_init();	
					// set URL for database creation
						curl_setopt($ch2, CURLOPT_URL, "http://www.$cpanel_host_get/create_subdomain.php");
						curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
						$output=curl_exec($ch2);
						curl_close($ch2);
						//echo '<br>'.$output;					
					
					$cid = ftp_connect($cpanel_host_get);
					if(ftp_login($cid,$cpanel_user_get,$cpanel_password_get))
					{
						$ftp_root_sub = $_SESSION["chmod"]." ".$pathofserver."/".$subdomain[$i];
						@ftp_site($cid, "CHMOD ".$_SESSION["chmod"]." /public_html/".$subdomain[$i]); 
					}
					else
					{
					//echo 'unable to connect';
					}
					
				}
				@ftp_close($conn);
				
				if($ftpcheck == 1)
				{	for($i=0;$i<count($subdomain);$i++){
						echo "subdomain <B>" . $subdomain[$i] ."." . $hostname ."</B> successfully 		added<br>";
						$val =  $subdomain[$i].".".$hostname;
					}
					echo "<br>";
					echo "<input type=\"button\" name=\"close\" value=\"Close\"  onclick=\"javascript:parentfill('".$val."');\"/>";
				}
				else
				{
					echo "could not connect";
					echo "<br>";
					echo "<input type=\"button\" name=\"close\" value=\"Close\"  onclick=\"javascript:window.close();\"/>";
				}
			}
			else
			{
				echo "Your login authuntification is fail ";
				echo "<input type=\"button\" name=\"close\" value=\"Close\"  onclick=\"javascript:window.close();\"/>";
			}
			}
			else
			{
				echo 'could not connect';
				echo "<input type=\"button\" name=\"close\" value=\"Close\"  onclick=\"javascript:window.close();\"/>";
			}
		}
		else
		{
			echo "Not able to connect with this FTP details <br><br>";
			echo "<input type=\"button\" name=\"close\" value=\"Close\"  onclick=\"javascript:window.close();\"/>";
		}

?>
</body>