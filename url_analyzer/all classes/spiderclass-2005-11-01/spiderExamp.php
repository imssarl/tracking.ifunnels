<?php

require_once("spiderClass.php");

getSport();

exit;

function getSport ($strSport="/football/", $strDetail1="/middlesbrough|boro\b/", $strDetail2="/prem/"){
	$strStartURL = "http://www.bbc.co.uk";
	$arrLinksRegex = array(1 => array("/sport/"), 2 => array($strSport, $strDetail1, $strDetail2), 3 => array($strDetail1, $strDetail2), 3 => array($strDetail1));
	$objSportSpider = new spiderScraper;
	$objSportSpider -> spiderStart($strStartURL);
	$objSportSpider -> arrLinksRegex = $arrLinksRegex;
	$objSportSpider -> intCrawlDepth = 4;

	for ($i = 1; $i <= 50; $i++) {
		$timePrev = $objSportSpider->timeLapsed;
		$arrFetchedPage = $objSportSpider -> spiderNextPage();
		if($arrFetchedPage["error"]>0){
			echo "<br>Error: ".$arrFetchedPage["errortext"];
		} else {
			echo $i.": Depth: ".$objSportSpider->intCurrentDepth." -Seq: ".$objSportSpider->intCurrentSequence." ".($objSportSpider->timeLapsed - $timePrev)."secs - ";
			echo " URL: ".$arrFetchedPage[0]."<br><hr>";
			echo "<br>";
			if(array_key_exists(1,$arrFetchedPage) && isset($arrFetchedPage[1])){
				if(preg_match($strDetail1,$arrFetchedPage[1])>0){
					echo $arrFetchedPage[1]."<br><hr>";	
				}
			}
		}
	}
	echo "total time: ".$objSportSpider->timeLapsed." secs<br>";
} // end function
?>