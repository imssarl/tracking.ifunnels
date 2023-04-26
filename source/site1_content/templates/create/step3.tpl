<h3 class="toggler" >Select project post settings</h3>
<div class="element initElement">
	<fieldset>
		<legend></legend>
					<ol>
				<li>
					<label for="project_title">Project title <em>*</em></label>
					<input type="text" id="project_title" value="{$arrPrj.title}" name="arrPrj[title]" />
				</li>
				<li>
					<label for="category">Select category <em>*</em></label>
					<select id="category" name="arrPrj[category_pid]"{if $arrPrj.flg_status==1} disabled="disabled"{/if} >
					<option value=""> - select -</option>
					{foreach from=$arrContentCategories item=i}
					<option {if $smarty.get.cat == $i.id}selected='1'{/if} value="{$i.id}">{$i.title}</option>
					{/foreach}
					</select>
				</li>
				<li>
					<label></label>
					<select  name="arrPrj[category_id]" id="category_child"{if $arrPrj.flg_status==1} disabled="disabled"{/if}>
						<option value=""> - select -</option>
					</select> <a class="smb" rel="width:800,height:500" href="{url name='site1_blogfusion' action='muliboxmanage'}">Check your blog categories here</a>
				</li>

				<li class="no_child_category" {if empty($arrPrj.category_id)}style="display:none;"{/if}>
					<label for="randomly_in_category">Post randomly in this category</label>
					<input type="radio" id="randomly_in_category" name="arrPrj[flg_posting]"{if $arrPrj.flg_status==1} disabled="disabled"{/if}  {if $arrPrj.flg_posting =='1'}checked="checked"{/if} value="1" class="blog-list clear-blog-list"/>
					<fieldset class="fieldset-blog-list" style="display:none;"></fieldset>
				</li>
				<li class="no_child_category" {if empty($arrPrj.category_id)}style="display:none;"{/if}>
					<label for="select_below_list">Select Site from below list</label>
					<input type="radio" id="select_below_list" class="blog-list clear-blog-list"{if $arrPrj.flg_status==1} disabled="disabled"{/if}  {if $arrPrj.flg_posting =='3'}checked="checked"{/if} name="arrPrj[flg_posting]" value="3" />
					<fieldset class="fieldset-blog-list" style="display:none;"></fieldset>
				</li>
				<li class="no_child_category" {if empty($arrPrj.category_id)}style="display:none;"{/if}>
					<label for="select_list">Randomly in the selected sites</label>
					<input type="radio" id="select_list" class="blog-list clear-blog-list"{if $arrPrj.flg_status==1} disabled="disabled"{/if}  {if $arrPrj.flg_posting=='2'}checked="checked"{/if} name="arrPrj[flg_posting]" value="2"/>
					<fieldset class="fieldset-blog-list" style="display:none;"></fieldset>
				</li>

				<li>
					<a href="#" class="acc_prev" >Prev</a> / <a class="acc_next" href="#" >Next</a>
				</li>
			</ol>
	</fieldset>
</div>
{literal}<script type="text/javascript">
window.addEvent('domready', function() {
// changer для выбора типа категории
$('category_child').addEvent('change',function(event){ 
	if (this.value == "") 
		$$('.no_child_category').setStyle('display','none'); 
	else 
		$$('.no_child_category').setStyle('display','');
	$$('.clear-blog-list').setStyle('checked','0');
});
$('category').addEvent('change',function(event){ 
	$$('.no_child_category').setStyle('display','none');
	$$('.clear-blog-list').setStyle('checked','0');
});
});
</script>{/literal}