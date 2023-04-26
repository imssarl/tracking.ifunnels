<br/>
<br/>
{if !empty($arrRes)}
{********************** SAVE ARTICLE *******************************}
<form id="form-rewrite" method="POST" action="" class="wh">
<div class="conteiner">{$arrRes[0].title}</div>
<div class="conteiner">{$arrRes[0].body|replace:"\r\n":'<br>'}</div>
<textarea style="display:none;" class="clipboard-text clipboard-id-1">{$arrRes[0].title}{assign var=new_str value="\n"}{$new_str}{$arrRes[0].body}</textarea>
<textarea  name="arrRes[title]" style="display:none;"  >{$arrRes[0].title}</textarea>
<textarea  name="arrRes[body]" style="display:none;"  >{$arrRes[0].body}</textarea>

<input type="hidden" name="arr[max]" id="max-articles" value="1" />
<input type="hidden" name="arr[body]" value='{$arr.body|escape}' />
<input type="hidden" name="arr[title]" value='{$arr.title|escape}' />
<input type="hidden" name="arr[id]" value="{$arr.id}" />
<input type="hidden" name="arr[vars]" value="{$arr.vars}" />
<input type="hidden" name="type" value="0" id="type-action" />
<input type="hidden" id="container-original-body" value="{$arr.original_text}" name="arr[original_text]">
<input type="hidden" id="container-original-title" value="{$arr.original_title}" name="arr[original_title]">
<fieldset id="button-block">
	<ol>
		<li>
			<input type="button" class="clipboard-click clipboard-id-1" value="Copy to Clipboard" />
			{if !$isLast}<input type="submit"  id="rewrite" value="Rewrite It Again" />{/if}
			<input type="button" id="save-one" value="Save to Content Wizard" />
			<input type="button" id="save-all" value="Save All Variations" />
		</li>
	</ol>
</fieldset>
<fieldset id="all-variations" style="display:none;">
	<ol>
		<li>
			<label>Number of times to rewrite</label><input type="text" id="max" maxlength="2" value="15" />
			<p class="helper" style="padding-left:180px;">Max:15</p>
		</li>
		<li>
			<label>Export</label><input type="radio" name="type-save" class="type-save" value="3" checked='1' />
		</li>
		<li>
			<label>Save to content wizard</label><input type="radio" name="type-save" class="type-save" value="2" />
		</li>
		<li>
			<input type="button" value="Back" id="all-back">&nbsp;<input type="submit" value="Save" id="save" />
		</li>
	</ol>
</fieldset>
<div id="clipboard_content"></div>
<script language="javascript" src="/skin/_js/clipboard/clipboard.js"></script>
{literal}
<script type="text/javascript">
var _clipboard={};
window.addEvent('domready',function(){ 
	_clipboard=new Clipboard($$('.clipboard-click'));
	$('max').addEvent('change',function(){ 
		$('max-articles').set('value',$('max').value);
	});
	$('save').addEvent('click',function(e){ 
		e.stop(); 
		if(!$chk($('max').value) ){
			r.alert('Error','Please, enter number of times to rewrite.','roar_error');
			return false;
		}
		if($('max').value>15){
			r.alert('Error','Number of times to rewrite. Max. 15','roar_error');
			return false;
		}
		$('max-articles').set('value',$('max').value);
		$$('.type-save').each(function(item){ 
			if( item.checked ) {
				$('type-action').value=item.value;
			}
		});
		$('form-rewrite').submit();
	});
	$('save-one').addEvent('click', function(e){ 
		e.stop();
		$('type-action').set('value',1);
		$('form-rewrite').submit();
	});
	$('save-all').addEvent('click', function(e){ 
		e.stop();
		$('button-block').setStyle('display','none');
		$('all-variations').setStyle('display','block');
		$('type-action').set('value',2);
	});	
	$('all-back').addEvent('click',function(){ 
		$('button-block').setStyle('display','block');
		$('all-variations').setStyle('display','none');
	});
});
</script>
{/literal}
</form>
{else}
{********************* REWRITE ARTICLE *****************************}
{if !empty($arrErr)}
<div class="red">
Error! Can't create new articles. 
</div>
<br/>
{/if}
<div style="min-height:300px;">
<form class="wh" method="POST">
	<input type="hidden" name="arr[max]" value="1"/>
	<input type="hidden" name="arr[clear_session]" value="1"/>
	<fieldset>
		<legend>Rewriter</legend>
		<ol id="select-article-block" style="display:block;">
			<li>{module name='site1_articles' action='multiboxplace' place='select_one' type='multiple' input='radio'}	</li>
		</ol>
		<div style="position:relative;">
			<div id="blocked" style="width:0px; height:0px; position:absolute; background:#fff; opacity:0.1; filter: alpha(opacity = 10);"></div>
			<div id="div2blocked">
				<div class="conteiner" id="title" style="display:{if empty($arr)}none{else}block{/if};">{$arr.title}</div>
				<div class="conteiner" id="body"  style="display:{if empty($arr)}none{else}block{/if};">{$arr.body}</div>
			</div>
		</div>
		<input type="hidden" id="container-original-body" value="{$arr.original_text}" name="arr[original_text]">
		<input type="hidden" id="container-original-title" value="{$arr.original_title}" name="arr[original_title]">
		<ol id="settings" style="display:none;">
			<li id="system-synonyms">
				<label>Synonyms&nbsp;<img id="system-loader" src="/skin/i/frontends/design/ajax_loader_line.gif"></label><select class="variations"  style="display:none;" multiple='1' id="default-variation"></select>
			</li>
			<li>
				<label>Enter Variations&nbsp;<img id="users-loader" src="/skin/i/frontends/design/ajax_loader_line.gif"></label><textarea  class="variations" id="user-variation"></textarea>
			</li>
			<li>
				<input type="button" value="Cancel" id="cancel" >
				<input type="button" value="Apply" id="apply"/>
				<input type="button" value="Apply to all" id="apply-all"/>
			</li>
		</ol>
		<ol id="rewrite" style="display:{if empty($arr)}none{else}block{/if};">
			<li>
				<input type="submit" value="Rewrite" id="submit" >
				<input type="button" value="Cancel" id="cancel-all" >
				<input type="hidden" name="arr[body]" id="data-body" value="" />
				<input type="hidden" name="arr[title]" id="data-title" value="" />
				<input type="hidden" name="arr[vars]" id="user-vars" value="{$arr.vars}" >
				<input type="hidden" name="arr[id]" id="article-id" value="{$arr.id}" >
			</li>
		</ol>			
	</fieldset>
</form>

<script type="text/javascript"  src="/skin/_js/contextmenu/contextmenu.js"></script>
<script type="text/javascript"  src="/skin/_js/articles/rewriter.js"></script>
<link rel="stylesheet" type="text/css" href="/skin/_css/contextmenu.css" >
<ul id="contextmenu">
	<li><a href="#clear" class="clear contextmenu-item">Clear</a></li>
	<li class="separator"><a href="#del" class="delete contextmenu-item">Delete</a></li>
	<li class="separator"><a href="#quit" class="quit contextmenu-item">Quit</a></li>
</ul>

{literal}
<script type="text/javascript">
var multiboxArticle = new Class({
	initialize: function( json ) {
		this.ids = JSON.decode(json.jsonData);
		this.cunstructHTML();
	},
	cunstructHTML: function(){
		var request = new Request.MyJSON({
			url:"{/literal}{url name='site1_articles' action='get'}{literal}",
			onSuccess: function( res ){
				var strText = res['arrArticle'].body.stripTags().replace(/\r/g,"").replace(/\n/g,'<br>');
				var strText = strText.replace(/\|/g,'');	
				var strTitle = res['arrArticle'].title.stripTags().replace(/\|/g,'');
				$('body').set('html',strText );
				$('title').set('html',strTitle );
				$('container-original-title').set('value', res['arrArticle'].title );
				$('container-original-body').set('value', res['arrArticle'].body );
				$('article-id').set('value', res['arrArticle'].id );
				$('body').setStyle('display','block');
				$('title').setStyle('display','block');
			}
		}).post({ get_article:true,id:this.ids[0].id });
	}
});

window.addEvent('domready',function(){
	new Rewriter({
		linkDefultVariations: '{/literal}{url name="site1_articles" action="defaultVariations"}{literal}',
		linkUserVariations: '{/literal}{url name="site1_articles" action="userVariations"}{literal}'
	});
});
</script>
{/literal}
</div>
{/if}
<style>
{literal}
.conteiner{border:5px solid #CCCCCC; width:100%; height:auto; margin:5px 0 0 0; padding:5px;}
#user-variation{height:200px; width:550px;}
#default-variation{padding:5px;height:150px; width:550px; border:1px solid #A2A2A2;}
{/literal}
</style>