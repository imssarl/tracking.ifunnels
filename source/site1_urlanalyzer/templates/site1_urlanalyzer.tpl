{if $arrPrm.flg_tpl==1 || $arrPrm.action=='admin_templates'}
{include file="site1_urlanalyzer_`$arrPrm.action`.tpl"}
{elseif $arrPrm.action}
	<div class="heading">
		<a class="menu" href="{url name='site1_urlanalyzer' action='settings'}">Settings</a> 
	</div>
	{if in_array( $arrPrm.action, array('create','edit') )}
		{include file="site1_urlanalyzer_create.tpl"}
	{else}
		{include file="site1_urlanalyzer_`$arrPrm.action`.tpl"}
	{/if}
{else}
	wrong action!
{/if}