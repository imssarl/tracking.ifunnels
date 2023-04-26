<br/>
<form action="" method="post" class="wh" style="width:50%" id="from-create">
{if count($arrErr)}
	{foreach from=$arrErr item=i}
		<p style="color:red;"><b>Error: {$i}</b></p>
	{/foreach}
{/if}
<fieldset>
	<legend>Configuration settings</legend>
	<ol>
		<li>
			<fieldset>
				<legend>Select Category <em>*</em></legend>
					<ol>
						<li>
					 	<label style="margin:0 0 0 170px;"><select id="category"  >
					 	<option value="0"> - select -
					 	{foreach from=$arrCategories item=i}
					 		<option {if $arrBlog.category_id == $i.id}selected='1'{/if} value="{$i.id}">{$i.title}
					 	{/foreach}</select></label>
						</li>
						<li>	
						<label style="margin:0 0 0 170px;"><select name="arrPsb[category_id]" class="required" id="category_child" ></select></label>
						</li>
					</ol>
			</fieldset>
		</li>			
		<li>
			<label>URL <em>*</em></label><input class="required" type="text" name="arrPsb[url]">
		</li>
		<li>
			<label>Main Keyword <em>*</em></label><input class="required" type="text" name="arrPsb[main_keyword]">
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
<input type="submit" value="Import" id="import" />
</form>

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<style>
ol li{position:relative;}
.validation-advice{position:absolute; top:-21px; right:-115px;  z-index:100;}
.validation-advice div#validation-id-advice{width:200px;  padding:2px; margin:2px;}
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
var jsonCategory = {$treeJson|default:'null'};
var categoryId = {$arrNcsb.category_id|default:0};
{literal}
var PSB = new Class({
	initialize: function( options ){
		this.initSubmit();
		this.start();
	},
	start: function(){
		
	},
	initSubmit: function(){
		$('from-create').addEvent('submit',function( e ){
			$('import').set('disabled','1');
		});
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
		if (!$chk(this.arrCategories)) {
			return;
		}
		this.arrCategories.each(function(el){
			if( el.id == id ) { bool=true; }
		}); 
		return bool;
	},
	setFromFirstLevel: function( id ){
		if (!$chk(this.arrCategories)) {
			return;
		}		
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
		if (!$chk(this.arrCategories)) {
			return;
		}		
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

var multibox={};
window.addEvent('domready', function(){
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
	});
	
	new PSB();
	new Categories();
	$('import').addEvent('click',function( e ){
		e && e.stop();
		var valid = new myVaidator($('from-create'));
		if(valid.startValidate()) {
			$('from-create').submit();
		}		
	});
});
{/literal}
</script>