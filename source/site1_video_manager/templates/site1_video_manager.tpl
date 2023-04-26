{if in_array( $arrPrm.action, array( 'view', 'multibox', 'options' ) )}
	{include file="site1_video_manager_`$arrPrm.action`.tpl"}
{elseif $arrPrm.action}
	<div style="text-align:center;margin-bottom:10px;"><a href="{$config->path->html->user_files}cnm_help/videomanager.pdf">Need help? Download the user's guide HERE</a></div>
	<div class="heading">
		<a class="menu" href="{url name='site1_video_manager' action='category'}">Manage Category</a> | 
		<a class="menu" href="{url name='site1_video_manager' action='video'}">Manage Videos</a> | 
		<a class="menu" href="{url name='site1_video_manager' action='add'}">Add Video</a> | 
		<a class="menu" href="{url name='site1_video_manager' action='import'}">Mass Import</a>
	</div>
	<div style="padding-top:10px;margin:0 auto;width:80%;">
	{if in_array( $arrPrm.action, array( 'add', 'edit' ) )}
		{include file="site1_video_manager_add.tpl"}
	{else}
		{include file="site1_video_manager_`$arrPrm.action`.tpl"}
	{/if}
	</div>
{else}
	wrong action!
{/if}