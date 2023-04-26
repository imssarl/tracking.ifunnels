{if $errorCode}
<p><font color="Red">
		{if $errorCode == '011'}Uploaded file size is more than 2MB.Please upload below 2MB.<br/>{/if}
		{if $errorCode == '012'}Invalid file.Please upload only zip file.<br/>{/if}
		{if $errorCode == '013'}Invalid zip file.<br/>{/if}
		{if $errorCode == '014'}Invalid Plugin.<br/>{/if}
		{if $errorCode == '002'}This plugin is already exist.<br/>{/if}
</font></p>
{/if}
{if $msg == 'added'}
<p class="grn">Uploaded successfully</p> 
{/if}
{if $msg == 'delete'}
<p class="grn">The Plug-in was deleted successfully</p> 
{/if}
<form action="" method="POST" style="display:none;" enctype='multipart/form-data' id="add_plugin">
	<table>
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
<thead>
<tr>
	<th>Title
		{if count($arrList)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='admin_blogfusion' action='plugins' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='admin_blogfusion' action='plugins' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}	
	</th>
	<th>Author{if count($arrList)>1}
			{if $arrFilter.order!='author--up'}<a href="{url name='admin_blogfusion' action='plugins' wg='order=author--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='author--dn'}<a href="{url name='admin_blogfusion' action='plugins' wg='order=author--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
	<th width="80">Version{if count($arrList)>1}
			{if $arrFilter.order!='version--up'}<a href="{url name='admin_blogfusion' action='plugins' wg='order=version--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='version--dn'}<a href="{url name='admin_blogfusion' action='plugins' wg='order=version--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
	<th>Description</th>
	<th>Added
		{if count($arrList)>1}
			{if $arrFilter.order!='added--up'}<a href="{url name='admin_blogfusion' action='plugins' wg='order=added--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='added--dn'}<a href="{url name='admin_blogfusion' action='plugins' wg='order=added--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}	
	</th>
	<th>&nbsp;</th>
</tr>
</thead>
	<tr>
		<td colspan="4"><a href="#" id="link_add">Add</a> new plugin</td>
	</tr>
<tbody>
{foreach from=$arrList key='k' item='v'}
<tr {if $k%2=='0'} class="matros"{/if}>
	<td><a href="{$v.url}" target="_blank">{$v.title}</a></td>
	<td>{$v.author}</td>
	<td>{$v.version}</td>
	<td>{$v.description}</td>
	<td>{$v.added|date_local:$config->date_time->dt_full_format}</td>
	<td class="option">
			<a href="{url name='admin_blogfusion' action='plugins'}?delete={$v.id}">del</a>
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
</script>
{/literal}