<?php
session_start();
set_time_limit(0);
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/settings.class.php");
require_once("classes/sites.class.php");
require_once("classes/common.class.php");
require_once("classes/sites.steps.class.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");
require_once("classes/articles.class.php");
require_once("classes/projects.class.php");
require_once("classes/pclzip.lib.php");
require_once("classes/feed.class.php");
require_once("classes/pinger.php");
require_once("classes/phpmailer.class.php");
// extra fuctions for wizrad
//require_once("classes/amarticle.class.php");
$database = new Database();
$database->openDB();
$pg = new PSF_Pagination();
$sc = new psf_Search();
// end of extra function 
$article = new Article();
$mail = new PHPMailer();
$pf = new PingFeed();
$feed = new xmlParser();
$steps = new Steps();
$prj = new Projects();
$art =  new Article();
$archive = new PclZip($_FILES['importtextzip']['tmp_name'][$no]);
$settings = new Settings();
$common = new Common();
$settings->checkSession();
$ms_db = new Database();
$ms_db->openDB();
$user_data = $settings->getSettings();
//print_r($_REQUEST['admin']);
$articles=$_REQUEST['article'];
//print_r($articles);
$art=explode(',',$articles);

if($_REQUEST['amcat']>0)
	$process='advsearch';
else
	$process='new';

if($process=="new")
{
	echo $sql = "select count(*) from `hct_am_article` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
}
elseif($process=="advsearch")
{
	echo $sql = "select count(*) from `hct_am_article` where category_id='".$_REQUEST['amcat']."' ";
}
//else
//$sql = "select count(*) from `".TABLE_PREFIX."am_article` ";
$totalrecords = $database->getDataSingleRecord($sql);
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
$str.=$article -> SelectBox();
$str.='</select>				
</td>';	
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
if($process=="new")
{
$str.= $article->manageArticle();
}
else
{
$str.= $article->selectCategory();
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
<script language="javascript">
</script>