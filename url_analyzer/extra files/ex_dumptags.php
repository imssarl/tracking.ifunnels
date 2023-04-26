<?
    include ("htmlparser.inc");
	
	$varBold="";
	$varItalic="";
	$varTitle="";
	$varhead="";
	$objname="";	
    $htmlText = "<html><!-- comment --><head><title>title contents from ashish </title>this is a head content</head><body>This is the boby <b> b1 </b> <b> b2 </b> <i> value i1 </i> <i> value i2 </i> this is body contents</body></html>";
    $parser = new HtmlParser($htmlText);
	$i=1;
    while ($parser->parse()) 
	{
        echo "<li> i".$i;
		echo "-----------------------------------\r\n";
         echo "<li>1Node type: " . $parser->iNodeType . "\r\n";
         echo "<li>2Node name: " . $parser->iNodeName . "\r\n";
		  echo "<li>3Node value:" . $parser->iNodeValue . "\r\n<li>";
		  $objname=($parser->iNodeName);
	     if($objname == "b")
		 {
		  echo "come outside";
		 
		  echo "<li>obj name===>".$objname;
		 if($parser->iNodeType==2)
		 {   
			 $varBold=$varBold ." ".$parser->iNodeValue;
			 
		 }
         echo "<li>3Node value=============>> " . $parser->iNodeValue . "\r\n\n\n";
		 }
		$i=$i+1; 
    }
	echo "<li> Bold Text ".$varBold;
	echo "<li> Italic Text ".$varItalic;
?>
