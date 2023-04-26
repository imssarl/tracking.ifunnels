<?php
include("html_info.class.php");

function get_url($url)
   {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		ob_start();
		curl_exec ($ch);
		curl_close ($ch);
		$content = ob_get_contents();
		ob_end_clean();
	    return $content;    
   }
//////////////////// Title Tag Contents
function Title_Tag_Content($para)
{
$info=new html_info($para);
$title=$info->get_title();
return  trim(RemoveSpecialChar($title));
}

////////////////////
function Bold_Tag_Content($para)
{
$info=new html_info($para);
$title=$info->get_bold();
return  trim(RemoveSpecialChar($title));
}
////////////////
function RemoveScriptTags($para,$orgcontents)
{
    $i=1;
	$varStart=0;
	$varEnd=0;
	$varcurpos=1;
	while ($i<strlen($para))
	{
	while(strpos(strtolower($para), "<script",$varcurpos)>0)
    { 
	$varStart=strpos(strtolower($para), "<script",$varcurpos);
    $varEnd=strpos(strtolower($para), "</script>",$varStart);

	$contentswithoutscripttag=$contentswithoutscripttag.substr($orgcontents,$varcurpos,($varStart-1));
	$contentswithoutscripttag=$contentswithoutscripttag.substr($orgcontents,$varEnd+strlen("</script>"),strlen($orgcontents));
	
	$varcurpos=$varEnd + strlen("</script>");
    }
	$i=$i+1;
	}
	if(trim($contentswithoutscripttag)!=""){
		return $contentswithoutscripttag;
	}else
	{
		return $orgcontents;
	}
}
///////////////////////////////////////////
//===== COUNT TOTAL WORDS===========    //
//////////////////////////////////////////

function Total_WordCount($para,$txt_DontShowLessWords) 
{   
    $varString=RemoveScriptTags($para,$para);
	$varString = strip_tags($varString);
	$varString=RemoveSpecialChar($varString);
	$varString=strtolower($varString);
	$varRikin=split(" ",$varString);

	foreach($varRikin as $varRikin1)
	{
		if((trim($varRikin1)!="") && (trim($varRikin1)!=" ") && (trim(strlen($varRikin1))>=$txt_DontShowLessWords))
		{   
		    if(!(is_numeric($varRikin1)))
		    {
			$varMid[]=trim($varRikin1);
			}
		}
	}
	  return count($varMid);
}

///////////////////////////////////////////
//===== COUNT UNIQUE WORDS==========    //
//////////////////////////////////////////
function UniqueWordCount($para,$txt_DontShowLessWords,$txt_occuratleast)
{
	$varString=RemoveScriptTags($para,$para);
	$varString = strip_tags($varString);
	$varString=RemoveSpecialChar($varString);
	$varString=strtolower($varString);
	$varRikin=split(" ",$varString);

	foreach($varRikin as $varRikin1)
	{
		if((trim($varRikin1)!="") && (trim($varRikin1)!=" ") && (trim(strlen($varRikin1))>=$txt_DontShowLessWords) && (CountNoofOccurance($varRikin1,$varString,$txt_DontShowLessWords)>$txt_occuratleast))
		{   
		    if(!(is_numeric($varRikin1)))
		    {
			$varMid[]=trim($varRikin1);
			}
		}
	}
	$varTemp=array_unique($varMid);
	return count(array_unique($varTemp));
}
///////////////////////////////////////////
//===== <HEAD> TAG CONTENTS  ========    //
//////////////////////////////////////////

function WordsFoundInHeaderTag($para,$orgcontents,$txt_DontShowLessWords)
{  
	$varStart=0;
	$varEnd=0;
	$varcurpos=1;

	$varStart=strpos(strtolower($para), "<head>",$varcurpos); 
	$varEnd=strpos(strtolower($para), "</head>",$varcurpos);
	$vartempH=$vartempH." ".trim(strip_tags(substr($orgcontents,($varStart+strlen("<head>")),(($varEnd-$varStart))-strlen("</head>")+1)));
	$vartempH=RemoveScriptTags($vartempH,$vartempH);
	$vartempH = explode(" ", $vartempH);
	foreach($vartempH as $val)
	{   
	 if((trim($val)!="") && (trim($val)!=" ") && (strlen($val)>=$txt_DontShowLessWords))
	 {
		 $arr1[]=RemoveSpecialChar(trim($val));
	 }	
	}
  return implode(" ",$arr1);
}
////==============
function HeaderData($para)
{
get_headers($txt_Inputurl);
return get_headers($txt_Inputurl, 1);
}
////==============
function WordsFoundInAlternateText($para,$orgcontents)
{
    $i=1;
	$varStart=0;
	$varEnd=0;
	$varcurpos=1;
	while(strpos(strtolower($para), "alt=\"",$varcurpos)>0)
    { 
		$varStart=strpos(strtolower($para), "alt=\"",$varcurpos);
		$varEnd=strpos(strtolower($para), "\"",$varStart+strlen("alt=\""));
		$varAltText= $varAltText." ".trim(strip_tags(substr($orgcontents,($varStart+strlen("alt=\"")),(($varEnd-$varStart))-3)));
		$varcurpos=$varEnd+1;
    }
	return RemoveSpecialChar($varAltText);
}
////==============
function WordsFoundInAnchorTags($para,$orgcontents)
{
    $i=1;
	$varStart=0;
	$varEnd=0;
	$varcurpos=1;
	while ($i<strlen($para))
	{
	while(strpos(strtolower($para), "<a ",$varcurpos)>0)
    { 
	$varStart=strpos(strtolower($para), "<a ",$varcurpos);
    $varStart=strpos(strtolower($para), ">",($varStart+1));
	$varEnd=strpos(strtolower($para), "</a>",$varStart);
	$vartempH=$vartempH." ".strip_tags(trim(substr($orgcontents,($varStart+strlen(">")),($varEnd-$varStart)-1)));
	$varcurpos=$varEnd + strlen("</a>");
    }
	$i=$i+1;
	}
	return $vartempH;
}
///////////////////////////////////////////
//==== <H1><H2>-<H6> TAG CONTENTS  ====  //
//////////////////////////////////////////

function WordsFoundInHTag($para,$orgcontents)
{ 
/*	$info=new html_info($para);
	$varboldtext=$info->get_strings_in_tag("<h1>","</h1>",strtolower($para));
	$varBold="";
	if(count($varboldtext)!=0)
	{
		foreach($varboldtext as $bold)
		{
		$varBold=$varBold." ".$bold;
		}
	}
	return RemoveSpecialChar($varBold);*/
    $i=1;
	$varStart=0;
	$varEnd=0;
	$varcurpos=1;
	for($i=1;$i<=6;$i++)
	{
		while(strpos(strtolower($para), "<h$i>",$varcurpos)>0)
		{ 
			$varStart=strpos(strtolower($para), "<h$i>",$varcurpos);
			$varEnd=strpos(strtolower($para), "</h$i>",$varStart+strlen("<h$i>"));
			$varHText= $varHText." ".trim(strip_tags(substr($orgcontents,($varStart+strlen("<h$i>")),(($varEnd-$varStart))-1)));
			$varcurpos=$varEnd+1;
		}
	}
	return RemoveSpecialChar($varHText);

}

///////////////////////////////////////////
//===== <BOLD> TAG CONTENTS  ========    //
//////////////////////////////////////////
function WordFoundInBoldText($para,$orgcontents)
{  
/*$info=new html_info($para);
$tags[0]['open']='<b>';
$tags[0]['close']='</b>';
$varboldtext=$info->get_strings_in_tags($tags,strtolower($para));
$varBold="";
if(count($varboldtext)!=0)
{
foreach($varboldtext as $bold)
{
	$varBold=$varBold." ".$bold;
}
}*/
   
	$varStart=0;
	$varEnd=0;
	$varcurpos=1;
	while(strpos(strtolower($para), "<b>",$varcurpos)>0)
    { 
		$varStart=strpos(strtolower($para), "<b>",$varcurpos);
		$varEnd=strpos(strtolower($para), "</b>",$varStart+strlen("<b>"));
		$varBoldText= $varBoldText." ".trim(strip_tags(substr($orgcontents,($varStart+strlen("<b>")),(($varEnd-$varStart))-1)));
		$varcurpos=$varEnd+1;
    }
	return RemoveSpecialChar($varBoldText);
}

///////////////////////////////////////////
//===== <ITALIC> TAG CONTENTS  ========  //
//////////////////////////////////////////

function WordFoundInItalicText($para,$orgcontents)
{
/*   $info=new html_info($para);
   $varIText=$info->get_strings_in_tag("<i>","</i>",strtolower($para));
   $varI="";
   if(count($varIText)!=0)
   {
   foreach($varIText as $I)
   {
 		$varI=$varI." ".$I;
	}
	}
	return $varI;*/
	
	$varStart=0;
	$varEnd=0;
	$varcurpos=1;
	while(strpos(strtolower($para), "<i>",$varcurpos)>0)
    { 
		$varStart=strpos(strtolower($para), "<i>",$varcurpos);
		$varEnd=strpos(strtolower($para), "</i>",$varStart+strlen("<i>"));
		$varItalicText= $varItalicText." ".trim(strip_tags(substr($orgcontents,($varStart+strlen("<i>")),(($varEnd-$varStart))-1)));
		$varcurpos=$varEnd+1;
    }
	return RemoveSpecialChar($varItalicText);
}

////==============RemoveSpecialChar  
function RemoveSpecialChar($vst)
{
$varStr=strtolower($vst);
/*
$varStr=str_replace("~"," ",$varStr);
$varStr=str_replace("!"," ",$varStr);
$varStr=str_replace("$"," ",$varStr);
$varStr=str_replace("%"," ",$varStr);
$varStr=str_replace("^"," ",$varStr);
$varStr=str_replace("&"," ",$varStr);
$varStr=str_replace("*"," ",$varStr);
$varStr=str_replace("("," ",$varStr);
$varStr=str_replace(")"," ",$varStr);
$varStr=str_replace("-"," ",$varStr);
$varStr=str_replace("|"," ",$varStr);
$varStr=str_replace("+"," ",$varStr);
$varStr=str_replace("="," ",$varStr);
$varStr=str_replace(":"," ",$varStr);
$varStr=str_replace(";"," ",$varStr);
$varStr=str_replace("\""," ",$varStr);
$varStr=str_replace("'"," ",$varStr);
$varStr=str_replace("<"," ",$varStr);
$varStr=str_replace(">"," ",$varStr);
$varStr=str_replace(","," ",$varStr);
$varStr=str_replace("."," ",$varStr);
$varStr=str_replace("?"," ",$varStr);
$varStr=str_replace("/"," ",$varStr);
$varStr=str_replace("@"," ",$varStr);
$varStr=str_replace("["," ",$varStr);
$varStr=str_replace("]"," ",$varStr);
$varStr=str_replace("{"," ",$varStr);
$varStr=str_replace("}"," ",$varStr);
$varStr=str_replace("nbsp"," ",$varStr);*/
$varStr = ereg_replace("[^a-z]", " ", $varStr);
return trim($varStr);
}
////==============
function CountNoofOccurance($varStr,$varSearchIn,$txt_DontShowLessWords)
{

if (strlen($varStr)>$txt_DontShowLessWords)
{	
	$varTotal=substr_count($varSearchIn," ".$varStr." ");
	return $varTotal;
}
}

function CountNoofOccuranceForPhrases($varStr,$varSearchIn)
{
//if (strlen($varStr)>$txt_DontShowLessWords)
//{	
	$varTotal=substr_count($varSearchIn," ".$varStr." ");
	return $varTotal;
//}
}


//////////////////

if(!function_exists('get_headers')) 
{
  
   /**
   * @return array
   * @param string $url
   * @param int $format
   * @desc Fetches all the headers
   * @author cpurruc fh-landshut de
   * @modified by dotpointer
   * @modified by aeontech
   */
   function get_headers($url,$format=0)
   {
       $url_info=parse_url($url);
       $port = isset($url_info['port']) ? $url_info['port'] : 80;
       $fp=fsockopen($url_info['host'], $port, $errno, $errstr, 30);
      
       if($fp)
       {
           $head = "HEAD ".@$url_info['path']."?".@$url_info['query']." HTTP/1.0\r\nHost: ".@$url_info['host']."\r\n\r\n";     
           fputs($fp, $head);     
           while(!feof($fp))
           {
               if($header=trim(fgets($fp, 1024)))
               {
                   if($format == 1)
                   {
                       $key = array_shift(explode(':',$header));
                       // the first element is the http header type, such as HTTP 200 OK,
                       // it doesn't have a separate name, so we have to check for it.
                       if($key == $header)
                       {
                           $headers[] = $header;
                       }
                       else
                       {
                           $headers[$key]=substr($header,strlen($key)+2);
                       }
                       unset($key);
                   }
                   else
                   {
                       $headers[] = $header;
                   }
               }
           }
           return $headers;
       }
       else
       {
           return false;
       }
   }
}

function IgnoreWordsRead()
{
      $handle=fopen("temp/ignore.txt","rb");
      $ignorecontent = fread($handle, filesize("temp/ignore.txt"));
      fclose($handle); 
	 
	return $ignorecontent;	  
}
function IgnoreWordsWrite($para)
{
      $handle=fopen("temp/ignore.txt","w");
      $ignorecontent = fread($handle, $filesize("temp/ignore.txt"));
	  fputs($handle,$para);
      fclose($handle); 
}
function AduldWordsRead()
{
      $handle=fopen("temp/adultwords.txt","rb");
      $adultcontent = fread($handle, filesize("temp/adultwords.txt"));
      fclose($handle); 
	  
      return $adultcontent;	  
}
function poisionWordsRead()
{
      $handle=fopen("temp/poisionwords.txt","rb");
      $poisioncontent = fread($handle, filesize("temp/poisionwords.txt"));
      fclose($handle); 
	 
	 return $poisioncontent;	  
}


//////////////////////////////////////////////////////////////////
//===== PULL TWO WORDPHRASES Words from the Content   ========  //
/////////////////////////////////////////////////////////////////

function Twowordphrases($para,$txt_DontShowLessWords)
{
	$i=0;
	$varSeekStr="";                      // Splites full Contents in to an array
	$varRikin=split(" ",$para);
	$varArrFinal=array(array());
	$j=0;
	//echo "<li> $para";

	foreach($varRikin as $varRikin1)
	{
		if((trim($varRikin1)!="") && (trim($varRikin1)!=" ")) // && (trim(strlen($varRikin1))>$txt_DontShowLessWords)) // && (CountNoofOccurance($varRikin1,$varString,$txt_DontShowLessWords)>$txt_occuratleast))
		{   
		    if(!(is_numeric($varRikin1)))
		    {
			$arrOfContents[]=$varRikin1;
			}
		}
	}

	for($i=0;$i<count($arrOfContents)-1;$i=$i+1)
	{
		if (strlen(trim($arrOfContents[$i]))>0)
		{
						$varSeekStr=$arrOfContents[$i]." ".$arrOfContents[$i+1];
						$varTemp[$j]=$varSeekStr;
						$j=$j+1;
		}
    }

	$varTemp=array_unique($varTemp);                     								// Extract unique words
	for($i=0;$i<count($varTemp)-1;$i=$i+1)
	{
		if (strlen(trim($varTemp[$i]))>0)
		{
				$varTotalOccurance=CountNoofOccuranceForPhrases($varTemp[$i],$para);   //count there occurancy 1 by 1 and store in a two 
				if ($varTotalOccurance>0)                         					   // dimentional array.
				{
						$varArrFinal[$j][0]=$varTemp[$i];                              // Text String     
						$varArrFinal[$j][1]=$varTotalOccurance;                        // No of Times Occurance
						$j=$j+1;
				}
		}
    }

return $varArrFinal;
}
////////////////////////////////////////////////////////


function remove_dups($array, $index) {
   $array_count = count($array);
   $array_count_inner = count($array[$index]);
   for ($i=0; $i<$array_count_inner; $i++) {
       for ($j=$i+1; $j<$array_count_inner; $j++) {
           if ($array[$index][$i]==$array[$index][$j]) {
               for ($k=0; $k<$array_count; $k++) {
                   unset($array[$k][$i]);
               } // end for   
           } // end if
       } // end for   
   } // end for
   return $array;
} // end function remove_dups
?>