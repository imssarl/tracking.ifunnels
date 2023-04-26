{if $errorCode}
<p><font color="Red">
{foreach from=$errorCode item=i}
		{if $i == '011'}Uploaded file size is more than 5MB.Please upload below 5MB.<br/>{/if}
		{if $i == '012'}Invalid file.Please upload only zip file.<br/>{/if}
		{if $i == '013'}Invalid zip file.<br/>{/if}
		{if $i == '014'}Invalid Theme.<br/>{/if}
		{if $i == '002'}This theme is already exist.<br/>{/if}
{/foreach}
</font></p>
{/if}
{if $msg == 'added'}
<p class="grn">Uploaded successfully</p> 
{/if}
{if $msg == 'delete'}
<p class="grn">The Theme was deleted successfully</p> 
{/if}
<form action="" method="POST" style="display:none;" enctype='multipart/form-data' id="add_plugin">
	<table>
		<tr>
			<td>Priority</td>
			<td><input type="text" name="theme[priority]" value="0" /></td>
		</tr>
		<tr>
			<td>Proprietary</td>
			<td><input type="checkbox" value="1" name="theme[flg_prop]"></td>
		</tr>
		<tr>
			<td>Zip file</td>
			<td><input type="file" name="zip" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="add" value="Add"></td>
		</tr>
	</table>
</form>

<table class="info glow">
<thead >
<tr>
	<th width="70">
		Priority
		{if count($arrList)>1}
			{if $arrFilter.order!='priority--up'}<a href="{url name='admin_blogfusion' action='themes' wg='order=priority--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='priority--dn'}<a href="{url name='admin_blogfusion' action='themes' wg='order=priority--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th width="80">
		Proprietary
		{if count($arrList)>1}
			{if $arrFilter.order!='flg_prop--up'}<a href="{url name='admin_blogfusion' action='themes' wg='order=flg_prop--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='flg_prop--dn'}<a href="{url name='admin_blogfusion' action='themes' wg='order=flg_prop--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th width="200">Title
		{if count($arrList)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='admin_blogfusion' action='themes' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='admin_blogfusion' action='themes' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}	
	</th>
	<th>Version{if count($arrList)>1}
			{if $arrFilter.order!='version--up'}<a href="{url name='admin_blogfusion' action='themes' wg='order=version--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='version--dn'}<a href="{url name='admin_blogfusion' action='themes' wg='order=version--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
	<th width="70">Author{if count($arrList)>1}
			{if $arrFilter.order!='author--up'}<a href="{url name='admin_blogfusion' action='themes' wg='order=author--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='author--dn'}<a href="{url name='admin_blogfusion' action='themes' wg='order=author--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
	<th>Description</th>
	<th width="150">Added
		{if count($arrList)>1}
			{if $arrFilter.order!='added--up'}<a href="{url name='admin_blogfusion' action='themes' wg='order=added--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='added--dn'}<a href="{url name='admin_blogfusion' action='themes' wg='order=added--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}	
	</th>
	<th width="50">&nbsp;</th>
</tr>
</thead>
	<tr>
		<td colspan="5"><a href="#" id="link_add">Add</a> new theme</td>
	</tr>
<tbody>
{foreach from=$arrList key='k' item='v'}
<tr {if $k%2=='0'} class="matros"{/if}>
	<td align="center">{$v.priority}</td>
	<td align="center">{if $v.flg_prop}yes{else}no{/if}</td>
	<td>{if $v.url}<a href="{$v.url}" target="_blank">{/if}{$v.title}{if $v.url}</a>{/if} (<a href="#" class="screenshot" rel="<img src='{$v.preview}'>" style="text-decoration:none">preview</a>)</td>
	<td>{if $v.author_url}<a href="{$v.author_url}" target="_blank">{/if}{$v.author}{if $v.author_url}</a>{/if}</td>
	<td>{$v.version}</td>
	<td>{$v.description}</td>
	<td>{$v.added|date_local:$config->date_time->dt_full_format}</td>
	<td class="option">
			<a href="{url name='admin_blogfusion' action='themes'}?delete={$v.id}">del</a>
	</td>
</tr>
{/foreach}
</tbody>
</table>
<br/>
<div align="right" style="padding:0 20px 0 0;">
{include file="../../pgg_frontend.tpl"}
</div>
{literal}
<script type="text/javascript">
$('link_add').addEvent('click', function(e){
	e.stop();
	if($('add_plugin').style.display == 'none') {
		$('add_plugin').style.display = 'block';
	}else{
		$('add_plugin').style.display = 'none';
	}
	
});
window.addEvent('domready', function(){
	var optTips = new Tips('.screenshot');
	$$('.screenshot').each(function(el){ el.addEvent('click',function(e){ e.stop(); }); });
});

</script>
{/literal}