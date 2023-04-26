{include file="site1_blogfusion_general_menu.tpl"}
<p><a href="#" id="add">Add new post</a></p>
<form action="" method="POST" class="wh" id="post_add" style="width:60%;">
<div style="display:none;"  id="form_add" align="left">
	<input type="hidden" name="arrPost[0][id]" id="post_id" value="" />
	<input type="hidden" name="arrPost[0][ext_id]" id="post_ext_id" value="" />
		<fieldset>
			<legend>Add new post</legend>
			<ol>
				<li>
					<label>Category <em>*</em></label><select name="arrPost[0][catIds][]" style="height:100px;" multiple='1' id="post_cat" >
						{foreach from=$arrCats item=i}<option {if $i.flg_default}selected='1'{/if} value="{$i.ext_id}">{$i.title}{/foreach}
						</select>
				</li>
				<li>
					<label>Post title <em>*</em></label><input type="text" class="required" id="post_title" title="Post title" name="arrPost[0][title]">
				</li>
				<li>
					<label>Post tags </label><input type="text" class="required" id="tags_input" title="Post title" name="arrPost[0][tags]">
				</li>				
				<li>
					<label>Description <em>*</em></label><textarea name="arrPost[0][content]" id="post_content"   style="height:200px;" ></textarea>
				</li>
				<li>
					<input type="submit" name=""  id="submit_post" value="Add post" />
				</li>
				<li id="commnets_link">
					<label><a href="{url name='site1_blogfusion' action='comments'}?id={$arrBlog.id}" id="view_comment">Comments</a></label>
				</li>				
			</ol>
		</fieldset>
</div>
</form>
<div align="right">
	Category: <select id="filter_category"><option value=""> -- {foreach from=$arrCats item=i}<option {if $smarty.get.cat_id == $i.ext_id}selected='1'{/if} value="{$i.ext_id}"> {$i.title} {/foreach}</select>
</div>
<br/>
<form action="" method="POST" id="form_delete">
<table width="100%">
	<thead>
	<tr>
		<th style="padding-right:0;" width="1px"><input type="checkbox" id="delete_all"></th>
		<th align="center">Posts</th>
		<th align="center">Comments</th>
		<th align="center" width="20%">Options</th>
	</tr>
	</thead>
	{foreach from=$arrList item=i}
	<input type="hidden" name="arrPost[{$i.id}][id]" value="{$i.id}">
	<input type="hidden" name="arrPost[{$i.id}][ext_id]" value="{$i.ext_id}">
	<input type="hidden" name="arrPost[{$i.id}][title]" value="{$i.title}">
	<input type="hidden" name="arrPost[{$i.id}][tags]" value="{$i.tags}" id="tags_input_{$i.id}">
	<tr>
		<td style="padding-right:0;"><input type="checkbox"  name="arrPost[{$i.id}][del]" class="delete_checkbox" id="del_{$i.id}"></td>
		<td>{$i.title}</td>
		<td align="center">{if $i.comments}<a href="{url name='site1_blogfusion' action='comments'}?id={$arrBlog.id}&post_id={$i.ext_id}">{$i.comments}</a>{else}{$i.comments}{/if}</td>
		<td align="center">
			<a href="#" rel="{$i.id}:{$i.ext_id}" id='[{foreach from=$i.categories item=j name=cat}{if !$smarty.foreach.cat.first},{/if}{$j}{/foreach}]' class="edit_post">Edit</a><textarea name="arrPost[{$i.id}][content]" style="display:none;">{$i.content}</textarea> | 
			<a href="" class="delete" rel="{$i.id}">Delete</a> | 
			<a href="{$arrBlog.url}?p={$i.ext_id}" target="_blank">View</a></td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="4"><input type="submit" value="Delete" /></td>
	</tr>
</table>
</form>
{include file="../../pgg_frontend.tpl"}
</td>
	</tr>
</table>
{literal}
<script type="text/javascript" src="/skin/_js/fckeditor/fckeditor.js"></script>
<script type="text/javascript">
var oFCKeditor = {};
function FCKeditor_OnComplete( editorInstance ) {
	oFCKeditor = editorInstance;
}
var oFCKeditor = new FCKeditor('post_content');
window.addEvent('domready', function(){
	oFCKeditor.ToolbarSet = 'Basic';
	oFCKeditor.ReplaceTextarea();
	$$('.pg_handler').each(function(el){
		el.addEvent('click',function(a){
			a.stop();
			var href = el.href+{/literal}'&id={$arrBlog.id}'{literal}; 
			href.toURI().go();
		});
	});

	$('delete_all').addEvent('click', function(){
		$$('.delete_checkbox').each(function(el){
			el.checked = $('delete_all').checked;
		});
	});
	$('view_comment').addEvent('click',function(a){
		a.stop();
	 	location.href = this.href + '&post_id=' + $('post_ext_id').value;
	});
	 
	$$('.delete').each(function(el){
		el.addEvent('click', function(a){
			a.stop();
			$('del_'+el.rel).checked = ($('del_'+el.rel).checked)? 0:1;
			if($('del_'+el.rel).checked) {
				$('form_delete').submit();
			}
		});
	});
	$('filter_category').addEvent('change', function(){
		if(!this.value) {	
			location.href = './?id='+{/literal}{$arrBlog.id}{literal};	
		} else {
			location.href = './?id='+{/literal}{$arrBlog.id}{literal}+'&cat_id='+this.value;
		}
	});
	$('add').addEvent('click',function(e){
		e.stop();
		if($('form_add').style.display == 'none') {
			$('form_add').style.display='block';
			$('post_title').set('value','');
			oFCKeditor.SetHTML('');
			$('submit_post').value = 'Add Post';
			$('commnets_link').style.display='none';
		} else {
			$('form_add').style.display='none';
			$('commnets_link').style.display='block';
		}
	});
	
	$$('.edit_post').each(function(el){
		el.addEvent('click', function(a){
			a.stop();
			var rel = el.rel;
			var cat_id = JSON.decode(el.id);
			var ext_id = rel.substitute({},/[0-9]+:/);
			var id = rel.substitute({},/:[0-9]+/);
			$('post_id').set('value',id);
			$('post_ext_id').set('value',ext_id);
			$('post_title').set('value',el.getParent().getPrevious().getPrevious().get('html'));
			$('tags_input').set('value', $('tags_input_' + id ).value );
			oFCKeditor.SetHTML(el.getNext('textarea').value);
			$('submit_post').value = 'Update Post';
			var options = $A($('post_cat').options);
			options.each(function(option){
				option.selected = false;
			});
			options.each(function(option){
				cat_id.each(function(i){
					if(option.value == i){
						option.selected = true;
					}	
				});
			});
			$('commnets_link').style.display='block';
			$('form_add').style.display='block';
		});
	});

	$('post_add').addEvent('submit', function(e){
		if($('post_title').value == ''){e.stop(); r.alert('ERROR', 'Post title - This filed is required!','roar_error'); return false;}  
		if(oFCKeditor.GetHTML() == ''){e.stop(); r.alert('ERROR', 'Post description - This filed is required!','roar_error'); return false;}  
		var error=true;
		var options = $A($('post_cat').options);
		options.each(function(el){
			if(el.selected) {
				error=false;
			}
		});
		if(error) {
			e.stop();
			r.alert('ERROR', 'Category - This filed is required!','roar_error');
			return false;
		}
		$('submit_post').disabled = true;
	});
});

</script>
{/literal}