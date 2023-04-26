{if $arrPrm.action}
	<div style="text-align:center;margin-bottom:10px;"><a href="{$config->path->html->user_files}cnm_help/socialbookmarking.pdf">Need help? Download the user's guide HERE</a></div>
	{include file="site1_sbookmarking_`$arrPrm.action`.tpl"}
{else}
	wrong action!
{/if}