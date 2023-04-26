<?php

/**
 * HTML Form Parser Example
 *
 * @package HtmlFormParser
 * @version $Id 1.0
 * @author Peter Valicek <Sonny2@gmx.DE>
 * @copyright 2004 Peter Valicek Peter Valicek <Sonny2@gmx.DE>
 */

require_once 'HtmlFormParser.php';
function get_url($url)
   {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		ob_start();
		curl_exec ($ch);
		curl_close ($ch);
		$content = ob_get_contents();
		ob_end_clean();
	   return $content;    
   }
$contents = get_url('http://www.yahoo.com');
echo $contents;
$parser =& new HtmlFormParser( $contents);
$result = $parser->parseForms();
echo count($result);
foreach($result as $rs)
{
foreach($rs as $r)
{
echo $r;
}
}
print_r($result);

?>