<link rel="stylesheet" type="text/css" href="stylesheets/csshorizontalmenu.css" />
<script type="text/javascript" src="jscripts/csshorizontalmenu.js">
</script>
<script language="javascript">
function openhelp(url)
{
//	window.open(url, "<?php// echo SITE_TITLE ?>","height=500,width=400,status=yes,toolbar=no,menubar=no,location=no");
window.open(url,"AdspyPro","height=500,width=450,status=yes,toolbar=no,menubar=no,location=no, scrollbars=yes, resizable=yes");
}
</script>
<div class="horizontalcssmenu">
<ul id="cssmenu1">
	<li style="border-left: 1px solid #202020;"><a href="index.php">Home</a></li>
	

	<li><a href="#">My Campaigns</a>
    	<ul>
    		<li><a href="campaign.php">Manage</a></li>
    	</ul>
		<?php
			if(isset($_SESSION[SESSION_PREFIX.'sessionuser']))
			{
		 ?>
		<li style="border-left: 1px solid #202020;"><a href="logout.php">Logout</a></li>
		
		<?php
			}
		?>
</ul>
<br style="clear: left;" />
</div>

