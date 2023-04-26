<br/>
<form action="" method="post" class="wh" style="width:50%" id="from-create">
<input type="hidden" name="arrPsb[id]" value="{$arrPsb.id}" />
{if !empty($arrErr)}
	{foreach from=$arrErr item=i}
		<p style="color:red;"><b>Error: {$i}</b></p>
	{/foreach}
{/if}
	<p>Please complete the form below. Mandatory fields are marked with <em>*</em></p>
<fieldset>
	<legend>Site template</legend>
	<ol>
		<li>
			<label>Template <em>*</em></label><select class="required" id="select-template" name="arrPsb[template_id]"><option value="" id=""> - select -
			{foreach from=$arrTemplates item=i}
			<option {if $arrPsb.template_id == $i.id}selected{/if} value="{$i.id}">{$i.title}
			{/foreach}
			</select>
		</li>
		<li>
			<div align="center">
				<img src="" border="0" alt="" id="template_img" />
				<p id="divdesc"></p>
			</div>
		</li>		
	</ol>
</fieldset>
<span {if $smarty.get.template} style="display:none;"{/if}>
<fieldset>
	<legend>Configuration settings</legend>
	<ol>
		<li>
			<label>Choose Profile <em>*</em></label><select class="required" name="arrPsb[profile_id]"><option value=""> - select - 
			{foreach from=$arrProfile item=i}
			<option {if $arrPsb.profile_id == $i.id}selected{/if} value="{$i.id}">{$i.profile_name}
			{/foreach}
			</select>
		</li>
		<li>
			<fieldset>
				<legend>Select Category <em>*</em></legend>
					<ol>
						<li>
					 	<label style="margin:0 0 0 170px;"><select id="category"  >
					 	<option value="0"> - select -
					 	{foreach from=$arrCategories item=i}
					 		<option {if $arrPsb.category_id == $i.id}selected='1'{/if} value="{$i.id}">{$i.title}
					 	{/foreach}</select></label>
						</li>
						<li>	
						<label style="margin:0 0 0 170px;"><select name="arrPsb[category_id]" class="required" id="category_child" ></select></label>
						</li>
					</ol>
			</fieldset>
		</li>			
		<li>
			<label>URL<em>*</em></label><input class="required" type="text" name="arrPsb[url]" value="{$arrPsb.url}">
			<p>Example: http://www.mydomain.com/myfolder/ </p>
		</li>
		<li>
			<label>Main Keyword <em>*</em></label><input class="required" type="text" name="arrPsb[main_keyword]" value="{$arrPsb.main_keyword}">
		</li>
		<li>
			<label>Number of results to display</label><input type="text" name="arrPsb[result_num]" value="{$arrPsb.result_num}" />
		</li>
		<li>
			<label>Use google analytics</label><input type="checkbox" name="arrPsb[flg_gastatus]"{if $arrPsb.flg_gastatus} checked=""{/if} id="use_google" />
		</li>
		<li id="google_analytics" style="display:{if $arrPsb.flg_gastatus}block{else}none{/if};">
			<label>Google Analytics ID</label><input type="text" name="arrPsb[google_analytics]"  value="{$arrPsb.google_analytics}">
		</li>
		<li>
			<label>Add site to syndication network</label><input type="checkbox" name="arrPsb[syndication]" {if $arrPsb.syndication||(empty( $arrPsb.id )&&empty( $arrErr ))} checked=""{/if} />
		</li>
	</ol>
</fieldset>
{if $smarty.get.id}
	<input type="hidden" name="arrFtp[address]" id="ftp_address" value="{$arrFtp.address}" />
	<input type="hidden" name="arrFtp[username]" id="ftp_username" value="{$arrFtp.username}" />
	<input type="hidden" name="arrFtp[password]" id="ftp_password" value="{$arrFtp.password}" />
	<input type="hidden" name="arrFtp[directory]" id="ftp_directory" value="{$arrFtp.directory}" />
{else}
	{module name='ftp_tools' action='set' selected=$arrFtp}
{/if}
<fieldset>
	<legend>Source settings</legend>
	<ol>
		<li>
			<fieldset>
				<legend>Do you want to add articles to the site now (they will show up in the Blog section of your site)? <input type="checkbox" {if $arrPsb.flg_articles}checked=1{/if} name="arrPsb[flg_articles]" id="source_type" />  Yes</legend>
			</fieldset>
		</li>
		<li id="source_block" style="display:{if $arrPsb.flg_articles}block{else}none{/if};">
			{module name='site1_articles' action='multiboxplace' selected=$strJson place='content_wizard' type='multiple'}	
			<div id="articleList"></div>
		</li>
	</ol>
</fieldset>
<input type="hidden" name="post_true" value="1">
{module name='advanced_options' action='optinos' site_type=Project_Sites::PSB site_data=$arrOpt}
</span>
<input type="submit" id="create" value="{if $arrPsb.id}Save site{else}Generate new site{/if}" />
</form>
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<style>
ol li{position:relative;}
.validation-advice{position:absolute; top:-21px; right:-115px;  z-index:100;}
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
<script type="text/javascript">
var post = {$smarty.post.arrOpt|default:'null'};
var edit = {$arrPsb.id|default:'null'};
var templateId = {$arrPsb.template_id|default:'null'};
var arrTemplates = {$jsonTemplates|default:'null'};
var jsonCategory = {$treeJson|default:'null'};
var categoryId = {$arrPsb.category_id|default:0};
var jsonSpots = {$jsonSpots|default:'null'};
{literal}

var PSB = new Class({
	Implements:Options,
	options:{
		templatesSelect:'select-template',
		countSpot:10
	},
	initialize: function( options ){
		this.setOptions( options );	
		this.initSpots();
		this.initSubmit();
		this.elementTemplateSelect = $( this.options.templatesSelect );
		this.start();
		this.sourceType();
		this.google();
		
	},
	start: function(){
		this.selectTemplate();
		if( templateId != null ) {
			this.editTemplate();
		}
	},
	initSubmit: function(){
		$('from-create').addEvent('submit', function(){
			$('create').set('disabled','1');
		});
	},
	google: function(){
		$('use_google').addEvent('click',function(){
			$('google_analytics').setStyle('display', ( $('use_google').checked )?'block':'none' );
		});
	},
	sourceType: function(){
		$('source_type').addEvent('click', function(){
			$('source_block').setStyle('display', ( $('source_type').checked )?'block':'none' );
		});
	},
	editTemplate:function(){
		$('divdesc').set('html','');
		arrTemplates.each(function(template){
			if( template.id == this.elementTemplateSelect.value ) {
				$('template_img').set( 'src', template.preview );
				$('divdesc').set('html',template.description);
			}
		},this);
	},
	selectTemplate: function(){
		this.elementTemplateSelect.addEvent( 'change', function(){
				$('template_img').set( 'src', '' );
				$('divdesc').set('html','');
				arrTemplates.each(function(template){
					if( template.id == this.elementTemplateSelect.value ) {
						$('template_img').set( 'src', template.preview );
						$('divdesc').set('html',template.description);
						this.parentFields.empty();
						this.createSpots( template.spots );
					}
				},this);
		}.bindWithEvent( this ) );
	},
	initSpots: function( ){
		this.parentFields = $('parent-spot-fields');
		this.strSpot=this.parentFields.getChildren('fieldset')[0].get('html');
		if( !edit && !post) {
			this.parentFields.getChildren('fieldset')[0].getChildren('legend')[0].destroy();
			this.strSpot=this.parentFields.getChildren('fieldset')[0].get('html');
			this.parentFields.empty();
		}
	},
	createSpots: function( jsonSpots ){
		var arrSpots = jsonSpots;
		if(  !arrSpots  ){ return; }
		var i=0;
		var parentLegend = new Element('legend',{'html':'You can now customize the following spots: <a href="http://members.creativenichemanager.info/usersdata/cnm_help/spbspots.pdf" target="_blank">click here</a> to see where the spots are located for your template'});
		parentLegend.inject( this.parentFields );	
		arrSpots.each(function(  item,index ){
				i++;
				tempSpot = this.strSpot.replace(/arrOpt\[spots\]\[0\]/g,'arrOpt[spots]['+index+']');
				tempSpot = tempSpot.replace(/id="0::([1-4])"/g,'id="'+index+'::$1"');
				tempSpot = tempSpot.replace(/spot1/g,'spot'+i+'');
				tempSpot = tempSpot.replace(/spot0/g,'spot'+i+'');
				var fieldset = new Element('fieldset',{'html':tempSpot});
				var legend = new Element('legend',{'html':'<input type="checkbox"  name="arrOpt[spots]['+index+'][spot_name]" id="spot'+index+'" value="spot'+i+'" class="check-position" /> Spot '+i+' (max width='+ item.width +'px max height='+ item.height +'px)'});
				legend.inject(fieldset,'top');
				fieldset.inject(this.parentFields);
		},this);
		new Advanced_Options();
	}
});

var articleList = new Class({
	Implements: Options,
	options: {
		jsonData:'',
		place:'',
		contentDiv:$('articleList')
	},
	initialize: function( options ){
		this.setOptions( options );
		this.hash = JSON.decode( this.options.jsonData );
	},
	set: function(){
		this.options.contentDiv.empty();
		var header = new Element( 'div' );
		var b = new Element( 'b' ).set( 'html','<br/><br/>Selected articles' ).injectInside( header );
		header.inject( this.options.contentDiv );
		if( !$chk(this.hash) ) {return;}
		this.hash.each( function( value, key ) {
			key++ ;
			var div = new Element( 'div' );
			var name = new Element( 'p' );
			name.set( 'html',key + '. ' + value.title.substr( 0, 50 ) + ' <a href="#" class="delete_article_' + this.options.place + '" rel="' + value.id + '">Delete from list</a>' );
			name.injectInside( div );
			div.inject( this.options.contentDiv );
			$('count_article_' + this.options.place).value = key;
		},this );	
		this.initDeleteArticle();
	},
	initDeleteArticle: function() {
		$$( '.delete_article_' + this.options.place ).each( function( el ) {
			el.addEvent( 'click',function( e ) {
				e && e.stop();
				var arr = new Array();
				var i = 0;
				this.hash.each( function( value, key ) {
					if( value.id != el.rel ) {
						arr[ i ] = value;
						i++;
					}
				} );
				this.hash = arr;
				this.set();
			}.bindWithEvent( this ) );
		},this );
	}	
});


var Categories = new Class({
	Implements: Options,
	options: {
		firstLevel: 'category',
		secondLevel: 'category_child',
		intCatId: categoryId
	},
	initialize: function( options ){
		this.setOptions(options);
		this.arrCategories = new Hash(jsonCategory);
		$(this.options.firstLevel).addEvent('change',function(){
			this.setFromFirstLevel( $(this.options.firstLevel).value );
		}.bindWithEvent( this ) );
		if( $chk( this.options.intCatId ) && this.checkLevel( this.options.intCatId ) ) {
			this.setFromFirstLevel( this.options.intCatId );
		} else if( $chk( this.options.intCatId ) ) {
			this.setFromSecondLevel( this.options.intCatId );
		}
	},
	checkLevel: function(id){
		var bool=false;
		this.arrCategories.each(function(el){
			if( el.id == id ) { bool=true; }
		}); 
		return bool;
	},
	setFromFirstLevel: function( id ){
		this.arrCategories.each( function(item){
			if( item.id == id ) {
				$A( $(this.options.firstLevel).options).each(function(i){
					if(i.value == id){
						i.selected=1;
					}
				});					

				$(this.options.secondLevel).empty();
				var option = new Element('option',{'value':'','html':'- select -'});
				option.inject( $(this.options.secondLevel) );
				var hash = new Hash(item.node);
				hash.each(function(i,k){
					var option = new Element('option',{'value':i.id,'html':i.title});
					if( i.id == this.options.intCatId ){
						option.selected=1;
					}
					option.inject( $(this.options.secondLevel) );
				},this);
			}
		},this);
	},
	setFromSecondLevel: function( id ) {
		this.arrCategories.each(function( item ){
			var hash = new Hash(item.node);
			hash.each(function(el){
				if ( id == el.id ) {
					this.setFromFirstLevel( el.pid );
				}
			},this);
		},this);
	}
});

Form.Validator.add('ftp_required', {
    errorMsg: 'This field is required',
    test: function(element){
        if (element.value.length == 0) return false;
        else return true;
    }
});
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

var multibox = {};
window.addEvent('domready', function(){
	new PSB();
	new Categories();
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});	
	$('create').addEvent('click',function( e ){
		e && e.stop();
		var error = false;
		var valid = new myVaidator($('from-create'));
		if(valid.startValidate()) {
			if( $('source_type').checked ) {
				if(parseInt($('count_article_content_wizard').value) < 1){
					r.alert( 'Warning', 'Please select more than one article' , 'roar_warning' );
					error = true;
				}	
			}
			if ( !error ) {
			$('from-create').submit();
			}
		}		
	});
});
{/literal}
</script>