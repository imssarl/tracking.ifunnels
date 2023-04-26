<?php
	session_start();
	require_once("config/config.php");
	require_once("classes/database.class.php");
	require_once("classes/sound.class.php");

	$damp_db = new Database();
	$sound_obj = new Sound();
	$damp_db->openDB();
	
	$field=$_GET['field'];
	
	$sound_Data = $sound_obj->getAllSound();
	
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
	for(var i=0;i<document.select_source_form.select_file.length;i++)
	{
		if(document.select_source_form.select_file[i].checked==true)
		{
			window.opener.setSoundFile(field, document.select_source_form.select_file[i].value, document.select_source_form.select_id[i].value);
			self.close();
		}
	}
	return false;
}

</script>
<Table><TR><TD>&nbsp;</TD></TR></Table>
<table width="100%" cellpadding="5" cellspacing="0" align="center" border="1" class="summary2">
	<TR>
		<TD>
			Select Sound...
		</TD>
	</TR>
	<tr>
		<TD>
	<form name="select_source_form">
		<table width="100%" cellpadding="5" cellspacing="0" align="center" border="1">
				<?php
					if($sound_Data!==false)
					{
						$count=0;
						while($row=$damp_db->getNextRow($sound_Data))
						{
							echo "<tr>";
							echo "<td width='15%'><input type='radio' id='select_file' name='select_file' value='".$row['title']."'></td>";
							echo "<td width='85%'>Title:".$row['title']."<br>Description:".$row['description']."<br>Date Uploaded:".$row['date_uploaded']."</td>";
							echo "<input type='hidden' id='select_file_name_".$count."' value='".$row['id']."'>";
							echo "<input type='hidden' id='select_id' value='".$row['id']."'>";
							echo '<td class="formtextback2" colspan="2" align="right">';
								echo '<input type="button" value="Select" onClick="getSelectedItem()" />';
							echo '</td>';
							echo "</tr>\n";
							$count++;
						}
					}
				?>
			<!--<tr class="formtextback2">
				<td class="formtextback2" colspan="2" align="right">
					<input type="button" value="Select" onClick="getSelectedItem()" />
				</td>
			</tr>-->
		</table>
	</form>
		
		</TD>
	</tr>
</table>
</BODY>
</html>