<br/>
<div align="center">
	<div  style="width:58%;">
		<a class="" href="{url name='site1_blogfusion' action='general'}?id={$smarty.get.id}"   rel="create_form">General</a> | 
		<a class="" href="{url name='site1_blogfusion' action='categories'}?id={$smarty.get.id}"   rel="create_form">Categories</a> | 
		<a class="" href="{url name='site1_blogfusion' action='posts'}?id={$smarty.get.id}"   rel="create_form">Posts</a> | 
		<a class="" href="{url name='site1_blogfusion' action='comments'}?id={$smarty.get.id}"   rel="create_form">Comments</a> | 
		<a class="" href="{url name='site1_blogfusion' action='pages'}?id={$smarty.get.id}"   rel="create_form">Pages</a> | 
		<a class="" href="{url name='site1_blogfusion' action='changetheme'}?id={$smarty.get.id}"   rel="create_form">Change theme</a> | 
		<a class="" href="{url name='site1_blogfusion' action='edittheme'}?id={$smarty.get.id}"   rel="create_form">Edit theme</a>
	</div>
</div>	
<br/>
<div align="center">
	<p><b>{$arrBlog.title}</b>&nbsp;<a href="{$arrBlog.url}" target="_blank">{$arrBlog.url}</a>&nbsp;<a target="_blank" href="{$arrBlog.url}wp-login.php">Dashboard</a> </p>
</div>
{literal}
<style>
.toggler{padding:5px 0 5px 0; cursor:pointer;}
.element{padding:5px 0 5px 0;}
ul.v-menu{padding:0px 0 0 10px; margin:0;}
ul.v-menu li{padding:2px; list-style:none;}
ul.v-menu li a{}
</style>
{/literal}
<table width="100%" border="0">
	<tr>
		<td width="200" valign="top" align="left">
			
			<ul class="v-menu">
				<li><h3>Blogs</h3> </li>
				{foreach from=$menuBlog item=i}
				<li> <a href="./?id={$i.id}">{$i.title|ellipsis:"30"}</a> </li>
				{/foreach}
			</ul>
		</td>
		<td align="left" valign="top">