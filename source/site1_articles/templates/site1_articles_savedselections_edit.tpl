{literal}
<style>
form.wh textarea, input[type="text"], input[type="password"], select {width:480px; margin:4px;}
form.wh label {width:70px;}
</style>
{/literal}
<p>&nbsp;</p>
<form action="./?id={$smarty.get.id}{if $smarty.get.page}&page={$smarty.get.page}{/if}" method="POST" class="wh" style="width:50%;">
<fieldset>
	<legend>Edit Saved Selection</legend>
	<ol>
		<li>
			<label>Title</label>
			<input type="text" name="name" value="{$arrItem.name}" />
		</li>
		<li>
			<label>Description</label>
			<textarea name="description" style="height:100px;">{$arrItem.description}</textarea>
		</li>
		<li>
			<label>Code</label>
			<textarea name="code" style="height:250px;">{$arrItem.code}</textarea>
		</li>	
		<li>
			<label>&nbsp;</label>
			<input type="submit" value="Submit" name="save" />
		</li>			
	</ol>
</fieldset>
</form>