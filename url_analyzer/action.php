<?php



session_start();



   require_once('functions/url_function.php');



	//ini_set("display-errors","Off");

	



   define('XML_HTMLSAX3', dirname(__FILE__)."/classes/");



   require_once('classes/Safe.php');



   // Instantiate the handler



    $safehtml = new HTML_Safe();



     set_time_limit(220);



    if((isset($_POST['abcd']) && ($_POST['abcd']!="")))



   {



     header("Content-type: application/txt");



     header("Content-Disposition: attachment; filename=keyword.txt");



     echo $_POST['abcd'];



	 exit();



   }



   global $txt_Inputurl;



   global $only_name;



   



   $txt_Inputurl=$_POST['txt_Inputurl'];



  if((@strpos($txt_Inputurl,"http://",0)!=0) || (@strpos($txt_Inputurl,"https://",0)!=0))



	{



	$txt_Inputurl="http://".$txt_Inputurl;



	}



	



	$startstring=@strpos($txt_Inputurl,"http://",0);



	if($startstring==0)



	{



	$only_name=substr($txt_Inputurl,6,strlen($txt_Inputurl));



	}



	$startstring=@strpos($txt_Inputurl,"https://",0);



	if($startstring==0)



	{



	$only_name=substr($txt_Inputurl,7,strlen($txt_Inputurl));



	}



   



	/*if($startstring!="http://" || $startstring!=)



	{



	 $txt_Inputurl=str_replace($txt_Inputurl,"http://","");



     $txt_Inputurl="http://".$txt_Inputurl;



	}*/



   



   



   //$txt_Inputurl=str_replace($txt_Inputurl,"https://","");



   //$txt_Inputurl="https://".$txt_Inputurl;



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







global $contents;    



   /*   $handle=fopen($txt_Inputurl,"rb");



      $contents = fread($handle, filesize($txt_Inputurl));



      fclose($handle); */



$local_contents = get_url($txt_Inputurl);



$contents = $safehtml->parse($local_contents);



//echo strip_tags($result);



/////////////////////////////////////////////////////////////////////////////////////



//this fuction is for single pharse word calculation



function Wrdp($para,$txt_DontShowLessWords,$Opt_Density)



{ 



    global $varTotalCount,$GlobalUniqueCount;



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



	



	/*$var_total_words=Total_WordCount_With_Words($para,$txt_DontShowLessWords);



	for($i=0;$i<count($var_total_words);$i++)



		{   



		echo " ".$var_total_words[$i];



		}*/



	



	$GlobalUniqueCount=UniqueWordCount($para,$txt_DontShowLessWords,$txt_occuratleast);



 // echo "<li>======>GlobalUniqueCount by ashish".$GlobalUniqueCount;



 



	$varCreateTable="";



	$varTotal=0;



	$varString=RemoveScriptTags($para,$para);



	$varString = strip_tags($varString);



	



	$varString=RemoveSpecialChar($varString);



	$varString=strtolower($varString);



	



	



	



	



	//////////////////////////////////////////



	// Remove Ignore Words from the Content // 



	//////////////////////////////////////////



	if($chk_stopWords=="on")



	{



		$txtIgnoreWords=IgnoreWordsRead();



		$arrIgnoreWords=explode(",",$txtIgnoreWords);



		if(count($arrIgnoreWords)>0){



		echo "<b> Ignore words :</b>";



		for($i=0;$i<count($arrIgnoreWords);$i++)



		{   echo " ".$arrIgnoreWords[$i];



			 $varString = str_replace(" ".$arrIgnoreWords[$i]." "," ",$varString);



		}



		}



	} else {



		$arrIgnoreWords=explode(",",$txtstopcustomwords);



		if (strlen($txtstopcustomwords)>0){



			echo "<b> Ignore words :</b>";



			for($i=0;$i<count($arrIgnoreWords);$i++)



			{	



				echo " ".$arrIgnoreWords[$i];



				$varString = str_replace(" ".$arrIgnoreWords[$i]." "," ",$varString);



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



		 echo "<b> Adult words :</b>";



		for($i=0;$i<count($arrIgnoreWords);$i++)



		{   echo " ".$arrIgnoreWords[$i];



			$varString = str_replace(" ".$arrIgnoreWords[$i]." "," ",$varString);



		}



		}



	} else {



		$arrIgnoreWords=explode(",",$txtadultcustomwords);



		 



		if (strlen($txtadultcustomwords)>0){



			echo "<b> Adult words :</b>";



			for($i=0;$i<count($arrIgnoreWords);$i++)



			{	



				echo " ".$arrIgnoreWords[$i];



				$varString = str_replace(" ".$arrIgnoreWords[$i]." "," ",$varString);



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



		echo "<b> Poisson words :</b>";



		for($i=0;$i<count($arrIgnoreWords);$i++)



		{   echo " ".$arrIgnoreWords[$i];



			$varString = str_replace(" ".$arrIgnoreWords[$i]." "," ",$varString);



		}



		}



	} else {



		$arrIgnoreWords=explode(",",$txtpoisoncustomwords);



		if (strlen($txtpoisoncustomwords)>0){



			echo "<b> Poisson words :</b>";



			for($i=0;$i<count($arrIgnoreWords);$i++)



			{	



				echo " ".$arrIgnoreWords[$i];



				$varString = str_replace(" ".$arrIgnoreWords[$i]." "," ",$varString);



			}



	   }



	}



	



	///////////////////////////////////////////



	



	



	$varRikin=split(" ",$varString);

	//echo $txt_DontShowLessWords.'$txt_DontShowLessWords<br>';

	//echo $txt_occuratleast.'txt_occuratleast<br>';

	//print_r($varRikin);

	if(is_array($varRikin)){

    foreach($varRikin as $varRikin1)



	{



		if((trim($varRikin1)!="") && (trim($varRikin1)!=" ") && (trim(strlen($varRikin1))>$txt_DontShowLessWords) && (CountNoofOccurance($varRikin1,$varString,$txt_DontShowLessWords)>$txt_occuratleast))



		{   

			//echo "??";

		    if(!(is_numeric($varRikin1)))



		    {



			$varMid[]=$varRikin1;



			}



		}



	}

	}

//die;

	//actual code of the single keyword start from here	

	

	

	$varTemp=@array_unique($varMid);



    //echo "<li>All Keyword list".count($varTemp);



	



	



	$i=0;

	

	if(is_array($varTemp)){

		foreach ($varTemp as $v1)

	

		{

	

			if(($Opt_Density=="DensityByUniqueWords") && (strlen($v1)>$txt_DontShowLessWords)) 

	

			{

	

				// old one $varDensity=((CountNoofOccurance($v1,strtolower($para),$txt_DontShowLessWords)/count($varTemp))*100);

	

				$total_occurance=CountNoofOccurance($v1,$varString,$txt_DontShowLessWords);

	

				$varDensity=((CountNoofOccurance($v1,$varString,$txt_DontShowLessWords)/($GlobalUniqueCount))*100);

	

				if((round($varDensity,2)>round(floatval($txt_Mindensity),2)) && (round($varDensity,2)<round(floatval($txt_Maxdensity),2)))

	

				{

	

				$var_new[$i][0]=$v1;

	

				$var_new[$i][1]=round($varDensity,2);

	

				$var_new[$i][2]=$total_occurance;

	

				$i=$i+1;

	

				}

	

			}

	

	

	

			if(($Opt_Density=="DensityByTotalWords") && (strlen($v1)>$txt_DontShowLessWords))

	

			{	

	

				$total_occurance=CountNoofOccurance($v1,$varString,$txt_DontShowLessWords);

	

				$varDensity=((CountNoofOccurance($v1,$varString,$txt_DontShowLessWords)/($varTotalCount))*100);

	

				if((round($varDensity,2)>round(floatval($txt_Mindensity),2)) && (round($varDensity,2)<round(floatval($txt_Maxdensity),2)))

	

				{

	

				$var_new[$i][0]=$v1;

	

				$var_new[$i][1]=round($varDensity,2);

	

				$var_new[$i][2]=$total_occurance;	

	

				$i=$i+1;

	

				}

	

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







	@usort($var_new,compare);



//only for testing



/*for($x=0;$x<count($var_new);$x++)



	{



     echo "<li> ".$var_new[$x][0];



	 echo  "-".$var_new[$x][1];



	}*/



		



//@foreach ($var as $tp)



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



	//echo "<li>Unique Words Count".count($var_new);



	for($x=0;$x<count($var_new);$x++)



    {   



		//........................



		if($x%2){$bg="#efefef";}else{$bg="#F4FDFF";}



		$varCreateTable.="<tr bgcolor='".$bg."'>";



		$vk=0;



		do{



		//..........................



		$val=$var_new[$x][0];



        if(trim($val) != "")		



		{



		



        



    	/* Finding Text Occurance In Bold Text	*/



		$varTotalOccurance=CountNoofOccurance($val,$varString,$txt_DontShowLessWords);



        if($chk_Boldtext=="on")



		{



		$varBoldCount=CountNoofOccurance($val,$varBoldText,$txt_DontShowLessWords);



		if ($varBoldCount>0)



		{	



			$varAnchor="<a href='' title='".$varBoldText."'> B </a>";



		}



		}







		/* Finding Text in Header Tag */



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



		    $varCreateTable=$varCreateTable." <td nowrap='nowrap'><input type='checkbox' name='q' id='q' value='".$val."' onclick='return test(this)'>  ". $val ." </td> <td align='center'>".$varTotalOccurance." ". $varAnchor ." </td><td align='center'>".$var_new[$x][1]."% </td>";



   	    	//$varCreateTable=$varCreateTable." <td>".$counter." </td><td > <input type='checkbox' name='q' id='q' value=".$val."> ".$val." </td> <td width='9%'>".$varTotalOccurance." ". $varAnchor ." </td><td >". $var_new[$x][1] ."% </td></tr>";



		} else



		{   



		    $varCreateTable=$varCreateTable." <td nowrap='nowrap'> <input type='checkbox' name='q' id='q' value='".$val."' onclick='return test(this)'> ". $val ." </td> <td align='center'> ". $varAnchor ." </td> <td align='center'>".$var_new[$x][1]."% </td>";



			//$varCreateTable="". $varCreateTable."<tr> <td>". $counter." </td> <td > <input type='checkbox' name='q' id='q' value=".$val.">".$val." </td> <td >".$varAnchor." </td> <td >". $var_new[$x][1] ."% </td></tr>";



		}



		



			



		}







		$varAlternateCount=0;



		$varAnchorCount=0;



		$varItalicCount=0;



		$varHeaderCount=0;



		$varBoldCount=0;



		$varAnchor="";



		//..............................



		$vk++;



		$x++;



		}while($vk<2);



		$varCreateTable.="</tr>";



		//$x--;



	   //.........................



    }



//	T,H,L,B,I,A



return $varCreateTable;



}



function CreateTableOfTwoWordsPhrases($para,$txt_DontShowLessWords,$Opt_Density,$NoOfwords)



{   



    global $varTotalCount,$GlobalUniqueCount;



	global $chk_Alttags,$chk_Linktext,$chk_Headings,$chk_Boldtext,$chk_Italictext,$txt_occuratleast;



	global $chk_stopWords,$txt_Inputurl,$txtstopcustomwords,$txtadultcustomwords,$chk_adultWords,$txtpoisoncustomwords,$chk_poisonWords;



	global $txt_Mindensity,$txt_Maxdensity;



	global $varBoldText,$varHeaderText,$varItalicText,$varAnchorText,$varAlternateText;



	$varString=RemoveScriptTags($para,$para);



	$varString = strip_tags($varString);



	$varString=RemoveSpecialChar($varString);



	$varString=strtolower($varString);











    $Twowordphrases=Twowordphrases($varString,$txt_DontShowLessWords,$NoOfwords);



	@usort($Twowordphrases,compare);  // sort by no or times occurancy



	$Twowordphrases=remove_dups($Twowordphrases, 0); // Remove Duplicate Words



	$i=0;

	if(is_array($Twowordphrases)){

		foreach($Twowordphrases as $rr=>$v)   // Calculate Density of two word phrases



		{



			$varStr=$Twowordphrases[$rr][0];



			$varOccurancy=$Twowordphrases[$rr][1];



	



	    if(($Opt_Density=="DensityByUniqueWords") && (strlen($varStr)>$txt_DontShowLessWords)) 



		 {    



		    $varDensity=((($varOccurancy*$NoOfwords)/($GlobalUniqueCount))*100);



			if((round($varDensity,2)>round(floatval($txt_Mindensity),2)) && (round($varDensity,2)<round(floatval($txt_Maxdensity),2)))



			{



			$TwowordphrasesWithDensity[$rr][0]=$varStr;



			$TwowordphrasesWithDensity[$rr][1]=round($varDensity,2);



		    }



		 }



		



		if(($Opt_Density=="DensityByTotalWords") && (strlen($varStr)>$txt_DontShowLessWords))



		 {	



		        $varDensity=((($varOccurancy*$NoOfwords)/($varTotalCount))*100);



			    if((round($varDensity,2)>round(floatval($txt_Mindensity),2)) && (round($varDensity,2)<round(floatval($txt_Maxdensity),2)))



			    {



	           	$TwowordphrasesWithDensity[$rr][0]=$varStr;



				$TwowordphrasesWithDensity[$rr][1]=round($varDensity,2);	



			    }



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



		//........................



		if($x%2){$bg="#ececec";}else{$bg="#F4FDFF";}



		$varCreateTable.="<tr bgcolor='".$bg."'>";



		$vk=0;



		do{



		//..........................



		$val=$Twowordphrases[$x][0];



		if(trim($val) != "")		



		{



		



    	/* Finding Text Occurance In Bold Text	*/



		



		$varTotalOccurance= $Twowordphrases[$x][1];  //-------->CountNoofOccurance($val,$varString,$txt_DontShowLessWords);



		//echo "<li>".$varTotalOccurance;



        if ($chk_Boldtext=="on")



		{



		$varBoldCount=CountNoofOccurance($val,$varBoldText,$txt_DontShowLessWords);



			if ($varBoldCount>0)



			  {	



				$varAnchor="<a href='' title='".$varBoldText."'> B </a>";



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



		



 		//if ($varTotalOccurance>0)



		if (($varTotalOccurance>0)&&($TwowordphrasesWithDensity[$x][1]!=""))



         {	  // $val=strip_tags($val);



   	    	   //$varCreateTable=$varCreateTable." <td nowrap='nowrap'><input type='checkbox' name='q' id='q' value='".$val."'> ".$val." </td>  <td align='center'>".$varTotalOccurance." ". $varAnchor ." </td><td align='center'>".$TwowordphrasesWithDensity[$x][1]."% </td>";



			    $varCreateTable=$varCreateTable." <td nowrap='nowrap'><input type='checkbox' name='q' id='q' value=".$value=str_replace(" ","_",$val)." onclick='return test(this)'> ".$val." </td>  <td align='center'>".$varTotalOccurance." ". $varAnchor ." </td><td align='center'>".$TwowordphrasesWithDensity[$x][1]."% </td>"; 



			  //$varCreateTable="". $varCreateTable."<tr> <td>".$counter." </td><td ><input type='checkbox' name='q' id='q' value=".$val."> ". $val ." </td> <td >".$varTotalOccurance." ". $varAnchor ." </td><td >". $TwowordphrasesWithDensity[$x][1] ."% </td></tr>";



		} //else



//		{



			//$varCreateTable="". $varCreateTable."<tr> <td>". $counter." </td> <td ><input type='checkbox' name='q' id='q' value=".$val."> ". $val ." </td> <td >". $varAnchor ." </td> <td >". $TwowordphrasesWithDensity[$x][1] ."% </td></tr>";



//		}



		}



		$varAlternateCount=0;



		$varAnchorCount=0;



		$varItalicCount=0;



		$varHeaderCount=0;



		$varBoldCount=0;



		$varAnchor="";



		//..............................



		$vk++;



		$x++;



		}while($vk<2);



		$varCreateTable.="</tr>";



		//$x--;



	   //.........................



	



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



<link rel="stylesheet" type="text/css" href="menu/menu.css"> 



<script type="text/javascript" src="menu/chrome.js"></script>



<script language="JavaScript" type="text/JavaScript">



function checkall()



{



for (var i=0;i<document.form1.elements.length;i++)



	{



	    var e = document.form1.elements[i];



		if (e.type == "checkbox")



		{



		e.checked = document.form1.checkbox2.checked;



		}



	}



}







function replaceAll(oldStr,findStr,repStr) {



var srchNdx = 0; // srchNdx will keep track of where in the whole line



// of oldStr are we searching.



var newStr = ""; // newStr will hold the altered version of oldStr.



while (oldStr.indexOf(findStr,srchNdx) != -1)



// As long as there are strings to replace, this loop



// will run.



{



newStr += oldStr.substring(srchNdx,oldStr.indexOf(findStr,srchNdx));



// Put it all the unaltered text from one findStr to



// the next findStr into newStr.



newStr += repStr;



// Instead of putting the old string, put in the



// new string instead.



srchNdx = (oldStr.indexOf(findStr,srchNdx) + findStr.length);



// Now jump to the next chunk of text till the next findStr.



}



newStr += oldStr.substring(srchNdx,oldStr.length);



// Put whatever's left into newStr.



return newStr;



}











function Submitlist(val)



{



var c_value = "";

var ch=0;t=0;f=0;

var final_content="";

var che=false;

var xs=document.form1.q.length;

if(isNaN(xs))

{

	che=true;

	

}

else

{



	for (var i=0; i<xs; i++)

	

	  {

		if (document.form1.q[i].checked)

			{

				che=true;

				break;

			}

		else

			{

				che=false;

				

			}

	  }

}



if(isNaN(xs))

{

	c_value = document.form1.q.value;

}

else

{

	for (var i=0; i<document.form1.q.length; i++)

	

	  {

	

		   if (document.form1.q[i].checked)

	

		   {	//ch=0;

				

				if (c_value =="")

		

				{

		

				c_value = document.form1.q[i].value;

		

				c_value=replaceAll(c_value ,"_"," ");

		

				}

		

				else

		

				{

		

				c1_value = document.form1.q[i].value;

		

				c1_value=replaceAll(c1_value ,"_"," ");

		

				c_value = c_value + "," + c1_value;

		

				}

				

				//

			}

			

	

	  }

}	  

 //alert(ch);

  if(che==false){

  

  alert("Please select at least one check box to export");

			//document.form1.q[i].focus();

			return false;

	}

document.form1.abcd.value=c_value;



document.form1.submit(); 



}





function Submitlist_view()
{

var c_value = "";
var final_content="";
var che=false;
var xs=document.form1.q.length;
if(isNaN(xs))
{
	che=true;
}
else
{
	for (var i=0; i<document.form1.q.length; i++)
	  {
		if (document.form1.q[i].checked)
			{
				che=true;
				break;
			}
		else
			{
				che=false;
			}
	  }
}
if(isNaN(xs))
{
	c_value = document.form1.q.value;
}
else
{
for (var i=0; i<document.form1.q.length; i++)
  {
       if (document.form1.q[i].checked)
	   {
			if (c_value =="")
			{
			c_value = document.form1.q[i].value;
			c_value=replaceAll(c_value ,"_"," ");
			}
			else
			{
			c1_value = document.form1.q[i].value;
			c1_value=replaceAll(c1_value ,"_"," ");
			c_value = c_value + "," + c1_value;
			}
		}
  }

 } 

  if(che==false){
  alert("Please select at least one check box to export");
			//document.form1.q[i].focus();
			return false;
	}
document.form1.abcd.value=c_value;
window.open('view_keyword.php', 'newwindow','height=400,width=500,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no,checkbox=yes');
}





/*function Submitlist()



{



var c_value = "";



var final_content="";



for (var i=0; i<document.form1.q.length; i++)



  {



       if (document.form1.q[i].checked)



	   {



		if (c_value=="")



		{



		c_value = document.form1.q[i].value;



	   // c_value=replaceAll(c_value ,"_"," ");



    	}



		else



		{



		//c1_value = document.form1.q[i].value;



		//c1_value=replaceAll(c1_value ,"_"," ");



		c_value = c_value + "," + document.form1.q[i].value;



		}



		}



  }



  alert(c_value);



document.form1.abcd.value=c_value;



document.form1.submit(); 



}



*/







/*function Submitlist()



{



var c_value = "";



var final_content="";



for (var i=0; i<document.form1.q.length; i++)



  {



       if (document.form1.q[i].checked)



	   {



		if (c_value =="")



		c_value = document.form1.q[i].value.replace("_"," ");  



		else



		c_value = c_value + "," + document.form1.q[i].value.replace("_"," "); 



	   }



  }



document.form1.abcd.value=c_value;



document.form1.submit(); 



}*/



function test(theElement)

	{	

		var tForm = theElement.form, z = 0;

		var ch=document.getElementById("checkbox2").checked;  

		//alert("??");

		//alert(ch);

		for(z=0;z<tForm.length;z++)



		{

			// tForm[z].checked = theElement.checked;	      

			if(tForm[z].checked==true && ch==true)

				document.getElementById("checkbox2").checked=false;

		      



		}

		

	}

</script>







</head>



<body>



<table width="100%" border="0" cellspacing="0" cellpadding="0">



  <tr>



    <td><?php include("header.php");?></td>



  </tr>

<tr>



<tr> <td align="left" style="padding-left:10px;">

<a class="a1" href="../index.php">Home >></a>

<a class="a1" href="url.php">url analyzer >></a> action</td>



	</tr>

<tr>

    <td align="left" height="20"></td>

  </tr>

<td>



<table width="95%"  border="0" align="center" cellpadding="3" cellspacing="0" class="border">



<form name="form1" method="post" action="">



<input type="hidden" name="abcd" value="">



    <tr>



      <td colspan="8" bgcolor="#006699" class="whiteheading">Welcome to <?php echo $txt_Inputurl; ?></td>



    </tr>



    <tr>



      <td colspan="8" align="left"><?php include('menu/manage.php');?> </td>



    </tr>



    <tr>



      <td colspan="8" class="heading">Header Data</td>



    </tr>



    <tr>



      <td colspan="8"><textarea name="header_data" cols="90" rows="12" class="inputbox" id="header_data"><?php 



	  $response = @get_headers($txt_Inputurl,1);



	  $header_data="";

		if(is_array($response)){

			foreach ($response as $head)

	

			{

	

				$header_data=$header_data."\n".$head;

	

			}

		}

	   echo $header_data; ?> </textarea></td>



    </tr>



    <tr>



      <td colspan="8" class="heading"> HTML Code</td>



    </tr>



    <tr>



      <td colspan="8"><textarea name="textarea" COLS=90 ROWS=12 MAXSIZE=24549><?php echo $local_contents; ?></textarea></td>



    </tr>



	 <tr>



	   <td colspan="8" class="heading">Totals, counts, special words     



    </tr>



	 <tr>



      <td colspan="8"><?php $tempContents=$contents; 



      $varTotalCount=Total_WordCount($tempContents,$txt_DontShowLessWords);



      echo "<b>Total Word Count in the File::</b>" .$varTotalCount;



	 // $var_total_words=Total_WordCount_With_Words($local_contents,$txt_DontShowLessWords);



	 /*  for($i=0;$i<count($var_total_words);$i++)



		{   



		echo " <li> $i=====>".$var_total_words[$i];



		}*/



	



	  



      ?>



    </tr>



	  <tr>



      <td colspan="8"><?php // to revise



	  $varUniqueCount=UniqueWordCount($contents,$txt_DontShowLessWords,$txt_occuratleast);



	  echo " <b>Total ".$varUniqueCount." unique words found in file </b>";



	   ?>



     <hr></td>



    </tr>



	  <tr>



      <td colspan="8"><b class="heading">Page Elements</b></td>



    </tr>



	<?php if($chk_Title=="on")



     { ?>



    <tr>



     <td colspan="8" >



<?php $title=Title_Tag_Content($contents);



	echo "<B> Title Tag ::</B>".RemoveSpecialChar($title);



?>



</td>



    </tr>



	<?php }?>



    <tr>



      <td colspan="8">



     </td>



    </tr>



	    <tr>



	      <td colspan="8">&nbsp;</td>



    </tr>



	<?php if($chk_Meta=="on")



     { ?>



	    <tr>



        <td colspan="8"> <?php $tags=@get_meta_tags($txt_Inputurl);



	    echo "<b> Meta Author Tags ::</b>".RemoveSpecialChar($tags['author']); ?> 



	    </td>



        </tr>



		



    <tr>



      <td colspan="8"><?php echo "<b>Meta Keywords Tag::</b>".RemoveSpecialChar($tags['keywords']); ?> </td>



    </tr>



	



	<tr>



      <td colspan="8"><?php echo "<b>Meta Description Tag::</b>".RemoveSpecialChar($tags['description']);   ?> </td>



   



    </tr>



	



	<tr>



      <td colspan="8"><?php echo "<b>Meta Geo_Position Tag::</b>".RemoveSpecialChar($tags['geo_position']); ?> <hr></td>



    </tr>



	<?php }?>



	<?php if($chk_Headings=="on")



	{ ?>



    <tr>



      <td colspan="8"> <?php //ok  



 	  $tempContents=$contents;



     $vartempHead=WordsFoundInHTag($tempContents,$tempContents);



     echo " <B> Text Found In Heading Tags::</B>" .trim($vartempHead);



	  ?>



<hr></td>



    </tr>



	<?php } ?>



<?php if ($chk_Linktext=="on")



{ ?>



    <tr>



      <td colspan="8">&nbsp;</td>



    </tr>



    <tr>



      <td colspan="8"><?php //ok



     $varAnchorWords=WordsFoundInAnchorTags($contents,$contents); 



	 echo " <b> Text Found In Anchor Tags::</b>".trim(RemoveSpecialChar($varAnchorWords));



	  ?>



 <hr></td>



    </tr>



	<?php } ?>



    <tr>



      <td colspan="8">&nbsp;</td>



    </tr>



	<?php if($chk_Alttags=="on")



	{ ?>



    <tr>



      <td colspan="8"><?php //ok



	  



	  $varAlternateText=WordsFoundInAlternateText($contents,$contents);



	  echo " <b> Text Found In Alternate Text:: </b>".trim(RemoveSpecialChar($varAlternateText));



	  ?>



<hr></td>



    </tr> <?php } ?>



	<?php if ($chk_Boldtext=="on")



	{ ?>



     <tr>



       <td colspan="8"><?php //ok 



	     $varBoldText=WordFoundInBoldText($contents,$contents);



		 // $varBoldText=Bold_Tag_Content($contents);



	     echo " <b> Text Found In Bold Tags:</b>".trim($varBoldText);



      ?>



<hr></td>



    </tr> <?php } ?>



	<?php if ($chk_Italictext=="on")



	{ ?>



      <tr>



      <td colspan="8"><?php $varItalicText=WordFoundInItalicText($contents,$contents);



	  echo "  <b>Text Found In Italic Tags:: </b> ".trim(RemoveSpecialChar($varItalicText));



	  ?>



<hr></td>



    </tr>



	<?php } ?>



	 <tr>



      <td colspan="8"><input name="select_keyword" type="button" class="button" onClick=" return Submitlist(this);" value="Export Keywords"> 



      <input name="select_keyword" type="button" class="button" onClick=" return Submitlist_view();" value="View Keywords"> 



      <label for="checkbox2"> <input type="checkbox" id="checkbox2" name="checkbox2" value="checkbox" onClick="javascript:checkall();"><a href="#"> Select All </a></label>      </td>



     </tr>



	<tr><td> 



		<table width="95%"  border="0" align="center"cellpadding="3" cellspacing="1"   bordercolor="#666666" class="border">



			<tr > <td colspan="6"><span class="heading">Single Words Phrases </span></td>



			<tr bgcolor="#006699" class="whiteheading">



			  <td width="22%" align="center" nowrap="nowrap"><strong> Word </strong></td>



			  <td width="10%" align="center"><strong> Repeats </strong></td>



			  <td width="10%" align="center"><strong> Density </strong></td>



			  <td width="22%" align="center" nowrap="nowrap"><strong> Word </strong></td>



			  <td width="10%" align="center"><strong> Repeats </strong></td>



			  <td width="10%" align="center"><strong> Density </strong></td>



			  </tr>



		  



		 <p><?php $varWrdp=Wrdp($contents,$txt_DontShowLessWords,$Opt_Density);



			 echo "".$varWrdp;



		 ?></p>



		 </table>



     </td></tr>



 <tr>



 <td>



	 <table width="95%"  border="0" align="center"cellpadding="3" cellspacing="1"   bordercolor="#666666" class="border">



			<tr > <td colspan="6"><span class="heading">Two Words Phrases </span></td>



			<tr bgcolor="#006699" class="whiteheading">



			  <td width="22%" align="center" nowrap="nowrap"><strong> Word </strong></td>



			  <td width="10%" align="center"><strong> Repeats </strong></td>



			  <td width="10%" align="center"><strong> Density </strong></td>



			  <td width="22%" align="center" nowrap="nowrap"><strong> Word </strong></td>



			  <td width="10%" align="center"><strong> Repeats </strong></td>



			  <td width="10%" align="center"><strong> Density </strong></td>



			  </tr>



		  



			 <p><?php $varWrdptwo=CreateTableOfTwoWordsPhrases($contents,$txt_DontShowLessWords,$Opt_Density,2);



				  echo  $varWrdptwo;



			 ?>



			 </table>



     </td></tr>



	



    <tr>



	<td>



		<table width="95%"  border="0" align="center"cellpadding="3" cellspacing="1"   bordercolor="#666666">



			<tr > <td colspan="6"><span class="heading">Three Words Phrases </span></td>



			<tr bgcolor="#006699" class="whiteheading">



			  <td width="22%" align="center" nowrap="nowrap"><strong> Word </strong></td>



			  <td width="10%" align="center"><strong> Repeats </strong></td>



			  <td width="10%" align="center"><strong> Density </strong></td>



			  <td width="22%" align="center" nowrap="nowrap"><strong> Word </strong></td>



			  <td width="10%" align="center"><strong> Repeats </strong></td>



			  <td width="10%" align="center"><strong> Density </strong></td>



			  </tr>



				  



				 <p><?php $varWrdpthree=CreateTableOfTwoWordsPhrases($contents,$txt_DontShowLessWords,$Opt_Density,3);



					 echo $varWrdpthree;



				 ?>



		 </table>



	</td>



	</tr>



	



<tr><td>



	<table width="95%"  border="0" align="center"cellpadding="3" cellspacing="1"   bordercolor="#666666">



			<tr > <td colspan="6"><span class="heading">Four Words Phrases </span></td>



			<tr bgcolor="#006699" class="whiteheading">



			  <td width="22%" align="center" nowrap="nowrap"><strong> Word </strong></td>



			  <td width="10%" align="center"><strong> Repeats </strong></td>



			  <td width="10%" align="center"><strong> Density </strong></td>



			  <td width="22%" align="center" nowrap="nowrap"><strong> Word </strong></td>



			  <td width="10%" align="center"><strong> Repeats </strong></td>



			  <td width="10%" align="center"><strong> Density </strong></td>



			  </tr>



				  



				 <p><?php $varWrdpfour=CreateTableOfTwoWordsPhrases($contents,$txt_DontShowLessWords,$Opt_Density,4);



						echo $varWrdpfour;



				 ?>



			</table>



	</td>



	</tr>



	



</form>	 



	</table>



	



</td></tr>



     







</table>







<?php include("../bottom.php"); ?> 