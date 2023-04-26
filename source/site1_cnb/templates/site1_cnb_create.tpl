<br/>
<form action="" method="post" class="wh" style="width:50%" id="from-create" enctype="multipart/form-data">
<input type="hidden" name="arrCnb[id]" value="{$arrCnb.id}" />
{if count($arrErr)}
	{foreach from=$arrErr item=i}
		<p style="color:red;"><b>Error: {$i}</b></p>
	{/foreach}
{/if}
	<p>Please complete the form below. Mandatory fields are marked with <em>*</em></p>
<fieldset>
	<legend>Site template</legend>
	<ol>
		<li>
			<label>Template <em>*</em></label><select class="required" id="select-template" name="arrCnb[template_id]"><option value="" id=""> - select -
			{foreach from=$arrTemplates item=i}
			<option {if $arrCnb.template_id == $i.id}selected{/if} value="{$i.id}">{$i.title}
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
			<label>Choose Profile <em>*</em></label><select class="required" name="arrCnb[profile_id]"><option value=""> - select - 
			{foreach from=$arrProfile item=i}
			<option {if $arrCnb.profile_id == $i.id}selected{/if} value="{$i.id}">{$i.profile_name}
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
					 		<option {if $arrCnb.category_id == $i.id}selected='1'{/if} value="{$i.id}">{$i.title}
					 	{/foreach}</select></label>
						</li>
						<li>	
						<label style="margin:0 0 0 170px;"><select name="arrCnb[category_id]" class="required" id="category_child" ></select></label>
						</li>
					</ol>
			</fieldset>
		</li>
		<li>
			<label>Site Title <em>*</em></label><input class="required" type="text" name="arrCnb[title]" value="{$arrCnb.title}">
		</li>		
		<li>
			<fieldset>
				<legend>Site type <em>*</em></legend>
				<label><input type="radio" class="select-type" name="site_type" {if !isset($arrCnb.parent_id) || $arrCnb.parent_id == 0}checked='1'{/if} value="0"> New Standalone Site</label>
				<label><input type="radio" class="select-type" name="site_type"  {if $arrCnb.parent_id}checked='1'{/if}value="1"> Portal site </label>
			</fieldset>
		</li>
		<li id="portal" style="display:{if $arrCnb.parent_id}block{else}none{/if}">
			<label>Portal</label>
			<select name="arrCnb[parent_id]">
				<option value=""> - select -
				{foreach from=$arrPortals item=i}
				<option {if $arrCnb.parent_id==$i.id}selected="1"{/if} value="{$i.id}">{$i.title} [ {$i.url} ]
				{/foreach}
			</select>
		</li>
		<li>
			<label>URL <em>*</em></label><input class="required" type="text" name="arrCnb[url]" value="{$arrCnb.url}">
			<p>Example: http://www.mydomain.com/myfolder/ </p>
		</li>
		<li>
			<label>Sub Folder </label><input   type="text" name="arrCnb[sub_dir]" value="{$arrCnb.sub_dir}">
			<p>Example: if www.site.com/myfolder/ is your site, input myfolder in the above field </p>
		</li>
		<li>
			<label>Add site to syndication network</label><input type="checkbox" name="arrCnb[syndication]" {if $arrCnb.syndication||(empty( $arrCnb.id )&&empty( $arrErr ))} checked=""{/if} />
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
			<label>Primary keyword  <em>*</em></label><input type="text" name="arrCnb[primary_keyword]" value="{$arrCnb.primary_keyword}" >
		</li>
		{if empty($arrCnb.id)}
		<li>
			<label>Keywords  <em>*</em></label><textarea id="keyword-conteiner" name="arrPrj[keywords]" style="height:150px;">{$arrPrj.keywords}</textarea>
			<p><a href="{url name='keyword_generator' action='multiboxlist'}" rel="width:800,height:500" class="mb">Get keywords from Keyword Research</a></p>
		</li>
		<li>
			<fieldset>
				<legend>Select mode  <em>*</em></legend>
					<label><input type="radio" name="arrPrj[flg_schedule]" class="select-mode" dir="one-time" value="1" {if $arrPrj.flg_schedule == 1}checked="1"{/if}/> One Time</label>
					<label><input type="radio" name="arrPrj[flg_schedule]" class="select-mode" dir="recurring" value="2" {if $arrPrj.flg_schedule == 2}checked="1"{/if}/> Scheduling</label>
			</fieldset>
		</li>
		<li {if $arrPrj.flg_schedule != 1}style="display:none;"{/if} id="one-time" class="block-mode">
			<fieldset>
				<label><input type="radio" name="arrPrj[flg_generate]" {if $arrPrj.flg_generate ==1} checked=1 {/if} value="1"> Get All</label>
				<table cellpadding="0" style="padding:2px 0 2px 0;" cellspacing="0"><tr><td><label><input type="radio" {if $arrPrj.flg_generate ==2} checked="1" {/if} name="arrPrj[flg_generate]" value="2"> Get&nbsp;</label></td><td><input type="text" name="arrPrj[keywords_first]" value="{if $arrPrj.flg_generate == 2}{$arrPrj.keywords_first}{/if}" style="width:40px"> first keywords</td></tr></table>
				<table cellpadding="0" style="padding:2px 0 2px 0;" cellspacing="0"><tr><td><label><input type="radio" {if $arrPrj.flg_generate ==3} checked="1" {/if} name="arrPrj[flg_generate]" value="3"> Get&nbsp;</label></td><td><input type="text" name="arrPrj[keywords_random]"  value="{if $arrPrj.flg_generate == 3}{$arrPrj.keywords_random}{/if}" style="width:40px"> random keywords </td></tr></table>
			</fieldset>
		</li>
		<div {if $arrPrj.flg_schedule != 2}style="display:none;"{/if} id="recurring"  class="block-mode">
			<li>
				<label for="time_in_between">Time in between each posts</label>
				<input id="time_in_between" type="text" name="arrPrj[time_between]" {if $arrPrj.flg_status==1} disabled='1'{/if} value="{$arrPrj.time_between}" >&nbsp;( mins )
			</li>
			<li>
				<label for="random_factor">Random factor</label>
				<input id="random_factor" type="text" name="arrPrj[random]"{if $arrPrj.flg_status==1} disabled='1'{/if} value="{$arrPrj.random}" >&nbsp;( mins )
			</li>
			<li>
				<label for="view-date">Start Date</label>
				<input type="text" name="arrPrj[start]"  readonly='1'{if $arrPrj.flg_status==1} disabled='1'{/if} value="{if $arrPrj.start}{$arrPrj.start|date_format:"%Y-%m-%d %H:%M"}{/if}" id="view-date" ><input type="hidden" value="{if !$arrPrj.start}{$smarty.now|date_format:"%Y-%m-%d %T"}{else}{$arrPrj.start|date_format:"%Y-%m-%d %T"}{/if}" id="date" ></span>&nbsp;<img src="/skin/_js/jscalendar/img.gif" id="trigger" style="{if $arrPrj.flg_status == 1}display:none;{/if}cursor:pointer;">
			</li>
		</div>	
		{/if}	
	</ol>
</fieldset>
<fieldset>
	<legend>Additional Feature</legend>
	<ol>
		<li>
			<label>Upload Header Image</label><input type="file" name="header"/>
			<p class="helper">Notice: max width=800px, recommended height:150px</p>
		</li>
		<li>
			<label>Upload Report Image</label><input type="file" name="report" />
		</li>
		<li>
			<label>Upload the article thumbnail</label><input type="file" name="thumb" />
		</li>
	</ol>
</fieldset>
<input type="hidden" name="post_true" value="1">
{module name='advanced_options' action='optinos' site_type=Project_Sites::CNB site_data=$arrOpt}
</span>
<input type="submit" id="create" value="{if $arrCnb.id}Save site{else}Generate new site{/if}" />
</form>
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="/skin/_js/jscalendar/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="/skin/_js/jscalendar/calendar.js"></script>
<script type="text/javascript" src="/skin/_js/jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="/skin/_js/jscalendar/calendar-setup.js"></script>
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
var post = {$post_true|default:'null'};
var edit = {$arrCnb.id|default:'null'};
var templateId = {$arrCnb.template_id|default:'null'};
var arrTemplates = {$jsonTemplates|default:'null'};
var jsonCategory = {$treeJson|default:'null'};
var categoryId = {$arrCnb.category_id|default:0};
{literal}
var setKeyword = function( arr ){
	var str='';
	arr.each(function(item){
		 str += item.keyword + '\n';
	});
	$('keyword-conteiner').value=str;
}
var CNB = new Class({
	Implements:Options,
	options:{
		templatesSelect:'select-template',
		countSpot:10
	},
	initialize: function( options ){
		this.setOptions( options );	
		this.initSubmit();
		this.elementTemplateSelect = $( this.options.templatesSelect );
		this.selectMode();
		this.selectType();
		this.start();
	},
	start: function(){
		this.selectTemplate();
		if( templateId != null ) {
			this.editTemplate();
		}
	},
	selectMode: function(){
		$$('.select-mode').each(function(item){
			item.addEvent('click', function(){
				$$('.block-mode').each(function(li){ li.setStyle('display', 'none'); });
				if(item.value == 1){
					$('one-time').setStyle('display','block');
					$('recurring').setStyle('display','none');
				} else {
					$('one-time').setStyle('display','none');
					$('recurring').setStyle('display','block');
				}
			});
		});
	},
	selectType: function(){
		$$('.select-type').each(function(item){
			item.addEvent('click', function(){
				$('portal').setStyle('display', ((item.value==1)?'block':'none'));
			});
		});
	},	
	initSubmit: function(){
		$('from-create').addEvent('submit', function(){
			$('create').set('disabled','1');
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
					}
				},this);
		}.bindWithEvent( this ) );
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
{/literal}{if empty($arrCnb.id)}{literal}
Calendar.setup({
        inputField     :    "date",
        ifFormat       :    "%Y-%m-%d %H:%M %s",
        showsTime      :    true,
        button         :    "trigger",
        step           :    0,
        
        onUpdate : function() {
  			var date =  $( 'date' ).get( 'value' );
        	var date = date.substitute( {},/[0-9]{5,20}/g );
        	$( 'view-date' ).set( 'value',date.trim() );
        },
        onClose : function(){
			var date =  $( 'date' ).get( 'value' );
        	var date = date.substitute( {},/[0-9]{5,20}/g );
        	$( 'view-date' ).set( 'value',date.trim() );
        	this.hide();
        }
    });
{/literal}{/if}{literal}    
var multibox = {};
window.addEvent('domready', function(){
	new CNB();
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
			$('create').disabled=true;
			$('from-create').submit();
		}		
	});
});
{/literal}
</script>