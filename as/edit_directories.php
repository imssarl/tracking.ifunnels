<?php
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/article.class.php");

$asm_db = new Database();
$asm_db->openDB();
$article_obj = new Article();

$directory_rs=$article_obj->getDirectory();

if(isset($_POST['submit_d']) && $_POST['submit_d']=="Yes")
{
	if($_POST['directory']!="")
	{
		$url_rs = $article_obj->getUrl($_POST['directory']);
		$dir_type = $article_obj->getDirType($_POST['directory']);
	}
}

if(isset($_POST['submit_u']) && $_POST['submit_u']=="Yes")
{
	//$profile_rs = $article_obj->getProfile($_POST['directory'],$_POST['url']);
	
	$cat_rs = $article_obj->getCategory();
}

if(isset($_POST['submit_p']) && $_POST['submit_p']=="Yes")
{
	$id = $article_obj->updateArticle();
	echo "<script>window.close();

		if (!window.opener.closed) {
		window.opener.location.reload();
		window.opener.focus();
		}
		</script>";
}
?>
<?php
require_once("header.php");
?>
<link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
function submitdirectory()
{
	document.frm.submit();
}

function submiturl()
{
	document.frmurl.submit();
}


</script>

<table align="center" cellpadding="3" cellspacing="3" class="summary">
<tr>
            <td align="center" width="40%" class="heading" colspan="2" style="font-weight:bold;">Directories</td>
</tr>
<tr>
	<td align="right">Article Directory:</td>
	<td align="left">
		<form action="" name="frm" method="post">
			<select name="directory" style="width:70%" onchange="submitdirectory();">
				<option value="" selected="selected">Please Select Directory</option>
			<?php
			if($directory_rs)
			{
				while($directory=$asm_db->getNextRow($directory_rs))
				{
			?>
				<option value="<?php echo $directory['id'];?>" <?php if($_POST['directory']==$directory['id']) { ?> selected="selected" <?php } ?>><?php echo $directory['directory'];?></option>
			<?php
				}
			}
			?>
			</select>

			<input type="hidden" name="submit_d" value="Yes" />
		</form>
	</td>
</tr>
<?php
if($url_rs)
{
?>
<tr>
	<td align="right">URL:</td>
	<td align="left">
		<form action="" name="frmurl" method="post">
			<select name="url" style="width:70%" onchange="submiturl();">
				<option value="" selected="selected" >Please Select URL</option>
			<?php
				while($url=$asm_db->getNextRow($url_rs))
				{
			?>
				<option value="<?php echo $url['id'];?>" <?php if($_POST['url']==$url['id']) { ?> selected="selected" <?php } ?>><?php echo "[".$url['dir_label']."] ".$url['url'];?></option>
			<?php
				}
			?>
			</select>
			<input type="hidden" name="submit_d" value="Yes" />
			<input type="hidden" name="submit_u" value="Yes" />
			<input type="hidden" name="type" value="<?php echo $dir_type['type']?>" />
			<input type="hidden" name="directory" value="<?php echo $_POST['directory'];?>" />
		</form>
	</td>
</tr>
<?php
}
?>
<form action="" name="frmprofile" method="post" onsubmit="return validate();">
<?php
if($cat_rs)
{
?>
<tr>
	<td align="right">Category:</td>
	<td align="left">
			<select name="cat" style="width:80%">
				<option value="" selected="selected" >Please Select Category</option>
			<?php
				while($cat=$asm_db->getNextRow($cat_rs))
				{
					if($_POST['type']=="EA")
					{
						$cat['cat_id']=$cat['cat_name'];
					}
			?>
				<option value="<?php echo $cat['cat_id'];?>" <?php if($_POST['cat']==$cat['cat_name']) { ?> selected="selected" <?php } ?>><?php echo $cat['cat_name'];?></option>
			<?php
				}
			?>
			</select>

	</td>
</tr>
<?php
}
?>
<tr>
	<td colspan="2" align="center" class="heading">
		<input type="hidden" name="submit_p" value="Yes" />
	    <input type="hidden" name="url" value="<?php echo $_POST['url'];?>" />
		<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
		<input type="hidden" name="type" value="<?php echo $dir_type['type']?>" />
		<input type="hidden" name="directory" value="<?php echo $_POST['directory'];?>" />
		<input type="submit" name="Submit" value="Save" >
	</td>
</tr>

</form>
</table>
<?php
require_once("footer.php");
?>