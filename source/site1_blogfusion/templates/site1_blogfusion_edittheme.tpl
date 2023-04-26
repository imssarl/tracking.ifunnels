{include file="site1_blogfusion_general_menu.tpl"}
<form class="wh" style="width:90%" id="post_form" method="POST">
<input type="hidden" name="arrFtp[address]" value="{$arrBlog.ftp_host}">
<input type="hidden" name="arrFtp[username]" value="{$arrBlog.ftp_username}">
<input type="hidden" name="arrFtp[password]" value="{$arrBlog.ftp_password}">
<input type="hidden" name="arrFtp[directory]" id="ftp_directory" value="{$arrBlog.ftp_directory}">
<input type="hidden" name="edit[type]" value="edit">
<fieldset>
	<legend>Edit theme</legend>
	<ol>
		<li>
			<label>Select file:</label>
			<select id="file">
			<option value=""> --
			{foreach from=$arrDirs item=i}
			{if !$i.is_dir && $i.view}
				<option value="{$strPath}{$i.name}">{$i.name}
			{/if}
			{/foreach}
			</select>&nbsp;<img src="/skin/i/frontends/design/ajax-loader_new.gif" id="loader" style="display:none;" >
		</li>
		<li>
			<label>Theme Files</label><textarea name="file_content" style="width:100%; height:400px;" id="editor"></textarea>
		</li>
		<li>
			<input type="button" id="save" value="Save" />&nbsp;<img src="/skin/i/frontends/design/ajax-loader_new.gif" id="loader_save" style="display:none;" >
		</li>
		<li>
			<div id="editor_message"></div>
		</li>
	</ol>
</fieldset>
</form>
</td>
</tr>
</table>
{literal}
<script>

$('file').addEvent('change', function(event){
	$('editor_message').empty();
	var ftp_directory = $('ftp_directory').value;
	$('ftp_directory').value +=this.value;
	var req = new Request({url: "{/literal}{url name='site1_affiliate' action='get'}{literal}",onRequest: function(){$('loader').style.display='inline';}, onSuccess: function(responseText){
		$('editor').value = responseText;
	}, onComplete: function(){ $('loader').style.display='none'; }}).post($('post_form'));	
	$('ftp_directory').value = ftp_directory;
});

$('save').addEvent('click', function(){
	$('editor_message').empty();
	var ftp_directory = $('ftp_directory').value;
	$('ftp_directory').value +=$('file').get('value');;	
	var req = new Request({url: "{/literal}{url name='site1_affiliate' action='save'}{literal}",onRequest: function(){$('loader_save').style.display="inline";}, onSuccess: function(responseText){
		if(parseInt(responseText)){
			$('editor_message').set('html','<div class="grn">File has been saved successfully</div>');
		} else {
			$('editor_message').set('html','<div class="red">Error. File has not been saved</div>');
		}
	}, onComplete: function(){$('loader_save').style.display="none"; }}).post($('post_form'));	
	$('ftp_directory').value = ftp_directory;
});
</script>
{/literal}