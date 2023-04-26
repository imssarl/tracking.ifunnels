<!-- light box -->
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
<script>
{literal}
function saveKeywords()
{
	var form    = document.forms[1];
	var element = document.getElementById('type');
	var value   = document.getElementById('title').value;
	
	if (!value)
	{
		alert('Keyword list title is empty !');
		return false;
	}
	
	element.value = 'save';
	form.submit();
}

function exportKeywords()
{
	var form    = document.forms[1];
	var element = document.getElementById('type');
	
	element.value = 'export';
	form.submit();
}

var multibox={};
window.addEvent('load', function() {
	multibox = new multiBox({
		mbClass: '.keyword-help',
		container: $(document.body),
		useOverlay: true,
	});
});
{/literal}
</script>

<div id="wrap">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td class="head_article" align="left">
			Follow these steps to combine keywords and create long-tail keywords lists:
			<br/>
			1. Add keywords that you want to combine into each of the boxes
			<br/>
			2. Check 'Include blank line' box if you want to exclude keywords from that box in one round of combining your keywords
			<br/>
			3. Click 'Combine' to combine your keywords
			<br/>
			4. Check the created long-tail keywords in the 'Results' box
			<br/>
			5. To save your keywords results into a text file, input the file name and click 'Export'
			<br/>
			6. To save your keywords into the Saved Keywords Selections section of the Keyword Research module, click 'Save and Export'
			<br/>
			<br/>
			Click <a href="/images/keywordgenerator.jpg" class="keyword-help" title="Example" rel="">here</a> for some examples of words to use within the boxes
		</td>
	</tr>
</table>
<br/>
</div>



<form method="post" action="./#combine-form"  class="wh" style="width:50%">
<fieldset>
<legend>Step 1</legend>
	<ol>
	{foreach from=$arrData item=i name=box}
		<li>
			<label> Box{$smarty.foreach.box.iteration} (Include blank line <input type="checkbox" name="arrData[{$smarty.foreach.box.iteration}][check]" id=""> ):</label>
			<textarea name="arrData[{$smarty.foreach.box.iteration}][keywords]" id="" rows="5" cols="40">{$i.keywords}</textarea>
		</li>
	{/foreach}
			<li>
				<label>Regular</label><input type="checkbox" class="output" id="regular" {if $smarty.post.regular}checked=1{elseif empty($post)}checked=1{/if}  name="regular" value="1"/>
			</li>
			<li>
				<label>Quotes</label><input type="checkbox" class="output" id="quotes" {if $smarty.post.quotes}checked=1{/if} name="quotes" value="2"/>
			</li>			
			<li>
				<label>Brackets</label><input type="checkbox" class="output" id="brackets" {if $smarty.post.brackets}checked=1{/if} name="brackets" value="3"/>
			</li>			
	
	</ol>
	<p><input type="submit" value="Combine" /></p>
</fieldset>		
</form>

<p>&nbsp;</p>

<form method="post" action="" id="combine-form" class="wh" style="width:50%">
<fieldset>
<legend>Step 2</legend>
	<ol>
		<input type="hidden" name="type" id="type" value="export">
		<li>
			<label>Result:</label>
			<textarea name="result" id="" rows="5" cols="40">{$result}</textarea>
		</li>
		<li>
			<label>File name:</label>
			<input type="text" name="name" />
		</li>
	</ol>	
	<p><input type="button" value="Export" onclick="exportKeywords()"/></p>
	
	<div align="center"><p><b>or</b></p></div> 
	
	<ol>	
		<li>
			<label>Keyword list title:</label>
			<input type="text" id="title" name="title"/>
		</li>	
	</ol>		
	<p><input type="button" value="Save and Export" onclick="saveKeywords()"/></p>
	
</fieldset>			
</form>
