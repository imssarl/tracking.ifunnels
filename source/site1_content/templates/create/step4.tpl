<h3 class="toggler" >Scheduling</h3>
<div class="element initElement">
	<fieldset>
		<legend></legend>
		<ol>
			<li class="contents_automat" style="display:{if $arrPrj.flg_mode == 'automat'};{else}none;{/if}">
				<label>Post New Content every days: <em>*</em></label>
					<input value="{$arrPrj.post_every}" type="text" name="arrPrj[post_every]" />
					<p></p>
				<label>Posts number: <em>*</em></label>
					<input value="{$arrPrj.post_num}" type="text" name="arrPrj[post_num]" />
			</li>
			<li class="contents_manual" style="display:{if $arrPrj.flg_mode == 'manual'||empty($arrPrj.flg_mode)};{else}none;{/if}">
				<label>Time in between each posts: </label>
					<input value="{$arrPrj.time_between}" type="text" name="arrPrj[time_between]" />
					<p></p>
				<label>Random factor: </label>
					<input value="{$arrPrj.random}" type="text" name="arrPrj[random]" />
			</li>
			<li>
				<label>Start Date: </label>
					<input type="text" readonly="readonly" value="{if $arrPrj.start}{$arrPrj.start|date_format:"%Y-%m-%d %H:%M"}{else}{$smarty.now|date_format:"%Y-%m-%d %H:%M"}{/if}" id="view-date-start" />
					<input type="hidden" name="arrPrj[start]"  value="{if !empty($arrPrj.start)}{$arrPrj.start}{else}{$smarty.now}{/if}" id="date-start" />
					<img src="/skin/_js/jscalendar/img.gif" id="trigger-start" style="{if $arrPrj.flg_status == 1}display:none;{/if}cursor:pointer;" alt="" />
			</li>
			<li>
				<label>End Date: </label>
					<input type="text" readonly="readonly" value="{if $arrPrj.end}{$arrPrj.end|date_format:"%Y-%m-%d %H:%M"}{/if}" id="view-date-end" />
					<input type="hidden" name="arrPrj[end]"  value="{if !$arrPrj.end}{$smarty.now}{else}{$arrPrj.end}{/if}" id="date-end" />
					<img src="/skin/_js/jscalendar/img.gif" id="trigger-end" style="{if $arrPrj.flg_status == 1}display:none;{/if}cursor:pointer;" alt="" />
			</li>
			<li>
				<a href="#" class="acc_prev">Prev step</a> <span class="nonet" >/ <a href="#" class="acc_next">Next step</a></span>
			</li>
		</ol>
	</fieldset>
</div>
{literal}
<script type="text/javascript">
window.addEvent( 'domready', function() {
    Calendar.setup( {
        inputField     :    "date-start",
        ifFormat       :    "%s",
        showsTime      :    true,
        button         :    "trigger-start",
        step           :    0,
        
        onUpdate : function() {
			var date = new Date ();
			date.parse( $( 'date-start' ).get( 'value' ) * 1000 );
        	$( 'view-date-start' ).set( 'value',date.format('%Y-%m-%d %H:%M') );
        }
    } ); 
	
    Calendar.setup( {
        inputField     :    "date-end",
        ifFormat       :    "%s",
        showsTime      :    true,
        button         :    "trigger-end",
        step           :    0,
        
        onUpdate : function() {
			var date = new Date ();
			date.parse( $( 'date-end' ).get( 'value' ) * 1000 );
        	$( 'view-date-end' ).set( 'value',date.format('%Y-%m-%d %H:%M') );
        }
    } ); 
} );
</script>
{/literal}