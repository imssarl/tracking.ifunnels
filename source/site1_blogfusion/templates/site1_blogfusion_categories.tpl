{include file="site1_blogfusion_general_menu.tpl"}
<div style="padding-top:10px;margin:0 auto;width:80%;">
<table style="width:100%;">
<form method="post" action="" id='manage_category'>
<thead>
	<tr>
		<th width="1px">Del</th>
		<th>Category name</th>
	</tr>
</thead>
	{foreach from=$arrList key=k item=i}
	<input type="hidden" name="arrList[{$i.id}][id]" value="{$i.id}" /> 
	<input type="hidden" name="arrList[{$i.id}][ext_id]" value="{$i.ext_id}" /> 
	<input type="hidden" name="arrList[{$i.id}][flg_default]" value="{$i.flg_default}" /> 
	<tr {if $k%2=='0'} class="matros"{/if}>
		<td valign="top">{if !$i.flg_default}<input type="checkbox" name="arrList[{$i.id}][del]" class="del-me" />{/if}</td>
		<td valign="top">
			<input type="text" style="width:100%;" name="arrList[{$i.id}][title]" value="{$i.title|escape}" />
		</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="2" valign="top">Add new:<input type="text" style="width:100%;" name="arrList[0][title]" value="" /></td>
	</tr>
</table>
<div><input type="submit" value="Update" /></div>
</form>
</div>
{include file="../../pgg_frontend.tpl"}
{literal}
<script>
$$('.pg_handler').each(function(el){
	el.addEvent('click',function(a){
		a.stop();
		var href = el.href+{/literal}'&id={$arrBlog.id}'{literal}; 
		href.toURI().go();
	});
});
</script>
{/literal}
		</td>
	</tr>
</table>