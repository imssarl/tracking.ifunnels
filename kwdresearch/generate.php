<?php
error_reporting(0);
include("config.php");
$keywords=$_POST['keywords'];
$DATA=$_POST['DATA'];
if (empty($keywords)) { exit; };
header("Content-Type: text/plain");
$x= count($keywords) - 1;
$start = 0;
$report = $x;
$browser = $_SERVER['HTTP_USER_AGENT'];
if ($DATA == "Basic") {
while ($start <= $report) {
  //echo $keywords[$start] . "\r\n";
   $start++;   
   $content = $content . $keywords[$start] . "\r\n";
}
header('Content-Length: '.strlen($content));
//header("Content-Type: application/download");
header('Content-Disposition: inline;filename=keywords.txt'); 
echo $content;
exit;
/* header("Content-Type: application/force-download\n");
 header("Cache-Control: cache, must-revalidate");   
 header("Pragma: public");
 header("Content-Disposition: attachment; filename=keywords.txt");
 print $content;
 exit;*/
}


$link = mysql_connect($host,$username,$password);
mysql_select_db($database , $link);


$content = "Keyword" . "\t" . "Searches" . "\t" . "Daily Searches" . "\t" . "Competition" . "\t" . "RS" . "\t" . "KEI" . "\r\n";


while ($start <= $report) {


$result = mysql_query("SELECT * FROM keywords WHERE keyword='$keywords[$start]'");
$myrow = mysql_fetch_row($result);
$daily=$myrow[1] / 30;
$daily=number_format($daily);
if ($myrow[3] > 9) { $kei = number_format($myrow[3]); } else { $kei = $myrow[3]; }

 //  echo $myrow[0] . "\t" . number_format($myrow[1]) . "\t" . $daily . "\t" . number_format($myrow[2]) . "\t" . number_format($myrow[4]) . "\t" . $kei . "\r\n";

   $start++;   
   $content = $content . $myrow[0] . "\t" . number_format($myrow[1]) . "\t" . $daily . "\t" . number_format($myrow[2]) . "\t" . number_format($myrow[4]) . "\t" . $kei . "\r\n";

}
header('Content-Length: '.strlen($content));
//header("Content-Type: application/download");
header('Content-Disposition: inline;filename=keywords.txt'); 
echo $content;
exit;
 /*header("Content-Type: application/force-download\n");
 header("Cache-Control: cache, must-revalidate");   
 header("Pragma: public");
 header("Content-Disposition: attachment; filename=keywords.txt");
 print $content;
 exit;*/
?>