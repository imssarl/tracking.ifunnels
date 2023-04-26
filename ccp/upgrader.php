<?php
require_once("config/config.php");
require_once("classes/database.class.php");

$ms_db = new Database();
$ms_db->openDB();

$sql="UPDATE `".TABLE_PREFIX."affiliatenetwork` SET `affiliate_link` = 'subid' WHERE `affiliate_link` = '&subid' and `affiliate_name`='CPA Empire'";

$id = $ms_db->modify($sql);

if($id)
	$msg="Sucessfully updated........";
else
	$msg="There is some problem in the updatation";
?>

<?php require_once("header.php"); ?>

<title>
<?php echo SITE_TITLE; ?>
</title>

<script language="javascript">
	// Javascript code will come here
</script>

<?php require_once("top.php"); ?>

<?php require_once("left.php"); ?>

<?php
	if(isset($msg) && $msg!="")
		echo $msg;
?>	
<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>
