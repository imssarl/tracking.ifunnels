<?php
class report
{
	function gettotalamountofkeyword($keyword_id)
	{
		global $ms_db;

		$sql = "SELECT sum(amount) as total from  `".TABLE_PREFIX."upload_csv` where track_id = ".$keyword_id;
		$amountofkey=$ms_db->getDataSingleRecord($sql);
	$finalamount="$".round($amountofkey,2);
	return $finalamount;
	}

	function gettotalitemofkeyword($keyword_id)
	{
		global $ms_db;

		$sql = "SELECT sum(item) as total from  `".TABLE_PREFIX."upload_csv` where track_id = ".$keyword_id;
		$itemofkey=$ms_db->getDataSingleRecord($sql);
	return $itemofkey;
	}
	
	function getkeywordfromtrackid($track_id)
	{
		global $ms_db;
		$sql = "SELECT keyword from  `".TABLE_PREFIX."track` where id = ".$track_id;
		$keyword=$ms_db->getDataSingleRecord($sql);
		return $keyword;
	}
}
?>