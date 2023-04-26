<script type="text/javascript" src="/skin/_js/typedtags.js"></script>
<div style="padding-top:10px;">
<form method="post" action="" class="wh" style="width:60%">
	<input type="hidden" name="arrData[id]" value="{$arrData.id}" />
	<fieldset>
	<ol>
		<li>
			<label for="stencil_category"><span{if $arrErr.category_id} class="red"{/if}>Category: <em>*</em></span></label>
			<select name="arrData[category_id]" id="stencil_category">
				<option value=''> - select - </option>
				{html_options options=$arrSelect.category selected=$arrData.category_id}
			</select>
		</li>
		<li>
			<label for="stencil_source"><span>Source:</span></label>
			<select name="arrData[source_id]" id="stencil_category">
				<option value=''> - select - </option>
				{html_options options=$arrSelect.source selected=$arrData.source_id}
			</select>
		</li>
		<li>
			<label for="stencil_title"><span{if $arrErr.title} class="red"{/if}>Title: <em>*</em></span></label>
			<input type="text" name="arrData[title]" value="{$arrData.title}" id="stencil_title" />
		</li>
		<li>
			<label for="stencil_body"><span>Embed Code:</span></label>
			<textarea name="arrData[body]" rows="6" id="stencil_body">{$arrData.body}</textarea><p>(at least either Embed Code or URL cannot be empty)</p>
		</li>
		<li>
			<label for="stencil_url_of_video"><span>URL of Video:</span></label>
			<textarea name="arrData[url_of_video]" rows="6" id="stencil_url_of_video">{$arrData.url_of_video}</textarea><p>(at least either Embed Code or URL cannot be empty)</p>
		</li>

		<li>
			<label style="float:left;">Tags</label>{module name='tags' action='getlist' type='video' item_id=$arrData.id textarea_name='arrArticle[tags]' search_href='./'}
		</li>
	</ol>
	</fieldset>
	<p><input value="{if $arrData}Edit{else}Create{/if}" type="submit"></p>
</form>
</div>