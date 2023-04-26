<?php


//chdir( dirname(__FILE__) );
//chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
require_once 'inc_config.php'; // set defined params - depercated!!!
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
Core_Errors::off();
// а тут запуск нужного класса - метода
error_reporting(E_ALL);
$view = new Project_Options_HtmlGenerator();
$_GET['type_view'] = 'snippetsshow';
$view->init($_GET);
/*

$url = $_SERVER['REQUEST_URI'];
$url = explode("?",$url);
if ($url[1]){
	header( "HTTP/1.1 200 OK\n" );
	header("Location: /cronjobs/getcontent.php?type_view=snippetsshow&{$url[1]}");
}

require_once( "config/config.php" );
require_once( "classes/snippets.functions.class.php" );
require_once( "classes/database.class.php" );
require_once( "classes/common.class.php" );
require_once( "classes/snippets.class.php" );
require_once( "classes/settings.class.php" );
require_once( "classes/en_decode.class.php" );
$endec = new encode_decode();
$settings = new Settings();
$common = new Common();
$ms_db = new Database();
$ms_db->openDB();
$sf = new SnippetFunctions();
$snippets = new Snippet();
$_GET["id"] = $endec->decode( $_GET["id"] );
if ( isset( $_GET["id"] ) && $_GET["id"] > 0 ) {
	$id = $_GET["id"];
	$snippet = $sf->getSnippetShowPart( $id ); 
	$httppath = "http://" . $_SERVER["HTTP_HOST"] . dirname( $_SERVER["PHP_SELF"] );
	$trackurl = $httppath . "/snippetstrack.php?pid=" . $snippet["id"] . "-0";
	$link = $snippets->changeLinksWithTrackURLs( $snippet["link"], $snippet["id"] );
	echo html_entity_decode( $link );
} else if ( isset( $_GET["partidfortest"] ) && $_GET["partidfortest"] > 0 ) {
	$id = $_GET["partidfortest"];
	$part = $snippets->getSnippetPartById( $id );
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Snippet Part # <?php echo $id ?></title>
<script language="JavaScript">
function closeme()
{
window.close();
}
</script>
<link href="stylesheets/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<center>Snippet Part # <?php echo $id ?></center>
<hr>
<table cellpadding="0" cellspacing="0" border="0" class="tablematter" align="center" height="200" width="100%">
<TR><TD>
<div align="center">
<?php
	echo html_entity_decode( $part["link"] );
	?>
</div>
</TD></TR>
</table>
<hr>
<center>
<div>
<input type="button" value="Close" onclick="closeme()">
</div>
</center>
<br>
</body>
</html>	
<?php
} else {
	echo "<div align='center'>Invalid Request</div>";
}*/
?>