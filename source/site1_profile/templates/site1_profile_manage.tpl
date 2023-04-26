<br/>
{if $msg == 'delete'}
<div class="grn">Profile has been deleted</div>
{elseif $msg=='delete_error'}
<div class="red">Profile can't be deleted</div>
{elseif $msg=='duplicated'}
<div class="grn">Duplicate Profile has been created</div>
{elseif $msg=='duplicated_error'}
<div class="grn">Duplicate Profile can't be created</div>
{elseif $msg=='created'}
<div class="grn">Profile has been created</div>
{elseif $msg=='saved'}
<div class="grn">Profile has been saved</div>
{/if}
{if $error}
<div class="red">{$error}</div>
{/if}
<p>
	<input type="submit" value="Delete" id="delete" />
</p>
<form action="" id="current-form" method="post">
<input type="hidden" name="mode" value="" id="mode" />
<table style="width:100%">
	<thead>
	<tr>
		<th style="padding-right:0;" width="1px"><input type="checkbox" id="del" class="tooltip" title="mass delete" rel="check to select all" /></th>
		<th>Title{if count($arrList)>1}
			{if $arrFilter.order!='profile_name--up'}<a href="{url name='site1_profile' action='manage' wg='order=profile_name--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='profile_name--dn'}<a href="{url name='site1_profile' action='manage' wg='order=profile_name--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>First name{if count($arrList)>1}
			{if $arrFilter.order!='first_name--up'}<a href="{url name='site1_profile' action='manage' wg='order=first_name--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='first_name--dn'}<a href="{url name='site1_profile' action='manage' wg='order=first_name--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Last name{if count($arrList)>1}
			{if $arrFilter.order!='last_name--up'}<a href="{url name='site1_profile' action='manage' wg='order=last_name--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='last_name--dn'}<a href="{url name='site1_profile' action='manage' wg='order=last_name--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Email{if count($arrList)>1}
			{if $arrFilter.order!='email--up'}<a href="{url name='site1_profile' action='manage' wg='order=email--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='email--dn'}<a href="{url name='site1_profile' action='manage' wg='order=email--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Added{if count($arrList)>1}
			{if $arrFilter.order!='date_created--up'}<a href="{url name='site1_profile' action='manage' wg='order=date_created--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='date_created--dn'}<a href="{url name='site1_profile' action='manage' wg='order=date_created--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th width="10%">Options</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arrList item='v' key='k'}
	<tr{if $k%2!='0'} class="matros"{/if}>
		<td style="padding-right:0;"><input type="checkbox" name="del[{$v.id}]" class="check-me-del" id="check-{$v.id}" /></td>
		<td>&nbsp;{$v.profile_name}</td>
		<td>{$v.first_name}</td>
		<td>{$v.last_name}</td>
		<td>{if $v.email}{mailto address=$v.email encode="javascript"}{/if}</td>
		<td>{$v.date_created}</td>
		<td align="center">
			<a href="{url name='site1_profile' action='edit'}?id={$v.id}"><img title="Edit" src="/skin/i/frontends/design/buttons/edit.png" /></a>
			<a href="{url name='site1_profile' action='manage'}?dup={$v.id}"><img title="Duplicate" src="/skin/i/frontends/design/buttons/duplicate.png" /></a>
			<a href="#" rel="{$v.id}" class="click-me-del" id="{$v.id}"><img title="Delete" src="/skin/i/frontends/design/buttons/delete.png" /></a>
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