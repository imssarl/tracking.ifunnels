{if $arrPrm.flg_tpl!=1}
	<div class="heading">
		<a class="menu" href="{url name='site1_content' action='blog'}">Blog Content Projects</a>  
		{if !Project_Users::haveAccess( array( 'Blog Fusion' ) )}| 
		<a class="menu" href="{if Project_Users::haveAccess( array( 'Unlimited' ) )}{url name='site1_content' action='cnb'}{*/projects.php?which_site=0&which_project=all*}{else}{url name='site1_content' action='blog'}{/if}">CNB Content Projects</a> | 
		<a class="menu" href="{url name='site1_content' action='ncsb'}">NCSB Content Projects</a> | 
		<a class="menu" href="{url name='site1_accounts' action='externalData'}">Content Settings</a> 
		{/if}
	</div>
	{if $arrPrm.action}
	{include file="site1_content_`$arrPrm.action`.tpl"}
{else}
	wrong action!
{/if}
{elseif $arrPrm.flg_tpl}
	{include file="site1_content_`$arrPrm.action`.tpl"}
{/if}