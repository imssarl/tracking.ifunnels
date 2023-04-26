function checkUncheckAll(theElement,damstype){           
	var tForm = theElement.form, z = 0;	
	if(tForm.chkselect.length!=undefined){
		for(z=0;z<tForm.chkselect.length;z++){
			if(document.getElementById("chkall").checked==true){
				tForm.chkselect[z].checked=true;
				get_damscode(tForm,damstype);
			}else{
			tForm.chkselect[z].checked=false;
			document.getElementById("dmascodetext").value ='';
			}
		}
	}else{
	
			if(document.getElementById("chkall").checked==true){		
				document.getElementById("chkselect").checked=true;				
				get_damscode(tForm,damstype);
			}else{
			document.getElementById("chkselect").checked=false;
			document.getElementById("dmascodetext").value ='';
			}
	}
}

function checksaveselectionUncheckAll(theElement,spot){           
	if(theElement.length!=undefined){
		for(z=0;z<theElement.length;z++){
			if(document.getElementById("chksaveselectall_"+spot).checked==true){		
				theElement[z].checked=true;
				get_saveselectioncode(theElement,spot);
			}else{
			theElement[z].checked=false;
			document.getElementById("txtsaveselection_"+spot).value ='';
			}
		}
	}else{
			if(document.getElementById("chksaveselectall_"+spot).checked==true){		
				document.getElementById("chksaveselect_"+spot).checked=true;
				get_saveselectioncode(document.getElementById("chksaveselect_"+spot),spot);
			}else{
			document.getElementById("chksaveselect_"+spot).checked=false;
			document.getElementById("txtsaveselection_"+spot).value ='';
			}
	}
}
function checksnippetsUncheckAll(theElement,spot){
 
	if(theElement.length!=undefined){
		for(z=0;z<theElement.length;z++){
			if(document.getElementById("chksnippetsall_"+spot).checked==true){		
				theElement[z].checked=true;
				get_snippetscode(theElement,spot);
			}else{
				theElement[z].checked=false;
				document.getElementById("snippetscodetext_"+spot).value ='';
			}
		}
	}else{
			if(document.getElementById("chksnippetsall_"+spot).checked==true){		
				document.getElementById("chksnippetsselect_"+spot).checked=true;
				get_snippetscode(document.getElementById("chksnippetsselect_"+spot),spot);
			}else{
				document.getElementById("chksnippetsselect_"+spot).checked=false;
				document.getElementById("snippetscodetext_"+spot).value ='';
			}
	}

}	

function get_damscode(theElement,type){  
var c_value = "";  
var noofcheck='';

  if(theElement.chkselect.length!=undefined){
   
	for (var i=0; i < theElement.chkselect.length; i++){
	   if (theElement.chkselect[i].checked){
		 	 c_value = c_value + 'if(function_exists("curl_init")){ $ch = @curl_init();curl_setopt($ch, CURLOPT_URL,"<?php echo SERVER_PATH;?>dams/showcode.php?id='+theElement.chkselect[i].value+'&process='+type+'&ref_url=".$_SERVER[\'HTTP_REFERER\']."&php_self=".$_SERVER[\'SERVER_NAME\'].$_SERVER[\'PHP_SELF\']);curl_setopt($ch, CURLOPT_HEADER, 0);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$resp=@curl_exec($ch);$err=curl_errno($ch);if($err === false || $resp ==""){$newsstr = "";}else{if (function_exists("curl_getinfo")){$info = curl_getinfo($ch);if ($info["http_code"]!=200)$resp="";}$newsstr = $resp;}@curl_close ($ch);echo $newsstr;}';
			 noofcheck=noofcheck+i;
		  }
		  
	   }
	 
	}
	else{
		noofcheck='1';
		c_value='if(function_exists("curl_init")){ $ch = @curl_init();curl_setopt($ch, CURLOPT_URL,"<?php echo SERVER_PATH;?>dams/showcode.php?id='+document.getElementById('chkselect').value+'&process='+type+'&ref_url=".$_SERVER[\'HTTP_REFERER\']."&php_self=".$_SERVER[\'SERVER_NAME\'].$_SERVER[\'PHP_SELF\']);curl_setopt($ch, CURLOPT_HEADER, 0);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$resp=@curl_exec($ch);$err=curl_errno($ch);if($err === false || $resp ==""){$newsstr = "";}else{if (function_exists("curl_getinfo")){$info = curl_getinfo($ch);if ($info["http_code"]!=200)$resp="";}$newsstr = $resp;}@curl_close ($ch);echo $newsstr;}';
			if(document.getElementById('chkselect').checked==false){noofcheck='';}
	}

	
	if(noofcheck!=''){	
		document.getElementById("dmascodetext").value =c_value;
	}else{
		document.getElementById("dmascodetext").value ='';
	}
}
function get_saveselectioncode(theElement,spot){  
var saveselection_value = ""; 
var noofcheck='';
  if(theElement.length!=undefined){
   
	for (var i=0; i < theElement.length; i++){
	  // if (theElement.chksnippetselect[i].checked){
	  if (theElement[i].checked){
		  saveselection_value = saveselection_value + theElement[i].value;
		  noofcheck=noofcheck+i;
		  }
	   }
	}
	else{
		noofcheck='1';
		saveselection_value = document.getElementById('chksaveselect_'+spot).value;
		if(document.getElementById('chksaveselect_'+spot).checked==false){noofcheck='';}
	}  
	if(noofcheck!=''){	
		document.getElementById("txtsaveselection_"+spot).value = saveselection_value;
	}else{
		document.getElementById("txtsaveselection_"+spot).value ='';
	} 
	
}



function get_snippetscode(theElement,spot){  
var snippet_value = ""; 
var noofcheck='';
  if(theElement.length!=undefined){
   
	for (var i=0; i < theElement.length; i++){
	  // if (theElement.chksnippetselect[i].checked){
	  if (theElement[i].checked){
		  snippet_value = snippet_value + 'if(function_exists("curl_init")){ $ch = @curl_init();curl_setopt($ch, CURLOPT_URL,"<?php echo SERVER_PATH;?>snippetsshow.php?id='+theElement[i].value+'");curl_setopt($ch, CURLOPT_HEADER, 0);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$resp=@curl_exec($ch);$err=curl_errno($ch);if($err === false || $resp ==""){$newsstr = "";}else{if (function_exists("curl_getinfo")){$info = curl_getinfo($ch);if ($info["http_code"]!=200)$resp="";}$newsstr = $resp;}@curl_close ($ch);echo $newsstr;}';
		  noofcheck=noofcheck+i;
		  }
	   }
	}
	else{
		noofcheck='1';
		snippet_value='if(function_exists("curl_init")){ $ch = @curl_init();curl_setopt($ch, CURLOPT_URL,"<?php echo SERVER_PATH;?>snippetsshow.php?id='+document.getElementById('chksnippetsselect_'+spot).value+'");curl_setopt($ch, CURLOPT_HEADER, 0);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$resp=@curl_exec($ch);$err=curl_errno($ch);if($err === false || $resp ==""){$newsstr = "";}else{if (function_exists("curl_getinfo")){$info = curl_getinfo($ch);if ($info["http_code"]!=200)$resp="";}$newsstr = $resp;}@curl_close ($ch);echo $newsstr;}';
		if(document.getElementById('chksnippetsselect_'+spot).checked==false){noofcheck='';}
	}  
	if(noofcheck!=''){	
		document.getElementById("snippetscodetext_"+spot).value = snippet_value;
	}else{
		document.getElementById("snippetscodetext_"+spot).value ='';
	} 
	//alert(snippet_value);
}
