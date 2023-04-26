<br/>
<div align="right">
<form action="" method="GET" id="filter-form">
Site type: <select name="site_type" id="filter_type">
				<option value="">- select -
{if Project_Users::haveAccess( 'Unlimited' )}
				<option {if $smarty.get.site_type == 1}selected='1'{/if} value="1">PSB
				<option {if $smarty.get.site_type == 2}selected='1'{/if} value="2">NCSB
				<option {if $smarty.get.site_type == 3}selected='1'{/if} value="3">NVSB
				<option {if $smarty.get.site_type == 4}selected='1'{/if} value="4">CNB
{/if}
				<option {if $smarty.get.site_type == 5}selected='1'{/if} value="5">Blog Fusion
		   </select>
		   <br/>
		   <br/>
		   <input type="button" id="filter" value="Filter" />
</form>		   
</div>
{if $msg == 'delete'}
<div class="grn">Site has been deleted</div>
{elseif $msg=='delete_error'}
<div class="red">Site can't be deleted</div>
{elseif $msg=='added'}
<div class="grn">Site has been added</div>
{elseif $msg=='add_error'}
<div class="grn">Site can't be added</div>
{/if}
{if $error}
<div class="red">{$error}</div>
{/if}
<br/>
Add site 
{if Project_Users::haveAccess( 'Unlimited' )}
<a href="{url name="site1_psb" action="multiboxlist"}" class="mb" rel="width:800,height:500">PSB</a>
&nbsp;&nbsp;&nbsp;<a href="{url name="site1_ncsb" action="multiboxlist"}" class="mb" rel="width:800,height:500">NCSB</a>
&nbsp;&nbsp;&nbsp;<a href="{url name="site1_nvsb" action="multiboxlist"}" class="mb" rel="width:800,height:500">NVSB</a>
&nbsp;&nbsp;&nbsp;<a href="{url name="site1_cnb" action="multiboxlist"}" class="mb" rel="width:800,height:500">CNB</a>
{/if}
&nbsp;&nbsp;&nbsp;<a href="{url name="site1_blogfusion" action="multiboxlist"}?noversion=1" class="mb" rel="width:800,height:500">Blog fusion</a>
<br/>
<br/>
<table style="width:100%;">
<thead>
<tr>
	<th>Site title
		{if $arrPg.recall>1}
			{if $arrFilter.order!='title--up'}<a href="{url name='site1_syndication' action='site_manage' wg='order=title--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='title--dn'}<a href="{url name='site1_syndication' action='site_manage' wg='order=title--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>
	<th align="center" width="10%">Site Type
		{if $arrPg.recall>1}
			{if $arrFilter.order!='flg_type--up'}<a href="{url name='site1_syndication' action='site_manage' wg='order=flg_type--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='flg_type--dn'}<a href="{url name='site1_syndication' action='site_manage' wg='order=flg_type--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>	
	{*<th>Site URL
		{if $arrPg.recall>1}
			{if $arrFilter.order!='url--up'}<a href="{url name='site1_syndication' action='site_site_manage' wg='order=url--up'}"><img src="/skin/i/backend/up.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/up_off.gif" width="5" height="11" alt="" />{/if}{if $arrFilter.order!='url--dn'}<a href="{url name='site1_syndication' action='site_manage' wg='order=url--dn'}"><img src="/skin/i/backend/down.gif" width="5" height="11" alt="" /></a>{else}<img src="/skin/i/backend/down_off.gif" width="5" height="11" alt="" />{/if}
		{/if}
	</th>*}
	<th width="10%">Options</th>
</tr>
</thead>
<tbody>
{foreach from=$arrList key='k' item='v'}
<tr {if $k%2=='0'} class="matros"{/if}>
	<td><a href="{$v.url}" target="_blank">{$v.title}</a></td>
	<td align="center" >{if $v.flg_type == 5}BF{elseif $v.flg_type == 4}CNB{elseif $v.flg_type==3}NVSB{elseif $v.flg_type==2}NCSB{elseif $v.flg_type==1}PSB{/if}</td>
	{*<td><a href="{$v.url}" target="_blank">{$v.url}</a></td>*}
	<td align="center" class="option">
		<a href="{url name='site1_syndication' action='site_manage'}?del={$v.id}"><img title="Delete" src="/skin/i/frontends/design/buttons/delete.png" /></a>	 
	</td>
</tr>
{/foreach}
</tbody>
</table>
<br/>
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>
<form action="" method="POST" id="form-add">
<input type="hidden" id="jsonSiteList" name="jsonSite" value='{$jsonSites}'  />
 </form>
{literal}
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
<script type="text/javascript">
var withUrl=true;
var jsonSites={/literal}{$jsonSites}{literal};
var siteMultiboxDo=function(){
	var arr=jsonSites;
	var arrSites=JSON.decode($('jsonSiteList').value);
	var i=0;
	var temp=new Array();
	arrSites.each(function( item ){
		if( !arr.some(function( v ){ return item.site_id == v.site_id && item.flg_type==v.flg_type; }) ){
			temp[i]=item;		
			i++;
		}
	});
	if( temp.length ){
		$('jsonSiteList').value=JSON.encode(temp);
		$( 'form-add' ).submit();
	}
};
var multibox = {};
window.addEvent('domready', function(){
	$('filter').addEvent('click', function(){
		var myURI = new URI();
		if( $('filter_type').value ){
			myURI.setData({'site_type':$('filter_type').value});	
		} else {
			myURI.clearData();
		}
		myURI.go();
	});
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		showControls: false,
		useOverlay: true,
		nobuttons: true
	});
});
</script>
{/literal}