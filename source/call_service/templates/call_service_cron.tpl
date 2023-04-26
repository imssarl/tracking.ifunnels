
<table class="info glow" style="width:98%">
<thead>
<tr>
	<th>User{include file="../../ord_backend.tpl" field='email'}</th>
	<th width="100">Status{include file="../../ord_backend.tpl" field='flg_status'}</th>
	<th width="150">Action{include file="../../ord_backend.tpl" field='action'}</th>
	<th width="120">Start{include file="../../ord_backend.tpl" field='start'}</th>
	<th width="120">Added{include file="../../ord_backend.tpl" field='added'}</th>
	<th width="120">Edited{include file="../../ord_backend.tpl" field='edited'}</th>
	<th width="50">Options</th>
</tr>
</thead>
<tbody>
	<tr>
		<td colspan="7">{include file="../../pgg_backend.tpl"}</td>
	</tr>
{foreach $arrList as $v}
<tr{if ($v@iteration-1) is div by 2} class="matros"{/if}>
	<td>{$v.id}</td>
	<td>{if $v.flg_status==0}Not started{elseif $v.flg_status==1}In progress{elseif $v.flg_status==2}Completed{elseif $v.flg_status==3}Error{/if}</td>
	<td>
		{if $v.action==Project_Ccs_Arrange::ACTION_CALL_CONFIRM}Call: Confirm Phone
		{elseif $v.action==Project_Ccs_Arrange::ACTION_CALL_CREATE_SITE}Call: Create Sites
		{elseif $v.action==Project_Ccs_Arrange::ACTION_CALL_BALANCE}Call: Balance
		{/if}
	</td>
	<td>{$v.start|date_local:$config->date_time->dt_full_format}</td>
	<td>{$v.added|date_local:$config->date_time->dt_full_format}</td>
	<td>{$v.edited|date_local:$config->date_time->dt_full_format}</td>
	<td class="option">
		<a href="{url name='call_service' action='cron' wg="del={$v.id}"}">del</a>
	</td>
</tr>
{/foreach}
	<tr>
		<td colspan="7">{include file="../../pgg_backend.tpl"}</td>
	</tr>
</tbody>
</table>