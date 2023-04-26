<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{module name='site1' action='head'}
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
{if $arrArticle}
	<div style="width:90%;padding:5px;">
		<ol>
			<li><label><span><b>Source:</b></span> {$arrSelect.source[$arrArticle.source_id]}</label></li>
			<li><label><span><b>Category:</b></span> {$arrSelect.category[$arrArticle.category_id]}</label></li>
			<li><label><span><b>Status:</b></span> {if $arrArticle.flg_status=='1'}Active{else}InActive{/if}</label></li>
			<li><label><span><b>Title:</b></span> {$arrArticle.title}</label></li>
			<li><label><span><b>Author:</b></span> {$arrArticle.author}</label></li>
			<li>
				<label><span><b>Summary:</b></span></label>
				<p>{$arrArticle.summary|nl2br}</p>
			</li>
			<li>
				<label><span><b>Body:</b></span></label>
				<p>{$arrArticle.body|nl2br}</p>
			</li>
		</ol>
	</div>
{/if}
</body>
</html>