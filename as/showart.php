<?php

	//PHP code will come here

require_once("config/config.php");

require_once("classes/database.class.php");

require_once("classes/article.class.php");

require_once("classes/profile.class.php");



$asm_db = new Database();

$asm_db->openDB();

$article = new Article();

$profile = new Profile();



if(isset($_POST['process']) && $_POST['process']=="title")

{

	$sql = 'update `'.TABLE_PREFIX.'submission` set title="'.$_POST["title"].'",summary="'.$_POST["summary"].'",body="'.$_POST["body"].'",keyword="'.$_POST["keyword"].'" where id='.$_POST["id"];

	$asm_db->modify($sql);

	 	echo "<script>window.close();



		if (!window.opener.closed) {

		window.opener.location.reload();

		window.opener.focus();

		}

		</script>";

} 

if(isset($_POST['process']) && $_POST['process']=="article")

{

	$sql = 'update `'.TABLE_PREFIX.'article` set title="'.$_POST["title"].'",summary="'.$_POST["summary"].'",body="'.$_POST["body"].'",keyword="'.$_POST["keyword"].'" where id='.$_POST["id"];

	$asm_db->modify($sql);

	 	echo "<script>window.close();



		if (!window.opener.closed) {

		window.opener.location.reload();

		window.opener.focus();

		}

		</script>";

}

if(isset($_POST['process']) && $_POST['process']=="author")

{

	//print_r($_POST);

	

		$sql = "update `".TABLE_PREFIX."submission` set profile_id='".$_POST['attach_profile']."' where id=".$_POST['id'];

		$asm_db->modify($sql);

	

		echo "<script>window.close();



		if (!window.opener.closed) {

		window.opener.location.reload();

		window.opener.focus();

		}

		</script>";

}

?>

<html>

<head>

</head>

<link href="<?php echo SERVER_PATH; ?>stylesheets/amarticlestyle.css" rel="stylesheet" type="text/css">

<body class="ambackground">

<?php

if(isset($_GET['id']) && ($_GET['process']=="title" || $_GET['process']=="article"))

{

	if($_GET['process']=="title")

	$article_data = $article->getArticleBySubId($_GET['id']);

	else

	$article_data = $article->getArticleById($_GET['id']);

?>

<form action="" name="art_edit" method="post">

<table class="amtable">



	<TR>

		<TD class="amarticle">Title:<br> <input type="text" name="title" value="<?php echo str_replace("&acirc;€“",' - ', html_entity_decode($article_data['title']));?>" size="80" /></TD>

	</TR>

	<TR>

		<TD class="amunderline"></TD>

	</TR>

	<tr><TD><br></TD></tr>

	<TR>

		<TD class="amarticle">Summary:<br> <textarea name="summary" id="summary" rows="3" cols="93"><?php echo str_replace("&acirc;€“",' - ', html_entity_decode($article_data['summary']));?></textarea></TD>

	</TR>

	<TR>

		<TD class="amunderline"></TD>

	</TR>

	<tr><TD><br></TD></tr>

	<TR>

		<TD class="amarticle">Body:<br> <textarea name="body" id="body" rows="10" cols="93"><?php echo str_replace("&acirc;€“",' - ', html_entity_decode($article_data['body']));?></textarea></TD>

	</TR>

	<TR>

		<TD class="amunderline"></TD>

	</TR>

	<tr><TD><br></TD></tr>

	<TR>

		<TD class="amarticle">Keywords:<br> <textarea name="keyword" id="keyword" rows="10" cols="93"><?php echo str_replace("&acirc;€“",' - ', html_entity_decode($article_data['keyword']));?></textarea></TD>

	</TR>

	<tr>

		<td align="center" class="heading">

	<input type="submit" name="submit" value="Save" /><input type="hidden" name="process" value="<?php echo $_GET['process'];?>"><input type="hidden" name="id" value="<?php echo $_GET['id'];?>">

		</td>

	</tr>

</table>

</form>

<?php

}elseif(isset($_GET['id']) && $_GET['process']=="author")

{

	//$profile_rs = $profile->getSingleProfileById($_GET['id']);

	$sql = "select * from `".TABLE_PREFIX."profile` where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];

	$profile_rs = $asm_db->getRS($sql);

?>



<form action="" method="post" name="attach">

<table align="center" class="summary" cellpadding="5" cellspacing="5">

<tr>

            <td align="center" width="40%" class="heading" colspan="2" style="font-weight:bold;">Change Profile</td>

</tr>

	<tr>

		<?php

		if($profile_rs)

		{

		?>

		<td align="center">

			<select name="attach_profile" style="width:50%">

			<?php

				while($profile = $asm_db->getNextRow($profile_rs))

				{

			?>

				<option value="<?php echo $profile['id'];?>"><?php echo $profile['profile_name'];?></option>

			<?php

				}

			?>

			</select>

		</td>

		<?php

		}

		?>

	</tr>

	<tr><!--window.close();-->

		<td align="center" class="heading">

			<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />

			<input type="hidden" name="process" value="<?php echo $_GET['process'];?>">

			<input type="submit" name="submit" value="Attach" />

		</td>

	</tr>

</table>

</form>

<?php

}

?>

</body>



</html>