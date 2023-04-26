<?php
	include('curl.php');

//	$curl->set_user_agent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0');
	
//	$curl->set_error_message('error');
	
//	$curl->set_referrer('$$$hostname$$$');

	$username = "$$$cpanelusername$$$";

	$password = "$$$cpanel_password$$$";

	$hostname = "$$$hostname$$$";

	$portnum = "2082";

	$newdomain = "$$$newdomain$$$";
	$newusername = "$$$newusername$$$";
	$newpassword = "$$$newpassword$$$";
	$newdomain2 = urlencode($newdomain);
	$newusername2 = urlencode($newusername);
	$newpassword2 = urlencode($newpassword);



	$page = "http://" . $username . ":" . $password . "@" . $hostname .

	":" . $portnum . "/frontend/x3/addon/doadddomain.html";

	$args = "?domain=$newdomain2&user=$newusername2&pass=$newpassword2";

	//echo "trying to add addon domain $newdomain with user/subdirectory/subdomain $newusername ... ";

	$result = $curl->get($page . $args);

	/*if(!strcmp($result,"error")) {

		echo "<b>error</b>.<br>";
		$error = 1;
	}
	else
	{	
		echo "<b>added</b>.<br>";
	}*/
?>

