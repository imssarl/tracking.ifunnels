<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<script type="text/javascript" src="/skin/_js/moopop.js"></script>	
</head>
<body style="padding:10px;">


{foreach  Project_Content::toLabelArray() item=name key=ids}{if {$name.flg_source}=={$smarty.get.flg_source}}{$arrOfSource = $name}{/if}{/foreach}
<div style="display:none">
{foreach $smarty.get.arrFlt item=value key=number}
<p>{$number} = {$value}</p>
{/foreach}
</div>

{if $smarty.get.label}
{include file="selectcontents/{$smarty.get.label}.tpl"}
{else}
{include file="selectcontents/{$arrOfSource.label}.tpl"}
{/if}

{literal}
<script type="text/javascript">
window.addEvent('domready', function(){
// фильтрация контента (??)
/*	$('content-filter').addEvent('submit',function(e){
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
				embed_code: $(el.rel+'_embed_code').get('html'),
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
			window.parent.placeParam={embed_code: $(el.rel+'_embed_code').get('html')};
			window.parent.placeDo();
			window.parent.multibox.close();
		}.bindWithEvent(this, el));
	});
	*/
	
	// нажатие клавиши Choose (для всех одинаковое)
	if( $('choose') ) {
		// initialized list
		if( window.parent.placeParam != null || window.parent.jsonContentIds != null ){
			$$('.chk_item').each( function( v ){
				window.parent.placeParam.each( function( i ){
					if ( i.title == v.value ) {
						v.checked = true;
					}
				});
				//var contentIds = JSON.decode( window.parent.jsonContentIds );
				window.parent.jsonContentIds.each( function( c ){
					if ( c == v.value ) {
						v.checked = true;
						v.disabled = 'disabled';
					}
				});
			});
		}
		$('choose').addEvent('click', function() {
			var arrChk = new Array();
			var i = 0;
			$$('.chk_item').each( function( v ) {
				if( v.checked && !v.disabled ) {
					arrChk[i] = {'id':v.id, 'title':$('content_'+v.id+'_title').get('html')};
					i++;
				}
			});
			window.parent.placeParam = arrChk;
			window.parent.placeDo();
			window.parent.multibox.close();
		});
				
	$('select_all').addEvent( 'click', function() {
			$$('.chk_item').each( function( el ){
				el.checked = this.checked;
			},this );
		});
	}
});
</script>
{/literal}
</body>
</html>