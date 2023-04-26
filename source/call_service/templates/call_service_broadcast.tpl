<script type="text/javascript" src="/skin/_js/ckeditor/ckeditor.js"></script>
{if !empty($successCount)}
<div class="grn">{$successCount} messages sent successfully</div>
<br/>
<br/>
{/if}
{if !empty($arrErrors)}
<div class="red">{foreach from=$arrErrors item=i}
User <a  href="{url name='members' action='set'  wg="id={$i.user_id}"}" target="_blank">{$i.email}</a> error: <b>{$i.message}</b><br/>
{/foreach}</div>
{/if}
<div>
	<form action="" method="post" class="wh validate" id="form-mail">
		<fieldset>
			<ol>
				<li>
					<label>All verified users: <em>*</em></label><input id="all-users" type="checkbox" name="arrData[all]" class="required" value="1" checked="1" />
				</li>
				<li id="users" style="display: none;">
					<label>Select users: <em>*</em></label>
					<select multiple="1" name="arrData[users][]" style="width:300px; height: 300px;">
						{foreach from=$arrUsers item=i}
							<option value="{$i.id}">[{$i.buyer_phone}]</option>
						{/foreach}
					</select>
				</li>
				<li>
					<label>Message. Max. <span id="count">160</span> characters: <em>*</em></label>
					<textarea rows="4" id="message"  style="width:300px;" name="arrData[message]">{$arrData.message}</textarea>
				</li>
				<li>
					<input type="submit"  value="Send" id="send"   />
				</li>
			</ol>
		</fieldset>
	</form>
</div>
{literal}
<script type="text/javascript">
	window.addEvent('load', function(){
		var max=160;
		$('message').addEvent('keyup', function(){
			$('count').setStyle('color','black');
			$('send').set('disabled',false);
			var count=max-this.get('value').length;
			if( count<0 ){
				$('count').setStyle('color','red');
				$('send').set('disabled',true);
			}
			$('count').set('html',count);
		});
		$('all-users').addEvent('click', function(){
			if( this.get('checked') ){
				$('users').setStyle('display','none');
			} else {
				$('users').setStyle('display','block');
			}
		});
	});
</script>
{/literal}