<form method="post" action="" id="sites-filter">
	<input type="hidden" name="arrFilter[order]" value="{$arrFilter.order}" />
	<div style="margin-bottom:10px;">
		Service:&nbsp;
		<select class="elogin" style="width:150px;" id="with_services-filter">
			<option value=""> - show all - </option>
			<option value="0"{if isset($arrFilter.with_services) && $arrFilter.with_services=='0'}selected{/if}>Instaffiliate</option>
			<option value="1"{if isset($arrFilter.with_services) && $arrFilter.with_services =='1'}selected{/if}>Sweepstakes iPhone</option>
			<option value="2"{if isset($arrFilter.with_services) && $arrFilter.with_services =='2'}selected{/if}>Sweepstakes Macbook</option>
			<option value="3"{if isset($arrFilter.with_services) && $arrFilter.with_services =='3'}selected{/if}>Sweepstakes Amazon</option>
			<option value="4"{if isset($arrFilter.with_services) && $arrFilter.with_services =='4'}selected{/if}>Sweepstakes Ipad</option>
			<option value="5"{if isset($arrFilter.with_services) && $arrFilter.with_services =='5'}selected{/if}>Sweepstakes Prize</option>
		</select>
		<br/>Prepare Event Type:&nbsp;
		<select class="elogin" style="width:150px;" id="with_event_type-filter">
			<option value=""> - show all - </option>
			<option value="opt_in"{if isset($arrFilter.with_event_type) && $arrFilter.with_event_type=='opt_in'}selected{/if}>opt_in</option>
			<option value="opt_out"{if isset($arrFilter.with_event_type) && $arrFilter.with_event_type =='opt_out'}selected{/if}>opt_out</option>
			<option value="no_event"{if isset($arrFilter.with_event_type) && $arrFilter.with_event_type =='no_event'}selected{/if}>no_event</option>
		</select>
		<br/>Period:&nbsp;
		<select class="elogin" style="width:150px;" id="with_date-filter">
			<option value=""> - all period- </option>
			<option value="daily"{if isset($arrFilter.with_date) && $arrFilter.with_date =='daily'}selected{/if}>daily</option>
			<option value="weekly"{if isset($arrFilter.with_date) && $arrFilter.with_date =='weekly'}selected{/if}>weekly</option>
			<option value="monthly"{if isset($arrFilter.with_date) && $arrFilter.with_date =='monthly'}selected{/if}>monthly</option>
		</select>
		<br/>Aggregator:&nbsp;
		<select class="elogin" style="width:150px;" id="with_aggregator-filter">
			<option value=""> - all - </option>
			<option value="centili"{if isset($arrFilter.with_aggregator) && $arrFilter.with_aggregator =='centili'}selected{/if}>centili</option>
			<option value="txtnations"{if isset($arrFilter.with_aggregator) && $arrFilter.with_aggregator =='txtnations'}selected{/if}>txtnations</option>
			<option value="oxigen8"{if isset($arrFilter.with_aggregator) && $arrFilter.with_aggregator =='oxigen8'}selected{/if}>oxigen8</option>
		</select>
		<br/>Status:&nbsp;
		<select class="elogin" style="width:150px;" id="with_status-filter">
			<option value=""> - all - </option>
			<option value="failed"{if isset($arrFilter.with_status) && $arrFilter.with_status =='failed'}selected{/if}>failed</option>
			<option value="success"{if isset($arrFilter.with_status) && $arrFilter.with_status =='success'}selected{/if}>success</option>
			<option value="others"{if isset($arrFilter.with_status) && $arrFilter.with_status =='others'}selected{/if}>others</option>
		</select>
		<br/>
		<br/>Phone:&nbsp;
		<input class="elogin" style="width:150px;" id="with_phone-filter" value="{if isset($arrFilter.with_phone)}{$arrFilter.with_phone}{/if}">
		<br/>Client Id:&nbsp;
		<input class="elogin" style="width:150px;" id="with_clientid-filter" value="{if isset($arrFilter.with_clientid)}{$arrFilter.with_clientid}{/if}">
		<br/>Transaction Id:&nbsp;
		<input class="elogin" style="width:150px;" id="with_transactionid-filter" value="{if isset($arrFilter.with_transactionid)}{$arrFilter.with_transactionid}{/if}">
		<br/><br/>
		<input type="submit" value="filter">
	</div>
</form>
{literal}
<script type="text/javascript">
$('sites-filter').addEvent('submit',function(e){
	e&&e.stop();
	['with_services','with_transactionid','with_clientid','with_phone','with_status','with_aggregator','with_date','with_event_type'].toURI('-filter').go();
});
</script>
{/literal}
<table class="info glow" style="width:98%">
<thead>
<tr>
	<th>Service{include file="../../ord_backend.tpl" field='services'}</th>
	<th>Aggregator{include file="../../ord_backend.tpl" field='aggregator'}</th>
	<th>Phone{include file="../../ord_backend.tpl" field='phone'}</th>
	<th>User ID{include file="../../ord_backend.tpl" field='userid'}</th>
	<th>Status{include file="../../ord_backend.tpl" field='status'}</th>
	<th>Error Message{include file="../../ord_backend.tpl" field='errormessage'}</th>
	<th>Event Type{include file="../../ord_backend.tpl" field='event_type'}</th>
	<th>Client ID{include file="../../ord_backend.tpl" field='clientid'}</th>
	<th>Revenue Currency{include file="../../ord_backend.tpl" field='revenuecurrency'}</th>
	<th>Amount{include file="../../ord_backend.tpl" field='amount'}</th>
	<th>Service{include file="../../ord_backend.tpl" field='service'}</th>
	<th>Transaction ID{include file="../../ord_backend.tpl" field='transactionid'}</th>
	<th>End User Price{include file="../../ord_backend.tpl" field='enduserprice'}</th>
	<th>Country{include file="../../ord_backend.tpl" field='country'}</th>
	<th>MNO{include file="../../ord_backend.tpl" field='mno'}</th>
	<th>MNO code{include file="../../ord_backend.tpl" field='mnocode'}</th>
	<th>Revenue{include file="../../ord_backend.tpl" field='revenue'}</th>
	<th>Interval{include file="../../ord_backend.tpl" field='interval'}</th>
	<th>OptIn Channel{include file="../../ord_backend.tpl" field='opt_in_channel'}</th>
	<th>Added{include file="../../ord_backend.tpl" field='added'}</th>
</tr>
</thead>
<tbody>
	<tr>
		<td colspan="19">{include file="../../pgg_backend.tpl"}</td>
	</tr>
{if count( $arrList )>0}
{foreach $arrList as $v}
<tr{if ($v@iteration-1) is div by 2} class="matros"{/if}>
	<th>{if $v.services==0}Instaffiliate{elseif $v.services==1}Sweepstakes iPhone{elseif $v.services==2}Sweepstakes Macbook{/if}</th>
	<th>{$v.aggregator}</th>
	<th>{$v.phone}</th>
	<th>{$v.userid}</th>
	<th>{$v.status}</th>
	<th>{$v.errormessage}</th>
	<th>{$v.event_type}</th>
	<th>{$v.clientid}</th>
	<th>{$v.revenuecurrency}</th>
	<th>{$v.amount}</th>
	<th>{$v.service}</th>
	<th>{$v.transactionid}</th>
	<th>{$v.enduserprice}</th>
	<th>{$v.country}</th>
	<th>{$v.mno}</th>
	<th>{$v.mnocode}</th>
	<th>{$v.revenue}</th>
	<th>{$v.interval}</th>
	<th>{$v.opt_in_channel}</th>
	<td>{$v.added|date_local:$config->date_time->dt_full_format}</td>
</tr>
{/foreach}

{else}
	<tr>
		<td colspan="19">No billings</td>
	</tr>
{/if}
	<tr>
		<td colspan="19">{include file="../../pgg_backend.tpl"}</td>
	</tr>
</tbody>
</table>