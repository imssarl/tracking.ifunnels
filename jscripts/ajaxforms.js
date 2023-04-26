 //This JavaScript modified from this Tutorial.
 //AJAX Form POST/GET - HTML Form Submit with AJAX/Javascript Example/Tutorial 
 //http://www.captain.at/howto-ajax-form-post-get.php

 var http_request = false;
   function makeRequest(url, parameters) {
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
      http_request.onreadystatechange = alertContents;
      http_request.open('GET', url + parameters, true);
      http_request.send(null);
   }

   function alertContents() {
      if (http_request.readyState == 4) {
         if (http_request.status == 200) {
            //alert(http_request.responseText);
            result = http_request.responseText;
            document.getElementById('passwordchk').innerHTML = result;            
         } else {
            alert('There was a problem with the request.');
         }
      }
	  else
	  {
		  document.getElementById('passwordchk').innerHTML ="<img src='../images/ajax-loader_new.gif' border='0' alt='processing' title='processing'/>";            
	  }
   }
   
   function get(obj) {    
	  var getstr = "?password=" + encodeURI( document.getElementById("txtdbpass").value );
      makeRequest('passwordstrength.php', getstr);
   }