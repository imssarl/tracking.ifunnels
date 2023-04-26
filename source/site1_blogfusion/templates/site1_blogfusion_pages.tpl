{include file="site1_blogfusion_general_menu.tpl"}
<br/>
<p><a href="#" id="add">Add new page</a></p>
<form action="" method="POST" class="wh" id="page_add" style="width:60%;">
<div style="display:none;"  id="form_add" align="left">
	<input type="hidden" name="arrPage[0][id]" id="page_id" value="" />
	<input type="hidden" name="arrPage[0][ext_id]" id="page_ext_id" value="" />	
		<fieldset>
			<legend>Add new page</legend>
			<ol>
				<li>
					<label>Page title <em>*</em></label><input type="text" class="required" id="page_title" title="Post title" name="arrPage[0][title]">
				</li>
				<li>
					<label>Page Content <em>*</em></label><textarea name="arrPage[0][content]" title="Content" id="page_content" class="required" style="height:200px;"></textarea>
				</li>
				<li>
					<input type="submit" id="submit_page"  value="Add page" />
				</li>
			</ol>
		</fieldset>
</div>
</form>
<br/>
<form action="" method="POST" id="form_delete">
<table width="100%">
	<thead>
	<tr>
		<th style="padding-right:0;" width="1px"><input type="checkbox" id="delete_all"></th>
		<th align="center">Page title</th>
		<th align="center" width="20%">Options</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arrList item=i}
	<input type="hidden" name="arrPage[{$i.id}][id]" value="{$i.id}" />
	<input type="hidden" name="arrPage[{$i.id}][ext_id]" value="{$i.ext_id}" />
	<input type="hidden" name="arrPage[{$i.id}][title]" value="{$i.title}">
	<tr>
		<td style="padding-right:0;"><input type="checkbox" name="arrPage[{$i.id}][del]" id="del_{$i.id}" class="delete_checkbox"></td>
		<td>{$i.title}</td>
		<td align="center">
			<a href="#"  rel="{$i.ext_id}:{$i.id}" class="edit_page">Edit</a><textarea style="display:none;">{$i.content}</textarea> | 
			<a href="" class="delete" rel="{$i.id}">Delete</a> | 
			<a href="{$arrBlog.url}?p={$i.ext_id}" target="_blank">View</a>
		</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="3"><input type="submit" value="Delete"></td>
	</tr>
	</tbody>
</table>
</form>
{include file="../../pgg_frontend.tpl"}
</td>
</tr>
</table>

{literal}<script type="text/javascript" src="/skin/_js/fckeditor/fckeditor.js"></script>
<script type="text/javascript">
var oFCKeditor = {};
function FCKeditor_OnComplete( editorInstance )
{
	oFCKeditor = editorInstance;
}
var oFCKeditor = new FCKeditor('page_content');
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
	$$('.delete').each(function(el){
		el.addEvent('click', function(a){
			a.stop();
			$('del_'+el.rel).checked = ($('del_'+el.rel).checked)? 0:1;
			if($('del_'+el.rel).checked) {
				$('form_delete').submit();
			}
		});
	});
	$('add').addEvent('click',function(e){
		e.stop();
		if($('form_add').style.display == 'none') {
			$('form_add').style.display='block';
			$('page_title').set('value','');
			oFCKeditor.SetHTML('');
			$('submit_page').value = 'Add Page';
		} else {
			$('form_add').style.display='none';
		}
	});
	$$('.edit_page').each(function(el){
		el.addEvent('click', function(a){
			a.stop();
			var rel = el.rel;
			var id = rel.substitute({},/[0-9]+:/);
			var ext_id = rel.substitute({},/:[0-9]+/);
			$('page_id').set('value',id);
			$('page_ext_id').set('value',ext_id);
			$('page_title').set('value',el.getParent().getPrevious().get('html'));
			oFCKeditor.SetHTML(el.getNext('textarea').value);
			$('submit_page').value = 'Update Page';
			$('form_add').style.display='block';
		});
	});
	$('page_add').addEvent('submit', function(e){
		if($('page_title').value == ''){
			e.stop(); 
			r.alert('ERROR', 'Page title - This filed is required!','roar_error'); 
			return false;
		}
		if(oFCKeditor.GetHTML() == ''){
			e.stop(); r.alert('ERROR', 'Page content - This filed is required!','roar_error'); 
			return false;
		}
		$('submit_page').disabled = true;

	});
});
</script>
{/literal}