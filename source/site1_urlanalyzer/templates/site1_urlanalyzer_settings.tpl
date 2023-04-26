<br/>
<br/>
<form action="{url name='site1_urlanalyzer' action='analyze'}" method="POST" class="wh" style="width:50%;">
	<fieldset>
		<legend>Page Settings</legend>
		<ol>
			<li>
				<label>Enter Url</label><input type="text" name="arrData[url]" value="{$arrData.url}" />
			</li>
		</ol>
	</fieldset>		
	<fieldset>
		<legend>Report options</legend>
		<ol>
			<li>
				<label>Count words</label><input type="text" name="arrData[count_words]"  value="{if empty($arrData.count_words)}5{else}{$arrData.count_words}{/if}" />
			</li>
			<li>
				<label>Show Title</label><input type="checkbox" name="arrData[show_title]" value="1" >
			</li>
			<li>
				<label>Show Meta tags</label><input type="checkbox" name="arrData[show_meta]" value="1" >
			</li>
			<li>
				<label></label><input type="submit" value="Analyze" />
			</li>
		</ol>
	</fieldset>
</form>