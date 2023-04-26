{if $arrPrm.action}
	<div class="heading">
		<a class="menu" href="{url name='site1_profile' action='create'}">Create profile</a> | 
		<a class="menu" href="{url name='site1_profile' action='manage'}">Manage profile</a>
	</div>
	{if Project_Users::haveAccess( array('Site Profit Bot Hosted' ) )}
	<br/>
	<center>Please refer to the <a href="http://members.creativenichemanager.info/usersdata/cnm_help/spbhosted.pdf" target="_blank">Site Profit Bot Hosted User's guide</a> to see how to use this section correctly</center>
	{/if}	
	{if Project_Users::haveAccess( array('Site Profit Bot Pro' ) )}
	<br/>
	<center>Please refer to the <a href="http://members.creativenichemanager.info/usersdata/cnm_help/spbpro.pdf" target="_blank">Site Profit Bot Pro User's guide</a> to see how to use this section correctly</center>
	{/if}
	{if in_array( $arrPrm.action, array('create','edit') )}
		{include file="site1_profile_create.tpl"}
	{else}
		{include file="site1_profile_`$arrPrm.action`.tpl"}
	{/if}
{else}
	wrong action!
{/if}