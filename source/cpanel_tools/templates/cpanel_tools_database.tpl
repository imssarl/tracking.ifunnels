<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{module name='site1' action='head'}

	<link href="/skin/_css/tips.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<script type="text/javascript" src="/skin/_js/xlib.js"></script>
	{*r.alert*}
	<link rel="stylesheet" type="text/css" href="/skin/_js/roar/roar.css" />
	<script type="text/javascript" src="/skin/_js/roar/roar.js"></script>
	<script type="text/javascript">
	{literal}
		var r=new Roar();
		img_preload(['/skin/_js/roar/roar.png']);
	{/literal}
	</script>
</head>
<body style="padding:10px;">
<div>
	{if $error}
	<div class="red" style="padding:10px;">
		{if $error == '001' ||  $error == '002'}
			Sorry, the database was not created. Please make sure all provided details are correct. Check with your host about the issue.
		{/if}
	</div>
	{/if}
	{if !empty($result)}
	<div class="grn" style="padding:10px;">
		Database Created Successfully. <br> <b>Username privileges added.</b><br>username: {$result.user}, database: {$result.db}
	</div>
	{/if}
</div>
	<form class="wh" action="" id="wh-form" method="POST" style="width:100%;"> 
		<fieldset>
			<legend>cPanel info</legend>
			<ol>
				<li>
					<label>Hostname</label>
					<input type="text" name="arrCpanel[host]" id="cpanel_host" />&nbsp;<a href="Hostname like mysite.com" class="Tips" title="Example">?</a>
				</li>
				<li>
					<label>Username</label>
					<input type="text" name="arrCpanel[user]" id="cpanel_user" />&nbsp;<a href="Cpanel user name" class="Tips" title="Example">?</a>
				</li>
				<li>
					<label>Password</label>
					<input type="password" name="arrCpanel[passwd]" id="cpanel_passwd" />&nbsp;<a href="Cpanel password" class="Tips" title="Example">?</a>
				</li>
				<li>
					<label>cPanel Theme / Skin</label>
					<select name="arrCpanel[theme]"  id="cpanel_theme">
						<option value="x">x 
						<option value="x2">x2 
						<option value="x3">x3 
						<option value="other">other 
					</select>&nbsp;<a href="<div style='width:300px;'><strong>Try following steps if you do not know what your current cPanel theme is:</strong> 	
					<ul>
	  					<li>- Login to your cPanel account</li>
	  					<li>- Look at the URL in your browser. It would look somewhat similar to <strong>http://www.hosting.com:2082/frontend/x/index.html</strong></li>
	  					<li>- cPanel  theme	name is everything after the &quot;/frontend/&quot;, and before the next  slash &quot;/&quot;. In above example cPanel theme is &quot;x&quot;. It could be &quot;x2&quot;,  &quot;rvblue&quot;, etc.</li>
					</ul></div>"  
					class="Tips" title="cPanel Theme / Skin">?</a>
				</li>
				<li id="other">
					<label>&nbsp;</label>
				</li>
				<li>
					<p><font color="Red">Note</font>: Please Check your cpanel theme/skin before select.The script will not work if wrong cPanel theme is selected. Usually cPanel skin name would be "x", but yours may be different.</p>
				</li>
			</ol>
		</fieldset>
		<fieldset>
			<legend>New database info</legend>
			<ol>
				<li>
					<label>Database name</label>
					<input type="text" name="arrAction[name]" id="base_name"/>&nbsp;<a href="New database name" class="Tips" title="Example">?</a> 
				</li>
				<li>
					<label>Username</label>
					<input type="text" name="arrAction[user]" id="base_user" />&nbsp;<a href="New database user name" class="Tips" title="Example">?</a>
				</li>
				
				<li>
					<label>Password</label>
					<input type="password" name="arrAction[passwd]" id="base_passwd" />&nbsp;<a href="<div style='width:300px;'>As Some cpanel doesnot allow to create database user which have not above 70% password strength.So you need to set password which is Uber Secure(above 70%).<br/><br/>Example like <strong>Mypass_12</strong> i.e Password like Uppercase(Mypass)+symbol(_)+number(12)</div>"  class="Tips" title="Password">?</a>
				</li>
				
				<li>
					<input type="submit" name="submit" value="Submit" />
				</li>
			</ol>
		</fieldset>
	</form>
	<script type="text/javascript">
	var info = '{$smarty.get.info|default:'none'}';
	var cPanelResult = '{$jsonResult}';
	
	{literal}
	window.addEvent('domready', function() {

		if(window.parent.$('ftp_address')){
			$('cpanel_host').set('value', window.parent.$('ftp_address').get('value'));
			$('cpanel_user').set('value', window.parent.$('ftp_username').get('value'));
			$('cpanel_passwd').set('value', window.parent.$('ftp_password').get('value'));
		}
		var hash = new Hash(JSON.decode(cPanelResult));
		if(hash.getLength() > 0 && info == 'allocate'){
			window.parent.CpanelDatabaseResult(hash);
		}		
		
		$('cpanel_theme').addEvent('change',function(){
			if( $('cpanel_theme').value  == 'other' ) {
				var input = new Element('input', {'type':'text','name':'arrCpanel[theme]', 'id':'input_other'}).inject($('other'));
				var example = new Element('a',{'href':'Your cPanel skin name','title':'Example','class':'TipsSmall'}).set('html','&nbsp;?').injectInside($('other'));
				var optTips = new Tips('.Tips', {className: 'tips-small'});
			} else {
				if($('input_other')) {
					$('input_other').destroy();
				}
			}
		});
		
		var optTips = new Tips('.Tips', {className: 'tips'});		
		$$('.Tips').each(function(a){a.addEvent('click',function(e){e.stop()})});		
		
		$('wh-form').addEvent('submit',function(e){
			$$('.Tips').each(function(el){
				el.style.color='#999999';
			});
			if(validateForm()) {
				e.stop();	
				r.alert( 'Client side error', 'Fill cPanel info and Database info', 'roar_error' );
			}
		});	
	});
	var validateForm = function(){
		var error = false;
		if( !$('cpanel_host').value ) {
			error = 1;
	  		$('cpanel_host').getNext(0).style.color ='red';
		}
		if( !$('cpanel_user').value ) {
			error = 1;
			$('cpanel_user').getNext(0).style.color ='red';
		}
		if( !$('cpanel_passwd').value ) {
			error = 1;
			$('cpanel_passwd').getNext(0).style.color ='red';
		}
		if( $('input_other') && !$('input_other').value ) {
			error = 1;
			$('input_other').getNext(0).style.color ='red';
		}							
		if(!$('base_name').value ) {
			error = 1;
			$('base_name').getNext(0).style.color ='red';
		}							
		if(!$('base_user').value ) {
			error = 1;
			$('base_user').getNext(0).style.color ='red';
		}							
		if(!$('base_passwd').value ) {
			error = 1;
			$('base_passwd').getNext(0).style.color ='red';
		}							
		return error;
	}
	{/literal}
	</script>
</body>
</html>	