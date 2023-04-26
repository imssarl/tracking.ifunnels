<div>
{if $emails}
 <form method="post" action="" id="u_list" name="u_list">
	<table class="info glow" style=" float:left;">
	<thead>
	<tr>
		<th width="1px;"><label for="assign_ch" style="width:50px;">del</label><input type="checkbox" onClick="toggle_multicheckbox('u_list','DelList',this);" id="assign_ch" /></th>
		<th>email</th>
		<th>name</th>
		<th>added</th>
		<th>edit</th>
	</tr>
	</thead>
	{foreach from=$emails key=key item=value}
	<tr{if $key%2==0} class="matros"{/if}>
		<td><input type="checkbox" name="DelList[{$value.id}]" value="{$value.id}"/></td>
		<td>{$value.email}</td>
		<td>{$value.name}</td>
		<td>{$value.added|date_format:"%Y-%m-%d %H:%M:%S"}</td>
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
	<div class="red" style="margin:80px auto;text-align:center;"><b>no emails exist</b></div>
{/if}
</div>

<div style="margin-top:30px;">
<div><h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2></div>
<div></div>
<div style="width:600px;">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
	<form aciton="" method="POST" id="add_email" name="add_email">
	<label{if $arrErr.email} class="red"{/if}>E-mail:</label><br><input type="text" name="email[email]" class="elogin" style="width:600px;" value="{$form_data.email}"><br>
	<input type="hidden" name="email[id]" value="{$form_data.id}">
	<label{if $arrErr.name} class="red"{/if}>Name To: </label><br><input type="text" name="email[name]" class="elogin" style="width:600px;" value="{$form_data.name}"><br>

	{if $form_data.id}
	<a href="#" onClick="add_email.submit();return false;">Update Email</a>
	{else}
	<label>Type list of emails (optional): </label>
	<textarea name="list" class="elogin"></textarea><br>
	<a href="#" onClick="add_email.submit();return false;">Add Email</a>
	{/if}
	</form>
</div>
</div>

