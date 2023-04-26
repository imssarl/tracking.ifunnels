function displayftpserver()
{

if(document.getElementById("ftpserveroption").value=="new_ftp")	{	


	document.getElementById('ftp_address').value= "";
	document.getElementById('ftp_username').value= "";
	document.getElementById('ftp_password').value= "";
	
	document.getElementById('ftp_address').readOnly=false;
	document.getElementById('ftp_username').readOnly=false;
	document.getElementById('ftp_password').readOnly=false;
}
else if(document.getElementById("ftpserveroption").value=="" || document.getElementById("ftpserveroption").value!="new_ftp")	{
	
	var temp = new Array();
	str=document.getElementById("ftpserveroption").value;
	temp=str.split(' ');
	
	document.getElementById('ftp_address').readOnly=true;
	document.getElementById('ftp_username').readOnly=true;
	document.getElementById('ftp_password').readOnly=true;
	
	document.getElementById('ftp_address').value= temp[0];
	document.getElementById('ftp_username').value= temp[1];
	document.getElementById('ftp_password').value= temp[2];
}
}
