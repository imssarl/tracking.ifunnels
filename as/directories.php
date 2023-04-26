<?php
session_start();
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/article.class.php");
require_once("classes/profile.class.php");

$asm_db = new Database();
$asm_db->openDB();
$article_obj = new Article();

$directory_rs=$article_obj->getDirectory();

$sql = "select profile_id from `".TABLE_PREFIX."article_profile` where article_id=".$_GET['id'];

$attach_profile = $asm_db->getDataSingleRow($sql);

$sql = "select * from `".TABLE_PREFIX."article` where id=".$_GET['id'];
$article_data = $asm_db->getDataSingleRow($sql);

if(isset($_POST['submit']) && $_POST['submit']!="")
{
//	print_r($_POST);
$sql = "delete from `".TABLE_PREFIX."submission` where isProcess='N' and article_id=".$_POST['id'];
$asm_db->modify($sql);
	for($i=0;$i<count($_POST['chkdir']);$i++)
	{
		$value = $_POST['chkdir'][$i];
		$url_id = $_POST['url'.$value];
		$cat_id = $_POST['cat'.$value];
		$dir_type = $_POST['dirtype'.$value];
		//echo $dir_id."<br>";
		$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,keyword,directory_id,url_id,category_id,article_id,dir_type,user_id) values("'.$_POST["title"].'","'.$_POST["summary"].'","'.$_POST["body"].'","'.$_POST["keyword"].'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$_POST["id"].'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';
		$id = $asm_db->insert($sql);
		
		$sql = "select profile_id from `".TABLE_PREFIX."article_profile` where article_id=".$_POST['id'];
		$res = $asm_db->getDataSingleRecord($sql);
		
		$attach_profile = explode(",",$res);
		
		shuffle($attach_profile);
		
		$attach = $attach_profile[0];
		//echo $attach."<br>";
		$sql ="update `".TABLE_PREFIX."submission` set profile_id='".$attach."' where id=".$id;
		$asm_db->modify($sql);
		
	}
	
	
	
	echo "<script>window.close();</script>";
}

?>

<?php
//require_once("header.php");
?>
<link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
<!--<script language="javascript" type="text/javascript" src="jscripts/request.js"></script>-->
<script language="javascript" type="text/javascript">
function showurl(dirid, val)
{
	var urldivid="urldiv"+dirid;
	var catdivid="catdiv"+dirid;
	if(val.checked)
	{
		document.getElementById(urldivid).style.display="";
		document.getElementById(catdivid).style.display="";
	}
	else
	{
		document.getElementById(urldivid).style.display="none";
		document.getElementById(catdivid).style.display="none";
	}
}
function uncheck(vals)
{
document.getElementById(vals).checked=false;
}

function check_box(){

		flags=false;
		var element;
		var numberOfControls = document.frm.length;
		for (Index = 0; Index < numberOfControls; Index++)
		{
			element = document.frm[Index];
			if (element.type == "checkbox")
			{
				if (element.checked == true)
				{
					flags=true;
				}
			}
		}
	if (flags==false) { alert("Please select at least one checkbox."); return false; } else { return true;}

}

</script>
<?php
if($attach_profile)
{
?>
<form action="" name="frm" method="post">
<table align="center" cellpadding="3" cellspacing="3" class="summary">
<tr>
            <td align="center" width="40%" class="heading" colspan="2" style="font-weight:bold;">Directories</td>
</tr>
<tr>
	<td align="left">
		
			<table cellpadding="0" cellspacing="4" border="0" width="100%">
			<tr>
				<td colspan="2" class="heading" width="33%">Directory Type</td><td width="33%" class="heading">Directory [Label] URL</td><td width="33%" class="heading">Category</td>
			</tr>
			
			<?php
			if($directory_rs)
			{
				while($directory=$asm_db->getNextRow($directory_rs))
				{
					//echo $directory['directory'];
					echo "<tr><td height='45'>";
			?>
				<input type="checkbox" name="chkdir[]" id="chkdir<?php echo $directory['id'];?>" value="<?php echo $directory['id'];?>" onclick="showurl('<?php echo $directory['id'];?>',this);" />
				<input type="hidden" name="dirtype<?php echo $directory['id'];?>" value="<?php echo $directory['type'];?>" />
				<script language="javascript">uncheck('chkdir<?php echo $directory['id'];?>'); </script>
				</td>
				 <td><?php echo $directory['directory'];?>
		
			<?php
				echo "</td><td>";
				$url_rs = $article_obj->getUrl($directory['id']);
				?>
				
			<div id="urldiv<?php echo $directory['id'];?>" style="display:none; float:left; padding-bottom:5px;">
				
				
				<select style="width:250px;" name="url<?php echo $directory['id'];?>">
				<?php
				if($url_rs)
				{
					while($url = $asm_db->getNextRow($url_rs))
					{
				?>
					<option value="<?php echo $url['id'];?>"><?php echo "[".$url['dir_label']."] ".$url['url'];?></option>
				<?php
					}
				}
				?>
				</select>
			</div>
		<?php
				echo "</td><td>";
				$cat_rs = $article_obj->getCategorybyDIR($directory['id']);
			?>	
				<div id="catdiv<?php echo $directory['id'];?>" style="display:none; float:left; padding-bottom:5px;">
				
				
				<select style="width:250px;" name="cat<?php echo $directory['id'];?>">
				<?php
				if($cat_rs)
				{
					while($cat = $asm_db->getNextRow($cat_rs))
					{
				?>
					<option value="<?php if($cat['cat_id']=="") echo $cat['cat_name']; else echo $cat['cat_id']; ?>"><?php echo $cat['cat_name'];?></option>
				<?php
					}
				}
				?>
				</select>
				</div>
			<?php	
				echo "</td></tr>";
				}
				
			}
			?>
			</table>
			
	</td>
</tr>
<tr><td  align="center" width="40%" class="heading" colspan="2">
	<input type="hidden" name="title" value="<?php echo $article_data['title'];?>" />
	<input type="hidden" name="summary" value="<?php echo $article_data['summary'];?>" />
	<input type="hidden" name="body" value="<?php echo $article_data['body'];?>" />
	<input type="hidden" name="keyword" value="<?php echo $article_data['keyword'];?>" />
	<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
	<input type="submit" value="Submit" name="submit"  onclick="return check_box()" />
</td></tr>
</table>
</form>
<?php
}else
{
?>
<table align="center" cellpadding="3" cellspacing="3" class="summary">
<tr>
            <td align="center" width="40%" class="heading" colspan="2" style="font-weight:bold;">Directories</td>
</tr>
<tr>
	<td align="center" style="font-weight:bold;">Please attach Profile before selecting Directory</td>
</tr>
<tr><td  align="center" class="heading">
	<input type="button" value="Close"  onclick="window.close();"/>
</td></tr>
</table>
<?php
}
?>
<?php
//require_once("footer.php");
?>