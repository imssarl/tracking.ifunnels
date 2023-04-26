<?php
session_start();
$dirname = "./file/" . $_REQUEST["PHPSESSID"];
function deleteDirectory($dirname,$only_empty=false) {
    if (!is_dir($dirname))
        return false;
    $dscan = array(realpath($dirname));
    $darr = array();
    while (!empty($dscan)) {
        $dcur = array_pop($dscan);
        $darr[] = $dcur;
        if ($d=opendir($dcur)) {
            while ($f=readdir($d)) {
                if ($f=='.' || $f=='..')
                    continue;
                $f=$dcur.'/'.$f;
                if (is_dir($f))
                    $dscan[] = $f;
                else
                    unlink($f);
            }
            closedir($d);
        }
    }
    $i_until = ($only_empty)? 1 : 0;
    for ($i=count($darr)-1; $i>=$i_until; $i--) {
        //echo "\nDeleting '".$darr[$i]."' ... ";
        if (rmdir($darr[$i]))
        {
		//echo "ok";
		}
        else
		{
        //    echo "FAIL";
		}
    }
    return (($only_empty)? (count(scandir)<=2) : (!is_dir($dirname)));
}
if (file_exists ($dirname))
{
deleteDirectory($dirname);
}
include 'previewvars.php';
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

function checkAndEcho($a, $b)
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
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head profile="http://gmpg.org/xfn/11">
<title>Wordpress Theme Preview</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="generator" content="WordPress 2.1.3" /> <!-- leave this for stats please -->
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.2.0/build/reset-fonts-grids/reset-fonts-grids.css"/>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.2.2/build/tabview/assets/tabview.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.2.2/build/tabview/assets/border_tabs.css">
<style type="text/css" media="screen">
/*
Theme Name: WPThemeGenerator 15,May 2007
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
</style>
<base href="http://techvista.net/wpgen/preview.php" />
</head>
<body>
<div id="<?php checkAndEcho($doc, $default_doc);?>" class="<?php checkAndEcho($gridPage, $default_gridPage);?>">

  <div id="hd">
    <h1><?php 
	if ($titleimage != null && trim($titleimage) != "" && $titleimage != $default_titleimage)
	{	
		echo $timage1 . $titleimage . $timage2 . checker($headTitle, $default_headTitle) . $timage3;
	}
	else
	checkAndEcho($headTitle, $default_headTitle);?></h1>
  </div>
  <div id="bd" class="yui-navset">
	 <?php
	 //check and print top menu tabs
	 $menulayout = checker($menulayout, $default_menulayout);
	 if ($menulayout == "tabsinline" || $menulayout == "tabs")
	 echo $pagestabs;
	 ?>
    <div id="yui-main">
	<div class="yui-b" ><div class="<? checkAndEcho($thirdColumn, $default_thirdColumn) ?>">
		<?php
//check extra column and add
$thirdColumn = checker($thirdColumn, $default_thirdColumn);
if ($thirdColumn != "yui-g")
echo '<div class="yui-u first">';
?>
				<!-- item -->
				<div class="item entry" id="post-1">

				          <div class="itemhead">
				            <h3><a href="preview.php?comment=1&" rel="bookmark">Elton John</a></h3>
				            <div class="chronodata">15 May, 2007</div>
				          </div>
						  <div class="storycontent">
							<p>In 1974 a collaboration with John Lennon took place, resulting in Elton John covering The Beatles' "Lucy in the Sky with Diamonds" and Lennon's "One Day at a Time", and in return Elton John and band being featured on Lennon's "Whatever Gets You Thru The Night". In what would be Lennon's last live performance, the pair performed these two number 1 hits along with the Beatles classic "I Saw Her Standing There" at Madison Square Garden. Lennon made the rare stage appearance to keep the promise he made that he would appear on stage with Elton if "Whatever Gets You Thru The Night" became a number 1 single.</p>
							<p><img src="http://upload.wikimedia.org/wikipedia/en/thumb/1/16/Elton_John_-_Captain_Fantastic_and_the_Brown_Dirt_Cowboy.jpg/200px-Elton_John_-_Captain_Fantastic_and_the_Brown_Dirt_Cowboy.jpg"></p>
							<br>
							<p>He played on 8 September 2007 in Vevey, a small village situated on Lake Geneva, Switzerland. Of this he said "The market square in Vevey is one of the most beautiful and magic places in Europe. Since visiting the area by chance in Summer 2003, I have always wanted to sing there. My friend Shania Twain who lives there, convinced me to set up that gig". </p>

							<p><strong>Update 28 Apr:</strong> On populair demand also support for a 3rd column with Widget support. And with serveral installed themes,  theme version is now date & time of generation.</p>
						  </div>
				          <small class="metadata">
							 <span class="category">Filed under: <a href="#" title="View all posts in #" rel="category tag">Category</a> </span> | <a href="preview.php?comment=1&" title="Comment on WordPress Generator">Comments (2)</a></small>

				 </div>
<!-- end item -->

<!-- item -->
				<div class="item entry" id="post-2">
				          <div class="itemhead">
				            <h3><a href="#" rel="bookmark">Baraka</a></h3>
				            <div class="chronodata">13 May, 2007</div>
				          </div>

						  <div class="storycontent">
							<p><img src="http://upload.wikimedia.org/wikipedia/en/thumb/c/cb/Baraka_%28Film%29.jpg/200px-Baraka_%28Film%29.jpg"></p>
							
							<p>Often compared to Koyaanisqatsi, Baraka's subject matter has some similarities—including footage of various landscapes, churches, ruins, religious ceremonies, and cities thrumming with life, filmed using time-lapse photography in order to capture the great pulse of humanity as it flocks and swarms in daily activity. The film also features a number of long tracking shots through various settings, including one through former concentration camps at Auschwitz (in Nazi-occupied Poland) and Tuol Sleng (in Cambodia) turned into museums honoring their victims: over photos of the people involved, past skulls stacked in a room, to a spread of bones. In addition to making comparisons between natural and technological phenomena, such as in Koyaanisqatsi, Baraka searches for a universal cultural perspective: for instance, following a shot of an elaborate tattoo on a bathing Japanese yakuza mobster with one of Native Australian tribal paint.</p>

							<p>
						  </div>
				          <small class="metadata">
							 <span class="category">Filed under: <a href="#" title="View all posts in #" rel="category tag">Category</a> </span> | <a href="preview.php?comment=1&" title="Comment on WordPress Generator">Comments (0)</a></small>
				 </div>
<!-- end item -->

<!-- item -->
				

<!-- end item -->

		<div class="navigation" style="height: 190%">
			<div class="alignleft">&laquo; Previous Entries</div>
			<div class="alignright">Next Entries &raquo;</div>
			<p> </p>
		</div>
<!-- 2nd sidebar example: <div class="yui-u"></div>-->
<?php
//check extra column and add
$thirdColumn = checker($thirdColumn, $default_thirdColumn);
if ($thirdColumn != "yui-g")
echo $extrasidebar;'<div class="yui-u first">';
?>
<!-- end 2nd sidebar -->
	</div>

	</div>
	</div>
	<div class="yui-b" id="secondary">
<!-- menu -->
	 <div id="menu">
	<?php
		//check and print pages label and pages
	 $menulayout = checker($menulayout, $default_menulayout);
	 if ($menulayout == "titles")
	 {
	 echo $pageslabel;
	 }
	 if ($menulayout == "inline" || $menulayout == "titles")
	 {
	 echo $pagesmenu;
	 }
	?>
	<?php
		//check and print categories label
	 $menulayout = checker($menulayout, $default_menulayout);
	 if ($menulayout == "titles" || $menulayout == "tabsinline")
	 {
	 echo $categorieslabel;
	 }
	?>

		<ul>
				<li><a href="#" titles="View all posts filed under Movies">Movies</a>
				</li>
					<li><a href="#" title="View all posts filed under Music">Music</a>
				</li>

					<li><a href="#" title="View all posts filed under News">News</a>
				</li>
		</ul>
      <h4>WP Theme Generator</h4>
	  <p>Hi, my name is WP ThemeGenerator This site is about my daily life being a the theme generator. Thanks for stopping by!</p>
	  <p><a href="#" title="Syndicate this site using RSS"><abbr title="Really Simple Syndication"><img src="http://www.feedburner.com/fb/images/pub/feed-icon16x16.png"></abbr></a>
		</p>

      <div id="sidebarSearch">
				<div class="BlogSearch">
					<form id="searchform" method="get" action="preview.php">
					<h4>Quick search:</h4>
					<input type="text" name="s" id="s" value="" size="15" /><input type="submit" id="searchsubmit" name="search" value="Search" />

					</form>
				</div>
	  </div>

<!-- links -->
		<div class="sb-links">
		<h4>Links</h4>
		<ul>
			<li><a href="http://www.nichesinabox.com">Niches-In-A-Box</a></li>
			<li><a href="http://www.ethiccash.com">Internet Marketing Club</a></li>
			<li><a href="http://www.completealbumlyrics.com/">Album Lyrics</a></li>

			<li><a href="http://www.completealbumlyrics.com/lyric/131311/Rihanna+-+Umbrella.html">Rihanna - Umbrella</a></li>
			<li><a href="http://www.completealbumlyrics.com/lyric/131228/Gym+Class+Heroes+-+Cupids+Chokehold.html">Cupids Chokehold</a></li>
		</ul>
	</li>
	</div>
<!-- links end -->
<!-- end menu options-->
  		</div>
	</div>

  </div>
  <div id="ft">
    <div style="font-size: 70%;">Proudly powered by <a href="http://wordpress.org/">WordPress</a>. Theme developed with <a href="http://www.wpthemegenerator.org">WordPress Theme Generator</a>.<br />
	      Copyright &copy; 2007 WP Generator Blog & JP Schoeffel. All rights reserved.
	</div>
  </div>

</div>
</body>
</html>