<?php
session_start();
//print_r($_SESSION);
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/profile.class.php");
require_once("classes/article.class.php");
require_once("classes/pclzip.lib.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");
require_once("classes/keyword.class.php");
include("classes/xmlparse.class.php");
//echo "SESSION ID:".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
$asm_db = new Database();
$asm_db->openDB();
$profile = new Profile();
$article_obj = new Article();
$archive = new PclZip($_FILES['importtextzip']['tmp_name']);
$pg = new Pagination;
$sc = new Search();
$key=new keyword();
$objXML = new xml2Array();
$count=0;
$last_tag="";
$current_tag="";
$arrtitles = array();
$arrdescs = array();
$arrlinks = array();
$arrtitles = array();
$arrdescs = array();
$arrlinks = array();
$title=array();
$arrtitles[$count]["TITLE"]="";
$arrlinks[$count]["LINK"]="";
$arrdescs[$count]["DESCRIPTION"]="";
$title[$count]="";
$flagi=0;
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
}
if(isset($_POST['insert']) && $_POST['insert']=="Yes")
{
$id = $article_obj->insertArticle();
if($id)
{ 
$msg.= "Article has been inserted successfully!";
header("Location:manage_article.php?msg=".$msg."");
}
else
{ 
$msg.= "Article has not been created, try again!";
header("Location:manage_article.php?msg=".$msg."");
}
}
$directory_rs=$article_obj->getDirectory();
// echo "fjghkfldgmjsdfg";
if(isset($_POST['submit_a']) && $_POST['submit_a']=="Yes")
{
$uploaddata = true;
$uploaddata = $article_obj->checkUploadedFile();
if($uploaddata)
{
header("location:manage_article.php?process=manage&msg=Article has been uploaded");
}
}
if(isset($_POST['submit_b']) && $_POST['submit_b']=="Yes")
{
$uploaddata = true;
$uploaddata = $article_obj->fetch_by_rss();
if($uploaddata)
{
header("location:manage_article.php?process=manage&msg=Article has been imported");
}
}

if(isset($_POST['submit_cw']) && $_POST['submit_cw']=="Yes")
{
	
	$article_obj->importArticleCW();
	$msg.= "Articles have been submitted successfully!";
	header("Location:manage_submission.php?msg=".$msg."");
}
?>
<?php
require_once("header.php");
?>
<link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jscripts/request.js"></script>
<script type="text/javascript" src="jscripts/common.js"></script>
<script language="javascript">
function getcode(id)
{
//alert(id);
openwindow= window.open ("submit_article.php?id="+id, "GETCODE",
"'status=0,scrollbars=1',width=0,height=0,resizable=1");
openwindow.moveTo(50,50);
}
function getdirectory(id)
{
openwindow= window.open ("directories.php?id="+id, "GETCODE",
"'status=0,scrollbars=1',width=750,height=500,resizable=1");
openwindow.moveTo(50,50);
}
function getschedule(id)
{
openwindow= window.open ("schedule.php?id="+id, "GETCODE",
"'status=0,scrollbars=1',width=1050,height=550,resizable=1");
openwindow.moveTo(50,50);
}
function getprofile(id)
{
openwindow= window.open ("attach_profile.php?id="+id, "GETCODE",
"'status=0,scrollbars=1',width=650,height=500,resizable=1");
openwindow.moveTo(50,50);
}
function selectmultiple()
{
document.getElementById("multiple").style.display="block";
document.getElementById("single").style.display="none";
}
function selectsingle()
{
document.getElementById("multiple").style.display="none";
document.getElementById("single").style.display="block";
}
function submitdirectory()
{
document.frm.submit();
}
function submiturl()
{
document.frmurl.submit();
}
function chkArt(){
/*	var frm = document.frmcw;
var flag=true;
for(i=0;i<frm.elements.length;i++)
{
if(frm.elements[i].type=="checkbox" && frm.elements[i].)
}*/
}
function validateCW(frm){
flags=false;
var element;
var numberOfControls = document.frmcw.length;
for (Index = 0; Index < numberOfControls; Index++)
{
element = document.frmcw[Index];
//alert(element.name);
if (element.type == "checkbox" && element.name=='chk['+Index+']')
{
if (element.checked == true)
{
flags=true;
}
}
}
if (flags==false)
{ 
alert("Please select at least one row to submit.");
return false; 
} 
else
{
for (Index = 0; Index < numberOfControls; Index++)
{
element = document.frmcw[Index];
if (element.type == "checkbox" && element.name=='chkdir'+Index)
{
if (element.checked == true)
{
flags=true;
}
}
}
if (flags==false)
{ 
alert("Please select a directory.");
return false; 
} 
else
{
//document.frmcw.submit();
//return false;
}
}
}
function validate(frm)
{
var flag=true;
var chkflg=false;
var chkpenname=false;
msg="*******************************************************\n";
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
msg+="Please Select a Directory\n";
flag = false;
}
if(frm.keyword.value=="")
{
msg+="Please enter Keyword\n";
flag = false;
}
var oSelect=document.getElementById('penname');
for(var i=0;i<oSelect.length;i++){
if(oSelect.options[i].selected==true) { chkpenname=true; break;}
}
if(chkpenname==false)
{
msg+="Please Select a Profile\n";
flag = false;
}	
if(frm.importtextzip.value=="")
{
if(frm.name=="upload")
{
msg+="Please provide ZIP file\n";
}else 
{
msg+="Please Enter url\n"; 
}
flag = false;
}
msg+="*******************************************************\n";
if(flag)
return true;
else
{
alert(msg);
return flag;
}
}
</script>
<script language="javascript" type="text/javascript">
var checkcount=0;
function showurl(dirid, val)
{
var urldivid="urldiv"+dirid;
var catdivid="catdiv"+dirid;
if(val.checked)
{	checkcount++;
document.getElementById(urldivid).style.display="";
document.getElementById(catdivid).style.display="";
}
else
{	checkcount--;
document.getElementById(urldivid).style.display="none";
document.getElementById(catdivid).style.display="none";
}
if(checkcount>1)
{
document.getElementById("filter").style.display="";
}
else
{
document.getElementById("filter").style.display="none";	
}
}
function uncheck(vals)
{
document.getElementById(vals).checked=false;
}
function opencode(id,process)
{
openwindow= window.open ("showart.php?id="+id+"&process="+process, "GETCODE",
"status=0,scrollbars=1,width=850,height=700,resizable=1");
openwindow.moveTo(50,50);
}
function rand_no(theElement)
{
var tForm = theElement.form, z = 0;
///alert(theElement.form);  
var txt=document.getElementById("art").value;
var countChk=0;
var eleNum=0;
var startAr=false;
for(z=0;z<tForm.length;z++)
{
if(tForm[z].type == 'checkbox' && tForm[z].name != 'chkall' && tForm[z].name != 'damscode_spot1' && tForm[z].name != 'spot1'&&tForm[z].name != 'spot2' && tForm[z].name != 'spot3' && tForm[z].name!='chksnippetsall_spot1' && tForm[z].name!='chksnippetsall_spot2' && tForm[z].name!='chksnippetsall_spot3' && tForm[z].name!='chksnippetsselect_spot1[]' && tForm[z].name!='chksnippetsselect_spot2[]' && tForm[z].name!='chksnippetsselect_spot3[]' && tForm[z].name!='chkcustomer_code_spot1' && tForm[z].name!='chkcustomer_code_spot2' && tForm[z].name!='chkcustomer_code_spot3' && tForm[z].name!='chksaveselectall_spot1' && tForm[z].name!='chksaveselectall_spot2' && tForm[z].name!='chksaveselectall_spot3' && tForm[z].name!='chksaveselect_spot1[]' && tForm[z].name!='chksaveselect_spot2[]' && tForm[z].name!='chksaveselect_spot3[]' && tForm[z].name!='chkselect[]' && tForm[z].name!='chksnippets_spot1'  && tForm[z].name!='chksnippets_spot2' && tForm[z].name!='chksnippets_spot3' && tForm[z].name!='chkcontents_spot1' && tForm[z].name!='chkcontents_spot2' && tForm[z].name!='chkcontents_spot3'){
//eleNum[countChk]=z;
if(startAr==false)
{
eleNum=z+1;
startAr=true;
}
countChk++;
}
}
//alert(countChk);
//alert(document.getElementById("art").value+"admin");
//alert(tForm.length);
if (document.getElementById("art").value.length == 0) 
{
alert("Please enter a value.");
} 
else if (IsNumeric(document.getElementById("art").value) == false) 
{
alert("Please enter numeric value!");
}
if(txt>(countChk-1)){
alert("Please enter numeric value less than"+countChk);
document.getElementById("art").focus();
return false;
}
for(z=0;z<txt;z++)
{
//alert("?");\
var n=Math.floor(Math.random()*(countChk-1))+eleNum;
if(tForm[n].type == 'checkbox' && tForm[n].name != 'checkall')
{
//alert("I m a cchkbox "+n);	
tForm[n].checked =true;
//ch_art[z]=tForm[n].value;	      
}
}
}
var ch_art=new Array();
// select categores function 
function cat_select(theElement){

/*//alert("admin"+id);
var tForm1 = theElement.form;
//alert(tForm1.length);
for(z=0,i=0;z<tForm1.length;z++)
{
if(tForm1[z].type == 'checkbox' && tForm1[z].name != 'checkall' && tForm1[z].checked==true)
{
ch_art[i]=tForm1[z].value;i++;	      
}
}
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
xmlHttp.onreadystatechange=function()
{
if(xmlHttp.readyState==4)
{
hdwtms();
//document.getElementById("oprss").style.display = 'block';
alert(xmlHttp.responseText);
document.getElementById("wizard").innerHTML=xmlHttp.responseText;
}
if (xmlHttp.readyState == 1) 
{
shwtms("Please wait....");
}	
}
var id=document.getElementById("seamcat").value;
var url="contentwizard.php";
url=url+"?amcat="+id+"&article="+ch_art;
//url=url+"?amcat="+id;
xmlHttp.open("GET",url,true);
xmlHttp.send(null);*/
document.frmcw.submit_cw.value='No';
document.frmcw.action="manage_article.php?amcat="+document.getElementById("seamcat").value+"&process=cw";
document.frmcw.submit();
}
// end of funtion
function shwtms(mss)
{
msd = document.getElementById("waitmss1");
//		msd.style.display = "block";
msd.innerHTML = mss;
//document.getElementById("waitmss1").style.display = 'block';
}
function hdwtms()
{
msd = document.getElementById("waitmss1");
//		msd.style.display = "none";
msd.innerHTML = '';
}
function checkUncheckContentWizard(theElement){           
var tForm = theElement.form, z = 0;  
for(z=0;z<tForm.length;z++)
{
if(tForm[z].type == 'checkbox' && tForm[z].name != 'chkall' && tForm[z].name != 'damscode_spot1' && tForm[z].name != 'spot1'&&tForm[z].name != 'spot2' && tForm[z].name != 'spot3' && tForm[z].name!='chksnippetsall_spot1' && tForm[z].name!='chksnippetsall_spot2' && tForm[z].name!='chksnippetsall_spot3' && tForm[z].name!='chksnippetsselect_spot1[]' && tForm[z].name!='chksnippetsselect_spot2[]' && tForm[z].name!='chksnippetsselect_spot3[]' && tForm[z].name!='chkcustomer_code_spot1' && tForm[z].name!='chkcustomer_code_spot2' && tForm[z].name!='chkcustomer_code_spot3' && tForm[z].name!='chksaveselectall_spot1' && tForm[z].name!='chksaveselectall_spot2' && tForm[z].name!='chksaveselectall_spot3' && tForm[z].name!='chksaveselect_spot1[]' && tForm[z].name!='chksaveselect_spot2[]' && tForm[z].name!='chksaveselect_spot3[]' && tForm[z].name!='chkselect[]' && tForm[z].name!='chksnippets_spot1'  && tForm[z].name!='chksnippets_spot2' && tForm[z].name!='chksnippets_spot3' && tForm[z].name!='chkcontents_spot1' && tForm[z].name!='chkcontents_spot2' && tForm[z].name!='chkcontents_spot3'){
tForm[z].checked = theElement.checked;
}
}
}		
function IsNumeric(strString)
//  check for valid numeric strings	
{
var strValidChars = "0123456789.-";
var strChar;
var blnResult = true;
if (strString.length == 0) return false;
//  test strString consists of valid characters listed above
for (i = 0; i < strString.length && blnResult == true; i++)
{
strChar = strString.charAt(i);
if (strValidChars.indexOf(strChar) == -1)
{
blnResult = false;
}
}
return blnResult;
}	
</script>
<?php
require_once("inc_menu.php");
?>
<?php
if($process=="manage")
{
//$sql = "select count(*) from ".TABLE_PREFIX."article where isScheduled='N'";
//$totalrecords = $asm_db->getDataSingleRecord($sql);
//		if ($totalrecords>0)
//		{
//			$pg->setPagination($totalrecords);
//		}
//		else
//		{
//			$pg->startpos=0;
//		}
//
$order_sql = $sc->getOrderSql(array("id","title","summary"),"id");
//$sql = "select * from `".TABLE_PREFIX."project_master`".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;
//$project_rs = $cnm_db->getRS($sql);
$article_rs = $article_obj->getArticle();
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
<td valign = "top" align="center" class="heading"><a href="?process=add" class="menu">Add a new article</a> <?/*?>| <a href="?process=cw" class="menu">Import from Content Wizard</a><?*/?> | <a href="?process=import" class="menu">Mass Import </a> | <a href="?process=rss" class="menu">Import through Rss </a></td>
</tr>
<br>
<tr>
<td colspan="6">
<?php $pg->showPagination(); ?>
</td>
</tr>
</table><br /><br />
<table border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
<tr  class="tableheading">
<th><a title = "Sort" class = "menu" href="?sort=id">Article #</a></th>
<th><a title = "Sort" class = "menu" href="?sort=title">Title</a></th>
<th><a title = "Sort" class = "menu" href="?sort=summary">Summary</a></th>
<th></th>
<th></th>
<th></th>
<th></th>
<th></th>
</tr>
<?php
if ($article_rs)
{
$tblmat=0;
$cnt=1;
while($article = $asm_db->getNextRow($article_rs))
{
$id = $article['id'];
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
<td align="center"><a href="#" onclick="opencode('<?php echo $id;?>','article')" style="cursor:pointer"><?php echo str_replace("&acirc;€“",' - ', html_entity_decode($article['title'])); ?></a></td>
<td align="center"><?php echo str_replace("&acirc;€“",' - ',html_entity_decode($article['summary']));?></td>
<td align="center" width="16px">
<img src="images/edit.png" border="0" title="Click Here To Edit Article" onClick="opencode('<?php echo $id;?>','article');" style="cursor:pointer;">
</td>
<td align="center" width="16px">
<img src="images/list.png" border="0" title="Click Here To Select Profile" onClick="getprofile('<?php echo $id;?>');" style="cursor:pointer;">
</td>
<td align="center" width="16px">
<img src="images/folder.png" border="0" title="Click Here To Select Directory" onClick="getdirectory('<?php echo $id;?>');" style="cursor:pointer;">
</td>
<td align="center" width="16px">
<a href="#">
<img src="images/calender.gif" border="0" title="Click Here To Select Schedule" onClick="getschedule('<?php echo $id;?>');" style="cursor:pointer;">
</a>
</td>
<td align="center" width="16px">
<img src="images/getcode1.gif" border="0" title="Click Here To Submit Article" onClick="getcode('<?php echo $id;?>');" style="cursor:pointer;">
</td>
</tr>
<?php
}
}
else
{
echo "<tr><td align='center' colspan='6'>No Article Found</td></tr>";
}
?>
</table>
<?php
}elseif($process=="add")
{
?>
<form action="" method="post" name="article">
<table width="80%"  border="0" cellspacing="3" cellpadding="3"  align="center" class="summary2">
<tr>
<td class="heading" colspan="2" align="center" style="font-weight:bold">Add New Article</td>
</tr>
<tr>
<td align="right">Title&nbsp;</td>
<td><input name="title" id="title" size="95" value="" type="text"></td>
</tr>
<tr>
<td align="right">Summary&nbsp;</td>
<td><textarea name="summary" id="summary" rows="3" cols="93"></textarea></td>
</tr>
<tr>
<td align="right">Body&nbsp;</td>
<td><textarea name="body" id="body" rows="10" cols="93"></textarea></td>
</tr>
<tr>
<td align="right">Keyword&nbsp;</td>
<td><textarea name="keyword" id="keyword" rows="10" cols="93"></textarea></td>
</tr>
<tr>
<td class="heading" colspan="2" align="center">
<input type="hidden" name="insert" value="Yes" />
<input name="submit" value="Save" type="submit">&nbsp;&nbsp;<input type="button" name="cancel" value="Cancel" onclick="window.location.href = 'manage_article.php'"  />
</td>
</tr>
</table>
</form>
<?php
}elseif($process=="import")
{
?>
<form action="" name="upload" method="post" enctype="multipart/form-data" onsubmit="return validate(this);">
<table width="80%"  border="0" cellspacing="3" cellpadding="3" class="summary">
<tr>
<td align="center" width="40%" class="heading" style="font-weight:bold;">Mass Import</td>
</tr>
<br>
<tr>
<TD align="left">Upload ZIP:
<input type="file" name="importtextzip" id="importtextzip" size="45">
</TD>
</tr>
<tr>
<td align="left">Keyword:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea name="keyword" id="keyword" rows="2" cols="40"></textarea></td>
</tr>
<tr>
<td align="left">
<table cellpadding="0" cellspacing="4" border="0">
<?php
if($directory_rs)
{
while($directory=$asm_db->getNextRow($directory_rs))
{
echo "<tr><td height='32'>";
?>
<input type="checkbox" name="chkdir[]" id="chkdir<?php echo $directory['id'];?>" value="<?php echo $directory['id'];?>" onclick="showurl('<?php echo $directory['id'];?>',this);" />
<input type="hidden" name="dirtype<?php echo $directory['id'];?>" value="<?php echo $directory['type'];?>" />
<script language="javascript">uncheck('chkdir<?php echo $directory['id'];?>'); </script>
</td>
<td><?php echo $directory['directory'];?>
<?php
echo "</td><td nowrap='nowrap' style='padding-left:10px;'>";
$url_rs = $article_obj->getUrl($directory['id']);
?>
<div id="urldiv<?php echo $directory['id'];?>" style="display:none; float:left; padding-bottom:5px;">
Directory [Label] Url: <select style="width:250px;" name="url<?php echo $directory['id'];?>">
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
echo "</td><td nowrap='nowrap'>";
$cat_rs = $article_obj->getCategorybyDIR($directory['id']);
?>	
<div id="catdiv<?php echo $directory['id'];?>" style="display:none; float:left; padding-bottom:5px;">
Category: <select style="width:250px;" name="cat<?php echo $directory['id'];?>">
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
<tr>
<td colspan="2" align="center">
<div id="filter" style="display:none;">
All articles submitted to all directories: <input name="filter" value="A" checked="checked" type="radio">&nbsp;&nbsp;1 article submitted randomly to 1 directory : <input name="filter" value="R" type="radio">
</div>
</td>
</tr>
<tr><td align="center">
<table align="center" width="100%" border="0">
<?php
$sql = "select * from `".TABLE_PREFIX."profile` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
$profile_rs = $asm_db->getRS($sql);
if($profile_rs)
{
?>
<tr>
<td align="center" colspan="2">Profile:
<select name="f_pennameid[]" id="penname" multiple="multiple" style="width:35%">
<?php
while($profile=$asm_db->getNextRow($profile_rs))
{
?>
<option value="<?php echo $profile['id'];?>"><?php echo $profile['profile_name'];?></option>
<?php
}
?>
</select>
</td>
</tr>
<tr>
<td colspan="2" align="center">
Do you want to apply all profiles to each article: <input type="radio" name="profile" value="A" checked="checked" />&nbsp;&nbsp;To rotate the profiles among all the articles: <input type="radio" name="profile" value="R"/>
</td>
</tr>
<?php
}
?>
</table>
</td></tr>
<tr>
<td align="center">Schedule:
<select name="scount">
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
<td align="center" class="heading">
<div align="center">
<input type="hidden" name="submit_a" value="Yes" />
<input type="hidden" name="url" value="<?php echo $_POST['url'];?>" />
<input type="hidden" name="article_id" value="<?php echo $_GET['id'];?>" />
<input type="hidden" name="directory" value="<?php echo $_POST['directory'];?>" />
<input type="hidden" name="type" value="<?php echo $dir_type['type']?>" />
<input type="hidden" name="process" value="<?php echo $process ?>">
<input type="submit" name="Submit" value="Save">
</div>
</td>
</tr>
</table>
</form>    
<?php
}elseif($process=="rss")
{
?>
<form action="" name="fetchrss" method="post" enctype="multipart/form-data" onsubmit="return validate(this);">
<table width="80%"  border="0" cellspacing="3" cellpadding="3" class="summary">
<tr>
<td align="center" width="40%" class="heading" style="font-weight:bold;">Import by Rss</td>
</tr>
<br>
<tr>
<TD align="left">Enter url to fetch:
<input type="text" name="importtextzip" id="importtextzip" size="45" onchange="fetch_rss(this)" onblur="fetch_rss(this)"><div id="oprss"></div>
</TD>
</tr>
<tr>
<TD align="left">Enter Keyword:&nbsp;&nbsp;&nbsp;&nbsp;
<textarea  name="keyword" id="keyword" cols="40" rows="2"></textarea></div>
</TD>
</tr>
<tr>
<td align="left">
<table cellpadding="0" cellspacing="4" border="0">
<?php
if($directory_rs)
{
while($directory=$asm_db->getNextRow($directory_rs))
{
echo "<tr><td height='32'>";
?>
<input type="checkbox" name="chkdir[]" id="chkdir<?php echo $directory['id'];?>" value="<?php echo $directory['id'];?>" onclick="showurl('<?php echo $directory['id'];?>',this);" />
<input type="hidden" name="dirtype<?php echo $directory['id'];?>" value="<?php echo $directory['type'];?>" />
<script language="javascript">uncheck('chkdir<?php echo $directory['id'];?>'); </script>
</td>
<td><?php echo $directory['directory'];?>
<?php
echo "</td><td nowrap='nowrap' style='padding-left:10px;'>";
$url_rs = $article_obj->getUrl($directory['id']);
?>
<div id="urldiv<?php echo $directory['id'];?>" style="display:none; float:left; padding-bottom:5px;">
Directory [Label] Url: <select style="width:250px;" name="url<?php echo $directory['id'];?>">
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
echo "</td><td nowrap='nowrap'>";
$cat_rs = $article_obj->getCategorybyDIR($directory['id']);
?>	
<div id="catdiv<?php echo $directory['id'];?>" style="display:none; float:left; padding-bottom:5px;">
Category: <select style="width:250px;" name="cat<?php echo $directory['id'];?>">
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
<tr>
<td colspan="2" align="center">
<div id="filter" style="display:none;">
All articles submitted to all directories: <input name="filter" value="A" checked="checked" type="radio">&nbsp;&nbsp;1 article submitted randomly to 1 directory : <input name="filter" value="R" type="radio">
</div>
</td>
</tr>
<tr><td align="center">
<table align="center" width="100%" border="0">
<?php
$sql = "select * from `".TABLE_PREFIX."profile` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
$profile_rs = $asm_db->getRS($sql);
if($profile_rs)
{
?>
<tr>
<td align="center" colspan="2">Profile:
<select name="f_pennameid[]" id="penname" multiple="multiple" style="width:35%">
<?php
while($profile=$asm_db->getNextRow($profile_rs))
{
?>
<option value="<?php echo $profile['id'];?>"><?php echo $profile['profile_name'];?></option>
<?php
}
?>
</select>
</td>
</tr>
<tr>
<td colspan="2" align="center">
Do you want to apply all profiles to each article: <input type="radio" name="profile" value="A" checked="checked" />&nbsp;&nbsp;To rotate the profiles among all the articles: <input type="radio" name="profile" value="R"/>
</td>
</tr>
<?php
}
?>
</table>
</td></tr>
<tr>
<td align="center">Schedule:
<select name="scount">
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
<td align="center" class="heading">
<div align="center">
<input type="hidden" name="submit_b" value="Yes" />
<input type="hidden" name="url" value="<?php echo $_POST['url'];?>" />
<input type="hidden" name="article_id" value="<?php echo $_GET['id'];?>" />
<input type="hidden" name="directory" value="<?php echo $_POST['directory'];?>" />
<input type="hidden" name="type" value="<?php echo $dir_type['type']?>" />
<input type="hidden" name="process" value="<?php echo $process ?>">
<input type="submit" name="Submit" value="Save">
</div>
</td>
</tr>
</table>
</form>    
<?php
}elseif($process=="cw"){
?>
<form action="" name="frmcw" method="post" enctype="multipart/form-data" ><!--onsubmit="return validateCW(this);"-->
<table width="80%"  border="0" cellspacing="3" cellpadding="3" class="summary">
<tr>
<td align="center" width="40%" class="heading" style="font-weight:bold;">Import From Content Wizard</td>
</tr>
<br>
<tr>
<td align="center">
<div id="waitmss1" class="message" style="display:inline;" ></div>
</td>
</tr>
<tr>
<TD align="left">
<div id="oprss">
<div id="wizard" >
<?php
//print_r($_SESSION);
if($_REQUEST['amcat']>0)
$process1='advsearch';
else
$process1='new';
if($process1=="new")
{
$sql = "select count(*) from `hct_am_article` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
}
elseif($process1=="advsearch")
{
$sql = "select count(*) from `hct_am_article` where category_id='".$_REQUEST['amcat']."' ";
}
$totalrecords = $asm_db->getDataSingleRecord($sql);
if ($totalrecords>0)
{
$pg->setPagination($totalrecords);
$order_sql = $sc->getOrderSql(array("id","category","title","summary","source","status"),"id");
$str='	<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
<tr>
<td align="center" colspan="6">
<input type="text" name="art" id="art"  />
<input type="button" name="rand"  value="Random" onClick=\'rand_no(this)\'/>
</td>
</tr>
<!--<tr>
<td colspan="12">$pg->showPagination1()</td>
</tr> -->
<tr bgcolor="#999999">
<td>ID</td>
<td>
<input type="hidden" name="type" value="P" />
<select name="seamcat" id="seamcat" style="background-color:#A2A2A2" onchange="cat_select(this)">';
$str.=$article_obj->SelectBox();
$str.='</select>				
</td>';	
//echo $sql;
//die;
if(isset($articles) && $articles!=""){		
foreach($art as $id)
{
$str.=' <input type="hidden" name="art1[]" value="'.$id.'" />';
}
}
$str.=' <input type="hidden" name="btndelete" value="" />
<td>Title</td>
<td>Summary</td>
<td><input name=\'chkall\' type=\'checkbox\' id=\'chkall\' value=\'chkall\' onClick=\'checkUncheckContentWizard(this)\'></td>
</tr>';
if($process1=="new")
{
$str.= $article_obj->manageArticle();
}
else
{
$str.= $article_obj->selectCategory();
}
echo $str.='</table>';
}
else
{
echo '<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
<tr>
<td align="center" >
No article in Content Wizard 
</td>
</tr>

</table>';
}
?>
</div>	
</div>
</TD>
</tr>
<tr>
<TD align="left">Enter Keyword:&nbsp;&nbsp;&nbsp;&nbsp;
<textarea  name="keyword" id="keyword" cols="40" rows="2"></textarea></div>				</TD>
</tr>
<tr>
<td align="left">
<table cellpadding="0" cellspacing="4" border="0">
<?php
if($directory_rs)
{
while($directory=$asm_db->getNextRow($directory_rs))
{
echo "<tr><td height='32'>";
?>
<input type="checkbox" name="chkdir[]" id="chkdir<?php echo $directory['id'];?>" value="<?php echo $directory['id'];?>" onclick="showurl('<?php echo $directory['id'];?>',this);" />
<input type="hidden" name="dirtype<?php echo $directory['id'];?>" value="<?php echo $directory['type'];?>" />
<script language="javascript">uncheck('chkdir<?php echo $directory['id'];?>'); </script>
</td>
<td><?php echo $directory['directory'];?>
<?php
echo "</td><td nowrap='nowrap' style='padding-left:10px;'>";
$url_rs = $article_obj->getUrl($directory['id']);
?>
<div id="urldiv<?php echo $directory['id'];?>" style="display:none; float:left; padding-bottom:5px;">
Directory [Label] Url: <select style="width:250px;" name="url<?php echo $directory['id'];?>">
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
echo "</td><td nowrap='nowrap'>";
$cat_rs = $article_obj->getCategorybyDIR($directory['id']);
?>	
<div id="catdiv<?php echo $directory['id'];?>" style="display:none; float:left; padding-bottom:5px;">
Category: <select style="width:250px;" name="cat<?php echo $directory['id'];?>">
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
<tr>
<td colspan="2" align="center">
<div id="filter" style="display:none;">
All articles submitted to all directories: <input name="filter" value="A" checked="checked" type="radio">&nbsp;&nbsp;1 article submitted randomly to 1 directory : <input name="filter" value="R" type="radio">
</div>				</td>
</tr>
<tr><td align="center">
<table align="center" width="100%" border="0">
<?php
$sql = "select * from `".TABLE_PREFIX."profile` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
$profile_rs = $asm_db->getRS($sql);
if($profile_rs)
{
?>
<tr>
<td align="center" colspan="2">Profile:
<select name="f_pennameid[]" id="penname" multiple="multiple" style="width:35%">
<?php
while($profile=$asm_db->getNextRow($profile_rs))
{
?>
<option value="<?php echo $profile['id'];?>"><?php echo $profile['profile_name'];?></option>
<?php
}
?>
</select>	</td>
</tr>
<tr>
<td colspan="2" align="center">
Do you want to apply all profiles to each article: <input type="radio" name="profile" value="A" checked="checked" />&nbsp;&nbsp;To rotate the profiles among all the articles: <input type="radio" name="profile" value="R"/>	</td>
</tr>
<?php
}
?>
</table>
</td></tr>
<tr>
<td align="center">Schedule:
<select name="scount">
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
</select>			</td>
</tr>
<tr>
<td align="center" class="heading">
<div align="center">
<input type="hidden" name="submit_cw" value="Yes" />
<input type="hidden" name="url" value="<?php echo $_POST['url'];?>" />
<input type="hidden" name="article_id" value="<?php echo $_GET['id'];?>" />
<input type="hidden" name="directory" value="<?php echo $_POST['directory'];?>" />
<input type="hidden" name="type" value="<?php echo $dir_type['type']?>" />
<input type="hidden" name="process" value="<?php echo $process ?>">
<input type="submit" name="Submit" value="Save">
</div>			</td>
</tr>
</table>
</form>    
<?php
}
?>
<?php
require_once("footer.php");
?>