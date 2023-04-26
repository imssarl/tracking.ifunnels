function SetCooKie(name,value)
{
	var argv=SetCooKie.arguments;
	var argc=SetCooKie.arguments.length
	var expires=(argc>2) ? argv[2] : null
	var path=(argc>3) ? argv[3] : null
	var domain=(argc>4) ? argv[4] : null
	var secure=(argc>5) ? argv[5] : false
	document.cookie=name + "=" +escape(value) + 
	((expires==null) ? "" :( ";expires=" + expires.toGMTString())) +
	((path==null) ? "" :( ";path=" + path)) +
	((domain==null) ? "" :( ";domain=" + domain)) +
	((secure==true) ? "; secure " : "")
	
}
function DeleteCookie (name,path,domain) 
{
if (GetCookie(name)) {
		document.cookie = name + "=" +
		((path) ? "; path=" + path : "") +
		((domain) ? "; domain=" + domain : "") +
		"; expires=Thu, 01-Jan-70 00:00:01 GMT";
	}
}

function GetCookie(name)
{	
	var arg=name + "=" 
	var alen=arg.length
	var clen=document.cookie.length
	var i=0,prev=0;next=0;
	var retval=false;
	while(true)
	{
		next=document.cookie.indexOf("; ", prev);
		if(next<=0)
		{
			var chkval=document.cookie.substring(prev,clen);
			var eqpos=chkval.indexOf("=", 0);
			if(eqpos>0)
			{
				if((chkval.substring(0,eqpos+1))==arg)
				{
					retval=true;
					break;
				}	
			}	
			break;				
		}
		if(next>0)
		{
			var chkval=document.cookie.substring(prev,next);
			var eqpos=chkval.indexOf("=", 0);
			if(eqpos>0)
			{
				if((chkval.substring(0,eqpos+1))==arg)
				{
					retval=true;
					break;
				}	
			}
		}
		prev=next+2;	
	}
	return retval;
}


function getCookieVal(offset)
{
	var endstr=document.cookie.indexOf(";",offset)
	if(endstr==-1)
	{
		endstr=document.cookie.length;
	}
	return unescape(document.cookie.substring(offset,endstr));

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