<?php
include("config.php");

$link = mysql_connect($host,$username,$password) or die("Connect Error: ".mysql_error());
print "Successfully connected.\n";

mysql_select_db($database, $link) or die("Select DB Error: ".mysql_error());

mysql_query("CREATE TABLE keywords( keyword VARCHAR(128), searches int, competition int, kei DECIMAL(10,3), rs DECIMAL(10,3), PRIMARY KEY (keyword))") or die("Create table Error: ".mysql_error());

mysql_close($link);
print "Successfully created tables.\n";

?>