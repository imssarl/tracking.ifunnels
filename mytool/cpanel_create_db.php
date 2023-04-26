<?php
ob_start();
$dir=$_SERVER['DOCUMENT_ROOT']."/cnm/mytool/sites/";
$filename="cpanel_create_db.php";
@chmod($_SERVER['DOCUMENT_ROOT']."/cnm/mytool/sites/".$filename,"0777");
if (file_exists($dir.'/'.$filename)) {
  @unlink($dir.'/'.$filename);
} 
@chmod($_SERVER['DOCUMENT_ROOT']."/cnm/mytool/sites/".$filename,"0777");
$handle = fopen($dir.'/'.$filename, 'x+');
@chmod($_SERVER['DOCUMENT_ROOT']."/cnm/mytool/sites/".$filename,"0777");
$cpanel_user = "imsllc";
$cpanel_password = "kalptaru";
$cpanel_host = "mytestserver1.info";
$cpanel_skin = "x";
$db_username = '';
$db_userpass = '';

// Update this only if you are experienced user or if script does not work
// Path to cURL on your server. Usually /usr/bin/curl
$curl_path = "";

function execCommand($command) {
  global $curl_path;

  if (!empty($curl_path)) {
    return exec("$curl_path '$command'");
  }
  else {
    return file_get_contents($command);
  }
}

if(isset($_GET['db']) && !empty($_GET['db'])) {
  // escape db name
  $db_name = mysql_escape_string($_GET['db']);

  // will return empty string on success, error message on error
  $result = execCommand("http://$cpanel_user:$cpanel_password@$cpanel_host:2082/frontend/$cpanel_skin/sql/adddb.html?db=$db_name");

  if(isset($_GET['user']) && !empty($_GET['user'])) {
    $db_username = $_GET['user'];
    $db_userpass = $_GET['pass'];
  }

  if (!empty($db_username)) {
    // create user
    $result .= execCommand("http://$cpanel_user:$cpanel_password@$cpanel_host:2082/frontend/$cpanel_skin/sql/adduser.html?user={$db_username}&pass={$db_userpass}");
    // assign user to database
 $result .= execCommand("http://$cpanel_user:$cpanel_password@$cpanel_host:2082/frontend/$cpanel_skin/sql/addusertodb.html?user={$cpanel_user}_{$db_username}&db={$cpanel_user}_{$db_name}&ALL=ALL");
  }

}


$out1 = ob_get_contents();

echo $out1;
@chmod($_SERVER['DOCUMENT_ROOT']."/cnm/mytool/sites/".$filename,"0777");
fwrite($handle, $out1);
@chmod($_SERVER['DOCUMENT_ROOT']."/cnm/mytool/sites/".$filename,"0777");
fclose($handle);


ob_end_clean();
@chmod($_SERVER['DOCUMENT_ROOT']."/cnm/mytool/sites/".$filename,"0777");

?>