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

<table width="100%">
	<tr>
		<th>Blog{if count($arrList)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_blogfusion' action='manage' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_blogfusion' action='manage' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Category{if count($arrList)>1}
			{if $arrFilter.order!='category--up'}<a href="{url name='site1_blogfusion' action='manage' wg='order=category--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='category--dn'}<a href="{url name='site1_blogfusion' action='manage' wg='order=category--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		{if !$smarty.get.noversion}
		<th>Version</th>
		{/if}
		<th>Dashboad (username/password)</th>
		<th align="center">{if count($arrList)>1}
			{if $arrFilter.order!='flg_update--up'}<a href="{url name='site1_blogfusion' action='manage' wg='order=flg_update--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='flg_update--dn'}<a href="{url name='site1_blogfusion' action='manage' wg='order=flg_update--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}<input type="checkbox" id="select_all" /></th>
	</tr>
{foreach from=$arrList item=i key=k}
{if $i.category_id>0}
{assign var=foundSite value=1}
<input type="hidden" value="{$i.url}" id="url_{$i.id}" />
<input type="hidden" value="{$i.category_id}" id="category_{$i.id}" />
<input type="hidden" value="{$i.category}" id="category_title_{$i.id}" />
<input type="hidden" value="{$i.title}" id="title_{$i.id}" />
	<tr {if $k%2=='0'} class="matros"{/if}>
		<td align="left"><span id="title_{$i.id}">{$i.title}</span></td>
		<td width="30%">{$i.category}</td>
		{if !$smarty.get.noversion}
		<td width="70" align="center">{$i.version}</td>
		{/if}
		<td width="32%" align="left"><a target="_blank" href="{$i.url}wp-login.php">Dashboard</a> ({$i.dashboad_username}/{$i.dashboad_password})</td>
		<td width="35" align="center"><input type="checkbox" {if !empty($arrBlogLinks) && in_array($i.id, $arrBlogLinks)}checked="1"{/if} name="arrCheck[]" value="{$i.id}" class="check_blog" ></td>
	</tr>
{/if}
{/foreach}
{if empty($foundSite)}
<tr>
	<td colspan="4" align="center">sites not found</td>
</tr>
{/if}
</table>
<br/>
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>

<div align="center"><p><input type="button" value="Choose" id="choose"></p></div>

{literal}
<script type="text/javascript">
var CONST_SITE_TYPE=5;
	window.addEvent('domready', function(){
		$('select_all').addEvent('click',function(){
			$$('.check_blog').each(function(el){
				el.checked = this.checked;
			},this);
		});
				
		if ( window.parent.$('jsonSiteList') ) {
			var arrList = JSON.decode(window.parent.$('jsonSiteList').value);
			if( arrList )
			arrList.each(function(item){
				$$('.check_blog').each(function(el){
					if( window.parent.withUrl ){
						var v= ( item.flg_type == CONST_SITE_TYPE)?item.site_id:0;;
					} else {
						var v=item;
					}
					if( el.value == v ){
						el.checked = true;
					}
				});
			});
		}	

		$('choose').addEvent('click', function(){
			var arrChecked = new Array();
			var arrCheckedWhithCategories = new Array();
			var i=0;
			$$('.check_blog').each(function(el){
				if ( el.checked ) {
					if( window.parent.withUrl ){
						arrChecked[i] = {'site_id':el.value,'flg_type':CONST_SITE_TYPE,'title':$('title_'+el.value).value,'url':$('url_'+el.value).value,'category_id':$('category_'+el.value).value,'category_title':$('category_title_'+el.value).value};
					} else {
						arrChecked[i] = el.value;
					}
					i++;
				}
			});
			if( window.parent.withUrl && arrList){
			arrList.each(function(item){
				if( item.flg_type != CONST_SITE_TYPE ){
					i++;
					arrChecked[i]=item;
				}
			});
			}			
			if( window.parent.$('jsonSiteList') ) {
				window.parent.$('jsonSiteList').value = JSON.encode( arrChecked ); 
			}			
			window.parent.siteMultiboxDo();
			window.parent.multibox.close();
		});
	});
</script>
{/literal}
</body>
</html>