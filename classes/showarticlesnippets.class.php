<?php

class ArticleSnippet

{

	function getArticleByCategory($cat,$no)

	{

		global $database;

		$sql = "SELECT * from  `".TABLE_PREFIX."am_categories` where category = '".$cat."'";

		$rs = $database->getDataSingleRow($sql);



		$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where category_id = '".$rs['id']."' limit 0,".$no;

		$res = $database->getRS($sql1);

		return $res;	

	}

	function getArticleById($id)

	{

		global $database;

		$sql = "SELECT * from  `".TABLE_PREFIX."am_article` where id ='".$id."'";
		//SELECT * from `hct_am_article` where id ='938' 
		//SELECT * from `hct_am_article` where id ='942' echo $sql;
		//echo $sql;
		$rs = $database->getDataSingleRow($sql);

		return $rs;	

	}

function getArticleByCategoryID($cat,$no)
{

		global $database;
		$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where category_id = '".$cat."' limit 0,".$no;

		$res = $database->getRS($sql1);
		//echo $sql1;	
		return $res;	

	}



	function getArticleBySource($cat,$no,$source)
	{

		global $database;



		if($_GET['source']==1)

		{

		$source="PLR";

		}

		if($_GET['source']==2)

		{

		$source="Free reprint rights";

		}

		if($_GET['source']==3)

		{

		$source="Own";

		}

		if($_GET['source']==4)

		{

		$source="Partners";

		}

		

		$sql = "SELECT * from  `".TABLE_PREFIX."am_categories` where category = '".$cat."'";

		$rs = $database->getDataSingleRow($sql);



		$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where category_id = '".$rs['id']."' and source='".$source."' limit 0,".$no;

		$res = $database->getRS($sql1);

		return $res;	

	}

	function showTitleByCategory($cat_id,$no)

	{

		global $database;

// 		$sql = "SELECT * from  `".TABLE_PREFIX."am_categories` where category = '".$cat."'";

// 		$rs = $database->getDataSingleRow($sql);



		$sql1 = "SELECT id,title from  `".TABLE_PREFIX."am_article` where category_id = '".$cat_id."' limit 0,".$no;

		$res = $database->getRS($sql1);



		return $res;

	}

	function getArticleByRandom($cat_id,$no)

	{

		global $database;

// 		$sql = "SELECT * from  `".TABLE_PREFIX."am_categories` where category = '".$cat."'";

// 		$rs = $database->getDataSingleRow($sql);



		$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where category_id = '".$cat_id."'   order by rand() limit 0,".$no;

		$res = $database->getRS($sql1);

		return $res;	

	}

	function showTitleByRandom($cat_id,$no)

	{

		global $database;

// 		$sql = "SELECT * from  `".TABLE_PREFIX."am_categories` where category = '".$cat."'";

// 		$rs = $database->getDataSingleRow($sql);



		$sql1 = "SELECT id,title from  `".TABLE_PREFIX."am_article` where category_id = '".$cat_id."' order by rand() limit 0,".$no;

		$res = $database->getRS($sql1);



		return $res;

	}	



}

function getArticleBySourcecatid($cat,$no,$source)
{
	

		global $database;



		if($_GET['source']==1)

		{

		$source="PLR";

		}

		if($_GET['source']==2)

		{

		$source="Free reprint rights";

		}

		if($_GET['source']==3)

		{

		$source="Own";

		}

		if($_GET['source']==4)

		{

		$source="Partners";

		}

		

		$sql = "SELECT * from  `".TABLE_PREFIX."am_categories` where category = '".$cat."'";

		$rs = $database->getDataSingleRow($sql);

		echo $sql;

		$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where category_id = '".$rs['id']."' and source='".$source."' limit 0,".$no;
	
		echo $sql1;

		$res = $database->getRS($sql1);

		return $res;	

	

}

?>