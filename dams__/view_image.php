<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<style>
.closed{
     font-family: Arial, Helvetica, sans-serif;
     font-size: medium;
     color: #3A72A1;
     font-weight: bold;
     margin: 0px;
     padding: 0px;
  }
.closed:hover{
  color: #FDD000;
    text-decoration: none;
}
</style>
<script language="javascript">

</script>
</head>

<body><br><br><br><br><br>
		<table align="center" border="0">
		<tr>
		    <td><img src="<?php echo $_GET['imgpath']; ?>"></td>
		</tr>	
		<tr>
		    <td align="right">
			<a  href="javascript:window.close();" class="closed">Close</a>
			</td>
		</tr>	
		</table>
			

</body>
</html>
<script language="javascript">
function opens(path)
{
 window.open(path,'abc','height=340,width=600,menubar=no,toolbar=no,resizable=yes');
alert(path);
}
</script>