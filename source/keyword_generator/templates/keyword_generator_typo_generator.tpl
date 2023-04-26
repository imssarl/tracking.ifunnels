<form action="#res" method="POST" class="wh" style="width:50%;">
	<fieldset>
		<legend>Typo Generator</legend>
		<ol>
			<li>
				<label>Words</label>
				<textarea id="words" name="word" style="height:100px;">{$smarty.post.word}</textarea>
			</li>
			<li>
				<label>Regular</label><input type="checkbox" class="output" id="regular" checked='1' name="output[regular]" value="1"/>
			</li>
			<li>
				<label>Quotes</label><input type="checkbox" class="output" id="quotes" {if $output.quotes} checked='1' {/if} name="output[quotes]" value="2"/>
			</li>			
			<li>
				<label>Brackets</label><input type="checkbox" class="output" id="brackets"  {if $output.brackets} checked='1'{/if} name="output[brackets]" value="3"/>
			</li>			
			<li>
				<label></label>
				<input type="submit" value="Generate" id="generate" name="submit" />
			</li>
		</ol>
		
	</fieldset>
	<fieldset   id="field_res" {if !$smarty.post.submit} style="display:none;" {/if}>
		<legend></legend>
		<ol>
			<li>
				<label>Result</label>
				{assign var=n value=""}
				<textarea id="res" name="result" style="height:200px;">{foreach from=$arrRes item=i name=word}{foreach from=$i item=j}{$j}{/foreach}{$n}{/foreach}</textarea>
			</li>
			<li>
				<label>File name</label>
				<input type="text" name="name" />
			</li>
			<li>
				<label></label>
				<input type="submit" name="export" value="Export" />
			</li>
		</ol>
	</fieldset>
</form>
<script type="text/javascript">
{literal}
	window.addEvent('domready', function(){
		
		$('generate').addEvent('click',function(){
			if(!$('words').value) {
				r.alert( 'Warning', 'Field Words can not be empty' , 'roar_warning' );
				return false;
			}
		});
		
	});
{/literal}
</script>