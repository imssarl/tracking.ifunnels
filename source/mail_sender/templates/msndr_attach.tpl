 <div>
		<form method="post" action="{Core_Module_Router::$uriFull}" name="co_type">
		<label style="width:60px;">Select event</label>
		<select name="event" class="elogin" style="width:200px;float:left;" onchange="co_type.submit();">
		{if $event_id}
			{html_options options=$events selected=$event_id}
		{else}
		<option value="" >---Select Event---</option>
			{html_options options=$events}
		{/if}
		</select>
		</form>
		{if $confirmation}<div style="float:left;" class="grn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your changes have been successfully added</div>{/if}
	</div>

{if $emails_to && $emails_from}
<div>
<form action="" method="post" name="u_set" id="u_set">
<input type="hidden" name="event_id" value="{$event_id}"><br><br>
	<div class="for_checkbox"><b>Select email to:</b> <span>(</span><label for="g_sel_all">select all</label>
		<input type="checkbox" onClick="toggle_multicheckbox('u_set','To',this);" id="g_sel_all" /><span>):</span>
	</div>
	<div style="clear:both">
	<table>
	<tr>
	{foreach from=$emails_to key='k' item=value}
		{if $k%2==0}
	</tr>
	<tr>
		{/if}
		<td width="30%" class="for_checkbox">
			<input type="checkbox" name="To[{$value.id}]" value=""{if $value.address_id} checked{/if} id="g_{$value.id}">
			<label for="g_{$value.id}">{$value.email} {if $value.name}&lt;{$value.name}&gt;{/if}</label>
		</td>
	{/foreach}
	</tr>
	</table>
	</div>
	


<div class="for_checkbox"><b>Select email from:</b> <span>(</span><label for="f_sel_all">select all</label>
		<input type="checkbox" onClick="toggle_multicheckbox('u_set','From',this);" id="f_sel_all" /><span>):{if !$from_not_empty}<div class="red">You don't have any 'From' emails marked</div>{/if}</span>
	</div>
	<div style="clear:both">
	<table>
	<tr>
	{foreach from=$emails_from key='k' item=value}
		{if $k%2==0}
	</tr>
	<tr>
		{/if}
		<td width="30%" class="for_checkbox">
			<input type="checkbox" name="From[{$value.id}]" value="" {if $value.address_id} checked{/if} id="f_{$value.id}">
			<label for="f_{$value.id}">{$value.email} {if $value.name} &lt;{$value.name}&gt;{/if}</label>
		</td>
	{/foreach}
	</tr>
	</table>
	</div>
<div><a href="#" onClick="u_set.submit(); return false;">update</a></div>

</form>

  {else}
    <div style="float:left;width:100%;"></div>
	<div class="red" style="margin:80px auto;text-align:center;"><b>select event, please</b></div>
    </div>



    
</div>
{/if}
</div>
