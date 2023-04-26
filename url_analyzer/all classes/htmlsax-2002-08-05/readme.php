<?
/*
	(c) Copyright by Alexander Zhukov alex@veresk.ru. Released under LGPL
	TODO: correct comment handling
*/	

include("StateMachine.class.php");
include("AttributesParser.class.php");
include("HTMLSax.class.php");

// Sample HTML text
$html = "
		<html>
		<head>
		<title>
			my test title
		</title>
		</head>
		<body topmargin=10>
		<h1>A test text</h1>
		example text1
		example text2
		<a href=http://test/?asd>test link</a>
		<img src=\"/images/smile.gif\" width=100 height=100>
		<table width=100% bgcolor=\"black\">
			<tr color=#cceeff>
				<td>cell1</td><td>cell2</td>
			</tr>
			<tr>
              <td width='100%' class='productlink'><b><font color='#CC0000'>The
                final destination for software resellers.</font><br>

                </b>If you plan to use your selling skills to make money, we
                offer tons of software with resale rights and source code. <br>
                We not only give you the software, we also give you the source code, the
                marketing materials that you will need to sell it and support to you and your customers for any bugs in the system.<br>
                You can also  purchase the software without
                the source code.<br>
                <b>» </b>Have a look at any of our products by selecting it
                from the list on the right.<br>
                <b>» </b><a href='http://www.a2zhelp.com/forum/forumtopics.asp?id=4'>Click
                here to visit the reseller's forum</a> [<script src='http://www.a2zhelp.com/forum/forumNumMessages.asp?id=4'></script> Topics] </td>

            </tr>
		</table>
		</body>
		</html>
		";

class MySax extends HTMLSax
{
	function MySax()
	{
		$this->HTMLSax();
		$this->skipWhitespace = 1; // turn this on if you want to skip whitespace between tags
		$this->trimDataNodes = 1; // turn this on if you want to trim the data nodes 
	}
	
	function handle_data($data)
	{ 
	 
		echo "$data ";
	}

	function handle_start_tag($tag,$attribs)
	{
		echo "start tag \"$tag\"<br>";
	}

	function handle_end_tag($tag)
	{
		echo "end tag \"$tag\"<br>";
	}

	
}

$p = new MySax();
$p->parse($html);

?>