
<br/>
<div align="center">
	<div  style="width:58%;">
		<a class="" href="{url name='site1_blogfusion' action='create'}" rel="create_form">Create blog</a> | 
		<a class="" href="{url name='site1_blogfusion' action='import'}"   class="select_type" rel="import_form">Import blog</a>
	</div>
</div>	
<br/>


<div class="red">
{if count($arrErr.filtered)}
	Error: Please fill all required fields
{/if}
{if count($arrErr.import)}
Process aborted:<br/>
{foreach from=$arrErr.import item=i key=k}
	 - {$i}<br/>
{/foreach}
{/if}
</div>

<form class="wh" style="width:55%;" id="import_form" method="POST" action="" >
	<fieldset>
		<legend>Add Existing Blog </legend>
		<ol>
			<li>
				<label>Blog Name <em>*</em> </label><input type="text" name="arrBlog[title]" class="{if $arrErr.filtered.title}error{/if}" value="{$arrBlog.title}" />
			</li>
			<li>
				<fieldset>
					<legend>Select Category</legend>
						<ol>
							<li>
						 	<label style="margin:0 0 0 170px;"><select id="category"  >
						 	<option value="0"> -select-
						 	{foreach from=$arrCategories item=i}
						 		<option {if $arrBlog.category_id == $i.id}selected='1'{/if} value="{$i.id}">{$i.title}
						 	{/foreach}</select></label>
							</li>
							<li>	
							<label style="margin:0 0 0 170px;"><select name="arrBlog[category_id]" class="required" id="category_child" ></select></label>
							</li>
						</ol>
				</fieldset>
			</li>
			<li>
				<label>Blog URL(full URL) <em>*</em></label><input type="text" class="required {if $arrErr.filtered.url}error{/if}" value="{$arrBlog.url}" name="arrBlog[url]" id="domain" />
			</li>
			<li><label>Add site to syndication network: </label><input type="checkbox" name="arrBlog[syndication]" {if $arrBlog.syndication||(empty( $arrBlog.id )&&empty( $arrErr ))} checked=""{/if} />
		</li>

		</ol>
	</fieldset>
	{module name='ftp_tools' action='set' selected=$arrBlog.arrFtp arrayName='arrFtp'}
	<fieldset>
		<legend>Database Details</legend>
		<ol>
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
		</ol>
	</fieldset>
	<fieldset>
		<legend>Dashboard Login detail</legend>
		<ol>	
			<li>
				<label>Login ID <em>*</em></label><input type="text" title="Login ID"  class="required {if $arrErr.filtered.dashboad_username}error{/if}"  value="{$arrBlog.dashboad_username}" name="arrBlog[dashboad_username]" />
			</li>
			<li>
				<label>Password <em>*</em></label><input type="password" title="Password"  value="{$arrBlog.dashboad_password}" class="required {if $arrErr.filtered.dashboad_password}error{/if}" name="arrBlog[dashboad_password]"/>
			</li>
			<li>
				<input type="submit" id="submit" value="Import Blog" >
			</li>
		</ol>
	</fieldset>
</form>
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>

{literal}
<style>
.error{border:1px solid red;}
</style>
<script type="text/javascript">
var category = new Hash({/literal}{$treeJson}{literal});
var categoryId = {/literal}{$arrBlog.category_id|default:0}{literal};

// import Class
var importBlog = new Class({
	initialize: function(){
		this.testDB();
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
	}
});


var visualEffect = new Class({
	initialize: function(){
		this.initCategory();
	},
	initCategory: function(){
	$('category').addEvent('change',function(){
		this.setCategory($('category').value,false);
	}.bindWithEvent(this));
	if(categoryId != 0){
		category.each(function(item){
			var hash = new Hash(item.node);
			hash.each(function(i){
				if(categoryId == i.id){
					this.setCategory(item.id,true);
				}
			},this);
		},this);
	}
		
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
	}
});

// Initialize Class
var multibox={};
window.addEvent('domready', function() {
	var objImport = new importBlog();
	var view = new visualEffect();
	// init multibox
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		nobuttons: true
	});
	$('import_form').addEvent('submit',function(){
		$('submit').disabled = true;
	});
});


</script>
{/literal}