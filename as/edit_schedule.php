<?php
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/profile.class.php");
require_once("classes/article.class.php");

$asm_db = new Database();
$asm_db->openDB();
$profile = new Profile();
$article = new Article();

$sql = "select start_date from `".TABLE_PREFIX."submission` where id=".$_GET['id'];
$start_date = $asm_db->getDataSingleRow($sql);

if(isset($_POST['Submit']) && $_POST['Submit']!="")
{
	$today = date("Y-m-d");
	
	if($_POST['schedule']=="T")
	{
		$sql = "update `".TABLE_PREFIX."submission` set schedule='".$today."' where id=".$_POST['id'];
		$asm_db->modify($sql);
		
		header("location:post.php");
	}
	elseif($_POST['schedule']=="S")
	{
		if($start_date['start_date']<$_POST['date'])
		{
			$sql = "update `".TABLE_PREFIX."submission` set schedule='".$_POST['date']."' where id=".$_POST['id'];
			$asm_db->modify($sql);
			
			echo "<script>window.close();
	
				if (!window.opener.closed) {
				window.opener.location.reload();
				window.opener.focus();
				}
				</script>";
		}
		else
		{
			header("location:edit_schedule.php?id=".$_POST['id']."&msg=Please Choose Date Greater than ".$start_date['start_date']."");
		}
	}
}
?>
<?php
require_once("header.php");
?>
<link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
<table align="center" width="100%" border="0">
			<TR>
				<td align="center">
					<?php if(isset($_GET['msg']) && $_GET['msg']!=""){ ?>
					<span class="optional_field"><?php echo $_GET['msg']; ?></span>
					<?php } if(isset($error) && $error!=""){ ?>
					<span class="error"><?php echo $error; ?></span>
					<?php } ?>
				</TD>
			</TR>
</table>
<form action="" name="schedule" method="post">
<table align="center" cellpadding="3" cellspacing="3" class="summary">
<tr>
            <td align="center" width="40%" class="heading" colspan="2" style="font-weight:bold;">Change Schedule</td>
</tr>
<tr>
	<td align="right"><input type="radio" name="schedule" value="T" checked="checked"/></td>
	<td>Submit Now</td>
</tr>

<tr>
	<td align="right"><input type="radio" name="schedule" value="S" /></td>
	<td>Schedule:
	<input type="text" value="" readonly name="date" id="date">
					<input type="button" value="Pick" onclick="displayCalendar(document.forms[0].date,'yyyy-mm-dd',this)">
	</td>
</tr>
<tr>
	<td colspan="2" align="center" class="heading">
		<input type="hidden" name="process" value="<?php echo $_GET['process'];?>" />
		<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
		<input type="submit" name="Submit" value="Save">
	</td>
</tr>
</table>
</form>
<?php
require_once("footer.php");
?>