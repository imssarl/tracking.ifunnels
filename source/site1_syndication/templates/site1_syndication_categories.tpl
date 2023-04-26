{literal}
<style>
ul.list li ul{padding:3px 10px 15px 10px;}
</style>
{/literal}
<ul class="list">
{foreach from=$arrCategories item=i}
<li>{$i.title}
	<ul>
		{foreach from=$i.node item=j}
		<li>{$j.title}</li>
		{/foreach}	
	</ul>
</li>
{/foreach}
</ul>