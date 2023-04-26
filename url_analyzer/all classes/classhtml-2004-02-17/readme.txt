This class is based in a superclass, the clsTag class. All the classes extends this one. 
The Properties of this class are:

	$indent      : used to set the indentation of HTML code
	$outputCode  : holds the generated HTML code
	$tag         : holds the HTML tag being generated
	$child       : an array of objects that holds all the child nodes
                       of this tag. 
	$indenting   : deprecated. it was intended to act as a flag of
                       indentation

The methods are:

   InString(what,where): search through an array (where) looking for a
                         string (what)

   GetAttributes(addAttr,attr): this method mixes the default tag's 
                         attributes with the provided ones

   clsTag(tag,attr,indenting): the class Constructor. 

   getOutputCode: returns as a string the generated HTML code

   Generate: builds the code and output it.

   AddNode(node): insert a node in the tag tree. For example, you can add
                  a row inside a table, or an image between opening and 
                  closing A tags

   HookIt: it was introduced as a sort of parent::constructor callback 
           function.

   CloseTag: this is almost the only one method that is overcharged. It 
             adds the closing tag to the tree.


Classes that extends the clsTag class:

clsHTMLDocument: This should be the top class in the tree. It builds the HTML /HTML tags

clsHeaderSection: adds the HEAD /HEAD tags

clsTitle: adds the TITLE /TITLE tags

clsMeta: adds the META tag. This class overwrite the CloseTag method.

---------------

I think the most interesting part of this tutorial must be the support functions, the ones used to actually put this class to work. Those functions and their parameters are: 

In every case the child parameter can be an object or an array of objects
in every case, the attr parameter (if present and/or not empty) represents the tag's attributes, like the border of a table, or the src of an img. the format of this parameter is: attr1=val;attr2=val;attr3=val;....;attrn=val

fnHTML(object [child])

fnHeader(object [child])

fnTitle(string [title])

fnMeta(string [attr])

fnStyleSheet(string stylesheet, string [attr]): The stylesheet parameter is the location of the .css file. it will generate a <link rel=stylesheet type=text/css href=«the style sheet»>

fnBody(string [attr], object [child])

fnTable(string [attr], object [child])

fnRow(string [attr], object [child])

fnCell(string [text], string [font], string [attr], object [child]): The text parameter is used in conjunction with the font parameter, and is used when you want to show text inside a cell, instead another tag.

fnFont(string [text], string [attr], object [child]): Just like the fnCell, but the font there is attr here.

fnParagraph(string [text], string [font], string [attr], object [child]): See fnCell

fnShowText(string text, string [attr]): insert text without formatting

fnImg(string src, string [id], string [attr])

fnLink(string [href], string [text], string [font], string [attr], object [child])

fnForm(string [action], string [name], string [hiddens], string [attr], object [child]): the hiddens parameter has the same format that the attr parameter, and it will generate the <input type=hidden> tags according to the name=value pairs.

fnOption: This function is used internaly, and should NEVER be used by you

fnSelect(string name, string [values], string [selvalue], string [attr]): the values parameter is an array. Its format is values["the_val"]="the text". the selvalue is a string, matching one key in the values array. 

fnInput(string name,string [value], string [size], string [maxlength], string [attr]): It generates a <input type=text>

fnPassword(string name,string [value], string [size], string [maxlength], string [attr])

fnFile(string name, string [attr]): Builds a <input type=file>

fnTextArea(string name,string [value], string [rows], string [cols], string [attr]): It generates a <textarea>value</textarea>

fnHidden(string name, string [value], string [attr])

----------------------------

I will add more documentation in a few days... but I think the other support functions are quite self-explainatory. Below, a few examples...

----------------------------

$document=fnHtml(array(
	fnHeader(array(
		fnTitle("This is a test"),
		fnStyleSheet("styles/mystyle.css")
	)),
	fnBody(
		fnTable("",array(
			fnRow("",
				fnCell("This is a text","","align=center;bgcolor=#c0c0c0")
			)
		))
	)
));

$document->Generate();

The example above will generate this code:

<html>
	<head>
		<title>This is a test</title>
		<link rel=stylesheet type=text/css href="styles/mystyle.css">
	</head>
	<body>
		<table border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td align=center bgcolor="#c0c0c0">This is a test</td>
			</tr>
		</table>
	</body>
</html>



