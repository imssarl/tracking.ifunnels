<?php 
	$ch1 = curl_init();	
	// set URL for database creation
//	$cpanel_host1 = $cpanel_host 
//echo "http://www.$cpanel_host_get/databasecpanelcreation.php";

	curl_setopt($ch1, CURLOPT_URL, "http://www.$cpanel_host_get/databasecpanelcreation.php");
	//curl_setopt($ch1, CURLOPT_RETURNTRANSFER, FALSE);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	$output=curl_exec($ch1);
	curl_close($ch1);
	echo $output;
	
	
?>