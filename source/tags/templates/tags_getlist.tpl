<div class='wh-tag-cloud'>
{if empty($arrPrm.item_id)}
	<textarea cols="10" rows="6" name="{$arrPrm.textarea_name}" class="wh-tag-textarea"></textarea>
{else}
	<div class="wh-tag-list"  id='wh-tag-cloud-{$arrPrm.item_id}'>{foreach from=$arrTags item=i name='tags'}<span>{if !empty($arrPrm.search_href)}<a target="_blank" href="{$arrPrm.search_href}?tag={$i.decoded}">{$i.decoded}</a>{else}{$i.decoded}{/if}<a href="#" class="wh-tag-delete" title="Delete tag"> x</a>{if !$smarty.foreach.tags.last}, {/if}</span>{/foreach}</div>
	<div class="wh-tag-actions"><input type="button" class='wh-tag-edit' value="Edit" /><input type="button" class="wh-tag-save" value="Save" /><input type="button" class="wh-tag-cancel" value="Cancel"> </div>
{/if}
</div>
<div class="wh-tag-clear"></div>
{literal}
<style>
	div.wh-tag-cloud{float:left; width:300px;}
	div.wh-tag-list{border:1px solid #FFF; padding:5px;}
	div.wh-tag-cloud a{text-decoration:none;}
	div.wh-tag-cloud a.wh-tag-delete{text-decoration:none; color:#FF0000;}
	div.wh-tag-clear{clear:both;}
	div.wh-tag-actions{ padding:10px 0 0 0;}
</style>
<script type="text/javascript">
	window.addEvent('domready',function(){
		var myTag{/literal}_{$arrPrm.item_id}{literal}=new Tags({
			url:'{/literal}{url name='tags' action='setlist'}{literal}',
			itemId:{/literal}{$arrPrm.item_id|default:0}{literal},
			type:{/literal}'{$arrPrm.type|default:'null'}'{literal},
			searchUrl:{/literal}'{$arrPrm.search_href|default:'null'}'{literal}
		});
	});
</script>
{/literal}
