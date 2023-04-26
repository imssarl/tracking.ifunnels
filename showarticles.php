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
$_GET['type_view'] = 'showarticles';
$view->init($_GET);
/*

$url = $_SERVER['REQUEST_URI'];
$url = explode("?",$url);
if ($url[1]){
	header("Location: /cronjobs/getcontent.php?type_view=showarticles&{$url[1]}");
}
//PHP code will come here
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/showarticles.class.php");
require_once("classes/en_decode.class.php");
error_reporting(E_ERROR);
$endec=new encode_decode();
$article = new showArticle();
$database = new Database();
$database->openDB();
//echo "id:".$_GET['id'];
if(!is_numeric($_GET['category_id']))$_GET['category_id']=$endec->decode($_GET['category_id']);

if(!is_numeric($_GET['id']))$_GET['id']=$endec->decode($_GET['id']);

//echo "<br/>id:".$_GET['id'];

if(isset($_GET['id'])&& $_GET['id']!=''){
	//echo $sql = "SELECT * from  `".TABLE_PREFIX."am_article` where id =".$_GET['id']."";
	$article_data = $article->getArticleById($_GET['id']);?>	
	<table>		
		<TR>			
			<TD><?php echo $article_data['title'];?></TD>		
		</TR>		
		<TR>
			<TD></TD>
		</TR>	
		<TR>
			<TD><?php //echo nl2br($article_data['summary']);?></TD>
		</TR>
		<TR>
			<TD><?php echo nl2br($article_data['body']);?></TD>		
		</TR>	
		</table>
<?php	
}

if(!is_numeric($_GET['category_id']))
$_GET['category_id']=$endec->decode($_GET['category_id']);

if(isset($_GET['category_id']) && $_GET['source'] && $_GET['source']!='') { 

	//echo "Catid: ".$_GET['category_id'];
	//	$article_data = $article->getArticleBySource($_GET['category_id'],$_GET['source']);
	$article_data = $article->getArticleBySourcecatid($_GET['category_id'],$_GET['source']);?>	

	<table>		
		<TR>			
			<TD>
				<?php echo $article_data['title'];?>
			</TD>
		</TR>
		<tr>
			<TD><br></TD>
		</tr>		
		<!--TR><TD><?php //echo nl2br($article_data['summary']);?></TD></TR-->		
		
		<TR>
			<TD><?php echo nl2br($article_data['body']);?></TD>		
		</TR>
	</table>
<?php 
}
elseif(isset($_GET['category_id']) && $_GET['category_id']!='') 
{
	//echo $_GET['category_id'];
	if(isset($_GET["nb"])){ 
		$article_data = $article->getArticleByCategoryRandom($_GET['category_id'],$_GET["nb"]);
		while($article=$database->getNextRow($article_data)){ 
		?>		
		<table>
			<TR>
				<TD><?php echo $article['title'];?></TD>
			</TR>
			<TR>
			<!--TD><?php //echo nl2br($article['summary']);?></TD-->
			</TR>
			<tr><TD><br></TD></tr>	
			<TR>	
				<TD><?php echo nl2br($article['body']);?></TD>			</TR>		
		</table>
	<?php
		}
	}
	else
	{
		$article_data = $article->getArticleByCategory($_GET['category_id']);
		while($article=$database->getNextRow($article_data))
		{
?>	
		<table>
			<TR>
				<TD><?php echo $article['title'];?></TD>			</TR>
			<tr><TD><br></TD></tr>			
			<!--TR><TD><?php //echo nl2br($article['summary']);?></TD></TR-->
			<tr><TD><br></TD></tr>	
			<TR>
				<TD><?php echo nl2br($article['body']);?></TD>			
			</TR>
		</table>
<?php		
		}
	}
}
if (isset($_GET['keyword']) && $_GET['keyword']!="")
{
	if(!is_numeric($_GET['defcategory']))$_GET['defcategory']=$endec->decode($_GET['defcategory']);
	$article_data = $article->getArticleByKeywordAndCat($_GET['keyword'],$_GET['defcategory']);
	if($article_data!="")
	{
?>	
		<table>
			<TR>
				<TD><?php echo $article_data['title'];?></TD>		
			</TR>
			<tr><TD><br></TD></tr>
			<!--TR>	<TD><?php //echo $article_data['summary'];?></TD></TR-->
			<tr><TD><br></TD></tr>
			<TR>
				<TD><?php echo $article_data['body'];?></TD>
			</TR>
		</table>
<?php 
	}else{
		$article_data = $article->getArticle($_GET['keyword']);
		if($article_data!="")
		{
?>
		<table>
			<TR>
				<TD><?php echo $article_data['title'];?></TD>
			</TR>
			<tr><TD><br></TD></tr>
			<!--TR>	<TD><?php //echo $article_data['summary'];?></TD></TR-->
			<TR>
				<TD><?php //echo $article_data['body'];?></TD>
			</TR>
		</table>
<?php
		}else if(isset($_GET['defcategory']) && $_GET['defcategory']!="")
		{
			if(!is_numeric($_GET['defcategory']))$_GET['defcategory']=$endec->decode($_GET['defcategory']);
			$article_data = $article->getArticleByCategory($_GET['defcategory']);

?>
		<table>
			<TR>
				<TD><?php echo $article_data['title'];?></TD>
			</TR>
			<!--TR>
				<TD><?php //echo $article_data['summary'];?></TD>
			</TR-->
			<tr><TD><br></TD></tr>
			<TR>
				<TD><?php echo $article_data['body'];?></TD>
			</TR>
		</table>
<?php

		}else

		echo "No Record Found";

	}

}*/

?>