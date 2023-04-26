	<div class="element initElement">
	<fieldset>
		<legend></legend>
		<ol>
			<li>
				<fieldset>
					<legend>Enable Plugins</legend>
					<label><input type="checkbox"  id="plugins_all" value="all" />&nbsp;Select All</label>
					{foreach from=$arrPlugins item=i}
					<label><input type="checkbox" class="plugins" {if is_array($arrBlog.plugins) && in_array($i.id, $arrBlog.plugins)}checked='1'{/if} name="arrBlog[plugins][]" value="{$i.id}" />&nbsp;{$i.title}</label>
					{/foreach}
				</fieldset>
			</li>
			<li>
				<p><font color="Red">Note:</font> All the selected plugins will be automatically activated at the time of blog creation </p>
			</li>
			<li>
				<a href="#" class="acc_prev">Prev step</a> / <a href="#" class="acc_next" rel="2">Next step</a>
			</li>
		</ol>
	</fieldset>
	</div>