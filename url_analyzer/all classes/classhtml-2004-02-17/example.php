<?
/*
 This is a full working example on how to use the classHTML classes

 Change the "Configuration" section on this script to meet your site's configuration.
*/

/*
Configuration Section
*/

$siteRoot = "/~admin306";
$includesDirectory = "/library";

/*
Including the support class
*/

include ("classHTML.php");


/*
Let's create an instance of clsHTML:
*/

$myDocument=fnHTML(
	fnHeader(
		fnTitle("This is an example on how to use this classes")
	)
);

/*
Now, we'll create a table
*/

$myTable=fnTable("width=70%;align=center;border=1;cellpadding=0",
	fnRow("bgcolor=FF0000",array(
		fnCell("Author","color=#FFFFFF","width=30%"),
		fnCell("","","width=10%",
			fnFont("Year Born","color=#FFFFFF")
		),
		fnCell("Last Book Published","","width=60%")
	))
);

/*
Look at the above definition: You've create a table using fnTable, with a row. The row will have three columns, Look carefully the middle column. Do you see the difference? The first and third columns has the text and format of text as first and second parameters respectively. The middle column uses fnFont as the cell's child to show the text.

Now, we'll craete an array with some authors, years born and books:
*/

$authors=array(
	array("John Doe","1969","How to survive without being nobody"),
	array("Jane Doe","1970","How survive being the wife of John Doe"),
	array("Daniel Marjos",1970,"Programming PHP: The best way to put content on the web")
)

/*
Now, we'll add the authors data into the table:
*/

for ($theAuthor=0; $theAuthor<count($authors); $theAuthor++) {
	$myTable->AddNode(
		fnRow("",array(
			fnCell($authors[$theAuthor][0]),
			fnCell($authors[$theAuthor][1]),
			fnCell($authors[$theAuthor][2])
		))
	);
}

/*
The next thing to do is insert the table into the body section of the document:
*/


$myDocument->AddNode(
	fnBody("",$myTable)
);

/*
Now.. everything is on place. You can now generate the HTML code:
*/

$myDocument->Generate();

/*
That's all... Now, of course... if you want to get the generated code without sending it to the browser, you can use the getOutputCode() method of the class.

I hope this example help you using the class...

More examples coming soon.
*/

?>