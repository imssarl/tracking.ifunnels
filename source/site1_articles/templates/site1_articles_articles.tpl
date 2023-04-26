<div style="padding-top:10px;margin:0 auto;width:100%;">
{if $msg}
	<div class="grn" style="padding:10px;">
		Article successful {$msg}.
	</div>
{/if}
<div style="float:right;">
<form method="post" action="" id="video-filter">
	<div style="float:left;">Category <select name="category" id='category-filter'>
		<option value=''> - select - </option>
		{html_options options=$arrSelect.category selected=$smarty.get.category}
	</select></div>
	<div style="float:left;padding-left:3px;"><input type="submit" value="Filter" /></div>
</form>
<script type="text/javascript">
{literal}
$('video-filter').addEvent('submit',function(e){
	e.stop();
	var myURI=new URI();
	if ( $('category-filter').value=='' ) {
		myURI.setData(new Hash(myURI.getData()).filter(function(value, key){return key!='category';}));
	} else {
		myURI.setData({category:$('category-filter').value}, true);
	}
	myURI.go();
});
{/literal}
</script>
</div>
<div style="clear:both;">
{if $arrList}
<form action="" id="post_form" method="post">
<p>
	<input type="submit" value="Export" id="export" />
	<input type="submit" value="Delete" id="delete" />
</p>
<table style="width:100%;">
<thead>
<tr>
	<th><input type="checkbox" id="sel" /></th>
	<th width="10%">Category
		{if $arrPg.recall>1}
			{if $arrFilter.order!='category_title--up'}<a href="{url name='site1_articles' action='articles' wg='order=category_title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='category_title--dn'}<a href="{url name='site1_articles' action='articles' wg='order=category_title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>Title
		{if $arrPg.recall>1}
			{if $arrFilter.order!='a.title--up'}<a href="{url name='site1_articles' action='articles' wg='order=a.title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='a.title--dn'}<a href="{url name='site1_articles' action='articles' wg='order=a.title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>Summary</th>
	<th width="8%">Source
		{if $arrPg.recall>1}
			{if $arrFilter.order!='source_title--up'}<a href="{url name='site1_articles' action='articles' wg='order=source_title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='source_title--dn'}<a href="{url name='site1_articles' action='articles' wg='order=source_title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th width="8%">Status
		{if $arrPg.recall>1}
			{if $arrFilter.order!='a.flg_status--up'}<a href="{url name='site1_articles' action='articles' wg='order=a.flg_status--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='a.flg_status--dn'}<a href="{url name='site1_articles' action='articles' wg='order=a.flg_status--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th width="8%">Added
		{if $arrPg.recall>1}
			{if $arrFilter.order!='a.id--up'}<a href="{url name='site1_articles' action='articles' wg='order=a.id--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='a.id--dn'}<a href="{url name='site1_articles' action='articles' wg='order=a.id--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th style="width:90px;">&nbsp;</th>
</tr>
</thead>
<tbody>
{foreach from=$arrList key='k' item='v'}
<tr{if $k%2=='0'} class="matros"{/if}>
	<td><input type="checkbox" value="{$v.id}" name="ids[]" class="check-me-sel" /></td>
	<td>{$v.category_title}</td>
	<td>{$v.title}</td>
	<td>{$v.summary}...</td>
	<td>{$v.source_title}</td>
	<td>{if $v.flg_status}Active{else}Inactive{/if}</td>
	<td>{$v.date}</td>
	<td>
		<a href="{url name='site1_articles' action='edit'}?id={$v.id}"><img title="Edit" src="/skin/i/frontends/design/buttons/edit.png" /></a>
		<a href="{url name='site1_articles' action='getcode'}?id={$v.id}&type=art" style="float:left" class="mb" rel="width:530,height:390" title="Code for '{$v.title}' article"><img title="Code for '{$v.title}' article" src="/skin/i/frontends/design/buttons/view.gif" /></a>
		<a href="{url name='site1_articles' action='articles'}?dup={$v.id}"><img title="Duplicate" src="/skin/i/frontends/design/buttons/duplicate.png" /></a>
		<a href="{url name='site1_articles' action='articles'}?del={$v.id}"><img title="Delete" src="/skin/i/frontends/design/buttons/delete.png" /></a>
	</td>
</tr>
{/foreach}
</tbody>
</table>
{include file="../../pgg_frontend.tpl"}
</form>
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
<script type="text/javascript">
var exportLink='{url name='site1_articles' action='export'}';
{literal}
var multibox = {};
window.addEvent("domready", function(){
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});
});
checkboxToggle($('sel'));
$('export').addEvent('click',function(e){
	$('post_form').set('action',exportLink);
});
$('delete').addEvent('click',function(e){
	$('post_form').set('action','');
});
{/literal}
</script>
{else}
	<p>no articles found</p>
{/if}
</div>
</div>