<?php

$file = fopen("welcome.txt", "r") or exit("Unable to open file!");

//Output a line of the file until the end is reached
while(!feof($file))
  {
  echo fgets($file). "<br />";
  }
fclose($file);

$newfilename = "testroshni.php";

@chmod($newfilename,0755);

$fp = @fopen($newfilename,"x+");
if ($fp)
		{
			
			fputs($fp,'Hello Mam');
			
			fclose($fp);

		}


?>