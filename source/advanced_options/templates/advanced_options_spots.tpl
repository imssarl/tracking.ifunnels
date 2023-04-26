{if $type == Project_Options::ARTICLE}
	{include file="advanced_options_spots_selections.tpl"}
{/if}
{if $type == Project_Options::SNIPPET}
	{include file="advanced_options_spots_snippets.tpl"}
{/if}
{if $type == Project_Options::VIDEO}
	{include file="advanced_options_spots_video.tpl"}
{/if}