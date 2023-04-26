<link rel="stylesheet" href="/skin/_js/tabs/tabs.css" />
	<ul id="tabs" style="display:none;">
			<li><a class="tab" href="#" id="one">Content Wizard</a></li>
			<li><a class="tab" href="#" id="two">Upload Article</a></li>
		</ul>
		
		<div id="home" style="display:none;">
			<div class="feature">
				<div> <!--Content Wizard-->
				{module name='site1' action='head'}
					<div style="float:right; padding:10px;">
						<form method="post" action="" id="article-filter" style="display:none;">
							<div style="float:left;">
								Category <select name="category" id='category-filter'>
									<option value=''> - select - </option>
									{html_options options=$arrSelect.articles.category selected=$smarty.post.category.articles}
								</select>
							</div>
							<div style="float:left;padding-left:3px;"><input type="submit" value="Filter" /></div>
						</form>

						</div>

						<div style="width:100%; padding:10px 0 0 20px;"><input type="text" id="random" style="width:80px" > <input type="button" value="Random" id="set_random" /> </div>
						
					<table class="summary2" align="center" cellpadding="0" cellspacing="0" border="0" width="95%" height="95%">
						<tr>
							<th>Id</th>
							<th>Category</th>
							<th>Title</th>
							<th>Summary</th>
							<th>Source</th>
							<th align="center"><input type="checkbox" id="select_all" /></th>
						</tr>
					{foreach from=$arrList item=i}
						<tr>
							<td align="center">{$i.id}</td>
							<td align="center"><span id="category_{$i.id}">{$i.category_title|replace:'\r':' '}</span></td>
							<td><span id="content_{$i.id}_title">{$i.title|replace:"\r":" "|escape}</span></td>
							<td><span id="summary_{$i.id}">{$i.summary}</span></td>
							<td align="center"><span id="source_{$i.id}">{$i.source_title}</span></td>
							<td align="center"><input type="checkbox" value="{$i.title}" id="{$i.id}"  class="chk_item" /></td>
						</tr>
					{/foreach}	
					</table>
					{include file="../../pgg_frontend.tpl"}
					<div align="center"><p><input type="button" value="Choose" id="choose"></p></div>
				</div><!--/Content Wizard-->
			</div>
			<div class="feature">
				<div style="left:0px;top:0px;"><!--Upload Article-->
					{if $save_article_true == 1}
						<div class="grn" style="padding:10px;">
						Articles saved successfully  
						</div>
					{elseif $save_article_true == 2}
						<div class="red" style="padding:10px;">
						Process Aborted. Unable to upload articles. Check format file! 
						</div>
					{/if}
					<form class="wh" style="width:{if $return_type == 'list' || $return_type == 'form'}90%{else}70%{/if}" action="" method="POST" enctype="multipart/form-data" id="import_form">
						<p>Please complete the form below. Mandatory fields are marked with <em>*</em></p>
						<fieldset >
							<legend>Articles import</legend>
							<ol>
								<li>
									<label>Source <em>*</em> </label>
									<select name="import[source]" id="import_source">
										<option value='0'> - select source - </option>
										{html_options options=$arrSelect.articles.source}
									</select>				
								</li>
								<li>
									<label>Author</label>
									<input type="text" name="import[author]" id="import_author" />
								</li>
								<li>
									<fieldset>
									<legend>Article source <em>*</em></legend>
									<ol>
										<label><input type="radio" name="import[article_source]" class="article_source" value="text_file" />&nbsp;Text file (new line separated)</label>
										<label><input type="radio" name="import[article_source]" class="article_source" value="manually" />&nbsp;Manually </label>
										{if $return_type != 'form'}<label><input type="radio" name="import[article_source]" class="article_source" value="zip_file" />&nbsp;Zip file </label>{/if}
									</ol>
									</fieldset>
								</li>
								<li>
									<fieldset>
									<legend>Status</legend>
									<ol>
										<label><input type="radio" name="import[status]"  value="1" checked='1' />&nbsp;Active </label>
										<label><input type="radio" name="import[status]"  value="0" />&nbsp;InActive </label>
									</ol>
									</fieldset>
								</li>			
							</ol>
						</fieldset>
						<fieldset id="fieldset_manually" style="display:none;">
							<legend></legend>
							<ol>
								<li>
									<label>Category <em>*</em></label>
									<select name="import[manually][category]" id="manually_category">
										<option value="0"> - select category -
										{foreach from=$arrSelect.articles.category item=i key=k name=cat}
										<option value="{$k}">{$i}
										{/foreach}
									</select>
								</li>
								<li>
									<label>Title <em>*</em></label>
									<input type="text" name="import[manually][title]" id="manually_title" />
								</li>
								<li>
									<label>Enter article <em>*</em></label>
									<textarea style="height:150px;" name="import[manually][text]" id="manually_text"></textarea>
								</li>
							</ol>
						</fieldset>

						<fieldset id="fieldset_file" style="display:none;">
							<legend>{if $return_type != 'form'}<a href="#" id="add">+ Add</a>{/if}</legend>
							<ol>
								<li>
									<label>Category <em>*</em></label>
									<select name="import[category][0]" class="category_file">
										<option value="0"> - select category -
										{foreach from=$arrSelect.articles.category item=i key=k name=cat}
										<option value="{$k}">{$i}
										{/foreach}
									</select>
								</li>
								<li>
									<label>File <em>*</em></label>
									<input type="file" name="import[file][0]" class="file" >
								</li>
							</ol>
						</fieldset>
						
						<fieldset>
							<legend></legend>
							<ol>
								<li>
									<input type="submit" name="save" id="save_botton" value="Save"/>
								</li>
							</ol>
						</fieldset>
					</form>
				</div><!--/Upload Article-->
		</div>
		</div>

{literal}
		<script type="text/javascript" src="/skin/_js/tabs/rotater.js"></script>
		<script type="text/javascript" src="/skin/_js/tabs/tabs.js"></script>
		<script type="text/javascript">
			var tabs = new MGFX.Tabs('.tab','.feature',{startIndex:{/literal}{if $save_article_true != null}1{else}0{/if}{literal}});
		
			var index = 0;
			var category = {{/literal}{$keyw = 0}{foreach from=$arrSelect.articles.category item=i key=k name=cat}{$keyw++}{literal}:{id:{/literal}'{$k}',name:'{$i}'{literal}}{/literal}{if !$smarty.foreach.cat.last},{/if}{/foreach}{literal}};//выглядит ужасно но работает (создает obj objs категорий)

			var importMass = new Class({
				initialize: function( category, index ){
					this.category = category;
					this.first = $('fieldset_file');
					this.nameLinkDelete = 'fieldset_delete';
					this.index=index;
					this.arrError = new Array();
					this.errIndex = 0;
				},
				addFieldset: function(){
					var fieldset = new Element('fieldset');
					var legend = new Element('legend');
					var a = new Element('a',{'class':this.nameLinkDelete,'href':'#'}).set('html', '- delete');
					a.inject( legend.inject( fieldset,'top' ) );
					
					var ol = new Element('ol');
					var li_category = new Element('li');
					var label_category = new Element('label').set('html','Category <em>*</em>');
					var select = new Element('select',{'name':'import[category]['+this.index+']', 'class':'category_file'});

					var option = new Element('option',{'value':'0'}).set('html','- select category -');
					option.inject(select,'top');
							
					var hash = new Hash(this.category);
					hash.each(function(value, key){
						var option = new Element('option', {'value': value.id}).set('html',value.name);
						option.inject(select,'bottom');
					});
					
					label_category.inject(li_category,'top');
					select.inject(li_category,'bottom');
					li_category.inject(ol);
					
					var li_file = new Element('li');
					var label_file = new Element('label').set('html', 'File <em>*</em>');
					var input = new Element('input', {'type':'file', 'name':'import[file]['+this.index+']', 'class':'file'});
					
					label_file.inject(li_file,'top');
					input.inject(li_file,'bottom');
					li_file.inject(ol);
					
					ol.inject(fieldset,'bottom');
					fieldset.inject(this.first,'after');
					this.deleteFieldset();
				},
				deleteFieldset: function(){
					$$('a.'+this.nameLinkDelete).each(function(el){
						el.addEvent('click',function(e){
							e.stop();
							if(el.getParent('fieldset'))
							el.getParent('fieldset').destroy();
						});
					});
				},
				deleteAllFiledset:function(){
					$$('a.'+this.nameLinkDelete).each(function(el){
							el.getParent('fieldset').destroy();
					});		
				}, 
				displayFileBlock:function(){
					$('fieldset_file').style.display='block';
					$('fieldset_manually').style.display='none';
				},
				displayManually:function(){
					$('fieldset_file').style.display='none';
					$('fieldset_manually').style.display='block';
					this.deleteAllFiledset();
				}, 
				validateManually:function(){
					var error = 0;
					if( $('manually_category').value == 0 ) {
						error = 1;
						var errorSpan = new Element('span', {'class': 'errors_span','style':'color:red;'}).set('html','!!!');
						errorSpan.inject( $('manually_category') , 'after');
					}

					if( !$('manually_title').value ) {
						error = 1;
						var errorSpan = new Element('span', {'class': 'errors_span','style':'color:red;'}).set('html','!!!');
						errorSpan.inject( $('manually_title') , 'after');
					}

					if( !$('manually_text').value ) {
						error = 1;
						var errorSpan = new Element('span', {'class': 'errors_span','style':'color:red;'}).set('html','!!!');
						errorSpan.inject( $('manually_text') , 'after');
					}			
					
					if (error != 0) {
						r.alert( 'Client side error', 'Select Category. Fill Title and Article text', 'roar_error' );
						return false;
					}
					return true;
				},
				validateFileBlock:function(){
					var error = 0;
					$$('select.category_file').each(function(select){
						if( select.value == 0 ) {
							error = 1; 
							var errorSpan = new Element('span', {'class': 'errors_span','style':'color:red;'}).set('html','!!!');
							errorSpan.inject( select , 'after');
						}
					});
					$$('input.file').each(function(input){
						if ( !input.value ) {
							error = 1;

							var errorSpan = new Element('span', {'class': 'errors_span','style':'color:red;'}).set('html','!!!');
							errorSpan.inject( input, 'after');				
						}			
					});
					if (error != 0) {
						r.alert( 'Client side error', 'Select Category and Files', 'roar_error' );
						return false;
					}
					return true;
				},
				deleteAllError:function(){
					$$('span.errors_span').each(function(el){
						el.destroy();
					});
				}
			});

		window.addEvent('domready',function(){
			var obj = new importMass(category,index);
			obj.deleteFieldset();
			$$('input.article_source').each(function(el){
				el.addEvent('click', function(){
					var obj = new importMass(category,index);
					if(el.value == 'text_file'){
						obj.displayFileBlock();
					} else if (el.value == 'manually') {
						obj.displayManually();
					} else if (el.value == 'zip_file') {
						obj.displayFileBlock();
					}
				});
			});

			if( $('add') ) {
				$('add').addEvent('click', function(e){
					e.stop();
					index++;
					var obj = new importMass(category,index);
					obj.addFieldset();
				});
			}

				//Edit IMPORT FORM
				$('import_form').addEvent('submit', function(e){
					var obj = new importMass(category,index);
					obj.deleteAllError();
					if( $('import_source').value==0 ) {
						new Element('span', {'class': 'errors_span','style':'color:red;'}).set('html','!!!').inject( $('import_source'), 'after');
						r.alert( 'Client side error', 'Select Source', 'roar_error' );
						e.stop();
						return false;
					}

					if ( !$$('input.article_source').some( function(el) { return el.checked; }) ) {
						r.alert( 'Client side error', 'Select Article source', 'roar_error' );
						e.stop();
						return false;
					}

					if ( !$$('input.article_source').some( function(el) {
						if ( !el.checked ) {
							return false;
						}
						switch( el.value ) {
							case 'text_file': return this.validateFileBlock();
							case 'manually': return this.validateManually();
							case 'zip_file': return this.validateFileBlock();
						}
					},obj) ) {
						e.stop();
						return false;
					}

					if ( $('import_author').value=="" ) {
						alert( 'Please do not forget to define Author Name for your articles.\nYou can do so when editing articles in the Manage Articles section.' );
					}
				});

				//добавление элемента вставленого при помощи Upload Article
				JSONdata = {/literal}{if  !empty({$jsonData})}{$jsonData}{else}''{/if}{literal};
				if (JSONdata != '') {
					var addData = Array.from(JSONdata);
					var arrParent = window.parent.placeParam;
					addData.append(arrParent);
					window.parent.placeParam = addData;
					window.parent.placeDo();
					window.parent.multibox.close();		
				}
		
		});
		$('home').setStyle('display','');
		$('tabs').setStyle('display','');
		</script>
{/literal}