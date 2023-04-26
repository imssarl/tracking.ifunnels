<?php 



class link_search



{







function msnBackLinkCount($url)



{



	$url = "http://search.msn.com/results.aspx?q=".$url;



	//echo $url; 



	$sPageData = implode('', file($url));







	$spos = false ;



	$ret=0;



	$spos = strpos($sPageData,'search_header');



	//echo "<textarea name='1'>".$spos."</textarea>";



	//echo "<li>".$spos;



	if ($spos !== false)



	{ 



			$spos = strpos($sPageData,"of",$spos);



			//echo "<textarea name='1'>".$spos."</textarea>";



			//echo "<li>".$spos;



			if ($spos !== false)



			{



				//$spos = strpos($sPageData,"of",$spos+7);



				//echo "<li>".$spos;



				//if ($spos !== false)



				///{					



					$epos = strpos($sPageData,"results",$spos);



					//echo "<textarea name='1'>".$spos."</textarea>";



					//echo "<li>".substr($sPageData,$spos-100,120);



					$ret = str_replace(",","",trim(substr($sPageData,$spos+2,$epos-($spos+2))));



					//echo "<textarea name='1'>".$ret."</textarea>";



				//}



				



			} else $ret=0;



	} else $ret=0;



	//echo "Result : " . $ret;



	return trim($ret);



}











function get_url($url)



	{



				



if(function_exists("fopen"))



{



			$handle = @fopen($url,"r");



			$resp  = "";



			do {



			$data = @fread($handle, 8192);



			if (strlen($data) == 0) 



			{



			break;



			}



			$resp.= $data;



			} while (true);



			@fclose($handle);



		}



		else if(function_exists("curl_init"))



		{



			



			$ch = curl_init();



			curl_setopt($ch, CURLOPT_URL, $url);



			curl_setopt($ch, CURLOPT_HEADER, 0);



			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



			



			$resp = curl_exec($ch);



			curl_close ($ch);



		}



		return $resp;



}



		











function yahooBackLinkCount($url)



{



	global $link;



	$url = "http://search.yahoo.com/search?p=".$url;



	//echo $url;



	#if cfopen is supported



	//$sPageData = implode('', file($url));



	



	#if curl is supported



	$sPageData=$link->get_url($url);



	//echo $sPageData;



	$spos = false ;



	$ret=0;



	$spos1 = strpos($sPageData,"<h1>Results</h1>");



	//echo "<textarea name='l0'>".$spos1."</textarea>";



	if ($spos1 !== false)



	{ 



			$spos = strpos($sPageData,"<strong>",$spos1);



			//echo "<textarea name='l1'>".$spos."</textarea>";



			//echo "<li>1 : ".$spos;



			if ($spos !== false)



			{



				//$spos = strpos($sPageData,"<strong>",$spos+8);



				$ret = substr($sPageData, $spos1, $spos);



				$ret = strip_tags($ret);



				$spos = strpos($ret, "about");



				$spos =$spos+strlen("about");



				$spos1 = strpos($ret, "for");



				$spos1 = $spos1 + 3;



				$ret = substr($ret, $spos, $spos1);



				//echo "<li>2 : ".$ret;



				//echo "<textarea name='l1'>".$ret."</textarea>";



				//$totallinks=explode("-", $ret);



				//print_r($totallinks);



				$data = explode("-", $ret);



				$ret = trim($data[0]);



				//echo "<li>2 : ".$ret;



				/*if ($spos !== false)



				{					



					$epos = strpos($sPageData,"</strong>",$spos);



					//echo "<li> 3 : ".substr($sPageData,$spos-10,30);



					$ret = substr($sPageData,$spos+8,$epos-($spos+8));



				}*/



				



			} else $ret=0;



	} else $ret=0;



	//echo "Result : " . $ret;



	//echo "<textarea name='l0'>".$sPageData."</textarea>";



	//$ret=tot_link($sPageData,"yahoo");// by vinay



	//echo "Result : " . $ret;



	$ret=str_replace(",","",$ret);



	return trim($ret);



}























function googleLinkPopularity($url)



{	



	//$url = "http://www.google.com/search?hl=en&btnG=Search&q=".$url;



	$url ="http://www.google.com/search?q=".$url;



	//echo $url; 



	$sPageData = implode('', file($url));



	//echo($sPageData);



	$spos = false ;



	$ret=0;



	$spos = strpos($sPageData,'Web');



	//echo "<li>".$spos;



	if ($spos !== false)



	{ 



			$spos = strpos($sPageData,"about",$spos);



			//echo "<li>".$spos;



			if ($spos !== false)



			{



				//$spos = strpos($sPageData,"of",$spos+7);



				//echo "<li>".$spos;



				//if ($spos !== false)



				///{					



					$epos = strpos($sPageData,"linking",$spos);



					//echo "<li>".substr($sPageData,$spos-100,120);



//echo "<li>".$epos;					



                                 $ret = str_replace(",","",trim(substr($sPageData,$spos+5,$epos-($spos+5))));



                                       //echo("<li>".$ret); 



					



				//}



				



			} else $ret=0;



	} else $ret=0;



	



	//



	#---------------------------------------------------



	//echo "Result : " . $ret;



	return trim($ret);



}







function googleBackLinkCount($url)



{



	$url = "http://www.google.com/search?hl=en&btnG=Search&q=".$url;



	//echo $url; 



	$sPageData = implode('', file($url));



	//echo($sPageData);



	$spos = false ;



	$ret=0;



	$spos = strpos($sPageData,'Web');



	//echo "<li>".$spos;



	if ($spos !== false)



	{ 



			$spos = strpos($sPageData,"about",$spos);



			//echo "<li>".$spos;



			if ($spos !== false)



			{



				//$spos = strpos($sPageData,"of",$spos+7);



				//echo "<li>".$spos;



				//if ($spos !== false)



				///{					



					$epos = strpos($sPageData,"from",$spos);



					//echo "<li>".substr($sPageData,$spos-100,120);



//echo "<li>".$epos;					



                                 $ret = str_replace(",","",trim(substr($sPageData,$spos+5,$epos-($spos+5))));



                                  //echo("<li>".$ret); 



								  



					



				//}



				



			} else $ret=0;



	} else $ret=0;



	return trim($ret);



}	











function getrank($url)



 { 



$rank=0;



    $url = strip_tags($url);



	$file = "http://www.cmsnx.com/demo/seotoolkit/tests.php?url=$url";



	//echo $file;



	 $data = @file($file);



	//print_r($data);



	$rank  = $data[0];



	if($rank=='')



	{



	$rank=0;



	}



	 $rank;



   $file=@fopen($rank,r);



   if($file==false)



	{



//	echo "return false";



	return false;



	}



	 else



    {



   	 while(!@feof($file))



	{



	$str = @fgets($file);



	$status .= $str;



	}	



	@fclose($file);



    



    



    



    }



    return $status;



    



	}



	



function search_results($name,$id)



 {



 	global $ms_db;



 	



 



 	$sql = "Select * from `".TABLE_PREFIX."search` where 



		name = '".$ms_db->GetSQLValueString($name,"text")."' and 



		site_id = ".$id." order by id DESC LIMIT 1 ";



		$rs = $ms_db->getDataSingleRow($sql);



		return $rs;



}



	



function insert_search($site_id,$name,$page,$links,$pr,$date)



{



global $ms_db;



		$sql = "INSERT INTO `".TABLE_PREFIX."search` (  `site_id` , `name` ,  `indexedpage` , `backlink` , `pr`, `date`,`user_id`)



VALUES ("



		."'".$ms_db->GetSQLValueString($site_id,"int")."',"



		."'".$ms_db->GetSQLValueString($name,"text")."',"



		."'".$ms_db->GetSQLValueString($page,"bigint")."',"



		."'".$ms_db->GetSQLValueString($links,"bigint")."',"



		."'".$ms_db->GetSQLValueString($pr,"bigint")."',"



		."'".$ms_db->GetSQLValueString($date,"date")."',"
		
		."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX.'sessionuserid'],"int").
		
		"')";



		$id = $ms_db->insert($sql);



		return $id;



			



}	







	



function fetch_record($url,$id)



{



	global $link;



	







		$sites = new Sites();	



		$count=$link->googleLinkPopularity("link:".$url);



		$count= strip_tags($count);



		$q="site:".$url;



		$page=$link->googleBackLinkCount($q);



		$page= strip_tags($page);



		$name="google";



		$date=date("Y-m-d H:i:s");



		$rank = $link->getrank(trim($url));



		$pr=substr(trim($rank),9);



		$check=$link->insert_search($id,$name,$page,$count,$pr,$date);



		$pr=0;



		$process="yahoo";



	if($process=="yahoo")



		{



			$name="yahoo";



			$url_yahoo = "site:".$url;



			$count =$link->yahooBackLinkCount("Link:".$url);		



			$page =$link->yahooBackLinkCount($url_yahoo);



			$date=date("Y-m-d H:i:s"); 



			$check=$link->insert_search($id,$name,$page,$count,$pr,$date);



			$process="msn";



		}



		if($process=="msn")



		{



			$name="msn";



			$url_msn = "site:".$url;



			$page = $link->msnBackLinkCount($url_msn);		



			$count = $link->msnBackLinkCount($url);	



			$date=date("Y-m-d H:i:s");



			$check=$link->insert_search($id,$name,$page,$count,$pr,$date);



			$process="google";



			



		}



}	



	











	











}











?>