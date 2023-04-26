{if $arrPrm.type == 'database'}
<a href="{url name='cpanel_tools' action='database'}{if $arrPrm.info}?info={$arrPrm.info}{/if}" class="cpanel_{$arrPrm.type}_mb" title="Cpanel Database Creator" rel="width:600,height:500" >Create database</a>
{elseif $arrPrm.type == 'subdomain'}
<a href="{url name='cpanel_tools' action='subdomain'}?set={$arrPrm.set|default:'multi'}{if $arrPrm.info}&info={$arrPrm.info}{/if}" class="cpanel_{$arrPrm.type}_mb" title="Cpanel Subdomain Creator" rel="width:600,height:500" >Create subdomain</a>
{elseif $arrPrm.type == 'addon'}
<a href="{url name='cpanel_tools' action='addondomain'}{if $arrPrm.info}?info={$arrPrm.info}{/if}" class="cpanel_{$arrPrm.type}_mb" title="Cpanel Addon Domains Creator" rel="width:600,height:500" >Create addon domain</a>
{/if}

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>

<script type="text/javascript">
{literal}
var cpanel_{/literal}{$arrPrm.type}{literal}_multibox={};
window.addEvent('domready', function() {
	cpanel_{/literal}{$arrPrm.type}{literal}_multibox = new multiBox({
		mbClass: '.cpanel_{/literal}{$arrPrm.type}{literal}_mb',
		container: $(document.body),
		useOverlay: true,
	});
});
{/literal}
</script>

	{*module name='cpanel_tools' action='set' type='database'*}