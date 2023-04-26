<fieldset>
	<legend>FTP details</legend>
	<ol>
		<li>
			<label for="ftp_id"><span>Existing FTP Server</span></label>
			<select name="{$arrayName}[id]" id="ftp_id">
				<option value='0'> - new server or select existing - </option>
				{html_options options=$arrFtps selected=$arrFtp.id}
			</select>
		</li>
		<li><label for="ftp_address"><span>FTP Address <em>*</em></span></label> 
			<input name="{$arrayName}[address]" value="{$arrFtp.address}" type="text" id="ftp_address" class="ftp_required" /></li>
		<li><label for="ftp_username"><span>FTP Username <em>*</em></span></label> 
			<input name="{$arrayName}[username]" value="{$arrFtp.username}" type="text" id="ftp_username" class="ftp_required" /></li>
		<li><label for="ftp_password"><span>FTP Password <em>*</em></span></label> 
			<input name="{$arrayName}[password]" value="{$arrFtp.password}" type="password" id="ftp_password" class="ftp_required" /></li>
		<li><label for="ftp_directory"><span id="title_file" style="display:{if $arrPrm.with_file}block{else}none{/if};">Select file to edit<em>*</em></span> <span id="title_dir" style="display:{if !$arrPrm.with_file}block{else}none{/if};">FTP Homepage Folder<em>*</em></span></label> 
			<input name="{$arrayName}[directory]" value="{$arrFtp.directory}" type="text" readonly="" class="ftp_required" id="ftp_directory" />
			<a href="{url name='ftp_tools' action='browse'}{if $arrPrm.with_file}?mode=with_files{/if}" class="mb" title="Browse FTP" rel="width:600,height:550,type:iframe" id="href"><span id="browse">Browse</span></a>
			<p id="help_file"  style="display:{if $arrPrm.with_file}block{else}none{/if};">(click 'Browse' link and select the file you want to edit by clicking on the file icon - it will automatically fill in the directory path and test the connection here)</p></li>
			<p id="help_dir"   style="display:{if !$arrPrm.with_file}block{else}none{/if};">(click 'Browse' link and select the folder of installation in the popup - it will automatically fill in the directory path and test the connection also here)</p></li>
	</ol>
</fieldset>

<script type="text/javascript">
var ftps=JSON.decode('{$strFtps}'); // для конвертации кодов utf8 см. Core_Json_Encoder - _encodeString
$('ftp_id').addEvent('change',function(){
	if ( this.value>0 ) {
		$('ftp_address').value=ftps[this.value].ftp_address;
		$('ftp_username').value=ftps[this.value].ftp_username;
		$('ftp_password').value=ftps[this.value].ftp_password;
		$('ftp_address').readOnly=$('ftp_username').readOnly=$('ftp_password').readOnly=true;
	} else {
		$('ftp_address').value=$('ftp_username').value=$('ftp_password').value='';
		$('ftp_address').readOnly=$('ftp_username').readOnly=$('ftp_password').readOnly=false;
	}
});

var placePath=function(path) {
	$('ftp_directory').value=path;
}

var isEmpty = new InputValidator('ftp_required', {
	errorMsg: 'This field is required.',
	test: function(field){
		return ((field.get('value') == null) || (field.get('value').length == 0));
	}
});

$('browse').addEvent('click',function(e) {
	if ( isEmpty.test($("ftp_address"))||isEmpty.test($("ftp_username"))||isEmpty.test($("ftp_password")) ) {
		r.alert( 'Client side error', 'Fill FTP Address, Username and Password field', 'roar_error' );
		e.stopPropagation();
		return false;
	}
	var myURI=new URI($('href').href);
	var password = encodeURIComponent($('ftp_password').value);
	myURI.setData({
		ftp_host:$('ftp_address').value,
		ftp_username:$('ftp_username').value,
		ftp_password:password,
		directory:$('ftp_directory').value
	},true);
	$('href').href=myURI.toString();
});
</script>