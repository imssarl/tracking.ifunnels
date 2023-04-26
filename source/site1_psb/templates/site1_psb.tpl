{if $arrPrm.flg_tpl==1}
{include file="site1_psb_`$arrPrm.action`.tpl"}
{elseif $arrPrm.action}
	{if $arrPrm.action!='templates'}
	<div class="heading">
		<a class="menu" href="{url name='site1_psb' action='create'}">Create SPB site</a> | 
		<a class="menu" href="{url name='site1_psb' action='import'}">Register SPB site</a> | 
		<a class="menu" href="{url name='site1_psb' action='manage'}">Manage SPB Sites</a> | 
		<a class="menu" href="{url name='site1_psb' action='fronttemplates'}">Manage Template</a>
		{if Project_Users::haveAccess( array( 'Unlimited' ) )}{module name='site1_accounts' action='manage' strCurrent='site1_psb'}{/if}
	</div>
	{/if}
	{if in_array( $arrPrm.action, array('create','edit') )}
		{include file="site1_psb_create.tpl"}
	{else}
		{include file="site1_psb_`$arrPrm.action`.tpl"}
	{/if}
{else}
	wrong action!
{/if}