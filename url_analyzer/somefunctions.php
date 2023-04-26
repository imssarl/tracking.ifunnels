<?

//
// Various Parsing Functions used to "help" parse an HTML file for data.
//


//
// Remove forbidden HTML tags using the PHP strip_tags function
//
function removeTags($source)
{
   global $allowedTags;
   /**
   * Allow these tags
   */
   if (empty($allowedTags)) $allowedTags = '<table><tr><td><div><p><br>' . 
			'<h1><b><i><a><ul><li><pre><hr><blockquote><img>';
   $source = strip_tags($source, $allowedTags);
   return preg_replace('/<(.*?)>/ie', "'<'.removeAttributes('\\1').'>'", $source);
}


//
// Remove unwanted attributes from HTML source using the PHP preg_replace function
//
function removeAttributes($source)
{
   global $stripAttrib;
   // Disallow these attributes/prefix within a tag
   if (empty($stripAttrib)) $stripAttrib = 
			'javascript:[\S]+|onclick[\s\t\r\n]*=[\s\t\r\n]*[\S]+|ondblclick|onmousedown[\s\t\r\n]*=[\s\t\r\n]*[\S]+|onmouseup[\s\t\r\n]*=[\s\t\r\n]*[\S]+|onmouseover[\s\t\r\n]*=[\s\t\r\n]*[\S]+|' . 
			'onmousemove[\s\t\r\n]*=[\s\t\r\n]*[\S]+|onmouseout[\s\t\r\n]*=[\s\t\r\n]*[\S]+|onkeypress[\s\t\r\n]*=[\s\t\r\n]*[\S]+|onkeydown[\s\t\r\n]*=[\s\t\r\n]*[\S]+|onkeyup[\s\t\r\n]*=[\s\t\r\n]*[\S]+|' . 
			'valign[\s\t\r\n]*=[\s\t\r\n]*[\S]+|align[\s\t\r\n]*=[\s\t\r\n]*[\S]+|alt[\s\t\r\n]*=[\s\t\r\n]*[\S]+|border[\s\t\r\n]*=[\s\t\r\n]*[\S]+|' . 
			'cellpadding[\s\t\r\n]*=[\s\t\r\n]*[\S]+|cellspacing[\s\t\r\n]*=[\s\t\r\n]*[\S]+|width[\s\t\r\n]*=[\s\t\r\n]*[\S]+|height[\s\t\r\n]*=[\s\t\r\n]*[\S]+|background[\s\t\r\n]*=[\s\t\r\n]*[\S]+|src[\s\t\r\n]*=[\s\t\r\n]*[\S]+|style[\s\t\r\n]*=[\s\t\r\n]*[\S]+';
   return stripslashes(preg_replace("/[\s\t\r\n]+$stripAttrib/i", '', $source));
}


function reformatHTML($document) {
	// $document should contain an HTML document.
	// This will remove HTML tags, javascript sections
	// and white space. It will also convert some
	// common HTML entities to their text equivalent.
	
	$search = array ("'<script[^>]*?>.*?</script>'si",	// Strip out javascript
                 "'([\r\n]{1,})[\t\s]+'",				// Strip out white space
                 "'&(quot|#34);'i",						// Replace HTML entities
                 "'&(amp|#38);'i",
                 "'&(lt|#60);'i",
                 "'&(gt|#62);'i",
                 "'&(nbsp|#160);'i",
                 "'&(iexcl|#161);'i",
                 "'&(cent|#162);'i",
                 "'&(pound|#163);'i",
                 "'&(copy|#169);'i",
				 "'[\s\t]+'",
				 "'[\s]+>'",					// Strip Spaces at End of Tags
				 "'<tr'i",						// Split Table Rows
				 "'</tr>'i",					// Split Table Rows
				 "'<br>'i",						// Add Spacing
                 //"'&#(\d+);'e",					// evaluate as php
				 );
	$replace = array ("",
                 "\\1",
                 "\"",
                 "&",
                 "<",
                 ">",
                 " ",
                 chr(161),
                 chr(162),
                 chr(163),
                 chr(169),
				 " ",
				 ">",
				 "\n\n<tr",
				 "</tr>\n\n",
				 "\n<br>",
                 //"chr(\\1)",
				 );
	return preg_replace($search, $replace, $document);
}

//
// Split the page HTML
//
function splitPageHTML($document, $start_pattern, $end_pattern) {
	
	if (!empty($start_pattern)) {
		$start_split = preg_split($start_pattern, $document, 2);
		if (count($start_split) > 1) {
			//var_dump($start_split);
			$document = "";
			for ($i=1;$i<=count($start_split)-1;$i++) $document .= $start_split[$i];
		}
	}
	if (!empty($end_pattern)) {
		$end_split = preg_split($end_pattern, $document, 2);
		if (count($end_split) > 1) {
			//var_dump($end_split);
			$document = "";
			for ($i=0;$i<=count($end_split)-2;$i++) $document .= $end_split[$i];
		}
	}
	return $document;
}



/////////////////////////////////////////////////



function strip_leading_and_trailing_double_quotes($string) {
  while (substr($string,-1) == '"' || substr($string,0,1) == '"') {
	if (substr($string,-1) == '"') { $string = substr($string,0,strlen($string)-1); } // remove " from end of string
	if (substr($string,0,1) == '"') { $string = substr($string,1,strlen($string)-1); } // remove " from start of string
  }
  return $string;
}


function format_text_field($value, $width)
{
  if (!$width) { $width = 30; }
  $new_width = $width - strlen($value);
  $new_value = $value;
  for ($i=0; i<$width; $i++) { $new_value .= " "; }
  return ($new_value);
}


function truncate_string($string, $length = 70)
// This function will truncate a string and add "..." to a specified length.
{
  if (strlen($string) > $length) {
	// Reduce to $length-3 characters and add "..."
	$split = preg_split("/\n/", wordwrap($string, $length-3));
	$string = $split[0] . "...";
  }
  return ($string);
}


function ucfirst_title($string) { 
  // Split the words (\W) by delimiters, ucfirst and then recombine with delimiters.
  $temp = preg_split('/(\W)/', $string, -1, PREG_SPLIT_DELIM_CAPTURE );
  foreach ($temp as $key=>$word) {
		$temp[$key] = ucfirst(strtolower($word));
  }
  $new_string = join ('', $temp);
  // Do the Search and Replacements on the $new_string.
  $search  = array (' And ',' Or ',' But ',' At ',' In ',' On ',' To ',' From ',' Is ',' A ',' An ',' Am ',' For ',' Of ',' The ',"'S",' Cpa',' Llc',' Llp',' Md',' Dds',' Psy D',' Dmd',' Phd');
  $replace = array (' and ',' or ',' but ',' at ',' in ',' on ',' to ',' from ',' is ',' a ',' an ',' am ',' for ',' of ',' the ',"'s",' CPA',' LLC',' LLP',' MD',' DDS',' PSY D',' DMD',' PHD');
  $new_string = str_replace($search, $replace, $new_string);
  // Several Special Replacements ('s, McPherson, McBain, etc.) on the $new_string.
  $new_string = preg_replace("/Mc([a-z]{3,})/e", "\"Mc\".ucfirst(\"$1\")", $new_string);
  // Another Strange Replacement (example: "Pure-Breed Dogs: the Breeds and Standards") on the $new_string.
  $new_string = preg_replace("/([:;])\s+([a-zA-Z]+)/e", "\"$1\".\" \".ucfirst(\"$2\")", $new_string);
  // If this is a very low string ( > 60 char) then do some more replacements.
  if (strlen($new_string) > 60) {
	$search  = array (" With "," That ");
	$replace = array (" with "," that ");
	$new_string = str_replace($search, $replace, $new_string);
  }
  return ($new_string);
}

?>