<div style="clear:both;">
{if !$arrUser.id&&$arrUser.id!=$arrPrm.owner_id}
	<div id="t_container_show{$arrPrm.flg_type}{$arrPrm.item_id}">
	{foreach from=$arrTags item='v' name='loop'}
		<a href="{url name='site1_files' action='search_result'}?tag={$v.tag}" title="">{$v.decoded|truncate:15:"...":true}</a>{if !$smarty.foreach.loop.last}, {/if}
	{/foreach}
	</div>
</div>
{else}
<div id="t_area_show{$arrPrm.flg_type}{$arrPrm.item_id}"><div id="t_container_show{$arrPrm.flg_type}{$arrPrm.item_id}">
	{foreach from=$arrTags item='v' name='loop'}
		<a href="{url name='site1_files' action='search_result'}?tag={$v.tag}" title="">{$v.decoded|truncate:15:"...":true}</a>{if !$smarty.foreach.loop.last}, {/if}
	{/foreach}</div>
	[<a href="#" title="" id="t_set{$arrPrm.flg_type}{$arrPrm.item_id}">{if $arrTags}edit{else}add{/if}</a>]
	</div>
	<div id="t_area_edit{$arrPrm.flg_type}{$arrPrm.item_id}" style="display:none;"><textarea maxlength="100" rows="5" cols="20" id="t_container_upd{$arrPrm.flg_type}{$arrPrm.item_id}" class="elogin" style="width:30%">{foreach from=$arrTags item='v' name='loop'}{$v.decoded}{if !$smarty.foreach.loop.last}, {/if}{/foreach}</textarea><br />
	<div>tags must contain at least 3 characters (alphabetic or numeric) and divided by commas.</div>
	[<a href="#" title="" id="t_upd{$arrPrm.flg_type}{$arrPrm.item_id}">{if $arrTags}update{else}save{/if}</a>] or [<a href="#" title="" id="t_upd_cancel{$arrPrm.flg_type}{$arrPrm.item_id}">cancel</a>]
	</div>
</div>
<script type="text/javascript" language="JavaScript">
var myTags{$arrPrm.flg_type}{$arrPrm.item_id}=new tags({literal}{{/literal}
	flg_type:'{$arrPrm.flg_type}',
	item_id:{$arrPrm.item_id},
	set_url:'{url name="typedtags" action="ajax_set_taglist_site1"}',
	search_url:'{url name="site1_files" action="search_result"}'{literal}}{/literal});
</script>
{/if}