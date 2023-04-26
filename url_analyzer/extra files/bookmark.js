function insertBookmarkList() {
 // ------------------------------------------ //
 // Version: This is version 1.1.4             //
 // Script by Claus Schmidt of http://clsc.net //
 //                                            //
 // Copyright: You have the right to copy this //
 // in fact you have to if you want to use it. //
 //                                            //
 // License: Creative commons,                 //
 //          Attribution-ShareAlike 2.0        //
 // ------------------------------------------ //


 // ------------------------------------------ //
 // To leave out a bookmark service just       //
 // comment out the relevant line below. You   //
 // do that by putting two slashes ("//") to   //
 // the left of "$theButtonList".              //
 //                                            //
 // For an example, look for the entry called  //
 // "Threadwatch" (this one will not be shown  //
 // unless you remove the slashes "//" to the  //
 // left of "$theButtonList".                  //
 //                                            //
 // You may also just delete the full two      //
 // lines for each service you don't need.     //
 //                                            //
 // Enjoy.                                     //
 // ------------------------------------------ //


 // define common stuff
    $theMessage = "Submit this tool to:";
    //$theURL = encodeURIComponent(location.href); // location.href;
    $theURL = 'http://www.ranks.nl/tools/spider.html';
    $theTitle = encodeURIComponent(document.title) // document.title;
    $theDescription = "please write something here";

    if(window.getSelection)
    {
        $theDescription = encodeURIComponent(window.getSelection());
    }
    else if (document.getSelection)
    {
        $theDescription = encodeURIComponent(document.getSelection());
    }
    else if (document.selection && document.selection.createRange)
    {
        txt = document.selection.createRange().text;
        $theDescription = encodeURIComponent(txt);
    }
    else if(document.selection)
    {
        txt=document.selection.createRange().text;
        $theDescription = encodeURIComponent(txt);
    }

 //button list begin
    var $theButtonList = '<p><strong>' + $theMessage + '</strong></p>';
    $theButtonList += '<ul class="theBookmarkButtons">';
 // del.icio.us
    $theButtonList += '<li><a href="http:\/\/del.icio.us\/post?url=' + $theURL + '&amp;title=' + $theTitle + '">Del.iCio.us<\/a><\/li> ';
  // digg
    $theButtonList += '<li><a href="http:\/\/digg.com\/submit?phase=2&amp;url=' + $theURL + '">Digg<\/a><\/li> ';
 // fark
    $theButtonList += '<li><a href="http:\/\/cgi.fark.com\/cgi\/fark\/edit.pl?new_url=' + $theURL + '&amp;new_comment=' + $theTitle + '&amp;new_link_other=&amp;linktype&amp;topic=">Fark<\/a><\/li> ';
 // ask jeeves
    $theButtonList += '<li><a href="http:\/\/myjeeves.ask.com\/mysearch\/BookmarkIt?v=1.2&amp;t=webpages&amp;title=' + $theTitle + '&amp;url=' + $theURL + '">AskJeeves<\/a><\/li> ';
// del.irio.us
    $theButtonList += '<li><a href="http:\/\/de.lirio.us\/rubric\/post?uri=' + $theURL + '&amp;title=' + $theTitle + '&amp;when_done=go_back">Del.iRio.us<\/a><\/li> ';
 // blinklist
    $theButtonList += '<li><a href="http:\/\/www.blinklist.com\/index.php?Action=Blink\/addblink.php&Description=' + $theDescription + '&amp;Url=' + $theURL + '&amp;title=' + $theTitle + '">Blinklist<\/a><\/li> ';
 // blogger
    $theButtonList += '<li><a href="http:\/\/www.blogger.com\/blog-this.g?ie=UTF-8&amp;oe=UTF-8&amp;u=' + $theURL + '&amp;n=' + $theTitle + '&amp;t=' + $theDescription + '%5B...%5D+More%3A+%3Ca+href%3D%22' + $theURL + '%22%3E' + $theTitle + '%3C%2Fa%3E">Blogger<\/a><\/li> ';
 // blogmarks
    $theButtonList += '<li><a href="http:\/\/blogmarks.net\/my\/new.php?mini=1&amp;title=' + $theTitle + '&amp;url=' + $theURL + '">Blogmarks<\/a><\/li> ';
 // blogrolling
    $theButtonList += '<li><a href="http:\/\/www.blogrolling.com/add_links_pop.phtml?u=' + $theURL + '&amp;t=' + $theTitle + '">Blogrolling<\/a><\/li> ';
 // buddymarks
    $theButtonList += '<li><a href="http:\/\/buddymarks.com\/add_bookmark.php?bookmark_title=' + $theTitle + '&amp;bookmark_url=' + $theURL + '">Buddymarks<\/a><\/li> ';
 // citeulike
    $theButtonList += '<li><a href="http:\/\/www.citeulike.org\/posturl?url=' + $theURL + '&amp;title=' + $theTitle + '">CiteUlike<\/a><\/li> ';
 // feedmarker
    $theButtonList += '<li><a href==http:\/\/www.feedmarker.com\/admin.php?do=bookmarklet_mark&url=' + $theURL + '&amp;title=' + $theTitle + '">FeedMarker<\/a><\/li> ';
 // feedmelinks
    $theButtonList += '<li><a href="http:\/\/feedmelinks.com\/categorize?from=toolbar&amp;op=submit&amp;name=' + $theTitle + '&amp;url=' + $theURL + '&amp;ref=' + escape(document.referrer) + '&amp;version=0.7">FeedMeLinks<\/a><\/li> ';
 // furl
    $theButtonList += '<li><a href="http:\/\/www.furl.net\/storeIt.jsp?t=' + $theTitle + '&amp;u=' + $theURL + '">Furl<\/a><\/li> ';
 // kuro5hin
    $theButtonList += '<li><a href="http:\/\/www.kuro5hin.org\/submit">Kuro5hin<\/a><\/li> ';
 // kinja
    $theButtonList += '<li><a href="http:\/\/www.kinja.com\/checksiteform.knj?add' + $theURL + '">Kinja<\/a><\/li> ';
 // ma.gnolia
    $theButtonList += '<li><a href="http:\/\/ma.gnolia.com\/bookmarklet\/add?url=' + $theURL + '&amp;title=' + $theTitle + '">Ma.gnolia<\/a><\/li> ';
 // maple.nu
    $theButtonList += '<li><a href="http:\/\/www.maple.nu\/bookmarks\/bookmarklet?bookmark[url]=' + $theURL + '&amp;bookmark[description]=' + $theTitle + '">Maple.nu<\/a><\/li> ';
 // netvouz
    $theButtonList += '<li><a href="http:\/\/www.netvouz.com\/action\/submitBookmark?url=' + $theURL + '&amp;title=' + $theTitle + '">Netvouz<\/a><\/li> ';
 // newsvine
    $theButtonList += '<li><a href="http:\/\/www.newsvine.com\/_tools\/seed&amp;save?u=' + $theURL + '&amp;h=' + $theTitle + '">Newsvine<\/a><\/li> ';
 // raw sugar
    $theButtonList += '<li><a href="http:\/\/www.rawsugar.com\/tagger\/?turl=' + $theURL + '&amp;tttl=' + $theTitle + '">RawSugar<\/a><\/li> ';
 // reddit
    $theButtonList += '<li><a href="http:\/\/reddit.com\/submit?url=' + $theURL + '&amp;title=' + $theTitle + '">Reddit<\/a><\/li> ';
 // scuttle
    $theButtonList += '<li><a href="http:\/\/scuttle.org\/bookmarks.php\/pass?action=add&address=' + $theURL + '&amp;title=' + $theTitle + '">Scuttle<\/a><\/li> ';
 // spurl
    $theButtonList += '<li><a href="http:\/\/www.spurl.net\/spurl.php?url=' + $theURL + '&amp;title=' + $theTitle + '">Spurl<\/a><\/li> ';
 // shadows
    $theButtonList += '<li><a href="http:\/\/www.shadows.com\/features\/tcr.htm?url=' + $theURL + '&amp;title=' + $theTitle + '">Shadows<\/a><\/li> ';
 // shoutwire
    $theButtonList += '<li><a href="http:\/\/www.shoutwire.com\/submit">Shoutwire<\/a><\/li> ';
 // simpy
    $theButtonList += '<li><a href="http:\/\/simpy.com\/simpy\/LinkAdd.do?title=' + $theTitle + '&amp;href=' + $theURL + '&amp;note=' + $theDescription + '&amp;_doneURI=' + $theURL + ' &amp;v=6&amp;src=bookmarklet' + '">Simpy<\/a><\/li> ';
 // slashdot
    $theButtonList += '<li><a href="http:\/\/slashdot.org\/submit.pl">Slashdot<\/a><\/li> ';
 // technorati
    $theButtonList += '<li><a href="http:\/\/www.technorati.com\/search\/' + $theURL + '">Technorati<\/a><\/li> ';
 // threadwatch
//    $theButtonList += '<li><a href="http:\/\/www.threadwatch.org\/submit">Threadwatch<\/a><\/li> ';
 // wink
    $theButtonList += '<li><a href="http:\/\/www.wink.com\/_\/tag?url=' + $theURL + '&amp;doctitle=' + $theTitle + '">Wink<\/a><\/li> ';
 // yahoo my web
    $theButtonList += '<li><a href="http:\/\/myweb2.search.yahoo.com\/myresults\/bookmarklet?t=' + $theTitle + '&amp;u=' + $theURL + '">Y! MyWeb<\/a><\/li> ';

 // IE bookmark
    var IEstring = '<li><a href="#bookmark"';
    IEstring +=  'onClick="javascript:window.external.AddFavorite(location.href,document.title)\;">';
    IEstring +=  'Bookmark in IE<\/a><\/li> ';
 // IE start page
    IEstring +=  '<li><a href="#startpage"';
    IEstring +=  'onClick="this.style.behavior=\'url(#default#homepage)\'\;this.setHomePage(location.href)\;">';
    IEstring +=  'Make this your start page<\/a><\/li>';

    var agt = navigator.userAgent.toLowerCase();
    var ieAgent = agt.indexOf('msie');

    if (ieAgent != -1) {
        $theButtonList += IEstring;
    }

 //button list end
    $theButtonList += '</ul>';

 // write list to page
   document.write($theButtonList);
}