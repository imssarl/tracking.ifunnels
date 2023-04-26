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
$xhtml->allowedTags(array("<table>","<th>","<td>","<tr>","<tbody>","<thead>"));

echo '<fieldset><legend>Before cleaning:</legend><textarea rows="20" cols="80">'.$html.'</textarea></fieldset>';
echo '<fieldset><legend>After cleaning:</legend><textarea rows="20" cols="80">'.$xhtml->GetCleanedHtml().'</textarea></fieldset>';

$end_time=getmicrotime();

echo "<hr />Clean time ".($end_time-$start_time)." s";

?>