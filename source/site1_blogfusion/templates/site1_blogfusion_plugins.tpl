{if isset($errorCode)}
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
{if $msg == 'restore'}
<p class="grn">Restored successfully</p> 
{/if}
{if $msg == 'delete'}
<p class="grn">The Plug-in was deleted successfully</p> 
{/if}
<br/>
<a href="{url name='site1_blogfusion' action='plugin_search'}" class="mb" rel="width:920,height:500">Search New Plugin</a>&nbsp;|&nbsp;<a href="#" id="upload" rel="block">Upload New Plugin</a>&nbsp;|&nbsp;<a href="{url name='site1_blogfusion' action='plugins' wg='restore=default'}" rel="<font color='red'>Note:</font> it won't affect the plugins you uploaded" class="tips">Restore default plugins</a>
<div id="plugin_form" style="display:none;"> 
<form class="wh" enctype="multipart/form-data" action="{url name='site1_blogfusion' action='plugins'}" method="POST" style="width:50%;">
	<fieldset>
		<legend>Upload New Plugin</legend>
		<ol>
			<li>
				<label>Zip File <em>*</em></label><input type="file" name="zip"/>
			</li>
			<li>
				<label></label><input type="submit" name="upload" value="Upload">
			</li>
		</ol>
	</fieldset>
</form>
</div>
<table width="100%">
	<tr>
		<th>Title
		{if count($arrPlugins)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_blogfusion' action='plugins' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_blogfusion' action='plugins' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}		
		</th>
		<th>Author
		{if count($arrPlugins)>1}
			{if $arrFilter.order!='author--up'}<a href="{url name='site1_blogfusion' action='plugins' wg='order=author--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='author--dn'}<a href="{url name='site1_blogfusion' action='plugins' wg='order=author--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}		
		</th>
		<th>Version</th>
		<th width="45%">Description</th>
		<th width="15%">Added
		{if count($arrPlugins)>1}
			{if $arrFilter.order!='added--up'}<a href="{url name='site1_blogfusion' action='plugins' wg='order=added--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='added--dn'}<a href="{url name='site1_blogfusion' action='plugins' wg='order=added--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}		
		</th>
		<th width="5%">Delete</th>
	</tr>	
	{foreach from=$arrPlugins item=i}
	<tr>
		<td>{if $i.url}<a href="{$i.url}" target="_blank">{/if}{$i.title}{if $i.url}</a>{/if}</td>
		<td>{if $i.author_url}<a href="{$i.author_url}" target="_blank">{/if}{$i.author}{if $i.author_url}</a>{/if}</td>
		<td>{$i.version}</td>
		<td>{$i.description}</td>
		<td align="center">{$i.added|date_local:$config->date_time->dt_full_format}</td>
		<td align="center"><a href="{url name='site1_blogfusion' action='plugins'}?del_id={$i.id}">Delete</a></td>
	</tr>	
	{/foreach}
</table>
<br/>
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>
{literal}
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
<script type="text/javascript">
	$('upload').addEvent('click', function(e){
		e.stop();
		if($('plugin_form').style.display == 'block')
			$('plugin_form').style.display = 'none';
		else 
			$('plugin_form').style.display = 'block'
	});
var multibox={};	
window.addEvent('domready', function(){ 
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});		
	var optTips = new Tips('.tips');
});		
</script>
{/literal}