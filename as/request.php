<?php
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/article.class.php");

$asm_db = new Database();
$asm_db->openDB();

$article_obj = new Article();

if(isset($_POST['process']))
{
	$process=$_POST['process'];
}
elseif(isset($_GET['process']))
{
	$process=$_GET['process'];
}

$article_obj = new Article();

$sql = "update `".TABLE_PREFIX."submission` set flag='F' where profile_id='".$_GET['id']."'";

$id = $asm_db->modify($sql);
if($id)
{
	echo "Profile has been reset successfully";
}

?>