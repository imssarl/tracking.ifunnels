<?php 

session_start();

class Article{

	function manageArticle(){
	
	global $asm_db,$pg,$order_sql;

	$sql="SELECT a.category,b.* FROM `hct_am_categories` a,`hct_am_article` b WHERE b.user_id='".$_SESSION[MSESSION_PREFIX.'sessionuserid']."' and a.id=b.category_id ".$order_sql;
	/*$sql="SELECT a.category,b.* FROM `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b WHERE a.id=b.category_id ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;*/
	$man_rs=$asm_db->getRS($sql);
	if($man_rs)
	{	$no=0;
		while($data=$asm_db->getNextRow($man_rs))
		{	
			$no=$no+1;
			$summary=wordwrap($data['summary'], 100, "\n");
			$title=wordwrap($data['title'], 100, "\n");
			// for task11 on 27 nov
			for($i=0;$i<count($_SESSION['ncsb_article_id']);$i++) 

				{		

					 //$_SESSION['$i']=$ncsb[$i];

					 

					 if($_SESSION['ncsb_article_id'][$i]==$data['id']){

						$sel='checked="checked"';break;}

					else

						$sel='';

				}



			$str .= "<tr><td align='center' width='20px'>".$data['id']."</td><td align='center'>".$data['category']."</td><td align='center' onclick='opencode(".$data['id'].")' style='cursor:pointer'>".$title."</td><td align='center'>".$summary."</td><td><input name='chk[]' id='chk".$no."' type='checkbox' value=".$data['id']."  ".$sel."  onclick='return test(this)'></td></tr>";







		}







	}







	return $str; 







	
	
	}

	function selectCategory()
	{



		global $asm_db,$pg,$order_sql;



		$sql="SELECT a.category,b.* FROM `hct_am_categories` a,`hct_am_article` b WHERE b.user_id='".$_SESSION[MSESSION_PREFIX.'sessionuserid']."' and  b.category_id='".$_REQUEST['amcat']."' and a.id=b.category_id ".$order_sql;

		/*$sql="SELECT a.category,b.* FROM `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b WHERE b.category_id='".$_REQUEST['amcat']."' and a.id=b.category_id ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;*/

		$man_rs=$asm_db->getRS($sql);



		$str="";



		if($man_rs)



		{



			$no=0;



			while($data=$asm_db->getNextRow($man_rs))



			{	$no=$no+1;



				$summary=wordwrap($data['summary'], 100, "\n");



				$title=wordwrap($data['title'], 100, "\n");



				$str .= "<tr><td align='center' width='20px'>".$data['id']."</td><td align='center'>".$data['category']."</td><td align='center' onclick='opencode(".$data['id'].")' style='cursor:pointer'>".$title."</td><td align='center'>".$summary."</td><td><input name='chk[]' id='chk".$no."' type='checkbox' value=".$data['id']." onclick='return test(this)'></td></tr>";



  	



			}







		}







		return $str;



	}
function SelectBox()
		{
			global $asm_db,$article_data;



			$sql="select id, category from `hct_am_categories` where status='Active' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];



			$cat_rs=$asm_db->getRS($sql);

				$str.="<option value='-1'>All Category</option>";

			?>



			<!--$str .= "<select name='category' id='category'>";-->



			



			<?php



			if ($cat_rs)



			{



				while($data = $asm_db->getNextRow($cat_rs))



				{



				



					if($_REQUEST['amcat']==$data["id"]) $selected = 'selected="selected"'; else $selected = "";

						

						$str.='<option value="'. $data['id'].'" '.$selected.'>'.$data['category'].'</option>';

				?>



					



	<?php



				}



			}



			//$str .= "</select>";



			return $str;		



			



		}

	function insertArticle()

	{

		global $asm_db;
		
		//echo $_POST['title'].'<br>'.$_POST['summary'];
		$_POST['title']=str_replace('&acirc;&#65533;&#65533;','-',$_POST['title']);
		$_POST['title']=str_replace('&#65533;','',$_POST['title']);
		
		$_POST['summary']=str_replace('&acirc;&#65533;&#65533;','-',$_POST['summary']);
		$_POST['summary']=str_replace('&#65533;','',$_POST['summary']);	
		
		$_POST['body']=str_replace('&acirc;&#65533;&#65533;','-',$_POST['body']);
		$_POST['body']=str_replace('&#65533;','',$_POST['body']);
		
		$_POST['keyword']=str_replace('&acirc;&#65533;&#65533;','-',$_POST['keyword']);
		$_POST['keyword']=str_replace('&#65533;','',$_POST['keyword']);	
			
		$sql = "INSERT INTO `".TABLE_PREFIX."article` (`title`, `summary`,`body`, `keyword`,`flag`,`user_id`) VALUES (

		
		
		".$asm_db->GetSQLValueString($_POST['title'],"text").",

		".$asm_db->GetSQLValueString($_POST['summary'],"text").",

		".$asm_db->GetSQLValueString($_POST['body'],"date").",

		".$asm_db->GetSQLValueString($_POST['keyword'],"text").",

		".$asm_db->GetSQLValueString('F',"text").",

		".$asm_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int").")";

	
		$id = $asm_db->insert($sql);

		return $id;

	}

	function getArticle()
	{

		global $asm_db,$order_sql,$pg;

		$sql = "select * from `".TABLE_PREFIX."article` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid']." and (flag!='Y' or re_inject='Y') ".$order_sql;

		$result = $asm_db->getRS($sql);

		return $result;

	}

	function getDirectory()



	{



		global $asm_db;



		$sql="select * from `".TABLE_PREFIX."directory` where user_id=-1 or user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];



		



		$result = $asm_db->getRS($sql);



		return $result;



	}



	



	function getDirType($id)



	{



		global $asm_db;



		$sql = "select type from `".TABLE_PREFIX."directory` where id=".$id;



		



		$result = $asm_db->getDataSingleRow($sql);



		return $result; 



	}



	



	function getUrl($id)



	{



		global $asm_db;



		$sql="select * from `".TABLE_PREFIX."url` where directory_id=".$id." and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];



		



		$result = $asm_db->getRS($sql);



		return $result;



	}



	



	function getUrlByDir($id)



	{



		global $asm_db;



		$sql="select url,directory_id from `".TABLE_PREFIX."url` where directory_id=".$id;//." and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];



		



		$result = $asm_db->getDataSingleRow($sql);



		return $result;



	}



	



	function getUrlDir()
	{
		global $asm_db,$order_sql;
 	    $sql="select a.*,b.directory from `".TABLE_PREFIX."url` as a,`".TABLE_PREFIX."directory` as b where b.id=a.directory_id  and a.user_id='".$_SESSION[MSESSION_PREFIX.'sessionuserid']."' ".$order_sql; 
		$result = $asm_db->getRS($sql);
		return $result;
	}



	



	function getUrlDirById($id)



	{



		global $asm_db;



		$sql="select a.*,b.directory from `".TABLE_PREFIX."url` as a,`".TABLE_PREFIX."directory` as b where b.id=a.directory_id and a.id=".$id." and a.user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];



		



		$result = $asm_db->getDataSingleRow($sql);



		return $result;



	}



	



	function getProfile($did,$uid)



	{



		global $asm_db;



		$sql="select * from `".TABLE_PREFIX."profile` where directory_id='".$did."' and url_id='".$uid."'";



		



		$result = $asm_db->getRS($sql);



		return $result;



	}



	



	function updateArticle()



	{



		global $asm_db;



		



		$sql = "update `".TABLE_PREFIX."submission` set url_id='".$_POST['url']."',directory_id='".$_POST['directory']."',category_id='".$_POST['cat']."',`dir_type`='".$_POST['type']."' where id=".$_POST['id'];



			



		$id = $asm_db->modify($sql);



		



		return $id;



	}



	



	function getSubmissionArticle()



	{



		global $asm_db,$order_sql,$pg;



		



		$sql = "select * from `".TABLE_PREFIX."article`  where flag='Y' and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid']." ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;;



		



		$result = $asm_db->getRS($sql);



		return $result;



	}



	



	function getLoginDetail($id)



	{



		global $asm_db;



		



		$sql="select * from `".TABLE_PREFIX."url` where id=".$id;



		



		$result = $asm_db->getDataSingleRow($sql);



		return $result; 



	}



	



	function getCategory()



	{



		global $asm_db;



		



		$sql="select cat_name,cat_id from `".TABLE_PREFIX."category` where directory_id=".$_POST['directory'];



		



		$result = $asm_db->getRS($sql);



		return $result; 



	}



	



	function getCategorybyDIR($dirid)



	{



		global $asm_db;



		



		$sql="select cat_name,cat_id from `".TABLE_PREFIX."category` where url_id=".$dirid;



		



		$result = $asm_db->getRS($sql);



		return $result; 



	}



	



	function getArticleById($id)



	{



		global $asm_db;



		



		$sql="select * from `".TABLE_PREFIX."article` where id=".$id;



		



		$result = $asm_db->getDataSingleRow($sql);



		return $result; 



	}



	



	function getArticleBySubId($id)



	{



		global $asm_db;



		



		$sql="select title,body,summary,keyword from `".TABLE_PREFIX."submission` where id=".$id;



		



		$result = $asm_db->getDataSingleRow($sql);



		return $result; 



	}



	



	function getSubmission($id)



	{



		global $asm_db,$order_sql,$pg;



		



		$sql = "select d.*,b.directory,c.profile_name,c.author,c.author_lname,e.dir_label,e.url from `".TABLE_PREFIX."article` as a,`".TABLE_PREFIX."directory` as b,`".TABLE_PREFIX."profile` as c,`".TABLE_PREFIX."submission` as d,`".TABLE_PREFIX."url` as e where a.user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid']." and  d.isScheduled='Y' and d.directory_id=e.directory_id and d.url_id=e.id and d.directory_id=b.id and d.article_id=a.id and d.article_id='".$id."' and (c.id in (d.profile_id) or c.profile_id in (d.profile_id))" ;



		



		$result = $asm_db->getRS($sql);



		return $result; 



	}



	



	function checkUploadedFile()



	{



		global $asm_db,$key;



		$filename = array();



		$flag = false;



		$mess = array();



	

	



				$uplfilext = $this->getExt($_FILES['importtextzip']['name']);



                                $chkext=false;

				if ($uplfilext != "zip")



				{



                                    if($uplfilext != "txt")

					 $chkext=true;

				} 

                                



				if ($chkext==true)



				{



                                    	$mess[] = "Wrong Format : ".$_FILES['importtextzip']['name'].""; 



				} else if (!($_FILES['importtextzip']['size']==0)) 



					{



						if ($_FILES['importtextzip']['type'] == "application/zip" || $uplfilext == "zip") {



							$archive = new PclZip($_FILES['importtextzip']['tmp_name']);



							$zipfilename = $_FILES['importtextzip']['name'];



							$tempname = $archive->listContent();



							$nooffiles = count($tempname);



							if ($archive->extract("temp_data/") != 0) {



								for ($fno = 0; $fno<$nooffiles; $fno++ ) {



									$source_file = "temp_data/".$tempname[$fno]["stored_filename"];



									$srcext = $this->getExt($source_file);



		



									if (!(strtolower($srcext) == "txt" || strtolower($srcext) == "text"))



									{



										$mess[] = "Wrong Format : ".$tempname[$fno]["stored_filename"]." (in ".$zipfilename.")";



										unlink($source_file); 



									}



									else if (@filesize($source_file)==0)



									{



										$mess[] =  "Bad File : ".$tempname[$fno]["stored_filename"]." (in ".$zipfilename.")";



										@unlink($source_file);



									}



									else



									{



										$filename[] = $source_file;



									}



								}



							}



							else



							{



								$flag = false;  // $archive->extract("temp_articles/") when return zero



							}



						



						}else if ($_FILES['importtextzip']['type'] == "application/txt" || $_FILES['importtextzip']['type'] == "application/text" || $uplfilext == "txt" ||  $uplfilext == "text" ) {



							 $zipfilename = $_FILES['importtextzip']['name'];

							$nooffiles = 1; 

								 $source_file ="temp_data/".$_FILES['importtextzip']['name'];



							if(move_uploaded_file($_FILES['importtextzip']['tmp_name'], $source_file)) 

                                                        {

								 $filename[] = $source_file;

                                                        }

													

						}else { 

	



							$mess[] = "Wrong Format ";



							$flag = false;



						}



						



					}



					else



					{



						$mess[] = "Bad File ";



						$flag = false;  // when file size is ZERO



					}



	



	



		if (count($mess)>0)



		{	



//			$this->showTopOfPage();



			foreach($mess as $msg)



			{



				echo "<br>".$msg;



			}



			echo str_repeat(" ", 4000);



			flush();



			echo "<br>";



			echo "<tr><td align='center'><a href='manage_article.php?process=manage'>Ok</a></td></tr>";



//			$this->showBottomOfPage();



			exit();



		} 



			



		if (count($filename) == 0 && $flag == false)



		 {



			 return false;



		 }



		 else



		 {



		 	$x=0; 



			$bcprcnt=0;



			$bcpprofile=$_POST['f_pennameid'];



			



			$bcdircnt=0;



			$bcpdir=$_POST['chkdir'];



			



			foreach($filename as $f)



			{



				



                                $handle=fopen($f,"r");



				$content=fread($handle,filesize($f));







				$regex = "([^0-9 a-zA-Z`~!@#$%\^&\*\(\)-_=\+\|\\\{\}\[\]:;\"'<>\?/\n\r\t]+)";



	 



				$str1 = preg_replace($regex, "" , $content);



				



				$data1=explode("\n",$str1);



	



				$tit=$data1[0];







				$body="";



				for($i=2;$i<count($data1);$i++)



				{	



					$body.=$data1[$i];



					



				}

                                if(strlen($body)>=200) {

				$pos=strpos($body," ",200);



				$summ=substr($body,0,$pos); } else $summ=$body;



				$sum=$summ.".....";



				//print_r($_POST['f_pennameid']); 



				



	



					 $sql = "INSERT INTO `".TABLE_PREFIX."article` ( `title` , `summary` , `body` , `flag`,`keyword`,`user_id`)



				VALUES (



				".$asm_db->GetSQLValueString(addslashes($tit),"text").",



				".$asm_db->GetSQLValueString(addslashes($sum),"text").",



				".$asm_db->GetSQLValueString(addslashes($body),"text").",



				".$asm_db->GetSQLValueString('Y',"text").",".$asm_db->GetSQLValueString($_POST['keyword'],"text").",".$asm_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int").")";



				



				$id = $asm_db->insert($sql);



				



				



//------------------------------------------------------------------------------------------------------------------------	

	









				$today = date("Y-m-d");



				if(count($_POST['chkdir'])>1)



				{	



					if($_POST['filter']=="A")



					{	



						for($i=0;$i<count($_POST['chkdir']);$i++)



						{



							$value = $_POST['chkdir'][$i];



							$url_id = $_POST['url'.$value];



							$cat_id = $_POST['cat'.$value];



							$dir_type = $_POST['dirtype'.$value];



							//echo $dir_id."<br>";



							shuffle($_POST['f_pennameid']);



							$attach_profile=$_POST['f_pennameid'][0];



							if($_POST['profile']=="A")



							{	



								



								//$attach_profile = implode(",",$_POST['f_pennameid']);



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,`user_id`) values("'.addslashes($tit).'","'.addslashes($sum).'","'.addslashes($body).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$attach_profile.'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



								



							}



							else



							{	



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id) values("'.addslashes($tit).'","'.addslashes($sum).'","'.addslashes($body).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$bcpprofile[$bcprcnt].'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



										



								unset($bcpprofile[$bcprcnt]);



								$bcprcnt++; 



								if(count($bcpprofile)==0)



								{



									$bcpprofile=$_POST['f_pennameid'];



									$bcprcnt=0;



								}



							}



						$sid = $asm_db->insert($sql);



						//$key->keywordgenerator();	

 						/*$sql="select max(id) from `".TABLE_PREFIX."article`";

						 $rs = $asm_db->getDataSingleRow($sql);

						 $id=$rs['max(id)'];

//                                                  $sql1="select keyword from `".TABLE_PREFIX."article` where id=".$id;

// 						$res= $asm_db->getDataSingleRow($sql1);

// 						$tit=$res['keyword'];

                                               $tit=$_POST['keyword'];

                                             

						$sql = "update `".TABLE_PREFIX."article` set keyword='".addslashes($tit)."' where id=".$id;

						$asm_db->modify($sql)*/;

						}



					}



					else



					{	



						/*$bcdircnt=0;	$bcpdir=$_POST['f_pennameid']; */



							$value = $bcpdir[$bcdircnt];



							$url_id = $_POST['url'.$value];



							$cat_id = $_POST['cat'.$value];



							$dir_type = $_POST['dirtype'.$value];



							



							shuffle($_POST['f_pennameid']);



							$attach_profile=$_POST['f_pennameid'][0];



							



							if($_POST['profile']=="A")



							{	



								//$attach_profile = implode(",",$_POST['f_pennameid']);



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id) values("'.addslashes($tit).'","'.addslashes($sum).'","'.addslashes($body).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$attach_profile.'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



							}



							else



							{



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id) values("'.addslashes($tit).'","'.addslashes($sum).'","'.addslashes($body).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$bcpprofile[$bcprcnt].'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



								unset($bcpprofile[$bcprcnt]);



								$bcprcnt++; 



								if(count($bcpprofile)==0)



								{



									$bcpprofile=$_POST['f_pennameid'];



									$bcprcnt=0;



								}



							}



							



							$sid = $asm_db->insert($sql);



							//$key->keywordgenerator();

// 						$sql="select max(id) from `".TABLE_PREFIX."article`";

// 						 $rs = $asm_db->getDataSingleRow($sql);

// 						 $id=$rs['max(id)'];

// //                                                  $sql1="select keyword from `".TABLE_PREFIX."article` where id=".$id;

// // 						$res= $asm_db->getDataSingleRow($sql1);

// // 						$tit=$res['keyword'];

//                                                $tit=$_POST['keyword'];

//                                              

// 						$sql = "update `".TABLE_PREFIX."article` set keyword='".addslashes($tit)."' where id=".$id;

// 						$asm_db->modify($sql);

							



								unset($bcpdir[$bcdircnt]);



								$bcdircnt++; 



								if(count($bcpdir)==0)



								{



									$bcpdir=$_POST['f_pennameid'];



									$bcdircnt=0;



								}



					



					}



				}



				else



				{



						for($i=0;$i<count($_POST['chkdir']);$i++)



						{



							$value = $_POST['chkdir'][$i];



							$url_id = $_POST['url'.$value];



							$cat_id = $_POST['cat'.$value];



							$dir_type = $_POST['dirtype'.$value];



							



							shuffle($_POST['f_pennameid']);



							$attach_profile=$_POST['f_pennameid'][0];



							//echo $dir_id."<br>";



							if($_POST['profile']=="A")



							{



								//$attach_profile = implode(",",$_POST['f_pennameid']);



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id) values("'.addslashes($tit).'","'.addslashes($sum).'","'.addslashes($body).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$attach_profile.'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



							}



							else



							{



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id) values("'.addslashes($tit).'","'.addslashes($sum).'","'.addslashes($body).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$bcpprofile[$bcprcnt].'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



								unset($bcpprofile[$bcprcnt]);



								$bcprcnt++; 



								if(count($bcpprofile)==0)



								{



									$bcpprofile=$_POST['f_pennameid'];



									$bcprcnt=0;



								}



							}



							$sid = $asm_db->insert($sql);



							//$key->keywordgenerator();

//                                                         $sql="select max(id) from `".TABLE_PREFIX."article`";

// 						 $rs = $asm_db->getDataSingleRow($sql);

// 						 $id=$rs['max(id)'];

// //                                                  $sql1="select keyword from `".TABLE_PREFIX."article` where id=".$id;

// // 						$res= $asm_db->getDataSingleRow($sql1);

// // 						$tit=$res['keyword'];

//                                                $tit=$_POST['keyword'];

//                                              

// 						$sql = "update `".TABLE_PREFIX."article` set keyword='".addslashes($tit)."' where id=".$id;

// 						$asm_db->modify($sql);



						}				



				



				}



				



				



				//$sid = $asm_db->insert($sql);



//---------------------------------------------------------------------------------------------------------------------------------			



				$arr[$x] = $id;



				$x++;



				



			}



				shuffle($arr);			



				$scount = $_POST['scount'];



				$sday = $_POST['sday']; 



			



				$k=0;



				foreach($arr as $id)



				{



					$newarr[$k] = $id;



					$k++;



				}



				$j=0;



				if($sday=="D" || $sday=="M") $dwm=1; else $dwn=0;



				for($i=0;$i<count($newarr);$i++)



				{



					if($i==0) if($sday=="D" || $sday=="M") $dwm++; else $dwm=$dwm+7;



					if($i<$scount)



					{	



						//  	echo "No.--".$i."--".$newarr[$i]."<br>"; 						



							$tm =mktime(0, 0, 0, date('m'), date('d')+1, date('Y'));



						    $date = date('Y-m-d',$tm);



						//	echo $date."<br>";



					}



					else



					{	



						if($i==$scount && $sday=="M") $dwm--;



						if(!($j<$scount))



						{ 



						  	$j=0;							



						  	if($sday=="D" || $sday=="M") 



						 		$dwm++; else $dwm=$dwm+7;



						}



						$j++;



					   	if($sday=="D" || $sday=="M")



					  	{		



							if($sday=="D")



								$tm =mktime(0, 0, 0, date('m'), date('d')+$dwm, date('Y'));



							if($sday=="M")



								$tm =mktime(0, 0, 0, date('m')+$dwm, date('d')+1, date('Y'));



							$date = date('Y-m-d',$tm);



							//echo $date."<br>";



						  }



						  if($sday=="W")



						  { 



							//echo "No.--".$i."--".$newarr[$i]."<br>"; 



							$tm =mktime(0, 0, 0, date('m'), date('d')+1+$dwm, date('Y'));



							$date = date('Y-m-d',$tm);



							//echo $date."<br>";



						  }



					}



					



					$sql = "update `".TABLE_PREFIX."submission` set schedule='".$date."',isScheduled='Y',start_date='".$today."' where article_id=".$newarr[$i];



					$asm_db->modify($sql); 



				}//die();



		 }



		return true;



	 }



	 



	function getExt($file)



	{



	return strtolower(strrev(substr(strrev($file),0,strpos(strrev($file),"."))));



	}



	



	function getArticleBySchedule()



	{



		global $asm_db;



		



		$today = date("Y-m-d");



		



		//$sql = "select a.title,a.summary,a.body,a.keyword,b.author,b.author_lname,b.biography_html,b.biography,c.* from `".TABLE_PREFIX."article` as a,`".TABLE_PREFIX."profile` as b,`".TABLE_PREFIX."submission` as c where c.schedule='".$today."' and c.article_id=a.id and (b.id in (c.profile_id) or b.profile_id in (c.profile_id))";



		



		$sql = "select b.author,b.author_lname,b.biography_html,b.biography,c.* from `".TABLE_PREFIX."profile` as b,`".TABLE_PREFIX."submission` as c where c.isSubmit='N' and c.schedule<='".$today."' and (b.id in (c.profile_id)) and b.user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];



		



		$result = $asm_db->getRS($sql);



		return $result; 



	}

function check_duplicate($ar_title)

 {

            global $asm_db;

         	if(count($ar_title)>1) 

            $str_title=implode($ar_title,",");

            else

              $str_title="'".$ar_title."'";

 

           $sql="SELECT count(*) as total FROM ".TABLE_PREFIX."article WHERE `title` in (".$str_title.") and user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

           $data=$asm_db->getDataSingleRow($sql);

           return $data['total'];

}

 

	function get_url($url)

	{

			@$ch = curl_init();

			curl_setopt ($ch, CURLOPT_URL, $url);

			curl_setopt ($ch, CURLOPT_HEADER, 0);

			ob_start();

			@curl_exec ($ch);

			$errnum=curl_errno($ch);

			@curl_close ($ch);

			if($errnum != "0") {  

				return false;	

			} else {

					$content = ob_get_contents();

					ob_end_clean();

					return $content;

				}

			

	}


function importArticleCW()
{
	global $asm_db,$key;
	$x=0; 
	$bcprcnt=0;
	$bcpprofile=$_POST['f_pennameid'];
	$bcdircnt=0;
	$bcpdir=$_POST['chkdir'];
	$numimport=1;
	
	$chk=$_POST['chk'];
	$x=0; 
	
	for($i=0;$i<count($chk);$i++){
		$sql="SELECT * from `hct_am_article` where id=".$chk[$i];
		$data=$asm_db->getDataSingleRow($sql);
		//echo $sql;
		$sql = "INSERT INTO `".TABLE_PREFIX."article` (`title`, `summary`,`body`, `keyword`,`flag`,`user_id`) VALUES (
		".$asm_db->GetSQLValueString($data['title'],"text").",
		".$asm_db->GetSQLValueString($data['summary'],"text").",
		".$asm_db->GetSQLValueString($data['body'],"text").",
		".$asm_db->GetSQLValueString($_POST['keyword'],"text").",
		".$asm_db->GetSQLValueString('F',"text").",
		".$asm_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int").")";
		$id=$asm_db->insert($sql);
		
		
		
		$today = date("Y-m-d");
		if(count($_POST['chkdir'])>1)
		{
					if($_POST['filter']=="A")
					{	
						for($j=0;$j<count($_POST['chkdir']);$j++)
						{
							$value = $_POST['chkdir'][$j];
							$url_id = $_POST['url'.$value];
							$cat_id = $_POST['cat'.$value];
							$dir_type = $_POST['dirtype'.$value];
							//echo $dir_id."<br>";
							//shuffle($_POST['f_pennameid']);
							$attach_profile=$_POST['f_pennameid'][0];
							if($_POST['profile']=="A")
							{	
								//$attach_profile = implode(",",$_POST['f_pennameid']);
								/*/###############################################################3
							// Code to attach profile
							
							$sqlA = "select count(*) as total,id from `".TABLE_PREFIX."article_profile` where article_id=".$id." GROUP BY id";
							$count = $asm_db->getData($sqlA);
							if($count[0]['total']>0)
							{
								$sql = "update `".TABLE_PREFIX."article_profile` set profile_id='".$attach_profile."' where id=".$count[0]['id'];
								$asm_db->modify($sql);
								
							}
							else
							{
								$sql = "insert into `".TABLE_PREFIX."article_profile`(article_id,profile_id) values('".$id."','".$attach_profile."')";
								$id = $asm_db->insert($sql);
							}
							
							
							//################################################################*/
							
								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id,start_date,isScheduled,isProcess) values("'.addslashes($data['title']).'","'.addslashes(strip_tags($data['summary'])).'","'.addslashes(strip_tags($data['body'])).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$attach_profile.'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'","'.$today.'","Y","Y")';
							}
							else
							{	
								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id,start_date,isScheduled,isProcess) values("'.addslashes($data['title']).'","'.addslashes(strip_tags($data['summary'])).'","'.addslashes(strip_tags($data['body'])).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$bcpprofile[$bcprcnt].'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'","'.$today.'","Y","Y")';
								unset($bcpprofile[$bcprcnt]);
								$bcprcnt++; 
								if(count($bcpprofile)==0)
								{
									$bcpprofile=$_POST['f_pennameid'];
									$bcprcnt=0;
								}
							}
							$sid = $asm_db->insert($sql);
							//$key->keywordgenerator();	
							$sql="select max(id) from `".TABLE_PREFIX."article` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
							$rs = $asm_db->getDataSingleRow($sql);
							$id=$rs['max(id)']; 
							
							$tit1=$_POST['keyword'];
							$sql = "update `".TABLE_PREFIX."article` set keyword='".addslashes($tit1)."', flag='Y' where id=".$id;
							$asm_db->modify($sql);
					  }
					}
					else
					{	
						/*$bcdircnt=0;	$bcpdir=$_POST['f_pennameid']; */
						$value = $bcpdir[$bcdircnt];
						$url_id = $_POST['url'.$value];
						$cat_id = $_POST['cat'.$value];
						$dir_type = $_POST['dirtype'.$value];
						//shuffle($_POST['f_pennameid']);
						$attach_profile=$_POST['f_pennameid'][0];
						if($_POST['profile']=="A")
						{	
						//$attach_profile = implode(",",$_POST['f_pennameid']);
						/*/###############################################################3
							// Code to attach profile
							
							$sqlA = "select count(*) as total,id from `".TABLE_PREFIX."article_profile` where article_id=".$id." GROUP BY id";
							$count = $asm_db->getData($sqlA);
							if($count[0]['total']>0)
							{
								$sql = "update `".TABLE_PREFIX."article_profile` set profile_id='".$attach_profile."' where id=".$count[0]['id'];
								$asm_db->modify($sql);
								
							}
							else
							{
								$sql = "insert into `".TABLE_PREFIX."article_profile`(article_id,profile_id) values('".$id."','".$attach_profile."')";
								$idA = $asm_db->insert($sql);
							}
							
							
							//################################################################*/
							
						$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id,start_date,isScheduled,isProcess) values("'.addslashes($data['title']).'","'.addslashes(strip_tags($data['summary'])).'","'.addslashes(strip_tags($data['body'])).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$attach_profile.'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'","'.$today.'","Y","Y")';
						}
						else
						{
							$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id,start_date,isScheduled,isProcess) values("'.addslashes($data['title']).'","'.addslashes(strip_tags($data['summary'])).'","'.addslashes(strip_tags($data['body'])).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$bcpprofile[$bcprcnt].'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'","'.$today.'","Y","Y")';
							unset($bcpprofile[$bcprcnt]);
							$bcprcnt++; 
							if(count($bcpprofile)==0)
							{
								$bcpprofile=$_POST['f_pennameid'];
								$bcprcnt=0;
							}
						}
							$sid = $asm_db->insert($sql);
							//$key->keywordgenerator();
							$sql="select max(id) from `".TABLE_PREFIX."article`";
							$rs = $asm_db->getDataSingleRow($sql);
							$id=$rs['max(id)']; die();
							$tit1=$_POST['keyword'];
							
							$sql = "update `".TABLE_PREFIX."article` set keyword='".addslashes($tit1)."', flag='Y' where id=".$id;
							$asm_db->modify($sql);
							unset($bcpdir[$bcdircnt]);
							$bcdircnt++; 
							if(count($bcpdir)==0)
							{
								$bcpdir=$_POST['f_pennameid'];
								$bcdircnt=0;
							}
						}
				
				}
		else
		{
			for($k=0;$k<count($_POST['chkdir']);$k++)
			{
						$value = $_POST['chkdir'][$k];
						$url_id = $_POST['url'.$value];
						$cat_id = $_POST['cat'.$value];
						$dir_type = $_POST['dirtype'.$value];
						//shuffle($_POST['f_pennameid']);
						$attach_profile=$_POST['f_pennameid'][0];
						//echo $dir_id."<br>";
						if($_POST['profile']=="A")
						{
							//$attach_profile = implode(",",$_POST['f_pennameid']);='"*/
							$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id,start_date,isScheduled,isProcess) values("'.addslashes($data['title']).'","'.addslashes(strip_tags($data['summary'])).'","'.addslashes(strip_tags($data['body'])).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$attach_profile.'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'","'.$today.'","Y","Y")';
							
							//echo "<br/>";
						}
						else
						{
							$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id,start_date,isScheduled,isProcess) values("'.addslashes($data['title']).'","'.addslashes(strip_tags($data['summary'])).'","'.addslashes(strip_tags($data['body'])).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$bcpprofile[$bcprcnt].'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'","'.$today.'","Y","Y")';
							
							//echo "<hr>";
							unset($bcpprofile[$bcprcnt]);
							$bcprcnt++; 
							if(count($bcpprofile)==0)
							{
								$bcpprofile=$_POST['f_pennameid'];
								$bcprcnt=0;
							}
						}
						
							
							$sid = $asm_db->insert($sql);
							//$key->keywordgenerator();
							$sql="select max(id) from `".TABLE_PREFIX."article` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
							$rs = $asm_db->getDataSingleRow($sql);
							$idX=$rs['max(id)'];
							$tit1=$_POST['keyword'];
							$sql = "update `".TABLE_PREFIX."article` set keyword='".addslashes($tit1)."', flag='Y' where id=".$idX;
							$asm_db->modify($sql);
					}				
		}
		
		$arr[$x] = $id;
		$x++;
			
	}
	
	shuffle($arr);			
	$scount = $_POST['scount'];
	$sday = $_POST['sday']; 
	$k=0;
	foreach($arr as $id)
	{
		$newarr[$k] = $id;
		$k++;
	}
	$j=0;
	if($sday=="D" || $sday=="M") $dwm=1; else $dwn=0;
	for($i=0;$i<count($newarr);$i++)
	{
		if($i==0){ 
		if($sday=="D" || $sday=="M")
			 $dwm++; 
		else 
			$dwm=$dwm+7;
		}	
	if($i<$scount)
	{	
		//  	echo "No.--".$i."--".$newarr[$i]."<br>"; 						
		$tm =mktime(0, 0, 0, date('m'), date('d')+1, date('Y'));
		$date = date('Y-m-d',$tm);
		//	echo $date."<br>";
	}
	else
	{	
		if($i==$scount && $sday=="M") $dwm--;
		if(!($j<$scount))
		{ 
			$j=0;							
			if($sday=="D" || $sday=="M") 
			$dwm++; else $dwm=$dwm+7;
		}
		$j++;
		if($sday=="D" || $sday=="M")
		{		
			if($sday=="D")
			$tm =mktime(0, 0, 0, date('m'), date('d')+$dwm, date('Y'));
			if($sday=="M")
			$tm =mktime(0, 0, 0, date('m')+$dwm, date('d')+1, date('Y'));
			$date = date('Y-m-d',$tm);
			//echo $date."<br>";
		}
		if($sday=="W")
		{ 
			//echo "No.--".$i."--".$newarr[$i]."<br>"; 
			$tm =mktime(0, 0, 0, date('m'), date('d')+1+$dwm, date('Y'));
			$date = date('Y-m-d',$tm);
			//echo $date."<br>";
		}
	}
	
	$sql = "update `".TABLE_PREFIX."submission` set schedule='".$date."',isScheduled='Y',start_date='".$today."',keyword='".addslashes($_POST['keyword'])."' where article_id=".$newarr[$i];
	$asm_db->modify($sql);
 }		
	return true;
}


function fetch_by_rss()
{
     

	global $asm_db,$key;

 		global $objXML,$key;

        global $arrtitles;

	global $arrdescs;

	global $arrlinks;



                 $total_import=$_POST['counterrss'];

                show1($_POST['importtextzip']);



		// $xmldata=$this->get_url();

//echo count($arrtitles);

			if(count($arrtitles)>0)	{  

			//$arrOutput = $objXML->parse($xmldata);

	//echo "sdnfjshduof";

		 	$x=0; 



			$bcprcnt=0;



			$bcpprofile=$_POST['f_pennameid'];



			



			$bcdircnt=0;



			$bcpdir=$_POST['chkdir'];



			$numimport=1;



			for($g=1;$g<sizeof($arrtitles);$g++)



			{



//echo count($arrtitles); //die();

	        if( $numimport<=$total_import)

		{



				 $tit=$arrtitles[$g]["TITLE"];

                               

                          $cnm=$this->check_duplicate(addslashes($tit)); 

                         

                          if($cnm==0)

                          {

     

                               $numimport++;

				$body=html_entity_decode(strip_tags($arrdescs[$g]["DESCRIPTION"]));



if(strlen($body)>=200) {

				$pos=@strpos($body," ",200);



				$summ=substr($body,0,$pos); } else $summ=$body;



				



				



				$sum=$summ.".....";



			  $sql = "INSERT INTO `".TABLE_PREFIX."article` ( `title` , `summary` , `body` , `flag`,`user_id`)



				VALUES (



				".$asm_db->GetSQLValueString(addslashes($tit),"text").",



				".$asm_db->GetSQLValueString(addslashes(html_entity_decode(strip_tags($sum))),"text").",



				".$asm_db->GetSQLValueString(addslashes(strip_tags($body)),"text").",



				".$asm_db->GetSQLValueString('Y',"text").",

				".$asm_db->GetSQLValueString($_SESSION[MSESSION_PREFIX.'sessionuserid'],"int").")";



				



				$id = $asm_db->insert($sql);



				



				



//------------------------------------------------------------------------------------------------------------------------	











				$today = date("Y-m-d");
				if(count($_POST['chkdir'])>1)
				{	
					if($_POST['filter']=="A")
					{	
						for($i=0;$i<count($_POST['chkdir']);$i++)
						{



							$value = $_POST['chkdir'][$i];



							$url_id = $_POST['url'.$value];



							$cat_id = $_POST['cat'.$value];



							$dir_type = $_POST['dirtype'.$value];



							//echo $dir_id."<br>";



							shuffle($_POST['f_pennameid']);



							$attach_profile=$_POST['f_pennameid'][0];



							if($_POST['profile']=="A")



							{	



								



								//$attach_profile = implode(",",$_POST['f_pennameid']);



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id) values("'.addslashes($tit).'","'.addslashes(strip_tags($sum)).'","'.addslashes(strip_tags($body)).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$attach_profile.'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



								



							}



							else



							{	



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id) values("'.addslashes($tit).'","'.addslashes(strip_tags($sum)).'","'.addslashes(strip_tags($body)).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$bcpprofile[$bcprcnt].'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



										



								unset($bcpprofile[$bcprcnt]);



								$bcprcnt++; 



								if(count($bcpprofile)==0)



								{



									$bcpprofile=$_POST['f_pennameid'];



									$bcprcnt=0;



								}



							}



						$sid = $asm_db->insert($sql);



						//$key->keywordgenerator();	





                                                 $sql="select max(id) from `".TABLE_PREFIX."article` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

						 $rs = $asm_db->getDataSingleRow($sql);

					  $id=$rs['max(id)']; 

//                                                  $sql1="select keyword from `".TABLE_PREFIX."article` where id=".$id;

// 						$res= $asm_db->getDataSingleRow($sql1);

// 						$tit=$res['keyword'];

                                                 $tit1=$_POST['keyword'];

                                                

					 $sql = "update `".TABLE_PREFIX."article` set keyword='".addslashes($tit1)."' where id=".$id;

						$asm_db->modify($sql);



						}



					}



					else



					{	



						/*$bcdircnt=0;	$bcpdir=$_POST['f_pennameid']; */



							$value = $bcpdir[$bcdircnt];



							$url_id = $_POST['url'.$value];



							$cat_id = $_POST['cat'.$value];



							$dir_type = $_POST['dirtype'.$value];



							



							shuffle($_POST['f_pennameid']);



							$attach_profile=$_POST['f_pennameid'][0];



							



							if($_POST['profile']=="A")



							{	



								//$attach_profile = implode(",",$_POST['f_pennameid']);



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id) values("'.addslashes($tit).'","'.addslashes(strip_tags($sum)).'","'.addslashes(strip_tags($body)).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$attach_profile.'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



							}



							else



							{



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id) values("'.addslashes($tit).'","'.addslashes(strip_tags($sum)).'","'.addslashes(strip_tags($body)).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$bcpprofile[$bcprcnt].'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



								unset($bcpprofile[$bcprcnt]);



								$bcprcnt++; 



								if(count($bcpprofile)==0)



								{



									$bcpprofile=$_POST['f_pennameid'];



									$bcprcnt=0;



								}



							}



							



							$sid = $asm_db->insert($sql);



							//$key->keywordgenerator();

						 $sql="select max(id) from `".TABLE_PREFIX."article`";

						 $rs = $asm_db->getDataSingleRow($sql);

						 $id=$rs['max(id)']; die();

//                                                  $sql1="select keyword from `".TABLE_PREFIX."article` where id=".$id;

// 						$res= $asm_db->getDataSingleRow($sql1);

// 						$tit1=$res['keyword'];

                                               $tit1=$_POST['keyword'];

                                             

						$sql = "update `".TABLE_PREFIX."article` set keyword='".addslashes($tit1)."' where id=".$id;

						$asm_db->modify($sql);

							



								unset($bcpdir[$bcdircnt]);



								$bcdircnt++; 



								if(count($bcpdir)==0)



								{



									$bcpdir=$_POST['f_pennameid'];



									$bcdircnt=0;



								}



					



					}



				}



				else



				{



						for($i=0;$i<count($_POST['chkdir']);$i++)



						{



							$value = $_POST['chkdir'][$i];



							$url_id = $_POST['url'.$value];



							$cat_id = $_POST['cat'.$value];



							$dir_type = $_POST['dirtype'.$value];



							



							shuffle($_POST['f_pennameid']);



							$attach_profile=$_POST['f_pennameid'][0];



							//echo $dir_id."<br>";



							if($_POST['profile']=="A")



							{



								//$attach_profile = implode(",",$_POST['f_pennameid']);



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id) values("'.addslashes($tit).'","'.addslashes(strip_tags($sum)).'","'.addslashes(strip_tags($body)).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$attach_profile.'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



							}



							else



							{



								$sql = 'insert into `'.TABLE_PREFIX.'submission` (title,summary,body,directory_id,url_id,category_id,article_id,profile_id,dir_type,user_id) values("'.addslashes($tit).'","'.addslashes(strip_tags($sum)).'","'.addslashes(strip_tags($body)).'","'.$value.'","'.$url_id.'","'.$cat_id.'","'.$id.'","'.$bcpprofile[$bcprcnt].'","'.$dir_type.'","'.$_SESSION[MSESSION_PREFIX.'sessionuserid'].'")';



								unset($bcpprofile[$bcprcnt]);



								$bcprcnt++; 



								if(count($bcpprofile)==0)



								{



									$bcpprofile=$_POST['f_pennameid'];



									$bcprcnt=0;



								}



							}



							$sid = $asm_db->insert($sql);



							//$key->keywordgenerator();

                                                        $sql="select max(id) from `".TABLE_PREFIX."article` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

						 $rs = $asm_db->getDataSingleRow($sql);

						 $id=$rs['max(id)'];

//                                                  $sql1="select keyword from `".TABLE_PREFIX."article` where id=".$id;

// 						$res= $asm_db->getDataSingleRow($sql1);

// 						$tit=$res['keyword'];

                                                 $tit1=$_POST['keyword'];

                                                

						$sql = "update `".TABLE_PREFIX."article` set keyword='".addslashes($tit1)."' where id=".$id;

						$asm_db->modify($sql);



						}				



				



				}



				



				



				//$sid = $asm_db->insert($sql);



//---------------------------------------------------------------------------------------------------------------------------------			



				$arr[$x] = $id;



				$x++;

                         }



			}	



			}



				shuffle($arr);			



				$scount = $_POST['scount'];



				$sday = $_POST['sday']; 



			



				$k=0;



				foreach($arr as $id)



				{



					$newarr[$k] = $id;



					$k++;



				}



				$j=0;



				if($sday=="D" || $sday=="M") $dwm=1; else $dwn=0;



				for($i=0;$i<count($newarr);$i++)



				{



					if($i==0) if($sday=="D" || $sday=="M") $dwm++; else $dwm=$dwm+7;



					if($i<$scount)



					{	



						//  	echo "No.--".$i."--".$newarr[$i]."<br>"; 						



							$tm =mktime(0, 0, 0, date('m'), date('d')+1, date('Y'));



						    $date = date('Y-m-d',$tm);



						//	echo $date."<br>";



					}



					else



					{	



						if($i==$scount && $sday=="M") $dwm--;



						if(!($j<$scount))



						{ 



						  	$j=0;							



						  	if($sday=="D" || $sday=="M") 



						 		$dwm++; else $dwm=$dwm+7;



						}



						$j++;



					   	if($sday=="D" || $sday=="M")



					  	{		



							if($sday=="D")



								$tm =mktime(0, 0, 0, date('m'), date('d')+$dwm, date('Y'));



							if($sday=="M")



								$tm =mktime(0, 0, 0, date('m')+$dwm, date('d')+1, date('Y'));



							$date = date('Y-m-d',$tm);



							//echo $date."<br>";



						  }



						  if($sday=="W")



						  { 



							//echo "No.--".$i."--".$newarr[$i]."<br>"; 



							$tm =mktime(0, 0, 0, date('m'), date('d')+1+$dwm, date('Y'));



							$date = date('Y-m-d',$tm);



							//echo $date."<br>";



						  }



					}



					



					$sql = "update `".TABLE_PREFIX."submission` set schedule='".$date."',isScheduled='Y',start_date='".$today."',keyword='".addslashes($_POST['keyword'])."' where article_id=".$newarr[$i];



					$asm_db->modify($sql); 



				}//die();



		 }



		return true;



	 }







}





function show1($rss)

  {

	global $arrtitles;

	global $arrdescs;

	global $arrlinks; global $title;

	global $q_title;

	global $script_base_url;

	//first read the xml file

	$rss=$rss;

	//$rss=str_replace(" ","+",$rss);

	//echo "<LI>Getting file from --".$rss."--";

	if(!($fp=@fopen($rss,"r")))  //open a xml file

		{die("Unable to open XML file");}

	

	if(!($xml_parser=xml_parser_create())) //create parser for xml file

		{die("couldnt create xml parser");}

	xml_set_element_handler($xml_parser,"startelement1","endelement1");

	xml_set_character_data_handler($xml_parser,"characterdata1");



	while($data = fread($fp,4096))

	{

		if(!xml_parse($xml_parser,$data,feof($fp)))

		{

			break;

		}	

	}

xml_parser_free($xml_parser);

/*for($i=0;$i<count($arrtitles);$i++){

echo "<br>".$arrtitles[$i]["TITLE"]."<br>";

    echo     $arrlinks[$i]["LINK"];   

echo $arrdescs[$i]["DESCRIPTION"];

	}*/






function characterdata1($parser, $data) {

	global $count;

	global $last_tag;

	global $current_tag;

	global $arrtitles;

	global $arrdescs;

	global $arrlinks;

    

	if (!$current_tag)

		{return;}



	if ($count<0)

		{return;}

	if($current_tag=="TITLE")

		{

			 $arrtitles[$count]["TITLE"].=$data;



		}

	if($current_tag=="LINK")

		{

		$arrlinks[$count]["LINK"].=$data;

		}

	if($current_tag=="DESCRIPTION")

		{$arrdescs[$count]["DESCRIPTION"].=$data;}

}

function startelement1($parser, $name, $attrs) {

	global $current_tag;

	global $last_tag;

	global $count;

	global $arrtitles;

	global $arrdescs;

	global $arrlinks;



	$last_tag=$current_tag;

	$current_tag=$name;



	if ($current_tag=="ITEM"){

		$count++;

		$arrtitles[$count]["TITLE"]="";

		$arrlinks[$count]["LINK"]="";

		$arrdescs[$count]["DESCRIPTION"]="";



	}

}



function endelement1($parser, $name) {

	global $current_tag;

	$current_tag="";

} 

}


?>