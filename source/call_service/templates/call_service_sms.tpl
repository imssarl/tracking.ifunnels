
<table class="info glow" style="width:98%">
<thead>
<tr>
	<th>User{include file="../../ord_backend.tpl" field='email'}</th>
	<th width="200">Text</th>
	<th width="100">Direction{include file="../../ord_backend.tpl" field='Direction'}</th>
	<th width="100">Status{include file="../../ord_backend.tpl" field='SmsStatus'}</th>
	<th width="100">Cost{include file="../../ord_backend.tpl" field='cost'}</th>
	<th width="150">Added{include file="../../ord_backend.tpl" field='added'}</th>
	<th width="150">Edited{include file="../../ord_backend.tpl" field='edited'}</th>
	<th width="100">Options</th>
</tr>
</thead>
<tbody>
	<tr>
		<td colspan="7">{include file="../../pgg_backend.tpl"}</td>
	</tr>
{foreach $arrList as $v}
<tr{if ($v@iteration-1) is div by 2} class="matros"{/if}>
	<td>{$v.user_id}</td>
	<td>{$v.Body}</td>
	<td>{$v.Direction}</td>
	<td>{$v.SmsStatus}</td>
	<td>{$v.cost}</td>
	<td>{$v.added|date_local:$config->date_time->dt_full_format}</td>
	<td>{$v.edited|date_local:$config->date_time->dt_full_format}</td>
	<td class="option">
		<a href="{url name='call_service' action='sms' wg="del={$v.id}"}">del</a>
	</td>
</tr>
{/foreach}
	<tr>
		<td colspan="7">{include file="../../pgg_backend.tpl"}</td>
	</tr>
</tbody>
</table>