<div align="center" style="padding:10px 0 0 0; ">
	<a href="{url name='site1_content' action='cnb'}">Manage</a> | <a href="{url name='site1_content' action='cnb_create'}">Create</a>
</div>
{if $msg == 'delete'}<div class="grn">Delete successfully.</div>{/if}
{if $msg == 'error'}<div class="red">Error. Can't delete project.</div>{/if}
<p>
	<input type="submit" value="Delete" id="delete" />
</p>
<form action="" id="current-form" method="post">
<input type="hidden" name="mode" value="store-settings" id="mode" />
<table width="100%">
	<thead>
	<tr>
		<th style="padding-right:0;" width="1px"><input type="checkbox" id="del" class="tooltip" title="mass delete" rel="check to select all" /></th>
		<th>Project{if count($arrList)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_content' action='cnb' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_content' action='cnb' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th width="10%">Posting Status</th>
		<th width="10%">Project Status</th>
		<th width="10%">Project Type</th>
		<th width="15%">Options</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arrList item=i key=k}
	<tr{if $k%2=='0'} class="matros"{/if}>
		<input type="hidden" name="ids[]" value="{$i.id}" />
		<td style="padding-right:0;"><input type="checkbox" name="del[{$i.id}]" value="{$i.id}" class="check-me-del" id="check-{$i.id}" /></td>
		<td>{$i.title}</td>
		<td align="center"><a href="{url name='site1_content' action='statistic'}?id={$i.id}">{$i.count_posted_content}/{$i.count_content}</a></td>
		<td align="center">{if $i.flg_status == 0}not started{elseif $i.flg_status == 1}in progress{elseif $i.flg_status == 2}cross linking{else}completed{/if}</td>
		<td align="center">{foreach Project_Content::toLabelArray() item=name key=ids}{if {$name.flg_source}=={$i.flg_source}}{$contentTypeName = $name.title}{/if}{/foreach}
		{$contentTypeName}</td>
		<td align="center">
			<a href="{url name='site1_content' action='cnb_create'}?id={$i.id}">Edit</a> | 
			<a href="#" rel="{$i.id}" class="click-me-del">Delete</a>
		</td>
	</tr>	
	{/foreach}
	</tbody>
</table>
</form>
{literal}
<script>
$$('.click-me-del').each(function(el){
	el.addEvent('click', function(e) {
		e && e.stop();
		$('check-'+el.rel).checked = true;
		$('current-form').submit();
	});
});
$('del').addEvent('click',function(){
	$$('.check-me-del').each(function(el){
		el.checked = $('del').checked;
	});
});
$('delete').addEvent('click',function(){
	$('current-form').submit();
});
</script>
{/literal}
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>