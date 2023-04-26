<?
include('highlighting_skipphtml.php');
$teststring='<img src="image.gif">image.gif <small>a</small>';

$HIGH= new highlighting_skipphtml();
echo $HIGH->dohighlight('image',$teststring)."\n";

//first parameter can be an array too

$HIGH= new highlighting_skipphtml();
echo $HIGH->dohighlight(array('image','a'),$teststring)."\n";

//to avoid double highlighting like <b>im<b>a</b>ge</b>,
//which doesn't seem to be a html error (w3c)
//set $doublecheck to one

$HIGH= new highlighting_skipphtml();
echo $HIGH->dohighlight(array('image','a'),$teststring,'<b>','</b>',1)."\n";

// using fonts

$HIGH= new highlighting_skipphtml();
echo $HIGH->dohighlight(array('image','a'),$teststring,'<font color="red">','</font>',1)."\n";

//or highlight directly if you like
$HIGH= new highlighting_skipphtml();
$HIGH->highlight(array('image','a'),$teststring,'<font color="red">','</font>',1);
echo $teststring."\n";
?>