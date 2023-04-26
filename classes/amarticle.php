<?php	
	session_start();require_once("config/config.php");
	require_once("classes/settings.class.php");
	require_once("classes/database.class.php");
	require_once("classes/amarticle.class.php");
	require_once("classes/pagination.class.php");
	require_once("classes/search.class.php");
	require_once("classes/pclzip.lib.php");
	require_once("classes/keyword.class.php");
	require_once("classes/en_decode.class.php");

	$endec=new encode_decode();
	$settings = new Settings();
	$settings->checkSession();
	$article = new Article();
	$database = new Database();
	$pg = new PSF_Pagination();
	$sc = new psf_Search();
	$archive = new PclZip($_FILES['importtextzip']['tmp_name']);
	$key=new keyword();

	$database->openDB();

	if (isset($_POST['process']))
	{
		$process = $_POST['process'];
	}
	else if (isset($_GET['process']))
	{
		$process = $_GET['process'];
	}
	else if($_REQUEST['amcat']>0)
	{
		$process='advsearch';
	}
	else
	{
		$process='manage';
	}

	if (isset($_GET["page"]))
	{
		$page = $_GET["page"];
	}
	else if  (isset($_POST["page"]))
	{
		$page = $_POST["page"];
	}
	else
	{
		$page = 1;
	}

	if (isset($_GET["search"]) && $_GET["search"]!="")	
	{		
		$search = $_GET["search"];	
	}
	else if  (isset($_POST["search"]))
	{
		$search = $_POST["search"];
	}
	else
	{
		$_GET['search'] = 1;
	}

	if(isset($_POST['submit']))
	{
		if(isset($_POST['articleform']) && $_POST['articleform']=="yes")
		{
			if($process=="new")
			{
				$article->insertArticle();
				$article->insertSnippet();
				$key->keywordgenerator();
				header("location: amarticle.php?process=manage&msg=Article has been added");
				exit;
			}
			elseif($process=="edit")
			{
				$article->updateArticle($_POST['id']);
				$article->updateArticleSnippet($_POST['id']);
				$key->updateKeyword($_POST['id']);
				header("location: amarticle.php?process=manage&msg=Article has been modified");
				exit;
			}
		}
	}

	if(isset($_POST['categoryform']) && $_POST['categoryform']=="yes")
	{
		if($process=="addcategory")
		{
			$sql1="select * from `".TABLE_PREFIX."am_categories` where category='".$_POST["newcategory"]."' and user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];
			$rs = $database->getDataSingleRow($sql1);
			if($rs!="")
			{
				$msg= "Category Already Exist";
				header("location: amarticle.php?process=addcategory&msg=Category Already Exist");
				exit;
			}
			else
			{
				$article->insertCategory();
				header("location: amarticle.php?process=managecategory&search=2&msg=category has been added");
				exit;
			}
		}
		elseif($process=="editcategory")
		{
			$article->updateCategory($_POST['id']);
			header("location: amarticle.php?process=managecategory&search=2&msg=Category has been modified");
			exit;
		}
	}

	if(isset($_POST['upload']) && $_POST['upload']=="yes")
	{
		// changes for task11 on 27 nov
		$uploaddata = true;
		if($_REQUEST['ncsb']=='yes')
		{
			$ncsb[]=$article->checkUploadedFile();
			$j=0;
			for($i=$j;$i<count($ncsb);$i++)
			{
				$_SESSION['ncsb_article_id']=$ncsb[$i];
			}
			header("location: amarticle.php?process=ncsb&msg=Article has been uploaded ");
		}
		else
		{
			$article->checkUploadedFile();
			header("location: amarticle.php?process=manage&msg=Article has been uploaded");
		}
		/*$uploaddata = true;
		$article->checkUploadedFile();
		header("location: amarticle.php?process=manage&msg=Article has been uploaded");*/
	}


	if(isset($_POST['yes']))
	{
		$article->deleteArticle($_GET['id']);
		$article->deleteArticleSnippet($_GET['id']);
		header("location: amarticle.php?process=manage&msg=Article has been Deleted");
	}
	
	if(isset($_POST['no']))
	{
		header("location: amarticle.php?process=manage&msg=Article has not been Deleted");
	}

	if(isset($_POST['yesbutton']))
	{
		$article->deleteCat($_GET['id']);
		header("location: amarticle.php?process=managecategory&search=2&msg=category has been Deleted");
	}

	if(isset($_POST['nobutton']))
	{
		header("location: amarticle.php?process=managecategory&search=2&msg=Category has not been Deleted");
	}

	if ($process=="edit")
	{
		$article_data = $article->getArticleById($_GET['id']);
	}
	elseif($process=="editcategory")
	{
		$category_data=$article->getCategoryById($_GET['id']);
	}
	elseif($process=="duplicate")
	{
		$article->insertDuplicateArticle($_GET['id']);
		header("location: amarticle.php?process=manage&msg=Article has been Duplicated");
		exit;
	}

	if(isset($_POST['btndelete']) && $_POST['btndelete']!="")
	{
		$s=implode(",",$_POST['chk']);
		$sql="delete from `".TABLE_PREFIX."am_article` where `id` in ($s)";
		$database->modify($sql);
	}

	// $$$$$$$$$$$$$$$$$$$$$$$$$$ coding for export atricle $$$$$$$$$$$$$$$$$$$$ 	
	if(isset($_POST['btnexport']) && $_POST['btnexport']!="")
	{
		$s=implode(",",$_POST['chk']);
		$i=0;
		foreach($_POST['chk'] as $id)
		{
			$sql="select * from `".TABLE_PREFIX."am_article` where id=".$id."";
			$article=$database->getDataSingleRow($sql);
			$title=str_replace(" ",'_',$article['title']);
			$article_file[$i]="article_export/".$title.'.txt';//.mt_rand(1,10000).
			$article_name[$i]=substr($article_file[$i],15);
			$fp = @fopen($article_file[$i],"wb");
			//fwrite($fp, "Content-type: text/plain\r\n");
			fwrite($fp,$article['title']);
			fwrite($fp,"\r\n");
			fwrite($fp,$article['author']);
			fwrite($fp,"\r\n\r\n");
			$x=str_replace('&acirc;','',$article['body']);
			//$x=preg_replace('/\n/'g,'',$x);
			//$x = preg_replace("/[\n]|[\r]|[\t]|[\f])/", "", $x);

			$x = preg_replace("/[\n]|[\r]/", " ", preg_replace("/\s{2,}/", "<br /><br />", $x));
			$x = nl2br($x);
			fwrite($fp,str_replace("<br />","\r\n",$x));
			fclose($fp);
			$i++;
		}
		// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$4
		// Test this class
		require_once("classes/article_export.class.php");
		$zipTest = new zipfile();
		//$zipTest->add_dir("images/");
		for($j=0;$j<count($article_file);$j++)
		{
			$zipTest->add_file($article_file[$j],$article_name[$j]);
		}
		// Return Zip File to Browser
		header("Content-type: application/octet-stream");
		header ("Content-disposition: attachment; filename=article_export.zip");
		echo $zipTest->file();
		// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$4
		}
		// $$$$$$$$$$$$$$$$$$$$$$$$$$ export $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
?>
<?php 
	require_once("header.php");
?>
<title><?php echo SITE_TITLE; ?></title>
<script language="javascript">

	// Javascript code will come here
	function trim(s)
	{
		var l=0;
		var r=s.length -1;
		while(l < s.length && s[l] == ' ')
		{
			l++;
		}
		while(r > l && s[r] == ' ')
		{
			r-=1;
		}
		return s.substring(l, r+1);
	}

	function saveX()
	{
		var elementX=document.frmsedit;
		if(elementX.txtTitle.value=='')
		{
			alert("Please provide the title");
			return false;
		}
		if(elementX.txtDescription.value=='')
		{
			alert("Please provide the Description");
			return false;
		}
		if(elementX.txtCode.value=='')
		{
			alert("Please provide the Code");
			return false;
		}
		elementX.action="sedit.php";
		elementX.submit();
	}

	function valdateform()
	{
		if(trim(document.getElementById("newcategory").value)=="")
		{
			alert("Please enter the category name");
			return false;
		}
		else
		{
			return true;
		}
	}

	function getCode (x)
	{
		// @@@@@@@@@@@ajax start here @@@@@@@@@@
		/*
		var xmlHttp;
		try
		{
			// Firefox, Opera 8.0+, Safari
			xmlHttp=new XMLHttpRequest();
		}
		catch (e)
		{
			// Internet Explorer
			try
			{
				xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e)
				{
					alert("Your browser does not support AJAX!");
					return false;
				}
			}
		}
		var url = "delcode.php";
		var params;
		var anum=/(^\d+$)|(^\d+\.\d+$)/;
		params="id="+document.getElementById("id").value;
		//alert(params);
		xmlHttp.open("POST", url, true);
		//Send the proper header information along with the request
		xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		//xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		//xmlHttp.setRequestHeader("Content-length", params.length);
		//xmlHttp.setRequestHeader("Connection", "close");
		xmlHttp.send(params);
		xmlHttp.onreadystatechange = function()
		{//Call a function when the state changes.
			if(xmlHttp.readyState == 4 && xmlHttp.status == 200) 
			{
				document.getElementById("myMsg").innerHTML=xmlHttp.responseText;
			}
		}
		*/

		if(x=='y')
		{
			document.frmdel.action="delcode.php";
			document.frmdel.submit();
		}
		else if(x=='n')
		{
			document.frmdel.action="amarticle.php?process=savedcode";
			document.frmdel.submit();
		}
	}

	function opencode(id)
	{
		openwindow= window.open ("showarticles.php?id="+id, "GETCODE","'status=0,scrollbars=1',width=650,height=500,resizable=1");
		openwindow.moveTo(50,50);
	}

	function showcode()
	{
		openwindow= window.open ("amarticleshowcode.php", "GETCODE","status=0,scrollbars=1,width=650,height=500,resizable=1");
		openwindow.moveTo(50,50);
	}

	function getcode(id)
	{
		openwindow= window.open ("amarticlegetcode.php?id="+id, "GETCODE","'status=0,scrollbars=1',width=650,height=500,resizable=1");
		openwindow.moveTo(50,50);
	}

	function getxcode(id)
	{
		openwindow= window.open ("amarticlexgetcode.php?id="+id, "GETCODE","'status=0,scrollbars=1',width=650,height=500,resizable=1");
		openwindow.moveTo(50,50);
	}

	function viewcode(cat)
	{
		openwindow= window.open ("amarticleviewcode.php?category_id="+cat, "GETCODE","'status=0,scrollbars=1',width=700,height=300,resizable=1");
		openwindow.moveTo(50,50);
	}

	function checkArticle()
	{
		var flag=true;
		var msg="";
		if(document.article.source.value==-1)
		{
			msg+="Please Select Source\n";
		}
		if(document.article.category.value==-1)
		{
			msg+="Please Select Category\n";
		}
		if(document.article.title.value=="")
		{
			msg+="Please Enter Title\n";
		}
		if(document.article.author.value=="")
		{
			msg+="Please Enter Author Name\n";
		}
		if(document.article.summary.value=="")
		{
			msg+="Please Enter Summary\n";
		}
		if(document.article.body.value=="")
		{
			file:///home/kil3/lampstack-5.5/apache2/htdocs/cpanel/classes/amarticle.class.php
			msg+="Please Enter Body\n";
		}
		if(document.article.status.value=="")
		{
			msg+="Please Enter Status\n";
		}
		if(msg.length>0)
		{
			alert(msg);
			flag=false;
		}
		return flag;
	}

	function chkuploadForm(frm)
	{
		var mss = "";
		if(frm.source.value==-1)
		{
			mss+="Please Select Source.\n";
		}
		if (frm.category.value==-1)
		{
			mss += "Please Select Category.\n";
		}
		if (frm.author.value=="")
		{
			mss += "Please Enter Author.\n";
		}
		if (frm.importtextzip.value=="")
		{
			mss += "Please enter any file to upload.\n";
		}
		if (mss.length>0)
		{
			alert(mss);
			return false;
		}
		else
		{
			return true;
		}
	}

	function dele()
	{
		flags=false;
		var element;
		var numberOfControls = document.myform.length;
		for (Index = 0; Index < numberOfControls; Index++)
		{
			element = document.myform[Index];
			if (element.type == "checkbox")
			{
				if (element.checked == true)
				{
					flags=true;
				}
			}
		}
		if (flags==false)
		{
			alert("Please select at least one row.");
			return false;
		}
		else
		{
			//confirm("Are you sure you want to delete Article");
			if (confirm("Are you sure you want to delete Article")==true)
			{
				document.myform.btndelete.value="1";
				document.myform.submit();
			}
			else
			{
				return false;
			}
		}
	}

	// function for export article
	function export_art()
	{
		flags=false;
		var element;
		var numberOfControls = document.myform.length;
		for (Index = 0; Index < numberOfControls; Index++)
		{
			element = document.myform[Index];
			if (element.type == "checkbox")
			{
				if (element.checked == true)
				{
					flags=true;
				}
			}
		}
		if (flags==false)
		{
			alert("Please select at least one row to export.");
			return false;
		}
		else
		{
			document.myform.btnexport.value="1";
			document.myform.submit();
			return false;
		}
	}
	// end of export

	function chk(frm)
	{
		// flags=false;
		// if (flags==false) { return false; }	
		if (confirm("Are you sure you want to delete Article")==true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	<!-- check confirmation article to delete or not  added at 04_nov  -->
	function chk_art(id)
	{
		if (confirm("Are you sure you want to delete Article")==true)
		{
			//return true;
			window.location.href='?process=confirmdelete&id='+id;
		}
		else
		{
			return false;
		}
	}
	<!-- end of confomatioj  -->

	function checkUncheckAll(theElement)
	{
		var tForm = theElement.form, z = 0;
		for(z=0;z<tForm.length;z++)
		{
			if(tForm[z].type == 'checkbox' && tForm[z].name != 'checkall')
			{
				tForm[z].checked = theElement.checked;
				//if(tForm[z].checked==true)alert(tForm[z].value);
			}
		}
	}

	function test(theElement)
	{
		var tForm = theElement.form, z = 0;
		var ch=document.getElementById("chkall").checked;
		//alert("??");
		//alert(ch);
		for(z=0;z<tForm.length;z++)
		{
			// tForm[z].checked = theElement.checked;
			if(tForm[z].checked==true && ch==true)
				document.getElementById("chkall").checked=false;
		}
	}

	function selcat()
	{
		document.cat.submit();
	}
</script>

<?php require_once("top.php"); ?>
<?php require_once("left.php"); ?>

<!-- html code will come here -->

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left">
			<?php
				$home = '<a class="general" href="index.php">Home</a>';
				if ($process=="manage" || $process=="advsearch")
				{
					$manage = " >> Manage Article";
				}
				elseif ($process=="new" || $process=="edit" || $process=="upload" || $process == "confirmdelete")
				{
					$manage = ' >> <a class="general" href="amarticle.php">Manage Article</a> ';
				}

				if($process=="savedcode")
				{
					$editprocess=" >>Saved Selection";
				}

				if($process=="sedit")
				{
					$editprocess=" >>Edit Saved Selection";
				}

				if ($process=="new")
				{
					$editprocess = ' >> New Article';
				}
				else if ($process=="edit")
				{
					$editprocess = ' >> Edit Article';
				}
				else if ($process == "managecategory")
				{
					$manage = ' >> Manage Category';
				}
				else if($process=="addcategory" || $process=="editcategory")
				{
					$manage = ' >> <a class="general" href="amarticle.php?process=managecategory">Manage Category</a> ';
				}

				if ($process == "addcategory")
				{
					$editprocess = ' >> New Category';
				}
				else if ($process == "editcategory")
				{
					$editprocess = ' >> Edit Category';
				}
				else if ($process == "getcode")
				{
					$editprocess = ' >> View Article';
				}
				else if ($process == "upload")
				{
					$editprocess = ' >> Upload ZIP';
				}
				else if ($process == "confirmdelete")
				{
					$editprocess = ' >> Delete Article';
				}
				else if ($process == "deletecategory")
				{
					$editprocess = ' >> Delete Category';
				}
				echo $home.$manage.$editprocess;

				if($_GET['search']==2)
				{
					$process="managecategory";
				}
			?>
			<br>
		</td>

		<td  align="center">
			<?php //echo $msg ?>
		</td>

	</tr>
</table>
<?php  	
	if ($process=="new" || $process == "edit")
	{
?>
<form action="amarticle.php" name="article" method="POST" onsubmit="return checkArticle();">
	<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td>
				<br>
			</TD>
		</tr>
		<tr>
			<td align="center" class="heading" colspan="2" >
				<?php 
					if($process=="new")
						echo "New Article";
					else echo "Edit Article";
				?>
			</td>
		</tr>
		<tr>
			<td>
				<br>
			</td>
		</tr>
		<tr>
			<td align="right">Source&nbsp;</td>
			<td align="left">
				<select name="source" id="source" >
				<option value="-1"><--Select Source--></option>
				<OPTION value="PLR" <?php if($article_data['source']=="PLR") echo "selected"; ?>>PLR</OPTION>
				<OPTION value="Free Reprint rights" <?php if($article_data['source']=="Free Reprint rights") echo "selected"; ?>>Free Reprint rights</OPTION>
				<OPTION value="Own" <?php if($article_data['source']=="Own") echo "selected"; ?>>Own</OPTION>
				<OPTION value="Partner" <?php if($article_data['source']=="Partner") echo "selected"; ?>>Partner</OPTION>
			</select>
			
			</TD>
				</TR>
				<tr>
				<TD><br></TD>
				</tr><TR>
				<TD align="right">Category&nbsp;</TD>
				<TD>
				<!--<SELECT name="category" id="category"><?php //echo $article -> categorySelectBox(); ?>	</SELECT>	 -->
				<!-- chnages for task109  -->
				<?php
					$sql="select id, category from `".TABLE_PREFIX."am_categories` where status='Active' and user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];
					$cat_rs=$database->getRS($sql);
					if ($cat_rs)
					{
				?>
					<SELECT name="category" id="category">
						<?php echo $article -> categorySelectBox(); ?>
					</SELECT>
				<?php
					}
					else
					{
						echo '<input type="hidden" name="category" value="-1" />';
						echo 'Please Create Category Before create article';
					}
				?>
				</TD>
			</TR>

			<tr><TD><br></TD></tr>
			<TR>
				<TD align="right">Title&nbsp;</TD>
				<TD>
					<input  type="text" name="title"  id="title" size="95" maxlength="150" value="<?php echo $article_data['title']; ?>" />
					<br />
					(insert character width less then 150)
				</TD>
			</TR>

			<tr><TD><br></TD></tr>

			<TR>
				<TD align="right">Author&nbsp;</TD>
				<TD>
					<input  type="text" name="author" id="author" size="95" value="<?php echo $article_data['author']; ?>"></TD>
				</TR>
			<tr>

			<TD><br></TD>
		</tr>

		<TR>
			<TD align="right">Summary&nbsp;</TD>
			<TD>
				<textarea name="summary" id="summary" rows="3" cols="93" >
					<?php echo $article_data['summary']; ?>
				</textarea>
			</TD>
		</TR>

		<tr><TD><br></TD></tr>
		<TR>
			<TD align="right">Body&nbsp;</TD>
			<TD>
				<textarea name="body" id="body" rows="10" cols="93">
					<?php echo $article_data['body']; ?>
				</textarea>
			</TD>
		</TR>
		
		<tr><TD><br></TD></tr>
		<TR>
			<TD align="right">Status&nbsp;</TD>
			<TD align="left">
				<input type="radio" name="status" value="Active" <?php if($article_data['status']=="Active") echo "checked"; else ?> checked>
					&nbsp;Active&nbsp;
				<input type="radio" name="status" value="Inactive" <?php if($article_data['status']=="Inactive") echo "checked"; ?>>
					&nbsp;InActive
			</TD>
		</TR>

		<tr><TD><br></TD></tr>

		<tr>
			<TD align="center" class="heading" colspan="2"><input type="submit" name="submit" value="Save"></TD>
		</tr>

		<tr><TD><br></TD></tr>
		<input type="hidden" name="process" value="<?php echo $process;?>">
		<input type="hidden" name="articleform" value="yes">
		<input type="hidden" name="id" value="<?php echo $article_data['id']; ?>">
	</table>
</form>

<?php	
	}
	else if($process=="manage" || $process=="advsearch")
	{
?>
	<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<TD valign = "top" align="center" class="heading">
			<a class="menu" href = "?process=new">Create New Article</a>  |  <a  class="menu" href = "?process=managecategory&search=2">Manage Category</a>  |  <a  class="menu" href = "?process=upload">Upload ZIP</a>  |  <a  class="menu" href = "?process=savedcode">Saved Selection</a>
			</TD>
		</tr>
		<br>	
			<?php	
				if($process=="manage")
				{
					// on 03_dec task116
					// $sql = "select count(*) from `".TABLE_PREFIX."am_article` where user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];
					$sql = "select count(*) from `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b where a.id=b.category_id and a.status='Active'   and b.user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];
				}
				elseif($process=="advsearch")
				{
					/*$sql="SELECT a.category,a.id as cat_id,b.* FROM `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b WHERE a.id=b.category_id and a.status='Active' and b.user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid']." ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;*/
					// on 03_dec task116
					$sql = "select count(*) from `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b where a.id=b.category_id and a.status='Active' and b.category_id='".$_REQUEST['amcat']."'  and b.user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];
				}

				$totalrecords = $database->getDataSingleRecord($sql);
				if ($totalrecords>0)
				{
					$pg->setPagination($totalrecords);
					$order_sql = $sc->getOrderSql(array("id","category","title","summary","source","status"),"id");
					/*$sql= "SELECT c.*, count(a.id) as noofads  from `".TABLE_PREFIX."campaign` c	LEFT JOIN `".TABLE_PREFIX."ad` a ON a.campaign_id = c.id	Group BY c.id	".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;	$campaign_rs = $ms_db->getRS($sql);	*/
				?>
		<tr>
			<td colspan="12">
			<?php $pg->showPagination1(); ?>
			</td>
		</tr>
	</table>
	<br>

	<div class="message">
		<?php echo $_GET['msg']; ?>
	</div>
	
	<table border="0" align="center">
		<tr>
			<TD align="right">
				<input type="button" title="show code" style="cursor:pointer" onclick="showcode()" value="Advanced Content Display Options">
			</TD>
		</tr>
	</table>

	<table border="0" align="center" width="90%">
		<TR>
			<td align="right" width="95%">
				<input type="button" name="delete" value="Delete" onclick="return dele();">&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="export" value="Export" onclick="return export_art();">
			</td>
		</tr>
	</table>

	<table width="1000px"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
		<th><a title = "Sort" class = "menu" href="?sort=id">ID</a></th>
		<th>
			<form action="amarticle.php" method="get" name="cat">
				<select name="amcat" id="amcat" style="background-color:#A2A2A2" onchange="selcat();" >
					<?php $article -> SelectBox(); ?>
				</select>
			</form>
		</th>
		<form name="myform" action="" method="post" onsubmit="return chk(this)">
			<input type="hidden" name="btndelete" value="" />
			<input type="hidden" name="btnexport" value="" />
			<th><a title = "Sort" class = "menu" href="?sort=title">Title</a></th>
			<th><a title = "Sort" class = "menu" href="?sort=summary">Summary</a></th>
			<th><a title = "Sort" class = "menu" href="?sort=source">Source</a></th>
			<th><a title = "Sort" class = "menu" href="?sort=status">Status</a></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th><input name='chkall' type='checkbox' value='chkall' id="chkall" onClick='checkUncheckAll(this)'></td></th>

			<?php
					if($process=="manage")
					{
						echo $article->manageArticle();
					}
					else
					{
						echo $article->selectCategory();
					}
				}
				else
				{
					$totalrecords = 0;
					$man_rs = false;
				}
			?>
		</form>
	</table>
	<br>
<?php
	}
	elseif($process=="managecategory")
	{
?>	
	<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<TD valign = "top" align="center" class="heading">
				<a  class="menu" href = "?process=addcategory">Create New Category</a>  |  <a  class="menu" href = "?process=manage&search=1">Manage Article</a>
			</TD>
		</tr>
		<br>
		<?php
			$sql = "select count(*) from `".TABLE_PREFIX."am_categories` where user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];	$totalrecords = $database->getDataSingleRecord($sql);
			if ($totalrecords>0)
			{
				$pg->setPagination($totalrecords);
				$order_sql = $sc->getOrderSql(array("id","category","status"),"id");
		?>
		<tr>
			<td colspan="12">
				<?php	$pg->showPagination();	?>
			</td>
		</tr>
		<tr>
			<td align="center">
				<b><?php echo ucwords($_GET["msg"]);?></b>
				<br/>
			</td>
		</tr>
	</table>
	<br>
	<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
		<tr  class="tableheading">
			<th><a title = "Sort" class = "menu" href="?process=managecategory&search=2&sort=id">ID</a></th>
			<th><a title = "Sort" class = "menu" href="?process=managecategory&search=2&sort=category">Category Name</a></th>
			<th><a title = "Sort" class = "menu" href="?process=managecategory&search=2&sort=status">Status</a></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>	
		<?php echo $article -> manageCategory(); ?>
		<?php
			}
			else
			{
				$totalrecords = 0;
				$man_rs = false;
			}
		?>
	</table>
	<br>
<?php
	}
	elseif($process=="addcategory" || $process == "editcategory")
	{
?>
	<br>
		<form action="" method="POST" name="addcategory" id="addcategory" onsubmit="return valdateform();">
		<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
			<tr>
				<TD align="center" class="heading" colspan="2" >
				<?php 
					if($process=="addcategory") 
					echo "New Category"; 
					else echo "Edit Category";
				?>
				</TD>
			</tr>
			<div class="message">
				<?php echo $_GET['msg']; ?>
			</div>
			<tr><TD><br></TD></tr>
			<TR>
				<TD align="right">Category Name&nbsp;</TD>
				<TD align="left"><input type="text" name="newcategory" id="newcategory" size="20" value="<?php echo $category_data['category'];?>"></TD>
			</TR>
			<tr><TD><br></TD></tr>
			<TR>
				<TD align="right">Status&nbsp;</TD>
				<TD align="left">
					<input type="radio" name="status" value="Active" <?php if($category_data['status']=="Active") echo "checked"; else ?> checked>&nbsp;Active&nbsp;
					<input type="radio" name="status" value="Inactive" <?php if($category_data['status']=="Inactive") echo "checked"; ?>>&nbsp;InActive
				</TD>
			</TR>
			<tr><TD><br></TD></tr>
			<tr>
				<TD align="center" class="heading" colspan="2" ><input type="submit" name="save" value="Save"></TD>
			</tr>
			<!-- <input type="hidden" name="process" value="<?php //echo $process;?>"> -->
			<input type="hidden" name="categoryform" value="yes">
			<input type="hidden" name="id" value="<?php echo $category_data['id']; ?>">
		</table>
		<br>
		</form>
<?php
	}
	elseif($process=="getcode")
	{
?>
	<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
	<tr>
		<TD valign = "top" align="center" class="heading">
			<a  class="menu" href = "?process=new">Create New Article</a>  |  <a  class="menu" href = "?process=manage">Manage Article</a>  |  <a  class="menu" href = "?process=upload">Upload ZIP</a>
		</TD>
	</tr>
	</table>

	<br>

	<table class="amtable">
		<?php $article->viewArticle($_GET['id']);?>
	</table>

	<br>

<?php
	}
	else if($process=="upload")
	{
?>
	<form name="uploadZIP" method="POST" action="amarticle.php" onSubmit="return chkuploadForm(this)" enctype="multipart/form-data">
		<input type="hidden" name="ncsb" value="<?php echo $_REQUEST['ncsb']?>" />
		<table width="90%"  border="0" cellspacing="0" cellpadding="0" class="inputform">
			<tr>
				<td align="left" width="40%" class="heading" colspan="2">Upload ZIP</td>
			</tr>
			<br>
			<tr>
				<TD align="right" width="50%">Source</TD>
				<td align="left" width="50%">
					<select name="source" id="source" >
					<?php if($_REQUEST['source']=='PLR'){ ?>
						<option value="-1"><--Select Source--></option>	<OPTION value="PLR" selected="selected">PLR</OPTION>
						<OPTION value="Free Reprint rights">Free Reprint rights</OPTION>
						<OPTION value="Own">Own</OPTION>
						<OPTION value="Partner">Partner</OPTION>
					<?php  }elseif($_REQUEST['source']=='Own'){?>
						<option value="-1"><--Select Source--></option>
						<OPTION value="PLR">PLR</OPTION>
						<OPTION value="Free Reprint rights">Free Reprint rights</OPTION>
						<OPTION value="Own" selected="selected">Own</OPTION>
						<OPTION value="Partner">Partner</OPTION>
					<?php  }elseif($_REQUEST['source']=='Free Reprint rights'){?>
						<option value="-1"><--Select Source--></option>
						<OPTION value="PLR">PLR</OPTION>
						<OPTION value="Free Reprint rights" selected="selected">Free Reprint rights</OPTION>
						<OPTION value="Own">Own</OPTION>
						<OPTION value="Partner">Partner</OPTION>
					<?php   }elseif($_REQUEST['source']=='Partner'){?>
						<option value="-1"><--Select Source--></option>
						<OPTION value="PLR">PLR</OPTION>
						<OPTION value="Free Reprint rights">Free Reprint rights</OPTION>
						<OPTION value="Own">Own</OPTION>
						<OPTION value="Partner" selected="selected">Partner</OPTION>
					<?php   }else{?>
						<option value="-1"><--Select Source--></option>
						<OPTION value="PLR">PLR</OPTION>
						<OPTION value="Free Reprint rights">Free Reprint rights</OPTION>
						<OPTION value="Own">Own</OPTION>
						<OPTION value="Partner">Partner</OPTION>
					<?php } ?>
					</select>
				</TD>
			</tr>

			<tr>
				<TD align="right" width="50%">Category</TD>
				<TD align="left" width="50%">
				<SELECT name="category" id="category" style="width:140px;">
					<?php echo $article -> categorySelectBox(); ?>
				</SELECT>
				</TD>
			</tr>

			<!-- add for task 109 at 11_nov -->
			<TR>
				<TD align="right">Author&nbsp;</TD>
				<TD>
					<input  type="text" name="author" id="author" size="95" value="<?php echo $_REQUEST['author']; ?>">
				</TD>
			</TR>

			<tr>
				<TD align="right">Upload ZIP:</TD>
				<TD align="left">
					<input type="file" name="importtextzip" id="importtextzip" size="45">
				</TD>
			</tr>

			<TR>
				<TD align="right">Status&nbsp;</TD>
				<TD align="left">
					<input type="radio" name="status" value="Active" checked="true">&nbsp;Active&nbsp;
					<input type="radio" name="status" value="Inactive">&nbsp;InActive
				</TD>
			</TR>

			<tr>
				<td colspan="2" align="center" class="heading">
				<div align="center">
					<input type="submit" name="Submit" value="Save">
				</div>

				<input type="hidden" name="process" value="<?php echo $process ?>">
				<input type="hidden" name="upload" value="yes">
			</td>
			</tr>
		</table>
	</form>
<?php
	}
	elseif($process=="savedcode")
	{
		$subprocess=isset($_GET['subpro'])?$_GET['subpro']:'';
?>	<table width="1000px"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
		<tr>
			<TD valign = "top" align="center" class="heading">
				<a  class="menu" href = "?process=new">Create New Article</a>  |  <a  class="menu" href = "?process=managecategory&search=2">Manage Category</a>  |  <a  class="menu" href = "?process=upload">Upload ZIP</a>  |  <a  class="menu" href = "?process=savedcode">Saved Selection</a>
			</TD>
		</tr>
	</table>

	<br/>

	<div id="myMsg">
		<p align="center"><?php echo ucwords($_GET["msg"]);?></p>
	</div>

	<br/>
		<?php 
			if($subprocess=="confirmdelete") {
				$cid=isset($_GET['id'])?$_GET['id']:(isset($_POST['id'])?$_POST['id']:'');
		?>
			<form name="frmdel" action="" method="POST">
				<table class="messagebox" align="center" width="50%" height="100%">
				<tr><TD><br></TD></tr>
				<tr><TD><br></TD></tr>
				<tr><TD><br></TD></tr>
				<tr><TD><br></TD></tr>
				<tr><TD><br></TD></tr>
				<TR>
					<TD align="center">Are you sure you want to delete the saved selection?</TD>
					<input type="hidden" id="id" name="id" value="<?php echo $cid;?>" />
				</TR>
				<TR>
				<TD align="center">
					<input type="button" name="yes" value="Yes" onclick="getCode('y');" >&nbsp;
					<input type="button" name="no" value="No" onclick="getCode('n')">
				</TD>
				</TR>
				<tr><TD><br></TD></tr>
				<tr><TD><br></TD></tr>
				<tr><TD><br></TD></tr>
				<tr><TD><br></TD></tr>
				<tr><TD><br></TD></tr>
				</table>
				<br>
			</form>
		<?php
			}
		?>
		<?php	
			$sql = "select count(*) from `".TABLE_PREFIX."am_savedcode` where user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];
			$totalrecords = $database->getDataSingleRecord($sql);
			if ($totalrecords>0)
			{
				$pg->setPagination($totalrecords);
				$order_sql = $sc->getOrderSql(array("id","dispoption"),"id");
				$pg->showPagination1();
		?>
		<table width="1000px" border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
			<tr>
			<th><a title = "Sort" class = "menu" href="?sort=id">ID</a></th>
			<form name="myform" action="" method="post" onsubmit="return chk(this)">
			<input type="hidden" name="btndelete" value="" />
			<th><a title = "Sort" class = "menu" href="?sort=title">Title</a></th>
			<th><a title = "Sort" class = "menu" href="?sort=title">Description</a></th>
			<!--th><a title = "Sort" class = "menu" href="?sort=summary">Code</a></th>
			<th><a title = "Sort" class = "menu" href="?sort=status">Status</a></th-->
			<th></th>
			<th></th>
			<th></th>
			<!--th></th>	<th><input name='chkall' type='checkbox' value='chkall' id="chkall" onClick='checkUncheckAll(this)'></th-->
			</tr>
		<?php 
				echo $article->manageCode();
			}else{
				$totalrecords = 0;
				$man_rs = false;
			}
		?>
		</table>
	</form>

<?php 
	}
	if ($process=="sedit")
	{
		$xId=isset($_GET['id'])?$_GET['id']:(isset($_POST['id'])?$_POST['id']:'');
		$article_data = $article->getCodeById($_GET['id']);
?>
		<table width="1000px"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
		<tr>
			<TD valign = "top" align="center" class="heading">
				<a  class="menu" href = "?process=new">Create New Article</a>  |  <a  class="menu" href = "?process=managecategory&search=2">Manage Category</a>  |  <a  class="menu" href = "?process=upload">Upload ZIP</a>  |  <a  class="menu" href = "?process=savedcode">Saved Selection</a>
			</TD>
		</tr>
		</table>

		<form name="frmsedit" action="sedit.php" method="post">	<table width="1000px"  border="0" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<td>Title</td>
					<td><input type="text" name="txtTitle" value="<?php echo $article_data['name']?>" /></td>
				</tr>

				<tr>
					<td>Description</td>
					<td>
					<textarea id="txtDescription" name="txtDescription" rows="5" cols="80">
						<?php echo stripslashes($article_data['description']);?>
					</textarea>
					</td>
				</tr>

				<tr>
					<td>Code</td>
					<td>
						<textarea id="txtCode" name="txtCode" rows="20" cols="80">
							<?php echo html_entity_decode($article_data['code']);?>
						</textarea>
					</td>
				</tr>

				<tr>
					<td><input type="hidden" name="id" value="<?php echo $xId; ?>" /></td>
					<td><input type="button" style="cursor:pointer" name="cmdSub" value="Submit" onclick="saveX()"></td>
				</tr>
			</table>
		</form>
<?php	
	}
?>
<?php
	if($process=="confirmdelete")
	{
?>
	<form name="delete" action="" method="POST">
		<table class="messagebox" align="center" width="50%" height="100%">
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		
		<TR>
			<TD align="center">Are you sure you want to delete the article</TD>
		</TR>

		<TR>
			<TD align="center"><input type="submit" name="yes" value="Yes">&nbsp;<input type="submit" name="no" value="No"></TD>
		</TR>
	
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		</table>

		<br>

	</form>

<?php
	}
?>
<?php	
	if($process=="deletecategory")
	{
?>
	<form name="delete" action="" method="POST">
		<table class="messagebox" align="center" width="50%" height="70%">
			<tr><TD><br></TD></tr>
			<tr><TD><br></TD></tr>
			<tr><TD><br></TD></tr>
			<tr><TD><br></TD></tr>
			<tr><TD><br></TD></tr>

			<TR>
				<TD align="center">Are you sure you want to delete the category?<br/><br/>Articles of this category is also removed if present.</TD>
			</TR>

			<TR>
				<TD align="center"><input type="submit" name="yesbutton" value="Yes">&nbsp;<input type="submit" name="nobutton" value="No"></TD>
			</TR>

			<tr><TD><br></TD></tr>
			<tr><TD><br></TD></tr>
			<tr><TD><br></TD></tr>
			<tr><TD><br></TD></tr>
			<tr><TD><br></TD></tr>
		</table><br>
	</form>
<?php
	}
	if($process=="ncsb")
	{
?>
	<table class="messagebox" align="center" width="50%" height="70%">
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		
		<TR>
			<TD align="center">
				<?php echo $_REQUEST['msg']?>
			</TD>
		</TR>

		<TR>
			<TD align="center">
				<input type="button" name="yesbutton" value="Close" onclick="javascript:window.close();">
			</TD>
		</TR>

		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		<tr><TD><br></TD></tr>
		</table>

		<br>
<?php }	?>
<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>