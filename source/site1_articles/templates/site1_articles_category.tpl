<div style="padding-top:10px;margin:0 auto;width:80%;">
<table style="width:100%;">
<form method="post" action="" id='manage_category'>
<thead>
	<tr>
		<th width="1px">Del</th>
		{*foreach from=$arrFlags item='v'*}
		<th width="1px">Active</th>
		{*/foreach*}
		<th width="1px">&nbsp;</th>
		<th>Category name</th>
		{if $arrType.flg_sort}
		<th width="1px">Priority</th>
		{/if}
		<th width="70px">Number of article</th>
		<th width="70px">Show article list</th>
		<th width="70px">Get code</th>
	</tr>
</thead>
	{foreach from=$arrCats key='k' item='v'}
	<tr{if $k%2=='0'} class="matros"{/if}>
		<input type="hidden" name="arrCats[{$v.id}][id]" value="{$v.id}">
		<td style="padding-right:0;" valign="top"><input type="checkbox" name="arrCats[{$v.id}][del]" class="del-me" /></td>
		{foreach from=$arrFlags key='fk' item='f'}
		{assign var="name" value="flag`$fk`"}
		<td style="padding-right:0;" valign="top"><input type="checkbox" name="arrCats[{$v.id}][flag{$fk}]"{if $v.$name} checked{/if} /></td>
		{/foreach}
		{assign var="error" value=$v.id}
		<td>{if $arrErr.$error}<span class="red">*</span>{/if}</td>
		<td valign="top">
			<input type="text" style="width:100%;" name="arrCats[{$v.id}][title]" value="{$v.title}" />
			<div style="display:none;" id="items_{$v.id}">
			{foreach from=$v.items item='item'}
				<div><a href="{url name='site1_articles' action='showarticle'}?id={$item.id}" class="mb" rel="width:800,height:550" title="'{$item.title}' preview">{$item.title}</a></div>
			{/foreach}
			</div>
		</td>
		{if $arrType.flg_sort}
		<td valign="top"><input type="text" class="elogin" name="arrCats[{$v.id}][priority]" value="{$v.priority}" /></td>
		{/if}
		<td align="center" valign="top">{$v.count}</td>
		<td align="center" valign="top">
			{if $v.items}
			<a href="#" class="switch" rel="{$v.id}" title="Articles in '{$v.title}' category">
				<img src="/skin/i/frontends/design/go-down.gif" id="img_{$v.id}" />
			</a>
			{/if}
		</td>
		<td align="center" valign="top">
			{if $v.items}
			<a href="{url name='site1_articles' action='getcode'}?id={$v.id}&type=cat" class="mb" rel="width:530,height:390" title="Code for '{$v.title}' category">
				<img title="View" src="/skin/i/frontends/design/buttons/view.gif" id="get_{$v.id}" />
			</a>
			{/if}
		</td>
	</tr>
	{/foreach}
	<tr>
		<input type="hidden" name="arrCats[0][id]" value="0">
		<td>&nbsp;</td>
		{foreach from=$arrFlags key='fk' item='f'}
		<td style="padding-right:0;"><input type="checkbox" name="arrCats[0][flag{$fk}]" checked="" /></td>
		{/foreach}
		<td>&nbsp;</td>
		<td valign="top">add new:<input type="text" style="width:100%;" name="arrCats[0][title]" value="" /></td>
		{if $arrType.flg_sort}
		<td valign="top"><input type="text" class="elogin" name="arrCats[0][priority]" value="" /></td>
		{/if}
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
{include file="../../pgg_frontend.tpl"}
<div><input type="submit" value="Update" /></div>
</form>
</div>
<script type="text/javascript" src="/skin/_js/moopop.js"></script>
<script type="text/javascript">
{literal}
$$('.switch').each(function(el){
	el.addEvent('click',function(e,el){
		e.stop();
		var obj=$('items_'+el.rel);
		obj.style.display=obj.style.display=='none'?'block':'none';
		$('img_'+el.rel).src=obj.style.display=='none'?'/skin/i/frontends/design/go-down.gif':'/skin/i/frontends/design/go-up.gif';
	}.bindWithEvent(this, el));
});

$('manage_category').addEvent('submit',function(e){
	if ($$('.del-me').some(function(item){
		return item.checked==true;
	})) {
		if (!confirm('Are you sure you want to delete this category?\nPlease note that all articles stored under that category will be deleted too.')) {
			e.stop();
		}
	}
});
{/literal}
</script>

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script>
var multibox = {};
window.addEvent("domready", function(){
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});
});
</script>
{/literal}