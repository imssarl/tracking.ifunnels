<?php
	@session_start();
chdir( '../' );
include("config/config.php");
chdir( dirname(__FILE__) );
	include('../header.php');
	require_once("../top.php");
?>
<script>
function win_close()
{ 
	window.close();
//	if (!window.opener.closed) 
//	{
		window.open("http://www.ethiclinks.com",target="other","status=yes,toolbar=yes,menubar=yes,fullscreen=yes,scrollbars=no,titlebar=yes,directories=yes,copyhistory=yes");
//	}
}
</script>
<div align="center" style="word-spacing:2px;"> 
Please note this discount code
<br><strong style="font-size:14px">8005f5b1c2b6493fa54a43ab0d48a629</strong>
<br>You can use this code with the Platinum package
<br><a  href="javascript:onclick=win_close();">Click here to go to ethiclinks.com</a>
</div>

<?php
	include ('../bottom.php');
?>