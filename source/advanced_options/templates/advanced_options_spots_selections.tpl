<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
		<tr class="tableheading">
			<th   align="center">Title</th>
			<th   align="center">Description</th>
			<th align="center"><input type="checkbox" class="select-all" value="articles-{$spot_index}"></th>
		</tr>
		{foreach from=$arrList key=iKey item=i}
		<tr>
			<td align="center" width="20%">{$i.name}</td>
			<td align="left">{$i.description|stripslashes}</td>			
			<td align="center">
				<input name="arrOpt[spots][{$spot_index}][articles][]" class="item-articles-{$spot_index}" type="checkbox" {if  in_array($i.id,$ids)} checked='1'{/if} value="{$i.id}" /></td>
		</tr>
		{foreachelse}
		<tr>
			<td align="center" colspan="3">No Save selection Found</td>
		</tr>
		{/foreach}
		<tr>
			<td align="center" colspan="3"  class="heading">&nbsp;</td>
		</tr>
</table>