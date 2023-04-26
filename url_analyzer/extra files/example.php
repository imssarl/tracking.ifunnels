<?php

#for mathcing a tag
$pattern = '/<(a|input)\s+[^>]*href\s*=\s*(?:"([^"]+)"|\'([^\']+)\'|([^\s>]+))/is';



$html=' <table border="0" cellpadding="2" cellspacing="1" width="100%">
            <tr>
              <td width="100%" class="productlink"><b><font color="#CC0000">The
                final destination for software resellers.</font><br>
                </b>If you plan to use your selling skills to make money, we
                offer tons of software with resale rights and source code. <br>
                We not only give you the software, we also give you the source code, the
                marketing materials that you will need to sell it and support to you and your customers for any bugs in the system.<br>
                You can also  purchase the software without
                the source code.<br>
                <b>» </b>Have a look at any of our products by selecting it
                from the list on the right.<br>
                <b>» </b> <b> text </b><a href="rikin">Click
                here to visit the reseller forum</a> [<script src="rikin"></script> Topics] </td>
            </tr>
          </table> ';

//$pattern="|".$tags[$i]['open']."(.*)".$tags[$i]['close']."|U";
//preg_match_all($pattern,$string,$matches, PREG_PATTERN_ORDER);
//preg_match_all("|<[^>+>[.*]</*>+>|", $html, $matches);
//preg_match_all("|[^'<b>'](.*)[<\/font>]|", $html, $matches);

//preg_match_all("|[^<b>](.*)['<\/b>$']|", $html, $matches);
$matches=strip_tags($html,'<b>');
$ma=explode('<b>',$matches);
//echo $ma[0];

///echo $ma[1];

function removeTags($html)
{
echo $html;

   global $allowedTags;

   
   /**
   * Allow these tags
   */
   if (empty($allowedTags)) $allowedTags = "<b>";
   $source = strip_tags($source, $allowedTags);
   echo "source ".$source;
	echo $allowedTags;
   return preg_replace('/<(.*?)>/ie', "'<'.removeAttributes('\\1').'>'", $source);
}

	$ma=removeTags($html);
	echo "<li>".$ma;

//preg_match_all("/['^<b>']*/", $html, $matches);
/*echo $matches;
for ($i=0; $i< count($matches[0]); $i++) {
		echo "<li> matched: " . $matches[0][$i] . "\n";
  }*/
  

?>
