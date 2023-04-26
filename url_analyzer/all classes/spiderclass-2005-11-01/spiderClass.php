<?php

error_reporting(E_ALL);
ini_set('display_errors',true);

//
// +------------------------------------------------------------------------------+
// | C360UK Ltd                                                                   |
// +------------------------------------------------------------------------------+
// | spiderClass.php - class to spider and scrape web pages                       |
// | note this is being pre-released  					        	              |
// | as it is different than most spidering programs by disaggregating            |
// | its methods to quite a high degree.  Could be made asymmetric in             |
// | future by serialising the object into a db or file, to enable spidering      |
// | to be handled in 'parcels' by a daemon, reducing load on spidered servers    |
// +------------------------------------------------------------------------------+
// | Copyright (c) 2005 C360 UK Ltd					                              |
// | Email         greg.jackson@c360uk.com     	                                  |
// | Web           http://www.c360uk.com    									  |
// | This class is free software; you can redistribute it and/or				  |
// | modify it under the terms of the GNU General Public						  |
// | License as published by the Free Software Foundation; either				  |
// | version 2 of the License, or (at your option) any later version.			  |
// | 																			  |
// | This class is distributed in the hope that it will be useful,				  |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of				  |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU			  |
// | General Public License for more details.									  |
// | 																			  |
// | Before using, modifying or publishing this class, you should refer to the	  |
// | GNU GENERAL PUBLIC LICENSE Version 2. This is available at:				  |
// | http://www.gnu.org/licenses/gpl.txt										  | 
// +------------------------------------------------------------------------------+
// | Change Log    17/April/2005:  GSJ:  0.01:	pre-beta released		          |  
// +------------------------------------------------------------------------------+
//
/*

Class to handle spidering, scraping, etc

How to use it:
 - simplest way is to spiderStart then repeatedly call spiderNextPage and carry out whatever tasks you'd like on retrieved pages

Capabilities:
Spider from a start page matching links according to regexps (regexps can vary according to crawl depth)
Retrieve a page as a string
Extract Links from a page (optionally according to regexps)

CLASS: spiderScraper

METHODS IN THIS CLASS:
	spiderStart($strStartPage) 
	spiderNextPage()
	getPage($pageToGet)
	getLinks($strURL="", $strPageContents, $strScrapeRegExp="")
	glue_url - internal use only


*/

/*
Example to Retrieve one page and all its links:
$objTestPage = new spiderScraper;
$url = "http://www.page.com/index.html";
$page = $objTestPage -> getPage ($url);
$arrLinksParsed = $objTestPage -> getLinks("", $page);
*/

/*
//Example To Scan 3 links deep across 100 pages:
$arrTimes=array();
$objTest = new spiderScraper;
$objTest -> spiderStart("http://www.example.com/index.html");
$objTest -> arrLinksRegex = array(1 => array("/^http\:\/\/www\.example\.com\/stuff\//"), 2 => array("/^http\:\/\/www\.example\.com\/stuff\/level2/"), 3 => array("/^http\:\/\/www\.example\.com\/stuff\/level2\/level3/"));
for ($i = 1; $i <= 100; $i++) {
	$arrFetchedPage = $objTest -> spiderNextPage();
	switch ($objTes->intCurrentDepth){
		case: 0
		//process stuff on initial level - page contents in $arrFetchedPage[1]
		break;
		case 1:
		//process stuff on first level - page contents in $arrFetchedPage[1]
		break;
		case 2:
		//process stuff on second level - page contents in $arrFetchedPage[1]
		break;		
		case 3:
		//process stuff on third level - page contents in $arrFetchedPage[1]
		break;		
	}
}
echo "All links found for crawling in array of depth and sequence:<pre>";
print_r ($objTest->arrLinksToCrawl);
echo "</pre><br>List of unique links found for crawling:<pre>";
print_r ($objTest->arrLinksFound);
echo "</pre>";
*/

class spiderScraper
{
	public $ch;    // will initialize curl handle in __construct
	public $timeDelay = 50000; //5/100 of a second delay just to reduce server nasties
	public $timeStart;
	public $timeLapsed;
	public $VAR_CURLOPT_FAILONERROR = 0;  // if HTTP code > 300 still returns the page
	public $VAR_CURLOPT_FOLLOWLOCATION = 1;// allow redirects 
	public $VAR_CURLOPT_RETURNTRANSFER = 1; // will return the page in a variable 
	public $VAR_CURLOPT_TIMEOUT = 3; // times out after 4s
	public $VAR_CURLOPT_POST = 1; // set POST method
	public $VAR_CURLOPT_POSTFIELDS = ""; // add POST fields 
	public $VAR_CURLOPT_USERAGENT = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)";
	public $VAR_CURLOPT_USERPWD = "";
	public $VAR_CURLOPT_COOKIEJAR; 		// Note that these will be defined in __construct using global variable $strDefaultPath, if it exists
	public $VAR_CURLOPT_COOKIEFILE; 	// Note that these will be defined in __construct using global variable $strDefaultPath, if it exists
	public $intCrawlDepth = 3; 			// maximum depth to crawl
	public $intMaxPages = 1000; 		// max number of pages to crawl  --> note that this should not be used for control loop; it is a safety valve
	public $strLinkRegExp = "/href=\"(.*?)\"|\<frame.*?src=\"(.*?)\"/"; // This is the regex used to identify what a link looks like (is applied at point of link scanning, whereas arrLinksRegex is used when fetching Next Page)
	public $arrLinksRegex = array(); 	// array of depth and regexp arrays which URLs must match in order to be crawled:
										// [depth => regexparray], depth = 0,1,2,3 etc  or 'all'
										// eg. $arrLinksRexeg = (0=> array('/example.com/'),1=>array('/\.com/','/\.co\.uk/'))
										// if regexp not specified for a specific depth, will crawl all URLs
	// note:	$strLinksRegExp is used in method: getLinks  [and therefore spiderNextPage too]
	//			$arrLinksRegex is used in method: spiderNextPage
	//	ie:		method: getLinks is not 'selective'  -  it is designed to find links, and links only.
	//			selectiveness is intended in the spidering functions or in any script which calls getLinks
	
	public $arrLinksFound = array();	// holds links found - can be read by calling scripts, but shouldnt be changed
	public $arrLinksToCrawl = array();	// holds links to crawl - can be read by calling scripts, but shouldnt be changed
	public $intCurrentDepth = 0;		// tracks depth as spider crawls - can be read by calling scripts, but shouldnt be changed
	public $intCurrentSequence = 0;		// tracks sequence - can be read by calling scripts, but shouldnt be changed
	public $intPagesCrawled = 0;		// tracks number of pages crawled
	public $booDownThenAcross=true;		// true = scan all depth 1 first; then depth 2; then depth 3 etc
										// --> note; not currently implemented

	function __construct() {
		$this->timeStart=microtime(true);
		$this->timeLapsed=microtime(true);
		$this->ch = curl_init();
		global $strDefaultPath;
		$strDefaultPath = (isset($strDefaultPath) && $strDefaultPath) ? $strDefaultPath : "";		
		$this->VAR_CURLOPT_COOKIEJAR = $strDefaultPath."scraping/cookies";  // place to put cookies
		$this->VAR_CURLOPT_COOKIEFILE = $strDefaultPath."scraping/cookies";  // place to get cookies from
	}	
	

   function __destruct() {
       curl_close($this->ch);  // this probably isn't necessary :)
   }
   
	/* spiderStart
		starts a spider - thereafter, call the method nextPage 
		$strStartPage
	*/
	public function spiderStart($strStartPage) {
		$this->intCurrentDepth = 0;
		$this->intCurrentSequence = 0;
		$this->intPagesCrawled = 0;
		unset($this->arrLinksFound, $this->arrLinksToCrawl);
		$this->arrLinksToCrawl = array(0 => array(0 => $strStartPage));	// (depth => (currentsequence => url))
		$this->arrLinksFound = array($strStartPage);
	} // end function spiderStart

	/* spiderNextPage
		returns the next page for the spider
	*/
	public function spiderNextPage() {
		if($this->intPagesCrawled > $this->intMaxPages){
			return(array("error"=>0, "errortext"=>"exceeded max no. of pages"));
		}
		if(!isset($this->arrLinksToCrawl[$this->intCurrentDepth][$this->intCurrentSequence])){
			$this->intCurrentDepth ++;
			$this->intCurrentSequence =0;
			if(!isset($this->arrLinksToCrawl[$this->intCurrentDepth][0])){
				return(array("error"=>0, "errortext"=>"no more links"));
			}
			if($this->intCurrentDepth > $this->intCrawlDepth){
				return(array("error"=>0, "errortext"=>"reached max depth"));
			}
		}
		$strSpiderURL = $this->arrLinksToCrawl[$this->intCurrentDepth][$this->intCurrentSequence];
		$arrSpiderPage = $this->getPage($strSpiderURL);
		if($arrSpiderPage["error"]>0){
			return($arrSpiderPage);
		}
		$strSpiderPage = $arrSpiderPage[0];
		$arrSpiderLinks = $this->getLinks("", $strSpiderPage);
		if(!empty($arrSpiderLinks)){
			foreach($arrSpiderLinks as $strAnotherURL){
				if(!in_array($strAnotherURL,$this->arrLinksFound)){
					if(!isset($this->arrLinksRegex[$this->intCurrentDepth+1])){
						$booAddThis = true;
					} else {
						$booAddThis = false;
						foreach($this->arrLinksRegex[$this->intCurrentDepth+1] as $strThisRegEx){
							if(preg_match($strThisRegEx,$strAnotherURL)>0){
								$booAddThis = true;
							}
						}
					}
					if($booAddThis){	
						if(!isset($this->arrLinksToCrawl[$this->intCurrentDepth + 1])){
							$this->arrLinksToCrawl[$this->intCurrentDepth + 1]=array();
						}
						array_push($this->arrLinksToCrawl[$this->intCurrentDepth + 1],$strAnotherURL);
						array_push($this->arrLinksFound, $strAnotherURL);
					}
				}
			}
		}
	$this->intCurrentSequence ++;
	$this->intPagesCrawled ++;
	$this->timeLapsed = substr((microtime(true) - $this->timeStart),0,5);
	usleep($this->timeDelay); //just to reduce server nasties
	return(array($strSpiderURL,$strSpiderPage,"error"=>0, "errortext"=>""));
	} // end function spiderNextPage


	public function getPage($pageToGet) {
		curl_setopt($this->ch, CURLOPT_URL, $pageToGet); // set url to post to
		curl_setopt($this->ch, CURLOPT_FAILONERROR, $this->VAR_CURLOPT_FAILONERROR);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, $this->VAR_CURLOPT_FOLLOWLOCATION);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, $this->VAR_CURLOPT_RETURNTRANSFER);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->VAR_CURLOPT_TIMEOUT);
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->VAR_CURLOPT_COOKIEJAR);
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->VAR_CURLOPT_COOKIEFILE);
		if(strlen($this->VAR_CURLOPT_POSTFIELDS)>1) {
			curl_setopt($this->ch, CURLOPT_POST, $this->VAR_CURLOPT_POST);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->VAR_CURLOPT_POSTFIELDS);
			}
		if(strlen($this->VAR_CURLOPT_USERAGENT)>0) {
			curl_setopt($this->ch, CURLOPT_USERAGENT, $this->VAR_CURLOPT_USERAGENT);
			}
		if(strlen($this->VAR_CURLOPT_USERPWD)>2) {
			curl_setopt($this->ch, CURLOPT_USERPWD, $this->VAR_CURLOPT_USERPWD);
			}
		$strPageContents = curl_exec($this->ch); // go get the page
		$this->timeLapsed = substr((microtime(true) - $this->timeStart),0,5);
		return(array($strPageContents, "error"=>curl_errno($this->ch), "errortext"=>curl_error($this->ch)));

	} // end function scrape


	// function getLinks retrieves all href= and <frame src= links within the page
	// it requires two parameters:
	// the page url - DO NOT SUPPLY IF METHOD IS CALLED WHILE A CURL SESSION IS ACTIVE (THE NORM) - this is used to complete relative links.  If a CURL session is open and you do not supply the url, the method will use the CURL url which handles redirects/frames properly
	// page contents - note that this function does not go and get the contents itself
	// optionally; you can supply the regex you want to use for link finding

	public function getLinks($strURL="", $strPageContents, $strScrapeRegExp="") {
		if (empty($strURL)){
			$strURL = curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
		}
		if(empty($strScrapeRegExp)){
			$strScrapeRegExp=$this->strLinkRegExp;
		}
		$arrURLParsed = parse_url($strURL);
		$searchResults=array();
	    preg_match_all($strScrapeRegExp, $strPageContents, $searchResults);
		$i=0;
		$arrResults=array();
		foreach($searchResults as $arrThisMatch){
			if($i>0){
				$arrResults = array_merge($arrResults,$arrThisMatch);
			}
			$i++;
		}
		$arrLinksParsed = array();
		if(!empty($arrResults)){
			foreach($arrResults as $strLink){
				$arrLink = parse_url($strLink);
				foreach(array_keys($arrURLParsed) as $key){
					if(!isset($arrLink[$key])){
						$arrLink[$key] = $arrURLParsed[$key];
					}
				}
				$strLink = $this->glue_url($arrLink);
				array_push($arrLinksParsed,$strLink);
			}
		}
		return($arrLinksParsed);
	}// end function 


	// this function receives an array containing a parsed url and reconstructs the url as a string
	// it is taken from flop at escapesoft's comments at http://uk.php.net/parse_url
	function glue_url($parsed) {
		if (! is_array($parsed)) return false;
		$uri = $parsed['scheme'] ? $parsed['scheme'].':'.((strtolower($parsed['scheme']) == 'mailto') ? '':'//'): '';
		$uri .= isset($parsed['user']) ? $parsed['user'].($parsed['pass']? ':'.$parsed['pass']:'').'@':'';
		$uri .= isset($parsed['host']) ? $parsed['host'] : '';
		$uri .= isset($parsed['port']) ? ':'.$parsed['port'] : '';
		$uri .= isset($parsed['path']) ? $parsed['path'] : '';
		$uri .= isset($parsed['query']) ? '?'.$parsed['query'] : '';
		$uri .= isset($parsed['fragment']) ? '#'.$parsed['fragment'] : '';
  		return $uri;
	}// end function glue


} // end class spiderScraper

?>