<table class="info glow">
<thead>
<tr>
	<th>Title
		{if count($arrList)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_syndication' action='review' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_syndication' action='review' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}	
	</th>
	<th>Status{if count($arrList)>1}
			{if $arrFilter.order!='flg_status--up'}<a href="{url name='site1_syndication' action='review' wg='order=flg_status--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='flg_status--dn'}<a href="{url name='site1_syndication' action='review' wg='order=flg_status--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody>
{foreach from=$arrList key='k' item='v'}
<tr {if $k%2=='0'} class="matros"{/if}>
	<td><a href="{url name='site1_syndication' action='review_content'}?id={$v.id}" class="mb" rel="width:800,height:500">{$v.title}</a></td>
	<td>pending review</td>
	<td class="option">
			<a href="{url name='site1_syndication' action='review_content'}?id={$v.id}" class="mb" rel="width:800,height:500">review</a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="3">not found content for review</td></tr>
{/foreach}
</tbody>
</table>
<br/> 

<div align="right" style="padding:0 20px 0 0;">
{include file="../../pgg_frontend.tpl"}
</div>
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script type="text/javascript">
var id2unblock=false;
var Review = new Class({
	initialize: function(){
		this.id=false;
	},
	initReload:function(){
		$('Overlay').addEvent('click', function(){
			this.unBlock();
			window.location.reload(true);
		}.bindWithEvent(this));
		$$('.MultiBoxClose').each(function(el){
			el.addEvent('click',function(){
				this.unBlock();
				window.location.reload(true);
			}.bindWithEvent(this) );
		},this);
		setTimeout(function( param ){ multibox.close(); var ob=new Review(); ob.setId2unblock(id2unblock); ob.unBlock(); window.location.reload(true); },1000);		
	},
	setId2unblock: function( id ){
		this.id = id;
		id2unblock=id;
	},
	initUblock: function(){
		if( $('Overlay') )
		$('Overlay').addEvent('click', function(){
			this.unBlock();
		}.bindWithEvent(this));
		if( $$('.MultiBoxClose') )
		$$('.MultiBoxClose').each(function(el){
			el.addEvent('click',function(){
				this.unBlock();
			}.bindWithEvent(this) );
		},this);			
	},
	unBlock: function(){
		if( !this.id ){	return;	}
		var req = new Request({
				url: "{/literal}{url name='site1_syndication' action='review'}{literal}",
			}).post({'ajax_unblock':true, 'id':this.id});
	}
});
var obj={};
var multibox = {};
window.addEvent('domready', function(){
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		showControls: false,
		nobuttons: true
	});	
	obj = new Review();
	obj.initUblock();
});
</script>
{/literal}