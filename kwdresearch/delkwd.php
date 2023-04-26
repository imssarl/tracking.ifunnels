<?php
chdir( '../' );
include("config/config.php");
chdir( dirname(__FILE__) );
require_once("../classes/database.class.php");
$database = new Database();
$database->openDB();
$id=$_POST['id'];
$sql="DELETE from `".TABLE_PREFIX."kwd_savedkwds` where kwdid='".$id."'";
$result=$database->modify($sql);
echo "<b>The keyword has been deleted successfully</b>";
?>
