<?php
chdir( '../' );
include("config/config.php");
chdir( dirname(__FILE__) );
require_once("../classes/database.class.php");
$database = new Database();
$database->openDB();
$id=$_POST['id'];
$text=$_POST['text'];
$sql="UPDATE `".TABLE_PREFIX."kwd_savedkwds` SET `keyword`='".$text."' where kwdid='".$id."'";
$result=$database->modify($sql);
echo "<b>The keyword has been updated successfully</b>";
?>
