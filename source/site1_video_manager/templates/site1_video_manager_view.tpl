<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{module name='site1' action='head'}
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
{if $arrItem}
	<div style="width:100%;padding:5px;">
<table cellpadding="0" cellspacing="0" class="inputform" style="width:60%;">
<tr>
	<td align="right" valign="top" style="width:30%;">Category:</td>
	<td align="left" valign="top">{$arrSelect.category[$arrItem.category_id]}</td>
</tr>
<tr>
	<td align="right" valign="top" style="width:30%;">Source:</td>
	<td align="left" valign="top">{$arrSelect.source[$arrItem.source_id]}</td>
</tr>
<tr>
	<td align="right" valign="top" style="width:30%;">Title:</td>
	<td align="left" valign="top">{$arrItem.title}</td>
</tr>
<tr>
	<td align="right" valign="top" style="width:30%;">Video:</td>
	<td align="left" valign="top">{$arrItem.body}</td>
</tr>
<tr>
	<td align="right" valign="top" style="width:30%;">Embed Code:</td>
	<td align="left" valign="top"><textarea name="" rows="10" cols="70">{$arrItem.body}</textarea></td>
</tr>
<tr>
	<td align="right" valign="top" style="width:30%;">URL of Video:</td>
	<td align="left" valign="top"><textarea name="" rows="3" cols="70">{$arrItem.url_of_video}</textarea></td>
</tr>
</table>
	</div>
{/if}
</body>
</html>