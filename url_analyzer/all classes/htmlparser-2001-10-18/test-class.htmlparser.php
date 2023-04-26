<? 
// for test class.htmlparser

include_once('class.htmlparser.php');

$fp = fopen('php://stdin', 'r') or die('stdin');
$s = fread($fp, 1048576);
$h = new HTMLin($s);
echo $h->show();
fclose($fp);

?>
