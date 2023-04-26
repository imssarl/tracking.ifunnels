{if $arrPrm.action}
	{include file="tags_`$arrPrm.action`.tpl"}
{else}
	wrong action!
{/if}