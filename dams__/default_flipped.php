<?php
	session_start();
	require_once("config/config.php");
	require_once("classes/database.class.php");


	$damp_db = new Database();
	$damp_db->openDB();
	
	$field=$_GET['field'];
	//echo "var field='".$field."';";

?>
<html>
<HEAD>
<link href="stylesheets/style1.css" rel="stylesheet" type="text/css" />
<TITLE></TITLE></HEAD>
<BODY>
<script language="JavaScript">
<?php
	echo "var field='".$field."';";
?>
</script>
<script language="JavaScript">
function getSelectedItem()
{
	if(document.select_source_form.select_file.length==undefined)
	{ 
		window.opener.setFlippedFile(field, document.select_source_form.select_file.value);
		self.close();
	}
	else
	{
		for(var i=0;i<document.select_source_form.select_file.length;i++)
		{
			if(document.select_source_form.select_file[i].checked==true)
			{
				window.opener.setFlippedFile(field, document.select_source_form.select_file[i].value);
				self.close();
			}
		}
	}
	return false;
}

</script>
<Table><TR><TD>&nbsp;</TD></TR></Table>
<table width="100%" cellpadding="5" cellspacing="0" align="center" border="1" class="summary2">
	<TR>
		<TD>
			Select Image...
		</TD>
	</TR>
	<tr>
		<TD>
	<form name="select_source_form">
		<table width="100%" cellpadding="5" cellspacing="0" align="center" border="1">
				<?php
					$dir=ROOTPATH."flipped_images";
					if (is_dir($dir))
					{
						if ($dh = opendir($dir))
						{
							while (($file = readdir($dh)) !== false)
							{
								if(!is_dir($file))
								{
									
									echo "<tr>";
										echo "<td width='15%'><input type='radio' id='select_file' name='select_file' value='".$file."'></td>";
										echo "<td width='85%'><img width='100' src='flipped_images/$file'></td>";
										echo '<td class="formtextback2" colspan="2" align="right">';
											echo '<input type="button" value="Select" onClick="getSelectedItem()" />';
										echo '</td>';
									echo "</tr>";
								}
							}
							closedir($dh);
						}
					}
				?>
			<tr class="formtextback2">
				<td class="formtextback2" colspan="2" align="right">
					<input type="button" value="Select" onClick="getSelectedItem()" />
				</td>
			</tr>
		</table>
	</form>
		
		</TD>
	</tr>
</table>
</BODY>
</html>