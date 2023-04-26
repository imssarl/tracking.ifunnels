<?php
class GoogleAds
{

function getContentFromURL($url, $keyword="")
{
	$str = "";
	if(function_exists("fopen"))
	{
			$fp = @fopen($url,"r");
			if($fp)
			{		

				while(!feof($fp))
				{
					$str .= fgets($fp);
				}
				fclose($fp);
			}
			else 
			{
				$str = false;
			}
	}
	else if(function_exists("curl_init"))
	{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$resp = curl_exec($ch); 
			curl_close ($ch);
			$str .= $resp;
	}
	return $str;
}

function getTopAds($str)
{

	$partstart = '<a id=pa';
	$partend = '</font></div>';
	$linkstart = '<a id=pa';
	$linkend = '</a>';
	$contentstart = '<font size=-1>';
	$contentend = '</font>';
	
	$txtcontentstart = '</span>';
	$txtcontentend = '</font>';
	
	$spanstart = '<span class=a>';
	$spanend = '</span>';
	
	$ad = array();
	$gad = array();
	$desturl = array();
	$dispurl = array();
	
	$str = str_replace(array("\n","\r","\t"),array("","",""),$str);
	
	
	preg_match_all("|(".$partstart."(.*)".$partend.")|U",$str, $out);
	

	for($x=0;$x<count($out[1]);$x++)
	{ 
	
		preg_match_all("|(".$linkstart."(.*)".$linkend.")|U",$out[1][$x], $out_1);
		
		$link = $this->getURLfromAnchor($out_1[1][0]);
		$ad["desturl"] = $link["url"];
		$ad["subject"] = $link["text"];
		
		
		preg_match_all("|(".$contentstart."(.*)".$contentend.")|U",$out[1][$x], $out_1);
		
		preg_match_all("|(".$txtcontentstart."(.*)".$txtcontentend.")|U",$out_1[1][0], $out_1);
		
		$ad["body"] = $out_1[1][0];
		
				preg_match_all("|(".$spanstart."(.*)".$spanend.")|U",$out[1][$x], $out_1);
		
		$ad["dispurl"] =  $out_1[2][0];

		$gad[] = $ad;
	}

return $gad;
}





function getRightAds($str)
{
	$mainstart = '<table cellspacing=0 cellpadding=0 width=25% align=right id=mbEnd bgcolor=#ffffff border=0 class=ra>';
	$mainend = '</table>';
	$partstart = '<font size=\+0>';
	$partend = '</span>';
	$linkstart = '<font size=\+0>';
	$linkend = '</font>';
	$contentstart = '</font>';
	$contentend = '<span class=a>';
	$spanstart = '<span class=a>';
	$spanend = '</span>';
	
	$ad = array();
	$gad = array();
	$desturl = array();
	$dispurl = array();
	
	$str = str_replace(array("\n","\r","\t"),array("","",""),$str);
	
	preg_match_all("|(".$mainstart."(.*)".$mainend.")|U",$str, $outmain);
	
	for($r=0;$r<count($outmain[2]);$r++)
	{
	
	preg_match_all("|(".$partstart."(.*)".$partend.")|U",$outmain[2][$r], $out);

	for($x=0;$x<count($out[1]);$x++)
	{ 
	
		preg_match_all("|(".$linkstart."(.*)".$linkend.")|U",$out[1][$x], $out_1);
		
		$link = $this->getURLfromAnchor($out_1[2][0]);
		$ad["desturl"] = $link["url"];
		$ad["subject"] = $link["text"];
		
		
		preg_match_all("|(".$contentstart."(.*)".$contentend.")|U",$out[1][$x], $out_1);
		
		$ad["body"] = $out_1[2][0];
		
		preg_match_all("|(".$spanstart."(.*)".$spanend.")|U",$out[1][$x], $out_1);
		
		$ad["dispurl"] =  $out_1[2][0];
		$gad[] = $ad;
	}
}
return $gad;
}




	function getURLfromAnchor($in, $astart ="<a")
	{
		$posstart = 0;
		$lnk = array();
		$out = $in = html_entity_decode($in);
		
		while(($posofA = strpos(strtolower($in),$astart,$posstart)) !== false)
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
						
						$posoflinkstart = strpos($in,'=',$posofhref+1)+1;
						//$posoflinkstart = $posofhref+5;
						$linkquot = ' ';
						$linkstartposfound = true;					
					
					if ($linkstartposfound)
					{
					
						$posoflinkend = strpos($in,$linkquot,$posoflinkstart);
						if ($posoflinkend>0)
						{
								$posofAclose = strpos(strtolower($in),">",$posoflinkend);
								$posofAtagclose = strpos(strtolower($in),"</a>",$posofAclose);
								if ($posofAclose !== false && $posofAtagclose!== false)
									$atext = substr($in,$posofAclose+1,$posofAtagclose-$posofAclose-1);
								else
									$atext = "X";
						
						
							$link = substr($in,$posoflinkstart,$posoflinkend-$posoflinkstart);
							$posstart = $posoflinkend+1;
							$lnk["text"] = $atext;
							$lnk["url"] = $link;
							return $lnk;
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
		return false;
	}
}
?>
