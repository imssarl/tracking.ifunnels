<?php



session_start();

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "keyword_form")) 

{

	header("Location:url.php");



/*	if ((isset($_POST["selectoption"])) && ($_POST["selectoption"]!=""))

	{

		$selected_option=$_POST["selectoption"];

		if($selected_option=="analyze_article")

		{

			header("Location:article.php");

		}

		if($selected_option=="analyze_url")

		{

			header("Location:url.php");

		}

	}*/

}

?>



<?php include("header.php");?>



<?php include("top.php");?>



<table width="100%"  border="0" cellpadding="0" cellspacing="0">

	<tr>

		<!--This is first column-->

		<!--menu-->

		<!--<td  width = "22%" bgcolor="#F4F4F4" style="padding-left:8px;padding-top:10px;padding-bottom:10px;">			 

			&raquo;<a href="index.php">&nbsp;&nbsp;Home</a><br>

			<br>&raquo;<a href="#" onClick="javascript:history.back()">&nbsp;&nbsp;Back</a><br>

			<br>&raquo;<a href="#">&nbsp;&nbsp;More Tools</a><br>

			<br>&raquo;<a href="readme.php">&nbsp;&nbsp;Help</a><br>

			<br>&raquo;<a href="settings.php">&nbsp; Default settings</a><br>

			<br>&raquo;<a href="#">&nbsp;&nbsp;About Us</a> <br>

			<br>&raquo;<a href="#">&nbsp;&nbsp;Privacy Policy</a><br>

			<br>&raquo;<a href="#">&nbsp;&nbsp;Version History</a> <br>

			<br>&raquo;<a href="#">&nbsp;&nbsp;Add this tool to your Site</a> 

			<br>

	  </td>
-->
		

		

		<!--This is second column-->

		<!--menuBar-->

	 <!-- <td  width = "4%" background="images/menubar.jpg" style="background-repeat:repeat-y; width:10px;text-align:center;">&nbsp;	  </td>
-->
		

		

		<!--This is Third column -->		

		<!--Content area-->

		<td width = "74%" align="left" valign="top" class="heading">

			<table width="100%" border="0" cellspacing="0" cellpadding="0">

			<tr>

				<td height="40" class="heading1">&nbsp;</td>

			</tr>

			<tr>

				<td>

				<form name="keyword_form" method="post" action="">

					<table width="60%" border="0" align="center" cellpadding="5" cellspacing="1" class="border">

						<tr>

							<td align="center" height="30" bgcolor="#006699" class="whiteheading">Welcome to URL Analyzer</td>

						</tr>

						<tr><td>&nbsp;</td></tr>

						<!--<tr>

							<td class="normaltext"><label>

								<input name="selectoption" type="radio" class="normaltext" value="analyze_article" checked>

									Analyze Articles</label>

								<p><label><input name="selectoption" type="radio" value="analyze_url">Analyze URL </label>

								<br><br><label></label>

							</td>

						</tr> -->

						<tr>

							<td height="30" align="center"><label>

								<input name="Submit" type="submit" class="button" value="Enter">

								<input type="hidden" name="MM_insert" value="keyword_form">

								</label>

							</td>

						</tr>

						<tr><td>&nbsp;</td></tr>

					</table>

				</form>

				</td>

			</tr>

			<tr>

				<td>&nbsp;</td>

			</tr>

		</table>	

	</td>

	</tr>	



</table>  

<?php include("../bottom.php"); ?> 