var divid="";

function makeRequest(div,id,process) {

        var httpRequest;
	
        if(process=="profile")
	{ 
        divid=div;
	//alert("---"+div+"----"+id+"---"+process); return false;
	url="request.php?id="+id+"";

        }else if(process=="article"){
               divid=div;
		url="request_rss.php?rss_url="+escape(id)+"";

          }else if(process=="duplicate")
          {
               url="request_duprofile.php?id="+id+""; 
          }
        if (window.XMLHttpRequest) { // Mozilla, Safari, ...
            httpRequest = new XMLHttpRequest();
            if (httpRequest.overrideMimeType) {
                httpRequest.overrideMimeType('text/xml');
                // See note below about this line
            }
        } 
        else if (window.ActiveXObject) { // IE
            try {
                httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
                } 
                catch (e) {
                           try {
                                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
                               } 
                             catch (e) {}
                          }
                                       }

        if (!httpRequest) {
            alert('Giving up :( Cannot create an XMLHTTP instance');
            return false;
        }
        httpRequest.onreadystatechange = function() { alertContents(httpRequest,process); };
        httpRequest.open('GET', url, true);
        httpRequest.send('');

    }

    function alertContents(httpRequest,process) 
	{

        if (httpRequest.readyState == 4) 
		{
            	if (httpRequest.status == 200) 
	    		{
                                     if(process=="profile"){
					alert(httpRequest.responseText);
					document.getElementById(divid).innerHTML=0;
                                     }else if(process=="article"){
                                          document.getElementById(divid).innerHTML=httpRequest.responseText;
                                       // alert(httpRequest.responseText);
                                     }else if(process=="duplicate")
                                        {
                                          alert("Duplicate Profile has been created.");
                                        }
                   	}else 
				{
					alert('There was a problem with the request.');
            	}
        }

    }
