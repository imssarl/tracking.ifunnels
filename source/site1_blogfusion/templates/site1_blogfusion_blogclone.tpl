<br/>
<br/>
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
<form style="width:51%;" class="wh"  id="create_form" method="POST" action="">
	<fieldset>
		<legend>New Blog Settings</legend>
		<ol>
			<li>
				<label>Blog Name <em>*</em></label><input type="text" name="arrBlog[title]" value="{$arrBlog.title}" />
			</li>
			<li>
				<label>Blog URL(full URL) <em>*</em></label><input type="text" class="required {if $arrErr.filtered.url}error{/if}" value="{$arrBlog.url}" name="arrBlog[url]" id="domain" />&nbsp;{module name='cpanel_tools' action='set' type='subdomain' set='one' info='allocate'}
				<p class="helper"><font color="Red">Note:</font> Please provide full URL here including the folder name, where you want to install the blog. i.e. http://www.mysite.com/blog or http://blog.mysite.com. Otherwise blog will not be properly installed on your remote server.</p>
			</li>
			<li>
				<label>Sub Folder </label><input type="text" name="arrBlog[sub_dir]" value="{$arrBlog.sub_dir}" />
				<p class="helper">Example: if www.site.com/blog/ is your site, input /blog/ in the above field </p>
			</li>				
		</ol>
	</fieldset>
	{module name='ftp_tools' action='set' selected=$arrFtp arrayName='arrFtp'}
	<fieldset>
		<legend>New Database Detail</legend>
		<ol>
			<li>
				Createe new database and users: {module name='cpanel_tools' action='set' type='database' info='allocate'}  or fill database detail below 
			</li>
			<li>
				<label>Host Name <em>*</em></label><input type="text" title="Host Name" class="required {if $arrErr.filtered.db_host}error{/if}" value="{$arrBlog.db_host}" name="arrBlog[db_host]" id="db_host" /><p>(Please enter host name like "localhost" or "192.168.1.7")</p>
			</li>
			<li>
				<label>Database Name <em>*</em></label><input type="text"  title="Database Name" class="required {if $arrErr.filtered.db_name}error{/if}"  value="{$arrBlog.db_name}" name="arrBlog[db_name]" id="db_name" />
			</li>
			<li>
				<label>Database User Name <em>*</em></label><input type="text" title="Database User Name" class="required {if $arrErr.filtered.db_user}error{/if}" value="{$arrBlog.db_username}" name="arrBlog[db_username]" id="db_user" />
			</li>
			<li>
				<label>Database Password <em>*</em></label><input type="password" title="Database Password" class="required {if $arrErr.filtered.db_password}error{/if}" value="{$arrBlog.db_password}" name="arrBlog[db_password]" id="db_pass" /> <a href="#" id="test_db">Test connection <img src="/skin/i/frontends/design/ajax-loader_new.gif" style="display:none;" id="test_db_loader"></a>
			</li>
			<li>
				<label>Table Prefix</label><input type="text" name="arrBlog[db_tableprefix]"  value="{if $arrBlog.db_tableprefix}{$arrBlog.db_tableprefix}{else}wp_{/if}"/>
			</li>
			<li>
				<label>Clone blog without posts</label><input type="hidden" name="arrBlog[without_post]" value="0"><input type="checkbox" name="arrBlog[without_post]" value="1" />
			</li>
			<li>
				<label>Clone blog without pages</label><input type="hidden" name="arrBlog[without_page]" value="0"><input type="checkbox" name="arrBlog[without_page]" value="1" />
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>New Dashboard Login detail</legend>
		<ol>	
			<li>
				<label>Login ID <em>*</em></label><input type="text" title="Login ID"  class="required {if $arrErr.filtered.dashboad_username}error{/if}"  value="{$arrBlog.dashboad_username}" name="arrBlog[dashboad_username]" />
			</li>
			<li>
				<label>Password <em>*</em></label><input type="password" title="Password"  value="{$arrBlog.dashboad_password}" class="required {if $arrErr.filtered.dashboad_password}error{/if}" name="arrBlog[dashboad_password]"/>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<ol>
			<li><input type="submit" value="Clone" id="create"/> </li>
		</ol>
	</fieldset>
</form>
{literal}
<style>
.toggler{padding:5px 0 5px 0; cursor:pointer; position:relative; z-index:10;}
.element{padding:5px 0 5px 0; }
.error{border:1px solid red;}
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
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script type="text/javascript">
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
				onElementFail: function(element){ this.stop();  this.coord(element)}
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
		this.right = -72;
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
var multibox={};
window.addEvent('domready',function(){ 
	new createBlog();
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});
})
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