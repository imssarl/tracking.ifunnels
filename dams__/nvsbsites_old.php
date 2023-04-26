<?php

//error_reporting(E_ALL);
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/settings.class.php");
require_once("classes/sites.class.php");
require_once("classes/common.class.php");
require_once("classes/sites.steps.class.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");
require_once("classes/projects.class.php");
require_once("classes/feed.class.php");
require_once("classes/cnbprofile.class.php");
require_once("classes/cnbmanage_template.class.php");
require_once("classes/nvsbmanage_template.class.php");
require_once("classes/cnbsites.class.php");
require_once("classes/sites.steps.class.php");
require_once("classes/nvsbsites.class.php");
require_once("classes/nvsbprofile.class.php");
require_once("classes/pclzip.lib.php");
include_once("classes/ftp.class.php");
require_once("classes/en_decode.class.php");
$Ftp_obj=new Ftp();
$feed = new xmlParser();
$settings = new Settings();
$settings->checkSession();
$project = new Projects();
$common = new Common();
$ms_db = new Database();
$ms_db->openDB();
$sites = new Sites();
$steps = new Steps();
$steps = new Steps();
$pg = new PSF_Pagination();
$sc = new psf_Search();
$profile = new CNBProfile();
$template = new mng_template();
$nvsb_template = new nvsb_template();
$csites = new CNBSites();
$nvsbsites = new NVSBSites();
$nvsbprofiles = new NVSBProfile();


if (isset($_GET['process']))
$process = $_GET['process'];
else if (isset($_POST['process']))
$process = $_POST['process'];
else  $process = "manage";

if($process=="change_settings")
{
	if(isset($_POST['update_type']) && $_POST['update_type']=="change_settings")
	{
	//print_r($_POST);die();
		$site_data = $nvsbsites->getSingleProject($_POST['cnvb_site_id']);
		if($site_data)
		{
		
			if($site_data["sub_folder"]=="NULL")
				$_POST["sub_folder"] = "";
			else
				$_POST["sub_folder"] = $site_data["sub_folder"];
			

			$conn = @ftp_connect($site_data["ftp_address"]);
			if (@ftp_login($conn, $site_data["ftp_username"], $site_data["ftp_password"])) 
                        {
				$template_info = $nvsb_template->getTemplateById($site_data['temp_id']);
				$uploadstatus = $nvsbprofiles->uploadProfile($site_data['ftp_homepage'],$template_info['temp_name']);
				if($uploadstatus)
				{
				        $updatestatus = $nvsbsites->updateSiteSettings($_POST['cnvb_site_id']);
				        if($updatestatus)
				        {
						$msg = "Settings changed successfully!";
						$process="manage";
						header("location: nvsbsites.php?msg=".$msg."&process=".$process);
						exit();
				        }
					else
					{
						header("location: nvsbsites.php?msg=problem in updating data");
						exit();
					}
				}
				else
				{
					header("location: nvsbsites.php?msg=problem in uploading data");
					exit();
				}
			}
			else
			{
				header("location: nvsbsites.php?msg=problem connecting to server");
				exit();
			}
		}
		else
		{
			header("location: nvsbsites.php?msg=problem in uploading data");
			exit();
		}
		
	}
	else
	{
		if(isset($_GET["id"]) && $_GET['id']!="")
		{
			$site_data = $nvsbsites->getSingleProject($_GET["id"]);
		}
		else
		{
			header("location: nvsbsites.php?msg=problem updating data");
		}
	}

}
else if($process=="delete_site")
{
	
	//print_r($_POST);die();
		$site_data = $nvsbsites->deletesiteByid($_GET['id']);
		if($site_data)
		{
			header("location: nvsbsites.php?msg=Your NVSB Site has been deleted.");
			
		}
		else
		{
			header("location: nvsbsites.php?msg=Problem in deleting your nvsb site.");
		
		}
		
	

}

elseif($process=="change_template")
{

}
elseif($process=="delete_template")
{
	//$nvsb_template->deleteTemplateById($_GET['tid']);
	//$msg="Template Deleted Successfully";
	$process="manage_template";
	$del = $nvsb_template->deleteTemplateById($_GET["tid"]);
	//header("location: nvsbsites.php?process=".$process."&msg=".$msg."");
		if ($del)
		{
			$delfol = $nvsb_template->deleteFolder(trim($_GET["tname"]));
			if($delfol)
			{
				header("location: nvsbsites.php?msg=Template ".$_GET["tname"]." has been deleted&process=".$process);
			}
			else
			{
				header("location: nvsbsites.php?msg=Due to some problem, contents of template ".$_GET["tname"]." are still exist,<BR>Please remove them manually&process=".$process);
			}
		}
		else
		{
			header("location: nvsbsites.php?msg=Problem in deleting ".$_GET["tname"]." template details&process=".$process);
		}
}
else
{
	$sites_rs = $nvsbsites->getProjects();
}



if ($process=="validate")
{
	if (isset($_GET['ftp_homepage']))
	{
		if ($_GET['ftp_homepage'] != "")
		{
			$_GET['ftp_homepage'] = trim(str_replace(array("\\\\","\\"),"/",$_GET['ftp_homepage']));
			if (substr($_GET['ftp_homepage'],strlen($_GET['ftp_homepage'])-1,1)!="/") $_GET['ftp_homepage'] .= "/";
			if (substr($_GET["ftp_homepage"],0,1)!="/") $_GET['ftp_homepage'] = "/".$_GET['ftp_homepage'];
		}
		$_GET['url'] = trim(str_replace(array("\\\\","\\"),"/",$_GET['url']));
		if (substr(trim($_GET['url']),0,7)!="http://") $_GET['url']="http://".$_GET['url'];
		if (substr($_GET['url'],strlen($_GET['url'])-1,1)!="/") $_GET['url'] .= "/";
	}

		$validate = "true";
		$RS = "!%#%!";
		$urlmsg = "noresp";
		$ftpmsg = "noresp";
		
		$exist = $sites->isURLexist($_GET['url'],0);
		if ($exist)
		{
			$urlmsg = "Site URL already exists";
			$validate = "false";
		}

		//$steps->ftp_details($_GET['ftp_address'],$_GET['ftp_username'],$_GET['ftp_password'],$_GET['ftp_homepage'], $_GET['url']);
		
		//$isftp = $steps->check_ftp_details();

		//$steps->close_conn();
		
		//if ($isftp!="ok")
		//{
		//	$ftpmsg = $isftp;
		//	$validate = "false";
		//}

		echo $validate.$RS.$urlmsg;
		//.$RS.$ftpmsg;
		die();
}else if ($process=="getftpdtl")
{
	$site = $sites->getSiteByID($_GET["id"]);	
	if ($site)
	{
		$RS = "!%#%!";
		$tabl = $site["ftp_address"].$RS.$site["ftp_username"].$RS.$site["ftp_password"];
		echo 'true'.$RS.$tabl;
	}
	else
	{
		echo "false";
	}
die();
}
if(isset($_POST['process']) && $_POST['process']=="upload_template")
{
	if(isset($_POST['upload_in']) && $_POST['upload_in']!="-1")
	{  
		
		if($_POST['upload_in']=="nvsb")
		{
			$upload_dir = "nvsb_templates/";
			require_once("classes/nvsbmanage_template.class.php");
							$nvsb_template = new nvsb_template();
		}
	}
	else
	{   
	      	$upload_dir = "template/";
	}


	$template_name=$_FILES['upload_temp']['name'];

	$pos = strpos($template_name,".");

	$chk_format=substr($template_name,$pos+1);

	$template_name=substr($template_name,0,$pos);

	$template_name=trim($template_name);



	if($chk_format=="zip" || $chk_format=="ZIP")
	{

			

		if($_FILES['upload_temp']['error']==0 && $_FILES['upload_temp']['size'] > 0)
		{
			$archive = new PclZip($_FILES['upload_temp']['tmp_name']);

			$upfiles = $archive->listContent();

			$template_name = "";

			foreach($upfiles as $upar)
			{

				$up = $upar["stored_filename"];

				$pos = strpos($up,"/");

				$template_name=substr($up,0,$pos);

				$template_name=trim($template_name);

				$mmsg = "";
				
				if ($template_name != "")
				{

					if (file_exists($upload_dir.$template_name))
					{
						$template_name = "";
						$mmsg = "This template is already exists";
					}

					break;

				}

			}
			if ($template_name != "")
			{

				if ($archive->extract($upload_dir) != 0)
				{
					
					if($_POST['upload_in']=="nvsb")
					{
						$nvsb_template->insert_template($template_name);
						$msg = "Template Upload Successfully.";
						header("location: nvsbsites.php?msg=".$msg.'&process=manage_template');
					}
					
				}
				else
				{	
					$msg= "Unable to extract ".$template_name."Zip please upload using FTP or try again later";	
				}
			}
			else
			{
				if ($mmsg=="")
				$msg= "The zip file you uploaded has bad template format, please check it";	
				else
				$msg = $mmsg;
			}
		}
		else
		{	
			$msg="Unable to ulpload ".$template_name."Zip please upload using FTP or try again later";	
		}
 	}
 	else
 	{
 		 $msg="Please select zip format file";	
 	} 



	$_SESSION['upload_template']="";

	unset($_SESSION['upload_template']);	

	if($msg!="")

	{

		$process="new_template";

	}

	else

	{

		$process="collect_template";

	}



}



?>

<?php require_once("header.php"); ?>

<title>
<?php echo SITE_TITLE; ?>
</title>
<script src="jscripts/common.js"></script>
<script src="jscripts/ajax.js"></script>
<script language="javascript">
function checkUncheckAll(theElement){           
	var tForm = theElement.form, z = 0;  
	for(z=0;z<tForm.length;z++){							
		if(tForm[z].type == 'checkbox' && tForm[z].name != 'checkall'){							
		tForm[z].checked = theElement.checked;
		}
	}
}
function get_damscode(theElement,type){
var tForm = theElement.form,var damscodes = "";
for (var i=0; i < tForm.chk.length; i++){
	   if (tForm.chk[i].checked)
		  {
		  //c_value = c_value + "'" +tForm.chk[i].value +"',";
		  damscodes= damscodes + 'if(function_exists("curl_init")){ $ch = @curl_init();
curl_setopt($ch, CURLOPT_URL,"<?php echo SERVER_PATH;?>dams/showcode.php?id='+ tForm.chk[i].value +'&process='+ type +'&ref_url=".$_SERVER[\'HTTP_REFERER\']."&php_self=".$_SERVER[\'SERVER_NAME\'].$_SERVER[\'PHP_SELF\']");

curl_setopt($ch, CURLOPT_HEADER, 0);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$resp = @curl_exec($ch);

$err = curl_errno($ch);
if($err === false || $resp == ""){
	   $newsstr = "";
} else{
	   if (function_exists("curl_getinfo")){

		   $info = curl_getinfo($ch);
		   if ($info["http_code"]!=200)$resp="";
	   }
	   $newsstr = $resp;
}
@curl_close ($ch);
echo $newsstr;
}\n';
		  }
	   }
	}
document.getElementById("dmascodetext").value=damscodes;
}
function updShowPotalBlock(pack)
{
	if (pack==-1)
	{
//		document.getElementById("portalftpdtl").style.display = 'none';
	//	document.getElementById("ftpdtlform").style.display = 'none';
		document.getElementById("ftpform").style.display = 'none';
		freeTextBoxes();
	}
	else
	{
		document.getElementById("ftpform").style.display = 'block';
//		document.getElementById("portalftpdtl").style.display = 'block';		
		freeTextBoxes();
//		document.getElementById("ftpdtlform").style.display = 'none';	
	}
}
function smode(mode)
{
	if (mode.value=="N") {
		document.getElementById("divpackage").style.display = 'none';
		document.getElementById("subdmn").style.display = 'none';				
		document.getElementById("ftpform").style.display = 'block';
		document.getElementById("ftpdtlform").style.display = 'block';
		document.getElementById("packagelist").value = -1;
		document.getElementById("portallist").style.display = 'none';
		document.getElementById("ft").style.display = 'none';
		document.getElementById("subfolder").value = '';
		freeTextBoxes();

	} else if (mode.value=="Y") {
		document.getElementById("divpackage").style.display = 'block';
		document.getElementById("subdmn").style.display = 'block';
		document.getElementById("portallist").style.display = 'block';
		document.getElementById("ftpform").style.display = 'none';		
		document.getElementById("ft").style.display = 'block';
	}
}
function freeTextBoxes()
{			
	document.getElementById("ftp_address").readOnly = false;
	document.getElementById("ftp_username").readOnly = false;
	document.getElementById("ftp_password").readOnly = false;
	
	document.getElementById("ftp_address").value = "";
	document.getElementById("ftp_username").value = "";
	document.getElementById("ftp_password").value = "";
	document.getElementById("same_ftp_address").value = "";
	document.getElementById("same_ftp_username").value = "";
	document.getElementById("same_ftp_password").value = "";
	
}			
function changeDetailMode(check)
{
	if (!check.checked)
	{
//			document.getElementById("portalftpdtl").style.display = 'none';
//			document.getElementById("ftpdtlform").style.display = 'block';
			freeTextBoxes();
			return true;
	}
	else
	{
//			document.getElementById("portalftpdtl").style.display = 'block';
//			document.getElementById("ftpdtlform").style.display = 'none';
			document.getElementById("ftp_address").readOnly = true;
			document.getElementById("ftp_username").readOnly = true;
			document.getElementById("ftp_password").readOnly = true;

		return false;
	}	
}
function showftpform(pack)
{

}
function pmode(mode)
{
	if (mode.value=="O") {
		document.getElementById("modeone").style.display = 'block';
		document.getElementById("moderec").style.display = 'none';
//		alert("keyword pages are generated and project is completed");
	} else if (mode.value=="R") {
		document.getElementById("modeone").style.display = 'none';
		document.getElementById("moderec").style.display = 'block';
	}
}

function set_focus(key)
{
if (key == "genall") {
document.getElementById("genxfst").disabled = true;
document.getElementById("genxrnd").disabled = true;
} else if (key == "genxfst") {
document.getElementById("genxfst").disabled = false;
document.getElementById("genxrnd").disabled = true;
} else if (key == "genxrnd"){
document.getElementById("genxfst").disabled = true;
document.getElementById("genxrnd").disabled = false;

}
}
	function operation(process,tmpl)
	{
	
		if (process == "showtemplate")
		{
			if (tmpl.value != -1)
			{
				temp_name = tmpl["options"][tmpl["selectedIndex"]].text;
				document.getElementById("temp_name").value = temp_name;
				document.getElementById("mif").style.display = 'block';
				url = "nvsbmanage_template.php?process=getprev&temp_name="+temp_name;
				ajaxRequest(url,"getprev",1);
			}
			else
			{
				document.getElementById("mif").style.display = 'none';
			}
			
		}
		else if (process == "getftpdtl")
		{
			changeDetailMode(tmpl)
			if (tmpl.checked)
			{
				val = document.getElementById("packagelist").value;
				url = "nvsbsites.php?process=getftpdtl&id="+val;
				ajaxRequest(url,"getftpdtl",1);
			}
		}
		else if (process == "validate")
		{   	         
    
			url = "nvsbsites.php?process=validate&url="+tmpl[0]+"&ftp_address="+tmpl[1]+"&ftp_username="+tmpl[2]+"&ftp_password="+tmpl[3]+"&ftp_homepage="+tmpl[4];
		         
			ajaxRequest(url,"validate",1);
		}
	
	}
	function ajaxResponse(xmlHttp, process, part)
	{
	if (xmlHttp.readyState == 4) 
	{
//		document.getElementById("waitmss").innerHTML = "";
		hdwtms();
		if (xmlHttp.status == 200) 
		{
			if (xmlHttp.responseText != "")
			{
				response = explodeStr(xmlHttp.responseText, "!%#%!");
				if (process == "getprev")
				{
					if (response[0]=="true")
					{
						document.getElementById("divprev").innerHTML = response[1];
						document.getElementById("divdesc").innerHTML = response[2];
					}
				}
				else if (process == "getftpdtl")
				{
					if (response[0]=="true")
					{
//						document.getElementById("portalftpdtl").innerHTML = response[1];
						document.getElementById("ftp_address").value = response[1];
						document.getElementById("ftp_username").value = response[2];
						document.getElementById("ftp_password").value = response[3];
						// now some extra lines to make it compatible with sites.php
						document.getElementById("same_ftp_address").value = response[1];
						document.getElementById("same_ftp_username").value = response[2];
						document.getElementById("same_ftp_password").value = response[3];
					}
				}
				else if (process =="validate")
				{
					if (response[0]=="true")
					{
						document.cnbnewsite.submit();
					}
					else
					{
/*						var msg = "";
						msg += "The following error(s) occured:\n";
						msg += response[1]+"\n";
						msg += response[2]+"\n";
						*/
						if (trim(response[1])!="noresp")
						document.getElementById("urlmsg").innerHTML = response[1];
						else 
						document.getElementById("urlmsg").innerHTML = "";
						if (trim(response[2])!="noresp")
						document.getElementById("ftpmsg").innerHTML = response[2];
						else
						document.getElementById("ftpmsg").innerHTML = "";
					}
				}
			}
			else
			{
				message.value = "Unable to perform operation";
			}
			} 
			else 
			{
				alert("There was a problem retrieving the XML data:\n" +
				xmlHttp.statusText);
			}
		}
		if (xmlHttp.readyState == 1) 
		{
			shwtms("Please wait....");
		}
	}
	function shwtms(mss)
	{
		msd = document.getElementById("waitmss");
//		msd.style.display = "block";
		msd.innerHTML = mss;
	}	
	function hdwtms()
	{
		msd = document.getElementById("waitmss");
//		msd.style.display = "none";
		msd.innerHTML = '';
	}		
	function validate(frm)
	{
		var mss = "";
		if (frm.temp_id.value<1) mss += "- Please select a template\n";
		else 
		{
		
			//if (trim(frm.adsense_id.value)=="") mss += "- Please enter adsense id\n";
			if (trim(frm.main_keyword.value)=="") mss += "- Please enter main keyword\n";
			//if (trim(frm.tag_cloud_word.value)=="") mss += "- Please enter tag cloud word\n";
			if (trim(frm.ftp_address.value)=="") mss += "- FTP address should be entered\n";
			if (trim(frm.ftp_username.value)=="") mss += "- FTP username should be entered\n";
			if (trim(frm.ftp_password.value)=="") mss += "- FTP password should be entered\n";
			if (trim(frm.ftp_homepage.value)=="") mss += "- FTP home page should be entered\n";
			if (trim(frm.sub_folder.value)=="") mss += "- Please enter Sub Folder\n";
			if (trim(frm.url.value)=="") mss += "- Please enter site URL\n";

		}
		if (mss.length>0)
		{
			alert(mss);
			return false
		}
		var data = new Array(4);
		//frm.description.value = frm.title.value;
		//frm.importmanual.value = frm.prim_keyword.value+"\n"+frm.importmanual.value;
		data[0] = frm.url.value;
		data[1] = frm.ftp_address.value;
		data[2] = frm.ftp_username.value;
		data[3] = frm.ftp_password.value;
		data[4] = frm.ftp_homepage.value;
		operation("validate",data);
		return false;
	}
	function browseroot(which)
	{
		addr = document.getElementById("ftp_address").value;
		user = document.getElementById("ftp_username").value;
		pass = document.getElementById("ftp_password").value;
		
		if (addr.length==0 || user.length == 0 || pass.length == 0)
		{
		alert("Please enter all FTP details");
		return false;
		}
		
		testwindow= window.open ("browsef.php?dir=&onlyf=yes&oldv=yes&address="+addr+"&username="+user+"&password="+pass+"&homebox="+which, "mywindow" ,"status=0,scrollbars=1,width=400,height=500,resizable=1");
	/*testwindow= window.open ("browse.php?dir=.&address="+addr+"&username="+user+"&password="+pass+"&homebox="+which, "mywindow");
	*/
		testwindow.moveTo(50,50);
		//window.open("" );
	}
	function chft(ft)
	{
		var sf = document.getElementById("subfldr");
		if (ft.value == "F")
		{
			sf.style.display = "block";
		}
		else 
		{
			sf.style.display = "none";
			document.getElementById("subfolder").value = '';
		}
	}
	function subdwind(name)
	{
		openwindow= window.open ("mytool/"+name, "Subdomain",
			"status=0,scrollbars=1,width=625,height=450,resizable=1");
		
		openwindow.moveTo(50,50);
	}
	function trackkw(ta)
	{
		var kw = ta.value;
		var nok = 0;
		var kwa = kw.split("\n");
		for(var i=0; i<kwa.length; i++)
		if (trim(kwa[i]) != "") nok++;
		
		document.getElementById("nok").innerHTML = nok;
	}
	function deltempl(tid, tname)
	{
	
		if(confirm("Are you sure to delete template "+tname+"?"))
		{
			location = 'nvsbsites.php?process=delete_template&tid='+tid+'&tname='+tname;
		}
	}		
	function validat()

	{
	
	var error="There are following errors:\n";
	
	var flag=true;	
	
	if(document.new_template.upload_temp.value == "")
	
	if(document.new_template.upload_temp.value == "")
	
		{	
	
			error+="- Please select template ZIP file from the disk\n";
	
			flag = false;
	
		}
	
	
	
	if(flag==false)
	
		{
	
			alert(error);
	
			return false;	
	
		}
	
		else
	
		{
	
		
	
		return true;	
	
		}
	
	}	
</script>
<script src="jscripts/jquery.js" type="text/javascript"></script>
<script src="jscripts/main.js" type="text/javascript"></script>	
<style>



/*  */

#screenshot{
	position:absolute;
	border:1px solid #ccc;
	background:#333;
	padding:5px;
	display:none;
	color:#fff;
	}

/*  */
</style>
<?php  $menutype="cnb"; require_once("top.php"); ?>
<?php require_once("left.php"); ?>
&nbsp;<a href="index.php">Home</a> >> Niche Video Site Builder
<br><br><br>
<table width="80%"  border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td colspan="2" align="center" class="heading"> 
			<a href="nvsbsites.php?process=new" class="menu">Create NVSB site</a> | <a class="menu"  href="sites.php?process=manage_nvsbsites">Manage NVSB Sites</a>| <a
class="menu" href="nvsbsites.php?process=manage_template">Manage Template</a>
		</td>
	</tr>
</table>
<?php
	
	if($process=="new" or $process=="already")
	{
?>
<script type="text/javascript">
function displayftpserver()
{

if(document.getElementById("ftpserveroption").value=="new_ftp")	{	


	document.getElementById('ftp_address').value= "";
	document.getElementById('ftp_username').value= "";
	document.getElementById('ftp_password').value= "";
	
	document.getElementById('ftp_address').readOnly=false;
	document.getElementById('ftp_username').readOnly=false;
	document.getElementById('ftp_password').readOnly=false;
}
else if(document.getElementById("ftpserveroption").value=="" || document.getElementById("ftpserveroption").value!="new_ftp")	{
	
	var temp = new Array();
	str=document.getElementById("ftpserveroption").value;
	temp=str.split(' ');
	
	document.getElementById('ftp_address').readOnly=true;
	document.getElementById('ftp_username').readOnly=true;
	document.getElementById('ftp_password').readOnly=true;
	
	document.getElementById('ftp_address').value= temp[0];
	document.getElementById('ftp_username').value= temp[1];
	document.getElementById('ftp_password').value= temp[2];
}
}
</script>
<br>
<form name="cnbnewsite" method="post" action="sites.php" onSubmit="return validate(this)">
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="inputform">
	<tr>
   		<td class="heading" colspan="2" width="100%"><?php
		if($process=="new") {echo "Create New Site";}else{ echo "Register NVSB Site";}?></td>
	</tr>
	<tr>
      		<td>
			<?php if($process=="new"){?>
      			<table width="100%" cellpadding="0" cellspacing="0">
				
				<tr>
			      		<TD>&nbsp;</TD>
			      		<TD align="right" nowrap="nowrap">Select Template :</TD>
			      		<td><?php echo $nvsb_template->templateList(); ?></td>
			      </tr>
			      <tr><TD colspan="2" height="10px"></TD></tr>
      			</table>
				
      			<div id="mif" style="display:none;">
		      		<table  border="0" width="100%" cellpadding="4" cellspacing="0">
					<tr><th colspan="2" height="2px" class="formback2"></th></tr>
					<tr><th colspan="2" height="10px"></th></tr>
					<tr>
						<TD colspan="2" align="left" valign="middle">
							<table width="100%">
								<TR>
									<TD align="center" valign="middle">
										<div id="divprev" align="center" style="display:inline;"></div>
									</td>
									<td align="left" valign="top">
										<div id="divdesc" align="justify" style="display:inline;"></div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					
					<tr><th colspan="2" height="2px" class="formback2"></th></tr>
					<tr><TD colspan="2" height="10px"></TD></tr>
					<tr>
						<TD width="30%" align="right"> Adsense ID : </td>
						<TD><input type="text" name="adsense_id" value="" size="30"><br/>
						Format: pub-xxxxx; do not forget the pub-...</td>
					</tr>           	
					<tr>
						<TD align="right">Main Keyword : </td>
						<TD><input type="text" name="main_keyword" value="" size="30"><br/>
						Example:Flower Gardening
						</td>
					</tr>           	
					
					
					<tr>
						<TD align="right">Tag Cloud Word : </td>
						<TD><input type="text" name="tag_cloud_word" value="" size="30"><br/>
						We recommend no more than 10 to 15 words. Separate each word with coma.</td>
					</tr>
					
					<tr>
						<TD align="right">Related keywords:</td>
						<TD><select name="related_keywords"><option value="0">0</option><option value="1">1</option></select>
						<br/>"0" will hide this section,"1" will display it.
						</td>
					</tr>
					
					<tr>
						<TD align="right">Usage:</td>
						<TD><select name="usage"><option value="1">1</option><option value="2">2</option></select>
						<br/>1 to use filter videos using mandatory keywords,2 to filter videos using banned keywords</td>
					</tr>
					
					<tr>
						<TD align="right">Mandatory keywords:</td>
						<TD><input type="text" name="mandatory_keywords" value="" size="50"><br/>
						If you have selected 1 for the usage parameter,enter your mandatory words separated with coma.</td>
					</tr>
					
					<tr>
						<TD align="right">Show_comments:</td>
						<TD><select name="show_comments"><option value="0">0</option><option value="1">1</option></select>
						<br/>
						0 to hide the comments,1 to show the comments and enable your vistors to add
comments to your videos.</td>
					</tr>
					
					<tr><td colspan="2" align="center">
						<div id="ftpmsg" class="message" style="display:inline;"></div>
					</td></tr>     
					<?php } /*elseif($process=="already"){?>
					<div id="mif">
		      		<table  border="0" width="100%" cellpadding="4" cellspacing="0">
									
					<?php } */?>
					<tr>
						<td align="right">Existing FTP Server  : </td>
						<td align="left"><select id="ftpserveroption" onchange="displayftpserver()">
						<option value='new_ftp'>--New FTP--</option>
						<?php 
						$sql="select * from ".TABLE_PREFIX."ftp_details_tb where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
						$Ftp_rs = $ms_db->getRS($sql);
						if($Ftp_rs){
						while($Ftp=$ms_db->getNextRow($Ftp_rs))
						{
						echo "<option value='".$Ftp["ftp_address"]." ".$Ftp["ftp_username"]." ".$Ftp["ftp_password"]."'>".$Ftp["ftp_address"]."(".$Ftp["ftp_username"].")"."</option>";
						}
						}
						?>			
					</select> </td>
					</tr>
					      	
					<tr>
						<td align="right">FTP Address  :&nbsp;</td>
						<td align="left">
							<input name="ftp_address" type="text" id="ftp_address" value="<?php echo $site_data['ftp_address'] ?>" size="30" maxlength="255">
						</td>
					</tr>
					<tr>
						<td align="right">FTP Username  :&nbsp;</td>
						<td align="left">
						<input name="ftp_username" type="text" id="ftp_username" value="<?php echo $site_data['ftp_username'] ?>" size="30" maxlength="255">			</td>
					</tr>
					<tr>
						<td align="right">FTP Password  :&nbsp;</td>
						<td align="left">
							<input name="ftp_password" type="password" id="ftp_password" value="" size="30" maxlength="255">
						</td>
					</tr>
					<tr>
						<td align="right" >FTP Homepage Folder : </td>
						<td align="left">
							<input name="ftp_homepage" type="text" id="ftp_homepage1" value="" size="50" maxlength="255">		
							<input type="button" name="browse" id="browse" value="Browse" onClick="browseroot(1)"><br>(ex.:/user_folder/public_html/path_to_site_root_folder/)
						</td>
					</tr>
					<tr>
						<td align="right">Sub Folder  :&nbsp;</td>
						<td align="left">
							<input name="sub_folder" type="text" id="sub_folder" value="" size="30" maxlength="255">
							<br>//(example, if www.site.com/videos/ is your site, input videos in the above field)
						</td>
					</tr>
					<tr><td colspan="2" align="center"><div id="urlmsg" class="message" style="display:inline;"></div></td></tr>
			     		
					
					<tr>
						<td align="right">URL of the site  :&nbsp;</td>
						<td align="left">
							<input name="url" type="text" id="url" value="" size="30" maxlength="255">
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<!--Start adavance option module -->
						<fieldset><legend>Advanced Customization Options</legend>
						<table border="0" width="100%" cellpadding="4" cellspacing="0">
							<tr><td align="left">
							<script type="text/javascript" language="javascript">
							   var http_request = false;
							   function makePOSTRequest(url, parameters) {
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
								  http_request.open('POST', url, true);
								  http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								  http_request.setRequestHeader("Content-length", parameters.length);
								  http_request.setRequestHeader("Connection", "close");
								  http_request.send(parameters);
							   }
							
							   function alertContents() {
								  if (http_request.readyState == 4) {
									 if (http_request.status == 200) {
										//alert(http_request.responseText);
										result = http_request.responseText;
										document.getElementById('processing').style.display="none";										
										document.getElementById('get_headline').innerHTML = result;            
									 } else {
									 
										alert('There was a problem with the request.');										
									 }
								  }else{
								  	document.getElementById('processing').style.display="block";
								  }
								  
							   }
							   
							   function get_headlines(obj) {
								  var poststr = "process=" + encodeURI( document.getElementById("headlines").value );
								  makePOSTRequest('dams/getcampaign.php', poststr);
							   }
							function show_div(types){
							if(types=="damscode"){
								if(document.getElementById('damscode').checked==true){
								document.getElementById('damscodes').style.display="block";
								document.getElementById('processing').style.display="none";	
								}else{
								document.getElementById('damscodes').style.display="none";
								document.getElementById('processing').style.display="none";	
								}
							}
							else if(types=="spot"){
								if(document.getElementById('spot').checked==true){
								document.getElementById('spots').style.display="block";
								
								}else{
								document.getElementById('spots').style.display="none";
								
								}
							}
							else if(types=="spot1"){
								if(document.getElementById('spot2').checked==true){
								document.getElementById('spots1').style.display="block";
							
								}else if(document.getElementById('spot1').checked==true){
								document.getElementById('spots1').style.display="none";
								
								}
							}
								
							}
							
							</script>
							<input type="checkbox" name="damscode" id="damscode" value="yes" onchange="show_div('damscode');"/>
							Do you want to add a floating, top / bottom, or corner ad?<br/><br/>
								<textarea id="dmascodetext">som</textarea>
							<div id="damscodes" style="display:none;float:left;">
							<form action="javascript:get_headlines(document.getElementById('headline'));" name="headline" id="headline">
							Headline:<select name="headlines" id="headlines" onchange="get_headlines(this.parentNode);">
								<option value="">--Select--</option>
								<option value="manage">Campaigns</option>
								<option value="split">Split Tests</option>
							</select><img id="processing" src='./images/ajax-loader_new.gif' alt='processing'/><br/><br/>
							</form>
							<span id="get_headline">&nbsp;</span>
							</div>
							</td></tr>							
							<tr><td height="2"></td></tr>
							<tr><td align="left"><input type="checkbox" name="spot" id="spot" value="yes" onchange="show_div('spot');"/>You can now customize the 3 following spots" (you can have a "?" icon where user can click
and a screenshot will appear with the 3 spots displayed)(recommended width: 468px, max: 550px).<br/><br/>
							<div id="spots" style="display:none;">
							<input type="radio" name="spot1" id="spot1" value="default" onchange="show_div('spot1');"/>Default Adsense ads<br/>
							<input type="radio" name="spot1" id="spot2" value="customze" onchange="show_div('spot1');"/>Replace by...<br/>
								<div style="display:none;" id="spots1">
								<fieldset><legend>Replace by</legend>
								<input type="checkbox" name="chkcontents" value="content" onchange="get_saveselections();"/>Content (from the content wizard)
									<span id="get_saveselections"></span><br/>
									
								<input type="checkbox" name="chksnippets" value="snippets" onchange="get_snippets();"/>Rotating ad / snippets
									<span id="get_snippets"></span><br/>
									
								<input type="checkbox" name="chkcustomer_code" value="customer_code" onchange="get_customercode();"/>Customer code
									<span id="get_customercode"></span>
								</fieldset>	
								</div>
							</div>
							</td></tr>						
						</table>						
						</fieldset>
						<!--End adavance option module -->
					
						</td>
					</tr>
					<tr>
						<td colspan="2" class="messagebox">
							Note: Creating a site at root level may be harmfull for the running sites, It may overwrite some of the files and folder including .htaccess file.
							
						</td>
					</tr>
    				</table>
			</div>			
		</td>
	</tr>
	
	<tr>
   		<td class="heading">
			<input type="submit" value="Generate new site">
			<div id="waitmss" class="message" style="display:inline;" ></div>
   			<input type="hidden" name="process" value="nvsbnewsite">
      			<input type="hidden" name="created_date" value="<?php echo date("Y-m-d") ?>">
   			<input type="hidden" name="temp_name" id="temp_name" value="">
   		</td>
   	</tr>
</table>
</form>
<br>
<?php
	}
	elseif($process=="change_settings")
	{
		$template_info = $nvsb_template->getTemplateById($site_data['temp_id']);
		$temp_name=$template_info['temp_name'];
	
?>              <br>
		<table width="80%"  align="center" cellpadding="4" cellspacing="3" border="0">
			<form name="cnbnewsite" method="post" action="sites.php" onSubmit="return validate(this)">
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="inputform">
	<tr>
   		<td class="heading" colspan="2" width="100%"><?php
		if($process=="change_settings"){ echo "Edit NVSB Site";}?></td>
	</tr>
	<tr>
      		<td>
		<br>	<?php if($process=="change_settings"){?>
      			<table width="100%" cellpadding="0" cellspacing="0">
				
				<tr>
			      		<TD>&nbsp;</TD>
			      		<TD align="right" nowrap="nowrap">Select Template :</TD>
			      		<td><?php echo $nvsb_template->templateList($site_data['temp_id']); ?></td>
			      </tr>
			      <tr><TD colspan="2" height="10px"></TD></tr>
      			</table>
				
      			<div id="mif">
		      		<table  border="0" width="100%" cellpadding="4" cellspacing="0">
					<tr><th colspan="2" height="2px" class="formback2"></TD></tr>
					<tr><d colspan="2" height="10px"></TD></tr>
					<tr>
						<TD colspan="2" align="left" valign="middle">
							<table width="100%">
								<TR>
									<TD align="center" valign="middle">
										<div id="divprev" align="center" style="display:inline;">																				
										<img border="0" src="nvsb_templates/<?php echo $temp_name;?>/datas/desc/screenshot.jpg"/>
										</div>
									</td>
									<td align="left" valign="top">
										<?php $desc = $nvsb_template->gettemplate_decr($temp_name);?>
										<div id="divdesc" align="justify" style="display:inline;"><?php echo $desc; ?></div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					
					<tr><th colspan="2" height="2px" class="formback2"></TD></tr>
					<tr><TD colspan="2" height="10px"></TD></tr>
					<tr>
						<TD width="30%" align="right"> Adsense ID : </td>
						<TD><input type="text" name="adsense_id" value="<?php echo $site_data['adsense_id'];?>" size="30"><br/>
						Format: pub-xxxxx; do not forget the pub-...</td>
					</tr>           	
					<tr>
						<TD align="right">Main Keyword : </td>
						<TD><input type="text" name="main_keyword" value="<?php echo $site_data['main_keyword'];?>" size="30"><br/>
						Example:Flower Gardening
						</td>
					</tr>           	
					
					
					<tr>
						<TD align="right">Tag Cloud Word : </td>
						<TD><input type="text" name="tag_cloud_word" value="<?php echo $site_data['tag_cloud_word'];?>" size="30"><br/>
						We recommend no more than 10 to 15 words. Separate each word with coma.</td>
					</tr>
					
					<tr>
						<TD align="right">Related keywords:</td>
						<TD><select name="related_keywords">
						<?php if($site_data["related_keywords"]=='0'){
							echo "<option selected value='0'>0</option><option value='1'>1</option>";
							} else if($site_data["related_keywords"]=='1'){
							echo "<option value='0'>0</option><option selected value='1'>1</option>";
							}else{
							echo "<option value='0'>0</option><option value='1'>1</option>";
							}
						?>	</select>					
						<br/>"0" will hide this section,"1" will display it.
						</td>
					</tr>
					
					<tr>
						<TD align="right">Usage:</td>
						<TD><select name="usage">
						<?php if($site_data["usage"]=='1'){
							echo "<option selected value='1'>1</option><option value='2'>2</option>";
							} else if($site_data["usage"]=='2'){
							echo "<option value='1'>1</option><option selected value='2'>2</option>";
							}else{
							echo "<option value='1'>1</option><option value='2'>2</option>";
							}
						?>
						</select>
						<br/>1 to use filter videos using mandatory keywords,2 to filter videos using banned keywords</td>
					</tr>
					
					<tr>
						<TD align="right">Mandatory keywords:</td>
						<TD><input type="text" name="mandatory_keywords" value="<?php echo $site_data['mandatory_keywords'];?>" size="50"><br/>
						If you have selected 1 for the usage parameter,enter your mandatory words separated with coma.</td>
					</tr>
					
					<tr>
						<TD align="right">Show_comments:</td>
						<TD><select name="show_comments">
						<?php if($site_data["show_comments"]=='0'){
							echo "<option selected value='0'>0</option><option value='1'>1</option>";
							} else if($site_data["show_comments"]=='1'){
							echo "<option value='0'>0</option><option selected value='1'>1</option>";
							}else{
							echo "<option value='0'>0</option><option value='1'>1</option>";
							}
						?>		
						</select>
						<br/>
						0 to hide the comments,1 to show the comments and enable your vistors to add
comments to your videos.</td>
					</tr>
					
					<tr><td colspan="2" align="center">
						<div id="ftpmsg" class="message" style="display:inline;"></div>
					</td></tr>     
					<?php } elseif($process=="already"){?>
					<div id="mif">
		      		<table  border="0" width="100%" cellpadding="4" cellspacing="0">									
					<?php } ?>
					<tr>
						<td align="right">Existing FTP Server  : </td>
						<td align="left"><select id="ftpserveroption" onchange="displayftpserver()">
						<option value='new_ftp'>--New FTP--</option>
						<?php 
						$sql="select * from ".TABLE_PREFIX."ftp_details_tb where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
						$Ftp_rs = $ms_db->getRS($sql);
						if($Ftp_rs){
							while($Ftp=$ms_db->getNextRow($Ftp_rs))
							{
									if($site_data['ftp_address']==$Ftp["ftp_address"]){
								echo "<option selected value='".$Ftp["ftp_address"]." ".$Ftp["ftp_username"]." ".$Ftp["ftp_password"]."'>".$Ftp["ftp_address"]."(".$Ftp["ftp_username"].")"."</option>";
								}else{
								echo "<option value='".$Ftp["ftp_address"]." ".$Ftp["ftp_username"]." ".$Ftp["ftp_password"]."'>".$Ftp["ftp_address"]."(".$Ftp["ftp_username"].")"."</option>";								
								}
							}
						}
						?>			
					</select> </td>
					</tr>
					      	
					<tr>
						<td align="right">FTP Address  :&nbsp;</td>
						<td align="left">
							<input name="ftp_address" type="text" id="ftp_address" value="<?php echo $site_data['ftp_address'] ?>" size="30" maxlength="255">
						</td>
					</tr>
					<tr>
						<td align="right">FTP Username  :&nbsp;</td>
						<td align="left">
						<input name="ftp_username" type="text" id="ftp_username" value="<?php echo $site_data['ftp_username'] ?>" size="30" maxlength="255">			</td>
					</tr>
					<tr>
						<td align="right">FTP Password  :&nbsp;</td>
						<td align="left">
							<input name="ftp_password" type="password" id="ftp_password" value="<?php echo $site_data['ftp_password'] ?>" size="30" maxlength="255">
						</td>
					</tr>
					<tr>
						<td align="right">FTP Homepage Folder : </td>
						<td align="left">
							<input name="ftp_homepage" type="text" id="ftp_homepage1" value="<?php echo $site_data['ftp_homepage'] ?>" size="50" maxlength="255">		
							<input type="button" name="browse" id="browse" value="Browse" onClick="browseroot(1)"><br>(ex.:/user_folder/public_html/path_to_site_root_folder/)
						</td>
					</tr>
					<tr>
						<td align="right">Sub Folder  :&nbsp;</td>
						<td align="left">
							<input name="sub_folder" type="text" id="sub_folder" value="<?php echo $site_data['sub_folder'] ?>" size="30" maxlength="255">
							<br>//(example, if www.site.com/videos/ is your site, input videos in the above field)
						</td>
					</tr>
					<tr><td colspan="2" align="center"><div id="urlmsg" class="message" style="display:inline;"></div></td></tr>
			     		
					
					<tr>
						<td align="right">URL of the site  :&nbsp;</td>
						<td align="left">
							<input name="url" type="text" id="url" value="<?php echo $site_data['url'] ?>" size="30" maxlength="255">
						</td>
					</tr>
					
					
					<tr>
						<td colspan="2" class="messagebox">
							Note: Creating a site at root level may be harmfull for the running sites, It may overwrite some of the files and folder including .htaccess file.
						</td>
					</tr>
    				</table>
			</div>
			<br>
		</td>
	</tr>
	
	<tr>
   		<td class="heading">
			<input type="submit" value="Update">
			<div id="waitmss" class="message" style="display:inline;" ></div>
   			<input type="hidden" name="process" value="editnvsbsite">
			<input type="hidden" name="cnvb_site_id" value="<?php echo $_GET["id"];?>">
      		<input type="hidden" name="created_date" value="<?php echo date("Y-m-d") ?>">
			<input type="hidden" name="sub_folder" value="<?php echo $site_data["sub_folder"];?>">
   			<input type="hidden" name="temp_name" id="temp_name" value="<?php echo $temp_name; ?>">			
   		</td>
   	</tr>
</table>
</form>
		</table>
		<br>
<?php	
	}
	elseif($process=="manage_template")
	{
		if(isset($_GET['msg']) && $_GET['msg']!="")
		{
	?>
	<br>
	<table width="60%" align="center" border="0"><tr><td align="center"
class="success-message"><?php echo $_GET['msg']?></td></tr></table>
	<?php		
		}
	?>
	<br>
	<table width="60%" align="center" border="0">
		<tr>
			<td align="center">
				<a href="nvsbsites.php?process=new_template">Add New Template</a>
			</td>
		</tr>
	</table>
	
	<?php
 		$template_data = $nvsb_template->getTemplatesAll();
?>
<br><br>
		<table width="80%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary2">
			<tr>
				<th>ID</th>
				<th>Template Name</th>
				<th>Desciption</th>
				<th>Screen Shot</th>
				<th>&nbsp;</th>
			</tr>
			<?php	$i=0;
			while($data = $ms_db->getNextRow($template_data))
			{$i++;
			?>
				<tr>
					<td><?php echo $data['id']?></td>
					<td align="center"><?php echo $data['temp_name']?></td>
					<td align="center" width="30%">
					<?php
						$desc=$nvsb_template->gettemplate_decr($data['temp_name']);
						if($desc==false)
						{
							$desc="No description found for this template";
						}
						echo wordwrap($desc,65,"<br>",1);
					?>
					</td>
					<td align="center">
						<a href="<?php echo $data['url']?>/nvsb_templates/<?php echo $data['temp_name']?>/datas/desc/screenshot.jpg" target="_blank" class="screenshot" rel="<?php echo $data['url']?>/nvsb_templates/<?php echo $data['temp_name']?>/datas/desc/screenshot.jpg"><img src="nvsb_templates/<?php echo $data['temp_name']?>/datas/desc/screenshot.jpg" style="height:70px;width:70px;border:1px solid #EFEFEF;overflow:hidden;"></a>
					</td>
					<td width="20px">
						<img src="images/delete.png" onClick="deltempl(<?php echo $data["id"]; ?>,'<?php echo trim($check['temp_name']);?>')" title="Delete Template">
					</td>					
				</tr>
			<?php
			}
			?>
		</table>
<?php	
	}
	else // start coding for add new templete
	if($process=="new_template") 
	
	{
	
	?>
	
	
	
	
	
	<form name="new_template" method="post" enctype="multipart/form-data" action="nvsbsites.php">
	
	<table cellpadding="4" cellspacing="4" border="0"  height="200" align="center" width="500" class="inputform">
	
	<?php
	
	if($msg)
	
	{?>
	
	<tr class="heading">
	
	<td class="message" nowrap="true" align="center" colspan="2" height="5"> <?php  echo $msg; ?> </td>
	
	</tr>
	
	<br>
	
	<?php }?>
	
	<tr> <TD colspan="2" height="5" class="heading" width="500">Add Templates</TD></tr>	
	
	<tr align="center">
	
	<td align="right" width="200"> &nbsp;Upload Template&nbsp;</td>
	
	<TD align="left"> 
	
	<input type="file" name="upload_temp" size="70">
	
	</TD></tr>
	
	<?php
		$sql = "Select * from ".TABLE_PREFIX."addon_tbl where keyword='nvsb'";
		$nvsb_data = $ms_db->getDataSingleRow($sql);
		
		$sql = "Select * from ".TABLE_PREFIX."addon_tbl where keyword='ncsb'";
		$ncsb_data = $ms_db->getDataSingleRow($sql);
		
		?>
			<tr>
				<td align="right" width="200">Options</td>
				<td>
					<select name="upload_in">
						
						<option value="nvsb" selected="selected">Niche Video Site builder</option>
						
					</select>
				</td>
			</tr>
	
	<tr><td colspan="2" align="center" class="message">
	
	Template should have a "folder container" which includes "datas" folder and other files with "desc" folder to be found inside the "datas" folder with "description.txt" and "screenshot.jpg" inside 
	
	</td></tr>
	
	<?php  $_SESSION['upload_template']="upload_sucess"; ?>
	
	<input type="hidden" name="process" value="upload_template">
	
	<tr > <TD colspan="2" height="5" class="heading" width="500"><input type="submit" value="Upload Template" id="yes" onClick="return validat();"></TD></tr>	
	
	</table>
	
	</form>
	
	<br>
	
	<?php } 
	// end of coding for add new templete
	else
	{
	
?>      
	<?php
		if(isset($_GET['msg']) && $_GET['msg']!="")
		{
	?>
	<br>
	<table width="60%" align="center" border="0"><tr><td align="center" class="success-message"><?php echo $_GET['msg']?></td></tr></table>
	<?php		
		}
	?>
	
	<br>
	<table width="80%"  border="0" cellspacing="1" cellpadding="1" class="summary">
		<tr>
			<th width="15" >ID.</th>
			<th><a  class="menu"  href="?sort=created_date">Date Created</a></th>
			<th nowrap="nowrap"><a  class="menu" href="?sort=title">Template Used</a></th>
			<th><a class="menu" href="?sort=main_keyword">Main Keyword</a></th>
			<th><a class="menu" href="?sort=url">Site URL</a></th>
			<th width="19">&nbsp;</th>
			<th width="19">&nbsp;</th>
			<th width="19">&nbsp;</th>
			<th width="19">&nbsp;</th>
		</tr>
	<?php
		if ($sites_rs)
		{	$pos = $pg->startpos + 1;
			$tblmat = 1;
			while ($site = $ms_db->getNextRow($sites_rs))
			{
				$template_info = $nvsb_template->getTemplateById($site["temp_id"]);
	?>
				<tr>
					<td><?php echo $site['id'];?></td>
					<td align="center"><?php echo $site["date_created"];?></td>
					<td align="center">
					<?php if($template_info["temp_name"]!=""){?>
						<a href="<?php echo $site["url"];?>"><img src="nvsb_templates/<?php echo trim($template_info["temp_name"]);?>/datas/desc/screenshot.jpg"></a>
					<?php } else {?>	
						<a href="<?php echo $site["url"];?>"><img src="<?php echo $site["url"];?>/datas/desc/screenshot.jpg"></a>
					<?php } ?>
					</td>
					<td align="center"><?php echo $site["main_keyword"];?></td>
					<td align="center">
						<a href="<?php echo $site["url"];?>" target="_blank"><?php echo $site["url"];?></a>
					</td>
					<td>
						<a href="nvsbsites.php?process=change_settings&id=<?php echo $site['id'];?>">
							<img title="Change Settings" alt="change settings" src="images/edit.png">
						</a>				
						
					</td>
					<td>						
						<a href="nvsbsites.php?process=delete_site&id=<?php echo $site['id'];?>">
							<img title="Delete NVSB site" alt="Delete NVSB site" src="images/delete.png">
						</a>
						
					</td>
					<td>
						<a href="nvsbupdate_template.php?&siteid=<?php echo $site['id'] ?>&page=<?php echo $pg->page?>&temp_id=<?php echo $site['temp_id'] ?>" title="Change Template" target="_blank">
							<img title="Change Template" alt="change template" src="images/template.png">
						</a>
						
					</td>
					<td>

					<?php

					if ($site['wp_installed'] == "Y") {	

				

					/*echo "<td>";

					$linkmiddle = " title='Install Wordpress Themes' ><img src='images/wpinstalltheme.gif'";

					$linkhref = "wp_theme_install.php";				

					$linkstart = "<a href='$linkhref?site_id=".$rid."&page=".$pg->page."'";

					$linkend = " border='0'></a>" ;

					echo $linkstart.$linkmiddle.$linkend."</td>";*/

					

					$linkmiddle = " title='Install Wordpress Themes' ><img src='images/wpinstalltheme.gif'";

					$sql=$ms_db->getRS("select id as blogid from bmp_blogs where site_id='".$site['id']."'");

					$blogresult = $ms_db->getNextRow($sql);

					$linkhref = "./bmp/presentation.php";				

					$linkstart = "<a href='$linkhref?m=mb&blog_id=".$blogresult["blogid"]."'";

					$linkend = " border='0'></a>" ;

					echo $linkstart.$linkmiddle.$linkend;

				}

				else if ($site['wp_installed'] == "N" || $site['wp_installed'] == "" ) {				

					/*echo "<td>";				

					$linkmiddle = " title='Install Wordpress' ><img src='images/wpinstall.gif'";

					$linkhref = "wp_install.php";

					$linkstart = "<a href='$linkhref?site_id=".$rid."&page=".$pg->page."'";

					$linkend = " border='0'></a>" ;

					echo $linkstart.$linkmiddle.$linkend."</td>";*/

						

					$linkmiddle = " title='Install Wordpress' ><img src='images/wpinstall.gif'";

					//$linkhref = "wp_install.php";

					$linkhref = "./bmp/create_blog.php?m=cb&st=nv";

					//$linkstart = "<a href='$linkhref&site_id=".$rid."&page=".$pg->page."'";

					$linkstart= "<a href='$linkhref&site_id=".$site['id']."'";

					$linkend = " border='0'></a>" ;

					echo $linkstart.$linkmiddle.$linkend;	

					}

					?>

					</td>
				</tr>
	<?php
			}
		}
	?>
	</table>
<?php
	}
?>
<?php 

	
?>

<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>
