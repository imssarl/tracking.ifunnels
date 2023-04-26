	// Javascript code will come here
var LastRowColor = "";
function changeDetailMode(check)
{
if (!check.checked)
{
		document.getElementById("portalsiteftpdetails").style.display = 'block';
		document.getElementById("divhmpagefornewpsite").style.display = 'none';
return true;
}
else
{
		document.getElementById("portalsiteftpdetails").style.display = 'none';
		document.getElementById("divhmpagefornewpsite").style.display = 'block';
return false;
}	
}
function hidepermconf()
{
		document.getElementById("notcheckpermissions").checked = false;
//		document.getElementById("notcheckpermissions").style.display = 'none';
//		document.getElementById("permtext").style.display = 'none';
}

function browseroot(which)
{
//testwindow= window.open ("browse.php?homebox="+which);
if ((document.getElementById("editing") == "undefined" || document.getElementById("editing") == null))
{
	if (document.getElementById("ftpsame").checked == true)
	{
		addr = document.getElementById("same_ftp_address").value;
		user = document.getElementById("same_ftp_username").value;
		pass = document.getElementById("same_ftp_password").value;
		pass=URLEncode('same_ftp_password');
	}
	else
	{
		addr = document.getElementById("ftp_address").value;
		user = document.getElementById("ftp_username").value;
		pass = document.getElementById("ftp_password").value;
		pass=URLEncode('ftp_password');
	}
}
else
{
	addr = document.getElementById("ftp_address").value;
	user = document.getElementById("ftp_username").value;
	pass = document.getElementById("ftp_password").value;
	pass=URLEncode('ftp_password');
}
if (addr.length==0 || user.length == 0 || pass.length == 0)
{
alert("Please enter all FTP details");
return false;
}
//To checkftp connection
           var xmlHttp;
           try
           {
           // Firefox, Opera 8.0+, Safari
           xmlHttp=new XMLHttpRequest();
           }
           catch (e)
           {
           // Internet Explorer
				try{
				xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (e){
					   try{
					   xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
						} 
					   catch (e){
					   alert("Your browser does not support AJAX!");
					   return false;
					   }
				   }
           }
          
           var url = "/checkftp.php";          
           var params = "address="+addr+"&username="+user+"&password="+pass;
           xmlHttp.open("POST", url, true);
           //Send the proper header information along with the request
           xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8"); 
           //xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
           //xmlHttp.setRequestHeader("Content-length", params.length);
           //xmlHttp.setRequestHeader("Connection", "close");
           xmlHttp.send(params);
           xmlHttp.onreadystatechange = function() {//Call a function when the state changes.
			   if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
					result=xmlHttp.responseText;
					if(result=='success'){
						testwindow= window.open ("/browsef.php?dir=&onlyf=yes&oldv=yes&address="+addr+"&username="+user+"&password="+pass+"&homebox="+which, "mywindow" ,"status=0,scrollbars=1,width=400,height=500,resizable=1");
	testwindow.moveTo(50,50);				   
					}else{
					alert("Please check ftp details or retry again.");
					}    
				}
           }


}
function showinfo(id)
{
	openwindow= window.open("wp-wordinfo.php?site_id="+id, "GETCODE","status=0,scrollbars=1,width=650,height=230,resizable=1");
	
	openwindow.moveTo(50,50);
}
function showdiv(no)
{

	var ctrl = document.getElementById("div"+no);
	if (ctrl.style.display=='block')
	{
		ctrl.style.display='none';
		document.getElementById('sign'+no).innerHTML = '+';
		if (LastRowColor=="")
		{
			document.getElementById('row'+no).className = document.getElementById('row'+no).bgColor;
		}
		else
		{
			document.getElementById('row'+no).className = LastRowColor ;
		}
	}
	else
	{
		ctrl.style.display='block';
		document.getElementById('sign'+no).innerHTML = '-';
		LastRowColor = document.getElementById('row'+no).className;
		document.getElementById('row'+no).className = "tablematter3" ;
	}
}
function confirmDelete(id)
{
var ans = "";

ans = confirm("Are you sure to delete this site?");
alert(ans);
	if (ans==true)
	{
		document.location = "sites.php?process=delete&siteid="+id;
	}
	else
	{
		document.location = "sites.php?process=manage&msg=Operation cancelled";
	}
}
function chkMainForm(form)
{


var mess = "";
if (document.getElementById('title').value=="") mess+="- Title can't be blank\n";
if (document.getElementById('description').value=="") mess+="- Description can't be blank\n";
if (document.getElementById('url').value=="") mess+="- Url can't be blank\n";
if (document.getElementById('ftp_address').value=="") mess+="- Ftp address can't be blank\n";
if (document.getElementById('ftp_username').value=="") mess+="- Ftp username can't be blank\n";
if (document.getElementById('ftp_password').value=="") mess+="- Ftp password can't be blank\n";
if (document.getElementById('ftp_homepage1').value=="") mess+="- Ftp homepage can't be blank\n";
/*if (mess.length==0)
if (!(form.ftp_homepage.value.indexOf("/"+form.ftp_username.value+"/") > -1 || form.ftp_homepage.value.indexOf("\\"+form.ftp_username.value+"\\") > -1))
{
mess+="- Ftp username must be a part of FTP homepage URL\n";
}
*/
if (mess.length>0)
{
mess = "The following error:\n"+mess;
alert(mess);
return false;
}
else 
{
form.submit();
return true;
}
}	
function submitotherform()
{
//alert('hello');
/*document.getElementById('headline_spot1').submit();
document.getElementById('saveselection_spot1').submit();
document.getElementById('frmsaveselection_spot1').submit();
document.getElementById('snippet_spot1').submit();
document.getElementById('frmsnippets_spot1').submit();
document.getElementById('saveselection_spot2').submit();
document.getElementById('frmsaveselection_spot2').submit();
document.getElementById('snippet_spot2').submit();
document.getElementById('frmsnippets_spot2').submit();*/

}
function chkSubForm(form)
{

var mess = "";
if (form.url.value=="") mess+="- Url can't be blank\n";
if (!form.ftpsame.checked)
{
	if (form.ftp_address.value=="") mess+="- Ftp address can't be blank\n";
	if (form.ftp_username.value=="") mess+="- Ftp username can't be blank\n";
	if (form.ftp_password.value=="") mess+="- Ftp password can't be blank\n";
	if (form.ftp_homepage.value=="") mess+="- Ftp homepage can't be blank\n";
}

if (mess.length>0)
{
	mess = "The following error:\n"+mess;
	alert(mess);
	return false;
}
else 
{
	return true;
}

}	
