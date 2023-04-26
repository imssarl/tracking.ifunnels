<?php



  session_start();



  require_once('functions/url_function.php');?>







<!--<link rel="stylesheet" type="text/css" href="menu/menu.css" /> -->



<?php include("header.php");?>



<?php //include("top.php");
	//echo  '<a class="general" href="../index.php">Home</a>';
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" style="padding-left:10px;"><a class="a1" href="../index.php">Home >></a> url analyzer </td>
  </tr>
  <tr>
    <td align="left" height="10"></td>
  </tr>
</table>

<link href="style.css" rel="stylesheet" type="text/css">

<link href="csshorizontalmenu.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="menu/menu.css" /> 

<script type="text/javascript" src="menu/chrome.js"></script>
<script type="text/javascript" src="/skin/_js/mootools.js"></script>
<script type="text/javascript" src="/skin/_js/mootools_more.js"></script>

<script type="text/javascript" language="javascript">

function check_url(){
	var url = document.form1.txt_Inputurl.value;
	if(!$chk(url))
	{
		 alert ("Please enter URL")
	     document.form1.txt_Inputurl.focus();
	     return false;
	}
	url=url.replace('http://','');
	url='http://'+url;
	document.form1.txt_Inputurl.value=url;
	
/*	 if(document.form1.txt_Inputurl.value!="")
          {
		  		string=document.form1.txt_Inputurl.value;
		  		 if(!(string.search(/[http:\/\/]www\.[a-zA-Z0-9\-]+\.[a-z\.]{2,5}[\/a-zA-Z0-9\.]+/) != -1))
				 	{
					   alert ("Please enter a valid URL like 'http://www.test.com' or 'http://www.test.net' ");
					   document.form1.txt_Inputurl.focus();
					   return false;
					}
				
           }
*/	
	}
	

</script>

<style type="text/css">



<!--



.style1 {color: #990000}



-->



</style>







<form name="form1" method="post" action="action.php" >



<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">



  



 <tr>



    <td width="26%" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">



      <tr>



        <td width="88%" align="right" valign="top" bgcolor="#F4F4F4"><table width="95%"  border="0" cellspacing="0" cellpadding="5">



          <!--<tr>



            <td align="left"><a href="index.php">Home </a><br>



              <a href="" onClick="javascript:history.back();">Back</a><br>



              <a href="">More Tools </a><br>



              <a href="readme.php">Help </a><br>



              <a href="#">Default settings </a><br>



              <a href="#">Simple Mode </a><br>



              <a href="#">Privacy Policy </a><br>



              <a href="#">Version History </a><br>



              <a href="#">Add this tool to your Site </a></td>



          </tr>-->



        <!--  <tr>



            <td height="1" align="left" class="grayline"> </td>



          </tr>-->



          <tr>



            <td align="left"> Items in grey fields <br>



do not affect keyword density calculation  </td>



          </tr>



          <tr>



            <td height="1" align="left" class="grayline"> </td>



          </tr>



          <tr>



            <td align="left"> <strong>Report options </strong> </td>



          </tr>



          <tr>



            <td height="1" align="left" class="grayline"> </td>



          </tr>



          <tr>



            <td align="left"> Do not show words less then <br>



              <input name="txt_DontShowLessWords" type="text" value="2" size="5">



              characters </td>



          </tr>



          <tr>



            <td height="1" align="left" class="grayline"> </td>



          </tr>



          <tr>



            <td align="left"> <input type="checkbox" name="chk_Title" value="on" checked>



              Show Title <br>



              <input type="checkbox" name="chk_Meta" value="on" checked>



              Show Meta tags <br>



              <input type="checkbox" name="chk_Headings" value="on" checked>



              Show Headings<br>



              <input type="checkbox" name="chk_Email" value="on" checked>



              Show Email <br>



              <input type="checkbox" name="chk_Alttags" value="on" checked>



              Show Alt tags <br>



              <input type="checkbox" name="chk_Linktext" value="on" checked>



              Show Linktext<br>              



              <input type="checkbox" name="chk_Boldtext" value="on" checked>



              Show Bold text <br>



              <input type="checkbox" name="chk_Italictext" value="on" checked>



              Show Italic text </td>



          </tr>



          <tr>



            <td height="1" align="left" class="grayline"> </td>



          </tr>



          <tr>



            <td align="left">&nbsp; </td>



          </tr>



          <tr>



            <td height="1" align="left" class="grayline"> </td>



          </tr>



          <tr>



            <td align="left"> Show all words <br>



OR Show keywords <br>



occuring at least 



<input name="txt_occuratleast" type="text" value="2" size="5">



times </td>



          </tr>



          <tr>



            <td align="left">&nbsp; </td>



          </tr>



          <tr>



            <td height="1" align="left" class="grayline"> </td>



          </tr>



          <tr>



            <td align="left"> Maximum keyword density <br>



              <input name="txt_Maxdensity" type="text" value="55.0" size="8">



              % </td>



          </tr>



          <tr>



            <td height="1" align="left" class="grayline"> </td>



          </tr>



          <tr>



            <td align="left">Minimum keyword density <br>



              <input name="txt_Mindensity" type="text" value="0" size="8">



              % </td>



          </tr>



          <tr>



            <td align="left">&nbsp;</td>



          </tr>



        </table></td>



        <td width="12%" height="100%" background="images/menubar.jpg">&nbsp;</td>



        </tr>



    </table></td>



    <td valign="top" class="heading">



      <table  style=" margin-top:15px; margin-bottom:15px;" width="98%"  border="0" cellpadding="5" cellspacing="0" class="border">



        <tr>



          <td width="3%">&nbsp;</td>



          <td width="97%">Enter URL</td>



        </tr>



        <tr>



          <td>&nbsp;</td>



          <td><input name="txt_Inputurl" id="txt_Inputurl" type="text" class="inputbox" value="http://" size="70"  maxlength="256">            </td>



        </tr>



        <tr>



          <td>&nbsp;</td>



          <td> Pay special attention to the following keyword <em>(optional) </em>: </td>



        </tr>



        <tr>



          <td>&nbsp;</td>



          <td><input name="txt_Specialkeyword" type="text" size="70" maxlength="256"></td>



        </tr>



        <tr>



          <td>&nbsp;</td>



          <td> User Agent </td>



        </tr>



        <tr>



          <td>&nbsp;</td>



          <td><input name="txt_Useragent" type="text" size="70" maxlength="256" value="Keyword Density Analyzer ( http://www.cmsnx.com)"></td>



        </tr>



        



        



        <tr>



          <td>&nbsp;</td>



          <td><input name="OptDensity" type="radio" value="DensityByUniqueWords" checked>



 Density = Occurence / Unique words   <br>



            <input name="OptDensity" type="radio" value="DensityByTotalWords"> 



 Density = Occurence / Total words<br>



            <br></td>



        </tr>



        



        <tr>



          <td>&nbsp;</td>



          <td>



            <div align="left">



              <input name="Submit" type="submit" class="button" value="Submit"  onclick="return check_url()">



            </div></td>



        </tr>



        



        <tr>



          <td>&nbsp;</td>



          <td><input type="checkbox" name="chk_stopWords" value="on" checked >



Check to Ignore StopWords from <a href="" title="<?php echo IgnoreWordsRead();?>">default</a> list <br>



<b> or</b> specify stopwords below:<br>



<textarea name="txtstopcustomwords" rows="6" cols="50"></textarea></td>



        </tr>



        <tr>



          <td>&nbsp;</td>



          <td><input type="checkbox" name="chk_poisonWords" value="on" checked >



Set custom Poison words (otherwise <a href="" title="<?php echo poisionWordsRead();?>">default</a> list is used) <br>



<textarea name="txtpoisoncustomwords" rows=6 cols=50></textarea></td>



        </tr>



        <tr>



          <td>&nbsp;</td>



          <td><input type="checkbox" name="chk_adultWords" value="on" checked >



            Set custom Adult words (otherwise <a href="" title="<?php echo AduldWordsRead();?>">default</a> list is used) <br>



            <textarea name="txtadultcustomwords" rows=6 cols=50></textarea></td>



        </tr>



        <tr>



          <td>&nbsp;</td>



          <td>&nbsp;</td>



        </tr>



      </table>



      </td>



  </tr>



  



  <tr>



    <td height="3" colspan="2" class="grayline"> </td>



  </tr>







</table>



</form>







<?php include("../bottom.php");?>