<?php
 	include_once("config/config.php");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="stylesheets/style1.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="stylesheets/login.css" />
<link type="text/css" rel="stylesheet" href="<?=SERVER_PATH?>stylesheets/style_new.css" />

<title>Creative Niche Manager</title>
</head>
 <?php
 if($_GET["process"]=="change_settings"){
 	$preparedstr='onload="javascript:adCustomization();"';
 }
 ?>
 
<body <?php echo $preparedstr;?>>
 <?/*?>
<!-- Header Start -->
<div id="header">
<div id="head_news"></div>
<div id="head_inside">
</div>
</div>
<!-- Header End -->
<div id="navigation">
<?php 
	include_once("menu.php");
?>
<div id="nav_flower"></div>
</div><?*/?>
 
    <div id="header-bg">
        <div id="header">
        <div class="col-full">
            <div id="logo" class="fl">
                <a href="/" title="Niche Marketing Platform"><img class="title" src="/skin/i/frontends/design/logo3.png" alt="Creative Niche Manager" /></a>                
            </div>
            <div style="clear:both;"></div>
        </div>
        </div>
		<div id="navigation">
			<?php 
				include_once("menu.php");
			?>
		</div>        
    </div>
