{if $msg == 'delete'}
<div class="grn">Item has been deleted</div>
{elseif $msg=='delete_error'}
<div class="red">Item can't be deleted</div>
{elseif $msg=='created'}
<div class="grn">Item has been created</div>
{elseif $msg=='saved'}
<div class="grn">Item has been saved</div>
{/if}
<div align="right" style="padding:0 15% 0 0;"> 
<form class="wh">
	Category: <select id="category">
	<option value=""> - select - </option>
	{foreach from=$arrCategories item=i}
	<option {if $smarty.get.cat == $i.id}selected='1'{/if} value="{$i.id}">{$i.title}</option>
	{/foreach}
	</select><br/>
	<select id="category_child" name="category">
		<option value=""> - select - </option>
	</select><br /><br />
	<input type="button" value="Filter" id="filter" />
</form>	
</div>
<table class="info glow" width="100%">
<thead>
<tr>
	<th width="30%">Title
		{if count($arrList)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='content_clickbank' action='manage' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='content_clickbank' action='manage' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}	
	</th>
	<th>Short description</th>
	<th align="center">Edited{if count($arrList)>1}
			{if $arrFilter.order!='edited--up'}<a href="{url name='content_clickbank' action='manage' wg='order=edited--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='edited--dn'}<a href="{url name='content_clickbank' action='manage' wg='order=edited--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
	<th align="center">Added{if count($arrList)>1}
			{if $arrFilter.order!='added--up'}<a href="{url name='content_clickbank' action='manage' wg='order=added--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='added--dn'}<a href="{url name='content_clickbank' action='manage' wg='order=added--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
	<th width="10%">Action</th>
</tr>
</thead>
	<tr>
		<td colspan="5"><a href="{url name='content_clickbank' action='create'}" class="mb" rel="width:800,height:500" title="Create item">Add</a> new item</td>
	</tr>
<tbody>
{foreach from=$arrList key='k' item='v'}
<tr {if $k%2=='0'} class="matros"{/if}>
	<td><a href="{$v.url}" target="_blank">{$v.title}</a></td>
	<td>{$v.short_description|strip_tags|ellipsis:'80'}</td>
	<td align="center" width="120">{$v.edited|date_format:'%Y-%m-%d'}</td>
	<td align="center" width="120">{$v.added|date_format:'%Y-%m-%d'}</td>
	<td class="option">
		<a href="{url name='content_clickbank' action='manage'}?delete={$v.id}" class="delete" rel="{$v.title}">del</a>&nbsp;&nbsp;
		<a href="{url name='content_clickbank' action='create'}?id={$v.id}">edit</a>
	</td>
</tr>
{/foreach}
</tbody>
</table>
<br/> 
<div align="right" style="padding:0 20px 0 0;">
{include file="../../pgg_frontend.tpl"}
</div>
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
window.addEvent('domready',function(){
	new Categories({firstLevel:'category',secondLevel:'category_child',intCatId: categoryId});
	$$('.delete').each(function(a){
		a.addEvent('click',function(e){
			if( !confirm('Delete this item?') ){
				e.stop();
			}
		});
	});
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
		myURI.go();
	});	
});
</script>
{/literal}

