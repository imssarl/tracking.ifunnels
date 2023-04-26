<?php
session_start();
if(!file_exists("config/config.php"))
{
	header("location:installer.php");
	exit();
}

require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/profile.class.php");

$asm_db = new Database();
$asm_db->openDB();
$profile = new Profile();

$profile_count = $profile->getProfileCount();
$article_count = $profile->getArticleCount();
$sarticle_count = $profile->getSubmmitedArticleCount();
$particle_count = $profile->getPendingArticleCount();
$rarticle_count = $profile->getRejectedArticleCount();
?>
<?php

require_once("header.php");
?>
<link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
<?php
require_once("inc_menu.php");
?>
	<table width="40%" align="center" class="summary2">
	     <tr>
			<td valign = "top" align="center" class="heading" colspan="2">Summary</td>
		</tr>
		<tr>
			<td align="center" colspan="2" style="font-weight:bold;">Number of articles in the system</td>
		</tr>
            <TR>
                <TD>Total Number of Articles</TD>
                <TD><?php echo $article_count;?></TD>
            </TR>
            <TR>
                <TD>Total Number of Submitted Articles</TD>
                <TD><?php echo $sarticle_count;?></TD>
            </TR>
            <TR>
                <TD>Total Number of Pending Articles</TD>
                <TD><?php echo $particle_count;?></TD>
            </TR>
            <TR>
                <TD>Total Number of Rejected Articles</TD>
                <TD><?php echo $rarticle_count;?></TD>
            </TR>
			<tr><td align="center" colspan="2" style="font-weight:bold;">Number of Profiles in the system</td></tr>
			<TR>
                <TD>Total Number of Profiles in the System</TD>
                <TD><a href="manage_profile.php"><?php echo $profile_count;?></a></TD>
            </TR>
        </table><br /><br />
<table width="80%" align="center">
<tr><td width="200">&nbsp;</td><td>
<h3>Get started:</h3>
Step 1 - <a href="manage_profile.php?process=create">Create at least one profile</a>. <br /><br />
Step 2 - <a href="manage_directory.php">Enter your article directory login information</a>. <br /><br />
Step 3 - <a href="manage_article.php?process=add">Add article</a> or <a href="manage_article.php?process=import">mass import</a> a set of articles.<br /><br />
Step 4 - <a href="manage_article.php">Publish article</a>. <br /><br />
Step 5 - <a href="manage_submission.php">View submission status</a>. <br /><br />

</td></tr>
</table>	
<?php require_once("footer.php");?>