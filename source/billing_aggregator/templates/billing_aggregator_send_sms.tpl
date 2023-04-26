<form action="" method="POST" enctype="multipart/form-data" class="wh" >
<fieldset>
	<legend></legend>
	<ol>
		<li>
			<label>Mobile Numbers: </label>
			<textarea name="arrData[numbers]" >{$arrData.numbers}</textarea>
		</li>
		<li>
			<label>SMS: </label>
			<textarea name="arrData[text]" >{$arrData.text}</textarea>
		</li>
	</ol>
	<ol>
		<li>
			<label></label>
			<input type="submit" name="" value="Send" />
		</li>
	</ol>
</fieldset>
</form>