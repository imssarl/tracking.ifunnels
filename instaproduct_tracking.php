<?php 
$host = 'localhost';
$username = 'instaproduct';
$password = 'zu0JBiUZU6';
$database_name = 'track_instaproduct';
	
mysql_connect($host, $username, $password);
mysql_select_db($database_name);

if (isset($_GET['increment_tracking_value'])){
	$query = "SELECT count FROM ebooks_tracking LIMIT 1";
	$row = mysql_fetch_array(mysql_query($query));
	if (!$row){
		mysql_query("INSERT INTO `ebooks_tracking` (`id`, `count`) VALUES (NULL, '0');");
	}
	$query = "UPDATE ebooks_tracking SET count=count+1 WHERE id = 1";
	mysql_query($query);
} 
?>