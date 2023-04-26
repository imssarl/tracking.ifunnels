{if $arrPrm.action}
	{include file="ftp_tools_`$arrPrm.action`.tpl"}
{else}
	wrong action!
{/if}