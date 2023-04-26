<br/>
<div align="center">The latest stable release of WordPress (Version <b>{$newVersion}</b>)</div>
<br/>
<form class="wh" style="width:60%" method="POST" id="form-settings" action="" enctype="multipart/form-data">
<input type="hidden" name="arrSettings[flg_status]" value="1" />
<input type="hidden" id="jsonSiteList" name="jsonBlogs" value="" />
{if $arrSettings.id}<input type="hidden" name="arrSettings[id]" value="{$arrSettings.id}" />{/if}
	<fieldset>
		<legend>Upgrade</legend>
		<ol>
			{if $arrSettings.flg_status==1}
			<li>
				<label><b>Update is running.</b></label><input type="submit" value="Stop updating" />
			</li>
			<input type="hidden" name="arrSettings[flg_status]" value="0" >
			{/if}
			<li>
				<fieldset>
					<legend>Mode:</legend>
						<label><input{if $arrSettings.flg_status==1} disabled='1'{/if} type="radio" name="arrSettings[flg_mode]"{if $arrSettings.flg_mode==0} checked='1'{/if} value="0" class="mode" >&nbsp;all</label>
						<label>{if empty( $intNumOldBlogs )}all blogs are updated to latest version{else}<input{if $arrSettings.flg_status==1} disabled='1'{/if} type="radio" name="arrSettings[flg_mode]"{if $arrSettings.flg_mode==1} checked='1'{/if} value="1" class="mode" id="view-link" >&nbsp;blog list <a{if $arrSettings.flg_status==1} disabled='1'{/if} id="select-link" style="display:{if $arrSettings.flg_mode==1 && $arrSettings.flg_status != 1}inline{else}none{/if};" href="{url name="site1_blogfusion" action="multiboxlist"}"  rel="width:800" class="mb"  >select</a>{/if}</label>
				</fieldset>
			</li>
			<li><input type="hidden" name="arrSettings[flg_auto]" value="0"/>
				<label>Automatic Upgrade:</label><input {if $arrSettings.flg_status==1}disabled='1'{/if} type="checkbox" name="arrSettings[flg_auto]" {if $arrSettings.flg_auto==1}checked='1'{/if} value="1">
			</li>
			<li>
				<input type="submit" id="submit"  {if $arrSettings.flg_status==1}disabled='1'{/if} value="Save settings">
			</li>
		</ol>
	</fieldset>
</form>

{if $arrList}
<table width="100%">
	<thead>
	<tr>
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
		<th width="30%">Update Status</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$arrList item='i' key='k'}
	<tr{if $k%2=='0'} class="matros"{/if}>
		<td>{$i.title}<br/><a href="{$i.url}" target="_blank">{$i.url}</a></td>
		<td>{if $i.category}{$i.category}{else}<a class="mb select-category"  href="#mb" title="Select category" rel="type:element,width:400" rev="{$i.id}">Select category</a>{/if}</td>
		<td><a target="_blank" href="{$i.url}wp-login.php">Dashboard</a> ({$i.dashboad_username}/{$i.dashboad_password})</td>
		<td align="center">{$i.version}</td>
		<td align="center">{if $arrBlogsStatus[$i.id].flg_update==0}pending{elseif $arrBlogsStatus[$i.id].flg_update==1}in process{elseif $arrBlogsStatus[$i.id].flg_update==2}error{elseif $arrBlogsStatus[$i.id].flg_update==3}updated{/if}</td>
	</tr>	
	{/foreach}
	</tbody>
</table>
{/if}

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script type="text/javascript">
var multibox = {};
window.addEvent('domready', function(){
	$$('.mode').each(function(el){
		el.addEvent('click', function(){
			if( $('select-link') ) {
				$('select-link').setStyle('display',( $('view-link').checked ) ? 'inline':'none');
			}
		});
	});
	$('submit').addEvent('click', function(e){
		if( !$chk($('jsonSiteList').value) && $('view-link').checked ) {
			r.alert( 'Messages', 'Error! Please select blogs to update.', 'roar_error' );
			e.stop();
		}		
	});

	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});	
	
});	
var siteMultiboxDo = function(){};
</script>
{/literal}