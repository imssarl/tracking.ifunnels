<br/>
<form method="post" action="" class="wh" id="create_ncsb" style="width:50%">
<input type="hidden" name="arrNcsb[id]" value="{$arrNcsb.id}" />
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
				<label for="address1">Template <em>*</em></label><select name="arrNcsb[template_id]" id="templates">
					<option value=''> - select - </option>
					{html_options options=$arrTemplates selected=$arrNcsb.template_id}
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
	<span{if $smarty.get.template} style="display:none;"{/if}>
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
							<label style="margin:0 0 0 170px;"><select name="arrNcsb[category_id]" class="required" id="category_child" ></select></label>
							</li>
						</ol>
				</fieldset>
			</li>		
			<li>
				<label>URL <em>*</em></label><input type="text" name="arrNcsb[url]" value="{$arrNcsb.url}" />
				<p>Example: http://www.mydomain.com/myfolder/ </p>
			</li>
			<li><label for="adsenseid"><span>Adsense ID <em>*</em></span></label> 
				<input name="arrNcsb[google_analytics]" type="text" id="adsenseid" value="{$arrNcsb.google_analytics}" /></li>
			<li><label for="mainkeyword"><span>Main Keyword <em>*</em></span></label> 
				<input name="arrNcsb[main_keyword]" type="text" id="mainkeyword" value="{$arrNcsb.main_keyword}" /></li>
			<li>
				<label>Add site to syndication network</label><input type="checkbox" name="arrNcsb[syndication]" {if $arrNcsb.syndication||(empty( $arrNcsb.id )&&empty( $arrErr ))} checked=""{/if} />
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
			<li><label for="articlenavigationlength"><span>Article Navigation Length <em>*</em></span></label> 
				<input name="arrNcsb[navigation_length]" type="text" id="articlenavigationlength" value="{if $arrNcsb.navigation_length==0&&$arrNcsb.id}5{else}{$arrNcsb.navigation_length}{/if}" />
				<p>(number of links to articles to display in the sidebar)</p></li>
			<li>
				<fieldset>
					<legend>Display type <em>*</em></legend>
					<label><input name="arrNcsb[flg_snippet]" type="radio" id="show_full" value="no"{if $arrNcsb.flg_snippet==0} checked="1"{/if}> Full Article (display random article on the home page)</label>
					<label><input name="arrNcsb[flg_snippet]" type="radio" id="flg_snippets" value="yes"{if $arrNcsb.flg_snippet=='1'} checked="1"{/if}> Snippets (display article snippets on the home page)</label>
				</fieldset>
			</li>
			<li style="display:{if $arrNcsb.flg_snippet == 'yes'}block{else}none{/if};" id="flg_snippets_1"><label for="snippet_number"><span>Number of article snippets</span></label> 
				<input name="arrNcsb[snippet_number]" type="text" id="snippet_number" value="{$arrNcsb.snippet_number}" /></li>
			<li style="display:{if $arrNcsb.flg_snippet == 'yes'}block{else}none{/if};" id="flg_snippets_2"><label for="snippet_length"><span>Length of each snippet</span></label> 
				<input name="arrNcsb[snippet_length]" type="text" id="snippet_length" value="{$arrNcsb.snippet_length}" /></li>	
			<li>
			{module name='site1_articles' action='multiboxplace' selected=$strJson place='content_wizard' type='multiple' required=1}
				<div id="articleList"></div>
			</li>
		</ol>
	</fieldset>
	
	{module name='advanced_options' action='optinos' site_type=Project_Sites::NCSB site_data=$arrOpt}
	</span>
	<input value="{if $smarty.get.id}Save site{else}Generate new site{/if}" type="submit">
</form>

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
<script type="text/javascript">

var jsonCategory = {$treeJson};
var categoryId = {$arrNcsb.category_id|default:0};
var info={$strTemplatesInfo};
{literal}


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
window.addEvent('domready', function(){
	new Categories();
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
		if(this.hash == false){ return; }
		this.hash.each( function( value, key ) {
			key++ ;
			var div = new Element( 'div' );
			var name = new Element( 'p' );
			name.set( 'html',key + '. ' + value.title.substr( 0, 50 ) + ' <a href="#" class="delete_article_' + this.options.place + '" rel="' + value.id + '">Delete from list</a>' );
			name.injectInside( div );
			div.inject( this.options.contentDiv );
			$('count_article_' + this.options.place).value = key;
		},this );
		$('multibox_ids_'+this.options.place).value = JSON.encode(this.hash);	
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


$('create_ncsb').addEvent('submit',function(e){//e.stop();
	var message = '';

	if( !$('show_full').checked && !$('flg_snippets').checked  ) {
		message='Please select Display type';
	}

	
	if ( $('articlenavigationlength').value=='' ){
		message='Field Article Navigation Length can not be empty';
	}

	if ( $('mainkeyword').value=='' ){
		message='Field Main Keyword  can not be empty';
	} 	 	
	if ( $('adsenseid').value=='' ){
		message='Field Adsense ID can not be empty';
	} 	 

	if ( $('templates').value=='' ) {
		message='Please select a template';
	}

	if(parseInt($('count_article_content_wizard').value) < 2){
		message='Please select more than one article';
	}	

	if( parseInt($('articlenavigationlength').value) >= parseInt($('count_article_content_wizard').value) && parseInt($('count_article_content_wizard').value) > 1) {
		$('articlenavigationlength').value	= $('count_article_content_wizard').value-1;
	}	
	
	if ( message != '' ) {
		e.stop();
		r.alert( 'Warning', message , 'roar_warning' );
	}
 	
	var isEmpty = new InputValidator('ftp_required', {
		errorMsg: 'This field is required.',
		test: function(field){
			return ((field.get('value') == null) || (field.get('value').length == 0));
		}
	});	
	if ( isEmpty.test($("ftp_address"))||isEmpty.test($("ftp_username"))||isEmpty.test($("ftp_password")) || isEmpty.test($('ftp_directory')) ) {
		r.alert( 'Client side error', 'Fill FTP Address, Username, Password and FTP Homepage Folder field', 'roar_error' );
		e.stop();
		return false;
	}
	
});

$('templates').addEvent('change',function(){
	if ( this.value>'' ) {
		info.each(function( item ){
			if( item.id == this.value ){
				$('template_img').set('src',item.preview);
				$('divdesc').set('html',item.description);
			}
		}, this);
	} else {
		$('template_img').set('src','');
		$('divdesc').set('html','');
	}
});
$('templates').fireEvent('change');

$('show_full').addEvent('click',function(){
	$('flg_snippets_1').style.display='none';
	$('flg_snippets_2').style.display='none';
});
//$('show_full').fireEvent('click');

$('flg_snippets').addEvent('click',function(){
	$('flg_snippets_1').style.display='block';
	$('flg_snippets_2').style.display='block';
});
//$('flg_snippets').fireEvent('click');

var multibox={};
window.addEvent('domready', function() {
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
	});
});



function get_check_value(theChkelement,theStoreid) {
	var c_value = "";
	var chks = document.getElementsByName(theChkelement+'[]');


		for (var i=0; i < chks.length; i++)
		   {
		   if (chks[i].checked)
			  {
			  c_value = c_value + chks[i].value +",";
				
			  }
		   }
		
			
	  theStoreid.value=c_value;
	  return c_value;
}

	function validate(frm)
	{

		get_check_value('chkselect', document.getElementById('damsids'));
		
		get_check_value_savesel('chksaveselect_spot1', document.getElementById('spot1_saveselection_id'), 'chksaveselect_spot1id');
		get_check_value('chksnippetsselect_spot1', document.getElementById('spot1_rotating_ids'));
		
		get_check_value_savesel('chksaveselect_spot2', document.getElementById('spot2_saveselection_id'), 'chksaveselect_spot2id');
		get_check_value('chksnippetsselect_spot2', document.getElementById('spot2_rotating_ids'));
		
		get_check_value_savesel('chksaveselect_spot3', document.getElementById('spot3_saveselection_id'), 'chksaveselect_spot3id');
		get_check_value('chksnippetsselect_spot3', document.getElementById('spot3_rotating_ids'));

		var mss = "";
		if (frm.temp_id.value<1) mss += "- Please select a template\n";
		else 
		{
			if (trim(frm.adsense_id.value)=="") mss += "- Please enter adsense id\n";
			if (trim(frm.main_keyword.value)=="") mss += "- Please enter main keyword\n";
			//if (trim(frm.tag_cloud_word.value)=="") mss += "- Please enter tag cloud word\n";
			if (trim(frm.ftp_address.value)=="") mss += "- FTP address should be entered\n";
			if (trim(frm.ftp_username.value)=="") mss += "- FTP username should be entered\n";
			if (trim(frm.ftp_password.value)=="") mss += "- FTP password should be entered\n";
			if (trim(frm.ftp_homepage.value)=="") mss += "- FTP home page should be entered\n";
			if (trim(frm.sub_folder.value)=="") mss += "- Please enter subfolder\n";
			if (trim(frm.url.value)=="") mss += "- Please enter site URL\n";
			if (!(frm.source_type[0].checked || frm.source_type[1].checked )) mss += "- Article source should be selected\n";
			if(frm.source_type[0].checked)
				{	
					var flags=false;
					var element;
					var numberOfControls = frm.length;
					//alert("???"+numberOfControls);
					for(i=0;i<numberOfControls;i++)
					{
						if (frm[i].type == "checkbox")
						{
							if (frm[i].checked == true)
							{
								flags=true;
							}
						}
						
					}
					if (flags==false)	mss +="- Please select at least one checkbox \n";
				}
		}
		if (mss.length>0)
		{
			alert(mss);
			return false
		}

		var data = new Array(4);
		//frm.description.value = frm.title.value;
		//frm.importmanual.value = frm.prim_keyword.value+"\n"+frm.importmanual.value;
		data[0] = frm.url.value;
		data[1] = frm.ftp_address.value;
		data[2] = frm.ftp_username.value;
		data[3] = frm.ftp_password.value;
		data[4] = frm.ftp_homepage.value;

		operation("validate",data);

		return false;
	}


function operation(process,tmpl) {
	 if (process == "getftpdtl")
	{
		changeDetailMode(tmpl)

		if (tmpl.checked)
		{

			val = document.getElementById("packagelist").value;
			url = "ncsbsites.php?process=getftpdtl&id="+val;

			ajaxRequest(url,"getftpdtl",1);
		}

	}
	else if (process == "validate")
	{
		url ="ncsbsites.php?process=validate&url="+tmpl[0]+"&ftp_address="+tmpl[1]+"&ftp_username="+tmpl[2]+"&ftp_password="+tmpl[3]+"&ftp_homepage="+tmpl[4];
		ajaxRequest(url,"validate",1);
	}
}

	function ajaxResponse(xmlHttp, process, part) {
	if (xmlHttp.readyState == 4) 
	{
		hdwtms();
		if (xmlHttp.status == 200) 
		{
			if (xmlHttp.responseText != "")
			{
				response = explodeStr(xmlHttp.responseText, "!%#%!");
				if (process == "getftpdtl")
				{
					if (response[0]=="true")
					{
						document.getElementById("ftp_address").value =response[1];
						document.getElementById("ftp_username").value =response[2];
						document.getElementById("ftp_password").value =response[3];
						document.getElementById("same_ftp_address").value =response[1];
						document.getElementById("same_ftp_username").value =response[2];
						document.getElementById("same_ftp_password").value =response[3];
					}
				}
				else if (process =="validate")
				{
					if (response[0]=="true")
					{
						document.cnbnewsite.submit();
					}
					else
					{
						if (trim(response[1])!="noresp")
						document.getElementById("urlmsg").innerHTML =response[1];
						else 
						document.getElementById("urlmsg").innerHTML = "";
						if (trim(response[2])!="noresp")
						document.getElementById("ftpmsg").innerHTML =response[2];
						else
						document.getElementById("ftpmsg").innerHTML = "";
					}
				}
			}
			else
			{
				message.value = "Unable to perform operation";
			}
			} 
			else 
			{
				alert("There was a problem retrieving the XML data:\n" +
				xmlHttp.statusText);
			}
		}
		if (xmlHttp.readyState == 1) {shwtms("Please wait....");
		}
	}

function	displayftpserver() {
	if(document.getElementById("ftpserveroption").value=="new_ftp")	{	
		document.getElementById('ftp_address').value= "";
		document.getElementById('ftp_username').value= "";
		document.getElementById('ftp_password').value= "";
		document.getElementById('ftp_address').readOnly=false;
		document.getElementById('ftp_username').readOnly=false;
		document.getElementById('ftp_password').readOnly=false;
	}else	if(document.getElementById("ftpserveroption").value=="" || document.getElementById("ftpserveroption").value!="new_ftp")	{
		var temp	= new	Array();
		str=document.getElementById("ftpserveroption").value;
		temp=str.split(' ');
		document.getElementById('ftp_address').readOnly=true;
		document.getElementById('ftp_username').readOnly=true;
		document.getElementById('ftp_password').readOnly=true;
		document.getElementById('ftp_address').value= temp[0];
		document.getElementById('ftp_username').value= temp[1];
		 document.getElementById('ftp_password').value=	temp[2];
	}
}

	function browseroot(which)
	{
		addr = document.getElementById("ftp_address").value;
		user = document.getElementById("ftp_username").value;
		pass = document.getElementById("ftp_password").value;
		if (addr.length==0 || user.length == 0 || pass.length == 0)
		{
		alert("Please enter all FTP details");
		return false;
		}
		pass=URLEncode('ftp_password');
		testwindow= window.open("browsef.php?dir=&onlyf=yes&oldv=yes&address="+addr+"&username="+user+"&password="+pass+"&homebox="+which, "mywindow" ,"status=0,scrollbars=1,width=400,height=500,resizable=1");
		testwindow.moveTo(50,50);
		//window.open("" );
	}
{/literal}
</script>