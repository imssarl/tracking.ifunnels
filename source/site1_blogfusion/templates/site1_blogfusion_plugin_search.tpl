<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{module name='site1' action='head'}
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<script type="text/javascript" src="/skin/_js/moopop.js"></script>
</head>
<body style="padding:10px;">
{if $msg == 'added'}
<p class="grn">Downloaded successfully</p> 
{/if}
{if isset($errorCode)}
<p><font color="Red">
{foreach from=$errorCode item=i}
		{if $i == '011'}Uploaded file size is more than 5MB.Please upload below 5MB.<br/>{/if}
		{if $i == '012'}Invalid file.Please upload only zip file.<br/>{/if}
		{if $i == '013'}Invalid zip file.<br/>{/if}
		{if $i == '014'}Invalid Plugin.<br/>{/if}
		{if $i == '002'}This plugin is already exist.<br/>{/if}
{/foreach}
</font></p>
{/if}
<form action="" method="GET" class="wh" style="width:80%;">
<fieldset>
	<legend></legend>
	<ol>
		<li>
			<label>Search:</label>
				<select style="width:50px;" name="arr[type]">
					<option {if $smarty.get.arr.type=='tag'}selected='1'{/if} value="tag">tag
					<option {if $smarty.get.arr.type=='term'}selected='1'{/if} value="term">term
					<option {if $smarty.get.arr.type=='author'}selected='1'{/if} value="author">author
				</select>&nbsp;
				<input type="text" name="arr[search]" value="{$smarty.get.arr.search}" />
		</li>
		<li>
			<label></label><input type="submit" value="Search" />
		</li>
	</ol>
</fieldset>
<fieldset></fieldset>
</form>
{if $arrList.info.results}
<div style="width:auto;float:right;padding:3px;">
<span>Total {$arrList.info.results} record{if $arrList.info.results>1}s{/if}</span>&nbsp;
{if $arrList.info.results > 21}
{section name=i loop=$arrList.info.pages}
 {if $smarty.section.i.iteration==$arrList.info.page} 
 	<span><b>{$smarty.section.i.iteration}</b></span>
 {else}
	<a href="?page={$smarty.section.i.iteration}{if !empty($smarty.get.arr)}&arr[type]={$smarty.get.arr.type}&arr[search]={$smarty.get.arr.search}{/if}" class="pg_handler">{$smarty.section.i.iteration}</a>
 {/if}
{/section}
{/if}
</div>
{/if}
{if $arrList.info.results}
<table width="100%" class="tb">
<tr>
	<th width="250">Name</th>
	<th width="80">Version</th>
	<th width="110">Rating</th>
	<th>Description</th>
	<th width="80" align="center">Actions</th>
</tr>
{foreach from=$arrList.plugins item=i name=x}
<tr{if $k%2=='0'} class="matros"{/if}>
	<td class="theme-item" align="left" valign="top">
		<a href="{$i->author_profile}" target="_blank">{$i->name}</a>
	</td>
	<td>{$i->version}</td>
	<td>
		<div style="width:100px;height:10px;border:1px solid #000;"><div style="width:{$i->rating}%;height:10px;background:red;"></div></div>
	</td>
	<td>
		{$i->description}
	</td>
	<td>
		<a href="#" rel="{$i->download_link}" class="download">Download</a>
	</td>
</tr>
{/foreach}
</table>
{/if}
{if $arrList.info.results}
<div style="width:auto;float:right;padding:3px;">
<span>Total {$arrList.info.results} record{if $arrList.info.results>1}s{/if}</span>&nbsp;
{if $arrList.info.results > 21}
{section name=i loop=$arrList.info.pages}
 {if $smarty.section.i.iteration==$arrList.info.page} 
 	<span><b>{$smarty.section.i.iteration}</b></span>
 {else}
	<a href="?page={$smarty.section.i.iteration}{if !empty($smarty.get.arr)}&arr[type]={$smarty.get.arr.type}&arr[search]={$smarty.get.arr.search}{/if}" class="pg_handler">{$smarty.section.i.iteration}</a>
 {/if}
{/section}
{/if}
</div>
{/if}
<br/>
<br/>
<form action="" method="POST" id="form-download">
<input type="hidden" name="arr[link]" id="download-link" />
</form>
{literal}
<style>
.tb ul{padding:2px 0 2px 15px;}
</style>
<script type="text/javascript">
	window.addEvent('domready', function(){
		$$('.download').each(function(item){ 
			item.addEvent('click',function(e){ 
				e.stop();
				$('download-link').value=item.rel;
				$('form-download').submit();
			});
		});
	});
</script>
{/literal}
</body>
</html>