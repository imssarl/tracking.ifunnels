<?php
class SnippetFunctions
{
	function getSnippetShowPart($id)
	{
		global $ms_db;
		$sql = "Select is_itm_enabled from `".TABLE_PREFIX."snippets` where id = ".$id;
		$isITMenabled = $ms_db->getDataSingleRecord($sql);
		if ($isITMenabled=="Y") {
			$showpart = $this->getITMSnippetShowPart($id);
		} else {
			$showpart = $this->getERSnippetShowPart($id);
		}
		$this->updatePartShown($showpart["id"]);
		//		$this->updatePartShownForSnippet($id, $showpart["id"]);
		return $showpart;
	}
	
	function getLastShownByIP($id)
	{
		global $ms_db;
		$sql = "Select p.id from `".TABLE_PREFIX."snippet_parts` p 
		Left Join `".TABLE_PREFIX."snippets` s ON s.id = p.snippet_id
		Left Join `".TABLE_PREFIX."snippet_impression_details` d ON d.snippet_part_id = p.id
		where s.id = ".$id."
		ORDER BY d.id desc
		LIMIT 1";
		$lastshown = $ms_db->getDataSingleRecord($sql);
		return $lastshown;
	}
	
	function deleteImpressions()
	{
		global $ms_db;
		$maxrec = 50;
		$sql = "Select snippet_part_id , count(snippet_part_id) as noofimp from ".TABLE_PREFIX."snippet_impression_details GROUP BY snippet_part_id having noofimp > $maxrec";
		$prt_rs = $ms_db->getRS($sql);
		if ($prt_rs)
		{
		while($part = $ms_db->getNextRow($prt_rs))
		{
		$limit = $part["noofimp"]-$maxrec;
		$sql="delete  FROM `".TABLE_PREFIX."snippet_impression_details`  where  snippet_part_id = ".$part["snippet_part_id"]." order by id limit  $limit";
		$ms_db->modify($sql);
		}
		}
	}
	
	function getLastShown($id)
	{
		global $ms_db;
		$sql = "Select p.id from `".TABLE_PREFIX."snippet_parts` p 
		Left Join `".TABLE_PREFIX."snippets` s ON s.id = p.snippet_id
		Left Join `".TABLE_PREFIX."snippet_impression_details` d ON d.snippet_part_id = p.id
		where s.id = ".$id." 
		ORDER BY p.id desc
		LIMIT 1";
		$lastshown = $ms_db->getDataSingleRecord($sql);
		return $lastshown;
	}	
	
	function getERSnippetShowPart($id)
	{
		global $ms_db;
		$lastid = $this->getLastShownByIP($id);
		$newpart = false;
		if ($lastid)
		{
		$sql = "Select p.* from `".TABLE_PREFIX."snippet_parts` p
		Left Join `".TABLE_PREFIX."snippets` s ON s.id = p.snippet_id
		WHERE p.id > ".$lastid." AND s.id = ".$id." AND p.pause!=1 LIMIT 1";
		$newpart = $ms_db->getDataSingleRow($sql);
		}
		if (!$newpart)
		{
		$sql = "Select p.* from `".TABLE_PREFIX."snippet_parts` p 
		Left Join `".TABLE_PREFIX."snippets` s ON s.id = p.snippet_id
		WHERE s.id = ".$id." AND p.pause!=1 ORDER BY p.id LIMIT 1";	
		$newpart = $ms_db->getDataSingleRow($sql);
		}
		return $newpart;
	}	
	
	function getITMSnippetShowPart($id)
	{
		global $ms_db;
		$isranked = $this->checkRankingStarted($id);
		if (!$isranked)
		{
		$showpart = $this->getERSnippetShowPart($id);
		}
		else
		{
		$showpartid = $this->getSnippetShowPartOnITMBalancing($id);
		$sql = "Select p.* from `".TABLE_PREFIX."snippet_parts` p WHERE p.id = ".$showpartid." AND p.pause!=1 LiMiT 1";
		$showpart = $ms_db->getDataSingleRow($sql);
		}
		return $showpart;
	}
	
	function checkRankingStarted($id)
	{
		global $ms_db;
		$sql = "Select d.id 
		from `".TABLE_PREFIX."snippet_parts` p 
		Join `".TABLE_PREFIX."snippets` s ON s.id = p.snippet_id
		Join `".TABLE_PREFIX."snippet_click_details` d ON d.snippet_part_id = p.id	
		where    s.id = $id";
		$rankedpart = $ms_db->getDataSingleRecord($sql);
		if ($rankedpart && $rankedpart>0)
		return true;
		else
		return false;
	}
	
	function updatePartShown($id)
	{
		global $ms_db;
		$sql = "INSERT INTO `".TABLE_PREFIX."snippet_impression_details` (`snippet_part_id` , `date`) VALUES ("
		."'".$ms_db->GetSQLValueString($id,"int")."',"
		."'".$ms_db->GetSQLValueString(date("Y-m-d H:i:s"),"date")."')";
		$sid = $ms_db->insert($sql);
		$this->updatePartEvent($id, "impressions");
		return $sid;			
	}
	
	function updatePartShownForSnippet($id, $partid)
	{
		global $ms_db,$snippets;
		$topimpression = "";
		$snippet = $snippets->getSnippetById($id);
		if ($snippet["top1_id"]==$partid)
		$topimpression = "top1_impression";
		if ($snippet["top2_id"]==$partid)
		$topimpression = "top2_impression";
		if ($snippet["top3_id"]==$partid)
		$topimpression = "top3_impression";
		if ($topimpression !=  "")
		{
		$updatersql = "Update `".TABLE_PREFIX."snippets` SET
		$topimpression = $topimpression + 1 
		where id = ".$id;
		$ms_db->modify($updatersql);	
		}
	}
	function updatePartClicked($id,$lid)
	{
		global $ms_db;
		$sql = "INSERT INTO `".TABLE_PREFIX."snippet_click_details` (`snippet_part_id` , `date` , `ip_address` , `url_shown` , `trackurl_id` ) VALUES ("
		."'".$ms_db->GetSQLValueString($id,"int")."',"
		."'".$ms_db->GetSQLValueString(date("Y-m-d H:i:s"),"date")."',"
		."'".$ms_db->GetSQLValueString($_SERVER["REMOTE_ADDR"],"text")."',"
		."'".$ms_db->GetSQLValueString($_SERVER["HTTP_REFERER"],"text")."',"
		."'".$ms_db->GetSQLValueString($lid,"int")."')";
		$cid = $ms_db->insert($sql);
		$this->updatePartEvent($id, "clicks");
		return $cid;
	}
		
	function updatePartEvent($id, $event)
	{
		global $ms_db;
		$sql="Update ".TABLE_PREFIX."snippet_parts set $event = $event+1 where id = $id";
		$ms_db->modify($sql);		
	}
	function getSnippetShowPartOnITMBalancing($id)
	{
		global $settings;
		$rank = $settings->getSettings();
		if($rank["snippet_part_3"]==''||$rank["snippet_part_3"]==0)$rank["snippet_part_3"]=1;
		
		$rank1 = $rank["snippet_part_1"]/$rank["snippet_part_3"];
		$rank2 = $rank["snippet_part_2"]/$rank["snippet_part_3"];
		$rank3 = 1;
		if($rank1==''||$rank1==0)$rank1=1;
		if($rank2==''||$rank2==0)$rank2=1;
		
		$part = $this->findTopThree($id);		
		if (count($part) == 3)
		{
		if ($part[0]["ctr"] == $part[1]["ctr"] && $part[1]["ctr"] == $part[2]["ctr"])
		{
			if(rand(0,1)==1)
			{
				$showid = $this->getRandomInLowers($id, $part, 0);
				return $showid;
			}
		}
		else if ($part[0]["ctr"] == $part[1]["ctr"])
		{
		if(rand(0,1)==1)
		{
		$showid = $this->getRandomInLowers($id, $part, 0);
		return $showid;
		}		
		}
		else if( $part[1]["ctr"] == $part[2]["ctr"])
		{
		if(rand(0,1)==1)
		{
		$showid = $this->getRandomInLowers($id, $part, 0);
		return $showid;
		}		
		}
		}
		if (count($part) < 3)
		{
		if (count($part) == 1)
		{
		$islastone = $this->checkIsLastShown($id, $part[0]["id"], ceil($rank1/$rank2));
		if ($islastone)
		{
		$showid = $this->getRandomInLowers($id, $part);
		}
		else
		{
		$showid = $part[0]["id"];
		}
		}
		else if (count($part) == 2)
		{
		if ($part[0]["shown"] >= $part[1]["shown"]*($rank1/$rank2) )
		{
		$showidgenerated = false;
		if(rand(0,1)==1)
		{
		$showid = $this->getRandomInLowers($id, $part, 0);
		$showidgenerated = true;
		}
		if ($showidgenerated==false)
		{
		$islast2wasone = $this->checkIsLastShown($id, $part[0]["id"], ceil($rank1/$rank2));// dbt
		if ($islast2wasone)
		{
		$showid = $part[rand(0,1)]["id"];
		}
		else
		{
		$showid = $part[0]["id"];
		}
		}
		}
		else
		{
		if ($part[0]["ctr"] == $part[1]["ctr"])
		{
		if(rand(0,1)==1)
		{
		$showid = $this->getRandomInLowers($id, $part, 0);
		return $showid;
		}		
		}
		$islast4wasone = $this->checkIsLastShown($id, $part[0]["id"], ceil($rank1/$rank2));
		if ($islast4wasone)
		{
		$showid = $this->getRandomInLowers($id, $part);
		}
		else
		{
		$showid = $part[0]["id"];
		}
		}
		}
		}
		else if ($part[0]["shown"] >= $part[2]["shown"]*$rank1 ) // old created
		{
		if ($part[0]["shown"] >= $part[1]["shown"]*($rank1/$rank2) )
		{
		if ($part[1]["shown"] >= $part[2]["shown"]*$rank2 )
		{
		$showidgenerated = false;
		if ($part[0]["shown"] == $part[1]["shown"]*($rank1/$rank2) && $part[0]["shown"]== $part[2]["shown"]*$rank1)
		{
		if(rand(0,1)==1)
		{
		$showid = $this->getRandomInLowers($id, $part, 0);
		$showidgenerated = true;
		}
		}
		if ($showidgenerated==false)
		{
		$islastthird = $this->checkIsLastShown($id, $part[2]["id"], $rank3);
		if ($islastthird)
		{
		$showid = $this->getRandomInLowers($id, $part); // , 1, true
		}
		else
		{
		$showid = $part[2]["id"];
		}
		}
		}
		else
		{
		$showid = $part[1]["id"];
		}
		}
		else
		{
		$islast4wasone = $this->checkIsLastShown($id, $part[0]["id"], $rank1);
		if ($islast4wasone)
		{
		$myrand = rand(0,1);
		$showid = $part[$myrand*2]["id"];
		}
		else
		{
		$showid = $part[0]["id"];
		}
		}
		}
		else
		{
		if ($part[0]["shown"] >= $part[1]["shown"]*($rank1/$rank2) )
		{
		$islast2wasone = $this->checkIsLastShown($id, $part[0]["id"], ceil($rank1/$rank2));
		if ($islast2wasone)
		{
		$showid = $part[rand(0,1)]["id"];
		}
		else
		{
		$showid = $part[0]["id"];
		}
		}
		else
		{
		$islast2wasone = $this->checkIsLastShown($id, $part[0]["id"], ceil($rank1));
		if ($islast2wasone)
		{
		$showid = $this->getRandomInLowers($id, $part, 2);
		}	
		else
		{
		$showid = $part[0]["id"];
		}			
		}
		}
		return $showid;
	}
	
	function findTopThree($id)
	{
		global $ms_db;
		/*$sql = "Select p.id,p.snippet_id, count(distinct d.id) as clicks ,count(distinct e.id) as shows, count(distinct d.id)/count(distinct e.id)*100 as ctr
		from `".TABLE_PREFIX."snippet_parts` p
		LEFT JOIN `".TABLE_PREFIX."snippets` s on s.id = p.snippet_id
		LEFT JOIN `".TABLE_PREFIX."snippet_details` d ON d.snippet_part_id = p.id AND d.operation = 'C'
		LEFT JOIN `".TABLE_PREFIX."snippet_details` e ON e.snippet_part_id = p.id AND e.operation = 'I'
		WHERE d.operation = 'C' AND s.id = ".$id." group by p.id order by ctr desc LIMIT 3";*/
		/*		$sql = "Select p.id,p.snippet_id, p.clicks as clicks ,p.impressions as shows
		, s.top1_id,s.top1_impression,s.top2_id,s.top2_impression,s.top3_id,s.top3_impression
		, (p.clicks/p.impressions)*100 as ctr
		from `".TABLE_PREFIX."snippet_parts` p
		LEFT JOIN `".TABLE_PREFIX."snippets` s on s.id = p.snippet_id
		WHERE s.id = ".$id." AND p.clicks > 0 group by p.id order by ctr desc, p.id asc LIMIT 3"; */
		$sql = "Select p.id,p.snippet_id, p.clicks as clicks ,p.impressions as shows
		, (p.clicks/p.impressions)*100 as ctr
		from `".TABLE_PREFIX."snippet_parts` p
		LEFT JOIN `".TABLE_PREFIX."snippets` s on s.id = p.snippet_id
		WHERE s.id = ".$id." AND p.clicks > 0 AND p.pause!=1 group by p.id order by ctr desc, p.id asc LIMIT 3";
		$toprs = $ms_db->getRS($sql);
		if ($toprs)
		{
		$data = array();
		$topperlistchanged = false;
		$index = 0;
		$fc = 1;
		while($top = $ms_db->getNextRow($toprs))
		{
		/*				if ($top["top".$fc."_id"] != $top["id"])
		{
		$topperlistchanged = true;
		}
		if ($topperlistchanged)
		{
		$partshown = 0;
		}
		else
		{
		$partshown = $top["top".$fc."_impression"];
		} */
		$data[$index]["id"] = $top["id"];
		$data[$index]["shown"] = $top["shows"]; 
		//				$data[$index]["shown"] = $partshown; // new  code commented when giving problem
		$data[$index]["clicked"] = $top["clicks"];
		$data[$index]["ctr"] = $top["ctr"];
		$index++;
		$fc++;
		}
		/*			if ($topperlistchanged)
		{
		$row1="";$row2="";$row3="";
		if (isset($data[0]["id"]))
		$row1 = " top1_id = ".$data[0]["id"].",	top1_impression = 1";
		if (isset($data[1]["id"]))
		$row2 = ", top2_id = ".$data[1]["id"].",	top2_impression = 1";
		if (isset($data[2]["id"]))
		$row3 = ", top3_id = ".$data[2]["id"].",	top3_impression = 1 ";
		$updatersql = "Update `".TABLE_PREFIX."snippets` SET
		$row1 $row2 $row3
		where id = ".$id;
		$ms_db->modify($updatersql);
		} */
		return $data;
		}
		else
		{
		return false;
		}
	}
	
	function checkIsLastShown($snippet, $part, $limit)
	{
			global $ms_db;
			$limit = ceil($limit);
			$sql = "select p.id
			from `".TABLE_PREFIX."snippet_parts` p
			LEFT JOIN `".TABLE_PREFIX."snippets` s on s.id = p.snippet_id
			LEFT JOIN `".TABLE_PREFIX."snippet_impression_details` d ON d.snippet_part_id = p.id 
			where s.id = $snippet
			order by d.id desc 
			limit $limit";
			$lastrs = $ms_db->getRS($sql);
			if ($lastrs)
			{
			$data = array();
			$index = 0;
			while($last = $ms_db->getNextRow($lastrs))
			{
			if ($last["id"] == $part)
			{
			$index++;
			}
			}
			if ($index >= $limit)
			{
			return true;
			}
			else
			{
			return false;
			}
			}
			else
			{
			return false;
			}
	}
		
	function getRandomInLowers($id, $part, $leave=1, $onlyranked=false)
	{
			global $ms_db;
			$i=0;
			$cond = "";
			$sqlcnt = "select count(p.id)
			from `".TABLE_PREFIX."snippet_parts` p
			where snippet_id = $id ";
			$totparts = $ms_db->getDataSingleRecord($sqlcnt);
			if ($onlyranked)
			{
			$whichparts = " AND clicks > 0 ";
			}
			else
			{
			$whichparts = "";
			}
			if ($totparts != false && $totparts > 3)
			{
			for ($i=0; $i < count($part)-$leave;$i++)
			{
			$cond .= " AND  id != ".$part[$i]["id"];
			}
			}
			//		$id1 = $part[0]["id"];
			//		$id2 = $part[1]["id"];
			$sql = "select id
			from `".TABLE_PREFIX."snippet_parts` p
			where snippet_id = $id $cond $whichparts ORDER BY RAND() LIMIT 1";
			$showid = $ms_db->getDataSingleRecord($sql);
			if ($showid==false || $showid == "")
			{
				$showid = "0";
			}
			return $showid;
	}
		
		function showSnippet($id)
		{
			global $ms_db;
			$sql = "SELECT * from  `".TABLE_PREFIX."snippet_parts` where id = ".$id;
			$rs = $ms_db->getDataSingleRow($sql);
			return $rs;	
		}	
		
		function evaluateLinkCode($content)
		{
		/*	$phpstart = strpos($content,"<?php");
		if ( $phpstart >=0 )
		{
		$phpend = strpos($content,"?>");
		if ( $phpend >=0 )
		{
		$phpcode = substr($content, $phpstart, $phpend);
		$html = $this->eval_code($phpcode);
		echo "-- $html --------";
		//substr_replace($content, $html, $phpstart, $phpend);
		}			
		}*/
			return $content;
		}
	
	function eval_code($string) 
	{
		ob_start();
		eval("$string;");
		$return = ob_get_contents();
		ob_end_clean();
		return $return;
	}
	
	/*	function getSnippetShowPartOnITMBalancing($id)
	{
	global $settings;
	$rank = $settings->getSettings();
	$part = $this->findTopThree($id);
	if (count($part) < 3)
	{
	if (count($part) == 1)
	{
	$islastone = $this->checkIsLastShown($id, $part[0]["id"], 2);
	if ($islastone)
	{
	$showid = $this->getRandomInLowers($id, $part);
	}
	else
	{
	$showid = $part[0]["id"];
	}
	}
	else if (count($part) == 2)
	{
	if ($part[0]["shown"] >= $part[1]["shown"]*2 )
	{
	$islast2wasone = $this->checkIsLastShown($id, $part[0]["id"], 2);
	if ($islast2wasone)
	{
	$showid = $part[rand(0,1)]["id"];
	}
	else
	{
	$showid = $part[0]["id"];
	}
	}
	else
	{
	$islast4wasone = $this->checkIsLastShown($id, $part[0]["id"], 4);
	if ($islast4wasone)
	{
	$showid = $this->getRandomInLowers($id, $part);
	}
	else
	{
	$showid = $part[0]["id"];
	}
	}
	}
	}
	else if ($part[0]["shown"] >= $part[2]["shown"]*4 ) // old created
	{
	if ($part[0]["shown"] >= $part[1]["shown"]*2 )
	{
	if ($part[1]["shown"] >= $part[2]["shown"]*2 )
	{
	$islastthird = $this->checkIsLastShown($id, $part[2]["id"], 1);
	if ($islastthird)
	{
	$showid = $this->getRandomInLowers($id, $part);
	}
	else
	{
	$showid = $part[2]["id"];
	}
	}
	else
	{
	$showid = $part[1]["id"];
	}
	}
	else
	{
	$islast4wasone = $this->checkIsLastShown($id, $part[0]["id"], 4);
	if ($islast4wasone)
	{
	$showid = rand($part[0]["id"], $part[2]["id"]);
	}
	else
	{
	$showid = $part[0]["id"];
	}
	}
	}
	else
	{
	if ($part[0]["shown"] >= $part[1]["shown"]*2 )
	{
	$islast2wasone = $this->checkIsLastShown($id, $part[0]["id"], 2);
	if ($islast2wasone)
	{
	$showid = $part[rand(0,1)]["id"];
	}
	else
	{
	$showid = $part[0]["id"];
	}
	}
	else
	{
	$showid = $part[0]["id"];
	}
	}
	return $showid;
	}
	*/	
}

?>