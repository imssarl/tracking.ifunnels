{*if MULTI_LANG}
<div class="ru{if LANG=='ru'} chosen{/if}">{if LANG!='ru'}<a href="/admin/ru{$smarty.const.LANGURI}">{/if}Рус{if LANG!='ru'}</a>{/if}</div>
<div class="eng{if LANG=='en'} chosen{/if}">{if LANG!='en'}<a href="/admin/en{$smarty.const.LANGURI}">{/if}Eng{if LANG!='en'}</a>{/if}</a></div>
{/if*}
{foreach from=$arrMenu item='m'}
	<a href="#" class="menu_js_hdl {if $arrNest.pid==$m.id}show{else}leftsubmenu{/if}" rel="{$m.id}">{$m.title}</a>
	<div{if $arrNest.pid!=$m.id} style="display:none;"{/if} id="{$m.id}">
		{foreach from=$m.node item='a'}
		<a href="{url name=$a.name action=$a.action}" class="{if $arrNest.name==$a.name&&$arrNest.action==$a.action}subshow{else}leftsubsubmenu{/if}">{$a.title}</a>
		{/foreach}
	</div>
{/foreach}
<script type="text/javascript">
{literal}
$$( '.menu_js_hdl' ).each(function(el){
	el.addEvent('click',function(e,el){
		e.stop();
		var obj=$(el.rel);
		obj.style.display=obj.style.display=='none'?'block':'none';
	}.bindWithEvent(this, el));
});
{/literal}
</script>