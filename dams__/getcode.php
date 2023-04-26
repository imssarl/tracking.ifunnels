<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
</head>
<script language="JavaScript">
function closeme()
{
window.close();
}
</script>
<link href="stylesheets/style1.css" rel="stylesheet" type="text/css">
<body>
<br><br>
<table width="95%" cellpadding="0" cellspacing="0" border="0" class="summary2" align="center">
<tr valign="center"><TD align="center" class="heading">Include Code</TD></tr>

	<!--include("http://kil21:8080/dams/showcode.php?id=1&ref_url=".$_SERVER['HTTP_REFERER']."&php_self=".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']);-->

<TR><TD align="center">
<?php
require_once("classes/en_decode.class.php");
require_once("config/config.php");	
$endec=new encode_decode();
$_GET["id"]=$endec->decode($_GET["id"]);
if(isset($_GET["id"]) && $_GET["id"]>0)
{
/*$code = '<?php include("http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/showcode.php?id='.$endec->encode($_GET["id"]).'&process='.$_GET['process'].'&ref_url=".$_SERVER[\'HTTP_REFERER\']."&php_self=".$_SERVER[\'SERVER_NAME\'].$_SERVER[\'PHP_SELF\']);
?>';*/

$code = '<?php

			if(function_exists("curl_init"))

               {

                               $ch = @curl_init();

                               curl_setopt($ch, CURLOPT_URL,"'.BASEPATH.'showcode.php?id='.$endec->encode($_GET["id"]).'&process='.$_GET['process'].'&ref_url=".$_SERVER[\'HTTP_REFERER\']."&php_self=".$_SERVER[\'SERVER_NAME\'].$_SERVER[\'PHP_SELF\']);

                               /*curl_setopt($ch, CURLOPT_HEADER, 0);*/

                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                               $resp = @curl_exec($ch);

                               $err = curl_errno($ch);



                       if($err === false || $resp == "")

                       {

                               $newsstr = "";

                       } else

                       {

                               if (function_exists("curl_getinfo"))

                               {

                                   $info = curl_getinfo($ch);

                                       if ($info["http_code"]!=200)

                                               $resp="";

                               }

                               $newsstr = $resp;

                       }

                       @curl_close ($ch);

                       echo $newsstr;

               }

               else

               {

                        @include("'.BASEPATH.'showcode.php?id='.$endec->encode($_GET["id"]).'&process='.$_GET['process'].'&ref_url=".$_SERVER[\'HTTP_REFERER\']."&php_self=".$_SERVER[\'SERVER_NAME\'].$_SERVER[\'PHP_SELF\']);

               }



?>';

$img_tag ='<?php
 echo("<img src=http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/actiontrack.php?id='.$endec->encode($_GET["id"]).' height=1 width=1>");
 ?>';


?>

<TEXTAREA  rows="5" cols="80"><?php echo ($code); ?></TEXTAREA>
<div class="message">
The code has to be copied and then paste inside the body tag of the page where you wants Ad to appear. Page needs to have a php extension
</div>
<TEXTAREA  rows="5" cols="80"><?php echo ($img_tag); ?></TEXTAREA>
<div class="message">
The code has to be copied and then paste into the thanks page of your site. Page needs to have a php extension
</div>
<?php
}
else
{
	echo "invalid request";
}
?>
</TD></head>
<tr><TD  class="heading"><input type="button" value="Close" onclick="closeme()" ></TD></tr>
</table>
<br>
</body>
</html>