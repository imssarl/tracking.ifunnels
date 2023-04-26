<?php

if(isset($_POST["rte1"]) && $_POST["rte1"] != "")
{
	echo $_POST["rte1"];
}

function setRTeditor($matter)
{
$output="";
$output.='<script language="JavaScript" type="text/javascript" src="rte/html2xhtml.js"></script>
<script language="JavaScript" type="text/javascript" src="rte/richtext.js"></script>
<script language="JavaScript" type="text/javascript">
function submitForm() {
	//make sure hidden and iframe values are in sync for all rtes before submitting form
	updateRTEs();
	//alert(document.RTEDemo.rich.value);
	return true;
}
//Usage: initRTE(imagesPath, includesPath, cssFile, genXHTML, encHTML)
initRTE("./rte/images/", "./rte/", "", true);
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>
<script language="JavaScript" type="text/javascript">
//build new richTextEditor
var rte1 = new richTextEditor("rich");';
//format content for preloading

if (!(isset($matter))) {
	$content = "";
	//$content = rteSafe($content);
} else {
	//retrieve posted value
	$content = rteSafe($matter);
}
$output.="rte1.html ='".$content."' ;";
$output.='rte1.toggleSrc = false;
rte1.build();
//-->
</script>
';
return $output;
}

function rteSafe($strText) {
	//returns safe code for preloading in the RTE
	$tmpString = $strText;
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	$tmpString = str_replace("'", "&#39;", $tmpString);
	
	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
//	$tmpString = str_replace("\"", "\"", $tmpString);
	
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), " ", $tmpString);
	$tmpString = str_replace(chr(13), " ", $tmpString);
	return $tmpString;
}

?>

<script language="javascript">
	function setEditor(val)
	{	
		if(val == "1")
		{
			document.getElementById("td1").style.visibility = "visible";
			document.getElementById("td1").style.visibility = "relative";
			document.getElementById("td2").style.visibility = "hidden";
			document.getElementById("td2").style.visibility = "absolute";
		}
		else if(val == "2")
		{
			document.getElementById("td2").style.visibility = "visible";
			document.getElementById("td2").style.visibility = "relative";
			document.getElementById("td1").style.visibility = "hidden";
			document.getElementById("td1").style.visibility = "absolute";
		}
	}
</script>
	<script language="JavaScript" type="text/javascript" src="html2xhtml.js"></script>
	<script language="JavaScript" type="text/javascript" src="richtext.js"></script>

<!-- START Demo Code -->
<form name="RTEDemo" action="demo.php" method="post" onsubmit="return submitForm();">
<table>
<tr >
<td>
	<input type="radio" name="r" onclick="setEditor('1')" />
	<input type="radio" name="r" onclick="setEditor('2')" />
</td>
</tr>
<tr id="td1">
<td>
<script language="JavaScript" type="text/javascript">
<!--
function submitForm()
{
	//make sure hidden and iframe values are in sync for all rtes before submitting form
	updateRTEs();
	//change the following line to true to submit form
	
}
//Usage: initRTE(imagesPath, includesPath, cssFile, genXHTML, encHTML)
initRTE("./images/", "./", "", true);
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>
<script language="JavaScript" type="text/javascript">
<!--
//build new richTextEditor
var rte1 = new richTextEditor('rte1');
var rte2 = new richTextEditor('rte2');

rte1.html = '';
rte1.toggleSrc = false;
rte1.build();

//-->
</script>
</td>
</tr>
<tr id="td2" style="visibility:hidden;position:absolute">
<td >
	<textarea name="text1"></textarea>
</td>
</tr>
</table>
<p><input type="submit" name="submit" value="Submit" /></p>
</form>
<!-- END Code -->


