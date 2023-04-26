<?php	
session_start();	
chdir( '../' );
include("config/config.php");
chdir( dirname(__FILE__) );
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-us" />
<title>CNM:Add-ons</title>
<head></head>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function trim(b)
{
	var i=0;
	while(b.charAt(i)==" ")
	{
	i++;
	}
	b=b.substring(i,b.length);
	len=b.length-1;
	while(b.charAt(len)==" "){
	len--;
	}
	b=b.substring(0,len+1);
	return b;
}
function alphanumeric(alphane)
{
	var numaric = alphane;
	for(var j=0; j<numaric.length; j++)
		{
			 var alphaa = numaric.charAt(j);
			var hh = alphaa.charCodeAt(0);
			if((hh > 47 && hh<58) || (hh > 64 && hh<91) || (hh > 96 && hh<123) || (hh==32))
			{
			}
			else	{
			return false;
			}
		}
	return true;
}

function isEmpty(s)
{ 
	  s=trim(s);
	  return ((s == null) || (s.length == 0))
}
	  
function isValidDomain(url){ 
		var arr = new Array('.com','.net','.org','.biz','.coop','.info','.museum','.name','.pro','.edu','.gov','.int','.mil','.ac','.ad','.ae','.af','.ag','.ai','.al','.am','.an','.ao','.aq','.ar','.as','.at','.au','.aw','.az','.ba','.bb','.bd','.be','.bf','.bg','.bh','.bi','.bj','.bm','.bn','.bo','.br','.bs','.bt','.bv','.bw','.by','.bz','.ca','.cc','.cd','.cf','.cg','.ch','.ci','.ck','.cl','.cm','.cn','.co','.cr','.cu','.cv','.cx','.cy','.cz','.de','.dj','.dk','.dm','.do','.dz','.ec','.ee','.eg','.eh','.er','.es','.et','.fi','.fj','.fk','.fm','.fo','.fr','.ga','.gd','.ge','.gf','.gg','.gh','.gi','.gl','.gm','.gn','.gp','.gq','.gr','.gs','.gt','.gu','.gv','.gy','.hk','.hm','.hn','.hr','.ht','.hu','.id','.ie','.il','.im','.in','.io','.iq','.ir','.is','.it','.je','.jm','.jo','.jp','.ke','.kg','.kh','.ki','.km','.kn','.kp','.kr','.kw','.ky','.kz','.la','.lb','.lc','.li','.lk','.lr','.ls','.lt','.lu','.lv','.ly','.ma','.mc','.md','.mg', '.mh','.mk','.ml','.mm','.mn','.mo','.mp','.mq','.mr','.ms','.mt', '.mu','.mv','.mw','.mx','.my','.mz','.na','.nc','.ne','.nf','.ng', '.ni','.nl','.no','.np','.nr','.nu','.nz','.om','.pa','.pe','.pf', '.pg','.ph','.pk','.pl','.pm','.pn','.pr','.ps','.pt','.pw','.py', '.qa','.re','.ro','.rw','.ru','.sa','.sb','.sc','.sd','.se','.sg', '.sh','.si','.sj','.sk','.sl','.sm','.sn','.so','.sr','.st','.sv', '.sy','.sz','.tc','.td','.tf','.tg','.th','.tj','.tk','.tm','.tn', '.to','.tp','.tr','.tt','.tv','.tw','.tz','.ua','.ug','.uk','.um', '.us','.uy','.uz','.va','.vc','.ve','.vg','.vi','.vn','.vu','.ws', '.wf','.ye','.yt','.yu','.za','.zm','.zw');  
		var mai = url; 
		var val = true;  
		var dot = mai.lastIndexOf("."); 
		var dname = mai.substring(0,dot); 
		var ext = mai.substring(dot,mai.length);
		if(dot>=0) {  
			for(var i=0; i<arr.length; i++)  {    
				if(ext == arr[i])    {    
					val = true;   break;    
				}else{
					val = false;    
				}  
			}  
			if(val == false){      
				return false;  
			}else {   
		        return true; 
	  		}
	  }else{
			return false;
	  }
} 


function Validateform()
{
	var hostname,username,password,newdomain,newusername,newpassword;
	hostname=document.getElementById('hostname');
	username=document.getElementById('username');
	password=document.getElementById('password');
	newdomain=document.getElementById('newdomain');
	newusername=document.getElementById('newusername');
	newpassword=document.getElementById('newpassword');
	
	if(isEmpty(hostname.value))
	{
		alert("Please enter hostname");
		hostname.focus();
		return false;
	}
	if(isEmpty(username.value))
	{
		alert("Please enter user name");
		username.focus();
		return false;
	}
	if(isEmpty(password.value))
	{
		alert("Please enter password");
		password.focus();
		return false;
	}
	if(isEmpty(newdomain.value))
	{
		alert("Please enter new domain");
		newdomain.focus();
		return false;
	}
	if(isValidDomain(newdomain.value)==false)
	{
		alert("Please enter valid domain name.");
		hostname.focus();
		return false;
	}
	if(isEmpty(newusername.value))
	{
		alert("Please enter new user name");
		newusername.focus();
		return false;
	}
	if(isEmpty(newpassword.value))
	{
		alert("Please enter new password");
		newpassword.focus();
		return false;
	}
	return true;
	
}
</script>
<?php
if($_POST['Submit']) 
{	
	$hostname = strip_tags($_POST["hostname"]);		
	$username = strip_tags($_POST["username"]);	
	$password = $_POST['password'];	
	$newdomain = strip_tags($_POST["newdomain"]);	
	$newusername = strip_tags($_POST["newusername"]);	
	$newpassword = $_POST['newpassword'];	
	$newdomain2 = urlencode($newdomain);	
	$newusername2 = urlencode($newusername);	
	$newpassword2 = urlencode($newpassword2);	
	$portnum = "2082";	
	include('sample_addon.php');	
	if($error)		
	
	form();
	}
	else	
	form();
	function form() 
	{	
		echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\" onsubmit=\"return Validateform()\">";	

	
	echo "<div style=\"center\"><table align=\"center\" width=\"380\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\" class=\"blue_brd\" bgcolor=\"#FFFFFF\">";

	echo "<tr class=\"blue_tab\"><td colspan=\"2\"><b>Addon Domain info</b></td></tr>";
	echo "<tr><td colspan=\"3\" align='center'><b>Cpanel info</b></td></tr>";
	echo "<tr><td align='right'>Hostname:</td><td><input type=\"text\" name=\"hostname\" id=\"hostname\" maxlength=\"250\"><img src=\"help.jpg\" title=\"Hostname (eg. qjmp.com)\"/></td></tr>";


	echo "<tr><td align='right'>Username:</td><td><input type=\"text\" name=\"username\" id=\"username\" maxlength=\"100\"><img src=\"help.jpg\" title=\"Cpanel username \"/></td></tr>";


	echo "<tr><td align='right'>Password:</td><td><input type=\"password\" name=\"password\" id=\"password\" ><img src=\"help.jpg\" title=\"Cpanel password \"/></td></tr>";
	
	echo "<tr><td align='right'>cPanel Theme / Skin:</td><td><select name='cpanelversion' id='cpanelversion' onchange='javascript:Othertheme();'><option value='x'>x</option><option value='x2'>x2</option><option value='x3'>x3</option><option value='other'>other</option></select><a href='#' onclick=\"javascript:showhelp();\"><img border='0' src=\"help.jpg\" title=\"Click here to know how to determine Cpanel theme /skin.\"/></a>&nbsp;<input style='display:none;' type='text' maxlength='50' id='othertheme' name='othertheme' size='8'/></td></tr>";
	
	
	
	echo '<tr><td align="left" colspan="2"><strong><span style="color:#FF0000">Note:</span></strong> Please Check your cpanel theme/skin before select.The script will not work if wrong cPanel theme is selected. Usually cPanel skin name would be "x", but yours may be different.<br/><br />';
	
	echo"<div id='help' style='display:none;'><strong>Try following steps if you do not know what your current cPanel theme is.</strong> 
	<ul>
	  <li>Login to your cPanel account</li>
	  <li>Look at the URL in your browser. It would look somewhat similar to <strong>http://www.hosting.com:2082/frontend/x/index.html</strong></li>
	  <li>cPanel  theme name is everything after the &quot;/frontend/&quot;, and before the next  slash &quot;/&quot;. In above example cPanel theme is &quot;x&quot;. It could be &quot;x2&quot;,  &quot;rvblue&quot;, etc.</li>
	</ul></div></td></tr>";

	echo "<tr><td colspan=\"3\">&nbsp;</td></tr>";

	echo "<tr><td colspan=\"3\" align='center'><b>New Addons:</b></td></tr>";

	echo "<tr><td align='right'>New Domain:</td><td><input type=\"text\" name=\"newdomain\" id=\"newdomain\" maxlength=\"250\"><img src=\"help.jpg\" title=\"Domain Name (Do not put any http:// or www)\"/></td></tr>";	
	
	echo "<tr><td colspan=\"3\" align='center'><b>Username/directory/subdomain</b></td></tr>";
	
	echo "<tr><td align='right'>Subdomain:</td><td><input type=\"text\" name=\"newusername\" id=\"newusername\" maxlength=\"50\"><img src=\"help.jpg\" title=\"New Username\"/></td></tr>";
	echo "<tr><td align='right'>Password</td><td><input type=\"password\" name=\"newpassword\" id=\"newpassword\"><img src=\"help.jpg\" maxlength=\"12\" title=\"New Password\"/></td></tr>";	

	echo "<tr><td colspan=\"3\">&nbsp;</td></tr>";

	echo "<tr><td colspan=\"3\" align='center'><input type=\"submit\" value=\"Submit\" name=\"Submit\"></td></tr>";

	echo "</tabel></div></form>";
	}
?>
		
</body>

</html>