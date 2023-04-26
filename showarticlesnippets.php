<?php
//chdir( dirname(__FILE__) );
//chdir( '../' );
set_time_limit(0);
ignore_user_abort(true);
require_once 'inc_config.php'; // set defined params - depercated!!!
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
Core_Errors::off();
// а тут запуск нужного класса - метода
error_reporting(E_ALL);
$view = new Project_Options_HtmlGenerator();
$_GET['type_view'] = 'showarticlesnippets';
$view->init($_GET);
/*
$url = $_SERVER['REQUEST_URI'];
$url = explode("?",$url);
if ($url[1]){
	header("Location: /cronjobs/getcontent.php?type_view=showarticlesnippets&{$url[1]}");
}
	//PHP code will come here

require_once("config/config.php");

require_once("classes/database.class.php");

require_once("classes/showarticlesnippets.class.php");

//require_once("classes/pagination.class.php");

//require_once("classes/search.class.php");

//require_once("classes/pclzip.lib.php");

//require_once("classes/keyword.class.php");
require_once("classes/en_decode.class.php");	
	
	$endec=new encode_decode();

$article = new ArticleSnippet();

$database = new Database();

//$pg = new PSF_Pagination();

//$sc = new psf_Search();

//$archive = new PclZip($_FILES['importtextzip']['tmp_name']);

//$key=new keyword();

$database->openDB();

if(!is_numeric($_GET['category_id']))$_GET['category_id']=$endec->decode($_GET['category_id']);
if(isset($_GET['category_id']) && $_GET['nb'] && $_GET['source'])

	{ 

	//$article_data = $article->getArticleBySource($_GET['category'],$_GET['nb'],$_GET['source']);

	$article_data = $article->getArticleBySourcecatid($_GET['category_id'],$_GET['nb'],$_GET['source']);

	while($data=$database->getNextRow($article_data))

	{

?>

<script language="JavaScript">
function openNewWindow(url) {
	popupWin = window.open(url,
		'open_window',
		'menubar=0, toolbar=0, location=1, directories=0, status=0, scrollbars, resizable=0, dependent, width=400, height=500, left=0, top=0')
	}
</script>

<table>

	<TR>
		<TD class><a href="javascript:openNewWindow('<?php echo SERVER_PATH; ?>showarticles1.php?id=<?php echo $endec->encode($data['id']);?>');" class="a"><?php echo $data['title'];?></a></TD>

	</TR>

	<tr><TD><br></TD></tr>

	<TR>

		<TD><?php echo $data['summary'];?></TD>

	</TR>

	<tr><TD><br></TD></tr>



</table>

	

<?php

	}

}elseif(isset($_GET['category_id']) && $_GET['nb'])

	{

	$article_data = $article->getArticleByCategoryID($_GET['category_id'],$_GET['nb']);



	while($data=$database->getNextRow($article_data))

	{

?>


<script language="JavaScript">
function openNewWindow(url) {
	popupWin = window.open(url,
		'open_window',
		'menubar=0, toolbar=0, location=1, directories=0, status=0, scrollbars, resizable=0, dependent, width=400, height=500, left=0, top=0')
	}

</script>

<table>

	<TR>
		<td> <a href="javascript:openNewWindow('<?php echo SERVER_PATH; ?>showarticles1.php?id=<?php echo $endec->encode($data['id']);?>');" class="a"><?php echo $data['title'];?></td>

	</TR>

	<tr><TD><br></TD></tr>

	<TR>

		<TD><?php echo $data['summary'];?></TD>

	</TR>

	<tr><TD><br></TD></tr>



</table>

	

<?php

	}

}

//$database->freeResult($article_data);
*/
?>
