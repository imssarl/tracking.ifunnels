<script type="text/javascript" src="/skin/_js/typedtags.js"></script>
<form method="post" action="" class="wh" id="create_article" style="width:50%">
<input type="hidden" name="arrArticle[id]" value="{$arrArticle.id}" />
	<p>Please complete the form below. Mandatory fields are marked with <em>*</em></p>
	<fieldset>
		<legend>{if $arrArticle.id}Update{else}Add{/if} article</legend>
		<ol>
			<li>
				<label for="source"><span{if $arrErr.source} class="red"{/if}>Source <em>*</em></span></label>
				<select name="arrArticle[source_id]" id="source">
					<option value=''> - select - </option>
					{html_options options=$arrSelect.source selected=$arrArticle.source_id}
				</select>
			</li>
			<li>
				<label for="category"><span{if $arrErr.category_id} class="red"{/if}>Category <em>*</em></span></label>
				<select name="arrArticle[category_id]" id="category">
					<option value=''> - select - </option>
					{html_options options=$arrSelect.category selected=$arrArticle.category_id}
				</select>
			</li>
			<li><label for="title"><span{if $arrErr.title} class="red"{/if}>Title <em>*</em></span></label> 
				<input name="arrArticle[title]" type="text" id="title" value="{$arrArticle.title|escape:"html"}" maxlength="150" />
				<p>(insert character width less then 150)</p>
			</li>
			<li><label for="author"><span{if $arrErr.author} class="red"{/if}>Author <em>*</em></span></label> 
				<input name="arrArticle[author]" type="text" id="author" value="{$arrArticle.author|escape:"html"}" />
			</li>
			<li>
				<label for="summary"><span{if $arrErr.summary} class="red"{/if}>Summary <em>*</em></span></label>
				<textarea name="arrArticle[summary]" id="summary" rows="2" cols="50">{$arrArticle.summary|escape:"html"}</textarea>
			</li>
			<li>
				<label for="body"><span{if $arrErr.body} class="red"{/if}>Body <em>*</em></span></label>
				<textarea name="arrArticle[body]" id="body" rows="5" cols="50">{$arrArticle.body|escape:"html"}</textarea>
			</li>
			<li>
				<label style="float:left;">Tags</label>{module name='tags' action='getlist' type='articles' item_id=$arrArticle.id textarea_name='arrArticle[tags]' search_href='./'}
			</li>
			<li>
				<fieldset>
					<legend>Status</legend>
					<label><input name="arrArticle[flg_status]" value="1" type="radio"{if !isset($arrArticle.flg_status)||$arrArticle.flg_status=='1'} checked{/if}>Active</label>
					<label><input name="arrArticle[flg_status]" value="0" type="radio"{if $arrArticle.flg_status=='0'} checked{/if}>InActive</label>
				</fieldset>
			</li>
			{*if $arrArticle.id}
			<li>
				<label>Update article on NCSB site(s)</label>
				<input type="checkbox">
			</li>
			{/if*}
		</ol>
	</fieldset>
	<p><input value="{if $arrArticle.id}Update{else}Add{/if} article" type="submit"></p>
</form>