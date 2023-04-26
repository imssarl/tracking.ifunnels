<?php

	if(isset($_GET['process']) && $_GET['process']=="single")
	{
		include("http://localhost:8080/dams_new/assignad.php?id=5&process=single&ref_url=".$_SERVER['HTTP_REFERER']."&php_self=".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']);
	}
?>