<?php

session_start();

require_once("config/config.php");

require_once("classes/database.class.php");

require_once("classes/article.class.php");

require_once("classes/pagination.class.php");

require_once("classes/search.class.php");



$asm_db = new Database();

$asm_db->openDB();

$article_obj = new Article();

$pg = new Pagination;

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



if($process=="delete")

{

	$sql = "delete from `".TABLE_PREFIX."submission` where id=".$_GET['id'];

	$asm_db->modify($sql);

	

	$sql = "select * from `".TABLE_PREFIX."submission` where article_id=".$_GET['art_id'];

	$art_res = $asm_db->getRS($sql);

	

	if(!$art_res)

	{

		$sql = "update `".TABLE_PREFIX."article` set flag='F',re_inject='N' where id=".$_GET['art_id'];

		$asm_db->modify($sql);

		

		$sql = "delete from `".TABLE_PREFIX."submission` where article_id=".$_GET['art_id'];

		$asm_db->modify($sql);

		

		$sql = "delete from `".TABLE_PREFIX."article_profile` where article_id=".$_GET['art_id'];

		$asm_db->modify($sql);

		

		header("location:manage_submission.php?msg=Article has been Deleted & Restored");

	}

	else

	{

	header("location:manage_submission.php?process=manage&msg=Article has been sucessfully deleted");

	}

}



if($process=="restore")

{



		$sql = "update `".TABLE_PREFIX."article` set re_inject='Y' where id=".$_GET['id'];

		$asm_db->modify($sql);

		

		//$sql = "delete from `".TABLE_PREFIX."submission` where article_id=".$_GET['id'];

		//$asm_db->modify($sql);

		

		$sql = "delete from `".TABLE_PREFIX."article_profile` where article_id=".$_GET['id'];

		$asm_db->modify($sql);

	

		header("location:manage_submission.php?msg=Article has been restored");

	

}



if(isset($_POST['delete']) && $_POST['delete']!="")

{

	$s=implode(",",$_POST['chk']);

	$sql="delete from `".TABLE_PREFIX."submission` where `id` in ($s)";

	$asm_db->modify($sql);

	

	$sql = "select * from `".TABLE_PREFIX."submission` where article_id=".$_POST['art_id'];

	$art_res = $asm_db->getRS($sql);

	

	if(!$art_res)

	{

		$sql = "update `".TABLE_PREFIX."article` set flag='N',re_inject='N' where id=".$_POST['art_id'];

		$asm_db->modify($sql);

		

		$sql = "delete from `".TABLE_PREFIX."submission` where article_id=".$_POST['art_id'];

		$asm_db->modify($sql);

		

		$sql = "delete from `".TABLE_PREFIX."article_profile` where article_id=".$_POST['art_id'];

		$asm_db->modify($sql);

		

		header("location:manage_submission.php?msg=Article has been Deleted & Restored");

	}

	else

	{

		$sql = "update `".TABLE_PREFIX."article` set re_inject='Y' where id=".$_POST['art_id'];

		$asm_db->modify($sql);

		

		$sql = "delete from `".TABLE_PREFIX."article_profile` where article_id=".$_POST['art_id'];

		$asm_db->modify($sql);

		

		header("location:manage_submission.php?msg=Article has been Deleted & Restored");

	}

}



//$sql = "select count(*) from `".TABLE_PREFIX."article` as a,`".TABLE_PREFIX."directory` as b,`".TABLE_PREFIX."profile` as c,`".TABLE_PREFIX."submission` as d where d.isScheduled='Y'  and d.directory_id=b.id and d.article_id=a.id and (c.id in (d.profile_id) or c.profile_id in (d.profile_id))";



$sql = "select count(*) from `".TABLE_PREFIX."article` where flag='Y' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];



$totalrecords = $asm_db->getDataSingleRecord($sql);

if ($totalrecords>0)

{

	$pg->setPagination($totalrecords);

}

else

{

	$pg->startpos=0;

}



$order_sql = $sc->getOrderSql(array("id","title"),"id");



$article_rs = $article_obj->getSubmissionArticle();

?>

<?php

require_once("header.php");

?>

<link href="stylesheets/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="jscripts/common.js"></script>

<script type="text/javascript" language="javascript">

function opencode(id,process)

{

	openwindow= window.open ("showart.php?id="+id+"&process="+process, "GETCODE",

		"status=0,scrollbars=1,width=850,height=700,resizable=1");

	

	openwindow.moveTo(50,50);

}

function geterror(id)

{

	openwindow= window.open ("error.php?id="+id, "GETCODE",

		"status=0,scrollbars=1,width=750,height=500,resizable=1");

	

	openwindow.moveTo(50,50);

}

function opendir(id,process)

{

	openwindow= window.open ("edit_directories.php?id="+id+"&process="+process, "GETCODE",

		"status=0,scrollbars=1,width=650,height=500,resizable=1");

	

	openwindow.moveTo(50,50);

}

function openschedule(id,process)

{

	openwindow= window.open ("edit_schedule.php?id="+id+"&process="+process, "GETCODE",

		"status=0,scrollbars=1,width=650,height=500,resizable=1");

	

	openwindow.moveTo(50,50);

}



function getpost()

{

	openwindow= window.open ("post.php", "GETCODE",

		"status=0,scrollbars=1,width=0,height=0,resizable=1");

	

	openwindow.moveTo(0,0);

}



function checkUncheckAll(theElement)

{

	var tForm = theElement.form, z = 0;

	while (tForm[z].type == 'checkbox' && tForm[z].name != 'checkall')

	{

	tForm[z].checked = theElement.checked;

	z++;

	}

}



/*function dele()

{

	flags=false;

	var element;

	var numberOfControls = document.myform.length;

	for (Index = 0; Index < numberOfControls; Index++)

	{

	element = document.myform[Index];

	alert(document.myform[Index].type);

//	alert(document.getElementById("chk1").type);

	alert(element);

	if (element.type == "checkbox")

	{

		if (element.checked == true)

		{

		flags=true;

		}

	}

}

if (flags==false) { alert("Please select at least one row."); return false; } else { return true; }

}*/



function chk(frm)

{

// 	flags=false;

// 	if (flags==false) { return false; }

	var chkflg=false;

	for(i=0;i<frm.elements.length;i++)

	{

		if(frm.elements[i].type=="checkbox")

		{

			if(frm.elements[i].checked==true)

				{chkflg=true;break;	}

		}	

	}

	if(chkflg==false)

	{

		alert("Please select at least one row."); return false;

	}

	else

	{

		if (confirm("Are you sure you want to delete Article")==true)

		{

			return true;

		}

		else

		{

			return false;

		}

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

				<td colspan="6">

					<?php $pg->showPagination(); ?>

				</td>

			</tr>

</table><br /><br />



<table border="0" cellspacing="1" cellpadding="1" align="center" class="summary">



	<tr class="tableheading">

		<th><a title = "Sort" class = "menu" href="?sort=id">No. #</a></th>

		<th><a title = "Sort" class = "menu" href="?sort=title">Article</a></th>

		<th><a title = "Sort" class = "menu" href="#">Start Date</a></th>

		<th><a title = "Sort" class = "menu" href="?sort=schedule">Completion Date</a></th>

		<th>Re-Inject</th>

	</tr>

<?php

if($article_rs)

{

	$tblmat=0;

	$count=1;

	while($article = $asm_db->getNextRow($article_rs))

	{

		$id = $article['id'];

?>

		<tr  id="row<?php echo $id; ?>"  class='<?php echo ($tblmat++%2) ? "tablematter1" : "tablematter2" ?>' >

				<td align="center">

				<?php

					if((isset($_GET['page']) && $_GET['page']==1) || $_GET['page']=="")

					echo $count;

					elseif(isset($_GET['page']))

					{

					echo $count + (ROWS_PER_PAGE * ($_GET['page']-1));

					}

					else

					{

					echo $count;

					}

					$count++;

				?>

				</td>

				<td align="center">

					<div id="count<?php echo $id;?>"><a href="#" onClick="hndlsr(<?php echo $id; ?>,0); return false;"><?php echo str_replace("&acirc;€“",' - ', html_entity_decode($article['title']));?></a></div>

				</td>

				<?php 

					$sql1 = "select MIN(start_date) as stdate,MAX(schedule) as schd from `".TABLE_PREFIX."submission` where article_id=".$id;

					

					$sdate = $asm_db->getDataSingleRow($sql1);

				?>

				<td align="center">

					<?php echo $sdate['stdate'];?>

				</td>

				<td align="center">

				<?php echo $sdate['schd'];?>

				</td>

				<td align="center">

					<a onclick="javascript:return confirm('Are you sure you want to Restore this Article');"  href="?process=restore&id=<?php echo $id;?>">

					<img src="images/resume.png" border="0" title="Restore" style="cursor:pointer">

					</a>

				</td>

			</tr>

			

			<tr>

			<TD colspan="6">

			</TD>

			</tr>

	<!-- 	/////////////////// Code for Inner Table starts here////////////////////////// -->

		<tr>

			<td colspan="7">

				<div class="noshow" id="ad<?php echo $id ?>">

<form name="myform" action="" method="post" onsubmit="return chk(this)">				

			<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">

			<tr  class="tableheading">

			<th>No. #</th>

			<th>Article</th>

			<th>Account Label/URL</th>

			<th>Profile</th>

			<th>Start Date</th>

			<th>Completion Date</th>

			<th>Status</th>

			<th></th>

			<th></th>

			<th></th>

			<th><input name='chkall' type='checkbox' value='chkall' onClick='checkUncheckAll(this)'></th>

			</tr>

<?php

	$submission_rs = $article_obj->getSubmission($id);

	if($submission_rs)

	{

		$tblmat=0;

		$cnt=1;

		$no=0;

		while($submission = $asm_db->getNextRow($submission_rs))

		{

			$id = $submission['id'];

			$no=$no+1;

			//echo $submission['isSubmit'];

?>

		<tr id="row<?php echo $id; ?>"  class='<?php echo ($tblmat++%2) ? "tablematter1" : "tablematter2" ?>' >

			<td align="center">

			<?php

				if((isset($_GET['page']) && $_GET['page']==1) || $_GET['page']=="")

				echo $cnt;

				elseif(isset($_GET['page']))

				{

				echo $cnt + (ROWS_PER_PAGE * ($_GET['page']-1));

				}

				else

				{

				echo $cnt;

				}

				$cnt++;

			?>

			</td>

			<td align="center">

			<?php

			if($submission['isSubmit']!='Y')

			{

			?>

				<a href="#" onclick="opencode('<?php echo $id;?>','title')" style="cursor:pointer"><?php echo str_replace("&acirc;€“",' - ', html_entity_decode($submission['title']));?></a>

			<?php

			}else

			{

			?>

			<?php echo $submission['title'];?>

			<?php

			}

			?>

			</td>

			<td align="center">

			<?php

			if($submission['isSubmit']!='Y')

			{

			?>

				<a href="#" onclick="opendir('<?php echo $id;?>','dir')" style="cursor:pointer"><?php echo $submission['directory']." [".$submission['dir_label']."]<br>".$submission['url'];?></a>

			<?php

			}else

			{

			?>

			<?php echo $submission['directory']." [".$submission['dir_label']."]<br>".$submission['url'];?>

			<?php

			}

			?>

			</td>

			<td align="center">

			<?php

			if($submission['isSubmit']!='Y')

			{

			?>

				<a href="#" onclick="opencode('<?php echo $id;?>','author')" style="cursor:pointer"><?php echo $submission['profile_name'];?></a>

			<?php

			}else

			{

			?>

			<?php echo $submission['profile_name'];?>

			<?php

			}

			?>

			</td>

			<td align="center">

				<?php echo $submission['start_date'];?>

			</td>

			<td align="center">

			<?php

			if($submission['isSubmit']!='Y')

			{

			?>

					<a href="#" onclick="openschedule('<?php echo $id;?>','schedule')" style="cursor:pointer"><?php echo $submission['schedule'];?></a>

			<?php

			}else

			{

				echo $submission['schedule'];

			}

			?>

			</td>

			<td align="center">

			<?php

			if($submission['isSubmit']!='Y' && $submission['error']!='Y')

			{

			?>

			<?php echo "Pending";?>

			<?php

			}elseif($submission['isSubmit']=='Y' && $submission['error']=='N')

			{

			?>

			<?php echo "Completed";?>

			<?php

			}elseif($submission['error']=='Y')

			{

				echo "Failed";

			}

			?>

			</td>

			<td align="center">

				<?php

					if($submission['error']=='Y')

					{

				?>

						<img src="images/denied.png" border="0" title="Error Log" style="cursor:pointer" onClick="geterror('<?php echo $id;?>');" style="cursor:pointer; height:20px;">

				<?php

					}

				?>

			</td>

			<td align="center">

				<?php

					if($submission['error']=='Y')

					{

				?>

						<img src="images/scan.png" border="0" title="Re-Submit" style="cursor:pointer" onclick="getpost();">

				<?php

					}

				?>

			</td>

			<td align="center">

			<?php

			if($submission['isSubmit']!='Y')

			{

			?>

				<a onclick="javascript:return confirm('Are you sure you want to delete this Article');"  href="?process=delete&id=<?php echo $id;?>&art_id=<?php echo $article['id'];?>">

					<img src="images/delete.png" border="0" title="Delete" style="cursor:pointer"></a>

			<?php

			}

			?>

			</td>

			<td align="center">

			<?php

			/*if($submission['isSubmit']!='Y')

			{

			?>

				<input name="chk[]" id="chk<?php echo $no;?>" type="checkbox" value="<?php echo $id;?>">

			<?php

			}

			*/?>

			<?php  

			// changes for task 104 issue01 on 07_nov at 4:00pm

			if($submission['isSubmit']!='Y')

			{

			?>

				<input name="chk[]" id="chk<?php echo $no;?>" type="checkbox" value="<?php echo $id;?>">

			<?php

			}

			?>

			

			<!--<input name="chk[]" id="chk<?php //echo $no;?>" type="checkbox" value="<?php //echo $id;?>"> -->

			</td>		

		</tr>

		<tr>

	<td colspan="9">

	<?php if($submission['isSubmit']!='Y')

			{

			?>

	<table border="0" align="right" width="90%">

		<TR>

			<td align="right" width="95%">

				<input type="hidden" name="art_id" value="<?php echo $article['id']?>" />

				<input type="submit" name="delete" value="Delete">

			</td>

		</tr>

	</table>

	<?php }?>

</form>	

	</td>

</tr>

<?php

			$i++;

		}

	}

	else

	{

		echo "<tr><td colspan='9' align='center'>No Article Found For submission</td></tr>";

	}

?>



					</table>

				</div>

			

			</td>

		</tr>

<?php

}

}	  

		else

		{

		echo "<tr><td align='center' colspan='6'>No Profile Found</td></tr>";

		}

		?>	  

		<tr ><td align='center' colspan='14'  class="heading">&nbsp;</td></tr>	  

		</table>





</table>

<?php

}

?>

<?php

require_once("footer.php");

?>

