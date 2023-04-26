{if $arrPrm.flg_tpl==1 || in_array( $arrPrm.action, array('multiboxplace','getcode','importpopup','multiboxlist') )}
	{include file="keyword_generator_`$arrPrm.action`.tpl"}
{elseif $arrPrm.action}
	<div class="heading">
		<a class="menu" href="{url name='keyword_generator' action='combine_keywords'}">Keyword Mixer</a> | 
		<a class="menu" href="{url name='keyword_generator' action='combine_url'}">URL Mixer</a> | 
		<a class="menu" href="{url name='keyword_generator' action='typo_generator'}">Typo Generator</a> 
	</div>
	<p>&nbsp;</p>
		{include file="keyword_generator_`$arrPrm.action`.tpl"}
{/if}