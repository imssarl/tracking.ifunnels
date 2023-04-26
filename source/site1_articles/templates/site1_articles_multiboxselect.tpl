<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{module name='site1' action='head'}
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<script type="text/javascript" src="/skin/_js/moopop.js"></script>
</head>
<body style="padding:10px;">

	<div style="float:right; padding:10px;">
	<form method="post" action="" id="video-filter">
		<div style="float:left;">
			Category <select name="category" id='category-filter'>
				<option value=''> - select - </option>
				{html_options options=$arrSelect.category selected=$smarty.post.category}
			</select>
		</div>
		<div style="float:left;padding-left:3px;"><input type="submit" value="Filter" /></div>
	</form>

	</div>

	<div style="width:100%; padding:10px 0 0 20px;"><input type="text" id="random" style="width:80px" > <input type="button" value="Random" id="set_random" /> </div>
	
<table class="summary2" align="center" cellpadding="0" cellspacing="0" border="0" width="95%">
	<tr>
		<th>Id</th>
		<th>Category</th>
		<th>Title</th>
		<th>Summary</th>
		<th>Source</th>
		<th align="center">{if $type_input_element == 'checkbox'}<input type="checkbox" id="select_all" />{/if}</th>
	</tr>
{foreach from=$arrArticles item=i}
	<tr>
		<td align="center">{$i.id}</td>
		<td align="center"><span id="category_{$i.id}">{$i.category_title|replace:'\r':' '}</span></td>
		<td><span id="title_{$i.id}">{$i.title|replace:"\r":" "|escape}</span></td>
		<td><span id="summary_{$i.id}">{$i.summary}</span></td>
		<td align="center"><span id="source_{$i.id}">{$i.source_title}</span></td>
		<td align="center"><input type="{$type_input_element}" name="{$type_check}" value="{$i.id}" id="id_{$i.id}" class="check_article" ></td>
	</tr>
{/foreach}	
</table>
{include file="../../pgg_frontend.tpl"}
<div align="center"><p><input type="button" value="Choose" id="close"></p></div>
	
<script type="text/javascript">
{literal}
var place = {};
var choose = function(){
		var i = 0;	
		var arrIds = new Array();
			$$('.check_article').each(function(checkbox){
				if(checkbox.checked) {
					arrIds[i] = {
						'id' : checkbox.value,
						'category' : $('category_' + checkbox.value).get('html'),
						'title'	: $('title_' + checkbox.value).get('html'),
						'source' : $('source_' + checkbox.value).get('html')
					};
					i++;					
				}
			});
			new window.parent.multiboxArticle( {jsonData:JSON.encode(arrIds),place:'{/literal}{$smarty.get.place}{literal}'} );
}

window.addEvent('domready', function(){
	if( window.parent.$('multibox_ids_{/literal}{$smarty.get.place}{literal}') && window.parent.$('multibox_ids_{/literal}{$smarty.get.place}{literal}').value  ) {
		var hash =  JSON.decode( window.parent.$('multibox_ids_{/literal}{$smarty.get.place}{literal}').value );
		if( window.parent.disabled ) {
			var jsonDisabled = JSON.decode( window.parent.json_{/literal}{$smarty.get.place}{literal} );
		}
		if(hash != false){ 
		hash.each(function( value, key ){
			if( $( 'id_'+value.id ) ) {
				$('id_'+value.id).checked = true;
				if ( window.parent.disabled ) { 
					jsonDisabled.each( function( v ) {
						if( v.id == value.id ) {
							$('id_'+value.id).disabled = true;
						}
					});
				} 
			}
		});
		 }
	}

	$('close').addEvent('click', function(){
		choose();
		window.parent.multibox_article.close();
	});
	
	if( $('select_all') ) {
		$('select_all').addEvent('click', function(){
			$$('.check_article').each(function(el){
				el.checked = $('select_all').checked;
			});
		});
	}
	
	$('set_random').addEvent('click', function(){
		var random = parseInt($('random').value);
		if(!random) {
			alert('Please enter numeric value!');
			return false;
		}
		var numElement = 0;
		var elements = $$('.check_article'); 
		$$('.check_article').each(function(el){numElement += 1; el.checked=false;});
		for(var i = 1; i <= random; i++){
			var n=Math.floor(Math.random()*(numElement));
			if(!elements[n].checked)
			elements[n].checked = true;
			else
			elements[n+1].checked = true;
		}
	});
});
{/literal}
</script>
</body>
</html>