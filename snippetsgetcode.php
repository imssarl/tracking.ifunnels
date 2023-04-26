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



<TR><TD align="center">

<?php
require_once("classes/en_decode.class.php");
require_once("config/config.php");
$endec=new encode_decode();
$_GET["id"]=$endec->decode($_GET["id"]);
if(isset($_GET["id"]) && $_GET["id"]>0)

{

/*$code = '<?php

include("http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'snippetsshow.php?id='.$endec->encode($_GET["id"]).'");

?>';*/

$code = '<?php

			if(function_exists("curl_init"))

               {

                               $ch = @curl_init();

                               curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'snippetsshow.php?id='.$endec->encode($_GET["id"]).'");

                               curl_setopt($ch, CURLOPT_HEADER, 0);

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

                        @include("'.SERVER_PATH.'snippetsshow.php?id='.$endec->encode($_GET["id"]).'");

               }



?>';

?>

<TEXTAREA  rows="5" cols="80"><?php echo ($code); ?></TEXTAREA>

<div class="message">

The code has to be copied and then paste into the page where you wants it to appear. Page needs to have a php extension

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