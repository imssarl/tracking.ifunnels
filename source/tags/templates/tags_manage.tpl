<h1>{$arrPrm.title}</h1>
<div>
<script type="text/javascript" language="javascript">
{literal}
	function set_order(field) {
		$('order').value=field;
		$('u_filter').submit();
		return false;
	}
{/literal}
</script>
<form method="post" action="" id="u_filter" name="u_filter">
<input type="hidden" name="arrFilter[order]" value="{$arrFilter.order}" id="order">
<div style="margin-bottom:10px;">
	tag with type <select name="arrFilter[flg_type]" class="elogin" style="width:100px;" >
	<option value="all"> - show all - </option>
	{html_options options=$arrTypes selected=$arrFilter.flg_type}
	</select>
	and less than <input type="text" name="arrFilter[search_num]" value="{$arrFilter.search_num}" class="elogin" style="width:50px;" /> time usage
	and tagname is <input type="text" name="arrFilter[tagnames]" value="{$arrFilter.tagnames}" class="elogin" style="width:150px;" />
	<input type="submit" value="submit">
</div>
</form>
{if !$arrList}
	<div style="float:left; width: 100%">
		<div class="red" style="margin: 80px auto; width: 100%; text-align:center;"><b>no tags found</b></div>
	</div>
{else}
<form method="post" action="" id="t_list" name="t_list">
<table class="info glow" style="width:90%;">
<thead>
<tr>
	<th width="1px" nowrap><input type="checkbox" onClick="toggle_multicheckbox('t_list','del',this);" />del</th>
	<th>tag
		{if count($arrList)>1}
			{if $arrFilter.order!='decoded+up'}<a href="#" onclick="set_order('decoded+up')"><img src="/i/admin/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='decoded+dn'}<a href="#" onclick="set_order('decoded+dn')"><img src="/i/admin/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>search num
		{if count($arrList)>1}
			{if $arrFilter.order!='t.search_num+up'}<a href="#" onclick="set_order('t.search_num+up')"><img src="/i/admin/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='t.search_num+dn'}<a href="#" onclick="set_order('t.search_num+dn')"><img src="/i/admin/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>last search
		{if count($arrList)>1}
			{if $arrFilter.order!='t.search_last+up'}<a href="#" onclick="set_order('t.search_last+up')"><img src="/i/admin/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='t.search_last+dn'}<a href="#" onclick="set_order('t.search_last+dn')"><img src="/i/admin/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>added
		{if count($arrList)>1}
			{if $arrFilter.order!='t.added+up'}<a href="#" onclick="set_order('t.added+up')"><img src="/i/admin/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='t.added+dn'}<a href="#" onclick="set_order('t.added+dn')"><img src="/i/admin/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>attach items num
		{if count($arrList)>1}
			{if $arrFilter.order!='items_num+up'}<a href="#" onclick="set_order('items_num+up')"><img src="/i/admin/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='items_num+dn'}<a href="#" onclick="set_order('items_num+dn')"><img src="/i/admin/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>type
		{if count($arrList)>1}
			{if $arrFilter.order!='t.flg_type+up'}<a href="#" onclick="set_order('t.flg_type+up')"><img src="/i/admin/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='t.flg_type+dn'}<a href="#" onclick="set_order('t.flg_type+dn')"><img src="/i/admin/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/i/admin/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
</tr>
</thead>
<tbody>
{foreach from=$arrList key='k' item='v'}
<input type="hidden" name="arrList[{$v.id}][id]" value="{$v.id}" />
<tr{if $k%2=='0'} class="matros"{/if}>
	<td><input type="checkbox" name="arrList[{$v.id}][del]"/></td>
	<td>{$v.decoded}</td>
	<td><input type="text" name="arrList[{$v.id}][search_num]" value="{$v.search_num}" class="elogin" style="width:50px;" /></td>
	<td>{$v.search_last}</td>
	<td>{$v.added}</td>
	<td>{$v.items_num}</td>
	<td>{$arrTypes[$v.flg_type]}</td>
</tr>
{/foreach}
</tbody>
<tfoot>
	<tr><td colspan="9">{include file="../../pgg_backend.tpl"}</td></tr>
	<tr><td colspan="9"><center>
		<a href="#" onClick="$('t_list').submit();return false;">update</a> |
		<a href="#" onClick="$('t_list').reset();return false;">cancel</a>
	</center></td></tr>
</tfoot>
</table>
</form>
{/if}
</div>