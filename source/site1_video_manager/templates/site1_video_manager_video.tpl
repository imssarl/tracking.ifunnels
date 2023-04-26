<br/>
{if $msg == 'delete'}
<div class="grn">Video has been deleted</div>
{elseif $msg=='delete_error'}
<div class="red">Video can't be deleted</div>
{elseif $msg=='duplicated'}
<div class="grn">Duplicate Video has been created</div>
{elseif $msg=='duplicated_error'}
<div class="grn">Duplicate Video can't be created</div>
{elseif $msg=='created'}
<div class="grn">Video has been created</div>
{elseif $msg=='saved'}
<div class="grn">Video has been saved</div>
{/if}
{if $error}
<div class="red">{$error}</div>
{/if}
<div style="float:right;">
<form method="post" action="" id="video-filter">
	<div style="float:left;">Category <select name="category" id='category-filter'>
		<option value=''> - select - </option>
		{html_options options=$arrSelect.category selected=$smarty.get.category}
	</select></div>
	<div style="float:left;padding-left:3px;"><input type="submit" value="Filter" /></div>
</form>
<script type="text/javascript">
{literal}
$('video-filter').addEvent('submit',function(e){
	e.stop();
	var myURI=new URI();
	if ( $('category-filter').value=='' ) {
		myURI.setData(new Hash(myURI.getData()).filter(function(value, key){return key!='category';}));
	} else {
		myURI.setData({category:$('category-filter').value}, true);
	}
	myURI.go();
});
{/literal}
</script>
</div>
{if $arrList}
<p>
	<input type="submit" value="Delete" id="delete" />
</p>
<form action="" id="current-form" method="post">
<input type="hidden" name="mode" value="" id="mode" />
<table style="width:100%">
	<thead>
	<tr>
		<th style="padding-right:0;" width="1px"><input type="checkbox" id="del" class="tooltip" title="mass delete" rel="check to select all" /></th>
		<th>Category{if count($arrList)>1}
			{if $arrFilter.order!='category_id--up'}<a href="{url name='site1_video_manager' action='video' wg='order=category_id--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='category_id--dn'}<a href="{url name='site1_video_manager' action='video' wg='order=category_id--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Source{if count($arrList)>1}
			{if $arrFilter.order!='source_id--up'}<a href="{url name='site1_video_manager' action='video' wg='order=source_id--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='source_id--dn'}<a href="{url name='site1_video_manager' action='video' wg='order=source_id--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Title{if count($arrList)>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_video_manager' action='video' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_video_manager' action='video' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Edited{if count($arrList)>1}
			{if $arrFilter.order!='edited--up'}<a href="{url name='site1_video_manager' action='video' wg='order=edited--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='edited--dn'}<a href="{url name='site1_video_manager' action='video' wg='order=edited--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Added{if count($arrList)>1}
			{if $arrFilter.order!='added--up'}<a href="{url name='site1_video_manager' action='video' wg='order=added--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='added--dn'}<a href="{url name='site1_video_manager' action='video' wg='order=added--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th width="10%">Options</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arrList item='v' key='k'}
	<tr{if $k%2!='0'} class="matros"{/if}>
		<td style="padding-right:0;"><input type="checkbox" name="del[{$v.id}]" class="check-me-del" id="check-{$v.id}" /></td>
		<td>&nbsp;{$arrSelect.category[$v.category_id]}</td>
		<td>{$arrSelect.source[$v.source_id]}</td>
		<td>{$v.title}</td>
		<td>{$v.edited|date_local:$config->date_time->dt_full_format}</td>
		<td>{$v.added|date_local:$config->date_time->dt_full_format}</td>
		<td align="center">
			<a href="{url name='site1_video_manager' action='edit'}?id={$v.id}"><img title="Edit" src="/skin/i/frontends/design/buttons/edit.png" /></a>
			<a href="{url name='site1_video_manager' action='view'}?id={$v.id}" class="vid" rel="width:800,height:550" title="'{$v.title}' preview"><img title="View" src="/skin/i/frontends/design/buttons/view.gif" /></a>
			<a href="{url name='site1_video_manager' action='video'}?dup={$v.id}"><img title="Duplicate" src="/skin/i/frontends/design/buttons/duplicate.png" /></a>
			<a href="#" rel="{$v.id}" class="click-me-del" id="{$v.id}"><img title="Delete" src="/skin/i/frontends/design/buttons/delete.png" /></a>
		</td>
	</tr>	
	{/foreach}
	</tbody>
</table>
</form>
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>
{else}
	<p>no videos found</p>
{/if}

{literal}
<script>
window.addEvent('domready',function(){
	checkboxToggle($('del'));
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
</script>
{/literal}

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script>
var multibox = {};
window.addEvent("domready", function(){
	multibox = new multiBox({
		mbClass: '.vid',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});
});
</script>
{/literal}