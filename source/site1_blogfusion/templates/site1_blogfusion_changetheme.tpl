{include file="site1_blogfusion_general_menu.tpl"}

{if $msg == 'error'}
	<div class="red" align="center">Error. Can't change theme.	</div>
{elseif $msg == 'change'}
 <div class="grn" align="center">Changed successfully</div>
{/if}

<form action="" method="POST" class="wh" id="form_post" style="width:70%">
	<fieldset>
		<legend>Themes</legend>
		<ol>
			<li>
				<p>
					<h2>Current theme: <span style="text-transform:uppercase;">{$selectedTheme.title}{if $selectedTheme.preview} (<a href="#" class="screenshot" rel="<img src='{$selectedTheme.preview}'>" style="text-decoration:none">preview</a>){/if}</span></h2>
				</p>
			</li>
			<li>
				<fieldset>
					<legend>Change theme</legend>
					<ol>
						<li>
						{foreach from=$arrList item='i'}
							<label><input type="radio" class="selectTheme" {if $i.id == $selectedTheme.id}checked='1'{/if} name="theme" value="{$i.id}" /> {$i.title}{if $i.preview} (<a href="#" class="screenshot" rel="<img src='{$i.preview}'>" style="text-decoration:none">preview</a>){/if}</label>
						{/foreach}	
						</li>	
						<li>
							<label><input type="button" value="Change" id="change"></label>
							
						</li>					
					</ol>
				</fieldset>
			</li>
		</ol>
	</fieldset>
</form>
</td>
</tr>
</table>
{literal}
<script>
window.addEvent('domready', function(){
	$('change').addEvent('click', function(){
		this.disabled = true;
		$('form_post').submit();
	});
	var optTips = new Tips('.screenshot');
	$$('.screenshot').each(function(el){ el.addEvent('click',function(e){ e.stop(); }); });
});
</script>
{/literal}