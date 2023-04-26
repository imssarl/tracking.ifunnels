<br/>
{if !$popup}
<div align="center">
	<div class=""  style="width:50%;">
		<a class="" href="{url name='site1_blogfusion' action='manage'}">Manage blogs</a> | 
		<a class="" href="{url name='site1_blogfusion' action='upgrade'}">Upgrade WP</a>
	</div>
</div>
{/if}
	<br/>
<div align="right">
	Category: <select id="category">
	<option value=""> - select - </option>
	{foreach from=$arrCategories item=i}
	<option {if $smarty.get.cat == $i.id}selected='1'{/if} value="{$i.id}">{$i.title}</option>
	{/foreach}
	</select><br/>
	<select id="category_child" name="category">
		<option value=""> - select - </option>
	</select><br />
	Blog title: <input type="text" name="title" value="{$smarty.get.blog_title}" id="blog_title" style="width:296px; margin:5px 0 5px 0;"><br/>
	<input type="button" value="Filter" id="filter" />
</div>
{if $msg == 'delete'}<div class="grn">Delete successfully.</div>{/if}
{if $msg == 'stored'}<div class="grn">Stored successfully.</div>{/if}
{if $msg == 'changed'}<div class="grn">Directory changed successfully.</div>{/if}
{if $msg == 'error'}<div class="red">Error. Can't delete blog.</div>{/if}
<p>
	<input type="submit" value="Delete" id="delete" />
	<input type="submit" value="Store settings" id="store-settings" />
</p>
<form action="" id="current-form" method="post">
<input type="hidden" name="mode" value="store-settings" id="mode" />
<table width="100%">
	<thead>
	<tr>
		<th style="padding-right:0;" width="1px"><input type="checkbox" id="del" class="tooltip" title="mass delete" rel="check to select all" /></th>
		<th style="padding-right:0;" width="1px"><input type="checkbox" id="set" class="tooltip" title="mass store settings" rel="check to select all" /></th>
		<th>Blog{if count($arrList)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_blogfusion' action='manage' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_blogfusion' action='manage' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Category{if count($arrList)>1}
			{if $arrFilter.order!='category--up'}<a href="{url name='site1_blogfusion' action='manage' wg='order=category--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='category--dn'}<a href="{url name='site1_blogfusion' action='manage' wg='order=category--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Dashboad (username/password)</th>
		<th>Version{if count($arrList)>1}
			{if $arrFilter.order!='version--up'}<a href="{url name='site1_blogfusion' action='manage' wg='order=version--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='version--dn'}<a href="{url name='site1_blogfusion' action='manage' wg='order=version--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th width="30%">Options</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arrList item=i key=k}
	<tr{if $k%2=='0'} class="matros"{/if}>
		<input type="hidden" name="ids[]" value="{$i.id}" />
		<td style="padding-right:0;"><input type="checkbox" name="del[{$i.id}]" class="check-me-del" id="check-{$i.id}" /></td>
		<td style="padding-right:0;"><input type="checkbox" name="set[{$i.id}]" class="check-me-set"{if $i.flg_settings} checked=""{/if} /></td>
		<td>{$i.title}<br/><a href="{$i.url}" target="_blank">{$i.url}</a></td>
		<td>{if $i.category}{$i.category}{else}<a class="mb select-category"  href="#mb" title="Select category" rel="type:element,width:400" rev="{$i.id}">Select category</a>{/if}</td>
		<td><a target="_blank" href="{$i.url}wp-login.php">Dashboard</a> ({$i.dashboad_username}/{$i.dashboad_password})</td>
		<td align="center">{$i.version}</td>
		<td align="center">
			<a href="{url name='site1_blogfusion' action='multiboxwidget'}?id={$i.id}" rel="width:1000,height:500" class="mb test">Widgets</a> | 
			<a href="{url name='site1_blogfusion' action='blogclone'}?id={$i.id}">Clone</a> | 
			<a href="{url name='site1_blogfusion' action='general'}?id={$i.id}">Manage</a> | 
			<a href="#mb" rel="type:element,width:400" rev="{$i.id}" title="Change category" class="mb select-category" >Change category</a> | 
			<a href="#" rel="{$i.category_id}" class="click-me-del" id="{$i.id}">Delete</a>
		</td>
	</tr>	
	{/foreach}
	</tbody>
</table>
</form>

<div id="mb" style="display:none;">
	<form action="" class="wh" style="padding:10px" method="POST" >
		<input type="hidden" name="arrNewCat[id]" value="" id="change-cat-id" >
		<select  id="cat-id" class="first" >
			<option value="">- select -
			{foreach from=$arrCategories item=i}
			<option value="{$i.id}">{$i.title}
			{/foreach}
		</select><br/><br/>
		<select class="second" name="arrNewCat[category_id]"><option value="">- select - </select><br/><br/>
		<input type="submit" value="Change">
		<br/><br/>
	</form>
</div>
<br/>
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script>

var categoryId = {/literal}{$smarty.get.cat|default:'null'}{literal};
var jsonCategory = {/literal}{$treeJson}{literal};

var Categories = new Class({
	Implements: Options,
	options: {
		firstLevel: 'category',
		secondLevel: 'category_child',
		intCatId: categoryId
	},
	initialize: function( options ){
		this.setOptions(options);
		this.arrCategories = new Hash(jsonCategory);
		$(this.options.firstLevel).addEvent('change',function(){
			this.setFromFirstLevel( $(this.options.firstLevel).value );
		}.bindWithEvent( this ) );
		if( $chk( this.options.intCatId ) && this.checkLevel( this.options.intCatId ) ) {
			this.setFromFirstLevel( this.options.intCatId );
		} else if( $chk( this.options.intCatId ) ) {
			this.setFromSecondLevel( this.options.intCatId );
		}
	},
	checkLevel: function(id){
		var bool=false;
		this.arrCategories.each(function(el){
			if( el.id == id ) { bool=true; }
		}); 
		return bool;
	},
	setFromFirstLevel: function( id ){
		this.arrCategories.each( function(item){
			if( item.id == id ) {
				$A( $(this.options.firstLevel).options).each(function(i){
					if(i.value == id){
						i.selected=1;
					}
				});					

				$(this.options.secondLevel).empty();
				var option = new Element('option',{'value':'','html':'- select -'});
				option.inject( $(this.options.secondLevel) );
				var hash = new Hash(item.node);
				hash.each(function(i,k){
					var option = new Element('option',{'value':i.id,'html':i.title});
					if( i.id == this.options.intCatId ){
						option.selected=1;
					}
					option.inject( $(this.options.secondLevel) );
				},this);
			}
		},this);
	},
	setFromSecondLevel: function( id ) {
		this.arrCategories.each(function( item ){
			var hash = new Hash(item.node);
			hash.each(function(el){
				if ( id == el.id ) {
					this.setFromFirstLevel( el.pid );
				}
			},this);
		},this);
	}
});

var multibox={};
window.addEvent('domready',function(){
	new Categories({firstLevel:'category',secondLevel:'category_child',intCatId: categoryId});
	checkboxToggle($('del'));
	checkboxToggle($('set'));
	$('filter').addEvent('click',function(e){
		e && e.stop();
		var myURI=new URI();
		var catFirstLevel=$('category').get('value');
		var catSecondLevel=$('category_child').get('value');
		if ( $chk(catSecondLevel) ) {
			myURI.setData({cat:catSecondLevel}, true);
		} else if ( $chk(catFirstLevel) ) {
			myURI.setData({cat:catFirstLevel}, true);
		} else {
			myURI.setData(new Hash(myURI.getData()).filter(function(value, key){return key!='cat';}));
		}
		if( $chk( $('blog_title').value ) ) {
			myURI.setData({blog_title:$('blog_title').value}, true);
		} else {
			myURI.setData(new Hash(myURI.getData()).filter(function(value, key){return key!='blog_title';}));
		}
		myURI.go();
	});
	$('blog_title').addEvent('keydown',function(event){
		if ( event.key == 'enter'){
			event && event.stop();
			var myURI=new URI();
			var catFirstLevel=$('category').get('value');
			var catSecondLevel=$('category_child').get('value');
			if ( $chk(catSecondLevel) ) {
				myURI.setData({cat:catSecondLevel}, true);
			} else if ( $chk(catFirstLevel) ) {
				myURI.setData({cat:catFirstLevel}, true);
			} else {
				myURI.setData(new Hash(myURI.getData()).filter(function(value, key){return key!='cat';}));
			}
			if( $chk( $('blog_title').value ) ) {
				myURI.setData({blog_title:$('blog_title').value}, true);
			} else {
				myURI.setData(new Hash(myURI.getData()).filter(function(value, key){return key!='blog_title';}));
			}
			myURI.go();
		}
	});
	$('delete').addEvent('click',function(e){
		e && e.stop();
		if (!$$('.check-me-del').some(function(item){
			return item.checked==true;
		})) {
			alert( 'Please, select one checkbox at least' );
			return;
		}
		if(!confirm('Your sure to delete selected items?')) {
			return;
		}
		$('mode').set('value','delete');
		$('current-form').submit();
	});
	$('store-settings').addEvent('click',function(e){
		e && e.stop();
		if (!$$('.check-me-set').some(function(item){
			return item.checked==true;
		})) {
			alert( 'Please, select one checkbox at least' );
			return;
		}
		$('mode').set('value','store-settings');
		$('current-form').submit();
	});
	$$('.click-me-del').addEvent('click',function(e){
		e && e.stop();
		var el='check-'+this.get('id');
		if ( !$(el).get('checked') ) {
			$(el).set('checked',true);
			if ($(el).get('checked')) {
				$('delete').fireEvent('click');
			}
			$(el).set('checked',false);
		}
	});
	$$('.select-category').addEvent('click', function(e){
		e && e.stop();
		var id = this.get('rev');
		$('change-cat-id').value = id;
		categoryId = $(id).rel;
		setTimeout("initMultiboxCat()",700);
	});	
	
	
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});	
});
var url='';
var initWidgetEvents = function(strUrl){ 
	url = strUrl;
	var iframe = multibox.box.firstChild.firstChild;
	var conteiner = multibox.box;
	var div = new Element('div',{id:'multiboxBlocked'});
	div.setStyle('width','1000px');
	div.setStyle('height','500px');
	div.setStyle('background','url(/skin/i/frontends/design/ajax-loader-big.gif) center center no-repeat #FFF');
	div.setStyle('position','absolute');
	div.setStyle('left','0');
	div.setStyle('top','0');
	div.inject(conteiner);
	iframe.addEvent('load',function(){ 
		locationIframe();
	});
}
var locationIframe = function(){ 
	var iframe = multibox.box.firstChild.firstChild;
	iframe.contentWindow.location=url+'wp-admin/widgets.php';
	iframe.removeEvents();
	iframe.addEvent('load',function(){ 
		$('multiboxBlocked').destroy();
		iframe.removeEvents();
	});
}
var initMultiboxCat = function(){
	var el = $$('.wh').getLast().elements;
	var first = null;
	var last = null;
	$A(el).each(function(e){
		if(e.tagName == 'SELECT'){
			if(e.className == 'first'){first = e;}
			if(e.className == 'second'){last = e;}
		}
	});
	first.id = 'cat';
	last.id = 'cat_child';
	new Categories({firstLevel:'cat',secondLevel:'cat_child',intCatId: categoryId});
}
</script>
{/literal}