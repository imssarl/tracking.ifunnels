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
		{if $i == '014'}Invalid Theme.<br/>{/if}
		{if $i == '002'}This theme is already exist.<br/>{/if}
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
					<option {if $smarty.get.arr.type=='search'}selected='1'{/if} value="search">term
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
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
{foreach from=$arrList.themes item=i name=x}
<td class="theme-item" align="left" valign="top">
	<div class="in">
	{if !empty($i->screenshot_url)}<img src="{$i->screenshot_url}" width="220" height="225" />{/if}
	<h1>{$i->name}</h1>
	<a href="#" rel="{$i->download_link}" class="download">Download</a> | <a href="{$i->preview_url}" target="_blank">Preview</a>
	<br/>
	<p>{$i->description}</p>
	<a href="#" class="details">Details</a>
	<div class="item-details" style="display:none;">
		<p><b>Version:</b> {$i->version}</p>
		<p><b>Author:</b> {$i->author}</p>
		<p><b>Last Updated:</b> {$i->last_updated}</p>
		<p><b>Downloaded:</b> {$i->downloaded} times</p>
		<div style="width:100px;height:10px;border:1px solid #000;"><div style="width:{$i->rating}%;height:10px;background:red;"></div></div>
	</div>
	</div>
</td>
{if $smarty.foreach.x.iteration%3==0}
</tr><tr>
{/if}
{/foreach}
</tr>
</table>
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
.theme-item{width:32%;  border:1px solid #AAA;}
.theme-item .in{padding:5px;}
</style>
<script type="text/javascript">
	window.addEvent('domready', function(){
		$$('.details').each(function(item){
			item.addEvent('click',function(e){ 
				e.stop();
				item.getNext('div').setStyle('display', (item.getNext('div').style.display=='block')?'none':'block' );
			});
		});
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