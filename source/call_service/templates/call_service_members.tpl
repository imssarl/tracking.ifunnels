<form action="" method="get">
	<div style="padding: 0 0 10px 0;">
		Group: <select class="elogin" style="width:150px;" name="arrFilter[group_id]">
			<option value="">-select group-</option>
			{html_options options=$arrGroups selected=$smarty.get.arrFilter.group_id}
		</select>
		Package: <select class="elogin" style="width:150px;" name="arrFilter[package_id]">
			<option value="">-select package-</option>
			{html_options options=$arrPack selected=$smarty.get.arrFilter.package_id}
		</select>
		&nbsp;
		Nickname: <input class="elogin"  style="width:150px;" type="text" name="arrFilter[search][nickname]" value="{$smarty.get.arrFilter.search.nickname}" />
		Email: <input class="elogin"  style="width:150px;" type="text" name="arrFilter[search][email]" value="{$smarty.get.arrFilter.search.email}" /><input type="submit" value="Search">
	</div>
</form>
<form method="post" action="" id="users-filter">



<table class="info glow" style="width:98%">
<thead>
<tr>
	<th>Nickname{include file="../../ord_backend.tpl" field='nickname'}</th>
	<th>Calls outbound{include file="../../ord_backend.tpl" field='outbound_voice'}</th>
	<th>Calls inbound{include file="../../ord_backend.tpl" field='inbound_voice'}</th>
	<th>Calls cost{include file="../../ord_backend.tpl" field='voice_cost'}</th>
	<th>SMS outbound{include file="../../ord_backend.tpl" field='outbound_sms'}</th>
	<th>SMS inbound{include file="../../ord_backend.tpl" field='inbound_sms'}</th>
	<th>SMS cost{include file="../../ord_backend.tpl" field='sms_cost'}</th>
	<th width="120">Registered{include file="../../ord_backend.tpl" field='added'}</th>
	<th width="100">Options</th>
</tr>
</thead>
<tbody>
{foreach $arrList as $v}
<tr{if ($v@iteration-1) is div by 2} class="matros"{/if}>
	<td>{$v.id}</td>
	<td>{$v.outbound_voice}</td>
	<td>{$v.inbound_voice}</td>
	<td>{$v.voice_cost}</td>
	<td>{$v.outbound_sms}</td>
	<td>{$v.inbound_sms}</td>
	<td>{$v.sms_cost}</td>
	<td>{$v.added|date_local:$config->date_time->dt_full_format}</td>
	<td class="option">
		<a href="{url name='members' action='set' wg="id={$v.id}"}" target="_blank">edit</a> |
		<a href="{url name='members' action='manage'}?auth={Core_Payment_Encode::encode($v.id)}" target="_blank">login</a>
	</td>
</tr>
{/foreach}
	<tr>
		<td colspan="7">{include file="../../pgg_backend.tpl"}</td>
	</tr>
</tbody>
</table>
</form>
<script>
window.addEvent('domready',function(){
	checkboxFullToggle($('sel'));
	$('go').addEvent('click',function(e){
		if( $('go-action').get('value')=='delete'&&!confirm('WARNING! All data will be deleted!') ){
			e.stop();
			return false;
		}
		if( $('go-action').get('value')=='delete'&&!confirm('You are sure? We can\'t recover the data!') ){
			e.stop();
			return false;
		}
	});
	$$('.resend').addEvent('click',function(e){
		if( !confirm('Change password and send email to user?')){
			e.stop();
			return false;
		}
	});
});
</script>