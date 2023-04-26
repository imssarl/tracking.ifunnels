<?php
	//PHP code will come here
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/article.class.php");
require_once("classes/profile.class.php");

$asm_db = new Database();
$asm_db->openDB();
$article = new Article();
$profile = new Profile();

$sql = "select log from `".TABLE_PREFIX."submission` where id=".$_GET['id'];
$error_log = $asm_db->getDataSingleRow($sql);

?>
<html>
<head>
</head>
<link href="<?php echo SERVER_PATH; ?>stylesheets/amarticlestyle.css" rel="stylesheet" type="text/css">
<body class="ambackground">

</body>
<table class="amtable">
<TR>
		<TD class="amunderline"></TD>
	</TR>
	<tr><TD><br></TD></tr>
	<TR>
		<TD class="amerror"><?php echo html_entity_decode($error_log['log']);?></TD>
	</TR>
	<tr><TD><br></TD></tr>
	<TR>
		<TD class="amunderline"></TD>
	</TR>
	<tr>
		<td align="center" class="heading"><input type="button" value="Close" onClick="window.close();"></td>
	</tr>
</table>
</html>