<table style="width:100%;">
<thead>
<tr>
	<th width="20">Id number</th>
	<th>Title</th>	
	<th width="20"><input type="checkbox" id="select_all" /></th>
</tr>
</thead>
<tbody>
{foreach from=$arrList key=k item=v}
<tr {if $k%2=='0'} class="matros"{/if}>
	<td align="center">{$v.list_id}</td>
	<td><span id="content_{$v.list_id}_title">{$v.list_title}</span></td>
	<td align="center" class="option">
	<input type="checkbox" value="{$v.list_title}" id="{$v.list_id}" class="chk_item" />
	</td>
</tr>
{/foreach}
</tbody>
</table>
<br/>
<div align="right">
{include file="../../pgg_frontend.tpl"}
</div>
<div align="center"><p><input type="button" value="Choose" id="choosekeyw"></p></div>
{literal}
<script type="text/javascript">
window.addEvent('domready', function(){
	$('select_all').addEvent('click', function(){
		$$('.chk_item').each(function(el){
			el.checked=$('select_all').checked;
		});
	});
	$('choosekeyw').addEvent('click', function(){
		var arr=new Array();
		var i=0;
		$$('.chk_item').each(function(v){
			if(v.checked){
				arr[i]=v.value;
				i++;
			}
		});
		var strJson = JSON.encode(arr);
		var req = new Request({
				url: "",
				onComplete:function(r){
					var arrRes = JSON.decode(r);
					window.parent.setKeyword(arrRes);
					window.parent.multibox.close();
				},
			}).get({ 'keyword':1, 'jsonIds':strJson });	
	});
});
</script>
{/literal}