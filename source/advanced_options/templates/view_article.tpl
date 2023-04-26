{if $type_view == 'one'}
<table>
	<tr>
		<td>{$article.title}</td>
	</tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr>
		<td>{$article.body|nl2br}</td>
	</tr>
</table>
{else}
{foreach from=$articles item=article}
<table>
	<tr>
		<td>{$article.title}</td>
	</tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr>
		<td>{$article.body|nl2br}</td>
	</tr>
</table>
{/foreach}
{/if}
