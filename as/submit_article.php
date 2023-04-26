<?php
session_start();
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/article.class.php");

$asm_db = new Database();
$asm_db->openDB();
$article_obj = new Article();

if(isset($_GET['id']) && $_GET['id']!="")
{
	
	$sql = "select schedule from `".TABLE_PREFIX."submission` where article_id=".$_GET['id'];
	$schedule_rs = $asm_db->getDataSingleRow($sql);
	//print_r($schedule_rs); die();
	if($schedule_rs['schedule']!="")
	{
		$today = date("Y-m-d");
		//$sql="update `".TABLE_PREFIX."submission` set isScheduled='Y',start_date='".$today."' where article_id=".$_GET['id'];
		$sql="update `".TABLE_PREFIX."submission` set  start_date='".$today."',isScheduled='Y',isProcess='Y' where article_id=".$_GET['id'];
		$asm_db->modify($sql);
		$sql="update `".TABLE_PREFIX."article` set flag='Y',re_inject='N' where id=".$_GET['id'];
		$asm_db->modify($sql);
		header("location:post.php");
	}	
	else
	{
?>	
		<table align="center" class="summary2">
			<tr><td>Please Select Schedule Before Submitting</td></tr>
			<tr>
				<td><input type="button" value="Close" onclick="window.close();" /></td>
			</tr>
		</table> 
<?php	
	}
}
?>

