<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{module name='site1' action='head'}
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<script type="text/javascript" src="/skin/_js/xlib.js"></script>
	{*r.alert*}
	<link rel="stylesheet" type="text/css" href="/skin/_js/roar/roar.css" />
	<script type="text/javascript" src="/skin/_js/roar/roar.js"></script>
	<script type="text/javascript">
	{literal}
		var r=new Roar();
		img_preload(['/skin/_js/roar/roar.png']);
	{/literal}
	</script>
</head>
<body style="padding:10px;">
{if $arrDirs}
<div>path: {$strCurrentDir}</div>
<div id="show_div"><a href="#" id="show">create folder here</a></div>
<div style="display:none;" id="create_div"><form method="post" action="" id="create_form">
	<input type="text" id="new_folder" name="new_folder" value="{$strFolder}" class="required" />&nbsp;
	<a href="#" onclick="$('create_form').fireEvent('submit');">create</a>
</form></div>
<table style="width:100%;">
<thead>
<tr>
	<th></th>
	<th>Name</th>
	<th>Files Inside</th>
	<th>User/Group</th>
	<th>Date</th>
</tr>
</thead>
<tbody>
{foreach from=$arrDirs key='k' item='v'}
<tr{if $k%2=='0'} class="matros"{/if}>
{if $v.name=='.'}
	<td><img src="/skin/i/frontends/design/buttons/folder.png"{if $smarty.get.mode!='with_files'} title="Click here to select" id="{$strCurrentDir}" class="select"{/if} /></td>
	<td>{$v.name}</td>
	<td>current folder</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
{elseif $v.name=='..'}
	<td>{if $strCurrentDir!='/'}<a href="{url name='ftp_tools' action='browse' wg=$strPrevDir}" title="Click here to go back">{/if}<img src="/skin/i/frontends/design/buttons/folder.png" title="Click here to go back" /></a></td>
	<td>{if $strCurrentDir!='/'}<a href="{url name='ftp_tools' action='browse' wg=$strPrevDir}" title="Click here to go back">{/if}{$v.name}</a></td>
	<td>parent folder</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
{elseif $v.is_dir}
	<td><img src="/skin/i/frontends/design/buttons/folder.png"{if $smarty.get.mode!='with_files'} title="Click here to select" id="{$strCurrentDir}{$v.name}/" class="select"{/if} /></td>
	<td><a href="{$strUrl}{$v.name}/" title="Click here to view subfolder(s)">{$v.name}</a></td>
	<td>{$v.files_inside}</td>
	<td>{$v.user}/{$v.group}</td>
	<td>{$v.stamp|date_format:$config->date_time->dt_full_format}</td>
{elseif $v.type=='-'}
	<td><img src="/skin/i/frontends/design/buttons/file.png" title="Select {$v.name} file" id="{$strCurrentDir}{$v.name}" class="select" /></td>
	<td><a href="#" title="Select {$v.name} file" id="{$strCurrentDir}{$v.name}" class="select">{$v.name|ellipsis:"15"}</a></td>
	<td>{$v.files_inside}</td>
	<td>{$v.user}/{$v.group}</td>
	<td>{$v.stamp|date_format:$config->date_time->dt_full_format}</td>
{else}
	<td><img src="/skin/i/frontends/design/buttons/link.png" title="{$v.name} is not Regular file" /></td>
	<td><a href="#" onclick="r.alert( 'Information', '{$v.name} is not Regular file', 'roar_information' ); return false;" title="{$v.name} is not Regular file">{$v.name|ellipsis:"15"}</a></td>
	<td>{$v.files_inside}</td>
	<td>{$v.user}/{$v.group}</td>
	<td>{$v.stamp|date_format:$config->date_time->dt_full_format}</td>
{/if}
</tr>
{/foreach}
</tbody>
</table>
<script type="text/javascript">
{literal}
window.addEvent('domready', function(){
	$each($$('.select'),function(el){
		el.addEvent('click',function(e){
			e.stop();
			window.parent.placePath( this.id );
			window.parent.multibox.close();
		})
	});
	$('show').addEvent('click',function(e){
		e.stop();
		$('show_div').style.display='none';
		$('create_div').style.display='block';
		$('new_folder').focus();
	});
	var isEmpty = new InputValidator('required', {
		errorMsg: 'This field is required.',
		test: function(field){
			return ((field.get('value') == null) || (field.get('value').length == 0));
		}
	});
	var send=false;
	$('create_form').addEvent('submit',function(e){
		e && e.stop();
		if( send ) {
			return;
		}
		if ( isEmpty.test($("new_folder")) ) {
			r.alert( 'Client side error', 'Fill folder name field', 'roar_error' );
			return;
		}
		send=true;
		this.submit();
	});
});
{/literal}
</script>
{else}
	<div>{if $arrErrors}Error occurred:{foreach from=$arrErrors item='v'}<br />{$v}{/foreach}{else}Unknown error. Please check FTP address{/if}</div>
{/if}
</body>
</html>