function ajaxRequest(url,process, part)
{
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
	
    xmlHttp.onreadystatechange=function() { ajaxResponse(xmlHttp, process, part); };

    if (process=="getadsfornewcampaign")
    {
    	xmlHttp.open("GET",url,false);
    }
    else
    {
    	xmlHttp.open("GET",url,true);    
    }

    xmlHttp.send(null);
}


