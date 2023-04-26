<?php
include('curl.php');

	include("phpHTMLParser.php");
$page = "http://imsllc:kalptaru@mytestserver1.info:2082/frontend/x/addon/doadddomain.html";

	$args = "?domain=testdomain.info&user=testdomain&pass=testdomain";

	echo "trying to add addon domain $newdomain with user/subdirectory/subdomain ... ";

	echo "Here<br>";

	 $result = $curl->get($page . $args);

	$s = $result;
	
	$a = $mtc = array();
	if (preg_match_all("/<font class=\"med\">(.*?)<\/font>/",$s,$mtc,PREG_SET_ORDER)) {
	foreach($mtc as $v){
	if($v[2] == "<i>no value</i>") continue;
	$a[$v[1]] = $v[2];
	echo $a[$v[1]];
	}
	}


?>


