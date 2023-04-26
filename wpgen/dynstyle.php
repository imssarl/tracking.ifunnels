<?php
session_start();
include 'previewvars.php';
$nameTemplate = $_REQUEST['nameTemplate'];
$headTitle = $_REQUEST['headTitle'];
$texttitlecolor = $_REQUEST['texttitlecolor'];
$titleimage = $_REQUEST['titleimage'];
$doc = $_REQUEST['doc'];
$gridPage = $_REQUEST['gridPage'];
$thirdColumn = $_REQUEST['thirdColumn'];
$menulayout = $_REQUEST['menulayout'];
$bgcolor = $_REQUEST['bgcolor'];
$bgimg = $_REQUEST['bgimg'];
$bgrepeat = $_REQUEST['bgrepeat'];
$itemcolor = $_REQUEST['itemcolor'];
$itemimage = $_REQUEST['itemimage'];
$itemrepeat = $_REQUEST['itemrepeat'];
$itemborder = $_REQUEST['itemborder'];
$bgmenucolor = $_REQUEST['bgmenucolor'];
$menuimage = $_REQUEST['menuimage'];
$menurepeat = $_REQUEST['menurepeat'];
$textcolor = $_REQUEST['textcolor'];
$textsize = $_REQUEST['textsize'];
$textfont = $_REQUEST['textfont'];
$linkcolor = $_REQUEST['linkcolor'];
$linkstyle = $_REQUEST['linkstyle'];
$linkhovercolor = $_REQUEST['linkhovercolor'];
$linkhoverstyle = $_REQUEST['linkhoverstyle'];
$headercolor = $_REQUEST['headercolor'];
$headerfontstyle = $_REQUEST['headerfontstyle'];
$menuheader = $_REQUEST['menuheader'];
$linkmenucolor = $_REQUEST['linkmenucolor'];
$headermousecolor = $_REQUEST['headermousecolor'];
$linkfootercolor = $_REQUEST['linkfootercolor'];
$copy = $_REQUEST['copy'];

/*function checkAndEcho($a, $b)
{
if ($a != null && $a != "" && trim($a) != "")
echo $a;
else
echo $b;
}

function checkAndEchoColor($a, $b)
{
if ($a != null && $a != "")
echo $a;
else
echo $b;
}
function checker($a, $b)
{
if ($a != null && $a != "" && trim($a) != "")
return $a;
else
return $b;
}*/
?>
/*
Theme Name: <?php echo $nameTemplate;?> <?echo date('d,M Y',time())."\n"?>
Theme URI: 
Description: WordPress Custom Theme Generator for WPThemeGenerator.com.
Version: 1.0
Author: WPThemeGenerator

*/

html>body #content {
	height: auto;
	min-height: 580px;
}

body{
	font-family: <?php checkAndEcho($textfont, $default_textfont);?>;
	font-size: 95%;
	line-height: 115%;
	background-color: <?php checkAndEchoColor($bgcolor, $default_bgcolor);?>;
	<?if (trim($bgimg) != "")?>
	background-image: url(<?php checkAndEcho($bgimg, $default_bgimg);?>);
	<?if (trim($bgimg) != "" && trim($bgrepeat) != "")?>
	background-repeat: <?php checkAndEcho($bgrepeat, $default_bgrepeat);?>;
	background-position: center top;
	text-align: center;
}

body,td,th {
	color: <?php checkAndEchoColor($textcolor, $default_textcolor);?>;
}

a, a:link {
	padding: 1px;
	color: <?php checkAndEchoColor($linkcolor, $default_linkcolor);?>;
	text-decoration: <?php checkAndEcho($linkstyle, $default_linkstyle);?>;
}

a:hover {
	text-decoration: <?php checkAndEcho($linkhoverstyle, $default_linkhoverstyle);?>;
	<?
	if ($linkhoverstyle != null && trim($linkhoverstyle) == 'backgroundcolor')
	{
		echo 'background-color: '; checkAndEchoColor($linkcolor, $default_linkcolor); echo ";";
		echo 'color: '; checkAndEchoColor($linkhovercolor, $default_linkhovercolor); echo ";";
	}
	else
	{
		echo 'color: '; checkAndEchoColor($linkhovercolor, $default_linkhovercolor); echo ";";
	}
	?>
	}

h1 {
	font-family: <?php checkAndEcho($headerfontstyle, $default_headerfontstyle);?>;
	font-weight: bold;
	font-size: 190%;
}


h2 {
	font-family: Arial;
	font-weight: bold;
	font-size: 150%;
}

h3 {
	font-family: <?php checkAndEcho($headerfontstyle, $default_headerfontstyle);?>;
	font-size: 130%;
}

h4 {
	font-size: 105%;
}

p {
	font-size: 80%;
	margin-bottom:1em;
}

strong {
	font-weight: bold;
}

em{
	font-style: italic;
}

code {
	font: 1.1em 'Courier New', Courier, Fixed;
}

acronym, abbr
{
	font-size: 0.9em;
	letter-spacing: .07em;
}

a img {
	border: none;
}

p {
	font-size: 80%;
	margin-bottom:1em;
}

#hd {
	text-align: center;
	padding-top: 24px;
	padding-bottom: 24px;}

#hd h1{
	font-size: 290%;
	color: <?php checkAndEchoColor($texttitlecolor, $default_texttitlecolor);?>;
	<?/*background-color: <?php checkAndEcho($bgcolor, $default_bgcolor);?>;*/
	?>

}

.item {
	padding: 10px;
	background-color: <?php checkAndEchoColor($itemcolor, $default_itemcolor);?>;
	<?if (trim($bgimg) != "")?>
	background-image: url(<?php checkAndEcho($itemimage, $default_itemimage);?>);
	<?if (trim($bgimg) != "" && trim($bgrepeat) != "")?>
	background-repeat: <?php checkAndEcho($itemrepeat, $default_itemrepeat);?>;
	text-align:left;
	border: 1pt solid <?php checkAndEcho($itemborder, $default_itemborder);?>;
	margin-bottom: 1em;
}

.item ul {
	list-style-type: disc;
	padding-left: 15px;
	margin-left: 10px;
	font-size: 80%;
}


.item ol{
	list-style-type: decimal;
	padding-left: 15px;
	margin-left: 10px;
	font-size: 80%;
}

.itemhead{
	padding-top: 5px;
	padding-bottom: 5px;
}

h3 a:link, h3 a:hover, h3 a:visited{
	color: <?php checkAndEchoColor($headercolor, $default_headercolor);?>;
}

.chronodata {
	display: inline;
	text-align: right;
	margin-left: 2em;
	font-size: 80%;
}


.itemhead h3{
	display: inline;
}


input{
	font-size: 80%;
}

.metadata{
	line-height: 190%;
	font-size: 75%;

}

.metadata a:link, .metadata a:hover, .metadata a:visited{
	color: <?php checkAndEchoColor($linkhovercolor, $default_linkhovercolor);?>;
}

.commentlist p {
	clear: both;
	font-size: 90%;
}

.commentlist li {
	padding: 2px;
	border-top: 1px solid #1A1A1A;
}


.commentmetadata {
	font-size: 80%;
	float: right;

}


#secondary, #third{
	background-color: <?php checkAndEchoColor($bgmenucolor, $default_bgmenucolor);?>;
	<?if (trim($menuimage) != "")?>
	background-image: url(<?php checkAndEcho($menuimage, $default_menuimage);?>);
	<?if (trim($menuimage) != "" && trim($menurepeat) != "")?>
	background-repeat: <?php checkAndEcho($menurepeat, $default_menurepeat);?>;
	text-align:left;
	padding: 0px;
	margin: 0px;
	border: 1pt solid #1A1A1A;
	}

#secondary h4, #third h4 {
	color: <?php checkAndEcho($menuheader, $default_menuheader);?>;
	font-family: Trebuchet MS, arial, sans-serif;
	margin-top: 5px;
	padding: 3px;

}

#secondary p, #third p {
		padding: 3px;
		font-size: 70%;
}

#third {
	margin-left: 9px;
}


.navigation {
	display: block;
	margin-top: 10px;
	margin-bottom: 10px;
	color: #FFFFCC;
}

.navigation a:link, .navigation a:hover, .navigation a:visited{
	color: #FFFFCC;
}

.alignright {
	float: right;

}

.alignleft {
	float: left

}

blockquote {
	margin: 15px 30px 0 10px;
	padding-left: 20px;
	border-left: 5px solid #ddd;
	}

blockquote cite {
	margin: 5px 0 0;
	display: block;
}


#menu {padding:0; border:0px solid #fff }
#menu ul {list-style:none; margin:0; padding:0; font-size:<?php checkAndEcho($textsize, $default_textsize);?>; }
#menu ul li { padding:0; margin:0; border-bottom: #ddd solid 1px; }
#menu ul li a { display:block; padding:4px 4px 4px 10px; text-decoration:none; color: <?php checkAndEcho($linkmenucolor, $default_linkmenucolor);?>; }
#menu ul li a:hover { color:#fff; background: <?php checkAndEcho($headermousecolor, $default_headermousecolor);?>; }
#menu ul li em { display:none; }
#menu ul li.sect { font-weight:bold; color:#fff; background:#89d; padding:2px 0; text-indent:2px; margin-top:2px;}
#menu ul li.first {margin-top:0;}

.yui-nav{
	margin-bottom: -1px;
}
.yui-navset .yui-nav a:hover {
	background-color: #e60;;
	color: <?php checkAndEcho($linkmenucolor, $default_linkmenucolor);?>;
}

.yui-navset .yui-nav li a {
	border-bottom: 0px;
	background-color: <?php checkAndEcho($bgmenucolor, $default_bgmenucolor);?>;
	<?if (trim($menuimage) != "")?>
	background-image: url(<?php checkAndEcho($menuimage, $default_menuimage);?>);
	<?if (trim($menuimage) != "" && trim($menurepeat) != "")?>
	background-repeat: <?php checkAndEcho($menurepeat, $default_menurepeat);?>;
	color: <?php checkAndEcho($linkmenucolor, $default_linkmenucolor);?>;
	padding: .3em .7em .3em .7em;
	text-decoration:none;
	font-size:<?php checkAndEcho($textsize, $default_textsize);?>;
}

.sidebarSearch{
	clear: both;
	margin-bottom: 5px;
	margin-left: 2px;
}


.categories, .linkcat, .pagenav {
	list-style: none;
	margin: 0;
	padding: 0;
}


#ft {
	text-align: center;
	margin-top: 10px;
	margin-bottom: 10px;
	color: <?php checkAndEcho($linkfootercolor, $default_linkfootercolor);?>;
}

#ft a:link, #ft a:hover, #ft a:visited{
	color: <?php checkAndEcho($linkfootercolor, $default_linkfootercolor);?>;
}