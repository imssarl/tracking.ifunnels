<?php
   require_once('function.php');
//  set_time_limit(220);
   
   $txt_Inputurl=$_POST['txt_Inputurl'];
   $txt_Specialkeyword=$_POST['txt_Specialkeyword'];
   $txt_Useragent=$_POST['txt_Useragent'];
   $txt_DontShowLessWords=intval($_POST['txt_DontShowLessWords']);
   $chk_Title=$_POST['chk_Title'];
   $chk_Meta=$_POST['chk_Meta'];
   $chk_Headings=$_POST['chk_Headings'];
   $chk_Email=$_POST['chk_Email'];
   $chk_Alttags=$_POST['chk_Alttags'];
   $chk_Linktext=$_POST['chk_Linktext'];
   $chk_Noframes=$_POST['chk_Noframes'];
   $chk_Comments=$_POST['chk_Comments'];
   $chk_Boldtext=$_POST['chk_Boldtext'];
   $chk_Italictext=$_POST['chk_Italictext'];
   $chk_CSSFontSizes=$_POST['chk_CSSFontSizes'];
   $txt_occuratleast=$_POST['txt_occuratleast'];
   $RadioSortreport=$_POST['RadioSortreport'];
   $txt_Maxdensity=$_POST['txt_Maxdensity'];
   $txt_Mindensity=$_POST['txt_Mindensity'];
   $txt_Closeiswithin=$_POST['txt_Closeiswithin'];
   $txt_LeastWords=$_POST['txt_LeastWords'];
   $txt_Targeteddensity=$_POST['txt_Targeteddensity'];
   $txt_charactercount=$_POST['txt_charactercount'];
   $chk_TableLegend=$_POST['chk_TableLegend'];
   $chk_Calculations=$_POST['chk_Calculations'];
   $chk_Nodropdownnavigation=$_POST['chk_Nodropdownnavigation'];
   $Opt_Density=$_POST['OptDensity'];
   $txt_occuratleast=intval($_POST['txt_occuratleast']);
   $chk_stopWords=$_POST['chk_stopWords'];
   $txtstopcustomwords=$_POST['txtstopcustomwords'];
   $txtadultcustomwords=$_POST['txtadultcustomwords'];
   $chk_adultWords =$_POST['chk_adultWords'];
   $txtpoisoncustomwords=$_POST['txtpoisoncustomwords'];
   $chk_poisonWords=$_POST['chk_poisonWords'];
   $txt_Maxdensity=$_POST['txt_Maxdensity'];
   $txt_Mindensity=$_POST['txt_Mindensity'];
   
 /*  echo "<li>chk_Title".$chk_Title;
   echo "<li>chk_Meta".$chk_Meta;
   echo "<li>chk_Alttags".$chk_Alttags;
   echo "<li>chk_Linktext".$chk_Linktext;
   echo "<li>chk_Headings".$chk_Headings;
   echo "<li>chk_Boldtext".$chk_Boldtext;
   echo "<li>chk_Italictext".$chk_Italictext; */
global $contents;
  /*    $handle=fopen($txt_Inputurl,"rb");
      $contents = fread($handle, filesize($txt_Inputurl));
      fclose($handle); */
$contents = get_url($txt_Inputurl);

function Wrdp($para,$txt_DontShowLessWords,$Opt_Density)
{   
	global $chk_Alttags,$chk_Linktext,$chk_Headings,$chk_Boldtext,$chk_Italictext,$txt_occuratleast;
	global $chk_stopWords,$txt_Inputurl,$txtstopcustomwords,$txtadultcustomwords,$chk_adultWords,$txtpoisoncustomwords,$chk_poisonWords;
	global $txt_Mindensity,$txt_Maxdensity;
	global $varBoldText,$varHeaderText,$varItalicText,$varAnchorText,$varAlternateText;
	$varBoldText=trim(RemoveSpecialChar(WordFoundInBoldText($para,$para)));
	//$varBoldText=ConvertTextToLines($varBoldText);
	
    $varHeaderText=trim(RemoveSpecialChar(WordsFoundInHTag($para,$para)));	
	//$varHeaderText=ConvertTextToLines($varHeaderText);
	
	$varItalicText=trim(RemoveSpecialChar(WordFoundInItalicText($para,$para)));
	//$varItalicText=ConvertTextToLines($varItalicText);
	
	$varAnchorText=trim(RemoveSpecialChar(WordsFoundInAnchorTags($para,$para))); 
	//$varAnchorText=ConvertTextToLines($varAnchorText);
	
	$varAlternateText=trim(RemoveSpecialChar(WordsFoundInAlternateText($para,$para)));
	//$varAlternateText=ConvertTextToLines($varAlternateText);
	
	$varTotalCount=Total_WordCount($para,$txt_DontShowLessWords);
	
	$varCreateTable="";
	$varTotal=0;
	$varString=RemoveScriptTags($para,$para);
	$varString = strip_tags($varString);
	
	$varString=RemoveSpecialChar($varString);
	$varString=strtolower($varString);
	
	/////////////////////////////////////////////////
	// PULL TWO WORDPHRASES Words from the Content // 
	/////////////////////////////////////////////////
	
	
	//////////////////////////////////////////
	// Remove Ignore Words from the Content // 
	//////////////////////////////////////////
	if($chk_stopWords=="on")
	{
		$txtIgnoreWords=IgnoreWordsRead();
		$arrIgnoreWords=explode(",",$txtIgnoreWords);
		if(count($arrIgnoreWords)>0){
		echo "<li> <b> Ignore words :</b>";
		for($i=0;$i<count($arrIgnoreWords);$i++)
		{   echo " ".$arrIgnoreWords[$i];
			$varString = str_replace(" $arrIgnoreWords[$i] ", " ",$varString);
		}
		}
	} else {
		$arrIgnoreWords=explode(",",$txtstopcustomwords);
		if (strlen($txtstopcustomwords)>0){
			echo "<li> <b> Ignore words :</b>";
			for($i=0;$i<count($arrIgnoreWords);$i++)
			{	
				echo " ".$arrIgnoreWords[$i];
				$varString = str_replace(" $arrIgnoreWords[$i] ", " ", $varString);
			}
	   }
	}
	
	///////////////////////////////////////////
	
	//////////////////////////////////////////
	// Remove Adult Words from the Content //
	//////////////////////////////////////////
	if($chk_adultWords=="on")
	{
		$txtadultcustomwords=AduldWordsRead();
		$arrIgnoreWords=explode(",",$txtadultcustomwords);
		
		if(count($arrIgnoreWords)>0){
		echo "<li> <b> Adult words :</b>";
		for($i=0;$i<count($arrIgnoreWords);$i++)
		{   echo " ".$arrIgnoreWords[$i];
			$varString = str_replace(" $arrIgnoreWords[$i] ", " ",$varString);
		}
		}
	} else {
		$arrIgnoreWords=explode(",",$txtadultcustomwords);
		 
		if (strlen($txtadultcustomwords)>0){
			echo "<li> <b> Adult words :</b>";
			for($i=0;$i<count($arrIgnoreWords);$i++)
			{	
				echo " ".$arrIgnoreWords[$i];
				$varString = str_replace(" $arrIgnoreWords[$i] ", " ", $varString);
			}
	   }
	}
	
	///////////////////////////////////////////
	//////////////////////////////////////////
	// Remove Poision Words from the Content // 
	//////////////////////////////////////////
	if($chk_poisonWords=="on")
	{
		$txtpoisoncustomwords=poisionWordsRead();
		$arrIgnoreWords=explode(",",$txtpoisoncustomwords);
		if(count($arrIgnoreWords)>0){
		echo "<li> <b> Poision words :</b>";
		for($i=0;$i<count($arrIgnoreWords);$i++)
		{   echo " ".$arrIgnoreWords[$i];
			$varString = str_replace(" $arrIgnoreWords[$i] ", " ",$varString);
		}
		}
	} else {
		$arrIgnoreWords=explode(",",$txtpoisoncustomwords);
		if (strlen($txtpoisoncustomwords)>0){
			echo "<li> <b> Poision words :</b>";
			for($i=0;$i<count($arrIgnoreWords);$i++)
			{	
				echo " ".$arrIgnoreWords[$i];
				$varString = str_replace(" $arrIgnoreWords[$i] ", " ",$varString);
			}
	   }
	}
	
	///////////////////////////////////////////
	
	
	$varRikin=split(" ",$varString);

	foreach($varRikin as $varRikin1)
	{
		if((trim($varRikin1)!="") && (trim($varRikin1)!=" ") && (trim(strlen($varRikin1))>$txt_DontShowLessWords) && (CountNoofOccurance($varRikin1,$varString,$txt_DontShowLessWords)>$txt_occuratleast))
		{   
		    if(!(is_numeric($varRikin1)))
		    {
			$varMid[]=$varRikin1;
			}
		}
	}
	
	$varTemp=array_unique($varMid);

	$i=0;

	foreach ($varTemp as $v1)
	{
		if(($Opt_Density=="DensityByUniqueWords") && (strlen($v1)>$txt_DontShowLessWords)) 
		{
			$varDensity=((CountNoofOccurance($v1,strtolower($para),$txt_DontShowLessWords)/count($varTemp))*100);
			if((round($varDensity,2)>round(floatval($txt_Mindensity),2)) && (round($varDensity,2)<round(floatval($txt_Maxdensity),2)))
			{
			$var_new[$i][0]=$v1;
			$var_new[$i][1]=round($varDensity,2);
			$i=$i+1;
			}
		}

		if(($Opt_Density=="DensityByTotalWords") && (strlen($v1)>$txt_DontShowLessWords))
		{	
			if((round($varDensity,2)>round(floatval($txt_Mindensity),2)) && (round($varDensity,2)<round(floatval($txt_Maxdensity),2)))
			{
			$varDensity=((CountNoofOccurance($vl,strtolower($para),$txt_DontShowLessWords)/$varTotalCount)*100);
			$var_new[$i][0]=$v1;
			$var_new[$i][1]=round($varDensity,2);	
			$i=$i+1;
			}
		}
	} 

	function compare($x, $y)
	{
		 if ( $x[1] == $y[1] )
		  return 0;
		 else if ( $x[1] > $y[1] )
		  return -1;
		 else
		  return 1;
	}

	usort($var_new,compare);
// 	echo "\n Sort Arrar \n";
		
/*	for($x=0;$x<count($var_new);$x++)
	{
			
			echo "<li> ".$var_new[$x][0];
			echo  "-".$var_new[$x][1];
	}*/
		
//foreach ($var as $tp)
//{
//for($x=0;$x<count($var_new);$x++)
//{
 // for($y=0;$y<1;$y++)
//  {		
 	$counter=0;
	$varHeaderCount=0;
	$varBoldCount=0;
	$varItalicCount=0;
	$varAnchorCount=0;
	$varAlternateCount=0;
	$varAnchor="";
	for($x=0;$x<count($var_new);$x++)
    {   
		$val=$var_new[$x][0];

		if(trim($val) != "")		
		{
		// $var_new[] = $val;
         $counter++;
    	/* Finding Text Occurance In Bold Text	*/
		
		$varTotalOccurance=CountNoofOccurance($val,$varString,$txt_DontShowLessWords);

		if ($chk_Boldtext=="on"){
		$varBoldCount=CountNoofOccurance($val,$varBoldText,$txt_DontShowLessWords);
		if ($varBoldCount>0)
		{	
			$varAnchor="<a href='' title='".$varBoldText."'> B </a>";
		   //<a onMouseover="ddrivetip('The default title of each page. This title will be ignored on article details pages.', '#AAD5FF',350)"; onMouseout="hideddrivetip()">Help</a>
			//$varAnchor="<a onMouseover=\"ddrivetip('hi','#AAD5FF',350)\"; onMouseout=\"hideddrivetip()\"> B </a>";
		}
		}

		/* Finding Text in Hearder Tag */
		if ($chk_Headings=="on")
		{
		$varHeaderCount=CountNoofOccurance($val,$varHeaderText,$txt_DontShowLessWords);
		if ($varHeaderCount>0)
		{
		$varAnchor="". $varAnchor." <a href='' title='".$varHeaderText."'> H </a>";
        }
		}
		
		/* Finding Text in Italic Tag */
		if ($chk_Italictext=="on"){
		$varItalicCount=CountNoofOccurance($val,$varItalicText,$txt_DontShowLessWords);
		if ($varItalicCount>0)
		{
			$varAnchor="". $varAnchor."<a href='' title='".$varItalicText."'> I </a>";
		}
		}
		
		/* Finding Text in Anchor Tag */
		if ($chk_Linktext=="on"){
		$varAnchorCount=CountNoofOccurance($val,$varAnchorText,$txt_DontShowLessWords);
		if ($varAnchorCount>0)
		{
			$varAnchor="". $varAnchor."<a href='' title='".$varAnchorText."'> A </a>";
		}	
		}
		/* Finding Text in Alternate Tag */
		if ($chk_Alttags=="on")
		{
		$varAlternateCount=CountNoofOccurance($val,$varAlternateText,$txt_DontShowLessWords);
		if ($varAlternateCount>0)
		{
			$varAnchor="". $varAnchor."<a href='' title='".$varAlternateText."'> Alt </a>";
		}	
		}	
		/* Print Fianally all Details with Total No Count*/	
		
		if ($varTotalOccurance>0)
		{
   	    	$varCreateTable="". $varCreateTable."<tr> <td>".$counter." </td><td width='9%'> ". $val ." </td> <td width='9%'>".$varTotalOccurance." ". $varAnchor ." </td><td width='9%'>". $var_new[$x][1] ."% </td></tr>";
		} else
		{
			$varCreateTable="". $varCreateTable."<tr> <td>". $counter." </td> <td width='9%'> ". $val ." </td> <td width='9%'>". $varAnchor ." </td> <td width='9%'>". $var_new[$x][1] ."% </td></tr>";
		}
		}

		$varAlternateCount=0;
		$varAnchorCount=0;
		$varItalicCount=0;
		$varHeaderCount=0;
		$varBoldCount=0;
		$varAnchor="";
	//}   
	}
//	T,H,L,B,I,A
return $varCreateTable;
}
function CreateTableOfTwoWordsPhrases($para,$txt_DontShowLessWords,$Opt_Density)
{
	global $chk_Alttags,$chk_Linktext,$chk_Headings,$chk_Boldtext,$chk_Italictext,$txt_occuratleast;
	global $chk_stopWords,$txt_Inputurl,$txtstopcustomwords,$txtadultcustomwords,$chk_adultWords,$txtpoisoncustomwords,$chk_poisonWords;
	global $txt_Mindensity,$txt_Maxdensity;
	global $varBoldText,$varHeaderText,$varItalicText,$varAnchorText,$varAlternateText;
	$varString=RemoveScriptTags($para,$para);
	$varString = strip_tags($varString);
	
	$varString=RemoveSpecialChar($varString);
	$varString=strtolower($varString);

echo "<li> ->Bold ".$varBoldText;
echo "<li> ->hea ".$varHeaderText;
echo "<li> ->it ".$varItalicText;
echo "<li> ->anc ".$varAnchorText;

/*	function compare($x, $y)
	{
		 if ( $x[1] == $y[1] )
		  return 0;
		 else if ( $x[1] > $y[1] )
		  return -1;
		 else
		  return 1;
} */
	
	$Twowordphrases=Twowordphrases($varString,$txt_DontShowLessWords);
	usort($Twowordphrases,compare);  // sort by no or times occurancy
	echo "count ->".count($Twowordphrases);
 	$Twowordphrases=remove_dups($Twowordphrases, 0); // Remove Duplicate Words
	$i=0;
	foreach($Twowordphrases as $rr=>$v)   // Calculate Density of two word phrases
	{
			$varStr=$Twowordphrases[$rr][0];
			$varOccurancy=$Twowordphrases[$rr][1];
	if(($Opt_Density=="DensityByUniqueWords") && (strlen($varStr)>$txt_DontShowLessWords)) 
		{
			$varDensity=(($varOccurancy/count($Twowordphrases))*100);
			if((round($varDensity,2)>round(floatval($txt_Mindensity),2)) && (round($varDensity,2)<round(floatval($txt_Maxdensity),2)))
			{
			$TwowordphrasesWithDensity[$rr][0]=$varStr;
			$TwowordphrasesWithDensity[$rr][1]=round($varDensity,2);
			//echo "<li>".$TwowordphrasesWithDensity[$rr][0];
			//echo "<li>-->>".$TwowordphrasesWithDensity[$rr][1];
			//$i=$i+1;
			}
		}

		if(($Opt_Density=="DensityByTotalWords") && (strlen($v1)>$txt_DontShowLessWords))
		{	
			if((round($varDensity,2)>round(floatval($txt_Mindensity),2)) && (round($varDensity,2)<round(floatval($txt_Maxdensity),2)))
			{
			$varDensity=(($varOccurancy/$varTotalCount)*100);
			$TwowordphrasesWithDensity[$i][0]=$varStr;
			$TwowordphrasesWithDensity[$i][1]=round($varDensity,2);	
			//echo "<li>".$TwowordphrasesWithDensity[$i][0];
			//echo "<li>".$TwowordphrasesWithDensity[$i][1];
			$i=$i+1;
			}
		}
	}
	
	$counter=0;
	$varHeaderCount=0;
	$varBoldCount=0;
	$varItalicCount=0;
	$varAnchorCount=0;
	$varAlternateCount=0;
	$varAnchor="";
	for($x=0;$x<count($Twowordphrases);$x++)
    {   
		$val=$Twowordphrases[$x][0];
		//echo "<li>".$val;
		if(trim($val) != "")		
		{
		// $var_new[] = $val;
         $counter++;
    	/* Finding Text Occurance In Bold Text	*/
		
		$varTotalOccurance= $Twowordphrases[$x][1];  //-------->CountNoofOccurance($val,$varString,$txt_DontShowLessWords);
		//echo "<li>".$varTotalOccurance;
        if ($chk_Boldtext=="on"){
		$varBoldCount=CountNoofOccurance($val,$varBoldText,$txt_DontShowLessWords);
		if ($varBoldCount>0)
		{	
			$varAnchor="<a href='' title='".$varBoldText."'> B </a>";
		   //<a onMouseover="ddrivetip('The default title of each page. This title will be ignored on article details pages.', '#AAD5FF',350)"; onMouseout="hideddrivetip()">Help</a>
			//$varAnchor="<a onMouseover=\"ddrivetip('hi','#AAD5FF',350)\"; onMouseout=\"hideddrivetip()\"> B </a>";
		}
		}

		/* Finding Text in Hearder Tag */
     	if ($chk_Headings=="on")
		{
		$varHeaderCount=CountNoofOccurance($val,$varHeaderText,$txt_DontShowLessWords);
		if ($varHeaderCount>0)
		{
		$varAnchor="". $varAnchor." <a href='' title='".$varHeaderText."'> H </a>";
        }
		}
		
		/* Finding Text in Italic Tag */
        if ($chk_Italictext=="on"){
		$varItalicCount=CountNoofOccurance($val,$varItalicText,$txt_DontShowLessWords);
		if ($varItalicCount>0)
		{
			$varAnchor="". $varAnchor."<a href='' title='".$varItalicText."'> I </a>";
		}
		}
		
		/* Finding Text in Anchor Tag */
    	if ($chk_Linktext=="on"){
		$varAnchorCount=CountNoofOccurance($val,$varAnchorText,$txt_DontShowLessWords);
		if ($varAnchorCount>0)
		{
			$varAnchor="". $varAnchor."<a href='' title='".$varAnchorText."'> A </a>";
		}	
		}
		/* Finding Text in Alternate Tag */
       if ($chk_Alttags=="on")
		{
		$varAlternateCount=CountNoofOccurance($val,$varAlternateText,$txt_DontShowLessWords);
		if ($varAlternateCount>0)
		{
			$varAnchor="". $varAnchor."<a href='' title='".$varAlternateText."'> Alt </a>";
		}	
		}	
		/* Print Fianally all Details with Total No Count*/	
		
 		if ($varTotalOccurance>0)
         {
   	    	$varCreateTable="". $varCreateTable."<tr> <td>".$counter." </td><td width='9%'> ". $val ." </td> <td width='9%'>".$varTotalOccurance." ". $varAnchor ." </td><td width='9%'>". $TwowordphrasesWithDensity[$x][1] ."% </td></tr>";
		} //else
//		{
			//$varCreateTable="". $varCreateTable."<tr> <td>". $counter." </td> <td width='9%'> ". $val ." </td> <td width='9%'>". $varAnchor ." </td> <td width='9%'>". $TwowordphrasesWithDensity[$x][1] ."% </td></tr>";
//		}
		}
		$varAlternateCount=0;
		$varAnchorCount=0;
		$varItalicCount=0;
		$varHeaderCount=0;
		$varBoldCount=0;
		$varAnchor="";
	//}   
	}
	//	T,H,L,B,I,A
return $varCreateTable;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Page Summary </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip

</script>
</head>
<body>

<form name="form1" method="post" action="">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="8" class="heading">Welcome to <?php echo $txt_Inputurl ?></td>
    </tr>
    <tr>
      <td colspan="8">&nbsp; </td>
    </tr>
    <tr>
      <td colspan="8" class="heading">Header Data</td>
    </tr>
    <tr>
      <td colspan="8"><textarea name="header_data" cols="90" rows="12" id="header_data"><?php 
	  $response = get_headers($txt_Inputurl,1);
	  $header_data="";
    	foreach ($response as $head)
		{
			$header_data=$header_data."\n".$head;
		}
	   echo $header_data; ?> </textarea></td>
    </tr>
    <tr>
      <td colspan="8" class="heading"> HTML Code</td>
    </tr>
    <tr>
      <td colspan="8"><textarea name="textarea" COLS=90 ROWS=12 MAXSIZE=24549><?php echo $contents; ?></textarea></td>
    </tr>
	 <tr>
	   <td colspan="8" class="heading">Totals, counts, special words     
    </tr>
	 <tr>
      <td colspan="8"><?php $tempContents=$contents; 
      $varTotalCount=Total_WordCount($tempContents,$txt_DontShowLessWords);
      echo "<b>Total Word Count in the File::</b>" .$varTotalCount;
      ?>
    </tr>
	  <tr>
      <td colspan="8"><?  // to revise
	  $varUniqueCount=UniqueWordCount($contents,$txt_DontShowLessWords,$txt_occuratleast);
	  echo " <b>Total ".$varUniqueCount." unique words found in file </b>";
	   ?>
     <hr></td>
    </tr>
	  <tr>
      <td colspan="8"><b class="heading">Page Elements</b></td>
    </tr>
	<? if($chk_Title=="on")
     { ?>
    <tr>
     <td colspan="8" >
<? 
	$title=Title_Tag_Content($contents);
	echo "<B> Title Tag ::</B>".RemoveSpecialChar($title);
?>
</td>
    </tr>
	<? }?>
    <tr>
      <td colspan="8">
     </td>
    </tr>
	    <tr>
	      <td colspan="8">&nbsp;</td>
    </tr>
	<? if($chk_Meta=="on")
     { ?>
	    <tr>
        <td colspan="8"> <? $tags=get_meta_tags($txt_Inputurl);
	    echo "<b> Meta Author Tags ::</b>".RemoveSpecialChar($tags['author']); ?> 
	    </td>
        </tr>
		
    <tr>
      <td colspan="8"><? echo "<b>Meta Keywords Tag::</b>".RemoveSpecialChar($tags['keywords']); ?> </td>
    </tr>
	
	<tr>
      <td colspan="8"><? echo "<b>Meta Description Tag::</b>".RemoveSpecialChar($tags['description']);   ?> </td>
   
    </tr>
	
	<tr>
      <td colspan="8"><? echo "<b>Meta Geo_Position Tag::</b>".RemoveSpecialChar($tags['geo_position']); ?> <hr></td>
    </tr>
	<? }?>
	<? if($chk_Headings=="on")
	{ ?>
    <tr>
      <td colspan="8"><? 
	  //ok  
 	  $tempContents=$contents;
     $vartempHead=WordsFoundInHTag($tempContents,$tempContents);
     echo " <B> Text Found In Heading Tags::</B>" .trim(RemoveSpecialChar($vartempHead));
	  ?>
<hr></td>
    </tr>
	<? } ?>
<? if ($chk_Linktext=="on")
{ ?>
    <tr>
      <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="8"><? //ok
     $varAnchorWords=WordsFoundInAnchorTags($contents,$contents); 
	 echo " <b> Text Found In Anchor Tags::</b>".trim(RemoveSpecialChar($varAnchorWords));
	  ?>
 <hr></td>
    </tr>
	<? } ?>
    <tr>
      <td colspan="8">&nbsp;</td>
    </tr>
	<? if($chk_Alttags=="on")
	{ ?>
    <tr>
      <td colspan="8"><? //ok
	  
	  $varAlternateText=WordsFoundInAlternateText($contents,$contents);
	  echo " <b> Text Found In Alternate Text:: </b>".trim(RemoveSpecialChar($varAlternateText));
	  ?>
<hr></td>
    </tr> <? } ?>
	<? if ($chk_Boldtext=="on")
	{ ?>
     <tr>
       <td colspan="8"><?  //ok 
	    $varBoldText=WordFoundInBoldText($contents,$contents);
	    echo " <b> Text Found In Bold Tags:</b>".trim(RemoveSpecialChar($varBoldText));
      ?>
<hr></td>
    </tr> <? } ?>
	<? if ($chk_Italictext=="on")
	{ ?>
      <tr>
      <td colspan="8"><? $varItalicText=WordFoundInItalicText($contents,$contents);
	  echo "  <b>Text Found In Italic Tags:: </b> ".trim(RemoveSpecialChar($varItalicText));
	  ?>
<hr></td>
    </tr>
	<? } ?>
  </table>
 <table width="100%"  border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td width="9%"><strong> Srno </strong></td>
      <td width="10%"><strong> Word </strong></td>
      <td width="12%"><strong> Repeats </strong></td>
      <td width="19%"><strong> Density </strong></td>
      <td width="14%"><strong> Prominence </strong></td>
      <td width="36%"><strong>  </strong></td>
    </tr>
  
 <p><?  $varWrdp=Wrdp($contents,$txt_DontShowLessWords,$Opt_Density);
     echo "".$varWrdp;
 ?>
 </table>
  </table>
 <table width="100%"  border="1" cellspacing="0" cellpadding="0">
 <tr> Tow Words Phrases</tr>
    <tr>
      <td width="9%"><strong> Srno </strong></td>
      <td width="10%"><strong> Word </strong></td>
      <td width="12%"><strong> Repeats </strong></td>
      <td width="19%"><strong> Density </strong></td>
      <td width="14%"><strong> Prominence </strong></td>
      <td width="36%"><strong>  </strong></td>
    </tr>
  
 <p><?  $varWrdp=CreateTableOfTwoWordsPhrases($contents,$txt_DontShowLessWords,$Opt_Density);
     echo "".$varWrdp;
 ?>
 </table>

</form>
</body>
</html>
