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
	<div style="float:right;">
	<form method="post" action="" id="video-filter">
		<div style="float:left;">Category <select name="category" id='category-filter'>
			<option value=''> - select - </option>
			{html_options options=$arrSelect.category selected=$smarty.get.category}
		</select></div>
		<div style="float:left;padding-left:3px;"><input type="submit" value="Filter" /></div>
	</form>
	</div>
	{if $arrList}
	<table style="width:100%;">
	<thead>
	<tr>
		<th>Category{if count($arrList)>1}
			{if $arrFilter.order!='category_id--up'}<a href="{url name='site1_video_manager' action='multibox' wg='order=category_id--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='category_id--dn'}<a href="{url name='site1_video_manager' action='multibox' wg='order=category_id--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Source{if count($arrList)>1}
			{if $arrFilter.order!='source_id--up'}<a href="{url name='site1_video_manager' action='multibox' wg='order=source_id--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='source_id--dn'}<a href="{url name='site1_video_manager' action='multibox' wg='order=source_id--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Title{if count($arrList)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_video_manager' action='multibox' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_video_manager' action='multibox' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Edited{if count($arrList)>1}
			{if $arrFilter.order!='edited--up'}<a href="{url name='site1_video_manager' action='multibox' wg='order=edited--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='edited--dn'}<a href="{url name='site1_video_manager' action='multibox' wg='order=edited--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Added{if count($arrList)>1}
			{if $arrFilter.order!='added--up'}<a href="{url name='site1_video_manager' action='multibox' wg='order=added--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='added--dn'}<a href="{url name='site1_video_manager' action='multibox' wg='order=added--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>{if !$smarty.get.multiselect}place{else}<input type="checkbox" id="select-all">{/if}</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arrList item='v' key='k'}
	<tr{if $k%2!='0'} class="matros"{/if}>
		<td>&nbsp;{$arrSelect.category[$v.category_id]}</td>
		<td>{$arrSelect.source[$v.source_id]}</td>
		<td>{$v.title}</td>
		<td>{$v.edited|date_local:$config->date_time->dt_full_format}</td>
		<td>{$v.added|date_local:$config->date_time->dt_full_format}</td>
		<td align="center">
			<div style="display:none;" id="video_{$v.id}_video_title">{$v.title}</div>
			<div style="display:none;" id="video_{$v.id}_body">{$v.body}</div>
			<div style="display:none;" id="video_{$v.id}_url_of_video">{$v.url_of_video}</div>		
			<div {if $smarty.get.multiselect}style="display:none;"{/if}>
			<a href="#" class="place_full" rel="video_{$v.id}">All</a> |
			<a href="#" class="place_url" rel="video_{$v.id}">URL</a> |
			<a href="#" class="place_embed" rel="video_{$v.id}">Embed Code</a>			
			</div>
			<input type="checkbox" {if $smarty.get.multiselect}style="display:block;"{else}style="display:none;"{/if} class="chk_item" value="{$v.id}">
		</td>
	</tr>	
	{/foreach}
	</tbody>
	<tfoot>
		<tr><td colspan="{$arrFilter.fields_num+3}">{include file="../../pgg_frontend.tpl"}</td></tr>
	</tfoot>
	</table>
	{if $smarty.get.multiselect}<input type="button" value="Choose" id="choose" >{/if}
	{else}
		<p>no videos found</p>
	{/if}
<script type="text/javascript">
{literal}
window.addEvent('domready', function(){
	$('video-filter').addEvent('submit',function(e){
		e.stop();
		var myURI=new URI(window.location);
		if ( $('category-filter').value=='' ) {
			myURI.setData(new Hash(myURI.getData()).filter(function(value, key){return key!='category';}));
		} else {
			myURI.setData({category:$('category-filter').value}, true);
		}
		myURI.go();
	});
	$$('.place_full').each(function(el){
		el.addEvent('click',function(e,el){
			e.stop();
			window.parent.placeParam={
				body: $(el.rel+'_body').get('html'),
				url_of_video: $(el.rel+'_url_of_video').get('html'),
				video_title: $(el.rel+'_video_title').get('html')
			};
			window.parent.placeDo();
			window.parent.multibox.close();
		}.bindWithEvent(this, el));
	});
	$$('.place_url').each(function(el){
		el.addEvent('click',function(e,el){
			e.stop();
			window.parent.placeParam={url_of_video: $(el.rel+'_url_of_video').get('html')};
			window.parent.placeDo();
			window.parent.multibox.close();
		}.bindWithEvent(this, el));
	});
	$$('.place_embed').each(function(el){
		el.addEvent('click',function(e,el){
			e.stop();
			window.parent.placeParam={body: $(el.rel+'_body').get('html')};
			window.parent.placeDo();
			window.parent.multibox.close();
		}.bindWithEvent(this, el));
	});
	if( $('choose') ) {
		// initialized list
		if( $chk( window.parent.placeParam ) ){
			$$('.chk_item').each( function( v ){
				window.parent.placeParam.each( function( i ){
					if ( i.id == v.value ) {
						v.checked = true;
						if ( window.parent.flgStatus != 0) {
							var arrList = JSON.decode(window.parent.jsonContentList);
							if( arrList.some(function(item){ return item.id == v.value;})) {
								v.disabled = true;
							}
						}
					}
				});
			});
		}
		
		$('select-all').addEvent( 'click', function() {
			$$('.chk_item').each( function( el ){
				el.checked = this.checked;
			},this );
		});
		
		$('choose').addEvent('click', function() {
			var arrChk = new Array();
			var i = 0;
			$$('.chk_item').each( function( v ) {
				if( v.checked ) {
					arrChk[i] = {'id':v.value, 'title':$('video_'+v.value+'_video_title').get('html')};
					i++;
				}
			});
			window.parent.placeParam = arrChk;
			window.parent.placeDo();
			window.parent.multibox.close();
		});
		
	}
});
{/literal}
</script>
</body>
</html>