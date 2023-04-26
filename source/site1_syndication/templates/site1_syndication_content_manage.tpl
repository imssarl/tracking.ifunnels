<br/>
{if $msg == 'delete'}
	<div class="grn" style="padding:10px;">
		The Content was deleted successfully
	</div>
{/if}
{if $msg == 'delete_error'}
	<div class="red" style="padding:10px;">
		Error: Can't delete content.
	</div>
{/if}
<div style="clear:both;">
{if $arrList}

<table style="width:100%;">
<thead>
<tr>
	<th width="75%">Title</th>
	<th width="10%">Type Site</th>
	<th width="15%">Action</th>
</tr>
</thead>
<tbody>
{foreach from=$arrList key='k' item='v'}
<tr  class="{if $k%2!='0'}matros{/if} sites">
	<td>{if !empty($v.arrContent)}<a href="#" class="view-content" rel="{$v.id}">{$v.title}</a>{else}{$v.title}{/if}</td>
	<td align="center">{if $v.flg_type == 5}BF{elseif $v.flg_type == 4}CNB{elseif $v.flg_type==3}NVSB{elseif $v.flg_type==2}NCSB{elseif $v.flg_type==1}PSB{/if}</td>
	<td align="center">{if !empty($v.arrContent)}<a href="#" class="view-content" rel="{$v.id}">view content</a>{/if}</td>
</tr>
<tr id="{$v.id}" class="content" style="display:none;">
	<td colspan="3" align="center">
		<table style="border:1px solid #333; margin:0px 0 3px 30px;" width="700" >
			<tr>
				<th>Title</th>
				<th width="50">Action</th>
			</tr>
			{foreach from=$v.arrContent item=i key=key}
			<tr class="{if $key%2=='0'}matros{/if}">
				<td>{$i.title}</td>
				<td align="center">
					<a href="?del={$i.c2s_id}"><img title="Delete" src="/skin/i/frontends/design/buttons/delete.png" /></a>
				</td>
			</tr>
			{/foreach}
		</table>
	</td>
</tr>
{/foreach}
</tbody>
</table>
{literal}
<script type="text/javascript">
window.addEvent('domready', function(){
	$$('.view-content').each(function(item){
		item.addEvent('click', function(e){
			e && e.stop();
			$$('.sites').each(function(tr,i){
				tr.setStyle('background','none');
				tr.setStyle('background',( i%2 != 0 )?'#EAFAFA':'none');
			});
			$$('.content').each(function(content,i){ content.setStyle('display','none'); });
			$(item.rel).setStyle('display','block' );
			item.getParent('tr').setStyle('background','#99CCFF');;
		});
	});
});
</script>
{/literal}
{else}
	<p>no sites found</p>
{/if}
</div>
