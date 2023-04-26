<?
  include ("htmlparser.inc");
  $htmlText="<html><!-- commentdasddaddad --><head><title>title contents from ashish </title>this is a head</head><body>This is the<b> b1 </b> <b> b2 </B> <i> i 1 </i>";
   // $parser = HtmlParser_ForFile ("testfile.html");
   //$parser = HtmlParser_ForURL ("http://yahoo.com");
   
   $parser = new HtmlParser($htmlText);
   $i=0;
    while ($parser->parse())
	 {
	  echo "<li>value of i==>>".$i;
      echo "<li>Name=" . $parser->iNodeName . ";";
      echo "<li>Type=" . $parser->iNodeType . ";";
        if ($parser->iNodeType == NODE_TYPE_TEXT || $parser->iNodeType == NODE_TYPE_COMMENT) {
            echo "<li>Value====>>>>='" . $parser->iNodeValue . "'";
        }
        echo "\r\n";
        if ($parser->iNodeType == NODE_TYPE_ELEMENT) {
                echo "ATTRIBUTES: ";
				echo "<br><br>";
                $attrValues = $parser->iNodeAttributes;
                $attrNames = array_keys($attrValues);
                $size = count($attrNames);
                for ($i = 0; $i < $size; $i++) {
                        $name = $attrNames[$i];
                        echo "<li>attrNames".$attrNames[$i] . "=\"" . $attrValues[$name] . "\" ";
                }
        }
        echo "\r\n";
		$i=$i+1;
    }
?>
