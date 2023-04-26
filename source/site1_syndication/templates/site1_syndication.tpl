{if WorkHorse::$isBackend}
	{if  $arrPrm.flg_tpl==0}<h1>{$arrPrm.title}</h1>{/if}
	{include file="site1_syndication_`$arrPrm.action`.tpl"}
{elseif $arrPrm.action}
<div align="center"><p>You have <b>{$arrPoints.actual}</b> credits and you have <b>{$arrPoints.planned}</b> credits planned to be used by your syndication projects. Your balance is: <b>{$arrPoints.balance}</b></p><p>{if $arrPoints.balance>0}You can create more syndication projects if you wish{else}You would need to earn credits by publishing others' content before your projects can be reviewed{/if}.</p></div>
	<div class="heading">
		<a class="menu" href="{url name='site1_syndication' action='create'}">Create Project</a> | 
		<a class="menu" href="{url name='site1_syndication' action='manage'}">Manage Projects</a> | 
		<a class="menu" href="{url name='site1_syndication' action='site_manage'}">Syndicated Sites</a> |
		<a class="menu" href="{url name='site1_syndication' action='content_manage'}">Manage Content</a> 
	</div>
	{include file="site1_syndication_`$arrPrm.action`.tpl"}
{/if}