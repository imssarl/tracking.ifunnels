<div><a href="{Core_Module_Router::$uriFull}?backup_sys=1">archiving of system data</a></div>
<form method="post" action="">
	<input type="text" name="user_id" value="" />
	<input type="submit" name="4tracker" value="Backup Tracker User" />
</form>
<div style="width:90%;">
<table>
	<tr>
		<td>
	<form method="post" action="" name="g_set" id="g_set">
	<table width="100%">
	<tr>
		<td colspan="3" class="for_checkbox">
			<b>Select table</b> 
			<span>(</span><label for="g_sel_all">select all</label>
			<input type="checkbox" onClick="toggle_checkbox('g_set',this);" id="g_sel_all" /><span>):</span>
		</td>
	</tr>
	{foreach from=$arrTables key='k' item='v'}
		{if $k%3==0}
	</tr>
	<tr>
		{/if}
		<td class="for_checkbox">
			<input type="checkbox" name="arrSet[tables][{$k}]" value="{$v}" id="g_{$k}">
			<label for="g_{$k}" style="width:130px;"><a href="{url name='configuration' action='view_table'}?table={$v}" title="View table: {$v}" rel="width:1000,height:550" class="mb">{$v|truncate:19}</a></label>
		</td>
	{/foreach}
	</table>
<div style="width:90%;text-align:center;clear:both;padding-top: 20px;"><a href="#" onclick="g_set.submit();return false;">archive</a></div>
	</form>
		</td>
	{if $arrDumps}
		<td valign="top">
	<div style="float:right;">
	<table>
	<tr>
		<td colspan="5">
			<b>Backup</b> 
		</td>
	</tr>
	{foreach from=$arrDumps key='k' item='v'}
	<tr>
		<td><a href="{$smarty.const.HTML_DB_BACKUP}{$v.name}" title="download file ({$v.size} byte)" target="_blank">{$v.name}</a></td>
		<td>{$v.frendly_size}</td>
		<td>{if strpos({$v.name},'user_')===false}<a href="{Core_Module_Router::$uriFull}?restore={$v.name}" title="restore dump">restore</a>{/if}</td>
		<td>{$v.date|date_local:$config->date_time->dt_full_format}</td>
		<td><a href="{Core_Module_Router::$uriFull}?delete={$v.name}" title="delete file">X</a></td>
	</tr>
	{/foreach}
	</table>
	</div>
		</td>
	{/if}
	</tr>
</table>
</div>
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script type="text/javascript">
var multibox = {};
window.addEvent( 'domready', function() {
	multibox = new multiBox( {
		mbClass: '.mb',
		container: $( document.body ),
		useOverlay: true,
		nobuttons: true
	} );	
} );
</script>
{/literal}