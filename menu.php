 <?php 
 	if ((isset($_SESSION['CP_SESS_sessionuserid']) &&
$_SESSION['CP_SESS_sessionuserid'] != ""))
	{
?>
		<ul>
		<li class="active"><a href="<?=SERVER_PATH?>index.php" title="Home">Home</a></li>
		<li><a href="/user-settings/" title="Page Link">Display Options</a></li>
		<li><a href="/tutorials-and-how-to-videos/" title="Page Link">Tutorials and How-To Videos</a></li>
		<li><a href="http://creativenichemanager.zendesk.com/" title="Support" target="_blank">Support</a></li>
		<li><a href="http://creativenichemanager.feedbackhq.com/" title="Forums" target="_blank">Forums, Suggestions & Feedbacks</a></li>
		<li><a href="/logoff/" title="Page Link">Logout</a></li>
		</ul>
<?php
	}
?>