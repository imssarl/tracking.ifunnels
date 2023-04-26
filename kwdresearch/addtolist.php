<?php
session_start();
chdir( '../' );
include("config/config.php");
chdir( dirname(__FILE__) );
require_once("../classes/database.class.php");

$database = new Database();
$database->openDB();

$list=$_POST['cboList'];
$keywords=$_POST['keywords'];
$storedVal=array();
$sql="SELECT keyword from `".TABLE_PREFIX."kwd_savedkwds` where list_id=".$list;
$rs=$database->getRS($sql);
$i=0;
while($data=$database->getNextRow($rs)){
	$storedVal[$i]=$data['keyword'];
	$i++;
}
for($i=0;$i<count($keywords);$i++)
	{
		$flag=false;
		for($k=0;$k<count($storedVal);$k++)
		{
			if($keywords[$i]==$storedVal[$k]){
				$flag=true;
			}
		}
		if(!$flag){
			$sqlKwd="INSERT INTO `".TABLE_PREFIX."kwd_savedkwds` (`keyword`,`list_id`) VALUES ('".$keywords[$i]."','".$list."')";
			$result=$database->insert($sqlKwd);
		}
	}

header("Location:index.php?resp=Selected keywords have been added successfully");
?>