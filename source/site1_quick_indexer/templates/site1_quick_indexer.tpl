{if $arrPrm.flg_tpl==1}
{include file="site1_quick_indexer_`$arrPrm.action`.tpl"}
{elseif $arrPrm.action}
		{include file="site1_quick_indexer_`$arrPrm.action`.tpl"}
{else}
	wrong action!
{/if}