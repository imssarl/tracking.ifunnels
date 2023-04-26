<p><center>Total {$arrPg.recall} record(s) found. Showing 15 record(s) per page <br/><br/></center></p>
{if isset($deleteResult) && $deleteResult == true}
<p><center>Deleted Successfully</center></p>
{/if}
<table style="width:100%;" border="0">
<thead>
	<tr>
		<th width="50">ID</th>
		<th>Title</th>
		<th>Description</th>
		<th width="80">&nbsp;</th>
	</tr>
</thead>
<tbody>
{foreach from=$arrItems item=i name=j}
	<tr {if $smarty.foreach.j.iteration%2=='0'} class="matros"{/if}>
		<td>{$i.id}</td>
		<td>{$i.name}</td>
		<td>{$i.description}</td>
		<td align="center">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center"><a href="{url name='site1_articles' action='getcode'}?id={$i.id}" class="mb"   rel="width:530,height:390"><img title="View" src="/skin/i/frontends/design/buttons/view.gif" /></a></td>
					<td align="center"><a href="{url name='site1_articles' action='savedselections_edit'}?id={$i.id}{if $smarty.get.page}&page={$smarty.get.page}{/if}"><img title="Edit" src="/skin/i/frontends/design/buttons/edit.png" /></a></td>
					<td align="center"><a class="delete_action" href="{url name='site1_articles' action='savedselections'}?del={$i.id}{if $smarty.get.page}&page={$smarty.get.page}{/if}"><img title="Delete" src="/skin/i/frontends/design/buttons/delete.png" /></a>		</td>
				</tr>
			</table>
		</td>
	</tr>
{/foreach}
</tbody>
<tfoot>
	<tr>
		<td colspan="3" align="center">{include file="../../pgg_frontend.tpl"}</td>
		<td></td>
	</tr>
</tfoot>
</table>

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script>
var multibox = {};
window.addEvent("domready", function(){
	$$('.delete_action').each(function(el){
		el.addEvent('click', function(e){
			if( confirm("Are you sure you want to delete the saved selection?") ) {
				return true;
			} else {
				e.stop();
			}
		});
	});
	
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
	});		

});
</script>
{/literal}