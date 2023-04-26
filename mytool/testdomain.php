
<?php

	include('curl.php');


//	$curl->set_user_agent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0');

	//$curl->set_error_message('error');

	$root = "$$$root$$$";
	
	//$curl->set_referrer($root);

	$hostname = "$$$cpaneldomain$$$";
	
	$username = "$$$cpanelusername$$$";

	$password = "$$$cpanel_password$$$";

	$root = "$$$root$$$";

	$portnum = "2082";


	$page = "http://" . $username . ":" . $password . "@" . $hostname .


	":" . $portnum . "/frontend/x/subdomain/doadddomain.html";


	$subdomain = "$$$subdomain$$$";


	//$subdomain=strtok($subdomains,"\n ");



	
	if($subdomain!='') {

		$args = "?domain=$subdomain&rootdomain=$root";

		//echo "trying to add subdomain $subdomain" . "." . "$root ... ";

		$result = $curl->get($page . $args);
			
	}



?>
