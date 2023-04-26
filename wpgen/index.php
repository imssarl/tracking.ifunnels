<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Wordpress Theme Generator</title>
<link rel="stylesheet" href="http://yui.yahooapis.com/2.2.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
  <link rel="stylesheet" type="text/css" href="colorPicker.css">
  <script src="lib/prototype.js" type="text/javascript"></script>
  <script src="scriptaculous/scriptaculous.js" type="text/javascript"></script>
  <script src="yahoo.color.js" type="text/javascript"></script>
  <script src="colorPicker.js" type="text/javascript"></script>
</head>
<style>
#hd h1 { font-size: 150%; color: #0099DD; }
#bd { min-height: 350px; }
fieldset { border: 2px solid #ccc; padding: .5em; margin: .5em 0; }
p { padding-bottom: .35em }
#configuration p { padding-bottom: .4em; clear: both; }
strong{	font-weight: bold; }
</style>
<script language="JavaScript">
<!--

function toggleConfig(){
	var widthblock ="100%";
	layer = document.getElementById('configuration');
	if(layer){
		if(layer.style.display == "none"){
			layer.style.display = "block";
			document.getElementById('previewwindow').style.width= widthblock;
		}else{
			layer.style.display = "none";
			document.getElementById('previewwindow').style.width= '100%';
		}
	}

}

function reflectOnPreview(data){

	//data.id;
	//alert(data.options[data.selectedIndex].value);
	//post to target frame
	//frame = document.getElementById('previewframe');
	//frame.src = "preview.php?";
	if(data.options){
		if(data.options[data.selectedIndex].value == "custom-doc"){
			var customdoc = prompt('What is your custom size?', '500px');
			//data.options[data.selectedIndex].value = 'custom-doc\" style=\"width: '+customdoc+'\"';
		}
		if(data.options[data.selectedIndex].value == "yoururl"){
			var url = prompt('What is the url?', 'http://');
			if(url)
				data.options[data.selectedIndex].value = url;
		}
	}

	document.grids.action = "preview.php";
	document.grids.submit();

}

function generateTheme()
{
	//create file
	document.grids.action = "writer.php#top";
	document.grids.submit();
}

function setCustomPicture(el)
 {
	document.grids.action = "preview.php";
	document.grids.submit();
	//preview.style.backgroundImage = 'file:///'+el.value+'';
}
-->
 </script>
</head>
<body>
<div id="doc3" class="yui-t7">

  <div id="hd">
    <h1>Instant Wordpress Theme Generator</h1>
  </div>
  <div id="bd">
    <div class="yui-g">
      <p>This online generator creates your own custom unique WordPress Theme. Change the colors, settings, layout, preview live, click "save" and download your unique Wordpress theme zip-file.</p>
      <p><a href="javascript:toggleConfig();"><img src="showhidegen.jpg"></a></p>

    </div>
    <div class="yui-gd">
      <div class="yui-g" id="configuration">
        <form id="grids" name="grids" target="previewframe" method="post" onsubmit="reflectOnPreview(true)" action="preview.php">
		  <fieldset><legend>Theme Configuration</legend>
		  <table cellspacing="200"><tr><td>
          <fieldset>
          <legend>Theme Basics:</legend>
		  <fieldset>
		  <legend>Template name + Title + color or Logo</legend>
          <p><input type="text" value="WPThemeGenerator" name="nameTemplate" onblur="reflectOnPreview(this)"></p>
          <input type="text" value="WPTHEMEGENERATOR.ORG" name="headTitle" onblur="reflectOnPreview(this)">
		<p><input type="text" size="6" id="texttitlecolor" name="texttitlecolor" value="#FFFFFF">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox1" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("texttitlecolor", { "swatch" : "colorbox1" });
			// ]]>
		</script></p>
				<p>or logo <input type="text" name="titleimage" onblur="reflectOnPreview(this)" value="http://"></p>

         </fieldset>
         <fieldset>
          <legend>Content Width:</legend>
          <select name="doc" onChange="reflectOnPreview(this)">
            <option value="doc" selected>750px</option>
            <option value="doc2">950px</option>
            <option value="doc3">100%</option>

            <option value="custom-doc">Custom</option>
          </select>
          </fieldset>
          <fieldset>
          <legend>Sidebar Location and Size:</legend>
          <select name="gridPage" onChange="reflectOnPreview(this)">
            <option value="yui-t7">No Sidebar</option>

            <option value="yui-t1" selected>Sidebar left 160px</option>
            <option value="yui-t2">Left - 180px</option>
            <option value="yui-t3">Left - 300px</option>
            <option value="yui-t4">Right - 180px</option>
            <option value="yui-t5">Right - 240px</option>
            <option value="yui-t6">Right - 300px</option>

          </select>
          </fieldset>
          <fieldset>
          <legend>Extra Column:</legend>
          <select name="thirdColumn" onChange="reflectOnPreview(this)">
            <option value="yui-g" selected>No Extra Column</option>
            <option value="yui-gc" >One Extra Column</option>

            <!--<option value="yui-gd">Extra 2/3 Column</option>-->
            </select>
          </fieldset>
          <fieldset>
          <legend>Menu Layout:</legend>
          <select name="menulayout" onChange="reflectOnPreview(this)">
          <option value="inline" selected>Inline menu - pages & category</option>
          <option value="titles">Original  - titles seperated</option>
          <option value="tabs">Tabs - top page tabs</option>
          <option value="tabsinline">Tabs and menu titles</option>
	  </select>
          </fieldset>
		  </fieldset>
		  </td><td>
		  <fieldset>
          <legend>Theme Colors</legend>
		  <table><tr><td>
          <fieldset>
          <legend>Theme Basic Colors</legend>

          <span>
          <p>Background:<br>
				<input type="text" size="6" id="bgcolor" name="bgcolor" value="#1A1A1A">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox2" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("bgcolor", { "swatch" : "colorbox2" });
			// ]]>
		</script>
            <select name="bgimg" onChange="reflectOnPreview(this)">
              <option value="/" selected>No background</option>
              <optgroup label="Selected">
              <option label="Overlay" value="images/bg_overlay.png">Overlay</option>

              <option label="Hearts" value="images/2hearts.gif">Hearts</option>
              <option label="Blackwood" value="images/blackwood.jpg">Blackwood</option>
              <option label="Your URL" value="yoururl">Your URL</option>
              </optgroup>
              <optgroup label="Special">
              <option label="Blue Art" value="images/blueart.jpg">Blue Art</option>
              <option label="Blue Square" value="images/bluesquare.gif">Brown Nature</option>

              <option label="Blue Black Mix" value="images/blueblackmix.jpg">Blue Black Mix</option>
              <option label="Blue Velvet" value="images/bluevelv.jpg">Blue Velvet</option>
              </optgroup>
              <optgroup label="Retro">
              <option label="Bluish" value="images/bluishtexture.jpg">Bluish</option>
              <option label="Bricks" value="images/bricks.jpg">Bricks</option>
              <option label="Brown Decor" value="images/browndeco.jpg">Brown Decor</option>
              <option label="Bug Eyes" value="images/bugeyes.jpg">Bug Eyes</option>
              <option label="Brown Swirl" value="images/brwnswirl.jpg">Brown Swirl</option>
              </optgroup>
              <optgroup label="Holiday">
              <option label="Cells" value="images/cells.jpg">Cells</option>
              <option label="Dirt Soil" value="images/dirtsoil.jpg">Dirt Soil</option>
              <option label="Faint Flower" value="images/faintcolorflower.jpg">Faint Flower</option>
              <option label="Sand in zand" value="images/fallleaf.jpg">Sand</option>
              <option label="Palmtree" value="images/fallleaves.jpg">Palmtree</option>
              </optgroup>
            </select>
            <select  name="bgrepeat" onChange="reflectOnPreview(this)">
              <option label="Repeat" value="repeat" selected>Repeat</option>
              <option label="Horizontal Repeat" value="repeat-x">Horizontal Repeat</option>
              <option label="Vertical Repeat" value="repeat-y">Vertical Repeat</option>

              <option label="No Repeat" value="no-repeat">No Repeat</option>
            </select>
          </p>
          </span>
          <span>
          <p>Background Item:<br>
				<input type="text" size="6" id="itemcolor" name="itemcolor" value="#1A1A1A">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox3" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("itemcolor", { "swatch" : "colorbox3" });
			// ]]>
		</script>
            <select name="itemimage" onChange="reflectOnPreview(this)">

              <option value="/">No background</option>
              <option label="Overlay" value="images/bg_overlay.png" selected>Overlay</option>
              <option label="Vertical Gradient" value="images/bg_gradient.png">Vertical Gradient</option>
              <option label="Your URL" value="yoururl">Your URL</option>
            </select>
			 <select  name="itemrepeat" onChange="reflectOnPreview(this)">
              <option label="Repeat" value="repeat" selected>Repeat</option>

              <option label="Horizontal Repeat" value="repeat-x">Horizontal Repeat</option>
              <option label="Vertical Repeat" value="repeat-y">Vertical Repeat</option>
              <option label="No Repeat" value="no-repeat">No Repeat</option>
            </select>
          </p>
          <span>
          <p>Border:<br>
			<input type="text" size="6" id="itemborder" name="itemborder" value="#1A1A1A">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox4" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("itemborder", { "swatch" : "colorbox4" });
			// ]]>
		</script>
			</p>
          </span>
          <span>
          <p>Background Menu:<br>
				<input type="text" size="6" id="bgmenucolor" name="bgmenucolor" value="#1A1A1A">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox5" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("bgmenucolor", { "swatch" : "colorbox5" });
			// ]]>
		</script>
            <select name="menuimage" onChange="reflectOnPreview(this)">
              <option value="/">No background</option>

              <option label="Overlay" value="images/bg_overlay.png">Overlay</option>
              <option label="Vertical Gradient" value="images/bg_gradient.png" selected>Vertical Gradient</option>
              <option label="Your URL" value="yoururl">Your URL</option>
            </select>
			 <select  name="menurepeat" onChange="reflectOnPreview(this)">
              <option label="Repeat" value="repeat" selected>Repeat</option>
              <option label="Horizontal Repeat" value="repeat-x" selected>Horizontal Repeat</option>

              <option label="Vertical Repeat" value="repeat-y">Vertical Repeat</option>
              <option label="No Repeat" value="no-repeat">No Repeat</option>
            </select>
          </p>
          </span>
          <span>
          <p>Text:<br>
			<input type="text" size="6" id="textcolor" name="textcolor" value="#FFFFFF">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox6" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("textcolor", { "swatch" : "colorbox6" });
			// ]]>
		</script>
            <select name="textsize" onChange="reflectOnPreview(this)">

              <option value="85%">Smaller</option>
              <option value="95%" selected>Normal</option>
              <option value="105%" >Bigger</option>
              <option value="115%" >Biggest</option>
            </select>
            <select name="textfont" onChange="reflectOnPreview(this)">
              <option value="monospace">Monospace</option>

              <option value="georgia">Georgia</option>
              <option value="verdana" selected>Verdana</option>
              <option value="times">Times</option>
            </select>

          </p>
          </span>
          <span>

          <p>Footer:<br>
				<input type="text" size="6" id="linkfootercolor" name="linkfootercolor" value="#FFFFCC">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox7" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("linkfootercolor", { "swatch" : "colorbox7" });
			// ]]>
		</script>
          </p>

          </fieldset></td><td>
          <fieldset>
          <legend>Theme Extra colors</legend>
		  <span>

          <p>Link color:<br>
				<input type="text" size="6" id="linkcolor" name="linkcolor" value="#FFFFCC">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox8" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("linkcolor", { "swatch" : "colorbox8" });
			// ]]>
		</script>
            <select name="linkstyle" onChange="reflectOnPreview(this)">
              <option value="none">none</option>
              <option value="underline" selected>underline</option>
              <!--<option value="backgroundcolor" selected>underline</option>-->
            </select>

            <!-- hover -->
          <p>Link Mouseover:<br>
				<input type="text" size="6" id="linkhovercolor" name="linkhovercolor" value="#FFFFCC">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox9" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("linkhovercolor", { "swatch" : "colorbox9" });
			// ]]>
		</script>
            <select name="linkhoverstyle" onChange="reflectOnPreview(this)">
              <option value="none" selected>none</option>
              <option value="underline">underline</option>
              <option value="backgroundcolor">backgroundcolor</option>

            </select>
          </p>
          </span>
          <span>
          <p>Title Post:<br>
				<input type="text" size="6" id="headercolor" name="headercolor" value="#0099DD">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox10" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("headercolor", { "swatch" : "colorbox10" });
			// ]]>
		</script>
            <select name="headerfontstyle" onChange="reflectOnPreview(this)">
              <option value="monospace">Monospace</option>

              <option value="georgia" selected>Georgia</option>
              <option value="verdana">Verdana</option>
              <option value="times">Times</option>
            </select>
          </span>
          </p>
          <span>
          <p>Menu Header:<br>
				<input type="text" size="6" id="menuheader" name="menuheader" value="#859E6C">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox11" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("menuheader", { "swatch" : "colorbox11" });
			// ]]>
		</script>
          </p>
          </span>
          <span>
          <p>Menu Categories:<br>
				<input type="text" size="6" id="linkmenucolor" name="linkmenucolor" value="#FFFFFF">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox12" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("linkmenucolor", { "swatch" : "colorbox12" });
			// ]]>
		</script>
            <!-- menulinkhovercolor -->
          </p>

          </span>
          <span>
          <p>Menu Mouseover:<br>
				<input type="text" size="6" id="headermousecolor" name="headermousecolor" value="#0099DD">
		<button style="width: 1.6em; height: 1.6em; border: 1px outset #666;" id="colorbox13" class="colorbox">&gt;</button>
		<script type="text/javascript">
			// <![CDATA[
			new Control.ColorPicker("headermousecolor", { "swatch" : "colorbox13" });
			// ]]>
		</script>
            <!-- menulinkhovercolor -->
          </p>
          </span>
          </span>
		  </fieldset></td></tr></table>
		  <p style="font-size: 80%;">tip #1: make item and menu transparant by entering a space (" ") in the color field.<br/>
									 tip #2: choose background image 'overlay' to make your colorscheme less hard, and more stylish.</p>
          </fieldset></td></tr></table>
		  </fieldset>
          <!--fieldset>
          <legend>Distribution <a href="http://creativecommons.org/license/?lang=en" target="_blank">[?]</a></legend>

              <select name="copy" onChange="reflectOnPreview(this)">
              <option value="/by/3.0/">Creative Commons</option>
              <option value="/by-nc/3.0/">Creative Commons - NC</option>
              <option value="copyright" selected>Copyright 2007</option>
            </select>
          </fieldset-->
          <input type="image" src="preview.jpg" value="  Preview  ">
          <input type="image" src="save.jpg" onclick="generateTheme();return false;" value="  Save  ">
        </form>
      </div>
    </div>
      <div class="yui-g" id="previewwindow">
        <iframe src="preview.php" height="750" width="100%" noborder frameborder=0 id="previewframe" name="previewframe"></iframe>

      </div>
  </div>
  <div id="ft" style="font-size: 80%; text-align: right">Wordpress Theme Generator v1.0 (<a href="preview.php">preview</a> layout) developed by <a href="http://www.wpthemegenerator.org">JP Schoeffel</a>.</div>
</div>
</body>
</html>