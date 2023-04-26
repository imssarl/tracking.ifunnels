<?php require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/profile.class.php");

$asm_db = new Database();
$asm_db->openDB();
$profile = new Profile();
if($_REQUEST['id'])
{
	$profile->duplicate_profile($_REQUEST['id']);
}
   header("Location:manage_profile.php?msg=Duplicate profile has been created successfully!");
?>
