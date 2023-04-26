<br />
<br />
<form class="wh" action="" id="post_form" method="POST" style="width:60%;">
	<input type="hidden" name="page_id" value="{$arrItem.page_id}">
	<input type="hidden" name="cpp" value="{if $smarty.get.cpp}{$smarty.get.cpp}{else}0{/if}">
	<input type="hidden" name="ad_env" value="{$arrItem.ad_env}" />
	<input type="hidden" name="ad_id"  value="{$arrItem.ad_id}"  />
	<input type="hidden" name="aid"  value="{$arrItem.aid}"  />
	<div style="display:none;">
	{module name='ftp_tools' action='set' selected=$arrItem.arrFtp}
	</div>
	<fieldset>
		<legend>Page settings. File name ({$arrItem.page_name})</legend>
		<ol>
		{if $arrItem.page_type != 'redirect'}
		<input type="hidden" name="file_name_ad"  value="{$arrItem.page_name}" />
		<input type="hidden" name="cloack"  value="cloaked" />
			<li>
				<label><span>Affiliate URL <em>*</em></span> </label>
				<input type="text" name="redirect_url2" value="{$arrItem.page_affiliate_url}" id="redirect_url2" />
			</li>
			<li>
				<label>Page Title</label>
				<input type="text" name="page_title" value="{$arrItem.page_title}" id="page_titlle" />
			</li>
			<li>
				<label>Meta tags (keywords)</label>
				<input type="text" name="meta_tag" value="{$arrItem.page_keywords}" id="meta_tag"/>
			</li>
			<li><input type="hidden" name="dams_add" value="0">
				<input type="checkbox" name="dams_add" {if count($arrItem.ids)}checked='1'{/if} id="dams_add" value="1">&nbsp;&nbsp;Do you want to add a list building subscrpition form, or a scarcity message or any other High Impact Ad Manager Campaign to your tracking page?
				<br/>(Warning: this option could be responsible for big improvements to your bottom line!)
			</li>	
			<li>
				<fieldset style="display:{if count($arrItem.ids)}block{else}none{/if};" id="dams_select">
					<legend></legend>
					<ol>
						<li>
							<label><input type="radio"  class="dams" value="single" {if $arrItem.compaign_type == 'single'}checked='1'{/if} name="headlines_spot1"> Campaigns</label>
							<label><input type="radio" class="dams" value="split" {if $arrItem.compaign_type == 'split'}checked='1'{/if} name="headlines_spot1" > Split</label>
						</li>
					</ol>
				</fieldset>
			</li>
			<li>
				<img id="ajax_loader" src='/skin/i/frontends/design/ajax-loader_new.gif' alt='processing' style="display:none"/>
				<div id="dams_container"  style="display:{if count($arrItem.ids)}block{else}none{/if};">
				{if count($arrItem.ids)}
					{module name='advanced_options' action='ad' process=$arrItem.compaign_type ids = $arrItem.ids}
				{/if}
				</div>
				<br/>
				<div id="cloack_cloaced_block_message"></div>
				<img id="ajax_loader_save_page2" src='/skin/i/frontends/design/ajax-loader_new.gif' alt='processing' style="display:none"/>
				<input type="button" value="Save" id="save_page2"/>
			</li>
			{else}
			<input type="hidden" name="file_name"  value="{$arrItem.page_name}" />
			<input type="hidden" name="cloack"  value="redirect" />
			<li>
				<label><span>Affiliate URL <em>*</em></span></label>
				<input type="text" name="redirect_url" value="{$arrItem.page_affiliate_url}" id="redirect_url2"/>
			</li>
			<li>
				<div id="cloack_cloaced_block_message"></div>
				<img id="ajax_loader_save_page2" src='/skin/i/frontends/design/ajax-loader_new.gif' alt='processing' style="display:none"/>
				<input type="button" value="Save" id="save_page2"/>
			</li>			
			{/if}
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

if($('dams_add')) {
$('dams_add').addEvent('click', function(){
	if( !$('dams_add').checked ) {
		$('dams_select').style.display = 'none';
		$('dams_container').style.display= 'none';
	} else {
		$('dams_container').style.display= 'block';
		$('dams_select').style.display = 'block';
	}
	
});
}
$('save_page2').addEvent('click', function(){
	if( !$('ftp_directory').value || !$('redirect_url2').value ) {
		r.alert( 'Client side error', 'Fill FTP Address, Username, Password field and Homepage folder. Fill Affiliate URL and File name', 'roar_error' );
		return false;
	}	
	var req = new Request({url: "{/literal}{url name='site1_affiliate' action='save'}{literal}",onRequest: function(){$('ajax_loader_save_page2').style.display="block";}, onSuccess: function(responseText){
		$('cloack_cloaced_block_message').set('html','File has been saved successfully {/literal}<a href="{$arrItem.page_address}{$arrItem.page_name}" target="_blank">View</a>{literal}');
	}, onComplete: function(){$('ajax_loader_save_page2').style.display="none"; }}).post($('post_form'));		
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


