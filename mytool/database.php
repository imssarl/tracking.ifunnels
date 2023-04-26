<html>
<head>
<meta http-equiv="Content-Language" content="en-us" />
<title>Database Script</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<script type="text/javascript" src="../jscripts/sdeivalidations.js"></script>
<script type="text/javascript">
function showhelp()
{	
	if(document.getElementById('help').style.display="none")
	{
		document.getElementById('help').style.display="block";
	}
	else if(document.getElementById('help').style.display="block")
	{
		document.getElementById('help').style.display="none";
	}
}
function showpasshelp()
{	
	if(document.getElementById('passhelp').style.display="none")
	{
		document.getElementById('passhelp').style.display="block";
	}
	else if(document.getElementById('passhelp').style.display="block")
	{
		document.getElementById('passhelp').style.display="none";
	}
}
function Othertheme()
{
	if(document.getElementById('cpanelversion').value=="other")
	{
		document.getElementById('othertheme').style.display="block";
	}
	else
	{
		document.getElementById('othertheme').style.display="none";
	}
}
function Validateform()
{
	var hostname,cpaneluser,cpanelpassword,database,dbuser,dbpassword;
	hostname=document.getElementById('txtcpanelhost');
	cpaneluser=document.getElementById('txtcpaneluser');
	cpanelpassword=document.getElementById('txtcpanelpass');
	database=document.getElementById('txtnewdb');
	dbuser=document.getElementById('txtdbuser');
	dbpassword=document.getElementById('txtdbpass');
	var invalid = " "; // Invalid character is a space
	var minLength = 6;
	
	if(isEmpty(hostname.value))
	{
		alert("Please enter hostname");
		hostname.focus();
		return false;
	}
	if(isValidURL(hostname.value)==false){
			alert("Please enter valid hostname.");
			hostname.focus();
			return false;
		}
	if(isEmpty(cpaneluser.value))
	{
		alert("Please enter control panel user name");
		cpaneluser.focus();
		return false;
	}
	if(!Databaseuser(cpaneluser.value))
	{
		alert("Please enter valid control panel user name");
		cpaneluser.focus();
		return false;
	}

	if(isEmpty(cpanelpassword.value))
	{
		alert("Please enter control panel user password");
		cpanelpassword.focus();
		return false;
	}
	if(cpanelpassword.value.indexOf(invalid) > -1)
	{	
		alert("Sorry, spaces are not allowed in control panel user password.")
		cpanelpassword.focus();
		return false;
	}
	
	if(isEmpty(database.value))
	{
		alert("Please enter new database");
		database.focus();
		return false;
	}
	if(!isDbName(database.value))
	{
		alert("Please enter valid database name");
		database.focus();
		return false;
	}
	if(isEmpty(dbuser.value))
	{
		alert("Please enter new database user name");
		dbuser.focus();
		return false;
	}
	if(!isDbName(dbuser.value))
	{
		alert("Please enter valid database user name");
		dbuser.focus();
		return false;
	}
	if(isEmpty(dbpassword.value))
	{
		alert("Please enter new database user password");
		dbpassword.focus();
		return false;
	}
	if(dbpassword.value.indexOf(invalid) > -1)
	{	
		alert("Sorry, spaces are not allowed in ftp password.")
		dbpassword.focus();
		return false;
	}
	if (dbpassword.value.length < minLength) 
	{
		alert("Your password must be at least 6 characters long.")
		dbpassword.focus();
		return false;
	}
	if(document.getElementById("usecure"))
	{
		
	}
	else 
	{
		alert("Your database password must be Uber Secure.Please take help by clicking on help icon.")
		dbpassword.focus();
		return false;		
	}
	return true;
	
}
function parentfill1()
{
	var str;
	if(document.getElementById('txtcpaneluser').value!='' && document.getElementById('txtnewdb').value!='' && document.getElementById('txtdbuser').value!='' && document.getElementById('txtdbpass').value!='')
	{
		opener.document.getElementById('db_name').value=document.getElementById('txtnewdb').value;
		opener.document.getElementById('db_username').value=document.getElementById('txtdbuser').value;
		opener.document.getElementById('db_password').value=document.getElementById('txtdbpass').value;
		
	}
	window.close();
	
}

</script>
<script src="../jscripts/ajaxforms.js" type="text/javascript" language="javascript"></script>
<body>
<!--end populate html for cpanel database creation by sdei-->
<?php
global $result;
$result = '';
$mode = "";
$error = "";
if ( isset( $_POST['cmdCpaneldatabase'] ) ) {
	$db_name_get = urlencode( mysql_escape_string( $_POST["txtnewdb"] ) );
	// new cpanel database user name
	$db_username_get = urlencode( mysql_escape_string( $_POST["txtdbuser"] ) );
	// new cpanel database user password
	$db_userpass_get = urlencode( mysql_escape_string( $_POST["txtdbpass"] ) );
	// cPanel username (you use to login to cPanel)
	$cpanel_user_get = urlencode( mysql_escape_string( $_POST["txtcpaneluser"] ) );
	// cPanel password (you use to login to cPanel)
	$cpanel_password_get = urlencode( mysql_escape_string( $_POST["txtcpanelpass"] ) );
	// cPanel domain (example: mysite.com)
	$cpanel_host_get = urlencode( mysql_escape_string( $_POST["txtcpanelhost"] ) );
	include( "sample_database.php" );
} else {
	form();
}

function form() {

	?>
<!--start populate html for cpanel database creation by sdn-->
<form name="passcheck" id="passcheck" action="" method="post" onSubmit="return Validateform()">

	<table align="center" width="90%" border="0" class="blue_brd" bgcolor="#FFFFFF">
		<tr class="blue_tab"><td colspan="2"><b>New database info</b></td></tr>
	<tr>
		<td colspan="2" align="center" class="heading" style="color:red;text-align:justify;"><b><?php echo $error;
	?></b></td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="heading">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="heading"><b>Cpanel info</b></td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="heading">&nbsp;</td>

	</tr>
	<tr>
		<td align='right'>Hostname:</td>
		<td width="228" align='left'><input type="text" name="txtcpanelhost" id="txtcpanelhost" maxlength="250"><img src="help.jpg" title="Hostname like mysite.com"/></td>
	</tr>
	<tr>
		<td align='right'>Username:</td>
		<td width="228"><input type="text" name="txtcpaneluser" id="txtcpaneluser" maxlength="100"><img src="help.jpg" title="Cpanel user name"/></td>
	</tr>
	<tr>
		<td align='right'>Password:</td>
		<td width="228"><input type="password" name="txtcpanelpass" id="txtcpanelpass" maxlength="50"><img src="help.jpg" title="Cpanel password"/></td>
	</tr>
	
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td align='right'>cPanel Theme / Skin:</td>
		<td>		
		<select name="cpanelversion" id="cpanelversion" onChange="javascript:Othertheme();"><option value='x'>x</option><option value='x2'>x2</option><option value='x3'>x3</option><option value='other'>other</option></select><a href='#' onClick="javascript:showhelp();"><img border='0' src="help.jpg" title="Click here to know how to determine Cpanel theme / skin."/></a>&nbsp;<input style='display:none;' type='text' maxlength='50' id='othertheme' name='othertheme' size='8'/>
		</td>
	</tr>	
	<tr><td align="left" colspan="2"><strong><span style="color:#FF0000">Note:</span></strong> Please Check your cpanel theme/skin before select.The script will not work if wrong cPanel theme is selected. Usually cPanel skin name would be "x", but yours may be different.<br/><br />
	
	<div id='help' style='display:none;'><strong>Try following steps if you do not know what your current cPanel theme is.</strong> 	<ul>
	  <li>Login to your cPanel account</li>
	  <li>Look at the URL in your browser. It would look somewhat similar to <strong>http://www.hosting.com:2082/frontend/x/index.html</strong></li>
	  <li>cPanel  theme name is everything after the &quot;/frontend/&quot;, and before the next  slash &quot;/&quot;. In above example cPanel theme is &quot;x&quot;. It could be &quot;x2&quot;,  &quot;rvblue&quot;, etc.</li>
	</ul></div>
	</td></tr>
	<tr>
		<td colspan="2" align="center"  class="heading"><b>New database info</b></td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="heading">&nbsp;</td>
	</tr>
	<tr>
		<td align='right'>Database name:</td><td><input type="text" name="txtnewdb" id="txtnewdb" maxlength="50"><img src="help.jpg" title="New database name"/></td>
	</tr>
	<tr>
		<td align='right'>Username:</td>
		<td><input type="text" name="txtdbuser" id="txtdbuser" maxlength="5"><img src="help.jpg" title="New database user name"/></td>
	</tr>
	<tr>
		<td align='right'>Password:</td>
		<td><input type="password" name="txtdbpass" id="txtdbpass" maxlength="8" onKeyUp="javascript:get(this.parentNode);"><a href='#' onClick="javascript:showpasshelp();"><img border="0" src="help.jpg" title="Click here to know how to set password strength."/></a>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="justify">
	<div id='passhelp' style='display:none;'>As Some cpanel doesnot allow to create database user which have not above 70% password strength.So you need to set password which is Uber Secure(above 70%).<br/><br/>Example like <strong>Mypass_12</strong> i.e Password like Uppercase(Mypass)+symbol(_)+number(12)</div>
	</td>	
	<tr>
		<td align='right'>Password Strength:</td>
		<td id="passwordchk" style="width:140px;">&nbsp;</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" value="Create Cpanel Database" name="cmdCpaneldatabase" title="Create Cpanel Database"></td>
	</tr>
</table>	

</form>
<?php } 
?>
</body>
</html>