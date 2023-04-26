<?php 


	
	if(isset($_POST['Submit']) && $_POST['Submit']=="Submit")
	{	
		echo $matter=htmlentities($_POST['rte1']);
		/*$sql = "INSERT INTO TABLENAME (content) values('".$matter."')";
		mysql_query($sql);*/
	}
	$content="this is test content";
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript" type="text/javascript" src="jscripts/rte/html2xhtml.js"></script>
<script language="JavaScript" type="text/javascript" src="jscripts/rte/richtext_compressed.js"></script>
<script language="JavaScript" type="text/javascript" src="jscripts/rte/richtext.js"></script>
<script language="javascript">


function chk()
{
	
	updateRTEs(rte1);
// 	toggleHTMLSrc('rte1',true,true);
// 	toggleHTMLSrc('rte1',true,true);

		return true;
}


</script>
<title>No.6 Restaurant Padstow Cornwall</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>

<body>
<form method="post" action="" onsubmit="chk();">
      <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td><script language="javascript">
												initRTE("jscripts/rte/images/", "jscripts/rte/", "", true);
												var message = new richTextEditor('rte1');
												message.html="<?php echo addslashes(str_replace(array("\n","\r","'"),array("","","&#39;"),$content));?>";
												message.toolbar1=true;
												message.toggleSrc = true;
												message.width=620;
												message.height=228;
												message.build();
												</script></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                </tr>
              <tr>
                
                <td align="center"><input name="Submit" type="submit"value="Submit"></td>
              </tr>
            </table>
            </form>
   

</body>
</html>
