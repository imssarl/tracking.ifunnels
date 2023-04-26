<html>
<body>
		<form method="POST" action="">
			host <input type="text" value="<?php echo $_POST['host']?>" name="host" style="width:800px">
			<input type="submit" value="Go">
		</form>
		<hr />
		<br />
<?php 

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, ( $_POST['host'] ) ? $_POST['host'] : 'http://www.google.com.by/' );
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0' );
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		"Cache-Control: max-age=0",
		"Connection: keep-alive",
		"Keep-Alive: 300",
		"Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
		"Accept-Language: en-us,en;q=0.5",
	));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	$responce = curl_exec($ch);
	$errno = curl_errno($ch);
	$error = curl_error($ch);
	curl_close ($ch);

	if ($errno) {
		echo $error;
	}
	echo $responce;
?>

</body>
</html>