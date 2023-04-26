function hideMessage(divName, timeout)
{
	window.setTimeout('document.getElementById("'+divName+'").innerHTML="&nbsp;";', timeout);
}
function explodeStr(item,delimiter) {
	tempArray=new Array(1);
	var Count=0;
	var tempString=new String(item);
	while (tempString.indexOf(delimiter)>0) {
		tempArray[Count]=tempString.substr(0,tempString.indexOf(delimiter));
		tempString=tempString.substr(tempString.indexOf(delimiter)+delimiter.length,tempString.length-tempString.indexOf(delimiter)+1);
		Count=Count+1
	}
	tempArray[Count]=tempString;
	return tempArray;
}
function delRow(tablename, rowname)
{
	r = document.getElementById(rowname).rowIndex;
	document.getElementById(tablename).deleteRow(r)
}
function addRow(tablename, rowname, all, ih)
{

	var tbl = document.getElementById(tablename);

	var lastRow = tbl.rows.length;

	var row = tbl.insertRow(lastRow);
	
	row.id = rowname;
	
//	alert("no"+lastRow);
	
//	tr.innerHTML = innermatter;
//	alert(tr.innerHTML);
	var cel = new Array();
	var i =0;
	if (all==1)
	{
		
		for (i=0; i<=15; i++)
		{
			cel[i+1] = row.insertCell(i);
		}
	

		for (i=0; i<=15; i++)
		{
			if (i==1) cel[i+1].align = "left";
			else cel[i+1].align = "center";
		}

		for (i=0; i<=15; i++)
		{
			cel[i+1].className = "backcolor1";			
		}
		
		for (i=0; i<=15; i++)
		{
			cel[i+1].innerHTML = ih[i+1];
		}
}
	else if (all==0)
	{
			var cel1 = row.insertCell(0);
			cel1.colSpan = 16;
			cel1.align = "center";
			cel1.innerHTML = ih[17];
	}
	else if (all==2)
	{
			var cel1 = row.insertCell(0);
			cel1.colSpan = 16;
			cel1.align = "center";
			cel1.innerHTML = ih[2];
	}
}
function trim(value)

{

	var temp = value;

	var obj = /^(\s*)([\W\w]*)(\b\s*$)/;

	if (obj.test(temp)) { temp = temp.replace(obj, '$2'); }

	var obj = /[\n ]+/g;

	temp = temp.replace(obj, " ");

	if (temp == " ") { temp = ""; }

	return temp;

}

function isValidNumber(value)
{
  var obj=/^[1-9]+$/;
  var val;
  val =  obj.test(value);
  return val;
}


function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*60*1000)); // days*24*60*60*1000
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function getCookieArray(name) {
	var nameEQ = name;
	var cary = new Array();
	var ca = document.cookie.split(';');
	var j=0;
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];

		while (c.charAt(0)==' ') c = c.substring(1,c.length);

		if (c.indexOf(nameEQ) == 0)
		{
			var v = c.split('=');
			if (v[1] > 0)
				cary[j++] =  v[1];
		}
	}
	return cary;
}

function unsetCookie(name) {
	var nameEQ = name;
	var cary = new Array();
	var ca = document.cookie.split(';');
	var j=0;
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];

		while (c.charAt(0)==' ') c = c.substring(1,c.length);

		if (c.indexOf(nameEQ) == 0)
		{
			var v = c.split('=');
			if (v[1] > 0)
				eraseCookie(v[0]);
		}
	}
	return cary;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

