{literal}
<script type="text/javascript">
var objAccordion = {};
window.addEvent('domready', function() {
	objAccordion = new myAccordion($('accordion'), $$('.toggler'), $$('.element'), { fixedHeight:false });
});
</script>
{/literal}
<br/>
{if !$arrBlog.id}
<div align="center">
	<div  style="width:58%;">
		<a class="" href="{url name='site1_blogfusion' action='create'}"  rel="create_form">Create blog</a> | 
		<a class="" href="{url name='site1_blogfusion' action='import'}" class="select_type" rel="import_form">Import blog</a>
	</div>
</div>
<br/>
{/if}
<div class="red">
{if count($arrErr.filtered)}
	Error: Please fill all required fields
{/if}
{if count($arrErr.create)}
Create process aborted:<br/>
{foreach from=$arrErr.create item=i key=k}
	 - {$i}<br/>
{/foreach}
{/if}
{if count($arrErr.import)}
Import process aborted:<br/>
{foreach from=$arrErr.import item=i key=k}
	 - {$i}<br/>
{/foreach}
{/if}
</div>

{if $msg == 'succes'}
	<div class="grn">Saved successfuly</div>
{/if}

{if $msg == 'error'}
	<div class="red">Error. Blog has not by saved</div>
{/if}

<form class="wh" style="width:{if !$arrBlog.id}58{else}65{/if}%;  visibility: hidden; opacity: 0;" id="create_form" method="post" action="" enctype="multipart/form-data">
{if !$arrBlog.id}
<div>
	Load&nbsp;settings:&nbsp;<select id="masterBlog">
	<option value="0"> - select -
	{foreach from=$arrSettingsSelect item=i}
	<option value="{$i.id}">{$i.title}
	{/foreach}
	</select>
</div>
{/if}
<br/>
{if $arrBlog.id}<input type="hidden" value="{$arrBlog.id}" name="arrBlog[id]" />{/if}
<div id="accordion">
{**************************************************************************************}
<h3 class="toggler">Design and Content setup</h3>
	{include file="create/inc_create_step1.tpl"}

{**************************************************************************************}
<div id="proprietary" style="display:none;">
	<h3 id="toggler">Proprietary template options</h3>
	{include file="create/inc_create_step2.tpl"}
</div>		
{**************************************************************************************}
	<h3 class="toggler">Select plugins to install and activate (optional)</h3>
	{include file="create/inc_create_step3.tpl"}
	
{**************************************************************************************}
	{if !$arrBlog.id}
	<h3 class="toggler">Create first post (optional)</h3>
	{include file="create/inc_create_step4.tpl"}
	{/if}
{**************************************************************************************}
	<h3 class="toggler">Technical details</h3>
	{include file="create/inc_create_step5.tpl"}
	
{**************************************************************************************}
	<h3 class="toggler">Advanced options (optional)</h3>
	{include file="create/inc_create_step6.tpl"}
</div>
	<fieldset style="border:none;">
	<ol>
		<li>Save settings of this blog: <input type="checkbox" name="arrBlog[flg_settings]" {if $arrBlog.flg_settings} checked='1' {/if} value="1"></li>
		<li>Add site to syndication network: <input type="checkbox" name="arrBlog[syndication]" {if $arrBlog.syndication||(empty( $arrBlog.id )&&empty( $arrErr ))} checked=""{/if} />
		</li>
		<li><input type="button"  id="create" value="{if !$arrBlog.id}Create{else}Save{/if} Blog"></li>
	</ol>
	</fieldset>
</form>
{literal}
<style>
	.toggler{padding:5px 0 5px 0; cursor:pointer; position:relative; z-index:10;}
	.element{padding:5px 0 5px 0; }
	.error{border:1px solid red;}
</style>
<style>
ol li{position:relative;}
.validation-advice div#validation-id-advice{position:absolute; z-index:100; width:200px;  padding:2px; margin:2px;}
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
//  Exstends to Accordion
var category = new Hash({/literal}{$treeJson}{literal});
var categoryId = {/literal}{$arrBlog.category_id|default:0}{literal};
var myAccordion = new Class({
	Extends: Accordion,
	initialize: function(container, toggler, element, options){
		this.parent(container, toggler, element, options);
		this.initButton();
		
	}, 
	initButton:function(){
		this.prev = $$('a.acc_prev');
		this.next = $$('a.acc_next');		
		var obj = this;
		this.prev.each(function(el){
			el.removeEvents('click');
			el.addEvent('click',function(e){e.stop(); obj.display(obj.previous-1);   });
		});
		this.next.each(function(el){
			el.removeEvents('click');
			el.addEvent('click',function(e){e.stop(); obj.display(obj.previous+1);
			
			var myFx = new Fx.Scroll(document.body, {
    		offset: {
        		'x': 0,
        		'y': 260
    			}
			}).toTop();
			
			});
		});
		setTimeout('displayForm()', 1000);		
	},
	add:function(){
		$('proprietary').style.display='block';
		$('toggler').addClass('toggler');
		$('toggler').getNext().addClass('element');
		this.addSection($('toggler'),$('toggler').getNext());
		$('toggler').getNext().addClass('initElement');
		$$('div.initElement').each(function(div,index){
			div.set('id',index);
		});
		this.initButton();
		this.clearEvent();
		this.initialize($('accordion'), $$('.toggler'), $$('.element'));
	},
	deleteSection:function(init){
		$('proprietary').style.display='none';
		$('toggler').removeClass('toggler');
		$('toggler').getNext().removeClass('element');
		$('toggler').getNext().removeClass('initElement');
		$$('div.initElement').each(function(div,index){
			div.set('id',index);
		});
		
		if( init ) {
			this.clearEvent();
			this.initialize($('accordion'), $$('.toggler'), $$('.element'));
		}
	},
	clearEvent:function(){
		$$('.toggler').each(function(el){
			el.removeEvents(this.trigger);
		});
	}
});

var displayForm = function(){
	$('create_form').fade(1);
};

var jsonSettings = {/literal}'{$jsonSettings}'{literal};
var prop = false;

// Visual Form Effects
var visualEffect = new Class({
	initialize: function(){
		this.initShuffel();
		this.selectPlugin();
		this.headerBar();
		this.initTheme();
		this.initCategory();
		this.initMaster();
	},
	initMaster: function(){
		if ( !$('masterBlog') ){
			return false;
		}
		$('masterBlog').addEvent('change', function(){
			this.setMaster($('masterBlog').value);
		}.bindWithEvent(this));
	},
	setMaster: function(id){
		var arrSettings = JSON.decode(jsonSettings);
		var set = false;
		arrSettings.each(function(item){
			if( item.id == id ){
				set = item;
			}
		});
		if ( !set ) {
			return false;
		}
		this.setCategoryFromId(set.category_id); // set Categories
		$A($('theme').options).each(function(option){ // set Theme
			if ( option.value == set.theme[0] ) {
				this.setTheme(option);
			}
		},this);
		// set Next fields
		set = new Hash(set);
		$A($('create_form').elements).each(function(element){ 
			switch(element.tagName) {
				case 'INPUT' : 
					set.each(function(value,key){ 
						// set Plugins
						if ( element.className == 'plugins' && key == 'plugins' ) { 
							value.each(function(p){
								if( element.value == p ){
									element.checked = 1;
								}
							});
						}
						// set all Radio
						if ( element.type == 'radio' && element.name == 'arrBlog['+ key +']' && element.value == value ) {  
							element.checked = 1;
						}						
						// set all Text
						if( element.name == 'arrBlog['+key+']' && element.type != 'checkbox' && element.type != 'radio'
							&& key != 'db_tableprefix' 
							&& key != 'title' 
							&& key != 'ftp_directory' 
							&& key != 'url' ){ 
							element.value = value; 
						}
					});
					if ( element.name == 'arrFtp[address]' ) { element.value = set.ftp_host } 
					if ( element.name == 'arrFtp[username]' ) { element.value = set.ftp_username } 
					if ( element.name == 'arrFtp[password]' ) { element.value = set.ftp_password } 
				break;
				
				case 'TEXTAREA' :  set.each(function(value,key){ if( element.name == 'arrBlog['+key+']'){ element.value = value; } });  break;
				
				default: break;
			}
		},this);
	},
	initCategory: function(){
	$('category').addEvent('change',function(){
		this.setCategory($('category').value,false);
	}.bindWithEvent(this));
	if(categoryId != 0){
		this.setCategoryFromId(categoryId);
	}
		
	},
	setCategoryFromId: function(id) {
		category.each(function(item){
			var hash = new Hash(item.node);
			hash.each(function(i){
				if(id == i.id){
					this.setCategory(item.id,true);
				}
			},this);
		},this);		
	},
	setCategory: function( pid, selected){
		if( selected ) {
			$A($('category').options).each(function(i){
				if(i.value == pid){
					i.selected=1;
				}
			});
		}
		$('category_child').empty();
		category.each(function(item){
			if( item.id == pid ){
				var hash = new Hash(item.node);
				hash.each(function(v,i){
					var option = new Element('option',{'value':v.id,'html':v.title});
					if(categoryId == v.id){
						option.selected=1;//console.log(v.id);
					}
					option.inject($('category_child'));
				});
			}
		});		
	},
	selectPlugin:function(){
		$('plugins_all').addEvent('click', function(){
			$$('input.plugins').each(function(el){
				el.checked = $('plugins_all').checked;
			});
		});
	},
	initShuffel: function() {
		$$('a.shuffel').each(function(a){
			var obj = this;
			a.addEvent('click', function(e){
				e.stop();
				obj.shuffel(a.rel);
			});
		},this);
		this.loadShuffel();
	},
	loadShuffel:function(){
		this.html = new Array();
		$$('div.shuffCont').each(function(el,index){
			this.html[el.id]=el.get('html');
		},this);
		$$('input.initShuf').each(function(el){
			var div = el.getNext('div');
			div.set('html', this.html[el.value]);
		},this);		
	},
	shuffel: function(no) {
		var a = $("affiliate").get('html');
		var b = $("subscription").get('html');
		var c = $("adsense_sky").get('html');
		var placeA = $('affilate_place').value;
		var placeB = $('subscription_place').value;
		var placeC = $('adsense_sky_place').value;
		var tempText1 = $('affilated_programs').value;
		var tempText2 = $('subscription_form').value;
		var tempText3 = $('adsense_skycraper').value;
		
		if ( no == 1 ) {
			$("affiliate").set('html',c);
			$("subscription").set('html',a);
			$("adsense_sky").set('html',b);
			$('affilate_place').value = placeC;
			$('subscription_place').value = placeA;
			$('adsense_sky_place').value = placeB;
		} else {
			$("affiliate").set('html',b);
			$("subscription").set('html',c);
			$("adsense_sky").set('html',a);
			$('affilate_place').value = placeB;
			$('subscription_place').value = placeC;
			$('adsense_sky_place').value = placeA;
		}

		$('affilated_programs').value = tempText1;
		$('subscription_form').value = tempText2;
		$('adsense_skycraper').value = tempText3;
				
	},
	headerBar: function(){
		$$('input.header_bar').each(function(el){
			el.addEvent('click', function(){
				$$('div.header_bar_block').each(function(e){e.style.display='none';});
				$(el.value).style.display='block';
				this.addRequired(el);
			}.bindWithEvent(this));
		},this);
	},
	addRequired:function(el){
		$$('.propRequired').each(function(element){
			element.removeClass('required');
		});
		switch(el.value){
			case 'adsense_code':  $(el.value).getChildren().getChildren('input').each(function(e){e.addClass('required');});break;
			case 'code':  $(el.value).getChildren().getChildren('textarea').each(function(e){e.addClass('required');}); break;
			case 'upload_banner':  $(el.value).getChildren().getChildren('textarea').each(function(e){e.addClass('required');}); break;
		}		
	},
	initTheme: function(){
		if ( !$chk( $('theme') ) ) {
			return;
		}
		var objThis = this;
		$('theme').addEvent('change',function(){
			objThis.setTheme(this.options[this.selectedIndex]);	
		});
		
		$('theme').addEvent('domready', function(){
			objThis.setTheme(this.options[this.selectedIndex]);	
		});
		
  		$("theme").addEvent('keyup', function(event) {
   			if(event.key != 'down' && event.key !='up' ){
   				return false;
   			}   	
   			objThis.setTheme(this.options[this.selectedIndex]);		
  		});
	},
	setTheme: function(element){
		if(element.className.test('prop')) {
			prop = true;
			objAccordion.add();
		} else {
			objAccordion.deleteSection(prop);
			prop = false;
		}
		if(element.tagName == 'IMG' ){
			$('themeImg').empty();
			$('theme').value=element.id;
			var img = new Element('img', {'src':element.getParent('a').href});
			img.inject($('themeImg'));	
		} else {
			element.selected=1;
			$('themeImg').empty();
			var img = new Element('img', {'src':element.title});
			img.inject($('themeImg'));			
		}
	}
});

Form.Validator.add('ftp_required', {
    errorMsg: 'This field is required',
    test: function(element){
        if (element.value.length == 0) return false;
        else return true;
    }
});
// validator
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
				onElementFail: function(element){this.scrollAccordion(element); this.stop();  this.coord(element)}
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
		if($('validation-id-advice'))
		$('validation-id-advice').set('style','right:'+this.right+'px; top:'+this.top+'px; display:block;');
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
	},
	coord: function(element){
		this.top = -16;
		this.right = 22;
	},
	// Открывает нужный блок аккордиона
	scrollAccordion: function(element){
		var block = parseInt(element.getParent('div.element').id);
		objAccordion.display(block);
		
	}
});

// create Class
var createBlog = new Class({
	initialize: function(){
		this.testDB();
		this.create();
	},
	testDB: function(){
		$('test_db').addEvent('click', function(e){
			e.stop();
			
			var password = encodeURIComponent($('ftp_password').value);
			var req = new Request({url: "{/literal}{url name='site1_blogfusion' action='testdb'}{literal}",onRequest: function(){$('test_db_loader').style.display='inline';}, onSuccess: function(responseText){
				if( responseText == 'succ') {
					r.alert( 'Messages', 'Connection to Server for this database successfully', 'roar_information' );
				} else if( responseText == 'empty') {
					r.alert( 'Messages', 'Please fill required fields: Blog URL, FTP Address, FTP Username, FTP Password, FTP Homepage Folder, Host Name, Database Name, Database User Name, Database Password', 'roar_error' );
				} else if(responseText == 'error'){
					r.alert( 'Messages', 'Not Connect to database. Please enter correct data.', 'roar_error' );
				}
			}, onComplete: function(){$('test_db_loader').style.display='none';} }).post({'url':$('domain').value, 'db_host':$('db_host').value, 'db_name':$('db_name').value, 'db_username':$('db_user').value, 'db_password': $('db_pass').value, 'ftp_host':$('ftp_address').value, 'ftp_username':$('ftp_username').value, 'ftp_password':password, 'ftp_directory':$('ftp_directory').value});
		});
	},
	create: function() {
		var obj = this;
		$('create').addEvent('click', function(e){
			e.stop();
			var valid = new myVaidator($('create_form'));
			if(!valid.startValidate()) {
				return false;
			}
			$('create_form').submit();
			$('create').disabled=true;
		});
	}
});

var redirect=function(url){location.href=url;}

var multibox={};
var visual={};
window.addEvent('domready', function() {
	visual = new visualEffect();
	var create = new createBlog();
	
	// init multibox
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});

	$$('div.initElement').each(function(div,index){
		div.set('id',index);
	});
});

var CpanelDatabaseResult = function(hash){
	$('db_name').value = hash.db;
	$('db_user').value = hash.user;
	$('db_pass').value = hash.pass;
}

var CpanelSubdomainResult = function(hash) {
	$('domain').value = 'http://'+hash.subdomain[0]+'.'+hash.root+'/';
}
</script>
{/literal}
