// Code to display window every time broser opens this page language="JavaScript1.2"

var ie=document.all
var dom=document.getElementById
var ns4=document.layers
var ns6=document.getElementById&&!document.all
var calunits=document.layers? "" : "px"

var bouncelimit=40 //(must be divisible by 8)
var direction="up"
var lpos

function initbox(){
if (!dom&&!ie&&!ns4)
return
crossobj=(dom)?document.getElementById("dropin").style : ie? document.all.dropin : document.dropin
scroll_top=(ie)? truebody().scrollTop : window.pageYOffset
crossobj.top=scroll_top-250+calunits
crossobj.visibility=(dom||ie)? "visible" : "show"
dropstart=setInterval("dropin()",50)
}

function dropin(){
scroll_top=(ie)? truebody().scrollTop : window.pageYOffset
//if (parseInt(crossobj.top)<lpos)   // Ravindra 
if (parseInt(crossobj.top)<lpos)
{
//alert (lpos);
crossobj.top=parseInt(crossobj.top)+25+calunits
//alert(crossobj.top);
}
else{
clearInterval(dropstart)
bouncestart=setInterval("bouncein()",50)
//alert(bouncestart);
}
}

function bouncein(){
crossobj.top=parseInt(crossobj.top)-bouncelimit+calunits
if (bouncelimit<0)
bouncelimit+=8
bouncelimit=bouncelimit*-1
if (bouncelimit==0){
clearInterval(bouncestart)
}
}

function dismissbox(){
if (window.bouncestart) clearInterval(bouncestart)
crossobj.visibility="hidden"
}

function truebody(){
return (document.compatMode!="BackCompat")? document.documentElement : document.body
}
function delay(x)
{
	var d=new Date()
	d=d.getSeconds()+x
	if (d>=60) d=d-60
	while (true)
	{
		var d1=new Date()
		if(d==d1.getSeconds())
			break;
	}	
}

function Delaynew()
{
	if (!ns4)
	{	
		document.getElementById("dropin").style.visibility="visible";
			
	}	
	else
		document.dropin.visibility="show";
	
	return;
}


function DropIn(pos,main_bgcolor,main_texttype,main_textsize,main_textcolor,textbar,top,left,bordercolor,borderstyle,borderwidth,sec,showeverytime)
{
	lpos = pos
	ans="no"
	//delay(sec)
	var chkshow=false;
	if (!showeverytime)
	{
		chkshow=true;
		//chkshow=GetCookie('IMCCUSTOMER');
		if (!chkshow)
		{			
			var d = new Date();
			d.setFullYear(d.getFullYear() + 2);
			document.cookie = "popDropin=" + document.lastModified +
					"; expires=" + d.toGMTString();
			SetCooKie('popDropin','added',d);
			//document.vidFrame.location.href='vid_blank.htm';			
		}		
	}
	else
	{
		DeleteCookie('popalert1','','');
	}
	
	if(!chkshow)
	{ 
		if (ns4)
		{
			templayer=document.layers[0]				
			templayer.left=left				
			templayer.top=top
			//templayer.width=width
			//templayer.height=height
		}
		else if (ns6)
		{
			document.getElementById("tbl").style.border = bordercolor+' '+borderwidth+'px '+borderstyle
			document.getElementById("dragtext").innerHTML=textbar
				
			//document.getElementById("dropin").style.height=height +'px'
			//document.getElementById("dropin").style.width =width +'px'
			document.getElementById("dropin").style.left =left +'px'
			document.getElementById("dropin").style.top =top +'px'
			document.getElementById("dragtext").style.backgroundColor=main_bgcolor
			document.getElementById("dragtext").style.color = main_textcolor
			document.getElementById("dragtext").style.fontFamily = main_texttype
			document.getElementById("dragtext").style.fontSize = main_textsize+'px'
		}
		else 
		{
			document.getElementById("tbl").style.border = bordercolor+' '+borderwidth+'px '+borderstyle
			document.getElementById("dragtext").innerHTML=textbar
				
			//document.getElementById("dropin").style.height=height +'px'
			//document.getElementById("dropin").style.width =width +'px'
			document.getElementById("dropin").style.left =left +'px'
			document.getElementById("dropin").style.top =top +'px'
			document.getElementById("dragtext").style.backgroundColor=main_bgcolor
			document.getElementById("dragtext").style.color = main_textcolor
			document.getElementById("dragtext").style.fontFamily = main_texttype
			document.getElementById("dragtext").style.fontSize = main_textsize+'px'
		}
		setTimeout("initbox()",(sec*1000));	
	}
}
//}




//window.onload=initbox