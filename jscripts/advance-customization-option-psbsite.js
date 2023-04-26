function hidediv(){
	 document.getElementById("wizard").style.display = 'none';
}
/*start content wizard script*/
var ch_art=new Array();
function IsNumeric(strString)

   //  check for valid numeric strings	

   {

   var strValidChars = "0123456789.-";

   var strChar;

   var blnResult = true;



   if (strString.length == 0) return false;



   //  test strString consists of valid characters listed above

   for (i = 0; i < strString.length && blnResult == true; i++)

      {

      strChar = strString.charAt(i);

      if (strValidChars.indexOf(strChar) == -1)

         {

         blnResult = false;

         }

      }

   return blnResult;

   }
function rand_no(theElement)

	{

		var tForm = theElement.form, z = 0;

		///alert(theElement.form);  

		var txt=document.getElementById("art").value;

		var countChk=0;

		var eleNum=0;

		var startAr=false;

		for(z=0;z<tForm.length;z++)

		{

			if(tForm[z].type == 'checkbox' && tForm[z].name != 'chkall' && tForm[z].name != 'damscode_spot1' && tForm[z].name != 'spot1'&&tForm[z].name != 'spot2' && tForm[z].name != 'spot3' && tForm[z].name!='chksnippetsall_spot1' && tForm[z].name!='chksnippetsall_spot2' && tForm[z].name!='chksnippetsall_spot3' && tForm[z].name!='chksnippetsselect_spot1[]' && tForm[z].name!='chksnippetsselect_spot2[]' && tForm[z].name!='chksnippetsselect_spot3[]' && tForm[z].name!='chkcustomer_code_spot1' && tForm[z].name!='chkcustomer_code_spot2' && tForm[z].name!='chkcustomer_code_spot3' && tForm[z].name!='chksaveselectall_spot1' && tForm[z].name!='chksaveselectall_spot2' && tForm[z].name!='chksaveselectall_spot3' && tForm[z].name!='chksaveselect_spot1[]' && tForm[z].name!='chksaveselect_spot2[]' && tForm[z].name!='chksaveselect_spot3[]' && tForm[z].name!='chkselect[]' && tForm[z].name!='chksnippets_spot1'  && tForm[z].name!='chksnippets_spot2' && tForm[z].name!='chksnippets_spot3' && tForm[z].name!='chkcontents_spot1' && tForm[z].name!='chkcontents_spot2' && tForm[z].name!='chkcontents_spot3'){


				//eleNum[countChk]=z;

				if(startAr==false)

				{

					eleNum=z+1;

					startAr=true;

				}

				countChk++;

			}

		}


		 if (document.getElementById("art").value.length == 0) 

		  {

		  alert("Please enter a value.");

		  } 

	   else if (IsNumeric(document.getElementById("art").value) == false) 

		  {

		  alert("Please enter numeric value!");

		  }

		if(txt>(countChk-1)){

			alert("Please enter numeric value less than"+countChk);

			document.getElementById("art").focus();

			return false;

		}

		for(z=0;z<txt;z++)

		{
			var n=Math.floor(Math.random()*(countChk-1))+eleNum;

		      if(tForm[n].type == 'checkbox' && tForm[n].name != 'checkall')

		      {
			      tForm[n].checked =true;

		      }

		}
	}	

function unique_random(Min,Max,num){
    //this will swap min an max values if $Min>$Max
    if (Min>Max) { min2=Max; max2=Min; }
    else { min2=Min; max2=Max; }
    //this will avoid to enter a number of results greater than possible results
    if (num>(max2-min2)) num=(max2-min2);
    values=Array();
    result=Array();
	j=0;
    for (i=min2;i<=max2;i++) {
      values[j]=i;
	  j++;
    }
	//alert(values.length);
	k=0;
	for (j=0;j<num;j++){
     // key= Math.random(0,values.length-1);
	  xj=values.length-1;
	  key=Math.floor(Math.random()*xj)
      result[k]=values[key];
      //unset(values[key]);
	  delete values.key;
      values.sort();
	  k++;
    }
    return result;
	
}	
function selcat()
{
	document.getElementById("amcat").value=document.getElementById("seamcat").value;
	document.cat.submit();
}
	// add article from contain wizard
	function pimportfrom(from)
	{
		if (from.value== "C")
		{
			  document.getElementById("wizard").style.display = 'block';
			  document.getElementById("article_b").style.display = 'none';
				var xmlHttp;

				try

				  {

				  // Firefox, Opera 8.0+, Safari

				  xmlHttp=new XMLHttpRequest();

				  }

				catch (e)

				  {

				  // Internet Explorer

				  try

					{

					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");

					}

				  catch (e)

					{

					try

					  {

					  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");

					  }

					catch (e)

					  {

					  alert("Your browser does not support AJAX!");

					  return false;

					  }

					}

				  }

				  xmlHttp.onreadystatechange=function()

					{

					if(xmlHttp.readyState==4)

					  {
						  document.getElementById("waitmss1").innerHTML='';
					  	//alert(xmlHttp.responseText);
						
					  document.getElementById("wizard").innerHTML=xmlHttp.responseText;

					  }
				   if (xmlHttp.readyState == 1) 

						{
				
							document.getElementById("waitmss1").innerHTML="Please wait....";
				
						}

					}

				  xmlHttp.open("GET","contentwizard_psb.php",true);

				  xmlHttp.send(null);

				  
		}	
	}

// end of article
	// select categores function 

   function cat_select(theElement){

   		//alert("admin"+id);
		//sden2--------------------/
	   var chk_selected =[];
	   var chks = document.getElementsByName('chk[]');
	   for(var i=0;i<chks.length;i++){		
			if(chks[i].checked == true){
				chk_selected.push(chks[i].value);
			}
		}
	 

   //sden2--------------------/
		var tForm1 = theElement.form;

		//alert(tForm1.length);

		for(z=0,i=0;z<tForm1.length;z++)

		{

		      if(tForm1[z].type == 'checkbox' && tForm1[z].name != 'checkall' && tForm1[z].checked==true)

		      {

			      ch_art[i]=tForm1[z].value;i++;	      

		      }

		}


   	var xmlHttp;

				try

				  {

				  // Firefox, Opera 8.0+, Safari

				  xmlHttp=new XMLHttpRequest();

				  }

				catch (e)

				  {

				  // Internet Explorer

				  try

					{

					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");

					}

				  catch (e)

					{

					try

					  {

					  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");

					  }

					catch (e)

					  {

					  alert("Your browser does not support AJAX!");

					  return false;

					  }

					}

				  }

				  xmlHttp.onreadystatechange=function()

					{

					if(xmlHttp.readyState==4)

					  {

						hdwtms();
					
					  document.getElementById("wizard").style.display = 'block';
;

					  document.getElementById("wizard").innerHTML=xmlHttp.responseText;
					  var chks = document.getElementsByName('chk[]');
					   for(var i=0;i<chks.length;i++){	
							  for(var j=0;j<chk_selected.length;j++){ 								
									if(chks[i].value == chk_selected[j]){
										chks[i].checked = true;
									}
								
								
							  }
						}

					  }
					 if (xmlHttp.readyState == 1) 

						{
				
							shwtms("Please wait....");
				
						}	
					}

					var id=document.getElementById("seamcat").value;
							//alert(ch_art);
					var url="contentwizard_psb.php";
						
					url=url+"?amcat="+id+"&article="+ch_art;
						//url=url+"?amcat="+id;
					



				  xmlHttp.open("GET",url,true);

				  xmlHttp.send(null);

			

   }
 /*end of conent wizzard section script*/

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
  var http_request;
function GetXmlHttpObject()
{
var http_request=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 http_request=new XMLHttpRequest();
 }
catch (e)
 {
 //Internet Explorer
 try
  {
  http_request=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  http_request=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return http_request;
}

 
   function makePOSTRequest(url,parameters,paramtype) {
	   http_request=GetXmlHttpObject();
		if (http_request==null)
		 {
		 alert ("Browser does not support HTTP Request");
		 return;
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
			var result = http_request.responseText;
			//alert(http_request.responseText);
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
			var result = http_request.responseText;	
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
			var result = http_request.responseText;		
			
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
			var result = http_request.responseText;	
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
			var result = http_request.responseText;										
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
			var result = http_request.responseText;	
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
			var result = http_request.responseText;										
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
			var result = http_request.responseText;	
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
			var result = http_request.responseText;										
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
			var result = http_request.responseText;	
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
			var result = http_request.responseText;										
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
   
   
   function get_headlines_spot1(spot) {
	  var poststr = "process=" + encodeURI( document.getElementById("headlines_spot1").value )+
	   "&spot="+spot;
	  
	  makePOSTRequest('dams/getcampaign.php', poststr,'get_headlines_spot1');
   }
  function get_saveselection(spot) {
	  var poststr = "process=manage&spot="+spot;
	  if(document.getElementById('chkcontents_'+spot).checked==false){
	  document.getElementById('txtsaveselection_'+spot).value='';
	  }					 				  
	  makePOSTRequest('getsaveselection.php', poststr,'get_saveselection_'+spot);
   } 
	function get_snippets(spot) {
	  var poststr = "process=manage&spot="+spot;
	  if(document.getElementById('chksnippets_'+spot).checked==false){
	  document.getElementById('snippetscodetext_'+spot).value='';
	  }	
	  makePOSTRequest('getsnippets.php', poststr,'get_snippets_'+spot);
   }

