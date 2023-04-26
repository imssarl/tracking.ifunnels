<form class="wh" action="" style="width:50%;">
<fieldset>
	<legend></legend>
	<ol>
		<li>
		<b>What is Quick Indexer about?</b><br/>
Quick Indexer automatically creates remote pages all over the net on frequently indexed web pages with a link back to your main URL.<br/>
It then Pings the major update services on the net to inform all search engines about your main URL as well as all the newly generated pages. In plain english: it generates 50 backlinks to your main website and sends search engines to index those backlinks as well as your main website.<br/>
This generates search engine food and should help your site get indexed in no time.
		</li>
	</ol>
</fieldset>
<fieldset>
	<legend>Settings&nbsp;</legend>
	<ol>
		<li>
			<label>URL: <em>*</em> </label><input type="text"  id="url" />
			<p>Enter your site URL without http:// or www.<br/>Example: yourwebsite.com</p>
		</li>
		<li>	
			<label>Title: <em>*</em> </label><input type="text"  id="title" />
			<p>Enter a short but descriptive title with your main keyword in it. Example: Forex Trading Software</p>
		</li>
		<li>
			<label></label><input type="button" value="Quickly Index This" id="start">
		</li>
	</ol>
</fieldset>
<div id="steps" style="display:none;">
<p><b>Warning!</b> Each step will open around 20 tabbed windows, don't worry!</p>
<br/>
<fieldset>
	<legend>Step 1&nbsp;</legend>
	<ol>
		<li><label></label><input type="button" value="Open URL Set 1" id="openSet1"></li>
		<li><label></label><input type="button" value="Open URL Set 2" id="openSet2"></li>
	</ol>
</fieldset>
<fieldset>
	<legend>Step 2&nbsp;</legend>
	<ol>
		<li><label></label><input type="button" value="Ping URL Set 1" id="pingSet1"></li>
		<li><label></label><input type="button" value="Ping URL Set 2" id="pingSet2"></li>
	</ol>
</fieldset>
</div>
</form>
{literal}
<script type="text/javascript">

var arrOpenSet1 = new Array(
'http://www.quantcast.com/',
'http://www.thegetpr.net/site/',
'http://uptime.netcraft.com/up/graph?site=',
'http://www.pageheat.com/heat/',
'http://www.aboutthedomain.com/',
'http://websiteshadow.com/',
'http://domainsearch101.com/domainsearch/',
'http://page2rss.com/page?url=',
'http://www.surcentro.com/en/info/',
'http://www.quarkbase.com/',
'http://www.esitestats.com/',
'http://www.backlinkcheck.com/popular.pl?url1=',
'http://www.onlinewebcheck.com/check.php?url=',
'http://websitevaluebot.com/www.',
'http://peekstats.com/',
'http://worthbot.com/www.',
'http://websitevaluecalculator.org/www.',
'http://www.webworth.info/',
'http://statswebsites.com/www.',
'http://tatlia.com/www.',
'http://statout.com/www.'
);
var arrOpenSet2 = new Array(
'http://georanks.com/www.',
'http://webrapport.net/www.',
'http://worthlook.com/www.',
'http://worth.im/www.',
'http://www.statbrain.com/www.',
'http://www.builtwith.com/?',
'http://www.aboutus.org/',
'http://www.cubestat.com/www.',
'http://whois.tools4noobs.com/info/',
'http://www.alexa.com/siteinfo/',
'http://www.siteadvisor.cn/sites/',
'http://whois.domaintools.com/',
'http://www.aboutdomain.org/info/',
'http://www.whoisya.com/',
'http://www.who.is/whois-com/',
'http://www.robtex.com/dns/',
'http://whoisx.co.uk/',
'http://searchanalytics.compete.com/site_referrals/'
);
var strPingUrl = 'http://pingomatic.com/ping/?title=$$title$$&blogurl=$$url$$&rssurl=http://&chk_weblogscom=on&chk_blogs=on&chk_technorati=on&chk_feedburner=on&chk_syndic8=on&chk_newsgator=on&chk_myyahoo=on&chk_pubsubcom=on&chk_blogdigger=on&chk_blogstreet=on&chk_moreover=on&chk_weblogalot=on&chk_icerocket=on&chk_newsisfree=on&chk_topicexchange=on&chk_google=on&chk_tailrank=on&chk_bloglines=on&chk_postrank=';
var checkForm = function(){
	if( !$chk($('url').value) ){
		r.alert( 'Error', 'Field "URL" can not be empty ' , 'roar_error' );
		return false;
	}
	if( !$chk($('title').value) ){
		r.alert( 'Error', 'Field "Title" can not be empty ' , 'roar_error' );
		return false;
	}		
	return true;
};
window.addEvent('domready',function(){
	$('start').addEvent('click', function( e ){
		if( !checkForm() ){ return false; }
		$('url').value = $('url').value.replace('http://','');
		$('url').value = $('url').value.replace('www.','');
		$('steps').setStyle('display','block');
	});
	$('openSet1').addEvent('click', function(){
		var url = $('url').value;
		if( !checkForm() ){ return false; }
		arrOpenSet1.each(function(link){
			window.open(link+url);
		});
	});
	$('openSet2').addEvent('click', function(){
		var url = $('url').value;
		if( !checkForm() ){ return false; }		
		arrOpenSet2.each(function(link){
			window.open(link+url);
		});
	});
	$('pingSet1').addEvent('click', function(){
		var url = $('url').value;
		if( !checkForm() ){ return false; }
		arrOpenSet1.each(function(link){
			var strUrl = strPingUrl.replace('$$title$$',( $('title').value )? $('title').value : '' );
			strUrl = strUrl.replace('$$url$$', link+url );
			window.open(strUrl);
		});
		var strUrl = strPingUrl.replace('$$title$$',( $('title').value )? $('title').value : '' );
		strUrl = strUrl.replace('$$url$$', url );
		window.open(strUrl);		
	});	
	$('pingSet2').addEvent('click', function(){
		var url = $('url').value;
		if( !checkForm() ){ return false; }
		arrOpenSet2.each(function(link){
			var strUrl = strPingUrl.replace('$$title$$',( $('title').value )? $('title').value : '' );
			strUrl = strUrl.replace('$$url$$', link+url );
			window.open(strUrl);
		});
	});	
});

</script>
{/literal}