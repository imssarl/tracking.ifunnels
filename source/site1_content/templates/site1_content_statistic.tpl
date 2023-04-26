<p><input type="submit" value="Delete" id="delete" /></p>
<form action="" id="current-form" method="post">
<input type="hidden" name="mode" value="store-settings" id="mode" />
<table width="100%">
	<thead>
	<tr>
		<th>Title{if count($arrList)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_content' action='statistic' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_content' action='statistic' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th width="15%">Status</th>
		<th width="15%">Generated Date</th>
		<th width="15%"></th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arrList item=i key=k}
	<tr {if $k%2=='0'} class="matros"{/if}>
		<td>{$i.keyword}{$i.content.title}</td>
		<td align="center">{if $i.flg_status == 0}No{elseif $i.flg_status == 1}Yes{elseif $i.flg_status == 2}Error{/if}</td>
		<td align="center">{$i.start|date_format:'%Y.%m.%d'}</td>
		<td align="center"><a target="_blank" href="{$i.url}{if $arrPrj.flg_source==1}permalink.php?article={$i.content.url}{else}{$i.keyword}.html{/if}" class="click-me-del">View</a></td>
	</tr>	
	{/foreach}
	</tbody>
</table>
</form> 
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>