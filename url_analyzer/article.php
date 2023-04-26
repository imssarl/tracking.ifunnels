<?php
include("include/config.php");
if((isset($_REQUEST['upload_article']) && ($_REQUEST['upload_article']!="")))
{
         $filename=$_FILES['import_article']['name']; 
         $tempfilename=$_FILES['import_article']['tmp_name'];
         $dir="uploadedarticles/";
	     $attchfile=$dir.$filename;  
         if(move_uploaded_file($tempfilename,$attchfile))
	     {  
           $contents = implode("",file($attchfile));
         }
        
}
if((isset($_REQUEST['server_article']) && ($_REQUEST['server_article']!="")))
{        
      
         //$dir_name="Test1";  
         //mkdir("uploadedarticles/".$dir_name, 0700);
		 $filename=$_FILES['import_dir']['name'];
		 $tempfilename=$_FILES['import_dir']['tmp_name'];
		 //echo "temp_file_name".$tempfilename;
         $dir="uploadedarticles/";
	     $attchfile=$dir.$filename;  
         if(move_uploaded_file($tempfilename,$attchfile))
	     {  
           $contents = implode("",file($attchfile));
         }
        
}

$includes_dir = opendir("uploadedarticles/");
$directory_list = array();
$int_files=array();
while (($inc_file = readdir($includes_dir))!=false)
	   {
		if((is_dir($inc_file)==false))
			 {
			  // if(!(strstr($inc_file,".txt")))  
				//{	
				 $directory_list[]=$inc_file;
			     //echo "<LI>list of Directory::".$inc_file;
				//} 
			 }	   
		}	   
		

		
     /*  foreach($directory_list as $newfiles)
		 {  
		   $newfiles="uploadedarticles/".$newfiles."/";
		   //echo "<li>".$newfiles;
		   $includes_new_dir = opendir($newfiles);
		   while (($inc_new_file = readdir($includes_new_dir))!=false)
           {
		   if((strstr($inc_new_file,".txt")))  
		    {
		    $int_files[]=$inc_new_file;
	        // echo "<LI>list of txt files::".$inc_new_file;
	       } 
		   }

		 }*/
if((isset($_POST['hidden_server_frm']) && ($_POST['hidden_server_frm']=="server_frm")))
{	
$folder_name=$_POST['select_directory'];

 if((strstr($folder_name,".txt")))  
	{
	$folder_name="uploadedarticles/".$folder_name;
	$handle_file=fopen($folder_name,"rb");
	$file_content=fread($handle_file, filesize($folder_name));
	fclose($handle_file);
	} 
//this code is for selecting no of files from the list
if((isset($_POST['select_file']) && ($_POST['select_file']!="")))
{
$file_content="";
$file_array=$_POST['select_file'];
//$config_allowed_file=5;
//$config_file_size_kb=20;
$size_of_file=($config_file_size_kb*1024);
$total_files_for_process="";
//====logic for restricting allowed number of files:
if(count($file_array)<$config_allowed_file)
{
$varcount=count($file_array);
}
else
{
$varcount=$config_allowed_file;
}
//====logic for calculating all file contents:
for($count=0;$count<$varcount;$count++)
{
	if($file_array[$count]!="-1")
	{
		$handle_file=fopen($file_array[$count],"rb");
		$sub_file_content=fread($handle_file, filesize($file_array[$count]));
		fclose($handle_file);
	    $file_size=($file_size + filesize($file_array[$count]));
	    if($file_size > $size_of_file)
	    {
	      break;
	    }
		$varTemp=$file_array[$count];
		$vararry=explode("/",$varTemp);
		foreach($vararry as $val)
		{
			if(strpos($val,".txt",1)>0)
			{
		     $var_file_name=$val;
			
			}
			
		}
		
	    if($total_files_for_process=="")
		{
		$total_files_for_process=$var_file_name;
	    }
		else
		{
		$total_files_for_process=$total_files_for_process.",".$var_file_name;
		
		}
		$file_content=$file_content ."\n".$sub_file_content;
   }
}
}	 
}   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Analyse Aricle</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {color: #990000}
-->
</style>
<script language="javascript" type="text/javascript">
function collect_files()
{
document.server_frm.submit();
}
function hide(myid)
	{
	 document.getElementById(myid).style.visibility="Hidden";
	 document.getElementById(myid).style.display="none";
	}
                  
function unhide(myid)
{
	document.getElementById(myid).style.visibility="Visible"
	document.getElementById(myid).style.display="";
}	
function hh_all()
{
	var ctrl = document.server_frm.elements["select_file[]"];
for(i=0;i<ctrl.length;i++)
{
ctrl[i].selected=true;
}
}	 
</script>
</head>
<body>
<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
 <tr>
    <td height="70" colspan="2" class="header"><?php include("header.php");?> </td>
  </tr>
  
 <tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
     <td bgcolor="#F4F4F4">&nbsp;</td>
     <td bgcolor="#F4F4F4">&nbsp;</td>
     <td background="images/menubar.jpg">&nbsp;</td>
   </tr>
   <tr>
     <td width="4%" bgcolor="#F4F4F4">&nbsp;</td>
     <td width="84%" bgcolor="#F4F4F4"><a href="index.php">Home </a><br>
       <a href="" onClick="javascript:history.back()">Back </a><br>
       <a href="#">More Tools </a><br>
       <a href="readme.php">Help </a><br>
       <a href="#">Default settings </a><br>
       <a href="#">Simple Mode </a><br>
       <a href="#">Privacy Policy </a><br>
       <a href="#">Version History </a><br>
       <a href="#">Add this tool to your Site <br>
       <br>
       </a></td>
     <td width="12%" background="images/menubar.jpg">&nbsp;</td>
   </tr>
 </table></td>
 <td valign="top">
<table  style="margin-top:15px;"width="98%" border="0" cellpadding="5" cellspacing="0" class="border" >
<tr valign="bottom">
      <td valign="top"><table width="53%" border="0" cellpadding="8" cellspacing="0">
        <tr>
          <td>
            <input name="choose_type" type="radio" value="import_option" onclick="unhide(1);hide(2);" checked>          </td>
          <td class="headingsmall" >Import Article</td>
          <td >
            <input name="choose_type" type="radio" value="server_option" onclick="hide(1);unhide(2);" >          </td>
          <td class="headingsmall" >Server Article</td>
        </tr>
      </table></td>
    </tr>
    
    <tr id="1">
	 <form name="import_frm" method="post" action="article.php" enctype="multipart/form-data" >
      <td valign="top" ><span class="heading">Import Article </span><input name="import_article" type="file" size="54">
      &nbsp;
        <input name="upload_article" type="submit" class="button" value="Upload Article"></td>
		</form>
    </tr>
    <form name="server_frm" method="post" action="article.php">
	<input type="hidden" name="hidden_server_frm" value="server_frm">
	 <tr id="2">
	 <td valign="top">
	 <table width="100%" border="0" align="left" cellpadding="5" cellspacing="0">
           <tr>
		   <td width="17%" valign="top" class="heading"> Server Article</td>
           <td width="26%"  valign="top" class="heading">
          <select name="select_directory" id="select_directory">
          <option value="-1">#Select Directory#</option>
          <?php foreach($directory_list as $newfiles)
				 {  
				  $ext=explode('.',$newfiles);
                   if($newfiles!= '.' && $newfiles!= '..' && $ext[1])
                    {
                     $sub_dir_name=$newfiles;
				    }
					
				   else
				    {
					$sub_dir_name="[DIR]-".$newfiles;
				    }
				 ?>
				 <option value="<?php echo $newfiles;?>" onClick="javascript:collect_files();"><?php echo $sub_dir_name;?></option>
				<?php } ?>
         </select> </td>
		 
		 <td width="57%"  align="left">
          <select name="select_file[]" size="4" style="width:150px" multiple="multiple">
		   <option value="-1" onClick="javascript:hh_all();">#Select All#</option>
           <?php   
		          if($folder_name!="")
				   {
					 $newfiles="uploadedarticles/".$folder_name."/";
					 $includes_new_dir = opendir($newfiles);
					 while (($inc_new_file = readdir($includes_new_dir))!=false)
					 {
					 if((strstr($inc_new_file,".txt")))  
					 {
					 $int_files[]=$inc_new_file;
					 ?>
					  <option value="<?php echo $newfiles."".$inc_new_file;?>"><?php echo $folder_name."::".$inc_new_file;?></option>
					 <?php 
					 } 
					}
				 }
		  ?>
          </select>
		   <input name="server_article" type="submit" class="button" value="Process Articles"> </td>
        </tr></table></td>
	 </tr>
	</form>
   </table> </td>
 </tr>
 <form name="article_frm" method="post" action="art_action.php">
  <tr>
    <td width="26%" rowspan="2" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="88%" align="right" valign="top" bgcolor="#F4F4F4"><table width="95%"  border="0" cellspacing="0" cellpadding="5">
          
          <tr>
            <td height="1" align="left" class="grayline"> </td>
          </tr>
          <tr>
            <td align="left"> Items in grey fields <br>
do not affect keyword density calculation  </td>
          </tr>
          <tr>
            <td height="1" align="left" class="grayline"> </td>
          </tr>
          <tr>
            <td align="left"> <strong>Report options </strong> </td>
          </tr>
          <tr>
            <td height="1" align="left" class="grayline"> </td>
          </tr>
          <tr>
            <td align="left"> Do not show words less then <br>
              <input name="txt_DontShowLessWords" type="text" value="2" size="5">
              characters </td>
          </tr>
          <tr>
            <td height="1" align="left" class="grayline"> </td>
          </tr>
          <tr>
            <td align="left"> <input type="checkbox" name="chk_Title" value="on" checked>
              Show Title </td>
          </tr>
          <tr>
            <td height="1" align="left" class="grayline"> </td>
          </tr>
          <tr>
            <td align="left">&nbsp; </td>
          </tr>
          <tr>
            <td height="1" align="left" class="grayline"> </td>
          </tr>
          <tr>
            <td align="left"> Show all words <br>
OR Show keywords <br>
occuring at least 
<input name="txt_occuratleast" type="text" value="2" size="5">
times </td>
          </tr>
          <tr>
            <td align="left">&nbsp; </td>
          </tr>
          <tr>
            <td height="1" align="left" class="grayline"> </td>
          </tr>
          <tr>
            <td align="left"> Maximum keyword density <br>
              <input name="txt_Maxdensity" type="text" value="55.0" size="8">
              % </td>
          </tr>
          <tr>
            <td height="1" align="left" class="grayline"> </td>
          </tr>
          <tr>
            <td align="left">Minimum keyword density <br>
              <input name="txt_Mindensity" type="text" value="0.0" size="8">
              % </td>
          </tr>
          <tr>
            <td align="left">&nbsp;</td>
          </tr>
        </table></td>
        <td width="12%" background="images/menubar.jpg">&nbsp;</td>
        </tr>
    </table></td>
    <td valign="top" class="heading">
      <table width="98%"  border="0" cellpadding="3" cellspacing="0" class="border">
         <?php if($total_files_for_process!="")
		 {?>
		 <tr>
           <td>&nbsp;</td>
          <td><?php echo"Processed files::".$total_files_for_process;?></td>
        </tr>
		 <tr>
           <td>&nbsp;</td>
          <td><?php echo "Total File Size::".round(($file_size/1024),2)."Kb";?></td>
        </tr>
		<?php } ?>
         <tr>
           <td>&nbsp;</td>
          <td>Title</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><p>
            <input name="txt_Title" type="text" class="inputbox" id="txt_Title" value="Title of Article" size="70">
          </p>            </td>
        </tr>
       <!-- <tr>
          <td>summary</td>
        </tr>
        <tr>
          <td><input name="txt_Summary" type="text" id="txt_Summary" size="70" maxlength="256"></td>
        </tr>-->
        <tr>
          <td>&nbsp;</td>
          <td> Description</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><textarea name="txt_Description" cols="70" rows="12" id="txt_Description"  maxsize=24549><?php echo $contents; echo $file_content;?></textarea></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      <tr>
        <td>&nbsp;</td>
          <td><input name="OptDensity" type="radio" value="DensityByUniqueWords" checked>
 Density = Occurence / Unique words   <br>
            <input name="OptDensity" type="radio" value="DensityByTotalWords"> 
 Density = Occurence / Total words</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <div align="left">
              <input name="Submit" type="submit" class="button" value="Submit">
            </div></td>
        </tr>
        
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="chk_stopWords" value="on" checked >
Check to Ignore StopWords from <a href="temp/ingore.txt">default</a> list <br>
<b> or</b> specify stopwords below:<br>
<textarea name="textarea" cols="60" rows="7" class="inputbox"></textarea></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="chk_poisonWords" value="on" checked >
Set custom poisonwords (otherwise default list is used) <br>
<textarea name="textarea2" cols=60 rows=7 class="inputbox"></textarea></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="chk_adultWords" value="on" checked >
            Set custom adultwords (otherwise default list is used) <br>
            <textarea name="textarea3" cols=60 rows=7 class="inputbox"></textarea></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <label></label>      </td>
  </tr>
  
  <tr>
    <td valign="top" class="heading">&nbsp;</td>
  </tr>
  <tr>
    <td height="3" colspan="2" class="grayline"> </td>
  </tr>
  <tr align="center" bgcolor="#F4F4F4">
    <td height="50" colspan="2" bgcolor="#F4F4F4"> <table width="70%"  border="0" align="right" cellpadding="0" cellspacing="0">
     <tr>
	 <td > <div align="center">&copy;Kalptaru Infotech Ltd.</div></td>
	</tr>

    </table> </td>
  </tr>
</form>  
<script language="javascript">
<?php if($_POST['hidden_server_frm']=="server_frm")
{?>
hide(1);unhide(2);
<?php 
} else {?>
unhide(1);hide(2);
<?php }?>
</script>
</table>
</body>
</html>
