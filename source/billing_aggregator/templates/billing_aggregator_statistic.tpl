<link rel="stylesheet" type="text/css" href="/skin/_js/jscalendar/css/jscal2.css" />
<link rel="stylesheet" type="text/css" href="/skin/_js/jscalendar/css/border-radius.css" />
<!--<link rel="stylesheet" type="text/css" href="/skin/_js/jscalendar/css/steel/steel.css" />-->
<script type="text/javascript" src="/skin/_js/jscalendar/js/jscal2.js"></script>
<script type="text/javascript" src="/skin/_js/jscalendar/js/unicode-letter.js"></script>
<script type="text/javascript" src="/skin/_js/jscalendar/js/lang/en.js"></script>

<link rel="stylesheet" href="/skin/_js/cerabox/style/cerabox.css" media="screen">
<script type="text/javascript" src="/skin/_js/cerabox/cerabox.js"></script>

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
		<br/>Period:&nbsp;
		<select class="elogin" style="width:150px;" id="with_period-filter">
			<option value=""> - all time- </option>
			<option value="today"{if isset($arrFilter.with_period) && $arrFilter.with_period =='today'}selected{/if}>today</option>
			<option value="yesterday"{if isset($arrFilter.with_period) && $arrFilter.with_period =='yesterday'}selected{/if}>yesterday</option>
			<option value="this_week"{if isset($arrFilter.with_period) && $arrFilter.with_period =='this_week'}selected{/if}>this week</option>
			<option value="last_week"{if isset($arrFilter.with_period) && $arrFilter.with_period =='last_week'}selected{/if}>last week</option>
			<option value="this_month"{if isset($arrFilter.with_period) && $arrFilter.with_period =='this_month'}selected{/if}>this month</option>
			<option value="last_month"{if isset($arrFilter.with_period) && $arrFilter.with_period =='last_month'}selected{/if}>last month</option>
			<option value="custom_date_selection"{if isset($arrFilter.with_period) && $arrFilter.with_period =='custom_date_selection'}selected{/if}>custom date selection</option>
		</select>
		<br/>
		<div id="custom_date_selection" {if !isset($arrFilter.with_period) || ( isset($arrFilter.with_period) && $arrFilter.with_period!='custom_date_selection' )}style="display:none;"{/if}>
			Start Date:&nbsp;
			<input type="text" value="{if isset($arrFilter.with_period_custom_a)}{$arrFilter.with_period_custom_a|date_format:$config->date_time->dt_full_format}{else}{$calendar_date_a|date_format:$config->date_time->dt_full_format}{/if}" id="with_period_custom_a" class="not_started completed meio medium-input text-input" data-meiomask="fixed.DateTime"    />
			<input type="hidden" name="arrPrj[start]"  value="{if isset($arrFilter.with_period_custom_a)}{$arrFilter.with_period_custom_a}{else}{$calendar_date_a}{/if}" id="with_period_custom_a-filter" />
			<img src="/skin/_js/jscalendar/img.gif" id="trigger-start" style="{if $arrPrj.flg_status == 3}display:none;{/if}cursor:pointer;" alt="" />
			
			End Date:&nbsp;
			<input type="text" value="{if isset($arrFilter.with_period_custom_b)}{$arrFilter.with_period_custom_b|date_format:$config->date_time->dt_full_format}{else}{$calendar_date_b|date_format:$config->date_time->dt_full_format}{/if}" id="with_period_custom_b" class=" medium-input text-input not_started completed meio" data-meiomask="fixed.DateTime" />
			<input type="hidden" name="arrPrj[end]"  value="{if isset($arrFilter.with_period_custom_b)}{$arrFilter.with_period_custom_b}{else}{$calendar_date_b}{/if}" id="with_period_custom_b-filter" />
			<img src="/skin/_js/jscalendar/img.gif" id="trigger-end" style="{if $arrPrj.flg_status == 3}display:none;{/if}cursor:pointer;" alt="" />
		</div>
		<br/><br/>
		<input type="submit" value="filter">
	</div>
</form>
<br/>
 {$arrStatistic.opt_in} - 
<a href="#new_optins" data-type="inline" class="popup-new_optins">New optins</a>
<div style="display: none;">
	<div id="new_optins">
		<table class="info glow" style="width:98%">
		<thead>
		<tr>
			<th>Aggregator</th>
			<th>Phone & User ID</th>
			<th>Client ID</th>
			<th>Revenue Currency</th>
			<th>Amount</th>
			<th>Service</th>
			<th>Transaction ID</th>
			<th>End User Price</th>
			<th>Country</th>
			<th>MNO</th>
			<th>MNO code</th>
			<th>Revenue</th>
			<th>Interval</th>
			<th>OptIn Channel</th>
			<th>Added</th>
		</tr>
		</thead>
		<tbody>
		{if count( $arrStatistic.opt_in_clients )>0}
		{foreach $arrStatistic.opt_in_clients as $v}
		<tr{if ($v@iteration-1) is div by 2} class="matros"{/if}>
			<th>{$v.aggregator}</th>
			<th>{$v.phone}</th>
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
		</tbody>
		</table>
	</div>
</div>
<br/>
{$arrStatistic.opt_out} - Unsubscribes
<br/>
{$arrStatistic.rebils} - Rebills (meaning success notification but not from new optins)
{if count( $arrStatistic.rebill_counter )>0}
{foreach $arrStatistic.rebill_counter as $rebills=>$count}
<br/>
{$rebills} times - {$count}
{/foreach}
<br/>Cumulative Rebills:
{foreach $arrStatistic.rebill_cumulate as $rebills=>$count}
<br/>
{$rebills} times - {$count}
{/foreach}
{/if}
<br/>
{$arrStatistic.closers} - Those who opted in and opted out (during the selected period).
{if $arrStatistic.strange_data_counter > 0}
<br/><br/>Strange datas:
{$arrStatistic.strange_data}
{/if}
{literal}
<script type="text/javascript">
$('sites-filter').addEvent('submit',function(e){
	e&&e.stop();
	['with_services','with_period','with_period_custom_a','with_period_custom_b'].toURI('-filter').go();
});
$('with_period-filter').addEvent('change',function(e){
	if( e.target.value == 'custom_date_selection' ){
		$('custom_date_selection').show();
	}else{
		$('custom_date_selection').hide();
	}
});
var end_calendar = Calendar.setup({
	trigger    : "trigger-end",
	inputField : "with_period_custom_b-filter",
	dateFormat: "%s",
	showTime : false,
	onSelect : function() {
		var date = new Date ();
		date.parse( $( 'with_period_custom_b-filter' ).get( 'value' ) * 1000 );
		$( 'with_period_custom_b' ).set( 'value',date.format('%d.%m.%Y %H:%M') );
		this.hide();
	}
});
var start_calendar = Calendar.setup({
	trigger    : "trigger-start",
	inputField : "with_period_custom_a-filter",
	dateFormat: "%s",
	showTime : false,
	selection : Date.parse(new Date()),
	onSelect : function() {
		var date = new Date ();
		date.parse( $( 'with_period_custom_a-filter' ).get( 'value' ) * 1000 );
		$( 'with_period_custom_a' ).set( 'value',date.format('%d.%m.%Y %H:%M') );
		var newdate = Calendar.intToDate(this.selection.get());
		end_calendar.args.min = newdate;
		end_calendar.args.selection = newdate;
		end_calendar.redraw();
		this.hide();
	}
});
new CeraBox( $$('.popup-new_optins'), {
	group: false,
	width:'70%',
	height:'70%',
	displayTitle: true,
	titleFormat: '{title}',
	events:{
		onOpen: function(currentItem, collection){
			$('cerabox').getChildren('.cerabox-content')[0].setStyle('overflow','scroll');
		}
	}
});
</script>
{/literal}
