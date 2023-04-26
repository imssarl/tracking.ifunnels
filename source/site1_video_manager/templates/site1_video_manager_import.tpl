{if $msg}
	<div style="padding:10px;">
	{foreach from=$msg item='m'}
		{if $m.r}
		<div class="red">{$m.r}</div>
		{else}
		<div class="grn">{$m.g}</div>
		{/if}
	{/foreach}
	</div>
{/if}
<form method="post" action="" class="wh" style="width:70%" enctype="multipart/form-data">
	<p>You can upload text or zip files here. Zip file should contain files in text file format.<br />Note: text file name will be used as Category title (if category already exists, then videos will be added to it;<br />if category does not exist, it will be created at the time of import)</p>
	<fieldset>
		<legend>Upload file</legend>
		<ol>
			<li>
				<label for="address1"><span>Source: <em>*</em></span></label>
				<select name="source">
					<option value=''> - select - </option>
					{html_options options=$arrSelect.source}
				</select>
			</li>
			<li>
				<label for="address1"><span>File: <em>*</em></span></label>
				<input type="file" name="file" />
			</li>
		</ol>
	</fieldset>
	<p><input type="submit" value="Upload" /></p>
</form>
<div style="padding-top:10px;margin:0 auto;width:50%">
	file format:<br />
	<textarea rows="10" cols="70"  class="clipboard-text clipboard-id-1"><videos>
	<video>
		<title><![CDATA[ your title here ]]></title>
		<embed><![CDATA[ your embed code here ]]></embed>
		<url><![CDATA[ your url here ]]></url>
	</video>
	<video>
		...
	</video>
</videos></textarea><br />
	<a class="clipboard-click clipboard-id-1" href="#">Copy to clipboard</a>
</div>
<div id="clipboard_content"></div>
<script>
{literal}
var _clipboard = {};
window.addEvent('load', function () {
	_clipboard=new Clipboard($$('.clipboard-click'));
});
{/literal}
</script>
<script src="/skin/_js/clipboard/clipboard.js"></script>