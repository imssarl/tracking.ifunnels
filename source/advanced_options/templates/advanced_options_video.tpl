{if count($arrVideo)}
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
		<tr class="tableheading">
			<th align="center">Category</th>
			<th align="center">Source</th>
			<th align="center">Title</th>
			<th align="center"><input name="chksaveselectall_{$sSpotName}" type="checkbox" value="chksaveselectall_{$sSpotName}" id="chksaveselectall_{$sSpotName}" onClick="checksaveselectionUncheckAll($('chksaveselect_{$sSpotName}') , '{$sSpotName}');"></th>
		</tr>
		{foreach from=$aSavedSelections key=key item=i}
		<tr>
			<td align="center" width="20%">{$aVal.name}</td>
			<td align="left">{$aVal.description}</td>			
			<td align="center">
				<input name="chksaveselect_{$sSpotName}[]" id="chksaveselect_{$sSpotName}" type="checkbox" {if in_array($aVal.id,$aCheckedIds)} checked='1'{/if} value="{$aVal.encode_id}" onclick="get_saveselectioncode($('chksaveselect_') , '{$sSpotName}');">
			</td>
		</tr>
		{foreachelse}
		<tr>
			<td align="center" colspan="12">No Save selection Found</td>
		</tr>
		{/foreach}
			
		<tr>
			<td align="center" colspan="13"  class="heading">&nbsp;</td>
		</tr>
</table>
{/if}

