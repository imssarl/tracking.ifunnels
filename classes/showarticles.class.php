<?php

class showArticle

{

	function getArticleById($id)

	{

		global $database;

		$sql = "SELECT * from  `".TABLE_PREFIX."am_article` where id = ".$id;

		$rs = $database->getDataSingleRow($sql);

		return $rs;	

	}

	

	function getArticleByTitle($title)

	{

		global $database;

         	$newTitle = str_replace("_"," ",$title);



		$sql = "SELECT * from  `".TABLE_PREFIX."am_article` where title = '".$newTitle."'";

		$rs = $database->getDataSingleRow($sql);

		//echo $sql;

		return $rs;	

	}



	function getArticleByCategory($cat_id)
	{

		global $database;

// 		$sql = "SELECT * from  `".TABLE_PREFIX."am_categories` where category = '".$cat."'";

// 		$rs = $database->getDataSingleRow($sql);



		$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where category_id = '".$cat_id."' order by rand()" ;

		$res = $database->getDataSingleRow($sql1);

		return $res;	

	}

function getArticleByCategoryRandom($cat_id,$nb)
	{

		global $database;

// 		$sql = "SELECT * from  `".TABLE_PREFIX."am_categories` where category = '".$cat."'";

// 		$rs = $database->getDataSingleRow($sql);



		$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where category_id = '".$cat_id."' order by rand() LIMIT 0,".$nb ;

		$res = $database->getRS($sql1);

		return $res;	

	}




	function getArticleBySource($cat,$source)

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



		$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where category_id = '".$rs['id']."' and source='".$source."'" ;

		$res = $database->getDataSingleRow($sql1);

		return $res;	

	}

		

	function getArticleBySourcecatid($cat,$source)

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



		//$sql = "SELECT * from  `".TABLE_PREFIX."am_categories` where category = '".$cat."'";

		//$rs = $database->getDataSingleRow($sql);



		$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where category_id = '".$cat."' and source='".$source."'" ;

		$res = $database->getDataSingleRow($sql1);

		return $res;	

	}



	function getArticleByKeyword($key)

	{

		global $database;

		$data= str_replace(" ","%",$key);

		$sql="SELECT * from  `".TABLE_PREFIX."am_article_keywords` where keywords like('%".$data."%')";

		$rs = $database->getDataSingleRow($sql);



		$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where id = '".$rs['article_id']."' " ;

		$res=$database->getDataSingleRow($sql1);

		return $res;

	}





	function getArticle($key)

	{

		global $database;

		$k=explode(" ",$key);



		$sql="SELECT * from  `".TABLE_PREFIX."am_article_keywords` where ";

		$sql2 = "";

		foreach($k as $kw)

		{

			if ($sql2=="")

				$sql2 = "keywords like('%".$kw."%')";

			else

				$sql2 .= " or keywords like('%".$kw."%')";

		}



		$sql = $sql.$sql2;

 

		$result = $database->getDataSingleRow($sql);



		$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where id = '".$result['article_id']."' " ;

		$res=$database->getDataSingleRow($sql1);

		return $res;

	}

	function getArticleByKeywordAndCat($key,$cat)
{
		global $database;
		$data= str_replace(" ","%",$key);
		$sql="SELECT * from  `".TABLE_PREFIX."am_article_keywords` where keywords like('%".$data."%')";
		//$rs = $database->getDataSingleRow($sql); changed by SDEI 080109
		/*Following code added on 080109*/
		$rs = $database->getRS($sql);	
		while($data=$database->getNextRow($rs))
		{
			$xAr[]=$data['article_id'];
		}
		//print_r($xAr);
		$resTit="";
		$resBody="";
		for($i=0;$i<count($xAr);$i++){
			$res="";
			shuffle($xAr);
			$rand=mt_rand(0,count($xAr));
			$resid=$xAr[$rand];
			/*********************************/
			//echo $sql;
			//$sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where id = '".$rs['article_id']."' " ;
			 $sql1 = "SELECT * from  `".TABLE_PREFIX."am_article` where id = '".$resid."'  and category_id = '".$cat."' " ;
			$res=$database->getDataSingleRow($sql1);
			$resTit=$res['title'];
			$resBody=$res['body'];
			if($resTit !="" && $resBody!="") break;
		}
		//echo $sql1;	
		return $res;
}

	

}

?>