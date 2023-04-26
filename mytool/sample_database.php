<script>
function parentfill(hname,dname,duser,dpass) {
	window.close();
}
</script>
<?php
if ( $_POST["cpanelversion"] == "other" ) {
	$skin = trim( $_POST["othertheme"] );
} else {
	$skin = $_POST["cpanelversion"];
} 
$cpanel_host_get = preg_replace( '/^www\./', '', trim( $cpanel_host_get ) );

$str = '<?php 
$cpanel_user ="' . $cpanel_user_get . '";
$cpanel_password = "' . $cpanel_password_get . '";
$cpanel_host ="' . $cpanel_host_get . '";
$cpanel_skin = "' . trim( $skin ) . '";
$database = "' . $db_name_get . '";
$user_name = "' . $db_username_get . '";
$password = "' . $db_userpass_get . '";
$password=urldecode($password);
$db_username = $user_name;
$db_userpass = $password;
$db_name = mysql_escape_string($database);

if ( $cpanel_skin=="x3" ) {
	$dburl="http://$cpanel_user:$cpanel_password@$cpanel_host:2082/frontend/$cpanel_skin/sql/addb.html?db=$db_name";
	$assignust_to_db_url="http://$cpanel_user:$cpanel_password@$cpanel_host:2082/frontend/$cpanel_skin/sql/addusertodb.html?user={$cpanel_user}_{$db_username}&db={$cpanel_user}_{$db_name}&update=&ALL=ALL";
} else {
	$dburl="http://$cpanel_user:$cpanel_password@$cpanel_host:2082/frontend/$cpanel_skin/sql/adddb.html?db=$db_name";
	$assignust_to_db_url="http://$cpanel_user:$cpanel_password@$cpanel_host:2082/frontend/$cpanel_skin/sql/addusertodb.html?user={$cpanel_user}_{$db_username}&db={$cpanel_user}_{$db_name}&ALL=ALL";
}
$createusrurl="http://$cpanel_user:$cpanel_password@$cpanel_host:2082/frontend/$cpanel_skin/sql/adduser.html?user={$db_username}&pass={$db_userpass}";

$db_username = "";
$db_userpass = "";

// Update this only if you are experienced user or if script does not work
// Path to cURL on your server. Usually /usr/bin/curl
$curl_path = "";

function execCommand($command) {
	global $curl_path;
	return file_get_contents($command);
}

if(isset($database) && !empty($database)) {
	$db_name = mysql_escape_string($database);
	// will return empty string on success, error message on error
	$result = execCommand($dburl);
	if(!strcmp($result,"error"))  {
		echo "Failed creating the database. Check the data you provided and try again.<br>";
		$error = 1;
	} else {
		echo "<b>Database created.</b><br>";
	}
	if(isset($user_name) && !empty($user_name)) {
		$db_username = $user_name;
		$db_userpass = $password;
	}
	if (!empty($db_username)) {
		// create user
		$result .= execCommand($createusrurl);
		// assign user to database
		$result .= execCommand($assignust_to_db_url);
	}
	if(!strcmp($result,"error")) {
		echo "Failed creating the username.<br>";
		$error = 1;
	} else {
		echo "<b>Username created.</b><br>";
	}
	$newusername3 = $cpanel_user . "_" . $user_name;
	$newdatabase3 = $cpanel_user . "_" . $database;
	if(!strcmp($result,"error"))  {
		echo "Failed adding username privileges.<br>";
		$error = 1;
	} else {
		echo "<b>Username privileges added.</b><br>username: $newusername3, database: $newdatabase3";	
	}
} else {
  echo "Usage: cpanel_create_db.php?db=databasename&user=username&pass=password";
}';
$str .='?>';



$newfilename = "checkserverdb" . $skin . substr( md5( rand() * time() ), 0, 4 ) . ".php"; 
// $newfilename = "database_cpanel_dynamic.php";
// echo  $newfilename;
@chmod( $newfilename, 0755 );
$fp = @fopen( $newfilename, "w+" );
if ( $fp ) {
	fwrite( $fp, $str, strlen( $str ) );
	fclose( $fp );
} else {
	echo "Unable to open a file";
} 



$cpanel_password_get = urldecode( $cpanel_password_get );
if ( $conn = @ftp_connect( $cpanel_host_get ) ) {
	if ( @ftp_login( $conn, $cpanel_user_get, $cpanel_password_get ) ) {
		$ftp_root = '/public_html/';
		$ftpcheck = @ftp_put( $conn, $ftp_root . "databasecpanelcreation.php", $newfilename, FTP_BINARY );
		ftp_close( $conn );
		$ch1 = curl_init();
		curl_setopt( $ch1, CURLOPT_URL, "http://www.$cpanel_host_get/databasecpanelcreation.php" );
		curl_setopt( $ch1, CURLOPT_RETURNTRANSFER, 1 );
		$output = curl_exec( $ch1 );
		curl_close( $ch1 ); 
		if ( $ftpcheck == 1 ) {
			echo "Database Created Successfully";
			echo "<br>";
			$newusername3 = $cpanel_user_get . "_" . $db_username_get ;
			$newdatabase3 = $cpanel_user_get . "_" . $db_name_get ;
			$db_userpass_get = urldecode( $db_userpass_get );
			echo "<b>Username privileges added.</b><br>username: $newusername3, database: $newdatabase3";
			echo "<input type=\"button\" name=\"close\" value=\"Close\"  onclick=\"javascript:parentfill('','" . $newdatabase3 . "','" . $newusername3 . "','" . $db_userpass_get . "');\"/>";
		} else {
			echo "Could not Connect";
			echo "<br>";
			echo "<input type=\"button\" name=\"close\" value=\"Close\"  onclick=\"javascript:window.close();\"/>";
		} 
	} else {
		echo 'could not connect';
		echo "<input type=\"button\" name=\"close\" value=\"Close\"  onclick=\"javascript:window.close();\"/>";
	} 
} else {
	echo "Your login authentication is fail ";
	echo "<input type=\"button\" name=\"close\" value=\"Close\"  onclick=\"javascript:window.close();\"/>";
}
?>