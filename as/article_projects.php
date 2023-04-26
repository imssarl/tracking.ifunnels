<?php
session_start();
set_time_limit(0);

require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/settings.class.php");
require_once("classes/sites.class.php");
require_once("classes/common.class.php");
require_once("classes/sites.steps.class.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");
require_once("classes/articles.class.php");
require_once("classes/projects.class.php");
require_once("classes/pclzip.lib.php");
require_once("classes/feed.class.php");
require_once("classes/pinger.php");
require_once("classes/phpmailer.class.php");

// extra fuctions for wizrad

//require_once("classes/amarticle.class.php");

$database = new Database();

$database->openDB();

$pg = new PSF_Pagination();

$sc = new psf_Search();







// end of extra function 



$article = new Article();



$mail = new PHPMailer();



$pf = new PingFeed();



$feed = new xmlParser();



$steps = new Steps();



$prj = new Projects();



$art =  new Article();



$archive = new PclZip($_FILES['importtextzip']['tmp_name'][$no]);



$settings = new Settings();



$common = new Common();



$settings->checkSession();



$ms_db = new Database();



$ms_db->openDB();



$user_data = $settings->getSettings();



$msg = "";



if (isset($_POST['process']))



{



	$process = $_POST['process'];



}



else if (isset($_GET['process']))



{



	$process = $_GET['process'];



}

/*else if($_REQUEST['amcat']>0)

	$process='advsearch'; 

	else 

	$process='new';*/

	

if (isset($_GET["page"]))



{



	$page = $_GET["page"];



}



else if (isset($_POST["page"]))



{



	$page = $_POST["page"];



}



else



{



	$page = 1;



}



/*if($_REQUEST['submit']!="")

{

	print_r($_REQUEST['chk']);

	echo $_REQUEST['process'].$_POST['mode'].$_POST['gen'];

	die;

}*/



if (isset($_POST['articleform']) && $_POST['articleform'] == "yes")



{





$prj->showTopOfPage();



$uploaddata = true;

	//echo $_REQUEST['source_type'].'??';die;

$uploaddata = $art->checkUploadedFile();

//print_r($uploaddata);die;

if(isset($_REQUEST['source_type']) && $_REQUEST['source_type']!="")

{

	



}

if ($uploaddata != false)

{

	if($process=="new")

	{

	$projectid = $prj->insertProject();

		if ($_POST['mode'] == "O")

		{

			if ($_POST['gen'] == "A")

			{

			$genarticles = count($uploaddata);

			$cond = 1;

			$period = 0;

			}

		}

		else if ($_POST['mode'] == "R")

		{

			$period = $_POST['period'];

			$genarticles = $_POST['genarticlesp'];	

			$cond = 4;	

		}

		$artprojectid = $art->insertArticleProject($projectid, $genarticles, $period);

		$source = $art->insertArticleSource($artprojectid,$uploaddata);

		$art->uploadArticles($projectid);

		if ($_POST['mode'] == "R")

		{

			$addindays = floor($source/$genarticles)*$period;
			if($addindays==0)$addindays=$period;
			echo '

			<script language="javascript">

			alert("In '.$addindays.' days, you will need to add new articles.\nAn email will be sent to inform you");

			</script>';

		}

?>



<script language="javascript">



document.writeln('<center>');



document.writeln('<input type="button" name="proc" value="Continue" onclick="javascript: location = \'projects.php?msg=New article project has been added&which_project=allart\'" />');



document.writeln('</center>');



</script>



<?php



		$prj->showBottomOfPage();



		exit;



/*		echo '



		<script language="javascript">



		document.location = "projects.php?msg=New article project has been added";



		</script>';*/







//		header("location: projects.php?msg=New keywords project has been added");		



	}



	else if ($process == "addnewarticle")



	{







		$cond = 4;	// new keywords can be added only in recurring projects. hence cond is set to 4;



		$status = 'I';



		$why = 'Running';



		$artprojectid = $art->getArtProjectIdFromProjectId($_POST["addnewartprojectid"]);



		$sourceid = $art->insertArticleSource($artprojectid,$uploaddata);







					$prj->setProjectStatus($_POST["addnewartprojectid"],$status,$why);







		echo '



		<script language="javascript">



		document.location = "projects.php?page='.$page.'&msg=New article(s) added";



		</script>';



		$prj->showBottomOfPage();



		exit;







//		header("location: projects.php?page=".$page."&msg=New article added");



	}



	



}	



else



{



	$kd = $common->getPostData();



	?>



	<br>



	<script language="javascript">



	document.writeln('<center>');



	document.writeln('<input type="button" name="proc" value="Continue" onclick="javascript: location = \'article_projects.php?process=repeatentry\'" />');



	document.writeln('</center>');



	</script>



	<?php



	$prj->showBottomOfPage();



	exit;



}



}



else if ($process == "repeatentry")



{



	$kd = $common->getLastPostData();



	$process = $kd["process"];



}















?>







<?php require_once("header.php"); ?>







<title>



<?php echo SITE_TITLE; ?>



</title>







<script language="javascript">



	var ch_art=new Array();



		<?php



			$count=0;



			global $ms_db;



			$sql = "Select Distinct portal_id,id,url from ".TABLE_PREFIX."portals_sites_tb  where   is_under_portal = 'Y' and type = 'S' and user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."' order by url";



			$catlist = $ms_db->getRS($sql);



			if ($catlist!=false)



			{



			$totalrows = mysql_num_rows($catlist);



			}



			else



			{



			$totalrows = 0;



			}



			?>







			var stateLen=<?php echo ($totalrows=="" || !$totalrows) ? "0" : $totalrows; ?>;



			



			var i;



			var State = new Array(stateLen);



			for(i=0;i<stateLen;i++)



			{



				State[i] = new Array(2);



			}







			<?php



				$i  = 0;



				For ($i = 0;$i<$totalrows;$i++)



				{



				$sDatasetRows = mysql_fetch_assoc($catlist);



				



			?>



		State[<?php  echo $count?>][0] = "<?php echo $sDatasetRows["portal_id"]?>";



		State[<?php  echo $count?>][1] = "<?php echo $sDatasetRows["url"]?>";



		State[<?php  echo $count?>][2] = "<?php echo $sDatasetRows["id"]?>";



		



			<?php



				$count=$count+1;



				}



				$count=0;



			?>



	function PopulateCategory(ParentList,ChildList,Default)



	{



		



		



	



		var i;



	//	alert (ParentList.value);



		ClearOptions(ChildList);



		AddToOptionList(ChildList,"0","<--Select Site-->");



			//AddToOptionList(ChildList,"All","All");



			



		for(i=0;i<stateLen;i++)



		{



			if((State[i][0]==(ParentList.value)))



			{



				AddToOptionList(ChildList,State[i][2],State[i][1],Default);



			}



		}



	}	



	



	function ClearOptions(OptionList)



		{ 



			for(x=eval(OptionList.length); x>=0; x--)



			{



				//alert(OptionList[x]);



				OptionList[x] = null;



			}



		}



		



		function AddToOptionList(OptionList, OptionValue, OptionText,Default)



		{ 



			if (OptionValue==Default)



			OptionList[eval(OptionList.length)] = new Option(OptionText, OptionValue, true);



			else



			OptionList[eval(OptionList.length)] = new Option(OptionText, OptionValue, false);			



		}



function addField_2( area,descriptionName,descriptionID,fileName,fileID,limit ) {







        if( !document.getElementById ) return ; // Only DOM browsers







        var field_area = document.getElementById( area ) ;



        var lastRow = field_area.rows.length ;



        var iteration = lastRow ;



        var row = field_area.insertRow( lastRow ) ;







        // Cells



        var cellLeft = row.insertCell(0) ;







        var count = lastRow - 1 ;







        if( count > limit && limit > 0 ) return ;



       



        if( document.createElement ) { //W3C Dom method.



                



                var file = document.createElement('input') ;



                file.id = fileID+count ;



                file.name = fileName+'[]' ;



                file.type = 'file' ;



				file.size = '30';



                cellLeft.appendChild( file ) ;



        }



}







function getPortalSiteList(portalid)



{



location = "?showportalsites="+portalid;



}



function showList(type)



{



if (type.value=="2") {



	document.getElementById("sitelist").style.display = 'block';



	document.getElementById("packagelist").style.display = 'none';



	document.getElementById("packagesitelist").style.display = 'none';



}



else  if (type.value=="1") {



	document.getElementById("sitelist").style.display = 'none';



	document.getElementById("packagelist").style.display = 'block';



	document.getElementById("packagesitelist").style.display = 'block';	



} else {



		document.getElementById("packagesitelist").style.display = 'none';



		document.getElementById("sitelist").style.display = 'none';



		document.getElementById("packagelist").style.display = 'none';



}



}



function pimportfrom(from)



{



	document.getElementById("authorworning").style.display = 'block';



	if (from.value == "T" || from.value == "Z" ) {



			document.getElementById("importtextzipdiv").style.display = 'block';



			document.getElementById("importmanual").style.display = 'none';



			document.getElementById("whichimport").style.display = 'block';



			document.getElementById("saperater").style.display = 'none';	

			

			document.getElementById("wizard").style.display = 'none';						



		if (from.value=="Z") file = "ZIP"; else file = "TEXT";



			document.getElementById("whichimport").innerHTML = 'Select a '+file+' file :';



	} else if (from.value== "M") {



			document.getElementById("importtextzipdiv").style.display = 'none';



			document.getElementById("importmanual").style.display = 'block';



			document.getElementById("whichimport").style.display = 'block';



			document.getElementById("whichimport").innerHTML = 'Enter Articles : ';			



			document.getElementById("saperater").style.display = 'block';	

			document.getElementById("wizard").style.display = 'none';						



	}

	else if (from.value== "C") {



			document.getElementById("importtextzipdiv").style.display = 'none';

					  document.getElementById("importmanual").style.display = 'none';

					  document.getElementById("whichimport").style.display = 'none';

					  document.getElementById("authorworning").style.display = 'none';

					  document.getElementById("wizard").style.display = 'block';

					  document.getElementById("saperater").style.display = 'none';

				

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

				  xmlHttp.onreadystatechange=function()

					{

					if(xmlHttp.readyState==4)

					  {

					  

					  	//alert(xmlHttp.responseText);

					  document.getElementById("wizard").innerHTML=xmlHttp.responseText;

					  }

					}

				  xmlHttp.open("GET","cat_.php",true);

				  xmlHttp.send(null);

				  }					

				

}



function pmode(mode)



{



	if (mode.value=="O") {



		document.getElementById("modeone").style.display = 'block';



		document.getElementById("moderec").style.display = 'none';



//		alert("keyword pages are generated and project is completed");



	} else if (mode.value=="R") {



		document.getElementById("modeone").style.display = 'none';



		document.getElementById("moderec").style.display = 'block';



	}



}



function chkform(form)



{


var anum=/(^\d+$)|(^\d+\.\d+$)/;
mss = "";



if (form.process.value != "addnewarticle")



{



	if (form.type.value== "1") site = form.site_id2.value; else site = form.site_id1.value;



	if (site == 0) mss += "- Site shold be selected\n";



}



if (!(form.source_type[0].checked || form.source_type[1].checked || form.source_type[2].checked || form.source_type[3].checked))



{



mss += "- Article source should be selected\n";



}



else if ((form.source_type[0].checked || form.source_type[1].checked) && form.importtextzip.value == "")



{



mss += "- Article source file should be selected\n";



}



else if (form.source_type[2].checked && form.importmanual.value == "")



{



mss += "- Article should be entered \n";



}



if (form.process.value != "addnewarticle")



{



	if (!(form.mode[0].checked || form.mode[1].checked))



	{



		mss += "- Mode should be selected\n";



	}



	else if (form.mode[0].checked)



	{



		if (!(form.gen.checked) )



		{



			mss += "- Generate type should be selected\n";



		}



	}



	else if (form.mode[1].checked && (form.genarticlesp.value == "" || form.period.value == ""))
	{
		mss += "- No of articles/frequency should be entered\n";
	}
	else if (form.mode[1].checked && (anum.test(form.genarticlesp.value)==false || form.genarticlesp.value<=0 || anum.test(form.period.value)==false || form.period.value<=0 ))
		{
		mss += "- No of articles/frequency should be greater then 0 \n";
		}


	



	if (!(form.will_keyword_generate[0].checked  || form.will_keyword_generate[1].checked))



	{



		mss += "- Generate keyword list? should be selected\n";



	}



}

// check validation for check boxes

if (form.source_type[3].checked )



{



		flags=false;

		var element;

		var numberOfControls = document.myimportform.length;

		for (Index = 0; Index < numberOfControls; Index++)

		{

		element = document.myimportform[Index];

			if (element.type == "checkbox")

			{

				if (element.checked == true)

				{

					flags=true;

				}

			}

		}

		if (flags==false) { 

			alert("Please select at least check box."); 

			return false;

		 }

	}

// end of check boxes 









if (mss.length > 0)



{



alert(mss);



return false;



}



else



{



return true;



}







}



function checkUncheckAll(theElement)

	{           

		var tForm = theElement.form, z = 0;  

		//alert(theElement.form);

		for(z=0;z<tForm.length;z++)

		{

		      if(tForm[z].type == 'checkbox' && tForm[z].name != 'checkall')

		      {

			      tForm[z].checked = theElement.checked;	      

		      }

		}

	}

function rand_no(theElement)

	{

		var tForm = theElement.form, z = 0;

		///alert(theElement.form);  

		var txt=document.getElementById("art").value;

		var countChk=0;

		var eleNum=0;

		var startAr=false;

		for(z=0;z<tForm.length;z++)

		{

			if(tForm[z].type=='checkbox')

			{

				//eleNum[countChk]=z;

				if(startAr==false)

				{

					eleNum=z+1;

					startAr=true;

				}

				countChk++;

			}

		}

		

		//alert(countChk);

		//alert(document.getElementById("art").value+"admin");

		//alert(tForm.length);

		 if (document.getElementById("art").value.length == 0) 

		  {

		  alert("Please enter a value.");

		  } 

	   else if (IsNumeric(document.getElementById("art").value) == false) 

		  {

		  alert("Please enter numeric value!");

		  }

		if(txt>(countChk-1)){

			alert("Please enter numeric value then then "+countChk);

			document.getElementById("art").focus();

			return false;

		}

		for(z=0;z<txt;z++)

		{

			//alert("?");\

			var n=Math.floor(Math.random()*(countChk-1))+eleNum;

				

		      if(tForm[n].type == 'checkbox' && tForm[n].name != 'checkall')

		      {

			  	  //alert("I m a cchkbox "+n);	

			      tForm[n].checked =true;

				  

				  //ch_art[z]=tForm[n].value;	      

		      }

		}

	

	}



function selcat()

	{

		//alert("admin"+document.getElementById("amcat").value);type

		

		//ccc=document.getElementById("type").value;

		//alert(ccc);

		//document.getElementById("type1").value=ccc;

		document.getElementById("amcat").value=document.getElementById("seamcat").value;

		document.cat.submit();

		

	}



// select categores function 

   function cat_select(theElement){

   		//alert("admin"+id);

		var tForm1 = theElement.form;

		//alert(tForm1.length);

		for(z=0,i=0;z<tForm1.length;z++)

		{

		      if(tForm1[z].type == 'checkbox' && tForm1[z].name != 'checkall' && tForm1[z].checked==true)

		      {

			      ch_art[i]=tForm1[z].value;i++;	      

		      }

		}

		//alert("admin"+ch_art.length);

		/*for(i=0;i<ch_art.length;i++){

			alert(ch_art[i]);

		}*/

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

				  xmlHttp.onreadystatechange=function()

					{

					if(xmlHttp.readyState==4)

					  {

					  document.getElementById("importtextzipdiv").style.display = 'none';

					  document.getElementById("importmanual").style.display = 'none';

					  document.getElementById("whichimport").style.display = 'none';

					  document.getElementById("authorworning").style.display = 'none';

					  document.getElementById("wizard").style.display = 'block';

					  document.getElementById("saperater").style.display = 'none';

					  	//alert(xmlHttp.responseText);

					  document.getElementById("wizard").innerHTML=xmlHttp.responseText;

					  }

					}

					var id=document.getElementById("seamcat").value;

					var url="cat_.php";

					url=url+"?amcat="+id+"&article="+ch_art;

					



				  xmlHttp.open("GET",url,true);

				  xmlHttp.send(null);

			

   }

   

   // end of funtion 



function IsNumeric(strString)

   //  check for valid numeric strings	

   {

   var strValidChars = "0123456789.-";

   var strChar;

   var blnResult = true;



   if (strString.length == 0) return false;



   //  test strString consists of valid characters listed above

   for (i = 0; i < strString.length && blnResult == true; i++)

      {

      strChar = strString.charAt(i);

      if (strValidChars.indexOf(strChar) == -1)

         {

         blnResult = false;

         }

      }

   return blnResult;

   }

 function opencode(id)

{

	openwindow= window.open ("showarticles.php?id="+id, "GETCODE",

		"'status=0,scrollbars=1',width=650,height=500,resizable=1");

	

	openwindow.moveTo(50,50);

}  

  

   

   

</script>







<?php require_once("top.php"); ?>







<?php require_once("left.php"); ?>



  <table width="100%"  border="0" cellspacing="0" cellpadding="0">



  



    <tr>



      <td align="left">



<?php



$home = '<a class="general" href="index.php">Home</a> >>';



if ($process == "manage")



$middle = "My Projects";



if ($process == "new")



$middle = '<a class="general" href= "projects.php?page='.$page.'">My Projects</a> >> New Project';



if ($process == "addnewarticle")



$middle = '<a class="general" href= "projects.php?page='.$page.'">My Projects</a> >> Add New Article';







$bcrumb = $home.$middle;







echo $bcrumb;



?>



	  </td>



  </tr>



  <tr>



  <td width="100%" align="center">&nbsp;<?php echo $msg ?></td>



  </tr>







  <td>



	  <br/>



<?php if ($process == "new" || $process == "edit" || $process == "addnewarticle") { ?>



<form name="myimportform" method="post" enctype="multipart/form-data" onSubmit="return chkform(this)" action="article_projects.php">	



<div id="page1" style="display:block ">



<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center" class="summary2">



  <tr>



    <th colspan="2" align="left">&nbsp;Article Projects</t>



    </tr>



  <tr>



    <td colspan="2" align="center" >&nbsp;</td>



    </tr>



	



</tr>



<?php if ($process != "addnewarticle") { ?>



  <tr >



    <td align="right" width="20%" id="sitetitle" valign="top" nowrap="nowrap">Select a site : </td>



    <td align="left">



<div id="sitetype" style="display:block " align="left">



<table border="0" width="100%" cellpadding="0" cellspacing="0">



<tr  align="left" id="rd">



<td align="left" width="10%">



      <select name="type" id = "type" onChange="showList(this)">



        <option selected><-- Select Type --></option>



        <option value="1" <?php if ($kd["type"]=="1") echo "selected"; ?>>Package</option>



        <option value="2" <?php if ($kd["type"]=="2") echo "selected"; ?>>Site</option>



      </select>



</td>



<td align="left">



	  <?php echo $prj->showSiteList($kd["site_id1"], $kd["type"]) ?>



	  <?php echo $prj->showPortalList($kd["packagelist"], $kd["type"]) ?>



</td>



<td align="left">



	<select name="site_id2" id="packagesitelist"  <?php if ($kd["type"]=="1") echo ''; else echo 'style="display:none"';?> >



	<option value="0"><--Select SIte--></option>



	</select>



<td width="50%">&nbsp;



</td>







<tr>



<td colspan="4" class="formback2" height="2">



</td>



</tr>



</table>



</div>



<BR>



    </td>



  </tr>



<?php } ?>  



  <tr>



    <td align="right" valign="top" nowrap="nowrap"> Article source : </td>



    <td>







<table width="100%">



<tr>



	<td colspan="2" align="left">



        <input type="radio" name="source_type" value="T" onClick="pimportfrom(this)" <?php if ($kd["source_type"]=="T") echo "checked";?>>



    Text file (new line separated) 



        <input type="radio" name="source_type" value="Z" onClick="pimportfrom(this)" <?php if ($kd["source_type"]=="Z") echo "checked";?>>



    ZIP file



        <input type="radio" name="source_type"  value="M" id="importfrommanual" onClick="pimportfrom(this)" <?php if ($kd["source_type"]=="M") echo "checked";?>>



    Manually



		<input type="radio" name="source_type"  value="C" id="contentwizard" onClick="pimportfrom(this)" <?php if ($kd["source_type"]=="C") echo "checked";?>>



    Content Wizard

	<br>



	<label id="authorworning" style="display:none">Be



sure to enter the author's name below the title. Then you can start



the body of the article</label>



	</td>



</tr>



<tr class="formback2">



<td align="right"> <label id="whichimport" <?php if ($kd["source_type"]=="Z" || $kd["source_type"]=="T") echo ">Select a file: " ; else echo 'style="display:none;">&nbsp;'; ?>></label> </td>



	<td align="left">	







		<div id="importtextzipdiv" <?php if ($kd["source_type"]=="Z" || $kd["source_type"]=="T") echo "" ; else echo 'style="display:none"'; ?>>



		<div id="innerimportfile">







<!--		  <input name="importtextzip[]" id="importtextzip" type="file" size="30" multiple> -->



			<table id="file_area" border="0">



			<tr>



			<td>



			<input name="importtextzip[]" id="importtextzip" type="file" size ="30" />



			</td>



			</tr>



			</table>



			<input type="button" value="Add another file" onclick="addField_2('file_area','ta_desc','description_','importtextzip','file_',15);" />







		</div>



		<input type="hidden" name="nooffile" id="nooffile" value="1" >



		</div>







<!--		  <input name="importtextzip" type="file" size="30" id="importtextzip" > -->







		  <textarea name="importmanual" cols="70" rows="7" id="importmanual" style="display:none"></textarea>



		  <label id="saperater" style="display:none">you can enter multiple articles separated by ###NEW### in between them</label>



		  



		 <div id="wizard" style="display:none">

		 <?php

				//$sql = "select count(*) from `".TABLE_PREFIX."am_article` ";

		/*			if($process=="new")

					{

						$sql = "select count(*) from `".TABLE_PREFIX."am_article` ";

					}

					elseif($process=="advsearch")

					{

						$sql = "select count(*) from `".TABLE_PREFIX."am_article` where category_id='".$_REQUEST['amcat']."' ";

					}

					//else

						//$sql = "select count(*) from `".TABLE_PREFIX."am_article` ";

					//echo $sql;

				$totalrecords = $database->getDataSingleRecord($sql);

			   if ($totalrecords>0)

			   {

					   $pg->setPagination($totalrecords);

					   

					   $order_sql = $sc->getOrderSql(array("id","category","title","summary","source","status"),"id");

			?>

			<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">

			

				<tr>

					<td align="center" colspan="6">

						<input type="text" name="art" id="art"  />

						<input type="button" name="rand"  value="Random" onClick='rand_no(this)'/>

					</td>

				</tr>

				<tr bgcolor="#999999">

				<td><a title = "Sort" class = "menu" href="?sort=id">ID</a></td>

				<td>

										

						<select name="seamcat" id="seamcat" style="background-color:#A2A2A2" onchange="selcat();" >

							<?php  $article -> SelectBox(); ?>

						</select>

						

					

				</td>

				

				 

				   <input type="hidden" name="btndelete" value="" />

				<td><a title = "Sort" class = "menu" href="?sort=title">Title</a></td>

				<td><a title = "Sort" class = "menu" href="?sort=summary">Summary</a></td>

				

				

				<td><input name='chkall' type='checkbox' value='chkall' onClick='checkUncheckAll(this)'></td>

				</tr>

	

				<?php	

						if($process=="new")

						{

						echo $article->manageArticle();

						}

						else

						{

						echo $article->selectCategory();

						}

			

				

				?></table>

			<?php 

			

			}

				   else

				   {

						   $totalrecords = 0;

						   $man_rs = false;

				   }

				   */

			?>

						

		

		</div>

		



	</td>		  



</tr>



</table>



<br>



    </td>



  </tr>



<?php if ($process != "addnewarticle") { ?>



  <tr>



    <td align="right" valign="top" nowrap="nowrap"> Mode : </td>



    <td width="100%">



<table width="100%">



<tr>



	<td colspan="2" align="left">



        <input type="radio" name="mode" id="modeo" value="O" onClick="pmode(this)" <?php if ($kd["mode"]=="O") echo "checked";?>>



    One TIme



        <input type="radio" name="mode" id="moder" value="R" onClick="pmode(this)" <?php if ($kd["mode"]=="R") echo "checked";?>>



    Recurring



	</td>



</tr>



</table>



<?php if ($kd["mode"]=="O") { ?>



<div id="modeone" style="display:block;">



<?php } else { ?>



<div id="modeone" style="display:none;">



<?php } ?>



<table width = "100%" class="formback2">



<tr>



<td align="right" width="20%"><input type="radio" name="gen" id="gena" value="A"  <?php if ($kd["gen"]=="A") echo "checked";?>></td>



<td align="left" width="80%">Generate All</td>



</tr>







</table>	



</div>











<?php if ($kd["mode"]=="R") { ?>



<div id="moderec" style="display:block">



<?php } else { ?>



<div id="moderec" style="display:none">



<?php } ?>







<table width="100%" class="formback2">



<tr>



<td align="right" width="40%">No.of articles will be added :</td>



<td align="left" width="60%"><input name="genarticlesp" type="text"  id="noofarticles" value="<?php echo $kd["genarticlesp"]?>" size="6" maxlength="6"></td>



</tr>



<tr>



<td align="right">Frequency (in days) :</td>



<td align="left"><input name="period" type="text"  id="noofdays" value="<?php echo $kd["period"]?>" size="6" maxlength="6"></td>



</tr>



</table>



</div>



<br>



	</td>



  </tr>



<tr>



<td align="right" valign="top" nowrap="nowrap">Generate keyword list ? </td>



<td align="left"> <input type="radio" name="will_keyword_generate" id="genlisty"  value="Y" <?php if ($kd["will_keyword_generate"]=="Y") echo "checked";?>> Yes 



				<input type="radio" name="will_keyword_generate" id="genlistn"   value="N" <?php if ($kd["will_keyword_generate"]=="N") echo "checked";?> > No 



</td>



</tr>



  



  <tr>



    <td colspan="2" align="center" >&nbsp;</td>



    </tr>



<?php } ?>



  <tr>



    <td colspan="2" align="center" class="heading">



	<input type="hidden" name="process" value="<?php echo $process ?>">



	<input type="hidden" name="articleform" value="yes">



	<input type="hidden" name="projtype" value="A">	



	<input type="hidden" name="page" value="<?php echo $_GET["page"] ?>">	



	<input type="hidden" name="site_id" value="<?php echo $_GET["site_id"] ?>">		



	<input type="hidden" name="addnewartprojectid" value="<?php echo $_GET["id"] ?>">



      <input type="submit" name="submit" value="Submit"> 



      <!--      <input type="submit" name="submit" value="Submit" onClick="process()" > -->



    </td>



    </tr>







</table>



</div>



</form>

<form action="article_projects.php" method="get" name="cat" >

	

	<input type="hidden" name="process" value="new" />

	<input type="hidden" name="source_type" value="C" />

	<input type="hidden" name="type1" value="" />

	<input type="hidden" name="amcat" id="amcat" value="" />

</form>

<?php } // new process ends



		



		



?>



	  



	  <br>	  <br>	  <br>



	  </td>



    </tr>



  </table>



	



<?php require_once("right.php"); ?>



<?php require_once("bottom.php"); ?>



<?php 



if ($kd["type"]=="1")



{



?>



<script language="javascript">



PopulateCategory(document.forms[0].packagelist,document.getElementById('packagesitelist'),<?php echo $kd["site_id2"]?>);



</script>



<?php



}



$ms_db->closeDB();



?>