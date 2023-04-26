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
<table style="width:100%;">
<thead>
<tr>
	<th width="20">S.No</th>
	<th>Title</th>	
	<th width="20"><input type="checkbox" id="select_all" /></th>
</tr>
</thead>
<tbody>
{foreach from=$arrList key='k' item='v'}
<tr {if $k%2=='0'} class="matros"{/if}>
	<td align="center">{$v.list_id}</td>
	<td>{$v.list_title}</td>
	<td align="center" class="option">
	<input class="check" type="checkbox" value="{$v.list_id}">
	</td>
</tr>
{/foreach}
</tbody>
</table>
<br/>
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>
<div align="center"><p><input type="button" value="Choose" id="choose"></p></div>
{literal}
<script type="text/javascript">
window.addEvent('domready', function(){
	$('select_all').addEvent('click', function(){
		$$('.check').each(function(el){
			el.checked=$('select_all').checked;
		});
	});
	$('choose').addEvent('click', function(){
		var arr=new Array();
		var i=0;
		$$('.check').each(function(v){
			if(v.checked){
				arr[i]=v.value;
				i++;
			}
		});
		var strJson = JSON.encode(arr);
		var req = new Request({
				url: "",
				onComplete:function(r){
					var arrRes = JSON.decode(r);
					window.parent.setKeyword(arrRes);
					window.parent.multibox.close();
				},
			}).get({ 'keyword':1, 'jsonIds':strJson });	
	});
});
</script>
{/literal}
</body>
</html>