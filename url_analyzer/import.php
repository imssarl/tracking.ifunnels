<?php
/*if((isset($_REQUEST['upload_article']) && ($_REQUEST['upload_article']!="")))
{
         $filename=$_FILES['import_article']['name']; 
         $tempfilename=$_FILES['import_article']['tmp_name'];
         $dir="uploadedarticles/";
	     $attchfile=$dir.$filename;  
         if(move_uploaded_file($tempfilename,$attchfile))
	     {  
           //$contents = addslashes(str_replace(array("\n","\r"),"",implode("",file($attchfile))));
		   $content=implode("",file($attchfile));
		   $content=str_replace(array("\n","\r","\t"),"",$content);
		   $content=str_replace("'","&#39;",$content);
		   //echo "<pre>$contents</pre>";
		 }
		 ?>
		    <script language="javascript">
		 	var win = window.opener;
			win.document.article_frm.txt_Description.value = "<?php echo $contents; ?>";

		 </script>
		 <?php
         //echo"<li>".$contents;

}*/


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>
<body>
<form name="import_frm" method="post" action="article.php" enctype="multipart/form-data" >
  <table width="70%" border="0" align="center">
    <tr>
      <td>You have to just Browse the file path and click on upload button.</td>
    </tr>
    <tr>
      <td>Import Article <input type="file" name="import_article" size="54">&nbsp;
        <input type="submit" name="upload_article" value="Upload Article"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
