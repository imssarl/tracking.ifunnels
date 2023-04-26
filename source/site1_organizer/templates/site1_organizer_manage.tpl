<br/>
{if $msg == 'delete'}
<div class="grn">Note has been deleted</div>
{elseif $msg=='delete_error'}
<div class="red">Note can't be deleted</div>
{elseif $msg=='created'}
<div class="grn">Note has been created</div>
{elseif $msg=='saved'}
<div class="grn">Note has been saved</div>
{/if}
{if $error}
<div class="red">{$error}</div>
{/if}
<p>
	<input type="submit" value="Add Note" id="add" />
</p>
<form action="" class="wh" id="current-form" style="display:none; width:50%" method="post">
<input type="hidden" name="arrData[id]" id="form-id" />
<fieldset>
	<legend id="legend">Add Note</legend>
	<ol>
		<li>
			<label>Title</label><input type="text" id="form-title" name="arrData[title]" value="{$arrData.title}" />
		</li>
		<li>
			<label>Note</label><textarea rows="15" id="form-note" name="arrData[description]" >{$arrData.description}</textarea>
		</li>
		<li>
			<label></label><input type="submit" value="Save" />
		</li>
	</ol>
</fieldset>
</form>
<table style="width:100%">
	<thead>
	<tr>
		<th style="padding-right:0;" width="30">S.No</th>
		<th width="30%">Title{if count($arrList)>1}
			{if $arrFilter.order!='profile_name--up'}<a href="{url name='site1_organizer' action='manage' wg='order=profile_name--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='profile_name--dn'}<a href="{url name='site1_organizer' action='manage' wg='order=profile_name--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th>Note</th>
		<th width="10%">Date{if count($arrList)>1}
			{if $arrFilter.order!='date_created--up'}<a href="{url name='site1_organizer' action='manage' wg='order=date_created--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='date_created--dn'}<a href="{url name='site1_organizer' action='manage' wg='order=date_created--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}</th>
		<th width="10%">Options</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arrList item='v' key='k'}
	<tr{if $k%2!='0'} class="matros"{/if}>
		<td style="padding-right:0;">{counter}</td>
		<td>&nbsp;{$v.title|truncate:'150':'...'}<span style="display:none;" id="title-{$v.id}">{$v.title}</span></td>
		<td>{$v.description|truncate:'150':'...'}<span style="display:none;" id="note-{$v.id}">{$v.description}</span></td>
		<td align="center">{$v.added|date_format:'Y-m-d'}</td>
		<td align="center">
			<a href="#mb"  rel="type:element,width:600,height:auto" id="{$v.id}" class="mb view"><img style="display:inline" title="Edit" src="/skin/i/frontends/design/buttons/view.gif" /></a>
			<a href="#" class="edit" rel="{$v.id}"><img title="Edit" src="/skin/i/frontends/design/buttons/edit.png" /></a>
			<a href="{url name='site1_organizer' action='manage'}?delete={$v.id}"><img title="Delete" src="/skin/i/frontends/design/buttons/delete.png" /></a>
			<a href="{url name='site1_organizer' action='manage'}?archive={$v.id}">archive</a>
		</td>
	</tr>	
	{/foreach}
	</tbody>
</table>
<div id="mb" style="display:none;padding:20px;">
	<h3 id="title"></h3>
	<p id="note"></p>
</div>
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script>
var multibox = {};
window.addEvent('domready',function(){
	$$('.view').each(function(el){ 
		el.addEvent('click',function(){
			$('title').set('html',$('title-'+el.id).get('html'));
			$('note').set('html',$('note-'+el.id).get('html'));
		});
	});
	$$('.edit').each(function(el){
		el.addEvent('click',function(e){ 
			e.stop();
			$('legend').set('html','Edit Note');
			$('form-id').set('value',el.rel);
			$('form-title').set('value',$('title-'+el.rel).get('html'));
			$('form-note').set('value',$('note-'+el.rel).get('html'));
			$('current-form').setStyle('display', 'block' );
		});
	});
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});		
	$('add').addEvent('click',function(e){
		e.stop();
		$('form-id').set('value','');
		$('form-title').set('value','');
		$('form-note').set('value','');		
		$('legend').set('html','Add Note');
		$('current-form').setStyle('display', ($('current-form').style.display=='none')?'block':'none' );
	});
});
</script>
{/literal}