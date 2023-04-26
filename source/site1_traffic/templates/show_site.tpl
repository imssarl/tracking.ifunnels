<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<style type="text/css">{literal}
		body, html{margin: 0; padding: 0; height: 100%; overflow: hidden;}
		#content{position:absolute; left: 0; right: 0; bottom: 0; top: 50px;}
		#ads{position:absolute; left: 0; right: 0; bottom: 0; top: 0px;width:100%;height:50px;}
		#timer{position:absolute; right: 200px; top: 0px;width:auto;height:1em;}
	{/literal}</style>
</head>
<body>
<div id="ads" style="display:none;">{$ads_script}{if $flg_show_redirect}<div id="timer"></div>{/if}</div>
<div id="content">
	<iframe width="100%" height="100%" frameborder="0" src="{$url}" id="content_iframe"{if $flg_show_redirect} onload="activateTimer();"{/if}></iframe>
</div>

<script type="text/javascript">
{if $flg_show_redirect}
{literal}
function activate10seconds(){
	if( $('content_iframe') != null ){
		//console.log( 'run money' );
		new Request({
			url:'{/literal}http://qjmpz.com/te{literal}',
			onSuccess: function(text){
				$('timer').set( 'html', 'Enlisted credits' );
			}
		}).get({'update_credits':'true','c':'{/literal}{$c}{literal}'});
	}
};
var timer=10;
function activateTimer(){
	$('ads').show();
	if( $('timer') != null && timer > 0 ){
		console.log( timer );
		var tire='s'; if( timer < 2 ){ tire='' }
		$('timer').set( 'html', timer+' second'+tire );
		setTimeout('activateTimer();', 1000);
		timer--;
	}else{
		setTimeout('activate10seconds();', 1000);
	}
};
/*
window.addEvent('domready', function(){
	setTimeout('activate10seconds();', 10000);
	activateTimer();
});*/{/literal}
{else}
$('ads').hide();
$('content').setStyle( 'top', '0px' );
{/if}
</script>

</body>
</html>