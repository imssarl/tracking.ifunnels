<?php
require_once("classes/tracking.class.php");
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/settings.class.php");
require_once("classes/ctmsalesdata.class.php");
require_once("classes/en_decode.class.php");	
	
$endec=new encode_decode();
$settings = new Settings();
//$settings->checkSession();
$track= new track();
$sales = new CTMSales();

$ms_db = new Database();
$ms_db->openDB();


$name = "name";
$regex = "regex";

$keyword="";
$searchengine = "";
$clicks="1";

$_GET["id"]=$endec->decode($_GET["id"]);
if(isset($_GET["href"]) && isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"]>0)
{

	$url=urldecode($_GET["href"]);
	$rip = $_GET["ip"];
	
	if (isset($_GET["tid"]) && is_numeric($_GET["tid"]) && $_GET["tid"]>0 )
	{
		$trackid = $_GET["tid"];
		$track_id = $track->updatetrackdata($trackid);
		echo $track_id;
	}
	else
	{

	
		$searchengines = array(
		"google"=>array($name=>"google.",$regex=>"(q=([^&]+)[&]?)", "id"=>"Google"),
		"yahoo"=>array($name=>"search.yahoo.",$regex=>"(p=([^&]+)[&]?)", "id"=>"Yahoo"),
		"msn"=>array($name=>"search.msn.",$regex=>"(q=([^&]+)[&]?)", "id"=>"MSN"),
		"aol"=>array($name=>"search.aol.",$regex=>"(query=([^&]+)[&]?)", "id"=>"AOL"),
		"altavista"=>array($name=>"altavista.",$regex=>"(q=([^&]+)[&]?)", "id"=>"Altavista"),
		"kanoodle"=>array($name=>"kanoodle.",$regex=>"(query=([^&]+)[&]?)", "id"=>"Kanoodle"),
		"goclick"=>array($name=>"goclick.",$regex=>"(SEARCH=([^&]+)[&]?)", "id"=>"Goclick"),
		"search"=>array($name=>"search.",$regex=>"(qu=([^&]+)[&]?)", "id"=>"Search"),
		"unknown"=>array($name=>"unknown.",$regex=>"(XXXXXXXXXXX)", "id"=>"xxxxxx")
		);	
		$engregex="";
		foreach($searchengines as $engine)
		{
			$findat = @strpos($url, $engine["name"]);
			if ($findat!== false)
			{
				$engregex = $engine["regex"];
				$searchengine = $engine["id"];
				break;
			}
		}
		if ($engregex!="")
		{
			@preg_match($engregex, $url, $out);
			$keyword = $out[1];
		}
		
		if ($keyword=="")
		{
			$keywattributes = array("keyword", "search", "query", "kw", "keyw", "kword");

			foreach($keywattributes as $keys)
			{
				$regex = "($keys=([^&]+)[&]?)";
			
				@preg_match($regex, $url, $out);	
				if (trim($out[1]) != "")
				{
					$keyword = $out[1];
					break;
				}
			}
			
			if ($keyword=="")
				$keyword = "UnknownKW";
		}
		if($searchengine=="")
		{
			$newengine = explode(".",$url);
			if(isset($newengine[1]) && $newengine[1]!="")
				$searchengine = $newengine[1];
			else
				$searchengine = "UnknownSE";
		}
			
		if($searchengine != "" && $keyword!="")
		{
			$aid = $_GET["id"];
			$sql = "select id from `".TABLE_PREFIX."track` 
			where keyword='".$ms_db->getSqlValueString($keyword, "text")."' 
			and url_refered='".$ms_db->getSqlValueString($searchengine, "text")."' 
			and ad_id = ".$ms_db->getSqlValueString($aid, "int");
			
			$trackid = $ms_db->getDataSingleRecord($sql);
			
			if($trackid && $trackid > 0)
				$track_id=$track->updatetrackdata($trackid);
			else
				$track_id=$track->inserttrackdata($searchengine, $keyword, $clicks);
			
			echo $track_id;
		}
	}
	$cd = $track->insertClicksDetails($track_id, $url, $rip);
}
else if(isset($_GET["mytid"]) && $_GET["mytid"] > 0)
{


	if (isset($_GET["amount"]) && is_numeric($_GET["amount"]) && $_GET["amount"]>0)
		$amount = $_GET["amount"];
	else
		$amount = 0;

	if (isset($_GET["items"]) && is_numeric($_GET["items"]) && $_GET["items"]>0)
		$items = $_GET["items"];
	else
		$items = 0;

	if (isset($_GET["mytid"]) && is_numeric($_GET["mytid"]) && $_GET["mytid"]>0)
	{
		$track_id = $_GET["mytid"];
		$trn_id = date("ymdhis");

		$id = $sales->insertSalesData($trn_id, $track_id, $amount, $items);
	}
}
?>
