{if $arrPrm.action}
	<div class="heading">
		<a class="menu" href="{url name='site1_affiliate' action='create'}">Create Affiliate Pages</a> | 
		<a class="menu" href="{url name='site1_affiliate' action='manage'}">Manage Affiliate Pages</a> 
	</div>
	{include file="site1_affiliate_`$arrPrm.action`.tpl"}
{else}
	wrong action!
{/if}