<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{module name='site1' action='head'}
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<script type="text/javascript" src="/skin/_js/xlib.js"></script>
	<script type="text/javascript" src="/skin/_js/moopop.js"></script>
	{*r.alert*}
	<link rel="stylesheet" type="text/css" href="/skin/_js/roar/roar.css" />
	<script type="text/javascript" src="/skin/_js/roar/roar.js"></script>
	<script type="text/javascript">
	{literal}
		var r=new Roar();
		img_preload(['/skin/_js/roar/roar.png']);
	{/literal}
	</script>
</head>
<body style="padding:5px;">
<form id="login" method="POST" action="{$arrBlog.url}wp-login.php">
<input type="hidden" name="log" value="{$arrBlog.dashboad_username}" />
<input type="hidden" name="pwd" value="{$arrBlog.dashboad_password}" />
</form>
{literal}
<script type="text/javascript">
window.addEvent('domready',function(){ 
	parent.window.initWidgetEvents({/literal}'{$arrBlog.url}'{literal});
	$('login').submit();
})
</script>
{/literal}
</body>
</html>