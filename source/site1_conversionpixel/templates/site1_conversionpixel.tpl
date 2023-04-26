window.onload = function(){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if (xhttp.readyState == 4 && xhttp.status == 200){
			console.log(xhttp.responseText);
		}
	};
	xhttp.open("POST", window.location.protocol + "//fasttrk.net/conversionpixel/", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("ip={$ip}&&splitid={$split_id}&type={$type}");
}
