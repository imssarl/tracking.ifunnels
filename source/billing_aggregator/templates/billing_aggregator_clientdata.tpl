<link rel="stylesheet" type="text/css" href="/skin/_js/jscalendar/css/jscal2.css" />
<link rel="stylesheet" type="text/css" href="/skin/_js/jscalendar/css/border-radius.css" />
<!--<link rel="stylesheet" type="text/css" href="/skin/_js/jscalendar/css/steel/steel.css" />-->
<script type="text/javascript" src="/skin/_js/jscalendar/js/jscal2.js"></script>
<script type="text/javascript" src="/skin/_js/jscalendar/js/unicode-letter.js"></script>
<script type="text/javascript" src="/skin/_js/jscalendar/js/lang/en.js"></script>

<form method="post" action="">
	<div style="margin-bottom:10px;">
		Client Ids:&nbsp;
		<br/>
		<textarea class="elogin" style="width:150px;" name="with_clientid">{if isset($arrFilter.with_clientid)}{$arrFilter.with_clientid}{/if}</textarea>
		<br/>
		Phone Numbres:&nbsp;
		<br/>
		<textarea class="elogin" style="width:150px;" name="with_phone">{if isset($arrFilter.with_phone)}{$arrFilter.with_phone}{/if}</textarea>
		<br/><br/>
		<input type="submit" value="filter">
	</div>
</form>
{if isset($arrStatistic)}
<br/>
{$arrStatistic.opt_in} - New optins
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
{if $arrStatistic.strange_data_counter > 0}
<br/><br/>Strange datas:
{$arrStatistic.strange_data}
{/if}
{/if}
