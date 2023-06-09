<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
// Cross-Browser Rich Text Editor
// Written by Kevin Roth (http://www.kevinroth.com/rte/)
// Visit the support forums at http://www.kevinroth.com/forums/index.php?c=2
// This code is public domain. Redistribution and use of this code, with or without modification, is permitted.
//-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Cross-Browser Rich Text Editor (RTE)</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="keywords" content="cross-browser rich text editor, rte, textarea, htmlarea, content management, cms, blog, internet explorer, firefox, safari, opera, netscape, konqueror" />
	<meta name="description" content="The cross-browser rich-text editor (RTE) is based on the designMode() functionality introduced in Internet Explorer 5, and implemented in Mozilla 1.3+ using the Mozilla Rich Text Editing API." />
	<meta name="author" content="Kevin Roth" />
	<meta name="ROBOTS" content="ALL" />
	<!-- html2xhtml.js written by Jacob Lee <letsgolee@lycos.co.kr> //-->
	<script language="JavaScript" type="text/javascript" src="html2xhtml.js"></script>
	<script language="JavaScript" type="text/javascript" src="richtext_compressed.js"></script>
</head>
<body>

<h2>Cross-Browser Rich Text Editor (RTE) Demo</h2>
<p>For more information, visit the <a href="http://www.kevinroth.com/rte/">Cross-Browser Rich Text Editor (RTE) home page</a>.</p>

<!-- START Demo Code -->
<form name="RTEDemo" action="demo.asp" method="post" onsubmit="return submitForm();">
<script language="JavaScript" type="text/javascript">
<!--
function submitForm() {
	//make sure hidden and iframe values are in sync for all rtes before submitting form
	updateRTEs();
	
	return true;
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
<%
if request.form("rte1") = "" then
	sContent = "here's the " & chr(13) & """preloaded <b>content</b>"""
	sContent = RTESafe(sContent)
else
	sContent = RTESafe(request.form("rte1"))
end if
%>
rte1.html = '<%=sContent%>';
//rte1.toggleSrc = false;
rte1.build();
//-->
</script>
<p><input type="submit" name="submit" value="Submit" /></p>
</form>
<%
function RTESafe(strText)
	'returns safe code for preloading in the RTE
	dim tmpString
	
	tmpString = trim(strText)
	
	'convert all types of single quotes
	tmpString = replace(tmpString, chr(145), chr(39))
	tmpString = replace(tmpString, chr(146), chr(39))
	tmpString = replace(tmpString, "'", "&#39;")
	
	'convert all types of double quotes
	tmpString = replace(tmpString, chr(147), chr(34))
	tmpString = replace(tmpString, chr(148), chr(34))
'	tmpString = replace(tmpString, """", "\""")
	
	'replace carriage returns & line feeds
	tmpString = replace(tmpString, chr(10), " ")
	tmpString = replace(tmpString, chr(13), " ")
	
	RTESafe = tmpString
end function
%>
<!-- END Demo Code -->

</body>
</html>
