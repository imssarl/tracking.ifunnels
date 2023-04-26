<?php require_once('functions/article_function.php');
      set_time_limit(220);
   if((isset($_POST['abcd']) && ($_POST['abcd']!="")))
   {
     header("Content-type: application/txt");
     header("Content-Disposition: attachment; filename=keyword.txt");
     echo $_POST['abcd'];
	 exit();
   }
   $txt_Title=$_POST['txt_Title'];
  //no need.... $txt_Summary=$_POST['txt_Summary']; 
   $txt_Description=$_POST['txt_Description'];
   $txt_DontShowLessWords=intval($_POST['txt_DontShowLessWords']);
   $chk_Title=$_POST['chk_Title'];
  
   $txt_occuratleast=$_POST['txt_occuratleast'];
 
   $txt_Closeiswithin=$_POST['txt_Closeiswithin'];
   $txt_LeastWords=$_POST['txt_LeastWords'];
  
   $txt_charactercount=$_POST['txt_charactercount'];
  
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
$contents=strip_tags($txt_Description); 

//===this fuction is for single pharse data==
function Wrdp($para,$txt_DontShowLessWords,$Opt_Density)
{   
    global $varTotalCount,$GlobalUniqueCount;
	global $chk_Alttags,$chk_Linktext,$chk_Headings,$chk_Boldtext,$chk_Italictext,$txt_occuratleast;
	global $chk_stopWords,$txt_Inputurl,$txtstopcustomwords,$txtadultcustomwords,$chk_adultWords,$txtpoisoncustomwords,$chk_poisonWords;
	global $txt_Mindensity,$txt_Maxdensity;
	global $varBoldText,$varHeaderText,$varItalicText,$varAnchorText,$varAlternateText;

	$varTotalCount=Total_WordCount($para,$txt_DontShowLessWords);

	$GlobalUniqueCount=UniqueWordCount($para,$txt_DontShowLessWords,$txt_occuratleast);

	$varCreateTable="";
	$varTotal=0;
	$varString=$para; 
	//$varString = strip_tags($varString);
	$varString=RemoveSpecialChar($varString);
	$varString=strtolower($varString);
	////////////////////////////////////////////////
	// Remove Ignore Words from the Content // 
	//////////////////////////////////////////
	if($chk_stopWords=="on")
		{
			$txtIgnoreWords=IgnoreWordsRead();
		//	echo "<li><li>Origional Stop words list".$txtIgnoreWords;
			$arrIgnoreWords=explode(",",$txtIgnoreWords);
			if(count($arrIgnoreWords)>0)
			{
			 echo " <b> Ignore Words :</b>";
				for($i=0;$i<count($arrIgnoreWords);$i++)
				{  
				 echo " ".$arrIgnoreWords[$i];
				 $varString = str_replace(" ".$arrIgnoreWords[$i]." "," ",$varString);
				}
		   }
		   
		} 
		else 
		{
			$arrIgnoreWords=explode(",",$txtstopcustomwords);
			if (strlen($txtstopcustomwords)>0){
				echo " <b> Ignore Words :</b>";
				for($i=0;$i<count($arrIgnoreWords);$i++)
				{	
				   echo " ".$arrIgnoreWords[$i];
				   $varString = str_replace(" ".$arrIgnoreWords[$i]." "," ",$varString);
			    }
		   }
	    }
	//////////////////////////////////////////
	// Remove Adult Words from the Content //
	//////////////////////////////////////////
	if($chk_adultWords=="on")
	{
		$txtadultcustomwords=AduldWordsRead();
		$arrIgnoreWords=explode(",",$txtadultcustomwords);
		
		if(count($arrIgnoreWords)>0){
		echo " <b> Adult Words :</b>";
		for($i=0;$i<count($arrIgnoreWords);$i++)
		{   echo " ".$arrIgnoreWords[$i];
			$varString = str_replace(" ".$arrIgnoreWords[$i]." "," ",$varString);
		}
		}
	} else {
		$arrIgnoreWords=explode(",",$txtadultcustomwords);
		 
		if (strlen($txtadultcustomwords)>0){
			echo " <b> Adult Words :</b>";
			for($i=0;$i<count($arrIgnoreWords);$i++)
			{	
				echo " ".$arrIgnoreWords[$i];
				$varString = str_replace(" ".$arrIgnoreWords[$i]." "," ", $varString);
			}
	   }
	}

	//////////////////////////////////////////
	// Remove Poision Words from the Content // 
	//////////////////////////////////////////
	if($chk_poisonWords=="on")
	{
		$txtpoisoncustomwords=poisionWordsRead();
		$arrIgnoreWords=explode(",",$txtpoisoncustomwords);
		if(count($arrIgnoreWords)>0){
		echo " <b> Poision Words :</b>";
		for($i=0;$i<count($arrIgnoreWords);$i++)
		{   echo " ".$arrIgnoreWords[$i];
			$varString = str_replace(" ".$arrIgnoreWords[$i]." "," ",$varString);
		}
		}
	} else {
		$arrIgnoreWords=explode(",",$txtpoisoncustomwords);
		if (strlen($txtpoisoncustomwords)>0){
			echo " <b> Poision Words :</b>";
			for($i=0;$i<count($arrIgnoreWords);$i++)
			{	
				echo " ".$arrIgnoreWords[$i];
				$varString = str_replace(" ".$arrIgnoreWords[$i]." "," ",$varString);
			}
	   }
	}
///////////////////////////////////////////
	$varRikin=split(" ",$varString);
	foreach($varRikin as $varRikin1)
	{
		if((trim($varRikin1)!="") && (trim($varRikin1)!=" ") && (trim(strlen($varRikin1))>=$txt_DontShowLessWords) && (CountNoofOccurance($varRikin1,$varString,$txt_DontShowLessWords)>=$txt_occuratleast))
		{   
		    if(!(is_numeric($varRikin1)))
		    {
			$varMid[]=$varRikin1;
			}
		}
		
		
	}
//actual code of the single keyword start from here	
	$varTemp=array_unique($varMid);
 	$i=0;
	foreach ($varTemp as $v1)
	{   
	  if(($Opt_Density=="DensityByUniqueWords") && (strlen($v1)>$txt_DontShowLessWords)) 
		{
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
	$counter=0;
	$varHeaderCount=0;
	$varBoldCount=0;
	$varItalicCount=0;
	$varAnchorCount=0;
	$varAlternateCount=0;
	$varAnchor="";
	for($x=0;$x<count($var_new);$x++)
    {   
		//........................
		if($x%2){$bg="#efefef";}else{$bg="#F4FDFF";}
		$varCreateTable.="<tr bgcolor='".$bg."'>";
		$vk=0;
		do{
		//..........................
		$val=$var_new[$x][0];

		if(trim($val)!= "")		
		{
		 /* Print Fianally all Details with Total No Count*/
		$varTotalOccurance_Single_Word=CountNoofOccurance($val,$varString,$txt_DontShowLessWords);
        $varAnchor="";
			if ($varTotalOccurance_Single_Word>0)
			{
				$varCreateTable=$varCreateTable." <td nowrap='nowrap'><input type='checkbox' name='q' id='q' value=".$val.">  ". $val ." </td> <td align='center'>".$varTotalOccurance_Single_Word." ". $varAnchor ." </td><td align='center'>".$var_new[$x][1]."% </td>";
			} 
			else
			{
				$varCreateTable=$varCreateTable." <td nowrap='nowrap'> <input type='checkbox' name='q' id='q' value=".$val."> ". $val ." </td> <td align='center'> ". $varAnchor ." </td> <td align='center'>".$var_new[$x][1]."% </td>";
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
	
	$varString=$para;
	$varString=RemoveSpecialChar($varString);
	$varString=strtolower($varString);
    //important function
	$Twowordphrases=Twowordphrases($varString,$txt_DontShowLessWords,$NoOfwords);
  
    usort($Twowordphrases,compare);  // sort by no or times occurancy
    $Twowordphrases=remove_dups($Twowordphrases, 0); // Remove Duplicate Words
	$i=0;
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
		if(trim($val)!= "")		
		{
		// $var_new[] = $val;
        /* Finding Text Occurance In Bold Text	*/
		 $varTotalOccurance=$Twowordphrases[$x][1];  //-------->CountNoofOccurance($val,$varString,$txt_DontShowLessWords);
        /* Print Fianally all Details with Total No Count*/	
	    if (($varTotalOccurance>0)&&($TwowordphrasesWithDensity[$x][1]!=""))
          {
		   // $val=strip_tags($val);
   	    	$varCreateTable=$varCreateTable." <td nowrap='nowrap'><input type='checkbox' name='q' id='q' value=".$value=str_replace(" ","_",$val)."> ".$val." </td><td align='center'>".$varTotalOccurance." ". $varAnchor ." </td><td align='center'>".$TwowordphrasesWithDensity[$x][1]."% </td>";
		  }  
	//	else
	  //    {
	    //  $varCreateTable= $varCreateTable." <td nowrap='nowrap'><input type='checkbox' name='q' id='q' value=".$val."> ".$val." </td> <td align='center'>". $varAnchor ." </td> <td align='center'>".$TwowordphrasesWithDensity[$x][1]."% </td>";
		//  }
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


function Submitlist()
{
var c_value = "";
var final_content="";
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
document.form1.abcd.value=c_value;
document.form1.submit(); 
}

/*function Submitlist()
{
var c_value = "";
var final_content="";
for (var i=0; i<document.form1.q.length; i++)
  {
       if (document.form1.q[i].checked)
	   {
		if (c_value =="")
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
document.form1.abcd.value=c_value;
document.form1.submit(); 
}
*/

</script>
</head>
<body>
<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	<tr>
		<td><?php include("header.php"); ?></td>
	</tr>
	
	<tr><td>&nbsp;</td>
	</tr>
	<tr>
		<td><form name="form1" method="post" action="">
       <input type="hidden" name="abcd" value="">
  <table width="95%"  border="0" align="center" cellpadding="3" cellspacing="0" class="border">
 
    <tr>
      <td colspan="8" bgcolor="#006699" class="whiteheading">Welcome to <?php echo $txt_Title ?></td>
    </tr>
  
    <tr>
      <td colspan="8" class="heading">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="8" class="heading"> Article Content </td>
    </tr>
    <tr>
      <td colspan="8"><textarea name="textarea" cols=90 rows=12 maxsize=24549><?php echo $contents; ?></textarea></td>
    </tr>
	 <tr>
    <td colspan="8" class="heading">Totals, Counts, Special words    </tr>
	 <tr>
    <td colspan="8"><?php $tempContents=$contents; 
	  
      $varTotalCount=Total_WordCount($tempContents,$txt_DontShowLessWords);
      echo "<b>Total Word Count in the File::</b>" .$varTotalCount;
	  ?> </tr>
	  <tr>
      <td colspan="8"><?php  // to revise
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
<?php 
	$title=$txt_Title; //No need due to Article AnalylizeTitle_Tag_Content($contents);
	echo "<B> Title Tag ::</B>".$title;
?> </td>
    </tr>
	<?php }?>
    <tr>
      <td colspan="8"><input name="select_keyword" type="button" class="button" onClick="javascript:Submitlist();" value="Export Keywords"> 
      <input name="select_keyword" type="button" class="button" onClick="window.open('view_keyword.php', 'newwindow', config='height=400, width=500, toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, directories=no, status=no,checkbox=yes')" value="View Keywords"> 
      <label for="checkbox2"> <input type="checkbox" id="checkbox2" name="checkbox2" value="checkbox" onClick="javascript:checkall();"><a href="#"> Select All </a></label>      </td>
    </tr>	
	
	<tr>
		<td>
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
		  
		 <p><?php  $varWrdp=Wrdp($contents,$txt_DontShowLessWords,$Opt_Density);
			 echo "".$varWrdp;
		 ?></p>
		 </table>
		</td>
	</tr>
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
		  
			 <p><?php  $varWrdptwo=CreateTableOfTwoWordsPhrases($contents,$txt_DontShowLessWords,$Opt_Density,2);
				  echo  $varWrdptwo;
			 ?>
			 </table>
		 </td>
	</tr>
	
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
	
	<tr>
		<td>
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
	
  </table>
</form></td>
	</tr>
	
	<tr>
	<td > <div align="center">&copy;Kalptaru Infotech Ltd.</div></td>
	</tr>

</table>


 
 
  

</body>
</html>
