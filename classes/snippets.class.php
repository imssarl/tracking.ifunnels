<?php
     class Snippet
     {
		 
         function insertSnippet()
         {
			 global $ms_db;
			 $sql = "INSERT INTO `".TABLE_PREFIX."snippets` ( `title` , `description` , `is_itm_enabled` , `created_date`,`user_id` )
			 VALUES ("
			 ."'".$ms_db->GetSQLValueString($_POST["title"],"text")."',"
			 ."'".$ms_db->GetSQLValueString($_POST["description"],"text")."',"
			 ."'".$ms_db->GetSQLValueString($_POST["is_itm_enabled"],"text")."',"
			 ."'".$ms_db->GetSQLValueString($_POST["created_date"],"date")."',"
			 ."'".$ms_db->GetSQLValueString($_SESSION[SESSION_PREFIX.'sessionuserid'],"int").
			 "')";
			 //echo $sql;
			 $id = $ms_db->insert($sql);
			 return $id;	
         }
		 
         function insertSnippetPart()
         {
			 global $ms_db;
			 
			 if ($_POST['reset_css'] == 1) { // Добавить css
			 	$_POST['link'] = '<div class="cnmBanner">'.$_POST['link'].'</div>';
			 	$_POST['link'] = $this->getCssCode().$_POST['link'];
			 }
			 $sql = "INSERT INTO `".TABLE_PREFIX."snippet_parts` ( `snippet_id` , `link` , `created_date`, `inputmode`, `reset_css`  )
			 VALUES ("
			 ."'".$ms_db->GetSQLValueString($_POST["snippet_id"],"text")."',"
			 ."'".$ms_db->GetSQLValueString($_POST["link"],"text")."',"
			 ."'".$ms_db->GetSQLValueString($_POST["created_date"],"date")."',"
			 ."'".$_POST["inputmode"]."',"
			 ."'{$_POST['reset_css']}')";
			 $id = $ms_db->insert($sql);
			 $link = $this->changeLinksWithTrackURLs($_POST["link"], $id);
			 /*
			 $sql = "UPDATE `".TABLE_PREFIX."snippet_parts` SET
			 `link` = '".$ms_db->GetSQLValueString($link,"text")."'
			 WHERE `id` = ".$id;
			 $uid = $ms_db->modify($sql);
			 */
			 return $id;
         }

         function getCssCode() {
         	return $css = '<style title="reset"  type="text/css">
.cnmBanner div, .cnmBanner span, .cnmBanner applet, .cnmBanner object, .cnmBanner iframe, .cnmBanner h1, .cnmBanner h2, .cnmBanner h3, .cnmBanner h4,.cnmBanner  h5, .cnmBanner h6, 
.cnmBanner p, .cnmBanner blockquote, .cnmBanner pre,.cnmBanner a, .cnmBanner abbr, .cnmBanner acronym, .cnmBanner address, .cnmBanner big, .cnmBanner cite, 
.cnmBanner code, .cnmBanner del, .cnmBanner dfn, .cnmBanner em, .cnmBanner font, .cnmBanner img, .cnmBanner ins, .cnmBanner kbd, .cnmBanner q, .cnmBanner s, .cnmBanner samp,
.cnmBanner small, .cnmBanner strike, .cnmBanner strong, .cnmBanner sub, .cnmBanner sup, .cnmBanner tt, .cnmBanner var,.cnmBanner dl, .cnmBanner dt, .cnmBanner dd, 
.cnmBanner ol, .cnmBanner ul, .cnmBanner li, .cnmBanner fieldset, .cnmBanner form, .cnmBanner label, .cnmBanner legend,.cnmBanner table, .cnmBanner caption, .cnmBanner tbody,
.cnmBanner  tfoot, .cnmBanner thead, .cnmBanner tr, .cnmBanner th, .cnmBanner td {
margin: 0;
padding: 0;
border: 0;
outline: 0;
font-weight: inherit;
font-style: inherit;
font-size: 100%;
font-family: inherit;
vertical-align: baseline;
background:none;
color:#000;
}
/* remember to define focus styles! */
.cnmBanner :focus {
outline: 0;
}
.cnmBanner ol, .cnmBanner ul {
list-style: none;
}
/* tables still need \'cellspacing="0"\' in the markup */
.cnmBanner table {
border-collapse: separate;
border-spacing: 0;
}
.cnmBanner caption, .cnmBanner th, .cnmBanner td {
text-align: left;
font-weight: normal;
}
.cnmBanner blockquote:before, .cnmBanner blockquote:after,
q:before, q:after {
content: "";
}
.cnmBanner blockquote, .cnmBanner q {
quotes: "" "";
}</style><span id="endstyle"></span>';
         }

         function updateSnippet($id)
         {
         	global $ms_db;
         	$sql = "UPDATE `".TABLE_PREFIX."snippets` SET
			 `title` = '".$ms_db->GetSQLValueString($_POST["title"],"text")."',
			 `description` = '".$ms_db->GetSQLValueString($_POST["description"],"text")."',
			 `is_itm_enabled` = '".$ms_db->GetSQLValueString($_POST["is_itm_enabled"],"text")."'
			 WHERE `id` = ".$id;
			 $id = $ms_db->modify($sql);
			 return $id;	
         }
		 
         function updateSnippetPart($id)
         {
			 global $ms_db;
			 
			 if ($_POST['reset_css'] == 0  && $_POST['old_css'] == 1) { // Удалить css
			 	$_POST['link'] = preg_replace('@<style title=\\\"reset\\\"(.+)<span id=\\\"endstyle\\\"><\/span><div class=\\\"cnmBanner\\\">(.+)</div>@si','$2',$_POST['link']);
			 }			 
			 if ($_POST['reset_css'] == 1 && $_POST['old_css'] != 1) { // Добавить css
			 	$_POST['link'] = '<div class="cnmBanner">'.$_POST['link'].'</div>';
			 	$_POST['link'] = $this->getCssCode().$_POST['link'];
			 }			 
			 $link = $this->changeLinksWithTrackURLs($_POST["link"], $id);
			 $sql = "UPDATE `".TABLE_PREFIX."snippet_parts` SET 
			 `link` = '".$ms_db->GetSQLValueString($_POST["link"],"text")."', `inputmode` = '{$_POST['inputmode']}', `reset_css` = '{$_POST['reset_css']}' WHERE `id` = ".$id;
			 $id = $ms_db->modify($sql);
			 return $id;	
         }
		 
         function deleteSnippet($id)
         {
			 global $ms_db;
			 $sql = "Select id from  `".TABLE_PREFIX."snippet_parts` WHERE `snippet_id` = ".$id;
			 $part_rs = $ms_db->getRS($sql);
			 if ($part_rs)
			 {
				 while($partid = $ms_db->getNextRow($part_rs))
				 {
					 $pid = $this->deleteSnippetPart($partid["id"]);
				 }
			 }
			 $sql = "Delete from  `".TABLE_PREFIX."snippets` WHERE `id` = ".$id;
			 $id = $ms_db->modify($sql);
			 return $id;			
         }
		 
         function deleteSnippetPart($id)
         {
			 global $ms_db;
			 $sql1 = "Delete from ".TABLE_PREFIX."snippet_impression_details where snippet_part_id = ".$id;
			 $id1 = $ms_db->modify($sql1);
			 $sql9 = "Delete from ".TABLE_PREFIX."snippet_click_details where snippet_part_id = ".$id;
			 $id9 = $ms_db->modify($sql9);
			 $sql2 = "Delete from ".TABLE_PREFIX."snippet_trackurls where snippet_part_id = ".$id;
			 $id2 = $ms_db->modify($sql2);
			 $sql3 = "Delete from  `".TABLE_PREFIX."snippet_parts` WHERE `id` = ".$id;
			 $id = $ms_db->modify($sql3);
			 return $id;		
         }
		 
         function resetSnippetPart($pid)
         {
			 global $ms_db;
			 $sql1 = "Delete from `".TABLE_PREFIX."snippet_impression_details` where snippet_part_id = ".$pid;
			 $id1 = $ms_db->modify($sql1);
			 $sql = "Delete from `".TABLE_PREFIX."snippet_click_details` where snippet_part_id = ".$pid;
			 $id = $ms_db->modify($sql);
			 $sql9 = "Update `".TABLE_PREFIX."snippet_parts` set clicks = 0, impressions = 0 WHERE `id` = ".$pid;
			 $id = $ms_db->modify($sql9);
			 return $id;		
         }
		 
         function createDuplicateSnippetPart($pid, $snippet_id=0)
         {
			 global $ms_db;
			 $part = $this->getSnippetPartById($pid);
			 if ($snippet_id>0)
			 {
				 $part["snippet_id"] = $snippet_id;
			 }
			 $sql = "INSERT INTO `".TABLE_PREFIX."snippet_parts` ( `snippet_id` , `link` , `created_date`  )
			 VALUES ("
			 ."'".$ms_db->GetSQLValueString($part["snippet_id"],"text")."',"
			 ."'".$ms_db->GetSQLValueString(html_entity_decode($part["link"]),"text")."',"
			 ."'".$ms_db->GetSQLValueString(date("Y-m-d"),"date")."')";
			 $id = $ms_db->insert($sql);
			 $link = $this->changeLinksWithTrackURLs($part["link"], $id);
			 return $id;
         }
		 
         function createDuplicateSnippet($sid)
         {
			 global $ms_db;
			 $snippet = $this->getSnippetById($sid);
			 $sql = "INSERT INTO `".TABLE_PREFIX."snippets` ( `title` , `description` , `is_itm_enabled` , `created_date`,`user_id` )
			 VALUES ("
			 ."'".$ms_db->GetSQLValueString($snippet["title"],"text")."',"
			 ."'".$ms_db->GetSQLValueString($snippet["description"],"text")."',"
			 ."'".$ms_db->GetSQLValueString($snippet["is_itm_enabled"],"text")."',"
			 ."'".$ms_db->GetSQLValueString(date("Y-m-d"),"date")."',"
			 ."'".$ms_db->GetSQLValueString($snippet["user_id"],"text").
			 "')";
			 $id = $ms_db->insert($sql);
			 $sql = "Select id from `".TABLE_PREFIX."snippet_parts` where snippet_id = ".$sid;
			 $part_rs = $ms_db->getRS($sql);
			 if ($part_rs)
			 {
				 while($part = $ms_db->getNextRow($part_rs))
				 {
					 $this->createDuplicateSnippetPart($part["id"], $id);
				 }
			 }
			 return $id;
         }
		 
         function getSnippetById($id)
         {
			 global $ms_db;
			 $sql = "SELECT * from  `".TABLE_PREFIX."snippets` where id = ".$id;
			 $rs = $ms_db->getDataSingleRow($sql);
			 return $rs;	
         }
		 
         function getSnippetPartById($id)
         {
			 global $ms_db;
			 $sql = "SELECT * from  `".TABLE_PREFIX."snippet_parts` where id = ".$id;
			 $rs = $ms_db->getDataSingleRow($sql);
			 return $rs;	
         }
         function getPartBySnippetId($id)
         {
			 global $ms_db;
			 /*			$sql = "select p.*, count(distinct d.id) as noofimpressions, count(distinct  e.id) as noofclicks
			 from `".TABLE_PREFIX."snippet_parts` p
			 LEFT JOIN `".TABLE_PREFIX."snippet_details` d ON d.snippet_part_id = p.id AND d.operation = 'I'
			 LEFT JOIN `".TABLE_PREFIX."snippet_details` e ON e.snippet_part_id = p.id AND e.operation = 'C' 
			 where p.snippet_id = ".$id." 
			 GROUP BY p.id"; */
			 $sql = "select p.*
			 from `".TABLE_PREFIX."snippet_parts` p
			 where p.snippet_id = ".$id." 
			 GROUP BY p.id"; 		
			 $rs = $ms_db->getRS($sql);
			 return $rs;	
         }
		 
         function checkTitleExist($title, $notin=0)
         {
			 global $ms_db;
			 if ($notin==0)
			 $cond = "";
			 else
			 $cond = " and id != ".$notin;
			 $sql = "SELECT count(*) from  `".TABLE_PREFIX."snippets` where title = '".$title."'".$cond;
			 $found = $ms_db->getDataSingleRecord($sql);
			 if ($found>0)
			 return true;
			 else
			 return false;
         }
		 
         function changeLinksWithTrackURLs($in, $partid)
         {
			 $posstart = 0;
			 $out = $in = html_entity_decode($in);
			 while(($posofA = strpos(strtolower($in),"<a",$posstart)) !== false)
			 {
				 $posofAend = strpos($in, ">",$posofA+1);
				 $posofAtagend = strpos(strtolower($in), "</a>",$posofA+1);
				 if ($posofAend === false)
				 {
					 if ($posofAtagend === false)
					 {
						 return $in;
					 }
					 else
					 {
						 $posstart = $posofAtagend;
						 continue;
					 }
				 }
				 else
				 {
					 if ($posofAtagend === false)
					 {
						 return $in;
					 }
					 else
					 {
						 if ($posofAend > $posofAtagend)
						 {
							 $posstart = $posofAtagend;
							 continue;					
						 }
					 }
				}
				if ($posofA>=0)
				{
					 $posofhref = strpos(strtolower($in),"href",$posofA+1);
					 if ($posofhref>=0)
					 {
						 $posoflinkstarta = strpos($in,"'",$posofhref+1);
						 $posoflinkstartb = strpos($in,'"',$posofhref+1);
						 //if ($posoflinkstarta===false)$posoflinkstarta=-1;
						 //if ($posoflinkstartb===false)$posoflinkstartb=-1;
						 $linkstartposfound = false;
						 if ($posoflinkstarta !== false && $posoflinkstartb !== false)
						 {
							 if ($posoflinkstarta < $posoflinkstartb)
							 {
								 $posoflinkstart = $posoflinkstarta+1;
								 $linkquot = "'";
								 $linkstartposfound = true;
							 }
							 else
							 {
								 $posoflinkstart = $posoflinkstartb+1;
								 $linkquot = '"';
								 $linkstartposfound = true;
							 }
						 }
						 else if ($posoflinkstarta !== false)
						 {
							 $posoflinkstart = $posoflinkstarta+1;
							 $linkquot = "'";
							 $linkstartposfound = true;					
						 }
						 else if ($posoflinkstartb !== false)
						 {
							 $posoflinkstart = $posoflinkstartb+1;
							 $linkquot = '"';
							 $linkstartposfound = true;					
						 }
						 else
						 {
							 $linkstartposfound = false;
						 }
						 if ($linkstartposfound)
						 {
							 $posoflinkend = strpos($in,$linkquot,$posoflinkstart);
							 if ($posoflinkend>0)
							 {
								 $link = substr($in,$posoflinkstart,$posoflinkend-$posoflinkstart);
								 $posstart = $posoflinkend+1;
								 //echo "==> $posstart <==$link<br>";
								 //echo htmlentities(substr($in,$posstart,1000));
								 $tracked = strpos($link,"snippetstrack.php?");
								 if ($tracked>0)
								 {
								 }
								 else
								 {
									 $posofAclose = strpos(strtolower($in),">",$posoflinkend);
									 $posofAtagclose = strpos(strtolower($in),"</a>",$posofAclose);
									 if ($posofAclose !== false && $posofAtagclose!== false)
									 	$atext = substr($in,$posofAclose+1,$posofAtagclose-$posofAclose-1);
									 else
										 $atext = "X";
									 //$posstart = $posofAclose+1;
									 $posstart = $posoflinkstart;
									 $trackURL = $this->getTrackURLForLink($link,$atext, $partid);
									 //$in = str_replace($link,$trackURL,$in);
									 $in = substr_replace($in,$trackURL,$posoflinkstart,$posoflinkend-$posoflinkstart);
								 }
							 }
							 else
							 {
							 	$posstart = $posoflinkstart;
							 }
						 }
						 else
						 {
						 $posstart = $posofhref;
						 }
					 }
					 else
					 {
					 	$posstart = $posofA;
					 }
				 }
			 }
			 return $in;
         }
		 
         function getTrackURLForLink($link,$atext, $partid)
		 {
			 global $ms_db;
			 $sql = "Select * from  `".TABLE_PREFIX."snippet_trackurls` where snippet_part_id = ".$partid;
			 $trk_rs = $ms_db->getRS($sql);
			 if ($trk_rs)
			 {
				 while($turl = $ms_db->getNextRow($trk_rs))
				 {
					 if ($link == $turl["url"])
					 {
					 $trackURL = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/snippetstrack.php?id=$partid-".$turl["id"];
					 return $trackURL;
					 }
				 }
			 }
			 $sql = "INSERT INTO `".TABLE_PREFIX."snippet_trackurls` (`snippet_part_id` , `url`, anchortext )
			 VALUES ("
			 ."'".$ms_db->GetSQLValueString($partid,"int")."',"
			 ."'".$ms_db->GetSQLValueString($link,"text")."',"		
			 ."'".$ms_db->GetSQLValueString($atext,"text")."')";
			 $id = $ms_db->insert($sql);
			 $trackURL = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/snippetstrack.php?id=$partid-$id";
			 return $trackURL;			
		 }
		 
         function unsetTrackURLForLink($content, $partid)
         {
			 global $ms_db;
			 $sql = "Select * from  `".TABLE_PREFIX."snippet_trackurls` where snippet_part_id = ".$partid;
			 $trk_rs = $ms_db->getRS($sql);
			 if ($trk_rs)
			 {
				 while($turl = $ms_db->getNextRow($trk_rs))
				 {
					 $trackURL = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/snippetstrack.php?id=$partid-".$turl["id"];
					 $trackURL = trim($trackURL);
					 $content = str_replace($trackURL,$turl["url"],$content);
				 }
			 }
			 return $content;
         }	
		 
         function getTrackUrlToRedirect($redirect)
         {
			 global $ms_db;
			 $sql = "Select url from `".TABLE_PREFIX."snippet_trackurls` where id = ".$redirect;
			 $track = $ms_db->getDataSingleRecord($sql);
			 return $track;
         }
  }

?>