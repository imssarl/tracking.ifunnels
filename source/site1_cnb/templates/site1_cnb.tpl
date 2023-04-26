{if $arrPrm.flg_tpl==1 || $arrPrm.action=='admin_templates'}
{include file="site1_cnb_`$arrPrm.action`.tpl"}
{elseif $arrPrm.action}
	{if $arrPrm.action!='templates'}
	<div class="heading">
		<a class="menu" href="{url name='site1_cnb' action='create'}">Create CNB site</a> | 
		<a class="menu" href="{url name='site1_cnb' action='portal'}">Register Portal</a> | 
		<a class="menu" href="{url name='site1_cnb' action='import'}">Register CNB site</a> | 
		<a class="menu" href="{url name='site1_cnb' action='manage'}">Manage CNB Sites</a> | 
		<a class="menu" href="{url name='site1_cnb' action='fronttemplates'}">Manage Template</a>
		{module name='site1_accounts' action='manage' strCurrent='site1_cnb'}
	</div>
	{/if}
	{if in_array( $arrPrm.action, array('create','edit') )}
		{include file="site1_cnb_create.tpl"}
	{else}
		{include file="site1_cnb_`$arrPrm.action`.tpl"}
	{/if}
{else}
	wrong action!
{/if}