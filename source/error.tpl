{if !empty($arrErrors.errForm)}
<div class="alert alert-danger">
	<p class="m-b-10">
		<strong><i class="fa fa-exclamation-triangle"></i> Errors fields:</strong>
	</p>

	{foreach from=$arrErrors.errForm item=errors  key=field}
		- <i>{$fields[$field]}</i> - {join(', ',$errors)}<br/>
	{/foreach}
</div>
{/if}
{if !empty($arrErrors.errFlow)}
<div class="notification error png_bg">
	<a href="#" class="close"><img src="/skin/i/frontends/design/newUI/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
	<div>Process aborted because:<br/>
	{foreach from=$arrErrors.errFlow item=error}
		- {$error}<br/>
	{/foreach}</div>
</div>
{/if}
{if empty($arrErrors.errFlow) && empty($arrErrors.errForm) && !empty($arrErrors)}
<div class="notification error png_bg">
	<a href="#" class="close"><img src="/skin/i/frontends/design/newUI/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
	<div>Core Errors:<br/>
	{foreach from=$arrErrors item=error}
		- {$error}<br/>
	{/foreach}</div>
</div>
{/if}