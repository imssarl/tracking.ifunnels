{if $arr.arrList}
	<table  width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
	<thead>
	<tr>
		<th>Category</th>
		<th>Source</th>
		<th>Title</th>
		<th>Edited</th>
		<th>Added</th>
		<th><input type="checkbox" class="select-all" value="video-{$spot_index}"></th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arr.arrList item='v' key='k'}
	<tr>
		<td>&nbsp;{$arr.arrSelect.category[$v.category_id]}</td>
		<td>{$arr.arrSelect.source[$v.source_id]}</td>
		<td>{$v.title}</td>
		<td>{$v.edited|date_local:$config->date_time->dt_full_format}</td>
		<td>{$v.added|date_local:$config->date_time->dt_full_format}</td>
		<td align="center">
			<input name="arrOpt[spots][{$spot_index}][video][]" class="item-video-{$spot_index}" type="checkbox" {if in_array($v.id,$ids)} checked='1'{/if} value="{$v.id}" />
		</td>
	</tr>	
	{/foreach}
	<tr>
		<td align="center" colspan="6"  class="heading">&nbsp;</td>
	</tr>	
	</tbody>
	</table>
{else}
		<p>no videos found</p>
{/if}
