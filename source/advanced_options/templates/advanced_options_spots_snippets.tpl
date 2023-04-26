		<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="summary">
			<tr  class="tableheading">
				<th  align="center">Snippet #</th>
				<th  align="center">Title</th>
				<th  align="center">Description</th>
				<th  align="center"># of parts</th>
				<th  align="center">Date created</th>
				<th  align="center">Intelligent<br>tracking management<br> enabled</th>
				<th  align="center"># of impressions</th>
				<th  align="center"># of clicks</th>
				<th align="center">
					<input type="checkbox" value="snippets-{$spot_index}" class="select-all">
				</th>
			</tr>
			{foreach from=$arrList key=iKey item=i}
				{if 'Y' == $i.is_itm_enabled}
					{assign var=sItm value='Yes'}
				{else}
					{assign var=sItm value='No'}
				{/if}
				<tr>
					<td align="center">{$i.id}</td>
					<td align="left">{$i.title}</td>
					<td align="left">{$i.description}</td>
					<td align="center">{$i.noofparts}</td>
					<td align="center">{$i.created_date}</td>
					<td align="center">{$sItm}</td>		
					<td align="center">{$i.noofimpression}</td>
					<td align="center">{$i.noofclicks}</td>
					<td align="center">
					{if $i.noofparts > 0}
						<input type="checkbox" name="arrOpt[spots][{$spot_index}][snippets][]" class="item-snippets-{$spot_index}" type="checkbox" {if  in_array($i.id,$ids)} checked='1'{/if} value="{$i.id}" />
					{else}
						<img src="./images/denied.png" border="0" title="Please add a part before generate code">
					{/if}
				{foreachelse}
				<tr>
					<td align="center" colspan="12">No Save selection Found</td>
				</tr>
				{/foreach}
			<tr>
				<td align="center" colspan="13"  class="heading">&nbsp;</td>
			</tr>
		</table>