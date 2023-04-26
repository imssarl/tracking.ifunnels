// JavaScript starts for the page project.php here //

	function show_div_corescript()
	{
		document.getElementById("div_corescript").style.display="block";
	}
	function hide_div_corescript()
	{
		document.getElementById("div_corescript").style.display="none";
	}
	function show_div_paymentoption()
	{
		document.getElementById("div_paymentoption").style.display="block";
	}
	function hide_div_paymentoption()
	{
		document.getElementById("div_paymentoption").style.display="none";
	}
	function show_divprojetname(val)
	{
		if(document.getElementById("project_name_list").value=="0" || val=="0")
		{
			document.getElementById("project_name").style.display="inline";
			document.getElementById("gate_secret").style.display="block";
			document.getElementById("upgrader_div").style.display="none";
			document.getElementById("related").style.display="block";
			
		}	
		else
		{
			document.getElementById("project_name").style.display="none";
			document.getElementById("gate_secret").style.display="none";
			document.getElementById("upgrader_div").style.display="block";
			document.getElementById("related").style.display="none";
		}	
	}
	function hndlsr(tid,val)
	{
		var ad = document.getElementById('ad'+tid);
		var cp = document.getElementById('row'+tid);	
		if (ad.className == 'show')
		{	
			ad.className = 'noshow';
			cp.className = 'backcolor1';
		}
		else 
		{	if(val==0){
			ad.className = 'show';
			cp.className = 'backcolor3';
			}
		}
			
	}//created on 20080228
function hide_option()
{
	document.getElementById("div_paymentoption").style.display="none";
	document.getElementById("div_corescript").style.display="none"
}


// JavaScript ends for the page project.php here //


function fetch_rss(abc)
{
   rssurl=abc.value;
   //alert(rssurl);
   makeRequest('oprss',rssurl,'article');
}

function duplicate_profile(id)
{
  makeRequest('dup',id,'duplicate');
}