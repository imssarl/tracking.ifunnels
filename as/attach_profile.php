<?php
session_start();
require_once("config/config.php");
require_once("classes/database.class.php");

$asm_db = new Database();
$asm_db->openDB();

$sql = "select * from `".TABLE_PREFIX."profile` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
$profile_rs = $asm_db->getRS($sql);

if(isset($_POST['submit']) && $_POST['submit']=="Attach")
{
	for($i=0;$i<count($_POST['attach_profile']);$i++)
	{
		$attach_profile = implode(",",$_POST['attach_profile']);
	}
//	echo $attach_profile;
	$sql = "select count(*) as total,id from `".TABLE_PREFIX."article_profile` where article_id=".$_POST['id']." GROUP BY id";
	$count = $asm_db->getData($sql);
	//print_r($count);
	//echo $count[0]['total'];
	//die;
	if($count[0]['total']>0)
	{
		$sql = "update `".TABLE_PREFIX."article_profile` set profile_id='".$attach_profile."' where id=".$count[0]['id'];
		$asm_db->modify($sql);
		echo "<script>window.close();</script>";
	}
	else
	{
		$sql = "insert into `".TABLE_PREFIX."article_profile`(article_id,profile_id) values('".$_POST['id']."','".$attach_profile."')";
		$id = $asm_db->insert($sql);
		echo "<script>window.close();</script>";
	}
}
?>
<?php
//require_once("header.php");
?>
<link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
<form action="" method="post" name="attach">
<table align="center" class="summary" cellpadding="5" cellspacing="5">
<tr>
            <td align="center" width="40%" class="heading" colspan="2" style="font-weight:bold;">Attach Profile</td>
</tr>
	<tr>
		<?php
		if($profile_rs)
		{
		?>
		<td align="center">
			<select name="attach_profile[]" style="width:50%" multiple="multiple">
			<?php
				while($profile = $asm_db->getNextRow($profile_rs))
				{
			?>
				<option value="<?php echo $profile['id'];?>"><?php echo $profile['profile_name'];?></option>
			<?php
				}
			?>
			</select>
		</td>
		<?php
		}
		?>
	</tr>
	<tr><!--window.close();-->
		<td align="center" class="heading">
			<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
			<input type="submit" name="submit" value="Attach" />
		</td>
	</tr>
</table>
</form>
<?php
//require_once("footer.php");
?>