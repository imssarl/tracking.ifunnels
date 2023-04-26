	<div style="float:right;">
	<form method="post" action="" id="video-filter">
		<div style="float:left;">Category <select name="category" id='category-filter'>
			<option value=''> - select - </option>
			{html_options options=$arrSelect.video.category selected=$smarty.post.category.video}
		</select></div>
		<div style="float:left;padding-left:3px;"><input type="submit" value="Filter" /></div>
	</form>
	</div>
	{if $arrVideo}
	<table style="width:100%;">
	<thead>
	<tr>
		<th>Category{if count($arrVideo)>1}
			{if $arrFilter.order!='category_id--up'}<a href="{url name='site1_content' action='selectcontent' wg='order=category_id--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='category_id--dn'}<a href="{url name='site1_content' action='selectcontent' wg='order=category_id--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Source{if count($arrVideo)>1}
			{if $arrFilter.order!='source_id--up'}<a href="{url name='site1_content' action='selectcontent' wg='order=source_id--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='source_id--dn'}<a href="{url name='site1_content' action='selectcontent' wg='order=source_id--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Title{if count($arrVideo)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_content' action='selectcontent' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_content' action='selectcontent' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Edited{if count($arrVideo)>1}
			{if $arrFilter.order!='edited--up'}<a href="{url name='site1_content' action='selectcontent' wg='order=edited--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='edited--dn'}<a href="{url name='site1_content' action='selectcontent' wg='order=edited--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Added{if count($arrVideo)>1}
			{if $arrFilter.order!='added--up'}<a href="{url name='site1_content' action='selectcontent' wg='order=added--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='added--dn'}<a href="{url name='site1_content' action='selectcontent' wg='order=added--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>{if !$smarty.get.multiselect}place{else}<input type="checkbox" id="select_all">{/if}</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arrVideo item='v' key='k'}
	<tr{if $k%2!='0'} class="matros"{/if}>
		<td>&nbsp;{$arrSelect.video.category[$v.category_id]}</td>
		<td>{$arrSelect.video.source[$v.source_id]}</td>
		<td>{$v.title}</td>
		<td>{$v.edited|date_local:$config->date_time->dt_full_format}</td>
		<td>{$v.added|date_local:$config->date_time->dt_full_format}</td>
		<td align="center">
			<div style="display:none;" id="content_{$k}_title">{$v.title}</div>
			<div style="display:none;" id="video_{$v.id}_body">{$v.body}</div>
			<div style="display:none;" id="video_{$v.id}_url_of_video">{$v.url_of_video}</div>		
			<div {if $smarty.get.multiselect}style="display:none;"{/if}>
			<a href="#" class="place_full" rel="video_{$v.id}">All</a> |
			<a href="#" class="place_url" rel="video_{$v.id}">URL</a> |
			<a href="#" class="place_embed" rel="video_{$v.id}">Embed Code</a>			
			</div>
			<input type="checkbox" {if $smarty.get.multiselect}style="display:block;"{else}style="display:none;"{/if} class="chk_item" value="{$v.title}" id="{$k}">
		</td>
	</tr>	
	{/foreach}
	</tbody>
	<tfoot>
		<tr><td colspan="{$arrFilter.fields_num+3}">{include file="../../pgg_frontend.tpl"}</td></tr>
	</tfoot>
	</table>
	<div align="center"><p>	<input type="button" value="Choose" id="choose" ></p></div>
	{else}
		<p>no content found</p>
	{/if}