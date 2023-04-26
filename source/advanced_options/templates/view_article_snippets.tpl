{literal}
<script language="JavaScript">
function openNewWindow(url) {
	popupWin = window.open(url,
		'open_window',
		'menubar=0, toolbar=0, location=1, directories=0, status=0, scrollbars, resizable=0, dependent, width=400, height=500, left=0, top=0')
	}
</script>
{/literal}

{if $type_view == 'one'}
<table>
	<tr>
		<td><a href="javascript:openNewWindow('http://{$path}/cronjobs/getcontent.php?type_view=showarticles&id={$article.encode_id}');" class="a">{$article.title}</a></td>
	</tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr>
		<td>{$article.summary|nl2br}</td>
	</tr>
</table>
{else}
{foreach from=$articles item=article}
<table>
	<tr>
		<td><a href="javascript:openNewWindow('http://{$path}/cronjobs/getcontent.php?type_view=showarticles&id={$article.encode_id}');" class="a">{$article.title}</a></td>
	</tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr>
		<td>{$article.summary|nl2br}</td>
	</tr>
</table>
{/foreach}
{/if}