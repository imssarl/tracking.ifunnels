<div>
{if $arrLog}
	<table class="info glow" style="width:1000px;">
	<thead>
	<tr>
		<th>No.</th>
		<th>Email</th>
		<th>Date entered</th>
		<th>IP address (if known)</th>
		<th>Browser Info</th>
	</tr>
	</thead>
	{foreach from=$arrLog key=k item=v}
	<tr{if $k%2==0} class="matros"{/if}>
		<td>{$k}</td>
		<td>{$v.email}</td>
		<td>{if $v.added}{$v.added|date_format:$config->date_time->dt_date_format}{else}-{/if}</td>
		<td>{$v.ip}</td>
		<td>{$v.long_name} {$v.version}, js-{$v.javascript}, {$v.platform}-{$v.os}</td>
	</tr>
	{/foreach}
	<tfoot>
		<tr><td colspan="5">{include file="../../pgg_backend.tpl"}</td></tr>
	</tfoot>
	</table>
{else}
<div style="float:left;width:100%;">
	<div class="red" style="margin:80px auto;text-align:center;"><b>no events exist</b></div>
</div>
{/if}
</div>