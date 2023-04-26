{if $arrPrm.action}
	{include file="email_funnels_`$arrPrm.action`.tpl"}
{else}
	wrong action!
{/if}