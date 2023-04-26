<?php
//PHP code will come here
session_start();
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/amarticle.class.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");

$article = new Article();
$database = new Database();
$pg = new PSF_Pagination();
$sc = new psf_Search();
$database->openDB();
$option=isset($_GET['process'])?$_GET['process']:'';

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

</head>

<script language="JavaScript">

function checkUncheckAll(theElement){
       var tForm = theElement.form, z = 0;
	   for(z=0;z<tForm.length;z++){
	   if(tForm[z].type == 'checkbox' && tForm[z].name != 'checkall'){
	   	tForm[z].checked = theElement.checked;	      
	   	//if(tForm[z].checked==true)alert(tForm[z].value);
		}
	   }
 }

function redirectme()
{
	var action;
	if(document.getElementById("optArt1").checked)action="?process=art";
	if(document.getElementById("optArt2").checked)action="?process=randart";
	if(document.getElementById("optArt3").checked)action="?process=artcat";
	if(document.getElementById("optArt4").checked)action="?process=kwdart";
	if(document.getElementById("optArt5").checked)action="?process=artsnip";
	//if(document.getElementById("optArt6").checked)action="?process=viewcode";
	window.location.href=action;
}

function savethecode()
{
	document.getElementById("myMsg").innerHTML="";
	document.getElementById("txtname").value="";
	document.getElementById("txtdescription").innerHTML="";
	if(document.getElementById("saveme").style.display=="none")
		document.getElementById("saveme").style.display="inline";
}

function getCode (xType) {
	
		// @@@@@@@@@@@ajax start here @@@@@@@@@@
			var xmlHttp;
			try
			  {
				  // Firefox, Opera 8.0+, Safari
				  xmlHttp=new XMLHttpRequest();
			  }
			catch (e)
			  {
				  // Internet Explorer
				  try
					{
						xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
					}
				  catch (e)
					{
					try
					  {
						  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
					  }
					catch (e)
					  {
					  	alert("Your browser does not support AJAX!");
					 	 return false;
					  }
					}
				  }

					var url = "getcode.php";
					var params;
					var anum=/(^\d+$)|(^\d+\.\d+$)/;
					switch(xType){
						case "artcat":
						if(document.getElementById("txtNum").value=='' || anum.test(document.getElementById("txtNum").value)==false)
						{
							alert("Enter a valid number");
							return false;
						}
						else{
						params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=artcat";
						}
						break;
						case "artsnip":
						if(document.getElementById("txtNum").value=='' || anum.test(document.getElementById("txtNum").value)==false)
						{
							alert("Enter a valid number");
							return false;
						}
						else{
						params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=artsnip";
						}
						break;
						case "art":
						var chkVal;
						chkVal=getValue();
						params="catid="+chkVal+"&process=art";
						break;
						case "randart":
						if(document.getElementById("txtNum").value=='' || anum.test(document.getElementById("txtNum").value)==false)
						{
							alert("Enter a valid number");
							return false;
						}
						else{
						params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=randart";
						}
						break;
						case "kwdart":
						if(document.getElementById("txtNum").value=='' || anum.test(document.getElementById("txtNum").value)==true)
						{
							alert("Enter a valid keyword");
							return false;
						}
						else{
						params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=kwdart";
						}
						break;
						case "save":
						if(document.getElementById("optArt1").checked)params="catid="+chkVal+"&process=save&disp=art";
						if(document.getElementById("optArt2").checked)params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=save&disp=randart";
						if(document.getElementById("optArt3").checked)params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=save&disp=artcat";
						if(document.getElementById("optArt4").checked)params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=save&disp=kwdart";
						if(document.getElementById("optArt5").checked)params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=save&disp=artsnip";
						
						params +="&name="+document.getElementById("txtname").value+"&descp="+document.getElementById("txtdescription").value;
						//params=+"&process=save&disp="+actionX+"&code="+document.getElementById("showcode").innerHTML;
						//alert(params);
						break;
					}
					//alert(params);
					xmlHttp.open("POST", url, true);
					
					//Send the proper header information along with the request
					xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8"); 
					//xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xmlHttp.setRequestHeader("Content-length", params.length);
					xmlHttp.setRequestHeader("Connection", "close");
					xmlHttp.send(params);
					xmlHttp.onreadystatechange = function() {//Call a function when the state changes.
						if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
							//alert(xmlHttp.responseText);
							//alert("dfs");
							if(xType!='save'){
								document.getElementById("showcode").value=xmlHttp.responseText;	
							}	
							else if(xType=='save'){
								document.getElementById("myMsg").innerHTML=xmlHttp.responseText;
								document.getElementById("saveme").style.display="none";
							}
							document.getElementById("xshow").style.display="inline";	
						
						}
					}
					


	}

function saveMe () {
	
		// @@@@@@@@@@@ajax start here @@@@@@@@@@
			var xmlHttp;
			try
			  {
				  // Firefox, Opera 8.0+, Safari
				  xmlHttp=new XMLHttpRequest();
			  }
			catch (e)
			  {
				  // Internet Explorer
				  try
					{
						xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
					}
				  catch (e)
					{
					try
					  {
						  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
					  }
					catch (e)
					  {
					  	alert("Your browser does not support AJAX!");
					 	 return false;
					  }
					}
				  }

					var url = "getcode.php";
					var params;
					
					switch(xType){
						case "artcat":
						params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=artcat";
						break;
						case "artsnip":
						params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=artsnip";
						break;
						case "art":
						var chkVal;
						chkVal=getValue();
						alert ("Hello+"+chkVal);
						return false;
						/*if(chkVal>0){
							params="catid="+chkVal+"&process=art";
						}
						else return false;	*/
						break;
						case "randart":
						params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=randart";
						break;
						case "kwdart":
						params="artNum="+document.getElementById("txtNum").value+"&catid="+document.getElementById("amcat").value+"&process=kwdart";
						break;
					}
					//alert(params);
					xmlHttp.open("POST", url, true);
					
					//Send the proper header information along with the request
					xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8"); 
					//xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					//xmlHttp.setRequestHeader("Content-length", params.length);
					//xmlHttp.setRequestHeader("Connection", "close");
					xmlHttp.send(params);
					xmlHttp.onreadystatechange = function() {//Call a function when the state changes.
						if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
							//alert(xmlHttp.responseText);
							//alert("dfs");
							document.getElementById("showcode").innerHTML=xmlHttp.responseText;
							document.getElementById("xshow").style.display="inline";
						}
					}
					


	}

function getValue()
{
	flags=false;
	var element;
	var numberOfControls = document.myform.length;
	var chkval;
	var xChk=0;
	for (Index = 0; Index < numberOfControls; Index++)
	{
		element = document.myform[Index];
		if (element.type == "radio")
		{
			if (element.checked == true)
			{
				flags=true;
				chkval=element.value;
			}
		}
	}
	if (flags==false)
	{ 
		alert("Please select one row.");
		return(0); 
	} 
	else
	{
		//confirm("Are you sure you want to delete Article");
		
			if (confirm("Are you sure you want to add this Article")==true)
			{
				return (chkval);
			}
			else
			{
				return (0);
			}
				
	}
}

function closeme()
{
	window.close();
}
</script>

<link href="stylesheets/style1.css" rel="stylesheet" type="text/css">

<body>

<br><br>

<form name="frmGetCode" action="" method="post">
<table width="95%" cellpadding="0" cellspacing="0" border="0" class="summary2" align="center">
<tr><TD align="center" class="heading" valign="top">Include Code</TD></tr>
<TR>
	<TD align="center">
		<table width="95%" align="center">
			<tr>
				<td align="left"><input type="radio"  value="art" <?php if($option=="art") echo "checked='checked'";?> checked="checked" id="optArt1" name="optArt"/></td>
				<td>Display single article</td>
			</tr>
			<tr>
				<td align="left"><input type="radio"   value="randart" id="optArt2" <?php if($option=="randart") echo "checked='checked'";?> name="optArt" /></td>
				<td>Display random articles from the category</td>
			</tr>
			<tr>
				<td align="left"><input type="radio"   value="artcat" id="optArt3" name="optArt" <?php if($option=="artcat") echo "checked='checked'";?> /></td>	
				<td>Display a number of articles from the category</td>
			</tr>
			<tr>
				<td align="left"><input type="radio"   value="kwdart" id="optArt4" name="optArt" <?php if($option=="kwdart") echo "checked='checked'";?> /></td>	
				<td>Display keyword relevant article</td>
			</tr>
			<tr>
				<td align="left"><input type="radio"  value="artsnip" id="optArt5" name="optArt" <?php if($option=="artsnip") echo "checked='checked'";?> /></td>	
				<td>Display article snippets</td>
			</tr>
			<!--tr>
				<td><input type="radio"  value="viewcode" id="optArt6" name="optArt" <?php //if($option=="viewcode") echo "checked='checked'";?> /></td>	
				<td>View Saved Codes</td>
			</tr-->
			<tr>
				<td  align="left"><input type="button" style="cursor:pointer" name="cmdGo" value="Submit" onClick="redirectme();"></td>
				<td></td>
			</tr>
		</table>
	</TD>
</TR>
</table>
</form>
<?php
	if($option=='art')
	{
		$process='manage';
		$sql = "select count(*) from `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b where a.id=b.category_id and a.status='Active'   and b.user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];
		$totalrecords = $database->getDataSingleRecord($sql);
		if ($totalrecords>0){
		$pg->setPagination($totalrecords);
	    $order_sql = $sc->getOrderSql(array("id","category","title","summary","source","status"),"id");
		//$pg->showPagination1();
		echo $article->manageArticleGCPaging();
?>
<table width="1000px"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
<tr>
<th><a title = "Sort" class = "menu" href="?sort=id">ID</a></th>
<th>
<form action="amarticleshowcode.php" method="get" name="cat">
<select name="amcat" id="amcat" style="background-color:#A2A2A2" onchange="selcat();" ><?php $article -> SelectBox(); ?></select>
</form>
</th>
<form name="myform" action="" method="post" onsubmit="return chk(this)">
<input type="hidden" name="btndelete" value="" />
<th><a title = "Sort" class = "menu" href="?sort=title">Title</a></th>
<th><a title = "Sort" class = "menu" href="?sort=summary">Summary</a></th>
<th><a title = "Sort" class = "menu" href="?sort=source">Source</a></th>
<!--th><a title = "Sort" class = "menu" href="?sort=status">Status</a></th>
<th></th>
<th></th>
<th></th>
<th></th-->
<th><!--input name='chkall' type='checkbox' value='chkall' id="chkall" onClick='checkUncheckAll(this)'--></th>
</tr>
<?php if($process=="manage")
		{
			echo $article->manageArticleGC();
		}
		else{
			echo $article->selectCategory();
			}
	}else{$totalrecords = 0;$man_rs = false;}?>
	<tr>
			<td colspan="6">Select an article above : <input type="button" onClick="getCode('art');" name="cmdgetcode" value="Get Code" /></td>
	</tr>
</table>
</form>	
<?php 
	}
	elseif($option=='randart')
	{
?>
	<form action="amarticleshowcode.php" method="get" name="cat">
	<table width="95%" cellpadding="0" cellspacing="0" border="0" class="summary2" align="center">
		<tr>
			<td>Enter the number of random articles</td>
			<td><input type="text" name="txtNum" id="txtNum" ></td>
		</tr>
		<tr>
			<td>Select Category</td>
			<td>
				
					<select name="amcat" id="amcat" style="background-color:#A2A2A2">
						<?php $article -> SelectBox(); ?>
					</select>

			</td>
		</tr>
		<tr>
			<td><input type="button" onClick="getCode('randart');" name="cmdgetcode" value="Get Code" /></td>
		</tr>	
	</table>
		</form>
<?php		
	}
	elseif($option=='artcat')
	{
?>
	<form action="amarticleshowcode.php" method="get" name="cat">
	<table width="95%" cellpadding="0" cellspacing="0" border="0" class="summary2" align="center">
		<tr>
			<td>Enter the number of articles</td>
			<td><input type="text" name="txtNum" id="txtNum" ></td>
		</tr>
		<tr>
			<td>Select Category</td>
			<td>
				
					<select name="amcat" id="amcat" style="background-color:#A2A2A2">
						<?php $article -> SelectBox(); ?>
					</select>

			</td>
		</tr>
		<tr>
			<td><input type="button" onClick="getCode('artcat');" name="cmdgetcode" value="Get Code" /></td>
		</tr>	
	</table>
		</form>
<?php		
	}
	elseif($option=='artsnip')
	{
?>
	<form action="amarticleshowcode.php" method="get" name="cat">
	<table width="95%" cellpadding="0" cellspacing="0" border="0" class="summary2" align="center">
		<tr>
			<td>Enter the number of article snippets</td>
			<td><input type="text" name="txtNum" id="txtNum" ></td>
		</tr>
		<tr>
			<td>Select Category</td>
			<td>
				
					<select name="amcat" id="amcat" style="background-color:#A2A2A2">
						<?php $article -> SelectBox(); ?>
					</select>

			</td>
		</tr>
		<tr>
			<td><input type="button" onClick="getCode('artsnip');" name="cmdgetcode" value="Get Code" /></td>
		</tr>	
	</table>
		</form>
<?php 
	}
elseif($option=='kwdart')
	{
?>
	<form action="amarticleshowcode.php" method="get" name="cat">
	<table width="95%" cellpadding="0" cellspacing="0" border="0" class="summary2" align="center">
		<tr>
			<td>Enter a keyword</td>
			<td><input type="text" name="txtNum" id="txtNum" ></td>
		</tr>
		<tr>
			<td>Select Category</td>
			<td>
				
					<select name="amcat" id="amcat" style="background-color:#A2A2A2">
						<?php $article -> SelectBox(); ?>
					</select>

			</td>
		</tr>
		<tr>
			<td><input type="button" onClick="getCode('kwdart');" name="cmdgetcode" value="Get Code" /></td>
		</tr>	
	</table>
		</form>
<?php		
	}
	
?>	
<div id="xshow" style="display:none" >
<table width="95%" cellpadding="0" cellspacing="0" border="0" class="summary2" align="center">
		<tr><TD align="center" class="heading" valign="top">Generated Code</TD></tr>
		<tr>
			<td><textarea id="showcode"readonly="readonly" cols="100" rows="30"></textarea></td>
		</tr>
		<tr>
			<td><input type="button" style="cursor:pointer" value="Save Generated Code" onClick="savethecode();"></td>
		</tr>
</table>
</div>
<div id="saveme" style="display:none">
<table width="95%" cellpadding="0" cellspacing="0" border="0" class="summary2" align="center">
		<tr><TD align="center" class="heading" colspan="2" valign="top">Save Selected Code</TD></tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td>Add Code Title</td>
			<td><input type="text" id="txtname" name="txtname" /></td>
		</tr>
		<tr>
			<td>Add Code Description</td>
			<td><textarea id="txtdescription" cols="50" rows="10"></textarea></td>
		</tr>
		<tr>
			<td><input type="button" style="cursor:pointer" value="Save" onClick="getCode('save');"></td>
		</tr>
</table>
</div>
<div id="myMsg"></div>
<?php 
/*

elseif($option=='viewcode')
	{
		$sql = "select count(*) from `".TABLE_PREFIX."am_savedcode` where user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid'];
		$totalrecords = $database->getDataSingleRecord($sql);
		if ($totalrecords>0){
		$pg->setPagination($totalrecords);
	    $order_sql = $sc->getOrderSql(array("id","dispoption"),"id");
		$pg->showPagination1();
?>
<table width="1000px"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
<tr>
<th><a title = "Sort" class = "menu" href="?sort=id">ID</a></th>
<form name="myform" action="" method="post" onsubmit="return chk(this)">
<input type="hidden" name="btndelete" value="" />
<th><a title = "Sort" class = "menu" href="?sort=title">Display Option</a></th>
<th><a title = "Sort" class = "menu" href="?sort=summary">Code</a></th>
<!--th><a title = "Sort" class = "menu" href="?sort=status">Status</a></th-->
<th></th>
<th></th>
<!--th></th>
<th></th>
<th><input name='chkall' type='checkbox' value='chkall' id="chkall" onClick='checkUncheckAll(this)'></th-->
</tr>
<?php 
echo $article->manageCode();
}else{$totalrecords = 0;$man_rs = false;}?>

</table>
</form>	
<?php 
	}
<table width="95%" cellpadding="0" cellspacing="0" border="0" class="summary2" align="center">

<tr><TD align="center" class="heading" valign="top">Include Code</TD></tr>



<TR><TD align="center">

<?php

$code = '<?php

   	if(function_exists("curl_init"))

               {

                               $ch = @curl_init();

                               curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticles.php?id='.xxxx.'");

                               curl_setopt($ch, CURLOPT_HEADER, 0);

                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                               $resp = @curl_exec($ch);

                               $err = curl_errno($ch);



                       if($err === false || $resp == "")

                       {

                               $newsstr = "";

                       } else

                       {

                               if (function_exists("curl_getinfo"))

                               {

                                   $info = curl_getinfo($ch);

                                       if ($info["http_code"]!=200)

                                               $resp="";

                               }

                               $newsstr = $resp;

                       }

                       @curl_close ($ch);

                       echo $newsstr;

               }

               else

               {

                        @include("'.SERVER_PATH.'showarticles.php?id='.xxxx.'");

               }



?>';

?>

<TEXTAREA  rows="5" cols="80"><?php echo ($code); ?></TEXTAREA>

<div class="message">

The code has to be copied and then paste into the page where you wants it to appear. Page needs to have a php extension.

You can choose to display a single pre-chosen article :

showarticles.php?id=xxxx

where xxxx is the Id of the article

</div>

</TD>

</TR>



<TR><TD align="center">

<?php

$code = '<?php

   		$category_id = "put category ID here";

   		if(function_exists("curl_init"))

        {

		   $ch = @curl_init();

		   curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticles.php?category_id=$category_id");

		   curl_setopt($ch, CURLOPT_HEADER, 0);

		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		   $resp = @curl_exec($ch);

		   $err = curl_errno($ch);



		   if($err === false || $resp == "")

		   {

				   $newsstr = "";

		   }

		   else

		   {

			   if (function_exists("curl_getinfo"))

				   {

					   $info = curl_getinfo($ch);

						   if ($info["http_code"]!=200)

								   $resp="";

				   }

				   $newsstr = $resp;

		   }

		   @curl_close ($ch);

		   echo $newsstr;

	  }

	   else

	   {

				@include("'.SERVER_PATH.'showarticles.php?category_id=$category_id");

	   }



?>';

?>

<TEXTAREA  rows="5" cols="80"><?php echo ($code); ?></TEXTAREA>

<div class="message">

You can also choose to display a random article within a given category :<br>

Please replace followings <br>

$category_id = "put category ID here";<br>

</div>

</TD>

</TR>



<TR><TD align="center">

<?php

$code = '<?php

		$keyword = "put keyword here";

		$category_id = "put category ID here";

		$keyword = str_replace(" ","%20",$keyword);

   		

   		if(function_exists("curl_init"))

               {

               

		       $ch = @curl_init();

		       curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticles.php?keyword=$keyword&defcategory=$category_id");

		       curl_setopt($ch, CURLOPT_HEADER, 0);

		       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		       $resp = @curl_exec($ch);

		       $err = curl_errno($ch);



                       if($err === false || $resp == "")

                       {

                               $newsstr = "";

                       } else

                       {

                               if (function_exists("curl_getinfo"))

                               {

                                   $info = curl_getinfo($ch);

                                       if ($info["http_code"]!=200)

                                               $resp="";

                               }

                               $newsstr = $resp;

                       }

                       @curl_close ($ch);

                       echo $newsstr;

               }

               else

               {

                        @include("'.SERVER_PATH.'showarticles.php?keyword=$keyword&defcategory=$category_id");

               }



?>';

?>

<TEXTAREA  rows="5" cols="80"><?php echo ($code); ?></TEXTAREA>

<div class="message">

You can display a keyword relevant article that could be in the database :<br>

Please replace followings <br>

$keyword = "put keyword here";<br>

$category_id = "put category ID here";<br>

 (if none, then a random selection among the default category)

</div>

</TD>

</TR>



<TR><TD align="center">

<?php

$code = '<?php

   		

   		$category_id = "Put category ID here";

   		$no_of_snippets = "Put No. of snippets to display";

   		$no_of_snippets = str_replace(" ","%20",$no_of_snippets);



   		if(function_exists("curl_init"))

 	        {

 	        

		       $ch = @curl_init();

		       curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticlesnippets.php?category_id=$category_id&nb=$no_of_snippets");

		       curl_setopt($ch, CURLOPT_HEADER, 0);

		       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		       $resp = @curl_exec($ch);

		       $err = curl_errno($ch);



                       if($err === false || $resp == "")

                       {

                               $newsstr = "";

                       }

                       else

                       {

                               if (function_exists("curl_getinfo"))

                               {

                                   $info = curl_getinfo($ch);

                                       if ($info["http_code"]!=200)

                                               $resp="";

                               }

                               $newsstr = $resp;

                       }

                       @curl_close ($ch);

                       echo $newsstr;

               }

               else

               {

                        @include("'.SERVER_PATH.'showarticlesnippets.php?category_id=$category_id&nb=$no_of_snippets");

               }



?>';

?>



<TEXTAREA  rows="5" cols="80"><?php echo ($code); ?></TEXTAREA>

<div class="message">

You can display a list of article snippets of a given category: Title + summary<br>

Please replace followings <br>

$category_id = "put category Id here";<br>

$no_of_snippets = "Put No. of snippets to display";

</div>

</TD>

</TR>

<tr><TD align="center"><br><br>





<TR><TD align="center">

<?php
/*
$code = SERVER_PATH.'showarticlencsb.php?category_id={Put category Id}&nb={No. of snippets to display}';

?>

<TEXTAREA  rows="5" cols="80"><?php echo ($code); ?></TEXTAREA>

<div class="message">

Put this code into Niche Content Site Builder Section.

</div>

</TD>

</TR>



<TR><TD align="center">

<?php

$code = SERVER_PATH.'showarticle_random.php?category_id={Put category Id}&nb={No. of snippets to display}&rand=Y';

?>

<TEXTAREA  rows="5" cols="80"><?php echo ($code); ?></TEXTAREA>

<div class="message">You can select Random Articles.Please replace followings <br> Category_Id="Put Category Id here"<br> nb="Put No. of snippets to display";<br>Please do not change in rand

<br>

Put this code into Niche Content Site Builder Section.

</div>

</TD>

</TR>

<TR><TD align="center">

<?php





$code = SERVER_PATH.'showarticle_ncsb.php?category_id={Put category Id}&nb={No. of snippets to display}&status=Y';

?>

<TEXTAREA  rows="5" cols="80"><?php echo ($code); ?></TEXTAREA>

<div class="message">

You can display a list of article snippets of a given category: Title + summary<br>

Put this code into Niche Content Site Builder Section.<br>

Please replace followings <br> Category_Id="Put Category Id here"<br> nb="Put No. of snippets to display";<br>

Please do not change in status

</div>

</TD>

</TR>





<tr><TD align="center"><br><br>

<div class="message">Note: For each case of the above calls (Except when we call a given article

id), user can append &source=1, 2, 3 or 4 depending on

1 - PLR

2- Free reprint rights

3- Own

4- Partners



so that user can further select which source of article he wants to be

displayed.

If &source is omited then this field is not used for filtering </div>

</TD></tr>

<tr><TD  class="heading"><input type="button" value="Close" onclick="closeme()" ></TD></tr>

</table>
*/?>