<?php 

$url_name=$txt_Inputurl;

?>



<div id="chromemenu">

<table width="65%"  border="0" align="left" cellpadding="0" cellspacing="0">

          

<tr  bgcolor="#00CCFF">



<th valign="bottom" nowrap scope="col"><table><tr><td><div valign="center" align="center">&nbsp;&nbsp;<a href="#" onMouseover="cssdropdown.dropit(this,event,'drop_popularity')">LinkPopularity</a></div></td></tr></table></th>



<th valign="bottom" nowrap scope="col"><table><tr><td><div valign="center" align="center">&nbsp;&nbsp;<a href="#" onMouseover="cssdropdown.dropit(this,event,'drop_listing')"> Other Listings</a></div></td></tr></table></th>



<th valign="bottom" nowrap scope="col"><table><tr><td><div valign="center" align="center">&nbsp;&nbsp;<a href="#" onMouseOver="cssdropdown.dropit(this,event,'drop_misc')"> Page_Misc.</a></div></td></tr></table></th>



<th valign="bottom" nowrap scope="col"><table><tr><td><div valign="center" align="center">&nbsp;&nbsp;<a href="#" onMouseover="cssdropdown.dropit(this,event,'drop_validate')">Validate</a></div></td></tr></table></th>	


<!-- comment on 09_jan -->
<!--<th valign="bottom" nowrap scope="col"><table><tr><td><div valign="center" align="center">&nbsp;&nbsp;<a href="#" onMouseover="cssdropdown.dropit(this,event,'drop_help')"> Help</a></div></td></tr></table></th> -->

</tr>

</table>

<div id="drop_popularity" class="dropmenudiv">

<a  href="http://www.google.com/search?q=link:<?php echo $url_name;?>&num=100" target="_blank" >Google</a>

<a  href="http://www.altavista.com/sites/search/web?q=link:<?php echo $url_name;?>+-url%3A<?php echo $only_name;?>&nbq=100" target="_blank">AltaVista</a>

<a  href="http://www.alltheweb.com/search?cat=web&advanced=1&type=all&query=&jsact=&lang=any&charset=utf-8&wf%5Bn%5D=3&wf%5B0%5D%5Br%5D=&wf%5B0%5D%5Bq%5D=<?php echo $url_name;?>&wf%5B0%5D%5Bw%5D=link.all%3A&wf%5B1%5D%5Br%5D=-&wf%5B1%5D%5Bq%5D=<?php echo $only_name;?>&wf%5B1%5D%5Bw%5D=url.all%3A&wf%5B2%5D%5Br%5D=-&wf%5B2%5D%5Bq%5D=&wf%5B2%5D%5Bw%5D=&dincl=&dexcl=&age=&size%5Bp%5D=%3C&size%5Bv%5D=&size%5Bx%5D=0&hits=100&nooc=on" target="_blank">Fast</a>

<a  href="http://search.msn.com/results.asp?FORM=MSNH&v=1&RS=CHECKED&q=link:<?php echo $url_name;?>" target="_blank">MSN</a>

<a  href="http://search.lycos.com/main/default.asp?lpv=1&loc=searchbox&query=&adv=1&wfr=&wfw=link.all%3A&wfq=<?php echo $url_name;?>&wfr=-&wfw=url.all%3A&wfq=<?php echo $only_name;?>&wfr=-&wfw=&wfq=&wfc=3&df0=i&dfq=&dfc=1&lang=&ca=&submit_button=Submit+Search" target="_blank">Lycos</a>

</div>





<div id="drop_listing" class="dropmenudiv">

 <a  href="http://www.google.com/search?hl=nl&q=site%3A<?php echo $only_name;?>+%2B<?php echo $only_name;?>&lr=&num=100" target="_blank"  style="width:auto">Google</a>

 <a  href="http://www.altavista.com/sites/search/web?q=<?php echo $only_name;?>&sb=all&srin=all&d2=0&d0=&d1=&sgr=all&rc=dmn&swd=<?php echo $only_name;?>&lh=&nbq=100&pg=ps" target="_blank">AltaVista</a>

 <a  href="http://search.lycos.com/main/default.asp?lpv=1&loc=searchbox&query=&adv=1&wfr=&wfw=url.all%3A&wfq=<?php echo $only_name;?>&wfr=%2B&wfw=&wfq=&wfr=-&wfw=&wfq=&wfc=3&df0=i&dfq=&df1=e&dfq=&dfc=2&lang=&ca=&submit_button=Submit+Search" target="_blank">Lycos</a>

 <a  href="http://search.msn.com/results.asp?q=<?php echo $only_name;?>&spoff=on&origq=&RS=CHECKED&FORM=SMCA&v=1&cfg=SMCINK&nosp=0&thr=&f=all&sort=date+dsc&rgn=&lng=&dom=<?php echo $only_name;?>&depth=&d0=&d1=&cf=&cy=SSO_EN_US&x=33&y=9" target="_blank">MSN Inktomi</a>

 <a  href="http://search.yahoo.com/bin/search?p=<?php echo $url_name;?>" target="_blank">Yahoo</a>

 <a  href="http://www.whois.sc/dmoz/<?php echo $only_name;?>" target="_blank">DMOZ</a>  

 <a  href="http://www.looksmart.com/r_search?look=&key=<?php echo urlencode($url_name);?>" target="_blank">Looksmart</a>

 <a  href="http://www.1stekeuze.nl/cgi-bin/search/keuze.cgi?Terms=url:<?php echo $url_name;?>" target="_blank">1steKeuze.nl</a>

</div>



<div id="drop_misc" class="dropmenudiv" style="width:auto">

<a  href="http://www.checkdomain.com/cgi-bin/checkdomain.pl?domain=<?php echo $only_name;?>" target="_blank">Owner of domain</a>

<a  href="view-source:<?php echo $url_name;?>" target="_blank">View Source HTML</a>

<!--<a  href="http://www.alltheweb.com/search?advanced=1&cat=web&limip=208.116.9.21" target="_blank">More on IP ?</a>  -->

<a  href="http://validator.w3.org/checklink?uri=<?php echo $url_name;?>&depth=&submit=Check" target="_blank">W3.org Check Links</a>

<a  href="http://www.google.com/search?sourceid=navclient&q=cache:<?php echo $url_name;?>" target="_blank">Google Cache</a>

<a  href="http://www.google.com/search?sourceid=navclient&q=related:<?php echo $url_name;?>&num=100" target="_blank">Google Similar</a>

<a  href="http://altavista.com/cgi-bin/query?pg=q&stype=stext&q=like:<?php echo $url_name;?>&nbq=100" target="_blank" style="width:auto">AltaVista Related</a>

<a  href="http://babel.altavista.com/translate.dyn?urltext=<?php echo $url_name;?>&lp=it_en" target="_blank" style="width:auto">AltaVista Translate</a>

<a  href="http://www.altavista.com/sites/search/web?q=image:<?php echo $only_name;?>&nbq=100" target="_blank" style="width:auto">AltaVista(remote)Images</a>

<a  href="http://xslt.alexa.com/data?cli=16&url=<?php echo $url_name;?>" target="_blank">Alexa Related</a>

<a  href="http://web.archive.org/web/*/<?php echo $url_name;?>" target="_blank">Wayback Machine</a>

<a  href="http://www.alexa.com/data/details/traffic_details?q=&p=TrafficDet_W_t_40_L1&range=1y&size=medium&compare_sites=&url=<?php echo $only_name;?>" target="_blank">Alexa Traffic 1y</a>

<a  href="http://www.cs.toronto.edu/db/topic/topic.cgi?url=<?php echo $only_name;?>&engine=AltaVista&lno=300&terms=" target="_blank">TOPIC AltaVista</a>

<a  href="http://www.cs.toronto.edu/db/topic/topic.cgi?url=<?php echo $only_name;?>&engine=Lycos&lno=300&terms=" target="_blank">TOPIC Lycos</a>

<a  href="http://ugweb.cs.ualberta.ca/~gerald/lynx-me.cgi?url=<?php echo $url_name;?>;list;message=off" target="_blank">Lynx-me</a></div>

</div>



<div id="drop_validate" class="dropmenudiv">

<a href="http://www.searchengineworld.com/cgi-bin/validator/validate.cgi?uri=<?php echo $url_name;?>&doctype=Inline" target="_blank">SEW HTML</a>  <!-- to do-->

<a href="http://validator.w3.org/check?uri=<?php echo $url_name;?>" target="_blank">W3.org HTML</a>	

<a href="http://jigsaw.w3.org/css-validator/validator?uri=<?php echo $url_name;?>&warning=no" target="_blank">W3.org CSS</a>

<a href="http://www.web-caching.com/cgi-web-caching/cacheability.py?query=<?php echo $url_name;?>&descend=on" target="_blank">Cacheability </a>	

<!--<a href="http://www.searchengineworld.com/cgi-bin/robotcheck.cgi?url=http://www.a2zhelp.com/robots.txt&action=go" target="_blank">SEW Robots.txt </a>  --><!-- to do-->

</div>



<div id="drop_help" class="dropmenudiv">

<a target="_blank" href="aboutus.php"> About Us</a>

<a target="_blank"href="facts.php"> Facts</a>

<a target="_blank" href="readme.htm"> Read file</a>

</div>