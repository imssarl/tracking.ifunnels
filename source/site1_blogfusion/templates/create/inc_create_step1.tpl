	<div class="element initElement">
	<fieldset>
		<legend></legend>
		<ol>
			<li>
				<fieldset>
					<legend>Select Category <em>*</em></legend>
						<ol>
							<li>
						 	<label style="margin:0 0 0 170px;"><select id="category"  >
						 	<option value="0"> - select -
						 	{foreach from=$arrCategories item=i}
						 		<option {if $arrBlog.category_id == $i.id}selected='1'{/if} value="{$i.id}">{$i.title}
						 	{/foreach}</select></label>
							</li>
							<li>	
							<label style="margin:0 0 0 170px;"><select name="arrBlog[category_id]" class="required" id="category_child" ></select></label>
							</li>
						</ol>
				</fieldset>
			</li>
			{if !$arrBlog.id||$arrBlog.theme_id}
			<li>
			<p class="helper">You can pick up the theme of your choice using the drop down menu or our Wordpress<br/> Theme Wall directly</p>
				<label>Select Theme</label><select name="arrBlog[theme_id]" id="theme">
					{foreach from=$arrThemes item=i}
					<option value="{$i.id}" title='{img src=$i.preview w=282 h=231}' {if $arrBlog.theme_id == $i.id}selected='1'{/if}  class="theme_option test {if $i.flg_prop} prop{/if}" >{$i.title}</option>
					{/foreach}
				</select><br/>
				<label></label><a  href="{url name='site1_blogfusion' action='multiboxtheme'}" class="mb" rel="width:940,height:500"">Use Wordpress Theme Wall to select a theme VISUALLY</a>
				<div id="themeImg" align="center" style="padding:10px;"></div>
			</li>
			{/if}
			{if !$arrBlog.id}
			<li>
				<label>Default Category Name</label><input type="text" name="arrBlog[blog_default_category]" value="{$arrBlog.blog_default_category}" />
			</li>
			<li>
				<label>Enter the blog content categories</label><textarea class="" style="height:50px;" name="arrBlog[blog_categories]" >{$arrBlog.blog_categories}</textarea>
				<p>e.g. Affiliate Marketing, Social Networking, Social Bookmarking</p>
			</li>
			{/if}
			<li>
				<label>Blog Name <em>*</em></label><input class="required {if $arrErr.filtered.title}error{/if}"  title="Blog Name" value="{$arrBlog.title|escape}" type="text" name="arrBlog[title]" />
			</li>
			<li>
				<label>Blog Tag Line</label><input type="text" value="{$arrBlog.blogtag_line}" name="arrBlog[blogtag_line]" />
			</li>
			<li>
				 <a href="#" class="acc_next" rel="1">Next step</a>
			</li>
		</ol>
	</fieldset>
	</div>