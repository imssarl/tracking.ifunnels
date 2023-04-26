<?
function ArrCombine($item, $key) {
  global $html;
  $html.=$item;
  }
function getmicrotime() {
  list($usec, $sec) = explode(" ",microtime());
  return ((float)$usec + (float)$sec);
  } 


$start_time=getmicrotime();

## GET CONTENT FROM FILE ##
$file = (file("test.htm"));
array_walk($file, 'ArrCombine');

## MAKE CLEANER INSTANCE
require ("cleanhtml.class.php");
$xhtml=new HtmlCleaner($html);

## SET ALLOWED TAGS IN CODE
$xhtml->allowedTags(array("<table>","<th>","<td>","<tr>","<tbody>","<thead>","<p>"));

## SAVE RESULT TO FILE
$xhtml->saveToFile("cleaned-test.htm");

$end_time=getmicrotime();

echo "Clean time ".($end_time-$start_time)." s";

?>