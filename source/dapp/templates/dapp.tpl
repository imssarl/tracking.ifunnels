<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

<div class="container-fluid">
	{if $arrPrm.action}
	<h3 class="title">{$arrPrm.title}</h3>
	{include file="dapp.`$arrPrm.action`.tpl"}
	{else}
	wrong action!
	{/if}
</div>