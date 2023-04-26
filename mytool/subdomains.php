<html>

<head>

<meta http-equiv="Content-Language" content="en-us" />

<title>CNM:Subdomains</title>

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

		else{

		return false;

		}

	}

	return true;

}

function isName(s){

	s=trim(s);

	return isCharsInBag (s, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-");

}



function isSpecial(s){

	s=trim(s);

	return isCharsInBag (s, "!@#$%^&*()':;?");

}



function isNumeric(s){

	s=trim(s);

	return isCharsInBag (s, "0123456789");

}

function isEmpty(s)

{ 

	  s=trim(s);

	  return ((s == null) || (s.length == 0))

}

function isValidURL(url){ 

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

	var hostname,username,password,root;

	hostname=document.getElementById('hostname');

	username=document.getElementById('username');

	password=document.getElementById('password');

	root=document.getElementById('root');
	
	//alert(document.getElementById('subdomains0').value);
	subdomains =Array();
	for(i=0;i<10;i++)
		subdomains[i]=document.getElementById('subdomains'+i);
	
		
	

	if(isEmpty(hostname.value))

	{

		alert("Please enter hostname");

		hostname.focus();

		return false;

	}

	if(isValidURL(document.getElementById('hostname').value)==false){

			alert("Please enter valid hostname domain.");

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

		alert("Please enter control panel user password");

		password.focus();

		return false;

	}

	if(isEmpty(root.value))

	{

		alert("Please enter root domain");

		root.focus();

		return false;

	}

	if(isValidURL(document.getElementById('root').value)==false){

			alert("Please enter valid root domain.");

			root.focus();

			return false;

		}



	if(isEmpty(subdomains[0].value || subdomains[1].value || subdomains[2].value || subdomains[3].value || subdomains[4].value || subdomains[5].value || subdomains[6].value || subdomains[7].value || subdomains[8].value || subdomains[9].value))

	{

		alert("Please enter subdomain");

		subdomains[0].focus();

		return false;
	
	}

	if(!alphanumeric(subdomains[0].value || subdomains[1].value || subdomains[2].value || subdomains[3].value || subdomains[4].value || subdomains[5].value || subdomains[6].value || subdomains[7].value || subdomains[8].value || subdomains[9].value))

	{

		alert("Please enter valid subdomain");

		subdomains[0].focus();

		return false;

	}

	return true;

	

}

function parentfill1()

{

	/*var str;

	if(document.getElementById('subdomains').value!='' && document.getElementById('hostname').value!='')

	{

		str=document.getElementById('subdomains').value +"."+ document.getElementById('hostname').value;

		opener.document.getElementById('blog_url').value="http://"+str;

	}*/

	

	window.close();

}

</script>



<?php

if(isset($_POST['Submit'])) {



	include("Sample_subdomain.php");



	//$subdomain=strtok($subdomains,"\n ");

	/*if($_POST["cpanelversion"]=="x3")

	{	

		include("Sample_x3subdomain.php");

		

	}

	else 

	{

		include("Sample_xsubdomain.php");

		

	}*/

}

else

	form();

	

?>

<body>	

<?php

function form() {



	echo "<form action=\"\" method=\"post\" onsubmit=\"return Validateform()\">";





	echo "<div style=\"center\"><table align=\"center\" width=\"380\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\" class=\"blue_brd\" bgcolor=\"#FFFFFF\">";

	

echo "<tr class=\"blue_tab\"><td colspan=\"2\"><b>Subdomain info</b></td></tr>";

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





	echo "<tr><td colspan=\"3\" align='center'><b>Main domain</b></td></tr>";





	echo "<tr><td align='right'>root domain:</td><td><input type=\"text\" name=\"root\" id=\"root\" maxlength=\"250\"><img src=\"help.jpg\" title=\"Main domain name(example:mysite.com)\"/></td></tr>";





	echo "<tr><td colspan=\"2\" align='center'><b>Subdomain</b></td></tr>";




	for($i=0;$i<10;$i++)
	{
		echo "<tr><td align='right'> sub domain".($i+1)." :</td><td><input type=\"text\" name=\"subdomains[".$i."]\" id=\"subdomains".$i."\" maxlength=\"50\"><img src=\"help.jpg\" title=\"please enter subdomain without www \"/></td></tr>";
	}
	

	echo "<tr><td align='right'></td><td><font color='red'><small>(please enter subdomain without www)</small></font></td></tr>";



	echo "<tr><td colspan=\"3\">&nbsp;</td></tr>";





	echo "<tr><td colspan=\"3\" align='center'><input type=\"submit\" value=\"Submit\" name=\"Submit\"></td></tr>";





	echo "</table></div></form>";





}

?>

</body>



</html>