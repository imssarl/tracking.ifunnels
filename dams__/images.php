<?php
// ob_start();
// print_r($_GET);
// print_r($_SERVER);
// $str = ob_get_contents();
// ob_end_clean(); 
//     	<image>".$str."</image>
include_once('config/config.php');
define(BASEPATH_NEW, "http://{$_SERVER['SERVER_NAME']}/dams/");	
$vars = explode('::::', $_GET["imageid"]);
$images = $vars[0];
$link1 = $vars[1];
$link2 = $vars[2];
$link3 = $vars[3];
$link = $link1."&ref_url=".$link2."&php_self=".$link3;
$var = BASEPATH_NEW."/flipped_images/".$images;

echo "<?xml version='1.0' encoding='utf-8' standalone='yes'?>
<images>
    <pic>
    <image>".$var."</image>
        <link>".$link."</link>
    </pic>
</images>";
?>

