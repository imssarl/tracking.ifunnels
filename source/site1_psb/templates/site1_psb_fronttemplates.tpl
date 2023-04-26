
{if isset($errorCode)}
<p><font color="Red">
{foreach from=$errorCode item=i}
		{if $i == '011'}Uploaded file size is more than 2MB.Please upload below 2MB.<br/>{/if}
		{if $i == '012'}Invalid file.Please upload only zip file.<br/>{/if}
		{if $i == '013'}Invalid zip file.<br/>{/if}
		{if $i == '014'}Invalid Theme.<br/>{/if}
		{if $i == '002'}This theme is already exist.<br/>{/if}
{/foreach}
</font></p>
{/if}
{if $msg == 'saved'}
<p class="grn">Template has been saved successfully</p> 
{/if}
{if $msg == 'copy'}
<p class="grn">Template has been duplicated successfully</p> 
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

<a href="#" id="upload" rel="block">Upload New Theme</a>&nbsp;|&nbsp;
<a href="{url name='site1_psb' action='fronttemplates' wg='restore=default'}" rel="<font color='red'>Note:</font> it won't affect the themes you uploaded" class="tips">Restore default themes</a>

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
<form  id="copy_form" style="display:none; width:50%;" action="" method="POST" class="wh">
<input type="hidden" name="arrCopy[id]" id="copy_id">
<fieldset>
	<legend>Copy template</legend>
	<ol>
		<li>
			<label>Old Name:</label><b id="old_name"></b>
		</li>
		<li>
			<label>New Name <em>*</em> </label><input type="text" name="arrCopy[name]" id="copy_new_name"/>
		</li>
		<li>
			<label></label><input type="submit" name="copy" value="Copy">
		</li>
	</ol>
</fieldset>
</form>
{if $arrList}
<table width="100%">
	<tr>
		<th>Title
		{if count($arrList)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_psb'  action='fronttemplates' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_psb'  action='fronttemplates' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}		
		</th>
		<th width="24%">Description</th>
		<th width="25%">Installed on URLs</th>
		<th width="12%">Added
		{if count($arrList)>1}
			{if $arrFilter.order!='added--up'}<a href="{url name='site1_psb'  action='fronttemplates' wg='order=added--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='added--dn'}<a href="{url name='site1_psb'  action='fronttemplates' wg='order=added--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}		
		</th>
		<th width="15%">Action</th>
	</tr>
	{foreach from=$arrList item=i}
	<tr>
		<td>{if $i.url}<a href="{$i.url}" target="_blank">{/if}{$i.title}{if $i.url}</a>{/if} {if $i.preview}(<a href="#" class="screenshot" rel="<img src='{$i.preview}'>" style="text-decoration:none">preview</a>){/if}</td>
		<td>{$i.description}</td>
		<td>
		{foreach from=$arrSites item=j}
		{if $j.template_id == $i.id}
		<a href="{$j.url}" target="_blank">{$j.url}</a><br/>
		{/if}
		{/foreach}
		</td>
		<td align="center">{$i.added|date_local:$config->date_time->dt_full_format}</td>
				<td align="center">
		{if $i.flg_belong==1}
		<a href="{url name='site1_psb' action='fronttemplates'}?delete={$i.id}">Del</a> |
		<a href="{url name='site1_psb' action='edit_templates'}?id={$i.id}" >Edit</a> |
		{/if}
		<input type="hidden" id="title_{$i.id}" value="{$i.title}">
		<a href="#" rel="{$i.id}" class="copy-link" >Copy</a>
		</td>
	</tr>	
	{/foreach}	
</table>
<br/>
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>
{literal}
<script type="text/javascript">

window.addEvent('domready', function(){
	$$('.copy-link').each(function( a ){
		a.addEvent('click', function( e ){
			e && e.stop();
			$('copy_form').setStyle('display','block');
			$('copy_id').value = a.rel;
			$('old_name').set('html', $('title_'+a.rel).value );
		});
	});
	$('copy_form').addEvent('submit', function(e){
		if( !$chk($('copy_new_name').value) ){
			e && e.stop();
			r.alert('Error','Field "New name" can not be empty','roar_error');
		}
	});
	$('upload').addEvent('click', function(e){
		e.stop();
		if($('theme_form').style.display == 'block')
			$('theme_form').style.display = 'none';
		else 
			$('theme_form').style.display = 'block'
	});
	
	var optTips2 = new Tips('.tips');
	var optTips = new Tips('.screenshot');
	$$('.screenshot').each(function(el){ el.addEvent('click',function(e){ e.stop(); }); });
});	
</script>
{/literal}
{else}
	<p>no templates found</p>
{/if}