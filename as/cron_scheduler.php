<?php
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/article.class.php");

$asm_db = new Database();
$asm_db->openDB();
$article_obj = new Article();

$article_rs = $article_obj->getArticleBySchedule();
if($article_rs)
{
	$today = date("Y-m-d");
	
	while($article = $asm_db->getNextRow($article_rs))
	{ //echo $article['id']."<br>";
		
		if($article['url_id']!="" || $article['url_id']!=NULL)
		{	
			$login = $article_obj->getLoginDetail($article['url_id']);	
		
			if($article['dir_type']=="AD")
			{
				$str="f_username=".$login['username']."&f_password=".$login['password']."&action=login&B7=Submit";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $login['url']."login2submitart.php");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
				curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($ch);
				
				
				
				$str1="f_penname=".$article['author']."&act=add&submit=Submit";
				curl_setopt($ch, CURLOPT_URL, $login['url']."penname.php");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $str1);
				curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($ch);
				//echo $response;
				
				$content = $response;
				$search   = 'This Pen Name was already used by another Author';
				$pos = strpos($content, $search);
				if($pos == false)
				{
					curl_setopt($ch, CURLOPT_URL, $login['url']."submitarticles.php");
				
					$response = curl_exec($ch);
					//echo $response."<br><br>";
				
					
					$spartstart = '<select size="1" name="f_pennameid"';//for original
					$spartend = '</select>'; 
					$str = str_replace(array("\n","\r","\t"),array("","",""),$response);
					preg_match_all("|(".$spartstart."(.*)".$spartend.")|U",$str, $out1);
					
					$author = strtolower($article["author"]);
		
					$idstart = '<option value="';//for original
					$idend = '</option>'; 
		
					preg_match_all("|(".$idstart."(.*)".$idend.")|U",$out1[1][0], $out2);
		
					for($x=0;$x<count($out2[2]);$x++)
					{
						$string=explode('">',$out2[2][$x]);
						//print_r($string);
						//echo $string[1]."<br>";
						//echo $author."<br>";
						if(strtolower($string[1])==$author)
						{	$penid=$string[0]; 
							break; 
						}
					}
					
					if($article["biography_html"]!="")
					{
						$biography = urlencode(html_entity_decode($article["biography_html"]));
					}
					else
					{
						$biography = urlencode(html_entity_decode($article["biography"]));
					}
					
					$str2="f_pennameid=".$penid."&f_categoryid=".$article['category_id']."&f_arttitle=".urlencode($article['title'])."&f_artsummary=".urlencode(html_entity_decode($article['summary']))."&f_artbody=".urlencode(html_entity_decode($article['body']))."&f_artres=".$biography."&f_artkey=".urlencode($article['keyword'])."&act=add&submit=Submit";
					curl_setopt($ch, CURLOPT_URL, $login['url']."submitarticles.php");
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $str2);
					curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$response = curl_exec($ch);
					//echo "<br><br>".$response; die();
					$content = $response;
					$search   = 'Sorry, this article has already been submitted to our directory. Please submit another article or choose another title';
					
					$pos = strpos($content, $search);
					
					curl_close($ch);
				}
				
				if ($pos === false)
				{
					$sql = "update `".TABLE_PREFIX."submission` set schedule='".$today."',isSubmit='Y',error='N',log='' where id=".$article['id'];
					$asm_db->modify($sql);
				}
				else
				{
					$sql = "update `".TABLE_PREFIX."submission` set schedule='".$today."',error='Y',log='".$search."' where id=".$article['id'];
					
					$asm_db->modify($sql);
				}
			}
			elseif($article['dir_type']=="GA")
			{
				$str="email=".$login['username']."&password=".$login['password']."&SUBMIT=Login";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $login['url']."ulogin.html");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
				curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($ch);
				
				if($article["biography_html"]!="")
				{
					$biography = urlencode(html_entity_decode($article["biography_html"]));
				}
				else
				{
					$biography = urlencode(html_entity_decode($article["biography"]));
				}
				
				$str2="author=".urlencode($article['author'])."&category=".$article['category_id']."&title=".urlencode($article['title'])."&content=".urlencode(html_entity_decode($article['body']))."&bio=".$biography."&password=".$login['password']."&email=".$login['username']."&submit-verify=Add Article..."; 
				curl_setopt($ch, CURLOPT_URL, $login['url']."cgi-bin/add.cgi");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $str2);
				curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($ch);
				//echo "<br><br>".$response; 
				$content = $response;
				$search   = 'We do not accept articles that are less than 50 words long';
				
				$pos = strpos($content, $search);
				
			
				curl_close($ch);
				if ($pos === false)
				{
					$sql = "update `".TABLE_PREFIX."submission` set schedule='".$today."',isSubmit='Y',error='N',log='' where id=".$article['id'];
					$asm_db->modify($sql);
				}
				else
				{
					$sql = "update `".TABLE_PREFIX."submission` set schedule='".$today."',error='Y',log='".$search."' where id=".$article['id'];
					
					$asm_db->modify($sql);
				}
			}
			elseif($article['dir_type']=="EA")
			{
				$name = $article['author']." ".$article['author_lname'];
				
				$str="email=".$login['username']."&pass=".$login['password']."&newlogin=1&jscheck=1";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $login['url']."index.php");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
				curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($ch);
				
				
				curl_setopt($ch, CURLOPT_URL, $login['url']."index.php");
				$response = curl_exec($ch);
				
				$content = $response;
				$search = $name;
				
				$pos = strpos($content, $search);
				
				$idstart = '<input type="hidden" name="id" value="';//for original
				$idend = '"';
				$passstart = '<input type="hidden" name="pass" value="';//for original
				$passend = '"';
			
	//			$startname='<p><strong>';
	//			$endname='</strong>';
				
				$str = str_replace(array("\n","\r","\t"),array("","",""),$response);
				preg_match_all("|(".$idstart."(.*)".$idend.")|U",$str, $out1);
				preg_match_all("|(".$passstart."(.*)".$passend.")|U",$str, $out2);
	//			preg_match_all("|(".$startname."(.*)".$endname.")|U",$str, $out3);
				
				if ($pos == false)
				{
					$str2="pass=".$out2[2][0]."&id=".$out1[2][0]."&add_alt_author_first=".urlencode($article['author'])."&add_alt_author_last=".urlencode($article['author_lname'])."&verify=1&add_alt=1&act=addAltAuth";
					curl_setopt($ch, CURLOPT_URL, $login['url']."profile-manager/add_alternate.php");
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $str2);
					curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$response = curl_exec($ch);
					
					$content = $response;
					$search = $name.' is already in use.';
					
					$position = strpos($content, $search);
				}
				
				//$content = $response;
				//$search   = '<div id="error_message">'.$name.' is already in use.<br>';
			//	print_r($out3); die();
				if($pos != false || $position == false)
				{
						if($article["biography_html"]!="")
						{
							$biography = urlencode(html_entity_decode($article["biography_html"]));
						}
						else
						{
							$biography = urlencode(html_entity_decode($article["biography"]));
						}
				
					$str2="category=".$article['category_id']."&title=".urlencode($article['title'])."&body=".urlencode(html_entity_decode($article['body']))."&sig=".$biography."&summary=".urlencode(html_entity_decode($article['summary']))."&keywords=".urlencode($article['keyword'])."&article_author=".urlencode($article['author'])." ".urlencode($article['author_lname'])."&agree=1&add_entry=1";
					//die($str2);
					///die($login['url']."submit.php?id=".$out1[2][0]."&pass=".$out2[2][0].""); 
					curl_setopt($ch, CURLOPT_URL, $login['url']."submit.php?id=".$out1[2][0]."&pass=".$out2[2][0]."");
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $str2);
					curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$response = curl_exec($ch);
					
					$content = $response;
					$search = '<div id="error_message">';
					
					$error_pos = strpos($content, $search);
				
					if($error_pos == false)
					{
						$sql = "update `".TABLE_PREFIX."submission` set schedule='".$today."',isSubmit='Y' where id=".$article['id'];
						$asm_db->modify($sql);
					}
					else
					{				
						$errorstart = '<div id="error_message">';//for original
						$errorend = '</div>';
						
						$str = str_replace(array("\n","\r","\t"),array("","",""),$response);
						preg_match_all("|(".$errorstart."(.*)".$errorend.")|U",$str, $out1);
						//echo $out1[2][0];
						
						$log=str_replace('"',' ',$out1[2][0]);
						
						$sql = 'update `'.TABLE_PREFIX.'submission` set schedule="'.$today.'",error="Y",log="'.$log.'" where id='.$article["id"];
					
						$asm_db->modify($sql);
					}
				//echo $response; die();
					curl_close($ch);
					
					
				}
				else
				{
					$search = "Author name Name [".$name."] is already used by another user";
					$sql = "update `".TABLE_PREFIX."submission` set schedule='".$today."',error='Y',log='".$search."' where id=".$article['id'];
					
					$asm_db->modify($sql);
				}
			}
		}
		
	}
}

?>