<br />
<br />
<form class="wh" action="" method="POST" style="width:60%;" id="post_form">
	<input type="hidden" name="page_id" value="{$arrItem.page_id}">
	<input type="hidden" name="file_name"  value="{$arrItem.page_name}" />
	<input type="hidden" name="edit[type]"  value="edit" />
	{module name='ftp_tools' action='set' selected=$arrItem.arrFtp  with_file=true}
	<fieldset >
		<legend>Edit</legend>
		<ol>
			<li>
				<input type="button" value="Open file" id="open" />
				<img id="ajax_loader" src='/skin/i/frontends/design/ajax-loader_new.gif' alt='processing' style="display:none"/>
			</li>
			<li id="editor" style="display:none;">
				<textarea style="width:100%; height:400px;" id="file_content" name="file_content"></textarea>
			</li>
			<li id="save_botton" style="display:none;">
				<div id="editor_message"></div>
				<input type="button" id="save_file" value="Save" />
				<img id="ajax_loader_save" src='/skin/i/frontends/design/ajax-loader_new.gif' alt='processing' style="display:none"/>
			</li>
		</ol>	
	</fieldset>
</form>

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script type="text/javascript">
var multibox={};

window.addEvent('domready', function() {
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
	});	

});
$('open').addEvent('click',function(){
	if( !$('ftp_directory').value  ) {
		r.alert( 'Client side error', 'Fill FTP Address, Username, Password field and Homepage folder.', 'roar_error' );
		return false;
	}		
	$('editor_message').set('html','');
	$('editor').style.display = 'none';
	$('save_botton').style.display = 'none';
	$('file_content').value = '';
	var req = new Request({url: "{/literal}{url name='site1_affiliate' action='get'}{literal}",onRequest: function(){$('ajax_loader').style.display="block";}, onSuccess: function(responseText){
			$('file_content').value = responseText;
			$('editor').style.display = 'block';
			$('save_botton').style.display = 'block';
			$('file_content').style.display = 'block';
	}, onComplete: function(){$('ajax_loader').style.display="none"; }}).post($('post_form'));	
});

$('save_file').addEvent('click', function(){
	var req = new Request({url: "{/literal}{url name='site1_affiliate' action='save'}{literal}",onRequest: function(){$('ajax_loader_save').style.display="inline";}, onSuccess: function(responseText){
		$('editor_message').set('html','File has been saved successfully');
	}, onComplete: function(){$('ajax_loader_save').style.display="none"; }}).post($('post_form'));	
});
</script>
{/literal}