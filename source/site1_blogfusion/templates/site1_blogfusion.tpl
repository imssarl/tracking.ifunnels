{if $arrPrm.flg_tpl==1 || $arrPrm.local||$arrPrm.action=='warning'}
	{include file="site1_blogfusion_`$arrPrm.action`.tpl"}
{elseif $arrPrm.action}
	<div class="heading">
		<a class="menu" href="{url name='site1_blogfusion' action='create'}">Create blog</a> | 
		<a class="menu" href="{url name='site1_blogfusion' action='manage'}">Manage blogs</a> | 
		<a class="menu" href="{url name='site1_blogfusion' action='plugins'}">Plugins</a> | 
		<a class="menu" href="{url name='site1_blogfusion' action='themes'}">Themes</a>
		{if Project_Users::haveAccess( array( 'Unlimited' ) )}{module name='site1_accounts' action='manage' strCurrent='site1_blogfusion'}{/if}
	</div>
	{include file="site1_blogfusion_`$arrPrm.action`.tpl"}
{/if}