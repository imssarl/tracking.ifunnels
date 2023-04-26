<?php
	session_start();
	//PHP code will come here
	require_once("config/config.php");
	require_once("classes/settings.class.php");
	require_once("classes/database.class.php");
	require_once("classes/amarticle.class.php");
	require_once("classes/pagination.class.php");
	require_once("classes/search.class.php");
	require_once("classes/pclzip.lib.php");
	require_once("classes/keyword.class.php");
	require_once("classes/en_decode.class.php");
	require_once("classes/organizer.class.php");	
	
	$endec=new encode_decode();
	$settings = new Settings();
	$settings->checkSession();
	$article = new Article();
	$database = new Database();
	$org = new Organizer();
	$pg = new PSF_Pagination();
	$sc = new psf_Search();
	$archive = new PclZip($_FILES['importtextzip']['tmp_name']);
	$key=new keyword();
	$database->openDB();
	if (isset($_POST['process']))
	{
		$process = $_POST['process'];
	}
	else if (isset($_GET['process']))
	{
		$process = $_GET['process'];
	}
	elseif($process=='')
		$process='manage';
	
	if(isset($_POST['hsub'])&&$_POST['hsub']=='Yes'){
	
			$id=$org->insertNote();
			if($id>0){
				$msg="Note has been added successfully!!";
				header("Location:organizer.php?process=manage&msg=".$msg);
			}else{
				$msg="Error occured while adding note!!";
				header("Location:organizer.php?process=manage&msg=".$msg);
			}
			
	}
	
	if(isset($_POST['hedit'])&&$_POST['hedit']=='Yes'){
	
			$id=$org->modifyNote();
			if($id>0){
			
				$msg="Note has been updated successfully!!";
				header("Location:organizer.php?process=manage&msg=".$msg);
			}else{
				$msg="Error occured while updating note!!";
				header("Location:organizer.php?process=manage&msg=".$msg);
			}
			
	}
	

	?>
	<?php require_once("header.php"); ?>
	<title>
	<?php echo SITE_TITLE; ?>
	</title>
	<script type="text/javascript">
	<!--
		function validate(){
			var frm=document.frmadd;
			
			if(frm.txtTitle.value==''){
				alert("Please enter the title");
				return false;
			}
			
			if(frm.txtNote.value==''){
				alert("Please enter the Note");
				return false;
			}
			frm.action="organizer.php";
			frm.submit();
		}
		
	 function viewNote(xId){
		openwindow= window.open ("shownotes.php?id="+xId, "View_Note","'status=0,scrollbars=1',width=650,height=500,resizable=0");
		openwindow.moveTo(50,50);
	 }	
	//-->
	</script>
	<?php require_once("top.php"); ?>
	<?php require_once("left.php"); ?>
	<!-- html code will come here -->
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td align="left">
	<?php
	$home = '<a class="general" href="index.php">Home</a>';
	if ($process=="manage" || $process=="advsearch")
	{
		$manage = " >> Manage Notes";
	}
	elseif ($process=="add" || $process=="edit" || $process == "confirmdelete")
	{
		$manage = ' >> <a class="general" href="organizer.php">Manage Notes</a> ';
	}
	
	if ($process=="add")
	{
		$editprocess = ' >> New Note';
	}
	else if ($process=="edit")
	{
		$editprocess = ' >> Edit Note';
	}
	echo 	$home.$manage.$editprocess;
	?>
	<br>
	</td>
	<td  align="center"> </td>
	</tr>
</table>
<table style="width:300px;" align="center">
	<tr>
		<td><a href="?process=add">Add Note</a></td>
		<td><a href="?process=archive">Archived</a></td>
	</tr>
</table>
<table style="width:500px;" align="center">
	<tr>
		<td><?php echo $_GET['msg']; ?></td>
	</tr>
</table>
<?php
if($process=='manage'){
?>
<table style="width:90%" align="center">
<tr>
	<th>S.No</th>
	<th>Title</th>
	<th>Note</th>
	<th>Date</th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
</tr>
<?php echo $org->getNote(); ?>
</table>
<?php
}elseif($process=='add'){
?>	
<form name="frmadd" method="post" onsubmit="validate()">
<table style="width:90%" align="center">
	<tr>
		<td>Title</td>
		<td><input type="text" name="txtTitle" id="txtTitle" value="<?php echo $_POST['txtTitle']?>" /></td>
	<tr>
	<tr>
		<td>Note</td>
		<td><textarea rows="10" cols="100" name="txtNote" id="txtNote"><?php echo $_POST['txtNote']?></textarea></td>
	<tr>
	<tr>
		<td><input type="hidden" name="hsub" id="hsub" value="Yes" /></td>
		<td><input type="submit" name="cmdsub" id="cmdsub" value="Add Note" /></td>
	<tr>	
</table>
</form>
<?php }elseif($process=='edit'){ 
$id=isset($_GET['id'])?$_GET['id']:(isset($_POST['id'])?$_POST['id']:'');
$data=$org->getNoteById($id);
?>
<form name="frmedit" method="post" onsubmit="validate()">
<table style="width:90%" align="center">
	<tr>
		<td>Title</td>
		<td><input type="text" name="txtTitle" id="txtTitle" value="<?php echo $data['title']?>" /></td>
	<tr>
	<tr>
		<td>Note</td>
		<td><textarea rows="10" cols="100" name="txtNote" id="txtNote"><?php echo $data['text']?></textarea></td>
	<tr>
	<tr>
		<td><input type="hidden" name="hedit" id="hedit" value="Yes" />
		<input type="hidden" name="id" id="id" value="<?php echo $id;?>" /></td>
		<td><input type="submit" name="cmdsub" id="cmdsub" value="Update" /></td>
	<tr>	
</table>
</form>
<?php }elseif($process=='confirmdelete'){
$id=isset($_GET['id'])?$_GET['id']:(isset($_POST['id'])?$_POST['id']:'')
?>
<form name="frmedit" method="post" >
<table style="width:90%" align="center">
	<tr>
		<td>Title</td>
		<td><input type="text" name="txtTitle" id="txtTitle" value="<?php echo $data['title']?>" /></td>
	<tr>
	<tr>
		<td>Note</td>
		<td><textarea rows="10" cols="100" name="txtNote" id="txtNote"><?php echo $data['text']?></textarea></td>
	<tr>
	<tr>
		<td><input type="hidden" name="hedit" id="hedit" value="Yes" /><input type="hidden" name="id" id="id" value="<?php echo $id;?>" /></td>
		<td><input type="submit" name="cmdsub" id="cmdsub" value="Add Note" /></td>
	<tr>	
</table>
</form>
<?php }elseif($process=='archive')?>
<?php require_once("right.php"); ?>
<?php require_once("bottom.php"); ?>