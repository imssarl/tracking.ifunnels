function activateFlash(swfBig,swfSmall,dataURL,dataURL1,dataURL2,dividBig,dividSmall,disp_prop_bigDiv,disp_prop_smallDiv,divHeight,divWidth)
{
	document.write('<div id="'+dividBig+'" style="display:'+disp_prop_bigDiv+'">');
	document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="'+divWidth+'" height="'+divHeight+'" align="top">');
	document.write('<param name="allowScriptAccess" value="always" />');
	document.write('<param name="movie" value="'+swfBig+'" />');
	document.write('<param name="quality" value="high" />');
	document.write('<PARAM NAME=FlashVars VALUE="dataURL='+dataURL+'&dataURL1='+dataURL1+'&dataURL2='+dataURL2+'">');
	document.write('<param value="transparent" name="wmode" />');
	document.write('<param value="#ffffff" name="bgcolor"/>');
	document.write('<embed src="'+swfBig+'" FlashVars="dataURL='+dataURL+'&dataURL1='+dataURL1+'&dataURL2='+dataURL2+'" quality="high"  wmode="transparent" bgcolor="#ffffff" width="'+divWidth+'" height="'+divHeight+'" align="top" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />');
	document.write('</object>');
	document.write('</div>');
	
	document.write('<div id="'+dividSmall+'" style="display:'+disp_prop_smallDiv+'">');
	document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="75" height="75" align="top">');
	document.write('<param name="allowScriptAccess" value="always" />');
	document.write('<param name="movie" value="'+swfSmall+'" />');
	document.write('<param name="quality" value="high" />');
	document.write('<param name="dataURL" value="high" />');
	document.write('<param name="dataURL1" value="high" />');
	document.write('<PARAM NAME=FlashVars VALUE="dataURL='+dataURL+'">');
	document.write('<PARAM NAME=FlashVars VALUE="dataURL1='+dataURL1+'">');
	document.write('<param name="wmode" value="transparent" />');
	document.write('<param name="bgcolor" value="#ffffff" />');
	document.write('<embed src="'+swfSmall+'" FlashVars="dataURL='+dataURL+'&dataURL1='+dataURL1+'" quaslity="high" wmode="transparent" bgcolor="#ffffff" width="75" height="75" align="top" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />');
	document.write('</object>');
	document.write('</div>');
}

function activateSound(dataURL,basepath)
{
	document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="1" height="1">');
	document.write('<param name="movie" value="'+basepath+'swf_files/mp3player1.swf"/>');
	document.write('<param name="quality" value="high" />');
	document.write('<param name="dataURL" value="high" />');
	document.write('<PARAM NAME=FlashVars VALUE="dataURL='+dataURL+'"> ');
	document.write('<embed src="'+basepath+'swf_files/mp3player1.swf" FlashVars="dataURL='+dataURL+'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="1" height="1"></embed>');
	document.write('</object> ');
}

function activeImage(dataURL,basepath)
{
	document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="1" height="1">');
	document.write('<param name="movie" value="'+basepath+'swf_files/images1.swf" />');
	document.write('<param name="quality" value="high" />');
	document.write('<param name="dataURL" value="high" />');
	document.write('');
	document.write('<PARAM NAME=FlashVars VALUE="dataURL='+dataURL+'">');
	document.write('<embed src="'+basepath+'swf_files/images1.swf" FlashVars="dataURL='+dataURL+'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>  width="1" height="1"');
	document.write('</object>');
	
}

function bigflash()
{
	document.getElementById(dividBig).style.display = "block";
	document.getElementById(dividSmall).style.display = "none";
}

function smallflash()
{
	document.getElementById(dividSmall).style.display = "block";
	document.getElementById(dividBig).style.display = "none";
}
