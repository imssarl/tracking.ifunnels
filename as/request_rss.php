<?php require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/article.class.php");
include("classes/xmlparse.class.php");

$asm_db = new Database();
$asm_db->openDB();
$article_obj = new Article();
//$objXML = new xml2Array();
$total_remain=0;
 $xml_url=$_REQUEST['rss_url'];

//"http://www.1dogobedience.com/blog/feed/rss";
/*$xmldata=$article_obj->get_url($xml_url);

if($xmldata!=false)
    {  
     $output=insertOutput($xmldata);
     echo $output;
    }

*/
?>


<?php






/*function insertOutput($xmldata)
{
 global $objXML,$key,$article_obj,$total_remain;
 $arrOutput = $objXML->parse($xmldata);
  $title=array();
  //print_r($arrOutput);
	for($g=0;$g<sizeof($arrOutput[0]["children"][0]["children"]);$g++)
	{
		if($arrOutput[0]["children"][0]["children"][$g]['name']=='ITEM')
		{

    $title[]="'".addslashes($arrOutput[0]["children"][0]["children"][$g]["children"][0]["tagData"])."'";
    $decription=$arrOutput[0]["children"][0]["children"][$g]["children"][1]["tagData"];
 $article_url=$arrOutput[0]["children"][0]["children"][$g]["children"][2]["tagData"];
		}
	}
      $total=count($title);
      
      $num=$article_obj->check_duplicate($title);
      if($num>$total)
      $imp_num=$num-$total;
      else
        $imp_num=$num;
      $total_remain=$total-$imp_num;
      $str_output="<FONT size='2px' color='Red'>"."Total ".$total." article are fetched , ".$imp_num." article are duplicate.</FONT>";
      return $str_output;
} */


?>


<?php

  
  
  $count=0;
$last_tag="";
$current_tag="";
$arrtitles = array();
$arrdescs = array();
$arrlinks = array();
$arrtitles = array();
$arrdescs = array();
$arrlinks = array();
$title=array();
$final=0;
$arrtitles[$count]["TITLE"]="";
$arrlinks[$count]["LINK"]="";
$arrdescs[$count]["DESCRIPTION"]="";
$title[$count]="";
$flagi=0;
show($xml_url);

for($i=0;$i<count($title);$i++)
{
    if($title[$i]==""){ $title[$i]="''"; }
}


    $num=$article_obj->check_duplicate($title);
$total=count($title)-1;
      if($num>$total)
      $imp_num=$num-$total;
      else
        $imp_num=$num;
      $total_remain=$total-$imp_num;
   echo    $str_output="<FONT size='2px' color='Red'>"."Total ".$total." article are fetched , ".$imp_num." article are duplicate.</FONT>";
   



if($total_remain!=0) {
echo '<br><br>Select Number of article for import : <select name="counterrss">';
  for($i=1;$i<=$total_remain;$i++)
  {
   if($i==$total_remain) 
   echo '<option value="'.$i,'" selected>'.$i.'</option>'; else  echo '<option value="'.$i,'" >'.$i.'</option>';
  }
echo '</select>';
}else {
   echo '<input type="hidden" name="counterrss" value="0">';
}

        function show($rss)
  {
	global $arrtitles;
	global $arrdescs;
	global $arrlinks; global $title;
	global $q_title;
global $final;global $flagi;
	global $script_base_url;
	//first read the xml file
	$rss=$rss;
	//$rss=str_replace(" ","+",$rss);
	//echo "<LI>Getting file from --".$rss."--";
	if(!($fp=@fopen($rss,"r")))  //open a xml file
		{die("Unable to open XML file");}
	
	if(!($xml_parser=xml_parser_create())) //create parser for xml file
		{die("couldnt create xml parser");}
	xml_set_element_handler($xml_parser,"startelement","endelement");
	xml_set_character_data_handler($xml_parser,"characterdata");

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

}
  

function characterdata($parser, $data) {
	global $count;
	global $last_tag;
	global $current_tag;
	global $arrtitles;
	global $arrdescs;
	global $arrlinks;
   	global $title;global $final;global $flagi;
	if (!$current_tag)
		{return;}
	if($flagi==0)
	{return;}
	if ($count<=0)
		{return;}


	if($current_tag=="TITLE")
		{
			 $arrtitles[$count]["TITLE"].=$data;
 if($arrtitles[$count]["TITLE"]!=""){
			
			$title[$count-1]="'".addslashes($arrtitles[$count]["TITLE"])."'"; 

if($title[$count-1]==""){  $title[$count-1]="''";    }


}

		}		
		

	if($current_tag=="LINK")
		{
			$arrlinks[$count]["LINK"].=$data;
		}

	if($current_tag=="DESCRIPTION")
		{$arrdescs[$count]["DESCRIPTION"].=$data;}
}

function startelement($parser, $name, $attrs) {
	global $current_tag;
	global $last_tag;
	global $count;
	global $arrtitles;
	global $arrdescs;global $flagi;
	global $arrlinks;global $final;
	global $title;
	$last_tag=$current_tag;
	$current_tag=$name;

	if ($current_tag=="ITEM"){
		$count++;$flagi=1;
		$arrtitles[$count]["TITLE"]="";
		$arrlinks[$count]["LINK"]="";
		$arrdescs[$count]["DESCRIPTION"]="";
		$title[$count]="";
	}
}

function endelement($parser, $name) {
	global $current_tag;
	$current_tag="";
}
  
 
?>