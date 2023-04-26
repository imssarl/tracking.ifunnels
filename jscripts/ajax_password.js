var request5 = null;

	try {

		request5 = new XMLHttpRequest();

	} catch (trymicrosoft) {

		try {

			request5 = new ActiveXObject("Msxml2.XMLHTTP");

		} catch (othermicrosoft) {

			try {

				request5 = new ActiveXObject("Microsoft.XMLHTTP");

			} catch (failed) {

				request5 = null;

			}

		}

	}
	/* code for get posts*/
	function setPost(opt,bid)
	{
		//alert(am);
		cat_id=opt.value;
		var url = "set_post.php"; 
		var params="cat_id="+cat_id+"&blog_id="+bid;
		request5.open("POST", url, true);
		//Send the proper header information along with the request
		request5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request5.setRequestHeader("Content-length", params.length);
		request5.setRequestHeader("Connection", "close");
		request5.onreadystatechange =  updatePost;
		request5.send(params);
		
	}


	function updatePost()
	{
		if (request5.readyState == 4&& request5.status == 200) 
		{
			var changeoption = request5.responseText;
			if (changeoption) 
		       {
			         var changeoption = request5.responseText;
				  var content = changeoption.split('##');
			         document.getElementById("posts").innerHTML =content[2];
				document.getElementById("sub_cat").innerHTML =content[1];
		        }
		}
	}




/* code for get Comment*/
	function setCom(c_id,bid)
	{
//		alert(c_id);
		post_id=c_id.value;
		var url = "set_comment.php"; 
		var params="post_id="+post_id+"&blog_id="+bid;
		request5.open("POST", url, true);
		//Send the proper header information along with the request
		request5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request5.setRequestHeader("Content-length", params.length);
		request5.setRequestHeader("Connection", "close");
		request5.onreadystatechange =  updateCom;
		request5.send(params);
		
	}


	function updateCom()
	{
		if (request5.readyState == 4&& request5.status == 200) 
		{
			var changeoption = request5.responseText;
			if (changeoption) 
		       {
			         var changeoption = request5.responseText;
			         document.getElementById("post_comment").innerHTML =changeoption;
		       }
		}
	}
/* End code*/


/* Code for set master block*/

	function check(b_id,c_id,check_id)
	{
		if(confirm('Do you want to set this Blog as a master blog for this category?'))
		{	
			setMas(b_id,c_id);
		}
		else
		{
			b_id.checked='';
			
				document.getElementById(check_id).checked="checked";
			
		}
	}

	function setMas(b_id,c_id)
		{
			//alert(b_id.value);
			//alert(c_id);
			plog_id=b_id.value;
			var url = "set_master.php"; 
			var params="blog_id="+plog_id+"&c_id="+c_id;
			request5.open("POST", url, true);
			//Send the proper header information along with the request
			request5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			request5.setRequestHeader("Content-length", params.length);
			request5.setRequestHeader("Connection", "close");
			request5.onreadystatechange =  updateMas;
			request5.send(params);
		
		}


	function updateMas()
	{
		if (request5.readyState == 4&& request5.status == 200) 
		{
			var changeoption = request5.responseText;
			
				
			         var changeoption = request5.responseText;
				
				alert('This blog has been set as a Master blog.');
			        
		       
		}
	}

/* End*/


