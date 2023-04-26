<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{module name='site1' action='head'}
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<script type="text/javascript" src="/skin/_js/moopop.js"></script>
</head>
<body style="padding:10px;">

<div>
	{foreach from=$arrThemes item=i}
	<a href="{img src=$i.preview w=282 h=231}" class="links">
	<img src="{img src=$i.preview w=132 h=81}" rel="<div style='border:2px solid #000000; background:#000;'><img src='{img src=$i.preview w=282 h=231}' /><center style='color:#FFF;'><b>{$i.title}</b></center></div>" 
	align="left" id="{$i.id}" style="margin:5px; border:2px solid #444; padding:2px;" class="preview {if $i.flg_prop} prop{/if}" />
	</a>
	{/foreach}
</div>
{literal}
<script type="text/javascript">
window.addEvent('domready',function(){
	var optTips = new Tips('.preview');
	$$('.preview').each(function(img){
		if( img.id == window.parent.$('theme').value ){
			img.setStyle('border-color','red');
		}
	});
	$$('.preview').addEvent('click',function(e){
		e && e.stop();
		window.parent.visual.setTheme(this);
		window.parent.multibox.close();
	});
	$$('.links').addEvent('click',function(e){e && e.stop(); return false;});
});
</script>
{/literal}

</body>
</html>