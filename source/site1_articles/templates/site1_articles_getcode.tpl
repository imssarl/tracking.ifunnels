<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{module name='site1' action='head'}
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<script type="text/javascript" src="/skin/_js/xlib.js"></script>
	<link rel="stylesheet" type="text/css" href="/skin/_js/roar/roar.css" />
	<script type="text/javascript" src="/skin/_js/roar/roar.js"></script>
	<script type="text/javascript">
		var r=new Roar();
		img_preload(['/skin/_js/roar/roar.png']);
	</script>

</head>
<body style="padding:10px;">
<div id="code" style="display:block;">
		<textarea class="clipboard-text clipboard-id-1" style="width:500px; height:300px;">{$arrItem.code}</textarea>
		<br/><br/>
		<center><a class="clipboard-click clipboard-id-1" href="#">Copy to clipboard</a></center>
		<br/> 
		<div id="clipboard_content"></div>	
</div>
<script language="javascript" src="/skin/_js/clipboard/clipboard.js"></script>
{literal}
<script>
var _clipboard = {};
window.addEvent("domready", function(){
	_clipboard=new Clipboard($$('.clipboard-click'));
});
</script>
{/literal}
</body>
</html>