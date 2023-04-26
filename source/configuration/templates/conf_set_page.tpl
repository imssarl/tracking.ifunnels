<div>
	<a href="{url name='configuration' action='sites_map'}?root_id={$smarty.get.root_id}">back to current site map</a>
	{if $smarty.get.pid&&$smarty.get.id} | <a href="{url name='configuration' action='set_page'}?root_id={$smarty.get.root_id}&pid={$smarty.get.pid}">create another page</a>{/if}
	{if $smarty.get.id} | <a href="{url name='configuration' action='set_page'}?root_id={$smarty.get.root_id}&pid={$smarty.get.id}">create child page</a>{/if}
</div>
<div style="width:70%;">
<form method="post" action="" name="a_set" id="a_set" enctype="multipart/form-data">
<input type="hidden" name="mode" value="" id="mode"> {*при изменении parent page надо перерисовать page position*}
<input type="hidden" name="arrPage[id]" value="{$smarty.get.id}">
<input type="hidden" name="arrPage[root_id]" value="{$smarty.get.root_id}">
<div>
	<table class="info">
	<tr>
		<td width="30%">title:</td>
		<td><input type="text" name="arrPage[title]" value="{$arrPage.title|escape:"html"}" class="elogin"></td>
	</tr>
	<tr>
		<td{if $arrErr.sys_name_exists} class="red"{/if}>url name:</td>
		<td><input type="text" name="arrPage[sys_name]" value="{$arrPage.sys_name}" class="elogin"></td>
	</tr>
	{*изменять можно только для страниц фронтэнда и не рутовых*}
	{if $arrPage.id!=$smarty.get.root_id}
		{if $arrSite.flg_type==0}
			<tr>
				<td><nobr>parent page:</nobr><br/><small>(autodispatch field)</small></td>
				<td><select name="arrPage[pid]" class="elogin" onchange="$('mode').value='chenge_pid';a_set.submit();return false;">
					{function name=recursion}
						{if $tree}
							{foreach from=$tree item='v'}
							<option value="{$v.id}"{if $v.id==$selected} selected=""{/if}>{$v.title|indent:$v.level:"-&nbsp;"}</option>
							{if $v.node}
								{recursion tree=$v.node selected=$selected}
							{/if}
							{/foreach}
						{/if}
					{/function}
					{recursion tree=$arrTree selected=$arrPage.pid}
				</select></td>
			</tr>
		{else}
			<input type="hidden" name="arrPage[pid]" value="{$arrPage.pid}">
		{/if}
		{*изменять можно только для созданных страниц - кстати почему? TODO!!! *}
		<tr>
			<td>page position:</td>
			<td><select name="arrPage[position]" class="elogin">
					<option value="first">first page</option>
					{section name=i loop=$arrPos}
						{if $arrPos[i].id!=$arrPage.id}
							<option value="{$arrPos[i].id}"{if $arrPos[i.index_next].id==$arrPage.id} selected{/if}>
								after "{$arrPos[i].title}" page</option>
						{/if}
					{/section}
				</select></td>
		</tr>
	{/if}
	<tr>
		<td style="vertical-align:top;"><nobr>seo tool:</nobr><br/><small>(to fill description and keywords meta-tags)</small></td>
		<td><textarea name="full_description" rows="4" cols="20" class="elogin" style="overflow:auto"></textarea></td>
	</tr>
	<tr>
		<td style="vertical-align:top;"><nobr>description meta-tag:</nobr><br/><small>(200 - 250 chars may be indexed. this amount may be displayed partly.)</small></td>
		<td><textarea name="arrPage[meta_description]" rows="4" cols="20" class="elogin" style="overflow:auto">{$arrPage.meta_description}</textarea></td>
	</tr>
	<tr>
		<td style="vertical-align:top;"><nobr>keywords meta-tag:</nobr><br/><small>(Search engines indexed up to 1000 characters of text. Commas weren't required.)</small></td>
		<td><textarea name="arrPage[meta_keywords]" rows="4" cols="20" class="elogin" style="overflow:auto">{$arrPage.meta_keywords}</textarea></td>
	</tr>
	<tr>
		<td><nobr>robots meta-tag:</nobr><br/><small>(If checked, the page will be indexed by search engines.)</small></td>
		<td><input type="checkbox" name="arrPage[meta_robots]"{if $arrPage.meta_robots} checked{/if}></td>
	</tr>
	<tr>
		<td><nobr>show on site map:</nobr><br/><small>(If checked, the page will be shown on site map.)</small></td>
		<td><input type="checkbox" name="arrPage[flg_onmap]"{if $arrPage.flg_onmap} checked{/if}></td>
	</tr>
	<tr>
		<td>action:</td>
		<td><select name="arrPage[action_id]" class="elogin">
			<option value="0"> - select - </option>
			{html_options options=$arrModulesWithActions selected=$arrPage.action_id}
			</select></td>
	</tr>
	</table>
</div>
<div style="width:90%;text-align:center;clear:both;padding-top: 20px;"><a href="#" onclick="a_set.submit();return false;">{if $arrP}update{else}add{/if}</a></div>
</div>
</form>
</div>