// ====================================================================
//       URLEncode and URLDecode functions
//
// Copyright Albion Research Ltd. 2002
// http://www.albionresearch.com/
//
// You may copy these functions providing that 
// (a) you leave this copyright notice intact, and 
// (b) if you use these functions on a publicly accessible
//     web site you include a credit somewhere on the web site 
//     with a link back to http://www.albionresearch.com/
//
// If you find or fix any bugs, please let us know at albionresearch.com
//
// SpecialThanks to Neelesh Thakur for being the first to
// report a bug in URLDecode() - now fixed 2003-02-19.
// And thanks to everyone else who has provided comments and suggestions.
// ====================================================================
function URLEncode(id)
{
	
	// The Javascript escape and unescape functions do not correspond
	// with what browsers actually do...
	var SAFECHARS = "0123456789" +					// Numeric
					"ABCDEFGHIJKLMNOPQRSTUVWXYZ" +	// Alphabetic
					"abcdefghijklmnopqrstuvwxyz" +
					"-_.!~*'()";					// RFC2396 Mark characters
	var HEX = "0123456789ABCDEF";

	var plaintext = document.getElementById(id).value;
	var encoded = "";
	for (var i = 0; i < plaintext.length; i++ ) {
		var ch = plaintext.charAt(i);
	    if (ch == " ") {
		    encoded += "+";				// x-www-urlencoded, rather than %20
		} else if (SAFECHARS.indexOf(ch) != -1) {
		    encoded += ch;
		} else {
		    var charCode = ch.charCodeAt(0);
			if (charCode > 255) {
			    alert( "Unicode Character '" 
                        + ch 
                        + "' cannot be encoded using standard URL encoding.\n" +
				          "(URL encoding only supports 8-bit characters.)\n" +
						  "A space (+) will be substituted." );
				encoded += "+";
			} else {
				encoded += "%";
				encoded += HEX.charAt((charCode >> 4) & 0xF);
				encoded += HEX.charAt(charCode & 0xF);
			}
		}
	} // for

//document.URLForm.F2.value = encoded;
 //document.URLForm.F2.select();
	return encoded;
};

function URLDecode(encoded)
{
   // Replace + with ' '
   // Replace %xx with equivalent character
   // Put [ERROR] in output if %xx is invalid.
   var HEXCHARS = "0123456789ABCDEFabcdef"; 
   var encoded;
   var plaintext = "";
   var i = 0;
   while (i < encoded.length) {
       var ch = encoded.charAt(i);
	   if (ch == "+") {
	       plaintext += " ";
		   i++;
	   } else if (ch == "%") {
			if (i < (encoded.length-2) 
					&& HEXCHARS.indexOf(encoded.charAt(i+1)) != -1 
					&& HEXCHARS.indexOf(encoded.charAt(i+2)) != -1 ) {
				plaintext += unescape( encoded.substr(i,3) );
				i += 3;
			} else {
				alert( 'Bad escape combination near ...' + encoded.substr(i) );
				plaintext += "%[ERROR]";
				i++;
			}
		} else {
		   plaintext += ch;
		   i++;
		}
	} // while
   //document.URLForm.F1.value = plaintext;
   //document.URLForm.F1.select();
   return plaintext;
};



function hideMessage(divName, timeout)

{

	window.setTimeout('document.getElementById("'+divName+'").innerHTML="&nbsp;";', timeout);

}

function explodeStr(item,delimiter) {

	tempArray=new Array(1);

	var Count=0;

	var tempString=new String(item);

	while (tempString.indexOf(delimiter)>0) {

		tempArray[Count]=tempString.substr(0,tempString.indexOf(delimiter));

		tempString=tempString.substr(tempString.indexOf(delimiter)+delimiter.length,tempString.length-tempString.indexOf(delimiter)+1);

		Count=Count+1

	}

	tempArray[Count]=tempString;

	return tempArray;

}

function delRow(tablename, rowname)

{

	r = document.getElementById(rowname).rowIndex;

	document.getElementById(tablename).deleteRow(r)

}

function addRow(tablename, rowname, all, ih)

{



	var tbl = document.getElementById(tablename);



	var lastRow = tbl.rows.length;



	var row = tbl.insertRow(lastRow);

	

	row.id = rowname;

	

//	alert("no"+lastRow);

	

//	tr.innerHTML = innermatter;

//	alert(tr.innerHTML);

	if (all==1)

	{

		var cel1 = row.insertCell(0);

		var cel2 = row.insertCell(1);

		var cel3 = row.insertCell(2);

		var cel4 = row.insertCell(3);

		var cel5 = row.insertCell(4);

		var cel6 = row.insertCell(5);

		var cel7 = row.insertCell(6);

		var cel8 = row.insertCell(7);

		var cel9 = row.insertCell(8);

		var cel10 = row.insertCell(9);

		var cel11 = row.insertCell(10);

		var cel12 = row.insertCell(10);

		var cel13 = row.insertCell(12);

		

		cel1.align = "center";

		cel2.align = "left";

		cel3.align = "center";

		cel4.align = "center";

		cel5.align = "center";

		cel6.align = "center";

		cel7.align = "center";

		cel8.align = "center";

		cel9.align = "center";

		cel10.align = "center";

		cel11.align = "center";

		cel12.align = "center";

		cel13.align = "center";

		

		cel1.className = "backcolor1";

		cel2.className = "backcolor1";

		cel3.className = "backcolor1";

		cel4.className = "backcolor1";

		cel5.className = "backcolor1";

		cel6.className = "backcolor1";

		cel7.className = "backcolor1";

		cel8.className = "backcolor1";

		cel9.className = "backcolor1";

		cel10.className = "backcolor1";

		cel11.className = "backcolor1";

		cel12.className = "backcolor1";

		cel13.className = "backcolor1";

		

		

		cel1.innerHTML = ih[1];

		cel2.innerHTML = ih[2];

		cel3.innerHTML = ih[3];

		cel4.innerHTML = ih[4];

		cel5.innerHTML = ih[5];

		cel6.innerHTML = ih[6];

		cel7.innerHTML = ih[7];

		cel8.innerHTML = ih[8];

		cel9.innerHTML = ih[9];

		cel10.innerHTML = ih[10];

		cel11.innerHTML = ih[11];

		cel12.innerHTML = ih[12];

		cel13.innerHTML = ih[13];

	}

	else if (all==0)

	{

			var cel1 = row.insertCell(0);

			cel1.colSpan = 13;

			cel1.align = "center";

			cel1.innerHTML = ih[14];

	}

	else if (all==2)

	{

			var cel1 = row.insertCell(0);

			cel1.colSpan = 13;

			cel1.align = "center";

			cel1.innerHTML = ih[2];

	

	}



}

function trim(value)
{
	var temp = value;
	var obj = /^(\s*)([\W\w]*)(\b\s*$)/;
	if (obj.test(temp)) { temp = temp.replace(obj, '$2'); }
	var obj = /[ \n]+/g;
	temp = temp.replace(obj, " ");
	if (temp == " ") { temp = ""; }
	return temp;
}



function isValidNumber(value)

{

  var obj=/^[1-9]+$/;

  var val;

  val =  obj.test(value);

  return val;

}