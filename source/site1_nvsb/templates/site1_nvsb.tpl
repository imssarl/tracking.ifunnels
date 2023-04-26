{if $arrPrm.flg_tpl==1 || $arrPrm.action=='admin_templates'}
{include file="site1_nvsb_`$arrPrm.action`.tpl"}
{elseif $arrPrm.action}
	<div class="heading">
		<a class="menu" href="{url name='site1_nvsb' action='create'}">Create NVSB site</a> | 
		<a class="menu" href="{url name='site1_nvsb' action='import'}">Register NVSB site</a> | 
		<a class="menu" href="{url name='site1_nvsb' action='manage'}">Manage NVSB Sites</a> | 
		<a class="menu" href="{url name='site1_nvsb' action='templates'}">Manage Template</a>
		{if Project_Users::haveAccess( array( 'Unlimited' ) )}{module name='site1_accounts' action='manage' strCurrent='site1_nvsb'}{/if}
	</div>
	{if in_array( $arrPrm.action, array('create','edit') )}
		{include file="site1_nvsb_create.tpl"}
	{else}
		{include file="site1_nvsb_`$arrPrm.action`.tpl"}
	{/if}
{else}
	wrong action!
{/if}