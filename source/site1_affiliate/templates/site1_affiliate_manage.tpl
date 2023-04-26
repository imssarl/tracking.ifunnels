<br/>
<br/>
<div align="center">
<table style="width:80%;" border="0">
<thead>
	<tr>
		<th width="50">ID</th>
		<th>Page&nbsp;URL</th>
		<th>Affiliate&nbsp;URL</th>
		<th>Type</th>
		<th>CCP Tracking</th>
		<th>&nbsp;Ad&nbsp;Campaign&nbsp;</th>
		<th width="180">Date&nbsp;Created</th>
		<th width="80">&nbsp;</th>
	</tr>
</thead>
<tbody>
{foreach from=$arrItems item=i name=j}
	<tr {if $smarty.foreach.j.iteration%2=='0'} class="matros"{/if}>
		<td align="center">{$i.page_id}</td>
		<td>{$i.page_address}{$i.page_name}</td>
		<td>{$i.page_affiliate_url}</td>
		<td align="center">&nbsp;{if $i.page_type == 'redirect'}Redirect{else}Cloaked{/if}&nbsp;</td>
		<td align="center"> {if $i.is_cpp}Yes{else}No{/if} </td>
		<td align="center"> {if $i.is_compaign}Yes{else}No{/if} </td>
		<td align="center">{$i.page_date_created}</td>
		<td align="center">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center"><a href="{$i.page_address}{$i.page_name}" target="_blank" class="mb" ><img title="View" src="/skin/i/frontends/design/buttons/view.gif" /></a></td>
					<td align="center"><a href="{url name='site1_affiliate' action='edit_settings'}?id={$i.page_id}{if $i.is_cpp}&cpp=1{/if}{if $smarty.get.page}&page={$smarty.get.page}{/if}"><img title="Edit settings" src="/skin/i/frontends/design/buttons/edit.png" /></a></td>
					<td align="center"><a href="{url name='site1_affiliate' action='edit_file'}?id={$i.page_id}{if $i.is_cpp}&cpp=1{/if}{if $smarty.get.page}&page={$smarty.get.page}{/if}"><img title="Edit file" src="/skin/i/frontends/design/buttons/edit_file.png" /></a></td>
					<td align="center"><a class="delete_action" href="{url name='site1_affiliate' action='manage'}?del={$i.page_id}{if $i.is_cpp}&cpp=1{/if}{if $smarty.get.page}&page={$smarty.get.page}{/if}"><img title="Delete" src="/skin/i/frontends/design/buttons/delete.png" /></a>		</td>
				</tr>
			</table>
		</td>
	</tr>
{foreachelse}
&nbsp;		
{/foreach}
</tbody>
</table>
</div>
