function show_div(types,spot){
	if(types=='damscode'){
		if(document.getElementById('damscode_'+spot).checked==true){
			document.getElementById('damscodes_'+spot).style.display="block";
			document.getElementById('headline_processing_spot1').style.display="none";
		}else{
			document.getElementById('damscodes_'+spot).style.display="none";			 
			document.getElementById('headline_processing_spot1').style.display="none";	
		}
	}
	else if(types=="chkcustomer_code"){
		if(document.getElementById('chkcustomer_code_'+spot).checked==true){								
		document.getElementById('div_customercode_'+spot).style.display="block";																
		}else if(document.getElementById('chkcustomer_code_'+spot).checked==false){																
		document.getElementById('div_customercode_'+spot).style.display="none";									
		}
	}							
}
function get_check_value(theElement)
{
var c_value = "";

	if(theElement.length!=undefined)
	{

	for (var i=0; i < theElement.length; i++)
	   {
		if (theElement[i].checked)
		{
		c_value = c_value +i+",";
		}
	   }
	}
	else
	{
		c_value='0'+",";
	}
   return c_value;
}
function set_checked(theElement,id,checkvalue)
{
if(checkvalue!=undefined){

var checklist = checkvalue.split(",");
	
	if(theElement.length!=undefined){
		for(var i = 0; i < (theElement.length-1); i++){	
			if(checklist[i] != '')	{		
			theElement[checklist[i]].checked=true;
			}
		}
		
	}else
	{		
		if(checklist[0]=='0'){		
		document.getElementById(id).checked=true;
		}
	}

}
	
}								

function shuffel(no,spot,theElement){
	
	var a=document.getElementById("one_"+spot).innerHTML;
	
	var b=document.getElementById("two_"+spot).innerHTML;
	
	var c=document.getElementById("three_"+spot).innerHTML;
	
	var saveselection_data=document.getElementById("txtsaveselection_"+spot).value;
	var snippet_data=document.getElementById("snippetscodetext_"+spot).value;
	var customer_data=document.getElementById("customercode_"+spot).value;
	
	var chksnippets=document.getElementById("chksnippets_"+spot).checked;
	var chkcustomer_code=document.getElementById("chkcustomer_code_"+spot).checked;
	var chkcontents=document.getElementById("chkcontents_"+spot).checked;
	//alert(document.frmsaveselection_spot1.chksaveselect_spot1);
	/*if(spot=='spot1' && document.frmsaveselection_spot1.chksaveselect_spot1!=undefined){ 								
	var chk_saveselection_data=get_check_value(document.frmsaveselection_spot1.chksaveselect_spot1);
	}
	if(spot=='spot1' && document.frmsnippets_spot1.chksnippetsselect_spot1!=undefined){ 								
	var chk_snippetsselect_data=get_check_value(document.frmsnippets_spot1.chksnippetsselect_spot1);
	}
	
	if(spot=='spot2' && document.frmsaveselection_spot2.chksaveselect_spot2!=undefined){ 								
	var chk_saveselection_data=get_check_value(document.frmsaveselection_spot2.chksaveselect_spot2);
	}
	if(spot=='spot2' && document.frmsnippets_spot2.chksnippetsselect_spot2!=undefined){ 								
	var chk_snippetsselect_data=get_check_value(document.frmsnippets_spot2.chksnippetsselect_spot2);
	}
	
	if(spot=='spot3' && document.frmsaveselection_spot3.chksaveselect_spot3!=undefined){ 								
	var chk_saveselection_data=get_check_value(document.frmsaveselection_spot3.chksaveselect_spot3);
	}
	if(spot=='spot3' && document.frmsnippets_spot3.chksnippetsselect_spot3!=undefined){ 								
	var chk_snippetsselect_data=get_check_value(document.frmsnippets_spot3.chksnippetsselect_spot3);
	}	*/

	if(no==1 || no==2){
	
		document.getElementById("one_"+spot).innerHTML=b;
		
		document.getElementById("two_"+spot).innerHTML=a;
		
		document.getElementById("three_"+spot).innerHTML=c;
	
	}
	else if(no==3 || no==4){
	
		document.getElementById("one_"+spot).innerHTML=a;
		
		document.getElementById("two_"+spot).innerHTML=c;
		
		document.getElementById("three_"+spot).innerHTML=b;
	
	}
	
	document.getElementById("txtsaveselection_"+spot).value=saveselection_data;
	document.getElementById("snippetscodetext_"+spot).value=snippet_data;
	document.getElementById("customercode_"+spot).value=customer_data;
	
	document.getElementById("chksnippets_"+spot).checked=chksnippets;
	document.getElementById("chkcustomer_code_"+spot).checked=chkcustomer_code;
	document.getElementById("chkcontents_"+spot).checked=chkcontents;
	
	
	/*if(spot=='spot1'){ 	
	
	set_checked(document.frmsaveselection_spot1.chksaveselect_spot1,'chksaveselect_spot1',chk_saveselection_data);
	set_checked(document.frmsnippets_spot1.chksnippetsselect_spot1,'chksnippetsselect_spot1',chk_snippetsselect_data);
	}
	if(spot=='spot2'){ 	
	
	set_checked(document.frmsaveselection_spot2.chksaveselect_spot2,'chksaveselect_spot2',chk_saveselection_data);
	set_checked(document.frmsnippets_spot2.chksnippetsselect_spot2,'chksnippetsselect_spot2',chk_snippetsselect_data);
	}
	if(spot=='spot3'){ 	
	
	set_checked(document.frmsaveselection_spot3.chksaveselect_spot3,'chksaveselect_spot3',chk_saveselection_data);
	set_checked(document.frmsnippets_spot3.chksnippetsselect_spot3,'chksnippetsselect_spot3',chk_snippetsselect_data);
	}*/
	
	
}
   var http_request = false;
   function makePOSTRequest(url,parameters,paramtype) {
	  http_request = false;
	  if (window.XMLHttpRequest) { // Mozilla, Safari,...
		 http_request = new XMLHttpRequest();
		 if (http_request.overrideMimeType) {
			// set type accordingly to anticipated content type
			//http_request.overrideMimeType('text/xml');
			http_request.overrideMimeType('text/html');
		 }
	  } else if (window.ActiveXObject) { // IE
		 try {
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		 } catch (e) {
			try {
			   http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		 }
	  }
	  if (!http_request) {
		 alert('Cannot create XMLHTTP instance');
		 return false;
	  }
  if(paramtype=='get_headlines_spot1'){ http_request.onreadystatechange = headlinesContentsSpot1;}
  else if(paramtype=='get_snippets_spot1'){ http_request.onreadystatechange = snippetsContentsSpot1;}
  else if(paramtype=='get_saveselection_spot1'){ http_request.onreadystatechange = saveselectionContentsSpot1;}
   else if(paramtype=='get_snippets_spot2'){ http_request.onreadystatechange = snippetsContentsSpot2;}
  else if(paramtype=='get_saveselection_spot2'){ http_request.onreadystatechange = saveselectionContentsSpot2;}
  else if(paramtype=='get_snippets_spot3'){ http_request.onreadystatechange = snippetsContentsSpot3;}
  else if(paramtype=='get_saveselection_spot3'){ http_request.onreadystatechange = saveselectionContentsSpot3;}
  
else if(paramtype=='get_snippets_spot4'){ http_request.onreadystatechange = snippetsContentsSpot4;}
else if(paramtype=='get_saveselection_spot4'){ http_request.onreadystatechange = saveselectionContentsSpot4;}
	
else if(paramtype=='get_snippets_spot5'){ http_request.onreadystatechange = snippetsContentsSpot5;}
else if(paramtype=='get_saveselection_spot5'){ http_request.onreadystatechange = saveselectionContentsSpot5;}

else if(paramtype=='get_snippets_spot6'){ http_request.onreadystatechange = snippetsContentsSpot6;}
else if(paramtype=='get_saveselection_spot6'){ http_request.onreadystatechange = saveselectionContentsSpot6;}
	
else if(paramtype=='get_snippets_spot7'){ http_request.onreadystatechange = snippetsContentsSpot7;}
else if(paramtype=='get_saveselection_spot7'){ http_request.onreadystatechange = saveselectionContentsSpot7;}

else if(paramtype=='get_snippets_spot8'){ http_request.onreadystatechange = snippetsContentsSpot8;}
else if(paramtype=='get_saveselection_spot8'){ http_request.onreadystatechange = saveselectionContentsSpot8;}
	
else if(paramtype=='get_snippets_spot9'){ http_request.onreadystatechange = snippetsContentsSpot9;}
else if(paramtype=='get_saveselection_spot9'){ http_request.onreadystatechange = saveselectionContentsSpot9;}
	
	else if(paramtype=='get_snippets_spot10'){ http_request.onreadystatechange = snippetsContentsSpot10;}
else if(paramtype=='get_saveselection_spot10'){ http_request.onreadystatechange = saveselectionContentsSpot10;}

	  http_request.open('POST', url, true);
	  http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	  http_request.setRequestHeader("Content-length", parameters.length);
	  http_request.setRequestHeader("Connection", "close");
	  http_request.send(parameters);
   }

   function headlinesContentsSpot1() {
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {
			//alert(http_request.responseText);
			result = http_request.responseText;
			document.getElementById('headline_processing_spot1').style.display="none";										
			document.getElementById('get_headline_spot1').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		  document.getElementById('dmascodetext').value="";
		document.getElementById('headline_processing_spot1').style.display="block";
	  }
	  
   }
	function saveselectionContentsSpot1() {																		
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {
			result = http_request.responseText;	
			document.getElementById('save_processing_spot1').style.display="none";									
			document.getElementById('get_saveselections_spot1').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		document.getElementById('txtsaveselection_spot1').value='';
		document.getElementById('save_processing_spot1').style.display="block";
	  }
	  
   }
   
   function snippetsContentsSpot1() {
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {	
			result = http_request.responseText;										
			document.getElementById('snippets_processing_spot1').style.display="none";										
			document.getElementById('get_snippets_spot1').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		   document.getElementById('snippetscodetext_spot1').value='';
		document.getElementById('snippets_processing_spot1').style.display="block";
	  }
	  
   }
	function saveselectionContentsSpot2() {																		
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {
			result = http_request.responseText;	
			document.getElementById('save_processing_spot2').style.display="none";									
			document.getElementById('get_saveselections_spot2').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		  document.getElementById('txtsaveselection_spot2').value='';
		document.getElementById('save_processing_spot2').style.display="block";
	  }
	  
   }
   
   function snippetsContentsSpot2() {
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {	
			result = http_request.responseText;										
			document.getElementById('snippets_processing_spot2').style.display="none";										
			document.getElementById('get_snippets_spot2').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		//document.getElementById('snippetscodetext_spot2').value='';
		document.getElementById('snippets_processing_spot2').style.display="block";
	  }
	  
   }
	function saveselectionContentsSpot3() {																		
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {
			result = http_request.responseText;	
			document.getElementById('save_processing_spot3').style.display="none";									
			document.getElementById('get_saveselections_spot3').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		  document.getElementById('txtsaveselection_spot3').value='';
		document.getElementById('save_processing_spot3').style.display="block";
	  }
	  
   }
   
   function snippetsContentsSpot3() {
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {	
			result = http_request.responseText;										
			document.getElementById('snippets_processing_spot3').style.display="none";										
			document.getElementById('get_snippets_spot3').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		   document.getElementById('snippetscodetext_spot3').value='';
		document.getElementById('snippets_processing_spot3').style.display="block";
	  }
	  
   }
   
   function saveselectionContentsSpot4() {																		
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {
			result = http_request.responseText;	
			document.getElementById('save_processing_spot4').style.display="none";									
			document.getElementById('get_saveselections_spot4').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		  document.getElementById('txtsaveselection_spot4').value='';
		document.getElementById('save_processing_spot4').style.display="block";
	  }
	  
   }
   
   function snippetsContentsSpot4() {
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {	
			result = http_request.responseText;										
			document.getElementById('snippets_processing_spot4').style.display="none";										
			document.getElementById('get_snippets_spot4').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		   document.getElementById('snippetscodetext_spot4').value='';
		document.getElementById('snippets_processing_spot4').style.display="block";
	  }
	  
   }
   
    function saveselectionContentsSpot5() {																		
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {
			result = http_request.responseText;	
			document.getElementById('save_processing_spot5').style.display="none";									
			document.getElementById('get_saveselections_spot5').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		  document.getElementById('txtsaveselection_spot5').value='';
		document.getElementById('save_processing_spot5').style.display="block";
	  }
	  
   }
   
   function snippetsContentsSpot5() {
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {	
			result = http_request.responseText;										
			document.getElementById('snippets_processing_spot5').style.display="none";										
			document.getElementById('get_snippets_spot5').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		   document.getElementById('snippetscodetext_spot5').value='';
		document.getElementById('snippets_processing_spot5').style.display="block";
	  }
	  
   }
   
   
    function saveselectionContentsSpot6() {																		
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {
			result = http_request.responseText;	
			document.getElementById('save_processing_spot6').style.display="none";									
			document.getElementById('get_saveselections_spot6').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		  document.getElementById('txtsaveselection_spot6').value='';
		document.getElementById('save_processing_spot6').style.display="block";
	  }
	  
   }
   
   function snippetsContentsSpot6() {
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {	
			result = http_request.responseText;										
			document.getElementById('snippets_processing_spot6').style.display="none";										
			document.getElementById('get_snippets_spot6').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		   document.getElementById('snippetscodetext_spot6').value='';
		document.getElementById('snippets_processing_spot6').style.display="block";
	  }
	  
   }
   
  function saveselectionContentsSpot7() {																		
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {
			result = http_request.responseText;	
			document.getElementById('save_processing_spot7').style.display="none";									
			document.getElementById('get_saveselections_spot7').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		  document.getElementById('txtsaveselection_spot7').value='';
		document.getElementById('save_processing_spot7').style.display="block";
	  }
	  
   }
   
   function snippetsContentsSpot7() {
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {	
			result = http_request.responseText;										
			document.getElementById('snippets_processing_spot7').style.display="none";										
			document.getElementById('get_snippets_spot7').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		   document.getElementById('snippetscodetext_spot7').value='';
		document.getElementById('snippets_processing_spot7').style.display="block";
	  }
	  
   } 
   
    function saveselectionContentsSpot8() {																		
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {
			result = http_request.responseText;	
			document.getElementById('save_processing_spot8').style.display="none";									
			document.getElementById('get_saveselections_spot8').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		  document.getElementById('txtsaveselection_spot8').value='';
		document.getElementById('save_processing_spot8').style.display="block";
	  }
	  
   }
   
   function snippetsContentsSpot8() {
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {	
			result = http_request.responseText;										
			document.getElementById('snippets_processing_spot8').style.display="none";										
			document.getElementById('get_snippets_spot8').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		   document.getElementById('snippetscodetext_spot8').value='';
		document.getElementById('snippets_processing_spot8').style.display="block";
	  }
	  
   } 
   
   function saveselectionContentsSpot9() {																		
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {
			result = http_request.responseText;	
			document.getElementById('save_processing_spot9').style.display="none";									
			document.getElementById('get_saveselections_spot9').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		  document.getElementById('txtsaveselection_spot9').value='';
		document.getElementById('save_processing_spot9').style.display="block";
	  }
	  
   }
   
   function snippetsContentsSpot9() {
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {	
			result = http_request.responseText;										
			document.getElementById('snippets_processing_spot9').style.display="none";										
			document.getElementById('get_snippets_spot9').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		   document.getElementById('snippetscodetext_spot9').value='';
		document.getElementById('snippets_processing_spot9').style.display="block";
	  }
	  
   } 
   function saveselectionContentsSpot10() {																		
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {
			result = http_request.responseText;	
			document.getElementById('save_processing_spot10').style.display="none";									
			document.getElementById('get_saveselections_spot10').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		  document.getElementById('txtsaveselection_spot10').value='';
		document.getElementById('save_processing_spot10').style.display="block";
	  }
	  
   }
   
   function snippetsContentsSpot10() {
	  if (http_request.readyState == 4) {
		 if (http_request.status == 200) {	
			result = http_request.responseText;										
			document.getElementById('snippets_processing_spot10').style.display="none";										
			document.getElementById('get_snippets_spot10').innerHTML = result;            
		 } else {
		 
			alert('There was a problem with the request.');										
		 }
	  }else{
		   document.getElementById('snippetscodetext_spot10').value='';
		document.getElementById('snippets_processing_spot10').style.display="block";
	  }
	  
   } 
   
   
   function get_headlines_spot1(spot, ids) {
	  var poststr = "process=" + encodeURI( document.getElementById("headlines_spot1").value )+
	   "&spot="+spot;
	   if (ids)
	   {
	   	poststr = poststr + '&ids=' + ids;
	   }
	   
	   
	  
	  makePOSTRequest('dams/getcampaign.php', poststr,'get_headlines_spot1');
   }
  function get_saveselection(spot, ids) {
	  var poststr = "process=manage&spot="+spot;
	  if (ids)
	  {
	  	poststr = poststr + '&ids=' + ids;
	  }
	  
	  if(document.getElementById('chkcontents_'+spot).checked==false){
	  	document.getElementById('txtsaveselection_'+spot).value='';
	  	document.getElementById('get_saveselections_'+spot).style.display='none';
	  }
	  else
	  {
	  	document.getElementById('get_saveselections_'+spot).innerHtml = ' ';
	  	makePOSTRequest('getsaveselection.php', poststr,'get_saveselection_'+spot);
	  	document.getElementById('get_saveselections_'+spot).style.display='block';
	  }
	  
   }
	function get_snippets(spot, ids) {
	  var poststr = "process=manage&spot="+spot;
	  if (ids)
	  {
	  	poststr = poststr + '&ids=' + ids;
	  }
	  if(document.getElementById('chksnippets_'+spot).checked==false){
	  	document.getElementById('snippetscodetext_'+spot).value='';
	  	document.getElementById('get_snippets_'+spot).style.display='none';
	  }
	  else
	  {
	  	document.getElementById('get_snippets_'+spot).innerHtml=' ';
	  	makePOSTRequest('getsnippets.php', poststr,'get_snippets_'+spot);
	  	document.getElementById('get_snippets_'+spot).style.display='block';
	  }
   }
function show_spotdiv(id){
	if(document.getElementById(id).checked==true){
	document.getElementById('div_'+id).style.display="block";
	}else{
	document.getElementById('div_'+id).style.display="none";
	}								
}
function show_replaceby(id){
	if(document.getElementById(id+'_customze').checked==true){
	document.getElementById('replace_'+id).style.display="block";
	document.getElementById('save_processing_'+id).style.display="none";
	document.getElementById('snippets_processing_'+id).style.display="none";								
	}else{
	document.getElementById('replace_'+id).style.display="none";
	document.getElementById('save_processing_'+id).style.display="none";
	document.getElementById('snippets_processing_'+id).style.display="none";
	}	
}
function show_default(id){
	if(document.getElementById(id+'_default').checked==true){
	document.getElementById('replace_'+id).style.display="none";
	document.getElementById('save_processing_'+id).style.display="none";
	document.getElementById('snippets_processing_'+id).style.display="none";								
	}else{
	document.getElementById('replace_'+id).style.display="block";
	document.getElementById('save_processing_'+id).style.display="block";
	document.getElementById('snippets_processing_'+id).style.display="block";
	}	
}
