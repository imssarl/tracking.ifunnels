<?php 
# 
# indrek päri
# indrek@indrek.ee
# 

# parser class
include_once('html_parser.php');

# html file to parse
$file = "em.htm";

# new class ( filename & show linenumbers = 1 or don't show = 0 )
$parser = new html_parser($file,1);

# pint out the result
echo $parser->content;

?>