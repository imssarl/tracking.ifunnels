<?php

	//PHP code will come here

	session_start();

	require_once("config/config.php");

 	require_once("classes/database.class.php");

	require_once("classes/settings.class.php");

 	require_once("classes/traffic.class.php");



$settings = new Settings();

$settings->checkSession();

$database = new Database();

$traffic = new Traffic();

$database->openDB();

	//require_once("classes/ntlkey.class.php");



?>



<?php

if(isset($_GET['process']))

{

	$process=$_GET['process'];

}

else

$process="manage";

if(isset($_GET['key']))

{

	if($_GET['key']=="All")

	$process="display";

	else

	$process="google";	

}



//if(isset($_GET['submit']))
if($process=="google")
{	

	
	$content= $_GET['keyword'];
	$regex = "([^0-9 a-zA-Z`~!@#$%\^&\*\(\)-_=\+\|\\\{\}\[\]:;\"'<>\?/]+)";
  	$str1 = preg_replace($regex, "" , $content);
	if($_GET['query']!=1)
	{
		$keyphrases = $str1." ".$_GET['key'];//$_GET['keyword']." ".$_GET['key'];
	}
	else
	{
		$keyphrases = '"'.$str1." ".$_GET['key'].'"';
	}
		$datacenter = trim($_GET['datacenter']);
		$var = $datacenter."search?q=";
		
		//$KEYWORD_MAIN_TAG["GOOGLE"] = array("<div class=g>","</div>"); Commented by SDEI 101108
		$KEYWORD_MAIN_TAG["GOOGLE"] = array("<li class=g>","</div>");
		
		//$KEYWORD_TITLE_TAG["GOOGLE"] = array("<h2 class=r>","</h2>");
		$KEYWORD_TITLE_TAG["GOOGLE"] = array("<h3 class=r>","</h3>");

		//$KEYWORD_SUMMARY_TAG["GOOGLE"] = array('<table border=0 cellpadding=0 cellspacing=0><tr><td class="j"><font size=-1>','<br><span class=a>');
		$KEYWORD_SUMMARY_TAG["GOOGLE"] = array('</h3>','<span class=gl>');

		//$KEYWORD_URL_TAG["GOOGLE"] = array('<span class=a>',' -');   
		$KEYWORD_URL_TAG["GOOGLE"] = array('<h3 class=r>','</h3>');

		$KEYWORD_SUMMARY_SEPARATOR["GOOGLE"] = array("<nobr>");

		$KEYWORD_SOURCE_SITES["GOOGLE"] = array("GOOGLE",$var);

		//$KEYWORD_SOURCE_SITES["GOOGLE"] = array("GOOGLE","http://www.google.co.in/search?q=");

		$KEYWORD_START_VARS["GOOGLE"] = array("start",0,10);

		$KEYWORD_DATAS = 20;   

		$KEYWORD_SEARCH_BY = "GOOGLE";



// CURLOPT_URL, $KEYWORD_SOURCE_SITES[$KEYWORD_SEARCH_BY][1].urlencode($keyphrases)."&".$KEYWORD_START_VARS[$KEYWORD_SEARCH_BY][0]."=".$start;

    /*if(function_exists("fopen"))

    {
		
        $str = "";

        $n=0;

			//echo $KEYWORD_SOURCE_SITES[$KEYWORD_SEARCH_BY][1].urlencode($keyphrases);

     //   for ($start=$KEYWORD_START_VARS[$KEYWORD_SEARCH_BY][1];$start<$KEYWORD_DATAS;$start+=$KEYWORD_START_VARS[$KEYWORD_SEARCH_BY][2])

      //  {

			//echo file_get_contents($KEYWORD_SOURCE_SITES[$KEYWORD_SEARCH_BY][1].urlencode($keyphrases));

            $fp = @fopen($KEYWORD_SOURCE_SITES[$KEYWORD_SEARCH_BY][1].urlencode($keyphrases),"r");

            if($fp)

	   {       

				


                while(!feof($fp))

                {

                    $str .= fgets($fp);

                }
				
                fclose($fp);



                $process ="true";

            }

            else {

                echo "<br>&nbsp;Unable to open ".$KEYWORD_SOURCE_SITES[$KEYWORD_SEARCH_BY][1].$keyphrases;

                $process = "false";

            }

      //  }

//echo $str;





    }

    else*/ if(function_exists("curl_init"))

    {
		//echo $ch, CURLOPT_URL, $KEYWORD_SOURCE_SITES[$KEYWORD_SEARCH_BY][1].urlencode($keyphrases)."&".$KEYWORD_START_VARS[$KEYWORD_SEARCH_BY][0]."=".$start;
        $str = "";

        $n=0;

        for ($start=$KEYWORD_START_VARS[$KEYWORD_SEARCH_BY][1];$start<$KEYWORD_DATAS;$start+=$KEYWORD_START_VARS[$KEYWORD_SEARCH_BY][2])

        {

            $ch = curl_init();
			$ip_port="213.185.116.218:3128";

            curl_setopt($ch, CURLOPT_URL, $KEYWORD_SOURCE_SITES[$KEYWORD_SEARCH_BY][1].urlencode($keyphrases)."&".$KEYWORD_START_VARS[$KEYWORD_SEARCH_BY][0]."=".$start);

        

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			@curl_setopt($cUrl, CURLOPT_TIMEOUT, PROXY_TIMEOUT_SEC);
			@curl_setopt($cUrl, CURLOPT_HTTPPROXYTUNNEL, 1);
			@curl_setopt($cUrl, CURLOPT_PROXY,$ip_port );
		    curl_setopt($ch, CURLOPT_HEADER, 1);	

            $resp = curl_exec($ch);

            curl_close ($ch);

            $str .= $resp;
			$process="true";
        }

 //echo $str;

 //die();

    }



if ($process == "true")

{

$page = "";



preg_match_all("|(".$KEYWORD_MAIN_TAG[$KEYWORD_SEARCH_BY][0]."(.*)".$KEYWORD_MAIN_TAG[$KEYWORD_SEARCH_BY][1].")|U",$str, $out);

//print_r($out); die();

for($x=0;$x<count($out[2]);$x++)

{

preg_match_all("|(".$KEYWORD_TITLE_TAG[$KEYWORD_SEARCH_BY][0]."(.*)".$KEYWORD_TITLE_TAG[$KEYWORD_SEARCH_BY][1].")|U",$out[2][$x], $out_1);

$page .= "<b>".strip_tags($out_1[2][0])."</b><br>";

$data .= strip_tags($out_1[2][0])."\n";

preg_match_all("|(".$KEYWORD_SUMMARY_TAG[$KEYWORD_SEARCH_BY][0]."(.*)".$KEYWORD_SUMMARY_TAG[$KEYWORD_SEARCH_BY][1].")|U",$out[2][$x], $out_2);

$page .= strip_tags($out_2[2][0])."<br>";

$data .= strip_tags($out_2[2][0])."\n";

preg_match_all("|(".$KEYWORD_URL_TAG[$KEYWORD_SEARCH_BY][0]."(.*)".$KEYWORD_URL_TAG[$KEYWORD_SEARCH_BY][1].")|U",$out[2][$x], $out_3);

$out_4=str_replace("<a href=\""," ",$out_3[2][0]); 
$pos=strpos($out_4,"\" class=l>");
$out_5=substr($out_4,0,$pos);
$out_4=substr($out_4,$pos+10); 
$out_4=str_replace("</a>"," ",$out_4); 
//$pos=strpos($out_4,"</a>");
//$out5=substr($out_4,0,$pos+3);
//$out_5=str_replace("</a>"," ",$out_5);
//$out_6=substr($out_4,$pos+1);
//$out_5="http://".$out_4;
//$out_5=$out_4;

$page .= "<a href='".strip_tags($out_5)."' TARGET='_blank'>".$out_4."</a><br><br>";

//$page .=$out_5."<br><br>";
$data .= strip_tags($out_5)."\n\n";

//$out1 = "";

//echo $out_3[2][0];die();

$_SESSION['PAGE']=$data;



/*$summary =  preg_split("/".$KEYWORD_SUMMARY_SEPARATOR[$KEYWORD_SEARCH_BY][0]."/",$out_1[2][0]);



$page .= $summary[0]."<br>";*/

}

//for($i=0;$i<10;$i++)

//echo $page2."<Br><br><br>".$page3."<Br><br><br>";

 //die();

}

}

?>

<?php require_once("header.php"); ?>



<title>



<?php echo SITE_TITLE; ?>

</title>



<script language="javascript">

	// Javascript code will come here

// 	function opencode()

// 	{

// 		openwindow= window.open ("openntl.php", "GETCODE",

// 			"'status=0,scrollbars=1',width=650,height=500,resizable=1");

// 		

// 		openwindow.moveTo(50,50);

// 	}



	function showdata(val)

	{

		document.getElementById("data").innerHTML=val.value;

	}



	function check()

{

	var flag=true;

	var msg=""

	if(document.ntl.keyword.value=="")

	{

	msg+="Please Enter Keyword\n";

	}

	if(document.ntl.datacenter.value=="")

	{

	msg+="Please Select Datacenter\n";

	}

	

	if(msg.length>0)

	{

		alert(msg);

		flag=false;

	}

	return flag;

	}

</script>
 <script src="http://www.google.com/jsapi"
        type="text/javascript"></script>
    <script language="Javascript" type="text/javascript">
    //<![CDATA[
	
    google.load('search', '1');
    
    /*function searchG(srcin) {
      // Create a search control
      var searchControl= new google.search.SearchControl();
	  var drawOptions = new google.search.DrawOptions();
	  var src=document.getElementById("keyword").value; 	
      // Add in a full set of searchers
      //var localSearch = new google.search.LocalSearch();
      //searchControl.addSearcher(localSearch);
      searchControl.addSearcher(new google.search.WebSearch());
      //searchControl.addSearcher(new google.search.VideoSearch());
      //searchControl.addSearcher(new google.search.BlogSearch());
      //searchControl.addSearcher(new google.search.NewsSearch());
     // searchControl.addSearcher(new google.search.ImageSearch());
      //searchControl.addSearcher(new google.search.BookSearch());
      //searchControl.addSearcher(new google.search.PatentSearch());

      // Set the Local Search center point
      //localSearch.setCenterPoint("New York, NY");

      // tell the searcher to draw itself and tell it where to attach
	  //drawOptions.setDrawMode(google.search.SearchControl.DRAW_MODE_TABBED);
	  //searchControl.draw(document.getElementById("searchcontrol"),drawOptions);

      // execute an inital search
	  var mysearch;
	  switch(srcin)
	  {
	  		case "all":
			mysearch=src+" Forum"+ " Blog"+ " Directories"+" Social Networking Sites";
			break;
			case "forum":
			mysearch=src+" Forum"
			break;
	  }
      searchControl.execute(mysearch);
    }
    //google.setOnLoadCallback(OnLoad);*/
	
	function OnLoad() {
      // Create a search control
      var searchControl = new google.search.SearchControl();

      // Add in a full set of searchers
      var localSearch = new google.search.LocalSearch();
      //searchControl.addSearcher(localSearch);
      searchControl.addSearcher(new google.search.WebSearch());
      //searchControl.addSearcher(new google.search.VideoSearch());
      searchControl.addSearcher(new google.search.BlogSearch());
      //searchControl.addSearcher(new google.search.NewsSearch());
      //searchControl.addSearcher(new google.search.ImageSearch());
      //searchControl.addSearcher(new google.search.BookSearch());
      //searchControl.addSearcher(new google.search.PatentSearch());
		
      // Set the Local Search center point
      //localSearch.setCenterPoint("New York, NY");

      // tell the searcher to draw itself and tell it where to attach
      //searchControl.draw(document.getElementById("searchcontrol"));
	  
	  // create a drawOptions object
	  var drawOptions = new google.search.DrawOptions();

	  drawOptions.setDrawMode(google.search.SearchControl.DRAW_MODE_TABBED);
	  searchControl.draw(document.getElementById("searchcontrol"), drawOptions);	

      // execute an inital search
      //searchControl.execute("VW GTI");
	  
	  /*/ create a search form without a clear button
	// bind form submission to my custom code
	var container = document.getElementById("searchFormContainer");
	this.searchForm = new google.search.SearchForm(false, container);
	this.searchForm.setOnSubmitCallback(this, App.prototype.newSearch);
	
	// called on form submit
	App.prototype.newSearch = function(form) {
	  if (form.input.value) {
		this.searchControl.execute(form.input.value);
	  }
	  return false;
	}*/
	  
    }
   // google.setOnLoadCallback(OnLoad);

	


    //]]>
	
    </script>


<?php require_once("top.php"); ?>



<?php require_once("left.php"); ?>



	<!-- html code will come here -->

<table width="100%"  border="0" cellspacing="0" cellpadding="0">

<tr>



	<td align="left">

<?php

$home = '<a class="a1" href="index.php">Home</a>';



if ($process=="manage")

{

	$manage = " >> Niche Traffic Locator";

}

elseif ($process=="display" || $process=="true")

{

	$manage = ' >> <a class="a1" href="traffic.php">Niche Traffic Locator</a> ';

}



if ($process=="display")

{

	$editprocess = ' >> Results';

}

else if ($process=="true")

{

	$editprocess = ' >> Results';

}

echo 	$home.$manage.$editprocess;

?>

	<br>

	</td>



<td  align="center"> <?php //echo $msg ?></td>

</tr>

</table>





<?php

if($process== "true")

{ 

?>

	

<?php //* ?>
<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">

	<tr><TD><br></TD></tr>

	<tr>

			

			<TD align="left" class="heading1" style="font-size:15px; font-weight:bold;"><?php echo stripslashes(str_replace('"','',$keyphrases));?></TD>

			<td class="heading" width="20%"><a href="?process=google&amp;search=0&amp;key=<?php echo stripslashes(str_replace('"','',$_GET['key']));?>&amp;keyword=<?php echo stripslashes(str_replace('"','',$_GET['keyword']));?>&amp;datacenter=<?php echo $_GET['datacenter'];?>">Expand</a>&nbsp;&nbsp;

			<?php

			/*if($_GET['query']!="")

			{

			?>

				<a href="?process=google&amp;query=1&amp;search=<?php echo $_GET['search'];?>&amp;key=<?php echo $_GET['key'];?>&amp;keyword=<?php echo $_GET['keyword'];?>&amp;datacenter=<?php echo $_GET['datacenter'];?>" onclick="opencode();">Export</a>

			<?php

			}else

			{

			*/?>

				

				<a href="openntl.php" target="_blank" >Export</a>

			<?php

			//}

			?>

			</td>

	</tr>

	<tr>

			

			<TD align="center" class="heading" colspan="2">

			

			<?php

				if($_GET['key']=="Forums")

				{

			?>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Blogs&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Blogs".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Social Networks&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Social Networks".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Directories&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Directories".'</b>|';?></a>

			<?php

			}else if($_GET['key']=="Blogs")

				{

			?>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Forums&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Forums".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Social Networks&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Social Networks".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Directories&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Directories".'</b>|';?></a>	

			<?php

			}else if($_GET['key']=="Social Networks")

			{

			?>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Forums&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Forums".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Blogs&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Blogs".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Directories&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Directories".'</b>|';?></a>	

			<?php

			}else if($_GET['key']=="Directories")

			{

			?>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Forums&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Forums".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Blogs&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Blogs".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Social Networks&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Social Networks".'</b>|';?></a>

			

			<?php

			}

			?>

			</TD>

	</tr>

		<tr><TD><br></TD></tr>

	<tr>

		<TD><?php echo $page;?></TD>

	</tr>

	<tr>

			

			<TD align="center" class="heading" colspan="2">

			

			<?php

				if($_GET['key']=="Forums")

				{

			?>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Blogs&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Blogs".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Social Networks&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Social Networks".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Directories&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Directories".'</b>|';?></a>

			<?php


			}else if($_GET['key']=="Blogs")

				{

			?>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Forums&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Forums".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Social Networks&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Social Networks".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Directories&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Directories".'</b>|';?></a>	

			<?php

			}else if($_GET['key']=="Social Networks")

			{

			?>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Forums&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Forums".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Blogs&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Blogs".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Directories&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Directories".'</b>|';?></a>	

			<?php

			}else if($_GET['key']=="Directories")

			{

			?>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Forums&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Forums".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Blogs&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Blogs".'</b>|';?></a>

				 <a href="?process=google&amp;search=1&amp;query=1&amp;key=Social Networks&amp;keyword=<?php echo stripslashes($_GET['keyword']);?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.stripslashes($_GET['keyword'])." "."Social Networks".'</b>|';?></a>

			

			<?php

			}

			?>
				

			</TD>

	</tr>

	<tr >

		<?php

		if($_GET['search']==1)

		{

		?>

		<TD align="center" class="heading" colspan="2"><a href="traffic.php?process=display&amp;key=All&amp;keyword=<?php echo $_GET['keyword'];?>&amp;datacenter=<?php echo $_GET['datacenter'];?>">Back</a>&nbsp;&nbsp;</TD>

		<?php

		}elseif($_GET['search']==0)

		{

		?>

		<TD align="center" class="heading" colspan="2"><a href="traffic.php?process=display&amp;key=<?php echo $_GET['key'];?>&amp;keyword=<?php echo $_GET['keyword'];?>&amp;datacenter=<?php echo $_GET['datacenter'];?>">Back</a>&nbsp;&nbsp;</TD>

		<?php

		}

		?>

	</tr>

	

</table><?php //*/?><br>

<?php

}

elseif($process=="manage")

{

?>

<form action="" method="GET" name="ntl" onsubmit="return check();">

<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">

	<tr><TD><br></TD></tr>

	<tr>

			

			<TD align="center" class="heading" colspan="4" >Niche Traffic Locator</TD>

	</tr>
	

		<tr><TD><br></TD></tr>

	<tr>

		<td rowspan="0" width="50%" valign="top">Please enter a keyword in the field on the right and we will return a

list of the most relevant niche related :

<table>

<tr><td>- Forums</td></tr>

<tr><td>- Blogs</td></tr>

<tr><td>- Social Networks</td></tr>

<tr><td>- Directories</td></tr>

</table>

		</td>

		

	</tr>

	

	

	<tr><TD><br></TD></tr>

	<tr>

		<TD align="right" colspan="2">Datacenter&nbsp;</TD>

		<td align="left" nowrap="true">

			<select name="datacenter" onchange="showdata(this);">

			

			<?php

				$traffic->datacenter();

			?>

			</select>&nbsp;<div id="data"></div>

		</td>

	</tr>

			<tr><TD><br></TD></tr>

	<tr>

		<td  align="right" colspan="2">Keyword&nbsp;</td>
		<td align="left"><input type="text" name="keyword" id="keyword" size="30"><br />
		<!--<div id="searchcontrol" >Loading</div>--></td>

	</tr>

	<tr><TD><br></TD></tr>
	<tr>

		<td><!--Keyword&nbsp;</td>
		<td align="left"><input type="text" name="keyword" id="keyword" size="30"><br /><div id="searchFormContainer" ></div>-->
		</td>

	</tr>


	<tr>

		<td align="center" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="key" value="All" style="width:100px;"></td>

	</tr>

	<tr><TD><br></TD></tr>

	<tr>

		<td align="center" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="key" value="Forums" style="width:100px;"></td>

	</tr>

	<tr><TD><br></TD></tr>

	<tr>

		<td align="center" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="key" value="Blogs" style="width:100px;"></td>

	</tr>

	<tr><TD><br></TD></tr>

	<tr>

		<td align="center" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="key" value="Social Networks" style="width:100px;"></td>

	</tr>

	<tr><TD><br></TD></tr>

	<tr>

		<td align="center" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="key" value="Directories" style="width:100px;"></td>

		

		<input type="hidden" name="search" value="1">

	</tr>

	



</table>


	<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">

	<tr>

			

			<TD align="center" class="heading" colspan="4" >&nbsp;</TD>

	</tr>

	</table>

	<br>

</form>
<?php

}elseif($process=="display")

{

?>

	<table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">

	<tr><TD><br></TD></tr>

	<tr>

		<TD align="center" class="heading" colspan="2"><?php echo "Results";//$keyphrases;?></TD>

	</tr>

		<tr><TD><br></TD></tr>

<?php	

	if($_GET['key']=="All")

	{

?>	

	

	<tr>

		<TD align="center"><a href="?process=google&amp;query=1&amp;search=1&amp;key=Forums&amp;datacenter=<?php echo $_GET['datacenter'];?>&amp;keyword=<?php echo $_GET['keyword'];?>"><?php echo '<b>'.$_GET['keyword']." "."Forums".'</b>';?></a></td>

		

	</tr>

	<tr><TD><br></TD></tr>

	<tr>

		<TD align="center"><a href="?process=google&amp;query=1&amp;search=1&amp;key=Blogs&amp;keyword=<?php echo $_GET['keyword'];?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.$_GET['keyword']." "."Blogs".'</b>';?></a></td>

		

	</tr>

	<tr><TD><br></TD></tr>

	<tr>

		<TD align="center"><a href="?process=google&amp;query=1&amp;search=1&amp;key=Social Networks&amp;datacenter=<?php echo $_GET['datacenter'];?>&amp;keyword=<?php echo $_GET['keyword'];?>"><?php echo '<b>'.$_GET['keyword']." "."Social Networks".'</b>';?></a></td>

		

	</tr>

	<tr><TD><br></TD></tr>

	<tr>

		<TD align="center"><a href="?process=google&amp;query=1&amp;search=1&amp;key=Directories&amp;keyword=<?php echo $_GET['keyword'];?>&amp;datacenter=<?php echo $_GET['datacenter'];?>"><?php echo '<b>'.$_GET['keyword']." "."Directories".'</b>';?></a></td>

		

	</tr>

	<tr><TD><br></TD></tr>

<?php	

	}



?>



	<tr><TD><br></TD></tr>

<?php

	

?>

	<tr>

		<TD align="center" class="heading" colspan="2"><a href="traffic.php?process=manage">Back</a></TD>

	</tr>

	</table><br>

<?php

}

?>

<?php require_once("right.php"); ?>

<?php require_once("bottom.php"); ?>