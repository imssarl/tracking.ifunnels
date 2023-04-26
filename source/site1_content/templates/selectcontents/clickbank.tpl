<table style="width:100%;">
<thead>
<tr>
	<th width="20">Id number</th>
	<th>Title</th>	
	<th width="20"><input type="checkbox" id="select_all" /></th>
</tr>
</thead>
<tbody>
{foreach from=$arrClick key=k item=v}
<tr {if $k%2=='0'} class="matros"{/if}>
	<td align="center">{$v.id}</td>
	<td><span id="content_{$v.id}_title">{$v.title}</span></td>
	<td align="center" class="option">
	<input type="checkbox" value="{$v.title}" id="{$v.id}" class="chk_item" />
	</td>
</tr>
{/foreach}
</tbody>
</table>
<br/>
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>
<div align="center"><p><input type="button" value="Choose" id="choose"></p></div>