


<?php

###############################################################
# cPanel Database Creator 1.2
###############################################################
# Visit http://www.zubrag.com/scripts/ for updates
############################################################### 

// cPanel username (you use to login to cPanel)
$cpanel_user = "qjmp";

// cPanel password (you use to login to cPanel)
$cpanel_password = "xtba3yhc";

// cPanel domain (example: mysite.com)
$cpanel_host = "qjmp.com";

// cPanel theme/skin, usually "x"
// Check http://www.zubrag.com/articles/determine-cpanel-skin.php
// to know it for sure
$cpanel_skin = "x";

$database = "qjmp_test"; 
$user_name ="qjmp_test"; 
$password ="test"; 

// Script will add user to database if these values are not empty
// User wil have ALL permissions
$db_username = '';
$db_userpass = '';

// Update this only if you are experienced user or if script does not work
// Path to cURL on your server. Usually /usr/bin/curl
$curl_path = "";

//////////////////////////////////////
/* Code below should not be changed */
//////////////////////////////////////

function execCommand($command) {
  global $curl_path;

  if (!empty($curl_path)) {
    return exec("$curl_path '$command'");
  }
  else {
    return file_get_contents($command);
  }
}

if(isset($database) && !empty($database)) {
  // escape db name

  // Updated bu SDEI
 // $db_name = escapeshellarg($_GET['db']);
 $db_name = mysql_escape_string($database);
	

// Updated End 


  // will return empty string on success, error message on error
  $result = execCommand("http://$cpanel_user:$cpanel_password@$cpanel_host:2082/frontend/$cpanel_skin/sql/adddb.html?db=$db_name");

  if(isset($_GET['user']) && !empty($_GET['user'])) {
    $db_username = $database;
    $db_userpass = $db_username;
  }

  if (!empty($db_username)) {
    // create user
    $result .= execCommand("http://$cpanel_user:$cpanel_password@$cpanel_host:2082/frontend/$cpanel_skin/sql/adduser.html?user={$db_username}&pass={$db_userpass}");
    // assign user to database
    $result .= execCommand("http://$cpanel_user:$cpanel_password@$cpanel_host:2082/frontend/$cpanel_skin/sql/addusertodb.html?user={$cpanel_user}_{$db_username}&db={$cpanel_user}_{$db_name}&ALL=ALL");
  }

  // output result
  echo $result;
}
else {
  echo "Usage: cpanel_create_db.php?db=databasename&user=username&pass=password";
}

?>