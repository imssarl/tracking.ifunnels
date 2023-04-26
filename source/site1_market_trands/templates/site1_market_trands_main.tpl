<form action="" method="POST" class="wh" style="width:70%;">
<fieldset>
	<legend>Search terms</legend>
	<ol>
		<li>
		<label><span>Enter your keyword <em>*</em>:</span></label>
		<textarea name="keywords"  id="keywords" style="height:27px">{if !$smarty.post.keywords}Example: adobe photoshop cs4{else}{$smarty.post.keywords}{/if}</textarea>
		</li>
	</ol>
</fieldset>
<fieldset>
	<legend>Filters</legend>
	<ol>
		<li>
			<label><span>Scope:</span></label>
			<select name="scope">
				<option {if $smarty.post.scope == 'empty' || !$smarty.post.scope}selected='1'{/if} value="empty">Web search
				<option {if $smarty.post.scope == 'images'}selected='1'{/if} value="images">Images search
				<option {if $smarty.post.scope == 'news'}selected='1'{/if} value="news">News search
				<option {if $smarty.post.scope == 'froogle'}selected='1'{/if} value="froogle">Product search
			</select>
			
		</li>
		<li>
			<label><span>Date:</span></label>
			<select name="time">
				<option {if $smarty.post.time == '7-d'}selected='1'{/if}  value="7-d">Last 7 days
				<option {if $smarty.post.time == '1-m'}selected='1'{/if}  value="1-m">Last 30 days
				<option {if !$smarty.post.time || $smarty.post.time == '3-m'}selected='1'{/if}  value="3-m">Last 90 days
				<option {if $smarty.post.time == '12-m'}selected='1'{/if}  value="12-m">Last year
			</select>
		</li>
		<li>
			<input id="display" type="submit" value="Display Trends">
		</li>
	</ol>
</fieldset>
{if $google_display}
<fieldset>
	<legend>{if $smarty.post.scope == 'empty'}Web&nbsp;search
		{elseif $smarty.post.scope == 'images'}Images&nbsp;search
		{elseif $smarty.post.scope == 'news'}News&nbsp;search
		{elseif $smarty.post.scope == 'froogle'}Product&nbsp;search
		{/if}</legend>
	<ol>
		<li id="step1" >
		<div align="center"> 
		<table border="0" width="650">
			<tr>
				<td align="center" colspan="2">
					<script type="text/javascript" src="http://www.gmodules.com/ig/ifr?url=http%3A%2F%2Fwww.google.com%2Fig%2Fmodules%2Fgoogle_insightsforsearch_interestovertime_searchterms.xml&amp;up__property={$scope}&amp;up__search_terms={$keywords}&amp;up__location=empty&amp;up__category=0&amp;up__time_range={$time}&amp;up__compare_to_category=false&amp;synd=ig&amp;w=650&amp;h=350&amp;lang=en-US&amp;title=Google+Insights+for+Search&amp;border=%23ffffff%7C3px%2C1px+solid+%23999999&amp;output=js"></script>
				</td>
			</tr>
			<tr>
				<td align="center" width="50%">
					<script type="text/javascript" src="http://www.gmodules.com/ig/ifr?url=http%3A%2F%2Fwww.google.com%2Fig%2Fmodules%2Fgoogle_insightsforsearch_relatedsearches.xml&amp;up__results_type=TOP&amp;up__property={$scope}&amp;up__search_term={$keywords}&amp;up__location=empty&amp;up__category=0&amp;up__time_range={$time}&amp;up__max_results=10&amp;synd=ig&amp;w=320&amp;h=350&amp;lang=en-US&amp;title=Google+Insights+for+Search&amp;border=%23ffffff%7C3px%2C1px+solid+%23999999&amp;output=js"></script>
				</td>
				<td align="center" width="50%"> 
					<script type="text/javascript" src="http://www.gmodules.com/ig/ifr?url=http%3A%2F%2Fwww.google.com%2Fig%2Fmodules%2Fgoogle_insightsforsearch_relatedsearches.xml&amp;up__results_type=RISING&amp;up__property={$scope}&amp;up__search_term={$keywords}&amp;up__location=empty&amp;up__category=0&amp;up__time_range={$time}&amp;up__max_results=10&amp;synd=ig&amp;w=320&amp;h=350&amp;lang=en-US&amp;title=Google+Insights+for+Search&amp;border=%23ffffff%7C3px%2C1px+solid+%23999999&amp;output=js"></script>
				</td>
			</tr>
		</table>	
		</div>
	</ol>
</fieldset>
{/if}
</form>
{literal}
<script>
$('keywords').addEvent('click', function(){
	if( $('keywords').value == 'Example: adobe photoshop cs4') {
		$('keywords').value = '';
	}
});
$('keywords').addEvent('blur', function(){
	if( $('keywords').value == '') {
		$('keywords').value = 'Example: adobe photoshop cs4';
	}
});

$('display').addEvent('click', function(){
	if( !$('keywords').value) {
		r.alert( 'Client side error', 'Enter your keywords', 'roar_error' );
		return false;
	}		
});
</script>
{/literal}