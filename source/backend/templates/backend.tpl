{if $arrNest.name&&$arrNest.flg_tpl>0}{module name=$arrNest.name action=$arrNest.action mcur=1} {*pop-up backend actions with no space at start please*}
{elseif $arrPrm.uniq} {*cur mod actions through template*}
	{include file="b_`$arrPrm.action`.tpl"}
{else}
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{if $arrCurReverse}/{foreach from=$arrCurReverse item='node'}{$node.title}/{/foreach} - {/if}{$smarty.const.PROJECT_DOMAIN} Backend Panel</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	{*if $arrHtmlRedirect}<meta http-equiv="Refresh" content="{$arrHtmlRedirect.sec}; url={$arrHtmlRedirect.url}">{/if*}
	<link rel="stylesheet" type="text/css" href="/skin/_css/backend.css" />
	<link rel="stylesheet" type="text/css" href="/skin/_css/tips.css" />
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<script type="text/javascript" src="/skin/_js/xlib.js"></script>
	<script type="text/javascript" src="/skin/_js/form_checker.js"></script>
	{*r.alert*}
	<link rel="stylesheet" type="text/css" href="/skin/_js/roar/roar.css" />
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<style>{literal}body {font-size: 13px;}{/literal}</style>
	<script type="text/javascript" src="/skin/_js/roar/roar.js"></script>
	{*html editor*}
	<script type="text/javascript" src="/skin/_js/fckeditor/fckeditor.js"></script>
	{*calendar*}
	<link rel="stylesheet" type="text/css" media="all" href="/skin/_js/jscalendar/calendar-blue2.css" />
	<script type="text/javascript" src="/skin/_js/jscalendar/calendar.js"></script>
	<script type="text/javascript" src="/skin/_js/jscalendar/lang/calendar-en.js"></script>
	<script type="text/javascript" src="/skin/_js/jscalendar/calendar-setup.js"></script>
	<script type="text/javascript">
	{literal}
		img_preload([
			'/skin/i/backend/paging/sr_first.gif',
			'/skin/i/backend/paging/sr_first_gray.gif',
			'/skin/i/backend/paging/sr_prev.gif',
			'/skin/i/backend/paging/sr_prev_gray.gif',
			'/skin/i/backend/paging/sr_next.gif',
			'/skin/i/backend/paging/sr_next_gray.gif',
			'/skin/i/backend/paging/sr_last.gif',
			'/skin/i/backend/paging/sr_last_gray.gif',
			'/skin/i/backend/down.gif',
			'/skin/i/backend/down_off.gif',
			'/skin/i/backend/up.gif',
			'/skin/i/backend/up_off.gif'
		]);
		var r,tips;
		window.addEvent('domready',function(){
			if (window.ie) new ie_hover_tabletr_fix();
			tips=new Tips('.tooltip');
			r=new Roar();
		});
	{/literal}
	</script>
</head>
{module name='configuration' action='just_install_me'}
<body>
	<div id="container">
		<div class="top">
			{if $arrUser.id}
			<ul>
				<li><a href="{url name='accounts' action='logoff'}">exit</a></li>
				<li>signed in as {if in_array('Super Admin',$arrUser.groups)}<a href="{url name='accounts' action='set_profile'}?id={$arrUser.id}">{/if}<b>{$arrUser.nickname}</b></a></li>
				{if $arrUser.right_parsed.backend}
					<li>current managed frontend
					{foreach from=$arrF item='v'}
					<a href="{Core_Module_Router::$uriFull}?new_frontend={$v.sys_name}" title="">{if $admin_current_frontend==$v.sys_name}<b>{$v.sys_name}</b>{else}{$v.sys_name}{/if}</a>
					{/foreach}
					</li>
				{/if}
			</ul>
			{/if}
		</div>
		<div id="leftmenu">{include file="inc_amenu.tpl"}</div>
		<div id="content">
		{if $arrUser.id}
			{if $arrNest.action&&$arrNest.action==$arrPrm.action} {*cur mod actions*}
				<h1>{$arrPrm.title}</h1>
				{include file="b_`$arrPrm.action`.tpl"}
			{elseif $arrNest.name} {*nested mod actions*}
				{module name=$arrNest.name action=$arrNest.action mcur=1}
			{else}
				<h1>{if LANG=='ru'}Выберите один из разделов меню{else}Select an item from left menu{/if}</h1>
			{/if}
		{else}
			{module name='accounts' action='adm_login'}
		{/if}
		</div>
	</div>
	<div id="footer_container">{$smarty.const.PROJECT_DOMAIN} &copy; all rights reserved.</div>
</body>
</html>
{/if}