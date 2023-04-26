{if $msg == 'delete'}<div class="grn">Delete successfully.</div>{/if}
{if $msg == 'stored'}<div class="grn">Checked and saved successfully.</div>{/if}
{if $msg == 'changed'}<div class="grn">Checked and changed successfully.</div>{/if}
{if $msg == 'error'}<div class="red">Error. Can't FTP details.</div>{/if}
{if $msg == 'import_error'}<div class="red">Error. Can't import FTP details. Check file format.</div>{/if}
{if $arrErr.exists}<div class="red">This FTP account already exists</div>{/if}
{if $arrErr.connect}<div class="red">Can't connect to FTP server ({$arrFtp.ftp_username}:{$arrFtp.ftp_password}@{$arrFtp.ftp_address})</div>{/if}
<a href="#" id="toggle" rel="block">New FTP details</a>&nbsp;&nbsp;&nbsp;<a href="#" id="import-toggle" rel="block">Import FTP details</a>

<div id="import" style="display:none;">
<form action="" class="wh" method="POST" enctype="multipart/form-data"  style="width:50%;">
	<fieldset>
		<legend></legend>
		<ol>
			<li>
				<label>Text file </label><input type="file" name="ftp"/>
			</li>
			<li>
				<label></label><input type="submit" value="Import" >
			</li>
		</ol>
	</fieldset>
</form>
</div>
<div id="section"{if !($arrFtp.id||$arrErr)} style="display:none;"{/if}>
<form class="wh" enctype="multipart/form-data" action="" method="POST" style="width:50%;">
<input type="hidden" name="arrFtp[id]" value="{$arrFtp.id}" />
	<fieldset>
		<legend>{if $arrFtp.id}Edit FTP details{else}Add new FTP{/if}</legend>
		<ol>
			<li>
				<label>Address <em>*</em></label><input class="required {if $arrErr.ftp_address}error{/if}" title="Address" value="{$arrFtp.ftp_address|escape}" type="text" name="arrFtp[ftp_address]" />
			</li>
			<li>
				<label>Username <em>*</em></label><input class="required {if $arrErr.ftp_username}error{/if}" title="Address" value="{$arrFtp.ftp_username|escape}" type="text" name="arrFtp[ftp_username]" />
			</li>
			<li>
				<label>Password <em>*</em></label><input class="required {if $arrErr.ftp_password}error{/if}" title="Address" value="{$arrFtp.ftp_password|escape}" type="text" name="arrFtp[ftp_password]" />
			</li>
			<li>
				<label></label><input type="submit" value="Check & {if $arrFtp.id}change{else}save{/if}">
			</li>
		</ol>
	</fieldset>
</form>
</div>
<p>
	<input type="submit" value="Delete" id="delete" />
</p>
<form action="" id="current-form" method="post">
<input type="hidden" name="mode" value="" id="mode" />
<table style="width:80%">
	<thead>
	<tr>
		<th style="padding-right:0;" width="1px"><input type="checkbox" id="del" class="tooltip" title="mass delete" rel="check to select all" /></th>
		<th>Address{if count($arrList)>1}
			{if $arrFilter.order!='ftp_address--up'}<a href="{url name='ftp_tools' action='manage' wg='order=ftp_address--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='ftp_address--dn'}<a href="{url name='ftp_tools' action='manage' wg='order=ftp_address--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Username{if count($arrList)>1}
			{if $arrFilter.order!='ftp_username--up'}<a href="{url name='ftp_tools' action='manage' wg='order=ftp_username--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='ftp_username--dn'}<a href="{url name='ftp_tools' action='manage' wg='order=ftp_username--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Password{if count($arrList)>1}
			{if $arrFilter.order!='ftp_password--up'}<a href="{url name='ftp_tools' action='manage' wg='order=ftp_password--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='ftp_password--dn'}<a href="{url name='ftp_tools' action='manage' wg='order=ftp_password--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th width="30%">Options</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arrList item='v' key='k'}
	<tr{if $k%2!='0'} class="matros"{/if}>
		<td style="padding-right:0;"><input type="checkbox" name="del[{$v.id}]" class="check-me-del" id="check-{$v.id}" /></td>
		<td>&nbsp;{$v.ftp_address}</td>
		<td>{$v.ftp_username}</td>
		<td>{$v.ftp_password}</td>
		<td align="center">
			<a href="{url name='ftp_tools' action='manage'}?id={$v.id}">Edit</a> | 
			<a href="#" rel="{$v.id}" class="click-me-del" id="{$v.id}">Delete</a>
		</td>
	</tr>	
	{/foreach}
	</tbody>
</table>
</form>
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>

{literal}
<script>
window.addEvent('domready',function(){
	$('toggle').addEvent('click', function(e){
		e.stop();
		$('section').toggle();
	});
	$('import-toggle').addEvent('click', function(e){
		e.stop();
		$('import').toggle();
	});
		
	checkboxToggle($('del'));
	$('delete').addEvent('click',function(e){
		e && e.stop();
		if (!$$('.check-me-del').some(function(item){
			return item.checked==true;
		})) {
			alert( 'Please, select one checkbox at least' );
			return;
		}
		if(!confirm('Your sure to delete selected items?')) {
			return;
		}
		$('mode').set('value','delete');
		$('current-form').submit();
	});
	$$('.click-me-del').addEvent('click',function(e){
		e && e.stop();
		var el='check-'+this.get('id');
		if ( !$(el).get('checked') ) {
			$(el).set('checked',true);
			if ($(el).get('checked')) {
				$('delete').fireEvent('click');
			}
			$(el).set('checked',false);
		}
	});
});
</script>
{/literal}