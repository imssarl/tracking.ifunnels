{if isset($errorCode)}
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
{if $msg == 'restore'}
<p class="grn">Restored successfully</p> 
{/if}
{if $msg == 'delete'}
<p class="grn">The Theme was deleted successfully</p> 
{/if}
<br/>
<a href="{url name='site1_blogfusion' action='themes_search'}" class="mb" rel="width:850,height:500">Search New Theme</a>&nbsp;|&nbsp;<a href="#" id="upload" rel="block">Upload New Theme</a>&nbsp;|&nbsp;<a href="{url name='site1_blogfusion' action='themes' wg='restore=default'}" rel="<font color='red'>Note:</font> it won't affect the themes you uploaded" class="tips">Restore default themes</a>
<div id="theme_form" style="display:none;"> 
<form class="wh" action="" method="POST" enctype="multipart/form-data" style="width:50%;">
	<fieldset>
		<legend>Upload New Theme</legend>
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
		{if count($arrThemes)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_blogfusion' action='themes' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_blogfusion' action='themes' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}		
		</th>
		<th>Author
		{if count($arrThemes)>1}
			{if $arrFilter.order!='author--up'}<a href="{url name='site1_blogfusion' action='themes' wg='order=author--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='author--dn'}<a href="{url name='site1_blogfusion' action='themes' wg='order=author--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}		
		</th>
		<th>Version</th>
		<th width="45%">Description</th>
		<th width="15%">Added
		{if count($arrThemes)>1}
			{if $arrFilter.order!='added--up'}<a href="{url name='site1_blogfusion' action='themes' wg='order=added--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='added--dn'}<a href="{url name='site1_blogfusion' action='themes' wg='order=added--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}		
		</th>
		<th width="5%">Delete</th>
	</tr>
	{foreach from=$arrThemes item=i}
	<tr>
		<td>{if $i.url}<a href="{$i.url}" target="_blank">{/if}{$i.title}{if $i.url}</a>{/if} {if $i.preview}(<a href="#" class="screenshot" rel="<img src='{$i.preview}'>" style="text-decoration:none">preview</a>){/if}</td>
		<td>{if $i.author_url}<a href="{$i.author_url}" target="_blank">{/if}{$i.author}{if $i.author_url}</a>{/if}</td>
		<td>{$i.version}</td>
		<td>{$i.description}</td>
		<td align="center">{$i.added|date_local:$config->date_time->dt_full_format}</td>
		<td align="center"><a href="{url name='site1_blogfusion' action='themes'}?del_id={$i.id}">Delete</a></td>
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
var multibox={};
window.addEvent('domready', function(){ 
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});	
	$('upload').addEvent('click', function(e){
		e.stop();
		if($('theme_form').style.display == 'block')
			$('theme_form').style.display = 'none';
		else 
			$('theme_form').style.display = 'block'
	});	
	var optTips = new Tips('.screenshot');
	var optTips2 = new Tips('.tips');
	$$('.screenshot').each(function(el){ el.addEvent('click',function(e){ e.stop(); }); });
});	
</script>
{/literal}