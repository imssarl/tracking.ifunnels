<?php
class Organizer{

//############################################################################################################
// Function to retrive all Notes

function getNote(){

	global $database,$order_sql;
	
	$sql="SELECT id, title, date, text from `".TABLE_PREFIX."notes_tb` where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."' and is_deleted!='Y' and is_archive!='Y'";
	$man_rs=$database->getRS($sql);
	if($man_rs)
	{	$no=1;
		while($data=$database->getNextRow($man_rs))
		{	
			$xlen=strlen($data['text']);
			if($xlen>100){
				$dtext=substr($data['text'],0,100);
			}
			else{
				$dtext=$data['text'];
			}	
			$str .="<tr><td align='center'>".$no ."</td><td>".stripslashes($data['title'])."</td><td>".stripslashes($dtext)."........</td><td>".$data['date']."</td><td  align='center'><img src='images/getcode.gif' border='0' title='View Note' style='cursor:pointer' onclick='viewNote(".$data['id'].")'></td><td  align='center'><a href='?process=edit&id=".$data['id']."'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td  align='center'><a href='?process=confirmdelete&id=".$data['id']."'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td><td  align='center'><a href='?process=archive&id=".$data['id']."'>Archive</a></td></tr>";
			$no++;
		}
	}
	else
	{
		$str="<tr><td>Sorry! No records found!!</td></tr>";
	}	

	return $str;
}

//############################################################################################################
// Function to retrive all Archive Notes

function getArchives(){

	global $database,$order_sql;
	
	$sql="SELECT id, title, date, text from `".TABLE_PREFIX."notes_tb` where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."' and is_deleted!='Y' and is_archive='Y'";
	$man_rs=$database->getRS($sql);
	if($man_rs)
	{	$no=1;
		while($data=$database->getNextRow($man_rs))
		{	
			$xlen=strlen($data['text']);
			if($xlen>100){
				$dtext=substr($data['text'],0,100);
			}
			else{
				$dtext=$data['text'];
			}	
			$str .="<tr><td align='center'>".$no ."</td><td>".stripslashes($data['title'])."</td><td>".stripslashes($dtext)."........</td><td>".$data['date']."</td><td  align='center'><img src='images/getcode.gif' border='0' title='View Note' style='cursor:pointer' onclick='viewNote(".$data['id'].")'></td><td  align='center'><a href='?process=edit&id=".$data['id']."'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td  align='center'><a href='?process=confirmdelete&id=".$data['id']."'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td><td  align='center'><a href='?process=unarchive&id=".$data['id']."'>Un-Archive</a></td></tr>";
			$no++;
		}
	}
	else
	{
		$str="<tr><td>Sorry! No records found!!</td></tr>";
	}	

	return $str;
}

//############################################################################################################
// Function to Add a Note
function insertNote(){

	global $database,$order_sql;
	
	$today=date('Y-m-d');
	$sql="Insert into `".TABLE_PREFIX."notes_tb` (`title`,`text`,`date`,`user_id`,`is_archive`,`is_deleted`) VALUES ('".addslashes(strip_tags($_POST['txtTitle']))."','".addslashes(strip_tags($_POST['txtNote']))."','".$today."','".$_SESSION[SESSION_PREFIX.'sessionuserid']."','N','N')";
	$id = $database->insert($sql);
	return $id;

}

//############################################################################################################
// Function to Select a Note By Id
function getNoteById($xId){


	global $database,$order_sql;
	
	$sql="SELECT id, title, date, text from `".TABLE_PREFIX."notes_tb` where id=".$xId;
	$rs = $database->getDataSingleRow($sql);
	

	return $rs;


}

//############################################################################################################
// Function to Update a Note
function modifyNote(){

	global $database,$order_sql;
	
	$today=date('Y-m-d');
	$sql="UPDATE `".TABLE_PREFIX."notes_tb` SET `title`='".addslashes(strip_tags($_POST['txtTitle']))."',`text`='".addslashes(strip_tags($_POST['txtNote']))."' where id=".$_POST['id'];
	$id = $database->modify($sql);
	return $id;

}


//############################################################################################################
// Function to Delete a Note
function deleteNote(){

	global $database,$order_sql;
	$sql="UPDATE `".TABLE_PREFIX."notes_tb` SET `is_deleted`='Y' where id=".$_POST['id'];
	$id = $database->modify($sql);
	return $id;

}
//##############################################################################################################

//############################################################################################################
// Function to Archive a Note
function setArchiveNote(){

	global $database,$order_sql;
	$sql="SELECT `is_archive` from `".TABLE_PREFIX."notes_tb` where id=".$_POST['id'];
	$data=$database->getDataSingleRecord($sql);
	if($data=='N'){
		$sql="UPDATE `".TABLE_PREFIX."notes_tb` SET `is_archive`='Y' where id=".$_POST['id'];
	}elseif($data=='Y'){
		$sql="UPDATE `".TABLE_PREFIX."notes_tb` SET `is_archive`='N' where id=".$_POST['id'];
	}
		
	$id = $database->modify($sql);
	return $id;

}
//##############################################################################################################
}
?>