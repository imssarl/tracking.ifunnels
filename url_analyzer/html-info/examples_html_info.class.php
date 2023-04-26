<?php

/*include("html_info.class.php");
$html_code="<html><!-- commentdasddaddad --><head><title>title contents from ashish </title>this is a head</head><body>This is the<b> b1 </b> <b> b2 </B> <i> i 1 </i>";
$info=new html_info($html_code);

//echo $info->get_title();

//$strings=$info->get_strings_headed(1,3);
$tags[0]['open']="<b>";
$tags[0]['close']="</b>";
$tags[1]['open']="<i>";
$tags[1]['close']="</i>";

$string=$info->get_strings_in_tags($tags,$html_code);
foreach($string as $st)
{
echo $st;
}

for($i=0;$i<count($strings);$i++)
{
	echo $strings[$i]."<br>\n";
}

*/
?>
<script language="javascript" type="text/javascript">


</script>
<?php
function html_out_keep_tags ($string) {
  $newstring = '';
  $pattern = '/(<\/?(?:a .*|h1|h2|b|i|head)>)/ims';
  $newarray = preg_split( $pattern, $string, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
  foreach ($newarray as $element) {
   if (!preg_match($pattern, $element))
     $element = htmlspecialchars ( html_entity_decode($element, ENT_QUOTES), ENT_QUOTES);
   $newstring .= $element;
  }
  return $newstring;
}
$html_code="<html><!-- commentdasddaddad --><head><title>title contents from ashish </title>this is a head   <script>hi it is ashish</script></head><body>This is thefffffffffffff<b> b1 </b> <b> b2 </B> <i> i 1 </i>";
$text=html_out_keep_tags($html_code);
echo $text;

$html_array = preg_split ('/(<(?:[^<>]+(?:"[^"]*"|\'[^\']*\')?)+>)/', trim ($html_code), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
	foreach($html_array as $rs)
	{
	 echo $rs;
	}
?>
