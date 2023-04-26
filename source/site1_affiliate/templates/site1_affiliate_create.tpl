
<form action="" method="POST" class="wh" id="post_form" style="width:60%; height:100%;">
<p>
This module allows you to create affiliate redirect page (cloaked
or not) with enhanced capabilities (ability to add an optin form, or a scarcity message, or a coupon code...) with the click of a button.<br/>

Basically, whenever you have an affiliate program to promote you can come here and create your affiliate redirect page. You can even add your optin form and display your optin form on a website that is not yours...
</p>

	<fieldset>
		<legend>Creat/Edit page</legend>
		<ol>
			<li><input type="radio"  value="edit" name="edit[type]" id="get_file">&nbsp;I want to edit an existing page</li>
			<li><input type="radio"  value="creat" name="edit[type]" id="get_file_affiliate">&nbsp;I want to create an affiliate redirect page with list building capabilities (for more help <a href="#">Click Here</a>)
				<fieldset style="display:none;" id="cloack_settings">
					<legend></legend>
						<ol>
							<li>
								<label><input type="radio" name="cloack" value="redirect" id="cloack_redirect" /> Simple redirect</label>
								<label><input type="radio" name="cloack" value="cloaked" id="cloack_cloaced" /> Cloaked link with enhanced capabilities</label>
							</li>
						</ol>
				</fieldset>			
			</li>
		</ol>
	</fieldset>
	
	<div id="ftp-block" style="height:100%; display:none;">
	
	{module name='ftp_tools' action='set' selected=$ftp with_file=true}
	<fieldset id="convert_block"  style="display:none;" >
		<legend></legend>
		<ol>
			<li>
				<input type="hidden" name="convert_page" value="0">
				<input type="checkbox" name="convert_page" value="1"  id="warning"/>&nbsp;Convert this page to affiliate redirect page with list building capability.
			</li>		
		</ol>	
	</fieldset>			
		<input type="button" name="open_file" id="open_file" value="Open file" />
	</div>
	
	
	<fieldset id="cloack_redirect_block" style="display:none;">
		<legend>Page settings</legend>
		<ol>
			<li>
				<label><span>Affiliate URL <em>*</em></span></label>
				<input type="text" name="redirect_url" id="redirect_url"/>
			</li>
			<li>
				<label><span>File name <em>*</em></span></label>
				<input type="text" name="file_name" id="file_name" />
			</li>			
			<li>
				<div id="cloack_redirect_block_message"></div>
				<img id="ajax_loader_save_page" src='/skin/i/frontends/design/ajax-loader_new.gif' alt='processing' style="display:none"/>
				<input type="button" value="Save" id="save_page" />
			</li>
		</ol>	
	</fieldset>
	
	
	<fieldset id="cloack_cloaced_block" style="display:none;">
		<legend>Page settings</legend>
		<ol>
			<li>
				<label><span>Affiliate URL <em>*</em></span> </label>
				<input type="text" name="redirect_url2" id="redirect_url2" />
			</li>
			<li>
				<label>Page Title</label>
				<input type="text" name="page_title" id="page_title" />
			</li>
			<li>
				<label>Meta tags (keywords)</label>
				<input type="text" name="meta_tag"  id="meta_tag"/>
			</li>
			<li  style="display:block;" id="file_name_block">
				<label><span>File name <em>*</em></span></label>
				<input type="text" name="file_name_ad"  id="file_name_ad" />
			</li>		
			<li><input type="hidden" name="dams_add" value="0">
				<input type="checkbox" name="dams_add" id="dams_add" value="1">&nbsp;&nbsp;Do you want to add a list building subscrpition form, or a scarcity message or any other High Impact Ad Manager Campaign to your tracking page?
				<br/>(Warning: this option could be responsible for big improvements to your bottom line!)
			</li>	
			<li>
				<fieldset style="display:none;" id="dams_select">
					<legend></legend>
					<ol>
						<li>
							<label><input type="radio"  class="dams" value="single" name="headlines_spot1"> Campaigns</label>
							<label><input type="radio" class="dams" value="split" name="headlines_spot1" > Split</label>
						</li>
					</ol>
				</fieldset>
			</li>
			<li>
				<div id="dams_container"  style="display:none;"></div>
				<br/>
				<div id="cloack_cloaced_block_message"></div>
				<img id="ajax_loader_save_page2" src='/skin/i/frontends/design/ajax-loader_new.gif' alt='processing' style="display:none"/>
				<input type="button" value="Save" id="save_page2"/>
			</li>
		</ol>	
	</fieldset>
		
	

	<img id="ajax_loader" src='/skin/i/frontends/design/ajax-loader_new.gif' alt='processing' style="display:none"/>
	<div id="editor_message"></div>
	<fieldset id="editor" style="display:none;">
		
		<legend>Editor</legend>
		<ol>
			<li>
				<textarea style="width:100%; height:400px;" name="file_content" id="file_content"></textarea>
			</li>
			<li><input  type="button" id="save_file" value="Save" /><img id="ajax_loader_save" src='/skin/i/frontends/design/ajax-loader_new.gif' alt='processing' style="display:none"/></li>
		</ol>
	</fieldset>
</form>

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script type="text/javascript">
$('dams_add').addEvent('click', function(){
	if( !$('dams_add').checked ) {
		$('dams_select').style.display = 'none';
		$('dams_container').style.display= 'none';
	} else {
		$('dams_container').style.display= 'block';
		$('dams_select').style.display = 'block';
	}
	
});

$('warning').addEvent('click', function(){
	if( !$('ftp_directory').value  ) {
		r.alert( 'Client side error', 'Fill FTP Address, Username, Password field and Homepage folder.', 'roar_error' );
		return false;
	}	
	if( $('warning').checked ){
		if( confirm('Warning! your old content will  be replaced') ) {
			$('warning').checked = true;
			$('cloack_cloaced_block').style.display='block';
			$('open_file').style.display='none';
			$('editor').style.display='none';
			$('file_name_ad').value = $('ftp_directory').value;
			$('file_name').value = '';
			$('redirect_url2').value = '';
			$('redirect_url').value = '';
			$('meta_tag').value = '';
			$('page_title').value = '';
			$('file_name_ad').type='hidden';
			$('file_name_block').style.display='none';
		} else {
			$('warning').checked = false;
		}
	} else {
			$('cloack_cloaced_block').style.display='none';
			$('redirect_url2').value='';
			$('page_title').value='';
			$('meta_tag').value='';
			$('file_name_ad').value='';
			
			$$('.dams').each(function(el){ el.checked=false; });
			$('dams_add').checked = false;
			$('dams_select').style.display = 'none';
			$('dams_container').set('html', '');	
			$('dams_container').style.display= 'none';	
			$('file_name_ad').type='text';
			$('open_file').style.display='block';	
	}
});

var multibox={};

window.addEvent('domready', function() {
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
	});	

});
$('save_page').addEvent('click', function(){
	$('cloack_redirect_block_message').set('html','');
	if( !$('ftp_directory').value || !$('redirect_url').value || !$('file_name').value ) {
		r.alert( 'Client side error', 'Fill FTP Address, Username, Password field and Homepage folder. Fill Affiliate URL and File name', 'roar_error' );
		return false;
	}	
	var req = new Request({url: "{/literal}{url name='site1_affiliate' action='save'}{literal}",onRequest: function(){$('ajax_loader_save_page').style.display="block";}, onSuccess: function(responseText){
		if( responseText != 0 ){
		$('cloack_redirect_block_message').set('html','File has been saved successfully');
		} else {
		$('cloack_redirect_block_message').set('html','File can not be saved');	
		}
		location.href='{/literal}{url name='site1_affiliate' action='manage'}{literal}';
	}, onComplete: function(){$('ajax_loader_save_page').style.display="none"; }}).post($('post_form'));		
});


$('save_page2').addEvent('click', function(){
	$('cloack_cloaced_block_message').set('html','');
	if( !$('ftp_directory').value || !$('redirect_url2').value || !$('file_name_ad').value ) {
		r.alert( 'Client side error', 'Fill FTP Address, Username, Password field and Homepage folder. Fill Affiliate URL and File name', 'roar_error' );
		return false;
	}	
	var req = new Request({url: "{/literal}{url name='site1_affiliate' action='save'}{literal}",onRequest: function(){$('ajax_loader_save_page2').style.display="block";}, onSuccess: function(responseText){
		if( responseText != 0 ){
			$('cloack_cloaced_block_message').set('html','File has been saved successfully');
		} else {
			$('cloack_cloaced_block_message').set('html','File can not be saved');
		}
		location.href='{/literal}{url name='site1_affiliate' action='manage'}{literal}';
	}, onComplete: function(){$('ajax_loader_save_page2').style.display="none"; }}).post($('post_form'));		
});


$('cloack_cloaced').addEvent('click', function(){
	$('dams_container').style.display='none';
	$('cloack_cloaced_block').style.display='block';
	$('cloack_redirect_block').style.display='none';
	$('cloack_redirect_block_message').set('html','');
	$('cloack_cloaced_block_message').set('html','');
	$('ftp-block').style.display='block';	
	$('dams_add').checked = false;
	$('dams_select').style.display = 'none';
	$('dams_container').style.display= 'none';	
	$('file_name_ad').value = '';
	$('file_name_ad').type='text';
	$('file_name_block').style.display='block';
	clearPageSettings();
	
});

var clearPageSettings = function(){
	$('file_name').value = '';
	$('redirect_url2').value = '';
	$('redirect_url').value = '';
	$('meta_tag').value = '';
	$('page_title').value = '';
	$('warning').checked=false;
}

$('cloack_redirect').addEvent('click', function(){	
	$('dams_container').style.display='none';
	$('cloack_cloaced_block').style.display='none';
	$('cloack_redirect_block').style.display='block';
	$('cloack_redirect_block_message').set('html','');
	$('cloack_cloaced_block_message').set('html','');	
	$('ftp-block').style.display='block';
	clearPageSettings();
	
});


$('get_file').addEvent('click', function(){
	$('href').href='{/literal}{url name='ftp_tools' action='browse'}{literal}?mode=with_files';
	$('title_file').style.display='block';
	$('title_dir').style.display='none';
	$('help_file').style.display='block';
	$('help_dir').style.display='none';
	
	$('ftp_directory').value='';
	$('open_file').style.display='block';
	$('ftp-block').style.display='block';
	$('convert_block').style.display='block';
	$('warning').checked = false;
	$('cloack_cloaced_block').style.display='none';
	if( $('get_file').checked ) {
		$('cloack_settings').style.display='none';
		$('cloack_redirect_block').style.display='none';
		$('cloack_cloaced_block').style.display='none';
		$('dams_container').style.display='none';
		$('cloack_cloaced').checked = false;
		$('cloack_redirect').checked = false;
	}	
	$('cloack_redirect_block_message').set('html','');
	$('cloack_cloaced_block_message').set('html','');
	$('ftp-block').style.display='block';
});

$('open_file').addEvent('click',function(){
	if( !$('ftp_directory').value) {
		r.alert( 'Client side error', 'Fill FTP Address, Username, Password field and Homepage folder', 'roar_error' );
		$('cloack_redirect').checked = false;
		return false;
	}		
	var req = new Request({url: "{/literal}{url name='site1_affiliate' action='get'}{literal}",onRequest: function(){$('ajax_loader').style.display="block";}, onSuccess: function(responseText){
			$('file_content').value = responseText;
			$('editor').style.display='block';
	}, onComplete: function(){$('ajax_loader').style.display="none"; }}).post($('post_form'));	
});



$('get_file_affiliate').addEvent('click', function(){	
	$('href').href='{/literal}{url name='ftp_tools' action='browse'}{literal}';
	$('title_file').style.display='none';
	$('title_dir').style.display='block';
	$('help_file').style.display='none';
	$('help_dir').style.display='block';	
	$('convert_block').style.display='none';
	$('cloack_cloaced_block').style.display='none';
	$('open_file').style.display='none';
	$('ftp_directory').value='';
	if( $('get_file_affiliate').checked ) {
		$('cloack_settings').style.display='block';
		$('editor').style.display='none';
	}
	$('editor_message').set('html','');
	$('cloack_redirect_block_message').set('html','');
	$('cloack_cloaced_block_message').set('html','');
	
});

$('save_file').addEvent('click', function(){
	$('editor_message').set('html',''); 
	var req = new Request({url: "{/literal}{url name='site1_affiliate' action='save'}{literal}",onRequest: function(){$('ajax_loader_save').style.display="inline";}, onSuccess: function(responseText){
		if(responseText != '0') {
			$('editor_message').set('html','File has been saved successfully'); 
			if($('warning').checked){
				location.href='{/literal}{url name='site1_affiliate' action='manage'}{literal}';
			}
		} else {
			$('editor_message').set('html','File can not be saved'); 
		}
	}, onComplete: function(){$('ajax_loader_save').style.display="none"; }}).post($('post_form'));	
});

function get_damscode(el,type){ };
function checkUncheckAll(el,type){
	$$('.check_all_items').each(function(el){
		if( $('chkall').checked ) {
			el.checked = true;
		} else {
			el.checked = false;
		}
	});
};


window.addEvent('domready', function() {

$$('.dams').each(function(e){
	e.addEvent('click', function(){
		var req = new Request({url: "{/literal}{url name='advanced_options' action='ad'}{literal}",onRequest: function(){$('ajax_loader').style.display="block";}, onSuccess: function(responseText){
			$('dams_container').style.display='block';
			$('dams_container').set('html', responseText);
		}, onComplete: function(){$('ajax_loader').style.display="none"; }}).post({'process':$(e).value, 'spot':'spot1'});		
	});
});
	
});
</script>
{/literal}

