<?php
session_start();
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/profile.class.php");
require_once("classes/article.class.php");
require_once("classes/pclzip.lib.php");
require_once("classes/search.class.php");


$asm_db = new Database();
$asm_db->openDB();
$profile = new Profile();
$article = new Article();
$sc = new Search();

if(isset($_POST['process']))
{
	$process=$_POST['process'];
}
elseif(isset($_GET['process']))
{
	$process=$_GET['process'];
}
else
{
	$process="manage";
}

if($process=="manage")
{
	$order_sql = $sc->getOrderSql(array("id","url","dir_label","directory"),"id");
	//echo "Order SQL ------<".$order_sql;
	$url_rs = $article->getUrlDir();
	
}
elseif($process=="add")
{
	$dir_rs = $article->getDirectory();
}
elseif($process=="edit")
{
	$dir_rs = $article->getDirectory();
	$dir_edit_rs = $article->getUrlDirById($_GET['id']);
}
elseif($process=="delete")
{
	$profile->deleteurl($_GET['id']); 
	$msg.= "Directory has been Deleted successfully!";
	header("Location:manage_directory.php?msg=".$msg."");
}

if(isset($_POST['insert']) && $_POST['insert']=="Yes")
{
	$id = $profile->inserturl();
	
	if($id)
	{ 
		$msg.= "Directory has been created successfully!";
		header("Location:manage_directory.php?msg=".$msg."");
	}
	else
	{ 
		$msg.= "Directory has not been created, try again!";
		header("Location:manage_directory.php?msg=".$msg."");
	}
}
if(isset($_POST['edit']) && $_POST['edit']=="Yes")
{
	$id = $profile->editurl($_POST['id']);
	
	if($id)
	{ 
		$msg.= "Directory has been updated successfully!";
		header("Location:manage_directory.php?msg=".$msg."");
	}
	else
	{ 
		$msg.= "Directory has not been created, try again!";
		header("Location:manage_directory.php?msg=".$msg."");
	}
}
if(isset($_POST['submit_d']) && $_POST['submit_d']=="Yes")
{
	if($_POST['directory']!="")
	{
		$dir_rs = $article->getDirectory();
		$dir_edit_rs = $article->getUrlByDir($_POST['directory']);
	}
}
?>
<?php
require_once("header.php");
?>
<link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
function showfields()
{
	document.getElementById("showurl").style.display="block";
}
function submit_dir()
{
	document.article_d.submit();
}

function check_field()
{
	
	var form=document.article_u;
	if(form.dir_label.value==""){
		alert("Please enter account label");
		form.dir_label.focus();
		return false;
	}
	if(form.login.value==""){
		alert("Please enter name");
		form.login.focus();
		return false;
	}
	if(form.password.value==""){
		alert("Please enter password");
		form.password.focus();
		return false;
	}
	
	
}

</script>
<?php
require_once("inc_menu.php");
?>
<?php
if($process=="manage")
{
?>
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
<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
			<tr>
				<td valign = "top" align="center" class="heading"><a href="manage_directory.php?process=add" class="menu">Add New Account</a></td>
			</tr>
			<br>
			
</table>
<br>
		<table border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
		<tr  class="tableheading">
		<th><a title = "Sort" class = "menu" href="?sort=id">Directory #</a></th>
		<th><a title = "Sort" class = "menu" href="?sort=url">Directory URL</a></th>
		<th><a title = "Sort" class = "menu" href="?sort=dir_label">Account Label</a></th>
		<th><a title = "Sort" class = "menu" href="?sort=directory">Directory Type</a></th>
		<th></th>
		<th></th>
		</tr>
		<?php
		if($url_rs)
		{
			while($url = $asm_db->getNextRow($url_rs))
			{
		?>
		<tr>
			<td align="center"><?php echo $url['id'];?></td>
			<td align="center"><?php echo $url['url'];?></td>
			<td align="center"><?php echo $url['dir_label'];?></td>
			<td align="center"><?php echo $url['directory'];?></td>
			<td align="center" width="16px">
				<a href="?process=edit&id=<?php echo $url['id'];?>">
				<img src="images/edit.png" border="0" title="Click Here To Edit" style="cursor:pointer">
				</a>
			</td>
			<td align="center" width="16px">
				<a onclick="javascript:return confirm('Are you sure you want to delete this directory');"  href="?process=delete&id=<?php echo $url['id'];?>">
				<img src="images/delete.png" border="0" title="Delete" style="cursor:pointer">
				</a>
			</td>
		</tr>
		<?php
			}
		}
		?>
		</table>
<?php
}elseif($process=="add" || $process=="edit")
{
?>

<table align="center" width="80%" cellpadding="3" cellspacing="3" class="summary2" border="0">
	<form action="" name="article_d" method="post">
	<tr>
		<td align="right" width="35%">Article Directory:</td>
		<td align="left">
			<select name="directory" style="width:50%" onChange="submit_dir();">
				<option value="" selected="selected">Please Select Directory Type</option>
				<?php
				if($dir_rs)
				{
					while($dir = $asm_db->getNextRow($dir_rs))
					{
				?>
						<option value="<?php echo $dir['id'];?>" <?php if($dir_edit_rs['directory_id']==$dir['id']) { echo "selected"; } ?>><?php echo $dir['directory'];?></option>
				<?php
					}
				}
				?>
			</select>
			<input type="hidden" name="submit_d" value="Yes" />
		</td>
	</tr>
	</form>
	<?php
	if($dir_edit_rs)
	{
	?>
	<form action="" name="article_u" method="post">
	<tr>
		<td align="left" colspan="2" style="padding-left:80px;">
		<div id="showurl">
			<table align="center" border="0" width="100%" cellpadding="3" cellspacing="3">
			<tr>			
				<td align="right">URL:</td>
				<td align="left"><input type="text" readonly="true" name="url" size="42" value="<?php echo $dir_edit_rs['url'];?>"></td>
			</tr>
			<tr>			
				<td align="right">Account Label:</td>
				<td align="left"><input type="text" name="dir_label" size="42" value="<?php echo $dir_edit_rs['dir_label'];?>"></td>
			</tr>
			<tr>
				<td colspan="2" style="font-weight:bold" align="center">Login Details</td>
			<tr>			
				<td align="right">User Name:</td>
				<td align="left"><input type="text" name="login" size="42" value="<?php echo $dir_edit_rs['username'];?>"></td>
			</tr>
			<tr>			
				<td align="right">Password:</td>
				<td align="left"><input type="password" name="password" size="42" value="<?php echo $dir_edit_rs['password'];?>"></td>
			</tr>
			<?php 
			if($process=="edit")
			{
			?>
				<input type="hidden" name="edit" value="Yes" />
				<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
			<?php
			}else
			{
			?>
				<input type="hidden" name="insert" value="Yes" />
			<?php
			}
			?>
			<input type="hidden" name="process" value="<?php echo $process;?>" />
			<input type="hidden" name="directory" value="<?php echo $_POST['directory'];?>" />
			<tr>
				<td colspan="2" align="center"><input type="submit" name="submit" value="Submit" onclick="return check_field()">&nbsp;&nbsp;<input type="button" name="cancel" value="Cancel" onclick="window.location.href = 'manage_directory.php'" /></td>
			</tr>
			</table>
		</div>
		</td>
	</tr>
	</form>
	<?php
	}
	?>
</table>

<?php
	if($process=="edit")
	{
	?>
		<script>showfields();</script>
	<?php
	}
	?>
<?php
}
?>
<?php
require_once("footer.php");
?>
