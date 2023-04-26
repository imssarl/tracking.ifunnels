<div>
	<form method="post" action="{Core_Module_Router::$uriFull}" name="co_type">
	<label style="width:60px;">Select Event Site</label>
	<select name="filter" class="elogin" style="width:200px;float:left;" onchange="co_type.submit();">
	{if $site_selected}
		{html_options options=$sys_sites_list selected=$site_selected}
	{else}
	<option value="" >---Select Event Site---</option>
		{html_options options=$sys_sites_list}
	{/if}
	</select>
	</form>
</div>

<div>
{if $event_list}
	<form method="post" action="" id="u_list" name="u_list">
	<table class="info glow" style="float:left;">
	<thead>
	<tr>
		<th width="1px;"><label for="assign_ch" style="width:50px;">del</label><input type="checkbox" onClick="toggle_multicheckbox('u_list','DelList',this);" id="assign_ch" /></th>
		<th>event sys name</th>
		<th>event title</th>
		<th>event site name</th>
		<th>description</th>
		<th>edit</th>
	</tr>
	</thead>
	{foreach from=$event_list key=key item=value}
	<tr{if $key%2==0} class="matros"{/if}>
		<td><input type="checkbox" name="DelList[{$value.id}]" value="{$value.id}"/></td>
		<td>{$value.event_sys_name}</td>
		<td>{$value.title}</td>
		<td>{$value.sys_name}</td>
		<td>{$value.description}</td>
		<td><a href="?edit=yes&id={$value.id}" >edit</a></td>
	</tr>
	{/foreach}
	<tfoot>
		<tr><td colspan="10">
			<a href="#" onClick="u_list.submit();return false;">update</a>
		</td></tr>
	</tfoot>
	</table>
	</form>
{else}
	<div style="float:left;width:100%;"></div>
	<div class="red" style="margin:80px auto;text-align:center;"><b>no events exist</b></div>
{/if}
</div>

<div style="margin-top:30px;">
<div><h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2></div>
<div></div>
<div style="width:600px;">
	<form aciton="" method="POST" id="add_event" name="add_event">
	<label{if $arrErr.sys_name} class="red"{/if}>Event system name: {if $arrErr.sys_name_exist}<label class="red">This event already exists</label>{/if}</label><br>&nbsp;<br>{if $form_data.id}{$form_data.sys_name}{else}<input type="text" name="event[sys_name]" class="elogin" style="width:600px;" value="{$form_data.sys_name}">{/if}<br>
	<input type="hidden" name="event[id]" value="{$form_data.id}">
	<label{if $arrErr.title} class="red"{/if}>Event title: </label><br><input type="text" name="event[title]" class="elogin" style="width:600px;" value="{$form_data.title}"><br>
	<label{if $arrErr.description} class="red"{/if}>Event description: </label><br><textarea name="event[description]" class="elogin" style="width:600px;">{$form_data.description}</textarea><br>
	<label{if $arrErr.sites_id} class="red"{/if}>Select site: </label><br><select name="event[sites_id]">
	{html_options options=$sys_sites_list selected=$form_data.sites_id}
	</select><br><br>
	{if $form_data.id}
	<a href="#" onClick="add_event.submit();return false;">Update Event</a>
	{else}
	<a href="#" onClick="add_event.submit();return false;">Add Event</a>
	{/if}
	</form>
</div>
</div>




	
<div>
	
</div>
