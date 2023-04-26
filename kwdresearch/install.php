<?php
error_reporting(0);
ini_set('memory_limit','300M');
set_time_limit(0);


include("config.php");

$link = mysql_connect($host,$username,$password)  or die("Upload Error: ".mysql_error());
print "Successfully connected.\n";
mysql_select_db($database, $link) or die("Select DB Error: ".mysql_error());


$handle = fopen("keywords.txt", "rb");
$contents = "";

while (!feof($handle)) {
  $contents = fgets($handle, 4096);

list($keyword, $keysearches, $googleresults, $kei, $rs ) = split("\t", $contents);

$xyz++;

//echo "$xyz:  " . "\t" . $keyword . "\t" . $keysearches . "\t" . $googleresults . "\t" . $kei . "\t" . $rs . "\r\n";

mysql_query("INSERT keywords (keyword, searches, competition, kei, rs)  VALUES ('$keyword', '$keysearches', '$googleresults', '$kei', '$rs' )");

//print "Successfully connected.\n";

}

fclose($handle);


mysql_close($link);
print "Successfully Updated Data.\n";

?>