<br/>
<br/>
<form method="post" action="" class="wh" id="from-create" style="width:50%">
<input type="hidden" name="arrData[id]" value="{$arrData.id}"/>
	<fieldset>
		<ol>
			<li><label>Title <em>*</em></label><input type="text" name="arrData[profile_name]" class="required" value="{$arrData.profile_name}" /></li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Personal</legend>
		<ol>
		<li><label>First Name <em>*</em></label><input type="text"  class="required" name="arrData[first_name]" value="{if $arrData.first_name !='NULL'}{$arrData.first_name}{/if}"></li>
		<li><label>Last Name <em>*</em></label><input type="text"  class="required" name="arrData[last_name]" value="{if $arrData.last_name !='NULL'}{$arrData.last_name}{/if}"></li>
		<li><label>Email address</label><input type="text" name="arrData[email]" value="{if $arrData.email !='NULL'}{$arrData.email}{/if}"></li>
		<li><label>Autoresponder email</label><input type="text" name="arrData[autoresponder_email]" value="{if $arrData.autoresponder_email != 'NULL'}{$arrData.autoresponder_email}{/if}"></li>
		<li><label>URL of the landing page (in case of a portal)</label><input type="text" name="arrData[url_of_landingpage]" value="{if $arrData.url_of_landingpage!='NULL'}{$arrData.url_of_landingpage}{/if}"></li>
		</ol>
	</fieldset>	
	<fieldset>
		<legend>Modules</legend>
		<ol>	
		<li><label>Show Google ads</label><input type="checkbox" value="1" name="arrData[show_google_ads]" {if $arrData.show_google_ads}checked="1"{/if}></li>
		<li><label>Show Yahoo ads</label><input type="checkbox" value="1" name="arrData[show_yahoo_ads]" {if $arrData.show_yahoo_ads}checked="1"{/if}></li>
		<li><label>Show Search feed</label><input type="checkbox" value="1" name="arrData[show_search_feed]" {if $arrData.show_search_feed}checked="1"{/if}></li>
		<li><label>Show Chitika</label><input type="checkbox" value="1" name="arrData[show_chitika]" {if $arrData.show_chitika}checked="1"{/if}></li>
		<li><label>Show Subscribe</label><input type="checkbox" value="1" name="arrData[show_subscribe]" {if $arrData.show_subscribe}checked="1"{/if}></li>
		<li><label>Show Amazon ads</label><input type="checkbox" value="1" name="arrData[show_amazon_ads]" {if $arrData.show_amazon_ads}checked="1"{/if}></li>
		<li><label>Show Partners</label><input type="checkbox" value="1" name="arrData[show_parteners]" {if $arrData.show_parteners}checked="1"{/if}></li>
		<li><label>Show Bestseller</label><input type="checkbox" value="1" name="arrData[show_bestseller]" {if $arrData.show_bestseller}checked="1"{/if}></li>
		<li><label>Show best Products</label><input type="checkbox" value="1" name="arrData[show_best_products]" {if $arrData.show_best_products}checked="1"{/if}></li>
		<li><label>Show Center (below article zone)</label><input type="checkbox" value="1" name="arrData[show_centers]" {if $arrData.show_centers}checked="1"{/if}></li>
		<li><label>Show right (below subscribe form)</label><input type="checkbox" value="1" name="arrData[show_right]" {if $arrData.show_right}checked="1"{/if}></li>
		<li><label>Show Submit Article form</label><input type="checkbox" value="1" name="arrData[show_submit_article_form]" {if $arrData.show_submit_article_form}checked="1"{/if}></li>
		<li><label>Number of News related results to show <em>*</em></label><input type="text"  class="required" name="arrData[no_of_results]" value="{if $arrData.no_of_results != 'NULL'}{$arrData.no_of_results}{/if}"></li>
		<li><label>Switch from Keyword Navigation to Article navigation</label><input type="checkbox" value="1" name="arrData[switch]" {if $arrData.switch}checked="1"{/if}></li>
		</ol>	
	</fieldset>
	<fieldset>
		<legend>Details</legend>
		<ol>
		<li><label>Your Adsense ID (include pub-) <em>*</em></label><input type="text"  class="required" name="arrData[adsense_id]" value="{$arrData.adsense_id}"></li>
		<li><label>Adsense Channel to use (if any)</label><input type="text" name="arrData[adsense_channel]" value="{if $arrData.adsense_channel != 'NULL'}{$arrData.adsense_channel}{/if}"></li>
		<li><label>Yahoo Pub (leave blank if not enabled)</label><input type="text" name="arrData[yahoo_id]" value="{if $arrData.yahoo_id != 'NULL'}{$arrData.yahoo_id}{/if}"></li>
		<li><label>Yahoo Channel to use (if any)</label><input type="text" name="arrData[yahoo_channel]" value="{if $arrData.yahoo_channel != 'NULL'}{$arrData.yahoo_channel}{/if}"></li>
		<li><label>Chitika ID (leave blank if not enabled)</label><input type="text" name="arrData[chitika_id]" value="{if $arrData.chitika_id != 'NULL'}{$arrData.chitika_id}{/if}"></li>
		<li><label>Chitika Channel to use (if any)</label><input type="text" name="arrData[chitika_channel]" value="{if $arrData.chitika_channel != 'NULL'}{$arrData.chitika_channel}{/if}"></li>
		<li><label>ClickBank ID</label><input type="text" name="arrData[clickbank_id]" value="{if $arrData.clickbank_id != 'NULL'}{$arrData.clickbank_id}{/if}"></li>
		<li><label>Search Feed ID</label><input type="text" name="arrData[search_feed_id]" value="{if $arrData.search_feed_id != 'NULL'}{$arrData.search_feed_id}{/if}"></li>
		<li><label>Search Feed Track ID</label><input type="text" name="arrData[search_feed_track_id]" value="{if $arrData.search_feed_track_id != 'NULL'}{$arrData.search_feed_track_id}{/if}"></li>
		<li><label>Amazon Country</label>
			<fieldset>
				<label><input type="radio" name="arrData[amazon_country]" {if $arrData.amazon_country == 'us' || $arrData.amazon_country != 'uk'}checked="1"{/if} value="us"/>  U.S.</label>
				<label><input type="radio" name="arrData[amazon_country]" {if $arrData.amazon_country == 'uk'}checked="1"{/if} value="uk"/>  U.K.</label>
			</fieldset>
		</li>
		<li><label>Amazon Associates ID</label><input type="text" name="arrData[amazon_associates_id]" value="{if $arrData.amazon_associates_id != 'NULL'}{$arrData.amazon_associates_id}{/if}"></li>
		<li><label>Number of Amazon products to display</label><input type="text" name="arrData[no_of_amazon_products]" value="{if $arrData.no_of_amazon_products != 'NULL'}{$arrData.no_of_amazon_products}{/if}"></li>
		<li><label>Ebay Affilate ID</label><input type="text" name="arrData[ebayaffid]" value="{if $arrData.ebayaffid != 'NULL'}{$arrData.ebayaffid}{/if}"></li>
		<li><input type="submit" id="create" value="{if  empty($arrData.profile_name)}Add profile{else}Save profile{/if}"></li>
		</ol>	
	</fieldset>
</form>
{literal}
<style>
form.wh label{width:255px !important;}
form.wh fieldset fieldset label {margin-left:255px;}
ol li{position:relative;}
.validation-advice{position:absolute; top:-21px; right:-156px;  z-index:100;}
.validation-advice div#validation-id-advice{ width:200px;  padding:2px; margin:2px;}
.validator-tl{width:16px; height:16px; background:url(/skin/i/frontends/design/validator/tl.png)  left top no-repeat; font-size:1px;}
.validator-t{height:16px; background:url(/skin/i/frontends/design/validator/t.png)  left top repeat-x; font-size:1px;}
.validator-tr{width:16px; height:16px; background:url(/skin/i/frontends/design/validator/tr.png)  left top no-repeat; font-size:1px;}
.validator-bl{width:16px; height:25px; background:url(/skin/i/frontends/design/validator/bl.png)  left top no-repeat; font-size:1px;}
.validator-b{height:25px; background:url(/skin/i/frontends/design/validator/b.png)  left top no-repeat; font-size:1px;}
.validator-br{width:16px; height:25px; background:url(/skin/i/frontends/design/validator/br.png)  left top no-repeat; font-size:1px;}
.validator-r{width:16px;  background:url(/skin/i/frontends/design/validator/r.png)  left top repeat; font-size:1px;}
.validator-l{width:16px;  background:url(/skin/i/frontends/design/validator/l.png)  left top repeat; font-size:1px;}
.validator-c{padding:2px 2px 2px 2px; position:relative; background:url(/skin/i/frontends/design/validator/c.png)  left top repeat;}
.validator-conteiner {color:#FFF; float:left; font-family:Arial; font-size:11px;}
.validator-close {background:url(/skin/i/frontends/design/validator/close.png) left top no-repeat; display:block; position:absolute; right:25px; top:15px; text-decoration:none; width:10px; height:10px; line-height:0px; font-size:0px;}
</style>
{/literal}
{literal}
<script type="text/javascript">
var myVaidator = new Class({
	Extends: FormValidator.Inline,
	initialize: function(form){
		this.form = form;
		this.parent(form, {
				fieldSelectors: "select,input,textarea",
				evaluateFieldsOnBlur: true,
				evaluateFieldsOnChange: false,
				scrollToErrorsOnSubmit: true,
				scrollFxOptions: {offset:{'x':0,'y':-100}},
				onElementFail: function(element){ this.stop();}
			});
	},
	startValidate: function() {
		var error = this.validate();
		this.visualAlert();
		return error;
	},
	visualAlert: function() {
		$$('div.validation-advice').each(function(el){
			var tempMsg = el.get('html');
			if($('validation-id-advice'))
			$('validation-id-advice').destroy();
			el.empty();
			this.getHtml(el,tempMsg);			
		},this);
		this.initClose();
		this.start();
	}, 
	initClose: function(){
		$$('a.validator-close').each(function(a){
			a.addEvent('click', function(event){
				event.stop();
				var div = a.getParent('div').getParent('div');
				div.destroy();
			});
		},this);
	},
	getHtml: function(element,msg){
		var divId = new Element('div',{'id':'validation-id-advice'});
		var table = new Element('table', {'cellpadding':'0','cellspacing':'0','width':'100%' });
		var tr1 = new Element('tr');
		var tr2 = new Element('tr');
		var tr3 = new Element('tr');
		var td_tl = new Element('td',{'class':'validator-tl'});
		var td_t = new Element('td',{'class':'validator-t'});
		var td_tr = new Element('td',{'class':'validator-tr'});
		var td_l = new Element('td',{'class':'validator-l'});
		var td_c = new Element('td',{'class':'validator-c'});
		var td_r = new Element('td',{'class':'validator-r'});
		var td_bl = new Element('td',{'class':'validator-bl'});
		var td_b = new Element('td',{'class':'validator-b'});
		var td_br = new Element('td',{'class':'validator-br'});
		var div = new Element('div', {'class':'validator-conteiner'}).set('html',msg);
		var a = new Element('a', {'class':'validator-close', 'href':'#'});
		div.inject(td_c);
		a.inject(td_c);
		td_tl.inject(tr1);
		td_t.inject(tr1);
		td_tr.inject(tr1);
		td_l.inject(tr2);
		td_c.inject(tr2);
		td_r.inject(tr2);
		td_bl.inject(tr3);
		td_b.inject(tr3);
		td_br.inject(tr3);
		tr1.inject(table);
		tr2.inject(table);
		tr3.inject(table);
		table.inject(divId);
		divId.inject(element);
		return;
	}
});
window.addEvent('domready', function(){
	$('create').addEvent('click',function( e ){
		e && e.stop();
		var valid = new myVaidator($('from-create'));
		if(valid.startValidate()) {
			$('from-create').submit();
		}		
	});	
});
</script>
{/literal}