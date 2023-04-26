<br/>
<br/>
<h1 align="center">Welcome to {$arrData.url}</h1>
<div align="center">
<div style="width:50%; padding:15px;" align="left" >
		<ul class="menu-h-d">
			<li><a href="">Link Popularity</a>
				<ul>
					<li><a href="http://www.google.com/search?q=link:{$arrParams.url}&num=100" target="_blank">Google</a></li>
					<li><a href="http://www.altavista.com/sites/search/web?q=link:{$arrParams.url}+-url%3A{$arrParams.only_name}&nbq=100" target="_blank">AltaVista</a></li>
					<li><a  href="http://search.msn.com/results.asp?FORM=MSNH&v=1&RS=CHECKED&q=link:{$arrParams.url}" target="_blank">MSN</a></li>
				</ul>
			</li>
			<li><a href="">Other Listings</a>
				<ul>
					<li><a  href="http://www.google.com/search?hl=nl&q=site%3A{$arrParams.only_name}+%2B{$arrParams.only_name}&lr=&num=100" target="_blank" >Google</a></li>
					<li><a  href="http://www.altavista.com/sites/search/web?q={$arrParams.only_name}&sb=all&srin=all&d2=0&d0=&d1=&sgr=all&rc=dmn&swd={$arrParams.only_name}&lh=&nbq=100&pg=ps" target="_blank">AltaVista</a></li>
					<li><a  href="http://search.msn.com/results.asp?q={$arrParams.only_name}&spoff=on&origq=&RS=CHECKED&FORM=SMCA&v=1&cfg=SMCINK&nosp=0&thr=&f=all&sort=date+dsc&rgn=&lng=&dom={$arrParams.only_name}&depth=&d0=&d1=&cf=&cy=SSO_EN_US&x=33&y=9" target="_blank">MSN Inktomi</a></li>
					<li><a  href="http://search.yahoo.com/bin/search?p={$arrParams.url}" target="_blank">Yahoo</a></li>
					<li><a  href="http://www.whois.sc/dmoz/{$arrParams.only_name}" target="_blank">DMOZ</a></li>
					<li><a  href="http://www.1stekeuze.nl/cgi-bin/search/keuze.cgi?Terms=url:{$arrParams.url}" target="_blank">1steKeuze.nl</a></li>
				</ul>
			</li>
			<li><a href="">Page_Misc.</a>
				<ul>
					<li><a  href="http://www.checkdomain.com/cgi-bin/checkdomain.pl?domain={$arrParams.domain}" target="_blank">Owner of domain</a></li>
					<li><a  href="view-source:{$arrParams.url}" target="_blank">View Source HTML</a></li>
					<li><a  href="http://validator.w3.org/checklink?uri={$arrParams.url}&depth=&submit=Check" target="_blank">W3.org Check Links</a></li>
					<li><a  href="http://www.google.com/search?sourceid=navclient&q=cache:{$arrParams.url}" target="_blank">Google Cache</a></li>
					<li><a  href="http://www.google.com/search?sourceid=navclient&q=related:{$arrParams.url}&num=100" target="_blank">Google Similar</a></li>
					<li><a  href="http://altavista.com/cgi-bin/query?pg=q&stype=stext&q=like:{$arrParams.url}&nbq=100" target="_blank" style="width:auto">AltaVista Related</a></li>
					<li><a  href="http://babel.altavista.com/translate.dyn?urltext={$arrParams.url}&lp=it_en" target="_blank" style="width:auto">AltaVista Translate</a></li>
					<li><a  href="http://www.altavista.com/sites/search/web?q=image:{$arrParams.only_name}&nbq=100" target="_blank" style="width:auto">AltaVista(remote)Images</a></li>
					<li><a  href="http://xslt.alexa.com/data?cli=16&url={$arrParams.url}" target="_blank">Alexa Related</a></li>
					<li><a  href="http://web.archive.org/web/*/{$arrParams.url}" target="_blank">Wayback Machine</a></li>
					<li><a  href="http://www.alexa.com/data/details/traffic_details?q=&p=TrafficDet_W_t_40_L1&range=1y&size=medium&compare_sites=&url={$arrParams.only_name}" target="_blank">Alexa Traffic 1y</a></li>
					<li><a  href="http://ugweb.cs.ualberta.ca/~gerald/lynx-me.cgi?url={$arrParams.url};list;message=off" target="_blank">Lynx-me</a></li>
				</ul>
			</li>
			<li><a href="">Validate</a>
				<ul>
					<li><a href="http://validator.w3.org/check?uri={$arrParams.url}" target="_blank">W3.org HTML</a></li>
					<li><a href="http://jigsaw.w3.org/css-validator/validator?uri={$arrParams.url}&warning=no" target="_blank">W3.org CSS</a></li>
				</ul>
			</li>
		</ul>
	</div>	
</div>	
<form class="wh" style="width:50%;">
{if $arrData.show_title==1||$arrData.show_meta}
	<fieldset>
		<legend>Page Elements</legend>
		<ol>
			{if $arrData.show_title==1}
			<li>
				<label>Title Tag:</label><textarea>{$arrParams.title}</textarea>
			</li>
			{/if}
			{if $arrData.show_meta==1}
			<li>
				<label>Meta Tags:</label><p>{foreach from=$arrParams.meta key=k item=i}<b><i>{$k}:</i></b> <span>{$i}</span><br/>{/foreach}</p>
			</li>
			{/if}
		</ol>
	</fieldset>
{/if}
	<fieldset>
		<legend>Words Phrases</legend>

		<ol>
			<li>
			{if !empty($arrList)}
				<table class="table" width="100%">
					<tr>
						<th>Word</th>
						<th>Repeats</th>
						{*<th>Density</th>*}
					</tr>
					{foreach from=$arrList item=i name=j}
					<tr {if $smarty.foreach.j.iteration%2==0} class="matros" {/if}>
						<td>{$i.words}</td>
						<td align="center">{$i.score}</td>
						{*<td align="center"></td>*}
					</tr>
					{/foreach}
				</table>
			{/if}
			</li>
		</ol>
	</fieldset>
</form>