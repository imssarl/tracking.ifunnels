<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{module name='site1' action='head'}
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<script type="text/javascript" src="/skin/_js/moopop.js"></script>
</head>
<body style="padding:10px;">
{if $msg=='success'}
<div class="grn">Status has been changed </div>
{elseif $msg=='error'}
<div class="red">Status can't be changed</div> 
{/if}

{if $blocked == 1 && !$msg}
Content removed or temporary bloked
{elseif $msg!='success'}
<form action="" method="POST" class="wh" style="width:100%;">
<input type="hidden" name="arr[id]" value="{$arrContent.id}" id="id">
<input type="hidden" name="arr[project_id]" value="{$arrContent.project_id}" >
<input type="hidden" name="arr[body]" value='{$arrContent.body}' />
<fieldset>
	<ol>
		<li>
			<div style="width:100%; padding:0 0 10px 0;">
				<h2>{$arrContent.title}</h2>
				{$arrContent.body|replace:"\n":'<br/>'}
			</div>
		</li>
		<li>
			<div style="width:100%; padding:0 0 10px 0;">Project category: {foreach $arrContent.projectCats as $cat}{$cat.title}{if !$cat@last}, {/if}{/foreach}.</div>
		</li>
		<li>
			<fieldset>
				<legend>Status <em>*</em></legend>
				<label><input type="radio" name="arr[flg_status]" {if $arr.flg_status == 3}checked='1'{/if} value="3" class="status"> approved</label>
				<label><input type="radio" name="arr[flg_status]" {if $arr.flg_status == 1}checked='1'{/if} value="1" class="status"> rejected</label>
			</fieldset>
		</li>
		<div id="cause" style="display:{if $arr.flg_status == 1}block{else}none{/if};">
		<li >
			<label>Cause <em>*</em></label><select name="arr[flg_cause]">
				<option value="0">- select -
				<option {if $arr.flg_cause == 1}checked='1'{/if} value="1">wrong category
				<option {if $arr.flg_cause == 2}checked='1'{/if} value="2">spam
				<option {if $arr.flg_cause == 3}checked='1'{/if} value="3">too hype
				<option {if $arr.flg_cause == 4}checked='1'{/if} value="4">poor english
			</select>
		</li>
		<li>
			<label>Comment</label><textarea name="arr[comment]" style="height:200px;">{$arr.comment}</textarea>
		</li>
		</div>
		<li>
			<input type="submit" value="Save" >
		</li>	
	</ol>
</fieldset>
</form>
{/if}

<script>
var result = '{$msg|default:'null'}';
{literal}
window.addEvent('domready',function(){
	$$('.status').each(function(el){
		el.addEvent('click', function(){
			$('cause').setStyle('display', ((el.value==1)?'block':'none') );
		});
	});
	if( result == 'success' ){
		window.parent.obj.initReload();
	} else {
		if( $('id') )
		window.parent.obj.setId2unblock( $('id').value );
	}
	
});
</script>
{/literal}

</body>
</html>