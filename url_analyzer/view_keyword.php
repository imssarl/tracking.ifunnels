<?php
if((isset($_POST['word_list']) && ($_POST['word_list']!="")))
{
$keyword_list=$_POST['word_list'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<script language="javascript" type="text/javascript">
var win = window.opener;
function replaceAll(oldStr,findStr,repStr) {
var srchNdx = 0; // srchNdx will keep track of where in the whole line
// of oldStr are we searching.
var newStr = ""; // newStr will hold the altered version of oldStr.
while (oldStr.indexOf(findStr,srchNdx) != -1)
// As long as there are strings to replace, this loop
// will run.
{
newStr += oldStr.substring(srchNdx,oldStr.indexOf(findStr,srchNdx));
// Put it all the unaltered text from one findStr to
// the next findStr into newStr.
newStr += repStr;
// Instead of putting the old string, put in the
// new string instead.
srchNdx = (oldStr.indexOf(findStr,srchNdx) + findStr.length);
// Now jump to the next chunk of text till the next findStr.
}
newStr += oldStr.substring(srchNdx,oldStr.length);
// Put whatever's left into newStr.
return newStr;
}


function Submitlist_View()
{
var c_value = "";
//var final_content="";
for (var i=0; i< win.document.form1.q.length; i++)
  {
       if (win.document.form1.q[i].checked)
	   {  
			if (c_value =="")
			{
			c_value = win.document.form1.q[i].value;
			c_value=replaceAll(c_value ,"_"," ");
			} 
			else
			 {
			 c1_value = win.document.form1.q[i].value;
			 c1_value=replaceAll(c1_value ,"_"," ");
			 c_value = c_value + "<br>" + c1_value;
			 }	   
	  }
  }
document.write("<B>List of selected keywords</b><br>");  
document.write(c_value);
}



</script>

</head>

<body onload="javascript:Submitlist_View();">
<form name="view_keyword_frm" method="post" action="">
  <table width="100%" border="0">
    <tr>
      <td><table width="80%" border="0">
        <tr>
          <td><p>&nbsp;</p>
            </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Keyword List View</td>
        </tr>
        <tr>
          <td>
           <!-- <textarea name="textfield" cols="60" rows="8" ><?php  echo $keyword_list; ?></textarea>
			
		  -->  </td>
        </tr>
        <tr>
          <td><!--<input type="button" name="submit_list" value="View Keywords"  onClick="javascript:Submitlist_View();"/>
		     <input type="hidden" name="word_list" value=""> --></td>
        </tr>
      </table></td>
    </tr>
	
  </table>
</form>
</body>
</html>
