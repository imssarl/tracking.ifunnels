<?php
	define("SITE_TITLE","my CP temp site");
	define("DB_SERVER_NAME","213.171.218.244");
	define("DB_USERNAME","inforebels");
	define("DB_PASSWORD","inforebels");		
	define("DB_NAME","inforebels");			
	define("TABLE_PREFIX","00_mscpanel_");					
	define("SERVER_PATH","http://www.inforebels.co.uk/delete/controlpanel/");					
	
	define("SESSION_PREFIX","CP_SESS_");							
	define("ROWS_PER_PAGE", 50);
	define("ARTICLE_SEPARATOR","###NEW###");	
	define("MAX_FEED_ITEMS", 6);
	define("MAX_LENGTH_FEED_DESC", 100);	
	
	$KEYWORD_MAIN_TAG["GOOGLE"] = array("<div class=g>","</div>");
	$KEYWORD_TITLE_TAG["GOOGLE"] = array("<h2 class=r>","</h2>");
	$KEYWORD_SUMMARY_TAG["GOOGLE"] = array("<table border=0 cellpadding=0 cellspacing=0><tr><td class=j>","</td></tr></table>");	
	$KEYWORD_SUMMARY_SEPARATOR["GOOGLE"] = array("<nobr>");
	$KEYWORD_SOURCE_SITES["GOOGLE"] = array("GOOGLE","http://www.google.co.in/search?q=");
	$KEYWORD_START_VARS["GOOGLE"] = array("start",0,10);
	$KEYWORD_DATAS = 20;	
	$KEYWORD_SEARCH_BY = "GOOGLE";
?>
