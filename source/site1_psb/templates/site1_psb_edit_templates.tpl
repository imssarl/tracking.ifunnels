<br/>
{if $error}
<p class="red">
Error. Template has not been saved
</p>
{/if}
<div align="center">
	<h3>{$arrTemplate.title}</h3>
	<img src="{$arrTemplate.preview}" >
</div>
<br/><br/>
<form class="wh" action="" style="width:70%;" method="POST" enctype="multipart/form-data">
<input type="hidden" name="arr[id]" value="{$arrTemplate.id}">
<fieldset>
	<legend>Edit templates</legend>
	<ol>
		<li><label>Select file:</label>
			<select name="arr[file]" id="select-file">
				<option value=""> - select -
				{foreach from=$arrFiles item=files key=dir}
				{foreach from=$files item=file}
				<option value="{$dir}/{$file}">{$file}
				{/foreach}
				{/foreach}
			</select>
		</li>{*
		<li>
			<label>Header Image:</label><input type="file" name="header" >
		</li>*}
		<li>
			File:<br/><textarea style="height:300px; width:100%;" id="editor"></textarea>
		</li>
		<li>
			<input type="button" value="Update file" id="save-file" > <input type="submit" value="Save Template" id="save-template">
		</li>
		<li id="messages-block" style="display:none;">
			<p class="red" id="error-message"></p>
			<p class="grn" id="success-message"></p>
		</li>		
	</ol>
</fieldset>
</form>
{literal}
<script type="text/javascript">
window.addEvent('domready', function(){
	$('select-file').addEvent('change', function(){
		$('messages-block').setStyle('display','none');
		var r = new Request({  url:"{/literal}{url name='site1_psb' action='ajax_edit_template'}{literal}/?open_file=true", method:'post', onSuccess: function(response){
			$('editor').set('value',response);
		}}).post({'file': $('select-file').value });
	});
	$('save-file').addEvent('click', function(){
		$('messages-block').setStyle('display','none');
		$('success-message').set('html','');
		$('error-message').set('html','');
		var r = new Request({  url:"{/literal}{url name='site1_psb' action='ajax_edit_template'}{literal}/?save_file=true", method:'post', onSuccess: function(response){
			response = JSON.decode(response);
			if( response.result == 1 ){
				$('success-message').set('html','File has been saved successfully');
			} else {
				$('error-message').set('html','File has not been saved');
			}
			$('messages-block').setStyle('display','block');
		}}).post({'file': $('select-file').value, 'strContent':$('editor').value });
	});
});
</script>
{/literal}