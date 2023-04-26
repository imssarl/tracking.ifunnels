<?php
session_start();
include 'previewvars.php';
$dirname = "./file/" . $_REQUEST["PHPSESSID"];
$basedir = "./base";

mkdir($dirname, 0777);

function dircopy($srcdir, $dstdir, $verbose = true) {
  $num = 0;
  if(!is_dir($dstdir)) mkdir($dstdir);
  if($curdir = opendir($srcdir)) {
    while($file = readdir($curdir)) {
      if($file != '.' && $file != '..') {
        $srcfile = $srcdir . '\\' . $file;
        $dstfile = $dstdir . '\\' . $file;
        if(is_file($srcfile)) {
          if(is_file($dstfile)) $ow = filemtime($srcfile) - filemtime($dstfile); else $ow = 1;
          if($ow > 0) {
            if($verbose) echo "Copying '$srcfile' to '$dstfile'...";
            if(copy($srcfile, $dstfile)) {
              touch($dstfile, filemtime($srcfile)); $num++;
              if($verbose) echo "OK\n";
            }
            else echo "Error: File '$srcfile' could not be copied!\n";
          }                  
        }
        else if(is_dir($srcfile)) {
          $num += dircopy($srcfile, $dstfile, $verbose);
        }
      }
    }
    closedir($curdir);
  }
  return $num;
}


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

//dircopy($basedir, $dirname, $verbose = false);

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

// your new data + newline
$categories = '			<h4>Categories</h4>
';
$pages = '			<h4>Pages</h4>
';
// the filepath
$file = $basedir . '/sidebar.php';

// the old data as array
$old_lines = file($file);

// add new line to beginning of array
if ($menulayout == "titles" || $menulayout == "tabsinline")
{
$old_lines[4] = $categories;
}
if ($menulayout == "titles")
{
$old_lines[6] = $pages;
}
if ($menulayout == "tabs")
{
$old_lines[5] = '';
}

// make string out of array
$new_content = join('',$old_lines);
$fp = fopen($dirname . "/sidebar.php",'w');
// write string to file
$write = fwrite($fp, $new_content);
fclose($fp);

$tabstop = '	<ul class="yui-nav" style="text-align:right"><?php wp_list_pages("title_li="); ?></ul>
';

// the filepath
$file = $basedir . '/index.php';

// the old data as array
$old_lines = file($file);

// add new line to beginning of array
if ($menulayout == "tabs" || $menulayout == "tabsinline")
{
$old_lines[2] = $tabstop;
}
if ($thirdColumn != $default_thirdColumn)
{
$old_lines[4] = rtrim($old_lines[4]) . '<div class="yui-u first">
';
$old_lines[41] = "</div><!-- end yiu-u --><div class=\"yui-u\" id=\"third\"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('rightsidebar') ) : ?><h4>Extra Column</h4><p>You can fill this column by editing the index.php theme file. Or by Widget support.</p><?php endif; ?></div>
";
}
// make string out of array
$new_content = join('',$old_lines);
$fp = fopen($dirname . "/index.php",'w');
// write string to file
$write = fwrite($fp, $new_content);
fclose($fp);

// the filepath
$file = $basedir . '/header.php';

// the old data as array
$old_lines = file($file);

// add new line to beginning of array
if ($titleimage != null && trim($titleimage) != "" && $titleimage != $default_titleimage)
{
$old_lines[20] = "    <h1>" .  $timage1 . $titleimage . $timage2 . checker($headTitle, $default_headTitle) . $timage3 . "</h1>";
}
//if($thirdColumn != $default_thirdColumn)
{
$old_lines[17] = "<div id=\"" . checker($doc, $default_doc) . "\" class=\"" . checker($gridPage, $default_gridPage) . "\">
";
}
// make string out of array
$new_content = join('',$old_lines);
$fp = fopen($dirname . "/header.php",'w');
// write string to file
$write = fwrite($fp, $new_content);
fclose($fp);

$styledata = get_include_contents('./dynstyle.php');

function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}
$fp = fopen($dirname . "/style.css",'w');
// write string to file
$write = fwrite($fp, $styledata);
fclose($fp);

include 'zipper.php';

$zipfile = new zipfile();  
$_zipName = (!empty($nameTemplate))?$nameTemplate:'wpgen';
// add the subdirectory ... important!
$zipfile -> add_dir("{$_zipName}/");
$zipfile -> add_dir("{$_zipName}/images/");

// add the binary data stored in the string 'filedata'
$filedata = file_get_contents ($basedir . "/comments.php");
$zipfile -> add_file($filedata, "{$_zipName}/comments.php");  

$filedata = file_get_contents ($basedir . "/footer.php");
$zipfile -> add_file($filedata, "{$_zipName}/footer.php");  

$filedata = file_get_contents ($basedir . "/functions.php");
$zipfile -> add_file($filedata, "{$_zipName}/functions.php");  

$filedata = file_get_contents ($dirname . "/header.php");
$zipfile -> add_file($filedata, "{$_zipName}/header.php");  

$filedata = file_get_contents ($dirname . "/index.php");
$zipfile -> add_file($filedata, "{$_zipName}/index.php");  

$filedata = file_get_contents ($basedir . "/screenshot.png");
$zipfile -> add_file($filedata, "{$_zipName}/screenshot.png");  

$filedata = file_get_contents ($dirname . "/sidebar.php");
$zipfile -> add_file($filedata, "{$_zipName}/sidebar.php");  

$filedata = file_get_contents ($dirname . "/style.css");
$zipfile -> add_file($filedata, "{$_zipName}/style.css");   

$filedata = file_get_contents ($basedir . "/images/bg_overlay.png");
$zipfile -> add_file($filedata, "{$_zipName}/images/bg_overlay.png");   

$filedata = file_get_contents ($basedir . "/images/bg_gradient.png");
$zipfile -> add_file($filedata, "{$_zipName}/images/bg_gradient.png"); 

if (trim($bgimg) != $default_bgimg)
{
$filedata = file_get_contents ($bgimg);
$zipfile -> add_file($filedata, "{$_zipName}/" . $bgimg); 
}

// the next three lines force an immediate download of the zip file:
//header("Content-type: application/octet-stream");  
//header("Content-disposition: attachment; filename=test.zip");  
//echo $zipfile -> file();  


// OR instead of doing that, you can write out the file to the loca disk like this:
$timestr = time();
$filename = $dirname . "/{$_zipName}_" . $timestr . ".zip";
$fd = fopen ($filename, "wb");
$out = fwrite ($fd, $zipfile -> file());
fclose ($fd);
// then offer it to the user to download:
?>
<script src="../skin/_js/mootools.js"></script>
<script src="../skin/_js/mootools_more.js"></script>
<a href="<?echo $dirname;?>/<?echo $_zipName?>_<?echo $timestr?>.zip">Click here to download the new zip file.</a><br/>
<a href="#" rel="<?echo $_SERVER['DOCUMENT_ROOT']?>/wpgen/file/<?echo $_zipName?>_<?echo $timestr?>.zip" rev="<?echo $_zipName?>_<?echo $timestr?>.zip" id="upload-theme-bf">Save to Blog Fusion.</a><br/>
<script type="text/javascript">
window.addEvent('domready', function(){
	$('upload-theme-bf').addEvent('click', function(e){
		e && e.stop();
//		$('zip_name').value=$('upload-theme-bf').rev;
//		$('zip_tmp_name').value=$('upload-theme-bf').rel;
//		$('zip_size').value=200000;
//		$('save-form').submit();
		var r = new Request({  url:"/blog-fusion/themes/", method:'post', onSuccess: function(response){
			if( response.test('Uploaded successfully') ){
				alert('Uploaded successfully.');
			} else if( response.test('This theme is already exist') ) {
				alert('This theme is already exist.');
			} else if( response.test('Invalid Theme') ) {
				alert('Invalid Theme.');
			} else if( response.test('Invalid zip file') ) {
				alert('Invalid zip file.');
			} else if( response.test('Invalid file.Please upload only zip file') ) {
				alert('Invalid file.Please upload only zip file.');
			} else if( response.test('Uploaded file size is more than 5MB.Please upload below 5MB') ) {
				alert('Uploaded file size is more than 5MB.Please upload below 5MB.');
			}
		}}).post({ 'zip[name]': $('upload-theme-bf').rev, 'zip[tmp_name]': $('upload-theme-bf').rel,'size':200000,'error':0,'type':'application/zip' });	
	});		
	
});
</script>
<!--<form action="/blog-fusion/themes/" style="display:block;" method="POST" id="save-form" enctype="multipart/form-data">
<input type="hidden" name="zip[name]" id="zip_name" >
<input type="hidden" name="zip[tmp_name]" id="zip_tmp_name" >
<input type="hidden" name="zip[size]" id="zip_size" >
<input type="hidden" name="zip[type]" value="application/zip" >
<input type="hidden" name="zip[error]" value="0"  >
</form>-->

<?php
/*get the absolute dirname and offer it to automatically upload to blog fusion system*/
 $getdir=explode("/",$dirname);
?>