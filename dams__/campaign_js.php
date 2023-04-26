<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script language='javascript' src ='jscripts/picker.js'></script>

<script language="JavaScript">
	function validate_form(frm)
	{ 
		tinyMCE.triggerSave();
		msg="";
		flag=true;
		if (document.getElementById("start_date").value > document.getElementById("end_date").value)
		{
			flag=false;
			msg+= "Start Date Should not greater\n";
			//return false;
		}
		if(frm.campaign_name.value=="")
		{
			flag=false;
			msg+="Campaign Name should not be empty \n";
			frm.campaign_name.focus();
		}
		if(!frm.positionS.checked && !frm.positionF.checked && !frm.positionC.checked)
		{
			flag=false;
			msg+="Please select atleast one ad type \n";
		}

	if(frm.positionS.checked)
	{ //alert(frm.contents.value);
		if(!frm.content_type[0].checked && !frm.content_type[1].checked)
		{
			flag=false;
			msg+="Please choose option Slide In Content Type  \n";
			//frm.content_type.focus();
		}
		if(frm.content_type[0].checked && frm.txt_contents.value=="")
		{
			flag=false;
			msg+="Contents should not be empty \n";
			//frm.contents.focus();
		}
		if(frm.content_type[1].checked && frm.contents.value=="")
		{ //alert(frm.content_type);
			flag=false;
			msg+="Contents  should not be empty \n";
			//frm.contents.focus();
		}
		if(edit!="Yes")
		{
			if(frm.background.value=="")
			{
				flag=false;
				msg+="Background should not be empty \n";
				//frm.background.focus();
			}
		}
		if((document.getElementById("user_hgt1").style.display== 'inline') && (frm.user_hgt.value<=100 || isNaN(frm.user_hgt.value)))
		{
			flag=false;
			msg+="Please Enter Height(Only numeric values are accepted) and height should be greater than 100\n";
		}
		if((document.getElementById("user_shgt1").style.display== 'inline') && (frm.user_shgt.value<=75 || isNaN(frm.user_shgt.value)))
		{
			flag=false;
			msg+="Please Enter slide in position(Only numeric values are accepted) and it should be greater than 75\n";
		}
		if(!(frm.positionC.checked ) && !(frm.positionS.checked) && !(frm.positionF.checked))
		{
				flag=false;
				msg+="Please Choose atleast one option of Ad Types \n";
		}
	}
		if(frm.positionF.checked)
		{ //alert(frm.contents.value);
			if(!frm.fix_content_type[0].checked && !frm.fix_content_type[1].checked)
			{ 
				flag=false;
				msg+="Please choose option Fix content type \n";
				//frm.content_type.focus();
			}//alert(frm.fix_html_contents.value);
			if(frm.fix_content_type[0].checked && frm.fix_txt_contents.value=="")
			{
				flag=false;
				msg+="Fix Contents should not be empty \n";
				//frm.contents.focus();
			}
			else if(frm.fix_content_type[1].checked && frm.fix_html_contents.value=="")
			{ //alert(frm.fix_html_contents.value);
				flag=false;
				msg+="Contents should not be empty \n";
				//frm.contents.focus();
			}
			if((document.getElementById("user_hgt1").style.display== 'inline') && (frm.user_hgt.value<=30 || isNaN(frm.user_hgt.value)))
			{
				flag=false;
				msg+="Please Enter Height(Only numeric values are accepted) and height should be greater than 30";
			}
			
			if((document.getElementById("user_width1").style.display== 'inline') && (frm.user_width.value<=100 || isNaN(frm.user_width.value)))
			{
				flag=false;
				msg+="Please Enter width(Only numeric values are accepted) and width should be greater than 100";
			}
		}
			if(frm.positionC.checked)
			{ 
				if(edit!="Yes")
				{
				
					if(!frm.flipped_default[0].checked && !frm.flipped_default[1].checked)
					{
						flag=false;
						msg+="Please choose option how to upload flipped image \n";
					} 
					else
					{ //flag = false;alert(document.getElementById("small_corner_img").value);
						if(frm.flipped_default[0].checked && (document.getElementById("small_corner_img").value==""))
						{  
							flag=false;				
							msg+="Please upload default flipped image \n";
							//frm.small_corner_img.focus();
						}
						if(frm.flipped_default[1].checked && (frm.small_corner_img2.value=="" || frm.small_corner_img2.value==undefined))
						{
							flag=false;				
							msg+="Please upload new flipped image \n";
							//frm.small_corner_img.focus();
						}
					}
				}
			}
			if(frm.positionC.checked && frm.play_sound[1].checked)
			{
				if(edit!="Yes")
				{
					if(!frm.sound_option[0].checked && !frm.sound_option[1].checked)
					{
						flag=false;
						msg+="Please choose upload sound option \n";
					}
					else if(frm.sound_option[0].checked && document.getElementById("default_sound_file").value=="")
					{
						flag=false;
						msg+="Please Upload default sound \n";
					}
					else if(frm.sound_option[1].checked)
					{
						if(document.getElementById("new_sound_file").value=="")
						{
							flag=false;
							msg+="Please upload new sound \n";
						}
					}
				}
			}
			if(frm.positionC.checked) 
			{
				if(frm.url.value=="")
				{
					flag=false;
					msg+="Please provide url \n";
					frm.url.focus();		
				}
				else if(!isValidURL(frm.url.value)) 
				{ 
					flag=false;
					msg+="Please enter a valid Web Address\n";
					frm.url.focus();
				}
			}
	

		if(flag)
			return true;
		else
		{
			alert(msg);
			return false;
		}	
	}
	function isValidURL(url){
    var RegExp = /^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/;
    if(RegExp.test(url)){
        return true;
    }else{
        return false;
    }
	}	

</script>
<script language="javascript">

function hndlsr(tid)
{
	var ad = document.getElementById('ad'+tid);
	var cp = document.getElementById('row'+tid);	
	if (ad.className == 'show')
	{
		ad.className = 'noshow';
		cp.className = 'backcolor1';
	}
	else 
	{
		ad.className = 'show';
		cp.className = 'backcolor3';
	}
		
}
function checking()
{	
	if(!(document.getElementById("positionC").checked))
	{
		if(document.getElementById("url_mandatory").style.display == 'block')
		{
			document.getElementById("url_mandatory").style.display = 'none';
			document.getElementById("url_unmandatory").style.display ='block';
			//document.getElementById("action").style.display = 'block';

		}
	}
	if(document.getElementById("positionC").checked)
	{
		if(document.getElementById("url_unmandatory").style.display =='block')
		{
			document.getElementById("url_mandatory").style.display = 'block';
			document.getElementById("url_unmandatory").style.display ='none';
			//document.getElementById("action").style.display = 'block';

		}
	}
	if(document.getElementById("positionC").checked && document.getElementById("positionS").checked && document.getElementById("positionF").checked )
	{//alert("F+S+C");background_upload_option
		document.getElementById("corder").style.display = 'block';
		document.getElementById("slide").style.display = 'block';
		document.getElementById("fix_pos").style.display = 'block';
		document.getElementById("floating").style.display = 'block';
		document.getElementById("sheight").style.display = 'block';
		document.getElementById("height").style.display = 'block';
		document.getElementById("width").style.display = 'block';
		document.getElementById("fixed").style.display = 'inline';
		document.getElementById("slidein").style.display = 'none';
		document.getElementById("background_color").style.display = 'block';
		document.getElementById("border").style.display = 'block';
		document.getElementById("border_width").style.display = 'block';
		//document.getElementById("action").style.display = 'block';
		document.getElementById("fix_content").style.display = 'block';
		document.getElementById("background_upload_option").style.display = 'block';
		document.getElementById("warn").style.display = 'block';
	}
	else if(document.getElementById("positionC").checked && !(document.getElementById("positionS").checked) && document.getElementById("positionF").checked )
	{//alert("F+C");fix_content
		document.getElementById("corder").style.display = 'block';
		document.getElementById("slide").style.display = 'none';
		document.getElementById("fix_pos").style.display = 'block';
		document.getElementById("floating").style.display = 'block';
		document.getElementById("sheight").style.display = 'block';
		document.getElementById("height").style.display = 'block';
		document.getElementById("width").style.display = 'block';
		document.getElementById("fixed").style.display = 'inline';
		document.getElementById("slidein").style.display = 'none';
		document.getElementById("background_color").style.display = 'block';
		document.getElementById("border").style.display = 'block';
		document.getElementById("border_width").style.display = 'block';
		//document.getElementById("action").style.display = 'block';
		document.getElementById("fix_content").style.display = 'block';
		document.getElementById("background_upload_option").style.display = 'block';
		document.getElementById("warn").style.display = 'block';
	}
	else if(!(document.getElementById("positionC").checked) && document.getElementById("positionS").checked && document.getElementById("positionF").checked )
	{//alert("S+F");
		document.getElementById("corder").style.display = 'none';
		document.getElementById("slide").style.display = 'block';
		document.getElementById("fix_pos").style.display = 'block';
		document.getElementById("floating").style.display = 'block';
		document.getElementById("sheight").style.display = 'block';
		document.getElementById("height").style.display = 'block';
		document.getElementById("width").style.display = 'block';
		document.getElementById("fixed").style.display = 'inline';
		document.getElementById("slidein").style.display = 'none';
		document.getElementById("background_color").style.display = 'block';
		document.getElementById("border").style.display = 'block';
		document.getElementById("border_width").style.display = 'block';
		//document.getElementById("action").style.display = 'block';
		document.getElementById("fix_content").style.display = 'block';
		document.getElementById("background_upload_option").style.display = 'block';
		document.getElementById("warn").style.display = 'block';
	}
	else if(document.getElementById("positionC").checked && document.getElementById("positionS").checked && !(document.getElementById("positionF").checked) )
	{//alert("C+S");
		document.getElementById("corder").style.display = 'block';
		document.getElementById("slide").style.display = 'block';
		document.getElementById("fix_pos").style.display = 'none';
		document.getElementById("floating").style.display = 'none';
		document.getElementById("sheight").style.display = 'block';
		document.getElementById("height").style.display = 'block';
		document.getElementById("width").style.display = 'block';
		document.getElementById("fixed").style.display = 'none';
		document.getElementById("slidein").style.display = 'inline';
		document.getElementById("background_color").style.display = 'block';
		document.getElementById("border").style.display = 'block';
		document.getElementById("border_width").style.display = 'block';
		//document.getElementById("action").style.display = 'block';
		document.getElementById("fix_content").style.display = 'none';
		document.getElementById("background_upload_option").style.display = 'block';
		document.getElementById("warn").style.display = 'block';
	}
	else if(!(document.getElementById("positionC").checked )&& document.getElementById("positionS").checked && !(document.getElementById("positionF").checked) )
	{//alert("S");
		document.getElementById("corder").style.display = 'none';
		document.getElementById("slide").style.display = 'block';
		document.getElementById("fix_pos").style.display = 'none';
		document.getElementById("floating").style.display = 'none';
		document.getElementById("sheight").style.display = 'block';
		document.getElementById("height").style.display = 'block';
		document.getElementById("width").style.display = 'block';
		document.getElementById("fixed").style.display = 'none';
		document.getElementById("slidein").style.display = 'inline';
		document.getElementById("background_color").style.display = 'block';
		document.getElementById("border").style.display = 'block';
		document.getElementById("border_width").style.display = 'block';
		//document.getElementById("action").style.display = 'block';
		document.getElementById("fix_content").style.display = 'none';
		document.getElementById("background_upload_option").style.display = 'block';
		document.getElementById("warn").style.display = 'none';
	}
	else if(!(document.getElementById("positionC").checked) && !(document.getElementById("positionS").checked) && document.getElementById("positionF").checked) 
	{//alert("F");
		document.getElementById("corder").style.display = 'none';
		document.getElementById("slide").style.display = 'none';
		document.getElementById("fix_pos").style.display = 'block';
		document.getElementById("floating").style.display = 'block';
		document.getElementById("sheight").style.display = 'none';
		document.getElementById("height").style.display = 'block';
		document.getElementById("width").style.display = 'block';
		document.getElementById("fixed").style.display = 'inline';
		document.getElementById("slidein").style.display = 'none';
		document.getElementById("background_color").style.display = 'block';
		document.getElementById("border").style.display = 'block';
		document.getElementById("border_width").style.display = 'block';
		//document.getElementById("action").style.display = 'none';
		document.getElementById("fix_content").style.display = 'block';
		document.getElementById("background_upload_option").style.display = 'block';
		document.getElementById("warn").style.display = 'none';
	}
	else if(document.getElementById("positionC").checked && !(document.getElementById("positionS").checked) && !(document.getElementById("positionF").checked) )
	{//alert("C");
		document.getElementById("corder").style.display = 'block';
		document.getElementById("slide").style.display = 'none';
		document.getElementById("fix_pos").style.display = 'none';
		document.getElementById("floating").style.display = 'none';
		document.getElementById("sheight").style.display = 'none';
		document.getElementById("height").style.display = 'none';
		document.getElementById("width").style.display = 'none';
		document.getElementById("fixed").style.display = 'none';
		document.getElementById("slidein").style.display = 'none';
		document.getElementById("background_color").style.display = 'none';
		document.getElementById("border").style.display = 'none';
		document.getElementById("border_width").style.display = 'none';
		//document.getElementById("action").style.display = 'block';
		document.getElementById("fix_content").style.display = 'none';
		document.getElementById("background_upload_option").style.display = 'none';
		document.getElementById("warn").style.display = 'none';
	}
	else if(!(document.getElementById("positionC")).checked && !(document.getElementById("positionS").checked) && !(document.getElementById("positionF").checked) )
	{//alert("NONE");
		document.getElementById("corder").style.display = 'block';
		document.getElementById("slide").style.display = 'none';
		document.getElementById("fix_pos").style.display = 'none';
		document.getElementById("floating").style.display = 'none';
		document.getElementById("sheight").style.display = 'none';
		document.getElementById("height").style.display = 'none';
		document.getElementById("width").style.display = 'none';
		document.getElementById("fixed").style.display = 'none';
		document.getElementById("slidein").style.display = 'none';
		document.getElementById("background_color").style.display = 'none';
		document.getElementById("border").style.display = 'none';
		document.getElementById("border_width").style.display = 'none';
		//document.getElementById("action").style.display = 'block';
		document.getElementById("fix_content").style.display = 'none';
		document.getElementById("background_upload_option").style.display = 'none';
		document.getElementById("warn").style.display = 'none';
	}

// if(document.getElementById("height")).checked)
// {
// document.getElementById("user_hgt").style.display = 'block';
// }



}


function show_textarea()
{ 

	document.getElementById(id=2).style.display = 'block';
	document.getElementById(id=1).style.display = 'none';
	document.getElementById("url_unmandatory").style.display = 'block';	document.getElementById("url_mandatory").style.display = 'none';
}



function show_html()
{

	document.getElementById(id=1).style.display = 'block';
	document.getElementById(id=2).style.display = 'none';
}
function show_html_fix()
{

	document.getElementById("html_fix").style.display = 'block';
	document.getElementById("fix_textarea").style.display = 'none';
}
function show_textarea_fix()
{ 

	document.getElementById("html_fix").style.display = 'none';
	document.getElementById("fix_textarea").style.display = 'block';
	
}
function show_height()
{ 

	document.getElementById("user_hgt1").style.display = 'inline';
	
}
function show_height1()
{ 

	document.getElementById("user_hgt1").style.display = 'none';
	
}
function show_sheight()
{ 

	document.getElementById("user_shgt1").style.display = 'inline';
	
}
function show_sheight1()
{ 

	document.getElementById("user_shgt1").style.display = 'none';
	
}
function show_width()
{ 

	document.getElementById("user_width1").style.display = 'inline';
	
}
function show_width1()
{ 

	document.getElementById("user_width1").style.display = 'none';
	
}

// function show_width_s()
// { 
// 
// 	document.getElementById("user_width_s1").style.display = 'inline';
// 	
// }
// function show_width_s1()
// { 
// 
// 	document.getElementById("user_width_s1").style.display = 'none';
// 	
// }

function opens(path)
{
 window.open('view_image.php?imgpath='+path,'abc', 'height=1000,width=1000,menubar=no,toolbar=no,resizable=yes, scrollbars=yes');
}
function showcode(id,process)
{
//alert(process);
	openwindow= window.open("getcode.php?id="+id+"&process="+process, "GETCODE",
		"'status=0,scrollbars=1',width=700,height=325,resizable=1");
	
	openwindow.moveTo(50,50);
}

</script>	
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script language="JavaScript" type="text/javascript">

function flipped()
{
	document.getElementById("flipped_upload_option").style.display = 'block';
}
function no_flipped()
{
	document.getElementById("flipped_upload_option").style.display = 'none';
	document.getElementById("flipped_default_upload").style.display = 'none';
	document.getElementById("flipped_new_upload").style.display = 'none';
	document.getElementById("show_image_div").style.display = 'none';
}
function show_corner()
{
	document.getElementById("corder").style.display = 'block';
	document.getElementById("slide").style.display = 'none';
	document.getElementById("url_mandatory").style.display='none';
	document.getElementById("url_unmandatory").style.display='block';
}
function hide_corner()
{
	document.getElementById("corder").style.display = 'none';
	document.getElementById("fix_pos").style.display = 'none';
	document.getElementById("slide").style.display = 'block';
	document.getElementById("url_mandatory").style.display='block';
	document.getElementById("url_unmandatory").style.display='none';
	document.getElementById("fix_pos").style.display = 'none';
}

function hide_corner1()
{
	document.getElementById("corder").style.display = 'none';
	document.getElementById("slide").style.display = 'block';
	document.getElementById("url_mandatory").style.display='block';
	document.getElementById("url_unmandatory").style.display='none';
	document.getElementById("fix_pos").style.display = 'block';
}
function show_default_background_upload()
{
	document.getElementById("background_default_upload").style.display = 'block';
	document.getElementById("background_new_upload").style.display = 'none';
}
function show_background_upload()
{
	document.getElementById("background_new_upload").style.display = 'block';
	document.getElementById("background_default_upload").style.display = 'none';
}
function show_default_backgroundphp(field)
{
	// alert("Hi");
	window.open('default_background.php?field='+field,'abc','height=500,width=500,menubar=no,toolbar=no,resizable=yes, scrollbars=yes');
}
function setBackgroundFile(field, file_name)
{
	document.getElementById("background").value=file_name;
}
function show_default_flipped_upload()
{
	document.getElementById("flipped_default_upload").style.display = 'block';
	document.getElementById("flipped_new_upload").style.display = 'none';
}
function show_flipped_upload()
{
	document.getElementById("flipped_new_upload").style.display = 'block';
	document.getElementById("flipped_default_upload").style.display = 'none';
}
function show_default_flippedphp(field)
{
	// alert("Hi");
	window.open('default_flipped.php?field='+field,'abc','height=500,width=500,menubar=no,toolbar=no,resizable=yes, scrollbars=yes');
}
function setFlippedFile(field, file_name)
{
	document.getElementById("small_corner_img").value=file_name;
}
function show_sound_file(field)
{
	 //alert("Hi");
	
	window.open('sound_file.php?field='+field,'abc','height=500,width=500,menubar=no,toolbar=no,resizable=yes, scrollbars=yes');
}
function setSoundFile(field, file_name ,sound_id)
{
	document.getElementById("default_sound_file").value=file_name;
	document.getElementById("sound_id_hid").value=sound_id;
	
}
function playsound_yes()
{
	document.getElementById("sound_upload").style.display = 'block';
}
function playsound_no()
{
	document.getElementById("sound_upload").style.display = 'none';
	document.getElementById("sound_new_upload").style.display = 'none';
	document.getElementById("sound_default_upload").style.display = 'none';
 	document.getElementById("show_sound_edit_div").style.display = 'none';
	
}
function show_default_sound_upload()
{
	document.getElementById("sound_default_upload").style.display = 'block';
	document.getElementById("sound_new_upload").style.display = 'none';
}
function show_new_sound_upload()
{
	document.getElementById("sound_new_upload").style.display = 'block';
	document.getElementById("sound_default_upload").style.display = 'none';
}
function customSave(id, content)
{
		
}

////////////////////////////////////////////////////
///////Javascript for Split test starts here/////////
////////////////////////////////////////////////////

function show_split_test_duration_option_div(val)
{
	if(val==true)
	{
		document.getElementById("split_test_duration_option_div").style.display='block';
	}	
	else
	{
		document.getElementById("split_test_duration_option_div").style.display='none';
		document.getElementById("split_test_duration_div_for_days").style.display='none';
		document.getElementById("split_test_duration_div_for_hits").style.display='none';
	}
}
function show_split_test_duration_div(val)
{
	if(val.value=='D')
	{
		document.getElementById("split_test_duration_div_for_days").style.display='block';
		document.getElementById("split_test_duration_div_for_hits").style.display='none';
	}
	else if(val.value=='H')
	{
		document.getElementById("split_test_duration_div_for_hits").style.display='block';
		document.getElementById("split_test_duration_div_for_days").style.display='none';
	}
}
function show_split_test_duration_div_for_value(val)
{
	if(val=='D')
	{
		document.getElementById("split_test_duration_div_for_days").style.display='block';
		document.getElementById("split_test_duration_div_for_hits").style.display='none';
	}
	else if(val=='H')
	{
		document.getElementById("split_test_duration_div_for_hits").style.display='block';
		document.getElementById("split_test_duration_div_for_days").style.display='none';
	}
}

function validate_split_test_form(frm)
{
	flag = true;
//alert(frm.S_campaign_list[1].value)	
	msg="***********************************************************\n";
	msg+="Following errors has orrured \n\n\n";
	if(frm.test_name.value=="")
	{
		msg+="Test name should not be kept empty \n";
		flag = false;
	}
	if(frm.S_campaign_list.value==-1)
	{
		msg+="Please select some campaigns \n";
		flag = false;
	}
	if(frm.split_test_duration_checkbox.checked===true)
	{//alert(isNaN(frm.spilt_duration_days_inputbox.value));
		if(!frm.duration_days[0].checked && !frm.duration_days[1].checked)
		{
			msg+="Please select duration type\n";
			flag = false;
		}
		else if((frm.duration_days[0].checked || frm.duration_days[1].checked) && frm.spilt_duration_days_inputbox.value=="" &&  frm.spilt_duration_hits_inputbox.value=="")
		{
			msg+="Please enter duration \n";
			flag = false;
		}
		if(frm.duration_days[0].checked || frm.duration_days[1].checked)
		{
			if(frm.spilt_duration_days_inputbox.value!="" && isNaN(frm.spilt_duration_days_inputbox.value))
			{
				msg+="Please enter valid duration (days) \n";
				flag = false;
			}
			if(frm.spilt_duration_hits_inputbox.value!="" && isNaN(frm.spilt_duration_hits_inputbox.value))
			{
				msg+="Please enter valid No. of hits \n";
				flag = false;
			}
		}
	}
	
	
	msg+="\n***********************************************************";
	if(flag)
		return flag;
	else
	{
		alert(msg);
		return flag;
	}

}

function submit_form(id)
{

var var1 = 'make_winner_form'+id

	 document.getElementById(var1).submit();
}

////////////////////////////////////////////////////
///////Javascript for Split test ends here/////////
////////////////////////////////////////////////////

</script>
<script language="javascript" type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
    // Notice: The simple theme does not use all options some of them are limited to the advanced theme
    tinyMCE.init({
        elements : "contents",
        mode : "exact",
   theme : "advanced",
   plugins : "table",
   theme_advanced_buttons1_add : "fontselect,fontsizeselect",
   theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
   theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
   theme_advanced_buttons3_add_before : "tablecontrols,separator",
   table_styles : "Header 1=header1;Header 2=header2;Header 3=header3",
   table_cell_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Cell=tableCel1",
   table_row_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1",
   table_cell_limit : 100,
   table_row_limit : 5,
   table_col_limit : 5,
   theme_advanced_toolbar_location : "top",
   theme_advanced_toolbar_align : "left",
   theme_advanced_path_location : "bottom",
   plugin_insertdate_dateFormat : "%Y-%m-%d",
   plugin_insertdate_timeFormat : "%H:%M:%S",
   extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
        debug : false        
        
    });
    tinyMCE.init({
        elements : "fix_html_contents",
        theme : "advanced",
        mode : "exact",
        plugins : "table",
   theme_advanced_buttons1_add : "fontselect,fontsizeselect",
   theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
   theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
   theme_advanced_buttons3_add_before : "tablecontrols,separator",
   table_styles : "Header 1=header1;Header 2=header2;Header 3=header3",
   table_cell_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Cell=tableCel1",
   table_row_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1",
   table_cell_limit : 100,
   table_row_limit : 5,
   table_col_limit : 5,
   theme_advanced_toolbar_location : "top",
   theme_advanced_toolbar_align : "left",
   theme_advanced_path_location : "bottom",
   plugin_insertdate_dateFormat : "%Y-%m-%d",
   plugin_insertdate_timeFormat : "%H:%M:%S",
   extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
        debug : false                
        
    });
</script>