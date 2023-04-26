{if $arrPrm.flg_tpl==1 || in_array( $arrPrm.action, array('multiboxplace','getcode','importpopup') )}
	{include file="site1_articles_`$arrPrm.action`.tpl"}
{elseif $arrPrm.action}
	<div class="heading">
		<a class="menu" href="{url name='site1_articles' action='category'}">Manage Category</a> | 
		<a class="menu" href="{url name='site1_articles' action='articles'}">Manage Articles</a> | 
		<a class="menu" href="{url name='site1_articles' action='add'}">Add Article</a> | 
		<a class="menu" href="{url name='site1_articles' action='import'}">Mass Import</a> | 
		<a class="menu" href="{url name='site1_articles' action='advancedopt'}">Advanced content display options</a> | 
		<a class="menu" href="{url name='site1_articles' action='savedselections'}">Saved selections</a> | 
		<a class="menu" href="{url name='site1_articles' action='rewriter'}">Article Rewriter</a>
	</div>
	{if in_array( $arrPrm.action, array('add','edit') )}
		{include file="site1_articles_add.tpl"}
	{else}
		{include file="site1_articles_`$arrPrm.action`.tpl"}
	{/if}
	{*<div style="padding-top:10px;margin:0 auto;width:80%;">{include file="site1_articles_`$arrPrm.action`.tpl"}</div>*}
{/if}