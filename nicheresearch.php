<?php
session_start();
//PHP code will come here
require_once("config/config.php");
require_once("classes/settings.class.php");
require_once("classes/database.class.php");
require_once("classes/amarticle.class.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");
require_once("classes/pclzip.lib.php");
require_once("classes/keyword.class.php");
require_once("classes/en_decode.class.php");
require_once("classes/niche.class.php");

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
$niche=new Niche();

if (isset($_POST['process'])){$process = $_POST['process'];}
else if (isset($_GET['process'])){$process = $_GET['process'];}
else if($_REQUEST['amcat']>0)$process='advsearch';
 else $process='manage';
 // else
 // {
 // 	$process='manage';
 // }
 if (isset($_GET["page"])){$page = $_GET["page"];}
 else if  (isset($_POST["page"])){$page = $_POST["page"];}
 else{$page = 1;}
 if (isset($_GET["search"]) && $_GET["search"]!=""){$search = $_GET["search"];}
 else if  (isset($_POST["search"])){$search = $_POST["search"];}
 else{$_GET['search'] = 1;}if(isset($_POST['submit'])){
 if(isset($_POST['articleform']) && $_POST['articleform']=="yes"){
 if($process=="new"){$article->insertArticle();
 $article->insertSnippet();
 $key->keywordgenerator();
 header("location: amarticle.php?process=manage&msg=Article has been added");
 exit;
 }
 elseif($process=="edit"){
 $article->updateArticle($_POST['id']);
 $article->updateArticleSnippet($_POST['id']);
 $key->updateKeyword($_POST['id']);
 header("location: amarticle.php?process=manage&msg=Article has been modified");exit;}}}
 if(isset($_POST['categoryform']) && $_POST['categoryform']=="yes"){
 if($process=="addcategory"){$sql1="select * from `".TABLE_PREFIX."am_categories` where category='".$_POST["newcategory"]."' and user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];
 $rs = $database->getDataSingleRow($sql1);
 if($rs!=""){$msg= "Category Already Exist";header("location: amarticle.php?process=addcategory&msg=Category Already Exist");exit;}
 else{$article->insertCategory();
 header("location: amarticle.php?process=managecategory&search=2&msg=category has been added");
 exit;}}
 elseif($process=="editcategory"){$article->updateCategory($_POST['id']);header("location: amarticle.php?process=managecategory&search=2&msg=Category has been modified");exit;}}if(isset($_POST['upload']) && $_POST['upload']=="yes"){
 // changes for task11 on 27 nov
 $uploaddata = true;
 if($_REQUEST['ncsb']=='yes'){$ncsb[]=$article->checkUploadedFile();
 $j=0;for($i=$j;$i<count($ncsb);$i++) {	
 $_SESSION['ncsb_article_id']=$ncsb[$i];}
 header("location: amarticle.php?process=ncsb&msg=Article has been uploaded ");}
 else{$article->checkUploadedFile();
 header("location: amarticle.php?process=manage&msg=Article has been uploaded");}
 /*$uploaddata = true;$article->checkUploadedFile();header("location: amarticle.php?process=manage&msg=Article has been uploaded");*/}
 if(isset($_POST['yes'])){
 $article->deleteArticle($_GET['id']);
 $article->deleteArticleSnippet($_GET['id']);
 header("location: amarticle.php?process=manage&msg=Article has been Deleted");}
 if(isset($_POST['no'])){header("location: amarticle.php?process=manage&msg=Article has not been Deleted");}
 if(isset($_POST['yesbutton'])){
 $article->deleteCat($_GET['id']);
 header("location: amarticle.php?process=managecategory&search=2&msg=category has been Deleted");}
 if(isset($_POST['nobutton'])){
 header("location: amarticle.php?process=managecategory&search=2&msg=Category has not been Deleted");}
 if ($process=="edit"){$article_data = $article->getArticleById($_GET['id']);}
 elseif($process=="editcategory"){$category_data=$article->getCategoryById($_GET['id']);}
 elseif($process=="duplicate"){$article->insertDuplicateArticle($_GET['id']);header("location: amarticle.php?process=manage&msg=Article has been Duplicated");exit;}
 if(isset($_POST['btndelete']) && $_POST['btndelete']!=""){
 $s=implode(",",$_POST['chk']);
 $sql="delete from `".TABLE_PREFIX."am_article` where `id` in ($s)";$database->modify($sql);}?>
 <?php require_once("header.php"); ?>
 <title><?php echo SITE_TITLE; ?></title>
 <script language="JavaScript" type="text/javascript">
 // Javascript code will come here
 function showcode()
 	{
		//openwindow= window.open ("amarticleshowcode.php", "GETCODE","status=0,scrollbars=1,width=650,height=500,resizable=1");openwindow.moveTo(50,50);
		window.location.href="nicheresearch.php?process=Random";
	}
	
	function exportKwd()
	{
		/*// @@@@@@@@@@@ajax start here @@@@@@@@@@
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

					var url = "genKwd.php";
					var params;
					var anum=/(^\d+$)|(^\d+\.\d+$)/;
					params="kwd="+document.getElementById("xkwd").value;
					//alert(params);
					xmlHttp.open("POST", url, true);
					
					//Send the proper header information along with the request
					xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8"); 
					//xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xmlHttp.setRequestHeader("Content-length", params.length);
					xmlHttp.setRequestHeader("Connection", "close");
					xmlHttp.send(params);
					xmlHttp.onreadystatechange = function() {//Call a function when the state changes.
						if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
							//alert(xmlHttp.responseText);
							//alert("dfs");
							/*if(xType!='save'){
								document.getElementById("showcode").value=xmlHttp.responseText;	
							}	
							else if(xType=='save'){
								document.getElementById("myMsg").innerHTML=xmlHttp.responseText;
								document.getElementById("saveme").style.display="none";
							}
							document.getElementById("xshow").style.display="inline";	
						
						}
					}*/
					window.open("getKwd.php?kwd="+document.getElementById("xkwd").value);
	}
	
 </script>
 <?php require_once("top.php"); ?>
 <?php require_once("left.php"); ?>
 <!-- html code will come here -->
 <table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr><td align="left">
 <?php
 $home = '<a class="general" href="index.php">Home</a>';
 if ($process=="manage"){$manage = " >> Main";}
 elseif ($process=="Top" || $process=="Random" || $process=="search"){
 $manage = ' >> <a class="general" href="nicheresearch.php">Main</a> ';}
 if ($process=="Top"){$editprocess = ' >> Top 1000 Niches';}
 else if ($process=="Random"){$editprocess = ' >> Random Idea';}
 else if ($process=="search"){$editprocess = ' >> Releated Search';}
 echo 	$home.$manage.$editprocess;if($_GET['search']==2){$process="managecategory";}?><br />
 </td><td  align="center"> <?php //echo $msg ?></td></tr></table>
 <?php
 	switch ($process)
	{
		case "manage":
 ?>
 <table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
 <tr>
 	<td valign = "top" align="center" class="heading"> <a  class="menu" href = "#">Main</a>  |  <a  class="menu" href = "?process=Top">Top 1000 Niches</a>  |  <a  class="menu" href = "?process=Random">Random Idea</a></td></tr>
 </table>
 <?php
 		break;
		case "Top":
 ?>
  <table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
 <tr>
 	<td valign = "top" align="center" class="heading"> <a  class="menu" href = "?process=manage">Main</a>  |  <a  class="menu" href = "#.">Top 1000 Niches</a>  |  <a  class="menu" href = "?process=Random">Random Idea</a></td></tr>
 </table>
 <?php
 		break;
		case "Random":
 ?>
  <table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
 <tr>
 	<td valign = "top" align="center" class="heading"> <a  class="menu" href = "?process=manage">Main</a>  |  <a  class="menu" href = "?process=Top">Top 1000 Niches</a>  |  <a  class="menu" href = "#">Random Idea</a></td></tr>
 </table>
 <?php 
 		break;
		default:
 ?>
  <table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
 <tr>
 	<td valign = "top" align="center" class="heading"> <a  class="menu" href = "?process=manage">Main</a>  |  <a  class="menu" href = "?process=Top">Top 1000 Niches</a>  |  <a  class="menu" href = "?process=Random">Random Idea</a></td></tr>
 </table>
 <?php
 		break;
 		
 	}
 ?>
 <div class="message"><?php echo $_GET['msg']; ?></div>
  <form name="frmSearch" action="?process=search" method="post">
 <?php
 	if($process=="manage")
	{
 ?>

 <table border="0" align="center">
 <tr><td align="center" colspan="2" ><input type="button" title="show code" onclick="showcode()" style="cursor:pointer" value="Get a Random Niche Suggestion" />	  </td>	</tr>
  <tr>
  	<td align="right" ><input type="text" name="txtNiche"  value="" width="300px" />	  </td>
	<td align="right" ><input type="submit" name="cmdNiche" value="Search Related Niches" />	  </td>	
  </tr>
 </table>

 <?php 
 	}
	else if($process=="Top")
	{
?>
	<table align="center" width="80%">
		<!--tr>
			<td colspan="2" align="right"><input type="button" value="Export" name="cmdExp" onclick="" />  </td>
		</tr-->	
		<tr>
			<th>S.No</th>
			<th> Top Niches </th>
			<th>  </th>
		</tr>	
<?php 
		/*$sql = "select count(*) from `".TABLE_PREFIX."niche_topniche` ";
		$totalrecords = $database->getDataSingleRecord($sql);
		if ($totalrecords>0){
		$pg->setPagination($totalrecords);
		//$order_sql = $sc->getOrderSql(array("id","category","status"),"id");*/?>
		
<?php		
		echo $niche -> TopNiche();
?>
		
		</table>
<?php	
 		// } else{$totalrecords = 0;$man_rs = false;}
	}
 else if($process=="Random")
	{
?>

	<table align="center" width="80%">
		<!--tr>
			<td colspan="2" align="right"><input type="button" value="Export" name="cmdExp" onclick="" />  </td>
		</tr-->
		<tr>
			<th>S.No</th>
			<th> Randomly Generated Niches </th>
			<th>  </th>
		</tr>	
<?php		
		echo $niche -> RandomNiche();
?>
	
	</table>
<?php	
 		// } else{$totalrecords = 0;$man_rs = false;}
	}
else if($process=="search")
	{
?>

	<table border="0" align="center" >
 <tr><td align="center" colspan="2" ><input type="button" title="show code" onclick="showcode()" style="cursor:pointer" value="Get a Random Niche Suggestion" />	  </td>	</tr>
  <tr>
  	<td align="right" ><input type="text" name="txtNiche"  value="" width="300px" />	  </td>
	<td align="right" ><input type="submit" name="cmdNiche" value="Search Related Niches" />	  </td>	
  </tr>
 </table>
	<table align="center" width="80%">
		<tr>
			<td colspan="2" align="right"><input type="button" value="Export" name="cmdExp" onclick="exportKwd();" />  </td>
		</tr>
		<tr>
			<th>S.No</th>
			<th> Related Niches </th>
			<th>  </th>
		</tr>	
<?php		
		echo $niche -> RelatedNiche();
?>
	
	</table>

<?php	
 		
	}
 ?> 
   </form>
<script type="text/javascript" src="/skin/_js/mootools.js"></script>
<script type="text/javascript" src="/skin/_js/mootools_more.js"></script>
<script type="text/javascript" src="/skin/_js/xlib.js"></script>

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
   
<script>
var multibox = {};
window.addEvent('domready', function() {
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
	});
});
</script>   
 <?php require_once("right.php"); ?>
 <?php require_once("bottom.php"); ?>