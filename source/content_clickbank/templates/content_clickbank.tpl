{if $arrPrm.flg_tpl==3}
{elseif $arrPrm.flg_tpl==1}
	{include file="content_clickbank_`$arrPrm.action`.tpl"}
{elseif $arrPrm.action}
	{if $arrPrm.action!='set_cat_outer'}<h1>{$arrPrm.title}</h1>{/if}
	{include file="content_clickbank_`$arrPrm.action`.tpl"}
{else}
	wrong action!
{/if}