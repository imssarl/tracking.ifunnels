<?php
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/profile.class.php");
require_once("classes/article.class.php");

$asm_db = new Database();
$asm_db->openDB();
$profile = new Profile();
$article = new Article();

if(isset($_POST['Submit']) && $_POST['Submit']!="")
{
	$today = date("Y-m-d");
	
	if($_POST['schedule']=="T")
	{
		$sql = "update `".TABLE_PREFIX."submission` set start_date='".$today."', schedule='".$today."',isScheduled='Y' where isProcess='N' and article_id=".$_POST['id'];
		$asm_db->modify($sql);
		
		$sql="update `".TABLE_PREFIX."article` set flag='Y' where id=".$_POST['id'];
		$asm_db->modify($sql);
		
//		header("location:post.php");
		echo "<script>window.close();</script>";
	}
	elseif($_POST['schedule']=="S")
	{
		
		if($_POST['date']!="" && $today<$_POST['date'] && $_POST['scount']=="")
		{
			$sql = "update `".TABLE_PREFIX."submission` set schedule='".$_POST['date']."' where isProcess='N' and article_id=".$_POST['id'];
			$asm_db->modify($sql);
			
			echo "<script>window.close();</script>";
		}
		elseif($_POST['date']=="" || $today>=$_POST['date'])
		{	
			header("location:schedule.php?id=".$_POST['id']."&msg=Please Choose Date Greater than ".$today."");
		}
		elseif($_POST['date']!="" && $today<$_POST['date'] && $_POST['scount']!="")
		{
			$sql = "select id from `".TABLE_PREFIX."submission` where isProcess='N' and article_id=".$_POST['id'];
			$id_res = $asm_db->getRS($sql);
			
			$scount = $_POST['scount'];
			$sday = $_POST['sday'];
			
			$date = $_POST['date'];
			$newdate = $_POST['date'];
			$sc = $scount;
			while($art_id = $asm_db->getNextRow($id_res))
			{
				echo $sql = "update `".TABLE_PREFIX."submission` set start_date='".$date."', schedule='".$newdate."' where isProcess='N' and id=".$art_id['id'];
				$asm_db->modify($sql);
			
				if($_POST['sday']=="D")
				{
					$d = explode("-",$date);
					$tm =mktime(0, 0, 0, date('m'), $d[2]+$sc, date('Y'));
					$newdate = date("Y-m-d",$tm);
				}
				elseif($_POST['sday']=="W")
				{
					$sw = $sc*7;
					$d = explode("-",$date);
					$tm =mktime(0, 0, 0, date('m'), $d[2]+$sw, date('Y'));
					$newdate = date("Y-m-d",$tm);
				}
				elseif($_POST['sday']=="M")
				{
					$d = explode("-",$date);
					$tm =mktime(0, 0, 0, date('m')+$sc, $d[2], date('Y'));
					$newdate = date("Y-m-d",$tm);
				}
				
				
				$sc = $sc+$scount;
			}
			
			/*$sql="update `".TABLE_PREFIX."article` set flag='Y' where id=".$_POST['id'];
			$asm_db->modify($sql);*/
			
			echo "<script>window.close();</script>";
		}
	}
	
	/*if($today<$_POST['date'])
	{
		if($_POST['process']=="schedule")
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
			$sql = "update `".TABLE_PREFIX."submission` set schedule='".$_POST['date']."' where article_id=".$_POST['id'];
			$asm_db->modify($sql);
			echo "<script>window.close();</script>";
		}
		
	}
	else
	{	
		header("location:schedule.php?id=".$_POST['id']."&msg=Please Choose Date Greater than ".$today."");
	}*/
}

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
            <td align="center" width="40%" class="heading" colspan="2" style="font-weight:bold;">Schedule</td>
</tr>
<?php
$sql = "select * from `".TABLE_PREFIX."submission` where article_id=".$_GET['id'];
$result = $asm_db->getRS($sql);

if($result)
{
?>
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
	<td align="right">Time between submission to the different directories:</td>
	<td>
				<select name="scount">
					<option value="">Please Select</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
				<select name="sday">
					<option value="D">Day</option>
					<option value="W">Week</option>
					<option value="M">Month</option>
				</select>
			</td>
</tr>
<tr>
	<td colspan="2" align="center" class="heading">
		<input type="hidden" name="process" value="<?php echo $_GET['process'];?>" />
		<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
		<input type="submit" name="Submit" value="Save">
	</td>
</tr>
<?php
}else
{
?>
<tr>
	<td align="center">Please select Profile and Directories before scheduling</td>
</tr>
<tr>
            <td align="center" width="40%" class="heading" colspan="2" style="font-weight:bold;"><input type="button" value="Close" onclick="window.close();" /></td>
</tr>
<?php
}
?>
</table>
</form>
<?php
require_once("footer.php");
?>