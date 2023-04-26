<link rel="stylesheet" href="/source/_js/tagscloud/tagscloud.css" />
<script type="text/javascript" src="/source/_js/tagscloud/tagscloud.js"></script>
<a href="#" onclick="cloud.draw('most_used');return false;">most used</a> |
<a href="#" onclick="cloud.draw('this_week_used');return false;">this week used</a> |
<a href="#" onclick="cloud.draw('most_searched');return false;">most searched</a> |
<a href="#" onclick="cloud.draw('this_week_searched');return false;">this week searched</a>
<ol id="site1_cloud" class="cloud"></ol>
<script type="text/javascript">
{literal}
	var cloud;
	window.addEvent('domready', function() {
		cloud=new tagscloud( 'site1_cloud', {
			url: '{/literal}{url name='site1_files' action='search_result'}{literal}',
			clouds: {
				most_used: {
					frequencies: {
{/literal}
{foreach from=$arrAllUsed item='v' name='loop'}
	"{$v.tag}": {$v.items_num}{if !$smarty.foreach.loop.last},{/if}
{/foreach}
{literal}
					}
				},
				this_week_used: {
					frequencies: {
{/literal}
{foreach from=$arrLastWeekUsed item='v' name='loop'}
	"{$v.tag}": {$v.items_num}{if !$smarty.foreach.loop.last},{/if}
{/foreach}
{literal}
					}
				},
				most_searched: {
					frequencies: {
{/literal}
{foreach from=$arrAllSearched item='v' name='loop'}
	"{$v.tag}": {$v.search_num}{if !$smarty.foreach.loop.last},{/if}
{/foreach}
{literal}
					}
				},
				this_week_searched: {
					frequencies: {
{/literal}
{foreach from=$arrLastWeekSearched item='v' name='loop'}
	"{$v.tag}": {$v.search_num}{if !$smarty.foreach.loop.last},{/if}
{/foreach}
{literal}
					}
				}
			}
		});
		cloud.draw('most_used');
	});
{/literal}
</script>