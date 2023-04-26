<script type="text/javascript" src="/skin/_js/typedtags.js"></script>
<form action="" method="POST" enctype="multipart/form-data" class="wh" >
{if !empty($arrErrors)}<p class="error">Error! Fill all required fields.</p>{/if}
{if $arrData.id}<input type="hidden" name="arrData[id]" value="{$arrData.id}" />{/if}
<fieldset>
	<legend></legend>
	<ol>
		<li>
			<label>Language </label><select name="arrData[flg_language]" class="required" id="language">
				{foreach from=Core_Language::$flags item=i key=lang_id}
				<option {if $arrData.flg_language==$lang_id} selected {/if} value="{$lang_id}">{$i.title}
				{/foreach}
			</select>
		</li>	
		<li>
			<label>Category <em>*</em></label>
			<select  class="required"  id="category"><option value="">- select -{foreach from=$arrCategories item=i}<option {if $arrData.category_id == $i.id}selected='1'{/if} value="{$i.id}">{$i.title}</option>{/foreach}</select><br/>
			<select class="required" name="arrData[category_id]" id="category_child"><option value="">- select -</select>{if $arrErrors.category_id}<span class="error">this fields can't be empty</span>{/if}
		</li>
		<li>
			<label>Network <em>*</em></label><select name="arrData[flg_network]" class="required">
				<option value="">- select -
				<option {if $arrData.flg_network==1} selected=1 {/if} value="1">Clickbank
			</select>{if $arrErrors.flg_network}<span class="error">this fields can't be empty</span>{/if}
		</li>
		<li>
			<label>URL <em>*</em></label><input type="text" name="arrData[url]" value="{$arrData.url}" class="required" />{if $arrErrors.url}<span class="error">this fields can't be empty</span>{/if}
		</li>
		<li>
			<label>Video URL</label><input type="text" name="arrData[video_url]" value="{$arrData.video_url}"  />
		</li>
		<li>
			<label>Title <em>*</em></label><input type="text" class="required" name="arrData[title]" value="{$arrData.title}" />{if $arrErrors.title}<span class="error">this fields can't be empty</span>{/if}
		</li>
		<li>
			<label>Short description <em>*</em></label><textarea class="required" name="arrData[short_description]" id="short-description" >{$arrData.short_description}</textarea>{if $arrErrors.short_description}<span class="error">this fields can't be empty</span>{/if}
		</li>
		<li>
			<label>Long description <em>*</em></label><textarea class="required" name="arrData[long_desctiption]" id="long-description" >{$arrData.long_desctiption}</textarea>{if $arrErrors.long_desctiption}<span class="error">this fields can't be empty</span>{/if}
		</li>
		<li>
			<label>Vendor ID</label><input type="text" name="arrData[vendor_id]" value="{$arrData.vendor_id}" />
		</li>
		<li>
			<label>Vendor Name</label><input class="required" type="text" name="arrData[vendor_name]" value="{$arrData.vendor_name}" />
		</li>		
		<li>
			<label>Tags</label>{module name='tags' action='getlist' type='clickbank' item_id=$arrData.id textarea_name='arrData[tags]' search_href='./'}
		</li>
		<li>
			<label>Small thumb <br/><span class="helper">100px X 100px max</span>	</label><input type="file" class="required" name="smallthumb"  value="100" />
			{if !empty($arrData.smallthumb)}
			<input type="hidden" name="arrData[smallthumb]" value="{$arrData.smallthumb}" />
			<img src="{img src=$arrData.smallthumb_preview w=50 h=50}" rel="<div style='border:1px solid #000000;'><img src='{$arrData.smallthumb_preview}' /></div>"  class="screenshot"  />&nbsp;<input type="checkbox" name="arrData[smallthumb_delete]" value="1">&nbsp;delete
			{/if}	
		</li>
		<div style="clear:both;"></div>
		<li>
			<label>Large thumb <br/><span class="helper">350px X 350px max</span></label><input type="file" class="required" name="largethumb" value="350" />
			{if !empty($arrData.largethumb)}
			<input type="hidden" name="arrData[largethumb]" value="{$arrData.largethumb}" />
			<img  src="{img src=$arrData.largethumb_preview w=50 h=50}" rel="<div style='border:1px solid #000000;'><img src='{$arrData.largethumb_preview}' /></div>"  class="screenshot"  />&nbsp;<input type="checkbox" name="arrData[largethumb_delete]" value="1">&nbsp;delete
			{/if}
		</li>
		<div style="clear:both;"></div>
		{section loop=10 name=j}
		{assign var=name_preview value="preview{$smarty.section.j.index}"}
		{assign var=name_file value="file{$smarty.section.j.index}"}
		{assign var=name_type value="type{$smarty.section.j.index}"}
		<li>
			<label>Banner {$smarty.section.j.index+1}</label>
			<input type="file" name="banners[]" class="banner-selected" /><select style="width:225px;"  name="arrData[banners][]">
			{foreach from=Project_Content_Adapter_Clickbank::$bannerType item=i key=k}
				<option {if $arrData[$name_type]==$k} selected=1 {/if} value="{$k}">{$i.title}
			{/foreach}
			</select>
			{if !empty($arrData[$name_preview])}
			<br/><img src="{img src=$arrData[$name_preview] w=95 h=60}" rel="<div style='border:1px solid #000000;'><img src='{$arrData[$name_preview]}' /></div>"  class="screenshot" />&nbsp;<input type="checkbox" name="arrData[banner_delete][{$smarty.section.j.index}]" value="1" />&nbsp;delete
			<input type="hidden" name="arrData[banner_file][{$smarty.section.j.index}]" value="{$arrData[$name_file]}" />
			{/if}
		</li>
		{/section}
	</ol>
	<ol>	
		<li>
			<label></label><input type="submit" name="" value="Submit" id="submit" />
		</li>
	</ol>
</fieldset>
</form>
{literal}
<script type="text/javascript" src="/skin/_js/categories.js"></script>
<script type="text/javascript">
var categoryId = {/literal}{$arrData.category_id|default:'null'}{literal};
var jsonCategory = {/literal}{$arrTree|json|default:'null'}{literal};

var Clickbank=new Class({
	initialize: function(){
		this.initEvents();
	},
	initEvents: function(){
		$('language').addEvent('change',function(e){
			this.setLanguage( $('language').get('value') );
		}.bind(this));		
		$('submit').addEvent('click',function(){
			
		});
	},
	setLanguage: function(lang){
		this.getCategory2Lang(lang);
	},
	getCategory2Lang: function(lang){
		var r=new Request({
			url:'{/literal}{url name="content_clickbank" action="ajax_get"}{literal}',
			onRequest: function(){
					$('category').disabled=true; 
					$('category_child').disabled=true;
					var img=new Element('img',{
						'src':'/skin/i/frontends/design/ajax_loader_line.gif',
						'id':'loader'
					});
					img.inject($('language').getPrevious('label'),'bottom');
			},
			onComplete: function(){
					$('category').disabled=false; 
					$('category_child').disabled=false;
					if( $('loader') ){
						$('loader').destroy();
					}
			},
			onSuccess: function(json){
				arr=JSON.decode(json);
				hash=new Hash(arr);
				$('category').empty();
				$('category_child').empty();
				var option=new Element('option',{'value':'','html':'- select -'});
				option.inject($('category'),'bottom');
				option.clone().inject($('category_child'),'bottom');
				hash.each(function(item){
					if(item.level>1){
						return;
					}
					var option=new Element('option',{'value':item.id,'html':item.title});
					option.inject($('category'),'bottom');
				});
			}
		}).post({'lang':lang,'action':'get_category'});
	}
});

var oFCKeditor_short = {};
var oFCKeditor_long = {};
function FCKeditor_OnComplete( editorInstance ) {
	oFCKeditor = editorInstance;
}
var oFCKeditor_short = new FCKeditor('short-description',570,150);
var oFCKeditor_long = new FCKeditor('long-description',570,250);
window.addEvent('domready',function(){
	var optTips = new Tips('.screenshot');
	new Clickbank();
	new Categories({
		firstLevel:'category',
		secondLevel:'category_child',
		intCatId: categoryId,
		jsonTree: jsonCategory
	});
	oFCKeditor_short.ToolbarSet = 'Basic';
	oFCKeditor_long.ToolbarSet = 'Basic';
	oFCKeditor_short.ReplaceTextarea();
	oFCKeditor_long.ReplaceTextarea();

});
</script>
{/literal}