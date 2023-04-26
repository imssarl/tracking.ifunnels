<?php
	//PHP code will come here
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/article.class.php");

$asm_db = new Database();
$asm_db->openDB();
$article = new Article();
?>
<html>
<head>
</head>
<link href="<?php echo SERVER_PATH; ?>stylesheets/amarticlestyle.css" rel="stylesheet" type="text/css">
<body class="ambackground">
<?php
if(isset($_GET['id']))
{
	$article_data = $article->getArticleById($_GET['id']);
?>
<table class="amtable">
	<TR>
		<TD class="amtitle"><?php echo $article_data['title'];?></TD>
	</TR>
	<TR>
		<TD class="amunderline"></TD>
	</TR>
	<tr><TD><br></TD></tr>
	<TR>
		<TD class="amsummary"><?php echo $article_data['summary'];?></TD>
	</TR>
	<TR>
		<TD class="amunderline"></TD>
	</TR>
	<tr><TD><br></TD></tr>
	<TR>
		<TD class="amarticle"><?php echo $article_data['body'];?></TD>
	</TR>
</table>
<?php
}
?>

</body>

</html>