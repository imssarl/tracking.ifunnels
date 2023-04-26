<br/>
{if $msg}
	<div class="grn" style="padding:10px;">
		Site successful {$msg}.
	</div>
{/if}
{if $error}
	<div class="red" style="padding:10px;">
		{$error}.
	</div>
{/if}
<div style="clear:both;">
{if $arrList}
<table style="width:100%;">
<thead>
<tr>
	<th width="95">Template
		{if $arrPg.recall>1}
			{if $arrFilter.order!='template_id--up'}<a href="{url name='site1_cnb' action='manage' wg='order=template_id--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='template_id--dn'}<a href="{url name='site1_cnb' action='manage' wg='order=template_id--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>Title
		{if $arrPg.recall>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_cnb' action='manage' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_cnb' action='manage' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>Category
		{if $arrPg.recall>1}
			{if $arrFilter.order!='category_id--up'}<a href="{url name='site1_cnb' action='manage' wg='order=category_id--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='category_id--dn'}<a href="{url name='site1_cnb' action='manage' wg='order=category_id--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>	
	<th>Site URL
		{if $arrPg.recall>1}
			{if $arrFilter.order!='url--up'}<a href="{url name='site1_cnb' action='manage' wg='order=url--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='url--dn'}<a href="{url name='site1_cnb' action='manage' wg='order=url--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>Last Modify
		{if $arrPg.recall>1}
			{if $arrFilter.order!='edited--up'}<a href="{url name='site1_cnb' action='manage' wg='order=edited--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='edited--dn'}<a href="{url name='site1_cnb' action='manage' wg='order=edited--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>	
	<th style="width:100px;">&nbsp;</th>
</tr>
</thead>
<tbody>
{foreach from=$arrList key='k' item='v'}
{if !$v.parent_id}
<tr {if $k%2=='0'} class="matros"{/if}>
	{foreach from=$arrTemplates item=i}{if $i.id == $v.template_id}{assign var=preview value=$i.preview}{/if}{/foreach}
	<td>{if !$v.flg_portal}<img class="screenshot" rel="<div style='border:2px solid #000000;'><img src='{$preview}' /></div>" src="{img src=$preview w=95 h=60}{assign var=preview value=''}" /><br />{/if}</td>
	<td>{$v.title} {if $v.flg_portal}<a href="#" class="open-childs" rel="{$v.id}">(+)</a>{/if}</td>
	<td width="150">{if $v.category}{$v.category}{else}<a class="mb select-category"  href="#mb" title="Select category" rel="type:element,width:400" rev="{$v.id}">Select category</a>{/if}</td>
	<td><a href="{$v.url}" target="_blank">{$v.url}</a></td>
	<td>{$v.edited|date_format:$config->date_time->dt_full_format}</td>
	<td class="option">
		<a href="{if !$v.flg_portal}{url name='site1_cnb' action='edit'}{else}{url name='site1_cnb' action='portal'}{/if}?id={$v.id}"  id="{$v.id}" rel="{$v.category_id}"><img title="Edit" src="/skin/i/frontends/design/buttons/edit.png" /></a>
		{if !$v.flg_portal}<a href="{url name='site1_cnb' action='edit'}?id={$v.id}&template=change"><img title="Change template" src="/skin/i/frontends/design/buttons/template.png" /></a>{/if}
		<a href="{url name='site1_cnb' action='manage'}?del={$v.id}" id="delete"><img title="Delete" src="/skin/i/frontends/design/buttons/delete.png" /></a>
		{if !$v.flg_portal}<a href="{url name='site1_blogfusion' action='create'}?cnb={$v.id}"><img title="Install Wordpress" src="/skin/i/frontends/design/buttons/wpinstall.gif" /></a>{/if}
		{if !$v.flg_portal}<a href="{url name='site1_sbookmarking' action='gadget'}?title={$v.title}&url={$v.url}"><img title="Social Bookmarking" src="/skin/i/frontends/design/buttons/social.png" /></a>{/if}
		<a href="#mb" rel="type:element,width:400" rev="{$v.id}" title="Change category" class="mb select-category" >Change&nbsp;category</a>
	</td>
</tr>
<tr >
	<td colspan="6">
	<div   style="width:80%; padding:0 0 0 92px; display:none;" id="childs_{$v.id}">
	<table width="100%">
	<th width="95">Template</th>
	<th>Title</th>
	<th>Category</th>	
	<th>Site URL</th>
	<th>Last Modify</th>	
	<th style="width:100px;">&nbsp;</th>	
		<tbody>
		{foreach from=$arrChilds key=j item=item}
		{if $item.parent_id == $v.id && $item.flg_portal==0}
			<tr {if $j%2!='0'} class="matros"{/if}>
			{foreach from=$arrTemplates item=t}{if $t.id == $item.template_id}{assign var=ppreview value=$t.preview}{/if}{/foreach}
				<td  width="95"><img class="screenshot" rel="<div style='border:2px solid #000000;'><img src='{$ppreview}' /></div>" src="{img src=$ppreview w=95 h=60}{assign var=ppreview value=''}" /><br /></td>
				<td>{$item.title}</td>
				<td width="150">{if $item.category}{$item.category}{else}<a class="mb select-category"  href="#mb" title="Select category" rel="type:element,width:400" rev="{$item.id}">Select category</a>{/if}</td>
				<td><a href="{$v.url}" target="_blank">{$item.url}</a></td>
				<td>{$item.edited|date_format:$config->date_time->dt_full_format}</td>
				<td style="width:100px;" class="option">
					<a href="{if !$item.flg_portal}{url name='site1_cnb' action='edit'}{else}{url name='site1_cnb' action='portal'}{/if}?id={$item.id}"  id="{$item.id}" rel="{$item.category_id}"><img title="Edit" src="/skin/i/frontends/design/buttons/edit.png" /></a>
					<a href="{url name='site1_cnb' action='edit'}?id={$item.id}&template=change"><img title="Change template" src="/skin/i/frontends/design/buttons/template.png" /></a>
					<a href="{url name='site1_cnb' action='manage'}?del={$item.id}" id="delete"><img title="Delete" src="/skin/i/frontends/design/buttons/delete.png" /></a>
					<a href="{url name='site1_blogfusion' action='create'}?cnb={$item.id}"><img title="Install Wordpress" src="/skin/i/frontends/design/buttons/wpinstall.gif" /></a>
					<a href="{url name='site1_sbookmarking' action='gadget'}?title={$item.title}&url={$v.url}"><img title="Social Bookmarking" src="/skin/i/frontends/design/buttons/social.png" /></a>
					<a href="#mb" rel="type:element,width:400" rev="{$item.id}" title="Change category" class="mb select-category" >Change&nbsp;category</a>
				</td>
			</tr>
		{/if}
		{/foreach}
		</tbody>
	</table>	
	</div>
	</td>
</tr>
{/if}
{/foreach}
</tbody>
</table>
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
{include file="../../pgg_frontend.tpl"}
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script type="text/javascript">

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
var multibox = {};
window.addEvent('domready', function(){
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});	
			
	var optTips = new Tips('.screenshot');
	$$('.screenshot').each(function(el){ el.addEvent('click',function(e){ e.stop(); }); });
	
	$('delete').addEvent('click',function(e){
		if(!confirm('Are you sure you want to delete this site? Please note that it will only be deleted from the Manage Sites table of the Creative Niche Manager. If you want to delete your site completely, you will need to delete the site folder directly on your server.')) {
			e.stop();
		}
	});
	$$('.select-category').addEvent('click', function(e){
		e && e.stop();
		var id = this.get('rev');
		$('change-cat-id').value = id;
		categoryId = $(id).rel;
		setTimeout("initMultiboxCat()",700);
	});		
	$$('.open-childs').each(function(item){
		item.addEvent('click', function(e){
			e && e.stop();
			$('childs_'+item.rel).setStyle('display', (($('childs_'+item.rel).style.display=='none')?'block':'none') );
		});
	});
	
});

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
{else}
	<p>no sites found</p>
{/if}
</div>