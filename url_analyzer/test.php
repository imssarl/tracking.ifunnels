<?php
//Want a really simply file directory viewer? Take out the comments and it's obfuscated!
// grab a full file listing from the current and sub directories and show them as anchored
//(linked) files.

// pull a full file listing - requires the Unix 'find' and 'sort commands. 'find' will retrieve a
//list of all files from the current directory, 'sort' will sort the listing, and 'explode' will split
//all files into an array passed into $filelist.

$filelist = explode("\n",`find .|sort`);

// for each item (file) in the array...

for ($count=0;$count<count($filelist);$count++) {

// get the filename (including preceding directory, ie: ./software/gth1.0.9.tar.gz)

$filename=$filelist[$count];

// if it's not a directory, display linked

if (!is_dir($filename))
printf("<a href=\"%s\">%s</a><br>\n",$filename,$filename);

// otherwise tell the user it's a "category"

else printf("<p>Category: %s<p>\n",$filename);
}

?>
