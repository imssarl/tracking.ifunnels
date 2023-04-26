<div>
{if !$arrList}
	<div style="float:left; width: 100%">
		<div class="red" style="margin: 80px auto; width: 100%; text-align:center;"><b>no sites found</b></div>
	</div>
{else}
<form method="post" action="" id="sites-filter">
	<input type="hidden" name="arrFilter[order]" value="{$arrFilter.order}" />
	<div style="margin-bottom:10px;">
		<div style="float:left;">types: <select class="elogin" style="width:150px;" id="with_type-filter" >
		<option value=""> - show all - </option>
		{html_options options=Project_Sites::$code selected=$arrFilter.with_type}
		</select></div>
		<div class="for_checkbox" style="float:left;margin-left:10px;"><label for="without_categories-filter">only uncategorized:</label> <input type="checkbox"{if $arrFilter.without_categories} checked=""{/if} value="yes" id="without_categories-filter" /></div>
		<input type="submit" value="filter" style="margin-left:10px;">
	</div>
</form>
<script type="text/javascript">
	$('sites-filter').addEvent('submit',function(e){
		e&&e.stop();
		['with_type','without_categories'].toURI('-filter').go();
	});
</script>
<table class="info glow" style="width:90%;">
<form action="" id="current-form" method="post">
<input type="hidden" name="mode" value="" id="mode" />
	<tr><td colspan="7">
	{include file="../../pgg_frontend.tpl"}
	</td></tr>
<thead>
<tr>
	<th style="padding-right:0;" width="1px"><input type="checkbox" id="del" class="tooltip" title="mass delete from syndicate" rel="check to select all" /></th>
	<th>Site type
		{if $arrPg.recall>1}
			{if $arrFilter.order!='site_type--up'}<a href="{url name='site1_syndication' action='manage_sites' wg='order=site_type--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='site_type--dn'}<a href="{url name='site1_syndication' action='manage_sites' wg='order=site_type--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>Category
		{if $arrPg.recall>1}
			{if $arrFilter.order!='category--up'}<a href="{url name='site1_syndication' action='manage_sites' wg='order=category--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='category--dn'}<a href="{url name='site1_syndication' action='manage_sites' wg='order=category--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th></th>
	<th>URL
		{if $arrPg.recall>1}
			{if $arrFilter.order!='url--up'}<a href="{url name='site1_syndication' action='manage_sites' wg='order=url--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='url--dn'}<a href="{url name='site1_syndication' action='manage_sites' wg='order=url--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>Last modified
		{if $arrPg.recall>1}
			{if $arrFilter.order!='catedit--up'}<a href="{url name='site1_syndication' action='manage_sites' wg='order=catedit--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='catedit--dn'}<a href="{url name='site1_syndication' action='manage_sites' wg='order=catedit--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
{foreach from=$arrList key='k' item='v'}
<tr{if $k%2=='0'} class="matros"{/if}>
	<td style="padding-right:0;"><input type="checkbox" name="del[{$v.id}-{$v.site_type}]" class="check-me-del" id="check-{$v.id}" /></td>
	<td>{if $v.site_type==Project_Sites::CNB}CNB{elseif $v.site_type==Project_Sites::NCSB}NCSB{elseif $v.site_type==Project_Sites::BF}Blog Fusion{elseif $v.site_type==Project_Sites::NVSB}NVSB{elseif $v.site_type==Project_Sites::PSB}PSB{/if}</td>
	<td>{if $v.category_id}{$v.category}{else}not selected{/if}</td>
	<td><a href="#mb" rel="type:element,width:400" id="{$v.id}:{$v.category_id}:{$v.site_type}" title="{if $v.category_id}Change category{else}Select category{/if}" class="mb select-category" >{if $v.category_id}Change category{else}Select category{/if}</a></td>
	<td><a href="{$v.url}" target="_blank">{$v.url}</a></td>
	<td>{$v.catedit|date_format:$config->date_time->dt_full_format|default:'not changed'}</td>
	<td align="center">
		<a href="#" rel="{$v.id}" class="click-me-del" id="{$v.id}">Delete</a>
	</td>
</tr>
{/foreach}
</tbody>
<tfoot>
	<tr><td colspan="7">
	{include file="../../pgg_frontend.tpl"}
	</td></tr>
</tfoot>
</form>
</table>
<input type="submit" value="Delete" id="delete" />
{/if}
</div>
<div id="mb" style="display:none;">
	<form action="{Core_Module_Router::$uriFull}"  class="wh" style="padding:10px" method="POST" >
		<input type="hidden" name="arrNewCat[site_id]" value="" id="site_id" >
		<input type="hidden" name="arrNewCat[site_type]" value="" id="site_type" >
		<select style="width:100%;"  id="cat-id" class="first" >
			<option value="">- select -
			{foreach from=$arrCategories item=i}
			<option value="{$i.id}">{$i.title}
			{/foreach}
		</select><br/><br/>
		<select style="width:100%;" class="second" name="arrNewCat[category_id]"><option value="">- select - </select><br/><br/>
		<input type="submit" value="Change">
		<br/><br/>
	</form>
</div>
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script type="text/javascript">

var categoryId = {/literal}{$smarty.get.cat|default:'null'}{literal};
var jsonCategory = {/literal}{$treeJson}{literal};

var multibox = {};
window.addEvent('domready', function(){
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});

	$$('.select-category').addEvent('click', function(e){
		e && e.stop();
		var arrParams=this.get('id').split(':');
		$('site_id').value = arrParams[0];
		$('site_type').value = arrParams[2];
		categoryId = arrParams[1];
		setTimeout("initMultiboxCat()",700);
	});

	checkboxToggle($('del'));
	$('delete').addEvent('click',function(e){
		e && e.stop();
		if (!$$('.check-me-del').some(function(item){
			return item.checked==true;
		})) {
			alert( 'Please, select one checkbox at least' );
			return;
		}
		if(!confirm('Are you sure you want to delete this sites from syndication network?')) {
			return;
		}
		$('mode').set('value','delete');
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
});


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